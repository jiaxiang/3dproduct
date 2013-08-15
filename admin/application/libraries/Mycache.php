<?php defined('SYSPATH') or die('No direct script access.');

class Mycache_Core
{

	protected static $instances = array();

	// Configuration
	public $config;
	public $production;

	// server object
	protected $server;

	public static function & instance($config = FALSE)
	{
		if ( ! isset(Mycache::$instances[$config]))
		{
			// Create a new instance
			Mycache::$instances[$config] = new Mycache($config);
		}

		return Mycache::$instances[$config];
	}

	public function __construct($config = FALSE)
	{
        $this->production = false;

        //remark for test
        //$this->site_id = Mysite::instance()->id();
		$this->site_id = 1;

		if (!kohana::config('memcache.'.$config))
		{
			$this->config = Kohana::config('memcache.default');
		}
		else
		{
			$this->config = kohana::config('memcache.'.$config);
		}

		if($this->production)
		{
			// Initialize the drivers
			$this->server = new Memcache();

			foreach($this->config['server'] as $item)
			{
				$this->server->addServer($item['host'],$item['port']) or die ("cache error");
			}
		}
	}

	/**
	 * get the global data by key without site id
	 *
	 * @param 	String 	key
	 * @return 	Fix 	cache data
	 */
	public function get($key)
	{
		if($this->production) return $this->server->get($key);
	}

	/**
	 * set the global data 
	 *
	 * @param 	String 	key
	 * @param 	Fix 	cache data 
	 */
	function set($key, $data,$expire = NULL)
	{
		if($expire)
		{
			if($this->production) $this->server->set($key,$data,$this->config['compression'],$expire);
		}
		else
		{
			if($this->production) $this->server->set($key,$data,$this->config['compression'],$this->config['expire']);
		}
	}

	/**
	 * global delete site's cache by key
	 *
	 * @param 	String 	$key 
	 */
	public function delete($key)
	{
		if($this->production) $this->server->delete($key);
	}

	/**
	 * get the sever version
	 *
	 * @return 	String  server version information
	 */
	public function version()
	{
		return $this->server->getVersion();
	}
}
