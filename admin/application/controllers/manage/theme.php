<?php defined('SYSPATH') or die('No direct access allowed.');

class Theme_Controller extends Template_Controller{
    private $img_dir_name = 'theme';
	public $template_ = 'layout/common_html';
	public function __construct()
	{
		role::check('theme');
		parent::__construct();
	    if($this->is_ajax_request()==TRUE)
        {
            $this->template = new View('layout/default_json');
        }
	}
	
	/**
	 * 模板列表
	 */
	public function index()
	{
		//调用分页
		$per_page = controller_tool::per_page();
		// 初始化默认查询条件
		$request_struct_current = array(
			'like' => array(), 
			'orderby' => array(
				'id' => 'DESC'
			), 
			'limit' => array(
				'per_page' => $per_page, 
				'page' => 1
			)
		);
		//分页
		$this->pagination = new Pagination(array(
			'total_items' => Mytheme::instance()->count($request_struct_current), 
			'items_per_page' => $per_page
		));
		$request_struct['limit']['offset'] = $this->pagination->sql_offset;
		$themes = Mytheme::instance()->lists($request_struct_current);
		
		$this->template->content = new View("manage/theme_list", array('data' => $themes));
	}
	
	/**
	 * 编辑模板
	 * @param int $id 模板ID
	 */
	public function post_save()
	{
		if($_POST){
            $theme = Mytheme::instance();
            if(isset($_POST['id']) && $_POST['id']>0)
            {
                $theme->edit($_POST);
                $id = $theme->get('id');
            }
            else
            {
                $id = $theme->add($_POST);
            }
            if($id<=0)
            {
                remind::set(Kohana::lang('o_global.update_error'), '/manage/theme');
            }
            
			if(isset($_FILES['theme_image']['name']) && !empty($_FILES['theme_image']['name']))
            {
			    $file_type = kohana::config('theme.image_file_type');
			    $type = (count($file_type) > 0) ? $file_type : array(
			    	'jpg'
			    );
			    //判断文件类型
			    if(!in_array(strtolower($this->fileext($_FILES['theme_image']['name'])),$type)){
			    	remind::set(Kohana::lang('o_manage.pic_type_incorrect'),'/manage/theme/edit/' . $id);
			    }
			    //资源文件最大大小(default:1M)
			    $file_max_size = kohana::config('theme.file_max_size');
			    $file_max_size = ($file_max_size > 0) ? $file_max_size : 1048576;
			    
			    $file_size = filesize($_FILES['theme_image']['tmp_name']);
			    if($file_size > $file_max_size){
			    	remind::set(Kohana::lang('o_manage.pic_size_out_range'),'/manage/theme/edit/' . $id);
			    }
			    //$file = file_get_contents($_FILES['theme_image']["tmp_name"]);
			    //$filename = 'thumbnail.jpg';
			    //Storage_server::instance()->cache_theme($id,'images',$filename,$file);
                
                $AttService = AttService::get_instance($this->img_dir_name);
                $img_id = $AttService->save_default_img($_FILES['theme_image']["tmp_name"], $this->img_dir_name.$id);
                if(!$img_id){
                    remind::set(Kohana::lang('o_product.phprpc_pic_save_failed'), '/manage/theme/edit/' . $id);
                }
			}

        	/*
        		$path = DOCROOT."themes".DIRECTORY_SEPARATOR;
        		$handle = opendir($path);
        		$tt = Mycache::instance('tt');

        		While(false !== ($theme = readdir($handle)))
        		{
        			if(is_dir($path.DIRECTORY_SEPARATOR.$theme) AND $theme!== '.' AND $theme !== '..')
        			{
        				$theme = $path.DIRECTORY_SEPARATOR.$theme;		
        				$theme_handle = opendir($theme);
        				// php view file
        				While(false !== ($view = readdir($theme_handle)))
        				{
        					if( $view!== '.' AND $image !== '..')
        					{
        						//upload images 
        						$file = file_get_contents($theme_path.DIRECTORY_SEPARATOR.$view);
        						$file_name = "/themes/".$theme."/".$file;

        						$tt->set($file_name,$file);

        						$extend = explode('.',$file_name);
        						$va = count($extend) - 1;
        						$type = $extend[$va];

        						$tt->set($theme,$type,$file_name);
        					}
        				}
        				closedir($theme_handle);

        				// images 
        				$images = $path.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'images';		
        				$images_handle = opendir($images);
        				While(false !== ($image = readdir($images_handle)))
        				{
        					if( $image !== '.' AND $image !== '..')
        					{
        						//upload images 
        						$tt = Mycache::instance('tt');
        						$file = file_get_contents($theme_path.DIRECTORY_SEPARATOR.$image);
        						$file_name = "/themes/".$theme."/images/".$image;
        						$extend = explode('.',$file_name);
        						$va = count($extend) - 1;
        						$type = $extend[$va];

        						$tt->set($type,$key,$file);
        					}
        				}
        				closedir($images_handle);

        				// css
        				$css = $path.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'css';		
        				$css_handle = opendir($css);
        				While(false !== ($css = readdir($css_handle)))
        				{
        					if( $css !== '.' AND $css !== '..')
        					{
        						//upload css 
        						$tt = Mycache::instance('tt');
        						$file = file_get_contents($theme_path.DIRECTORY_SEPARATOR.$css);
        						$file_name = "/themes/".$theme."/css/".$css;
        						$extend = explode('.',$file_name);
        						$va = count($extend) - 1;
        						$type = $extend[$va];

        						$tt->set($type,$key,$file);
        					}
        				}
        				closedir($css_handle);

        				// js
        				$js = $path.DIRECTORY_SEPARATOR.$theme.DIRECTORY_SEPARATOR.'js';		
        				$js_handle = opendir($js);
        				While(false !== ($js = readdir($theme_handle)))
        				{
        					if( $js !== '.' AND $js !== '..')
        					{
        						//upload javascripts
        						$tt = Mycache::instance('tt');
        						$file = file_get_contents($theme_path.DIRECTORY_SEPARATOR.$js);
        						$file_name = "/themes/".$theme."/js/".$js;

        						$extend = explode('.',$file_name);
        						$va = count($extend) - 1;
        						$type = $extend[$va];
        						$tt->set($type,$key,$file);
        					}
        				}
        				closedir($js_handle);
        			}
        		}
        		closedir($handle);
        	*/
            remind::set(Kohana::lang('o_global.update_success'),'/manage/theme','success');
		}
    }
			
