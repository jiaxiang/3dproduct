<?php 
defined('SYSPATH') OR die('No direct access allowed.');

class News_category_Controller extends Template_Controller {

	public function __construct()
	{
		parent::__construct();
        role::check('news_category');
	}
	
	/**
	 * 页面分类列表
	 */
	public function index()
	{
		$list_columns = array(
			array('name'=>'名称','column'=>'category_name','class_num'=>'200')
		);
		$orderby_arr= array
            (
                0   => array('id'=>'ASC'),
                1   => array('id'=>'DESC'),
                2   => array('p_order'=>'ASC'),
                3   => array('p_order'=>'DESC'),
            );
        $orderby    = controller_tool::orderby($orderby_arr);
        $query_struct = array(
            'where'=>array(
                'parent_id' => 0,
            ),
            'like'=>array(),
            'orderby'   => $orderby,
            'limit'     => array(
                'per_page'  =>controller_tool::per_page(),
                'offset'    =>0,
            ),
        );
		$categories = Mynews_category::instance()->list_news_categories($query_struct);
		
		$this->template->content = new View("site/news_category_list");
		$this->template->content->list_columns = $list_columns;
		
		$this->template->content->categories = $categories;
	}
	
	/**
	 * 页面分类添加
	 */
	public function add()
	{
		$form_data = array(
			'category_name' => '',
			'parent_id' => '',
			'p_order' => 0
		);
		if($_POST)
		{
			$category_name = $this->input->post('category_name');
			if(empty($category_name))
			{
				remind::set('请填写分类名称','site/news_category/add');
			}
			else 
			{
				if(Mynews_category::instance()->name_exist($category_name))
				{
					remind::set(Kohana::lang('o_site.news_category_has_exist'));
				}
                else
                {
    				if(Mynews_category::instance()->add($_POST))
    				{	
    					remind::set(Kohana::lang('o_global.add_success'),'site/news_category','success');
    				}
    				else
    				{
    					remind::set(Kohana::lang('o_global.add_error'));
    				}
                }
			}
			$form_data = array_merge($form_data,$_POST);
		}
		
		$news_categories = Mynews_category::instance()->news_categories(0);
		$this->template->content = new View("site/news_category_add");
		$this->template->content->form_data = $form_data;
		$this->template->content->news_categories = $news_categories;
	}
	
	/**
	 * 编辑页面分类
	 */
	public function edit()
	{
		$id = $this->input->get('id');
		if(!$id)
		{
			remind::set('非法操作','site/news_category');
		}
        $data = Mynews_category::instance($id)->get();
		if($_POST)
		{
			$category_name = $this->input->post('category_name');
			$parent_id = $this->input->post('parent_id');
			if(empty($category_name))
			{
				remind::set(Kohana::lang('o_site.news_category_cannot_null'));
			}
			else if($parent_id==$id)
			{
                remind::set(Kohana::lang('o_site.doc_parent_category_cannot_self'));
            }
			else 
			{
				if(Mynews_category::instance()->name_exist($category_name,$id))
				{
					remind::set(Kohana::lang('o_site.news_category_has_exist'));
				}
				else 
				{
					if(Mynews_category::instance($id)->edit($_POST))
					{
						remind::set(Kohana::lang('o_global.update_success'),'site/news_category','success');
					}
					else
					{
						remind::set(Kohana::lang('o_global.update_error') . Mynews_category::instance($id)->error());
					}
				}
			}
            $data = array_merge($data,$_POST);
		}
		$news_categories = Mynews_category::instance()->news_categories(0);
		$this->template->content = new View("site/news_category_edit");
		$this->template->content->data = $data;		
		$this->template->content->news_categories = $news_categories;
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
       /* 验证是否可以操作 */
       if(!role::verify('news_category',site::id(),0))
       {
           $return_struct['msg'] = Kohana::lang('o_global.permission_enough');
           exit(json_encode($return_struct));
       }
       if(empty($id) || (empty($order) && $order != 0))
       {
           $return_struct['msg'] = Kohana::lang('o_global.bad_request');
           exit(json_encode($return_struct));
       }
       if(!is_numeric($order) || $order<0){
           $return_struct['msg'] = Kohana::lang('o_global.position_rule');
           exit(json_encode($return_struct));
       }
       if(Mynews_category::instance()->set_order($id,$order)){
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
