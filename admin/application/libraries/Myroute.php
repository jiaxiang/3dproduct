<?php
defined('SYSPATH') or die('No direct script access.');

class Myroute_Core
{
	private static $instance;
	private $data;
	protected $serv_route_instance = NULL;
	public static function &instance()
	{
		if(!isset(self::$instance))
		{
			$class = __CLASS__;
			self::$instance = new $class();
		}
		
		return self::$instance;
	}
	/**
     * 获取路由实例管理实例
     */
    private function get_serv_route_instance()
    {
        if($this->serv_route_instance === NULL){
            $this->serv_route_instance = ServRouteInstance::getInstance(ServRouteConfig::getInstance());
        }
        return $this->serv_route_instance;
    }
	/**
	 * get the site's route config information
	 *
	 * @return 	Fix 	site's route config information
	 */
	public function get()
	{
        return ORM::factory('route')->find()->as_array();
		$serv_route_instance = $this->get_serv_route_instance();
		$cache_instance      = $serv_route_instance->getMemInstance('route', array('id' => 0))->getInstance();
        $route_key           = 'route';
        $route               = $cache_instance->get($route_key);
        if (empty($route)) {
        	$route = ORM::factory('route')->find()->as_array();
        	$cache_instance->set($route_key, $route);
        }
		
		return $route;
	}
	
	/**
	 * 得到路由的可设定段
	 */
	public function get_route_columns()
	{
		$route = ORM::factory('route');
		$route_columns = $route->table_columns;
		return $route_columns;
	}

	/**
	 * set site's route config 
	 *
	 * @param 	Int 	$site_id
	 * @param 	Array 	route data	
	 */
	public function edit($data)
	{
		$route = ORM::factory('route')->find();
        
		$errors = '';
		if($route->validate($data ,TRUE ,$errors))
		{
			//$serv_route_instance = $this->get_serv_route_instance();
			//$cache_instance      = $serv_route_instance->getMemInstance('route', array('id' => $site_id))->getInstance();
	        //$route_key           = 'route';
	        //$route               = $cache_instance->set($route_key, NULL);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * init site default data
	 */
	public function init($type = 0)
	{
		$type = 0;
		$data = array();
		
		$data['type'] = $type;
		
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
		$data['promotion'] = 'promotion';
		$data['faq'] = 'faq';
		$data['contact_us'] = 'contact-us';
		$data['user'] = 'user';
		//TODO
		$data['category_suffix'] = '';
		$data['product_suffix'] = '';
		
		$data['login_name'] = 'login';
		$data['logout_name'] = 'logout';
		$data['register_name'] = 'register';
		$data['cart_name'] = 'cart';
		$data['find_password_name'] = 'find-password';
		$data['get_password_name'] = 'get-password';
		$data['profile_name'] = 'profile';
		$data['wishlists_name'] = 'wishlists';
		$data['addresses_name'] = 'addresses';
		$data['password_name'] = 'password';
		$data['orders_name'] = 'orders';
		$data['product_name'] = 'product';
		$data['promotion_name'] = 'promotion';
		$data['faq_name'] = 'faq';
		$data['contact_us_name'] = 'contact-us';
		
		//$route = self::instance();
		return $this->edit($data);
	}

	/**
	 * 删除站点的路由信息
	 * 
	 * @param int $site_id
	 * @return boolean
	 */
	public function delete_by_site($site_id)
	{
		$route = ORM::factory('route')->where('site_id',$site_id);
		$route->delete_all();
		return true;
	}
}
