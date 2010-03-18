<?php

require_once 'Facebook/Exception.php';

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
		$this->initSmarty();
		$this->initLog();
		$this->init();
	}
	
	abstract function init();

	function __call($name, $parameterArray){
		throw new WST_Facebook_Exception("No action with the name ".$name." found.");
	}

	private function autoLoader(){
		require_once 'facebook/facebook.php';
		require_once 'smarty/Smarty.class.php';
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
			$this->view->assign('base_url', $base_url);
			$this->view->assign('app_url', $this->app_url);			
			$this->view->assign('fbuser',$this->fbuserid);

		}catch(WST_Facebook_Exception $e){
			$facebookapp->log('error', $e->getMessage());
			$facebookapp->errorAction();
			}
	}

	final protected function render(){
		$backtrace = debug_backtrace();
		
		$action = str_replace("Action", '', $backtrace[1]['function']);
		
		if ($action == 'admin'){
			$this->view->assign('apptab', 'false');
			$this->view->assign('admintab', 'true');
		}
		else{
			$this->view->assign('apptab', 'true');
			$this->view->assign('admintab', 'false');
		
		}

		$backtrace = debug_backtrace();
		$this->view->display($action.'.tpl');
	}

	final protected function initSmarty(){
		$this->view = new Smarty();
		$this->view->template_dir = 'views';
		$this->view->compile_dir = 'views/c';
	}


	final public function initDB($dbDriver, $dbServer, $dbUser, $dbPasswd, $db, $options = array()){
		$dsn = rawurlencode($dbDriver).'://'.rawurlencode($dbUser).':'.rawurlencode($dbPasswd).'@'.rawurlencode($dbServer).'/'.$db;
		$dsn .= '?'.http_build_query($options);
		$this->db = ADONewConnection($dsn);
		if (!$this->db) die("Connection failed");   
	}
	
	function errorAction(){
		$this->view->assign('message', 'An error occured.');
		$this->render();
	}
	
	final public function log($status, $message){
		$date = date("Y-m-d H:i:s");
		$new_message = $date . " " . $status . ": " . $message . "\n";
		file_put_contents($this->log_file, $new_message, FILE_USE_INCLUDE_PATH|FILE_APPEND|LOCK_EX|FILE_TEXT);
	}
}
