<?php defined('SYSPATH') or die('No direct script access.');

class Myrelation_Core extends My
{
	//对象名称(表名)
    protected $object_name = 'ag_relation';
    
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
	public function relations($limit,$offset)
	{
		$realtime_contracts = ORM::factory('ag_relation')	
			->find_all($limit,$offset);
		$data = array();
		foreach($realtime_contracts as $contract)
		{
			$data[] = $contract->as_array();
		}
		return $data;
	}
	/**
	 * 站点新闻数据
	 */
	public function count_relations()
	{
		return ORM::factory('ag_relation')->count_all();
	}
	
	public function count_agent_client($agentId)
	{
		return ORM::factory('ag_relation')->where('agentid',$agentId)->count_all();
	}
	
	/**
	 * 一个用户只能成为一个代理的下级用户
	 * 在 ag_relations 中userid的值是唯一的
	 * @param unknown_type $data
	 */
	public function relation_exist($data)
	{
		$where = array();
		$where['user_id'] = $data['user_id'];
		$count = ORM::factory('ag_relation')->where($where)->count_all();
         return ($count > 0) ? true : false;
	}
	
	public function user_has_been_client($user_id)
	{
		$where = array();
		$where['user_id'] = $user_id;
		$count = ORM::factory('ag_relation')->where($where)->count_all();
        return ($count > 0) ? true : false;
	}	
	
	public function get_by_id($relation_id)
	{
		if(empty($relation_id)){
			return false;
		}
		$where = array();
		$where['id'] = $relation_id;
		$relation = ORM::factory('ag_relation')->where($where)->find();
		if($relation->loaded){
			return $relation->as_array();
		}else{
			return false;
		}
	}
	
	public function get_by_agentid_userid($agentId, $userId)
	{
		if(empty($agentId) || empty($userId)){
			return false;
		}
		$where = array();
		$where['agentid'] = $agentId;
		$where['user_id'] = $userId;
		$relation = ORM::factory('ag_relation')->where($where)->find();
		if($relation->loaded){
			return $relation->as_array();
		}
		return false;
	}
	
	public function is_client_user($userid) {
		if(empty($userid)){
			return false;
		}
		$where = array();
		$where['user_id'] = $userid;
		$relation = ORM::factory('ag_relation')->where($where)->find();
		if($relation->loaded){
			return $relation->as_array();
		}else{
			return false;
		}
	}
	
	public function get_by_userid($userId)
	{
		if(empty($userId)){
			return false;
		}
		$where = array();
		$where['user_id'] = $userId;
		$relation = ORM::factory('ag_relation')->where($where)->find();
		if($relation->loaded){
			return $relation->as_array();
		}
		return false;
	}
	
	/**
	 * 收索代理的下级用户
	 * @param $query_struct
	 * @param agent_client_list
	 */
	public function mylists($query_struct=array())
	{
		$list = array();
		$orm_instance = ORM::factory('ag_relation')
    	        ->select('users.*, ag_agents.agent_type, '
    	        		.'ag_relations.id as relationId, '
						.'ag_relations.client_type, '
						.'ag_relations.client_rate, '
						.'ag_relations.client_rate_beidan')
    	        ->join('users', 'users.id', 'ag_relations.user_id', 'LEFT')
    	        ->join('ag_agents', 'ag_agents.user_id', 'ag_relations.user_id', 'LEFT')
    	        ->where('ag_relations.agentid',$query_struct['where']['agentid']);
    	// 处理输入条件
		$where = array();
		$in = array();
		if(isset($query_struct['where'])&&is_array($query_struct['where']))
		{
			foreach($query_struct['where'] as $key=>$condition)
			{
				if(is_array($condition))
				{
					$in[$key] = $condition;
				}
				else
				{
					$where[$key] = $condition;
				}
			}
		}
		// 处理 where 模块
		if(!empty($where))
		{
			$orm_instance->where($where);
		}
		//处理IN条件
		if(!empty($in))
		{
			foreach($in as $in_key=>$in_val)
			{
				$orm_instance->in($in_key,$in_val);
			}
		}
		//处理传入in条件
		if(isset($query_struct['in'])&&is_array($query_struct['in']))
		{
			foreach($query_struct['in'] as $key=>$value)
			{
				$orm_instance->in($key,$value);
			}
		}
		// 处理 like 模块
		if(isset($query_struct['like'])&&is_array($query_struct['like'])&&count($query_struct['like']))
		{
			$orm_instance->like($query_struct['like']);
		}
		// 处理 orlike 模块
		if(isset($query_struct['orlike'])&&is_array($query_struct['orlike'])&&count($query_struct['orlike']))
		{
			$orm_instance->orlike($query_struct['orlike']);
		}		
		// 处理 orderby 模块
		if(isset($query_struct['orderby'])&&is_array($query_struct['orderby'])&&count($query_struct['orderby']))
		{
			$orm_instance->orderby($query_struct['orderby']);
		}
		//处理limit条件，无条件最多查询1000条数据
		$limit = isset($query_struct['limit']['per_page']) ? $query_struct['limit']['per_page'] : 1000;
		$offset = isset($query_struct['limit']['offset']) ? $query_struct['limit']['offset'] : 0;
		$orm_list = $orm_instance->find_all($limit,$offset);
		//得到返回结构体
		foreach($orm_list as $item)
		{
			$list[] = $item->as_array();
		}
		return $list;
	}
	
	public function set_client_rate($relationId, $client_rate)
	{
		$where = array('id'=>$relationId);
		$obj = ORM::factory('ag_relation')->where($where)->find();
		if($obj->loaded)
		{
			$obj->client_rate = $client_rate;
			return $obj->save();
		}
		return false;
	}
	
}

?>
