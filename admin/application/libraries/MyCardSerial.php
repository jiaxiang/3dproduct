<?php defined('SYSPATH') or die('No direct script access.');


class MyCardSerial_Core extends My
{
	//对象名称(表名)
    protected $object_name = 'ac_cardserial';
    
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
	
	public function mylists2($limit,$offset)
	{
		$cards = ORM::factory($this->object_name)
			->find_all($limit,$offset);
		$data = array();
		foreach($cards as $agent)
		{
			$data[] = $agent->as_array();
		}
		return $data;
	}
	
	public function count_itmes()
	{
		return ORM::factory($this->object_name)->count_all();
	}
	
	public function count_items_with_condition($where)
	{
		return ORM::factory($this->object_name)->where($where)->count_all();
	}
	
	public function item_exist($data)
	{
		$where = array();
		$where['id'] = $data['id'];
		$count = ORM::factory($this->object_name)->where($where)->count_all();
		
        return ($count > 0) ? true : false;
	}
	
	public function get_by_id($id)
	{
		if (empty($id)) {return false;}
		$where = array();
		$where['id'] = $id;
		$user = ORM::factory($this->object_name)->where($where)->find();
		
		return ($user->loaded) ? $user->as_array() : false ;
	}

}

?>