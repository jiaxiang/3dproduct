<?php defined('SYSPATH') OR die('No direct access allowed.');

class Gift_card_Controller extends Template_Controller {

    public function __construct(){
        parent::__construct();
        if($this->is_ajax_request()==TRUE){
            $this->template = new View('layout/default_json');
        }
    }

    /**
     * 显示商品列表
     */
    public function index()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       
       try {
            //* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            $query_struct_current = array(
                            'where' => array(
                                    'status' => 0,
                                    'type' => ProductService::PRODUCT_TYPE_GIFT_CARD
                                ),
                            'orderby' => array
                                (
                                    'id' => 'ASC'
                                )

                        );  
            $return_data = BLL_Product::index($query_struct_current);
            
            //* 如果是ajax请求缩减返回的字段 ==根据业务逻辑定制== */
            if($this->is_ajax_request()){
                $requestkeys = array('id', 'title', 'on_sale', 'price', 'sku');
                array_walk($return_data['assoc'], 'util::simplify_return_array', $requestkeys);
            }
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $return_data;
			
            //* 请求类型 */
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                $this->template->return_struct = $return_struct;
                $this->template->content = new View('product/gift_card/index',
                                                    array(
                                                        'request_data'=>$request_data,
                                                        'return_struct' => $return_struct
                                                    )
                                                );
            }
        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    /**
     * 显示编辑商品表单
     */
    public function edit()
    {
    	// 初始化返回结构体
    	$return_struct = array(
    		'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
    	);
    	try {
    		// 初始化返回数据
    		$return_data = array();
    		
    		// 收集请求数据
    		$request_data = $this->input->get();
    		
    		if (empty($request_data['id']))
    		{
    			throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
    		}
    		
    		$product = BLL_Product::get($request_data['id']);
    		if(empty($product['id'])){
    			throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
    		}
            
            $return_struct['status']  = 1;
            $return_struct['code']    = 200;
    		$return_struct['content'] = $product;
            
    		//* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            } else {
                
            }// end of request type determine
    	} catch (MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
    	}
    }
    
    /**
     * 提交商品修改
     */
    public function post()
    {
    	// 初始化返回结构体
    	$return_struct = array(
    		'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
    	);
    	try {
    		// 初始化返回数据
    		$return_data = array();
    		
    		// 收集请求数据
    		$request_data = $this->input->post();
    		$request_data = trims::run($request_data);
            $url_redirect = url::base().'product/gift_card';   
            $id = isset($request_data['id'])?(int)$request_data['id']:0;
            $on_sale = isset($request_data['on_sale'])?(int)$request_data['on_sale']:0;
            $price = (int)$request_data['price'];
            if($price<=0){
                throw new MyRuntimeException("价格必须为整数", 500);
            }
            
            $data = array(
                'front_visible' => 0, //礼品卡在前台商品列表中不可见
    			'type'  => ProductService::PRODUCT_TYPE_GIFT_CARD,
    			'price' => $price,
    			'title' => $request_data['title'],
    			'sku'   => $request_data['sku'],
    			'on_sale' => $on_sale
    		);
            if($id>0){         
                $data['id'] = $id;
                $data['update_time'] = time();
                ProductService::get_instance()->update($data);
            }else{
                $data['create_time'] = time();        
		        $id = ProductService::get_instance()->add($data);        
            }
        
            if($id<=0){
                throw new MyRuntimeException(Kohana::lang('o_product.no_save'), 500);
            }            
    		remind::set(Kohana::lang('o_product.edit_product_success'), $url_redirect, 'success');
            
    	} catch (MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
    	}
    }
    
    /**
     * 单体逻辑删除商品
     */
    public function delete()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       try {
            //* 初始化返回数据 */
            $return_data = array();
            $request_data = $this->input->get();
            $status = isset($request_data['status'])?(int)$request_data['status']:0;
            if(!isset($request_data['id']) || empty($request_data['id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
            $this->delete_product($request_data['id'], $status);

            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = 'Sucess';
            $return_struct['content']= $return_data;

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
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
                
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    /**
     * 批量删除商品
     *
     */
    public function delete_all()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       try {
            //* 初始化返回数据 */
            $return_data = array();
            
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
            
            //* 数据验证 ==根据业务逻辑定制== */
            if(!isset($request_data['ids']) OR empty($request_data['ids']) OR !is_array($request_data['ids']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
            if(count($request_data['ids']) > 100)
            {
            	throw new MyRuntimeException(Kohana::lang('o_product.over_max_delete_range'), 400);
            }
            
            // 删除商品
            set_time_limit(0);
            foreach ($request_data['ids'] as $product_id)
            {
            	$this->delete_product($product_id);
            }

            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '删除成功';
            $return_struct['content']= $return_data;
            $return_struct['action'] = array(
                'type'=>'location',
                'url'=>!empty($request_data['listurl']) ? url::base().$request_data['listurl'] : url::base().'product/'.$this->class_name.'/'.'index'
            );
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
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
                
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
 
    /**
     * 通过AJAX验证商品SKU是否已存在
     *
     */
    public function sku_exists()
    {
    	try
    	{
    		$request_data = $this->input->get();
    		$request_data = trims::run($request_data);
    		
    		empty($request_data['sku']) && exit('false');
    		$id = isset($request_data['id'])?$request_data['id']:0;
    		if (BLL_Product::sku_exists($request_data['sku'], $request_data['id']))
    			exit('false');
    		else
    			exit('true');
    	} catch (MyRuntimeException $ex) {
    		exit('false');
    	}
    }
      
    /**
     * 逻辑删除商品
     *
     * @param int $product_id
     * @param int $status
     * @return bool
     */
    protected function delete_product($product_id, $status=0)
    {
        /* 调用业务逻辑 */
        switch($status)
        {
            case 2:
                return BLL_Product::delete($product_id);
                break;
            default:
                return BLL_Product::modify_status($product_id, ProductService::PRODUCT_STATUS_DELETE);
                break;
        }        
    }
     
    public function set_on_sale()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       try {
            //* 初始化返回数据 */
            $return_data = array(

            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            if (empty($request_data['product_id']))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            if (!isset($request_data['status']) OR !in_array($request_data['status'], array('0', '1')))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            //* 逻辑验证 ==根据业务逻辑定制== */
            BLL_Product::set_on_sale($request_data['product_id'], $request_data['status']);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $request_data['status'];

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
            	// 仅允许AJAX调用
            	//throw new MyRuntimeException('Not Implemented',501);
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
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
}