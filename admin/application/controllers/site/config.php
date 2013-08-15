<?php
defined('SYSPATH') or die('No direct access allowed.');

class Config_Controller extends Template_Controller
{
    private $site_id =1;
    private $img_dir_name = 'site';
    //private $site_config = 'site_config';

	public function __construct()
	{
		parent::__construct();
		role::check('site_config');
	}
	
	/**
	 * 站点的基本配置项 
	 */
	public function index()
	{
		if($_POST)
		{
			$data = array('type'=>1);
			$site_title = $this->input->post('site_title');
			if(empty($site_title))
			{
				remind::set(Kohana::lang('o_site.title_cannot_null'),'site/config');
			}
			
			$data['site_title'] = $site_title;
			$data['site_email'] = $this->input->post('site_email');
			$data['name'] = $this->input->post('name');
			$data['domain'] = $this->input->post('domain');
			$data['logo'] = $this->input->post('logo');

			$data_detail = array();
			$data_detail['copyright']  = $this->input->post('copyright');
			$data_detail['twitter'] = $this->input->post('twitter');
			$data_detail['facebook'] = $this->input->post('facebook');
			$data_detail['youtube'] = $this->input->post('youtube');
			$data_detail['trustwave'] = $this->input->post('trustwave');
			$data_detail['macfee'] = $this->input->post('macfee');
			$data_detail['livechat'] = $this->input->post('livechat');
			$data_detail['head_code'] = $this->input->post('head_code');
			$data_detail['body_code'] = $this->input->post('body_code');
			$data_detail['index_code'] = $this->input->post('index_code');
			$data_detail['product_code'] = $this->input->post('product_code');
			$data_detail['payment_code'] = $this->input->post('payment_code');
			$data_detail['pay_code'] = $this->input->post('pay_code');
			$data_detail['register_mail_active'] = $this->input->post('register_mail_active');
            /*if(!Mysite::instance()->update_site_config(array_merge($data,$data_detail)))
            {
                remind::set(Kohana::lang('o_site.update_site_config_error'),'site/config','success');
            }*/
            if(Mysite::instance($this->site_id)->update($data) && Mysite_detail::instance($this->site_id)->update($data_detail))
            {
                remind::set(Kohana::lang('o_global.update_success'),'site/config','success');
            }
            else
            {
                remind::set(Kohana::lang('o_global.update_success'),'site/config','success');
            }
		}
        
		$this->template->content = new View("site/config");
        $this->template->content->data = array_merge(Mysite::instance()->get(),Mysite::instance()->detail());
	}

