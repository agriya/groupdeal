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
class BusinessSuggestionsController extends AppController
{
    public $name = 'BusinessSuggestions';
	public $permanentCacheAction = array(
		'user' => array(
			'add',
			'update',
		) ,
		'public' => array(
			'index',
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
    public function add()
    {
        $this->pageTitle = __l('Suggest a Business');
        $this->BusinessSuggestion->create();
        if (!empty($this->request->data)) {
            if ($this->Auth->user('id')) {
                $this->request->data['BusinessSuggestion']['user_id'] = $this->Auth->user('id');
            }
            if ($this->BusinessSuggestion->save($this->request->data)) {
                $this->Session->setFlash(__l('Suggestion has been sent') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'deals',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Suggestion could not be sent. Please, try again.') , 'default', null, 'error');
            }
        }
        if ($this->Auth->user('id')) {
            $this->request->data['BusinessSuggestion']['email'] = $this->Auth->user('email');
        }
		$cities = $this->BusinessSuggestion->City->find('list', array(array('conditions' => array(
                'City.is_approved' => 1,
                'City.is_enable' => 1))));
		$this->set(compact('cities'));		
         
		
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Business Suggestions');
        $conditions = array();
        $this->paginate = array(
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.user_type_id',
                        'User.username',
                        'User.id',
                    ) ,
                    'UserAvatar'
                ) ,
            ) ,
            'recursive' => 1,
            'order' => 'BusinessSuggestion.id desc'
        );
        $this->set('businessSuggestions', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    public function view($id = null)
    {
        $this->pageTitle = __l('Business Suggestion');
        $conditions = array();
        $businessSuggestion = $this->BusinessSuggestion->find('first', array(
            'conditions' => array(
                'BusinessSuggestion.id' => $id,
            ) ,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.user_type_id',
                        'User.username',
                        'User.id',
                    ) ,
                    'UserAvatar'
                ) ,
            ) ,
            'recursive' => 1,
        ));
        $this->set('businessSuggestion', $businessSuggestion);
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $businessSuggestion = $this->BusinessSuggestion->find('first', array(
            'conditions' => array(
                'BusinessSuggestion.id' => $id,
            ) ,
            'recursive' => -1
        ));
        if (!empty($businessSuggestion['BusinessSuggestion']['id']) && $this->BusinessSuggestion->delete($businessSuggestion['BusinessSuggestion']['id'])) {
            $this->Session->setFlash(__l('Business suggestion deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>