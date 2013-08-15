<?php defined('SYSPATH') OR die('No direct access allowed.');

class Service_Controller extends Template_Controller {

	public $obj_user_help, $obj_order, $obj_orderbasic, $obj_orderdetail;
	public function __construct() {
		parent::__construct();
		$this->obj_user_help = userfunc::get_instance();
		$this->obj_order = order::get_instance();
		$this->obj_orderbasic = OrderBasic::instance();
		$this->obj_orderdetail = OrderDetail::instance();
		if ($this->obj_user_help->is_login() == FALSE) {
			//header('Location: http://'.$this->_site_config['site_config']['name'].'/user/login');
		}
	}

	public function index() {

	}

	public function price_calc() {
		$return_array = $this->obj_order->return_array();
		if ($_POST) {
			$material = $this->input->post('material');
			$precision = $this->input->post('precision');
			$lengh = intval($this->input->post('sizel'));
			$width = intval($this->input->post('sizew'));
			$heigh = intval($this->input->post('sizeh'));
			$quantity = $this->input->post('quantity');
			$size = intval(($lengh * $width * $heigh) / 1000);
			if ($size > 0 && $quantity > 0 && order::get_print_material($material) != FALSE && order::get_print_precision($precision) != FALSE) {
				$pre_price = order::get_size_price($precision, $material);
				$total_price = $size * $pre_price * $quantity;
				$return_array['code'] = 1;
				$return_array['msg'] = $total_price;
				echo json_encode($return_array);
				return;
			}
			else {
				$return_array['msg'] = '数据错误！';
				echo json_encode($return_array);
				return;
			}
		}
		$return_array['msg'] = '数据错误！';
		echo json_encode($return_array);
		return;
	}

