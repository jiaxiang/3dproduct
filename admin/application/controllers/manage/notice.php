<?php defined('SYSPATH') OR die('No direct access allowed.');

class Notice_Controller extends Template_Controller {

	public function __construct()
	{
		parent::__construct();
        role::check('manage_notice');
	}
    
	/**
	 * 列表
	 */
	public function index()
	{
		//查询条件
		$query_struct = array(
		  'orderby'=>array('id' => 'DESC'),
		);
		
		/* 搜索 */
		$search_arr = array('title','content');
		$search_type = $this->input->get('search_type');
		$search_value = $this->input->get('search_value');
		if(!empty($search_value) && in_array($search_type,$search_arr))
		{
			$query_struct['like'][$search_type] = $search_value;
		}
		$where_view['search_value'] = $search_value;
		
		//调用分页
		$per_page = controller_tool::per_page();
		$query_struct['per_page'] = $per_page;
		$this->pagination = new Pagination(
			array(
				'total_items'    => Mynotice::instance()->count($query_struct),
				'items_per_page' => $per_page,
			)
		);
		$query_struct['per_page'] = $this->pagination->sql_offset;
		$notice = Mynotice::instance()->lists($query_struct);

		foreach($notice as $k=>$v)
		{
			$notice[$k]['content_small'] = strip_tags(text::limit_words($v['content'],30));
			$notice[$k]['manager_id'] = Mymanager::instance($v['manager_id'])->get('name');			
		}
		//VIEW
		$this->template->content = new View("manage/notice_list");
		$this->template->content->notice = $notice;	
		$this->template->content->where = $where_view;	
	}
    
	/**
	 * 添加公告
	 */
	public function add()
	{
		if($_POST) 
		{
			$_POST['manager_id'] = $this->manager_id;
			$_POST['title'] = strip_tags($_POST['title']);
			if(Mynotice::instance()->add($_POST))
			{
				remind::set(Kohana::lang('o_global.add_success'),'manage/notice','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),'manage/notice/add');
			}
		}
		$this->template->content = new View("manage/notice_add");
	}
    
	/**
	 * 修改公告
	 */
	public function edit($id)
	{
		$notice = Mynotice::instance($id)->get();
		if(!$notice['id']) 
		{
			remind::set(Kohana::lang('o_manage.notice_not_exist'),'manage/notice');
		}

		if($_POST) 
		{
			$_POST['manager_id'] = $this->manager_id;
			$_POST['title'] = strip_tags($_POST['title']);
			if(Mynotice::instance($id)->edit($_POST)) 
			{
				remind::set(Kohana::lang('o_global.update_success'),'manage/notice','success');
			}
			else 
			{
				remind::set(Kohana::lang('o_global.update_error'),'manage/notice');
			}
		}

		$this->template->content = new View("manage/notice_edit");
		$this->template->content->data = $notice;
	}
	
	/**
	 * ajax get notice content
	 */
	public function ajax_content()
	{
		$return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
		if(request::is_ajax()) 
		{
			$id = intval($this->input->get('id'));

			$notice = Mynotice::instance($id)->get();

			$return_template = $this->template = new View('template_blank');
			$this->template->content = $notice['content'];
			$return_str = $return_template->render();
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = 'Success';
			$return_struct['content'] = $return_str;
			exit(json_encode($return_struct));
		}
	}
    
	/**
	 * delete notice
	 */
	public function delete($id)
	{
		if(Mynotice::instance($id)->delete()) 
		{
			remind::set(Kohana::lang('o_global.delete_success'),'manage/notice','success');
		}
		else
		{
			$error = Mynotice::instance($id)->error();
			remind::set(Kohana::lang('o_global.delete_error') . $error,'manage/notice');
		}
	}
	
    /**
     * 批量删除公告
     */
    public function batch_delete()
    {
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array();
        
        try {
            if($this->manager_is_admin != 1)
            {
                throw new MyRuntimeException(Kohana::lang('o_global.permission_enough'),403);
            }
            $notice_ids = $this->input->post('notice_ids');
            
            if(is_array($notice_ids) && count($notice_ids) > 0)
            {
                /* 删除失败的 */
                $failed_notice_names = '';
                /* 执行操作 */
                foreach($notice_ids as $notice_id)
                {
                    if(!Mynotice::instance($notice_id)->delete())
                    {
                        $failed_notice_names .= ' | ' . $notice_id;
                    }
                }
                if(empty($failed_notice_names))
                {
                    remind::set(Kohana::lang('o_manage.delete_notice_success'),'manage/notice','success');
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_notice_names = trim($failed_notice_names,' | ');
                    remind::set(Kohana::lang('o_manage.delete_notice_error',$failed_notice_names),'manage/notice');
                    //throw new MyRuntimeException(Kohana::lang('o_manage.delete_notice_error',$failed_notice_names),403);
                }
            }
            else
            {
                throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
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
