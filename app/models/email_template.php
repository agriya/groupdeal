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
class EmailTemplate extends AppModel
{
    public $name = 'EmailTemplate';
    public $displayField = 'name';
    public $actsAs = array(
        'i18n' => array(
            'fields' => array(
                'subject',
                'email_content'
            ) ,
            'display' => array(
                'subject',
                'email_content'
            ) ,
        )
    );
    //This section is commented since i18n.php didn't support
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'from' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'subject' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'email_content' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
    }
    function selectTemplate($tempName, $lang_code = null)
    {
        $temp = Configure::read('lang_code');
        if (!is_null($lang_code)) Configure::write('lang_code', $lang_code);
        $emailTemplate = $this->find('first', array(
            'conditions' => array(
                'EmailTemplate.name' => $tempName
            ) ,
            'recursive' => -1
        ));
        $resultArray = array();
        foreach($emailTemplate as $singleArrayEmailTemplate) {
            foreach($singleArrayEmailTemplate as $key => $value) {
                $resultArray[$key] = $value;
            }
        }
        if (!is_null($lang_code)) Configure::write('lang_code', $temp);
        return $resultArray;
    }
}
?>