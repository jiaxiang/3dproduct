<?php defined('SYSPATH') OR die('No direct access allowed.');

class Mysite_menu_Core extends My{
	//对象名称(表名)
    protected $object_name = 'site_menu';

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
	 * 判断输入的url是否已存在
	 */
	public function url_exist($url)
	{
		$to = array();
		$to = array(
			'where' => array(
				'url' => $url
			)
		);
		$count = $this->count($to);
		if($count > 0){
			return TRUE;
		} else{
			return FALSE;
		}
	}
	
	
	/**
	 * site menu list
	 */
	public function site_menus($site_id,$limit=100,$offset=0)
	{
		$site_menus = ORM::factory('site_menu')	
			->where('site_id',$site_id)
			->orderby('order','ASC')
			->find_all($limit,$offset);
		$data = array();
		foreach($site_menus as $item)
		{
			$data[] = $item->as_array();
		}
		return $data;
	}

    /**
     * 遍历操作
     *
     * @param <Int> $id ID
     * @return Array
     */
    public function site_menu_queue($id = 0,$limit=300,$offset=0)
    {
        $result = array();

        $list = ORM::factory('site_menu')
            ->where(array('parent_id'=>$id))
            ->orderby(array('order'=>'DESC'))
            ->find_all($limit,$offset);
        
        foreach($list as $item)
        {
            $result[] = $item->as_array();
            $temp = $this->site_menu_queue($item->id);
            if(is_array($temp) && count($temp))
            {
                $result = array_merge($result,$temp);
            }			
        }
        return $result;
    }
	
	/**
	 * site menu edit
	 */
	public function site_menu_edit($id,$data)
	{
		$site_menu = ORM::factory('site_menu',$id);

		if($site_menu->validate($data,TRUE))
		{
			return TRUE;
		}

		return FALSE;
	}

	public function site_menu_add($data)
	{
		$site_menu = ORM::factory('site_menu');	
		if($site_menu->validate($data,TRUE))
		{
			return TRUE;
		}

		return FALSE;	
	}

	public function init($type = 0)
	{
		$data = array();
		$data['name'] = 'Home';
		$data['url'] = '/';
		$data['title'] = 'home';
		$data['target'] = '0';
		$data['order'] = '0';

		$data1 = array();
		$data1['name'] = 'about us';
		$data1['url'] = '/about-us';
		$data1['title'] = 'about us';
		$data1['target'] = '0';
		$data1['order'] = '0';

		$this->site_menu_add($data);
		$this->site_menu_add($data1);
	}


    public function set_order($id ,$order)
    {
        $where = array('id'=>$id);
        $obj = ORM::factory('site_menu')->where($where)->find();
        if($obj->loaded){
            $obj->order = $order;
            if($obj->save()){
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * 修改子导航的level_depth
     *
     * @param <int> $id
     * @param <array> $child_menus
     * @return Boolean
     */
    public function child_level_depth_edit($parent_id,$parent_depth,$child_menus = array())
    {
        $data = array();
        $temp = array();
        foreach($child_menus as $value)
        {
        	if($value['parent_id'] == $parent_id)
        	{
        		$data = $value;
        		$data['level_depth'] = $parent_depth + 1;
        		if(!$this->site_menu_edit($value['id'],$data))
        		{
        			return false;
        		}
        		$temp = self::site_menu_queue($value['id']);
        		self::child_level_depth_edit($value['id'],$data['level_depth'],$temp);
        	}
        }
    	return true;
    }
    
	/**
	 * set site_menu order
     */
    /*
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
			$this->error[] = "非法操作";
			return false;
		}
    }
     */



	/**
	 * set site_menu order up
	 */
    /*
	function set_order_up($id)
	{
		$where = array();
		$orderby = array();

		$site_menu = ORM::factory('site_menu',$id);
		if($site_menu->loaded)
		{
			$site_id = $site_menu->site_id;
			$cur_order = $site_menu->order;
			if($cur_order < 1)
			{
				$this->error[] = "已经是最顶部";
				return false;
			}
			else
			{
				$where['order <'] = $cur_order;
				$where['site_id'] = $site_id;
				$orderby['order'] = "DESC";
				$perv_site_menu = ORM::factory('site_menu')->where($where)->orderby($orderby)->find();
				if($perv_site_menu->id)
				{
					$perv_site_menu_order = $perv_site_menu->order;

					$site_menu->order = $perv_site_menu_order;
					$perv_site_menu->order = $cur_order;

					$site_menu->save();
					$perv_site_menu->save();
					return true;
				}
				else
				{
					$this->error[] = "已经是顶部";
					return false;
				}
			}
		}
		else
		{
			$this->error[] = "记录未找到";
			return false;
		}
    }
     */

	/**
	 * set site_menu order down
	 */
    /*
	function set_order_down($id)
	{
		$where = array();
		$orderby = array();

		$site_menu = ORM::factory('site_menu',$id);
		if($site_menu->loaded)
		{
			$site_id = $site_menu->site_id;
			$cur_order = $site_menu->order;
			$where['order >'] = $cur_order;
			$where['site_id'] = $site_id;
			$orderby['order'] = "DESC";
			$perv_site_menu = ORM::factory('site_menu')->where($where)->orderby($orderby)->find();
			if($perv_site_menu->id)
			{
				$perv_site_menu_order = $perv_site_menu->order;

				$site_menu->order = $perv_site_menu_order;
				$perv_site_menu->order = $cur_order;

				$site_menu->save();
				$perv_site_menu->save();
				return true;
			}
			else
			{
				$this->error[] = "已经是底部";
				return false;
			}
		}
		else
		{
			return false;
		}
    }
     */
}
