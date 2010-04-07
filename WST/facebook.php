<?php
/**
 * WST_Facebook
 *
 * LICENSE
 *
 * This source file is subject to the new CC-GNU LGPL
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/LGPL/2.1/
 *
 * @category   wst-facebook
 * @copyright  Copyright (c) 2009 Thomas Niepraschk (me@thomas-niepraschk.net), Alexander fanatique* Thomas (me@alexander-thomas.net)
 * @license    http://creativecommons.org/licenses/LGPL/2.1/
 */
require_once 'Facebook/Exception.php';
require_once 'Facebook/View.php';

abstract class WST_Facebook {

	protected $view;
	protected $db;
	protected $facebook;
	protected $fbuserid;
	private $log_file;
	protected $base_url;
	protected $app_url;

	function __construct() {
		$this->autoLoader();
		// $this->initSmarty();
		$this->initView();
		$this->initLog();
		$this->init();
	}
	
	abstract function init();

	function __call($name, $parameterArray){
		throw new WST_Facebook_Exception("No action with the name ".$name." found.");
	}

	private function autoLoader(){
		require_once 'facebook/facebook.php';
		require_once 'adodb5/adodb.inc.php';
		require_once 'dBug.php';
	}
	
	final public function initLog($log_file = null){
		if(!empty($log_file)){
			$this->log_file = $log_file;
		}else{
			$this->log_file = "logs/WST_Facebook.log";			
		}
		
	}
	
	
	final public function initFacebook($api_key, $secret, $user_login, $base_url, $app_url){

		try{
			$this->facebook = new Facebook($api_key, $secret);
			if($user_login){
				$this->fbuserid = $this->facebook->require_login(); 
			}
			$this->base_url = $base_url;
			$this->app_url = $app_url;
			
			$this->view->base_url = $base_url;
			$this->view->app_url = $this->app_url;			

			$this->view->fbuserid = $this->fbuserid;

		}catch(WST_Facebook_Exception $e){
			$facebookapp->log('error', $e->getMessage());
			$facebookapp->errorAction();
			}
	}

	final protected function render(){
		$backtrace = debug_backtrace();
		
		$action = str_replace("Action", '', $backtrace[1]['function']);

		$backtrace = debug_backtrace();
		$this->view->render($action);
	}

	final protected function initView(){
		$this->view = new WST_Facebook_View();
	}

	final public function initDB($dbDriver, $dbServer, $dbUser, $dbPasswd, $db, $options = array()){
		$dsn = rawurlencode($dbDriver).'://'.rawurlencode($dbUser).':'.rawurlencode($dbPasswd).'@'.rawurlencode($dbServer).'/'.$db;
		$dsn .= '?'.http_build_query($options);
		$this->db = ADONewConnection($dsn);
		if (!$this->db) die("Connection failed");   
	}
	
	function errorAction(){
		$this->view->message = 'An error occured.';
		$this->render();
	}
	
	final public function log($status, $message){
		$date = date("Y-m-d H:i:s");
		$new_message = $date . " " . $status . ": " . $message . "\n";
		file_put_contents($this->log_file, $new_message, FILE_USE_INCLUDE_PATH|FILE_APPEND|LOCK_EX|FILE_TEXT);
	}
}
