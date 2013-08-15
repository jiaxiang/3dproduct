<?php
defined('SYSPATH') or die('No direct access allowed.');

class CollectionService_Core extends DefaultService_Core {
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
     * 根据 site_id 获取该  site 中所有的虚拟集合
     * @param $site_id  int
     * @return array
     */
    public function get_collections_by_site_id($site_id)
    {
        // 初始化默认查询条件
        $query_struct = array (
            'where' => array (
                'site_id' => $site_id 
            ) 
        );
        
        try{
            return $this->query_assoc($query_struct);
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
    
    /**
     * 根据 collection_id 获取该虚拟集合中所有的商品id数组
     * @param $collection_id  int
     * @return array
     */
    public function get_products_by_collection_id($collection_id)
    {
        $result = array ();
        $query_struct = array (
            'where' => array (
                'collection_id' => $collection_id 
            ) 
        );
        
        try{
            $return_result = array ();
            $result = Collection_product_relationService::get_instance()->query_assoc($query_struct);
            if(is_array($result) && !empty($result)){
                foreach($result as $val){
                    $return_result[] = $val['product_id'];
                }
            }
            return $return_result;
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
    
    /**
     * 根据 collection_id 获取该虚拟集合中所有的商品集合关系
     * @param $collection_id  int
     * @return array
     */
    public function get_relations_by_collection_id($collection_id)
    {
        $result = array ();
        $query_struct = array (
            'where' => array (
                'collection_id' => $collection_id 
            ) 
        );
        
        try{
            $return_array = array ();
            $result = Collection_product_relationService::get_instance()->query_assoc($query_struct);
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
     * 批量更新商品
     * @param $collection_id  int
     * @param $products array 商品id数组
     * @param bool
     */
    public function update_products($collection_id, $products = array())
    {
        $collection = $this->get($collection_id);
        $oldproducts = $this->get_products_by_collection_id($collection_id);
        if(!empty($oldproducts)){
            $del_products = array ();
            foreach($oldproducts as $val){
                if(!in_array($val, $products)){
                    $del_products[] = $val;
                }
            }
            if(!empty($del_products)){
                ORM::factory('collection_product_relation')->where('collection_id', $collection_id)->in('product_id', $del_products)->delete_all();
            }
        }
        if(!empty($products)){
            foreach($products as $val){
                if(!in_array($val, $oldproducts)){
                    $data['site_id'] = $collection['site_id'];
                    $data['collection_id'] = $collection_id;
                    $data['product_id'] = $val;
                    $result = Collection_product_relationService::get_instance()->add($data);
                }
            }
        }
        $this->clear($collection_id);
    }
    
    /**
     * 根据 collection_id 删除虚拟集合
     * @param $collection_id  int
     * @return void
     */
    public function delete_by_collection_id($collection_id)
    {
        $products = $this->get_products_by_collection_id($collection_id);
        if(!empty($products)){
            $where = array (
                'collection_id' => $collection_id 
            );
            Collection_product_relationService::get_instance()->delete_relations($where);
        }
        $this->remove($collection_id);
    }
    
    /**
     * 批量 删除虚拟集合
     * @param  $collections array 虚拟集合ID数组
     * @return void
     * @throws MyRuntimeException
     */
    public function delete_collections($collections)
    {
        try{
            if(!empty($collections)){
                foreach($collections as $val){
                    $this->delete_by_collection_id($val);
                }
            }
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
    
    /**
     * 判断lable是否存在
     * @param $lable  string
     * @return bool
     */
    public function check_exist_lable($lable)
    {
        //TODO
    }
    
    /**
     * 根据title[名称]检查是否重复
     * @param $site_id  int
     * @param $title  string
     * @return bool
     */
    public function check_exist_title($title)
    {
        $query_struct = array (
            'where' => array (
                'title' => $title 
            ) 
        );
        if($this->count($query_struct))
        return true;
    }
}