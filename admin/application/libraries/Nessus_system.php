<?php defined('SYSPATH') OR die('No direct access allowed.');

class Nessus_system_Core {

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
		if (Kohana::config('nessus_system.server')&&Kohana::config('key.api_key_NessusSystem')){
			$this->server = Kohana::config('nessus_system.server');
			$this->key = Kohana::config('key.api_key_NessusSystem');
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
	
	/* 队列管理 - 添加操作  */
	public function queue_add($site){
		if(empty($site)){
			remind::set('域名或者IP出错，请检查下您的输入！','site/scan/add');
		}else{
			if(filter_var($site, FILTER_VALIDATE_IP)){
				$ip = $site;
			}else if(filter_var(gethostbyname($site), FILTER_VALIDATE_IP)){
				$ip = gethostbyname($site);
			}else{
                                remind::set('域名或者IP出错，请检查下您的输入！','site/scan/add');
			}
		}
		$sign = $this->generate_code('queue', $ip);
		return $this->client->queue_add($ip, $sign, $this->ip);
	}

	/* 队列管理 - 删除操作  */
	public function queue_delete($site){
		$sign = $this->generate_code('queue', $site);
		return $this->client->queue_delete($site, $sign, $this->ip);
	}
	
	/* 报告管理 - 获取报告内容操作  */
	public function report_get($queue_id){
		$sign = $this->generate_code('report', $queue_id);
		return $this->client->report_get($queue_id, $sign, $this->ip);
	}
	
	/* 报告管理 - 获取报告状态操作  */
	public function report_get_status($queue_id){
		$sign = $this->generate_code('report', $queue_id);
		return $this->client->report_get_status($queue_id, $sign, $this->ip);
	}
}