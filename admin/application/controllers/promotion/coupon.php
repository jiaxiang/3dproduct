<?php defined('SYSPATH') or die("No direct access allowed.");

class Coupon_Controller extends Template_Controller {
    public function index()
    {
        role::check('promotion_coupon');
        
        // 初始化默认查询条件
        $request_struct_current = array (
            'where' => array (
                'is_front' => 0  //默认只显示在后台管理员添加的优惠券
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
                'cpn_name' => 'ASC' 
            ), 
            3 => array (
                'cpn_name' => 'DESC' 
            ), 
            4 => array (
                'id' => 'ASC' 
            ), 
            5 => array (
                'id' => 'DESC' 
            ), 
            6 => array (
                'cpn_prefix' => 'ASC' 
            ), 
            7 => array (
                'cpn_prefix' => 'DESC' 
            ), 
            8 => array (
                'cpn_type' => 'ASC' 
            ), 
            9 => array (
                'cpn_type' => 'DESC' 
            ), 
            10 => array (
                'disabled' => 'ASC' 
            ), 
            11 => array (
                'disabled' => 'DESC' 
            ), 
            12 => array (
                'cpn_gen_quantity' => 'ASC' 
            ), 
            13 => array (
                'cpn_gen_quantity' => 'DESC' 
            ), 
            14 => array (
                'cpn_time_begin' => 'ASC' 
            ), 
            15 => array (
                'cpn_time_begin' => 'DESC' 
            ), 
            16 => array (
                'cpn_time_end' => 'ASC' 
            ), 
            17 => array (
                'cpn_time_end' => 'DESC' 
            ) 
        );
        $orderby = controller_tool::orderby($orderby_arr);
        // 排序处理 
        if(isset($request_data['orderby']) && is_numeric($request_data['orderby'])){
            $request_struct_current['orderby'] = $orderby;
        }
        // 每页条目数
        controller_tool::request_per_page($request_struct_current, $request_data);
        //显示前台添加的优惠券
        if(isset($request_data['is_front'])){
            $request_struct_current['where']['is_front'] = intval($request_data['is_front']);
            $request_data['is_front'] = $request_struct_current['where']['is_front'];
        }
        // 当前支持的查询业务逻辑
        if(isset($request_data['type']) && isset($request_data['keyword']) && !empty($request_data['keyword'])){
            switch($request_data['type']){
                case 'id':
                    $request_struct_current['where'][$request_data['type']] = intval(trim($request_data['keyword']));
                    header("Charset=utf-8");
                    $request_data['keyword'] = $request_struct_current['where'][$request_data['type']];
                    break;
                case 'cpn_name':
                    $request_struct_current['like'][$request_data['type']] = trim($request_data['keyword']);
                    $request_data['keyword'] = $request_struct_current['like'][$request_data['type']];
                    break;
                case 'cpn_prefix':
                    $request_struct_current['like'][$request_data['type']] = trim($request_data['keyword']);
                    $request_data['keyword'] = $request_struct_current['like'][$request_data['type']];
                    break;
            }
        }
        
        $count = Mycoupon::instance()->count($request_struct_current);
        
        // 模板输出 分页
        $this->pagination = new Pagination(array (
            'total_items' => $count, 
            'items_per_page' => $request_struct_current['limit']['per_page'] 
        ));
        
        $request_struct_current['limit']['offset'] = $this->pagination->sql_offset;
        
        $coupon_list = Mycoupon::instance()->lists($request_struct_current);
        
        $i = 0;
        
        foreach($coupon_list as $key => $rs){
            //$coupon_list[$key] = $value;
            //$coupon_list[$key]['site'] = Mysite::instance($rs['site_id'])->get();
            //$used_coupon_name = !isset(${'used_coupons' . $rs['site_id']}) && ${'used_coupons' . $rs['site_id']} = Myused_coupon::instance()->get_used_coupon_codes($rs['site_id']);
            
            $request_struct = array (
                'where' => array (
                    'cpn_id' => $rs['id'] 
                ) 
            );
            $cpn_promotions = Mycpn_promotion::instance()->lists($request_struct);
            $count = count($cpn_promotions);
            //打折只能有一条规则，如果没有（添加时错误）或多添加，则删除这条优惠方案
            if($count != 1){
                //Mycpn_promotion::instance()->delete_by_couponid($rs['id']);
                Mycoupon::instance()->delete($rs['id']);
                unset($coupon_list[$key]);
            }else{
                $cpn_promotion = array_shift($cpn_promotions);
                $coupon_scheme = Mycoupon_scheme::instance($cpn_promotion['cpns_id'])->get();
                $coupon_list[$key]['cpn_promotion'] = $cpn_promotion;
                $coupon_list[$key]['coupon_scheme'] = $coupon_scheme;
            }
        
        }
        // 模板输出
        $this->template->content = new View("promotion/coupon_list");
        
        // 变量绑定
        $this->template->content->coupon_list = $coupon_list;
        $this->template->content->count = $count;
        
        $this->template->content->request_data = $request_data;
    }
    
