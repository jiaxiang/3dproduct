<?php defined('SYSPATH') OR die('No direct access allowed.');

class Order_doc_Controller extends Template_Controller 
{
	public function __construct() 
	{
		parent::__construct(); //this must be included
		if($this -> is_ajax_request() == TRUE)
		{
			$this -> template = new View('layout/default_json');
		}
	}
    
	/**
	 * 收款单管理
	 */
	public function payment()
	{
		/* 权限检查*/
		role::check('order_doc_payment');
        
		// 初始化默认查询条件
        $query_struct = array(
            'where'=>array(),
            'like'=>array(),
            'orderby'   => array(
                'id'   =>'DESC',
            ),
            'limit'     => array(
                'per_page'  =>20,
                'offset'    =>0,
            ),
        );
        
		$this->template->content = new view("order/order_doc/payment");		
		/* 订单状态，支付状态，物流状态 */
		$order_status = Kohana::config('order.order_status');
		$pay_status = Kohana::config('order.pay_status');
		$ship_status = Kohana::config('order.ship_status');
		/* 搜索功能 */
		$search_arr = array('payment_num','email');

		/* 搜索视图返回 */
		$search_type = $this->input->get('search_type');
		$search_value = $this->input->get('search_value');
		if($search_arr)
		{
			foreach($search_arr as $value)
			{
				if(($search_type == $value) && !empty($search_value))
				{
					$query_struct['like'][$search_type] = $search_value;
					//$query_struct['where'][$search_type] = $search_value;
				}
			}
		}
        
		$where_view = array();
		$where_view['search_type'] = $search_type;
		$where_view['search_value'] = $search_value;		
		
		/* 得到默认每页显示多少条 */
		$per_page = controller_tool::per_page();
		$total_items = Myorder_payment_log::instance()->count($query_struct);

		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => $total_items,
			'items_per_page' => $per_page,
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
		$query_struct['limit']['per_page'] = $per_page;
		
		/* 收款单显示*/
		$payment_list = array();
		$payments = Myorder_payment_log::instance()->lists($query_struct);
		foreach($payments as $key=>$payment)
		{
			$payment_list[$key]['id'] = $payment['id'];                                                         			//收款单id
			$payment_list[$key]['payment_num'] = $payment['payment_num'];                                       			//收款单号
			$payment_list[$key]['order_num'] = Myorder::instance($payment['order_id'])->get('order_num');       			//订单号
			$payment_list[$key]['email'] = $payment['email'];                                                   			//用户邮箱
			$payment_list[$key]['manager'] = Mymanager::instance($payment['manager_id'])->get('username'); 					//操作员
			$payment_list[$key]['payment_method'] = $payment['payment'];     												//支付方式
			$payment_list[$key]['currency'] = $payment['currency'];															//币种
			$payment_list[$key]['amount'] = $payment['amount'];																//支付金额
			$payment_list[$key]['content_admin'] = $payment['content_admin'];												//管理员备注
			$payment_list[$key]['content_user'] = $payment['content_user'];													//用户备注
			$payment_list[$key]['date_add'] = $payment['date_add'];															//添加时间
			$payment_list[$key]['trans_no'] = (empty($payment['trans_no']))?'无':$payment['trans_no'];						//交易号
			$payment_list[$key]['receive_account'] = (empty($payment['receive_account']))?'无':$payment['receive_account'];	//收款账号
			$payment_list[$key]['is_send_email'] = $payment['is_send_email'];												//是否发送邮件
			/* 支付状态*/
			if($payment['status'] == 'succ')
			{
				$payment_list[$key]['payment_status'] = Kohana::lang('o_order.order_status_success');												
			}
			else
			{
                $payment_list[$key]['payment_status'] = Kohana::lang('o_order.order_status_error');				
			}
		}
		/* 调用列表 */
		$this->template->content->where				= $where_view;
		$this->template->content->order_status_list	= $order_status;
		
		$this->template->content->order_status	    = $order_status;
		$this->template->content->pay_status	    = $pay_status;
		$this->template->content->ship_status		= $ship_status;
		
		$this->template->content->payment_list  	= $payment_list;		
	}	
	/**
	 *  收款单导出
	 */
	public function payment_export()
	{
        role::check('order_doc_payment');
		// 初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try {
	        /* 验证是否选择了收款单 */
			if(!isset($_POST['payment_ids']))
			{
				throw new MyRuntimeException(Kohana::lang('o_order.select_payment_export'),403);
			}
			
			if($_POST)
			{
				$payment_ids = $this->input->post('payment_ids');//array(1,2);
				
				/* 得到当前的导出配置 */
				$output_field_ids = array(1,2,4,5,6,7,8,9,10,11,12,13,14,15);
				
				/* 导出格式错误 */
				if(!is_array($output_field_ids) || count($output_field_ids) < 1)
				{
					throw new MyRuntimeException(Kohana::lang('o_order.export_config_error'),403);
				}
	
				$xls = doc_export::instance();
				
				//$xls->debug(true);//开测试模式
				$xls->set_output_field_ids($output_field_ids);
				
				/* 订单状态，支付状态，物流状态 */
				$order_status = Kohana::config('order.order_status');
				$pay_status = Kohana::config('order.pay_status');
				$ship_status = Kohana::config('order.ship_status');
			
				$result = array();
				
				foreach($payment_ids as $payment_id){
					$payment = array();
					$payment_info = Myorder_payment_log::instance($payment_id)->get();
					$payment['id'] = $payment_info['id'];                                                         					//收款单id
					$payment['payment_num'] = $payment_info['payment_num'];                                       					//收款单号
					$payment['order_num'] = Myorder::instance($payment_info['order_id'])->get('order_num');       					//订单号
					$payment['email'] = $payment_info['email'];                                                   					//用户邮箱
					$payment['manager'] = Mymanager::instance($payment_info['manager_id'])->get('username'); 	  					//操作员
					$payment['payment_method'] = $payment_info['payment'];     									  					//支付方式
					$payment['currency'] = $payment_info['currency'];											  					//币种
					$payment['amount'] = $payment_info['amount'];												  					//支付金额
					$payment['content_admin'] = $payment_info['content_admin'];									 					//管理员备注
					$payment['content_user'] = $payment_info['content_user'];									  					//用户备注
					$payment['date_add'] = $payment_info['date_add'];											  					//添加时间
					$payment['trans_no'] = (empty($payment_info['trans_no']))?'无':$payment_info['trans_no'];						//交易号
					$payment['receive_account'] = (empty($payment_info['receive_account']))?'无':$payment_info['receive_account'];	//收款账号
					$payment['is_send_email'] = (empty($payment_info['is_send_email']))?'否':'是';									//是否发送邮件
					/* 支付状态*/
					if($payment_info['status'] == 'succ')
					{
						$payment['payment_status'] = '已支付';												
					}
					else
					{
						remind::set(Kohana::lang('o_order.order_status_error'),url::base(),'error');
					}
					
					$xls->set_order_line($payment);
				}
				$xls->output();
				exit;
			}
		} catch (MyRuntimeException $ex) {
			$return_struct['status'] = 0;
			$return_struct['code']   = $ex->getCode();
			$return_struct['msg']    = $ex->getMessage();
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
    	
	/**
	 *  收款单批量删除
	 */
    public function payment_delete()
    {
        role::check('order_doc_payment');
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => Kohana::lang('o_global.not_implemented'),
            'content'       => array(),
        );
       try {
            /* 初始化返回数据 */
            $return_data = array();
            
            /* 收集请求数据 */
            $request_data = $this->input->post();
            
            /* 数据验证*/
            if(!isset($request_data['payment_ids']) OR empty($request_data['payment_ids'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'),400);
            }
            
            $payment_log = Myorder_payment_log::instance();
            
            /* 根据ID列表获取收款单*/
            $query_struct = array('where' => array(
            	'id' => $request_data['payment_ids'],
            ));
            $payments = $payment_log->query_assoc($query_struct);
           
            /* 删除单据*/
            foreach ($payments as $payment) {
                $payment_log->delete($payment['id']);
            }

            /* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = Kohana::lang('o_global.delete_success');
            $return_struct['content']= $return_data;
            $return_struct['action'] = array(
                'type'=>'location',
                'url'=>url::base().'order/order_doc/payment/'
            );
            
            /* 请求类型 */
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                /* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                /* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                /* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                /* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                /* 当前应用专用数据*/
                $this->template->content->title = Kohana::config('site.name');
            }

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new  View('info');
                $this->template->content = $content;
                /* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                /* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
	/**
	 *  显示收款单细节
	 */    
	public function payment_detail($id)
	{
		/* 权限检查*/
		role::check('order_doc_payment');
		
		$payment = Myorder_payment_log::instance($id)->get();
		
		$payment_list = array();
		$payment_list['id'] = $payment['id'];                                                         				//收款单id
		$payment_list['payment_num'] = $payment['payment_num'];                                       				//收款单号
		$payment_list['order_num'] = Myorder::instance($payment['order_id'])->get('order_num');       				//订单号
		$payment_list['email'] = $payment['email'];                                                   				//用户邮箱
		$payment_list['manager'] = Mymanager::instance($payment['manager_id'])->get('username'); 	  				//操作员
		$payment_list['payment_method'] = $payment['payment'];     									  				//支付方式
		$payment_list['currency'] = $payment['currency'];											  				//币种
		$payment_list['amount'] = $payment['amount'];												  				//支付金额
		$payment_list['content_admin'] = $payment['content_admin'];									  				//管理员备注
		$payment_list['content_user'] = $payment['content_user'];								      				//用户备注
		$payment_list['date_add'] = $payment['date_add'];											  				//添加时间
		$payment_list['trans_no'] = (empty($payment['trans_no']))?'无':$payment['trans_no'];							//交易号
		$payment_list['receive_account'] = (empty($payment['receive_account']))?'无':$payment['receive_account'];	//收款账号
		$payment_list['is_send_email'] = (empty($payment['is_send_email']))?'否':'是';								//是否发送邮件			
		/* 支付状态*/
		if($payment['status'] == 'succ')
		{
			$payment_list['payment_status'] = '已支付';												
		}
		else
		{
			remind::set(Kohana::lang('o_order.order_status_error'),url::base(),'error');
		}
		
		$this->template = new View('layout/commonfix_html');
		$this->template->content = new view("order/order_doc/doc_detail/payment_detail");
		$this->template->content->payment_list = $payment_list;
	}
    
	/**
	 * 退款单管理
	 */
	public function refund()
	{
		/* 权限检查*/
		role::check('order_doc_refund');
		// 初始化默认查询条件
        $query_struct = array(
            'where'=>array(),
            'like'=>array(),
            'orderby'   => array(
                'id'   =>'DESC',
            ),
            'limit'     => array(
                'per_page'  =>20,
                'offset'    =>0,
            ),
        );
        
		$this->template->content = new view("order/order_doc/refund");		
		/* 订单状态，支付状态，物流状态 */
		$order_status = Kohana::config('order.order_status');
		$pay_status = Kohana::config('order.pay_status');
		$ship_status = Kohana::config('order.ship_status');
		/* 搜索功能 */
		$search_arr = array('refund_num','email');

		/* 搜索视图返回 */
		$where_view = array();
		$search_type = $this->input->get('search_type');
		$search_value = $this->input->get('search_value');
		if($search_arr)
		{
			foreach($search_arr as $value)
			{
				if(($search_type == $value) && !empty($search_value))
				{
					$query_struct['like'][$search_type] = $search_value;
					//$query_struct['where'][$search_type] = $search_value;
				}
			}
		}
		$where_view['search_type'] = $search_type;
		$where_view['search_value'] = $search_value;
		
		/* 得到默认每页显示多少条 */
		$per_page = controller_tool::per_page();
		$total_items = Myorder_refund_log::instance()->count($query_struct);

		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => $total_items,
			'items_per_page' => $per_page,
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
		$query_struct['limit']['per_page'] = $per_page;
		
		/* 收款单显示*/
		$refund_list = array();
		$refund_method = Kohana::config('order.refund_method');
		$refund_reason = Kohana::config('order.refund_reason');
		$refunds = Myorder_refund_log::instance()->lists($query_struct);
		foreach($refunds as $key=>$refund)
		{
			$refund_list[$key]['id'] = $refund['id'];                                                           //退款单id
			$refund_list[$key]['refund_num'] = $refund['refund_num'];                                           //退款单号
			$refund_list[$key]['order_num'] = Myorder::instance($refund['order_id'])->get('order_num');         //订单号
			$refund_list[$key]['email'] = $refund['email'];              										//用户邮箱
			$refund_list[$key]['manager'] = Mymanager::instance($refund['manager_id'])->get('username');   		//操作员
			$refund_list[$key]['refund_reason'] = 
				(!empty($refund_reason[$refund['reason_id']]['name']))?
				$refund_reason[$refund['reason_id']]['name']:
				'不明';					//退款原因
			$refund_list[$key]['refund_method'] = 
				(!empty($refund_method[$refund['refundmethod_id']]['name']))?
				$refund_method[$refund['refundmethod_id']]['name']:
				'不明';           //退款方式
			$refund_list[$key]['currency'] = $refund['currency'];												//币种
			$refund_list[$key]['refund_amount'] = $refund['refund_amount'];										//退款金额
			$refund_list[$key]['refund_status'] = $pay_status[$refund['refund_status_id']]['name'];				//退款状态
			$refund_list[$key]['content_admin'] = $refund['content_admin'];									    //管理员备注
			$refund_list[$key]['content_user'] = $refund['content_user'];										//用户备注
			$refund_list[$key]['date_add'] = $refund['date_add'];												//添加时间			
			$refund_list[$key]['is_send_email'] = $refund['is_send_email'];										//是否发送邮件
		}
		/* 调用列表 */
		$this->template->content->where				= $where_view;
		$this->template->content->order_status_list	= $order_status;
		
		$this->template->content->order_status	    = $order_status;
		$this->template->content->pay_status	    = $pay_status;
		$this->template->content->ship_status		= $ship_status;
		
		$this->template->content->refund_list  	    = $refund_list;		
	}	
    
	/**
	 *  退款单导出
	 */
	public function refund_export()
	{
        role::check('order_doc_refund');
		// 初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try {
	        /* 验证是否选择了收款单 */
			if(!isset($_POST['refund_ids']))
			{
				throw new MyRuntimeException(Kohana::lang('o_order.select_refund_export'),403);
			}
			
			if($_POST)
			{
				$refund_ids = $this->input->post('refund_ids');//array(1,2);
				
				/* 得到当前的导出配置 */
				$output_field_ids = array(16,2,4,5,17,7,18,19,20,12,13,14,15);
				
				/* 导出格式错误 */
				if(!is_array($output_field_ids) || count($output_field_ids) < 1)
				{
					throw new MyRuntimeException(Kohana::lang('o_order.export_config_error'),403);
				}
	
				$xls = doc_export::instance();
				
				//$xls->debug(true);//开测试模式
				$xls->set_output_field_ids($output_field_ids);
				
				/* 订单状态，支付状态，物流状态 */
				$order_status = Kohana::config('order.order_status');
				$pay_status = Kohana::config('order.pay_status');
				$ship_status = Kohana::config('order.ship_status');
				$refund_method = Kohana::config('order.refund_method');
				$refund_reason = Kohana::config('order.refund_reason');
							
				$result = array();
				
				foreach($refund_ids as $refund_id){
					$refund = array();
					$refund_info = Myorder_refund_log::instance($refund_id)->get();
					$refund['id'] = $refund_info['id'];                                                           	//退款单id
					$refund['refund_num'] = $refund_info['refund_num'];                                           	//退款单号
					$refund['order_num'] = Myorder::instance($refund_info['order_id'])->get('order_num');         	//订单号
					$refund['email'] = $refund_info['email'];              											//用户邮箱
					$refund['manager'] = Mymanager::instance($refund_info['manager_id'])->get('username');   		//操作员
					$refund['refund_reason'] = $refund_reason[$refund_info['reason_id']]['name'];					//退款原因
					$refund['refund_method'] = $refund_method[$refund_info['refundmethod_id']]['name'];           	//退款方式
					$refund['currency'] = $refund_info['currency'];													//币种
					$refund['refund_amount'] = $refund_info['refund_amount'];										//退款金额
					$refund['refund_status'] = $pay_status[$refund_info['refund_status_id']]['name'];				//退款状态
					$refund['content_admin'] = $refund_info['content_admin'];									    //管理员备注
					$refund['content_user'] = $refund_info['content_user'];											//用户备注
					$refund['date_add'] = $refund_info['date_add'];													//添加时间
					$refund['is_send_email'] = (empty($refund_info['is_send_email']))?'否':'是';						//是否发送邮件
					
					$xls->set_order_line($refund);
				}
				$xls->output();
				exit;
			}
		} catch (MyRuntimeException $ex) {
			$return_struct['status'] = 0;
			$return_struct['code']   = $ex->getCode();
			$return_struct['msg']    = $ex->getMessage();
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
    
	/**
	 *  退款单批量删除
	 */
    public function refund_delete()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => Kohana::lang('o_global.not_implemented'),
            'content'       => array(),
        );
       try {
            /* 初始化返回数据 */
            $return_data = array();
            
            /* 收集请求数据*/
            $request_data = $this->input->post();
            
            //* 数据验证*/
            if(!isset($request_data['refund_ids']) OR empty($request_data['refund_ids'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'),400);
            }
            
            $refund_log = Myorder_refund_log::instance();
            /* 根据ID列表获取退款单*/
            $query_struct = array('where' => array(
            	'id' => $request_data['refund_ids'],
            ));
            $refunds = $refund_log->query_assoc($query_struct);
                
            /* 删除单据*/
            foreach ($refunds as $refund) {
                $refund_log->delete($refund['id']);
            }

            /* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = Kohana::lang('o_global.delete_success');
            $return_struct['content']= $return_data;
            $return_struct['action'] = array(
                'type'=>'location',
                'url'=>url::base().'order/order_doc/refund/'
            );
            
            /* 请求类型 */
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                /* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                /* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                /* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                /* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                
            }

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new  View('info');
                $this->template->content = $content;
                /* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                /* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
	/**
	 *  显示退款单细节
	 */    
	public function refund_detail($id)
	{
		/* 权限检查*/
		role::check('order_doc_refund');
		
		$refund = Myorder_refund_log::instance($id)->get();
		$pay_status = Kohana::config('order.pay_status');
		$refund_method = Kohana::config('order.refund_method');
		$refund_reason = Kohana::config('order.refund_reason');
		
		$refund_list = array();
		$refund_list['id'] = $refund['id'];                                                           	//退款单id
		$refund_list['refund_num'] = $refund['refund_num'];                                           	//退款单号
		$refund_list['order_num'] = Myorder::instance($refund['order_id'])->get('order_num');         	//订单号
		$refund_list['email'] = $refund['email'];              											//用户邮箱
		$refund_list['manager'] = Mymanager::instance($refund['manager_id'])->get('username');   		//操作员
		$refund_list['refund_reason'] = $refund_reason[$refund['reason_id']]['name'];					//退款原因
		$refund_list['refund_method'] = $refund_method[$refund['refundmethod_id']]['name'];           	//退款方式
		$refund_list['currency'] = $refund['currency'];													//币种
		$refund_list['refund_amount'] = $refund['refund_amount'];										//退款金额
		$refund_list['refund_status'] = $pay_status[$refund['refund_status_id']]['name'];				//退款状态
		$refund_list['content_admin'] = $refund['content_admin'];									    //管理员备注
		$refund_list['content_user'] = $refund['content_user'];											//用户备注
		$refund_list['date_add'] = $refund['date_add'];													//添加时间	
		$refund_list['is_send_email'] = (empty($refund['is_send_email']))?'否':'是';						//是否发送邮件		
		
		$this->template = new View('layout/commonfix_html');
		$this->template->content = new view("order/order_doc/doc_detail/refund_detail");
		$this->template->content->refund_list = $refund_list;
	}	
    
	/**
	 * 发货单管理
	 */
	public function ship()
	{
		/* 权限检查*/
		role::check('order_doc_ship');
		// 初始化默认查询条件
        $query_struct = array(
            'where'=>array(),
            'like'=>array(),
            'orderby'   => array(
                'id'   =>'DESC',
            ),
            'limit'     => array(
                'per_page'  =>20,
                'offset'    =>0,
            ),
        );
        
		$this->template->content = new view("order/order_doc/ship");		
		/* 订单状态，支付状态，物流状态 */
		$order_status = Kohana::config('order.order_status');
		$pay_status = Kohana::config('order.pay_status');
		$ship_status = Kohana::config('order.ship_status');
		/* 搜索功能 */
		$search_arr = array('ship_num','email');

		/* 搜索视图返回 */
		$where_view = array();
		$search_type = $this->input->get('search_type');
		$search_value = $this->input->get('search_value');
		if($search_arr)
		{
			foreach($search_arr as $value)
			{
				if(($search_type == $value) && !empty($search_value))
				{
					$query_struct['like'][$search_type] = $search_value;
					//$query_struct['where'][$search_type] = $search_value;
				}
			}
		}
		$where_view['search_type'] = $search_type;
		$where_view['search_value'] = $search_value;
		
		/* 得到默认每页显示多少条 */
		$per_page = controller_tool::per_page();
		$total_items = Myorder_ship_log::instance()->count($query_struct);

		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => $total_items,
			'items_per_page' => $per_page,
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
		$query_struct['limit']['per_page'] = $per_page;
		
		/* 收款单显示*/
		$ship_list = array();
		$ship_status = Kohana::config('order.ship_status');
		$ships = Myorder_ship_log::instance()->lists($query_struct);
		$delivery_service = DeliveryService::get_instance();
		foreach($ships as $key=>$ship)
		{
			$ship_list[$key]['id'] = $ship['id'];                                                           //发货单id
			$ship_list[$key]['ship_num'] = $ship['ship_num'];                                           	//发货单号
			$ship_list[$key]['order_num'] = Myorder::instance($ship['order_id'])->get('order_num');         //订单号
			$ship_list[$key]['carrier'] = $delivery_service->get_delivery_name($ship['carrier_id']);
			$ship_list[$key]['email'] = $ship['email'];              										//用户邮箱
			$ship_list[$key]['manager'] = Mymanager::instance($ship['manager_id'])->get('username');   		//操作员
			$ship_list[$key]['currency'] = $ship['currency'];												//币种
			$ship_list[$key]['total_shipping'] = $ship['total_shipping'];									//运费金额
			$ship_list[$key]['ems_num'] = $ship['ems_num'];													//物流单号
			$ship_list[$key]['ship_status'] = $ship_status[$ship['ship_status_id']]['name'];				//发货状态
			$ship_list[$key]['content_admin'] = $ship['content_admin'];									    //管理员备注
			$ship_list[$key]['content_user'] = $ship['content_user'];										//用户备注
			$ship_list[$key]['date_add'] = $ship['date_add'];												//添加时间	
			$ship_list[$key]['is_send_email'] = $ship['is_send_email'];										//是否发送邮件		
			/* 得到订单货品信息*/
			$ship_list[$key]['send_data'] = array();
			$send_data = unserialize($ship['send_data']);
			$order_products = Myorder_product::instance()->get_order_products_by_order_id($ship['order_id']);
			foreach($order_products as $val)
			{
				foreach($send_data as $v)
				{
					if($v['id'] == $val['id'])
					{
						$ship_list[$key]['send_data']['SKU'] = $val['SKU'];
						$ship_list[$key]['send_data']['name'] = $val['name'];
						$ship_list[$key]['send_data']['attribute_style'] = (empty($val['attribute_style']))?'默认':$val['attribute_style'];
						$ship_list[$key]['send_data']['quantity'] = $val['quantity'];
						$ship_list[$key]['send_data']['shipnum'] = $v['ship_num'];
					}
				}
			}
			if(empty($ship_list[$key]['send_data']))
			{
				remind::set(Kohana::lang('o_order.product_ship_load_error'),url::base(),'error');
			}
		}
		/* 调用列表 */
		$this->template->content->where				= $where_view;
		$this->template->content->order_status_list	= $order_status;
		
		$this->template->content->order_status	    = $order_status;
		$this->template->content->pay_status	    = $pay_status;
		$this->template->content->ship_status		= $ship_status;
		
		$this->template->content->ship_list  	    = $ship_list;		
	}
    	
	/**
	 *  发货单导出
	 */
	public function ship_export()
	{
	    //权限验证
	    role::check('order_doc_ship');
        
		// 初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try {
	        /* 验证是否选择了发货单 */
			if(!isset($_POST['ship_ids']))
			{
				throw new MyRuntimeException(Kohana::lang('o_order.select_ship_export'),403);
			}
			
			if($_POST)
			{
				$ship_ids = $this->input->post('ship_ids');//array(1,2);
				
				/* 得到当前的导出配置 */
				$output_field_ids = array(21,2,22,4,5,7,23,24,25,12,13,14,15);
				
				/* 导出格式错误 */
				if(!is_array($output_field_ids) || count($output_field_ids) < 1)
				{
					throw new MyRuntimeException(Kohana::lang('o_order.export_config_error'),403);
				}
	
				$xls = doc_export::instance();
				
				//$xls->debug(true);//开测试模式
				$xls->set_output_field_ids($output_field_ids);
				
				$ship_status = Kohana::config('order.ship_status');
				
				foreach($ship_ids as $ship_id){
					$ship = array();
					$ship_info = Myorder_ship_log::instance($ship_id)->get();
					$ship['id'] = $ship_info['id'];                                                           	//发货单id
					$ship['ship_num'] = $ship_info['ship_num'];                                           		//发货单号
					$ship['order_num'] = Myorder::instance($ship_info['order_id'])->get('order_num');         	//订单号
					$ship['carrier'] = DeliveryService::get_instance()->get_delivery_name($ship_info['carrier_id']); 
					$ship['email'] = $ship_info['email'];              											//用户邮箱
					$ship['manager'] = Mymanager::instance($ship_info['manager_id'])->get('username');   		//操作员
					$ship['currency'] = $ship_info['currency'];													//币种
					$ship['total_shipping'] = $ship_info['total_shipping'];										//运费金额
					$ship['ems_num'] = $ship_info['ems_num'];													//物流单号
					$ship['ship_status'] = $ship_status[$ship_info['ship_status_id']]['name'];					//发货状态
					$ship['content_admin'] = $ship_info['content_admin'];									    //管理员备注
					$ship['content_user'] = $ship_info['content_user'];											//用户备注
					$ship['date_add'] = $ship_info['date_add'];													//添加时间
					$ship['is_send_email'] = (empty($ship_info['is_send_email']))?'否':'是';						//是否发送邮件	
					
					$xls->set_order_line($ship);
				}
				$xls->output();
				exit;
			}
		} catch (MyRuntimeException $ex) {
			$return_struct['status'] = 0;
			$return_struct['code']   = $ex->getCode();
			$return_struct['msg']    = $ex->getMessage();
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
	/**
	 *  发货单批量删除
	 */
    public function ship_delete()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => Kohana::lang('o_global.not_implemented'),
            'content'       => array(),
        );
       try {
            /* 初始化返回数据 */
            $return_data = array();
            
            /* 收集请求数据*/
            $request_data = $this->input->post();

            /* 数据验证*/
            if(!isset($request_data['ship_ids']) OR empty($request_data['ship_ids'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'),400);
            }
            
            $ship_log = Myorder_ship_log::instance();
            /* 根据ID列表获取发货单*/
            $query_struct = array('where' => array(
            	'id' => $request_data['ship_ids'],
            ));
            $ships = $ship_log->query_assoc($query_struct);
            
            /* 删除单据*/
            foreach ($ships as $ship) {
                $ship_log->delete($ship['id']);
            }

            /* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = Kohana::lang('o_global.delete_success');
            $return_struct['content']= $return_data;
            $return_struct['action'] = array(
                'type'=>'location',
                'url'=>url::base().'order/order_doc/ship/'
            );
            
            /* 请求类型 */
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                /* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                /* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                /* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                /* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;                
            }

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new  View('info');
                $this->template->content = $content;
                /* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                /* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
	/**
	 *  显示发货单细节
	 */    
	public function ship_detail($id)
	{
		/* 权限检查*/
		role::check('order_doc_ship');
		
		$ship = Myorder_ship_log::instance($id)->get();
		$ship_status = Kohana::config('order.ship_status');
		
		$ship_list = array();
		$ship_list['id'] = $ship['id'];                                                           	//发货单id
		$ship_list['ship_num'] = $ship['ship_num'];                                           		//发货单号
		$ship_list['order_num'] = Myorder::instance($ship['order_id'])->get('order_num');         	//订单号
		$ship_list['carrier'] = DeliveryService::get_instance()->get_delivery_name($ship['carrier_id']); 
		$ship_list['email'] = $ship['email'];              											//用户邮箱
		$ship_list['manager'] = Mymanager::instance($ship['manager_id'])->get('username');   		//操作员
		$ship_list['currency'] = $ship['currency'];													//币种
		$ship_list['total_shipping'] = $ship['total_shipping'];										//运费金额
		$ship_list['ems_num'] = $ship['ems_num'];													//物流单号
		$ship_list['ship_status'] = $ship_status[$ship['ship_status_id']]['name'];					//发货状态
		$ship_list['content_admin'] = $ship['content_admin'];									    //管理员备注
		$ship_list['content_user'] = $ship['content_user'];											//用户备注
		$ship_list['date_add'] = $ship['date_add'];													//添加时间	
		$ship_list['is_send_email'] = (empty($ship['is_send_email']))?'否':'是';						//是否发送邮件		
		/* 得到订单货品信息*/
		$ship_list['send_data'] = array();
		$send_data = unserialize($ship['send_data']);
		$order_products = Myorder_product::instance()->get_order_products_by_order_id($ship['order_id']);
		foreach($order_products as $val)
		{
			foreach($send_data as $k=>$v)
			{
				if($v['id'] == $val['id'])
				{
					$ship_list['send_data'][$k]['SKU'] = $val['SKU'];
					$ship_list['send_data'][$k]['name'] = $val['name'];
					$ship_list['send_data'][$k]['attribute_style'] = (empty($val['attribute_style']))?'默认':$val['attribute_style'];
					$ship_list['send_data'][$k]['quantity'] = $val['quantity'];
					$ship_list['send_data'][$k]['shipnum'] = $v['ship_num'];
				}
			}
		}
		
		$this->template = new View('layout/commonfix_html');
		$this->template->content = new view("order/order_doc/doc_detail/ship_detail");
		$this->template->content->ship_list = $ship_list;
	}
	/**
	 * 退货单管理
	 */
	public function return_product()
	{
		/* 权限检查*/
		role::check('order_doc_return');
		// 初始化默认查询条件
        $query_struct = array(
            'where'=>array(),
            'like'=>array(),
            'orderby'   => array(
                'id'   =>'DESC',
            ),
            'limit'     => array(
                'per_page'  =>20,
                'offset'    =>0,
            ),
        );
        
		$this->template->content = new view("order/order_doc/return");		
		/* 订单状态，支付状态，物流状态 */
		$order_status = Kohana::config('order.order_status');
		$pay_status = Kohana::config('order.pay_status');
		$return_status = Kohana::config('order.return_status');
		/* 搜索功能 */
		$search_arr = array('return_num','email');

		/* 搜索视图返回 */
		$where_view = array();
		$search_type = $this->input->get('search_type');
		$search_value = $this->input->get('search_value');
		if($search_arr)
		{
			foreach($search_arr as $value)
			{
				if(($search_type == $value) && !empty($search_value))
				{
					$query_struct['like'][$search_type] = $search_value;
					//$query_struct['where'][$search_type] = $search_value;
				}
			}
		}
		$where_view['search_type'] = $search_type;
		$where_view['search_value'] = $search_value;
		
		/* 得到默认每页显示多少条 */
		$per_page = controller_tool::per_page();
		$total_items = Myorder_return_log::instance()->count($query_struct);

		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => $total_items,
			'items_per_page' => $per_page,
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
		$query_struct['limit']['per_page'] = $per_page;
		
		/* 收款单显示*/
		$return_list = array();
		$ship_status = Kohana::config('order.ship_status');
		$returns = Myorder_return_log::instance()->lists($query_struct);
		foreach($returns as $key=>$return)
		{
			$return_list[$key]['id'] = $return['id'];                                                           //退货单id
			$return_list[$key]['return_num'] = $return['return_num'];                                           //退货单号
			$return_list[$key]['order_num'] = Myorder::instance($return['order_id'])->get('order_num');         //订单号
			$return_list[$key]['carrier'] = DeliveryService::get_instance()->get_delivery_name($return['carrier_id']); 
			$return_list[$key]['email'] = $return['email'];              										//用户邮箱
			$return_list[$key]['manager'] = Mymanager::instance($return['manager_id'])->get('username');   		//操作员
			$return_list[$key]['currency'] = $return['currency'];												//币种
			$return_list[$key]['total_shipping'] = $return['total_shipping'];									//运费金额
			$return_list[$key]['return_status'] = $ship_status[$return['return_status_id']]['name'];			//退货状态
			$return_list[$key]['content_admin'] = $return['content_admin'];									    //管理员备注
			$return_list[$key]['content_user'] = $return['content_user'];										//用户备注
			$return_list[$key]['date_add'] = $return['date_add'];												//添加时间			
			
			/* 得到订单货品信息*/
			$return_list[$key]['return_data'] = array();
			$return_data = unserialize($return['return_data']);
			$order_products = Myorder_product::instance()->get_order_products_by_order_id($return['order_id']);
			foreach($order_products as $val)
			{
				foreach($return_data as $v)
				{
					if($v['id'] == $val['id'])
					{
						$return_list[$key]['return_data']['SKU'] = $val['SKU'];
						$return_list[$key]['return_data']['name'] = $val['name'];
						$return_list[$key]['return_data']['attribute_style'] = (empty($val['attribute_style']))?'默认':$val['attribute_style'];
						$return_list[$key]['return_data']['quantity'] = $val['quantity'];
						$return_list[$key]['return_data']['sendnum'] = (isset($v['send_num']))?$v['send_num']:'未知';
						$return_list[$key]['return_data']['returnnum'] = $v['return_num'];
					}
				}
			}
			if(empty($return_list[$key]['return_data']))
			{
				remind::set(Kohana::lang('o_order.product_return_load_error'),url::base(),'error');
			}

		}
		/* 调用列表 */
		$this->template->content->where				= $where_view;
		$this->template->content->order_status_list	= $order_status;
		
		$this->template->content->order_status	    = $order_status;
		$this->template->content->pay_status	    = $pay_status;
		$this->template->content->return_status		= $return_status;
		
		$this->template->content->return_list  	    = $return_list;		
	}	
    
	/**
	 *  退货单导出
	 */
	public function return_export()
	{
		// 初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try {
	        //权限验证
	        role::check('order_doc_return');

	        /* 验证是否选择了退货单 */
			if(!isset($_POST['return_ids']))
			{
				throw new MyRuntimeException(Kohana::lang('o_order.select_return_export'),403);
			}
			
			if($_POST)
			{
				$return_ids = $this->input->post('return_ids');//array(1,2);
				
				/* 得到当前的导出配置 */
				$output_field_ids = array(26,2,22,4,5,7,23,27,13,14,15);
				
				/* 导出格式错误 */
				if(!is_array($output_field_ids) || count($output_field_ids) < 1)
				{
					throw new MyRuntimeException(Kohana::lang('o_order.export_config_error'),403);
				}
	
				$xls = doc_export::instance();
				
				//$xls->debug(true);//开测试模式
				$xls->set_output_field_ids($output_field_ids);
				
				$ship_status = Kohana::config('order.ship_status');
				$result = array();
				
				foreach($return_ids as $return_id){
					$return = array();
					$return_info = Myorder_return_log::instance($return_id)->get();
					$return['id'] = $return_info['id'];                                                           	//退货单id
					$return['return_num'] = $return_info['return_num'];                                           	//退货单号
					$return['order_num'] = Myorder::instance($return_info['order_id'])->get('order_num');         	//订单号
					$return['site_domain'] = Mysite::instance()->get('domain');       		//站点名称
					$return['carrier'] = DeliveryService::get_instance()->get_delivery_name($return_info['carrier_id']); 
					$return['email'] = $return_info['email'];              											//用户邮箱
					$return['manager'] = Mymanager::instance($return_info['manager_id'])->get('username');   		//操作员
					$return['currency'] = $return_info['currency'];													//币种
					$return['total_shipping'] = $return_info['total_shipping'];										//运费金额
					$return['return_status'] = $ship_status[$return_info['return_status_id']]['name'];				//退货状态
					$return['content_admin'] = $return_info['content_admin'];									    //管理员备注
					$return['content_user'] = $return_info['content_user'];											//用户备注
					$return['date_add'] = $return_info['date_add'];													//添加时间	
					
					$xls->set_order_line($return);
				}
				$xls->output();
				exit;
			}
		} catch (MyRuntimeException $ex) {
			$return_struct['status'] = 0;
			$return_struct['code']   = $ex->getCode();
			$return_struct['msg']    = $ex->getMessage();
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
	/**
	 *  退货单批量删除
	 */
    public function return_delete()
    {
        role::check('order_doc_return');
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => Kohana::lang('o_global.not_implemented'),
            'content'       => array(),
        );
       try {
            /* 初始化返回数据 */
            $return_data = array();
            
            /* 收集请求数据*/
            $request_data = $this->input->post();

            /* 数据验证*/
            if(!isset($request_data['return_ids']) OR empty($request_data['return_ids'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'),400);
            }
            
            $return_log = Myorder_return_log::instance();
            /* 根据ID列表获取退货单*/
            $query_struct = array('where' => array(
            	'id' => $request_data['return_ids'],
            ));
            $returns = $return_log->query_assoc($query_struct);
                
            /* 删除单据*/
            foreach ($returns as $return) {
                $return_log->delete($return['id']);
            }

            /* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = Kohana::lang('o_global.delete_success');
            $return_struct['content']= $return_data;
            $return_struct['action'] = array(
                'type'=>'location',
                'url'=>url::base().'order/order_doc/return_product/'
            );
            
            /* 请求类型 */
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                /* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                /* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                /* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                /* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                
            }

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new  View('info');
                $this->template->content = $content;
                /* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                /* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
    
	/**
	 *  显示退货单细节
	 */    
	public function return_detail($id)
	{
		/* 权限检查*/
		role::check('order_doc_return');
		
		$return = Myorder_return_log::instance($id)->get();
		$ship_status = Kohana::config('order.ship_status');
		
		$return_list = array();
		$return_list['id'] = $return['id'];                                                           	//退货单id
		$return_list['return_num'] = $return['return_num'];                                           	//退货单号
		$return_list['order_num'] = Myorder::instance($return['order_id'])->get('order_num');         	//订单号
		$return_list['carrier'] = DeliveryService::get_instance()->get_delivery_name($return['carrier_id']); 
		$return_list['email'] = $return['email'];              											//用户邮箱
		$return_list['manager'] = Mymanager::instance($return['manager_id'])->get('username');   		//操作员
		$return_list['currency'] = $return['currency'];													//币种
		$return_list['total_shipping'] = $return['total_shipping'];										//运费金额
		$return_list['return_status'] = $ship_status[$return['return_status_id']]['name'];				//退货状态
		$return_list['content_admin'] = $return['content_admin'];									    //管理员备注
		$return_list['content_user'] = $return['content_user'];											//用户备注
		$return_list['date_add'] = $return['date_add'];													//添加时间			
		/* 得到订单货品信息 */
		$return_list['return_data'] = array();
		$return_data = unserialize($return['return_data']);
		$order_products = Myorder_product::instance()->get_order_products_by_order_id($return['order_id']);
		foreach($order_products as $val)
		{
			foreach($return_data as $k=>$v)
			{
				if($v['id'] == $val['id'])
				{
					$return_list['return_data'][$k]['SKU'] = $val['SKU'];
					$return_list['return_data'][$k]['name'] = $val['name'];
					$return_list['return_data'][$k]['attribute_style'] = (empty($val['attribute_style']))?'默认':$val['attribute_style'];
					$return_list['return_data'][$k]['quantity'] = $val['quantity'];
					$return_list['return_data'][$k]['sendnum'] = (isset($v['send_num']))?$v['send_num']:'未知';
					$return_list['return_data'][$k]['returnnum'] = $v['return_num'];
				}
			}
		}
		
		$this->template = new View('layout/commonfix_html');
		$this->template->content = new view("order/order_doc/doc_detail/return_detail");
		$this->template->content->return_list = $return_list;
	}	
}
