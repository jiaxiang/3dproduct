<?php defined('SYSPATH') or die('No direct access allowed.');

class ClassifyService_Core extends DefaultService_Core {
    const DEFAULT_CLASSIFY_NAME = '通用商品类型';
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
        return true;
        $servRouteInstance = $this->get_serv_route_instance();
        $cacheInstance = $servRouteInstance->getMemInstance($this->object_name, array (
            'id' => $id 
        ))->getInstance();
        $routeKey = $this->object_name . '_' . $id;
        // 清理单体cache 
        $cacheInstance->delete($routeKey, 0);
    }
    
    /**
     * 获取所有的类型
     * @param $query_struct
     * @return array
     */
    public function get_classifies($query_struct= array ())
    {
        $return_array = array ();
        
        try{
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
     * 根据classify_id 获取该类型下品牌id数组 
     * @param $classify_id  int 
     * @return array 
     */
    public function get_brand_ids_by_classify_id($classify_id)
    {
        $return_array = array ();
        $query_struct = array (
            'where' => array (
                'classify_id' => $classify_id 
            ) 
        );
        $relations = Classify_brand_relationService::get_instance()->query_assoc($query_struct);
        if(!empty($relations)){
            foreach($relations as $val){
                $return_array[] = $val['brand_id'];
            }
        }
        return $return_array;
    }
    
    /** 
     * 根据classify_id 获取该类型下品牌关系数组 
     * @param $classify_id  int 
     * @return array 
     */
    public function get_brand_relations_by_classify_id($classify_id)
    {
        $return_array = array ();
        $query_struct = array (
            'where' => array (
                'classify_id' => $classify_id 
            ) 
        );
        $relations = Classify_brand_relationService::get_instance()->query_assoc($query_struct);
        if(!empty($relations)){
            foreach($relations as $val){
                $return_array[$val['brand_id']] = $val;
            }
        }
        return $return_array;
    }
    
    /** 
     * 根据classify_id 获取该类型下规格id数组 
     * @param $classify_id  int 
     * @return array 
     */
    public function get_attribute_ids_by_classify_id($classify_id, $apply = NULL)
    {
        $return_array = array ();
        $query_struct = array (
            'where' => array (
                'classify_id' => $classify_id 
            )
        );
        isset($apply) && $query_struct['where']['apply'] = $apply;

        $relations = Classify_attribute_relationService::get_instance()->query_assoc($query_struct);
        if(!empty($relations)){
            foreach($relations as $val){
                $return_array[] = $val['attribute_id'];
            }
        }
        return $return_array;
    }
    
    /** 
     * 根据classify_id 获取该类型下规格关系数组 
     * @param $classify_id  int 
     * @return array 
     */
    public function get_attribute_relations_by_classify_id($classify_id, $apply)
    {
        $return_array = array ();
        $query_struct = array (
            'where' => array (
                'apply' => $apply,
                'classify_id' => $classify_id 
            ) 
        );
        $relations = Classify_attribute_relationService::get_instance()->query_assoc($query_struct);
        if(!empty($relations)){
            foreach($relations as $val){
                $return_array[$val['attribute_id']] = $val;
            }
        }
        return $return_array;
    }
    
    /** 
     * 根据 classify_id获取该类型下所有品牌数组 
     * @param classify_id  in 
     * @return array 
     */
    public function get_brands_by_classify_id($classify_id)
    {
        $result = array ();
        $brand_ids = $this->get_brand_ids_by_classify_id($classify_id);
        if(!empty($brand_ids)){
            $query_struct = array (
                'where' => array (
                    'id' => $brand_ids 
                ) 
            );
            $brands = BrandService::get_instance()->index($query_struct);
            if(!empty($brands)){
                foreach($brands as $key => $val){
                    $result[$val['id']] = $val;
                }
            }
        }
        return $result;
    }
    
    /** 
     * 根据 classify_id 获取关联的规格及规格项 
     * 
     * @param  int $classify_id 
     * @return array 
     * @throws MyRuntimeException 
     */
    public function get_attribute_options_by_classify_id($classify_id, $apply = NULL)
    {
        $return_array = array ();
        
        try{
            /* 判断 classify 是否存在 
            $classify = $this->get($classify_id);
            if(!$classify){
                throw new MyRuntimeException('Object not found.', 500);
            }*/
            // 获取关联表数据 
            $attribute_ids = $this->get_attribute_ids_by_classify_id($classify_id, $apply);
            
            if($attribute_ids){
                // 获取所有关联的attribute 
                $query_struct = array (
                    'where' => array (
                        'id' => $attribute_ids 
                    ), 
                    'orderby' => array (
                        'id' => 'ASC' 
                    ) 
                );
                $attributes = AttributeService::get_instance()->query_assoc($query_struct);
                
                // 每个ID必须都是有效的 
                if(count($attributes) != count($attribute_ids)){
                    throw new MyRuntimeException('Internal error.', 500);
                }
                // 组装返回数组 
                foreach($attributes as $attribute){
                    //$attribute = coding::decode_attribute($attribute);
                    $attribute['options'] = array ();
                    $return_array[$attribute['id']] = $attribute;
                }
                
                // 获取关联项 
                $query_struct = array (
                    'where' => array (
                        'attribute_id' => $attribute_ids 
                    ) 
                );
                $attributeoptions = Attribute_valueService::get_instance()->query_assoc($query_struct);
                foreach($attributeoptions as $attributeoption){
                    //$attributeoption = coding::decode_attributeoption($attributeoption);
                    $attribute_id = $attributeoption['attribute_id'];
                    $return_array[$attribute_id]['options'][$attributeoption['id']] = $attributeoption;
                }
            }
            
            return $return_array;
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
    
    /** 
     * 更新类型-品牌关系 
     * @param $classify_id 
     * @param $brand_ids array 
     */
    public function update_relations_by_brand_ids($classify_id, $brand_ids = array(), $brand_show = array())
    {
        $oldbrands = $this->get_brand_relations_by_classify_id($classify_id);
        if(!empty($oldbrands)){
            $in = array ();
            foreach($oldbrands as $key => $val){
                if(!in_array($key, $brand_ids)){
                    $in[] = $val['id'];
                }
            }
            if(!empty($in)){
                ORM::factory('classify_brand_relation')->in('id', $in)->delete_all();
            }
        }
        if(!empty($brand_ids)){
            foreach($brand_ids as $val){
                if(!isset($oldbrands[$val])){
                    $data['classify_id'] = $classify_id;
                    $data['brand_id'] = $val;
                    if(isset($brand_show[$val])){
                        $data['is_show'] = $brand_show[$val];
                    }
                    $result = Classify_brand_relationService::get_instance()->add($data);
                }else{
                    $set_data['id'] = $oldbrands[$val]['id'];
                    if(isset($brand_show[$val])){
                        $set_data['is_show'] = $brand_show[$val];
                    }
                    $result = Classify_brand_relationService::get_instance()->set($set_data['id'], $set_data);
                }
            }
        }
        $this->clear($classify_id);
    }
    
    /** 
     * 更新类型-规格关系 
     * @param $classify_id 
     * @param $attribute_ids array 
     */
    public function update_relations_by_attribute_ids($classify_id, $attribute_ids = array(), $attribute_show = array(), $apply)
    {
        $oldattributes = $this->get_attribute_relations_by_classify_id($classify_id, $apply);
        if(!empty($oldattributes)){
            $in = array ();
            foreach($oldattributes as $key => $val){
                if(!in_array($key, $attribute_ids)){
                    $in[] = $val['id'];
                }
            }
            if(!empty($in)){
                ORM::factory('classify_attribute_relation')->in('id', $in)->delete_all();
            }
        }
        if(!empty($attribute_ids)){
            foreach($attribute_ids as $val){
                if(!isset($oldattributes[$val])){
                    $data['classify_id'] = $classify_id;
                    $data['attribute_id'] = $val;
                    $data['apply'] = $apply;
                    if(isset($attribute_show[$val])){
                        $data['is_show'] = $attribute_show[$val];
                    }
                    $result = Classify_attribute_relationService::get_instance()->add($data);
                }else{
                    $set_data['id'] = $oldattributes[$val]['id'];
                    if(isset($attribute_show[$val])){
                        $set_data['is_show'] = $attribute_show[$val];
                    }
                    $result = Classify_attribute_relationService::get_instance()->set($set_data['id'], $set_data);
                }
            }
        }
        $this->clear($classify_id);
    }
    
    
    /**
     * 根据 classify_id删除类型
     * @param $classify_id  int
     */
    public function delete_classify_by_classify_id($classify_id)
    {
        $query_struct = array (
            'where' => array (
                'classify_id' => $classify_id 
            ) 
        );
        
        try{
            if(CategoryService::get_instance()->count($query_struct)){
                throw new MyRuntimeException('该类型已被关联，请取消关联之后重试', 500);
            }
        	if(ProductService::get_instance()->count($query_struct)){
                throw new MyRuntimeException('该类型已被关联，请取消关联之后重试', 500);
            }
            $where = array (
                'classify_id' => $classify_id 
            );
            if(Classify_brand_relationService::get_instance()->count($query_struct)){
                Classify_brand_relationService::get_instance()->delete_relations($where);
            }
            
            if(Classify_attribute_relationService::get_instance()->count($query_struct)){
                Classify_attribute_relationService::get_instance()->delete_relations($where);
            }
            
            /*if(Classify_feature_relationService::get_instance()->count($query_struct)){
                Classify_feature_relationService::get_instance()->delete_relations($where);
            }*/
            $this->remove($classify_id);
        
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
    
    /** 
     * 根据 类型ID数组批量删除类型 
     * @param $categorys  array 
     * @return void 
     * @throws MyRuntimeException 
     */
    public function delete_classifies($classifies)
    {
        try{
            $del_all = TRUE;
            if(!empty($classifies)){
                foreach($classifies as $val){
                    $query_struct = array (
                        'where' => array (
                            'classify_id' => $val 
                        ) 
                    );
                    if (CategoryService::get_instance()->count($query_struct))
                    {
                        $del_all = FALSE;
                    }
                    if ($del_all === TRUE AND ProductService::get_instance()->count($query_struct) > 0)
                    {
                    	$del_all = FALSE;
                    }
                    if ($del_all === TRUE) {
                        $where = array (
                            'classify_id' => $val,
                        );
                        Classify_brand_relationService::get_instance()->delete_relations($where);
                        Classify_attribute_relationService::get_instance()->delete_relations($where);
                        //Classify_feature_relationService::get_instance()->delete_relations($where);
                        $this->remove($val);
                    }
                }
                if(!$del_all){
                    throw new MyRuntimeException('无法删除部分被关联的类型', 500);
                }
            }
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
    
    /** 
     * 根据name检查是否重复 
     * @param $site_id  int 
     * @param $name  string 
     * @return bool 
     */
    public function check_exist_name($name)
    {
        $query_struct = array (
            'where' => array (
                'name' => $name 
            ) 
        );
        if($this->count($query_struct))
        return true; 
    }
}
