<?php defined('SYSPATH') or die('No direct script access.');

class Myagent_Core extends My
{
	//对象名称(表名)
    protected $object_name = 'ag_agent';
    
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
	 * 得到站点新闻列表
	 */
	public function agents($limit,$offset)
	{
		$agents = ORM::factory('ag_agent')	
			->find_all($limit,$offset);
		$data = array();
		foreach($agents as $agent)
		{
			$data[] = $agent->as_array();
		}
		return $data;
	}
	/**
	 * 站点新闻数据
	 */
	public function count_agents()
	{
		return ORM::factory('ag_agent')->count_all();
	}
	
	public function agent_exist($data)
	{
		$where = array();
		$where['user_id'] = $data['user_id'];
		
		$count = ORM::factory('ag_agent')->where($where)->count_all();
		
        //TODO
        if($count > 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
	}
	
	public function get_by_user_id($user_id)
	{
		if(empty($user_id)){
			return false;
		}
		$where = array();
		$where['user_id'] = $user_id;
		$user = ORM::factory('ag_agent')->where($where)->find();
		if($user->loaded){
			return $user->as_array();
		}else{
			return false;
		}
	}
	
	public function get_by_id($id)
	{
		if(empty($id)){
			return false;
		}
		$where = array();
		$where['id'] = $id;
		$user = ORM::factory('ag_agent')->where($where)->find();
		if($user->loaded){
			return $user->as_array();
		}else{
			return false;
		}
	}
	
	public function get_by_invite_code($invite_code)
	{
		if(empty($id)){
			return false;
		}
		$where = array();
		$where['invite_code'] = $invite_code;
		$user = ORM::factory('ag_agent')->where($where)->find();
		if($user->loaded){
			return $user->as_array();
		}else{
			return false;
		}
	}
}

?>
