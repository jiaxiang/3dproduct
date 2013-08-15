<?php defined('SYSPATH') OR die('No direct access allowed.');

/*
 * 系统附件相关配置
 */

/* 附件默认缓存的时间 */
$config['httpCacheTimeDefault'] = NULL;

/* 附件web访问基础路由 */
$config['routePrefix'] = '/att/';
$config['defaultPostfix'] = 'jpg';
$config['sizePresets']=array(
    'o'   => '',
	'40'  => '40x40',
	'50'  => '50x50',
	'60'  => '60x60',
	'70'  => '70x70',
	'80'  => '80x80',
	'90'  => '90x90',
	'100' => '100x100',
	'110' => '110x110',
    '120' => '120x120',
    '130' => '130x130',
    '140' => '140x140',
    '150' => '150x150',
    '160' => '160x160',
	'170' => '170x170',
    '180' => '180x180',
    '190' => '190x190',
    '200' => '200x200',
	'300' => '300x300',
	'360' => '360x360',
	'400' => '400x400',
	'ti'  => '40x40',   // 微图
	'sq'  => '60x60',   // 方图
	't'   => '120x120', // 缩略图
	's'   => '160x160', // 小图
	'm'   => '180x180', // 中图
    'l'   => '300x300', // 大图
);
//$config['routeMaskView'] = '/attachment/view/#id##preset##postfix#';
$config['routeMaskView'] = '#id##preset##postfix#';
$config['routeMaskViewProduct'] = 'product/'.$config['routeMaskView'];
$config['routeMaskViewCategory'] = 'category/'.$config['routeMaskView'];

/* 站点产品图片附件上传配置 */
$config['productPicAttach']=array();
$config['productPicAttach']['allowTypes'] = array(
    'gif','png','jpg','jpeg',
    //'bmp','tif','tiff',
//    'swf',
//    'doc','docx','ppt','pps','txt','rtf','pdf',
//    'zip','rar','7z','tgz','gz','tar'
);
$config['productPicAttach']['thumbPresets'] = array(
    '40x40',   // 微图
    '60x60',   // 方图
    '120x120', // 缩略图
    '160x160', // 小图
    '180x180', // 中图
    '300x300', // 大图
);
$config['productPicAttach']['fileCountLimit'] = 5; // 5 attachement file
$config['productPicAttach']['fileSizePreLimit'] = 1048576; // 1048576 (1M)
$config['productPicAttach']['fileSizeTotalLimit'] = 5242880; // 5*1048576 (5M)

/* 站点附件上传配置 */
$config['sitePicAttach']=array();
$config['sitePicAttach']['allowTypes'] = array(
    'gif','png','jpg','jpeg',
    //'bmp','tif','tiff',
//    'swf',
//    'doc','docx','ppt','pps','txt','rtf','pdf',
//    'zip','rar','7z','tgz','gz','tar'
);
$config['sitePicAttach']['fileCountLimit'] = 5; // 5 attachement file
$config['sitePicAttach']['fileSizePreLimit'] = 1048576; // 1048576 (1M)
$config['sitePicAttach']['fileSizeTotalLimit'] = 5242880; // 5*1048576 (5M)