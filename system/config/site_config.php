<?php defined('SYSPATH') OR die('No direct access allowed.');
$config['site']=array (
  	'type' => 1,
  	'site_title' => '3DLAB',
	'site_title2' => '3DLAB',
  	'site_email' => 'webmaster@3DLAB.com',
	'site_email2' => 'webmaster@3DLAB.com',
  	'name' => '3d.localhost.com',
  	'domain' => 'localhost.com',
	'keywords' => 'webmaster',
	'description' => 'webmaster',
	'kf_phone_num' => '',
	'kf_phone_num2' => '',
	'cz_phone_num' => '',
	'copyright' => 'Copyright © 2013',
	'company_name' => '3DLAB',
	'icp' => '沪ICP备000000号',
  	'logo' => NULL,
  	'twitter' => 'Twitter链接设置',
  	'facebook' => 'Facebook链接设置',
  	'youtube' => 'Youtube链接设置',
  	'trustwave' => 'Trustwave链接设置',
  	'macfee' => 'Macfee代码',
  	'livechat' => 'livechat代码',
  	'register_mail_active' => '0',
  	'register_mail_check_pwd' => '9bqVBhvhr3GwrwSE',    //验证邮箱密钥
	'secret_pwd' => '6vBJqUK4VpVJyECN',    //用户密码加密密钥
	'secret_tk' => 'szJUteeUNxJAp5H7',
);
if (gethostname() == 'www.jingbo365.com') {
	$config['site']=array (
			'type' => 1,
			'site_title' => '3DLAB',
			'site_title2' => '3DLAB',
			'site_email' => 'webmaster@3DLAB.com',
			'site_email2' => 'webmaster@3DLAB.com',
			'name' => '180.153.223.69:3688/',
			'domain' => 'localhost.com',
			'keywords' => '3DLAB',
			'description' => '3DLAB',
			'kf_phone_num' => '',
			'kf_phone_num2' => '',
			'cz_phone_num' => '',
			'copyright' => 'Copyright © 2013',
			'company_name' => '3DLAB',
			'icp' => '沪ICP备000000号',
			'logo' => NULL,
			'twitter' => 'Twitter链接设置',
			'facebook' => 'Facebook链接设置',
			'youtube' => 'Youtube链接设置',
			'trustwave' => 'Trustwave链接设置',
			'macfee' => 'Macfee代码',
			'livechat' => 'livechat代码',
			'register_mail_active' => '0',
			'register_mail_check_pwd' => '9bqVBhvhr3GwrwSE',    //验证邮箱密钥
			'secret_pwd' => '6vBJqUK4VpVJyECN',    //用户密码加密密钥
			'secret_tk' => 'szJUteeUNxJAp5H7',
	);
}
$config['front_path'] = '/usr/local/web/front'; //系统前台根目录