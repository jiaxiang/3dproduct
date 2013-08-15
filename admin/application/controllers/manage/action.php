<?php defined('SYSPATH') OR die('No direct access allowed.');

class Action_Controller extends Template_Controller {

	public function __construct()
	{
		parent::__construct();
		role::check('manage_action');
	}
    
	/**
	 * 列表
	 */
	public function index() {
		$this->template->content = new View("manage/action_list");

		$actions = Myaction::instance()->actions();
		//用户可管理权限资源的ID
		$user_action_ids = role::get_action_ids();
		foreach($actions as $key=>$value)
		{
			if(!in_array($value['id'],$user_action_ids))
			{
				unset($actions[$key]);
			}
		}

		$this->template->content->actions = $actions;
	}

	/**
	 * 新权限资源
	 */
	public function add()
	{
        if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
			$name = $this->input->post('name');
			$resource = $this->input->post('resource');

			$data = Myaction::instance()->get_by_name($name);
			if($data['id'])
			{
                remind::set(Kohana::lang('o_manage.resources_name_exist'),'manage/action/add');
			}

			$data = Myaction::instance()->get_by_resource($resource);
			if($data['id'])
			{
                remind::set(Kohana::lang('o_manage.resources_mark_exist'),'manage/action/add');
			}
			
            if(Myaction::instance()->add($_POST)) {
                remind::set(Kohana::lang('o_global.add_success'),'manage/action/add','success');
            }else {
                remind::set(Kohana::lang('o_global.add_error'),'manage/action/add');
            }
        }
		$actions = Myaction::instance()->actions();

		$this->template->content = new View("manage/action_add");
		$this->template->content->actions = $actions;
	}

	/**
	 * edit 
	 */
	public function edit($id)
	{
		$action = Myaction::instance($id)->get();
		if(!$action['id'])
		{
			remind::set(Kohana::lang('o_manage.resources_not_exist'),'manage/action');
		}

        if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
            
            $name = $this->input->post('name');
            $resource = $this->input->post('resource');
            $parent_id = $this->input->post('parent_id');

			if($name <> $action['name'])
			{
				$data = Myaction::instance()->get_by_name($name);
				if($data['id'])
				{
					remind::set(Kohana::lang('o_manage.resources_name_exist'),'manage/action/add');
				}
			}

			if($resource <> $action['resource'])
			{
				$data = Myaction::instance()->get_by_resource($resource);
				if($data['id'])
				{
					remind::set(Kohana::lang('o_manage.resources_mark_exist'),'manage/action/add');
				}
			}

            //zhu add 上级资源不能选择自身,子项
			if($parent_id>0)
			{
    			if($action['id'] == $parent_id)
    			{
    				remind::set(Kohana::lang('o_manage.parent_action_is_self'),'manage/action/edit/' . $id);
    			}

				$sub_ac = Myaction::instance()->actions($id);
                if(is_array($sub_ac) && count($sub_ac))
                {
                    foreach($sub_ac as $ac)
                    {
                        if($ac['id'] == $parent_id)
                        {
                            remind::set(Kohana::lang('o_manage.parent_action_is_child'),'manage/action/edit/' . $id);
                        }
                    }
                }	
			}//zhu add end

			if(Myaction::instance($id)->edit($_POST)) 
			{
                remind::set(Kohana::lang('o_global.update_success'),'manage/action','success');
			} 
			else 
			{
                remind::set(Kohana::lang('o_global.update_error'),'manage/action/edit/' . $id);
            }
        }

		$actions = Myaction::instance()->actions();
		foreach($actions as $key=>$value)
		{
			$actions[$key]['selected'] = '';
			if($actions['id'] = $action['parent_id'])
			{
				$actions[$key]['selected'] = 'selected';
			}
		}

		$this->template->content = new View("manage/action_edit");
		$this->template->content->actions = $actions;
		$this->template->content->data = $action;
	}
}
