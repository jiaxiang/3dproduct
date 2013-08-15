<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 路由驱动
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

/* 自定义的Route异常 */
class ServRouteDriverException extends MyRuntimeException{ }

abstract class ServRoute_Driver {
    protected $rootDefaultSetup = array();
    protected $defaultSetup = array();
    protected $currentSetup = array();
    protected $routeKey = '';
    protected $thisArgs = NULL;
    protected $setupArray = array();
    protected $currentConfigPath = NULL;
    public function __construct($defaultSetup=NULL,$thisArgs=NULL){
        $this->setRootDefault($defaultSetup);
        //$this->setDefault();
        //$this->setup($thisArgs);
        //!empty($thisArgs) && $this->thisArgs = $thisArgs;
        //empty($this->currentSetup) && $this->currentSetup = $this->defaultSetup;
        //empty($this->routeKey) && $this->routeKey = 'default';
    }
    /**
     * 根据当前参数设定当前setup配置
     * @param $thisArgs
     */
    public function setup($thisArgs = NULL){
        if(!empty($thisArgs)){
            $this->thisArgs = $thisArgs;
        }
        empty($this->currentSetup) && $this->currentSetup = $this->defaultSetup;
        empty($this->routeKey) && $this->routeKey = 'default';
    }
    /**
     * 根据默认配置设定默认设定
     * @param $defaultSetup
     */
    public function setRootDefault($defaultSetup = NULL){
        if(!empty($defaultSetup)){
            $this->rootDefaultSetup = $this->filterVars($defaultSetup);
        }
    }
    
    public function setDefault($defaultSetup = NULL){
        if(!empty($defaultSetup)){
            $this->defaultSetup = $this->filterVars($defaultSetup);
        }else{
            if(!empty($this->rootDefaultSetup)){
                $this->defaultSetup = $this->rootDefaultSetup;
            }
        }
    }
    /**
     * 获取路由key
     * @param unknown_type $thisArgs
     */
    public function getRouteKey($thisArgs = NULL){
        !empty($thisArgs) && $this->setup($thisArgs);
        return $this->routeKey;
    }
    /**
     * 获取当前配置信息
     * @param unknown_type $thisArgs
     */
    public function getSetup($thisArgs = NULL){
        !empty($thisArgs) && $this->setup($thisArgs);
        return $this->currentSetup;
    }
    /**
     * 获取当期参数信息
     */
    public function getArgs(){
        return $this->thisArgs;
    }
    
    public function getRouteGroupSetup($routeKey='default'){
        if($routeKey=='default'){
            return array($routeKey=>$this->defaultSetup);
        }else{
            $returnArray = array();
            if(!empty($this->setupArray)){
                foreach($this->setupArray as $routeLabel =>$routeSetup){
                    if(preg_match("/^".$routeKey."[0-9]+$/i",$routeLabel)){
                        $returnArray[$routeLabel]=$routeSetup;
                    }
                }
            }
            return $returnArray;
        }
    }
    /**
     * 替代配置中的固定变量
     * @param $array
     */
    public static function filterVars($array){
        return str_replace(array(
        '{project_root}'
        ),array(
        PROJECT_ROOT
        ),$array);
    }
}
