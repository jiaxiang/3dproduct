<?php
defined('SYSPATH') or die('No direct access allowed.');
class Feature_Controller extends Template_Controller {
    // Disable this controller when Kohana is set to production mode.
    const ALLOW_PRODUCTION = TRUE;
    private $package_name = 'product';
    private $class_name = '';
    // Set the name of the template to use
    public $template_ = 'layout/common_html';
    const ATTRIBUTE_TYPE =  AttributeService::ATTRIBUTE_FEATURE;
    
    /**
     * 构造方法
     */
    public function __construct()
    {
        parent::__construct();
        $this->class_name = strtolower(substr(__CLASS__, 0, strpos(__CLASS__, '_')));
        if($this->is_ajax_request()){
            $this->template = new View('layout/default_json');
        }
    }
    
    
    /**
     * 数据列表
     */
    public function index()
    {
        role::check('product_feature');
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
                'where' => array (                    
                    'apply' => self::ATTRIBUTE_TYPE
                ), 
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
                    'id' => 'ASC' 
                ),
                2 => array (
                    'name' => 'ASC' 
                ), 
                3 => array (
                    'name' => 'DESC' 
                ),
                4 => array (
                    'order' => 'ASC' 
                ), 
                5 => array (
                    'order' => 'DESC' 
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
            $attribute_service = AttributeService::get_instance();
            $count = $attribute_service->count($query_struct_current);
            // 模板输出 分页
            $this->pagination = new Pagination(array (
                'total_items' => $count, 
                'items_per_page' => $query_struct_current['limit']['per_page'] 
            ));
            $query_struct_current['limit']['page'] = $this->pagination->current_page;
            
            $return_data['list'] = $attribute_service->get_attribute_options($query_struct_current);
            //echo "<pre>";print_r($return_data['list']);die();

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
                $this->template->title = '特性管理';
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                
            } // end of request type determine
        }catch(MyRuntimeException $ex){
             $this->_ex($ex, $request_data, $return_struct);
        }
    }
    
    /**
     * 添加数据页面
     */
    public function add()
    {
        role::check('product_feature_add');
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
                'data' => array ()  
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
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
            $this->_ex($ex, $request_data, $return_struct);
        }
    }
    
    public function edit()
    {
        role::check('product_feature_edit');
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
                'data' => array (), 
                'site' => NULL 
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            //数据验证
            if(!isset($request_data['id']) || !is_numeric($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            $attribute_service = AttributeService::get_instance();
            $attribute = $attribute_service->get_attribute_options(array( 
                    'where' => array ( 
                        'id' => $request_data['id']  
                    )));
            $attribute = $attribute[$request_data['id']];
            
            //属性值处理数据
            $options = $attribute['options'];        
            foreach($options as $key => $option){
                if(isset($options[$key]['image']) && !empty($options[$key]['image'])){
                    $img = explode('|', $options[$key]['image']);
                    $options[$key]['picurl'] = $img[2];
                    //if($options[$key]['picurl'] == 'default') $options[$key]['picurl'] = '/att/';
                }else{
                    $options[$key]['image'] = '';
                    $options[$key]['picurl'] = '/att/';
                }
            }
            $attribute['options'] = $options;
            $return_data['data'] = $attribute;
            
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
                $content = new View('product/' . $this->class_name . '/add');
                
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                
            } // end of request type determine
        

        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $request_data, $return_struct);
        }
    }
    
    public function put()
    {
        $request_data = $this->input->post();
        
        //权限检查
        if(isset($request_data['id'])){
            role::check('product_feature_edit');
        }else{
            role::check('product_feature_add');
        }
        
        //安全过滤
        $request_data = trims::run($request_data);
        tool::filter_strip_tags($request_data);
        $request_data['type'] = isset($request_data['type'])?$request_data['type']:0;
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //初始化返回数据
            $return_data = array ();
            $validation = Validation::factory($request_data);
            $validResult = $validation->pre_filter('trim')->add_rules('name', 'required', 'length[1,100]');
            if($validResult->validate() == FALSE){
                $return_struct['content']['errors'] = $validResult->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            
            // 调用底层服务
            $attribute_service = AttributeService::get_instance();
            
            //是输入项时，要先删除旧的选项值
            if(isset($request_data['type']) && $request_data['type']==1)
            {
                $request_data['option_name']='';
                $request_data['id']>0 && $attribute_service->clear_attribute_value($request_data['id']);
            }
            else
            {
                //数据验证
                if(!isset($request_data['option_name']) || empty($request_data['option_name'])){
                    throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
                }
            }
            
            //执行添加
            $set_data = array ();
            $set_data['apply'] = self::ATTRIBUTE_TYPE;
            $set_data['name'] = html::specialchars($request_data['name']);
            $set_data['alias'] = html::specialchars($request_data['alias']);
            $set_data['memo'] = html::specialchars($request_data['memo']);
            $set_data['type'] = $request_data['type'];
            
            if($request_data['id']){
                $return_data['id'] = $set_data['id'] = $request_data['id'];
                $attribute_service->update($set_data);
            }else{
                $return_data['id'] = $attribute_service->add($set_data);
            }
            
            if(!$return_data['id']){
                throw new MyRuntimeException('Internal Error', 500);
            }
            
            //option
            if($request_data['type']==0 && isset($request_data['option_name']) && !empty($request_data['option_name'])){
                foreach($request_data['option_name'] as $key => $val){
                    $att_val = array();
                    $att_val['attribute_id'] = $return_data['id'];
                    $att_val['name'] = html::specialchars($val);
                    $att_val['alias'] = isset($request_data['option_alias'][$key])?html::specialchars($request_data['option_alias'][$key]):'';
                    $att_val['order'] = (int)$request_data['option_order'][$key];
                    
                    if(isset($request_data['option_image'][$key]) && !empty($request_data['option_image'][$key])){
                        $att_val['image'] = $request_data['option_image'][$key];
                    }
                    if(isset($request_data['option_id_old'][$key]) && !empty($request_data['option_id_old'][$key])){
                        $att_val['id'] = $request_data['option_id_old'][$key];
                    }
                    
                    $flag = $attribute_service->save_attribute_value($att_val);
                    if(!$flag){
                        throw new MyRuntimeException('Internal Error', 500);
                    }
                }
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '保存成功';
            $return_struct['content'] = $return_data;
            $return_struct['action'] = array (
                'type' => 'location', 
                'url' => url::base() . 'product/' . $this->class_name . '/index'
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
            $this->_ex($ex, $request_data, $return_struct);
        }
    }
    
    public function delete()
    {
        role::check('product_feature_delete');
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
            
            //执行删除
            AttributeService::get_instance()->delete_by_attribute_id($request_data['id']);
            
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
            $this->_ex($ex, $request_data, $return_struct);
        }
    }
    
    public function delete_all()
    {
        role::check('product_feature_delete');
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
            
            //数据验证
            if(!isset($request_data['id']) || empty($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            
            $attribute_service = AttributeService::get_instance();
            //执行删除
            if(!empty($request_data['id'])){
                $attribute_service->delete_attributes($request_data['id']);
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
            $this->_ex($ex, $request_data, $return_struct);
        }
    }
    
    public function option_relation_data()
    {
        role::check('product_feature_delete');
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

            //必须为ajax请求
            if(!$this->is_ajax_request()){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            //数据验证
            if(!isset($request_data['option_id']) || !is_numeric($request_data['option_id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            
            // 调用底层服务
            $attributeoption_service = AttributeoptionService::get_instance();
            if($attributeoption_service->is_relation_by_attributeoption_id($request_data['option_id'])){
                throw new MyRuntimeException('该特性已被关联，请取消关联之后重试！', 500);
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
            $this->_ex($ex, $request_data, $return_struct);
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
       /* 验证是否可以操作 */
       if(!role::verify('product_feature'))
       {
           $return_struct['msg'] = Kohana::lang('o_global.permission_enough');
           exit(json_encode($return_struct));
       }
        
       $request_data = $this->input->get();
       $id = isset($request_data['id']) ?  $request_data['id'] : '';
       $order = isset($request_data['order']) ?  $request_data['order'] : '';

       if(empty($id) || (empty($order) && $order != 0))
       {
           $return_struct['msg'] = Kohana::lang('o_global.bad_request');
           exit(json_encode($return_struct));
       }
       if(!is_numeric($order) || $order<0){
           $return_struct['msg'] = Kohana::lang('o_global.position_rule');
           exit(json_encode($return_struct));
       }
       $attribute_service = AttributeService::get_instance();
       $attribute_service->set($id,array('order'=>$order));
        $return_struct = array(
            'status'        => 1,
            'code'          => 200,
            'msg'           => Kohana::lang('o_global.position_success'),
            'content'       => array('order'=>$order),
        );
       exit(json_encode($return_struct));
    }
    
}
