<?php defined('SYSPATH') or die('No direct script access.');

class Mylog_Core{
	private static $instance;

	private $data;

	private $error = FALSE;
	private $warning = FALSE;
	private $debug = FALSE;
	private $info = FALSE;

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
		$this->_load();
	}

	public function _load()
	{
		$this->error = TRUE;	
		$this->warning = TRUE;
		$this->debug = TRUE;
		$this->info = TRUE;
	}

	public function add($type,$log,$file = NULL,$line = NULL)
	{
		$data = array();
        $data['type'] = $type;
        
		$data['log'] = var_export($log,true).PHP_EOL.$file.PHP_EOL.$line;	
        
		$data['created'] = date('Y-m-d H:i:s');

		$log = ORM::factory('log');
		if($log->validate($data))
		{
			$log->save();
			return TRUE;
		}else{
			return FALSE;	
		}
	}

	public function error($log,$file = NULL,$line = NULL)
	{
		if($this->error) $this->add('error',$log,$file,$line);			
	}

	public function warning($log,$file = NULL,$line = NULL)
	{
		if($this->warning) $this->add('warning',$log,$file,$line);			
	}

	public function debug($log,$file = NULL,$line = NULL)
	{
		if($this->debug) $this->add('debug',$log,$file,$line);			
	}

	public function info($log,$file = NULL,$line = NULL)
	{
		if($this->info) $this->add('info',$log,$file,$line);			
	}
}
