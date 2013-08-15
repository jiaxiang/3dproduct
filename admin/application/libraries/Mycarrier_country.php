<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mycarrier_country_Core {
	private $data = array();
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
	 * Construct load carrier_country data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}
	/**
	 * load carrier_country data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$carrier_country = ORM::factory('carrier_country',$id)->as_array();
		$this->data = $carrier_country;
	}

	/**
	 * get carrier_country data
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	private function _data($where = NULL,$in=NULL,$orderby = NULL,$limit = 0,$offset = 1000)
	{
		$list = array();

		$orm = ORM::factory('carrier_country');
		if(!empty($where))
		{
			$orm->where($where);
		}

		if(!empty($in))
		{
			$orm->in('site_id',$in);
		}
		if(!empty($orderby))
		{
			$orm->orderby($orderby);
		}

		$orm_list = $orm->find_all($limit,$offset);

        $list = array();
		foreach($orm_list as $key=>$rs)
		{
            $list[$key] = $rs->as_array();
            $list[$key]['site'] = Mysite::instance($rs->site_id)->get();
            $list[$key]['carrier'] = Mycarrier::instance($rs->carrier_id)->get();
            $list[$key]['country'] = Mycountry::instance($rs->country_id)->get();
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
	function count($where=NULL,$in=NULL)
	{
		$orm = ORM::factory('carrier_country');
		if(!empty($where))
		{
			$orm->where($where);
		}
		if(!empty($in))
		{
			$orm->in('site_id',$in);
		}
		$count = $orm->count_all();
		return $count;
	}

	/**
	 * list carrier_country
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function carrier_countries($where=NULL,$in=NULL,$orderby=NULL,$limit=100,$offset=0)
	{
		$list = $this->_data($where,$in,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get carrier_country data
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
	 * add a carrier
	 *
	 * @param Int $id
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//ADD
		$orm = ORM::factory('carrier_country');
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
	 * edit a carrier_country
	 *
	 * @param Int $id
	 * @param Array $data
	 * @return Array
	 */
	public function edit($data)
	{
		$id = $this->data['id'];
		//EDIT
		$orm = ORM::factory('carrier_country',$id);
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
	 *
     * @param <array> $args
     * @return <boolean>
     */
    public function exist($data)
    {
		$where = array();
        $where['site_id']	=$data['site_id'];
        $where['carrier_id']	=$data['carrier_id'];
        $where['country_id']	=$data['country_id'];
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
	/**
	 * 删除物流国家关联
	 */
	public function delete()
	{
		$id = $this->data['id'];

		$orm = ORM::factory('carrier_country',$id);
		if(!$orm->loaded)
		{
			return FALSE;
		}
		$orm->delete();
		return TRUE;
	}
	/**
	 * 根据国家ID，删除当前站点的所有关联
	 *
	 * @param site_id $site_id
	 * @param country_id $country_id
     * @return Boolean
	 */
    public function delete_by_country_id($site_id,$country_id)
    {
        ORM::factory('carrier_country')
            ->where('site_id',$site_id)
            ->where('country_id',$country_id)
            ->delete_all();
        return TRUE;
    }
}
