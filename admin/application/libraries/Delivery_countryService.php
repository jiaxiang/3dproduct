<?php defined('SYSPATH') OR die('No direct access allowed.');

class Delivery_countryService_Core extends DefaultService_Core {
	
	/* 兼容php5.2环境 Start */
    private static $instance = NULL;
    // 获取单态实例
    public static function get_instance()
    {
        if(self::$instance === null)
        {
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    
	/**
	 * 获取所有国家的信息
	 *
	 * @return array
	 */
	public function get_countries()
	{
		$vals = array ();
		$results = ORM::factory('country')
			->find_all();
		if(!empty($results))
		{
			foreach($results as $key => $val)
			{
				$result[] = $val->as_array();
			}
			foreach($result as $val)
			{
				$vals[$val['id']] = $val;
			}
		}		
		return $vals;
	}
	
	/**
	 * 根据delivery_id得到国家的物流信息
	 *
	 * @param int $delivery_id
	 * @return array
	 */
	public function get_delivery_countries_by_id($delivery_id)
	{
		$results = array();
		$result = array();
		$delivery_countries = ORM::factory('delivery_country')
			->where('delivery_id',$delivery_id)
			->orderby('position', 'ASC')
			->orderby('id', 'ASC')
			->find_all();
		foreach($delivery_countries as $value)
		{
			$results[] = $value->as_array();
		}
		foreach($results as $key=>$val)
		{
			$result[$val['position']][] = $val;
		}
		ksort($result);
		$result = array_merge($result);
		return $result;
	}
	
	/**
	 * 根据条件获得物流涉及的国家，按position排序
	 *
	 * @param int $delivery_id
	 * @return array
	 */
	public function get_delivery_countries_by_position($query_struct)
	{
		$results = array();
		$result = array();
		$delivery_countries = $this->query_assoc($query_struct);
		
		foreach($delivery_countries as $key=>$value)
		{
			if(Mycountry::instance($value['country_id'])->get('active') == 0)
			{
				$delivery_countries[$key]['disable'] = true;
			}
		}
	
		return $delivery_countries;
	}
	
	/**
	 * 根据delivery_id删除某条物流下的国家关系
	 *
	 * @param int $delivery_id
	 * @return array
	 */
	public function delete_delivery_countries_by_delivery_id($delivery_id)
	{
		$orm = ORM::factory('delivery',$delivery_id);
		if(!$orm->loaded)
		{
			return FALSE;
		}
		//国家物流关系删除
		ORM::factory('delivery_country')->where('delivery_id',$orm->id)->delete_all();
		return TRUE;
	}	
	
   /**
	 * 根据删除国家的物流信息
	 * 
	 * @param  int $country_id
	 * @return boolean
	 */
	
	public function delete_delivery_by_country($country_id)
	{
		return ORM::factory('delivery_country')->where('country_id',$country_id)->delete_all();	
	}
}    
?>