	public function print3d() {
		$return_array = $this->obj_order->return_array();
		$view = new View('service/print1');
		$view->set('user', $this->_user);
		if ($_POST) {
			include_once WEBROOT.'application/libraries/recaptchalib.php';
			$privatekey = "6LeEcuASAAAAAAdzMZqxXewlJJhn50HSA4sD9_yG";
			$resp = recaptcha_check_answer ($privatekey,
			                                $_SERVER["REMOTE_ADDR"],
			                                $_POST["rcf"],
			                                $_POST["rrf"]);

			if (!$resp->is_valid) {
				$return_array['msg'] = '验证码错误';
				echo json_encode($return_array);
				return;
			}

			$model_file = $this->obj_session->get('PRINT_3D_MODELSTL');
			if ($model_file == FALSE) {
				$return_array['msg'] = '先上传3D模型文件';
				echo json_encode($return_array);
				return;
			}
			$preview = $this->obj_session->get('PRINT_3D_PREVIEW');
			if ($preview == FALSE) {
				$return_array['msg'] = '先上传预览图';
				echo json_encode($return_array);
				return;
			}
			$name = $this->input->post('name');
			if ($name == FALSE) {
				$return_array['msg'] = '请填写真实姓名!';
				echo json_encode($return_array);
				return;
			}
			$email = $this->input->post('email');
			if ($email == FALSE || !preg_match('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/', $email)) {
				$return_array['msg'] = '请填写正确的电子邮箱地址!';
				echo json_encode($return_array);
				return;
			}
			$mobile = $this->input->post('mobile');
			if ($mobile == FALSE || !preg_match('/^1[3|4|5|8][0-9]\d{4,8}$/', $mobile)) {
				$return_array['msg'] = '手机号码格式错误!';
				echo json_encode($return_array);
				return;
			}
			//用户注册
			$r = $this->obj_user_lib->get_user_by_email($email);
			if ($r == FALSE) {
				$reg_data = array(
						'username' 			=> $email,
						'email' 			=> $email,
						'mobile'			=> $mobile,
						'name'				=> $name,
						'passwd' 			=> $this->obj_user_help->encrypt_passwd($mobile),
						'tk'				=> $this->obj_user_help->create_token($email),
						'lastlogin_time' 	=> date('Y-m-d H:i:s'),
						'status' 			=> User_Model::STATUS_0,
				);
				//var_dump($reg_data);die();
				$uid = $this->obj_user_lib->add_user($reg_data);
				if ($uid == FALSE) {
					$return_array['msg'] = '用户信息错误!';
					echo json_encode($return_array);
					return;
				}
				$reg_data['id'] = $uid;
				$this->obj_user_help->send_reg_mail($uid, $email, $email);
				$this->_user = $reg_data;
			}
			else {
				$this->_user = $r;
				if ($this->_user['name'] == FALSE) {
					$this->obj_user_lib->update_user_name($this->_user['id'], $name);
				}
				if ($this->_user['mobile'] == FALSE) {
					$this->obj_user_lib->update_user_mobile($this->_user['id'], $mobile);
				}
			}

			$lengh = intval($this->input->post('sizel'));
			$width = intval($this->input->post('sizew'));
			$heigh = intval($this->input->post('sizeh'));
			if ($lengh == FALSE || $lengh < 30) {
				$return_array['msg'] = '最小为30mm!';
				echo json_encode($return_array);
				return;
			}
			if ($width == FALSE || $width < 30) {
				$return_array['msg'] = '最小为30mm!';
				echo json_encode($return_array);
				return;
			}
			if ($heigh == FALSE || $heigh < 30) {
				$return_array['msg'] = '最小为30mm!';
				echo json_encode($return_array);
				return;
			}
			$size = $lengh.'*'.$width.'*'.$heigh;
			$material = $this->input->post('material');
			if (order::get_print_material($material) == FALSE) {
				$return_array['msg'] = '材料数据错误!';
				echo json_encode($return_array);
				return;
			}
			$color = $this->input->post('color');
			if (order::get_print_color($color) == FALSE) {
				$return_array['msg'] = '颜色数据错误!';
				echo json_encode($return_array);
				return;
			}
			$precision = $this->input->post('precision');
			if (order::get_print_precision($precision) == FALSE) {
				$return_array['msg'] = '精度数据错误!';
				echo json_encode($return_array);
				return;
			}
			$quantity = $this->input->post('quantity');
			if ($quantity == false || $quantity < 1) {
				$return_array['msg'] = '请填写数量!';
				echo json_encode($return_array);
				return;
			}
			$message = $this->input->post('message');
			$order_num = $this->obj_order->creat_order_num();
			$order_data = array(
					'order_num' => $order_num,
					'uid' => $this->_user['id'],
					'name' => $name,
					'mobile' => $mobile,
					'price' => Order_detail_Model::FRONT_MONEY,
					'status' => Order_basic_Model::STATUS_0,
			);
			$order_id = $this->obj_orderbasic->add_order($order_data);
			if ($order_id ==  FALSE) {
				$return_array['msg'] = '订单数据出错！';
				echo json_encode($return_array);
				return;
			}
			$order_detail_data = array(
					'order_id' => $order_id,
					'uid' => $this->_user['id'],
					'type' => Order_detail_Model::TYPE_1,
					'price' => Order_detail_Model::FRONT_MONEY,
					'model' => $model_file['path'],
					'model_name' => $model_file['name'],
					'preview' => $preview['path'],
					'preview_name' => $preview['name'],
					'size' => $size,
					'material' => $material,
					'color' => $color,
					'precision' => $precision,
					'quantity' => $quantity,
					'message' => $message,
					'front_money' => Order_detail_Model::FRONT_MONEY,
					'status' => Order_basic_Model::STATUS_0,
			);
			$service_id = $this->obj_orderdetail->add_order($order_detail_data);
			if ($service_id ==  FALSE) {
				$return_array['msg'] = '服务订单数据出错！';
				echo json_encode($return_array);
				return;
			}
			mail::order_create($this->_user['email'], $this->_user['username'], $order_num);
			$userinfo = array(
					'username' => $this->_user['username'],
					'email' => $this->_user['email'],
					'name' => $name,
					'mobile' => $mobile,
			);
			mail::order_create2admin($this->_user, $order_id);
			$this->obj_session->delete('PRINT_3D_PREVIEW', NULL);
			$this->obj_session->delete('PRINT_3D_MODELSTL', NULL);
			$return_array['code'] = 1;
			echo json_encode($return_array);
			return;
		}
		$view->render(TRUE);
	}

	public function model3d() {
		if (isset($_SESSION['CART'])) {
			var_dump($_SESSION['CART']);
		}
		if ($_FILES) {
			$return = $this->obj_order->upload_attach('img_val', 'model3d');
			if ($return['code'] == 1) {
				$this->obj_session->set('MODEL3D_DRAFT', $return['code']['data']);
			}
			/* $file_input = 'img_val';
			if (!isset($_FILES[$file_input])
			|| !is_uploaded_file($_FILES[$file_input]["tmp_name"])
			|| $_FILES[$file_input]["error"] != 0) {
				die('上传出错');
			}
			$file_obj = $_FILES[$file_input];
			$filename = $file_obj['name'];
			$file_ext = tool::fileext($filename);
			$file_max_size = kohana::config('upload.file_max_size');
			$type = array('gif','png','jpg','jpeg');
			if (!in_array(strtolower($file_ext), $type)) {
				die('文件格式错误');
			}
			$file_size = filesize($file_obj['tmp_name']);
			if ($file_size > $file_max_size) {
				die('文件太大');
			}
			$AttService = AttService::get_instance('model3d');
			$img_id = $AttService->save_default_img($file_obj["tmp_name"]);
			$draft_info = array(
					'name' => $filename,
					'path' => 'model3d/'.$img_id,
			);
			$this->obj_session->set('MODEL3D_DRAFT', $draft_info); */
		}
		$view = new View('service/model');
		if ($this->obj_session->get('MODEL3D_DRAFT')) {
			$view->set('is_upload', 1);
		}
		$view->render(TRUE);
	}

