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
class UserCommentsController extends AppController
{
    public $name = 'UserComments';
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
    public function beforeFilter()
    {
        if (!$this->UserComment->User->isAllowed($this->Auth->user('user_type_id'))) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function index($username = null)
    {
        $this->pageTitle = __l('User Comments');
        $user = $this->UserComment->User->find('first', array(
            'conditions' => array(
                'User.username' => $username
            ) ,
            'fields' => array(
                'User.user_type_id',
                'User.username',
                'User.id',
                'User.fb_user_id',
            ) ,
            'recursive' => -1
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->paginate = array(
            'conditions' => array(
                'UserComment.user_id' => $user['User']['id']
            ) ,
            'contain' => array(
                'PostedUser' => array(
                    'UserAvatar',
                    'fields' => array(
                        'PostedUser.user_type_id',
                        'PostedUser.username',
                        'PostedUser.id',
                        'PostedUser.fb_user_id',
                    )
                ) ,
                'User' => array(
                    'fields' => array(
                        'User.user_type_id',
                        'User.username',
                        'User.id',
                        'User.fb_user_id',
                    )
                )
            ) ,
            'order' => array(
                'UserComment.id DESC'
            )
        );
        $this->UserComment->recursive = 0;
        $this->set('userComments', $this->paginate());
        $this->set('user', $user);
        $this->set('username', $username);
    }
    public function view($id = null, $view_name = 'view')
    {
        $this->pageTitle = __l('User Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $userComment = $this->UserComment->find('first', array(
            'conditions' => array(
                'UserComment.id = ' => $id
            ) ,
            'contain' => array(
                'PostedUser' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.dir',
                            'UserAvatar.filename'
                        )
                    ) ,
                    'fields' => array(
                        'PostedUser.user_type_id',
                        'PostedUser.username',
                        'PostedUser.id',
                        'PostedUser.fb_user_id',
                    )
                )
            ) ,
            'recursive' => 2,
        ));
        if (empty($userComment)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $userComment['UserComment']['id'];
        $this->set('userComment', $userComment);
        $this->render($view_name);
    }
    public function add()
    {
        $this->pageTitle = __l('Add User Comment');
        if (!empty($this->request->data)) {
            $user = $this->UserComment->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data['UserComment']['user_id']
                ) ,
                'fields' => array(
                    'User.username',
                    'User.user_type_id',
                    'User.email',
                    'User.id'
                ) ,
                'contain' => array(
                    'UserProfile',
                    'Company' => array(
                        'fields' => array(
                            'Company.slug'
                        )
                    )
                ) ,
                'recursive' => 1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->request->data['UserComment']['posted_user_id'] = $this->Auth->user('id');
            $this->UserComment->create();
            if ($this->UserComment->save($this->request->data)) {
                // To send email when post comments
                if (Configure::read('user.is_send_email_on_profile_comments') && $this->UserComment->_checkForPrivacy('Profile-is_receive_email_for_new_comment', $user['User']['id'], $this->Auth->user('id'))) {
                    $this->_sendAlertOnCommentPost($user, $this->request->data['UserComment']['comment'], $user['User']['username'], $user['User']['id']);
                }
                $this->Session->setFlash(__l('User Comment has been added') , 'default', null, 'success');
                if (!$this->RequestHandler->isAjax()) {
                    if ($user['User']['user_type_id'] == ConstUserTypes::Company) {
                        $this->redirect(array(
                            'controller' => 'companies',
                            'action' => 'view',
                            $user['Company']['slug']
                        ));
                    } else {
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'view',
                            $user['User']['username']
                        ));
                    }
                } else {
                    // Ajax: return added blog comment
                    $this->setAction('view', $this->UserComment->getLastInsertId() , 'view_ajax');
                }
            } else {
                $this->Session->setFlash(__l('User Comment could not be added. Please, try again.') , 'default', null, 'error');
            }
            $this->set('user', $user);
        }
    }
    function _sendAlertOnCommentPost($email, $comment, $username, $user_id)
    {
        $this->loadModel('EmailTemplate');
        $language_code = $this->UserComment->getUserLanguageIso($user_id);
        $email_message = $this->EmailTemplate->selectTemplate('New Comment Profile', $language_code);
        $email_replace = array(
            '##FROM_EMAIL##' => $this->UserComment->changeFromEmail(($email_message['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email_message['from']) ,
            '##SITE_LINK##' => Router::url('/', true) ,
            '##PROFILEUSERNAME##' => $username,
            '##USERNAME##' => $this->Auth->user('username') ,
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##PROFILE_LINK##' => Router::url(array(
                'controller' => 'users',
                'action' => 'view',
                $username,
                '#tabs-1',
                'admin' => false
            ) , true) ,
            '##COMMENT##' => $comment,
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
        $this->Email->to = $this->UserComment->formatToAddress($email);
        $this->Email->subject = strtr($email_message['subject'], $email_replace);
        $this->Email->content = strtr($email_message['email_content'], $email_replace);
        $this->Email->sendAs = ($email_message['is_html']) ? 'html' : 'text';
        $this->Email->send($this->Email->content);
    }
    public function edit($id = null)
    {
        $this->pageTitle = __l('Edit User Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->UserComment->save($this->request->data)) {
                $this->Session->setFlash(__l('User Comment has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('User Comment could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->UserComment->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['UserComment']['id'];
        $users = $this->UserComment->User->find('list');
        $this->set(compact('users'));
    }
    public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        // Check is user allow to delete
        $userComment = $this->UserComment->find('first', array(
            'conditions' => array(
                'UserComment.id' => $id,
                'OR' => array(
                    'UserComment.posted_user_id' => $this->Auth->user('id') ,
                    'UserComment.user_id' => $this->Auth->user('id')
                )
            ) ,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username',
                        'User.user_type_id'
                    ) ,
                    'Company' => array(
                        'fields' => array(
                            'Company.slug'
                        )
                    )
                ) ,
            )
        ));
        if (empty($userComment)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->UserComment->delete($id)) {
            $this->Session->setFlash(__l('User Comment deleted') , 'default', null, 'success');
            if ($userComment['User']['user_type_id'] == ConstUserTypes::Company) {
                $this->redirect(array(
                    'controller' => 'companies',
                    'action' => 'view',
                    $userComment['User']['Company']['slug']
                ));
            } else {
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'view',
                    $userComment['User']['username']
                ));
            }
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index()
    {
        $this->pageTitle = __l('User Comments');
        $this->UserComment->recursive = 0;
        $this->paginate = array(
            'order' => array(
                'UserComment.id' => 'desc'
            )
        );
        $this->set('userComments', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
        $moreActions = $this->UserComment->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit User Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->UserComment->save($this->request->data)) {
                $this->Session->setFlash(sprintf(__l('User Comment has been updated') , $this->request->data['UserComment']['id']) , 'default', null, 'success');
            } else {
                $this->Session->setFlash(sprintf(__l('User Comment could not be updated. Please, try again.') , $this->request->data['UserComment']['id']) , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->UserComment->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['UserComment']['id'];
        $users = $postedUsers = $this->UserComment->User->find('list');
        $this->set(compact('users', 'postedUsers'));
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->UserComment->delete($id)) {
            $this->Session->setFlash(__l('User Comment deleted') , 'default', null, 'success');
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
                    $this->Session->setFlash(__l('Checked user comments has been deleted') , 'default', null, 'success');
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