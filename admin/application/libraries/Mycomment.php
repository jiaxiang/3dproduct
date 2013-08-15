<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mycomment_Core {
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
	 * Construct load comment data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}
	/**
	 * load comment data
	 *
	 * @param Int $id
	 */
	public function _load($id)
	{
		$id = intval($id);

		$comment = ORM::factory('product_comment',$id)->as_array();
		$this->data = $comment;
	}
	/**
	 * comment list
	 *
	 * @param array $where
	 * @param array $orderby
	 * @param int $limit
	 * @param int $offset
	 * @param int $in
	 * @return array>
	 */
	public function comments($where=NULL,$orderby=NULL,$limit = 100,$offset=0,$in=NULL)
	{
		$list = array();
		if(empty($in))
		{
			$notin_flag = false;
			$in = array(0);
		}else{
			$notin_flag = true;
		}

		if(empty($where))
		{
			$where = array('1=1');
		}

		if(empty($orderby))
		{
			$orderby = array('id'=>'DESC');
		}

		$comments = ORM::factory('comment')
			->where($where)
			->in('id',$in,$notin_flag)
			->orderby($orderby)
			->find_all($limit,$offset);

		foreach($comments as $item)
		{
			$list[] = $item->as_array();
		}

		return $list;
	}
	/**
	 * get the total number
	 *
	 * @param array $where
	 * @param array $in
	 * @return int
	 */
	function count($where=NULL,$in=NULL)
	{
		if(empty($in))
		{
			$notin_flag = false;
			$in = array(0);
		}else{
			$notin_flag = true;
		}
		if(empty($where))
		{
			$where = array('1=1');
		}	
		$count = ORM::factory('comment')
			->where($where)
			->in('id',$in,$notin_flag)
			->count_all();
		return $count;
	}
	/**
	 * get comment data
	 *
	 * @return Array
	 */
	public function get()
	{
		return $this->data;
	}
	/**
	 * add a comment 
	 *
	 * @param Array $data
	 * @return Boolean
	 */
	public function add($data)
	{
		$site_id 			= $data['site_id'];
		$name 				= $data['name'];

		$comment = $this->get_by_name($site_id,$name);
		if($comment['id'])
		{
			return false;
		}
		//ADD
		$comment = ORM::factory('comment');
		$errors = '';
		if($comment->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $comment->as_array();
			return $this->data['id'];
		}else {
			return FALSE;
		}
	}
	/**
	 * get comment by name
	 *
	 * @param Int $site_id
	 * @param Int $comment_group_id
	 * @param String $name
	 * @return Array comment
	 */
	public function get_by_name($site_id = 0,$name = NULL)
	{
		$this->data = ORM::factory('comment')
			->where(array('name'=>$name,'site_id'=>$site_id))
			->find()
			->as_array();
		return $this->data;
	}
	/**
	 * edit a comment
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function edit($data)
	{
		$comment = $this->get();
		if(!$comment['id'])
		{
			return false;
		}
		//EDIT
		$comment = ORM::factory('comment',$comment['id']);
		$errors = '';
		if($comment->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $comment->as_array();
			return $this->data['id'];
		}
		else 
		{
			return FALSE;
		}
	}
	/**
	 * delete a comment
	 *
	 * @return Boolean
	 */
	public function delete()
	{
		$comment = $this->get();
		if(!$comment['id'])
		{
			return FALSE;
		}
		ORM::factory('comment',$this->data['id'])->delete();
		return TRUE;
	}
}
