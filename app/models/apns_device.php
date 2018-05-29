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
class ApnsDevice extends AppModel
{
    public $name = 'ApnsDevice';
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
        $this->validate = array(
            'message' => array(
                'rule2' => array(
                    'rule' => array(
                        'between',
                        '1',
                        '256'
                    ) ,
                    'message' => sprintf(__l('Must be between of') . ' ' . '1' . ' to ' . '256')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required') ,
                ) ,
            ) ,
        );
        $this->isFilterOptions = array(
            'unregistered' => __l('Unregistered') ,
            'registered' => __l('Registered')
        );
    }
    function findOrSave_apns_device($user_id, $data)
    {
        $findExist = $this->find('first', array(
            'conditions' => array(
                'user_id' => $user_id,
                'devicetoken' => $data['devicetoken']
            ) ,
            'recursive' => -1
        ));
        if (empty($findExist)) {
            if (Configure::read('subscription.iphone_certification_mode') == ConstIphoneCertificationMode::Live) {
                $development = 'production';
            } elseif (Configure::read('subscription.iphone_certification_mode') == ConstIphoneCertificationMode::Sandbox) {
                $development = 'sandbox';
            }
            $data['development'] = $development;
            $data['user_id'] = $user_id;
            $this->create();
            $this->set($data);
            $this->save($data);
            if (Configure::read('subscription.iphone_apns_push_mail_enable') && Configure::read('subscription.iphone_apns_push_mail') == ConstIphoneApnsPushMail::UrbanAirShip) {
                include_once (APP . DS . 'vendors' . DS . 'urbanairship' . DS . 'urbanairship.php');
                $airship = new Airship(Configure::read('subscription.urbanairship_app_key') , Configure::read('subscription.urbanairship_master_key'));
                $airship->register($data['devicetoken'], $this->getLastInsertId());
            }
        }
    }
}
