<?php defined('SYSPATH') or die('No direct access allowed.');

class Productinquiry_Controller extends Template_Controller {    
    private $package_name = '';
    private $class_name = '';

    public $template_ = 'layout/common_html';
    
	public function __construct()
    {
        role::check('product_inquiry');
    	$this->package_name = 'product';
        $this->class_name = strtolower(substr(__CLASS__, 0, strpos(__CLASS__, '_')));
        parent::__construct();
        if($this->is_ajax_request())
        {
            $this->template = new View('layout/default_json');
        }
    }
    
    /**
     * 咨询列表
     */
    public function index()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
            //* 初始化返回数据 */
            $return_data = array (
                'assoc' => NULL, 
                'count' => 0 
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //* 初始化默认查询结构体 ==根据业务逻辑定制== */
            $request_struct_default = array (
                'type' => NULL, 
                'keyword' => NULL 
            );
            //* 初始化请求结构体 */
            $request_struct_current = array ();
            $product_all_ids = array();
            $product_get_ids = array();
            $product_id = 0;
            //* 设置合并默认请求结构数据到当前请求结构体 */
            $request_struct_current = array_merge($request_struct_current, $request_struct_default);
            //* 初始化默认查询结构体 ==根据业务逻辑定制== */
            $query_struct_default = array (
                'where' => array (), 
                'like' => array (), 
                'orderby' => array (
                    'id' => 'DESC' 
                ), 
                'limit' => array (
                    'per_page' => Kohana::config('my.items_per_page'),
                    'page' => 1 
                ) 
            );
            //* 初始化当前查询结构体 */
            $query_struct_current = array ();
            //* 设置合并默认查询条件到当前查询结构体 */
            $query_struct_current = array_merge($query_struct_current, $query_struct_default);
            
            $data = ProductinquiryService::get_instance()->index($query_struct_current);
            foreach($data as $key=>$val)
            {
            	if(isset($product_all_ids[$val['product_id']]))
            	{
            		unset($data[$key]);
            	}
            	else
            	{
            		$product_all_ids[$val['product_id']] = $val['product_id'];
            	}           	
            } 
            
            if(isset($request_data['is_show']) AND in_array(trim($request_data['is_show']), array (
                ProductinquiryService::SHOW_NOTIN_FRONT, 
                ProductinquiryService::SHOW_IN_FRONT
            )))
            {
                $query_struct_current['where']['is_show'] = trim($request_data['is_show']);
            }
            //* 当前支持的查询业务逻辑 ==根据业务逻辑定制== */
            if(isset($request_data['type']) && isset($request_data['keyword']) && !empty($request_data['keyword']))
            {
                switch($request_data['type'])
                {
                    case 'sku':
                        $products = ProductService::get_instance()->query_assoc(array (
                            'like' => array (
                                'sku' => trim($request_data['keyword']) 
                            ) 
                            /*
                            'where' => array (
                                'sku' => trim($request_data['keyword']) 
                            )
                            */
                        ));
                		if(empty($products))
                        {
                           $product_id = 0;
                        }
                        else
                        {
                            foreach($products as $val)
                            {
                            	if(in_array($val['id'], $product_all_ids))
                            	{
                            		$product_get_ids[$val['id']] = $val['id'];
                            	}
                            }
                        }
                        if(is_array($product_get_ids) && count($product_get_ids) > 0)
                        {
                        	$query_struct_current['where']['product_id'] = $product_get_ids;
                        }
                        else
                        {
                        	$query_struct_current['where']['product_id'] = $product_id;
                        }
                        $request_struct_current['keyword'] = trim($request_data['keyword']);
                        break;
                    case 'email':
                        $query_struct_current['like'][$request_data['type']] = trim($request_data['keyword']);
                        $request_struct_current['keyword'] = $query_struct_current['like'][$request_data['type']];
                        /*
                        $query_struct_current['where'][$request_data['type']] = trim($request_data['keyword']);
                        $request_struct_current['keyword'] = $query_struct_current['where'][$request_data['type']];
                        */
                        break;
                    case 'subject':
                    	$query_struct_current['like'][$request_data['type']] = trim($request_data['keyword']);
                        $request_struct_current['keyword'] = $query_struct_current['like'][$request_data['type']];
                        /*
                        $query_struct_current['where'][$request_data['type']] = trim($request_data['keyword']);
                        $request_struct_current['keyword'] = $query_struct_current['where'][$request_data['type']];
                        */
                        break;
                }
                $request_struct_current['type'] = $request_data['type'];
            }
            
            //列表排序
            $orderby_arr = array (
                0 => array (
                    'id' => 'DESC' 
                ), 
                1 => array (
                    'id' => 'ASC' 
                ),
                2 => array (
                    'site_id' => 'ASC' 
                ), 
                3 => array (
                    'site_id' => 'DESC' 
                ), 
                4 => array (
                    'is_show' => 'ASC' 
                ), 
                5 => array (
                    'is_show' => 'DESC' 
                ), 
                6 => array (
                    'product_id' => 'ASC' 
                ), 
                7 => array (
                    'product_id' => 'DESC' 
                ), 
                8 => array (
                    'user_id' => 'ASC' 
                ), 
                9 => array (
                    'user_id' => 'DESC' 
                ), 
                10 => array (
                    'email' => 'ASC' 
                ), 
                11 => array (
                    'email' => 'DESC' 
                ), 
                12 => array (
                    'create_timestamp' => 'ASC' 
                ), 
                13 => array (
                    'create_timestamp' => 'DESC' 
                ), 
                14 => array (
                    'ip' => 'ASC'
                ), 
                15 => array (
                    'ip' => 'DESC'
                ),
                16 => array (
                    'status' => 'ASC'
                ), 
                17 => array (
                    'status' => 'DESC'
                ),
                18 => array (
                    'subject' => 'ASC'
                ), 
                19 => array (
                    'subject' => 'DESC'
                ),
                20 => array (
                    'reply_content' => 'ASC'
                ), 
                21 => array (
                    'reply_content' => 'DESC'
                )   
            );
            $orderby = controller_tool::orderby($orderby_arr);
            // 排序处理 
            if(isset($request_data['orderby']) && is_numeric($request_data['orderby']))
            {
                $query_struct_current['orderby'] = $orderby;
            }
            // 每页条目数
            controller_tool::request_per_page($query_struct_current, $request_data);
            //print_r($query_struct_current);
            try
            {
                //* 调用后端服务获取数据 */
                $productinquiry_service = ProductinquiryService::get_instance();
                $return_data['count'] = $productinquiry_service->count($query_struct_current);
                $this->pagination = new Pagination(array (
	                'total_items' => $return_data['count'], 
	                'items_per_page' => $query_struct_current['limit']['per_page'] 
            	));
            	
            	$query_struct_current['limit']['offset']      = $this->pagination->sql_offset;
				$query_struct_current['limit']['page'] = $this->pagination->current_page;
                //查询
                $return_data['assoc'] = $productinquiry_service->index($query_struct_current);               
                
                $product_ids = array ();
                $user_ids = array ();
                foreach($return_data['assoc'] as $inquiries)
                {
                    if(!empty($inquiries['user_id']))
                    {
                        $user_ids[$inquiries['user_id']] = TRUE;
                    }
                    if(!empty($inquiries['product_id']))
                    {
                        $product_ids[$inquiries['product_id']] = TRUE;
                    }
                }
                
                $products = array ();
                $users = array ();
                if(!empty($product_ids))
                {
                    foreach(ProductService::get_instance()->query_assoc(array (
                        'where' => array (
                            'id' => array_keys($product_ids) 
                        ) 
                    )) as $product)
                    {
                        $products[$product['id']] = $product;
                    }
                }
                
                if(!empty($user_ids))
                {
                    foreach(ORM::factory('user')->in('id', array_keys($user_ids))->find_all() as $user)
                    {
                        $user = $user->as_array();
                        $users[$user['id']] = $user;
                    }
                }
            }
            catch(MyRuntimeException $ex){
                //* ==根据业务逻辑定制== */
                //FIXME 根据service层的异常做一些对应处理并抛出用户友好的异常Message
                throw $ex;
            }
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
                //* html 输出 ==根据业务逻辑定制== */
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                
                $content = new View($this->package_name . '/' . $this->class_name . '/' . __FUNCTION__);
                
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                $this->template->content->request_struct = $request_struct_current;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
                $this->template->content->products = $products;
                $this->template->content->users = $users;
            } // end of request type determine
        }
        catch(MyRuntimeException $ex)
        {
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
    
    /*
     * 查看咨询信息
     */
    public function get()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
            //* 初始化返回数据 */
            $return_data = array ()

            ;
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //* 数据验证 ==根据业务逻辑定制== */
            if(!isset($request_data['id']) || empty($request_data['id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            // 调用底层服务
            $productinquiry_service = ProductinquiryService::get_instance();
            //* 调用后端服务获取数据  */
            try
            {
                $productinquiry = $productinquiry_service->get($request_data['id']);
            }
            catch(MyRuntimeException $ex)
            {
                throw $ex;
            }
            if(empty($productinquiry))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            $product = array ();
            $user = array ();
            
            $product = ProductService::get_instance()->get($productinquiry['product_id']);
            if(!empty($productinquiry['user_id']))
            {
                $user = Myuser::instance($productinquiry['user_id'])->get();
            }
            
            //* 根据请求数据和业务逻辑补充修订输入设置数据 ==根据业务逻辑定制== */
            $return_data = $productinquiry;
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request())
            {
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }
            else
            {
                // html 输出
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
                $this->template->content->user = $user;
                $this->template->content->product = $product;
            } // end of request type determine
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
    
    /*
     * 处理咨询信息
     */
    public function do_edit(){
    	$return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //* 初始化返回数据 */
            $return_data = array();            
            $request_data = $this->input->post();
            
            /* 数据验证 ==根据业务逻辑定制== */
            if(empty($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            $validResult = Validation::factory($request_data)->pre_filter('trim')
            						    ->add_rules('id',   'required',  'digit')
            						    ->add_rules('is_receive',   'digit')
            						    ->add_rules('is_show',   'digit')
            						    ->add_rules('reply_content', 'length[0,1024]');
            
        	if (!$validResult->validate()){
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            //* 逻辑验证 ==根据业务逻辑定制== */
            // 调用底层服务
            $productinquiry_service = ProductinquiryService::get_instance();
            //* 调用后端服务获取数据  */
        	try{
            	$productinquiry = $productinquiry_service->get($request_data['id']);
            	if(!isset($productinquiry) || empty($productinquiry))
            	{
            		throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);	
            	}
                
            	$set_data = array(
            		'reply_content'    => $request_data['reply_content'],
            		'is_show'          => $request_data['is_show'],
            		'update_timestamp' => date('Y-m-d H:i:s'),
            		'status'           => 1
            	);
            	
            	if(!empty($request_data['is_receive']) && $productinquiry['is_receive'] != 1){
                    
            		$product = ProductService::get_instance()->get($productinquiry['product_id']);

					if(!empty($productinquiry['user_id']))
					{
						$email = Myuser::instance($productinquiry['user_id'])->get('email');
					}
					else
					{
						$email = $productinquiry['email'];
					}
                                        
	            	$email_flag		= 'reply_inquiry';
					$title_param	= array();	
                    $title_param['{title}'] = strip_tags($product['title']);	
                    
					$content_param	= array();
					$content_param['{user_name}'] = strip_tags($productinquiry['user_name']);
					$content_param['{reply_content}'] = strip_tags($request_data['reply_content']);
                    $content_param['{product_title}'] = strip_tags($product['title']);

					if(!mail::send_mail($email_flag, $email, '', $title_param, $content_param))
					{
						throw new MyRuntimeException(Kohana::lang('o_global.mail_send_error'), 500);
					}
					else 
					{
						$set_data['is_receive'] = 1;
					}
                    
            		//不套用邮件模板的方式
            		/*
	            	$subject = 'Reply to Inquiry About '.$product['title'];
					$content = '';
					$content .= 'Dear '.$productinquiry['user_name'].' :<br>';
					$content .= 'Having received your letter regarding the inquiry about '.$product['title'].'.<br>';
					$content .= $productinquiry['reply_content'].'<br>';
					$content .= 'If we may be of further service, please feel free to contact us.<br>';
					$content .= 'Sincerely yours,<br>';
					$content .= $site['name'];

            		if(!mail::send($email,$subject,$content))
            		{
						throw new MyRuntimeException(Kohana::lang('o_global.mail_send_error'), 500);
					}
					else
					{
						$set_data['is_receive'] = 1;
					}
					*/
            	}
            	$productinquiry_service->set($productinquiry['id'], $set_data);            
            } 
            catch (MyRuntimeException $ex) 
            {
            	throw $ex;
            }
            
       		 //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '操作成功';
            $return_struct['content'] = $return_data;
            $return_struct['action'] = array (
                'type' => 'location', 
                'url' => url::base() . 'product/' . $this->class_name . '/' . 'index' 
            );
            
            //* 请求类型 */
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
                // html 输出
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            
            } // end of request type determine
        }
        catch(MyRuntimeException $ex)
        {
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }

    /*
     * 批量处理
     */
    public function examine_all()
    {
    	$return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
            //* 初始化返回数据 */
            $return_data = array ();
            $set_data = array();
            
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
            
            //* 数据验证 ==根据业务逻辑定制== */
            if(empty($request_data['inquiry_id']) OR !is_array($request_data['inquiry_id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            if(isset($request_data['status']) && $request_data['status'] != 1 ){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
        	if(isset($request_data['is_show']) && !in_array($request_data['is_show'], array (
            	ProductinquiryService::SHOW_NOTIN_FRONT, 
                ProductinquiryService::SHOW_IN_FRONT
            ))){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
            //* 逻辑验证 ==根据业务逻辑定制== */
            // 调用底层服务
            $productinquiry_service = ProductinquiryService::get_instance();
            //* 调用后端服务获取数据  */
            foreach ($request_data['inquiry_id'] as $inquiry_id)
            {
            	try
            	{
            		$productinquiry = $productinquiry_service->get($inquiry_id);
	            	if(isset($request_data['is_show']))
	            	{
	            		$set_data['is_show'] = $request_data['is_show'];
	            		
	            	}
	            	if(isset($request_data['status']))
	            	{
	            		$set_data['status'] = $request_data['status'];
	            	}
	            	$productinquiry_service->set($productinquiry['id'], $set_data);	            	
            	} catch (MyRuntimeException $ex) {
            		
            	}
            }
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '操作成功！';
            $return_struct['content'] = $return_data;
            
            if (!empty($request_data['listurl']))
            {
	            $return_struct['action'] = array(
	            	'type' => 'location',
	            	'url' => url::base().$request_data['listurl'],
	            );
            }
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
            } // end of request type determine
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
    
    public function delete()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();

            //* 数据验证 ==根据业务逻辑定制== */
            if(empty($request_data['id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            //* 逻辑验证 ==根据业务逻辑定制== */
            // 调用底层服务
            $productinquiry_service = ProductinquiryService::get_instance();
            //* 调用后端服务获取数据  */
            try
            {
                $productinquiry = $productinquiry_service->get($request_data['id']);
            }
            catch(MyRuntimeException $ex)
            {
                //* ==根据业务逻辑定制== */
                //FIXME 根据service层的异常做一些对应处理并抛出用户友好的异常Message
                throw $ex;
            }
            if(empty($productinquiry))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            $productinquiry_service->delete_by_id($productinquiry['id']);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request())
            {
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }
            else
            {
                // html 输出
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
            } // end of request type determine
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
    
    public function delete_all()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
            //* 初始化返回数据 */
            $return_data = array ()

            ;
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();

            //* 数据验证 ==根据业务逻辑定制== */
            if(empty($request_data['inquiry_id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            //* 逻辑验证 ==根据业务逻辑定制== */
            // 调用底层服务
            $productinquiry_service = ProductinquiryService::get_instance();
            //* 调用后端服务获取数据  */
            try{
                $query_struct = array (
                    'where' => array (
                        'id' => $request_data['inquiry_id'] 
                    ) 
                );
                $productinquiries = $productinquiry_service->query_assoc($query_struct);
                if(count($productinquiries) != count($request_data['inquiry_id']))
                {
                    throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
                }
                
                foreach($productinquiries as $productinquiry)
                {
                    $productinquiry_service->delete_by_id($productinquiry['id']);
                }
            }catch(MyRuntimeException $ex){
                //* ==根据业务逻辑定制== */
                //FIXME 根据service层的异常做一些对应处理并抛出用户友好的异常Message
                throw $ex;
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = Kohana::lang('o_product.delete_success');
            $return_struct['content'] = $return_data;
            
            if (!empty($request_data['listurl']))
            {
            	$return_struct['action'] = array(
            		'type' => 'location',
            		'url'  => url::base().$request_data['listurl'],
            	);
            }
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                //throw new MyRuntimeException('Not Found', 404);
                // html 输出
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
            } // end of request type determine
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
}