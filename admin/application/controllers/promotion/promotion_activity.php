<?php
defined('SYSPATH') or die("No direct access allowed.");

class Promotion_activity_Controller extends Template_Controller {
    private $img_dir_name = 'promotion';
    
    public function index()
    {
        role::check('promotion_promotion');
        // 初始化默认查询条件
        $request_struct_current = array (
            'where' => array (
            ), 
            'like' => array (), 
            'orderby' => array (
                'id' => 'DESC' 
            ), 
            'limit' => array (
                'per_page' => 20, 
                'offset' => 0 
            ) 
        );
        
        // 收集请求数据
        $request_data = $this->input->get();
        
        //列表排序
        $orderby_arr = array (
            0 => array (
                'id' => 'DESC' 
            ), 
            1 => array (
                'id' => 'ASC' 
            ), 
            2 => array (
                'id' => 'ASC' 
            ), 
            3 => array (
                'id' => 'DESC' 
            ), 
            4 => array (
                'pmta_name' => 'ASC' 
            ), 
            5 => array (
                'pmta_name' => 'DESC' 
            ), 
            6 => array (
                'pmta_time_begin' => 'ASC' 
            ), 
            7 => array (
                'pmta_time_begin' => 'DESC' 
            ), 
            8 => array (
                'pmta_time_end' => 'ASC' 
            ), 
            9 => array (
                'pmta_time_end' => 'DESC' 
            ), 
            10 => array (
                'meta_title' => 'ASC' 
            ), 
            11 => array (
                'meta_title' => 'DESC' 
            ), 
            12 => array (
                'frontend_description' => 'ASC' 
            ), 
            13 => array (
                'frontend_description' => 'DESC' 
            ) 
        );
        $orderby = controller_tool::orderby($orderby_arr);
        // 排序处理 
        if(isset($request_data['orderby']) && is_numeric($request_data['orderby'])){
            $request_struct_current['orderby'] = $orderby;
        }
        
        // 每页条目数
        controller_tool::request_per_page($request_struct_current, $request_data);
        
        // 当前支持的查询业务逻辑
        if(isset($request_data['type']) && isset($request_data['keyword']) && !empty($request_data['keyword'])){
            switch($request_data['type']){
                case 'id':
                    $request_struct_current['where'][$request_data['type']] = intval(trim($request_data['keyword']));
                    $request_data['keyword'] = $request_struct_current['where'][$request_data['type']];
                    break;
                case 'pmta_name':
                    $request_struct_current['like'][$request_data['type']] = trim($request_data['keyword']);
                    $request_data['keyword'] = $request_struct_current['like'][$request_data['type']];
                    break;
                case 'meta_title':
                    $request_struct_current['like'][$request_data['type']] = trim($request_data['keyword']);
                    $request_data['keyword'] = $request_struct_current['like'][$request_data['type']];
                    break;
                case 'frontend_description':
                    $request_struct_current['like'][$request_data['type']] = trim($request_data['keyword']);
                    $request_data['keyword'] = $request_struct_current['like'][$request_data['type']];
                    break;
                case 'backend_description':
                    $request_struct_current['like'][$request_data['type']] = trim($request_data['keyword']);
                    $request_data['keyword'] = $request_struct_current['like'][$request_data['type']];
                    break;
            }
        }
        
        $count = Mypromotion_activity::instance()->count($request_struct_current);
        
        // 模板输出 分页
        $this->pagination = new Pagination(array (
            'total_items' => $count, 
            'items_per_page' => $request_struct_current['limit']['per_page'] 
        ));
        
        $request_struct_current['limit']['offset'] = $this->pagination->sql_offset;
        
        $promotion_activity_list = Mypromotion_activity::instance()->lists($request_struct_current);
        foreach($promotion_activity_list as $key => $rs){
            $promotion_list = Mypromotion::instance()->list_by_pmtaid($rs['id']);
            
            $promotion_activity_list[$key]['promotions'] = array ();
            foreach($promotion_list as $keyp => $_promotion){
                $promotion_activity_list[$key]['promotions'][$keyp]['id'] = $_promotion['id'];
                $promotion_activity_list[$key]['promotions'][$keyp]['description'] = $_promotion['pmt_description'];
                $promotion_activity_list[$key]['promotions'][$keyp]['time_begin'] = $_promotion['time_begin'];
                $promotion_activity_list[$key]['promotions'][$keyp]['time_end'] = $_promotion['time_end'];
            }
        }
        
        // 模板输出
        $this->template->content = new View("promotion/promotion_activity_list");
        
        // 变量绑定
        $this->template->content->promotion_activity_list = $promotion_activity_list;
        $this->template->content->count = $count;
        
        $this->template->content->request_data = $request_data;
    }
    
