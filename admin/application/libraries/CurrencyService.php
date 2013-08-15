<?php 
defined('SYSPATH') or die('No direct access allowed.'); 
 
class CurrencyService_Core extends DefaultService_Core { 
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
} 
