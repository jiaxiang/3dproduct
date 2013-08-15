<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mydomaininterface_Core {
	// Driver object
	protected $driver;

	private static $instance;
	public static function & instance($api)
	{
		if (!isset(self::$instance))
		{
			$class = __CLASS__;
			self::$instance = new $class($api);
		}
		return self::$instance;
	}

	public function __construct($api)
	{
		//driver启动
		$driver = 'Mydomaininterface_'.ucfirst($api).'_Driver';

		// Load the driver
		if ( ! Kohana::auto_load($driver))
			throw new Kohana_Exception($driver.'.driver_not_found',$api, get_class($this));

		// Initialize the driver
		$this->driver = new $driver();
		// Validate the driver
		if ( ! ($this->driver instanceof Mydomaininterface_Driver))
			throw new Kohana_Exception('core.driver_implements', $api, get_class($this), 'Mydomain_interface_Driver');
	}

	/**
	 * get transfer info
	 */
	public function values()
	{
		return $this->driver->Values;
	}
	/**
	 * set interface account
	 *
	 * @param $username account username
	 * @param $password account password
	 */
	public function account($username,$password)
	{
		$this->driver->account($username,$password);
	}

	/**
	 * check domain
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @return Boolean
	 */
	public function check($sld,$tld)
	{
		return $this->driver->check($sld,$tld);
	}
	
	/**
	 * check domain
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @return Boolean
	 */
	public function purchase($sld,$tld)
	{
		return $this->driver->purchase($sld,$tld);
	}
	
	/**
	 * get domain info
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @return Boolean
	 */
	public function get_dns($sld,$tld)
	{
		return $this->driver->get_dns($sld,$tld);
	}

	/**
	 * get domain hosts
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @return Boolean
	 */
	public function get_hosts($sld,$tld)
	{
		return $this->driver->get_hosts($sld,$tld);
	}

	/**
	 * set domain hosts
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @param $data 
	 * @return Boolean
	 */
	public function set_hosts($sld,$tld,$data)
	{
		return $this->driver->set_hosts($sld,$tld,$data);
	}

	/**
	 * set domain name server
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @param $data 
	 * @return Boolean
	 */
	public function set_ns($sld,$tld,$data)
	{
		return $this->driver->set_ns($sld,$tld,$data);
	}
}