	/**
	 * 编辑模板
	 * @param int $id 模板ID
	 */
	public function edit($id)
	{
		$theme = Mytheme::instance($id);
		$data = $theme->get();
		
		if(!$data['id']){
			remind::set(Kohana::lang('o_manage.theme_not_exist'),'/manage/theme');
		}
		
		$this->template->content = new View("manage/theme_edit");
		$this->template->content->data = $data;
	}

	/**
	 * 查看模板大图
	 */
	public function view_theme_img($id)
	{
		$theme = Mytheme::instance($id)->get();
		$this->template = new View('template_blank');
		$this->template->content = new View('manage/theme_view');
		$this->template->content->theme = $theme;
	}
	
	/**
	 * 添加新模板
	 */
	public function add()
	{
		$this->template->content = new View("manage/theme_edit");
	}

	/**
	 * 配置模板可变内容
	 */
	public function config($id = 0)
	{
		$theme = Mytheme::instance($id)->get();
		if(!$theme['id']){
			remind::set(Kohana::lang('o_manage.theme_id_not_exist'),'/manage/theme');
		}
        
		$config_str = $theme['config'];
		$configs = array();
		$vals = array();
		$names = array();
		$types = array();
		$descriptions = array();
		if($config_str){
			$configs = unserialize($config_str);
			$vals = isset($configs['val']) ? $configs['val'] : null;
			$names = isset($configs['name']) ? $configs['name'] : null;
			$types = isset($configs['type']) ? $configs['type'] : null;
			$descriptions = isset($configs['desc']) ? $configs['desc'] : null;
		}
		
		$this->template->content = new View("manage/theme_config");
		$this->template->content->vals = $vals;
		$this->template->content->names = $names;
		$this->template->content->types = $types;
		$this->template->content->descriptions = $descriptions;
		$this->template->content->id = $id;
	}

