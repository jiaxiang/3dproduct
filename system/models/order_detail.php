<?php defined('SYSPATH') OR die('No direct access allowed.');

class Order_detail_Model extends ORM {

	const TYPE_1 = 1;		//3D打印
	const TYPE_2 = 2;		//3D建模
	const TYPE_3 = 3;		//3D扫描

	const TYPE_11 = 11;		//商品，打印产品
	const TYPE_12 = 12;		//商品，打印机
	const TYPE_13 = 13;		//商品，备件
	const TYPE_14 = 14;		//商品，扫描仪

	const TYPE_99 = 99;		//报价单，服务费

	const FRONT_MONEY = 10;		//定金

	public function validate(array & $array, $save = FALSE, & $errors='') {
		$fields=parent::as_array();
		$array=array_merge($fields,$array);
		$array = Validation::factory($array)
			->pre_filter('trim')
			->add_rules('order_id', 	'required', 'numeric')
			->add_rules('uid', 			'required', 'numeric')
			->add_rules('type', 		'required', 'numeric')
			->add_rules('price', 		'required', 'numeric')
			->add_rules('model', 					'length[0,255]')
			->add_rules('model_name', 				'length[0,255]')
			->add_rules('preview', 					'length[0,255]')
			->add_rules('preview_name', 			'length[0,255]')
			->add_rules('size', 					'length[0,100]')
			->add_rules('material',					'length[0,100]')
			->add_rules('color', 					'length[0,100]')
			->add_rules('precision', 				'length[0,100]')
			->add_rules('quantity', 				'numeric')
			->add_rules('draft', 					'length[0,255]')
			->add_rules('message',					'length[0,65535]')
			->add_rules('front_money', 	'required', 'numeric')
			->add_rules('status', 		'required', 'numeric');
		if (parent::validate($array, $save)) {
			return TRUE;
		}
		else {
			$errors = $array->errors();
			return FALSE;
		}
	}

	public static function show_type($type) {
		$array = array(
				self::TYPE_1 => '3D打印',
				self::TYPE_2 => '3D建模',
				self::TYPE_3 => '3D扫描',
				self::TYPE_11 => '打印产品',
				self::TYPE_12 => '打印机',
				self::TYPE_13 => '备件',
				self::TYPE_14 => '扫描仪',
				self::TYPE_99 => '报价单',
		);
		if (isset($array[$type])) {
			return $array[$type];
		}
		else {
			return FALSE;
		}
	}
}

