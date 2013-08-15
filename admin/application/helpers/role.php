<?php defined('SYSPATH') OR die('No direct script access.');

class Role_Core {
	public static $acl_tag = "admin_acl";
	public static $warn_site_ids = array();
	
	//定义系统的超级root帐号 zhu add
	const ROOT = 'root';

	private static $instance;
	private static $expire = 300;
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
	 * check role for view (视图权限的控制)
	 */
	public static function view_check($html,$action_resourse='default')
	{
		if(!self::verify($action_resourse))
		{
			return NULL;
		}
		return $html;
	}

	/**
	 * 验证当前登录用户是root超级帐号
	 * @param string $username
	 * @return Boolean
	 */
	public static function is_root($username = NULL)
	{
		/* 用户详情 */
		if (is_null($username))
		{
			$manager = self::get_manager();
			$username = $manager['username'];
		}		
		return $username && strtolower($username) == self::ROOT;
	}
	
	/**
	 * 判断用户的操作权限
	 *
	 * @param <String> $model_flag
	 * @return <type>
	 */
	public static function check($model_flag = 'default')
	{
		//zhu modify
		$verify =  self::verify($model_flag);
				
		//验证操作
		if($verify)
		{
			return $verify;
		}
		else
		{
			if(request::is_ajax())
			{
				$return_struct = array (
					'status' => 0,
					'code' => 501,
					'msg' => Kohana::lang('o_global.access_denied'),
					'content' => array () 
				);
				die(json_encode($return_struct));
			} else {
				$referrer = tool::referrer_url();
				remind::set('权限不足',$referrer,'error');
			}
		}
	}

	/**
	 * 验证是否有权限操作
	 * @param string $model_flag
	 * @return Boolean
	 */
	public static function verify($permission = 'default')
	{
		$verify = false;

        //超级管理员root不需要检查权限 zhu modify 
		if(role::is_root()){
			$verify = true;
		}
		else
		{	
			$acl = Session::instance()->get(self::$acl_tag);
			
			if($acl)
			{
				$acl = unserialize($acl);
			}
			else
			{
				$acl = self::acl_init();
			}

			$manager = self::get_manager();
			//d($manager, false);
		    $verify = $acl->is_allowed($manager["username"], $permission);
		}
		//var_dump($verify);
		return $verify;
	}

	/**
	 * 得到用户所有权限资源
	 * @param Int $manager_id manager id
	 * @param Boolean $type 读取缓存数据还是更新缓存数据
	 * @return Array
	 */
	public static function manager_actions($manager_id = NULL, $type = false)
	{
		if (is_null($manager_id))
		{
			$manager = self::get_manager();
		}
		else
		{
			$manager = Mymanager::instance($manager_id)->get();
		}
		$manager_id = $manager['id'];

		$cache = Mycache::instance();
		$tag = "admin/actions/".$manager_id;
		$data = $cache->get($tag);

		
		if ($type)
		{
			$data = false;
		}

		if ($data)
		{
			if (!is_array($data))
			{
				return unserialize($data);
			}
			else
			{
				return $data;
			}
		}
		else
		{				
			//帐号名称为超级管理员root，获得全部的资源和权限 zhu modify
			if (self::is_root($manager['username']))		
			{
				$actions = Myaction::instance()->get_actions();
			}
			else
			{
				//zhu modify get acl
				//$manager = Mymanager::instance($manager_id)->get();
				//$role = Mymanager::instance($manager_id)->role();
				//$actions = Myrole::instance($role['id'])->actions();
				$acl = Mymanager::instance($manager_id)->acl();
				$actions = Myacl::instance($acl['id'])->actions();
				//d($actions);
			}
			$cache->set($tag, $actions, self::$expire);
			return $actions;
		}
	}

	/**
	 * get user action ids
	 *
	 * @return Array
	 */
	public static function get_action_ids()
	{
		$actions = self::manager_actions();
		$ids = array();
		foreach ($actions as $key=>$value)
		{
			$ids[] = $value['id'];
		}
		return $ids;
	}

