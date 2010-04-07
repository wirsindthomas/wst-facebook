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
class WST_Facebook_View
{
	private $_template_dir = 'views';
	private $_template_ext = 'phtml';

	
	public function setTemplateDir($template_dir)
	{
		$this->_template_dir = $template_dir;
	}
	
	public function __get($key)
	{
		if(isset($this->$key)){
			return $this->$key;
		}
		return null;
	}
	
	public function __set($key, $value)
	{
		$this->$key = $value;
	}
	
	public function render($action)
	{	
		$template_file = $this->_template_dir . '/' . $action.'.'.$this->_template_ext;
		if(!file_exists($template_file)){
			throw new WST_Facebook_Exception("Template File does not exist.");
		}
		
		include $template_file;
	}
}