	/**
	 * 配置站点的robots.txt信息
	 */
	public function robots()
	{
		$site = Mysite::instance($this->site_id)->get();
		$site_detail = Mysite::instance($this->site_id)->detail();

		//无站点ID非法操作
		if(!$site['id'])
		{
			die(Kohana::lang('o_global.access_denied'));
		}
		//更新ROBOTS信息
		if($_POST)
		{
			$robots = $this->input->post('robots');
			if(empty($robots))
			{
				remind::set(Kohana::lang('o_site.robots_cannot_null'),'site/config/robots');
			}
			//更新站点robots信息
			$data = array();
			$data['robots'] = $robots;
			if(Mysite_detail::instance()->update_by_site_id($this->site_id,$data))
			{
				remind::set(Kohana::lang('o_global.update_success'),'site/config/robots','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),'site/config');
			}
		}
		$robots = isset($site_detail['robots'])?$site_detail['robots']:'';

		$this->template->content = new View("site/robots_edit");
		$this->template->content->robots = $robots;
	}

	/**
	 * 当前模板对应的配置
	 */
	public function theme()
	{
		//得到站点信息
		$site = Mysite::instance($this->site_id)->get();
		//得到站点的详细信息
		$site_detail = Mysite::instance($this->site_id)->detail();
        
		//得到站点所选主题的配置信息
		$theme = Mytheme::instance($site['theme_id'])->get();
        
		//主题的标签配置信息
		$theme_configs = empty($theme['config']) ? null : unserialize($theme['config']);
		//模板无配置信息
		if(empty($theme_configs) || count($theme_configs) <= 0)
		{
			//当前主题无可配置信息，清空站点内的主题的配置
			$data = array('theme_config'=>null);
			Mysite_detail::instance()->update_by_site_id($this->site_id,$data);
		    remind::set(Kohana::lang('o_site.config_not_exist'),'site/config');
		}
		//站点主题 的配置信息
		$site_theme_configs = empty($site_detail['theme_config']) ? null : unserialize($site_detail['theme_config']);
		//得到站点模板标签的配置情况，如果站点中无配置则使用全局模板的配置
		$configs = empty($site_theme_configs) ? $theme_configs : tool::multimerge($theme_configs,$site_theme_configs);

		$vals = array();
		$names = array();
		$types = array();
		$config = array();
		//if($configs)
		//{
			$vals = isset($configs['val']) ? $configs['val'] : null;
			$names = isset($configs['name']) ? $configs['name'] : null;
			$types = isset($configs['type']) ? $configs['type'] : null;
			$descriptions = isset($configs['desc']) ? $configs['desc'] : null;
		//}
		//else
		//{
			//remind::set(Kohana::lang('o_site.config_not_exist'),'site/config');
		//}
		if(!count($vals))
		{
			//remind::set(Kohana::lang('o_site.config_not_exist'),'site/config');
		}
		
		$this->template->content = new View("site/theme_config");
		$this->template->content->vals = $vals;
		$this->template->content->names = $names;
		$this->template->content->types = $types;
		$this->template->content->descriptions = $descriptions;
		$this->template->content->site_theme_configs = $site_theme_configs;
		$this->template->content->id = $site['theme_id'];
	}

	/**
	 * 单体配置站点的全局配置
	 */
	public function theme_single_config()
	{
		$return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
		$key = $this->input->get('key');
		if(empty($key))
		{
			$return_struct['code'] = 400;
			$return_struct['msg'] = Kohana::lang('o_global.bad_request');
			exit(json_encode($return_struct));
		}
		//站点信息
		$site = Mysite::instance($this->site_id)->get();
		if(!$site['id'])
		{
			$return_struct['code'] = 400;
			$return_struct['msg'] = Kohana::lang('o_global.access_denied');
			exit(json_encode($return_struct));
		}
		//站点详细信息
		$site_detail = Mysite::instance($this->site_id)->detail();
		//模板详情
		$theme = Mytheme::instance($site['theme_id'])->get();
		//模板配置
		$theme_configs = empty($theme['config']) ? array() : unserialize($theme['config']);
		//站点模板配置
		$site_theme_configs = empty($site_detail['theme_config']) ? array() : unserialize($site_detail['theme_config']);
		//得到站点模板标签的配置情况，如果站点中无配置则使用全局模板的配置
		$configs = empty($site_theme_configs) ? $theme_configs : tool::multimerge($theme_configs,$site_theme_configs);
		
		if(!count($configs))
		{
			$return_struct['code'] = 400;
			$return_struct['msg'] = Kohana::lang('o_global.access_denied');
			exit(json_encode($return_struct));
		}
		//判断请求，处理业务逻辑
		if(request::is_ajax())
		{
			$return_template = $this->template = new View('template_blank');
			//KEY对应的配置信息
			$data = array();
			$data['name'] = '';
			$data['val'] = '';
			$data['type'] = '';
			$data['key'] = '';
			if(isset($configs['name'][$key]))
			{
				$data['name'] = $configs['name'][$key];
				$data['val'] = htmlentities($configs['val'][$key]);
				$data['type'] = $configs['type'][$key];
				$data['description'] = $configs['desc'][$key];
				$data['key'] = $key;
			}
			else
			{
				$return_struct['code'] = 400;
				$return_struct['msg'] = Kohana::lang('o_global.bad_request');
				exit(json_encode($return_struct));
			}
			
			$this->template->content = new View('site/theme_single_config');
			$this->template->content->key = $key;
			$this->template->content->data = $data;
			$return_str = $return_template->render();
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = 'Success';
			$return_struct['content'] = $return_str;
			exit(json_encode($return_struct));
		}
		else
		{
			if($_POST)
			{
				//资源名称
				$name = $this->input->post('config_name');
				//图片链接
				$url = $this->input->post('img_url');
				//图片链接
				$alt = $this->input->post('img_alt');
				//资源类型
				$config_type = $this->input->post('config_type_value');
				
				//资源文件最大大小(default:1M)
				$file_max_size = kohana::config('theme.file_max_size');
				$file_max_size = ($file_max_size>0) ? $file_max_size : 1048576;
				//根据类型来选取相应的内容
				switch($config_type)
				{
					case 2:
						if($val = $_FILES['img_val']['name'])
						{
							$file_type = kohana::config('theme.image_file_type');
							$type = (count($file_type)>0) ? $file_type : array('jpg');
							//判断文件类型
							if(!in_array(strtolower(tool::fileext($_FILES['img_val']['name'])),$type))
							{
								remind::set(Kohana::lang('o_site.pic_type_incorrect'),'/manage/theme/config_edit/'.$id.'?key='.$key);
							}
							$val = $_FILES['img_val']['name'];
							$file_size = filesize($_FILES['img_val']['tmp_name']);
							if($file_size>$file_max_size)
							{
								remind::set(Kohana::lang('o_site.pic_size_out_range'),'/manage/theme/config_edit/'.$id.'?key='.$key);
							}
						}
						break;
					default:
						$val = $this->input->post('config_val');
						break;
				}
				
				//如果修改的是图片，则覆盖站点配置原有的图片
				if(!empty($val)&&$config_type==2)
				{
					//文件名的存放用 键值+文件名
					$filename = $key.'_'.$configs['val'][$key];
					$file = file_get_contents($_FILES['img_val']["tmp_name"]);
					//把图片存入站点图片资源
					Storage_server::instance()->cache_site_theme($site['id'],$site['theme_id'],'images',$filename,$file);
				}
				elseif($config_type==2)
				{
					if(!isset($site_theme_configs['val'][$key]))
					{
						$filename = $key.'_'.$configs['val'][$key];
						$file = Storage_server::instance()->get_theme($site['theme_id'],'images',$filename);
						Storage_server::instance()->cache_site_theme($site['id'],$site['theme_id'],'images',$filename,$file);
					}
				}
				//内容可以不更改，如果为空则使用原有内容
				$site_theme_configs['val'][$key] = $configs['val'][$key] = ($config_type==2)?$configs['val'][$key]:$val;
				$site_theme_configs['val'][$key] = stripslashes($site_theme_configs['val'][$key]);
				$site_theme_configs['val'][$key] = strip_tags($site_theme_configs['val'][$key],'<br><p><span><div>');
				$alt = htmlentities($alt);
				$site_theme_configs['name'][$key] = $configs['name'][$key] = $name;
				$site_theme_configs['type'][$key] = $configs['type'][$key] = $config_type;
				$site_theme_configs['desc'][$key] = $configs['desc'][$key] = array('url'=>$url,'alt'=>$alt);
				//var_dump($site_theme_configs);exit;
				$data['theme_config'] = serialize($site_theme_configs);
				
				if(Mysite_detail::instance()->update_by_site_id($this->site_id,$data))
				{
					remind::set(Kohana::lang('o_global.update_success'),'site/config/theme','success');
				}
				else
				{
					remind::set(Kohana::lang('o_global.update_error'),'site/config');
				}
			}
			else
			{
				die(Kohana::lang('o_global.access_denied'));
			}
		}
	}
	
	/**
	 * 上传新Logo
	 */
	public function logo_upload()
	{
		$return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
		if($_POST)
		{
			if (!isset($_FILES["img_val"]) || !is_uploaded_file($_FILES["img_val"]["tmp_name"]) || $_FILES["img_val"]["error"] != 0)
			{
				remind::set(Kohana::lang('o_global.illegal_data'),'site/config');
			}
			$site = Mysite::instance($this->site_id)->get();
			//Logo文件名
			$filename = $_FILES['img_val']['name'];
			$file_ext = tool::fileext($filename);
			//资源文件最大大小(default:1M)
			$file_max_size = kohana::config('theme.file_max_size');
			$file_max_size = ($file_max_size>0) ? $file_max_size : 1048576;
			$type = array('gif','png','jpg','jpeg');
			//判断文件类型
			if(!in_array(strtolower($file_ext),$type))
			{
				remind::set(Kohana::lang('o_site.file_type_error'),'site/config');
			}
			$val = $_FILES['img_val']['name'];
			$file_size = filesize($_FILES['img_val']['tmp_name']);
			if($file_size>$file_max_size)
			{
				remind::set(Kohana::lang('o_site.pic_size_out_range'),'site/config');
			}
			//$filename = 'logo.' . $file_ext;
			//$file = file_get_contents($_FILES['img_val']["tmp_name"]);
			//Storage_server::instance()->cache_site($site['id'],$filename,$file);
            $AttService = AttService::get_instance($this->img_dir_name);
            $AttService->default_img_type = 'gif';
            $img_id = $AttService->save_default_img($_FILES['img_val']["tmp_name"], 'logo');
            if(!$img_id){
                remind::set(Kohana::lang('o_product.phprpc_pic_save_failed'),'site/config');
            }
            
			//更新数据库标识
    		$data = Mysite::instance()->get();
			$data['logo'] = $AttService->get_img_url($img_id,0,0);
            $data['logo_desc'] = $this->input->post('logo_desc');
			if(Mysite::instance()->update_site_config($data))
			{
				remind::set(Kohana::lang('o_site.logo_success_upload'),'site/config','success');
			}
			else
			{
				remind::set(Kohana::lang('o_site.logo_error_upload'),'site/config');
			}
		}
		if(request::is_ajax())
		{
			$site = Mysite::instance()->get();
			//VIEW
			$return_template = $this->template = new View('template_blank');
			$this->template->content = new View('site/config_logo_upload');
			$this->template->content->data = $site;
			$return_str = $return_template->render();
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = 'Success';
			$return_struct['content'] = $return_str;
			exit(json_encode($return_struct));
		}
		else
		{
			$return_struct['code'] = 400;
			$return_struct['msg'] = Kohana::lang('o_global.access_denied');
			exit(json_encode($return_struct));
		}
	}

	/**
	 * 删除站点LOGO
	 */
	public function delete_logo()
	{
		//更新数据库标识
		$data = Mysite::instance()->get();
		$data['logo'] = NULL;
			
		if(Mysite::instance()->update_site_config($data))
		{
			$img_id = 'logo';
            AttService::get_instance($this->img_dir_name)->delete_img($img_id, false);
			//Storage_server::instance()->delete_site($this->site_id, $filename);
			remind::set(Kohana::lang('o_global.delete_success'),'site/config','success');
		}
		else
		{
			remind::set(Kohana::lang('o_global.delete_error'),'site/config');
		}
	}
	
	/**
	 * 配置站点的支付成功代码
	 */
	public function pay_code()
	{
		// 初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try {
			/* 管理员才能进行代码更新操作 */
			if($this->manager_is_admin == 1)
			{
				$site = Mysite::instance($this->site_id)->get();
				$site_detail = Mysite::instance($this->site_id)->detail();
		
				//无站点ID非法操作
				if(!$site['id'])
				{
					throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),401);
				}
				//更新pay_code信息
				if($_POST)
				{
					$pay_code = $this->input->post('pay_code');
					if(empty($pay_code))
					{
						throw new MyRuntimeException(Kohana::lang('o_site.site_config_payment_success_cannot_submit'),402);
					}
					//更新站点robots信息
					$data = array();
					$data['pay_code'] = $pay_code;
					if(Mysite_detail::instance()->update_by_site_id($this->site_id,$data))
					{
						remind::set(Kohana::lang('o_global.update_success'),'site/config/pay_code','success');
					}
					else
					{
						remind::set(Kohana::lang('o_global.update_error'),'site/config/pay_code');
					}
				}
				$pay_code = isset($site_detail['pay_code'])?$site_detail['pay_code']:'';
		
				$this->template->content = new View("site/pay_code");
				$this->template->content->pay_code = $pay_code;
			} else {
				throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
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

}