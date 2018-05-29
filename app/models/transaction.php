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
class Transaction extends AppModel
{
    public $name = 'Transaction';
    public $actsAs = array(
        'Polymorphic' => array(
            'classField' => 'class',
            'foreignKey' => 'foreign_id',
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
        'TransactionType' => array(
            'className' => 'TransactionType',
            'foreignKey' => 'transaction_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'PaymentGateway' => array(
            'className' => 'PaymentGateway',
            'foreignKey' => 'payment_gateway_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'SecondUser' => array(
            'className' => 'SecondUser',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'GiftUser' => array(
            'className' => 'GiftUser',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'DealUser' => array(
            'className' => 'DealUser',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'Deal' => array(
            'className' => 'Deal',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'Currency' => array(
            'className' => 'Currency',
            'foreignKey' => 'currency_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'ConvertedCurrency' => array(
            'className' => 'Currency',
            'foreignKey' => 'converted_currency_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'ReceiverUser' => array(
            'className' => 'User',
            'foreignKey' => 'receiver_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'user_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'amount' => array(
                'rule3' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        -1
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than or equal to 0')
                ) ,
                'rule2' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Require numeric value')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            )
        );
    }
    function log($data)
    {
        if (!empty($data)) {
            $this->create();
            $this->save($data);
            return $this->getLastInsertId();
        }
        return false;
    }
}
?>