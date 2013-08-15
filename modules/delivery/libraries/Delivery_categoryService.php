<?php defined('SYSPATH') OR die('No direct access allowed.');

class Delivery_categoryService_Core extends DefaultService_Core {
	protected $serv_route_instance = NULL;
	/* 兼容php5.2环境 Start */
    private static $instance = NULL;
    // 获取单态实例
    public static function get_instance()
    {
        if(self::$instance === null)
        {
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    
    public function get_name_by_id($id){
    	$query_struct = array(
    		'where' => array(
    			'id' => $id,
    		),
    	);
    	$cat = $this->query_row($query_struct);
    	if (empty($cat)) {
    		return 'default';
    	}else {
    		return $cat['name'];
    	}
    }
}