	/**
	 * 设置模板
	 */
	public function set()
	{
		remind::set(Kohana::lang('o_manage.to_upgrade'),'/manage/theme');
	}

	/**
	 * 编辑模板可变全局变量
	 */
	public function config_edit($id = 0)
	{
		if(!$id){
			remind::set(Kohana::lang('o_manage.theme_id_not_exist'),'/manage/theme');
		}
		$flag = trim($this->input->get('key'));
		if(empty($flag)){
			remind::set(Kohana::lang('o_manage.theme_key_not_exist'),'/manage/theme');
		}
		//当前主题信息
		$theme = Mytheme::instance($id)->get();
		//当前主题的标签配置信息
		$configs = unserialize($theme['config']);
		
		if($_POST){
			//资源名称
			$name = trim($this->input
				->post('config_name'));
			//资源标记
			$flag = trim($this->input
				->post('config_flag'));
			//图片链接
			$url = trim($this->input
				->post('img_url'));
			//图片ALT标签
			$alt = trim($this->input
				->post('img_alt'));
			//资源类型
			$config_type = trim($this->input
				->post('config_type_value'));
			//资源文件最大大小(default:1M)
			$file_max_size = kohana::config('theme.file_max_size');
			$file_max_size = ($file_max_size > 0) ? $file_max_size : 1048576;
			
			//根据类型来选取相应的内容
			switch($config_type)
			{
				case 1:
					$val = trim($this->input
						->post('text_val'));
					break;
				case 2:
					if($val = $_FILES['img_val']['name']){
						$file_type = kohana::config('theme.image_file_type');
						$type = (count($file_type) > 0) ? $file_type : array(
							'jpg'
						);
						//判断文件类型
						if(!in_array(strtolower($this->fileext($_FILES['img_val']['name'])),$type)){
							remind::set(Kohana::lang('o_manage.pic_type_incorrect'),'/manage/theme/config_edit/' . $id . '?key=' . $flag);
						}
						$val = $_FILES['img_val']['name'];
						$file_size = filesize($_FILES['img_val']['tmp_name']);
						if($file_size > $file_max_size){
							remind::set(Kohana::lang('o_manage.pic_size_out_range'),'/manage/theme/config_edit/' . $id . '?key=' . $flag);
						}
					}
					break;
				case 3:
					$val = trim($this->input
						->post('text_val'));
					break;
			}
			//如果修改的是图片，则替换原有的图片
			if(!empty($val) && $config_type == 2){
				//$filename = $flag . '_' . $configs['val'][$flag];
				//$file = file_get_contents($_FILES['img_val']["tmp_name"]);
				//Storage_server::instance()->cache_theme($id,'images',$filename,$file);
                if($_FILES['img_val']['name']){
                    $AttService = AttService::get_instance($this->img_dir_name);
                    $img_id = $AttService->save_default_img($_FILES['img_val']["tmp_name"], $this->img_dir_name.$id.$flag);
                    if(!$img_id){
                        remind::set(Kohana::lang('o_product.phprpc_pic_save_failed'), '/manage/theme/config_edit/' . $id . '?key=' . $flag);
                    }
                }      
			}
			
			//内容可以不更改，如果为空则使用原有内容
			$configs['val'][$flag] = ($config_type == 2) ? $configs['val'][$flag] : $val;
			$configs['name'][$flag] = $name;
			$configs['type'][$flag] = $config_type;
			$configs['desc'][$flag] = array(
				'url' => $url, 
				'alt' => $alt
			);
			$data = array();
			$data['config'] = serialize($configs);
			if(Mytheme::instance($id)->edit($data)){
				remind::set(Kohana::lang('o_global.update_success'),'/manage/theme/config/' . $id,'success');
			} else{
				remind::set(Kohana::lang('o_global.update_error'),'/manage/theme/config_edit/' . $id . '?key=' . $flag);
			}
		
		}
		
		$data = array();
		$data['name'] = '';
		$data['val'] = '';
		$data['type'] = '';
		$data['key'] = '';
		if(isset($configs['val'][$flag])){
			$data['name'] = $configs['name'][$flag];
			$data['val'] = $configs['val'][$flag];
			$data['type'] = $configs['type'][$flag];
			$data['description'] = $configs['desc'][$flag];
			$data['key'] = $flag;
		}
		
		$this->template->content = new View("manage/theme_config_edit");
		$this->template->content->data = $data;
		$this->template->content->id = $id;
	}

