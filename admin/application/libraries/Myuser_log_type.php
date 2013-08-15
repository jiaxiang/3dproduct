<?php defined('SYSPATH') OR die('No direct access allowed.');

class Myuser_log_type_Core {
	private $data = array();

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
	 * Construct load user_log_type data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load user_log_type data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$user_log_type = ORM::factory('user_log_type',$id)->as_array();
		$this->data = $user_log_type;
	}

    /**
     * get user_log_type data
	 *
     * @param Array $where
     * @param String $orderby
     * @param Int $limit
     * @param Int $offset
     * @return Array
     */
	private function _data($where=NULL,$in=NULL,$orderby=NULL,$limit=0,$offset=1000)
	{
		$list = array();

		$user_log_type = ORM::factory('user_log_type');
		if(!empty($where))
		{
			$user_log_type->where($where);
		}

		if(!empty($in))
		{
			$user_log_type->in($in);
		}

		if(!empty($orderby))
		{
			$user_log_type->orderby($orderby);
		}

		$orm_list = $user_lot_type->find_all($limit,$offset);

		foreach($orm_list as $item)
		{
			$list[] = $item->as_array();
		}

		return $list;
	}

	/**
	 * get the user_log_type number
	 *
	 * @param Array $where
	 * @param Array $in
	 * @return Int
	 */
	function count($where=NULL,$in=NULL) {
		$user_log_type = ORM::factory('user_log_type');

		if(!empty($where))
		{
			$user_log_type->where($where);
		}

		if(!empty($in))
		{
			$user_log_type->in($in);
		}

		$count = $user_log_type->count_all();
		return $count;
	}

    /**
     * user_log_type list
	 *
     * @param Array $where
     * @param String $orderby
     * @param Int $limit
     * @param Int $offset
     * @return Array
     */
	private function user_log_types($where=NULL,$in=NULL,$orderby=NULL,$limit = 100,$offset=0)
	{
		$list = $this->_data($where,$in,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get user_log_type data
	 *
	 * @return Array
	 */
	public function get()
	{
		return $this->data;
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
		$user_log_type = ORM::factory('user_log_type');
		$errors = '';
		if($user_log_type->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $user_log_type->as_array();
			return TRUE;
		}
		else
	   	{
			return FALSE;
		}
	}
}
