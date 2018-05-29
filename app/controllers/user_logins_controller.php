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
class UserLoginsController extends AppController
{
    public $name = 'UserLogins';
    public function admin_index()
    {
        $this->_redirectPOST2Named(array(
            'user_id',
            'q'
        ));
        $conditions = array();
        $this->pageTitle = __l('User Logins');
        if (!empty($this->request->params['named']['username']) || !empty($this->request->params['named']['user_id'])) {
            $userConditions = !empty($this->request->params['named']['username']) ? array(
                'User.username' => $this->request->params['named']['username']
            ) : array(
                'User.id' => $this->request->params['named']['user_id']
            );
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => $userConditions,
                'fields' => array(
                    'User.user_type_id',
                    'User.username',
                    'User.id',
                    'User.fb_user_id',
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['User.id'] = $this->request->data[$this->modelClass]['user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        if (isset($this->request->params['named']['user_education_id'])) {
            if ($this->request->params['named']['user_education_id'] == 0) {
                $this->request->params['named']['user_education_id'] = NULL;
            }
            $user_profiles = $this->UserLogin->User->UserProfile->find('all', array(
                'conditions' => array(
                    'UserProfile.user_education_id' => $this->request->params['named']['user_education_id'],
                    'User.user_type_id' => ConstUserTypes::User,
                ) ,
                'fields' => array(
                    'UserProfile.user_id',
                ) ,
            ));
            if ($user_profiles) {
                foreach($user_profiles as $user_profile) {
                    $user_ids[] = $user_profile['UserProfile']['user_id'];
                }
                $conditions['UserLogin.user_id'] = $user_ids;
            } else {
                $conditions['UserLogin.user_id'] = 0;
            }
        }
        if (isset($this->request->params['named']['user_employment_id'])) {
            if ($this->request->params['named']['user_employment_id'] == 0) {
                $this->request->params['named']['user_employment_id'] = NULL;
            }
            $user_profiles = $this->UserLogin->User->UserProfile->find('all', array(
                'conditions' => array(
                    'UserProfile.user_employment_id' => $this->request->params['named']['user_employment_id'],
                    'User.user_type_id' => ConstUserTypes::User,
                ) ,
                'fields' => array(
                    'UserProfile.user_id',
                ) ,
            ));
            if ($user_profiles) {
                foreach($user_profiles as $user_profile) {
                    $user_ids[] = $user_profile['UserProfile']['user_id'];
                }
                $conditions['UserLogin.user_id'] = $user_ids;
            } else {
                $conditions['UserLogin.user_id'] = 0;
            }
        }
        if (isset($this->request->params['named']['user_income_range_id'])) {
            if ($this->request->params['named']['user_income_range_id'] == 0) {
                $this->request->params['named']['user_income_range_id'] = NULL;
            }
            $user_profiles = $this->UserLogin->User->UserProfile->find('all', array(
                'conditions' => array(
                    'UserProfile.user_income_range_id' => $this->request->params['named']['user_income_range_id'],
                    'User.user_type_id' => ConstUserTypes::User,
                ) ,
                'fields' => array(
                    'UserProfile.user_id',
                ) ,
            ));
            if ($user_profiles) {
                foreach($user_profiles as $user_profile) {
                    $user_ids[] = $user_profile['UserProfile']['user_id'];
                }
                $conditions['UserLogin.user_id'] = $user_ids;
            } else {
                $conditions['UserLogin.user_id'] = 0;
            }
        }
        if (isset($this->request->params['named']['user_relationship_id'])) {
            if ($this->request->params['named']['user_relationship_id'] == 0) {
                $this->request->params['named']['user_relationship_id'] = NULL;
            }
            $user_profiles = $this->UserLogin->User->UserProfile->find('all', array(
                'conditions' => array(
                    'UserProfile.user_relationship_id' => $this->request->params['named']['user_relationship_id'],
                    'User.user_type_id' => ConstUserTypes::User,
                ) ,
                'fields' => array(
                    'UserProfile.user_id',
                ) ,
            ));
            if ($user_profiles) {
                foreach($user_profiles as $user_profile) {
                    $user_ids[] = $user_profile['UserProfile']['user_id'];
                }
                $conditions['UserLogin.user_id'] = $user_ids;
            } else {
                $conditions['UserLogin.user_id'] = 0;
            }
        }
        if (isset($this->request->params['named']['gender_id'])) {
            if ($this->request->params['named']['gender_id'] == 0) {
                $this->request->params['named']['gender_id'] = NULL;
            }
            $user_profiles = $this->UserLogin->User->UserProfile->find('all', array(
                'conditions' => array(
                    'UserProfile.gender_id' => $this->request->params['named']['gender_id'],
                    'User.user_type_id' => ConstUserTypes::User,
                ) ,
                'fields' => array(
                    'UserProfile.user_id',
                ) ,
            ));
            if ($user_profiles) {
                foreach($user_profiles as $user_profile) {
                    $user_ids[] = $user_profile['UserProfile']['user_id'];
                }
                $conditions['UserLogin.user_id'] = $user_ids;
            } else {
                $conditions['UserLogin.user_id'] = 0;
            }
        }
        if (isset($this->request->params['named']['age_filter'])) {
            $age_conditions = array();
            $age_conditions['User.user_type_id'] = ConstUserTypes::User;
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
            $user_profiles = $this->UserLogin->User->UserProfile->find('all', array(
                'conditions' => array(
                    $age_conditions,
                ) ,
                'fields' => array(
                    'UserProfile.user_id',
                ) ,
            ));
            if ($user_profiles) {
                foreach($user_profiles as $user_profile) {
                    $user_ids[] = $user_profile['UserProfile']['user_id'];
                }
                $conditions['UserLogin.user_id'] = $user_ids;
            } else {
                $conditions['UserLogin.user_id'] = 0;
            }
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'yesterday') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserLogin.created)'] = 1;
            $this->pageTitle.= __l(' - Login Yesterday');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserLogin.created) <= '] = 0;
            $this->pageTitle.= __l(' - Login today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserLogin.created) <= '] = 7;
            $this->pageTitle.= __l(' - Login in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserLogin.created) <= '] = 30;
            $this->pageTitle.= __l(' - Login in this month');
        }
        if (isset($this->request->params['named']['user_type_id']) && $this->request->params['named']['user_type_id'] == ConstUserTypes::User) {
            $conditions['User.user_type_id'] = ConstUserTypes::User;
            $this->pageTitle.= __l(' - User');
        } else if (isset($this->request->params['named']['user_type_id']) && $this->request->params['named']['user_type_id'] == ConstUserTypes::Company) {
            $conditions['User.user_type_id'] = ConstUserTypes::Company;
            $this->pageTitle.= __l(' - Merchant');
        } else if (isset($this->request->params['named']['user_type_id']) && $this->request->params['named']['user_type_id'] == ConstUserTypes::Admin) {
            $conditions['User.user_type_id'] = ConstUserTypes::Admin;
            $this->pageTitle.= __l(' - Admin');
        }
        if (!empty($this->request->params['named']['main_filter_id'])) {
            if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::OpenID) {
                $conditions['User.is_openid_register'] = 1;
                $this->pageTitle.= __l(' - Login through OpenID ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::FaceBook) {
                $conditions['User.is_facebook_register'] = 1;
                $this->pageTitle.= __l(' - Login through Facebook ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Twitter) {
                $conditions['User.is_twitter_register'] = 1;
                $this->pageTitle.= __l(' - Login through Twitter ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Foursquare) {
                $conditions['User.is_foursquare_register'] = 1;
                $this->pageTitle.= __l(' - Login through Foursquare ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Gmail) {
                $conditions['User.is_gmail_register'] = 1;
                $this->pageTitle.= __l(' - Login through Gmail ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::Yahoo) {
                $conditions['User.is_yahoo_register'] = 1;
                $this->pageTitle.= __l(' - Login through Yahoo ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::IphoneUser) {
                $conditions['UserLogin.user_login_type_id'] = ConstUserLoginType::IPhone;
                $this->pageTitle.= __l(' - Login through IPhone ');
            } else if ($this->request->params['named']['main_filter_id'] == ConstMoreAction::AndroidUser) {
                $conditions['UserLogin.user_login_type_id'] = ConstUserLoginType::Android;
                $this->pageTitle.= __l(' - Login through Android ');
            } else if ($this->request->params['named']['main_filter_id'] == 'gift_card') {
                $conditions['User.gift_user_id != '] = NULL;
                $this->pageTitle.= __l(' - Login Via Gift Card');
            } else if ($this->request->params['named']['main_filter_id'] == 'all') {
                $conditions['User.user_type_id != '] = ConstUserTypes::Company;
                $this->pageTitle.= __l(' - All ');
            }
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['UserLogin']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->UserLogin->recursive = 2;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
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
                'User' => array(
                    'UserAvatar',
                )
            ) ,
            'order' => array(
                'UserLogin.id' => 'desc'
            ) ,
        );
        if (isset($this->request->data['UserLogin']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('userLogins', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
        $moreActions = $this->UserLogin->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->UserLogin->delete($id)) {
            $this->Session->setFlash(__l('User Login deleted') , 'default', null, 'success');
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
        if (!empty($this->request->data[$this->modelClass])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $selectedIds = array();
            foreach($this->request->data[$this->modelClass] as $primary_key_id => $is_checked) {
                if ($is_checked['id']) {
                    $selectedIds[] = $primary_key_id;
                }
            }
            if ($actionid && !empty($selectedIds)) {
                if ($actionid == ConstMoreAction::Delete) {
                    $this->{$this->modelClass}->deleteAll(array(
                        $this->modelClass . '.id' => $selectedIds
                    ));
                    $this->Session->setFlash(__l('Checked user logins has been deleted') , 'default', null, 'success');
                }
            }
        }
        if (!$this->RequestHandler->isAjax()) {
            $this->redirect(Router::url('/', true) . $r);
        } else {
            $this->redirect($r);
        }
    }
}
?>