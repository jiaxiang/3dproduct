<?php
defined('SYSPATH') or die('No direct access allowed.');
class Classify_Controller extends Template_Controller {
    // Disable this controller when Kohana is set to production mode.
    const ALLOW_PRODUCTION = TRUE;
    
    private $class_name = '';
    private $package = '';
    // Set the name of the template to use
    public $template_ = 'layout/common_html';
    
    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->package = 'product';
        $this->class_name = strtolower(substr(__CLASS__, 0, strpos(__CLASS__, '_')));
        parent::__construct();
        if($this->is_ajax_request()){
            $this->template = new View('layout/default_json');
        }
    }
    
    /**
     * 数据列表
     */
    public function index()
    {
        role::check('product_classify');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            // 执行业务逻辑
            //* 初始化默认查询结构体 */
            $query_struct_default = array (
                'where' => array (), 
                'like' => array (), 
                'orderby' => array (
                    'id' => 'DESC' 
                ), 
                'limit' => array (
                    'per_page' => 20, 
                    'page' => 1 
                ) 
            );
            //* 初始化当前查询结构体 */
            $query_struct_current = array ();
            //* 设置合并默认查询条件到当前查询结构体 */
            $query_struct_current = array_merge($query_struct_current, $query_struct_default);
            
            //列表排序
            $orderby_arr = array (
                0 => array (
                    'id' => 'DESC' 
                ), 
                1 => array (
                    'name' => 'ASC' 
                ), 
                2 => array (
                    'name' => 'ASC' 
                ), 
                3 => array (
                    'name' => 'DESC' 
                ), 
                4 => array (
                    'update_timestamp' => 'ASC' 
                ), 
                5 => array (
                    'update_timestamp' => 'DESC' 
                ) 
            );
            $orderby = controller_tool::orderby($orderby_arr);
            // 排序处理 
            if(isset($request_data['orderby']) && is_numeric($request_data['orderby'])){
                $query_struct_current['orderby'] = $orderby;
            }
            
            // 每页条目数
            controller_tool::request_per_page($query_struct_current, $request_data);
            
            //调用服务执行查询
            $classify_service = ClassifyService::get_instance();
            $count = $classify_service->count($query_struct_current);
            // 模板输出 分页
            $this->pagination = new Pagination(array (
                'total_items' => $count, 
                'items_per_page' => $query_struct_current['limit']['per_page'] 
            ));
            $query_struct_current['limit']['page'] = $this->pagination->current_page;
            
            $return_data['list'] = $classify_service->index($query_struct_current);
            
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
                // html 输出
                //* 模板输出 */
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = '类型管理';
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            } // end of request type determine

        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    /**
     * 添加数据页面
     */
    public function add()
    {
        role::check('product_classify_add');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //* 初始化返回数据 */
            $return_data = array ("action"=>'put');
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            // 调用底层服务
            $classify_service = ClassifyService::get_instance();
            
            //品牌
            $return_data['brand_list'] = BrandService::get_instance()->get_brands();
            
            //规格
            $return_data['attribute_list'] = AttributeService::get_instance()->get_attributes_spec();

            //特性
            $return_data['feature_list'] = AttributeService::get_instance()->get_attributes_feature();
            
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
                // html 输出
                //* 模板输出 */
                $content = new View($this->package . '/' . $this->class_name . '/edit');
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            
            } // end of request type determine
        

        }catch(MyRuntimeException $ex){
             $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function put()
    {
        role::check('product_classify_add');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
            $request_data = trims::run($request_data);
            //标签过滤
            tool::filter_strip_tags($request_data);
            //数据验证
            $validResult = Validation::factory($request_data)->pre_filter('trim')->add_rules('name', 'required', 'length[1,100]');
            
            if($validResult->validate() == FALSE){
                //* 输出错误的具体信息 ==根据业务逻辑定制== */
                $return_struct['content']['errors'] = $validResult->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            //执行添加
            $set_data = array ();
            $set_data['name'] = $request_data['name'];
            $set_data['alias'] = $request_data['alias'];
            $set_data['create_timestamp'] = time();
            $set_data['update_timestamp'] = time();
            
            // 调用底层服务
            $classify_service = ClassifyService::get_instance();
            
        	//判断name是否存在
            if($classify_service->check_exist_name($set_data['name'])){
                throw new MyRuntimeException(Kohana::lang('o_product.product_classify_exist'), 409);
            }
            
            //类型的参数处理
            $this->get_arguments($request_data, $set_data);
            
            $return_data['classify_id'] = $classify_service->add($set_data);
            if(!$return_data['classify_id']){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 500);
            }
            
            //相关品牌添加
            if(isset($request_data['brand_id']) && is_array($request_data['brand_id'])){
                foreach($request_data['brand_id'] as $key => $val){
                    $set_data = array ();
                    $set_data['classify_id'] = $return_data['classify_id'];
                    $set_data['brand_id'] = $val;
                    if(isset($request_data['brand_show'][$val])){
                        $set_data['is_show'] = $request_data['brand_show'][$val];
                    }
                    $result = Classify_brand_relationService::get_instance()->add($set_data);
                    if(!$result){
                        throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 500);
                    }
                }
            }
            //相关规格添加
            if(isset($request_data['attribute_id']) && is_array($request_data['attribute_id'])){
                foreach($request_data['attribute_id'] as $val){
                    $set_data = array ();
                    $set_data['type'] = AttributeService::ATTRIBUTE_SPEC;
                    $set_data['classify_id'] = $return_data['classify_id'];
                    $set_data['attribute_id'] = $val;
                    if(isset($request_data['attribute_show'][$val])){
                        $set_data['is_show'] = $request_data['attribute_show'][$val];
                    }
                    $result = Classify_attribute_relationService::get_instance()->add($set_data);
                    if(!$result){
                        throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 500);
                    }
                }
            }
            //相关特性添加
            if(isset($request_data['feature_id']) && is_array($request_data['feature_id'])){
                foreach($request_data['feature_id'] as $val){
                    $set_data = array ();
                    $set_data['type'] = AttributeService::ATTRIBUTE_FEATURE;
                    $set_data['classify_id'] = $return_data['classify_id'];
                    $set_data['attribute_id'] = $val;
                    if(isset($request_data['feature_show'][$val])){
                        $set_data['is_show'] = $request_data['feature_show'][$val];
                    }
                    $result = Classify_attribute_relationService::get_instance()->add($set_data);
                    if(!$result){
                        throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 500);
                    }
                }
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '添加成功';
            $return_struct['content'] = $return_data;
            $return_struct['action'] = array (
                'type' => 'location', 
                'url' => url::base() . 'product/' . $this->class_name . '/' . 'index' 
            );
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            
            } // end of request type determine

        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
        }
    }

    public function edit()
    {
        role::check('product_classify_edit');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array ("action"=>'post');
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //数据验证
            if(!isset($request_data['id']) || !is_numeric($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            $classify_service = ClassifyService::get_instance();
            $classify = $classify_service->get($request_data['id']);
            
            //返回数据
            $return_data['data'] = $classify;
            
            //获取关联品牌、规格、特新的id数组
            $return_data['data']['brands'] = $classify_service->get_brand_relations_by_classify_id($classify['id']);
            $return_data['data']['attributes'] = $classify_service->get_attribute_relations_by_classify_id($classify['id'], AttributeService::ATTRIBUTE_SPEC);
            $return_data['data']['features'] = $classify_service->get_attribute_relations_by_classify_id($classify['id'], AttributeService::ATTRIBUTE_FEATURE);
            
            //所有的品牌、规格、特新
            $return_data['brand_list'] = BrandService::get_instance()->get_brands();
            $return_data['attribute_list'] = AttributeService::get_instance()->get_attributes_spec();
            $return_data['feature_list'] = AttributeService::get_instance()->get_attributes_feature();

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
                // html 输出
                //* 模板输出 */
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            
            } // end of request type determine
        

        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
        
    public function post()
    {
        role::check('product_classify_edit');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            /*初始化返回数据*/
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
            $request_data = trims::run($request_data);
            
            //标签过滤
            tool::filter_strip_tags($request_data);
            
            if(!isset($request_data['id']) || !is_numeric($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
            //数据验证
            $validResult = Validation::factory($request_data)->pre_filter('trim')->add_rules('name', 'required', 'length[1,100]');
            if($validResult->validate() == FALSE){
                //* 输出错误的具体信息 ==根据业务逻辑定制== */
                $return_struct['content']['errors'] = $validResult->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            
            //执行修改
            $set_data = array ();
            $set_data['id'] = $request_data['id'];
            $set_data['name'] = $request_data['name'];
            $set_data['alias'] = $request_data['alias'];
            $set_data['update_timestamp'] = time();
            
            // 调用底层服务
            $classify_service = ClassifyService::get_instance();
            $classify = $classify_service->get($request_data['id']);
            
        	//判断name是否存在
            if($classify['name']!=$set_data['name'] && $classify_service->check_exist_name($set_data['name'])){
                throw new MyRuntimeException(Kohana::lang('o_product.product_classify_exist'), 409);
            }
            
            $this->get_arguments($request_data, $set_data);
            $classify_service->set($set_data['id'], $set_data);
            
            //相关品牌修改
            $request_data['brand_id'] = isset($request_data['brand_id']) ? $request_data['brand_id'] : array();
            $request_data['brand_show'] = isset($request_data['brand_show']) ? $request_data['brand_show'] : array();
            $classify_service->update_relations_by_brand_ids($set_data['id'], $request_data['brand_id'], $request_data['brand_show']);
            
            //相关规格修改
            $request_data['attribute_id'] = isset($request_data['attribute_id']) ? $request_data['attribute_id'] : array();
            $request_data['attribute_show'] = isset($request_data['attribute_show']) ? $request_data['attribute_show'] : array();
            $classify_service->update_relations_by_attribute_ids($set_data['id'], $request_data['attribute_id'], $request_data['attribute_show'], AttributeService::ATTRIBUTE_SPEC);
            
            //相关特性修改
            $request_data['feature_id'] = isset($request_data['feature_id']) ? $request_data['feature_id'] : array();
            $request_data['feature_show'] = isset($request_data['feature_show']) ? $request_data['feature_show'] : array();
            $classify_service->update_relations_by_attribute_ids($set_data['id'], $request_data['feature_id'], $request_data['feature_show'], AttributeService::ATTRIBUTE_FEATURE);
            
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
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            
            } // end of request type determine
        

        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function delete()
    {
        role::check('product_classify_delete');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //数据验证
            if(!isset($request_data['id']) || !is_numeric($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            $classify_service = ClassifyService::get_instance();
            
            //执行删除
            $classify_service->delete_classify_by_classify_id($request_data['id']);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '删除成功';
            $return_struct['content'] = $return_data;
            $return_struct['action'] = array (
                'type' => 'location', 
                'url' => url::base() . 'product/' . $this->class_name . '/' . 'index' 
            );
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            
            } // end of request type determine

        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function delete_all()
    {
        role::check('product_classify_delete');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
            
            //数据验证
            if(!isset($request_data['id']) || empty($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            $classify_service = ClassifyService::get_instance();
            //执行删除
            if(isset($request_data['id']) || !empty($request_data['id'])){
                $classify_service->delete_classifies($request_data['id']);
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '删除成功';
            $return_struct['content'] = $return_data;
            $return_struct['action'] = array (
                'type' => 'location', 
                'url' => url::base() . 'product/' . $this->class_name . '/' . 'index' 
            );
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
                // html 输出
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            
            } // end of request type determine

        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function transport()
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
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            //* 权限验证 */
            $site_id = site::id();
            if($site_id == 0){
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            role::check('product_classify', $site_id, 0);
            
            $classifies = ClassifyService::get_instance()->query_assoc(array('where' => array(
            	'site_id' => $site_id,
            )));
            
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
                // html 输出
                //* 模板输出 */
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
            	$this->template->content->classifies = $classifies;
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
    
    public function transport_post()
    {
    	$return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            set_time_limit(0);
            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
            
            //* 权限验证 */
            $site_id = site::id();
            if($site_id == 0)
            {
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            role::check('product_classify', $site_id, 0);
            
            //数据验证
            if(!isset($request_data['classify_id']) OR !preg_match('/^\d+$/', $request_data['classify_id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
       		if (!isset($request_data['argument_group']) OR !is_array($request_data['argument_group']))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
        	if (!isset($request_data['argument']) OR !is_array($request_data['argument']))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            // 调用底层服务
            $classify = ClassifyService::get_instance()->get($request_data['classify_id']);
            
            if ($classify['site_id'] != $site_id)
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            $categorys = CategoryService::get_instance()->query_assoc(array('where' => array(
            	'site_id'     => $site_id,
            	'classify_id' => $classify['id'],
            )));
            
            $arguments = empty($classify['argument_relation_struct']) ? array() : json_decode($classify['argument_relation_struct'], TRUE);
            $features  = ClassifyService::get_instance()->get_features_by_classify_id($classify['id']);
            
            $transport = array();
            if (!empty($features))
            {
            	foreach ($features as $feature)
            	{
            		if (!empty($request_data['argument_group'][$feature['id']]) AND !empty($request_data['argument'][$feature['id']]))
            		{
            			$gname = trim($request_data['argument_group'][$feature['id']]);
            			$aname = trim($request_data['argument'][$feature['id']]);
            			foreach ($arguments as $group)
            			{
            				if ($gname === $group['name'])
            				{
            					foreach ($group['items'] as $argument)
            					{
            						if ($aname === $argument['name'])
            						{
            							$transport[$feature['id']] = array(
            								$gname,
            								$aname,
            							);
            							break;
            						}
            					}
            					break;
            				}
            			}
            			if (!isset($transport[$feature['id']]))
            			{
            				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            			}
            		}
            	}
            }
            
            if (!empty($transport))
            {
            	if (!empty($categorys))
            	{
            		$category_ids = array();
            		foreach ($categorys as $category)
            		{
            			$category_ids[] = $category['id'];
            		}
            		
            		$products = ProductService::get_instance()->query_assoc(array('where' => array(
            			'site_id'     => $site_id,
            			'category_id' => $category_ids,
            		)));
            		
            		foreach ($products as $product)
            		{
            			$product = coding::decode_product($product);
            			
            			$product_argument_record = Product_argumentService::get_instance()->query_row(array('where' => array(
            				'product_id' => $product['id'],
            			)));
            			$product_argument = empty($product_argument_record) ? array() : json_decode($product_argument_record['arguments'], TRUE);
            			
            			foreach ($transport as $feature_id => $argument)
            			{
            				
            				if (!empty($product['product_feature_relation_struct']['items']))
            				{
            					$key = array_search($feature_id, $product['product_feature_relation_struct']['items']);
            					if ($key !== FALSE)
            					{
            						unset($product['product_feature_relation_struct']['items'][$key]);
            					}
            				}
            				
            				if (!empty($product['product_featureoption_relation_struct']['items'][$feature_id]))
            				{
            					$option_id = $product['product_featureoption_relation_struct']['items'][$feature_id];
            					
            					if (isset($features[$feature_id]['options'][$option_id]))
            					{
            						if (!isset($product_argument[$argument[0]]))
            						{
            							$product_argument[$argument[0]] = array();
            						}
            						$product_argument[$argument[0]][$argument[1]] = $features[$feature_id]['options'][$option_id]['name'];
            					}
            					
            					unset($product['product_featureoption_relation_struct']['items'][$feature_id]);
            				}
            			}
            			
            			ProductService::get_instance()->set($product['id'], coding::encode_product($product));
            			if (!empty($product_argument))
            			{
            				if (empty($product_argument_record))
            				{
	            				Product_argumentService::get_instance()->create(array(
	            					'product_id' => $product['id'],
	            					'arguments'  => json_encode($product_argument),
	            				));
            				} else {
            					Product_argumentService::get_instance()->set($product_argument_record['id'], array(
            						'arguments' => json_encode($product_argument),
            					));
            				}
            			}
            			ORM::factory('product_featureoption_relation')
            				->where('product_id', $product['id'])
            				->delete_all();
            		}
            	}
            	
            	ORM::factory('classify_feature_relation')
            		->where('classify_id', $classify['id'])
            		->in('feature_id', array_keys($transport))
            		->delete_all();
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
                // html 输出
                //* 模板输出 */
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
            

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
    
	public function transport_relation()
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
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //* 权限验证 */
            $site_id = site::id();
            if($site_id == 0)
            {
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            role::check('product_classify', $site_id, 0);
            
            //数据验证
            if(!isset($request_data['classify_id']) || !preg_match('/^\d+$/', $request_data['classify_id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            // 调用底层服务
            $classify = ClassifyService::get_instance()->get($request_data['classify_id']);
            
        	if ($classify['site_id'] != $site_id)
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            $arguments = empty($classify['argument_relation_struct']) ? array() : json_decode($classify['argument_relation_struct'], TRUE);
            $features  = ClassifyService::get_instance()->get_features_by_classify_id($request_data['classify_id']);
            
            $return_struct['arguments'] = $arguments;
            $return_struct['features']  = $features;
            
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
                // html 输出
                //* 模板输出 */
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
            

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
    
    function get_site_data()
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
                'brand_list' => NULL, 
                'attribute_list' => NULL, 
                'feature_list' => NULL 
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException('Not Implemented',501);
            

            //必须为ajax请求
            if(!$this->is_ajax_request()){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            //* 权限验证 */
            $site_id_list = role::check('product_classify', 0, 0);
            if(empty($site_id_list)){
                throw new MyRuntimeException('Access Denied', 403);
            }
            if(isset($request_data['site_id']) && is_numeric($request_data['site_id'])){
                if(!in_array($request_data['site_id'], $site_id_list)){
                    throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
                }
            }
            
            //数据验证
            if(!isset($request_data['site_id']) || !is_numeric($request_data['site_id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            // 调用底层服务
            $classify_service = ClassifyService::get_instance();
            //请求站点品牌列表
            $brands = BrandService::get_instance()->get_brands_by_site_id($request_data['site_id']);
            foreach($brands as $val){
                $return_data['brand_list'] .= '<option value=' . $val['id'] . '>' . $val['name'] . '</option>';
            }
            //请求站点规格列表
            $attributes = AttributeService::get_instance()->get_attributes_by_site_id($request_data['site_id']);
            foreach($attributes as $val){
                $return_data['attribute_list'] .= '<option value=' . $val['id'] . '>' . $val['name'] . '</option>';
            }
            //请求站点规格列表
            $features = FeatureService::get_instance()->get_features_by_site_id($request_data['site_id']);
            foreach($features as $val){
                $return_data['feature_list'] .= '<option value=' . $val['id'] . '>' . $val['name'] . '</option>';
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
                // html 输出
                //* 模板输出 */
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
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

    protected function get_arguments($request_data, & $classify)
    {
    	if (isset($request_data['argument_group_index']))
    	{
    		if (empty($request_data['argument_group_index']) || !is_array($request_data['argument_group_index']))
    		{
    			throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
    		}
    		
    		$argument_relation_struct = array();
    		
    		$gnames = array();
    		$anames = array();
            
            //参数组循环处理
    		foreach ($request_data['argument_group_index'] as $index)
    		{
    			$argument_group = array();
    			
    			$group_name_index = 'argument_group_name_'.$index;
    			$group_alias_index = 'argument_group_alias_'.$index;
    			$argument_name_index = 'argument_name_'.$index;
    			$argument_alias_index = 'argument_alias_'.$index;
    			
    			//if (empty($request_data[$group_name_index]) || empty($request_data[$group_alias_index]))
    			if (empty($request_data[$group_name_index]))
    			{
    				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
    			}
    			
    			$argument_group['name'] = $request_data[$group_name_index];
    			$argument_group['alias'] = $request_data[$group_alias_index];
    			$argument_group['items'] = array();
    			
    			if (isset($gnames[$argument_group['name']]))
    			{
    				throw new MyRuntimeException('组名称不可重复', 400);
    			} else {
    				$gnames[$argument_group['name']] = TRUE;
    			}
    			
    			if (empty($request_data[$argument_name_index]) || !is_array($request_data[$argument_name_index]))
    			{
    				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
    			}
    			/*if (empty($request_data[$argument_alias_index]) || !is_array($request_data[$argument_alias_index]))
    			{
    				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
    			}*/
    			
    			//参数值的循环处理
    			foreach ($request_data[$argument_name_index] as $idx => $argument_name) {
    				$argument = array();
    				
    				if (empty($argument_name))
    				{
    					throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
    				}
    				$argument['name'] = $argument_name;
    				
    				/*if (empty($request_data[$argument_alias_index][$idx]))
    				{
    					throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
    				}*/
    				$argument['alias'] = $request_data[$argument_alias_index][$idx];
    				
    				if (isset($anames[$argument_group['name'].$argument['name']]))
    				{
    					throw new MyRuntimeException('参数名称不可重复', 400);
    				} else {
    					$anames[$argument_group['name'].$argument['name']] = TRUE;
    				}
    				
    				$argument_group['items'][] = $argument;
    			}
    			
    			$argument_relation_struct[] = $argument_group;
    		}
    		
    		$classify['argument_relation_struct'] = json_encode($argument_relation_struct);
    		
    		if (strlen($classify['argument_relation_struct']) >= 8196)
    		{
    			throw new MyRuntimeException('参数关联过多，无法正确保存', 404);
    		}
    	} else {
    		$classify['argument_relation_struct'] = '';
    	}
    }
    
}
