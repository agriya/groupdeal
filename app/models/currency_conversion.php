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
class CurrencyConversion extends AppModel
{
    public $name = 'CurrencyConversion';
    public $displayField = 'name';
    //$validate set in __construct for multi-language support
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'currency_id' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'converted_currency_id' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'rate' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
    }
    public $hasMany = array(
        'CurrencyConversionHistory' => array(
            'className' => 'CurrencyConversionHistory',
            'foreignKey' => 'currency_conversion_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $belongsTo = array(
        'Currency' => array(
            'className' => 'Currency',
            'foreignKey' => 'currency_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'ConvertedCurrency' => array(
            'className' => 'Currency',
            'foreignKey' => 'converted_currency_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
    );
    function cacheConversionCurrency($is_supported = null, $currency_id = null, $converted_currency_id = null)
    {
        $conditions = array();
        if (isset($currency_id) && isset($converted_currency_id)) {
            $conditions['CurrencyConversion.currency_id'] = $currency_id;
            $conditions['CurrencyConversion.converted_currency_id'] = $converted_currency_id;
        }
        $conversion_currencies = $this->find('first', array(
            'conditions' => $conditions,
            'recursive' => 1
        ));
        Cache::write('site_paypal_conversion_currency', $conversion_currencies);
        Cache::write('site_paypal_conversion_currency_rate', $conversion_currencies['CurrencyConversion']['rate']);
        return $conversion_currencies;
    }
    function getCurrencyConversion($currency_id)
    {
        $currency_conversions = array();
        $currency_conversions = $this->find('all', array(
            'conditions' => array(
                'CurrencyConversion.currency_id' => $currency_id
            ) ,
            'fields' => array(
                'CurrencyConversion.id',
                'CurrencyConversion.currency_id',
                'CurrencyConversion.converted_currency_id',
                'CurrencyConversion.rate',
            ) ,
            'contain' => array(
                'Currency' => array(
                    'fields' => array(
                        'Currency.id',
                        'Currency.name',
                        'Currency.code',
                        'Currency.symbol',
                    )
                ) ,
                'ConvertedCurrency' => array(
                    'fields' => array(
                        'ConvertedCurrency.id',
                        'ConvertedCurrency.name',
                        'ConvertedCurrency.code',
                        'ConvertedCurrency.symbol',
                    )
                ) ,
            ) ,
            'order' => array(
                'Currency.code' => 'asc'
            ) ,
            'recursive' => 0
        ));
        if (empty($currency_conversions)) {
            $currencies = $this->Currency->find('all', array(
                'recursive' => -1
            ));
            $i = 0;
            foreach($currencies as $currency) {
                $currency_conversions[$i]['CurrencyConversion']['id'] = '';
                if ($currency_id == $currency['Currency']['id']) $currency_conversions[$i]['CurrencyConversion']['rate'] = 1;
                else $currency_conversions[$i]['CurrencyConversion']['rate'] = '';
                $currency_conversions[$i]['ConvertedCurrency']['code'] = $currency['Currency']['code'];
                $i++;
            }
        }
        return $currency_conversions;
    }
    function _checkHistoryChanges($data)
    {
        $conversion_changes = array();
        foreach($data['CurrencyConversion'] as $conversion_currency) {
            $currency_conversion = $this->find('first', array(
                'conditions' => array(
                    'CurrencyConversion.id' => $conversion_currency['id']
                ) ,
                'recursive' => -1
            ));
            if (!empty($currency_conversion) && ($conversion_currency['rate'] != $currency_conversion['CurrencyConversion']['rate'])) {
                $conversion_changes[] = array(
                    'id' => $currency_conversion['CurrencyConversion']['id'],
                    'currency_id' => $currency_conversion['CurrencyConversion']['currency_id'],
                    'rate_before_change' => $currency_conversion['CurrencyConversion']['rate'],
                    'rate' => $conversion_currency['rate']
                );
                $currency_conversion;
            }
        }
        if (!empty($conversion_changes)) {
            return $conversion_changes;
        }
        return 0;
    }
}
?>