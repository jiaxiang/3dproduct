<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mymail_category_Core {
	private $data = array();

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
	 * Construct load mail_category data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load mail_category data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$mail_category = ORM::factory('mail_category',$id);
		$this->data = $mail_category->as_array();
	}

	/**
	 * get mail_category data
	 *
	 * @return Array
	 */
	public function get($key = NULL)
	{
		if(empty($key))
		{
			return $this->data;
		}
		else
		{
			if(isset($this->data[$key]))
			{
				return $this->data[$key];
			}
			else
			{
				return NULL;
			}
		}
	}

	/**
	 * get by flag
	 *
	 * @param <String> $flag
	 * @return <Array>
	 */
	public function get_by_flag($flag = NULL)
	{
		$where = array();
		$where['flag'] = $flag;

		$mail_category = ORM::factory('mail_category')->where($where)->find();
		return $mail_category->as_array();
	}
}
