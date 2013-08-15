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
    'o'=>'',        // 原图
	'ti'=>'40x40',  // 微图
	'sq'=>'60x60',  // 方图
    't'=>'120x120', // 缩略图
	's'=>'160x160', // 小图
    'm'=>'180x180', // 中图
    'l'=>'300x300', // 大图
);
$config['routeMaskView'] = '#id##preset##postfix#';
$config['routeMaskViewProduct'] = 'product/#id##preset##postfix#';

/* 站点商品图片附件上传配置 */
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