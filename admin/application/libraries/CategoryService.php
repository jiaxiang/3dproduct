<?php defined('SYSPATH') or die('No direct access allowed.');

class CategoryService_Core extends DefaultService_Core {
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
    
    //路由实例管理实例 
    private $serv_route_instance = NULL;
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
            if(!empty($cacheObject)){
                $cacheInstance->set($routeKey, $cacheObject);
            }
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
    
    public function clear($id)
    {
        $category = $this->get($id);
        $servRouteInstance = $this->get_serv_route_instance();
        // 清理单体cache 
        $cacheInstance = $servRouteInstance->getMemInstance($this->object_name, array (
            'id' => $id 
        ))->getInstance();
        $routeKey = $this->object_name . '_' . $id;
        $cacheInstance->delete($routeKey, 0);
        /* 清理站点uri_name缓存 */
        $cacheInstance = $servRouteInstance->getMemInstance($this->object_name . '_uri_name_', array ())->getInstance();
        $routeKey = $this->object_name . '_uri_name_';
        $cacheInstance->delete($routeKey, 0);
    }
    
    /** 
     * 根据 site_id 加载分类数据 
     * @param $site_id  int 
     * @return array 
     */
    private function load()
    {
        $result = array ();
        $return_array = array ();
        $query_struct = array (
            'where' => array (), 
            'like' => array (), 
            'orderby' => array (), 
            'limit' => array () 
        );
        //echo "a/"; 
        $result = $this->query_assoc($query_struct);
        if(!empty($result)){
            foreach($result as $val){
                $return_array[$val['id']] = $val;
            }
        }
        return $return_array;
    }
    
    /** 
     * 根据 site_id 获取该  site 中所有的分类数据 
     * @param $site_id  int 
     * @param $requestkeys array 指定显示的字段,为空时表示显示全部字段 
     * @return array 
     */
    public function get_categories($requestkeys = array())
    {
        $result = array ();
        $result = $this->load();
        if(!empty($result) && !empty($requestkeys)){
            foreach($result as $key => $val){
                util::filter_keys($result[$key], $requestkeys);
            }
        }
        return $result;
    }
    
    /**
     * 获取所有的分类的树
     * @param $site_id int
     * @param $str string 生成树型结构的基本代码，例如：例如：'<option value={$id} {$selected}>{$spacer}{$title}</option>'
     * @param $sid int 被选中的ID，比如在做树型下拉框的时候需要用到 
     * @param $icon 前缀
     * @return string
     */
    public function get_tree($str, $sid = 0, $icon = '--')
    {
        $categories = array ();
        $categories = $this->load();
        $tree = tree::get_tree($categories, $str, 0, $sid, $icon);
        return $tree;
    }
    
    /**
     * 根据 category_id 获取该 分类 下所有的子分类树
     * @param $category_id  int
     * @param $str string 生成树型结构的基本代码，例如：例如：'<option value={$id} {$selected}>{$spacer}{$title}</option>'
     * @param $sid int 被选中的ID，比如在做树型下拉框的时候需要用到 
     * @param $icon 前缀
     * @return string
     */
    public function get_tree_by_category_id($category_id, $str, $sid = 0, $icon = '--')
    {
        $categories = array ();
        $category = $this->get($category_id);
        $site_id = $category['site_id'];
        $categories = $this->load();
        $tree = tree::get_tree($categories, $str, $category_id, $sid, $icon);
        return $tree;
    }
    
    /**
     * 根据 category_id 获取该分类所有的父级数组（包括自己）
     * @param $category_id  int
     * @return array
     */
    public function get_parents_by_category_id($category_id)
    {
        $result = array ();
        $category = $this->get($category_id);
        $categories = $this->load();
        $result = tree::get_parents($categories, $category_id);
        return $result;
    }
    
    /** 
     * 根据 category_id 维护sub_ids字段数据 
     * @param $category_id  int 
     * @return string 
     */
    public function get_sub_ids_by_category_id($category_id)
    {
        $result = array ();
        $category = $this->get($category_id);
        $categories = $this->load();
        $result = tree::get_childs($categories, $category_id);
        $str = implode(',', $result);
        return $str;
    }
    
    /** 
     * 根据 category_id 维护level_depth字段数据 
     * @param $category_id  int 
     * @return int 
     */
    public function get_level_depth_by_category_id($category_id)
    {
        $result = array ();
        $result = $this->get_parents_by_category_id($category_id);
        $level_depth = count($result);
        return $level_depth;
    }
    
