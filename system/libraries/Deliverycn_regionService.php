<?php defined('SYSPATH') OR die('No direct access allowed.');

class Deliverycn_regionService_Core extends DefaultService_Core {
	
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
	 * 获取所有一级地区的信息
	 *
	 * @return array
	 */
	public function get_regions()
	{
		$vals = array ();
		$results = ORM::factory('region')
			->where('p_region_id',0)->find_all();
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
	 * 根据delivery_id得到地区的物流信息
	 *
	 * @param int $delivery_id
	 * @return array
	 */
	public function get_delivery_regions_by_id($deliverycn_id)
	{
		$results = array();
		$result = array();
		$delivery_regions = ORM::factory('deliverycn_region')
			->where('deliverycn_id',$deliverycn_id)
			->orderby('position', 'ASC')
			->orderby('id', 'ASC')
			->find_all();
		foreach($delivery_regions as $value)
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
	public function get_delivery_regions_by_position($query_struct)
	{
		$results = array();
		$result = array();
		$deliverycn_regions = $this->query_assoc($query_struct);
		
		foreach($deliverycn_regions as $key=>$value)
		{
			if(Myregion::instance($value['region_id'])->get('disabled') == false)
			{
				$deliverycn_regions[$key]['disable'] = true;
			}
		}
	
		return $deliverycn_regions;
	}
	
	/**
	 * 根据deliverycn_id删除某条物流下的国家关系
	 *
	 * @param int $delivery_id
	 * @return array
	 */
	public function delete_delivery_regions_by_deliverycn_id($deliverycn_id)
	{
		$orm = ORM::factory('deliverycn',$deliverycn_id);
		if(!$orm->loaded)
		{
			return FALSE;
		}
		//地区物流关系删除
		ORM::factory('deliverycn_region')->where('deliverycn_id',$orm->id)->delete_all();
		return TRUE;
	}	
	
   /**
	 * 根据删除地区的物流信息
	 * 
	 * @param  int $country_id
	 * @return boolean
	 */
	
	public function delete_delivery_by_region($region_id)
	{
		return ORM::factory('deliverycn_region')->where('region_id',$region_id)->delete_all();	
	}
}    
?>
