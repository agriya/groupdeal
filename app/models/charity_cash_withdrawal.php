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
class CharityCashWithdrawal extends AppModel
{
    public $name = 'CharityCashWithdrawal';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Charity' => array(
            'className' => 'Charity',
            'foreignKey' => 'charity_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'CharityCashWithdrawalStatus' => array(
            'className' => 'CharityCashWithdrawalStatus',
            'foreignKey' => 'charity_cash_withdrawal_status_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    public $hasOne = array(
        'PaypalTransactionLog' => array(
            'className' => 'PaypalTransactionLog',
            'foreignKey' => 'charity_cash_withdrawal_id',
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
            'charity_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'amount' => array(
                'rule3' => array(
                    'rule' => '_checkAmount',
                    'message' => __l('should be less than or equal to available amount') ,
                    'allowEmpty' => false
                ) ,
                'rule2' => array(
                    'rule' => 'numeric',
                    'message' => __l('Should be numeric') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'charity_cash_withdrawal_status_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'description' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
        $this->moreActions = array(
            ConstCharityCashWithdrawalStatus::Pending => __l('Pending') ,
            ConstCharityCashWithdrawalStatus::Rejected => __l('Rejected') ,
        );
        $paymentGateways = $this->Charity->CharityMoneyTransferAccount->PaymentGateway->find('all', array(
            'conditions' => array(
                'PaymentGateway.is_mass_pay_enabled' => 1
            ) ,
            'recursive' => -1
        ));
        foreach($paymentGateways as $paymentGateway) {
            $const = 'Charity' . $paymentGateway['PaymentGateway']['name'];
            $this->moreActions[constant('ConstWithdrawalMassPayGateWays::' . $const) ] = sprintf('%s (%s %s %s)', __l('Approve') , __l('Pay Via') , $paymentGateway['PaymentGateway']['display_name'], __l('Mass Pay API'));;
        }
    }
    function _checkAmount()
    {
        $charity = $this->Charity->find('first', array(
            'conditions' => array(
                'Charity.id' => $this->data['CharityCashWithdrawal']['charity_id']
            ) ,
            'fields' => array(
                'Charity.available_amount',
            ) ,
            'recursive' => -1
        ));
        $charity_transaction_fee_enabled = Configure::read('charity.site_commission_amount');
        if (!empty($charity_transaction_fee_enabled)) {
            if (Configure::read('charity.site_commission_type') == 'percentage') {
                $this->data['CharityCashWithdrawal']['commission_amount'] = ($this->data['CharityCashWithdrawal']['amount']*Configure::read('charity.site_commission_amount') /100);
            } else {
                $this->data['CharityCashWithdrawal']['commission_amount'] = Configure::read('charity.site_commission_amount');
            }
        }
        $charity_available_balance = $charity['Charity']['available_amount'];
        $amount = $this->data['CharityCashWithdrawal']['amount']+$this->data['CharityCashWithdrawal']['commission_amount'];
        if ($charity_available_balance < $amount) {
            return false;
        }
        return true;
    }
    function _getWithdrawalRequest($userCashWithdrawalsIds, $user_type_id, $payment_gateway_id)
    {
        $filteredUserCashWithdrawals = array();
        $conditions['CharityCashWithdrawal.charity_cash_withdrawal_status_id'] = ConstCharityCashWithdrawalStatus::Pending;
        if (!empty($charityCashWithdrawalsIds)) {
            $conditions['CharityCashWithdrawal.id'] = $charityCashWithdrawalsIds;
        }
        $returns['error'] = 0;
        $charityCashWithdrawals = $this->find('all', array(
            'conditions' => $conditions,
            'contain' => array(
                'Charity' => array(
                    'CharityMoneyTransferAccount' => array(
                        'conditions' => array(
                            'CharityMoneyTransferAccount.payment_gateway_id' => $payment_gateway_id
                        )
                    ) ,
                ) ,
                'CharityCashWithdrawalStatus' => array(
                    'fields' => array(
                        'CharityCashWithdrawalStatus.name',
                        'CharityCashWithdrawalStatus.id',
                    )
                )
            ) ,
            'recursive' => 2
        ));
        $filteredCharityCashWithdrawals = array();
        if (!empty($charityCashWithdrawals)) {
            foreach($charityCashWithdrawals as $charityCashWithdrawal) {
                if (empty($charityCashWithdrawal['Charity']['CharityMoneyTransferAccount'])) {
                    $returns['error'] = 1;
                    $returns['message'] = __l('one the selected withdrawal has not configured the money transfer account. Please try again');
                    break;
                } else if ($charityCashWithdrawal['Charity']['withdraw_request_amount'] >= $charityCashWithdrawal['CharityCashWithdrawal']['amount']) {
                    $charity_transaction_fee_enabled = Configure::read('charity.site_commission_amount');
                    if (!empty($charity_transaction_fee_enabled)) {
                        $amount = $charityCashWithdrawal['CharityCashWithdrawal']['amount']-$charityCashWithdrawal['CharityCashWithdrawal']['commission_amount'];
                        $charityCashWithdrawal['CharityCashWithdrawal']['amount'] = $amount;
                    }
                    $filteredUserCashWithdrawals[] = $charityCashWithdrawal;
                }
            }
        }
        $returns['data'] = $filteredUserCashWithdrawals;
        return $returns;
    }
    function charity_masspay_ipn_process($userCashWithdrawal_id, $userCashWithdrawal_response, $gateway_id = ConstPaymentGateways::PayPalAuth, $logTable = 'PaypalTransactionLog')
    {
        $userCashWithdrawal = $this->find('first', array(
            'conditions' => array(
                'CharityCashWithdrawal.id' => $userCashWithdrawal_id,
                'CharityCashWithdrawal.charity_cash_withdrawal_status_id' => ConstCharityCashWithdrawalStatus::Approved,
            ) ,
            'contain' => array(
                'Charity',
                $logTable => array(
                    'fields' => array(
                        $logTable . '.id',
                        $logTable . '.user_id',
                        $logTable . '.transaction_id',
                        $logTable . '.charity_cash_withdrawal_id',
                        $logTable . '.currency_id',
                        $logTable . '.converted_currency_id',
                        $logTable . '.orginal_amount',
                        $logTable . '.rate',
                        $logTable . '.masspay_response',
                    )
                ) ,
            ) ,
            'recursive' => 1
        ));
        $return = '';
        if (!empty($userCashWithdrawal)) {
            if ($userCashWithdrawal_response['status'] == 'Completed') {
                $logTableData['currency_id'] = $userCashWithdrawal[$logTable]['currency_id'];
                $logTableData['converted_currency_id'] = $userCashWithdrawal[$logTable]['converted_currency_id'];
                $logTableData['rate'] = $userCashWithdrawal[$logTable]['rate'];
                $transaction_id = $this->onSuccessProcess($userCashWithdrawal, $userCashWithdrawal_response, $logTableData, $gateway_id);
            } else {
                $transaction_id = $this->onFailedProcess($charityCashWithdrawal);
            }
            $return['transaction_id'] = $transaction_id;
            $return['log_id'] = $userCashWithdrawal[$logTable]['id'];
        }
        return $return;
    }
    public function onSuccessProcess($charityCashWithdrawal, $charityCashWithdrawal_response = array() , $logTable = array() , $gateway_id = 0)
    {
        App::import('Model', 'Transaction');
        $this->Transaction = new Transaction();
        $data['Transaction']['user_id'] = $charityCashWithdrawal['CharityCashWithdrawal']['charity_id'];
        $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
		$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
        $data['Transaction']['class'] = 'Charity';
        $data['Transaction']['amount'] = $charityCashWithdrawal['CharityCashWithdrawal']['amount'];
        $data['Transaction']['payment_gateway_id'] = $gateway_id;
        $data['Transaction']['description'] = 'Payment Success';
        $data['Transaction']['gateway_fees'] = $charityCashWithdrawal_response['mc_fee'];
        $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::CharityAcceptCashWithdrawRequest;
        // Currency Conversion Changes //
        $data['Transaction']['currency_id'] = $logTable['currency_id'];
        $data['Transaction']['converted_currency_id'] = $logTable['converted_currency_id'];
        $data['Transaction']['converted_amount'] = $charityCashWithdrawal_response['mc_gross'];
        $data['Transaction']['rate'] = $logTable['rate'];
        if (!empty($charityCashWithdrawal['CharityCashWithdrawal']['description'])) {
            $data['Transaction']['description'] = $charityCashWithdrawal['CharityCashWithdrawal']['description'];
        }
        $transaction_id = $this->Transaction->log($data);
        $this->Charity->updateAll(array(
            'Charity.paid_amount' => 'Charity.paid_amount +' . $charityCashWithdrawal['CharityCashWithdrawal']['amount']
        ) , array(
            'Charity.id' => $charityCashWithdrawal['CharityCashWithdrawal']['charity_id']
        ));
        $this->updateAll(array(
            'CharityCashWithdrawal.charity_cash_withdrawal_status_id' => ConstCharityCashWithdrawalStatus::Success
        ) , array(
            'CharityCashWithdrawal.id' => $charityCashWithdrawal['CharityCashWithdrawal']['id']
        ));
        $this->Charity->updateAll(array(
            'Charity.withdraw_request_amount' => 'Charity.withdraw_request_amount -' . $charityCashWithdrawal['CharityCashWithdrawal']['amount']
        ) , array(
            'Charity.id' => $charityCashWithdrawal['CharityCashWithdrawal']['charity_id']
        ));
        return $transaction_id;
    }
    public function onFailedProcess($charityCashWithdrawal)
    {
        App::import('Model', 'Transaction');
        $this->Transaction = new Transaction();
        $data['Transaction']['user_id'] = $charityCashWithdrawal['CharityCashWithdrawal']['charity_id'];
        $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
		$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
        $data['Transaction']['class'] = 'Charity';
        $data['Transaction']['amount'] = $charityCashWithdrawal['CharityCashWithdrawal']['amount'];
        $data['Transaction']['description'] = 'Charity cash withdrawal request failed';
        $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::CharityFailedWithdrawalRequestRefundToUser;
        $this->Transaction->log($data);
        $this->Charity->updateAll(array(
            'Charity.available_amount' => 'Charity.available_amount +' . $charityCashWithdrawal['CharityCashWithdrawal']['amount']
        ) , array(
            'Charity.id' => $charityCashWithdrawal['CharityCashWithdrawal']['charity_id']
        ));
        $this->updateAll(array(
            'CharityCashWithdrawal.charity_cash_withdrawal_status_id' => ConstCharityCashWithdrawalStatus::Failed
        ) , array(
            'CharityCashWithdrawal.id' => $charityCashWithdrawal['CharityCashWithdrawal']['id']
        ));
        $this->Charity->updateAll(array(
            'Charity.withdraw_request_amount' => 'Charity.withdraw_request_amount -' . $charityCashWithdrawal['CharityCashWithdrawal']['amount']
        ) , array(
            'Charity.id' => $charityCashWithdrawal['CharityCashWithdrawal']['charity_id']
        ));
        return $transaction_id;
    }
    public function onApprovedProcess($userCashWithdrawalIds, $status = array() , $logTable = 'PaypalTransactionLog')
    {
        APP::Import('Model', $logTable);
        $this->
        {
            $logTable} = new $logTable();
            App::import('Model', 'Transaction');
            $this->Transaction = new Transaction();
            foreach($userCashWithdrawalIds as $userCashWithdrawalId) {
                $cash_withdraw = $this->find('first', array(
                    'conditions' => array(
                        'CharityCashWithdrawal.id' => $userCashWithdrawalId
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($userCashWithdrawalId) && !empty($cash_withdraw)) {
                    $data['Transaction']['user_id'] = ConstUserIds::Admin;
					$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
                    $data['Transaction']['foreign_id'] = $cash_withdraw['CharityCashWithdrawal']['charity_id'];
                    $data['Transaction']['class'] = 'Charity';
                    $data['Transaction']['amount'] = $cash_withdraw['CharityCashWithdrawal']['amount'];
                    $data['Transaction']['description'] = 'Charity amount withdrawal approved by admin';
                    $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::CharityAdminApprovedWithdrawalRequest;
                    $this->Transaction->log($data);
                    $transaction_id = $this->Transaction->getLastInsertId();
                    // update log transaction id
                    if (!empty($status)) {
                        $log_array = array();
                        $log_array[$logTable]['id'] = $status['log_list'][$userCashWithdrawalId];
                        $log_array[$logTable]['transaction_id'] = $transaction_id;
                        $this->$logTable->save($log_array);
                    }
                    // update status
                    $user_cash_data = array();
                    $user_cash_data['CharityCashWithdrawal']['id'] = $userCashWithdrawalId;
                    $user_cash_data['CharityCashWithdrawal']['charity_cash_withdrawal_status_id'] = ConstCharityCashWithdrawalStatus::Approved;
                    $this->save($user_cash_data, false);
                }
            }
        }
    }
?>
