<?php defined('SYSPATH') OR die('No direct access allowed.');

class Myuser_Core extends My {
	protected $object_name = "user";
	protected $data = array();
	protected $errors = NULL;
	protected static $instances;
	public static $users;
	public static $default_user_score_formula = 's';
	
	
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
	 * get user select list
	 *
	 * @param array $in
	 * @return array
	 */
	public function select_list($in = NULL)
	{
		$list = array();

		$orm = ORM::factory('user');

		if(!empty($in))
		{
			$orm->in($in);
		}

		$list = $orm->select_list('id','email');

		return $list;
	}
	
	/**
	 * 通过站点ID和邮件得到用户详情
	 * @param String $email
	 * @return Array
	 */
	public function get_by_email($email)
	{
		if(empty($email))
		{
			return false;
		}
		$where = array();
		$where['email'] = $email;
		$user = ORM::factory('user')->where($where)->find();
		if($user->loaded)
		{
			return $user->as_array();
		} else {
			return false;
		}
	}
	
	//根据id获取用户详细信息
	public function get_by_id($id){
		if(empty($id)){
			return false;
			}
		$where = array();
		$where['id'] = $id;
		$user = ORM::factory('user')->where($where)->find();
		if($user->loaded){
			return $user->as_array();
			}else{
				return false;
				}
		}
	
    /**
     * 用户是否存在
     * @param <array> $args
     * @return <boolean>
     */
    public function user_exist($data)
    {
		$where = array();
		$where['email'] = $data['email'];
		
		$count = ORM::factory('user')->where($where)->count_all();
		
        //TODO
        if($count > 0)
        {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
	public static function edit_handsel_users($uid, $data)
	{
		$uid = intval($uid);
		$user = ORM::factory('user', $uid);
		if($user->validate($data, TRUE))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * list status
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function user_ids($where=NULL,$orderby=NULL,$limit=100,$offset=0)
	{
		$list = $this->_data($where,$orderby,$limit,$offset);
		$res  = array();
		foreach($list as $key=>$rs){
			if(isset($rs['id']))
			{
				$res[] = $rs['id'];
			}
		}
		return $res;
	}

	/**
	 * 批量软删除用户
	 */
	public function set_inactive($id = 0, $status=0, $level_id=0)
	{
		$user = ORM::factory('user',$id);
		if($user->loaded == true)
		{
			$user->active = $status;
			$user->level_id = $level_id;
			$user->save();
			return $user->saved;
		}
		else
		{
			return false;
		}
	}
	/**
	 * 跟新用户的数据 
	 */
	public function set($id,$request_data)
	{
		$orm_instance = ORM::factory('user', $id);
		if($orm_instance->loaded != false)
    	{
    		$data = $orm_instance->as_array();
    		
    		foreach($request_data as $key=>$value)
    		{
    			array_key_exists($key, $data) && $orm_instance->$key = $value;
    		}
			return $orm_instance->save();
    	}
	}
    /**
     * 计算储存用户的积分并刷新用户的等级
     * @param int $user_id
     * @param string $action_type
     * @param int $value
     */
    public function store_score_refresh_level($user_id, $action_type, $value)
    {
    	if(empty(self::$users[$user_id]))
    	{
	    	$users = $this->lists(array('where'=>array('id'=>$user_id)));
			self::$users[$user_id] = !empty($users)?$users[0]:NULL;
    	}
		$user = self::$users[$user_id];
		if(!empty($user))
		{
			//计算加到的积分
	    	$add_score = $this->calculate_score($action_type, $value);
			//储存积分
	    	if($add_score != 0)
			{
				$user['score'] += $add_score;
				$this->set($user_id, array('score'=>$user['score']));
				self::$users[$user_id]['score'] = $user['score'];
			}
			$this->refresh_user_level($user_id);
			return true;
		}else{
			return false;
		}
    }

    /**
     * 刷新用户的等级
     * @param int $user_id
     */
    public function refresh_user_level($user_id)
    {
    	if(empty(self::$users[$user_id]))
    	{
	    	$users = $this->lists(array('where'=>array('id'=>$user_id)));
			self::$users[$user_id] = !empty($users)?$users[0]:NULL;
    	}
		$user = self::$users[$user_id];
		
    	if(empty($user))
    	{
    		return false;
    	}else{
    		//取得level的信息
    		$query_struct = array(
    			'where'=>array(
    				//'site_id'=>$user['site_id'],
    			),
    			'orderby'=>array(
    				'score'=>'ASC',
    			),
    		);
    		$user_levels = User_levelService::get_instance()->index($query_struct);
    		//判断用户级别是否为特殊等级
    		foreach($user_levels as $user_level)
    		{
    			//特殊等级不能够自动的升级
    			if($user_level['id'] == $user['level_id'] && $user_level['is_special'])
    			{
    				return false;
    			}
    		}
    		//普通等级自动升级
    		$user_level_id = $user['level_id'];
    		foreach($user_levels as $user_level)
    		{	
    			if(!$user_level['is_special'])
    			{
    			 	if($user['score'] >= $user_level['score'])
	    			{
	    				$user_level_id = $user_level['id'];
	    			}
	    			if($user['score'] < $user_level['score'])
	    			{
	    				break;
	    			}
    			}
    		}
    		if($user_level_id != $user['level_id'])
    		{
    			$this->set($user_id, array('level_id'=>$user_level_id));
    		}
    	}
    }
	/**
	 * 计算积分的值
	 * @param $action_type
	 * @param $value
	 */
	public function calculate_score($action_type, $value)
	{
		//取得积分的公式
		$site_detail = Mysite_detail::instance()->get();
		$score_formula = $site_detail['user_score_formula'];
		empty($score_formula) && $score_formula = self::$default_user_score_formula;
		// 计算积分
		$score_formula = str_replace($action_type, $value, $score_formula);
		
		$score_formula = preg_replace('/[a-zA-Z]/',0,$score_formula);
		eval(" \$score = $score_formula;");
		return $score;
	}

	
}
