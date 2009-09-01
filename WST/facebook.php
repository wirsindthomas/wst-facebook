<?php
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
	
	/**
	 * Must be implemented for setting basic values 
	 * and performing init actions without overwriting the parent constructor.
	 *
	 * @return void
	 * @author Alexander Thomas
	 */
	abstract function init();
	
	/**
	 * This method is called in the index.php as soon as an error occures.
	 * Should be overwritten to fit your needs.
	 *
	 * @return void
	 * @author Alexander Thomas
	 */
	function errorAction(){
		$this->view->assign('message', 'An error occured.');
		$this->render();
	}
	
	/**
 	 * catch all non existing action calls
 	 *
 	 * @param string $name - function name
 	 * @param string $parameterArray 
 	 * @return void
 	 * @throws WST_Facebook_Exception
 	 * @author Thomas Niepraschk
 	 */
	function __call($name, $parameterArray){
		throw new WST_Facebook_Exception("No method with the name ".$name." found.");
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
 	 * Initializes the logger by setting the log file
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
	
	/**
	 * Initializes the Facebook PHP Client Library
	 *
	 * @param string $api_key 
	 * @param string $secret 
	 * @param string $user_login 
	 * @return void
	 * @author Alexander Thomas
	 */
	final public function initFacebook($api_key, $secret, $user_login){
		$this->facebook = new Facebook($api_key, $secret);
		if($user_login){
			$this->fbuserid = $this->facebook->require_login(); 
		}  

	}
	
	/**
	 * Renders the smarty template which belongs to the current action.
	 * @throws WST_Facebook_Exception
	 * @return void
	 * @author Alexander Thomas
	 */
	final protected function render(){
		$backtrace = debug_backtrace();
		$tpl_file = str_replace("Action", '', $backtrace[1]['function']).'.tpl';
		if(file_exists($this->view->template_dir . $tpl_file)){
			$this->view->display($tpl_file);			
		}else{
			throw new WST_Facebook_Exception("The template file $tpl_file could not be found in " . $this->view->template_dir);
		}
	}

	/**
	 * Initializes the Smarty template library
	 *
	 * @return void
	 * @author Alexander Thomas
	 */
	final protected function initSmarty(){
		$this->view = new Smarty();
		$this->view->template_dir = 'views';
		$this->view->compile_dir = 'views/c';
	}

	/**
	 * Initializes a database connection using the AdoDB database abstration library.
	 * If you don't like that it - use a different one.
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

	/**
	 * Writes a message into the logfile.
	 *
	 * @param string $status 
	 * @param string $message 
	 * @return void
	 * @author Alexander Thomas
	 */
	final public function log($status, $message){
		$date = date("Y-m-d H:i:s");
		$new_message = $date . " " . $status . ": " . $message . "\n";
		file_put_contents($this->log_file, $new_message, FILE_USE_INCLUDE_PATH|FILE_APPEND|LOCK_EX|FILE_TEXT);
	}
}
