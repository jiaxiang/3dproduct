<?php defined('SYSPATH') OR die('No direct access allowed.');

class Manager_Controller extends Template_Controller {
	const CURRENT_FLOW = 'manager';
	public $site_ids;

	public function __construct()
	{
		parent::__construct();
        //zhu modify
		//$this->site_ids = role::check('manage_merchant');
	}

	/*
	 * zhu add 检查管理帐号权限的异常：账号不存在、屏蔽root账号和自身账号、非子账号
	 */
	private function _check_manager($id)
	{
        $manager = Mymanager::instance($id)->get();
        if(!$manager['id'])
        {
        	remind::set(Kohana::lang('o_manage.user_not_exist'),'manage/manager');
        }
        elseif (role::is_root($manager['username']))
        {
        	remind::set(Kohana::lang('o_global.access_root_denied'),'manage/manager');
        }
        elseif ($manager['id']==$this->manager_id)
        {
        	remind::set(Kohana::lang('o_manage.self_account_not_do'),'manage/manager');
        }
        elseif (!role::is_root())
        {
        	$flag_p = false;
        	$manager_subs = Mymanager::instance()->subs($this->manager_id);
        	foreach($manager_subs as $managers_key=>$managers_value)
        	{
        		if($managers_value['id'] == $manager['id'])
        		{
        			$flag_p = true;
        			break;
        		}
        	}
        	if($flag_p==false)
        	{
        	    remind::set(Kohana::lang('o_global.permission_enough'),'manage/manager');
        	}
        }	
	}
		
	/**
	 * 后台用户列表
	 */
	public function index() {
        //zhu add
        role::check('manage_merchant');
		$query_struct = array();
		
		/* 管理员才能显示分页 */
		$show_page = false;
		
		//zhu modify 
		if(role::is_root())
		{
			$show_page = true;
			$per_page = controller_tool::per_page();
			$this->pagination = new Pagination(
				array(
					'total_items' => Mymanager::instance()->count($query_struct),
					'items_per_page' => $per_page,
				)
			);
			$order_by = array('id'=>'DESC');
		   	$managers = Mymanager::instance()->managers($query_struct,$order_by,$per_page,$this->pagination->sql_offset);		   	
		}
		else
		{
			$managers = Mymanager::instance()->subs($this->manager_id);
		}

		foreach($managers as $managers_key=>$managers_value)
		{
			/* 账号列表中把自己的账号排除、root帐号排除 zhu */
			if($managers_value['id'] == $this->manager_id || role::is_root($managers_value['username']))
			{
				unset($managers[$managers_key]);
				continue;
			}
			
			$parent_email = Mymanager::instance($managers_value['parent_id'])->get('email');
			$managers[$managers_key]['parent_email'] = empty($parent_email) ? '无' : $parent_email;
		}
		
		$this->template->content = new View("manage/manager_list");
		$this->template->content->managers = $managers;
		$this->template->content->show_page = $show_page;
	}

