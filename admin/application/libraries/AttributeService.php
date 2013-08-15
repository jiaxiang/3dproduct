<?php defined('SYSPATH') or die('No direct access allowed.'); 
/**
 * 属性管理 Service 
 */ 
class AttributeService_Core extends DefaultService_Core { 
    const ATTRIBUTE_SPEC = '1';//规格,供后台设定，前台选择或填写
    const ATTRIBUTE_FEATURE = '0';//特性,供后台设定，前台查看
    
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
     * 获取所有的规格数组
     * @return array
     * @throws MyRuntimeException
     */ 
    public function clear_attribute_value($attribute_id)
    {
        if($attribute_id<=0)return false;
        try{            
             //调用服务
             $attribute_value_service = Attribute_valueService::get_instance();
             $request_struct = array ( 
                 'where' => array ( 
                     'attribute_id' => $attribute_id  
                 ) 
             ); 
             $result = $attribute_value_service->query_assoc($request_struct); 

             if(!empty($result)){ 
                 foreach($result as $key => $val){
                     if($attribute_value_service->is_relation_by_attribute_value_id($val['id'])){
                         throw new MyRuntimeException('该规格值已被关联，请取消关联之后重试！', 500);
                     }
                     $attribute_value_service->delete_by_value_array($val);
                 } 
             }
             return true;
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    }
            
    /**
     * 获取所有的规格数组
     * @return array
     * @throws MyRuntimeException
     */ 
    public function get_attributes_spec()
    {
       //规格
       $query_struct = array (
           'where' => array (                    
               'apply' => self::ATTRIBUTE_SPEC
           ),
           'orderby' => array (                    
               'id' => 'DESC' 
           )
        );
        try{ 
            return $this->get_attributes($query_struct); 
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    } 
    
    /**
     * 获取所有的特性数组
     * @return array
     * @throws MyRuntimeException
     */ 
    public function get_attributes_feature()
    {
       //规格
       $query_struct = array (
           'where' => array (                    
               'apply' => self::ATTRIBUTE_FEATURE
           ),
           'orderby' => array (                    
               'id' => 'DESC' 
           )
        );
        try{ 
            return $this->get_attributes($query_struct); 
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    } 
         
    public function set($id, $data) 
    { 
        $request_data = $data; 
        $request_data['id'] = $id; 
        $this->update($request_data); 
    }
    
    public function remove($id) 
    { 
        $this->delete(array ( 
            'id' => $id  
        )); 
    }
     
    /**
     * 通过 attribute_id获取该 attribute之下所有的 attributeoption
     * @param  int $attribute_id 规格ID
     * @return array
     * @throws MyRuntimeException
     */ 
    public function get_attributeoptions_by_attribute_id($attribute_id) 
    { 
        $return_array = array (); 
        $request_struct = array ( 
            'where' => array ( 
                'attribute_id' => $attribute_id  
            ),  
            'orderby' => array ( 
                'order' => 'ASC',  
                'id' => 'ASC'  
            )  
        ); 
        $result = Attribute_valueService::get_instance()->query_assoc($request_struct); 
        
        if(!empty($result)){ 
            foreach($result as $key => $val){ 
                $val = coding::decode_attributeoption($val); 
                $return_array[$val['id']] = $val; 
            } 
        } 
        return $return_array;
    } 
     
    /**
     * 获取规格数组
     * @param  array $request_struct  请求结构体
     * @return array
     * @throws MyRuntimeException
     */ 
    public function get_attributes($request_struct=array(), $k = 'meta_struct') 
    { 
        $return_array = array (); 
        try{ 
            $attributes = $this->query_assoc($request_struct); 
            if(!empty($attributes)){ 
                foreach($attributes as $attribute){ 
                    $attribute = coding::decode_attribute($attribute, $k); 
                    $return_array[$attribute['id']] = $attribute; 
                } 
            } 
            return $return_array; 
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    }
     
    /**
     * 获取经过格式化的规格及规格项数组
     * @param array $request_struct  请求结构体
     * @param string $mapping_name  attributeoption 在其所属 attribute 的数组中的索引
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
    public function get_attribute_options($request_struct, $mapping_name = 'options') 
    { 
        $return_array = array (); 
        $attributes = $this->query_assoc($request_struct);
        if(!empty($attributes)){ 
            foreach($attributes as $attribute){ 
                $attribute[$mapping_name] = array (); 
                $return_array[$attribute['id']] = $attribute; 
            } 
            $attribute_ids = array_keys($return_array);

            $attribute_values = ORM::factory('attribute_value')->in('attribute_id', $attribute_ids)->find_all();

            foreach($attribute_values as $attribute_value)
            {
                $attribute_id = $attribute_value->attribute_id;
                if(isset($return_array[$attribute_id])){
                    $return_array[$attribute_id][$mapping_name][$attribute_value->id] = $attribute_value->as_array(); 
                }
            }
        }
        
        return $return_array; 
    } 

    //规格&值
    public function get_attribute_spec_options($mapping_name = 'options') 
    {
       $query_struct = array (
           'where' => array (                    
               'apply' => self::ATTRIBUTE_SPEC
           ),
           'orderby' => array (                    
               'id' => 'DESC' 
           )
        );
       return $this->get_attribute_options($query_struct, $mapping_name);
    }
    
    //特性值
    public function get_attribute_feature_options($mapping_name = 'options') 
    {
       $query_struct = array (
           'where' => array (                    
               'apply' => self::ATTRIBUTE_FEATURE
           ),
           'orderby' => array (                    
               'id' => 'DESC' 
           )
        );
       return $this->get_attribute_options($query_struct, $mapping_name);
    }
     
    /**
     * 通过 attribute_id, 值数组，更新该 attribute的值
     * @param  array $val  更新的选项数组
     */ 
    public function save_attribute_value($val = array()) 
    {
        $val['id'] = isset($val['id'])?$val['id']:null;
        $attribute_values = ORM::factory('attribute_value', $val['id']);
        if($val['id'] && $attribute_values->loaded == FALSE){
            throw new MyRuntimeException('object not found',404);
        }
        $data = $attribute_values->as_array();
        foreach($val as $k =>$v){
            array_key_exists($k, $data) && $attribute_values->$k = $v;
        }
        $attribute_values->save();
        return $attribute_values->id;
    }
    
    /**
     * 通过 attribute.id 判断该 attribute当前是否正在被关联
     * @param  int $attribute_id  规格ID
     * @return bool
     * @throws MyRuntimeException
     */ 
    public function is_relation_by_attribute_id($attribute_id) 
    {
            // 获取 attribute 下所有的 attributeoption
            $attributeoptions = $this->get_attributeoptions_by_attribute_id($attribute_id);
            if(!empty($attributeoptions)){ 
                // 获取所有 attributeoption 的 ID
                $attributeoption_ids = array_keys($attributeoptions);
                
                if(!empty($attributeoption_ids)){ 
                    // 初始化默认查询条件
                    $request_struct = array ( 
                        'where' => array ( 
                            'attributeoption_id' => $attributeoption_ids  
                        )  
                    );
                    
                    // 判断与  product_attributeoption_relations 表的关联
                    if(Product_attributeoption_relationService::get_instance()->count($request_struct)){ 
                        return TRUE; 
                    } 
                     
                    // 判断与  product_attributeoption_productpic_relations 表的关联
                    //if(Product_attributeoption_productpic_relationService::get_instance()->count($request_struct)){ 
                    //    return TRUE; 
                    //} 
                } 
            } 
             
            //判断与classify_attribute_relation 表的关联
            $request_struct = array ( 
                'where' => array ( 
                    'attribute_id' => $attribute_id  
                )  
            ); 
             
            if(Classify_attribute_relationService::get_instance()->count($request_struct)){ 
                return TRUE; 
            } 
             
            return FALSE; 
    } 
     
    /**
     * 通过 attribute_id 删除 attribute
     * @param  int $attribute_id 规格ID
     * @return void
     * @throws MyRuntimeException
     */ 
    public function delete_by_attribute_id($attribute_id) 
    { 
        try{ 
            if($this->is_relation_by_attribute_id($attribute_id)){ 
                throw new MyRuntimeException('该属性值已被商品数据关联，正在使用中，请取消关联之后重试', 500); 
            } 
            
            //删除图片附件 
            $attributeoptions = $this->get_attributeoptions_by_attribute_id($attribute_id); 
            foreach($attributeoptions as $option){ 
                if(isset($option['image']) && !empty($option['image']) && !empty($option['image'][0])){ 
                    $image_id = $option['image'][0]; 
                    $this->delete_attachment($image_id); 
                }
            }
            ORM::factory('attribute_value')->where('attribute_id', $attribute_id)->delete_all(); 
            $this->remove($attribute_id); 
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    } 
     
    /** 
     * 批量 删除 attribute 
     * @param  $attributes array 规格ID数组 
     * @return void 
     * @throws MyRuntimeException 
     */ 
    public function delete_attributes($attributes) 
    { 
        try{ 
            $del_all = true; 
            if(!empty($attributes)){ 
                foreach($attributes as $val){ 
                    if($this->is_relation_by_attribute_id($val)){ 
                        $del_all = false; 
                    }else{ 
                        //删除图片附件 
                        $attributeoptions = $this->get_attributeoptions_by_attribute_id($val); 
                        foreach($attributeoptions as $option){ 
                            if(isset($option['meta_struct']['image']) && !empty($option['meta_struct']['image']) && !empty($option['meta_struct']['image'][0])){ 
                                $image_id = $option['meta_struct']['image'][0]; 
                                $this->delete_attachment($image_id); 
                            } 
                        } 
                        ORM::factory('attribute_value')->where('attribute_id', $val)->delete_all();
                        $this->remove($val); 
                    } 
                } 
                if(!$del_all){ 
                    throw new MyRuntimeException('无法删除部分被关联的规格', 500); 
                } 
            } 
        }catch(MyRuntimeException $ex){ 
            throw $ex; 
        } 
    } 
     
    /** 
     * 删除对应的附件数据和存储文件 
     * @param int $id 附件id 
     */ 
    public function delete_attachment($image_id) 
    { 
        // 调用附件服务 
        /*require_once (Kohana::find_file('vendor', 'phprpc/phprpc_client', TRUE)); 
        !isset($attachmentService) && $attachmentService = new PHPRPC_Client(Kohana::config('phprpc.remote.Attachment.host')); 
        !isset($phprpcApiKey) && $phprpcApiKey = Kohana::config('phprpc.remote.Attachment.apiKey'); 
         
        $args = array ( 
            $id  
        );
        $sign = md5(json_encode($args) . $phprpcApiKey); 
        $attachmentService->phprpc_removeAttachmentDataByAttachmentId($id, $sign); 
        */
        AttService::get_instance("att")->delete_img($image_id);
    } 
     
    /** 
     * 获取附件url 
     * @param $prefix 
     * @param $attachment_id 
     * @param $stand 
     * @param $postfix 
     */ 
    public static function get_attach_url($attachment_id = 0, $stand = 'ti', $prefix = NULL, $postfix = NULL, $mask = NULL) 
    { 
        $current_prefix = $prefix == NULL ? Kohana::config('attach.routePrefix') : $prefix; 
        $presets = Kohana::config('attach.sizePresets'); 
        $current_preset_string = !empty($presets[$stand]) ? '_' . $presets[$stand] : ''; 
        $current_postfix = $postfix == NULL ? Kohana::config('attach.defaultPostfix') : $postfix; 
        $current_postfix_string = !empty($current_postfix) ? '.' . $current_postfix : ''; 
        $current_mask_string = $mask == NULL ? Kohana::config('attach.routeMaskView') : $mask; 
        return $current_prefix . str_replace(array ( 
            '#id#',  
            '#preset#',  
            '#postfix#'  
        ), array ( 
            $attachment_id,  
            $current_preset_string,  
            $current_postfix_string  
        ), $current_mask_string); 
    } 
    
    /**
     * 根据name_manage[管理名称]检查是否重复
     * @param $site_id  int
     * @param $name_manage  string
     * @return bool     
    public function check_exist_name_manage($name_manage)
    {
        $query_struct = array (
            'where' => array (
                'name_manage' => $name_manage 
            ) 
        );
        if($this->count($query_struct))
        return true;
    }*/
}
