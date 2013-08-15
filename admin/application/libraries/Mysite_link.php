<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mysite_link_Core extends My{
	//对象名称(表名)
    protected $object_name = 'site_link';

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

	public function site_links($site_id,$limit,$offset)
	{
		$site_links = ORM::factory('site_link')	
			->where('site_id',$site_id)
			->find_all($limit,$offset);
		$data = array();
		foreach($site_links as $item)
		{
			$data[] = $item->as_array();
		}
		return $data;
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
        $obj = ORM::factory('site_link')->where($where)->find();
        if($obj->loaded)
        {
            $obj->order = $order;
            return $obj->save();
        }
        return false;
    }

}
