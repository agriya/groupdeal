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
class GiftUsersController extends AppController
{
    public $name = 'GiftUsers';
	public $permanentCacheAction = array(
		'user' => array(
			'add',
			'edit',
			'update',
			'delete',
		) ,
		'public' => array(
			'index',
			'search',
		    'view'
		) ,
		'admin' => array(
			'admin_add',
			'admin_edit',
			'admin_update',
			'admin_delete',
		) ,
        'is_view_count_update' => true
    );
    public $components = array(
        'Email',
        'Paypal',
        'pagSeguro'
    );
    public $helpers = array(
        'Gateway',
        'pagSeguro',
    );
    public function beforeFilter()
    {
        if (!$this->GiftUser->User->isAllowed($this->Auth->user('user_type_id'))) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Security->disabledFields = array(
            'GiftUser.from',
            'GiftUser.submit',
            'GiftUser.id',
            'GiftUser.r',
            'GiftUser.more_action_id',
            'GiftUser.is_purchase_via_wallet',
            'GiftUser.group_wallet',
            'GiftUser.is_show_new_card',
            'GiftUser.payment_gateway_id',
            'City.id',
            'City.name',
            'State.id',
            'State.name',
            'User.referer_name',
            'UserProfile.country_id',
            'UserProfile.state_id',
            'UserProfile.city_id',
            'User.geobyte_info',
            'User.maxmind_info',
            'User.referred_by_user_id',
            'User.type',
            'User.is_agree_terms_conditions',
            'User.country_iso_code',
            'User.is_requested',
            'User.is_remember',
            'User.is_show_new_card',
            'User.f',
        );
        parent::beforeFilter();
    }
    public function index()
    {
        $this->disableCache();
        if ((!$this->GiftUser->User->isAllowed($this->Auth->user('user_type_id')))) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = __l('My Gift Cards');
        $conditions = array();
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'sent') {
            $conditions['GiftUser.user_id'] = $this->Auth->user('id');
        } elseif (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'received') {
            $conditions['or'] = array(
                'GiftUser.gifted_to_user_id = ' => $this->Auth->user('id') ,
                'GiftUser.friend_mail = ' => $this->Auth->user('email') ,
            );
        } elseif (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'all') {
            $conditions['or'] = array(
                'GiftUser.user_id = ' => $this->Auth->user('id') ,
                'GiftUser.friend_mail = ' => $this->Auth->user('email') ,
            );
        }
        $this->GiftUser->recursive = 1;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    )
                ) ,
                'GiftedToUser' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    )
                )
            ) ,
            'order' => array(
                'GiftUser.id' => 'desc'
            )
        );
        $this->set('giftUsers', $this->paginate());
        $this->set('sent', $this->GiftUser->find('count', array(
            'conditions' => array(
                'GiftUser.user_id = ' => $this->Auth->user('id')
            ) ,
            'recursive' => -1
        )));
        $count_conditions['or'] = array(
            'GiftUser.gifted_to_user_id = ' => $this->Auth->user('id') ,
            'GiftUser.friend_mail = ' => $this->Auth->user('email') ,
        );
        $this->set('received', $this->GiftUser->find('count', array(
            'conditions' => $count_conditions,
            'recursive' => -1
        )));
        $this->set('pageTitle', $this->pageTitle);
    }
    public function view_gift_card($coupon_code = null)
    {
        $this->pageTitle = __l('Gift Card');
        if (is_null($coupon_code)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!$this->Auth->user('id') and !empty($this->request->url)) $this->Session->write('Auth.redirectUrl', $this->request->url);
        $giftUser = $this->GiftUser->find('first', array(
            'conditions' => array(
                'GiftUser.coupon_code = ' => $coupon_code
            ) ,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.user_type_id',
                        'User.username',
                        'User.id',
                    )
                )
            ) ,
            'recursive' => 0,
        ));
        if (empty($giftUser)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Session->write('gift_user_id', $giftUser['GiftUser']['user_id']);
        $this->set('giftUser', $giftUser);
    }
    public function add()
    {
        $this->pageTitle = __l('Customize Your Gift Card');
        if (!$this->GiftUser->User->isAllowed($this->Auth->user('user_type_id'))) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user_available_balance = 0;
        $check_expire = 0;
        if ($this->Auth->user('id')) {
            $user_available_balance = $this->GiftUser->User->checkUserBalance($this->Auth->user('id'));
        }
        $cur_user = $this->GiftUser->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->Auth->user('id') ,
            ) ,
            'fields' => array(
                'User.id',
                'User.fb_user_id',
                'User.email',
            ) ,
            'recursive' => -1
        ));
        if (!empty($this->request->data)) {
            // If wallet act like groupon enabled, and purchase with wallet enabled, setting below for making purchase through wallet //
            if (Configure::read('wallet.is_handle_wallet_as_in_groupon') && !empty($this->request->data['GiftUser']['is_purchase_via_wallet'])) {
                $this->request->data['GiftUser']['payment_gateway_id'] = ConstPaymentGateways::Wallet;
            }
            //validation for credit card details
            $this->GiftUser->set($this->request->data);
            if ($this->request->data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::CreditCard) {
                $this->GiftUser->validate = array_merge($this->GiftUser->validate, $this->GiftUser->validateCreditCard);
                $this->GiftUser->User->Company->City->State->validate = array_merge($this->GiftUser->User->Company->City->State->validate, $this->GiftUser->User->Company->City->State->validateStateName);
                $check_expire = $this->GiftUser->_checkExpiryMonthAndYear($this->request->data['GiftUser']['expDateMonth']['month'], $this->request->data['GiftUser']['expDateYear']['year']);
            } else if (($this->request->data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet && isset($this->request->data['GiftUser']['payment_profile_id']) && empty($this->request->data['GiftUser']['payment_profile_id']))) {
                $this->GiftUser->validate = array_merge($this->GiftUser->validate, $this->GiftUser->validateCreditCard);
                $this->GiftUser->User->Company->City->State->validate = array_merge($this->GiftUser->User->Company->City->State->validate, $this->GiftUser->User->Company->City->State->validateStateName);
                $check_expire = $this->GiftUser->_checkExpiryMonthAndYear($this->request->data['GiftUser']['expDateMonth']['month'], $this->request->data['GiftUser']['expDateYear']['year']);
                if ($this->request->data['GiftUser']['is_show_new_card'] == 0) {
                    $payment_gateway_id_validate = array(
                        'payment_profile_id' => array(
                            'rule1' => array(
                                'rule' => 'notempty',
                                'message' => __l('Required')
                            )
                        )
                    );
                    $this->GiftUser->validate = array_merge($this->GiftUser->validate, $payment_gateway_id_validate);
                }
            } else if ($this->request->data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet && (!isset($this->request->data['GiftUser']['payment_profile_id']))) {
                $this->GiftUser->validate = array_merge($this->GiftUser->validate, $this->GiftUser->validateCreditCard);
                $this->GiftUser->User->Company->City->State->validate = array_merge($this->GiftUser->User->Company->City->State->validate, $this->GiftUser->User->Company->City->State->validateStateName);
                $check_expire = $this->GiftUser->_checkExpiryMonthAndYear($this->request->data['GiftUser']['expDateMonth']['month'], $this->request->data['GiftUser']['expDateYear']['year']);
            }
            $this->GiftUser->validates();
            // State Validation //
            $this->GiftUser->User->Company->City->State->set($this->request->data['State']);
            $this->GiftUser->User->Company->City->State->validates();
            $user_details_updated = true;
            //for facebook users need to update email address at first time
            if (!empty($cur_user['User']['fb_user_id']) && empty($cur_user['User']['email'])) {
                $this->request->data['User']['id'] = $this->Auth->user('id');
                $this->GiftUser->User->set($this->request->data['User']);
                if ($this->GiftUser->User->validates() && empty($this->GiftUser->User->validationErrors) &empty($this->GiftUser->User->City->State->validationErrors)) {
                    $this->GiftUser->User->save($this->request->data['User']);
                    if (empty($_SESSION['Auth']['User']['cim_profile_id'])) {
                        if (!empty($this->request->data['State']['name'])) {
                            $this->request->data['GiftUser']['state'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->GiftUser->User->Company->City->State->findOrSaveAndGetId($this->request->data['State']['name']);
                        }
                        if (!empty($this->request->data['GiftUser']['city'])) {
                            $this->request->data['GiftUser']['city'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->GiftUser->User->Company->City->findOrSaveAndGetId($this->request->data['GiftUser']['city']);
                        }
                        $this->GiftUser->User->_createCimProfile($this->Auth->user('id'));
                    }
                } else {
                    $user_details_updated = false;
                }
            }
            if (empty($this->GiftUser->validationErrors) &empty($this->GiftUser->User->City->State->validationErrors) && $user_details_updated && empty($check_expire)) {
                if (!empty($this->request->data['State']['name'])) {
                    $this->request->data['GiftUser']['state'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->GiftUser->User->Company->City->State->findOrSaveAndGetId($this->request->data['State']['name']);
                }
                if (!empty($this->request->data['GiftUser']['city'])) {
                    $this->request->data['GiftUser']['city'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->GiftUser->User->Company->City->findOrSaveAndGetId($this->request->data['GiftUser']['city']);
                }
                if ($this->request->data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet) {
                    if (!empty($this->request->data['GiftUser']['creditCardNumber'])) {
                        $user = $this->GiftUser->User->find('first', array(
                            'conditions' => array(
                                'User.id' => $this->Auth->user('id')
                            ) ,
                            'fields' => array(
                                'User.id',
                                'User.cim_profile_id'
                            )
                        ));
                        //create payment profile
                        $data = $this->request->data['GiftUser'];
                        $data['expirationDate'] = $this->request->data['GiftUser']['expDateYear']['year'] . '-' . $this->request->data['GiftUser']['expDateMonth']['month'];
                        $data['customerProfileId'] = $user['User']['cim_profile_id'];
                        $payment_profile_id = $this->GiftUser->User->_createCimPaymentProfile($data);
                        if (is_array($payment_profile_id) && !empty($payment_profile_id['payment_profile_id']) && !empty($payment_profile_id['masked_cc'])) {
                            $payment['UserPaymentProfile']['user_id'] = $this->Auth->user('id');
                            $payment['UserPaymentProfile']['cim_payment_profile_id'] = $payment_profile_id['payment_profile_id'];
                            $payment['UserPaymentProfile']['masked_cc'] = $payment_profile_id['masked_cc'];
                            $payment['UserPaymentProfile']['is_default'] = 0;
                            $this->GiftUser->User->UserPaymentProfile->save($payment);
                            $this->request->data['GiftUser']['payment_profile_id'] = $payment_profile_id['payment_profile_id'];
                        } else {
                            $this->Session->setFlash(sprintf(__l('Gateway error: %s <br>Note: Due to security reasons, error message from gateway may not be verbose. Please double check your card number, security number and address details. Also, check if you have enough balance in your card.') , $payment_profile_id['message']) , 'default', null, 'error');
                        }
                    }
                    if (!empty($this->request->data['GiftUser']['payment_profile_id'])) {
                        // If enabled, purchase amount is first taken with amount in wallet and then passed to CreditCard //
                        if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
                            $user_available_balance = $this->GiftUser->User->checkUserBalance($this->Auth->user('id'));
                            $amount_needed = $this->request->data['GiftUser']['amount']-$user_available_balance;
                            $is_purchase_with_wallet_amount = 1;
                            $authorizenet_converted_amt = $this->User->_convertAuthorizeAmount($amount_needed); //Convert amount
                            $this->request->data['GiftUser']['amount'] = $amount_needed;
                            $this->request->data['GiftUser']['amount_needed'] = $authorizenet_converted_amt;
                            $this->request->data['GiftUser']['is_purchase_with_wallet_amount'] = $is_purchase_with_wallet_amount;
                            $this->request->data['GiftUser']['original_amount_needed'] = $amount_needed;
                        } else {
                            $authorizenet_converted_amt = $this->User->_convertAuthorizeAmount($this->request->data['User']['amount']); //Convert amount
                            $this->request->data['GiftUser']['amount_needed'] = $authorizenet_converted_amt;
                            $this->request->data['GiftUser']['original_amount_needed'] = $this->request->data['GiftUser']['amount'];
                        }
                        $this->_giftPurchaseViaAuthorizeNet($this->request->data);
                    }
                } elseif (($this->request->data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::CreditCard) || ($this->request->data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::PayPalAuth) || ($this->request->data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::PagSeguro)) {
                    $this->process_gift_user($this->request->data);
                } else {
                    $this->_buyGift($this->request->data);
                }
            } else {
                $this->Session->setFlash(__l('Enter all required data') , 'default', null, 'error');
            }
        } else {
            $this->request->data['GiftUser']['user_id'] = $this->Auth->user('id');
            $this->request->data['GiftUser']['from'] = $this->Auth->user('username');
            $this->request->data['GiftUser']['amount'] = '';
            $this->request->data['GiftUser']['friend_name'] = '';
            $this->request->data['GiftUser']['message'] = '';
            $this->request->data['GiftUser']['is_show_new_card'] = 0;
            //intially merge credit card validation array
            $this->GiftUser->validate = array_merge($this->GiftUser->validate, $this->GiftUser->validateCreditCard);
            $this->GiftUser->User->Company->City->State->validate = array_merge($this->GiftUser->User->Company->City->State->validate, $this->GiftUser->User->Company->City->State->validateStateName);
        }
        // Checking payment settings enabled
        $payment_options = $this->GiftUser->getGatewayTypes('is_enable_for_gift_card');
        // If 'handle like groupon' enabled, unset wallet. Since, all purchase should proceed through wallet first, coz it is compulsary.
        if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
            unset($payment_options[ConstPaymentGateways::Wallet]);
        }
        //credit card related fields
        if (!empty($payment_options[ConstPaymentGateways::CreditCard]) || !empty($payment_options[ConstPaymentGateways::AuthorizeNet])) {
            $gateway_options['cities'] = $this->GiftUser->User->Company->City->find('list', array(
                'conditions' => array(
                    'City.is_approved =' => 1
                ) ,
                'fields' => array(
                    'City.name',
                    'City.name'
                ) ,
                'order' => array(
                    'City.name' => 'asc'
                )
            ));
            $gateway_options['states'] = $this->GiftUser->User->Company->State->find('list', array(
                'conditions' => array(
                    'State.is_approved =' => 1
                ) ,
                'fields' => array(
                    'State.code',
                    'State.name'
                ) ,
                'order' => array(
                    'State.name' => 'asc'
                )
            ));
            $gateway_options['countries'] = $this->GiftUser->User->Company->Country->find('list', array(
                'fields' => array(
                    'Country.iso2',
                    'Country.name'
                ) ,
                'conditions' => array(
                    'Country.iso2 != ' => '',
                ) ,
                'order' => array(
                    'Country.name' => 'asc'
                ) ,
            ));
            $gateway_options['creditCardTypes'] = array(
                'Visa' => __l('Visa') ,
                'MasterCard' => __l('MasterCard') ,
                'Discover' => __l('Discover') ,
                'Amex' => __l('Amex')
            );
            if (!empty($payment_options[ConstPaymentGateways::AuthorizeNet]) && empty($this->request->data['GiftUser']['payment_gateway_id'])) {
                $this->request->data['GiftUser']['payment_gateway_id'] = ConstPaymentGateways::AuthorizeNet;
            } else if (!empty($payment_options[ConstPaymentGateways::CreditCard]) && empty($this->request->data['GiftUser']['payment_gateway_id'])) {
                $this->request->data['GiftUser']['payment_gateway_id'] = ConstPaymentGateways::CreditCard;
            }
        } elseif (!empty($payment_options[ConstPaymentGateways::PayPalAuth])) {
            if (empty($this->request->data['GiftUser']['payment_gateway_id'])) {
                $this->request->data['GiftUser']['payment_gateway_id'] = ConstPaymentGateways::PayPalAuth;
            }
        } elseif (!empty($payment_options[ConstPaymentGateways::Wallet])) {
            if (empty($this->request->data['GiftUser']['payment_gateway_id'])) {
                $this->request->data['GiftUser']['payment_gateway_id'] = ConstPaymentGateways::Wallet;
            }
        }
        $gateway_options['paymentGateways'] = $payment_options;
        if (!$this->Auth->user()) {
            unset($gateway_options['paymentGateways'][ConstPaymentGateways::Wallet]);
        }
        $Paymentprofiles = $this->GiftUser->User->UserPaymentProfile->find('all', array(
            'fields' => array(
                'UserPaymentProfile.masked_cc',
                'UserPaymentProfile.cim_payment_profile_id',
                'UserPaymentProfile.is_default'
            ) ,
            'conditions' => array(
                'UserPaymentProfile.user_id' => $this->Auth->user('id')
            )
        ));
        foreach($Paymentprofiles as $pay_profile) {
            $gateway_options['Paymentprofiles'][$pay_profile['UserPaymentProfile']['cim_payment_profile_id']] = $pay_profile['UserPaymentProfile']['masked_cc'];
            if ($pay_profile['UserPaymentProfile']['is_default']) {
                $this->request->data['GiftUser']['payment_profile_id'] = $pay_profile['UserPaymentProfile']['cim_payment_profile_id'];
            }
        }
        if (empty($gateway_options['Paymentprofiles'])) {
            $this->request->data['GiftUser']['is_show_new_card'] = 1;
        }
        $this->set('gateway_options', $gateway_options);
        $this->request->data['GiftUser']['cvv2Number'] = $this->request->data['GiftUser']['creditCardNumber'] = '';
        $states = $this->GiftUser->User->Company->State->find('list', array(
            'conditions' => array(
                'State.is_approved =' => 1
            ) ,
            'fields' => array(
                'State.code',
                'State.name'
            ) ,
            'order' => array(
                'State.name' => 'asc'
            )
        ));
        $this->set('states', $states);
        $this->set('user', $cur_user);
        $this->set('check_expire', $check_expire);
    }
    public function _giftPurchaseViaAuthorizeNet($data)
    {
        if (!empty($this->request->data)) {
            $cim = $this->GiftUser->User->_getCimObject();
            if (!empty($cim)) {
                $user = $this->GiftUser->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $this->Auth->user('id')
                    ) ,
                    'fields' => array(
                        'User.id',
                        'User.cim_profile_id'
                    )
                ));
				$cim->setParameter('amount', $data['GiftUser']['amount_needed']);
                $cim->setParameter('refId', time());
                $cim->setParameter('customerProfileId', $user['User']['cim_profile_id']);
                $cim->setParameter('customerPaymentProfileId', $data['GiftUser']['payment_profile_id']);
                $title = Configure::read('site.name') . ' - Gift card';
                $description = 'Gift card purchased in ' . Configure::read('site.name');
                // CIM accept only 30 character in title
                if (strlen($title) > 30) {
                    $title = substr($title, 0, 27) . '...';
                }
                $cim->setLineItem($this->Auth->user('id') , $title, $description, 1, $data['GiftUser']['amount_needed']);
                $cim->createCustomerProfileTransaction();
                $response = $cim->getDirectResponse();
                $approval_code = $cim->getAuthCode();
                if (!empty($approval_code) && !empty($response)) {
                    $response_array = explode(',', $response);
                    if ($response_array[0] == 1) {
                        $capture = 1;
                    }
                }
                if (!empty($capture)) {
                    $authorize_currency = $this->getAuthorizeConversionCurrency();
                    $site_currency_id = $authorize_currency['CurrencyConversion']['currency_id'];
                    $converted_currency_id = $authorize_currency['CurrencyConversion']['converted_currency_id'];
                    $conversion_rate = $authorize_currency['CurrencyConversion']['rate'];
                    $data['GiftUser']['coupon_code'] = $this->_uuid();
                    $data['GiftUser']['is_redeemed'] = 0;
                    $data['GiftUser']['from'] = (!empty($data['GiftUser']['from'])) ? $data['GiftUser']['from'] : $this->Auth->user('username');
                    if ($this->GiftUser->save($data)) {
                        $giftusers_id = $this->GiftUser->getLastInsertId();
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['gift_user_id'] = $giftusers_id;
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_response_text'] = $cim->getResponseText();
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_authorization_code'] = $cim->getAuthCode();
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_avscode'] = $cim->getAVSResponse();
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['transactionid'] = $cim->getTransactionID();
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_amt'] = $response_array[9];
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_gateway_feeamt'] = $response[32];
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_cvv2match'] = $cim->getCVVResponse();
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['authorize_response'] = $response;
                        ///////Authorize.net currency conversion////////////////////////
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['currency_id'] = $site_currency_id;
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['converted_currency_id'] = $converted_currency_id;
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['orginal_amount'] = $this->request->data['GiftUser']['original_amount_needed'];
                        $data_authorize_docapture_log['AuthorizenetDocaptureLog']['rate'] = $conversion_rate;
                        $this->GiftUser->User->DealUser->AuthorizenetDocaptureLog->save($data_authorize_docapture_log);
                        $data['Transaction']['user_id'] = $this->Auth->user('id');
                        $data['Transaction']['foreign_id'] = $giftusers_id;
                        $data['Transaction']['class'] = 'GiftUser';
                        $data['Transaction']['amount'] = $data['GiftUser']['user_available_balance'] + $data['GiftUser']['amount'];
                        $data['Transaction']['payment_gateway_id'] = ConstPaymentGateways::AuthorizeNet;
                        $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::GiftSent;
                        $data['Transaction']['currency_id'] = $site_currency_id;
                        $data['Transaction']['converted_currency_id'] = $converted_currency_id;
                        $data['Transaction']['converted_amount'] = $response_array[9]; //Converted amount
                        $data['Transaction']['rate'] = $conversion_rate;
                        $this->GiftUser->User->Transaction->save($data);
                        // If enabled, and after purchase, deduct partial amount from wallet //
                        if (Configure::read('wallet.is_handle_wallet_as_in_groupon') && (!empty($this->request->data['GiftUser']['is_purchase_with_wallet_amount']))) {
                            // Deduct amount ( zero will be updated ) //
                            $user_available_balance = $this->GiftUser->User->checkUserBalance($this->Auth->user('id'));
                            $this->GiftUser->User->updateAll(array(
                                'User.available_balance_amount' => 'User.available_balance_amount -' . $user_available_balance,
                            ) , array(
                                'User.id' => $this->Auth->user('id')
                            ));
                            // Updating Remaining amount //
                            $this->GiftUser->updateAll(array(
                                'GiftUser.amount' => 'GiftUser.amount +' . $user_available_balance,
                            ) , array(
                                'GiftUser.id' => $giftusers_id
                            ));
                            // Update transaction, This is firs transaction, to notify user that partial amount taken from wallet. Second transaction will be updated after deal gets tipped.//
                            if (!empty($user_available_balance) && $user_available_balance != '0.00') {
                                $transaction['Transaction']['user_id'] = $this->Auth->user('id');
                                $transaction['Transaction']['foreign_id'] = $giftusers_id;
                                $transaction['Transaction']['class'] = 'GiftUser';
                                $transaction['Transaction']['amount'] = $user_available_balance;
                                $transaction['Transaction']['transaction_type_id'] = ConstTransactionTypes::PartallyAmountTakenForGiftCardPurchase;
                                $transaction['Transaction']['payment_gateway_id'] = ConstPaymentGateways::Wallet;
                                $this->GiftUser->User->Transaction->log($transaction);
                            }
                        }
                        $this->_send_gift_coupon_mail($data);
                        $this->Session->setFlash(__l('Gift has been sent successfully.') , 'default', null, 'success');
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'my_stuff#My_Gift_Cards'
                        ));
                    }
                } else {
                    $this->Session->setFlash(__l('Gift could not be send. Please, try again.') , 'default', null, 'error');
                }
            }
        }
    }
    public function _send_gift_coupon_mail($giftUser)
    {
        $this->loadModel('EmailTemplate');
        $check_reciever = $this->GiftUser->User->find('first', array(
            'conditions' => array(
                'User.email' => $giftUser['GiftUser']['friend_mail']
            ) ,
            'recursive' => -1
        ));
        $language_code = $this->GiftUser->getUserLanguageIso($check_reciever['User']['id']);
        $template = $this->EmailTemplate->selectTemplate('Gift Coupon', $language_code);
        $emailFindReplace = array(
            '##FROM_EMAIL##' => $this->GiftUser->changeFromEmail(($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from']) ,
            '##SITE_LINK##' => Router::url('/', true) ,
            '##SITE_NAME##' => strtolower(Configure::read('site.name')) ,
            '##HOST_NAME##' => strtolower(Configure::read('site.name')) ,
            '##FRIEND_NAME##' => $giftUser['GiftUser']['friend_name'],
            '##FROM##' => $giftUser['GiftUser']['from'],
            '##TO##' => $giftUser['GiftUser']['friend_name'],
            '##TO_MAIL##' => $giftUser['GiftUser']['friend_mail'],
            '##MESSAGE##' => $giftUser['GiftUser']['message'],
            '##GIFT_CODE##' => $giftUser['GiftUser']['coupon_code'],
            '##GIFT_AMOUNT##' => $giftUser['Transaction']['amount'],
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
                'city' => $this->request->params['named']['city'],
                'admin' => false
            ) , true) ,
        );
        $this->Email->from = ($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from'];
        $this->Email->replyTo = ($template['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $template['reply_to'];
        $this->Email->to = $giftUser['GiftUser']['friend_mail'];
        $this->Email->subject = strtr($template['subject'], $emailFindReplace);
        $this->Email->content = strtr($template['email_content'], $emailFindReplace);
        $this->Email->sendAs = ($template['is_html']) ? 'html' : 'text';
        $this->log('Controller');
        $this->log(strtr($template['email_content'], $emailFindReplace));
        $this->Email->send($this->Email->content);
        // Update AfterSave //
        $this->GiftUser->_updateGiftUserSent($giftUser['GiftUser']['user_id']);
    }
    public function process_gift_user($gift_data)
    {
        $is_purchase_with_wallet_amount = 0;
        $this->loadModel('TempPaymentLog');
        if (!empty($this->request->data)) {
            //payment process
            if ($this->request->data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::CreditCard) {
                $this->_buyGift($this->request->data);
            } else {
                //paypal process
                $amount_needed = $this->request->data['GiftUser']['amount'];
                if ($this->request->data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::PagSeguro) {
                    $payment_gateway_id = ConstPaymentGateways::PagSeguro;
                } else {
                    $payment_gateway_id = ConstPaymentGateways::PayPalAuth;
                }
                $paymentGateway = $this->GiftUser->User->Transaction->PaymentGateway->find('first', array(
                    'conditions' => array(
                        'PaymentGateway.id' => $payment_gateway_id,
                    ) ,
                    'contain' => array(
                        'PaymentGatewaySetting' => array(
                            'fields' => array(
                                'PaymentGatewaySetting.key',
                                'PaymentGatewaySetting.test_mode_value',
                                'PaymentGatewaySetting.live_mode_value',
                            ) ,
                        ) ,
                    ) ,
                    'recursive' => 1
                ));
                $this->pageTitle = __l('Customize Your Gift Card');
                $this->set('gateway_name', $paymentGateway['PaymentGateway']['name']);
                if (empty($paymentGateway)) {
                    throw new NotFoundException(__l('Invalid request'));
                }
                $action = strtolower(str_replace(' ', '', $paymentGateway['PaymentGateway']['name']));
                if ($paymentGateway['PaymentGateway']['name'] == 'PayPal') {
                    Configure::write('paypal.is_testmode', $paymentGateway['PaymentGateway']['is_test_mode']);
                    if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                        foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                            if ($paymentGatewaySetting['key'] == 'payee_account') {
                                Configure::write('paypal.account', $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value']);
                            }
                            if ($paymentGatewaySetting['key'] == 'receiver_emails') {
                                $this->Paypal->paypal_receiver_emails = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                        }
                    }
                    // If enabled, purchase amount is first taken with amount in wallet and then passed to CreditCard //
                    if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
                        $user_available_balance = $this->GiftUser->User->checkUserBalance($this->Auth->user('id'));
                        if (!empty($user_available_balance) && $user_available_balance != '0.00') {
                            $amount_needed = $amount_needed-$user_available_balance;
                            $is_purchase_with_wallet_amount = 1;
                        }
                    }
                    $cmd = '_xclick';
                    //gateway options set
                    // Currency Conversion Process //
                    $get_conversion = $this->_convertAmount($amount_needed);
                    //gateway options set
                    $gateway_options = array(
                        'cmd' => $cmd,
                        'notify_url' => Router::url('/', true) . 'gift_users/processpayment/paypal',
                        'cancel_return' => Router::url('/', true) . 'gift_users/payment_cancel/' . $payment_gateway_id,
                        'return' => Router::url('/', true) . 'gift_users/payment_success/' . $payment_gateway_id,
                        'item_name' => __l('Buy Gift Card') ,
                        'currency_code' => $get_conversion['currency_code'],
                        'amount' => $get_conversion['amount'],
                        'user_defined' => array(
                            'user_id' => $this->Auth->user('id') ,
                            'payment_gateway_id' => $this->request->data['GiftUser']['payment_gateway_id'],
                            'friend_name' => $this->request->data['GiftUser']['friend_name'],
                            'friend_mail' => $this->request->data['GiftUser']['friend_mail'],
                            'message' => $this->request->data['GiftUser']['message'],
                        ) ,
                        'system_defined' => array(
                            'ip' => $this->RequestHandler->getClientIP()
                        ) ,
                        'm_defined' => array(
                            'message' => !empty($this->request->data['GiftUser']['message']) ? $this->request->data['GiftUser']['message'] : '',
                            'amount_needed' => $get_conversion['amount'],
                            'original_amount_needed' => $amount_needed,
                            'currency_code' => $get_conversion['currency_code'],
                        )
                    );
                    $this->set('gateway_options', $gateway_options);
                } else if ($paymentGateway['PaymentGateway']['name'] == 'PagSeguro') {
                    Configure::write('PagSeguro.is_testmode', $paymentGateway['PaymentGateway']['is_test_mode']);
                    if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                        foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                            if ($paymentGatewaySetting['key'] == 'payee_account') {
                                $email = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                        }
                    }
                    // If enabled, purchase amount is first taken with amount in wallet and then passed to PagseGuro //
                    $is_purchase_with_wallet_amount = 0;
                    if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
                        $user_available_balance = $this->GiftUser->User->checkUserBalance($this->Auth->user('id'));
                        if (!empty($user_available_balance) && $user_available_balance != '0.00') {
                            $amount_needed = $amount_needed-$user_available_balance;
                            $is_purchase_with_wallet_amount = 1;
                        }
                    }
                    // Currency Conversion For Pagseguro //
                    $original_amount_needed = $amount_needed; //Convert amount
                    $get_conversion = $this->_convertPagseguroAmount($amount_needed); //Convert amount
                    $amount_needed = $get_conversion['amount'];
                    $ref = time();
                    if (!is_int($amount_needed)) { // Quick fix for float issue with pagse //
                        $gift_amount = $amount_needed*100;
                    } else {
                        $gift_amount = $amount_needed;
                    }
                    //gateway options set
                    $gateway_options['init'] = array(
                        'pagseguro' => array( // Array com informa��es pertinentes ao pagseguro
                            'email' => $email,
                            'type' => 'CBR', // Obrigat�rio passagem para pagseguro:tipo
                            'reference' => $ref, // Obrigat�rio passagem para pagseguro:ref_transacao
                            'freight_type' => 'EN', // Obrigat�rio passagem para pagseguro:tipo_frete
                            'theme' => 1, // Opcional Este parametro aceita valores de 1 a 5, seu efeito � a troca dos bot�es padr�es do pagseguro
                            'currency' => 'BRL', // Obrigat�rio passagem para pagseguro:moeda,
                            'extra' => 0
                            // Um valor extra que voc� queira adicionar no valor total da venda, obs este valor pode ser negativo

                        ) ,
                        'definitions' => array( // Array com informa��es para manusei das informa��es
                            'currency_type' => 'dolar', // Especifica qual o tipo de separador de decimais, suportados (dolar, real)
                            'weight_type' => 'kg', // Especifica qual a medida utilizada para peso, suportados (kg, g)
                            'encode' => 'utf-8'
                            // Especifica o encode n�o implementado

                        ) ,
                        'format' => array(
                            'item_id' => $this->Auth->user('id') ,
                            'item_descr' => __l('gift') ,
                            'item_quant' => '1',
                            'item_valor' => $gift_amount,
                            'item_frete' => '0',
                            'item_peso' => '20'
                        ) ,
                    );
                    $transaction_data = array(
                        'trans_id' => $ref,
                        'payment_method' => 'gift card',
                        'payment_type' => 'gift card',
                        'user_id' => $this->Auth->user('id') ,
                        'payment_gateway_id' => $this->request->data['GiftUser']['payment_gateway_id'],
                        'friend_name' => $this->request->data['GiftUser']['friend_name'],
                        'friend_mail' => $this->request->data['GiftUser']['friend_mail'],
                        'message' => $this->request->data['GiftUser']['message'],
                        'ip' => $this->TempPaymentLog->toSaveIp() ,
                        'amount_needed' => $amount_needed,
                        'currency_code' => Configure::read('paypal.currency_code') ,
                        'is_purchase_with_wallet_amount' => $is_purchase_with_wallet_amount,
                        'original_amount_needed' => $original_amount_needed,
                    );
                    $this->TempPaymentLog->save($transaction_data);
                    $this->set('gateway_options', $gateway_options);
                }
                $this->set('action', $action);
                $this->set('amount', $amount_needed);
				$this->pageTitle = __l('Purchase Gift Card');
                $this->render('do_payment');
            }
        }
    }
    public function processpayment($gateway_name)
    {
        $gateway = array(
            'paypal' => ConstPaymentGateways::PayPalAuth,
            'pagseguro' => ConstPaymentGateways::PagSeguro
        );
        $gateway_id = (!empty($gateway[$gateway_name])) ? $gateway[$gateway_name] : 0;
        $transaction_data = $this->Session->read('transaction_data');
        if (empty($transaction_data) && $gateway_name == 'pagseguro') {
            throw new NotFoundException(__l('Invalid request'));
        }
        $paymentGateway = $this->GiftUser->User->Transaction->PaymentGateway->find('first', array(
            'conditions' => array(
                'PaymentGateway.id' => $gateway_id
            ) ,
            'contain' => array(
                'PaymentGatewaySetting' => array(
                    'fields' => array(
                        'PaymentGatewaySetting.key',
                        'PaymentGatewaySetting.test_mode_value',
                        'PaymentGatewaySetting.live_mode_value',
                    ) ,
                ) ,
            ) ,
            'recursive' => 1
        ));
        switch ($gateway_name) {
            case 'paypal':
                $this->Paypal->initialize($this);
                if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                    foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                        if ($paymentGatewaySetting['key'] == 'payee_account') {
                            $this->Paypal->payee_account = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        if ($paymentGatewaySetting['key'] == 'receiver_emails') {
                            $this->Paypal->paypal_receiver_emails = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                    }
                }
                $this->Paypal->sanitizeServerVars($_POST);
                $this->Paypal->is_test_mode = $paymentGateway['PaymentGateway']['is_test_mode'];
                $this->Paypal->amount_for_item = !empty($this->Paypal->paypal_post_arr['amount']) ? $this->Paypal->paypal_post_arr['amount'] : 0;
                $actual_amount = $this->Paypal->paypal_post_arr['amount_needed'];
                $paid_amount = $this->Paypal->paypal_post_arr['mc_gross'];
                if ($this->Paypal->process() && ($actual_amount == $paid_amount)) {
                    if ($this->Paypal->paypal_post_arr['payment_status'] == 'Completed') {
                        $data['GiftUser']['user_id'] = $this->Paypal->paypal_post_arr['user_id'];
                        $data['GiftUser']['amount'] = $this->Paypal->paypal_post_arr['original_amount_needed'];
                        $data['GiftUser']['gateway_fees'] = $this->Paypal->paypal_post_arr['mc_fee'];
                        $data['GiftUser']['payment_gateway_id'] = $this->Paypal->paypal_post_arr['payment_gateway_id'];
                        $data['GiftUser']['message'] = $this->Paypal->paypal_post_arr['message'];
                        $data['GiftUser']['friend_mail'] = $this->Paypal->paypal_post_arr['friend_mail'];
                        $data['GiftUser']['friend_name'] = $this->Paypal->paypal_post_arr['friend_name'];
                        $data['GiftUser']['original_amount_needed'] = $this->Paypal->paypal_post_arr['original_amount_needed'];
                        $data['GiftUser']['converted_amount'] = $this->Paypal->paypal_post_arr['mc_gross'];
                        if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
                            $data['GiftUser']['is_purchase_with_wallet_amount'] = 1;
                        }
                        $paypal_transaction_log_id = $this->Paypal->logPaypalTransactions();
                        // converted currency changes
                        $get_conversion_cuurency = $this->getConversionCurrency();
                        $this->GiftUser->User->PaypalTransactionLog->updateAll(array(
                            'PaypalTransactionLog.currency_id' => $get_conversion_cuurency['CurrencyConversion']['currency_id'],
                            'PaypalTransactionLog.converted_currency_id' => $get_conversion_cuurency['CurrencyConversion']['converted_currency_id'],
                            'PaypalTransactionLog.orginal_amount' => $this->Paypal->paypal_post_arr['original_amount_needed'],
                            'PaypalTransactionLog.rate' => $get_conversion_cuurency['CurrencyConversion']['rate'],
                        ) , array(
                            'PaypalTransactionLog.id' => $paypal_transaction_log_id
                        ));
                        $this->_buyGift($data);
                    }
                }
                $this->Paypal->logPaypalTransactions();
                break;

            case 'pagseguro':
                $allow_to_process = 1;
                $temp = $this->TempPaymentLog->find('first', array(
                    'conditions' => array(
                        'TempPaymentLog.trans_id' => $this->request->params['named']['order']
                    )
                ));
                $transaction_data = $temp['TempPaymentLog'];
                $verificado = $this->PagSeguro->confirm();
                if ($verificado == 'VERIFICADO') {
                    $allow_to_process = 1;
                    $get_result = $this->PagSeguro->getDataPayment();
                } elseif ($verificado == 'FALSO') {
                    $allow_to_process = 0;
                }
                $paid_amount = $transaction_data['amount_needed'];
                if (!empty($transaction_data) && $allow_to_process) {
                    //add amount to wallet for normal paypal
                    $data['Transaction']['user_id'] = $this->Auth->user('id');
                    $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
                    $data['Transaction']['class'] = 'SecondUser';
                    $data['Transaction']['amount'] = $paid_amount;
                    $data['Transaction']['payment_gateway_id'] = $paymentGateway['PaymentGateway']['id'];
                    $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
                    $transaction_id = $this->GiftUser->User->Transaction->log($data);
                    if (!empty($transaction_id)) {
                        $transaction_id = $transaction_id;
                        $this->GiftUser->User->updateAll(array(
                            'User.available_balance_amount' => 'User.available_balance_amount +' . $paid_amount,
                        ) , array(
                            'User.id' => $this->Auth->user('id')
                        ));
                    }
                    $data['GiftUser']['user_id'] = $transaction_data['user_id'];
                    $data['GiftUser']['amount'] = $paid_amount;
                    $data['GiftUser']['payment_gateway_id'] = $transaction_data['payment_gateway_id'];
                    $data['GiftUser']['message'] = $transaction_data['message'];
                    $data['GiftUser']['friend_mail'] = $transaction_data['friend_mail'];
                    $data['GiftUser']['friend_name'] = $transaction_data['friend_name'];
                    $data['GiftUser']['pagseguro_transaction_log_id'] = $pagseguro_transaction_log_id;
                    $this->Session->delete('return_values');
                    $this->Session->delete('transaction_data');
                    $this->_buyGift($data);
                }
                break;

            default:
                throw new NotFoundException(__l('Invalid request'));
        } // switch
        $this->autoRender = false;
    }
    public function _buyGift($data)
    {
        $is_purchase_with_wallet_amount = 0;
        if (empty($data)) {
            throw new NotFoundException(__l('Invalid request'));
        } else {
            //in paypal process we will not get Auth
            $user = $this->GiftUser->User->find('first', array(
                'conditions' => array(
                    'User.id' => $data['GiftUser']['user_id']
                ) ,
                'fields' => array(
                    'User.available_balance_amount',
                    'User.referred_by_user_id',
                    'User.username',
                    'User.created',
                    'User.email',
                    'User.id'
                ) ,
                'recursive' => -1
            ));
            $paymentGateway = $this->GiftUser->User->Transaction->PaymentGateway->find('first', array(
                'conditions' => array(
                    'PaymentGateway.id' => ConstPaymentGateways::CreditCard,
                ) ,
                'contain' => array(
                    'PaymentGatewaySetting' => array(
                        'fields' => array(
                            'PaymentGatewaySetting.key',
                            'PaymentGatewaySetting.test_mode_value',
                            'PaymentGatewaySetting.live_mode_value',
                        ) ,
                    ) ,
                ) ,
                'recursive' => 1
            ));
            //for credit card doDirectPayment function call in paypal component
            if ($data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::CreditCard) {
                if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                    foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                        if ($paymentGatewaySetting['key'] == 'directpay_API_UserName') {
                            $sender_info['API_UserName'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        if ($paymentGatewaySetting['key'] == 'directpay_API_Password') {
                            $sender_info['API_Password'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        if ($paymentGatewaySetting['key'] == 'directpay_API_Signature') {
                            $sender_info['API_Signature'] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                    }
                }
                // If enabled, purchase amount is first taken with amount in wallet and then passed to CreditCard //
                if (Configure::read('wallet.is_handle_wallet_as_in_groupon')) {
                    $user_available_balance = $this->GiftUser->User->checkUserBalance($this->Auth->user('id'));
					$full_amount =  $data['GiftUser']['amount'];
                    $data['GiftUser']['amount'] = $data['GiftUser']['amount']-$user_available_balance;
                    $is_purchase_with_wallet_amount = 1;
                }
                $get_conversion = $this->_convertAmount($data['GiftUser']['amount']);
                $sender_info['is_testmode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
                $data_credit_card['firstName'] = $data['GiftUser']['firstName'];
                $data_credit_card['lastName'] = $data['GiftUser']['lastName'];
                $data_credit_card['creditCardType'] = $data['GiftUser']['creditCardType'];
                $data_credit_card['creditCardNumber'] = $data['GiftUser']['creditCardNumber'];
                $data_credit_card['expDateMonth'] = $data['GiftUser']['expDateMonth'];
                $data_credit_card['expDateYear'] = $data['GiftUser']['expDateYear'];
                $data_credit_card['cvv2Number'] = $data['GiftUser']['cvv2Number'];
                $data_credit_card['address'] = $data['GiftUser']['address'];
                $data_credit_card['city'] = $data['GiftUser']['city'];
                $data_credit_card['state'] = $data['GiftUser']['state'];
                $data_credit_card['zip'] = $data['GiftUser']['zip'];
                $data_credit_card['country'] = $data['GiftUser']['country'];
                $data_credit_card['paymentType'] = 'Sale';
                $data_credit_card['amount'] = $data['GiftUser']['amount'];
                $data_credit_card['amount'] = $get_conversion['amount'];
                $data_credit_card['currency_code'] = $get_conversion['currency_code'];
                //calling doDirectPayment fn in paypal component
                $payment_response = $this->Paypal->doDirectPayment($data_credit_card, $sender_info);
                //if not success show error msg as it received from paypal
                if (!empty($payment_response) && $payment_response['ACK'] != 'Success') {
                    $this->Session->setFlash(sprintf(__l('%s') , $payment_response['L_LONGMESSAGE0']) , 'default', null, 'error');
                    return;
                }
            }
            $coupon_code = $this->_uuid();
            $data['GiftUser']['coupon_code'] = $coupon_code;
            $data['GiftUser']['is_redeemed'] = 0;
            //$data['GiftUser']['from'] = (!empty($this->request->data['GiftUser']['from'])) ? $this->request->data['GiftUser']['from'] : $data['GiftUser']['from'];
            $data['GiftUser']['from'] = (!empty($this->request->data['GiftUser']['from'])) ? $this->request->data['GiftUser']['from'] : $user['User']['username'];
            if ($this->GiftUser->save($data)) {
                $giftusers_id = $this->GiftUser->getLastInsertId();
                // For Credit Card //
                if (!empty($this->request->data['GiftUser']['payment_gateway_id']) && ($this->request->data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::CreditCard && !empty($payment_response))) {
                    $data_paypal_docapture_log['PaypalDocaptureLog']['authorizationid'] = $payment_response['TRANSACTIONID'];
                    $data_paypal_docapture_log['PaypalDocaptureLog']['gift_user_id'] = $giftusers_id;
                    $data_paypal_docapture_log['PaypalDocaptureLog']['dodirectpayment_correlationid'] = $payment_response['CORRELATIONID'];
                    $data_paypal_docapture_log['PaypalDocaptureLog']['dodirectpayment_ack'] = $payment_response['ACK'];
                    $data_paypal_docapture_log['PaypalDocaptureLog']['dodirectpayment_build'] = $payment_response['BUILD'];
                    $data_paypal_docapture_log['PaypalDocaptureLog']['dodirectpayment_amt'] = $payment_response['AMT'];
                    $data_paypal_docapture_log['PaypalDocaptureLog']['dodirectpayment_avscode'] = $payment_response['AVSCODE'];
                    $data_paypal_docapture_log['PaypalDocaptureLog']['dodirectpayment_cvv2match'] = $payment_response['CVV2MATCH'];
                    $data_paypal_docapture_log['PaypalDocaptureLog']['dodirectpayment_response'] = serialize($payment_response);
                    $data_paypal_docapture_log['PaypalDocaptureLog']['version'] = $payment_response['VERSION'];
                    $data_paypal_docapture_log['PaypalDocaptureLog']['currencycode'] = $payment_response['CURRENCYCODE'];
                    // converted currency changes
                    $get_conversion_cuurency = $this->getConversionCurrency();
                    $data_paypal_docapture_log['PaypalDocaptureLog']['currency_id'] = $get_conversion_cuurency['CurrencyConversion']['currency_id'];
                    $data_paypal_docapture_log['PaypalDocaptureLog']['converted_currency_id'] = $get_conversion_cuurency['CurrencyConversion']['currency_id'];
                    $data_paypal_docapture_log['PaypalDocaptureLog']['original_amount'] = $this->request->data['GiftUser']['amount'];
                    $data_paypal_docapture_log['PaypalDocaptureLog']['rate'] = $get_conversion_cuurency['CurrencyConversion']['rate'];
                    //save do capture log records
                    $this->GiftUser->User->DealUser->PaypalDocaptureLog->save($data_paypal_docapture_log);
                    $data['GiftUser']['converted_amount'] = $payment_response['AMT'];
                }
                $transaction['Transaction']['user_id'] = $data['GiftUser']['user_id'];
                $transaction['Transaction']['foreign_id'] = $giftusers_id;
                $transaction['Transaction']['class'] = 'GiftUser';
                $transaction['Transaction']['amount'] = $full_amount;
                if (!empty($data['GiftUser']['gateway_fees'])) {
                    $transaction['Transaction']['gateway_fees'] = $data['GiftUser']['gateway_fees'];
                }
                $transaction['Transaction']['transaction_type_id'] = ConstTransactionTypes::GiftSent;
                //Currency Conversion Updation //
                $get_conversion = $this->getConversionCurrency();
                if ($data['GiftUser']['payment_gateway_id'] != ConstPaymentGateways::Wallet) {
                    $transaction['Transaction']['currency_id'] = $get_conversion['CurrencyConversion']['currency_id'];
                    $transaction['Transaction']['converted_currency_id'] = $get_conversion['CurrencyConversion']['converted_currency_id'];
                    $transaction['Transaction']['converted_amount'] = $data['GiftUser']['converted_amount'];
                    $transaction['Transaction']['rate'] = $get_conversion['CurrencyConversion']['rate'];
                }
                $transaction['Transaction']['payment_gateway_id'] = $data['GiftUser']['payment_gateway_id'];
                $this->GiftUser->User->Transaction->log($transaction);
                // If enabled, and after purchase, deduct partial amount from wallet //
                if (Configure::read('wallet.is_handle_wallet_as_in_groupon') && (!empty($is_purchase_with_wallet_amount) || !empty($data['GiftUser']['is_purchase_with_wallet_amount']))) {
                    // Deduct amount ( zero will be updated ) //
                    $user_available_balance = $this->GiftUser->User->checkUserBalance($data['GiftUser']['user_id']);
                    $this->GiftUser->User->updateAll(array(
                        'User.available_balance_amount' => 'User.available_balance_amount -' . $user_available_balance,
                    ) , array(
                        'User.id' => $data['GiftUser']['user_id']
                    ));
                    // Updating Remaining amount //
                    $this->GiftUser->updateAll(array(
                        'GiftUser.amount' => 'GiftUser.amount +' . $user_available_balance,
                    ) , array(
                        'GiftUser.id' => $giftusers_id
                    ));
                    // Update transaction, This is firs transaction, to notify user that partial amount taken from wallet. Second transaction will be updated after deal gets tipped.//
                    if (!empty($user_available_balance) && $user_available_balance != '0.00') {
                        $transaction = array();
                        $transaction['Transaction']['user_id'] = $data['GiftUser']['user_id'];
                        $transaction['Transaction']['foreign_id'] = $giftusers_id;
                        $transaction['Transaction']['class'] = 'GiftUser';
                        $transaction['Transaction']['amount'] = $user_available_balance;
                        if (!empty($data['GiftUser']['gateway_fees'])) {
                            $transaction['Transaction']['gateway_fees'] = $data['GiftUser']['gateway_fees'];
                        }
                        $transaction['Transaction']['transaction_type_id'] = ConstTransactionTypes::PartallyAmountTakenForGiftCardPurchase;
                        $transaction['Transaction']['payment_gateway_id'] = ConstPaymentGateways::Wallet;
                        $this->GiftUser->User->Transaction->log($transaction);
                    }
                }
                if (!empty($data['GiftUser']['payment_gateway_id']) && ($data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::Wallet)) {
                    $this->GiftUser->User->updateAll(array(
                        'User.available_balance_amount' => 'User.available_balance_amount -' . $data['GiftUser']['amount'],
                    ) , array(
                        'User.id' => $data['GiftUser']['user_id']
                    ));
                }
                if ($data['GiftUser']['payment_gateway_id'] == ConstPaymentGateways::PagSeguro) {
                    //update gift user id in PaypalTransactionLog table
                    $this->GiftUser->User->DealUser->PagseguroTransactionLog->updateAll(array(
                        'PagseguroTransactionLog.gift_user_id' => $giftusers_id
                    ) , array(
                        'PagseguroTransactionLog.id' => $data['GiftUser']['pagseguro_transaction_log_id']
                    ));
                }
                // Send gift user mail
                $this->_send_gift_coupon_mail($data);
                $this->Session->setFlash(__l('Gift has been sent successfully.') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'my_stuff#My_Gift_Cards'
                ));
            } else {
                $this->Session->setFlash(__l('Gift could not be send. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function payment_success()
    {
        $this->pageTitle = __l('Payment Success');
        $this->Session->setFlash(__l('Gift has been sent successfully.') , 'default', null, 'success');
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'my_stuff#My_Gift_Cards'
        ));
    }
    public function payment_cancel()
    {
        $this->pageTitle = __l('Payment Cancel');
        $this->Session->setFlash(__l('Transaction failure. Please try once again.') , 'default', null, 'error');
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'my_stuff',
            '#My_Transactions'
        ));
    }
    public function resend($gift_id = null)
    {
        if (is_null($gift_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $giftUser = $this->GiftUser->find('first', array(
            'conditions' => array(
                'GiftUser.id' => $gift_id
            ) ,
            'recursive' => -1
        ));
        if (!empty($giftUser)) {
            $this->_send_gift_coupon_mail($giftUser);
            $this->Session->setFlash(sprintf(__l('Gift mail resent successfully.')) , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'my_stuff',
                '#My_Gift_Cards'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function redeem($coupon_code = null)
    {
        if (is_null($coupon_code)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $giftUser = $this->GiftUser->find('first', array(
            'conditions' => array(
                'GiftUser.coupon_code' => $coupon_code,
            ) ,
            'recursive' => -1
        ));
        if (empty($giftUser) || (Configure::read('GiftCard.verify_email_in_redeem')) && $this->Auth->user('email') != $giftUser['GiftUser']['friend_mail']) {
            $this->Session->setFlash(__l('Invalid gift coupon, Please enter the valid coupon code') , 'default', null, 'error');
        } else if ($this->Auth->user('id') == $giftUser['GiftUser']['user_id'] && $this->Auth->user('email') != $giftUser['GiftUser']['friend_mail']) {
            $this->Session->setFlash(__l('You cannot redeem your own gift coupon.') , 'default', null, 'error');
        } else if ($giftUser['GiftUser']['is_redeemed']) {
            $this->Session->setFlash(__l('This Gift Coupon is already redeemed, Please enter the valid coupon code') , 'default', null, 'error');
        } else {
            $transaction['Transaction']['user_id'] = $this->Auth->user('id');
            $transaction['Transaction']['foreign_id'] = $giftUser['GiftUser']['id'];
            $transaction['Transaction']['class'] = 'GiftUser';
            $transaction['Transaction']['amount'] = $giftUser['GiftUser']['amount'];
            $transaction['Transaction']['transaction_type_id'] = ConstTransactionTypes::GiftReceived;
            $this->GiftUser->User->Transaction->log($transaction);
            $this->GiftUser->updateAll(array(
                'GiftUser.is_redeemed' => 1,
                'GiftUser.gifted_to_user_id' => $this->Auth->user('id')
            ) , array(
                'GiftUser.id' => $giftUser['GiftUser']['id']
            ));
            $this->GiftUser->User->updateAll(array(
                'User.available_balance_amount' => 'User.available_balance_amount + ' . $giftUser['GiftUser']['amount'],
            ) , array(
                'User.id' => $this->Auth->user('id')
            ));
            // Update AfterSave //
            $this->GiftUser->_updateGiftUserReceived($this->Auth->user('id'));
            $this->Session->setFlash(sprintf(__l('Gift redeemed successfully.')) , 'default', null, 'success');
        }
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'my_stuff#My_Gift_Cards'
        ));
    }
    public function redeem_gift()
    {
        if (!empty($this->request->data['GiftUser']['coupon_code'])) {
            if (isset($this->request->data['GiftUser']['submit'])) {
                echo 'redirect*' . Router::url(array(
                    'controller' => 'gift_users',
                    'action' => 'redeem',
                    $this->request->data['GiftUser']['coupon_code'],
                ) , true);
                exit;
            } else {
                if (!empty($this->request->data)) {
                    $this->redirect(array(
                        'controller' => 'gift_users',
                        'action' => 'redeem',
                        $this->request->data['GiftUser']['coupon_code'],
                        'admin' => false
                    ));
                }
            }
        } else {
            if (isset($this->request->data['GiftUser']['submit'])) {
                $this->GiftUser->validationErrors['coupon_code'] = __l('Required');
            }
        }
    }
    public function admin_index()
    {
        $this->disableCache();
        $this->pageTitle = __l('Gift Cards');
        $conditions = array();
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'redeemed') {
            $conditions['GiftUser.is_redeemed'] = 1;
            $this->pageTitle.= ' - ' . __l('Redemmed Gift Cards');
        } elseif (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'new') {
            $conditions['GiftUser.is_redeemed'] = 0;
            $this->pageTitle.= ' - ' . __l('New Gift Cards');
        }
        $this->GiftUser->recursive = 2;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    )
                ) ,
                'GiftedToUser' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    )
                )
            ) ,
            'order' => array(
                'GiftUser.id' => 'desc'
            ) ,
        );
        $this->set('giftUsers', $this->paginate());
        $this->set('redeemed', $this->GiftUser->find('count', array(
            'conditions' => array(
                'GiftUser.is_redeemed' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('new_gifts', $this->GiftUser->find('count', array(
            'conditions' => array(
                'GiftUser.is_redeemed' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('pageTitle', $this->pageTitle);
        $moreActions = $this->GiftUser->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_update()
    {
        $this->autoRender = false;
        if (!empty($this->request->data['GiftUser'])) {
            $r = $this->request->data['GiftUser']['r'];
            $actionid = $this->request->data['GiftUser']['more_action_id'];
            unset($this->request->data['GiftUser']['r']);
            unset($this->request->data['GiftUser']['more_action_id']);
            $userIds = array();
            foreach($this->request->data['GiftUser'] as $gift_id => $is_checked) {
                if ($is_checked['id']) {
                    $giftIds[] = $gift_id;
                }
            }
            if ($actionid && !empty($giftIds)) {
                if ($actionid == ConstMoreAction::Delete) {
                    $this->GiftUser->deleteAll(array(
                        'GiftUser.id' => $giftIds
                    ));
                    $this->Session->setFlash(__l('Checked gift cards has been deleted') , 'default', null, 'success');
                }
            }
        }
        if (!$this->RequestHandler->isAjax()) {
            $this->redirect(Router::url('/', true) . $r);
        } else {
            $this->redirect($r);
        }
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->GiftUser->delete($id)) {
            $this->Session->setFlash(__l('Gift deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>