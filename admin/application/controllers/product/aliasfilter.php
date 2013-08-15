<?php defined('SYSPATH') or die('No direct access allowed.');

class Aliasfilter_Controller extends Template_Controller {
    // Disable this controller when Kohana is set to production mode.
    const ALLOW_PRODUCTION = TRUE;
    
    private $class_name = '';
    // Set the name of the template to use
    public $template_ = 'layout/common_html';
    
    /**
     * 构造方法
     */
    public function __construct()
    {
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
        role::check('product_filter');
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
            
            // 执行业务逻辑
            //* 初始化默认查询结构体 */
            $query_struct_default = array (
                'where' => array (
                ), 
                'like' => array (), 
                'orderby' => array (                    
                    'id' => 'DESC' 
                ), 
                'limit' => array () 
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
                    'id' => 'ASC' 
                ), 
                2 => array (
                    'title' => 'ASC' 
                ), 
                3 => array (
                    'title' => 'DESC' 
                ), 
                4 => array (
                    'uri_name' => 'ASC' 
                ), 
                5 => array (
                    'uri_name' => 'DESC' 
                ), 
                6 => array (
                    'update_timestamp' => 'ASC' 
                ), 
                7 => array (
                    'update_timestamp' => 'DESC' 
                ),
                8 => array (
                    'order' => 'ASC' 
                ), 
                9 => array (
                    'order' => 'DESC' 
                )
            );
            $orderby = controller_tool::orderby($orderby_arr);
            // 排序处理 
            if(isset($request_data['orderby']) && is_numeric($request_data['orderby'])){
                $query_struct_current['orderby'] = $orderby;
            }
            // 每页条目数
            //controller_tool::request_per_page($query_struct_current, $request_data);
            

