<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Mymonitor API driver
 *
 */
abstract class Mymonitor_Driver {

	/**
	 * 创建HTTP类型监控项目.
	 *
	 * @param   string  task_name
	 * @param	int		frequency
	 * @param	string	url
	 * @return  boolean
	 */
	abstract public function create_http($task_name, $frequency, $url);
	
	/**
	 * 创建PING类型监控项目.
	 *
	 * @param   string  task_name
	 * @param	int		frequency
	 * @param	string	host
	 * @return  boolean
	 */
	abstract public function create_ping($task_name, $frequency, $host);
	
	/**
	 * 更新站点监控项目.
	 *
	 * @param   int		task_id
	 * @param   string  task_name
	 * @param	int		frequency
	 * @return  boolean
	 */
	abstract public function update($task_id, $task_name, $frequency);
	
	/**
	 * 删除站点监控项目.
	 *
	 * @param   int		task_id
	 * @return  boolean
	 */
	abstract public function delete($task_id);
	
	/**
	 * 获取所有监控项目的列表.
	 *
	 * @param   int  task_id
	 * @return  array
	 */
	abstract public function get_list();
	
	/**
	 * 获得单个监控项目的报告列表
	 *
	 * @param   int  task_id
	 * @return  array
	 */
	abstract public function report($task_id);
	
	/**
	 * 获得单个监控项目某日的报告
	 *
	 * @param   int  task_id
	 * @param   string   日期(格式：20100225)
	 * @return  array
	 */
	abstract public function report_date($task_id, $date);
}