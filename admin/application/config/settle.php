<?php defined('SYSPATH') OR die('No direct access allowed.');

$config['agent_type'] = array(
	'0' => '普通代理',
	'1' => '特殊超级代理',
	'2' => '特殊二级代理',
	'11' => '一级代理',
	'12' => '二级代理',
);

$config['isbeidan'] = array(
	'0' => '非北单',
	'7' => '北单',
);

$config['settlecls'] = array(
	'sp_settle_month' => '月度结算',
	'sp_settle_month_1' => '超代月结',
	'sp_settle_realtime' => '即时结算',
	'sp_settle_realtime_1' => '超代即时结',
	'sp_settle_realtime_2' => '一代即时结',
	'sp_settle_tax' => '月度税务结算',
	'sp_realtime_client' => '下线返利结算',
);
