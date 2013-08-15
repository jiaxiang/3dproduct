<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_Model extends ORM {

	const STATUS_0 = 0;		//默认状态
	const STATUS_1 = 1;		//已认证邮箱
	const STATUS_2 = 2;		//预留

	public function validate(array & $array, $save = FALSE, & $errors='') {
		$fields=parent::as_array();
		$array=array_merge($fields,$array);
		$array = Validation::factory($array)
			->pre_filter('trim')
			->add_rules('username', 		'required',	'length[0,20]')
			->add_rules('passwd', 			'required', 'length[0,50]')
			->add_rules('email', 			'required', 'length[0,100]')
			->add_rules('mobile', 						'length[0,20]')
			->add_rules('name', 						'length[0,10]')
			->add_rules('avatar', 						'length[0,200]')
			->add_rules('tk', 				'required', 'length[0,32]')
			->add_rules('lastlogin_time', 	'required', 'length[0,200]')
			->add_rules('status', 			'required', 'numeric');
		if (parent::validate($array, $save)) {
			return TRUE;
		}
		else {
			$errors = $array->errors();
			return FALSE;
		}
	}

	public static function show_status($status) {
		$array = array(
				self::STATUS_0 => '未验证',
				self::STATUS_1 => '邮箱已验证',
				self::STATUS_2 => '预留',
		);
		if (isset($array[$status])) {
			return $array[$status];
		}
		else {
			return FALSE;
		}
	}
}

