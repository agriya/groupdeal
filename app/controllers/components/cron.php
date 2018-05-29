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
class CronComponent extends Component
{
    var $controller;
    public function update_deal() 
    {
        App::import('Model', 'Deal');
        $this->Deal = new Deal();
        App::import('Model', 'Subscription');
        $this->Subscription = new Subscription();
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $this->Email = new EmailComponent($collection);
        App::import('Model', 'UserCashWithdrawal');
        $this->UserCashWithdrawal = new UserCashWithdrawal();
        $timeZone = Configure::read('site.timezone_offset');
        if (!empty($timeZone)) {
            date_default_timezone_set($timeZone);
        }
        require_once (LIBS . 'router.php');
        $this->Deal->_processOpenStatus();
        //change status of upcoming to open
        $this->Deal->updateAll(array(
            'Deal.deal_status_id' => ConstDealStatus::Open
        ) , array(
            'Deal.start_date <= ' => _formatDate('Y-m-d H:i:s', date('Y-m-d H:i:s') , true) ,
            'Deal.deal_status_id' => ConstDealStatus::Upcoming,
        ));
        //send subscription mail
        $this->Deal->_sendSubscriptionMail();
        //update failure deals
        $this->Deal->updateAll(array(
            'Deal.deal_status_id' => ConstDealStatus::Expired
        ) , array(
            'Deal.end_date <= ' => _formatDate('Y-m-d H:i:s', date('Y-m-d H:i:s') , true) ,
            'Deal.is_anytime_deal' => 0,
            'Deal.deal_status_id' => ConstDealStatus::Open
        ));
        // Create live sub deal ( now deal )
        $this->Deal->cron_now_deals();
        // Auto Cancelling for non-redeemed 'yesterday' purchases //
        $this->Deal->DealUser->auto_cancel_yesterday_now_deal();
        // Expired Tripped Deals To Closed With An Email To The Deal Owner With Deal User List
        $this->Deal->_closeDeals();
        //refund amount
        if (Configure::read('deal.is_auto_refund_enabled')) {
            $this->Deal->_refundDealAmount('cron');
        }
        //Automatic user cash with draw payment
        if (Configure::read('user.is_withdraw_request_amount_paid_automatic')) {
            $this->UserCashWithdrawal->_automaticTransferAmount(ConstUserTypes::User);
        }
        //Automatic company cash with draw payment for company
        if (Configure::read('company.is_withdraw_request_amount_paid_automatic')) {
            $this->UserCashWithdrawal->_automaticTransferAmount(ConstUserTypes::Company);
        }
        //Automatic pament to deals
        if (Configure::read('company.is_paid_to_company_automatic')) {
            $this->Deal->_payToCompany('cron');
        }
        // City wise deal count update
        $this->Deal->_updateCityDealCount();
        // For Affiliates ( //
        if (Configure::read('affiliate.is_enabled')) {
            App::import('Model', 'Affiliate');
            $this->Affiliate = new Affiliate();
            $this->Affiliate->update_affiliate_status();
        }
        // ) For affiliates //
        // company & branch deal status count update and number of user near by company update
        $this->Deal->live_deal_status_count_update();
        $this->Deal->Company->company_near_user_count_update();
        $this->Deal->Company->CompanyAddress->company_address_near_user_count_update();
        $this->Deal->Company->company_near_user_count_update('iphone');
        $this->Deal->Company->CompanyAddress->company_address_near_user_count_update('iphone');
		// Expired Count Updation //
        $this->updateCouponExpiryCount();
	}
	public function currency_conversion() {
		//currency conversion
       if(Configure::read('site.is_auto_currency_updation')) {
			App::import('Model', 'Currency');
			$this->Currency = new Currency();
			$this->Currency->rate_convertion();
		}
	}	
    function updateCouponExpiryCount() 
    {
        $expiry_conditions = array();
        $expired_conditions = array(); // Quick Fix //
        $expired_conditions['Deal.is_anytime_deal'] = 0;
        $expired_conditions['Deal.deal_status_id'] = array(
            ConstDealStatus::Tipped,
            ConstDealStatus::PaidToCompany,
            ConstDealStatus::Closed,
        );
        $expired_conditions['OR'] = array(
            'Deal.coupon_expiry_date <=' => _formatDate('Y-m-d H:i:s', date('Y-m-d H:i:s') , true) ,
            'Deal.deal_status_id' => ConstDealStatus::Expired,
        );
        $db = $this->Deal->getDataSource();
        $expired_deal_users = $this->Deal->find('all', array(
            'conditions' => $expired_conditions,
            'fields' => array(
                'Deal.id',
                'Deal.name',
                'Deal.coupon_expiry_date',
                'Deal.deal_status_id'
            ) ,
            'contain' => array(
                'DealUser' => array(
                    'conditions' => array(
                        'DealUser.is_paid' => 1,
                        'DealUser.is_repaid' => 0,
                        'DealUser.is_canceled' => 0,
                        'DealUser.quantity >' => $db->expression('DealUser.deal_user_coupon_count')
                    )
                )
            ) ,
            'recursive' => 1
        ));
        foreach($expired_deal_users as $expired_deal_user) {
            $expired_deal_users_expired_count = array();
            if (!empty($expired_deal_user['DealUser'])) {
                $expired_deal_users_expired_count = $this->Deal->DealUser->find('all', array(
                    'conditions' => array(
                        'DealUser.deal_id' => $expired_deal_user['Deal']['id'],
                        'DealUser.is_paid' => 1,
                        'DealUser.is_repaid' => 0,
                        'DealUser.is_canceled' => 0,
                        'DealUser.quantity >' => $db->expression('DealUser.deal_user_coupon_count')
                    ) ,
                    'fields' => array(
                        'SUM(DealUser.quantity - DealUser.deal_user_coupon_count) as expired_count'
                    ) ,
                    'recursive' => -1
                ));
                $this->Deal->updateAll(array(
                    'Deal.deal_user_expired_count' => $expired_deal_users_expired_count[0][0]['expired_count'],
                    'Deal.deal_user_available_count' => 'Deal.deal_user_available_count -' . $expired_deal_users_expired_count[0][0]['expired_count']
                ) , array(
                    'Deal.id' => $expired_deal_user['Deal']['id']
                ));
            }
        }
    }
    public function crushPng($dir, $dir_count) 
    {
        $handle = opendir($dir);
        while (false !== ($readdir = readdir($handle))) {
            if ($readdir != '.' && $readdir != '..') {
                $path = $dir . '/' . $readdir;
                if (is_dir($path)) {
                    ++$dir_count;
                    $this->crushPng($path, $dir_count);
                }
                if (is_file($path)) {
                    $info = pathinfo($path);
                    if (!empty($info['extension']) && $info['extension'] == 'png') {
                        exec('pngcrush -reduce -brute ' . $path . ' ' . $path);
                    }
                }
            }
        }
        closedir($handle);
    }
    function pushMessage() 
    {
        if (Configure::read('subscription.iphone_apns_push_mail_enable') && Configure::read('subscription.iphone_apns_push_mail') == ConstIphoneApnsPushMail::Site) {
            include_once (APP . DS . 'vendors' . DS . 'iphone-apns' . DS . 'classes' . DS . 'class_APNS.php');
            include_once (APP . DS . 'vendors' . DS . 'iphone-apns' . DS . 'classes' . DS . 'class_DbConnect.php');
            // Cert., Path //
            $certificate = APP . 'vendors' . DS . 'iphone-apns' . DS . 'certificate' . DS . 'apns.pem';
            $sandbox_certificate = APP . 'vendors' . DS . 'iphone-apns' . DS . 'certificate' . DS . 'apns_dev.pem';
			$log_path = APP . 'tmp' . DS . 'logs' . DS . 'certificate' . DS . 'apns.log';
            $db = new DbConnect();
            $apns_task['task'] = 'fetch';
            $apns = new APNS($db, $apns_task, $certificate, $sandbox_certificate, $log_path);
        }
    }
}
?>
