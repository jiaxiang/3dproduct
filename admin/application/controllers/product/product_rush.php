<?php defined('SYSPATH') or die('No direct access allowed.');

class Product_rush_Controller extends Template_Controller {
	private $package_name = '';
	private $class_name = '';
	
	/**
	 * 构造方法
	 */
	public function __construct(){
		$this->package_name = 'product';
		$this->class_name = 'product_rush';
		parent::__construct();
		if ($this->is_ajax_request()){
			$this->template = new View('layout/default_json');
		}
	}
	
	/**
	 * 数据列表
	 */
	public function index(){
		role::check('product_rush');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		try {
			//* 初始化返回数据 */
			$return_data = $rid = array ();
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->get();
			
			// 执行业务逻辑
			//* 初始化默认查询结构体 */
			$query_struct_current = array (
				'where' => array (), 
				'like' => array (), 
				'orderby' => array (
					'id' => 'DESC'
				), 
				'limit' => array (
					'per_page' => 20, 
					'page' => 1
				)
			);
			
			//列表排序
			$orderby_arr = array (
				0 => array (
					'id' => 'DESC'
				), 
				1 => array (
					'id' => 'ASC'
				), 
				2 => array (
					'title' => 'ASC'
				), 
				3 => array (
					'title' => 'DESC'
				), 
				4 => array (
					'start_time' => 'ASC'
				), 
				5 => array (
					'end_time' => 'DESC'
				), 
				6 => array (
					'position' => 'DESC'
				), 
				7 => array (
					'position' => 'ASC'
				)
			);
			$orderby = controller_tool::orderby($orderby_arr);
			// 排序处理 
			if (isset($request_data['orderby']) && is_numeric($request_data['orderby']))
			{
				$query_struct_current['orderby'] = $orderby;
			}
			// 每页条目数
			controller_tool::request_per_page($query_struct_current, $request_data);
			
			//调用服务执行查询
			$product_rush_service = Product_rushService::get_instance();
			$count = $product_rush_service->count($query_struct_current);
			// 模板输出 分页
			$this->pagination = new Pagination(array (
				'total_items' => $count, 
				'items_per_page' => $query_struct_current['limit']['per_page']
			));
			$query_struct_current['limit']['page'] = $this->pagination->current_page;
			
			$return_data['list'] = $product_rush_service->index($query_struct_current);
			foreach($return_data['list'] as $pd){
                $rid[$pd['product_id']] = true;
            }
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '';
			$return_struct['content'] = $return_data;
			
			//* 请求类型 */
			if ($this->is_ajax_request())
			{
				// ajax 请求
				// json 输出
				$this->template->content = $return_struct;
			} else
			{
				// html 输出
				//* 模板输出 */
				$content = new View('product/' . $this->class_name . '/' . __FUNCTION__, array('rid'=>$rid));
				//* 变量绑定 */
				$this->template->title = '抢购商品管理';
				$this->template->content = $content;
				//* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				//* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
			} // end of request type determine
		} catch (MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
		}
	}
	
	public function edit(){
        role::check('product_rush');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		try{ 
			$return_data = array ();
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->get();
			
			$product_rush_service = Product_rushService::get_instance();
            if($_POST){
                $data = array();
                $data['id'] = $_POST['id'];
                $data['store'] = $_POST['store'];
                $data['price_rush'] = $_POST['price_rush'];
                $data['price'] = $_POST['price'];
                $data['max_buy'] = $_POST['max_buy'];
                $data['start_time'] = $_POST['start_date'].' '.$_POST['start_time'];
                $data['end_time'] = $_POST['end_date'].' '.$_POST['end_time']; 
                $product_rush_service->update($data);
                remind::set(Kohana::lang('o_global.update_success'), 'product/product_rush', 'success');
            }
            
			//数据验证
			if(!isset($request_data['id']) || !is_numeric($request_data['id'])){
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
			}			
            
			$product_rush = $product_rush_service->get($request_data['id']);
            
			//返回数据
			$return_data['data'] = $product_rush;
			
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '';
			$return_struct['content'] = $return_data;
			
			//* 请求类型 */
			if ($this->is_ajax_request())
			{
				// ajax 请求
				// json 输出
				$this->template->content = $return_struct;
			} 
            else
			{
				// html 输出
				//* 模板输出 */
				$content = new View('product/' . $this->class_name . '/edit');
				//* 变量绑定 */
				$this->template->title = Kohana::config('site.name');
				$this->template->content = $content;
				//* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				//* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
			} // end of request type determine

		} 
        catch (MyRuntimeException $ex)
		{
            $this->_ex($ex, $return_struct, $request_data);
		}
	}
    
