<?php defined('SYSPATH') OR die('No direct access allowed.');

class Route_Controller extends Template_Controller {
	protected $current_flow = 'route';
	public $site_id = 1;

	public function __construct()
	{
		parent::__construct();
		role::check('site_route');
	}

	public function index()
	{
		$route_data = Myroute::instance()->get();

		if($_POST)
		{
			$site_next_flow = site::site_next_flow($this->current_flow);
			$submit_target = intval($this->input->post('submit_target'));

			if(Myroute::instance()->edit($_POST))	
			{
				//判断添加成功去向
				switch($submit_target)
				{
					case 2:
						remind::set(Kohana::lang('o_global.update_success'),$site_next_flow['url'],'success');
					default:
						remind::set(Kohana::lang('o_global.update_success'),'site/route','success');
				}
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),'site/route');
			}
		}

        $this->template->content = new View("site/route_edit");
		$this->template->content->is_modify = 0;
		if($this->manager_is_admin == 1)
		{
			$this->template->content->is_modify = 1;
		}
        $this->template->content->data = $route_data;
        $this->template->content->site_id = $this->site_id;
	}

	public function type()
	{
		$data['login'] = 'login';
		$data['logout'] = 'logout';
		$data['register'] = 'register';
		$data['cart'] = 'cart';
		$data['find_password'] = 'find-password';
		$data['get_password'] = 'get-password';
		$data['profile'] = 'profile';
		$data['wishlists'] = 'wishlists';
		$data['addresses'] = 'addresses';
		$data['password'] = 'password';
		$data['orders'] = 'orders';
		$data['product'] = 'product';
		//TODO category
		$data['category'] = 'category';
		$data['promotion'] = 'promotion';
		$data['faq'] = 'faq';
		$data['contact_us'] = 'contact-us';
		$data['user'] = 'user';
		$data['category_suffix'] = 'category_suffix';
		$data['product_suffix'] = 'product_suffix';

		$data['login_name'] = 'login';
		$data['logout_name'] = 'logout';
		$data['register_name'] = 'register';
		$data['cart_name'] = 'cart';
		$data['find_password_name'] = 'find password';
		$data['get_password_name'] = 'get password';
		$data['profile_name'] = 'profile';
		$data['wishlists_name'] = 'wishlists';
		$data['addresses_name'] = 'addresses';
		$data['password_name'] = 'password';
		$data['promotion_name'] = 'promotion';
		$data['faq_name'] = 'faq';
		$data['contact_us_name'] = 'contact us';

		// type : 0
		$type = 0;	

		// type : 1
		$type = 1;
		
		// type : 2
		$type = 2;
		
		// type : 3
		$type = 3;
		
	}

}
