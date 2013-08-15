<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 配置对象
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

/* 自定义的Configure异常 */
class ServRouteConfigException extends Myruntimeexception{ }

class ServRouteConfig {
    private static $instance = NULL;

    private $configObject = NULL;
    private $drivers = array();
    private function __construct($configObject=NULL){
        try{
            $this->loadConfig($configObject);
        }catch(Exception $ex){
            throw new ServRouteConfigException('Configure Init Error',500);
        }
    }
    // 获取单态实例
    public static function getInstance($configObject=NULL){
        if(self::$instance === null){
            //self::$instance = new InstanceService($configObject);
            $classname = __CLASS__;
            self::$instance = new $classname($configObject);
        }
        return self::$instance;
    }

    /**
     * 读取解析配置对象(array)
     * @param array $configObject
     */
    public function loadConfig($configObject=NULL){
        if($configObject==NULL){
            $configPath = Kohana::config('instance.configPath');
            empty($configPath) && $configPath = PROJECT_ROOT.'etc/web/instance.ini';
            if(!is_file($configPath)){
                throw new ServRouteConfigException('defaultConfigureObject Not Found',404);
            }
            $thisConfigObject=parse_ini_file($configPath,TRUE);
        }else{
           $thisConfigObject = $configObject;
        }
        $drivers = array();
        $cfgKeys = !is_null($thisConfigObject)?array_keys($thisConfigObject):NULL;
        if(!empty($cfgKeys)){
            foreach($cfgKeys as $cfgKey){
                if(substr($cfgKey,0,8) == 'Instance'){
                    $drivers[substr($cfgKey,8)] = $thisConfigObject[$cfgKey];
                }
            }
        }
        $this->configObject = $thisConfigObject;
        $this->drivers = $drivers;
    }

    /**
     * 检测是否有对应的驱动配置
     * @param $driverKey
     */
    public function isExistsDriverKey($driverKey){
        return array_key_exists($driverKey,$this->drivers);
    }

    /**
     * 获取 对应驱动的默认配置信息
     * @param string $driverKey
     */
    public function getDriverSetup($driverKey){
        if(!array_key_exists($driverKey,$this->drivers)){
            throw new ServRouteConfigException('configureDriver Not Found',404);
        }
        return $this->drivers[$driverKey];
    }
}
