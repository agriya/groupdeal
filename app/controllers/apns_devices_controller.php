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
class ApnsDevicesController extends AppController
{
    public $name = 'ApnsDevices';
    public function admin_index()
    {
        $this->pageTitle = __l('Devices');
        $this->_redirectGET2Named(array(
            'q',
            'filter_id'
        ));
        $conditions = array();
        $this->set('registered', $this->ApnsDevice->find('count', array(
            'conditions' => array(
                'ApnsDevice.status = ' => ConstIphoneApnsDeviceStatus::Registered,
            ) ,
            'recursive' => -1
        )));
        $this->set('unregistered', $this->ApnsDevice->find('count', array(
            'conditions' => array(
                'ApnsDevice.status = ' => ConstIphoneApnsDeviceStatus::Unregistered,
            ) ,
            'recursive' => -1
        )));
        $this->set('all', $this->ApnsDevice->find('count', array(
            'recursive' => -1
        )));
        if (!empty($this->request->data['ApnsDevice']['filter_id'])) {
            $this->request->params['named']['filter_id'] = $this->request->data['ApnsDevice']['filter_id'];
        }
        if (!empty($this->request->data['ApnsDevice']['q'])) {
            $this->request->params['named']['q'] = $this->request->data['ApnsDevice']['q'];
        }
        if (!empty($this->request->data['ApnsDevice']['main_filter_id'])) {
            $this->request->params['named']['main_filter_id'] = $this->request->data['ApnsDevice']['main_filter_id'];
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            $this->request->data['ApnsDevice']['filter_id'] = $this->request->params['named']['filter_id'];
            $conditions['ApnsDevice.status'] = $this->request->params['named']['filter_id'];
        }
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'yesterday') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ApnsDevice.created)'] = 1;
            $this->pageTitle.= __l(' - Registered Yesterday');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ApnsDevice.created) <= '] = 0;
            $this->pageTitle = __l('Device - Registered today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ApnsDevice.created) <= '] = 7;
            $this->pageTitle = __l('Device - Registered in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ApnsDevice.created) <= '] = 30;
            $this->pageTitle = __l('Device - Registered in this month');
        }
        if (!empty($this->request->params['named']['main_filter_id'])) {
            if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Registered) {
                $conditions['ApnsDevice.status'] = ConstIphoneApnsDeviceStatus::Registered;
                $this->request->data['ApnsDevice']['filter_id'] = ConstIphoneApnsDeviceStatus::Registered;
                $this->pageTitle = __l('Registered Device');
            } elseif ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Unregistered) {
                $conditions['ApnsDevice.status'] = ConstIphoneApnsDeviceStatus::Unregistered;
                $this->request->data['ApnsDevice']['filter_id'] = ConstIphoneApnsDeviceStatus::Unregistered;
                $this->pageTitle = __l('Unregistered Device');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
			'order' => 'ApnsDevice.created desc',
            'recursive' => 0
        );
        if (isset($this->request->params['named']['q']) && !empty($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
            $this->request->data['ApnsDevice']['q'] = $this->request->params['named']['q'];
        }
        $isFilterOptions = $this->ApnsDevice->isFilterOptions;
        $this->set('isFilterOptions', $isFilterOptions);
        $this->set('apnsDevices', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    function admin_broadcast()
    {
        $this->pageTitle = __l('Send Broadcast Push Message');
        $push_mail_setting = Configure::read('subscription.iphone_apns_push_mail');
        //if(!Configure::read('subscription.iphone_apns_push_mail_enable')){
        //throw new NotFoundException(__l('Invalid request'));
        //}
        if (!empty($this->request->data)) {
            $this->ApnsDevice->set($this->request->data);
            if ($this->ApnsDevice->validates()) {
                $conditions = $exception_device_token = array();
                $is_sent = false;
                $conditions['ApnsDevice.status'] = ConstIphoneApnsDeviceStatus::Registered;
                if ($push_mail_setting == ConstIphoneApnsPushMail::Site) {
                    $conditions['ApnsDevice.pushalert'] = 'enabled';
                    // Includes //
                    include_once (APP . DS . 'vendors' . DS . 'iphone-apns' . DS . 'classes' . DS . 'class_APNS.php');
                    include_once (APP . DS . 'vendors' . DS . 'iphone-apns' . DS . 'classes' . DS . 'class_DbConnect.php');
                    // Cert., Path //
                    $certificate = APP . 'vendors' . DS . 'iphone-apns' . DS . 'certificate' . DS . 'apns.pem';
                    $sandbox_certificate = APP . 'vendors' . DS . 'iphone-apns' . DS . 'certificate' . DS . 'apns_dev.pem';
                    $log_path = APP . 'TMP' . DS . 'logs' . DS . 'apns.log';
                    $db = new DbConnect();
                    $apns = new APNS($db, '', $certificate, $sandbox_certificate, $log_path);
                } elseif ($push_mail_setting == ConstIphoneApnsPushMail::UrbanAirShip) {
                    $conditions['ApnsDevice.pushalert'] = 'disabled';
                    // Includes //
                    include_once (APP . DS . 'vendors' . DS . 'urbanairship' . DS . 'urbanairship.php');
                    $airship = new Airship(Configure::read('subscription.urbanairship_app_key') , Configure::read('subscription.urbanairship_master_key'));
                }
                $iphone_users = $this->ApnsDevice->find('all', array(
                    'conditions' => $conditions,
                    'fields' => array(
                        'ApnsDevice.pid',
                        'ApnsDevice.user_id',
                        'ApnsDevice.devicetoken',
                    ) ,
                    'recursive' => -1
                ));
                foreach($iphone_users as $iphone_user) {
                    if ($push_mail_setting == ConstIphoneApnsPushMail::Site) {
                        $apns->newMessage($iphone_user['ApnsDevice']['pid'], '0000-00-00 00:00:00', $iphone_user['ApnsDevice']['user_id']);
                        $apns->addMessageAlert($this->request->data['ApnsDevice']['message']);
                        $apns->queueMessage();
                        $is_sent = true;
                    } else if ($push_mail_setting == ConstIphoneApnsPushMail::UrbanAirShip) { // IF AirShip Enabled
                        $exception_device_token[] = $iphone_user['ApnsDevice']['devicetoken'];
                    }
                }
                if ($push_mail_setting == ConstIphoneApnsPushMail::UrbanAirShip) { // IF AirShip Enabled
                    $broadcast_message = array(
                        'aps' => array(
                            'alert' => $this->request->data['ApnsDevice']['message']
                        )
                    );
                    $response = $airship->broadcast($broadcast_message, array(
                        $exception_device_token
                    ));
                    if (!empty($response['is_success'])) {
                        $is_sent = true;
                    }
                }
                if ($is_sent === true) $this->Session->setFlash(__l('Broadcast message succesfully sent.') , 'default', null, 'success');
                else $this->Session->setFlash(__l('Broadcast message Could not be sent. Please, try again.') , 'default', null, 'error');
            } else {
                $this->Session->setFlash(__l('Broadcast message not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
}
