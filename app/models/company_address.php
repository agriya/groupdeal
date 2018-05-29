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
class CompanyAddress extends AppModel
{
    public $name = 'CompanyAddress';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'company_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    public $hasAndBelongsToMany = array(
        'Deal' => array(
            'className' => 'Deal',
            'joinTable' => 'company_addresses_deals',
            'foreignKey' => 'company_address_id',
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
        $this->validate = array(
            'address2' => array(
                'rule2' => array(
                    'rule' => 'is_check_address',
                    'message' => __l('Must be Enter Detail Address') ,
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required') ,
                    'allowEmpty' => false
                )
            ) ,
            'address1' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'city_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'state_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'country_id' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'zip' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'url' => array(
                'rule2' => array(
                    'rule' => array(
                        'url'
                    ) ,
                    'message' => __l('Must be a valid URL, starting with http://') ,
                    'allowEmpty' => true
                ) ,
                'rule1' => array(
                    'rule' => array(
                        'custom',
                        '/^http:\/\//'
                    ) ,
                    'message' => __l('Must be a valid URL, starting with http://') ,
                    'allowEmpty' => true
                )
            )
        );
    }
    function is_check_address()
    {
        if (empty($this->data['CompanyAddress']['address1']) || empty($this->data['CompanyAddress']['latitude']) || empty($this->data['CompanyAddress']['longitude']) || empty($this->data['CompanyAddress']['country_id']) || empty($this->data['CompanyAddress']['country_id']) || empty($this->data['CompanyAddress']['country_id'])) {
            return false;
        }
        return true;
    }
    function _getCompanyAddressDeal($slug = '', $lat = 0, $lag = 0)
    {
        $deals = array();
        $distance = Configure::read('deal.nearby_deal_km');
        $city_slug = $slug;
        if (!empty($slug)) {
            $city = $this->City->find('first', array(
                'conditions' => array(
                    'City.slug' => $slug
                ) ,
                'recursive' => -1
            ));
            $lat = $city['City']['latitude'];
            $lag = $city['City']['longitude'];
        }
        if (!empty($lat) || !empty($lag)) {
            $companies = $this->find('all', array(
                'fields' => array(
                    'CompanyAddress.id',
                    'CompanyAddress.latitude',
                    'CompanyAddress.longitude',
                    '( 6371 * acos( cos( radians(' . $lat . ') ) * cos( radians( CompanyAddress.latitude ) ) * cos( radians( CompanyAddress.longitude ) - radians(' . $lag . ') ) + sin( radians(' . $lat . ') ) * sin( radians( CompanyAddress.latitude ) ) ) ) AS distance'
                ) ,
                'group' => array(
                    'CompanyAddress.id HAVING distance <' . Configure::read('deal.nearby_deal_km')
                ) ,
                'contain' => array(
                    'Deal' => array(
                        'conditions' => array(
                            'Deal.deal_status_id' => array(
                                ConstDealStatus::Open,
                                ConstDealStatus::Tipped
                            ) ,
                            'Deal.is_now_deal' => 1,
                            'Deal.is_hold' => 0,
                            'Deal.parent_id' => null,
                        ) ,
                        'fields' => array(
                            'Deal.id'
                        )
                    )
                ) ,
                'order' => array(
                    'distance' => 'asc'
                ) ,
                'recursive' => 2,
            ));
            foreach($companies as $company) {
                if (!empty($company['Deal'])) {
                    foreach($company['Deal'] as $deal) {
                        $deals[$deal['id']] = array(
                            'id' => $deal['id'],
                            'latitude' => $company['CompanyAddress']['latitude'],
                            'longitude' => $company['CompanyAddress']['longitude'],
                            'distance' => $company[0]['distance']
                        );
                    }
                }
            }
        }
        return $deals;
    }
    function company_address_near_user_count_update($type = 'normal')
    {
        $companies = $this->find('all', array(
            'fields' => array(
                'CompanyAddress.id',
                'CompanyAddress.latitude',
                'CompanyAddress.longitude',
            ) ,
            'recursive' => -1
        ));
        $field = '';
        $conditions = array();
        $start_date = date('Y-m-d H:i:s', mktime(date("H") , date("i") -30, date("s") , date("m") , date("d") , date("Y")));
        $end_date = date('Y-m-d H:i:s');
        $userprofiles = array();
        foreach($companies as $company) {
            if ($type == 'normal') {
                $field = '( 6371 * acos( cos( radians(' . $company['CompanyAddress']['latitude'] . ') ) * cos( radians( UserProfile.latitude ) ) * cos( radians( UserProfile.longitude ) - radians(' . $company['CompanyAddress']['longitude'] . ') ) + sin( radians(' . $company['CompanyAddress']['latitude'] . ') ) * sin( radians( UserProfile.latitude ) ) ) ) AS distance';
                $conditions['UserProfile.last_access BETWEEN ? AND ?'] = array(
                    $start_date,
                    $end_date
                );
                if (!empty($company['CompanyAddress']['latitude']) && !empty($company['CompanyAddress']['longitude'])) {
                    $userprofiles = $this->Company->User->UserProfile->find('all', array(
                        'conditions' => $conditions,
                        'fields' => array(
                            'UserProfile.user_id',
                            $field
                        ) ,
                        'group' => array(
                            'UserProfile.id HAVING distance <' . Configure::read('deal.nearby_deal_km')
                        ) ,
                        'order' => array(
                            'distance' => 'asc'
                        ) ,
                        'recursive' => -1,
                    ));
                    $count_data = array(
                        'CompanyAddress.near_user_count' => count($userprofiles)
                    );
                    $this->updateAll($count_data, array(
                        'CompanyAddress.id' => $company['CompanyAddress']['id']
                    ));
                }
            } else {
                $field = '( 6371 * acos( cos( radians(' . $company['CompanyAddress']['latitude'] . ') ) * cos( radians( User.iphone_latitude ) ) * cos( radians( User.iphone_longitude ) - radians(' . $company['CompanyAddress']['longitude'] . ') ) + sin( radians(' . $company['CompanyAddress']['latitude'] . ') ) * sin( radians( User.iphone_latitude ) ) ) ) AS distance';
                $conditions['User.iphone_last_access BETWEEN ? AND ?'] = array(
                    $start_date,
                    $end_date
                );
                if (!empty($company['CompanyAddress']['latitude']) && !empty($company['CompanyAddress']['longitude'])) {
                    $userprofiles = $this->Company->User->find('all', array(
                        'conditions' => $conditions,
                        'fields' => array(
                            'User.id',
                            $field
                        ) ,
                        'group' => array(
                            'User.id HAVING distance <' . Configure::read('deal.nearby_deal_km')
                        ) ,
                        'order' => array(
                            'distance' => 'asc'
                        ) ,
                        'recursive' => -1,
                    ));
                    $count_data = array(
                        'CompanyAddress.iphone_near_user_count' => count($userprofiles)
                    );
                    $this->updateAll($count_data, array(
                        'CompanyAddress.id' => $company['CompanyAddress']['id']
                    ));
                }
            }
        }
    }
}
?>