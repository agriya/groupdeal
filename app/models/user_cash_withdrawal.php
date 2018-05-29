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
class UserCashWithdrawal extends AppModel
{
    public $name = 'UserCashWithdrawal';
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
        'WithdrawalStatus' => array(
            'className' => 'WithdrawalStatus',
            'foreignKey' => 'withdrawal_status_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true,
            'counterScope' => ''
        ) ,
    );
    public $hasOne = array(
        'PaypalTransactionLog' => array(
            'className' => 'PaypalTransactionLog',
            'foreignKey' => 'user_cash_withdrawal_id',
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
            'withdrawal_status_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'amount' => array(
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
            ConstWithdrawalStatus::Pending => __l('Pending') ,
            ConstWithdrawalStatus::Approved => __l('Approved...') ,
            ConstWithdrawalStatus::Rejected => __l('Rejected') ,
        );
    }
    function _checkAmount($amount)
    {
        $user_available_balance = $this->User->checkUserBalance($this->data[$this->name]['user_id']);
        if ($user_available_balance < $amount) {
            $this->validationErrors['amount'] = __l('Given amount is greater than wallet amount');
        }
        if ($this->data[$this->name]['user_type_id'] == ConstUserTypes::User) {
            if (($amount < Configure::read('user.minimum_withdraw_amount')) || ($amount > Configure::read('user.maximum_withdraw_amount'))) {
                $this->validationErrors['amount'] = sprintf(__l('Given amount should lies from  %s%s to %s%s') , Configure::read('site.currency') , Configure::read('user.minimum_withdraw_amount') , Configure::read('site.currency') , Configure::read('user.maximum_withdraw_amount'));
            }
        } else if ($this->data[$this->name]['user_type_id'] == ConstUserTypes::Company) {
            if (($amount < Configure::read('company.minimum_withdraw_amount')) || ($amount > Configure::read('company.maximum_withdraw_amount'))) {
                $this->validationErrors['amount'] = sprintf(__l('Given amount should lies from  %s%s to %s%s') , Configure::read('site.currency') , Configure::read('company.minimum_withdraw_amount') , Configure::read('site.currency') , Configure::read('company.maximum_withdraw_amount'));
            }
        }
        return false;
    }
    function _automaticTransferAmount($user_type_id)
    {
        $conditions['UserCashWithdrawal.withdrawal_status_id'] = ConstWithdrawalStatus::Pending;
        if ($user_type_id == ConstUserTypes::User) {
            $conditions['User.user_type_id'] = ConstUserTypes::User;
        } elseif ($user_type_id == ConstUserTypes::Company) {
            $conditions['User.user_type_id'] = ConstUserTypes::Company;
        }
        $paymentGateways = $this->User->MoneyTransferAccount->PaymentGateway->find('all', array(
            'conditions' => array(
                'PaymentGateway.is_mass_pay_enabled' => 1,
            ) ,
            'recursive' => -1
        ));
        $userCashWithdrawals = $this->find('all', array(
            'conditions' => $conditions,
            'fields' => array(
                'UserCashWithdrawal.id',
                'UserCashWithdrawal.user_id'
            ) ,
            'recursive' => 0
        ));
        if (!empty($paymentGateways)) {
            foreach($paymentGateways as $paymentGateway) {
                $userWithdrawalIds = array();
                foreach($userCashWithdrawals as $userCashWithdrawal) {
                    $isExistMoneyTransferAccount = $this->User->MoneyTransferAccount->find('first', array(
                        'conditions' => array(
                            'MoneyTransferAccount.user_id' => $userCashWithdrawal['UserCashWithdrawal']['user_id'],
                            'MoneyTransferAccount.payment_gateway_id' => $paymentGateway['PaymentGateway']['id'],
                            'MoneyTransferAccount.is_default' => 1,
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($isExistMoneyTransferAccount)) {
                        $userWithdrawalIds[$userCashWithdrawal['UserCashWithdrawal']['id']] = $userCashWithdrawal['UserCashWithdrawal']['id'];
                    }
                }
                if (!empty($userWithdrawalIds)) {
                    $modelName = inflector::camelize('mass_pay_' . strtolower($paymentGateway['PaymentGateway']['name']));
                    if (class_exists($modelName)) {
						APP::Import('Model', $modelName);
						$this->obj = new $modelName();
						$status = $this->obj->_transferAmount($userWithdrawalIds, 'UserCashWithdrawal');
						if (empty($status['error'])) {
							$this->onApprovedProcess($userWithdrawalIds, $status);
						}
					}
                }
            }
        }
    }
    function _getWithdrawalRequest($userCashWithdrawalsIds, $user_type_id, $payment_gateway_id)
    {
        $conditions['UserCashWithdrawal.withdrawal_status_id'] = ConstWithdrawalStatus::Pending;
        if (!empty($userCashWithdrawalsIds)) {
            $conditions['UserCashWithdrawal.id'] = $userCashWithdrawalsIds;
        } elseif ($user_type_id == ConstUserTypes::User) {
            $conditions['User.user_type_id'] = ConstUserTypes::User;
        } elseif ($user_type_id == ConstUserTypes::Company) {
            $conditions['User.user_type_id'] = ConstUserTypes::Company;
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
                        'User.blocked_amount'
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
                } else if ($userCashWithdrawal['User']['blocked_amount'] >= $userCashWithdrawal['UserCashWithdrawal']['amount']) {
                    $filteredUserCashWithdrawals[] = $userCashWithdrawal;
                }
            }
        }
        $returns['data'] = $filteredUserCashWithdrawals;
        return $returns;
    }
    function user_masspay_ipn_process($userCashWithdrawal_id, $userCashWithdrawal_response, $gateway_id = ConstPaymentGateways::PayPalAuth, $logTable = 'PaypalTransactionLog')
    {
        $userCashWithdrawal = $this->find('first', array(
            'conditions' => array(
                'UserCashWithdrawal.id' => $userCashWithdrawal_id,
                'UserCashWithdrawal.withdrawal_status_id' => ConstWithdrawalStatus::Approved,
            ) ,
            'contain' => array(
                'User',
                $logTable => array(
                    'fields' => array(
                        $logTable . '.id',
                        $logTable . '.user_id',
                        $logTable . '.transaction_id',
                        $logTable . '.user_cash_withdrawal_id',
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
                $transaction_id = $this->onFailedProcess($userCashWithdrawal);
            }
            $return['transaction_id'] = $transaction_id;
            $return['log_id'] = $userCashWithdrawal[$logTable]['id'];
        }
        return $return;
    }
    public function onSuccessProcess($userCashWithdrawal, $userCashWithdrawal_response = array() , $logTable = array() , $gateway_id = 0)
    {
        if (!empty($userCashWithdrawal['UserCashWithdrawal']['description'])) {
            $data['Transaction']['description'] = $userCashWithdrawal['UserCashWithdrawal']['description'];
        }
        if (!empty($userCashWithdrawal['UserCashWithdrawal']['description'])) {
            $data['Transaction']['description'] = $userCashWithdrawal['UserCashWithdrawal']['description'];
        }
        $data['Transaction']['user_id'] = $userCashWithdrawal['UserCashWithdrawal']['user_id'];
        $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
		$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
        $data['Transaction']['class'] = 'SecondUser';
        $data['Transaction']['amount'] = $userCashWithdrawal['UserCashWithdrawal']['amount'];
        $data['Transaction']['payment_gateway_id'] = $gateway_id;
        $data['Transaction']['gateway_fees'] = $userCashWithdrawal_response['mc_fee'];
        $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AcceptCashWithdrawRequest;
        // Currency Conversion Changes //
        $data['Transaction']['currency_id'] = $logTable['currency_id'];
        $data['Transaction']['converted_currency_id'] = $logTable['converted_currency_id'];
        $data['Transaction']['converted_amount'] = $userCashWithdrawal_response['mc_gross'];
        $data['Transaction']['rate'] = $logTable['rate'];
        $transaction_to_user = $this->User->Transaction->log($data);
        $this->updateAll(array(
            'UserCashWithdrawal.withdrawal_status_id' => ConstWithdrawalStatus::Success
        ) , array(
            'UserCashWithdrawal.id' => $userCashWithdrawal['UserCashWithdrawal']['id']
        ));
        $this->_updateUserAmount($userCashWithdrawal['UserCashWithdrawal']['user_id']);
        $this->User->updateAll(array(
            'User.blocked_amount' => 'User.blocked_amount -' . $userCashWithdrawal['UserCashWithdrawal']['amount'],
        ) , array(
            'User.id' => $userCashWithdrawal['UserCashWithdrawal']['user_id']
        ));
        return $transaction_id;
    }
    // After Save Update //
    function _updateUserAmount($user_id)
    {
        if (!empty($user_id)) {
            $user_cash_withdrawal = $this->find('all', array(
                'conditions' => array(
                    'UserCashWithdrawal.user_id' => $user_id,
                    'UserCashWithdrawal.withdrawal_status_id' => ConstWithdrawalStatus::Success,
                ) ,
                'fields' => array(
                    'SUM(UserCashWithdrawal.amount) as total_amount_withdrawn',
                    'COUNT(UserCashWithdrawal.amount) as total_amount_withdrawn_count',
                ) ,
                'recursive' => -1
            ));
            if (!empty($user_cash_withdrawal)) {
                $this->User->updateAll(array(
                    'User.total_amount_withdrawn' => $user_cash_withdrawal[0][0]['total_amount_withdrawn'],
                    'User.total_withdraw_request_count' => $user_cash_withdrawal[0][0]['total_amount_withdrawn_count'],
                ) , array(
                    'User.id' => $user_id
                ));
            }
        }
    }
    public function onFailedProcess($userCashWithdrawal)
    {
        $data['Transaction']['user_id'] = $userCashWithdrawal['UserCashWithdrawal']['user_id'];
        $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
		$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
        $data['Transaction']['class'] = 'SecondUser';
        $data['Transaction']['amount'] = $userCashWithdrawal['UserCashWithdrawal']['amount'];
        $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::FailedWithdrawalRequestRefundToUser;
        $this->User->Transaction->log($data);
        $this->User->updateAll(array(
            'User.available_balance_amount' => 'User.available_balance_amount +' . $userCashWithdrawal['UserCashWithdrawal']['amount']
        ) , array(
            'User.id' => $userCashWithdrawal['UserCashWithdrawal']['user_id']
        ));
        $this->updateAll(array(
            'UserCashWithdrawal.withdrawal_status_id' => ConstWithdrawalStatus::Failed
        ) , array(
            'UserCashWithdrawal.id' => $userCashWithdrawal['UserCashWithdrawal']['id']
        ));
        $this->User->updateAll(array(
            'User.blocked_amount' => 'User.blocked_amount -' . $userCashWithdrawal['UserCashWithdrawal']['amount']
        ) , array(
            'User.id' => $userCashWithdrawal['UserCashWithdrawal']['user_id']
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
                        'UserCashWithdrawal.id' => $userCashWithdrawalId
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($userCashWithdrawalId) && !empty($cash_withdraw)) {
                    $data['Transaction']['user_id'] = $cash_withdraw['UserCashWithdrawal']['user_id'];
                    $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
					$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
                    $data['Transaction']['class'] = 'SecondUser';
                    $data['Transaction']['amount'] = $cash_withdraw['UserCashWithdrawal']['amount'];
                    $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AmountApprovedForUserCashWithdrawalRequest;
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
                    $user_cash_data['UserCashWithdrawal']['id'] = $userCashWithdrawalId;
                    $user_cash_data['UserCashWithdrawal']['withdrawal_status_id'] = ConstWithdrawalStatus::Approved;
                    $this->save($user_cash_data);
                }
            }
        }
    }
?>