    /**
     * 根据 category_id 获取该分类所有的同级数组
     * @param $category_id  int
     * @return array
     */
    public function get_brothers_by_category_id($category_id)
    {
        
        $result = array ();
        $category = $this->get($category_id);
        $query_struct = array (
            'where' => array (
                'pid' => $category['pid'] 
            ), 
            'like' => array (), 
            'orderby' => array (), 
            'limit' => array () 
        );
        $result = $this->query_assoc($query_struct);
        return $result;
    
    }
    
    /**
     * 根据 category_id 获取该分类所有的子级数组
     * @param $category_id  int
     * @return array
     */
    public function get_childrens_by_category_id($category_id)
    {
        $children_ids = array ();
        $category = $this->get($category_id);
        if(!empty($category['sub_ids'])){
            $children_ids = explode(",", $category['sub_ids']);
        }
        return $children_ids;
    }
    
    /** 
     * 通过category_id获取该分类下的所有商品的指定字段 
     * @param $category_id  int 
     * @param $requestkeys array 为空时表示显示全部字段 
     * @return array 
     */
    public function get_products_by_category_id($category_id, $requestkeys = array())
    {
        $result = array ();
        $return_array = array ();
        $query_struct = array (
            'where' => array (
                'category_id' => $category_id,
                'status' => ProductService::PRODUCT_STATUS_PUBLISH 
            ), 
            //'like' => array (), 
            'orderby' => array (
                'update_time' => 'DESC' 
            ), 
            'limit' => array () 
        );
        
        $result = ProductService::get_instance()->query_assoc($query_struct);
        if($result){
            foreach($result as $val){
                if(!empty($requestkeys)){
                    $return_array[$val['id']] = $val;
                    util::filter_keys($return_array[$val['id']], $requestkeys);
                }else{
                    $return_array[$val['id']] = $val;
                }
            }
        }
        return $return_array;
    
    }
    
    /**
     * 根据 site_id修复所有分类数据
     * @param $site_id  int
     * @return bool
     */
    public function update_categories()
    {
        @set_time_limit(600);
        $result = array ();
        $result = $this->load();
        if(!empty($result)){
            foreach($result as $key => $val){
                $this->update_categories_by_category_id($val['id']);
            }
        }
        return true;
    }
    
    /** 
     * 根据 category_id修复此分类数据 
     * @param $category_id  int 
     */
    public function update_categories_by_category_id($category_id)
    {
        $sub_ids = $this->get_sub_ids_by_category_id($category_id);
        $level_depth = $this->get_level_depth_by_category_id($category_id);
        $data['sub_ids'] = $sub_ids;
        $data['level_depth'] = $level_depth;
        $this->set($category_id, $data);
    }
    
    /** 
     * 修改分类的父分类 
     * @param $category_id  int 
     * @param $pid int 
     * @return bool 
     */
    public function update_parent($category_id, $pid)
    {
        $category = $this->get($category_id);
        $child_ids = $this->get_childrens_by_category_id($category_id);
        if(in_array($pid, $child_ids) || $pid == $category_id)
            return false;
        $data['pid'] = $pid;
        $this->set($category_id, $data);
        $this->update_categories();
        return true;
    }
    
