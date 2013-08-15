<?php defined('SYSPATH') OR die('No direct access allowed.');

class Myscan_Core {
	private $data = array();
	private $error = array();

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
	 * Construct load scan data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load scan data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$scan = ORM::factory('scan',$id)->as_array();
		$this->data = $scan;
	}

	/**
	 * get scan data
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

		$scan = ORM::factory('scan');
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
			$scan->where($where);
		}
		//LIKE
		if(count($like) > 0)
		{
			$scan->like($like);
		}
		//IN
		if(count($in) > 0)
		{
			$scan->in($in);
		}

		if(!empty($orderby))
		{
			$scan->orderby($orderby);
		}

		$orm_list = $scan->find_all($limit,$offset);

		foreach($orm_list as $item)
		{
            $list[] = $item->as_array();
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
		$scan = ORM::factory('scan');

		$where = array();
		$like = array();
		$in = array();

		$scan_tempate = ORM::factory('scan');
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
			$scan->where($where);
		}
		//LIKE
		if(count($like) > 0)
		{
			$scan->like($like);
		}
		//IN
		if(count($in) > 0)
		{
			$scan->in($in);
		}

		$count = $scan->count_all();
		return $count;
	}

	/**
	 * list scan
	 *
	 * @param Array $query_struct
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function scans($query_struct = array(),$orderby=NULL,$limit=1000,$offset=0)
	{
		$list = array();

		$list = $this->_data($query_struct,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get scan_templat data
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
	 * add a scan
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//ADD
		$scan = ORM::factory('scan');		
		$errors = '';
		if($scan->validate($data ,TRUE ,$errors))
		{
			$this->data = $scan->as_array();                        
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * edit a scan item
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function _edit($id,$data)
	{
		$id = intval($id);
		//TODO EDIT
		$scan = ORM::factory('scan',$id);
		if(!$scan->loaded)
		{
			return FALSE;
		}
		//TODO
		$errors = '';
		if($scan->validate($data ,TRUE ,$errors))
		{
			$this->data = $scan->as_array();
			return TRUE;
		}
		else
		{
			var_dump($errors);exit;
			return FALSE;
		}
	}

	/**
	 * edit a item
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function edit($data)
	{
		$id = $this->data['id'];                
		return $this->_edit($id,$data);
	}

	/**
	 * edit item by id
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function edit_by_id($id,$data)
	{
		$id = intval($id);
		return $this->_edit($id,$data);
	}

	/**
	 * delete a scan category by id
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function _delete($id)
	{
		$id = intval($id);
		$scan = ORM::factory('scan',$id);
		if(!$scan->loaded)
		{
			return false;
		}

		$scan->delete();
		return true;
	}

	/**
	 * delete a scan category
	 *
	 * @return boolean
	 */
	public function delete()
	{
		$id = $this->data['id'];
		return $this->_delete($id);
	}

    /**
     * get api error
     *
     * @return Array
     */
    public function error()
    {
        $result = '';
        if(count($this->error))
        {
            $result     = '<br />';
            foreach($this->error as $key=>$value)
            {
                $result .= ($key+1).' . '.$value.'<br />';
            }
        }
        return $result;
    }
}
