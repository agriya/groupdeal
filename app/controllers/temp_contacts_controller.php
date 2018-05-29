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
class TempContactsController extends AppController
{
    public $name = 'TempContacts';
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
            'TempContact.send_contact',
            'TempContact.temp_contact',
            'UserFriend.invite_all'
        );
        parent::beforeFilter();
    }
    public function index($member = null, $contacts_source = null, $deal_invite_check = null)
    {
        $this->pageTitle = __l('User Friends');
        $this->loadModel('TempContact');
        $this->paginate = array(
            'conditions' => array(
                'TempContact.user_id' => $this->Auth->user('id') ,
                'TempContact.is_member' => $member,
                'TempContact.contact_email != ' => $this->Auth->user('email')
            ) ,
            'contain' => array(
                'ContactUser' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.dir',
                            'UserAvatar.filename'
                        )
                    ) ,
                    'fields' => array(
                        'ContactUser.user_type_id',
                        'ContactUser.username',
                        'ContactUser.id',
                        'ContactUser.email',
                    )
                )
            ) ,
            'limit' => 10,
            'recursive' => 1
        );
        $this->set('invite_friend_options', $this->TempContact->invite_friend_options);
        $this->set('add_friend_options', $this->TempContact->add_friend_options);
        $this->set('exist_friend_options', $this->TempContact->exist_friend_options);
        $this->set('tempContacts', $this->paginate());
        $this->set('member', $member);
        $this->set('contacts_source', $contacts_source);
        $this->set('deal_slug', $deal_invite_check);
    }
}
?>