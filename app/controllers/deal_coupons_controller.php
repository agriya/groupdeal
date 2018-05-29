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
class DealCouponsController extends AppController
{
    public $name = 'DealCoupons';
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
    function index()
    {
        $this->pageTitle = __l('Deal Orders/Coupons');
        $this->DealCoupon->recursive = 0;
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $conditions = array(
                'Deal.id' => $this->request->params['named']['deal_id']
            );
        } else {
            $company = $this->DealCoupon->Deal->Company->find('first', array(
                'conditions' => array(
                    'Company.user_id' => $this->Auth->user('id')
                ) ,
                'recursive' => -1
            ));
            if (empty($company)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions = array(
                'Deal.id' => $this->request->params['named']['deal_id'],
                'Deal.company_id' => $company['Company']['id']
            );
        }
        $deal = $this->DealCoupon->Deal->find('first', array(
            'conditions' => $conditions,
            'fields' => array(
                'Deal.id',
                'Deal.name',
            ) ,
            'recursive' => -1
        ));
        if (empty($deal)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->paginate = array(
            'conditions' => array(
                'DealCoupon.deal_id' => $this->request->params['named']['deal_id']
            ) ,
			'contain' => array('Deal' => array('DealUser')),
            'recursive' => 3
        );
        $this->set('deal', $deal);
		$this->set('dealCoupons', $this->paginate());
    }
    function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->DealCoupon->delete($id)) {
            $this->Session->setFlash(__l('Unused coupon deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'deals',
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    function admin_index()
    {
        $this->setAction('index');
    }
    function admin_delete($id = null)
    {
        $this->setAction('delete', $id);
    }
}
?>