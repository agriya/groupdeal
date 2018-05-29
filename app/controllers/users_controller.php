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
class UsersController extends AppController
{
    public $name = 'Users';
    public $components = array(
        'Email',
        'Openid',
        'Paypal',
        'pagSeguro',
        'OauthConsumer'
    );
    public $helpers = array(
        'Csv',
        'Gateway',
        'pagSeguro',
    );
    public $uses = array(
        'User',
        'Attachment',
        'Subscription'
    );
    public $permanentCacheAction = array(
        'view' => array(
            'is_public_url' => true,
            'is_user_specific_url' => true,
            'is_view_count_update' => true
        )
    );
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'City.id',
            'City.name',
            'State.id',
            'State.name',
            'Company.name',
            'Company.phone',
            'Company.url',
            'Company.address1',
            'Company.address2',
            'Company.country_id',
            'Company.zip',
            'Company.latitude',
            'Company.longitude',
            'Company.map_zoom_level',
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
            'User.profile_image_id',
        );
        parent::beforeFilter();
        $this->disableCache();
    }
    public function view($username = null)
    {
        $this->pageTitle = __l('User');
        if (is_null($username)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.username = ' => $username
            ) ,
            'contain' => array(
                'UserProfile' => array(
                    'fields' => array(
                        'UserProfile.created',
                        'UserProfile.first_name',
                        'UserProfile.last_name',
                        'UserProfile.middle_name',
                        'UserProfile.about_me',
                        'UserProfile.dob',
                        'UserProfile.address',
                        'UserProfile.zip_code',
                        'UserProfile.paypal_account',
                    ) ,
                    'Gender' => array(
                        'fields' => array(
                            'Gender.name'
                        )
                    ) ,
                    'City' => array(
                        'fields' => array(
                            'City.name'
                        )
                    ) ,
                    'Language' => array(
                        'fields' => array(
                            'Language.id',
                            'Language.name'
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.name'
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name'
                        )
                    )
                ) ,
                'UserAvatar' => array(
                    'fields' => array(
                        'UserAvatar.id',
                        'UserAvatar.dir',
                        'UserAvatar.filename',
                        'UserAvatar.width',
                        'UserAvatar.height'
                    )
                )
            ) ,
            'fields' => array(
                'User.id',
                'User.username',
                'User.email',
                'User.user_type_id',
                'User.fb_user_id',
                'User.created'
            ) ,
            'recursive' => 2
        ));
        $statistics = array();
        $statistics['referred_users'] = $this->User->find('count', array(
            'conditions' => array(
                'User.Referred_by_user_id' => $user['User']['id']
            ) ,
            'recursive' => -1
        ));
        $statistics['deal_purchased'] = $this->User->DealUser->find('count', array(
            'conditions' => array(
                'DealUser.user_id' => $user['User']['id'],
    			'DealUser.is_repaid' => 0,
                // 'DealUser.is_gift' => 0

            ) ,
            'recursive' => -1
        ));
        $statistics['gift_sent'] = $this->User->GiftUser->find('count', array(
            'conditions' => array(
                'GiftUser.user_id' => $user['User']['id']
            ) ,
            'recursive' => -1
        ));
        $count_conditions['or'] = array(
            'GiftUser.gifted_to_user_id = ' => $user['User']['id'],
            'GiftUser.friend_mail = ' => $user['User']['email'],
        );
        $statistics['gift_received'] = $this->User->GiftUser->find('count', array(
            'conditions' => $count_conditions,
            'recursive' => -1
        ));
        if (ConstUserFriendType::IsTwoWay) {
            $statistics['user_friends'] = $this->User->UserFriend->find('count', array(
                'conditions' => array(
                    'UserFriend.user_id' => $user['User']['id'],
                    'UserFriend.friend_status_id' => 2,
                    'UserFriend.is_requested' => array(
                        0,
                        1
                    ) ,
                ) ,
                'recursive' => -1
            ));
        } else {
            $statistics['user_friends'] = $this->User->UserFriend->find('count', array(
                'conditions' => array(
                    'UserFriend.user_id' => $user['User']['id'],
                    'UserFriend.friend_status_id' => 2,
                    'UserFriend.is_requested' => 0,
                ) ,
                'recursive' => -1
            ));
        }
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        // To set is this user in current user friends lists
        $friend = $this->User->UserFriend->find('first', array(
            'conditions' => array(
                'UserFriend.user_id' => $this->Auth->user('id') ,
                'UserFriend.friend_user_id' => $user['User']['id'],
                'UserFriend.friend_status_id' => ConstUserFriendStatus::Approved
            ) ,
            'recursive' => -1
        ));
        $this->set('statistics', $statistics);
        $this->set('friend', $friend);
        $this->request->data['UserComment']['user_id'] = $user['User']['id'];
        $this->User->UserView->create();
        $this->request->data['UserView']['user_id'] = $user['User']['id'];
        $this->request->data['UserView']['viewing_user_id'] = $this->Auth->user('id');
        $this->request->data['UserView']['ip_id'] = $this->User->toSaveIp();
        $this->request->data['UserView']['dns'] = gethostbyaddr($this->RequestHandler->getClientIP());
        $this->User->UserView->save($this->request->data);
        $this->pageTitle.= ' - ' . $username;
        $this->set('user', $user);
    }
    public function register($type = null)
    {	
		$captcha_flag=1;
        $this->pageTitle = __l('User Registration');
        $this->loadModel('EmailTemplate');
		$this->Cookie->delete('is_subscribed');
        $temp_country_id = '';
        $fbuser = $this->Session->read('fbuser');
        $user_type_check = $this->Session->read('user_type');
        if (!empty($fbuser['fb_user_id'])) {
			$captcha_flag=0;
            $this->request->data['User']['username'] = $fbuser['username'];
            $this->request->data['User']['email'] = '';
            $this->request->data['User']['fb_user_id'] = $fbuser['fb_user_id'];
            $this->request->data['User']['fb_access_token'] = $fbuser['fb_access_token'];
            $this->request->data['User']['is_facebook_register'] = 1;
            if (!empty($user_type_check) && $user_type_check == 'company') {
                $type = 'company';
            }
            $this->Session->delete('fbuser');
        } else if (empty($this->request->data)) {
			$captcha_flag=0;
            $fb_sess_check = $this->Session->read('fbuser');
            if (Configure::read('facebook.is_enabled_facebook_connect') && !$this->Auth->user() && !empty($fb_sess_check)) {
                // Quick fix for facebook issue //
                $this->_facebook_login();
            }
        }
        // Twitter modified registration: Comes for registration from oauth //
        $twuser = $this->Session->read('twuser');
        if (empty($this->request->data)) {
            if (!empty($twuser)) {
				$captcha_flag=0;
                $this->request->data['User']['username'] = $twuser['username'];
                $this->request->data['User']['email'] = '';
                $this->request->data['User']['twitter_user_id'] = $twuser['twitter_user_id'];
                $this->request->data['User']['twitter_access_token'] = $twuser['twitter_access_token'];
                $this->request->data['User']['twitter_access_key'] = $twuser['twitter_access_key'];
                $this->request->data['User']['twitter_avatar_url'] = $twuser['profile_image_url'];
                $this->request->data['User']['is_twitter_register'] = 1;
                if (Configure::read('invite.is_referral_system_enabled')) {
                    //user id will be set in cookie
                    $cookie_value = $this->Cookie->read('referrer');
                    if (!empty($cookie_value)) {
                        $this->request->data['User']['referred_by_user_id'] = $cookie_value['refer_id'];
                    }
                }
                if (!empty($user_type_check) && $user_type_check == 'company') {
                    $type = 'company';
                }
                $this->Session->delete('twuser');
            }
        }
        // Foursquare modified registration: Comes for registration from fs_oauth //
        $fsuser = $this->Session->read('fsuser');
        if (empty($this->request->data)) {
            if (!empty($fsuser)) {
				$captcha_flag=0;
                $this->request->data['User']['username'] = $fsuser['username'];
                $this->request->data['User']['email'] = $fsuser['email'];
                $this->request->data['User']['foursquare_user_id'] = $fsuser['foursquare_user_id'];
                $this->request->data['User']['foursquare_access_token'] = $fsuser['foursquare_access_token'];
                $this->request->data['User']['is_foursquare_register'] = 1;
                if (!empty($user_type_check) && $user_type_check == 'company') {
                    $type = 'company';
                }
                $this->Session->delete('fsuser');
            }
        }
        //open id component included
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Openid');
        $this->Openid = new OpenidComponent($collection);
        $openid = $this->Session->read('openid');
        if (!empty($openid['openid_url'])) {
			$captcha_flag=0;
            if (isset($openid['email'])) {
                $this->request->data['User']['email'] = $openid['email'];
                $this->request->data['User']['username'] = $openid['username'];
                $this->request->data['User']['openid_url'] = $openid['openid_url'];
                if (!empty($openid['is_gmail_register'])) {
                    $this->request->data['User']['is_gmail_register'] = $openid['is_gmail_register'];
                }
                if (!empty($openid['is_yahoo_register'])) {
                    $this->request->data['User']['is_yahoo_register'] = $openid['is_yahoo_register'];
                }
                if (!empty($user_type_check) && $user_type_check == 'company') {
                    $this->request->data['User']['type'] = $type = 'company';
                }
                $this->Session->delete('openid');
            }
        }
        // handle the fields return from openid
        if ((count($_GET) > 1) && !empty($_GET['openid_identity'])) {
			$captcha_flag=0;
            if (!empty($user_type_check) && $user_type_check == 'company') {
                $type = 'company';
            }
            $returnTo = Router::url(array(
                'controller' => 'users',
                'action' => 'register'
            ) , true);
            $response = $this->Openid->getResponse($returnTo);
            if ($response->status == Auth_OpenID_SUCCESS) {
                // Required Fields
                if ($user = $this->User->UserOpenid->find('first', array(
                    'conditions' => array(
                        'UserOpenid.openid' => $response->identity_url
                    )
                ))) {
                    //Already existing user need to do auto login
                    $this->request->data['User']['email'] = $user['User']['email'];
                    $this->request->data['User']['username'] = $user['User']['username'];
                    $this->request->data['User']['password'] = $user['User']['password'];
                    if ($this->Auth->login($this->request->data)) {
                        $this->setMaxmindInfo('login');
                        $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'my_stuff'
                        ));
                    } else {
                        $this->Session->setFlash($this->Auth->loginError, 'default', null, 'error');
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'login'
                        ));
                    }
                } else {
                    if (Configure::read('referral.referral_enable') && (Configure::read('referral.referral_enabled_option') == ConstReferralOption::GrouponLikeRefer)) {
                        //user id will be set in cookie
                        $cookie_value = $this->Cookie->read('referrer');
                        if (!empty($cookie_value)) {
                            $this->request->data['User']['referred_by_user_id'] = $cookie_value['refer_id'];
                        }
                    }
                    $sregResponse = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
                    $sreg = $sregResponse->contents();
                    $this->request->data['User']['username'] = isset($sreg['nickname']) ? $sreg['nickname'] : '';
                    $this->request->data['User']['email'] = isset($sreg['email']) ? $sreg['email'] : '';
                    $this->request->data['User']['openid_url'] = $response->identity_url;
                }
            } else {
                $this->Session->setFlash(__l('Authenticated failed or you may not have profile in your OpenID account'));
            }
        }
        if (!empty($user_type_check) && $user_type_check == 'company') {
            $this->request->data['Company']['name'] = '';
            $this->request->data['Company']['address1'] = '';
            $this->request->data['Company']['zip'] = '';
            $this->Session->delete('user_type');
        }
        // send to openid function with open id url and redirect page
        if (!empty($this->request->data['User']['openid']) && preg_match('/^http?:\/\/+[a-z]/', $this->request->data['User']['openid'])) {
			$captcha_flag=0;
            $this->User->set($this->request->data);
            unset($this->User->validate[Configure::read('user.using_to_login') ]);
            unset($this->User->validate['passwd']);
            unset($this->User->validate['email']);
            unset($this->User->validate['confirm_password']);
            if ($this->User->validates()) {
                $this->request->data['User']['redirect_page'] = 'register';
                $this->_openid();
            } else {
                $this->Session->setFlash(__l('Your registration process is not completed. Please, try again.') , 'default', null, 'error');
            }
        } else {
  
            if (!empty($this->request->data)) {
            if ($captcha_flag && empty($this->request->data['User']['fb_user_id']) && empty($this->request->data['User']['is_gmail_register']) && empty($this->request->data['User']['is_yahoo_register'])  && empty($this->request->data['User']['openid_url'])) {
                if (Configure::read('system.captcha_type') == "Solve media") {
                	if(!$this->User->_isValidCaptchaSolveMedia()){
					$captcha_error = 1;
				}
                   }
           }
		
            if (empty($captcha_error)){
                if (!empty($this->request->data['User']['type'])) {
                    $type = $this->request->data['User']['type'];
                }
                if (!empty($this->request->data['Company']['country_id'])) {
                    $temp_country_id = $this->request->data['Company']['country_id'];
                    $this->request->data['UserProfile']['country_id'] = $this->request->data['Company']['country_id'] = $this->User->UserProfile->Country->findCountryIdFromIso2($this->request->data['Company']['country_id']);
                }
                if (!empty($this->request->data['State']['name'])) {
                    $this->request->data['Company']['state_id'] = $this->request->data['UserProfile']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->User->Company->State->findOrSaveAndGetId($this->request->data['State']['name']);
                } else {
                    $this->request->data['Company']['state_id'] = 0;
                }
                if (!empty($this->request->data['User']['country_iso_code'])) {
                    $this->request->data['Company']['country_id'] = $this->request->data['UserProfile']['country_id'] = $this->User->UserProfile->Country->findCountryIdFromIso2($this->request->data['User']['country_iso_code']);
                    if (empty($this->request->data['UserProfile']['country_id'])) {
                        unset($this->request->data['UserProfile']['country_id']);
                    }
                }
                if (empty($type)) {
                    if (!empty($_COOKIE['_geo'])) {
                        $_geo = explode('|', $_COOKIE['_geo']);
                        $this->request->data['Company']['latitude'] = $_geo[3];
                        $this->request->data['Company']['longitude'] = $_geo[4];
                    }
                }
                $this->request->data['Company']['latitude'] = (!isset($this->request->data['Company']['latitude'])) ? '' : $this->request->data['Company']['latitude'];
                $this->request->data['Company']['longitude'] = (!isset($this->request->data['Company']['longitude'])) ? '' : $this->request->data['Company']['longitude'];
                if (!empty($this->request->data['City']['name'])) {
                    $this->request->data['Company']['city_id'] = $this->request->data['UserProfile']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->User->Company->City->findOrSaveCityAndGetId($this->request->data['City']['name'], $this->request->data['UserProfile']['state_id'], $this->request->data['Company']['country_id'], $this->request->data['Company']['latitude'], $this->request->data['Company']['longitude']);
                }
                if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false) {
                    $this->request->data['User']['is_iphone_register'] = 1;
                }
                if (stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
                    $this->request->data['User']['is_android_register'] = 1;
                }
                $this->User->set($this->request->data);
                $this->User->UserProfile->set($this->request->data);
                $this->User->Company->set($this->request->data);
                if (!empty($this->request->data['User']['type'])) {
                    $this->User->Company->City->set($this->request->data);
                    $this->User->Company->State->set($this->request->data);
                }
				
                unset($this->User->UserProfile->validate['state_id']);
                if ($this->User->validates() &$this->User->UserProfile->validates() &$this->User->Company->validates() &$this->User->Company->City->validates() &$this->User->Company->State->validates()) {
                    $this->User->create();
                    if (!empty($this->request->data['User']['openid_url']) or !empty($this->request->data['User']['fb_user_id'])) {
                        $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['email'] . Configure::read('Security.salt'));
                        //For open id register no need for email confirm, this will override is_email_verification_for_register setting
                        $this->request->data['User']['is_agree_terms_conditions'] = 1;
                        $this->request->data['User']['is_email_confirmed'] = 1;
                        if (empty($this->request->data['User']['fb_user_id']) && empty($this->request->data['User']['is_gmail_register']) && empty($this->request->data['User']['is_yahoo_register'])) {
                            $this->request->data['User']['is_openid_register'] = 1;
                        }
                    } elseif (!empty($this->request->data['User']['twitter_user_id'])) { // Twitter modified registration: password  -> twitter user id and salt //
                        $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['twitter_user_id'] . Configure::read('Security.salt'));
                        $this->request->data['User']['is_email_confirmed'] = 1;
                    } else {
                        $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['passwd']);
                        $this->request->data['User']['is_email_confirmed'] = (Configure::read('user.is_email_verification_for_register')) ? 0 : 1;
                    }
                    $this->request->data['User']['is_active'] = (Configure::read('user.is_admin_activate_after_register')) ? 0 : 1;
                    $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
                    if ($this->Session->read('gift_user_id')) {
                        $this->request->data['User']['gift_user_id'] = $this->Session->read('gift_user_id');
                        $this->Session->delete('gift_user_id');
                    }
                    $this->request->data['User']['ip_id'] = $this->User->toSaveIp();
                    $this->request->data['User']['dns'] = gethostbyaddr($this->RequestHandler->getClientIP());
                    if (!empty($type)) {
                        $this->request->data['User']['user_type_id'] = ConstUserTypes::Company;
                    }
                    if ($this->User->save($this->request->data, false)) {
                        $this->User->_createCimProfile($this->User->getLastInsertId());
                        if (!empty($type)) {
                            if (!empty($this->request->data['City']['name'])) {
                                $this->request->data['UserProfile']['city_id'] = $this->request->data['Company']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->User->Company->City->findOrSaveAndGetId($this->request->data['City']['name']);
                            }
                            if (!empty($this->request->data['State']['name'])) {
                                $this->request->data['UserProfile']['state_id'] = $this->request->data['Company']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->User->Company->State->findOrSaveAndGetId($this->request->data['State']['name']);
                            } else {
                                $this->request->data['UserProfile']['state_id'] = 0;
                            }
                            $this->request->data['Company']['user_id'] = $this->User->getLastInsertId();
                            $this->request->data['Company']['is_online_account'] = 1;
                            $this->request->data['Company']['is_company_profile_enabled'] = 1;
                            $this->User->Company->create();
                            $this->User->Company->save($this->request->data['Company']);
                            $company_id = $this->User->Company->getLastInsertId();
                        }
                        /// Subscription Flow
                        if (!empty($this->request->data['User']['is_subscribe'])) {
                            $this->request->data['Subscription']['user_id'] = $this->User->getLastInsertId();
                            $this->request->data['Subscription']['email'] = $this->request->data['User']['email'];
                            $sub_city = $this->User->UserProfile->City->find('first', array(
                                'conditions' => array(
                                    'City.slug' => $this->request->params['named']['city'],
                                ) ,
                                'fields' => array(
                                    'City.id',
                                    'City.name',
                                    'City.slug',
                                ) ,
                                'recirsive' => -1
                            ));
                            $this->request->data['Subscription']['city_id'] = $sub_city['City']['id'];
                            $subscription = $this->Subscription->find('first', array(
                                'conditions' => array(
                                    'Subscription.email' => $this->request->data['Subscription']['email'],
                                    'Subscription.city_id' => $this->request->data['Subscription']['city_id']
                                ) ,
                                'fields' => array(
                                    'Subscription.id',
                                    'Subscription.is_subscribed'
                                ) ,
                                'recursive' => -1
                            ));
                            if (empty($subscription)) {
                                $this->Subscription->create();
                                if ($this->Subscription->save($this->request->data)) {
                                    $this->_subscribe($this->request->data, $this->request->data['City']['name'], $this->Subscription->getLastInsertId());
                                }
                            } elseif (!empty($subscription) && !$subscription['Subscription']['is_subscribed']) {
                                $this->request->data['Subscription']['is_subscribed'] = 1;
                                $this->request->data['Subscription']['id'] = $subscription['Subscription']['id'];
                                $this->Subscription->save($this->request->data);
                            }
                        }
                        /// End Subscription Flow
                        $this->request->data['UserProfile']['user_id'] = $this->User->getLastInsertId();
                        $this->User->UserProfile->create();
                        $this->User->UserProfile->save($this->request->data);
                        $this->request->data['UserPermissionPreference']['user_id'] = $this->User->id;
                        $this->User->UserPermissionPreference->create();
                        $this->User->UserPermissionPreference->save($this->request->data);
                        // send to admin mail if is_admin_mail_after_register is true
                        if (Configure::read('user.is_admin_mail_after_register')) {
                            $email = $this->EmailTemplate->selectTemplate('New User Join');
                            $emailFindReplace = array(
                                '##SITE_LINK##' => Router::url('/', true) ,
                                '##USERNAME##' => $this->request->data['User']['username'],
                                '##SITE_NAME##' => Configure::read('site.name') ,
                                '##SIGNUP_IP##' => $this->RequestHandler->getClientIP() ,
                                '##EMAIL##' => $this->request->data['User']['email'],
                                '##CONTACT_URL##' => Router::url(array(
                                    'controller' => 'contacts',
                                    'action' => 'add',
                                    'city' => $this->request->params['named']['city'],
                                    'admin' => false
                                ) , true) ,
                                '##FROM_EMAIL##' => $this->User->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']) ,
                                '##SITE_LOGO##' => Router::url(array(
                                    'controller' => 'img',
                                    'action' => 'blue-theme',
                                    'logo-email.png',
                                    'admin' => false
                                ) , true) ,
                            );
                            // Send e-mail to users
                            $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                            $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                            $this->Email->to = Configure::read('site.contact_email');
                            $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                            $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                            $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                        }
                        $this->Session->setFlash(__l('You have successfully registered with our site.') , 'default', null, 'success');
                        if (!empty($this->request->data['User']['openid_url']) || !empty($this->request->data['User']['fb_user_id'])) {
                            // send welcome mail to user if is_welcome_mail_after_register is true
                            if (Configure::read('user.is_welcome_mail_after_register')) {
                                $this->_sendWelcomeMail($this->User->id, $this->request->data['User']['email'], $this->request->data['User']['username']);
                            }
                            if (empty($this->request->data['User']['fb_user_id'])) {
                                $this->request->data['UserOpenid']['openid'] = $this->request->data['User']['openid_url'];
                                $this->request->data['UserOpenid']['user_id'] = $this->User->id;
                                $this->User->UserOpenid->create();
                                $this->User->UserOpenid->save($this->request->data);
                            }
                            if ($this->Auth->login($this->request->data)) {
                                $this->setMaxmindInfo('login');
                                $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                                if ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
                                    $company = $this->User->Company->find('first', array(
                                        'conditions' => array(
                                            'Company.user_id = ' => $company_id
                                        ) ,
                                        'fields' => array(
                                            'Company.slug',
                                        ) ,
                                        'recursive' => -1
                                    ));
                                    $this->redirect(array(
                                        'controller' => 'companies',
                                        'action' => 'dashboard',
                                        'admin' => false
                                    ));
                                } else {
                                    if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                                        $this->Session->delete('Auth.redirectUrl');
                                        $this->redirect(Router::url('/', true) . $redirectUrl);
                                    } else {
                                        $this->redirect(array(
                                            'controller' => 'users',
                                            'action' => 'my_stuff#My_Purchases'
                                        ));
                                    }
                                }
                            }
                        } else {
                            //For openid register no need to send the activation mail, so this code placed in the else
                            if (Configure::read('user.is_email_verification_for_register')) {
                                $this->Session->setFlash(__l('You have successfully registered with our site and your activation mail has been sent to your mail inbox.') , 'default', null, 'success');
                                $this->_sendActivationMail($this->request->data['User']['email'], $this->User->id, $this->User->getActivateHash($this->User->id));
                            }
                        }
                        // send welcome mail to user if is_welcome_mail_after_register is true
                        if (!Configure::read('user.is_email_verification_for_register') and !Configure::read('user.is_admin_activate_after_register') and Configure::read('user.is_welcome_mail_after_register')) {
                            $this->_sendWelcomeMail($this->User->id, $this->request->data['User']['email'], $this->request->data['User']['username']);
                        }
                        if (!Configure::read('user.is_email_verification_for_register') and Configure::read('user.is_auto_login_after_register')) {
                            $this->Session->setFlash(__l('You have successfully registered with our site.') , 'default', null, 'success');
                            if ($this->Auth->login($this->request->data)) {
                                $this->setMaxmindInfo('login');
                                // Affiliate Changes ( //
                                $cookie_value = $this->Cookie->read('referrer');
                                if (!empty($cookie_value) && (!Configure::read('affiliate.is_enabled'))) {
                                    $this->Cookie->delete('referrer'); // Delete referer cookie

                                }
                                // Affiliate Changes ) //
                                $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                                if ($this->RequestHandler->isAjax()) {
                                    if ($this->layoutPath == 'touch') {
                                        $this->redirect(array(
                                            'controller' => 'deals',
                                            'action' => 'index',
                                            'admin' => false
                                        ));
                                    } else {
                                        echo 'redirect*' . Router::url('/', true) . $this->request->data['User']['f'];
                                        exit;
                                    }
                                } else if (!empty($this->request->data['User']['f'])) {
                                    $this->redirect(Router::url('/', true) . $this->request->data['User']['f']);
                                }
                                if ($this->request->data['User']['user_type_id'] == ConstUserTypes::Company) {
                                    $company = $this->User->Company->find('first', array(
                                        'conditions' => array(
                                            'Company.user_id = ' => $this->Auth->user('id')
                                        ) ,
                                        'fields' => array(
                                            'Company.slug',
                                        ) ,
                                        'recursive' => -1
                                    ));
                                    $this->redirect(array(
                                        'controller' => 'companies',
                                        'action' => 'dashboard',
                                        'admin' => false
                                    ));
                                } else {
                                    $this->redirect(array(
                                        'controller' => 'users',
                                        'action' => 'my_stuff#My_Purchases'
                                    ));
                                }
                            }
                        }
                        if ($this->request->params['isAjax'] == 1) {
                            if ($this->layoutPath == 'touch') {
                                $this->redirect(array(
                                    'controller' => 'deals',
                                    'action' => 'index',
                                    'admin' => false
                                ));
                            } else {
                                $ajax_url = Router::url('/', true) . 'users/login?f=' . $this->request->data['User']['f'];
                                $success_msg = 'redirect*' . $ajax_url;
                                echo $success_msg;
                                exit;
                            }
                        }
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'login'
                        ));
                    }
                } else {
                    $this->request->data['Company']['country_id'] = $temp_country_id;
                    if (empty($this->request->data['User']['openid_url'])) {
                        $this->Session->setFlash(__l('Your registration process is not completed. Please, try again.') , 'default', null, 'error');
                    } else {
                        if (!empty($this->request->data['User']['is_gmail_register'])) {
                            $flash_verfy = 'Gmail';
                        } elseif (!empty($this->request->data['User']['is_yahoo_register'])) {
                            $flash_verfy = 'Yahoo';
                        } else {
                            $flash_verfy = 'OpenID';
                        }
                        $this->Session->setFlash($flash_verfy . ' ' . __l('verification is completed successfully. But you have to fill the following required fields to complete our registration process.') , 'default', null, 'error');
                    }
                }
                }
               else if(isset($captcha_error))
            {
             $this->Session->setFlash(__l('Your registration process is not completed.Enter valid captch value') , 'default', null, 'error');
            }

            }
            
        }
        if (isset($this->request->params['named']['city']) && (empty($type) || Configure::read('user.is_company_actas_normal_user') || $type=="redeem")) {
            $sub_city = $this->User->UserProfile->City->find('first', array(
                'conditions' => array(
                    'City.slug' => $this->request->params['named']['city'],
                ) ,
                'fields' => array(
                    'City.id',
                    'City.name',
                    'City.slug',
                ) ,
                'recirsive' => -1
            ));
            $this->set('subscribe_city', $sub_city['City']['name']);
        }
        if (!empty($this->request->params['named']['f'])) {
            $this->request->data['User']['f'] = $this->request->params['named']['f'];
        }
        if (!empty($this->request->params['requested'])) {
            $this->request->data['User']['is_requested'] = 1;
        }
        unset($this->request->data['User']['passwd']);
        unset($this->User->Company->City->validate['City']);
        // When already logged user trying to access the registration page we are redirecting to site home page
        if ($this->Auth->user()) {
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'my_stuff#My_Purchases'
            ));
        }
        //for user referral system
        if (empty($this->request->data) && Configure::read('referral.referral_enable') && (Configure::read('referral.referral_enabled_option') == ConstReferralOption::GrouponLikeRefer)) {
            //user id will be set in cookie
            $cookie_value = $this->Cookie->read('referrer');
            if (!empty($cookie_value)) {
                $this->request->data['User']['referred_by_user_id'] = $cookie_value['refer_id']; // Affiliate Changes //

            }
        }
        //end
        $countries = $this->User->UserProfile->Country->find('list', array(
            'fields' => array(
                'Country.iso2',
                'Country.name'
            )
        ));
        $this->set('type', $type);
        $this->set(compact('countries'));
        unset($this->request->data['User']['passwd']);
        unset($this->request->data['User']['confirm_password']);
        unset($this->request->data['User']['captcha']);
    }
    public function profile_image($id = null)
    {
        if (!empty($this->request->data['User']['id'])) {
            $id = $this->request->data['User']['id'];
        }
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $id
            ) ,
            'contain' => array(
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
            'recursive' => 0
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = $user['User']['username'] . ' - ' . __l('Profile Image');
        $this->User->UserAvatar->Behaviors->attach('ImageUpload', Configure::read('avatar.file'));
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['UserAvatar']['filename']['name'])) {
                $this->request->data['UserAvatar']['filename']['type'] = get_mime($this->request->data['UserAvatar']['filename']['tmp_name']);
            }
            if (!empty($this->request->data['UserAvatar']['filename']['name']) || (!Configure::read('avatar.file.allowEmpty') && empty($this->request->data['UserAvatar']['id']))) {
                $this->User->UserAvatar->set($this->request->data);
            }
            $ini_upload_error = 1;
            if (isset($this->request->data['UserAvatar']['filename'])) {
                if ($this->request->data['UserAvatar']['filename']['error'] == 1) {
                    $ini_upload_error = 0;
                }
            }
            if ($this->User->UserAvatar->validates() && $ini_upload_error) {
                if (!empty($this->request->data['UserAvatar']['filename']['name'])) {
                    $this->Attachment->delete($user['UserAvatar']['id']);
                    $this->Attachment->create();
                    $this->request->data['UserAvatar']['class'] = 'UserAvatar';
                    $this->request->data['UserAvatar']['foreign_id'] = $this->request->data['User']['id'];
                    $this->Attachment->save($this->request->data['UserAvatar']);
                    $this->request->data['User']['profile_image_id'] = ConstProfileImage::Upload;
                }
                $this->User->save($this->request->data, false);
                if (!empty($this->request->data['User']['profile_image_id'])) {
                    $this->Session->setFlash(__l('User Profile Image has been updated') , 'default', null, 'success');
                }
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
                    $this->redirect(array(
                        'controller' => 'companies',
                        'action' => 'dashboard',
                        'admin' => false
                    ));
                } else {
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'my_stuff',
                        'admin' => false,
                    ));
                }
            }
        } else {
            $this->request->data = $user;
        }
        $profileimage = array(
            ConstProfileImage::Upload => ''
        );
        $this->set('profileimage', $profileimage);
        $profileimage_twitter = array(
            ConstProfileImage::Twitter => '',
        );
        $this->set('profileimage_twitter', $profileimage_twitter);
        $profileimage_facebook = array(
            ConstProfileImage::Facebook => '',
        );
        $this->set('profileimage_facebook', $profileimage_facebook);
        $fb_return_url = Router::url(array(
            'controller' => $this->request->params['named']['city'],
            'action' => 'users',
            'connect',
            $id,
            'admin' => false
        ) , true);
        $this->Session->write('fb_return_url', $fb_return_url);
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        $this->set('fb_login_url', $this->facebook->getLoginUrl(array(
            'redirect_uri' => Router::url(array(
                'controller' => 'users',
                'action' => 'oauth_facebook',
                'admin' => false
            ) , true) ,
            'scope' => 'email,publish_stream'
        )));
        if (!empty($this->request->params['named']['connect']) && $this->request->params['named']['connect'] == 'linked_accounts') {
            $this->render('linked_accounts');
        }
    }
    public function connect($id)
    {
        $this->pageTitle = __l('Connect');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id = ' => $id
            ) ,
            'recursive' => -1,
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->request->data = $user;
        $type = '';
        $c_action = '';
        if (!empty($this->request->params['named']['type'])) {
            $type = $this->request->params['named']['type'];
        }
        if (!empty($this->request->params['named']['c_action'])) {
            $c_action = $this->request->params['named']['c_action'];
        }
        if ($type == 'facebook' && $c_action == 'disconnect') {
            $this->request->data['User']['id'] = $id;
            $this->request->data['User']['fb_user_id'] = '';
            $this->request->data['User']['fb_access_token'] = '';
            $this->User->Save($this->request->data['User'], false);
            $this->Session->setFlash(__l('You have successfully disconnected with facebook.') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'user_profiles',
                'action' => 'my_account',
                $id,
                'admin' => false,
            ));
        }
        if ($type == 'twitter' && $c_action == 'disconnect') {
            $this->request->data['User']['id'] = $id;
            $this->request->data['User']['twitter_user_id'] = '';
            $this->request->data['User']['twitter_access_key'] = '';
            $this->request->data['User']['twitter_access_token'] = '';
            $this->request->data['User']['twitter_avatar_url'] = '';
            $this->User->Save($this->request->data['User'], false);
            $this->Session->setFlash(__l('You have successfully disconnected with twitter.') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'user_profiles',
                'action' => 'my_account',
                $id,
                'admin' => false,
            ));
        } elseif ($type == 'twitter') {
            $requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'http://twitter.com/oauth/request_token');
            $twitter_return_url = Router::url(array(
                'controller' => $this->request->params['named']['city'],
                'action' => 'users',
                'oauth_callback',
                'admin' => false
            ) , true);
            $requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'https://api.twitter.com/oauth/request_token', $twitter_return_url);
            $this->Session->write('requestToken', serialize($requestToken));
            $this->Session->write('auth_user_id', $id);
            $this->redirect('http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
        }
        if (!empty($_GET)) {
            App::import('Vendor', 'facebook/facebook');
            $this->facebook = new Facebook(array(
                'appId' => Configure::read('facebook.app_id') ,
                'secret' => Configure::read('facebook.fb_secrect_key') ,
                'cookie' => true
            ));
            $this->_facebook_login($id);
        }
    }
    public function admin_export($hash = null)
    {
        Configure::write('debug', 0);
        $conditions = array();
        if (isset($this->request->params['named']['from_date']) || isset($this->request->params['named']['to_date'])) {
            $conditions['DATE(User.created) BETWEEN ? AND ? '] = array(
                _formatDate('Y-m-d H:i:s', $this->request->params['named']['from_date'], true) ,
                _formatDate('Y-m-d H:i:s', $this->request->params['named']['to_date'], true)
            );
        }
        if (!empty($this->request->params['named']['main_filter_id'])) {
            if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::OpenID) {
                $conditions['User.is_openid_register'] = 1;
                $this->pageTitle.= __l(' - Registered through OpenID ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::FaceBook) {
                $conditions['User.fb_user_id != '] = NULL;
                $this->pageTitle.= __l(' - Registered through FaceBook ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstUserTypes::User) {
                $conditions['User.user_type_id'] = ConstUserTypes::User;
                $conditions['User.fb_user_id = '] = NULL;
                $conditions['User.is_openid_register'] = 0;
            } else if ($this->request->params['named']['main_filter_id'] == ConstUserTypes::Admin) {
                $conditions['User.user_type_id'] = ConstUserTypes::Admin;
                $this->pageTitle.= __l(' - Admin ');
            } else if ($this->request->params['named']['main_filter_id'] == 'all') {
                $conditions['User.user_type_id != '] = ConstUserTypes::Company;
                $this->pageTitle.= __l(' - All ');
            }
            $count_conditions = $conditions;
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['User.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['User.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 0;
            $this->pageTitle.= __l(' - Registered today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 7;
            $this->pageTitle.= __l(' - Registered in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 30;
            $this->pageTitle.= __l(' - Registered in this month');
        }
        if (!empty($hash) && isset($_SESSION['user_export'][$hash])) {
            $user_ids = implode(',', $_SESSION['user_export'][$hash]);
            if ($this->User->isValidUserIdHash($user_ids, $hash)) {
                $conditions['User.id'] = $_SESSION['user_export'][$hash];
            } else {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        if (isset($this->request->params['named']['q']) && !empty($this->request->params['named']['q'])) {
            $conditions['User.username like'] = '%' . $this->request->params['named']['q'] . '%';
        }
        $users = $this->User->find('all', array(
            'conditions' => $conditions,
            'contain' => array(
                'RefferalUser',
                'Ip'
            ) ,
            'recursive' => 1
        ));
        if (!empty($users)) {
            foreach($users as $user) {
                if ($user['User']['last_logged_in_time'] == '0000-00-00 00:00:00') {
                    $last_logged_in_time = '-';
                } else {
                    $last_logged_in_time = $user['User']['last_logged_in_time'];
                }
                $data[]['User'] = array(
                    __l('Username') => $user['User']['username'],
                    __l('Email') => $user['User']['email'],
                    __l('Available Balance Amount') . '(' . Configure::read('site.currency') . ')' => $user['User']['available_balance_amount'],
                    __l('Purchase Count') => $user['User']['total_deal_purchase_count'],
                    __l('Purchased Amount') . '(' . Configure::read('site.currency') . ')' => $user['User']['total_purchased_amount'],
                    __l('Referred User') => !empty($user['RefferalUser']['username']) ? $user['RefferalUser']['username'] : '-',
                    __l('Registered On') => $user['User']['created'],
                    __l('Logins') => $user['User']['user_login_count'],
                    __l('Signup IP') => $user['User']['signup_ip'],
                    __l('Last Login Time') => $last_logged_in_time,
                );
            }
        }
        $this->set('data', $data);
    }
    public function refer()
    {
        $cookie_value = $this->Cookie->read('referrer');
		$this->Cookie->write('is_subscribed', 1, false);
		$this->js_vars['cfg']['ccity'] = Cache::read('site.default_city', 'long');
        $user_refername = '';
        if (!empty($this->request->params['named']['r'])) {
            $user_refername = $this->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['r']
                ) ,
                'recursive' => -1
            ));
            if (empty($user_refername)) {
                $this->Session->setFlash(__l('Referrer username does not exist.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'register'
                ));
            }
        }
        //cookie value should be empty or same user id should not be over written
        if (!empty($user_refername) && (empty($cookie_value) || (!empty($cookie_value) && (!empty($user_refername)) && ($cookie_value['refer_id'] != $user_refername['User']['id'])))) {
            $this->Cookie->delete('referrer');
            $referrer['refer_id'] = $user_refername['User']['id'];
            $referrer['type'] = 'User';
            $referrer['slug'] = '';
            if (Configure::read('affiliate.is_enabled')) {
                $this->Cookie->write('referrer', $referrer, false, sprintf('+%s hours', Configure::read('affiliate.referral_cookie_expire_time')));
            } else if (Configure::read('invite.is_referral_system_enabled')) {
                $this->Cookie->write('referrer', $referrer, false, sprintf('+%s hours', Configure::read('user.referral_cookie_expire_time')));
            }
            $cookie_value = $this->Cookie->read('referrer');
        }
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'register'
        ));
    }
    public function _openid()
    {
        //open id component included
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Openid');
        $this->Openid = new OpenidComponent($collection);
        $returnTo = Router::url(array(
            'controller' => 'users',
            'action' => $this->request->data['User']['redirect_page']
        ) , true);
        $siteURL = Router::url('/', true);
        // send openid url and fields return to our server from openid
        if (!empty($this->request->data)) {
            try {
                $this->Openid->authenticate($this->request->data['User']['openid'], $returnTo, $siteURL, array(
                    'email',
                    'nickname'
                ) , array());
            }
            catch(InvalidArgumentException $e) {
                $this->Session->setFlash(__l('Invalid OpenID') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
            catch(Exception $e) {
                $this->Session->setFlash(__l($e->getMessage()));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
        }
    }
    public function _sendActivationMail($user_email, $user_id, $hash)
    {
        $this->loadModel('EmailTemplate');
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.email' => $user_email
            ) ,
            'recursive' => -1
        ));
        $email = $this->EmailTemplate->selectTemplate('Activation Request');
        $emailFindReplace = array(
            '##SITE_LINK##' => Router::url('/', true) ,
            '##USERNAME##' => $user['User']['username'],
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##FROM_EMAIL##' => $this->User->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']) ,
            '##ACTIVATION_URL##' => Router::url(array(
                'controller' => 'users',
                'action' => 'activation',
                $user_id,
                $hash
            ) , true) ,
            '##CONTACT_URL##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add',
                'city' => (!empty($this->request->params['named']['city'])) ? $this->request->params['named']['city'] : '',
                'admin' => false
            ) , true) ,
            '##SITE_LOGO##' => Router::url(array(
                'controller' => 'img',
                'action' => 'blue-theme',
                'logo-email.png',
                'admin' => false
            ) , true) ,
        );
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->to = $user_email;
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        if ($this->Email->send(strtr($email['email_content'], $emailFindReplace))) {
            return true;
        }
    }
    public function _sendWelcomeMail($user_id, $user_email, $username)
    {
        $this->loadModel('EmailTemplate');
        $email = $this->EmailTemplate->selectTemplate('Welcome Email');
        $emailFindReplace = array(
            '##SITE_LINK##' => Router::url('/', true) ,
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##FROM_EMAIL##' => $this->User->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']) ,
            '##USERNAME##' => $username,
            '##SUPPORT_EMAIL##' => Configure::read('site.contact_email') ,
            '##SITE_URL##' => Router::url('/', true) ,
            '##CONTACT_URL##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add',
                'city' => $this->request->params['named']['city'],
                'admin' => false
            ) , true) ,
            '##SITE_LOGO##' => Router::url(array(
                'controller' => 'img',
                'action' => 'blue-theme',
                'logo-email.png',
                'admin' => false
            ) , true) ,
        );
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->to = $user_email;
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
    }
    public function activation($user_id = null, $hash = null)
    {
        $this->pageTitle = __l('Activate your account');
        if (is_null($user_id) or is_null($hash)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id,
                'User.is_email_confirmed' => 0,
            ) ,
            'recursive' => -1
        ));
        if (empty($user)) {
            $this->Session->setFlash(__l('Invalid activation request, please register again'));
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'register'
            ));
        }
        if (!$this->User->isValidActivateHash($user_id, $hash)) {
            $hash = $this->User->getActivateHash($user_id);
            $this->Session->setFlash(__l('Invalid activation request'));
            $this->set('show_resend', 1);
            $resend_url = Router::url(array(
                'controller' => 'users',
                'action' => 'resend_activation',
                $user_id,
                $hash
            ) , true);
            $this->set('resend_url', $resend_url);
        } else {
            $this->request->data['User']['id'] = $user_id;
            $this->request->data['User']['is_email_confirmed'] = 1;
            // admin will activate the user condition check
            $this->request->data['User']['is_active'] = (Configure::read('user.is_admin_activate_after_register')) ? 0 : 1;
            $this->User->save($this->request->data);
            // active is false means redirect to home page with message
            if (!$this->request->data['User']['is_active']) {
                $this->Session->setFlash(__l('You have successfully activated your account. But you can login after admin activate your account.') , 'default', null, 'success');
                $this->redirect(Router::url('/', true));
            }
            // send welcome mail to user if is_welcome_mail_after_register is true
            if (Configure::read('user.is_welcome_mail_after_register')) {
                $this->_sendWelcomeMail($user['User']['id'], $user['User']['email'], $user['User']['username']);
            }
            // after the user activation check script check the auto login value. it is true then automatically logged in
            if (Configure::read('user.is_auto_login_after_register')) {
                $this->Session->setFlash(__l('You have successfully activated and logged in to your account.') , 'default', null, 'success');
                $this->request->data['User']['email'] = $user['User']['email'];
                $this->request->data['User']['username'] = $user['User']['username'];
                $this->request->data['User']['password'] = $user['User']['password'];
                if ($this->Auth->login($this->request->data)) {
                    $this->setMaxmindInfo('login');
                    $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                    if ($user['User']['user_type_id'] == ConstUserTypes::Company) {
                        if (!Configure::write('user.is_company_actas_normal_user')) {
                            $company = $this->User->Company->find('first', array(
                                'conditions' => array(
                                    'Company.user_id = ' => $user_id
                                ) ,
                                'fields' => array(
                                    'Company.slug',
                                ) ,
                                'recursive' => -1
                            ));
                            $this->redirect(array(
                                'controller' => 'companies',
                                'action' => 'dashboard',
                                'admin' => false
                            ));
                        } else {
                            $this->redirect(array(
                                'controller' => 'users',
                                'action' => 'my_stuff'
                            ));
                        }
                    } else {
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'my_stuff'
                        ));
                    }
                }
            }
            // user is active but auto login is false then the user will redirect to login page with message
            $this->Session->setFlash(sprintf(__l('You have successfully activated your account. Now you can login with your %s.') , Configure::read('user.using_to_login')) , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
    }
    public function resend_activation($user_id = null, $hash = null)
    {
        if (is_null($user_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $hash = $this->User->getActivateHash($user_id);
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'recursive' => -1
        ));
        if ($this->_sendActivationMail($user['User']['email'], $user_id, $hash)) {
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                $this->Session->setFlash(__l('Activation mail has been resent.') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('A Mail for activating your account has been sent.') , 'default', null, 'success');
            }
        } else {
            $this->Session->setFlash(__l('Try some time later as mail could not be dispatched due to some error in the server') , 'default', null, 'error');
        }
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $this->redirect(array(
                'controller' => (!empty($this->request->params['named']['type'])) ? 'companies' : 'users',
                'action' => 'index',
                'admin' => true
            ));
        } else {
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
    }
    public function _facebook_login($id = null)
    {
        $this->loadModel('EmailTemplate');
        $me = $this->Session->read('fbuser');
        if (empty($me) || empty($me['id'])) {
            $this->Session->setFlash(__l('Problem in Facebook connect. Please try again') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.fb_user_id' => $me['id']
            ) ,
            'fields' => array(
                'User.id',
                'User.email',
                'User.username',
                'User.password',
                'User.fb_user_id',
                'User.is_active',
                'User.user_type_id'
            ) ,
        ));
        if (!empty($id) && !empty($me['id'])) {
            if (!empty($user) && $user['User']['id'] != $this->Auth->user('id')) {
                $this->Session->setFlash(__l('An account already exists with this Facebook Login.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
            $this->User->updateAll(array(
                //'User.fb_access_token' => '\'' . $me['access_token'] . '\'',
                'User.fb_user_id' => '\'' . $me['id'] . '\'',
            ) , array(
                'User.id' => $this->Auth->user('id') ,
            ));
            $this->Session->setFlash(__l('Your profile has been updated') , 'default', null, 'success');
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
                if (!Configure::read('user.is_company_actas_normal_user')) {
                    $this->redirect(array(
                        'controller' => 'companies',
                        'action' => 'dashboard',
                        'admin' => false
                    ));
                } else {
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'my_stuff',
                        'admin' => false
                    ));
                }
            } else {
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'my_stuff',
                    'admin' => false
                ));
            }
        }
        $this->Auth->fields['username'] = 'username';
        //create new user
        if (empty($user)) {
            $this->User->create();
            $this->request->data['UserProfile']['first_name'] = !empty($me['first_name']) ? $me['first_name'] : '';
            $this->request->data['UserProfile']['middle_name'] = !empty($me['middle_name']) ? $me['middle_name'] : '';
            $this->request->data['UserProfile']['last_name'] = !empty($me['last_name']) ? $me['last_name'] : '';
            $this->request->data['UserProfile']['about_me'] = !empty($me['about_me']) ? $me['about_me'] : '';
            if (empty($this->request->data['User']['username']) && strlen($me['first_name']) > 2) {
                $this->request->data['User']['username'] = $this->User->checkUsernameAvailable(strtolower($me['first_name']));
            }
            if (empty($this->request->data['User']['username']) && strlen($me['first_name'] . $me['last_name']) > 2) {
                $this->request->data['User']['username'] = $this->User->checkUsernameAvailable(strtolower($me['first_name'] . $me['last_name']));
            }
            if (empty($this->request->data['User']['username']) && strlen($me['first_name'] . $me['middle_name'] . $me['last_name']) > 2) {
                $this->request->data['User']['username'] = $this->User->checkUsernameAvailable(strtolower($me['first_name'] . $me['middle_name'] . $me['last_name']));
            }
            $this->request->data['User']['username'] = str_replace(' ', '', $this->request->data['User']['username']);
            $this->request->data['User']['username'] = str_replace('.', '_', $this->request->data['User']['username']);
            // A condtion to avoid unavilability of user username in our sites
            if (strlen($this->request->data['User']['username']) <= 2) {
                $this->request->data['User']['username'] = !empty($me['first_name']) ? str_replace(' ', '', strtolower($me['first_name'])) : 'fbuser';
                $i = 1;
                $created_user_name = $this->request->data['User']['username'] . $i;
                while (!$this->User->checkUsernameAvailable($created_user_name)) {
                    $created_user_name = $this->request->data['User']['username'] . $i++;
                }
                $this->request->data['User']['username'] = $created_user_name;
            }
            $this->request->data['User']['email'] = !empty($me['email']) ? $me['email'] : '';
            if (!empty($this->request->data['User']['email'])) {
                $check_user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.email' => $this->request->data['User']['email']
                    ) ,
                    'recursive' => -1
                ));
                $this->request->data['User']['id'] = $check_user['User']['id'];
            }
            $this->request->data['User']['password'] = $this->Auth->password($me['id'] . Configure::read('Security.salt'));
            if (!empty($check_user['User']['email'])) {
                $this->request->data['User']['email'] = $check_user['User']['email'];
                $this->request->data['User']['username'] = $check_user['User']['username'];
                $this->request->data['User']['password'] = $check_user['User']['password'];
            }
            ////////////////////////Admin section Begins//////////////////////////////////////
            if (!empty($check_user['User']['user_type_id']) && $check_user['User']['user_type_id'] == ConstUserTypes::Admin) {
                $this->request->data['User']['user_type_id'] = ConstUserTypes::Admin;
                $this->request->data['User']['fb_user_id'] = $me['id'];
                $this->request->data['User']['fb_access_token'] = $me['access_token'];
                $this->User->save($this->request->data, false);
                if ($this->Auth->login($this->request->data)) {
                    $this->setMaxmindInfo('login');
                    if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                        $this->Session->delete('Auth.redirectUrl');
                        $this->redirect(Router::url('/', true) . $redirectUrl);
                    } else {
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'my_stuff#My_Purchases',
                        ));
                    }
                }
            }
            ////////////////////////Admin section ends//////////////////////////////////////
            if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false) {
                $this->request->data['User']['is_iphone_register'] = 1;
            }
            if (stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
                $this->request->data['User']['is_android_register'] = 1;
            }
            $this->request->data['User']['fb_user_id'] = $me['id'];
            $this->request->data['User']['fb_access_token'] = $me['access_token'];
            $this->request->data['User']['is_agree_terms_conditions'] = '1';
            $this->request->data['User']['is_facebook_register'] = 1;
            $this->request->data['User']['is_email_confirmed'] = 1;
            $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
            $this->request->data['User']['is_active'] = 1;
            $this->request->data['User']['ip_id'] = $this->User->toSaveIp();
            $this->request->data['User']['dns'] = gethostbyaddr($this->RequestHandler->getClientIP());
            if ($this->Session->read('gift_user_id')) {
                $this->request->data['User']['gift_user_id'] = $this->Session->read('gift_user_id');
                $this->Session->delete('gift_user_id');
            }
            //for user referral system
            if (Configure::read('referral.referral_enable') && (Configure::read('referral.referral_enabled_option') == ConstReferralOption::GrouponLikeRefer)) {
                //user id will be set in cookie
                $cookie_value = $this->Cookie->read('referrer');
                if (!empty($cookie_value)) {
                    $this->request->data['User']['referred_by_user_id'] = $cookie_value['refer_id']; // Affiliate changes //

                }
            }
            //end
            // Redirect to registeration for company users to fill other details //
            $user_type_check = $this->Session->read('user_type');
            if (!empty($user_type_check) && $user_type_check == 'company') {
                $temp['first_name'] = $this->request->data['UserProfile']['first_name'];
                $temp['last_name'] = $this->request->data['UserProfile']['last_name'];
                $temp['username'] = $this->request->data['User']['username'];
                $temp['fb_user_id'] = $this->request->data['User']['fb_user_id'];
                $temp['fb_access_token'] = $this->request->data['User']['fb_access_token'];
                $this->Session->write('fbuser', $temp);
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'register'
                ));
            }
            $this->User->save($this->request->data, false);
            $this->setMaxmindInfo('login');
            $this->request->data['UserProfile']['user_id'] = $this->User->id;
            $this->User->UserProfile->save($this->request->data);
            if ($this->Auth->login($this->request->data)) {
                // Affiliate Changes ( //
                $cookie_value = $this->Cookie->read('referrer');
                if (!empty($cookie_value) && (!Configure::read('affiliate.is_enabled'))) {
                    $this->Cookie->delete('referrer'); // Delete referer cookie

                }
                // Affiliate Changes ) //
                $this->Session->setFlash(__l('You have successfully registered with our site.') , 'default', null, 'success');
                // send to admin mail if is_admin_mail_after_register is true
                if (Configure::read('user.is_admin_mail_after_register')) {
                    $email = $this->EmailTemplate->selectTemplate('New User Join');
                    $emailFindReplace = array(
                        '##SITE_LINK##' => Router::url('/', true) ,
                        '##USERNAME##' => $this->request->data['User']['username'],
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##SIGNUP_IP##' => $this->RequestHandler->getClientIP() ,
                        '##EMAIL##' => $this->request->data['User']['email'],
                        '##CONTACT_URL##' => Router::url(array(
                            'controller' => 'contacts',
                            'action' => 'add',
                            'city' => $this->request->params['named']['city'],
                            'admin' => false
                        ) , true) ,
                        '##FROM_EMAIL##' => $this->User->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']) ,
                        '##SITE_LOGO##' => Router::url(array(
                            'controller' => 'img',
                            'action' => 'blue-theme',
                            'logo-email.png',
                            'admin' => false
                        ) , true) ,
                    );
                    // Send e-mail to users
                    $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                    $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                    $this->Email->to = Configure::read('site.contact_email');
                    $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                    $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                    $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                }
                if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                    $this->Session->delete('Auth.redirectUrl');
                    $this->redirect(Router::url('/', true) . $redirectUrl);
                } else {
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'my_stuff#My_Purchases',
                    ));
                }
            }
        } else {
            if (!$user['User']['is_active']) {
                $this->Session->setFlash(__l('Sorry, login failed.  Your account has been blocked') , 'default', null, 'error');
                $this->redirect(Router::url('/', true));
            }
            $this->request->data['User']['fb_user_id'] = $me['id'];
            $this->User->updateAll(array(
                'User.fb_access_token' => '\'' . $me['access_token'] . '\'',
                'User.fb_user_id' => '\'' . $me['id'] . '\'',
            ) , array(
                'User.id' => $user['User']['id']
            ));
            $this->request->data['User']['email'] = $user['User']['email'];
            $this->request->data['User']['username'] = $user['User']['username'];
            $this->request->data['User']['password'] = $user['User']['password'];
            if ($this->Auth->login($this->request->data)) {
                $this->setMaxmindInfo('login');
                $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                    $this->Session->delete('Auth.redirectUrl');
                    $this->redirect(Router::url('/', true) . $redirectUrl);
                } else {
                    if (!empty($user['User']['user_type_id']) && ($user['User']['user_type_id'] == ConstUserTypes::Company)) {
                        $this->redirect(array(
                            'controller' => 'companies',
                            'action' => 'dashboard',
                        ));
                    } else {
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'my_stuff#My_Purchases',
                        ));
                    }
                }
            }
        }
    }
    public function login($username = null)
    {
        if (!is_null($username)) {
            $this->set('username', $username);
        }
        isset($this->request->params['named']['qty']) ? $temp['User']['qty'] = $this->request->params['named']['qty'] : $temp['User']['qty'] = '';
        isset($this->request->params['named']['id']) ? $temp['User']['deal_id'] = $this->request->params['named']['id'] : $temp['User']['deal_id'] = '';
        if (isset($this->request->params['named']['id']) && isset($this->request->params['named']['id'])) {
            $temp['User']['thru_login'] = '1';
            $this->Session->write('fbuser_pymnt', $temp);
        }
        if (!empty($this->request->data) && isset($this->request->data['User']['user_type'])) {
            $this->request->params['named']['user_type'] = $this->request->data['User']['user_type'];
        }
        $fb_sess_check = $this->Session->read('fbuser');
        if (empty($this->request->data) and Configure::read('facebook.is_enabled_facebook_connect') && !$this->Auth->user() && !empty($fb_sess_check) && !$this->Session->check('is_fab_session_cleared')) {
            $this->_facebook_login();
        }
        $this->pageTitle = __l('Login');
        // Foursqaure Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'foursquare' && Configure::read('foursquare.is_enabled_foursquare_connect')) {
            $foursqaure_return_url = Router::url(array(
                'controller' => 'users',
                'action' => 'fs_oauth_callback',
                'admin' => false
            ) , true);
			
			if(stristr($foursqaure_return_url, 'www') === FALSE) { 
				if(stristr($foursqaure_return_url, 'http://m.') != FALSE) {
					$foursqaure_return_url = str_replace("http://m.", "http://",$foursqaure_return_url);
				}
			} else {
				if(stristr($foursqaure_return_url, 'http://www.m.') != FALSE) {
					$foursqaure_return_url = str_replace("http://www.m.", "http://www.",$foursqaure_return_url);
				}
			}
            $client_key = Configure::read('foursquare.consumer_key');
            $client_secret = Configure::read('foursquare.consumer_secret');
            include APP . 'vendors' . DS . 'foursquare' . DS . 'FoursquareAPI.class.php';
            // Load the Foursquare API library
            $foursquare = new FoursquareAPI($client_key, $client_secret);
            $redirect_url = $foursquare->AuthenticationLink($foursqaure_return_url);
            if ($this->Auth->user('user_type_id') == 1) {
                $this->redirect($redirect_url);
            } else {
                $this->set('redirect_url', $redirect_url);
                $this->set('authorize_name', 'foursquare');
                $this->layout = 'redirection';
                $this->pageTitle.= ' - ' . __l('Foursquare');
                $this->render('authorize');
            }
        }
        // Twitter Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'twitter' && Configure::read('twitter.is_enabled_twitter_connect')) {
            $twitter_return_url = Router::url(array(
                'controller' => $this->request->params['named']['city'],
                'action' => 'users',
                'oauth_callback',
                'admin' => false
            ) , true);
            $requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'https://api.twitter.com/oauth/request_token', $twitter_return_url);
            $this->Session->write('requestToken', serialize($requestToken));
            if ($this->Auth->user('user_type_id') == 1) {
                $this->redirect('http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
            } else {
                $this->set('redirect_url', 'http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
                $this->set('authorize_name', 'twitter');
                $this->layout = 'redirection';
                $this->pageTitle.= ' - ' . __l('Twitter');
                $this->render('authorize');
            }
        }
        if (!empty($this->request->params['named']['user_type']) && $this->request->params['named']['user_type'] == 'company') {
            $this->Session->write('user_type', 'company');
        } else {
            if ($this->Session->check('user_type') && empty($_GET['openid_identity'])) {
                $this->Session->delete('user_type');
            }
        }
        // Facebook Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'facebook' && Configure::read('facebook.is_enabled_facebook_connect')) {
            $fb_return_url = Router::url(array(
                'controller' => $this->request->params['named']['city'],
                'action' => 'users',
                'register',
                'admin' => false
            ) , true);
            $this->Session->write('fb_return_url', $fb_return_url);
            $this->set('redirect_url', $this->facebook->getLoginUrl(array(
                'redirect_uri' => Router::url(array(
                    'controller' => 'users',
                    'action' => 'oauth_facebook',
                    'admin' => false
                ) , true) ,
                'scope' => 'email,publish_stream'
            )));
            $this->set('authorize_name', 'facebook');
			if($this->layoutPath != 'touch'){
				$this->layout = 'redirection';
			}	
            $this->pageTitle.= ' - ' . __l('Facebook');
            $this->render('authorize');
        }
        // OpenID validation setting
        if (!empty($this->request->data) && (isset($this->request->data['User']['openid']))) {
            $openidSubmit = 1;
        }
        // yahoo Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'yahoo' && Configure::read('user.is_enable_yahoo_openid')) {
            $this->request->data['User']['email'] = '';
            $this->request->data['User']['password'] = '';
            $this->request->data['User']['redirect_page'] = 'login';
            $this->request->data['User']['openid'] = 'http://yahoo.com/';
            $this->_openid();
        }
        // gmail Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'gmail' && Configure::read('user.is_enable_gmail_openid')) {
            $this->request->data['User']['email'] = '';
            $this->request->data['User']['password'] = '';
            $this->request->data['User']['redirect_page'] = 'login';
            $this->request->data['User']['openid'] = 'https://www.google.com/accounts/o8/id';
            $this->_openid();
        }
        // handle the fields return from openid
        if (!empty($_GET['openid_identity']) && (Configure::read('user.is_enable_openid') || Configure::read('user.is_enable_gmail_openid') || Configure::read('user.is_enable_yahoo_openid'))) {
            $returnTo = Router::url(array(
                'controller' => 'users',
                'action' => 'login'
            ) , true);
            $response = $this->Openid->getResponse($returnTo);
            if ($response->status == Auth_OpenID_SUCCESS) {
                // Required Fields
                if ($user = $this->User->UserOpenid->find('first', array(
                    'conditions' => array(
                        'UserOpenid.openid' => $response->identity_url
                    )
                ))) {
                    //Already existing user need to do auto login
                    $this->request->data['User']['email'] = $user['User']['email'];
                    $this->request->data['User']['username'] = $user['User']['username'];
                    $this->request->data['User']['password'] = $user['User']['password'];
                    if ($this->Auth->login($this->request->data)) {
                        $this->setMaxmindInfo('login');
                        $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                        if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                            $this->Session->delete('Auth.redirectUrl');
                            $this->redirect(Router::url('/', true) . $redirectUrl);
                        } else {
                            if ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
                                $company = $this->User->Company->find('first', array(
                                    'conditions' => array(
                                        'Company.user_id = ' => $this->Auth->user('id')
                                    ) ,
                                    'fields' => array(
                                        'Company.slug',
                                    ) ,
                                    'recursive' => -1
                                ));
                                $this->redirect(array(
                                    'controller' => 'companies',
                                    'action' => 'dashboard',
                                    'admin' => false
                                ));
                            } else {
                                $this->redirect(array(
                                    'controller' => 'deals',
                                    'action' => 'index'
                                ));
                            }
                        }
                    } else {
                        $this->Session->setFlash($this->Auth->loginError, 'default', null, 'error');
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'login'
                        ));
                    }
                } else {
                    $sregResponse = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
                    $sreg = $sregResponse->contents();
                    $temp['username'] = isset($sreg['nickname']) ? $sreg['nickname'] : '';
                    $temp['email'] = isset($sreg['email']) ? $sreg['email'] : '';
                    $temp['openid_url'] = $response->identity_url;
                    $respone_url = $response->identity_url;
                    $respone_url = parse_url($respone_url);
                    if (!empty($respone_url['host']) && $respone_url['host'] == 'www.google.com') {
                        $temp['is_gmail_register'] = 1;
                    } elseif (!empty($respone_url['host']) && $respone_url['host'] == 'me.yahoo.com') {
                        $temp['is_yahoo_register'] = 1;
                    }
                    $this->Session->write('openid', $temp);
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'register'
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Authenticated failed or you may not have profile in your OpenID account'));
            }
        }
        // check open id is given or not
        if ((Configure::read('user.is_enable_openid') || Configure::read('user.is_enable_gmail_openid') || Configure::read('user.is_enable_yahoo_openid')) && isset($this->request->data['User']['openid'])) {
            // Fix for given both email and openid url in login page....@todo
            $this->Auth->logout();
            $this->request->data['User']['email'] = '';
            $this->request->data['User']['password'] = '';
            $this->request->data['User']['redirect_page'] = 'login';
            $this->_openid();
        } else {
            // remember me for user
            if (!empty($this->request->data)) {
                $this->request->data['User'][Configure::read('user.using_to_login') ] = trim($this->request->data['User'][Configure::read('user.using_to_login') ]);
                //Important: For login unique username or email check validation not necessary. Also in login method authentication done before validation.
                unset($this->User->validate[Configure::read('user.using_to_login') ]['rule3']);
                $this->User->set($this->request->data);
                if ($this->User->validates()) {
                    $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['passwd']);
                    if ($this->Auth->login($this->request->data)) {
                        /* Checking IPhone or Andriod User & Setting the Flag */
                        if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') === true || stripos($_SERVER['HTTP_USER_AGENT'], 'Android') === true) {
                            if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') === true && ($this->Auth->user('is_iphone_user') == 0)) {
                                $this->User->updateAll(array(
                                    'User.is_iphone_user' => 1,
                                ) , array(
                                    'User.id' => $this->Auth->user('id')
                                ));
                            } elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Android') === true && ($this->Auth->user('is_android_user') == 0)) {
                                $this->User->updateAll(array(
                                    'User.is_android_user' => 1,
                                ) , array(
                                    'User.id' => $this->Auth->user('id')
                                ));
                            }
                        }
                        $this->setMaxmindInfo('login');
                        $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                        if ($this->Auth->user()) {
                            $this->Session->write('is_normal_login', 1); // fix for user can login with facebook or normal with same account.
                            if (!empty($this->request->data['User']['is_remember']) and $this->request->data['User']['is_remember'] == 1) {
                                $this->Cookie->delete('User');
                                $cookie = array();
                                $remember_hash = md5($this->request->data['User'][Configure::read('user.using_to_login') ] . $this->request->data['User']['password'] . Configure::read('Security.salt'));
                                $cookie['cookie_hash'] = $remember_hash;
                                $this->Cookie->write('User', $cookie, true, $this->cookieTerm);
                                $this->User->updateAll(array(
                                    'User.cookie_hash' => '\'' . md5($remember_hash) . '\'',
                                    'User.cookie_time_modified' => '\'' . date('Y-m-d h:i:s') . '\'',
                                ) , array(
                                    'User.id' => $this->Auth->user('id')
                                ));
                            } else {
                                $this->Cookie->delete('User');
                            }
                            if ($this->RequestHandler->isAjax()) {
                                if (!empty($this->request->data['User']['f'])) {
                                    echo 'redirect*' . Router::url('/', true) . $this->request->data['User']['f'];
                                } else {
                                    if ($this->layoutPath == 'touch') {
                                        $this->redirect(array(
                                            'controller' => 'pages',
                                            'action' => 'display',
                                            'main-menu',
                                            'admin' => false
                                        ));
                                    } else {
                                        echo 'success';
                                    }
                                }
                                exit;
                            } else if (!empty($this->request->data['User']['f'])) {
                                if ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
                                    $company = $this->User->Company->find('first', array(
                                        'conditions' => array(
                                            'Company.user_id = ' => $this->Auth->user('id')
                                        ) ,
                                        'fields' => array(
                                            'Company.slug',
                                        ) ,
                                        'recursive' => -1
                                    ));
                                    $this->redirect(array(
                                        'controller' => 'companies',
                                        'action' => 'dashboard',
                                        'admin' => false
                                    ));
                                } else {
                                    $this->redirect(Router::url('/', true) . $this->request->data['User']['f']);
                                }
                            } else if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                                $this->redirect(array(
                                    'controller' => 'users',
                                    'action' => 'stats',
                                    'admin' => true
                                ));
                            } elseif ($this->Auth->user('user_type_id') == ConstUserTypes::User) {
                                if ($this->layoutPath == 'touch') {
                                    $this->redirect(array(
                                        'controller' => 'pages',
                                        'action' => 'display',
                                        'main-menu',
                                        'admin' => false
                                    ));
                                } else {
                                    $this->redirect(array(
                                        'controller' => 'users',
                                        'action' => 'my_stuff#My_Purchases',
                                        'admin' => false
                                    ));
                                }
                            } elseif ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
                                $company = $this->User->Company->find('first', array(
                                    'conditions' => array(
                                        'Company.user_id = ' => $this->Auth->user('id')
                                    ) ,
                                    'fields' => array(
                                        'Company.slug',
                                    ) ,
                                    'recursive' => -1
                                ));
                                $this->redirect(array(
                                    'controller' => 'companies',
                                    'action' => 'dashboard',
                                    'admin' => false
                                ));
                            }
                        }
                    } else {
                        if (!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
                            $this->Session->setFlash(sprintf(__l('Sorry, login failed.  Your %s or password are incorrect') , Configure::read('user.using_to_login')) , 'default', null, 'error');
                        } else {
                            $this->Session->setFlash($this->Auth->loginError, 'default', null, 'error');
                        }
                    }
                } else {
                    $this->Session->setFlash($this->Auth->loginError, 'default', null, 'error');
                }
            } else {
                if (!empty($this->request->params['named']['f'])) {
                    $this->request->data['User']['f'] = $this->request->params['named']['f'];
                }
                if (!empty($this->request->params['requested'])) {
                    $this->request->data['User']['is_requested'] = 1;
                }
            }
        }
        //When already logged user trying to access the login page we are redirecting to site home page
        if ($this->Auth->user()) {
            $this->redirect(Router::url('/', true));
        }
        $this->request->data['User']['passwd'] = '';
        if (!empty($this->request->data['User']['type']) && $this->request->data['User']['type'] == 'openid') {
            $this->request->params['named']['type'] = 'openid';
        }
        if (!empty($openidSubmit)) {
            if (!empty($this->request->data) && (empty($this->request->data['User']['openid']))) {
                $this->User->validationErrors['openid'] = __l('Required');
            } else {
                $this->User->validationErrors['openid'] = __l('Enter valid OpenID');
            }
            $this->render('login_openid');
        }
        if (!empty($this->request->params['named']['type']) and $this->request->params['named']['type'] == 'openid' && (Configure::read('user.is_enable_openid') || Configure::read('user.is_enable_gmail_openid') || Configure::read('user.is_enable_yahoo_openid'))) {
            $this->render('login_openid');
        }
    }
    public function fs_oauth_callback()
    {
        $this->autoRender = false;
        // Fix to avoid the mail validtion for  Twitter
        $redirect_uri = Router::url(array(
            'controller' => 'users',
            'action' => 'fs_oauth_callback',
            'admin' => false
        ) , true);
        $client_key = Configure::read('foursquare.consumer_key');
        $client_secret = Configure::read('foursquare.consumer_secret');
        include APP . DS . 'vendors' . DS . 'foursquare' . DS . 'FoursquareAPI.class.php';
        // Load the Foursquare API library
        $foursquare = new FoursquareAPI($client_key, $client_secret);
        if (array_key_exists("code", $_GET)) {
            $token = $foursquare->GetToken($_GET['code'], $redirect_uri);
            $foursquare->SetAccessToken($token);
            $user = $foursquare->GetMyDetail('users/self');
            $user = json_decode($user);
            //print_r($user->response->user);
            $fs_user_id = $user->response->user->id;
            $fs_user_firstName = $user->response->user->firstName;
            $fs_user_lastname = $user->response->user->last;
            $fs_user_email = $user->response->user->contact->email;
            $data['User']['name'] = $fs_user_firstName . $fs_user_lastname;
            $this->request->data['User']['foursquare_access_token'] = (isset($token)) ? $token : '';
            $this->request->data['User']['foursquare_user_id'] = (isset($fs_user_id)) ? $fs_user_id : '';
            // So this to check whether it is  admin login to get its foursquare acces tocken
            if ($this->Auth->user('id') and $this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                App::import('Model', 'Setting');
                $setting = new Setting;
                $setting->updateAll(array(
                    'Setting.value' => "'" . $this->request->data['User']['foursquare_access_token'] . "'",
                ) , array(
                    'Setting.name' => 'foursquare.site_user_access_token'
                ));
                $setting->updateAll(array(
                    'Setting.value' => "'" . $this->request->data['User']['foursquare_user_id'] . "'"
                ) , array(
                    'Setting.name' => 'foursquare.site_user_fs_id'
                ));
                $this->Session->setFlash(__l('Your Foursquare credentials are updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'settings',
                    'admin' => true
                ));
            }
            if ($this->Auth->user('id')) {
                $check_foursquare_user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.foursquare_user_id' => $this->request->data['User']['foursquare_user_id']
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($check_foursquare_user) && $check_foursquare_user['User']['id'] != $this->Auth->user('id')) {
                    $this->Session->setFlash(__l('An account already exists with this Foursquare Login.') , 'default', null, 'error');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login'
                    ));
                }
                $this->User->updateAll(array(
                    'User.foursquare_user_id' => "'" . $this->request->data['User']['foursquare_user_id'] . "'",
                    'User.foursquare_access_token' => "'" . $this->request->data['User']['foursquare_access_token'] . "'",
                ) , array(
                    'User.id' => $this->Auth->user('id') ,
                ));
                $this->Session->setFlash(__l('Your profile has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'my_stuff',
                    'admin' => false
                ));
            }
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.foursquare_user_id =' => $this->request->data['User']['foursquare_user_id']
                ) ,
                'fields' => array(
                    'User.id',
                    'UserProfile.id',
                    'User.user_type_id',
                    'User.username',
                    'User.email',
                ) ,
                'recursive' => 0
            ));
            if (empty($user)) {
                // Foursquare modified registration: Prompts for email after regisration. Redirects to register method //
                $user_type_check = $this->Session->read('user_type');
                if (!empty($user_type_check) && $user_type_check == 'company') {
                    $temp['first_name'] = !empty($fs_user_firstName) ? $fs_user_firstName : '';
                    $temp['last_name'] = !empty($fs_user_lastName) ? $fs_user_lastName : '';
                    $temp['username'] = $this->genreteFSName($data); // Foursquare modified registration: Generate autoname from this method //
                    $temp['foursquare_user_id'] = !empty($fs_user_id) ? $fs_user_id : '';
                    $temp['email'] = !empty($fs_user_email) ? $fs_user_email : '';
                    $temp['foursquare_access_token'] = (isset($token)) ? $token : '';
                    $this->Session->write('fsuser', $temp);
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'register'
                    ));
                }
                if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false) {
                    $this->request->data['User']['is_iphone_register'] = 1;
                }
                if (stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
                    $this->request->data['User']['is_android_register'] = 1;
                }
                $this->request->data['User']['email'] = $fs_user_email;
                $this->request->data['User']['is_foursquare_register'] = 1;
                $this->request->data['User']['is_email_confirmed'] = 1;
                $this->request->data['User']['is_active'] = 1;
                $this->request->data['User']['is_agree_terms_conditions'] = '1';
                $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
                $this->request->data['User']['ip_id'] = $this->User->toSaveIp();
                $this->request->data['User']['pin'] = ($fs_user_id+Configure::read('user.pin_formula')) %10000;
                $this->request->data['User']['foursquare_user_id'] = $fs_user_id;
                $this->request->data['User']['foursquare_access_token'] = $token;
                $created_user_name = $this->User->checkUsernameAvailable($data['User']['name']);
                if (strlen($created_user_name) <= 2) {
                    $this->request->data['User']['username'] = !empty($data['User']['name']) ? $data['User']['name'] : 'fsuser';
                    $i = 1;
                    $created_user_name = $this->request->data['User']['username'] . $i;
                    while (!$this->User->checkUsernameAvailable($created_user_name)) {
                        $created_user_name = $this->request->data['User']['username'] . $i++;
                    }
                }
                $this->request->data['User']['username'] = $created_user_name;
                if (!empty($this->request->data['User']['email'])) {
                    $check_user = $this->User->find('first', array(
                        'conditions' => array(
                            'User.email' => $this->request->data['User']['email']
                        ) ,
                        'recursive' => -1
                    ));
                    $this->request->data['User']['id'] = $check_user['User']['id'];
                }
                if (!empty($check_user['User']['email'])) {
                    $this->request->data['User']['email'] = $check_user['User']['email'];
                    $this->request->data['User']['username'] = $check_user['User']['username'];
                    $this->request->data['User']['password'] = $check_user['User']['password'];
                }
                ////////////////////////Admin section Begins//////////////////////////////////////
                if (!empty($check_user['User']['user_type_id']) && $check_user['User']['user_type_id'] == ConstUserTypes::Admin) {
                    $this->request->data['User']['user_type_id'] = ConstUserTypes::Admin;
                    $this->request->data['User']['foursquare_user_id'] = $fs_user_id;
                    $this->request->data['User']['foursquare_access_token'] = $token;
                    $this->User->save($this->request->data, false);
                    if ($this->Auth->login($this->request->data)) {
                        $this->setMaxmindInfo('login');
                        if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                            $this->Session->delete('Auth.redirectUrl');
                            $this->redirect(Router::url('/', true) . $redirectUrl);
                        } else {
                            $this->redirect(array(
                                'controller' => 'users',
                                'action' => 'my_stuff#My_Purchases',
                            ));
                        }
                    }
                }
                ////////////////////////Admin section ends//////////////////////////////////////

            } else {
                $this->request->data['User']['id'] = $user['User']['id'];
                $this->request->data['User']['username'] = $user['User']['username'];
            }
            unset($this->User->validate['username']['rule2']);
            unset($this->User->validate['username']['rule3']);
            $this->request->data['User']['password'] = $this->Auth->password($data['User']['id'] . Configure::read('Security.salt'));
            //$this->request->data['User']['twitter_url'] = (isset($data['User']['url'])) ? $data['User']['url'] : '';
            $this->request->data['User']['description'] = (isset($data['User']['description'])) ? $data['User']['description'] : '';
            $this->request->data['User']['location'] = (isset($data['User']['location'])) ? $data['User']['location'] : '';
            // Affiliate Changes ( //
            if (Configure::read('referral.referral_enable') && (Configure::read('referral.referral_enabled_option') == ConstReferralOption::GrouponLikeRefer)) {
                //user id will be set in cookie
                $cookie_value = $this->Cookie->read('referrer');
                if (!empty($cookie_value)) {
                    $this->request->data['User']['referred_by_user_id'] = $cookie_value['refer_id'];
                }
            }
            // Affiliate Changes ) //
            if ($this->User->save($this->request->data, false)) {
                $cookie_value = $this->Cookie->read('referrer');
                if (!empty($cookie_value) && (!Configure::read('affiliate.is_enabled'))) {
                    $this->Cookie->delete('referrer'); // Delete referer cookie

                }
                if ($this->Auth->login($this->request->data)) {
                    $this->setMaxmindInfo('login');
                    $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                    if (!empty($user['User']['user_type_id']) && ($user['User']['user_type_id'] == ConstUserTypes::Company)) {
                        $this->redirect(array(
                            'controller' => 'companies',
                            'action' => 'dashboard',
                        ));
                    } else {
                        $this->redirect(array(
                            'controller' => 'deals',
                            'action' => 'index',
                        ));
                    }
                }
            }
            $this->redirect(Router::url('/', true));
        }
    }
    public function oauth_callback()
    {
        App::import('Xml');
        $this->autoRender = false;
        // Fix to avoid the mail validtion for  Twitter
        $this->Auth->fields['username'] = 'username';
        $requestToken = $this->Session->read('requestToken');
        $requestToken = unserialize($requestToken);
        $accessToken = $this->OauthConsumer->getAccessToken('Twitter', 'https://api.twitter.com/oauth/access_token', $requestToken);
        $this->Session->write('accessToken', $accessToken);
        $oauth_xml = $this->OauthConsumer->get('Twitter', $accessToken->key, $accessToken->secret, 'https://api.twitter.com/1/account/verify_credentials.xml');
        $this->request->data['User']['twitter_access_token'] = (isset($accessToken->key)) ? $accessToken->key : '';
        $this->request->data['User']['twitter_access_key'] = (isset($accessToken->secret)) ? $accessToken->secret : '';
        $data = Xml::toArray(Xml::build($oauth_xml['body']));
        // Modifying array index for existing code //
        $data['User'] = $data['user'];
        unset($data['user']);
        if (empty($data['User']['id'])) {
            $this->Session->setFlash(__l('Problem in Twitter connect. Please try again') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
        // So this to check whether it is  admin login to get its twiiter acces tocken
        if ($this->Auth->user('id') and $this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            if (!empty($this->request->params['named']['city_to_update']) && !empty($this->request->data['User']['twitter_access_key'])) {
                $this->request->data['User']['twitter_username'] = $data['User']['name'];
                $this->request->data['User']['twitter_user_id'] = $data['User']['id'];
                $this->request->data['User']['city_to_update'] = $this->request->params['named']['city_to_update'];
                $this->Session->write('tw_city_data', $this->request->data['User']);
                $this->redirect(array(
                    'controller' => 'cities',
                    'action' => 'tw_update',
                    'admin' => false
                ));
            }
            App::import('Model', 'Setting');
            $setting = new Setting;
            $setting->updateAll(array(
                'Setting.value' => "'" . $this->request->data['User']['twitter_access_token'] . "'",
            ) , array(
                'Setting.name' => 'twitter.site_user_access_token'
            ));
            $setting->updateAll(array(
                'Setting.value' => "'" . $this->request->data['User']['twitter_access_key'] . "'"
            ) , array(
                'Setting.name' => 'twitter.site_user_access_key'
            ));
            $this->Session->setFlash(__l('Your Twitter credentials are updated') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'settings',
                'admin' => true
            ));
        }
        if ($this->Auth->user('id')) {
            $check_twitter_user = $this->User->find('first', array(
                'conditions' => array(
                    'User.twitter_user_id' => $data['User']['id']
                ) ,
                'recursive' => -1
            ));
            if (!empty($check_twitter_user) && $check_twitter_user['User']['id'] != $this->Auth->user('id')) {
                $this->Session->setFlash(__l('An account already exists with this Twitter Login.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
            $this->User->updateAll(array(
                'User.twitter_user_id' => "'" . $data['User']['id'] . "'",
                'User.twitter_access_token' => "'" . $this->request->data['User']['twitter_access_token'] . "'",
                'User.twitter_access_key' => "'" . $this->request->data['User']['twitter_access_key'] . "'",
                'User.twitter_avatar_url' => "'" . $data['User']['profile_image_url'] . "'",
            ) , array(
                'User.id' => $this->Auth->user('id') ,
            ));
            $this->Session->setFlash(__l('Your profile has been updated') , 'default', null, 'success');
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
                if (!Configure::read('user.is_company_actas_normal_user')) {
                    $this->redirect(array(
                        'controller' => 'companies',
                        'action' => 'dashboard',
                        'admin' => false
                    ));
                } else {
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'my_stuff',
                        'admin' => false
                    ));
                }
            } else {
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'my_stuff',
                    'admin' => false
                ));
            }
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.twitter_user_id =' => $data['User']['id']
            ) ,
            'fields' => array(
                'User.id',
                'UserProfile.id',
                'User.user_type_id',
                'User.username',
                'User.email',
            ) ,
            'recursive' => 0
        ));
        if (empty($user)) {
            // Twitter modified registration: Prompts for email after regisration. Redirects to register method //
            $user_type_check = $this->Session->read('user_type');
            if (!empty($user_type_check) && $user_type_check == 'company') {
                $temp['first_name'] = !empty($data['User']['name']) ? $data['User']['name'] : '';
                $temp['last_name'] = !empty($data['User']['name']) ? $data['User']['name'] : '';
                $temp['username'] = $this->genreteTWName($data); // Twitter modified registration: Generate autoname from this method //
                $temp['twitter_user_id'] = !empty($data['User']['id']) ? $data['User']['id'] : '';
                $temp['twitter_access_token'] = (isset($accessToken->key)) ? $accessToken->key : '';
                $temp['twitter_access_key'] = (isset($accessToken->secret)) ? $accessToken->secret : '';
                $temp['profile_image_url'] = $data['User']['profile_image_url'];
                $this->Session->write('twuser', $temp);
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'register'
                ));
            }
            if (Configure::read('twitter.prompt_for_email_after_register')) {
                $temp['first_name'] = !empty($data['User']['name']) ? $data['User']['name'] : '';
                $temp['last_name'] = !empty($data['User']['name']) ? $data['User']['name'] : '';
                $temp['username'] = $this->genreteTWName($data); // Twitter modified registration: Generate autoname from this method //
                $temp['twitter_user_id'] = !empty($data['User']['id']) ? $data['User']['id'] : '';
                $temp['twitter_access_token'] = (isset($accessToken->key)) ? $accessToken->key : '';
                $temp['twitter_access_key'] = (isset($accessToken->secret)) ? $accessToken->secret : '';
                $temp['profile_image_url'] = $data['User']['profile_image_url'];
                $this->Session->write('twuser', $temp);
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'register'
                ));
            } else {
                $this->request->data['User']['is_twitter_register'] = 1;
                $this->request->data['User']['is_email_confirmed'] = 1;
                $this->request->data['User']['is_active'] = 1;
                $this->request->data['User']['is_agree_terms_conditions'] = '1';
                $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
                $this->request->data['User']['ip_id'] = $this->User->toSaveIp();
                $this->request->data['User']['pin'] = ($data['User']['id']+Configure::read('user.pin_formula')) %10000;
                $this->request->data['User']['twitter_user_id'] = $data['User']['id'];
                $this->request->data['User']['twitter_avatar_url'] = $data['User']['profile_image_url'];
                $created_user_name = $this->User->checkUsernameAvailable($data['User']['screen_name']);
                if (strlen($created_user_name) <= 2) {
                    $this->request->data['User']['username'] = !empty($data['User']['screen_name']) ? $data['User']['screen_name'] : 'twuser';
                    $i = 1;
                    $created_user_name = $this->request->data['User']['username'] . $i;
                    while (!$this->User->checkUsernameAvailable($created_user_name)) {
                        $created_user_name = $this->request->data['User']['username'] . $i++;
                    }
                }
                $this->request->data['User']['username'] = $created_user_name;
            }
        } else {
            $this->request->data['User']['id'] = $user['User']['id'];
            $this->request->data['User']['username'] = $user['User']['username'];
        }
        if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false) {
            $this->request->data['User']['is_iphone_register'] = 1;
        }
        if (stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false) {
            $this->request->data['User']['is_android_register'] = 1;
        }
        unset($this->User->validate['username']['rule2']);
        unset($this->User->validate['username']['rule3']);
        $this->request->data['User']['password'] = $this->Auth->password($data['User']['id'] . Configure::read('Security.salt'));
        $this->request->data['User']['avatar_url'] = $data['User']['profile_image_url'];
        $this->request->data['User']['twitter_url'] = (isset($data['User']['url'])) ? $data['User']['url'] : '';
        $this->request->data['User']['description'] = (isset($data['User']['description'])) ? $data['User']['description'] : '';
        $this->request->data['User']['location'] = (isset($data['User']['location'])) ? $data['User']['location'] : '';
        // Affiliate Changes ( //
        if (Configure::read('referral.referral_enable') && (Configure::read('referral.referral_enabled_option') == ConstReferralOption::GrouponLikeRefer)) {
            //user id will be set in cookie
            $cookie_value = $this->Cookie->read('referrer');
            if (!empty($cookie_value)) {
                $this->request->data['User']['referred_by_user_id'] = $cookie_value['refer_id'];
            }
        }
        // Affiliate Changes ) //
        if ($this->User->save($this->request->data, false)) {
            $cookie_value = $this->Cookie->read('referrer');
            if (!empty($cookie_value) && (!Configure::read('affiliate.is_enabled'))) {
                $this->Cookie->delete('referrer'); // Delete referer cookie

            }
            if ($this->Auth->login($this->request->data)) {
                $this->setMaxmindInfo('login');
                $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                if (!empty($user['User']['user_type_id']) && ($user['User']['user_type_id'] == ConstUserTypes::Company)) {
                    $this->redirect(array(
                        'controller' => 'companies',
                        'action' => 'dashboard',
                    ));
                } else {
                    $this->redirect(array(
                        'controller' => 'deals',
                        'action' => 'index',
                    ));
                }
            }
        }
        if (!empty($this->request->data['User']['f'])) {
            $this->redirect(Router::url('/', true) . $this->request->data['User']['f']);
        }
        $this->redirect(Router::url('/', true));
    }
    // Twitter modified registration: Generate autoname from this method //
    public function genreteTWName($data)
    {
        $created_user_name = $this->User->checkUsernameAvailable($data['User']['screen_name']);
        if (strlen($created_user_name) <= 2) {
            $this->request->data['User']['username'] = !empty($data['User']['screen_name']) ? $data['User']['screen_name'] : 'twuser';
            $i = 1;
            $created_user_name = $this->request->data['User']['username'] . $i;
            while (!$this->User->checkUsernameAvailable($created_user_name)) {
                $created_user_name = $this->request->data['User']['username'] . $i++;
            }
        }
        return $created_user_name;
    }
    public function genreteFSName($data)
    {
        $created_user_name = $this->User->checkUsernameAvailable($data['User']['name']);
        if (strlen($created_user_name) <= 2) {
            $this->request->data['User']['username'] = !empty($data['User']['name']) ? $data['User']['name'] : 'fsuser';
            $i = 1;
            $created_user_name = $this->request->data['User']['username'] . $i;
            while (!$this->User->checkUsernameAvailable($created_user_name)) {
                $created_user_name = $this->request->data['User']['username'] . $i++;
            }
        }
        return $created_user_name;
    }
    public function logout()
    {
        if ($this->Auth->user('fb_user_id')) {
            //$this->facebook->setSession(); // Quick fix for facebook redirect loop issue.
            $this->Session->write('is_fab_session_cleared', 1); // Quick fix for facebook redirect loop issue.
            $this->Session->delete('fbuser'); // Quick fix for facebook redirect loop issue.

        }
        $this->Session->delete('is_normal_login');
        $this->Auth->logout();
        $this->Cookie->delete('User');
        $this->Cookie->delete('user_language');
        $this->Session->setFlash(__l('You are now logged out of the site.') , 'default', null, 'success');
        $this->Session->delete('fbuser_pymnt');
        $this->User->CkSession->clear_session($this->Session->id());
        $redirect_url = array(
            'controller' => 'users',
            'action' => 'login'
        );
        if (!empty($this->request->params['named']['city'])) {
            $redirect_url['city'] = $this->request->params['named']['city'];
        }
        $this->redirect($redirect_url);
    }
    public function forgot_password()
    {
        $this->pageTitle = __l('Forgot Password');
        $this->loadModel('EmailTemplate');
        if ($this->Auth->user('id')) {
            $this->redirect(Router::url('/', true));
        }
        if (!empty($this->request->data)) {
            $this->User->set($this->request->data);
            //Important: For forgot password unique email id check validation not necessary.
            unset($this->User->validate['email']['rule3']);
            if ($this->User->validates()) {
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.email =' => $this->request->data['User']['email'],
                        'User.is_active' => 1
                    ) ,
                    'fields' => array(
                        'User.id',
                        'User.email'
                    ) ,
                    'contain' => array(
                        'UserProfile'
                    ) ,
                    'recursive' => 1
                ));
                if (!empty($user['User']['email'])) {
                    $user = $this->User->find('first', array(
                        'conditions' => array(
                            'User.email' => $user['User']['email']
                        ) ,
                        'recursive' => -1
                    ));
                    $language_code = $this->User->getUserLanguageIso($user['User']['id']);
                    $email = $this->EmailTemplate->selectTemplate('Forgot Password', $language_code);
                    $emailFindReplace = array(
                        '##SITE_LINK##' => Router::url('/', true) ,
                        '##USERNAME##' => (isset($user['User']['username'])) ? $user['User']['username'] : '',
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##SUPPORT_EMAIL##' => Configure::read('site.contact_email') ,
                        '##RESET_URL##' => Router::url(array(
                            'controller' => 'users',
                            'action' => 'reset',
                            $user['User']['id'],
                            $this->User->getResetPasswordHash($user['User']['id'])
                        ) , true) ,
                        '##FROM_EMAIL##' => $this->User->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']) ,
                        '##CONTACT_URL##' => Router::url(array(
                            'controller' => 'contacts',
                            'action' => 'add',
                            'city' => $this->request->params['named']['city'],
                            'admin' => false
                        ) , true) ,
                        '##SITE_LOGO##' => Router::url(array(
                            'controller' => 'img',
                            'action' => 'blue-theme',
                            'logo-email.png',
                            'admin' => false
                        ) , true) ,
                    );
                    $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                    $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                    $this->Email->to = $this->User->formatToAddress($user);
                    $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                    $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                    $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                    $this->Session->setFlash(__l('An email has been sent with a link where you can change your password') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login'
                    ));
                } else {
                    $this->Session->setFlash(sprintf(__l('There is no user registered with the email %s or admin deactivated your account. If you spelled the address incorrectly or entered the wrong address, please try again.') , $this->request->data['User']['email']) , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('Please Enter valid Email id') , 'default', null, 'error');
            }
        }
    }
    public function reset($user_id = null, $hash = null)
    {
        $this->pageTitle = __l('Reset Password');
        if (!empty($this->request->data)) {
            if ($this->User->isValidResetPasswordHash($this->request->data['User']['user_id'], $this->request->data['User']['hash'])) {
                $this->User->set($this->request->data);
                if ($this->User->validates()) {
                    $this->User->updateAll(array(
                        'User.password' => '\'' . $this->Auth->password($this->request->data['User']['passwd']) . '\'',
                    ) , array(
                        'User.id' => $this->request->data['User']['user_id']
                    ));
                    $this->Session->setFlash(__l('Your password changed successfully, Please login now') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login'
                    ));
                }
                $this->Session->setFlash(__l('Could not update your password, please enter password.') , 'default', null, 'error');
                $this->request->data['User']['passwd'] = '';
                $this->request->data['User']['confirm_password'] = '';
            } else {
                $this->Session->setFlash(__l('Invalid change password request'));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
        } else {
            if (is_null($user_id) or is_null($hash)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $user_id,
                    'User.is_active' => 1,
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                $this->Session->setFlash(__l('User cannot be found in server or admin deactivated your account, please register again'));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'register'
                ));
            }
            if (!$this->User->isValidResetPasswordHash($user_id, $hash)) {
                $this->Session->setFlash(__l('Invalid change password request'));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
            $this->request->data['User']['user_id'] = $user_id;
            $this->request->data['User']['hash'] = $hash;
        }
    }
    public function change_password($user_id = null)
    {
        $this->pageTitle = __l('Change Password');
        $this->loadModel('EmailTemplate');
        if (($this->Auth->user('user_type_id') == ConstUserTypes::Company) || ($this->Auth->user('user_type_id') == ConstUserTypes::User)) {
            if ($this->Auth->User('id') != $user_id && !is_null($user_id)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            if ($this->Auth->user('is_facebook_register') || $this->Auth->user('is_openid_register')) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        if (!empty($this->request->data)) {
            if (Configure::read('site.is_admin_settings_enabled')) {
                $this->User->set($this->request->data);
                if ($this->User->validates()) {
                    if ($this->User->updateAll(array(
                        'User.password' => '\'' . $this->Auth->password($this->request->data['User']['passwd']) . '\'',
                    ) , array(
                        'User.id' => $this->request->data['User']['user_id']
                    ))) {
                        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin && Configure::read('user.is_logout_after_change_password')) {
                            $this->Auth->logout();
                            $this->Session->setFlash(__l('Your password changed successfully. Please login now') , 'default', null, 'success');
                            if ($this->RequestHandler->isAjax()) {
                                echo 'redirect*' . Router::url(array(
                                    'controller' => 'users',
                                    'action' => 'login',
                                ) , true);
                                exit;
                            } else {
                                $this->redirect(array(
                                    'controller' => 'users',
                                    'action' => 'login'
                                ));
                            }
                        } elseif ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && $this->Auth->user('id') != $this->request->data['User']['user_id']) {
                            $user = $this->User->find('first', array(
                                'conditions' => array(
                                    'User.id' => $this->request->data['User']['user_id']
                                ) ,
                                'fields' => array(
                                    'User.username',
                                    'User.email',
                                    'User.id'
                                ) ,
                                'contain' => array(
                                    'UserProfile'
                                ) ,
                                'recursive' => 1
                            ));
                            $language_code = $this->User->getUserLanguageIso($user['User']['id']);
                            $email = $this->EmailTemplate->selectTemplate('Admin Change Password', $language_code);
                            $emailFindReplace = array(
                                '##SITE_LINK##' => Router::url('/', true) ,
                                '##PASSWORD##' => $this->request->data['User']['passwd'],
                                '##USERNAME##' => $user['User']['username'],
                                '##SITE_NAME##' => Configure::read('site.name') ,
                                '##FROM_EMAIL##' => $this->User->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']) ,
                                '##CONTACT_URL##' => Router::url(array(
                                    'controller' => 'contacts',
                                    'action' => 'add',
                                    'city' => $this->request->params['named']['city'],
                                    'admin' => false
                                ) , true) ,
                                '##SITE_LOGO##' => Router::url(array(
                                    'controller' => 'img',
                                    'action' => 'blue-theme',
                                    'logo-email.png',
                                    'admin' => false
                                ) , true) ,
                            );
                            // Send e-mail to users
                            $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                            $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                            $this->Email->to = $this->User->formatToAddress($user);
                            $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                            $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                            $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                        }
                        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && $this->Auth->user('id') != $this->request->data['User']['user_id']) {
                            $this->Session->setFlash(sprintf(__l('%s \'s password changed successfully.') , $user['User']['username']) , 'default', null, 'success');
                        } else {
                            $this->Session->setFlash(__l('Your password changed successfully') , 'default', null, 'success');
                        }
                    } else {
                        $this->Session->setFlash(__l('Password could not be changed') , 'default', null, 'error');
                    }
                } else {
                    $this->Session->setFlash(__l('Password could not be changed') , 'default', null, 'error');
                }
                unset($this->request->data['User']['old_password']);
                unset($this->request->data['User']['passwd']);
                unset($this->request->data['User']['confirm_password']);
            } else {
                $this->Session->setFlash(__l('Sorry. You Cannot Update the password in Demo Mode') , 'default', null, 'error');
            }
        } else {
            if (empty($user_id)) {
                $user_id = $this->Auth->user('id');
            }
        }
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->User->find('list', array(
                'conditions' => array(
                    'OR' => array(
                        array(
                            'User.fb_user_id =' => NULL,
                            'User.is_openid_register = ' => 0,
                            'User.user_type_id' => 2,
                        ) ,
                        array(
                            'User.user_type_id' => 3,
                            'Company.is_online_account' => 1
                        ) ,
                        array(
                            'User.user_type_id' => 1,
                        ) ,
                    )
                ) ,
                'contain' => array(
                    'Company'
                ) ,
                'recursive' => 1
            ));
            $this->set(compact('users'));
        }
        $this->request->data['User']['user_id'] = (!empty($this->request->data['User']['user_id'])) ? $this->request->data['User']['user_id'] : $user_id;
    }
    public function admin_index()
    {
        $count_conditions = array();
        $this->_redirectPOST2Named(array(
            'company_type',
            'q',
        ));
        $this->pageTitle = __l('Users');
        $conditions = array();
        if (!empty($this->request->data['User']['main_filter_id'])) {
            $this->request->params['named']['main_filter_id'] = $this->request->data['User']['main_filter_id'];
        }
        if (!empty($this->request->data['User']['filter_id'])) {
            $this->request->params['named']['filter_id'] = $this->request->data['User']['filter_id'];
        }
        if (isset($this->request->params['named']['user_education_id'])) {
            if ($this->request->params['named']['user_education_id'] == 0) {
                $this->request->params['named']['user_education_id'] = NULL;
            }
            $user_profiles = $this->User->UserProfile->find('all', array(
                'conditions' => array(
                    'UserProfile.user_education_id' => $this->request->params['named']['user_education_id'],
                ) ,
                'fields' => array(
                    'UserProfile.user_id',
                ) ,
                'recursive' => -1
            ));
            if ($user_profiles) {
                foreach($user_profiles as $user_profile) {
                    $user_ids[] = $user_profile['UserProfile']['user_id'];
                }
                $conditions['User.id'] = $user_ids;
            } else {
                $conditions['User.id'] = 0;
            }
            $conditions['User.user_type_id'] = ConstUserTypes::User;
        }
        if (isset($this->request->params['named']['user_employment_id'])) {
            if ($this->request->params['named']['user_employment_id'] == 0) {
                $this->request->params['named']['user_employment_id'] = NULL;
            }
            $user_profiles = $this->User->UserProfile->find('all', array(
                'conditions' => array(
                    'UserProfile.user_employment_id' => $this->request->params['named']['user_employment_id'],
                ) ,
                'fields' => array(
                    'UserProfile.user_id',
                ) ,
                'recursive' => -1
            ));
            if ($user_profiles) {
                foreach($user_profiles as $user_profile) {
                    $user_ids[] = $user_profile['UserProfile']['user_id'];
                }
                $conditions['User.id'] = $user_ids;
            } else {
                $conditions['User.id'] = 0;
            }
            $conditions['User.user_type_id'] = ConstUserTypes::User;
        }
        if (isset($this->request->params['named']['user_income_range_id'])) {
            if ($this->request->params['named']['user_income_range_id'] == 0) {
                $this->request->params['named']['user_income_range_id'] = NULL;
            }
            $user_profiles = $this->User->UserProfile->find('all', array(
                'conditions' => array(
                    'UserProfile.user_income_range_id' => $this->request->params['named']['user_income_range_id'],
                ) ,
                'fields' => array(
                    'UserProfile.user_id',
                ) ,
                'recursive' => -1
            ));
            if ($user_profiles) {
                foreach($user_profiles as $user_profile) {
                    $user_ids[] = $user_profile['UserProfile']['user_id'];
                }
                $conditions['User.id'] = $user_ids;
            } else {
                $conditions['User.id'] = 0;
            }
            $conditions['User.user_type_id'] = ConstUserTypes::User;
        }
        if (isset($this->request->params['named']['user_relationship_id'])) {
            if ($this->request->params['named']['user_relationship_id'] == 0) {
                $this->request->params['named']['user_relationship_id'] = NULL;
            }
            $user_profiles = $this->User->UserProfile->find('all', array(
                'conditions' => array(
                    'UserProfile.user_relationship_id' => $this->request->params['named']['user_relationship_id'],
                ) ,
                'fields' => array(
                    'UserProfile.user_id',
                ) ,
                'recursive' => -1
            ));
            if ($user_profiles) {
                foreach($user_profiles as $user_profile) {
                    $user_ids[] = $user_profile['UserProfile']['user_id'];
                }
                $conditions['User.id'] = $user_ids;
            } else {
                $conditions['User.id'] = 0;
            }
            $conditions['User.user_type_id'] = ConstUserTypes::User;
        }
        if (isset($this->request->params['named']['gender_id'])) {
            if ($this->request->params['named']['gender_id'] == 0) {
                $this->request->params['named']['gender_id'] = NULL;
            }
            $user_profiles = $this->User->UserProfile->find('all', array(
                'conditions' => array(
                    'UserProfile.gender_id' => $this->request->params['named']['gender_id'],
                ) ,
                'fields' => array(
                    'UserProfile.user_id',
                ) ,
                'recursive' => -1
            ));
            if ($user_profiles) {
                foreach($user_profiles as $user_profile) {
                    $user_ids[] = $user_profile['UserProfile']['user_id'];
                }
                $conditions['User.id'] = $user_ids;
            } else {
                $conditions['User.id'] = 0;
            }
            $conditions['User.user_type_id'] = ConstUserTypes::User;
        }
        if (isset($this->request->params['named']['age_filter'])) {
            $age_conditions = array();
            if ($this->request->params['named']['age_filter'] == 1) {
                $age_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 18;
                $age_conditions['Year(Now()) - Year(UserProfile.dob) <= '] = 34;
            } elseif ($this->request->params['named']['age_filter'] == 2) {
                $age_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 35;
                $age_conditions['Year(Now()) - Year(UserProfile.dob) <= '] = 44;
            } elseif ($this->request->params['named']['age_filter'] == 3) {
                $age_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 45;
                $age_conditions['Year(Now()) - Year(UserProfile.dob) <= '] = 54;
            } elseif ($this->request->params['named']['age_filter'] == 4) {
                $age_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 55;
            } elseif ($this->request->params['named']['age_filter'] == 0) {
                $age_conditions['UserProfile.dob'] = NULL;
            }
            $user_profiles = $this->User->UserProfile->find('all', array(
                'conditions' => array(
                    $age_conditions,
                ) ,
                'fields' => array(
                    'UserProfile.user_id',
                ) ,
                'recursive' => -1
            ));
            if ($user_profiles) {
                foreach($user_profiles as $user_profile) {
                    $user_ids[] = $user_profile['UserProfile']['user_id'];
                }
                $conditions['User.id'] = $user_ids;
            } else {
                $conditions['User.id'] = 0;
            }
            $conditions['User.user_type_id'] = ConstUserTypes::User;
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'yesterday') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created)'] = 1;
            $this->pageTitle.= __l(' - Registered Yesterday');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 0;
            $this->pageTitle.= __l(' - Registered today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 7;
            $this->pageTitle.= __l(' - Registered in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 30;
            $this->pageTitle.= __l(' - Registered in this month');
        }
        if (isset($this->request->params['named']['stat'])) {
            $conditions['User.user_type_id !='] = ConstUserTypes::Company;
        }
        $param_string = "";
        $param_string.= !empty($this->request->params['named']['filter_id']) ? '/filter_id:' . $this->request->params['named']['filter_id'] : $param_string;
        $param_string.= !empty($this->request->params['named']['main_filter_id']) ? '/main_filter_id:' . $this->request->params['named']['main_filter_id'] : $param_string;
        if (!empty($this->request->params['named']['stat'])) {
            $param_string.= !empty($this->request->params['named']['stat']) ? '/stat:' . $this->request->params['named']['stat'] : $param_string;
        }
        if (!empty($this->request->params['named']['main_filter_id'])) {
            if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Android) {
                $conditions['User.is_android_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Android ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::iPhone) {
                $conditions['User.is_iphone_register'] = 1;
                $this->pageTitle.= __l(' - Registered through iPhone ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::AffiliateUser) {
                $conditions['User.is_affiliate_user'] = 1;
                $this->pageTitle.= __l(' - Affiliate users');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::OpenID) {
                $conditions['User.is_openid_register'] = 1;
                $this->pageTitle.= __l(' - Registered through OpenID ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::FaceBook) {
                $conditions['User.is_facebook_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Facebook ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Twitter) {
                $conditions['User.is_twitter_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Twitter ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Foursquare) {
                $conditions['User.is_foursquare_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Foursquare ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Gmail) {
                $conditions['User.is_gmail_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Gmail ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Yahoo) {
                $conditions['User.is_yahoo_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Yahoo ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::IphoneUser) {
                $conditions['User.is_iphone_user'] = 1;
                $this->pageTitle.= __l(' - Registered through IPhone ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::AndroidUser) {
                $conditions['User.is_android_user'] = 1;
                $this->pageTitle.= __l(' - Registered through Android ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstUserTypes::User) {
                $conditions['User.user_type_id'] = ConstUserTypes::User;
                $conditions['User.is_openid_register'] = 0;
                $conditions['User.is_facebook_register'] = 0;
                $conditions['User.is_android_user'] = 0;
                $conditions['User.is_iphone_user'] = 0;
                $conditions['User.is_twitter_register'] = 0;
                $conditions['User.is_foursquare_register'] = 0;
                $conditions['User.is_gmail_register'] = 0;
                $conditions['User.is_yahoo_register'] = 0;
                $this->pageTitle.= __l(' - Registered through Site ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstUserTypes::Admin) {
                $conditions['User.user_type_id'] = ConstUserTypes::Admin;
                $this->pageTitle.= __l(' - Admin ');
            } else if ($this->request->params['named']['main_filter_id'] == 'gift_card') {
                $conditions['User.gift_user_id != '] = NULL;
                $this->pageTitle.= __l(' - Registered Via Gift Card');
            } else if ($this->request->params['named']['main_filter_id'] == 'all') {
                $conditions['User.user_type_id != '] = ConstUserTypes::Company;
                $this->pageTitle.= __l(' - All ');
            }
        }
        $conditions['User.user_type_id != '] = ConstUserTypes::Company;
        $count_conditions['User.user_type_id != '] = ConstUserTypes::Company;
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['User.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['User.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        if (isset($this->request->data['User']['q']) && !empty($this->request->data['User']['q'])) {
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->data['User']['q']);
            $param_string.= '/q:' . $this->request->data['User']['q'];
            $this->request->params['named']['q'] = $this->request->data['User']['q'];
        } else if (isset($this->request->params['named']['q'])) {
            $this->request->data['User']['q'] = $this->request->params['named']['q'];
        }
        if (!Configure::read('user.is_enable_openid') && !Configure::read('user.is_enable_gmail_openid') && !Configure::read('user.is_enable_yahoo_openid')) {
            $count_conditions['User.is_openid_register'] = 0;
            $conditions['User.is_openid_register'] = 0;
        }
        if (!Configure::read('facebook.is_enabled_facebook_connect')) {
            $conditions['User.fb_user_id'] = null;
            $count_conditions['User.fb_user_id'] = null;
        }
        if ($this->RequestHandler->prefers('csv')) {
            Configure::write('debug', 0);
            $this->set('user', $this);
            $this->set('conditions', $conditions);
            if (isset($this->request->data['User']['q'])) {
                $this->set('q', $this->request->data['User']['q']);
            }
            $this->set('contain', $contain);
        } else {
            $this->User->recursive = 2;
            $this->paginate = array(
                'conditions' => $conditions,
                'contain' => array(
                    'LastLoginIp' => array(
                        'City' => array(
                            'fields' => array(
                                'City.name',
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.name',
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.name',
                                'Country.iso2',
                            )
                        ) ,
                        'Timezone' => array(
                            'fields' => array(
                                'Timezone.name',
                            )
                        ) ,
                        'fields' => array(
                            'LastLoginIp.ip',
                            'LastLoginIp.latitude',
                            'LastLoginIp.longitude'
                        )
                    ) ,
                    'Ip' => array(
                        'City' => array(
                            'fields' => array(
                                'City.name',
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.name',
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.name',
                                'Country.iso2',
                            )
                        ) ,
                        'Timezone' => array(
                            'fields' => array(
                                'Timezone.name',
                            )
                        ) ,
                        'fields' => array(
                            'Ip.ip',
                            'Ip.latitude',
                            'Ip.longitude'
                        )
                    ) ,
                    'UserProfile' => array(
                        'Country' => array(
                            'fields' => array(
                                'Country.name',
                                'Country.iso2',
                            )
                        )
                    ) ,
                    'Company',
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.dir',
                            'UserAvatar.filename',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    ) ,
                    'RefferalUser' => array(
                        'UserAvatar' => array(
                            'fields' => array(
                                'UserAvatar.id',
                                'UserAvatar.dir',
                                'UserAvatar.filename',
                                'UserAvatar.width',
                                'UserAvatar.height'
                            )
                        )
                    ) ,
                    'GiftRecivedFromUser' => array(
                        'fields' => array(
                            'GiftRecivedFromUser.user_type_id',
                            'GiftRecivedFromUser.username',
                            'GiftRecivedFromUser.id',
                            'GiftRecivedFromUser.fb_user_id',
                        ) ,
                        'UserAvatar' => array(
                            'fields' => array(
                                'UserAvatar.id',
                                'UserAvatar.dir',
                                'UserAvatar.filename',
                                'UserAvatar.width',
                                'UserAvatar.height'
                            )
                        )
                    ) ,
                ) ,
                'order' => array(
                    'User.id' => 'desc'
                )
            );
            $export_users = $this->User->find('all', array(
                'conditions' => $conditions,
                'recursive' => -1
            ));
            if (!empty($export_users)) {
                $ids = array();
                foreach($export_users as $export_user) {
                    $ids[] = $export_user['User']['id'];
                }
                $hash = $this->User->getIdHash(implode(',', $ids));
                $_SESSION['export_users'][$hash] = $ids;
                $this->set('export_hash', $hash);
            }
            if (isset($this->request->data['User']['q']) && !empty($this->request->data['User']['q'])) {
                $this->paginate = array_merge($this->paginate, array(
                    'search' => $this->request->params['named']['q']
                ));
            }
            $this->set('param_string', $param_string);
            $this->set('users', $this->paginate());
            $this->set('pageTitle', $this->pageTitle);
            if (!empty($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstUserTypes::Admin) {
                $moreActions = $this->User->adminMoreActions;
            } else {
                $moreActions = $this->User->moreActions;
            }
            $this->set(compact('moreActions'));
            // total android users list
            $this->set('android_user_count', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_android_register' => 1,
                    $count_conditions
                ) ,
                'recursive' => -1
            )));
            // total iphone users list
            $this->set('iphone_user_count', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_iphone_register' => 1,
                    $count_conditions
                ) ,
                'recursive' => -1
            )));
            // total affiliate users list
            $this->set('affiliate_user_count', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_affiliate_user' => 1,
                    $count_conditions
                ) ,
                'recursive' => -1
            )));
            // total openid users list
            $this->set('openid_user_count', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_openid_register' => 1,
                    $count_conditions
                ) ,
                'recursive' => -1
            )));
            // total facebook users list
            $this->set('facebook_user_count', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_facebook_register' => 1,
                    $count_conditions
                ) ,
                'recursive' => -1
            )));
            // total twitter users list
            $this->set('twitter_user_count', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_twitter_register' => 1,
                    $count_conditions
                ) ,
                'recursive' => -1
            )));
            // total foursquare users list
            $this->set('foursquare_user_count', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_foursquare_register' => 1,
                    $count_conditions
                ) ,
                'recursive' => -1
            )));
            // total gmail users list
            $this->set('gamil_user_count', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_gmail_register' => 1,
                    $count_conditions
                ) ,
                'recursive' => -1
            )));
            // total yahoo users list
            $this->set('yahoo_user_count', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_yahoo_register' => 1,
                    $count_conditions
                ) ,
                'recursive' => -1
            )));
            // total users list
            $user_count_conditions['User.user_type_id'] = ConstUserTypes::User;
            $user_count_conditions['User.is_openid_register'] = 0;
            $user_count_conditions['User.is_facebook_register'] = 0;
            $user_count_conditions['User.is_android_user'] = 0;
            $user_count_conditions['User.is_iphone_user'] = 0;
            $user_count_conditions['User.is_twitter_register'] = 0;
            $user_count_conditions['User.is_foursquare_register'] = 0;
            $user_count_conditions['User.is_gmail_register'] = 0;
            $user_count_conditions['User.is_yahoo_register'] = 0;
            $user_count_conditions['User.is_affiliate_user'] = 0;
            $this->set('user_count', $this->User->find('count', array(
                'conditions' => $user_count_conditions,
                'recursive' => -1
            )));
            // total admin list
            $this->set('admin_count', $this->User->find('count', array(
                'conditions' => array(
                    'User.user_type_id' => ConstUserTypes::Admin,
                ) ,
                'recursive' => -1
            )));
            // total gift card user list
            $this->set('gift_card_user_count', $this->User->find('count', array(
                'conditions' => array(
                    'User.gift_user_id !=' => NULL,
                    $count_conditions
                ) ,
                'recursive' => -1
            )));
            // total users without company users list
            $this->set('users_without_company_count', $this->User->find('count', array(
                'conditions' => array(
                    'User.user_type_id !=' => ConstUserTypes::Company,
                ) ,
                'recursive' => -1
            )));
            // total approved users list
            $this->set('active', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_active' => 1,
                    $count_conditions
                ) ,
                'recursive' => -1
            )));
            // total approved users list
            $this->set('inactive', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_active' => 0,
                    $count_conditions
                ) ,
                'recursive' => -1
            )));
        }
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add New User/Admin');
        $this->loadModel('EmailTemplate');
        if (!empty($this->request->data)) {
            $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['passwd']);
            $this->request->data['User']['is_agree_terms_conditions'] = '1';
            $this->request->data['User']['is_email_confirmed'] = 1;
            $this->request->data['User']['is_active'] = 1;
            $this->request->data['User']['ip_id'] = $this->User->toSaveIp();
            $this->request->data['User']['dns'] = gethostbyaddr($this->RequestHandler->getClientIP());
            $this->User->create();
            $this->User->UserProfile->set($this->request->data);
            if ($this->User->save($this->request->data) &$this->User->UserProfile->validates()) {
                $this->request->data['UserProfile']['user_id'] = $this->User->getLastInsertId();
                $this->User->UserProfile->create();
                $this->User->UserProfile->save($this->request->data);
                // Send mail to user to activate the account and send account details
                $email = $this->EmailTemplate->selectTemplate('Admin User Add');
                $emailFindReplace = array(
                    '##SITE_LINK##' => Router::url('/', true) ,
                    '##USERNAME##' => $this->request->data['User']['username'],
                    '##LOGINLABEL##' => ucfirst(Configure::read('user.using_to_login')) ,
                    '##USEDTOLOGIN##' => $this->request->data['User'][Configure::read('user.using_to_login') ],
                    '##SITE_NAME##' => Configure::read('site.name') ,
                    '##PASSWORD##' => $this->request->data['User']['passwd'],
                    '##CONTACT_URL##' => Router::url(array(
                        'controller' => 'contacts',
                        'action' => 'add',
                        'city' => $this->request->params['named']['city'],
                        'admin' => false
                    ) , true) ,
                    '##SITE_LOGO##' => Router::url(array(
                        'controller' => 'img',
                        'action' => 'blue-theme',
                        'logo-email.png',
                        'admin' => false
                    ) , true) ,
                    '##FROM_EMAIL##' => $this->User->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']) ,
                );
                $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                $this->Email->to = $this->request->data['User']['email'];
                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                $this->Session->setFlash(__l('User has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                unset($this->request->data['User']['passwd']);
                $this->Session->setFlash(__l('User could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $userTypes = $this->User->UserType->find('list', array(
            'conditions' => array(
                'UserType.id !=' => ConstUserTypes::Company
            )
        ));
        $this->set(compact('userTypes'));
        if (!isset($this->request->data['User']['user_type_id'])) {
            $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
        }
        $cities = $this->User->UserProfile->City->find('list', array(
            'conditions' => array(
                'City.is_approved =' => 1
            ) ,
            'order' => array(
                'City.name' => 'asc'
            )
        ));
        $states = $this->User->UserProfile->State->find('list');
        $this->set(compact('cities', 'states'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id) && $id == ConstUserTypes::Admin) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->_sendAdminActionMail($id, 'Admin User Delete');
        if ($this->User->delete($id)) {
            $this->Session->setFlash(__l('User deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_update()
    {
        $this->autoRender = false;
        if (!empty($this->request->data['User'])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $userIds = array();
            foreach($this->request->data['User'] as $user_id => $is_checked) {
                if ($is_checked['id']) {
                    $userIds[] = $user_id;
                }
            }
            if ($actionid && !empty($userIds)) {
                if ($actionid == ConstMoreAction::Inactive) {
                    $this->User->updateAll(array(
                        'User.is_active' => 0,
                    ) , array(
                        'User.id' => $userIds
                    ));
                    foreach($userIds as $key => $user_id) {
                        $this->_sendAdminActionMail($user_id, 'Admin User Deactivate');
                    }
                    $this->Session->setFlash(__l('Checked users has been inactivated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Active) {
                    $this->User->updateAll(array(
                        'User.is_active' => 1
                    ) , array(
                        'User.id' => $userIds
                    ));
                    foreach($userIds as $key => $user_id) {
                        $this->_sendAdminActionMail($user_id, 'Admin User Active');
                    }
                    $this->Session->setFlash(__l('Checked users has been activated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Delete) {
                    foreach($userIds as $key => $user_id) {
                        $this->_sendAdminActionMail($user_id, 'Admin User Delete');
                    }
                    $this->User->deleteAll(array(
                        'User.id' => $userIds
                    ));
                    $this->Session->setFlash(__l('Checked users has been deleted') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Export) {
                    $user_ids = implode(',', $userIds);
                    $hash = $this->User->getUserIdHash($user_ids);
                    $_SESSION['user_export'][$hash] = $userIds;
                    echo 'redirect*' . Router::url(array(
                        'controller' => 'users',
                        'action' => 'export',
                        'ext' => 'csv',
                        $hash,
                        'admin' => true
                    ) , true);
                    exit;
                } else if ($actionid == ConstMoreAction::EnableCompanyProfile) {
                    $this->User->Company->updateAll(array(
                        'Company.is_company_profile_enabled' => 1
                    ) , array(
                        'Company.user_id' => $userIds
                    ));
                    $this->Session->setFlash(__l('Checked merchants profile has been enabled') , 'default', null, 'success');
                }
            }
        }
        if (!$this->RequestHandler->isAjax()) {
            $this->redirect(Router::url('/', true) . $r);
        } else {
            $this->redirect($r);
        }
    }
    public function admin_stats()
    {
        $this->pageTitle = __l('Snapshot');
        //recently logged in users
        $loggedUsers = $this->User->find('all', array(
            'conditions' => array(
                'User.is_active' => 1,
                'User.user_type_id != ' => ConstUserTypes::Admin
            ) ,
            'fields' => array(
                'User.user_type_id',
                'User.username',
                'User.id',
            ) ,
            'recursive' => -1,
            'limit' => 10,
            'order' => array(
                'User.last_logged_in_time' => 'desc'
            )
        ));
        // Cache file read
        $this->set('tmpCacheFileSize', bytes_to_higher(dskspace(TMP . 'cache')));
        $this->set('tmpLogsFileSize', bytes_to_higher(dskspace(TMP . 'logs')));
        $this->set(compact('loggedUsers', 'periods', 'models'));
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_recent_users()
    {
        //recently registered users
        $recentUsers = $this->User->find('all', array(
            'conditions' => array(
                'User.is_active' => 1,
                'User.user_type_id != ' => ConstUserTypes::Admin
            ) ,
            'fields' => array(
                'User.user_type_id',
                'User.username',
                'User.id',
            ) ,
            'recursive' => -1,
            'limit' => 10,
            'order' => array(
                'User.id' => 'desc'
            )
        ));
        $this->set(compact('recentUsers'));
    }
    public function admin_online_users()
    {
        //online users
         $onlineUsers = $this->User->CkSession->find('all', array(
            'conditions' => array(
                'User.is_active' => 1,
                'CkSession.user_id != ' => 0,
                'User.user_type_id != ' => ConstUserTypes::Admin
            ) ,
            'fields' => array(
                'DISTINCT CkSession.user_id',
                'User.username',
                'User.user_type_id',
                'User.id',
            ) ,
            'recursive' => 0,
            'limit' => 10,
            'order' => array(
                'User.last_logged_in_time' => 'desc'
            )
        ));
        $this->set(compact('onlineUsers'));
    }
    public function admin_change_password($user_id = null)
    {
        $this->setAction('change_password', $user_id);
    }
    public function admin_login()
    {
        $this->setAction('login');
    }
    public function admin_logout()
    {
        $this->setAction('logout');
    }
    public function resend_activemail($username = NUll, $status = NULL)
    {
        if (!empty($username) && !empty($status)) {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.username' => $username,
                )
            ));
            $this->_sendActivationMail($user['User']['email'], $user['User']['id'], $this->User->getActivateHash($user['User']['id']));
        }
        $this->set('username', $username);
    }
    public function company_register()
    {
        $this->setAction('register', 'company');
    }
    public function add_to_wallet()
    {
        $this->loadModel('TempPaymentLog');
        $payment_options = $this->User->getGatewayTypes('is_enable_for_add_to_wallet');
        if (empty($payment_options[ConstPaymentGateways::Wallet])) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = __l('Add Amount to Wallet');
        if (!$this->User->isAllowed($this->Auth->user('user_type_id'))) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $check_expire = 0;
        if (!empty($this->request->data)) {
            //for conflict credit card and user city
            unset($this->User->validate['city']);
            if ($this->request->data['User']['payment_gateway_id'] == ConstPaymentGateways::CreditCard) {
                $this->User->validate = array_merge($this->User->validate, $this->User->validateCreditCard);
                $this->User->Company->City->State->validate = array_merge($this->User->Company->City->State->validate, $this->User->Company->City->State->validateStateName);
                $check_expire = $this->User->_checkExpiryMonthAndYear($this->request->data['User']['expDateMonth']['month'], $this->request->data['User']['expDateYear']['year']);
            } else if (($this->request->data['User']['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet && isset($this->request->data['User']['payment_profile_id']) && empty($this->request->data['User']['payment_profile_id']))) {
                $this->User->validate = array_merge($this->User->validate, $this->User->validateCreditCard);
                $this->User->Company->City->State->validate = array_merge($this->User->Company->City->State->validate, $this->User->Company->City->State->validateStateName);
                $check_expire = $this->User->_checkExpiryMonthAndYear($this->request->data['User']['expDateMonth']['month'], $this->request->data['User']['expDateYear']['year']);
                if ($this->request->data['User']['is_show_new_card'] == 0) {
                    $payment_gateway_id_validate = array(
                        'payment_profile_id' => array(
                            'rule1' => array(
                                'rule' => 'notempty',
                                'message' => __l('Required')
                            )
                        )
                    );
                    $this->User->validate = array_merge($this->User->validate, $payment_gateway_id_validate);
                }
            } else if ($this->request->data['User']['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet && (!isset($this->request->data['User']['payment_profile_id']))) {
                $this->User->validate = array_merge($this->User->validate, $this->User->validateCreditCard);
                $this->User->Company->City->State->validate = array_merge($this->User->Company->City->State->validate, $this->User->Company->City->State->validateStateName);
                $check_expire = $this->User->_checkExpiryMonthAndYear($this->request->data['User']['expDateMonth']['month'], $this->request->data['User']['expDateYear']['year']);
            }
            $this->User->set($this->request->data);
            // State Validation //
            $this->User->Company->City->State->set($this->request->data['State']);
            $this->User->Company->City->State->validates();
            $this->User->_checkAmount($this->request->data['User']['amount']);
            if ($this->User->validates() &empty($this->Deal->City->State->validationErrors) && empty($check_expire)) {
                if (!empty($this->request->data['State']['name'])) {
                    $this->request->data['User']['state'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->User->Company->City->State->findOrSaveAndGetId($this->request->data['State']['name']);
                }
                if (!empty($this->request->data['User']['city'])) {
                    $this->request->data['User']['city'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->User->Company->City->findOrSaveAndGetId($this->request->data['User']['city']);
                }
                if ($this->request->data['User']['payment_gateway_id'] == ConstPaymentGateways::CreditCard) {
                    $this->_addWalletFromCreditCard($this->request->data);
                } elseif ($this->request->data['User']['payment_gateway_id'] == ConstPaymentGateways::AuthorizeNet) {
                    if (!empty($this->request->data['User']['creditCardNumber'])) {
                        $user = $this->User->find('first', array(
                            'conditions' => array(
                                'User.id' => $this->Auth->user('id')
                            ) ,
                            'fields' => array(
                                'User.id',
                                'User.cim_profile_id'
                            )
                        ));
                        //create payment profile
                        $data = $this->request->data['User'];
                        $data['expirationDate'] = $this->request->data['User']['expDateYear']['year'] . '-' . $this->request->data['User']['expDateMonth']['month'];
                        $data['customerProfileId'] = $user['User']['cim_profile_id'];
                        $payment_profile_id = $this->User->_createCimPaymentProfile($data);
                        if (is_array($payment_profile_id) && !empty($payment_profile_id['payment_profile_id']) && !empty($payment_profile_id['masked_cc'])) {
                            $payment['UserPaymentProfile']['user_id'] = $this->Auth->user('id');
                            $payment['UserPaymentProfile']['cim_payment_profile_id'] = $payment_profile_id['payment_profile_id'];
                            $payment['UserPaymentProfile']['masked_cc'] = $payment_profile_id['masked_cc'];
                            $payment['UserPaymentProfile']['is_default'] = 0;
                            $this->User->UserPaymentProfile->save($payment);
                            $this->request->data['User']['payment_profile_id'] = $payment_profile_id['payment_profile_id'];
                        } else {
                            $this->Session->setFlash(sprintf(__l('Gateway error: %s <br>Note: Due to security reasons, error message from gateway may not be verbose. Please double check your card number, security number and address details. Also, check if you have enough balance in your card.') , $payment_profile_id['message']) , 'default', null, 'error');
                        }
                    }
                    if (!empty($this->request->data['User']['payment_profile_id'])) {
                        $this->_addWalletFromAuthorizeNet($this->request->data);
                    }
                } else {
                    if ($this->request->data['User']['payment_gateway_id'] == ConstPaymentGateways::PagSeguro) {
                        $payment_gateway_id = ConstPaymentGateways::PagSeguro;
                    } else {
                        $payment_gateway_id = ConstPaymentGateways::PayPalAuth;
                    }
                    $paymentGateway = $this->User->Transaction->PaymentGateway->find('first', array(
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
                    $this->pageTitle.= ' - ' . $paymentGateway['PaymentGateway']['name'];
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
                        $cmd = '_xclick';
                        // Currency Conversion Process //
                        $get_conversion = $this->_convertAmount($this->request->data['User']['amount']);
                        $gateway_options = array(
                            'cmd' => $cmd,
                            'notify_url' => Router::url('/', true) . 'users/processpayment/paypal',
                            'cancel_return' => Router::url('/', true) . 'users/payment_cancel/' . $payment_gateway_id,
                            'return' => Router::url('/', true) . 'users/payment_success/' . $payment_gateway_id,
                            'item_name' => __l('Add amount to wallet') ,
                            'currency_code' => $get_conversion['currency_code'],
                            'amount' => $get_conversion['amount'],
                            'user_defined' => array(
                                'user_id' => $this->Auth->user('id') ,
                                'ip' => $this->RequestHandler->getClientIP() ,
                                'original_amount_needed' => $this->request->data['User']['amount'],
                                'amount_needed' => $get_conversion['amount'],
                                'currency_code' => $get_conversion['currency_code']
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
                        $original_amount_needed = $amount_user = $this->request->data['User']['amount'];
                        $get_conversion = $this->_convertPagseguroAmount($amount_user); //Convert amount
                        $amount_user = $get_conversion['amount'];
                        if (!is_int($amount_user)) {
                            $user_amount = $amount_user*100;
                        } else {
                            $user_amount = $amount_user;
                        }
                        //gateway options set
                        $ref = time();
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
                                'item_descr' => __l('wallet') ,
                                'item_quant' => '1',
                                'item_valor' => $user_amount,
                                'item_frete' => '0',
                                'item_peso' => '20'
                            ) ,
                            'customer_info'
                        );
                        $transaction_data['TempPaymentLog'] = array(
                            'trans_id' => $ref,
                            'payment_type' => 'wallet',
                            'payment_method' => 'wallet',
                            'user_id' => $this->Auth->user('id') ,
                            'ip_id' => $this->TempPaymentLog->toSaveIp() ,
                            'amount_needed' => $amount_user,
                            'payment_gateway_id' => $this->request->data['User']['payment_gateway_id'],
                            'currency_code' => Configure::read('paypal.currency_code') ,
                            'original_amount_needed' => $original_amount_needed,
                        );
                        $this->TempPaymentLog->save($transaction_data);
                        //$this->Session->write('transaction_data',$transaction_data);
                        $this->set('gateway_options', $gateway_options);
                    }
                    $this->set('action', $action);
                    $this->set('amount', $amount_user);
                    $this->render('do_payment');
                }
            } else {
                $this->Session->setFlash(__l('Your amount can not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->User->validate = array_merge($this->User->validate, $this->User->validateCreditCard);
            $this->User->Company->City->State->validate = array_merge($this->User->Company->City->State->validate, $this->User->Company->City->State->validateStateName);
            $this->request->data['User']['is_show_new_card'] = 0;
        }
        $payment_options = $this->User->getGatewayTypes('is_enable_for_add_to_wallet');
        unset($payment_options[ConstPaymentGateways::Wallet]);
        if (empty($this->request->data['User']['payment_gateway_id'])) {
            if (!empty($payment_options[ConstPaymentGateways::AuthorizeNet])) {
                $this->request->data['User']['payment_gateway_id'] = ConstPaymentGateways::AuthorizeNet;
            } elseif (!empty($payment_options[ConstPaymentGateways::CreditCard])) {
                $this->request->data['User']['payment_gateway_id'] = ConstPaymentGateways::CreditCard;
            } elseif (!empty($payment_options[ConstPaymentGateways::PayPalAuth])) {
                $this->request->data['User']['payment_gateway_id'] = ConstPaymentGateways::PayPalAuth;
            }
        }
        $gateway_options = array();
        $gateway_options['paymentGateways'] = $payment_options;
        $gateway_options['countries'] = $this->User->UserProfile->Country->find('list', array(
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
        $gateway_options['cities'] = $this->User->UserProfile->City->find('list', array(
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
        $gateway_options['states'] = $this->User->UserProfile->State->find('list', array(
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
        $gateway_options['creditCardTypes'] = array(
            'Visa' => __l('Visa') ,
            'MasterCard' => __l('MasterCard') ,
            'Discover' => __l('Discover') ,
            'Amex' => __l('Amex')
        );
        $Paymentprofiles = $this->User->UserPaymentProfile->find('all', array(
            'fields' => array(
                'UserPaymentProfile.masked_cc',
                'UserPaymentProfile.cim_payment_profile_id',
                'UserPaymentProfile.is_default'
            ) ,
            'conditions' => array(
                'UserPaymentProfile.user_id' => $this->Auth->user('id')
            ) ,
        ));
        foreach($Paymentprofiles as $pay_profile) {
            $gateway_options['Paymentprofiles'][$pay_profile['UserPaymentProfile']['cim_payment_profile_id']] = $pay_profile['UserPaymentProfile']['masked_cc'];
            if ($pay_profile['UserPaymentProfile']['is_default']) {
                $this->request->data['User']['payment_profile_id'] = $pay_profile['UserPaymentProfile']['cim_payment_profile_id'];
            }
        }
        if (empty($gateway_options['Paymentprofiles'])) {
            $this->request->data['User']['is_show_new_card'] = 1;
        }
        $states = $this->User->UserProfile->State->find('list', array(
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
        $this->set('gateway_options', $gateway_options);
        $this->set('check_expire', $check_expire);
        $this->request->data['User']['cvv2Number'] = $this->request->data['User']['creditCardNumber'] = '';
    }
    public function _addWalletFromAuthorizeNet($data)
    {
        if (!empty($this->request->data)) {
            $cim = $this->User->_getCimObject();
            if (!empty($cim)) {
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $this->Auth->user('id')
                    ) ,
                    'fields' => array(
                        'User.id',
                        'User.cim_profile_id'
                    )
                ));
                $authorize_currency = $this->getAuthorizeConversionCurrency();
                $site_currency_id = $authorize_currency['CurrencyConversion']['currency_id'];
                $converted_currency_id = $authorize_currency['CurrencyConversion']['converted_currency_id'];
                $conversion_rate = $authorize_currency['CurrencyConversion']['rate'];
                $authorizenet_converted_amt = $this->User->_convertAuthorizeAmount($this->request->data['User']['amount']); //Convert amount
                $cim->setParameter('amount', $authorizenet_converted_amt);
                $cim->setParameter('refId', time());
                $cim->setParameter('customerProfileId', $user['User']['cim_profile_id']);
                $cim->setParameter('customerPaymentProfileId', $data['User']['payment_profile_id']);
                $title = Configure::read('site.name') . ' - added to wallet';
                $description = 'Amount added to wallet in ' . Configure::read('site.name');
                // CIM accept only 30 character in title
                if (strlen($title) > 30) {
                    $title = substr($title, 0, 27) . '...';
                }
                $cim->setLineItem($this->Auth->user('id') , $title, $description, 1, $this->request->data['User']['amount']);
                $cim->createCustomerProfileTransaction();
                $response = $cim->getDirectResponse();
                $approval_code = $cim->getAuthCode();
                if (!empty($approval_code) && !empty($response)) {
                    $response_array = explode(',', $response);
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
                    $data_authorize_docapture_log['AuthorizenetDocaptureLog']['orginal_amount'] = $this->request->data['User']['amount'];
                    $data_authorize_docapture_log['AuthorizenetDocaptureLog']['rate'] = $conversion_rate;
                    $this->User->DealUser->AuthorizenetDocaptureLog->save($data_authorize_docapture_log);
                    if ($response_array[0] == 1) {
                        $capture = 1;
                    }
                }
                if (!empty($capture)) {
                    $data['Transaction']['user_id'] = $this->Auth->user('id');
					$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
                    $data['Transaction']['foreign_id'] = $this->Auth->user('id');
                    $data['Transaction']['class'] = 'SecondUser';
                    $data['Transaction']['amount'] = $this->request->data['User']['amount'];
                    $data['Transaction']['payment_gateway_id'] = ConstPaymentGateways::AuthorizeNet;
                    $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
                    ///////Authorize.net currency conversion////////////////////////
                    $data['Transaction']['currency_id'] = $site_currency_id;
                    $data['Transaction']['converted_currency_id'] = $converted_currency_id;
                    $data['Transaction']['converted_amount'] = $response_array[9];
                    $data['Transaction']['rate'] = $conversion_rate;
                    $this->User->Transaction->save($data);
                    $this->User->updateAll(array(
                        'User.available_balance_amount' => 'User.available_balance_amount +' . $this->request->data['User']['amount'],
                        'User.total_amount_added_to_wallet' => 'User.total_amount_added_to_wallet +' . $this->request->data['User']['amount'],
                    ) , array(
                        'User.id' => $this->Auth->user('id')
                    ));
                    $this->Session->setFlash(__l('Amount added in wallet successfully.') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'my_stuff',
                        '#My_Transactions'
                    ));
                } else {
                    $this->Session->setFlash(__l('Transaction failure. Please try once again. ') . $response['L_LONGMESSAGE0'], 'default', null, 'error');
                    $this->redirect(array(
                        'controller' => 'deals',
                        'action' => 'index'
                    ));
                }
            }
        }
    }
    public function processpayment($gateway_name)
    {
	$this->log("p");
        $this->loadModel('TempPaymentLog');
        $gateway = array(
            'paypal' => ConstPaymentGateways::PayPalAuth,
            'pagseguro' => ConstPaymentGateways::PagSeguro
        );
        $gateway_id = (!empty($gateway[$gateway_name])) ? $gateway[$gateway_name] : 0;
        $transaction_data = $this->Session->read('transaction_data');
        if (empty($transaction_data) && $gateway_name == 'pagseguro') {
            throw new NotFoundException(__l('Invalid request'));
        } else {
            $this->Session->delete('transaction_data');
        }
        $paymentGateway = $this->User->Transaction->PaymentGateway->find('first', array(
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
                //paypal quick fix
                $paid_amount = $this->Paypal->paypal_post_arr['mc_gross'];
                $min_wallet_amount = Configure::read('wallet.min_wallet_amount');
                $max_wallet_amount = Configure::read('wallet.max_wallet_amount');
                $allow_to_process = 0;
                $is_supported = Configure::read('paypal.is_supported');
                $actual_amount = $this->Paypal->paypal_post_arr['amount_needed'];
                if ($actual_amount == $paid_amount) {
                    $allow_to_process = 1; //**NEED TO OPTIMIZE FOR DEALS TOO **//

                }
                if (!empty($allow_to_process)) {
                    if ($this->Paypal->process()) {
                        if ($this->Paypal->paypal_post_arr['payment_status'] == 'Completed') {
                            $get_conversion_val = $this->getConversionCurrency();
                            $id = $this->Paypal->paypal_post_arr['user_id'];
                            $data['Transaction']['user_id'] = $id;
							$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
                            $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
                            $data['Transaction']['class'] = 'SecondUser';
                            $data['Transaction']['amount'] = $this->Paypal->paypal_post_arr['original_amount_needed'];
                            $data['Transaction']['payment_gateway_id'] = $paymentGateway['PaymentGateway']['id'];
                            $data['Transaction']['gateway_fees'] = $this->Paypal->paypal_post_arr['mc_fee'];
                            $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
                            $paypal_transaction_log_id = $this->Paypal->logPaypalTransactions();
                            // Currency Conversion Changes //
                            $data['Transaction']['currency_id'] = $get_conversion_val['CurrencyConversion']['currency_id'];
                            $data['Transaction']['converted_currency_id'] = $dget_conversion_val['CurrencyConversion']['converted_currency_id'];
                            $data['Transaction']['converted_amount'] = $this->Paypal->paypal_post_arr['mc_gross'];
                            $data['Transaction']['rate'] = $get_conversion_val['CurrencyConversion']['rate'];
                            $transaction_id = $this->User->Transaction->log($data);
                            if (!empty($transaction_id)) {
                                $this->Paypal->paypal_post_arr['transaction_id'] = $transaction_id;
                                $this->User->updateAll(array(
                                    'User.available_balance_amount' => 'User.available_balance_amount +' . $this->Paypal->paypal_post_arr['original_amount_needed'],
                                    'User.total_amount_added_to_wallet' => 'User.total_amount_added_to_wallet +' . $this->Paypal->paypal_post_arr['original_amount_needed']
                                ) , array(
                                    'User.id' => $id
                                ));
                            }
                            //update deal user id in PaypalTransactionLog table
                            $this->User->PaypalTransactionLog->updateAll(array(
                                'PaypalTransactionLog.currency_id' => $get_conversion_val['CurrencyConversion']['currency_id'],
                                'PaypalTransactionLog.converted_currency_id' => $get_conversion_val['CurrencyConversion']['converted_currency_id'],
                                'PaypalTransactionLog.orginal_amount' => $this->Paypal->paypal_post_arr['original_amount_needed'],
                                'PaypalTransactionLog.rate' => $get_conversion_val['CurrencyConversion']['rate'],
                            ) , array(
                                'PaypalTransactionLog.id' => $paypal_transaction_log_id
                            ));
                        } else {
                            $this->pageTitle = __l('Payment Failure');
                            $this->Session->setFlash(__l('Error in payment') , 'default', null, 'error');
                        }
                    } else {
                        //place to handle the failure of process
                        $this->pageTitle = __l('Payment Failure');
                        $this->Session->setFlash(__l('Error in payment') , 'default', null, 'error');
                    }
                }
                $this->Paypal->logPaypalTransactions();
                break;

            case 'pagseguro':
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
                if (!empty($transaction_data) && $allow_to_process) {
                    $id = $transaction_data['user_id'];
                    $paid_amount = $transaction_data['amount_needed'];
                    //add amount to wallet for normal paypal
                    $data['Transaction']['user_id'] = $id;
					$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
                    $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
                    $data['Transaction']['class'] = 'SecondUser';
                    $data['Transaction']['amount'] = $paid_amount;
                    $data['Transaction']['payment_gateway_id'] = $paymentGateway['PaymentGateway']['id'];
                    $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
                    $transaction_id = $this->User->Transaction->log($data);
                    if (!empty($transaction_id)) {
                        $transaction_id = $transaction_id;
                        $this->User->updateAll(array(
                            'User.available_balance_amount' => 'User.available_balance_amount +' . $paid_amount,
                            'User.total_amount_added_to_wallet' => 'User.total_amount_added_to_wallet +' . $paid_amount,
                        ) , array(
                            'User.id' => $id
                        ));
                        $this->redirect(array(
                            'controller' => 'transactions',
                            'action' => 'index',
                        ));
                        $this->TempPaymentLog->delete($transaction_data['id']);
                    }
                } else {
                    //place to handle the failure of process
                    $this->pageTitle = __l('Payment Failure');
                    $this->Session->setFlash(__l('Error in payment') , 'default', null, 'error');
                    $this->redirect(array(
                        'controller' => 'transactions',
                        'action' => 'index'
                    ));
                }
                break;

            default:
                throw new NotFoundException(__l('Invalid request'));
            } // switch
            $this->autoRender = false;
    }
    public function payment_success()
    {
        $this->pageTitle = __l('Payment Success');
        $this->Session->setFlash(__l('Your payment has been successfully transferred.') , 'default', null, 'success');
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'my_stuff',
            '#My_Transactions'
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
    public function _addWalletFromCreditCard($data)
    {
        $paymentGateway = $this->User->Transaction->PaymentGateway->find('first', array(
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
        // converted currency changes
        $get_conversion = $this->_convertAmount($data['User']['amount']);
        $sender_info['is_testmode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
        $data_credit_card['firstName'] = $data['User']['firstName'];
        $data_credit_card['lastName'] = $data['User']['lastName'];
        $data_credit_card['creditCardType'] = $data['User']['creditCardType'];
        $data_credit_card['creditCardNumber'] = $data['User']['creditCardNumber'];
        $data_credit_card['expDateMonth'] = $data['User']['expDateMonth'];
        $data_credit_card['expDateYear'] = $data['User']['expDateYear'];
        $data_credit_card['cvv2Number'] = $data['User']['cvv2Number'];
        $data_credit_card['address'] = $data['User']['address'];
        $data_credit_card['city'] = $data['User']['city'];
        $data_credit_card['state'] = $data['User']['state'];
        $data_credit_card['zip'] = $data['User']['zip'];
        $data_credit_card['country'] = $data['User']['country'];
        $data_credit_card['paymentType'] = 'Sale';
        // converted currency changes
        $data_credit_card['amount'] = $get_conversion['amount'];
        $data_credit_card['currency_code'] = $get_conversion['currency_code'];
        //calling doDirectPayment fn in paypal component
        $payment_response = $this->Paypal->doDirectPayment($data_credit_card, $sender_info);
        //if not success show error msg as it received from paypal
        if (!empty($payment_response) && $payment_response['ACK'] != 'Success') {
            $this->Session->setFlash(sprintf(__l('%s') , $payment_response['L_LONGMESSAGE0']) , 'default', null, 'error');
            return;
        }
        // converted currency changes
        $get_conversion_cuurency = $this->getConversionCurrency();
        $data['Transaction']['user_id'] = $this->Auth->user('id');
		$data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
        $data['Transaction']['foreign_id'] = ConstUserIds::Admin;
        $data['Transaction']['class'] = 'SecondUser';
        $data['Transaction']['amount'] = $data['User']['amount'];
        $data['Transaction']['payment_gateway_id'] = $paymentGateway['PaymentGateway']['id'];
        $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddedToWallet;
        // converted currency changes
        $data['Transaction']['currency_id'] = $get_conversion_cuurency['CurrencyConversion']['currency_id'];
        $data['Transaction']['converted_currency_id'] = $get_conversion_cuurency['CurrencyConversion']['converted_currency_id'];
        $data['Transaction']['converted_amount'] = $payment_response['AMT'];
        $data['Transaction']['rate'] = $get_conversion_cuurency['CurrencyConversion']['rate'];
        $transaction_id = $this->User->Transaction->log($data);
        if (!empty($transaction_id)) {
            $this->Paypal->paypal_post_arr['transaction_id'] = $transaction_id;
            $this->User->updateAll(array(
                'User.available_balance_amount' => 'User.available_balance_amount +' . $data['User']['amount'],
                'User.total_amount_added_to_wallet' => 'User.total_amount_added_to_wallet +' . $data['User']['amount']
            ) , array(
                'User.id' => $this->Auth->user('id')
            ));
        }
        $data_paypal_docapture_log['PaypalDocaptureLog']['authorizationid'] = $payment_response['TRANSACTIONID'];
        $data_paypal_docapture_log['PaypalDocaptureLog']['wallet_user_id'] = $this->Auth->user('id');
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
        $data_paypal_docapture_log['PaypalDocaptureLog']['currency_id'] = $get_conversion_cuurency['CurrencyConversion']['currency_id'];
        $data_paypal_docapture_log['PaypalDocaptureLog']['converted_currency_id'] = $get_conversion_cuurency['CurrencyConversion']['currency_id'];
        $data_paypal_docapture_log['PaypalDocaptureLog']['original_amount'] = $data['User']['amount'];
        $data_paypal_docapture_log['PaypalDocaptureLog']['rate'] = $get_conversion_cuurency['CurrencyConversion']['rate'];
        //save do capture log records
        $this->User->DealUser->PaypalDocaptureLog->save($data_paypal_docapture_log);
        $this->Session->setFlash(__l('Your payment has been successfully transferred.') , 'default', null, 'success');
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'my_stuff',
            '#My_Transactions'
        ));
    }
    public function my_stuff()
    {
        if (!$this->User->isAllowed($this->Auth->user('user_type_id'))) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = __l('My Stuff');
    }
    public function referred_users()
    {
        if (!empty($this->request->params['named']['user_id'])) {
            $conditions = array(
                'User.Referred_by_user_id' => $this->request->params['named']['user_id'],
            );
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'UserAvatar' => array(
                    'fields' => array(
                        'UserAvatar.id',
                        'UserAvatar.filename',
                        'UserAvatar.dir'
                    )
                ) ,
                'DealUser' => array(
                    'fields' => array(
                        'DealUser.id'
                    ) ,
                )
            ) ,
        );
        $users = $this->paginate();
        foreach($users as $user) $userlist[] = $user['User']['id'];
        $userlist = (!empty($userlist) ? $userlist : '');
        $referred_users_deal_counts = $this->User->DealUser->find('all', array(
            'conditions' => array(
                'DealUser.user_id' => $userlist
            ) ,
            'fields' => array(
                'Count(DealUser.user_id) as deal_count',
                'DealUser.user_id'
            ) ,
            'group' => array(
                'DealUser.user_id'
            ) ,
            'recursive' => -1
        ));
        foreach($referred_users_deal_counts as $referred_users_deal_count) $new_count[$referred_users_deal_count['DealUser']['user_id']] = $referred_users_deal_count['0']['deal_count'];
        foreach($users as &$user) $user['User']['deal_count'] = ($new_count[$user['User']['id']]) ? $new_count[$user['User']['id']] : 0;
        $this->User->recursive = 2;
        $this->set('referredFriends', $users);
    }
    public function whois($ip = null)
    {
        if (!empty($ip)) {
            $this->redirect(Configure::read('site.look_up_url') . $ip);
        }
    }
    public function admin_add_fund($id = null)
    {
        $this->pageTitle = __l('Add Fund');
        if (!empty($this->request->data['Transaction']['user_id'])) {
            $id = $this->request->data['Transaction']['user_id'];
        }
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $id
            ) ,
            'recursive' => -1
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $user['User']['username'];
        if (!empty($this->request->data)) {
            $this->request->data['Transaction']['foreign_id'] = ConstUserIds::Admin;
			$this->request->data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
            $this->request->data['Transaction']['class'] = 'SecondUser';
            $this->request->data['Transaction']['transaction_type_id'] = ConstTransactionTypes::AddFundToWallet;
            if ($this->User->Transaction->save($this->request->data['Transaction'])) {
                $this->User->updateAll(array(
                    'User.available_balance_amount' => 'User.available_balance_amount +' . $this->request->data['Transaction']['amount'],
                ) , array(
                    'User.id' => $this->request->data['Transaction']['user_id']
                ));
                $this->Session->setFlash(__l('Fund has been added successfully') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Fund could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data['Transaction']['user_id'] = $id;
        }
        $this->set('user', $user);
    }
    public function admin_deduct_fund($id = null)
    {
        $this->pageTitle = __l('Deduct Fund');
        if (!empty($this->request->data['Transaction']['user_id'])) {
            $id = $this->request->data['Transaction']['user_id'];
        }
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $id
            ) ,
            'recursive' => -1
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $user['User']['username'];
        if (!empty($this->request->data)) {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data['Transaction']['user_id']
                )
            ));
            $this->User->Transaction->set($this->request->data);
            $this->User->Transaction->validates();
            if ($user['User']['available_balance_amount'] < $this->request->data['Transaction']['amount']) {
                $this->Session->setFlash(__l('Deduct amount should be less than the available balance amount') , 'default', null, 'error');
            } else {
                $this->request->data['Transaction']['foreign_id'] = ConstUserIds::Admin;
				$this->request->data['Transaction']['receiver_user_id'] = ConstUserIds::Admin;
                $this->request->data['Transaction']['class'] = 'SecondUser';
                $this->request->data['Transaction']['transaction_type_id'] = ConstTransactionTypes::DeductFundFromWallet;
                if ($this->User->Transaction->save($this->request->data['Transaction'])) {
                    $this->User->updateAll(array(
                        'User.available_balance_amount' => 'User.available_balance_amount -' . $this->request->data['Transaction']['amount'],
                    ) , array(
                        'User.id' => $this->request->data['Transaction']['user_id']
                    ));
                    $this->Session->setFlash(__l('Fund has been deducted successfully') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'index'
                    ));
                } else {
                    $this->Session->setFlash(__l('Fund could not be deducted. Please, try again.') , 'default', null, 'error');
                }
            }
        } else {
            $this->request->data['Transaction']['user_id'] = $id;
            $this->Deal->validate = array_merge($this->User->validate, $this->User->Transaction->validate);
        }
        $this->set('user', $user);
    }
    // <-- For iPhone App code
    public function validate_user()
    {
        if ((Configure::read('user.using_to_login') == 'email') && isset($this->request->data['User']['username'])) {
            $this->request->data['User']['email'] = $this->request->data['User']['username'];
            unset($this->request->data['User']['username']);
        }
        $this->request->data['User'][Configure::read('user.using_to_login') ] = trim($this->request->data['User'][Configure::read('user.using_to_login') ]);
        $this->request->data['User']['password'] = $_POST['data']['User']['password'];
        $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);
        if ($this->Auth->login($this->request->data)) {
            $mobile_app_hash = md5($this->_unum() . $this->request->data['User'][Configure::read('user.using_to_login') ] . $this->request->data['User']['password'] . Configure::read('Security.salt'));
            $this->User->updateAll(array(
                'User.mobile_app_hash' => '\'' . $mobile_app_hash . '\'',
                'User.mobile_app_time_modified' => '\'' . date('Y-m-d h:i:s') . '\'',
            ) , array(
                'User.id' => $this->Auth->user('id')
            ));
            if (!empty($this->request->data['User']['devicetoken'])) {
                $this->User->ApnsDevice->findOrSave_apns_device($this->Auth->user('id') , $this->request->data['User']);
            }
            if (!empty($_GET['latitude']) && !empty($_GET['longtitude'])) {
                $this->update_iphone_user($_GET['latitude'], $_GET['longtitude'], $this->Auth->user('id'));
            }
            $resonse = array(
                'status' => 0,
                'message' => __l('Success') ,
                'hash_token' => $mobile_app_hash,
				'username' => $this->request->data['User'][Configure::read('user.using_to_login') ]
            );
        } else {
            $resonse = array(
                'status' => 1,
                'message' => sprintf(__l('Sorry, login failed.  Your %s or password are incorrect') , Configure::read('user.using_to_login'))
            );
        }
        if ($this->RequestHandler->prefers('json')) {
            $this->view = 'Json';
            $this->set('json', (empty($this->viewVars['iphone_response'])) ? $resonse : $this->viewVars['iphone_response']);
        }
    }
    // For iPhone App code -->
    function oauth_facebook()
    {
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        $this->autoRender = false;
        if (!empty($_REQUEST['code'])) {
            // For cities update & redirection //
            if (!empty($this->request->params['named']['city_to_update'])) {
                $tokens = $this->facebook->setAccessToken(array(
                    'redirect_uri' => Router::url(array(
                        'controller' => 'users',
                        'action' => 'oauth_facebook',
                        'city_to_update' => $this->request->params['named']['city_to_update'],
                        'admin' => false
                    ) , true) ,
                    'code' => $_REQUEST['code']
                ));
                // Since, return url cannot be set in session //
                $fb_return_url = Router::url(array(
                    'controller' => $this->request->params['named']['city'],
                    'action' => 'cities',
                    'city_to_update' => $this->request->params['named']['city_to_update'],
                    'fb_update',
                    'admin' => false
                ) , true);
            } else {
                $tokens = $this->facebook->setAccessToken(array(
                    'redirect_uri' => Router::url(array(
                        'controller' => 'users',
                        'action' => 'oauth_facebook',
                        'admin' => false
                    ) , true) ,
                    'code' => $_REQUEST['code']
                ));
            }
            if (empty($fb_return_url)) {
                $fb_return_url = $this->Session->read('fb_return_url');
            }
            $this->redirect($fb_return_url);
        } else {
            $this->Session->setFlash(__l('Invalid Facebook Connection.') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
        exit;
    }
    public function admin_referred_users()
    {
        $this->pageTitle = __l('Referrals');
        $conditions = array();
        $conditions['NOT']['User.referred_by_user_id'] = 0;
        $this->User->recursive = -1;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'User.referred_by_user_id',
                'User.username',
                'User.created',
            )
        );
        $this->set('referred_users', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_diagnostics()
    {
        $this->pageTitle = __l('Diagnostics');
        $this->set('pageTitle', $this->pageTitle);
    }
	
	public function test()
	{
		
	}
}
?>
