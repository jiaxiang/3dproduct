<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mynewsletter_Core extends My
{
	//对象名称(表名)
    protected $object_name = 'newsletter';

	private static $instances;
	public static function & instance($id = 0)
	{
		if (!isset(self::$instances[$id]))
		{
			$class = __CLASS__;
			self::$instances[$id] = new $class($id);
		}
		return self::$instances[$id];
	}
	
	/**
	 * 设置状态为inactive
	 */
	public function set_inactive($id = 0)
	{
		$email_sign_up = ORM::factory('newsletter',$id);
		if($email_sign_up->loaded == true)
		{
			$email_sign_up->active = 0;
			$email_sign_up->save();
			return $email_sign_up->saved;
		}
		else
		{
			return false;
		}
	}
}
