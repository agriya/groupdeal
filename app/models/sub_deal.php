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
App::import('Model', 'Deal');
class SubDeal extends Deal
{
    public $name = 'SubDeal';
    var $useTable = 'deals';
    public $belongsTo = array(
        'Deal' => array(
            'className' => 'Deal',
            'foreignKey' => 'parent_id',
            'fields' => '',
            'order' => '',
        ) ,
    );
}
?>