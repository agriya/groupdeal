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
class MailChimpListsController extends AppController
{
    public $name = 'MailChimpLists';
    public function admin_index()
    {
        $this->pageTitle = __l('MailChimp Lists');
        if (!empty($this->request->data)) {
            foreach($this->request->data['MailChimpList'] as $city_id => $mail_chimp_list) {
                if (!empty($mail_chimp_list['city_id'])) {
                    $data = array();
                    if (empty($mail_chimp_list['id'])) {
                        $this->MailChimpList->create();
                    } else {
                        $data['MailChimpList']['id'] = $mail_chimp_list['id'];
                    }
                    $data['MailChimpList']['city_id'] = $mail_chimp_list['city_id'];
                    $data['MailChimpList']['list_id'] = (!empty($mail_chimp_list['list_id']) ? $mail_chimp_list['list_id'] : '');
                    $this->MailChimpList->save($data);
                }
            }
            $this->Session->setFlash(__l('MailChimp List has been updated') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $this->MailChimpList->recursive = 0;
        $city_mail_chimp_lists = $this->MailChimpList->City->find('all', array(
            'conditions' => array(
                'City.is_approved' => 1,
                'City.is_enable' => 1
            ) ,
            'contain' => array(
                'MailChimpList'
            ) ,
            'order' => array(
                'City.name' => 'asc'
            ) ,
            'recursive' => 1
        ));
        $this->set('city_mail_chimp_lists', $city_mail_chimp_lists);
        $this->set('pageTitle', $this->pageTitle);
    }
}
?>