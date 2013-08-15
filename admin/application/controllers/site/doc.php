<?php defined('SYSPATH') OR die('No direct access allowed.');

class Doc_Controller extends Template_Controller {
	protected $current_flow = 'doc';
	public $site_id;

	public function __construct()
	{
		parent::__construct();
        /* 权限验证 */
        role::check('site_doc');
	}

	public function index()
	{
		$per_page = controller_tool::per_page();
        //列表排序
        $orderby_arr= array
            (
                0   => array('id'=>'DESC'),
                1   => array('id'=>'ASC'),
                2   => array('order'=>'ASC'),
                3   => array('order'=>'DESC'),
            );
       $orderby    = controller_tool::orderby($orderby_arr);
        // 初始化默认查询条件
        $query_struct = array(
            'where'=>array(
            ),
            'like'=>array(),
            'orderby'   => $orderby,
            'limit'     => array(
                'per_page'  =>$per_page,
                'offset'    =>0,
            ),
        );

		$doc = Mydoc::instance();
		$total = $doc->count($query_struct);
		$this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));

		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
		$docs = $doc->lists($query_struct);

        $this->template->content = new View("site/doc_list");
		$this->template->content->data = $docs;
		$this->template->content->title = "site doc list";
	}

	/**
	 * edit doc
	 */
	public function edit($id)
	{
		$doc = Mydoc::instance($id);
        $doc_data = array();

		if($_POST)
		{
            $doc_data = $_POST;
            //标签过滤
            tool::filter_strip_tags($_POST, array('content'));
			/* 判断url是否重复*/
	        $permalink = $this->input->post('permalink');
	        if($doc->url_exist($permalink, $id))
	        {
	        	remind::set(Kohana::lang('o_site.url_exist'));
	        }
            else
            {            			
    			$data = $_POST;
    			$data['updated'] = date('Y-m-d H:i:s');

    			if($doc->edit($data))
    			{
    			    //清楚uri_name缓存
    			    //$doc->clear_uris($doc_data['site_id']);
    				remind::set(Kohana::lang('o_global.update_success'),'site/doc', 'success');
    			}else{
    				remind::set(Kohana::lang('o_global.update_error'),'site/doc/edit/'.$id);
    			}
            }
		}
        
		$doc_data || $doc_data = $doc->get();
        
	    //分类树
		$category_list = '';
		$categories = Mydoc_category::instance()->doc_categories(0);
        if(!empty($categories)){
			foreach($categories as $category){
				$icon = '';
				for($i=1;$i<=$category['level_depth'];$i++){
					$icon = $icon.'--'; 
				}
                $selected = '';
				if($doc_data['category_id'] == $category['id']) $selected = 'selected';
				$category_list .= '<option value="'.$category['id'].'" '.$selected.'>'.$icon.$category['category_name'].'</option>';
			}
		}
        $this->template->content = new View("site/doc_edit");
		$this->template->content->data = $doc_data;
		$this->template->content->title = "site doc edit";
		$this->template->content->category_list = $category_list;
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
       if(Mydoc::instance()->set_order($id,$order)){
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
	 * add doc
	 */
	public function add()
	{
        $post = array();
		if($_POST)
		{
            $post = &$_POST;
			$site_next_flow = site::site_next_flow($this->current_flow);
			$submit_target = intval($this->input->post('submit_target'));

			$doc = Mydoc::instance();
            
            //标签过滤
            tool::filter_strip_tags($_POST, array('content'));
            
			/* 判断url是否重复*/
	        $permalink = $this->input->post('permalink');
	        if($doc->url_exist($permalink))
	        {
	        	remind::set(Kohana::lang('o_site.url_exist'));
	        }
            else
            {            
    			if($doc->add($_POST))
    			{
    				//判断添加成功去向
    				switch($submit_target)
    				{
        				case 1:
        					remind::set(Kohana::lang('o_global.add_success'),'site/doc/add','success');break;
        				case 2:
        					remind::set(Kohana::lang('o_global.add_success'),'site/doc/add','success');break;
        				default:
        					remind::set(Kohana::lang('o_global.update_success'),'site/doc','success');
    				}
    			}
    			else
    			{
    				remind::set(Kohana::lang('o_global.access_denied'));
    			}
            }
		}
            
		//分类树
		$category_list = '';
		$categories = Mydoc_category::instance()->doc_categories(0);
        if(!empty($categories)){
			foreach($categories as $category){
				$icon = '';
				for($i=1;$i<=$category['level_depth'];$i++){
					$icon = $icon.'--'; 
				}
                $select ='';
                if(isset($post['category_id']) && $post['category_id']==$category['id'])$select = ' selected';
				$category_list .= '<option value="'.$category['id'].'"'.$select.'>'.$icon.$category['category_name'].'</option>';
			}
		}
        
        $this->template->content = new View("site/doc_add");
		$this->template->content->post = $post;
		$this->template->content->title = "site doc add";
		$this->template->content->category_list = $category_list;
	}
    
   public function delete($id)
    {
        $doc = Mydoc::instance($id);
        $doc->delete();

        remind::set(Kohana::lang('o_global.delete_success'),'site/doc', 'success');
    }
    /**
     * 批量删除文案
     */
    public function batch_delete()
    {
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array();
        
        try {
            $doc_ids = $this->input->post('doc_ids');
            
            if(is_array($doc_ids) && count($doc_ids) > 0)
            {
                /* 删除失败的 */
                $failed_doc_names = '';
                /* 执行操作 */
                foreach($doc_ids as $doc_id)
                {
                    if(!Mydoc::instance($doc_id)->delete())
                    {
                        $failed_doc_names .= ' | ' . $doc_id;
                    }
                }
                if(empty($failed_doc_names))
                {
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_doc_success'),403);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_doc_names = trim($failed_doc_names,' | ');
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_doc_error',$failed_doc_names),403);
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
