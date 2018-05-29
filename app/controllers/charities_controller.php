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
class CharitiesController extends AppController
{
    public $name = 'Charities';
	public $permanentCacheAction = array(		
		'public' => array(
			'index',
			'search',
		    'view'
		) ,
        'is_view_count_update' => true
    );
    public function beforeFilter()
    {
        if (!Configure::read('charity.is_enabled') && $this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    function view($slug = null)
    {
        $this->pageTitle = __l('Charity');
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $charity = $this->Charity->find('first', array(
            'conditions' => array(
                'Charity.slug = ' => $slug
            ) ,
        ));
        $this->pageTitle.= ' - ' . $charity['Charity']['name'];
        $this->set('charity', $charity);
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Charities');
        $this->_redirectPOST2Named(array(
            'q',
        ));
        $conditions = array();
        if (!empty($this->request->params['named']['charity_category_id'])) {
            $conditions['Charity.charity_category_id'] = $this->request->params['named']['charity_category_id'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Charity.id' => 'desc'
            ) ,
            'contain' => array(
                'CharityCategory',
                'CharityMoneyTransferAccount' => array(
                    'fields' => array(
                        'CharityMoneyTransferAccount.id',
                        'CharityMoneyTransferAccount.payment_gateway_id',
                        'CharityMoneyTransferAccount.account'
                    ) ,
                    'PaymentGateway' => array(
                        'conditions' => array(
                            'PaymentGateway.is_mass_pay_enabled' => 1,
                        ) ,
                        'fields' => array(
                            'PaymentGateway.display_name',
                            'PaymentGateway.name'
                        )
                    )
                )
            ) ,
            'recursive' => 2
        );
        if (isset($this->request->params['named']['q']) && !empty($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
            $this->request->data['Charity']['q'] = $this->request->params['named']['q'];
        }
        $this->Charity->recursive = 0;
        $moreActions = $this->Charity->moreActions;
        $this->set(compact('moreActions'));
        $this->set('charities', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Charity');
        if (!empty($this->request->data)) {
            $this->Charity->create();
            if ($this->Charity->save($this->request->data)) {
                $this->Session->setFlash(__l('Charity has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'charity_money_transfer_accounts',
                    'action' => 'index',
                    $this->Charity->id
                ));
            } else {
                $this->Session->setFlash(__l('Charity could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data['Charity']['is_active'] = 1;
        }
        $charityCategories = $this->Charity->CharityCategory->find('list');
        $this->set(compact('charityCategories'));
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Charity');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->Charity->save($this->request->data)) {
                $this->Session->setFlash(__l('Charity has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Charity could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Charity->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['Charity']['name'];
        $charityCategories = $this->Charity->CharityCategory->find('list');
        $this->set(compact('charityCategories'));
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Charity->delete($id)) {
            $this->Session->setFlash(__l('Charity deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    function admin_update()
    {
        $redirect = true;
        if (!empty($this->request->data[$this->modelClass])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $ids = array();
            foreach($this->request->data[$this->modelClass] as $id => $is_checked) {
                if ($is_checked['id']) {
                    $ids[] = $id;
                }
            }
            if ($actionid && !empty($ids)) {
                switch ($actionid) {
                    case ConstMoreAction::Active:
                        foreach($ids as $id) {
                            $this->{$this->modelClass}->updateAll(array(
                                $this->modelClass . '.is_active' => 1
                            ) , array(
                                $this->modelClass . '.id' => $id
                            ));
                        }
                        $this->Session->setFlash(__l('Checked charities has been marked as active') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Inactive:
                        foreach($ids as $id) {
                            $this->{$this->modelClass}->updateAll(array(
                                $this->modelClass . '.is_active' => 0
                            ) , array(
                                $this->modelClass . '.id' => $id
                            ));
                        }
                        $this->Session->setFlash(__l('Checked charities has been marked as inactive') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Delete:
                        foreach($ids as $id) {
                            $this->{$this->modelClass}->deleteAll(array(
                                $this->modelClass . '.id' => $id
                            ));
                        }
                        $this->Session->setFlash(__l('Checked charities has been deleted') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::PayToCharity:
                        $paymentGateways = $this->Charity->CharityMoneyTransferAccount->PaymentGateway->find('list', array(
                            'conditions' => array(
                                'PaymentGateway.is_mass_pay_enabled' => 1
                            ) ,
                            'fields' => array(
                                'PaymentGateway.id',
                                'PaymentGateway.name',
                            ) ,
                            'recursive' => -1
                        ));
                        $conditions['Charity.id'] = $ids;
                        $this->paginate = array(
                            'conditions' => $conditions,
                            'order' => array(
                                'Charity.id' => 'desc'
                            ) ,
                            'contain' => array(
                                'CharityCategory',
                                'CharityMoneyTransferAccount' => array(
                                    'fields' => array(
                                        'CharityMoneyTransferAccount.id',
                                        'CharityMoneyTransferAccount.payment_gateway_id',
                                        'CharityMoneyTransferAccount.account'
                                    ) ,
                                    'PaymentGateway' => array(
                                        'conditions' => array(
                                            'PaymentGateway.is_mass_pay_enabled' => 1,
                                        ) ,
                                        'fields' => array(
                                            'PaymentGateway.display_name',
                                            'PaymentGateway.name'
                                        )
                                    )
                                )
                            ) ,
                            'recursive' => 2
                        );
                        $charities = $this->paginate();
                        $this->request->data['Charity'] = array();
                        foreach($charities as $key => $charity) {
                            $this->request->data['CharityCashWithdrawal'][$key]['charity_id'] = $charity['Charity']['id'];
                            $this->request->data['CharityCashWithdrawal'][$key]['amount'] = $charity['Charity']['available_amount'];
                            $payment_gates = array();
                            $payment_gates[ConstPaymentGateways::ManualPay] = __('Mark as paid/manual');
                            if (!empty($charity['CharityMoneyTransferAccount'])) {
                                foreach($charity['CharityMoneyTransferAccount'] as $gateway) {
                                    $payment_gates[$gateway['payment_gateway_id']] = __l('Pay via ') . $gateway['PaymentGateway']['display_name'] . ' ' . __l('API') . ' (' . substr($gateway['account'], 0, 10) . '...)';
                                }
                            }
                            foreach($payment_gates as $id => $name) {
                                if (ConstPaymentGateways::ManualPay != $id && empty($paymentGateways[$id])) {
                                    unset($payment_gates[$id]);
                                }
                            }
                            $charities[$key]['paymentways'] = $payment_gates;
                        }
                        $this->set('charities', $charities);
                        $redirect = false;
                        $this->pageTitle = __l('Pay to Charity - Approved');
                        $this->render('admin_pay_to_user');
                        break;
                }
            }
        }
        if ($redirect) $this->redirect(Router::url('/', true) . $r);
    }
    public function admin_pay_to_user()
    {
        $this->pageTitle = __l('Pay to Charity - Approved');
        if (!empty($this->request->data)) {
            $ids = $approve_list = $approve_list_id = array();
            if (!empty($this->request->data['CharityCashWithdrawal'])) {
                foreach($this->request->data['CharityCashWithdrawal'] as $key => $list) {
                    $ids[] = $list['charity_id'];
                    $this->Charity->CharityCashWithdrawal->set($list);
                    if (!$this->Charity->CharityCashWithdrawal->validates()) {
                        $charityCashWithdrawalError[$key] = $this->Charity->CharityCashWithdrawal->validationErrors;
                    }
                }
                if (!empty($charityCashWithdrawalError)) {
                    foreach($charityCashWithdrawalError as $key => &$errors) {
                        foreach($errors as $index => $error) {
                            $this->Charity->CharityCashWithdrawal->validationErrors[$key][$index] = $error;
                        }
                    }
                    $paymentGateways = $this->Charity->CharityMoneyTransferAccount->PaymentGateway->find('list', array(
                        'conditions' => array(
                            'PaymentGateway.is_mass_pay_enabled' => 1
                        ) ,
                        'fields' => array(
                            'PaymentGateway.id',
                            'PaymentGateway.name',
                        ) ,
                        'recursive' => -1
                    ));
                    $conditions['Charity.id'] = $ids;
                    $this->paginate = array(
                        'conditions' => $conditions,
                        'order' => array(
                            'Charity.id' => 'desc'
                        ) ,
                        'contain' => array(
                            'CharityCategory',
                            'CharityMoneyTransferAccount' => array(
                                'fields' => array(
                                    'CharityMoneyTransferAccount.id',
                                    'CharityMoneyTransferAccount.payment_gateway_id',
                                    'CharityMoneyTransferAccount.account'
                                ) ,
                                'PaymentGateway' => array(
                                    'conditions' => array(
                                        'PaymentGateway.is_mass_pay_enabled' => 1,
                                    ) ,
                                    'fields' => array(
                                        'PaymentGateway.display_name',
                                        'PaymentGateway.name'
                                    )
                                )
                            )
                        ) ,
                        'recursive' => 2
                    );
                    $charities = $this->paginate();
                    $this->request->data['Charity'] = array();
                    foreach($charities as $key => $charity) {
                        $this->request->data['CharityCashWithdrawal'][$key]['charity_id'] = $charity['Charity']['id'];
                        $payment_gates = array();
                        $payment_gates[ConstPaymentGateways::ManualPay] = __('Mark as paid/manual');
                        if (!empty($charity['CharityMoneyTransferAccount'])) {
                            foreach($charity['CharityMoneyTransferAccount'] as $gateway) {
                                $payment_gates[$gateway['payment_gateway_id']] = __l('Pay via ') . $gateway['PaymentGateway']['display_name'] . ' ' . __l('API') . ' (' . substr($gateway['account'], 0, 10) . '...)';
                            }
                        }
                        foreach($payment_gates as $id => $name) {
                            if (ConstPaymentGateways::ManualPay != $id && empty($paymentGateways[$id])) {
                                unset($payment_gates[$id]);
                            }
                        }
                        $charities[$key]['paymentways'] = $payment_gates;
                    }
                    $this->set('charities', $charities);
                } else { // no error that save cashwithdrawal table and process for masspayment
                    foreach($this->request->data['CharityCashWithdrawal'] as $key => $list) {
                        $list['charity_cash_withdrawal_status_id'] = ConstCharityCashWithdrawalStatus::Pending;
                        $this->Charity->CharityCashWithdrawal->create();
                        $this->Charity->CharityCashWithdrawal->set($list);
                        if ($this->Charity->CharityCashWithdrawal->save()) {
                            $insert_id = $this->Charity->CharityCashWithdrawal->getLastInsertId();
                            $charity_transaction_fee_enabled = Configure::read('charity.site_commission_amount');
                            if (!empty($charity_transaction_fee_enabled)) {
                                if (Configure::read('charity.site_commission_type') == 'percentage') {
                                    $list['commission_amount'] = ($list['amount']*Configure::read('charity.site_commission_amount') /100);
                                } else {
                                    $list['commission_amount'] = Configure::read('charity.site_commission_amount');
                                }
                            } else {
                                $list['commission_amount'] = 0;
                            }
                            $amount = $list['amount']+$list['commission_amount'];
                            $this->Charity->updateAll(array(
                                'Charity.available_amount' => 'Charity.available_amount -' . $amount
                            ) , array(
                                'Charity.id' => $list['charity_id']
                            )); //
                            $this->Charity->updateAll(array(
                                'Charity.withdraw_request_amount' => 'Charity.withdraw_request_amount +' . $amount
                            ) , array(
                                'Charity.id' => $list['charity_id']
                            ));
                            $list['id'] = $insert_id;
                            $approve_list[$list['gateway']][$insert_id] = $list;
                            $approve_list_id[$list['gateway']][] = $insert_id;
                        }
                    }
                    if (!empty($approve_list)) {
                        foreach($approve_list_id as $gateway => $list_id) {
                            if ($gateway == ConstPaymentGateways::ManualPay) { // manual pay
                                $charityCashWithdrawals = $this->Charity->CharityCashWithdrawal->find('all', array(
                                    'conditions' => array(
                                        'CharityCashWithdrawal.id' => $list_id,
                                        'CharityCashWithdrawal.charity_cash_withdrawal_status_id' => ConstCharityCashWithdrawalStatus::Pending,
                                    ) ,
                                    'recursive' => -1
                                ));
                                foreach($charityCashWithdrawals as $charityCashWithdrawal) {
                                    $get_conversion_val = $this->Charity->CharityCashWithdrawal->getConversionCurrency();
                                    $get_conversion = $this->Charity->CharityCashWithdrawal->_convertAmount($charityCashWithdrawal['CharityCashWithdrawal']['amount']);
                                    $logTableData['currency_id'] = $get_conversion_val['CurrencyConversion']['currency_id'];
                                    $logTableData['converted_currency_id'] = $get_conversion_val['CurrencyConversion']['converted_currency_id'];
                                    $logTableData['rate'] = $get_conversion_val['CurrencyConversion']['rate'];
                                    $charityCashWithdrawal_response['mc_fee'] = 0;
                                    $charityCashWithdrawal_response['mc_gross'] = $get_conversion['amount'];
                                    $charityCashWithdrawal['CharityCashWithdrawal']['description'] = '';
                                    $this->Charity->CharityCashWithdrawal->onSuccessProcess($charityCashWithdrawal, $charityCashWithdrawal_response, $logTableData);
                                    $this->Session->setFlash(__l('Manual payment process has been completed.') , 'default', null, 'success');
                                }
                            } else {
                                $paymentGateway = $this->Charity->CharityMoneyTransferAccount->PaymentGateway->find('first', array(
                                    'conditions' => array(
                                        'PaymentGateway.id' => $gateway
                                    ) ,
                                    'recursive' => -1
                                ));
                                $modelName = inflector::camelize('mass_pay_' . strtolower($paymentGateway['PaymentGateway']['name']));
                                APP::Import('Model', $modelName);
                                $this->obj = new $modelName();
                                $status = $this->obj->_transferAmount($list_id, 'CharityCashWithdrawal');
                                if (!empty($status['error'])) {
                                    $this->Session->setFlash($status['message'], 'default', null, 'error');
                                } else {
                                    $this->Charity->CharityCashWithdrawal->onApprovedProcess($list_id, $status);
                                    $this->Session->setFlash(__l('Mass payment request is submitted in Paypal. Charity will be paid once process completed.') , 'default', null, 'success');
                                }
                            }
                        }
                    }
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                }
            }
        } else {
            $this->redirect(array(
                'action' => 'index'
            ));
        }
    }
}
?>