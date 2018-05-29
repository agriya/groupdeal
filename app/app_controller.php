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
/**
 * Short description for file.
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7805 $
 * @modifiedby    $LastChangedBy: AD7six $
 * @lastmodified  $Date: 2008-10-30 23:00:26 +0530 (Thu, 30 Oct 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Short description for class.
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppController extends Controller
{
    public $components = array(
        'RequestHandler',
        'Session',
        'Security',
        'Auth',
        'XAjax',
        'Cookie',
    );
    public $helpers = array(
        'Html',
        'Session',
        'Javascript',
        'Form',
        'Auth',
        'Time',
        'RestXml',
        'RestJson',
    );
    var $cookieTerm = '+4 weeks';
    //    var $view = 'Theme';
    //    var $theme = 'default';
    function beforeRender()
    {
        $this->set('meta_for_layout', Configure::read('meta'));
        $this->set('js_vars_for_layout', (isset($this->js_vars)) ? $this->js_vars : '');
        parent::beforeRender();
    }
    function __construct($request = null)
    {
        //	echo '<pre>Memory Usage (construct): ' .  number_format(memory_get_usage(), 0, '.', ',') . " bytes\n</pre>";
        parent::__construct($request);
         
        if (Configure::read('debug')) {
            $plugins = App::objects('plugin');
            if (in_array('DebugKit', $plugins)) {
                $this->components[] = 'DebugKit.Toolbar';
            }
        }
        // <-- For iPhone App code
        if (empty($_GET['key'])) {
            if (!empty($_GET['mobile']) && $_GET['mobile'] == 'false') {
                setcookie('mobile', 0);
            } else if (!empty($_GET['mobile']) && $_GET['mobile'] == 'true') {
                setcookie('mobile', 1);
            }
            if (!isset($_COOKIE['mobile']) || ($_COOKIE['mobile'] != 0 && $_COOKIE['mobile'] != 1)) {
                setcookie('mobile', 1);
            }
            if ((isset($_COOKIE['mobile']) && $_COOKIE['mobile'] == 1) && (!isset($_GET['mobile']) || (isset($_GET['mobile']) && $_GET['mobile'] != 'false'))) {
                include_once (APP . DS . 'vendors' . DS . 'mobile_device_detect.php');
				$server_name = str_replace('www.', '', $_SERVER['SERVER_NAME']);
				mobile_device_detect('http://touch.' . $server_name . '/', 'http://touch.' . $server_name . '/', 'http://touch.' . $server_name . '/', true, true, true, true, 'http://m.' . $server_name . '/', false);                
            }
        }
        // For iPhone App code -->
        //Setting cache related code
        App::import('Model', 'Setting');
        $setting_model_obj = new Setting();
        $settings = $setting_model_obj->getKeyValuePairs();
        Configure::write($settings);
		Cache::write('site.currency_id', Configure::read('site.currency_id'));
        // languages are set in globals
        $current_city_slug = Configure::read('site.city');
        if (!empty($_GET['url'])) {
            $city_slug = explode('/', $_GET['url']);
            $current_city_slug = (!empty($city_slug[0])) ? $city_slug[0] : Configure::read('site.city');
        }
        $lang_code = Configure::read('site.language');
        if (!empty($_COOKIE['CakeCookie']['user_language'])) {
            $lang_code = $_COOKIE['CakeCookie']['user_language'];
        } else if (!empty($current_city_slug)) {
            $cookie_city_slug = !empty($_COOKIE['CakeCookie']['city_slug']) ? $_COOKIE['CakeCookie']['city_slug'] : '';
            if (empty($cookie_city_slug) || ($current_city_slug != $cookie_city_slug)) {
                // This cache file will delete, in city model after save
                $city = Cache::read('site_cities_languages_' . $current_city_slug);
                if (empty($city)) {
                    $this->loadModel('City');
                    $city = $this->City->find('first', array(
                        'conditions' => array(
                            'City.slug' => $current_city_slug,
                            'City.is_approved' => 1,
                            'City.is_enable' => 1
                        ) ,
                        'contain' => array(
                            'Language' => array(
                                'fields' => array(
                                    'Language.iso2'
                                )
                            )
                        ) ,
                        'fields' => array(
                            'City.language_id'
                        ) ,
                        'recursive' => 1
                    ));
                    // This cache file will delete, in city model after save
                    Cache::write('site_cities_languages_' . $current_city_slug, $city);
                }
                if (!empty($city['Language']['iso2'])) {
                    setcookie('CakeCookie[city_language]', $city['Language']['iso2']);
                    $lang_code = $city['Language']['iso2'];
                } else {
                    setcookie('CakeCookie[city_language]', $lang_code);
                }
            } elseif (!empty($_COOKIE['CakeCookie']['city_language'])) {
                $lang_code = $_COOKIE['CakeCookie']['city_language'];
            }
        }
        Configure::write('lang_code', $lang_code, 'too_long');
        $translations = Cache::read($lang_code . '_translations');
        if (empty($translations) and $translations === false) {
            $this->loadModel('Translation');
            $translations = $this->Translation->find('all', array(
                'conditions' => array(
                    'Language.iso2' => $lang_code
                ) ,
                'fields' => array(
                    'Translation.key',
                    'Translation.lang_text'
                ) ,
                'contain' => array(
                    'Language' => array(
                        'fields' => array(
                            'Language.iso2'
                        )
                    )
                ) ,
                'recursive' => 0
            ));
            Cache::write($lang_code . '_translations', $translations, 'too_long');
        }
        if (!empty($translations)) {
            foreach($translations as $translation) {
                $GLOBALS['_langs'][$translation['Language']['iso2']][$translation['Translation']['key']] = $translation['Translation']['lang_text'];
            }
        }
        $this->js_vars = array();
        $js_trans_array = array(
            'Are you sure you want to ' => __l('Are you sure you want to ') ,
            'Please select atleast one record!' => __l('Please select atleast one record!') ,
            'Are you sure you want to do this action?' => __l('Are you sure you want to do this action?') ,
            'Please enter valid original price.' => __l('Please enter valid original price.') ,
            'Discount percentage should be less than 100.' => __l('Discount percentage should be less than 100.') ,
            'Discount amount should be less than original price.' => __l('Discount amount should be less than original price.') ,
            'Are you sure do you want to change the status? Once the status is changed you cannot undo the status.' => __l('Are you sure do you want to change the status? Once the status is changed you cannot undo the status.') ,
            'By clicking this button you are confirming your purchase. Once you confirmed amount will be deducted from your wallet and you can not undo this process. Are you sure you want to confirm this purchase?' => __l('By clicking this button you are confirming your purchase. Once you confirmed amount will be deducted from your wallet and you can not undo this process. Are you sure you want to confirm this purchase?') ,
            'Since you don\'t have sufficent amount in wallet, your purchase process will be proceeded to PayPal. Are you sure you want to confirm this purchase?' => __l('Since you don\'t have sufficent amount in wallet, your purchase process will be proceeded to PayPal. Are you sure you want to confirm this purchase?') ,
            'Google map could not find your location, please enter known location to google' => __l('Google map could not find your location, please enter known location to google') ,
            'Invalid extension, Only csv, txt are allowed' => __l('Invalid extension, Only csv, txt are allowed') ,
        );
        foreach($js_trans_array as $trans) {
            if (!empty($GLOBALS['_langs'][$lang_code][$trans])) {
                $this->js_vars['cfg']['lang'][$trans] = $GLOBALS['_langs'][$lang_code][$trans];
            }
        }
        // Writing Currency in cache
        $this->_cacheWriteCurrency();
        // affiliate type write cache
        $this->_cacheWriteAffiliateType();
        // Writing site name in cache, required for getting sitename retrieving in routes
        Cache::write('site.name', strtolower(Inflector::slug(Configure::read('site.name'))) , 'long');
        if (!(Cache::read('site_url_for_shell', 'long'))) {
            if ((strpos(Router::url('/', true) , 'http://m.') === false) || (strpos(Router::url('/', true) , 'http://www.m.') === false) || (strpos(Router::url('/', true) , 'https://m.') === false) || (strpos(Router::url('/', true) , 'https://www.m.') === false)) {
                Cache::write('site_url_for_shell', Router::url('/', true) , 'long');
            } elseif ((strpos(Router::url('/', true) , 'http://m.') !== false)) {
                $site_url_shell = str_replace('http://m.', 'http://', Router::url('/', true));
                Cache::write('site_url_for_shell', $site_url_shell, 'long');
            } elseif ((strpos(Router::url('/', true) , 'http://www.m.') !== false)) {
                $site_url_shell = str_replace('http://www.m.', 'http://www.', Router::url('/', true));
                Cache::write('site_url_for_shell', $site_url_shell, 'long');
            } elseif ((strpos(Router::url('/', true) , 'http://m.') !== false)) {
                $site_url_shell = str_replace('https://m.', 'https://', Router::url('/', true));
                Cache::write('site_url_for_shell', $site_url_shell, 'long');
            } elseif ((strpos(Router::url('/', true) , 'http://m.') !== false)) {
                $site_url_shell = str_replace('https://www.m.', 'https://www.', Router::url('/', true));
                Cache::write('site_url_for_shell', $site_url_shell, 'long');
            }
        }
        // Writing city routing url in cache
        if (($city_url = Cache::read('site.city_url', 'long')) === false) {
            Cache::write('site.city_url', Configure::read('site.city_url') , 'long');
        }
        // Writing default city name in cache
        $default_city = Cache::read('site.default_city', 'long');
        if (($default_city = Cache::read('site.default_city', 'long')) === false) {
            Cache::write('site.default_city', Configure::read('site.city') , 'long');
            $this->redirect(Router::url('/', true));
        }
    }
    function beforeFilter()
    {
        $city = array();
        // Coding done to disallow demo user to change the admin settings
        if ($this->request->params['action'] != 'flashupload') {
            $cur_page = $this->request->params['controller'] . '/' . $this->request->params['action'];
            $admin_demomode_updation_not_allowed_array = Configure::read('site.admin_demomode_updation_not_allowed_array');
            if ($this->Auth->user('user_type_id') && $this->Auth->user('user_type_id') == ConstUserTypes::Admin && !Configure::read('site.is_admin_settings_enabled') && (!empty($this->request->data) || $this->request->params['action'] == 'admin_delete' || $this->request->params['action'] == 'admin_update') && in_array($cur_page, $admin_demomode_updation_not_allowed_array)) {
                $this->Session->setFlash(__l('Sorry. You cannot update or delete in demo mode') , 'default', null, 'error');
                if ($cur_page == 'subscriptions/admin_subscription_customise') {
                    $this->redirect(array(
                        'controller' => 'subscriptions',
                        'action' => 'admin_subscription_customise',
                    ));
                } elseif ($cur_page == 'user_profiles/admin_edit') {
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'admin_index',
                    ));
                } else {
                    $this->redirect(array(
                        'controller' => $this->request->params['controller'],
                        'action' => 'index',
                    ));
                }
            }
        }
        // End of Code
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'geocity') {
            $this->loadModel('City');
            $city = $this->City->find('first', array(
                'conditions' => array(
                    'City.name' => $_COOKIE['city_name'],
                    'City.is_approved' => 1,
                    'City.is_enable' => 1
                ) ,
                'contain' => array(
                    'Language' => array(
                        'fields' => array(
                            'Language.iso2'
                        )
                    )
                ) ,
                'fields' => array(
                    'City.language_id',
                    'City.slug'
                ) ,
                'recursive' => 1
            ));
            if (!empty($city)) {
                $this->request->params['named']['city'] = $city['City']['slug'];
                if (!empty($city['Language']['iso2'])) {
                    Configure::write('lang_code', $city['Language']['iso2']);
                }
				$active_cities = $this->City->find('list', array(
                'conditions' => array(
                    'City.is_approved' => 1,
                    'City.is_enable' => 1
                ) ,
                'fields' => array(
                    'City.slug'
                ) ,
                'recursive' => -1
            	));
				$city_exist_url = false;
				$requested_url = $_COOKIE['_requested_url'];
				
				if($active_cities) {
					foreach($active_cities as $active_city) {
						if($city_exist_url == false) {	
							if(stristr($requested_url, $active_city) != FALSE) {
								$city_exist_url = true;
							}
						}	
					}
				}
				if($city_exist_url) {
					$this->redirect(array(
						'controller' => 'deals',
						'action' => 'index',
						'city' => $this->request->params['named']['city'],
						'admin' => false
					));
				} else {
					if (!empty($requested_url) && (!$this->RequestHandler->prefers('json'))) {
						$this->redirect(Router::url($requested_url."/city:".$this->request->params['named']['city'], false));
					}
				}
            } else {
                $this->request->params['named']['city'] = Configure::read('site.city');
                //$this->redirect(Router::url('/', true));

            }
        }
        $timezone_code = Configure::read('site.timezone_offset');
        if (!empty($timezone_code)) {
            date_default_timezone_set($timezone_code);
        }
        if (Configure::read('site.is_ssl_for_deal_buy_enabled')) {
            $secure_array = array(
                'deals/buy',
                'users/add_to_wallet',
                'gift_users/add',
                'users/login',
                'users/admin_login',
                'users/register',
                'users/company_register',
                'users/show_captcha',
            );
            $cur_page = $this->request->params['controller'] . '/' . $this->request->params['action'];
            if (in_array($cur_page, $secure_array) && $this->request->params['action'] != 'flashupload') {
                $this->Security->blackHoleCallback = 'forceSSL';
                $this->Security->requireSecure($this->request->params['action']);
            } else if (env('HTTPS') && !$this->RequestHandler->isAjax()) {
                $this->_unforceSSL();
            }
        }
        if ($this->request->params['controller'] != 'images' && $this->request->params['action'] != 'flashupload') {
            $_SESSION['city_attachment'] = '';
            $city_slug = !empty($this->request->params['named']['city']) ? $this->request->params['named']['city'] : Configure::read('site.city');
            // This cache file will delete, in city model after save
            $city = Cache::read('site_city_detail_' . $city_slug);
            if (empty($city)) {
                $this->loadModel('City');
                $city = $this->City->find('first', array(
                    'conditions' => array(
                        'City.slug' => !empty($this->request->params['named']['city']) ? $this->request->params['named']['city'] : Configure::read('site.city') ,
                        'City.is_approved' => 1
                    ) ,
                    'contain' => array(
                        'Attachment'
                    ) ,
                    'recursive' => 0
                ));
                // This cache file will delete, in city model after save
                if (!empty($city)) {
                    Cache::write('site_city_detail_' . $city['City']['slug'], $city);
                } else {
                    $this->request->params['named']['city'] = Configure::read('site.city');
                    $params = '';
                    foreach($this->request->params['pass'] as $value) {
                        $params.= $value . '/';
                    }
                    foreach($this->request->params['named'] as $key => $value) {
                        $params.= $key . ':' . $value . '/';
                    }
                    $this->redirect(array(
                        'controller' => $this->request->params['controller'],
                        'action' => $this->request->params['action'],
                        $params
                    ));
                }
            }
            if (!empty($this->request->params['named']['city']) and empty($city)) {
                $this->Session->setFlash(__l('City you have reqested is not available in') . ' ' . Configure::read('site.name') . '. ' . __l('Please select a valid city from Visit More Cities') , 'default', null, 'error');
                if (empty($this->request->params['requested'])) {
                    throw new NotFoundException(__l('Invalid request'));
                }
            }
            if (Configure::read('site.is_in_prelaunch_mode')) {
                $this->loadModel('Attachment');
                $site_background_attachment = $this->Attachment->find('first', array(
                    'conditions' => array(
                        'Attachment.class' => 'PageLogo',
                        'Attachment.description' => 'site_logo',
                    ) ,
                    'recursive' => -1
                ));
                $background_attachment = $this->Attachment->find('first', array(
                    'conditions' => array(
                        'Attachment.class' => 'PageLogo',
                        'Attachment.description' => 'background_image',
                    ) ,
                    'recursive' => -1
                ));
                $this->set('background_attachment', $background_attachment);
                $this->set('site_background_attachment', $site_background_attachment);
            }
            $this->set('city_id', $city['City']['id']);
            $this->set('city_name', $city['City']['name']);
            $this->set('city_slug', $city['City']['slug']);
            $this->set('city_attachment', $city['Attachment']);
            $this->set('stretch_type', (!empty($city['City']['stretch_type'])) ? $city['City']['stretch_type'] : '');
            // user avail balance
            if ($this->Auth->user('id')) {
                $this->loadModel('User');
                $this->set('user_available_balance', $this->User->checkUserBalance($this->Auth->user('id')));
            }
        }
        if (isset($this->request->data['Subscription']['city_id'])) {
            $this->loadModel('City');
            $city_info = $this->City->find('first', array(
                'conditions' => array(
                    'City.id' => $this->request->data['Subscription']['city_id'],
                    'City.is_approved' => 1
                ) ,
                'recursive' => -1
            ));
            setcookie('CakeCookie[city_slug]', $city_info['City']['slug'], time() +60*60*24*30, '/');
        } else if (!empty($this->request->params['named']['city']) && empty($this->request->params['isAjax']) && empty($this->request->params['requested'])) {
            //setcookie('CakeCookie[city_slug]', $this->request->params['named']['city']);
            setcookie('CakeCookie[city_slug]', $this->request->params['named']['city'], time() +60*60*24*30, '/');
        }
        if (!empty($this->request->params['named']['city'])) {
            setcookie('CakeCookie[city_slug]', $this->request->params['named']['city'], time() +60*60*24*30, '/');
        }
        // check ip is banned or not. redirect it to 403 if ip is banned
        $this->loadModel('BannedIp');
        $bannedIp = $this->BannedIp->checkIsIpBanned($this->RequestHandler->getClientIP());
        if (empty($bannedIp)) {
            $bannedIp = $this->BannedIp->checkRefererBlocked(env('HTTP_REFERER'));
        }
        if (!empty($bannedIp)) {
            if (!empty($bannedIp['BannedIp']['redirect'])) {
                header('location: ' . $bannedIp['BannedIp']['redirect']);
            } else {
                throw new ForbiddenException(__l('Invalid request'));
            }
        }
        $cur_page = $this->request->params['controller'] . '/' . $this->request->params['action'];
        $maintenance_exception_array = array(
            'devs/asset_js',
            'devs/asset_css',
            'devs/robots',
            'devs/sitemap',
        );
        // check site is under maintenance mode or not. admin can set in settings page and then we will display maintenance message, but admin side will work.
        if (Configure::read('site.maintenance_mode') && $this->Auth->user('user_type_id') != ConstUserTypes::Admin && empty($this->request->params['prefix']) && !in_array($cur_page, $maintenance_exception_array)) {
            throw new MaintenanceModeException(__l('Maintenance Mode'));
        }
        //Fix to upload the file through the flash multiple uploader
        if ((isset($_SERVER['HTTP_USER_AGENT']) and ((strtolower($_SERVER['HTTP_USER_AGENT']) == 'shockwave flash') or (strpos(strtolower($_SERVER['HTTP_USER_AGENT']) , 'adobe flash player') !== false))) and isset($this->request->params['pass'][0]) and ($this->action == 'flashupload')) {
            $this->Session->id($this->request->params['pass'][0]);
        }
        if ($this->Auth->user('fb_user_id') || (!$this->Auth->user() && Configure::read('facebook.is_enabled_facebook_connect')) || ($this->request->params['controller'] == 'cities' && ($this->request->params['action'] == 'admin_index' || $this->request->params['action'] == 'admin_edit' || $this->request->params['action'] == 'fb_update')) || $this->request->params['controller'] == 'settings') {
            App::import('Vendor', 'facebook/facebook');
            // Prevent the 'Undefined index: facebook_config' notice from being thrown.
            $GLOBALS['facebook_config']['debug'] = NULL;
            // Create a Facebook client API object.
            $this->facebook = new Facebook(array(
                'appId' => Configure::read('facebook.app_id') ,
                'secret' => Configure::read('facebook.fb_secrect_key') ,
                'cookie' => true
            ));
            $this->set('facebookObj', $this->facebook);
        }
        if (strpos($this->here, '/view/') !== false) {
            trigger_error('*** dev1framework: Do not view page through /view/; use singular/slug', E_USER_ERROR);
        }
        // check the method is exist or not in the controller
        $methods = array_flip($this->methods);
        if (!isset($methods[strtolower($this->request->params['action']) ])) {
            throw new NotFoundException(__l('Invalid request'));
        }
        // <-- For iPhone App code
        $this->Auth->fields = array(
            'username' => Configure::read('user.using_to_login') ,
            'password' => 'password'
        );
        if (!empty($_GET['key'])) {
            $this->_handleIPhoneApp();
        }
        // For iPhone App code -->
        $this->_affiliate_referral();
        $this->_checkAuth();
        $this->js_vars['cfg'] = array(
            'icm' => $GLOBALS['_city']['icm'],
            'path_relative' => Router::url('/') ,
            'path_absolute' => Router::url('/', true) ,
            'date_format' => 'M d, Y',
            //'today_date' => date('Y-m-d') ,
            'timezone' => date('Z') /(60*60) ,
            'site_name' => strtolower(Inflector::slug(Configure::read('site.name'))) ,
            'small_big_thumb.width' => Configure::read('thumb_size.small_big_thumb.width') ,
            'small_big_thumb.height' => Configure::read('thumb_size.small_big_thumb.height') ,
            'medium_big_thumb.width' => Configure::read('thumb_size.medium_big_thumb.width') ,
            'medium_big_thumb.height' => Configure::read('thumb_size.medium_big_thumb.height') ,
            'deal.is_admin_enable_commission' => Configure::read('deal.is_admin_enable_commission') ,
            'deal.commission_amount_type' => Configure::read('deal.commission_amount_type') ,
            'deal.commission_amount' => Configure::read('deal.commission_amount') ,
            'user_type_id' => $this->Auth->user('user_type_id') ,
            'result_geo_format' => Configure::read('GoogleMap.geoautocomplete_format')
        );
        $language_cookie_value = $this->Cookie->read('user_language');
        if (!empty($language_cookie_value)) {
            $user_language = $language_cookie_value;
        } else {
            $user_language = Configure::read('lang_code');
        }
        $this->js_vars['cfg']['user_language'] = $user_language;
        $_paypal_conversion_currency = Cache::read('site_paypal_conversion_currency');
        if (!empty($_paypal_conversion_currency)) {
            //$this->js_vars['cfg']['site_paypal_conversion_currency'] = $_paypal_conversion_currency;
        }
        if (!Configure::read('site.is_in_prelaunch_mode') && $this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'view' && $this->request->params['pass'][0] == 'pre-launch') {
            $this->redirect(array(
                'controller' => 'deals',
                'action' => 'index',
                'admin' => false
            ));
        }
        if (Configure::read('site.is_in_prelaunch_mode') && (($this->request->params['controller'] != 'subscriptions' && $this->request->params['controller'] != 'cities' && $this->request->params['controller'] != 'pages' && $this->request->params['controller'] != 'images' && $this->request->params['controller'] != 'devs' && $this->request->params['controller'] != 'css' && $this->request->params['controller'] != 'js') || empty($this->request->url)) && ((isset($this->request->params['prefix']) && $this->request->params['prefix'] != 'admin') || !isset($this->request->params['prefix']))) {
            if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->redirect(array(
                    'controller' => 'page',
                    'action' => 'view',
                    'pre-launch',
                    'admin' => false
                ));
            }
        }
        $this->loadModel('PaymentGateway');
        $massPayEnableCount = $this->PaymentGateway->find('count', array(
            'conditions' => array(
                'PaymentGateway.is_active' => 1,
                'PaymentGateway.is_mass_pay_enabled' => 1,
            ) ,
            'recursive' => 0
        ));
        $this->set('massPayEnableCount', $massPayEnableCount);
        parent::beforeFilter();
    }
    function _checkAuth()
    {
        $exception_array = Configure::read('site.exception_array');
        $cur_page = $this->request->params['controller'] . '/' . $this->request->params['action'];
        if (!in_array($cur_page, $exception_array) && $this->request->params['action'] != 'flashupload') {
            if (!$this->Auth->user('id')) {
                // check cookie is present and it will auto login to account when session expires
                $cookie_hash = $this->Cookie->read('User.cookie_hash');
                if (!empty($cookie_hash)) {
                    if (is_integer($this->cookieTerm) || is_numeric($this->cookieTerm)) {
                        $expires = time() +intval($this->cookieTerm);
                    } else {
                        $expires = strtotime($this->cookieTerm, time());
                    }
                    $this->loadModel('User');
                    $this->request->data = $this->User->find('first', array(
                        'conditions' => array(
                            'User.cookie_hash =' => md5($cookie_hash) ,
                            'User.cookie_time_modified <= ' => date('Y-m-d h:i:s', $expires) ,
                        ) ,
                        'fields' => array(
                            'User.' . Configure::read('user.using_to_login') ,
                            'User.password'
                        ) ,
                        'recursive' => -1
                    ));
                    // auto login if cookie is present
                    if ($this->Auth->login($this->request->data)) {
                        $this->setMaxmindInfo('login');
                        $this->loadModel('User');
                        $user_model_obj->UserLogin->insertUserLogin($this->Auth->user('id'));
                        $this->redirect(Router::url('/', true) . $this->request->url);
                    }
                }
                $this->Session->setFlash(__l('Authorisation Required'));
                $is_admin = false;
                if (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') {
                    $is_admin = true;
                }
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login',
                    'admin' => $is_admin,
                    '?f=' . $this->request->url
                ));
            }
            if (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin' and $this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->redirect(Router::url('/', true));
            }
        } else {
            $this->Auth->allow('*');
        }
        $this->Auth->autoRedirect = false;
        $this->Auth->userScope = array(
            'User.is_active' => 1,
            'User.is_email_confirmed' => 1
        );
        if (isset($this->Auth)) {
            $this->Auth->loginError = __l(sprintf('Sorry, login failed.  Either your %s or password are incorrect or admin deactivated your account.', Configure::read('user.using_to_login')));
        }
        $this->layout = 'default';
        if (Configure::read('site.is_in_prelaunch_mode') && $this->Auth->user('user_type_id') != ConstUserTypes::Admin && ((isset($this->request->params['prefix']) && $this->request->params['prefix'] != 'admin') || !isset($this->request->params['prefix']))) {
            $this->layout = 'prelaunch';
        }
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin')) {
            $this->layout = 'admin';
        }
		$controller_array = array('companies','company_addresses','transactions','money_transfer_accounts','user_cash_withdrawals');
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Company &&  ($this->request->params['controller']!='deals' && $this->request->params['action']!='index' && !isset($this->request->params['named']['company']) && $this->request->params['controller']!='pages' && $this->request->params['controller']!='contacts' && ($this->request->params['controller']=='users' && $this->request->params['action']=='add_to_wallet')  && $this->request->params['controller']!='user_profiles' && $this->request->params['controller']!='user_friends') && $this->request->params['action'] != 'view' || isset($this->request->params['named']['company']) || (((in_array($this->request->params['controller'],$controller_array) && $this->Auth->user('user_type_id') == ConstUserTypes::Company) || ($this->request->params['controller']=='companies' && $this->request->params['action']!='view')) && $this->request->params['prefix']!='admin' && $this->Auth->user('user_type_id') != ConstUserTypes::User && $this->Auth->user('user_type_id') != ConstUserTypes::Admin) || ($this->request->params['controller']=='deals' && ($this->request->params['action']=='add' || $this->request->params['action']=='live_add' || $this->request->params['action']=='edit' || $this->request->params['action']=='subdeal_edit'))) {
		
            $this->layout = 'merchant';
        }
        if (Configure::read('site.maintenance_mode') && !$this->Auth->user('user_type_id')) {
            $this->layout = 'maintenance';
        }
        if (!empty($this->request->query['api_key']) && !empty($this->request->query['api_token'])) {
            $this->layout = false;
            $this->viewPath = 'api';
        }
        // if the site is accessed with m.domain; e.g., m.videomyne.com
        if (Configure::read('site.is_mobile_app') and stripos(getenv('HTTP_HOST') , 'm.') === 0) {
            // different layout and view for mobile application
            $this->layoutPath = 'mobile';
            //If mobile views folder and necessary .ctp file exist then using that, otherwise using the normal view folder ctp
            if (file_exists(VIEWS . $this->viewPath . DS . 'mobile' . DS . $this->request->params['action'] . $this->ext)) {
                $this->viewPath.= DS . 'mobile';
            }
        }
        // if the site is accessed with touch.domain; e.g., touch.videomyne.com
        if (stripos(getenv('HTTP_HOST') , 'touch.') === 0) {
            // different layout and view for touch application
            $this->layoutPath = 'touch';
            //If touch views folder and necessary .ctp file exist then using that, otherwise using the normal view folder ctp
            if (file_exists(VIEWS . $this->viewPath . DS . 'touch' . DS . $this->request->params['action'] . $this->ext) || ($this->request->params['controller'] == 'pages' && $this->request->params['action'] == 'display')) {
                $this->viewPath.= DS . 'touch';
            }
        }
    }
    function autocomplete($param_encode = null, $param_hash = null)
    {
        $modelClass = Inflector::singularize($this->name);
        $conditions = false;
        if (isset($this->{$modelClass}->_schema['is_approved'])) {
            $conditions['is_approved = '] = '1';
        }
        $this->XAjax->autocomplete($param_encode, $param_hash, $conditions);
    }
    function show_captcha()
    {
        include_once VENDORS . DS . 'securimage' . DS . 'securimage.php';
        $img = new securimage();
        $img->show(); // alternate use:  $img->show('/path/to/background.jpg');
        $this->autoRender = false;
    }
    function captcha_play()
    {
        App::import('Vendor', 'securimage/securimage');
        $img = new Securimage();
        $this->disableCache();
        $this->RequestHandler->respondAs('mp3', array(
            'attachment' => 'captcha.mp3'
        ));
        $img->audio_format = 'mp3';
        echo $img->getAudibleCode('mp3');
    }
    function _uuid()
    {
        return sprintf('%07x%1x', mt_rand(0, 0xffff) , mt_rand(0, 0x000f));
    }
    function _unum()
    {
        $acceptedChars = '0123456789';
        $max = strlen($acceptedChars) -1;
        $unique_code = '';
        for ($i = 0; $i < 8; $i++) {
            $unique_code.= $acceptedChars{mt_rand(0, $max) };
        }
        return $unique_code;
    }
    function _redirectGET2Named($whitelist_param_names = null)
    {
        $query_strings = array();
        $ajax_query_strings = '';
        if (is_array($whitelist_param_names)) {
            foreach($whitelist_param_names as $param_name) {
                if (!empty($this->request->query[$param_name])) { // querystring
                    if ($this->request->params['isAjax']) {
                        $ajax_query_strings.= $param_name . ':' . $this->request->query[$param_name] . '/';
                    } else {
                        $query_strings[$param_name] = $this->request->query[$param_name];
                    }
                }
            }
        } else {
            $query_strings = $this->request->query;
            unset($query_strings['url']); // Can't use ?url=foo

        }
        if (!empty($query_strings) || !empty($ajax_query_strings)) {
            if ($this->request->params['isAjax']) {
                $this->redirect(array(
                    'controller' => $this->request->params['controller'],
                    'action' => $this->request->params['action'],
                    $ajax_query_strings
                ) , null, true);
            } else {
                $query_strings = array_merge($this->request->params['named'], $query_strings);
                $this->redirect($query_strings, null, true);
            }
        }
    }
	function _redirectPOST2Named($paramNames = array())
    {
        //redirect the URL with query string to namedArg like URL structure...
        $query_strings = array();
        foreach($paramNames as $paramName) {
            if (!empty($this->data[Inflector::camelize(Inflector::singularize($this->params->controller))][$paramName])) { //via GET query string
				 $query_strings[$paramName] = $this->data[Inflector::camelize(Inflector::singularize($this->params->controller))][$paramName];
            }
        }
        if (!empty($query_strings)) {
            // preserve other named params
            $query_strings = array_merge($this->request->params['named'], $query_strings);
            $this->redirect($query_strings, null, true);
        }
    }
    public function redirect($url, $status = null, $exit = true)
    {
        if (Cache::read('site.city_url', 'long') == 'prefix') {
            parent::redirect(router_url_city($url, $this->request->params['named']) , $status, $exit);
        }
        parent::redirect($url, $status, $exit);
    }
    public function flash($message, $url, $pause = 1, $layout = 'flash')
    {
        if (Cache::read('site.city_url', 'long') == 'prefix') {
            parent::flash($message, router_url_city($url, $this->request->params['named']) , $pause);
        }
        parent::redirect($message, $url, $pause);
    }
    //Force a secure connection
    function forceSSL()
    {
        if (!env('HTTPS')) {
            $this->redirect('https://' . env('SERVER_NAME') . $this->here);
        }
    }
    function _unforceSSL()
    {
        if (empty($this->request->params['requested'])) $this->redirect('http://' . $_SERVER['SERVER_NAME'] . $this->here);
    }
    // <-- For iPhone App code
    function _handleIPhoneApp()
    {
        $this->Security->enabled = false;
        $this->loadModel('User');
        if ((!empty($_POST['data']) || (!empty($_GET['data']))) && in_array($this->request->params['action'], array(
            'validate_user',
            'add',
            'buy'
        ))) {
            if (!empty($_GET['data'])) {
                $_POST['data'] = $_GET['data'];
            }
            if (!empty($_POST['data'])) {
                foreach($_POST['data'] as $controller => $values) {
                    $this->request->data[Inflector::camelize(Inflector::singularize($controller)) ] = $values;
                }
            }
        }
        if (stripos($_SERVER['HTTP_USER_AGENT'], 'iPhone') === false && stripos($_SERVER['HTTP_USER_AGENT'], 'Android') === false && stripos($_SERVER['HTTP_USER_AGENT'], 'Blackberry') === false) {
            $this->set('iphone_response', array(
                'status' => 1,
                'message' => __l('Unknown Application')
            ));
        } elseif (Configure::read('site.iphone_app_key') != $_GET['key']) {
            $this->set('iphone_response', array(
                'status' => 2,
                'message' => __l('Invalid App key')
            ));
        } else {
            if (!empty($_GET['username']) && $this->request->params['action'] != 'validate_user') {
                $this->request->data['User'][Configure::read('user.using_to_login') ] = trim($_GET['username']);
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.mobile_app_hash' => $_GET['passwd']
                    ) ,
                    'fields' => array(
                        'User.password'
                    ) ,
                    'recursive' => -1
                ));
                if (empty($user)) {
                    $this->set('iphone_response', array(
                        'status' => 1,
                        'message' => sprintf(__l('Sorry, login failed.  Your %s or password are incorrect') , Configure::read('user.using_to_login'))
                    ));
                } else {
                    $this->request->data['User']['password'] = $user['User']['password'];
                    if (!$this->Auth->login($this->request->data)) {
                        $this->set('iphone_response', array(
                            'status' => 1,
                            'message' => sprintf(__l('Sorry, login failed.  Your %s or password are incorrect') , Configure::read('user.using_to_login'))
                        ));
                    }
                    if ($this->Auth->user('id') && !empty($_GET['latitude']) && !empty($_GET['longtitude'])) {
                        $this->update_iphone_user($_GET['latitude'], $_GET['longtitude'], $this->Auth->user('id'));
                    }
                }
            }
        }
        if ($this->request->params['action'] == 'buy') {
            $this->request->data['Deal']['user_id'] = $this->Auth->user('id');
            $this->request->data['Deal']['is_gift'] = 0;
        } elseif ($this->request->params['controller'] == 'user_payment_profiles' && $this->request->params['action'] == 'add') {
            $this->request->data['UserPaymentProfile']['user_id'] = $this->Auth->user('id');
        }
    }
    function update_iphone_user($latitude, $longitude, $user_id)
    {
        $this->loadModel('User');
        $this->User->updateAll(array(
            'User.iphone_latitude' => $latitude,
            'User.iphone_longitude' => $longitude,
            'User.iphone_last_access' => "'" . date("Y-m-d H:i:s") . "'"
        ) , array(
            'User.id' => $user_id
        ));
    }
    // For iPhone App code -->
    function _cacheWriteCurrency()
    {
        // write currency in cache
        $_currencies = Cache::read('site_currencies');
        $_supported_currencies = Cache::read('site_supported_currencies');
        $_paypal_conversion_currency = Cache::read('site_paypal_conversion_currency');
        if (empty($_currencies) || empty($_supported_currencies)) {
            App::import('Model', 'Currency');
        }
        if (empty($_currencies)) {
            $this->Currency = new Currency();
            $_currencies = $this->Currency->cacheCurrency();
            Cache::write('site_currencies', $_currencies);
        }
        if (empty($_supported_currencies)) {
            $this->Currency = new Currency();
            $_currencies_2 = $this->Currency->cacheCurrency(1);
            Cache::write('site_supported_currencies', $_currencies_2);
        }
        $_authorize_net_currency = Cache::read('site_authorizenet_conversion_currency');
        $_pagseguro_net_currency = Cache::read('site_pagseguro_conversion_currency');
        if (empty($_paypal_conversion_currency) || (empty($_authorize_net_currency) && $_currencies[Configure::read('site.currency_id') ]['Currency']['id'] != ConstCurrencies::USD) || empty($_pagseguro_net_currency)) {
            App::import('Model', 'CurrencyConversion');
        }
        if (empty($_paypal_conversion_currency)) {
            $this->CurrencyConversion = new CurrencyConversion();
            $selected_currency = $_currencies[Configure::read('site.currency_id') ]['Currency']['id'];
            $c_selected_currency = $_currencies[Configure::read('site.paypal_currency_converted_id') ]['Currency']['id'];
            $_currencies_3 = $this->CurrencyConversion->cacheConversionCurrency(0, $selected_currency, $c_selected_currency);
            Cache::write('site_paypal_conversion_currency', $_currencies_3);
        }
        $is_supported = (!empty($_currencies[Configure::read('site.currency_id') ]['Currency']['is_paypal_supported']) ? $_currencies[Configure::read('site.currency_id') ]['Currency']['is_paypal_supported'] : 0);
        Configure::write('paypal.is_supported', $is_supported);
        Configure::write('paypal.conversion_currency_code', $_currencies[Configure::read('site.paypal_currency_converted_id') ]['Currency']['code']);
        Configure::write('paypal.currency_code', $_currencies[Configure::read('site.currency_id') ]['Currency']['code']);
        Configure::write('site.currency', $_currencies[Configure::read('site.currency_id') ]['Currency']['symbol']);
        Configure::write('paypal.conversion_currency_symbol', $_currencies[Configure::read('site.paypal_currency_converted_id') ]['Currency']['symbol']);
        if (empty($_authorize_net_currency) && $_currencies[Configure::read('site.currency_id') ]['Currency']['id'] != ConstCurrencies::USD) {
            $this->CurrencyConversion = new CurrencyConversion();
            $_authorize_net_currency = $this->CurrencyConversion->cacheConversionCurrency(0, $_currencies[Configure::read('site.currency_id') ]['Currency']['id'], ConstCurrencies::USD);
            Cache::write('site_authorizenet_conversion_currency', $_authorize_net_currency);
        }
        if (empty($_pagseguro_net_currency)) {
            $this->CurrencyConversion = new CurrencyConversion();
            $_pagseguro_net_currency = $this->CurrencyConversion->cacheConversionCurrency(0, $_currencies[Configure::read('site.currency_id') ]['Currency']['id'], ConstCurrencies::BRL);
            Cache::write('site_pagseguro_conversion_currency', $_pagseguro_net_currency);
        }
    }
    // affiliate type write in cache file: cake_affiliate_type_affiliate_model
    function _cacheWriteAffiliateType()
    {
        $affiliate_model = Cache::read('affiliate_model', 'affiliatetype');
        if (empty($affiliate_model) and $affiliate_model === false) {
            $this->loadModel('AffiliateType');
            $affiliateType = $this->AffiliateType->find('list', array(
                'conditions' => array(
                    'AffiliateType.is_active' => 1
                ) ,
                'fields' => array(
                    'AffiliateType.model_name',
                    'AffiliateType.id'
                ) ,
                'recursive' => -1
            ));
            foreach($affiliateType as $key => $value) {
                $splited = explode(',', $key);
                if (count($splited) > 1) {
                    unset($affiliateType[$key]);
                    $affiliate_type_id = $value;
                    foreach($splited as $key => $value) {
                        $affiliateType[$value] = $affiliate_type_id;
                    }
                }
            }
            Cache::write('affiliate_model', $affiliateType, 'affiliatetype');
            $affiliate_model = Cache::read('affiliate_model', 'affiliatetype');
        }
    }
    function _affiliate_referral()
    {
        if (!empty($this->request->params['named']['r'])) {
            $this->loadModel('User');
            $referrer = array();
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['r'],
                ) ,
                'fields' => array(
                    'User.username',
                    'User.id'
                ) ,
                'recursive' => -1
            ));
            if (!empty($user)) {
                // not check for particular url or page, so that set in refer_id in common, future apply for specific url
                $referrer['refer_id'] = $user['User']['id'];
                if (!empty($this->request->params['controller']) && $this->request->params['controller'] == 'deals') {
                    if (!empty($this->request->params['named']['category'])) {
                        $referrer['refer_id'] = $user['User']['id'];
                        $referrer['type'] = 'category';
                        $referrer['slug'] = $this->request->params['named']['category'];
                    } else if (!empty($this->request->params['action']) && $this->request->params['action'] == 'view') {
                        $referrer['refer_id'] = $user['User']['id'];
                        $referrer['type'] = 'view';
                        $referrer['slug'] = $this->request->params['pass']['0'];
                    }
                } else if (!empty($this->request->params['controller']) && $this->request->params['controller'] == 'users') {
                    $referrer['refer_id'] = $user['User']['id'];
                    $referrer['type'] = 'user';
                    $referrer['slug'] = '';
                }
                $this->Cookie->delete('referrer');
                $this->Cookie->write('referrer', $referrer, false, sprintf('+%s hours', Configure::read('affiliate.referral_cookie_expire_time')));
                unset($this->request->params['named']['r']);
                $params = '';
                foreach($this->request->params['pass'] as $value) {
                    $params.= $value . '/';
                }
                foreach($this->request->params['named'] as $key => $value) {
                    $params.= $key . ':' . $value . '/';
                }
                $this->redirect(array(
                    'controller' => $this->request->params['controller'],
                    'action' => $this->request->params['action'],
                    $params
                ));
            }
        }
    }
    function setMaxmindInfo($type = 'geo')
    {
        if (!empty($_COOKIE['_geo'])) {
            $_geo = explode('|', $_COOKIE['_geo']);
            if ($type == 'login') {
                App::import('Model', 'UserProfile');
                $this->UserProfile = new UserProfile();
                $this->UserProfile->updateAll(array(
                    'UserProfile.latitude' => $_geo[3],
                    'UserProfile.longitude' => $_geo[4],
                    'UserProfile.last_access' => "'" . date("Y-m-d H:i:s") . "'"
                ) , array(
                    'UserProfile.user_id' => $this->Auth->user('id')
                ));
            }
        }
        if (Configure::read('deal.find_near_deal_by') == 'location') {
            $data_max['maxmaind_latitude'] = '';
            $data_max['maxmaind_longitude'] = '';
            if (!empty($_COOKIE['_geo'])) {
                $_geo = explode('|', $_COOKIE['_geo']);
                $data_max['maxmaind_latitude'] = $_geo[3];
                $data_max['maxmaind_longitude'] = $_geo[4];
            }
            return $data_max;
        } else {
            $this->loadModel('City');
            $city = $this->City->find('first', array(
                'conditions' => array(
                    'City.slug' => $this->request->params['named']['city']
                ) ,
                'fields' => array(
                    'City.latitude',
                    'City.longitude'
                ) ,
                'recursive' => -1
            ));
            $data_max['maxmaind_latitude'] = $city['City']['latitude'];
            $data_max['maxmaind_longitude'] = $city['City']['longitude'];
            return $data_max;
        }
    }
    function _convertAmount($amount)
    {
        $converted = array();
        $_paypal_conversion_currency = Cache::read('site_paypal_conversion_currency');
        $is_supported = Configure::read('paypal.is_supported');
        if (isset($is_supported) && empty($is_supported)) {
            $converted['amount'] = round($amount*$_paypal_conversion_currency['CurrencyConversion']['rate'], 2);
            $converted['currency_code'] = Configure::read('paypal.conversion_currency_code');
        } else {
            $converted['amount'] = $amount;
            $converted['currency_code'] = Configure::read('paypal.currency_code');
        }
        return $converted;
    }
    function _convertPagseguroAmount($amount, $rate = 0)
    {
        $_currencies = Cache::read('site_currencies');
        $converted = array();
        $_pagseguro_net_currency = Cache::read('site_pagseguro_conversion_currency');
        if (($_currencies[Configure::read('site.currency_id') ]['Currency']['id'] != ConstCurrencies::BRL) || (!empty($rate))) {
            $rate = !empty($rate) ? $rate : $_pagseguro_net_currency['CurrencyConversion']['rate'];
            $converted['amount'] = round($amount*$rate, 2);
            $converted['rate'] = round($rate, 2);
        } else {
            $converted['amount'] = $amount;
            $converted['rate'] = '0';
        }
        $converted['currency_id'] = ConstCurrencies::BRL;
        $converted['converted_currency_id'] = $_currencies[Configure::read('site.currency_id') ]['Currency']['id'];
        return $converted;
    }
    function getConversionCurrency()
    {
        if (Configure::read('paypal.is_supported') == 0) {
            $_paypal_conversion_currency = Cache::read('site_paypal_conversion_currency');
            $_paypal_conversion_currency['supported_currency'] = Configure::read('paypal.is_supported');
            $_paypal_conversion_currency['conv_currency_code'] = Configure::read('paypal.conversion_currency_code');
            $_paypal_conversion_currency['currency_code'] = Configure::read('paypal.currency_code');
            $_paypal_conversion_currency['conv_currency_symbol'] = Configure::read('paypal.conversion_currency_symbol');
        } else {
            $_currencies = Cache::read('site_currencies');
            $_paypal_actual_currency = $_currencies[Configure::read('site.currency_id') ]['Currency'];
            $_paypal_conversion_currency['CurrencyConversion']['currency_id'] = $_paypal_actual_currency['id'];
            $_paypal_conversion_currency['CurrencyConversion']['converted_currency_id'] = $_paypal_actual_currency['id'];
            $_paypal_conversion_currency['CurrencyConversion']['rate'] = '0';
            $_paypal_conversion_currency['supported_currency'] = Configure::read('paypal.is_supported');
            $_paypal_conversion_currency['conv_currency_code'] = $_paypal_actual_currency['code'];
            $_paypal_conversion_currency['currency_code'] = $_paypal_actual_currency['code'];
            $_paypal_conversion_currency['conv_currency_symbol'] = $_paypal_actual_currency['symbol'];
        }
        return $_paypal_conversion_currency;
    }
    function getAuthorizeConversionCurrency()
    {
        $_paypal_conversion_currency = Cache::read('site_authorizenet_conversion_currency');
        $_currencies = Cache::read('site_currencies');
        if ($_currencies[Configure::read('site.currency_id') ]['Currency']['id'] == ConstCurrencies::USD) {
            App::import('Model', 'CurrencyConversion');
            $this->CurrencyConversion = new CurrencyConversion();
            $_paypal_conversion_currency = $this->CurrencyConversion->cacheConversionCurrency(0, $_currencies[Configure::read('site.currency_id') ]['Currency']['id'], ConstCurrencies::USD);
        }
        return $_paypal_conversion_currency;
    }
    function _traverse_directory($dir, $dir_count)
    {
        @$handle = opendir($dir);
        while (false !== ($readdir = @readdir($handle))) {
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
        @closedir($handle);
        @rmdir($dir);
        return true;
    }
    function isAllowed($user_type = null)
    {
        if ($user_type == ConstUserTypes::Company && !Configure::read('user.is_company_actas_normal_user')) {
            return false;
        }
        return true;
    }
    function _subscribe($data, $city, $subscription_id)
    {
        if (Configure::read('mailchimp.is_enabled') == 1) {
            App::import('Model', 'MailChimpList');
            $this->MailChimpList = new MailChimpList();
            $city_list_id = $this->MailChimpList->find('first', array(
                'conditions' => array(
                    'MailChimpList.city_id' => $data['Subscription']['city_id']
                ) ,
                'fields' => array(
                    'MailChimpList.list_id'
                )
            ));
            include_once (APP . DS . 'vendors' . DS . 'mailchimp' . DS . 'MCAPI.class.php');
            include_once (APP . DS . 'vendors' . DS . 'mailchimp' . DS . 'config.inc.php');
            $api = new MCAPI(Configure::read('mailchimp.api_key'));
            $email = $data['Subscription']['email'];
            $unsub_link = Cache::read('site_url_for_shell', 'long') . preg_replace('/\//', '', Router::url(array(
                'controller' => 'subscriptions',
                'action' => 'unsubscribe',
                $subscription_id,
                'admin' => false
            ) , false) , 1);
            $merge_vars = array(
                'UNSUBSCRIB' => $unsub_link
            );
            $retval = $api->listSubscribe($city_list_id['MailChimpList']['list_id'], $email, $merge_vars, 'html', false);
            $retval = $api->listUpdateMember($city_list_id['MailChimpList']['list_id'], $email, $merge_vars, 'html', false);
        }
        // END OF MAIL CHIMP SAVING //
        App::import('Model', 'City');
        $this->City = new City();
        $city = $this->City->find('first', array(
            'conditions' => array(
                'City.id' => $data['Subscription']['city_id']
            ) ,
            'recursive' => -1
        ));
        if (Configure::read('referral.referral_enable') && Configure::read('referral.referral_enabled_option') == ConstReferralOption::GrouponLikeRefer) {
            $referal_person = "Refer A Friend";
            $referal_amount = Configure::read('site.currency') . Configure::read('user.referral_amount');
            $referal_link = Router::url(array(
                'controller' => 'pages',
                'action' => 'refer_a_friend',
            ) , true);
        }
        if (Configure::read('referral.referral_enable') && Configure::read('referral.referral_enabled_option') == ConstReferralOption::XRefer) {
            $referal_person = "Refer" . ' ' . Configure::read('referral.no_of_refer_to_get_a_refund') . ' ' . "Friends";
            $referal_link = Router::url(array(
                'controller' => 'pages',
                'action' => 'refer_friend',
            ) , true);
            if (Configure::read('referral.refund_type') == ConstReferralRefundType::RefundDealAmount) {
                $referal_amount = __l('a free');
            } else {
                $referal_amount = Configure::read('site.currency') . Configure::read('referral.refund_amount');
            }
        }
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $this->Email = new EmailComponent($collection);
        $language_code = $this->EmailTemplate->getUserLanguageIso($this->Auth->user('id'));
        $template = $this->EmailTemplate->selectTemplate('Subscription Welcome Mail', $language_code);
        $emailFindReplace = array(
            '##FROM_EMAIL##' => $this->Subscription->changeFromEmail(Configure::read('EmailTemplate.from_email')) ,
            '##SITE_LINK##' => Router::url('/', true) ,
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##FROM_EMAIL##' => ($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from'],
            '##LEARN_HOW_LINK##' => Router::url(array(
                'controller' => 'pages',
                'action' => 'view',
                'whitelist'
            ) , true) ,
            '##REFERAL_PERSON##' => $referal_person,
            '##REFERRAL_AMOUNT##' => $referal_amount,
            '##REFER_FRIEND_LINK##' => $referal_link,
            '##FACEBOOK_LINK##' => ($city['City']['facebook_url']) ? $city['City']['facebook_url'] : Configure::read('facebook.site_facebook_url') ,
            '##TWITTER_LINK##' => ($city['City']['twitter_url']) ? $city['City']['twitter_url'] : Configure::read('twitter.site_twitter_url') ,
            '##RECENT_DEALS##' => Router::url(array(
                'controller' => 'deals',
                'action' => 'index',
                'admin' => false,
                'type' => 'recent'
            ) , true) ,
            '##CONTACT_US_LINK##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add',
                'admin' => false
            ) , true) ,
            '##SITE_LOGO##' => Router::url(array(
                'controller' => 'img',
                'action' => 'blue-theme',
                'logo-email.png',
                'admin' => false
            ) , true) ,
            '##UNSUBSCRIBE_LINK##' => Router::url(array(
                'controller' => 'subscriptions',
                'action' => 'unsubscribe',
                'city' => $city['City']['slug'],
                $subscription_id,
                'admin' => false
            ) , true) ,
            '##CONTACT_URL##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add',
                'city' => $city,
                'admin' => false
            ) , true) ,
        );
        $this->Email->from = ($template['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $template['from'];
        $this->Email->replyTo = ($template['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $template['reply_to'];
        $this->Email->to = $this->request->data['Subscription']['email'];
        $this->Email->subject = strtr($template['subject'], $emailFindReplace);
        $this->Email->content = strtr($template['email_content'], $emailFindReplace);
        $this->Email->sendAs = ($template['is_html']) ? 'html' : 'text';
        $this->Email->send($this->Email->content);
    }
    public function _sendAdminActionMail($user_id, $email_template)
    {
        $this->loadModel('EmailTemplate');
        $this->loadModel('User');
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'fields' => array(
                'User.username',
                'User.id',
                'User.email'
            ) ,
            'contain' => array(
                'UserProfile'
            ) ,
            'recursive' => 1
        ));
        $language_code = $this->User->getUserLanguageIso($user['User']['id']);
        $email = $this->EmailTemplate->selectTemplate($email_template, $language_code);
        $emailFindReplace = array(
            '##SITE_LINK##' => Router::url('/', true) ,
            '##USERNAME##' => $user['User']['username'],
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##FROM_EMAIL##' => $this->User->changeFromEmail(($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from']) ,
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
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->to = $this->User->formatToAddress($user);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
    }
}
?>
