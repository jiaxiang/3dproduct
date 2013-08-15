<?php defined('SYSPATH') OR die('No direct access allowed.');

class Product_assemblyService_Core extends DefaultService_Core {
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
    
    /*
     * 获得简单商品数据
     * param. int $assembly_id
     * return array();
     */
    public function get_good_data_array($assembly_id)
    {
        return $this->index(array('where'=>array('assembly_id'=>$assembly_id)));
    }
    
    /*
     * 获得简单商品的id
     * param. int $assembly_id
     * return array();
     */
    public function get_good_id_array($assembly_id)
    {
        $id_array = array();
        $datas = $this->get_good_data_array($assembly_id);
        foreach($datas as $data)
        {
            $id_array[] = $data['product_id'];
        }
        return $id_array;
    }
    
    /*
     * 获得可配置商品的数据
     * param. int $good_id
     * return array();
     */
    public function get_configurable_product($good_id)
    {
        return array_shift($this->query_assoc(array(
            'where'=>array(
                'assembly_type'=>ProductService::PRODUCT_TYPE_CONFIGURABLE, 
                'product_id'=>$good_id
            )
        )));
    }
    
}