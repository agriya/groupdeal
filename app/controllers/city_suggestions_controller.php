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
class CitySuggestionsController extends AppController
{
    public $name = 'CitySuggestions';
    public function add()
    {
        $this->pageTitle = __l('Suggest a City');
        if (!empty($this->request->data)) {
            if ($this->Auth->user('id')) {
                $this->request->data['CitySuggestion']['user_id'] = $this->Auth->user('id');
            }
            $this->CitySuggestion->create();
            if ($this->CitySuggestion->save($this->request->data)) {
                $this->Session->setFlash(__l('City Suggestion has been sent') , 'default', null, 'success');
                $this->redirect(Router::url('/', true));
            } else {
                $this->Session->setFlash(__l('City Suggestion could not be sent. Please, try again.') , 'default', null, 'error');
            }
        }
        if ($this->Auth->user('id')) {
            $this->request->data['CitySuggestion']['email'] = $this->Auth->user('email');
        }
        $this->request->data['CitySuggestion']['email'] = $this->Auth->user('email');
		
    }
    public function admin_index()
    {
        $this->pageTitle = __l('City Suggestions');
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'recent_suggestion') {
            $conditions = array();
            if (!empty($this->request->params['named']['name'])) {
                $conditions['CitySuggestion.name'] = $this->request->params['named']['name'];
            }
            $this->paginate = array(
                'conditions' => array(
                    $conditions,
                ) ,
                'contain' => array(
                    'User' => array(
                        'fields' => array(
                            'User.user_type_id',
                            'User.username',
                            'User.id',
                        )
                    ) ,
                ) ,
                'recursive' => 0,
                'order' => 'CitySuggestion.id desc'
            );
        } else {
            $this->paginate = array(
                'group' => array(
                    'CitySuggestion.name'
                ) ,
                'fields' => array(
                    'CitySuggestion.name',
                    'Count(CitySuggestion.name) as count',
                ) ,
                'order' => 'count desc',
                'recursive' => -1,
            );
        }
        $this->set('citySuggestions', $this->paginate());
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'recent_suggestion') {
            $this->render('city_suggest_index');
        }
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->CitySuggestion->delete($id)) {
            $this->Session->setFlash(__l('City Suggestion deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>