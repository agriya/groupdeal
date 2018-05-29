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
class ApnsFeedbackLogsController extends AppController
{
    public $name = 'ApnsFeedbackLogs';
    public function admin_index()
    {
        $this->pageTitle = __l('Unregistered Devices Feedback');
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ApnsFeedbackLog.created) <= '] = 0;
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ApnsFeedbackLog.created) <= '] = 7;
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ApnsFeedbackLog.created) <= '] = 30;
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'recursive' => 0
        );
        $this->set('apnsFeedbackLogs', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->ApnsFeedbackLog->id = $id;
        if (!$this->ApnsFeedbackLog->exists()) {
            throw new NotFoundException(__l('Invalid apns feedback log'));
        }
        if ($this->ApnsFeedbackLog->delete()) {
            $this->Session->setFlash(__l('Apns feedback log deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->Session->setFlash(__l('Apns feedback log was not deleted') , 'default', null, 'error');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
