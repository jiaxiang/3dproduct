<?php defined('SYSPATH') OR die('No direct access allowed.');

class Myrole_Core {
	private $data;
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

		$role = ORM::factory('role',$id)->as_array();
		$this->data = $role;
	}

	/**
	 * get role data
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	private function _data($where=NULL,$orderby=NULL,$limit=0,$offset=1000)
	{
		$list = array();

		$role = ORM::factory('role');
		if(!empty($where))
		{
			$role->where($where);
		}

		if(!empty($orderby))
		{
			$role->orderby($orderby);
		}

		$orm_list = $role->find_all($limit,$offset);

		foreach($orm_list as $item)
		{
            $merge_arr = array('count'=>count($item->managers));
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
	function count($where=NULL)
	{
		$role = ORM::factory('role');

		if(!empty($where))
		{
			$role->where($where);
		}

		if(!empty($in))
		{
			$role->in($in);
		}

		$count = $role->count_all();
		return $count;
	}

	/**
	 * get role list
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @param Int $in
	 * @return Array
	 */
	function roles($where=NULL,$orderby=NULL,$limit = 100,$offset=0)
	{
		$list = $this->_data($where,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get site data
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
	 * get role by name
	 *
	 * @param String $name
	 * @return Array
	 */
	public function get_by_name($name=NULL)
	{
		$role = ORM::factory('role')->where(array('name'=>$name))->find();
		$this->data = $role;
		return $role->as_array();
	}

    /**
     * get role childrens
     *
     * @param <Int> $id ID
     * @return Array
     */
    public function childrens($id = 0,$relative = TRUE)
    {
        $result = array();

        $list = ORM::factory('role')
            ->where('parent_id',$id)
            ->where('active',1)
            ->find_all();
		if($relative)
		{
			$data = array();
			foreach($list as $item)
			{
				$data[] = $item->as_array();
			}
			return $data;
		}
        
        foreach($list as $item)
        {
			$result[] = $item->as_array();
            $temp = $this->subs($item->id);
            if(is_array($temp) && count($temp))
            {
                $result = array_merge($result,$temp);
            }			
        }
        return $result;
    }
	/**
	 * add a item
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		//LEVEL
		$level_depth = 1;
		$parent_id = $data['parent_id'];
		if($parent_id > 0)
		{
			$parent_role = ORM::factory('role',$parent_id);
			if($parent_role->loaded)
			{
				$level_depth = ++$parent_role->level_depth;
			}
		}
		//ADD
		$role = ORM::factory('role');
		$role->level_depth = $level_depth;
		$errors = '';
		if($role->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $role->as_array();
			return TRUE;
		}
		else
	   	{
			return FALSE;
		}
	}

	/**
	 * set role rule actions
	 *
	 * @param <Int> $id role id
	 * @param <Array> $data role rule array
	 * @return Boolean
	 */
	public function set_actions($id,$data)
	{		
		//zhu modify
		//$permissions = '';
		$resource = $data['resource'];
		//查询所有的权限
		//$actions = ORM::factory('action')->IN('id', $resource)->find_all();
		//foreach($actions as $item){
		//	$permissions .= $item->resource.",";
		//}
		//$permissions = $permissions?rtrim($permissions, ","):$permissions;
		$role = ORM::factory('role', $id);
		$role->permissions = implode(",", $resource);
		$role->save();
		/*
		$role_actions = ORM::factory('role',$id)->actions;
		foreach($role_actions as $item){
			if(in_array($item->id,$resource)){
				$key = array_search($item->id,$resource);
				unset($resource[$key]);
			}else{
				$role = ORM::factory('role',$id);
				$role->remove(ORM::factory('action',$item->id));
				$role->save();
			}
		}

		if(count($resource) > 0){
			foreach($resource as $key=>$value){
				if($value){
					$role = ORM::factory('role',$id);
					$role->add(ORM::factory('action',$value));
					$role->save();
				}
			}
		}*/
		return true;
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
		$role = ORM::factory('role',$id);
		if(!$role->loaded)
		{
			return FALSE;
		}
		//LEVEL
		$level_depth = 1;
		$parent_id = $data['parent_id'];
		if($parent_id > 0)
		{
			$parent_role = ORM::factory('role',$parent_id);
			if($parent_role->loaded)
			{
				$level_depth = ++$parent_role->level_depth;
			}
		}
		//TODO
		$role->level_depth = $level_depth;
		$errors = '';
		if($role->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $role->as_array();
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
     * get role actions
	 *
     * @param Int $id
     * @return Boolean
     */
	function actions() 
	{
		$list = array();

        $actions = ORM::factory('role',$this->data['id'])->actions;
		foreach($actions as $item)
		{
			$list[] = $item->as_array();
		}
        return $list;
    }

	/**
	 * delete role by id
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function _delete($id)
	{
		$id = intval($id);
		$role = ORM::factory('role',$id);
		if($role->loaded)
		{
			if(count($role->managers) > 0)
			{
				$this->error[] = '有帐号分配在该分组,移除后才可以删除.';
				return false;
			}
			else
			{
				//清除用户组和站点的关联关系
				$actions = $role->actions;
				if(count($actions) > 0)
				{
					foreach($actions as $item)
					{
						$item->remove($role);
						$item->save();
					}
					$role->delete();
					return true;
				}
				else
				{
					$role->delete();
					return true;
				}
			}
		}
		else
		{
			$this->error[] = '用户组不存在';
			return false;
		}
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
