<?php defined('SYSPATH') or die('No direct access allowed.');

class Attribute_valueService_Core extends DefaultService_Core {
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
     * 通过 attributeoption_id 判断该 attributeoption 当前是否正在被关联
     * @param  int || array $attributeoption_id 规格项ID或ID数组
     * @return bool
     */
    public function is_relation_by_attribute_value_id($attributeoption_id)
    {
        // 初始化默认查询条件
        $request_struct = array(
    	    'where'		=> array( 
    	        'attributeoption_id' => $attributeoption_id,
    		)
        );
    	
    	// 判断与  product_attributeoption_relations 表的关联
    	if (Product_attributeoption_relationService::get_instance()->count($request_struct)) {
    		return TRUE;
    	}
    	
    	// 判断与  product_attributeoption_productpic_relations 表的关联
    	if (Product_attributeoption_productpic_relationService::get_instance()->count($request_struct)) {
    		return TRUE;
    	}
    	return FALSE;
    }
    //兼容商舟
    function is_relation_by_attributeoption_id($attributeoption_id){
        return $this->is_relation_by_attribute_value_id($attributeoption_id);        
    }
    
    /**
     * 通过 id 删除 attributeoption
     * @param  int || array $attributeoption_id 规格项ID或ID数组
     * @return void 
     */
    public function delete_by_attribute_value_id($attribute_value_id)
    {       
        $qs = array (
            'id' => $attribute_value_id
        );     
        $attv = $this->read($qs);
        if(isset($attv['image']))
        {
            $img = explode('|', $attv['image']);
            isset($img[0]) && $this->delete_img($img[0]);
        }
        $this->delete($qs);
        return true;
    }
    
    /**
     * 通过 attribute_value_array 删除 attributeoption
     * @param 规格项数组
     * @return void
     */    
    public function delete_by_value_array($attv)
    {
        if(isset($attv['image']))
        {
            $img = explode('|', $attv['image']);
            isset($img[0]) && $this->delete_img($img[0]);
        }
        $attv['id']>0 && $this->delete(array (
            'id' => $attv['id']
        ));
        return true;
    }
    
    /**
     * 删除商品对应的附件数据和存储文件
     * @param $image_id
     */
    private function delete_img($image_id)
    {
        !empty($image_id) && AttService::get_instance("att")->delete_img($image_id);
    }
    
}
