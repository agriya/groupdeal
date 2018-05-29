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
class UserPaymentProfilesController extends AppController
{
    public $name = 'UserPaymentProfiles';
    function beforeFilter()
    {
        if (!$this->UserPaymentProfile->isAuthorizeNetEnabled()) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function index()
    {
        $this->pageTitle = __l('Credit Cards');
        $this->paginate = array(
            'conditions' => array(
                'UserPaymentProfile.user_id' => $this->Auth->user('id')
            ) ,
            'recursive' => 1,
            'order' => array(
                'UserPaymentProfile.id' => 'desc'
            )
        );
        // <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $this->view = 'Json';
            $this->set('json', (empty($this->viewVars['iphone_response'])) ? $this->paginate() : $this->viewVars['iphone_response']);
        } else {
            $this->set('userPaymentProfiles', $this->paginate());
        }
        // For iPhone App code -->

    }
    public function add()
    {
		$resonse = array();
        $this->pageTitle = __l('Add New Credit Card');
        $check_expire = 0;
        if (!empty($this->request->data)) {
            $user = $this->UserPaymentProfile->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->Auth->user('id')
                ) ,
                'fields' => array(
                    'User.id',
                    'User.cim_profile_id'
                )
            ));
            $data = $this->request->data['UserPaymentProfile'];
            $data['expirationDate'] = $this->request->data['UserPaymentProfile']['expDateYear']['year'] . '-' . $this->request->data['UserPaymentProfile']['expDateMonth']['month'];
            $data['customerProfileId'] = $user['User']['cim_profile_id'];
            $check_expire = $this->UserPaymentProfile->_checkExpiryMonthAndYear($this->request->data['UserPaymentProfile']['expDateMonth']['month'], $this->request->data['UserPaymentProfile']['expDateYear']['year']);
            $this->UserPaymentProfile->User->Company->City->State->validate = array_merge($this->UserPaymentProfile->User->Company->City->State->validate, $this->UserPaymentProfile->User->Company->City->State->validateStateName);
            $this->UserPaymentProfile->set($this->request->data);
            $this->UserPaymentProfile->validates();
            // State Validation //
            $this->UserPaymentProfile->User->Company->City->State->set($this->request->data['State']);
            $this->UserPaymentProfile->User->Company->City->State->validates();
            if (empty($this->UserPaymentProfile->validationErrors) &empty($this->UserPaymentProfile->User->Company->City->State->validationErrors) && empty($check_expire)) {
                if (!empty($this->request->data['State']['name'])) {
                    $data['state'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->UserPaymentProfile->User->Company->City->State->findOrSaveAndGetId($this->request->data['State']['name']);
                }
                if (!empty($this->request->data['UserPaymentProfile']['city'])) {
                    $data['city'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->UserPaymentProfile->User->Company->City->findOrSaveAndGetId($this->request->data['UserPaymentProfile']['city']);
                }
                $payment_profile_id = $this->UserPaymentProfile->User->_createCimPaymentProfile($data);
                if (is_array($payment_profile_id) && !empty($payment_profile_id['payment_profile_id']) && !empty($payment_profile_id['masked_cc'])) {
                    $payment['UserPaymentProfile']['user_id'] = $this->Auth->user('id');
                    $payment['UserPaymentProfile']['cim_payment_profile_id'] = $payment_profile_id['payment_profile_id'];
                    $payment['UserPaymentProfile']['masked_cc'] = $payment_profile_id['masked_cc'];
                    $this->UserPaymentProfile->save($payment, false);
                    // <-- For iPhone App code
                    if ($this->RequestHandler->prefers('json')) {
                        $resonse = array(
                            'status' => 0,
                            'message' => __l('Success')
                        );
                    } else {
                        $this->redirect(array(
                            'controller' => 'user_payment_profiles',
                            'action' => 'index'
                        ));
                    }
                    // For iPhone App code -->

                } else {
                    $this->Session->setFlash(sprintf(__l('Gateway error: %s <br>Note: Due to security reasons, error message from gateway may not be verbose. Please double check your card number, security number and address details. Also, check if you have enough balance in your card.') , $payment_profile_id['message']) , 'default', null, 'error');
                    // <-- For iPhone App code
                    if ($this->RequestHandler->prefers('json')) {
                        $resonse = array(
                            'status' => 1,
                            'message' => sprintf(__l('Gateway error: %s <br>Note: Due to security reasons, error message from gateway may not be verbose. Please double check your card number, security number and address details. Also, check if you have enough balance in your card.') , $payment_profile_id['message'])
                        );
                    }
                    // For iPhone App code -->

                }
            } else {
                if (!empty($check_expire)) {
                    $this->Session->setFlash(__l('Invalid expire date') , 'default', null, 'error');
                    // <-- For iPhone App code
                    if ($this->RequestHandler->prefers('json')) {
                        $resonse = array(
                            'status' => 2,
                            'message' => __l('Invalid expire date')
                        );
                    }
                    // For iPhone App code -->

                }
                if (!empty($this->UserPaymentProfile->validationErrors)) {
                    $this->Session->setFlash(__l('Enter all required fields.') , 'default', null, 'error');
                }
            }
        }
        $credit_card_types = array(
            'Visa' => __l('Visa') ,
            'MasterCard' => __l('MasterCard') ,
            'Discover' => __l('Discover') ,
            'Amex' => __l('Amex')
        );
        $this->set('credit_card_types', $credit_card_types);
        $this->set('user_id', $this->Auth->user('id'));
        $countries = $this->UserPaymentProfile->User->UserProfile->Country->find('list', array(
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
        $this->set('countries', $countries);
        $this->request->data['UserPaymentProfile']['cvv2Number'] = $this->request->data['UserPaymentProfile']['creditCardNumber'] = '';
        $states = $this->UserPaymentProfile->User->UserProfile->State->find('list', array(
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
        $this->set('check_expire', $check_expire);
        $this->UserPaymentProfile->User->Company->City->State->validate = array_merge($this->UserPaymentProfile->User->Company->City->State->validate, $this->UserPaymentProfile->User->Company->City->State->validateStateName);
        // <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $this->view = 'Json';
            $this->set('json', (empty($this->viewVars['iphone_response'])) ? $resonse : $this->viewVars['iphone_response']);
        }
        // For iPhone App code -->

    }
    public function edit($id)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = __l('Edit Credit Card');
        $user = $this->UserPaymentProfile->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'User.id',
                'User.cim_profile_id'
            )
        ));
        $userPaymentProfile = $this->UserPaymentProfile->find('first', array(
            'conditions' => array(
                'UserPaymentProfile.id' => $id
            ) ,
            'recursive' => -1
        ));
        if (empty($userPaymentProfile)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            $check_expire = $this->UserPaymentProfile->_checkExpiryMonthAndYear($this->request->data['UserPaymentProfile']['expDateMonth']['month'], $this->request->data['UserPaymentProfile']['expDateYear']['year']);
            if (empty($check_expire)) {
                unset($this->UserPaymentProfile->validate['cvv2Number']['rule2']);
                unset($this->UserPaymentProfile->validate['creditCardNumber']['rule2']);
                $this->UserPaymentProfile->set($this->request->data);
                $this->UserPaymentProfile->validates();
                $this->UserPaymentProfile->User->Company->City->State->validate = array_merge($this->UserPaymentProfile->User->Company->City->State->validate, $this->UserPaymentProfile->User->Company->City->State->validateStateName);
                $this->UserPaymentProfile->User->Company->City->State->set($this->request->data['State']);
                $this->UserPaymentProfile->User->Company->City->State->validates();
                if (empty($this->UserPaymentProfile->validationErrors) &empty($this->UserPaymentProfile->User->Company->City->State->validationErrors)) {
                    $data = $this->request->data['UserPaymentProfile'];
                    if (!empty($this->request->data['State']['name'])) {
                        $data['state'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->UserPaymentProfile->User->Company->City->State->findOrSaveAndGetId($this->request->data['State']['name']);
                    }
                    if (!empty($this->request->data['UserPaymentProfile']['city'])) {
                        $data['city'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->UserPaymentProfile->User->Company->City->findOrSaveAndGetId($this->request->data['UserPaymentProfile']['city']);
                    }
                    $data['expirationDate'] = $this->request->data['UserPaymentProfile']['expDateYear']['year'] . '-' . $this->request->data['UserPaymentProfile']['expDateMonth']['month'];
                    $data['customerProfileId'] = $user['User']['cim_profile_id'];
                    $data['customerPaymentProfileId'] = $userPaymentProfile['UserPaymentProfile']['cim_payment_profile_id'];
                    $payment_profile_id = $this->UserPaymentProfile->User->_updateCimPaymentProfile($data);
                    if (!is_array($payment_profile_id)) {
                        $this->Session->setFlash(__l('Credit card has been updated.') , 'default', null, 'success');
						$this->redirect(array(
							'controller' => 'users',
							'action' => 'my_stuff#Credit_Cards'
						));
                    } else {
                        $this->Session->setFlash(sprintf(__l('Gateway error: %s <br>Note: Due to security reasons, error message from gateway may not be verbose. Please double check your card number, security number and address details. Also, check if you have enough balance in your card.') , $payment_profile_id['message']) , 'default', null, 'error');
                    }
                } else {
                    $this->Session->setFlash(__l('Enter all required fields.') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('Enter all required fields.') , 'default', null, 'error');
            }
        } else {
            $data['customerProfileId'] = $user['User']['cim_profile_id'];
            $data['customerPaymentProfileId'] = $userPaymentProfile['UserPaymentProfile']['cim_payment_profile_id'];
            $credit_info = $this->UserPaymentProfile->User->_getCimPaymentProfile($data);
            $this->request->data['UserPaymentProfile'] = $credit_info;
            $state = $this->UserPaymentProfile->User->Company->City->State->find('first', array(
                'conditions' => array(
                    'State.id' => $credit_info['state']
                ) ,
                'fields' => array(
                    'State.id',
                    'State.name'
                ) ,
                'recursive' => -1
            ));
            $city = $this->UserPaymentProfile->User->Company->City->find('first', array(
                'conditions' => array(
                    'City.id' => $credit_info['city']
                ) ,
                'fields' => array(
                    'City.id',
                    'City.name'
                ) ,
                'recursive' => -1
            ));
            $this->request->data['State']['name'] = $state['State']['name'];
            $this->request->data['UserPaymentProfile']['city'] = $city['City']['name'];
            $this->request->data['UserPaymentProfile']['id'] = $id;
            $this->request->data['UserPaymentProfile']['cvv2Number'] = 'XXX';
            $this->UserPaymentProfile->User->Company->City->State->validate = array_merge($this->UserPaymentProfile->User->Company->City->State->validate, $this->UserPaymentProfile->User->Company->City->State->validateStateName);
        }
        $credit_card_types = array(
            'Visa' => __l('Visa') ,
            'MasterCard' => __l('MasterCard') ,
            'Discover' => __l('Discover') ,
            'Amex' => __l('Amex')
        );
        $this->set('credit_card_types', $credit_card_types);
        $this->set('user_id', $this->Auth->user('id'));
        $countries = $this->UserPaymentProfile->User->UserProfile->Country->find('list', array(
            'fields' => array(
                'Country.iso2',
                'Country.name'
            ) ,
            'order' => array(
                'Country.name' => 'asc'
            ) ,
        ));
        $this->set('countries', $countries);
        $states = $this->UserPaymentProfile->User->UserProfile->State->find('list', array(
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
        //$this->request->data['UserPaymentProfile']['cvv2Number'] = $this->request->data['UserPaymentProfile']['creditCardNumber'] = '';

    }
    public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $userPaymentProfile = $this->UserPaymentProfile->find('first', array(
            'conditions' => array(
                'UserPaymentProfile.id' => $id
            ) ,
            'recursive' => -1
        ));
        $user = $this->UserPaymentProfile->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'User.id',
                'User.cim_profile_id'
            )
        ));
        $data['customerProfileId'] = $user['User']['cim_profile_id'];
        $data['customerPaymentProfileId'] = $userPaymentProfile['UserPaymentProfile']['cim_payment_profile_id'];
        if ($this->UserPaymentProfile->User->_deleteCimPaymentProfile($data)) {
            if ($this->UserPaymentProfile->delete($userPaymentProfile['UserPaymentProfile']['id'])) {
                $this->Session->setFlash(__l('Credit card deleted') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'my_stuff#Credit_Cards'
                ));
            } else {
                throw new NotFoundException(__l('Invalid request'));
            }
        } else {
            $this->Session->setFlash(__l('Credit card could not be deleted. Please, try again.') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'my_stuff#Credit_Cards'
            ));
        }
    }
    public function update($id)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->UserPaymentProfile->updateAll(array(
            'UserPaymentProfile.is_default' => 0
        ) , array(
            'UserPaymentProfile.user_id' => $this->Auth->user('id')
        ));
        $this->UserPaymentProfile->updateAll(array(
            'UserPaymentProfile.is_default' => 1
        ) , array(
            'UserPaymentProfile.id' => $id
        ));
        $this->Session->setFlash(__l('Default credit card selected successfully') , 'default', null, 'success');
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'my_stuff#Credit_Cards'
        ));
    }
}
?>