<?php
defined('SYSPATH') or die('No direct access allowed.');
/**
 * $Id: comment.php 225 2010-02-27
 * $Author: huanxiangwu
 * $Revision: 1 
 */
class Comment_Controller extends Template_Controller {
    
    private $package_name = '';
    private $class_name = '';
    
    // Set the name of the template to use
    public $template_ = 'layout/common_html';
    
    public function __construct()
    {
        $package_name = substr(dirname(__FILE__), strlen(APPPATH . 'controllers/'));
        empty($package_name) && $package_name = 'default';
        $this->package_name = $package_name;
        $this->class_name = strtolower(substr(__CLASS__, 0, strpos(__CLASS__, '_')));
        parent::__construct();
        if($this->is_ajax_request() == TRUE){
            $this->template = new View('layout/default_json');
        }
    }
    
    /**
     * 评论列表
     */
    public function index()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{            
            //$profiler = new Profiler;
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
                    'per_page' => Kohana::config('my.items_per_page'),  //TODO 测试期参数，正式发布建议使用10
                    'page' => 1 
                ) 
            );
            
            //* 初始化当前查询结构体 */
            $query_struct_current = array ();
            $product_all_ids = array();
            $product_get_ids = array();
            $product_id = 0;
            
            //* 设置合并默认查询条件到当前查询结构体 */
            $query_struct_current = array_merge($query_struct_current, $query_struct_default);
            $data = ProductcommentService::get_instance()->index($query_struct_current);
            //d($query_struct_current,1);d($data);
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
            
            if(isset($request_data['status']) AND in_array(trim($request_data['status']), array (
                ProductcommentService::COMMENT_EXAMINE_FALSE, 
                ProductcommentService::COMMENT_EXAMINE_TRUE,
                ProductcommentService::COMMENT_NOT_EXAMINE
            ))){
                $query_struct_current['where']['status'] = trim($request_data['status']);
            }
            //* 当前支持的查询业务逻辑 ==根据业务逻辑定制== */
            if(isset($request_data['type']) && isset($request_data['keyword']) && !empty($request_data['keyword'])){
                switch($request_data['type']){
                    case 'sku':
                        $products = ProductService::get_instance()->query_assoc(array (
                            'like' => array (
                                'sku' => trim($request_data['keyword']) 
                            )
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
                    case 'mail':
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
                4 => array (
                    'status' => 'ASC' 
                ), 
                5 => array (
                    'status' => 'DESC' 
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
                    'mail' => 'ASC' 
                ), 
                11 => array (
                    'mail' => 'DESC' 
                ), 
                12 => array (
                    'grade' => 'ASC' 
                ), 
                13 => array (
                    'grade' => 'DESC' 
                ), 
                14 => array (
                    'create_timestamp' => 'ASC' 
                ), 
                15 => array (
                    'create_timestamp' => 'DESC' 
                ), 
                16 => array (
                    'ip' => 'ASC'
                ), 
                17 => array (
                    'ip' => 'DESC'
                ) 
            );
            $orderby = controller_tool::orderby($orderby_arr);

            // 排序处理 
            if(isset($request_data['orderby']) && is_numeric($request_data['orderby'])){
                $query_struct_current['orderby'] = $orderby;
            }
            
            //设定默认分页
            $preset_perpages = Kohana::config('my.items_per_pages');
            if(isset($request_data['per_page']) && !empty($request_data['per_page']))//&& in_array($request_data['per_page'],$preset_perpages) // 部分业务逻辑可以不限制每页数量跳格
            {
                $query_struct_current['limit']['per_page'] = $request_data['per_page'];
            }
            $request_struct_current['per_page'] = $query_struct_current['limit']['per_page'];
            if(isset($request_data['page']) && !empty($request_data['page']) && is_numeric($request_data['page']) && $request_data['page'] > 0){
                $query_struct_current['limit']['page'] = $request_data['page'];
            }
            $request_struct_current['page'] = $query_struct_current['limit']['page'];
            
            try{
                //* 调用后端服务获取数据 */
                $comment_service = ProductcommentService::get_instance();
                
                //查询
                $return_data['assoc'] = $comment_service->index($query_struct_current);
                $return_data['count'] = $comment_service->count($query_struct_current);
                
                $product_ids = array ();
                $user_ids = array ();
                foreach($return_data['assoc'] as $comment){
                    if(!empty($comment['user_id'])){
                        $user_ids[$comment['user_id']] = TRUE;
                    }
                    if(!empty($comment['product_id'])){
                        $product_ids[$comment['product_id']] = TRUE;
                    }
                }
                
                $products = array ();
                $users = array ();
                if(!empty($product_ids)){
                    foreach(ProductService::get_instance()->query_assoc(array (
                        'where' => array (
                            'id' => array_keys($product_ids) 
                        ) 
                    )) as $product){
                        $products[$product['id']] = $product;
                    }
                }
                if(!empty($user_ids)){
                    foreach(ORM::factory('user')->in('id', array_keys($user_ids))->find_all() as $user){
                        $user = $user->as_array();
                        $users[$user['id']] = $user;
                    }
                }
                
                // 模板输出 分页
                $this->pagination = new Pagination(array (
                    'total_items' => $return_data['count'], 
                    'items_per_page' => $query_struct_current['limit']['per_page'] 
                ));
            }catch(MyRuntimeException $ex){
                //* ==根据业务逻辑定制== */
                //FIXME 根据service层的异常做一些对应处理并抛出用户友好的异常Message
                throw $ex;
            }
            //* 如果是ajax请求缩减返回的字段 ==根据业务逻辑定制== */
            if($this->is_ajax_request()){
                $requestkeys = array (
                    'id', 
                    'category_id', 
                    'title', 
                    'uri_name', 
                    'store', 
                    'on_sale', 
                    'goods_price', 
                    'sku' 
                );
                array_walk($return_data['assoc'], 'util::simplify_return_array', $requestkeys);
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $return_data;
            
            //exit("<div id=\"do_debug\" style=\"clear:both;display:;\"><pre>\n".var_export($return_struct,TRUE)."\n</pre></div>");
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
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
            }
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
    
    public function get()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //* 数据验证 ==根据业务逻辑定制== */
            if(!isset($request_data['id']) || empty($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            //* 逻辑验证 ==根据业务逻辑定制== */
            $comment_service = ProductcommentService::get_instance();
            $comment = $comment_service->get($request_data['id']);            
            if(empty($comment)){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            $product = array ();
            $user = array ();            
            $product = ProductService::get_instance()->get($comment['product_id']);
            if(!empty($comment['user_id'])){
                $user = Myuser::instance($comment['user_id'])->get();
            }
            
            //* 根据请求数据和业务逻辑补充修订输入设置数据 ==根据业务逻辑定制== */
            $return_data = $comment;
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
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
            }
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
    
    public function examine()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //* 数据验证 ==根据业务逻辑定制== */
            if(empty($request_data['comment_id']) || !isset($request_data['status']) || 
                !in_array($request_data['status'], array (
            	ProductcommentService::COMMENT_NOT_EXAMINE,
                ProductcommentService::COMMENT_EXAMINE_FALSE, 
                ProductcommentService::COMMENT_EXAMINE_TRUE 
             )) ){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            //* 逻辑验证 ==根据业务逻辑定制== */
            $comment_service = ProductcommentService::get_instance();
            //* 调用后端服务获取数据  */
        	try
            {
            	$comment = $comment_service->get($request_data['comment_id']);
            	$comment_service->set($comment['id'], array(
            		'status' => $request_data['status'],
            	));
            	$this->update_product($comment['product_id']);
            } catch (MyRuntimeException $ex) {
            	throw $ex;
            }
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
                // html 输出
                //* 模板输出 */
                //$this->template->return_struct = $return_struct;
                //$content = new View($this->package_name . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                //$this->template->title = Kohana::config('site.name');
                //$this->template->content = $content;
                //* 请求结构数据绑定 */
                //$this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                //$this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                //$this->template->content->title = Kohana::config('site.name');
                //$this->template->content->site_list = Mysite::instance()->select_list($site_ids);
                //$this->template->content->user = $user;
                //$this->template->content->product = $product;
            }
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
    
    public function examine_all()
    {
    	$return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            $return_data = array();
            
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
             
            //* 数据验证 ==根据业务逻辑定制== */
            if(empty($request_data['comment_id']) || !is_array($request_data['comment_id']) ||
                !isset($request_data['status']) || !in_array($request_data['status'], array (
                	ProductcommentService::COMMENT_NOT_EXAMINE,
                    ProductcommentService::COMMENT_EXAMINE_FALSE, 
                    ProductcommentService::COMMENT_EXAMINE_TRUE 
                ))
            ){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
            //* 逻辑验证 ==根据业务逻辑定制== */
            // 调用底层服务
            $comment_service = ProductcommentService::get_instance();
            foreach ($request_data['comment_id'] as $comment_id)
            {
                $comment = $comment_service->get($comment_id);
	            $comment_service->set($comment['id'], array(
	            	'status' => $request_data['status'],
	            ));
	            $this->update_product($comment['product_id']);
            }
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '更新评论审核状态成功！';
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
        try{
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //* 数据验证 ==根据业务逻辑定制== */
            if(empty($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }

            // 调用底层服务
            $comment_service = ProductcommentService::get_instance();
            $comment = $comment_service->get($request_data['id']);
            if(empty($comment)){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            $comment_service->remove($comment['id']);
            $comment['product_id']>0 && $this->update_product($comment['product_id']);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
                // html 输出
                //* 模板输出 */
                //$this->template->return_struct = $return_struct;
                //$content = new View($this->package_name . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                //$this->template->title = Kohana::config('site.name');
                //$this->template->content = $content;
                //* 请求结构数据绑定 */
                //$this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                //$this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                //$this->template->content->title = Kohana::config('site.name');
            }
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
        try{
            $return_data = array ();
            $product_id_arr = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
            
            //* 数据验证 ==根据业务逻辑定制== */
            if(empty($request_data['comment_id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            $comment_service = ProductcommentService::get_instance();
            try{
                $query_struct = array (
                    'where' => array (
                        'id' => $request_data['comment_id'] 
                    ) 
                );
                $comments = $comment_service->query_assoc($query_struct);
                if(!count($comments)){
                    throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
                }
                
                foreach($comments as $comment){
                    $product_id_arr[$comment['product_id']] = $comment['product_id'];
                    $comment_service->remove($comment['id']);
                }
                
                //更新产品评论统计
                if(count($product_id_arr)){
                    foreach($product_id_arr as $product_id){
                        $this->update_product($product_id);
                    }
                }
            }catch(MyRuntimeException $ex){
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
    
    protected function update_product($product_id)
    {
        try{
            $grade_count = 0;
            $grade_score = 0;
            $comments = ProductcommentService::get_instance()->query_assoc(array(
                'where' => array (
                    'product_id' => $product_id, 
                    'status'     => ProductcommentService::COMMENT_EXAMINE_TRUE,
                )
            ));
            foreach($comments as $comment){
                if($comment['grade'] > 0){
                    $grade_count++;
                    $grade_score += $comment['grade'];
                }
            }
            ProductService::get_instance()->set($product_id, array (
                'comments_count'   => count($comments),
                'graded_count'     => $grade_count,
                'star_average'     => $grade_count > 0 ? round($grade_score / $grade_count, 1) : 0, 
                'update_time' => time()
            ));
        }catch(MyRuntimeException $ex){
            throw $ex;
        }
    }
}
