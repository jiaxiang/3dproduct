<?php defined('SYSPATH') or die('No direct access allowed.');

class Attribute_Controller extends Template_Controller {
    // Disable this controller when Kohana is set to production mode.
    const ALLOW_PRODUCTION = TRUE;
    private $package_name = 'product';
    private $class_name = '';
    // Set the name of the template to use
    public $template_ = 'layout/common_html';
    const ATTRIBUTE_TYPE = AttributeService::ATTRIBUTE_SPEC;
    
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
        role::check('product_attribute');
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
                $this->template->title = '规格管理';
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
        role::check('product_attribute_add');
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
        role::check('product_attribute_edit');
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
            
            //数据验证
            if(!isset($request_data['id']) || !is_numeric($request_data['id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
            }
            
            $attribute = AttributeService::get_instance()->get_attribute_options(array( 
                    'where' => array ( 
                        'id' => $request_data['id']  
                    )));
            $attribute = $attribute[$request_data['id']];
            
            //属性值处理数据
            $options = $attribute['options']; 
            if($options){       
                foreach($options as $key => $option){
                    if(isset($options[$key]['image']) && !empty($options[$key]['image'])){
                        $img = explode('|', $options[$key]['image']);
                        $options[$key]['picurl'] = $img[2];
                    }else{
                        $options[$key]['image'] = '';
                        $options[$key]['picurl'] = '/att/no.gif';
                    }
                }
            }
            $attribute['options'] = $options;
            $return_data['data'] = $attribute;
            //d($attribute);
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
            }
        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $request_data, $return_struct);
        }
    }
    
    public function put()
    {
        //* 收集请求数据 ==根据业务逻辑定制== */
        $request_data = $this->input->post();
        
        //权限检查
        if(isset($request_data['id'])){
            role::check('product_attribute_edit');
        }else{
            role::check('product_attribute_add');
        }
        
        //安全过滤
        $request_data = trims::run($request_data);
        tool::filter_strip_tags($request_data);
        
        $return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try{
            //* 初始化返回数据 */
            $return_data = array ();
            
            //数据验证
            $validResult = Validation::factory($request_data)->pre_filter('trim')
                                    ->add_rules('name', 'required', 'length[1,100]');
            if($validResult->validate() == FALSE){
                //* 输出错误的具体信息 ==根据业务逻辑定制== */
                $return_struct['content']['errors'] = $validResult->errors();
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
            }
            //d($request_data);
            
            // 调用底层服务
            $attribute_service = AttributeService::get_instance();
            
            //是输入项时，要先删除旧的选项值
            if(isset($request_data['type']) && $request_data['type']==1)
            {
                $request_data['option_name']=NULL;
                $request_data['display']=NULL;
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
            $set_data['display'] = $request_data['display'];
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
            if($request_data['type']==0 && isset($request_data['option_name']) || !empty($request_data['option_name'])){
                foreach($request_data['option_name'] as $key => $val){
                    $att_val = array();
                    $att_val['attribute_id'] = $return_data['id'];
                    $att_val['name'] = html::specialchars($val);
                    $att_val['alias'] = html::specialchars($request_data['option_alias'][$key]);
                    $att_val['order'] = $request_data['option_order'][$key];
                    $att_val['image'] = $request_data['option_image'][$key];
                    
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
        role::check('product_attribute_delete');
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
        role::check('product_attribute_delete');
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
    
    public function uploadform()
    {
        role::check('product_attribute');
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
            $return_data = $request_data = $this->input->get();

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
                $content = new View($this->package_name . '/' . $this->class_name . '/' . __FUNCTION__);

                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                
                $this->template->content->picture_max_size = $picture_max_size;
                $this->template->content->picture_types = $picture_types;
                
            } // end of request type determine

        }catch(MyRuntimeException $ex){
            $this->_ex($ex, $request_data, $return_struct);
        }
    }
    
    public function upload()
    {
        role::check('product_attribute');
        
        try{
            $return_struct = array (
                'status' => 0, 
                'code' => 501, 
                'msg' => 'Not Implemented', 
                'content' => array () 
            );
            
            //* 初始化返回数据 */
            $return_data = array ();
            
            //* 收集请求数据 ==根据业务逻辑定制== */
            $return_data = $request_data = $this->input->post();

            // 上传的表单域名字
            $attach_field = 'attribute_img';
            
            // 附件应用类型
            $attach_app_type = 'productPicAttach';
            
            // 如果无上传请求
            if(!page::issetFile($attach_field)){
                throw new MyRuntimeException('请选择需要上传的图片', 400);
            }
            
            $title = isset($request_data['attribute_img_title']) && is_array($request_data['attribute_img_title']) && !empty($request_data['attribute_img_title']) ? $request_data['attribute_img_title'] : array ();
            
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

                    if(isset($attach_setup['allowTypes']) && !in_array($file_type_current, $attach_setup['allowTypes'])){
                        throw new MyRuntimeException(Kohana::lang('o_product.file_type_invalid') . $_FILES[$attach_field]['name'][$index], 400);
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
                        'tmbfile' => $_FILES[$attach_field]['tmp_name'][$index] 
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

            //require_once (Kohana::find_file('vendor', 'phprpc/phprpc_client', TRUE));
            //!isset($attachmentService) && $attachmentService = new PHPRPC_Client(Kohana::config('phprpc.remote.Attachment.host'));
            //!isset($phprpcApiKey) && $phprpcApiKey = Kohana::config('phprpc.remote.Attachment.apiKey');

            // 调用附件存储服务
            for($index=0;$index<$file_count_total;$index++){
                $img_id = AttService::get_instance()->save_default_img($file_meta_data[$index]['tmbfile']);
                if(!$img_id){
                	throw new MyRuntimeException(Kohana::lang('o_product.phprpc_pic_save_failed'), 400);
                }                
                $attribute_image = array (
                    'attach_id' => $img_id, 
                    'title' => isset($title[$index]) ? strip_tags(trim($title[$index])) : '' 
                );
                $return_data['picurl'] = $attribute_image['picurl'] = AttService::get_instance()->get_img_url($img_id);
                $return_data['meta'] = implode('|', $attribute_image);
                // 清理临时文件
                @unlink($file_meta_data[$index]['tmbfile']);
            }
            //echo "<pre>";print_r($file_meta_data);print_r($return_data);die();
            
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
                $content = new View($this->package_name . '/' . $this->class_name . '/' . __FUNCTION__);
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
            $return_struct['status'] = 0;
            $return_struct['code'] = $ex->getCode();
            $return_struct['msg'] = $ex->getMessage();
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()){
                $this->template->content = $return_struct;
            }else{
                //$return_struct['action']['type']= 'close';  // 当前应用为弹出窗口所以定义失败后续动作为关闭窗口
                $this->template = new View('layout/default_html');
                $this->template->return_struct = $return_struct;
                $content = new View('info2');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                $return_struct['action'] = array (
	                'type' => 'location', 
	                'url' => request::referrer()
            	);
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
    
    public function option_relation_data()
    {
        role::check('product_attribute_delete');
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
            if(!isset($request_data['option_id']) || !is_numeric($request_data['option_id'])){
                throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 404);
            }
            
            // 调用服务
            $attributeoption_service = Attribute_valueService::get_instance();
            if($attributeoption_service->is_relation_by_attributeoption_id($request_data['option_id'])){
                throw new MyRuntimeException('该规格已被关联，请取消关联之后重试！', 500);
            }
            $attributeoption_service->delete_by_attribute_value_id($request_data['option_id']);
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = Kohana::lang('o_global.delete_success');
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
       if(!role::verify('product_attribute'))
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
