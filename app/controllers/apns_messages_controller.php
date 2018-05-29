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
class ApnsMessagesController extends AppController
{
    public $name = 'ApnsMessages';
    public function admin_index()
    {
        $this->pageTitle = __l('Sent Push Messages');
        $this->_redirectGET2Named(array(
            'q',
            'filter_id'
        ));
        $conditions = array();
        $this->set('queued', $this->ApnsMessage->find('count', array(
            'conditions' => array(
                'ApnsMessage.status = ' => ConstIphoneApnsSentMessageStatus::Queued,
            ) ,
            'recursive' => -1
        )));
        $this->set('delivered', $this->ApnsMessage->find('count', array(
            'conditions' => array(
                'ApnsMessage.status = ' => ConstIphoneApnsSentMessageStatus::Delivered,
            ) ,
            'recursive' => -1
        )));
        $this->set('failed', $this->ApnsMessage->find('count', array(
            'conditions' => array(
                'ApnsMessage.status = ' => ConstIphoneApnsSentMessageStatus::Failed,
            ) ,
            'recursive' => -1
        )));
        $this->set('all', $this->ApnsMessage->find('count', array(
            'recursive' => -1
        )));
        if (!empty($this->request->data['ApnsMessage']['filter_id'])) {
            $this->request->params['named']['filter_id'] = $this->request->data['ApnsMessage']['filter_id'];
        }
        if (!empty($this->request->data['ApnsMessage']['q'])) {
            $this->request->params['named']['q'] = $this->request->data['ApnsMessage']['q'];
        }
        if (!empty($this->request->data['ApnsMessage']['main_filter_id'])) {
            $this->request->params['named']['main_filter_id'] = $this->request->data['ApnsMessage']['main_filter_id'];
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            $this->request->data['ApnsMessage']['filter_id'] = $this->request->params['named']['filter_id'];
            $conditions['ApnsMessage.status'] = $this->request->params['named']['filter_id'];
        }
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'yesterday') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ApnsMessage.created)'] = 1;
            $this->pageTitle.= __l(' - Registered Yesterday');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ApnsMessage.created) <= '] = 0;
            $this->pageTitle.= __l(' - Registered today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ApnsMessage.created) <= '] = 7;
            $this->pageTitle.= __l(' - Registered in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ApnsMessage.created) <= '] = 30;
            $this->pageTitle.= __l(' - Registered in this month');
        }
        if (!empty($this->request->params['named']['main_filter_id'])) {
            if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Queued) {
                $conditions['ApnsMessage.status'] = ConstIphoneApnsSentMessageStatus::Queued;
                $this->request->data['ApnsMessage']['filter_id'] = ConstIphoneApnsSentMessageStatus::Queued;
                $this->pageTitle.= __l(' - Queued');
            } elseif ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Delivered) {
                $conditions['ApnsMessage.status'] = ConstIphoneApnsSentMessageStatus::Delivered;
                $this->request->data['ApnsMessage']['filter_id'] = ConstIphoneApnsSentMessageStatus::Delivered;
                $this->pageTitle.= __l(' - Delivered');
            } elseif ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Failed) {
                $conditions['ApnsMessage.status'] = ConstIphoneApnsSentMessageStatus::Failed;
                $this->request->data['ApnsMessage']['filter_id'] = ConstIphoneApnsSentMessageStatus::Failed;
                $this->pageTitle.= __l(' - Failed');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
			'order' => 'ApnsMessage.created desc',
            'recursive' => 0
        );
        if (isset($this->request->params['named']['q']) && !empty($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
            $this->request->data['ApnsMessage']['q'] = $this->request->params['named']['q'];
        }
        $isFilterOptions = $this->ApnsMessage->isFilterOptions;
        $this->set('apnsMessages', $this->paginate());
        $this->set('isFilterOptions', $isFilterOptions);
        $this->set('pageTitle', $this->pageTitle);
    }
}
?>