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
class Charity extends AppModel
{
    public $name = 'Charity';
    public $displayField = 'name';
    public $actsAs = array(
        'Aggregatable',
        'Sluggable' => array(
            'label' => array(
                'name'
            )
        )
    );
    var $aggregatingFields = array(
        'paid_amount' => array(
            'mode' => 'real',
            'key' => 'charity_id',
            'foreignKey' => 'charity_id',
            'model' => 'CharitiesDealUser',
            'function' => 'SUM(CharitiesDealUser.amount)',
            'conditions' => array(
                'Deal.deal_status_id' => array(
                    ConstDealStatus::PaidToCompany
                )
            )
        )
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'CharityCategory' => array(
            'className' => 'CharityCategory',
            'foreignKey' => 'charity_category_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        )
    );
    public $hasAndBelongsToMany = array(
        'DealUser' => array(
            'className' => 'DealUser',
            'joinTable' => 'charities_deal_users',
            'foreignKey' => 'charity_id',
            'associationForeignKey' => 'deal_user_id',
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
    public $hasMany = array(
        'CharityCashWithdrawal' => array(
            'className' => 'CharityCashWithdrawal',
            'foreignKey' => 'charity_id',
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
        'Deal' => array(
            'className' => 'Deal',
            'foreignKey' => 'charity_id',
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
        'CharityMoneyTransferAccount' => array(
            'className' => 'CharityMoneyTransferAccount',
            'foreignKey' => 'charity_id',
            'dependent' => false,
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
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'charity_category_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'name' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'url' => array(
                'rule' => array(
                    'url',
                    true
                ) ,
                'allowEmpty' => true,
                'message' => __l('Enter valid URL')
            ) ,
            'paypal_email' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
        );
        $this->moreActions = array(
            ConstMoreAction::Active => __l('Active') ,
            ConstMoreAction::Inactive => __l('Inactive') ,
            ConstMoreAction::Delete => __l('Delete') ,
            ConstMoreAction::PayToCharity => __l('Pay to Charity...')
        );
    }
}
?>