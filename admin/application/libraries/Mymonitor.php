<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mymonitor_Core {
	// Driver object
	protected $driver;

	private static $instance;
	
	public static function & instance($name)
	{
		if (!isset(self::$instance))
		{
			$class = __CLASS__;
			self::$instance = new $class($name);
		}
		return self::$instance;
	}

	public function __construct($name)
	{
		// Set driver name
		$driver = 'Mymonitor_'.ucfirst($name).'_Driver';

		// Load the driver
		if ( ! Kohana::auto_load($driver))
			throw new Kohana_Exception($driver.'.driver_not_found',$name, get_class($this));

		// Initialize the driver
		$this->driver = new $driver($name);
		// Validate the driver
		if ( ! ($this->driver instanceof Mymonitor_Driver))
			throw new Kohana_Exception('core.driver_implements', $name, get_class($this), 'Mymonitor_Driver');
	}
	
	/**
	 * 创建HTTP类型监控项目.
	 *
	 * @param   string  task_name
	 * @param	int		frequency
	 * @param	string	url
	 * @return  boolean
	 */
	public function create_http($task_name, $frequency, $url){
		return $this->driver->create_http($task_name, $frequency, $url);
	}
	
	/**
	 * 创建PING类型监控项目.
	 *
	 * @param   string  task_name
	 * @param	int		frequency
	 * @param	string	host
	 * @return  boolean
	 */
	public function create_ping($task_name, $frequency, $host){
		return $this->driver->create_ping($task_name, $frequency, $host);
	}
	
	/**
	 * 更新站点监控项目.
	 *
	 * @param   int		task_id
	 * @param   string  task_name
	 * @param	int		frequency
	 * @return  boolean
	 */
	public function update($task_id, $task_name, $frequency){
		return $this->driver->update($task_id, $task_name, $frequency);
	}
	
	/**
	 * 删除站点监控项目.
	 *
	 * @param   int		task_id
	 * @return  boolean
	 */
	public function delete($task_id){
		return $this->driver->delete($task_id);
	}

	/**
	 * 获取所有监控项目的列表.
	 *
	 * @param   int  task_id
	 * @return  array
	 */
	public function get_list()
	{
		return $this->driver->get_list();
	}

	/**
	 * 获得单个监控项目的报告列表
	 *
	 * @param   int  task_id
	 * @return  array
	 */
	public function report($task_id)
	{
		return $this->driver->report($task_id);
	}
	
	/**
	 * 获得单个监控项目某日的报告
	 *
	 * @param   int  task_id
	 * @param   string   日期(格式：20100225)
	 * @return  array
	 */
	public function report_date($task_id, $date)
	{
		return $this->driver->report_date($task_id, $date);
	}
}