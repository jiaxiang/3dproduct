<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * 用于自定义导出设置
 * 以后改数据库形式
 */
$config['default'] = array(
	1 => array(
		'field' => 'order_num', 
		'name' => 'OrderNum', 
		'show' => '订单号', 
		'parent' => '0'
	), 
	2 => array(
		'field' => 'site_name', 
		'name' => 'Site', 
		'show' => '站点名称', 
		'parent' => '0'
	), 
	3 => array(
		'field' => 'email', 
		'name' => 'Email', 
		'show' => '邮箱', 
		'parent' => '0'
	), 
	4 => array(
		'field' => 'shipping_firstname|shipping_lastname', 
		'name' => 'Name', 
		'show' => '名字(投递)', 
		'parent' => '0'
	), 
	5 => array(
		'field' => 'currency', 
		'name' => 'Currency', 
		'show' => '币种', 
		'parent' => '0'
	), 
	6 => array(
		'field' => 'total_products', 
		'name' => 'Product Total', 
		'show' => '订单产品总价', 
		'parent' => '0'
	), 
	7 => array(
		'field' => 'total_shipping', 
		'name' => 'Shipping', 
		'show' => '运费', 
		'parent' => '0'
	), 
	8 => array(
		'field' => 'total_real', 
		'name' => 'Total', 
		'show' => '订单总价', 
		'parent' => '0'
	), 
	9 => array(
		'field' => 'order_status_name', 
		'name' => 'Order status', 
		'show' => '订单状态', 
		'parent' => '0'
	), 
	10 => array(
		'field' => 'pay_status_name', 
		'name' => 'Payment status', 
		'show' => '订单支付状态', 
		'parent' => '0'
	), 
	11 => array(
		'field' => 'ship_status_name', 
		'name' => 'Shipping status', 
		'show' => '订单发货状态', 
		'parent' => '0'
	), 
	12 => array(
		'field' => 'shipping_firstname', 
		'name' => 'Shipping Firstname', 
		'show' => '投递姓', 
		'parent' => '0'
	), 
	13 => array(
		
		'field' => 'shipping_lastname', 
		'name' => 'Shipping Lastname', 
		'show' => '投递名', 
		'parent' => '0'
	), 
	14 => array(
		'field' => 'shipping_country', 
		'name' => 'Shipping Country Code', 
		'show' => '投递国家代码', 
		'parent' => '0'
	), 
	15 => array(
		'field' => 'shipping_country_name', 
		'name' => 'Shipping Country', 
		'show' => '投递国家名称', 
		'parent' => '0'
	), 
	16 => array(
		'field' => 'shipping_state', 
		'name' => 'Shipping State', 
		'show' => '投递省份', 
		'parent' => '0'
	), 
	17 => array(
		'field' => 'shipping_city', 
		'name' => 'Shipping City', 
		'show' => '投递城市', 
		'parent' => '0'
	), 
	18 => array(
		
		'field' => 'shipping_address', 
		'name' => 'Shipping Address', 
		'show' => '投递地址', 
		'parent' => '0'
	), 
	19 => array(
		'field' => 'shipping_zip', 
		'name' => 'Shipping Post Code', 
		'show' => '投递邮编', 
		'parent' => '0'
	), 
	20 => array(
		'field' => 'shipping_phone', 
		'name' => 'Shipping Phone', 
		'show' => '投递电话', 
		'parent' => '0'
	), 
	21 => array(
		'field' => 'shipping_mobile', 
		'name' => 'Shipping Mobile', 
		'show' => '投递移动电话', 
		'parent' => '0'
	), 
	22 => array(
		'field' => 'billing_firstname', 
		'name' => 'Billing Firstname', 
		'show' => '账单姓', 
		'parent' => '0'
	), 
	23 => array(
		'field' => 'billing_lastname', 
		'name' => 'Billing Lastname', 
		'show' => '账单名', 
		'parent' => '0'
	), 
	24 => array(
		'field' => 'billing_country', 
		'name' => 'Billing Country Code', 
		'show' => '账单国家代码', 
		'parent' => '0'
	), 
	25 => array(
		
		'field' => 'billing_state', 
		'name' => 'Billing Country', 
		'show' => '账单国家名称', 
		'parent' => '0'
	), 
	26 => array(
		'field' => 'billing_city', 
		'name' => 'Billing City', 
		'show' => '账单城市', 
		'parent' => '0'
	), 
	27 => array(
		'field' => 'billing_address', 
		'name' => 'Billing Address', 
		'show' => '账单地址', 
		'parent' => '0'
	), 
	28 => array(
		'field' => 'billing_zip', 
		'name' => 'Billing Post Code', 
		'show' => '账单邮编', 
		'parent' => '0'
	), 
	29 => array(
		
		'field' => 'billing_phone', 
		'name' => 'Billing Phone', 
		'show' => '账单电话', 
		'parent' => '0'
	), 
	30 => array(
		'field' => 'billing_mobile', 
		'name' => 'Billing Mobile', 
		'show' => '账单移动电话', 
		'parent' => '0'
	), 
	31 => array(
		'field' => 'trans_id', 
		'name' => 'Trans ID', 
		'show' => '交易号', 
		'parent' => '0'
	), 
	32 => array(
		'field' => 'ems_num', 
		'name' => 'EMS Num', 
		'show' => 'EMS号', 
		'parent' => '0'
	), 
	33 => array(
		'field' => 'carrier', 
		'name' => 'Carrier', 
		'show' => '运输方式', 
		'parent' => '0'
	), 
	34 => array(
		'field' => 'message', 
		'name' => 'Message', 
		'show' => '备注', 
		'parent' => '0'
	),
	35 => array(
		'field' => 'total', 
		'name' => 'Total', 
		'show' => '订单价格(美元)', 
		'parent' => '0'
	), 
	36 => array(
		'field' => 'date_add', 
		'name' => 'Date Add', 
		'show' => '下单时间', 
		'parent' => '0'
	), 
	37 => array(
		'field' => 'date_upd', 
		'name' => 'Date Update', 
		'show' => '最后更新时间', 
		'parent' => '0'
	), 
	38 => array(
		'field' => 'date_pay', 
		'name' => 'Date Pay', 
		'show' => '支付时间', 
		'parent' => '0'
	), 
	39 => array(
		'field' => 'date_verify', 
		'name' => 'Date Verify', 
		'show' => '订单确认时间', 
		'parent' => '0'
	), 
	40 => array(
		'field' => 'ip', 
		'name' => 'IP', 
		'show' => 'IP', 
		'parent' => '0'
	), 
	41 => array(
		
		'field' => 'SKU', 
		'name' => 'SKU', 
		'show' => 'SKU', 
		'parent' => 'product'
	), 
	42 => array(
		'field' => 'name', 
		'name' => 'Product Name', 
		'show' => '产品名称', 
		'parent' => 'product'
	), 
	43 => array(
		
		'field' => 'attribute_style', 
		'name' => 'Product Attribute', 
		'show' => '产品属性', 
		'parent' => 'product'
	), 
	44 => array(
		'field' => 'quantity', 
		'name' => 'Qty', 
		'show' => '产品数量', 
		'parent' => 'product'
	), 
	45 => array(
		'field' => 'price', 
		'name' => 'price', 
		'show' => '产品原价', 
		'parent' => 'product'
	), 
	46 => array(
		'field' => 'discount_price', 
		'name' => 'Discount price', 
		'show' => '产品销售价格', 
		'parent' => 'product'
	), 
	47 => array(
		'field' => 'total', 
		'name' => 'Total', 
		'show' => '产品小计', 
		'parent' => 'product'
	), 
	48 => array(
		'field' => 'image_url', 
		'name' => 'Image', 
		'show' => '产品图', 
		'parent' => 'product'
	),
	49 => array(
		'field' => 'link', 
		'name' => 'Product Link', 
		'show' => '产品链接', 
		'parent' => 'product'
	)
);