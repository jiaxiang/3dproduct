<?php defined('SYSPATH') OR die('No direct access allowed.');

class Contact_us_Controller extends Template_Controller {

	public function index($status = NULL)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('contact_us');

		$this->template->content = new View("user/contact_us_list");
		//搜索功能
		$search_arr     = array('id','email','name','message','ip');
		$where          = array();
		$where_view     = array();
		if($search_arr)
		{
			foreach($search_arr as $search_value)
			{
				if($this->input->get('search_type')==$search_value&&strlen($this->input->get('search_value')))
				{
					$where[$search_value]       = $this->input->get('search_value');
					if($search_value == 'ip')
					{
						$where[$search_value]       = tool::myip2long($this->input->get('search_value'));
					}
				}
			}
			$where_view['search_type']	  = $this->input->get('search_type');
			$where_view['search_value']   = $this->input->get('search_value');
		}
        
		//列表排序
		$orderby_arr= array(
				0   => array('id'=>'DESC'),
				1   => array('id'=>'ASC'),
				2   => array('id'=>'ASC'),
				3   => array('id'=>'DESC'),
				4   => array('email'=>'ASC'),
				5   => array('email'=>'DESC'),
				6   => array('name'=>'ASC'),
				7   => array('name'=>'DESC'),
				8   => array('message'=>'ASC'),
				9   => array('message'=>'DESC'),
				10   => array('date_add'=>'ASC'),
				11   => array('date_add'=>'DESC'),
				12  => array('ip'=>'ASC'),
				13  => array('ip'=>'DESC'),
			);
		$orderby    = controller_tool::orderby($orderby_arr);
		//每页显示条数
		$per_page    = controller_tool::per_page();
		//调用分页
		if(isset($status) && $status == 'active') 
		{
			$where['active'] = 1;
		}
		$this->pagination = new Pagination(array(
			'total_items'    => Mycontact_us::instance()->count($where),
			'items_per_page' => $per_page,
		));
		//调用列表
		$this->template->content->contact_us_list = Mycontact_us::instance()->contact_uses($where,$orderby,$per_page,$this->pagination->sql_offset);
		$this->template->content->where	= $where_view;
	}

	/**
	 * 回复留言信息
	 */
	function do_edit($id) {
		//权限检查 得到所有可管理站点ID列表
		role::check('contact_us_manage');
		if(!$id)
		{
			remind::set(Kohana::lang('o_global.bad_request'),'user/contact_us');
		}

		if($_POST)
		{
			//获取留言信息，发邮件
			$contact_us = Mycontact_us::instance($id)->get();

			$email_flag		= 'contact_us';
			$title_param	= array();

			$content_param	= array();
			$content_param['{message}'] = strip_tags($_POST['return_message']);

			if(mail::send_mail($email_flag,$contact_us['email'],$from_email = '',$title_param,$content_param))
			{
				$is_receive = 1;
				remind::set(Kohana::lang('o_global.mail_send_success'),'','success');
			}
			else
			{
				$is_receive = 0;
				remind::set(Kohana::lang('o_global.mail_send_error'),'','error');
			}

			$data = $_POST;
			$data['active'] = 0;
			$data['is_receive'] = $is_receive;
			if(Mycontact_us::instance($id)->edit($data))
			{
				remind::set(Kohana::lang('o_user.message_handle_success'),request::referrer(),'success');
			}
			else
			{
				remind::set(Kohana::lang('o_user.message_handle_error'),request::referrer(),'error');
			}
		}
	}

	/**
	 * 修改信息
	 */
	function ajax_edit() {
		role::check('contact_us_manage');
		$return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
		$id = intval($this->input->get('id'));
		//权限验证
		if(!$id)
		{
			$return_struct['code'] = 400;
			$return_struct['msg'] = Kohana::lang('o_global.illegal_data');
			exit(json_encode($return_struct));
		}

		$return_template = $this->template = new View("user/contact_us_edit");

		$data = Mycontact_us::instance($id)->get();

		$this->template->data = $data;
		$return_str = $return_template->render();
		$return_struct['status'] = 1;
		$return_struct['code'] = 200;
		$return_struct['msg'] = 'Success';
		$return_struct['content'] = $return_str;
		exit(json_encode($return_struct));
	}

	/**
	 * 改变留言状态
	 */
	function do_active($id) {
		//权限验证
		role::check('contact_us_manage');
		if(!$id)
		{
			remind::set(Kohana::lang('o_global.bad_request'),'user/contact_us');
		}

		$contact_us = Mycontact_us::instance($id)->get();
		$data = array();
		$data['active'] = ($contact_us['active'] == 1)?0:1;

		if(Mycontact_us::instance($id)->edit($data))
		{
			remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
		}
		else
		{
			remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
		}
	}
	
    /**
     * 批量删除留言
     */
    public function batch_delete()
    {
        role::check('contact_us_manage');
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array();
        /* 可管理的站点ID列表 */
        
        $loginfo = Role::get_manager();
        try {
            $user_messageids = $this->input->post('user_messageids');
            
            if(is_array($user_messageids) && count($user_messageids) > 0)
            {
            	
                /* 初始化默认查询条件 */
                $query_struct = array(
                    'where'=>array(
                        'id'   => $user_messageids,
                    ),
                    'like'=>array(),
                    'limit'     => array(
                        'per_page'  =>300,
                        'offset'    =>0
                    ),
                );
                $user_messages = Mycontact_us::instance()->query_assoc($query_struct);
                
                /* 删除失败的 */
                $failed_message_names = '';
                /* 执行操作 */
                foreach($user_messages as $key=>$message)
                {
                    if(!Mycontact_us::instance($message['id'])->delete())
                    {
                        $failed_message_names .= ' | ' . $message['name'];
                    }
                }
                if(empty($failed_message_names))
                {
                	$return_struct['action'] = array(
                		'type'=>'location',
                		'url'=>url::base().'user/contact_us/'
            		);
                    throw new MyRuntimeException(Kohana::lang('o_user.delete_user_message_success'),200);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_message_names = trim($failed_message_names,' | ');
                    throw new MyRuntimeException(Kohana::lang('o_user.delete_user_message_error',$failed_message_names),403);
                }
            }
            else
            {
                throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
            }
        } catch (MyRuntimeException $ex) {
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            $return_struct['status'] = $return_struct['code']==200?1:0;
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