    public function add()
    {
        role::check('promotion_promotion');
        // 模板输出
        $this->template->content = new View("promotion/add_promotion_activity");
    }
    
    public function do_add()
    {
        /* 站点列表 */
        role::check('promotion_promotion');
        $request_data = $this->input->post();
        //标签过滤
        tool::filter_strip_tags($request_data);
        $session = Session::instance();
        $session->set_flash('sessionErrorData', $request_data);
        
        if(strtotime($request_data['pmta_time_end'])+ 24 * 3600 < time()){
            remind::set(Kohana::lang('o_promotion.time_end'), request::referrer(), 'error');
        } 
        if(strtotime($request_data['pmta_time_end']) < strtotime($request_data['pmta_time_begin'])){
            remind::set(Kohana::lang('o_promotion.begin_time_over_end'), request::referrer(), 'error');
        }
        $dayTimeStamp = 24 * 3600;
        $request_data['pmta_time_end'] = date('Y-m-d H:i:s', strtotime($request_data['pmta_time_end']) + $dayTimeStamp);

        if($promotion_activity_id = Mypromotion_activity::instance()->add($request_data)){
            $session->delete('sessionErrorData');
            //promotion::delete_memcache($request_data['site_id']);
            remind::set(Kohana::lang('o_global.add_success'), 'promotion/promotion_activity', 'success');
        }else{
            remind::set(Kohana::lang('o_global.add_error'), request::referrer(), 'error');
        }
    }
    
    public function edit()
    {
        role::check('promotion_promotion');
        $id = $this->input->get('id');
        $promotion = Mypromotion_activity::instance($id)->get();
        if(!$promotion['id']){
            remind::set(Kohana::lang('o_global.bad_request'), 'promotion/promotion_activity', 'error');
        }
        
        // 模板输出
        $this->template->content = new View("promotion/edit_promotion_activity");
        // 变量绑定
        $this->template->content->promotion_activity = $promotion;
    }
    
    public function do_edit()
    {
        role::check('promotion_promotion');
        //收集请求
        $request_data = $this->input->post();
        //标签过滤
        tool::filter_strip_tags($request_data);
        
        //错误信息返回
        $session = Session::instance();
        $session->set_flash('sessionErrorData', $request_data);
        
        $id = $request_data['id'];
        // 验证 - 数据有效性
        $promotion_activity = Mypromotion_activity::instance($id)->get();
        if(!$promotion_activity['id']){
            remind::set(Kohana::lang('o_global.bad_request'), 'promotion/promotion_activity', 'error');
        }
        
        if(strtotime($request_data['pmta_time_end'])+ 24 * 3600 < time()){
            remind::set(Kohana::lang('o_promotion.time_end'), request::referrer(), 'error');
        } 
        if(strtotime($request_data['pmta_time_end']) < strtotime($request_data['pmta_time_begin'])){
            remind::set("开始时间不能比结束时间晚", request::referrer(), 'error');
        }
        $dayTimeStamp = 24 * 3600;
        $request_data['pmta_time_end'] = date('Y-m-d H:i:s', strtotime($request_data['pmta_time_end']) + $dayTimeStamp);
        //判断促销活动的时间是否与促销规则的时间冲突
        $promotions = Mypromotion::instance()->lists(array (
            'where' => array (
                'pmta_id' => $promotion_activity['id'] 
            ) 
        ));
        if(!empty($promotions)){
            $minTime = $promotions[0]['time_begin'];
            $maxTime = $promotions[0]['time_end'];
            foreach($promotions as $promotion){
                $minTime > $promotion['time_begin'] && $minTime = $promotion['time_begin'];
                $maxTime < $promotion['time_end'] && $maxTime = $promotion['time_end'];
            }
            if($minTime < $request_data['pmta_time_begin'] || $maxTime > $request_data['pmta_time_end']){
                remind::set(Kohana::lang('o_promotion.cpn_activity_time_conflict'), request::referrer(), 'error');
            }
        }
        if(Mypromotion_activity::instance($id)->edit($request_data)){
            $session->delete('sessionErrorData');
            //promotion::delete_memcache($request_data['id']);
            remind::set(Kohana::lang('o_global.update_success'), "promotion/promotion_activity", 'success');
        }else{
            remind::set(Kohana::lang('o_global.update_error'), request::referrer(), 'error');
        }
    }
    
