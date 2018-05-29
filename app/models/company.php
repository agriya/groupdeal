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
class Company extends AppModel
{
    public $name = 'Company';
    public $displayField = 'name';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'name'
            )
        ) ,
        'Aggregatable',
    );
    var $aggregatingFields = array(
        'total_upcoming_count' => array(
            'mode' => 'real',
            'key' => 'company_id',
            'foreignKey' => 'company_id',
            'model' => 'Deal',
            'function' => 'COUNT(Deal.deal_status_id)',
            'conditions' => array(
                'Deal.parent_id' => NULL,
                'Deal.deal_status_id' => ConstDealStatus::Upcoming
            )
        ) ,
        'total_open_count' => array(
            'mode' => 'real',
            'key' => 'company_id',
            'foreignKey' => 'company_id',
            'model' => 'Deal',
            'function' => 'COUNT(Deal.deal_status_id)',
            'conditions' => array(
                'Deal.parent_id' => NULL,
                'Deal.deal_status_id' => ConstDealStatus::Open
            )
        ) ,
        'total_canceled_count' => array(
            'mode' => 'real',
            'key' => 'company_id',
            'foreignKey' => 'company_id',
            'model' => 'Deal',
            'function' => 'COUNT(Deal.deal_status_id)',
            'conditions' => array(
                'Deal.parent_id' => NULL,
                'Deal.deal_status_id' => ConstDealStatus::Canceled
            )
        ) ,
        'total_expired_count' => array(
            'mode' => 'real',
            'key' => 'company_id',
            'foreignKey' => 'company_id',
            'model' => 'Deal',
            'function' => 'COUNT(Deal.deal_status_id)',
            'conditions' => array(
                'Deal.parent_id' => NULL,
                'Deal.deal_status_id' => ConstDealStatus::Expired
            )
        ) ,
        'total_tipped_count' => array(
            'mode' => 'real',
            'key' => 'company_id',
            'foreignKey' => 'company_id',
            'model' => 'Deal',
            'function' => 'COUNT(Deal.deal_status_id)',
            'conditions' => array(
                'Deal.parent_id' => NULL,
                'Deal.deal_status_id' => ConstDealStatus::Tipped
            )
        ) ,
        'total_closed_count' => array(
            'mode' => 'real',
            'key' => 'company_id',
            'foreignKey' => 'company_id',
            'model' => 'Deal',
            'function' => 'COUNT(Deal.deal_status_id)',
            'conditions' => array(
                'Deal.parent_id' => NULL,
                'Deal.deal_status_id' => ConstDealStatus::Closed
            )
        ) ,
        'total_refunded_count' => array(
            'mode' => 'real',
            'key' => 'company_id',
            'foreignKey' => 'company_id',
            'model' => 'Deal',
            'function' => 'COUNT(Deal.deal_status_id)',
            'conditions' => array(
                'Deal.parent_id' => NULL,
                'Deal.deal_status_id' => ConstDealStatus::Refunded
            )
        ) ,
        'total_paid_to_company_count' => array(
            'mode' => 'real',
            'key' => 'company_id',
            'foreignKey' => 'company_id',
            'model' => 'Deal',
            'function' => 'COUNT(Deal.deal_status_id)',
            'conditions' => array(
                'Deal.parent_id' => NULL,
                'Deal.deal_status_id' => ConstDealStatus::PaidToCompany
            )
        ) ,
        'total_pending_approval_count' => array(
            'mode' => 'real',
            'key' => 'company_id',
            'foreignKey' => 'company_id',
            'model' => 'Deal',
            'function' => 'COUNT(Deal.deal_status_id)',
            'conditions' => array(
                'Deal.parent_id' => NULL,
                'Deal.deal_status_id' => ConstDealStatus::PendingApproval
            )
        ) ,
        'total_rejected_count' => array(
            'mode' => 'real',
            'key' => 'company_id',
            'foreignKey' => 'company_id',
            'model' => 'Deal',
            'function' => 'COUNT(Deal.deal_status_id)',
            'conditions' => array(
                'Deal.parent_id' => NULL,
                'Deal.deal_status_id' => ConstDealStatus::Rejected
            )
        ) ,
        'total_draft_count' => array(
            'mode' => 'real',
            'key' => 'company_id',
            'foreignKey' => 'company_id',
            'model' => 'Deal',
            'function' => 'COUNT(Deal.deal_status_id)',
            'conditions' => array(
                'Deal.parent_id' => NULL,
                'Deal.deal_status_id' => ConstDealStatus::Draft
            )
        ) ,
        'deal_count' => array(
            'mode' => 'real',
            'key' => 'company_id',
            'foreignKey' => 'company_id',
            'model' => 'Deal',
            'function' => 'COUNT(Deal.id)',
            'conditions' => array(
                'Deal.parent_id' => NULL,
            )
        )
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
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
    public $hasMany = array(
        'Deal' => array(
            'className' => 'Deal',
            'foreignKey' => 'company_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'CompanyAddress' => array(
            'className' => 'CompanyAddress',
            'foreignKey' => 'company_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'CompanyView' => array(
            'className' => 'CompanyView',
            'foreignKey' => 'company_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => true
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'name' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'slug' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'address1' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'email' => array(
                'rule' => 'email',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'user_id' => array(
                'rule' => 'numeric',
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
            ) ,
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
            )
        );
        $this->moreActions = array(
            ConstMoreAction::EnableCompanyProfile => __l('Enable Profile') ,
            ConstMoreAction::DisableCompanyProfile => __l('Disable Profile') ,
            ConstMoreAction::Active => __l('Activate') ,
            ConstMoreAction::Inactive => __l('Deactivate') ,
        );
    }
    function is_check_address()
    {
        if (empty($this->data['Company']['address1']) || empty($this->data['Company']['latitude']) || empty($this->data['Company']['longitude']) || empty($this->data['Company']['country_id']) || empty($this->data['Company']['city_id'])) {
            return false;
        }
        return true;
    }
    function _getCompanyDeal($slug = '', $lat = 0, $lag = 0)
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
                    'Company.id',
                    'Company.latitude',
                    'Company.longitude',
                    '( 6371 * acos( cos( radians(' . $lat . ') ) * cos( radians( Company.latitude ) ) * cos( radians( Company.longitude ) - radians(' . $lag . ') ) + sin( radians(' . $lat . ') ) * sin( radians( Company.latitude ) ) ) ) AS distance'
                ) ,
                'group' => array(
                    'Company.id HAVING distance <' . Configure::read('deal.nearby_deal_km')
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
                foreach($company['Deal'] as $deal) {
                    $deals[$deal['id']] = array(
                        'id' => $deal['id'],
                        'latitude' => $company['Company']['latitude'],
                        'longitude' => $company['Company']['longitude'],
                        'distance' => $company[0]['distance']
                    );
                }
            }
        }
        return $deals;
    }
    function company_near_user_count_update($type = 'normal')
    {
        $companies = $this->find('all', array(
            'fields' => array(
                'Company.id',
                'Company.name',
                'Company.latitude',
                'Company.longitude',
            ) ,
            'recursive' => -1
        ));
        foreach($companies as $company) {
            $field = '';
            $conditions = array();
            $start_date = date('Y-m-d H:i:s', mktime(date("H") , date("i") -30, date("s") , date("m") , date("d") , date("Y")));
            $end_date = date('Y-m-d H:i:s');
            $userprofiles = array();
            if ($type == 'normal') {
                $field = '( 6371 * acos( cos( radians(' . $company['Company']['latitude'] . ') ) * cos( radians( UserProfile.latitude ) ) * cos( radians( UserProfile.longitude ) - radians(' . $company['Company']['longitude'] . ') ) + sin( radians(' . $company['Company']['latitude'] . ') ) * sin( radians( UserProfile.latitude ) ) ) ) AS distance';
                $conditions['UserProfile.last_access BETWEEN ? AND ?'] = array(
                    $start_date,
                    $end_date
                );
                $userprofiles = $this->User->UserProfile->find('all', array(
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
            } else {
                $field = '( 6371 * acos( cos( radians(' . $company['Company']['latitude'] . ') ) * cos( radians( User.iphone_latitude ) ) * cos( radians( User.iphone_longitude ) - radians(' . $company['Company']['longitude'] . ') ) + sin( radians(' . $company['Company']['latitude'] . ') ) * sin( radians( User.iphone_latitude ) ) ) ) AS distance';
                $conditions['User.iphone_last_access BETWEEN ? AND ?'] = array(
                    $start_date,
                    $end_date
                );
                $userprofiles = $this->User->find('all', array(
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
            }
            $count_data = array(
                'Company.near_user_count' => count($userprofiles)
            );
            if ($type != 'normal') {
                $count_data = array(
                    'Company.iphone_near_user_count' => count($userprofiles)
                );
            }
            $this->updateAll($count_data, array(
                'Company.id' => $company['Company']['id']
            ));
        }
    }
}
?>