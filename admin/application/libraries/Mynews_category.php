<?php 
defined('SYSPATH') or die('No direct script access.');

class Mynews_category_Core extends My{
	//对象名称(表名)
    protected $object_name = 'news_category';
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
     * 遍历操作
     *
     * @param <Int> $id ID
     * @return Array
     */
    public function news_categories($id = 0)
    {
        $result = array();

        $list = ORM::factory('news_category');
		$list->where('parent_id',$id);
        $list->orderby(array('p_order'=>'ASC'));
        $list = $list->find_all();
        
        foreach($list as $item)
        {
            $result[] = $item->as_array();
            $temp = $this->news_categories($item->id);
            if(is_array($temp) && count($temp))
            {
                $result = array_merge($result,$temp);
            }			
        }
        return $result;
    }
    
    public function list_news_categories($query_assoc)
    {
        
		$result = array();
        $list = $this->lists($query_assoc);
        foreach($list as $item)
        {
            $result[] = $item;
            $query_assoc['where']['parent_id'] = $item['id'];
            $temp = $this->list_news_categories($query_assoc);
            if(is_array($temp) && count($temp))
            {
                $result = array_merge($result,$temp);
            }           
        }
		return $result;
		
    }
    /**
     * 添加新的页面分类
     */
    public function add($data)
    {
    	$parent_id = $data['parent_id'];
    	
    	$data['active'] = 1;
	    $data['level_depth'] = 1;
	    $data['is_leaf'] = true;
    	if($parent_id > 0)
    	{
    		//取得父分类的信息
    		$parent_news_category = ORM::factory('news_category',$parent_id);
    		if($parent_news_category->id)
    		{
    			$data['level_depth'] = $parent_news_category->level_depth+1;
    			$data['cat_path'] = $parent_news_category->cat_path . $parent_id . ',';
    			//父节点有了子节点
    			$this->update_leaf($parent_id,false);
    			//父节点子节点的数量增加
    			$this->update_child_conut($parent_id);
    			$data['is_leaf'] = true;
    		}
    	}
    	$news_category = ORM::factory('news_category');
		$errors = '';
		if($news_category->validate($data,TRUE,$errors))
		{
			$this->data = $news_category->as_array();
			return $news_category->id;
		}
		else
		{
			//var_dump($errors);exit;
			$this->error[] = $errors;
			return FALSE;
		}
    	return $news_category_id;
    }
    
    /**
     * 
     */
    public function edit($data)
    {
    	$id = ((isset($data['id'])) && ($data['id'] > 0))?$data['id']:$this->data['id'];
    	if(!$id)
    	{
    		$this->error[] = '页面分类不存在.';
    		return false;
    	}
    	$news_category = ORM::factory('news_category',$id);
        if(!$id)
    	{
    		$this->error[] = '页面分类不存在.';
    		return false;
    	}
    	
    	$parent_id = $data['parent_id'];
	    
    	if($parent_id > 0)
    	{
    		//取得父分类的信息
    		$parent_news_category = ORM::factory('news_category',$parent_id);
    		if(!$parent_news_category->id)
    		{
	    		$this->error[] = '选择的页面上线分类不存在.';
	    		return false;
    		}
    		else
    		{
    			//父分类不能直接移到子分类中
    			$parent_cat_path_arr = explode(',',$parent_news_category->cat_path);
    			if(in_array($id,$parent_cat_path_arr))
    			{
		    		$this->error[] = '父分类不能重新设置到子分类中.';
		    		return false;
    			}
    			
    			//更换了父分类后，原父分类和现有父分类都要更新。
    			if($parent_id <> $news_category->parent_id)
    			{
	    			$data['level_depth'] = $parent_news_category->level_depth+1;
	    			$data['cat_path'] = $parent_news_category->cat_path . $parent_id . ',';
	    			//父节点子节点的数量增加
	    			$this->update_child_conut($parent_id);
	    			//父节点有了子节点
	    			$this->update_leaf($parent_id,false);
	    			//更新前的父分类                    
	    			$perv_parent_news_category = ORM::factory('news_category',$news_category->parent_id);
	    			if($perv_parent_news_category->child_count > 1)
	    			{
	    				//父分类的子节点数量减少
	    				$this->update_child_conut($parent_id,false);
	    			}
	    			else 
	    			{
	    				//父分类的子节点数量减少
	    				$this->update_child_conut($parent_id,false);
	    				$this->update_leaf($parent_id,true);
	    			}
    			}
    		}
    	}
        else
        {
            //没有父分类
    	    $data['level_depth'] = 1;
    	    $data['cat_path'] = '';
    	    $data['is_leaf'] = false;
        }
		$errors = '';
		if($news_category->validate($data,TRUE,$errors))
		{
			$this->data = $news_category->as_array();
			return $news_category->id;
		}
		else
		{
			$this->error[] = $errors;
			return FALSE;
		}
    	return $news_category_id;
    }
    
    /**
     * 更新节点的子节点数量
     * 
     * @param <int> $is_sum 增加数量还是减掉数量(true:增加，false:减少数量)
     * @return boolean
     */
    public function update_child_conut($id=0,$is_sum = true)
    {
    	if(!$id)
    	{
    		return false;
    	}
    	$news_category = ORM::factory('news_category',$id);
    	if($is_sum)
    	{
    		$news_category->child_count = $news_category->child_count++;
    	}
    	else
    	{
    		if($news_category->child_count > 0)
    		{
    			$news_category->child_count = $news_category->child_count++;
    		}
    		else
    		{
    			$news_category->child_count = 0;
    		}
    	}
    	$news_category->save();
    	if($news_category->saved)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
    
    /**
     * 判断名称是否重复
     */
    public function name_exist($name='',$filter = 0)
    {
    	if(empty($name))
    	{
    		return false;
    	}
    	
    	$news_category = ORM::factory('news_category')->where('category_name',$name);
    	if($filter)
    	{
    		$news_category->where('id !=',$filter);
    	}
    	$news_category = $news_category->find();
    	if($news_category->loaded)
    	{
    		return true;
    	}
    	else
    	{
    		return false;
    	}
    }
    
    /**
     * 更新节点是否含有子节点
     */
    public function update_leaf($id=0,$flag = true)
    {
    	if($id > 0)
    	{
    		$news_category = ORM::factory('news_category',$id);
    		$news_category->is_leaf = $flag;
    		$news_category->save();
    		if($news_category->saved)
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
     * set news_category order
     */
    public function set_order($id ,$order)
    {
        $where = array('id'=>$id);
        $obj = ORM::factory('news_category')->where($where)->find();
        if($obj->loaded){
            $obj->p_order = $order;
            if($obj->save()){
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * 根据站点ID删除文案分类的数据
     * @param $site_id 站点ID
     * @return boolean
     */
    public function delete_by_site_id($site_id = 0)
    {
    	if($site_id < 1)
    	{
    		return false;
    	}
    	/* 删除FAQ */
    	$faq = ORM::factory('faq')->where('site_id',$site_id)->delete_all();
    	return true;
    }
}
