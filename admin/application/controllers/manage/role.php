<?php defined('SYSPATH') OR die('No direct access allowed.');

class Role_Controller extends Template_Controller {
	public $site_ids;

	public function __construct()
	{
		parent::__construct();
		//zhu modify 只允许root执行
		//$this->site_ids = role::check('manage_role');
        if(!role::is_root())
        {
        	remind::set(Kohana::lang('o_manage.only_root_do'),'manage/manager');
        }		
		
	}
	/**
	 * 列表
	 */
	public function index() {

		$this->template->content = new View("manage/role_list");

		//调用分页
		$per_page = controller_tool::per_page();
		$this->pagination = new Pagination(
			array(
				'total_items'    => Myrole::instance()->count(),
				'items_per_page' => $per_page,
			)
		);
		//管理员可能查看所有的用户组，其实只能看到对应等级下面的用户组
		if($this->manager_is_admin == 1)
		{
			$roles = Myrole::instance()->roles();
		}
		else
		{
			$role_id = Mymanager::instance($this->manager_id)->get('role_id');
			$roles = Myrole::instance()->childrens($role_id);
		}

		foreach($roles as $k=>$v)
		{
			//显示图片标识
			$roles[$k]['active_img'] = view_tool::get_active_img($v['active']);
			$roles[$k]['type_name'] = 'merchant';
			if($v['type'] == 1)
			{
				$roles[$k]['type_name'] = 'admin';
			}
			//列表中显示上级用户组
			$roles[$k]['parent_name'] = '空';
			if($v['parent_id'] > 0 )
			{
				$role = Myrole::instance($v['parent_id'])->get();
				$roles[$k]['parent_name'] = $role['name'];
			}
		}
		$this->template->content->roles = $roles;
	}

	/**
	 * 添加
	 */
	public function add()
	{
		$this->site_ids = role::check('role_edit');
		if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
            
			$name = $this->input->post('name');
			$role = Myrole::instance()->get_by_name($name);
			$parent_id = $this->input->post('parent_id');
			if($role['id'])
			{
				remind::set(Kohana::lang('o_manage.group_has_exist'),'manage/role/add');
			}

			if($parent_id > 0)
			{
				$role = Myrole::instance($parent_id)->get();
                //zhu add
    			if($role['type']!=$this->input->post('type'))
    			{
    				remind::set(Kohana::lang('o_manage.parent_group_type_not_match'),'manage/role/add');
    			}
				$_POST['level_depth'] = $role['level_depth'] + 1;
			}
			else
			{
				$_POST['level_depth'] = 1;
			}

			if(Myrole::instance()->add($_POST)) {
				remind::set(Kohana::lang('o_global.add_success'),'manage/role','success');
			}else {
				remind::set(Kohana::lang('o_global.add_error'),'manage/role/add');
			}
		}
		$roles = Myrole::instance()->roles();

