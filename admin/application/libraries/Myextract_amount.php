<?php defined('SYSPATH') or die('No direct script access.');

class Myextract_amount_Core extends My
{
	//对象名称(表名)
    protected $object_name = 'extract_amount';

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
	 * 得到站点新闻列表
	 */
	public function site_extract_amount($site_id,$limit,$offset)
	{
		$extract_amount = ORM::factory('extract_amount')
			->where('site_id',$site_id)	
			->find_all($limit,$offset);
		$data = array();
		foreach($extract_amount as $item)
		{
			$data[] = $item->as_array();
		}
		return $data;
	}
	/**
	 * 站点新闻数据
	 */
	public function count_extract_amount()
	{
		return ORM::factory('extract_amount')->count_all();
	}
	
	/**
	 * 设置排序的值
	 * @param int $id
	 * @param int $order
	 * return bool
	 */
    public function set_order($id ,$order)
    {
        $where = array('id'=>$id);
        $obj = ORM::factory('extract_amount')->where($where)->find();
        if($obj->loaded)
        {
            $obj->order = $order;
            return $obj->save();
        }
        return false;
    }
    
}

