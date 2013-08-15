<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * $Id: product.php 225 2010-01-08 08:31:33Z zzy $
 * $Author: zzy $
 * $Revision: 225 $
 */

class Virtualcategory_Controller extends Template_Controller {

    /************************************************************************************/
    /*********************     虚拟分类信息                         *********************/ 
    /************************************************************************************/

    /**
     * 分类信息列表
     */
    public function index(){
        // 初始化默认查询条件
        $request_struct_current = array(
            'where'=>array(
                'site_id'   => NULL,
            ),
            'like'=>array(),
            'orderby'   => array(
                'id'   =>'DESC',
            ),
            'limit'     => array(
                'per_page'  =>20,
                'offset'    =>0,
            ),
        );

        // 收集请求数据
        $request_data = $this->input->get();

        /* b2c类型的站点列表 */
        $site_id_list = role::check('product_category',0,0);

        // 权限验证
        if(empty($site_id_list))
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');
        }
        if(isset($request_data['site_id'])&&is_numeric($request_data['site_id']) && $request_data['site_id']!=-1)
        {
            if(!in_array($request_data['site_id'],$site_id_list))
            {
                remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');
            }
        }

        // 站点查询
        controller_tool::request_site($request_struct_current,$request_data,$site_id_list);
        // 排序处理 
        controller_tool::request_orderby($request_struct_current,$request_data);
        // 每页条目数
        controller_tool::request_per_page($request_struct_current,$request_data);

        // 当前支持的查询业务逻辑
        if(isset($request_data['type']) && isset($request_data['keyword']) && !empty($request_data['keyword']))
        {
            switch ($request_data['type']){
            case 'id':
                $request_struct_current['where'][$request_data['type']] = intval(trim($request_data['keyword']));
                $request_data['keyword']                                = $request_struct_current['where'][$request_data['type']];
                break;
            case 'name_url':
                $request_struct_current['where'][$request_data['type']] = trim($request_data['keyword']);
                $request_data['keyword']                                = $request_struct_current['where'][$request_data['type']];
                break;
            case 'name':
                $request_struct_current['like'][$request_data['type']]  = trim($request_data['keyword']);
                $request_data['keyword']                                = $request_struct_current['like'][$request_data['type']];
                break;
            }
        }

        //当前查询
        $request_struct_current['where']['virtual']						= 1;

        $count				= Mycategory::instance()->count($request_struct_current);
        
        // 模板输出 分页
        $this->pagination       = new Pagination(array(
            'total_items'    => $count,
            'items_per_page' => $request_struct_current['limit']['per_page'],
        ));
        $request_struct_current['limit']['offset']      = $this->pagination->sql_offset;

        $category_list		= Mycategory::instance()->lists($request_struct_current);
        foreach($category_list as $key=>$rs)
        {
            $category_list[$key]['site']					= Mysite::instance($rs['site_id'])->get();	
            $category_list[$key]['parent_category']			= Mycategory::instance($rs['parent_id'])->get('name');
        }

        // 模板输出
        $this->template->content                    = new View("product/virtualcategory_list");

        // 变量绑定
        $this->template->content->category_list     = $category_list;
        $this->template->content->count             = $count;

        $this->template->content->request_data      = $request_data;

