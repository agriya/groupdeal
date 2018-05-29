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
class CompaniesController extends AppController
{
    public $name = 'Companies';
    public $uses = array(
        'Company',
        'Attachment',
    );
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
    );
    public $helpers = array(
        'Csv',
    );    
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'City',
            'State',
            'Company.latitude',
            'Company.longitude',
            'Company.map_zoom_level',
            'UserAvatar.filename',
            'User.id',
            'Company.id',
            'Company.address1',
            'Company.address2',
            'Company.company_profile',
            'Company.country_id',
            'Company.is_company_profile_enabled',
            'Company.name',
            'Company.phone',
            'Company.url',
            'Company.zip',
            'User.UserProfile.paypal_account'
        );
        parent::beforeFilter();
    }
    public function view($slug = null, $deal_slug = null)
    {
        $this->pageTitle = __l('Merchant');
        $allowed_company_addresses = array();
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $conditions['Company.slug'] = $slug;
        if (ConstUserTypes::Admin != $this->Auth->user('user_type_id')) {
            $conditions['Company.is_company_profile_enabled'] = 1;
            if (!$this->RequestHandler->prefers('kml')) {
                $conditions['Company.is_online_account'] = 1;
            }
        }
        // Checking whether which address allowed display in KML //
        if (!empty($this->request->params['named']['deal']) || !empty($deal_slug)) {
            $deal_conditions['Deal.slug'] = (!empty($this->request->params['named']['deal']) ? $this->request->params['named']['deal'] : $deal_slug);
            $deal = $this->Company->Deal->find('first', array(
                'conditions' => array(
                    'Deal.slug' => (!empty($this->request->params['named']['deal']) ? $this->request->params['named']['deal'] : $deal_slug)
                ) ,
                'fields' => array(
                    'Deal.id',
                    'Deal.name',
                    'Deal.is_redeem_at_all_branch_address',
                    'Deal.is_redeem_in_main_address',
                ) ,
                'contain' => array(
                    'CompanyAddressesDeal'
                ) ,
                'recursive' => 1
            ));
            // if redeem all branch address unchecked, we are checking which branch address was allowed to display //
            if (empty($deal['Deal']['is_redeem_at_all_branch_address'])) {
                foreach($deal['CompanyAddressesDeal'] as $company_addresses) {
                    $allowed_addresses[] = $company_addresses['company_address_id'];
                }
                $allowed_company_addresses['CompanyAddress.id'] = $allowed_addresses;
            }
        }
        $company = $this->Company->find('first', array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.email',
                        'User.user_type_id',
                        'User.username',
                        'User.id',
                        'User.available_balance_amount',
                        'User.is_email_confirmed',
                        'User.is_active'
                    ) ,
                    'UserAvatar',
                ) ,
                'CompanyAddress' => array(
                    'conditions' => $allowed_company_addresses,
                    'City' => array(
                        'fields' => array(
                            'City.name'
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
                    ) ,
                    'order' => array(
                        'CompanyAddress.id' => 'desc'
                    )
                ) ,
                'City' => array(
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.slug',
                    )
                ) ,
                'State' => array(
                    'fields' => array(
                        'State.id',
                        'State.name'
                    )
                ) ,
                'Country' => array(
                    'fields' => array(
                        'Country.id',
                        'Country.name',
                        'Country.slug',
                    )
                ) ,
                'Deal' => array(
                    'conditions' => array(
                        'Deal.deal_status_id' => array(
                            ConstDealStatus::Open,
                            ConstDealStatus::Expired,
                            ConstDealStatus::Tipped,
                            ConstDealStatus::Closed,
                            ConstDealStatus::PaidToCompany
                        )
                    ) ,
                    'fields' => array(
                        'Deal.id',
                        'Deal.name',
                        'Deal.slug',
                        'Deal.description'
                    ) ,
                    'limit' => 5
                )
            ) ,
            'recursive' => 2,
        ));
        // If no need to show in main address, we'll remove it, so it wont show in KML //
        if (isset($deal['Deal']) && empty($deal['Deal']['is_redeem_in_main_address'])) {
            unset($company['Company']['address1']);
        }
        if ($this->RequestHandler->prefers('kml')) {
            $this->set('company', $company);
        } else {
            $statistics = array();
            $statistics['referred_users'] = $this->Company->User->find('count', array(
                'conditions' => array(
                    'User.referred_by_user_id' => $company['Company']['user_id']
                )
            ));
            $deal_status_conditions = array(
                ConstDealStatus::Open,
                ConstDealStatus::Expired,
                ConstDealStatus::Tipped,
                ConstDealStatus::Closed,
                ConstDealStatus::PaidToCompany
            );
            if ($company['Company']['user_id'] == $this->Auth->user('id')) {
                $deal_status_conditions[] = ConstDealStatus::Draft;
                $deal_status_conditions[] = ConstDealStatus::PendingApproval;
                $deal_status_conditions[] = ConstDealStatus::Upcoming;
                $deal_status_conditions[] = ConstDealStatus::Refunded;
                $deal_status_conditions[] = ConstDealStatus::Canceled;
            }
            $statistics['deal_created'] = $this->Company->Deal->find('count', array(
                'conditions' => array(
                    'OR' => array(
                        'Deal.user_id' => $company['Company']['user_id'],
                        'Deal.company_id' => $company['Company']['id'],
                    ) ,
                    'Deal.deal_status_id' => $deal_status_conditions
                )
            ));
            $statistics['deal_purchased'] = $this->Company->User->DealUser->find('count', array(
                'conditions' => array(
                    'DealUser.user_id' => $company['Company']['user_id'],
                    'DealUser.is_gift' => 0
                )
            ));
            $statistics['gift_sent'] = $this->Company->User->GiftUser->find('count', array(
                'conditions' => array(
                    'GiftUser.user_id' => $company['Company']['user_id']
                )
            ));
            $statistics['gift_received'] = $this->Company->User->GiftUser->find('count', array(
                'conditions' => array(
                    'GiftUser.friend_mail' => $company['Company']['user_id']
                )
            ));
            $statistics['user_friends'] = $this->Company->User->UserFriend->find('count', array(
                'conditions' => array(
                    'UserFriend.user_id' => $company['Company']['user_id'],
                    'UserFriend.friend_status_id' => 2,
                    'UserFriend.is_requested' => 0,
                )
            ));
            if (empty($company)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->Company->CompanyView->create();
            $this->request->data['CompanyView']['company_id'] = $company['Company']['id'];
            $this->request->data['CompanyView']['user_id'] = $this->Auth->user('id');
            $this->request->data['CompanyView']['ip_id'] = $this->Company->toSaveIp();
            $this->request->data['CompanyView']['dns'] = gethostbyaddr($this->RequestHandler->getClientIP());
            $this->Company->CompanyView->save($this->request->data);
            $this->set('statistics', $statistics);
            $this->pageTitle.= ' - ' . $company['Company']['name'];
            $this->set('company', $company);
            $this->request->data['UserComment']['user_id'] = $company['User']['id'];
        }
    }
    public function edit($id = null)
    {
        $this->pageTitle = __l('Edit Merchant');
        $temp_country_id = '';
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            $user = $this->Company->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data['User']['id']
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
                    ) ,
                    'UserProfile' => array(
                        'fields' => array(
                            'UserProfile.paypal_account',
                            'UserProfile.language_id'
                        )
                    ) ,
                ) ,
                'recursive' => 0
            ));
            if (!empty($user['UserAvatar']['id'])) {
                $this->request->data['UserAvatar']['id'] = $user['UserAvatar']['id'];
            }
            if (!empty($this->request->data['Company']['country_id'])) {
                $temp_country_id = $this->request->data['Company']['country_id'];
                $this->request->data['Company']['country_id'] = $this->Company->Country->findCountryIdFromIso2($this->request->data['Company']['country_id']);
            }
            if (!empty($this->request->data['State']['name'])) {
                $this->request->data['Company']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->Company->State->findOrSaveAndGetId($this->request->data['State']['name']);
            } else {
                //if state name is empty then it will update as 0 in company table
                $this->request->data['Company']['state_id'] = 0;
            }
            $this->request->data['Company']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->Company->City->findOrSaveCityAndGetId($this->request->data['City']['name'], $this->request->data['Company']['state_id'], $this->request->data['Company']['country_id'], $this->request->data['Company']['latitude'], $this->request->data['Company']['longitude']);
            unset($this->Company->validate['city_id']);
            unset($this->Company->validate['state_id']);
            $this->Company->State->set($this->request->data);
            $this->Company->City->set($this->request->data);
            $this->Company->set($this->request->data['Company']);
            unset($this->Company->City->validate['City']);
            if ($this->Company->validates() && $this->Company->State->validates() && $this->Company->City->validates()) {
                if ($this->Company->save($this->request->data, false)) {
                    if (!empty($this->request->data['UserProfile']['language_id'])) {
                        $this->Company->User->UserProfile->updateAll(array(
                            'UserProfile.language_id' => $this->request->data['UserProfile']['language_id']
                        ) , array(
                            'UserProfile.user_id' => $this->Auth->user('id')
                        ));
                    }
                    if ($this->request->data['UserProfile']['language_id'] != $user['UserProfile']['language_id']) {
                        $this->Company->User->UserProfile->User->UserLogin->updateUserLanguage();
                    }
                    if (!empty($this->request->data['User']['UserProfile']['paypal_account'])) {
                        $this->Company->User->UserProfile->updateAll(array(
                            'UserProfile.paypal_account' => '\'' . $this->request->data['User']['UserProfile']['paypal_account'] . '\'',
                            'UserProfile.language_id' => '\'' . $this->request->data['UserProfile']['language_id'] . '\''
                        ) , array(
                            'UserProfile.user_id' => $this->Auth->user('id')
                        ));
                    }
                    $this->Session->setFlash(__l('Merchant has been updated') , 'default', null, 'success');
                    if (!empty($this->request->params['form']['is_iframe_submit'])) {
                        $this->layout = 'ajax';
                    }
                    $this->request->data['Company']['country_id'] = $temp_country_id;
					$this->redirect(array(
                    'controller' => 'companies',
                    'action' => 'edit',
                    $this->Auth->user('id'),
                    'admin' => false
                ));
                } else {
                    $this->request->data['Company']['country_id'] = $temp_country_id;
                    $this->Session->setFlash(__l('Merchant could not be updated. Please, try again.') , 'default', null, 'error');
                }
                if ($this->Company->User->isAllowed($this->Auth->user('user_type_id'))) {
                    $ajax_url = Router::url(array(
                        'controller' => 'users',
                        'action' => 'my_stuff',
                    ) , true);
                    $this->redirect(array(
                    'controller' => 'companies',
                    'action' => 'edit',
                    $this->Auth->user('id'),
                    'admin' => false
					));
                }
            } else {
                $this->request->data['Company']['country_id'] = $temp_country_id;
                $this->Session->setFlash(__l('Merchant could not be updated. Please, try again.') , 'default', null, 'error');
            }
            $data = $this->Company->find('first', array(
                'conditions' => array(
                    'Company.id = ' => $id,
                ) ,
                'contain' => array(
                    'User' => array(
                        'UserAvatar' => array(
                            'fields' => array(
                                'UserAvatar.id',
                                'UserAvatar.dir',
                                'UserAvatar.filename',
                                'UserAvatar.width',
                                'UserAvatar.height'
                            )
                        ) ,
                        'UserProfile' => array(
                            'fields' => array(
                                'UserProfile.paypal_account',
                                'UserProfile.language_id'
                            )
                        ) ,
                        'fields' => array(
                            'User.user_type_id',
                            'User.username',
                            'User.id',
                            'User.available_balance_amount',
                            'User.email',
                            'User.fb_user_id',
                        )
                    ) ,
                ) ,
                'recursive' => 2
            ));
            $this->request->data['User'] = $data['User'];
        } else {
            unset($this->Company->City->validate['City']);
            $this->request->data = $this->Company->find('first', array(
                'conditions' => array(
                    'Company.user_id = ' => $id,
                ) ,
                'contain' => array(
                    'User' => array(
                        'UserAvatar' => array(
                            'fields' => array(
                                'UserAvatar.id',
                                'UserAvatar.dir',
                                'UserAvatar.filename',
                                'UserAvatar.width',
                                'UserAvatar.height'
                            )
                        ) ,
                        'UserProfile' => array(
                            'fields' => array(
                                'UserProfile.paypal_account',
                                'UserProfile.language_id'
                            )
                        ) ,
                        'fields' => array(
                            'User.user_type_id',
                            'User.username',
                            'User.id',
                            'User.available_balance_amount',
                            'User.email',
                            'User.fb_user_id',
                        )
                    ) ,
                    'City' => array(
                        'fields' => array(
                            'City.name'
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.name'
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name',
                            'Country.iso2'
                        )
                    ) ,
                ) ,
                'recursive' => 2
            ));
        }
		if (empty($this->request->data)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data['Company']['City'])) {
            $this->request->data['City']['name'] = $this->request->data['Company']['City']['name'];
        }
        if (!empty($this->request->data['Company']['State']['name'])) {
            $this->request->data['State']['name'] = $this->request->data['Company']['State']['name'];
        }
        if (!empty($this->request->data['Country']['iso2'])) {
            $this->request->data['Company']['country_id'] = $this->request->data['Country']['iso2'];
        }
        $this->pageTitle.= ' - ' . $this->request->data['Company']['name'];
        $countries = $this->Company->Country->find('list', array(
            'fields' => array(
                'Country.iso2',
                'Country.name'
            )
        ));
        //get languages
        $languageLists = $this->Company->User->UserProfile->Language->Translation->find('all', array(
            'conditions' => array(
                'Language.id !=' => 0
            ) ,
            'fields' => array(
                'DISTINCT(Translation.language_id)',
                'Language.name',
                'Language.id'
            ) ,
            'order' => array(
                'Language.name' => 'ASC'
            )
        ));
        $languages = array();
        if (!empty($languageLists)) {
            foreach($languageLists as $languageList) {
                $languages[$languageList['Language']['id']] = $languageList['Language']['name'];
            }
        }
        //end
        $this->set(compact('countries', 'languages'));
    }
    public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Company->delete($id)) {
            $this->Session->setFlash(__l('Merchant deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index()
    {
        $this->disableCache();
        $this->pageTitle = __l('Merchants');
        if (!empty($this->request->data['Company']['q'])) {
            $this->request->params['named']['q'] = $this->request->data['Company']['q'];
            $this->pageTitle.= __l(' - Search - ') . $this->request->params['named']['q'];
        }
        if (!empty($this->request->data['Company']['main_filter_id'])) {
            $this->request->params['named']['filter_id'] = $this->request->data['Company']['main_filter_id'];
        }
        $this->set('online', $this->Company->find('count', array(
            'conditions' => array(
                'Company.is_online_account = ' => 1,
            ) ,
            'recursive' => -1
        )));
        // total approved users list
        $this->set('offline', $this->Company->find('count', array(
            'conditions' => array(
                'Company.is_online_account = ' => 0,
            ) ,
            'recursive' => -1
        )));
        // total openid users list
        $this->set('all', $this->Company->find('count', array(
            'recursive' => -1
        )));
        $conditions = $count_conditions = array();
        if (!empty($this->request->params['named']['main_filter_id'])) {
            if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::AffiliateUser) {
                $conditions['User.is_affiliate_user'] = 1;
                $this->pageTitle.= __l(' - Affiliate');
            } elseif ($this->request->params['named']['main_filter_id'] == ConstMoreAction::OpenID) {
                $conditions['User.is_openid_register'] = 1;
                $this->pageTitle.= __l(' - Registered through OpenID ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::FaceBook) {
                $conditions['User.is_facebook_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Facebook ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Twitter) {
                $conditions['User.is_twitter_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Twitter ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Gmail) {
                $conditions['User.is_gmail_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Gmail ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Yahoo) {
                $conditions['User.is_yahoo_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Yahoo ');
            }
        }
        if (!empty($this->request->params['named']['main_filter_id'])) {
            if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Online) {
                $conditions['Company.is_online_account'] = 1;
                $this->pageTitle.= __l(' - Online Account');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Offline) {
                $conditions['Company.is_online_account'] = 0;
                $this->pageTitle.= __l(' - Offline Account');
            }
            $this->request->data['Company']['main_filter_id'] = $this->request->params['named']['main_filter_id'];
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
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Company.created) <= '] = 0;
            $this->pageTitle.= __l(' - Registered today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Company.created) <= '] = 7;
            $this->pageTitle.= __l(' - Registered in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Company.created) <= '] = 30;
            $this->pageTitle.= __l(' - Registered in this month');
        }
        if ($this->RequestHandler->prefers('csv')) {
            Configure::write('debug', 0);
            $this->set('company', $this);
            $this->set('conditions', $conditions);
            if (isset($this->request->data['Company']['q'])) {
                $this->set('q', $this->request->data['Company']['q']);
            }
            $this->set('contain', $contain);
        } else {
            $this->paginate = array(
                'conditions' => array(
                    $conditions,
                ) ,
                'contain' => array(
                    'CompanyAddress' => array(
                        'fields' => array(
                            'CompanyAddress.id',
                            'CompanyAddress.address2',
                        )
                    ) ,
                    'User' => array(
                        'fields' => array(
                            'User.last_logged_in_time',
                            'User.created',
                            'User.user_login_count',
                            'User.email',
                            'User.user_type_id',
                            'User.username',
                            'User.id',
                            'User.available_balance_amount',
                            'User.is_email_confirmed',
                            'User.total_amount_withdrawn',
                            'User.is_active',
                            'User.dns',
                            'User.is_affiliate_user',
                            'User.is_openid_register',
                            'User.is_gmail_register',
                            'User.is_yahoo_register',
                            'User.is_facebook_register',
                            'User.is_twitter_register',
                            'User.is_iphone_user',
                            'User.is_android_user',
                            'User.is_foursquare_register',
                            'User.fb_user_id'
                        ) ,
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
                        'UserAvatar'
                    )
                ) ,
                'order' => array(
                    'Company.id' => 'desc'
                ) ,
                'recursive' => 3,
            );
            if (!empty($this->request->params['named']['q'])) {
                $this->paginate = array_merge($this->paginate, array(
                    'search' => $this->request->params['named']['q']
                ));
                $this->request->data['Company']['q'] = $this->request->params['named']['q'];
            }
            if (!empty($this->request->params['named']['main_filter_id']) && $this->request->params['named']['main_filter_id'] == ConstMoreAction::Offline) {
                $moreActions[ConstMoreAction::DeductAmountFromWallet] = __l('Set As Paid');
            } else {
                $moreActions = $this->Company->moreActions;
            }
            $this->set(compact('moreActions'));
            $this->set('companies', $this->paginate());
            $this->set('pageTitle', $this->pageTitle);
            // total approved users list
            $this->set('active', $this->Company->find('count', array(
                'conditions' => array(
                    'User.is_active' => 1,
                ) ,
                'recursive' => 1
            )));
            // total approved users list
            $this->set('inactive', $this->Company->find('count', array(
                'conditions' => array(
                    'User.is_active' => 0,
                ) ,
                'recursive' => 1
            )));
            $this->set('affiliate_user_count', $this->Company->User->find('count', array(
                'conditions' => array(
                    'User.is_affiliate_user' => 1,
                    'User.user_type_id' => ConstUserTypes::Company,
                ) ,
                'recursive' => -1
            )));
        }
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Merchant');
        $this->loadModel('EmailTemplate');
        $temp_country_id = '';
        $this->Company->User->UserAvatar->Behaviors->attach('ImageUpload', Configure::read('avatar.file'));
        if (!empty($this->request->data)) {
            //state and country looking
            if (!empty($this->request->data['Company']['country_id'])) {
                $temp_country_id = $this->request->data['Company']['country_id'];
                $this->request->data['Company']['country_id'] = $this->Company->Country->findCountryIdFromIso2($this->request->data['Company']['country_id']);
            }
            if (!empty($this->request->data['State']['name'])) {
                $this->request->data['Company']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->Company->State->findOrSaveAndGetId($this->request->data['State']['name']);
            }
            if (!empty($this->request->data['City']['name'])) {
                $this->request->data['Company']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->Company->City->findOrSaveCityAndGetId($this->request->data['City']['name'], $this->request->data['Company']['state_id'], $this->request->data['Company']['country_id'], $this->request->data['Company']['latitude'], $this->request->data['Company']['longitude']);
            }
            if (!empty($this->request->data['UserAvatar']['filename']['name'])) {
                $this->request->data['UserAvatar']['filename']['type'] = get_mime($this->request->data['UserAvatar']['filename']['tmp_name']);
            }
            if (!empty($this->request->data['UserAvatar']['filename']['name']) || !Configure::read('avatar.file.allowEmpty')) {
                $this->Company->User->UserAvatar->set($this->request->data);
            }
            $ini_upload_error = 1;
            if ($this->request->data['UserAvatar']['filename']['error'] == 1) {
                $ini_upload_error = 0;
            }
            $this->Company->create();
            $this->Company->set($this->request->data);
            $this->Company->User->set($this->request->data);
            $this->Company->State->set($this->request->data);
            $this->Company->City->set($this->request->data);
            unset($this->Company->City->validate['City']);
            if ($this->Company->User->validates() &$this->Company->validates() &$this->Company->City->validates() &$this->Company->State->validates() &$this->Company->User->UserAvatar->validates() && $ini_upload_error) {
                if (empty($this->request->data['Company']['user_id'])) {
                    $this->request->data['User']['user_type_id'] = ConstUserTypes::Company;
                    $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['passwd']);
                    if ($this->request->data['Company']['is_online_account']) {
                        $this->request->data['User']['is_email_confirmed'] = '1';
                        $this->request->data['User']['is_active'] = '1';
                    } else {
                        $this->request->data['User']['is_email_confirmed'] = '0';
                        $this->request->data['User']['is_active'] = '0';
                    }
                    if ($this->Company->User->save($this->request->data)) {
                        $user_id = $this->Company->User->getLastInsertId();
                        $this->request->data['Company']['user_id'] = $user_id;
                        $this->request->data['UserProfile']['user_id'] = $user_id;
                        $this->request->data['UserProfile']['address'] = $this->request->data['Company']['address1'];
                        $this->request->data['UserProfile']['city_id'] = $this->request->data['Company']['city_id'];
                        $this->request->data['UserProfile']['state_id'] = $this->request->data['Company']['state_id'];
                        $this->request->data['UserProfile']['zip_code'] = $this->request->data['Company']['zip'];
                        $this->request->data['UserProfile']['paypal_account'] = $this->request->data['User']['UserProfile']['paypal_account'];
                        $this->Company->User->UserProfile->create();
                        $this->Company->User->UserProfile->save($this->request->data);
                        if (!empty($this->request->data['UserAvatar']['filename']['name'])) {
                            $this->Attachment->create();
                            $this->request->data['UserAvatar']['class'] = 'UserAvatar';
                            $this->request->data['UserAvatar']['foreign_id'] = $user_id;
                            $this->Attachment->save($this->request->data['UserAvatar']);
                        }
                    }
                }
                if ($this->Company->save($this->request->data)) {
                    if (!empty($this->request->data['Company']['is_online_account'])) {
                        $email = $this->EmailTemplate->selectTemplate('Admin User Add');
                        $emailFindReplace = array(
                            '##FROM_EMAIL##' => $this->Company->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']) ,
                            '##USERNAME##' => $this->request->data['User']['username'],
                            '##LOGINLABEL##' => ucfirst(Configure::read('user.using_to_login')) ,
                            '##USEDTOLOGIN##' => $this->request->data['User'][Configure::read('user.using_to_login') ],
                            '##SITE_NAME##' => Configure::read('site.name') ,
                            '##PASSWORD##' => $this->request->data['User']['passwd'],
                            '##SITE_LINK##' => Router::url('/', true) ,
                            '##CONTACT_URL##' => Router::url(array(
                                'controller' => 'contacts',
                                'action' => 'add',
                                'city' => $this->request->params['named']['city'],
                                'admin' => false
                            ) , true) ,
                            '##SITE_LOGO##' => Router::url(array(
                                'controller' => 'img',
                                'action' => 'theme-image',
                                'logo-email.png',
                                'admin' => false
                            ) , true) ,
                        );
                        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                        $this->Email->to = $this->request->data['User']['email'];
                        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                    }
                    $this->Session->setFlash(__l('Merchant has been added') , 'default', null, 'success');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                } else {
                    $this->request->data['Company']['country_id'] = $temp_country_id;
                    $this->Session->setFlash(__l('Merchant could not be added. Please, try again.') , 'default', null, 'error');
                }
            } else {
                $this->request->data['Company']['country_id'] = $temp_country_id;
                $this->Session->setFlash(__l('Merchant could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        unset($this->Company->City->validate['City']);
        $countries = $this->Company->Country->find('list', array(
            'fields' => array(
                'Country.iso2',
                'Country.name'
            )
        ));
        $this->set(compact('countries'));
        unset($this->request->data['User']['passwd']);
        unset($this->request->data['User']['confirm_password']);
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Merchant');
        $temp_country_id = '';
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Company->User->UserAvatar->Behaviors->attach('ImageUpload', Configure::read('avatar.file'));
        $id = (!empty($this->request->data['Company']['id'])) ? $this->request->data['Company']['id'] : $id;
		$this->set('id', $id);
        $company = $this->Company->find('first', array(
            'conditions' => array(
                'Company.id' => $id
            ) ,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.email',
                        'User.user_type_id',
                        'User.username',
                        'User.id',
                    ) ,
                    'UserAvatar'
                )
            ) ,
            'fields' => array(
                'Company.id',
                'Company.user_id',
            ) ,
        ));
        if (!empty($this->request->data)) {
            if (!empty($company)) {
                if (!empty($company['User']['UserAvatar']['id'])) {
                    $this->request->data['UserAvatar']['id'] = $company['User']['UserAvatar']['id'];
                }
            }
            if (!empty($this->request->data['UserAvatar']['filename']['name'])) {
                $this->request->data['UserAvatar']['filename']['type'] = get_mime($this->request->data['UserAvatar']['filename']['tmp_name']);
            }
            if (!empty($this->request->data['UserAvatar']['filename']['name']) || (!Configure::read('avatar.file.allowEmpty') && empty($this->request->data['UserAvatar']['id']))) {
                $this->Company->User->UserAvatar->set($this->request->data);
            }
            $ini_upload_error = 1;
            if ($this->request->data['UserAvatar']['filename']['error'] == 1) {
                $ini_upload_error = 0;
            }
            //state and country looking
            if (!empty($this->request->data['Company']['country_id'])) {
                $temp_country_id = $this->request->data['Company']['country_id'];
                $this->request->data['Company']['country_id'] = $this->Company->Country->findCountryIdFromIso2($this->request->data['Company']['country_id']);
            }
            if (!empty($this->request->data['State']['name'])) {
                $this->request->data['Company']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->Company->State->findOrSaveAndGetId($this->request->data['State']['name']);
            } else {
                //if state name is empty then it will update as 0 in company table
                $this->request->data['Company']['state_id'] = 0;
            }
            $this->request->data['Company']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->Company->City->findOrSaveCityAndGetId($this->request->data['City']['name'], $this->request->data['Company']['state_id'], $this->request->data['Company']['country_id'], $this->request->data['Company']['latitude'], $this->request->data['Company']['longitude']);
            $this->Company->set($this->request->data);
            $this->Company->State->set($this->request->data);
            $this->Company->City->set($this->request->data);
            $this->Company->User->set($this->request->data);
            unset($this->Company->City->validate['City']);
            unset($this->Company->validate['state_id']);
            if ($company['User']['email'] == $this->request->data['User']['email']) {
                unset($this->Company->User->validate['email']['rule3']);
            }
            if ($this->Company->validates() &$this->Company->City->validates() &$this->Company->State->validates() &$this->Company->User->validates() &$this->Company->User->UserAvatar->validates() && $ini_upload_error) {
                if ($this->Company->save($this->request->data)) {
                    if ($this->request->data['Company']['is_online_account']) {
                        $this->request->data['User']['is_email_confirmed'] = '1';
                        $this->request->data['User']['is_active'] = '1';
                    } else {
                        $this->request->data['User']['is_email_confirmed'] = '0';
                        $this->request->data['User']['is_active'] = '0';
                    }
                    $this->request->data['User']['id'] = $company['Company']['user_id'];
                    $this->Company->User->save($this->request->data);
                    $this->Company->User->UserProfile->updateAll(array(
                        'UserProfile.paypal_account' => '\'' . $this->request->data['User']['UserProfile']['paypal_account'] . '\''
                    ) , array(
                        'UserProfile.user_id' => $company['Company']['user_id']
                    ));
                    if (!empty($this->request->data['UserAvatar']['filename']['name'])) {
                        $this->request->data['UserAvatar']['class'] = 'UserAvatar';
                        $this->request->data['UserAvatar']['foreign_id'] = $company['Company']['user_id'];
                        $this->Attachment->save($this->request->data['UserAvatar']);
                    }
                    $this->Session->setFlash(__l('Merchant has been updated') , 'default', null, 'success');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                } else {
                    $this->request->data['Company']['country_id'] = $temp_country_id;
                    $this->Session->setFlash(__l('Merchant could not be updated. Please, try again.') , 'default', null, 'error');
                }
            } else {
                $this->request->data['Company']['country_id'] = $temp_country_id;
                $this->Session->setFlash(__l('Merchant could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Company->find('first', array(
                'conditions' => array(
                    'Company.id ' => $id,
                ) ,
                'contain' => array(
                    'User' => array(
                        'UserAvatar' => array(
                            'fields' => array(
                                'UserAvatar.id',
                                'UserAvatar.dir',
                                'UserAvatar.filename',
                                'UserAvatar.width',
                                'UserAvatar.height'
                            )
                        ) ,
                        'UserProfile' => array(
                            'fields' => array(
                                'UserProfile.first_name',
                                'UserProfile.last_name',
                                'UserProfile.middle_name',
                                'UserProfile.gender_id',
                                'UserProfile.about_me',
                                'UserProfile.address',
                                'UserProfile.country_id',
                                'UserProfile.state_id',
                                'UserProfile.city_id',
                                'UserProfile.zip_code',
                                'UserProfile.dob',
                                'UserProfile.language_id',
                                'UserProfile.paypal_account'
                            ) ,
                        ) ,
                    ) ,
                    'City' => array(
                        'fields' => array(
                            'City.name'
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.name'
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name',
                            'Country.iso2'
                        )
                    ) ,
                    'CompanyAddress' => array(
                        'City' => array(
                            'fields' => array(
                                'City.name'
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.name'
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.name',
                                'Country.iso2'
                            )
                        ) ,
                        'order' => array(
                            'CompanyAddress.id' => 'desc'
                        )
                    ) ,
                ) ,
                'recursive' => 2
            ));
            if (!empty($this->request->data['City'])) {
                $this->request->data['City']['name'] = $this->request->data['City']['name'];
            }
            if (!empty($this->request->data['State']['name'])) {
                $this->request->data['State']['name'] = $this->request->data['State']['name'];
            }
            if (!empty($this->request->data['Country']['iso2'])) {
                $this->request->data['Company']['country_id'] = $this->request->data['Country']['iso2'];
            }
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        unset($this->Company->City->validate['City']);
        $this->pageTitle.= ' - ' . $this->request->data['Company']['name'];
        $users = $this->Company->User->find('list');
        $countries = $this->Company->Country->find('list', array(
            'fields' => array(
                'Country.iso2',
                'Country.name'
            )
        ));
        $this->set(compact('countries', 'users'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $company = $this->Company->find('first', array(
            'conditions' => array(
                'Company.id' => $id,
            ) ,
            'recursive' => -1
        ));
        if (!empty($company['Company']['user_id']) && $this->Company->User->delete($company['Company']['user_id'])) {
            $this->Session->setFlash(__l('Merchant deleted') , 'default', null, 'success');
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
        if (!empty($this->request->data['Company'])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $companyIds = array();
            foreach($this->request->data['Company'] as $company_id => $is_checked) {
                if ($is_checked['id']) {
                    $companyIds[] = $company_id;
                }
            }
            if ($actionid && !empty($companyIds)) {
                if ($actionid == ConstMoreAction::EnableCompanyProfile) {
                    $this->Company->updateAll(array(
                        'Company.is_company_profile_enabled' => 1
                    ) , array(
                        'Company.id' => $companyIds
                    ));
                    $this->Session->setFlash(__l('Checked merchants has been enabled') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::DisableCompanyProfile) {
                    $this->Company->updateAll(array(
                        'Company.is_company_profile_enabled' => 0
                    ) , array(
                        'Company.id' => $companyIds
                    ));
                    $this->Session->setFlash(__l('Checked merchants has been disabled') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Active) {
                    foreach($companyIds as $companyId) {
                        $get_company_user = $this->Company->find('first', array(
                            'conditions' => array(
                                'Company.id' => $companyId
                            ) ,
                            'recursive' => -1
                        ));
                        $this->Company->User->updateAll(array(
                            'User.is_active' => 1
                        ) , array(
                            'User.id' => $get_company_user['Company']['user_id']
                        ));
                        $this->_sendAdminActionMail($companyId, 'Admin User Active');
                    }
                    $this->Session->setFlash(__l('Checked merchants user has been activated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Inactive) {
                    foreach($companyIds as $companyId) {
                        $get_company_user = $this->Company->find('first', array(
                            'conditions' => array(
                                'Company.id' => $companyId
                            ) ,
                            'recursive' => -1
                        ));
                        $this->Company->User->updateAll(array(
                            'User.is_active' => 0
                        ) , array(
                            'User.id' => $get_company_user['Company']['user_id']
                        ));
                        $this->_sendAdminActionMail($companyId, 'Admin User Deactivate');
                    }
                    $this->Session->setFlash(__l('Checked merchants user has been deactivated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::DeductAmountFromWallet) {
                    $this->Session->write('companies_list.data', $companyIds);
                    $this->redirect(array(
                        'controller' => 'companies',
                        'action' => 'admin_deductamount',
                        'admin' => true
                    ));
                }
            }
        }
        if (!$this->RequestHandler->isAjax()) {
            $this->redirect(Router::url('/', true) . $r);
        } else {
            $this->redirect($r);
        }
    }
    public function _sendAdminActionMail($company_id, $email_template)
    {
        $this->loadModel('EmailTemplate');
        $company = $this->Company->find('first', array(
            'conditions' => array(
                'Company.id' => $company_id
            ) ,
            'fields' => array(
                'Company.id',
                'Company.name',
            ) ,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.email',
                    )
                )
            ) ,
            'recursive' => 1
        ));
        if (!empty($company['User']['email'])) {
            $email = $this->EmailTemplate->selectTemplate($email_template);
            $emailFindReplace = array(
                '##SITE_LINK##' => Router::url('/', true) ,
                '##USERNAME##' => $company['User']['username'],
                '##SITE_NAME##' => Configure::read('site.name') ,
                '##FROM_EMAIL##' => $this->Company->User->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']) ,
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
            $this->Email->to = $this->Company->User->formatToAddress($company);
            $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
            $this->Email->subject = strtr($email['subject'], $emailFindReplace);
            $this->Email->send(strtr($email['email_content'], $emailFindReplace));
        }
    }
    public function admin_deductamount($companies_list = null)
    {
        if (empty($companies_list)) {
            $companies_list = $this->Session->read('companies_list.data');
        }
        if (!empty($companies_list)) {
            $companies = $this->Company->find('all', array(
                'conditions' => array(
                    'Company.id' => $companies_list
                ) ,
                'contain' => array(
                    'User' => array(
                        'fields' => array(
                            'User.user_type_id',
                            'User.username',
                            'User.id',
                            'User.available_balance_amount'
                        )
                    )
                ) ,
                'recursive' => 0
            ));
            $this->set('companies', $companies);
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            foreach($this->request->data['Company'] as $company_id => $company) {
                $get_company = $this->Company->find('first', array(
                    'conditions' => array(
                        'Company.id' => $company_id
                    ) ,
                    'contain' => array(
                        'User' => array(
                            'fields' => array(
                                'User.user_type_id',
                                'User.username',
                                'User.id',
                                'User.available_balance_amount'
                            )
                        )
                    ) ,
                    'recursive' => 0
                ));
                if ($this->request->data['Company'][$company_id]['amount'] > $get_company['User']['available_balance_amount']) {
                    $this->Company->validationErrors[$company_id]['amount'] = __l('Should be less than available balance amount');
                }
                if (empty($company['amount'])) {
                    $this->Company->validationErrors[$company_id]['amount'] = __l('Required');
                }
            }
            if (empty($this->Company->validationErrors)) {
                $transactions = array();
                $transactions['Transaction']['foreign_id'] = $this->Auth->user('id');
                foreach($this->request->data['Company'] as $company_id => $company) {
                    $transactions['Transaction']['user_id'] = $company['user_id'];
					$transactions['Transaction']['receiver_user_id'] = $this->Auth->user('id');
                    $transactions['Transaction']['class'] = 'SecondUser';
                    $transactions['Transaction']['amount'] = $company['amount'];
                    $transactions['Transaction']['description'] = $company['description'];
                    $transactions['Transaction']['transaction_type_id'] = ConstTransactionTypes::DeductedAmountForOfflineCompany;
                    $this->Company->User->Transaction->log($transactions);
                    $this->Company->User->updateAll(array(
                        'User.available_balance_amount' => 'User.available_balance_amount -' . $company['amount'],
                    ) , array(
                        'User.id' => $company['user_id']
                    ));
                }
                $this->Session->delete('companies_list');
                $this->Session->setFlash(__l('Amount deducted for the selected companies') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'companies',
                    'action' => 'index',
                    'main_filter_id' => ConstMoreAction::Offline,
                    'admin' => true
                ));
            } else {
                $this->Session->setFlash(__l('Amount could not be deducted for the selected companies. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    function dashboard()
    {
        $this->pageTitle = __l('Merchant Dashboard');
    }
    function stats()
    {
        $companies = $this->Company->find('first', array(
            'conditions' => array(
                'Company.user_id' => $this->Auth->user('id')
            ) ,
            'contain' => array(
                'CompanyAddress'
            ) ,
            'recursive' => 2
        ));
        $dealStatuses = $this->Company->Deal->DealStatus->find('list');
        $this->set('companies', $companies);
        $this->set('dealStatuses', $dealStatuses);
    }
    function admin_merchant_stats()
    {
        $this->pageTitle = __l('Merchant Snapshot');
        $this->set('pageTitle', $this->pageTitle);
    }
    function get_company_address($comapny_id)
    {
        $conditions = array();
        $company_addresses_city = array();
        $conditions['CompanyAddress.company_id'] = $comapny_id;
        $company_addresses = $this->Company->CompanyAddress->find('all', array(
            'conditions' => $conditions,
            'fields' => array(
                'CompanyAddress.id',
                'CompanyAddress.address1',
            ) ,
            'contain' => array(
                'City' => array(
                    'fields' => array(
                        'City.name',
                    )
                ) ,
            ) ,
            'recursive' => 1
        ));
        $main_address = $this->Company->find('first', array(
            'conditions' => array(
                'Company.id' => $comapny_id,
            ) ,
            'contain' => array(
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
            ) ,
            'recursive' => 0
        ));
        foreach($company_addresses as $company_address) {
            $key = $company_address['CompanyAddress']['id'];
            $company_addresses_city[$key] = $company_address['City']['name'] . " (" . $company_address['CompanyAddress']['address1'] . ")";
        }
        $this->set('branch_addresses', $company_addresses_city);
        $this->set('main_address', $main_address);
    }
}
?>
