<?php
defined('SYSPATH') or die('No direct access allowed.');

class Mymail_Core extends My
{
	protected $object_name = 'mail';
	protected $data = array();
	protected $errors = array();
	
	private static $instances;

	public static function &instance($id = 0)
	{
		if(!isset(self::$instances[$id]))
		{
			$class = __CLASS__;
			self::$instances[$id] = new $class($id);
		}
		return self::$instances[$id];
	}

	/**
	 * Construct load mail data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load mail data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);
		
		$mail = ORM::factory('mail',$id)->as_array();
		$this->data = $mail;
	}

	/**
	 * get mail data
	 *
	 * @param Array $site_id
	 * @param Array $query_struct
	 * @param Array $orderby
	 * @return Array
	 */
	private function _data($mail_category_id = NULL)
	{
		$list = array();
		$where = array();
		
		$mail = ORM::factory('mail');
		
		if(!empty($mail_category_id))
		{
			$mail = $mail->where('mail_category_id',$mail_category_id);
		}
		
		$orm_list = $mail->find_all();

		foreach($orm_list as $item)
		{
			$merge_arr = array('mail_category_name'=>$item->mail_category->name);
			$list[] = array_merge($item->as_array(),$merge_arr);
		}
		return $list;
	}

	/**
	 * list mail
	 *
	 * @param Array $query_struct
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function mails($mail_category_id = NULL)
	{
		$list = array();
		
		$list = $this->_data($mail_category_id);
		return $list;
	}

	/**
	 * get mail data
	 *
	 * @param <String> $key column
	 * @return Array
	 */
	public function get($key = NULL)
	{
		if(empty($key))
		{
			return $this->data;
		}
		else
		{
			if(isset($this->data[$key]))
			{
				return $this->data[$key];
			}
			else
			{
				return NULL;
			}
		}
	}

	/**
	 * get mail by mail type
	 *
	 * @param <String> $type mail type
	 * @param <Int> $site_id site id
	 * 
	 * @return Array
	 */
	public function get_by_type($category_id = 0)
	{
		$where = array();
		$where['mail_category_id'] = $category_id;
		
		$mail = ORM::factory('mail')->where($where)->find();
		return $mail->as_array();
	}

	/**
	 * edit a item
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	private function _edit($id,$data)
	{
		$id = intval($id);
		//EDIT
		$mail = ORM::factory('mail',$id);
		if(!$mail->loaded)
		{
			return FALSE;
		}
		//TODO
		$errors = '';
		if($mail->validate($data,TRUE,$errors))
		{
			$this->data = $mail->as_array();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * edit a item
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function edit($data)
	{
		$id = $this->data['id'];
		return $this->_edit($id,$data);
	}

	/**
	 * edit item by id
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function edit_by_id($id,$data)
	{
		$id = intval($id);
		return $this->_edit($id,$data);
	}

	/**
	 * set site mail template
	 *
	 * @param <Array> $data
	 * @return Boolean
	 */
	public function set($data)
	{
		$where = array();		
		$where['mail_category_id'] = $data['mail_category_id'];

		//ADD
		$mail = ORM::factory('mail')->where($where)->find();
		if($mail->loaded)
		{
			$mail->update_time = date("Y-m-d H:i:s");
		}
		else
		{
			$mail->add_time = date("Y-m-d H:i:s");
		}
		
		$errors = '';
		if($mail->validate($data,TRUE,$errors))
		{
			$this->data = $mail->as_array();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * init site_mail
	 */
	public function init($type = 0)
	{
		$query_struct = array();
		$query_struct['where']['type'] = $type;
		
		$mail_categories = Mymail_category::instance()->mail_categories();
		foreach($mail_categories as $key=>$value)
		{
			$mail_template = Mymail_template::get_default_by_category($value['id']);
			$data = $mail_template;
			$this->set($data);
		}
	}

	/**
	 * delete site mail
	 */
	public function delete()
	{
		$id = $this->data['id'];
		
		$mail = ORM::factory('mail',$id);
		if($mail->loaded)
		{
			$mail->delete();
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * 删除站点对应的邮件模板
	 * 
	 * @param int $site_id
	 * @return boolean
	 */
	public function delete_by_site($site_id)
	{
		//删除站点邮件模板
		$mail = ORM::factory('mail')->where('site_id',$site_id);
		$mail->delete_all();
		
		return true;
	}

	/**
	 * get api error
	 *
	 * @return Array
	 */
	public function error()
	{
		$result = '';
		if(count($this->errors))
		{
			$result = '<br />';
			foreach($this->errors as $key=>$value)
			{
				$result .= ($key+1).' . '.$value.'<br />';
			}
		}
		return $result;
	}
}