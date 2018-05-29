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
class MoneyTransferAccountsController extends AppController
{
    public $name = 'MoneyTransferAccounts';
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
        $this->Security->disabledFields = array(
            'MoneyTransferAccount.default',
            'MoneyTransferAccount.delete'
        );
        parent::beforeFilter();
    }
    public function index()
    {
        $this->pageTitle = __l('Money Transfer Accounts');
        $this->paginate = array(
            'conditions' => array(
                'MoneyTransferAccount.user_id' => $this->Auth->user('id') ,
            ) ,
            'order' => array(
                'MoneyTransferAccount.id' => 'desc'
            ) ,
            'recursive' => 0
        );
        $is_default = $this->MoneyTransferAccount->find('first', array(
            'conditions' => array(
                'MoneyTransferAccount.user_id' => $this->Auth->user('id') ,
                'MoneyTransferAccount.is_default' => 1,
            ) ,
            'fields' => array(
                'MoneyTransferAccount.id',
            ) ,
            'recursive' => -1
        ));
        $this->request->data['MoneyTransferAccount']['checked'] = $is_default['MoneyTransferAccount']['id'];
        $this->set('moneyTransferAccounts', $this->paginate());
    }
    public function add()
    {
        $this->MoneyTransferAccount->create();
        if (!empty($this->request->data)) {
            $userMoneyTransferAccountCount = $this->MoneyTransferAccount->find('count', array(
                'conditions' => array(
                    'MoneyTransferAccount.user_id' => $this->Auth->user('id') ,
                ) ,
                'recursive' => -1
            ));
            if (empty($userMoneyTransferAccountCount)) {
                $this->request->data['MoneyTransferAccount']['is_default'] = 1;
            };
            $this->request->data['MoneyTransferAccount']['user_id'] = $this->Auth->user('id');
            if ($this->MoneyTransferAccount->save($this->request->data)) {
                $this->Session->setFlash(__l('money transfer account has been added') , 'default', null, 'success');
                $ajax_url = Router::url(array(
                    'controller' => 'money_transfer_accounts',
                    'action' => 'index'
                ));
                if ($this->request->params['isAjax'] == 1) {
                    $success_msg = 'redirect*' . $ajax_url;
                    echo $success_msg;
                    exit;
                } else {
                    $this->redirect($ajax_url);
                }
            } else {
                $this->Session->setFlash(__l('money transfer account could not be updated. Please, try again.') , 'default', null, 'error');
            }
        }
        $moneyTransferAccounts = $this->MoneyTransferAccount->find('all', array(
            'conditions' => array(
                'MoneyTransferAccount.user_id' => $this->Auth->user('id') ,
            ) ,
            'fields' => array(
                'MoneyTransferAccount.payment_gateway_id' => 'MoneyTransferAccount.payment_gateway_id'
            ) ,
            'recursive' => -1
        ));
        $paymentGatewayIds = array();
        if (!empty($moneyTransferAccounts)) {
            foreach($moneyTransferAccounts as $moneyTransferAccount) {
                $paymentGatewayIds[$moneyTransferAccount['MoneyTransferAccount']['payment_gateway_id']] = $moneyTransferAccount['MoneyTransferAccount']['payment_gateway_id'];
            }
        }
        $paymentGateways = $this->MoneyTransferAccount->PaymentGateway->find('list', array(
            'conditions' => array(
                'NOT' => array(
                    'PaymentGateway.id' => $paymentGatewayIds
                ) ,
                'AND' => array(
                    'PaymentGateway.is_mass_pay_enabled' => 1,
                )
            ) ,
            'display_name' => array(
                'order ASC'
            ) ,
            'recursive' => -1
        ));
        $this->set('paymentGateways', $paymentGateways);
    }
    public function update()
    {
        if (empty($this->request->data)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (isset($this->request->data['MoneyTransferAccount']['default']) && $this->request->data['MoneyTransferAccount']['default'] == 'Mark as Primary') {
            $this->MoneyTransferAccount->updateAll(array(
                'MoneyTransferAccount.is_default' => 0
            ) , array(
                'MoneyTransferAccount.user_id' => $this->Auth->user('id') ,
            ));
            $this->MoneyTransferAccount->updateAll(array(
                'MoneyTransferAccount.is_default' => 1
            ) , array(
                'MoneyTransferAccount.id' => $this->request->data['MoneyTransferAccount']['checked'],
            ));
            $this->Session->setFlash(__l('Primary money transfer account has been updated') , 'default', null, 'success');
        }
        if (isset($this->request->data['MoneyTransferAccount']['delete']) && $this->request->data['MoneyTransferAccount']['delete'] == 'Delete') {
            $this->MoneyTransferAccount->delete($this->request->data['MoneyTransferAccount']['checked']);
            $this->Session->setFlash(__l('Money transfer account has been deleted') , 'default', null, 'success');
        }
        $this->redirect(array(
            'controller' => 'money_transfer_accounts',
            'action' => 'index'
        ));
    }
}
?>