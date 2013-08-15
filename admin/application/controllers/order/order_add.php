<?php defined('SYSPATH') OR die('No direct access allowed.');

class Order_add_Controller extends Template_Controller 
{
	public function __construct() 
	{
		parent::__construct(); //this must be included
		if($this -> is_ajax_request() == TRUE)
		{
			$this -> template = new View('layout/default_json');
		}
	}
	/***********************************************	添加订单  	***********************************************/

	/**
	 * 添加订单
	 */
	public function index() 
	{
		/*权限检查*/
		role::check('order_add');

		/*session*/
		$this->session = Session::instance();
		$this->session->delete('email');
		$this->session->delete('cart_data');
		
		$this -> template -> content = new View("order/order_add");
	}

	/**
	 * 添加订单过程中的第二步
	 */
	function add_next()
	{
		/*权限检查*/
		role::check('order_add');
		
		$good_info = array();

		if($_POST) {
			/*验证用户邮箱*/
			$post = new Validation($_POST);
			$post->pre_filter('trim');
			$post->add_rules('email', 'required','email');

			if (!($post->validate()))
			{
				remind::set(Kohana::lang('o_order.email_wrong'),'/order/order_add','error');		
			}
			$email = $post->email;
			
			/*session*/
			$this->session = Session::instance();
			$this->session->set('email',$email);
		}
		else 
		{
			$session = Session::instance();
			$email = $session->get('email');
			$cart_data = $session->get('cart_data');
			if(!empty($cart_data))
			{
				foreach($cart_data as $key=>$value)
				{
					if($value > 0)
					{
						$good = ProductService::get_instance()->get($key);
						$good['cart_num'] = $value;

						$good_info[] = $good;
					}
				}
			}
		}
		$user = Myuser::instance()->get_by_email($email);
		if(!$user)
		{
			remind::set(Kohana::lang('o_order.user_not_exist'),'order/order_add','error');		
		}
		if($user['active']==0)
		{
			remind::set(Kohana::lang('o_order.user_not_active'),'order/order_add','error');		
		}

		/*模板输出*/
		$this->template->content = new view("order/order_add_2");
		$this->template->content->data = $user;
		$this->template->content->good_info = $good_info;
	}

	/**
	 * 添加订单第三步
	 */
	function add_again()
	{
		/*权限检查*/
		role::check('order_add');
		if(!$_POST)
		{
			remind::set(Kohana::lang('o_order.change_site'),'order/order_add','error');
		}
		$user_id = $this->input->post('user_id');
		$email   = $this->input->post('email');
        
		/* 存放用户添加货品的信息 */
		$cart_data = array();

		/* 订单产品的总价 */
		$good_price = 0;
		$shipping_price = 10;
		$good_info = array();
		$address_info = array();
		
		/* 得到国家列表*/
		$country_query_struct = array(
			'where'=>array(
				'active'  => 1
			),
			'orderby' => array (
                'id' => 'ASC' 
            ),
		);
		$country_list = Mycountry::instance()->query_assoc($country_query_struct);
        
		if(empty($country_list))
		{
			remind::set(Kohana::lang('o_order.no_country'),'order/order_add','error');
		}
		/* 得到默认地址信息*/
		$address_query_struct = array(
			'where'=>array(
				'user_id' => $user_id
			),
			'limit' => array (
                'per_page' => 1, 
                'offset' => 0 
            ), 
			'orderby' => array (
                'date_add' => 'DESC' 
            ),
		);
		$address = Myaddress::instance()->query_assoc($address_query_struct);
		if(!empty($address))
		{
			foreach($address as $value)
			{
				$address_info = $value;
			}
		}
		
		/*得到站点物流方式*/
		$carrier_list = DeliveryService::get_instance()->select_list();			
		if(empty($carrier_list))
		{
			remind::set(Kohana::lang('o_order.no_carrier'),'order/order_add','error');
		}
		$query_struct = array('where'=>array());
		$currency_info = Mycurrency::instance()->query_assoc($query_struct);

		/*处理已选中货品信息*/
		$good_amount = $this->input->post('amount');
		foreach($good_amount as $key => $value)
		{
			if($value <= 0)continue;
			$good = ProductService::get_instance()->get($key);
			
			if($good['store'] <=0 && $value > 999)
			{
				$value = 999;
			}
			if($good['store'] >0 && $value > $good['store'])
			{
				$value = $good['store'];
			}
			/* 计算总价 */
			$good_price += $good['price'] * $value;

			$good['cart_num'] = $value;

			$good_info[] = $good;

			$cart_data[$key] = $value;
		}

		/*session*/
		$this->session = Session::instance();
		$this->session->set('cart_data', $cart_data);
		
		/*模板输出*/
		$this->template->content                      = new view("order/order_add_3");
		$this->template->content->good_price          = $good_price;
		$this->template->content->shipping_price      = $shipping_price;
		$this->template->content->user_id             = $user_id;
		$this->template->content->email               = $email;
		$this->template->content->country_list        = $country_list;
		$this->template->content->carrier_list        = $carrier_list;
		$this->template->content->currency_info       = $currency_info;
		$this->template->content->address_info        = $address_info;
		$this->template->content->good_info           = $good_info;
	}

