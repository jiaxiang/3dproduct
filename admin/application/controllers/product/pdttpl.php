<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 区域分析
 * 
 */
class Pdttpl_Controller extends Template_Controller {
	
	private $package_name = '';
    private $class_name = '';
	public $template_ = 'layout/common_html';
	
	public $site_ids;
	public $site_id;
	
	/**
     * 构造方法
     */
    public function __construct()
    {
    	$package_name = substr(dirname(__FILE__),strlen(APPPATH.'controllers/'));
        empty($package_name) && $package_name = 'default';
        $this->package_name = $package_name;
        $this->class_name = strtolower(substr(__CLASS__,0,strpos(__CLASS__,'_')));
        $this->site_ids = role::get_site_ids();
        $this->site_id = site::id();
        parent::__construct();
        if($this->is_ajax_request()==TRUE){
            $this->template = new View('layout/default_json');
        }
    }
    
	public function index(){
		$return_struct = array (
            'status' => 0, 
            'code' => 501, 
            'msg' => 'Not Implemented', 
            'content' => array () 
        );
        try {
        	//* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $in_site_id = site::id();
            
            if (isset($request_data['site_id']) AND $request_data['site_id'] === '0')
            {
            	unset($request_data['site_id']);
            }
            
       		if (isset($request_data['site_id']) AND !in_array($request_data['site_id'], $site_ids))
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            if ($in_site_id > 0){
            	$query_site_id = $in_site_id;
            } else {
            	throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            
            $struct = product::get_struct($request_data, $query_site_id);
            $query_struct_current   = $struct['query'];
            $request_struct_current = $struct['request'];
            $query_struct_current['limit']['per_page'] = 1;
            $return_data = BLL_Product::index($query_struct_current);
            
            $products = $return_data['assoc'];
            
			$content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
			$this->template->content = $content;
			$this->template->content->products = $products;
			
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
	
	public function product_as_template(){
		$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       try {
            //* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $in_site_id = site::id();
            
            if (isset($request_data['site_id']) AND $request_data['site_id'] === '0')
            {
            	unset($request_data['site_id']);
            }
            
       		if (isset($request_data['site_id']) AND !in_array($request_data['site_id'], $site_ids))
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            if ($in_site_id > 0){
            	$query_site_id = $in_site_id;
            } else {
            	throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            
            $product = BLL_Product::get($request_data['product_id']);
            $classify = ClassifyService::get_instance()->index(array('where' => array('site_id' => $query_site_id, 'id' => $product['classify_id'])));
            $product['classify_name'] = isset( $classify[0]['name'] ) ? $classify[0]['name'] : '通用商品类型';
            $category = CategoryService::get_instance()->index(array('where' => array('site_id' => $query_site_id, 'id' => $product['category_id'])));
            $product['category_name'] = isset( $category[0]['name'] ) ? $category[0]['name'] : '通用商品分类';
            $brand = BrandService::get_instance()->index(array('where' => array('site_id' => $query_site_id, 'id' => $product['brand_id'])));
            $product['brand_name'] = isset( $brand[0]['name'] ) ? $brand[0]['name'] : '通用商品分类';
            
            if( !empty($product['fetuoptrs']) ){
            	$string = '';
            	$arr = BLL_Product_Feature::get_clsfeturs($product['classify_id']);
            	foreach ($product['fetuoptrs'] as $key => $value){
            		$string .= $arr[$key]['name_manage'] . ':' . $arr[$key]['options'][$value]['name_manage'] .'&nbsp;&nbsp;&nbsp;';
            	}
            	if ($string != '') {
            		$product['fetuoptrs_v'] = $string;
            	}
            }else {
            	$product['fetuoptrs_v'] = '没有设置商品特性';
            }
			
            if ($product['site_id'] != $query_site_id) {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
			
            if( Product_templateService::get_instance()->is_template_exist($query_site_id) ){
	            $template = Product_templateService::get_instance()->get_template_by_site($query_site_id);
	            $classify = ClassifyService::get_instance()->index(array('where' => array('site_id' => $query_site_id, 'id' => $template['classify_id'])));
	            $template['classify_name'] = isset( $classify[0]['name'] ) ? $classify[0]['name'] : '通用商品类型';
	            
	            $category = CategoryService::get_instance()->index(array('where' => array('site_id' => $query_site_id, 'id' => $template['category_id'])));
            	$template['category_name'] = isset( $category[0]['name'] ) ? $category[0]['name'] : '通用商品分类';
            	
            	$brand = BrandService::get_instance()->index(array('where' => array('site_id' => $query_site_id, 'id' => $template['brand_id'])));
            	$template['brand_name'] = isset( $brand[0]['name'] ) ? $brand[0]['name'] : '无';
	            
	            $template['fetuoptrs'] = json_decode($template['product_featureoption_relation_struct']);
	            if( !empty($template['fetuoptrs']) ){
	            	$string = '';
	            	$arr = BLL_Product_Feature::get_clsfeturs($template['classify_id']);
	            	foreach ($template['fetuoptrs']->items as $key => $value){
	            		if (isset($arr[$key])) {
	            			$string .= $arr[$key]['name_manage'] . ':' . $arr[$key]['options'][$value]['name_manage'] .'&nbsp;&nbsp;&nbsp;';
	            		}
	            	}
	            	if ($string != '') {
	            		$template['fetuoptrs_v'] = $string;
	            	}
	            }else {
	            	$template['fetuoptrs_v'] = '没有设置商品特性';
	            }
            }else {
            	$template = array();
            }
            
            //* 补充&修改返回结构体 */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '';
            $return_struct['content']= $return_data;
            
            //* 请求类型 */
            if($this->is_ajax_request()){
            	
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            }else{
            	
                // html 输出
                $this->template = new View('layout/commonblank_html');
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->product = $product;
                $this->template->content->template = $template;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
                
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
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
	
	public function set(){
		$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       try {
            //* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            if (empty($request_data)) {
            	$request_data = array_merge($_GET,$_POST);
            }
            
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $in_site_id = site::id();
            
            if (isset($request_data['site_id']) AND $request_data['site_id'] === '0')
            {
            	unset($request_data['site_id']);
            }
            
       		if (isset($request_data['site_id']) AND !in_array($request_data['site_id'], $site_ids))
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            if ($in_site_id > 0){
            	$query_site_id = $in_site_id;
            } else {
            	throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
            //$product = ProductService::get_instance()->get($request_data['product_id']);
            $product = BLL_Product::get($request_data['product_id']);
            
            if ($product['site_id'] != $query_site_id) {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            
            BLL_Pdttpl::set_product_template($request_data,$product);
			
            //* 补充&修改返回结构体 */
            //* 补充&修改返回结构体 ==根据业务逻辑定制== */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = Kohana::lang('o_product.edit_product_success');
            $return_struct['content']= $return_data;
    		
            $return_struct['action'] = array(
                'type'=>'location',
                'url'=>!empty($request_data['listurl']) ? url::base().$request_data['listurl'] : url::base().$this->package_name.'/'.$this->class_name.'/'.'index'
            );
    		
    		//* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            } else {
                // html 输出
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
                
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
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
	
	public function search_products(){
		$return_struct = array(
			'status'        => 0,
			'code'          => 501,
			'msg'           => 'Not Implemented',
			'content'       => array(),
		);
		try {
			//* 初始化返回数据 */
			$return_data = array(
				'assoc'         => NULL,
				'count'         => 0,
			);
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->get();
			if (empty($request_data)) {
				$request_data = array_merge($_POST,$_GET);
			}
			
			$site_ids = role::get_site_ids();
			
			if (empty($site_ids)){
				throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
			}
			
			$in_site_id = site::id();
			
			if (isset($request_data['site_id']) AND $request_data['site_id'] === '0')
			{
				unset($request_data['site_id']);
			}
			
			if (isset($request_data['site_id']) AND !in_array($request_data['site_id'], $site_ids))
			{
				throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
			}
			if ($in_site_id > 0){
				$query_site_id = $in_site_id;
			} else {
				throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
			}
			$page = isset( $request_data['page'] ) ? intval($request_data['page']) : 1;
			$page < 1 ? $page = 1 : '';
			$per_page = 10;
			
			$query_struct = array(
				'where' => array( 'site_id'=> $query_site_id, 'status' => 1, ),
				'limit' => array('page' => $page, 'per_page' => $per_page),
			);
			
			if ( isset($request_data['type']) && trim($request_data['keyword']) != '' ) {
				$select_key = mysql_escape_string($request_data['keyword']);
				
				if ($request_data['type'] == 'sku') {
					$query_struct['where']['sku'] = $select_key;
					
				}elseif ($request_data['type'] == 'name_manage'){
					$query_struct['like']['name_manage'] = $select_key;
					
				}elseif ($request_data['type'] == 'title'){
					$query_struct['like']['title'] = $select_key;
					
				}elseif ($request_data['type'] == 'category_id'){
					$categories_select = CategoryService::get_instance()->index(array(
							'where' => array( 'site_id' => $query_site_id),
							'like'  => array( 'title' => $select_key,),
					));
					if ( !empty($categories_select) ) {
						$categories_ids = array();
						for ($i=0; $i<count($categories_select); $i++){
							$categories_ids[] = $categories_select[$i]['id'];
						}
						$query_struct['where']['category_id'] = $categories_ids;
					}
					
				}elseif ($request_data['type'] == 'brand_id'){
					$brands_select = BrandService::get_instance()->index(array(
							'where' => array( 'site_id' => $query_site_id),
							'like'  => array( 'name' => $select_key,),
					));
					if ( !empty($brands_select) ) {
						$brands_ids = array();
						for ($i=0; $i<count($brands_select); $i++){
							$brands_ids[] = $brands_select[$i]['id'];
						}
						$query_struct['where']['brand_id'] = $brands_ids;
					}else {
						$query_struct['where']['brand_id'] = -1;
					}
				}
			}
			//print_r($query_struct);
			$return_data = BLL_Product::index($query_struct);
			$this->pagination = new Pagination(array(
				'total_items'    => $return_data['count'],
				'items_per_page' => $per_page,
			));
			
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code']   = 200;
			$return_struct['msg']    = '';
			$return_struct['content']= $return_data;
			
			//* 请求类型 */
			if($this->is_ajax_request()){
			
				// ajax 请求
				// json 输出
				$this->template->content = $return_struct;
			}else{
				// html 输出
				$this->template = new View('layout/commonblank_html');
				//* 模板输出 */
				$this->template->return_struct = $return_struct;
				$content = new View($this->package_name.'/'.$this->class_name.'/'.__FUNCTION__);
				//* 变量绑定 */
				$this->template->title = Kohana::config('site.name');
				$this->template->content = $content;
				//* 请求结构数据绑定 */
				$this->template->content->products = $return_data['assoc'];
				$this->template->content->pagination = $this->pagination;
				if (isset($request_data['type'])){
					$this->template->content->search_type = $request_data['type'];
				}
				$this->template->content->pagination = $this->pagination;
				//:: 当前应用专用数据
				$this->template->content->title = Kohana::config('site.name');
				
			}// end of request type determine
		
		}catch(MyRuntimeException $ex) {
			$return_struct['status'] = 0;
			$return_struct['code']   = $ex->getCode();
			$return_struct['msg']    = $ex->getMessage();
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
	
	public function delete(){
		$return_struct = array(
            'status'        => 0,
            'code'          => 501,
            'msg'           => 'Not Implemented',
            'content'       => array(),
        );
       try {
            //* 初始化返回数据 */
            $return_data = array(
                'assoc'         => NULL,
                'count'         => 0,
            );
            //* 收集请求数据 ==根据业务逻辑定制== */
            $request_data = $this->input->get();
            
            $site_ids = role::get_site_ids();
            if (empty($site_ids))
            {
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
            }
            
            $in_site_id = site::id();
            
            if (isset($request_data['site_id']) AND $request_data['site_id'] === '0')
            {
            	unset($request_data['site_id']);
            }
            
       		if (isset($request_data['site_id']) AND !in_array($request_data['site_id'], $site_ids))
       		{
            	throw new MyRuntimeException(Kohana::lang('o_global.access_denied'),403);
            }
            if ($in_site_id > 0){
            	$query_site_id = $in_site_id;
            } else {
            	throw new MyRuntimeException(Kohana::lang('o_global.select_site'), 400);
            }
			$template_id = intval($request_data['template_id']);
            $template = Product_templateService::get_instance()->index( array('where' => array('id'=>$template_id)) );
            if (!empty($template)) {
	            if ($template[0]['site_id'] == $query_site_id) {
	            	ORM::factory('product_template')->where('id', $template_id)->delete_all();
	            }else {
	            	throw new MyRuntimeException('没有权限删除该商品模板！', 403);
	            }
            }else {
            	throw new MyRuntimeException('该模板不存在，或者已被删除！', 403);
            }
            //* 补充&修改返回结构体 */
            //* 补充&修改返回结构体 ==根据业务逻辑定制== */
            $return_struct['status'] = 1;
            $return_struct['code']   = 200;
            $return_struct['msg']    = '商品模板已经成功删除！';
            $return_struct['content']= $return_data;
    		
            $return_struct['action'] = array(
                'type'=>'location',
                'url'=>!empty($request_data['listurl']) ? url::base().$request_data['listurl'] : url::base().$this->package_name.'/'.$this->class_name.'/'.'index'
            );
    		
    		//* 请求类型 */
            if($this->is_ajax_request()){
                // ajax 请求
                // json 输出
                $this->template->content = $return_struct;
            } else {
                // html 输出
                //* 模板输出 */
                $this->template->return_struct = $return_struct;
                $content = new View('info');
                //* 变量绑定 */
                $this->template->title = Kohana::config('site.name');
                $this->template->content = $content;
                //* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                //* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
                //:: 当前应用专用数据
                $this->template->content->title = Kohana::config('site.name');
                
            }// end of request type determine

        }catch(MyRuntimeException $ex) {
            $return_struct['status'] = 0;
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
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