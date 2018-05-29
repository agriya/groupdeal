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
class Deal extends AppModel
{
    public $name = 'Deal';
    public $displayField = 'name';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'name'
            )
        ) ,
        'Aggregatable',
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
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'company_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'DealStatus' => array(
            'className' => 'DealStatus',
            'foreignKey' => 'deal_status_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'Charity' => array(
            'className' => 'Charity',
            'foreignKey' => 'charity_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'DealRepeatType' => array(
            'className' => 'DealRepeatType',
            'foreignKey' => 'deal_repeat_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'DealCategory' => array(
            'className' => 'DealCategory',
            'foreignKey' => 'deal_category_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
		    'counterCache' => true
        ) ,
    );
    public $hasMany = array(
        'SubDeal' => array(
            'className' => 'SubDeal',
            'foreignKey' => 'parent_id',
            'dependent' => true,
            'conditions' => array(
                'SubDeal.parent_id' => 'Deal.id',
            ) ,
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'Attachment.class =' => 'Deal'
            ) ,
            'dependent' => true
        ) ,
        'DealUser' => array(
            'className' => 'DealUser',
            'foreignKey' => 'deal_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'PagseguroTransactionLog' => array(
            'className' => 'PagseguroTransactionLog',
            'foreignKey' => 'deal_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'DealReferrer' => array(
            'className' => 'DealReferrer',
            'foreignKey' => 'deal_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'CompanyAddressesDeal' => array(
            'className' => 'CompanyAddressesDeal',
            'foreignKey' => 'deal_id',
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
        'DealCoupon' => array(
            'className' => 'DealCoupon',
            'foreignKey' => 'deal_id',
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
        'Transaction' => array(
            'className' => 'Transaction',
            'foreignKey' => 'foreign_id',
            'dependent' => true,
            'conditions' => array(
                'Transaction.class' => 'Deal'
            ) ,
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'CitiesDeal' => array(
            'className' => 'CitiesDeal',
            'foreignKey' => 'deal_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'DealView' => array(
            'className' => 'DealView',
            'foreignKey' => 'deal_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => true,
        ) ,
    );
    public $hasAndBelongsToMany = array(
        'City' => array(
            'className' => 'City',
            'joinTable' => 'cities_deals',
            'foreignKey' => 'deal_id',
            'associationForeignKey' => 'city_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        ) ,
        'RepeatDate' => array(
            'className' => 'RepeatDate',
            'joinTable' => 'deals_repeat_dates',
            'foreignKey' => 'deal_id',
            'associationForeignKey' => 'repeat_date_id',
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
    function _checkCommissionAmount($data)
    {
        $is_valid_field = true;
        if (($_SESSION['Auth']['User']['id'] != ConstUserTypes::Admin) && (Configure::read('deal.is_admin_enable_commission'))) {
            if (!empty($this->data['Deal']['original_price']) && !empty($this->data['Deal']['discount_amount'])) {
                if (Configure::read('deal.commission_amount_type') == 'minimum') {
                    if (($this->data['Deal']['commission_percentage']) < Configure::read('deal.commission_amount')) {
                        $is_valid_field = false;
                    }
                } else {
                    if (($this->data['Deal']['commission_percentage']) != Configure::read('deal.commission_amount')) {
                        $is_valid_field = false;
                    }
                }
            }
        }
        return $is_valid_field;
    }
    function _checkCommissionAmountSubdeal($data)
    {
        $is_valid_field = true;
        if (($_SESSION['Auth']['User']['user_type_id'] != ConstUserTypes::Admin) && (Configure::read('deal.is_admin_enable_commission'))) {
            if (Configure::read('deal.commission_amount_type') == 'minimum') {
                if (($data['commission_percentage']) < Configure::read('deal.commission_amount')) {
                    $is_valid_field = false;
                }
            } else {
                if (($data['commission_percentage']) != Configure::read('deal.commission_amount')) {
                    $is_valid_field = false;
                }
            }
        }
        return $is_valid_field;
    }
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'is_redeem_in_main_address' => array(
                'rule2' => array(
                    'rule' => array(
                        '_checkRedeemLocation'
                    ) ,
                    'message' => __l('Should be select any one Redeem location')
                ) ,
                'rule1' => array(
                    'rule' => array(
                        '_checkRedeemLocationCompany'
                    ) ,
                    'message' => __l('Should be select merchant After Select Redeem Location')
                )
            ) ,
            'user_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'deal_category_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'name' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'original_price' => array(
                'rule2' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                ) ,
            ) ,
            'discounted_price' => array(
                'rule3' => array(
                    'rule' => array(
                        'comparison',
                        'equal to',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be equal to 0')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                ) ,
            ) ,
            'discount_percentage' => array(
                'rule2' => array(
                    'rule' => array(
                        'range',
                        0,
                        101
                    ) ,
                    'allowEmpty' => true,
                    'message' => __l('Should be greater than 0 and less than 100')
                ) ,
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => true,
                    'message' => __l('Should be a number')
                ) ,
            ) ,
            'min_limit' => array(
                'rule4' => array(
                    'rule' => array(
                        '_checkMaxWithSubDealLimt'
                    ) ,
                    'message' => __l('The given minimum coupon count is larger than total maximum coupon count of the subdeals. Update the maximum coupon count of the subdeal first.')
                ) ,
                'rule3' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'custom',
                        '/^[1-9]\d*\.?[0]*$/'
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be a number')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
            'max_limit' => array(
                'rule3' => array(
                    'rule' => array(
                        '_checkMaxLimt'
                    ) ,
                    'allowEmpty' => true,
                    'message' => __l('Maximum limit should be greater than or equal to minimum limit')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => true,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule1' => array(
                    'rule' => array(
                        'custom',
                        '/^[1-9]\d*\.?[0]*$/'
                    ) ,
                    'allowEmpty' => true,
                    'message' => __l('Should be a number')
                ) ,
            ) ,
            'buy_min_quantity_per_user' => array(
                'rule4' => array(
                    'rule' => array(
                        '_compareDealAndBuyMinLimt'
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Minimum buy limit should be less than or equal to deal maximum limit')
                ) ,
                'rule3' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'custom',
                        '/^[0-9]\d*\.?[0]*$/'
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be a number')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
            'buy_max_quantity_per_user' => array(
                'rule4' => array(
                    'rule' => array(
                        '_checkMaxQuantityLimt'
                    ) ,
                    'allowEmpty' => true,
                    'message' => __l('Maximum limit should be greater than or equal to minimum limit')
                ) ,
                'rule3' => array(
                    'rule' => array(
                        '_compareDealAndBuyMaxLimt'
                    ) ,
                    'allowEmpty' => true,
                    'message' => __l('Maximum buy limit should be less than or equal to deal maximum limit')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => true,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule1' => array(
                    'rule' => array(
                        'custom',
                        '/^[1-9]\d*\.?[0]*$/'
                    ) ,
                    'allowEmpty' => true,
                    'message' => __l('Required')
                ) ,
            ) ,
            'city_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            /*'company_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,*/
			'company_id' => array(
                'rule' =>array('_isEmptyCompany'),
				'message' => __l('Required')
            ) ,
			
            'deal_status_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'quantity' => array(
                'rule5' => array(
                    'rule' => array(
                        '_isEligibleMaximumQuantity'
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Quantity is more than the maximum quantity.')
                ) ,
                'rule4' => array(
                    'rule' => array(
                        '_isEligibleQuantity'
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Quantity exceeds maximum quantity limit per purchase.')
                ) ,
                'rule3' => array(
                    'rule' => array(
                        '_isEligibleMinimumQuantity'
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Quantity is less than the minimum quantity.')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule1' => array(
                    'rule' => array(
                        'custom',
                        '/^[1-9]\d*\.?[0]*$/'
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be a number')
                ) ,
            ) ,
            'gift_from' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'gift_email' => array(
                'rule2' => array(
                    'rule' => 'email',
                    'allowEmpty' => false,
                    'message' => __l('Must be a valid email')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'gift_to' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'description' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'start_date' => array(
                'rule2' => array(
                    'rule' => '_isValidStartDate',
                    'message' => __l('Start date should be greater than today') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'end_date' => array(
                'rule2' => array(
                    'rule' => '_isValidEndDate',
                    'message' => __l('End date should be greater than start date') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'coupon_start_date' => array(
                'rule4' => array(
                    'rule' => '_isValidLiveDealEndStartDate',
                    'message' => __l('Deal start date, Coupon start date and Coupon Expiry date should be same for now deal.') ,
                    'allowEmpty' => false
                ) ,
                'rule3' => array(
                    'rule' => '_isValidDealEndStartDate',
                    'message' => __l('Coupon Start date should be greater than deal start date') ,
                    'allowEmpty' => false
                ) ,
                'rule2' => array(
                    'rule' => '_isValidExpiryStartDate',
                    'message' => __l('Coupon Start date should be lesser than coupon expiry date') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'coupon_expiry_date' => array(
                'rule2' => array(
                    'rule' => '_isValidExpiryDate',
                    'message' => __l('Coupon Expiry date should be greater than deal end date') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'discount_amount' => array(
                'rule2' => array(
                    'rule' => array(
                        '_checkDiscountAmount',
                        'original_price',
                        'discount_amount'
                    ) ,
                    'message' => __l('Discount amouont should be less than original amount.')
                ) ,
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
            'user_each_purchase_max_limit' => array(
                'rule1' => array(
                    'rule' => '_checkUserEachPurchaseMaxLimit',
                    'allowEmpty' => true,
                    'message' => __l('User each purchase limit Should be less than or equal to Maxmium Purchase per day')
                ) ,
            ) ,
            'commission_percentage' => array(
                'rule4' => array(
                    'rule' => array(
                        '_checkCommissionAmount'
                    ) ,
                    'allowEmpty' => false,
                    'message' => (Configure::read('deal.commission_amount_type') == 'minimum') ? __l('Should be greater than or equal to') . ' ' . Configure::read('deal.commission_amount') : __l('Should be equal to') . ' ' . Configure::read('deal.commission_amount')
                ) ,
                'rule3' => array(
                    'rule' => array(
                        'comparison',
                        'equal to',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Free deal commission percentage should be equal to 0')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Should be a number')
                ) ,
            )
        );
        $this->validateCreditCard = array(
            'firstName' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'lastName' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'creditCardNumber' => array(
                'rule2' => array(
                    'rule' => 'numeric',
                    'message' => __l('Should be numeric') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'cvv2Number' => array(
                'rule2' => array(
                    'rule' => 'numeric',
                    'message' => __l('Should be numeric') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'zip' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'address' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'city' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'state' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'country' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'creditCardType' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
        );
        $this->validateNowDeal = array(
            'quantity' => array(
                'rule5' => array(
                    'rule' => array(
                        '_isEligibleMaximumQuantityNowDeal'
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Quantity exceeds maximum quantity limit per purchase.')
                ) ,
                'rule4' => array(
                    'rule' => array(
                        '_isEligibleQuantityNowDeal'
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Quantity is more than the maximum quantity.')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule1' => array(
                    'rule' => array(
                        'custom',
                        '/^[1-9]\d*\.?[0]*$/'
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be a number')
                ) ,
            ) ,
        );
        $this->validateSubDeal = array(
            'name' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'original_price' => array(
                'rule2' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                ) ,
            ) ,
            'discounted_price' => array(
                'rule3' => array(
                    'rule' => array(
                        'comparison',
                        'equal to',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be equal to 0')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                ) ,
            ) ,
            'discount_percentage' => array(
                'rule2' => array(
                    'rule' => array(
                        'range',
                        0,
                        101
                    ) ,
                    'allowEmpty' => true,
                    'message' => __l('Should be greater than 0 and less than 100')
                ) ,
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => true,
                    'message' => __l('Should be a number')
                ) ,
            ) ,
            'max_limit' => array(
                'rule2' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => true,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule1' => array(
                    'rule' => array(
                        'custom',
                        '/^[1-9]\d*\.?[0]*$/'
                    ) ,
                    'allowEmpty' => true,
                    'message' => __l('Should be a number')
                ) ,
            ) ,
            'coupon_start_date' => array(
                'rule3' => array(
                    'rule' => '_isValidDealEndStartDate',
                    'message' => __l('Start date should be greater than deal start date') ,
                    'allowEmpty' => false
                ) ,
                'rule2' => array(
                    'rule' => '_isValidExpiryStartDate',
                    'message' => __l('Start date should be lesser than coupon expiry date') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'coupon_expiry_date' => array(
                'rule2' => array(
                    'rule' => '_isValidExpiryDate',
                    'message' => __l('Expiry date should be greater than deal end date') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'discount_amount' => array(
                'rule2' => array(
                    'rule' => array(
                        '_checkDiscountAmount',
                        'original_price',
                        'discount_amount'
                    ) ,
                    'message' => __l('Discount amouont should be less than original amount.')
                ) ,
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
            'commission_percentage' => array(
                'rule4' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                ) ,
                'rule3' => array(
                    'rule' => array(
                        '_checkCommissionAmountSubdeal'
                    ) ,
                    'allowEmpty' => false,
                    'message' => (Configure::read('deal.commission_amount_type') == 'minimum') ? __l('Should be greater than or equal to') . ' ' . Configure::read('deal.commission_amount') : __l('Should be equal to') . ' ' . Configure::read('deal.commission_amount')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'range',
                        0,
                        101
                    ) ,
                    'allowEmpty' => true,
                    'message' => __l('Should be greater than 0 and less than 100')
                ) ,
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Should be a number')
                ) ,
            ) ,
            'bonus_amount' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => true,
                    'message' => __l('Should be a number')
                ) ,
            )
        );
        $this->filters = array(
            ConstDealStatus::PendingApproval => __l('Pending Approval') ,
            ConstDealStatus::Upcoming => __l('Upcoming') ,
            ConstDealStatus::Open => __l('Open') ,
            ConstDealStatus::Closed => __l('Closed') ,
        );
        $this->repeatUntilOptions = array(
            '1' => __l('I stop it') ,
            '2' => __l('End Date') ,
        );
        $this->dealStartDate = array(
            __l('Today') => __l('Today') ,
            __l('Tomorrow') => __l('Tomorrow') ,
        );
        $this->liveDealSearch = array(
            ConstLiveDealSearchTime::Today => __l('Today') ,
            ConstLiveDealSearchTime::TodayMorning => __l('Morning (6-10am)') ,
            ConstLiveDealSearchTime::TodayMidDay => __l('Mid-day (10am-2pm)') ,
            ConstLiveDealSearchTime::TodayAfternoon => __l('Afternoon 2-6pm') ,
            ConstLiveDealSearchTime::TodayEvening => __l('Evening (6-10pm)') ,
            ConstLiveDealSearchTime::TodayLateNight => __l('Late Night (10pm-6am)') ,
            ConstLiveDealSearchTime::Tomorrow => __l('Tomorrow') ,
            ConstLiveDealSearchTime::TomorrowMorning => __l('Morning (6-10am)') ,
            ConstLiveDealSearchTime::TomorrowMidDay => __l('Mid-day (10am-2pm)') ,
            ConstLiveDealSearchTime::TomorrowAfternoon => __l('Afternoon 2-6pm') ,
            ConstLiveDealSearchTime::TomorrowEvening => __l('Evening (6-10pm)') ,
            ConstLiveDealSearchTime::TomorrowLateNight => __l('Late Night (10pm-6am)') ,
        );
        //$this->_sendIphonePushMessage();

    }
    function _checkExpiryMonthAndYear($month = null, $year = null)
    {
        $error = 0;
        if (empty($month) || empty($year)) {
            $error = 'Required';
        } elseif ($year <= date('Y')) {
            if ($month <= date('m')) {
                $error = 'Invalid expiry date';
            }
        }
        return $error;
    }
    //deals add page validation
    function _checkRedeemLocationCompany()
    {	
		
        if ($this->data['Deal']['user_type_id']!=ConstUserTypes::Company && empty($this->data[$this->name]['company_id'])) {
            return false;
        }
        return true;
		
    }
	function _isEmptyCompany()
	{
		if($this->data['Deal']['user_type_id']!=ConstUserTypes::Company)
		{
			if(empty($this->data['Deal']['company_id']))
			{
				return false;
			}
			else
			{
			return true;
			}
			
		}
		else
		{
		return true;
		}
	}
    function _checkRedeemLocation()
    {
		if($this->data['Deal']['user_type_id']!=ConstUserTypes::Company)
		{
        if (!empty($this->data[$this->name]['company_id'])) {
            if (empty($this->data[$this->name]['is_redeem_in_main_address']) && ((isset($this->data[$this->name]['is_redeem_at_all_branch_address']) && empty($this->data[$this->name]['is_redeem_at_all_branch_address']) && empty($this->data['CompanyAddressesDeal']['company_address_id'])) || (!isset($this->data[$this->name]['is_redeem_at_all_branch_address'])))) {
                return false;
            }
        } else {
            if (empty($this->data[$this->name]['is_redeem_in_main_address']) && ((isset($this->data[$this->name]['is_redeem_at_all_branch_address']) && empty($this->data[$this->name]['is_redeem_at_all_branch_address']) && empty($this->data['CompanyAddressesDeal']['company_address_id'])) || (!isset($this->data[$this->name]['is_redeem_at_all_branch_address'])))) {
                return false;
            }
        }
        return true;
		}
		else
		return true;
    }
    function _checkDiscountAmount()
    {
        if ($this->data[$this->name]['discount_amount'] > $this->data[$this->name]['original_price']) {
            return false;
        }
        return true;
    }
    //deals add page validation
    function _isValidExpiryDate()
    {
        if (strtotime($this->data[$this->name]['coupon_expiry_date']) > strtotime($this->data[$this->name]['end_date'])) {
            return true;
        }
        return false;
    }
    function _isValidExpiryStartDate()
    {
        if (strtotime($this->data[$this->name]['coupon_expiry_date']) > strtotime($this->data[$this->name]['coupon_start_date'])) {
            return true;
        }
        return false;
    }
    function _isValidDealEndStartDate()
    {
        if (!empty($this->data['Deal']['is_now_deal']) && empty($this->data[$this->name]['start_date'])) {
            return true;
        }
        if (strtotime($this->data[$this->name]['coupon_start_date']) > strtotime($this->data[$this->name]['start_date'])) {
            return true;
        }
        return false;
    }
    function _isValidLiveDealEndStartDate()
    {
        // Verify equal dates
        // Verify coupon exp time > coupon start date
        if (!empty($this->data['Deal']['is_now_deal'])) {
            if (date('Y-m-d', strtotime($this->data['Deal']['coupon_start_date'])) == date('Y-m-d', strtotime($this->data['Deal']['coupon_expiry_date']))) {
                if (date('Y-m-d', strtotime($this->data['Deal']['coupon_start_date'])) >= date('Y-m-d', strtotime($this->data['Deal']['start_date']))) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }
    //deals add page validation
    function _checkUserEachPurchaseMaxLimit()
    {
        if (!empty($this->data[$this->name]['maxmium_purchase_per_day'])) {
            if ($this->data[$this->name]['maxmium_purchase_per_day'] >= $this->data[$this->name]['user_each_purchase_max_limit']) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }
    function _isValidStartDate()
    {
        if (!empty($this->data[$this->name]['is_now_deal'])) {
            if (strtotime($this->data[$this->name]['start_date']) >= strtotime(date('Y-m-d H:i:s'))) {
                return true;
            }
        } else {
            if (strtotime($this->data[$this->name]['start_date']) > strtotime(date('Y-m-d H:i:s'))) {
                return true;
            }
        }
        return false;
    }
    //deals add page validation
    function _isValidEndDate()
    {
        if (strtotime($this->data[$this->name]['end_date']) > strtotime($this->data[$this->name]['start_date'])) {
            return true;
        }
        return false;
    }
    //check whether user can buy this quantity by checking already bought count and buy_max_quantity_per_user field
    //called from deals controller
    function isEligibleForBuy($deal_id, $user_id, $buy_max_quantity_per_user)
    {
        $deals_count = $this->DealUser->find('first', array(
            'conditions' => array(
                'DealUser.deal_id' => $deal_id,
                'DealUser.user_id' => $user_id,
            ) ,
            'fields' => array(
                'SUM(DealUser.quantity) as total_count'
            ) ,
            'group' => array(
                'DealUser.user_id'
            ) ,
            'recursive' => -1
        ));
        if (empty($buy_max_quantity_per_user) || $deals_count[0]['total_count'] < $buy_max_quantity_per_user) {
            return true;
        }
        return false;
    }
    //for quantity maximum quantity per user validation
    function _isEligibleMaximumQuantityNowDeal()
    {
        $deals_count = $this->DealUser->find('first', array(
            'conditions' => array(
                'DealUser.deal_id' => (!empty($this->data[$this->name]['sub_deal_id']) ? $this->data[$this->name]['sub_deal_id'] : $this->data[$this->name]['deal_id']) ,
                'DealUser.user_id' => $this->data[$this->name]['user_id'],
            ) ,
            'fields' => array(
                'SUM(DealUser.quantity) as total_count'
            ) ,
            'group' => array(
                'DealUser.user_id'
            ) ,
            'recursive' => -1
        ));
        $deal = $this->find('first', array(
            'conditions' => array(
                'Deal.id' => (!empty($this->data[$this->name]['sub_deal_id']) ? $this->data[$this->name]['sub_deal_id'] : $this->data[$this->name]['deal_id']) ,
            ) ,
            'fields' => array(
                'Deal.user_each_purchase_max_limit',
            ) ,
            'recursive' => -1
        ));
        $newTotal = (!empty($deals_count[0]['total_count']) ? $deals_count[0]['total_count'] : 0) +$this->data[$this->name]['quantity'];
        if (empty($deal['Deal']['user_each_purchase_max_limit']) || $newTotal <= $deal['Deal']['user_each_purchase_max_limit']) {
            return true;
        }
        return false;
    }
    function _isEligibleMaximumQuantity()
    {
        $deals_count = $this->_countUserBoughtDeals();
        $deal = $this->find('first', array(
            'conditions' => array(
                'Deal.id' => $this->data[$this->name]['deal_id'],
            ) ,
            'fields' => array(
                'Deal.buy_max_quantity_per_user',
            ) ,
            'recursive' => -1
        ));
        $newTotal = (!empty($deals_count[0]['total_count']) ? $deals_count[0]['total_count'] : 0) +$this->data[$this->name]['quantity'];
        if (empty($deal['Deal']['buy_max_quantity_per_user']) || $newTotal <= $deal['Deal']['buy_max_quantity_per_user']) {
            return true;
        }
        return false;
    }
    //for minimum quantity per user validation
    function _isEligibleMinimumQuantity()
    {
        $deal = $this->find('first', array(
            'conditions' => array(
                'Deal.id' => $this->data[$this->name]['deal_id'],
            ) ,
            'fields' => array(
                'Deal.buy_min_quantity_per_user',
                'Deal.deal_user_count',
                'Deal.max_limit'
            ) ,
            'recursive' => -1
        ));
        if ($deal['Deal']['buy_min_quantity_per_user'] > 1) {
            $deals_count = $this->_countUserBoughtDeals();
            $boughtTotal = (!empty($deals_count[0]['total_count']) ? $deals_count[0]['total_count'] : 0) +$this->data[$this->name]['quantity'];
            $min = $deal['Deal']['buy_min_quantity_per_user'];
            if (!empty($deal['Deal']['max_limit']) && $min >= $deal['Deal']['max_limit']-$deal['Deal']['deal_user_count']) {
                $min = $deal['Deal']['max_limit']-$deal['Deal']['deal_user_count'];
            }
            if ($boughtTotal >= $min) {
                return true;
            }
            return false;
        } else {
            return true;
        }
    }
    //count upto this user how much deals bought
    function _countUserBoughtDeals()
    {
        $deals_count = $this->DealUser->find('first', array(
            'conditions' => array(
                'DealUser.deal_id' => $this->data[$this->name]['deal_id'],
                'DealUser.user_id' => $this->data[$this->name]['user_id'],
            ) ,
            'fields' => array(
                'SUM(DealUser.quantity) as total_count'
            ) ,
            'group' => array(
                'DealUser.user_id'
            ) ,
            'recursive' => -1
        ));
        return $deals_count;
    }
    //check whether it's eligible quantity to buy
    function _isEligibleQuantityNowDeal()
    {
        $deal = $this->find('first', array(
            'conditions' => array(
                'Deal.id' => (!empty($this->data[$this->name]['sub_deal_id']) ? $this->data[$this->name]['sub_deal_id'] : $this->data[$this->name]['deal_id']) ,
            ) ,
            'fields' => array(
                'Deal.deal_user_count',
                'Deal.maxmium_purchase_per_day'
            ) ,
            'recursive' => -1
        ));
        $newTotal = $deal['Deal']['deal_user_count']+$this->data[$this->name]['quantity'];
        if ($deal['Deal']['maxmium_purchase_per_day'] <= 0 || $newTotal <= $deal['Deal']['maxmium_purchase_per_day']) {
            return true;
        }
        if (preg_match("/./", $deal['Deal']['maxmium_purchase_per_day'])) {
            return false;
        }
        return false;
    }
    function _isEligibleQuantity()
    {
        $deal = $this->find('first', array(
            'conditions' => array(
                'Deal.id' => (!empty($this->data[$this->name]['sub_deal_id']) ? $this->data[$this->name]['sub_deal_id'] : $this->data[$this->name]['deal_id']) ,
            ) ,
            'fields' => array(
                'Deal.deal_user_count',
                'Deal.max_limit'
            ) ,
            'recursive' => -1
        ));
        $newTotal = $deal['Deal']['deal_user_count']+$this->data[$this->name]['quantity'];
        if ($deal['Deal']['max_limit'] <= 0 || $newTotal <= $deal['Deal']['max_limit']) {
            return true;
        }
        if (preg_match("/./", $deal['Deal']['max_limit'])) {
            return false;
        }
        return false;
    }
    //validate deal buy_min_quantity_per_user limit with maximum limit
    function _compareDealAndBuyMinLimt()
    {
        if (empty($this->data[$this->name]['max_limit']) || $this->data[$this->name]['max_limit'] >= $this->data[$this->name]['buy_min_quantity_per_user']) {
            return true;
        }
        return false;
    }
    //validate deal buy_max_quantity_per_user limit with maximum limit
    function _compareDealAndBuyMaxLimt()
    {
        if (empty($this->data[$this->name]['max_limit']) || $this->data[$this->name]['max_limit'] >= $this->data[$this->name]['buy_max_quantity_per_user']) {
            return true;
        }
        return false;
    }
    //validate deal minimum limit with maximum limit
    function _checkMaxLimt()
    {
        if ($this->data[$this->name]['max_limit'] >= $this->data[$this->name]['min_limit']) {
            return true;
        }
        return false;
    }
    function _checkMaxWithSubDealLimt()
    {
        if (!empty($this->data['Deal']['is_subdeal_available'])) {
            $subDeals = $this->find('all', array(
                'conditions' => array(
                    'Deal.parent_id' => $this->data['Deal']['id']
                ) ,
                'fields' => array(
                    'Deal.id',
                    'Deal.max_limit',
                ) ,
                'recursive' => -1
            ));
            $Ttl_cnt = 0;
            foreach($subDeals as $subDeal) {
                if (!empty($subDeal['Deal']['max_limit'])) {
                    $Ttl_cnt+= $subDeal['Deal']['max_limit'];
                } else {
                    return true;
                }
            }
            if ($Ttl_cnt < $this->data['Deal']['min_limit']) {
                return false;
            }
        }
        return true;
    }
    //validate deal buy_max_quantity_per_user limit with buy_min_quantity_per_user limit
    function _checkMaxQuantityLimt()
    {
        if ($this->data[$this->name]['buy_max_quantity_per_user'] >= $this->data[$this->name]['buy_min_quantity_per_user']) {
            return true;
        }
        return false;
    }
    //Process Tipped and closing status of deal
    function processDealStatus($deal_id, $last_inserted_id)
    {
        $deal = $this->find('first', array(
            'conditions' => array(
                'Deal.deal_status_id' => ConstDealStatus::Tipped,
                'Deal.id' => $deal_id
            ) ,
            'fields' => array(
                'Deal.is_coupon_mail_sent',
                'Deal.max_limit',
                'Deal.deal_user_count',
                'Deal.id'
            ) ,
            'recursive' => -1,
        ));
        if (!empty($deal)) {
            // - X Referral Methiod //
            if (Configure::read('referral.referral_enable') && Configure::read('referral.referral_enabled_option') == ConstReferralOption::XRefer) {
                $this->referalRefunding($deal_id, $last_inserted_id);
            }
            //handle tipped status
            $this->processTippedStatus($deal, $last_inserted_id);
            //handle closed status if max user reached
            if (!empty($deal['Deal']['max_limit']) && $deal['Deal']['deal_user_count'] >= $deal['Deal']['max_limit']) {
                $this->_closeDeals(array(
                    $deal['Deal']['id']
                ));
            }
        }
    }
    //Process Tipped status of deal
    function processTippedStatus($deal_details, $last_inserted_id)
    {
        $dealUserConditions = array();
        $dealUserConditions['DealUser.is_canceled'] = 0;
        if ($deal_details['Deal']['is_coupon_mail_sent']) {
            $dealUserConditions['DealUser.id'] = $last_inserted_id;
        }
        $deal = $this->find('first', array(
            'conditions' => array(
                'Deal.deal_status_id' => ConstDealStatus::Tipped,
                'Deal.id' => $deal_details['Deal']['id']
            ) ,
            'contain' => array(
                'DealUser' => array(
                    'User' => array(
                        'fields' => array(
                            'User.username',
                            'User.id',
                            'User.email',
                            'User.cim_profile_id'
                        ) ,
                        'UserProfile' => array(
                            'fields' => array(
                                'UserProfile.first_name',
                                'UserProfile.last_name'
                            ) ,
                        ) ,
                    ) ,
                    'SubDeal' => array(
                        'fields' => array(
                            'SubDeal.id',
                            'SubDeal.name',
                            'SubDeal.slug',
                            'SubDeal.is_enable_payment_advance',
                            'SubDeal.payment_remaining'
                        )
                    ) ,
                    'DealUserCoupon',
                    'PaypalDocaptureLog' => array(
                        'fields' => array(
                            'PaypalDocaptureLog.currency_id',
                            'PaypalDocaptureLog.converted_currency_id',
                            'PaypalDocaptureLog.original_amount',
                            'PaypalDocaptureLog.rate',
                            'PaypalDocaptureLog.authorizationid',
                            'PaypalDocaptureLog.dodirectpayment_amt',
                            'PaypalDocaptureLog.id',
                            'PaypalDocaptureLog.currencycode'
                        )
                    ) ,
                    'AuthorizenetDocaptureLog' => array(
                        'fields' => array(
                            'AuthorizenetDocaptureLog.currency_id',
                            'AuthorizenetDocaptureLog.converted_currency_id',
                            'AuthorizenetDocaptureLog.orginal_amount',
                            'AuthorizenetDocaptureLog.rate',
                            'AuthorizenetDocaptureLog.authorize_amt'
                        )
                    ) ,
                    'PaypalTransactionLog' => array(
                        'fields' => array(
                            'PaypalTransactionLog.currency_id',
                            'PaypalTransactionLog.converted_currency_id',
                            'PaypalTransactionLog.orginal_amount',
                            'PaypalTransactionLog.rate',
                            'PaypalTransactionLog.authorization_auth_exp',
                            'PaypalTransactionLog.authorization_auth_id',
                            'PaypalTransactionLog.authorization_auth_amount',
                            'PaypalTransactionLog.authorization_auth_status',
                            'PaypalTransactionLog.mc_currency',
                            'PaypalTransactionLog.mc_gross',
                            'PaypalTransactionLog.id'
                        )
                    ) ,
                    'ExpresscheckoutTransactionLog' => array(
                        'fields' => array(
                            'ExpresscheckoutTransactionLog.currency_id',
                            'ExpresscheckoutTransactionLog.converted_currency_id',
                            'ExpresscheckoutTransactionLog.orginal_amount',
                            'ExpresscheckoutTransactionLog.rate',
                            'ExpresscheckoutTransactionLog.transaction_id',
                            'ExpresscheckoutTransactionLog.mc_currency',
                            'ExpresscheckoutTransactionLog.mc_gross',
                            'ExpresscheckoutTransactionLog.id'
                        )
                    ) ,
                    'conditions' => $dealUserConditions,
                ) ,
                'CompanyAddressesDeal',
                'Company' => array(
                    'City' => array(
                        'fields' => array(
                            'City.id',
                            'City.name',
                            'City.slug',
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.id',
                            'State.name'
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.id',
                            'Country.name',
                            'Country.slug',
                        )
                    ) ,
                    'CompanyAddress' => array(
                        'limit' => 5,
                        'City' => array(
                            'fields' => array(
                                'City.id',
                                'City.name',
                                'City.slug',
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.id',
                                'State.name'
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.id',
                                'Country.name',
                                'Country.slug',
                            )
                        )
                    ) ,
                ) ,
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.dir',
                        'Attachment.filename',
                        'Attachment.width',
                        'Attachment.height'
                    )
                ) ,
                'City' => array(
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.slug',
                    )
                ) ,
            ) ,
            'recursive' => 3,
        ));
        //do capture for credit card
        if (!empty($deal['DealUser'])) {
            App::import('Core', 'ComponentCollection');
            $collection = new ComponentCollection();
            App::import('Component', 'Paypal');
            $this->Paypal = new PaypalComponent($collection);
            $paymentGateways = $this->User->Transaction->PaymentGateway->find('all', array(
                'conditions' => array(
                    'PaymentGateway.id' => array(
                        ConstPaymentGateways::CreditCard,
                        ConstPaymentGateways::AuthorizeNet,
						ConstPaymentGateways::ExpressCheckout
                    ) ,
                ) ,
                'contain' => array(
                    'PaymentGatewaySetting' => array(
                        'fields' => array(
                            'PaymentGatewaySetting.key',
                            'PaymentGatewaySetting.test_mode_value',
                            'PaymentGatewaySetting.live_mode_value',
                        ) ,
                    ) ,
                ) ,
                'recursive' => 1
            ));
            foreach($paymentGateways as $paymentGateway) {
                if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::CreditCard) {
                    if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                        foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                            if ($paymentGatewaySetting['key'] == 'directpay_API_UserName') {
                                $paypal_sender_info['API_UserName'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                            if ($paymentGatewaySetting['key'] == 'directpay_API_Password') {
                                $paypal_sender_info['API_Password'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                            if ($paymentGatewaySetting['key'] == 'directpay_API_Signature') {
                                $paypal_sender_info['API_Signature'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                            $paypal_sender_info['is_testmode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
                        }
                    }
                }
                if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::AuthorizeNet) {
                    if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                        foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                            if ($paymentGatewaySetting['key'] == 'authorize_net_api_key') {
                                $authorize_sender_info['api_key'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                            if ($paymentGatewaySetting['key'] == 'authorize_net_trans_key') {
                                $authorize_sender_info['trans_key'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                        }
                    }
                    $authorize_sender_info['is_test_mode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
                }
                if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::ExpressCheckout) {
					$paypal_expresscheckout_sender_info = array();
					if (!empty($paymentGateway['PaymentGatewaySetting'])) {
						foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
							if ($paymentGatewaySetting['key'] == 'expressecheckout_API_UserName') {
								$paypal_expresscheckout_sender_info['API_UserName'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
							}
							if ($paymentGatewaySetting['key'] == 'expressecheckout_API_Password') {
								$paypal_expresscheckout_sender_info['API_Password'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
							}
							if ($paymentGatewaySetting['key'] == 'expressecheckout_API_Signature') {
								$paypal_expresscheckout_sender_info['API_Signature'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
							}
							$paypal_expresscheckout_sender_info['is_testmode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
						}
					}
				}				
				
            }
            $paidDealUsers = array();
            foreach($deal['DealUser'] as $dealUser) {
                // Now Deal Modifications //
                if (!$dealUser['is_paid'] && !empty($dealUser['is_capture_after_redeem'])) {
                    $paidDealUsers[] = $dealUser['id'];
                }
                if (!$dealUser['is_paid'] && $dealUser['payment_gateway_id'] != ConstPaymentGateways::Wallet && empty($dealUser['is_capture_after_redeem'])) {
                    $payment_response = array();
                    if ($dealUser['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet) {
                        $capture = 0;
                        require_once (APP . 'vendors' . DS . 'CIM' . DS . 'AuthnetCIM.class.php');
                        if ($authorize_sender_info['is_test_mode']) {
                            $cim = new AuthnetCIM($authorize_sender_info['api_key'], $authorize_sender_info['trans_key'], true);
                        } else {
                            $cim = new AuthnetCIM($authorize_sender_info['api_key'], $authorize_sender_info['trans_key']);
                        }
                        $dealUser['discount_amount'] = $this->_convertAuthorizeAmount($dealUser['discount_amount'], $dealUser['AuthorizenetDocaptureLog']['rate']);
                        $cim->setParameter('amount', $dealUser['discount_amount']);
                        $cim->setParameter('refId', time());
                        $cim->setParameter('customerProfileId', $dealUser['User']['cim_profile_id']);
                        $cim->setParameter('customerPaymentProfileId', $dealUser['payment_profile_id']);
                        $cim_transaction_type = 'profileTransAuthCapture';
                        if (!empty($dealUser['cim_approval_code'])) {
                            $cim->setParameter('approvalCode', $dealUser['cim_approval_code']);
                            $cim_transaction_type = 'profileTransCaptureOnly';
                        }
                        $title = Configure::read('site.name') . ' - Deal Bought';
                        $description = 'Deal Bought in ' . Configure::read('site.name');
                        // CIM accept only 30 character in title
                        if (strlen($title) > 30) {
                            $title = substr($title, 0, 27) . '...';
                        }
                        $unit_amount = $dealUser['discount_amount']/$dealUser['quantity'];
                        $cim->setLineItem($dealUser['deal_id'], $title, $description, $dealUser['quantity'], $unit_amount);
                        $cim->createCustomerProfileTransaction($cim_transaction_type);
                        $response = $cim->getDirectResponse();
                        $response_array = explode(',', $response);
                        if ($cim->isSuccessful() && $response_array[0] == 1) {
                            $capture = 1;
                        }
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['deal_user_id'] = $dealUser['id'];
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_response_text'] = $cim->getResponseText();
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_authorization_code'] = $cim->getAuthCode();
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_avscode'] = $cim->getAVSResponse();
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['transactionid'] = $cim->getTransactionID();
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_amt'] = $response_array[9];
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_gateway_feeamt'] = $response[32];
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_cvv2match'] = $cim->getCVVResponse();
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_response'] = $response;
                        if (!empty($capture)) {
                            $data_authorize_docapture_log['AuthorizenetDocaptureLog']['payment_status'] = 'Completed';
                        }
                        $this->DealUser->AuthorizenetDocaptureLog->save($data_authorize_docapture_log);
                    } else {
                        //doCapture process for credit card and paypal auth
                        if ($dealUser['payment_gateway_id'] == ConstPaymentGateways::CreditCard && !empty($dealUser['PaypalDocaptureLog']['authorizationid'])) {
                            $post_info['authorization_id'] = $dealUser['PaypalDocaptureLog']['authorizationid'];
                            $post_info['amount'] = $dealUser['PaypalDocaptureLog']['dodirectpayment_amt'];
                            $post_info['invoice_id'] = $dealUser['PaypalDocaptureLog']['id'];
                            $post_info['currency'] = $dealUser['PaypalDocaptureLog']['currencycode'];
                        } else if ($dealUser['payment_gateway_id'] == ConstPaymentGateways::PayPalAuth && !empty($dealUser['PaypalTransactionLog']['authorization_auth_id'])) {
                            $post_info['authorization_id'] = $dealUser['PaypalTransactionLog']['authorization_auth_id'];
                            $post_info['amount'] = $dealUser['PaypalTransactionLog']['authorization_auth_amount'];
                            $post_info['invoice_id'] = $dealUser['PaypalTransactionLog']['id'];
                            $post_info['currency'] = $dealUser['PaypalTransactionLog']['mc_currency'];
                        } else if ($dealUser['payment_gateway_id'] == ConstPaymentGateways::ExpressCheckout && !empty($dealUser['ExpresscheckoutTransactionLog']['transaction_id'])) {
							$post_info['authorization_id'] = $dealUser['ExpresscheckoutTransactionLog']['transaction_id'];
                            $post_info['amount'] = $dealUser['ExpresscheckoutTransactionLog']['mc_gross'];
                            $post_info['invoice_id'] = $dealUser['ExpresscheckoutTransactionLog']['id'];
                            $post_info['currency'] = $dealUser['ExpresscheckoutTransactionLog']['mc_currency'];
                        }
                        $post_info['CompleteCodeType'] = 'Complete';
                        $post_info['note'] = __l('Deal Payment');
                        //call doCapture from paypal component
						if ($dealUser['payment_gateway_id'] == ConstPaymentGateways::ExpressCheckout) {
                        	$payment_response = $this->Paypal->doCapture($post_info, $paypal_expresscheckout_sender_info);
						} else {
							$payment_response = $this->Paypal->doCapture($post_info, $paypal_sender_info);
						}
                    }
                    if ((!empty($payment_response) && $payment_response['ACK'] == 'Success') || !empty($capture)) {
                        //update PaypalDocaptureLog for credit card
                        if ($dealUser['payment_gateway_id'] == ConstPaymentGateways::CreditCard) {
                            $data_paypal_docapture_log['PaypalDocaptureLog']['id'] = $dealUser['PaypalDocaptureLog']['id'];
                            foreach($payment_response as $key => $value) {
                                if ($key != 'AUTHORIZATIONID' && $key != 'VERSION' && $key != 'CURRENCYCODE') {
                                    $data_paypal_docapture_log['PaypalDocaptureLog']['docapture_' . strtolower($key) ] = $value;
                                }
                            }
                            $data_paypal_docapture_log['PaypalDocaptureLog']['docapture_response'] = serialize($payment_response);
                            $data_paypal_docapture_log['PaypalDocaptureLog']['payment_status'] = 'Completed';
                            $this->DealUser->PaypalDocaptureLog->save($data_paypal_docapture_log);
                        } else if ($dealUser['payment_gateway_id'] == ConstPaymentGateways::PayPalAuth) {
                            //update PaypalTransactionLog for PayPalAuth
                            $data_paypal_capture_log['PaypalTransactionLog']['id'] = $dealUser['PaypalTransactionLog']['id'];
                            $data_paypal_capture_log['PaypalTransactionLog']['error_no'] = '0';
                            $data_paypal_capture_log['PaypalTransactionLog']['payment_status'] = 'Completed';
                            foreach($payment_response as $key => $value) {
                                $data_paypal_capture_log['PaypalTransactionLog']['capture_' . strtolower($key) ] = $value;
                            }
                            $data_paypal_capture_log['PaypalTransactionLog']['capture_data'] = serialize($payment_response);
                            $this->DealUser->PaypalTransactionLog->save($data_paypal_capture_log);
                        } else if ($dealUser['payment_gateway_id'] == ConstPaymentGateways::ExpressCheckout) {
                            //update ExpresscheckoutTransactionLog for Express Checkout
							$data_paypal_expresscheckout_capture_log = array();
                            $data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['id'] = $dealUser['ExpresscheckoutTransactionLog']['id'];
                            $data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['error_no'] = '0';
                            $data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_amt'] = $payment_response['AMT'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_feeamt'] = $payment_response['FEEAMT'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_paymentstatus'] = $payment_response['PAYMENTSTATUS'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_pendingreason'] = $payment_response['PENDINGREASON'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_transactionid'] = $payment_response['TRANSACTIONID'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_parenttransactionid'] = $payment_response['PARENTTRANSACTIONID'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_taxamt'] = $payment_response['TAXAMT'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_ordertime'] = $payment_response['ORDERTIME'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_paymenttype'] = $payment_response['PAYMENTTYPE'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_transactiontype'] = $payment_response['TRANSACTIONTYPE'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_ack'] = $payment_response['ACK'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_timestamp'] = $payment_response['TIMESTAMP'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_correlationid'] = $payment_response['CORRELATIONID'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_build'] = $payment_response['BUILD'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_reasoncode'] = $payment_response['REASONCODE'];
							$data_paypal_expresscheckout_capture_log['ExpresscheckoutTransactionLog']['docapture_protectioneligibility'] = $payment_response['PROTECTIONELIGIBILITY'];
                            $this->DealUser->ExpresscheckoutTransactionLog->save($data_paypal_expresscheckout_capture_log);
                        }
                        // need to updatee deal user record is_paid as 1
                        $paidDealUsers[] = $dealUser['id'];
                        //add amount to wallet
                        // coz of 'act like groupon' logic, amount updated from what actual taken, instead of updating deal amount directly.
                        if (!empty($dealUser['PaypalTransactionLog']['orginal_amount'])) {
                            $paid_amount = $dealUser['PaypalTransactionLog']['orginal_amount'];
                        } elseif (!empty($dealUser['PaypalDocaptureLog']['original_amount'])) {
                            $paid_amount = $dealUser['PaypalDocaptureLog']['original_amount'];
                        } elseif (!empty($dealUser['ExpresscheckoutTransactionLog']['orginal_amount'])) {
                            $paid_amount = $dealUser['ExpresscheckoutTransactionLog']['orginal_amount'];
                        }
                        $data['Transaction']['user_id'] = $dealUser['user_id'];
                        $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
                        $data['Transaction']['class'] = 'SecondUser';
                        //$data['Transaction']['amount'] = $dealUser['discount_amount'];
                        $data['Transaction']['amount'] = $paid_amount;
                        $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
                        $data['Transaction']['payment_gateway_id'] = $dealUser['payment_gateway_id'];
                        $transaction_id = $this->User->Transaction->log($data);
                        if (!empty($transaction_id)) {
                            $this->User->updateAll(array(
                                'User.available_balance_amount' => 'User.available_balance_amount +' . $paid_amount
                            ) , array(
                                'User.id' => $dealUser['user_id']
                            ));
                        }
                        //Buy deal transaction
                        $transaction['Transaction']['user_id'] = $dealUser['user_id'];
						$transaction['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
                        $transaction['Transaction']['foreign_id'] = $dealUser['id'];
                        $transaction['Transaction']['class'] = 'DealUser';
                        $transaction['Transaction']['amount'] = $paid_amount;
                        $transaction['Transaction']['payment_gateway_id'] = $dealUser['payment_gateway_id'];
                        if (!empty($dealUser['PaypalTransactionLog']['rate'])) {
                            $transaction['Transaction']['currency_id'] = $dealUser['PaypalTransactionLog']['currency_id'];
                            $transaction['Transaction']['converted_currency_id'] = $dealUser['PaypalTransactionLog']['converted_currency_id'];
                            $transaction['Transaction']['converted_amount'] = $dealUser['PaypalTransactionLog']['mc_gross'];
                            $transaction['Transaction']['rate'] = $dealUser['PaypalTransactionLog']['rate'];
                        }
                        if (!empty($dealUser['PaypalDocaptureLog']['rate'])) {
                            $transaction['Transaction']['currency_id'] = $dealUser['PaypalDocaptureLog']['currency_id'];
                            $transaction['Transaction']['converted_currency_id'] = $dealUser['PaypalDocaptureLog']['converted_currency_id'];
                            $transaction['Transaction']['converted_amount'] = $dealUser['PaypalDocaptureLog']['dodirectpayment_amt'];
                            $transaction['Transaction']['rate'] = $dealUser['PaypalDocaptureLog']['rate'];
                        }
                        if (!empty($dealUser['AuthorizenetDocaptureLog']['rate'])) {
                            $transaction['Transaction']['currency_id'] = $dealUser['AuthorizenetDocaptureLog']['currency_id'];
                            $transaction['Transaction']['converted_currency_id'] = $dealUser['AuthorizenetDocaptureLog']['converted_currency_id'];
                            $transaction['Transaction']['converted_amount'] = $dealUser['AuthorizenetDocaptureLog']['authorize_amt'];
                            $transaction['Transaction']['rate'] = $dealUser['AuthorizenetDocaptureLog']['rate'];
                        }
						if (!empty($dealUser['ExpresscheckoutTransactionLog']['rate'])) {
                            $transaction['Transaction']['currency_id'] = $dealUser['ExpresscheckoutTransactionLog']['currency_id'];
                            $transaction['Transaction']['converted_currency_id'] = $dealUser['ExpresscheckoutTransactionLog']['converted_currency_id'];
                            $transaction['Transaction']['converted_amount'] = $dealUser['ExpresscheckoutTransactionLog']['mc_gross'];
                            $transaction['Transaction']['rate'] = $dealUser['ExpresscheckoutTransactionLog']['rate'];
                        }
                        $transaction['Transaction']['transaction_type_id'] = (!empty($dealUser['is_gift'])) ? ConstTransactionTypes::DealGift : ConstTransactionTypes::BuyDeal;
                        $this->User->Transaction->log($transaction);
                        //user update
                        $this->User->updateAll(array(
                            'User.available_balance_amount' => 'User.available_balance_amount -' . $paid_amount
                        ) , array(
                            'User.id' => $dealUser['user_id']
                        ));
                    } else {
                        //ack from paypal is not succes, so increasing payment_failed_count in deals table
                        $this->updateAll(array(
                            'Deal.payment_failed_count' => 'Deal.payment_failed_count +' . $dealUser['quantity'],
                        ) , array(
                            'Deal.id' => $dealUser['deal_id']
                        ));
                    }
                }
            }

            if (!empty($paidDealUsers)) {
                //update is_paid field
                $this->DealUser->updateAll(array(
                    'DealUser.is_paid' => 1
                ) , array(
                    'DealUser.id' => $paidDealUsers
                ));
                // paid user "is_paid" field update on $deal array, bcoz this array pass the _sendCouponMail.
                if (!empty($deal['DealUser'])) {
                    foreach($deal['DealUser'] as &$deal_user) {
                        foreach($paidDealUsers as $paid) {
                            if ($deal_user['id'] == $paid) {
                                $deal_user['is_paid'] = 1;
                            }
                        }
                    }
                }
            }
        }
        //do capture for credit card end
        //send coupon mail to users when deal tipped
        $this->_sendCouponMail($deal);
        if (!$deal_details['Deal']['is_coupon_mail_sent']) {
            //update in deals table as coupon_mail_sent
            $this->updateAll(array(
                'Deal.is_coupon_mail_sent' => 1
            ) , array(
                'Deal.id' => $deal['Deal']['id']
            ));
        }
    }
    //send buyers list to company user
    function _sendBuyersListCompany($dealIds)
    {
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $this->Email = new EmailComponent($collection);
        $deals = $this->find('all', array(
            'conditions' => array(
                'Deal.id' => $dealIds
            ) ,
            'contain' => array(
                'DealUser' => array(
                    'User' => array(
                        'fields' => array(
                            'User.username',
                            'User.email',
                        ) ,
                        'order' => array(
                            'User.username' => 'asc'
                        )
                    ) ,
                    'DealUserCoupon',
                    'SubDeal' => array(
                        'fields' => array(
                            'SubDeal.id',
                            'SubDeal.name',
                            'SubDeal.slug',
                            'SubDeal.is_enable_payment_advance',
                            'SubDeal.payment_remaining'
                        )
                    ) ,
                ) ,
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.email',
                    ) ,
                    'order' => array(
                        'User.username' => 'asc'
                    ) ,
                ) ,
                'Company' => array(
                    'City' => array(
                        'fields' => array(
                            'City.id',
                            'City.name',
                            'City.slug',
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.id',
                            'State.name'
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.id',
                            'Country.name',
                            'Country.slug',
                        )
                    )
                ) ,
                'City' => array(
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.slug',
                    )
                ) ,
                'SubDeal' => array(
                    'fields' => array(
                        'SubDeal.id',
                        'SubDeal.name',
                        'SubDeal.slug',
                        'SubDeal.is_enable_payment_advance',
                        'SubDeal.payment_remaining'
                    )
                ) ,
            ) ,
            'recursive' => 2,
        ));
        if (!empty($deals)) {
            foreach($deals as $deal) {
                if (!empty($deal['Company']['is_online_account'])) {
                    $dealUsers = $deal['DealUser'];
                    if (!empty($dealUsers)) {
                        //form users list array
                        $userslist = '';
                        $userslist.= '<table width="100%" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC" border="0"  style="color:#666; font-size:11px;">';
                        $userslist.= '<tr><th align="left" bgcolor="#BEE27B" style="color:#ffffff; font-size:14px;" rowspan="2">' . __l('Username') . '</th><th align="center" bgcolor="#BEE27B" style="color:#ffffff; font-size:14px;" colspan="2">' . __l('Coupon code') . '</th><th bgcolor="#BEE27B" style="color:#ffffff; font-size:14px;" rowspan="2">' . __l('Quantity') . '</th><th bgcolor="#BEE27B" style="color:#ffffff; font-size:14px;" rowspan="2">' . __l('Payment pending') . '</th></tr><tr><th bgcolor="#BEE27B" style="color:#ffffff; font-size:14px;">' . __l('Top code') . '</th><th bgcolor="#BEE27B" style="color:#ffffff; font-size:14px;">' . __l('Bottom code') . '</th></tr>';
                        foreach($dealUsers as $dealUser) {
                            $pending_amount = "";
                            if (!empty($dealUser['SubDeal']['is_enable_payment_advance'])) {
                                $pending_amount = Configure::read('site.currency') . ($dealUser['quantity']*$dealUser['SubDeal']['payment_remaining']);
                            } elseif (!empty($dealUser['Deal']['is_enable_payment_advance'])) {
                                $pending_amount = Configure::read('site.currency') .  ($dealUser['quantity']*$dealUser['Deal']['payment_remaining']);
                           } else {
                                $pending_amount = "-";
                            }
                            if (!empty($dealUser) && (empty($dealUser['is_canceled']) || $deal['Deal']['is_now_deal']==1)) {
								
                                $deal_user_coupon_codes = array();
                                $deal_user_coupon_codes = '<ul>';
                                $deal_user_unqiue_codes = '<ul>';
                                foreach($dealUser['DealUserCoupon'] as $deal_user_coupon) {
                                    $deal_user_coupon_codes.= '<li>' . $deal_user_coupon['coupon_code'] . '</li>';
                                    $deal_user_unqiue_codes.= '<li>' . $deal_user_coupon['unique_coupon_code'] . '</li>';
                                }
                                $deal_user_coupon_codes.= '</ul>';
                                $deal_user_unqiue_codes.= '</ul>';
                                $userslist.= '<tr><td bgcolor="#FFFFFF" align="left">' . $dealUser['User']['username'] . (!empty($dealUser['SubDeal']['name']) ? ' ( ' . $dealUser['SubDeal']['name'] . ' ) ' : '') . '</td><td bgcolor="#FFFFFF" align="left">' . $deal_user_coupon_codes . '</td><td bgcolor="#FFFFFF" align="center">' . $deal_user_unqiue_codes . '</td><td bgcolor="#FFFFFF" align="center">' . $dealUser['quantity'] . '</td><td bgcolor="#FFFFFF" align="center">' . $pending_amount . '</td></tr>';
                            }
                        }
                        $userslist.= '</table>';
                    }
                    $companyUser = $this->Company->User->find('first', array(
                        'conditions' => array(
                            'User.id' => $deal['Company']['user_id']
                        ) ,
                        'fields' => array(
                            'User.username',
                            'User.id',
                            'User.email',
                        ) ,
                        'contain' => array(
                            'UserProfile.first_name',
                            'UserProfile.last_name'
                        ) ,
                        'recursive' => 2,
                    ));
                    // $address = $deal['Company']['address1'] . '<br/>' . $deal['Company']['address2'];
                    $address = $deal['Company']['address1'];
                    if (!empty($deal['Company']['City']['name'])) {
                        $address.= '<br/>' . $deal['Company']['City']['name'];
                    }
                    if (!empty($deal['Company']['State']['name'])) {
                        $address.= '<br/>' . $deal['Company']['State']['name'];
                    }
                    if (!empty($deal['Company']['Country']['name'])) {
                        $address.= '<br/>' . $deal['Company']['Country']['name'];
                    }
                    if (!empty($deal['Company']['zip'])) {
                        $address.= '<br/>' . $deal['Company']['zip'];
                    }
                    //send mail to company user
                    $template = $this->EmailTemplate->selectTemplate('Deal Coupon Buyers List');
                    $emailFindReplace = array(
                        '##SITE_LINK##' => Cache::read('site_url_for_shell', 'long') ,
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##DEAL_NAME##' => $deal['Deal']['name'],
                        '##DEAL_LINK##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'deals',
                            'action' => 'view',
                            $deal['Deal']['slug'],
                            'admin' => false
                        ) , false) , 1) ,
                        '##USERNAME##' => $companyUser['User']['username'],
                        '##COUPON_EXPIRY_DATE##' => !empty($deal['Deal']['coupon_expiry_date']) ? strftime(Configure::read('site.datetime.format') , strtotime(_formatDate('Y-m-d H:i:s', strtotime($deal['Deal']['coupon_expiry_date'])))) : 'Unlimited',
                        '##COUPON_CONDITION##' => !empty($deal['Deal']['coupon_condition']) ? $deal['Deal']['coupon_condition'] : 'N/A',
                        '##REDEMPTION_PLACE##' => $address,
                        '##DEAL_URL##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'deals',
                            'action' => 'view',
                            'city' => $deal['City']['0']['slug'],
                            $deal['Deal']['slug'],
                            'admin' => false
                        ) , false) , 1) ,
                        '##CONTACT_URL##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'contacts',
                            'action' => 'add',
                            'city' => $deal['City']['0']['slug'],
                            'admin' => false
                        ) , false) , 1) ,
                        '##FROM_EMAIL##' => $this->changeFromEmail(($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from']) ,
                        '##TABLE##' => $userslist,
                        '##SITE_LOGO##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'img',
                            'action' => 'blue-theme',
                            'logo-email.png',
                            'admin' => false
                        ) , false) , 1) ,
                    );
                    if (!empty($deal['DealUser'])) {
                        $this->Email->from = ($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from'];
                        $this->Email->replyTo = ($template['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $template['reply_to'];
                        $this->Email->to = $this->formatToAddress($companyUser);
                        $this->Email->subject = strtr($template['subject'], $emailFindReplace);
                        $this->Email->content = strtr($template['email_content'], $emailFindReplace);
                        $this->Email->sendAs = ($template['is_html']) ? 'html' : 'text';
                        $this->Email->send($this->Email->content);
                    }
                }
            }
        }
    }
    //send coupon mail to users once deal tipped
    function _sendCouponMail($deal)
    {
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $this->Email = new EmailComponent($collection);
        /* sending coupon to all deal users and vendor starts here */
        $is_mail_sent = '';
        $emailFindReplace = array();
        if (!empty($deal['DealUser'])) { 
            foreach($deal['DealUser'] as $deal_user) {
                if ($deal_user['is_paid']) {
                    if (empty($deal_user['is_canceled'])) {
                        $barcode = Router::url(array(
                            'controller' => 'deals',
                            'action' => 'barcode',
                            $deal_user['id'],
                            'admin' => false
                        ) , true);
                        //for normal bought deals send Deal Coupon mail
                        $i = 1;
                        $content = '';
                        if ($deal['Deal']['is_redeem_in_main_address'] == 1) {
                            $name = !empty($deal['Company']['name']) ? $deal['Company']['name'] . '<br />' : '';
                            $address1 = !empty($deal['Company']['address1']) ? $deal['Company']['address1'] . '<br />' : '';
                            //$address2  = !empty($deal['Company']['address2']) ? $deal['Company']['address2'].'<br />' : '';
                            $city = !empty($deal['Company']['City']['name']) ? $deal['Company']['City']['name'] . '<br />' : '';
                            $state = !empty($deal['Company']['State']['name']) ? $deal['Company']['State']['name'] . '<br />' : '';
                            $country = !empty($deal['Company']['Country']['name']) ? $deal['Company']['Country']['name'] . '<br />' : '';
                            $content = '<dt style="width:120px; float:left; margin:0px;font-size:16px; padding:0px; text-align:right; font-weight:bold; color:#000;">Redeem At #' . $i . ':</dt><dd style="width:130px;float:left;margin:0px;padding:0px 0px 0px 10px;font-size:13px;color:#000;">' . $name . $address1 . $city . $state . $country . '</dd>';
                            $i++;
                        } else {
                            $temp_companyaddress = $deal['Company'];
                            unset($deal['Company']);
                            $deal['Company']['CompanyAddress'] = $temp_companyaddress['CompanyAddress'];
                        }
                        $branch_address = array();
                        foreach($deal['CompanyAddressesDeal'] as $company_address_deal) {
                            $branch_address[$company_address_deal['company_address_id']] = $company_address_deal['company_address_id'];
                        }
                        foreach($deal['Company']['CompanyAddress'] as $key => &$address) {
                            if ((in_array($address['id'], $branch_address) && empty($deal['Deal']['is_redeem_at_all_branch_address'])) || !empty($deal['Deal']['is_redeem_at_all_branch_address'])) {
                                $name = !empty($deal['Company']['name']) ? $deal['Company']['name'] . '<br />' : '';
                                $name = (empty($content)) ? $name : '';
                                $address1 = !empty($address['address1']) ? $address['address1'] . '<br />' : '';
                                //$address2  = !empty($address['address2']) ? $address['address2'].'<br />' : '';
                                $city = !empty($address['City']['name']) ? $address['City']['name'] . '<br />' : '';
                                $state = !empty($address['State']['name']) ? $address['State']['name'] . '<br />' : '';
                                $country = !empty($address['Country']['name']) ? $address['Country']['name'] . '<br />' : '';
                                $redeem = (empty($content)) ? __l('Redeem At') : '';
                                $content = $content . '<dt style="width:120px; float:left; margin:0px;font-size:16px; padding:0px; text-align:right; font-weight:bold; color:#000;">' . $redeem . '#' . $i . ':</dt><dd style="width:130px;float:left;margin:0px;padding:0px 0px 0px 10px;font-size:13px;color:#000;">' . $name . $address1 . $city . $state . $country . '</dd>';
                                $i++;
                            } else {
                                unset($deal['Company']['CompanyAddress'][$key]);
                            }
                        }
                        if (!$deal_user['is_gift']) {
                            $is_mail_sent = $deal['Deal']['is_coupon_mail_sent'];
                            $language_code = $this->getUserLanguageIso($deal_user['User']['id']);
                            $template = $this->EmailTemplate->selectTemplate('Deal Coupon', $language_code);
                            $emailFindReplace = array(
                                '##SITE_LINK##' => Cache::read('site_url_for_shell', 'long') ,
                                '##DEAL_NAME##' => $deal['Deal']['name'] . (($deal['Deal']['is_now_deal'] != 1) ? (!empty($deal_user['SubDeal']['name']) ? ' ( ' . $deal_user['SubDeal']['name'] . ' ) ' : '') : ''),
                                '##SITE_NAME##' => Configure::read('site.name') ,
                                '##QUANTITY##' => $deal_user['quantity'],
                                '##USER_NAME##' => $deal_user['User']['username'],
                                '##COMPANY_ADDRESS##' => $content,
                                '##COUPON_START_DATE##' => strftime(Configure::read('site.datetime.format') , strtotime(_formatDate('Y-m-d H:i:s', strtotime($deal['Deal']['coupon_start_date'])))) ,
                                '##COUPON_EXPIRY_DATE##' => !empty($deal['Deal']['coupon_expiry_date']) ? strftime(Configure::read('site.datetime.format') , strtotime(_formatDate('Y-m-d H:i:s', strtotime($deal['Deal']['coupon_expiry_date'])))) : 'Unlimited',
                                '##COUPON_PURCHASED_DATE##' => strftime(Configure::read('site.datetime.format') , strtotime(_formatDate('Y-m-d H:i:s', strtotime($deal_user['created'])))) ,
                                '##COUPON_CONDITION##' => $deal['Deal']['coupon_condition'],
                                '##FROM_EMAIL##' => $this->changeFromEmail(($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from']) ,
                                '##SITE_LOGO##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                                    'controller' => 'img',
                                    'action' => 'blue-theme',
                                    'logo-black.png',
                                    'admin' => false
                                ) , false) , 1) ,
                                '##CONTACT_URL##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', 'contactus', 1) ,
                                '##CONTACT_LINK##' => "<a href='mailto:" . Configure::read('site.contact_email') . "'>" . Configure::read('site.contact_email') . "</a>",
                                '##GOOGLE_MAP##' => $this->formGooglemap($deal['Company'], '340x250')
                            ); //is_enable_payment_advance, payment_remaining
                            if (!empty($deal_user['SubDeal']['is_enable_payment_advance'])) {
                                $emailFindReplace['##PENDING_AMOUNT##'] = "<strong style=\"color:#000; font-size:16px;display:block;margin:6px 0px 0px 0px; padding:0px 0px 0px 0px;display:block;\">Pending Amount:</strong>
									<p style=\"margin:0px;padding:0px;font-size:13px;color:#000;\">" . Configure::read('site.currency') . ($deal_user['SubDeal']['payment_remaining']) . "</p>";
                            } elseif (!empty($deal['Deal']['is_enable_payment_advance'])) {
                                $emailFindReplace['##PENDING_AMOUNT##'] = "<strong style=\"color:#000; font-size:16px;display:block;margin:6px 0px 0px 0px; padding:0px 0px 0px 0px;display:block;\">Pending Amount:</strong>
									<p style=\"margin:0px;padding:0px;font-size:13px;color:#000;\">" . Configure::read('site.currency') . ($deal['Deal']['payment_remaining']) . "</p>";
                            } else {
                                $emailFindReplace['##PENDING_AMOUNT##'] = "";
                            }
                            if (empty($emailFindReplace['##COUPON_CONDITION##']) && empty($emailFindReplace['##PENDING_AMOUNT##'])) {
                                $fineprint = "";
                            } else {
                                $fineprint = "Fine Print";
                            }
                            $emailFindReplace['##FINEPRINT##'] = $fineprint;
                            // Multiple coupon - sending mail for each coupon //
                            if (!empty($deal_user['DealUserCoupon'])) {
                                foreach($deal_user['DealUserCoupon'] as $deal_user_coupon) {
                                    $emailFindReplace['##COUPON_CODE##'] = '#' . $deal_user_coupon['coupon_code'];
                                    $parsed_url = parse_url(Router::url('/', true));
                                    $qr_code = str_ireplace($parsed_url['host'], 'm.' . $parsed_url['host'], Router::url(array(
                                        'controller' => 'deal_user_coupons',
                                        'action' => 'check_qr',
                                        $deal_user_coupon['coupon_code'],
                                        $deal_user_coupon['unique_coupon_code'],
                                        'admin' => false
                                    ) , true));
                                    $display_barcode = '';
                                    if (Configure::read('barcode.is_barcode_enabled') == 1) {
                                        $barcode_width = Configure::read('barcode.width');
                                        $barcode_height = Configure::read('barcode.height');
                                        if (Configure::read('barcode.symbology') == 'qr') {
                                            $display_barcode = '<img src="http://chart.apis.google.com/chart?cht=qr&chs=' . $barcode_width . 'x' . $barcode_height . '&chl=' . $qr_code . '" alt="[Image: Deal qr code]" />';
                                        }
                                        if (Configure::read('barcode.symbology') == 'c39') {
                                            $display_barcode = '<img src="' . $barcode . '" alt="[Image: barcode]" />';
                                        }
                                        $display_barcode.= '<br><b>' . $deal_user_coupon['unique_coupon_code'] . '</b>';
                                    }
                                    $emailFindReplace['##BARCODE##'] = $display_barcode;
                                    $this->Email->from = ($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from'];
                                    $this->Email->replyTo = ($template['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $template['reply_to'];
                                    $this->Email->to = $this->formatToAddress($deal_user);
                                    $this->Email->subject = strtr($template['subject'], $emailFindReplace);
                                    $this->Email->content = strtr($template['email_content'], $emailFindReplace);
                                    $this->Email->sendAs = ($template['is_html']) ? 'html' : 'text';
                                    $this->Email->send($this->Email->content);
                                }
                            }
                        } else { 
                            //for gifted deals
                            $is_mail_sent = $deal['Deal']['is_coupon_mail_sent'];
                            $language_code = $this->getUserLanguageIso($deal_user['User']['id']);
                            $template = $this->EmailTemplate->selectTemplate('Deal Coupon Gift', $language_code);
                            $emailFindReplace = array(
                                '##SITE_LINK##' => Cache::read('site_url_for_shell', 'long') ,
                                '##MESSAGE##' => $deal_user['message'],
                                '##DEAL_NAME##' => $deal['Deal']['name'] . (($deal['Deal']['is_now_deal'] != 1) ? (!empty($deal_user['SubDeal']['name']) ? ' ( ' . $deal_user['SubDeal']['name'] . ' ) ' : '') : '') ,
                                '##SITE_NAME##' => Configure::read('site.name') ,
                                '##DATE_PURCHASE##' => $deal_user['created'],
                                '##QUANTITY##' => $deal_user['quantity'],
                                '##USER_NAME##' => $deal_user['User']['username'],
                                '##FROM_EMAIL##' => $this->changeFromEmail(($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from']) ,
                                '##RECIPIENT_USER_NAME##' => $deal_user['gift_to'],
                                '##COMPANY_ADDRESS##' => $content,
                                '##VALID_FROM_DATE##' => strftime(Configure::read('site.datetime.format') , strtotime(_formatDate('Y-m-d H:i:s', strtotime($deal['Deal']['coupon_start_date'])))) ,
                                '##COUPON_EXPIRY_DATE##' => !empty($deal['Deal']['coupon_expiry_date']) ? strftime(Configure::read('site.datetime.format') , strtotime(_formatDate('Y-m-d H:i:s', strtotime($deal['Deal']['coupon_expiry_date'])))) : 'Unlimited',
                                '##COUPON_PURCHASED_DATE##' => strftime(Configure::read('site.datetime.format') , strtotime(_formatDate('Y-m-d H:i:s', strtotime($deal_user['created'])))) ,
                                '##COUPON_CONDITION##' => $deal['Deal']['coupon_condition'],
                                '##SITE_LOGO##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                                    'controller' => 'img',
                                    'action' => 'blue-theme',
                                    'logo-black.png',
                                    'admin' => false
                                ) , false) , 1) ,
                                '##CONTACT_URL##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', 'contactus', 1) ,
                                '##GIFT_IMAGE##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                                    'controller' => 'img',
                                    'action' => 'gift.png',
                                    'admin' => false
                                ) , false) , 1) ,
                                '##CONTACT_LINK##' => "<a href='mailto:" . Configure::read('site.contact_email') . "'>" . Configure::read('site.contact_email') . "</a>",
                                '##GOOGLE_MAP##' => $this->formGooglemap($deal['Company'], '340x250')
                            );
                            if (!empty($deal_user['SubDeal']['is_enable_payment_advance'])) {
                                $emailFindReplace['##PENDING_AMOUNT##'] = "<strong style=\"color:#000; font-size:16px;display:block;margin:6px 0px 0px 0px; padding:0px 0px 0px 0px;display:block;\">Pending Amount:</strong>
									<p style=\"margin:0px;padding:0px;font-size:13px;color:#000;\">" . Configure::read('site.currency') . $deal_user['SubDeal']['payment_remaining'] . "</p>";
                            } elseif (!empty($deal['Deal']['is_enable_payment_advance'])) {
                                $emailFindReplace['##PENDING_AMOUNT##'] = "<strong style=\"color:#000; font-size:16px;display:block;margin:6px 0px 0px 0px; padding:0px 0px 0px 0px;display:block;\">Pending Amount:</strong>
									<p style=\"margin:0px;padding:0px;font-size:13px;color:#000;\">" . Configure::read('site.currency') . $deal['Deal']['payment_remaining'] . "</p>";
                            } else {
                                $emailFindReplace['##PENDING_AMOUNT##'] = "";
                            }
                            if (empty($emailFindReplace['##COUPON_CONDITION##']) && empty($emailFindReplace['##PENDING_AMOUNT##'])) {
                                $fineprint = "";
                            } else {
                                $fineprint = "Fine Print";
                            }
                            $emailFindReplace['##FINEPRINT##'] = $fineprint;
                            // Multiple coupon - sending mail for each coupon //
                            if (!empty($deal_user['DealUserCoupon'])) {
                                foreach($deal_user['DealUserCoupon'] as $deal_user_coupon) {
                                    $emailFindReplace['##COUPON_CODE##'] = '#' . $deal_user_coupon['coupon_code'];
                                    $parsed_url = parse_url(Router::url('/', true));
                                    $qr_code = str_ireplace($parsed_url['host'], 'm.' . $parsed_url['host'], Router::url(array(
                                        'controller' => 'deal_user_coupons',
                                        'action' => 'check_qr',
                                        $deal_user_coupon['coupon_code'],
                                        $deal_user_coupon['unique_coupon_code'],
                                        'admin' => false
                                    ) , true));
                                    $display_barcode = '';
                                    if (Configure::read('barcode.is_barcode_enabled') == 1) {
                                        $barcode_width = Configure::read('barcode.width');
                                        $barcode_height = Configure::read('barcode.height');
                                        if (Configure::read('barcode.symbology') == 'qr') {
                                            $display_barcode = '<img src="http://chart.apis.google.com/chart?cht=qr&chs=' . $barcode_width . 'x' . $barcode_height . '&chl=' . $qr_code . '" alt="[Image: Deal qr code]" />';
                                        }
                                        if (Configure::read('barcode.symbology') == 'c39') {
                                            $display_barcode = '<img src="' . $barcode . '" alt="[Image: barcode]" />';
                                        }
                                        $display_barcode.= '<br><b>' . $deal_user_coupon['unique_coupon_code'] . '</b>';
                                    }
                                    $emailFindReplace['##BARCODE##'] = $display_barcode;
                                    $this->Email->from = ($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from'];
                                    $this->Email->replyTo = ($template['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $template['reply_to'];
                                    $this->Email->to = $deal_user['gift_email'];
                                    $this->Email->subject = strtr($template['subject'], $emailFindReplace);
                                    $this->Email->content = strtr($template['email_content'], $emailFindReplace);
                                    $this->Email->sendAs = ($template['is_html']) ? 'html' : 'text';
                                    $this->Email->send($this->Email->content);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    //refund deal amount to users if deal expired or canceled
    function _refundDealAmount($type = '', $dealIds = array())
    {
        $dealUserConditions = array(
            'OR' => array(
                array(
                    'DealUser.is_paid' => 1,
                    'DealUser.is_repaid' => 0,
                    'DealUser.is_canceled' => 0,
                    'DealUser.payment_gateway_id' => ConstPaymentGateways::Wallet
                ) ,
                array(
                    'DealUser.is_paid' => 0,
                    'DealUser.is_repaid' => 0,
                    'DealUser.is_canceled' => 0,
                    'DealUser.payment_gateway_id' => array(
                        ConstPaymentGateways::CreditCard,
                        ConstPaymentGateways::PayPalAuth,
                        ConstPaymentGateways::AuthorizeNet,
						ConstPaymentGateways::ExpressCheckout,
                    )
                )
            )
        );
        if (!empty($dealIds)) {
            $conditions['Deal.id'] = $dealIds;
        } elseif (!empty($type) && $type == 'cron') {
            $conditions['Deal.deal_status_id'] = array(
                ConstDealStatus::Expired,
                ConstDealStatus::Canceled
            );
        }
        $deals = $this->find('all', array(
            'conditions' => $conditions,
            'contain' => array(
                'DealUser' => array(
                    'User' => array(
                        'fields' => array(
                            'User.username',
                            'User.email',
                            'User.id',
                            'User.cim_profile_id',
                        ) ,
                        'UserProfile' => array(
                            'fields' => array(
                                'UserProfile.first_name',
                                'UserProfile.last_name'
                            ) ,
                        ) ,
                    ) ,
                    'SubDeal',
                    'PaypalDocaptureLog' => array(
                        'fields' => array(
                            'PaypalDocaptureLog.authorizationid',
                            'PaypalDocaptureLog.dodirectpayment_amt',
                            'PaypalDocaptureLog.id',
                            'PaypalDocaptureLog.currencycode'
                        )
                    ) ,
                    'PaypalTransactionLog' => array(
                        'fields' => array(
                            'PaypalTransactionLog.id',
                            'PaypalTransactionLog.authorization_auth_exp',
                            'PaypalTransactionLog.authorization_auth_id',
                            'PaypalTransactionLog.authorization_auth_amount',
                            'PaypalTransactionLog.authorization_auth_status'
                        )
                    ) ,
                    'AuthorizenetDocaptureLog' => array(
                        'fields' => array(
                            'AuthorizenetDocaptureLog.id',
                            'AuthorizenetDocaptureLog.authorize_amt',
                        )
                    ) ,
					'ExpresscheckoutTransactionLog' => array(
                        'fields' => array(
                            'ExpresscheckoutTransactionLog.transaction_id',
                            'ExpresscheckoutTransactionLog.mc_currency',
                            'ExpresscheckoutTransactionLog.mc_gross',
                            'ExpresscheckoutTransactionLog.id'
                        )
                    ) ,
                    'conditions' => $dealUserConditions,
                ) ,
                'Company' => array(
                    'fields' => array(
                        'Company.name',
                        'Company.id',
                        'Company.url',
                        'Company.zip',
                        'Company.address1',
                        'Company.address2',
                        'Company.city_id'
                    ) ,
                    'City' => array(
                        'fields' => array(
                            'City.id',
                            'City.name',
                            'City.slug',
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.id',
                            'State.name'
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.id',
                            'Country.name',
                            'Country.slug',
                        )
                    )
                ) ,
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.dir',
                        'Attachment.filename',
                        'Attachment.width',
                        'Attachment.height'
                    )
                ) ,
                'City' => array(
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.slug',
                    )
                ) ,
            ) ,
            'recursive' => 3,
        ));
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $this->Email = new EmailComponent($collection);
        if (!empty($deals)) {
            $dealIds = array();
            App::import('Component', 'Paypal');
            $this->Paypal = new PaypalComponent($collection);
            $paymentGateways = $this->User->Transaction->PaymentGateway->find('all', array(
                'conditions' => array(
                    'PaymentGateway.id' => array(
                        ConstPaymentGateways::CreditCard,
                        ConstPaymentGateways::AuthorizeNet,
						ConstPaymentGateways::ExpressCheckout,
                    ) ,
                ) ,
                'contain' => array(
                    'PaymentGatewaySetting' => array(
                        'fields' => array(
                            'PaymentGatewaySetting.key',
                            'PaymentGatewaySetting.test_mode_value',
                            'PaymentGatewaySetting.live_mode_value',
                        ) ,
                    ) ,
                ) ,
                'recursive' => 1
            ));
            foreach($paymentGateways as $paymentGateway) {
                if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::CreditCard) {
                    if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                        foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                            if ($paymentGatewaySetting['key'] == 'directpay_API_UserName') {
                                $paypal_sender_info['API_UserName'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                            if ($paymentGatewaySetting['key'] == 'directpay_API_Password') {
                                $paypal_sender_info['API_Password'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                            if ($paymentGatewaySetting['key'] == 'directpay_API_Signature') {
                                $paypal_sender_info['API_Signature'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                        }
                        $paypal_sender_info['is_testmode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
                    }
                }
                if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::AuthorizeNet) {
                    if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                        foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                            if ($paymentGatewaySetting['key'] == 'authorize_net_api_key') {
                                $authorize_sender_info['api_key'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                            if ($paymentGatewaySetting['key'] == 'authorize_net_trans_key') {
                                $authorize_sender_info['trans_key'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                        }
                    }
                    $authorize_sender_info['is_test_mode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
                }
                if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::ExpressCheckout) {
					$paypal_expresscheckout_sender_info = array();
					if (!empty($paymentGateway['PaymentGatewaySetting'])) {
						foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
							if ($paymentGatewaySetting['key'] == 'expressecheckout_API_UserName') {
								$paypal_expresscheckout_sender_info['API_UserName'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
							}
							if ($paymentGatewaySetting['key'] == 'expressecheckout_API_Password') {
								$paypal_expresscheckout_sender_info['API_Password'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
							}
							if ($paymentGatewaySetting['key'] == 'expressecheckout_API_Signature') {
								$paypal_expresscheckout_sender_info['API_Signature'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
							}
							$paypal_expresscheckout_sender_info['is_testmode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
						}
					}
                }
            }
            foreach($deals as $deal) {
                if (!empty($deal['DealUser'])) {
                    $dealUserIds = array();
                    $dealUserIdData = array();
                    $dealIds = array();
                    foreach($deal['DealUser'] as $deal_user) {
                        // SubDeal: If subdeal, setting subdeal array as Deal array, so all the cancel process remain for the sub and not the main //
                        if (!empty($deal['Deal']['is_subdeal_available']) && !empty($deal_user['SubDeal'])) {
                            $temp_deal = array();
                            $temp_deal = $deal['Deal'];
                            unset($deal['Deal']);
                            $deal['Deal'] = $deal_user['SubDeal'];
                        }
                        //do void for credit card
                        if ($deal_user['payment_gateway_id'] != ConstPaymentGateways::Wallet && $deal_user['payment_gateway_id'] != ConstPaymentGateways::PagSeguro) {
                            if ($deal_user['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet) {
                                require_once (APP . 'vendors' . DS . 'CIM' . DS . 'AuthnetCIM.class.php');
                                if ($authorize_sender_info['is_test_mode']) {
                                    $cim = new AuthnetCIM($authorize_sender_info['api_key'], $authorize_sender_info['trans_key'], true);
                                } else {
                                    $cim = new AuthnetCIM($authorize_sender_info['api_key'], $authorize_sender_info['trans_key']);
                                }
                                $cim->setParameter('customerProfileId', $deal_user['User']['cim_profile_id']);
                                $cim->setParameter('customerPaymentProfileId', $deal_user['payment_profile_id']);
                                $cim->setParameter('transId', $deal_user['cim_transaction_id']);
                                $cim->voidCustomerProfileTransaction();
                                // And if enbaled n Credit docapture amount and deal discount amount is not equal, add amount to user wallet and update transactions //
                                if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
                                    if ($deal_user['AuthorizenetDocaptureLog']['authorize_amt'] != $deal['Deal']['discount_amount']) {
                                        $update_wallet = '';
                                        $update_wallet = ($deal['Deal']['discounted_price']*$deal_user['quantity']) -$deal_user['AuthorizenetDocaptureLog']['orginal_amount'];
                                        //Updating transactions
										if($update_wallet!=0){
											$data = array();
											$data['Transaction']['user_id'] = $deal_user['user_id'];
											$data['Transaction']['foreign_id'] = ConstUserIds::Admin;
											$data['Transaction']['class'] = 'SecondUser';
											$data['Transaction']['amount'] = $update_wallet;
											$data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
											$transaction_id = $this->User->Transaction->log($data);
											if (!empty($transaction_id)) {
												$this->User->updateAll(array(
													'User.available_balance_amount' => 'User.available_balance_amount +' . $update_wallet
												) , array(
													'User.id' => $deal_user['user_id']
												));
											}
										}
                                    }
                                } // END  act like groupon wallet funct., //
                                if ($cim->isSuccessful()) {
                                    if (!empty($dealuser['AuthorizenetDocaptureLog']['id'])) {
                                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['id'] = $dealuser['AuthorizenetDocaptureLog']['id'];
                                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['payment_status'] = 'Cancelled';
                                        $this->DealUser->AuthorizenetDocaptureLog->save($data_authorize_docapture_log);
                                    }
                                    return true;
                                }
                            } else {
                                $payment_response = array();
                                if ($deal_user['payment_gateway_id'] == ConstPaymentGateways::CreditCard) {
                                    $post_info['authorization_id'] = $deal_user['PaypalDocaptureLog']['authorizationid'];
                                } else if ($deal_user['payment_gateway_id'] == ConstPaymentGateways::PayPalAuth) {
                                    $post_info['authorization_id'] = $deal_user['PaypalTransactionLog']['authorization_auth_id'];
								} else if ($deal_user['payment_gateway_id'] == ConstPaymentGateways::ExpressCheckout) {
									$post_info['authorization_id'] = $deal_user['ExpresscheckoutTransactionLog']['transaction_id'];
                                }
                                $post_info['note'] = __l('Deal Payment refund');
                                //call void function in paypal component
								if ($deal_user['payment_gateway_id'] == ConstPaymentGateways::ExpressCheckout) {
									$payment_response = $this->Paypal->doVoid($post_info, $paypal_expresscheckout_sender_info);
								} else {
									$payment_response = $this->Paypal->doVoid($post_info, $paypal_sender_info);
								}
                                //update payment responses
                                if (!empty($payment_response)) {
                                    if ($deal_user['payment_gateway_id'] == ConstPaymentGateways::CreditCard) {
                                        $data_paypal_docapture_log['PaypalDocaptureLog']['id'] = $deal_user['PaypalDocaptureLog']['id'];
                                        foreach($payment_response as $key => $value) {
                                            $data_paypal_docapture_log['PaypalDocaptureLog']['dovoid_' . strtolower($key) ] = $value;
                                        }
                                        $data_paypal_docapture_log['PaypalDocaptureLog']['dovoid_response'] = serialize($payment_response);
                                        $data_paypal_docapture_log['PaypalDocaptureLog']['payment_status'] = 'Cancelled';
                                        //update PaypalDocaptureLog table
                                        $this->DealUser->PaypalDocaptureLog->save($data_paypal_docapture_log);
										$this->log("credit card cancel");
										$this->log($deal);
                                        // And if enbaled n Credit docapture amount and deal discount amount is not equal, add amount to user wallet and update transactions //
                                        if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
                                            if ($data_paypal_docapture_log['PaypalDocaptureLog']['original_amount'] != $deal['Deal']['discount_amount']) {
                                                $update_wallet = '';
                                                $update_wallet = ($deal['Deal']['discounted_price']*$deal_user['quantity']) -$deal_user['PaypalDocaptureLog']['dodirectpayment_amt'];
                                                //Updating transactions
												$this->log("updatewallet amount");
												$this->log($update_wallet);
                                                $data = array();
                                                $data['Transaction']['user_id'] = $deal_user['user_id'];
                                                $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
                                                $data['Transaction']['class'] = 'SecondUser';
                                                $data['Transaction']['amount'] = $update_wallet;
                                                $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
                                                $transaction_id = $this->User->Transaction->log($data);
                                                if (!empty($transaction_id)) {
                                                    $this->User->updateAll(array(
                                                        'User.available_balance_amount' => 'User.available_balance_amount +' . $update_wallet
                                                    ) , array(
                                                        'User.id' => $deal_user['user_id']
                                                    ));
                                                }
                                            }
                                        } // END  act like groupon wallet funct., //

                                    } else if ($deal_user['payment_gateway_id'] == ConstPaymentGateways::PayPalAuth) {
                                        $data_paypal_capture_log['PaypalTransactionLog']['id'] = $deal_user['PaypalTransactionLog']['id'];
                                        foreach($payment_response as $key => $value) {
                                            $data_paypal_capture_log['PaypalTransactionLog']['void_' . strtolower($key) ] = $value;
                                        }
                                        $data_paypal_capture_log['PaypalTransactionLog']['void_data'] = serialize($payment_response);
                                        $data_paypal_capture_log['PaypalTransactionLog']['payment_status'] = 'Cancelled';
                                        $data_paypal_capture_log['PaypalTransactionLog']['error_no'] = '0';
                                        //update PaypalTransactionLog table
                                        $this->DealUser->PaypalTransactionLog->save($data_paypal_capture_log);
                                        // And if enabled n PayPal docapture amount and deal discount amount is not equal, add amount to user wallet and update transactions //
                                        if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
                                            if ($deal_user['PaypalTransactionLog']['orginal_amount'] != $deal['Deal']['discount_amount']) {
                                                $update_wallet = '';
                                                $update_wallet = ($deal['Deal']['discounted_price']*$deal_user['quantity']) -$deal_user['PaypalTransactionLog']['authorization_auth_amount'];
                                                //Updating transactions
                                                $data = array();
                                                $data['Transaction']['user_id'] = $deal_user['user_id'];
                                                $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
                                                $data['Transaction']['class'] = 'SecondUser';
                                                $data['Transaction']['amount'] = $update_wallet;
                                                $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
                                                $transaction_id = $this->User->Transaction->log($data);
                                                if (!empty($transaction_id)) {
                                                    $this->User->updateAll(array(
                                                        'User.available_balance_amount' => 'User.available_balance_amount +' . $update_wallet
                                                    ) , array(
                                                        'User.id' => $deal_user['user_id']
                                                    ));
                                                }
                                            }
                                        } // END  act like groupon wallet funct., //
                                    } else if ($deal_user['payment_gateway_id'] == ConstPaymentGateways::ExpressCheckout) {
										$data_expresscheckout_void_log = array();
                                        $data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['id'] = $deal_user['ExpresscheckoutTransactionLog']['id'];
                                        $data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['dovoid_payment_status'] = 'Cancelled';
                                        $data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['error_no'] = '0';
										$data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['dovoid_authorization_id'] = $payment_response['AUTHORIZATIONID'];
										$data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['dovoid_timestamp'] = $payment_response['TIMESTAMP'];
										$data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['dovoid_correlationid'] = $payment_response['CORRELATIONID'];
										$data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['dovoid_build'] = $payment_response['BUILD'];
                                        $this->DealUser->ExpresscheckoutTransactionLog->save($data_expresscheckout_void_log);
                                        if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
                                            if ($deal_user['ExpresscheckoutTransactionLog']['orginal_amount'] != $deal['Deal']['discount_amount']) {
                                                $update_wallet = '';
                                                $update_wallet = ($deal['Deal']['discounted_price']*$deal_user['quantity']) -$deal_user['ExpresscheckoutTransactionLog']['mc_gross'];
                                                //Updating transactions
                                                $data = array();
                                                $data['Transaction']['user_id'] = $deal_user['user_id'];
                                                $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
                                                $data['Transaction']['class'] = 'SecondUser';
                                                $data['Transaction']['amount'] = $update_wallet;
                                                $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
                                                $transaction_id = $this->User->Transaction->log($data);
                                                if (!empty($transaction_id)) {
                                                    $this->User->updateAll(array(
                                                        'User.available_balance_amount' => 'User.available_balance_amount +' . $update_wallet
                                                    ) , array(
                                                        'User.id' => $deal_user['user_id']
                                                    ));
                                                }
                                            }
                                        } // END  act like groupon wallet funct., //
                                    }
                                }
                            }
                            //authorization_auth_amount

                        } else {
                            $transaction['Transaction']['user_id'] = $deal_user['user_id'];
                            $transaction['Transaction']['foreign_id'] = $deal_user['id'];
                            $transaction['Transaction']['class'] = 'DealUser';
                            $transaction['Transaction']['amount'] = $deal_user['discount_amount'];
                            $transaction['Transaction']['transaction_type_id'] = (!empty($deal_user['is_gift'])) ? ConstTransactionTypes::DealGiftRefund : ConstTransactionTypes::DealBoughtRefund;
                            $this->DealUser->User->Transaction->log($transaction);
                            //update user balance
                            $this->DealUser->User->updateAll(array(
                                'User.available_balance_amount' => 'User.available_balance_amount +' . $deal_user['discount_amount'],
                            ) , array(
                                'User.id' => $deal_user['user_id']
                            ));
                        }
                        /* sending mail to all subscribers starts here */
                        $city = (!empty($deal['Company']['City']['name'])) ? $deal['Company']['City']['name'] : '';
                        $state = (!empty($deal['Company']['State']['name'])) ? $deal['Company']['State']['name'] : '';
                        $country = (!empty($deal['Company']['Country']['name'])) ? $deal['Company']['Country']['name'] : '';
                        $address = (!empty($deal['Company']['address1'])) ? $deal['Company']['address1'] : '';
                        //  $address.= (!empty($deal['Company']['address2'])) ? ', ' . $deal['Company']['address2'] : '';
                        $address.= (!empty($deal['Company']['City']['name'])) ? ', ' . $deal['Company']['City']['name'] : '';
                        $address.= (!empty($deal['Company']['State']['name'])) ? ', ' . $deal['Company']['State']['name'] : '';
                        $address.= (!empty($deal['Company']['Country']['name'])) ? ', ' . $deal['Company']['Country']['name'] : '';
                        $address.= (!empty($deal['Company']['zip'])) ? ', ' . $deal['Company']['zip'] : '';
                        $language_code = $this->getUserLanguageIso($deal_user['User']['id']);
                        $template = $this->EmailTemplate->selectTemplate('Deal Amount Refunded', $language_code);
                        $emailFindReplace = array(
                            '##SITE_LINK##' => Cache::read('site_url_for_shell', 'long') ,
                            '##USER_NAME##' => $deal_user['User']['username'],
                            '##SITE_NAME##' => Configure::read('site.name') ,
                            '##DEAL_NAME##' => $deal['Deal']['name'] . (!empty($deal_user['SubDeal']['name']) ? ' - ' . $deal_user['SubDeal']['name'] : '') ,
                            '##COMPANY_NAME##' => $deal['Company']['name'],
                            '##COMPANY_ADDRESS##' => $address,
                            '##CITY_NAME##' => $deal['City']['0']['name'],
                            '##FROM_EMAIL##' => $this->changeFromEmail(($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from']) ,
                            '##ORIGINAL_PRICE##' => Configure::read('site.currency') . $deal['Deal']['original_price'],
                            '##SAVINGS##' => Configure::read('site.currency') . $deal['Deal']['savings'],
                            '##BUY_PRICE##' => Configure::read('site.currency') . $deal['Deal']['discounted_price'],
                            '##DISCOUNT##' => $deal['Deal']['discount_percentage'] . ' %',
                            '##COMPANY_SITE##' => $deal['Company']['url'],
                            '##CONTACT_URL##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', 'contactus', 1) ,
                            '##DEAL_URL##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                                'controller' => 'deals',
                                'action' => 'view',
                                $deal['Deal']['slug'],
                                'admin' => false
                            ) , false) , 1) ,
                            '##DEAL_LINK##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                                'controller' => 'deals',
                                'action' => 'view',
                                $deal['Deal']['slug'],
                                'admin' => false
                            ) , false) , 1) ,
                            '##IMG_URL##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                                'controller' => 'img',
                                'action' => $image_hash,
                            ) , false) , 1) ,
                            '##SITE_LOGO##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                                'controller' => 'img',
                                'action' => 'blue-theme',
                                'logo-email.png',
                                'admin' => false
                            ) , false) , 1) ,
                        );
                        $this->Email->from = ($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from'];
                        $this->Email->replyTo = ($template['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $template['reply_to'];
                        $this->Email->to = $this->formatToAddress($deal_user);
                        $this->Email->subject = strtr($template['subject'], $emailFindReplace);
                        $this->Email->content = strtr($template['email_content'], $emailFindReplace);
                        $this->Email->sendAs = ($template['is_html']) ? 'html' : 'text';
                        $this->Email->send($this->Email->content);
                        $dealUserIds[] = $deal_user['id'];
                        $dealIds[] = $deal['Deal']['id'];
                        // SubDeal: Resetting the actual deal array //
                        if (!empty($temp_deal)) {
                            $deal['Deal'] = $temp_deal;
                        }
                        // after save fields //
                        $data_for_aftersave = array();
                        $data_for_aftersave['deal_id'] = $deal['Deal']['id'];
                        $data_for_aftersave['deal_user_id'] = $deal_user['id'];
                        $data_for_aftersave['user_id'] = $deal_user['user_id'];
                        $data_for_aftersave['company_id'] = $deal['Company']['id'];
                        $data_for_aftersave['payment_gateway_id'] = $deal_user['payment_gateway_id'];
                        $dealUserIdData[] = $data_for_aftersave;
                        //$this->_updateAfterPurchase($data_for_aftersave, 'cancel');

                    }
                    if (!empty($dealUserIds)) {
                        //is_repaid field updated
                        $this->DealUser->updateAll(array(
                            'DealUser.is_repaid' => 1,
                        ) , array(
                            'DealUser.id' => $dealUserIds
                        ));
                        $this->updateAll(array(
                            'Deal.deal_user_count' => 0,
                        ) , array(
                            'Deal.id' => $dealIds
                        ));
                        foreach($dealUserIdData as $dealUserData) {
                            $this->_updateAfterPurchase($dealUserData, 'cancel');
                        }
                        $refundedDealIds[] = $deal['Deal']['id'];
                    }
                }
            }
            if (!empty($refundedDealIds)) {
                foreach($refundedDealIds as $refunded_deal_id) {
                    $data = array();
                    $data['Deal']['id'] = $refunded_deal_id;
                    $data['Deal']['deal_status_id'] = ConstDealStatus::Refunded; // Already updated in model itself, calling it again for affiliate behaviour
                    $this->save($data);
                }
            }
        }
    }
    //pay deal amount to company when deal in closed status
    function _payToCompany($pay_type = '', $dealIds = array())
    {
        $conditions = array();
        if (!empty($dealIds)) {
            $conditions['Deal.id'] = $dealIds;
        } elseif ($pay_type == 'cron') {
            $conditions['Deal.deal_status_id'] = ConstDealStatus::Closed;
        }
        $deals = $this->find('all', array(
            'conditions' => $conditions,
            'contain' => array(
                'Company' => array(
                    'fields' => array(
                        'Company.name',
                        'Company.id',
                        'Company.user_id',
                        'Company.url',
                        'Company.zip',
                        'Company.address1',
                        'Company.address2',
                        'Company.city_id',
                        'Company.is_online_account'
                    ) ,
                ) ,
            ) ,
            'recursive' => 0,
        ));
        if (!empty($deals)) {
            foreach($deals as $deal) {
                $user_id = $deal['Company']['user_id'];
                $amount = ($deal['Deal']['total_purchased_amount']-($deal['Deal']['total_commission_amount']));
                $data = array();
                //pay deal amount to company
                $data['Transaction']['user_id'] = ConstUserIds::Admin;
                $data['Transaction']['foreign_id'] = $deal['Deal']['id'];
				$data['Transaction']['receiver_user_id'] = $user_id;
                $data['Transaction']['class'] = 'Deal';
                $data['Transaction']['amount'] = $amount;
                $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::PaidDealAmountToCompany;
                $this->User->Transaction->log($data);                
                //amount to charity given //
                if (!empty($deal['Deal']['seller_charity_amount']) && $deal['Deal']['seller_charity_amount'] != '0.00') {
                    $data['Transaction']['user_id'] = $user_id;
                    $data['Transaction']['foreign_id'] = $deal['Deal']['id'];
                    $data['Transaction']['class'] = 'Deal';
                    $data['Transaction']['amount'] = $deal['Deal']['seller_charity_amount'];
                    $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AmountTakenForCharity;
                    $this->User->Transaction->log($data);
                }
                if (!empty($deal['Deal']['site_charity_amount']) && $deal['Deal']['site_charity_amount'] != '0.00') {
					$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
                    $data['Transaction']['user_id'] = ConstUserIds::Admin;
                    $data['Transaction']['foreign_id'] = $deal['Deal']['id'];
                    $data['Transaction']['class'] = 'Deal';
                    $data['Transaction']['amount'] = $deal['Deal']['site_charity_amount'];
                    $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AmountTakenForCharityFromAdmin;
                    $this->User->Transaction->log($data);
                }
                $this->User->updateAll(array(
                    'User.available_balance_amount' => 'User.available_balance_amount +' . $amount,
                ) , array(
                    'User.id' => $user_id
                ));
                $paidDealIds[] = $deal['Deal']['id'];
            }
            $this->updateAll(array(
                'Deal.deal_status_id' => ConstDealStatus::PaidToCompany,
                'Deal.end_date' => '"' . date('Y-m-d H:i:s') . '"'
            ) , array(
                'Deal.id' => $paidDealIds
            ));
            // after save fields //
            $data_for_aftersave = array();
            $data_for_aftersave['deal_id'] = $deal['Deal']['id'];
            $data_for_aftersave['company_id'] = $deal['Deal']['company_id'];
            $this->_updateCompanyAfterPurchase($data_for_aftersave, 'cleared');
        }
    }
    // pay to charity
    function _payToCharity($deal_id)
    {
        $dealUserIds = $this->DealUser->find('list', array(
            'conditions' => array(
                'DealUser.deal_id' => $deal_id
            ) ,
            'fields' => array(
                'DealUser.id'
            ) ,
            'recursive' => -1,
        ));
        $charitiesDealUsers = $this->DealUser->CharitiesDealUser->find('all', array(
            'conditions' => array(
                'CharitiesDealUser.deal_user_id' => $dealUserIds
            ) ,
            'recursive' => -1,
        ));
		$this->log('Charity deal Users');
		$this->log($charitiesDealUsers);
        $total_charity_amount = $site_charity_amount = $seller_charity_amount = 0;
        foreach($charitiesDealUsers as $charitiesDealUser) {
            $total_charity_amount+= $charitiesDealUser['CharitiesDealUser']['amount'];
            $site_charity_amount+= $charitiesDealUser['CharitiesDealUser']['site_commission_amount'];
            $seller_charity_amount+= $charitiesDealUser['CharitiesDealUser']['seller_commission_amount'];
            // update in charity
            $this->Charity->updateAll(array(
                'Charity.total_amount' => 'Charity.total_amount + ' . $charitiesDealUser['CharitiesDealUser']['amount'],
                'Charity.available_amount' => 'Charity.available_amount + ' . $charitiesDealUser['CharitiesDealUser']['amount'],
                'Charity.total_site_amount' => 'Charity.total_site_amount + ' . $charitiesDealUser['CharitiesDealUser']['site_commission_amount'],
                'Charity.total_seller_amount' => 'Charity.total_seller_amount + ' . $charitiesDealUser['CharitiesDealUser']['seller_commission_amount']
            ) , array(
                'Charity.id' => $charitiesDealUser['CharitiesDealUser']['charity_id']
            ));
        }
        $this->updateAll(array(
            'Deal.total_charity_amount' => $total_charity_amount,
            'Deal.site_charity_amount' => $site_charity_amount,
            'Deal.seller_charity_amount' => $seller_charity_amount
        ) , array(
            'Deal.id' => $deal_id
        ));
		$this->log('After update');
    }
    function _sendIphonePushMessage($deal_ids = array())
    {
        $push_mail_setting = Configure::read('subscription.iphone_apns_push_mail');
        if (Configure::read('subscription.iphone_apns_push_mail_enable')) {
            App::import('Model', 'Subscription');
            $this->Subscription = new Subscription();
            $deals = $this->find('all', array(
                'conditions' => array(
                    'Deal.id' => $deal_ids,
                    'Deal.deal_status_id' => ConstDealStatus::Open,
                    'Deal.is_subscription_mail_sent' => 0
                ) ,
                'contain' => array(
                    'City' => array(
                        'fields' => array(
                            'City.id',
                            'City.name',
                            'City.slug',
                        ) ,
                        'Subscription' => array(
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
                    ) ,
                ) ,
                'fields' => array(
                    'Deal.id',
                    'Deal.name',
                    'Deal.slug',
                    'Deal.deal_status_id',
                ) ,
                'recursive' => 4
            ));
            $push_message = array();
            foreach($deals as $deal) {
                foreach($deal['City'] as $deal_city) { // Taking Current Open Deals //
                    if (!empty($deal_city['Subscription'])) {
                        foreach($deal_city['Subscription'] as $deal_subscription) {
                            //if(!empty($deal_subscription['User']['iphone_is_active']) && !empty($deal_subscription['User']['iphone_is_active'])){
                            if (!empty($deal_subscription['User']['id'])) {
                                $push_message[$deal_subscription['city_id']]['deal'][$deal['Deal']['id']] = $deal['Deal']['name'];
                                foreach($deal_subscription['User']['ApnsDevice'] as $apn_subscription) {
                                    if (!empty($apn_subscription['pid'])) {
                                        $push_message[$deal_subscription['city_id']]['user'][$apn_subscription['pid']] = array(
                                            'uid' => $deal_subscription['User']['id'],
                                            'pid' => $apn_subscription['pid'],
                                        );
                                    }
                                }
                            }
                            $push_message[$deal_subscription['city_id']]['city'] = $deal_city['name'];
                        }
                    }
                }
            }
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
            // Processing //
            if (!empty($push_message)) {
                foreach($push_message as $key => $value) {
                    if (!empty($value['deal'])) {
                        if (count($value['deal']) > 1) {
                            $message_skel = array();
                            $message_skel['##DEAL_COUNT##'] = count($value['deal']);
                            $message_skel['##CITY##'] = $value['city'];
                            $message = strtr(Configure::read('subscription.iphone_short_push_message_format') , $message_skel);
                        } else {
                            $message_skel = array();
                            $deal_name = array_values($value['deal']);
                            $message_skel['##DEAL_NAME##'] = '"' . $deal_name[0] . '"';
                            $message_skel['##CITY##'] = '"' . $value['city'] . '"';
                            $message = strtr(Configure::read('subscription.iphone_push_message_format') , $message_skel);
                            if (strlen($message) > 256) {
                                $message = strtr(Configure::read('subscription.iphone_short_push_message_format') , $message_skel);
                            }
                        }
                    }
                    if (!empty($message)) {
                        foreach($value['user'] as $user) {
                            if (!empty($user['pid'])) {
                                if ($push_mail_setting == ConstIphoneApnsPushMail::Site) { // Enabled for site
                                    $apns->newMessage($user['pid'], '0000-00-00 00:00:00', $user['uid']);
                                    $apns->addMessageAlert($message);
                                    $apns->queueMessage();
                                } else if ($push_mail_setting == ConstIphoneApnsPushMail::UrbanAirShip) { // IF AirShip Enabled
                                    $uas_message = array(
                                        'aps' => array(
                                            'alert' => $message
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
    //send subscription mail to users once deal coming to open status
    function _sendSubscriptionMail()
    {
        App::import('Model', 'ApnsMessage');
        $this->ApnsMessage = new ApnsMessage();
        App::import('Model', 'Subscription');
        $this->Subscription = new Subscription();
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $this->Email = new EmailComponent($collection);
        $i = 0;
        $dealIphoneId = array();
        do {
            $deals = $this->find('all', array(
                'conditions' => array(
                    'Deal.deal_status_id' => ConstDealStatus::Open,
                    'Deal.is_subscription_mail_sent' => 0
                ) ,
                'contain' => array(
                    'SubDeal',
                    'Company' => array(
                        'fields' => array(
                            'Company.name',
                            'Company.id',
                            'Company.url',
                            'Company.zip',
                            'Company.address1',
                            'Company.address2',
                            'Company.city_id',
                            'Company.phone'
                        ) ,
                        'City' => array(
                            'fields' => array(
                                'City.id',
                                'City.name',
                                'City.slug',
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.id',
                                'State.name'
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.id',
                                'Country.name',
                                'Country.slug',
                            )
                        )
                    ) ,
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.dir',
                            'Attachment.filename',
                            'Attachment.width',
                            'Attachment.height'
                        )
                    ) ,
                    'CitiesDeal' => array(
                        'City' => array(
                            'Subscription' => array(
                                'fields' => array(
                                    'Subscription.id',
                                    'Subscription.user_id',
                                    'Subscription.email',
                                ) ,
                                'conditions' => array(
                                    'Subscription.is_subscribed' => 1
                                )
                            ) ,
                            'fields' => array(
                                'City.id',
                                'City.name',
                                'City.slug',
                            )
                        )
                    ) ,
                    'City' => array(
                        'fields' => array(
                            'City.id',
                            'City.name',
                            'City.slug',
                        )
                    ) ,
                ) ,
                'recursive' => 2,
                'limit' => 2,
                'offset' => $i
            ));
            if (!empty($deals)) {
                $dealIds = $mail_chimp_missed_cities = array();
                $email_contents = array();
                foreach($deals as $deal) {
                    // Updating Deal subscriptions
                    $dealIphoneId[] = $deal['Deal']['id'];
                    $this->updateAll(array(
                        'Deal.is_subscription_mail_sent' => 1,
                    ) , array(
                        'Deal.id' => $deal['Deal']['id']
                    ));
                    $savings = $deal['Deal']['savings'];
                    $buy_price = $deal['Deal']['discounted_price'];
                    /* sending mail to all subscribers starts here */
                    $city = (!empty($deal['Company']['City']['name'])) ? $deal['Company']['City']['name'] : '';
                    $state = (!empty($deal['Company']['State']['name'])) ? $deal['Company']['State']['name'] : '';
                    $country = (!empty($deal['Company']['Country']['name'])) ? $deal['Company']['Country']['name'] : '';
                    $address = (!empty($deal['Company']['address2'])) ? $deal['Company']['address2'] : '';
                    $address.= (!empty($deal['Company']['phone'])) ? ', ' . $deal['Company']['phone'] : '';
                    $image_hash = '';
                    if (!empty($deal['Attachment']['id'])) {
                        $image_hash = 'small_big_thumb/Deal/' . $deal['Attachment']['id'] . '.' . md5(Configure::read('Security.salt') . 'Deal' . $deal['Attachment']['id'] . 'jpg' . 'small_big_thumb' . Configure::read('site.name')) . '.' . 'jpg';
                    }
                    $deal_url = Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                        'controller' => 'deals',
                        'action' => 'view',
                        'city' => $deal['City']['0']['slug'],
                        $deal['Deal']['slug'],
                        'admin' => false
                    ) , false) , 1);
                    $image_options = array(
                        'dimension' => 'subscription_thumb',
                        'class' => '',
                        'alt' => $deal['Deal']['name'],
                        'title' => $deal['Deal']['name'],
                        'type' => 'jpg'
                    );
                    $image_options_group = array(
                        'dimension' => 'subscription_group_thumb',
                        'class' => '',
                        'alt' => $deal['Deal']['name'],
                        'title' => $deal['Deal']['name'],
                        'type' => 'jpg'
                    );
                    $src = '';
                    $src_group = '';
                    if (!empty($deal['Attachment'][0])) {
                        $src = $this->getImageUrl('Deal', $deal['Attachment'][0], $image_options);
                    }
                    if (!empty($deal['Attachment'][0])) {
                        $src_group = $this->getImageUrl('Deal', $deal['Attachment'][0], $image_options_group);
                    }
                    $tmpURL = $this->getCityTwitterFacebookURL($deal['City']['0']['slug']);
                    ///Deals having subdeal
                    if ($deal['Deal']['is_subdeal_available'] != 0 && !empty($deal['SubDeal']) && $deal['Deal']['is_now_deal'] != 1) {
                        foreach($deal['SubDeal'] as $subdeal) {
                            $subdeal_name = $deal['Deal']['name'] . '(' . $subdeal['name'] . ')';
                            $subdeal_price = $subdeal['original_price'];
                            $buy_price = $subdeal['discounted_price'];
                            $savings = $subdeal['savings'];
                            $subdeal_dicount = $subdeal['discount_percentage'];
                        }
                    } else {
                        $subdeal_name = $deal['Deal']['name'];
                        $subdeal_price = $deal['Deal']['original_price'];
                        $subdeal_dicount = $deal['Deal']['discount_percentage'];
                    }
                    $emailFindReplace = array(
                        '##SITE_LINK##' => Cache::read('site_url_for_shell', 'long') ,
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##DEAL_NAME##' => $subdeal_name,
                        '##COMPANY_NAME##' => $deal['Company']['name'],
                        '##COMPANY_ADDRESS##' => $address,
                        '##COMPANY_WEBSITE##' => $deal['Company']['url'],
                        '##CITY_NAME##' => $deal['City']['0']['name'],
                        '##ORIGINAL_PRICE##' => Configure::read('site.currency') . $subdeal_price,
                        '##SAVINGS##' => Configure::read('site.currency') . $savings,
                        '##BUY_PRICE##' => Configure::read('site.currency') . $buy_price,
                        '##DISCOUNT##' => $subdeal_dicount . '%',
                        '##DESCRIPTION##' => $deal['Deal']['description'],
                        '##COMPANY_SITE##' => $deal['Company']['url'],
                        '##DEAL_URL##' => $deal_url, //This vaule is replaced in this function later
                        '##DEAL_LINK##' => $deal_url, //This vaule is replaced in this function later
                        '##CONTACT_URL##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'contacts',
                            'action' => 'add',
                            'city' => $deal['City']['0']['slug'],
                            'admin' => false
                        ) , false) , 1) ,
                        '##TWITTER_URL##' => !empty($tmpURL['City']['twitter_url']) ? $tmpURL['City']['twitter_url'] : Configure::read('twitter.site_twitter_url') ,
                        '##FACEBOOK_URL##' => !empty($tmpURL['City']['facebook_url']) ? $tmpURL['City']['facebook_url'] : Configure::read('facebook.site_facebook_url') ,
                        '##DATE##' => date('l, F j, Y', strtotime(_formatDate('Y-m-d H:i:s', strtotime($deal['Deal']['start_date'])))) ,
                        '##END_DATE##' => (empty($deal['Deal']['is_anytime_deal'])) ? (__l("End Date: ") . strftime(Configure::read('site.datetime.format') , strtotime(_formatDate('Y-m-d H:i:s', strtotime($deal['Deal']['end_date']))))) : '',
                        '##FACEBOOK_IMAGE##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'img',
                            'action' => 'icon-facebook.png',
                            'admin' => false
                        ) , false) , 1) ,
                        '##TWITTER_IMAGE##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'img',
                            'action' => 'icon-twitter.png',
                            'admin' => false
                        ) , false) , 1) ,
                        '##READMORE_IMAGE##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'img',
                            'action' => 'readmore.png',
                            'admin' => false
                        ) , false) , 1) ,
                        '##COMMENT##' => $deal['Deal']['comment'],
                        '##CONTACT_US##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'contacts',
                            'action' => 'add',
                            'city' => $deal['City']['0']['slug'],
                            'admin' => false
                        ) , false) , 1) ,
                        '##BACKGROUND##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'img',
                            'action' => 'blue-theme',
                            'ing13.png',
                            'admin' => false
                        ) , false) , 1) ,
                        '##DEAL_IMAGE_NORMAL##' => $src,
                        '##DEAL_IMAGE_GROUP##' => $src_group,
                        '##BTN_IMAGE##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'img',
                            'action' => 'blue-theme',
                            'btn1.png',
                            'admin' => false
                        ) , false) , 1) ,
                        '##READMORE_IMAGE##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'img',
                            'action' => 'blue-theme',
                            'readmore.png',
                            'admin' => false
                        ) , false) , 1) ,
                        '##READMORE##' => $deal_url,
                        '##EMAIL-COMMENT-BG##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'img',
                            'action' => 'blue-theme',
                            'email-comment-bg.png',
                            'admin' => false
                        ) , false) , 1) ,
                        '##SITE_LOGO##' => Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'img',
                            'action' => 'blue-theme',
                            'logo-email.png',
                            'admin' => false
                        ) , false) , 1) ,
                    );
                    foreach($deal['CitiesDeal'] as $city_deal) {
                        $sub_city_id[] = $city_deal['city_id'];
                        // Sending mail through MailChimp Server //
                        $deal_url = Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'deals',
                            'action' => 'view',
                            'city' => $city_deal['City']['slug'],
                            $deal['Deal']['slug'],
                            'admin' => false
                        ) , false) , 1);
                        $emailFindReplace['##UNSUB_LNK##'] = "<a href='" . Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'subscriptions',
                            'action' => 'unsubscribe_mailchimp',
                            'sub_city' => $city_deal['City']['slug'],
                            'email' => "*|HTML:EMAIL|*",
                            'admin' => false
                        ) , false) , 1) . "' title='Unsubscribe'>unsubscribe me!</a>" . ".";
                        $emailFindReplace['##UNSUB_LBL##'] = '';
                        $emailFindReplace['##CITY_NAME##'] = $city_deal['City']['name'];
                        $emailFindReplace['##DEAL_URL##'] = Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'deals',
                            'action' => 'view',
                            'city' => $city_deal['City']['slug'],
                            $deal['Deal']['slug'],
                            'admin' => false
                        ) , false) , 1);
                        $emailFindReplace['##DEAL_LINK##'] = Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'deals',
                            'action' => 'view',
                            'city' => $city_deal['City']['slug'],
                            $deal['Deal']['slug'],
                            'utm_source' => 'newsletter',
                            'utm_medium' => 'email',
                            'utm_term' => Configure::read('site.name') ,
                            'utm_campaign' => $deal['Deal']['slug'],
                            'utm_content' => $deal['Deal']['slug'] . '_' . $city_deal['City']['slug'],
                            'admin' => false
                        ) , false) , 1);
                        $template = $this->EmailTemplate->selectTemplate('Deal of the day Group Mail');
                        $emailFindReplace['##DEAL_LINK_GROUP_MAIL##'] = Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                            'controller' => 'deals',
                            'action' => 'view',
                            'city' => $city_deal['City']['slug'],
                            $deal['Deal']['slug'],
                            'utm_source' => 'newsletter',
                            'utm_medium' => 'email',
                            'utm_term' => Configure::read('site.name') ,
                            'utm_campaign' => $deal['Deal']['slug'],
                            'utm_content' => str_replace(" ", "_", $template['subject']) ,
                            'admin' => false
                        ) , false) , 1);
                        if (empty($email_contents[$city_deal['city_id']])) {
                            $email_contents[$city_deal['city_id']] = array();
                        }
                        $emailFindReplace['pushmessage'] = array(
                            'deal_id' => $deal['Deal']['id'],
                            'deal_name' => $deal['Deal']['name']
                        );
                        $email_contents[$city_deal['city_id']][] = $emailFindReplace;
                        $email_contents[$city_deal['city_id']]['##CITY_SLUG##'] = $city_deal['City']['slug'];
                        $dealIds[] = $deal['Deal']['id'];
                        // END OF MAIL SEND THROUGH MAILCHIMP //

                    }
                }
                if (Configure::read('mailchimp.is_enabled')) {
                    $this->Subscription->_send_mail_chimp_subscription_mail($email_contents);
                } else {
                    $this->Subscription->_send_normal_subscription_mail($email_contents);
                }
                if (Configure::read('subscription.iphone_apns_push_mail_enable')) {
                    $this->ApnsMessage->_sendIphonePushMessage($email_contents);
                }
            }
            $i+= 2;
        }
        while (!empty($deals));
    }
    //close deals and calculate commission amount and net profit
    function _closeDeals($dealIds = array())
    {
        $type = 'moreaction';
        $conditions = array();
        if (empty($dealIds)) {
            $type = 'cron';
            $deals = $this->find('list', array(
                'conditions' => array(
                    'Deal.deal_status_id' => ConstDealStatus::Tipped,
                    'Deal.end_date <= ' => _formatDate('Y-m-d H:i:s', date('Y-m-d H:i:s') , true) ,
                    'Deal.is_anytime_deal' => 0,
                ) ,
                'fields' => array(
                    'Deal.id',
                    'Deal.id',
                ) ,
            ));
            if (!empty($deals)) {
                $dealIds = array_keys($deals);
            }
        }
        if (!empty($dealIds)) {
            $deals = $this->find('all', array(
                'conditions' => array(
                    'Deal.id' => $dealIds
                ) ,
                'fields' => array(
                    'Deal.id',
                    'Deal.discounted_price',
                    'Deal.deal_user_count',
                    'Deal.total_purchased_amount',
                    'Deal.commission_percentage',
                    'Deal.bonus_amount',
                    'Deal.is_subdeal_available',
                    'Deal.is_now_deal',
                ) ,
                'recursive' => -1
            ));
			$this->log('Closed deal');
			$this->log($deal);
            $status = false;
            foreach($deals as $deal) {
                $this->_payToCharity($deal['Deal']['id']);
                if (!empty($deal['Deal']['is_subdeal_available'])) {
                    if ($deal['Deal']['is_now_deal'] && !$status) {
                        $status = true;
                        if ($type == 'moreaction') {
                            $this->DealUser->close_now_deal($deal['Deal']['id']);
                        } elseif ($type == 'cron') {
                            $this->DealUser->auto_cancel_yesterday_now_deal();
                        }
                    }
                    $get_deal = $this->find('all', array(
                        'conditions' => array(
                            'Deal.parent_id' => $deal['Deal']['id']
                        ) ,
                        'fields' => array(
                            'SUM(Deal.discounted_price * Deal.deal_user_count) as total_purchased_amount',
                            'SUM(Deal.bonus_amount + ((Deal.discounted_price * Deal.deal_user_count) * (Deal.commission_percentage/100)))as total_commission_amount',
                        ) ,
                        'recursive' => -1
                    ));
                    $data = array();
                    $data['Deal']['id'] = $deal['Deal']['id'];
                    $data['Deal']['total_purchased_amount'] = $get_deal[0][0]['total_purchased_amount'];
                    $data['Deal']['total_commission_amount'] = $get_deal[0][0]['total_commission_amount'];
                    $data['Deal']['end_date'] = date('Y-m-d H:i:s');
                    $data['Deal']['deal_status_id'] = ConstDealStatus::Closed; // Already updated in model itself, calling it again for affiliate behaviour
                    $this->save($data, false);
                } else {
                    $data = array();
                    $data['Deal']['id'] = $deal['Deal']['id'];
                    $data['Deal']['total_purchased_amount'] = ($deal['Deal']['discounted_price']*$deal['Deal']['deal_user_count']);
                    $data['Deal']['total_commission_amount'] = ($deal['Deal']['bonus_amount']+($data['Deal']['total_purchased_amount']*($deal['Deal']['commission_percentage']/100)));
                    $data['Deal']['end_date'] = date('Y-m-d H:i:s');
                    $data['Deal']['deal_status_id'] = ConstDealStatus::Closed; // Already updated in model itself, calling it again for affiliate behaviour
                    $this->save($data, false);
                }
            }
            $this->_sendBuyersListCompany($dealIds);
        }
    }
    //process deals which are coming to open status
    function _processOpenStatus($deal_id = null)
    {
        $conditions = array();
        if (is_null($deal_id)) {
            $conditions['Deal.deal_status_id'] = ConstDealStatus::Upcoming;
            $conditions['Deal.start_date <='] = _formatDate('Y-m-d H:i:s', date('Y-m-d H:i:s') , true);
        } else {
            $conditions['Deal.id'] = $deal_id;
        }
        $deals = $this->find('all', array(
            'conditions' => array(
                $conditions,
            ) ,
            'contain' => array(
                'CitiesDeal',
                'City' => array(
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.slug',
                        'City.fb_user_id',
                        'City.fb_access_token',
                        'City.facebook_page_id',
                        'City.twitter_username',
                        'City.twitter_access_token',
                        'City.twitter_access_key',
                        'City.twitter_url',
                        'City.facebook_url',
                        'City.foursquare_venue',
                    )
                ) ,
                'Attachment',
            ) ,
            'recursive' => 2
        ));
        if (!empty($deals)) {
            // Importing Facebook //
            if (Configure::read('facebook.enable_facebook_post_open_deal') == 1) {
                App::import('Vendor', 'facebook/facebook');
                $this->facebook = new Facebook(array(
                    'appId' => Configure::read('facebook.fb_api_key') ,
                    'secret' => Configure::read('facebook.fb_secrect_key') ,
                    'cookie' => true
                ));
            }
            // Importing Twitter //
            if (Configure::read('twitter.enable_twitter_post_open_deal') == 1) {
                App::import('Core', 'ComponentCollection');
                $collection = new ComponentCollection();
                App::import('Component', 'OauthConsumer');
                $this->OauthConsumer = new OauthConsumerComponent($collection);
            }
            // Importing FourSquare //
            if (Configure::read('foursquare.enable_foursquare_post_open_deal') == 1) {
                $client_key = Configure::read('foursquare.consumer_key');
                $client_secret = Configure::read('foursquare.consumer_secret');
                $token = Configure::read('foursquare.site_user_access_token');
                include_once APP . 'vendors' . DS . 'foursquare' . DS . 'FoursquareAPI.class.php';
                // Load the Foursquare API library
                $foursquare = new FoursquareAPI($client_key, $client_secret);
                $foursquare->SetAccessToken($token);
            }
            foreach($deals as $deal) {
                if (Configure::read('twitter.enable_twitter_post_open_deal') or Configure::read('facebook.enable_facebook_post_open_deal') or Configure::read('foursquare.enable_foursquare_post_open_deal')) {
                    $slug = $deal['Deal']['slug'];
                    $deal_id = $deal['Deal']['id'];
                    foreach($deal['City'] as $k => $city) {
                        $city_slug = $city['slug'];
                        $city_id = $city['id'];
                        if (empty($city['CitiesDeal']['bitly_short_url_subdomain']) or empty($city['CitiesDeal']['bitly_short_url_prefix'])) {
                            $bitly_short_url = $this->_updateDealBitlyURL($slug, $city_slug, $city_id, $deal_id);
                            if (Configure::read('site.city_url') == 'prefix') {
                                if ($bitly_short_url['prefix']) {
                                    $url = $bitly_short_url['prefix'];
                                } else {
                                    $url = Router::url('/', true) . 'deal/' . $slug . '/city:' . $city_slug;
                                }
                            } else {
                                if ($bitly_short_url['subdomain']) {
                                    $url = $bitly_short_url['subdomain'];
                                } else {
                                    $url = 'http://' . $city_slug . '.' . $domain . 'deal/' . $slug;
                                }
                            }
                        } else {
                            if (Configure::read('site.city_url') == 'prefix') {
                                $url = $city['CitiesDeal']['bitly_short_url_prefix'];
                            } else {
                                $url = $city['CitiesDeal']['bitly_short_url_subdomain'];
                            }
                        }
                    }
                    // Posting In Social Networking //
                    $fb_message = strtr(Configure::read('facebook.new_deal_message') , array(
                        '##DEAL_LINK##' => $url,
                        '##DEAL_NAME##' => $deal['Deal']['name'],
                    ));
                    $tw_message = strtr(Configure::read('twitter.new_deal_message') , array(
                        '##URL##' => $url,
                        '##DEAL_NAME##' => $deal['Deal']['name'],
                        '##SLUGED_SITE_NAME##' => Inflector::slug(strtolower(Configure::read('site.name'))) ,
                    ));
                    $fs_message = strtr(Configure::read('foursquare.new_deal_message') , array(
                        '##DEAL_NAME##' => $deal['Deal']['name'],
                        '##SLUGED_SITE_NAME##' => Inflector::slug(strtolower(Configure::read('site.name'))) ,
                    ));
                    $image_options = array(
                        'dimension' => 'normal_thumb',
                        'class' => 'Deal',
                        'alt' => $deal['Deal']['name'],
                        'title' => $deal['Deal']['name'],
                        'type' => 'jpg'
                    );
                    $image_url = '';
                    if (!empty($deal['Attachment'][0])) {
                        $image_url = $this->getImageUrl('Deal', $deal['Attachment'][0], $image_options);
                    }
                    foreach($deal['City'] as $i => $city) {
                        // Post in facebook //
                        if (Configure::read('facebook.enable_facebook_post_open_deal') == 1) {
                            $data['message'] = $fb_message;
                            $data['image_url'] = $image_url;
                            $data['url'] = $url;
                            $data['description'] = $deal['Deal']['description'];
                            $data['fb_access_token'] = $city['fb_access_token'];
                            $data['fb_user_id'] = (!empty($city['facebook_page_id']) ? $city['facebook_page_id'] : $city['fb_user_id']);
                            $this->_postInFacebook($data);
                        }
                        // Post in twitter //
                        if (Configure::read('twitter.enable_twitter_post_open_deal') == 1) {
                            $data['message'] = $tw_message;
                            $data['twitter_access_token'] = $city['twitter_access_token'];
                            $data['twitter_access_key'] = $city['twitter_access_key'];
                            $this->_postInTwitter($data);
                        }
                        // Post in FourSquare //
                        if (Configure::read('foursquare.enable_foursquare_post_open_deal') == 1) {
                            $foursquare_venue_id = (!empty($city['foursquare_venue'])) ? $city['foursquare_venue'] : Configure::read('foursquare.site_foursquare_venue_id');
                            $params['text'] = $fs_message;
                            $params['url'] = $url;
                            $params['venueId'] = $foursquare_venue_id;
                            $tipsresult = $foursquare->postTips($params);
                        }
                    } // EO - Posting //

                }
            }
        }
    }
    function _refundDealAmountForCacel($dealuser = array())
    {
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $this->Email = new EmailComponent($collection);
        App::import('Component', 'Paypal');
        $this->Paypal = new PaypalComponent($collection);
        $paymentGateways = $this->User->Transaction->PaymentGateway->find('all', array(
            'conditions' => array(
                'PaymentGateway.id' => array(
                    ConstPaymentGateways::CreditCard,
                    ConstPaymentGateways::AuthorizeNet,
					ConstPaymentGateways::ExpressCheckout
                ) ,
            ) ,
            'contain' => array(
                'PaymentGatewaySetting' => array(
                    'fields' => array(
                        'PaymentGatewaySetting.key',
                        'PaymentGatewaySetting.test_mode_value',
                        'PaymentGatewaySetting.live_mode_value',
                    ) ,
                ) ,
            ) ,
            'recursive' => 1
        ));
        foreach($paymentGateways as $paymentGateway) {
            if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::CreditCard) {
                if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                    foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                        if ($paymentGatewaySetting['key'] == 'directpay_API_UserName') {
                            $paypal_sender_info['API_UserName'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        if ($paymentGatewaySetting['key'] == 'directpay_API_Password') {
                            $paypal_sender_info['API_Password'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        if ($paymentGatewaySetting['key'] == 'directpay_API_Signature') {
                            $paypal_sender_info['API_Signature'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                    }
                    $paypal_sender_info['is_testmode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
                }
            }
            if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::AuthorizeNet) {
                if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                    foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                        if ($paymentGatewaySetting['key'] == 'authorize_net_api_key') {
                            $authorize_sender_info['api_key'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        if ($paymentGatewaySetting['key'] == 'authorize_net_trans_key') {
                            $authorize_sender_info['trans_key'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                    }
                }
                $authorize_sender_info['is_test_mode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
            }
			if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::ExpressCheckout) {
				$paypal_expresscheckout_sender_info = array();
				if (!empty($paymentGateway['PaymentGatewaySetting'])) {
					foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
						if ($paymentGatewaySetting['key'] == 'expressecheckout_API_UserName') {
							$paypal_expresscheckout_sender_info['API_UserName'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
						}
						if ($paymentGatewaySetting['key'] == 'expressecheckout_API_Password') {
							$paypal_expresscheckout_sender_info['API_Password'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
						}
						if ($paymentGatewaySetting['key'] == 'expressecheckout_API_Signature') {
							$paypal_expresscheckout_sender_info['API_Signature'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
						}
						$paypal_expresscheckout_sender_info['is_testmode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
					}
				}
			}
        }
        //do void for credit card
        if ($dealuser['DealUser']['payment_gateway_id'] != ConstPaymentGateways::Wallet && $dealuser['DealUser']['payment_gateway_id'] != ConstPaymentGateways::PagSeguro) {
            if ($dealuser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet) {
                require_once (APP . 'vendors' . DS . 'CIM' . DS . 'AuthnetCIM.class.php');
                if ($authorize_sender_info['is_test_mode']) {
                    $cim = new AuthnetCIM($authorize_sender_info['api_key'], $authorize_sender_info['trans_key'], true);
                } else {
                    $cim = new AuthnetCIM($authorize_sender_info['api_key'], $authorize_sender_info['trans_key']);
                }
                $cim->setParameter('customerProfileId', $dealuser['User']['cim_profile_id']);
                $cim->setParameter('customerPaymentProfileId', $dealuser['DealUser']['payment_profile_id']);
                $cim->setParameter('transId', $dealuser['DealUser']['cim_transaction_id']);
                $cim->voidCustomerProfileTransaction();
                // And if enbaled n Credit docapture amount and deal discount amount is not equal, add amount to user wallet and update transactions //
                if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
                    if ($dealuser['AuthorizenetDocaptureLog']['authorize_amt'] != $dealuser['DealUser']['discount_amount']) {
                        $update_wallet = '';
                        $update_wallet = ($dealuser['Deal']['discounted_price']*$dealuser['DealUser']['quantity']) -$dealuser['AuthorizenetDocaptureLog']['orginal_amount'];
						if($update_wallet!=0){
                        //Updating transactions
							$data = array();
							$data['Transaction']['user_id'] = $dealuser['DealUser']['user_id'];
							$data['Transaction']['foreign_id'] = ConstUserIds::Admin;
							$data['Transaction']['class'] = 'SecondUser';
							$data['Transaction']['amount'] = $update_wallet;
							$data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
							$transaction_id = $this->User->Transaction->log($data);
							if (!empty($transaction_id)) {
								$this->User->updateAll(array(
									'User.available_balance_amount' => 'User.available_balance_amount +' . $update_wallet
								) , array(
									'User.id' => $dealuser['DealUser']['user_id']
								));
							}
						}
                    }
                } // END  act like groupon wallet funct., //
                if ($cim->isSuccessful()) {
                    if (!empty($dealuser['AuthorizenetDocaptureLog']['id'])) {
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['id'] = $dealuser['AuthorizenetDocaptureLog']['id'];
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['payment_status'] = 'Cancelled';
                        $this->DealUser->AuthorizenetDocaptureLog->save($data_authorize_docapture_log);
                    }
                    return true;
                } else {
                    $response['message'] = $cim->getResponse();
                    return $response;
                }
            } else {
                $payment_response = array();
                if ($dealuser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::CreditCard) {
                    $post_info['authorization_id'] = $dealuser['PaypalDocaptureLog']['authorizationid'];
                } else if ($dealuser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::PayPalAuth) {
                    $post_info['authorization_id'] = $dealuser['PaypalTransactionLog']['authorization_auth_id'];
				} else if ($dealuser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::ExpressCheckout) {
					$post_info['authorization_id'] = $dealuser['ExpresscheckoutTransactionLog']['transaction_id'];
                }
                $post_info['note'] = __l('Deal Payment refund');
                //call void function in paypal component
				if ($dealuser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::ExpressCheckout) {
					$payment_response = $this->Paypal->doVoid($post_info, $paypal_expresscheckout_sender_info);
				} else {
					$payment_response = $this->Paypal->doVoid($post_info, $paypal_sender_info);
				}
                //update payment responses
                if (!empty($payment_response)) {
                    if ($dealuser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::CreditCard) {
                        $data_paypal_docapture_log['PaypalDocaptureLog']['id'] = $dealuser['PaypalDocaptureLog']['id'];
                        foreach($payment_response as $key => $value) {
                            $data_paypal_docapture_log['PaypalDocaptureLog']['dovoid_' . strtolower($key) ] = $value;
                        }
                        $data_paypal_docapture_log['PaypalDocaptureLog']['dovoid_response'] = serialize($payment_response);
                        $data_paypal_docapture_log['PaypalDocaptureLog']['payment_status'] = 'Cancelled';
                        //update PaypalDocaptureLog table
                        $this->DealUser->PaypalDocaptureLog->save($data_paypal_docapture_log);
                        // And if enbaled n Credit docapture amount and deal discount amount is not equal, add amount to user wallet and update transactions //
                        if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
                            if ($data_paypal_docapture_log['PaypalDocaptureLog']['original_amount'] != $dealuser['DealUser']['discount_amount']) {
                                $update_wallet = '';
                                $update_wallet = ($dealuser['Deal']['discounted_price']*$dealuser['DealUser']['quantity']) -$dealuser['PaypalDocaptureLog']['dodirectpayment_amt'];
                                //Updating transactions
                                $data = array();
                                $data['Transaction']['user_id'] = $dealuser['DealUser']['user_id'];
                                $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
                                $data['Transaction']['class'] = 'SecondUser';
                                $data['Transaction']['amount'] = $update_wallet;
                                $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
                                $transaction_id = $this->User->Transaction->log($data);
                                if (!empty($transaction_id)) {
                                    $this->User->updateAll(array(
                                        'User.available_balance_amount' => 'User.available_balance_amount +' . $update_wallet
                                    ) , array(
                                        'User.id' => $dealuser['DealUser']['user_id']
                                    ));
                                }
                            }
                        } // END  act like groupon wallet funct., //

                    } else if ($dealuser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::PayPalAuth) {
                        $data_paypal_capture_log['PaypalTransactionLog']['id'] = $dealuser['PaypalTransactionLog']['id'];
                        foreach($payment_response as $key => $value) {
                            $data_paypal_capture_log['PaypalTransactionLog']['void_' . strtolower($key) ] = $value;
                        }
                        $data_paypal_capture_log['PaypalTransactionLog']['void_data'] = serialize($payment_response);
                        $data_paypal_capture_log['PaypalTransactionLog']['payment_status'] = 'Cancelled';
                        $data_paypal_capture_log['PaypalTransactionLog']['error_no'] = '0';
                        //update PaypalTransactionLog table
                        $this->DealUser->PaypalTransactionLog->save($data_paypal_capture_log);
                        // And if enabled n PayPal docapture amount and deal discount amount is not equal, add amount to user wallet and update transactions //
                        if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
                            if ($dealuser['PaypalTransactionLog']['orginal_amount'] != $dealuser['DealUser']['discount_amount']) {
                                $update_wallet = '';
                                $update_wallet = ($dealuser['Deal']['discounted_price']*$dealuser['DealUser']['quantity']) -$dealuser['PaypalTransactionLog']['authorization_auth_amount'];
                                //Updating transactions
                                $data = array();
                                $data['Transaction']['user_id'] = $dealuser['DealUser']['user_id'];
                                $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
                                $data['Transaction']['class'] = 'SecondUser';
                                $data['Transaction']['amount'] = $update_wallet;
                                $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
                                $transaction_id = $this->User->Transaction->log($data);
                                if (!empty($transaction_id)) {
                                    $this->User->updateAll(array(
                                        'User.available_balance_amount' => 'User.available_balance_amount +' . $update_wallet
                                    ) , array(
                                        'User.id' => $dealuser['DealUser']['user_id']
                                    ));
                                }
                            }
                        } // END  act like groupon wallet funct., //
					} else if ($dealuser['DealUser']['payment_gateway_id'] == ConstPaymentGateways::ExpressCheckout) {
						$data_expresscheckout_void_log = array();
						$data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['id'] = $dealuser['ExpresscheckoutTransactionLog']['id'];
						$data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['dovoid_payment_status'] = 'Cancelled';
						$data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['error_no'] = '0';
						$data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['dovoid_authorization_id'] = $payment_response['AUTHORIZATIONID'];
						$data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['dovoid_timestamp'] = $payment_response['TIMESTAMP'];
						$data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['dovoid_correlationid'] = $payment_response['CORRELATIONID'];
						$data_expresscheckout_void_log['ExpresscheckoutTransactionLog']['dovoid_build'] = $payment_response['BUILD'];
						$this->DealUser->ExpresscheckoutTransactionLog->save($data_expresscheckout_void_log);
						if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
							if ($dealuser['ExpresscheckoutTransactionLog']['orginal_amount'] != $dealuser['DealUser']['discount_amount']) {
								$update_wallet = '';
								$update_wallet = ($dealuser['Deal']['discounted_price']*$dealuser['quantity']) -$dealuser['ExpresscheckoutTransactionLog']['mc_gross'];
								//Updating transactions
                                $data = array();
                                $data['Transaction']['user_id'] = $dealuser['DealUser']['user_id'];
                                $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
                                $data['Transaction']['class'] = 'SecondUser';
                                $data['Transaction']['amount'] = $update_wallet;
                                $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
                                $transaction_id = $this->User->Transaction->log($data);
                                if (!empty($transaction_id)) {
                                    $this->User->updateAll(array(
                                        'User.available_balance_amount' => 'User.available_balance_amount +' . $update_wallet
                                    ) , array(
                                        'User.id' => $dealuser['DealUser']['user_id']
                                    ));
                                }
							}
						} // END  act like groupon wallet funct., //
                    }
                }
            }
        } else {
            $transaction['Transaction']['user_id'] = $dealuser['DealUser']['user_id'];
            $transaction['Transaction']['foreign_id'] = $dealuser['DealUser']['id'];
            $transaction['Transaction']['class'] = 'DealUser';
            $transaction['Transaction']['amount'] = $dealuser['DealUser']['discount_amount'];
            $transaction['Transaction']['transaction_type_id'] = (!empty($dealuser['DealUser']['is_gift'])) ? ConstTransactionTypes::DealGiftCancel : ConstTransactionTypes::DealBoughtCancel;
            $this->DealUser->User->Transaction->log($transaction);
            //update user balance
            $this->DealUser->User->updateAll(array(
                'User.available_balance_amount' => 'User.available_balance_amount +' . $dealuser['DealUser']['discount_amount'],
            ) , array(
                'User.id' => $dealuser['DealUser']['user_id']
            ));
        }
        return true;
    }
    function _updateDealBitlyURL($deal_slug, $city = '', $city_id, $deal_id)
    {
        $city = !empty($city) ? $city : Configure::read('site.city');
        $domain = explode('http://', Router::url('/', true));
        $domain = count($domain) ? $domain[1] : $domain;
        $bitly_short_url_subdomain = 'http://' . $city . '.' . $domain . 'deal/' . $deal_slug;
        $bitly_short_url_prefix = Router::url('/', true) . $city . '/deal/' . $deal_slug;
        $bitly_short_url_subdomain = $this->getBitlyUrl($bitly_short_url_subdomain);
        $bitly_short_url_prefix = $this->getBitlyUrl($bitly_short_url_prefix);
        $this->CitiesDeal->updateAll(array(
            'CitiesDeal.bitly_short_url_subdomain' => '\'' . $bitly_short_url_subdomain . '\'',
            'CitiesDeal.bitly_short_url_prefix' => '\'' . $bitly_short_url_prefix . '\''
        ) , array(
            'CitiesDeal.city_id' => $city_id,
            'CitiesDeal.deal_id' => $deal_id,
        ));
        $bitly_short_url['subdomain'] = $bitly_short_url_subdomain;
        $bitly_short_url['prefix'] = $bitly_short_url_prefix;
        return $bitly_short_url;
    }
    //to get bitly url
    function getBitlyUrl($url = null)
    {
        if (is_null($url)) {
            return false;
        }
        $curl_uri = 'http://api.bit.ly/shorten?version=2.0.1&longUrl=' . $url . '&login=' . Configure::read('bitly.username') . '&apiKey=' . Configure::read('bitly.api_key');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $curl_uri);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $ret = curl_exec($curl);
        $result = json_decode($ret, true);
        if ($result['errorCode'] == '0' && $result['statusCode'] == 'OK') {
            return $result['results'][$url]['shortUrl'];
        } else {
            return $url;
        }
    }
    // quick fix for to update transaction for paid users
    function _updateTransaction($dealUser)
    {
        //add amount to wallet
        $data['Transaction']['user_id'] = $dealUser['user_id'];
        $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
        $data['Transaction']['class'] = 'SecondUser';
        $data['Transaction']['amount'] = $dealUser['discount_amount'];
        $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
        $data['Transaction']['payment_gateway_id'] = $dealUser['payment_gateway_id'];
        $transaction_id = $this->User->Transaction->log($data);
        if (!empty($transaction_id)) {
            $this->User->updateAll(array(
                'User.available_balance_amount' => 'User.available_balance_amount +' . $dealUser['discount_amount']
            ) , array(
                'User.id' => $dealUser['user_id']
            ));
        }
        //Buy deal transaction
        $transaction['Transaction']['user_id'] = $dealUser['user_id'];
        $transaction['Transaction']['foreign_id'] = $dealUser['id'];
        $transaction['Transaction']['class'] = 'DealUser';
        $transaction['Transaction']['amount'] = $dealUser['discount_amount'];
        $transaction['Transaction']['transaction_type_id'] = (!empty($dealUser['is_gift'])) ? ConstTransactionTypes::DealGift : ConstTransactionTypes::BuyDeal;
        $transaction['Transaction']['payment_gateway_id'] = $dealUser['payment_gateway_id'];
        if (!empty($dealUser['rate'])) {
            $transaction['Transaction']['currency_id'] = $dealUser['currency_id'];
            $transaction['Transaction']['converted_currency_id'] = $dealUser['converted_currency_id'];
            $transaction['Transaction']['converted_amount'] = $dealUser['authorize_amt'];
            $transaction['Transaction']['rate'] = $dealUser['rate'];
        }
        $this->User->Transaction->log($transaction);
        //user update
        $this->User->updateAll(array(
            'User.available_balance_amount' => 'User.available_balance_amount -' . $dealUser['discount_amount']
        ) , array(
            'User.id' => $dealUser['user_id']
        ));
    }
    function _updateCityDealCount()
    {
        // Take city_deal_count
        $city_deal_counts = $this->City->find('all', array(
            'fields' => array(
                'City.id',
            ) ,
            'contain' => array(
                'Deal' => array(
                    'conditions' => array(
                        'Deal.deal_status_id' => array(
                            ConstDealStatus::Open,
                            ConstDealStatus::Tipped
                        ) ,
                        'Deal.is_now_deal' => 0,
                    ) ,
                    'fields' => array(
                        'Deal.id'
                    )
                )
            ) ,
            'recursive' => 1
        ));
        $this->City->updateAll(array(
            'City.active_deal_count' => 0
        ) , array(
            'City.is_approved' => array(
                0,
                1
            )
        ));
        foreach($city_deal_counts as $city_deal_count) {
            if (!empty($city_deal_count['Deal'])) {
                if ($count = count($city_deal_count['Deal'])) {
                    $this->City->updateAll(array(
                        'City.active_deal_count' => $count
                    ) , array(
                        'City.id' => $city_deal_count['City']['id']
                    ));
                }
            }
        }
        // delete view more cities cache files
        $this->City->deleteAllCache();
    }


	function _updateCategoryDealCount()
    {
		
        // Take city_deal_count
        $category_deal_counts = $this->DealCategory->find('all', array(
            'fields' => array(
                'DealCategory.id',
            ) ,
            'contain' => array(
                'Deal' => array(
                    'conditions' => array(
                        'Deal.deal_status_id' => array(
                            ConstDealStatus::Open,
                            ConstDealStatus::Tipped
                        ) ,
                        'Deal.is_now_deal' => 0,
                    ) ,
                    'fields' => array(
                        'Deal.id'
                    )
                )
            ) ,
            'recursive' => 1
        ));					
        $this->DealCategory->updateAll(array(
            'DealCategory.deal_count' => 0
        ));
        foreach($category_deal_counts as $category_deal_count) {
            if (!empty($category_deal_count['Deal'])) {
                if ($count = count($category_deal_count['Deal'])) {					
                    $this->DealCategory->updateAll(array(
                        'DealCategory.deal_count' => $count
                    ) , array(
                        'DealCategory.id' => $category_deal_count['DealCategory']['id']
                    ));
                }
            }
        }
        // delete view more cities cache files
        $this->City->deleteAllCache();
    }
    // <-- For iPhone App code
    function saveiPhoneAppThumb($attachments)
    {
        $options[] = array(
            'dimension' => 'iphone_big_thumb',
            'class' => '',
            'alt' => '',
            'title' => '',
            'type' => 'jpg'
        );
        $options[] = array(
            'dimension' => 'iphone_small_thumb',
            'class' => '',
            'alt' => '',
            'title' => '',
            'type' => 'jpg'
        );
        $model = 'Deal';
        $attachment = $attachments[0];
        foreach($options as $option) {
            $destination = APP . 'webroot' . DS . 'img' . DS . $option['dimension'] . DS . $model . DS . $attachment['id'] . '.' . md5(Configure::read('Security.salt') . $model . $attachment['id'] . $option['type'] . $option['dimension'] . Configure::read('site.name')) . '.' . $option['type'];
            if (!file_exists($destination) && !empty($attachment['id'])) {
                $url = $this->getImageUrl($model, $attachment, $option);
                getimagesize($url);
            }
        }
    }
    // For iPhone App code -->
    // - X Referral Methiod //
    function referalRefunding($deal_id = null, $last_inserted_id = null)
    {
        App::import('Model', 'DealUser');
        $this->DealUser = new DealUser();
        $deal_users = $this->DealUser->find('all', array(
            'conditions' => array(
                'Deal.id' => $deal_id,
                'Deal.deal_status_id' => ConstDealStatus::Tipped,
                'DealUser.referred_by_user_id !=' => '0'
            ) ,
            'fields' => array(
                'DealUser.referred_by_user_id',
                'DealUser.deal_id',
                'DealUser.sub_deal_id',
                'SUM(DealUser.quantity) as referred_user_total_purchased_quantity',
            ) ,
            'group' => array(
                'DealUser.referred_by_user_id'
            ) ,
            'contain' => array(
                'Deal' => array(
                    'fields' => array(
                        'Deal.id',
                        'Deal.discounted_price',
                        'Deal.is_subdeal_available',
                    )
                ) ,
            ) ,
            'recursive' => 1
        ));
        foreach($deal_users as $deal_user) {
            if ($deal_user[0]['referred_user_total_purchased_quantity'] >= Configure::read('referral.no_of_refer_to_get_a_refund')) { // If creteria matches for refund
                $check_refer = $this->DealReferrer->find('first', array(
                    'conditions' => array(
                        'DealReferrer.user_id' => $deal_user['DealUser']['referred_by_user_id'],
                        'DealReferrer.deal_id' => $deal_user['Deal']['id'],
                    ) ,
                    'recursive' => -1
                ));
                if (empty($check_refer)) { // If empty, refer amount
                    // Add amount to referral user amount //
                    if (Configure::read('referral.refund_type') == ConstReferralRefundType::RefundDealAmount) {
                        if (!empty($deal_user['Deal']['is_subdeal_available'])) {
                            $get_sub_deal_info = $this->find('first', array(
                                'conditions' => array(
                                    'Deal.id' => $deal_user['DealUser']['sub_deal_id']
                                ) ,
                                'fields' => array(
                                    'Deal.id',
                                    'Deal.discounted_price',
                                ) ,
                                'recursive' => -1
                            ));
                            $refund_amount = $get_sub_deal_info['Deal']['discounted_price'];
                        } else {
                            $refund_amount = $deal_user['Deal']['discounted_price'];
                        }
                    } else {
                        $refund_amount = Configure::read('referral.refund_amount');
                    }
                    $this->User->updateAll(array(
                        'User.available_balance_amount' => 'User.available_balance_amount + ' . $refund_amount,
                        'User.total_referral_earned_amount' => 'User.total_referral_earned_amount + ' . $refund_amount,
                    ) , array(
                        'User.id' => $deal_user['DealUser']['referred_by_user_id']
                    ));
                    $this->User->DealUser->updateAll(array(
                        'DealUser.referral_commission_amount ' => $refund_amount,
                        'DealUser.is_referral_commission_sent ' => 1,
                        'DealUser.referral_commission_type ' => ConstReferralCommissionType::XRefer
                    ) , array(
                        'DealUser.id' => $last_inserted_id
                    ));
                    $data = array();
                    $data['Transaction']['user_id'] = $deal_user['DealUser']['referred_by_user_id'];
                    $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
                    $data['Transaction']['class'] = 'SecondUser';
                    $data['Transaction']['amount'] = $refund_amount;
                    $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::ReferralAmount;
                    $this->User->Transaction->save($data);
                    // Insert Record into DealReferrrer //
                    $referrer = array();
                    $referrer['DealReferrer']['user_id'] = $deal_user['DealUser']['referred_by_user_id'];
                    $referrer['DealReferrer']['deal_id'] = $deal_user['Deal']['id'];
                    $referrer['DealReferrer']['earned_amount'] = $refund_amount;
                    $referrer['DealReferrer']['refferral_count'] = Configure::read('referral.no_of_refer_to_get_a_refund');
                    $this->DealReferrer->save($referrer);
                } else {
                    $this->DealReferrer->updateAll(array(
                        'DealReferrer.total_purchased_referral_count' => 'DealReferrer.total_purchased_referral_count + ' . 1,
                    ) , array(
                        'DealReferrer.deal_id' => $deal_user['Deal']['id'],
                        'DealReferrer.id' => $check_refer['DealReferrer']['id']
                    ));
                }
            }
        }
    }
    function _getDealInfo($deal_id = null)
    {
        $deal = $this->find('first', array(
            'conditions' => array(
                'Deal.id' => $deal_id
            ) ,
            'fields' => array(
                'Deal.id',
                'Deal.parent_id',
                'Deal.name',
                'Deal.slug',
                'Deal.deal_status_id',
                'Deal.is_subdeal_available',
                'Deal.charity_id',
                'Deal.charity_percentage',
                'Deal.end_date',
                'Deal.is_anytime_deal',
                'Deal.is_now_deal',
                'Deal.is_hold',
                'Deal.company_id',
                'Charity.name',
                'Charity.url',
            ) ,
            'recursive' => 0
        ));
        return $deal;
    }
    function _findCompanyCities($company_id, $is_main, $is_branch, $branch_ids = array())
    {
        $city_array = array();
        if (!empty($is_main)) {
            $company = $this->Company->find('first', array(
                'conditions' => array(
                    'Company.id' => $company_id
                ) ,
                'recursive' => -1
            ));
            $city_array[] = $company['Company']['city_id'];
        }
        if (!empty($is_branch)) {
            $company_addresses = $this->Company->CompanyAddress->find('all', array(
                'conditions' => array(
                    'CompanyAddress.company_id' => $company_id
                ) ,
                'recursive' => -1
            ));
            if (!empty($company_addresses)) {
                foreach($company_addresses As $company_address) {
                    $city_array[] = $company_address['CompanyAddress']['city_id'];
                }
            }
        } elseif (!empty($branch_ids)) {
            $company_addresses = $this->Company->CompanyAddress->find('all', array(
                'conditions' => array(
                    'CompanyAddress.id' => $branch_ids['company_address_id']
                ) ,
                'recursive' => -1
            ));
            if (!empty($company_addresses)) {
                foreach($company_addresses As $company_address) {
                    $city_array[] = $company_address['CompanyAddress']['city_id'];
                }
            }
        }
        return $city_array;
    }
    function cron_now_deals($open_deal_id = array())
    {
        $today = date('w') +1;
        $deals = $this->now_deal_ids($is_tomorrow = 0, $today, '', $open_deal_id); //get now deals ids for today date
        if ($today == 7) {
            $tomorrow = 1;
        } else {
            $tomorrow = $today+1;
        }
        $deals_tomorrow = $this->now_deal_ids($is_tomorrow = 1, $tomorrow, $deals); //get now deals ids for tomorrow date

    }
    function now_deal_ids($is_tomorrow, $today, $deals = array() , $open_deal_id = array()) //get deals ids

    {
        App::import('Model', 'DealsRepeatDate');
        $this->DealsRepeatDate = new DealsRepeatDate();
        $conditions = array();
        $conditions['DealsRepeatDate.repeat_date_id'] = $today;
        if (!empty($open_deal_id)) {
            $conditions['DealsRepeatDate.deal_id'] = $open_deal_id;
        }
        $DealsRepeatDate = $this->DealsRepeatDate->find('list', array(
            'conditions' => $conditions,
            'fields' => array(
                'DealsRepeatDate.deal_id',
                'DealsRepeatDate.deal_id',
            ) ,
            'recursive' => -1
        ));
        $deals = $this->get_now_deal($is_tomorrow, $DealsRepeatDate, $deals);
        return $deals;
    }
    function get_now_deal($is_tomorrow, $deal_ids = array() , $today_ids = array())
    {
        $conditions = array();
        $conditions['OR'] = array(
            'AND' => array(
                'Deal.is_anytime_deal = ' => 1,
                'Deal.end_date = ' => NULL,
            ) ,
            'Deal.is_anytime_deal != ' => 1,
            'Deal.end_date != ' => NULL,
        );
        if (!empty($today_ids)) {
            $conditions['NOT']['Deal.id'] = $today_ids;
        }
        $conditions['Deal.is_now_deal'] = 1;
        $conditions['Deal.parent_id'] = NULL;
        //$conditions['NOT']['Deal.deal_repeat_type_id'] = 1;
        $conditions['OR'] = array(
            'Deal.id' => $deal_ids,
            'Deal.deal_repeat_type_id' => 1
        );
        $conditions['Deal.deal_status_id'] = array(
            ConstDealStatus::Open,
            ConstDealStatus::Tipped,
        );
        $isNowDealsIds = $this->find('all', array(
            'conditions' => $conditions,
            'fields' => array(
                'Deal.id',
                'Deal.deal_repeat_type_id',
            ) ,
            'recursive' => -1
        ));
        if (!empty($isNowDealsIds)) {
            $temp = array();
            foreach($isNowDealsIds as $key => $isNowDealId) {
                $check_deal = $this->check_nowdeal_exists($isNowDealId['Deal']['id'], $isNowDealId['Deal']['deal_repeat_type_id']);
                if (!$check_deal) {
                    $this->CreateLiveSubDeal($isNowDealId['Deal']['id'], $is_tomorrow); // create live sub deal
                    $temp[] = $isNowDealId['Deal']['id'];
                }
            }
            return $temp;
        }
    }
    function CreateLiveSubDeal($id, $is_tomorrow)
    {
        $isNowDeal = $this->find('first', array(
            'conditions' => array(
                'Deal.id' => $id,
            ) ,
            'recursive' => -1
        ));
        $this->create();
        if (!empty($isNowDeal)) {
            $subdeal['Deal'] = $isNowDeal['Deal']; //Assign main deal value to sub deal
            $subdeal['Deal']['parent_id'] = $isNowDeal['Deal']['id'];
            $subdeal['Deal']['deal_status_id'] = ConstDealStatus::SubDeal;
            unset($subdeal['Deal']['id']);
            unset($subdeal['Deal']['created']);
            unset($subdeal['Deal']['modified']);
            unset($subdeal['Deal']['slug']);
            unset($subdeal['Deal']['deal_tipped_time']);
            unset($subdeal['Deal']['deal_user_count']);
            unset($subdeal['Deal']['payment_failed_count']);
            unset($subdeal['Deal']['sub_deal_count']);
            if ($isNowDeal['Deal']['is_anytime_deal']) {
                unset($subdeal['Deal']['end_date']);
            }
            $subdeal['Deal']['is_anytime_deal'] = 0;
            $isNowDeal['Deal']['coupon_start_date'] = _formatDate('Y-m-d H:i:s', strtotime($isNowDeal['Deal']['coupon_start_date']));
            $coupon_start_date_detail = explode(" ", $isNowDeal['Deal']['coupon_start_date']);
            $coupon_start_time_details = $coupon_start_date_detail[1];
            $coupon_start_time_detail = explode(":", $coupon_start_time_details);
            $isNowDeal['Deal']['coupon_expiry_date'] = _formatDate('Y-m-d H:i:s', strtotime($isNowDeal['Deal']['coupon_expiry_date']));
            $coupon_expiry_date_detail = explode(" ", $isNowDeal['Deal']['coupon_expiry_date']);
            $coupon_expiry_time_details = $coupon_expiry_date_detail[1];
            $coupon_expiry_time_detail = explode(":", $coupon_expiry_time_details);
            //strftime(Configure::read('site.datetime.format') , strtotime(_formatDate('Y-m-d H:i:s', strtotime($deal['Deal']['coupon_expiry_date'])))) ,
            $start_date = date("Y-m-d H:i:s");
            if ($is_tomorrow) {
                $coupon_start_time = date("Y-m-d H:i:s", mktime($coupon_start_time_detail[0], $coupon_start_time_detail[1], $coupon_start_time_detail[2], date("m") , date("d") +1, date("Y")));
                $coupon_expiry_time = date("Y-m-d H:i:s", mktime($coupon_expiry_time_detail[0], $coupon_expiry_time_detail[1], $coupon_expiry_time_detail[2], date("m") , date("d") +1, date("Y")));
                $end_date = date("Y-m-d H:i:s", mktime($coupon_expiry_time_detail[0], $coupon_expiry_time_detail[1], $coupon_expiry_time_detail[2], date("m") , date("d") +1, date("Y")));
            } else {
                $coupon_start_time = date("Y-m-d H:i:s", mktime($coupon_start_time_detail[0], $coupon_start_time_detail[1], $coupon_start_time_detail[2], date("m") , date("d") , date("Y")));
                $coupon_expiry_time = date("Y-m-d H:i:s", mktime($coupon_expiry_time_detail[0], $coupon_expiry_time_detail[1], $coupon_expiry_time_detail[2], date("m") , date("d") , date("Y")));
                $end_date = date("Y-m-d H:i:s", mktime($coupon_expiry_time_detail[0], $coupon_expiry_time_detail[1], $coupon_expiry_time_detail[2], date("m") , date("d") , date("Y")));
            }
            $subdeal['Deal']['start_date'] = $start_date;
            $subdeal['Deal']['coupon_start_date'] = $coupon_start_time;
            $subdeal['Deal']['coupon_expiry_date'] = $coupon_expiry_time;
            $subdeal['Deal']['end_date'] = $end_date;
            if ($this->save($subdeal, false)) {
                $this->updateAll(array(
                    'Deal.sub_deal_count' => 'Deal.sub_deal_count + 1'
                ) , array(
                    'Deal.id' => $id
                ));
            }
        }
    }
    function check_nowdeal_exists($id, $repeat_type_id = null) //check whether deal already created for today and tomorrow

    {
        $today = _formatDate('Y-m-d H:i:s', date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))) , true);
        $tomorrow = _formatDate('Y-m-d H:i:s', date("Y-m-d H:i:s", mktime(0, 0, -1, date("m") , date("d") +2, date("Y"))) , true);
        $conditions['Deal.is_now_deal'] = 1;
        $conditions['Deal.parent_id'] = $id;
        if ($repeat_type_id != 1 || empty($repeat_type_id)) {
            $conditions['Deal.start_date BETWEEN ? and ?'] = array(
                $today,
                $tomorrow
            );
        }
        $count = $this->find('count', array(
            'conditions' => $conditions,
        ));
        if ($count) {
            return true;
        } else {
            return false;
        }
    }
    function live_deal_status_count_update()
    {
        // Company table live deal status count update
        $conditions = array();
        $conditions['Deal.parent_id'] = NULL;
        $conditions['is_now_deal'] = 1;
        $conditions['is_redeem_in_main_address'] = 1;
        $deals = $this->find('all', array(
            'conditions' => $conditions,
            'fields' => array(
                'count(Deal.deal_status_id ) as deal_status_count',
                'Deal.company_id',
                'Deal.deal_status_id'
            ) ,
            'group' => array(
                'Deal.company_id',
                'Deal.deal_status_id'
            ) ,
            'recursive' => -1
        ));
        $company = array();
        $companyids = array();
        $status = array(
            1 => 'upcoming_count',
            2 => 'open_count',
            3 => 'canceled_count',
            4 => 'expired_count',
            5 => 'tipped_count',
            6 => 'closed_count',
            7 => 'refunded_count',
            8 => 'paid_to_company_count',
            9 => 'pending_approval_count',
            10 => 'rejected_count',
            11 => 'draft_count'
        );
        foreach($deals as $deal) {
            if (!in_array($deal['Deal']['deal_status_id'], array(
                12,
                13,
                14
            ))) {
                $company[$deal['Deal']['company_id']][$status[$deal['Deal']['deal_status_id']]] = $deal[0]['deal_status_count'];
                $companyids[] = $deal['Deal']['company_id'];
            }
        }
        foreach($companyids as $companyid) {
            foreach($status as $ids => $stat) {
                if (!isset($company[$companyid][$status[$ids]])) $company[$companyid][$status[$ids]] = 0;
            }
        }
        // update company table
        foreach($company as $key => $value) {
            $this->Company->updateAll($value, array(
                'Company.id' => $key
            ));
        }
        // CompanyAddress table live deal status count update
        $company_address = array();
        $conditions = array();
        $conditions['Deal.parent_id'] = NULL;
        $conditions['Deal.is_now_deal'] = 1;
        $company_deals = $this->Company->CompanyAddress->find('all', array(
            //'conditions' => $conditions,
            'fields' => array(
                'CompanyAddress.id'
            ) ,
            'contain' => array(
                'Deal' => array(
                    'conditions' => $conditions,
                    'fields' => array(
                        'Deal.id',
                        'Deal.deal_status_id'
                    ) ,
                )
            ) ,
            'recursive' => 2
        ));
        foreach($company_deals as $company) {
            if (!empty($company['Deal'])) {
                $status_count = array(
                    1 => 0,
                    2 => 0,
                    3 => 0,
                    4 => 0,
                    5 => 0,
                    6 => 0,
                    7 => 0,
                    8 => 0,
                    9 => 0,
                    10 => 0,
                    11 => 0
                );
                foreach($company['Deal'] as $deal) {
                    if (!in_array($deal['deal_status_id'], array(
                        12,
                        13,
                        14
                    ))) {
                        $status_count[$deal['deal_status_id']] = $status_count[$deal['deal_status_id']]+1;
                    }
                }
                foreach($status_count as $key => $count) {
                    $company_address[$company['CompanyAddress']['id']][$status[$key]] = $count;
                }
            }
        }
        // update company table
        foreach($company_address as $key => $value) {
            $this->Company->CompanyAddress->updateAll($value, array(
                'CompanyAddress.id' => $key
            ));
        }
    }
    function _postInFacebook($data)
    {
        try {
            $this->facebook->api('/' . (!empty($data['fb_user_id']) ? $data['fb_user_id'] : Configure::read('facebook.page_id')) . '/feed', 'POST', array(
                'access_token' => (!empty($data['fb_access_token']) ? $data['fb_access_token'] : Configure::read('facebook.fb_access_token')) ,
                'message' => $data['message'],
                'picture' => $data['image_url'],
                'icon' => $data['image_url'],
                'link' => $data['url'],
                'caption' => Router::url('/', true) ,
                'description' => strip_tags($data['description'])
            ));
        }
        catch(Exception $e) {
            $this->log('Post on facebook error');
        }
    }
    function _postInTwitter($data)
    {
        $xml = $this->OauthConsumer->post('Twitter', (!empty($data['twitter_access_token']) ? $data['twitter_access_token'] : Configure::read('twitter.site_user_access_token')) , (!empty($data['twitter_access_key']) ? $data['twitter_access_key'] : Configure::read('twitter.site_user_access_key')) , 'http://api.twitter.com/1/statuses/update.json', array(
            'status' => $data['message']
        ));
    }
    function _purchased_via()
    {
        $purchased = ConstPurchasedVia::Web;
        if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false) {
            $purchased = ConstPurchasedVia::iPhone;
        }
        if (stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
            $purchased = ConstPurchasedVia::Android;
        }
        return $purchased;
    }
    function _updateAfterPurchase($data, $type = null)
    {
        // Updating Purchase [USER] Related 'AfterSave' Fields //
        $this->_updateUserAfterPurchase($data, $type);
        // Updating Purchase [DEAL] Related 'AfterSave' Fields //
        $this->_updateDealAfterPurchase($data, $type);
        // Updating Purchase [DEAL] Related 'AfterSave' Counts //
        $this->DealUser->_updateDealAfterPurchaseCount($data, $type);
        // Updating Purchase [COMPANY] Related 'AfterSave' Counts //
        $this->_updateCompanyAfterPurchase($data, $type);
    }
    // Updating Purchase [COMPANY] Related 'AfterSave' Counts //
    function _updateCompanyAfterPurchase($data, $type = null)
    {
        $updation = array();
        $total_sales = $this->find('all', array(
            'conditions' => array(
                'Deal.company_id' => $data['company_id'],
                'Deal.deal_status_id' => ConstDealStatus::PaidToCompany,
            ) ,
            'fields' => array(
                'SUM(Deal.total_sales_lost_amount) as total_sales_lost_amount',
                'SUM(Deal.seller_charity_amount) as seller_charity_amount',
                'SUM(Deal.total_commission_amount) as total_site_revenue_amount',
                'SUM(Deal.total_purchased_amount - (Deal.total_commission_amount + Deal.seller_charity_amount)) as total_sales_cleared_amount'
            ) ,
            'recursive' => -1
        ));
        $updation['Company.total_sales_lost_amount'] = (!empty($total_sales[0][0]['total_sales_lost_amount']) ? $total_sales[0][0]['total_sales_lost_amount'] : '0');
        $updation['Company.total_paid_for_charity_amount'] = (!empty($total_sales[0][0]['seller_charity_amount']) ? $total_sales[0][0]['seller_charity_amount'] : '0');
        $updation['Company.total_site_revenue_amount'] = (!empty($total_sales[0][0]['total_site_revenue_amount']) ? $total_sales[0][0]['total_site_revenue_amount'] : '0');
        $updation['Company.total_sales_cleared_amount'] = (!empty($total_sales[0][0]['total_sales_cleared_amount']) ? $total_sales[0][0]['total_sales_cleared_amount'] : '0');
        $total_sales_pipeline_amount = $this->find('all', array(
            'conditions' => array(
                'Deal.company_id' => $data['company_id'],
                'Deal.deal_status_id' => array(
                    ConstDealStatus::Open,
                    ConstDealStatus::Tipped,
                    ConstDealStatus::Closed,
                )
            ) ,
            'fields' => array(
                'SUM(Deal.discounted_price * Deal.deal_user_count) as total_sales_pipeline_amount',
            ) ,
            'recursive' => -1
        ));
        $updation['Company.total_sales_pipeline_amount'] = (!empty($total_sales_pipeline_amount[0][0]['total_sales_pipeline_amount']) ? $total_sales_pipeline_amount[0][0]['total_sales_pipeline_amount'] : '0');
        /*if($type == 'cleared'){
        $total_sales_cleared_amount = $this->find('all', array(
        'conditions' => array(
        'Deal.company_id' => $data['company_id'],
        'Deal.deal_status_id' => ConstDealStatus::PaidToCompany,
        ),
        'fields' => array(
        'SUM(Deal.total_purchased_amount - (Deal.total_commission_amount + Deal.seller_charity_amount)) as total_sales_cleared_amount'
        ),
        'recursive' => -1
        ));
        $updation['Company.total_sales_cleared_amount'] = (!empty($total_sales_cleared_amount[0][0]['total_sales_cleared_amount']) ? $total_sales_cleared_amount[0][0]['total_sales_cleared_amount'] : '0');
        }*/
        // Updating //
        $update_model = array(
            'Company.id' => $data['company_id']
        );
        if (!empty($updation) && !empty($update_model)) {
            $this->Company->updateAll($updation, $update_model);
        }
    }
    // Updating Purchase [DEAL] Related 'AfterSave' Counts //
    // Updating Purchase [DEAL] Related 'AfterSave' Fields //
    function _updateDealAfterPurchase($data, $type = null)
    {
        $conditions = $deal_user_conditions = $updation = array();
        if (!empty($data['sub_deal_id'])) {
            $conditions['Deal.parent_id'] = $data['deal_id'];
        } else {
            $conditions['Deal.id'] = $data['deal_id'];
        }
        if (!empty($data['sub_deal_id'])) {
            $this->_updateSubDealAfterPurchase($data, $type = null);
        }
        // Getting Purchased & Commission Amounts //
        $deals = $this->find('all', array(
            'conditions' => $conditions,
            'fields' => array(
                'SUM(Deal.discounted_price * Deal.deal_user_count) as total_purchased_amount',
                'SUM(Deal.bonus_amount + ((Deal.discounted_price * Deal.deal_user_count) * (Deal.commission_percentage/100)))as total_commission_amount',
            ) ,
            'recursive' => -1
        ));
        //($deal['Deal']['total_purchased_amount'] - ($deal['Deal']['total_commission_amount'] + $deal['Deal']['seller_charity_amount']));
        // Getting Charity //
        $deal_user_conditions = array(
            'DealUser.is_repaid' => 0,
            'DealUser.is_canceled' => 0
        );
        $deal_user_conditions['DealUser.deal_id'] = $data['deal_id'];
        $deal_users = $this->DealUser->find('all', array(
            'conditions' => $deal_user_conditions,
            'fields' => array(
                'SUM(DealUser.charity_paid_amount) as total_charity_amount',
                'SUM(DealUser.charity_seller_amount) as seller_charity_amount',
                'SUM(DealUser.charity_site_amount) as site_charity_amount',
                'SUM(DealUser.affiliate_commission_amount) as total_affiliate_amount',
                'SUM(DealUser.referral_commission_amount) as total_referral_amount',
                'COUNT(DealUser.id) as deal_user_canceled_count',
            ) ,
            'recursive' => -1,
        ));
        if (!empty($type) && $type == 'cancel') {
            $deal_user_conditions = array();
            $deal_user_conditions['DealUser.deal_id'] = $data['deal_id'];
            $deal_user_conditions['OR'] = array(
                'DealUser.is_repaid' => 1,
                'DealUser.is_canceled' => 1
            );
            $deal_user_canceled = $this->DealUser->find('all', array(
                'conditions' => $deal_user_conditions,
                'fields' => array(
                    'SUM(DealUser.discount_amount) as total_sales_lost_amount',
                ) ,
                'recursive' => -1,
            ));
            $updation['Deal.total_sales_lost_amount'] = (!empty($deal_user_canceled[0][0]['total_sales_lost_amount']) ? $deal_user_canceled[0][0]['total_sales_lost_amount'] : '0.00');
            $updation['Deal.deal_user_canceled_count'] = (!empty($deal_user_canceled[0][0]['deal_user_canceled_count']) ? $deal_user_canceled[0][0]['deal_user_canceled_count'] : '0');
        }
        $update_model = array(
            'Deal.id' => $data['deal_id']
        );
        $updation['Deal.total_purchased_amount'] = (!empty($deals[0][0]['total_purchased_amount']) ? $deals[0][0]['total_purchased_amount'] : '0.00');
        $updation['Deal.total_commission_amount'] = (!empty($deals[0][0]['total_commission_amount']) ? $deals[0][0]['total_commission_amount'] : '0.00');
        $updation['Deal.total_charity_amount'] = (!empty($deal_users[0][0]['total_charity_amount']) ? $deal_users[0][0]['total_charity_amount'] : '0.00');
        $updation['Deal.seller_charity_amount'] = (!empty($deal_users[0][0]['seller_charity_amount']) ? $deal_users[0][0]['seller_charity_amount'] : '0.00');
        $updation['Deal.site_charity_amount'] = (!empty($deal_users[0][0]['site_charity_amount']) ? $deal_users[0][0]['site_charity_amount'] : '0.00');
        $updation['Deal.total_company_earned_amount'] = ($updation['Deal.total_purchased_amount']-($updation['Deal.total_commission_amount']+$updation['Deal.seller_charity_amount']));
        $updation['Deal.total_affiliate_amount'] = (!empty($deal_users[0][0]['total_affiliate_amount']) ? $deal_users[0][0]['total_affiliate_amount'] : '0.00');
        $updation['Deal.total_referral_amount'] = (!empty($deal_users[0][0]['total_referral_amount']) ? $deal_users[0][0]['total_referral_amount'] : '0.00');
        if (!empty($updation) && !empty($update_model)) {
            $this->updateAll($updation, $update_model);
        }
    }
    function _updateSubDealAfterPurchase($data, $type = null)
    {
        $conditions = $updation = array();
        $conditions['Deal.id'] = $data['sub_deal_id'];
        $deals = $this->find('all', array(
            'conditions' => $conditions,
            'fields' => array(
                'SUM(Deal.discounted_price * Deal.deal_user_count) as total_purchased_amount',
                'SUM(Deal.bonus_amount + ((Deal.discounted_price * Deal.deal_user_count) * (Deal.commission_percentage/100)))as total_commission_amount',
            ) ,
            'recursive' => -1
        ));
        $updation['Deal.total_purchased_amount'] = (!empty($deals[0][0]['total_purchased_amount']) ? $deals[0][0]['total_purchased_amount'] : '0.00');
        $updation['Deal.total_commission_amount'] = (!empty($deals[0][0]['total_commission_amount']) ? $deals[0][0]['total_commission_amount'] : '0.00');
        $update_model = array(
            'Deal.id' => $data['sub_deal_id']
        );
        if (!empty($updation) && !empty($update_model)) {
            $this->updateAll($updation, $update_model);
        }
    }
    // Updating Purchase [USER] Related 'AfterSave' Fields //
    function _updateUserAfterPurchase($data, $type = null)
    {
        $conditions = $updation = $group = $extra_fields = array();
        if (!empty($data['user_id'])) {
            $conditions['DealUser.user_id'] = $data['user_id'];
            if (!empty($type) && $type == 'cancel') {
                $conditions['OR'] = array(
                    'DealUser.is_repaid' => 1,
                    'DealUser.is_canceled' => 1
                );
            } else {
                $extra_fields = $group = array(
                    'DealUser.payment_gateway_id'
                );
            }
            $deal_users = $this->DealUser->find('all', array(
                'conditions' => $conditions,
                'fields' => array_merge(array(
                    'SUM(DealUser.discount_amount) as total_purchased_amount',
                    'COUNT(DealUser.quantity) as total_deal_purchase_count'
                ) , $extra_fields) ,
                'group' => $group,
                'recursive' => -1
            ));
            $update_model = array(
                'User.id' => $data['user_id']
            );
            $total_purchashed_amount = $total_purchashed_count = '0';
            // When CANCELED/REFUNDED //
            if (!empty($type) && $type == 'cancel') {
                $updation['User.total_deal_purchase_cancel_count'] = $deal_users[0][0]['total_deal_purchase_count'];
            } else { // ELSE i.e Purchase //
                foreach($deal_users as $deal_user) {
                    $total_purchashed_count+= $deal_user[0]['total_deal_purchase_count'];
                    $total_purchashed_amount+= $deal_user[0]['total_purchased_amount'];
                    if ($deal_user['DealUser']['payment_gateway_id'] == ConstPaymentGateways::Wallet) {
                        $updation['User.total_purchased_amount_via_wallet'] = $deal_user[0]['total_purchased_amount'];
                    }
                    if ($deal_user['DealUser']['payment_gateway_id'] == ConstPaymentGateways::CreditCard) {
                        $updation['User.total_purchased_amount_via_credit_card'] = $deal_user[0]['total_purchased_amount'];
                    }
                    if ($deal_user['DealUser']['payment_gateway_id'] == ConstPaymentGateways::PayPalAuth) {
                        $updation['User.total_purchased_amount_via_paypal'] = $deal_user[0]['total_purchased_amount'];
                    }
                    if ($deal_user['DealUser']['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet) {
                        $updation['User.total_purchased_amount_via_cim'] = $deal_user[0]['total_purchased_amount'];
                    }
                    if ($deal_user['DealUser']['payment_gateway_id'] == ConstPaymentGateways::PagSeguro) {
                        $updation['User.total_purchased_amount_via_pagseguro'] = $deal_user[0]['total_purchased_amount'];
                    }
                }
                $updation['User.total_purchased_amount'] = $total_purchashed_amount;
                $updation['User.total_deal_purchase_count'] = $total_purchashed_count;
            }
        }
        if (!empty($updation) && !empty($update_model)) {
            $this->User->updateAll($updation, $update_model);
        }
    }
}
?>
