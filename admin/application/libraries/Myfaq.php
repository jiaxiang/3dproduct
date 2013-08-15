<?php defined('SYSPATH') or die('No direct script access.');

class Myfaq_Core extends My{
	//对象名称(表名)
    protected $object_name = 'faq';

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
     * site faq list
	 *
     * @param String $orderby
     * @param Int $limit
     * @param Int $offset
     * @return Array
     */
	public function site_faqs($limit = 100,$offset = 0)
	{
		$list = array();

		$faqs = ORM::factory('faq')
			->find_all($limit,$offset);

		foreach($faqs as $item)
		{
			$list[] = $item->as_array();
		}

		return $list;
	}
	
	/**
	 * 初始化FAQ数据
	 */
	public function init()
	{
		$data = array();
		$data['title'] = 'faq title';
		$data['content'] = 'I am a faq ';
		
		Myfaq::instance()->add($data);
	}

	/**
	 * 设置FAQ的排序
	 * @param int $id faq的ID
	 * @param int $order 排序值
	 * @return boolean
	 */
    public function set_order($id ,$order)
    {
    	$where = array('id'=>$id);
        $obj = ORM::factory('faq')->where($where)->find();
        if($obj->loaded){
            $obj->order = $order;
            if($obj->save()){
                return true;
            }
            return false;
        }
        return false;
    }
    
}
