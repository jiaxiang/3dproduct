<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mysite_mail_Core {
	private $data = array();

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
	 * Construct load site_mail data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load site_mail data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$site_mail = ORM::factory('site_mail',$id)->as_array();
		$this->data = $site_mail;
	}

	/**
	 * get site data
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	private function _data($where=NULL,$in=NULL,$orderby=NULL,$limit=0,$offset=1000)
	{
		$list = array();

		$site_mail = ORM::factory('site_mail');
		if(!empty($where))
		{
			$site_mail->where($where);
		}

		if(!empty($in))
		{
			$site_mail->in($in);
		}

		if(!empty($orderby))
		{
			$site_mail->orderby($orderby);
		}

		$orm_list = $site_mail->find_all($limit,$offset);

		foreach($orm_list as $item)
		{
			$list[] = $item->as_array();
		}

		return $list;
	}

	/**
	 * get the total number
	 *
	 * @param Array $where
	 * @param Array $in
	 * @return Int
	 */
	function count($where=NULL,$in=NULL) {
		$site_mail = ORM::factory('site_mail');

		if(!empty($where))
		{
			$site_mail->where($where);
		}

		if(!empty($in))
		{
			$site_mail->in($in);
		}

		$count = $site_mail->count_all();
		return $count;
	}

	/**
	 * list site
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function sites($where=NULL,$in=NULL,$orderby=NULL,$limit=100,$offset=0)
	{
		$list = $this->_data($where,$in,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get site_mail data
	 *
	 * @return Array
	 */
	public function get()
	{
		return $this->data;
	}

	/**
	 * add a site_mail
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//ADD
		$site_mail = ORM::factory('site_mail');
		$errors = '';
		if($site_mail->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $site_mail->as_array();
			return TRUE;
		}
		else 
		{
			return FALSE;
		}
	}

	/**
	 * edit a site_mail item
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function edit($data,$id)
	{
		$id = intval($id);

		//EDIT
		$site_mail = ORM::factory('site_mail',$id);
		if(!$site_mail->loaded)
		{
			return FALSE;
		}
		//TODO
		$errors = '';
		if($site_mail->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $site_mail->as_array();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
