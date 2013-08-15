<?php defined('SYSPATH') OR die('No direct access allowed.');

class Delivery_countryService_Core extends DefaultService_Core {
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
    
    public function get_delivery_country($delivery_id, $country_id){
    	$delivery_country = array();
    	$where = array('delivery_id' => $delivery_id,
    				   'country_id'  => $country_id,
    	              );
    	$query_struct = array('where' => $where);
    	$delivery_country = $this->query_row($query_struct);
    	return $delivery_country;
    }
}