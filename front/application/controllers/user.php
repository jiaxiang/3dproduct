<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_Controller extends Template_Controller {

	public $obj_user_help;
	public function __construct() {
		parent::__construct();
		//$this->obj_user_lib = User::instance();
		$this->obj_user_help = userfunc::get_instance();
	}

	public function register() {
		if ($_POST != FALSE) {
			//d($_POST);
			if ($this->obj_user_help->is_login() != FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '已登录！';
				echo json_encode($return_array);
				return;
			}
			$return_array = $this->return_array();
			$username = $this->input->post('username');
			if ($this->obj_user_help->check_username_rule($username) == FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '用户名不符合规则！';
				echo json_encode($return_array);
				return;
			}
			$r = $this->obj_user_lib->get_user_by_username($username);
			if ($r != FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '用户名已存在！';
				echo json_encode($return_array);
				return;
			}
			$email = $this->input->post('email');
			//d($email);
			if ($this->obj_user_help->check_email_rule($email) == FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '邮箱不符合规则！';
				echo json_encode($return_array);
				return;
			}
			$r = $this->obj_user_lib->get_user_by_email($email);
			if ($r != FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '邮箱已存在！';
				echo json_encode($return_array);
				return;
			}
			$passwd = $this->input->post('passwd');
			if ($this->obj_user_help->check_passwd_rule($passwd) == FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '密码不符合规则！';
				echo json_encode($return_array);
				return;
			}
			$reg_data = array(
					'username' 			=> $username,
					'email' 			=> $email,
					'passwd' 			=> $this->obj_user_help->encrypt_passwd($passwd),
					'tk'				=> $this->obj_user_help->create_token($email),
					'lastlogin_time' 	=> date('Y-m-d H:i:s'),
					'status' 			=> User_Model::STATUS_0,
			);
			$reg_res = $this->obj_user_lib->add_user($reg_data);
			if ($reg_res == FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '注册失败！';
				echo json_encode($return_array);
				return;
			}
			$this->obj_user_help->send_reg_mail($reg_res, $username, $email);
			echo json_encode($return_array);
			return;
		}
		else {
			$view = new View('user/register');
			$view->set('user', $this->_user);
			$view->render(TRUE);
		}
	}

	public function reg_success() {
		$error = '';
		if ($this->obj_user_help->is_login() != FALSE) {
			$error = '已登录！';
		}
		else {
			$key = $this->input->get('key');
			$uid = $this->input->get('id');
			$email = $this->input->get('e');
			$userinfo = $this->obj_user_lib->get_user_by_uid($uid);
			if ($userinfo == FALSE) {
				$error = '用户不存在！';
			}
			else {
				if ($userinfo['status'] != User_Model::STATUS_0) {
					$error = '已验证！';
				}
				else {
					if ($this->obj_user_help->check_reg_mail($uid, $email, $key) == FALSE) {
						$error = '验证信息出错！';
					}
					else {
						$r = $this->obj_user_lib->update_user_status($userinfo['id'], User_Model::STATUS_1);
						if ($r == FALSE) {
							$error = '邮箱认证失败！';
						}
					}
				}
			}
		}
		$view = new View('user/reg_success');
		$view->set('error', $error);
		$view->render(TRUE);
	}

	public function login() {
		if ($_POST) {
			$return_array = $this->return_array();
			if ($this->obj_user_help->is_login() != FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '已登录！';
				echo json_encode($return_array);
				return;
			}
			$ue = $this->input->post('ue');
			$username_res = $this->obj_user_lib->get_user_by_username($ue);
			$email_res = $this->obj_user_lib->get_user_by_email($ue);
			if ($username_res == FALSE && $email_res == FALSE) {
				$return_array['code'] = 1;
				$return_array['msg'] = '用户名/邮箱不存在！';
				echo json_encode($return_array);
				return;
			}
			if ($username_res == FALSE) {
				$userinfo = $email_res;
			}
			else {
				$userinfo = $username_res;
			}
			if ($userinfo['status'] != User_Model::STATUS_1) {
				$return_array['code'] = 1;
				$return_array['msg'] = '邮箱还没有认证！';
				echo json_encode($return_array);
				return;
			}
			$passwd = $this->input->post('passwd');
			if ($userinfo['passwd'] != $this->obj_user_help->encrypt_passwd($passwd)) {
				$return_array['code'] = 1;
				$return_array['msg'] = '密码输入错误！';
				echo json_encode($return_array);
				return;
			}
			$this->obj_session->set('USER', $userinfo);
			echo json_encode($return_array);
			return;
		}
		else {
			$view = new View('user/login');
			$view->set('user', $this->_user);
			$view->render(TRUE);
		}
	}

	public function orderlist() {
		if ($this->obj_user_help->is_login() == FALSE) {
			header('Location: http://'.$this->_site_config['site_config']['name'].'/user/login');
		}
		$obj_orderbasic = OrderBasic::instance();
		$obj_orderdetail = OrderDetail::instance();
		$get_page = $this->input->get('page');
		$page = ($get_page != FALSE) ? intval($get_page) : "1";//当前页码
		$config['base_url'] = "/user/orderlist/";
		$config['total_items'] = $obj_orderbasic->get_user_order_count($this->_user['id']);//总数量
		$config['query_string']  = 'page';
		$config['items_per_page']  = 10;	//每页的数量
		$config['uri_segment']  = $page;
		$config['directory']  = "";//样式路径
		$query_struct = array(
				'where'=>array(
						'uid' => $this->_user['id'],
				),
				'orderby'   => array(
						'id'=>'desc',
				),
				'limit'=> array(
						'per_page'  => $config['items_per_page'],
						'offset'    => $page,
				),
		);
		$this->pagination = new Pagination($config);
		$this->pagination->initialize();
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
		$data = $obj_orderbasic->lists($query_struct);
		for ($i = 0; $i < count($data); $i++) {
			$order_id = $data[$i]['id'];
			$detail = $obj_orderdetail->get_orders_by_orderid($order_id);
			$data[$i]['child_orders'] = $detail;
		}
		$view = new View('user/order_list');
		$view->set("data", $data);
		$view->render(TRUE);
	}

	public function logout() {
		$user = $this->obj_session->get('USER');
		if (!empty($user)) {
			$this->obj_user_lib->update_user_lastlogin($user['id'], date('Y-m-d H:i:s'));
			$this->obj_session->delete('USER', NULL);
			$this->obj_session->delete('CART', NULL);
			$this->obj_session->delete('MODEL3D_DRAFT', NULL);
		}
		header('Location: '.url::base().'user/login');
	}
}