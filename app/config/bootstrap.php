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
 * Long description for file
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
 * @since         CakePHP(tm) v 0.10.8.2117
 * @version       $Revision: 7805 $
 * @modifiedby    $LastChangedBy: AD7six $
 * @lastmodified  $Date: 2008-10-30 23:00:26 +0530 (Thu, 30 Oct 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */
App::import('Core', 'config/PhpReader');
Configure::config('default', new PhpReader());
Configure::load('config');
define('DEFAULT_LANGUAGE', 'en');
$user_preferred_city = '';
// Chekcing whether user ahev alreday visited our site
$user_preferred_city = (!empty($_COOKIE['CakeCookie']['city_slug'])) ? $_COOKIE['CakeCookie']['city_slug'] : Cache::read('site.default_city', 'long');
// For Subdoamin concept
if (Cache::read('site.city_url', 'long') == 'subdomain') {
    $tmp_city = substr(env('HTTP_HOST') , 0, strpos(env('HTTP_HOST') , '.'));
    if (!isset($_GET['url'])) {
        $_GET['url'] = 'deals/index';
    }
    if (strlen($tmp_city) > 0) {
        $site_domain = substr(env('HTTP_HOST') , strpos(env('HTTP_HOST') , '.'));
        ini_set('session.cookie_domain', $site_domain);
        if ($tmp_city == 'www' or $tmp_city == 'm' or $tmp_city == Configure::read('site.domain')) {
            $_GET['url'].= !empty($user_preferred_city) ? '/city:' . $user_preferred_city : '';
        } else {
            $_GET['url'].= '/city:' . $tmp_city;
        }
    } else {
        $_GET['url'].= !empty($user_preferred_city) ? '/city:' . $user_preferred_city : '';
    }
}
if (Cache::read('site.city_url', 'long') == 'prefix') {
    if (!function_exists('router_url_city')) {
        function router_url_city($url, $named = null) 
        {
            $user_preferred_city = (!empty($_COOKIE['CakeCookie']['city_slug'])) ? $_COOKIE['CakeCookie']['city_slug'] : Cache::read('site.default_city', 'long');
            if ($city_url = (!empty($url['city']) ? $url['city'] : (!empty($named['city']) ? $named['city'] : $user_preferred_city))) {
                if ($city_url == '/') $city_url = $user_preferred_city;
                if (is_array($url)) {
                    $url['city'] = htmlentities($city_url, ENT_QUOTES);
                } else if ($url == '/') {
                    $url = array(
                        'city' => htmlentities($city_url, ENT_QUOTES)
                    );
                }
            }
            return $url;
        }
    }
	$GLOBALS['_city']['icm'] = 0;
    if (!isset($_GET['url'])) {
        if (stripos(getenv('HTTP_HOST') , 'touch.') === false) {
			if (!empty($user_preferred_city)) {
				$_GET['url'] = $user_preferred_city;
				$GLOBALS['_city']['icm'] = 1;
			}
		}
    } else {
        $controllers = Cache::read('controllers_list', 'default');
        $controller_arr = explode('|', $controllers);
        // hardcoded for view pages
        array_push($controller_arr, 'company', 'deal', 'page', 'user', 'admin', 'deal_user', 'contactus', 'sitemap', 'robots', 'sitemap.xml', 'robots.txt');
        $url_arr = explode('/', $_GET['url']);
		$check_welcome_page = preg_match('/welcome_to_/', $_GET['url']);
        if (in_array($url_arr[0], $controller_arr) && empty($check_welcome_page)) {
            // quick fix. need to discuss.
            if (preg_match('/city:([^\/]*)(\/)*/', $_GET['url'], $matches)) {
                $current_tmp_city = $matches[1];
            }
            $tmp_url = $_GET['url'];
            unset($_GET['url']);
            if (!empty($current_tmp_city)) {
                $_GET['url'] = $current_tmp_city . '/' . $tmp_url;
            } else if (!empty($user_preferred_city)) {
                $_GET['url'] = $user_preferred_city . '/' . $tmp_url;
				$GLOBALS['_city']['icm'] = 1;
            }
        }
    }
}
require 'constants.php';
?>
