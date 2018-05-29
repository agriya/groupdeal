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
class City extends AppModel
{
    public $name = 'City';
    public $displayField = 'name';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'name'
            )
        )
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'Language' => array(
            'className' => 'Language',
            'foreignKey' => 'language_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $hasMany = array(
        'Subscription' => array(
            'className' => 'Subscription',
            'foreignKey' => 'city_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    public $hasOne = array(
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'Attachment.class =' => 'City'
            ) ,
            'dependent' => true
        ) ,
        'MailChimpList' => array(
            'className' => 'MailChimpList',
            'foreignKey' => 'city_id',
            'dependent' => true,
            'conditions' => '',
        )
    );
    public $hasAndBelongsToMany = array(
        'Deal' => array(
            'className' => 'Deal',
            'joinTable' => 'cities_deals',
            'foreignKey' => 'city_id',
            'associationForeignKey' => 'deal_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->moreActions = array(
            ConstMoreAction::Inactive => __l('Unserved') ,
            ConstMoreAction::Active => __l('Served') ,
            ConstMoreAction::Delete => __l('Delete')
        );
        $this->StretchOptions = array(
            ConstBackgroundStretchType::Repeat => __l('Repeat') ,
            ConstBackgroundStretchType::Stretch => __l('Stretch') ,
            ConstBackgroundStretchType::AutoResize => __l('Auto Resize') ,
        );
        $this->validate = array(
            'name' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'state_id' => array(
                'rule' => 'numeric',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'country_id' => array(
                'rule' => 'numeric',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'City' => array(
                'rule' => 'is_check',
                'message' => __l('Required') ,
                'allowEmpty' => false
            )
        );
    }
    function is_check()
    {
        if (empty($this->data['City']['City'])) {
            return false;
        }
        return true;
    }
    function findOrSaveCityAndGetId($name, $state_id, $country_id, $latitude, $longitude)
    {
        $findExist = $this->find('first', array(
            'conditions' => array(
                'name' => $name
            ) ,
            'fields' => array(
                'id'
            ) ,
            'recursive' => -1
        ));
        if (!empty($findExist)) {
            return $findExist[$this->name]['id'];
        } else {
            $data['City']['name'] = $name;
            if (!empty($state_id)) $data['City']['state_id'] = $state_id;
            if (!empty($country_id)) $data['City']['country_id'] = $country_id;
            if (!empty($latitude)) $data['City']['latitude'] = $latitude;
            if (!empty($longitude)) $data['City']['longitude'] = $longitude;
            $this->create();
            $this->set($data['City']);
            $this->save($data['City']);
            return $this->getLastInsertId();;
        }
    }
    function deleteAllCache()
    {
        $cities = $this->find('all', array(
            'conditions' => array(
                'City.is_approved' => 1
            ) ,
            'fields' => array(
                'City.id'
            ) ,
            'recursive' => -1
        ));
        foreach($cities as $city) {
            $this->deleteCache($city['City']['id']);
        }
    }
    function afterSave($created)
    {
        Cache::delete('cake_site_city_detail_');
        if (!empty($this->data['City']['slug'])) {
            Cache::delete('cake_site_city_detail_' . $this->data['City']['slug']);
            Cache::delete('site_cities_languages_' . $this->data['City']['slug']);
        }
    }
    function afterDelete()
    {
        $this->deleteAllCache();
    }
    function deleteCache($id)
    {
        // cake_element_42550_cities_index
        @unlink(TMP . DS . 'cache' . DS . 'views' . DS . 'cake_element_' . $id . '_cities_index');
        @unlink(TMP . DS . 'cache' . DS . 'views' . DS . 'cake_element_0_cities_index');
    }
}
?>
