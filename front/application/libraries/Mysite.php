<?php defined('SYSPATH') or die('No direct script access.');

class Mysite_Core
{
	private static $instance;
	private $data;

	public static function & instance()
	{
		if (!isset(self::$instance))
		{
			$class = __CLASS__;
			self::$instance = new $class;
		}

		return self::$instance;
	}

	public function __construct()
	{
		$domain = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ?
			$_SERVER['HTTP_X_FORWARDED_HOST'] :
			(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
		$this->_load($domain);
	}
	/**
	 * 载入site 数据
	 */
	public function _load($domain)
	{
		/*$domain_obj = ORM::factory('domain')
				->where('domain',$domain)
				->find();*/
				
		//get site data
		$site = ORM::factory('site',1);
		//get site detail data
		$site_detail = ORM::factory('site_detail')
			->where('site_id',$site->id)
			->find();

		$this->data['id'] = $site->id;
		$this->data['theme'] = $site->theme_id;
		$this->data['https'] = $site->https;
		$this->data['pay_id'] = $site->pay_id;
		$this->data['secure_code'] = $site->secure_code;
		$this->data['https'] = $site->https;
		$this->data['name'] = $site->name;
		$this->data['domain'] = $site->domain;
		$this->data['site_email'] = $site->site_email;
		$this->data['type'] = $site->type;
		$this->data['title'] = $site->site_title;
		$this->data['logo'] = $site->logo;
		/* 判断站点是否有批发功能(管理员设定) */
		$this->data['wholesale'] = $site->wholesale;
		/* 判断站点是否打开批发(用户自己调节) */
		$this->data['is_wholesale'] = $site->is_wholesale;
		//该站点最大的销售数量
		$this->data['product_sale_max'] = $site->product_sale_max;
		
		$this->data['page_count'] = $site_detail->page_count;
		$this->data['statking_id'] = $site_detail->statking_id;
		$this->data['copyright'] = $site_detail->copyright;
		$this->data['statking_code'] = $site_detail->statking_code;
		$this->data['stat_code'] = $site_detail->stat_code;
		$this->data['pay_code'] = $site_detail->pay_code;
		$this->data['livechat'] = $site_detail->livechat;
		$this->data['global_vars'] = $site_detail->theme_config;
		$this->data['twitter'] = $site_detail->twitter;
		$this->data['facebook'] = $site_detail->facebook;
		$this->data['youtube'] = $site_detail->youtube;
		$this->data['trustwave'] = $site_detail->trustwave;
		$this->data['macfee'] = $site_detail->macfee;
		$this->data['head_code'] = $site_detail->head_code;
		$this->data['body_code'] = $site_detail->body_code;
		$this->data['index_code'] = $site_detail->index_code;
		$this->data['product_code'] = $site_detail->product_code;
		$this->data['payment_code'] = $site_detail->payment_code;
		$this->data['sitemap'] = $site_detail->sitemap;
		$this->data['theme_config'] = $site_detail->theme_config;
		$this->data['robots'] = $site_detail->robots;
		
		// 新增字段，用于存储站点基准币种与前台默认币种 ID
		$this->data['currency_default_id'] = $site->currency_default_id;
		$this->data['currency_base_id']    = $site->currency_base_id;

		/** 判断是否是来自联盟的流量，如果是联盟的流量，则设置相应的cookie added by gehaifeng **/
		//$return_string = Site_affiliateService::set_cookie($this->data['id']);
		//$this->check_default_domain($domain);
	}

	/**
	 * 验证是否是默认的域名，不是默认直接跳转
	 */
	public function check_default_domain($domain = ''){
		if(empty($domain)){
			die('No direct script access.');
		}

		if($this->data['domain'] <> $domain){
			//处理REQUEST_URI
			if(!isset($_SERVER['REQUEST_URI'])) {  
				$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
				if(isset($_SERVER['QUERY_STRING'])) $_SERVER['REQUEST_URI'] .= '?'.$_SERVER['QUERY_STRING'];
			}

			$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
			$request_uri = $_SERVER['REQUEST_URI'];
			$jump_url = $protocol . $this->data['domain'] . $request_uri;
			header('location:' . $jump_url);
			exit;
		}
	}

	/**
	 *
	 * get config data
	 * @return 	Array
	 */
	public function get($key = NULL)
	{
		if(empty($key))
		{
			return $this->data;
		}
		else
		{
			return isset($this->data[$key])?$this->data[$key]:'';
		}
	}

	/**
	 * get site's theme config
	 *
	 * @return 	String 	theme's id
	 */
	public function theme()
	{
		return $this->data['theme'];
	}

	/**
	 * get site's id
	 *
	 * @return 	Int 	site's id
	 */
	public function id()
	{
		if($this->data['id'])
		{
			return $this->data['id'];
		}
		else
		{
			return 0;
		}
	}

	/**
	 * get site's type
	 *
	 * @return 	Int 	type
	 */
	public function type()
	{
		return $this->data['type'];
	}

	/**
	 * get site's active status
	 *
	 * @return 	Int 	status code
	 */
	public function active()
	{
		return $this->data['active'];
	}

	/**
	 * get site's secure code
	 *
	 * @return 	Str 	secure code
	 */
	public function secure_code()
	{
		return $this->data['secure_code'];
	}

	/**
	 * get site's domain without "http://" or "https://"
	 *
	 * @return 	String 	for example: "www.example.com"
	 */
	public function domain()
	{
		return $this->data['domain'];
	}

	/**
	 * get site's https config
	 *
	 * @return 	boolean
	 */
	public function https()
	{
		return $this->data['https'];
	}

	/**
	 * get site's payment id
	 *
	 * @return 	Int
	 */
	public function pay_id()
	{
		return $this->data['pay_id'];
	}

	/**
	 * get site's pagination per page count
	 * @return 	Int 	page count
	 */
	public function page_count()
	{
		return $this->data['page_count'];
	}

	/**
	 * get site's service email
	 * @return 	Arr 	email
	 */
	public function email()
	{
		return $this->data['site_email'];
	}

	/**
	 * get site's route config
	 *
	 * @return 	Array
	 */
	public function route()
	{
		// set route config
		$this->data['route'] = Myroute::instance($this->data['id'])->route();
		return $this->data['route'];
	}

	/**
	 * get site's currency config information
	 *
	 * @return 	Array
	 */
	public function currency()
	{
		// set currency config
		$currencies = BLL_Currency::index();
		return $currencies;
	}

	/**
	 * get site's countries config information
	 *
	 * @return Array 	countriy Structure list
	 */
	public function countries()
	{
		$site_id = $this->data["id"];
		//TODO fix  get countries  method
		$countries = Rpc::instance()->address('countries',$site_id);
		
		return $countries;
	}

	/**
	 * get site's pay_success code
	 * @return 	Str 	javaScript code
	 */
	public function pay_code()
	{
		$data = $this->data['pay_code'];
		return $data;
	}

	/**
	 * get site's stat code
	 * @return 	Str 	javaScript code
	 */
	public function statking_code()
	{
		$data = $this->data['statking_code'];
		return Mytool::js_decode($data);
	}
	
	/**
	 * 得到站点导航(站点只有一级导航)
	 *
	 * @return 	Array
	 */
	public function menu()
	{
		$menus = ORM::factory('site_menu')
			->where(array('parent_id'=>0))
			->orderby('order','DESC')
            ->orderby('id','DESC')
			->find_all();
		$data = array();
		foreach($menus as $item)
		{
			$data[] = $item->as_array();
		}
		return $data;
	}
	
	/**
	 * 得到站点导航(包含html代码的多级导航)
	 *
	 * @return 	Array
	 */
	public function menus()
	{
		$root_menus = ORM::factory('site_menu')
			->orderby('order','DESC')
            ->orderby('id','ASC')
			->find_all();
		$html = '';
	    if(!empty($root_menus))
	    {
            $data = array();
		    foreach($root_menus as $item)
		    {
			    $data[] = $item->as_array();
		    }
		    $menus = site::get_menus_array($data);
			/* 当前菜单*/
		    $html = site::get_unlimited_menus($menus);
	    }
		return $html;
	}
	
	/**
	 * get sub_menus
	 *
	 * @return 	Array
	 */
	public function sub_menus($id)
	{
		$menus = ORM::factory('site_menu')
			->where(array('parent_id'=>$id))
			->orderby('order','DESC')
            ->orderby('id','DESC')
			->find_all();
		$data = array();
		foreach($menus as $item)
		{
			$data[] = $item->as_array();
		}
		return $data;
	}
	
	public function faqs()
	{
		$faqs = ORM::factory('faq')
			->orderby(array('order'=>'DESC','id'=>'DESC'))
			->find_all();
		$data = array();
		foreach($faqs as $item)
		{
			$data[] = $item->as_array();
		}
		return $data;
	}
	
	/**
	 * 获得站点的友情链接
	 */
	public function links()
	{
		$links = ORM::factory('site_link')
	        ->orderby(array('order'=>'DESC','id'=>'DESC'))
			->find_all();
			
		$data = array();
			
		foreach ($links as $item) {
			$data[] = $item->as_array();
		}
		
		return $data;
	}

	public function seo()
	{
		$seo = ORM::factory('seo')
			->find();
		$data = array();
		$data = $seo->as_array();
		return $data;
	}

	public function global_vars()
	{
		$global_vars = unserialize($this->get('global_vars'));
		return $global_vars;
	}

	/**
	 * get for pay_id
	 *
	 * @param String $domain
	 * @return Array
	 */
	public function get_by_pay_id($pay_id)
	{
		$site = ORM::factory('site')->where(array('pay_id'=>$pay_id))->find()->as_array();
		return $site;
	}
}
