<?php
defined('SYSPATH') or die('No direct access allowed.');

class BrandService_Core extends DefaultService_Core {
    protected $serv_route_instance = NULL;
    /* 兼容php5.2环境 Start */
    private static $instance = NULL;
    // 获取单态实例
    public static function get_instance()
    {
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    /* 兼容php5.2环境 End */
    
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
    
    public function get_cache($id)
    {
        $servRouteInstance = $this->get_serv_route_instance();
        $cacheInstance = $servRouteInstance->getMemInstance($this->object_name, array (
            'id' => $id 
        ))->getInstance();
        $routeKey = $this->object_name . '_' . $id;
        $cacheObject = $cacheInstance->get($routeKey);
        if(empty($cacheObject)){
            $cacheObject = $this->read(array (
                'id' => $id 
            ));
            $cacheInstance->set($routeKey, $cacheObject);
        }
        return $cacheObject;
    }
    
    public function set($id, $data)
    {
        $request_data = $data;
        $request_data['id'] = $id;
        $this->update($request_data);
        $this->clear($id);
    }
    
    public function remove($id)
    {
        $this->delete(array (
            'id' => $id 
        ));
        $this->clear($id);
    }
    
    //清除单体缓存
    public function clear($id)
    {
        $servRouteInstance = $this->get_serv_route_instance();
        $cacheInstance = $servRouteInstance->getMemInstance($this->object_name, array (
            'id' => $id 
        ))->getInstance();
        $routeKey = $this->object_name . '_' . $id;
        // 清理单体cache
        $cacheInstance->delete($routeKey, 0);
    }
    
    /**
     * 获取该中所有的品牌
     * @param $site_id  int
     * @return array
     */
    public function get_brands($query_struct = array())
    {
        $result = array ();        
        try{
            $return_array = array ();
            $result = $this->query_assoc($query_struct);
            if(!empty($result)){
                foreach($result as $val){
                    $return_array[$val['id']] = $val;
                }
            }
            return $return_array;
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
    
    /**
     * 通过brand_id 判断该 brand当前是否正在被关联
     * @param $brand_id  int
     * @return bool
     */
    public function is_relation_by_brand_id($brand_id)
    {
        $query_struct = array (
            'where' => array (
                'brand_id' => $brand_id 
            ) 
        );
        if(ProductService::get_instance()->count($query_struct)){
            return true;
        }
        if(Classify_brand_relationService::get_instance()->count($query_struct)){
            return true;
        }
        return false;
    }
    
    /**
     * 根据 brand_id 删除品牌
     * @param $brand_id  int
     * @return void
     * @throws MyRuntimeException
     */
    public function delete_brand_by_brand_id($brand_id)
    {
        if($this->is_relation_by_brand_id($brand_id)){
            throw new MyRuntimeException('该品牌已被关联，请取消关联之后重试', 500);
        }
        $this->remove($brand_id);
    }
    
    /**
     * 批量 删除品牌
     * @param $brands  array 品牌id数组
     * @return void
     * @throws MyRuntimeException
     */
    public function delete_brands($brands)
    {
        $del_all = true;
        if(!empty($brands)){
            foreach($brands as $val){
                if($this->is_relation_by_brand_id($val)){
                    $del_all = false;
                }else{
                    $this->remove($val);
                }
            }
            if(!$del_all){
                throw new MyRuntimeException('无法删除部分被关联的品牌', 500);
            }
        }
    }
}
?>