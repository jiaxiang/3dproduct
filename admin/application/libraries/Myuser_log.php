<?php defined('SYSPATH') OR die('No direct access allowed.');

class Myuser_log_Core extends My{
	protected $object_name = 'user_log';
	
	protected $data = array();

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
	 * Construct load user_log data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load user_log data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$user_log = ORM::factory('user_log',$id)->as_array();
		$this->data = $user_log;
	}

	/**
	 * get user_log data
	 *
	 * @param Array $query_struct
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @return Array
	 */
	private function _data($query_struct=array(),$orderby=NULL,$limit=1000,$offset=0)
	{
		$list = array();
		$where = array();
		$like = array();
		$in = array();

		$user_log = ORM::factory('user_log');
		//WHERE
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['where']))
			{
				foreach($query_struct['where'] as $key=>$value)
				{
					$where[$key] = $value;
				}
			}
		}
		//LIKE
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['like']))
			{
				foreach($query_struct['like'] as $key=>$value)
				{
					$like[$key] = $value;
				}
			}
		}
		//IN
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['in']))
			{
				foreach($query_struct['in'] as $key=>$value)
				{
					$in[$key] = $value;
				}
			}
		}
		//WHERE
		if(count($where) > 0)
		{
			$user_log->where($where);
		}
		//LIKE
		if(count($like) > 0)
		{
			$user_log->like($like);
		}
		//IN
		if(count($in) > 0)
		{
			foreach($in as $key=>$value)
			{
				$user_log->in($key,$value);
			}
		}

		if(!empty($orderby))
		{
			$user_log->orderby($orderby);
		}

		$orm_list = $user_log->find_all($limit,$offset);
		//var_dump($orm_list);exit;

		foreach($orm_list as $item)
		{
			$merge_arr['site_name'] = Mysite::instance()->get('name');
			$merge_arr['manager_name'] = Mymanager::instance($item->manager_id)->get('name');
            $list[] = array_merge($item->as_array(),$merge_arr);
		}

		return $list;
	}

	/**
	 * get the total number
	 *
	 * @param Array $query_struct
	 * @return Int
	 */
	function count($query_struct = array())
	{
		$user_log = ORM::factory('user_log');

		$where = array();
		$like = array();
		$in = array();

		$user_log = ORM::factory('user_log');
		//WHERE
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['where']))
			{
				foreach($query_struct['where'] as $key=>$value)
				{
					$where[$key] = $value;
				}
			}
		}
		//LIKE
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['like']))
			{
				foreach($query_struct['like'] as $key=>$value)
				{
					$like[$key] = $value;
				}
			}
		}
		//IN
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['in']))
			{
				foreach($query_struct['in'] as $key=>$value)
				{
					$in[$key] = $value;
				}
			}
		}
		//WHERE
		if(count($where) > 0)
		{
			$user_log->where($where);
		}
		//LIKE
		if(count($like) > 0)
		{
			$user_log->like($like);
		}
		//IN
		if(count($in) > 0)
		{
			foreach($in as $key=>$value)
			{
				$user_log->in($key,$value);
			}
		}

		$count = $user_log->count_all();
		return $count;
	}

	/**
	 * list user_log
	 *
	 * @param Array $query_struct
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function user_logs($query_struct = array(),$orderby=NULL,$limit=1000,$offset=0)
	{
		$list = array();

		$list = $this->_data($query_struct,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get user_log data
	 *
	 * @return Array
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
	 * add a item
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//ADD
		$user_log = ORM::factory('user_log');
		$errors = '';
		if($user_log->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $user_log->as_array();
			return TRUE;
		}
		else
	   	{
			return FALSE;
		}
	}

	/**
	 * get user_log category
	 *
	 * @return Array
	 */
	public function user_log_category()
	{
		$user_log_category_id = $this->data['user_log_category_id'];

		$user_log_category = Myuser_log_category::instance($user_log_category_id)->get();
		return $user_log_category;
	}

	/**
	 * get user_log manager
	 *
	 * @return Array
	 */
	public function manager()
	{
		$manager_id = $this->data['manager_id'];

		$manager = Mymanager::instance($manager_id)->get();
		return $manager;
	}
	
	/**
	 * 删除站点所有的信息
	 * 
	 * @param int $site_id
	 * @return boolean
	 */
	public function delete_by_site($site_id)
	{
		//删除
		$user_log = ORM::factory('user_log')->where('site_id',$site_id);
		$user_log->delete_all();
		
		return true;
	}
}
