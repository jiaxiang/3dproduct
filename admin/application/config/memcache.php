<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @package  Cache:Memcache
 *
 * memcache server configuration.
 */
$config['default'] = array
	(
		'server'=> array(
			array
			(
				'host' => '172.16.7.1',
				'port' => 10000,
				'persistent' => FALSE
			),
			array
			(
				'host' => '172.16.7.1',
				'port' => 10001,
				'persistent' => FALSE
			)
		),
		'expire' => 10,
		'compression' => FALSE
	);


$config['tt'] = array
	(
		'server'=> array(
			array
			(
				'host' => '172.16.7.5',
				'port' => 11000,
				'persistent' => FALSE
			),
			array
			(
				'host' => '172.16.7.5',
				'port' => 11001,
				'persistent' => FALSE
			)
		),
		'expire' => 1000,
		'compression' => FALSE
	);

$config['session'] = array
	(
		'server'=> array(
			array
			(
				'host' => '172.16.7.5',
				'port' => 10000,
				'persistent' => FALSE
			),
			array
			(
				'host' => '172.16.7.5',
				'port' => 10001,
				'persistent' => FALSE
			)
		),
		'expire' => 1000,
		'compression' => FALSE
	);

//商品图片TT
$config['product_tt'] = array
	(
		'server'=> array(
			array
			(
				'host' => '92.48.122.200',
				'port' => 21000,
				'persistent' => FALSE
			)
		),
		'expire' => 1000,
		'compression' => FALSE
	);
	
//站点资源TT
$config['site_tt'] = array
	(
		'server'=> array(
			array
			(
				'host' => '92.48.122.200',
				'port' => 21001,
				'persistent' => FALSE
			)
		),
		'expire' => 1000,
		'compression' => FALSE
	);

//主题资源TT
$config['theme_tt'] = array
	(
		'server'=> array(
			array
			(
				'host' => '92.48.122.200',
				'port' => 21002,
				'persistent' => FALSE
			)
		),
		'expire' => 1000,
		'compression' => FALSE
	);

//归类资源TT
$config['category_tt'] = array
	(
		'server'=> array(
			array
			(
				'host' => '92.48.122.200',
				'port' => 21003,
				'persistent' => FALSE
			)
		),
		'expire' => 1000,
		'compression' => FALSE
	);

//系统配置TT
$config['config_tt'] = array
	(
		'server'=> array(
			array
			(
				'host' => '92.48.122.200',
				'port' => 21004,
				'persistent' => FALSE
			)
		),
		'expire' => 1000,
		'compression' => FALSE
	);

//站点主题资源TT
$config['site_theme_tt'] = array
	(
		'server'=> array(
			array
			(
				'host' => '92.48.122.200',
				'port' => 21005,
				'persistent' => FALSE
			)
		),
		'expire' => 1000,
		'compression' => FALSE
	);

//公共资源TT
$config['public_tt'] = array
	(
		'server'=> array(
			array
			(
				'host' => '92.48.122.200',
				'port' => 21006,
				'persistent' => FALSE
			)
		),
		'expire' => 1000,
		'compression' => FALSE
	);

//支付图片TT
$config['payment_tt'] = array
	(
		'server'=> array(
			array
			(
				'host' => '92.48.122.200',
				'port' => 21007,
				'persistent' => FALSE
			)
		),
		'expire' => 1000,
		'compression' => FALSE
	);