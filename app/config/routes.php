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
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7820 $
 * @modifiedby    $LastChangedBy: renan.saddam $
 * @lastmodified  $Date: 2008-11-03 23:57:56 +0530 (Mon, 03 Nov 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
Router::parseExtensions('rss', 'csv', 'json', 'txt', 'pdf', 'kml', 'xml', 'mobile', 'js');
// REST support controllers
Router::mapResources(array(
    'deals'
));
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
$site_name = Cache::read('site.name', 'long') ;
if (Cache::read('site.city_url', 'long') == 'prefix') {
    Router::connect('/img/:size/*', array(
        'controller' => 'images',
        'action' => 'view'
    ) , array(
        'size' => '(?:[a-zA-Z_]*)*'
    ));
    Router::connect('/img/*', array(
        'controller' => 'images',
        'action' => 'view',
        'size' => 'original'
    ));
    Router::connect('/js/*', array(
        'controller' => 'devs',
        'action' => 'asset_js'
    ));
    Router::connect('/css/*', array(
        'controller' => 'devs',
        'action' => 'asset_css'
    ));
    $controllers = Cache::read('controllers_list', 'default');
    if ($controllers === false) {
        $controllers = App::objects('controller');
        foreach($controllers as &$value) {
            $value = Inflector::underscore($value);
        }
        array_push($controllers, 'company', 'deal', 'page', 'user', 'admin', 'deal_user', 'contactus', 'sitemap', 'robots', 'sitemap.xml', 'robots.txt','welcome_to_'.$site_name);
        $controllers = implode('|', $controllers);
        Cache::write('controllers_list', $controllers);
    }
	if (stripos(getenv('HTTP_HOST') , 'touch.') !== false) {
		Router::connect('/', array(
			'controller' => 'pages',
			'action' => 'display',
			'main-menu'
		) , array(
			'city' => '(?!' . $controllers . ')[^\/]*'
		));
	} else{
		Router::connect('/', array(
			'controller' => 'deals',
			'action' => 'index'
		) , array(
			'city' => '(?!' . $controllers . ')[^\/]*'
		));
	}
	Router::connect('/live', array(
		'controller' => 'deals',
		'action' => 'live',
	) , array(
		'city' => '(?!' . $controllers . ')[^\/]*'
	));	
	Router::connect('/:city/live', array(
		'controller' => 'deals',
		'action' => 'live',
	) , array(
		'city' => '(?!' . $controllers . ')[^\/]*'
	));
	Router::connect('/:city', array(
		'controller' => 'deals',
		'action' => 'index'
	) , array(
		'city' => '(?!' . $controllers . ')[^\/]*'
	));
    Router::connect('/:city/users/facebook/login', array(
			'controller' => 'users',
			'action' => 'login',
			'type' => 'facebook'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/users/twitter/login', array(
        'controller' => 'users',
        'action' => 'login',
        'type' => 'twitter'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/users/yahoo/login', array(
		'controller' => 'users',
		'action' => 'login',
		'type' => 'yahoo'
	), array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/users/gmail/login', array(
		'controller' => 'users',
		'action' => 'login',
		'type' => 'gmail'
	), array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/users/openid/login', array(
		'controller' => 'users',
		'action' => 'login',
		'type' => 'openid'
	), array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/users/foursquare/login', array(
		'controller' => 'users',
		'action' => 'login',
		'type' => 'foursquare'
	), array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));

    Router::connect('/:city/company/facebook/login', array(
			'controller' => 'users',
			'action' => 'login',
			'type' => 'facebook',
			'user_type' => 'company'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/company/twitter/login', array(
        'controller' => 'users',
        'action' => 'login',
        'type' => 'twitter',
        'user_type' => 'company'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/company/yahoo/login', array(
		'controller' => 'users',
		'action' => 'login',
		'type' => 'yahoo',
		'user_type' => 'company'
	), array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/company/gmail/login', array(
		'controller' => 'users',
		'action' => 'login',
		'type' => 'gmail',
		'user_type' => 'company'
	), array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/company/openid/login', array(
		'controller' => 'users',
		'action' => 'login',
		'type' => 'openid',
		'user_type' => 'company'
	), array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/company/foursquare/login', array(
		'controller' => 'users',
		'action' => 'login',
		'type' => 'foursquare',
		'user_type' => 'company'
	), array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));

    Router::connect('/:city/pages/*', array(
        'controller' => 'pages',
        'action' => 'display'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/pages/*', array(
        'controller' => 'pages',
        'action' => 'display'
    ));

    Router::connect('/:city/company/user/register/*', array(
        'controller' => 'users',
        'action' => 'company_register'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/contactus', array(
        'controller' => 'contacts',
        'action' => 'add'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
	Router::connect('/cron/update_deal', array(
        'controller' => 'crons',
        'action' => 'update_deal'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/deals/recent', array(
        'controller' => 'deals',
        'action' => 'index',
        'type' => 'recent'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
	Router::connect('/:city/deals/near', array(
        'controller' => 'deals',
        'action' => 'index',
        'type' => 'near'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
	Router::connect('/:city/deals/main', array(
        'controller' => 'deals',
        'action' => 'index',
        'type' => 'main'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
	Router::connect('/:city/deals/side', array(
        'controller' => 'deals',
        'action' => 'index',
        'type' => 'side'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/deals/company/:company', array(
        'controller' => 'deals',
        'action' => 'index',
    ) , array(
        'company' => '[^\/]*',
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/deals/company/:company/:view', array(
        'controller' => 'deals',
        'action' => 'index',
    ) , array(
        'company' => '[^\/]*',
		'city' => '(?!' . $controllers . ')[^\/]*',
		'view' => 'live',
		
    ));
	Router::connect('/:city/deals/company/:company/:type', array(
        'controller' => 'deals',
        'action' => 'index',
    ) , array(
        'company' => '[^\/]*',
		'type' => '[^\/]*',
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
	Router::connect('/:city/deals/company/:company/:type/:view', array(
        'controller' => 'deals',
        'action' => 'index',
    ) , array(
        'company' => '[^\/]*',
		'type' => '[^\/]*',
		'view' => 'live',
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/city_suggestions/new', array(
        'controller' => 'city_suggestions',
        'action' => 'add',
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/subscribe', array(
        'controller' => 'subscriptions',
        'action' => 'add',
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/admin' , array(
        'controller' => 'users',
        'action' => 'stats',
        'prefix' => 'admin',
        'admin' => true
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/robots', array(
        'controller' => 'devs',
        'action' => 'robots',
        'ext' => 'txt'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/sitemap', array(
        'controller' => 'devs',
        'action' => 'sitemap',
        'ext' => 'xml'
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/files/*', array(
        'controller' => 'images',
        'action' => 'view',
        'size' => 'original'
    ));
    Router::connect('/admin/:controller/:action/*', array(
        'admin' => true
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/admin/:controller/:action/*', array(
        'admin' => true
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/admin/:controller/*', array(
		'action' => 'index',
        'admin' => true
    ) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/:controller/:action/*', array() , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
    Router::connect('/:city/:controller/*', array(
		'action' => 'index'
	) , array(
        'city' => '(?!' . $controllers . ')[^\/]*'
    ));
	Router::connect('/welcome_to_'.$site_name, array(
        'controller' => 'deals',
        'action' => 'index',
		'type' => 'geocity'
    ));
}
if (Cache::read('site.city_url', 'long') == 'subdomain') {
    Router::connect('/img/:size/*', array(
        'controller' => 'images',
        'action' => 'view'
    ) , array(
        'size' => '(?:[a-zA-Z_]*)*'
    ));
    Router::connect('/img/*', array(
        'controller' => 'images',
        'action' => 'view',
        'size' => 'original'
    ));
    Router::connect('/js/*', array(
        'controller' => 'devs',
        'action' => 'asset_js'
    ));
    Router::connect('/css/*', array(
        'controller' => 'devs',
        'action' => 'asset_css'
    ));
    Router::connect('/city::city', array(
        'controller' => 'deals',
        'action' => 'index'
    ) , array(
        'city' => '[^\/]*'
    ));
    Router::connect('/contactus/city::city', array(
        'controller' => 'contacts',
        'action' => 'add'
    ) , array(
        'city' => '[^\/]*'
    ));
    Router::connect('/pages/*', array(
        'controller' => 'pages',
        'action' => 'display'
    ));
	
    Router::connect('/company/user/register/city::city/*', array(
        'controller' => 'users',
        'action' => 'company_register'
    ) , array(
        'city' => '[^\/]*'
    ));
    Router::connect('/admin/city::city', array(
        'controller' => 'users',
        'action' => 'stats',
        'prefix' => 'admin' ,
        'admin' => 1
    ) , array(
        'city' => '[^\/]*'
    ));
    Router::connect('/robots', array(
        'controller' => 'devs',
        'action' => 'robots',
        'ext' => 'txt'
    ) , array(
        'city' => '[^\/]*'
    ));
    Router::connect('/deals/recent/city::city', array(
        'controller' => 'deals',
        'action' => 'index',
        'type' => 'recent'
    ) , array(
        'city' => '[^\/]*'
    ));
    Router::connect('/deals/company/:company/city::city', array(
        'controller' => 'deals',
        'action' => 'index',
    ) , array(
        'company' => '[^\/]*',
        'city' => '[^\/]*'
    ));
    Router::connect('/city_suggestions/new/city::city', array(
        'controller' => 'city_suggestions',
        'action' => 'add',
    ) , array(
        'city' => '[^\/]*'
    ));
    Router::connect('/subscribe/city::city', array(
        'controller' => 'subscriptions',
        'action' => 'add',
    ) , array(
        'city' => '[^\/]*'
    ));
    Router::connect('/subscribe', array(
        'controller' => 'subscriptions',
        'action' => 'add',
    ) , array(
        'city' => '[^\/]*'
    ));
    Router::connect('/files/*', array(
        'controller' => 'images',
        'action' => 'view',
        'size' => 'original'
    ));
    Router::connect('/users/twitter/login/city::city', array(
        'controller' => 'users',
        'action' => 'login',
        'type' => 'twitter'
    ) , array(
        'city' => '[^\/]*'
    ));
    Router::connect('/sitemap/city::city', array(
        'controller' => 'devs',
        'action' => 'sitemap'
    ) , array(
        'city' => '[^\/]*'
    ));  
	Router::connect('/welcome_to_'.$site_name, array(
        'controller' => 'deals',
        'action' => 'index',
		'type' => 'geocity'
    ));
}
?>