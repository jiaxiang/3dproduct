<?php defined('SYSPATH') OR die('No direct access allowed.');

class Host_server_Core {

	private static $instance;

	private static $server;
	
	private static $key;

	private $phprpc;

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
		self::$server = Kohana::config('host.server');
		$this->phprpc = TRUE;
		if (Kohana::config('key.api_key_host')){
			$this->key = Kohana::config('key.api_key_host');
		}else{
			die('api_key_host error~');
		}
	}

	public function __call($name, $args){

		$method = $name;
		switch ($method) {
			case 'get_hosts':
				$args[] = $this->key['default']['api_id'];
				$args[] = md5($this->key['default']['api_key']);
				break;
			case 'set_hosts':
				$args[] = $this->key['default']['api_id'];
				$args[] = md5($this->key['default']['api_key']);
				break;
			default:
				die('method error~');
		}
		
		if($this->phprpc)
		{
			require_once(APPPATH.'vendor/phprpc/phprpc_client.php');
			$client = new PHPRPC_Client(self::$server); 
			return $client->{$method}($args);
		}
	}
}
