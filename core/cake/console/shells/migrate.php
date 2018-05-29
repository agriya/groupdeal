<?php
/* SVN FILE: $Id: migrate.php 182 2009-02-10 14:04:41Z siva_063at09 $ */
/**
 * Task class for migrating cakePHP version 1.2 to 2.0
 */
class MigrateShell extends Shell
{
    /**
     * initialization callback
     *
     * @var string
     * @access public
     */
    function initialize()
    {
		include_once('beautify.php');
		$this->sBeautifier = new BeautifyShell();
        $this->_welcome();
        $this->out('Migrate all files in app folder');
        $this->hr();
    }
    /**
     * Override main
     *
     * @access public
     */
    function main()
    {
		$this->__migrate_recursive(APP);
        $this->out('done');
    }
    function __migrate_recursive($dir)
    {
        $handle = opendir($dir);
        while (false !== ($readdir = readdir($handle))) {
            if ($readdir != '.' && $readdir != '..' && $readdir != '.svn') {
                $path = $dir . DS . $readdir;
                if (is_dir($path)) {
                    $this->__migrate_recursive($path);
                }
				if (is_file($path) && ((strpos($path, '.php') !== false) || (strpos($path, '.ctp') !== false))) {
					$contents = $contents_migrated = file_get_contents($path);
					if (is_file($path) && (strpos($path, 'app_controller') !== false)) {
						$replace_array = array(
							'function __construct()' => 'function __construct($request = null)',
							'parent::__construct();' => 'parent::__construct($request);',
						);
						$is_grab = 0;
						$is_grab_2 = 0;
						$is_grab_3 = 0;
						$counter = 0;
						$make_js_vars = '';
						$form_case_array = array();
						$form_case_array_2 = array();
						$form_case_array_3 = array();
						$get_tokens = $this->tokenaize($contents_migrated);
						if(!empty($get_tokens)){
							foreach($get_tokens as $c){
								if(is_array($c)) {
									if($c[0] == T_VARIABLE && $c[1] == "\$components"){
										$is_grab = 1;
									}
									if($c[0] == T_VARIABLE && $c[1] == "\$helpers"){
										$is_grab_2 = 1;
									}
									if($c[0] == T_STRING && $c[1] == "js_vars"){
										$is_grab_3 = 1;									
									}
									if(!empty($is_grab)){
										$c[1] = trim($c[1]);
										if(!empty($c[1])){
											$form_case_array[] = $c[1];
										}
									}
									if(!empty($is_grab_2)){
										$c[1] = trim($c[1]);
										if(!empty($c[1])){
											$form_case_array_2[] = $c[1];
										}
									}
									if(!empty($is_grab_3)){
										$c[1] = trim($c[1]);
										if(!empty($c[1])){
											$form_case_array_3[] = $c[1];
										}
									}
								} 
							}
							foreach($form_case_array_3 as $form_case_arr){
								$make_js_vars = $make_js_vars.''.$form_case_arr;
							}
							$op = strpos($make_js_vars, "js_vars=array();");
							if($op === false){
								$replace_array['$this->js_vars[\'cfg\'][\'path_relative\']'] = '$this->js_vars = array();
		$this->js_vars[\'cfg\'][\'path_relative\']';
							}
							if(!in_array("'Session'", $form_case_array)){
								$replace_array['\'RequestHandler\''] ='\'RequestHandler\',\'Session\'';
							}
							if(!in_array("'Session'", $form_case_array_2)){
								$replace_array['\'Html\''] ='\'Html\',\'Session\'';
							}
						}
						foreach($replace_array as $key => $value){
							$contents_migrated = str_replace($key, $value, $contents_migrated, $count);
							$display[$key] = $count;
						}
					}
					if (is_file($path) && (strpos($path, 'bootstrap.php') !== false)) {
						$is_grab = 0;
						$counter = 0;
						$form_case_array = array();
						$get_tokens = $this->tokenaize($contents_migrated);
						if(!empty($get_tokens)){
							foreach($get_tokens as $c){
								if(is_array($c)) {
									if($c[0] == T_STRING && $c[1] == "Configure"){
										$is_grab = 1;
									}
									if(!empty($is_grab)){
										$c[1] = trim($c[1]);
										if(!empty($c[1])){
											$form_case_array[] = $c[1];
										}
									}
								} 
							}
							if(!in_array("PhpReader", $form_case_array)){
								$replace_array['Configure::load(\'config\');'] = "App::import('Core', 'config/PhpReader');
Configure::config('default', new PhpReader());
Configure::load('config');";
							}
							if(!empty($replace_array)){
								foreach($replace_array as $key => $value){
									$contents_migrated = str_replace($key, $value, $contents_migrated, $count);
									$display[$key] = $count;
								}
							}
						}
					}
					if (is_file($path) && (strpos($path, '.ctp') !== false) && (strpos($path, 'paging_links') !== false)) {
						$replace_array = array(
							'\'skip\' => \'<span class="skip">&hellip;.</span>\'' => '\'first\' => 3,
	\'last\' => 3,
	\'ellipsis\' => \'<span class="ellipsis">&hellip;.</span>\'',
						);
						foreach($replace_array as $key => $value){
							$contents_migrated = str_replace($key, $value, $contents_migrated, $count);
							$display[$key] = $count;
						}
					}
					if (is_file($path) && (strpos($path, '.ctp') !== false) && (strpos($path, '_head') !== false)) {
						$replace_array = array(
							', null, null, false' => ', null, array(\'inline\' => false)',
							'if (isset($javascript)):' => '',
							'endif;' => '',
						);
						foreach($replace_array as $key => $value){
							$contents_migrated = str_replace($key, $value, $contents_migrated, $count);
							$display[$key] = $count;
						}
					}
					if (is_file($path) && (strpos($path, '.ctp') !== false) && (strpos($path, 'views') !== false)) {
						$replace_array = array(
							'$html->' => '$this->Html->',
							'$javascript->' => '$this->Javascript->',
							'$auth->' => '$this->Auth->',
							'$text->' => '$this->Text->',
							'$asset->' => '$this->Asset->',
							'$session->' => '$this->Session->',
							'$paginator->' => '$this->Paginator->',
							'$form->' => '$this->Form->',
							'$tree->' => '$this->Tree->',
							'$time->' => '$this->Time->',
							'$rss->' => '$this->Rss->',
							'$xml->' => '$this->Xml->',
							'$csv->' => '$this->Csv->',
							'<?php echo $cakeDebug?>' => '<?php echo $this->element(\'sql_dump\'); ?>',
							'$this->params[\'url\'][\'url\']' => '$this->request->url',
							'$this->data' => '$this->request->data',
							'$this->params' => '$this->request->params',
							', null, null, false' => ', null, array(\'inline\' => false)',
							'<?php echo $cakeDebug?>' => '<?php echo $this->element(\'sql_dump\'); ?>',
						);
						$is_grab = 0;
						$counter = 0;
						$form_case_array = array();
						$get_tokens = $this->tokenaize($contents_migrated);
						if (!empty($get_tokens)) {
							foreach($get_tokens as $c){
								if(is_array($c)) {
									if($c[0] == T_STRING && $c[1] == "requestAction"){
										$is_grab = 1;
									}
									if(!empty($is_grab)){
										$c[1] = trim($c[1]);
										if(!empty($c[1])){
											$form_case_array[] = $c[1];
										}
									}
								}
							}
							if (in_array("'admin_index'", $form_case_array)) {
								$replace_array['\'admin_index\''] = "'index', 'admin' => true";
							}
						}
						foreach($replace_array as $key => $value) {
							$contents_migrated = str_replace($key, $value, $contents_migrated, $count);
							$display[$key] = $count;
						}
					}
					// RSS Layouts //
					if (is_file($path) && (strpos($path, '.ctp') !== false) && (strpos($path, 'views') !== false) && (strpos($path, 'layouts') !== false) && (strpos($path, 'rss') !== false)) {
						$replace_array = array(
							'echo $this->Rss->header();' => '',
						);
						foreach($replace_array as $key => $value){
							$contents_migrated = str_replace($key, $value, $contents_migrated, $count);
							$display[$key] = $count;
						}
					}
					if (is_file($path) && (strpos($path, '.php') !== false) && ((strpos($path, 'models') !== false) || (strpos($path, 'app_model') !== false))) {
						$replace_array = array(
							'var $name' => 'public $name',
							'var $displayField' => 'public $displayField',
							'var $actsAs' => 'public $actsAs',
							'var $belongsTo' => 'public $belongsTo',
							'var $hasMany' => 'public $hasMany',
							'var $hasOne' => 'public $hasOne',
							'var $hasAndBelongsToMany' => 'public $hasAndBelongsToMany',
							'&new ' => 'new ',
						);
						foreach($replace_array as $key => $value){
							$contents_migrated = str_replace($key, $value, $contents_migrated, $count);
							$display[$key] = $count;
						}
					}
					if (is_file($path) && (strpos($path, '.php') !== false) && (strpos($path, 'behaviors') !== false)) {
						$replace_array = array(
							'ife(is_array($settings) , $settings, array())' => '(is_array($settings) ? $settings : array())',
						);
						foreach($replace_array as $key => $value){
							$contents_migrated = str_replace($key, $value, $contents_migrated, $count);
							$display[$key] = $count;
						}
					}
					if (is_file($path) && ((strpos($path, 'webroot') !== false) && (strpos($path, 'index.php') !== false))) {
						$contents_migrated = $this->migrateFiles($path);
					}
					if (is_file($path) && (strpos($path, 'config') !== false) && (strpos($path, '.php') !== false)&& (strpos($path, 'core.php') !== false)) {
						$contents_migrated = $this->migrateCore($path, $contents);
					}
					if (is_file($path) && (strpos($path, 'vendors') !== false) && (strpos($path, '.php') !== false)&& (strpos($path, 'cron.php') !== false)) {
						$replace_array = array(
							'&new CronComponent()' => 'new CronComponent()'
						);
						foreach($replace_array as $key => $value){
							$contents_migrated = str_replace($key, $value, $contents_migrated, $count);
							$display[$key] = $count;
						}
					}
					if (is_file($path) && (strpos($path, '.php') !== false) && ((strpos($path, 'controllers') !== false) || (strpos($path, 'app_controller') !== false))) {
						$replace_array = array();
						$replace_array = array(
							'var $name' => 'public $name',
							'var $components' => 'public $components',
							'var $uses' => 'public $uses',
							'var $helpers' => 'public $helpers',
							'->del(' => '->delete(',
							'$this->params[\'url\'][\'url\']' => '$this->request->url',
							'$this->params[\'url\']' => '$this->request->query',
							'&new ' => 'new ',
							'$this->data' => '$this->request->data',
							'$this->params' => '$this->request->params',
							'$this->cakeError(\'error404\');' => 'throw new NotFoundException(__l(\'Invalid request\'));',
							'$this->cakeError(\'error403\');' => 'throw new ForbiddenException(__l(\'Invalid request\'));',
							'$this->cakeError(\'error500\');' => 'throw new InternalErrorException(__l(\'Invalid request\'));',
							'$this->WildPage->recursive' => '$this->Page->recursive',
							'Component extends Object' => 'Component extends Component',
						);
						$is_grab = 0;
						$counter = 0;
						$get_tokens = $this->tokenaize($contents_migrated);
						if (!empty($get_tokens)) {
							foreach($get_tokens as $c){
								if(is_array($c)) {
									if($c[0] == T_STRING && $c[1] == "paginate") {
										$is_grab = 1;
										$counter = 0;
									}
									if (!empty($is_grab)) {
										$counter++;
										if ($c[0] == T_CONSTANT_ENCAPSED_STRING && $c[1] == "'search'"){
											$replace_array['$this->paginate[\'search\']'] = '//$this->paginate[\'search\']';
										}
										if($counter > 3){
											$is_grab = 0;
										}
									}
								}
							}
						}
						foreach($replace_array as $key => $value){
							$contents_migrated = str_replace($key, $value, $contents_migrated, $count);
							$display[$key] = $count;
						}
						// Components oauth_consumer.php n abstract_consumer.php //
						if (is_file($path) && (strpos($path, '.php') !== false) && (strpos($path, 'controllers') !== false)  && (strpos($path, 'components') !== false)) {
							if(strpos($path, 'oauth_consumer.php') !== false || strpos($path, 'abstract_consumer.php') !== false ){
								$contents_migrated = $this->migrateFiles($path);
							}
						}
					}
					$path_out = str_replace(APP, 'APP', $path);
					if ($contents == $contents_migrated) {
						//$this->out($path_out . ' - skipped');
					} else {
						file_put_contents($path, $contents_migrated);
						if(!empty($display)){
							foreach($display as $key => $value){
								if(!empty($value)){
									echo $key." has been found ".$value." time and it has been replaced. \n";
								}
							}
							$this->out($path_out . ' - migrated');
						}
						if (is_file($path) && (strpos($path, '.php') !== false)){
							$this->sBeautifier->main($path);
						}
					}
					unset($contents);
					unset($contents_migrated);
				}
            }
        }
        closedir($handle);
    }
    function help()
    {
        $this->out(__('Migrate cakePHP files 1.2 to 2.0:', true));
        $this->hr();
        $this->out(__('By default -app is ROOT/app', true));
        $this->hr();
        $this->out(__('usage: cake migrate [command]', true));
        $this->out('');
        $this->out(__('commands:', true));
        $this->out(__('   -app [path...]: directory where your application is located', true));
        $this->out('');
    }
	function migrateFiles($path, $contents = null){
		if(strpos($path, 'oauth_consumer.php') !== false){
$str = <<<EOD
<?php
/**
 * A simple OAuth consumer component for CakePHP.
 * 
 * Requires the OAuth library from http://oauth.googlecode.com/svn/code/php/
 * 
 * Copyright (c) by Daniel Hofstetter (http://cakebaker.42dh.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 */

App::import('Core', 'http_socket');

class OauthConsumerComponent extends Component {
	private \$url = null;
	private \$fullResponse = null;
	
	public function __construct(ComponentCollection \$collection, \$settings = array()) {
		parent::__construct(\$collection, \$settings);

		\$pathToVendorsFolder = \$this->getPathToVendorsFolderWithOAuthLibrary();
		
		if (\$pathToVendorsFolder == '') {
			exit('Unable to find the PHP library for OAuth');
		}
		
		\$importPrefix = '';

		if (\$this->isPathWithinPlugin(\$pathToVendorsFolder)) {
			\$importPrefix = \$this->getPluginName() . '.';
		}
		
		App::import('Vendor', \$importPrefix.'oauth', array('file' => 'OAuth'.DS.'OAuth.php'));
	}
	
	/**
	 * Call API with a GET request
	 */
	public function get(\$consumerName, \$accessTokenKey, \$accessTokenSecret, \$url, \$getData = array()) {
		\$accessToken = new OAuthToken(\$accessTokenKey, \$accessTokenSecret);
		\$request = \$this->createRequest(\$consumerName, 'GET', \$url, \$accessToken, \$getData);
		
		return \$this->doGet(\$request->to_url());
	}
	
	public function getAccessToken(\$consumerName, \$accessTokenURL, \$requestToken, \$httpMethod = 'POST', \$parameters = array()) {
		\$this->url = \$accessTokenURL;
		\$queryStringParams = OAuthUtil::parse_parameters(\$_SERVER['QUERY_STRING']);
		\$parameters['oauth_verifier'] = \$queryStringParams['oauth_verifier'];
		\$request = \$this->createRequest(\$consumerName, \$httpMethod, \$accessTokenURL, \$requestToken, \$parameters);
		
		return \$this->doRequest(\$request);
	}
	
	/**
	 * Useful for debugging purposes to see what is returned when requesting a request/access token.
	 */
	public function getFullResponse() {
		return \$this->fullResponse;
	}
	
	/**
	 * @param \$consumerName
	 * @param \$requestTokenURL
	 * @param \$callback An absolute URL to which the Service Provider will redirect the User back when the Obtaining User 
	 * 					Authorization step is completed. If the Consumer is unable to receive callbacks or a callback URL 
	 * 					has been established via other means, the parameter value MUST be set to oob (case sensitive), to 
	 * 					indicate an out-of-band configuration. Section 6.1.1 from http://oauth.net/core/1.0a
	 * @param \$httpMethod 'POST' or 'GET'
	 * @param \$parameters
	 */
	public function getRequestToken(\$consumerName, \$requestTokenURL, \$callback = 'oob', \$httpMethod = 'POST', \$parameters = array()) {
		\$this->url = \$requestTokenURL;
		\$parameters['oauth_callback'] = \$callback;
		\$request = \$this->createRequest(\$consumerName, \$httpMethod, \$requestTokenURL, null, \$parameters);
		
		return \$this->doRequest(\$request);
	}
	
	/**
	 * Call API with a POST request
	 */
	public function post(\$consumerName, \$accessTokenKey, \$accessTokenSecret, \$url, \$postData = array()) {
		\$accessToken = new OAuthToken(\$accessTokenKey, \$accessTokenSecret);
		\$request = \$this->createRequest(\$consumerName, 'POST', \$url, \$accessToken, \$postData);
		
		return \$this->doPost(\$url, \$request->to_postdata());
	}
	
	protected function createOAuthToken(\$response) {
		if (isset(\$response['oauth_token']) && isset(\$response['oauth_token_secret'])) {
			return new OAuthToken(\$response['oauth_token'], \$response['oauth_token_secret']);
		}
		
		return null;
	}
	
	private function createConsumer(\$consumerName) {
		\$CONSUMERS_PATH = dirname(__FILE__).DS.'oauth_consumers'.DS;
		App::import('File', 'abstractConsumer', array('file' => \$CONSUMERS_PATH.'abstract_consumer.php'));
		
		\$fileName = Inflector::underscore(\$consumerName) . '_consumer.php';
		\$className = \$consumerName . 'Consumer';
		
		if (App::import('File', \$fileName, array('file' => \$CONSUMERS_PATH.\$fileName))) {
			\$consumerClass = new \$className();
			return \$consumerClass->getConsumer();
		} else {
			throw new InvalidArgumentException('Consumer ' . \$fileName . ' not found!');
		}
	}
	
	private function createRequest(\$consumerName, \$httpMethod, \$url, \$token, array \$parameters) {
		\$consumer = \$this->createConsumer(\$consumerName);
		\$request = OAuthRequest::from_consumer_and_token(\$consumer, \$token, \$httpMethod, \$url, \$parameters);
		\$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), \$consumer, \$token);
		
		return \$request;
	}
	
	private function doGet(\$url) {
		\$socket = new HttpSocket();
		return \$socket->get(\$url);
	}
	
	private function doPost(\$url, \$data) {
		\$socket = new HttpSocket();
		return \$socket->post(\$url, \$data);
	}
	
	private function doRequest(\$request) {
		if (\$request->get_normalized_http_method() == 'POST') {
			\$data = \$this->doPost(\$this->url, \$request->to_postdata());
		} else {
			\$data = \$this->doGet(\$request->to_url());
		}

		\$this->fullResponse = \$data;
		\$response = array();
		parse_str(\$data, \$response);

		return \$this->createOAuthToken(\$response);
	}
	
	private function getPathToVendorsFolderWithOAuthLibrary() {
		\$pathToVendorsFolder = '';
		
		if (\$this->isPathWithinPlugin(__FILE__)) {
			\$pluginName = \$this->getPluginName();
			
			if (file_exists(APP.'plugins'.DS.\$pluginName.DS.'vendors'.DS.'OAuth')) {
				\$pathToVendorsFolder = APP.'plugins'.DS.\$pluginName.DS.'vendors'.DS;
			}
		}

		if (\$pathToVendorsFolder == '') {
			if (file_exists(APP.'vendors'.DS.'OAuth')) {
				\$pathToVendorsFolder = APP.'vendors'.DS;
			} elseif (file_exists(VENDORS.'OAuth')) {
				\$pathToVendorsFolder = VENDORS;
			}
		}
		
		return \$pathToVendorsFolder;
	}
	
	private function getPluginName() {
		\$result = array();
		if (preg_match('#'.DS.'plugins'.DS.'(.*)'.DS.'controllers#', __FILE__, \$result)) { 
			return \$result[1];
		}
		
		return false;
	}
	
	private function isPathWithinPlugin(\$path) {
		return strpos(\$path, DS.'plugins'.DS) ? true : false;
	}
}
EOD;
		return $str;
		}
		if(strpos($path, 'abstract_consumer.php') !== false){
$str = <<<EOD
<?php
/**
 * Abstract base class for OAuth consumers. 
 * 
 * A typical class extending this base class looks like:
 * 
 * class FireEagleConsumer extends AbstractConsumer {
 *     public function __construct() {
 * 	       parent::__construct('key', 'secret');
 *     }
 * }
 * 
 * The following conventions apply for subclasses:
 * - class name has to end with "Consumer"
 * - each class has to be in its own file, the name ending with "_consumer.php"
 * - class name is camel-cased, file name uses underscores, e.g. FireEagleConsumer 
 *   and fire_eagle_consumer.php 
 * 
 * Copyright (c) by Daniel Hofstetter (http://cakebaker.42dh.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

abstract class AbstractConsumer {
	private \$consumerKey = null;
	private \$consumerSecret = null;
	
	public function __construct(\$consumerKey, \$consumerSecret) {
		\$this->consumerKey = \$consumerKey;
		\$this->consumerSecret = \$consumerSecret;
	}
	
	final public function getConsumer() {
		return new OAuthConsumer(\$this->consumerKey, \$this->consumerSecret);
	}
}
EOD;
		return $str;
		}
		if(strpos($path, 'index.php') !== false){
$str = <<<EOD
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
	if (!in_array(\$_SERVER['REQUEST_METHOD'], array('POST', 'PUT', 'DELETE')) && permanentCached()) {
		return;
	} else {
		//Fix to upload the file through the flash multiple uploader
		if ((isset(\$_SERVER['HTTP_USER_AGENT']) and ((strtolower(\$_SERVER['HTTP_USER_AGENT']) == 'shockwave flash') or (strpos(strtolower(\$_SERVER['HTTP_USER_AGENT']) , 'adobe flash player') !== false))) and strpos(\$_GET['url'], 'flashupload') !== false) {
			\$url_arr = explode('/', \$_GET['url']);
			session_name('PHPSESSID');
			session_id(\$url_arr[2]);
			@session_start();
		}
		if (!include(CORE_PATH . 'cake' . DS . 'bootstrap.php')) {
			trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
		}
		if (isset(\$_GET['url']) && \$_GET['url'] === 'favicon.ico') {
			return;
		} else {
			require LIBS . 'dispatcher.php';
			\$Dispatcher = new Dispatcher();
			\$Dispatcher->dispatch(new CakeRequest(isset(\$_GET['url']) ? \$_GET['url'] : null));
		}
	}

/**
 * Outputs cached dispatch view cache
 */
function permanentCached(\$requested = null) {
	session_name('CAKEPHP');
	@session_start();
	if (!empty(\$_SESSION['Message'])) {
		return false;
	}
	// quick fix for ajax submit
	if (in_array(\$_SERVER['REQUEST_METHOD'], array('POST', 'PUT', 'DELETE'))) {
		return false;
	}
	\$cache = !empty(\$requested) ? \$requested : baseUrl() . '/' . \$_GET['url'];
	if (isset(\$_SERVER['HTTP_X_REQUESTED_WITH']) && \$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
		\$requested = 1;
	}
	if (!class_exists('Inflector')) {
		require CORE_PATH . 'cake' . DS . 'libs' . DS . 'inflector.php';
	}
	\$cache = strtolower(Inflector::slug(\$cache));
	if (!empty(\$_SESSION['Auth']['User']['user_type_id']) && \$_SESSION['Auth']['User']['user_type_id'] == 1) {
		\$cache .= '{' . '_admin' . '_user_' . \$_SESSION['Auth']['User']['id'] . ',' . '_usertype_' . \$_SESSION['Auth']['User']['user_type_id'] . '}';
	} elseif (!empty(\$_SESSION['Auth']['User']['user_type_id'])) {
		\$cache .= '{' . '_user_' . \$_SESSION['Auth']['User']['id'] . ',' . '_usertype_' . \$_SESSION['Auth']['User']['user_type_id'] . '}';
	} else {
		\$cache .= '{_public,_loggedin}';
	}
	if (!empty(\$requested)) {
		\$cache .= '_requested';
	}
	if (!empty(\$_COOKIE['CakeCookie[city_language]'])) {
		\$cache .= '_' . \$_COOKIE['CakeCookie[city_language]'];
	} else {
		\$cache .= '_en';
	}
	\$cache .= '_*';
	if (\$filename = glob(APP_PATH . 'tmp' . DS . 'cache' . DS . 'views' . DS . \$cache . '.php', GLOB_BRACE)) {
		if (\$pos = strpos(\$filename[0], 'updateviews')) {
			\$tmp_arr = explode('_', substr(\$filename[0], \$pos + 11));
			\$tmp_model_arr = explode('.', \$tmp_arr[1]);
			updateViews(\$tmp_model_arr[0], \$tmp_arr[0]);
		}
		return readfile(\$filename[0]);
	}
	return false;
}
function baseUrl() {
	\$replace = array('<', '>', '*', '\'', '"');
	\$base = str_replace(\$replace, '', dirname(\$_SERVER['PHP_SELF']));
	if (\$base === DS || \$base === '.') {
		\$base = '';
	}
	return \$base;
}
function updateViews(\$table, \$main_id) {
	require APP_PATH . 'config' . DS . 'database.php';
	\$database = new DATABASE_CONFIG();
	\$db = mysql_connect(\$database->default['host'], \$database->default['login'], \$database->default['password']) or die ('Error connecting to mysql');
	mysql_select_db(\$database->default['database']);
	mysql_set_charset('utf8', \$db);
	\$main_table_name = Inflector::tableize(\$table);
	\$main_field_name = Inflector::singularize(\$main_table_name);
	\$main_result = mysql_query('SELECT * FROM ' . \$main_table_name . ' WHERE id = ' . \$main_id);
	\$main_row = mysql_fetch_assoc(\$main_result);
	\$ip_result = mysql_query('SELECT id FROM `ips` WHERE ip = "' . \$_SERVER['REMOTE_ADDR'] . '"');
	if (mysql_num_rows(\$ip_result)) {
		\$ip_row = mysql_fetch_assoc(\$ip_result);
		\$ip_id = \$ip_row['id'];
	} else {
		if (!empty(\$_COOKIE['_geo'])) {
			\$_geo = explode('|', \$_COOKIE['_geo']);
			\$country_result = mysql_query('SELECT id FROM `countries` WHERE iso2 = "' . \$_geo[0] . '"');
			if (mysql_num_rows(\$country_result)) {
				\$country_row = mysql_fetch_assoc(\$country_result);
				\$country_id = \$country_row['id'];
			}
			\$state_result = mysql_query('SELECT id FROM `states` WHERE name = "' . \$_geo[1] . '"');
			if (mysql_num_rows(\$state_result)) {
				\$state_row = mysql_fetch_assoc(\$state_result);
				\$state_id = \$state_row['id'];
			}
			\$city_result = mysql_query('SELECT id FROM `cities` WHERE name = "' . \$_geo[2] . '"');
			if (mysql_num_rows(\$city_result)) {
				\$city_row = mysql_fetch_assoc(\$city_result);
				\$city_id = \$city_row['id'];
			}
		}
		mysql_query('INSERT INTO `ips` (`created`, `modified`, `ip`, `host`, `city_id`, `state_id`, `country_id`, `latitude`, `longitude`) VALUES (now(), now(), "' . \$_SERVER['REMOTE_ADDR'] . '", "' . gethostbyaddr(\$_SERVER['REMOTE_ADDR']) . '", ' . \$city_id . ', ' . \$state_id . ', ' . \$country_id . ', "' . \$_geo[3] . '", "' . \$_geo[4] . '")');
		\$ip_id = mysql_insert_id();
	}
	\$view_table_name = Inflector::tableize(\$table . 'View');
	\$user_id = isset(\$_SESSION['Auth']['User']['id']) ? \$_SESSION['Auth']['User']['id'] : 0;
	mysql_query('INSERT INTO `' . \$view_table_name . '` (`created`, `modified`, `user_id`, `' . \$main_field_name . '_id`, `ip_id`) VALUES (now(), now(), ' . \$user_id . ', ' . \$main_id . ', ' . \$ip_id . ')');
	\$view_result = mysql_query('SELECT COUNT(*) as count FROM ' . \$view_table_name . ' WHERE ' . \$main_field_name . '_id = ' . \$main_id);
	\$view_row = mysql_fetch_assoc(\$view_result);
	mysql_query('UPDATE `' . \$main_table_name . '` SET ' . \$main_field_name . '_view_count = "' . \$view_row['count'] . '" WHERE id = ' . \$main_id);
}
EOD;
		return $str;
		}
	}
	function migrateCore($path, $contents){
		if(!empty($contents)){
			$get_tokens = $this->tokenaize($contents);
			$is_capture_salt = 0;
			foreach($get_tokens as $c){
				if(is_array($c)) {
					if ($c[0] == T_CONSTANT_ENCAPSED_STRING){
						if(!empty($is_capture_salt)){
							$old_salt = $c[1];
							break;
						}
						if($c[1] == "'Security.salt'"){
							$is_capture_salt = 1;
						}
					}
				}
			}
$str = <<<EOD
<?php
/**
 * This is core configuration file.
 *
 * Use it to configure core behavior of Cake.
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
 * @package       app.config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * CakePHP Debug Level:
 *
 * Production Mode:
 * 	0: No error messages, errors, or warnings shown. Flash messages redirect.
 *
 * Development Mode:
 * 	1: Errors and warnings shown, model caches refreshed, flash messages halted.
 * 	2: As in 1, but also with full debug messages and SQL output.
 *
 * In production mode, flash messages redirect after a time interval.
 * In development mode, you need to click the flash message to continue.
 */
	Configure::write('debug', 2);

/**
 * Configure the Error handler used to handle errors for your application.  By default
 * ErrorHandler::handleError() is used.  It will display errors using Debugger, when debug > 0
 * and log errors with CakeLog when debug = 0.
 *
 * Options:
 *
 * - `handler` - callback - The callback to handle errors. You can set this to any callback type, 
 *    including anonymous functions.
 * - `level` - int - The level of errors you are interested in capturing.
 * - `trace` - boolean - Include stack traces for errors in log files.
 *
 * @see ErrorHandler for more information on error handling and configuration.
 */
	Configure::write('Error', array(
		'handler' => 'ErrorHandler::handleError',
		'level' => E_ALL & ~E_DEPRECATED,
		'trace' => true
	));

/**
 * Configure the Exception handler used for uncaught exceptions.  By default, 
 * ErrorHandler::handleException() is used. It will display a HTML page for the exception, and 
 * while debug > 0, framework errors like Missing Controller will be displayed.  When debug = 0, 
 * framework errors will be coerced into generic HTTP errors.
 *
 * Options:
 *
 * - `handler` - callback - The callback to handle exceptions. You can set this to any callback type, 
 *   including anonymous functions.
 * - `renderer` - string - The class responsible for rendering uncaught exceptions.  If you choose a custom class you
 *   should place the file for that class in app/libs. This class needs to implement a render method.
 * - `log` - boolean - Should Exceptions be logged?
 *
 * @see ErrorHandler for more information on exception handling and configuration.
 */
	Configure::write('Exception', array(
		'handler' => 'ErrorHandler::handleException',
		'renderer' => 'ExceptionRenderer',
		'log' => true
	));

/**
 * Application wide charset encoding
 */
	Configure::write('App.encoding', 'UTF-8');

/**
 * To configure CakePHP *not* to use mod_rewrite and to
 * use CakePHP pretty URLs, remove these .htaccess
 * files:
 *
 * /.htaccess
 * /app/.htaccess
 * /app/webroot/.htaccess
 *
 * And uncomment the App.baseUrl below:
 */
	//Configure::write('App.baseUrl', env('SCRIPT_NAME'));

/**
 * Uncomment the define below to use CakePHP prefix routes.
 *
 * The value of the define determines the names of the routes
 * and their associated controller actions:
 *
 * Set to an array of prefixes you want to use in your application. Use for
 * admin or other prefixed routes.
 *
 * 	Routing.prefixes = array('admin', 'manager');
 *
 * Enables:
 *	`admin_index()` and `/admin/controller/index`
 *	`manager_index()` and `/manager/controller/index`
 *
 *	Configure::write('Routing.prefixes', array('admin'));

/**
 * Turn off all caching application-wide.
 *
 */
	//Configure::write('Cache.disable', true);

/**
 * Enable cache checking.
 *
 * If set to true, for view caching you must still use the controller
 * public \$cacheAction inside your controllers to define caching settings.
 * You can either set it controller-wide by setting public \$cacheAction = true,
 * or in each action using \$this->cacheAction = true.
 *
 */
	//Configure::write('Cache.check', true);

/**
 * Defines the default error type when using the log() function. Used for
 * differentiating error logging and debugging. Currently PHP supports LOG_DEBUG.
 */
	define('LOG_ERROR', 2);

/**
 * Session configuration.
 *
 * Contains an array of settings to use for session configuration. The defaults key is 
 * used to define a default preset to use for sessions, any settings declared here will override
 * the settings of the default config.
 *
 * ## Options
 *
 * - `Session.name` - The name of the cookie to use. Defaults to 'CAKEPHP'
 * - `Session.timeout` - The number of minutes you want sessions to live for. This timeout is handled by CakePHP
 * - `Session.cookieTimeout` - The number of minutes you want session cookies to live for.
 * - `Session.checkAgent` - Do you want the user agent to be checked when starting sessions? You might want to set the    
 *    value to false, when dealing with older versions of IE, Chrome Frame or certain web-browsing devices and AJAX
 * - `Session.defaults` - The default configuration set to use as a basis for your session.
 *    There are four builtins: php, cake, cache, database.
 * - `Session.handler` - Can be used to enable a custom session handler.  Expects an array of of callables,
 *    that can be used with `session_save_handler`.  Using this option will automatically add `session.save_handler`
 *    to the ini array.
 * - `Session.autoRegenerate` - Enabling this setting, turns on automatic renewal of sessions, and 
 *    sessionids that change frequently. See CakeSession::\$requestCountdown.
 * - `Session.ini` - An associative array of additional ini values to set.
 *
 * The built in defaults are:
 *
 * - 'php' -Uses settings defined in your php.ini.
 * - 'cake' - Saves session files in CakePHP's /tmp directory.
 * - 'database' - Uses CakePHP's database sessions.
 * - 'cache' - Use the Cache class to save sessions.
 *
 * To define a custom session handler, save it at /app/libs/session/<name>.php.
 * Make sure the class implements `CakeSessionHandlerInterface` and set Session.handler to <name>
 *
 * To use database sessions, run the app/config/schema/sessions.php schema using
 * the cake shell command: cake schema create Sessions
 *
 */
	Configure::write('Session', array(
		'defaults' => 'php'
	));

/**
 * The level of CakePHP security.
 */
	Configure::write('Security.level', 'medium');

/**
 * A random string used in security hashing methods.
 */
	Configure::write('Security.salt', $old_salt);

/**
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 */
	Configure::write('Security.cipherSeed', '68605533753216403975180006551');

/**
 * Apply timestamps with the last modified time to static assets (js, css, images).
 * Will append a querystring parameter containing the time the file was modified. This is
 * useful for invalidating browser caches.
 *
 * Set to `true` to apply timestamps, when debug = 0, or set to 'force' to always enable
 * timestamping.
 */
	//Configure::write('Asset.timestamp', true);
/**
 * Compress CSS output by removing comments, whitespace, repeating tags, etc.
 * This requires a/var/cache directory to be writable by the web server for caching.
 * and /vendors/csspp/csspp.php
 *
 * To use, prefix the CSS link URL with '/ccss/' instead of '/css/' or use HtmlHelper::css().
 */
	//Configure::write('Asset.filter.css', 'css.php');

/**
 * Plug in your own custom JavaScript compressor by dropping a script in your webroot to handle the
 * output, and setting the config below to the name of the script.
 *
 * To use, prefix your JavaScript link URLs with '/cjs/' instead of '/js/' or use JavaScriptHelper::link().
 */
	//Configure::write('Asset.filter.js', 'custom_javascript_output_filter.php');

/**
 * The classname and database used in CakePHP's
 * access control lists.
 */
	Configure::write('Acl.classname', 'DbAcl');
	Configure::write('Acl.database', 'default');

/**
 * If you are on PHP 5.3 uncomment this line and correct your server timezone
 * to fix the date & time related errors.
 */
	//date_default_timezone_set('UTC');

/**
 *
 * Cache Engine Configuration
 * Default settings provided below
 *
 * File storage engine.
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'File', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'path' => CACHE, //[optional] use system tmp directory - remember to use absolute path
 * 		'prefix' => 'cake_', //[optional]  prefix every cache file with this string
 * 		'lock' => false, //[optional]  use file locking
 * 		'serialize' => true, [optional]
 *	));
 *
 *
 * APC (http://pecl.php.net/package/APC)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Apc', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 *
 * Xcache (http://xcache.lighttpd.net/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Xcache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional] prefix every cache file with this string
 *		'user' => 'user', //user from xcache.admin.user settings
 *      'password' => 'password', //plaintext password (xcache.admin.pass)
 *	));
 *
 *
 * Memcache (http://www.danga.com/memcached/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Memcache', //[required]
 *		'duration'=> 3600, //[optional]
 *		'probability'=> 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 		'servers' => array(
 * 			'127.0.0.1:11211' // localhost, default port 11211
 * 		), //[optional]
 * 		'compress' => false, // [optional] compress data in Memcache (slower, but uses less memory)
 *	));
 *
 */
	Cache::config('default', array(
		'engine' => 'File'
	));
	Cache::config('sec', array(
		'engine' => 'File',
		'duration' => '30',
		'path' => CACHE . DS . 'views'
	));
	Cache::config('min', array(
		'engine' => 'File',
		'duration' => '120',
		'path' => CACHE . DS . 'views'
	));
EOD;
		return $str;
		}
	}
	function tokenaize($contents){
		$tokens = token_get_all($contents);
		foreach(array_keys($tokens) as $i) {
			if (is_string($tokens[$i])) {
				$tokens[$i] = array(ord($tokens[$i]) ,$tokens[$i] );
			}
			if (($tokens[$i][0] == T_STRING) && ($tokens[$i][1] == 'self')) {
				$tokens[$i][0] = self::T_SELF;
			}
		}
		return $tokens;
	}
}
?>