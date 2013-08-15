<?php defined('SYSPATH') OR die('No direct access allowed.');

class Menu_Controller extends Template_Controller {
	protected $current_flow = 'menu';
	public $site_id;
    protected $MENU_TYPE_ADDRESS = 1;
    protected $MENU_TYPE_CATEGORY = 2;
    protected $MENU_TYPE_DOC = 3;

	public function __construct()
	{
        role::check('site_menu');
		parent::__construct();
	}

	public function index()
	{
        // 初始化默认查询条件
        $orderby_arr= array(
                0   => array('id'=>'DESC'),
                1   => array('id'=>'ASC'),
                2   => array('order'=>'ASC'),
                3   => array('order'=>'DESC'),
                4   => array('parent_id'=>'ASC'),
                5   => array('parent_id'=>'DESC'),
                6   => array('memo'=>'ASC'),
                7   => array('memo'=>'DESC'),
            );
        $orderby    = controller_tool::orderby($orderby_arr);
		$query_struct = array(
            'where'=>array(
            ),
            'orderby'   => $orderby,
        );
        
		$site_menu = Mysite_menu::instance();
		$total = $site_menu->count($query_struct);
		$site_menus = $site_menu->lists($query_struct);
		$data = $site_menus;
		foreach($site_menus as $key=>$val)
		{
			$data[$key]['parent_name'] = Mysite_menu::instance($val['parent_id'])->get('name');
		}
        $this->template->content = new View("site/site_menu_list");
		$this->template->content->data = $data;
		$this->template->content->total = $total;
	}

	/**
	 * 添加链接地址导航
	 */
	public function address_menu_add()
	{
		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST);
	        
	        /* 判断url是否重复*/
	        $site_menu = Mysite_menu::instance();
	        $url = $this->input->post('url');
	        if($site_menu->url_exist($url))
	        {
	        	remind::set(Kohana::lang('o_site.url_exist'),'site/menu/address_menu_add','error');
	        }
	        
			/* 获取要添加的导航的level_depth*/
	        $data = $_POST;
	        $parent_id = $this->input->post('parent_id');
	        if($parent_id == 0)
	        {
	        	$data['level_depth'] = 1;
	        }
	        else
	        {
	        	$parent_level_depth = Mysite_menu::instance($parent_id)->get('level_depth');
	        	$data['level_depth'] = $parent_level_depth + 1;
	        }
	        
	        /* 标志导航类型*/
	        $data['memo'] = 'address';
            $data['type'] = $this->MENU_TYPE_ADDRESS;
	        
