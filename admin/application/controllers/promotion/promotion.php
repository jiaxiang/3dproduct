<?php  defined('SYSPATH') OR die("No direct access allowed.");

class Promotion_Controller extends Template_Controller
{
	public function add()
	{
        role::check('promotion_promotion');
        // 收集请求数据
        $request_data = $this->input->get();

        $pmta_id = $this->input->get('id');
        $pmts_id = $this->input->get('pmts_id');


        // 查询条件
        $request_struct = array(
            'where'=>array(
                'disabled'      => 0,
            ),
        );
        
        // 模板输出
        $this->template->content                        = new View("promotion/add_promotion");
        $this->template->content->promotion_schemes     = Mypromotion_scheme::instance()->lists($request_struct);

        // 当前应用专用数据
        $this->template->content->pmta_id               = $pmta_id;
        $this->template->content->pmts_id               = $pmts_id;
    }

    /**
     * Show parameters form for a promotion rule
     */
    public function add_next()
    {
    	$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
        try{
            // 收集请求数据
            $request_data = $this->input->get();
            $pmta_id = $this->input->get('pmta_id');        
            $pmts_id = $this->input->get('pmts_id');
            $session = Session::instance();
            if($session->get('sessionErrorData') === false){
                $session->set_flash('sessionErrorData',$request_data);
            }
            
            if(!is_numeric($pmta_id) || !is_numeric($pmts_id))
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);	
            }
            $promotion_scheme       = Mypromotion_scheme::instance($pmts_id)->get();            
            if(!$promotion_scheme['id'])
            {
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
            $promotion_activity     = Mypromotion_activity::instance($pmta_id)->get();
            if(!$promotion_activity['id'])
            {
                remind::set(Kohana::lang('o_global.bad_request'),'/promotion/promotion_activity/','error');
            }

            // 模板输出
            $this->template->content = new View("promotion/add_promotion_".$pmts_id);

            $this->template->content->pmts_id = $pmts_id;
            $this->template->content->pmta_id = $pmta_id;
            $this->template->content->promotion_scheme          = $promotion_scheme;
            $this->template->content->promotion_activity        = $promotion_activity;
            
            // extra process needed for IDs
            switch( $pmts_id ) {
            case 1: // discount_category
            case 10: // get_catgift_price_morethan
                $request_struct = array(
                    'where'=>array(
                    ),
                    'orderby'=>array(
                        'title_manage'=>'ASC'
                    )
                );
                $all_ids = CategoryService::get_instance()->index($request_struct);
                
                //dialog start
                $this->template->content->dialog = new View("promotion/partial/dialog_category");
                $categoryDiaStruct = array(
                    'dialog_form'        => 'dialog-form',
                    'categoryTable'      => 'categoryTable',
                );
                $related_ids = 'related_ids';
                if($pmts_id == 10){
                    $related_ids = 'gift_related_ids';
                }
                $this->template->content->dialog->categoryDiaStruct = $categoryDiaStruct;
                $this->template->content->dialog->related_ids_name = $related_ids;
                
                //显示字段设置
                $category_field  =array("title_manage"=>"中文名称","title"=>"分类名");
                $all_ids = promotion::convert($all_ids);
                $tree = promotion::generate_tree($all_ids,1,0,$related_ids,$category_field,'checkAll');
                $this->template->content->dialog->tree = $tree;
                //dialog end
                break;
            case 5:
            case 6:
            	$request_struct = array(
                    'where'=>array(
                        'type'       => ProductService::PRODUCT_TYPE_GOODS,
                        'on_sale'    => 1,
                       //'active'   => 1,
                    ),
                    'orderby'=>array(
                        'title'=>'ASC'
                    )
                );
                $all_ids = ProductService::get_instance()->index($request_struct);
                $this->template->content->goods_area = new View("promotion/partial/add_goods");
                $this->template->content->all_ids = $all_ids;
                $this->template->content->goods_area->all_ids = $all_ids;
                //dialog start
                $this->template->content->dialog = new View("promotion/partial/dialog_good");
                $goodDiaStruct = array(
                    'dialog_form'    => 'dialog-form-good',
                    'goodSearchType' => 'goodSearchType',
                    'goodKeyword'    => 'goodKeyword',
                    'goodSearchbtn'  => 'goodSearchbtn',
                    'goodTable'      => 'goodTable',
                    'checkAll'       => 'checkAll',
                    'goods'          => 'goods',
                );
                $this->template->content->dialog->goodDiaStruct = $goodDiaStruct;
                $this->template->content->dialog->all_ids = $all_ids;
                //显示字段设置
                $js_good_field ='var good_field = {\'SKU\':"sku","货品名":"title"};';
                $good_field  =array('sku'=>'SKU',"title"=>"货品名");
                $this->template->content->js_good_field = $js_good_field;
                $this->template->content->goods_area->good_field  = $good_field;
                //dialog end
                break;
            case 2: // discount_product_during
            case 3: // discount_product_quantity_morethan
            case 12: // discount_cart_buy_product
            case 16: // free_shipping_buy_product
                $request_struct = array(
                    'where'=>array(
                        'on_sale'    => 1,
                       // 'active'        => 1,
                    ),
                    'orderby'=>array(
                        'name_manage'=>'ASC'
                    )
                );
                //$all_ids = ProductService::get_instance()->index($request_struct);
                //$this->add_category_name($all_ids);
                
                $return_data = BLL_Product::index($request_struct);
                $all_ids = $return_data['assoc'];
                //dump($all_ids);
                $cids = array();
                
                $this->template->content->products_area = new View("promotion/partial/add_products");
                $this->template->content->products_area->all_ids = $all_ids;
                $this->template->content->all_ids = $all_ids;
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
                $this->template->content->dialog->all_ids = $all_ids;
                //显示字段设置
                $js_product_field ='var product_field = {"SKU":"sku","中文名称":"name_manage","商品名称":"title","分类名称":"category_name"};';
                $product_field  =array('sku'=>'SKU',"name_manage"=>"中文名称","title"=>"商品名称","category_name"=>"分类名称");
                $this->template->content->js_product_field = $js_product_field;
                $this->template->content->products_area->product_field  = $product_field;
                //dialog end

                break;

            case 7: // get_gifts_product_price_morethan
            	$request_struct = array(
                    'where'=>array(
                        //'type'       => ProductService::PRODUCT_TYPE_GOODS,
                        'on_sale'    => 1,
                       // 'active'        => 1,
                    ),
                    'orderby'=>array(
                        'title'=>'ASC'
                    )
                );
                $all_ids = ProductService::get_instance()->index($request_struct);
                $this->template->content->goods_area = new View("promotion/partial/add_goods");
                $this->template->content->goods_area->all_ids = $all_ids;
                $this->template->content->good_all_ids = $all_ids;
                //dialog start
                $this->template->content->dialog_good = new View("promotion/partial/dialog_good");
                $goodDiaStruct = array(
                    'dialog_form'    => 'dialog-form-good',
                    'goodSearchType' => 'goodSearchType',
                    'goodKeyword'    => 'goodKeyword',
                    'goodSearchbtn'  => 'goodSearchbtn',
                    'goodTable'      => 'goodTable',
                    'checkAll'       => 'checkAll',
                    'goods'          => 'goods',
                );
                $this->template->content->dialog_good->goodDiaStruct = $goodDiaStruct;
                $this->template->content->goodDiaStruct = $goodDiaStruct;
                $this->template->content->dialog_good->all_ids = $all_ids;
                //显示字段设置
                $js_good_field ='var good_field = {\'SKU\':"sku","货品名":"title"};';
                $good_field  =array('sku'=>'SKU',"title"=>"货品名");
                $this->template->content->js_good_field = $js_good_field;
                $this->template->content->goods_area->good_field  = $good_field;
                //dialog end
                
                /*$request_struct = array(
                    'where'=>array(
                        'on_sale'    => 1,
                        //'active'        => 1,
                    ),
                    'orderby'=>array(
                        'name_manage'=>'ASC'
                    )
                );
                $all_ids = ProductService::get_instance()->index($request_struct);*/
                $this->add_category_name($all_ids);
                
                $this->template->content->products_area = new View("promotion/partial/add_products");
                $this->template->content->products_area->all_ids = $all_ids;
                $this->template->content->product_all_ids = $all_ids;
                //dialog start
                $this->template->content->dialog_product = new View("promotion/partial/dialog_product");
                $productDiaStruct = array(
                    'dialog_form'       => 'dialog-form-product',
                    'productSearchType' => 'productSearchType',
                    'productKeyword'    => 'productKeyword',
                    'productSearchbtn'  => 'productSearchbtn',
                    'productTable'      => 'productTable',
                    'checkAll'          => 'checkAll',
                    'products'          => 'products',
                );
                $this->template->content->dialog_product->productDiaStruct = $productDiaStruct;
                $this->template->content->productDiaStruct = $productDiaStruct;
                $this->template->content->dialog_product->all_ids = $all_ids;
                //显示字段设置
                $js_product_field ='var product_field = {\'SKU\':"sku","中文名称":"name_manage","商品名称":"title","分类名称":"category_name"};';
                $product_field  =array('sku'=>'SKU',"name_manage"=>"中文名称","title"=>"商品名称","category_name"=>"分类名称");
                $this->template->content->js_product_field = $js_product_field;
                $this->template->content->products_area->product_field  = $product_field;
                //dialog end
                break;

            case 8: // get_1_buy_n
                break;

            case 9: // get_another_cat_buy_cat
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
                //dialog start
                $related_ids_gift = 'gift_related_ids';
                $this->template->content->dialog_gift = new View("promotion/partial/dialog_category");
                $this->template->content->dialog_gift->related_ids_name = $related_ids_gift;
                $categoryDiaStructGift = array(
                    'dialog_form'        => 'dialog-form-gift',
                    'categoryTable'      => 'categoryTable_gift',
                );
                $this->template->content->dialog_gift->categoryDiaStruct = $categoryDiaStructGift;
                $tree = promotion::generate_tree($all_ids,1,0,$related_ids_gift,$category_field,'checkAll_gift');
                $this->template->content->dialog_gift->tree = $tree;
                //dialog end

                break;
            }
            
        }catch(MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
        }
    }

    public function do_add()
    {
        role::check('promotion_promotion');
        // 收集请求数据
        $request_data = $this->input->post();

        $session = Session::instance();
        $session->set_flash('sessionErrorData',$request_data);
        
        //标签过滤
        tool::filter_strip_tags($request_data);
        
        $request_data['related_id'] = '';
        
        if(strtotime($request_data['time_begin']) > strtotime($request_data['time_end']))
        {
            remind::set(Kohana::lang('o_promotion.begin_time_over_end'),request::referrer(),'error');
        }

        //促销规则时间效验
        $dayTimeStamp = 24*3600;
        $request_data['time_end'] = date('Y-m-d H:i:s',strtotime($request_data['time_end'])+$dayTimeStamp);
        $time_begin     = strtotime($request_data['time_begin']);
        $time_end       = strtotime($request_data['time_end']);
        //结束时间不能比当前时间晚
        if($time_end < time()){
            remind::set(Kohana::lang('o_promotion.time_end'), request::referrer(), 'error');
        } 
        
        //促销规则时间必须在促销活动时间内
        $promotion_activity     = Mypromotion_activity::instance($request_data['pmta_id'])->get();
        if(!$promotion_activity['id'])
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');			
        }
        $pmta_time_begin        = strtotime($promotion_activity['pmta_time_begin']); 
        $pmta_time_end           = strtotime($promotion_activity['pmta_time_end']); 
        if($pmta_time_begin>$time_begin || $pmta_time_end<$time_end)
        {       
            remind::set(Kohana::lang('o_promotion.promotion_out_time_range'),request::referrer(),'error');		
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
        if((isset($request_data['quantity_from']) && (!preg_match('/^\d+$/',$request_data['quantity_from']) || $request_data['quantity_from']<0))
           || (isset($request_data['quantity_to']) && (!preg_match('/^\d+$/',$request_data['quantity_to']) || $request_data['quantity_to']<0))
          ||(isset($request_data['quantity_from']) && $request_data['quantity_from'] >= $request_data['quantity_to'])
           ){
            remind::set(Kohana::lang('o_promotion.buy_quantitys_error'),request::referrer(),'error');
        }
        $moneyError = '';
        switch($request_data['pmts_id']) {
            case 5: // get_gifts_buy_anything
            case 6: // get_gifts_price_morethan
            	$moneyError = '订单金额错误';
                $gift_related_ids = $this->input->post('gift_related_ids');
                if(empty($gift_related_ids)){
                    remind::set(Kohana::lang('o_promotion.select_gift'),request::referrer(),'error');
                }
                
                $request_data['gift_related_ids'] = Mypromotion::enclose_ids($gift_related_ids, ',');
                break;
            case 1: // discount_category
            	$related_ids = $this->input->post('related_ids');
                if(empty($related_ids)){
                    remind::set(Kohana::lang('o_promotion.select_category'),request::referrer(),'error');
                }
                !isset($moneyError) && $moneyError = '订单金额错误';
            case 2: // discount_product_during
            	empty($moneyError) && $moneyError = '订单金额错误';
            case 3: // discount_product_quantity_morethan
            case 12: // discount_cart_buy_product
            case 16: // free_shipping_buy_product
                $related_ids = $this->input->post('related_ids');
                if(empty($related_ids)){
                    remind::set(Kohana::lang('o_promotion.select_product'),request::referrer(),'error');
                }
                // enclose selected category ids with comma
                $request_data['related_ids'] = Mypromotion::enclose_ids($related_ids, ',');
                break;

            case 7: // get_gifts_product_price_morethan
                $related_ids = $this->input->post('related_ids');
                $gift_related_ids = $this->input->post('gift_related_ids');
                empty($moneyError) && $moneyError = '商品金额错误';
                if(empty($related_ids)){
                    remind::set(Kohana::lang('o_promotion.select_product'),request::referrer(),'error');
                }
                if(empty($gift_related_ids)){
                    remind::set(Kohana::lang('o_promotion.select_gift'),request::referrer(),'error');
                }
                // enclose selected category ids with comma
                $request_data['related_ids'] = Mypromotion::enclose_ids($related_ids, ',');
                $request_data['gift_related_ids'] = Mypromotion::enclose_ids($gift_related_ids, ',');
                break;

            case 8: // get_1_buy_n
                break;

            case 9: // get_another_cat_buy_cat
                $related_ids = $this->input->post('related_ids');            	
                $gift_related_ids = $this->input->post('gift_related_ids');
                if(empty($related_ids)){
                    remind::set(Kohana::lang('o_promotion.select_cpn_category'),request::referrer(),'error');
                }
                if(empty($gift_related_ids)){
                    remind::set(Kohana::lang('o_promotion.select_gift_category'),request::referrer(),'error');
                }
                // separate selected category ids with comma
                $request_data['related_ids'] = Mypromotion::enclose_ids($related_ids, ',');
                $request_data['gift_related_ids'] = Mypromotion::enclose_ids($gift_related_ids, ',');          
                break;

            case 10: // get_catgift_price_morethan
                $gift_related_ids = $this->input->post('gift_related_ids');
                if(empty($gift_related_ids)){
                    remind::set(Kohana::lang('o_promotion.select_gift_category'),request::referrer(),'error');
                }
                empty($moneyError) && $moneyError = '订单金额错误';
                $request_data['gift_related_ids'] = Mypromotion::enclose_ids($gift_related_ids, ',');
                break;
            case 11:
                empty($moneyError) && $moneyError = '购物车金额错误';
                break;
           case 14:
                empty($moneyError) && $moneyError = '订单金额错误';
                break;
        }
        if((isset($request_data['money_from']) 
                    && (!preg_match('/^\d{1,12}(\.\d{0,3})?$/',$request_data['money_from']) || $request_data['money_from']<0))
                || (isset($request_data['money_to']) && (!preg_match('/^\d{1,12}(\.\d{0,3})?$/',$request_data['money_to']) 
                || $request_data['money_to']<0))
                || (isset($request_data['money_from']) && $request_data['money_from'] >= $request_data['money_to']) )
        {
            remind::set($moneyError,request::referrer(),'error');
        }

        if ( Mypromotion::instance()->add($request_data) ) 
        {
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
        // 收集请求数据
        $request_data = $this->input->get();

        $promotion    = Mypromotion::instance($request_data['id'])->get();
        if(!$promotion['id'])
        {
            remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');		
        }
        $promotion['money_from'] = sprintf('%.3f',$promotion['money_from']);
        $promotion['money_to'] = sprintf('%.3f',$promotion['money_to']);
        $pmts_id 	  = $promotion['pmts_id'];
        $scheme 	  = Mypromotion_scheme::instance($promotion['pmts_id'])->get();
        //实例化session
        $session      = Session::instance();

        // 模板输出
        $this->template->content = new View("promotion/edit_promotion_".$pmts_id);
        //echo $pmts_id;
        //extra process needed for IDs
        switch( $pmts_id ) {
        case 1: // discount_category                		
            $request_struct = array(
                'where'=>array(
                    //'virtual'       => 0,
                ),
                'orderby'=>array(
                    'title_manage'=>'ASC'
                )
            );
            $all_ids = CategoryService::get_instance()->index($request_struct);
            unset($all_ids['count']);
            //dialog start
            $this->template->content->dialog = new View("promotion/partial/dialog_category");
            $this->template->content->dialog->related_ids_name = 'related_ids';
            if($session->get('sessionErrorData') === false){
                $this->template->content->dialog->related_ids = explode(',', $promotion['related_ids']);
            }
            $categoryDiaStruct = array(
                'dialog_form'        => 'dialog-form',
                'categoryTable'      => 'categoryTable',
            );
            $this->template->content->dialog->categoryDiaStruct = $categoryDiaStruct;
            
            //显示字段设置
            $category_field  =array("title_manage"=>"中文名称","title"=>"分类名");
            $all_ids = promotion::convert($all_ids);
            $tree = promotion::generate_tree($all_ids,1,0,'related_ids',$category_field,'checkAll');
            $this->template->content->dialog->tree = $tree;
            //dialog end

            break;
        case 5: // get_gifts_buy_anything
        case 6: // get_gifts_price_morethan
            $request_struct = array(
                'where'=>array(
                    'type'       => ProductService::PRODUCT_TYPE_GOODS,
                    'on_sale'    => 1,
                   // 'active'        => 1,
                ),
                'orderby'=>array(
                    'title'=>'ASC'
                )
            );
            $all_ids = ProductService::get_instance()->index($request_struct);
            $this->template->content->goods_area = new View("promotion/partial/edit_goods");
            if($session->get('sessionErrorData') === false){
                $this->template->content->goods_area->gift_related_ids = explode(',', $promotion['gift_related_ids']);
            }
            $this->template->content->goods_area->thing = 'goods';
            $this->template->content->thing = 'goods';
            $this->template->content->goods_area->all_ids = $all_ids;
            $this->template->content->all_ids = $all_ids;
            //dialog start
            $this->template->content->dialog = new View("promotion/partial/dialog_good");
            $goodDiaStruct = array(
                'dialog_form'    => 'dialog-form-good',
                'goodSearchType' => 'goodSearchType',
                'goodKeyword'    => 'goodKeyword',
                'goodSearchbtn'  => 'goodSearchbtn',
                'goodTable'      => 'goodTable',
                'checkAll'       => 'checkAll',
                'goods'          => 'goods',
            );
            $this->template->content->dialog->goodDiaStruct = $goodDiaStruct;
            $this->template->content->dialog->all_ids = $all_ids;
//显示字段设置
            $js_good_field ='var good_field = {\'SKU\':"sku","货品名":"title"};';
            $good_field  =array('sku'=>'SKU',"title"=>"货品名");
            $this->template->content->js_good_field = $js_good_field;
            $this->template->content->goods_area->good_field  = $good_field;
//dialog end
            break;
        case 2: // discount_product_during
        case 3: // discount_product_quantity_morethan
            
        case 12: // discount_cart_buy_product
        case 16: // free_shipping_buy_product                		
            $this->template->content->products_area = new View("promotion/partial/edit_products");
            if($session->get('sessionErrorData') === false){
            	$related_ids = explode(',', trim($promotion['related_ids'],','));
//                $this->template->content->products_area->related_ids = $related_ids;
            }else{
            	$related_ids = $session->get('sessionErrorData');
            	$related_ids = $related_ids['related_ids'];
            }
            $all_ids = ProductService::get_instance()->index(array(
            	'where' => array(
            		'on_sale' => 1,
            		'id'      => $related_ids,
            	),
                'orderby' => array(
                	'name_manage' => 'ASC'
            	)
            ));
            $this->add_category_name($all_ids);
            $this->template->content->products_area->all_ids = $all_ids;
            
            $this->template->content->all_ids = $all_ids;
            
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
            $this->template->content->dialog->all_ids = $all_ids;
            
//显示字段设置
            $js_product_field = 'var product_field = {\'SKU\':"sku","中文名称":"name_manage","商品名称":"title","分类名称":"category_name"};';
            $product_field  = array(
            	'sku'          => 'SKU',
            	"name_manage"  => "中文名称",
            	"title"        => "商品名称",
            	"category_name"=> "分类名称"
            );
            $this->template->content->js_product_field = $js_product_field;
            $this->template->content->products_area->product_field  = $product_field;
//dialog end

            break;

        case 7: // get_gifts_product_price_morethan           		
            $this->template->content->products_area = new View("promotion/partial/edit_products");
            if($session->get('sessionErrorData') === false){
            	$related_ids = explode(',', trim($promotion['related_ids'], ','));
                $this->template->content->products_area->related_ids = $related_ids;
            }else{
            	$related_ids = $session->get('sessionErrorData');
            	$related_ids = $related_ids['related_ids'];
            }
            $all_ids = ProductService::get_instance()->index(array(
            	'where' => array(
            		'on_sale' => 1,
            		'id'      => $related_ids,
            	),
                'orderby' => array(
                	'name_manage' => 'ASC'
            	)
            ));
            $this->add_category_name($all_ids);
            $this->template->content->products_area->all_ids = $all_ids;
            
            $this->template->content->products_area->thing = 'products';
            $this->template->content->thing = 'products';
            
            $this->template->content->product_all_ids = $all_ids;

//dialog start
            $this->template->content->dialog_product = new View("promotion/partial/dialog_product");
            $productDiaStruct = array(
                'dialog_form'       => 'dialog-form-product',
                'productSearchType' => 'productSearchType',
                'productKeyword'    => 'productKeyword',
                'productSearchbtn'  => 'productSearchbtn',
                'productTable'      => 'productTable',
                'checkAll'          => 'checkAll',
                'products'          => 'products',
            );
            $this->template->content->dialog_product->productDiaStruct = $productDiaStruct;
            $this->template->content->productDiaStruct = $productDiaStruct;
            $this->template->content->dialog_product->all_ids = $all_ids;
//显示字段设置
            $js_product_field ='var product_field = {\'SKU\':"sku","中文名称":"name_manage","商品名称":"title","分类名称":"category_name"};';
            $product_field  =array('sku'=>'SKU',"name_manage"=>"中文名称","title"=>"商品名称","category_name"=>"分类名称");
            $this->template->content->js_product_field = $js_product_field;
            $this->template->content->products_area->product_field  = $product_field;
//dialog end
            $this->template->content->goods_area = new View("promotion/partial/edit_goods");
            
            $this->template->content->goods_area->thing = 'goods';
            $this->template->content->thing = 'goods';
            
            $all_ids = ProductService::get_instance()->index(array('where' => array(), 'orderby' => array('title' => 'ASC')));
            $this->template->content->goods_area->all_ids = $all_ids;
            $this->template->content->good_all_ids = $all_ids;
            if($session->get('sessionErrorData') === false){
                $this->template->content->goods_area->gift_related_ids = explode(',', $promotion['gift_related_ids']);
            }
//dialog start
            $this->template->content->dialog_good = new View("promotion/partial/dialog_good");
            $goodDiaStruct = array(
                'dialog_form'    => 'dialog-form-good',
                'goodSearchType' => 'goodSearchType',
                'goodKeyword'    => 'goodKeyword',
                'goodSearchbtn'  => 'goodSearchbtn',
                'goodTable'      => 'goodTable',
                'checkAll'       => 'checkAll',
                'goods'          => 'goods',
            );
            $this->template->content->dialog_good->goodDiaStruct = $goodDiaStruct;
            $this->template->content->dialog_good->all_ids = $all_ids;
//显示字段设置
            $js_good_field ='var good_field = {\'SKU\':"sku","货品名":"title"};';
            $good_field  =array('sku'=>'SKU',"title"=>"货品名");
            $this->template->content->js_good_field = $js_good_field;
            $this->template->content->goods_area->good_field  = $good_field;
//dialog end
            break;

        case 8: // get_1_buy_n
            break;

        case 9: // get_another_cat_buy_cat
            $this->template->content->related_ids = explode(',', $promotion['related_ids']);
            $request_struct = array(
                'where'=>array(
                    //'virtual'       => 0,
                ),
                'orderby'=>array(
                    'title_manage'=>'ASC'
                )
            );
            $all_ids = CategoryService::get_instance()->index($request_struct);

//dialog start
            $this->template->content->dialog = new View("promotion/partial/dialog_category");
            $this->template->content->dialog->related_ids_name = 'related_ids';
            if($session->get('sessionErrorData') === false){
                $this->template->content->dialog->related_ids = explode(',', $promotion['related_ids']);
            }
            $categoryDiaStruct = array(
                'dialog_form'        => 'dialog-form',
                'categoryTable'      => 'categoryTable',
            );
            $this->template->content->dialog->categoryDiaStruct = $categoryDiaStruct;
            $this->template->content->dialog->all_ids = $all_ids;
            $category_field  =array("title_manage"=>"中文名称","title"=>"分类名");
            $all_ids = promotion::convert($all_ids);
            $tree = promotion::generate_tree($all_ids,1,0,'related_ids',$category_field,'checkAll');
            $this->template->content->dialog->tree = $tree;
//dialog end

//dialog start
            $this->template->content->dialog_gift = new View("promotion/partial/dialog_category");
            $this->template->content->dialog_gift->related_ids_name = 'gift_related_ids';
            if($session->get('sessionErrorData') === false){
                $this->template->content->dialog_gift->gift_related_ids = explode(',', $promotion['gift_related_ids']);
            }
            $categoryDiaStructGift = array(
                'dialog_form'        => 'dialog-form-gift',
                'categoryTable'      => 'categoryTable_gift',
            );
            $this->template->content->dialog_gift->categoryDiaStruct = $categoryDiaStructGift;
            $tree = promotion::generate_tree($all_ids,1,0,'gift_related_ids',$category_field,'checkAll_gift');
            $this->template->content->dialog_gift->tree = $tree;
//显示字段设置
//dialog end
            break;

        case 10: // get_catgift_price_morethan
            $request_struct = array(
                'where'=>array(
                    //'virtual'       => 0,
                ),
                'orderby'=>array(
                    'title_manage'=>'ASC'
                )
            );
            $all_ids = CategoryService::get_instance()->index($request_struct);
            unset($all_ids['count']);
//dialog start
            $this->template->content->dialog = new View("promotion/partial/dialog_category");
            $this->template->content->dialog->related_ids_name = 'gift_related_ids';
            if($session->get('sessionErrorData') === false){
                $this->template->content->dialog->gift_related_ids = explode(',', $promotion['gift_related_ids']);
            }
            $categoryDiaStruct = array(
                'dialog_form'        => 'dialog-form',
                'categoryTable'      => 'categoryTable',
            );
            $this->template->content->dialog->categoryDiaStruct = $categoryDiaStruct;
            
//显示字段设置
            $category_field  =array("title_manage"=>"中文名称","title"=>"分类名");
            $all_ids = promotion::convert($all_ids);
            $tree = promotion::generate_tree($all_ids,1,0,'gift_related_ids',$category_field,'checkAll');
            $this->template->content->dialog->tree = $tree;
//dialog end
            break;
        }

        // 变量绑定
        $this->template->content->faction			 = 'do_edit';
        $this->template->content->promotion			 = $promotion;
        $this->template->content->pmts_memo			 = $scheme['pmts_memo'];
        $this->template->content->promotion_scheme     = $scheme; 
    }

    public function do_edit()
    {
        // 收集请求数据
        $request_data = $this->input->post();
        $session = Session::instance();
        $session->set_flash('sessionErrorData',$request_data);
        
        //标签过滤
        tool::filter_strip_tags($request_data);
        
        $pmts_id = $this->input->post('pmts_id');
        $promotion    = Mypromotion::instance($request_data['id'])->get();
        if(!$promotion['id'])
        {      	
            remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');		
        }
      
        //促销规则时间效验
        if(strtotime($request_data['time_end'])+ 24 * 3600 < time()){
            remind::set(Kohana::lang('o_promotion.time_end'), request::referrer(), 'error');
        }
        if(strtotime($request_data['time_begin']) > strtotime($request_data['time_end']))
        {
            remind::set(Kohana::lang('o_promotion.begin_time_over_end'),request::referrer(),'error');
        }

        $dayTimeStamp = 24*3600;
        $request_data['time_end'] = date('Y-m-d H:i:s',strtotime($request_data['time_end'])+$dayTimeStamp);
        $time_begin     = strtotime($request_data['time_begin']);
        $time_end       = strtotime($request_data['time_end']);
        //促销规则时间必须在促销活动时间内
        $promotion_activity     = Mypromotion_activity::instance($promotion['pmta_id'])->get();
        if(!$promotion_activity['id'])
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }
        $pmta_time_begin        = strtotime($promotion_activity['pmta_time_begin']); 
        $pmta_time_end           = strtotime($promotion_activity['pmta_time_end']); 
        if($pmta_time_begin>$time_begin||$pmta_time_end<$time_end)
        {
            remind::set(Kohana::lang('o_promotion.promotion_out_time_range'),request::referrer(),'error');		
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
        if((isset($request_data['quantity_from']) && (!preg_match('/^\d+$/',$request_data['quantity_from']) || $request_data['quantity_from']<0))
           || (isset($request_data['quantity_to']) && (!preg_match('/^\d+$/',$request_data['quantity_to']) || $request_data['quantity_to']<0))
          ||(isset($request_data['quantity_from']) && $request_data['quantity_from'] >= $request_data['quantity_to'])
           ){
            remind::set(Kohana::lang('o_promotion.buy_quantitys_error'),request::referrer(),'error');
        }

        $moneyError = '';
        // extra process needed for IDs
        switch( $pmts_id ) {
        case 5: // get_gifts_buy_anything
        case 6: // get_gifts_price_morethan
        	$moneyError = '订单金额错误';
        	$gift_related_ids = $this->input->post('gift_related_ids');
            if(empty($gift_related_ids)){
                remind::set(Kohana::lang('o_promotion.select_gift'),request::referrer(),'error');
            }
            $request_data['gift_related_ids'] = Mypromotion::enclose_ids($gift_related_ids, ',');
            break;
        case 1: // discount_category
        	$related_ids = $this->input->post('related_ids');
            if(empty($related_ids)){
                remind::set(Kohana::lang('o_promotion.select_cpn_category'),request::referrer(),'error');
            }
            empty($moneyError) && $moneyError = '订单金额错误';
        case 2: // discount_product_during
        	empty($moneyError) && $moneyError = '订单金额错误';
        case 3: // discount_product_quantity_morethan

        case 12: // discount_cart_buy_product
        case 16: // free_shipping_buy_product
            if(!isset($related_ids)){
            	$related_ids = $this->input->post('related_ids');
            }
            if(empty($related_ids)){
                remind::set(Kohana::lang('o_promotion.select_product'),request::referrer(),'error');
            }
            // enclose selected category ids with comma
            $request_data['related_ids'] = Mypromotion::enclose_ids($related_ids, ',');
            break;

        case 7: // get_gifts_product_price_morethan
            $related_ids = $this->input->post('related_ids');
            $gift_related_ids = $this->input->post('gift_related_ids');
            empty($moneyError) && $moneyError = '货品金额错误';
            if(empty($related_ids)){
                remind::set(Kohana::lang('o_promotion.select_product'),request::referrer(),'error');
            }
            if(empty($gift_related_ids)){
                remind::set(Kohana::lang('o_promotion.select_gift'),request::referrer(),'error');
            }
            // enclose selected category ids with comma
            $request_data['related_ids'] = Mypromotion::enclose_ids($related_ids, ',');
            $request_data['gift_related_ids'] = Mypromotion::enclose_ids($gift_related_ids, ',');            	
            break;

        case 8: // get_1_buy_n
            break;

        case 9: // get_another_cat_buy_cat
            $related_ids = $this->input->post('related_ids');            	
            $gift_related_ids = $this->input->post('gift_related_ids');
            if(empty($related_ids)){
                remind::set(Kohana::lang('o_promotion.select_cpn_category'),request::referrer(),'error');
            }
            if(empty($gift_related_ids)){
                remind::set(Kohana::lang('o_promotion.select_gift_category'),request::referrer(),'error');
            }
           
            // separate selected category ids with comma
            $request_data['related_ids'] = Mypromotion::enclose_ids($related_ids, ',');
            $request_data['gift_related_ids'] = Mypromotion::enclose_ids($gift_related_ids, ',');          
            break;

        case 10: // get_catgift_price_morethan    	
            $gift_related_ids = $this->input->post('gift_related_ids');
            empty($moneyError) && $moneyError = '订单金额错误';
            if(empty($gift_related_ids)){
                remind::set(Kohana::lang('o_promotion.select_gift_category'),request::referrer(),'error');
            }
            // separate selected category ids with comma
            $request_data['gift_related_ids'] = Mypromotion::enclose_ids($gift_related_ids, ',');          
            break;
        case 11:
            empty($moneyError) && $moneyError = '购物车金额错误';
            break;
        case 14:
            empty($moneyError) && $moneyError = '订单金额错误';
            break;
        }
        //验证
         if((isset($request_data['money_from']) && (!preg_match('/^\d{1,12}(\.\d{0,3})?$/',$request_data['money_from']) || $request_data['money_from']<0))
           ||(isset($request_data['money_to']) && (!preg_match('/^\d{1,12}(\.\d{0,3})?$/',$request_data['money_to']) || $request_data['money_to']<0))
          ||(isset($request_data['money_from']) && $request_data['money_from'] >= $request_data['money_to'])
           ){
            remind::set($moneyError,request::referrer(),'error');
        }

        if(Mypromotion::instance()->edit($request_data))
        {
            $session->delete('sessionErrorData');
            //promotion::delete_memcache($promotion['site_id']);
            remind::set(Kohana::lang('o_global.update_success'),"promotion/promotion_activity",'success');
        }else{
            remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
        }
    }


    public function do_delete()
    {
        role::check('promotion_promotion');
        // 收集请求数据
        $request_data = $this->input->get();

        $promotion    = Mypromotion::instance($request_data['id'])->get();
        if(!$promotion['id'])
        {
            remind::set(Kohana::lang('o_global.bad_request'),'promotion/promotion_activity','error');		
        }

        if(Mypromotion::instance()->delete($promotion['id']))
        {
            //promotion::delete_memcache($promotion['site_id']);
            remind::set(Kohana::lang('o_global.delete_success'),'promotion/promotion_activity','success');
        }
    }
    
    public function search_product()
    {
        role::check('promotion_promotion');
        // 收集请求数据
        $request_data = $this->input->get();
        $request_data['stype'] = $request_data['type'];
        $request_data['on_sale'] = 1;
        $request_data['per_page'] = 6;
        $struct = product::get_struct($request_data);
        $query_struct_current   = $struct['query'];
        $request_struct_current = $struct['request'];
        
        $request_data['keyword'] && $query_struct_current['like'][$request_data['stype']]=$request_data['keyword'];

        $return_data = BLL_Product::index($query_struct_current);
        $returnData['content'] = $return_data['assoc'];
        $returnData['page']    = isset($request_struct_current['page']) ? $request_struct_current['page'] : 1;
        $returnData['count']   = ceil($return_data['count']/$request_struct_current['per_page']);
        echo json_encode($returnData);
        exit;
    }
    
    public function search_good()
    {        
        // 收集请求数据
        $request_data = $this->input->get();

        $request_struct_current = array(
            'where'    => array(
                'type' => ProductService::PRODUCT_TYPE_GOODS,
                'on_sale'  => 1,
            ),
            'limit'    => array(
                'per_page'  =>6,
                'page'    =>1,
            ),
            'like'     => array(),
            'orderby'  => array(
                'id'    => 'DESC',
            )
        );
        if(isset($request_data['page']) && !empty($request_data['page']))
        {
            $request_struct_current['limit']['page'] = $request_data['page'];
            $returnData['page'] = $request_struct_current['limit']['page'];
        }
        // 当前支持的查询业务逻辑
        if(isset($request_data['type']) && isset($request_data['keyword']) && !empty($request_data['keyword']))
        {
            switch ($request_data['type']){
            case 'id':
                $request_struct_current['where'][$request_data['type']] = intval(trim($request_data['keyword']));
                break;
            case 'title':
                $request_struct_current['like'][$request_data['type']]  = trim($request_data['keyword']);
                break;
            case 'sku':
                $request_struct_current['where'][$request_data['type']]  = trim($request_data['keyword']);
                break;
            }
        }

        $goods = ProductService::get_instance()->index($request_struct_current); 
        $returnData['content'] = $goods;
        $returnData['count'] = ceil(ProductService::get_instance()->count($request_struct_current)/$request_struct_current['limit']['per_page']);
        $returnData['page'] = isset($returnData['page'])?$returnData['page']:1;
        //header('Content-Type: text/javascript; charset=UTF-8');
        echo json_encode($returnData);
        exit;
    }
    
    public function search_category()
    {        
        role::check('promotion_promotion');
        // 收集请求数据
        $request_data = $this->input->get();

        $request_struct_current = array(
            'where'    => array(
            ),
            'like'     => array(),
            'limit'    => array(
                'per_page'  =>6,
                'page'      =>1,
            ),
            'orderby'  => array(
                'position'  => 'ASC',
                'id'        => 'DESC',
            )
        );
        $record_data = array();
        if(isset($request_data['page']) && !empty($request_data['page']))
        {
            $request_struct_current['limit']['page'] = $request_data['page'];
            $record_data['page'] = $request_data['page'];
        }
        // 当前支持的查询业务逻辑
        if(isset($request_data['type']) && isset($request_data['keyword']) && !empty($request_data['keyword']))
        {
            switch ($request_data['type']){
            case 'id':
                $request_struct_current['where'][$request_data['type']] = intval(trim($request_data['keyword']));
                break;
            case 'title_manage':
                $request_struct_current['like'][$request_data['type']]  = trim($request_data['keyword']);
                break;
            case 'title':
                $request_struct_current['like'][$request_data['type']]  = trim($request_data['keyword']);
                break;
            }
        }

        $categories = CategoryService::get_instance()->index($request_struct_current); 
        
        $returnData['content'] = $categories;
        $returnData['page'] = isset($record_data['page'])?$record_data['page']:1;
        $returnData['count'] = ceil(CategoryService::get_instance()->count($request_struct_current)/$request_struct_current['limit']['per_page']); 
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
        		$categories[$record['id']] = $record['title_manage']?$record['title_manage']:$record['title'];
        	}
        	foreach ($products as $index => $record)
        	{
        		$record['category_name'] = isset($categories[$record['category_id']]) ? $categories[$record['category_id']] : '';
        		$products[$index]        = $record;
        	}
        }
    }
}
