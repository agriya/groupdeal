<?php
/**
 * Index
 *
 * The Front Controller for handling every request
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.webroot
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
	/**
	 * Use the DS to separate the directories in other defines
	 */
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}
	require '..' . DS . 'config' . DS . 'config.php';
	if (IS_ENABLE_HTML5_HISTORY_API) {
		if (!empty($_GET['_escaped_fragment_'])) {
			$_GET['url'] = $_GET['_escaped_fragment_'];
			if (sizeof($script_name_arr = explode('/', $_SERVER['SCRIPT_NAME'])) > 2) {
				$_GET['url'] = str_replace('/' . $script_name_arr[1], '', $_GET['url']);
			}
			unset($_GET['_escaped_fragment_']);
		}
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_PJAX'])) {
			$_SERVER['HTTP_X_REQUESTED_WITH'] = 'hashbang';
			$_ENV['HTTP_X_REQUESTED_WITH'] = '';
			putenv('HTTP_X_REQUESTED_WITH=');
		}
	}	
	if (!in_array($_SERVER['REQUEST_METHOD'], array('POST', 'PUT', 'DELETE')) && permanentCached()) {		
		return;
	} else {
		/**
		 * These defines should only be edited if you have cake installed in
		 * a directory layout other than the way it is distributed.
		 * When using custom settings be sure to use the DS and do not add a trailing DS.
		 */

		/**
		 * The full path to the directory which holds "app", WITHOUT a trailing DS.
		 *
		 */
		if (!defined('ROOT')) {
			define('ROOT', dirname(dirname(dirname(__FILE__))));
		}
		/**
		 * The actual directory name for the "app".
		 *
		 */
		if (!defined('APP_DIR')) {
			define('APP_DIR', basename(dirname(dirname(__FILE__))));
		}
		/**
		 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
		 *
		 */
		if (!defined('CAKE_CORE_INCLUDE_PATH')) {
			define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'core');
		}

		/**
		 * Editing below this line should NOT be necessary.
		 * Change at your own risk.
		 *
		 */
		if (!defined('WEBROOT_DIR')) {
			define('WEBROOT_DIR', basename(dirname(__FILE__)));
		}
		if (!defined('WWW_ROOT')) {
			define('WWW_ROOT', dirname(__FILE__) . DS);
		}
		if (!defined('CORE_PATH')) {
			define('APP_PATH', ROOT . DS . APP_DIR . DS);
			define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
		}
		//Fix to upload the file through the flash multiple uploader
		if ((isset($_SERVER['HTTP_USER_AGENT']) && ((strtolower($_SERVER['HTTP_USER_AGENT']) == 'shockwave flash') || (strpos(strtolower($_SERVER['HTTP_USER_AGENT']) , 'adobe flash player') !== false))) && strpos($_GET['url'], 'flashupload') !== false) {
			$url_arr = explode('/', $_GET['url']);
			session_name('PHPSESSID');
			session_id($url_arr[2]);
			//@session_start();
		}
		if (!include(CORE_PATH . 'cake' . DS . 'bootstrap.php')) {
			trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
		}
		if (isset($_GET['url']) && $_GET['url'] === 'favicon.ico') {
			return;
		} else {
			require LIBS . 'dispatcher.php';
			$Dispatcher = new Dispatcher();
			$Dispatcher->dispatch(new CakeRequest(isset($_GET['url']) ? $_GET['url'] : null));
		}
	}

/**
 * Outputs cached dispatch view cache
 */
