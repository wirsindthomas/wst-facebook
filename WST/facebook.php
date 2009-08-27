/**
 * wst-facebook
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

<?php

require_once 'Facebook/Exception.php';


/**
 * WST Facebook
 *
 * @package default
 * @author Thomas Niepraschk
 */
abstract class WST_Facebook {

	protected $view;
	protected $db;
	protected $facebook;
	protected $fbuserid;
	private $log_file;

	function __construct() {
		$this->autoLoader();
		$this->initSmarty();
		$this->initLog();
		$this->init();
	}
	

	abstract function init();


/**
 * catch all non existing action calls
 *
 * @param string $name - function name
 * @param string $parameterArray 
 * @return void
 * @author Thomas Niepraschk
 */
	function __call($name, $parameterArray){
		throw new WST_Facebook_Exception("No action with the name ".$name." found.");
	}

/**
 * load all required classfiles
 *
 * @return void
 * @author Thomas Niepraschk
 */
	private function autoLoader(){
		require_once 'facebook/facebook.php';
		require_once 'smarty/Smarty.class.php';
		require_once 'adodb5/adodb.inc.php';
		require_once 'dBug.php';
	}
	
/**
 * init logging
 *
 * @param string $log_file 
 * @return void
 * @author Thomas Niepraschk
 */	
	final public function initLog($log_file = null){
		if(!empty($log_file)){
			$this->log_file = $log_file;
		}else{
			$this->log_file = "logs/WST_Facebook.log";			
		}
		
	}
	
	
	final public function initFacebook($api_key, $secret, $user_login){
		$this->facebook = new Facebook($api_key, $secret);
		if($user_login){
			$this->fbuserid = $this->facebook->require_login(); 
		}  

	}

	final protected function render(){
		$backtrace = debug_backtrace();
		$this->view->display(str_replace("Action", '', $backtrace[1]['function']).'.tpl');
	}

	final protected function initSmarty(){
		$this->view = new Smarty();
		$this->view->template_dir = 'views';
		$this->view->compile_dir = 'views/c';
	}

	/**
	 * undocumented function
	 *
	 * @param string $dbDriver 
	 * @param string $dbServer 
	 * @param string $dbUser 
	 * @param string $dbPasswd 
	 * @param string $db 
	 * @param array $options 
	 * @return void
	 * @author Alexander Thomas
	 * Legal options are:
	 * <ul>
	 * <li>For all drivers: 'persist', 'persistent', 'debug', 'fetchmode', 'new'
	 * <li>Interbase/Firebird: 'dialect','charset','buffers','role'
	 * <li>M'soft ADO: 'charpage'
	 * <li>MySQL: 'clientflags'
	 * <li>MySQLi: 'port', 'socket', 'clientflags'
	 * <li>Oci8: 'nls_date_format','charset'
	 * </ul>
	 */
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
