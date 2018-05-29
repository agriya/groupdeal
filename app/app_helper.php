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
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
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
 * @subpackage    cake.cake
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7904 $
 * @modifiedby    $LastChangedBy: mark_story $
 * @lastmodified  $Date: 2008-12-05 22:19:43 +0530 (Fri, 05 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::import('Core', 'Helper');
/**
 * This is a placeholder class.
 * Create the same file in app/app_helper.php
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake
 */
class AppHelper extends Helper
{
    function getUserAvatar($user_id)
    {
        App::import('Model', 'User');
        $this->User = new User();
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id,
            ) ,
            'fields' => array(
                'UserAvatar.id',
                'UserAvatar.dir',
                'UserAvatar.filename'
            ) ,
            'recursive' => 0
        ));
        return $user['UserAvatar'];
    }
    function checkForPrivacy($field_to_check = null, $logged_in_user = null, $user_id = null, $is_boolean = false)
    {
        App::import('Model', 'UserPermissionPreference');
        $this->UserPermissionPreference = new UserPermissionPreference();
        $privacy = $this->UserPermissionPreference->getUserPrivacySettings($user_id);
        $is_show = true;
        if (Configure::read($field_to_check)) {
            if ($privacy['UserPermissionPreference'][$field_to_check] == ConstPrivacySetting::Users && !$logged_in_user) {
                $is_show = false;
            } else if ($privacy['UserPermissionPreference'][$field_to_check] == ConstPrivacySetting::Nobody) {
                $is_show = false;
            } else if ($privacy['UserPermissionPreference'][$field_to_check] == ConstPrivacySetting::Friends) {
                // To write user friends lists in config
                App::import('Model', 'UserFriend');
                $this->UserFriend = new UserFriend();
                $is_show = $this->UserFriend->checkIsFriend($logged_in_user, $user_id);
            } else if ($privacy['UserPermissionPreference'][$field_to_check] == ConstPrivacySetting::EveryOne) {
                $is_show = true;
            }else if ($is_boolean) {
                $is_show = $privacy['UserPermissionPreference'][$field_to_check];
            }
        }
        return $is_show;
    }
    function getLanguage()
    {
        $languages = Cache::read('site_languages');
        if (empty($languages)) {
            App::import('Model', 'Translation');
            $this->Translation = new Translation();
            $languages = $this->Translation->find('all', array(
                'conditions' => array(
                    'Language.id !=' => 0,
                    'Language.is_active' => 1
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
            // we delete cache file in translation and language model in afterSave and afterDelete
            // we delete in languages/admin_update also.
            Cache::write('site_languages', $languages);
        }
        $languageList = array();
        if (!empty($languages)) {
            foreach($languages as $language) {
                $languageList[$language['Language']['iso2']] = $language['Language']['name'];
            }
        }
        return $languageList;
    }
    function getCity()
    {
        App::import('Model', 'City');
        $this->City = new City();
        $cities = $this->City->find('all', array(
            'conditions' => array(
                'City.is_approved' => 1,
                'City.is_enable' => 1
            ) ,
            'fields' => array(
                'City.id',
                'City.name',
                'City.slug',
                'City.active_deal_count'
            ) ,
            'order' => array(
                'City.name' => 'asc'
            ) ,
            'recursive' => -1
        ));
        $cityList = array();
        if (!empty($cities)) {
            foreach($cities as $city) {
                $cityList[$city['City']['id']] = $city['City']['name'];
            }
        }
        return $cityList;
    }
    function getCompany($user_id = null)
    {
        App::import('Model', 'Company');
        $this->Company = new Company();
        $company = $this->Company->find('first', array(
            'conditions' => array(
                'Company.user_id' => $user_id,
            ) ,
            'fields' => array(
                'Company.id',
                'Company.name',
                'Company.slug',
                'Company.user_id',
                'Company.is_company_profile_enabled',
                'Company.is_online_account'
            ) ,
            'recursive' => -1
        ));
        return $company;
    }
    function isAllowed($user_type = null)
    {
        if ($user_type == ConstUserTypes::Company && !Configure::read('user.is_company_actas_normal_user')) {
            return false;
        }
        return true;
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
        if (empty($city['City']['facebook_url'])) {
            $city['City']['facebook_url'] = (env('HTTPS')) ? str_replace("http://", "https://", Configure::read('facebook.site_facebook_url')) : Configure::read('facebook.site_facebook_url');
        } else {
            $city['City']['facebook_url'] = (env('HTTPS')) ? str_replace("http://", "https://", $city['City']['facebook_url']) : $city['City']['facebook_url'];
        }
        if (empty($city['City']['twitter_url'])) {
            $city['City']['twitter_url'] = (env('HTTPS')) ? str_replace("http://", "https://", Configure::read('twitter.site_twitter_url')) : Configure::read('twitter.site_twitter_url');
        } else {
            $city['City']['twitter_url'] = (env('HTTPS')) ? str_replace("http://", "https://", $city['City']['twitter_url']) : $city['City']['twitter_url'];
        }
        return $city;
    }
    public function url($url = null, $full = false)
    {
        if (Cache::read('site.city_url', 'long') == 'prefix') {
            return parent::url(router_url_city($url, $this->params['named']) , $full);
        }
        return parent::url($url, $full);
    }
    function total_saved()
    {
        App::import('Model', 'DealUser');
        $this->DealUser = new DealUser();
        $total_saved = $this->DealUser->Deal->find('first', array(
            'conditions' => array(
                'Deal.deal_status_id' => ConstDealStatus::PaidToCompany
            ) ,
            'fields' => array(
                'SUM(Deal.savings * Deal.deal_user_count) as total_saved'
            ) ,
            'recursive' => -1
        ));
        $total_bought = $this->DealUser->find('first', array(
            'fields' => array(
                'SUM(DealUser.quantity) as total_bought'
            ) ,
            'recursive' => -1
        ));
        $total_array = array(
            'total_saved' => (!empty($total_saved[0]['total_saved'])) ? $total_saved[0]['total_saved'] : 0,
            'total_bought' => (!empty($total_bought[0]['total_bought'])) ? $total_bought[0]['total_bought'] : 0,
        );
        return $total_array;
    }
    function truncate($text, $length = 100, $ending = '...', $exact = true, $considerHtml = false)
    {
        return $this->Text->truncate($this->cText($text, false) , $length, $ending, $exact, $considerHtml);
    }
    function cCurrency($str, $wrap = 'span', $title = false, $currency_code = null)
    {        
        if (empty($currency_code)) {
            $currency_code = Configure::read('paypal.currency_code');
        }
        $_currencies = Cache::read('site_currencies');
		$_precision = $_currencies[Configure::read('site.currency_id')]['Currency']['decimals'];
        $changed = (($r = floatval($str)) != $str);
        $rounded = (($rt = round($r, $_precision)) != $r);
        $r = $rt;
        if ($wrap) {
            if (!$title) {
                $title = ucwords(Numbers_Words::toCurrency($r, 'en_US', $currency_code));
            }
            $r = '<' . $wrap . ' class="c' . $changed . ' cr' . $rounded . '" title="' . $title . '">' . number_format($r, $_precision, $_currencies[Configure::read('site.currency_id') ]['Currency']['dec_point'], $_currencies[Configure::read('site.currency_id') ]['Currency']['thousands_sep']) . '</' . $wrap . '>';
        }
        return $r;
    }
    function pCurrency($str, $wrap = 'span', $title = false) // Used for PayPal Conversion Purpose //

    {
        $getCurr = $this->getConversionCurrency();
        $_precision = 2;
        $_currencies = Cache::read('site_currencies');
        $changed = (($r = floatval($str)) != $str);
        $rounded = (($rt = round($r, $_precision)) != $r);
        $r = $rt;
        if ($wrap) {
            if (!$title) {
                $title = ucwords(Numbers_Words::toCurrency($r, 'en_US', $getCurr['conv_currency_code']));
            }
            $r = '<' . $wrap . ' class="c' . $changed . ' cr' . $rounded . '" title="' . $title . '">' . number_format($r, $_precision, $_currencies[$getCurr['CurrencyConversion']['converted_currency_id']]['Currency']['dec_point'], $_currencies[$getCurr['CurrencyConversion']['converted_currency_id']]['Currency']['thousands_sep']) . '</' . $wrap . '>';
        }
        return $r;
    }
    function cInt($str, $wrap = 'span', $title = false)
    {
        $_currencies = Cache::read('site_currencies');
        $changed = (($r = intval($str)) != $str);
        if ($wrap) {
            if (!$title) {
                $title = $this->_num2words($r, 'en_US');
            }
            $r = '<' . $wrap . ' class="c' . $changed . '" title="' . $title . '">' . number_format($r, 0, '', $_currencies[Cache::read('site.currency_id') ]['Currency']['thousands_sep']) . '</' . $wrap . '>';
        }
        return $r;
    }
    function cFloat($str, $wrap = 'span', $title = false)
    {
        $_precision = 2;
        $_currencies = Cache::read('site_currencies');
        $changed = (($r = floatval($str)) != $str);
        $rounded = (($rt = round($r, $_precision)) != $r);
        $r = $rt;
        if ($wrap) {
            if (!$title) {
                $title = $this->_num2words($r, 'en_US', $_precision);
            }
            $r = '<' . $wrap . ' class="c' . $changed . ' cr' . $rounded . '" title="' . $title . '">' . number_format($r, $_precision, $_currencies[Cache::read('site.currency_id') ]['Currency']['dec_point'], $_currencies[Cache::read('site.currency_id') ]['Currency']['thousands_sep']) . '</' . $wrap . '>';
        }
        return $r;
    }
    function getUserLink($user_details)
    {
        if ($user_details['user_type_id'] == ConstUserTypes::Admin || $user_details['user_type_id'] == ConstUserTypes::User) {
            $user_details['full_name'] = (!empty($user_details['full_name'])) ? $user_details['full_name'] : $user_details['username'];
            return $this->link($this->cText($user_details['username']) , array(
                'controller' => 'users',
                'action' => 'view',
                $user_details['username'],
                'admin' => false
            ) , array(
                'title' => $this->cText($user_details['full_name'], false) ,
                'escape' => false,
                'class' => 'user-name'
            ));
        }
        //for company
        if ($user_details['user_type_id'] == ConstUserTypes::Company) {
            $companyDetails = $this->getCompany($user_details['id']);
            if (!$companyDetails['Company']['is_company_profile_enabled'] || !$companyDetails['Company']['is_online_account']) {
                return $this->cText($companyDetails['Company']['name']);
            }
            if ((!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') || empty($_SESSION['Auth']['User']['id']) || ($_SESSION['Auth']['User']['id'] != $companyDetails['Company']['user_id'])) {
                return $this->link($this->cText($companyDetails['Company']['name'], false) , array(
                    'controller' => 'companies',
                    'action' => 'view',
                    'admin' => false,
                    $companyDetails['Company']['slug']
                ) , array(
                    'title' => $this->cText($companyDetails['Company']['name'], false) ,
                    'escape' => false,
                    'class' => 'user-name'
                ));
            } else {
                return $this->link($this->cText($companyDetails['Company']['name'], false) , array(
                    'controller' => 'companies',
                    'action' => 'dashboard'
                ) , array(
                    'title' => $this->cText($companyDetails['Company']['name'], false) ,
                    'escape' => false,
                    'class' => 'user-name'
                ));
            }
        }
    }
    function getUserAvatarLink($user_details, $dimension = 'medium_thumb', $is_link = true)
    {
        App::import('Model', 'Setting');
        $this->Setting = new Setting();
        App::import('Model', 'User');
        $modelObj = new User();
        $user = $modelObj->find('first', array(
            'conditions' => array(
                'User.id' => $user_details['id'],
            ) ,
            'fields' => array(
                'UserAvatar.id',
                'UserAvatar.dir',
                'UserAvatar.filename',
                'UserAvatar.height',
                'UserAvatar.width',
                'User.profile_image_id',
                'User.twitter_avatar_url',
                'User.fb_user_id',
                'User.username',
                'User.id',
            ) ,
            'recursive' => 0
        ));
        if ($user_details['user_type_id'] == ConstUserTypes::Admin || $user_details['user_type_id'] == ConstUserTypes::User || $user_details['user_type_id'] == ConstUserTypes::Company) {
            $user_image = '';
            // Setting Default Profile Image //
            $width = $this->Setting->find('first', array(
                'conditions' => array(
                    'Setting.name' => 'thumb_size.' . $dimension . '.width'
                ) ,
                'recursive' => -1
            ));
            $height = $this->Setting->find('first', array(
                'conditions' => array(
                    'Setting.name' => 'thumb_size.' . $dimension . '.height'
                ) ,
                'recursive' => -1
            ));
            if (!empty($user['User']['fb_user_id'])) {
                $user_image = $this->getFacebookAvatar($user['User']['fb_user_id'], $height['Setting']['value'], $width['Setting']['value']);
            } elseif (!empty($user['User']['twitter_avatar_url'])) {
                $user_image = $this->image($user['User']['twitter_avatar_url'], array(
                    'title' => $this->cText($user['User']['username'], false) ,
                    'width' => $width['Setting']['value'],
                    'height' => $height['Setting']['value']
                ));
            }
            // Setting Profile Image based on settings choosed by user //
            if ($user['User']['profile_image_id'] == ConstProfileImage::Twitter) {
                $user_image = $this->image($user['User']['twitter_avatar_url'], array(
                    'title' => $this->cText($user['User']['username'], false) ,
                    'width' => $width['Setting']['value'],
                    'height' => $height['Setting']['value']
                ));
            } elseif ($user['User']['profile_image_id'] == ConstProfileImage::Facebook) {
                $width = $this->Setting->find('first', array(
                    'conditions' => array(
                        'Setting.name' => 'thumb_size.' . $dimension . '.width'
                    ) ,
                    'recursive' => -1
                ));
                $height = $this->Setting->find('first', array(
                    'conditions' => array(
                        'Setting.name' => 'thumb_size.' . $dimension . '.height'
                    ) ,
                    'recursive' => -1
                ));
                $user_image = $this->getFacebookAvatar($user['User']['fb_user_id'], $height['Setting']['value'], $width['Setting']['value']);
            } elseif ($user['User']['profile_image_id'] == ConstProfileImage::Upload || empty($user_image)) {
                //get user image
                $user_image = $this->showImage('UserAvatar', (!empty($user_details['UserAvatar'])) ? $user_details['UserAvatar'] : '', array(
                    'dimension' => $dimension,
                    'alt' => sprintf('[Image: %s]', $user_details['username']) ,
                    'title' => $user_details['username']
                ));
            }
            //return image to user
            if ($user_details['user_type_id'] == ConstUserTypes::Company) {
                return (!$is_link) ? $user_image : $this->link($user_image, array(
                    'controller' => 'companies',
                    'action' => 'dashboard',
                    'admin' => false
                ) , array(
                    'title' => $this->cText($user_details['username'], false) ,
                    'escape' => false
                ));
            } else {
                return (!$is_link) ? $user_image : $this->link($user_image, array(
                    'controller' => 'users',
                    'action' => 'view',
                    $user_details['username'],
                    'admin' => false
                ) , array(
                    'title' => $this->cText($user_details['username'], false) ,
                    'escape' => false
                ));
            }
        }
        //for company
        if ($user_details['user_type_id'] == ConstUserTypes::Company) {
            $companyDetails = $this->getCompany($user_details['id']);
            //get user image
            $user_image = $this->showImage('UserAvatar', $user_details['UserAvatar'], array(
                'dimension' => $dimension,
                'alt' => sprintf('[Image: %s]', $this->cText($companyDetails['Company']['name'], false)) ,
                'title' => $this->cText($companyDetails['Company']['name'], false)
            ));
            //return image to user
            return (!$companyDetails['Company']['is_company_profile_enabled'] || !$is_link) ? $user_image : $this->link($user_image, array(
                'controller' => 'companies',
                'action' => 'view',
                $companyDetails['Company']['slug'],
                'admin' => false
            ) , array(
                'title' => $this->cText($companyDetails['Company']['name'], false) ,
                'escape' => false
            ));
        }
    }
    function getFacebookAvatar($fbuser_id, $height = 35, $width = 35)
    {
        if ($height > 200) {
            $image_url = $this->image("http://graph.facebook.com/{$fbuser_id}/picture?type=large", array(
                'height' => $height,
                'width' => $width
            ));
        } elseif ($height > 100) {
            $image_url = $this->image("http://graph.facebook.com/{$fbuser_id}/picture?type=normal", array(
                'height' => $height,
                'width' => $width
            ));
        } else {
            $image_url = $this->image("http://graph.facebook.com/{$fbuser_id}/picture", array(
                'height' => $height,
                'width' => $width
            ));
        }
        return $image_url;
    }
    function transactionDescription($transaction)
    {
        $deal_name = $deal_slug = $friend_link = $user_link = '';
        $user_link = $this->getUserLink($transaction['User']);
        if ($transaction['Transaction']['class'] == 'DealUser') {
            $deal_name = (!empty($transaction['DealUser']['Deal']['name'])) ? $transaction['DealUser']['Deal']['name'] : '';
            $deal_slug = (!empty($transaction['DealUser']['Deal']['slug'])) ? $transaction['DealUser']['Deal']['slug'] : '';
            if ($transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::DealGift || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::PartallyAmountTakenForGiftPurchase) {
                $friend_link = $this->cText($transaction['DealUser']['gift_email'], false);
            }
        } elseif ($transaction['Transaction']['class'] == 'Deal') {
            $deal_name = (!empty($transaction['Deal']['display_field'])) ? $transaction['Deal']['display_field'] : '';
            $deal_slug = (!empty($transaction['Deal']['slug'])) ? $transaction['Deal']['slug'] : '';
            if (!empty($transaction['Deal']['Company'])) {
                $company_name = $transaction['Deal']['Company']['name'];
                $company_slug = $transaction['Deal']['Company']['slug'];
            }
        }
        if ($transaction['Transaction']['class'] == 'GiftUser') {
            if ($transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::GiftSent || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::PartallyAmountTakenForGiftCardPurchase) {
                if ($transaction['GiftUser']['gifted_to_user_id']) {
                    $friend_link = $this->getUserLink($transaction['GiftUser']['GiftedToUser']);
                } else {
                    $friend_link = $transaction['GiftUser']['friend_mail'];
                }
            } else {
                $friend_link = $this->getUserLink($transaction['GiftUser']['User']);
            }
        }
        if ($transaction['Transaction']['class'] == 'SecondUser') {
            if ($transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AmountRefundedForRejectedWithdrawalRequest || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AdminRejecetedWithdrawalRequest || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AddedToWallet || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::UserWithdrawalRequest || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AmountApprovedForUserCashWithdrawalRequest || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AffliateFailedWithdrawalRequestRefundToUser || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::FailedWithdrawalRequestRefundToUser || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AcceptCashWithdrawRequest || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::DeductedAmountForOfflineCompany || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AffliateAmountRefundedForRejectedWithdrawalRequest || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AffliateUserWithdrawalRequest || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AffliateAmountApprovedForUserCashWithdrawalRequest || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AffliateAcceptCashWithdrawRequest || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AddFundToWallet || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AmountTakenForAffiliate || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::ReferralAmount || $transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::DeductFundFromWallet ||
			$transaction['Transaction']['transaction_type_id'] == ConstTransactionTypes::AffliateAdminRejecetedWithdrawalRequest) {
                $user_link = $this->getUserLink($transaction['User']);
				$affiliate_user_link = $this->getUserLink($transaction['User']);				
            } else {
                $user_link = $this->getUserLink($transaction['SecondUser']);				
            }
        }
        if (!empty($transaction['DealUser']['payment_gateway_id']) && !empty($transaction['PaymentGateway']['name'])) {
            $payment_gateway_used = $transaction['PaymentGateway']['name'];
        }
        $transactionReplace = array(
            '##DEAL_LINK##' => (!empty($deal_slug) && !empty($deal_name)) ? $this->link($this->cText($deal_name) , array(
                'controller' => 'deals',
                'action' => 'view',
                $deal_slug,
                'admin' => false
            ) , array(
                'escape' => false,
                'title' => $this->cText($deal_name, false)
            )) : '',
            '##DEAL_NAME##' => (!empty($deal_slug) && !empty($deal_name)) ? $this->link($this->cText($deal_name) , array(
                'controller' => 'deals',
                'action' => 'view',
                $deal_slug,
                'admin' => false
            ) , array(
                'escape' => false,
                'title' => __l('View this deal')
            )) : '',
            '##COMPANY_NAME##' => (!empty($company_slug) && !empty($company_name)) ? $this->link($this->cText($company_name) , array(
                'controller' => 'company',
                'action' => 'view',
                $company_slug,
                'admin' => false
            ) , array(
                'escape' => false,
                'title' => __l('View this merchant')
            )) : '',
            '##AFFILIATE_USER##' => $affiliate_user_link,
            '##CHARITY_USER##' => $this->link((!empty($transaction['Charity']['name']) ? $transaction['Charity']['name'] : '') , array(
                'controller' => 'charities',
                'action' => 'view',
                (!empty($transaction['Charity']['slug']) ? $transaction['Charity']['slug'] : '') ,
                'admin' => false
            )) ,
            '##FRIEND_LINK##' => $friend_link,
            '##USER_LINK##' => $user_link,
            '##GATEWAY##' => (!empty($payment_gateway_used) ? __l('using') . ' ' . $payment_gateway_used : '')
        );
		$accept_request = array(ConstTransactionTypes::AcceptCashWithdrawRequest,ConstTransactionTypes::AffliateAmountRefundedForRejectedWithdrawalRequest);
		if (in_array($transaction['Transaction']['transaction_type_id'], $accept_request)) {		
			if($transaction['Transaction']['payment_gateway_id'] == 0)
			{
				$transactionReplace['to your money transfer account'] = 'via manual pay';
				$transactionReplace['to his money transfer account'] = 'via manual pay';
			}
		}
        if ((!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') && !empty($transaction['TransactionType']['message_for_admin'])) {
            return strtr($transaction['TransactionType']['message_for_admin'], $transactionReplace);
        } else {
            return strtr($transaction['TransactionType']['message'], $transactionReplace);
        }
    }
    public function formGooglemap($companydetails = array() , $size = '320x320')
    {
        $companyfulldetails = $companydetails;
        $companydetails = !empty($companydetails['Company']) ? $companydetails['Company'] : $companydetails;
        if ((Configure::read('GoogleMap.embedd_map') == 'Static') || (!empty($this->params['named']['type']) && $this->params['named']['type'] == 'print')) {
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
        } else {
            $map_size = explode('x', $size);
            $embeddmapurl[] = 'http://maps.google.com/maps?f=q&amp;hl=en&amp;geocode=;';
            if (env('HTTPS')) {
                $mapurl = 'https://maps.google.com/maps?f=q&amp;hl=en&amp;geocode=;';
            }
            if ((isset($companyfulldetails['is_redeem_in_main_address']) && !empty($companyfulldetails['is_redeem_in_main_address'])) || empty($companyfulldetails['CompanyAddress'])) {
                $company_address = !empty($companyfulldetails['address1']) ? $companyfulldetails['address1'] . '+' : '';
                //$company_address.= !empty($companyfulldetails['address2']) ? $companyfulldetails['address2'] . '+' : '';
                $company_address.= !empty($companyfulldetails['City']['name']) ? $companyfulldetails['City']['name'] . '+' : '';
                $company_address.= !empty($companyfulldetails['State']['name']) ? $companyfulldetails['State']['name'] . '+' : '';
                $company_address.= !empty($companyfulldetails['Country']['name']) ? $companyfulldetails['Country']['name'] . '+' : '';
                $company_address.= !empty($companyfulldetails['Company']['zip']) ? $companyfulldetails['Company']['zip'] : '';
                $embeddmapurl[] = 'q=' . $company_address;
                $embeddmapurl[] = 'll=' . str_replace(' ', '+', $companyfulldetails['latitude']) . ',' . $companyfulldetails['longitude'];
            } else {
                if (!empty($companyfulldetails['CompanyAddress'])) {
				
                    // Quickfix: showing only first address, since multplie address couldn't be show in iframe based map(for now) //
                    foreach($companyfulldetails['CompanyAddress'] as $companyfulldetail) {
                        $company_address = !empty($companyfulldetail['address1']) ? $companyfulldetail['address1'] . '+' : '';
                        //$company_address.= !empty($companyfulldetail['address2']) ? $companyfulldetail['address2'] . '+' : '';
                        $company_address.= !empty($companyfulldetail['City']['name']) ? $companyfulldetail['City']['name'] . '+' : '';
                        $company_address.= !empty($companyfulldetail['State']['name']) ? $companyfulldetail['State']['name'] . '+' : '';
                        $company_address.= !empty($companyfulldetail['Country']['name']) ? $companyfulldetail['Country']['name'] . '+' : '';
                        $company_address.= !empty($companyfulldetail['Company']['zip']) ? $companyfulldetail['Company']['zip'] : '';
                        $embeddmapurl[] = 'q=' . $company_address;
                        $embeddmapurl[] = 'll=' . str_replace(' ', '+', $companyfulldetail['latitude']) . ',' . $companyfulldetail['longitude'];
                        break;
                    }
                }
            }
            $embeddmapurl[] = 'z=' . Configure::read('GoogleMap.static_map_zoom_level');
            //$embeddmapurl[] = 'markers=color:pink|label:M|' . $companydetails['latitude'] . ',' . $companydetails['longitude'];
            $embeddmapurl[] = 'output=embed';
            //$embeddmapurl[] = '&amp;iwloc=near';
            $embeddmapurl = implode('&amp;', $embeddmapurl);
            $embbedd = "<iframe width='" . $map_size['0'] . "' height='" . $map_size['1'] . "' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='" . $embeddmapurl . "'></iframe>";
            return $embbedd;
        }
    }
    function cDate($str, $wrap = 'span', $title = false)
    {
        $changed = (($r = $this->htmlPurifier->purify(strftime(Configure::read('site.date.format') , strtotime($str . ' GMT')))) != strftime(Configure::read('site.date.format') , strtotime($str . ' GMT')));
        if ($wrap) {
            if (!$title) {
                $title = ' title="' . strftime(Configure::read('site.datetime.tooltip') , strtotime($str . ' GMT')) . ' ' . Configure::read('site.timezone_offset') . '"';
            }
            $r = '<' . $wrap . ' class="c' . $changed . '"' . $title . '>' . $r . '</' . $wrap . '>';
        }
        return $r;
    }
    function cDateTime($str, $wrap = 'span', $title = false)
    {
        $changed = (($r = $this->htmlPurifier->purify(strftime(Configure::read('site.datetime.format') , strtotime($str . ' GMT')))) != strftime(Configure::read('site.datetime.format') , strtotime($str . ' GMT')));
        if ($wrap) {
            if (!$title) {
                $title = ' title="' . strftime(Configure::read('site.datetime.tooltip') , strtotime($str . ' GMT')) . ' ' . Configure::read('site.timezone_offset') . '"';
            }
            $r = '<' . $wrap . ' class="c' . $changed . '"' . $title . '>' . $r . '</' . $wrap . '>';
        }
        return $r;
    }
    function cTime($str, $wrap = 'span', $title = false)
    {
        $changed = (($r = $this->htmlPurifier->purify(strftime(Configure::read('site.time.format') , strtotime($str . ' GMT')))) != strftime(Configure::read('site.time.format') , strtotime($str . ' GMT')));
        if ($wrap) {
            if (!$title) {
                $title = ' title="' . strftime(Configure::read('site.datetime.tooltip') , strtotime($str . ' GMT')) . ' ' . Configure::read('site.timezone_offset') . '"';
            }
            $r = '<' . $wrap . ' class="c' . $changed . '"' . $title . '>' . $r . '</' . $wrap . '>';
        }
        return $r;
    }
    function cBool($str, $wrap = 'span', $title = false)
    {
        $_options = array(
            0 => __l('No') ,
            1 => __l('Yes')
        );
        if (isset($_options[$str])) {
            $str = $_options[$str];
        }
        return $this->cText($str, $wrap, $title);
    }
    function cDateTimeHighlight($str, $wrap = 'span', $title = false)
    {
        if (strtotime(_formatDate('Y-m-d', strtotime($str))) == strtotime(date('Y-m-d'))) {
            $str = strftime('%I:%M %p', strtotime($str . ' GMT'));
        } else if (strtotime(date('Y-m-d', strtotime(_formatDate('Y-m-d', strtotime($str))))) > strtotime(date('Y-m-d')) || mktime(0, 0, 0, 0, 0, date('Y', strtotime(_formatDate('Y-m-d', strtotime($str))))) < mktime(0, 0, 0, 0, 0, date('Y'))) {
            $str = strftime('%b %d, %Y', strtotime($str . ' GMT'));
        } else {
            $str = strftime('%b %d', strtotime($str . ' GMT'));
        }
        $changed = (($r = $this->htmlPurifier->purify($str)) != $str);
        if ($wrap) {
            if (!$title) {
                $title = ' title="' . strftime(Configure::read('site.datetime.tooltip') , strtotime($str . ' GMT')) . ' ' . Configure::read('site.timezone_offset') . '"';
            }
            $r = '<' . $wrap . ' class="c' . $changed . '"' . $title . '>' . $r . '</' . $wrap . '>';
        }
        return $r;
    }
    function isWalletEnabled($field = null)
    {
        App::import('Model', 'PaymentGatewaySetting');
        $this->PaymentGatewaySetting = new PaymentGatewaySetting();
        $paymentGatewaySetting = $this->PaymentGatewaySetting->find('first', array(
            'conditions' => array(
                'PaymentGatewaySetting.key' => 'is_enable_wallet',
                'PaymentGatewaySetting.payment_gateway_id' => ConstPaymentGateways::Wallet
            ) ,
            'contain' => array(
                'PaymentGateway'
            ) ,
            'recursive' => 1
        ));
        if (!empty($paymentGatewaySetting['PaymentGatewaySetting']['test_mode_value']) && !empty($paymentGatewaySetting['PaymentGateway']['is_active'])) {
            return true;
        }
        return false;
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
    function siteCurrencyFormat($amount, $currency = null, $append_ccurency = false)
    {
        if (empty($currency)) {
            $currency = Configure::read('site.currency');
        }
        if (Configure::read('site.currency_symbol_place') == 'left') {
            if (!empty($append_ccurency)) {
                return $currency . $this->cCurrency($amount);
            }
            return $currency . $amount;
        } else {
            if (!empty($append_ccurency)) {
                return $this->cCurrency($amount) . $currency;
            }
            return $amount . $currency;
        }
    }
    function getCompanyAddress($companyAddress_id = null)
    {
        App::import('Model', 'CompanyAddress');
        $this->CompanyAddress = new CompanyAddress();
        $company = $this->CompanyAddress->find('first', array(
            'conditions' => array(
                'CompanyAddress.id' => $companyAddress_id,
            ) ,
            'contain' => array(
                'City' => array(
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.slug',
                    )
                ) ,
                'State' => array(
                    'fields' => array(
                        'State.id',
                        'State.name'
                    )
                ) ,
                'Country' => array(
                    'fields' => array(
                        'Country.id',
                        'Country.name',
                        'Country.slug',
                    )
                )
            ) ,
            'recursive' => 2
        ));
        return $company;
    }
    function getCurrencies()
    {
        $currencies = Cache::read('site_currencies');
        if (empty($currencies)) {
            App::import('Model', 'Currency');
            $this->Currency = new Currency();
            $currencies = $this->Currency->cacheCurrency();
            Cache::write('site_currencies', $currencies);
        }
        $currencyList = array();
        if (!empty($currencies)) {
            $s = 0;
            foreach($currencies as $currency) {
                $currencyList[$s] = $currency['Currency']['code'];
                $s++;
            }
        }
        return $currencyList;
    }
    function getSupportedCurrencies()
    {
        $supported_currencies = Cache::read('site_supported_currencies');
        if (empty($supported_currencies)) {
            App::import('Model', 'Currency');
            $this->Currency = new Currency();
            $supported_currencies = $this->Currency->cacheCurrency(1);
            Cache::write('site_supported_currencies', $supported_currencies);
        }
        $currencyList = array();
        if (!empty($supported_currencies)) {
            $s = 0;
            foreach($supported_currencies as $currency) {
                $currencyList[$s] = $currency['Currency']['code'];
                $s++;
            }
        }
        return $currencyList;
    }
    function getSubscriptionAttachment()
    {
        $attachment = Cache::read('subscription_attachment');
        if (empty($attachment)) {
            App::import('Model', 'Attachment');
            $this->Attachment = new Attachment();
            $attachment = $this->Attachment->find('first', array(
                'conditions' => array(
                    'Attachment.foreign_id' => 2,
                    'Attachment.class' => 'PageLogo'
                ) ,
                'order' => array(
                    'Attachment.id DESC'
                )
            ));
            Cache::write('subscription_attachment', $attachment);
        }
        return $attachment;
    }
    function getConversionCurrency()
    {
        $_paypal_conversion_currency = Cache::read('site_paypal_conversion_currency');
        $_paypal_conversion_currency['supported_currency'] = Configure::read('paypal.is_supported');
        $_paypal_conversion_currency['conv_currency_code'] = Configure::read('paypal.conversion_currency_code');
        $_paypal_conversion_currency['currency_code'] = Configure::read('paypal.currency_code');
        $_paypal_conversion_currency['conv_currency_symbol'] = Configure::read('paypal.conversion_currency_symbol');
        return $_paypal_conversion_currency;
    }
    function time_left($integer)
    {
        $minutes = 0;
        $return = '';
        $seconds = $integer;
        if ($seconds/60 >= 1) {
            $minutes = floor($seconds/60);
            if ($minutes/60 >= 1) { // Hours
                $hours = floor($minutes/60);
                if ($hours/24 >= 1) { //days
                    $days = floor($hours/24);
                    $return = '';
                    if ($days >= 2) $return = "$return $days days";
                    if ($days == 1) $return = "$return $days day";
                } //end of days
                $hours = $hours-(floor($hours/24)) *24;
                if ($days >= 1 && $hours >= 1) $return = "$return ";
                if ($hours >= 2) $return = "$return $hours hours";
                if ($hours == 1) $return = "$return $hours hour";
            } //end of Hours
            $minutes = $minutes-(floor($minutes/60)) *60;
            if ($hours >= 1 && $minutes >= 1) $return = "$return ";
            if ($minutes >= 2) $return = "$return $minutes minutes";
            if ($minutes == 1) $return = "$return $minutes minute";
        } //end of minutes
        $seconds = $integer-(floor($integer/60)) *60;
        if ($minutes >= 1 && $seconds >= 1) $return = "$return ";
        if ($seconds >= 2) $return = "$return $seconds seconds";
        if ($seconds == 1) $return = "$return $seconds second";
        $return = "$return";
        return $return;
    }
    function getReferredUsername($user_id)
    {
        App::import('Model', 'User');
        $this->User = new User();
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id,
            ) ,
            'fields' => array(
                'User.username',
            ) ,
            'recursive' => -1
        ));
        return $user['User']['username'];
    }
}
?>
