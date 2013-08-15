<?php defined('SYSPATH') or die('No direct script access.');

class Myfaq_tip_Core
{
	private static $instances;

	private $data;

	public static function & instance($id = 0)
	{
		if (!isset(self::$instances[$id]))
		{
			$class = __CLASS__;
			self::$instances[$id] = new $class($id);
		}

		return self::$instances[$id];
	}

	public function __construct($id)
	{
		$this->_load($id);
	}

	private function _load($id)
	{
		if($id != 0)
		{
			//get faq tip data
			$faq_tip = ORM::factory('faq_tip',$id);
			$this->data = $faq_tip->as_array();
		}
	}

	public function get($key=NULL)
	{
		if(empty($key))
		{
			return $this->data;
		}
		else
		{
			return isset($this->data[$key])?$this->data[$key]:'';
		}
	}

	public function edit()
	{
	
	}

	public function templates()
	{
	
	}

}

