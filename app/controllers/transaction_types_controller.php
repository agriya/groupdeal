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
class TransactionTypesController extends AppController
{
    public $name = 'TransactionTypes';
    public function admin_index()
    {
        $this->pageTitle = __l('Transaction Types');
        $this->TransactionType->recursive = 0;
        $this->set('transactionTypes', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Transaction Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->TransactionType->save($this->request->data)) {
                $this->Session->setFlash(sprintf(__l('"%s" Transaction Type has been updated') , $this->request->data['TransactionType']['name']) , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(sprintf(__l('"%s" Transaction Type could not be updated. Please, try again.') , $this->request->data['TransactionType']['name']) , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->TransactionType->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['TransactionType']['name'];
        $this->set('pageTitle', $this->pageTitle);
    }
}
?>