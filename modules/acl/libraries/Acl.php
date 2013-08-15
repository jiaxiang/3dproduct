<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Acl_Core
 *
 * $Id: Acl.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @package    Acl
 * @author     Ketai Team
 * @copyright  (c) 2007-2008 Ketai Team
 * @license    http://ketai.com/license.html
 */
require_once dirname(__FILE__).'/ZendAcl.php';
 
class Acl_Core extends Zend_Acl{
	private static $instance;
	
	/**
	 * Returns a singleton instance of acl.
	 *
	 * @return  object
	 */
	public static function & instance()
	{
		if (!isset(self::$instance))
		{
			$class = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
	}
	
	/**
	 * acl add role
	 *
	 * @param   string  role
	 * @return  boolean
	 */
	public function add_role($role, $parents = null)
	{
		return $this->addRole($role, $parents);
	}
	
	/**
	 * acl add resource
	 *
	 * @param   string  resource
	 * @return  boolean
	 */
	public function add_resource($resource, $parent = null)
	{
		return $this->addResource($resource, $parent);
	}
	
	/**
	 * acl permissions check.
	 *
	 * @param   string  role
	 * @param   string  default value
	 * @param   string  XSS clean the value
	 * @return  boolean
	 */
	public function is_allowed($role = NULL, $permissions = NULL, $resource = NULL)
	{
		if(strtolower($role) == "root")
		{
			return true;
		}else{
			return $this->isAllowed($role, $resource, $permissions);
		}
	}

} // End Acl_Core