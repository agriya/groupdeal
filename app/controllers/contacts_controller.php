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
class ContactsController extends AppController
{
    public $name = 'Contacts';
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
        'Email',
        'RequestHandler'
    );
    public function add()
    {
        $this->loadModel('EmailTemplate');
        $this->loadModel('UserProfile');
        $this->Contact->create();
        if (!empty($this->request->data)) {
            if (Configure::read('system.captcha_type') == "Solve media") {
                	if(!$this->Contact->_isValidCaptchaSolveMedia()){
					$captcha_error = 1;
				}
				}
            $this->Contact->set($this->request->data);
            if ($this->Contact->validates()) {
                if(empty($captcha_error)){
                $ip = $this->RequestHandler->getClientIP();
                $this->request->data['Contact']['ip'] = $ip;
                $this->request->data['Contact']['user_id'] = $this->Auth->user('id');
                $language_code = $this->Contact->getUserLanguageIso($this->Auth->user('id'));
                $email = $this->EmailTemplate->selectTemplate('Contact Us', $language_code);
                $emailFindReplace = array(
                    '##SITE_NAME##' => Configure::read('site.name') ,
                    '##FIRST_NAME##' => $this->request->data['Contact']['first_name'],
                    '##LAST_NAME##' => !empty($this->request->data['Contact']['last_name']) ? ' ' . $this->request->data['Contact']['last_name'] : '',
                    '##FROM_EMAIL##' => '<' . $this->request->data['Contact']['email'] . '>',
                    '##FROM_URL##' => Router::url('/', true) . 'contactus',
                    '##SITE_ADDR##' => gethostbyaddr($ip) ,
                    '##IP##' => $ip,
                    '##TELEPHONE##' => $this->request->data['Contact']['telephone'],
                    '##MESSAGE##' => $this->request->data['Contact']['message'],
                    '##SUBJECT##' => $this->request->data['Contact']['subject'],
                    '##POST_DATE##' => date('F j, Y g:i:s A (l) T (\G\M\TP)', strtotime(date('Y-m-d H:i:s'))) ,
                    '##CONTACT_URL##' => Router::url(array(
                        'controller' => 'contacts',
                        'action' => 'add',
                        'city' => $this->request->params['named']['city'],
                        'admin' => false
                    ) , true) ,
                    '##SITE_URL##' => Router::url('/', true) ,
                    '##SITE_LINK##' => Router::url('/', true) ,
                    '##SITE_LOGO##' => Router::url(array(
                        'controller' => 'img',
                        'action' => 'blue-theme',
                        'logo-email.png',
                        'admin' => false
                    ) , true) ,
                    '##CONTACT_FROM_EMAIL##' => $this->request->data['Contact']['email']
                );
                // send to contact email
                $this->Email->from = strtr($email['from'], $emailFindReplace);
                $this->Email->replyTo = strtr($email['reply_to'], $emailFindReplace);
                $this->Email->to = Configure::read('site.contact_email');
                $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                // reply email
                $language_code = $this->Contact->getUserLanguageIso($this->Auth->user('id'));
                $email = $this->EmailTemplate->selectTemplate('Contact Us Auto Reply', $language_code);
                $emailFindReplace['##FROM_EMAIL##'] = Configure::read('site.contact_email');
                $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                $this->Email->to = $this->request->data['Contact']['first_name'] . ' <' . $this->request->data['Contact']['email'] . '>';
                $this->Email->from = strtr($this->Email->from, $emailFindReplace);
                $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                $this->set('success', 1);
            }
            else
            {
                $this->Session->setFlash(__l('Contact could not be added. Please, Enter valid captcha.') , 'default', null, 'error');
            }
            }else {
                $this->Session->setFlash(__l('Contact could not be added. Please, try again.') , 'default', null, 'error');
            }
            unset($this->request->data['Contact']['captcha']);
        } else {
            $SignedInUserDetail = $this->UserProfile->find('first', array(
                'conditions' => array(
                    'UserProfile.user_id' => $this->Auth->user('id')
                ) ,
                'contain' => array(
                    'User' => array(
                        'fields' => array(
                            'User.id',
                            'User.email'
                        )
                    )
                ) ,
                'fields' => array(
                    'UserProfile.first_name',
                    'UserProfile.last_name',
                ) ,
                'recursive' => 0
            ));
            $this->request->data['Contact']['first_name'] = !empty($SignedInUserDetail['UserProfile']['first_name']) ? $SignedInUserDetail['UserProfile']['first_name'] : '';
            $this->request->data['Contact']['last_name'] = !empty($SignedInUserDetail['UserProfile']['last_name']) ? $SignedInUserDetail['UserProfile']['last_name'] : '';
            $this->request->data['Contact']['email'] = !empty($SignedInUserDetail['User']['email']) ? $SignedInUserDetail['User']['email'] : '';
        }
        $this->pageTitle = __l('Contact Us');
    }
}
?>