<?php defined('SYSPATH') or die('No direct script access.');
 
class ulog_Core {
	private static $instance;

	public static function & instance()
	{
		if (!isset(self::$instance))
		{
			$class = __CLASS__;
			self::$instance = new $class;
		}

		return self::$instance;
	}

	//用户登录日志
	public static function add($data, $status=0)
	{
		$data['status'] = $status;
		return Myuser_log::instance()->add($data);
	}

	//用户登录日志
	public static function login($manager_id = 0,$status = 0)
	{
		$user_log_type_id = 25;
		$ip = tool::get_str_ip();

		$data = array();
		$data['manager_id'] = $manager_id;
		$data['ip'] = $ip;
		$data['user_log_type'] = $user_log_type_id;
		$data['status'] = $status;
		return Myuser_log::instance()->add($data);
	}

	//用户更改密码日志
	public static function change_password($manager_id = 0,$status = 0)
	{
		$user_log_type_id = 26;
		$ip = tool::get_str_ip();

		$data = array();
		$data['manager_id'] = $manager_id;
		$data['ip'] = $ip;
		$data['user_log_type'] = $user_log_type_id;
		$data['status'] = $status;
		return Myuser_log::instance()->add($data);
	}
}