    public function add()
    {
        role::check('promotion_coupon');
    	$session = Session::instance();
    	if(!($session->get('sessionErrorData')))
    	{
    	   $session->keep_flash('sessionErrorData');
    	}
        
        // 收集请求数据
        $request_data = $this->input->get();
        
        // 模板输出
        $this->template->content = new View("promotion/add_coupon");
        
        // 当前应用专用数据
        $this->template->content->promotion_schemes = Mycoupon_scheme::instance()->lists(array (
            'where' => array (
                'disabled' => 0 
            ) 
        ));
    
    }
    
    public function do_add()
    {
        role::check('promotion_coupon');
        // 收集请求数据
        $request_data = $this->input->post();
        $session = Session::instance();
        $session->set_flash('sessionErrorData', $request_data);
        
        //标签过滤
        tool::filter_strip_tags($request_data);
        
        if(!isset($request_data['with_pmt'])){
            $request_data['with_pmt'] = 0;
        }
        $request_data['cpn_prefix'] = $request_data['cpn_type'] . $request_data['cpn_prefix'];
        
        //$request_data['cpn_gen_quantity']       = $request_data['cpn_gen_quantity'.$this->input->post('cpn_type')];
        //unset($request_data['cpn_gen_quantityA']);
        //unset($request_data['cpn_gen_quantityB']);
        
        $request_data['cpn_key'] = mt_rand();
        //时间处理
        if(strtotime($request_data['cpn_time_end'])+ 24 * 3600 < time()){
            remind::set(Kohana::lang('o_promotion.time_end'), request::referrer(), 'error');
        }
        if(strtotime($request_data['cpn_time_end']) < strtotime($request_data['cpn_time_begin'])){
            remind::set(Kohana::lang('o_promotion.begin_time_over_end'), request::referrer(), 'error');
        }
        $request_data['cpn_time_end'] = date("Y-m-d H:i:s", strtotime($request_data['cpn_time_end']) + 24 * 3600);
        if(Mycoupon::instance()->check_cpn_prefix($request_data['cpn_prefix'])){
            remind::set(Kohana::lang('o_promotion.cpn_prefix_has_exist'), request::referrer(), 'error');
        }
        
        if($coupon_id = Mycoupon::instance()->add($request_data)){
            
            url::redirect("promotion/cpn_promotion/add_next?cpns_id=" . $request_data['cpns_id'] . "&coupon_id=" . $coupon_id);
        }else{
            remind::set(Kohana::lang('o_global.add_error'), 'promotion/coupon', 'error');
        }
    }
    
    public function edit()
    {
        role::check('promotion_coupon');
        // 收集请求数据
        $request_data = $this->input->get();
        
        $coupon = Mycoupon::instance($request_data['id'])->get();
        $cpn_promotion = Mycpn_promotion::instance()->get_by_couponid($request_data['id']);
        $coupon['cpns_id'] = $cpn_promotion['cpns_id'];
        $coupon['cpnp_id'] = $cpn_promotion['id'];
        
        // 模板输出
        $this->template->content = new View("promotion/edit_coupon");
        
        // 变量绑定
        $this->template->content->coupon = $coupon;
        $this->template->content->promotion_schemes = Mycoupon_scheme::instance()->lists(array (
            'where' => array (
                'disabled' => 0 
            ) 
        ));
    }
    
