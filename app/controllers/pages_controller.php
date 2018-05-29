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
class PagesController extends AppController
{
    public $helpers = array(
        'Cache'
    );
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
            'Page.id',
            'Page.Update',
            'Page.Add',
            'Page.content',
            'Page.description_meta_tag',
            'Page.parent_id',
            'Page.slug',
            'Page.status_option_id',
            'Page.title',
            'Page.Preview',
            'PageLogo.site_logo',
            'PageLogo.background_image'
        );
        parent::beforeFilter();
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Page');
        $this->Page->create();
        if (!empty($this->request->data)) {
            $this->Page->set($this->request->data);
            if ($this->Page->validates()) {
                $this->Page->save($this->request->data);
                $this->Session->setFlash(__l('Page has been created') , 'default', null, 'success');
                $page_id = $this->Page->getLastInsertId();
                if (!empty($this->request->data['Page']['Preview'])) {
                    $page_slug = $this->Page->find('first', array(
                        'conditions' => array(
                            'Page.id' => $page_id
                        ) ,
                        'fields' => array(
                            'Page.slug'
                        ) ,
                        'recursive' => 1
                    ));
                    $this->redirect(array(
                        'controller' => 'pages',
                        'action' => 'view',
                        'type' => 'preview',
                        $page_slug['Page']['slug']
                    ));
                } else $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Page could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Page');
        $this->loadModel('City');
        $this->loadModel('Setting');
        if (!empty($this->request->data)) {
            $this->Page->set($this->request->data);
            if ($this->Page->validates()) {
                $upload_check = 1;
                if ($this->request->data['Page']['slug'] == 'pre-launch') {
                    $upload_check = $this->uploadImages($this->request->data);
                }
                if (!empty($upload_check)) {
                    $this->Page->save($this->request->data);
                    $this->Session->setFlash(__l('Page has been Updated') , 'default', null, 'success');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                } else {
                    $this->request->data = $this->Page->read(null, $this->request->data['Page']['id']);
                }
            } else {
                $data = $this->Page->read(null, $id);
                if (!empty($data['PageLogo'])) {
                    $this->request->data['PageLogo'] = $data['PageLogo'];
                }
                $this->Session->setFlash(__l('Page could not be Updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Page->read(null, $id);
            $background = $this->Setting->find('all', array(
                'conditions' => array(
                    'Setting.name' => array(
                        'prelaunch.stretch_type',
                    )
                ) ,
                'recursive' => -1
            ));
            foreach($background as $value) {
                if ($value['Setting']['name'] == 'prelaunch.stretch_type') {
                    $this->request->data['Prelaunch']['stretch_type'] = $value['Setting']['value'];
                }
            }
        }
        $stretchOptions = $this->City->StretchOptions;
        $this->set(compact('stretchOptions'));
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Pages');
        $this->Page->recursive = -1;
        $this->paginate = array(
            'order' => array(
                'id' => 'DESC'
            )
        );
        $this->set('pages', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_delete($id = null, $cancelled = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Page->delete($id)) {
            $this->Session->setFlash(__l('Page Deleted Successfully') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index',
                $cancelled
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_view($slug = null)
    {
        $this->setAction('view', $slug);
    }
    public function view($slug = null)
    {
        $this->cacheAction = Configure::read('action.cache_duration');
        $this->Page->recursive = -1;
        if (!empty($slug)) {
            $page = $this->Page->findBySlug($slug);
        } else {
            $page = $this->Page->find('first', array(
                'conditions' => array(
                    'Page.is_default' => 1
                )
            ));
        }
        $about_us_url = array(
            'controller' => 'users',
            'action' => 'login',
            'city' => $this->request->params['named']['city']
        );
        $pageFindReplace = array(
            '##FROM_EMAIL##' => Configure::read('EmailTemplate.from_email') ,
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_URL##' => Router::url('/', true) ,
            '##ABOUT_US_URL##' => Router::url(array(
                'controller' => 'pages',
                'action' => 'view',
                'about',
                'city' => $this->request->params['named']['city'],
                'admin' => false
            ) , true) ,
            '##CONTACT_US_URL##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add',
                'city' => $this->request->params['named']['city'],
                'admin' => false
            ) , true) ,
            '##FAQ_URL##' => Router::url(array(
                'controller' => 'pages',
                'action' => 'view',
                'faq',
                'city' => $this->request->params['named']['city'],
                'admin' => false
            ) , true) ,
            '##SITE_CONTACT_PHONE##' => Configure::read('site.contact_phone') ,
            '##SITE_CONTACT_EMAIL##' => "<a href='mailto:" . Configure::read('site.contact_email') . "'>" . Configure::read('site.contact_email') . "</a>",
            '##CONTACT_URL##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add',
                'city' => $this->request->params['named']['city'],
                'admin' => false
            ) , true) ,
        );
        if ($page) {
            $page['Page']['title'] = strtr($page['Page']['title'], $pageFindReplace);
            $page['Page']['content'] = strtr($page['Page']['content'], $pageFindReplace);
            $this->pageTitle = ucwords(strtolower($page[$this->modelClass]['title']));
            $this->set('page', $page);
            $this->set('currentPageId', $page[$this->modelClass]['id']);
            $this->set('isPage', true);
            $this->_chooseTemplate($page);
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    private function _chooseTemplate($page)
    {
        $render = 'view';
        if (!empty($page[$this->modelClass]['template'])) {
            $possibleThemeFile = APP . 'views' . DS . 'pages' . DS . 'themes' . DS . $page[$this->modelClass]['template'];
            if (file_exists($possibleThemeFile)) {
                $render = $possibleThemeFile;
            }
        }
        return $this->render($render);
    }
    public function display()
    {
        $this->cacheAction = Configure::read('action.cache_duration');
        $path = func_get_args();
        $count = count($path);
        if (!$count) {
            $this->redirect(Router::url('/', true));
        }
        $page = $subpage = $title = null;
        if (!empty($path[0])) {
            $page = $path[0];
        }
        if ($path[0] == 'tools' && (!$this->Auth->user('id') || $this->Auth->user('user_type_id') != ConstUserTypes::Admin)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        if (!empty($path[$count-1])) {
            $title = Inflector::humanize($path[$count-1]);
        }
        $this->set(compact('page', 'subpage', 'title'));
        $this->render(join('/', $path));
    }
    public function admin_display($page)
    {
        $this->setAction('display', $page);
    }
    function uploadImages($data)
    {
        $this->loadModel('PageLogo');
        $this->loadModel('Attachment');
        $this->loadModel('Setting');
        $is_success = 1;
        $background = $this->Setting->find('all', array(
            'conditions' => array(
                'Setting.name' => array(
                    'prelaunch.stretch_type',
                )
            ) ,
            'recursive' => -1
        ));
        foreach($data['PageLogo'] as $user_id => $is_checked) {
            if ($user_id != 'site_logo' && $user_id != 'background_image') {
                if ($is_checked['id']) {
                    $this->Attachment->delete($user_id);
                }
                unset($data['PageLogo'][$user_id]);
            }
        }
        $user = $this->Attachment->find('all', array(
            'conditions' => array(
                'Attachment.foreign_id' => $this->request->data['Page']['id'],
                'Attachment.class' => 'PageLogo'
            ) ,
            'recursive' => -1
        ));
        if (!empty($data['PageLogo']['background_image'])) {
            $uploads = array(
                'background_image' => $data['PageLogo']['background_image'],
            );
            $this->PageLogo->Behaviors->attach('ImageUpload', Configure::read('pagelogo.file'));
            foreach($uploads as $key => $upload) {
                if (!empty($upload['name'])) {
                    $attachment_id = $this->Attachment->find('first', array(
                        'conditions' => array(
                            'Attachment.foreign_id' => $this->request->data['Page']['id'],
                            'Attachment.class' => 'PageLogo',
                            'Attachment.description' => $key,
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($upload['name'])) {
                        $upload['type'] = get_mime($upload['tmp_name']);
                    }
                    $ini_upload_error = 1;
                    if ($upload['error'] == 1) {
                        $ini_upload_error = 0;
                    }
                    $tmp['filename'] = $upload;
                    unset($upload);
                    if (!empty($attachment_id)) {
                        $tmp['id'] = $attachment_id['Attachment']['id'];
                    }
                    $upload['PageLogo'] = $tmp;
                    if (!empty($upload['PageLogo']['filename']['name']) || (!Configure::read('pagelogo.file.allowEmpty') && empty($upload['PageLogo']['id']))) {
                        $this->PageLogo->set($upload);
                    }
                    if ($this->PageLogo->validates()) {
                        if (!empty($upload['PageLogo']['filename']['name'])) {
                            $this->Attachment->create();
                            $upload['PageLogo']['class'] = 'PageLogo';
                            $upload['PageLogo']['foreign_id'] = $data['Page']['id'];
                            $upload['PageLogo']['description'] = $key;
                            $this->Attachment->save($upload['PageLogo']);
                        }
                        $is_success = 1;
                        unset($tmp);
                    } else {
                        $this->PageLogo->validationErrors[$key] = __l('The submitted file extension is not permitted, only jpg,jpeg,gif,png permitted.');
                        $this->Session->setFlash(__l('Image not uploaded. Please try again ') , 'default', null, 'error');
                        return $is_success = 0;
                    }
                }
            }
            // End of foreach //

        }
        if (!empty($is_success)) {
            foreach($background as $value) {
                if (!empty($data['Prelaunch']['stretch_type']) && $value['Setting']['name'] == 'prelaunch.stretch_type') {
                    $_data['Setting']['id'] = $value['Setting']['id'];
                    $_data['Setting']['value'] = $data['Prelaunch']['stretch_type'];
                    $this->Setting->save($_data);
                    Configure::write('prelaunch.stretch_type', $data['Prelaunch']['stretch_type']);
                }
            }
        }
        return $is_success;
    }
}
