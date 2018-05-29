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
class DealUser extends AppModel
{
    public $name = 'DealUser';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'Deal' => array(
            'className' => 'Deal',
            'foreignKey' => 'deal_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'SubDeal' => array(
            'className' => 'SubDeal',
            'foreignKey' => 'sub_deal_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
    );
    public $hasMany = array(
        'Transaction' => array(
            'className' => 'Transaction',
            'foreignKey' => 'foreign_id',
            'dependent' => true,
            'conditions' => array(
                'Transaction.class' => 'DealUser'
            ) ,
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'DealUserCoupon' => array(
            'className' => 'DealUserCoupon',
            'foreignKey' => 'deal_user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    public $hasOne = array(
        'PaypalDocaptureLog' => array(
            'className' => 'PaypalDocaptureLog',
            'foreignKey' => 'deal_user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'PaypalTransactionLog' => array(
            'className' => 'PaypalTransactionLog',
            'foreignKey' => 'deal_user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'AuthorizenetDocaptureLog' => array(
            'className' => 'AuthorizenetDocaptureLog',
            'foreignKey' => 'deal_user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'ExpresscheckoutTransactionLog' => array(
            'className' => 'ExpresscheckoutTransactionLog',
            'foreignKey' => 'deal_user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'CharitiesDealUser' => array(
            'className' => 'CharitiesDealUser',
            'foreignKey' => 'deal_user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'user_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'deal_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'quantity' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
        $this->moreActions = array();
    }
    function afterSave($model)
    {
        if ((Configure::read('charity.is_enabled') == 1)) {
            if (!empty($this->data['DealUser']['is_canceled'])) {
                $charity = $this->CharitiesDealUser->find('first', array(
                    'conditions' => array(
                        'CharitiesDealUser.deal_user_id' => $this->data['DealUser']['id']
                    ) ,
                    'recusive' => -1
                ));
                if (!empty($charity['CharitiesDealUser']['id'])) $this->CharitiesDealUser->delete($charity['CharitiesDealUser']['id']);
            }
        }
    }
    // Releasing Payment for Now/Live Deal Purchase users //
    function processLiveDeal($deal_user_id = array())
    {
        $deal = $this->find('first', array(
            'conditions' => array(
                'DealUser.id' => $deal_user_id,
                'DealUser.is_paid' => 1,
                'DealUser.is_capture_after_redeem' => 1,
                'DealUser.is_canceled' => 0,
                'DealUser.deal_user_coupon_count' => 0,
            ) ,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username',
                        'User.id',
                        'User.email',
                        'User.cim_profile_id'
                    ) ,
                    'UserProfile' => array(
                        'fields' => array(
                            'UserProfile.first_name',
                            'UserProfile.last_name'
                        ) ,
                    ) ,
                ) ,
                'SubDeal' => array(
                    'fields' => array(
                        'SubDeal.id',
                        'SubDeal.name',
                        'SubDeal.slug',
                        'SubDeal.is_enable_payment_advance',
                        'SubDeal.payment_remaining'
                    )
                ) ,
                'DealUserCoupon',
                'PaypalDocaptureLog' => array(
                    'fields' => array(
                        'PaypalDocaptureLog.currency_id',
                        'PaypalDocaptureLog.converted_currency_id',
                        'PaypalDocaptureLog.original_amount',
                        'PaypalDocaptureLog.rate',
                        'PaypalDocaptureLog.authorizationid',
                        'PaypalDocaptureLog.dodirectpayment_amt',
                        'PaypalDocaptureLog.id',
                        'PaypalDocaptureLog.currencycode'
                    )
                ) ,
                'AuthorizenetDocaptureLog' => array(
                    'fields' => array(
                        'AuthorizenetDocaptureLog.id',
                        'AuthorizenetDocaptureLog.currency_id',
                        'AuthorizenetDocaptureLog.converted_currency_id',
                        'AuthorizenetDocaptureLog.orginal_amount',
                        'AuthorizenetDocaptureLog.rate',
                        'AuthorizenetDocaptureLog.authorize_amt'
                    )
                ) ,
                'PaypalTransactionLog' => array(
                    'fields' => array(
                        'PaypalTransactionLog.currency_id',
                        'PaypalTransactionLog.converted_currency_id',
                        'PaypalTransactionLog.orginal_amount',
                        'PaypalTransactionLog.rate',
                        'PaypalTransactionLog.authorization_auth_exp',
                        'PaypalTransactionLog.authorization_auth_id',
                        'PaypalTransactionLog.authorization_auth_amount',
                        'PaypalTransactionLog.authorization_auth_status',
                        'PaypalTransactionLog.mc_currency',
                        'PaypalTransactionLog.mc_gross',
                        'PaypalTransactionLog.id'
                    )
                ) ,
				'ExpresscheckoutTransactionLog' => array(
					'fields' => array(
						'ExpresscheckoutTransactionLog.currency_id',
						'ExpresscheckoutTransactionLog.converted_currency_id',
						'ExpresscheckoutTransactionLog.orginal_amount',
						'ExpresscheckoutTransactionLog.rate',
						'ExpresscheckoutTransactionLog.transaction_id',
						'ExpresscheckoutTransactionLog.mc_currency',
						'ExpresscheckoutTransactionLog.mc_gross',
						'ExpresscheckoutTransactionLog.id'
					)
				) ,
            ) ,
            'recursive' => 3,
        ));
        //do capture for credit card
        if (!empty($deal)) {
            App::import('Core', 'ComponentCollection');
            $collection = new ComponentCollection();
            App::import('Component', 'Paypal');
            $this->Paypal = new PaypalComponent($collection);
            // Gettin' Payment Gateway Details //
            $gateway_details = $this->User->Transaction->PaymentGateway->getGatewayDetails();
            $authorize_sender_info = $gateway_details['authorize_sender_info'];
            $paypal_sender_info = $gateway_details['paypal_sender_info'];
			$paypal_expresscheckout_sender_info = $gateway_details['expresscheckout_sender_info'];
			
            $dealUser = $deal;
            // Now Deal Modifications //
            if ($dealUser['DealUser']['payment_gateway_id'] != ConstPaymentGateways::Wallet) {
                $payment_response = array();
                if ($dealUser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet) {
                    $capture = 0;
                    require_once (APP . 'vendors' . DS . 'CIM' . DS . 'AuthnetCIM.class.php');
                    if ($authorize_sender_info['is_test_mode']) {
                        $cim = new AuthnetCIM($authorize_sender_info['api_key'], $authorize_sender_info['trans_key'], true);
                    } else {
                        $cim = new AuthnetCIM($authorize_sender_info['api_key'], $authorize_sender_info['trans_key']);
                    }
                    $dealUser['DealUser']['discount_amount'] = $this->_convertAuthorizeAmount($dealUser['DealUser']['discount_amount'], $dealUser['AuthorizenetDocaptureLog']['rate']);
                    $cim->setParameter('amount', $dealUser['DealUser']['discount_amount']);
                    $cim->setParameter('refId', time());
                    $cim->setParameter('customerProfileId', $dealUser['User']['cim_profile_id']);
                    $cim->setParameter('customerPaymentProfileId', $dealUser['DealUser']['payment_profile_id']);
                    $cim_transaction_type = 'profileTransAuthCapture';
                    if (!empty($dealUser['DealUser']['cim_approval_code'])) {
                        $cim->setParameter('approvalCode', $dealUser['DealUser']['cim_approval_code']);
                        $cim_transaction_type = 'profileTransCaptureOnly';
                    }
                    $title = Configure::read('site.name') . ' - Deal Bought';
                    $description = 'Deal Bought in ' . Configure::read('site.name');
                    // CIM accept only 30 character in title
                    if (strlen($title) > 30) {
                        $title = substr($title, 0, 27) . '...';
                    }
                    $unit_amount = $dealUser['DealUser']['discount_amount']/$dealUser['DealUser']['quantity'];
                    $cim->setLineItem($dealUser['DealUser']['deal_id'], $title, $description, $dealUser['DealUser']['quantity'], $unit_amount);
                    $cim->createCustomerProfileTransaction($cim_transaction_type);
                    $response = $cim->getDirectResponse();
                    $response_array = explode(',', $response);
                    if ($cim->isSuccessful() && $response_array[0] == 1) {
                        $capture = 1;
                    }
                    $data_authorize_docapture_log['AuthorizenetDocaptureLog']['id'] = $dealUser['AuthorizenetDocaptureLog']['id'];
                    $data_authorize_docapture_log['AuthorizenetDocaptureLog']['deal_user_id'] = $dealUser['DealUser']['id'];
                    $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_response_text'] = $cim->getResponseText();
                    $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_authorization_code'] = $cim->getAuthCode();
                    $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_avscode'] = $cim->getAVSResponse();
                    $data_authorize_docapture_log['AuthorizenetDocaptureLog']['transactionid'] = $cim->getTransactionID();
                    $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_amt'] = $response_array[9];
                    $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_gateway_feeamt'] = $response[32];
                    $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_cvv2match'] = $cim->getCVVResponse();
                    $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_response'] = $response;
                    if (!empty($capture)) {
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['payment_status'] = 'Completed';
                    }
                    $this->AuthorizenetDocaptureLog->save($data_authorize_docapture_log);
                } else {
                    //doCapture process for credit card and paypal auth
                    if ($dealUser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::CreditCard && !empty($dealUser['PaypalDocaptureLog']['authorizationid'])) {
                        $post_info['authorization_id'] = $dealUser['PaypalDocaptureLog']['authorizationid'];
                        $post_info['amount'] = $dealUser['PaypalDocaptureLog']['dodirectpayment_amt'];
                        $post_info['invoice_id'] = $dealUser['PaypalDocaptureLog']['id'];
                        $post_info['currency'] = $dealUser['PaypalDocaptureLog']['currencycode'];
                    } else if ($dealUser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::PayPalAuth && !empty($dealUser['PaypalTransactionLog']['authorization_auth_id'])) {
                        $post_info['authorization_id'] = $dealUser['PaypalTransactionLog']['authorization_auth_id'];
                        $post_info['amount'] = $dealUser['PaypalTransactionLog']['authorization_auth_amount'];
                        $post_info['invoice_id'] = $dealUser['PaypalTransactionLog']['id'];
                        $post_info['currency'] = $dealUser['PaypalTransactionLog']['mc_currency'];
                    } else if ($dealUser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::ExpressCheckout && !empty($dealUser['ExpresscheckoutTransactionLog']['transaction_id'])) {
						$post_info['authorization_id'] = $dealUser['ExpresscheckoutTransactionLog']['transaction_id'];
						$post_info['amount'] = $dealUser['ExpresscheckoutTransactionLog']['mc_gross'];
						$post_info['invoice_id'] = $dealUser['ExpresscheckoutTransactionLog']['id'];
						$post_info['currency'] = $dealUser['ExpresscheckoutTransactionLog']['mc_currency'];
                    }
                    $post_info['CompleteCodeType'] = 'Complete';
                    $post_info['note'] = __l('Deal Payment');
                    //call doCapture from paypal component
					if ($dealUser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::ExpressCheckout) {
						$payment_response = $this->Paypal->doCapture($post_info, $paypal_expresscheckout_sender_info);
					} else {
						$payment_response = $this->Paypal->doCapture($post_info, $paypal_sender_info);
					}
                }
                if ((!empty($payment_response) && $payment_response['ACK'] == 'Success') || !empty($capture)) {
                    //update PaypalDocaptureLog for credit card
                    if ($dealUser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::CreditCard) {
                        $data_paypal_docapture_log['PaypalDocaptureLog']['id'] = $dealUser['PaypalDocaptureLog']['id'];
                        foreach($payment_response as $key => $value) {
                            if ($key != 'AUTHORIZATIONID' && $key != 'VERSION' && $key != 'CURRENCYCODE') {
                                $data_paypal_docapture_log['PaypalDocaptureLog']['docapture_' . strtolower($key) ] = $value;
                            }
                        }
                        $data_paypal_docapture_log['PaypalDocaptureLog']['docapture_response'] = serialize($payment_response);
                        $data_paypal_docapture_log['PaypalDocaptureLog']['payment_status'] = 'Completed';
                        $this->PaypalDocaptureLog->save($data_paypal_docapture_log);
                    } else if ($dealUser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::PayPalAuth) {
                        //update PaypalTransactionLog for PayPalAuth
                        $data_paypal_capture_log['PaypalTransactionLog']['id'] = $dealUser['PaypalTransactionLog']['id'];
                        $data_paypal_capture_log['PaypalTransactionLog']['error_no'] = '0';
                        $data_paypal_capture_log['PaypalTransactionLog']['payment_status'] = 'Completed';
                        foreach($payment_response as $key => $value) {
                            $data_paypal_capture_log['PaypalTransactionLog']['capture_' . strtolower($key) ] = $value;
                        }
                        $data_paypal_capture_log['PaypalTransactionLog']['capture_data'] = serialize($payment_response);
                        $this->PaypalTransactionLog->save($data_paypal_capture_log);
                    } else if ($dealUser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::ExpressCheckout) {
						//update ExpresscheckoutTransactionLog for Express Checkout
						$data_paypal_expresscheckout_capture_log = array();
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['id'] = $dealUser['ExpresscheckoutTransactionLog']['id'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['error_no'] = '0';
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_amt'] = $payment_response['AMT'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_feeamt'] = $payment_response['FEEAMT'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_paymentstatus'] = $payment_response['PAYMENTSTATUS'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_pendingreason'] = $payment_response['PENDINGREASON'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_transactionid'] = $payment_response['TRANSACTIONID'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_parenttransactionid'] = $payment_response['PARENTTRANSACTIONID'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_taxamt'] = $payment_response['TAXAMT'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_ordertime'] = $payment_response['ORDERTIME'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_paymenttype'] = $payment_response['PAYMENTTYPE'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_transactiontype'] = $payment_response['TRANSACTIONTYPE'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_ack'] = $payment_response['ACK'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_timestamp'] = $payment_response['TIMESTAMP'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_correlationid'] = $payment_response['CORRELATIONID'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_build'] = $payment_response['BUILD'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_reasoncode'] = $payment_response['REASONCODE'];
						$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_protectioneligibility'] = $payment_response['PROTECTIONELIGIBILITY'];
						$this->ExpresscheckoutTransactionLog->save($data_paypal_expresscheckout_capture_log);
                    }
                    //add amount to wallet
                    // coz of 'act like groupon' logic, amount updated from what actual taken, instead of updating deal amount directly.
                    if (!empty($dealUser['PaypalTransactionLog']['orginal_amount'])) {
                        $paid_amount = $dealUser['PaypalTransactionLog']['orginal_amount'];
                    } elseif (!empty($dealUser['PaypalDocaptureLog']['original_amount'])) {
                        $paid_amount = $dealUser['PaypalDocaptureLog']['original_amount'];
                    }
                    $data['Transaction']['user_id'] = $dealUser['DealUser']['user_id'];
                    $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
                    $data['Transaction']['class'] = 'SecondUser';
                    //$data['Transaction']['amount'] = $dealUser['discount_amount'];
                    $data['Transaction']['amount'] = $paid_amount;
                    $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
                    $data['Transaction']['payment_gateway_id'] = $dealUser['DealUser']['payment_gateway_id'];
                    $transaction_id = $this->User->Transaction->log($data);
                    if (!empty($transaction_id)) {
                        $this->User->updateAll(array(
                            'User.available_balance_amount' => 'User.available_balance_amount +' . $paid_amount
                        ) , array(
                            'User.id' => $dealUser['user_id']
                        ));
                    }
                    //Buy deal transaction
                    $transaction['Transaction']['user_id'] = $dealUser['DealUser']['user_id'];
                    $transaction['Transaction']['foreign_id'] = $dealUser['DealUser']['id'];
                    $transaction['Transaction']['class'] = 'DealUser';
                    $transaction['Transaction']['amount'] = $paid_amount;
                    $transaction['Transaction']['payment_gateway_id'] = $dealUser['DealUser']['payment_gateway_id'];
                    if (!empty($dealUser['PaypalTransactionLog']['rate'])) {
                        $transaction['Transaction']['currency_id'] = $dealUser['PaypalTransactionLog']['currency_id'];
                        $transaction['Transaction']['converted_currency_id'] = $dealUser['PaypalTransactionLog']['converted_currency_id'];
                        $transaction['Transaction']['converted_amount'] = $dealUser['PaypalTransactionLog']['mc_gross'];
                        $transaction['Transaction']['rate'] = $dealUser['PaypalTransactionLog']['rate'];
                    }
                    if (!empty($dealUser['PaypalDocaptureLog']['rate'])) {
                        $transaction['Transaction']['currency_id'] = $dealUser['PaypalDocaptureLog']['currency_id'];
                        $transaction['Transaction']['converted_currency_id'] = $dealUser['PaypalDocaptureLog']['converted_currency_id'];
                        $transaction['Transaction']['converted_amount'] = $dealUser['PaypalDocaptureLog']['dodirectpayment_amt'];
                        $transaction['Transaction']['rate'] = $dealUser['PaypalDocaptureLog']['rate'];
                    }
                    if (!empty($dealUser['AuthorizenetDocaptureLog']['rate'])) {
                        $transaction['Transaction']['currency_id'] = $dealUser['AuthorizenetDocaptureLog']['currency_id'];
                        $transaction['Transaction']['converted_currency_id'] = $dealUser['AuthorizenetDocaptureLog']['converted_currency_id'];
                        $transaction['Transaction']['converted_amount'] = $dealUser['AuthorizenetDocaptureLog']['authorize_amt'];
                        $transaction['Transaction']['rate'] = $dealUser['AuthorizenetDocaptureLog']['rate'];
                    }
					if (!empty($dealUser['ExpresscheckoutTransactionLog']['rate'])) {
						$transaction['Transaction']['currency_id'] = $dealUser['ExpresscheckoutTransactionLog']['currency_id'];
						$transaction['Transaction']['converted_currency_id'] = $dealUser['ExpresscheckoutTransactionLog']['converted_currency_id'];
						$transaction['Transaction']['converted_amount'] = $dealUser['ExpresscheckoutTransactionLog']['mc_gross'];
						$transaction['Transaction']['rate'] = $dealUser['ExpresscheckoutTransactionLog']['rate'];
					}
                    $transaction['Transaction']['transaction_type_id'] = (!empty($dealUser['DealUser']['is_gift'])) ? ConstTransactionTypes::DealGift : ConstTransactionTypes::BuyDeal;
                    $this->User->Transaction->log($transaction);
                    //user update
                    $this->User->updateAll(array(
                        'User.available_balance_amount' => 'User.available_balance_amount -' . $paid_amount
                    ) , array(
                        'User.id' => $dealUser['DealUser']['user_id']
                    ));
                    $this->updateAll(array(
                        'DealUser.is_capture_after_redeem' => 0
                    ) , array(
                        'DealUser.id' => $dealUser['DealUser']['id']
                    ));
                    return true;
                } else {
                    //ack from paypal is not succes, so increasing payment_failed_count in deals table
                    $this->updateAll(array(
                        'Deal.payment_failed_count' => 'Deal.payment_failed_count +' . $dealUser['DealUser']['quantity'],
                    ) , array(
                        'Deal.id' => $dealUser['DealUser']['deal_id']
                    ));
                    return false;
                }
            } else {
                return true;
            }
        }
    }
    // Auto Cancelling for non-redeemed 'yesterday' purchases //
    function auto_cancel_yesterday_now_deal()
    {
        $db = $this->getDataSource();
        $start_date_check = _formatDate('Y-m-d H:i:s', date('Y-m-d H:i:s', mktime(0, 0, 0, date("m") , date("d") -2, date("y"))) , true);
        $end_date_check = _formatDate('Y-m-d H:i:s', date('Y-m-d H:i:s', mktime(0, 0, 0, date("m") , date("d") , date("y"))) , true);
        $deal = $this->find('all', array(
            'conditions' => array(
                'DealUser.is_paid' => 1,
                'DealUser.is_canceled' => 0,
                'DealUser.deal_user_coupon_count !=' => $db->expression('DealUser.quantity') ,
            ) ,
            'contain' => array(
                'Deal' => array(
                    'conditions' => array(
                        'Deal.is_now_deal' => 1,
                    )
                ) ,
                'User' => array(
                    'fields' => array(
                        'User.username',
                        'User.id',
                        'User.email',
                        'User.cim_profile_id'
                    ) ,
                    'UserProfile' => array(
                        'fields' => array(
                            'UserProfile.first_name',
                            'UserProfile.last_name'
                        ) ,
                    ) ,
                ) ,
                'SubDeal' => array(
                    'conditions' => array(
                        'SubDeal.start_date >=' => $start_date_check,
                        'SubDeal.end_date <' => $end_date_check,
                    ) ,
                    'fields' => array(
                        'SubDeal.id',
                        'SubDeal.name',
                        'SubDeal.slug',
                        'SubDeal.discount_amount',
                        'SubDeal.is_enable_payment_advance',
                        'SubDeal.payment_remaining'
                    )
                ) ,
                'DealUserCoupon',
                'PaypalDocaptureLog' => array(
                    'fields' => array(
                        'PaypalDocaptureLog.currency_id',
                        'PaypalDocaptureLog.converted_currency_id',
                        'PaypalDocaptureLog.original_amount',
                        'PaypalDocaptureLog.rate',
                        'PaypalDocaptureLog.authorizationid',
                        'PaypalDocaptureLog.dodirectpayment_amt',
                        'PaypalDocaptureLog.id',
                        'PaypalDocaptureLog.currencycode'
                    )
                ) ,
                'AuthorizenetDocaptureLog' => array(
                    'fields' => array(
                        'AuthorizenetDocaptureLog.currency_id',
                        'AuthorizenetDocaptureLog.converted_currency_id',
                        'AuthorizenetDocaptureLog.orginal_amount',
                        'AuthorizenetDocaptureLog.rate',
                        'AuthorizenetDocaptureLog.authorize_amt'
                    )
                ) ,
                'PaypalTransactionLog' => array(
                    'fields' => array(
                        'PaypalTransactionLog.currency_id',
                        'PaypalTransactionLog.converted_currency_id',
                        'PaypalTransactionLog.orginal_amount',
                        'PaypalTransactionLog.rate',
                        'PaypalTransactionLog.authorization_auth_exp',
                        'PaypalTransactionLog.authorization_auth_id',
                        'PaypalTransactionLog.authorization_auth_amount',
                        'PaypalTransactionLog.authorization_auth_status',
                        'PaypalTransactionLog.mc_currency',
                        'PaypalTransactionLog.mc_gross',
                        'PaypalTransactionLog.id'
                    )
                ) ,
            ) ,
            'recursive' => 3,
        ));
        foreach($deal as $deal_user) {
            // To make sure, today deal doesn't comes in array (coz of timezone issues) //
            if (!empty($deal_user['Deal']['id']) && !empty($deal_user['DealUser']) && (date('Y-m-d', strtotime($deal_user['Deal']['end_date'])) != date('Y-m-d'))) {
                if (($deal_user['DealUser']['deal_user_coupon_count'] == 0) && ($deal_user['DealUser']['is_capture_after_redeem'] == 1)) {
                    $this->Deal->_refundDealAmountForCacel($deal_user);
                    // Updating Count //
                    $this->Deal->UpdateAll(array(
                        'Deal.deal_user_count' => $deal_user['Deal']['deal_user_count']-$deal_user['DealUser']['quantity']
                    ) , array(
                        'Deal.id' => $deal_user['Deal']['id']
                    ));
                    // Saving Record //
                    $_data = array();
                    $_data['DealUser']['id'] = $deal_user['DealUser']['id'];
                    $_data['DealUser']['is_canceled'] = 1;
                    $this->save($_data);
                } else {
                    $refund_quantity = $deal_user['DealUser']['quantity']-$deal_user['DealUser']['deal_user_coupon_count'];
                    $refund_amount = $deal_user['SubDeal']['discount_amount']*$refund_quantity;
                    $transaction['Transaction']['user_id'] = $deal_user['DealUser']['user_id'];
                    $transaction['Transaction']['foreign_id'] = $deal_user['DealUser']['id'];
                    $transaction['Transaction']['class'] = 'DealUser';
                    $transaction['Transaction']['amount'] = $refund_amount;
                    $transaction['Transaction']['transaction_type_id'] = (!empty($dealuser['DealUser']['is_gift'])) ? ConstTransactionTypes::DealGiftCancel : ConstTransactionTypes::DealBoughtCancel;
                    $this->User->Transaction->log($transaction);
                    //update user balance
                    $this->User->updateAll(array(
                        'User.available_balance_amount' => 'User.available_balance_amount +' . $refund_amount
                    ) , array(
                        'User.id' => $deal_user['DealUser']['user_id']
                    ));
                    $this->Deal->UpdateAll(array(
                        'Deal.deal_user_count' => $deal_user['Deal']['deal_user_count']-$refund_quantity
                    ) , array(
                        'Deal.id' => $deal_user['Deal']['id']
                    ));
                    $this->Deal->UpdateAll(array(
                        'Deal.deal_user_count' => $deal_user['Deal']['deal_user_count']-$refund_quantity
                    ) , array(
                        'Deal.id' => $deal_user['SubDeal']['id']
                    ));
                }
            }
        }
    }
    function close_now_deal($deal_id = '')
    {
        $db = $this->getDataSource();
        $deal = $this->find('all', array(
            'conditions' => array(
                'DealUser.deal_id' => $deal_id,
                'DealUser.is_paid' => 1,
                'DealUser.is_repaid' => 0,
                'DealUser.is_canceled' => 0,
                'DealUser.deal_user_coupon_count !=' => $db->expression('DealUser.quantity') ,
            ) ,
            'contain' => array(
                'Deal',
                'User' => array(
                    'fields' => array(
                        'User.username',
                        'User.id',
                        'User.email',
                        'User.cim_profile_id'
                    ) ,
                    'UserProfile' => array(
                        'fields' => array(
                            'UserProfile.first_name',
                            'UserProfile.last_name'
                        ) ,
                    ) ,
                ) ,
                'SubDeal' => array(
                    'fields' => array(
                        'SubDeal.id',
                        'SubDeal.name',
                        'SubDeal.slug',
                        'SubDeal.discount_amount',
                        'SubDeal.is_enable_payment_advance',
                        'SubDeal.payment_remaining'
                    )
                ) ,
                'DealUserCoupon',
                'PaypalDocaptureLog' => array(
                    'fields' => array(
                        'PaypalDocaptureLog.currency_id',
                        'PaypalDocaptureLog.converted_currency_id',
                        'PaypalDocaptureLog.original_amount',
                        'PaypalDocaptureLog.rate',
                        'PaypalDocaptureLog.authorizationid',
                        'PaypalDocaptureLog.dodirectpayment_amt',
                        'PaypalDocaptureLog.id',
                        'PaypalDocaptureLog.currencycode'
                    )
                ) ,
                'AuthorizenetDocaptureLog' => array(
                    'fields' => array(
                        'AuthorizenetDocaptureLog.currency_id',
                        'AuthorizenetDocaptureLog.converted_currency_id',
                        'AuthorizenetDocaptureLog.orginal_amount',
                        'AuthorizenetDocaptureLog.rate',
                        'AuthorizenetDocaptureLog.authorize_amt'
                    )
                ) ,
                'PaypalTransactionLog' => array(
                    'fields' => array(
                        'PaypalTransactionLog.currency_id',
                        'PaypalTransactionLog.converted_currency_id',
                        'PaypalTransactionLog.orginal_amount',
                        'PaypalTransactionLog.rate',
                        'PaypalTransactionLog.authorization_auth_exp',
                        'PaypalTransactionLog.authorization_auth_id',
                        'PaypalTransactionLog.authorization_auth_amount',
                        'PaypalTransactionLog.authorization_auth_status',
                        'PaypalTransactionLog.mc_currency',
                        'PaypalTransactionLog.mc_gross',
                        'PaypalTransactionLog.id'
                    )
                ) ,
            ) ,
            'recursive' => 3,
        ));
        foreach($deal as $deal_user) {
            // To make sure, today deal doesn't comes in array (coz of timezone issues) //
            if (!empty($deal_user['Deal']['id'])) {
                if (($deal_user['DealUser']['deal_user_coupon_count'] == 0) && ($deal_user['DealUser']['is_capture_after_redeem'] == 1)) {
                    $this->Deal->_refundDealAmountForCacel($deal_user);
                    // Updating Count //
                   /* $this->Deal->UpdateAll(array(
                        'Deal.deal_user_count' => $deal_user['Deal']['deal_user_count']-$deal_user['DealUser']['quantity']
                    ) , array(
                        'Deal.id' => $deal_user['Deal']['id']
                    ));*/
                    // Saving Record //
                    $_data = array();
                    $_data['DealUser']['id'] = $deal_user['DealUser']['id'];
                    $_data['DealUser']['is_canceled'] = 1;
                    $this->save($_data);
                } else {
                    $refund_quantity = $deal_user['DealUser']['quantity']-$deal_user['DealUser']['deal_user_coupon_count'];
                    $refund_amount = $deal_user['SubDeal']['discount_amount']*$refund_quantity;
                    $transaction['Transaction']['user_id'] = $dealuser['DealUser']['user_id'];
                    $transaction['Transaction']['foreign_id'] = $dealuser['DealUser']['id'];
                    $transaction['Transaction']['class'] = 'DealUser';
                    $transaction['Transaction']['amount'] = $refund_amount;
                    $transaction['Transaction']['transaction_type_id'] = (!empty($dealuser['DealUser']['is_gift'])) ? ConstTransactionTypes::DealGiftCancel : ConstTransactionTypes::DealBoughtCancel;
                    $this->User->Transaction->log($transaction);
                    //update user balance
                    $this->User->updateAll(array(
                        'User.available_balance_amount' => 'User.available_balance_amount +' . $refund_amount
                    ) , array(
                        'User.id' => $dealuser['DealUser']['user_id']
                    ));
                    $this->Deal->UpdateAll(array(
                        'Deal.deal_user_count' => $deal_user['Deal']['deal_user_count']-$refund_quantity
                    ) , array(
                        'Deal.id' => $deal_user['Deal']['id']
                    ));
                    $this->Deal->UpdateAll(array(
                        'Deal.deal_user_count' => $deal_user['Deal']['deal_user_count']-$refund_quantity
                    ) , array(
                        'Deal.id' => $deal_user['SubDeal']['id']
                    ));
                }
            }
        }
    }
    function _updateDealAfterPurchaseCount($data, $type = null)
    {
        $deal = $this->Deal->find('first', array(
            'conditions' => array(
                'Deal.id' => $data['deal_id']
            ) ,
            'fields' => array(
                'Deal.id',
                'Deal.deal_status_id',
                'Deal.deal_user_count',
                'Deal.is_anytime_deal',
            ) ,
            'recursive' => -1
        ));
        $conditions = $updation = array();
        $conditions['DealUser.deal_id'] = $data['deal_id'];
        // For Pending //
        if ($deal['Deal']['deal_status_id'] == ConstDealStatus::Open) {
            $updation['Deal.deal_user_pending_count'] = $deal['Deal']['deal_user_count'];
            $updation['Deal.deal_user_available_count'] = 0;
        } elseif ($deal['Deal']['deal_status_id'] > ConstDealStatus::Open) {
            $db = $this->getDataSource();
            $updation['Deal.deal_user_pending_count'] = 0;
            $available_count_conditions = array();
            $available_count_conditions['DealUser.deal_id'] = $data['deal_id'];
            $available_count_conditions['DealUser.quantity >'] = $db->expression('DealUser.deal_user_coupon_count');
            $available_count_conditions['DealUser.is_canceled'] = 0;
            $available_count_conditions['DealUser.is_paid'] = 1;
            $availableCount = $this->find('all', array(
                'conditions' => array_merge($conditions, $available_count_conditions) ,
                'fields' => array(
                    'SUM(DealUser.quantity - DealUser.deal_user_coupon_count) as available_count'
                ) ,
                'contain' => array(
                    'Deal',
                    'SubDeal'
                ) ,
                'group' => array(
                    'DealUser.user_id'
                ) ,
                'recursive' => 2
            ));
            $updation['Deal.deal_user_available_count'] = !empty($availableCount[0][0]['available_count']) ? $availableCount[0][0]['available_count'] : '0';
        }
        // USED //
        $deal_user_used_count = $this->find('all', array(
            'conditions' => array_merge(array(
                'DealUser.deal_user_coupon_count !=' => 0,
                'DealUser.is_canceled' => 0,
                'DealUser.is_repaid' => 0,
            ) , $conditions) ,
            'fields' => array(
                'SUM(DealUser.deal_user_coupon_count) as used_count'
            ) ,
            'recursive' => -1
        ));
        $updation['Deal.deal_user_used_count'] = (!empty($deal_user_used_count[0][0]['used_count']) ? $deal_user_used_count[0][0]['used_count'] : '0');
        // GIFT //
        $deal_user_gift_count = $this->find('all', array(
            'conditions' => array_merge(array(
                'DealUser.is_gift' => 1,
                'DealUser.is_canceled' => 0,
                'DealUser.is_repaid' => 0,
            ) , $conditions) ,
            'fields' => array(
                'SUM(DealUser.deal_user_coupon_count) as gift_count'
            ) ,
            'recursive' => -1
        ));
        $updation['Deal.deal_user_gift_count'] = (!empty($deal_user_gift_count[0][0]['gift_count']) ? $deal_user_gift_count[0][0]['gift_count'] : '0');
        // Updating //
        $update_model = array(
            'Deal.id' => $data['deal_id']
        );
        if (!empty($updation) && !empty($update_model)) {
            $this->Deal->updateAll($updation, $update_model);
        }
    }
}
?>