	/**
	 * 添加新商户
	 */
	public function add()
	{
		/* 只允许root和管理员执行 */
        if(!role::is_root())
        {
			role::check('manage_merchant');
            $manager = Mymanager::instance($this->manager_id)->get();            
            if(!$manager['is_admin'])
            {
                remind::set(Kohana::lang('o_manage.only_admin_do'),'manage/manager');
            }
		}
		
		$next_flow = site::next_flow(self::CURRENT_FLOW);
		if($_POST)
		{
            //echo "<pre>";print_r($this->input->post());die();
		    $role_id = intval($this->input->post('role_id'));
		    $submit_target = intval($this->input->post('submit_target'));

            //标签过滤
            tool::filter_strip_tags($_POST);
			
			$password1 = $this->input->post('password1');
			$password2 = $this->input->post('password2');
			$username  = $this->input->post('username');
			$email     = $this->input->post('email');

			$manager = Mymanager::instance()->get_by_username($username);
			if($manager['id'])
			{
				remind::set(Kohana::lang('o_manage.name_has_exist'),'manage/manager/add');
			}

			$manager = Mymanager::instance()->get_by_email($email);
			if($manager['id'])
			{
				remind::set(Kohana::lang('o_manage.email_has_exist'),'manage/manager/add');
			}

			//验证两次密码是否相同
			if($password1 <> $password2)
			{
				remind::set(Kohana::lang('o_manage.two_pwd_not_valid'),'manage/manager/add');
			}
			else
			{
				$_POST['password'] = $password1;
			}

			$_POST['parent_id'] = $this->manager_id;

			if(Mymanager::instance()->add($_POST)) {
				//判断添加成功去向
				switch($submit_target)
				{
				case 1:
					remind::set(Kohana::lang('o_global.add_success'),'manage/manager/add','success');
				case 2:
					remind::set(Kohana::lang('o_global.add_success_into') . $next_flow['name'],$next_flow['url'],'success');
				case 3:
				default:
                    if($role_id==0)
                    {
					    $id = Mymanager::instance()->get('id');
					    remind::set(Kohana::lang('o_global.add_success'),'manage/manager/rule/'.$id,'success');
                    }
					remind::set(Kohana::lang('o_global.add_success'),'manage/manager','success');
				}
			}
			else
			{
				$error = Mysite::instance()->error();
				remind::set(Kohana::lang('o_global.add_error').$error,'manage/manager/add');
			}
		}
		$where = array();
		$where['type'] = 0;
        $where['active'] = 1; //zhu add
		//在添加商家帐号时候只能显示一级的用户级别
		$where['level_depth'] = 1;
		$roles = Myrole::instance()->roles($where);

		$this->template->content = new View("manage/manager_add");
		$this->template->content->roles = $roles;
		//show next flow button
		$this->template->content->next_flow_btn = site::next_flow_btn(self::CURRENT_FLOW);
	}

	/*zhu add 商户只能添加一级子账号，如果上线已经是商户了就不能添加子账号 */
    private function _parent_is_admin($manager_id){
		$manager = Mymanager::instance($manager_id)->get();
		
		/* 商户只能添加一级子账号，如果上线已经是商户了就不能添加子账号 */
		if($manager['parent_id'] > 0)
		{
			$parent_manager = Mymanager::instance($manager['parent_id'])->get();
			if($parent_manager['is_admin'] == 0)
			{
				remind::set(Kohana::lang('o_manage.child_not_son_account'),'manage/manager');
			}
		}
        return $manager;
    }

	/**
	 * 添加子帐号
	 */
	public function child()
	{
		$this->site_ids = role::check('manage_manager_child');

		/* 商户只能添加一级子账号，如果上线已经是商户了就不能添加子账号 */
        //zhu add
        $manager = $this->_parent_is_admin($this->manager_id);

		/*$manager = Mymanager::instance($this->manager_id)->get();
		if($manager['parent_id'] > 0)
		{
			$parent_manager = Mymanager::instance($manager['parent_id'])->get();
			if($parent_manager['is_admin'] == 0)
			{
				remind::set(Kohana::lang('o_manage.child_not_son_account'),'manage/manager');
			}
		}*/

		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST);

		    $submit_target = intval($this->input->post('submit_target'));
			$password1 = $this->input->post('password1');
			$password2 = $this->input->post('password2');
			$username  = $this->input->post('username');
			$email     = $this->input->post('email');
			$role_id   = $this->input->post('role_id');

			$manager_temp = Mymanager::instance()->get_by_username($username);
			if($manager_temp['id'])
			{
				remind::set(Kohana::lang('o_manage.name_has_exist'),'manage/manager');
			}

			$manager_temp = Mymanager::instance()->get_by_email($email);
			if($manager_temp['id'])
			{
				remind::set(Kohana::lang('o_manage.email_has_exist'),'manage/manager/add_manager');
			}

			//验证两次密码是否相同
			if($password1 <> $password2)
			{
				remind::set(Kohana::lang('o_manage.two_pwd_not_valid'),'manage/manager/add_manager');
			}
			else
			{
				$_POST['password'] = $password1;
			}

