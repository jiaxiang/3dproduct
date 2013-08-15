<?php defined('SYSPATH') OR die('No direct access allowed.');

class Acltool_Core {
	public static $acl_tag = "admin_acl";
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
	
	public static function is_allowed($permission = 'default', $site_id = 0, $type = NULL)
	{
		$verify = true;
		$site_ids = role::get_site_ids($type);

        //超级管理员root不需要检查权限 zhu modify 
		if(role::is_root()){
			$verify = true;
		}
		else
		{
			if ($site_id > 0 && !in_array($site_id, $site_ids))
			{
				$verify = false;
			}
			
			$acl = Session::instance()->get(self::$acl_tag);
			if($acl)
			{
				$acl = unserialize($acl);
			}
			else
			{
				$acl = self::acl_init();
			}
			$manager = role::get_manager();
		    $verify = $acl->is_allowed($manager["username"], $permission);
		}
        
		//验证操作
		if($verify)
		{
			return $site_ids;
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
	 * 用户 acl对象初始化
	 * @return acl
	 */
	public static function acl_init()
	{
		/* 用户详情 */
		$manager = role::get_manager();
		$username = $manager["username"];
		$action_resourses = role::get_action_resources();
			
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