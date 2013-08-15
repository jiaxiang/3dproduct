<?php defined('SYSPATH') OR die('No direct access allowed.');

$config['affiliatefuture'] = array(
		'merchantid'  => '',	//联盟注册id,从联盟站点关系表中获取值
		'ordervalue'  => '',	//订单金额,该金额要减去运费，要从订单中获取
		'orderref'    => '',	//订单号，从订单中获取
		'payoutcodes' => '',	//空缺值
		'offlinecode' => '',	//空缺值
		'send_type'   => 0,
		'cache_time'  => 30,	//单位时间天
		'currency'    => 'USD', //币种
		);
		
$config['commissionjunction'] = array(
		'cid'      => '',		//联盟注册id,从联盟站点关系表中获取值
		'oid'      => '',		//订单id,从订单中获取
		'amount'   => '',		//订单金额,该金额要减去运费，要从订单中获取
		'type'     => '5634',	//推广类型,推广类型编号
		'currency' => 'USD',	//币种
		'method'   => 'IMG',    //以图片格式发送消息
		'send_type'=> 0,
		'cache_time'  => 30,	//单位时间天
		'currency'    => 'default', //币种
		);
		
$config['clixgalore'] = array(
		'adid' => '',			//联盟注册号,从联盟站点关系表中获取值
		'sv'   => '',			//订单金额,该金额要减去运费，要从订单中获取
		'oid'  => '',			//订单号,从订单中获取
		'send_type'   => 0,
		'cache_time'  => 30,	//单位时间天
		'currency'    => 'default', //币种
		);
		
$config['linkshare'] = array(
		'mid'      => '',		//联盟注册ID
		'ord'      => '',		//订单ID
		'skulist'  => '',		//SKU列表
		'qlist'    => '',		//销售数量列表
		'amtlist'  => '',		//金额列表,该金额要减去运费,要从订单中获取,订单金额要*100
		'cur'      => '',		//币种
		'namelist' => '',		//名称列表（可选）
		'send_type'=> 0,
		'cache_time'  => 30,	//单位时间天
		'currency'    => 'default', //币种
		);
		
$config['shareasale'] = array(
		'merchantid' =>'',		//联盟注册号
		'amount'     =>'',		//订单金额,该金额要减去运费，要从订单中获取
		'tracking'   =>'',		//订单号
		'transtype'  =>'sale',	//推广类型
		'send_type'  => 0,
		'cache_time'  => 30,	//单位时间天
		'currency'    => 'USD', //币种
		);
		
$config['webgains'] = array(
		'wgprogramid'     => '',	//注册ID,从联盟站点关系表中获取值
		'wgeventid'       => '',	//非必要参数,从联盟站点关系表中获取值
		'wgvalue'         => '',	//订单金额
		'wgorderreference'=> '',	//订单ID
		'pin'             => '',	//加密私钥,从联盟站点关系表中获取值
		'wgchecksum'      => '',	//md5('{pin}programid=2729&eventid=12&value=50&order_reference=ab9876')需要程序计算
		'wgcomment'       => '',	//描述。可以不要
		'send_type'       => 0,
		'cache_time'      => 30,	//单位时间天
		'currency'    => 'USD',     //币种
		);
		
$config['earnmoney'] = array(
		'uid'          => '',	//发布的产品
		'nid'          => '',	//推荐商品用户
		'resource'     => 'earnmoneyonfb',
		'url'          => '',	//商品页面地址
		'order_num'    => '',	//订单号
		'order_amount' => '',	//交易金额
		'referAddress' => '',	//销售站域名
		'send_type'    => 1,
		'cache_time'   => 7,	//单位时间天
		'currency'     => 'USD', //币种
	);