<?php defined('SYSPATH') OR die('No direct access allowed.');

class Myaction_Core {
	private $data = array();
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
	 * Construct load action data
	 *
	 * @param Int $id
	 */
	public function __construct($id)
	{
		$this->_load($id);
	}

	/**
	 * load action data
	 *
	 * @param Int $id
	 */
	private function _load($id)
	{
		$id = intval($id);

		$action = ORM::factory('action',$id)->as_array();
		$this->data = $action;
	}

	/**
	 * 根据条件得到权限操作列表
	 *
	 * @param <Array> $where
	 * @return Array
	 */
	public function get_actions($where = array())
	{
		$list = array();

		$actions = ORM::factory('action');

		if(count($where))
		{
			$actions->where($where);
		}

		$orm_list = $actions->find_all();

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
     * @param <array> $in ID
     * @return Array
     */
    public function actions($id = 0, $in = NULL)
    {
        $result = array();
        //zhu add
        static $aid;
        if($in && !$aid)
        {
            $aid = $in;
        }
        //zhu modify
        /*$list = ORM::factory('action')
            ->where('parent_id',$id)
            ->orderby(array('order'=>'DESC'))
            ->find_all();*/
        $action = ORM::factory('action');        
        if(is_array($in) && count($in)>0 && $in[0]>0)
        {
            $action->in('id',$in);
        }
        else
        {
        	$action->where('parent_id',$id);
        }
        $list = $action->orderby(array('order'=>'DESC'))->find_all();
        foreach($list as $item)
        {
            $result[] = $item->as_array();
            $temp = $this->actions($item->id);
            if(is_array($temp) && count($temp))
            {
                //zhu add 删除重复数据
                if(is_array($aid))
                {
                    foreach($temp as $k => $ac)
                    {
                        if(in_array($ac['id'], $aid))
                        {
                            unset($temp[$k]);
                        }
                    }
                }
                //zhu end

                $result = array_merge($result,$temp);
            }
        }
        return $result;
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
	 * get action by name
	 *
	 * @param <String> $name action name
	 * @return Array
	 */
	public function get_by_name($name)
	{
		$where = array();
		$where['name'] = $name;
		$action = ORM::factory('action')->where($where)->find();
		return $action->as_array();
	}

	/**
	 * get action by resource
	 *
	 * @param <String> $resource action resource
	 * @return Array
	 */
	public function get_by_resource($resource)
	{
		$where = array();
		$where['resource'] = $resource;
		$action = ORM::factory('action')->where($where)->find();
		return $action->as_array();
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
		if(isset($parent_id) && !empty($parent_id))
		{
			$parent_action = ORM::factory('action',$parent_id);
			$level_depth = $parent_action->level_depth+1;
		}
		//ADD
		$action = ORM::factory('action');
		$action->level_depth = $level_depth;
		$errors = '';
		if($action->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $action->as_array();
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
        //zhu add 
		$level_depth = 1;
		$parent_id = $data['parent_id'];
		if(isset($parent_id) && !empty($parent_id))
		{
			$parent_action = ORM::factory('action',$parent_id);
			$level_depth = $parent_action->level_depth+1;
		}

		$id = intval($id);
		$action = ORM::factory('action',$id);
		if(!$action->loaded)
		{
			return false;
		}

        //zhu add 
		$action->level_depth = $level_depth;

		$errors = '';
		if($action->validate($data ,TRUE ,$errors)) 
		{
			$this->data = $action->as_array();
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

        $action = ORM::factory('action',$id);
        if($action->id){
            $action->delete();
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
	 * @return String
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
