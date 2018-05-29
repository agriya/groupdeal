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
class StatesController extends AppController
{
    public $name = 'States';
    function admin_index()
    {
        $this->disableCache();
        $this->request->params['named']['q'] = !empty($this->request->data['State']['q']) ? $this->request->data['State']['q'] : '';
        $this->pageTitle = __l('States');
        $conditions = array();
        $this->State->validate = array();
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data[$this->modelClass]['filter_id'] = $this->request->params['named']['filter_id'];
        } else if (!empty($this->request->data[$this->modelClass]['filter_id'])) {
            $this->request->params['named']['filter_id'] = $this->request->data[$this->modelClass]['filter_id'];
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $this->pageTitle.= __l(' - Approved');
                $conditions[$this->modelClass . '.is_approved'] = 1;
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $this->pageTitle.= __l(' - Disapproved');
                $conditions[$this->modelClass . '.is_approved'] = 0;
            }
        }
        if (!empty($this->request->params['named']['q'])) {
            $this->request->data['State']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->State->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'City' => array(
                    'fields' => array(
                        'City.name',
                        'City.slug'
                    )
                ) ,
                'Country' => array(
                    'fields' => array(
                        'Country.name'
                    )
                ) ,
            ) ,
            'order' => array(
                'State.name' => 'asc'
            ) ,
            'limit' => 15,
        );
        if (!empty($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('states', $this->paginate());
        $this->set('pending', $this->State->find('count', array(
            'conditions' => array(
                'State.is_approved = ' => 0
            )
        )));
        $this->set('approved', $this->State->find('count', array(
            'conditions' => array(
                'State.is_approved = ' => 1
            )
        )));
        $filters = $this->State->isFilterOptions;
        $moreActions = $this->State->moreActions;
        $this->set(compact('filters', 'moreActions'));
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add State');
        $this->State->validate = array_merge($this->State->validate, $this->State->validateStateName);
        if (!empty($this->request->data)) {
            $this->State->create();
            if ($this->State->save($this->request->data)) {
                $this->Session->setFlash(__l('State has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('State could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data['State']['is_approved'] = 1;
        }
        $countries = $this->State->Country->find('list', array(
            'order' => array(
                'Country.name' => 'asc'
            ) ,
        ));
        $this->set(compact('countries'));
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit State');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->State->save($this->request->data)) {
                $this->Session->setFlash(__l('State has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('State could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->State->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['State']['name'];
        $countries = $this->State->Country->find('list');
        $this->set(compact('countries'));
        $this->set('pageTitle', $this->pageTitle);
    }
    // To change approve/disapprove status by admin
    public function admin_update_status($id = null, $status = null)
    {
        if (is_null($id) || is_null($status)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->request->data['State']['id'] = $id;
        if ($status == 'disapprove') {
            $this->request->data['State']['is_approved'] = 0;
        }
        if ($status == 'approve') {
            $this->request->data['State']['is_approved'] = 1;
        }
        $this->State->save($this->request->data);
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    public function admin_update()
    {
        $this->autoRender = false;
        if (!empty($this->request->data['State'])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $stateIds = array();
            foreach($this->request->data['State'] as $state_id => $is_checked) {
                if ($is_checked['id']) {
                    $stateIds[] = $state_id;
                }
            }
            if ($actionid && !empty($stateIds)) {
                if ($actionid == ConstMoreAction::Inactive) {
                    $this->State->updateAll(array(
                        'State.is_approved' => 0
                    ) , array(
                        'State.id' => $stateIds
                    ));
                    $this->Session->setFlash(__l('Checked states has been inactivated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Active) {
                    $this->State->updateAll(array(
                        'State.is_approved' => 1
                    ) , array(
                        'State.id' => $stateIds
                    ));
                    $this->Session->setFlash(__l('Checked states has been activated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Delete) {
                    $city = $this->State->City->find('first', array(
                        'conditions' => array(
                            'City.slug = ' => Configure::read('site.city')
                        ) ,
                        'fields' => array(
                            'City.state_id',
                        ) ,
                        'recursive' => -1,
                    ));
                    if (in_array($city['City']['state_id'], $stateIds)) {
                        $this->Session->setFlash(__l('States could not be deleted. Please, check seleted state belongs to default city') , 'default', null, 'error');
                    } else {
                        $this->State->deleteAll(array(
                            'State.id' => $stateIds
                        ));
                        $this->Session->setFlash(__l('Checked states has been deleted') , 'default', null, 'success');
                    }
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
        if ($this->State->delete($id)) {
            $this->Session->setFlash(__l('State deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>
