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
class ApnsMessage extends AppModel
{
    public $name = 'ApnsMessage';
    public $primaryKey = 'pid';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->isFilterOptions = array(
            'queued' => __l('Queued') ,
            'delivered' => __l('Delivered') ,
            'failed' => __l('Failed') ,
        );
    }
    function _sendIphonePushMessage($email_contents)
    {
        $push_mail_setting = Configure::read('subscription.iphone_apns_push_mail');
        App::import('Model', 'Subscription');
        $this->Subscription = new Subscription();
        if ($push_mail_setting == ConstIphoneApnsPushMail::Site) { // Enabled for site
            include_once (APP . DS . 'vendors' . DS . 'iphone-apns' . DS . 'classes' . DS . 'class_APNS.php');
            include_once (APP . DS . 'vendors' . DS . 'iphone-apns' . DS . 'classes' . DS . 'class_DbConnect.php');
            // Cert., Path //
            $certificate = APP . 'vendors' . DS . 'iphone-apns' . DS . 'certificate' . DS . 'apns.pem';
            $sandbox_certificate = APP . 'vendors' . DS . 'iphone-apns' . DS . 'certificate' . DS . 'apns_dev.pem';
            $log_path = APP . 'TMP' . DS . 'logs' . DS . 'apns.log';
            $db = new DbConnect();
            $apns = new APNS($db, '', $certificate, $sandbox_certificate, $log_path);
            $replace_message = array();
        } else if ($push_mail_setting == ConstIphoneApnsPushMail::UrbanAirShip) { // IF AirShip Enabled
            include_once (APP . DS . 'vendors' . DS . 'urbanairship' . DS . 'urbanairship.php');
            $airship = new Airship(Configure::read('subscription.urbanairship_app_key') , Configure::read('subscription.urbanairship_master_key'));
        }
        foreach($email_contents as $city_id => $contents) {
            $push_message = array();
            $no_of_deal = 0;
            if (!empty($contents)) {
                foreach($contents as $content) {
                    if (is_array($content)) {
                        $no_of_deal+= 1;
                    }
                }
            }
            if ($no_of_deal > 1) {
                $message_skel['##DEAL_COUNT##'] = $no_of_deal;
                $message_skel['##CITY##'] = $contents['##CITY_SLUG##'];
                $message = strtr(Configure::read('subscription.iphone_short_push_message_format') , $message_skel);
            } else {
                $message_skel = array();
                $message_skel['##DEAL_NAME##'] = '"' . $contents[0]['##DEAL_NAME##'] . '"';
                $message_skel['##CITY##'] = '"' . $contents['##CITY_SLUG##'] . '"';
                $message = strtr(Configure::read('subscription.iphone_push_message_format') , $message_skel);
                if (strlen($message) > 256) {
                    $message = strtr(Configure::read('subscription.iphone_short_push_message_format') , $message_skel);
                }
            }
            $push_message[$city_id]['message'] = $message;
            if (!empty($push_message[$city_id]['message'])) {
                $subscribtions = $this->Subscription->find('all', array(
                    'conditions' => array(
                        'Subscription.city_id' => $city_id,
                        'Subscription.user_id !=' => 0,
                        'Subscription.is_subscribed' => 1,
                    ) ,
                    'contain' => array(
                        'User' => array(
                            'fields' => array(
                                'User.id',
                                'User.username',
                            ) ,
                            'ApnsDevice' => array(
                                'conditions' => array(
                                    'ApnsDevice.pushalert' => 'enabled',
                                    'ApnsDevice.status' => ConstIphoneApnsDeviceStatus::Registered,
                                )
                            )
                        )
                    ) ,
                    'recursive' => 2
                ));
                if (!empty($subscribtions)) {
                    foreach($subscribtions as $subscribtion) {
                        if (!empty($subscribtion['User']['ApnsDevice'])) {
                            foreach($subscribtion['User']['ApnsDevice'] as $apndevice) {
                                $push_message[$city_id]['user'][$apndevice['pid']] = array(
                                    'uid' => $subscribtion['User']['id'],
                                    'pid' => $apndevice['pid']['pid'],
                                );
                            }
                        }
                        $push_message[$city_id]['city'] = $contents[0]['##CITY_NAME##'];
                    }
                    if (!empty($push_message[$city_id]) && !empty($push_message[$city_id]['user'])) {
                        foreach($push_message[$city_id]['user'] as $user) {
                            if (!empty($user['pid'])) {
                                if ($push_mail_setting == ConstIphoneApnsPushMail::Site) { // Enabled for site
                                    $apns->newMessage($user['pid'], '0000-00-00 00:00:00', $user['uid']);
                                    $apns->addMessageAlert($push_message[$city_id]['message']);
                                    $apns->queueMessage();
                                } else if ($push_mail_setting == ConstIphoneApnsPushMail::UrbanAirShip) { // IF AirShip Enabled
                                    $uas_message = array(
                                        'aps' => array(
                                            'alert' => $push_message[$city_id]['message']
                                        )
                                    );
                                    $airship->push($uas_message, null, array(
                                        $user['pid']
                                    ));
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
