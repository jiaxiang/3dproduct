<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mymail_log_Core {
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
	 * Construct load mail_log data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load mail_log data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$mail_log = ORM::factory('mail_log',$id);
		$this->data = $mail_log->as_array();
	}

	/**
	 * get mail_log data
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
	 * add a mail_log item
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//ADD
		$mail_log = ORM::factory('mail_log');
		$mail_log->add_time = date("Y-m-d H:i:s");
		$errors = '';
		if($mail_log->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $mail_log->as_array();
			return $mail_log->id;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * update mail_log
	 *
	 * @param <Int> $id
	 * @param <Array> $data
	 * @return Boolean
	 */
	public function _update($id,$data)
	{
		$mail_log = ORM::factory('mail_log',$id);
		if(!$mail_log->loaded)
		{
			return false;
		}

		foreach($data as $key=>$value)
		{
			$mail_log->$key = $value;
		}
		$mail_log->save();
		if($mail_log->saved)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * update mail_log
	 *
	 * @param <Array> $data
	 * @return Boolean
	 */
	public function update($data)
	{
		$id = intval($this->data['id']);

		return $this->_update($id,$data);
	}

	/**
	 * update mail_log by id
	 *
	 * @param <Array> $data
	 * @return Boolean
	 */
	public function update_by_id($id,$data)
	{
		return $this->_update($id,$data);
	}
}
