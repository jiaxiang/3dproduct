<?php defined('SYSPATH') OR die('No direct access allowed.');

class Menu_Controller extends Template_Controller {
	public $site_ids;

	public function __construct()
	{
		parent::__construct();
        role::check('manage_menu');
	}
	/**
	 * 菜单列表
	 */
	public function index() {
        $orderby_arr= array
		(
			0 => array('order'=>'DESC'),
			1 => array('order'=>'ASC'),
		);
        $orderby = controller_tool::orderby($orderby_arr);

		$this->template->content = new View("manage/menu_list");

		$menus = Mymenu::instance()->menus(0,$orderby);
		foreach($menus as $key=>$value)
		{
			$menus[$key]['action_name'] = Myaction::instance($value['action_id'])->get('name');
		}
		foreach($menus as $key=>$value)
		{
			foreach($value as $k=>$v)
			{
				if(!is_numeric($v) && empty($v))
				{
					$menus[$key][$k] = 'NULL';
				}
			}
		}

		$this->template->content->menus = $menus;
	}

	/**
	 * add menu
	 */
	public function add()
	{
		if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
            
			$target = $this->input->post('target');
			$parent_id = $this->input->post('parent_id');
			
			$data = Mymenu::instance()->get_by_target($target);
			if($data['id'])
			{
				remind::set(Kohana::lang('o_manage.menu_has_exist'),'manage/menu/add');
			}
			if($parent_id > 0)
			{
				$parent_menu = Mymenu::instance($parent_id)->get();
				if($parent_menu['level_depth'] >= 3)
				{
					remind::set(Kohana::lang('o_manage.menu_can_not_add_level'),'manage/menu/add');
				}
			}

			if(Mymenu::instance()->add($_POST)) {
				$cache = Mycache::instance();
				$tag = "admin/menu";
				$data = $cache->delete($tag);
				remind::set(Kohana::lang('o_global.add_success'),'manage/menu','success');
			}else {
				remind::set(Kohana::lang('o_global.add_error'),'manage/menu/add');
			}
		}

		$menus = Mymenu::instance()->menus();
		$actions = Myaction::instance()->actions();

		$this->template->content = new View("manage/menu_add");
		$this->template->content->menus = $menus;
		$this->template->content->actions = $actions;
	}

	/**
	 * edit menu
	 */
	public function edit($id)
	{        
		$menu = Mymenu::instance($id)->get();
		if(!$menu['id'])
		{
			remind::set(Kohana::lang('o_global.bad_request'),'manage/menu');
		}

		if($_POST) {			
            //标签过滤
            tool::filter_strip_tags($_POST);

            $target = $this->input->post('target');
            $parent_id = $this->input->post('parent_id');
			
			if($menu['target'] <> $target)
			{
				$data = Mymenu::instance($id)->get_by_target($target);
				if($data['id'])
				{
					remind::set(Kohana::lang('o_manage.mark_has_exist'),'manage/menu/edit/'.$id);
				}
			}
			/* 最多只能添加三级菜单 */
			if($parent_id > 0)
			{
				$parent_menu = Mymenu::instance($parent_id)->get();
				if($parent_menu['level_depth'] >= 3)
				{
					remind::set(Kohana::lang('o_manage.menu_can_not_add_level'),'manage/menu/add');
				}
			}
			/* 添加 */
			if(Mymenu::instance($id)->edit($_POST)) {
				remind::set(Kohana::lang('o_global.update_success'),'manage/menu','success');
			}else {
				remind::set(Kohana::lang('o_global.update_error'),'manage/menu/edit/' . $id);
			}
		}

		$menus = Mymenu::instance()->menus();
		foreach($menus as $key=>$value)
		{
			if($value['id'] == $menu['parent_id'])
			{
				$menus[$key]['selected'] = 'selected';
			}
			else
			{
				$menus[$key]['selected'] = '';
			}
		}
		$actions = Myaction::instance()->actions();
		foreach($actions as $key=>$value)
		{
			if($value['id'] == $menu['action_id'])
			{
				$actions[$key]['selected'] = 'selected';
			}
			else
			{
				$actions[$key]['selected'] = '';
			}
		}

		$this->template->content = new View("manage/menu_edit");
		$this->template->content->data = $menu;
		$this->template->content->menus = $menus;
		$this->template->content->actions = $actions;
	}

	/**
	 * set active
	 */
	public function set_active()
	{
		$id = $this->input->get('id');
		if(!$id)
		{
			remind::set(Kohana::lang('o_global.set_error'),'manage/menu');
		}
		$menu = Mymenu::instance($id)->get();
		$active = 0;
		if($menu['active'] == 0)
		{
			$active = 1;
		}
		if(Mymenu::instance()->set_active($id,$active))
		{
			remind::set(Kohana::lang('o_global.set_success'),'manage/menu','success');
		}
		else
		{
			remind::set(Kohana::lang('o_global.set_error'),'manage/menu');
		}
	}
	
    /**
     * 设定菜单的排序
     */
   public function set_order()
    {
        //初始化返回数组
        $return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       $request_data = $this->input->get();
       $id = isset($request_data['id']) ?  $request_data['id'] : '';
       $order = isset($request_data['order']) ?  $request_data['order'] : '';
       if(empty($id) || (empty($order) && $order != 0))
       {
           $return_struct['msg'] = Kohana::lang('o_global.bad_request');
           exit(json_encode($return_struct));
       }
       if(!is_numeric($order) || $order<0){
           $return_struct['msg'] = Kohana::lang('o_global.position_rule');
           exit(json_encode($return_struct));
       }
       if(Mymenu::instance()->set_order($id,$order)){
            $return_struct = array(
                'status'        => 1,
                'code'          => 200,
                'msg'           => Kohana::lang('o_global.position_success'),
                'content'       => array('order'=>$order),
            );
       } else {
            $return_struct['msg'] = Kohana::lang('o_global.position_error');
       }
       exit(json_encode($return_struct));
    }
}
