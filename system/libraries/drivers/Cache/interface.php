<?php defined('SYSPATH') OR die('Access denied!');

/**
 * 缓存驱动接口
 *
 * @author 王浩
 * @version 1.0
 */
interface Cache_Interface {
    
    /**
     * 构造函数
     *
     * @param array $option
     */
    public function __construct($option);
    
    /**
     * 析构函数
     */
    public function __destruct();
    
    /**
     * 读取缓存
     *
     * @param string $key
     * @return mixed
     */
    public function get($key);
    
    /**
     * 写入缓存
     *
     * @param string $key
     * @param mixed $value
     * @param integer $expire
     * @return boolean
     */
    public function set($key, $value, $expire = NULL);
    
    /**
     * 删除缓存
     *
     * @param string $key
     * @return mixed
     */
    public function remove($key);
    
    /**
     * 清空缓存
     *
     * @return boolean
     */
    public function flush();
}