<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mymenu_Core {
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

		$menu = ORM::factory('menu',$id)->as_array();
		$this->data = $menu;
	}

	/**
	 * 根据条件得到菜单列表
	 *
	 * @param <Array> $where
	 * @return Array
	 */
	public function get_menus($where = array(),$orderby = array())
	{
		$list = array();

		$menus = ORM::factory('menu');

		if(count($where))
		{
			$menus->where($where);
		}

		if(count($orderby))
		{
			$menus->orderby($orderby);
		}

		$orm_list = $menus->find_all();

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
	public function menus($id = 0,$orderby=array('order'=>'DESC'))
	{
		$result = array();

		$list = ORM::factory('menu')
			->where('parent_id',$id)
			->orderby($orderby)
			->find_all();

		foreach($list as $item)
		{
			$menu = $item->as_array();
			$action_name = $item->action->name;
			$menu['action_name'] = $action_name;
			$result[] = $menu;

			$temp = $this->menus($item->id);
			if(is_array($temp) && count($temp))
			{
				$result = array_merge($result,$temp);
			}			
		}
		return $result;
	}

	/**
	 * get menu data
	 *
	 * @return Array
	 */
	public function get($key=NULL)
	{
		if(empty($key))
		{
			return $this->data;
		}
		else
		{
			return isset($this->data[$key])?$this->data[$key]:'';
		}
	}
	
	/**
	 * 得到所有菜单项(父子项的关系)
	 */
	public function get_level_menus($orderby = array('order'=>'DESC'))
	{
		$cache = Mycache::instance ();
		$tag = "admin/menu";
		$data = $cache->get ( $tag );
		$list = array();
		if(!$data)
		{
			$menus = ORM::factory('menu')
				->where('parent_id',0)
				->where('active',1)
				->orderby($orderby)
				->find_all();
			
			foreach($menus as $item)
			{
				$value = $item->as_array();
				$sub_menus = self::sub_menus($value['id']);
				foreach ($sub_menus as $sub_menu_key=>$sub_menu_value)
				{
					$sub_menus[$sub_menu_key]['children'] = self::sub_menus($sub_menu_value['id']);
				}
				$value['children'] = $sub_menus;
				$list[] = $value;
			}
			
			$cache->set ( $tag, $list);
		}
		else
		{
			$list = $data;
		}
		return $list;
	}
	
	/**
	 * get sub menu by menu id
	 *
	 * @param Int $id
	 * @return Array
	 */
	public function sub_menus($id)
	{
		$list = array();

		$sub_menus = ORM::factory('menu')
			->where(array('parent_id'=>$id,'active'=>1))
			->orderby(array('order'=>'DESC'))
			->find_all();
		foreach($sub_menus as $item)
		{
			$list[] = $item->as_array();
		}
		return $list;
	}

	/**
	 * get menu by url
	 *
	 * @param String url
	 * @return Array
	 */
	public function get_by_url($url){
		$menu = ORM::factory('menu')->where(array('url'=>$url))->find()->as_array();
		return $menu;
	}

	/**
	 * get menu by url
	 *
	 * @param String url
	 * @return Array
	 */
	public function get_by_target($target){
		$menu = ORM::factory('menu')->where(array('target'=>$target))->find()->as_array();
		return $menu;
	}

	/**
	 * add a item
	 *
	 * @param Array $data
	 * @return Array
	 */
	public function add($data)
	{
		$level_depth = 1;
		$parent_id = $data['parent_id'];
		if($parent_id > 0)
		{
			$parent_menu = ORM::factory('menu',$parent_id);
			if($parent_menu->loaded)
			{
				$level_depth = $parent_menu->level_depth+1;
			}
		}

		//ADD
		$menu = ORM::factory('menu');
		$menu->level_depth = $level_depth;
		$errors = '';
		if($menu->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $menu->as_array();
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
		$menu = ORM::factory('menu',$id);
		if(!$menu->loaded)
		{
			return FALSE;
		}
		$level_depth = 1;
		$parent_id = $data['parent_id'];
		if($parent_id > 0)
		{
			$parent_menu = ORM::factory('menu',$parent_id);
			if($parent_menu->loaded)
			{
				$level_depth = $parent_menu->level_depth+1;
			}
		}
		//TODO
		$menu->level_depth = $level_depth;
		$errors = '';
		if($menu->validate($data ,TRUE ,$errors)) 
		{
			//更新排序
			if($menu->order <= 0)
			{
				$menu->order = $menu->id;
				$menu->save();
			}
			$this->data = $menu->as_array();
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
	 * get menu parent item
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
		$menu = ORM::factory('menu')->where(array('parent_id'=>$parent_id))->find()->as_array();
		return $menu;
	}
	
	/**
	 * set active
	 */
	function set_active($id = 0,$flag = 1)
	{
		if($id<=0)
		{
			return false;
		}
		$menu = ORM::factory('menu',$id);
		if($menu->loaded)
		{
			$menu->active = $flag;
			$menu->save();
			if($menu->saved)
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	

	/**
	 * 设置菜单的排序项
	 * @param int $id 菜单ID
	 * @param int $order 排序项
	 * return bool
	 */
    public function set_order($id ,$order)
    {
        $where = array('id'=>$id);
        $obj = ORM::factory('menu')->where($where)->find();
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

		$menu = ORM::factory('menu',$id);
		if($menu->id){
			$menu->delete();
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
