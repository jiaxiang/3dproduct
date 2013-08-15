<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mysite_detail_Core {
	private $data = array();
	private $error = array();

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
	 * Construct load site data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load site data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$site = ORM::factory('site',$id)->as_array();
		$this->data = $site;
	}
	
	/*
	 * update item by id
	 *
	 * @param Int $id site id
	 * @param Array $data update data array
	 * @return Boolean
	 */
	public function _update($id,$data=array())
	{
		$site_detail = ORM::factory('site_detail',$id);
		if(count($data) > 0)
		{
			foreach($data as $key=>$value)
			{
				$site_detail->$key = $value;
			}
		}

		$site_detail->save();
		$this->data = $site_detail->as_array();

		if($site_detail->saved)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * update item
	 *
	 * @param Array $data update data array
	 * @return Boolean
	 */
	public function update($data = array())
	{
		$id = intval($this->data['id']);

		return $this->_update($id,$data);
	}

	/**
	 * update item by id
	 *
	 * @param Array $id
	 * @param Array $data update data array
	 * @return Boolean
	 */
	public function update_by_id($id,$data=array())
	{
		return $this->_update($id,$data);
	}
	
	/**
	 * update site_detail info by site id
	 * 
	 * @param int $site_id
	 * @param array $data
	 * @return boolean
	 */
	public function update_by_site_id($site_id,$data)
	{
		$where = array();
		$where['site_id'] = $site_id;
		
		$id = ORM::factory('site_detail')->where($where)->find()->id;
		if(!$id)
		{
			$site_detail = ORM::factory('site_detail');
			$site_detail->site_id = $site_id;
			$site_detail->save();
			$id = $site_detail->id;
		}
		return $this->_update($id,$data);
	}
    
	public function get()
	{
        return ORM::factory('site_detail')->find()->as_array();
		//return ORM::factory('site_detail')->find_all()->as_array();
	}
}
