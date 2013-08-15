<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 实例驱动 - Fs
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

class ServInstance_Fs_Driver extends ServInstance_Driver {
    public $instance = NULL;
    private static $type = '';
    public function __construct($routeInstance=NULL){
        parent::__construct($routeInstance);

        $className = __CLASS__;
        $tmpInf = explode('_',$className);
        self::$type = strtolower($tmpInf[1]);
        $this->setup();
    }
    public function setup(){
       $settings = $this->routeInstance->getSetup();
       //TODO 调用对应的驱动配置
       require_once(Kohana::find_file('vendor', 'MixFS',TRUE));
       $this->instance = MixFS::factory($settings['fsBasePath'],$this->routeInstance->getRouteKey(),$settings['fsDomain']);
       $this->isAvailable = $this->instance->connect()===FALSE?FALSE:TRUE;
    }
    
    /**
     * 获取实例
     */
    public function getInstance ()
    {
        if(!$this->instance){
            $this->setup();
        }
        if($this->isAvailable!=TRUE){
            throw new ServRouteInstanceException('Instance init Error',500);
        }
        return $this->instance;
    }

}