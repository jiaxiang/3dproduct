<?php defined('SYSPATH') or die('No direct script access.');

class Mysettlelog_Core extends My
{
	//对象名称(表名)
    protected $object_name = 'ag_settle_log';
    
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
	
	public function mylists2($limit,$offset)
	{
		$agents = ORM::factory('ag_settle_log')
			->find_all($limit,$offset);
		$data = array();
		foreach($agents as $agent)
		{
			$data[] = $agent->as_array();
		}
		return $data;
	}
	/**
	 * 
	 */
	public function count_itmes()
	{
		return ORM::factory('ag_settle_log')->count_all();
	}
	
	public function agent_exist($data)
	{
		$where = array();
		$where['user_id'] = $data['user_id'];
		
		$count = ORM::factory('ag_settle_log')->where($where)->count_all();
		
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
		$user = ORM::factory('ag_settle_log')->where($where)->find();
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
		$user = ORM::factory('ag_settle_log')->where($where)->find();
		if($user->loaded){
			return $user->as_array();
		}else{
			return false;
		}
	}
}

?>