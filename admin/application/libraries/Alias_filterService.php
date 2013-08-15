<?php
defined('SYSPATH') or die('No direct access allowed.');

class Alias_filterService_Core extends DefaultService_Core {
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
    
    public function get($id)
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
     * 根据 uri_name检查是否重复
     * @param $uri_name  string
     * @return bool
     */
    public function check_exist_uri_name($uri_name)
    {
        $query_struct = array (
            'where' => array (
                'uri_name' => $uri_name 
            ) 
        );
        if($this->count($query_struct))
            return true;
    }
    
    /**
     * 根据 site_id 获取该 站点 下所有的虚拟分类的树
     * @param $site_id int
     * @param $str string 生成树型结构的基本代码，例如：例如：'<option value={$id} {$selected}>{$spacer}{$title}</option>'
     * @param $sid int 被选中的ID，比如在做树型下拉框的时候需要用到
     * @param $icon 前缀
     * @return string
     */
    public function get_tree($str, $sid = 0, $icon = '--')
    {
        $result = array ();
        $query_struct = array (
            'where' => array (
            ), 
            'like' => array (), 
            'orderby' => array (), 
            'limit' => array () 
        );
        $result = $this->query_assoc($query_struct);
        $tree = tree::get_tree($result, $str, 0, $sid, $icon);
        return $tree;
    }
    
    /**
     * 根据 filter_id 获取该分类所有的父级数组（包括自己）
     * @param $filter_id  int
     * @return array
     */
    public function get_parents_by_filter_id($filter_id)
    {
        $result = array ();
        $filter = $this->get($filter_id);
        $result[] = $filter;
        if($filter['pid']){
            $tmp = $this->get_parents_by_filter_id($filter['pid']);
            $result = array_merge($tmp, $result);
        }
        return $result;
    }

    /**
     * 根据filter_id 维护level_depth字段数据
     * @param $filter_id   int
     * @return int
     */
    public function get_level_by_filter_id($filter_id)
    {
        $result = array ();
        $result = $this->get_parents_by_filter_id($filter_id);
        $level_depth = count($result);
        return $level_depth;
    }
    
    /**
     * 根据 filter_id 删除虚拟分类
     * @param $filter_id  int
     * @return void
     * @throws MyRuntimeException
     */
    public function delete_filter_by_filter_id($filter_id)
    {
        try{
            $query_struct = array (
                'where' => array (
                    'pid' => $filter_id 
                ) 
            );
            $result = $this->query_assoc($query_struct);
            if(!empty($result)){
                throw new MyRuntimeException('有子分类不能删除', 500);
            }
            $this->remove($filter_id);
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
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
?>