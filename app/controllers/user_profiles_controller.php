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
class UserProfilesController extends AppController
{
    public $name = 'UserProfiles';
    public $components = array(
        'Email'
    );
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'UserProfile.address2',
            'UserProfile.address',
            'UserAvatar.filename',
            'City',
            'State',
            'UserProfile.latitude',
            'UserProfile.longitude',
            'UserProfile.country_id',
            'City.id',
            'State.id'
        );
        parent::beforeFilter();
    }
    public function edit($user_id = null)
    {
        $this->pageTitle = __l('Edit Profile');
        $this->loadModel('EmailTemplate');
        $temp_country_id = '';
        if (!empty($this->request->data)) {
            if (empty($this->request->data['User']['id'])) {
                $this->request->data['User']['id'] = $this->Auth->user('id');
            }
            if (!empty($this->request->data['UserProfile']['country_id'])) {
                $temp_country_id = $this->request->data['UserProfile']['country_id'];
                $this->request->data['UserProfile']['country_id'] = $this->UserProfile->Country->findCountryIdFromIso2($this->request->data['UserProfile']['country_id']);
            }
            $user = $this->UserProfile->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data['User']['id']
                ) ,
                'contain' => array(
                    'UserProfile' => array(
                        'fields' => array(
                            'UserProfile.id',
                            'UserProfile.language_id',
                        )
                    ) ,
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
            if (!empty($user)) {
                $this->request->data['UserProfile']['id'] = $user['UserProfile']['id'];
                if (!empty($user['UserAvatar']['id'])) {
                    $this->request->data['UserAvatar'] = $user['UserAvatar'];
                }
            }
            $this->request->data['UserProfile']['user_id'] = $this->request->data['User']['id'];
            $this->UserProfile->set($this->request->data);
            $this->UserProfile->User->set($this->request->data);
            $this->UserProfile->State->set($this->request->data);
            $this->UserProfile->City->set($this->request->data);
            unset($this->UserProfile->City->validate['City']);
            if ($this->UserProfile->User->validates() &$this->UserProfile->validates() &$this->UserProfile->City->validates() &$this->UserProfile->State->validates()) {
                if (!empty($this->request->data['State']['name'])) {
                    $this->request->data['UserProfile']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->UserProfile->State->findOrSaveAndGetId($this->request->data['State']['name']);
                } else {
                    $this->request->data['UserProfile']['state_id'] = 0;
                }
                $this->request->data['UserProfile']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->UserProfile->City->findOrSaveCityAndGetId($this->request->data['City']['name'], $this->request->data['UserProfile']['state_id'], $this->request->data['UserProfile']['country_id'], $this->request->data['UserProfile']['latitude'], $this->request->data['UserProfile']['longitude']);
                if ($this->UserProfile->save($this->request->data)) {
                    $this->UserProfile->User->save($this->request->data['User']);
                    if ($this->request->data['UserProfile']['language_id'] != $user['UserProfile']['language_id']) {
                        $this->UserProfile->User->UserLogin->updateUserLanguage();
                    }
                }
                $this->Session->setFlash(__l('User Profile has been updated') , 'default', null, 'success');
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin and $this->Auth->user('id') != $this->request->data['User']['id'] and Configure::read('user.is_mail_to_user_for_profile_edit')) {
                    // Send mail to user to activate the account and send account details
                    $language_code = $this->UserProfile->getUserLanguageIso($user['User']['id']);
                    $email = $this->EmailTemplate->selectTemplate('Admin User Edit', $language_code);
                    $emailFindReplace = array(
                        '##FROM_EMAIL##' => $this->UserProfile->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']) ,
                        '##SITE_LINK##' => Router::url('/', true) ,
                        '##USERNAME##' => $user['User']['username'],
                        '##SITE_NAME##' => Configure::read('site.name') ,
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
                    $this->Email->to = $user['User']['email'];
                    $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                    $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                    $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                }
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin and $this->Auth->user('id') != $this->request->data['User']['id']) {
                    if ($user['User']['is_active'] != $this->request->data['User']['is_active']) {
                        if ($this->request->data['User']['is_active'] == 0) {
                            $this->_sendAdminActionMail($this->request->data['User']['id'], 'Admin User Deactivate');
                        } else if ($this->request->data['User']['is_active'] == 1) {
                            $this->_sendAdminActionMail($this->request->data['User']['id'], 'Admin User Active');
                        }
                    }
                }
            } else {
                $this->request->data['UserProfile']['country_id'] = $temp_country_id;
                $this->Session->setFlash(__l('User Profile could not be updated. Please, try again.') , 'default', null, 'error');
            }
            $user = $this->UserProfile->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data['User']['id']
                ) ,
                'contain' => array(
                    'UserProfile' => array(
                        'fields' => array(
                            'UserProfile.id',
                            'UserProfile.address',
                            'UserProfile.address2',
                            'UserProfile.latitude',
                            'UserProfile.longitude',
                            'UserProfile.city_id',
                            'UserProfile.state_id',
                            'UserProfile.country_id'
                        )
                    ) ,
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
            if (!empty($user['User'])) {
                unset($user['UserProfile']);
                $this->request->data['User'] = array_merge($user['User'], $this->request->data['User']);
                $this->request->data['UserAvatar'] = $user['UserAvatar'];
                $this->request->data['UserProfile']['country_id'] = $temp_country_id;
            }
            //Setting ajax layout when submitting through iframe with jquery ajax form plugin
            if (!empty($this->request->params['form']['is_iframe_submit'])) {
                $this->layout = 'ajax';
            }
        } else {
            unset($this->UserProfile->City->validate['City']);
            if (empty($user_id)) {
                $user_id = $this->Auth->user('id');
            }
			$get_condition = array();
			if($this->Auth->user('user_type_id') == ConstUserTypes::Company) {
				$get_condition['User.id'] = $user_id;
				$get_condition['User.user_type_id'] = ConstUserTypes::Company;
			} else {
				$get_condition['User.id'] = $user_id;
				$get_condition['User.user_type_id != '] = ConstUserTypes::Company;
			}
            $this->request->data = $this->UserProfile->User->find('first', array(
                'conditions' => $get_condition,
                'fields' => array(
                    'User.user_type_id',
                    'User.username',
                    'User.id',
                    'User.email',
                    'User.user_type_id',
                    'User.user_login_count',
                    'User.user_view_count',
                    'User.is_active',
                    'User.is_email_confirmed',
                    'User.fb_user_id',
                    'User.mail_notification',
                ) ,
                'contain' => array(
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
                            'UserProfile.address2',
                            'UserProfile.latitude',
                            'UserProfile.longitude',
                            'UserProfile.country_id',
                            'UserProfile.state_id',
                            'UserProfile.city_id',
                            'UserProfile.zip_code',
                            'UserProfile.dob',
                            'UserProfile.language_id',
                            'UserProfile.paypal_account',
                            'UserProfile.user_id',
                            'UserProfile.user_education_id',
                            'UserProfile.user_employment_id',
                            'UserProfile.user_income_range_id',
                            'UserProfile.user_relationship_id',
                            'UserProfile.own_home',
                            'UserProfile.have_children',
                        ) ,
                        'City' => array(
                            'fields' => array(
                                'City.name'
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.id',
                                'Country.iso2'
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.name'
                            )
                        )
                    )
                ) ,
                'recursive' => 2
            ));
            $this->request->data['UserProfile']['user_id'] = $user_id;
            if (!empty($this->request->data['UserProfile']['City'])) {
                $this->request->data['City']['name'] = $this->request->data['City']['name'] = $this->request->data['City']['name'] = $this->request->data['UserProfile']['City']['name'];
            }
            if (!empty($this->request->data['UserProfile']['State']['name'])) {
                $this->request->data['State']['name'] = $this->request->data['UserProfile']['State']['name'];
            }
            if (!empty($this->request->data['UserProfile']['country_id'])) {
                $this->request->data['UserProfile']['country_id'] = $this->request->data['UserProfile']['Country']['iso2'];
            }
            if (!empty($this->request->data['UserProfile']['dob'])) {
                $this->request->data['UserProfile']['dob'] = _formatDate('Y-m-d', strtotime($this->request->data['UserProfile']['dob']));
            }
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['User']['username'];
        $genders = $this->UserProfile->Gender->find('list');
        $userEducations = $this->UserProfile->UserEducation->find('list', array(
            'conditions' => array(
                'UserEducation.is_active' => 1
            ) ,
            'fields' => array(
                'UserEducation.education'
            )
        ));
        $userEmployments = $this->UserProfile->UserEmployment->find('list', array(
            'conditions' => array(
                'UserEmployment.is_active' => 1
            ) ,
            'fields' => array(
                'UserEmployment.employment'
            )
        ));
        $userIncomeranges = $this->UserProfile->UserIncomeRange->find('list', array(
            'conditions' => array(
                'UserIncomeRange.is_active' => 1
            ) ,
            'fields' => array(
                'UserIncomeRange.income'
            )
        ));
        $userRelationships = $this->UserProfile->UserRelationship->find('list', array(
            'conditions' => array(
                'UserRelationship.is_active' => 1
            ) ,
            'fields' => array(
                'UserRelationship.relationship'
            )
        ));
        $countries = $this->UserProfile->Country->find('list', array(
            'fields' => array(
                'Country.iso2',
                'Country.name'
            ) ,
            'order' => array(
                'Country.name' => 'ASC'
            )
        ));
        //get languages
        //$languages = $this->UserProfile->Language->Translation->get_languages();
        $languageLists = $this->UserProfile->Language->Translation->find('all', array(
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
        $this->set(compact('genders', 'userEducations', 'userEmployments', 'userIncomeranges', 'userRelationships', 'countries', 'languages'));
    }
    public function admin_edit($id = null)
    {
        if (is_null($id) && empty($this->request->data)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->setAction('edit', $id);
    }
    public function admin_user_account($user_id = null)
    {
        $this->pageTitle = __l('My Account');
        if (is_null($user_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->set('pageTitle', $this->pageTitle);
        $this->setAction('my_account', $user_id);
    }
    public function my_account($user_id = null)
    {
        if (is_null($user_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->set('user_id', $user_id);
    }
}
?>