<?php
defined('SYSPATH') or die('No direct access allowed.');

class Country_Controller extends Template_Controller{
	protected $current_flow = 'country';

	public function __construct()
	{
		parent::__construct();
		/* 权限验证 国家管理 */
		role::check('site_country');
	}
	
	/*
	 * 国家列表
	 */
	public function index()
	{
		// 初始化国家结构体
		$country_query_struct = array(
			'where' => array(
			), 
			'orderby' => array(
				'id' => 'DESC'
			)
		);
		
		$this->template->content = new View("site/country_manage_list");
		
		//列表排序
		$orderby_arr = array(
			0 => array(
				'id' => 'DESC'
			), 
			1 => array(
				'id' => 'ASC'
			), 
			2 => array(
				'iso_code' => 'ASC'
			), 
			3 => array(
				'iso_code' => 'DESC'
			),
			4 => array(
				'name' => 'ASC'
			), 
			5 => array(
				'name' => 'DESC'
			),
			6 => array(
				'name_manage' => 'ASC'
			), 
			7 => array(
				'name_manage' => 'DESC'
			),
			8 => array(
				'position' => 'ASC'
			), 
			9 => array(
				'position' => 'DESC'
			)
		);
		$orderby = controller_tool::orderby($orderby_arr);
		
		if(isset($orderby) && !empty($orderby))
		{
			$country_query_struct['orderby'] = $orderby;
		}
		//每页显示条数
		$per_page = controller_tool::per_page();
		$country_query_struct['limit']['per_page'] = $per_page;
		
		//调用分页
		$this->pagination = new Pagination(array(
			'total_items' => Mycountry::instance()->query_count($country_query_struct), 
			'items_per_page' => $per_page
		));
		
		$country_query_struct['limit']['offset'] = $this->pagination->sql_offset;
		
		//调用列表
		$this->template->content->country_list = Mycountry::instance()->query_assoc($country_query_struct);
	}
	
	/*
	 * 加载国家设定的模板
	 */
	public function set()
	{
		$country_manage_service = Country_manageService::get_instance();
		$ids = array();      

		//初始化请求结构体
		$query_struct = array (
			'where' => array (
				'active'   =>  1
			),
			'orderby' => array(
				'name'     =>  'ASC'
			)
		);

		$country_manages = $country_manage_service->index($query_struct);

		$request_struct = array(
			'where'     => array(
				'active'   => 1
			)
		);
		$site_countries = Mycountry::instance()->lists($request_struct);

		if(!empty($site_countries))
		{
			foreach($site_countries as $val)
			{
				$ids[$val['country_manage_id']] = $val['country_manage_id'];
			}
			
			foreach($country_manages as $key=>$rs)
			{
				if(in_array($rs['id'], array_keys($ids)))
				{
					unset($country_manages[$key]);
				}
			}
		}

		$this->template->content = new View("site/country_set");
		$this->template->content->country_manages = $country_manages;
	}
	
