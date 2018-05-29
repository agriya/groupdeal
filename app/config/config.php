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
 * Custom configurations
 */
// site actions that needs random attack protection...
if (!defined('DEBUG')) {
	define('DEBUG', 0);
	// permanent cache re1ated settings
	define('PERMANENT_CACHE_CHECK',  (!empty($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] != '127.0.0.1') ? true : true);
	// site default language
	define('PERMANENT_CACHE_DEFAULT_LANGUAGE', 'en');
	// cookie variable name for site language
	define('PERMANENT_CACHE_COOKIE', 'user_language');
	// sub admin is available in site or not
	define('PERMANENT_CACHE_HAVE_SUB_ADMIN', false);
	define('IS_ENABLE_HASHBANG_URL', false);
	define('IS_ENABLE_HTML5_HISTORY_API', false);
	$_is_hashbang_supported_bot = (strpos($_SERVER['HTTP_USER_AGENT'], 'Googlebot') !== false);
	define('IS_HASHBANG_SUPPORTED_BOT', $_is_hashbang_supported_bot);
}
if (!defined('PERMANENT_CACHE_GZIP_SALT')) {
	define('PERMANENT_CACHE_GZIP_SALT', "e9a556134534545ab47c6c81c14f06c0b8sdfsdf");	
}
$config['site']['license_key'] = 'enter your license key';
$config['site']['_hashSecuredActions'] = array(
    'edit',
    'delete',
    'update',
    'unsubscribe',
    'barcode',
    'update_status',
    'resend',
    'my_account',
    'view_gift_card',
	'live_edit',
	'subdeal_edit',
	'subdeal_delete',
	'profile_image',
);
$config['site']['domain'] = 'grouponpro';
$config['photo']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
		'image/jpg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
	'allowEmpty' => true
);
$config['image']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => false
);
$config['avatar']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => true
);
$config['pagelogo']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => true
);
$config['sitemap']['models'] = array(
    'Deal' => array(
        'conditions' => array(
            'deal_status_id' => array(
				2,
				5
			),
        ),
		'contain' => array(
			'CitiesDeal' => array(
				'City' => array(
					'fields' => array(
						'City.id',
						'City.name',
						'City.slug',
					)
				)
			)
		),
		'fields'=>array(
			'slug',
			'id',
		)
    ),
);

$config['widget_no_scroll'] = array(1, 2, 3, 4);
$config['site']['search_distance'] = 30000;

$config['site']['is_admin_settings_enabled'] = true;
if ($_SERVER['HTTP_HOST'] == 'groupdeal.dev.agriya.com' && !in_array($_SERVER['REMOTE_ADDR'], array('118.102.143.2', '119.82.115.146', '122.183.135.202', '122.183.136.34','122.183.136.36'))) {
	$config['site']['is_admin_settings_enabled'] = false;
	$config['site']['admin_demomode_updation_not_allowed_array'] = array(
		'cities/admin_delete',
		'cities/admin_update',
		'cities/admin_edit',
		'cities/admin_update_status',
		'countries/admin_update',
		'countries/admin_delete',
		'countries/admin_edit',
		'countries/admin_update_status',
		'states/admin_update',
		'states/admin_delete',
		'states/admin_edit',
		'states/admin_update_status',
		'pages/admin_edit',
		'pages/admin_delete',
		'subscriptions/admin_subscription_customise',
		'user_profiles/admin_edit',
	);
}
$config['action']['cache_duration'] = 86400;
 $config['site']['exception_array'] = array(
            'cities/check_city',
            'countries/index',
            'countries/change_country',
            'pages/view',
            'pages/display',
            'pages/home',
            'deals/index',
            'deals/view',
            'users/processpayment',
            'gift_users/processpayment',
            'subscriptions/add',
            'cities/index',
            'companies/view',
            'contacts/show_captcha',
            'users/register',
            'users/company_register',
            'users/login',
            'users/logout',
            'users/reset',
            'users/forgot_password',
            'users/openid',
            'users/activation',
            'users/resend_activation',
            'users/view',
            'users/show_captcha',
            'users/oauth_callback',
            'users/captcha_play',
            'users/oauth_facebook',
            'users/fs_oauth_callback',
            'images/view',
            'devs/robots',
            'contacts/add',
            'contacts/show_captcha',
            'contacts/captcha_play',
            'images/view',
            'cities/autocomplete',
            'states/autocomplete',
            'users/admin_login',
            'users/admin_logout',
            'languages/change_language',
            'subscriptions/add',
            'subscriptions/index',
            'subscriptions/unsubscribe',
            'users/referred_users',
            'users/resend_activemail',
            'subscriptions/home',
            'subscriptions/unsubscribe_mailchimp',
            'subscriptions/city_suggestions',
            'subscriptions/skip',
            'subscriptions/sync_mc',
            'pages/refer_a_friends',
            'users/refer',
            'mass_pay_paypals/process_masspay_ipn',
            'deals/barcode',
            'city_suggestions/add',
            'cities/twitter_facebook',
            'user_comments/index',
            'deals/buy',
            'deals/process_user',
            'deals/processpayment',
            'deals/_buyDeal',
            'deals/payment_success',
            'deals/payment_cancel',
            'companies/view',
            'page/learn',
            'deals/company_deals',
            'deals/live',
            'gift_users/view_gift_card',
            'devs/sitemap',
            'devs/robotos',
            'business_suggestions/add',
            'crons/update_deal',
            'users/validate_user',
            'affiliates/widget',
            'deals/widget',
            'devs/asset_css',
            'devs/asset_js',
            'cities/get_city',
			'deal_categories/index',
			'cities/live_index',
			'deal_categories/live_index',
			'deals/live_time'
        );
?>
