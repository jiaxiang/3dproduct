<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Crond_Driver
 * 
 * @package   
 * @author B2C_BASE
 * @copyright qinbin
 * @version 2010
 * @access public
 */
abstract class Crond_Driver {

	/**
	 * 邮件消息加入队列(即时邮件)
	 * 
	 * @return
	 */
	abstract protected function add_mail_task($to,$subject,$message,$from);

	/**
	 * 邮件消息加入队列(定时邮件)
	 * 
	 * @return
	 */
	abstract protected function add_mail_crond($to,$subject,$message,$from,$interval_time,$exec_time);

	/**
	 * url触发加入队列(即时触发)
	 * 
	 * @return
	 */
	abstract protected function add_url_task($url);

	/**
	 * url触发加入队列(定时触发)
	 * 
	 * @return
	 */
	abstract protected function add_url_crond($url,$interval_time,$exec_time);

}