function permanentCached($requested_url = null) {
	$start = microtime(true);
	$debug_mode = DEBUG;
	if (empty($debug_mode) && PERMANENT_CACHE_CHECK && empty($_COOKIE['_flash']) && !in_array($_SERVER['REQUEST_METHOD'], array('POST', 'PUT', 'DELETE')) && !empty($_SERVER['HTTP_ACCEPT_ENCODING']) && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
		$cache = '_';
		if (!empty($_COOKIE['CakeCookie']['city_slug']) && !empty($_GET['url']) && (strpos($_GET['url'],$_COOKIE['CakeCookie']['city_slug']) != 0)) {
			$cache .= $_COOKIE['CakeCookie']['city_slug'].'_';
		}
		if (!empty($requested_url)) {
			$cache .= $requested_url;
		} else {
			$cache .= !empty($_GET['url']) ? $_GET['url'] : '';
		}
		if (strpos($cache, '.') !== false) {
			return false;
		}
		$url_replace_array = array(
			' ',
			'/',
			':',
			'?',
			'&',
			'$',
		);
		$cache = str_replace($url_replace_array, '_', $cache);
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
			$requested = 1;
		}
		if (!empty($_COOKIE['_gz'])) {
			$_gzip = explode('|', $_COOKIE['_gz']);
			if (count($_gzip) == 2) {
				$hashed_val = md5($_gzip[0] . $_COOKIE['PHPSESSID'] . PERMANENT_CACHE_GZIP_SALT);
				if (substr($hashed_val, 0, 7) == $_gzip[1]) {
					$gzip_user_id = $_gzip[0];
				}
			}
		}
		if (!empty($gzip_user_id)) {
			$cache_folder = 'user' . DS . str_replace('.', '_', $_SERVER['HTTP_HOST']) . DS . $gzip_user_id;
		} else {
			$cache_folder = 'public' . DS . str_replace('.', '_', $_SERVER['HTTP_HOST']);
		}
		if (!empty($requested)) {
			$cache .= '_requested';
		}
		if (PERMANENT_CACHE_COOKIE && !empty($_COOKIE['CakeCookie'][PERMANENT_CACHE_COOKIE])) {
			$cache .= '_' . $_COOKIE['CakeCookie'][PERMANENT_CACHE_COOKIE];
		} else {
			$cache .= '_' . PERMANENT_CACHE_DEFAULT_LANGUAGE;
		}
		if (!empty($_COOKIE['CakeCookie']['user_currency'])) {
			$cache .= '_' . $_COOKIE['CakeCookie']['user_currency'];
		}
		$filename = '..' . DS . 'tmp' . DS . 'cache' . DS . 'views' . DS . $cache_folder . DS . $cache . '.gz';	
		if(strstr($filename,"charts_chart")){
		  if (file_exists($filename)) {
			  cache_clear($filename);
		  }
		}
		if (file_exists($filename)) {
			if (!empty($requested) && !in_array('Content-Encoding: gzip', headers_list())) {
				return false;
			}
			if (empty($_GET['url'])) {
				header('X-XRDS-Location: http://' . $_SERVER['HTTP_HOST'] . '/yadis.xml');
			}
			header('Content-Encoding: gzip');
			header('Vary:Accept-Encoding');
			$end = microtime(true);
			header('X-cache: hit at ' . round(($end - $start) * 1000, 3) . 'ms');
			ob_clean();
			flush();
			if (session_id()) {
				session_write_close(); // close locking sessions
			}
			readfile_chunked($filename);
			if (file_exists($filename . '.updateviews')) {
				$tmp_arr = explode('_', file_get_contents($filename . '.updateviews'));
				updateViews($tmp_arr[0], $tmp_arr[1]);
			}
			exit;
		}
	}
	return false;
}
function readfile_chunked($filename, $retbytes = true) {
   $chunksize = 1*(1024*1024); // how many bytes per chunk
   $buffer = '';
   $cnt = 0;
   // $handle = fopen($filename, 'rb');
   $handle = fopen($filename, 'rb');
   if ($handle === false) {
       return false;
   }
   while (!feof($handle)) {
       $buffer = fread($handle, $chunksize);
       echo $buffer;
       ob_flush(); 
       flush(); 
       if ($retbytes) {
           $cnt += strlen($buffer);
       }
   }
   $status = fclose($handle);
   if ($retbytes && $status) {
       return $cnt; // return num. bytes delivered like readfile() does.
   }
   return $status;
}
function cache_clear($filename = null)
{
			if (file_exists($filename)) {
				$files = file($filename);
				if (!empty($files)) {
					$files = array_map('trim', $files);
					@array_map('unlink', $files);
				}
				unlink($filename);
			}
}
function updateViews($table, $main_id) {
	require '..' . DS . '..' . DS . 'core' . DS . 'cake' . DS . 'libs' . DS . 'inflector.php';
	require '..' . DS . 'config' . DS . 'database.php';
	$database = new DATABASE_CONFIG();
	$db = mysql_connect($database->default['host'], $database->default['login'], $database->default['password']) or die ('Error connecting to mysql');
	mysql_select_db($database->default['database']);
	mysql_set_charset('utf8', $db);
	$main_table_name = Inflector::tableize($table);
	$main_field_name = Inflector::singularize($main_table_name);
	$main_result = mysql_query('SELECT * FROM ' . $main_table_name . ' WHERE id = ' . $main_id);
	$main_row = mysql_fetch_assoc($main_result);
	$ip_result = mysql_query('SELECT id FROM `ips` WHERE ip = "' . $_SERVER['REMOTE_ADDR'] . '"');
	if (mysql_num_rows($ip_result)) {
		$ip_row = mysql_fetch_assoc($ip_result);
		$ip_id = $ip_row['id'];
	} else {
		if (!empty($_COOKIE['_geo'])) {
			$_geo = explode('|', $_COOKIE['_geo']);
			$country_result = mysql_query('SELECT id FROM `countries` WHERE iso2 = "' . $_geo[0] . '"');
			if (mysql_num_rows($country_result)) {
				$country_row = mysql_fetch_assoc($country_result);
				$country_id = $country_row['id'];
			}
			$state_result = mysql_query('SELECT id FROM `states` WHERE name = "' . $_geo[1] . '"');
			if (mysql_num_rows($state_result)) {
				$state_row = mysql_fetch_assoc($state_result);
				$state_id = $state_row['id'];
			}
			$city_result = mysql_query('SELECT id FROM `cities` WHERE name = "' . $_geo[2] . '"');
			if (mysql_num_rows($city_result)) {
				$city_row = mysql_fetch_assoc($city_result);
				$city_id = $city_row['id'];
			}
		}
		mysql_query('INSERT INTO `ips` (`created`, `modified`, `ip`, `host`, `city_id`, `state_id`, `country_id`, `latitude`, `longitude`) VALUES (now(), now(), "' . $_SERVER['REMOTE_ADDR'] . '", "' . gethostbyaddr($_SERVER['REMOTE_ADDR']) . '", ' . $city_id . ', ' . $state_id . ', ' . $country_id . ', "' . $_geo[3] . '", "' . $_geo[4] . '")');
		$ip_id = mysql_insert_id();
	}
	$view_table_name = Inflector::tableize($table . 'View');
	$user_id = !empty($gzip_user_id) ? $gzip_user_id : 0;
	mysql_query('INSERT INTO `' . $view_table_name . '` (`created`, `modified`, `user_id`, `' . $main_field_name . '_id`, `ip_id`) VALUES (now(), now(), ' . $user_id . ', ' . $main_id . ', ' . $ip_id . ')');
	$view_result = mysql_query('SELECT COUNT(*) as count FROM ' . $view_table_name . ' WHERE ' . $main_field_name . '_id = ' . $main_id);
	$view_row = mysql_fetch_assoc($view_result);
	mysql_query('UPDATE `' . $main_table_name . '` SET ' . $main_field_name . '_view_count = "' . $view_row['count'] . '" WHERE id = ' . $main_id);
}