	/**
	 * 添加订单
	 */
	function do_add(){
		/*权限检查*/
		role::check('order_add');
		
		if($_POST)
		{
			$post = new Validation($_POST);
			$post -> pre_filter('trim');
			$post -> add_rules('shipping_firstname','required','length[1,200]');
			$post -> add_rules('shipping_lastname','required','length[1,200]');
			$post -> add_rules('shipping_country','required','length[1,200]');
			$post -> add_rules('shipping_state','length[1,200]');
			$post -> add_rules('shipping_city','required','length[1,200]');
			$post -> add_rules('shipping_address','required','length[1,200]');
			$post -> add_rules('shipping_zip','required','length[1,200]');
			$post -> add_rules('shipping_phone','required','length[1,200]');
			$post -> add_rules('shipping_mobile','length[1,200]');
			$post -> add_rules('billing_firstname','length[1,200]');
			$post -> add_rules('billing_lastname','length[1,200]');
			$post -> add_rules('billing_country','length[1,200]');
			$post -> add_rules('billing_state','length[1,200]');
			$post -> add_rules('billing_city','length[1,200]');
			$post -> add_rules('billing_address','length[1,200]');
			$post -> add_rules('billing_zip','length[1,200]');
			$post -> add_rules('billing_phone','length[1,200]');
			$post -> add_rules('billing_mobile','length[1,200]');
			$post -> add_rules('good_price','required','length[1,200]');
			$post -> add_rules('shipping_price','required','length[1,200]');

			if(!($post->validate()))
			{
				$errors = $post -> errors();
				log::write('form_error',$errors,__FILE__,__LINE__);
				remind::set(Kohana::lang('o_order.user_address_wrong'),'order/order_add/add_again','error');		
			}

			/* 添加主订单详情*/
			$order_data = array();

			$user_id 		= $this->input->post('user_id');
			$email 			= $this->input->post('email');
			$carrier 		= $this->input->post('carrier');
			$currency_code 	= $this->input->post('code');
			
			if($user_id && $email && $currency_code)
			{
				/* 订单用户信息*/
				$order_data['user_id'] = $user_id;
				$order_data['email'] = $email;
				/* 订单币种信息*/
				$currency = Mycurrency::instance()->get_by_code($currency_code);
				$order_data['currency']	          = $currency_code;
				$order_data['conversion_rate']	  = $currency['conversion_rate'];
				/* 订单国家*/
				$order_data['shipping_country']	  = Mycountry::instance($post->shipping_country)->get('iso_code');
				$order_data['billing_country']	  = Mycountry::instance($post->billing_country)->get('iso_code');
				/* 订单时间和IP信息*/
				$order_data['data_add'] = date('Y-m-d H:i:s',time());
				$order_data['IP'] = tool::get_long_ip();
                
				/* 订单号生成*/
				$order_num = '';
				do{
					$temp = sprintf("%14.0f", (((date('ymd')."00000" + rand(0,99999))."0000")));

					$exist_data = array();
					$exist_data['order_num'] = $temp;
					if(!Myorder::instance()->exist($exist_data)){
						$order_num	= $temp;
						break;
					}
				}while(1);
				$order_data['order_num']            = $order_num;
				$order_data['order_status']	        = '1';
				$order_data['pay_status']	        = '1';
				$order_data['ship_status']	        = '1';
				$order_data['user_status']	        = 'NULL';
				$order_data['order_source']	        = 'manual';
				$order_data['total']	            = $post->good_price + $post->shipping_price;
				$order_data['total_products']	    = $post->good_price;
				$order_data['total_shipping']	    = $post->shipping_price;
				$order_data['total_real']	        = $order_data['total'] / $order_data['conversion_rate'];
				$order_data['total_discount']	    = '0.00';
				$order_data['total_paid']	        = '0.00';
				$order_data['shipping_firstname']	= $post->shipping_firstname;
				$order_data['shipping_lastname']	= $post->shipping_lastname;
				$order_data['shipping_state']		= $post->shipping_state;
				$order_data['shipping_city']		= $post->shipping_city;
				$order_data['shipping_address']	    = $post->shipping_address;
				$order_data['shipping_zip']		    = $post->shipping_zip;
				$order_data['shipping_phone']		= $post->shipping_phone;
				$order_data['shipping_mobile']		= $post->shipping_mobile;
				$order_data['billing_firstname']	= $post->billing_firstname;
				$order_data['billing_lastname']	    = $post->billing_lastname;
				$order_data['billing_state']		= $post->billing_state;
				$order_data['billing_city']		    = $post->billing_city;
				$order_data['billing_address']	    = $post->billing_address;
				$order_data['billing_zip']		    = $post->billing_zip;
				$order_data['billing_phone']		= $post->billing_phone;
				$order_data['billing_mobile']		= $post->billing_mobile;
				$order_data['carrier']          	= $carrier;
				$order_data['active']			    = 1;
			}
			else 
			{
				remind::set(Kohana::lang('o_order.data_trans_wrong'),'order/order_add','error');
			}
            
			/* 添加订单，返回订单数据*/
			$order_id = Myorder::instance()->add($order_data);
			$order = Myorder::instance($order_id)->get();

			/* 添加订单产品信息*/
			$session = Session::instance();
			$cart_data = $session->get('cart_data');
			if(isset($cart_data) && is_array($cart_data) && count($cart_data) && !empty($order['order_num']))
			{
				foreach ($cart_data as $key => $rs)
				{
					$good_full_data = ProductService::get_instance()->get($key);
                    
					$order_product_detail_data                          = array();
					$order_product_detail_data['order_id']              = $order['id'];
					$order_product_detail_data['product_type']          = ProductService::PRODUCT_TYPE_GOODS;
					$order_product_detail_data['dly_status']            = 'storage';
					//$order_product_detail_data['product_id']            = $good_full_data['product_id'];
					$order_product_detail_data['good_id']               = $key;
					$order_product_detail_data['quantity']              = $rs;
					$order_product_detail_data['sendnum']               = '0';
					$order_product_detail_data['price']                 = $good_full_data['price'];
					$order_product_detail_data['discount_price']        = $good_full_data['price'];
					$order_product_detail_data['weight']                = $good_full_data['weight'];
					//$order_product_detail_data['name']                = $good_full_data['name_manage'];
					$order_product_detail_data['name']                  = $good_full_data['title'];
					$order_product_detail_data['SKU']                   = $good_full_data['sku'];
					$order_product_detail_data['brief']                 = $good_full_data['brief'];
					$order_product_detail_data['date_add']              = date('Y-m-d H:i:s',time());
    				$order_product_detail_data['link']                  = product::permalink($good_full_data);
                    order::do_order_product_detail_data_by_good(&$order_product_detail_data, $good_full_data, $good_full_data['default_image_id']);
					$order_product_detail = Myorder_product::instance()->add($order_product_detail_data);
				}
			}
			
			/*验证是否添加成功，添加成功返回订单号*/
			if(!empty($order['order_num']) && $order_product_detail)
			{
				remind::set(Kohana::lang('o_order.add_order_success').$order['order_num'],'order/order','success');
			}
			else
			{
				remind::set(Kohana::lang('o_order.add_order_wrong'),'order/order_add','error');
			}
		}
	}
}
