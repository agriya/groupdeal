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
class RepeatDate extends AppModel
{
    public $name = 'RepeatDate';
    public $displayField = 'name';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $hasAndBelongsToMany = array(
        'Deal' => array(
            'className' => 'Deal',
            'joinTable' => 'deals_repeat_dates',
            'foreignKey' => 'repeat_date_id',
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
            'RepeatDate' => array(
                'rule' => 'is_check',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
        );
    }
    function is_check()
    {
        if (empty($this->data['RepeatDate']['RepeatDate'])) {
            return false;
        }
        return true;
    }
}
