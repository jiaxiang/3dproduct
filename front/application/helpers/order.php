<?php defined('SYSPATH') or die('No direct script access.');

class order_Core {
    private static $instance = NULL;

    public static $print_material = array(
    		1 => 'PLA',
    		2 => 'ABS',
    		3 => '木塑（LAYWOOD-W3）',
    		4 => '尼龙（Nylon618）',
    );
    public static $print_precision = array(
    		1 => '精细0.1mm层厚',
    		2 => '精细0.2mm层厚',
    		3 => '精细0.3mm层厚',
    );
    public static $print_price = array(
    		1 => array(
    				1 => 8,
    				2 => 8,
    				3 => 16,
    				4 => 16,
    		),
    		2 => array(
    				1 => 7,
    				2 => 7,
    				3 => 15,
    				4 => 15,
    		),
    		3 => array(
    				1 => 5,
    				2 => 5,
    				3 => 12,
    				4 => 12,
    		),
    );

    // 获取单态实例
    public static function get_instance() {
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }

    public static function return_array() {
    	return array('code'=>0,'msg'=>'');
    }

    public static function get_size_price($p, $m) {
    	$price = self::$print_price;
    	if (isset($price[$p][$m])) {
    		return $price[$p][$m];
    	}
    	else {
    		return 0;
    	}
    }

    public function add_cart($data) {
    	$session = Session::instance();
    	$cart = $session->get('CART');
    	if ($cart['data']) {
    		array_push($cart['data'], $data);
    		$cart['price'] += $data['price'];
    	}
    	else {
    		$cart = array();
    		$cart['data'][] = $data;
    		$cart['price'] = $data['price'];
    	}
    	$session->set('CART', $cart);
    }

    public function remove_cart($key) {
    	$session = Session::instance();
    	$cart = $session->get('CART');
    	if ($cart['data'] && isset($cart['data'][$key])) {
    		$key_price = $cart['data'][$key]['price'];
    		unset($cart['data'][$key]);
    		$cart['data'] = array_values($cart['data']);
    		$cart['price'] -= $key_price;
    		$session->set('CART', $cart);
    	}
    }

    public function creat_order_num() {
    	$order_num = '';
    	do {
    		$order_num = date('YmdHis').rand(1000, 9999);
    		$orderinfo = OrderBasic::instance()->get_orders_by_ordernum($order_num);
    		if ($orderinfo == FALSE || count($orderinfo) == 0) {
    			break;
    		}
    	}
    	while (1);
    	return $order_num;
    }

    public static function get_print_material($id = NULL) {
    	$material = self::$print_material;
    	if ($id === NULL) {
    		return $material;
    	}
    	elseif (!isset($material[$id])) {
    		return FALSE;
    	}
    	else {
    		return $material[$id];
    	}
    }

    public static function get_print_precision($id = NULL) {
    	$precision = self::$print_precision;
    	if ($id === NULL) {
    		return $precision;
    	}
    	elseif (!isset($precision[$id])) {
    		return FALSE;
    	}
    	else {
    		return $precision[$id];
    	}
    }

    public static function get_print_color($color = NULL) {
    	$color_path = WEBROOT.'media/images/color';
    	$color_url = url::base().'media/images/color';
    	if ($color === NULL) {
    		if ($handle = opendir($color_path)) {
	    		$return = array();
	    		while (false !== ($file = readdir($handle))) {
	    			if ($file != "." && $file != ".." && $file != 'Thumbs.db') {
	    				$filename = explode('.', $file);
	    				 $return[] = array(
    				 		'filepath' => $color_url.'/'.$file,
    				 		'filename' => $filename[0],
    				 	);
    				}
    			}
    			closedir($handle);
    		}
			return $return;
    	}
    	elseif (!file_exists($color_path.'/'.$color.'.jpg')) {
    		return FALSE;
    	}
    	else {
    		return $color_url.'/'.$color.'.jpg';
    	}
    }

	public static function get_3d_service_size($sizeid = NULL) {

	}

	public function upload_attach($name, $dirname, $type = 'pic') {
		$return = array('code'=>0,'msg'=>'');
		if ($_FILES) {
			//var_dump($_FILES);die();
			$file_input = $name;
			if (!isset($_FILES[$file_input])
			|| !is_uploaded_file($_FILES[$file_input]["tmp_name"])
			|| $_FILES[$file_input]["error"] != 0) {
				$return['msg'] = '上传文件出错';
				//echo json_encode($return);
				return $return;
			}
			$file_obj = $_FILES[$file_input];
			$filename = $file_obj['name'];
			$file_ext = strtolower(tool::fileext($filename));
			if ($type == 'pic') {
				$type = kohana::config('upload.pic_file_ext');
				$file_max_size = kohana::config('upload.pic_max_size');
			}
			else {
				$type = kohana::config('upload.model_file_ext');
				$file_max_size = kohana::config('upload.file_max_size');
			}
			if (!in_array($file_ext, $type)) {
				$return['msg'] = '文件格式错误';
				//echo json_encode($return);
				return $return;
			}
			$file_size = filesize($file_obj['tmp_name']);
			if ($file_size > $file_max_size) {
				$return['msg'] = '文件太大';
				//echo json_encode($return);
				return $return;
			}
			$AttService = AttService::get_instance($dirname);
			if ($type == 'pic') {
				$r = $AttService->save_default_img($file_obj["tmp_name"]);
				if ($r == FALSE) {
					$return['msg'] = '上传出错';
					//echo json_encode($return);
					return $return;
				}
			}
			else {
				$r = $AttService->save_default_file($file_obj["tmp_name"], $file_ext);
				if ($r == FALSE) {
					$return['msg'] = '上传出错';
					//echo json_encode($return);
					return $return;
				}
			}
			$img_info = array(
					'name' => $filename,
					'path' => $dirname.'/'.$r,
			);
			$return['code'] = 1;
			$return['data'] = $img_info;
			//echo json_encode($return);
			return $return;
		}
	}

}