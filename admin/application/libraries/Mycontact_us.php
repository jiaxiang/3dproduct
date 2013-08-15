<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mycontact_us_Core extends My{
	protected $object_name = "contact_us";
	protected $data = array();
	protected $orm_form = 'contact_us';
	private $errors = NULL;

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
	 * Construct load order_product data
	 *
	 * @param Int $id
	 */
	/*public function __construct($id)
	{
		$this->_load($id);
	}
	*
	 * load order_product data
	 *
	 * @param Int $id
	 
	private function _load($id)
	{
		$id = intval($id);

		$data = ORM::factory($this->orm_form,$id)->as_array();
		$this->data = $data;
	}*/
    
    //初始化对象数据
	private function _init_orm($id)
	{
        $this->_load($id);
        return $this->orm;
	}

	/**
	 * get order_product data
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	private function _data($where = NULL,$orderby = NULL,$limit = 0,$offset = 100)
	{
		$list = array();

		$orm = ORM::factory($this->orm_form);
		//改为模糊搜索
		if(!empty($where))
		{
			$orm->like($where);
		}
		/*
		if(!empty($where))
		{
			$orm->where($where);
		}*/

		if(!empty($orderby))
		{
			$orm->orderby($orderby);
		}

		$orm_list = $orm->find_all($limit,$offset);

        $list = array();
		foreach($orm_list as $key=>$rs)
		{
            $list[$key] = $rs->as_array();
		}

		return $list;
	}

	/**
	 * get the total number
	 *
	 * @param Array $where
	 * @param Array $in
	 * @return Int
	 */
	function count($where=NULL)
	{
		$orm = ORM::factory($this->orm_form);
		//改为模糊搜索
		if(!empty($where))
		{
			$orm->like($where);
		}
		/*
		if(!empty($where))
		{
			$orm->where($where);
		}*/
		$count = $orm->count_all();
		return $count;
	}

	/**
	 * list order_product
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function contact_uses($where=NULL,$in=NULL,$orderby=NULL,$limit=100,$offset=0)
	{
		$list = $this->_data($where,$in,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get order_product data
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
	 * add a order_product
	 *
	 * @param Int $id
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//ADD
		$orm = ORM::factory($this->orm_form);
		//TODO
		if($orm->validate($data ,TRUE ,$errors = ''))
		{
			$this->data = $orm->as_array();
			return TRUE;
		}
		else
		{
			$this->errors = $errors;
			return FALSE;
		}
	}

	/**
	 * edit return_message
	 *
	 * @param Int $id
	 * @param Array $data
	 * @return bool
	 */
	public function edit($data)
	{
		$id = $this->data['id'];
        $orm = & $this->orm;
		if(!$orm->loaded || !is_array($data))
		{
            return FALSE;
		}
        foreach($data as $k=>$v)
        {
            array_key_exists($k, $this->data) && $orm->$k = $v;
        }
        $orm->save();
		if($orm->saved == TRUE)
		{
			$this->data = $orm->as_array();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
    
	/**
	 * edit a order_product
	 *
	 * @param Int $id
	 * @param Array $data
	 * @return Array
	 */
	public function vedit($data)
	{
		$id = $this->data['id'];
		//EDIT
		$orm = ORM::factory($this->orm_form,$id);
		if(!$orm->loaded)
		{
			return FALSE;
		}
		//TODO
		if($orm->validate($data ,TRUE ,$errors = ''))
		{
			$this->data = $orm->as_array();
			return TRUE;
		}
		else
		{
			$this->errors = $errors;
			return FALSE;
		}
	}

    /**
     * 是否已经存在
     * @param <array> $args
     * @return <boolean>
     */
    public function email_exist($data)
    {
		$where = array();
		$where['email']		=$data['email'];
		$count = $this->count($where);
        //TODO
        if($count>0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

	/**
	 * get errors
	 */
	public function errors()
	{
		return $this->errors;
	}
	/*
	 * 删除联系我们信息
	 */
	public function delete()
	{
        $orm = & $this->orm;
		if(!$orm->loaded)
		{
			return FALSE;
		}
		$orm->delete();
		return TRUE;
	}
}
