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
class DealCategoriesController extends AppController
{
    public $name = 'DealCategories';
    public function admin_index()
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Deal Categories');
        $this->DealCategory->recursive = 0;
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['DealCategory']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->paginate = array(
            'order' => array(
                'DealCategory.id' => 'desc'
            ) ,
        );
        if (isset($this->request->data['DealCategory']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('dealCategories', $this->paginate());
        $moreActions = $this->DealCategory->moreActions;
        $this->set(compact('moreActions'));
        $this->set('pageTitle', $this->pageTitle);
    }

	public function index()
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Deal Categories');
        $this->DealCategory->recursive = 0;
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['DealCategory']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
		$city_slug = $this->request->params['named']['city'];
                $city = $this->DealCategory->Deal->City->find('first', array(
                    'conditions' => array(
                        'City.slug' => $city_slug
                    ) ,
                    'fields' => array(
                        'City.name',
                        'City.id'
                    ) ,
                    'contain' => array(
                        'Deal' => array(
                            'fields' => array(
                                'Deal.id'
                            ) ,
                        ),
						//'DealCategory'
                    ) ,
                    'recursive' => 1
                ));
		$city_deal_ids = array();
                foreach($city['Deal'] as $deal) {
                    $city_deal_ids[] = $deal['id'];
                }
       
		if(isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'home_color')
		{
				$deal_conditions['Deal.id'] = $city_deal_ids;
				$deal_conditions['Deal.deal_status_id'] = array(0=>2,1=>5);
        
				$deal_conditions['Deal.is_now_deal'] = 0;
				$this->set('category_name',$this->request->params['named']['category_name']);
		}
		else if(isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'recent')
		{
				 $deal_conditions['Deal.id'] = $city_deal_ids;
				 $deal_conditions['Deal.deal_status_id'] = array(
								ConstDealStatus::Expired,
								ConstDealStatus::Canceled,
								ConstDealStatus::PaidToCompany,
								ConstDealStatus::Closed
							);;        
				$deal_conditions['Deal.is_now_deal'] = 0;
				$this->set('category_name',$this->request->params['named']['category_name']);
		}
		else
		{
			 	$deal_conditions['Deal.id'] = $city_deal_ids;
				$deal_conditions['Deal.deal_status_id'] = array(
                ConstDealStatus::Expired,
                ConstDealStatus::Canceled,
                ConstDealStatus::PaidToCompany
            );
        $deal_conditions['Deal.end_date <'] = _formatDate('Y-m-d H:i:s', date('Y-m-d H:i:s') , true);
		$deal_conditions['Deal.is_now_deal'] = 0;
		}
        $this->paginate = array(
			'contain'=>array(
			'Deal'=>array(
				'conditions'=>$deal_conditions,
				'fields'=>'id'
			)),
            'order' => array(
                'DealCategory.id' => 'desc'
            ) ,
        );
        if (isset($this->request->data['DealCategory']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('dealCategories', $this->paginate());
        $moreActions = $this->DealCategory->moreActions;
        $this->set(compact('moreActions'));
        $this->set('pageTitle', $this->pageTitle);
		if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'home')
		{
			    $this->render('home');		
		}
    }
	public function live_index()
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Live Deal Categories');
        $this->DealCategory->recursive = 0;
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['DealCategory']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
		
        $this->paginate = array(
            'order' => array(
                'DealCategory.id' => 'desc'
            ) ,
        );
        if (isset($this->request->data['DealCategory']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('dealCategories', $this->paginate());
        $moreActions = $this->DealCategory->moreActions;
        $this->set(compact('moreActions'));
        $this->set('pageTitle', $this->pageTitle);
		if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'home')
		{
			    $this->render('home');		
		}
    }


    public function admin_add()
    {
        $this->pageTitle = __l('Add Live Deal Category');
        $this->DealCategory->create();
        if (!empty($this->request->data)) {
            if ($this->DealCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Live Deal category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Live Deal category could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Live Deal Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->DealCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Live Deal category has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Live Deal category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->DealCategory->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['DealCategory']['name'];
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->DealCategory->delete($id)) {
            $this->Session->setFlash(__l('Live Deal category deleted') , 'default', null, 'success');
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
                    $this->Session->setFlash(__l('Checked Live Deal categories has been deleted') , 'default', null, 'success');
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