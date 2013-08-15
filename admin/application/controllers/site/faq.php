<?php defined('SYSPATH') OR die('No direct access allowed.');

class Faq_Controller extends Template_Controller {
	protected $current_flow = 'faq';

	public function __construct()
	{
		parent::__construct();
		role::check('site_faq');
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
        
		$faq = Myfaq::instance();
		$total = $faq->count($query_struct);
		
		$this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));

		$faqs = $faq->lists($query_struct);

        $this->template->content = new View("site/faq_list");
		$this->template->content->data = $faqs;
		$this->template->content->title = "site faq list";
	}

	public function edit($id)
	{
		$faq = Myfaq::instance($id);
		$faq_data = $faq->get();

		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST, array('content'));
            
			if($faq->edit($_POST))
			{
				remind::set(Kohana::lang('o_global.update_success'),'site/faq', 'success');
			}else{
				remind::set(Kohana::lang('o_global.update_error'),'site/faq/edit/'.$id);
			}
		}

        $this->template->content = new View("site/faq_edit");
		$this->template->content->data = $faq_data;
		$this->template->content->title = "site faq edit";
	}

	public function delete($id)
	{
		$faq = Myfaq::instance($id);
		$faq->delete();
		remind::set(Kohana::lang('o_global.delete_success'),'site/faq', 'success');
	}

	public function add()
	{
		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST, array('content'));
			
			$site_next_flow = site::site_next_flow($this->current_flow);
			$submit_target = intval($this->input->post('submit_target'));

			$faq = Myfaq::instance();
			if($faq->add($_POST))
			{
				//判断添加成功去向
				switch($submit_target)
				{
				case 1:
					remind::set(Kohana::lang('o_global.add_success'),'site/faq/add','success');
				case 2:
					remind::set(Kohana::lang('o_global.add_success'),$site_next_flow['url'],'success');
				default:
					remind::set(Kohana::lang('o_global.add_success'),'site/faq','success');
				}
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),'site/faq/add');
			}
		}

        $this->template->content = new View("site/faq_add");
		$this->template->content->title = "site faq add";
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
       if(Myfaq::instance()->set_order($id,$order)){
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
     * 批量删除faq
     */
    public function batch_delete()
    {
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array();
        
        try {
            $faq_ids = $this->input->post('faq_ids');
            
            if(is_array($faq_ids) && count($faq_ids) > 0)
            {                
                /* 删除失败的 */
                $failed_faq_names = '';
                /* 执行操作 */
                foreach($faq_ids as $key=>$faq_id)
                {
                    if(!Myfaq::instance($faq_id)->delete())
                    {
                        $failed_faq_names .= ' | ' . $faq_id;
                    }
                }
                if(empty($failed_faq_names))
                {
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_faq_success'),403);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_faq_names = trim($failed_faq_names,' | ');
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_faq_error',$failed_faq_names),403);
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
