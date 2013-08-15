<?php defined('SYSPATH') OR die('No direct access allowed.');
$config['spf'] =  array (
    'result_type' => array(
        '2' => 'H',
        '1' => 'A',
    
    ),
    'result_cn' => array(
        '2' => '主负',
        '1' => '主胜'
    )
);
$config['rfsf'] =  array (
    'result_type' => array(
        '2' => 'h',
        '1' => 'a',
    ),
    'result_cn' => array(
        '2' => '主负',
        '1' => '主胜'
    ),
    'result_cn2' => array(
        '让分主负' => '2',
        '让分主胜' => '1'
    )
);
$config['dxf'] =  array (
    'result_type' => array(
        /* '2' => 'h',
        '1' => 'a', */
		'1' => 'h',
		'2' => 'a',
    ),
    'result_cn' => array(
        /* '2' => '大分',
        '1' => '小分',
        '0' => '负', */
		'1' => '大分',
		'2' => '小分',
		'0' => '负',
    ),
    'result_cn2' => array(
        //'大' => '2',
        //'小' => '1'
    	'大' => '1',
    	'小' => '2',
    )
);
$config['sfc'] =  array (
    'result_type' => array(
        '01' => '主胜1-5',
        '02' => '主胜6-10',
		'03' => '主胜11-15',
		'04' => '主胜16-20',
		'05' => '主胜21-25',
		'06' => '主胜26+',
        '11' => '客胜1-5',
        '12' => '客胜6-10',
		'13' => '客胜11-15',
		'14' => '客胜16-20',
		'15' => '客胜21-25',
		'16' => '客胜26+',
    )
);

$config['zjqs'] =  array (
    'result_type' => array(
        '0' => '0',
        '1' => '1',
        '2' => '2',
		'3' => '3',
		'4' => '4',
		'5' => '5',
		'6' => '6',
		'7+' => '7',
    )
);



