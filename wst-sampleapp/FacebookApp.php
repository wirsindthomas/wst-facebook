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

require_once '../WST/Facebook.php';

class FacebookApp extends WST_Facebook{

	/**
	 * Default function that is called before any action
	 *
	 * @return void
	 * @author Alexander Thomas
	 */
	function init(){
	}

	/**
	 * Default action
	 *
	 * @return void
	 * @author Alexander Thomas
	 */
	function indexAction(){
		
		$this->view->example_value = 'Hello World';
		
		//render() triggers template rendering process for this action
		$this->render();
	}

}