			$submit_target = intval($this->input->post('submit_target'));
			if($site_menu->site_menu_add($data))
			{
				//判断添加成功去向
				switch($submit_target)
				{
					case 1:
						remind::set(Kohana::lang('o_global.add_success'),'site/menu/address_menu_add','success');
					default:
						remind::set(Kohana::lang('o_global.add_success'),'site/menu','success');
				}
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),'site/menu');
			}
		}
		
		$site_menus = Mysite_menu::instance()->site_menu_queue();
        $this->template->content = new View("site/address_menu_add");
        $this->template->content->site_menus = $site_menus;
	}
	
	/**
	 * 添加商品分类导航
	 */
	public function category_menu_add()
	{
		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST);
	        
			/* 获取要添加的导航的level_depth*/
			$data = $_POST;
	        $parent_id = $this->input->post('parent_id');
	        if($parent_id == 0)
	        {
	        	$data['level_depth'] = 1;
	        }
	        else
	        {
	        	$parent_level_depth = Mysite_menu::instance($parent_id)->get('level_depth');
	        	$data['level_depth'] = $parent_level_depth + 1;
	        }
	        
	        /* 获取所添加的分类的url*/
	        $category_id = $this->input->post('category_id');
	        $data['url'] = category::permalink($category_id, false);//'/category/'.$category_id;
	        $data['relation_id'] = $category_id;
	        
	        /* 标志导航类型*/
	        $data['memo'] = 'category';
            $data['type'] = $this->MENU_TYPE_CATEGORY;
	        
			$submit_target = intval($this->input->post('submit_target'));
			if(Mysite_menu::instance()->site_menu_add($data))
			{
				//判断添加成功去向
				switch($submit_target)
				{
					case 1:
						remind::set(Kohana::lang('o_global.add_success'),'site/menu/category_menu_add','success');
					default:
						remind::set(Kohana::lang('o_global.add_success'),'site/menu','success');
				}
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),'site/menu');
			}
		}
		
		/* 当前站点分类*/
        $categories = CategoryService::get_instance()->get_categories();
        $str = '<option value={$id} {$selected}>{$spacer}{$title}</option>';
        $category_list = tree::get_tree($categories, $str, 0, 0);
        
		$site_menus = Mysite_menu::instance()->site_menu_queue();
        $this->template->content = new View("site/category_menu_add");
        $this->template->content->site_menus = $site_menus;
        $this->template->content->category_list = $category_list;
	}
	
	/**
	 * 添加文案链接导航
	 */
	public function doc_menu_add()
	{
		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST);
	        
			/* 获取要添加的导航的level_depth*/
			$data = $_POST;
	        $parent_id = $this->input->post('parent_id');
	        if($parent_id == 0)
	        {
	        	$data['level_depth'] = 1;
	        }
	        else
	        {
	        	$parent_level_depth = Mysite_menu::instance($parent_id)->get('level_depth');
	        	$data['level_depth'] = $parent_level_depth + 1;
	        }
	        
	        /* 获取文案的url*/
	        $doc_id = $this->input->post('doc_id');
	        $doc_permalink = Mydoc::instance($doc_id)->get('permalink');
	        $data['url'] = $doc_permalink;
            $data['relation_id'] = $doc_id;
	        
	        /* 标志导航类型*/
	        $data['memo'] = 'doc';
            $data['type'] = $this->MENU_TYPE_DOC;
	        
			$submit_target = intval($this->input->post('submit_target'));
			if(Mysite_menu::instance()->site_menu_add($data))
			{
				//判断添加成功去向
				switch($submit_target)
				{
					case 1:
						remind::set(Kohana::lang('o_global.add_success'),'site/menu/doc_menu_add','success');
					default:
						remind::set(Kohana::lang('o_global.add_success'),'site/menu','success');
				}
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),'site/menu');
			}
		}
		
		$site_docs = Mydoc::instance()->site_docs();
		$site_menus = Mysite_menu::instance()->site_menu_queue();
        $this->template->content = new View("site/doc_menu_add");
        $this->template->content->site_menus = $site_menus;
        $this->template->content->site_docs = $site_docs;
	}
	
	/**
	 * edit menu
	 */
	public function edit($id)
	{
		$site_menu = Mysite_menu::instance($id);
		$site_menu_data = $site_menu->get();
		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST);
            
			if($site_menu->site_menu_edit($site_menu_data['id'],$_POST))
			{
				remind::set(Kohana::lang('o_global.update_success'),'site/menu', 'success');
			}else{
				remind::set(Kohana::lang('o_global.update_error'),'site/menu');
			}
		}

        $this->template->content = new View("site/site_menu_edit");
		$this->template->content->data = $site_menu_data;
		$this->template->content->site_id = $this->site_id;
		$this->template->content->title = "site site_menu edit";
	}
	
	/**
	 * 编辑链接地址导航
	 */
	public function address_menu_edit($id)
	{        
		$site_menu = Mysite_menu::instance($id);
		$site_menu_data = $site_menu->get();
		
		/* 得到导航列表并删除自身及自身的子目录*/
		$child_ids = array();
		$site_menus = Mysite_menu::instance()->site_menu_queue();
	    $temp = Mysite_menu::instance()->site_menu_queue($id);
	    foreach($temp as $val)
	    {
	        $child_ids[] = $val['id'];
	    }
		foreach($site_menus as $key=>$value)
		{
			if($value['id'] == $id || in_array($value['id'], $child_ids))
			{
				unset($site_menus[$key]);
			}
		}

		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST);
	        
	        /* 判断url是否重复*/
	        $url = $this->input->post('url');
	        if($url != $site_menu_data['url'])
	        {
	        	if(Mysite_menu::instance()->url_exist($this->site_id,$url))
	        	{
	        		remind::set(Kohana::lang('o_site.url_exist'),'site/menu/address_menu_add','error');
	        	}
	        }
	        
			/* 获取要编辑的导航的level_depth并修改其下所有的子导航的level_depth*/
	        $data = $_POST;
	        $parent_id = $this->input->post('parent_id');
	        if($parent_id == 0)
	        {
	        	$data['level_depth'] = 1;
	        }
	        else
	        {
	        	$parent_level_depth = Mysite_menu::instance($parent_id)->get('level_depth');
	        	$data['level_depth'] = $parent_level_depth + 1;
	        }
	        if(!Mysite_menu::instance()->child_level_depth_edit($id,$data['level_depth'],$temp))
	        {
	        	remind::set(Kohana::lang('o_global.update_error'),'site/menu');
	        }
	        
	        /* 标志导航类型*/
	        $data['memo'] = 'address';
	        $data['type'] = $this->MENU_TYPE_ADDRESS;
	        
			if(Mysite_menu::instance()->site_menu_edit($site_menu_data['id'],$data))
			{
				remind::set(Kohana::lang('o_global.update_success'),'site/menu/','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),'site/menu');
			}
		}
		
        $this->template->content = new View("site/address_menu_edit");
        $this->template->content->site_menus = $site_menus;
        $this->template->content->site_menu_data = $site_menu_data;
	}
	
	/**
	 * 编辑商品分类导航
	 */
	public function category_menu_edit($id)
	{
		$site_menu = Mysite_menu::instance($id);
	    $temp = Mysite_menu::instance()->site_menu_queue($id);
		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST);
	        
			/* 获取要编辑的导航的level_depth并修改其下所有的子导航的level_depth*/
			$data = $_POST;
	        $parent_id = $this->input->post('parent_id');
	        if($parent_id == 0)
	        {
	        	$data['level_depth'] = 1;
	        }
	        else
	        {
	        	$parent_level_depth = Mysite_menu::instance($parent_id)->get('level_depth');
	        	$data['level_depth'] = $parent_level_depth + 1;
	        }
			if(!Mysite_menu::instance()->child_level_depth_edit($id,$data['level_depth'],$temp))
	        {
	        	remind::set(Kohana::lang('o_global.update_error'),'site/menu');
	        }
	        
	        /* 获取所添加的分类的url*/
	        $category_id = $this->input->post('category_id');
	        $data['url'] = category::permalink($category_id, false);//'/category/'.$category_id;
	        
	        /* 标志导航类型*/
	        $data['memo'] = 'category';
            $data['type'] = $this->MENU_TYPE_CATEGORY;
            $data['relation_id'] = $category_id;
			if($site_menu->site_menu_edit($id,$data))
			{
				remind::set(Kohana::lang('o_global.update_success'),'site/menu/','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),'site/menu');
			}
		}
		
		$site_menu_data = $site_menu->get();
		
		/* 得到导航列表并删除自身及自身的子目录*/
		$child_ids = array();
		$site_menus = Mysite_menu::instance()->site_menu_queue();
	    foreach($temp as $val)
	    {
	        $child_ids[] = $val['id'];
	    }
		foreach($site_menus as $key=>$value)
		{
			if($value['id'] == $id || in_array($value['id'], $child_ids))
			{
				unset($site_menus[$key]);
			}
		}
		
		/* 当前站点分类*/
        $categories = CategoryService::get_instance()->get_categories();
        $str = '<option value={$id} {$selected}>{$spacer}{$title_manage}</option>';
        $category_list = tree::get_tree($categories, $str, 0, $site_menu_data['relation_id']);
        
        $this->template->content = new View("site/category_menu_edit");
        $this->template->content->site_menus = $site_menus;
        $this->template->content->category_list = $category_list;
        $this->template->content->site_menu_data = $site_menu_data;
	}
	
	/**
	 * 编辑文案链接导航
	 */
	public function doc_menu_edit($id)
	{		
	    $site_menu = Mysite_menu::instance($id);
		$site_menu_data = $site_menu->get();
		
		/* 得到导航列表并删除自身及自身的子目录 */
		$child_ids = array();
		$site_menus = Mysite_menu::instance()->site_menu_queue();
	    $temp = Mysite_menu::instance()->site_menu_queue($id);
	    foreach($temp as $val)
	    {
	        $child_ids[] = $val['id'];
	    }
		foreach($site_menus as $key=>$value)
		{
			if($value['id'] == $id || in_array($value['id'], $child_ids))
			{
				unset($site_menus[$key]);
			}
		}
	    
		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST);
	        
			/* 获取要添加的导航的level_depth*/
			$data = $_POST;
	        $parent_id = $this->input->post('parent_id');
	        if($parent_id == 0)
	        {
	        	$data['level_depth'] = 1;
	        }
	        else
	        {
	        	$parent_level_depth = Mysite_menu::instance($parent_id)->get('level_depth');
	        	$data['level_depth'] = $parent_level_depth + 1;
	        }
			if(!Mysite_menu::instance()->child_level_depth_edit($id,$data['level_depth'],$temp))
	    	{
	        	remind::set(Kohana::lang('o_global.update_error'),'site/menu');
	    	}
	        
	        /* 获取文案的url*/
	        $doc_id = $this->input->post('doc_id');
	        $doc_permalink = Mydoc::instance($doc_id)->get('permalink');
	        $data['url'] = $doc_permalink;
	        
	        /* 标志导航类型*/
	        $data['memo'] = 'doc';
            $data['type'] = $this->MENU_TYPE_DOC;
	        $data['relation_id'] = $doc_id;
            
			if($site_menu->site_menu_edit($site_menu_data['id'],$data))
			{
				remind::set(Kohana::lang('o_global.update_success'),'site/menu/','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),'site/menu');
			}
		}
        
		$site_docs = Mydoc::instance()->site_docs();
		//$permalink = trim($site_menu_data['url'],'/');
		//$doc_now = Mydoc::instance()->get_by_permalink($permalink);
		$doc_id_now = $site_menu_data['relation_id'];
        $this->template->content = new View("site/doc_menu_edit");
        $this->template->content->site_menus = $site_menus;
        $this->template->content->site_docs = $site_docs;
        $this->template->content->site_menu_data = $site_menu_data;
        $this->template->content->doc_id_now = $doc_id_now;
	}

	public function delete($id)
	{        
		$site_menu = Mysite_menu::instance($id);
		$site_menu->delete();
		remind::set(Kohana::lang('o_global.delete_success'),'site/menu', 'success');
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
       if(Mysite_menu::instance()->set_order($id,$order)){
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
    
    /**
     * 批量删除导航
     */
    public function batch_delete(){
        
        $return_struct = array();
        
        //初始化返回数据
        $return_data = array();
        
        //请求结构体
        $request_data = array();
        
        try {
            $menu_ids = $this->input->post('menu_ids');
            
            if(is_array($menu_ids) && count($menu_ids) > 0)
            {
                /* 删除失败的 */
                $failed_menu_names = '';
                /* 执行操作 */
                foreach($menu_ids as $menu_id)
                {
                    if(!Mysite_menu::instance($menu_id)->delete($menu_id))
                    {
                        $failed_menu_names .= ' | ' . $menu_id;
                    }
                }
                if(empty($failed_menu_names))
                {
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_menu_success'), 200);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_menu_names = trim($failed_menu_names,' | ');
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_menu_error', $failed_menu_names), 500);
                }
            }
            else
            {
                throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
            }
        } catch (MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
}
