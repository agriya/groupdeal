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
class UserFriendsController extends AppController
{
    public $name = 'UserFriends';
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
    public $components = array(
        'Email'
    );
    public $uses = array(
        'UserFriend',
        'User',
    );
    public function beforeFilter()
    {
        if (!Configure::read('friend.is_enabled') && !$this->UserFriend->User->isAllowed($this->Auth->user('user_type_id'))) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Security->validatePost = false;
        parent::beforeFilter();
    }
    public function myfriends($user_id = null, $status = null)
    {
        $this->disableCache();
        if (!empty($this->request->params['named']['user_id']) && !empty($this->request->params['named']['status'])) {
            $user_id = $this->request->params['named']['user_id'];
            $status = $this->request->params['named']['status'];
        }
        if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'deal')) {
            $user_id = $this->Auth->user('id');
            $status = ConstUserFriendStatus::Approved;
            if (!empty($this->request->params['named']['type']) && !empty($this->request->params['named']['deal'])) {
                $this->request->data['UserFriend']['deal_slug'] = $this->request->params['named']['deal'];
            }
        }
        $total_friends = $this->UserFriend->find('count', array(
            'conditions' => array(
                'UserFriend.user_id' => $user_id,
                'UserFriend.friend_status_id' => $status
            )
        ));
        $this->paginate = array(
            'conditions' => array(
                'UserFriend.user_id' => $user_id,
                'UserFriend.friend_status_id' => $status
            ) ,
            'contain' => array(
                'FriendUser' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir'
                        )
                    ) ,
                    'fields' => array(
                        'FriendUser.user_type_id',
                        'FriendUser.username',
                        'FriendUser.id',
                        'FriendUser.fb_user_id',
                    )
                )
            ) ,
            'limit' => 16
        );
        $this->UserFriend->recursive = 0;
        $this->set('userFriends', $this->paginate());
        $this->set('total_friends', $total_friends);
        if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'deal')) {
            $this->render('myfriends_deal');
        }
    }
    public function index($status, $type = 'received')
    {
        if (is_null($status)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        // Set page titles
        if ($status == ConstUserFriendStatus::Approved) {
            $this->pageTitle = __l('Accepted');
        }
        if ($status == ConstUserFriendStatus::Pending) {
            $this->pageTitle = __l('Pending');
        }
        if ($status == ConstUserFriendStatus::Rejected) {
            $this->pageTitle = __l('Rejected');
        }
        if ($type == 'received') {
            $conditions = array(
                'UserFriend.friend_user_id' => $this->Auth->user('id') ,
                'UserFriend.friend_status_id' => $status
            );
        } else {
            $conditions = array(
                'UserFriend.user_id' => $this->Auth->user('id') ,
                'UserFriend.friend_status_id' => $status
            );
        }
        if (Configure::read('friend.is_two_way')) {
            $conditions[] = array(
                'UserFriend.is_requested' => 1
            );
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir'
                        )
                    ) ,
                    'fields' => array(
                        'User.username',
                        'User.user_type_id',
                        'User.id',
                        'User.fb_user_id',
                    )
                ) ,
                'FriendUser' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir'
                        )
                    ) ,
                    'fields' => array(
                        'FriendUser.user_type_id',
                        'FriendUser.username',
                        'FriendUser.id',
                        'FriendUser.fb_user_id',
                    )
                )
            ) ,
            'order' => array(
                'UserFriend.id DESC'
            ) ,
            'limit' => 12
        );
        $this->UserFriend->recursive = 0;
        $this->set('userFriends', $this->paginate());
        $this->set('status', $status);
        $this->set('type', $type);
    }
    public function lst()
    {
        $this->pageTitle = __l('Friends');
    }
    public function add($username = null)
    {
        $this->pageTitle = __l('Add User Friend');
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Company && !Configure::read('user.is_company_actas_normal_user')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        //Check email and username parameters
        $my_self = false;
        if (!empty($this->request->params['named']['email'])) {
            if ($this->Auth->user('email') != $this->request->params['named']['email']) {
                $conditions['User.email'] = $this->request->params['named']['email'];
            } else {
                $my_self = true;
            }
        } elseif (!empty($this->request->params['named']['username'])) {
            if ($this->Auth->user('username') != $this->request->params['named']['username']) {
                $conditions['User.username'] = $this->request->params['named']['username'];
            } else {
                $my_self = true;
            }
        } elseif (!empty($username)) {
            if ($this->Auth->user('username') != $username) {
                $conditions['User.username'] = $username;
            } else {
                $my_self = true;
            }
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!$my_self) {
            $user = $this->UserFriend->User->find('first', array(
                'conditions' => $conditions,
                'fields' => array(
                    'User.id',
                    'User.username',
                    'User.email',
                ) ,
                'contain' => array(
                    'UserProfile'
                ) ,
                'recursive' => 1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            // Check user is in blocked lists
            $blockedUser = $this->UserFriend->User->BlockedUser->find('count', array(
                'conditions' => array(
                    'BlockedUser.user_id' => $user['User']['id'],
                    'BlockedUser.blocked_user_id' => $this->Auth->user('id')
                )
            ));
            if ($blockedUser) {
                $this->Session->setFlash(__l(sprintf('%s is blocked you. You can not become friend of %s', $user['User']['username'], $user['User']['username'])) , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'view',
                    $user['User']['username']
                ));
            }
            // Check user is a company user
            $checkCompanyUser = $this->UserFriend->User->find('first', array(
                'conditions' => array(
                    'User.id' => $user['User']['id'],
                ) ,
                'recursive' => -1
            ));
            if ($checkCompanyUser['User']['user_type_id'] == ConstUserTypes::Company && !Configure::read('user.is_company_actas_normal_user')) {
                $this->Session->setFlash(__l('You cannot add this user as a friend') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'view',
                    $user['User']['username']
                ));
            }
            // Check is already added
            $userFriend = $this->UserFriend->find('first', array(
                'conditions' => array(
                    'UserFriend.user_id' => $this->Auth->user('id') ,
                    'UserFriend.friend_user_id' => $user['User']['id']
                ) ,
                'fields' => array(
                    'UserFriend.id'
                ) ,
                'recursive' => -1
            ));
            if (empty($userFriend)) {
                $friend_status = Configure::read('friend.is_accept') ? (ConstUserFriendStatus::Approved) : (ConstUserFriendStatus::Pending);
                $this->request->data['UserFriend']['user_id'] = $this->Auth->user('id');
                $this->request->data['UserFriend']['friend_user_id'] = $user['User']['id'];
                $this->request->data['UserFriend']['friend_status_id'] = $friend_status;
                $this->request->data['UserFriend']['is_requested'] = 1;
                $this->UserFriend->create();
                if ($this->UserFriend->save($this->request->data)) {
                    // Check user is in blocked lists
                    $blockedUser = $this->UserFriend->User->BlockedUser->find('first', array(
                        'conditions' => array(
                            'BlockedUser.user_id' => $this->Auth->user('id') ,
                            'BlockedUser.blocked_user_id' => $user['User']['id']
                        ) ,
                        'fields' => array(
                            'BlockedUser.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($blockedUser)) {
                        $this->UserFriend->User->BlockedUser->delete($blockedUser['BlockedUser']['id']);
                    }
                    // To send email to user about friend request
                    if (Configure::read('friend.is_send_email_on_friend_request')) {
                        $template = (Configure::read('friend.is_two_way')) ? 'Two Way New Friend' : 'One Way New Friend';
                        $this->_sendFriendRequestMail($template, $user, $user['User']['username'], $user['User']['id']);
                    }
                    if (Configure::read('friend.is_accept') and Configure::read('friend.is_two_way')) {
                        $this->request->data['UserFriend']['friend_user_id'] = $this->Auth->user('id');
                        $this->request->data['UserFriend']['user_id'] = $user['User']['id'];
                        $this->request->data['UserFriend']['friend_status_id'] = ConstUserFriendStatus::Approved;
                        $this->request->data['UserFriend']['is_requested'] = 0;
                        $this->UserFriend->create();
                        $this->UserFriend->save($this->request->data);
                    }
                    if (!$this->RequestHandler->isAjax()) {
                        $this->Session->setFlash(__l('Friend has been added.') , 'default', null, 'success');
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'my_stuff#My_Friends'
                        ));
                    } else {
                        $this->set('username', $user['User']['username']);
                    }
                }
            } else {
                if (!$this->RequestHandler->isAjax()) {
                    $this->Session->setFlash(__l('Already added in your friend\'s list.') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'my_stuff#My_Friends'
                    ));
                } else {
                    $this->set('username', $username);
                }
            }
        } else {
            $this->Session->setFlash(__l('You can\'t yourself to friend\'s list.') , 'default', null, 'error');
            if (!$this->RequestHandler->isAjax()) {
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'view',
                    $this->Auth->user('username')
                ));
            }
        }
    }
    function _sendFriendRequestMail($template, $email, $to_username, $user_id)
    {
        $this->loadModel('EmailTemplate');
        $language_code = $this->UserFriend->getUserLanguageIso($user_id);
        $email_message = $this->EmailTemplate->selectTemplate($template, $language_code);
        $email_find = array(
            '##FROM_EMAIL##' => $this->UserFriend->changeFromEmail(($email_message['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email_message['from']) ,
            '##USERNAME##' => $this->Auth->user('username') ,
            '##TO_USER##' => $to_username,
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_LINK##' => Router::url('/', true) ,
            '##PROFILE_LINK##' => Router::url(array(
                'controller' => 'users',
                'action' => 'view',
                $this->Auth->user('username') ,
                'admin' => false
            ) , true) ,
            '##FRIEND_LINK##' => Router::url(array(
                'controller' => 'user_friends',
                'action' => 'lst',
                'admin' => false
            ) , true) ,
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
        // Send e-mail to users
        $this->Email->from = ($email_message['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email_message['from'];
        $this->Email->replyTo = ($email_message['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email_message['reply_to'];
        $this->Email->to = $this->UserFriend->formatToAddress($email);
        $this->Email->subject = strtr($email_message['subject'], $email_find);
        $this->Email->sendAs = ($email_message['is_html']) ? 'html' : 'text';
        $this->Email->send(strtr($email_message['email_content'], $email_find));
    }
    public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->UserFriend->delete($id)) {
            $this->Session->setFlash(__l('User Friend deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function reject($username = null, $type = 'received')
    {
        if (is_null($type)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        // check is user exists
        $user = $this->UserFriend->User->find('first', array(
            'conditions' => array(
                'User.username' => $username
            ) ,
            'fields' => array(
                'User.id'
            ) ,
            'recursive' => -1
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($type == 'received') {
            // Check for is friend added
            $userFriend = $this->UserFriend->find('first', array(
                'conditions' => array(
                    'UserFriend.friend_user_id' => $this->Auth->user('id') ,
                    'UserFriend.user_id' => $user['User']['id']
                ) ,
                'fields' => array(
                    'UserFriend.id'
                ) ,
                'recursive' => -1
            ));
            $this->UserFriend->updateAll(array(
                'friend_status_id' => ConstUserFriendStatus::Rejected
            ) , array(
                'friend_user_id' => $this->Auth->user('id') ,
                'user_id' => $user['User']['id']
            ));
            if (Configure::read('friend.is_two_way')) {
                $userFriend = $this->UserFriend->find('first', array(
                    'conditions' => array(
                        'UserFriend.user_id' => $this->Auth->user('id') ,
                        'UserFriend.friend_user_id' => $user['User']['id']
                    ) ,
                    'fields' => array(
                        'UserFriend.id'
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($userFriend)) {
                    $this->request->data['UserFriend']['id'] = $userFriend['UserFriend']['id'];
                }
                // Add another record for reject for two way friendships
                $this->request->data['UserFriend']['user_id'] = $this->Auth->user('id');
                $this->request->data['UserFriend']['friend_user_id'] = $user['User']['id'];
                $this->request->data['UserFriend']['friend_status_id'] = ConstUserFriendStatus::Rejected;
                $this->UserFriend->save($this->request->data);
            }
        } else if ($type == 'sent') {
            // Check for is friend added
            $userFriend = $this->UserFriend->find('first', array(
                'conditions' => array(
                    'UserFriend.user_id' => $this->Auth->user('id') ,
                    'UserFriend.friend_user_id' => $user['User']['id']
                ) ,
                'fields' => array(
                    'UserFriend.id'
                ) ,
                'recursive' => -1
            ));
            $this->UserFriend->updateAll(array(
                'friend_status_id' => ConstUserFriendStatus::Rejected
            ) , array(
                'user_id' => $this->Auth->user('id') ,
                'friend_user_id' => $user['User']['id']
            ));
            if (Configure::read('friend.is_two_way')) {
                $userFriend = $this->UserFriend->find('first', array(
                    'conditions' => array(
                        'UserFriend.friend_user_id' => $this->Auth->user('id') ,
                        'UserFriend.user_id' => $user['User']['id']
                    ) ,
                    'fields' => array(
                        'UserFriend.id'
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($userFriend)) {
                    $this->request->data['UserFriend']['id'] = $userFriend['UserFriend']['id'];
                }
                // Add another record for reject for two way friendships
                $this->request->data['UserFriend']['user_id'] = $user['User']['id'];
                $this->request->data['UserFriend']['friend_user_id'] = $this->Auth->user('id');
                $this->request->data['UserFriend']['friend_status_id'] = ConstUserFriendStatus::Rejected;
                $this->UserFriend->save($this->request->data);
            }
        }
        if (!$this->RequestHandler->isAjax()) {
            $this->Session->setFlash('Friend has been rejected.', 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'my_stuff#My_Friends'
            ));
        } else {
            $this->Session->setFlash('Friend has been rejected.', 'default', null, 'success');
            $this->setAction('index', ConstUserFriendStatus::Rejected, $type);
        }
    }
    public function accept($username = null)
    {
        $this->pageTitle = __l('Add User Friend');
        // check is user exists
        $user = $this->UserFriend->User->find('first', array(
            'conditions' => array(
                'User.username' => $username
            ) ,
            'fields' => array(
                'User.id'
            ) ,
            'recursive' => -1
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        // Check is already added
        $userFriend = $this->UserFriend->find('first', array(
            'conditions' => array(
                'UserFriend.friend_user_id' => $this->Auth->user('id') ,
                'user_id' => $user['User']['id'],
                'UserFriend.friend_status_id' => ConstUserFriendStatus::Pending
            ) ,
            'fields' => array(
                'UserFriend.id',
            ) ,
            'recursive' => -1
        ));
        if (empty($userFriend)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($userFriend)) {
            // To update the pending friend status
            $this->UserFriend->updateAll(array(
                'friend_status_id' => ConstUserFriendStatus::Approved
            ) , array(
                'user_id' => $user['User']['id'],
                'friend_user_id' => $this->Auth->user('id')
            ));
            // To add an another record for two way friendships
            if (Configure::read('friend.is_two_way')) {
                $this->request->data['UserFriend']['friend_user_id'] = $user['User']['id'];
                $this->request->data['UserFriend']['user_id'] = $this->Auth->user('id');
                $this->request->data['UserFriend']['friend_status_id'] = ConstUserFriendStatus::Approved;
                $this->UserFriend->save($this->request->data);
            }
            if (!$this->RequestHandler->isAjax()) {
                $this->Session->setFlash('Friend has been accepted.', 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'my_stuff#My_Friends',
                    'admin' => false
                ));
            } else {
                $this->Session->setFlash('Friend has been accepted.', 'default', null, 'success');
                $this->setAction('index', ConstUserFriendStatus::Approved, 'received');
            }
        }
    }
    public function remove($username = null, $type = null)
    {
        if (is_null($type)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $conditions = array();
        $user = $this->UserFriend->User->find('first', array(
            'conditions' => array(
                'User.username' => $username
            ) ,
            'fields' => array(
                'User.id'
            ) ,
            'recursive' => -1
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($type == 'received') {
            $userFriend = $this->UserFriend->find('first', array(
                'conditions' => array(
                    'UserFriend.user_id' => $user['User']['id'],
                    'UserFriend.friend_user_id' => $this->Auth->user('id')
                ) ,
                'fields' => array(
                    'UserFriend.id'
                ) ,
                'recursive' => -1
            ));
            $conditions['UserFriend.friend_user_id'] = $user['User']['id'];
            $conditions['UserFriend.user_id'] = $this->Auth->user('id');
        } else if ($type == 'sent') {
            $userFriend = $this->UserFriend->find('first', array(
                'conditions' => array(
                    'UserFriend.friend_user_id' => $user['User']['id'],
                    'UserFriend.user_id' => $this->Auth->user('id')
                ) ,
                'fields' => array(
                    'UserFriend.id'
                ) ,
                'recursive' => -1
            ));
            $conditions['UserFriend.friend_user_id'] = $this->Auth->user('id');
            $conditions['UserFriend.user_id'] = $user['User']['id'];
        }
        if (!empty($userFriend)) {
            if ($this->UserFriend->delete($userFriend['UserFriend']['id'])) {
                if (Configure::read('friend.is_two_way')) {
                    $otherfriend = $this->UserFriend->find('first', array(
                        'conditions' => $conditions,
                        'fields' => array(
                            'UserFriend.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($otherfriend)) {
                        $this->UserFriend->delete($otherfriend['UserFriend']['id']);
                    }
                }
                if (!$this->RequestHandler->isAjax()) {
                    $this->Session->setFlash('Friend has been removed.', 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'my_stuff#My_Friends'
                    ));
                } else {
                    $this->set('username', $username);
                }
            }
        }
    }
    public function admin_index()
    {
        $this->_redirectGET2Named(array(
            'user_id',
            'friend_user_id',
            'filter_id',
            'q'
        ));
        $conditions = array();
        $this->pageTitle = __l('User Friends');
        if (isset($this->request->params['named']['user_id'])) {
            $this->request->data['UserFriend']['user_id'] = $this->request->params['named']['user_id'];
            $conditions['UserFriend.user_id'] = $this->request->params['named']['user_id'];
        }
        if (isset($this->request->params['named']['friend_user_id'])) {
            $this->request->data['UserFriend']['friend_user_id'] = $this->request->params['named']['friend_user_id'];
            $conditions['UserFriend.friend_user_id'] = $this->request->params['named']['friend_user_id'];
        }
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['UserFriend']['filter_id'] = $this->request->params['named']['filter_id'];
            $conditions['UserFriend.friend_status_id'] = $this->request->params['named']['filter_id'];
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['UserFriend']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->UserFriend->recursive = 1;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'UserFriend.id' => 'desc'
            ) ,
        );
        if (isset($this->request->data['UserFriend']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('userFriends', $this->paginate());
        $users = $this->UserFriend->User->find('list', array(
            'conditions' => array(
                'User.user_type_id !=' => ConstUserTypes::Admin
            )
        ));
        $friendUsers = $this->UserFriend->User->find('list', array(
            'conditions' => array(
                'User.user_type_id !=' => ConstUserTypes::Admin
            )
        ));
        $this->UserFriend->validate = array();
        $this->UserFriend->User->validate = array();
        $this->UserFriend->FriendUser->validate = array();
        $filters = $this->UserFriend->isFilterOptions;
        $moreActions = $this->UserFriend->moreActions;
        $this->set(compact('users', 'friendUsers', 'filters', 'moreActions'));
    }
    public function admin_view($id = null)
    {
        $this->pageTitle = __l('User Friend');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $userFriend = $this->UserFriend->find('first', array(
            'conditions' => array(
                'UserFriend.id = ' => $id
            ) ,
            'fields' => array(
                'UserFriend.id',
                'UserFriend.created',
                'UserFriend.modified',
                'UserFriend.user_id',
                'UserFriend.friend_user_id',
                'UserFriend.friend_status_id',
                'User.id',
                'User.created',
                'User.modified',
                'User.username',
                'User.email',
                'User.password',
                'User.display_name',
                'User.first_name',
                'User.last_name',
                'User.dob',
                'User.gender_id',
                'User.slug',
                'User.helper_rating_count',
                'User.total_helper_rating',
                'User.photo_album_count',
                'User.photo_count',
                'User.cookie_hash',
                'User.cookie_time_modified',
                'User.is_active',
                'FriendStatus.id',
                'FriendStatus.created',
                'FriendStatus.modified',
                'FriendStatus.name',
            ) ,
            'recursive' => 0,
        ));
        if (empty($userFriend)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $userFriend['UserFriend']['id'];
        $this->set('userFriend', $userFriend);
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add User Friend');
        if (!empty($this->request->data)) {
            $this->UserFriend->create();
            if ($this->UserFriend->save($this->request->data)) {
                $this->Session->setFlash(sprintf(__l('"%s" User Friend has been added') , $this->request->data['UserFriend']['id']) , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(sprintf(__l('"%s" User Friend could not be added. Please, try again.') , $this->request->data['UserFriend']['id']) , 'default', null, 'error');
            }
        }
        $users = $this->UserFriend->User->find('list');
        $friendStatuses = $this->UserFriend->FriendStatus->find('list');
        $this->set(compact('users', 'friendStatuses'));
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit User Friend');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->UserFriend->save($this->request->data)) {
                $this->Session->setFlash(sprintf(__l('"%s" User Friend has been updated') , $this->request->data['UserFriend']['id']) , 'default', null, 'success');
            } else {
                $this->Session->setFlash(sprintf(__l('"%s" User Friend could not be updated. Please, try again.') , $this->request->data['UserFriend']['id']) , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->UserFriend->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['UserFriend']['id'];
        $users = $this->UserFriend->User->find('list');
        $friendStatuses = $this->UserFriend->FriendStatus->find('list');
        $this->set(compact('users', 'friendStatuses'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $userFriend = $this->UserFriend->find('first', array(
            'conditions' => array(
                'UserFriend.id' => $id,
            ) ,
            'fields' => array(
                'UserFriend.friend_user_id',
                'UserFriend.user_id'
            ) ,
            'recursive' => -1
        ));
        if (empty($userFriend)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->UserFriend->delete($id)) {
            if (Configure::read('friend.is_two_way')) {
                $conditions['UserFriend.friend_user_id'] = $userFriend['UserFriend']['user_id'];
                $conditions['UserFriend.user_id'] = $userFriend['UserFriend']['friend_user_id'];
                $otherfriend = $this->UserFriend->find('first', array(
                    'conditions' => $conditions,
                    'fields' => array(
                        'UserFriend.id'
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($otherfriend)) {
                    $this->UserFriend->delete($otherfriend['UserFriend']['id']);
                }
            }
            $this->Session->setFlash(__l('User Friend deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_update()
    {
        if (!empty($this->request->data['UserFriend'])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $userFriendIds = array();
            foreach($this->request->data['UserFriend'] as $user_id => $is_checked) {
                if ($is_checked['id']) {
                    $userFriendIds[] = $user_id;
                }
            }
            if ($actionid && !empty($userFriendIds) && !empty($actionid)) {
                $this->UserFriend->updateAll(array(
                    'UserFriend.friend_status_id' => $actionid
                ) , array(
                    'UserFriend.id' => $userFriendIds
                ));
                if (Configure::read('friend.is_two_way')) {
                    $userFriends = $this->UserFriend->find('all', array(
                        'conditions' => array(
                            'UserFriend.id' => $userFriendIds
                        ) ,
                        'fields' => array(
                            'UserFriend.user_id',
                            'UserFriend.friend_user_id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($userFriends)) {
                        foreach($userFriends as $userfriend) {
                            $this->UserFriend->updateAll(array(
                                'UserFriend.friend_status_id' => $actionid
                            ) , array(
                                'UserFriend.user_id' => $userfriend['UserFriend']['friend_user_id'],
                                'UserFriend.friend_user_id' => $userfriend['UserFriend']['user_id']
                            ));
                        }
                    }
                }
                $this->UserFriend->FriendStatus->id = $actionid;
                $status = $this->UserFriend->FriendStatus->field('name');
                $this->Session->setFlash(__l("Checked users has been " . $status) , 'default', null, 'success');
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
    public function import($domain = '')
    {
        $this->pageTitle = __l('Friends Import');
        $this->loadModel('TempContact');
        $enabled = false;
        //Yahoo response
        if (isset($this->request->data['UserFriend']['domain']) and $this->request->data['UserFriend']['domain'] == 'yahoo' or (isset($_GET['oauth_token']) and isset($_GET['oauth_verifier']))) {
            $emails = $this->UserFriend->getYahooContacts();
            $enabled = true;
            $this->set('contacts_source', 'yahoo');
        }
        //Google response
        else if (isset($_GET['token'])) {
            $emails = $this->UserFriend->getGmailContacts($_GET['token']);
            $this->set('contacts_source', 'gmail');
        }
        //Msn response
        else if (isset($_POST['ConsentToken'])) {
            $emails = $this->UserFriend->get_msn_contacts($_POST);
            $this->set('contacts_source', 'msn');
        }
        //csv export
        else if (!empty($this->request->data['Attachment'])) {
            if (!empty($this->request->data['Attachment']['filename']['tmp_name'])) {
                $file_info = pathinfo($this->request->data['Attachment']['filename']['name']);
                if ($file_info['extension'] == 'csv') {
                    if (!empty($this->request->data['Attachment']['filename']['tmp_name'])) {
                        $emails = array();
                        if ($fp = @fopen($this->request->data['Attachment']['filename']['tmp_name'], 'r')) {
                            while ($buffer = fgetcsv($fp)) {
                                if (!empty($buffer[0]) and $this->UserFriend->validEmail($buffer[0])) {
                                    $emails[$buffer[0]] = $buffer[1] ? $buffer[1] : $buffer[1];
                                }
                            }
                        }
                        fclose($fp);
                    }
                    $this->set('contacts_source', 'csv file');
                } else {
                    $this->Session->setFlash(__l('Plesae select a valid CSV file') , 'default', null, 'error');
                }
            }
            $this->set('contacts_source', 'csv file');
        }
        $exist_friend_arr = array();
        $add_friend_arr = array();
        $invite_friend_arr = array();
        if ((isset($_GET['appid']) and isset($_GET['token'])) or (isset($_GET['token'])) or (isset($_POST['ConsentToken'])) or !empty($this->request->data['Attachment']) or $enabled) {
            if (isset($emails) and (!empty($emails))) {
                $temp_contact = $this->TempContact->find('all', array(
                    'conditions' => array(
                        'TempContact.user_id = ' => $this->Auth->user('id')
                    ) ,
                    'recursive' => -1
                ));
                foreach($temp_contact as $temp) {
                    $this->TempContact->delete($temp['TempContact']['id']);
                }
                foreach($emails as $email => $name) {
                    $user = $this->User->find('first', array(
                        'conditions' => array(
                            'User.email = ' => $email
                        ) ,
                        'fields' => array(
                            'User.id'
                        ) ,
                        'recursive' => -1
                    ));
                    $friend = $this->UserFriend->find('first', array(
                        'conditions' => array(
                            'UserFriend.friend_user_id = ' => $user['User']['id'],
                            'UserFriend.user_id = ' => $this->Auth->user('id')
                        ) ,
                        'fields' => array(
                            'UserFriend.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($user) && !empty($friend)) {
                        $this->TempContact->create();
                        $this->request->data['TempContact']['user_id'] = $this->Auth->user('id');
                        $this->request->data['TempContact']['contact_id'] = $user['User']['id'];
                        $this->request->data['TempContact']['contact_name'] = $name;
                        $this->request->data['TempContact']['contact_email'] = $email;
                        $this->request->data['TempContact']['is_member'] = '1';
                        $this->TempContact->save($this->request->data);
                        unset($this->request->data['TempContact']);
                        $exist_friend_ids[] = $user['User']['id'];
                    } else if (!empty($user)) {
                        $this->TempContact->create();
                        $this->request->data['TempContact']['user_id'] = $this->Auth->user('id');
                        $this->request->data['TempContact']['contact_id'] = $user['User']['id'];
                        $this->request->data['TempContact']['contact_name'] = $name;
                        $this->request->data['TempContact']['contact_email'] = $email;
                        $this->request->data['TempContact']['is_member'] = '2';
                        $this->TempContact->save($this->request->data);
                        unset($this->request->data['TempContact']);
                        $add_friend_ids[] = $user['User']['id'];
                    } else {
                        $this->TempContact->create();
                        $this->request->data['TempContact']['user_id'] = $this->Auth->user('id');
                        $this->request->data['TempContact']['contact_name'] = $name;
                        $this->request->data['TempContact']['contact_email'] = $email;
                        $this->request->data['TempContact']['is_member'] = '3';
                        $this->TempContact->save($this->request->data);
                        unset($this->request->data['TempContact']);
                        $invite_friend_arr[$name] = $email;
                    }
                }
                if (!empty($exist_friend_ids)) {
                    $exist_friend_ids = implode(',', $exist_friend_ids);
                    $this->set('exist_friend_arr', $exist_friend_arr);
                }
                if (!empty($add_friend_ids)) {
                    $add_friend_ids = implode(',', $add_friend_ids);
                    $this->set('add_friend_arr', $add_friend_arr);
                }
                if (!empty($invite_friend_arr)) {
                    $this->set('invite_friend_arr', $invite_friend_arr);
                }
            } else {
                $this->Session->setFlash(__l('Friends Import Failed. Plesase Try Again') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'my_stuff#Import_Friends'
                ));
            }
        } else if (!empty($this->request->data)) {
            if (isset($this->request->data['UserFriend']['domain']) and $this->request->data['UserFriend']['domain'] == 'gmail') {
                $this->redirect($this->UserFriend->getGmailUrl());
            } else if (isset($this->request->data['UserFriend']['domain']) and $this->request->data['UserFriend']['domain'] == 'msn') {
                $return_url = $this->UserFriend->getLiveUrl();
                if ($return_url) $this->redirect($return_url);
                else {
                    $this->Session->setFlash(__l('MSN Contact Import Not working. Please Contact Admin.') , 'default', null, 'error');
                    $this->redirect(array(
                        'controller' => 'user_friends',
                        'action' => 'import'
                    ));
                }
            }
        }
        if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'deal') && !empty($this->request->params['named']['deal'])) {
            $this->Session->write('deal_slug', $this->request->params['named']['deal']);
        }
        if (empty($this->request->params['named']['type']) && $this->Session->check('deal_slug')) {
            $this->Session->delete('deal_slug');
        }
        $deal_slug_check = $this->Session->read('deal_slug');
        if (!empty($deal_slug_check)) {
            //	echo $deal_slug_check;
            $this->request->data['UserFriend']['deal_slug'] = $deal_slug_check;
            //$this->Session->delete('deal_slug');

        }
        $this->set('current_user', $this->Auth->User('id'));
    }
    public function contacts()
    {
        $conditions = array();
        if (!empty($this->request->data)) {
            $conditions[] = array(
                'FriendUser.username Like ' => '%' . $this->request->data['UserFriend']['search'] . '%'
            );
        }
        $conditions[] = array(
            'UserFriend.user_id = ' => $this->Auth->user('id') ,
            'UserFriend.friend_status_id = ' => ConstFriendRequestStatus::Approved
        );
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'FriendUser' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.dir',
                            'UserAvatar.filename',
                            'UserAvatar.id',
                        )
                    ) ,
                    'fields' => array(
                        'FriendUser.username',
                        'FriendUser.fb_user_id',
                        'FriendUser.id',
                    )
                )
            )
        );
        $this->set('contacts', $this->paginate());
    }
    public function importfriends()
    {
        $this->loadModel('TempContact');
        if (!empty($this->request->data)) {
            $flag = 0;
            foreach($this->request->data['UserFriend']['FriendList'] as $temp_id => $option) {
                if ($option == 1) {
                    $flag = 1;
                    $temp_contact = $this->TempContact->find('first', array(
                        'conditions' => array(
                            'TempContact.id' => $temp_id
                        ) ,
                        'recursive' => -1
                    ));
                    if ($temp_contact['TempContact']['is_member'] == 1) {
                        $friend = $this->UserFriend->find('first', array(
                            'conditions' => array(
                                'UserFriend.user_id' => $this->Auth->user('id') ,
                                'UserFriend.friend_user_id' => $temp_contact['TempContact']['contact_id']
                            ) ,
                            'recursive' => -1
                        ));
                        $this->UserFriend->delete($friend['UserFriend']['id']);
                        if (Configure::read('friend.is_two_way')) {
                            $other_friend = $this->UserFriend->find('first', array(
                                'conditions' => array(
                                    'UserFriend.user_id' => $temp_contact['TempContact']['contact_id'],
                                    'UserFriend.friend_user_id' => $this->Auth->user('id')
                                ) ,
                                'recursive' => -1
                            ));
                            $this->UserFriend->delete($other_friend['UserFriend']['id']);
                        }
                    } else if ($temp_contact['TempContact']['is_member'] == 2) {
                        $this->UserFriend->create();
                        $friend_status = Configure::read('friend.is_accept') ? (ConstUserFriendStatus::Approved) : (ConstUserFriendStatus::Pending);
                        $this->request->data['UserFriend']['user_id'] = $this->Auth->user('id');
                        $this->request->data['UserFriend']['friend_user_id'] = $temp_contact['TempContact']['contact_id'];
                        $this->request->data['UserFriend']['friend_status_id'] = $friend_status;
                        $this->request->data['UserFriend']['is_requested'] = 1;
                        $this->UserFriend->create();
                        $this->UserFriend->save($this->request->data);
                        if (Configure::read('friend.is_accept') and Configure::read('friend.is_two_way')) {
                            $this->request->data['UserFriend']['friend_user_id'] = $this->Auth->user('id');
                            $this->request->data['UserFriend']['user_id'] = $temp_contact['TempContact']['contact_id'];
                            $this->request->data['UserFriend']['friend_status_id'] = ConstUserFriendStatus::Approved;
                            $this->request->data['UserFriend']['is_requested'] = 0;
                            $this->UserFriend->create();
                            $this->UserFriend->save($this->request->data);
                        }
                    } else if ($temp_contact['TempContact']['is_member'] == 3) {
                        if (!empty($this->request->data['UserFriend']['deal_slug'])) {
                            $user_invited[] = array(
                                'User' => array(
                                    'username' => !empty($temp_contact['TempContact']['contact_name']) ? $temp_contact['TempContact']['contact_name'] : $temp_contact['TempContact']['contact_email'],
                                    'email' => $temp_contact['TempContact']['contact_email'],
                                )
                            );
                        } else {
                            $this->_sendInviteMail($temp_contact['TempContact']['contact_email']);
                        }
                    }
                    $this->TempContact->delete($temp_contact['TempContact']['id']);
                }
            }
            if (!empty($this->request->data['UserFriend']['deal_slug']) && !empty($user_invited)) {
                $this->UserFriend->inviteDealUsers($user_invited, $this->request->data['UserFriend']['deal_slug']);
            }
            if ($flag == 1) {
                $this->Session->setFlash(__l('Your friend has been invited.') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('No Friends selected') , 'default', null, 'error');
            }
            $this->redirect(array(
                'controller' => 'temp_contacts',
                'action' => 'index',
                $this->request->data['UserFriend']['member'],
                $this->request->data['UserFriend']['contacts_source']
            ));
        }
    }
    public function _sendInviteMail($contact_email)
    {
        $this->pageTitle = __l('Invite Friend');
        $this->loadModel('EmailTemplate');
        $language_code = $this->UserFriend->getUserLanguageIso($this->Auth->user('id'));
        $email_message = $this->EmailTemplate->selectTemplate('Invite User', $language_code);
        $email_find = array(
            '##FROM_EMAIL##' => $this->UserFriend->changeFromEmail(($email_message['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email_message['from']) ,
            '##USERNAME##',
            '##SITE_NAME##',
            '##SITE_LINK##',
            '##CONTACT_URL##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add',
                'city' => $this->request->params['named']['city'],
                'admin' => false
            ) , true) ,
        );
        $email_replace = array(
            '##FROM_EMAIL##' => ($email_message['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email_message['from'],
            '##USERNAME##' => $this->Auth->user('username') ,
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_LINK##' => Router::url('/', true) ,
            '##SUPPORT_EMAIL##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add',
                'city' => $this->request->params['named']['city'],
                'admin' => false
            ) , true) ,
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
        $this->Email->from = ($email_message['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email_message['from'];
        $this->Email->replyTo = ($email_message['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email_message['reply_to'];
        $this->Email->to = $contact_email;
        $this->Email->subject = strtr($email_message['subject'], $email_replace);
        $this->Email->sendAs = ($email_message['is_html']) ? 'html' : 'text';
        $this->Email->send(strtr($email_message['email_content'], $email_replace));
    }
    public function invite_friend()
    {
        if (!empty($this->request->data)) {
            $this->UserFriend->set($this->request->data);
            if ($this->UserFriend->validates()) {
                $friend_email = explode(',', $this->request->data['UserFriend']['friends_email']);
                foreach($friend_email as $to_email) {
                    $this->_sendInviteMail($to_email);
                }
                $this->Session->setFlash(__l('Your friend has been invited.') , 'default', null, 'success');
                $this->request->data = array();
            } else {
                $this->Session->setFlash(__l('Problem in inviting.') , 'default', null, 'error');
            }
        }
    }
    public function deal_invite()
    {
        $this->pageTitle = __l('Invite your friends for the deal');
        if (!Configure::read('Deal.invite_after_deal_add')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['UserFriend']['deal_slug'])) {
                $this->request->params['named']['deal'] = $this->request->data['UserFriend']['deal_slug'];
            }
            if (!empty($this->request->data['UserFriend']['type'])) {
                $this->request->params['named']['type'] = $this->request->data['UserFriend']['type'];
            }
            $ids = array();
            $user_invited = array();
            foreach($this->request->data[$this->modelClass] as $id => $is_checked) {
                if ($is_checked['id'] && $id != 'deal_slug') {
                    $ids[] = $id;
                    $user_invited[] = $this->User->find('first', array(
                        'conditions' => array(
                            'User.id' => $id
                        ) ,
                        'fields' => array(
                            'User.id',
                            'User.email',
                            'User.username',
                        ) ,
                        'recursive' => -1
                    ));
                }
            }
            if (!empty($user_invited)) {
                $this->UserFriend->inviteDealUsers($user_invited, $this->request->data['UserFriend']['deal_slug']);
                $this->Session->setFlash(__l('Your friend has been invited.') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'deals',
                    'action' => 'index',
                    'admin' => false
                ));
            } else {
                $this->Session->setFlash(__l('Please select atleast one friend before inviting') , 'default', null, 'error');
            }
        }
        if (!empty($this->request->params['named']['deal'])) {
            $deal = $this->UserFriend->User->DealUser->Deal->find('first', array(
                'conditions' => array(
                    'Deal.slug' => $this->request->params['named']['deal']
                ) ,
                'contain' => array(
                    'Company',
                    'City' => array(
                        'fields' => array(
                            'City.id',
                            'City.name',
                            'City.slug',
                        )
                    )
                ) ,
                'recursive' => 1
            ));
            $this->set('deal', $deal);
            $this->set('deal_slug', $this->request->params['named']['deal']);
            $this->set('type', $this->request->params['named']['type']);
            $this->set('city_slug', $this->request->params['named']['city']);
        }
    }
}
?>