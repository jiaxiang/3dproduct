<?php defined('SYSPATH') or die('No direct script access.');

class Mydoc_Core extends My{
	//对象名称(表名)
    protected $object_name = 'doc';
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
	 * 判断输入的url是否已存在
	 */
	public function url_exist($permalink,$id=0)
	{
		$to = array(
			'where' => array(
				'permalink' => $permalink
			)
		);
        if($id>0)$to['where']['id!='] = $id;
		$count = $this->count($to);
		if($count > 0){
			return TRUE;
		} else{
			return FALSE;
		}
	}
        
    /**
     * doc list
	 *
     * @param String $orderby
     * @param Int $limit
     * @param Int $offset
     * @return Array
     */
	public function site_docs($limit = 2000,$offset = 0)
	{
		$list = array();

		$docs = ORM::factory('doc')
			->orderby('order','DESC')
			->orderby('id','DESC')
			->find_all($limit,$offset);

		foreach($docs as $item)
		{
			$list[] = $item->as_array();
		}

		return $list;
	}

	public function count_site_docs()
	{
		$count = ORM::factory('doc')->count_all();
		return $count;
	}

	/**
	 * get doc info by permalink and site_id
	 */
	public function get_by_permalink($permalink)
	{
		$where = array();
		$where['permalink'] = $permalink;

		$doc = ORM::factory('doc')->where($where)->find();
		$this->data = $doc->as_array();
		return $this->data;
	}
	
	/**
	 * 删除方案信息
	 */
	public function delete()
	{
		$doc = ORM::factory('doc',$this->data['id']);
		if(!$doc->loaded){
		  return false;
		}
		$doc->delete();
		//$this->clear_uris();
		return TRUE;
	}
	
	/**
	 * 删除站点所有的方案信息
	 * 
	 * @param int $site_id
	 * @return boolean
	 */
	public function delete_by_site()
	{
		$doc = ORM::factory('doc');
		$doc->delete_all();
		//$this->clear_uris();
		return true;
	}

	/**
	 * add doc
	 */
	public function add($data)
	{
		$id = parent::add($data);
		if($id)
		{
			//order和ID初始赋同样的值
			$doc = ORM::factory('doc',$id);
			$doc->save();
			//$this->clear_uris();
			return $id;
		}
		else
		{
			return false;
		}
	}

	/**
	 * init site doc default data
	 */
	public function init($type = 0)
	{
		$data = array();
		$data['title'] = 'about us';
		$data['permalink'] = 'about-us';
		$data['content'] = 'about us';
		Mydoc::instance()->add($data);
	}

	/**
	 * set doc order
	 */
    public function set_order($id ,$order)
    {
        $where = array('id'=>$id);
        $obj = ORM::factory('doc')->where($where)->find();
        if($obj->loaded){
            $obj->order = $order;
            if($obj->save()){
                return true;
            }
            return false;
        }
        return false;
    }
	
    public static function clear_uris()
	{
        return;
		$serv_route_instance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
		$cacheInstance      = $serv_route_instance->getMemInstance('doc_uris', array('id' => $site_id))->getInstance();
		$route_key           = 'doc_uris_';
		$cacheInstance->delete($route_key, 0);
	}
}
