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
class CompanyAddressesController extends AppController
{
    public $name = 'CompanyAddresses';
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
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'City',
            'State',
            'CompanyAddress.address1',
            'CompanyAddress.address2',
            'CompanyAddress.latitude',
            'CompanyAddress.longitude',
            'Company.map_zoom_level',
            'CompanyAddress.country_id'
        );
        parent::beforeFilter();
    }
    public function index()
    {
        // company & branch deal status count update and number of user near by company update
        $this->CompanyAddress->Deal->live_deal_status_count_update();
        $this->CompanyAddress->Company->company_near_user_count_update();
        $this->CompanyAddress->company_address_near_user_count_update();
        $this->CompanyAddress->Company->company_near_user_count_update('iphone');
        $this->CompanyAddress->company_address_near_user_count_update('iphone');
        $this->pageTitle = __l('My Branches');
        $companies = $this->CompanyAddress->Company->find('first', array(
            'conditions' => array(
                'Company.user_id' => $this->Auth->user('id')
            ) ,
            'contain' => array(
                'CompanyAddress'
            ) ,
            'recursive' => 2
        ));
        $dealStatuses = $this->CompanyAddress->Deal->DealStatus->find('list');
        $this->set('companies', $companies);
        $this->set('dealStatuses', $dealStatuses);
    }
    public function add()
    {
        $this->pageTitle = __l('Add Merchant Address');
        $temp_country_id = '';
        $this->CompanyAddress->create();
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['CompanyAddress']['country_id'])) {
                $temp_country_id = $this->request->data['CompanyAddress']['country_id'];
                $this->request->data['CompanyAddress']['country_id'] = $this->CompanyAddress->Country->findCountryIdFromIso2($this->request->data['CompanyAddress']['country_id']);
            }
            $this->CompanyAddress->set($this->request->data);
            $this->CompanyAddress->State->set($this->request->data);
            $this->CompanyAddress->City->set($this->request->data);
            unset($this->CompanyAddress->validate['state_id']);
            if ($this->CompanyAddress->validates() &$this->CompanyAddress->City->validates() &$this->CompanyAddress->State->validates()) {
                if (!empty($this->request->data['State']['name'])) {
                    $this->request->data['CompanyAddress']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->CompanyAddress->State->findOrSaveAndGetId($this->request->data['State']['name']);
                } else {
                    $this->request->data['CompanyAddress']['state_id'] = $this->request->data['State']['name'];
                }
                $this->request->data['CompanyAddress']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->CompanyAddress->City->findOrSaveCityAndGetId($this->request->data['City']['name'], $this->request->data['CompanyAddress']['state_id'], $this->request->data['CompanyAddress']['country_id'], $this->request->data['CompanyAddress']['latitude'], $this->request->data['CompanyAddress']['longitude']);
                $this->CompanyAddress->save($this->request->data);
                $this->Session->setFlash(__l('Merchant Address has been added') , 'default', null, 'success');
                if ($this->RequestHandler->isAjax()) {
                    $this->setAction('index');
                } else {
                    $this->redirect(array(
                        'controller' => 'company_addresses',
                        'action' => 'index',
                    ));
                }
            } else {
                $this->request->data['CompanyAddress']['country_id'] = $temp_country_id;
                $this->Session->setFlash(__l('Merchant Address could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        unset($this->CompanyAddress->Company->City->validate['City']);
        if (!empty($this->request->params['named']['company_id'])) {
            $this->request->data['CompanyAddress']['company_id'] = $this->request->params['named']['company_id'];
        }
        $countries = $this->CompanyAddress->Country->find('list', array(
            'fields' => array(
                'Country.iso2',
                'Country.name'
            )
        ));
        $this->set(compact('countries'));
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Merchant Address');
        $temp_country_id = '';
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['CompanyAddress']['country_id'])) {
                $temp_country_id = $this->request->data['CompanyAddress']['country_id'];
                $this->request->data['CompanyAddress']['country_id'] = $this->CompanyAddress->Country->findCountryIdFromIso2($this->request->data['CompanyAddress']['country_id']);
            }
            $this->CompanyAddress->set($this->request->data);
            $this->CompanyAddress->State->set($this->request->data);
            $this->CompanyAddress->City->set($this->request->data);
            unset($this->CompanyAddress->validate['state_id']);
            if ($this->CompanyAddress->validates() &$this->CompanyAddress->City->validates() &$this->CompanyAddress->State->validates()) {
                if (!empty($this->request->data['State']['name'])) {
                    $this->request->data['CompanyAddress']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->CompanyAddress->State->findOrSaveAndGetId($this->request->data['State']['name']);
                } else {
                    //if state name is empty then it will update as 0 in company address table
                    $this->request->data['CompanyAddress']['state_id'] = 0;
                }
                $this->request->data['CompanyAddress']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->CompanyAddress->City->findOrSaveCityAndGetId($this->request->data['City']['name'], $this->request->data['CompanyAddress']['state_id'], $this->request->data['CompanyAddress']['country_id'], $this->request->data['CompanyAddress']['latitude'], $this->request->data['CompanyAddress']['longitude']);
                $this->request->data['CompanyAddress']['id'] = $id;
                $this->CompanyAddress->save($this->request->data);
                $this->request->data['Company']['id'] = $this->request->data['CompanyAddress']['company_id'];
                $this->CompanyAddress->Company->save($this->request->data['Company']);
                $this->Session->setFlash(__l('Merchant Address has been updated') , 'default', null, 'success');
                if ($this->RequestHandler->isAjax()) {
                    $this->setAction('index');
                } else {
                    $this->redirect(array(
                        'controller' => 'company_addresses',
                        'action' => 'index',
                    ));
                }
            } else {
                $this->request->data['CompanyAddress']['country_id'] = $temp_country_id;
                $this->Session->setFlash(__l('Merchant Address could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->CompanyAddress->read(null, $id);
            $countries = $this->CompanyAddress->Country->find('first', array(
                'conditions' => array(
                    'Country.id' => $this->request->data['CompanyAddress']['country_id']
                ) ,
                'fields' => array(
                    'Country.id',
                    'Country.name',
                    'Country.iso2',
                ) ,
                'recursive' => -1
            ));
            $this->request->data['CompanyAddress']['country_id'] = $countries['Country']['iso2'];
            if (empty($this->request->data)) {
            echo "balamurugan";
                //throw new NotFoundException(__l('Invalid request'));
            }
        }
        unset($this->CompanyAddress->Company->City->validate['City']);
        $this->pageTitle.= ' - ' . $this->request->data['CompanyAddress']['id'];
        $countries = $this->CompanyAddress->Country->find('list', array(
            'fields' => array(
                'Country.iso2',
                'Country.name'
            )
        ));
        $this->set(compact('countries'));
    }
	 public function edit($id = null)
     {
        $this->pageTitle = __l('Edit Merchant Address');
        $temp_country_id = '';
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['CompanyAddress']['country_id'])) {
                $temp_country_id = $this->request->data['CompanyAddress']['country_id'];
                $this->request->data['CompanyAddress']['country_id'] = $this->CompanyAddress->Country->findCountryIdFromIso2($this->request->data['CompanyAddress']['country_id']);
            }
            $this->CompanyAddress->set($this->request->data);
            $this->CompanyAddress->State->set($this->request->data);
            $this->CompanyAddress->City->set($this->request->data);
            unset($this->CompanyAddress->validate['state_id']);
            if ($this->CompanyAddress->validates() &$this->CompanyAddress->City->validates() &$this->CompanyAddress->State->validates()) {
                if (!empty($this->request->data['State']['name'])) {
                    $this->request->data['CompanyAddress']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->CompanyAddress->State->findOrSaveAndGetId($this->request->data['State']['name']);
                } else {
                    //if state name is empty then it will update as 0 in company address table
                    $this->request->data['CompanyAddress']['state_id'] = 0;
                }
                $this->request->data['CompanyAddress']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->CompanyAddress->City->findOrSaveCityAndGetId($this->request->data['City']['name'], $this->request->data['CompanyAddress']['state_id'], $this->request->data['CompanyAddress']['country_id'], $this->request->data['CompanyAddress']['latitude'], $this->request->data['CompanyAddress']['longitude']);
                $this->request->data['CompanyAddress']['id'] = $id;
                $this->CompanyAddress->save($this->request->data);
                $this->request->data['Company']['id'] = $this->request->data['CompanyAddress']['company_id'];
                $this->CompanyAddress->Company->save($this->request->data['Company']);
                $this->Session->setFlash(__l('Merchant Address has been updated') , 'default', null, 'success');
                if ($this->RequestHandler->isAjax()) {
                    $this->setAction('index');
                } else {
                    $this->redirect(array(
                        'controller' => 'company_addresses',
                        'action' => 'index',
                    ));
                }
            } else {
                $this->request->data['CompanyAddress']['country_id'] = $temp_country_id;
                $this->Session->setFlash(__l('Merchant Address could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->CompanyAddress->read(null, $id);
            $countries = $this->CompanyAddress->Country->find('first', array(
                'conditions' => array(
                    'Country.id' => $this->request->data['CompanyAddress']['country_id']
                ) ,
                'fields' => array(
                    'Country.id',
                    'Country.name',
                    'Country.iso2',
                ) ,
                'recursive' => -1
            ));
            $this->request->data['CompanyAddress']['country_id'] = $countries['Country']['iso2'];
            if (empty($this->request->data)) {
            echo "balamurugan";
                //throw new NotFoundException(__l('Invalid request'));
            }
        }
        unset($this->CompanyAddress->Company->City->validate['City']);
        $this->pageTitle.= ' - ' . $this->request->data['CompanyAddress']['id'];
        $countries = $this->CompanyAddress->Country->find('list', array(
            'fields' => array(
                'Country.iso2',
                'Country.name'
            )
        ));
        $this->set(compact('countries'));
    }
    public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->CompanyAddress->delete($id)) {
            if ($this->RequestHandler->isAjax()) {
                echo 'deleted';
                exit;
            }
            $this->Session->setFlash(__l('Merchant Address deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'company_addresses',
                'action' => 'index',
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Merchant Addresses');
        $this->CompanyAddress->recursive = 0;
        $this->set('companyAddresses', $this->paginate());
    }
    public function admin_branches()
    {
        // company & branch deal status count update and number of user near by company update
        $this->CompanyAddress->Deal->live_deal_status_count_update();
        $this->CompanyAddress->Company->company_near_user_count_update();
        $this->CompanyAddress->company_address_near_user_count_update();
        $this->CompanyAddress->Company->company_near_user_count_update('iphone');
        $this->CompanyAddress->company_address_near_user_count_update('iphone');
        $this->pageTitle = __l('Branches & Stats');
        $companies = $this->CompanyAddress->Company->find('all', array(
            'contain' => array(
                'CompanyAddress'
            ) ,
            'recursive' => 2
        ));
        $dealStatuses = $this->CompanyAddress->Deal->DealStatus->find('list');
        $this->set('companies', $companies);
        $this->set('dealStatuses', $dealStatuses);
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Merchant Address');
        $this->setAction('add');
    }
    
    public function admin_delete($id = null, $company_id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->CompanyAddress->delete($id)) {
            if ($this->RequestHandler->isAjax()) {
                echo 'deleted';
                exit;
            }
            $this->Session->setFlash(__l('Merchant branch address has been deleted') , 'default', null, 'success');
            $this->redirect(array(
				 'controller' => 'companies',
                'action' => 'edit',
				'admin' => true,
				$company_id
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>