	/**
	 * get user actions
	 *
	 * @param <Int> $manager_id manager id
	 * @param <Boolean> $type 读取缓存数据还是更新缓存数据
	 * @return Array
	 */
	public static function get_action_resources()
	{
		$flags = array();
		$actions = self::manager_actions();
		$resources = array();
		foreach ($actions as $key=>$value)
		{
			$resources[] = $value['resource'];
		}
		$resources[] = 'default';
		return $resources;
	}
 
	/**
	 * 得到当前登录管理员的ID
	 * @return Int
	 */
	public static function manager_id()
	{
		$session = Session::instance();
		$manager = $session->get('Opococ_manager');
		return $manager['id'];
	}

	/**
	 * 得到当前登录管理员根帐号的ID
	 * @return Int
	 */
	public static function root_manager_id()
	{
		$session = Session::instance();
		$manager = $session->get('Opococ_manager');
		return $manager['id'];
	}


	/**
	 * set manager login info to session
	 */
	public static function set_manager_session($manager)
	{
		//保存用户信息
		if(isset($manager['is_remember']) && $manager['is_remember'] == 1)
		{
			Kohana::config_set('session.expiration',24*3600*7);
		}
		$session = Session::instance();
		$ip = tool::get_long_ip();
		//SESSION中记录用户的信息
		$s_manager['id'] = $manager['id'];
		$s_manager['username'] = $manager['username'];
		$s_manager['name'] = $manager['name'];
		$s_manager['email'] = $manager['email'];
		$s_manager['password'] = $manager['password'];
		$s_manager['is_admin'] = $manager['is_admin'];
		$s_manager['secure_code'] = md5($manager['id'].'%'.$manager['username']);
		//用户信息记入SESSION
		$session->set('Opococ_manager', $s_manager);//记录登录用户的信息
		$session->set('Opococ_manager_active_time', time());//用户最后操作时间
		$session->set('Opococ_manager_login_ip', $ip);//用户登录的IP
	}

	/**
	 * get login manager info
	 */
	public static function get_manager($session_id = NULL)
	{
		$s_manager = array();
		$s_manager['id'] = 0;
		$s_manager['username'] = "";
		$s_manager['name'] = "";
		$s_manager['email'] = "";
		$s_manager['password'] = '';
		$s_manager['secure_code'] = '';

		if(is_null($session_id))
		{
			$session = Session::instance();
		}
		else
		{
			$session = Session::instance($session_id);
		}
		$data = $session->get('Opococ_manager');
		if ($data)
		{
			$secure_code = md5($data['id'].'%'.$data['username']);
			if($secure_code == $data['secure_code'])
			{
				return $data;
			}
			else
			{
				return $s_manager;
			}
		}
		else
		{
			return $s_manager;
		}
	}
	
	/**
	 * user log in
	 *
	 * @param <String> $username
	 * @param <String> $password
	 * @param <String> $ip
	 */
	public static function log_in($username, $password, $ip = NULL)
	{
		$manager = Mymanager::instance()->get_by_username($username);
		if ($manager['id'])
		{
			if ($manager['password'] == md5($password))
			{
				if (is_null($ip))$ip = tool::get_long_ip();

				$data = array();
				$data['login_time'] = tool::db_date();
				$data['login_num'] = $manager['login_num'] + 1;
				$data['login_ip'] = $ip;
				return Mymanager::instance()->update($data);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 用户 acl对象初始化
	 * @return acl对象
	 */
	public static function acl_init()
	{
		/* 用户详情 */
		$manager = self::get_manager();
		$username = $manager["username"];
		$action_resourses = self::get_action_resources();
		
		//d($action_resourses);

		// Role 权限注册表
		$acl = Acl::instance();
		$acl->add_role($username);
		for($i=0;$i<count($action_resourses);$i++)
		{
			$acl->allow($username, null, $action_resourses[$i]);
		}
		self::_cache($acl);
		return $acl;
	}
	
	/**
	 * 缓存acl对象到session中
	 * @param AclAuth object $acl
	 * @return Boolean
	 */
	private static function _cache($acl)
	{
		$session = Session::instance();
		$data = $session->get(self::$acl_tag);		
		if(!$data)
		{
			$obj = serialize($acl);
			$session->set(self::$acl_tag, $obj);
		}		
		return true;
	}
}