    public function do_delete()
    {
        role::check('promotion_promotion');
        //收集请求
        $id = $this->input->get('id');
        
        // 验证 - 数据有效性
        $pa = Mypromotion_activity::instance($id);
        $promotion_activity = $pa->get();
        if(!$promotion_activity['id']){
            remind::set(Kohana::lang('o_global.bad_request'), 'promotion/promotion_activity', 'error');
        }
        
        if($pa->delete()){
            remind::set(Kohana::lang('o_global.delete_success'), 'promotion/promotion_activity', 'success');
        }else{
            remind::set(Kohana::lang('o_global.delete_error'), 'promotion/promotion_activity', 'error');
        }
    }
    
    public function do_delete_all()
    {
        role::check('promotion_promotion');
        $promotion_activity_id_array = $this->input->post('id');
        
        if(!(is_array($promotion_activity_id_array) && count($promotion_activity_id_array))){
            remind::set(Kohana::lang('o_promotion.select_activity'), request::referrer(), 'error');
        }
        
        $count = 0;
        $false_count = 0;
        foreach($promotion_activity_id_array as $id){
            // 验证 - 数据有效性
            $pa = Mypromotion_activity::instance($id);
            $promotion_activity = $pa->get();
            if(!$promotion_activity['id']){
                $false_count++;
                continue;
            }
            
            if($pa->delete()){
                //promotion::delete_memcache($promotion_activity['site_id']);
                $count++;
            }else{
                $false_count++;
            }
        }
        if($false_count){
            remind::set(Kohana::lang('o_promotion.have') . $false_count . Kohana::lang('o_promotion.num_activity_cannot_delete'), request::referrer(), 'error');
        }else{
            remind::set(Kohana::lang('o_promotion.success_delete') . $count . Kohana::lang('o_promotion.num_activity'), request::referrer(), 'success');
        }
    }
    
    public function uploadform()
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
            

            $picture_max_size = 0;
            $picture_types = array ();
            $picture_attach = Kohana::config('attach.productPicAttach');
            $picture_max_size = $picture_attach['fileSizePreLimit'] / 1024 / 1024;
            if(!preg_match('/^\d+$/', $picture_max_size)){
                $picture_max_size = number_format($picture_max_size, 2);
            }
            $picture_types = $picture_attach['allowTypes'];
            foreach($picture_types as $idx => $item){
                $picture_types[$idx] = strtolower($item);
            }
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code'] = 200;
            $return_struct['msg'] = 'Sucess';
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
                $content = new View('promotion/' . __FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                $this->template->content->picture_types = $picture_types;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                $this->template->content->picture_max_size = $picture_max_size;
                $this->template->content->picture_types = $picture_types;
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
    
    public function upload()
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
            $request_data = $this->input->post();
            
            // 上传的表单域名字
            $attach_field = 'promotionActivityImg';
            // 附件应用类型
            $attach_app_type = 'productPicAttach';
            // 如果有上传请求
            if(!page::issetFile($attach_field)){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
            $title = array ();
            
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
                        throw new MyRuntimeException(Kohana::lang('o_promotion.file_not_uploaded') . $index, 400);
                    }
                    $file_size_current = filesize($_FILES[$attach_field]['tmp_name'][$index]);
                    if($attach_setup['fileSizePreLimit'] > 0 && $file_size_current > $attach_setup['fileSizePreLimit']){
                        throw new MyRuntimeException(Kohana::lang('o_promotion.file_size_prelimit') . $attach_setup['fileSizePreLimit'] . Kohana::lang('o_promotion.index') . $index . Kohana::lang('o_promotion.size') . $file_size_current, 400);
                    }
                    $file_type_current = FALSE;
                    $file_type_current === FALSE && page::getImageType($_FILES[$attach_field]['tmp_name'][$index]); // 尝试通过图片类型判断
                    $file_type_current === FALSE && $file_type_current = page::getFileType($attach_field, $index); // 尝试通过Mime类型判断
                    $file_type_current === FALSE && $file_type_current = page::getPostfix($attach_field, $index); // 尝试通过后缀截取
                    if(!empty($attach_setup['allowTypes']) && !in_array($file_type_current, $attach_setup['allowTypes'])){
                        throw new MyRuntimeException(Kohana::lang('o_promotion.file_type_invalid') . $index, 400);
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
                }else{
                    throw new MyRuntimeException(Kohana::lang('o_product.pic_upload_failed'), 400);
                }
            }
            if($attach_setup['fileCountLimit'] > 0 && $file_count_total > $attach_setup['fileCountLimit']){
                throw new MyRuntimeException(Kohana::lang('o_promotion.file_count_limit') . $attach_setup['fileCountLimit'], 400);
            }
            if($attach_setup['fileSizeTotalLimit'] > 0 && $file_size_total > $attach_setup['fileSizeTotalLimit']){
                throw new MyRuntimeException(Kohana::lang('o_promotion.file_size_total_limit') . $attach_setup['fileSizeTotalLimit'] . Kohana::lang('o_promotion.size') . $file_size_total, 400);
            }
            // 预备一些数据 当前时间戳
            /*$timestamp_current = time();
            $src_ip_address = $this->input->ip_address();
            $attach_meta = array (
                'siteId' => $site_id, 
                'siteDomain' => $site_domain 
            );
            
            // 调用附件服务
            require_once (Kohana::find_file('vendor', 'phprpc/phprpc_client', TRUE));
            !isset($attachmentService) && $attachmentService = new PHPRPC_Client(Kohana::config('phprpc.remote.Attachment.host'));
            !isset($phprpcApiKey) && $phprpcApiKey = Kohana::config('phprpc.remote.Attachment.apiKey');*/
            
