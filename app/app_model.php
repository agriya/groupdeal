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
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @version       $Revision: 7847 $
 * @modifiedby    $LastChangedBy: renan.saddam $
 * @lastmodified  $Date: 2008-11-08 08:24:07 +0530 (Sat, 08 Nov 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppModel extends Model
{
    public $actsAs = array(
        'Containable',
        'Affiliate'
    );
    function beforeSave($options = array())
    {
        $this->useDbConfig = 'master';
        return true;
    }
    function afterSave($created)
    {
        $this->useDbConfig = 'default';
        return true;
    }
    function beforeDelete($cascade = true)
    {
        $this->useDbConfig = 'master';
        return true;
    }
    function afterDelete()
    {
        $this->useDbConfig = 'default';
        return true;
    }
    function getIdHash($ids = null)
    {
        return md5($ids . Configure::read('Security.salt'));
    }
    function isValidIdHash($ids = null, $hash = null)
    {
        return (md5($ids . Configure::read('Security.salt')) == $hash);
    }
    function findOrSaveAndGetId($data)
    {
        $findExist = $this->find('first', array(
            'conditions' => array(
                'name' => $data
            ) ,
            'fields' => array(
                'id'
            ) ,
            'recursive' => -1
        ));
        if (!empty($findExist)) {
            return $findExist[$this->name]['id'];
        } else {
            $this->data[$this->name]['name'] = $data;
            $this->save($this->data[$this->name]);
            return $this->getLastInsertId();;
        }
    }
    function _isValidCaptcha()
    {
        include_once VENDORS . DS . 'securimage' . DS . 'securimage.php';
        $img = new Securimage();
        return $img->check($this->data[$this->name]['captcha']);
    }
	 public function _isValidCaptchaSolveMedia()
    {
		include_once VENDORS . DS . 'solvemedialib.php';
		$privkey=Configure::read('captcha.verification_key');
		$hashkey=Configure::read('captcha.hash_key');
		$solvemedia_response = solvemedia_check_answer($privkey,
		$_SERVER["REMOTE_ADDR"],
		$_POST["adcopy_challenge"],
		$_POST["adcopy_response"],
		$hashkey);
		if (!$solvemedia_response->is_valid) {
			//handle incorrect answer
			return false;
		}
		else {
			return true;
		}
    }
    function _checkForPrivacy($type, $user_id, $logged_in_user, $is_boolean = false)
    {
        $is_show = true;
        App::import('Model', 'UserPermissionPreference');
        $privacy_model_obj = new UserPermissionPreference();
        $privacy = $privacy_model_obj->getUserPrivacySettings($user_id);
        if ($privacy['UserPermissionPreference'][$type] == ConstPrivacySetting::Users and !$logged_in_user) {
            $is_show = false;
        } else if ($privacy['UserPermissionPreference'][$type] == ConstPrivacySetting::Nobody) {
            $is_show = false;
        } else if ($privacy['UserPermissionPreference'][$type] == ConstPrivacySetting::Friends) {
            // To write user friends lists in config
            App::import('Model', 'UserFriend');
            $user_friend_obj = new UserFriend();
            $is_show = $user_friend_obj->checkIsFriend($logged_in_user, $user_id);
        } else if ($privacy['UserPermissionPreference'][$type] == ConstPrivacySetting::EveryOne) {
            $is_show = true;
        } 
		else if ($is_boolean) {
            $is_show = $privacy['UserPermissionPreference'][$type];
        }
        return $is_show;
    }
    function changeFromEmail($from_address = null)
    {
        if (!empty($from_address)) {
            if (preg_match('|<(.*)>|', $from_address, $matches)) {
                return $matches[1];
            } else {
                return $from_address;
            }
        }
    }
    function get_languages()
    {
        App::import('Model', 'Translation');
        $this->Translation = new Translation();
        $languages = $this->Translation->find('all', array(
            'conditions' => array(
                'Language.id !=' => 0,
                'Language.iso2 != ' => ''
            ) ,
            'fields' => array(
                'DISTINCT(Translation.language_id)',
                'Language.name',
                'Language.iso2'
            ) ,
            'order' => array(
                'Language.name' => 'ASC'
            )
        ));
        $languageList = array();
        if (!empty($languages)) {
            foreach($languages as $language) {
                $languageList[$language['Language']['iso2']] = $language['Language']['name'];
            }
        }
        return $languageList;
    }
    function formatToAddress($user = null)
    {
        if (!empty($user['UserProfile']['first_name']) && !empty($user['UserProfile']['last_name'])) {
            return $user['UserProfile']['first_name'] . ' ' . $user['UserProfile']['first_name'] . ' <' . $user['User']['email'] . '>';
        } elseif (!empty($user['UserProfile']['first_name'])) {
            return $user['UserProfile']['first_name'] . ' <' . $user['User']['email'] . '>';
        } else {
            return $user['User']['email'];
        }
    }
    public function formGooglemap($companydetails = array() , $size = '320x320')
    {
		$companyfulldetails = $companydetails;
        $companydetails = !empty($companydetails['Company']) ? $companydetails['Company'] : $companydetails;
        if ((!(is_array($companydetails))) || empty($companydetails)) {
            return false;
        }
        $color_array = array(
            array(
                'A',
                'green'
            ) ,
            array(
                'B',
                'orange'
            ) ,
            array(
                'C',
                'blue'
            ) ,
            array(
                'D',
                'yellow'
            )
        );
        $mapurl = 'http://maps.google.com/maps/api/staticmap?center=';
        if (env('HTTPS')) {
            $mapurl = 'https://maps.googleapis.com/maps/api/staticmap?center=';
        }
        if((isset($companyfulldetails['is_redeem_in_main_address']) && !empty($companyfulldetails['is_redeem_in_main_address'])) || empty($companyfulldetails['CompanyAddress']))
		{
			$mapcenter[] = str_replace(' ', '+', $companydetails['latitude']) . ',' . $companydetails['longitude'];
            $mapcenter[] = 'zoom=' . Configure::read('GoogleMap.static_map_zoom_level');
            $mapcenter[] = 'size=' . $size;
            $mapcenter[] = 'markers=color:pink|label:M|' . $companydetails['latitude'] . ',' . $companydetails['longitude'];
		}
		else
		{
            $mapcenter[] = str_replace(' ', '+', $companydetails['CompanyAddress'][0]['latitude']) . ',' . $companydetails['CompanyAddress'][0]['longitude'];
            $mapcenter[] = 'zoom=' . Configure::read('GoogleMap.static_map_zoom_level');
            $mapcenter[] = 'size=' . $size;
            $mapcenter[] = 'markers=color:pink|label:M|' . $companydetails['CompanyAddress'][0]['latitude'] . ',' . $companydetails['CompanyAddress'][0]['longitude'];
            if (!empty($companydetails['CompanyAddress'])) {
                $count = 0;
                foreach($companydetails['CompanyAddress'] as $address) {
                    if (!empty($address['latitude']) && !empty($address['longitude']) && !empty($color_array[$count][0]) && !empty($color_array[$count][1])) {
                        $mapcenter[] = 'markers=color:' . $color_array[$count][1] . '|label:' . $color_array[$count][0] . '|' . $address['latitude'] . ',' . $address['longitude'];
                        $count++;
                    }
                }
            }
		}
        $mapcenter[] = 'sensor=false';
        return $mapurl . implode('&amp;', $mapcenter);
    }
    function getCityTwitterFacebookURL($slug = null)
    {
        App::import('Model', 'City');
        $this->City = new City();
        $city = $this->City->find('first', array(
            'conditions' => array(
                'City.slug' => $slug
            ) ,
            'fields' => array(
                'City.twitter_url',
                'City.facebook_url'
            ) ,
            'recursive' => -1
        ));
        return $city;
    }
    function getGatewayTypes($field = null)
    {
        App::import('Model', 'PaymentGateway');
        $this->PaymentGateway = new PaymentGateway();
        $payment_gateway_types = array();
        $is_wallet_enabled = 0;
        $is_master_wallet_enabled = 0;
        $paymentGateways = $this->PaymentGateway->find('all', array(
            'conditions' => array(
                'PaymentGateway.is_active' => 1,
            ) ,
            'contain' => array(
                'PaymentGatewaySetting' => array(
                    'conditions' => array(
                        'PaymentGatewaySetting.key' => array(
                            $field,
                            'is_enable_wallet'
                        ) ,
                        'PaymentGatewaySetting.test_mode_value' => 1
                    ) ,
                ) ,
            ) ,
            'order' => array(
                'PaymentGateway.display_name' => 'asc'
            ) ,
            'recursive' => 1
        ));
        foreach($paymentGateways as $paymentGateway) {
            if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                if ($paymentGateway['PaymentGateway']['id'] == ConstPaymentGateways::Wallet) {
                    foreach($paymentGateway['PaymentGatewaySetting'] as $PaymentGatewaySetting) {
                        if ($PaymentGatewaySetting['key'] == 'is_enable_wallet') {
                            $is_master_wallet_enabled = 1;
                        }
                        if ($PaymentGatewaySetting['key'] == $field) {
                            $is_wallet_enabled = 1;
                        }
                    }
                    if (!empty($is_master_wallet_enabled) && !empty($is_wallet_enabled)) {
                        $payment_gateway_types[$paymentGateway['PaymentGateway']['id']] = $paymentGateway['PaymentGateway']['display_name'];
                    }
                } else {
                    $payment_gateway_types[$paymentGateway['PaymentGateway']['id']] = $paymentGateway['PaymentGateway']['display_name'];
                }
            }
        }
        return $payment_gateway_types;
    }
    function isAuthorizeNetEnabled()
    {
        App::import('Model', 'PaymentGateway');
        $this->PaymentGateway = new PaymentGateway();
        $paymentGateway = $this->PaymentGateway->find('first', array(
            'conditions' => array(
                'PaymentGateway.id' => ConstPaymentGateways::AuthorizeNet,
                'PaymentGateway.is_active' => 1
            ) ,
            'recursive' => -1
        ));
        if (!empty($paymentGateway)) {
            return true;
        }
        return false;
    }
    function getUserLanguageIso($user_id)
    {
        App::import('Model', 'UserProfile');
        $this->UserProfile = new UserProfile();
        $user = $this->UserProfile->find('first', array(
            'conditions' => array(
                'UserProfile.user_id' => $user_id
            ) ,
            'contain' => array(
                'Language'
            ) ,
            'recursive' => 3
        ));
        return !empty($user['Language']['iso2']) ? $user['Language']['iso2'] : '';
    }
    function getBranchAddresses($company_id)
    {
        $company_addresses_city = array();
        App::import('Model', 'CompanyAddress');
        $this->CompanyAddress = new CompanyAddress();
        $company_addresses = $this->CompanyAddress->find('all', array(
            'conditions' => array(
                'CompanyAddress.company_id' => $company_id
            ) ,
            'fields' => array(
                'CompanyAddress.id',
                'CompanyAddress.address1',
            ) ,
            'contain' => array(
                'City' => array(
                    'fields' => array(
                        'City.name',
                    )
                ) ,
            ) ,
            'recursive' => 1
        ));
        foreach($company_addresses as $company_address) {
            $key = $company_address['CompanyAddress']['id'];
            $company_addresses_city[$key] = $company_address['City']['name'] . " (" . $company_address['CompanyAddress']['address1'] . ")";
        }
        return $company_addresses_city;
    }
    function _convertAmount($amount)
    {
        $converted = array();
        $_paypal_conversion_currency = Cache::read('site_paypal_conversion_currency');
        $is_supported = Configure::read('paypal.is_supported');
        if (isset($is_supported) && empty($is_supported)) {
            $converted['amount'] = $amount*$_paypal_conversion_currency['CurrencyConversion']['rate'];
            $converted['currency_code'] = Configure::read('paypal.conversion_currency_code');
        } else {
            $converted['amount'] = $amount;
            $converted['currency_code'] = Configure::read('paypal.currency_code');
        }
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
    function getImageUrl($model, $attachment, $options, $path = 'absolute')
    {
        $default_options = array(
            'dimension' => 'original',
            'class' => '',
            'alt' => 'alt',
            'title' => 'title',
            'type' => 'jpg'
        );
        $options = array_merge($default_options, $options);
        $image_hash = $options['dimension'] . '/' . $model . '/' . $attachment['id'] . '.' . md5(Configure::read('Security.salt') . $model . $attachment['id'] . $options['type'] . $options['dimension'] . Configure::read('site.name')) . '.' . $options['type'];
        if ($path == 'absolute') return Cache::read('site_url_for_shell', 'long') . 'img/' . $image_hash;
        else return 'img/' . $image_hash;
    }
    function _convertAuthorizeAmount($amount, $rate = 0)
    {
        $_currencies = Cache::read('site_currencies');
        $converted = array();
        $_authorizenet_conversion_currency = Cache::read('site_authorizenet_conversion_currency');
        if (($_currencies[Configure::read('site.currency_id') ]['Currency']['id'] != ConstCurrencies::USD) || (!empty($rate))) {
            $rate = !empty($rate) ? $rate : $_authorizenet_conversion_currency['CurrencyConversion']['rate'];
            $converted['amount'] = round($amount*$rate, 2);
        } else {
            $converted['amount'] = $amount;
        }
        return $converted['amount'];
    }
    public function toSaveIp()
    {
        App::import('Model', 'Ip');
        $this->Ip = new Ip();
        $this->data['Ip']['ip'] = RequestHandlerComponent::getClientIP();
        $this->data['Ip']['host'] = RequestHandlerComponent::getReferer();
        $ip = $this->Ip->find('first', array(
            'conditions' => array(
                'Ip.ip' => $this->data['Ip']['ip']
            ) ,
            'fields' => array(
                'Ip.id'
            ) ,
            'recursive' => -1
        ));
        if (empty($ip)) {
            if (!empty($_COOKIE['_geo'])) {
                $_geo = explode('|', $_COOKIE['_geo']);
                $country = $this->Ip->Country->find('first', array(
                    'conditions' => array(
                        'Country.iso2' => $_geo[0]
                    ) ,
                    'fields' => array(
                        'Country.id'
                    ) ,
                    'recursive' => -1
                ));
                if (empty($country)) {
                    $this->data['Ip']['country_id'] = 0;
                } else {
                    $this->data['Ip']['country_id'] = $country['Country']['id'];
                }
                if (!empty($_geo[1])) {
                    $this->data['Ip']['state_id'] = $this->Ip->State->findOrSaveAndGetId($_geo[1]);
                }
                if (!empty($_geo[2])) {
                    $this->data['Ip']['city_id'] = $this->Ip->City->findOrSaveCityAndGetId($_geo[2], $this->data['Ip']['state_id'], $this->data['Ip']['country_id'], $_geo[3], $_geo[4]);
                }
                $this->data['Ip']['latitude'] = $_geo[3];
                $this->data['Ip']['longitude'] = $_geo[4];
                $this->Ip->create();
                $this->Ip->save($this->data['Ip']);
                return $this->Ip->getLastInsertId();
            } else {
                $this->log('non-save ip data');
                $this->log($this->data['Ip']);
            }
        } else {
            return $ip['Ip']['id'];
        }
    }
    /* Checking User Login Type during 'Login' -  UserLogin/insertUserLogin */
    function loginType($user_agent)
    {
        $user_login_type_id = ConstUserLoginType::Site;
        if (stripos($user_agent, 'iPhone') === true) {
            $user_login_type_id = ConstUserLoginType::IPhone;
        } elseif (stripos($user_agent, 'Android') === true) {
            $user_login_type_id = ConstUserLoginType::Android;
        }
        return $user_login_type_id;
    }
    function _uuid()
    {
        return sprintf('%07x%1x', mt_rand(0, 0xffff) , mt_rand(0, 0x000f));
    }
}
?>