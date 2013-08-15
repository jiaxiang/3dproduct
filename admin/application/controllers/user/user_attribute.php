<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_attribute_Controller extends Template_Controller {
    private $class_name = '';
    private $package = '';
    // Set the name of the template to use
    public $template_ = 'layout/common_html';
    
    public function __construct()
    {
        $this->package = 'site';
        $this->class_name = strtolower(substr(__CLASS__, 0, -11));
        parent::__construct();
        if($this->is_ajax_request()){
            $this->template = new View('layout/default_json');
        }
    }
    
    public function index()
    {
        role::check('user_attribute');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            $user_attribute_service = User_attributeService::get_instance();
            
            $query_assoc = array(
                'where'=>array(
                ),
                'orderby'=>array(
                        'attribute_order'=>'DESC',
                        'id'   =>'ASC',
                ),
            );
            $user_attributes = $user_attribute_service->index($query_assoc);
            
            //检查添加系统默认项
            $user_attribute_service->add_default_attributes($user_attributes);

            $this->template->content = new view("user/user_attribute");
            $this->template->content->user_attributes = $user_attributes;
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
    
    
    public function add()
    {
        role::check('user_attribute');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //读取配置文件
            $attribute_types = kohana::config('user_attribute_type.attribute');
            $attribute_type_group = kohana::config('user_attribute_type.type_group');
            $this->template->content = new view("user/user_attribute_add");
            $this->template->content->attribute_types = $attribute_types;
            $this->template->content->attribute_type_group = $attribute_type_group;
            
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
        role::check('user_attribute');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //收集数据
            $request_data = $this->input->get();
            if(empty($request_data)|| !isset($request_data['attribute_id']))
            {
                throw new MyRuntimeException(kohana::lang('o_user_attribute.data_lost'), 400);
            }
            $user_attribute_service = User_attributeService::get_instance();
            $user_attribute = $user_attribute_service->get($request_data['attribute_id']);
            //编辑的是否存在
            if(empty($user_attribute))
            {
                throw new MyRuntimeException(kohana::lang('o_user_attribute.user_attribute_not_exist'), 400);
            }
            
            //判断编辑的是否为系统默认项
            if($user_attribute['attribute_default'])
            {
                throw new MyRuntimeException(kohana::lang('o_user_attribute.default_can_not_edit'), 400);
            }
            $user_attribute['attribute_option'] = explode(',',trim($user_attribute['attribute_option'],','));
            //读取配置文件
            $attribute_types = kohana::config('user_attribute_type.attribute');
            $attribute_type_group = kohana::config('user_attribute_type.type_group');
            
            $this->template->content = new view("user/user_attribute_edit");
            $this->template->content->attribute_types = $attribute_types;
            $this->template->content->attribute_type_group = $attribute_type_group;
            $this->template->content->user_attribute = $user_attribute;
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
    
    public function put()
    {
        role::check('user_attribute');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //字符过滤
            tool::filter_strip_tags($_POST);
            //收集信息
            $request_data = $this->input->post();
            $validation = Validation::factory($request_data)
                        ->pre_filter('trim')
                        ->add_rules('attribute_name','required','length[1,256]')
                        ->add_rules('attribute_type','required','length[1,64]');
            if($validation->validate() == FALSE){
                $return_struct['content']['errors'] = $validation->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            isset($request_data['attribute_required']) &&
            $insert_data['attribute_required'] = $request_data['attribute_required'];
            $insert_data['attribute_name'] = $request_data['attribute_name'];
            $insert_data['attribute_type'] = $request_data['attribute_type'];

            //判断是否有选项内容
            if($offset = strpos($insert_data['attribute_type'],'.'))
            {
                if(substr($insert_data['attribute_type'],0,$offset)=='select')
                {
                    if(isset($request_data['attribute_option']) && !empty($request_data['attribute_option']))
                    {
                        $insert_data['attribute_option']='';
                        foreach($request_data['attribute_option'] as $attribute_option)
                        {
                            $insert_data['attribute_option'] .= $attribute_option.',';
                        }
                        $insert_data['attribute_option'] = trim($insert_data['attribute_option'],',');
                        if(strlen($insert_data['attribute_option'])>256){
                            throw new MyRuntimeException(kohana::lang('o_user_attribute.option_too_length'), 400);
                        }
                    }else{
                        throw new MyRuntimeException(kohana::lang('o_user_attribute.option_need'), 400);
                    }
                }else{
                    $insert_data['attribute_option'] = '';
                }
            }else{
                throw new MyRuntimeException(kohana::lang('o_user_attribute.user_attribute_type_error'), 400);
            }
            //调用底层Service
            $user_attribute_service = User_attributeService::get_instance();
            $insert_data['id'] = $user_attribute_service->add($insert_data);
            if(!$insert_data['id']){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 500);
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = kohana::lang('o_global.add_success');
            $return_struct['content'] = $insert_data;
            $return_struct['action'] = array (
                'type' => 'location', 
                'url' => url::base() . 'user/' . $this->class_name . '/' . 'index' 
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
    
    public function post()
    {
        role::check('user_attribute');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //字符过滤
            tool::filter_strip_tags($_POST);
            //收集信息
            $request_data = $this->input->post();
            $validation = Validation::factory($request_data)
                        ->pre_filter('trim')
                        ->add_rules('attribute_name','required','length[1,256]')
                        ->add_rules('attribute_type','required','length[1,64]')
                        ->add_rules('id','required','numeric');
            if($validation->validate() == FALSE){
                $return_struct['content']['errors'] = $validation->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            
            //调用底层Service
            $user_attribute_service = User_attributeService::get_instance();
            $user_attribute = $user_attribute_service->get($request_data['id']);
            
            //检查编辑的是否存在
            if(empty($user_attribute))
            {
                throw new MyRuntimeException(kohana::lang('o_user_attribute.user_attribute_not_exist'), 400);
            }
            
            //判断编辑的是否为系统默认项
            if($user_attribute['attribute_default'])
            {
                throw new MyRuntimeException(kohana::lang('o_user_attribute.default_can_not_edit'), 400);
            }
            $insert_data['attribute_required'] = isset($request_data['attribute_required'])?
                                                 $request_data['attribute_required']:0;
            $insert_data['attribute_name']      = $request_data['attribute_name'];
            $insert_data['attribute_type']      = $request_data['attribute_type'];
            $insert_data['id']                  = $request_data['id'];
            //判断是否有选项内容
            if($offset = strpos($insert_data['attribute_type'],'.'))
            {
                if(substr($insert_data['attribute_type'],0,$offset)=='select')
                {
                    if(isset($request_data['attribute_option']) && !empty($request_data['attribute_option']))
                    {
                        $insert_data['attribute_option']='';
                        foreach($request_data['attribute_option'] as $attribute_option)
                        {
                        	if(!empty($attribute_option))
                        	{
                                $insert_data['attribute_option'] .= $attribute_option.',';
                        	}else{
                        	   throw new MyRuntimeException(kohana::lang('o_user_attribute.option_empty'), 400);
                        	}
                        }
                        $insert_data['attribute_option'] = trim($insert_data['attribute_option'],',');
                        if(strlen($insert_data['attribute_option'])>256){
                            throw new MyRuntimeException(kohana::lang('o_user_attribute.option_too_length'), 400);
                        }
                    }else{
                        throw new MyRuntimeException(kohana::lang('o_user_attribute.option_need'), 400);
                    }
                }else{
                    $insert_data['attribute_option'] = '';
                }
            }else{
                throw new MyRuntimeException(kohana::lang('o_user_attribute.user_attribute_type_error'), 400);
            }
            
            $user_attribute_service->set($insert_data['id'],$insert_data);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = kohana::lang('o_global.update_success');
            $return_struct['content'] = $insert_data;
            $return_struct['action'] = array (
                'type' => 'location', 
                'url' => url::base() . 'user/' . $this->class_name . '/' . 'index' 
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
        role::check('user_attribute');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //收集信息
            $request_data = $this->input->get();
            
            if(empty($request_data)|| !isset($request_data['attribute_id']))
            {
                throw new MyRuntimeException(kohana::lang('o_user_attribute.data_lost'), 400);
            }
            
            //调用底层Service
            $user_attribute_service = User_attributeService::get_instance();
            $user_attribute = $user_attribute_service->get($request_data['attribute_id']);
            if(empty($user_attribute))
            {
                throw new MyRuntimeException(kohana::lang('o_user_attribute.user_attribute_not_exist'), 400);
            }
            
            //判断删除的是否为系统默认项
            if($user_attribute['attribute_default'])
            {
                throw new MyRuntimeException(kohana::lang('o_user_attribute.default_can_not_delete'), 400);
            }
            if(!$user_attribute_service->remove($request_data['attribute_id']))
            {
                throw new MyRuntimeException(kohana::lang('o_global.delete_error'), 400);
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = kohana::lang('o_global.delete_success');
            $return_struct['action'] = array (
                'type' => 'location', 
                'url' => url::base() . 'user/' . $this->class_name . '/' . 'index' 
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
    
    public function set_order()
    {
        role::check('user_attribute');
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //收集数据
            $request_data = $this->input->post();
            if(empty($request_data['setorder']))
            {
                throw new MyRuntimeException(kohana::lang('o_user_attribute.data_lost'), 400);
            }
            $query_struct = array('where'=>array('id'=>$request_data['setorder']));
            //调用底层服务
            $user_attribute_service = User_attributeService::get_instance();
            $user_attributes = $user_attribute_service->index($query_struct);
            //检查所提交的数据数据库里是否有
            if(count($user_attributes)!=count($request_data['setorder']))
            {
                throw new MyRuntimeException(kohana::lang('o_user_attribute.data_lost'), 400);
            }
            
            //修改排序值
            $i = 0;
            foreach($request_data['setorder'] as $id)
            {
                $user_attribute_service->set($id, array('attribute_order'=>count($request_data['setorder'])-$i));
                $i++;
            }
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = kohana::lang('o_global.position_success');
            $return_struct['action'] = array (
                'type' => 'location', 
                'url' => url::base() . 'user/' . $this->class_name . '/' . 'index' 
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
    
    public function show_toggle()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{            
            //权限验证
           if(!role::verify('user_attribute',$this->site_id,0))
	       {
	           $return_struct['msg'] = Kohana::lang('o_global.permission_enough');
	           exit(json_encode($return_struct));
	       }
            
            //收集信息
            $request_data = $this->input->get();
            
            if(empty($request_data)|| !isset($request_data['attribute_id']))
            {
            	if($this->is_ajax_request()){
	                $return_struct['msg'] = kohana::lang('o_user_attribute.data_lost');
	                $return_struct['code']=400;
	                exit(json_encode($return_struct));
            	}else{
            	   throw new MyRuntimeException(kohana::lang('o_user_attribute.data_lost'), 400);
            	}
            }
            
            //调用底层Service
            $user_attribute_service = User_attributeService::get_instance();
            $user_attribute = $user_attribute_service->get($request_data['attribute_id']);
            if(empty($user_attribute))
            {
                if($this->is_ajax_request()){
                    $return_struct['msg'] = kohana::lang('o_user_attribute.user_attribute_not_exist');
                    $return_struct['code']=400;
                    exit(json_encode($return_struct));
                }else{
                   throw new MyRuntimeException(kohana::lang('o_user_attribute.user_attribute_not_exist'), 400);
                }
            }
            
            $arrtibute_show = array('attribute_show'=>1-$user_attribute['attribute_show']);
            $user_attribute_service->set($user_attribute['id'],$arrtibute_show);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = kohana::lang('o_user_attribute.operator_success');
            $return_struct['content'] = $arrtibute_show;
            
            //* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                exit(json_encode($return_struct));
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
}