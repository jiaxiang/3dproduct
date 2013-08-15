<?php defined('SYSPATH') OR die('No direct access allowed.');

class Product_Controller extends Template_Controller {
	private $package_name = '';
    private $class_name = '';
    private $img_dir_name = 'product';
    
    // Set the name of the template to use
    public $template_ = 'layout/common_html';

    public function __construct()
    {
        $package_name = substr(dirname(__FILE__),strlen(APPPATH.'controllers/'));
        empty($package_name) && $package_name = 'default';
        $this->package_name = $package_name;
        $this->class_name = strtolower(substr(__CLASS__,0,strpos(__CLASS__,'_')));
        parent::__construct();
        if($this->is_ajax_request()==TRUE)
        {
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
            $request_data['status'] = isset($request_data['status'])?(int)$request_data['status']:0;
            $struct = product::get_struct($request_data);
            $query_struct_current   = $struct['query'];
            $query_struct_current['where']['type'] = array(ProductService::PRODUCT_TYPE_GOODS, 
                                                    ProductService::PRODUCT_TYPE_CONFIGURABLE, 
                                                    ProductService::PRODUCT_TYPE_ASSEMBLY
                                                );
            $request_struct_current = $struct['request'];
            // 每页条目数
            controller_tool::request_per_page($query_struct_current, $request_data);
            $return_data = BLL_Product::index($query_struct_current);
            
            // 模板输出 分页            
		    $this->pagination = new Pagination(array(
		        'total_items'    => $return_data['count'],
		        'items_per_page' => $query_struct_current['limit']['per_page'],
		    ));
            $query_struct_current['limit']['page'] = $this->pagination->current_page;
                
                
            //* 如果是ajax请求缩减返回的字段 ==根据业务逻辑定制== */
            if($this->is_ajax_request()){
                $requestkeys = array('id', 'category_id', 'title', 'store', 'on_sale', 'price', 'sku');
                array_walk($return_data['assoc'], 'util::simplify_return_array', $requestkeys);
            }
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $return_data;
			
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                //* html 输出 ==根据业务逻辑定制== */
                //* 模板输出 */
                $this->template->return_struct = $return_struct;

                $content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data   = $request_data;
                $this->template->content->request_struct = $request_struct_current;
                $this->template->content->query_struct   = $query_struct_current;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                
            }// end of request type determine

        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    /**
     * 显示添加商品表单
     *
     */
    public function add( $type = 0 )
    {
    	// 初始化返回结构体
    	$return_struct = array(
    		'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
    	);
    	try {
    		// 收集请求数据
    		$request_data = $this->input->get(); 
    		// 初始化返回数据
    		$return_data = $product = array();
    		$product['type'] = $type;   		
    		
    		// 分类列表默认关联第一个
    		$categorys_tree = CategoryService::get_instance()->get_tree("<option value=\\\"\$id\\\" \$selected>\$spacer\$title</option>", 0);
    		$categories = CategoryService::get_instance()->query_assoc(array());
    		$categories = tree::get_tree_array($categories);
    		$classifies = ClassifyService::get_instance()->index(array(
	    		'orderby' => array(
	    			'id' => 'ASC'
	    		)
	    	));
	    	$classify_content['brands']    = $this->load_brands();

	    	// 处理商品类型特定的模板区块
            $ptype_layout = NULL;
	    	switch($product['type'])
	    	{
	    	    case ProductService::PRODUCT_TYPE_ASSEMBLY:
                    throw new MyRuntimeException('Coming soon ...', 400);//暂时不支持组合商品
    	    		$ptype_layout = new View($this->package_name.'/'.$this->class_name.'/assembly/layout');
                    break;
	    	    case ProductService::PRODUCT_TYPE_CONFIGURABLE:
    	    		$ptype_layout = new View($this->package_name.'/'.$this->class_name.'/configurable/layout');
                    break;
                case ProductService::PRODUCT_TYPE_GOODS:
                default:
    	    		$ptype_layout = new View($this->package_name.'/'.$this->class_name.'/simple/layout');
                    break;
            }
	    	$ptype_layout->product = $product;
	    	
    		$return_struct['content'] = array(
    			'product' => $product,
    		);
    		
    		//* 请求类型 */
            if($this->is_ajax_request()) {
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            } else {
                // html 输出
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/edit');
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title          = Kohana::config('site.name');
                //$this->template->content->site_info_arr  = $site_info_arr;
                $this->template->content->categorys_tree = $categorys_tree;
                $this->template->content->classify_content = $classify_content;
                $this->template->content->ptype_layout   = $ptype_layout;
                $this->template->content->categories     = $categories;
                $this->template->content->classifies     = $classifies;
                //$this->template->content->site_id        = $site_id;
            }// end of request type determine
    	} catch (MyRuntimeException $ex) {
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
    		if (empty($product['id']))
    		{
    			throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
    		}

    		// 分类列表默认关联第一个
    		$categorys_tree = CategoryService::get_instance()->get_tree("<option value=\\\"\$id\\\" \$selected>\$spacer\$title</option>", $product['category_id']);
    		$categories = CategoryService::get_instance()->query_assoc(array());
    		$categories = tree::get_tree_array($categories);    		
    		$classifies = ClassifyService::get_instance()->index(array(
	    		'orderby' => array(
	    			'id' => 'ASC',
	    		),
	    	));
            
	    	$classify_content['features']  = $this->load_features($product['classify_id'], $product['fetuoptrs']);
	    	$classify_content['brands']    = $this->load_brands($product['classify_id'], $product['brand_id']);

	    	// 处理商品类型特定的模板区块
	    	$ptype_layout = NULL;
	    	switch($product['type'])
	    	{
	    	    case ProductService::PRODUCT_TYPE_ASSEMBLY:
                    throw new MyRuntimeException('Coming soon ...', 400);//暂时不支持组合商品
    	    		$ptype_layout = new View($this->package_name.'/'.$this->class_name.'/assembly/layout');
                    break;
	    	    case ProductService::PRODUCT_TYPE_CONFIGURABLE:
    	    		$ptype_layout = new View($this->package_name.'/'.$this->class_name.'/configurable/layout');
                    break;
                case ProductService::PRODUCT_TYPE_GOODS:
                default:
    	    		$ptype_layout = new View($this->package_name.'/'.$this->class_name.'/simple/layout');
                    break;
            }
	    	$ptype_layout->product = $product;
            
    		$return_struct['content'] = array(
    			'product' => $product,
    		);
    		//* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            } else {
                // html 输出
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
                //* 变量绑定 */
                $this->template->title   = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                
                $this->template->content->categorys_tree = $categorys_tree;
                $this->template->content->categories     = $categories;
                $this->template->content->classifies     = $classifies;
                $this->template->content->classify_content = $classify_content;
                $this->template->content->ptype_layout     = $ptype_layout;
                $this->template->content->listurl          = isset($request_data['listurl']) ? $request_data['listurl'] : '';
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
            $url_redirect = url::base().$this->package_name.'/'.$this->class_name;

            //标签过滤 商品描述保留
            tool::filter_strip_tags($request_data, array('desc','pdtdes_content'));
           
    		$product = $request_data;
	    	
	    	//收集商品详细描述表单数据
	    	$product['descsections'] = array();
	    	if (!empty($request_data['pdtdes_title']) AND is_array($request_data['pdtdes_title']))
	    	{
	    		foreach ($request_data['pdtdes_title'] as $index => $pdtdes_title)
	    		{
	    			$descsection = array(
	    				'title'    => $pdtdes_title,
	    				'position' => isset($request_data['pdtdes_position'][$index]) ? $request_data['pdtdes_position'][$index] : '',
	    				'content'  => isset($request_data['pdtdes_content'][$index]) ? $request_data['pdtdes_content'][$index] : '',
	    			);
	    			
	    			if (isset($request_data['pdtdes_id'][$index]))
	    			{
	    				$descsection['id'] = $request_data['pdtdes_id'][$index];
	    			}
	    			
	    			$product['descsections'][] = $descsection;
	    		}
	    	}
	    		
	    	//收集商品批发数据
	    	$product['wholesales'] = array(
	    		'type'  => 0,
	    		'items' => array(),
	    	);
	    	isset($request_data['wholesale_type']) AND $product['wholesales']['type'] = $request_data['wholesale_type'];
	    	if (isset($product['is_wholesale']) && $product['is_wholesale'] > 0)
	    	{
	    		if (!empty($request_data['wholesale_indexs']) AND is_array($request_data['wholesale_indexs']))
	    		{
	    			foreach ($request_data['wholesale_indexs'] as $index)
	    			{
	    				$wholesale = array();
	    				isset($request_data['wholesale_num_begin_'.$index]) AND $wholesale['num_begin'] = $request_data['wholesale_num_begin_'.$index];
	    				isset($request_data['wholesale_value_'.$index])     AND $wholesale['value']     = $request_data['wholesale_value_'.$index];
	    				$product['wholesales']['items'][] = $wholesale;
	    			}
	    		}
	    	}
            
	    	BLL_Product::set(&$product);
            if($product['id']<=0)
            {
                throw new MyRuntimeException(Kohana::lang('o_product.no_save'), 500);
            }
            
    		if($request_data['save_redirect']==1)
            {
                $url_redirect .= '/edit?id='.$product['id'];
            }
            elseif($request_data['save_redirect']==2)
            {
                $url_redirect .= '/add';
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
     * 单体还原商品
     */
    public function recycle()
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
            
            if(!isset($request_data['id']) || empty($request_data['id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
            //还原商品
            BLL_Product::modify_status($request_data['id'], ProductService::PRODUCT_STATUS_PUBLISH);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '还原成功';
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
     * 批量还原商品
     */
    public function recycle_all()
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
            
            //还原商品
            set_time_limit(0);
            foreach ($request_data['ids'] as $product_id)
            {
                BLL_Product::modify_status($product_id, ProductService::PRODUCT_STATUS_PUBLISH);
            }

            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '还原成功';
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
     * 通过AJAX验证商品URI NAME是否已存在
     *
     */
    public function uri_name_exists()
    {
    	try {
    		$request_data = $this->input->get();
    		$request_data = trims::run($request_data);
    		empty($request_data['uri_name']) && exit('true');
    		$id = isset($request_data['id'])?$request_data['id']:0;
    		
    		if (BLL_Product::uri_name_exists($request_data['uri_name'], $id))
    			exit('false');
    		else
    			exit('true');
    	} catch (MyRuntimeException $ex) {
    		exit('false');
    	}
    }
    
    /**
     * 通过AJAX获取商品类型所关联的商品特性、商品参数、商品品牌
     */
    public function get_classify()
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
            $request_data = $this->input->get();
            
            if (!isset($request_data['classify_id']) OR empty($request_data['classify_id']))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }

            $content = array(
            	'features'   => $this->load_features($request_data['classify_id']),
            	'brands'     => $this->load_brands($request_data['classify_id']),
            );
            if(isset($request_data['type']) && $request_data['type'] == ProductService::PRODUCT_TYPE_GOODS)
            {
                $content['attributes'] = $this->load_attributes($request_data['classify_id']);
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $content;

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
            	// 仅允许AJAX调用
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 501);
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
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
    
    /**
     * 加载商品特性模板
     *
     * @param array $features
     * @param array $relation
     * @return string
     */
    protected function load_features($classify_id, $relation = array())
    {
    	$template = new View($this->package_name.'/'.$this->class_name.'/plugins/classify/feature');
    	$template->features = BLL_Product_Feature::get_clsfeturs($classify_id);
    	$template->relation = $relation;
    	return trim((string)$template);
    }
    
    /**
     * 加载商品属性模板
     *
     * @param array $features
     * @param array $relation
     * @return string
     */
    protected function load_attributes($classify_id, $relation = array())
    {
    	$template = new View($this->package_name.'/'.$this->class_name.'/plugins/classify/attribute');
    	$template->attributes = BLL_Product_Attribute::get_clsattrrs($classify_id);
    	$template->relation = $relation;
    	return trim((string)$template);
    }
    
    /**
     * 加载商品参数模板
     *
     * @param array $arguments
     * @param array $argumrs
     * @return string
     */
    protected function load_arguments($classify_id, $argumrs = array())
    {
		$template = new View($this->package_name.'/'.$this->class_name.'/plugins/classify/argument');
		$template->argument_relation = BLL_Product_Argument::get_clsargurs($classify_id);
		$template->arguments = $argumrs;
		return trim((string)$template);
    }
    
    /**
     * 加载商品品牌模板
     *
     * @param array $brands
     * @param integer $brand_id
     * @return string
     */
    protected function load_brands($classify_id = 0, $brand_id = 0)
    {
    	$str = '<option value="0">----</option>';
    	
    	$brands = BLL_Product_Brand::get_clsbrdrs($classify_id);
		foreach ($brands as $brand)
		{
			$str .= '<option value="' . $brand['id'] . '"';
			if ($brand['id'] == $brand_id)
			{
				$str .= ' selected';
			}
			$str .= '>' . htmlspecialchars($brand['name']) . '</option>';
		}
    	
    	return $str;
    }
    
    public function pic_upform()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented.',
            'content'       => array(),
        );
       try {
            //* 初始化返回数据 */
            $return_data = array();
            $product = array();
            
            //* 收集请求数据 ==根据业务逻辑定制== */
            $return_data['product'] = $product = $request_data = $this->input->get();
            
            $picture_max_size = 0;
            $picture_types    = array();
            $picture_attach   = Kohana::config('attach.productPicAttach');
            $picture_max_size = $picture_attach['fileSizePreLimit']/1024/1024;
            if (!preg_match('/^\d+$/', $picture_max_size)) {
            	$picture_max_size = number_format($picture_max_size, 2);
            }
            $picture_types = $picture_attach['allowTypes'];
       		foreach ($picture_types as $idx => $item) {
                $picture_types[$idx] = strtolower($item);
            }

            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = 'Sucess.';
            $return_struct['content']= $return_data;

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                //* 模板输出 */
                $this->template = new View('layout/commonblank_html');
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
                $this->template->content->picture_max_size = $picture_max_size;
                $this->template->content->picture_types    = $picture_types;
            }// end of request type determine
        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function pic_upload()
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
            
            $request_data = $this->input->post();
            $request_product_id = $request_data['product_id'];
            
            // 请求的对应的图片说明
            $request_pic_title_assoc =  !empty($request_data) && isset($request_data['myPorductpicTitle']) && is_array($request_data['myPorductpicTitle']) && !empty($request_data['myPorductpicTitle'])?$request_data['myPorductpicTitle']:array();

            //多附件上传 上传的表单域名字
            $attach_field = 'myPorductpic';
            // 附件应用类型
            $attach_app_type = 'productPicAttach';
            // 如果有上传请求
            if(!page::issetFile($attach_field)){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'),400);
            }

            //读取当前应用配置
            $attach_setup = Kohana::config('attach.'.$attach_app_type);
            $mime_type2postfix = Kohana::config('mimemap.type2postfix');
            $mime_postfix2type = Kohana::config('mimemap.postfix2type');

            // 表单文件上传控件总数量
            $file_upload_count = page::getFileCount($attach_field);
            // 初始化一些数据
            // 本次文件上传总数量
            $file_count_total = 0;
            // 本次文件上传总大小
            $file_size_total = 0;
            // 上传文件meta信息
            $file_meta_data = array();
            // 遍历所有的上传域 //验证上传/采集上传信息
            for($index=0;$index<$file_upload_count;$index++)
            {
                // 如果上传标志成功
                if((int) $_FILES[$attach_field]['error'][$index] === UPLOAD_ERR_OK)
                {
                    if(!is_uploaded_file($_FILES[$attach_field]['tmp_name'][$index])){
                        throw new MyRuntimeException(Kohana::lang('o_product.file_upload_error'),400);
                    }
                    $file_size_current = filesize($_FILES[$attach_field]['tmp_name'][$index]);
                    if($attach_setup['fileSizePreLimit']>0 && $file_size_current>$attach_setup['fileSizePreLimit']){
                        throw new MyRuntimeException(Kohana::lang('o_product.file_size_not_flow').$attach_setup['fileSizePreLimit'],400);
                    }
                    
                    $file_type_current = FALSE;
                    $file_type_current === FALSE && $file_type_current = page::getFileType($attach_field,$index); // 尝试通过Mime类型判断
                    $file_type_current === FALSE && page::getImageType($_FILES[$attach_field]['tmp_name'][$index]); // 尝试通过图片类型判断
                    $file_type_current === FALSE && $file_type_current = page::getPostfix($attach_field,$index); // 尝试通过后缀截取

                   	//array_walk($attach_setup['allowTypes'], 'strtolower');
                   	foreach ($attach_setup['allowTypes'] as $idx => $item) {
                   		$attach_setup['allowTypes'][$idx] = strtolower($item);
                   	}
                    if(!empty($attach_setup['allowTypes']) && !in_array(strtolower($file_type_current),$attach_setup['allowTypes'])){
                    	throw new MyRuntimeException(Kohana::lang('o_product.pic_type_error'),400);
                    }
                    // 当前文件mime类型
                    $file_mime_current = isset($_FILES[$attach_field]['type'][$index])?$_FILES[$attach_field]['type'][$index]:'';
                    // 检测规整mime类型
                    if(!array_key_exists($file_mime_current,$mime_type2postfix)){
                        if(array_key_exists($file_type_current,$mime_postfix2type)){
                            $file_mime_current = $mime_postfix2type[$file_type_current];
                        }else{
                            $file_mime_current = 'application/octet-stream';
                        }
                    }
                    
                    //存储文件meta信息
                    $file_meta_data[$index]=array(
                        'name'=>strip_tags(trim($_FILES[$attach_field]['name'][$index])),
                        'size'=>$file_size_current,
                        'type'=>$file_type_current,
                        'mime'=>$file_mime_current,
                        'tmpfile'=>$_FILES[$attach_field]['tmp_name'][$index],
                    );
                    // 设置上传总数量
                    $file_count_total +=1;
                    // 设置上传总大小
                    $file_size_total+=$file_size_current;
                } else {
                	throw new MyRuntimeException(Kohana::lang('o_product.pic_upload_failed'), 400);
                }
            }
            
            if($attach_setup['fileCountLimit']>0 && $file_count_total>$attach_setup['fileCountLimit']){
                throw new MyRuntimeException(Kohana::lang('o_product.file_count_limit').$attach_setup['fileCountLimit'],400);
            }
            if($attach_setup['fileSizeTotalLimit']>0 && $file_size_total>$attach_setup['fileSizeTotalLimit']){
                throw new MyRuntimeException(Kohana::lang('o_product.file_size_total_limit').$attach_setup['fileSizeTotalLimit'].Kohana::lang('o_product.size').$file_size_total,400);
            }
            
            /*
            // 当前时间戳
            $timestamp_current = time();
            //预备一些数据
            $src_ip_address = $this->input->ip_address();

            $attach_meta = array(
                'siteId'=>$site_id,
                'siteDomain'=>$site_domain,
            );
            // 调用附件服务
            //$attachmentService = AttachmentService::get_instance();
            require_once(Kohana::find_file('vendor', 'phprpc/phprpc_client',TRUE));
            !isset($attachmentService) && $attachmentService = new PHPRPC_Client(Kohana::config('phprpc.remote.Attachment.host'));
            !isset($phprpcApiKey) && $phprpcApiKey = Kohana::config('phprpc.remote.Attachment.apiKey');
            */
            
            $route_prefix = Kohana::config('attach.routePrefix');
            $route_mask_view = Kohana::config('attach.routeMaskViewProduct');
            
            // 调用productpic服务
            $productpicService = ProductpicService::get_instance();

            // 遍历所有的上传meta域
            foreach($file_meta_data as $index=>$file_meta){
                $row_meta_struct = array();
                /*$attachment_data_original = array(
                    'filePostfix'=>$file_meta['type'],
                    'fileMimeType'=>$file_meta['mime'],
                    'fileSize'=>$file_meta['size'],
                    'fileName'=>$file_meta['name'],
                    'srcIp'=>$src_ip_address,
                    'attachMeta'=>json_encode($attach_meta),
                    'createTimestamp'=>$timestamp_current,
                    'modifyTimestamp'=>$timestamp_current,
                );                               
                // 调用后端添加附件信息，并调用存储服务存储文件                
                $args_org = array($attachment_data_original);
                $sign_org = md5(json_encode($args_org).$phprpcApiKey);
                $attachment_original_id = $attachmentService->phprpc_addAttachmentFileData($attachment_data_original,@file_get_contents($file_meta['tmpfile']),$sign_org);
                if (!is_numeric($attachment_original_id))
                {
                	throw new MyRuntimeException(Kohana::lang('o_product.phprpc_pic_save_failed'), 400);
                }*/
                $att = AttService::get_instance($this->img_dir_name);
                $img_id = $att->save_default_img($file_meta['tmpfile']);
                if(!$img_id){
                	throw new MyRuntimeException(Kohana::lang('o_product.phprpc_pic_save_failed'), 500);
                }
                $productpic_data = array(
                    'product_id' => $request_product_id,
                    'is_default' => ProductpicService::PRODUCTPIC_IS_DEFAULT_FALSE,
                    'title' => isset($request_pic_title_assoc[$index])?strip_tags(trim($request_pic_title_assoc[$index])):'',
                    'image_id' => $img_id,
                    //'create_time'=>$timestamp_current,
                    //'update_time'=>$timestamp_current,
                );
                $productpic_row_id = $productpicService->add($productpic_data);
                
                $return_row_struct = array(
                    'id' => $productpic_row_id,
                    'is_default' => $productpic_data['is_default'],
                    'title' => $productpic_data['title'],
                    'image_id' => $img_id,
                    //'picurl_o'=>$att->get_img_url($img_id),
                    //'picurl_t'=>$att->get_img_url($img_id),
                    'picurl_o' => ProductpicService::get_attach_url($route_prefix,$img_id,ProductpicService::PRODUCTPIC_STANDARDS_ORIGINAL,$file_meta['type'],$route_mask_view),
                    'picurl_t' => ProductpicService::get_attach_url($route_prefix,$img_id,ProductpicService::PRODUCTPIC_STANDARDS_THUMBNAIL,$file_meta['type'],$route_mask_view),
                    'picurl_l' => ProductpicService::get_attach_url($route_prefix,$img_id,ProductpicService::PRODUCTPIC_STANDARDS_LARGE,$file_meta['type'],$route_mask_view),
                );
                $return_data[] = $return_row_struct;
                // 清理临时文件
                @unlink($file_meta['thumb']['tmpfile']);
            }
            
            if(!empty($return_data) && $request_product_id>0){
                //检测是否存在默认图片，没有则设置商品默认图片
                if($productpicService->has_default_pic($request_product_id)==FALSE){
                    $default_productpic_id = $return_data[0]['id'];
                    $productpicService->set_default_pic_by_productpic_id($default_productpic_id, $request_product_id);
                    $return_data[0]['is_default'] = ProductpicService::PRODUCTPIC_IS_DEFAULT_TRUE;
                }
            }
            //Cache::remove('product_pictures.'.$request_product_id);

            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '上传成功!';
            $return_struct['content']= $return_data;
            
            //$pics = $productpicService->get_stand_pic_by_pic_id($productpic_row_id, ProductpicService::PRODUCTPIC_STANDARDS_COMMON);

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                $this->template = new View('layout/empty_html');
                //$this->template->manager_data = $this->manager;
                //* 模板输出 */
                //$this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
                //* 变量绑定 */
                //$this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                //$this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                //$this->template->content->return_struct = $return_struct;
                $this->template->content->picture = $return_struct['content'];
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $this->template = new View('layout/default_html');
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function pic_set_default()
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
            $request_data = $this->input->get();
            
            if (empty($request_data['picture_id'])) {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
            //调用图片服务
            $pictures = array();
            $current_picture = 0;
            $default_picture = 0;      
			$productpic_service = ProductpicService::get_instance();			
            $productpic_service->set_default_pic_by_productpic_id($request_data['picture_id'], $request_data['product_id']);
            if (isset($request_data['product_id']) && $request_data['product_id']>0)
            {
                $pictures = $productpic_service->get_stand_pic_list_by_product_id($request_data['product_id']);
                $current_picture = 0;
                $default_picture = 0;
                foreach ($pictures as $idx => $picture) {
                	if ($picture['is_default'] == ProductpicService::PRODUCTPIC_IS_DEFAULT_TRUE) {
                		$current_picture = $idx;
                		$default_picture = $idx;
                		break;
                	}
                }
            }
            $return_data['pictures'] = $pictures;
            $return_data['current_picture'] = $current_picture;
            $return_data['default_picture'] = $default_picture;
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = Kohana::lang('o_product.pic_set_default_ok');
            $return_struct['content']= $return_data;

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求 json 输出
                $this->template->content = $return_struct;
            }else{
                $this->template->content = new View('info');
                $this->template->content->return_struct = $return_struct;
            }// end of request type determine
            
        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function pic_delete()
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
            $request_data = $this->input->get();
            if (empty($request_data['product_id']) OR empty($request_data['picture_id'])) {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }

            // 调用底层服务
			$productpic_service = ProductpicService::get_instance();
			
			// 验证图片是否存在
			//$picture = $productpic_service->get($request_data['picture_id']);

			/* 验证图片是否正在被关联
			$query_struct = array('where'=>array(
				'productpic_id' => $picture['id'],
			));*/
			
			//if (Product_attributeoption_productpic_relationService::get_instance()->query_count($query_struct) > 0) {
			//	throw new MyRuntimeException(Kohana::lang('o_product.pic_has_relation'), 400);
			//}
			/*delete by zhu
            $good_pic_relations = Goods_productpic_relationService::get_instance()->query_assoc($query_struct);
			if (!empty($good_pic_relations)) {
				$good_ids = array();
				foreach ($good_pic_relations as $good_pic_relation) {
					$good_ids[$good_pic_relation['goods_id']] = TRUE;
				}
				$query_struct = array('where' => array(
					'id' => array_keys($good_ids),
				));
				$goods = ProductService::get_instance()->query_assoc($query_struct);
				if (count($goods) != count($good_ids)) {
					throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
				}
				foreach ($goods as $key => $good) {
					$good = coding::decode_good($good);
					if (!empty($good['goods_productpic_relation_struct']['items'])) {
						$idx = array_search($picture['id'], $good['goods_productpic_relation_struct']['items']);
						if ($idx !== FALSE) {
							unset($good['goods_productpic_relation_struct']['items'][$idx]);
							if (empty($good['goods_productpic_relation_struct']['items'])) {
								unset($good['goods_productpic_relation_struct']['items']);
							}
						} else {
							throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
						}
					} else {
						throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
					}
					$good['update_timestamp'] = time();
					$goods[$key] = coding::encode_good($good);
				}
				foreach ($goods as $good) {
					ProductService::get_instance()->set($good['id'], $good);
				}
				ORM::factory('goods_productpic_relation')->where('productpic_id', $picture['id'])->delete_all();
			}			
			ORM::factory('product_attributeoption_productpic_relation')
                ->where('productpic_id', $request_data['picture_id'])->delete_all();*/
			
            $productpic_service->delete_productpic($request_data['picture_id'], $request_data['product_id']);
            
            $pictures = $productpic_service->get_stand_pic_list_by_product_id($request_data['product_id']);
            $current_picture = 0;
            $default_picture = 0;
            foreach ($pictures as $idx => $picture) {
            	if ($picture['is_default'] == ProductpicService::PRODUCTPIC_IS_DEFAULT_TRUE) {
            		$current_picture = $idx;
            		$default_picture = $idx;
            		break;
            	}
            }
            
            $return_data['pictures'] = $pictures;
            $return_data['current_picture'] = $current_picture;
            $return_data['default_picture'] = $default_picture;
            
            //Cache::remove('product_pictures'.$request_data['id']);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = Kohana::lang('o_global.delete_success');
            $return_struct['content']= $return_data;

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
            	// 仅允许AJAX调用
            	//throw new MyRuntimeException('Not Implemented',501);
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
                
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
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
    
    public function set_front_visible()
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
            BLL_Product::set_front_visible($request_data['product_id'], $request_data['status']);
            
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
                // html 输出
                $this->template = new View('layout/commonblank_html');
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
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
    
    public function picrelation()
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
            
            //* 逻辑验证 ==根据业务逻辑定制== empty($request_data['product_id']) OR */
            if (empty($request_data['save_handler']))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
            if(!empty($request_data['product_id']))
            {
                $product = BLL_Product::get($request_data['product_id']);
            }
            
            $return_data['pictures']     = isset($product['pictures'])?$product['pictures']:'';
            $return_data['save_handler'] = $request_data['save_handler'];
            
            if (!empty($request_data['pids']))
            {
            	$return_data['pids'] = explode(',', $request_data['pids']);
            } else {
            	$return_data['pids'] = array();
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $return_data;

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                $this->template = new View('layout/commonblank_html');
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/pictures');
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
    
    public function get_attroptrs()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented.',
            'content'       => array(),
        );
       try {
            //* 初始化返回数据 */
            $return_data = array();
            $request_data = $this->input->get();
            
            if (isset($request_data['classify_id']))
            {
            	$attributes = BLL_Product_Attribute::get_clsattrrs($request_data['classify_id']);
            } 
            else 
            {
            	$aids = empty($request_data['aids']) ? array() : explode(',', $request_data['aids']);
            	$attributes = array();
            	if (!empty($aids))
            	{
            		$attributes = BLL_Product_Attribute::index(array(
            			'id' => $aids
            		));
            	}
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = 'Sucess.';
            $return_struct['content']= $return_data;

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                //* 模板输出 */
                $this->template = new View('layout/commonblank_html');
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/configurable/attributes');
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
                $this->template->content->attributes    = $attributes;
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function attrrelation()
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
            // 调用底层服务
            $attributes = BLL_Product_Attribute::index(array(
            	'apply' => AttributeService::ATTRIBUTE_SPEC
            ));
            $aids = empty($request_data['aids']) ? array() : explode(',', $request_data['aids']);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $return_data;

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                $this->template = new View('layout/commonblank_html');
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/configurable/'.__FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                $this->template->content->attributes    = $attributes;
                $this->template->content->aids          = $aids;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function set_attroptpicrs()
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
            $request_data = $this->input->get();
            
       		if (!empty($request_data['product_id']))
       		{
                $product_id = $request_data['product_id'];
                //$product = BLL_Product::get($request_data['product_id']);
                
                ORM::factory('product_attributeoption_productpic_relation')->where('product_id', $product_id)->delete_all();
                if (!empty($request_data['picrels']))
                {
                	if (!is_array($request_data['picrels']))
    	       		{
    	            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
    	            }
    	            
    	            $attributes = BLL_Product_Attribute::index(array(
    	            	'id' => array_keys($request_data['picrels']),
    	            ));
    	            
    	            if (count($request_data['picrels']) != count($attributes))
    	            {
    	            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
    	            }
    	            
    	            foreach ($request_data['picrels'] as $aid => $ors)
    	            {
    	            	foreach ($ors as $oid => $pids)
    	            	{
    	            		if (isset($attributes[$aid]['options'][$oid]))
    	            		{
    	            			if (!empty($pids))
    		            		{
    		            			foreach (explode(',', $pids) as $pid)
    		            			{
    		            				//if (isset($product['pictures'][$pid])){
    		            					Product_attributeoption_productpic_relationService::get_instance()->add(array(
    		            						'product_id'         => $product_id,
    		            						'attribute_id'       => $aid,
    		            						'attributeoption_id' => $oid,
    		            						'productpic_id'      => $pid,
    		            					));
    		            				//}
    		            			}
    		            		}
    	            		}
    	            	}
    	            }
                }
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $return_data;

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
            	
                // html 输出
                $this->template = new View('layout/commonblank_html');
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/configurable/'.__FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                $this->template->content->attributes    = $attributes;
                $this->template->content->site_id       = $request_data['site_id'];
                $this->template->content->aids          = $aids;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function get_goods()
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
            
       		if (empty($request_data['attroptrs']))
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            $product_id = isset($request_data['product_id'])?$request_data['product_id']:'';
            if($product_id>0){
                $product = BLL_Product::get($request_data['product_id']);
            }else{
                $product = array();   
            }
            
            function array_configurable($arrays)
			{
				$result = array();
				$array  = array_shift($arrays);
				if (empty($arrays))
				{
					foreach ($array as $key => $val)
					{
						$array[$key] = array($val);
					}
					return $array;
				} else {
					foreach ($array as $val)
					{
						foreach (array_configurable($arrays) as $item)
						{
							array_unshift($item, $val);
							$result[] = $item;
						}
					}
					return $result;
				}
			}
			
			$attributes = BLL_Product_Attribute::index(array(
				'id'      => array_keys($request_data['attroptrs']),
			));
            
			if (count($attributes) != count($request_data['attroptrs']))
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
			}
			
			$goods = array();
			$i = 0;
			foreach ($request_data['attroptrs'] as $aid => $oids)
			{
				if (!empty($oids) AND is_array($oids))
				{
					$goods[$i] = array();
					foreach ($oids as $oid)
					{
						$goods[$i][] = array($aid, $oid);
					}
					$i++;
				} else {
					throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
				}
			}
			$goods = array_configurable($goods);
			foreach ($goods as $key => $good)
			{
				$struct = array();
				foreach ($good as $item)
				{
					$struct[$item[0]] = $item[1];
				}
				$goods[$key] = array(
					'is_default' => $key == 0 ? 1 : 0,
					'attroptrs'  => $struct,
				);
			}
			//d($attributes,1); d($goods);
			$template = new View($this->package_name.'/'.$this->class_name.'/configurable/goods');
            $template->attributes = $attributes;
            $template->goods      = $goods;
            
            $return_data = array(
            	'tpl'   => (string)$template,
            	'goods' => $goods,
            );
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $return_data;

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{            	
                // html 输出
                $this->template = new View('layout/commonblank_html');
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/configurable/'.__FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                $this->template->content->attributes    = $attributes;
                $this->template->content->aids          = $aids;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    /**
     * 获取可以绑定的货品
     *
     * @author gehaifeng
     */
    public function get_goods_not_binded(){
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
            
            //* 权限验证 ==根据业务逻辑定制== */
            $site_id_list = role::get_site_ids();
            
       		if ( empty($request_data['site_id']) || empty($request_data['product_id']) )
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
            }
            
            if (!in_array($request_data['site_id'], $site_id_list))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            //ajax请求处理
			if (isset($request_data['is_ajax']) && $request_data['is_ajax']==1) {
				if (isset($request_data['select_classify'])) {
					$classify_id = intval($request_data['select_classify']);
				}
			}
			
            $page = isset($request_data['page']) ? intval($request_data['page']) : 1; 
            $per_page = 10;
            //获取货品信息，判断是否有提交查询，选择不同的操作
			if (isset($request_data['select_type']) && $request_data['select_key']!='') {
				$product = BLL_Product_Type_Binding::get_goods_nb_by_select($request_data,$page,$per_page);
			}else {
				$product = BLL_Product::get($request_data['product_id'],$page,$per_page);
			}
            
            $return_data = array();
            
            $this->pagination = new Pagination(array(
				'total_items'    => isset( $product['goods_nb_count'] ) ? $product['goods_nb_count'] : 0,
				'items_per_page' => $per_page,
			));
			
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request()){
            	
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{            	
                // html 输出
                $this->template = new View('layout/commonblank_html');
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/binding/goods_nb_show');
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->product = $product;
                $this->template->content->request_data = $request_data;
                $this->template->content->pagination = $this->pagination;                
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
}