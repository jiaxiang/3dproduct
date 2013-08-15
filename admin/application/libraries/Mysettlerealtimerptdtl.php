<?php defined('SYSPATH') or die('No direct script access.');

class Mysettlerealtimerptdtl_Core extends My
{
	//对象名称(表名)
    protected $object_name = 'ag_settle_realtime_dtl';
    
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
	public function count_itmes()
	{
		return ORM::factory('ag_settle_realtime_dtl')->count_all();
	}
	
	public function agent_exist($data)
	{
		$where = array();
		$where['user_id'] = $data['user_id'];
		
		$count = ORM::factory('ag_settle_realtime_dtl')->where($where)->count_all();
		
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
		$user = ORM::factory('ag_settle_realtime_dtl')->where($where)->find();
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
		$user = ORM::factory('ag_settle_realtime_dtl')->where($where)->find();
		if($user->loaded){
			return $user->as_array();
		}else{
			return false;
		}
	}
	public function mylists($query_struct=array(), $count01 = 0)
	{
		$list = array();
		$orm_instance = ORM::factory('ag_settle_realtime_dtl')
    	        ->select('uuu.lastname as clientlastname, agent.lastname as agentlastname, ag_settle_realtime_dtls.*')
    	        ->join('users as agent', 'agent.id', 'ag_settle_realtime_dtls.agentid', 'LEFT')
    	        ->join('users as uuu', 'uuu.id', 'ag_settle_realtime_dtls.user_id', 'LEFT');
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
		//$count01 = $orm_instance->count_last_query();
		return $list;
	}
	public function count_items2($query_struct=array())
	{
		$list = array();
		$orm_instance = ORM::factory('ag_settle_realtime_dtl')
    	        ->select('count(1)')
    	        ->join('users as agent', 'agent.id', 'ag_settle_realtime_dtls.agentid', 'LEFT')
    	        ->join('users as uuu', 'uuu.id', 'ag_settle_realtime_dtls.user_id', 'LEFT')
    	        ;
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
		$orm_ret = $orm_instance->count_all();
		return $orm_ret;
	}

	public function sum_items($query_struct=array(), $m_flag = 90)
	{
		$list = array();
		$orm_instance = ORM::factory('ag_settle_realtime_dtl')
    	        ->select('sum(ag_settle_realtime_dtls.fromamt) asum, sum(ag_settle_realtime_dtls.client_retamt) bsum')
    	        ->join('users as agent', 'agent.id', 'ag_settle_realtime_dtls.agentid', 'LEFT')
    	        ->join('users as uuu', 'uuu.id', 'ag_settle_realtime_dtls.user_id', 'LEFT')
    	        ->where('ag_settle_realtime_dtls.flag<>'.$m_flag);
    	// 处理输入条件
		$where = array();
		$in = array();
		if(isset($query_struct['where'])&&is_array($query_struct['where']))
		{
			foreach($query_struct['where'] as $key=>$condition)
			{
				if(is_array($condition)){
					$in[$key] = $condition;
				}else{
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
		$orm_list = $orm_instance->find_all();
		//得到返回结构体
		foreach($orm_list as $item)
		{
			$list[] = $item->as_array();
		}
		return $list;
	}
}

?>
