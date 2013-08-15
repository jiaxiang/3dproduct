<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mydomain_api_Core {
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
	 * Construct load domain_api data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load domain_api data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$domain_api = ORM::factory('domain_api',$id)->as_array();
		$this->data = $domain_api;
	}

	/**
	 * get domain_api data
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @return Array
	 */
	private function _data($where=NULL,$orderby=NULL,$limit=0,$offset=1000)
	{
		$list = array();

		$domain_api = ORM::factory('domain_api');
		if(!empty($where))
		{
			$domain_api->where($where);
		}

		if(!empty($orderby))
		{
			$domain_api->orderby($orderby);
		}

		$orm_list = $domain_api->find_all($limit,$offset);

		foreach($orm_list as $item)
		{
            $list[] = $item->as_array();
		}

		return $list;
	}

	/**
	 * get the total number
	 *
	 * @param Array $where
	 * @return Int
	 */
	function count($where=NULL)
	{
		$domain_api = ORM::factory('domain_api');

		if(!empty($where))
		{
			$domain_api->where($where);
		}

		$count = $domain->count_all();
		return $count;
	}

	/**
	 * list domain
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @return Array
	 */
	public function domain_apis($where=NULL,$orderby=NULL,$limit=100,$offset=0)
	{
		$list = $this->_data($where,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get domain_api data
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
	 * get domain_api prices
	 *
	 * @return Array
	 */
	public function prices()
	{
		$list = array();
		$id = $this->data['id'];

		$domain_api_prices = ORM::factory('domain_api',$id)->domain_api_prices;
		foreach($domain_api_prices as $item)
		{
			$list[] = $item->as_array();
		}
		return $list;
	}
}
