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
class PaymentGateway extends AppModel
{
    public $name = 'PaymentGateway';
    public $displayField = 'name';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $hasMany = array(
        'PaymentGatewaySetting' => array(
            'className' => 'PaymentGatewaySetting',
            'foreignKey' => 'payment_gateway_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'name' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
        // filter options in admin index
        $this->isFilterOptions = array(
            0 => __l('All') ,
            1 => __l('Active') ,
            2 => __l('Inactive') ,
            3 => __l('Test Mode') ,
            4 => __l('Live Mode') ,
            5 => __l('Auto Approved') ,
            6 => __l('Non Auto Approved')
        );
    }
    function getPaymentSettings($id = null)
    {
        if (is_null($id)) {
            return false;
        }
        $paymentGateway = $this->find('first', array(
            'conditions' => array(
                'PaymentGateway.id' => $id,
                'PaymentGateway.is_active' => 1
            ) ,
            'contain' => array(
                'PaymentGatewaySetting' => array(
                    'fields' => array(
                        'key',
                        'test_mode_value',
                        'live_mode_value'
                    )
                )
            ) ,
            'recursive' => 1
        ));
        if (!empty($paymentGateway) && !empty($paymentGateway['PaymentGatewaySetting'])) {
            foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                $paymentGateway['PaymentGateway'][$paymentGatewaySetting['key']] = ($paymentGateway['PaymentGateway']['is_test_mode']) ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
            }
            unset($paymentGateway['PaymentGatewaySetting']);
            return $paymentGateway;
        }
        return false;
    }
    function getGatewayDetails()
    {
        $paymentGateways = $this->find('all', array(
            'conditions' => array(
                'PaymentGateway.id' => array(
                    ConstPaymentGateways::CreditCard,
                    ConstPaymentGateways::AuthorizeNet,
                    ConstPaymentGateways::PagSeguro,
					ConstPaymentGateways::ExpressCheckout
                ) ,
            ) ,
            'contain' => array(
                'PaymentGatewaySetting' => array(
                    'fields' => array(
                        'PaymentGatewaySetting.key',
                        'PaymentGatewaySetting.test_mode_value',
                        'PaymentGatewaySetting.live_mode_value',
                    ) ,
                ) ,
            ) ,
            'recursive' => 1
        ));
        foreach($paymentGateways as $paymentGateway) {
            if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::CreditCard) {
                if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                    foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                        if ($paymentGatewaySetting['key'] == 'directpay_API_UserName') {
                            $paypal_sender_info['API_UserName'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        if ($paymentGatewaySetting['key'] == 'directpay_API_Password') {
                            $paypal_sender_info['API_Password'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        if ($paymentGatewaySetting['key'] == 'directpay_API_Signature') {
                            $paypal_sender_info['API_Signature'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        $paypal_sender_info['is_testmode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
                    }
                }
            }
            if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::AuthorizeNet) {
                if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                    foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                        if ($paymentGatewaySetting['key'] == 'authorize_net_api_key') {
                            $authorize_sender_info['api_key'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        if ($paymentGatewaySetting['key'] == 'authorize_net_trans_key') {
                            $authorize_sender_info['trans_key'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                    }
                }
                $authorize_sender_info['is_test_mode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
            }
            if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::PagSeguro) {
                if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                    foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                        if ($paymentGatewaySetting['key'] == 'payee_account') {
                            $pagseguro_sender_info['payee_account'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        if ($paymentGatewaySetting['key'] == 'token') {
                            $pagseguro_sender_info['token'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                    }
                }
                $authorize_sender_info['is_test_mode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
            }
			if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::ExpressCheckout) {
				$paypal_expresscheckout_sender_info = array();
				if (!empty($paymentGateway['PaymentGatewaySetting'])) {
					foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
						if ($paymentGatewaySetting['key'] == 'expressecheckout_API_UserName') {
							$paypal_expresscheckout_sender_info['API_UserName'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
						}
						if ($paymentGatewaySetting['key'] == 'expressecheckout_API_Password') {
							$paypal_expresscheckout_sender_info['API_Password'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
						}
						if ($paymentGatewaySetting['key'] == 'expressecheckout_API_Signature') {
							$paypal_expresscheckout_sender_info['API_Signature'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
						}
						$paypal_expresscheckout_sender_info['is_testmode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
					}
				}
			}				
        }
        $return = array();
        $return['authorize_sender_info'] = $authorize_sender_info;
        $return['paypal_sender_info'] = $paypal_sender_info;
        $return['pagseguro_sender_info'] = $pagseguro_sender_info;
		$return['expresscheckout_sender_info'] = $paypal_expresscheckout_sender_info;
        return $return;
    }
}
?>