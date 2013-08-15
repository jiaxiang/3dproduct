<?php  defined('SYSPATH') OR die("No direct access allowed.");

class Cpn_promotion_Controller extends Template_Controller
{

    /*
	public function add()
    {
        exit('Cpn_promotion add');
        // 收集请求数据
        $request_data = $this->input->get();
        $coupon_id = $this->input->get('id');
        $site_id = $this->input->get('site_id');

        $site_id_list = role::check('promotion_coupon');

        // 权限验证
        if(empty($site_id_list))
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }
        // 权限验证
        if(!in_array($site_id,$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }     

        // 模板输出
        $this->template->content                     = new View("promotion/add_cpn_promotion");

        // 变量绑定
        $this->template->content->promotion_scheme_id		 = $request_data['id'];
        $this->template->content->promotion_schemes = Mycoupon_scheme::instance()->index(array('where' => array('disabled' => 0)));
        // 当前应用专用数据
        $this->template->content->site_list          = Mysite::instance()->select_list($site_id_list);
        $this->template->content->coupon_id = $coupon_id;
        $this->template->content->site_id = $site_id;
    }
    */

    /**
     * Show parameters form for a promotion rule
     */
    public function add_next()
    {
        role::check('promotion_coupon');
        // 收集请求数据
        $request_data = $this->input->get();
        $coupon_id = $this->input->get('coupon_id');
        $cpns_id = $this->input->get('cpns_id');

        if(!$cpns_id)
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');	
        }

        // 模板输出
        $this->template->content = new View("promotion/add_cpn_promotion_".$cpns_id);
        $this->template->content->cpns_id       = $cpns_id;
        $this->template->content->coupon_id     = $coupon_id;
        $this->template->content->coupon_schemes= Mycoupon_scheme::instance($cpns_id)->get();
        $this->template->content->coupon        = Mycoupon::instance($coupon_id)->get();

