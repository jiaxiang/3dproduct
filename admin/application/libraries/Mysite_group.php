<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mysite_group_Core extends My{
	protected $object_name = 'site_group';
	protected $data = array();
	protected $errors = array();

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

		$site_group = ORM::factory('site_group',$id)->as_array();
		$this->data = $site_group;
	}

	/**
	 * get site_group
	 *
	 * @param <Array> $where
	 * @return Array
	 */
	public function get_site_groups($where = array(),$orderby = array())
	{
		$list = array();

		$site_group = ORM::factory('site_group');

		if(count($where))
		{
			$site_group->where($where);
		}

		if(count($orderby))
		{
			$site_group->orderby($orderby);
		}

		$orm_list = $site_group->find_all();

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
	public function site_groups($id = 0)
	{
		$result = array();

		$list = ORM::factory('site_group')
			->where('parent_id',$id)
			->orderby(array('order'=>'ASC'))
			->find_all();

		foreach($list as $item)
		{
			$result[] = $item->as_array();
			$temp = $this->site_groups($item->id);
			if(is_array($temp) && count($temp))
			{
				$result = array_merge($result,$temp);
			}			
		}
		return $result;
	}
    /**
     * 遍历操作
     *
     * @param <Int> $id ID
     * @return Array
     */
    public function manage_site_groups($query_assoc)
    {
        $result = array();
        $list = $this->lists($query_assoc);
//        $list = ORM::factory('site_group')
//            ->where('parent_id',$id)
//            ->orderby(array('order'=>'ASC'))
//            ->find_all();
//
        foreach($list as $item)
        {
            $result[] = $item;
            $query_assoc['where']['parent_id'] = $item['id'];
            $temp = $this->manage_site_groups($query_assoc);
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
	public function get()
	{
		return $this->data;
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

		$site_group = ORM::factory('site_group')->where($where)->find()->as_array();
		return $site_group;
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
			$parent_site_group = ORM::factory('site_group',$parent_id);
			if($parent_site_group->loaded)
			{
				$level_depth = ++$parent_site_group->level_depth;
			}
		}
		
		$perv_site_group = ORM::factory('site_group')->where('parent_id',$parent_id)->orderby('order','DESC')->find();

		//ADD
		$site_group = ORM::factory('site_group');
		$site_group->add_time = date('Y-m-d H:i:s');
		$site_group->level_depth = $level_depth;
		$errors = '';
		if($site_group->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $site_group->as_array();
			return TRUE;
		}
		else
		{
			//var_dump($errors);exit;
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
		$site_group = ORM::factory('site_group',$id);
		if(!$site_group->loaded)
		{
			return FALSE;
		}
		//TODO
		$errors = '';
		if($site_group->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $site_group->as_array();
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
		$site_group = ORM::factory('site_group')->where(array('parent_id'=>$parent_id))->find()->as_array();
		return $site_group;
	}

	/**
	 * get site_group sites
	 */
	function sites()
	{
		$list = array();

		$sites = ORM::factory('site_group',$this->data['id'])->sites;
		foreach($sites as $item)
		{
			$list[] = $item->as_array();
		}
		return $list;
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

		$site_group_sites = ORM::factory('site_group',$id)->sites;
		foreach($site_group_sites as $item){
			if(in_array($item->id,$sites)){
				$key = array_search($item->id,$sites);
				unset($sites[$key]);
			}else{
				$site_group = ORM::factory('site_group',$id);
				$site_group->remove(ORM::factory('site',$item->id));
				$site_group->save();
			}
		}

		if(count($sites) > 0){
			foreach($sites as $key=>$value){
				if($value){
					$site_group = ORM::factory('site_group',$id);
					$site_group->add(ORM::factory('site',$value));
					$site_group->save();
				}
			}
		}
		return true;
	}


    public function set_order($id ,$order)
    {
        $where = array('id'=>$id);
        $obj = ORM::factory('site_group')->where($where)->find();
        if($obj->loaded)
        {
            $obj->order = $order;
            return $obj->save();
        }
        return false;
    }

	/**
	 * delete a item
	 *
	 * @param Int $id
	 * @return Boolean
	 */
	function delete($id){
		$id = intval($id);
		if(!$id){
			return FALSE;
		}

		$site_group = ORM::factory('site_group',$id);
		if($site_group->id){
			$site_group->delete();
			return TRUE;
		}
		else
		{
			return FALSE;
		}
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