	/**
	 * 模板增加可变内容
	 */
	public function config_add($id = 0)
	{
		if(!$id){
			remind::set(Kohana::lang('o_manage.theme_id_not_exist'),'/manage/theme');
		}
		//默认类型
		$type = ($this->input->get('type') > 0) ? $this->input->get('type') : 1;
		
		if($_POST){
            $val = '';
			//资源类型
			$config_type = trim($this->input->post('config_type'));
			//资源名称
			$name = trim($this->input->post('config_name'));
			//资源标识
			$flag = trim($this->input->post('config_flag'));
			//图片链接
			$url = trim($this->input->post('img_url'));
			//图片链接
			$alt = trim($this->input->post('img_alt'));
			//资源文件最大大小(default:1M)
			$file_max_size = kohana::config('theme.file_max_size');
			$file_max_size = ($file_max_size > 0) ? $file_max_size : 1048576;
			
			switch($config_type)
			{
				case 1:
					$val = trim($this->input->post('text_val'));
					break;
				case 2:
					$file_type = kohana::config('theme.image_file_type');
					$type = (count($file_type) > 0) ? $file_type : array(
						'jpg'
					);
					//判断文件类型
					if(!in_array(strtolower($this->fileext($_FILES['img_val']['name'])),$type)){
						remind::set(Kohana::lang('o_manage.pic_type_incorrect'),'/manage/theme/config_add/' . $id . '&type=2');
					}
					$val = $_FILES['img_val']['name'];
					$file_size = filesize($_FILES['img_val']['tmp_name']);
					if($file_size > $file_max_size){
						remind::set(Kohana::lang('o_manage.pic_size_out_range'),'/manage/theme/config_add/' . $id . '&type=2');
					}
					break;
				case 3:
					$val = $this->input->post('link_val');
					break;
			}
			
			if(empty($val)){
				remind::set(Kohana::lang('o_global.add_error'),'/manage/theme/config/' . $id);
			}
			
			$theme = Mytheme::instance($id)->get();
			$configs = empty($theme['config']) ? null : unserialize($theme['config']);
			if($configs && isset($configs['val'])){
			    foreach($configs['val'] as $k=>$v){						
			    	if($k == $flag){
			    		remind::set(Kohana::lang('o_manage.key_conflict'),'/manage/theme/config_add/' . $id);
			    	}
			    }
			}
			$configs['val'][$flag] = $val;
			$configs['name'][$flag] = $name;
			$configs['type'][$flag] = $config_type;
			$configs['desc'][$flag] = array(
				'url' => $url, 
				'alt' => $alt
			);
			$data = array();
			$data['config'] = serialize($configs);
			if(Mytheme::instance($id)->edit($data)){
				if($config_type == 2){
					//$file = file_get_contents($_FILES['img_val']["tmp_name"]);
					//$filename = $flag . '_' . $val;
					//Storage_server::instance()->cache_theme($id,'images',$filename,$file);
                    if($_FILES['img_val']['name']){
                        $AttService = AttService::get_instance($this->img_dir_name);
                        $img_id = $AttService->save_default_img($_FILES['img_val']["tmp_name"], $this->img_dir_name.$id.$flag);
                        if(!$img_id){
                            remind::set(Kohana::lang('o_product.phprpc_pic_save_failed'), '/manage/theme/config_edit/' . $id . '?key=' . $flag);
                        }
                    }                    
				}
				remind::set(Kohana::lang('o_global.add_success'),'/manage/theme/config/' . $id,'success');
			} else{
				remind::set(Kohana::lang('o_global.add_error'),'/manage/theme/config_add/' . $id);
			}
		}
		
		$this->template->content = new View("manage/theme_config_add");
		$this->template->content->type = $type;
		$this->template->content->id = $id;
	}

