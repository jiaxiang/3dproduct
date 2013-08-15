<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 订单支付状态 */
$config['pay_status'] = array(
	'1' => array(
		'name'=>'未支付',
		'front_name'=>'Unpaid',
		'flow'=>array(2,3)
	),
	'2' => array(
		'name'=>'支付处理中',
		'front_name'=>'Pending',
		'flow'=>array(3)
	),
	'3' => array(
		'name'=>'已支付',
		'front_name'=>'Paid',
		'flow'=>array(4,5,6,7)
	),
	'4' => array(
		'name'=>'退款处理中',
		'front_name'=>'Refunding',
		'flow'=>array(5,6,7)
	),
	'5' => array(
		'name'=>'部分退款',
		'front_name'=>'Partly Refund',
		'flow'=>array(6,7)
	),
	'6' => array(
		'name'=>'已退款',
		'front_name'=>'Refunded',
		'flow'=>array()
	),
	'7' => array(
		'name'=>'charge back',
		'front_name'=>'Charge Back',
		'flow'=>array()
	)
);

/* 订单的发货状态 */
$config['ship_status'] = array(
	'1' => array(
		'name'=>'未发货',
		'front_name'=>'Unfilled',
		'flow'=>array(2,3,4)
	),
	'2' => array(
		'name'=>'配货中',
		'front_name'=>'Processing',
		'flow'=>array(3,4)
	),
	'3' => array(
		'name'=>'部分发货',
		'front_name'=>'Partial Delivery',
		'flow'=>array(3,4,5,6)
	),
	'4' => array(
		'name'=>'已发货',
		'front_name'=>'Send',
		'flow'=>array(5,6)
	),
	'5' => array(
		'name'=>'部分退货',
		'front_name'=>'Partial Return',
		'flow'=>array(3,4,5,6)
	),
	'6' => array(
		'name'=>'已退货',
		'front_name'=>'Returned',
		'flow'=>array(3,4)
	)
);

/* 订单状态 */
$config['order_status'] = array(
	'1' => array(
		'name'=>'处理中',
		'front_name'=>'Pending',
		'flow'=>array(2,3,4)
	),
	'2' => array(
		'name'=>'已确认',
		'front_name'=>'Confirmed',
		'flow'=>array(3,4)
	),
	'3' => array(
		'name'=>'完成',
		'front_name'=>'Paid Over',
		'flow'=>array()
	),
	'4' => array(
		'name'=>'废除',
		'front_name'=>'Cancel',
		'flow'=>array()
	)
);

