<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 实例管理
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

/* 自定义的ServRouteInstance异常 */
class ServRouteInstanceException extends Myruntimeexception{ }

class ServRouteInstance {
    private static $instance = NULL;

    private $configObject = NULL;

    private $instancePools = array();

    private function __construct($configObject){
        try{
            $this->configObject = $configObject;
        }catch(Exception $ex){
            throw new ServRouteInstanceException('ServRouteInstance Init Error',500);
        }
    }
    // 获取单态实例
    public static function getInstance($configObject){
        if(self::$instance === null){
            //self::$instance = new ServRouteInstance($configObject);
            $classname = __CLASS__;
            self::$instance = new $classname($configObject);
        }
        return self::$instance;
    }

    /**
     * Handles methods that do not exist.
     *
     * @param   string  method name
     * @param   array   arguments
     * @return  void
     */
    public function __call($method, $args)
    {
        // get(Db/Mem/Tt/Fs)Instance($routeStamp)
        if(substr($method,0,3)=='get' && substr($method,-8)=='Instance'){
            $baseDriverKey = substr($method,3,strlen($method)-11);
            
            if($this->configObject->isExistsDriverKey($baseDriverKey)==FALSE){
                throw new ServRouteInstanceException('Instance.driver_not_supported:'.$baseDriverKey, 500);
            }
            // 路由驱动
            $routeDriver = 'ServRoute_'.$baseDriverKey.'_Driver';
            // 实例驱动
            $instanceDriver = 'ServInstance_'.$baseDriverKey.'_Driver';
            if (!Kohana::auto_load($routeDriver)){
                throw new ServRouteInstanceException('Instance.route_driver_not_found:'.$baseDriverKey, 500);
            }
            if(!Kohana::auto_load($instanceDriver)){
                throw new ServRouteInstanceException('Instance.instance_driver_not_found:'.$baseDriverKey, 500);
            }
            
            // 实例池
            $driverInstancePoolName = $baseDriverKey;
            if(!array_key_exists($driverInstancePoolName,$this->instancePools)){
                $this->instancePools[$driverInstancePoolName] = array();
            }
            // fast but unflexible
            $thisRouteClassInstance = new $routeDriver($this->configObject->getDriverSetup($baseDriverKey),$args);
            // slow but flexible
            //$rc = new ReflectionClass($routeDriver);$thisRouteClassInstance = $rc->newInstanceArgs($args);
            if(!($thisRouteClassInstance instanceof ServRoute_Driver) || !is_subclass_of($thisRouteClassInstance, 'ServRoute_Driver')){
                throw new ServRouteInstanceException('Instance.route_driver_implements:'.$driverKey,500);
            }
            // 路由key
            $routeKey = $thisRouteClassInstance->getRouteKey();
            
            // 初始化Pool中的routeKey
            !isset($this->instancePools[$driverInstancePoolName][$routeKey]) && $this->instancePools[$driverInstancePoolName][$routeKey]=false;
            if($this->instancePools[$driverInstancePoolName][$routeKey]!==FALSE && $this->instancePools[$driverInstancePoolName][$routeKey]->isAvailable()==TRUE){
                // 池中有对应的实例并且可用
                return $this->instancePools[$driverInstancePoolName][$routeKey];
            }
            
            // 设定池中的实例
            $thisInstanceClassInstance = new $instanceDriver($thisRouteClassInstance);
            if(!($thisInstanceClassInstance instanceof ServInstance_Driver) || !is_subclass_of($thisInstanceClassInstance, 'ServInstance_Driver')){
                throw new ServRouteInstanceException('Instance.instance_driver_implements:'.$driverKey,500);
            }
            $this->instancePools[$driverInstancePoolName][$routeKey] = $thisInstanceClassInstance;
            
            if($this->instancePools[$driverInstancePoolName][$routeKey]!==FALSE){
                $this->instancePools[$driverInstancePoolName][$routeKey]->isAvailable() or $this->instancePools[$driverInstancePoolName][$routeKey]->setup();
                if($this->instancePools[$driverInstancePoolName][$routeKey]->isAvailable()!=TRUE){
                    throw new ServRouteInstanceException('Instance.getInstance Failed,Service Not Available.',500);
                }
                // 池中有对应的实例并且可用
                return $this->instancePools[$driverInstancePoolName][$routeKey];
            }
            throw new ServRouteInstanceException('Instance.getInstance Failed,critical error',500);
        }else{
            throw new ServRouteInstanceException('Unknown Method',500);
        }
    }
}