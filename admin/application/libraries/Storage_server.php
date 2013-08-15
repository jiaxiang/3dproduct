<?php defined('SYSPATH') OR die('No direct access allowed.');

class Storage_server_Core {

	private static $instance;		//单个实例

	private static $server;			//API服务端地址
	
	private static $key;			//API的Key

	private $client;				//phprpc客户端

	private $ip;					//IP地址

	//单例访问点
	public static function instance()
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
		if (Kohana::config('storage.server')&&Kohana::config('key.api_key_tt')){
			$this->server = Kohana::config('storage.server');
			$this->key = Kohana::config('key.api_key_tt');
		}else{
			die('config load error~');
		}
		//初始化IP地址
		$this->ip = ip2long($_SERVER['REMOTE_ADDR']);
		//初始化phprpc客户端
		require_once(APPPATH.'vendor/phprpc/phprpc_client.php');
		$this->client = new PHPRPC_Client($this->server);
	}
	
	//生成密文函数
	private function generate_code($type, $args_str){
		return md5($args_str.$this->ip.$this->key[$type]);
	}
	
	/* 商品图片 - 存/删/取 操作  */
	public function cache_product($site_id, $sku, $content){
		$sign = $this->generate_code('product', $site_id.$sku);
		return $this->client->cache_product($site_id, $sku, $content, $sign, $this->ip);
	}
	
	public function delete_product($site_id, $sku){
		$sign = $this->generate_code('product', $site_id.$sku);
		return $this->client->delete_product($site_id, $sku, $sign, $this->ip);
	}
	
	public function get_product($site_id, $sku){
		$sign = $this->generate_code('product', $site_id.$sku);
		return $this->client->get_product($site_id, $sku, $sign, $this->ip);
	}
	
	/* 站点资源 - 存/删/取 操作  */
	public function cache_site($site_id, $filename, $content){
		$sign = $this->generate_code('site', $site_id.$filename);
		return $this->client->cache_site($site_id, $filename, $content, $sign, $this->ip);
	}
	
	public function delete_site($site_id, $filename){
		$sign = $this->generate_code('site', $site_id.$filename);
		return $this->client->delete_site($site_id, $filename, $sign, $this->ip);
	}
	
	public function get_site($site_id, $filename){
		$sign = $this->generate_code('site', $site_id.$filename);
		return $this->client->get_site($site_id, $filename, $sign, $this->ip);
	}
	
	/* 主题资源 - 存/删/取 /获取一个主题下面某类资源的文件名 操作  */
	public function cache_theme($theme_id, $type, $filename, $content){
		$sign = $this->generate_code('theme', $theme_id.$type.$filename);
		return $this->client->cache_theme($theme_id, $type, $filename, $content, $sign, $this->ip);
	}
	
	public function delete_theme($theme_id, $type, $filename){
		$sign = $this->generate_code('theme', $theme_id.$type.$filename);
		return $this->client->delete_theme($theme_id, $type, $filename, $sign, $this->ip);
	}
	
	public function get_theme($theme_id, $type, $filename){
		$sign = $this->generate_code('theme', $theme_id.$type.$filename);
		return $this->client->get_theme($theme_id, $type, $filename, $sign, $this->ip);
	}
	
	public function get_themes($theme_id, $type){
		$sign = $this->generate_code('theme', $theme_id.$type);
		return $this->client->get_themes($theme_id, $type, $sign, $this->ip);
	}
	
	/* 站点主题资源 - 存/删/取 /获取一个站点下某个主题下面的某类资源的文件名 操作  */
	public function cache_site_theme($theme_id, $type, $filename, $content){
		$sign = $this->generate_code('site_theme', $theme_id.$type.$filename);
		return $this->client->cache_site_theme(1, $theme_id, $type, $filename, $content, $sign, $this->ip);
	}
	
	public function delete_site_theme($site_id, $theme_id, $type, $filename){
		$sign = $this->generate_code('site_theme', $site_id.$theme_id.$type.$filename);
		return $this->client->delete_site_theme($site_id, $theme_id, $type, $filename, $sign, $this->ip);
	}
	
	public function get_site_theme($site_id, $theme_id, $type, $filename){
		$sign = $this->generate_code('site_theme', $site_id.$theme_id.$type.$filename);
		return $this->client->get_site_theme($site_id, $theme_id, $type, $filename, $sign, $this->ip);
	}
	
	public function get_site_themes($site_id, $theme_id, $type = 'views'){
		$sign = $this->generate_code('site_theme', $site_id.$theme_id.$type);
		return $this->client->get_site_themes($site_id, $theme_id, $type, $sign, $this->ip);
	}
	
	/* 归类资源 - 存/删/取 操作  */
	public function cache_category($site_id, $type, $filename, $content){
		$sign = $this->generate_code('category', $site_id.$type.$filename);
		return $this->client->cache_category($site_id, $type, $filename, $content, $sign, $this->ip);
	}
	
	public function delete_category($site_id, $type, $filename){
		$sign = $this->generate_code('category', $site_id.$type.$filename);
		return $this->client->delete_category($site_id, $type, $filename, $sign, $this->ip);
	}
	
	public function get_category($site_id, $type, $filename){
		$sign = $this->generate_code('category', $site_id.$type.$filename);
		return $this->client->get_category($site_id, $type, $filename, $sign, $this->ip);
	}
	
	/* 系统配置 - 存/删/取 操作  */
	public function cache_config($type, $name, $value){
		$sign = $this->generate_code('config', $type.$name);
		return $this->client->cache_config($type, $name, $value, $sign, $this->ip);
	}
	
	public function delete_config($type, $name){
		$sign = $this->generate_code('config', $type.$name);
		return $this->client->delete_config($type, $name, $sign, $this->ip);
	}
	
	public function get_config($type, $name){
		$sign = $this->generate_code('config', $type.$name);
		return $this->client->get_config($type, $name, $sign, $this->ip);
	}
	
	/* 公共资源 - 存 操作  */
	public function cache_public($filename, $content){
		$sign = $this->generate_code('public', $filename);
		return $this->client->cache_public($filename, $content, $sign, $this->ip);
	}
	
	/* 支付图片 - 存 操作  */
	public function cache_payment($filename, $content){
		$sign = $this->generate_code('payment', $filename);
		return $this->client->cache_payment($filename, $content, $sign, $this->ip);
	}
	
	/* 删除站点所有资源操作 */
	public function clear_site_all($site_id){
		$sign = $this->generate_code('site', $site_id);
		return $this->client->clear_site_all($site_id, $sign, $this->ip);
	}
}