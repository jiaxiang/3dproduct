<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_Controller extends Template_Controller {

	public $user_helper;
	public function __construct() {
		parent::__construct();
		role::check('users');
		$this->user_helper = user::get_instance();
	}

	/* 用户列表 */
	public function index() {
        /* 初始化默认查询条件 */
        $user_query_struct = array(
            'where'=>array(),
            'like'=>array(),
            'orderby'   => array(),
            'limit'     => array(
                'per_page'  => 20,
                'offset'    => 0,
            ),
        );

		/* 用户列表模板 */
		$this->template->content = new View("user/user_list");

		/* 搜索功能 */
		$search_arr      = array('id','username','email','mobile','name');
		$search_value    = $this->input->get('search_value');
		$search_type     = $this->input->get('search_type');
		$where_view      = array();
		if($search_arr){
			foreach($search_arr as $value){
				if($search_type == $value && strlen($search_value) > 0){
					$user_query_struct['like'][$value] = $search_value;
					//$user_query_struct['where'][$value] = $search_value;
					if($value == 'ip'){
						$user_query_struct['like'][$value] = tool::myip2long($search_value);
						//$user_query_struct['where'][$value] = tool::myip2long($search_value);
					}
				}
			}
			$where_view['search_type']	  = $search_type;
			$where_view['search_value']   = $search_value;
		}


		/* 列表排序 */
		$orderby_arr= array(
				0   => array('id'=>'DESC'),
				1   => array('id'=>'ASC'),
				4   => array('username'=>'ASC'),
				5   => array('username'=>'DESC'),
				6   => array('email'=>'ASC'),
				7   => array('email'=>'DESC'),
				8   => array('status'=>'ASC'),
				9   => array('status'=>'DESC'),
				10  => array('name'=>'ASC'),
				11  => array('name'=>'DESC'),
				12  => array('reg_time'=>'ASC'),
				13  => array('reg_time'=>'DESC'),
			);

		$orderby = controller_tool::orderby($orderby_arr);
		$user_query_struct['orderby'] = $orderby;

		/* 每页显示条数 */
		$per_page    = controller_tool::per_page();
		$user_query_struct['limit']['per_page'] = $per_page;

		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => Myuser::instance()->query_count($user_query_struct),
			'items_per_page' => $per_page,
		));
		$user_query_struct['limit']['offset'] = $this->pagination->sql_offset;

		$users = Myuser::instance()->query_assoc($user_query_struct);
		//找出所有的站点的用户等级信息
		//$user_levelservice = User_levelService::get_instance();
		$query_struct = array(
			'where'=>array(
				'active'=>1,
			),
		);

		/* $user_levels = $user_levelservice->index($query_struct);
		$tmp = array();
		foreach($user_levels as $user_level)
		{
			if($user_level['is_default'])
			{
				$tmp['default'] = $user_level;
			}
			$tmp[$user_level['id']] = $user_level;
		}
		$user_levels = $tmp;
		foreach ($users as $key => $value) {
            $users[$key]['level'] = '';

		} */

		/* 调用列表 */
		$this->template->content->user_list		= $users;
		$this->template->content->where			= $where_view;
		$this->template->content->mail_check_pwd = Kohana::config('site_config.site.register_mail_check_pwd');    //Mytool::hash(Mytool::hash($data['password']).$mail_check_pwd)
	}

	/**
	 * 修改用户信息
	 */
	function edit($id) {
		if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
			if(Myuser::instance($id)->edit($_POST))
			{
				remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			}
		}

		$this->template->content = new View("user/user_edit");

		$data = Myuser::instance($id)->get();

		$this->template->content->data = $data;
		//用户所在站点的会员等级的查找
		/* $user_level_service = User_levelService_Core::get_instance();
		$query_struct = array(
			'where'=>array(
				'active'	=>1,
			),
			'orderby'=>array(
				'is_special'=>'ASC',
			),
		);
		$user_levels = $user_level_service->index($query_struct);
		$tmp = array();
		foreach($user_levels as $user_level)
		{
			$tmp[$user_level['is_special']][] = $user_level;
		}
		$this->template->content->user_levels = $tmp; */
		//邮件模板
		$forget_mail = mail::mail_by_type('reset_password');
		//d($forget_mail);
		$this->template->content->forget_mail = $forget_mail;
		/* $reset_draw_mail = mail::mail_by_type('reset_draw_password');
		$this->template->content->reset_draw_mail = $reset_draw_mail; */

        /* 初始化默认查询条件 */
