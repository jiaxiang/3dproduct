<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * @package  create a new site flow
 *
 */

$config['name'] = 'Login';

/* 默认缓存的时间 */
$config['httpCacheTimeDefault'] = 60;

$config['manager_flow'] = array(
	array(
		'flag'=>'manager',
		'name'=>'新建商户帐号',
		'url'=>url::base() . 'manage/manager/add'
	),
	array(
		'flag'=>'domain',
		'name'=>'新建域名',
		'url'=>url::base() . 'manage/domain/add'
	),
	array(
		'flag'=>'site',
		'name'=>'新建站点',
		'url'=>url::base() . 'manage/site/add'
	),
	array(
		'flag'=>'payment',
		'name'=>'添加支付帐号',
		'url'=>url::base() . 'manage/payment/flow_add'
	)
);

$config['site_config_flow'] = array(
	array(
		'flag'=>'theme',
		'name'=>'模板',
		'url'=>url::base() . 'site/theme/add'
	),
	array(
		'flag'=>'route',
		'name'=>'URL',
		'url'=>url::base() . 'site/route'
	),
	array(
		'flag'=>'mail',
		'name'=>'邮件',
		'url'=>url::base() . 'site/mail'
	),
	array(
		'flag'=>'menu',
		'name'=>'导航',
		'url'=>url::base() . 'site/menu'
	),
	array(
		'flag'=>'faq',
		'name'=>'FAQ',
		'url'=>url::base() . 'site/faq'
	),
	array(
		'flag'=>'doc',
		'name'=>'文案',
		'url'=>url::base() . 'site/faq'
	),
	array(
		'flag'=>'seo',
		'name'=>'SEO',
		'url'=>url::base() . 'site/seo'
	),
	array(
		'flag'=>'country',
		'name'=>'国家',
		'url'=>url::base() . 'site/country'
	),
	array(
		'flag'=>'carrier',
		'name'=>'物流',
		'url'=>url::base() . 'site/carrier'
	),
	array(
		'flag'=>'currency',
		'name'=>'币种',
		'url'=>url::base() . 'site/currency'
	)
);

/**
 * 站点类型
 */
$config['site_categories'] = array(
	'0'=>'B2C',
	'1'=>'B2B'
);
