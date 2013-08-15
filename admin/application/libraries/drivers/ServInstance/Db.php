<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 实例驱动 - Db
 * @package lemon
 * @author nickfan<nickfan81@gmail.com>
 * @link http://axiong.me
 * @version $Id$
 */

class ServInstance_Db_Driver extends ServInstance_Driver {
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
       require_once(Kohana::find_file('vendor', 'ez_sql/shared/ez_sql_core',TRUE));
       require_once(Kohana::find_file('vendor', 'ez_sql/mysql/ez_sql_mysql',TRUE));
       $curInst = new ezSQL_mysql($settings['dbUser'], $settings['dbPasswd'], $settings['dbSchema'], $settings['dbHost']);
       $curInst->cache_timeout = $settings['dbCacheTimeout'];
       $curInst->cache_dir = $settings['dbDiskCachePath'];
       $curInst->use_disk_cache = $settings['dbCache']==1;
       $curInst->cache_queries = $settings['dbCache']==1;
       if($settings['dbShowError']==1){
           $curInst->show_errors();
       }else{
           $curInst->hide_errors();
       }
       $curInst->set_charset('utf8');
       //$curInst->quick_connect($settings['dbUser'], $settings['dbPasswd'], $settings['dbSchema'], $settings['dbHost']);
       $this->instance = $curInst;
       //$this->isAvailable = $this->instance->dbh?TRUE:FALSE;
       $this->isAvailable = TRUE;
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