	public function to_buy() {
		$return_array = $this->return_array();
		$type = $this->input->post('type');
		if ($type == Order_detail_Model::TYPE_1) {

		}
		if ($type == Order_detail_Model::TYPE_2) {
			$draft_info = $this->obj_session->get('MODEL3D_DRAFT');
			if ($draft_info == FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '先上传！';
				echo json_encode($return_array);
				return;
			}
			$order_num = $this->obj_order->creat_order_num();
			$order_data = array(
					//'fid' => 0,
					'order_num' => $order_num,
					//'type' => Order_basic_Model::TYPE_1,
					'uid' => $this->_user['id'],
					'price' => Order_detail_Model::FRONT_MONEY,
					'status' => Order_basic_Model::STATUS_0,
			);
			$order_id = $this->obj_orderbasic->add_order($order_data);
			if ($order_id ==  FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '订单数据出错！';
				echo json_encode($return_array);
				return;
			}
			/* $order_data = array(
					'fid' => $order_id,
					'order_num' => $order_num,
					'type' => Order_basic_Model::TYPE_1,
					'uid' => $this->_user['id'],
					'price' => Order_detail_Model::FRONT_MONEY,
					'status' => Order_basic_Model::STATUS_0,
			);
			$order_id = $this->obj_orderbasic->add_order($order_data);
			if ($order_id ==  FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '子订单数据出错！';
				echo json_encode($return_array);
				return;
			} */
			$service_data = array(
					'order_id' => $order_id,
					'uid' => $this->_user['id'],
					'type' => Order_detail_Model::TYPE_2,
					'price' => 0,
					'front_money' => Order_detail_Model::FRONT_MONEY,
					'status' => Order_basic_Model::STATUS_0,
					'draft' => $draft_info['path'],
					'message' => 'test',
			);
			$service_id = $this->obj_orderdetail->add_order($service_data);
			if ($service_id ==  FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '服务订单数据出错！';
				echo json_encode($return_array);
				return;
			}
			$this->obj_session->delete('MODEL3D_DRAFT', NULL);
			echo json_encode($return_array);
			return;
		}
	}

	public function add_to_cart() {
		$return_array = $this->return_array();
		$type = $this->input->post('type');
		if ($type == Order_detail_Model::TYPE_2) {
			$draft_info = $this->obj_session->get('MODEL3D_DRAFT');
			if ($draft_info == FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '先上传！';
				echo json_encode($return_array);
				return;
			}
			$data = array(
					//'method' => Order_basic_Model::TYPE_1,
					'type' => Order_detail_Model::TYPE_2,
					'price' => Order_detail_Model::FRONT_MONEY,
					'front_money' => Order_detail_Model::FRONT_MONEY,
					'status' => Order_basic_Model::STATUS_0,
					'draft' => $draft_info['path'],
					'message' => 'test',
			);
		}
		$this->obj_order->add_cart($data);
		$this->obj_session->delete('MODEL3D_DRAFT', NULL);
		echo json_encode($return_array);
		return;
	}

	public function cart_to_buy() {
		$return_array = $this->return_array();
		$cart_info = $this->obj_session->get('CART');
		if ($cart_info != FALSE) {
			$order_num = $this->obj_order->creat_order_num();
			$order_data = array(
					'order_num' => $order_num,
					//'type' => 0,
					'uid' => $this->_user['id'],
					'price' => $cart_info['price'],
					'status' => Order_basic_Model::STATUS_0,
			);
			$order_id = $this->obj_orderbasic->add_order($order_data);
			if ($order_id ==  FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '订单数据出错！';
				echo json_encode($return_array);
				return;
			}
			for ($i = 0; $i < count($cart_info['data']); $i++) {
				//if ($cart_info['data'][$i]['method'] == Order_basic_Model::TYPE_1) {
				$service_data = array(
						'order_id' => $order_id,
						'uid' => $this->_user['id'],
						'type' => $cart_info['data'][$i]['type'],
						'price' => 0,
						'front_money' => Order_detail_Model::FRONT_MONEY,
						'status' => Order_basic_Model::STATUS_0,
						'draft' => $cart_info['data'][$i]['draft'],
						'message' => $cart_info['data'][$i]['message'],
				);
				$service_id = $this->obj_orderdetail->add_order($service_data);
				//}
			}
			$this->obj_session->delete('CART', NULL);
		}
		echo json_encode($return_array);
		return;
	}
}