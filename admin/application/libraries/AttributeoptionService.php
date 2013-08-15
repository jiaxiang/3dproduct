<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * 规格项管理 Service
 * 
 * @author 王浩
 */
class AttributeoptionService_Core extends DefaultService_Core {
    /* 兼容php5.2环境 Start */
    private static $instance = NULL;
    // 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    /* 兼容php5.2环境 End */
    
    /**
     * 根据 attributeoption_id 获取该 attributeoption 所属 attribute
     * 
     * @param  int $attributeoption_id 规格项ID
     * @return array
     * @throws MyRuntimeException
     */
    public function get_attribute_by_attributeoption_id($attributeoption_id)
    {
    	try {
	    	$attributeoption = $this->get($attributeoption_id);
	    	$attribute_id    = $attributeoption['attribute_id'];
	    	return AttributeService::get_instance()->get($attribute_id);
    	} catch (MyRuntimeException $ex) {
    		throw $ex;
    	}
    }
    
    /**
     * 根据 attributeoption_id 获取该 attributeoption 的兄弟记录
     * 
     * @param  int  $attributeoption_id  规格项ID
     * @param  bool $clear_self          是否清除自身
     * @return array
     * @throws MyRuntimeException
     */
    public function get_brothers_by_attributeoption_id($attributeoption_id, $clear_self = FALSE)
    {
    	try {
	    	$attributeoption  = $this->get($attributeoption_id);
	    	$attribute_id     = $attributeoption['attribute_id'];
	    	$attributeoptions = AttributeService::get_instance()->get_attributeoptions_by_attribute_id($attribute_id);
	    	
	    	if ($clear_self == TRUE) {
	    		foreach ($attributeoptions as $index => $attributeoption) {
	    			if ($attributeoption['id'] == $attributeoption_id) {
	    				unset($attributeoptions[$index]);
	    			}
	    		}
	    	}
	    	
	    	return $attributeoptions;
    	} catch (MyRuntimeException $ex) {
    		throw $ex;
    	}
    }
    
    /**
     * 通过 attributeoption_id 判断该 attributeoption 当前是否正在被关联
     * @param  int || array $attributeoption_ids 规格项ID或ID数组
     * @return bool
     * @throws MyRuntimeException
     */
    public function is_relation_by_attributeoption_id($attributeoption_ids)
    {
    	try {
    		// 初始化默认查询条件
        	$request_struct = array(
	            'where'		=> array( 
	                'attributeoption_id' => $attributeoption_ids,
				)
        	);
        	
    		// 判断与 goods_attributeoption_relations 表的关联
	        if (Goods_attributeoption_relationService::get_instance()->count($request_struct)) {
	        	return TRUE;
	        }
	        
	        // 判断与  product_attributeoption_relations 表的关联
	        if (Product_attributeoption_relationService::get_instance()->count($request_struct)) {
	        	return TRUE;
	        }
	        
	    	// 判断与  product_attributeoption_productpic_relations 表的关联
	        if (Product_attributeoption_productpic_relationService::get_instance()->count($request_struct)) {
	        	return TRUE;
	        }
    	} catch (MyRuntimeException $ex) {
    		throw $ex;
    	}
    	
    	return FALSE;
    }
    
    /**
     * 通过 attributeoption.id 删除 attributeoption
     * @param  int || array $attributeoption_ids 规格项ID或ID数组
     * @return void
     * @throws MyRuntimeException
     */
    public function delete_by_attributeoption_id($attributeoption_ids)
    {
    	try {
    		if ($this->is_relation_by_attributeoption_id($attributeoption_ids)) {
    		    throw new MyRuntimeException('Hava relative data, delete is denied.', 500);
    		}
    		if(is_array($attributeoption_ids) && !empty($attributeoption_ids)){
    		    ORM::factory('attributeoption')->in('id', $attributeoption_ids)->delete_all();
    		}else{
    		    $this->remove($attributeoption_ids);
    		}
    	} catch (MyRuntimeException $ex) {
    		throw $ex;
    	}
    }
}