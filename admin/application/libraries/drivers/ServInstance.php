<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 实例服务驱动
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

/* 自定义的Instance异常 */
class ServInstanceDriverException extends MyRuntimeException{ }

abstract class ServInstance_Driver {
    protected $isAvailable = FALSE;
    public function __construct($routeInstance=NULL){
        $this->routeInstance=$routeInstance;
        //$this->setup();
    }
    public function isAvailable(){
        return $this->isAvailable;
    }
    /**
     * 执行设置
     */
    abstract function setup();
    /**
     * 获取实例
     */
    abstract function getInstance();
}