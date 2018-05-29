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
class AffiliatesController extends AppController
{
    public $name = 'Affiliates';
    public $uses = array(
        'Affiliate',
        'User'
    );
	public $permanentCacheAction = array(
		'user' => array(
			'add',
			'edit',
			'update',
			'delete',
		) ,
		'public' => array(
			'index',
			'search',
		    'view'
		) ,
		'admin' => array(
			'admin_add',
			'admin_edit',
			'admin_update',
			'admin_delete',
		) ,
        'is_view_count_update' => true
    );
    public function beforeFilter()
    {
        if (!Configure::read('affiliate.is_enabled') && $this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function index()
    {
        $this->pageTitle = __l('Affiliate');
        $conditions = array();
        $conditions['Affiliate.affliate_user_id'] = $this->Auth->user('id');
        $conditions['Affiliate.affiliate_status_id'] = array(
            ConstAffiliateStatus::PipeLine,
            ConstAffiliateStatus::Completed
        );
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['Affiliate']['affiliate_status_id'] = $this->request->params['named']['filter_id'];
        }
        if (!empty($this->request->data['Affiliate']['affiliate_status_id'])) {
            switch ($this->request->data['Affiliate']['affiliate_status_id']) {
                case ConstAffiliateStatus::PipeLine:
                    $conditions['Affiliate.affiliate_status_id'] = ConstAffiliateStatus::PipeLine;
                    $this->pageTitle.= __l(' - Pipeline');
                    break;

                case ConstAffiliateStatus::Completed:
                    $conditions['Affiliate.affiliate_status_id'] = ConstAffiliateStatus::Completed;
                    $this->pageTitle.= __l(' - Completed');
                    break;
            }
            $this->request->params['named']['filter_id'] = $this->request->data['Affiliate']['filter_id'];
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Affiliate.created) <= '] = 0;
            $this->pageTitle.= __l(' -  today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Affiliate.created) <= '] = 7;
            $this->pageTitle.= __l(' -  in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Affiliate.created) <= '] = 30;
            $this->pageTitle.= __l(' -  in this month');
        }
        $this->Affiliate->recursive = 1;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Affiliate.id' => 'desc'
            )
        );
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'User.is_affiliate_user'
            ) ,
            'recursive' => 1
        ));
        $this->set('user', $user);
        $this->set('affiliates', $this->paginate());
    }
    public function stats()
    {
        $this->pageTitle = __l('Stats');
        if (Configure::read('affiliate.is_enabled')) {
            $this->loadModel('Affiliate');
            $this->loadModel('AffiliateCashWithdrawal');
        }
        $periods = array(
            'day' => array(
                'display' => __l('Today') ,
                'conditions' => array(
                    'TO_DAYS(NOW()) - TO_DAYS(created) <= ' => 0,
                )
            ) ,
            'week' => array(
                'display' => __l('This week') ,
                'conditions' => array(
                    'TO_DAYS(NOW()) - TO_DAYS(created) <= ' => 7,
                )
            ) ,
            'month' => array(
                'display' => __l('This month') ,
                'conditions' => array(
                    'TO_DAYS(NOW()) - TO_DAYS(created) <= ' => 30,
                )
            ) ,
            'total' => array(
                'display' => __l('Total') ,
                'conditions' => array()
            )
        );
        if (Configure::read('affiliate.is_enabled')) {
            $models[] = array(
                'Affiliate' => array(
                    'display' => __l('Affiliate') ,
                    'link' => array(
                        'controller' => 'affiliates',
                        'action' => 'index'
                    ) ,
                    'colspan' => 2
                )
            );
            $models[] = array(
                'Affiliate' => array(
                    'display' => __l('Pipeline') ,
                    'link' => array(
                        'controller' => 'affiliates',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateStatus::PipeLine
                    ) ,
                    'conditions' => array(
                        'Affiliate.affiliate_status_id' => ConstAffiliateStatus::PipeLine,
                        'Affiliate.affliate_user_id' => $this->Auth->user('id')
                    ) ,
                    'alias' => 'AffiliatePipeLine',
                    'type' => 'cCurrency',
                    'isSub' => 'Affiliate'
                )
            );
            $models[] = array(
                'Affiliate' => array(
                    'display' => __l('Completed') ,
                    'link' => array(
                        'controller' => 'affiliates',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateStatus::Completed
                    ) ,
                    'conditions' => array(
                        'Affiliate.affiliate_status_id' => ConstAffiliateStatus::Completed,
                        'Affiliate.affliate_user_id' => $this->Auth->user('id')
                    ) ,
                    'alias' => 'AffiliateCompleted',
                    'type' => 'cCurrency',
                    'isSub' => 'Affiliate'
                )
            );
            $models[] = array(
                'AffiliateCashWithdrawal' => array(
                    'display' => __l('Affiliate Withdaw Request') ,
                    'colspan' => 5
                )
            );
            $models[] = array(
                'AffiliateCashWithdrawal' => array(
                    'display' => __l('Pending') ,
                    'link' => array(
                        'controller' => 'affiliate_cash_withdrawals',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateCashWithdrawalStatus::Pending
                    ) ,
                    'conditions' => array(
                        'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Pending,
                        'AffiliateCashWithdrawal.user_id' => $this->Auth->user('id')
                    ) ,
                    'alias' => 'AffiliateCashWithdrawalPending',
                    'type' => 'cCurrency',
                    'isSub' => 'AffiliateCashWithdrawal'
                )
            );
            $models[] = array(
                'AffiliateCashWithdrawal' => array(
                    'display' => __l('Approved') ,
                    'link' => array(
                        'controller' => 'affiliate_cash_withdrawals',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateCashWithdrawalStatus::Approved
                    ) ,
                    'conditions' => array(
                        'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Approved,
                        'AffiliateCashWithdrawal.user_id' => $this->Auth->user('id')
                    ) ,
                    'alias' => 'AffiliateCashWithdrawalApproved',
                    'type' => 'cCurrency',
                    'isSub' => 'AffiliateCashWithdrawal'
                )
            );
            $models[] = array(
                'AffiliateCashWithdrawal' => array(
                    'display' => __l('Rejected') ,
                    'link' => array(
                        'controller' => 'affiliate_cash_withdrawals',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateCashWithdrawalStatus::Rejected
                    ) ,
                    'conditions' => array(
                        'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Rejected,
                        'AffiliateCashWithdrawal.user_id' => $this->Auth->user('id')
                    ) ,
                    'alias' => 'AffiliateCashWithdrawalReject',
                    'type' => 'cCurrency',
                    'isSub' => 'AffiliateCashWithdrawal'
                )
            );
            $models[] = array(
                'AffiliateCashWithdrawal' => array(
                    'display' => __l('Paid') ,
                    'link' => array(
                        'controller' => 'affiliate_cash_withdrawals',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateCashWithdrawalStatus::Success
                    ) ,
                    'conditions' => array(
                        'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Success,
                        'AffiliateCashWithdrawal.user_id' => $this->Auth->user('id')
                    ) ,
                    'alias' => 'AffiliateCashWithdrawalSuccess',
                    'type' => 'cCurrency',
                    'isSub' => 'AffiliateCashWithdrawal'
                )
            );
            $models[] = array(
                'AffiliateCashWithdrawal' => array(
                    'display' => __l('Payment Failure') ,
                    'link' => array(
                        'controller' => 'affiliate_cash_withdrawals',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateCashWithdrawalStatus::Failed
                    ) ,
                    'conditions' => array(
                        'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Failed,
                        'AffiliateCashWithdrawal.user_id' => $this->Auth->user('id')
                    ) ,
                    'alias' => 'AffiliateCashWithdrawalFail',
                    'type' => 'cCurrency',
                    'isSub' => 'AffiliateCashWithdrawal'
                )
            );
        }
        foreach($models as $unique_model) {
            foreach($unique_model as $model => $fields) {
                foreach($periods as $key => $period) {
                    $conditions = $period['conditions'];
                    if (!empty($fields['conditions'])) {
                        $conditions = array_merge($periods[$key]['conditions'], $fields['conditions']);
                    }
                    $aliasName = !empty($fields['alias']) ? $fields['alias'] : $model;
                    if ($model == 'Affiliate') {
                        $AffiliateStatus = $this->Affiliate->find('first', array(
                            'conditions' => $conditions,
                            'fields' => array(
                                'SUM(Affiliate.commission_amount) as commission_amount'
                            ) ,
                            'recursive' => -1
                        ));
                        $this->set($aliasName . $key, $AffiliateStatus['0']['commission_amount']);
                    } else if ($model == 'AffiliateCashWithdrawal') {
                        $AffiliateCashWithdrawalStatus = $this->AffiliateCashWithdrawal->find('first', array(
                            'conditions' => $conditions,
                            'fields' => array(
                                'SUM(AffiliateCashWithdrawal.amount) as amount'
                            ) ,
                            'recursive' => -1
                        ));
                        $this->set($aliasName . $key, $AffiliateCashWithdrawalStatus['0']['amount']);
                    } else {
                        $new_periods = $period;
                        foreach($new_periods['conditions'] as $p_key => $p_value) {
                            unset($new_periods['conditions'][$p_key]);
                            $new_periods['conditions'][str_replace('created', $model . '.created', $p_key) ] = $p_value;
                        }
                        $conditions = $new_periods['conditions'];
                        if (!empty($fields['conditions'])) {
                            $conditions = array_merge($new_periods['conditions'], $fields['conditions']);
                        }
                        $this->set($aliasName . $key, $this->{$model}->find('count', array(
                            'conditions' => $conditions,
                        )));
                    }
                }
            }
        }
        $this->set(compact('periods', 'models'));
    }
    public function admin_index()
    {
        $this->_redirectGET2Named(array(
            'filter_id',
            'q'
        ));
        $this->pageTitle = __l('Affiliates');
        $conditions = array();
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['Affiliate']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Affiliate.created) <= '] = 0;
            $this->pageTitle.= __l(' - Referred today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Affiliate.created) <= '] = 7;
            $this->pageTitle.= __l(' - Referred in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Affiliate.created) <= '] = 30;
            $this->pageTitle.= __l(' - Referred in this month');
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstAffiliateStatus::Pending) {
                $conditions['affiliate_status_id'] = ConstAffiliateStatus::Pending;
                $this->pageTitle.= __l('- Pending');
            } else if ($this->request->params['named']['filter_id'] == ConstAffiliateStatus::Canceled) {
                $conditions['affiliate_status_id'] = ConstAffiliateStatus::Canceled;
                $this->pageTitle.= __l('- Canceled');
            } else if ($this->request->params['named']['filter_id'] == ConstAffiliateStatus::PipeLine) {
                $conditions['affiliate_status_id'] = ConstAffiliateStatus::PipeLine;
                $this->pageTitle.= __l('- PipeLine');
            } else if ($this->request->params['named']['filter_id'] == ConstAffiliateStatus::Completed) {
                $conditions['affiliate_status_id'] = ConstAffiliateStatus::Completed;
                $this->pageTitle.= __l('- Completed');
            } else if ($this->request->params['named']['filter_id'] == 'All') {
                $conditions['affiliate_status_id'] = array(
                    ConstAffiliateStatus::Pending,
                    ConstAffiliateStatus::Canceled,
                    ConstAffiliateStatus::PipeLine,
                    ConstAffiliateStatus::Completed
                );
                $this->pageTitle.= __l('- All');
            }
            $this->request->params['named']['filter_id'] = !empty($this->request->data['Affiliate']['filter_id']) ? $this->request->data['Affiliate']['filter_id'] : '';
        }
        $filters = $this->Affiliate->AffiliateStatus->find('list', array());
        $this->Affiliate->recursive = 1;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Affiliate.id' => 'desc'
            ) ,
        );
        if (isset($this->request->data['Affiliate']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('affiliates', $this->paginate());
        $this->set(compact('filters'));
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Affiliate->delete($id)) {
            $this->Session->setFlash(__l('Affiliate deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_stats()
    {
        $this->pageTitle = __l('Stats');
        if (Configure::read('affiliate.is_enabled')) {
            $this->loadModel('AffiliateRequest');
            $this->loadModel('Affiliate');
            $this->loadModel('AffiliateCashWithdrawal');
        }
        $periods = array(
            'day' => array(
                'display' => __l('Today') ,
                'conditions' => array(
                    'TO_DAYS(NOW()) - TO_DAYS(created) <= ' => 0,
                )
            ) ,
            'week' => array(
                'display' => __l('This week') ,
                'conditions' => array(
                    'TO_DAYS(NOW()) - TO_DAYS(created) <= ' => 7,
                )
            ) ,
            'month' => array(
                'display' => __l('This month') ,
                'conditions' => array(
                    'TO_DAYS(NOW()) - TO_DAYS(created) <= ' => 30,
                )
            ) ,
            'total' => array(
                'display' => __l('Total') ,
                'conditions' => array()
            )
        );
        if (Configure::read('affiliate.is_enabled')) {
            $models[] = array(
                'Affiliate' => array(
                    'display' => __l('Affiliates') ,
                    'link' => array(
                        'controller' => 'affiliates',
                        'action' => 'index'
                    ) ,
                    'colspan' => 4
                )
            );
            $models[] = array(
                'Affiliate' => array(
                    'display' => __l('Pending') ,
                    'link' => array(
                        'controller' => 'affiliates',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateStatus::Pending,
                        'admin' => true
                    ) ,
                    'conditions' => array(
                        'Affiliate.affiliate_status_id' => ConstAffiliateStatus::Pending,
                    ) ,
                    'alias' => 'AffiliatePending',
                    'isSub' => 'Affiliate'
                )
            );
            $models[] = array(
                'Affiliate' => array(
                    'display' => __l('Canceled') ,
                    'link' => array(
                        'controller' => 'affiliates',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateStatus::Canceled,
                        'admin' => true
                    ) ,
                    'conditions' => array(
                        'Affiliate.affiliate_status_id' => ConstAffiliateStatus::Canceled,
                    ) ,
                    'alias' => 'AffiliateCanceled',
                    'isSub' => 'Affiliate'
                )
            );
            $models[] = array(
                'Affiliate' => array(
                    'display' => __l('Pipeline') ,
                    'link' => array(
                        'controller' => 'affiliates',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateStatus::PipeLine,
                        'admin' => true
                    ) ,
                    'conditions' => array(
                        'Affiliate.affiliate_status_id' => ConstAffiliateStatus::PipeLine,
                    ) ,
                    'alias' => 'AffiliatePipeLine',
                    'isSub' => 'Affiliate'
                )
            );
            $models[] = array(
                'Affiliate' => array(
                    'display' => __l('Completed') ,
                    'link' => array(
                        'controller' => 'affiliates',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateStatus::Completed,
                        'admin' => true
                    ) ,
                    'conditions' => array(
                        'Affiliate.affiliate_status_id' => ConstAffiliateStatus::Completed,
                    ) ,
                    'alias' => 'AffiliateCompleted',
                    'isSub' => 'Affiliate'
                )
            );
            $models[] = array(
                'Affiliate' => array(
                    'display' => __l('Affiliate Requests') ,
                    'isNeedLoop' => false,
                    'alias' => 'Affiliate',
                    'colspan' => 3
                ) ,
            );
            $models[] = array(
                'AffiliateRequest' => array(
                    'display' => __l('Approved') ,
                    'conditions' => array(
                        'AffiliateRequest.is_approved' => 1
                    ) ,
                    'link' => array(
                        'controller' => 'affiliate_requests',
                        'action' => 'index',
                        'is_approved' => 0,
                        'admin' => true
                    ) ,
                    'alias' => 'AffiliateRequestApproved',
                    'isSub' => 'Affiliate'
                ) ,
            );
            $models[] = array(
                'AffiliateRequest' => array(
                    'display' => __l('Waiting for Approved') ,
                    'conditions' => array(
                        'AffiliateRequest.is_approved' => 0
                    ) ,
                    'link' => array(
                        'controller' => 'affiliate_requests',
                        'action' => 'index',
                        'is_approved' => 0,
                        'admin' => true
                    ) ,
                    'alias' => 'AffiliateRequest',
                    'isSub' => 'Affiliate'
                ) ,
            );
            $models[] = array(
                'AffiliateRequest' => array(
                    'display' => __l('Total') ,
                    'conditions' => array() ,
                    'link' => array(
                        'controller' => 'affiliate_requests',
                        'action' => 'index',
                        'admin' => true
                    ) ,
                    'alias' => 'AffiliateRequestTotal',
                    'isSub' => 'Affiliate'
                ) ,
            );
            $models[] = array(
                'AffiliateCashWithdrawal' => array(
                    'display' => __l('Affiliate Withdaw Requests') ,
                    'link' => array(
                        'controller' => 'affiliates',
                        'action' => 'index'
                    ) ,
                    'colspan' => 5
                )
            );
            $models[] = array(
                'AffiliateCashWithdrawal' => array(
                    'display' => __l('Pending') ,
                    'link' => array(
                        'controller' => 'affiliate_cash_withdrawals',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateCashWithdrawalStatus::Pending,
                        'admin' => true
                    ) ,
                    'conditions' => array(
                        'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Pending,
                    ) ,
                    'alias' => 'AffiliateCashWithdrawalPending',
                    'isSub' => 'AffiliateCashWithdrawal'
                )
            );
            $models[] = array(
                'AffiliateCashWithdrawal' => array(
                    'display' => __l('Approved') ,
                    'link' => array(
                        'controller' => 'affiliate_cash_withdrawals',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateCashWithdrawalStatus::Approved,
                        'admin' => true
                    ) ,
                    'conditions' => array(
                        'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Approved,
                    ) ,
                    'alias' => 'AffiliateCashWithdrawalApproved',
                    'isSub' => 'AffiliateCashWithdrawal'
                )
            );
            $models[] = array(
                'AffiliateCashWithdrawal' => array(
                    'display' => __l('Rejected') ,
                    'link' => array(
                        'controller' => 'affiliate_cash_withdrawals',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateCashWithdrawalStatus::Rejected,
                        'admin' => true
                    ) ,
                    'conditions' => array(
                        'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Rejected,
                    ) ,
                    'alias' => 'AffiliateCashWithdrawalReject',
                    'isSub' => 'AffiliateCashWithdrawal'
                )
            );
            $models[] = array(
                'AffiliateCashWithdrawal' => array(
                    'display' => __l('Paid') ,
                    'link' => array(
                        'controller' => 'affiliate_cash_withdrawals',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateCashWithdrawalStatus::Success,
                        'admin' => true
                    ) ,
                    'conditions' => array(
                        'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Success,
                    ) ,
                    'alias' => 'AffiliateCashWithdrawalSuccess',
                    'isSub' => 'AffiliateCashWithdrawal'
                )
            );
            $models[] = array(
                'AffiliateCashWithdrawal' => array(
                    'display' => __l('Payment Failure') ,
                    'link' => array(
                        'controller' => 'affiliate_cash_withdrawals',
                        'action' => 'index',
                        'filter_id' => ConstAffiliateCashWithdrawalStatus::Failed,
                        'admin' => true
                    ) ,
                    'conditions' => array(
                        'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Failed,
                    ) ,
                    'alias' => 'AffiliateCashWithdrawalFail',
                    'isSub' => 'AffiliateCashWithdrawal'
                )
            );
        }
        foreach($models as $unique_model) {
            foreach($unique_model as $model => $fields) {
                foreach($periods as $key => $period) {
                    $conditions = $period['conditions'];
                    if (!empty($fields['conditions'])) {
                        $conditions = array_merge($periods[$key]['conditions'], $fields['conditions']);
                    }
                    $aliasName = !empty($fields['alias']) ? $fields['alias'] : $model;
                    $new_periods = $period;
                    foreach($new_periods['conditions'] as $p_key => $p_value) {
                        unset($new_periods['conditions'][$p_key]);
                        $new_periods['conditions'][str_replace('created', $model . '.created', $p_key) ] = $p_value;
                    }
                    $conditions = $new_periods['conditions'];
                    if (!empty($fields['conditions'])) {
                        $conditions = array_merge($new_periods['conditions'], $fields['conditions']);
                    }
                    $this->set($aliasName . $key, $this->{$model}->find('count', array(
                        'conditions' => $conditions,
                    )));
                }
            }
        }
        $this->set(compact('periods', 'models'));
    }
    public function generate_widget()
    {
        $is_affiliate_user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'User.is_affiliate_user'
            ) ,
            'recursive' => 0
        ));
        if (empty($is_affiliate_user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = __l('Daily Deals Widget');
        $this->loadModel('AffiliateWidgetSize');
        $this->loadModel('City');
        $affiliateWidgetSizes = $this->AffiliateWidgetSize->find('list');
        $cities = $this->City->find('list', array(
            'conditions' => array(
                'City.is_approved =' => 1,
                'City.is_enable =' => 1
            ) ,
            'fields' => array(
                'City.slug',
                'City.name'
            ) ,
            'order' => array(
                'City.name' => 'asc'
            )
        ));
        if (empty($this->request->data['Affiliate']['color'])) {
            $this->request->data['Affiliate']['color'] = '00B5C8';
        }
        $this->set(compact('affiliateWidgetSizes', 'cities'));
    }
    public function preview()
    {
        $is_preview = 0;
        if (!empty($this->request->data)) {
            $is_preview = 1;
        }
        $this->set('is_preview', $is_preview);
    }
    public function widget()
    {
        $this->loadModel('AffiliateWidgetSize');
        $affiliateWidgetSize = $this->AffiliateWidgetSize->find('first', array(
            'conditions' => array(
                'AffiliateWidgetSize.id =' => $this->request->params['named']['size']
            ) ,
            'recursive' => -1
        ));
        if (empty($affiliateWidgetSize)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->set('affiliateWidgetSize', $affiliateWidgetSize);
    }
}
?>