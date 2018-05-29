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
class GiftUser extends AppModel
{
    public $name = 'GiftUser';
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
        'GiftedToUser' => array(
            'className' => 'User',
            'foreignKey' => 'gifted_to_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    public $hasMany = array(
        'Transaction' => array(
            'className' => 'Transaction',
            'foreignKey' => 'foreign_id',
            'dependent' => true,
            'conditions' => array(
                'Transaction.class' => 'GiftUser'
            ) ,
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
            'user_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'coupon_code' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
            'amount' => array(
                'rule4' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule3' => array(
                    'rule' => '_checkWalletAmount',
                    'allowEmpty' => false,
                    'message' => __l('You don\'t have sufficient amount to buy this gift')
                ) ,
                'rule2' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Please enter numeric number.')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
            'friend_name' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'friend_mail' => array(
                'rule2' => array(
                    'rule' => 'checkMultipleEmail',
                    'message' => __l('Must be a valid email')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
        );
        $this->validateCreditCard = array(
            'firstName' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'lastName' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'creditCardNumber' => array(
                'rule2' => array(
                    'rule' => 'numeric',
                    'message' => __l('Should be numeric') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'cvv2Number' => array(
                'rule2' => array(
                    'rule' => 'numeric',
                    'message' => __l('Should be numeric') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'zip' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'address' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'city' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'state' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'country' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'creditCardType' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
        );
        $this->moreActions = array(
            ConstMoreAction::Delete => __l('Delete') ,
        );
    }
    function _checkExpiryMonthAndYear($month = null, $year = null)
    {
        $error = 0;
        if (empty($month) || empty($year)) {
            $error = 'Required';
        } elseif ($year <= date('Y')) {
            if ($month <= date('m')) {
                $error = 'Invalid expiry date';
            }
        }
        return $error;
    }
    function checkMultipleEmail()
    {
        $multipleEmails = explode(',', $this->data['GiftUser']['friend_mail']);
        foreach($multipleEmails as $key => $singleEmail) {
            if (!Validation::email(trim($singleEmail))) {
                return false;
            }
        }
        return true;
    }
    function _checkWalletAmount()
    {
        if ($this->data['GiftUser']['payment_gateway_id'] != ConstPaymentGateways::Wallet) {
            return true;
        }
        if ($this->User->checkUserBalance($this->data[$this->name]['user_id']) >= $this->data[$this->name]['amount']) {
            return true;
        }
        return false;
    }
    function _updateGiftUserSent($user_id)
    {
        $gift_users = $this->find('all', array(
            'conditions' => array(
                'GiftUser.user_id' => $user_id,
            ) ,
            'fields' => array(
                'SUM(GiftUser.amount) as total_gift_card_sent_amount',
                'COUNT(GiftUser.amount) as total_gift_card_sent_count',
            ) ,
            'recursive' => -1
        ));
        if (!empty($gift_users)) {
            $this->User->updateAll(array(
                'User.total_gift_card_sent_amount' => $gift_users[0][0]['total_gift_card_sent_amount'],
                'User.total_gift_card_sent_count' => $gift_users[0][0]['total_gift_card_sent_count'],
            ) , array(
                'User.id' => $user_id
            ));
        }
    }
    function _updateGiftUserReceived($user_id)
    {
        $gift_users = $this->find('all', array(
            'conditions' => array(
                'GiftUser.gifted_to_user_id' => $user_id,
            ) ,
            'fields' => array(
                'SUM(GiftUser.amount) as total_gift_card_received_amount',
                'COUNT(GiftUser.amount) as total_gift_card_received_count',
            ) ,
            'recursive' => -1
        ));
        if (!empty($gift_users)) {
            $this->User->updateAll(array(
                'User.total_gift_card_received_amount' => $gift_users[0][0]['total_gift_card_received_amount'],
                'User.total_gift_card_received_count' => $gift_users[0][0]['total_gift_card_received_count'],
            ) , array(
                'User.id' => $user_id
            ));
        }
    }
    function _nonIpnGiftProcessPayment($gateway_name, $transaction_data, $is_test_mode)
    {
        $gateway = array(
            'pagseguro' => ConstPaymentGateways::PagSeguro
        );
        $gateway_id = (!empty($gateway[$gateway_name])) ? $gateway[$gateway_name] : 0;
        if (empty($transaction_data)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        // Processing Each Gateways //
        switch ($gateway_name) {
            case 'pagseguro':
                $paystatus_to_check = (!empty($is_test_mode) ? 'Aguardando Pagto' : 'Aprovado');
                if ($transaction_data['payment_status'] == $paystatus_to_check) {
                    $user = $this->User->find('first', array(
                        'conditions' => array(
                            'User.id' => $transaction_data['user_id']
                        ) ,
                        'fields' => array(
                            'User.id',
                            'User.username',
                            'User.available_balance_amount',
                        ) ,
                        'recursive' => -1
                    ));
                    $data = array();
                    $data['GiftUser']['user_id'] = $transaction_data['user_id'];
                    $data['GiftUser']['payment_gateway_id'] = $transaction_data['payment_gateway_id'];
                    $data['GiftUser']['amount'] = $transaction_data['original_amount_needed'];
                    $data['GiftUser']['message'] = $transaction_data['message'];
                    $data['GiftUser']['friend_mail'] = $transaction_data['friend_mail'];
                    $data['GiftUser']['friend_name'] = $transaction_data['friend_name'];
                    $data['GiftUser']['from'] = $user['User']['username'];
                    $coupon_code = $this->_uuid();
                    $data['GiftUser']['coupon_code'] = $coupon_code;
                    if ($this->save($data)) {
                        $giftusers_id = $this->getLastInsertId();
                        //update gift user id in PaypalTransactionLog table
                        $this->User->DealUser->Deal->PagseguroTransactionLog->updateAll(array(
                            'PagseguroTransactionLog.gift_user_id' => $giftusers_id
                        ) , array(
                            'PagseguroTransactionLog.id' => $transaction_data['pagseguro_transaction_log_id']
                        ));
                        // Partial Payment Works //
                        if (Configure::read('wallet.is_handle_wallet_as_in_groupon') && (!empty($transaction_data['is_purchase_with_wallet_amount']))) {
                            $user_available_balance = $user['User']['available_balance_amount']; // Deduct amount ( zero will be updated ) //
                            if (!empty($user_available_balance) && $user_available_balance != '0.00') {
                                $this->User->updateAll(array(
                                    'User.available_balance_amount' => 'User.available_balance_amount -' . $user_available_balance,
                                ) , array(
                                    'User.id' => $user['User']['id']
                                ));
                                // Update transaction, that, partial amount taken from wallet //
                                $transaction = array();
                                $transaction['Transaction']['user_id'] = $user['User']['id'];
                                $transaction['Transaction']['foreign_id'] = $giftusers_id;
                                $transaction['Transaction']['class'] = 'GiftUser';
                                $transaction['Transaction']['amount'] = $user_available_balance;
                                $transaction['Transaction']['transaction_type_id'] = ConstTransactionTypes::PartallyAmountTakenForGiftCardPurchase;
                                $transaction['Transaction']['payment_gateway_id'] = ConstPaymentGateways::Wallet;
                                $this->User->Transaction->log($transaction);
                            }
                        }
                        // Transaction for GiftCard Purchase //
                        $transaction = array();
                        $transaction['Transaction']['user_id'] = $user['User']['id'];
                        $transaction['Transaction']['foreign_id'] = $giftusers_id;
                        $transaction['Transaction']['class'] = 'GiftUser';
                        $transaction['Transaction']['amount'] = $transaction_data['original_amount_needed'];
                        $transaction['Transaction']['transaction_type_id'] = ConstTransactionTypes::GiftSent;
                        $transaction['Transaction']['payment_gateway_id'] = ConstPaymentGateways::PagSeguro;
                        // Currency Conversion Data //
                        $transaction['Transaction']['converted_amount'] = $transaction_data['amount_needed'];
                        $transaction['Transaction']['currency_id'] = $transaction_data['currency_id'];
                        $transaction['Transaction']['converted_currency_id'] = $transaction_data['converted_currency_id'];
                        $transaction['Transaction']['rate'] = $transaction_data['rate'];
                        $this->User->Transaction->log($transaction);
                        // Send gift user mail
                        $this->_send_gift_coupon_mail($data);
                        return true;
                    }
                }
            default:
                throw new NotFoundException(__l('Invalid request'));
        }
    }
    function _send_gift_coupon_mail($giftUser)
    {
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $this->Email = new EmailComponent($collection);
        $check_reciever = $this->User->find('first', array(
            'conditions' => array(
                'User.email' => $giftUser['GiftUser']['friend_mail']
            ) ,
            'recursive' => -1
        ));
        $language_code = $this->getUserLanguageIso($check_reciever['User']['id']);
        $template = $this->EmailTemplate->selectTemplate('Gift Coupon', $language_code);
        $emailFindReplace = array(
            '##FROM_EMAIL##' => $this->changeFromEmail(($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from']) ,
            '##SITE_LINK##' => Router::url('/', true) ,
            '##SITE_NAME##' => strtolower(Configure::read('site.name')) ,
            '##HOST_NAME##' => strtolower(Configure::read('site.name')) ,
            '##FRIEND_NAME##' => $giftUser['GiftUser']['friend_name'],
            '##FROM##' => $giftUser['GiftUser']['from'],
            '##TO##' => $giftUser['GiftUser']['friend_name'],
            '##TO_MAIL##' => $giftUser['GiftUser']['friend_mail'],
            '##MESSAGE##' => $giftUser['GiftUser']['message'],
            '##GIFT_CODE##' => $giftUser['GiftUser']['coupon_code'],
            '##GIFT_AMOUNT##' => $giftUser['GiftUser']['amount'],
            '##CURRENCY_CODE##' => Configure::read('site.currency') ,
            '##BACKGROUND_IMAGE##' => Router::url(array(
                'controller' => 'img',
                'action' => 'blue-theme',
                'gift-card.png',
                'admin' => false
            ) , true) ,
            '##REDEEM_LINK##' => Router::url(array(
                'controller' => 'gift_users',
                'action' => 'view_gift_card',
                $giftUser['GiftUser']['coupon_code'],
                'admin' => false
            ) , true) ,
            '##SITE_LOGO##' => Router::url(array(
                'controller' => 'img',
                'action' => 'blue-theme',
                'logo-email.png',
                'admin' => false
            ) , true) ,
            '##CONTACT_URL##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add',
                'city' => $this->params['named']['city'],
                'admin' => false
            ) , true) ,
        );
        $this->Email->from = ($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from'];
        $this->Email->replyTo = ($template['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $template['reply_to'];
        $this->Email->to = $giftUser['GiftUser']['friend_mail'];
        $this->Email->subject = strtr($template['subject'], $emailFindReplace);
        $this->Email->content = strtr($template['email_content'], $emailFindReplace);
        $this->log('Model');
        $this->log(strtr($template['email_content'], $emailFindReplace));
        $this->Email->sendAs = ($template['is_html']) ? 'html' : 'text';
        $this->Email->send($this->Email->content);
    }
}
?>