<?php defined('SYSPATH') OR die('No direct access allowed.');
abstract class DefaultDataTransport_Service {
	/**
	 * 当前操作的记录ID
	 * 
	 * @var $current_id int
	 */
	protected $current_id = 0;
	
	/**
	 * 获取下一条记录的ID
	 * 
	 * @return int,bool  当不具备下一条记录时，返回 false;
	 */
	abstract public function next_id();
	
	/**
	 * 通过ID获取数组
	 * 
	 * @param int $id
	 * @return array
	 */
	abstract public function get($id);
}
