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
class PaypalTransactionLogsController extends AppController
{
    public $name = 'PaypalTransactionLogs';
    public function admin_index()
    {
        $this->pageTitle = __l('Paypal Transaction Logs');
        $conditions = array();
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'mass') {
            $this->pageTitle = __l('Mass Paypal Transaction Logs');
            $conditions['PaypalTransactionLog.is_mass_pay'] = 1;
        } elseif (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'normal') {
            $this->pageTitle = __l('Normal Paypal Transaction Logs');
            $conditions['PaypalTransactionLog.is_mass_pay'] = 0;
        }
        $this->PaypalTransactionLog->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'PaypalTransactionLog.id' => 'DESC'
            )
        );
        $this->set('paypalTransactionLogs', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_view($id = null)
    {
        $this->pageTitle = __l('Paypal Transaction Log');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $paypalTransactionLog = $this->PaypalTransactionLog->find('first', array(
            'conditions' => array(
                'PaypalTransactionLog.id = ' => $id
            ) ,
            'recursive' => 0,
        ));
        if (empty($paypalTransactionLog)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $paypalTransactionLog['PaypalTransactionLog']['id'];
        $this->set('paypalTransactionLog', $paypalTransactionLog);
        $this->set('pageTitle', $this->pageTitle);
    }
}
?>