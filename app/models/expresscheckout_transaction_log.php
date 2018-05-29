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
class ExpresscheckoutTransactionLog extends AppModel
{
    public $name = 'ExpresscheckoutTransactionLog';
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
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
    }
    function logdata($transaction_data)
    {
        $this->create();
        $data['ExpresscheckoutTransactionLog']['payer_id'] = $transaction_data['PAYERID'];
        $data['ExpresscheckoutTransactionLog']['token'] =  $transaction_data['TOKEN'];
        $data['ExpresscheckoutTransactionLog']['correlation_id'] = $transaction_data['CORRELATIONID'];
        $data['ExpresscheckoutTransactionLog']['payer_email'] = $transaction_data['EMAIL'];
        $data['ExpresscheckoutTransactionLog']['mc_gross'] = $transaction_data['AMT'];
		$data['ExpresscheckoutTransactionLog']['mc_currency'] = $transaction_data['CURRENCYCODE'];
		$data['ExpresscheckoutTransactionLog']['mc_fee'] = $transaction_data['FEEAMT'];
		$data['ExpresscheckoutTransactionLog']['payment_status'] = $transaction_data['PAYMENTSTATUS'];
		$data['ExpresscheckoutTransactionLog']['transaction_id'] = $transaction_data['TRANSACTIONID'];
		$data['ExpresscheckoutTransactionLog']['pending_reason'] = $transaction_data['PENDINGREASON'];
		$data['ExpresscheckoutTransactionLog']['error_no'] = $transaction_data['ERRORCODE'];
		$data['ExpresscheckoutTransactionLog']['user_id'] = $transaction_data['user_id'];
		$data['ExpresscheckoutTransactionLog']['is_authorization'] = 1;
        $this->save($data, false);
        return $this->getLastInsertId();
    }
	
}
?>