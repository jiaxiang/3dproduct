<?php defined('SYSPATH') OR die('No direct access allowed.');

class Order_basic_Model extends ORM {

	const STATUS_0 = 0;		//提交订单，待付款（系统）
	const STATUS_1 = 1;		//已付定金，待报价（系统）
	const STATUS_2 = 2;		//已付款，待发货（系统）
	const STATUS_3 = 3;		//已发货，待收货（人工）
	const STATUS_4 = 4;		//已收货，订单完成（人工）
	const STATUS_5 = 5;		//订单取消（人工）

	//const TYPE_1 = 1;		//服务
	//const TYPE_2 = 2;		//商店

	public function validate(array & $array, $save = FALSE, & $errors='') {
		$fields=parent::as_array();
		$array=array_merge($fields,$array);
		$array = Validation::factory($array)
			->pre_filter('trim')
			//->add_rules('fid',			'required',	'numeric')
			->add_rules('order_num', 	'required', 'length[0,100]')
			//->add_rules('type', 		'required', 'numeric')
			->add_rules('uid', 			'required', 'numeric')
			->add_rules('name', 					'length[0,10]')
			->add_rules('mobile', 					'length[0,20]')
			->add_rules('price', 		'required', 'numeric')
			->add_rules('status', 		'required', 'numeric');
		if (parent::validate($array, $save)) {
			return TRUE;
		}
		else {
			$errors = $array->errors();
			return FALSE;
		}
	}

	/* public static function show_type($type) {
		$array = array(
				self::TYPE_1 => '服务',
				self::TYPE_2 => '商店',
		);
		if (isset($array[$type])) {
			return $array[$type];
		}
		else {
			return FALSE;
		}
	} */

	public static function show_status($status) {
		$array = array(
				self::STATUS_0 => '待付款',
				self::STATUS_1 => '待报价',
				self::STATUS_2 => '待发货',
				self::STATUS_3 => '待收货',
				self::STATUS_4 => '已完成',
				self::STATUS_5 => '已取消',
		);
		if (isset($array[$status])) {
			return $array[$status];
		}
		else {
			return FALSE;
		}
	}
}

