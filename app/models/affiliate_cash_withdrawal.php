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
class AffiliateCashWithdrawal extends AppModel
{
    public $name = 'AffiliateCashWithdrawal';
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
        'AffiliateCashWithdrawalStatus' => array(
            'className' => 'AffiliateCashWithdrawalStatus',
            'foreignKey' => 'affiliate_cash_withdrawal_status_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'PaymentGateway' => array(
            'className' => 'PaymentGateway',
            'foreignKey' => 'payment_gateway_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    public $hasOne = array(
        'PaypalTransactionLog' => array(
            'className' => 'PaypalTransactionLog',
            'foreignKey' => 'affiliate_cash_withdrawal_id',
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
            'amount' => array(
                'rule3' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule2' => array(
                    'rule' => 'numeric',
                    'message' => __l('Should be numeric')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'description' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
        $this->moreActions = array(
            ConstAffiliateCashWithdrawalStatus::Pending => __l('Pending') ,
            ConstAffiliateCashWithdrawalStatus::Approved => __l('Approved...') ,
            ConstAffiliateCashWithdrawalStatus::Rejected => __l('Rejected')
        );
    }
    function _checkAmount($amount)
    {
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->data[$this->name]['user_id']
            ) ,
            'fields' => array(
                'User.commission_line_amount',
                'User.user_type_id',
            ) ,
            'recursive' => -1
        ));
        $user_available_balance = $user['User']['commission_line_amount'];
        if ($user_available_balance < $amount) {
            $this->validationErrors['amount'] = __l('Given amount is greater than your commission amount');
        }
        if ($user['User']['user_type_id'] == ConstUserTypes::User) {
            if ($amount < Configure::read('affiliate.payment_threshold_for_threshold_limit_reach')) {
                $this->validationErrors['amount'] = __l('Given amount is less than withdraw limit');
            }
        }
        return false;
    }
    function _getWithdrawalRequest($userCashWithdrawalsIds, $user_type_id, $payment_gateway_id)
    {
        $conditions['AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id'] = ConstAffiliateCashWithdrawalStatus::Pending;
        if (!empty($userCashWithdrawalsIds)) {
            $conditions['AffiliateCashWithdrawal.id'] = $userCashWithdrawalsIds;
        } elseif ($user_type_id == ConstUserTypes::User) {
            $conditions['User.user_type_id'] = ConstUserTypes::User;
        }
        $returns['error'] = 0;
        $userCashWithdrawals = $this->find('all', array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'MoneyTransferAccount' => array(
                        'conditions' => array(
                            'MoneyTransferAccount.payment_gateway_id' => $payment_gateway_id
                        )
                    ) ,
                    'fields' => array(
                        'User.username',
                        'User.available_balance_amount',
                        'User.blocked_amount',
                        'User.commission_line_amount',
                        'User.commission_withdraw_request_amount',
                        'User.commission_paid_amount'
                    )
                ) ,
            ) ,
            'recursive' => 2
        ));
        $filteredUserCashWithdrawals = array();
        if (!empty($userCashWithdrawals)) {
            foreach($userCashWithdrawals as $userCashWithdrawal) {
                if (empty($userCashWithdrawal['User']['MoneyTransferAccount'])) {
                    $returns['error'] = 1;
                    $returns['message'] = __l('one the selected withdrawal has not configured the money transfer account. Please try again');
                    break;
                } else if ($userCashWithdrawal['User']['commission_withdraw_request_amount'] >= $userCashWithdrawal['AffiliateCashWithdrawal']['amount']) {
                    $affilate_transaction_fee_enabled = Configure::read('affiliate.site_commission_amount');
                    if (!empty($affilate_transaction_fee_enabled)) {
                        if (Configure::read('affiliate.site_commission_type') == 'percentage') {
                            $commission_amount = ($userCashWithdrawal['AffiliateCashWithdrawal']['amount']*Configure::read('affiliate.site_commission_amount') /100);
                        } else {
                            $commission_amount = Configure::read('affiliate.site_commission_amount');
                        }
                        $amount = $userCashWithdrawal['AffiliateCashWithdrawal']['amount']-$commission_amount;
                        $this->updateAll(array(
                            'AffiliateCashWithdrawal.commission_amount' => $commission_amount
                        ) , array(
                            'AffiliateCashWithdrawal.id' => $userCashWithdrawal['AffiliateCashWithdrawal']['id']
                        ));
                        $userCashWithdrawal['AffiliateCashWithdrawal']['commission_amount'] = $commission_amount;
                        $userCashWithdrawal['AffiliateCashWithdrawal']['amount'] = $amount;
                    }
                    $filteredUserCashWithdrawals[] = $userCashWithdrawal;
                }
            }
        }
        $returns['data'] = $filteredUserCashWithdrawals;
        return $returns;
    }
    function affiliate_masspay_ipn_process($userCashWithdrawal_id, $userCashWithdrawal_response, $gateway_id = ConstPaymentGateways::PayPalAuth, $logTable = 'PaypalTransactionLog')
    {
        $userCashWithdrawal = $this->find('first', array(
            'conditions' => array(
                'AffiliateCashWithdrawal.id' => $userCashWithdrawal_id,
                'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Approved,
            ) ,
            'contain' => array(
                'User',
                $logTable => array(
                    'fields' => array(
                        $logTable . '.id',
                        $logTable . '.user_id',
                        $logTable . '.transaction_id',
                        $logTable . '.affiliate_cash_withdrawal_id',
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
            if (!empty($userCashWithdrawal)) {
                if ($userCashWithdrawal_response['status'] == 'Completed') {
                    $logTableData['currency_id'] = $userCashWithdrawal[$logTable]['currency_id'];
                    $logTableData['converted_currency_id'] = $userCashWithdrawal[$logTable]['converted_currency_id'];
                    $logTableData['rate'] = $userCashWithdrawal[$logTable]['rate'];
                    $transaction_id = $this->onSuccessProcess($userCashWithdrawal, $userCashWithdrawal_response, $logTableData, $gateway_id);
                } else {
                    $transaction_id = $this->onFailedProcess($userCashWithdrawal);
                }
                $return['transaction_id'] = $transaction_id;
                $return['log_id'] = $userCashWithdrawal[$logTable]['id'];
            }
            return $return;
        }
    }
    public function onSuccessProcess($affiliateCashWithdrawal, $affiliateCashWithdrawal_response = array() , $logTable = array() , $gateway_id = 0)
    {
        $data = array();
        $data['Transaction']['user_id'] = $affiliateCashWithdrawal['AffiliateCashWithdrawal']['user_id'];
        $data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
		$data['Transaction']['foreign_id'] = ConstUserIds::Admin;
        $data['Transaction']['class'] = 'SecondUser';
        $data['Transaction']['amount'] = $affiliateCashWithdrawal['AffiliateCashWithdrawal']['amount'];
        $data['Transaction']['payment_gateway_id'] = $gateway_id;
        $data['Transaction']['description'] = 'Payment Success';
        $data['Transaction']['gateway_fees'] = $affiliateCashWithdrawal_response['mc_fee'];
        $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AffliateAcceptCashWithdrawRequest;
        // Currency Conversion Changes //
        $data['Transaction']['currency_id'] = $logTable['currency_id'];
        $data['Transaction']['converted_currency_id'] = $logTable['converted_currency_id'];
        $data['Transaction']['converted_amount'] = $affiliateCashWithdrawal_response['mc_gross'];
        $data['Transaction']['rate'] = $logTable['rate'];
        if (!empty($affiliateCashWithdrawal['AffiliateCashWithdrawal']['description'])) {
            $data['Transaction']['description'] = $affiliateCashWithdrawal['AffiliateCashWithdrawal']['description'];
        }
        $transaction_to_user = $this->User->Transaction->log($data);
        $this->User->updateAll(array(
            'User.commission_paid_amount' => 'User.commission_paid_amount +' . $affiliateCashWithdrawal['AffiliateCashWithdrawal']['amount']
        ) , array(
            'User.id' => $affiliateCashWithdrawal['AffiliateCashWithdrawal']['user_id']
        ));
        $this->updateAll(array(
            'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Success
        ) , array(
            'AffiliateCashWithdrawal.id' => $affiliateCashWithdrawal['AffiliateCashWithdrawal']['id']
        ));
        $this->User->updateAll(array(
            'User.commission_withdraw_request_amount' => 'User.commission_withdraw_request_amount -' . $affiliateCashWithdrawal['AffiliateCashWithdrawal']['amount']
        ) , array(
            'User.id' => $affiliateCashWithdrawal['AffiliateCashWithdrawal']['user_id']
        ));
        return $transaction_id;
    }
    public function onFailedProcess($affiliateCashWithdrawal)
    {
        $data = array();
        $data['Transaction']['user_id'] = $affiliateCashWithdrawal['AffiliateCashWithdrawal']['user_id'];
		$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
        $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
        $data['Transaction']['class'] = 'SecondUser';
        $data['Transaction']['amount'] = $affiliateCashWithdrawal['AffiliateCashWithdrawal']['amount'];
        $data['Transaction']['description'] = 'User cash withdrawal request failed';
        $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AffliateFailedWithdrawalRequestRefundToUser;
        $this->User->Transaction->log($data);;
        $transaction_to_user = $this->User->Transaction->log($data);
        $this->User->Transaction->log($data);
        $this->User->updateAll(array(
            'User.commission_line_amount' => 'User.commission_line_amount +' . $affiliateCashWithdrawal['AffiliateCashWithdrawal']['amount']
        ) , array(
            'User.id' => $affiliateCashWithdrawal['AffiliateCashWithdrawal']['user_id']
        ));
        $this->updateAll(array(
            'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Failed
        ) , array(
            'AffiliateCashWithdrawal.id' => $affiliateCashWithdrawal['AffiliateCashWithdrawal']['id']
        ));
        $this->User->updateAll(array(
            'User.commission_withdraw_request_amount' => 'User.commission_withdraw_request_amount -' . $affiliateCashWithdrawal['AffiliateCashWithdrawal']['amount']
        ) , array(
            'User.id' => $affiliateCashWithdrawal['AffiliateCashWithdrawal']['user_id']
        ));
        return $transaction_id;
    }
    public function onApprovedProcess($userCashWithdrawalIds, $status = array() , $logTable = 'PaypalTransactionLog')
    {
        APP::Import('Model', $logTable);
        $this->
        {
            $logTable} = new $logTable();
            foreach($userCashWithdrawalIds as $userCashWithdrawalId) {
                $cash_withdraw = $this->find('first', array(
                    'conditions' => array(
                        'AffiliateCashWithdrawal.id' => $userCashWithdrawalId
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($userCashWithdrawalId) && !empty($cash_withdraw)) {
                    $data['Transaction']['user_id'] = $cash_withdraw['AffiliateCashWithdrawal']['user_id'];
                    $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
					$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
                    $data['Transaction']['class'] = 'SecondUser';
                    $data['Transaction']['amount'] = $cash_withdraw['AffiliateCashWithdrawal']['amount'];
                    $data['Transaction']['description'] = 'User request affiliate commission amount withdrawal approved by admin';
                    $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AffliateAmountRefundedForRejectedWithdrawalRequest;
                    $this->User->Transaction->log($data);
                    // update log transaction id
                    if (!empty($status)) {
                        $log_array = array();
                        $log_array[$logTable]['id'] = $status['log_list'][$userCashWithdrawalId];
                        $log_array[$logTable]['transaction_id'] = $transaction_id;
                        $this->$logTable->save($log_array);
                    }
                    // update status
                    $user_cash_data = array();
                    $user_cash_data['AffiliateCashWithdrawal']['id'] = $userCashWithdrawalId;
                    $user_cash_data['AffiliateCashWithdrawal']['affiliate_cash_withdrawal_status_id'] = ConstAffiliateCashWithdrawalStatus::Approved;
                    $this->save($user_cash_data);
                }
            }
        }
    }
?>