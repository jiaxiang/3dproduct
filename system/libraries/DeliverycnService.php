<?php defined('SYSPATH') OR die('No direct access allowed.');

class DeliverycnService_Core extends DefaultService_Core {
	protected $serv_route_instance = NULL;
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
     * 获取路由实例管理实例
     */
    private function get_serv_route_instance()
    {
        if($this->serv_route_instance === NULL){
            $this->serv_route_instance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
        }
        return $this->serv_route_instance;
    }
    
    /**
	 * 根据物流id修改物流的排序order
	 * 
	 * @param  int $id
	 * @param  int $order
	 * @return boolean
	 */
    public function set_order($id ,$order)
    {
        $obj = ORM::factory('deliverycn')->where(array('id'=>$id))->find();
        //$servRouteInstance = $this->get_serv_route_instance();
        //$cacheInstance = $servRouteInstance->getMemInstance($this->object_name,array('id'=>$id))->getInstance();
        //$routeKey = $this->object_name.'_'.$id;
        if($obj->loaded)
        {
            $obj->position = $order;
            // 清理单体cache
       		//$cacheInstance->delete($routeKey,0);
            if($obj->save())
            {
                return true;
            }
            return false;
        }
        return false;
    }
    
     /**
	 * 根据id修改指定的物流以及关联的国家信息
	 * 
	 * @param  int $id
	 * @param  array $data
	 * @return 
	 */
	public function set($id, $data)
	{
        $request_data = $data;
        $request_data['id'] = $id;
        $this->update($request_data);
        
        //$servRouteInstance = $this->get_serv_route_instance();
        //$cacheInstance = $servRouteInstance->getMemInstance($this->object_name,array('id'=>$id,))->getInstance();
        //$routeKey = $this->object_name.'_'.$id;
        // 清理单体cache
        //$cacheInstance->delete($routeKey,0);
    }
    
    /**
	 * 根据删除指定的物流以及关联的地区信息
	 * 
	 * @param  int $id
	 * @return boolean
	 */
	public function delete_by_id($id)
	{
		$orm = ORM::factory('deliverycn',$id);
		if(!$orm->loaded)
		{
			return FALSE;
		}
		//物流中国家删除
		ORM::factory('deliverycn_region')->where('deliverycn_id',$orm->id)->delete_all();
		$orm->delete();
		//$servRouteInstance = $this->get_serv_route_instance();
        //$cacheInstance = $servRouteInstance->getMemInstance($this->object_name,array('id'=>$id,))->getInstance();
        //$routeKey = $this->object_name.'_'.$id;
        // 清理单体cache
        //$cacheInstance->delete($routeKey,0);
		return TRUE;
	}
	
	/**
	 * 判断物流名称是否已经存在
	 * @param  int $id
	 * @param  string $name
	 * @param  int $deliverycn_id
	 * @return boolean
	 */
	public function name_is_exist($name, $deliverycn_id = NULL)
	{
		$query_struct = array();
		if(!empty($deliverycn_id))
		{
			$query_struct = array(
				'where'   => array(
					'id !='    => $deliverycn_id,
					'name'     => $name

				)
			);
		}
		else
		{
			$query_struct = array(
				'where'   => array(
					'name'     => $name
				)
			);
		}

		$count = $this->query_count($query_struct);

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
	 * get carrier select list
	 *
	 * @param array $in
	 * @return array
	 */
	public function select_list($in = NULL)
	{
		$list = array();
		
		$orm = ORM::factory('deliverycn');
		
		if(!empty($in))
		{
			//$orm->in('site_id',$in);
		}
		$orm->where('active',1);
		$list = $orm->select_list('id','name');
		
		return $list;
	}
	
	/**
	 * get carrier data
	 *
	 * @param int $site_id
	 * @param int $name
	 * @return Array
	 */
	public function get_by_name($name)
	{
		$deliverycn = ORM::factory('deliverycn')->where(array('name'=>$name))->find()->as_array();
		return deliverycn;
	}
	
	/**
	 * 删除站点所有的物流信息
	 * @return boolean
	 */
	public function delete_all()
	{
		//删除物流信息
		ORM::factory('deliverycn')->delete_all();
		//删除物流国家关联信息
		ORM::factory('deliverycn_region')->delete_all();
		return true;
	}
	
	/**
	 * 根据物流id得到其对应的名字，考虑id不在表里面的情况
	 * 
	 * @param int $deliverycn_id
	 * @return string
	 */
	public function get_delivery_name($deliverycn_id)
	{
		$deliverycn = ORM::factory('deliverycn')->where('id',$deliverycn_id)->find()->as_array();
		$name = isset($deliverycn) && !empty($deliverycn['id']) ? $deliverycn['name'] : '未知';
		return $name;
	}
}
