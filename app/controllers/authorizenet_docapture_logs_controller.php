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
class AuthorizenetDocaptureLogsController extends AppController
{
    public $name = 'AuthorizenetDocaptureLogs';
    public function admin_index()
    {
        $this->pageTitle = __l('Authorizenet Docapture Logs');
        $this->AuthorizenetDocaptureLog->recursive = -1;
        $this->paginate = array(
            'order' => array(
                'AuthorizenetDocaptureLog.id' => 'desc'
            )
        );
        $this->set('authorizenetDocaptureLogs', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_view($id = null)
    {
        $this->pageTitle = __l('Authorizenet Docapture Log');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $authorizenetDocaptureLog = $this->AuthorizenetDocaptureLog->find('first', array(
            'conditions' => array(
                'AuthorizenetDocaptureLog.id = ' => $id
            ) ,
            'fields' => array(
                'AuthorizenetDocaptureLog.id',
                'AuthorizenetDocaptureLog.created',
                'AuthorizenetDocaptureLog.modified',
                'AuthorizenetDocaptureLog.deal_user_id',
                'AuthorizenetDocaptureLog.payment_status',
                'AuthorizenetDocaptureLog.transactionid',
                'AuthorizenetDocaptureLog.authorize_amt',
                'AuthorizenetDocaptureLog.authorize_gateway_feeamt',
                'AuthorizenetDocaptureLog.authorize_taxamt',
                'AuthorizenetDocaptureLog.authorize_cvv2match',
                'AuthorizenetDocaptureLog.authorize_avscode',
                'AuthorizenetDocaptureLog.authorize_authorization_code',
                'AuthorizenetDocaptureLog.authorize_response_text',
                'AuthorizenetDocaptureLog.authorize_response',
            ) ,
            'recursive' => -1
        ));
        if (empty($authorizenetDocaptureLog)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $authorizenetDocaptureLog['AuthorizenetDocaptureLog']['id'];
        $this->set('authorizenetDocaptureLog', $authorizenetDocaptureLog);
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->AuthorizenetDocaptureLog->delete($id)) {
            $this->Session->setFlash(__l('Authorizenet Docapture Log deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>