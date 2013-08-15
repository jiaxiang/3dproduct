<?php defined('SYSPATH') or die('No direct access allowed.');

class Mycountry_Core extends My{
	protected $object_name = 'country';
	protected $data = array();
	protected $errors = NULL;
	
	private static $instances;

	public static function &instance($id = 0)
	{
		if(!isset(self::$instances[$id])){
			$class = __CLASS__;
			self::$instances[$id] = new $class($id);
		}
		return self::$instances[$id];
	}

	/**
	 * get country data
	 *
	 * @return Array
	 */
	public function get_by_iso_code($iso_code)
	{
		$country = ORM::factory('country')->where(array(
			'iso_code' => $iso_code
		))
			->find()
			->as_array();
		return $country;
	}

	/**
	 * get country name
	 *
	 * @return Array
	 */
	public function get_name_by_iso_code($iso_code)
	{
		$country = ORM::factory('country')->where(array(
			'iso_code' => $iso_code
		))
			->find()
			->as_array();
		return $country['name'];
	}

	/**
	 * get country select list
	 *
	 * @param array $in
	 * @return array
	 */
	public function select_list_code()
	{
		return ORM::factory('country')->select_list('iso_code','name');
	}

	/**
	 * get country select list
	 *
	 * @param array $in
	 * @return array
	 */
	public function select_list()
	{
		$list = array();		
		$orm = ORM::factory('country');		
		$list = $orm->select_list('id','name');
		
		return $list;
	}

	/**
	 * 是否已经存在
	 * @param <array> $args
	 * @return <boolean>
	 */
	public function exist($data)
	{
		$where = array();
		$where['iso_code'] = $data['iso_code'];
		$count = ORM::factory('country')->where($where)
			->count_all();
		//TODO
		if($count > 0){
			return TRUE;
		} else{
			return FALSE;
		}
	}

	/**
	 * 删除国家信息
	 */
	public function delete()
	{
		$id = $this->data['id'];
		
		$orm = ORM::factory('country',$id);
		if(!$orm->loaded){
			return FALSE;
		}
		//删除物流与国家的关系
		ORM::factory('carrier_country')->where('country_id',$orm->id)
			->delete_all();
		$orm->delete();
		return TRUE;
	}

	/**
	 * 删除站点的国家信息
	 * 
	 * @param int $site_id
	 * @return boolean
	 */
	public function delete_by_site($site_id)
	{
		//删除站点所有国家
		$country = ORM::factory('country')->where('site_id',$site_id);
		$country->delete_all();
		//删除国家物流关联信息
		$carrier_country = ORM::factory('carrier_country')->where('site_id',$site_id);
		$carrier_country->delete_all();
		
		return true;
	}

	/**
	 * 设置排序
	 * @param int $id
	 * @param int $order
	 */
	public function set_order($id, $order)
	{
		$where = array(
			'id' => $id
		);
		$obj = ORM::factory('country')->where($where)
			->find();
		if($obj->loaded){
			$obj->position = $order;
			if($obj->save()){
				return true;
			}
			return false;
		}
		return false;
	}
	
	
    /**
     * 根据id找到对应的国家信息
     * @param $id int
     * @return Array
     */	
	public function get_country_by_id($id)
	{
		$country_manage = array();
		$country_manage = ORM::factory('country_manage')->where(array('id'=>$id, 'active'=>1))->find()->as_array();
		return $country_manage;
	}
}
