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
class AffiliateWidgetSizesController extends AppController
{
    public $name = 'AffiliateWidgetSizes';
    public function beforeFilter()
    {
        if (!Configure::read('affiliate.is_enabled') && $this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Security->disabledFields = array(
            'Attachment'
        );
        parent::beforeFilter();
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Affiliate Widget Sizes');
        $this->AffiliateWidgetSize->recursive = 0;
        $this->paginate = array(
            'contain' => array(
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.dir',
                        'Attachment.filename',
                        'Attachment.height',
                        'Attachment.width'
                    )
                ) ,
            ) ,
            'order' => array(
                'AffiliateWidgetSize.id' => 'desc'
            )
        );
        $this->set('affiliateWidgetSizes', $this->paginate());
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Affiliate Widget Size');
        $this->AffiliateWidgetSize->Attachment->Behaviors->attach('ImageUpload', Configure::read('image.file'));
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
            }
            $this->AffiliateWidgetSize->set($this->request->data);
            $this->AffiliateWidgetSize->Attachment->set($this->request->data);
            $ini_upload_error = 1;
            if ($this->request->data['Attachment']['filename']['error'] == 1) {
                $ini_upload_error = 0;
            }
            if ($this->AffiliateWidgetSize->validates() &$this->AffiliateWidgetSize->Attachment->validates() && $ini_upload_error) {
                if ($this->AffiliateWidgetSize->save($this->request->data)) {
                    $this->request->data['Attachment']['filename'] = $this->request->data['Attachment']['filename'];
                    $this->request->data['Attachment']['class'] = $this->modelClass;
                    $this->request->data['Attachment']['description'] = 'Widget Logo';
                    $this->request->data['Attachment']['id'] = $attach['Attachment']['id'];
                    $this->request->data['Attachment']['foreign_id'] = $this->AffiliateWidgetSize->id;
                    $data['Attachment']['filename'] = $this->request->data['Attachment']['filename'];
                    $this->AffiliateWidgetSize->Attachment->Behaviors->attach('ImageUpload', Configure::read('image.file'));
                    $this->AffiliateWidgetSize->Attachment->set($data);
                    if ($this->AffiliateWidgetSize->Attachment->validates()) {
                        $this->AffiliateWidgetSize->Attachment->save($this->request->data['Attachment']);
                    }
                    $this->Session->setFlash(__l('Affiliate Widget Size has been added.') , 'default', null, 'success');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                } else {
                    $this->Session->setFlash(__l('Affiliate Widget Size could not be added. Please, try again.') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('Affiliate Widget Size could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Affiliate Widget Size');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                $this->AffiliateWidgetSize->Attachment->Behaviors->attach('ImageUpload', Configure::read('image.file'));
            }
            if (!empty($this->request->data['OldAttachment']['id'])) {
                $this->AffiliateWidgetSize->Attachment->delete($this->request->data['Attachment']['id']);
            }
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
            }
            if (!empty($this->request->data['Attachment']['filename']['name']) || (!Configure::read('image.file.allowEmpty') && empty($this->request->data['Attachment']['id']))) {
                $this->request->data['Attachment']['class'] = 'City';
                $this->AffiliateWidgetSize->Attachment->create();
                $this->AffiliateWidgetSize->Attachment->set($this->request->data);
            }
            $this->AffiliateWidgetSize->set($this->request->data);
            $ini_upload_error = 1;
            if ($this->request->data['Attachment']['filename']['error'] == 1) {
                $ini_upload_error = 0;
            }
            if ($this->AffiliateWidgetSize->validates() &(empty($this->request->data['Attachment']['filename']['name']) || $this->AffiliateWidgetSize->Attachment->validates()) && $ini_upload_error) {
                if ($this->AffiliateWidgetSize->save($this->request->data)) {
                    $foreign_id = $this->request->data['AffiliateWidgetSize']['id'];
                    $attach = $this->AffiliateWidgetSize->Attachment->find('first', array(
                        'conditions' => array(
                            'Attachment.foreign_id = ' => $foreign_id,
                            'Attachment.class = ' => 'AffiliateWidgetSize'
                        ) ,
                        'fields' => array(
                            'Attachment.id'
                        ) ,
                        'recursive' => -1,
                    ));
                    if (!(empty($this->request->data['Attachment']['filename']['name']))) {
                        $this->request->data['Attachment']['filename'] = $this->request->data['Attachment']['filename'];
                        $this->request->data['Attachment']['class'] = $this->modelClass;
                        $this->request->data['Attachment']['description'] = 'Widget Logo';
                        $this->request->data['Attachment']['id'] = $attach['Attachment']['id'];
                        $this->request->data['Attachment']['foreign_id'] = $this->request->data['AffiliateWidgetSize']['id'];
                        $data['Attachment']['filename'] = $this->request->data['Attachment']['filename'];
                        $this->AffiliateWidgetSize->Attachment->Behaviors->attach('ImageUpload', Configure::read('image.file'));
                        $this->AffiliateWidgetSize->Attachment->set($data);
                        if ($this->AffiliateWidgetSize->Attachment->validates()) {
                            $this->AffiliateWidgetSize->Attachment->save($this->request->data['Attachment']);
                        }
                    }
                    $this->Session->setFlash(__l('Affiliate Widget Size has been updated') , 'default', null, 'success');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                } else {
                    $this->Session->setFlash(__l('Affiliate Widget Size could not be updated. Please, try again.') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('Affiliate Widget Size could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->AffiliateWidgetSize->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['AffiliateWidgetSize']['name'];
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->AffiliateWidgetSize->delete($id)) {
            $this->Session->setFlash(__l('Affiliate Widget Size deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>