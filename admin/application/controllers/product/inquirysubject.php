<?php
defined('SYSPATH') or die('No direct access allowed.');
class Inquirysubject_Controller extends Template_Controller {
    
    private $class_name = '';
    private $package = '';

    public $template_ = 'layout/common_html';
    
    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->package = 'product';
        $this->class_name = strtolower(substr(__CLASS__, 0, strpos(__CLASS__, '_')));
        parent::__construct();
        if($this->is_ajax_request())
        {
            $this->template = new View('layout/default_json');
        }
    }
    
    /**
     * 数据列表
     */
    public function index()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
            //* 初始化返回数据 */
            $return_data = array (
                'site_list' => array (), 
                'list' => array () 
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();       

            //* 权限验证 */
            $site_id = site::id();
            if($site_id == 0){
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            
            role::check('inquiry_subject', $site_id, 0);
            
            // 执行业务逻辑
            //* 初始化默认查询结构体 */
            $query_struct_default = array (
                'where' => array (
                    'site_id' => $site_id 
                ), 
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
                    'id' => 'ASC' 
                ), 
                1 => array (
                    'id' => 'DESC' 
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
                ),
                6 => array (
                    'position' => 'ASC' 
                ),
                7 => array (
                    'position' => 'DESC' 
                ) 
            );
            $orderby = controller_tool::orderby($orderby_arr);
            // 排序处理 
            if(isset($request_data['orderby']) && is_numeric($request_data['orderby']))
            {
                $query_struct_current['orderby'] = $orderby;
            }
            // 每页条目数
            controller_tool::request_per_page($query_struct_current, $request_data);
            
            //调用服务执行查询
            $inquieysubject_service = InquirysubjectService::get_instance();
            $count = $inquieysubject_service->count($query_struct_current);
            // 模板输出 分页
            $this->pagination = new Pagination(array (
                'total_items' => $count, 
                'items_per_page' => $query_struct_current['limit']['per_page'] 
            ));
            $query_struct_current['limit']['page'] = $this->pagination->current_page;
            
            $return_data['list'] = $inquieysubject_service->index($query_struct_current);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
                // html 输出
                //* 模板输出 */
                $content = new View($this->package . '/' . $this->class_name . '/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = '商品咨询主题管理';
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据 
                $this->template->content->site_id = $site_id;
            
            } // end of request type determine
        }catch(MyRuntimeException $ex){
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
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
     * 添加数据页面
     */
    public function add()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
            //* 初始化返回数据 */
            $return_data = array (
                'site_list' => array () 
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            //* 权限验证 */
            $site_id = site::id();
            if($site_id == 0)
            {
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            role::check('inquiry_subject', $site_id, 0);
            
            // 调用底层服务
            $site_name = Mysite::instance($site_id)->get('name');
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request())
            {
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }
            else
            {
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
                $this->template->content->site_id = $site_id;
                $this->template->content->site_name = $site_name;            
            } // end of request type determine
        }
        catch(MyRuntimeException $ex)
        {
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
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
    
    /*
     * 提交新增咨询主题
     */
    public function do_add()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
            $request_data = trims::run($request_data);            
            //标签过滤
            tool::filter_strip_tags($request_data);

            //* 权限验证 */
            $site_id = site::id();
            if($site_id == 0)
            {
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            role::check('inquiry_subject', $site_id, 0);
            
            // 调用底层服务
            $inquieysubject_service = InquirysubjectService::get_instance();
            
            //数据验证
            $validResult = Validation::factory($request_data)->pre_filter('trim')->add_rules('name', 'required', 'length[1,255]');
            
            if($validResult->validate() == FALSE)
            {
                //* 输出错误的具体信息 ==根据业务逻辑定制== */
                $return_struct['content']['errors'] = $validResult->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            
            //name重复判断
            $query_struct = array(
                'where' => array(
                    'site_id' => $site_id,
                    'name' => $request_data['name']
                )
            );
            if(!empty($request_data['name']) && $inquieysubject_service->count($query_struct))
            {
                throw new MyRuntimeException(Kohana::lang('o_product.subject_has_exists'), 409);
            }
            
            //执行添加
            $set_data = array ();
            $set_data['site_id'] = $site_id;
            $set_data['name'] = $request_data['name'];
            $set_data['position'] = InquirysubjectService::DEFAULT_POSITION;
            $set_data['create_timestamp'] = time();
            $set_data['update_timestamp'] = time();
            $return_data['id'] = $inquieysubject_service->add($set_data);
            if(!$return_data['id'])
            {
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
            if($this->is_ajax_request())
            {
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }
            else
            {
                // html 输出
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;            
            } // end of request type determine
        }
        catch(MyRuntimeException $ex)
        {
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
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
    
    /*
     * 提交编辑
     */
    public function do_edit()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->post();
            $request_data = trims::run($request_data);
            
            //标签过滤
            tool::filter_strip_tags($request_data);
            
            //* 权限验证 */
            $site_id = site::id();
            if($site_id == 0)
            {
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            role::check('inquiry_subject', $site_id, 0);
            
            //数据验证
            if(!isset($request_data['id']) || !is_numeric($request_data['id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            // 调用底层服务
            $inquieysubject_service = InquirysubjectService::get_instance();
            $inquieysubject = $inquieysubject_service->get($request_data['id']);
            //权限验证
            if($inquieysubject['site_id'] != $site_id)
            {
                throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            //数据验证
            $validResult = Validation::factory($request_data)->pre_filter('trim')->add_rules('name', 'required', 'length[1,255]');
            if($validResult->validate() == FALSE)
            {
                //* 输出错误的具体信息 ==根据业务逻辑定制== */
                $return_struct['content']['errors'] = $validResult->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            
            //name重复判断
            $query_struct = array(
                'where' => array(
                    'site_id' => $site_id,
                    'name' => $request_data['name']
                )
            );
            if($request_data['name'] != $inquieysubject['name'] && $inquieysubject_service->count($query_struct))
            {
                throw new MyRuntimeException(Kohana::lang('o_product.subject_has_exists'), 409);
            }
            
            //执行修改
            $set_data = array ();
            $set_data['id'] = $request_data['id'];
            $set_data['name'] = $request_data['name'];
            $set_data['update_timestamp'] = time();
            $inquieysubject_service->set($set_data['id'], $set_data);
            
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
            if($this->is_ajax_request())
            {
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }
            else
            {
                // html 输出
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;           
            } // end of request type determine
        }
        catch(MyRuntimeException $ex)
        {
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
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
    
    /*
     * 加载编辑的页面
     */
    public function edit()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
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
            
            role::check('inquiry_subject', $site_id, 0);
            
            //数据验证
            $inquieysubject_service = InquirysubjectService::get_instance();
            if(!isset($request_data['id']) || !is_numeric($request_data['id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            
            $inquieysubject = $inquieysubject_service->get($request_data['id']);
            if($inquieysubject['site_id'] != $site_id)
            {
                throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            //返回数据
            $return_data['data'] = $inquieysubject;
            
            //当前站点
            $site_name = Mysite::instance($inquieysubject['site_id'])->get('name');
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = '';
            $return_struct['content'] = $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request())
            {
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }
            else
            {
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
                $this->template->content->site_name = $site_name;           
            } // end of request type determine        
        }
        catch(MyRuntimeException $ex)
        {
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
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
    
    /*
     * 删除
     */
    public function delete()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
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
            role::check('inquiry_subject', $site_id, 0);
            
            //数据验证
            $inquieysubject_service = InquirysubjectService::get_instance();
            if(!isset($request_data['id']) || !is_numeric($request_data['id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            
            $inquieysubject = $inquieysubject_service->get($request_data['id']);
            if($inquieysubject['site_id'] != $site_id)
            {
                throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            //执行删除
            $inquieysubject_service->remove($inquieysubject['id']);
            
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
            if($this->is_ajax_request())
            {
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }
            else
            {
                // html 输出
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;           
            } // end of request type determine
        }
        catch(MyRuntimeException $ex)
        {
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
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
    
    /*
     * 批量删除
     */
    public function delete_all()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
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
            
            role::check('inquiry_subject', $site_id, 0);
            
            //数据验证
            if(!isset($request_data['site_id']) || !is_numeric($request_data['site_id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            if(!isset($request_data['id']) || empty($request_data['id']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            if($request_data['site_id'] != $site_id)
            {
                throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $inquieysubject_service = InquirysubjectService::get_instance();
            //执行删除
            if(isset($request_data['id']) || !empty($request_data['id']))
            {
                $inquieysubject_service->delete_subjects($request_data['id']);
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
            if($this->is_ajax_request())
            {
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }
            else
            {
                // html 输出
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;            
            } // end of request type determine
        }
        catch(MyRuntimeException $ex)
        {
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
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
    
    /*
     * Ajax检查主题名字是否存在
     */
    public function check_name()
    {
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try
        {
            //* 初始化返回数据 */
            $return_data = array ();
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();  
            $request_data = trims::run($request_data);            
            //标签过滤
            tool::filter_strip_tags($request_data);         

            //必须为ajax请求
            if(!$this->is_ajax_request())
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            //* 权限验证 */
            $site_id = site::id();
            if($site_id == 0)
            {
                throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            
            if(!isset($request_data['name']) || empty($request_data['name']))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            $inquieysubject_service = InquirysubjectService::get_instance();
            if(isset($request_data['subject_id']) && is_numeric($request_data['subject_id']))
            {
                $inquieysubject = $inquieysubject_service->get($request_data['subject_id']);
                //判断站点
                if($site_id != $inquieysubject['site_id'])
                {
                    throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 404);
                }
                if($inquieysubject['name'] == $request_data['name'])
                    exit(Kohana::lang('o_global.true'));
            }
            $query_struct = array(
                'where' => array(
                    'site_id' => $site_id,
                    'name' => $request_data['name']
                )
            );
            // 调用底层服务
            if($inquieysubject_service->count($query_struct))
            {
                exit(Kohana::lang('o_global.false'));
            }
            else
            {
                exit(Kohana::lang('o_global.true'));
            }       
        }
        catch(MyRuntimeException $ex)
        {
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request())
            {
                $this->template->content = $return_struct;
            }
            else
            {
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
       if(!role::verify('inquiry_subject',site::id(),0))
       {
           $return_struct['msg'] = Kohana::lang('o_global.permission_enough');
           exit(json_encode($return_struct));
       }
       $validation = Validation::factory($request_data)
				->pre_filter('trim')
				->add_rules('id',               'required',   'digit')
				->add_rules('order',            'required',   'digit');
       if (!$validation->validate())
	   {
			$return_struct['msg'] = Kohana::lang('o_global.position_rule');
            exit(json_encode($return_struct));
	   }
       if(empty($id) || (empty($order) && $order != 0))
       {
           $return_struct['msg'] = Kohana::lang('o_global.bad_request');
           exit(json_encode($return_struct));
       }
       if(!is_numeric($order) || $order < 0)
       {
           $return_struct['msg'] = Kohana::lang('o_global.position_rule');
           exit(json_encode($return_struct));
       }

       $inquieysubject_service = InquirysubjectService::get_instance();
       $inquieysubject_service->set($id,array('position'=>$order));
       $return_struct = array(
            'status'        => 1,
            'code'          => 200,
            'msg'           => Kohana::lang('o_global.position_success'),
            'content'       => array('order'=>$order),
       );
       exit(json_encode($return_struct));
    } 
}