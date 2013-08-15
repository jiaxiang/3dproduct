<?php
defined('SYSPATH') or die('No direct access allowed.');

class Message_Controller extends Template_Controller
{

	public function __construct()
	{
		parent::__construct();
		role::check('message');
	}

	/**
	 * 商户留言列表
	 */
	public function index()
	{
		$request_data = $this->input->get();
		$message_service = MessageService::get_instance();      
		$true = FALSE; 
		//判断用户的角色是不是管理员
		if($this->manager_is_admin == 1)
		{
			$true = TRUE;
		}
		//初始化请求结构体
		$query_struct = array (
			'where' => array (), 
			'like' => array (), 
			'orderby' => array (
				'id' => 'DESC' 
			), 
			'limit' => array (
				'per_page'  =>20,
				'offset'    =>0,
			),
		);
		
		if($true <> TRUE){
			$query_struct['where']['manager_id'] = $this->manager_id;
		}
        
		//列表排序
        $orderby_arr= array
            (
            	0   => array('id'=>'DESC'),
                1   => array('id'=>'ASC'),
                2   => array('manager_id'=>'ASC'),
                3   => array('manager_id'=>'DESC'),
                4   => array('status'=>'ASC'),
                5   => array('status'=>'DESC'),
                6   => array('title'=>'ASC'),
                7   => array('title'=>'DESC'),
                8   => array('email'=>'ASC'),
                9   => array('email'=>'DESC'),
                10  => array('create_timestamp'=>'ASC'),
                11  => array('create_timestamp'=>'DESC'),
                12  => array('ip'=>'ASC'),
                13  => array('ip'=>'DESC'),
                14  => array('is_reply'=>'ASC'),
                15  => array('is_reply'=>'DESC'),
            );

		// 排序处理 
		$orderby    = controller_tool::orderby($orderby_arr);
		if(isset($orderby) && !empty($orderby)){
			$query_struct['orderby'] = $orderby;
		}
		// 每页条目数
		controller_tool::request_per_page($query_struct,$request_data);

		$count = $message_service->query_count($query_struct);

		// 模板输出 分页
		$this->pagination       = new Pagination(array(
			'total_items'    => $count,
			'items_per_page' => $query_struct['limit']['per_page'],
		));

		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
		$query_struct['limit']['page'] = $this->pagination->current_page;

		if($true === TRUE)
		{
			$messages = $message_service->messages($query_struct);
			$this->template->content = new View("manage/message_manage_list");
			$this->template->content->messages = $messages;			
			$this->template->content->count = $count;
		}
		else
		{
			$messages = $message_service->get_message_reply_by_site_manage_role($query_struct);
			$this->template->content = new View("manage/message_list");
			$this->template->content->messages = $messages;
			$this->template->content->count = $count;
		}		
	}

	/**
	 * 添加留言VIEW
	 */
	public function add(){
		//管理没有发布留言的权限
		if($this->manager_is_admin == 1){
			remind::set(Kohana::lang('o_global.permission_enough'),'manage/message/');
		}
		$status = Kohana::config('message.status');
		$email = Mymanager::instance($this->manager_id)->get('email');
		$this->template->content = new View("manage/message_add");
		$this->template->content->email =  $email;
		$this->template->content->status = $status;
	}

	/**
	 * 留言提交
	 */
	public function do_add(){
		$request_data = $this->input->post();

		//流程
		$submit_target = intval($this->input->post('submit_target'));
		if($_POST)
		{			
			//数据验证
			$validation = Validation::factory($request_data)
				->add_rules('title',   'required', 'length[0,100]')
				->add_rules('status', 'required', 'digit')
				->add_rules('email', 'required', 'email')
				->add_rules('content', 'required', 'length[0,65535]');
			if (!$validation->validate()) {
				remind::set(Kohana::lang('o_global.input_error'),request::referrer());
			}

			$set_data = array(
				'manager_id'   =>    $this->manager_id,
				'title'        =>    $request_data['title'],
				'content'      =>    $request_data['content'],
				'status'       =>    $request_data['status'],
				'email'       =>     $request_data['email'],
				'ip'           =>    tool::get_long_ip(),
				'create_timestamp'   =>  date('Y-m-d H:i:s'),
			);       	

			if($return_data['id'] = MessageService::get_instance()->add($set_data)){
				//判断添加成功去向
				switch($submit_target)
				{
				case 1:
					remind::set(Kohana::lang('o_global.add_success'),'manage/message/add','success');
				default:
					remind::set(Kohana::lang('o_global.add_success'),'manage/message/','success');
				}
			}else{
				remind::set(Kohana::lang('o_global.add_error'),'manage/message/add');
			}	  
		}else{
			remind::set(Kohana::lang('o_global.add_error'),'manage/message/add');
		}
	}

	/**
	 * 留言查看VIEW
	 */
	public function edit(){
		$request_data = $this->input->get();
		$message_service = MessageService::get_instance();
		$messages = $message_service->get($request_data['id']);
		if(empty($messages['id'])){
			remind::set(Kohana::lang('o_global.access_denied'),request::referrer());
		}
		$true = FALSE;
		//判断用户的角色是不是管理员
		if($this->manager_is_admin == 1){
			$true = TRUE;
		}

		//查看留言信息
		$query_struct = array(
			'where'  => array(
				'id' => $messages['id'],			
			),
		);
		$message_replys = $message_service->get_message_reply_by_site_manage_role($query_struct);

		if($true === TRUE){
			$this->template->content = new View("manage/message_manage_edit");
			$this->template->content->message_replys = $message_replys;
		}else{
			$status = Kohana::config('message.status');
			$this->template->content = new View("manage/message_edit");
			$this->template->content->messages = $messages;
			$this->template->content->status = $status;
		}
	}

