<?php defined('SYSPATH') OR die('Access denied!');

/**
 * 缓存服务器配置
 */
$config['sources'] = array(
    'default' => array(
        'driver' => 'Mem',
        'option' => array(
            'host'   => '127.0.0.1',
            'port'   => 11211,
            'expire' => 3600,
            'flag'   => MEMCACHE_COMPRESSED,
        ),
    ),
);

/**
 * 缓存驱动名称
 */
$config['driver'] = 'Cache_%s_Driver';
    
/**
 * 缓存类型路由
 */
$config['routes'] = array();
    
/**
 * 启用本地缓冲
 */
$config['buffer'] = array();
    
/**
 * 禁用缓存类型
 */
$config['denied'] = array();