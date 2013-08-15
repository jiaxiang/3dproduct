<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 实例驱动 - Mem
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

class ServInstance_Mem_Driver extends ServInstance_Driver {
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
       $memHostsArr=explode(',',rtrim($settings['memHosts'],','));
       $curInst=new Memcache;
       foreach($memHostsArr as $memHost){
            $curMemHostInfo= explode(':',$memHost);
            $curInst->addServer($curMemHostInfo[0],isset($curMemHostInfo[1])?$curMemHostInfo[1]:11211);
       }
       //added by Zhou Hui(zhou_hui@live.com) on July 12nd, 2010
       //memcached的数据必须压缩，大于4k的以0.2的比率进行压缩
       $curInst->setcompressthreshold(4096, 0.2);
       //add end
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