    public function do_edit()
    {
        role::check('promotion_coupon');
        // 收集请求数据
        $coupon_id = $this->input->post('id');
        $request_data = $this->input->post();
        $session = Session::instance();
        $session->set_flash('sessionErrorData', $request_data);
        
        //标签过滤
        tool::filter_strip_tags($request_data);
        
        $coupon = Mycoupon::instance($coupon_id)->get();
        if(!isset($request_data['with_pmt'])){
            $request_data['with_pmt'] = 0;
        }
        
        if(($this->input->post('cpn_type') == 'A') && ($coupon['cpn_key'] == '')){
            $request_data['cpn_key'] = mt_rand();
        }
        
        //时间处理
        if(strtotime($request_data['cpn_time_end'])+ 24 * 3600 < time()){
            remind::set(Kohana::lang('o_promotion.time_end'), request::referrer(), 'error');
        }
        if(strtotime($request_data['cpn_time_end']) < strtotime($request_data['cpn_time_begin'])){
            remind::set(Kohana::lang('o_promotion.begin_time_over_end'), request::referrer(), 'error');
        }
        $request_data['cpn_time_end'] = date("Y-m-d H:i:s", strtotime($request_data['cpn_time_end']) + 24 * 3600);

        if(Mycoupon::instance()->edit($request_data)){            
            $session->delete('sessionErrorData');
            url::redirect("promotion/cpn_promotion/edit?id=" . $request_data['cpnp_id']);
        }else{
            remind::set(Kohana::lang('o_promotion.update_cpn_error'), request::referrer(), 'error');
        }
    }
    
    public function do_delete()
    {
        role::check('promotion_coupon');
        // 收集请求数据
        $request_data = $this->input->get();
        
        $coupon = Mycoupon::instance($request_data['id'])->get();
        
        if(!$coupon['id']){
            remind::set(Kohana::lang('o_global.access_denied'), '/promotion/coupon' , 'error');
        }
        
        if(Mycoupon::instance()->delete($coupon['id'])){
            remind::set(Kohana::lang('o_global.delete_success'), '/promotion/coupon' , 'success');
        }else{
            remind::set(Kohana::lang('o_global.delete_error'), '/promotion/coupon' , 'error');
        }
    }
    
    public function download()
    {
        role::check('promotion_coupon');
        // 收集请求数据
        $request_data = $this->input->get();
        $coupon = Mycoupon::instance($request_data['id'])->get();
        
        // 权限验证
        if(!$coupon['id']){
            remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
        }
        if(!preg_match('/^\d+$/',$request_data['amount']) || $request_data['amount']>=10000 ||$request_data['amount']<=0)
        {
        	exit;
        }
        //$used_coupons = Myused_coupon::instance()->get_used_coupon_codes($coupons['site_id']);
        $coupons = Mycoupon::instance()->gen_coupons($coupon['id'], $request_data['amount']);
        $cpn_promotion = Mycpn_promotion::instance()->get_by_couponid($coupon['id']);
        $coupon_scheme = Mycoupon_scheme::instance($cpn_promotion['cpns_id'])->get();
        
        //CSV输出
        $rand_name = date('Y-m-d') . '_' . mt_rand();
        
        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Transfer-Encoding: binary ");
        header("Content-type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=coupons_$rand_name.csv");
        //header("Content-Disposition:attachment;filename={$filename}{$file}.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $output = ",,,,,\n";
        switch($cpn_promotion['cpns_id']){
            case 1:
            case 2:
                $output .= "订单开始金额,订单结束金额,,,,\n";
                $output .= "\${$cpn_promotion['money_from']},\${$cpn_promotion['money_to']},,,,\n";
                break;
            case 3:
                $related_ids = explode(',', trim($cpn_promotion['related_ids'], ','));
                $request_struct = array (
                    'where' => array (
                        'id' => $related_ids 
                    ), 
                    'orderby' => array (
                        'email' => 'ASC' 
                    ) 
                );
                $all_users = Myuser::instance()->lists($request_struct);
                $output .= "用户邮箱,姓名,,,,\n";
                foreach($all_users as $key => $rs){
                    $output .= "{$rs['email']},{$rs['firstname']} {$rs['lastname']},,,,\n";
                }
                break;
            
            case 4:
                $related_ids = explode(',', trim($cpn_promotion['related_ids'], ','));
                $request_struct = array (
                    'where' => array (
                        'id' => $related_ids 
                    ), 
                    'orderby' => array (
                        'name_manage' => 'ASC' 
                    ) 
                );
                $all_products = ProductService::get_instance()->index($request_struct);
                $output .= "商品SKU,名称,,,,\n";
                foreach($all_products as $key => $rs){
                    $output .= "{$rs['sku']},{$rs['name_manage']},,,,\n";
                }
                break;
            
            case 5:
                $related_ids = explode(',', trim($cpn_promotion['related_ids'], ','));
                $request_struct = array (
                    'where' => array (
                        'id' => $related_ids 
                    ), 
                    'orderby' => array (
                        'title_manage' => 'ASC' 
                    ) 
                );
                $all_categories = CategoryService::get_instance()->index($request_struct);
                $output .= "分类名称,,,,,\n";
                foreach($all_categories as $key => $rs){
                    $output .= "{$rs['title_manage']},,,,,\n";
                }
                break;
            case 6:
                $output .= "订单商品开始数量,订单商品结束数量,,,,\n";
                $output .= "{$cpn_promotion['quantity_from']},{$cpn_promotion['quantity_to']},,,,\n";
                break;
        }
        $output .= ",,,,,\n";
        $output .= "打折号,开始时间,结束时间,打折值,折扣类型,\n";
        foreach($coupons as $key => $rs){
            $output .= $rs . "," . $cpn_promotion['time_begin'] . "," . $cpn_promotion['time_end'] . ",";
            switch($cpn_promotion['discount_type']){
                case 0:
                    $output .= '百分比 ' . ($cpn_promotion['price'] * 100) . "%";
                    break;
                case 1:
                    $output .= '减去 $' . $cpn_promotion['price'];
                    break;
                case 2:
                    $output .= '减到 $' . $cpn_promotion['price'];
                    break;
            }
            $output .= ",";
            $output .= "{$coupon_scheme['cpns_memo']},\n";
        }
        
        echo iconv('UTF-8', 'gb2312', $output);
        exit();
    }
    