		$this->template->content = new View("manage/role_add");
		$this->template->content->roles = $roles;
	}

	/**
	 * edit
	 */
	public function edit($id)
	{
		$role = Myrole::instance($id)->get();
		if(empty($role['id']) || !isset($role['id']))
		{
			remind::set(Kohana::lang('o_manage.group_not_exist'),'manage/role');
		}

		if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
			
			$name = $this->input->post('name');
            //zhu add
			$parent_id = $this->input->post('parent_id');
			if($role['name'] <> $name)
			{
                //zhu modify
				$role = Myrole::instance()->get_by_name($name);
				//$parent_id = $this->input->post('parent_id');
				if($role['id'])
				{
					remind::set(Kohana::lang('o_manage.group_has_exist'),'manage/role/edit/' . $id);
				}
			}

            //zhu add 检查自身，上级帐户类型
			if($parent_id>0)
            {
    			if($role['id']==$parent_id)
    			{
    				remind::set(Kohana::lang('o_manage.parent_group_is_self'),'manage/role/edit/' . $id);
    			}

                //zhu add
    			$parent_role = Myrole::instance($parent_id)->get();
                if($parent_role['type']!=$this->input->post('type'))
                {
                	remind::set(Kohana::lang('o_manage.parent_group_type_not_match'),'manage/role/edit/' . $id);
                }
            }

			if(Myrole::instance($id)->edit($_POST)){
				remind::set(Kohana::lang('o_global.update_success'),'manage/role','success');
			}else {
				remind::set(Kohana::lang('o_global.update_error'),'manage/role/edit/' . $id);
			}
		}
		$roles = Myrole::instance()->roles();
		foreach($roles as $key=>$value)
		{
			$roles[$key]['selected'] = '';
			if($value['id'] = $role['parent_id'])
			{
				$roles[$key]['selected'] = 'selected';
			}
		}

		$this->template->content = new View("manage/role_edit");
		$this->template->content->roles = $roles;
		$this->template->content->data = $role;
	}

	/**
	 * view role rule
	 */
	public function view_rule($id)
	{

		$this->template->content = new View("manage/role_view_rule");

		//VIEW
		$role = Myrole::instance($id)->get();
		if(!$role['id'])
		{
			remind::set(Kohana::lang('o_global.access_denied'),'manage/role');
		}

		if($role['parent_id'] > 0)
		{
			//zhu modify
			//$actions = Myrole::instance($role['parent_id'])->actions();
			$parent_role = Myrole::instance($role['parent_id'])->get();
			$parent_actions_id = explode(",", $parent_role["permissions"]);
			$actions = Myaction::instance()->actions(0, $parent_actions_id);
		}
		else
		{
			$actions = Myaction::instance()->actions();
		}
		
        //zhu modify 
		$current_actions_id_arr = array();
		/*$current_actions = Myrole::instance($id)->actions();
		foreach($current_actions as $key=>$value)
		{
			$current_actions_id_arr[] = $value['id'];
		}*/
        $current_actions_id_arr = explode(",", $role["permissions"]);
		foreach($actions as $key=>$value)
		{
			if(!in_array($value['id'],$current_actions_id_arr))
			{
				unset($actions[$key]);
			}
		}

		$this->template->content->actions = $actions;
	}

	/**
	 * set role rule
	 */
	public function rule($id)
	{
		$this->template->content = new View("manage/role_rule");

		if($_POST) {
			$resource = $this->input->post('resource');
			if(!$resource)
			{
				remind::set(Kohana::lang('o_manage.select_user_role'),'manage/role/rule/'.$id);
			}

			if(Myrole::instance()->set_actions($id,$_POST)) {
				remind::set(Kohana::lang('o_global.add_success'),'manage/role','success');
			}else {
				remind::set(Kohana::lang('o_global.add_error'),'manage/role/rule/'.$id);
			}
		}

		//VIEW
		$role = Myrole::instance($id)->get();
		if(!$role['id'])
		{
			remind::set(Kohana::lang('o_global.access_denied'),'manage/role');
		}

		$actions = Myaction::instance()->actions();		

		if($role['parent_id'] > 0)
		{
			//zhu modify  $active_actions type:string
			//$current_actions = Myrole::instance($id)->actions();
			//$active_actions = Myrole::instance($role['parent_id'])->actions();
			$parent_role = Myrole::instance($role['parent_id'])->get();
			$active_actions = $parent_role["permissions"];
		}
		else
		{
			//zhu modify  $active_actions type:array
			//$current_actions = Myrole::instance($id)->actions();
			$active_actions = $actions;
		}

		$current_actions_id_arr = array();
		$active_actions_id_arr = array();
        //zhu modify 
		/*
		foreach($current_actions as $key=>$value)
		{
			$current_actions_id_arr[] = $value['id'];
		}*/
		$current_actions_id_arr = explode(",", $role["permissions"]);
		/*
		foreach($active_actions as $key=>$value)
		{
			$active_actions_id_arr[] = $value['id'];
		}*/
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

		$this->template->content->actions = $actions;
	}

	//删除用户组
	public function delete($id)
	{
		if(Myrole::instance($id)->delete()) {
			remind::set(Kohana::lang('o_global.delete_success'),'manage/role','success');
		}else {
			$error = Myrole::instance($id)->error();
			remind::set(Kohana::lang('o_global.delete_error') . $error,'manage/role');
		}
	}
	
}