	public function delete(){
        role::check('product_rush');
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
			$product_rush_service = Product_rushService::get_instance();
			if (!isset($request_data['id']) || !is_numeric($request_data['id'])){
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
			}
            
			//执行删除
			$product_rush_service->delete($request_data['id']);
			
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '删除成功';
			$return_struct['content'] = $return_data;
			$return_struct['action'] = array (
				'type' => 'location', 
				'url' => url::base() . 'product/' . $this->class_name . '/index'
			);
			
			//* 请求类型 */
			if ($this->is_ajax_request())
			{
				// ajax 请求
				// json 输出
				$this->template->content = $return_struct;
			} else
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
        catch (MyRuntimeException $ex)
		{
            $this->_ex($ex, $return_struct, $request_data);
		}
	}
	
	public function delete_all(){
        role::check('product_rush');
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
            
			if(!isset($request_data['id']) || empty($request_data['id'])){
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
			}
            
			$product_rush_service = Product_rushService::get_instance();
			foreach($request_data['id'] as $id){
				$product_rush_service->delete($id);
			}
			
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '删除成功';
			$return_struct['content'] = $return_data;
			$return_struct['action'] = array(
				'type' => 'location', 
				'url' => url::base() . 'product/' . $this->class_name . '/' . 'index'
			);
			
			//* 请求类型 */
			if ($this->is_ajax_request())
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
        catch (MyRuntimeException $ex)
		{
            $this->_ex($ex, $return_struct, $request_data);
		}
	}
	
