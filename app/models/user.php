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
class User extends AppModel
{
    public $name = 'User';
    public $displayField = 'username';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'username'
            )
        ) ,
    );
    public $belongsTo = array(
        'UserType' => array(
            'className' => 'UserType',
            'foreignKey' => 'user_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'RefferalUser' => array(
            'className' => 'User',
            'foreignKey' => 'referred_by_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'GiftRecivedFromUser' => array(
            'className' => 'User',
            'foreignKey' => 'gift_user_id',
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
        ) ,
        'LastLoginIp' => array(
            'className' => 'Ip',
            'foreignKey' => 'last_login_ip_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $hasMany = array(
        'PaypalTransactionLog' => array(
            'className' => 'PaypalTransactionLog',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'UserView' => array(
            'className' => 'UserView',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'UserComment' => array(
            'className' => 'UserComment',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'OtherUserComment' => array(
            'className' => 'UserComment',
            'foreignKey' => 'posted_user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'UserOpenid' => array(
            'className' => 'UserOpenid',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'UserFriend' => array(
            'className' => 'UserFriend',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'BlockedUser' => array(
            'className' => 'BlockedUser',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'UserLogin' => array(
            'className' => 'UserLogin',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'DealUser' => array(
            'className' => 'DealUser',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'GiftUser' => array(
            'className' => 'GiftUser',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'Transaction' => array(
            'className' => 'Transaction',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'SecondTransaction' => array(
            'className' => 'Transaction',
            'foreignKey' => 'foreign_id',
            'dependent' => true,
            'conditions' => array(
                'SecondTransaction.class' => 'GiftUser'
            ) ,
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'UserCashWithdrawal' => array(
            'className' => 'UserCashWithdrawal',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'CitySuggestion' => array(
            'className' => 'CitySuggestion',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'UserPaymentProfile' => array(
            'className' => 'UserPaymentProfile',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'Affiliate' => array(
            'className' => 'Affiliate',
            'foreignKey' => 'affliate_user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ) ,
        'AffiliateCashWithdrawal' => array(
            'className' => 'AffiliateCashWithdrawal',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ) ,
        'ApnsDevice' => array(
            'className' => 'ApnsDevice',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ) ,
        'ApnsMessage' => array(
            'className' => 'ApnsMessage',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ) ,
        'ApnsDeviceHistory' => array(
            'className' => 'ApnsDeviceHistory',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ) ,
        'AffiliateRequest' => array(
            'className' => 'AffiliateRequest',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ) ,
        'MoneyTransferAccount' => array(
            'className' => 'MoneyTransferAccount',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ) ,
        'DealView' => array(
            'className' => 'DealView',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ) ,
        'CompanyView' => array(
            'className' => 'CompanyView',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ) ,
		'CkSession' => array(
            'className' => 'CkSession',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => '',
        ) ,
    );
    public $hasOne = array(
        'UserProfile' => array(
            'className' => 'UserProfile',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'UserPermissionPreference' => array(
            'className' => 'UserPermissionPreference',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'UserAvatar' => array(
            'className' => 'UserAvatar',
            'foreignKey' => 'foreign_id',
            'dependent' => true,
            'conditions' => array(
                'UserAvatar.class' => 'UserAvatar',
            ) ,
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'Company' => array(
            'className' => 'Company',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'user_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'message' => __l('Required')
                )
            ) ,
            'username' => array(
                'rule5' => array(
                    'rule' => array(
                        'between',
                        3,
                        20
                    ) ,
                    'message' => __l('Must be between of 3 to 20 characters')
                ) ,
                'rule4' => array(
                    'rule' => 'alphaNumeric',
                    'message' => __l('Must be a valid character')
                ) ,
                'rule3' => array(
                    'rule' => 'isUnique',
                    'message' => __l('Username is already exist')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'custom',
                        '/^[a-zA-Z]/'
                    ) ,
                    'message' => __l('Must be start with an alphabets')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'email' => array(
                'rule3' => array(
                    'rule' => 'isUnique',
                    'message' => __l('Email address is already exist')
                ) ,
                'rule2' => array(
                    'rule' => 'email',
                    'message' => __l('Must be a valid email')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'passwd' => array(
                'rule2' => array(
                    'rule' => array(
                        'minLength',
                        6
                    ) ,
                    'message' => __l('Must be at least 6 characters')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'old_password' => array(
                'rule3' => array(
                    'rule' => array(
                        '_checkOldPassword',
                        'old_password'
                    ) ,
                    'message' => __l('Your old password is incorrect, please try again')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'minLength',
                        6
                    ) ,
                    'message' => __l('Must be at least 6 characters')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'confirm_password' => array(
                'rule3' => array(
                    'rule' => array(
                        '_checkPassword',
                        'passwd',
                        'confirm_password'
                    ) ,
                    'message' => __l('New and confirm password field must match, please try again')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'minLength',
                        6
                    ) ,
                    'message' => __l('Must be at least 6 characters')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'captcha' => array(
                'rule2' => array(
                    'rule' => '_isValidCaptcha',
                    'message' => __l('Please enter valid captcha')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'is_agree_terms_conditions' => array(
                'rule' => array(
                    'equalTo',
                    '1'
                ) ,
                'message' => __l('You must agree to the terms and policies')
            ) ,
            'message' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'subject' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'city' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'amount' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
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
            'expiration_month' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'expiration_year' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
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
        // filter options in admin index
        $this->isFilterOptions = array(
            ConstMoreAction::Inactive => __l('Inactive') ,
            ConstMoreAction::Active => __l('Active')
        );
        $this->moreActions = array(
            ConstMoreAction::Inactive => __l('Inactive') ,
            ConstMoreAction::Active => __l('Active') ,
            ConstMoreAction::Delete => __l('Delete') ,
            ConstMoreAction::Export => __l('Export')
        );
        $this->adminMoreActions = array(
            ConstMoreAction::Active => __l('Active') ,
            ConstMoreAction::Export => __l('Export')
        );
        $this->bulkMailOptions = array(
            1 => __l('All Users') ,
            2 => __l('Inactive Users') ,
            3 => __l('Active Users')
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
    // check the new and confirm password
    function _checkPassword($field1 = array() , $field2 = null, $field3 = null)
    {
        if ($this->data[$this->name][$field2] == $this->data[$this->name][$field3]) {
            return true;
        }
        return false;
    }
    // check the old password field with database
    function _checkOldPassword($field1 = array() , $field2 = null)
    {
        $user = $this->find('first', array(
            'conditions' => array(
                'User.id' => $_SESSION['Auth']['User']['id']
            ) ,
            'recursive' => -1
        ));
        if (AuthComponent::password($this->data[$this->name][$field2]) == $user['User']['password']) {
            return true;
        }
        return false;
    }
    // hash for forgot password mail
    function getResetPasswordHash($user_id = null)
    {
        return md5($user_id . '-' . date('y-m-d') . Configure::read('Security.salt'));
    }
    // check the forgot password hash
    function isValidResetPasswordHash($user_id = null, $hash = null)
    {
        return (md5($user_id . '-' . date('y-m-d') . Configure::read('Security.salt')) == $hash);
    }
    // hash for activate mail
    function getActivateHash($user_id = null)
    {
        return md5($user_id . '-' . Configure::read('Security.salt'));
    }
    // check the activate mail
    function isValidActivateHash($user_id = null, $hash = null)
    {
        return (md5($user_id . '-' . Configure::read('Security.salt')) == $hash);
    }
    function getUserIdHash($user_ids = null)
    {
        return md5($user_ids . Configure::read('Security.salt'));
    }
    function isValidUserIdHash($user_ids = null, $hash = null)
    {
        return (md5($user_ids . Configure::read('Security.salt')) == $hash);
    }
    function isAllowed($user_type = null)
    {
        if ($user_type != ConstUserTypes::Company || ($user_type == ConstUserTypes::Company && Configure::read('user.is_company_actas_normal_user'))) {
            return true;
        }
        return false;
    }
    function checkUserBalance($user_id = null)
    {
        $user = $this->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'fields' => array(
                'User.available_balance_amount'
            ) ,
            'recursive' => -1
        ));
        if ($user['User']['available_balance_amount']) {
            return $user['User']['available_balance_amount'];
        }
        return false;
    }
    function _checkamount($amount)
    {
        if (!empty($amount) && !is_numeric($amount)) {
            $this->validationErrors['amount'] = __l('Amount should be Numeric');
        }
        if (empty($amount)) {
            $this->validationErrors['amount'] = __l('Required');
        }
        if (!empty($amount) && $amount < Configure::read('wallet.min_wallet_amount')) {
            $this->validationErrors['amount'] = __l('Amount should be greater than minimum amount');
        }
        if (Configure::read('wallet.max_wallet_amount') && !empty($amount) && $amount > Configure::read('wallet.max_wallet_amount')) {
            $this->validationErrors['amount'] = sprintf(__l('Given amount should lies from  %s%s to %s%s') , Configure::read('site.currency') , Configure::read('wallet.min_wallet_amount') , Configure::read('site.currency') , Configure::read('wallet.max_wallet_amount'));
        }
        return false;
    }
    function checkUsernameAvailable($username)
    {
        $user = $this->find('count', array(
            'conditions' => array(
                'User.username' => $username
            ) ,
            'recursive' => -1
        ));
        if (!empty($user)) {
            return false;
        }
        return $username;
    }
    function _getCimObject()
    {
        require_once (APP . 'vendors' . DS . 'CIM' . DS . 'AuthnetCIM.class.php');
        $paymentGateway = $this->Transaction->PaymentGateway->getPaymentSettings(ConstPaymentGateways::AuthorizeNet);
        if (!empty($paymentGateway) && !empty($paymentGateway['PaymentGateway']['authorize_net_api_key']) && !empty($paymentGateway['PaymentGateway']['authorize_net_trans_key'])) {
            if ($paymentGateway['PaymentGateway']['is_test_mode']) {
                $cim = new AuthnetCIM($paymentGateway['PaymentGateway']['authorize_net_api_key'], $paymentGateway['PaymentGateway']['authorize_net_trans_key'], true);
            } else {
                $cim = new AuthnetCIM($paymentGateway['PaymentGateway']['authorize_net_api_key'], $paymentGateway['PaymentGateway']['authorize_net_trans_key']);
            }
            return $cim;
        }
        return false;
    }
    function _createCimProfile($user_id)
    {
        $user = $this->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'fields' => array(
                'User.email',
                'User.id',
                'User.username'
            ) ,
            'recursive' => -1
        ));
        $cim = $this->_getCimObject();
        if (!empty($cim) && !empty($user['User']['email'])) {
            $cim->setParameter('email', $user['User']['email']);
            $cim->setParameter('description', 'Profile for ' . $user['User']['username']); // Optional
            $cim->setParameter('merchantCustomerId', $user['User']['id']);
            $cim->createCustomerProfile();
            $profile_id = $cim->getProfileID();
            $this->updateAll(array(
                'User.cim_profile_id' => $profile_id,
            ) , array(
                'User.id' => $user['User']['id']
            ));
        }
    }
    function _createCimPaymentProfile($data)
    {
        $cim = $this->_getCimObject();
        if (!empty($cim)) {
            $cim->setParameter('refId', time());
            $cim->setParameter('billToCompany', Configure::read('site.name'));
            $cim->setParameter('customerProfileId', $data['customerProfileId']);
            $cim->setParameter('billToFirstName', $data['firstName']);
            $cim->setParameter('billToLastName', $data['lastName']);
            $cim->setParameter('billToAddress', $data['address']);
            $cim->setParameter('billToCity', $data['city']);
            $cim->setParameter('billToState', $data['state']);
            $cim->setParameter('billToZip', $data['zip']);
            $cim->setParameter('billToCountry', $data['country']);
            $cim->setParameter('cardNumber', $data['creditCardNumber']);
            $cim->setParameter('cardCode', $data['cvv2Number']);
            $cim->setParameter('expirationDate', $data['expirationDate']);
            $cim->createCustomerPaymentProfile();
            if ($cim->isSuccessful()) {
                $payment_profile_id = $cim->getPaymentProfileId();
                $profile_info = array_reverse(explode(',', $cim->validationDirectResponse()));
                if (end($profile_info) == 1) {
                    $return['payment_profile_id'] = $payment_profile_id;
                    $return['masked_cc'] = $profile_info[16] . ' ' . $profile_info[17];
                } else {
                    $return = $profile_info[3];
                }
            } else {
                $return['message'] = $cim->getResponse();
            }
            return $return;
        }
        return false;
    }
    function _updateCimPaymentProfile($data)
    {
        $cim = $this->_getCimObject();
        if (!empty($cim)) {
            $cim->setParameter('refId', time());
            $cim->setParameter('company', Configure::read('site.name'));
            $cim->setParameter('customerProfileId', $data['customerProfileId']);
            $cim->setParameter('customerPaymentProfileId', $data['customerPaymentProfileId']);
            $cim->setParameter('firstName', $data['firstName']);
            $cim->setParameter('lastName', $data['lastName']);
            $cim->setParameter('address', $data['address']);
            $cim->setParameter('city', $data['city']);
            $cim->setParameter('state', $data['state']);
            $cim->setParameter('zip', $data['zip']);
            $cim->setParameter('country', $data['country']);
            $cim->setParameter('cardNumber', $data['creditCardNumber']);
            $cim->setParameter('expirationDate', $data['expirationDate']);
            $cim->updateCustomerPaymentProfile();
            if ($cim->isSuccessful()) {
                return true;
            } else {
                $return['message'] = $cim->getResponse();
            }
        }
        return false;
    }
    function _deleteCimPaymentProfile($data)
    {
        $cim = $this->_getCimObject();
        if (!empty($cim)) {
            $cim->setParameter('refId', time());
            $cim->setParameter('customerProfileId', $data['customerProfileId']);
            $cim->setParameter('customerPaymentProfileId', $data['customerPaymentProfileId']);
            $cim->deleteCustomerPaymentProfile();
            if ($cim->isSuccessful()) {
                return true;
            }
        }
        return false;
    }
    function _getCimPaymentProfile($data)
    {
        $cim = $this->_getCimObject();
        if (!empty($cim)) {
            $cim->setParameter('refId', time());
            $cim->setParameter('customerProfileId', $data['customerProfileId']);
            $cim->setParameter('customerPaymentProfileId', $data['customerPaymentProfileId']);
            $cim->getCustomerPaymentProfile();
            if ($cim->isSuccessful()) {
                $return = $cim->getPaymentProfile();
            }
            return $return;
        }
        return false;
    }
    function _createCustomerProfileTransaction($data, $type)
    {
        $cim = $this->_getCimObject();
        if (!empty($cim)) {
            $cim->setParameter('refId', time());
            $cim->setParameter('amount', $data['amount']);
            $cim->setParameter('customerProfileId', $data['customerProfileId']);
            $cim->setParameter('customerPaymentProfileId', $data['customerPaymentProfileId']);
            if ($type == 'profileTransAuthOnly') {
                $title = Configure::read('site.name') . ' - Deal Amount Authorize';
                $description = 'Authorize deal amount in ' . Configure::read('site.name');
            } else {
                $title = Configure::read('site.name') . ' - Deal Bought';
                $description = 'Deal Bought in ' . Configure::read('site.name');
            }
            // CIM accept only 30 character in title
            if (strlen($title) > 30) {
                $title = substr($title, 0, 27) . '...';
            }
            $unit_amount = $data['amount']/$data['quantity'];
            $unit_amount = round($unit_amount, 2);
            $cim->setLineItem($data['deal_id'], $title, $description, $data['quantity'], $unit_amount);
            $cim->createCustomerProfileTransaction($type);
            $response = $cim->getDirectResponse();
            $response_array = explode(',', $response);
            $data_authorize_docapture_log['AuthorizenetDocaptureLog']['deal_user_id'] = $data['deal_id'];
            $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_response_text'] = $cim->getResponseText();
            $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_authorization_code'] = $cim->getAuthCode();
            $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_avscode'] = $cim->getAVSResponse();
            $data_authorize_docapture_log['AuthorizenetDocaptureLog']['transactionid'] = $cim->getTransactionID();
            $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_amt'] = $response_array[9];
            $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_gateway_feeamt'] = $response_array[32];
            $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_cvv2match'] = $cim->getCVVResponse();
            $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_response'] = $response;
            $this->DealUser->AuthorizenetDocaptureLog->save($data_authorize_docapture_log);
            $get_authorize_id = $this->DealUser->AuthorizenetDocaptureLog->getLastInsertId();
            if ($cim->isSuccessful()) {
                $outputResponse['cim_approval_code'] = $cim->getAuthCode();
                $outputResponse['cim_transaction_id'] = $cim->getTransactionID();
                if ($type == 'profileTransAuthCapture') {
                    $outputResponse['capture'] = 1;
                }
                $outputResponse['pr_authorize_id'] = $get_authorize_id;
            } else {
                $outputResponse['message'] = $cim->getResponse();
            }
            return $outputResponse;
        }
        return false;
    }
}
?>