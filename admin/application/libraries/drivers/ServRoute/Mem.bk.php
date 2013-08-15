<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 路由驱动 - Mem
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

class ServRoute_Mem_Driver extends ServRoute_Driver {
    private static $type = '';
    public function __construct($defaultSetup=NULL,$thisArgs=NULL){
        parent::__construct($defaultSetup,$thisArgs);
        $className = __CLASS__;
        $tmpInf = explode('_',$className);
        self::$type = strtolower($tmpInf[1]);
        $this->setDefault();
        $this->setup($thisArgs);
    }
    public function getSetupArray($configPath=NULL){
        $currentConfigPath = PROJECT_ROOT.'var/etc/web/'.self::$type.'/setup.ini';
        //现有配置为空
        if(empty($this->setupArray)){ 
            if(!empty($configPath)){ //指定配置
                $currentConfigPath = $configPath;
            }
            if(is_file($currentConfigPath)){
                $getConfigArray = parse_ini_file($currentConfigPath,TRUE);
                if(!empty($getConfigArray)){
                    $this->setupArray = $getConfigArray;
                    $this->currentConfigPath = $currentConfigPath;
                }
            }
        }else{//现有配置不为空
            if(!empty($configPath) && $configPath!=$this->currentConfigPath){//新配置和现有的不同
                $currentConfigPath = $configPath;
                if(is_file($currentConfigPath)){
                    $getConfigArray = parse_ini_file($currentConfigPath,TRUE);
                    if(!empty($getConfigArray)){
                        $this->setupArray = $getConfigArray;
                        $this->currentConfigPath = $currentConfigPath;
                    }
                }
            }
        }
        return $this->setupArray;
    }
    public function setDefault($defaultSetup = NULL){
        if(!empty($defaultSetup)){
            $this->defaultSetup = $this->filterVars($defaultSetup);
        }else{
            $this->getSetupArray();
            if(!empty($this->setupArray) && array_key_exists('default',$this->setupArray)){
                $this->defaultSetup = $this->filterVars($this->setupArray['default']);
            }else{
                if(!empty($this->rootDefaultSetup)){
                    $this->defaultSetup = $this->rootDefaultSetup;
                }
            }
        }
    }
    public function setup($thisArgs = NULL){
        if(!empty($thisArgs)){
            $this->thisArgs = $thisArgs;
            $routeKeyBase = $thisArgs[0];
            $separateId = '';
            $attributes = $thisArgs[1];
            switch ($routeKeyBase){
                case 'attachment':
                        $attachmentId=array_key_exists('id',$attributes)?$attributes['id']:0;
                        //TODO 根据不同的设置配置到不同的设定上去
                        if($attachmentId%2==0){
                            $separateId=2;
                        }else{
                            $separateId=1;
                        }
                    break;
                case 'store':
                        $storeId=array_key_exists('id',$attributes)?$attributes['id']:0;
                        //TODO 根据不同的设置配置到不同的设定上去
                        if($storeId%2==0){
                            $separateId=2;
                        }else{
                            $separateId=1;
                        }
                    break;
                case 'productpic':
                        $productpicId=array_key_exists('id',$attributes)?$attributes['id']:0;
                        //TODO 根据不同的设置配置到不同的设定上去
                        if($productpicId%2==0){
                            $separateId=2;
                        }else{
                            $separateId=1;
                        }
                    break;
                case 'default':
                default:
                    $routeKeyBase = 'default';
                    $separateId = '';
            }
            $this->routeKey = $routeKeyBase.$separateId;
            $this->getSetupArray();
            if(!empty($this->setupArray) && array_key_exists($this->routeKey,$this->setupArray)){
                $currentSetup = $this->filterVars($this->setupArray[$this->routeKey]);
                $this->currentSetup = array_merge($this->defaultSetup,$currentSetup);
            }else{
                throw new ServRouteDriverException('required Configure not found',404);
            }
            $this->thisArgs = NULL;
        }
        empty($this->currentSetup) && $this->currentSetup = $this->defaultSetup;
        empty($this->routeKey) && $this->routeKey = 'default';
    }
}