<?php defined('SYSPATH') or die('No direct script access.');

class Mynews_Core extends My
{
	//对象名称(表名)
    protected $object_name = 'news';

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
	public function site_news($site_id,$limit,$offset)
	{
		$news = ORM::factory('news')
			->where('site_id',$site_id)	
			->find_all($limit,$offset);
		$data = array();
		foreach($news as $item)
		{
			$data[] = $item->as_array();
		}
		return $data;
	}
	/**
	 * 站点新闻数据
	 */
	public function count_site_news()
	{
		return ORM::factory('news')->count_all();
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
        $obj = ORM::factory('news')->where($where)->find();
        if($obj->loaded)
        {
            $obj->order = $order;
            return $obj->save();
        }
        return false;
    }
    
}

