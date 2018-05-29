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
/**
 * Model behavior to support making any string, URL safe
 *
 */
class CensoredBehavior extends ModelBehavior
{
    /**
     * Contain settings indexed by model name.
     *
     * @var array
     * @access private
     */
    var $__settings = array();
    /**
     * Array containing all bad words to be replaced
     *
     * @var array
     * @access private
     */
    public $__badWords = array();
    /**
     * Initiate behavior for the model using specified settings. Available settings:
     *
     * - fields: array of fields to search and replace in
     *
     * - type: determines when replace happens
     *                 - 'find' runs afterFind (non-destructive)
     *                 - 'save' runs beforeSave (destructive)
     *                 - 'both' runs after find and before save (obviously!)
     *
     * @param object $Model Model using the behaviour
     * @param array $settings Settings to override for model.
     * @access public
     */
    function setup(&$Model, $settings = array()) 
    {
        // To over ride the censored words
        App::import('Model', 'CensorWord');
        $modelObjone = new CensorWord();
        $censoredwords = $modelObjone->find('all', array(
            'fields' => array(
                'CensorWord.word'
            )
        ));
        foreach($censoredwords as $word) $this->__badWords[] = $word['CensorWord']['word'];
        // stores the name of each field to be replaced
        $default = array(
            'fields' => array(
                'name'
            ) ,
            'type' => 'find'
        );
        if (!isset($this->__settings[$Model->alias])) {
            $this->__settings[$Model->alias] = $default;
        }
        $this->__settings[$Model->alias] = am($this->__settings[$Model->alias], (is_array($settings) ? $settings : array()));
    }
    /**
     * Runs before a save() operation.
     *
     * @param object $Model    Model using the behaviour
     * @param array $results Results of the find operation.
     * @access public
     */
    function beforeSave(&$Model) 
    {
        // check field has content
        if (!empty($this->__settings[$Model->alias]['fields']) && ($this->__settings[$Model->alias]['type'] == 'save' || $this->__settings[$Model->alias]['type'] == 'both')) {
            // loop through results
            foreach($Model->data as &$row) {
                // loop through fields
                foreach($this->__settings[$Model->alias]['fields'] as $field) {
                    // check field exists
                    if (isset($row[$Model->alias][$field])) {
                        // replace isntances of each bad word
                        foreach($this->__badWords as $word) {
                            $row[$Model->alias][$field] = eregi_replace($word, $this->__settings[$Model->alias]['replace'], $row[$Model->alias][$field]);
                        }
                    }
                }
            }
        }
        return true;
    }
    /**
     * Runs after a find() operation.
     *
     * @param object $Model    Model using the behaviour
     * @param array $results Results of the find operation.
     * @access public
     */
    function afterFind(&$Model, $results) 
    {
        // check field has content
        if (!empty($this->__settings[$Model->alias]['fields']) && ($this->__settings[$Model->alias]['type'] == 'find' || $this->__settings[$Model->alias]['type'] == 'both')) {
            // loop through results
            foreach($results as &$row) {
                // loop through fields
                foreach($this->__settings[$Model->alias]['fields'] as $field) {
                    // check field exists
                    if (isset($row[$Model->alias][$field])) {
                        // preg replace on an array?
                        foreach($this->__badWords as $word) {
                            $row[$Model->alias][$field] = eregi_replace($word, $this->__settings[$Model->alias]['replace'], $row[$Model->alias][$field]);
                        }
                    }
                }
            }
        }
        return $results;
    }
}
?>