	/*
	 * 保存国家数据
	 */
	public function save()
	{
		$request_data = $this->input->post();
		//流程
		$submit_target = intval($this->input->post('submit_target'));
		if($_POST) 
		{
			//标签过滤
			tool::filter_strip_tags($request_data);

			$country = Mycountry::instance();			
			$country_manage_service = Country_manageService::get_instance();
			$country_manage = $country_manage_service->get($request_data['country_manage_id']);
			if(!$country_manage['id']) 
			{
				remind::set(Kohana::lang('o_manage.country_not_exist'),'manage/country_manage');
			}

			$set_data = array(
				'country_manage_id'   =>  $country_manage['id'],
				'name'                =>  $country_manage['name'],
				'name_manage'         =>  $country_manage['name_manage'],
				'iso_code'            =>  $country_manage['iso_code'],
				'active'              =>  1,
				'position'            =>  0
	 		); 
	 		
			if($country->exist($set_data))
			{
				remind::set(Kohana::lang('o_site.country_has_exist'),'site/country/set','error');
			} 		
			if($return_data['id'] = $country->add($set_data))
			{
				//判断添加成功去向
				switch($submit_target)
				{
					case 1:
						remind::set(Kohana::lang('o_global.add_success'),'site/country/set','success');
					default:
						remind::set(Kohana::lang('o_global.add_success'),'site/country/','success');
				}
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),'site/country/set');
			}
		}
		else
		{
			remind::set(Kohana::lang('o_global.add_error'),'site/country/set');
		}
	}
	
	/*
	 * 编辑国家模板
	 */
	public function get($id)
	{			
		$ids = array();
		$is_change = 0; 
		$is_delete = 0; 
		if(!$id)
		{
			remind::set(Kohana::lang('o_global.bad_request'),'site/country');
		}
		$country = Mycountry::instance($id)->get();
		if(!$country['id']) 
		{
			remind::set(Kohana::lang('o_manage.country_not_exist'),'site/country');
		}    

		//初始化请求结构体
		$query_struct = array (
			'where' => array (
				'active'   =>  1
			),
			'orderby' => array(
				'name'     =>  'ASC'
			)
		);

		$country_manages = Country_manageService::get_instance()->index($query_struct);
		
		$request_struct = array(
			'where'     => array(
				'active'   => 1
			)
		);
		$site_countries = Mycountry::instance()->lists($request_struct);

		foreach($site_countries as $key=>$val)
		{
			if($country['country_manage_id'] != $val['country_manage_id'])
			{
				$ids[$val['country_manage_id']] = $val['country_manage_id'];	
			}					
		}
		
		foreach($country_manages as $key=>$rs)
		{
			if(in_array($rs['id'], array_keys($ids)))
			{
				unset($country_manages[$key]);
			}
		}
		
		/*
		 * 获取此条信息对应国家管理中的信息
		 */
		$country_manage = Mycountry::instance()->get_country_by_id($country['country_manage_id']);
		
		if(empty($country_manage['id']))
		{
			$is_delete = 1;
		}

		if(!empty($country_manage['id']) && ($country_manage['name'] != $country['name'] || $country_manage['name_manage'] != $country['name_manage']
									 || $country_manage['iso_code'] != $country['iso_code']))
		{
			$is_change = 1;
		}
		$this->template->content = new View("site/country_get");
		$this->template->content->country_manages = $country_manages;
		$this->template->content->country = $country;
		$this->template->content->country_manage = $country_manage;
		$this->template->content->is_change = $is_change;
		$this->template->content->is_delete = $is_delete;
	}
	
	/*
	 * 保存编辑的信息
	 */
	public function do_save()
	{
		$request_data = $this->input->post();
		if($_POST) 
		{
			//标签过滤
			tool::filter_strip_tags($request_data);
			if(!$request_data['id'])
			{
				remind::set(Kohana::lang('o_global.bad_request'),'site/country');
			}
			$data = Mycountry::instance($request_data['id'])->get();
			if(empty($data) || !isset($data))
			{
				remind::set(Kohana::lang('o_global.bad_request'),'site/country');
			}
			$set_data = array(
							  'id'       => $data['id']
						);
			$country_manage = Mycountry::instance()->get_country_by_id($data['country_manage_id']);

			//假如没有选择其他项
			if(!empty($country_manage) && $data['country_manage_id'] == $request_data['country_manage_id'])
			{
				$set_data['country_manage_id']   =  $country_manage['id'];
				$set_data['name']                =  $country_manage['name'];
				$set_data['name_manage']         =  $country_manage['name_manage'];
				$set_data['iso_code']            =  $country_manage['iso_code'];
			}
			else
			{
			    $country_manage_service = Country_manageService::get_instance();
				$country_manage_new = $country_manage_service->get($request_data['country_manage_id']);
				$set_data['country_manage_id']   =  $country_manage_new['id'];
				$set_data['name']                =  $country_manage_new['name'];
				$set_data['name_manage']         =  $country_manage_new['name_manage'];
				$set_data['iso_code']            =  $country_manage_new['iso_code'];
					 		
				if(Mycountry::instance()->exist($set_data))
				{
					remind::set(Kohana::lang('o_site.country_has_exist'),'site/country/','error');
				} 
			}
			Mycountry::instance()->edit($set_data);
			remind::set(Kohana::lang('o_global.update_success'),'site/country/','success');	
		}
		else
		{
			remind::set(Kohana::lang('o_global.update_error'),'site/country/');
		}
	}
	
	/*
	 * 获取相应国家的数据 
	 */
	public function get_country_data()
	{
		$return_struct = array();
		if($this->is_ajax_request()==FALSE)
		{
			die('error');
		}
		$id = $this->input->get('country_manage_id');
		$country = Mycountry::instance()->get_country_by_id($id);
		if(is_array($country) && !empty($country['id']))
		{
			$return_struct['status']           = 1;
			$return_struct['code']             = 200;
			$return_struct['data']             = $country;
		}
		header('Content-Type: text/javascript; charset=UTF-8');
		exit(json_encode($return_struct));
	}
	
	/*
	 * 删除
	 */
	public function delete($id)
	{
		if(!$id)
		{
			remind::set(Kohana::lang('o_global.bad_request'),'site/country');
		}
		if(Mycountry::instance($id)->delete())
		{
			//删除国家对应的物流
			Delivery_countryService::get_instance()->delete_delivery_by_country($id);
			remind::set(Kohana::lang('o_global.delete_success'),'site/country','success');
		} 
		else
		{
			remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
		}
	}
	
    /**
     * 批量删除
     */
    public function do_batch_delete()
    {
        $country_ids = $this->input->post('country_ids');
            
        if(is_array($country_ids) && count($country_ids) > 0)
        {
            /* 删除失败的物流 */
            $failed_country_names = '';
            /* 执行操作 */
            foreach($country_ids as $country_id)
            {
                if(!Mycountry::instance($country_id)->delete())
                {
                    $failed_country_names .= ' | ' . $country_id;
                }
                else
                {
                	Delivery_countryService::get_instance()->delete_delivery_by_country($country_id);
                }
            }
            if(empty($failed_country_names))
            {
            	remind::set(Kohana::lang('o_global.delete_success'), 'site/country', 'success');
            }
            else
            {
                $failed_country_names = trim($failed_country_names,' | ');
                remind::set(Kohana::lang('o_global.delete_error').$failed_country_names, 'site/country');
            }
        }
        else
        {
             remind::set(Kohana::lang('o_global.delete_error'), 'site/country');
        }
    }

	/**
	 * 国家列表
	public function index()
	{
		// 初始化国家结构体
		$country_query_struct = array(
			'where' => array(
				'site_id' => $this->site_id
			), 
			'orderby' => array(
				'position' => 'DESC'
			)
		);
		
		$this->template->content = new View("site/country_list");
		
		//列表排序
		$orderby_arr = array(
			0 => array(
				'id' => 'DESC'
			), 
			1 => array(
				'id' => 'ASC'
			), 
			2 => array(
				'position' => 'ASC'
			), 
			3 => array(
				'position' => 'DESC'
			)
		);
		$orderby = controller_tool::orderby($orderby_arr);
		
		//每页显示条数
		$per_page = controller_tool::per_page();
		$country_query_struct['limit']['per_page'] = $per_page;
		
		//调用分页
		$this->pagination = new Pagination(array(
			'total_items' => Mycountry::instance()->query_count($country_query_struct), 
			'items_per_page' => $per_page
		));
		
		$country_query_struct['limit']['offset'] = $this->pagination->sql_offset;
		
		//调用列表
		$this->template->content->country_list = Mycountry::instance()->query_assoc($country_query_struct);
	}*/

	/**
	 * 修改国家
	 
	function edit($id = 0)
	{
		if(!$id){
			die(Kohana::lang('o_global.bad_request'));
		}
		
		if($_POST){
			//标签过滤
			tool::filter_strip_tags($_POST);
			
			if(Mycountry::instance($id)->edit($_POST)){
				//$country = Mycountry::instance($id)->get();
				//在费除国家时，删除国家和物流的关系
				//if($country['active'] == 0){
					//Mycarrier_country::instance()->delete_by_country_id($country['site_id'],$country['id']);
				//}
				
				remind::set(Kohana::lang('o_global.update_success'),'site/country','success');
			} else{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer('/'),'error');
			}
		}
		
		$this->template->content = new View("site/country_edit");
		
		$data = Mycountry::instance($id)->get();
		
		$this->template->content->data = $data;
		$this->template->content->site_info = Mysite::instance($data['site_id'])->get();
	}
	*/

	/**
	 * 添加新用户
	 
	function add()
	{
		if($_POST){
			//标签过滤
			tool::filter_strip_tags($_POST);
			
			$site_next_flow = site::site_next_flow($this->current_flow);
			$submit_target = intval($_POST['submit_target']);
			
			$data = $_POST;
			$country = Mycountry::instance();
			if($country->exist($data)){
				remind::set(Kohana::lang('o_site.country_has_exist'),'site/country/add','error');
			}
			
			unset($data['id']);
			$data['active'] = 1;
			if($country->add($data)){
				//判断添加成功去向
				switch($submit_target)
				{
					case 1:
						remind::set(Kohana::lang('o_global.add_success'),'site/country/add','success');
					case 2:
						remind::set(Kohana::lang('o_global.add_success'),$site_next_flow['url'],'success');
					default:
						remind::set(Kohana::lang('o_global.update_success'),'site/country','success');
				}
			} else{
				remind::set(Kohana::lang('o_global.add_error'),'site/country/add','error');
			}
		}
		$this->template->content = new View("site/country_add");
		$this->template->content->site_list = Mysite::instance()->select_list($this->site_id);
	}
	*/

	/**
	 * 删除
	 
	function do_delete($id)
	{
		// 权限验证 物流管理 
		role::check('site_country_delete',$this->site_id);
		
		$site_id_list[] = $this->site_id;
		if(!$id){
			die(Kohana::lang('o_global.bad_request'));
		}
		$country = Mycountry::instance($id)->get();
		if(Mycountry::instance($id)->delete()){
			//删除国家对应的物流
			Delivery_countryService::get_instance()->delete_delivery_by_country($country['site_id'], $country['id']);
			remind::set(Kohana::lang('o_global.delete_success'),'site/country','success');
		} else{
			remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
		}
	}
	*/

	/**
	 * 设定菜单的排序
	 */
	public function set_order()
	{
		//初始化返回数组
		$return_struct = array(
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array()
		);
		$request_data = $this->input
			->get();
		$id = isset($request_data['id']) ? $request_data['id'] : '';
		$order = isset($request_data['order']) ? $request_data['order'] : '';
		if(empty($id) || (empty($order) && $order != 0)){
			$return_struct['msg'] = Kohana::lang('o_global.bad_request');
			exit(json_encode($return_struct));
		}
		if(!is_numeric($order) || $order < 0){
			$return_struct['msg'] = Kohana::lang('o_global.position_rule');
			exit(json_encode($return_struct));
		}
		if(Mycountry::instance()->set_order($id,$order)){
			$return_struct = array(
				'status' => 1, 
				'code' => 200, 
				'msg' => Kohana::lang('o_global.position_success'), 
				'content' => array(
					'order' => $order
				)
			);
		} else{
			$return_struct['msg'] = Kohana::lang('o_global.position_error');
		}
		exit(json_encode($return_struct));
	}

	/**
	 * 批量删除国家
	 */
	public function batch_delete()
	{
		//初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try{
			$country_ids = $this->input->post('country_ids');
			
			if(is_array($country_ids) && count($country_ids) > 0){
				/* 删除失败的 */
				$failed_country_names = '';
				/* 执行操作 */
				foreach($country_ids as $country_id){
					if(!Mycountry::instance($country_id)->delete()){
						$failed_country_names .= ' | ' . $country_id;
					}else{
						//删除国家对应的物流
						Delivery_countryService::get_instance()->delete_delivery_by_country($country_id);
					}
				}
				if(empty($failed_country_names)){
					throw new MyRuntimeException(Kohana::lang('o_site.delete_country_success'),403);
				} else{
					/* 中转提示页面的停留时间 */
					$return_struct['action']['time'] = 10;
					$failed_country_names = trim($failed_country_names,' | ');
					throw new MyRuntimeException(Kohana::lang('o_site.delete_country_error',$failed_country_names),403);
				}
			} else{
				throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
			}
		}
		catch(MyRuntimeException $ex){
			$return_struct['status'] = 0;
			$return_struct['code'] = $ex->getCode();
			$return_struct['msg'] = $ex->getMessage();
			//TODO 异常处理
			//throw $ex;
			if($this->is_ajax_request()){
				$this->template = new View('layout/empty_html');
				$this->template->content = $return_struct['msg'];
			} else{
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
