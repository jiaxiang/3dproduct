<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mymail_template_Core extends My{
	protected $object_name = 'mail_template';
	protected $data = array();

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
	 * Construct load mail data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load mail_template data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$mail_template = ORM::factory('mail_template',$id)->as_array();
		$this->data = $mail_template;
	}

	/**
	 * get mail_template data
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

		$mail_template = ORM::factory('mail_template');
		//WHERE
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
		//LIKE
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
		//IN
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
			$mail_template->where($where);
		}
		//LIKE
		if(count($like) > 0)
		{
			$mail_template->like($like);
		}
		//IN
		if(count($in) > 0)
		{
			$mail_template->in($in);
		}

		if(!empty($orderby))
		{
			$mail_template->orderby($orderby);
		}

		$orm_list = $mail_template->find_all($limit,$offset);

		foreach($orm_list as $item)
		{
			$merge_arr = array(
				'mail_category_name' => $item->mail_category->name,
			);
            $list[] = array_merge($item->as_array(),$merge_arr);
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
		$mail_template = ORM::factory('mail_template');

		$where = array();
		$like = array();
		$in = array();

		$mail_tempate = ORM::factory('mail_template');
		//WHERE
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
		//LIKE
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
		//IN
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
			$mail_template->where($where);
		}
		//LIKE
		if(count($like) > 0)
		{
			$mail_template->like($like);
		}
		//IN
		if(count($in) > 0)
		{
			$mail_template->in($in);
		}

		$count = $mail_template->count_all();
		return $count;
	}

	/**
	 * list mail_template
	 *
	 * @param Array $query_struct
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	public function mail_templates($query_struct = array(),$orderby=NULL,$limit=1000,$offset=0)
	{
		$list = array();

		$list = $this->_data($query_struct,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get mail_templat data
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
	 * get default by category_id
	 *
	 * @param int $category_id
	 * @return array
	 */
	public function get_default_by_category($category_id)
	{
		$where = array();
		$where['mail_category_id'] = $category_id;
		$mail_template = ORM::factory('mail_template')->where($where)->find();
		return $mail_template->as_array();
	}

	/**
	 * add a mail_template
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//ADD
		$mail_template = ORM::factory('mail_template');
		$site->add_time = date("Y-m-d H:i:s");
		$errors = '';
		if($mail_template->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $mail_template->as_array();
			return TRUE;
		}
		else 
		{
			return FALSE;
		}
	}

	/**
	 * edit a mail_template item
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function _edit($id,$data)
	{
		$id = intval($id);
		//TODO EDIT
		$mail_template = ORM::factory('mail_template',$id);
		if(!$mail_template->loaded)
		{
			return FALSE;
		}
		//TODO
		$errors = '';
		if($mail_template->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $mail_template->as_array();
			return TRUE;
		}
		else
		{
			var_dump($errors);exit;
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
	 * get mail_template's category
	 *
	 * @return Array
	 */
	public function mail_category(){
		$mail_category = ORM::factory('mail_template',$this->data['id'])->mail_category;
		return $mail_category->as_array();
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
		$mail_template = ORM::factory('mail_template',$id);
		if(!$mail_template->loaded)
		{
			return false;
		}

		$mail_template->delete();
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
