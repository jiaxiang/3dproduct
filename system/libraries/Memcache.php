<?php defined('SYSPATH') OR die('No direct access allowed!');
/**
 * Memcache 虚拟缓存组件
 * auth: zhu
 */
if(!class_exists('Memcache')){
    
    define('MEMCACHE_COMPRESSED', 1);
    class Memcache_Core {

        /*
         * Memcache 虚拟方法
         */
        static public function connect(){ }
        static public function get($k){ }
        static public function set($k){ }
        static public function addServer(){ }
        static public function setcompressthreshold(){ }
        static public function delete(){ }
        static public function remove(){ } 
    }
    
}