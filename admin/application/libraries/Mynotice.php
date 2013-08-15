<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mynotice_Core extends My
{
	//对象名称(表名)
    protected $object_name = 'notice';

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
}