    /**
     * 根据 category_id 删除分类
     * @param $category_id  int
     * @return void 
     * @throws MyRuntimeException
     */
    public function delete_category_by_category_id($category_id)
    {
        try{
            $category = $this->get($category_id);
            if($this->get_childrens_by_category_id($category_id)){
                throw new MyRuntimeException('有子分类不能删除', 500);
            }
            if($this->get_products_by_category_id($category_id)){
                throw new MyRuntimeException('分类下有商品不能删除', 500);
            }
            $query_struct = array (
                'where' => array (
                    'category_id' => $category_id 
                ) 
            );
            if(Alias_filterService::get_instance()->count($query_struct)){
                throw new MyRuntimeException('有相关联的虚拟分类不能删除', 500);
            }
            if(!empty($category['pic_attach_id'])){
                $this->delete_pic($category['pic_attach_id']);
            }
            $this->remove($category_id);
            $this->update_categories();
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
    
    /** 
     * 根据 分类ID数组批量删除分类 
     * @param $categorys  array 
     * @param $site_id int 分类站点 
     * @return void 
     * @throws MyRuntimeException 
     */
    public function delete_categorys($categorys)
    {
        try{
            $count = count($categorys);
            $del_ids = array ();
            $result = $this->query_assoc();
            foreach($result as $val){
                $del_ids[] = $val['id'];
            }
            if(!empty($categorys)){
                foreach($categorys as $key => $val){
                    if(in_array($val, $del_ids)){
                        $products = $this->get_products_by_category_id($val);
                        if(empty($products)){
                            $this->remove($val);
                            unset($categorys[$key]);
                        }
                    }
                }
            }
            $this->update_categories();
            if(count($categorys) == $count){
                throw new MyRuntimeException('部分分类不能删除', 500);
            }
            if(!empty($categorys)){
                $this->delete_categorys($categorys);
            }
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
    
    /**
     * 根据 classify_id 获取分类id数组
     * @param $classify_id  int
     * @return array
     */
    public function get_categories_by_classify_id($classify_id)
    {
        $result = array ();
        $query_struct = array (
            'where' => array (
                'classify_id' => $classify_id 
            ) 
        );
        
        try{
            $return_array = array ();
            $result = $this->query_assoc($query_struct);
            if(is_array($result) && !empty($result)){
                foreach($result as $val){
                    $return_array[] = $val['id'];
                }
            }
            return $return_array;
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
    
    /** 
     * 根据站点id,父id,获取该站点下同级的最大位置 
     * @param $site_id  int 站点id 
     * @param $pid  int 父id 
     * @return int 
     */
    public function get_last_position($pid)
    {
        $position = 1;
        $query_struct = array (
            'where' => array (
                //'site_id' => $site_id, 
                'pid' => $pid 
            ), 
            'orderby' => array (
                'position' => 'DESC', 
                'id' => 'DESC' 
            ), 
            'limit' => array (
                'per_page' => 1, 
                'page' => 1 
            ) 
        );
        $category = $this->query_assoc($query_struct);
        if(!empty($category)){
            $position = $category[0]['position'];
        }
        return $position;
    }
    
    /** 
     * 根据分类id更新站点位置并使位置不重复 
     * @param $category_id  int 分类id 
     * @param $position  int 更新的位置值 
     * @return void 
     */
    public function edit_position($category_id, $position)
    {
        $category = $this->get($category_id);
        if($position != $category['position']){
            $query_struct = array (
                'where' => array (
                    //'site_id' => $category['site_id'], 
                    'pid' => $category['pid'], 
                    'position=' => $position 
                ), 
                'orderby' => array (
                    'id' => 'ASC' 
                ) 
            );
            $count = $this->count($query_struct);
            if($count > 0){
                $query_struct = array (
                    'where' => array (
                        'site_id' => $category['site_id'], 
                        'pid' => $category['pid'], 
                        'position>=' => $position 
                    ), 
                    'orderby' => array (
                        'position' => 'ASC', 
                        'id' => 'ASC' 
                    ) 
                );
                $category_downs = $this->query_assoc($query_struct);
                if(!empty($category_downs)){
                    foreach($category_downs as $key => $val){
                        $set_data = array (
                            'position' => $val['position'] + 1 
                        );
                        $this->set($val['id'], $set_data);
                    }
                }
            }
            $set_data = array (
                'position' => $position 
            );
            $this->set($category_id, $set_data);
        }
    }
    
    /**
     * 设置菜单的排序项
     * @param int $id 菜单ID
     * @param int $order 排序项
     * return bool
     */
    public function set_order($id, $order)
    {
        $obj = $this->get($id);
        if(!empty($obj)){
            $set_data = array (
                'position' => $order 
            );
            $this->set($obj['id'], $set_data);
            return true;
        }
        return false;
    }
    
    /** 
     * 根据 category_id修改位置 
     * @param int $category_id 分类id 
     * @return array position改变的分类id数组 
     * @throws MyRuntimeException 
     */
    public function position_by_category_id($category_id, $type = 'up')
    {
        $return_array = array ();
        try{
            $category = $this->get($category_id);
            if($type == 'up'){
                $query_struct = array (
                    'where' => array (
                        'id<' => $category['id'], 
                        'site_id' => $category['site_id'], 
                        'pid' => $category['pid'], 
                        'position=' => $category['position'] 
                    ), 
                    'like' => array (), 
                    'orderby' => array (
                        'position' => 'DESC', 
                        'id' => 'DESC' 
                    ), 
                    'limit' => array (
                        'per_page' => 1, 
                        'page' => 1 
                    ) 
                );
                $category_ups = $this->query_assoc($query_struct);
                if(empty($category_ups)){
                    $query_struct = array (
                        'where' => array (
                            'site_id' => $category['site_id'], 
                            'pid' => $category['pid'], 
                            'position<' => $category['position'] 
                        ), 
                        'like' => array (), 
                        'orderby' => array (
                            'position' => 'DESC', 
                            'id' => 'DESC' 
                        ), 
                        'limit' => array (
                            'per_page' => 1, 
                            'page' => 1 
                        ) 
                    );
                    $category_ups = $this->query_assoc($query_struct);
                }
                if(empty($category_ups)){
                    throw new MyRuntimeException('it is top,can\'t up', 500);
                }
                
                $category_up = $category_ups[0];
                if($category_up['position'] == $category['position']){
                    $return_array = $this->position_by_category_id($category_up['id'], 'down');
                }else{
                    $set_data = array (
                        'position' => $category_up['position'] 
                    );
                    $this->set($category['id'], $set_data);
                    $set_data = array (
                        'position' => $category['position'] 
                    );
                    $this->set($category_up['id'], $set_data);
                    $return_array[0] = array (
                        'id' => $category['id'], 
                        'position' => $category_up['position'] 
                    );
                    $return_array[1] = array (
                        'id' => $category_up['id'], 
                        'position' => $category['position'] 
                    );
                }
            }else if($type == 'down'){
                $query_struct = array (
                    'where' => array (
                        'id>' => $category['id'], 
                        'site_id' => $category['site_id'], 
                        'pid' => $category['pid'], 
                        'position=' => $category['position'] 
                    ), 
                    'like' => array (), 
                    'orderby' => array (
                        'position' => 'ASC', 
                        'id' => 'ASC' 
                    ) 
                );
                $category_eqs = $this->query_assoc($query_struct);
                $query_struct = array (
                    'where' => array (
                        'site_id' => $category['site_id'], 
                        'pid' => $category['pid'], 
                        'position>' => $category['position'] 
                    ), 
                    'like' => array (), 
                    'orderby' => array (
                        'position' => 'ASC', 
                        'id' => 'ASC' 
                    ) 
                );
                $category_downs = $this->query_assoc($query_struct);
                $category_downs = array_merge($category_eqs, $category_downs);
                
                if(empty($category_downs)){
                    throw new MyRuntimeException('it is bottom,can\'t down', 500);
                }
                $category_down = $category_downs[0];
                if($category_down['position'] == $category['position']){
                    $category_downs[0] = $category;
                    foreach($category_downs as $key => $val){
                        $set_data = array (
                            'position' => $val['position'] + 1 
                        );
                        $this->set($val['id'], $set_data);
                        $return_array[$key]['id'] = $val['id'];
                        $return_array[$key]['position'] = $val['position'] + 1;
                    }
                }else{
                    $set_data = array (
                        'position' => $category_down['position'] 
                    );
                    $this->set($category['id'], $set_data);
                    $set_data = array (
                        'position' => $category['position'] 
                    );
                    $this->set($category_down['id'], $set_data);
                    $return_array[0] = array (
                        'id' => $category_down['id'], 
                        'position' => $category['position'] 
                    );
                    $return_array[1] = array (
                        'id' => $category['id'], 
                        'position' => $category_down['position'] 
                    );
                }
            
            }
            return $return_array;
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
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
     * 根据站点id删除相关缓存
     */
    public function clear_cache_by_site_id($site_id=0)
    {
        $servRouteInstance = $this->get_serv_route_instance();
        // 清理站点uri_name缓存
        $cacheInstance = $servRouteInstance->getMemInstance($this->object_name . '_uri_name_', array (
            'id' => $site_id 
        ))->getInstance();
        $routeKey = $this->object_name . '_uri_name_' . $site_id;
        $cacheInstance->delete($routeKey, 0);
    }
    
	/**
     * 根据 title[前台名称]检查是否重复
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
    
    /**
     * 根据 title_manage[后台名称]检查是否重复
     * @param $site_id  int
     * @param $title_manage  string
     * @return bool
     */
    public function check_exist_title_manage($title_manage)
    {
        $query_struct = array (
            'where' => array (
                'title_manage' => $title_manage 
            ) 
        );
        if($this->count($query_struct))
        return true;
    }
    
    public function update_show_val_by_id($id, $is_show)
    {
    	$category_ids = $this->get_sub_ids_by_category_id($id);
        if(!$category_ids)return false;
    	$category_ids = explode(',', $category_ids);
    	$query_struct = array(
    		'where'   => array(
    			'id'  => $category_ids
    		)
    	);
    	$categories = $this->query_assoc($query_struct);
    	$set_data = array('is_show'=>$is_show);
    	foreach($categories as $val){
    		$this->set($val['id'], $set_data);
    	}
        return true;
    }
    
    /** 
     * 删除对应的附件数据和存储文件 
     * @param int $id 附件id 
     */ 
    public function delete_pic($id) 
    {
        AttService::get_instance("category")->delete_img($id);
    } 
    
}