    public function do_delete_all()
    {
        role::check('promotion_coupon');
        $coupon_id_array = $this->input->post('id');
        
        if(!(is_array($coupon_id_array) && count($coupon_id_array))){
            remind::set(Kohana::lang('o_promotion.select_cpn'), request::referrer(), 'error');
        }
        
        $count = 0;
        $false_count = 0;
        
        foreach($coupon_id_array as $key => $coupon_id){
            // 验证 - 数据有效性
            $coupon = Mycoupon::instance($coupon_id)->get();
            
            if(!$coupon['id']){
                $false_count++;
                continue;
            }
            
            if(Mycoupon::instance()->delete($coupon_id)){
                $count++;
            }else{
                $false_count++;
            }
        }
        if($false_count){
            remind::set(Kohana::lang('o_promotion.have') . $false_count . Kohana::lang('o_promotion.num_cpn_cannot_delete'), request::referrer(), 'error');
        }else{
            remind::set(Kohana::lang('o_promotion.success_delete') . $count . Kohana::lang('o_promotion.num_cpn'), request::referrer(), 'success');
        }
    }
    
    public function search_product()
    {
        role::check('promotion_coupon');
        // 收集请求数据
        $request_data = $this->input->get();
        
        $request_struct_current = array (
            'where' => array (
            ), 
            'like' => array (), 
            'orderby' => array (
                'name' => 'ASC' 
            ) 
        );
        
        // 当前支持的查询业务逻辑
        if(isset($request_data['type']) && isset($request_data['keyword']) && !empty($request_data['keyword'])){
            switch($request_data['type']){
                case 'id':
                    $request_struct_current['where'][$request_data['type']] = intval(trim($request_data['keyword']));
                    break;
                case 'name_url':
                    $request_struct_current['where'][$request_data['type']] = trim($request_data['keyword']);
                    break;
                case 'name':
                    $request_struct_current['like'][$request_data['type']] = trim($request_data['keyword']);
                    break;
                case 'SKU':
                    $request_struct_current['where'][$request_data['type']] = trim($request_data['keyword']);
                    break;
            }
        }
        
        $products = Myproduct::instance()->lists($request_struct_current);
        
        //header('Content-Type: text/javascript; charset=UTF-8');
        echo json_encode($products);
        exit();
    }

}