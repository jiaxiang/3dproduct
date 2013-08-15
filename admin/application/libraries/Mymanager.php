<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mymanager_Core extends My{ 
	protected $object_name = 'manager';
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

		$manager = ORM::factory('manager',$id)->as_array();
		$this->data = $manager;
	}

	/**
	 * get data
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

		$manager = ORM::factory('manager');
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
			$manager->where($where);
		}
		//LIKE
		if(count($like) > 0)
		{
			$manager->like($like);
		}
		//IN
		if(count($in) > 0)
		{
			foreach($in as $key=>$value)
			{
				$manager->in($key,$value);
			}
		}

		if(!empty($orderby))
		{
			$manager->orderby($orderby);
		}

		$orm_list = $manager->find_all($limit,$offset);

		foreach($orm_list as $item)
		{
			$merge_arr = array(
				'role_name' => Myrole::instance($item->role_id)->get('name')
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
		$manager = ORM::factory('manager');

		$where = array();
		$like = array();
		$in = array();

		$manager = ORM::factory('manager');
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
			$manager->where($where);
		}
		//LIKE
		if(count($like) > 0)
		{
			$manager->like($like);
		}
		//IN
		if(count($in) > 0)
		{
			foreach($in as $key=>$value)
			{
				$manager->in($key,$value);
			}
		}

		$count = $manager->count_all();
		return $count;
	}

	/**
	 * manager list
	 *
	 * @param Array $where
	 * @param Array $orderby
	 * @param Int $limit
	 * @param Int $offset
	 * @return Array
	 */
	function managers($query_struct = array(),$orderby=NULL,$limit = 1000,$offset=0)
	{
		$list = array();

		$list = $this->_data($query_struct,$orderby,$limit,$offset);
		return $list;
	}

	/**
	 * get manager root account
	 *
	 * @param boolean $is_manager 是否跟踪到管理员级别
	 * @return $manager
	 */
	public function root($is_manager = false)
	{
		$id = intval($this->data['id']);

		$manager = ORM::factory('manager',$id);
		if($manager->loaded)
		{
			$parent_manager = ORM::factory('manager',$manager->parent_id);
			while($parent_manager->parent_id > 0 && $parent_manager->type <> 1)
			{
				$manager = ORM::factory('manager',$parent_manager['id']);
				$parent_manager = ORM::factory('manager',$manager->parent_id);
			}
			return $manager;
		}
		else
		{
			return $manager;
		}
	}

    /**
     * get manager sub account
     *
     * @param <Int> $id ID
     * @return Array
     */
    public function subs($id = 0)
    {
		if(!$id)
		{
			$id = $this->data['id'];
		}

        $result = array();

        $list = ORM::factory('manager')
            ->where('parent_id',$id)
            ->find_all();
        
        foreach($list as $item)
        {
			$tmp = $item->as_array();
			$tmp['role_name'] = $item->role->name;
            $result[] = $tmp;
            $temp = $this->subs($item->id);
            if(is_array($temp) && count($temp))
            {
                $result = array_merge($result,$temp);
            }			
        }
        return $result;
    }

	/**
	 * get user_log data
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
	 * get manager data by email
	 *
	 * @param String $email
	 * @return Array
	 */
	function get_by_email($email)
	{
		$manager = ORM::factory('manager')->where('email',$email)->find()->as_array();

		$this->data = $manager;

		return $manager;
	}

	/**
	 * get manager data by email
	 *
	 * @param String $email
	 * @return Array
	 */
	function get_by_username($username)
	{
		$manager = ORM::factory('manager')->where('username',$username)->find()->as_array();

		$this->data = $manager;

		return $manager;
	}

	/**
	 * add a manager
	 *
	 * @param array $data
	 * @return array
	 */
	public function adminadd($data)
	{
		$data['is_admin'] = 1;
		return $this->add($data);
	}


	/**
	 * add a merchant item
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		$flag = FALSE;
		$data['password'] = md5($data['password']);
		$manager = ORM::factory('manager');
		$manager->parent_id = $data['parent_id'];
		$errors = '';
		if($manager->validate($data ,TRUE ,$errors)) 
		{
			$flag = TRUE;
			$this->data = $manager->as_array();
			//zhu add
			if($data['role_id']>0)
			{
				$data['manager_id'] = $this->data['id'];
				$flag = $this->set_acl($data);			
			}
		}
		return $flag;
	}
	
	/**
	 * zhu add
	 * set manager acl
	 */
	public function set_acl($data)
	{
		$flag = FALSE;
		$manager_id = $data['manager_id'];
		if($manager_id>0)
		{
		    $permissions = '';
		    $role_id = $data['role_id'];
			if($role_id>0)
			{
				$role = ORM::factory('role',$role_id)->as_array();
				$permissions = $role['permissions'];
			}	
			$flag = $this->set_actions($manager_id, $permissions, $role_id);
		}
		return $flag;
	}
		
	/**
	 * zhu add set manager rule actions
	 *
	 * @param <Int> $id manager id
	 * @param <Array> $resource manager rule array
	 * @return Boolean
	 */
	public function set_actions($manager_id, $resource, $role_id=0)
	{		
		if(is_array($resource))
		{
			$resource = implode(",", $resource);
		}
		$manager = ORM::factory('manager', $manager_id);
		$managerData = $manager->as_array();
		$manager->acl->manager_id = $manager_id;
		$manager->acl->username = $managerData["username"];
		$manager->acl->permissions = $resource;
		$manager->acl->save();
        $manager->role_id = $role_id;
        $manager->save();
		return TRUE;
	}
	
	/**
	 * set manger sites
	 *
	 * @param <Int> $id manager id
	 * @param <Array> $data 
	 *
	 * @return <Boolean>
	 */
	public function set_sites($id,$data)
	{
		$sites = $data['target_select'];

		$manager_sites = ORM::factory('manager',$id)->sites;
		foreach($manager_sites as $item){
			if(in_array($item->id,$sites)){
				$key = array_search($item->id,$sites);
				unset($sites[$key]);
			}else{
				$manager = ORM::factory('manager',$id);
				$manager->remove(ORM::factory('site',$item->id));
				$manager->save();
			}
		}

		if(count($sites) > 0){
			foreach($sites as $key=>$value){
				if($value){
					$manager = ORM::factory('manager',$id);
					$manager->add(ORM::factory('site',$value));
					$manager->save();
				}
			}
		}
		return true;
	}
	
	/**
	 * edit manager
	 *
	 * @param Array $data
	 * @param Int $id
	 * @return Array
	 */
	public function _edit($id,$data)
	{
		$id = intval($id);

		//EDIT
		$manager = ORM::factory('manager',$id);
		if(!$manager->loaded)
		{
			$this->error[] = "用户不存在.";
			return FALSE;
		}
		//TODO
		$errors = '';
		if($manager->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $manager->as_array();
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
		//zhu modify 更新用户acl
		//return $this->_edit($id,$data);
		//if($data['setacl']>0)
		//{
		    //$data['role_id']=0;
		//}
		$flag = $this->_edit($id, $data);
		if($flag && $data['role_id']>0)
		{
			$data['manager_id'] = $id;
			$flag = $this->set_acl($data);			
		}
		return $flag;
	}
	
	/**
	 * 判断用户名是否存在
	 * @param string $username
	 * @param int $extra_id
	 */
	public function username_exist($username,$extra_id=0)
	{
		$where = array();
		$where['username'] = $username;
		$where['id <>'] = $extra_id;
		$manager = ORM::factory('manager')->where($where)->find();
		return $manager->loaded;
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
	 * change manager parent
	 *
	 * @param <Int> $id parent_id
	 * @retrun Boolean
	 */	
	public function change_parent($parent_id)
	{
		$id = intval($this->data['id']);

		$manager = ORM::factory('manager',$id);
		$manager->parent_id = $parent_id;
	    $manager->save();	
		if($manager->saved)
		{
			return true;
		}
		else
		{
			return false;
		}	
	}

	/**
	 * zhu add get manager acl
	 * 
	 */
	public function acl()
	{
		$id = intval($this->data['id']);

		$acl = ORM::factory('manager',$id)->acl;
		return $acl->as_array();
	}

	/**
	 * get manager role
	 */
	public function role()
	{
		$id = intval($this->data['id']);

		$role = ORM::factory('manager',$id)->role;
		return $role->as_array();
	}

	/**
	 * get manager sites
	 *
	 * @return Array
	 */
	public function sites()
	{
		$list = array();

		$sites = ORM::factory('manager',$this->data['id'])->sites;
		foreach($sites as $item)
		{
			$list[] = $item->as_array();
		}
		return $list;
	}

	/**
	 * get manager site_types
	 *
	 * @return Array
	 */
	public function site_types()
	{
		$id = intval($this->data['id']);

		$manager = ORM::factory('manager',$id);
		$site_types = $manager->site_types;
		return $site_types->as_array();
	}

	/**
	 * get manager actions
	 *
	 * @return Array
	 */
	public function actions()
	{
		$list = array();

		$actions = ORM::factory('manager',$this->data['id'])->actions;
		foreach($actions as $item)
		{
			$list[] = $item->as_array();
		}
		return $list;
	}

	/**
	 * change manager password
	 *
	 * @param Int $id
	 * @param String $pwd_old
	 * @param Int $pwd_new
	 * @return Boolean
	 */
	public function change_password($id,$pwd_old,$pwd_new)
	{
		$manager = ORM::factory('manager',$id);
		if($manager->password == md5($pwd_old))
		{
			$manager->password = md5($pwd_new);
			$manager->save();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * update manager by id
	 *
	 * @param <Int> $id
	 * @param <Array> $data
	 *
	 * @return <Boolean>
	 */
	public function _update($id,$data = array())
	{
		$manager = ORM::factory('manager',$id);

		if(count($data) > 0)
		{
			foreach($data as $key=>$value)
			{
				$manager->$key = $value;
			}
		}
		$manager->save();
		return $manager->as_array();
	}
	
	/**
	 * update manager
	 *
	 * @param <Array> $data
	 *
	 * @return <Boolean>
	 */
	public function update($data)
	{
		$id = intval($this->data['id']);

		return $this->_update($id,$data);
	}

	/**
	 * update manager
	 *
	 * @param <Array> $data
	 *
	 * @return <Boolean>
	 */
	public function update_by_id($id,$data)
	{
		return $this->_update($id,$data);
	}

	/**
	 * delete manager
	 *
	 * @param int $id manager id
	 * @return boolean
	 */
	public function _delete($id)
	{
		$id = intval($id);
		$manager = ORM::factory('manager',$id);
		if($manager->loaded)
		{
			$manager->delete();
			return true;
		}
		else
		{
			$this->error[] = '管理员不存在';
			return false;
		}
	}

	/**
	 * delete manager
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
