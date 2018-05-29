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
class UserPermissionPreferencesController extends AppController
{
    public $name = 'UserPermissionPreferences';
    public function edit($user_id = null)
    {
        $this->loadModel('PrivacyType');
        $this->loadModel('UserPreferenceCategory');
        if (empty($user_id)) {
            $user_id = $this->Auth->user('id');
        }
        $this->pageTitle = __l('Edit Permission Preferences');
        if (!empty($this->request->data)) {
            if (empty($this->request->data['User']['id'])) {
                $this->request->data['User']['id'] = $this->Auth->user('id');
            }
            $user = $this->UserPermissionPreference->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data['User']['id']
                ) ,
                'contain' => array(
                    'UserPermissionPreference' => array(
                        'fields' => array(
                            'UserPermissionPreference.id'
                        )
                    )
                ) ,
                'recursive' => 0
            ));
            $user_id = $user['User']['id'];
            if (!empty($user['UserPermissionPreference'])) {
                $this->request->data['UserPermissionPreference']['id'] = $user['UserPermissionPreference']['id'];
                $this->request->data['UserPermissionPreference']['user_id'] = $this->request->data['User']['id'];
            }
            if ($this->UserPermissionPreference->save($this->request->data)) {
                $this->Session->setFlash(__l('Permissions are updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Permissions could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->UserPermissionPreference->find('first', array(
                'conditions' => array(
                    'UserPermissionPreference.user_id' => $user_id,
                ) ,
                'contain' => array(
                    'User' => array(
                        'fields' => array(
                            'User.user_type_id',
                            'User.username',
                            'User.id',
                        )
                    )
                ) ,
                'recursive' => 0
            ));
            if (empty($this->request->data)) {
                $this->request->data['UserPermissionPreference']['user_id'] = $user_id;
                $this->UserPermissionPreference->create();
                $this->UserPermissionPreference->save($this->request->data);
                $this->redirect(array(
                    'controller' => 'user_permission_preferences',
                    'action' => 'edit',
                    $user_id,
                    'admin' => false
                ));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['User']['username'];
        $userPreferenceCategories = $this->UserPreferenceCategory->find('all');
        $privacyTypes = $this->PrivacyType->find('list');
        if (!Configure::read('friend.is_enabled')) {
            unset($privacyTypes[ConstPrivacySetting::Friends]);
        }
        if ((!empty($this->request->data['User']['user_type_id']) ? $this->request->data['User']['user_type_id'] : '') == ConstUserTypes::Company && !Configure::read('user.is_company_actas_normal_user')) {
            unset($privacyTypes[ConstPrivacySetting::Friends]);
        }
        if ((!empty($this->request->data['User']['user_type_id']) ? $this->request->data['User']['user_type_id'] : '') == ConstUserTypes::Company) {
            unset($this->request->data['UserPermissionPreference']['Profile-is_show_gender']);
            unset($this->request->data['UserPermissionPreference']['Profile-is_show_name']);
        }
        if ((!empty($this->request->data['User']['user_type_id']) ? $this->request->data['User']['user_type_id'] : '') == ConstUserTypes::Company && !Configure::read('user.is_company_actas_normal_user')) {
            unset($this->request->data['UserPermissionPreference']['Profile-is_allow_comment_add']);
            unset($this->request->data['UserPermissionPreference']['Profile-is_receive_email_for_new_comment']);
        }
        $this->set(compact('userPreferenceCategories', 'privacyTypes'));
        $this->request->data['User']['id'] = $user_id;
    }
    public function admin_edit($user_id)
    {
        $this->setAction('edit', $user_id);
    }
}
?>