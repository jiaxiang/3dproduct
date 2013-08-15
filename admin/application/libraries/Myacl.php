<?php defined('SYSPATH') OR die('No direct access allowed.');

class Myacl_Core {
	private $data;
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
	 * Construct load site data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load site data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$acl = ORM::factory('acl',$id)->as_array();
		$this->data = $acl;
	}

    /**
     * get acl actions
	 *
     * @return Array
     */
	function actions() 
	{
		$list = array();        
        if($this->data['permissions'])
        {
        	$action = ORM::factory('action');
            $in = explode(",", $this->data['permissions']);
            $action->in('id', $in);
	        $actions = $action->find_all();
			foreach($actions as $item)
			{
				$list[] = $item->as_array();
			}
        }
        return $list;
    }

	/**
	 * get role data
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	private function _data($where=NULL,$orderby=NULL,$limit=0,$offset=1000)
	{
		$list = array();

		$acl = ORM::factory('acl');
		if(!empty($where))
		{
			$acl->where($where);
		}

		if(!empty($orderby))
		{
			$acl->orderby($orderby);
		}

		$orm_list = $acl->find_all($limit,$offset);

		foreach($orm_list as $item)
		{
            //$merge_arr = array('count'=>count($item->managers));
			//$list[] = array_merge($item->as_array(),$merge_arr);
			$list[] = $item->as_array();
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
	function count($where=NULL , $in=NULL)
	{
		$acl = ORM::factory('acl');

		if(!empty($where))
		{
			$acl->where($where);
		}

		if(!empty($in))
		{
			$acl->in($in);
		}

		$count = $acl->count_all();
		return $count;
	}

	/**
	 * get site data
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
