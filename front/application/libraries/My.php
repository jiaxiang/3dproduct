<?php
defined('SYSPATH') or die('No direct access allowed.');

class My_Core
{
	//表名
	protected $object_name = NULL;
	//数据成员记录单体数据
	protected $data = array();
	//记录Service中的错误信息
	protected $error = array();

	/**
	 * Construct load data
	 *
	 * @param Int $id
	 */
	protected function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$this->data = ORM::factory($this->object_name,$id)->as_array();
	}

	/**
	 * get the total number
	 *
	 * @param Array $query_struct
	 * @return Int
	 */
	public function count($query_struct = array())
	{
		$orm_instance = ORM::factory($this->object_name);
		// 处理输入条件
		$where = array();
		$in = array();
		if(isset($query_struct['where'])&&is_array($query_struct['where']))
		{
			foreach($query_struct['where'] as $key=>$condition)
			{
				if(is_array($condition))
				{
					$in[$key] = $condition;
				}
				else
				{
					$where[$key] = $condition;
				}
			}
		}
		// 处理 where 模块
		if(!empty($where))
		{
			$orm_instance->where($where);
		}
		if(!empty($in))
		{
			foreach($in as $in_key=>$in_val)
			{
				$orm_instance->in($in_key,$in_val);
			}
		}
		// 处理 like 模块
		if(isset($query_struct['like'])&&is_array($query_struct['like'])&&!count($query_struct['like']))
		{
			$orm_instance->like($query_struct['like']);
		}
		return $orm_instance->count_all();
	}

	/**
	 * 多条数据结构体
	 *
	 * @param Array $query_struct
	 * @return Array
	 */
	public function lists($query_struct = array())
	{
		$list = array();
		$orm_instance = ORM::factory($this->object_name);
		// 处理输入条件
		$where = array();
		$in = array();
		if(isset($query_struct['where'])&&is_array($query_struct['where']))
		{
			foreach($query_struct['where'] as $key=>$condition)
			{
				if(is_array($condition))
				{
					$in[$key] = $condition;
				}
				else
				{
					$where[$key] = $condition;
				}
			}
		}
		// 处理 where 模块
		if(!empty($where))
		{
			$orm_instance->where($where);
		}
		//处理IN条件
		if(!empty($in))
		{
			foreach($in as $in_key=>$in_val)
			{
				$orm_instance->in($in_key,$in_val);
			}
		}
		//处理传入in条件
		if(isset($query_struct['in'])&&is_array($query_struct['in']))
		{
			foreach($query_struct['in'] as $key=>$value)
			{
				$orm_instance->in($key,$value);
			}
		}
		// 处理 like 模块
		if(isset($query_struct['like'])&&is_array($query_struct['like'])&&count($query_struct['like']))
		{
			$orm_instance->like($query_struct['like']);
		}
		// 处理 orlike 模块
		if(isset($query_struct['orlike'])&&is_array($query_struct['orlike'])&&count($query_struct['orlike']))
		{
			$orm_instance->orlike($query_struct['orlike']);
		}		
		// 处理 orderby 模块
		if(isset($query_struct['orderby'])&&is_array($query_struct['orderby'])&&count($query_struct['orderby']))
		{
			$orm_instance->orderby($query_struct['orderby']);
		}
		//处理limit条件，无条件最多查询1000条数据
		$limit = isset($query_struct['limit']['per_page']) ? $query_struct['limit']['per_page'] : 1000;
		$offset = isset($query_struct['limit']['offset']) ? $query_struct['limit']['offset'] : 0;
		$orm_list = $orm_instance->find_all($limit,$offset);
		//得到返回结构体
		foreach($orm_list as $item)
		{
			$list[] = $item->as_array();
		}
		return $list;
	}

	/**
	 * select list
	 *
	 * @param Array $query_struct
	 * @param String $key_select
	 * @param String $val_select
	 * @return Array
	 */
	public function select_list($query_struct,$key_select,$val_select)
	{
		$list = array();
		$orm_instance = ORM::factory($this->object_name);
		// 处理输入条件
		$where = array();
		$in = array();
		if(isset($query_struct['where'])&&is_array($query_struct['where']))
		{
			foreach($query_struct['where'] as $key=>$condition)
			{
				if(is_array($condition))
				{
					$in[$key] = $condition;
				}
				else
				{
					$where[$key] = $condition;
				}
			}
		}
		// 处理 where 模块
		if(!empty($where))
		{
			$orm_instance->where($where);
		}
		if(!empty($in))
		{
			foreach($in as $in_key=>$in_val)
			{
				$orm_instance->in($in_key,$in_val);
			}
		}
		
		// 处理 like 模块
		if(isset($query_struct['like'])&&is_array($query_struct['like'])&&!empty($query_struct['like']))
		{
			$orm_instance->like($query_struct['like']);
		}
		// 处理 orderby 模块
		if(isset($query_struct['orderby'])&&is_array($query_struct['orderby'])&&!empty($query_struct['orderby']))
		{
			$orm_instance->orderby($query_struct['orderby']);
		}
		// 处理 select_list 模块
		return $orm_instance->select_list($key_select,$val_select);
	}

	/**
	 * get data
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
			return isset($this->data[$key]) ? $this->data[$key] : '';
		}
	}

	/**
	 * add a site
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{	
		$orm_instance = ORM::factory($this->object_name);
		$errors = '';
		if($orm_instance->validate($data,TRUE,$errors))
		{
			$this->data = $orm_instance->as_array();
			return $orm_instance->id;
		}
		else
		{
			$this->error[] = $errors;
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
		$id = isset($data['id'])&&intval($data['id']) ? $data['id'] : $this->data['id'];
		
		$orm_instance = ORM::factory($this->object_name,$id);
		if(!$orm_instance->loaded)
		{
			$this->error[] = 'Data load failed , object : '.$this->object_name.' , id : '.$id;
			return FALSE;
		}
		
		$errors = '';
		if($orm_instance->validate($data,TRUE,$errors))
		{
			$this->data = $orm_instance->as_array();
			return TRUE;
		}
		else
		{
			$this->error[] = $errors;
			return FALSE;
		}
	}

	/**
	 * delete a item
	 *
	 * @param Int $id
	 * @return Boolean
	 */
	public function delete($id = 0)
	{
		$id = $id ? $id : $this->data['id'];
		
		$orm_instance = ORM::factory($this->object_name,$id);
		
		if(!$orm_instance->loaded)
		{
			$this->error[] = 'Data load failed , object : '.$this->object_name.' , id : '.$id;
			return FALSE;
		}
		else
		{
			$orm_instance->delete();
			return TRUE;
		}
	}

	/**
	 * 删除站点所有的信息
	 * 
	 * @param int $site_id
	 * @return boolean
	 */
	public function delete_by_site($site_id)
	{
		//删除物流信息
		$obj = ORM::factory($this->object_name);
		$obj->delete_all();
		
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
		if(count($this->error))
		{
			$result = '<br />';
			foreach($this->error as $key=>$value)
			{
				if(is_array($value))
				{
					
					foreach($value as $key1=>$value1)
					{
						$result .= $key1.' . '.$value1.'<br />';
					}
				}
				else
				{
					$result .= ($key+1).' . '.$value.'<br />';
				}
			}
		}
		return $result;
	}
}
