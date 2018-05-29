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
class PaypalTransactionLog extends AppModel
{
    public $name = 'PaypalTransactionLog';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => ''
        ) ,
        'Transaction' => array(
            'className' => 'Transaction',
            'foreignKey' => 'transaction_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => ''
        ) ,
        'DealUser' => array(
            'className' => 'DealUser',
            'foreignKey' => 'deal_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => ''
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
        'Ip' => array(
            'className' => 'Ip',
            'foreignKey' => 'ip_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    function isDownloadable($mix_id, $transaction_id, $user_id, $type)
    {
        if ($type == 'mix') {
            $condition = 'PaypalTransactionLog.mix_id';
        } else if ($type == 'artwork') {
            $condition = 'PaypalTransactionLog.artistphoto_id';
        }
        return $this->find(array(
            $condition => $mix_id,
            'PaypalTransactionLog.transaction' => $transaction_id,
            'PaypalTransactionLog.ack' => 'Success',
            'PaypalTransactionLog.user_id' => $user_id,
            'PaypalTransactionLog.type' => $type
        ) , null, null, -1);
    }
    function savePaymentReturnFields($arr_of_paypal_fields, $result, $payment_typeid, $user_id)
    {
        $this->create();
        foreach($arr_of_paypal_fields as $fields) $this->data['PaypalTransactionLog'][$fields] = isset($result[$fields]) ? $result[$fields] : '';
        $this->data['PaypalTransactionLog']['type'] = $type;
        $this->data['PaypalTransactionLog']['user_id'] = $user_id;
        $this->save($this->data);
    }
}
?>