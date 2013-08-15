<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 实例驱动 - Tt
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

class ServInstance_Tt_Driver extends ServInstance_Driver {
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
       $curHostInfo=explode(':',$settings['ttHost']);
       $curInst = new TokyoTyrant($curHostInfo[0], isset($curHostInfo[1])?$curHostInfo[1]:TokyoTyrant::RDBDEF_PORT);
       $this->instance = $curInst;
       $this->isAvailable = $this->instance?TRUE:FALSE;
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