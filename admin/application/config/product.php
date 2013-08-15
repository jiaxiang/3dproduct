<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * carrier 
 */
$config['carrier_type'] = array(
	'0'	=> '统一定价',
	'1'	=> '基于价格衡量',
	'2'	=> '基于重量衡量',
	'3'	=> '基于数量衡量',
);


$config['order_product_type'] = array(
	'1'	=> array(
		'name'			=> '正常商品',
		'value_type'	=> array(
			'1'		=> '正常商品',
			'2'		=> '配件',
		)
	),
	'2'	=> array(
		'name'			=> '赠品',
		'value_type'	=> array(
			'1'		=> '赠品',
		)
	),
	'3'	=> array(
		'name'			=> '捆绑商品',
		'value_type'	=> array(
			'1'		=> '捆绑商品',
		)
	),
	'4'	=> array(
		'name'			=> '定制商品',
		'value_type'	=> array(
			'1'		=> '定制商品',
		)
	),
);


$config['import_tmp_dir'] = PROJECT_ROOT.'var/tmp/pip';
$config['export_tmp_dir'] = PROJECT_ROOT.'var/tmp/pec';

$config['featureoption_toplimit'] = 50;