        // 当前应用专用数据
        $this->template->content->site_list             = Mysite::instance()->select_list($site_id_list);
        $this->template->content->category_level_list	= Mycategory::instance()->sites_subcategories($site_id_list);

    }

    /**
     * 分类排序向上
     */
    public function doedit_position_up(){
        $id	= $this->input->get('id');

        $position	= 'up';
        $category   = Mycategory::instance($id)->get();

        if(!$category['id']){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        // 权限检测
        $site_id_list = role::check('product_virtual_category',0,0);
        // 权限验证
        if(!in_array($category['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        if(Mycategory::instance()->virtual_position($id,$position))
        {
            remind::set(Kohana::lang('o_global.position_success'),request::referrer(),'success');		
        }else{
            remind::set(Kohana::lang('o_global.position_error'),request::referrer(),'error');		
        }
    }

    /**
     * 分类排序向下
     */
    public function doedit_position_down() {
        $id	= $this->input->get('id');

        $position	= 'down';

        $category   = Mycategory::instance($id)->get();
        if(!$category['id']){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        // 权限检测
        $site_id_list = role::check('product_virtual_category',0,0);
        // 权限验证
        if(!in_array($category['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        if(Mycategory::instance()->virtual_position($id,$position))
        {
            remind::set(Kohana::lang('o_global.position_success'),request::referrer(),'success');		
        }else{
            remind::set(Kohana::lang('o_global.position_error'),request::referrer(),'error');		
        }
    }

    // 添加分类主信息显示 
    public function add(){
        $site_id_list = role::check('product_virtual_category',0,0);

        $this->template->content = new View("product/virtualcategory_add");
        // 站点列表
        $this->template->content->site_info_arr = Mysite::instance()->select_list($site_id_list);
    }

    // 添加分类主信息操作
    public function do_add(){
        // 权限检测
        $site_id_list = role::check('product_virtual_category',0,0);

        // name url 不能重复
        if(!Mycategory::instance()->check_unique_name_url($_POST['name_url']))
        {
            remind::set(Kohana::lang('o_product.vir_category_url_has_exist'),request::referrer(),'error');
        }

        // 权限验证
        if(!in_array($_POST['site_id'],$site_id_list)){
            remind::set('access denied',request::referrer(),'error');		
        }

        if(isset($_FILES['imagefile']['name']) && filesize($_FILES['imagefile']['tmp_name'])>1048576){
            remind::set(Kohana::lang('o_product.file_size_out_range'),request::referrer(),'error');
        }

        $request_data = $this->input->post();
        $request_data['date_add']       = date('Y-m-d H:i:s');
        $request_data['date_upd']       = date('Y-m-d H:i:s');
        $request_data['parent_id']      = 0;
        $request_data['level_depth']    = 1;
        $request_data['virtual']        = 1;
        $request_data['position']       = 1000;
        $request_data['image']			= 'category.jpg';
        unset($_POST['id']);
        if($id = Mycategory::instance()->virtual_add($request_data))
        {
            if($_FILES["imagefile"]["error"] == 0) 
            {	
                $image_data					= array();
                $image_data['image']		= 'category_'.$id.'.jpg';

                $file = file_get_contents($_FILES["imagefile"]["tmp_name"]);
                $type = 'images';
                Storage_server::instance()->cache_category($_POST['site_id'], $type, $image_data['image'],$file);
                //log::write('image_type_1',$image_data['image'],__FILE__,__LINE__);
                //log::write('image_type_1',$file,__FILE__,__LINE__);
                //log::write('image_type_1',Storage_server::instance()->cache_category($_POST['site_id'], $type, $image_data['image'],$file),__FILE__,__LINE__);

                //更新图片名
                Mycategory::instance($id)->edit($image_data);
            }
            remind::set(Kohana::lang('o_global.add_success'),'product/virtualcategory','success');
        }else{
            remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
        }   
    }

    // 分类编辑显示
    public function edit() {
        $id			= $this->input->get('id'); 	

        $category   = Mycategory::instance($id)->get();

        if(!$category['id'] || $category['virtual']!=1)
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        // 权限检测
        $site_id_list = role::check('product_virtual_category',0,0);
        // 权限验证
        if(!in_array($category['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }


        $this->template->content                        = new View("product/virtualcategory_edit");				

        $this->template->content->data					= $category;
    }


    // 分类编辑操作
    public function do_edit(){
        //收集请求
        $request = $this->input->post();
        $id = $request['id'];

        // 验证 - 数据有效性
        $category   = Mycategory::instance($id)->get();
        if(!$category['id'] || $category['virtual']!=1)
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        // 权限检测
        $site_id_list = role::check('product_virtual_category',0,0);
        // 权限验证
        if(!in_array($category['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        // 修整 输入数据
        if(isset($request['date_add'])){
            unset($request['date_add']);
        }
        if(isset($request['position'])){
            unset($request['position']);
        }
        $request['date_upd']        = date('Y-m-d H:i:s');
        $request['site_id']         = $category['site_id'];

        if(Mycategory::instance($id)->virtual_edit($request))
        {
            if($_FILES['imagefile']['name'])
            {
                if($_FILES["imagefile"]["error"] == 0) 
                {	
                    $image_data					= array();
                    $image_data['image']		= 'category_'.$id.'.jpg';

                    $file = file_get_contents($_FILES["imagefile"]["tmp_name"]);
                    $type = 'images';
                    Storage_server::instance()->cache_category($category['site_id'], $type, $image_data['image'],$file);

                    //更新图片名
                    Mycategory::instance($id)->edit($image_data);
                }
            }
            remind::set(Kohana::lang('o_global.update_success'),'product/virtualcategory','success');
        }else{
            remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
        }
    }

    /**
     * 虚拟分类删除
     */
    public function do_delete(){
        //收集请求
        $id = $this->input->get('id');

        // 验证 - 数据有效性
        $category   = Mycategory::instance($id)->get();
        if(!$category['id'])
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        // 权限检测
        $site_id_list = role::check('product_virtual_category',0,0);
        // 权限验证
        if(!in_array($category['site_id'],$site_id_list)){
            remind::set('access denied',request::referrer(),'error');		
        }

        if(Mycategory::instance()->delete($id))
        {
            remind::set(Kohana::lang('o_global.delete_success'),'product/virtualcategory','success');
        }else{
            remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
        }
    }

    /**
     * 虑拟分类批量删除
     */
    public function do_delete_all(){

        $category_id_array		= $this->input->post('id');

        if(!(is_array($category_id_array)&&count($category_id_array)))
        {
            remind::set(Kohana::lang('o_product.select_vir_category'),request::referrer(),'error');
        }

        $site_id_list = role::check('product_virtual_category',0,0);

        $count			= 0;
        $false_count	= 0;
        foreach($category_id_array as $key=>$rs)
        {
            // 验证 - 数据有效性
            $category   = Mycategory::instance($rs)->get();
            if(!$category['id'])
            {
                $false_count++;
                continue;			
            }

            // 权限验证
            if(!in_array($category['site_id'],$site_id_list)){
                $false_count++;
                continue;			
            }     

            if(Mycategory::instance()->delete($rs))
            {
                $count++;
            }
            else
            {
                $false_count++;
            }
        }
        if($false_count)
        {
            remind::set(Kohana::lang('o_product.have').$false_count.Kohana::lang('o_product.num_vir_category_cannot_delete'),request::referrer(),'error');
        }
        else
        {
            remind::set(Kohana::lang('o_product.success_delete').$count.Kohana::lang('o_product.num_vir_category'),request::referrer(),'success');
        }

    }


    /************************************************************************************/
    /*********************     分类商品列表                         *********************/ 
    /************************************************************************************/

    /**
     * 分类商品列表  
     */
    public function product_list(){
        // 初始化默认查询条件
        $request_struct_current = array(
            'where'=>array(
                'site_id'   => NULL,
            ),
            'like'=>array(),
            'orderby'   => array(
                'id'   =>'DESC',
            ),
            'limit'     => array(
                'per_page'  =>20,
                'offset'    =>0,
            ),
        );

        // 收集请求数据
        $request_data	= $this->input->get();
        $category_id	= $request_data['id'];

        // 验证 - 数据有效性
        $category   = Mycategory::instance($category_id)->get();
        if(!$category['id'])
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');	
        }


        /* b2c类型的站点列表 */
        $site_id_list = role::check('product_virtual_category',0,0);
        // 权限验证
        if(empty($site_id_list))
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');
        }
        if(!in_array($category['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');	
        }     

        // 排序处理 
        controller_tool::request_orderby($request_struct_current,$request_data);
        // 每页条目数
        controller_tool::request_per_page($request_struct_current,$request_data);

        // 站点查询
        $request_struct_current['where']['site_id']     = $category['site_id'];

        //当前查询
        $request_struct_current['where']['category_id'] = $category['id'];

        $count						= Mycategory_product::instance()->count($request_struct_current);

        // 模板输出 分页
        $this->pagination       = new Pagination(array(
            'total_items'    => $count,
            'items_per_page' => $request_struct_current['limit']['per_page'],
        ));

        $request_struct_current['limit']['offset']      = $this->pagination->sql_offset;
        
        $category_product_list		= Mycategory_product::instance()->lists($request_struct_current);
        foreach($category_product_list as $key=>$rs)
        {
            $category_product_list[$key]['site']		= Mysite::instance($rs['site_id'])->get();	
            $category_product_list[$key]['product']		= Myproduct::instance($rs['product_id'])->get();
        }

        // 模板输出
        $this->template->content                    = new View("product/virtualcategory_product_list");

        // 变量绑定
        $this->template->content->category_product_list      = $category_product_list;
        $this->template->content->count             = $count;

        // 当前应用专用数据
        $this->template->content->data					= $category;

    }

    /**
     * 分类商品排序向上
     */
    public function doedit_product_position_up() 
    {
        $id			= $this->input->get('id');

        $position	= 'up';
        $category_product   = Mycategory_product::instance($id)->get();

        if(!$category_product['id']){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        $site_id_list = role::check('product_virtual_category',0,0);
        // 权限验证
        if(!in_array($category_product['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }   

        if(Mycategory_product::instance()->position($id,$position))
        {
            remind::set(Kohana::lang('o_global.position_success'),request::referrer(),'success');		
        }else{
            remind::set(Kohana::lang('o_global.position_error'),request::referrer(),'error');		
        }
    }

    /**
     * 分类商品排序向下
     */
    public function doedit_product_position_down() 
    {
        $id			= $this->input->get('id');

        $position	= 'down';

        $category_product   = Mycategory_product::instance($id)->get();
        if(!$category_product['id']){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        $site_id_list = role::check('product_virtual_category',0,0);
        // 权限验证
        if(!in_array($category_product['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');	
        }   

        if(Mycategory_product::instance()->position($id,$position))
        {
            remind::set(Kohana::lang('o_global.position_success'),request::referrer(),'success');		
        }else{
            remind::set(Kohana::lang('o_global.position_error'),request::referrer(),'error');		
        }
    }

    //删除分类商品
    public function do_delete_product(){
        //收集请求
        $id		= $this->input->get('id');

        // 验证 - 分类数据有效性
        $category_product   = Mycategory_product::instance($id)->get();
        if(!$category_product['id'])
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        $site_id_list = role::check('product_virtual_category',0,0);
        // 权限验证
        if(!in_array($category_product['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }   
        if(Mycategory_product::instance()->delete($id))
        {
            remind::set(Kohana::lang('o_global.delete_success'),request::referrer(),'success');
        }else{
            remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
        }
    }

    public function do_delete_product_all()
    {
        $category_prdouct_id_array		= $this->input->post('id');

        if(!(is_array($category_prdouct_id_array)&&count($category_prdouct_id_array)))
        {
            remind::set(Kohana::lang('o_product.select_vir_category_product'),request::referrer(),'error');
        }

        // 验证 - 权限
        $site_id_list		= role::check('product_virtual_category',0,0);

        $count			= 0;
        $false_count	= 0;
        foreach($category_prdouct_id_array as $key=>$rs)
        {
            // 验证 - 分类数据有效性
            $category_product   = Mycategory_product::instance($rs)->get();
            if(!$category_product['id'])
            {
                $false_count++;
                continue;	
            }

            // 权限验证
            if(!in_array($category_product['site_id'],$site_id_list)){
                $false_count++;
                continue;
            }   

            if(Mycategory_product::instance()->delete($rs))
            {
                $count++;
            }
            else
            {
                $false_count++;
            }
        }

        if($false_count)
        {
            remind::set(Kohana::lang('o_product.have').$false_count.Kohana::lang('o_product.num_category_product_cannot_delete'),request::referrer(),'error');
        }
        else
        {
            remind::set(Kohana::lang('o_product.success_delete').$count.Kohana::lang('o_product.num_category_product'),request::referrer(),'success');
        }
    }

    /**
     * 分类编辑显示
     */
    public function add_product() {
        $id			= $this->input->get('id'); 	

        $category   = Mycategory::instance($id)->get();

        if(!$category['id'])
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        $site_id_list = role::check('product_virtual_category',0,0);
        // 权限验证
        if(!in_array($category['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }        

        $this->template->content = new View("product/virtualcategory_product_add");				

        $this->template->content->data					= $category;
        $this->template->content->site					= Mysite::instance($category['site_id'])->get();

        $this->template->content->product_list			= Myproduct::instance()->site_product_select_list($category['site_id']);
    }

    /**
     * 分类编辑操作
     */
    public function do_add_product(){
        //收集请求
        $request = $this->input->post();

        $id			= $request['id'];
        // 验证 - 数据有效性
        $category   = Mycategory::instance($id)->get();
        if(!$category['id'] || $category['virtual']==0)
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        $site_id_list = role::check('product_virtual_category',0,0);
        // 权限验证
        if(!in_array($category['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');	
        }     
        $count			= 0;
        if(isset($request['product_ids'])&&is_array($request['product_ids'])&&count($request['product_ids']))
        {
            $count		+= Mycategory_product::instance()->add_product_by_ids($id,$request['product_ids']);
        }


        if(isset($request['SKUs'])&&trim($request['SKUs']))
        {
            $sku_arr = explode("\n",$request['SKUs']);
			foreach($sku_arr as $key=>$rs)
			{
				if(!trim($rs))
				{
					unset($sku_arr[$key]);
				}
			}
            if(count($sku_arr))
            {
                $count		+= Mycategory_product::instance()->add_product_by_SKUs($id,$sku_arr);

            }
        }
        remind::set(Kohana::lang('o_product.update_vir_category_success').$count.Kohana::lang('o_product.num_category_product_update'),'product/virtualcategory/product_list?id='.$category['id'],'success');

    }
    /************************************************************************************/
    /*********************     分类属性关联列表                     *********************/ 
    /************************************************************************************/

    //查看分类属性关联
    public function category_features(){
        // 初始化默认查询条件
        $request_struct_current = array(
            'where'=>array(
                'site_id'   => NULL,
            ),
            'like'=>array(),
            'orderby'   => array(
                'id'   =>'DESC',
            ),
            'limit'     => array(
                'per_page'  =>20,
                'offset'    =>0,
            ),
        );

        // 收集请求数据
        $request_data	= $this->input->get();
        $category_id	= $request_data['id'];

        // 验证 - 数据有效性
        $category   = Mycategory::instance($category_id)->get();
        if(!$category['id'])
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');	
        }

        /* b2c类型的站点列表 */
        $site_id_list		= role::check('product_virtual_category',0,0);

        // 权限验证
        if(empty($site_id_list))
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');
        }
        if(!in_array($category['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }     

        // 排序处理 
        controller_tool::request_orderby($request_struct_current,$request_data);
        // 每页条目数
        controller_tool::request_per_page($request_struct_current,$request_data);

        // 站点查询
        $request_struct_current['where']['site_id']     = $category['site_id'];

        //当前查询
        $request_struct_current['where']['category_id'] = $category['id'];


        $count						= Mycategory_feature::instance()->count($request_struct_current);

        // 模板输出 分页
        $this->pagination       = new Pagination(array(
            'total_items'    => $count,
            'items_per_page' => $request_struct_current['limit']['per_page'],
        ));

        $request_struct_current['limit']['offset']      = $this->pagination->sql_offset;
        
        $category_feature_list		= Mycategory_feature::instance()->lists($request_struct_current);
        foreach($category_feature_list as $key=>$rs)
        {
            $category_feature_list[$key]['site']		= Mysite::instance($rs['site_id'])->get();	
            if($rs['type'] == 1){
                $category_feature_list[$key]['type'] = '规格';
                $category_feature_list[$key]['group_id'] = Myattribute_group::instance($rs['group_id'])->get('name');
                $category_feature_list[$key]['value_id'] = Myattribute::instance($rs['value_id'])->get('name');
            }
            if($rs['type'] == 2){
                $category_feature_list[$key]['type'] = '附加属性';
                $category_feature_list[$key]['group_id'] = Myfeature_group::instance($rs['group_id'])->get('name');
                $category_feature_list[$key]['value_id'] = Myfeature::instance($rs['value_id'])->get('name');
            }
        }

        // 模板输出
        $this->template->content                    = new View("product/virtualcategory_features");

        // 变量绑定
        $this->template->content->category_feature_list      = $category_feature_list;
        $this->template->content->count             = $count;
        
        // 当前应用专用数据
        $this->template->content->data				= $category;

    }

    //更新分类属性关联
    public function update_category_features(){
        $category_id			= $this->input->get('id');

        // 验证 - 数据有效性
        $category   = Mycategory::instance($category_id)->get();
        if(!$category['id'])
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        /* b2c类型的站点列表 */
        $site_id_list		= role::check('product_virtual_category',0,0);
        //权限验证
        if(!in_array($category['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }  

        Mycategory_feature::instance()->update_category_features($category_id);
        remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
    }

    /**
     * 分类属性关联排序向上
     */
    public function doedit_category_feature_position_up() 
    {
        $id			= $this->input->get('id');

        $position	= 'up';
        $category_feature   = Mycategory_feature::instance($id)->get();

        if(!$category_feature['id']){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');	
        }

        /* b2c类型的站点列表 */
        $site_id_list		= role::check('product_virtual_category',0,0);
        //权限验证
        if(!in_array($category_feature['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }  

        if(Mycategory_feature::instance()->position($id,$position))
        {
            remind::set(Kohana::lang('o_global.position_success'),request::referrer(),'success');		
        }else{
            remind::set(Kohana::lang('o_global.position_error'),request::referrer(),'error');		
        }
    }

    /**
     * 分类属性关联排序向下
     */
    public function doedit_category_feature_position_down() 
    {
        $id			= $this->input->get('id');

        $position	= 'down';

        $category_feature   = Mycategory_feature::instance($id)->get();
        if(!$category_feature['id']){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        /* b2c类型的站点列表 */
        $site_id_list		= role::check('product_virtual_category',0,0);
        //权限验证
        if(!in_array($category_feature['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }  


        if(Mycategory_feature::instance()->position($id,$position))
        {
            remind::set(Kohana::lang('o_global.position_success'),request::referrer(),'success');		
        }else{
            remind::set(Kohana::lang('o_global.position_error'),request::referrer(),'error');		
        }
    }

    //删除分类属性关联
    public function do_delete_category_feature(){
        //收集请求
        $id = $this->input->get('id');

        // 验证 - 数据有效性
        $category_feature   = Mycategory_feature::instance($id)->get();
        if(!$category_feature['id'])
        {
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');		
        }

        /* b2c类型的站点列表 */
        $site_id_list		= role::check('product_virtual_category',0,0);
        //权限验证
        if(!in_array($category_feature['site_id'],$site_id_list)){
            remind::set(Kohana::lang('o_global.access_denied'),request::referrer(),'error');	
        }  

        if(Mycategory_feature::instance()->delete($id))
        {
            remind::set(Kohana::lang('o_global.delete_success'),request::referrer(),'success');
        }else{
            remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
        }
    }

    //批量删除分类属性关联
    public function do_delete_category_feature_all()
    {
        $category_feature_id_array		= $this->input->post('id');

        if(!(is_array($category_feature_id_array)&&count($category_feature_id_array)))
        {
            remind::set(Kohana::lang('o_product.select_category_about'),request::referrer(),'error');
        }

        // 验证 - 权限
        $site_id_list		= role::check('product_virtual_category',0,0);

        $count			= 0;
        $false_count	= 0;
        foreach($category_feature_id_array as $key=>$rs)
        {
            // 验证 - 数据有效性
            $category_feature   = Mycategory_feature::instance($rs)->get();
            if(!$category_feature['id'])
            {
                $false_count++;
                continue;	
            }

            //权限验证
            if(!in_array($category_feature['site_id'],$site_id_list)){
                $false_count++;
                continue;
            }  

            if(Mycategory_feature::instance()->delete($rs))
            {
                $count++;
            }
            else
            {
                $false_count++;
            }
        }
        if($false_count)
        {
            remind::set(Kohana::lang('o_product.have').$false_count.Kohana::lang('o_product.num_category_about_cannot_delete'),request::referrer(),'error');
        }
        else
        {
            remind::set(Kohana::lang('o_product.success_delete').$count.Kohana::lang('o_product.num_category_about'),request::referrer(),'success');
        }
    }
}
?>
