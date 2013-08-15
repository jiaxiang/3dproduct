<?php
defined('SYSPATH') or die('No direct access allowed.');
/**
 * @author FanChongyuan
 * @time 2010-5-17 上午11:04:12
 */
class Category_Controller extends Template_Controller {
    // Disable this controller when Kohana is set to production mode.
    const ALLOW_PRODUCTION = TRUE;
    
    private $class_name = '';
    private $package = '';
    private $img_dir_name = 'category';
    // Set the name of the template to use
    public $template_ = 'layout/common_html';
    
    /**
     * 构造方法
     */
    public function __construct(){
        //$profiler = new Profiler;
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
    public function index(){
        role::check('product_category');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //* 初始化返回数据 */
            $return_data = array (
                'list' => array (), 
                'count' => 0 
            );
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
                'limit' => array () 
            );
            //* 初始化当前查询结构体 */
            $query_struct_current = array ();
            //* 设置合并默认查询条件到当前查询结构体 */
            $query_struct_current = array_merge($query_struct_current, $query_struct_default);
            
            //列表排序
            $orderby_arr = array (
                0 => array (
                    'id' => 'ASC' 
                ), 
                1 => array (
                    'id' => 'DESC' 
                ), 
                2 => array (
                    'title_manage' => 'ASC' 
                ), 
                3 => array (
                    'title_manage' => 'DESC' 
                ), 
                4 => array (
                    'title' => 'ASC' 
                ), 
                5 => array (
                    'title' => 'DESC' 
                ), 
                6 => array (
                    'classify_id' => 'ASC' 
                ), 
                7 => array (
                    'classify_id' => 'DESC' 
                ), 
                8 => array (
                    'position' =>  'ASC'
                ), 
                9 => array (
                    'position' => 'DESC' 
                ), 
                10 => array (
                    'update_timestamp' => 'ASC' 
                ), 
                11 => array (
                    'update_timestamp' => 'DESC' 
                ),
                12 => array (
                    'is_show' => 'ASC' 
                ), 
                13 => array (
                    'is_show' => 'DESC' 
                )  
            );
            $orderby = controller_tool::orderby($orderby_arr);
            
            // 排序处理 
            if(isset($request_data['orderby']) && is_numeric($request_data['orderby'])){
                $query_struct_current['orderby'] = $orderby;
            }
            
            //调用服务执行查询
            $result = array ();
            $category_service = CategoryService::get_instance();
            $result = $category_service->index($query_struct_current);
            $classfy = ClassifyService::get_instance()->get_classifies();
            if(!empty($result)){
                foreach($result as $key => $val){
                    if($val['classify_id'] && array_key_exists($val['classify_id'], $classfy)){
                        $val['classify_name'] = $classfy[$val['classify_id']]['name'];
                    }else{
                        $val['classify_name'] = '';
                    }
                    $val['create_timestamp'] = date('Y-m-d H:i:s', $val['create_timestamp']);
                    $val['update_timestamp'] = date('Y-m-d H:i:s', $val['update_timestamp']);
                    $return_data['list'][$val['id']] = $val;
                }
            }
            $return_data['count'] = $category_service->count($query_struct_current);
            
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
                $this->template->title = '分类管理';
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;            

            } // end of request type determine

        }catch(MyRuntimeException $ex){
            $this->_ex(&$ex, $return_struct, $request_data);
        }
    }
    
    /**
     * 添加数据页面
     */
    public function add()
    {
        role::check('product_category_add');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //* 初始化返回数据 */
            $return_data = array (
                'action'=>'put',
                'data'=> array(),
                'site_list' => NULL, 
                'category_list' => NULL, 
                'classify_list' => NULL 
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            // 调用底层服务
            $category_service = CategoryService::get_instance();
            
            //商品的分类
            $query_struct = array('where'=>array());
            $list = $category_service->query_assoc($query_struct);
            $cate_list = array();
	        if(!empty($list)){
	            foreach($list as $val){
	                $cate_list[$val['id']] = $val;
	            }
	        }
	        $return_data['category_list'] = tree::get_tree($cate_list,'<option value={$id} {$selected}>{$spacer}{$title}</option>');
            
            //商品的类型
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
            $this->_ex(&$ex, $return_struct, $request_data);
        }
    }
    
    public function put(){
        role::check('product_category_add');
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
            //echo "<pre>";print_r($request_data);die();
            //标签过滤
            tool::filter_strip_tags($request_data, array('description'));
 
            //数据验证
            $validResult = Validation::factory($request_data)->pre_filter('trim')
                ->add_rules('pid', 'required', 'numeric')
                ->add_rules('title', 'required', 'length[1,100]')
                ->add_rules('title_manage', 'length[1,255]')
                ->add_rules('uri_name', 'length[1,255]')
                ->add_rules('description', 'length[0,1024]')
                ->add_rules('memo', 'length[0,65535]')
                ->add_rules('meta_title', 'length[0,255]')
                ->add_rules('meta_keywords', 'length[0,255]')
                ->add_rules('meta_description', 'length[0,65535]')
                ->add_rules('is_show', 'numeric');
            
            if($validResult->validate() == FALSE){
                //* 输出错误的具体信息 ==根据业务逻辑定制== */
                $return_struct['content']['errors'] = $validResult->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            
            // 调用底层服务
            $category_service = CategoryService::get_instance();
            //判断title是否存在
            if($category_service->check_exist_title($request_data['title'])){
                throw new MyRuntimeException(Kohana::lang('o_product.category_title_has_exists'), 409);
            }
            
            //生成uri_name
            $request_data['uri_name'] = tool::create_uri_name($request_data['uri_name']);
            //uri_name重复判断
            if(!empty($request_data['uri_name'])){
                while($category_service->check_exist_uri_name($request_data['uri_name'])){
                    $request_data['uri_name'] .= '-' . substr(uniqid(), -6);
                }
            }
            
            //执行添加
            $set_data = $request_data;
            $set_data['is_show'] = $request_data['is_show'];
            $set_data['pid'] = $request_data['pid'];
            $set_data['classify_id'] = $request_data['classify_id'];
            $set_data['title'] = $request_data['title'];
            $set_data['title_manage'] = $request_data['title_manage'];
            $set_data['pic_attach_id'] = $request_data['pic_attach_id'];
            $set_data['meta_title'] = $request_data['meta_title'];
            $set_data['meta_keywords'] = $request_data['meta_keywords'];
            $set_data['meta_description'] = $request_data['meta_description'];
            $set_data['description'] = $request_data['description'];
            $set_data['memo'] = $request_data['memo'];
            $set_data['create_timestamp'] = time();
            $set_data['update_timestamp'] = time();
            $return_data['id'] = $category_service->add($set_data);
            
            if($set_data['pid']){
                $category_service->update_categories();
            }
            if(!$return_data['id']){
                throw new MyRuntimeException('Internal Error', 500);
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
            $this->_ex(&$ex, $return_struct, $request_data);
        }
    }
    
    public function edit()
    {
        role::check('product_category_edit');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //* 初始化返回数据 */
            $return_data = array ('action'=>'post');
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //数据验证
            if(!isset($request_data['id']) || !is_numeric($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            
            $category_service = CategoryService::get_instance();
            $category = $category_service->get($request_data['id']);
            
            //返回数据
            //$category['pic_url'] = AttributeService::get_attach_url($category['pic_attach_id'], 't');
            $category['pic_url'] = AttService::get_instance($this->img_dir_name)->get_img_url($category['pic_attach_id']);
            $return_data['data'] = $category;
            
            //uri_name处理
            $route = Myroute::instance()->get();
            $route_type = $route['type'];
            $category_route = $route['category'];
            $category_suffix = $route['category_suffix'];
            if($route_type == 0){
                // 0: none  get category and product with id
                $category_permalink = $category_route . '/' . $category['id'];
            }
            else if($route_type == 1){
                // 1: get  product with {product}/permalink
                $category_permalink = $category_route . '/';
            }
            else if($route_type == 2 || $route_type == 4){
                // 2: get category and product with {category_permalink}  and {category+permalink}/{product_permalink}
                $category_permalink = '';
            }
            else if($route_type == 3){
                // 3: get category and prdouct with {category_permalink1}/.../{category_permalinkn} and {category_permalink1}/.../{category_permalinkn}/{product_permalink}
                $parents = $category_service->get_parents_by_category_id($category['id']);
                $category_permalink = '';
                $i = 1;
                foreach($parents as $val){
                    if($i != 1){
                        $category_permalink = urlencode($val['uri_name']) . '/' . $category_permalink;
                    }
                    $i++;
                }
            }
            
            //当前站点分类
            $categories = $category_service->get_categories();
            $child_ids = $category_service->get_childrens_by_category_id($category['id']);
            $parent_data = $category_service->get_parents_by_category_id($category['id']);
            $parent_is_show = isset($parent_data[1])?$parent_data[1]['is_show']:1;
            //去掉子分类和自己
            foreach($categories as $key => $val){
                if(in_array($val['id'], $child_ids) || $val['id'] == $category['id'])
                    unset($categories[$key]);
            }
            
            $str = '<option value={$id} {$selected}>{$spacer}{$title}</option>';
            //$return_data['category_list'] = $category_service->get_tree_by_site_id($request_data['site_id'], '<option value={$id} {$selected}>{$spacer}{$title}</option>',$category['pid']);
            $return_data['category_list'] = tree::get_tree($categories, $str, 0, $category['pid']);
            //当前站点类型
            $classify = ClassifyService::get_instance()->get_classifies();
            
            $return_data['classify_list'] = '';
            foreach($classify as $val){
                $selected = $category['classify_id'] == $val['id'] ? 'selected' : '';
                $return_data['classify_list'] .= '<option value=' . $val['id'] . ' ' . $selected . '>--' . $val['name'] . '--</option>';
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
                $this->template->content->has_child = count($child_ids);   
                $this->template->content->parent_is_show = $parent_is_show;              
                $this->template->content->route_type = $route_type;
                $this->template->content->category_suffix = $category_suffix;
                $this->template->content->category_permalink = $category_permalink;
            } // end of request type determine

        }catch(MyRuntimeException $ex){
            $this->_ex(&$ex, $return_struct, $request_data);
        }
    }

    public function post()
    {
        role::check('product_category_edit');
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
            tool::filter_strip_tags($request_data, array('description'));
            
            //数据验证
            $validResult = Validation::factory($request_data)->pre_filter('trim')
                ->add_rules('pid', 'required', 'numeric')
                ->add_rules('title', 'required', 'length[1,100]')
                ->add_rules('title_manage', 'length[1,255]')
                ->add_rules('uri_name', 'required', 'length[1,255]')
                ->add_rules('description', 'length[0,1024]')
                ->add_rules('memo', 'length[0,65535]')
                ->add_rules('meta_title', 'length[0,255]')
                ->add_rules('meta_keywords', 'length[0,255]')
                ->add_rules('meta_description', 'length[0,65535]')->add_rules('is_show', 'numeric');
            if($validResult->validate() == FALSE){
                //* 输出错误的具体信息 ==根据业务逻辑定制== */
                $return_struct['content']['errors'] = $validResult->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            
            // 调用底层服务
            $category_service = CategoryService::get_instance();
            /*获取分类数据*/
            $category = $category_service->get($request_data['id']);
            
            //判断title是否存在
            if($request_data['title'] != $category['title'] && $category_service->check_exist_title($request_data['title'])){
                throw new MyRuntimeException(Kohana::lang('o_product.category_title_manage_has_exists'), 409);
            }
            
            //uri_name重复判断
            if(strtolower($request_data['uri_name']) != strtolower($category['uri_name']) && $category_service->check_exist_uri_name($request_data['uri_name'])){
                throw new MyRuntimeException(Kohana::lang('o_product.uri_name_has_exists'), 409);
            }
            
            //执行修改
            if($request_data['oldpid'] != $request_data['pid']){
                $child_ids = $category_service->get_childrens_by_category_id($request_data['id']);
                if(in_array($request_data['pid'], $child_ids) || $request_data['pid'] == $request_data['id']){
                    throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
                }
            }
            //if(!empty($category['pic_attach_id']) && $request_data['pic_attach_id'] != $category['pic_attach_id']){
              //  AttributeService::get_instance()->delete_attachment($category['pic_attach_id']);
            //}
            $set_data = $request_data;
            $set_data['update_timestamp'] = time();
            $category_service->set($set_data['id'], $set_data);
            if($request_data['oldpid'] != $request_data['pid']){
                $category_service->update_categories();
            }
            //if($request_data['is_show'] == 0){
                $category_service->update_show_val_by_id($category['id'], $request_data['is_show']);
            //}
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
            $this->_ex(&$ex, $return_struct, $request_data);
        }
    }
        
    public function delete()
    {
        role::check('product_category_delete');
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
            
            //数据验证
            if(!isset($request_data['id']) || !is_numeric($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            
            //$category_service = CategoryService::get_instance();
            //$category = $category_service->get($request_data['id']);
            
            //执行删除
            CategoryService::get_instance()->delete_category_by_category_id($request_data['id']);
            
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
        role::check('product_category_delete');
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
            $request_data = $this->input->post();
            
            if(!isset($request_data['id']) || empty($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            
            $category_service = CategoryService::get_instance();
            
            //执行删除
            $category_service->delete_categorys((array)$request_data['id']);
            
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
            $this->_ex(&$ex, $return_struct, $request_data);
        }
    }
    
    public function edit_pos()
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
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException('Not Implemented',501);
            //* 权限验证 */
            $site_id = site::id();
            if($site_id == 0){
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            role::check('product_category', $site_id, 0);
            
            //数据验证
            $category_service = CategoryService::get_instance();
            $validResult = Validation::factory($request_data)->pre_filter('trim')->add_rules('id', 'required', 'numeric')->add_rules('position', 'required', 'numeric');
            if($validResult->validate() == FALSE){
                //* 输出错误的具体信息 ==根据业务逻辑定制== */
                $return_struct['content']['errors'] = $validResult->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            $category = $category_service->get($request_data['id']);
            if($site_id != $category['site_id']){
                throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 404);
            }
            
            //执行修改
            $category_service->edit_position($request_data['id'], $request_data['position']);
            
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
    
    public function position($type)
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
                'categorys' => array () 
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException('Not Implemented',501);
            //路径验证
            if(!in_array($type, array (
                'up', 
                'down' 
            ))){
                throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 404);
            }
            //* 权限验证 */
            $site_id = site::id();
            if($site_id == 0){
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            
            //数据验证
            $category_service = CategoryService::get_instance();
            if(!isset($request_data['id']) || !is_numeric($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            
            $category = $category_service->get($request_data['id']);
            if($site_id != $category['site_id']){
                throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 404);
            }
            
            //修改位置
            $return_data['categorys'] = $category_service->position_by_category_id($request_data['id'], $type);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '修改成功';
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
    
    /**
     * 设定菜单的排序
     */
    public function set_order()
    {
        //初始化返回数组
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        $request_data = $this->input->get();
        $id = isset($request_data['id']) ? $request_data['id'] : '';
        $order = isset($request_data['order']) ? $request_data['order'] : '';
        /* 验证是否可以操作 */
        if(!role::verify('product_category', site::id(), 0)){
            $return_struct['msg'] = Kohana::lang('o_global.permission_enough');
            exit(json_encode($return_struct));
        }
        if(empty($id) || (empty($order) && $order != 0)){
            $return_struct['msg'] = Kohana::lang('o_global.bad_request');
            exit(json_encode($return_struct));
        }
        if(!is_numeric($order) || $order < 0){
            $return_struct['msg'] = Kohana::lang('o_global.position_rule');
            exit(json_encode($return_struct));
        }
        if(CategoryService::get_instance()->set_order($id, $order)){
            $return_struct = array (
                'status' => 1, 
                'code' => 200, 
                'msg' => Kohana::lang('o_global.position_success'), 
                'content' => array (
                    'order' => $order 
                ) 
            );
        }else{
            $return_struct['msg'] = Kohana::lang('o_global.position_error');
        }
        exit(json_encode($return_struct));
    }
    
    function get_category_data()
    {
        role::check('product_category_edit');
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
            

            //必须为ajax请求
            if(!$this->is_ajax_request()){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            //数据验证
            if(!isset($request_data['category_id']) || !is_numeric($request_data['category_id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            // 调用底层服务
            $category_service = CategoryService::get_instance();
            //获取分类数据
            $category = $category_service->get($request_data['category_id']);

            $child_ids = $category_service->get_childrens_by_category_id($category['id']);
            
            //获取此分类类型
            $return_data['classify_id'] = $category['classify_id'];
            $return_data['is_show']  = $category['is_show'];
            
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
            $this->_ex($ex, $return_struct, $request_data);
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
            //$profiler = new Profiler;
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            //* 实现功能后屏蔽此异常抛出 */
            //throw new MyRuntimeException('Not Implemented',501);
            

            //必须为ajax请求
            if(!$this->is_ajax_request()){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            //* 权限验证 */
            $site_id = site::id();
            if($site_id == 0){
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            
            if(!isset($request_data['uri_name']) || empty($request_data['uri_name'])){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            
            $category_service = CategoryService::get_instance();
            if(isset($request_data['category_id']) && is_numeric($request_data['category_id'])){
                $category = $category_service->get($request_data['category_id']);
                //判断站点
                if($site_id != $category['site_id']){
                    throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 404);
                }
                if(strtolower($category['uri_name']) == strtolower($request_data['uri_name']))
                    exit(Kohana::lang('o_global.true'));
            }
            
            // 调用底层服务
            if($category_service->check_exist_uri_name($site_id, $request_data['uri_name'])){
                exit(Kohana::lang('o_global.false'));
            }else{
                exit(Kohana::lang('o_global.true'));
            }
        
        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function uploadform()
    {
        role::check('product_category');
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
            
            //* 逻辑验证 ==根据业务逻辑定制== */
            $picture_max_size = 0;
            $picture_types = array ();
            $picture_attach = Kohana::config('attach.productPicAttach');
            $picture_max_size = $picture_attach['fileSizePreLimit'] / 1024 / 1024;
            if(!preg_match('/^\d+$/', $picture_max_size)){
                $picture_max_size = number_format($picture_max_size, 2);
            }
            $picture_types = $picture_attach['allowTypes'];
            
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
                $this->template = new View('layout/commonblank_html');
                $this->template->return_struct = $return_struct;
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
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
                $this->template->content->picture_types = $picture_types;
            } // end of request type determine
        

        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
        }
    }
    
    public function upload()
    {
        role::check('product_category');
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
            
            // 上传的表单域名字
            $attach_field = 'category_img';
            // 附件应用类型
            $attach_app_type = 'productPicAttach';
            // 如果有上传请求
            if(!page::issetFile($attach_field)){
                throw new MyRuntimeException('请选择所要上传的图片', 400);
            }
            
            //读取当前应用配置
            $attach_setup = Kohana::config('attach.' . $attach_app_type);
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
            $file_meta_data = array ();
            // 遍历所有的上传域 //验证上传/采集上传信息
            for($index = 0;$index < $file_upload_count;$index++){
                // 如果上传标志成功
                if(( int ) $_FILES[$attach_field]['error'][$index] === UPLOAD_ERR_OK){
                    if(!is_uploaded_file($_FILES[$attach_field]['tmp_name'][$index])){
                        throw new MyRuntimeException(Kohana::lang('o_product.file_not_uploaded') . $index, 400);
                    }
                    $file_size_current = filesize($_FILES[$attach_field]['tmp_name'][$index]);
                    if($attach_setup['fileSizePreLimit'] > 0 && $file_size_current > $attach_setup['fileSizePreLimit']){
                        throw new MyRuntimeException(Kohana::lang('o_product.file_size_prelimit') . $attach_setup['fileSizePreLimit'] . Kohana::lang('o_product.index') . $index . Kohana::lang('o_product.size') . $file_size_current, 400);
                    }
                    
                    $file_type_current = FALSE;
                    $file_type_current === FALSE && page::getImageType($_FILES[$attach_field]['tmp_name'][$index]); // 尝试通过图片类型判断
                    $file_type_current === FALSE && $file_type_current = page::getFileType($attach_field, $index); // 尝试通过Mime类型判断
                    $file_type_current === FALSE && $file_type_current = page::getPostfix($attach_field, $index); // 尝试通过后缀截取
                    if(!empty($attachSetup['allowTypes']) && !in_array($file_type_current, $attach_setup['allowTypes'])){
                        throw new MyRuntimeException(Kohana::lang('o_product.file_type_invalid') . $index, 400);
                    }
                    // 当前文件mime类型
                    $file_mime_current = isset($_FILES[$attach_field]['type'][$index]) ? $_FILES[$attach_field]['type'][$index] : '';
                    // 检测规整mime类型
                    if(!array_key_exists($file_mime_current, $mime_type2postfix)){
                        if(array_key_exists($file_type_current, $mime_postfix2type)){
                            $file_mime_current = $mime_postfix2type[$file_type_current];
                        }else{
                            $file_mime_current = 'application/octet-stream';
                        }
                    }
                    
                    //存储文件meta信息
                    $file_meta_data[$index] = array (
                        'name' => strip_tags(trim($_FILES[$attach_field]['name'][$index])), 
                        'size' => $file_size_current, 
                        'type' => $file_type_current, 
                        'mime' => $file_mime_current, 
                        'tmpfile' => $_FILES[$attach_field]['tmp_name'][$index] 
                    );
                    // 设置上传总数量
                    $file_count_total += 1;
                    // 设置上传总大小
                    $file_size_total += $file_size_current;
                }
            }
            if($attach_setup['fileCountLimit'] > 0 && $file_count_total > $attach_setup['fileCountLimit']){
                throw new MyRuntimeException(Kohana::lang('o_product.file_count_limit') . $attach_setup['fileCountLimit'], 400);
            }
            if($attach_setup['fileSizeTotalLimit'] > 0 && $file_size_total > $attach_setup['fileSizeTotalLimit']){
                throw new MyRuntimeException(Kohana::lang('o_product.file_size_total_limit') . $attach_setup['fileSizeTotalLimit'] . Kohana::lang('o_product.size') . $file_size_total, 400);
            }

            // 当前时间戳
            //$timestamp_current = time();
            //预备一些数据
            //$src_ip_address = $this->input->ip_address();
            
            //$attach_meta = array ();
            
            // 调用附件服务
            //$attachmentService = AttachmentService::get_instance();
            //require_once (Kohana::find_file('vendor', 'phprpc/phprpc_client', TRUE));
            //!isset($attachmentService) && $attachmentService = new PHPRPC_Client(Kohana::config('phprpc.remote.Attachment.host'));
            //!isset($phprpcApiKey) && $phprpcApiKey = Kohana::config('phprpc.remote.Attachment.apiKey');
            
                /*$attachment_data_original = array (
                    //                    'site_id'=>$site_id,
                    'filePostfix' => $file_meta['type'], 
                    'fileMimeType' => $file_meta['mime'], 
                    'fileSize' => $file_meta['size'], 
                    'fileName' => $file_meta['name'], 
                    'srcIp' => $src_ip_address, 
                    'attachMeta' => json_encode($attach_meta), 
                    'createTimestamp' => $timestamp_current, 
                    'modifyTimestamp' => $timestamp_current 
                );
                // 调用后端添加附件信息，并调用存储服务存储文件
                $args_org = array (
                    $attachment_data_original 
                );
                $sign_org = md5(json_encode($args_org) . $phprpcApiKey);
                $attachment_original_id = $attachmentService->phprpc_addAttachmentFileData($attachment_data_original, @file_get_contents($file_meta['tmpfile']), $sign_org);
            	*/
                $AttService = AttService::get_instance($this->img_dir_name);
                $file_meta = $file_meta_data[0];
                $img_id = $AttService->save_default_img($file_meta['tmpfile']);
                if(!$img_id){
                	throw new MyRuntimeException(Kohana::lang('o_product.phprpc_pic_save_failed'), 400);
                }
                $return_data['pic_attach_id'] = $img_id;
                $return_data['pic_url'] = $AttService->get_img_url($img_id);
                // 清理临时文件
                @unlink($file_meta['tmpfile']);
                
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '上传成功!';
            $return_struct['content'] = $return_data;
            
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
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                //$this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
            } // end of request type determine
        

        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data, 'info2', 'layout/default_html');
        }
    }
    
}
