<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 订单支付状态 */
$config['pay_status'] = array(
	'1' => array(
		'name'=>'未支付',
		'flow'=>array(2,3)
	),
	'2' => array(
		'name'=>'支付处理中',
		'flow'=>array(3)
	),
	'3' => array(
		'name'=>'已支付',
		'flow'=>array(4,5,6,7)
	),
	'4' => array(
		'name'=>'退款处理中',
		'flow'=>array(5,6,7)
	),
	'5' => array(
		'name'=>'部分退款',
		'flow'=>array(5,6,7)
	),
	'6' => array(
		'name'=>'已退款',
		'flow'=>array()
	),
	'7' => array(
		'name'=>'charge back',
		'flow'=>array()
	)
);

/* 订单的发货状态 */
$config['ship_status'] = array(
	'1' => array(
		'name'=>'未发货',
		'flow'=>array(2,3,4)
	),
	'2' => array(
		'name'=>'配货中',
		'flow'=>array(3,4)
	),
	'3' => array(
		'name'=>'部分发货',
		'flow'=>array(3,4,5,6)
	),
	'4' => array(
		'name'=>'已发货',
		'flow'=>array(5,6)
	),
	'5' => array(
		'name'=>'部分退货',
		'flow'=>array(3,4,5,6)
	),
	'6' => array(
		'name'=>'已退货',
		'flow'=>array(3,4)
	)
);

/* 订单状态 */
$config['order_status'] = array(
	'1' => array(
		'name'=>'处理中',
		'flow'=>array(2,3,4)
	),
	'2' => array(
		'name'=>'已确认',
		'flow'=>array(3,4)
	),
	'3' => array(
		'name'=>'完成',
		'flow'=>array()
	),
	'4' => array(
		'name'=>'废除',
		'flow'=>array()
	)
);

/* 退款原因 */
$config['refund_reason'] = array(
	'1' => array(
		'name'=>'商品缺货',
		'flow'=>''
	),
	'2' => array(
		'name'=>'退订单折扣',
		'flow'=>''
	),
	'3' => array(
		'name'=>'退订单邮费',
		'flow'=>''
	),
	'4' => array(
		'name'=>'商品质量问题',
		'flow'=>''
	),
	'5' => array(
		'name'=>'商品尺寸问题',
		'flow'=>''
	),
	'6' => array(
		'name'=>'发货问题',
		'flow'=>''
	),
	'7' => array(
		'name'=>'投递问题',
		'flow'=>''
	)
);

/* 退款方式 */
$config['refund_method'] = array(
	'1' => array(
		'name'=>'直接支付账号内退款',
		'flow'=>''
	),
	'2' => array(
		'name'=>'提供paypal账号退款',
		'flow'=>''
	)
);

/* 后台订单修改为已支付可选的支付方式 */
$config['payment_method'] = array(
	'1' => array(
		'name' => 'PayPal打款',
		'status' => 0
	),
	'3' => array(
		'name' => '银行汇款',
		'status_flag' => 'pay_status',
		'status' => 3
	),
	'4' => array(
		'name' => '西联转账',
		'status_flag' => 'order_status',
		'status' => 2
	)
);

/* 订单快速搜索状态 */
$config['quick_search'] = array(
	'all' => array(
		'name' => '订单列表',
		'status' => array()
	),
	'noprocessed' => array(
		'name' => '待处理',
		'status' => array('pay_status'=>3,'ship_status'=>1,'order_status'=>1)
	),
	'paid' => array(
		'name' => '已支付待发货',
		'status' => array('pay_status'=>3,'ship_status'=>array(1,2,3))
	),
	'shiped' => array(
		'name' => '已发货',
		'status' => array('ship_status'=>array(3,4))
	),
	'refund' => array(
		'name' => '退款订单',
		'status' => array('pay_status'=>array(5,6))
	),
	'confirm' => array(
		'name' => '已确认',
		'status' => array('order_status'=>2)
	),
	'complete' => array(
		'name' => '完成',
		'status' => array('order_status'=>3)
	),
	'cancel' => array(
		'name' => '废除',
		'status' => array('order_status'=>4)
	)
);

/* 可显示废除按钮的快速搜索状态 */
$config['show_cancel_btn_quick_search'] = array('all','noprocessed','paid','shiped','refund','confirm');