            // 遍历所有的上传meta域
            /*foreach($file_meta_data as $index => $file_meta){
                $attachment_data_original = array (
                    'filePostfix' => $file_meta['type'], 
                    'fileMimeType' => $file_meta['mime'], 
                    'fileSize' => $file_meta['size'], 
                    'fileName' => $file_meta['name'], 
                    'srcIp' => $src_ip_address, 
                    'attachMeta' => json_encode($attach_meta), 
                    'createTimestamp' => $timestamp_current, 
                    'modifyTimestamp' => $timestamp_current 
                );
                //                $attachment_data_thumb = $attachment_data_original;
                //                $attachment_data_thumb['filePostfix'] = $file_meta['thumb']['type'];
                //                $attachment_data_thumb['fileMimeType'] = $file_meta['thumb']['mime'];
                //                $attachment_data_thumb['fileSize'] = $file_meta['thumb']['size'];
                //                
                // 调用后端添加附件信息，并调用存储服务存储文件
                $args_org = array (
                    $attachment_data_original 
                );
                $sign_org = md5(json_encode($args_org) . $phprpcApiKey);
                $attachment_original_id = $attachmentService->phprpc_addAttachmentFileData($attachment_data_original, @file_get_contents($file_meta['tmpfile']), $sign_org);
                
                //                $args_thumb = array($attachment_data_thumb);
                //                $sign_thumb = md5(json_encode($args_thumb).$phprpcApiKey);
                //                $attachment_thumb_id = $attachmentService->phprpc_addAttachmentFileData($attachment_data_thumb,@file_get_contents($file_meta['thumb']['tmpfile']),$sign_thumb);
                //                
            	if (!is_numeric($attachment_original_id))
                {
                	throw new MyRuntimeException(Kohana::lang('o_product.phprpc_pic_save_failed'), 400);
                }
                //                $attribute_image = array (
                //                    'attach_id' => $attachment_original_id, 
                //                    'title' => isset($title[$index]) ? strip_tags(trim($title[$index])) : '' 
                //                );
                $promotionAImage['picurl'] = AttributeService::get_attach_url($attachment_original_id, 'o');
                $return_data['picurl'] = $promotionAImage['picurl'];
                $return_data['attachId'] = $attachment_original_id;
                //                $return_data['meta'] = implode('|', $attribute_image);
                //                 清理临时文件
                @unlink($file_meta['thumb']['tmpfile']);     
            }*/
            $file_meta = $file_meta_data[0];
            $AttService = AttService::get_instance($this->img_dir_name);
            $img_id = $AttService->save_default_img($file_meta['tmpfile']);
            if(!$img_id){
            	throw new MyRuntimeException(Kohana::lang('o_product.phprpc_pic_save_failed'), 400);
            }
            $return_data['attachId'] = $img_id;
            $return_data['picurl'] = $AttService->get_img_url($img_id);
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
                $content = new View('promotion/' . __FUNCTION__);
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
            $return_struct['action'] = array (
                'type' => 'location', 
                'url' => url::base() . 'promotion/promotion_activity/uploadform' 
            );
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
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
}
