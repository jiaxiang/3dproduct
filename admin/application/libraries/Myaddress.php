<?php defined('SYSPATH') OR die('No direct access allowed.');

class Myaddress_Core extends My{
	//表名
	protected $object_name = 'address';
	//数据成员记录单体数据
	protected $data = array();
	//记录Service中的错误信息
	protected $error = array();

	private static $instances;
	public static function & instance($id = 0)
	{
		if (!isset(self::$instances[$id]))
		{
			$class = __CLASS__;
			self::$instances[$id] = new $class($id);
		}
		return self::$instances[$id];
	}

	/**
	 * address ids
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function address_ids($where=NULL,$in=NULL,$orderby=NULL,$limit=100,$offset=0)
	{
		$list = $this->_data($where,$in,$orderby,$limit,$offset);
		$res  = array();
		foreach($list as $key=>$rs){
			if(isset($rs['id']))
			{
				$res[] = $rs['id'];
			}
		}
		return $res;
	}
}
