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
class CharityMoneyTransferAccountsController extends AppController
{
    public $name = 'CharityMoneyTransferAccounts';
    public function admin_index($charity_id)
    {
        $this->pageTitle = __l('Charity Money Transfer Accounts');
        if (is_null($charity_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->paginate = array(
            'conditions' => array(
                'CharityMoneyTransferAccount.charity_id' => $charity_id,
            ) ,
            'order' => array(
                'CharityMoneyTransferAccount.id' => 'desc'
            ) ,
            'recursive' => 0
        );
        $this->set('charityMoneyTransferAccounts', $this->paginate());
        $this->set('charity_id', $charity_id);
    }
    public function admin_add($charity_id = null)
    {
        if (is_null($charity_id) && empty($this->request->data['CharityMoneyTransferAccount']['charity_id'])) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->CharityMoneyTransferAccount->create();
        if (!empty($this->request->data)) {
            if ($this->CharityMoneyTransferAccount->save($this->request->data)) {
                $this->Session->setFlash(__l('charity money transfer account has been added') , 'default', null, 'success');
                $ajax_url = Router::url(array(
                    'controller' => 'charity_money_transfer_accounts',
                    'action' => 'index',
                    $this->request->data['CharityMoneyTransferAccount']['charity_id']
                ));
                if ($this->request->params['isAjax'] == 1) {
                    $success_msg = 'redirect*' . $ajax_url;
                    echo $success_msg;
                    exit;
                } else {
                    $this->redirect($ajax_url);
                }
            } else {
                $this->Session->setFlash(__l('charity money transfer account could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data['CharityMoneyTransferAccount']['charity_id'] = $charity_id;
        }
        $moneyTransferAccounts = $this->CharityMoneyTransferAccount->find('all', array(
            'conditions' => array(
                'CharityMoneyTransferAccount.charity_id' => $this->request->data['CharityMoneyTransferAccount']['charity_id'],
            ) ,
            'fields' => array(
                'CharityMoneyTransferAccount.payment_gateway_id' => 'CharityMoneyTransferAccount.payment_gateway_id'
            ) ,
            'recursive' => -1
        ));
        $paymentGatewayIds = array();
        if (!empty($moneyTransferAccounts)) {
            foreach($moneyTransferAccounts as $moneyTransferAccount) {
                $paymentGatewayIds[$moneyTransferAccount['CharityMoneyTransferAccount']['payment_gateway_id']] = $moneyTransferAccount['CharityMoneyTransferAccount']['payment_gateway_id'];
            }
        }
        $paymentGateways = $this->CharityMoneyTransferAccount->PaymentGateway->find('list', array(
            'conditions' => array(
                'PaymentGateway.is_mass_pay_enabled' => 1,
            ) ,
            'display_name' => array(
                'order ASC'
            ) ,
            'recursive' => -1
        ));
        $this->set('paymentGateways', $paymentGateways);
    }
    public function admin_update()
    {
        if (empty($this->request->data)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (isset($this->request->data['CharityMoneyTransferAccount']['delete']) && $this->request->data['CharityMoneyTransferAccount']['delete'] == 'Delete') {
            $this->CharityMoneyTransferAccount->delete($this->request->data['CharityMoneyTransferAccount']['checked']);
            $this->Session->setFlash(__l('Charity money transfer account has been deleted') , 'default', null, 'success');
        }
        $this->redirect(array(
            'controller' => 'charity_money_transfer_accounts',
            'action' => 'index',
            $this->request->data['CharityMoneyTransferAccount']['charity_id']
        ));
    }
}
?>