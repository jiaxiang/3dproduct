<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mylibrary_mail_Core {
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
	 * Construct load library_mail data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}
	
	/**
	 * load libaray_mail data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$library_mail = ORM::factory('library_mail',$id)->as_array();
		$this->data = $site;
	}

	/**
	 * get libaray_mail data
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	private function _data($where,$orderby,$limit,$offset,$in,$notin_flag)
	{
		$list = array();

		$library_mail = ORM::factory('library_mail');
		$orm_list = $library_mail
			->where($where)
			->in('id',$in,$notin_flag)
			->orderby($orderby)
			->find_all($limit,$offset);

		foreach($orm_list as $item)
		{
			$merge_arr = array('library_mail_category_name'=>$item->libaray_category->name);
			$list[] = array_merge($item->as_array(),$merge_arr);
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
	function count($where=NULL,$in=NULL)
	{
		if(empty($in))
		{
			$notin_flag = FALSE;
			$in = array(0);
		}
		else
		{
			$notin_flag = TRUE;
		}
		if(empty($where))
		{
			$where = array('1=1');
		}

		$library_mail = ORM::factory('library_mail');
		$count = $library_mail
			->where($where)
			->in('id',$in,$notin_flag)
			->count_all();
		return $count;
	}

	/**
	 * list library_mail
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function library_mails($where=NULL,$orderby=NULL,$limit=100,$offset=0,$in=NULL)
	{
		if(empty($in))
		{
			$notin_flag = FALSE;
			$in = array(0);
		}
		else
		{
			$notin_flag = TRUE;
		}

		if(empty($where))
		{
			$where = array('1=1');
		}

		if(empty($orderby))
		{
			$orderby = array('id'=>'DESC');
		}
		$list = $this->_data($where,$orderby,$limit,$offset,$in,$notin_flag);
		return $list;
	}

	/**
	 * get library_mail data
	 *
	 * @return Array
	 */
	public function get()
	{
		return $this->data;
	}

	/**
	 * add a library_mail
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//ADD
		$library_mail = ORM::factory('library_mail');
		$errors = '';
		if($library_mail->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $library_mail->as_array();
			return TRUE;
		}
		else 
		{
			return FALSE;
		}
	}

	/**
	 * edit a library_mail item
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function edit($data,$id)
	{
		$id = intval($id);

		//EDIT
		$library_mail = ORM::factory('library_mail',$id);
		if(!$library_mail->loaded)
		{
			return FALSE;
		}
		//TODO
		$errors = '';
		if($library_mail->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $library_mail->as_array();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * get library_mail's category
	 *
	 * @return Array
	 */
	public function library_mail_category(){
		$library_mail_category = ORM::factory('library_mail',$this->data['id'])->library_mail_category;
		return $library_mail_category->as_array();
	}
}
