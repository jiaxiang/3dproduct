<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mysite_type_Core {
	private $data = array();
	private $errors = array();

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
	 * Construct load site_group data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load site_group data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$site_type = ORM::factory('site_type',$id)->as_array();
		$this->data = $site_type;
	}

	/**
	 * get site_group
	 *
	 * @param <Array> $where
	 * @return Array
	 */
	public function get_site_types($where = array(),$orderby = array())
	{
		$list = array();

		$site_type = ORM::factory('site_type');

		if(count($where))
		{
			$site_type->where($where);
		}

		if(count($orderby))
		{
			$site_type->orderby($orderby);
		}

		$orm_list = $site_type->find_all();

		foreach($orm_list as $item)
		{
			$list[] = $item->as_array();
		}
		return $list;
	}

	/**
	 * 遍历操作
	 *
	 * @param <Int> $id ID
	 * @return Array
	 */
	public function site_types($id = 0)
	{
		$result = array();

		$list = ORM::factory('site_type')
			->where('parent_id',$id)
			->orderby(array('id'=>'DESC'))
			->find_all();

		foreach($list as $item)
		{
			$result[] = $item->as_array();
			$temp = $this->site_types($item->id);
			if(is_array($temp) && count($temp))
			{
				$result = array_merge($result,$temp);
			}			
		}
		return $result;
	}

	/**
	 * get site_group data
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
	 * get by name
	 *
	 * @param String url
	 * @return Array
	 */
	public function get_by_name($name){
		$where = array();
		$where['name'] = $name;

		$site_type = ORM::factory('site_type')->where($where)->find()->as_array();
		return $site_type;
	}

	/**
	 * add a item
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		$where = array();
		$level_depth = 1;

		$parent_id = $data['parent_id'];

		if($parent_id > 0)
		{
			$parent_site_type = ORM::factory('site_type',$parent_id);
			if($parent_site_type->loaded)
			{
				$level_depth = ++$parent_site_type->level_depth;
			}
		}
		
		$perv_site_type = ORM::factory('site_type')->where('parent_id',$parent_id)->orderby('order','DESC')->find();
		$order = $perv_site_type->order*2+1;

		//ADD
		$site_type = ORM::factory('site_type');
		$site_type->add_time = date('Y-m-d H:i:s');
		$site_type->level_depth = $level_depth;
		$site_type->order = $order;
		$errors = '';
		if($site_type->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $site_type->as_array();
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
	public function _edit($id,$data)
	{
		$id = intval($id);
		//EDIT
		$site_type = ORM::factory('site_type',$id);
		if(!$site_type->loaded)
		{
			return FALSE;
		}
		//TODO
		$errors = '';
		if($site_type->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $site_type->as_array();
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
	 * get parent item
	 *
	 * @param Int $parent_id
	 * @return Array
	 */
	function parent($parent_id = 0)
	{
		if($parent_id = 0)
		{
			$parent_id = $this->data['parent_id'];
		}
		$site_type = ORM::factory('site_type')->where(array('parent_id'=>$parent_id))->find()->as_array();
		return $site_type;
	}

	/**
	 * get site_group sites
	 */
	function sites()
	{
		$list = array();

		$sites = ORM::factory('site_type',$this->data['id'])->sites;
		foreach($sites as $item)
		{
			$list[] = $item->as_array();
		}
		return $list;
	}

	/**
	 * set order
	 */
	function set_order($id = 0,$type = "up")
	{
		if($type == "up")
		{
			return $this->set_order_up($id);
		}
		elseif($type == "down")
		{
			return $this->set_order_down($id);
		}
		else
		{
			$this->errors[] = "非法操作";
			return false;
		}
	}

	/**
	 * set order up
	 */
	function set_order_up($id)
	{
		$where = array();
		$orderby = array();

		$site_type = ORM::factory('site_type',$id);
		if($site_type->loaded)
		{
			$cur_order = $site_type->order;
			if($cur_order < 1)
			{
				$this->errors[] = "已经是最顶部";
				return false;
			}
			else
			{
				$where['parent_id'] = $site_type->parent_id;
				$where['order <'] = $cur_order;
				$orderby['order'] = "DESC";

				$perv_site_type = ORM::factory('site_type')->where($where)->orderby($orderby)->find();
				if($perv_site_type->id)
				{
					$perv_site_type_order = $perv_site_type->order;

					$site_type->order = $perv_site_type_order;
					$perv_site_type->order = $cur_order;

					$site_type->save();
					$perv_site_type->save();
					return true;
				}
				else
				{
					$this->errors[] = "已经是顶部";
					return false;
				}
			}
		}
		else
		{
			$this->errors[] = "记录未找到";
			return false;
		}
	}

	/**
	 * set order down
	 */
	function set_order_down($id)
	{
		$where = array();
		$orderby = array();

		$site_type = ORM::factory('site_type',$id);
		if($site_type->loaded)
		{
			$cur_order = $site_type->order;
			$where['parent_id'] = $site_type->parent_id;
			$where['order >'] = $cur_order;
			$orderby['order'] = "ASC";

			$perv_site_type = ORM::factory('site_type')->where($where)->orderby($orderby)->find();
			if($perv_site_type->id)
			{
				$perv_site_type_order = $perv_site_type->order;

				$site_type->order = $perv_site_type_order;
				$perv_site_group->order = $cur_order;

				$site_type->save();
				$perv_type_group->save();
				return true;
			}
			else
			{
				$this->errors[] = "已经是底部";
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * delete a item
	 *
	 * @param Int $id
	 * @return Boolean
	 */
	function _delete($id){
		$id = intval($id);
		$site_type = ORM::factory('site_type',$id);
		if(!$site_type->loaded)
		{
			$this->error[] = '管理员不存在';
			return false;
		}
		$where = array();
		$where['parent_id'] = $site_type->id;
		$sub_site_type = ORM::factory('site_type')->where($where)->find();
		if($sub_site_type->id)
		{
			$this->error[] = '存在子分类,不能删除.';
			return false;
		}

		$sites = $site_type->sites;
		foreach($sites as $item)
		{
			$item->remove($site_type);
			$item->save();
		}
		$site_type->delete();
		return true;
	}

	/**
	 * delete role
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
    public function errors()
    {
        $result = '';
        if(count($this->errors))
        {
            $result     = '<br />';
            foreach($this->errors as $key=>$value)
            {
                $result .= ($key+1).' . '.$value.'<br />';
            }
        }
        return $result;
    }
}
