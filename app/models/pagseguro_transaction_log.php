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
class PagseguroTransactionLog extends AppModel
{
    public $name = 'PagseguroTransactionLog';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'DealUser' => array(
            'className' => 'DealUser',
            'foreignKey' => 'deal_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'GiftUser' => array(
            'className' => 'GiftUser',
            'foreignKey' => 'gift_user_id',
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
        'Deal' => array(
            'className' => 'Deal',
            'foreignKey' => 'deal_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
    }
    function logPagSeguroTransactions($transaction_data)
    {
        $this->create();
        $data['PagseguroTransactionLog']['serialized_post_array'] = base64_encode(serialize($transaction_data));
        $data['PagseguroTransactionLog']['amount'] = $transaction_data['amount_needed'];
        $data['PagseguroTransactionLog']['currency'] = $transaction_data['currency_code'];
        $data['PagseguroTransactionLog']['is_gift'] = !empty($transaction_data['is_gift']) ? $transaction_data['is_gift'] : '0';
        $data['PagseguroTransactionLog']['quantity'] = !empty($transaction_data['quantity']) ? $transaction_data['quantity'] : '1';
        $data['PagseguroTransactionLog']['payment_gateway_id'] = $transaction_data['payment_gateway_id'];
        $data['PagseguroTransactionLog']['gift_to'] = !empty($transaction_data['gift_to']) ? $transaction_data['gift_to'] : '';
        $data['PagseguroTransactionLog']['gift_from'] = !empty($transaction_data['gift_from']) ? $transaction_data['gift_from'] : $transaction_data['friend_name'];
        $data['PagseguroTransactionLog']['gift_email'] = !empty($transaction_data['gift_email']) ? $transaction_data['gift_email'] : $transaction_data['friend_mail'];
        $data['PagseguroTransactionLog']['buyer_email'] = !empty($transaction_data['buyer_email']) ? $transaction_data['buyer_email'] : '';
        $data['PagseguroTransactionLog']['transaction_id'] = !empty($transaction_data['transaction_id']) ? $transaction_data['transaction_id'] : '';
        $data['PagseguroTransactionLog']['transaction_date'] = !empty($transaction_data['transaction_date']) ? $transaction_data['transaction_date'] : '';
        $data['PagseguroTransactionLog']['ip'] = $transaction_data['ip'];
        $data['PagseguroTransactionLog']['message'] = !empty($transaction_data['message']) ? $transaction_data['message'] : '';
        $data['PagseguroTransactionLog']['company_address_id'] = !empty($transaction_data['company_address_id']) ? $transaction_data['company_address_id'] : 0;
        $data['PagseguroTransactionLog']['payment_method'] = !empty($transaction_data['payment_method']) ? $transaction_data['payment_method'] : '';
        $data['PagseguroTransactionLog']['payment_type'] = !empty($transaction_data['payment_method']) ? $transaction_data['payment_method'] : '';
        $data['PagseguroTransactionLog']['payment_status'] = !empty($transaction_data['payment_status']) ? $transaction_data['payment_status'] : '';
        $data['PagseguroTransactionLog']['name'] = !empty($transaction_data['name']) ? $transaction_data['name'] : '';
        $data['PagseguroTransactionLog']['address'] = !empty($transaction_data['address']) ? $transaction_data['address'] : '';
        $data['PagseguroTransactionLog']['number'] = !empty($transaction_data['number']) ? $transaction_data['number'] : '';
        $data['PagseguroTransactionLog']['quarter'] = !empty($transaction_data['quarter']) ? $transaction_data['quarter'] : '';
        $data['PagseguroTransactionLog']['city'] = !empty($transaction_data['city']) ? $transaction_data['city'] : '';
        $data['PagseguroTransactionLog']['state'] = !empty($transaction_data['state']) ? $transaction_data['state'] : '';
        $data['PagseguroTransactionLog']['zip'] = !empty($transaction_data['zip']) ? $transaction_data['zip'] : '';
        $data['PagseguroTransactionLog']['phone'] = !empty($transaction_data['phone']) ? $transaction_data['phone'] : '';
        $data['PagseguroTransactionLog']['currency_id'] = !empty($transaction_data['currency_id']) ? $transaction_data['currency_id'] : '';
        $data['PagseguroTransactionLog']['converted_currency_id'] = !empty($transaction_data['converted_currency_id']) ? $transaction_data['converted_currency_id'] : '';
        $data['PagseguroTransactionLog']['orginal_amount'] = !empty($transaction_data['orginal_amount']) ? $transaction_data['orginal_amount'] : '';
        $data['PagseguroTransactionLog']['rate'] = !empty($transaction_data['rate']) ? $transaction_data['rate'] : '';
        $this->save($data, false);
        return $this->getLastInsertId();
    }
}
?>