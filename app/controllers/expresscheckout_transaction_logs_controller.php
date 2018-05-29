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
class ExpresscheckoutTransactionLogsController extends AppController
{
    public $name = 'ExpresscheckoutTransactionLog';
    public function admin_index()
    {
        $this->pageTitle = __l('Paypal Express Checkout Logs');
        $conditions = array();
        $this->ExpresscheckoutTransactionLog->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'ExpresscheckoutTransactionLog.id' => 'DESC'
            )
        );
        $this->set('expresscheckoutTransactionLogs', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_view($id = null)
    {
        $this->pageTitle = __l('Paypal Express Checkout Log');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $expresscheckoutTransactionLog = $this->ExpresscheckoutTransactionLog->find('first', array(
            'conditions' => array(
                'ExpresscheckoutTransactionLog.id = ' => $id
            ) ,
            'recursive' => 0,
        ));
        if (empty($expresscheckoutTransactionLog)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $expresscheckoutTransactionLog['ExpresscheckoutTransactionLog']['id'];
        $this->set('expresscheckoutTransactionLog', $expresscheckoutTransactionLog);
        $this->set('pageTitle', $this->pageTitle);
    }
}
?>