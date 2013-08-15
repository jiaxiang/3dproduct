<?php 
defined('SYSPATH') or die('No direct access allowed.'); 
 
class FeatureService_Core extends DefaultService_Core { 
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
     * 通过 feature_id获取该 feature之下所有的 featureoption 
     * @param  int $feature_id 规格ID 
     * @return array 
     * @throws MyRuntimeException 
     */ 
    public function get_featureoptions_by_feature_id($feature_id) 
    { 
        $return_array = array (); 
        $request_struct = array ( 
            'where' => array ( 
                'feature_id' => $feature_id  
            ),  
            'orderby' => array ( 
                'order' => 'ASC',  
                'id' => 'ASC'  
            )  
        ); 
         
        try{ 
            $result = FeatureoptionService::get_instance()->query_assoc($request_struct); 
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
     * 获取特性数组 
     * @param  array $request_struct  请求结构体 
     * @return array 
     * @throws MyRuntimeException 
     */ 
    public function get_features($request_struct = array()) 
    { 
        $return_array = array (); 
        try{ 
            $features = $this->query_assoc($request_struct); 
            if(!empty($features)){ 
                foreach($features as $feature){ 
                    $return_array[$feature['id']] = $feature; 
                } 
            } 
            return $return_array; 
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    } 
     
    /** 
     * 通过 site_id 获取该 site中所有的特性数组 
     * @param  int $site_id  站点 ID 
     * @return array 
     * @throws MyRuntimeException 
     */ 
    public function get_features_by_site_id($site_id) 
    { 
        $request_struct = array ( 
            'where' => array ( 
                'site_id' => $site_id  
            )  
        ); 
         
        try{ 
            return $this->get_features($request_struct); 
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    } 
     
    /**
     * 获取经过格式化的特性及特性值数组
     * @param array $request_struct  请求结构体
     * @param string $mapping_name  featureoption 在其所属 feature的数组中的索引
     * @return
     * array(
     * 1 => array(
     * 'id' => 1,
     * 'site_id' => 1,
     * 'name' => 'Color',
     * 'name_manage' => '颜色',
     * 'meta_struct' => array(),
     * 'items' => array(
     * 2 => array(
     * 'id' => 2,
     * 'attribute_id' => 1,
     * 'name' => 'Red',
     * 'name_manage' => '红色',
     * 'order' => 0,
     * 'meta_struct' => array(),
     * ),
     * ......
     * )
     * ),
     * ......
     * )
     * @throws MyRuntimeException
     */ 
    public function get_feature_options($request_struct, $mapping_name = 'options') 
    { 
        $return_array = array (); 
         
        try{ 
            $features = $this->query_assoc($request_struct); 
            if(!empty($features)){ 
                foreach($features as $feature){ 
                    $feature[$mapping_name] = array (); 
                    $return_array[$feature['id']] = $feature; 
                } 
                $feature_ids = array_keys($return_array); 
                $request_struct = array ( 
                    'where' => array ( 
                        'feature_id' => $feature_ids  
                    ),  
                    'orderby' => array ( 
                        'order' => 'ASC',  
                        'id' => 'ASC'  
                    )  
                ); 
                $featureoptions = FeatureoptionService::get_instance()->query_assoc($request_struct); 
                if(!empty($featureoptions)){ 
                    foreach($featureoptions as $featureoption){ 
                        $feature_id = $featureoption['feature_id']; 
                        if(isset($return_array[$feature_id])){ 
                            $featureoption_id = $featureoption['id']; 
                            $return_array[$feature_id][$mapping_name][$featureoption_id] = $featureoption; 
                        } 
                    } 
                } 
            } 
             
            return $return_array; 
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    } 
     
    /** 
     * 通过 site.id 获取该站点下经过格式化的特性及特性项数组 
     *  
     * @param int    $site_id       站点ID 
     * @param string $mapping_name  featureoption 在其所属 feature的数组中的索引 
     * @return 
     * array( 
     * 1 => array( 
     * 'id' => 1, 
     * 'site_id' => 1, 
     * 'name' => 'Color', 
     * 'name_manage' => '颜色', 
     * 'meta_struct' => array(), 
     * 'items' => array( 
     * 2 => array( 
     * 'id' => 2, 
     * 'attribute_id' => 1, 
     * 'name' => 'Red', 
     * 'name_manage' => '红色', 
     * 'order' => 0, 
     * 'meta_struct' => array(), 
     * ), 
     * ...... 
     * ) 
     * ), 
     * ...... 
     * ) 
     * @throws MyRuntimeException 
     */ 
    public function get_feature_options_by_site_id($site_id, $mapping_name = 'options') 
    { 
        try{ 
            $request_struct = array ( 
                'where' => array ( 
                    'site_id' => $site_id  
                )  
            ); 
             
            return $this->get_feature_options($request_struct, $mapping_name); 
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    } 
     
    /** 
     * 通过feature_id,特性数组，更新该 feature的选项 
     * @param  int $feature_id  特性ID 
     * @param  array $options  更新的特性数组 
     * @return bool 
     * @throws MyRuntimeException 
     */ 
    public function update_options($feature_id, $site_id, $options = array()) 
    { 
        $oldoptions = $this->get_featureoptions_by_feature_id($feature_id); 
        if(!empty($oldoptions)){ 
            $del_ids = array (); 
            foreach($oldoptions as $val){ 
                if(!array_key_exists($val['id'], $options)){ 
                    $del_ids[] = $val['id']; 
                }else{ 
                    $set_data = $options[$val['id']]; 
                    FeatureoptionService::get_instance()->set($val['id'], $set_data); 
                } 
            } 
            if(!empty($del_ids)){ 
                FeatureoptionService::get_instance()->delete_by_featureoption_id($del_ids); 
            } 
        } 
        if(!empty($options)){ 
            foreach($options as $key => $val){ 
                if(!array_key_exists($key, $oldoptions)){ 
                    $set_data = $options[$key]; 
                    $set_data['site_id'] = $site_id; 
                    $set_data['feature_id'] = $feature_id; 
                    FeatureoptionService::get_instance()->add($set_data); 
                } 
            } 
        } 
        $this->clear($feature_id); 
    } 
     
    /**
     * 通过 feature.id 判断该 feature 当前是否正在被关联
     * 
     * @param $feature_id  int
     * @return bool
     */ 
    public function is_relation_by_feature_id($feature_id) 
    { 
        try{ 
            // 获取 该feature 下所有的 featureoption
            $featureoptions = $this->get_featureoptions_by_feature_id($feature_id); 
             
            if(!empty($featureoptions)){ 
                // 获取所有 featureoptions 的 ID
                $featureoption_ids = array (); 
                foreach($featureoptions as $featureoption){ 
                    $featureoption_ids[] = $featureoption['id']; 
                } 
                 
                // 初始化默认查询条件
                $request_struct = array ( 
                    'where' => array ( 
                        'featureoption_id' => $featureoption_ids  
                    )  
                ); 
                 
                // 判断与  product_featureoption_relations 表的关联
                if(Product_featureoption_relationService::get_instance()->count($request_struct)){ 
                    return TRUE; 
                } 
            } 
            //判断与classify_feature_relation 表的关联 
            $request_struct = array ( 
                'where' => array ( 
                    'feature_id' => $feature_id  
                )  
            ); 
            if(Classify_feature_relationService::get_instance()->count($request_struct)){ 
                return TRUE; 
            } 
             
            return FALSE; 
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    } 
     
    /** 
     * 通过 feature_id 删除 feature 
     * @param  int $feature_id 特性ID 
     * @return void 
     * @throws MyRuntimeException 
     */ 
    public function delete_by_feature_id($feature_id) 
    { 
        try{ 
            if($this->is_relation_by_feature_id($feature_id)){ 
                throw new MyRuntimeException('该特性已被关联，请取消关联之后重试', 500); 
            } 
            ORM::factory('featureoption')->where('feature_id', $feature_id)->delete_all(); 
            $this->remove($feature_id); 
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    } 
     
    /** 
     * 批量 删除feature 
     * @param  $features array 特性ID数组 
     * @return void 
     * @throws MyRuntimeException 
     */ 
    public function delete_features($features) 
    { 
        try{ 
            $del_all = true; 
            if(!empty($features)){ 
                foreach($features as $val){ 
                    if($this->is_relation_by_feature_id($val)){ 
                        $del_all = false; 
                    }else{ 
                        ORM::factory('featureoption')->where('feature_id', $val)->delete_all(); 
                        $this->remove($val); 
                    } 
                } 
                if(!$del_all){ 
                    throw new MyRuntimeException('无法删除部分被关联的特性', 500); 
                } 
            } 
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    }

    /**
     * 根据name_manage[管理名称]检查是否重复
     * @param $site_id  int
     * @param $name_manage  string
     * @return bool
     */
    public function check_exist_name_manage($site_id, $name_manage)
    {
        $query_struct = array (
            'where' => array (
                'site_id' => $site_id, 
                'name_manage' => $name_manage 
            ) 
        );
        if($this->count($query_struct))
        return true;
    }
}