	/**
	 * 留言回复
	 */
	public function do_edit(){
		$request_data = $this->input->post();
        
		if($_POST)
		{			
			//数据验证
			$validation = Validation::factory($request_data)
				->add_rules('message_id',   'required', 'digit')
				->add_rules('content', 'required', 'length[0,65535]');
			if (!$validation->validate()) {
				remind::set(Kohana::lang('o_global.input_error'),request::referrer());
			}
			
			$message = MessageService::get_instance()->get($request_data['message_id']);
			if(empty($message['id'])){
				remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');
			}
			
			$set_data = array(
				'message_id'            =>    $message['id'],
				'manager_id'            =>    $this->manager_id,
				'content'               =>    $request_data['content'],  
				'update_timestamp'      =>    date('Y-m-d H:i:s'), 	   		
			);
            
			//判断此管理员是否已经回复过
			$message_reply_service = Message_replyService::get_instance();
			$manager_reply = $message_reply_service->get_reply_by_manager_id($this->manager_id, $message['id']);
			if(isset($manager_reply) && !empty($manager_reply['id'])){
				$message_reply_service->set($manager_reply['id'], $set_data);      	   		
			}else{
				$set_data['create_timestamp'] = date('Y-m-d H:i:s');
				$message_reply_service->add($set_data);
				//把留言的回复状态改为已回复
				$message_data['is_reply'] = MessageService::IS_MANAGER_REPLY;
				MessageService::get_instance()->set($message['id'], $message_data);				
			} 
			
			//发送邮件
			if($request_data['send_mail'] == '1')
			{
				$email_flag='the answer of your question';
				$email = $message['email'];
				$name = Mymanager::instance($message['manager_id'])->get('name');
				$content = '';
				$content .= 'Dear '.$name.' :<br>';
				$content .= $request_data['content'];
				if(mail::send($email,$email_flag,$content)){
					remind::set(Kohana::lang('o_global.mail_send_success'));
				}else{
					remind::set(Kohana::lang('o_global.mail_send_error'),'manage/message/edit?id='.$message['id']);
				}
			}
			remind::set(Kohana::lang('o_manage.message_success'),'manage/message','success');
		}else{
			remind::set(Kohana::lang('o_manage.message_error'),'manage/message/edit?id='.$message['id']);
		}

	}

	/**
	 * 商户编辑留言
	 */
	public function put(){
		$request_data = $this->input->post();

		if($_POST)
		{			
			//数据验证
			$validation = Validation::factory($request_data)
				->add_rules('id', 'required', 'digit')
				->add_rules('title',   'required', 'length[0,100]')
				->add_rules('email',   'required', 'email')
				->add_rules('status', 'required', 'digit')
				->add_rules('content', 'required', 'length[0,65535]');
			if (!$validation->validate()) {
				remind::set(Kohana::lang('o_global.input_error'),request::referrer());
			}
			
			$message = MessageService::get_instance()->get($request_data['id']);
			if(empty($message['id'])){
				remind::set(Kohana::lang('o_global.access_denied'),request::referrer());
			}
			$set_data = array(
				'title'        =>    $request_data['title'],
				'content'      =>    $request_data['content'],
				'email'      =>    $request_data['email'],
				'status'       =>    $request_data['status'],
				'ip'           =>    tool::get_long_ip(),
				'create_timestamp'   =>  date('Y-m-d H:i:s'),
			);
				MessageService::get_instance()->set($message['id'], $set_data);
				remind::set(Kohana::lang('o_manage.message_edit_success'),'manage/message','success');			
		}else{
			remind::set(Kohana::lang('o_manage.message_edit_error'),'manage/message/edit?id='.$message['id']);
		}
	}
	
	/**
	 * 删除留言信息
	 */
	public function delete()
	{
		$request_data = $this->input->get();

		if($this->manager_is_admin == 1)
		{
			MessageService::get_instance()->remove($request_data['id']);
			remind::set(Kohana::lang('o_global.delete_success'),'manage/message','success');					
		}
		else
		{
			remind::set(Kohana::lang('o_global.permission_enough'),'manage/message');
		}
	}
	
    /**
     * 批量删除留言
     */
    public function batch_delete()
    {
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array();
        /* 可管理的站点ID列表 */
        
        $loginfo = Role::get_manager();
        try {
            if($this->manager_is_admin != 1)
            {
                remind::set(Kohana::lang('o_global.permission_enough'),'manage/message');
            }
            $message_ids = $this->input->post('message_id');

            if(is_array($message_ids) && count($message_ids) > 0)
            {
                $message_service = MessageService::get_instance();
                /* 删除失败的 */
                $failed_message_names = '';

                foreach($message_ids as $message_id)
                {
                    if(!$message_service->remove($message_id))
                    {
                        $failed_message_names .= ',' . $message_id;
                    }
                }
                if(empty($failed_message_names))
                {
                	$return_struct['action'] = array(
                		'type'=>'location',
                		'url'=>url::base().'manage/message/'
            		);
                    throw new MyRuntimeException(Kohana::lang('o_manage.delete_message_success'),403);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_message_names = trim($failed_message_names,',');
                    throw new MyRuntimeException(Kohana::lang('o_manage.delete_message_error',$failed_message_names),403);
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
