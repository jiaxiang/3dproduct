<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Mymonitor API driver
 */
class Mymonitor_Jiankongbao_Driver extends Mymonitor_Driver {
	
	protected $monitor_config;
	
	protected $server;
	
	protected $username;
	
	protected $password;
	
	public function __construct($config)
	{
		$this->monitor_config = Kohana::config('monitor.'.$config);
		$this->server = $this->monitor_config['server'];
		$this->username = $this->monitor_config['username'];
		$this->password = $this->monitor_config['password'];
	}
	
	public function create_http($task_name, $frequency, $url){
		$post = array('task_name' => $task_name, 'frequency' => $frequency, 'url' => $url);
		$ch = curl_init($this->server.'task/http/create.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post)); 
		$data = json_decode(curl_exec($ch), true);
		if($data['response']['result']){
			return TRUE;
		}else{
			return FALSE;
			//return $data['response']['error'];
		}
	}
	
	public function create_ping($task_name, $frequency, $host){
		$post = array('task_name' => $task_name, 'frequency' => $frequency, 'host' => $host);
		$ch = curl_init($this->server.'task/ping/create.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post)); 
		$data = json_decode(curl_exec($ch), true);
		if($data['response']['result']){
			return TRUE;
		}else{
			return FALSE;
			//return $data['response']['error'];
		}
	}
	
	public function update($task_id, $task_name, $frequency){
		$post = array('task_name' => $task_name, 'frequency' => $frequency);
		$ch = curl_init($this->server.'site/task/'.$task_id.'/update.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post)); 
		$data = json_decode(curl_exec($ch), true);
		if($data['response']['result']){
			return TRUE;
		}else{
			return FALSE;
			//return $data['response']['error'];
		}
	}
	
	public function delete($task_id){
		if(!$task_id) return FALSE;
		$ch = curl_init($this->server.'site/task/'.$task_id.'/delete.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
		curl_setopt($ch, CURLOPT_POST, true);
		$data = json_decode(curl_exec($ch), true);
		if(isset($data['response']['result'])){
			return TRUE;
		}else{
			//return FALSE;
			return $data['response']['error'];
		}
	}
	
	public function get_list()
	{
		$ch = curl_init($this->server.'site/task/list.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
		curl_setopt($ch, CURLOPT_HTTPGET, true);
		$data = json_decode(curl_exec($ch), true);
		return $data['tasks']['task'];
	}
	
	public function report($task_id)
	{
		if(!$task_id) return FALSE;
		$ch = curl_init($this->server.'site/task/'.$task_id.'/report.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
		curl_setopt($ch, CURLOPT_HTTPGET, true);
		$data = json_decode(curl_exec($ch), true);
		curl_close($ch);
		return $data['reports']['report'];
	}
	
	public function report_date($task_id, $date)
	{
		if(!$task_id && !$date) return FALSE;
		$ch = curl_init($this->server.'site/task/'.$task_id.'/report/'.$date.'.json');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username.':'.$this->password);
		curl_setopt($ch, CURLOPT_HTTPGET, true);
		$data = json_decode(curl_exec($ch), true);
		curl_close($ch);
		if(isset($data['report'])) return $data['report'];
		return FALSE;
	}
}