	public function add_products(){
		role::check('product_rush');
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
            
			$product_rush_service = Product_rushService::get_instance();
			
			$relations = $product_rush_service->index(array());
			$product_ids = array ();
			foreach ($relations as $relation){
				$product_ids[] = $relation['product_id'];
			}
			
			$struct = product::get_struct($request_data);
			$query_struct_current = $struct['query'];
			$request_struct_current = $struct['request'];
			
			//* 调用后端服务获取数据 */
			$product_service = ProductService::get_instance();
			$return_data['assoc'] = $product_service->index($query_struct_current);
			$return_data['count'] = $product_service->count($query_struct_current);
			// 初始化分类列表
			$category_list = array ();
			// 初始化品牌列表
			$brand_list = array ();
			if (!empty($return_data['assoc'])){
				// 获取该商品列表中涉及到的分类以及品牌ID
				$category_ids = array ();
				$brand_ids = array ();
				foreach ($return_data['assoc'] as $product)
				{
					if ($product['category_id'] != 0)
					{
						$category_ids[$product['category_id']] = TRUE;
					}
					if ($product['brand_id'] != 0)
					{
						$brand_ids[$product['brand_id']] = TRUE;
					}
				}
				
				// 获取分类列表
				if (!empty($category_ids))
				{
					$query_struct = array (
						'where' => array (
							'id' => array_keys($category_ids)
						)
					);
					$categorys = CategoryService::get_instance()->query_assoc($query_struct);
					foreach ($categorys as $category)
					{
						$category_list[$category['id']] = $category['title_manage'];
					}
				}
				
				// 获取品牌列表
				if (!empty($brand_ids))
				{
					$query_struct = array (
						'where' => array (
							'id' => array_keys($brand_ids)
						)
					);
					$brands = BrandService::get_instance()->query_assoc($query_struct);
					foreach ($brands as $brand)
					{
						$brand_list[$brand['id']] = $brand['name'];
					}
				}
			}
			
			// 模板输出 分页
			$this->pagination = new Pagination(array (
				'total_items' => $return_data['count'], 
				'items_per_page' => $query_struct_current['limit']['per_page']
			));
			//* 如果是ajax请求缩减返回的字段 ==根据业务逻辑定制== */
			if ($this->is_ajax_request()) {
				$requestkeys = array (
					'id', 
					'category_id', 
					'title', 
					'uri_name', 
					'store', 
					'on_sale', 
					'goods_price', 
					'sku'
				);
				array_walk($return_data['assoc'], 'util::simplify_return_array', $requestkeys);
			}
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '';
			$return_struct['content'] = $return_data;
			
			//exit("<div id=\"do_debug\" style=\"clear:both;display:;\"><pre>\n".var_export($return_struct,TRUE)."\n</pre></div>");
			//* 请求类型 */
			if ($this->is_ajax_request()) {
				// ajax 请求
				// json 输出
				$this->template->content = $return_struct;
			} else {
				//* html 输出 ==根据业务逻辑定制== */
				$this->template = new View('layout/commonfix_html');
				//* 模板输出 */
				$this->template->return_struct = $return_struct;
				
				$content = new View($this->package_name . '/' . $this->class_name . '/' . __FUNCTION__);
				//* 变量绑定 */
				$this->template->title = Kohana::config('site.name');
				$this->template->content = $content;
				//* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				//* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
				//:: 当前应用专用数据
				$this->template->content->title = Kohana::config('site.name');
				$this->template->content->product_ids = $product_ids;
				$this->template->content->category_list = $category_list;
				$this->template->content->brand_list = $brand_list;
			
			} // end of request type determine

		} catch (MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
		}
	}
	
	public function put_products(){
		role::check('product_rush');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		try{
			//$profiler = new Profiler;
			//* 初始化返回数据 */
			$return_data = $ids = array ();
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->post();        
            $relation_id = explode(',', $request_data['relation_id']);
			if(empty($relation_id) || !is_array($relation_id)){
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
			}
            
            $products = ProductService::get_instance()->index(array('where'=>array('id'=>$relation_id)));

            $product_rush_service = Product_rushService::get_instance(); 
            $product_rush = $product_rush_service->index(array());
            foreach($product_rush as $p){
                $ids[] = $p['product_id'];
            }
			foreach($products as $product){
				if(!in_array($product['id'], $ids)){
					$data = array ();
					$data['product_id'] = $product['id'];
					$data['store'] = $product['store'];
					$data['price_rush'] = $product['price'];
					$data['price'] = $product['price'];
					$data['title'] = $product['title'];                    
					$data['max_buy'] = 1;
					$data['start_time'] = date('Y-m-d').' 00:00:00';
					$data['end_time'] = date('Y-m-d').' 23:59:59';
					$result = $product_rush_service->add($data);
					if (!$result) {
						throw new MyRuntimeException('Internal Error', 500);
					}
				}
			}
			
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '添加成功！';
			$return_struct['content'] = $return_data;
			
			//* 请求类型 */
			if ($this->is_ajax_request())
			{
				// ajax 请求
				// json 输出
				$this->template->content = $return_struct;
			} else
			{
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
		

		} catch (MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
		}
	}
	
    
	/**
	 * 设定菜单的排序
	 */
	public function set_order(){
		//初始化返回数组
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		$request_data = $this->input->get();
		$id = isset($request_data['id']) ? $request_data['id'] : '';
		$order = isset($request_data['order']) ? $request_data['order'] : '';

		if (empty($id)){
			$return_struct['msg'] = Kohana::lang('o_global.bad_request');
			exit(json_encode($return_struct));
		}
		if (!is_numeric($order) || $order < 0){
			$return_struct['msg'] = Kohana::lang('o_global.position_rule');
			exit(json_encode($return_struct));
		}
		$product_rush_service = Product_rushService::get_instance();
		$product_rush_service->set($id, array (
			'position' => $order
		));
		$return_struct = array (
			'status' => 1, 
			'code' => 200, 
			'msg' => Kohana::lang('o_global.position_success'), 
			'content' => array (
				'order' => $order
			)
		);
		exit(json_encode($return_struct));
	}
	 
}