			$_POST['parent_id'] = $this->manager_id;
			$_POST['site_num'] = $manager['site_num'];
			//zhu modify
			//$_POST['is_admin'] = 1;

			if(Mymanager::instance()->add($_POST)) {
				//判断添加成功去向
				switch($submit_target)
				{
					case 1:
						remind::set(Kohana::lang('o_global.add_success'),'manage/manager/child','success');
				    case 2:
					default:
                        if($role_id==0)
                        {
					        $id = Mymanager::instance()->get('id');
					        remind::set(Kohana::lang('o_global.add_success'),'manage/manager/rule/'.$id,'success');
                        }
						remind::set(Kohana::lang('o_global.add_success'),'manage/manager','success');
				}
			}
			else
			{
				$error = Mysite::instance()->error();
				remind::set(Kohana::lang('o_global.add_error').$error,'manage/manager/child');
			}
		}
		//zhu modify
		$roles = array();
		$role_id = Mymanager::instance($this->manager_id)->get('role_id');
		//$roles = Myrole::instance()->childrens($role_id);
		if($role_id>0)
		{
		    $roles = Myrole::instance()->childrens($role_id);
		}

		$this->template->content = new View("manage/manager_child");
		$this->template->content->roles = $roles;
	}

	/**
	 * 添加管理员
	 */
	public function add_manager()
	{
		//zhu add 只允许root执行
        //if(!role::is_root())
        //{
        //	remind::set(Kohana::lang('o_manage.only_root_do'),'manage/manager');
        //}
		role::check('manage_admin_account');
		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST);
			
		    $submit_target = intval($this->input->post('submit_target'));
			$password1 = $this->input->post('password1');
			$password2 = $this->input->post('password2');
			$username  = $this->input->post('username');
			$email     = $this->input->post('email');
		    $role_id = intval($this->input->post('role_id'));

			$manager = Mymanager::instance()->get_by_username($username);
			if($manager['id'])
			{
				remind::set(Kohana::lang('o_manage.name_has_exist'),'manage/manager/add_manager');
			}

			$manager = Mymanager::instance()->get_by_email($email);
			if($manager['id'])
			{
				remind::set(Kohana::lang('o_manage.email_has_exist'),'manage/manager/add_manager');
			}

			//验证两次密码是否相同
			if($password1 <> $password2)
			{
				remind::set(Kohana::lang('o_manage.two_pwd_not_valid'),'manage/manager/add_manager');
			}
			else
			{
				$_POST['password'] = $password1;
			}

			/* 父账号，即添加管理员账号的账号 */
			$_POST['parent_id'] = $this->manager_id;
			$_POST['site_num'] = 100;
			$data = $_POST;

			if(Mymanager::instance()->adminadd($data)) {
				//判断添加成功去向 zhu add case 2:指定权限
				switch($submit_target)
				{
				case 1:
					remind::set(Kohana::lang('o_global.add_success'),'manage/manager/add_manager','success');
				case 2:
				default:
                    if($role_id==0)
                    {
					    $id = Mymanager::instance()->get('id');
					    remind::set(Kohana::lang('o_global.add_success'),'manage/manager/rule/'.$id,'success');
                    }
					remind::set(Kohana::lang('o_global.add_success'),'manage/manager','success');
				}
			}
			else
			{
				$error = Mysite::instance()->error();
				remind::set(Kohana::lang('o_global.add_error').$error,'manage/manager/add_manager');
			}
		}
		$where = array();
		$where['type'] = 1;
		$where['active'] = 1; //zhu add
		$roles = Myrole::instance()->roles($where);

		$this->template->content = new View("manage/manager_adminadd");
		$this->template->content->roles = $roles;
	}	

	/**
	 * 编辑商户信息
	 */
	public function edit()
	{
		$id = intval($this->uri->segment('id'));
		$manager = Mymanager::instance($id)->get();
		//zhu add
		$this->_check_manager($manager['id']);

		if($_POST)
		{
            tool::filter_strip_tags($_POST);
            
			$password1 = $this->input->post('password1');
			$password2 = $this->input->post('password2');
			$email     = $this->input->post('email');
			$username  = $this->input->post('username');
		    $role_id = intval($this->input->post('role_id'));
			$submit_target = intval($this->input->post('submit_target'));//zhu add			

			if($manager['email'] <> $email)
			{
				$data = Mymanager::instance()->get_by_email($email);
				if($data['id'])
				{
					remind::set(Kohana::lang('o_manage.email_has_exist'),'manage/manager/add');
				}
			}
			
			/* 判断用户名是否重复 */
			if(Mymanager::instance()->username_exist($username,$id))
			{
				remind::set(Kohana::lang('o_manage.username_can_not_repeat'),'manage/manager/edit/id/' . $id);
			}

			//验证两次密码是否相同
			if($password1 <> $password2)
			{
				remind::set(Kohana::lang('o_manage.two_pwd_not_valid'),'manage/manager/add');
			}
			else
			{
				//不填写密码表示不修改密码
				if(empty($password1))
				{
					$_POST['password'] = $manager['password'];
				}
				else
				{
					$_POST['password'] = md5($password1);
				}
			}
			
			if(Mymanager::instance($id)->edit($_POST)) {
				//zhu modify to acl page
				if($role_id ==0 || $submit_target>0)
				{
					remind::set(Kohana::lang('o_global.update_success'),'manage/manager/rule/' . $id,'success');
				}
				else
				{
				    remind::set(Kohana::lang('o_global.update_success'),'manage/manager','success');
				}
			}
			else
			{
				$error = Mysite::instance()->error();
				remind::set(Kohana::lang('o_global.update_error').$error,'manage/manager');
			}
		}
		
		//zhu modify
		$role_id = $manager['role_id'];
		if($role_id>0){
			$role = Myrole::instance($role_id)->get();
			if($manager['is_admin'] <> $role['type'])
			{
				remind::set(Kohana::lang('o_manage.manager_edit_load_error'),'manage/manager');
			}	
		}
		
		if($manager['is_admin'] == 1)
		{
			/* 管理员 */
			$where = array();
			$where['type'] = $manager['is_admin'];
            $where['active'] = 1; //zhu add
			/* 管理员的用户组 */
			$roles = Myrole::instance()->roles($where);
			$this->template->content = new View("manage/manager_adminedit");
		} else {
			$parent_id = $manager['parent_id'];
			$parent = Mymanager::instance($parent_id)->get();
			if($parent['is_admin'])
			{
				/* 商户 */
				$where = array();
				$where['type'] = 0;
                $where['active'] = 1; //zhu add
				$roles = Myrole::instance()->roles($where);
				
				$this->template->content = new View("manage/manager_edit");
			} else {
				/* 商户子账号 zhu modify */
				//$roles = Myrole::instance()->childrens($parent['role_id']);
				$roles = array();
				if($parent['role_id']>0)
				{
				    $roles = Myrole::instance()->childrens($parent['role_id']);
				}
				
				$this->template->content = new View("manage/manager_child_edit");
			}
		}
		
		foreach($roles as $key=>$value)
		{
			if($manager['role_id'] == $value['id'])
			{
				$roles[$key]['selected'] = 'selected';
			}
			else
			{
				$roles[$key]['selected'] = '';
			}
		}
		$this->template->content->roles = $roles;
		$this->template->content->data = $manager;
	}

	/**
	 * set manager acl zhu add
	 */
	public function rule($id=0)
	{
		$manager = Mymanager::instance($id);
		$manager_data = $manager->get();
		//zhu add
		$this->_check_manager($manager_data['id']);
		
		if($_POST) {
			$resource = $this->input->post('resource');
			
			if(!$resource)
			{
				remind::set(Kohana::lang('o_manage.select_user_role'),'manage/manager/rule/'.$id);
			}

			if(Mymanager::instance()->set_actions($id, $resource)) {
				remind::set(Kohana::lang('o_global.add_success'),'manage/manager','success');
			}else {
				remind::set(Kohana::lang('o_global.add_error'),'manage/manager/rule/'.$id);
			}
		}

		//VIEW
		$acl = $manager->acl();
		$actions = Myaction::instance()->actions();
		
		//获得当前管理帐户的权限
        if(role::is_root())
        {			
			$active_actions = $actions;
		}
		else
		{
			$current_admin_acl = Mymanager::instance($this->manager_id)->acl();
			$active_actions = $current_admin_acl['permissions'];
	    }

		$current_actions_id_arr = array();
		$active_actions_id_arr = array();
		$current_actions_id_arr = explode(",", $acl["permissions"]);

        if(is_array($active_actions))
        {
		    foreach($active_actions as $key=>$value)
		    {
			    $active_actions_id_arr[] = $value['id'];
		    }        	
        }
        else
        {
        	$active_actions_id_arr = explode(",", $active_actions);
        }
		
		foreach($actions as $key=>$value)
		{
			if(in_array($value['id'],$active_actions_id_arr))
			{
				$actions[$key]['flag'] = 0;
			}
			else
			{
				$actions[$key]['flag'] = 1;
			}
			if(in_array($value['id'],$current_actions_id_arr))
			{
				$actions[$key]['checked'] = "checked";
			}
			else
			{
				$actions[$key]['checked'] = "";
			}
		}
		
		$this->template->content = new View("manage/manager_rule");
		$this->template->content->acl = $acl;
		$this->template->content->actions = $actions;
	}
	
	/**
	 * manager rule view zhu add
	 */
	public function rule_view()
	{		
		$id = intval($this->uri->segment('id'));
		//zhu add
		$this->_check_manager($id);
		$acl = Mymanager::instance($id)->acl();
		
		$current_actions_id_arr = explode(",", $acl["permissions"]);
		$actions = Myaction::instance()->actions();
		foreach($actions as $key=>$value)
		{
			if(!in_array($value['id'],$current_actions_id_arr))
			{
				unset($actions[$key]);
			}
		}
		$this->template->content = new View("manage/manager_rule_view");		
		$this->template->content->acl = $acl;
		$this->template->content->actions = $actions;
	}

	/**
	 * 分配管理员站点
	 */
	public function site($id)
	{
		//zhu add
		$this->_check_manager($id);
		if($_POST)
		{
			$manager = Mymanager::instance($id)->get();
			$target_select = $this->input->post('target_select');
			if($manager['site_num'] < count($target_select))
			{
				remind::set(Kohana::lang('o_manage.self_account') . $manager['site_num'] . Kohana::lang('o_manage.num_site'),'manage/manager/site/'.$id);
			}
			if(!$target_select)
			{
				remind::set(Kohana::lang('o_manage.select_site'),'manage/manager/site/'.$id);
			}

			if(Mymanager::instance()->set_sites($id,$_POST)) {
				remind::set(Kohana::lang('o_global.add_success'),'manage/manager','success');
			}else {
				remind::set(Kohana::lang('o_global.add_error'),'manage/manager/site/'.$id);
			}
		}
		
		/* 得到当前所有的站点类型 */
		$site_types = Mysite_type::instance()->site_types();

		/* 验证是超级管理员就列出所有站点 */
		if(role::is_root($this->manager_name))
		{
			$sites = Mysite::instance()->get_sites();
		} else {
			/* 超级管理员外自己不能调整自己的站点 */
			if($this->manager_id == $id)
			{
				remind::set(Kohana::lang('o_manage.self_can_not_set_self_site'),'manage/manager');
			}
			$sites = Mymanager::instance($this->manager_id)->sites();
		}
		
		$target_sites = Mymanager::instance($id)->sites();
		$optional_sites = tool::my_array_diff($sites,$target_sites);

		$this->template->content = new View("manage/assign_site");
		$this->template->content->sites = $optional_sites;
		$this->template->content->site_types = $site_types;
		$this->template->content->target_sites = $target_sites;
		$this->template->content->access_url = url::base().'manage/site/ajax_search_site';
		$this->template->content->title = '管理员站点分组管理';
	}

	/**
	 * change password
	 */
	public function change_password()
	{
		if($_POST){
			$password = $this->input->post('password');
			$password1 = $this->input->post('password1');
			$password2 = $this->input->post('password2');
			$data = role::get_manager();
			//判断SESSION中的数据是否存在
			if($data['id'] > 0)
			{
				if(strlen($password1) < 6)
				{
					remind::set(Kohana::lang('o_manage.password_length_error'),'manage/manager/change_password');
				}
				if($password1 <> $password2)
				{
					remind::set(Kohana::lang('o_manage.two_pwd_not_valid'),'manage/manager/change_password');
				}
				else
				{
					$manager = Mymanager::instance($data['id'])->get();
					if($manager['password'] == md5($password))
					{
						$update_data = array();
						$update_data['password'] = md5($password1);
						$manager = Mymanager::instance($data['id'])->update($update_data);
						$is_remember = (isset($manager['is_remember']))?$manager['is_remember']:0;//验证是否记住状态
						$manager['is_remember'] = $is_remember;
						role::set_manager_session($manager);
						//记录用户修改密码日志
						ulog::change_password($this->manager_id,1);
						remind::set(Kohana::lang('o_global.update_success'),'manage/manager/change_password','success');
					}
					else
					{
						//记录用户修改密码日志
						ulog::change_password($this->manager_id);
						remind::set(Kohana::lang('o_manage.pwd_is_incorrect'),'manage/manager/change_password');
					}
				}
			}
			else
			{
				remind::set(Kohana::lang('o_global.login_first'),'login');
			}
		}

		$this->template->content = new View("manage/manager_change_password");
	}

	/**
	 * change manager parent
	 */
	public function change_parent()
	{
		$this->site_ids = role::check('change_account_link');

		$error = "";
		$parent_id = $this->input->post('parent_id');
		$id_str = $this->input->post('id_str');
		$id_arr = explode(',',$id_str);

        //zhu add
        $this->_parent_is_admin($parent_id);

		if(count($id_arr) < 1)
		{
			remind::set(Kohana::lang('o_global.update_error'),'manage/manager/');
		}
		else
		{
			foreach($id_arr as $key=>$value)
			{
				if($value > 0)
				{
					//zhu add 子账号不能与父账号相同
                     if($parent_id==$value)
                     {
                     	remind::set(Kohana::lang('o_manage.child_same_parent'),'manage/manager/');
                     }
					
					if(!Mymanager::instance($value)->change_parent($parent_id))
					{
						$error .= Mymanager::instance($value)->get('name')."更新失败<br/>";
					}
				}
			}
			if(strlen($error) > 1)
			{
				remind::set(Kohana::lang('o_global.update_error').$error,'manage/manager');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_success'),'manage/manager','success');
			}
		}
	}

	/**
	 * ajax change manager parent content
	 */
	public function ajax_change_parent()
	{
		$return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
		if(request::is_ajax()) {
			$manager_id = role::manager_id();

			$id_str = $this->input->post('id_str');
			$managers = Mymanager::instance()->subs($manager_id);

			$return_template = $this->template = new View('template_blank');
			$this->template->content = new View('manage/manager_change_parent');
			$this->template->content->managers = $managers;
			$this->template->content->id_str = $id_str;
			$return_str = $return_template->render();
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = 'Success';
			$return_struct['content'] = $return_str;
			exit(json_encode($return_struct));
		}
	}

	/**
	 * delete manager
	 */
	public function delete($id)
	{
		role::check('delete_manager');
		//zhu add
		$this->_check_manager($id);
		/*if($id == $this->manager_id)
		{
			remind::set(Kohana::lang('o_manage.self_account_not_do'),'manage/manager');
		}*/
		//不能删除root
		$manager = Mymanager::instance($id)->get();
        if(role::is_root($manager['username']))
        {
        	remind::set(Kohana::lang('o_global.access_root_denied'),'manage/manager');
        }

		if(Mymanager::instance($id)->delete()) 
		{
			remind::set(Kohana::lang('o_global.delete_success'),'manage/manager','success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.delete_error'),'manage/manager');
		}
	}
}
