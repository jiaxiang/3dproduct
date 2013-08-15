<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * KC配置文件
 *
 * @author weizhifeng
 **/
$config['disabled'] = false;
$config['readonly'] = false;

// 主题 位于images/kc/themes/下
$config['theme'] = 'oxygen';

// 允许的文件类型
$config['aollowedExts'] = array('jpg', 'jpeg', 'png', 'gif');

// 最大容量100M
$config['maxSize'] = 100*1024*1024;

// 字符集
$config['charset'] = 'UTF-8';

// lang
$config['lang'] = 'zh_CN';

// type
$config['type'] = 'image';

$config['types'] = array
	(
		// CKEditor & FCKEditor types
        'files'   =>  '',
        'flash'   =>  'swf',
        'images'  =>  '*img',

        // TinyMCE types
        'file'    =>  '',
        'media'   =>  'swf flv avi mpg mpeg qt mov wmv asf rm',
        'image'   =>  '*img'
	);
	
$config['thumbWidth'] = 100;
$config['thumbHeight'] = 100;

$config['cookieDomain'] = '';
$config['cookiePath'] = '/';
$config['cookiePrefix'] = 'KCFINDER_';

// THE FOLLOWING SETTINGS CANNOT BE OVERRIDED WITH SESSION CONFIGURATION
$config['_check4htaccess'] = true;
$config['_tinyMCEPath'] = '/js/tiny_mce';
$config['_sessionVar'] = &$_SESSION['KCFINDER'];

// session
/*$config['_sessionLifetime'] = 30;
$config['_sessionDir'] = '/full/directory/path';
$config['_sessionDomain'] = '.mysite.com';
$config['_sessionPath'] = '/my/path';*/