/*         $address_query_struct = array
        (
            'where'=>array('user_id' => $id),
            'like'=>array(),
            'orderby'   => array('date_add' => 'DESC'),
            'limit'     => array
            (
                'per_page'  => 5,
                'offset'    => 0,
            ),
        );

		$address_limit = 5;
		$this->template->content->address_list = Myaddress::instance()->query_assoc($address_query_struct);
 */	}

	/**
	 * 重置密码
	 */
	function do_edit_password($id) {
		if (!$id) {
			remind::set(Kohana::lang('o_global.bad_request'),'user/user');
		}
		if ($_POST) {
			$data = $_POST;
			$user = Myuser::get_by_id($id);

			//ucenter 修改密码 start
			$username = $user['username'];
			//	$oldpassword = $current_password;
			$newpassword = $data['password'];

            //标签过滤
            tool::filter_strip_tags($data);
			$data['password'] = $this->user_helper->encrypt_passwd($data['password']);

			if(Myuser::instance($id)->edit($data)) {
				$user = Myuser::instance($id)->get();
				//发邮件
				if($this->input->post('send_mail') == 1)
				{
					$email_flag='reset_password';
					$title_param	= array('{lastname}'=>$user['username']);

					$content_param	= array();
					$content_param['{lastname}']     = $user['username'];
					$content_param['{new_password}'] = $this->input->post('password');
					$content_param['{email}']	     = $user['email'];

					if(mail::send_mail($email_flag, $user['email'], $from_email = '', $title_param, $content_param))
					{
						remind::set(Kohana::lang('o_global.mail_send_success'),'','success');
					}
					else
					{
						remind::set(Kohana::lang('o_global.mail_send_error'),'','error');
					}
				}
				remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			}
		}
	}


	/**
	 * 重置取款密码
	 */
	function do_edit_draw_password($id) {
	    role::check('edit_user_draw_password');
		if(!$id)
		{
			remind::set(Kohana::lang('o_global.bad_request'),'user/user');
		}
		if($_POST) {
			$data = $_POST;
			$user = Myuser::get_by_id($id);

			//ucenter 修改密码 start
			$username = $user['lastname'];
			//	$oldpassword = $current_password;
			$newdraw_password = $data['draw_password'];

            //标签过滤
            tool::filter_strip_tags($data);
			$data['draw_password'] = sha1($data['draw_password']);

			if(Myuser::instance($id)->edit($data))
			{


				$user = Myuser::instance($id)->get();
				//发邮件
				if($this->input->post('send_mail') == 1)
				{
					$email_flag='reset_draw_password';
					$title_param	= array('{lastname}'=>$user['lastname']);

					$content_param	= array();
					$content_param['{lastname}']     = $user['lastname'];
					$content_param['{new_draw_password}'] = $this->input->post('draw_password');
					$content_param['{email}']	     = $user['email'];

					if(mail::send_mail($email_flag, $user['email'], $from_email = '', $title_param, $content_param))
					{
						remind::set(Kohana::lang('o_global.mail_send_success'),'','success');
					}
					else
					{
						remind::set(Kohana::lang('o_global.mail_send_error'),'','error');
					}
				}
				remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			}
		}
	}


	/**
	 * 添加新用户
	 */
	function add() {
		//权限检查 得到所有可管理站点ID列表
		role::check('user_add');
		$submit_target = intval($this->input->post('submit_target'));

		if($_POST) {
            $data = $_POST;

            //标签过滤
            tool::filter_strip_tags($data);

			$data['password'] = sha1($_POST['password']);
			$data['ip']		  = tool::get_long_ip();
			$data['active']	  = 1;
			//默认未激活状态
			$data['register_mail_active'] = 1;
			$user = Myuser::instance();
			if($user->user_exist($data))
			{
				remind::set(Kohana::lang('o_user.user_email_has_exist'),request::referrer(),'error');
			}
			if($user->add($data))
			{
				//发邮件
				if($this->input->post('send_mail') == 1)
				{
					$email_flag='reg';
					$title_param	= array();
					$content_param	= array();
					$content_param['{firstname}'] = $this->input->post('firstname');
					$content_param['{password}'] = $this->input->post('password');
					$content_param['{email}'] = $this->input->post('email');

					if(mail::send_mail($email_flag,$this->input->post('email'),$from_email = '',$title_param,$content_param))
					{
						//判断添加成功去向
						switch($submit_target)
						{
    						case 1:
    							remind::set(Kohana::lang('o_global.add_success'),'user/user/add','success');
    						default:
    							remind::set(Kohana::lang('o_global.add_success'),'user/user','success');
						}
					}
					else
					{
						remind::set(Kohana::lang('o_global.mail_send_error'),'','error');
					}
				}
				//判断添加成功去向
				switch($submit_target)
				{
    				case 1:
    					remind::set(Kohana::lang('o_global.add_success'),'user/user/add','success');
    				default:
    					remind::set(Kohana::lang('o_global.add_success'),'user/user','success');
				}
			}
			else
			{
				$errors = $user->error() ;
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
			}
		}
		$this->template->content = new View("user/user_add");
	}

	/**
	 * 群发消息
	 */
    public function site_msg(){
        role::check('user_site_msg');

        //找出用户的信息
        $query_struct = array(
            'where'=>array(
                ),
            'limit'=>array(
                'per_page' => 100,
                ),
         );

        $userids = $this->input->post('uid');
        $msg = $this->input->post('msg');
	    if(empty($userids)){
            remind::set(kohana::lang('o_user.select_user'), request::referrer(), 'error');
	    }

	    if(empty($msg)){
            remind::set("请填写消息", request::referrer(), 'error');
	    }

	    $query_struct['where']['id'] = explode(',', trim($userids, ','));
        $users = Myuser::instance()->query_assoc($query_struct);
        foreach($users as $u){
            if(empty($u['id']))continue;
            $data = array();
            $data['from_user_id'] = 0;
            $data['to_user_id']   = $u['id'];
            $data['name']         = $u['lastname'];
            $data['email']        = $u['email'];
            $data['tel']          = $u['tel'];
            $data['tel']          = $u['tel'];
            $data['message']      = $msg;
            Mycontact_us::instance()->add($data);
        }
        remind::set("消息群发成功", request::referrer(), 'success');
    }

	/**
	 * 导出会员
	 */
    public function export()
    {
        role::check('user_export');

        //找出用户的信息
        $query_struct = array(
            'where'=>array(
                ),
            'limit'=>array(
                'per_page' => 100000000000,
                ),
         );

        //判断是否为选择指定会员导出
        $userids = $this->input->get('userids');
        if($this->input->get('export_point_user'))
        {
	        if(!empty($userids))
	        {
	            $query_struct['where']['id'] = $userids;
	        }else{
	           remind::set(kohana::lang('o_user.select_user'),'user/user');
	        }
        }

        $users = Myuser::instance()->query_assoc($query_struct);
        $output = '';
        $fields = array(
                        'email'     =>'邮箱',
                        'title'     =>'称呼',
                        //'password'  =>'密码',
                        //'firstname' =>'姓',
                        'lastname'  =>'姓名',
                        'birthday'  =>'生日',
                        'date_add'  =>'添加时间',
                        'ip'        =>'IP',
                        'active'    =>'是否有效',
                    );
        foreach($fields as $value)
        {
            $output .= iconv('UTF-8', 'GB2312//IGNORE',$value.',');
        }
        $output .= @iconv('UTF-8', "GB2312//IGNORE", "\n");
        $domain = @iconv('UTF-8', 'GB2312//IGNORE',$domain.',');
        foreach($users as $key=>$user)
        {
            foreach($fields as $field=>$value)
            {
            	if($field == 'ip')
            	{
            	   $str = @iconv('UTF-8', "GB2312//IGNORE", $user[$field].",");
            	}else if($field == 'active'){
            	   $user[$field] = $user[$field]?'有效':'无效';
            	   $str = @iconv('UTF-8', "GB2312//IGNORE", $user[$field].",");
            	}else{
	            	$user[$field] = str_replace(',','',$user[$field]);
	            	$str = @iconv('UTF-8', "GB2312//IGNORE", $user[$field].",");
            	}
            	$output .= $str === false ?$user[$field].",":$str;
            }
            $output .= @iconv('UTF-8', "GB2312//IGNORE", "\n");
        }
        //输出信息
        $rand_name = date('Y-m-d') . '_' . mt_rand();
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Transfer-Encoding: binary ");

        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=users_$rand_name.csv");
        //header("Content-Disposition:attachment;filename={$filename}{$file}.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        exit($output);
    }

	/**
	 * 重置密码
	 */
	function do_delete($id) {
		//权限验证
		role::check('user_edit');
		if(!$id)
		{
			remind::set(Kohana::lang('o_global.bad_request'),'user/user');
		}
		if(Myuser::instance()->set_inactive($id))
		{
			$query_struct = array(
				'where' => array('user_id'=>$id),
			);
			$email_sign_up = Mynewsletter::instance()->query_assoc($query_struct);
			if(!empty($email_sign_up) && $email_sign_up[0]['active'] == 1)
			{
				Mynewsletter::instance()->set_inactive($email_sign_up[0]['id']);
			}
			remind::set(Kohana::lang('o_global.set_success'),request::referrer(),'success');
		}
		else
		{
			remind::set(Kohana::lang('o_global.set_error'),request::referrer(),'error');
		}
	}

	/**
	 * 批量删除、恢复用户
	 */
	public function batch($status='delete')
	{
		//初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try {
			$userids = $this->input->post('userids');
            $status = $status=='delete'?0:1;
            $level_id = $status;
			if(is_array($userids) && count($userids) > 0)
			{
		        /* 初始化默认查询条件 */
		        $user_query_struct = array(
		            'where'=>array(
		                'id'   => $userids,
		            ),
		            'like'=>array(),
		            'limit'     => array(
		                'per_page'  =>300,
		                'offset'    =>0
		            ),
		        );
		        $users = Myuser::instance()->query_assoc($user_query_struct);

		        /* 删除失败的用户 */
		        $failed_user_emails = '';

		        /* 执行操作 */
				foreach($users as $key=>$user)
		        {
		        	if(!Myuser::instance()->set_inactive($user['id'], $status, $level_id))
		        	{
		        		$failed_user_emails .= ' | ' . $user['email'];
		        	}
		        }
		        if(empty($failed_user_emails))
		        {
		        	throw new MyRuntimeException(Kohana::lang('o_global.set_success'), 200);
		        }
		        else
		        {
		        	/* 中转提示页面的停留时间 */
		        	$return_struct['action']['time'] = 10;
		        	$failed_carrier_names = trim($failed_carrier_names,' | ');
		        	throw new MyRuntimeException(Kohana::lang('o_global.set_error', $failed_user_emails), 500);
		        }
			}
			else
			{
				throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'), 403);
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

	public function active_user(){
		$return_struct = array(
		    'status'        => 0,
            'code'          => 501,
            'msg'           => kohana::lang('o_global.not_implemented'),
            'content'       => array(),
		);
		try {
			$user_id = $this->input->post('user_id');
			if(!$user_id)
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'),403);
			}
			$user = Myuser::instance($user_id)->get();
			$data['register_mail_active'] = 1;
			$data['id'] = $user_id;
			if($user=Myuser::instance($user_id)->edit($data))
			{
				$return_struct = array(
				    'status'        => 1,
		            'code'          => 200,
		            'msg'           => Kohana::lang('o_global.update_success'),
		            'content'       => $user,
				);
				exit(json_encode($return_struct));
			}

		} catch (MyRuntimeException $ex) {
			$return_struct = array(
			    'status'        => 0,
	            'code'          => 501,
	            'msg'           => $ex->getMessage(),
	            'content'       => array(),
			);
			if(request::is_ajax())
			{
				exit(json_encode($return_struct));
			}else{
				remind::set($ex->getMessage(),'user/user');
			}
		}
	}


	/**
	 * 查看用户信息
	 */
	function detail($id) {
		$this->template->content = new View("user/user_detail");
		$data = Myuser::instance($id)->get();
		$data['questions'] = Kohana::config('passwordquestion');
		$data['questions'] = $data['questions']['default'];
		//$user_handsel = users_handsel::get_instance();
		//$user_handsel_info = $user_handsel->get($id);
		//$data['user_handsel'] = $user_handsel_info;
		//$ubobj = User_bankService::get_instance();
		//$ub_info = $ubobj->get_results_by_uid($id);
		//$data['ub_info'] = $ub_info;
		$this->template->content->data = $data;
		//用户所在站点的会员等级的查找
		/* $user_level_service = User_levelService_Core::get_instance();
		$query_struct = array(
			'where'=>array(
				'active'	=>1,
			),
			'orderby'=>array(
				'is_special'=>'ASC',
			),
		);
		$user_levels = $user_level_service->index($query_struct);
		$tmp = array();
		foreach($user_levels as $user_level)
		{
			$tmp[$user_level['is_special']][] = $user_level;
		}
		$this->template->content->user_levels = $tmp; */
		//邮件模板
		//$forget_mail = mail::mail_by_type('reset_password');
		//$this->template->content->forget_mail = $forget_mail;
        /* $address_query_struct = array
        (
            'where'=>array('user_id' => $id),
            'like'=>array(),
            'orderby'   => array('date_add' => 'DESC'),
            'limit'     => array
            (
                'per_page'  => 5,
                'offset'    => 0,
            ),
        );

		$address_limit = 5;
		$this->template->content->address_list = Myaddress::instance()->query_assoc($address_query_struct); */
	}


	/* 资金操作日志 */
	public function account($id)
	{
        role::check('user_account');

        //初始化默认查询结构体
        $query_struct_default = array (
            'where' => array (
                'user_id' => $id,
            ),
            'orderby' => array (
                'id' => 'DESC'
            ),
            'limit' => array (
                'per_page' => 10,
                'page' => 1
            )
        );

        if (!empty($log_type))
        {
            $query_struct_default['where']['log_type'] = $log_type;
        }

        $request_data = $this->input->get();
        $timebeg = $this->input->get('begintime');
		$timeend = $this->input->get('endtime');

		if (!empty($timebeg))
		{
		    $query_struct_default['where']['add_time >='] = $timebeg;
		}
		else
		{
		    //$query_struct_default['where']['add_time >='] = date("Y-m-d H:i:s", time()-7*24*3600);
		}

	    if (!empty($timeend))
		{
		    $query_struct_default['where']['add_time <='] = $timeend;
		}

        //初始化当前查询结构体
        $query_struct_current = array ();

        //设置合并默认查询条件到当前查询结构体
        $query_struct_current = array_merge($query_struct_current, $query_struct_default);

        //列表排序
        $orderby_arr = array (
            0 => array (
                'id' => 'DESC'
            ),
            1 => array (
                'id' => 'ASC'
            ),
        );
        $orderby = controller_tool::orderby($orderby_arr);
        // 排序处理
        if(isset($request_data['orderby']) && is_numeric($request_data['orderby'])){
            $query_struct_current['orderby'] = $orderby;
        }
        $query_struct_current['orderby'] = $orderby;


        //求和统计结构体需要放在controller_tool操作之前
        $sum_struct_current = $query_struct_current;
        $sum_struct['in'] = $query_struct_current;
        $sum_struct['out'] = $query_struct_current;
        $sum_struct['recharge'] = $query_struct_current;

        $sum_struct['in']['where']['is_in'] = 0;
        $sum_struct['out']['where']['is_in'] = 1;
        $sum_struct['out']['recharge']['log_type'] = 1;

        //每页条目数
        controller_tool::request_per_page($query_struct_current, $request_data);

        //调用服务执行查询
        $acobj = Account_logService::get_instance();
        $return_data['count'] = $acobj->count($query_struct_current);    //统计数量
        $return_data['incount'] = $acobj->count($sum_struct['in']);      //统计数量
        $return_data['outcount'] = $acobj->count($sum_struct['out']);    //统计数量
        $return_data['rechargecount'] = $acobj->count($sum_struct['recharge']);    //统计数量

        $sums = array('price');
        if (!empty($sums))
        {
            //d($sum_struct_current);
            $return_data['sum'] = $acobj->query_sum($sum_struct_current, $sums); //求和统计

            if (empty($log_type))
            {
                $sum_struct_current['where']['is_in'] = 1;
                $return_data['outsum'] = $acobj->query_sum($sum_struct_current, array('price')); //求和统计
            }
        }

        //d($return_data['outsum']);

		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => $return_data['count'],
			'items_per_page' => $query_struct_current['limit']['per_page'],
		));

        $query_struct_current['limit']['page'] = $this->pagination->current_page;
        $return_data['list'] = $acobj->query_assoc($query_struct_current);

        $account_type = Kohana::config('acccount_type');
        //var_dump($account_type);
        $i = 0;
        foreach ($return_data['list'] as $rowlist)
        {
            $return_data['list'][$i] = $rowlist;
            $return_data['list'][$i]['type_name'] = empty($account_type[$rowlist['log_type']]) ? '' : $account_type[$rowlist['log_type']];
            $i++;
        }
        $return_data['user'] = user::get_instance()->get($id);

		/* 用户列表模板 */
		$this->template->content = new View("user/user_account");
		/* 调用列表 */
		$this->template->content->data = $return_data;
		$this->template->content->userid = $id;
	}

	/**
	 * 虚拟资金明细
	 * @param unknown_type $id
	 */
	public function virtual_money_account($id)
	{
		role::check('user_account');

		//初始化默认查询结构体
		$query_struct_default = array (
				'where' => array (
						'user_id' => $id,
				),
				'orderby' => array (
						'id' => 'DESC'
				),
				'limit' => array (
						'per_page' => 10,
						'page' => 1
				)
		);

		if (!empty($log_type))
		{
			$query_struct_default['where']['log_type'] = $log_type;
		}

		$request_data = $this->input->get();
		$timebeg = $this->input->get('begintime');
		$timeend = $this->input->get('endtime');

		if (!empty($timebeg))
		{
			$query_struct_default['where']['add_time >='] = $timebeg;
		}
		else
		{
			//$query_struct_default['where']['add_time >='] = date("Y-m-d H:i:s", time()-7*24*3600);
		}

		if (!empty($timeend))
		{
			$query_struct_default['where']['add_time <='] = $timeend;
		}

		//初始化当前查询结构体
		$query_struct_current = array ();

		//设置合并默认查询条件到当前查询结构体
		$query_struct_current = array_merge($query_struct_current, $query_struct_default);

		//列表排序
		$orderby_arr = array (
				0 => array (
						'id' => 'DESC'
				),
				1 => array (
						'id' => 'ASC'
				),
		);
		$orderby = controller_tool::orderby($orderby_arr);
		// 排序处理
		if(isset($request_data['orderby']) && is_numeric($request_data['orderby'])){
			$query_struct_current['orderby'] = $orderby;
		}
		$query_struct_current['orderby'] = $orderby;


		//求和统计结构体需要放在controller_tool操作之前
		$sum_struct_current = $query_struct_current;
		$sum_struct['in'] = $query_struct_current;
		$sum_struct['out'] = $query_struct_current;
		$sum_struct['recharge'] = $query_struct_current;

		$sum_struct['in']['where']['is_in'] = 0;
		$sum_struct['out']['where']['is_in'] = 1;
		$sum_struct['out']['recharge']['log_type'] = 1;

		//每页条目数
		controller_tool::request_per_page($query_struct_current, $request_data);

		//调用服务执行查询
		$acobj = Account_virtual_logService::get_instance();
		$return_data['count'] = $acobj->count($query_struct_current);    //统计数量
		$return_data['incount'] = $acobj->count($sum_struct['in']);      //统计数量
		$return_data['outcount'] = $acobj->count($sum_struct['out']);    //统计数量
		$return_data['rechargecount'] = $acobj->count($sum_struct['recharge']);    //统计数量

		$sums = array('price');
		if (!empty($sums))
		{
			//d($sum_struct_current);
			$return_data['sum'] = $acobj->query_sum($sum_struct_current, $sums); //求和统计

			if (empty($log_type))
			{
				$sum_struct_current['where']['is_in'] = 1;
				$return_data['outsum'] = $acobj->query_sum($sum_struct_current, array('price')); //求和统计
			}
		}

		//d($return_data['outsum']);

		/* 调用分页 */
		$this->pagination = new Pagination(array(
				'total_items'    => $return_data['count'],
				'items_per_page' => $query_struct_current['limit']['per_page'],
		));

		$query_struct_current['limit']['page'] = $this->pagination->current_page;
		$return_data['list'] = $acobj->query_assoc($query_struct_current);

		$account_type = Kohana::config('acccount_type');
		//var_dump($account_type);
		$i = 0;
		foreach ($return_data['list'] as $rowlist)
		{
			$return_data['list'][$i] = $rowlist;
			$return_data['list'][$i]['type_name'] = empty($account_type[$rowlist['log_type']]) ? '' : $account_type[$rowlist['log_type']];
			$i++;
		}
		$return_data['user'] = user::get_instance()->get($id);

		/* 用户列表模板 */
		$this->template->content = new View("user/user_account_virtual");
		/* 调用列表 */
		$this->template->content->data = $return_data;
		$this->template->content->userid = $id;
	}


	/* 资金操作详细日志 */
	public function user_money($user_id = NULL, $acid = NULL, $log_type = NULL)
	{
        role::check('user_account');
        $return_data = array();

        //初始化默认查询结构体
        $query_struct_default = array (
            'orderby' => array (
                'id' => 'DESC'
            ),
            'limit' => array (
                'per_page' => 10,
                'page' => 1
            )
        );

        $return_data['get']['user_id'] = NULL;
        $return_data['get']['acid'] = NULL;
        $return_data['get']['log_type'] = NULL;

        if (!empty($user_id))
        {
            $query_struct_default['where']['user_id'] = $user_id;
            $return_data['get']['user_id'] = $user_id;
        }
        if (!empty($acid))
        {
            $query_struct_default['where']['account_log_id'] = $acid;
            $return_data['get']['acid'] = $acid;
        }
        if (!empty($log_type))
        {
            $query_struct_default['where']['log_type'] = $log_type;
            $return_data['get']['log_type'] = $log_type;
        }

        $request_data = $this->input->get();
        $timebeg = $this->input->get('begintime');
		$timeend = $this->input->get('endtime');

		if (!empty($timebeg))
		{
		    $query_struct_default['where']['add_time >='] = $timebeg;
		}
	    if (!empty($timeend))
		{
		    $query_struct_default['where']['add_time <='] = $timeend;
		}

        //初始化当前查询结构体
        $query_struct_current = array ();

        //设置合并默认查询条件到当前查询结构体
        $query_struct_current = array_merge($query_struct_current, $query_struct_default);

        //列表排序
        $orderby_arr = array (
            0 => array (
                'id' => 'DESC'
            ),
            1 => array (
                'id' => 'ASC'
            ),
        );

        $orderby = controller_tool::orderby($orderby_arr);

        // 排序处理
        if(isset($request_data['orderby']) && is_numeric($request_data['orderby'])){
            $query_struct_current['orderby'] = $orderby;
        }
        $query_struct_current['orderby'] = $orderby;

        //每页条目数
        controller_tool::request_per_page($query_struct_current, $request_data);

        //调用服务执行查询
        $acobj = Money_logService::get_instance();
        $return_data['count'] = $acobj->count($query_struct_current);    //统计数量
        $sum_list = $acobj->query_assoc($query_struct_current);

        $return_data['USER_MONEY']['in'] = 0;
        $return_data['USER_MONEY']['out'] = 0;
        $return_data['BONUS_MONEY']['in'] = 0;
        $return_data['BONUS_MONEY']['out'] = 0;
        $return_data['FREE_MONEY']['in'] = 0;
        $return_data['FREE_MONEY']['out'] = 0;

        foreach ($sum_list as $rowsum)
        {
            if ($rowsum['log_type'] == 'USER_MONEY')
            {
                if ($rowsum['is_in'] == 1)
                {
                    $return_data['USER_MONEY']['out'] += $rowsum['price'];
                }
                else
                {
                    $return_data['USER_MONEY']['in'] += $rowsum['price'];
                }
            }
            elseif ($rowsum['log_type'] == 'BONUS_MONEY')
            {
                if ($rowsum['is_in'] == 1)
                {
                    $return_data['BONUS_MONEY']['out'] += $rowsum['price'];
                }
                else
                {
                    $return_data['BONUS_MONEY']['in'] += $rowsum['price'];
                }
            }
            elseif ($rowsum['log_type'] == 'FREE_MONEY')
            {
                if ($rowsum['is_in'] == 1)
                {
                    $return_data['FREE_MONEY']['out'] += $rowsum['price'];
                }
                else
                {
                    $return_data['FREE_MONEY']['in'] += $rowsum['price'];
                }
            }
        }

		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => $return_data['count'],
			'items_per_page' => $query_struct_current['limit']['per_page'],
		));

        $query_struct_current['limit']['page'] = $this->pagination->current_page;
        $return_data['list'] = $acobj->query_assoc($query_struct_current);

        $money_type = Kohana::config('money_type');

        $i = 0;
        foreach ($return_data['list'] as $rowlist)
        {
            $return_data['list'][$i] = $rowlist;
            $return_data['list'][$i]['type_name'] = empty($account_type[$rowlist['log_type']]) ? '' : $account_type[$rowlist['log_type']];
            $i++;
        }
        $return_data['user'] = user::get_instance()->get($id);

		/* 用户列表模板 */
		$this->template->content = new View("user/user_money");
		/* 调用列表 */
		$this->template->content->data = $return_data;
		$this->template->content->money_type = $money_type;
	}



	/**
	 * 充值扣款
	 */
	function recharge($id) {
		//权限检查 得到所有可管理站点ID列表
		role::check('user_recharge');

		if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);

            $userobj = user::get_instance();
            $usermoney = $userobj->get_user_money($id);
            $money_type = $_POST['money_type'];
            $money_type_set = Kohana::config('money_type');
            if (array_key_exists($money_type, $money_type_set)) {
            	$money_type_name = $money_type_set[$money_type];
            	$update_money = $_POST['money'];

	            //记录日志
	            $data_log = array();
	            $data_log['order_num'] = date('YmdHis').rand(0, 99999);
	            $data_log['user_id'] = $id;
	            $data_log['log_type'] = 6;                 //参照config acccount_type 设置
	            $data_log['is_in'] = 0;
	            $data_log['price'] = $_POST['money'];
	            $data_log['user_money'] = $usermoney;
	            $data_log['memo'] = $_POST['memo'];
				$um = user_money::get_instance()->update_money($data_log['is_in'], $data_log['user_id'], $data_log['price'], $data_log['log_type'], $data_log['order_num'], $money_type, $data_log['memo']);

	            //添加日志
	            $logs_data = array();
	            $logs_data['manager_id'] = $this->manager_id;
	            $logs_data['user_log_type'] = 29;
	            $logs_data['ip'] = tool::get_long_ip();
	            $logs_data['memo'] = "成功为用户{$id}充值{$money_type_name}{$_POST['money']}";
	            ulog::instance()->add($logs_data);
				remind::set($money_type_name.'充值成功',request::referrer(),'success');
            }

		}
		$this->template->content = new View("user/user_recharge");
		$this->template->content->data = Myuser::instance($id)->get();
	}

	/**
	 * 虚拟充值
	 * @param unknown_type $id
	 */
	function recharge_virtual_money($id) {
		//权限检查 得到所有可管理站点ID列表
		role::check('user_recharge');

		if($_POST) {
			//标签过滤
			tool::filter_strip_tags($_POST);

			$userobj = user::get_instance();
			$usermoney = $userobj->get_user_virtual_money($id);


			if ($_POST['money'] > 0) {
				$money_type_name = '竞波币';
				//记录日志
				$data_log = array();
				$data_log['order_num'] = date('YmdHis').rand(0, 99999);
				$data_log['user_id'] = $id;
				$data_log['log_type'] = 6;                 //参照config acccount_type 设置
				$data_log['is_in'] = 0;
				$data_log['price'] = $_POST['money'];
				$data_log['user_money'] = $usermoney;
				$data_log['memo'] = $_POST['memo'];
				account_virtual_log::get_instance()->add($data_log);

				//$um = user_money::get_instance()->update_money($data_log['is_in'], $data_log['user_id'], $data_log['price'], $data_log['log_type'], $data_log['order_num'], $money_type, $data_log['memo']);

				//添加日志
				$logs_data = array();
				$logs_data['manager_id'] = $this->manager_id;
				$logs_data['user_log_type'] = 29;
				$logs_data['ip'] = tool::get_long_ip();
				$logs_data['memo'] = "成功为用户{$id}充值{$money_type_name}{$_POST['money']}";
				ulog::instance()->add($logs_data);
				remind::set($money_type_name.'充值成功',request::referrer(),'success');
			}

		}
		$this->template->content = new View("user/user_recharge_virtual");
		$this->template->content->data = Myuser::instance($id)->get();
	}

}