            //调用服务执行查询
            $aliasfilter_service = Alias_filterService::get_instance();
            $return_data['total'] = $aliasfilter_service->count($query_struct_current);
            // 模板输出 分页
            /*
            $this->pagination = new Pagination(array (
                'total_items' => $count, 
                'items_per_page' => $query_struct_current['limit']['per_page'] 
            ));*/
            //$query_struct_current['limit']['page'] = $this->pagination->current_page;
            $result = $aliasfilter_service->index($query_struct_current);
            $return_data['list'] = array ();
            if(!empty($result)){
                foreach($result as $key => $val){
                    $val['create_timestamp'] = date('Y-m-d H:i:s', $val['create_timestamp']);
                    $val['update_timestamp'] = date('Y-m-d H:i:s', $val['update_timestamp']);
                    $return_data['list'][$val['id']] = $val;
                }
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
                $content = new View('product/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = '别名过滤器管理';
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
        role::check('product_filter');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //* 初始化返回数据 */
            $return_data = array (
                'action' => "put", 
                'category_list' => NULL, 
                'classify_list' => NULL 
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            // 调用底层服务
            $category_service = CategoryService::get_instance();
            $filter_service = Alias_filterService::get_instance();

            //当前站点虚拟分类树
            $return_data['filter_list'] = $filter_service->get_tree('<option value={$id} {$selected}>{$spacer}{$title}</option>');
            //当前站点分类树
            $return_data['category_list'] = $category_service->get_tree('<option value={$id} {$selected}>{$spacer}{$title_manage}</option>');
            //当前站点类型
            $classify = ClassifyService::get_instance()->get_classifies();
            foreach($classify as $val){
                $return_data['classify_list'] .= '<option value=' . $val['id'] . '>--' . $val['name'] . '--</option>';
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
                $content = new View('product/' . $this->class_name . '/edit');
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
        role::check('product_filter_edit');
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
            
            // 调用底层服务
            $alias_filter_service = Alias_filterService::get_instance();
            
            //数据验证
            $validResult = Validation::factory($request_data)->add_rules('title', 'required', 'length[1,100]')->add_rules('uri_name', 'required', 'length[1,255]');
            
            if($validResult->validate() == FALSE){
                //* 输出错误的具体信息 ==根据业务逻辑定制== */
                $return_struct['content']['errors'] = $validResult->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            
            //title重复判断
            if(!empty($request_data['title']) && $alias_filter_service->check_exist_title($request_data['title'])){
                throw new MyRuntimeException(Kohana::lang('o_product.category_title_has_exists'), 409);
            }
            //uri_name重复判断
            if(!empty($request_data['uri_name']) && $alias_filter_service->check_exist_uri_name($request_data['uri_name'])){
                throw new MyRuntimeException(Kohana::lang('o_product.uri_name_has_exists'), 409);
            }
            
            //获取过滤结构
            $filter = array ();
            $filter['keywords'] = html::specialchars($request_data['keywords']);
            if(isset($request_data['pricefrom']) && preg_match('/^(0\.\d+|[1-9]\d*(\.\d+)?)$/', $request_data['pricefrom'])){
                $filter['pricefrom'] = $request_data['pricefrom'];
            }
            if(isset($request_data['priceto']) && preg_match('/^(0\.\d+|[1-9]\d*(\.\d+)?)$/', $request_data['priceto'])){
                $filter['priceto'] = $request_data['priceto'];
            }
            if(isset($request_data['brand'])){
                $filter['brands'] = $request_data['brand'];
            }
            if(isset($request_data['attribute'])){
                $filter['attributes'] = $request_data['attribute'];
            }
            if(isset($request_data['feature'])){
                $filter['features'] = $request_data['feature'];
            }
            //执行添加
            $set_data = array ();
            $set_data['pid'] = $request_data['pid'];
            if(!empty($request_data['pid'])){
                $level = $alias_filter_service->get_level_by_filter_id($request_data['pid']);
                $set_data['level_depth'] = $level + 1;
            }
            $set_data['category_id'] = $request_data['category_id'];
            //$set_data['classify_id'] = $request_data['classify_id'];
            $set_data['uri_name'] = $request_data['uri_name'];
            $set_data['title'] = html::specialchars($request_data['title']);
            $set_data['filter_struct'] = json_encode($filter);
            $set_data['create_timestamp'] = time();
            $set_data['update_timestamp'] = time();
            $return_data['id'] = $alias_filter_service->add($set_data);
            if(!$return_data['id']){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 500);
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

    public function edit()
    {
        role::check('product_filter_edit');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //* 初始化返回数据 */
            $return_data = array (
                'action' => "post", 
                'data' => array (), 
                'category_list' => NULL 
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //数据验证
            if(!isset($request_data['id']) || !is_numeric($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            $alias_filter_service = Alias_filterService::get_instance();
            $filter = $alias_filter_service->get($request_data['id']);
            
            //虚拟分类树
            $str = '<option value={$id} {$selected}>{$spacer}{$title}</option>';
            $return_data['filter_list'] = $alias_filter_service->get_tree($str, $filter['pid']);
            
            //返回数据
            $category_service = CategoryService::get_instance();
            $categories = $category_service->get_categories();
            $str = '<option value={$id} {$selected}>{$spacer}{$title_manage}</option>';
            $return_data['category_list'] = tree::get_tree($categories, $str, 0, $filter['category_id']);
            if(!empty($filter['category_id'])){
                $category = $category_service->get($filter['category_id']);
                if(!empty($category['classify_id'])){
                    $classify_service = ClassifyService::get_instance();
                    $classify = $classify_service->get($category['classify_id']);
                    $filter['classify_name'] = $classify['name'];
                    //获取类型关联品牌数组
                    $filter['brand_list'] = $classify_service->get_brands_by_classify_id($category['classify_id']);
                    //获取类型关联规格及规格项数组
                    $filter['attribute_list'] = $classify_service->get_attribute_options_by_classify_id($category['classify_id'], AttributeService::ATTRIBUTE_SPEC);
                    //获取类型关联特性及特性值数组
                    $filter['feature_list'] = $classify_service->get_attribute_options_by_classify_id($category['classify_id'], AttributeService::ATTRIBUTE_FEATURE);
                }
            }else{
                //当前站点品牌
                $filter['brand_list'] = BrandService::get_instance()->get_brands();
                //当前站点规格
                $filter['attribute_list'] = AttributeService::get_instance()->get_attribute_spec_options();
                //当前站点品牌
                $filter['feature_list'] = AttributeService::get_instance()->get_attribute_feature_options();
            }
            
            //获取过滤条件
            $filter['filter_struct'] = json_decode($filter['filter_struct'], TRUE);
            $filter['filter_struct']['pricefrom'] = !empty($filter['filter_struct']['pricefrom']) ? $filter['filter_struct']['pricefrom'] : '';
            $filter['filter_struct']['priceto'] = !empty($filter['filter_struct']['priceto']) ? $filter['filter_struct']['priceto'] : '';
            $return_data['data'] = $filter;

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
                $content = new View('product/' . $this->class_name . '/' . __FUNCTION__);
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
        
    public function post()
    {
        role::check('product_filter');
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
            
            if(!isset($request_data['id']) || !is_numeric($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            // 调用底层服务
            $alias_filter_service = Alias_filterService::get_instance();
            $alias_filter = $alias_filter_service->get($request_data['id']);
            
            //数据验证
            $validResult = Validation::factory($request_data)->add_rules('title', 'required', 'length[1,100]')->add_rules('uri_name', 'length[0,100]');
            if($validResult->validate() == FALSE){
                //* 输出错误的具体信息 ==根据业务逻辑定制== */
                $return_struct['content']['errors'] = $validResult->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            //title重复判断
            if($request_data['title'] != $alias_filter['title'] && $alias_filter_service->check_exist_title($request_data['title'])){
                throw new MyRuntimeException(Kohana::lang('o_product.category_title_has_exists'), 409);
            }
            //uri_name重复判断
            if($request_data['uri_name'] != $alias_filter['uri_name'] && $alias_filter_service->check_exist_uri_name($request_data['uri_name'])){
                throw new MyRuntimeException(Kohana::lang('o_product.uri_name_has_exists'), 409);
            }
            
            //获取过滤结构
            $filter = array ();
            $filter['keywords'] = html::specialchars($request_data['keywords']);
            if(isset($request_data['pricefrom']) && preg_match('/^(0\.\d+|[1-9]\d*(\.\d+)?)$/', $request_data['pricefrom'])){
                $filter['pricefrom'] = $request_data['pricefrom'];
            }
            if(isset($request_data['priceto']) && preg_match('/^(0\.\d+|[1-9]\d*(\.\d+)?)$/', $request_data['priceto'])){
                $filter['priceto'] = $request_data['priceto'];
            }
            if(isset($request_data['brand']) && !empty($request_data['brand'])){
                $filter['brands'] = $request_data['brand'];
            }
            if(isset($request_data['attribute']) && !empty($request_data['attribute'])){
                $filter['attributes'] = $request_data['attribute'];
            }
            if(isset($request_data['feature']) && !empty($request_data['feature'])){
                $filter['features'] = $request_data['feature'];
            }
            //执行修改
            $set_data = array ();
            $set_data['id'] = $request_data['id'];
            $set_data['pid'] = $request_data['pid'];
            if($request_data['oldpid'] != $request_data['pid']){
                if(!empty($request_data['pid'])){
                    $parents = $alias_filter_service->get_parents_by_filter_id($request_data['pid']);
                    $parent_ids = array ();
                    foreach($parents as $val){
                        $parent_ids[] = $val['id'];
                    }
                    if(in_array($request_data['id'], $parent_ids)){
                        throw new MyRuntimeException('设置父分类错误', 400);
                    }
                    $set_data['level_depth'] = count($parent_ids) + 1;
                }else{
                    $set_data['level_depth'] = 1;
                }
            }
            $set_data['category_id'] = $request_data['category_id'];
            //$set_data['classify_id'] = $request_data['classify_id'];
            $set_data['uri_name'] = $request_data['uri_name'];
            $set_data['title'] = html::specialchars($request_data['title']);
            $set_data['filter_struct'] = json_encode($filter);
            $set_data['update_timestamp'] = time();
            $alias_filter_service->set($set_data['id'], $set_data);
            
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
        role::check('product_filter');
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
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException('Not Implemented',501);
            
            //数据验证
            $alias_filter_service = Alias_filterService::get_instance();
            if(!isset($request_data['id']) || !is_numeric($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            //$filter = $alias_filter_service->get($request_data['id']);
            
            //执行删除
            $alias_filter_service->delete_filter_by_filter_id($request_data['id']);
            
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
        role::check('product_filter');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
             
            if(!isset($request_data['id']) || empty($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            $alias_filter_service = Alias_filterService::get_instance();
            //执行删除
            if(isset($request_data['id']) || !empty($request_data['id'])){
                foreach($request_data['id'] as $val){
                    $alias_filter_service->delete_filter_by_filter_id($val);
                }
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
    
    function get_category_data()
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
                'classify' => array (), 
                'brand_list' => array (), 
                'attribute_list' => array (), 
                'feature_list' => array () 
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();            

            //必须为ajax请求
            if(!$this->is_ajax_request()){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            //数据验证
            if(!isset($request_data['category_id']) || !is_numeric($request_data['category_id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            if($request_data['category_id'] == 0){
                //当前站点品牌
                $return_data['brand_list'] = BrandService::get_instance()->get_brands();
                //当前站点规格
                $return_data['attribute_list'] = AttributeService::get_instance()->get_attribute_spec_options();
                //当前站点品牌
                $return_data['feature_list'] = AttributeService::get_instance()->get_attribute_feature_options();
            }else{
                // 调用底层服务
                $category_service = CategoryService::get_instance();
                $classify_service = ClassifyService::get_instance();
                
                //获取数据
                $category = $category_service->get($request_data['category_id']);
                if($category['classify_id']){
                    $return_data['classify'] = $classify_service->get($category['classify_id']);
                    //获取关联品牌数组
                    $return_data['brand_list'] = $classify_service->get_brands_by_classify_id($category['classify_id']);
                    //获取关联规格及规格项数组
                    $return_data['attribute_list'] = $classify_service->get_attribute_options_by_classify_id($category['classify_id'], AttributeService::ATTRIBUTE_SPEC);
                    //获取关联特性及特性值数组
                    $return_data['feature_list'] = $classify_service->get_attribute_options_by_classify_id($category['classify_id'], AttributeService::ATTRIBUTE_FEATURE);
                }
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
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
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
    
    public function check_exist_uri_name()
    {
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

            //必须为ajax请求
            if(!$this->is_ajax_request()){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
             
            //数据验证
            if(!isset($request_data['uri_name']) || empty($request_data['uri_name'])){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            $filter_service = Alias_filterService::get_instance();
            if(isset($request_data['filter_id']) && is_numeric($request_data['filter_id'])){
                $filter = $filter_service->get($request_data['filter_id']);

                if($filter['uri_name'] == $request_data['uri_name'])
                    exit(Kohana::lang('o_global.true'));
            }
            
            // 调用底层服务
            if($filter_service->check_exist_uri_name($request_data['uri_name'])){
                exit('false');
            }else{
                exit('true');
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
    
    /**
     * 设定菜单的排序
     */
   public function set_order()
    {
        //初始化返回数组
        $return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       $request_data = $this->input->get();
       $id = isset($request_data['id']) ?  $request_data['id'] : '';
       $order = isset($request_data['order']) ?  $request_data['order'] : '';
       /* 验证是否可以操作 */
       if(!role::verify('product_filter',site::id(),0))
       {
           $return_struct['msg'] = Kohana::lang('o_global.permission_enough');
           exit(json_encode($return_struct));
       }
       if(empty($id) || (empty($order) && $order != 0))
       {
           $return_struct['msg'] = Kohana::lang('o_global.bad_request');
           exit(json_encode($return_struct));
       }
       if(!is_numeric($order) || $order<0){
           $return_struct['msg'] = Kohana::lang('o_global.position_rule');
           exit(json_encode($return_struct));
       }
       $aliasfilter_service = Alias_filterService::get_instance();
       $aliasfilter_service->set($id,array('order'=>$order));
        $return_struct = array(
            'status'        => 1,
            'code'          => 200,
            'msg'           => Kohana::lang('o_global.position_success'),
            'content'       => array('order'=>$order),
        );
       exit(json_encode($return_struct));
    }
}
?>