        // Extra process needed for IDs
        switch( $cpns_id ) {
        case 3: // discount_cart_user_buy
            //$all_ids = Myuser::instance()->lists(array('where' => array()));
            $this->template->content->users_area = new View("promotion/partial/add_users");
            //$this->template->content->users_area->all_ids = $all_ids;
            $this->template->content->users_area->relatedUsers="relatedUsers";
            $this->template->content->users_area->related_idsu="related_ids";
            $this->template->content->users_area->addUser="addUser";
            //$this->template->content->all_ids = $all_ids;
//dialog start
            $this->template->content->dialog = new View("promotion/partial/dialog_user");
            $userDiaStruct = array(
                'dialog_form'    => 'dialog-form-user',
                'userSearchType' => 'userSearchType',
                'userKeyword'    => 'userKeyword',
                'userSearchbtn'  => 'userSearchbtn',
                'userTable'      => 'userTable',
                'checkAll'       => 'checkAll',
                'users'          => 'users',
            );
            $this->template->content->dialog->userDiaStruct = $userDiaStruct;
            //$this->template->content->dialog->all_ids = $all_ids;
//显示字段设置
            $js_user_field ='var user_field = {"用户登录邮箱":"email"};';
            $user_field  =array('email'=>"用户登录邮箱");
            $this->template->content->js_user_field = $js_user_field;
            $this->template->content->users_area->user_field  = $user_field;
//dialog end
            break;

        case 4: // discount_cart_buy_product
            $all_ids = array();
            $related_data = Session::instance()->get('sessionErrorData');
            if($related_data && isset($related_data['related_ids'])){
            	$related_ids = $related_data['related_ids'];
                $request_struct = array(
                    'where'=>array(
                        'on_sale'       => 1,
                		'id'			=> $related_ids,
                       // 'active'        => 1,
                    ),
                    'orderby'=>array(
                        'name_manage'=>'ASC'
                    )
                );
                $all_ids = ProductService::get_instance()->index($request_struct);
                $this->add_category_name($all_ids);
            }
            $this->template->content->products_area = new View("promotion/partial/add_products");
            $this->template->content->products_area->all_ids = $all_ids;
//dialog start
            $this->template->content->dialog = new View("promotion/partial/dialog_product");
            $productDiaStruct = array(
                'dialog_form'       => 'dialog-form-product',
                'productSearchType' => 'productSearchType',
                'productKeyword'    => 'productKeyword',
                'productSearchbtn'  => 'productSearchbtn',
                'productTable'      => 'productTable',
                'checkAll'          => 'checkAll',
                'products'          => 'products',
            );
            $this->template->content->dialog->productDiaStruct = $productDiaStruct;
            //$this->template->content->dialog->all_ids = $all_ids;
//显示字段设置
            $js_product_field ='var product_field = {\'SKU\':"sku","中文名称":"name_manage","商品名称":"title","分类名称":"category_name"};';
            $product_field  =array('sku'=>'SKU',"name_manage"=>"中文名称","title"=>"商品名称","category_name"=>"分类名称");
            $this->template->content->js_product_field = $js_product_field;
            $this->template->content->products_area->product_field  = $product_field;
//dialog end

            break;

        case 5: // discount_category
            $request_struct = array(
                'where'=>array(
                    //'virtual'       => 0,
                ),
                'orderby'=>array(
                    'title_manage'=>'ASC'
                )
            );
            $all_ids = CategoryService::get_instance()->index($request_struct);
            $all_ids = promotion::convert($all_ids);
//dialog start
            $related_ids = 'related_ids';
            $this->template->content->dialog = new View("promotion/partial/dialog_category");
            $this->template->content->dialog->related_ids_name = $related_ids;
            $categoryDiaStruct = array(
                'dialog_form'        => 'dialog-form',
                'categoryTable'      => 'categoryTable',
            );
            $this->template->content->dialog->categoryDiaStruct = $categoryDiaStruct;
//显示字段设置
            $category_field  =array("title_manage"=>"中文名称","title"=>"分类名");
            $tree = promotion::generate_tree($all_ids,1,0,$related_ids,$category_field,'checkAll');
            $this->template->content->dialog->tree = $tree;
//dialog end
            break;
        }
    }

    public function do_add()
    {
        role::check('promotion_coupon');
        // 收集请求数据
        $request_data = $this->input->post();
        $session = Session::instance();
        $session->set_flash('sessionErrorData',$request_data);
        
        //标签过滤
        tool::filter_strip_tags($request_data);
        
        $request_data['related_id'] = '';

        //促销规则时间效验
        $time_begin     = strtotime($request_data['time_begin']);
        $time_end       = strtotime($request_data['time_end']);

        if($time_begin>$time_end)
        {
            remind::set(Kohana::lang('o_promotion.begin_time_over_end'),request::referrer(),'error');
        }  
        $request_data['time_end'] = date('Y-m-d H:i:s',strtotime($request_data['time_end'])+24*3600);
        $time_begin     = strtotime($request_data['time_begin']);
        $time_end       = strtotime($request_data['time_end']);
        //促销规则时间必须在促销活动时间内
        $coupon     = Mycoupon::instance($request_data['cpn_id'])->get();
        if(!$coupon['id'])
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }
        $cpn_time_begin        = strtotime($coupon['cpn_time_begin']); 
        $cpn_time_end          = strtotime($coupon['cpn_time_end']); 
        if($cpn_time_begin>$time_begin||$cpn_time_end<$time_end)
        {
            remind::set(Kohana::lang('o_promotion.cpn_out_time_range'),request::referrer(),'error');		
        }
        //验证打折值与订单优惠条件
        if(isset($request_data['discount_type'])){
            if(isset($request_data['price']) && (!preg_match('/^\d{1,12}(\.\d{0,3})?$/',$request_data['price']) || $request_data['price']<0))
            {
                remind::set(Kohana::lang('o_promotion.cpn_price_error'),request::referrer(),'error');
            }
            if($request_data['discount_type']==0 && $request_data['price']>1){
                remind::set(Kohana::lang('o_promotion.cpn_price_error'),request::referrer(),'error');
            }
        }
        if((isset($request_data['money_from']) && (!preg_match('/^\d{1,12}(\.\d{0,3})?$/',$request_data['money_from']) || $request_data['money_from']<0))
           ||(isset($request_data['money_to']) && (!preg_match('/^\d{1,12}(\.\d{0,3})?$/',$request_data['money_to']) || $request_data['money_to']<0))
          ||(isset($request_data['money_from']) && $request_data['money_from'] >= $request_data['money_to'])
           ){
            remind::set(Kohana::lang('o_promotion.cpn_money_error'),request::referrer(),'error');
        }
        if((isset($request_data['quantity_from']) && (!preg_match('/^\d+$/',$request_data['quantity_from']) || $request_data['quantity_from']<0))
           || (isset($request_data['quantity_to']) && (!preg_match('/^\d+$/',$request_data['quantity_to']) || $request_data['quantity_to']<0))
          ||(isset($request_data['quantity_from']) && $request_data['quantity_from'] >= $request_data['quantity_to'])
           ){
            remind::set(Kohana::lang('o_promotion.buy_quantity_error'),request::referrer(),'error');
        }
        switch($request_data['cpns_id']) {
        case 3: // discount_cart_user_buy
        	isset($info) || $info = "请选择用户";
        case 4: // discount_cart_buy_product
        	isset($info) ||$info = "请选择商品";
        case 5: // discount_category
        	isset($info) ||$info = "请选择分类";
            $related_ids = $this->input->post('related_ids');
            if(empty($related_ids)){
                remind::set($info,request::referrer(),'error');
            }
            // enclose selected category ids with comma
            $request_data['related_ids'] = Mycpn_promotion::enclose_ids($related_ids, ',');
            break;
        }
        
        if ( Mycpn_promotion::instance()->add($request_data) ) {
        	$session->delete('sessionErrorData');
            remind::set(Kohana::lang('o_global.add_success'), 'promotion/coupon', 'success');
        } else {
            remind::set(Kohana::lang('o_global.add_error'), request::referrer(), 'error');
        }
    }

    public function edit()
    {
        role::check('promotion_coupon');
        
        // 收集请求数据
        $request_data = $this->input->get();
        
        $promotion          = Mycpn_promotion::instance($request_data['id'])->get();
        $cpns_id 	        = $promotion['cpns_id'];
        if(!$cpns_id)
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');	
        }
        $promotion['money_from'] = sprintf('%.3f',$promotion['money_from']);
        $promotion['money_to'] = sprintf('%.3f',$promotion['money_to']);
        
        $coupon_schemes     = Mycoupon_scheme::instance($cpns_id)->get();
        
        //实例化session
        $session      = Session::instance();
        $this->template->content = new View("promotion/edit_cpn_promotion_".$cpns_id);

        // extra process needed for IDs
        switch( $cpns_id ) {
        case 3: // discount_cart_user_buy
            $all_ids = array();
            if(isset($promotion['related_ids']))
            {
                $query_struct = array(
                    'where'=>array(
                        'id' => explode(',',$promotion['related_ids'])
                    )                
                );
                $all_ids = Myuser::instance()->lists($query_struct);
            }            
            $this->template->content->users_area = new View("promotion/partial/edit_users");
            if($session->get('sessionErrorData') === false){
                $this->template->content->users_area->related_ids = explode(',', $promotion['related_ids']);
            }
            $this->template->content->users_area->thing = 'users';
            $this->template->content->thing = 'users';
            
            $this->template->content->users_area->all_ids = $all_ids;
            $this->template->content->users_area->relatedUsers="relatedUsers";
            $this->template->content->users_area->related_idsu="related_ids";
            $this->template->content->users_area->addUser="addUser";
            //$this->template->content->all_ids = $all_ids;
//dialog start
            $this->template->content->dialog = new View("promotion/partial/dialog_user");
            $userDiaStruct = array(
                'dialog_form'    => 'dialog-form-user',
                'userSearchType' => 'userSearchType',
                'userKeyword'    => 'userKeyword',
                'userSearchbtn'  => 'userSearchbtn',
                'userTable'      => 'userTable',
                'checkAll'       => 'checkAll',
                'users'          => 'users',
            );
            $this->template->content->dialog->userDiaStruct = $userDiaStruct;
            //$this->template->content->dialog->all_ids = $all_ids;
//显示字段设置
            $js_user_field ='var user_field = {"用户登录邮箱":"email"};';
            $user_field  =array('email'=>"用户登录邮箱");
            $this->template->content->js_user_field = $js_user_field;
            $this->template->content->users_area->user_field  = $user_field;
//dialog end
            break;

        case 4: // discount_cart_buy_product
            if($session->get('sessionErrorData') === false){
//                $this->template->content->products_area->related_ids = explode(',', $promotion['related_ids']);
                $related_ids = explode(',', $promotion['related_ids']);
            }else{
            	$related_ids = $session->get('sessionErrorData');
            	$related_ids = $related_ids['related_ids'];
            }
            $request_struct = array(
                'where'=>array(
                    'on_sale'       => 1,
            		'id'			=> $related_ids,
                   // 'active'        => 1,
                ),
                'orderby'=>array(
                    'name_manage'=>'ASC'
                )
            );
            $all_ids = ProductService::get_instance()->index($request_struct);
            $this->add_category_name($all_ids);
            
            $this->template->content->products_area = new View("promotion/partial/edit_products");
            $this->template->content->products_area->all_ids = $all_ids;

            $this->template->content->products_area->thing = 'products';
            $this->template->content->thing = 'products';
            
//dialog start
            $this->template->content->dialog = new View("promotion/partial/dialog_product");
            $productDiaStruct = array(
                'dialog_form'       => 'dialog-form-product',
                'productSearchType' => 'productSearchType',
                'productKeyword'    => 'productKeyword',
                'productSearchbtn'  => 'productSearchbtn',
                'productTable'      => 'productTable',
                'checkAll'          => 'checkAll',
                'products'          => 'products',
            );
            $this->template->content->dialog->productDiaStruct = $productDiaStruct;
//显示字段设置
            $js_product_field ='var product_field = {\'SKU\':"sku","中文名称":"name_manage","商品名称":"title","分类名称":"category_name"};';
            $product_field  =array('sku'=>'SKU',"name_manage"=>"中文名称","title"=>"商品名称","category_name"=>"分类名称");
            $this->template->content->js_product_field = $js_product_field;
            $this->template->content->products_area->product_field  = $product_field;
//dialog end

            break;

        case 5: // discount_category
            $request_struct = array(
                'where'=>array(
                ),
                'orderby'=>array(
                    'title_manage'=>'ASC'
                )
            );
            $all_ids = CategoryService::get_instance()->index($request_struct);
            $all_ids = promotion::convert($all_ids);
            
//dialog start
            $related_ids = 'related_ids';
            $this->template->content->dialog = new View("promotion/partial/dialog_category");
            $this->template->content->dialog->related_ids_name = $related_ids;
            if($session->get('sessionErrorData') === false){
                $this->template->content->dialog->related_ids = explode(',', $promotion['related_ids']);
            }
            $categoryDiaStruct = array(
                'dialog_form'        => 'dialog-form',
                'categoryTable'      => 'categoryTable',
            );
            $this->template->content->dialog->categoryDiaStruct = $categoryDiaStruct;
            $this->template->content->dialog->all_ids = $all_ids;
//显示字段设置
            $category_field  =array("title_manage"=>"中文名称","title"=>"分类名");
            $tree = promotion::generate_tree($all_ids,1,0,$related_ids,$category_field,'checkAll');
            $this->template->content->dialog->tree = $tree;
//dialog end
            break;
        }

        // 变量绑定
        $this->template->content->promotion			= $promotion;
        $this->template->content->coupon_schemes    = $coupon_schemes;
        $this->template->content->coupon            = Mycoupon::instance($promotion['cpn_id'])->get();
    }

    public function do_edit()
    {
        role::check('promotion_coupon');
        // 收集请求数据
        $request_data   = $this->input->post();
        $session = Session::instance();
        $session->set_flash('sessionErrorData',$request_data);
        
        //标签过滤
        tool::filter_strip_tags($request_data);

        $cpn_promotion  = Mycpn_promotion::instance($request_data['id'])->get();
        $cpns_id        = $cpn_promotion['cpns_id'];
        if(!$cpn_promotion['id'])
        {
            remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
        }
        
        //时间处理
        if(strtotime($request_data['time_end']) < strtotime($request_data['time_begin']))
        {
            remind::set(Kohana::lang('o_promotion.begin_time_over_end'),request::referrer(),'error');
        }
        $request_data['time_end'] = date("Y-m-d H:i:s",strtotime($request_data['time_end'])+24*3600);
    //促销规则时间必须在促销活动时间内
        $coupon     = Mycoupon::instance($request_data['cpn_id'])->get();
        //var_dump($coupon);exit;
        if(!$coupon['id'])
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');
        }
        $cpn_time_begin        = strtotime($coupon['cpn_time_begin']); 
        $cpn_time_end           = strtotime($coupon['cpn_time_end']); 
        if($cpn_time_begin>strtotime($request_data['time_begin'])||$cpn_time_end<strtotime($request_data['time_end']))
        {
            remind::set(Kohana::lang('o_promotion.cpn_out_time_range'),request::referrer(),'error');      
        }
        // extra process needed for IDs
    //验证打折值与订单优惠条件
        if(isset($request_data['discount_type'])){
            if(isset($request_data['price']) && (!preg_match('/^\d{1,12}(\.\d{0,3})?$/',$request_data['price']) || $request_data['price']<0))
            {
                remind::set(Kohana::lang('o_promotion.cpn_price_error'),request::referrer(),'error');
            }
            if($request_data['discount_type']==0 && $request_data['price']>1){
               remind::set(Kohana::lang('o_promotion.cpn_price_error'),request::referrer(),'error');
            }
        }
        if((isset($request_data['money_from']) && (!preg_match('/^\d{1,12}(\.\d{0,3})?$/',$request_data['money_from']) || $request_data['money_from']<0))
           ||(isset($request_data['money_to']) && (!preg_match('/^\d{1,12}(\.\d{0,3})?$/',$request_data['money_to']) || $request_data['money_to']<0))
          ||(isset($request_data['money_from']) && $request_data['money_from'] >= $request_data['money_to'])
           ){
            remind::set(Kohana::lang('o_promotion.cpn_money_error'),request::referrer(),'error');
        }
        if((isset($request_data['quantity_from']) && (!preg_match('/^\d+$/',$request_data['quantity_from']) || $request_data['quantity_from']<0))
           || (isset($request_data['quantity_to']) && (!preg_match('/^\d+$/',$request_data['quantity_to']) || $request_data['quantity_to']<0))
          ||(isset($request_data['quantity_from']) && $request_data['quantity_from'] >= $request_data['quantity_to'])
           ){
            remind::set(Kohana::lang('o_promotion.buy_quantity_error'),request::referrer(),'error');
        }
        switch( $cpns_id ) {
        case 3: // discount_cart_user_buy
            isset($info) || $info = "请选择用户";
        case 4: // discount_cart_buy_product
            isset($info) ||$info = "请选择货品";
        case 5: // discount_category
            isset($info) ||$info = "请选择分类";
            $related_ids = $this->input->post('related_ids');
            if(empty($related_ids)){
                remind::set($info,request::referrer(),'error');
            }
            // enclose selected category ids with comma
            $request_data['related_ids'] = Mycpn_promotion::enclose_ids($related_ids, ',');
            
            break;
        }
        
        //print_r($request_data);exit;
        if($promotion_id = Mycpn_promotion::instance()->edit($request_data))
        {
            $session->delete('sessionErrorData');
            remind::set(Kohana::lang('o_global.update_success'),"promotion/coupon",'success');
        }
        else
        { 
            remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
        }
    }
    
    
    public function search_user()
    {
        role::check('promotion_promotion');
        // 收集请求数据
        $request_data = $this->input->get();

        $request_struct_current = array(
            'where'    => array(
                'active'  => 1,
            ),
            'like'     => array(),
            'limit'    => array(
                'per_page'  =>6,
                'offset'    =>0,
            ),
            'orderby'  => array(
                'id'    => 'DESC',
            )
        );
        $record_data = array();
        if(isset($request_data['page']) && !empty($request_data['page']))
        {
            $request_struct_current['limit']['offset'] = ($request_data['page']-1)*$request_struct_current['limit']['per_page'];
            $record_data['page'] = $request_data['page'];
        }
        // 当前支持的查询业务逻辑
        if(isset($request_data['type']) && isset($request_data['keyword']) && !empty($request_data['keyword']))
        {
            switch ($request_data['type']){
            case 'email':
                $request_struct_current['like'][$request_data['type']] = trim($request_data['keyword']);
                break;
            case 'firstname':
                $request_struct_current['like'][$request_data['type']]  = trim($request_data['keyword']);
                break;
            case 'name':
            	$name = trim($request_data['keyword']);
            	$nameArr = explode(' ',$name);
            	$request_struct_current['like']['firstname'] = trim($nameArr[0]);
            	$request_struct_current['like']['lastname']  = trim($nameArr[1]);
            	break;
            }
        }
    
        $users = Myuser::instance()->lists($request_struct_current);
        
        $returnData['content'] = $users;
        $returnData['page'] = isset($record_data['page'])?$record_data['page']:1;
        $returnData['count'] = ceil(Myuser::instance()->query_count($request_struct_current)/$request_struct_current['limit']['per_page']);
        //header('Content-Type: text/javascript; charset=UTF-8');
        echo json_encode($returnData);
        exit;
    }
    public function add_category_name(& $products)
    {
    	if (!empty($products))
        {
	        $cids = array();
	        foreach ($products as $record)
	        {
	        	$cids[$record['category_id']] = TRUE;
	        }
	        $categories = array();
        	$query_struct = array('where' => array(
        		'id' => array_keys($cids),
        	));
        	foreach ((array)CategoryService::get_instance()->query_assoc($query_struct) as $record)
        	{
        		$categories[$record['id']] = $record['title_manage'];
        	}
        	foreach ($products as $index => $record)
        	{
        		$record['category_name'] = isset($categories[$record['category_id']]) ? $categories[$record['category_id']] : '';
        		$products[$index]        = $record;
        	}
        }
    }
    /*
    public function do_delete()
    {
        // 收集请求数据
        $request_data = $this->input->get();

        $site_id_list = role::check('promotion_coupon');
        $promotion    = Mycpn_promotion::instance($request_data['id'])->get();

        // 权限验证
        if(empty($site_id_list))
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }
        if(!in_array($promotion['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');
        }        

        if(Mycpn_promotion::instance()->delete($promotion['id']))
        {
            remind::set(Kohana::lang('o_global.delete_success'),request::referrer(),'success');
        }
        else
        { 
            remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
        }
    }
     */

}
