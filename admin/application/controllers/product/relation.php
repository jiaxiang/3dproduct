<?php defined('SYSPATH') OR die('No direct access allowed.');

class Relation_Controller extends Template_Controller {
	public $profiler = NULL;
    private $package_name = '';
    private $class_name = '';

    // Set the name of the template to use
    public $template_ = 'layout/common_html';

    public function __construct()
    {
        $package_name = substr(dirname(__FILE__),strlen(APPPATH.'controllers/'));
        empty($package_name) && $package_name = 'default';
        $this->package_name = $package_name;
        $this->class_name = strtolower(substr(__CLASS__,0,strpos(__CLASS__,'_')));
        parent::__construct();
        if($this->is_ajax_request()==TRUE){
            $this->template = new View('layout/default_json');
        }
    }
    
    public function index()
    {
		$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       try {
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            $search_value  = isset($request_data['keyword'])?$request_data['keyword']:array();
            $category_list = array();
            $brand_list    = array();
            $product_ids   = array();
            $relation_product_ids = array();
            $product_id = $request_data['product_id'];
            
            $product_service = ProductService::get_instance();
            $product = $product_service->get($product_id);
            $product_id = $product['id'];
            if(!$product_id>0)
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            else
            {
                $struct = product::get_struct($request_data);
                $query_struct_current   = $struct['query'];
                $request_struct_current = $struct['request'];               
                if($product['type'] == ProductService::PRODUCT_TYPE_CONFIGURABLE 
                    || $product['type'] == ProductService::PRODUCT_TYPE_ASSEMBLY)
                {
                    $product_id_array = Product_assemblyService::get_instance()->get_good_id_array($product_id);
                    $product_id_array[] = $product_id;
                }
                else
                {
                    $product_id_array = array($product_id);
                }
                $query_struct_current['not_in']['id'] = $product_id_array;
                
                try{                    
                    $return_data = BLL_Product::index($query_struct_current);

                    if (!empty($return_data['assoc'])) 
                    {
                    	// 获取该商品列表中涉及到的分类以及品牌ID
                    	$category_ids = array();
                    	$brand_ids    = array();
                    	foreach ($return_data['assoc'] as $product) {
                    		if ($product['category_id'] != 0) {
                    			$category_ids[$product['category_id']] = TRUE;
                    		}
                    		if ($product['brand_id'] != 0) {
                    			$brand_ids[$product['brand_id']] = TRUE;
                    		}
                    		$product_ids[] = $product['id'];
                    	}
                    	
    	                $query_struct = array('where'=>array(
    	                	'product_id' => $product_id,
    	                ));
    	                foreach (Product_relationService::get_instance()->query_assoc($query_struct) as $relation) {
    	                	if (in_array($relation['relation_product_id'], $product_ids)) {
    	                		$relation_product_ids[] = $relation['relation_product_id'];
    	                	}
    	                }
                    	
                    	// 获取分类列表
                    	if (!empty($category_ids)) {
    	                	$query_struct = array('where' => array(
    	                		'id' => array_keys($category_ids),
    	                	));
    	                	$categorys = CategoryService::get_instance()->query_assoc($query_struct);
    	                	foreach ($categorys as $category) {
    	                		$category_list[$category['id']] = $category['title_manage']?$category['title_manage']:$category['title'];
    	                	}
                    	}
                    	
                    	// 获取品牌列表
                    	if (!empty($brand_ids)) {
    	                	$query_struct = array('where' => array(
    	                		'id' => array_keys($brand_ids),
    	                	));
    	                	$brands = BrandService::get_instance()->query_assoc($query_struct);
    	                	foreach ($brands as $brand) {
    	                		$brand_list[$brand['id']] = $brand['name'];
    	                	}
                    	}
                    }
                    
                    // 模板输出 分页
    		        $this->pagination = new Pagination(array(
    		            'total_items'    => $return_data['count'],
    		            'items_per_page' => $query_struct_current['limit']['per_page'],
    		        ));
                }catch(MyRuntimeException $ex) {
                    //* ==根据业务逻辑定制== */
                    //FIXME 根据service层的异常做一些对应处理并抛出用户友好的异常Message
                    throw $ex;
                }
            }
            
            //* 如果是ajax请求缩减返回的字段 ==根据业务逻辑定制== */
            if($this->is_ajax_request()){
                $requestkeys = array('id', 'category_id', 'title', 'uri_name', 'store', 'on_sale', 'price', 'sku');
                array_walk($return_data['assoc'], 'util::simplify_return_array', $requestkeys);
            }
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $return_data;

            //exit("<div id=\"do_debug\" style=\"clear:both;display:;\"><pre>\n".var_export($return_struct,TRUE)."\n</pre></div>");
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                //* html 输出 ==根据业务逻辑定制== */
                $this->template = new View('layout/commonfix_html');
                //* 模板输出 */
                $this->template->return_struct = $return_struct;

                $content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data   = $request_data;
                $this->template->content->request_struct = $request_struct_current;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title         = Kohana::config('site.name');
                //$this->template->content->site_list     = Mysite::instance()->select_list($site_ids);
                $this->template->content->category_list = $category_list;
                $this->template->content->brand_list    = $brand_list;
                $this->template->content->product_id    = $product_id;
                $this->template->content->product_ids   = $product_ids;
                $this->template->content->relation_ids  = $relation_product_ids;
                $this->template->content->keyword       = $search_value;
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $this->template = new View('layout/commonfix_html');
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function put()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       try {
            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array(

            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            if (empty($request_data['product_id']) OR !isset($request_data['relation_ids'])) {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            //* 权限验证 ==根据业务逻辑定制== */
            $product_service          = ProductService::get_instance();
            $product_relation_service = Product_relationService::get_instance();
            
            $product = $product_service->get($request_data['product_id']);
            
            ORM::factory('product_relation')->where('product_id', $product['id'])->delete_all();
            
            $query_relation_ids = explode('-', $request_data['relation_ids']);
            $relation_ids       = array();
            $products           = array();
            $category_ids       = array();
            $categorys          = array();
            $brand_ids          = array();
            $brands             = array();
            if (!empty($query_relation_ids)) {
	            $query_struct = array('where' => array(
	            	'id' => $query_relation_ids,
	            ));
	            foreach ($product_service->query_assoc($query_struct) as $relation) {
	            	if ($relation['site_id'] == $product['site_id']) {
	            		$relation_ids[$relation['id']] = TRUE;
	            		$category_ids[$relation['category_id']] = TRUE;
	            		if ($relation['brand_id'] > 0) {
	            			$brand_ids[$relation['brand_id']] = TRUE;
	            		}
	            		$products[] = $relation;
	            	}
	            }
            }
            
            if (!empty($category_ids)) {
            	$query_struct = array('where' => array(
            		'id' => array_keys($category_ids),
            	));
            	foreach (CategoryService::get_instance()->query_assoc($query_struct) as $category) {
            		$categorys[$category['id']] = $category['title_manage'];
            	}
            }
            
       		if (!empty($brand_ids)) {
            	$query_struct = array('where' => array(
            		'id' => array_keys($brand_ids),
            	));
            	foreach (BrandService::get_instance()->query_assoc($query_struct) as $brand) {
            		$brands[$brand['id']] = $brand['name'];
            	}
            }
            
            if (!empty($relation_ids)) {
            	foreach ($relation_ids as $key => $val) {
            		$product_relation_service->create(array(
            			'product_id'          => $product['id'],
            			'relation_product_id' => $key,
            		));
            	}
            }
            
            ProductService::get_instance()->set($product['id'], array(
            	'update_timestamp' => time(),
            ));
            
            $list = new View($this->package_name.'/'.$this->class_name.'/list');
            $list->brands    = $brands;
            $list->categorys = $categorys;
            $list->products  = $products;
            
            $return_data['relation_ids'] = $relation_ids;
            $return_data['list'] = (string)$list;
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '关联成功！';
            $return_struct['content']= $return_data;

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
            	throw new MyRuntimeException();
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
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                //$return_struct['action']['type']= 'close';  // 当前应用为弹出窗口所以定义失败后续动作为关闭窗口
                $this->template = new View('layout/default_html');
                $this->template->return_struct = $return_struct;
                $content = new  View('info2');
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
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented.',
            'content'       => array(),
        );
       try {

            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array(

            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException('Not Implemented',501);
            //* 权限验证，数据验证，逻辑验证 ==根据业务逻辑定制== */
       		$site_ids = role::get_site_ids();
            if (empty($site_ids)) {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            
            if (empty($request_data['product_id']) OR empty($request_data['relation_id'])) {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            //* 权限验证 ==根据业务逻辑定制== */
            $product_service          = ProductService::get_instance();
            $product_relation_service = Product_relationService::get_instance();
            
            $product = $product_service->get($request_data['product_id']);
            
            if (!in_array($product['site_id'], $site_ids)) {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            ORM::factory('product_relation')->where('product_id', $request_data['product_id'])
            								->where('relation_product_id', $request_data['relation_id'])
            								->delete_all();
            								
            ProductService::get_instance()->set($product['id'], array(
            	'update_timestamp' => time(),
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
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 404);
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
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                //$return_struct['action']['type']= 'close';  // 当前应用为弹出窗口所以定义失败后续动作为关闭窗口
                $this->template = new View('layout/default_html');
                $this->template->return_struct = $return_struct;
                $content = new  View('info2');
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
    $return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented.',
            'content'       => array(),
        );
       try {

            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array(

            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException('Not Implemented',501);
            //* 权限验证，数据验证，逻辑验证 ==根据业务逻辑定制== */
       		$site_ids = role::get_site_ids();
            if (empty($site_ids)) {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            
            if (empty($request_data['product_id']) OR empty($request_data['relation_ids'])) {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            //* 权限验证 ==根据业务逻辑定制== */
            $product_service          = ProductService::get_instance();
            $product_relation_service = Product_relationService::get_instance();
            
            $product = $product_service->get($request_data['product_id']);
            
            if (!in_array($product['site_id'], $site_ids)) {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $relation_ids = array();
            $query_struct = array('where'=>array(
            	'product_id'          => $product,
            	'relation_product_id' => explode('-', $request_data['relation_ids']),
            ));
            foreach (Product_relationService::get_instance()->query_assoc($query_struct) as $relation) {
            	$relation_ids[$relation['relation_product_id']] = TRUE;
            }
            
            ORM::factory('product_relation')->where('product_id', $request_data['product_id'])
            								->in('relation_product_id', array_keys($relation_ids))
            								->delete_all();
            								
            ProductService::get_instance()->set($product['id'], array(
            	'update_timestamp' => time(),
            ));
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $relation_ids;

            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 404);
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
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                //$return_struct['action']['type']= 'close';  // 当前应用为弹出窗口所以定义失败后续动作为关闭窗口
                $this->template = new View('layout/default_html');
                $this->template->return_struct = $return_struct;
                $content = new  View('info2');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
}