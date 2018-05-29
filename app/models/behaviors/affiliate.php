<?php
/**
 * Group Deal
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    GroupDeal
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
/**
 * CounterCacheHabtmBehavior - add counter cache support for HABTM relations
 *
 * Based on CounterCacheBehavior by Derick Ng aka dericknwq
 *
 * @see http://bakery.cakephp.org/articles/view/counter-cache-behavior-for-habtm-relations
 * @author Yuri Pimenov aka Danaki (http://blog.gate.lv)
 * @version 2009-05-28
 */
class AffiliateBehavior extends ModelBehavior
{
    function afterSave($model, $created) 
    {
        if (Configure::read('affiliate.is_enabled')) {
            $affiliate_model = Cache::read('affiliate_model', 'affiliatetype');
            if (array_key_exists($model->name, $affiliate_model)) {
                if ($created) {
                    $this->__createAffiliate($model);
                } else {
                    $this->__updateAffiliate($model);
                }
            }
        }
    }
    function __createAffiliate($model) 
    {
        App::import('Core', 'Cookie');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $cookie = new CookieComponent($collection);
        $referrer = $cookie->read('referrer');
        $this->User = $this->__getparentClass('User');
        $affiliate_model = Cache::read('affiliate_model', 'affiliatetype');
        if (((!empty($referrer['refer_id'])) || (!empty($model->data['User']['referred_by_user_id']))) && ($model->name == 'User')) {
            if (empty($referrer['refer_id'])) {
                $referrer['refer_id'] = $model->data['User']['referred_by_user_id'];
            }
            // update refer_id
            $data['User']['referred_by_user_id'] = $referrer['refer_id'];
            $data['User']['id'] = $model->id;
            $this->User->save($data);
            // referred count update
            $this->User->updateAll(array(
                'User.referred_by_user_count' => 'User.referred_by_user_count + ' . '1'
            ) , array(
                'User.id' => $referrer['refer_id']
            ));
            if ($this->__CheckAffiliateUSer($referrer['refer_id'])) {
                $this->AffiliateType = $this->__getparentClass('AffiliateType');
                $affiliateType = $this->AffiliateType->find('first', array(
                    'conditions' => array(
                        'AffiliateType.id' => $affiliate_model['User']
                    ) ,
                    'fields' => array(
                        'AffiliateType.id',
                        'AffiliateType.commission',
                        'AffiliateType.affiliate_commission_type_id'
                    ) ,
                    'recursive' => -1
                ));
                $affiliate_commision_amount = 0;
                if (!empty($affiliateType)) {
                    if (($affiliateType['AffiliateType']['affiliate_commission_type_id'] == ConstAffiliateCommissionType::Percentage)) {
                        $affiliate_commision_amount = 0.0; //($model->data['DealUser']['commission_amount'] * $affiliateType['AffiliateType']['commission']) / 100;
                        
                    } else {
                        $affiliate_commision_amount = $affiliateType['AffiliateType']['commission'];
                    }
                }
                // set affiliate data
                $affiliate['Affiliate']['class'] = 'User';
                $affiliate['Affiliate']['foreign_id'] = $model->id;
                $affiliate['Affiliate']['affiliate_type_id'] = $affiliate_model['User'];
                $affiliate['Affiliate']['affliate_user_id'] = $referrer['refer_id'];
                $affiliate['Affiliate']['affiliate_status_id'] = ConstAffiliateStatus::PipeLine;
                $affiliate['Affiliate']['commission_holding_start_date'] = date('Y-m-d');
                $affiliate['Affiliate']['commission_amount'] = $affiliate_commision_amount;
                $this->__saveAffiliate($affiliate);
                $cookie->delete('referrer');
            }
        } else if ($model->name == 'DealUser') {
            $this->DealUser = $this->__getparentClass('DealUser');
            if (empty($model->data['DealUser']['referred_by_user_id'])) {
                if (isset($model->data['DealUser']['user_id']) && !empty($model->data['DealUser']['user_id'])) {
                    $user = $this->User->find('first', array(
                        'conditions' => array(
                            'User.id' => $model->data['DealUser']['user_id']
                        ) ,
                        'fields' => array(
                            'User.id',
                            'User.username',
                            'User.referred_by_user_id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($user['User']['referred_by_user_id'])) {
                        if (Configure::read('affiliate.commission_on_every_job_purchase')) {
                            $referrer['refer_id'] = $user['User']['referred_by_user_id'];
                        } else {
                            $dealusers = $this->DealUser->find('count', array(
                                'conditions' => array(
                                    'DealUser.id <>' => $model->id,
                                    'DealUser.user_id' => $model->data['DealUser']['user_id'],
                                    'DealUser.referred_by_user_id' => $user['User']['referred_by_user_id'],
                                ) ,
                            ));
                            if ($dealusers < 1) $referrer['refer_id'] = $user['User']['referred_by_user_id'];
                        }
                    }
                }
            } else {
                $referrer['refer_id'] = $model->data['DealUser']['referred_by_user_id'];
            }
            if (!empty($referrer['refer_id']) && $this->__CheckAffiliateUSer($referrer['refer_id'])) {
                $this->AffiliateType = $this->__getparentClass('AffiliateType');
                $affiliateType = $this->AffiliateType->find('first', array(
                    'conditions' => array(
                        'AffiliateType.id' => $affiliate_model['DealUser']
                    ) ,
                    'fields' => array(
                        'AffiliateType.id',
                        'AffiliateType.commission',
                        'AffiliateType.affiliate_commission_type_id'
                    ) ,
                    'recursive' => -1
                ));
                $affiliate_commision_amount = 0;
                $admin_commision_amount = 0;
                if (!empty($affiliateType)) {
                    $this->Deal = $this->__getparentClass('Deal');
                    $dealuser_condition = $dealuser_subdeal_condition = array();
                    $dealuser_condition['Deal.id'] = $model->data['DealUser']['deal_id'];
                    if ($model->data['DealUser']['sub_deal_id']) {
                        $dealuser_subdeal_condition['SubDeal.id'] = $model->data['DealUser']['sub_deal_id'];
                    }
                    $deal = $this->Deal->find('first', array(
                        'conditions' => $dealuser_condition,
                        'contain' => array(
                            'SubDeal' => array(
                                'conditions' => $dealuser_subdeal_condition,
                                'fields' => array(
                                    'SubDeal.id',
                                    'SubDeal.discount_amount',
                                    'SubDeal.commission_percentage',
                                ) ,
                            )
                        ) ,
                        'fields' => array(
                            'Deal.id',
                            'Deal.discount_amount',
                            'Deal.commission_percentage',
                        ) ,
                        'recursive' => 2
                    ));
                    if ($model->data['DealUser']['sub_deal_id']) {
                        $deal['Deal']['commission_percentage'] = $deal['SubDeal'][0]['commission_percentage'];
                        $deal['Deal']['discount_amount'] = $deal['SubDeal'][0]['discount_amount'];
                    }
                    $deal_commission = ($deal['Deal']['discount_amount']*$deal['Deal']['commission_percentage']) /100;
                    if (($affiliateType['AffiliateType']['affiliate_commission_type_id'] == ConstAffiliateCommissionType::Percentage)) {
                        $affiliate_commision_amount = ($deal_commission*$affiliateType['AffiliateType']['commission']) /100;
                    } else {
                        $affiliate_commision_amount = $affiliateType['AffiliateType']['commission'];
                    }
                    $admin_commision_amount = $deal_commission-$affiliate_commision_amount;
                }
                $this->DealUser->updateAll(array(
                    'DealUser.referred_by_user_id' => $referrer['refer_id'],
                    'DealUser.admin_commission_amount' => $admin_commision_amount,
                    'DealUser.affiliate_commission_amount' => $affiliate_commision_amount
                ) , array(
                    'DealUser.id' => $model->id
                ));
                // set affiliate data
                $affiliate['Affiliate']['class'] = 'DealUser';
                $affiliate['Affiliate']['foreign_id'] = $model->id;
                $affiliate['Affiliate']['affiliate_type_id'] = $affiliate_model['DealUser'];
                $affiliate['Affiliate']['affliate_user_id'] = $referrer['refer_id'];
                $affiliate['Affiliate']['affiliate_status_id'] = ConstAffiliateStatus::Pending;
                $affiliate['Affiliate']['commission_amount'] = $affiliate_commision_amount;
                $this->__saveAffiliate($affiliate);
                $cookie->delete('referrer');
                $this->User->updateAll(array(
                    'User.referred_purchase_count' => 'User.referred_purchase_count + ' . '1'
                ) , array(
                    'User.id' => $referrer['refer_id']
                ));
                $this->User->updateAll(array(
                    'User.affiliate_refer_purchase_count' => 'User.affiliate_refer_purchase_count + ' . '1'
                ) , array(
                    'User.id' => $model->data['DealUser']['user_id']
                ));
                $conditions['Affiliate.class'] = 'DealUser';
                $conditions['Affiliate.foreign_id'] = $model->id;
                $affliates = $this->__findAffiliate($conditions);
                if (!empty($affliates) && empty($affliates['DealUser']['deal_id'])) {
                    $deal_id = $model->data['DealUser']['deal_id'];
                } else {
                    $deal_id = $affliates['DealUser']['deal_id'];
                }
                $this->DealUser->Deal->updateAll(array(
                    'Deal.referred_purchase_count' => 'Deal.referred_purchase_count + ' . '1'
                ) , array(
                    'Deal.id' => $deal_id
                ));
            }
        }
    }
    // Can be optimized. //
    function __updateAffiliate($model) 
    {
        $conditions = array();
        if ($model->name == 'DealUser' && !empty($model->data['DealUser']['is_canceled'])) {
            $conditions['Affiliate.class'] = 'DealUser';
            $conditions['Affiliate.foreign_id'] = $model->id;
            $affliates = $this->__findAffiliate($conditions);
            if (!empty($affliates) && empty($affliates['DealUser']['referred_by_user_id'])) {
                $this->DealUser = $this->__getparentClass('DealUser');
                $deal_user = $this->DealUser->find('first', array(
                    'conditions' => array(
                        'DealUser.id' => $affliates['Affiliate']['foreign_id']
                    ) ,
                    'fields' => array(
                        'DealUser.id',
                        'DealUser.deal_id',
                        'DealUser.referred_by_user_id',
                    ) ,
                    'recursive' => -1
                ));
                $affliates['DealUser']['referred_by_user_id'] = $deal_user['DealUser']['referred_by_user_id'];
            }
            if (!empty($affliates['DealUser']['referred_by_user_id'])) {
                $affiliate['Affiliate']['id'] = $affliates['Affiliate']['id'];
                $affiliate['Affiliate']['affiliate_status_id'] = ConstAffiliateStatus::Canceled;
                $this->User = $this->__getparentClass('User');
                $this->User->updateAll(array(
                    'User.total_commission_canceled_amount' => 'User.total_commission_canceled_amount + ' . $affliates['Affiliate']['commission_amount']
                ) , array(
                    'User.id' => $affliates['Affiliate']['affliate_user_id']
                ));
                $this->__saveAffiliate($affiliate);
            }
        } else if ($model->name == 'Deal' && !empty($model->data['Deal']['deal_status_id']) && ($model->data['Deal']['deal_status_id'] == ConstDealStatus::Canceled || $model->data['Deal']['deal_status_id'] == ConstDealStatus::Refunded || $model->data['Deal']['deal_status_id'] == ConstDealStatus::Closed)) {
            $this->DealUser = $this->__getparentClass('DealUser');
            $deal_users = $this->DealUser->find('all', array(
                'conditions' => array(
                    'DealUser.deal_id' => $model->data['Deal']['id']
                ) ,
                'fields' => array(
                    'DealUser.id',
                    'DealUser.deal_id',
                    'DealUser.referred_by_user_id',
                ) ,
                'recursive' => -1
            ));
            foreach($deal_users as $deal_user) {
                $conditions['Affiliate.class'] = 'DealUser';
                $conditions['Affiliate.foreign_id'] = $deal_user['DealUser']['id'];
                $affliates = $this->__findAffiliate($conditions);
                if (!empty($affliates) && empty($affliates['DealUser']['referred_by_user_id'])) {
                    $affliates['DealUser']['referred_by_user_id'] = $deal_user['DealUser']['referred_by_user_id'];
                }
                if (!empty($affliates['DealUser']['referred_by_user_id'])) {
                    $affiliate['Affiliate']['id'] = $affliates['Affiliate']['id'];
                    if ($model->data['Deal']['deal_status_id'] == ConstDealStatus::Closed) {
                        $affiliate['Affiliate']['commission_holding_start_date'] = date('Y-m-d');
                        $affiliate['Affiliate']['affiliate_status_id'] = ConstAffiliateStatus::PipeLine;
                    } else {
                        $affiliate['Affiliate']['affiliate_status_id'] = ConstAffiliateStatus::Canceled;
                    }
                    $this->User = $this->__getparentClass('User');
                    $this->__saveAffiliate($affiliate);
                    if ($model->data['Deal']['deal_status_id'] != ConstDealStatus::Closed) {
                        $this->User->updateAll(array(
                            'User.total_commission_canceled_amount' => 'User.total_commission_canceled_amount + ' . $affliates['Affiliate']['commission_amount']
                        ) , array(
                            'User.id' => $affliates['Affiliate']['affliate_user_id']
                        ));
                    }
                }
            }
        }
    }
    function __saveAffiliate($data) 
    {
        $this->Affiliate = $this->__getparentClass('Affiliate');
        if (!isset($data['Affiliate']['id'])) {
            $this->Affiliate->create();
            $this->Affiliate->AffiliateUser->updateAll(array(
                'AffiliateUser.total_commission_pending_amount' => 'AffiliateUser.total_commission_pending_amount + ' . $data['Affiliate']['commission_amount']
            ) , array(
                'AffiliateUser.id' => $data['Affiliate']['affliate_user_id']
            ));
        }
        $this->Affiliate->save($data);
    }
    function __findAffiliate($condition) 
    {
        $this->Affiliate = $this->__getparentClass('Affiliate');
        $affiliate = $this->Affiliate->find('first', array(
            'conditions' => $condition,
            'recursive' => -1
        ));
        return $affiliate;
    }
    function __CheckAffiliateUSer($refer_user_id) 
    {
        $this->User = $this->__getparentClass('User');
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $refer_user_id
            ) ,
            'recursive' => -1
        ));
        if (!empty($user) && ($user['User']['is_affiliate_user'])) {
            return true;
        }
        return false;
    }
    function __getparentClass($parentClass) 
    {
        App::import('model', $parentClass);
        return new $parentClass;
    }
}
?>