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
class SettingsController extends AppController
{
    public $components = array(
        'Cookie'
    );
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'SiteLogo.filename',
            'Setting'
        );
        parent::beforeFilter();
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Settings');
        $setting_categories = $this->Setting->SettingCategory->find('all', array(
            'conditions' => array(
                'SettingCategory.parent_id' => 0,
                "NOT" => array(
                    "SettingCategory.id" => array(
                        65
                    )
                )
            ) , // Images category will not showed
            'name' => array(
                'order ASC'
            ) ,
            'recursive' => -1
        ));
        $this->set('setting_categories', $setting_categories);
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_edit($category_id = 1)
    {
        $save_check_flag = 0;
        $ssl_enable = true;
        $this->disableCache();
        if (!empty($this->request->data)) {
            if (Configure::read('site.is_admin_settings_enabled')) {
                // Save settings
                if (!empty($this->request->data['Setting']['22'])) {
                    $this->Cookie->write('user_language', $this->request->data['Setting']['22']['name'], false);
                }
                $category_id = $this->request->data['Setting']['setting_category_id'];
                unset($this->request->data['Setting']['setting_category_id']);
                $validate['error'] = '';
                if (!empty($this->data['Setting']['162']['name'])) {
                    if (!stristr(substr($this->data['Setting']['162']['name'], -1, 1) , '/') === FALSE) {
                        $validate['error'] = __l('This is image base URL should not trailing slash');
                        $this->Setting->validationErrors[162]['name'] = __l('This is image base URL should not trailing slash');
                    }
                }
                if (!empty($this->data['Setting']['163']['name'])) {
                    if (stristr(substr($this->data['Setting']['163']['name'], -1, 1) , '/') === FALSE) {
                        $validate['error'] = __l('This is css base URL should have trailing slash');
                        $this->Setting->validationErrors[163]['name'] = __l('This is css base URL should have trailing slash');
                    }
                }
                if (!empty($this->data['Setting']['164']['name'])) {
                    if (stristr(substr($this->data['Setting']['164']['name'], -1, 1) , '/') === FALSE) {
                        $validate['error'] = __l('This is JS base URL should have trailing slash');
                        $this->Setting->validationErrors[164]['name'] = __l('This is JS base URL should have trailing slash');
                    }
                }
                if (empty($validate['error'])) {
                    foreach($this->request->data['Setting'] as $id => $value) {
                        $settings['Setting']['id'] = $id;
                        if ($id == '8') { // Writing default city name in cache.
                            Cache::delete('site_paypal_conversion_currency');
                            Cache::delete('site_paypal_conversion_currency_rate');
                            Cache::delete('site_supported_currencies');
                            Cache::delete('site_authorizenet_conversion_currency');
                            Configure::write('site.currency_id', $value['name']);
                        }
                        if ($id == '23') { // Writing default city name in cache.
                            if (($default_city = Cache::read('site.default_city', 'long')) === false) {
                                Cache::write('site.default_city', $value['name'], array(
                                    'config' => 'long'
                                ));
                            } else {
                                Cache::delete('site.default_city', 'long');
                                Cache::write('site.default_city', $value['name'], array(
                                    'config' => 'long'
                                ));
                            }
                        }
                        if ($id == '24') { // Writing city routing url in cache
                            if (($city_url = Cache::read('site.city_url', 'long')) === false) {
                                Cache::write('site.city_url', $value['name'], array(
                                    'config' => 'long'
                                ));
                                Cache::write('site.city_url', $value['name'], 'long');
                            } else {
                                Cache::delete('site.city_url', 'long');
                                Cache::write('site.city_url', $value['name'], array(
                                    'config' => 'long'
                                ));
                            }
                        }
                        if ($id == '9') { // Writing conversion currency details in cache
                            Cache::delete('site_paypal_conversion_currency');
                            Cache::delete('site_paypal_conversion_currency_rate');
                            Cache::delete('site_supported_currencies');
                            Cache::delete('site_authorizenet_conversion_currency');
                            Configure::write('site.paypal_currency_converted_id', $value['name']);
                            $this->_cacheWriteCurrency();
                        }
                        if (count($value['name']) == 1) {
                            $settings['Setting']['value'] = $value['name'];
                            $this->Setting->save($settings['Setting']);
                            $save_check_flag = 1;
                        }
                    }
                    if (!empty($save_check_flag)) {
                        $this->Session->setFlash(__l('Settings updated successfully.') , 'default', null, 'success');
                    }
                } else {
                    $this->Session->setFlash($validate['error'], 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('Sorry. You Cannot Update the Settings in Demo Mode') , 'default', null, 'error');
            }
            Cache::delete('setting_key_value_pairs');
        }
        $this->request->data['Setting']['setting_category_id'] = $category_id;
        $conditions = array();
        if ($category_id == 16) { //  module manager that
            $conditions['Setting.name'] = array(
                'affiliate.is_enabled',
                'referral.referral_enable',
                'friend.is_enabled',
                'charity.is_enabled',
                'discussions.discussions_enable'
            );
        } else {
            $conditions['Setting.setting_category_parent_id'] = $category_id;
        }
        $settings = $this->Setting->find('all', array(
            'conditions' => $conditions,
            'order' => array(
                'Setting.setting_category_id' => 'asc',
                'Setting.order' => 'asc'
            ) ,
            'recursive' => 0
        ));
        $is_module = false;
        $active_module = true;
        if (in_array($category_id, array(
            ConstModule::Affiliate,
            ConstModule::Charity,
            ConstModule::Friends,
            ConstModule::Referral
        ))) {
            $is_module = true;
            foreach($settings as $setting) {
                if (in_array($setting['Setting']['id'], array(
                    ConstModuleEnableFields::Affiliate,
                    ConstModuleEnableFields::Charity,
                    ConstModuleEnableFields::Friends,
                    ConstModuleEnableFields::Referral,
                    ConstModuleEnableFields::Discussion
                ))) {
                    $active_module = ($setting['Setting']['value']) ? true : false;
                }
            }
        }
        $this->set('active_module', $active_module);
        $this->set('is_module', $is_module);
        if ($category_id == 1) {
            $url = "https://" . $_SERVER['SERVER_NAME'];
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            if (curl_exec($ch) === false) {
                $ssl_enable = false;
            }
            // Close handle
            curl_close($ch);
        }
        $this->request->data['Setting']['setting_category_id'] = $category_id;
        $main_setting_categories = $this->Setting->SettingCategory->find('first', array(
            'conditions' => array(
                'SettingCategory.id = ' => $category_id
            ) ,
            'recursive' => 1
        ));
        $setting_categories = $this->Setting->SettingCategory->find('all', array(
            'conditions' => array(
                'SettingCategory.parent_id = ' => $category_id
            ) ,
            'recursive' => -1
        ));
        $this->set('setting_categories', $main_setting_categories);
        $this->set('setting_category_name', $main_setting_categories);
        $this->pageTitle = $main_setting_categories['SettingCategory']['name'] . __l(' Settings');
        $is_submodule = false;
        $active_submodule = true;
        foreach($setting_categories as $setting_category) {
            if (!empty($setting_category) && $setting_category['SettingCategory']['id'] == ConstSettingsSubCategory::Commission) {
                $is_submodule = true;
                foreach($settings as $setting) {
                    if (in_array($setting['Setting']['id'], array(
                        ConstModuleEnableFields::Commission
                    ))) {
                        $active_submodule = ($setting['Setting']['value']) ? true : false;
                    }
                }
            }
            $this->set('is_submodule', $is_submodule);
            $this->set('active_submodule', $active_submodule);
            if (!empty($setting_category) && $setting_category['SettingCategory']['id'] == ConstSettingsSubCategory::DateAndTime) {
                $this->loadModel('Timezone');
                $timezones = $this->Timezone->find('all', array(
                    'fields' => array(
                        'Timezone.name',
                        'Timezone.code',
                        'Timezone.gmt_offset'
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($timezones)) {
                    foreach($timezones as $timezone) {
                        $timezoneOptions[$timezone['Timezone']['code']] = $timezone['Timezone']['name'];
                    }
                }
                $this->set(compact('timezoneOptions', 'timezoneOptions'));
            }
            if (!empty($setting_category) && $setting_category['SettingCategory']['id'] == ConstSettingsSubCategory::Regional) {
                $languageOptions = array();
                $cityOptions = array();
                $this->loadModel('City');
                $languages = $this->City->Language->find('all', array(
                    'conditions' => array(
                        'Language.is_active' => 1
                    ) ,
                    'fields' => array(
                        'Language.name',
                        'Language.iso2'
                    )
                ));
                $cities = $this->City->find('all', array(
                    'conditions' => array(
                        'City.is_approved' => 1,
						'City.is_enable' => 1
                    ) ,
                    'fields' => array(
                        'City.name',
                        'City.slug',
                    )
                ));
                if (!empty($languages)) {
                    foreach($languages as $language) {
                        $languageOptions[$language['Language']['iso2']] = $language['Language']['name'];
                    }
                }
                if (!empty($cities)) {
                    foreach($cities as $city) {
                        $cityOptions[$city['City']['slug']] = $city['City']['name'];
                    }
                }
                $this->set(compact('languageOptions', 'cityOptions'));
                $attachment = $this->Setting->SiteLogo->find('first', array(
                    'conditions' => array(
                        'SiteLogo.Class = ' => 'SiteLogo'
                    ) ,
                    'fields' => array(
                        'SiteLogo.id',
                        'SiteLogo.dir',
                        'SiteLogo.filename',
                        'SiteLogo.width',
                        'SiteLogo.height',
                    ) ,
                    'recursive' => -1
                ));
                $this->set('attachment', $attachment);
            }
            if (!empty($setting_category) && $setting_category['SettingCategory']['id'] == ConstSettingsSubCategory::Barcode) {
                $barcodeSymbologies = array(
                    'qr' => 'QR Code',
                    'c39' => 'c39',
                    'c128a' => 'c128a',
                    'c128b' => 'c128b',
                    'c128c' => 'c128c',
                    'i25' => 'i25'
                );
                $this->set(compact('barcodeSymbologies'));
            }
        }
        $beyondOriginals = array();
        $aspects = array();
        foreach($settings as $setting) {
            $field_name = explode('.', $setting['Setting']['name']);
            if (isset($field_name[2])) {
                if ($field_name[2] == 'is_not_allow_resize_beyond_original_size') {
                    $beyondOriginals[$setting['Setting']['id']] = Inflector::humanize(Inflector::underscore($field_name[1]));
                    $this->request->data['Setting']['not_allow_beyond_original'][] = ($setting['Setting']['value']) ? $setting['Setting']['id'] : '';
                } else if ($field_name[2] == 'is_handle_aspect') {
                    $aspects[$setting['Setting']['id']] = Inflector::humanize(Inflector::underscore($field_name[1]));
                    $this->request->data['Setting']['allow_handle_aspect'][] = ($setting['Setting']['value']) ? $setting['Setting']['id'] : '';
                }
            }
        }
        $fb_login_url = Router::url(array(
            'controller' => 'settings',
            'action' => 'update_facebook'
        ) , true);
        $tw_login_url = Router::url(array(
            'controller' => 'settings',
            'action' => 'update_twitter'
        ) , true);
        $fs_login_url = Router::url(array(
            'controller' => 'settings',
            'action' => 'admin_update_foursquare'
        ) , true);
        $this->set('ssl_enable', $ssl_enable);
        $this->set('fb_login_url', $fb_login_url);
        $this->set('tw_login_url', $tw_login_url);
        $this->set('fs_login_url', $fs_login_url);
        $this->set(compact('settings', 'beyondOriginals', 'aspects'));
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_update_facebook()
    {
        $this->pageTitle = __l('Update Facebook');
        if (!empty($this->request->params['named']['city'])) {
            $get_current_city = $this->request->params['named']['city'];
        } else {
            $get_current_city = Configure::read('site.city');
        }
        $fb_return_url = Router::url(array(
            'controller' => $get_current_city,
            'action' => 'settings',
            'fb_update',
            'admin' => false
        ) , true);
        $this->Session->write('fb_return_url', $fb_return_url);
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        $fb_city_login_url = $this->facebook->getLoginUrl(array(
            'redirect_uri' => Router::url(array(
                'controller' => 'users',
                'action' => 'oauth_facebook',
                'city_to_update' => $this->request->params['named']['city_to_update'],
                'admin' => false
            ) , true) ,
            'scope' => 'email,offline_access,publish_stream'
        ));
        $this->redirect($fb_city_login_url);
        $this->autoRender = false;
    }
    public function admin_update_twitter()
    {
        $this->pageTitle = __l('Update Twitter');
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'OauthConsumer');
        $this->OauthConsumer = new OauthConsumerComponent($collection);
        $twitter_return_url = Router::url(array(
            'controller' => $this->request->params['named']['city'],
            'action' => 'users',
            'oauth_callback',
            'admin' => false
        ) , true);
        $requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'https://api.twitter.com/oauth/request_token', $twitter_return_url);
        $this->Session->write('requestToken', serialize($requestToken));
        $this->redirect('http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
        $this->autoRender = false;
    }
    public function admin_update_foursquare()
    {
        $foursqaure_return_url = Router::url(array(
            'controller' => 'users',
            'action' => 'fs_oauth_callback',
            'admin' => false
        ) , true);
        $client_key = Configure::read('foursquare.consumer_key');
        $client_secret = Configure::read('foursquare.consumer_secret');
        include APP . 'vendors' . DS . 'foursquare' . DS . 'FoursquareAPI.class.php';
        // Load the Foursquare API library
        $foursquare = new FoursquareAPI($client_key, $client_secret);
        $redirect_url = $foursquare->AuthenticationLink($foursqaure_return_url);
        $this->redirect($redirect_url);
        $this->autoRender = false;
    }
    public function _traverse_directory($dir, $dir_count)
    {
        $handle = opendir($dir);
        while (false !== ($readdir = readdir($handle))) {
            if ($readdir != '.' && $readdir != '..') {
                $path = $dir . '/' . $readdir;
                if (is_dir($path)) {
                    @chmod($path, 0777);
                    ++$dir_count;
                    $this->_traverse_directory($path, $dir_count);
                }
                if (is_file($path)) {
                    @chmod($path, 0777);
                    @unlink($path);
                    //so that page wouldn't hang
                    flush();
                }
            }
        }
        closedir($handle);
        @rmdir($dir);
        return true;
    }
    public function fb_update()
    {
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        if ($fb_session = $this->Session->read('fbuser')) {
            $settings = $this->Setting->find('all', array(
                'conditions' => array(
                    'Setting.name' => array(
                        'facebook.fb_access_token',
                        'facebook.fb_user_id'
                    )
                ) ,
                'fields' => array(
                    'Setting.id',
                    'Setting.name'
                ) ,
                'recursive' => -1
            ));
            foreach($settings as $setting) {
                $this->request->data['Setting']['id'] = $setting['Setting']['id'];
                if ($setting['Setting']['name'] == 'facebook.fb_user_id') {
                    $this->request->data['Setting']['value'] = $fb_session['id'];
                } elseif ($setting['Setting']['name'] == 'facebook.fb_access_token') {
                    $this->request->data['Setting']['value'] = $fb_session['access_token'];
                }
                if ($this->Setting->save($this->request->data)) {
                    $this->Session->setFlash(__l('Facebook credentials updated') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('Facebook credentials could not be updated. Please, try again.') , 'default', null, 'error');
                }
            }
        }
        $this->redirect(array(
            'action' => 'index',
            'admin' => true
        ));
    }
    public function crush()
    {
        $this->autoRender = false;
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'cron');
        $this->Cron = new CronComponent($collection);
        $this->Cron->crushPng(APP . WEBROOT_DIR, 0);
        if (!empty($_GET['f'])) {
            $this->Session->setFlash(__l('PNG images crushed successfully') , 'default', null, 'success');
            $this->redirect(Router::url('/', true) . $_GET['f']);
        }
    }
}
?>