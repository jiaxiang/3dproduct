<?php defined('SYSPATH') OR die('No direct access allowed.');
class User_level_Controller extends Template_Controller {
    public function __construct()
    {
        $this->class_name = strtolower(substr(__CLASS__, 0, -11));
        parent::__construct();
        if($this->is_ajax_request()){
            $this->template = new View('layout/default_json');
        }
    }
    
    public function index()
    {
        role::check('user_level');
        $return_struct = array (
            'status'  => 0, 
            'code'       => 501, 
            'msg'       => 'Not Implemented', 
            'content' => '' 
        );
        try{
            $user_level_service = User_levelService::get_instance();
            $query_assoc = array(
                'where'=>array(
                    'active' => 1,
                ),
                'orderby'=>array(
                    'score'=>'ASC',
                ),
            );
            $user_levels = $user_level_service->index($query_assoc);
            
            $this->template->content = new View('user/user_level');
            $this->template->content->user_levels = $user_levels;
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
        role::check('user_level');
        $return_struct = array (
            'status'  => 0, 
            'code'       => 501, 
            'msg'       => 'Not Implemented', 
            'content' => '' 
        );
        try{            
            $this->template->content = new View('user/user_level_add');
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
        $return_struct = array (
            'status'  => 0, 
            'code'       => 501, 
            'msg'       => 'Not Implemented', 
            'content' => '' 
        );
        try{
            role::check('user_level');
            //收集数据
            tool::filter_strip_tags($_POST);
            $request_data = $this->input->post();
            $validation = Validation::factory($request_data)
                        ->pre_filter('trim')
                        ->add_rules('name_manage','required','length[1,255]')
                        ->add_rules('name','required','length[1,255]')
                        ->add_rules('is_default','required')
                        ->add_rules('is_special','required');
            if($this->input->post('is_special')===0)
            {
                $validation->add_rules('score','numeric','length[1,10]');
            }
            if($validation->validate() == FALSE)
            {
                $return_struct['content']['errors'] = $validation->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }

            //调用底层服务
            $user_level_service = User_levelService::get_instance();
            //验证是否有同积分的等级
            if(!$request_data['is_special'] && 
                $user_level_service->check_exist_score($request_data['score']))
            {
                throw new MyRuntimeException(Kohana::lang('o_user_level.same_score_exists'), 400);
            }
            
            //验证是否有同名称的等级
            if($user_level_service->check_exist_name($request_data['name']))
            {
                throw new MyRuntimeException(Kohana::lang('o_user_level.same_name_exists'), 400);
            }
            
            //判断是否有默认的等级
            $query_struct = array(
                    'where'=>array(
                        'is_default'=>1,
                        'active'    =>1,
                    ),
                );
            $default_levels = $user_level_service->index($query_struct);
            if(empty($default_levels) && !$request_data['is_default'])
            {
                remind::set(kohana::lang('o_user_level.need_default_level'), 'user/user_level/add', 'error');
            } else{
                //如果该等级为默认的等级，将别的默认等级设为非默认等级
                if($request_data['is_default'])
                {
                    foreach($default_levels as $default_level)
                    {
                        $edit_data['is_default'] = 0;
                        $user_level_service->set($default_level['id'],$edit_data);
                    }    
                }
            } 
            //准备存储数据
            $insert_data = array();
            $insert_data['name_manage'] = $request_data['name_manage'];
            $insert_data['name']         = $request_data['name'];
            $insert_data['score']         = !empty($request_data['score'])?$request_data['score']:0;
            $insert_data['is_special']     = $request_data['is_special'];
            $insert_data['is_default']     = $request_data['is_default'];
            $insert_data['active']         = 1;
            //储存数据
            $insert_data['id']    = $user_level_service->create($insert_data);
            
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

    public function edit()
    {
        role::check('user_level');
        $return_struct = array (
            'status'  => 0, 
            'code'       => 501, 
            'msg'       => 'Not Implemented', 
            'content' => '' 
        );
        try{
            //收集数据
            $level_id = $this->input->get('level_id');
            if(empty($level_id))
            {
                throw new MyRuntimeException(kohana::lang('o_global.bad_request'), 400);
            }
            //调用底层服务
            $user_level_service = User_levelService::get_instance();
            //取出数据
            $user_level = $user_level_service->get($level_id);
            
            $this->template->content = new View('user/user_level_edit');
            $this->template->content->user_level = $user_level;
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
        role::check('user_level');
        $return_struct = array (
            'status'  => 0, 
            'code'       => 501, 
            'msg'       => 'Not Implemented', 
            'content' => '' 
        );
        try{
            //收集数据
            tool::filter_strip_tags($_POST);
            $request_data = $this->input->post();
            $validation = Validation::factory($request_data)
                        ->pre_filter('trim')
                        ->add_rules('name_manage','required','length[1,255]')
                        ->add_rules('name','required','length[1,255]')
                        ->add_rules('level_id','required')
                        ->add_rules('is_default','required')
                        ->add_rules('is_special','required');
            if($this->input->post('is_special')===0)
            {
                $validation->add_rules('score','numeric','length[1,10]');
            }
            if($validation->validate() == FALSE)
            {
                $return_struct['content']['errors'] = $validation->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            //调用底层服务
            $user_level_service = User_levelService::get_instance();
            
            //验证是否有同积分的等级
            if(!$request_data['is_special'] && 
                $user_level_service->check_exist_score($request_data['score'], $request_data['level_id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_user_level.same_score_exists'), 400);
            }
            
            //验证是否有同名称的等级
            if($user_level_service->check_exist_name($request_data['name'], $request_data['level_id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_user_level.same_name_exists'), 400);
            }
            
            $edit_level = $user_level_service->get($request_data['level_id']);
            if(empty($edit_level))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            //判断是否有默认的等级
            $query_struct = array(
                    'where'=>array(
                        'is_default'=>1,
                        'id!='        =>$edit_level['id'],
                        'active'    =>1,
                    ),
                );
            $default_levels = $user_level_service->index($query_struct);
            if(empty($default_levels) && !$request_data['is_default'])
            {
                remind::set(kohana::lang('o_user_level.need_default_level'), 'user/user_level/edit?level_id='.$edit_level['id'], 'error');
            } else{
                //如果该等级为默认的等级，将别的默认等级设为非默认等级
                if($request_data['is_default'])
                {
                    foreach($default_levels as $default_level)
                    {
                        $edit_data['is_default'] = 0;
                        $user_level_service->set($default_level['id'],$edit_data);
                    }    
                }
            } 
            //准备存储数据
            $edit_data = array();
            $edit_data['name_manage']     = $request_data['name_manage'];
            $edit_data['name']             = $request_data['name'];
            $edit_data['score']         = !empty($request_data['score'])?$request_data['score']:0;
            $edit_data['is_default']      = $request_data['is_default'];
            $edit_data['is_special']      = $request_data['is_special'];

            //储存数据
            $user_level_service->set($edit_level['id'],$edit_data);
            $edit_data['id'] = $edit_level['id'];
            
             //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = kohana::lang('o_global.update_success');
            $return_struct['content'] = $edit_data;
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
        role::check('user_level');
        $return_struct = array (
            'status'  => 0, 
            'code'       => 501, 
            'msg'       => 'Not Implemented', 
            'content' => '' 
        );
        try{
            //收集数据
            $request_data = $this->input->get();
            if(empty($request_data['level_id']))
            {
                throw new MyRuntimeException(kohana::lang('o_global.bad_request'), 400);
            }
            //调用底层服务
            $user_level_service = User_levelService::get_instance();
            //取出数据
            $user_level = $user_level_service->get($request_data['level_id']);
            if(empty($user_level))
            {
                throw new MyRuntimeException(kohana::lang('o_global.bad_request'), 400);
            }
            if(!$user_level['is_default'])
            {
                $user_level_service->remove($user_level['id']);
            }else{
                //throw new MyRuntimeException("默认等级不能删除", 400);
                remind::set("默认等级不能删除", '/user/user_level', 'error');
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = kohana::lang('o_global.delete_success');
            $return_struct['content'] = '';
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
    
    public function formula()
    {
        role::check('user_level');
        $return_struct = array (
            'status'  => 0, 
            'code'       => 501, 
            'msg'       => 'Not Implemented', 
            'content' => '' 
        );
        try{
            //调用底层服务
            $site_detail_service = Mysite_detail::instance();
            $user_level_service = User_levelService::get_instance();
            //取得当前的站点详细信息
            $site_detail = array();
            $site_detail = $site_detail_service->get_by_site_id();
            if(empty($site_detail))    
            {
                $site_detail = $user_level_service->set_default_level();
            }
            $this->template->content = new View('user/site_score_formula');
            $this->template->content->site_detail = $site_detail;
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
    
    public function edit_formula()
    {
        role::check('user_level');
        $return_struct = array (
            'status'  => 0, 
            'code'       => 501, 
            'msg'       => 'Not Implemented', 
            'content' => '' 
        );
        try{
            //收集数据
            tool::filter_strip_tags($_POST);
            $request_data = $this->input->post();
            $validation = Validation::factory($request_data)
                        ->pre_filter('trim')
                        ->add_rules('user_score_formula','required','length[1,255]');
            if($validation->validate() == FALSE)
            {
                $return_struct['content']['errors'] = $validation->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            //调用底层服务
            $site_detail_service = Mysite_detail::instance();
            //储存数据
            $data['user_score_formula'] = $request_data['user_score_formula'];
            //判断是否为默认的
            $site_detail_service->update_by_site_id($this->site_id,$data);
                
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = kohana::lang('o_global.update_success');
            $return_struct['content'] = $request_data;
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
}