/* 导出单据*/
$config['doc'] = array(
	1 => array(
		'field' => 'payment_num', 
		'name' => 'PaymentNum', 
		'show' => '收款单号', 
		'parent' => '0'
	), 
	2 => array(
		'field' => 'order_num', 
		'name' => 'OrderNum', 
		'show' => '订单号', 
		'parent' => '0'
	), 
	3 => array(
		'field' => 'site_domain', 
		'name' => 'Site Domain', 
		'show' => '站点名称', 
		'parent' => '0'
	), 
	4 => array(
		'field' => 'email', 
		'name' => 'Email', 
		'show' => '用户邮箱', 
		'parent' => '0'
	), 
	5 => array(
		'field' => 'manager', 
		'name' => 'Manager', 
		'show' => '操作员', 
		'parent' => '0'
	), 
	6 => array(
		'field' => 'payment_method', 
		'name' => 'Payment Method', 
		'show' => '支付方式', 
		'parent' => '0'
	), 
	7 => array(
		'field' => 'currency', 
		'name' => 'Currency', 
		'show' => '币种', 
		'parent' => '0'
	), 
	8 => array(
		'field' => 'amount', 
		'name' => 'Amount', 
		'show' => '支付金额', 
		'parent' => '0'
	), 
	9 => array(
		'field' => 'payment_status', 
		'name' => 'Payment Status', 
		'show' => '支付状态', 
		'parent' => '0'
	), 
	10 => array(
		'field' => 'receive_account', 
		'name' => 'Receive Account', 
		'show' => '收款账号', 
		'parent' => '0'
	), 
	11 => array(
		'field' => 'trans_no', 
		'name' => 'TransNo', 
		'show' => '交易号', 
		'parent' => '0'
	), 
	12 => array(
		'field' => 'is_send_email', 
		'name' => 'Is Send Email', 
		'show' => '是否发送邮件', 
		'parent' => '0'
	), 
	13 => array(
		
		'field' => 'content_admin', 
		'name' => 'Content Admin', 
		'show' => '管理员备注', 
		'parent' => '0'
	), 
	14 => array(
		'field' => 'content_user', 
		'name' => 'Content User', 
		'show' => '用户备注', 
		'parent' => '0'
	), 
	15 => array(
		'field' => 'date_add', 
		'name' => 'Date Add', 
		'show' => '添加时间', 
		'parent' => '0'
	),
	16 => array(
		'field' => 'refund_num', 
		'name' => 'RefundNum', 
		'show' => '退款单号', 
		'parent' => '0'
	), 
	17 => array(
		'field' => 'refund_method', 
		'name' => 'Refund Method', 
		'show' => '退款方式', 
		'parent' => '0'
	), 
	18 => array(
		'field' => 'refund_amount', 
		'name' => 'Refund Amount', 
		'show' => '退款金额', 
		'parent' => '0'
	), 
	19 => array(
		'field' => 'refund_status', 
		'name' => 'Refund Status', 
		'show' => '退款状态', 
		'parent' => '0'
	), 
	20 => array(
		'field' => 'refund_reason', 
		'name' => 'Refund Reason', 
		'show' => '退款原因', 
		'parent' => '0'
	), 
	21 => array(
		'field' => 'ship_num', 
		'name' => 'ShipNum', 
		'show' => '发货单号', 
		'parent' => '0'
	), 
	22 => array(
		'field' => 'carrier', 
		'name' => 'Carrier', 
		'show' => '配送方式', 
		'parent' => '0'
	),
	23 => array(
		'field' => 'total_shipping', 
		'name' => 'Total Shipping', 
		'show' => '运费金额', 
		'parent' => '0'
	), 
	24 => array(
		'field' => 'ship_status', 
		'name' => 'Ship Status', 
		'show' => '发货状态', 
		'parent' => '0'
	), 
	25 => array(
		'field' => 'ems_num', 
		'name' => 'Ems Num', 
		'show' => '物流单号', 
		'parent' => '0'
	), 
	26 => array(
		'field' => 'return_num', 
		'name' => 'ReturnNum', 
		'show' => '退货单号', 
		'parent' => '0'
	), 
	27 => array(
		'field' => 'return_status', 
		'name' => 'Return Status', 
		'show' => '退货状态', 
		'parent' => '0'
	)  
);

$config['show_cancel_btn_quick_search'] = array('all','noprocessed','paid','shiped','refund','confirm');

/* 订单来源(网站订单还是手动生成的订单) */
$config['order_source'] = array(
	'site' => array(
		'name' => '网站订单'
	),
	'manual' => array(
		'name' => '后台手动添加的订单'
	),
	'other' => array(
		'name' => '其他'
	)
);
