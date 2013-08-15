<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mymail_category_Core extends My{
	protected $object_name = 'mail_category';
	protected $data = array();
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
	 * Construct load mail_category data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load mail_category data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$mail_category = ORM::factory('mail_category',$id);
		$this->data = $mail_category->as_array();
	}

	/**
	 * get mail_category data
	 *
	 * @param Array $query_struct
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @return Array
	 */
	private function _data($query_struct=array(),$orderby=NULL,$limit=1000,$offset=0)
	{
		$list = array();
		$where = array();
		$like = array();
		$in = array();

		$mail_category = orm::factory('mail_category');
		//where
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['where']))
			{
				foreach($query_struct['where'] as $key=>$value)
				{
					$where[$key] = $value;
				}
			}
		}
		//like
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['like']))
			{
				foreach($query_struct['like'] as $key=>$value)
				{
					$like[$key] = $value;
				}
			}
		}
		//in
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['in']))
			{
				foreach($query_struct['in'] as $key=>$value)
				{
					$in[$key] = $value;
				}
			}
		}
		//WHERE
		if(count($where) > 0)
		{
			$mail_category->where($where);
		}
		//LIKE
		if(count($like) > 0)
		{
			$mail_category->like($like);
		}
		//IN
		if(count($in) > 0)
		{
			$mail_category->in($in);
		}

		if(!empty($orderby))
		{
			$mail_category->orderby($orderby);
		}

		$orm_list = $mail_category->find_all($limit,$offset);

		foreach($orm_list as $key=>$item)
		{
            $list[$key] = $item->as_array();
		}

		return $list;
	}

	/**
	 * get the total number
	 *
	 * @param Array $query_struct
	 * @return Int
	 */
	function count($query_struct = array())
	{
		$where = array();
		$like = array();
		$in = array();

		$mail_category = orm::factory('mail_category');
		//where
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['where']))
			{
				foreach($query_struct['where'] as $key=>$value)
				{
					$where[$key] = $value;
				}
			}
		}
		//like
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['like']))
			{
				foreach($query_struct['like'] as $key=>$value)
				{
					$like[$key] = $value;
				}
			}
		}
		//in
		if(count($query_struct) > 0)
		{
			if(isset($query_struct['in']))
			{
				foreach($query_struct['in'] as $key=>$value)
				{
					$in[$key] = $value;
				}
			}
		}
		//WHERE
		if(count($where) > 0)
		{
			$mail_category->where($where);
		}
		//LIKE
		if(count($like) > 0)
		{
			$mail_category->like($like);
		}
		//IN
		if(count($in) > 0)
		{
			$mail_category->in($in);
		}

		$count = $mail_category->count_all();
		return $count;
	}

	/**
	 * list mail_category
	 *
	 * @param Array $query_struct
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function mail_categories($query_struct = array(),$orderby=NULL,$limit=100,$offset=0)
	{
		$list = array();

		$list = $this->_data($query_struct,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get mail_category data
	 *
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
	 * get by flag
	 *
	 * @param <String> $flag
	 * @return <Array>
	 */
	public function get_by_flag($flag = NULL)
	{
		$where = array();
		$where['flag'] = $flag;

		$mail_category = ORM::factory('mail_category')->where($where)->find();
		return $mail_category->as_array();
	}

	/**
	 * add a mail_category item
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//TODO add
		$mail_category = ORM::factory('mail_category');
		$mail_category->add_time = date("Y-m-d H:i:s");
		$errors = '';
		if($mail_category->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $mail_category->as_array();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * edit mail_category
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function _edit($id,$data)
	{
		$id = intval($id);
		//TODO EDIT
		$mail_category = ORM::factory('mail_category',$id);
		if(!$mail_category->loaded)
		{
			return FALSE;
		}
		//TODO
		$errors = '';
		if($mail_category->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $mail_category->as_array();
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
	 * delete a mail category by id
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function _delete($id)
	{
		$id = intval($id);
		$mail_category = ORM::factory('mail_category',$id);
		if(!$mail_category->loaded)
		{
			return false;
		}
		$mail_category_mail_templates = $mail_category->mail_templates;
		if(count($mail_category_mail_templates) > 0)
		{
			$this->error[] = "本分类中存在邮件模板,不能删除.";
			return false;
		}

		$mail_category_mails = $mail_category->mails;
		if(count($mail_category_mails) > 0)
		{
			$this->error[] = "本分类中存在站点邮件模板,不能删除.";
			return false;
		}

		$mail_category->delete();
		return true;
	}

	/**
	 * delete a mail category
	 *
	 * @return boolean
	 */
	public function delete()
	{
		$id = $this->data['id'];
		return $this->_delete($id);
	}

    /**
     * get api error
     *
     * @return Array
     */
    public function error()
    {
        $result = '';
        if(count($this->error))
        {
            $result     = '<br />';
            foreach($this->error as $key=>$value)
            {
                $result .= ($key+1).' . '.$value.'<br />';
            }
        }
        return $result;
    }
}
