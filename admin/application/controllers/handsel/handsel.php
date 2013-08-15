<?php defined('SYSPATH') OR die('No direct access allowed.');

class Handsel_Controller extends Template_Controller {

	public function __construct(){
		parent::__construct();
        
        /* 权限验证  */
        role::check('handsel');
	}

	public function index(){
		
		//列表排序
        $orderby_arr= array(
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
            'orderby'   => $orderby,
        );
        
		$link  = Myhandsel::instance();
		$links = $link->lists($query_struct);
		$total = $link->count($query_struct);

        $this->template->content = new View("handsel/handsel");
		$this->template->content->data = $links;
		$this->template->content->total = $total;
	}

	public function edit($id)
	{
		$link = Myhandsel::instance($id);

		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST);
            
			if($link->edit($_POST))
			{
				remind::set(Kohana::lang('o_global.update_success'),'handsel/handsel','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),'handsel/handsel/edit');
			}
		}

		$link_data = $link->get();
        $this->template->content = new View("handsel/handsel_edit");
		$this->template->content->data = $link_data;
	}
	
	public function user_handsel_list($id='')
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
		
        /* 初始化默认查询条件 */
		if(empty($id))
		{
	        $user_query_struct = array(
	            'where'=>array('check_status'=>2),
	            'orderby'   => $orderby,
	            'limit'     => array(
	                'per_page'  => $per_page,
	                'offset'    => 0,
	            ),
	        ); 
		}else
		{
			$user_query_struct = array(
	            'where'=>array('check_status'=>3),
	            'orderby'   => $orderby,
	            'limit'     => array(
	                'per_page'  => $per_page,
	                'offset'    => 0,
	            ),
	        );
		}
		
		$user_handsel_list = Myuser::instance();
		$total =$user_handsel_list->count($user_query_struct);
		$this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		
		$user_query_struct['limit']['offset'] = $this->pagination->sql_offset; 
		$user_handsel_list = Myuser::instance();
		$user_list = $user_handsel_list->lists($user_query_struct);
		
		
		$this->template->content = new View("handsel/user_handsel_list");
		$this->template->content->user_list = $user_list;
		$this->template->content->total = $total;
		$this->template->content->title = "site news list";
		
	}
	public function check_status($id='')
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('user_edit');
		
		if(!empty($id))
		{
			$data['check_status'] = 2;
			if(Myuser::instance($id)->edit_handsel_users($id,$data))
			{
				$user = user::get_instance();
				//彩金赠送开始						
				$handsel = handsel::get_instance();
				$handsel_info = $handsel->get(1);
				//$data_money = array('free_money' => $handsel_info['total']);
				//$flag = $user->update_user_free_money($id,$data_money);
				//account_log::get_instance()->add($data_log);
				user_money::get_instance()->update_money(0, $id, $handsel_info['total'], 7, date('YmdHis').rand(0, 99999), 'FREE_MONEY', '赠送彩金');
				//彩金赠送结束
				
				remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			}
		}
	}
	
	public function check_status_fail($id='')
	{
		//从彩金表中删除未通过的用户
		$user_handsel = users_handsel::get_instance();
		$data['uid'] = $id;
		$user_handsel->delete($data);
		
		$data['check_status'] = 0;
		Myuser::instance($id)->edit_handsel_users($id,$data);
		remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
	}
	
	public function delete($id)
	{
		$link = Mysite_link::instance($id);
		$link->delete();
		remind::set(Kohana::lang('o_global.delete_success'),'site/link', 'success');
	}

	public function add()
	{
		if($_POST)
		{
            //标签过滤
            tool::filter_strip_tags($_POST);
			
			$link = Mysite_link::instance();
			if($link->add($_POST))
			{
				remind::set(Kohana::lang('o_global.add_success'),'site/link','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),'site/link/add/');
			}
		}

        $this->template->content = new View("site/link_edit");
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
       if(Mysite_link::instance()->set_order($id,$order)){
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
     * 批量删除友情链接
     */
    public function batch_delete()
    {
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array();

        try {
            $link_ids = $this->input->post('link_ids');
            
            if(is_array($link_ids) && count($link_ids) > 0)
            {
                /* 删除失败的 */
                $failed_link_names = '';
                /* 执行操作 */
                foreach($link_ids as $link_id)
                {
                    if(!Mysite_link::instance($link_id)->delete())
                    {
                        $failed_link_names .= ' | ' . $link_id;
                    }
                }
                if(empty($failed_link_names))
                {
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_link_success'),200);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_link_names = trim($failed_link_names,' | ');
                    throw new MyRuntimeException(Kohana::lang('o_site.delete_link_error',$failed_link_names),403);
                }
            }
            else
            {
                throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
            }
        } catch (MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct);
        }
    }
	
	/**
	 * 改变状态
	 */
	function do_active($id){
		if(!$id){
			remind::set(Kohana::lang('o_global.bad_request'), 'site/link');
		}
        
		$data = Mysite_link::instance($id)->get();
            
		if($data['id']<=0){
			remind::set(Kohana::lang('o_global.bad_request'), 'site/link');
		}
        $data['updated'] = date('Y-m-d H:i:s');
        $data['status'] = $data['status']==0?1:0;
		if(Mysite_link::instance($id)->edit($data)){
			remind::set(Kohana::lang('o_global.update_success'), 'site/link','success');
		}else{
			remind::set(Kohana::lang('o_global.update_error'), 'site/link','error');
		}
	}
    
}