	/**
	 * 删除单个主题资源配置
	 */
	public function config_delete($id = 0)
	{
		if(!$id){
			remind::set(Kohana::lang('o_manage.theme_id_not_exist'),'/manage/theme');
		}
		$key = $this->input->get('key');
		if(empty($key)){
			remind::set(Kohana::lang('o_manage.theme_key_not_exist'),'/manage/theme');
		}
		/*
        $site = Mysite::instance()->get();
	    if(isset($site['theme_id']) && $site['theme_id']==$id)
	    {
	    	remind::set(Kohana::lang('o_manage.theme_config_delete_theme_used'),'/manage/theme');
	    }*/
		
		$theme = Mytheme::instance($id)->get();
		$configs = unserialize($theme['config']);
		$type = $configs['type'][$key];
		$val = $configs['val'][$key];
		unset($configs['val'][$key]);
		unset($configs['name'][$key]);
		unset($configs['type'][$key]);
		unset($configs['desc'][$key]);
		$data['config'] = serialize($configs);
		if(Mytheme::instance($id)->edit($data)){
			if($type == 2){
				//$filename = $key . '_' . $val;
				//Storage_server::instance()->delete_theme($id, 'images', $filename);
                $img_id = $this->img_dir_name.$id.strtr($key, array('_'=>''));
                Mytheme::instance()->clear_theme_img($img_id);
			}
			remind::set(Kohana::lang('o_global.delete_success'),'/manage/theme/config/' . $id,'success');
		} else{
			remind::set(Kohana::lang('o_global.delete_error'),'/manage/theme/config_add/' . $id);
		}
	}
	
	/**
	 * 删除模板
	 * @param int $id
	 */
	public function delete($id = 0)
	{
		// 初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try {
			if($id <= 0)
			{
				throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
			}
			
			$theme = Mytheme::instance($id);
			
			if($theme->delete($id))
			{
				throw new MyRuntimeException(Kohana::lang('o_manage.delete_theme_success'),403);
			} else {
				throw new MyRuntimeException(Kohana::lang('o_manage.manage_theme_delete_failed_by_site'),403);
			}
		} catch (MyRuntimeException $ex) {
			$return_struct['status'] = 0;
			$return_struct['code']   = $ex->getCode();
			$return_struct['msg']    = $ex->getMessage();
			//TODO 异常处理
			//throw $ex;
			if($this->is_ajax_request()) {
				$this->template = new View('layout/empty_html');
				$this->template->content = $return_struct['msg'];
			} else {
				$this->template->return_struct = $return_struct;

				$content = new View('info');
				$this->template->content = $content;
				/* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				/* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
			}
		}
	}
	
	/**
	 * 获取文件后缀名函数
	 */
	public function fileext($filename)
	{
		return substr(strrchr($filename,'.'),1);
	}

}
