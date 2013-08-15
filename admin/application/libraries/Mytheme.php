<?php defined('SYSPATH') or die('No direct script access.');

class Mytheme_Core extends My{
    //对象名称(表名)
    protected $object_name = 'theme';
    public $img_dir_name = 'theme';

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

	public function get_themes($type = NULL,$grade = NULL,$offset = NULL, $limit = NULL)
	{
		$themes = ORM::factory('theme');
		if(!is_null($type))
		{
			if(is_array($type))
			{
				$themes->in('type',$type);
			}
			else
			{
				$themes->where('type',$type);
			}
		}

		if(!is_null($grade))
		{
			if(is_array($grade))
			{
				$themes->in('grade',$grade);
			}
			else
			{
				$themes->where('grade',$grade);
			}
		}

		$themes = $themes->find_all($offset,$limit);
		$data = array();
		foreach($themes as $item)
		{
			$data[] = $item->as_array();
		}
		return $data;
	}

	public function init($type = 0)
	{
        return array();
		$theme_id = $this->data['id'];
		$server = Storage_server::instance();
		$data = array();	

		// views 
		$theme_views = $server->get_themes($theme_id,'views');
		$data['views'] = $theme_views;

		if($theme_views)
		{
			foreach($theme_views as $item)
			{
				$file = $server->get_theme($theme_id,'views',$item);
				$server->cache_site_theme($theme_id,'views',$item,$file);
			}
		}

		// js 
		$theme_js = $server->get_themes($theme_id,'js');
		$data['js'] = $theme_js;

		if($theme_js)
		{
			foreach($theme_js as $item)
			{
				$file = $server->get_theme($theme_id,'js',$item);
				$server->cache_site_theme($theme_id,'js',$item,$file);
			}
		}

		// css
		$theme_css = $server->get_themes($theme_id,'css');
		$data['css'] = $theme_css;
		if($theme_css)
		{
			foreach($theme_css as $item)
			{
				$file = $server->get_theme($theme_id,'css',$item);
				$server->cache_site_theme($theme_id,'css',$item,$file);
			}
		}

		return $data;
	}
	
	/**
	 * 删除模板
	 * @param int $id
	 */
	public function delete($id = 0)
	{
		$id = $id ? $id : $this->data['id'];
		
		$theme = ORM::factory('theme',$id);
        
		if($theme->loaded)
		{
            $site = Mysite::instance()->get();
	        if(isset($site['theme_id']) && $site['theme_id']==$id)
	        {
	        	return false;
	        }
	        $this->clear_theme($theme->id);
			$theme->delete();
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 清理指定主题的图片资源信息
	 * @param int $theme_id 主题ID
	 */
	public function clear_theme_img($img_id)
	{   
        AttService::get_instance($this->img_dir_name)->delete_img($img_id);
        return true;
    }
    
	/**
	 * 清理指定主题的资源信息
	 * @param int $theme_id 主题ID
	 */
	public function clear_theme($theme_id = 0)
	{   
		$img_id = $this->img_dir_name.$theme_id;
        $this->clear_theme_img($img_id);
        return true;
		$server = Storage_server::instance();
		// views 
		$theme_views = $server->get_themes($theme_id,'views');
		if($theme_views)
		{
			foreach($theme_views as $item)
			{
				$server->delete_theme($theme_id,'views',$item);
			}
		}
		// js 
		$theme_js = $server->get_themes($theme_id,'js');
		if($theme_js)
		{
			foreach($theme_js as $item)
			{
				$server->delete_theme($theme_id,'js',$item);
			}
		}
		// css
		$theme_css = $server->get_themes($theme_id,'css');
		if($theme_css)
		{
			foreach($theme_css as $item)
			{
				$server->delete_theme($theme_id,'css',$item);
			}
		}
		// images 
		$theme_images = $server->get_themes($theme_id,'images');
		if($theme_images)
		{
			foreach($theme_images as $item)
			{
				$server->delete_theme($theme_id,'images',$item);
			}
		}
		return true;
	}
}

