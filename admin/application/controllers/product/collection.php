<?php
defined('SYSPATH') or die('No direct access allowed.');
class Collection_Controller extends Template_Controller {
	// Disable this controller when Kohana is set to production mode.
	const ALLOW_PRODUCTION = TRUE;
	private $package_name = '';
	private $class_name = '';
	// Set the name of the template to use
	public $template_ = 'layout/common_html';
	
	/**
	 * 构造方法
	 */
	public function __construct()
	{
		$package_name = substr(dirname(__FILE__), strlen(APPPATH . 'controllers/'));
		empty($package_name) && $package_name = 'default';
		$this->package_name = $package_name;
		$this->class_name = strtolower(substr(__CLASS__, 0, strpos(__CLASS__, '_')));
		parent::__construct();
		if ($this->is_ajax_request())
		{
			$this->template = new View('layout/default_json');
		}
	}
	
	/**
	 * 数据列表
	 */
	public function index()
	{
		role::check('product_collection');
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
			
			// 执行业务逻辑
			//* 初始化默认查询结构体 */
			$query_struct_default = array (
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
					'title' => 'ASC'
				), 
				3 => array (
					'title' => 'DESC'
				), 
				4 => array (
					'update_timestamp' => 'ASC'
				), 
				5 => array (
					'update_timestamp' => 'DESC'
				), 
				6 => array (
					'order' => 'DESC'
				), 
				7 => array (
					'order' => 'ASC'
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
			$collection_service = CollectionService::get_instance();
			$count = $collection_service->count($query_struct_current);
			// 模板输出 分页
			$this->pagination = new Pagination(array (
				'total_items' => $count, 
				'items_per_page' => $query_struct_current['limit']['per_page']
			));
			$query_struct_current['limit']['page'] = $this->pagination->current_page;
			
			$return_data['list'] = $collection_service->index($query_struct_current);
			
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
				$content = new View('product/' . $this->class_name . '/' . __FUNCTION__);
				//* 变量绑定 */
				$this->template->title = '虚拟集合管理';
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
	
	/**
	 * 添加数据页面
	 */
	public function add()
	{
		role::check('product_collection_add');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		try
		{
			//* 初始化返回数据 */
			$return_data = array ('data'=>array());
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->get();
			
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
				$content = new View('product/' . $this->class_name . '/' . __FUNCTION__);
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
            $this->_ex(&$ex, $return_struct, $request_data);
		}
	}
	
	public function put()
	{
		//* 收集请求数据 ==根据业务逻辑定制== */
		$request_data = $this->input->post();
		$request_data = trims::run($request_data);
		if($request_data['id']>0)
        {
		    role::check('product_collection_edit');
        }
        else
        {
            role::check('product_collection_add');
        }
		
		//标签过滤
		tool::filter_strip_tags($request_data);
        
		$return_struct = array(
			'status' => 0,
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		try
		{
			//* 初始化返回数据 */
			$return_data = array ();
			
			//数据验证
			$validResult = Validation::factory($request_data)->pre_filter('trim')
                                ->add_rules('title', 'required', 'length[1,100]')
                                ->add_rules('lable', 'length[0,32]')
                                ->add_rules('description', 'length[0,1024]')
                                ->add_rules('meta_title', 'length[0,100]')
                                ->add_rules('meta_keywords', 'length[0,255]')
                                ->add_rules('meta_description', 'length[0,255]')
                                ->add_rules('memo', 'length[0,255]');
			
			if ($validResult->validate() == FALSE)
			{
				//* 输出错误的具体信息 ==根据业务逻辑定制== */
				$return_struct['content']['errors'] = $validResult->errors();
				throw new MyRuntimeException(Kohana::lang('o_global.input_error'), 400);
			}
			
			// 调用底层服务
			$collection_service = CollectionService::get_instance();
            if($request_data['id']>0)
            {
                $collection = $collection_service->get($request_data['id']);
                
    		    //判断title是否存在
                if($collection['title'] != $request_data['title'] && $collection_service->check_exist_title($request_data['title']))
                {
                    throw new MyRuntimeException(Kohana::lang('o_product.collection_title_has_exists'), 409);
                }
            }
            else if($collection_service->check_exist_title($request_data['title']))
            {
                throw new MyRuntimeException(Kohana::lang('o_product.collection_title_has_exists'), 409);
            }
			
			//执行添加
			$set_data = array();
            $set_data['title'] = $request_data['title'];
            $set_data['label'] = $request_data['label'];
            $set_data['meta_title'] = $request_data['meta_title'];
            $set_data['meta_keywords'] = $request_data['meta_keywords'];
            $set_data['meta_description'] = $request_data['meta_description'];
            $set_data['description'] = $request_data['description'];
            $set_data['memo'] = $request_data['memo'];
			$set_data['update_timestamp'] = time();
            if($request_data['id']>0)
            {
			    $return_data['id'] = $set_data['id'] = $request_data['id'];
                $collection_service->set($set_data['id'], $set_data);
            }
            else
            {
			    $set_data['create_timestamp'] = time();
			    $return_data['id'] = $collection_service->add($set_data);
            }
			if (!$return_data['id'])
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 500);
			}
			
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '操作成功';
			$return_struct['content'] = $return_data;
			$return_struct['action'] = array (
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
	
	public function edit()
	{
        role::check('product_collection_edit');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		try
		{
			//$profiler = new Profiler;
			//* 初始化返回数据 */
			$return_data = array ();
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->get();
			
			//数据验证
			if (!isset($request_data['id']) || !is_numeric($request_data['id']))
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
			}
			
			$collection_service = CollectionService::get_instance();
			$collection = $collection_service->get($request_data['id']);

			//返回数据
			//$product_ids = $collection_service->get_products_by_collection_id($request_data['id']);
			//$collection['product_ids'] = $product_ids;
			$return_data['data'] = $collection;
			
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
				$content = new View('product/' . $this->class_name . '/add');
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
	
	public function products()
	{
        role::check('product_collection');
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
			
			//数据验证
			if (!isset($request_data['id']) || !is_numeric($request_data['id']))
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
			}
			
			$collection_service = CollectionService::get_instance();
			$collection = $collection_service->get($request_data['id']);
			
			//业务逻辑
			//默认查询结构
			$relation_query_struct = array (
				'where' => array (
					'collection_id' => $request_data['id']
				), 
				'orderby' => array (
					'id' => 'asc'
				)
			);
			// 排序处理 
			//列表排序
			$relation_orderby_arr = array (
				0 => array (
					'order' => 'DESC'
				), 
				1 => array (
					'order' => 'ASC'
				)
			);
			
			$relations = Collection_product_relationService::get_instance()->index($relation_query_struct);
			$product_ids = array ();
			$relation_products = array ();
			$relation_product_ids = array ();
			foreach ($relations as $relation)
			{
				$relation_products[$relation['product_id']]['relation_id'] = $relation['id'];
				$relation_products[$relation['product_id']]['relation_order'] = $relation['order'];
				$product_ids[] = $relation['product_id'];
				$relation_product_ids[$relation['product_id']] = true;
			}
			
			$products = array (); //初始化返回数据
			if (isset($request_data['orderby']) && isset($relation_orderby_arr[$request_data['orderby']]))
			{
				$relation_query_struct['orderby'] = array_merge($relation_orderby_arr[$request_data['orderby']], $relation_query_struct['orderby']);
				$relations = Collection_product_relationService::get_instance()->index($relation_query_struct);
				$relation_products = array ();
				foreach ($relations as $relation)
				{
					$relation_products[$relation['product_id']]['relation_id'] = $relation['id'];
					$relation_products[$relation['product_id']]['relation_order'] = $relation['order'];
				}
				if (!empty($product_ids))
				{
					$query_struct = array (
						'where' => array (
							'id' => $product_ids
						)
					);
					$product_service = ProductService::get_instance();
					$results = $product_service->index($query_struct);
					$result_products = array ();
					foreach ($results as $val)
					{
						$result_products[$val['id']] = $val;
					}
					$categorys = CategoryService::get_instance()->get_categories();
					//$brands = BrandService::get_instance()->get_brands();
					foreach ($relation_products as $key => $val)
					{
						if (isset($result_products[$key]))
						{
							$products[$key] = $result_products[$key] + $val;
							if (isset($categorys[$result_products[$key]['category_id']]))
							{
								$products[$key]['category'] = $categorys[$result_products[$key]['category_id']]['title'];
							}
						}
					}
					$total = count($products);
					// 每页条目数
					controller_tool::request_per_page($relation_query_struct, $request_data);
					$per_page = $relation_query_struct['limit']['per_page'];
					/*分页处理*/
					$this->pagination = new Pagination(array (
						'base_url' => url::current(), 
						'uri_segment' => 'page', 
						'total_items' => $total, 
						'items_per_page' => $per_page
					));
					$current_page = $this->pagination->current_page;
					$offset = (int) min(max(0, ($current_page - 1) * $per_page), max(0, $total - 1));
					if (!empty($products))
					{
						$products = array_slice($products, $offset, $per_page);
					}
				}
			} 
            else
			{
				if (!empty($product_ids))
				{
					$struct = product::get_struct($request_data);
					$query_struct = $struct['query'];
					$request_struct = $struct['request'];
					$query_struct['where']['id'] = $product_ids;
					$product_service = ProductService::get_instance();
					$total = $product_service->count($query_struct);
					// 排序处理 
					//列表排序
					$orderby_arr = array (
						2 => array (
							'sku' => 'ASC'
						), 
						3 => array (
							'sku' => 'DESC'
						), 
						4 => array (
							'name_manage' => 'ASC'
						), 
						5 => array (
							'name_manage' => 'DESC'
						), 
						6 => array (
							'title' => 'ASC'
						), 
						7 => array (
							'title' => 'DESC'
						), 
						8 => array (
							'category_id' => 'ASC'
						), 
						9 => array (
							'category_id' => 'DESC'
						), 
						10 => array (
							'on_sale' => 'ASC'
						), 
						11 => array (
							'on_sale' => 'DESC'
						), 
						12 => array (
							'goods_price' => 'ASC'
						), 
						13 => array (
							'goods_price' => 'DESC'
						)
					);
					if (isset($request_data['orderby']) && isset($orderby_arr[$request_data['orderby']]))
					{
						$query_struct['orderby'] = $orderby_arr[$request_data['orderby']];
					}
					
					// 每页条目数
					controller_tool::request_per_page($query_struct, $request_data);
					//分页处理
					$this->pagination = new Pagination(array (
						'total_items' => $total, 
						'items_per_page' => $query_struct['limit']['per_page']
					));
					$query_struct['limit']['page'] = $this->pagination->current_page;
					$results = $product_service->index($query_struct);
					$result_products = array ();
					foreach ($results as $val)
					{
						$result_products[$val['id']] = $val;
					}
					$categorys = CategoryService::get_instance()->get_categories();
					//$brands = BrandService::get_instance()->get_brands();
					foreach ($result_products as $key => $val)
					{
						if (isset($relation_products[$key]))
						{
							$products[$key] = $val + $relation_products[$key];
							if (isset($categorys[$val['category_id']]))
							{
								$products[$key]['category'] = $categorys[$val['category_id']]['title'];
							}
						}
					}
				}
			}
			//echo kohana::debug($relation_query_struct);
			if (empty($products))
			{
				//分页处理
				$this->pagination = new Pagination(array (
					'total_items' => 0, 
					'items_per_page' => 20
				));
			}
			//echo kohana::debug($products);
			$return_data['products'] = $products;
			$return_data['collection'] = $collection;
			
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
				$content = new View('product/' . $this->class_name . '/' . __FUNCTION__);
				//* 变量绑定 */
				$this->template->title = Kohana::config('site.name');
				$this->template->content = $content;
				//* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				//* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
				//:: 当前应用专用数据
				$this->template->content->relation_product_ids = $relation_product_ids;
			
			} // end of request type determine

		} 
         catch (MyRuntimeException $ex)
		{
            $this->_ex($ex, $return_struct, $request_data);
		}
	}
	
	public function delete()
	{
        role::check('product_collection_delete');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		try
		{
			//$profiler = new Profiler;
			//* 初始化返回数据 */
			$return_data = array ();
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->get();
			
			//数据验证
			$collection_service = CollectionService::get_instance();
			if (!isset($request_data['id']) || !is_numeric($request_data['id']))
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
			}
			
			$collection = $collection_service->get($request_data['id']);
			
			//执行删除
			$collection_service->delete_by_collection_id($collection['id']);
			
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
	
	public function delete_all()
	{
        role::check('product_collection_delete');
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
            
			if (!isset($request_data['id']) || empty($request_data['id']))
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
			}
            
			$collection_service = CollectionService::get_instance();
			//执行删除
			if (isset($request_data['id']) || !empty($request_data['id']))
			{
				$collection_service->delete_collections($request_data['id']);
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
	
	public function get_site_data()
	{
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		try
		{
			
			//$profiler = new Profiler;
			//* 初始化返回数据 */
			$return_data = array (
				'product_list' => NULL
			);
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->get();
			//* 实现功能后屏蔽此异常抛出 */
			//throw new MyRuntimeException('Not Implemented',501);
			

			//必须为ajax请求
			if (!$this->is_ajax_request())
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
			}
			
			//* 权限验证 */
			$site_id_list = role::check('product_collection', 0, 0);
			if (empty($site_id_list))
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 403);
			}
			if (isset($request_data['site_id']) && is_numeric($request_data['site_id']))
			{
				if (!in_array($request_data['site_id'], $site_id_list))
				{
					throw new MyRuntimeException(Kohana::lang('o_global.access_denied'), 403);
				}
			}
			//数据验证
			if (!isset($request_data['site_id']) || !is_numeric($request_data['site_id']))
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
			}
			// 调用底层服务
			$collection_service = CollectionService::get_instance();
			//请求站点商品列表
			$query_struct = array (
				'where' => array (
					'site_id' => $request_data['site_id'], 
					'status' => 1, 
					'on_sale' => 1
				)
			);
			$products = ProductService::get_instance()->index($query_struct);
			if (!empty($products))
			{
				foreach ($products as $val)
				{
					$return_data['product_list'] .= '<option value=' . $val['id'] . '>' . $val['title'] . '</option>';
				}
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
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
			} // end of request type determine
		

		} catch (MyRuntimeException $ex) {
			$return_struct['status'] = 0;
			$return_struct['code'] = $ex->getCode();
			$return_struct['msg'] = $ex->getMessage();
			//TODO 异常处理
			//throw $ex;
			if ($this->is_ajax_request())
			{
				$this->template->content = $return_struct;
			} else
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
	
	public function add_products()
	{
		role::check('product_collection');
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
			
			if (empty($request_data['collection_id']) || !is_numeric($request_data['collection_id']))
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 404);
			}
			$collection_service = CollectionService::get_instance();
			$collection = $collection_service->get($request_data['collection_id']);
			
			$relation_query_struct = array (
				'where' => array (
					'collection_id' => $collection['id']
				)
			);
			
			$relations = Collection_product_relationService::get_instance()->index($relation_query_struct);
			$product_ids = array ();
			foreach ($relations as $relation)
			{
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
			if (!empty($return_data['assoc']))
			{
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
			if ($this->is_ajax_request())
			{
				$requestkeys = array (
					'id', 
					'site_id', 
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
			if ($this->is_ajax_request())
			{
				// ajax 请求
				// json 输出
				$this->template->content = $return_struct;
			} else
			{
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
				$this->template->content->collection_id = $collection['id'];
			
			} // end of request type determine
		

		} catch (MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
		}
	}
	
	public function put_products()
	{
		role::check('product_collection');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		try
		{
			//$profiler = new Profiler;
			//* 初始化返回数据 */
			$return_data = array ();
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->get();
			
			if (empty($request_data['collection_id']) || !is_numeric($request_data['collection_id']))
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
			}
			$collection_service = CollectionService::get_instance();
			$collection = $collection_service->get($request_data['collection_id']);

			$request_data['relation_ids'] = explode('-', $request_data['relation_ids']);
			
			if (isset($request_data['relation_ids']) && !empty($request_data['relation_ids']))
			{
				$product_ids = $collection_service->get_products_by_collection_id($collection['id']);
				foreach ($request_data['relation_ids'] as $val)
				{
					if (!in_array($val, $product_ids))
					{
						$data = array ();
						//$data['site_id'] = $collection['site_id'];
						$data['collection_id'] = $collection['id'];
						$data['product_id'] = $val;
						$result = Collection_product_relationService::get_instance()->add($data);
						if (!$result)
						{
							throw new MyRuntimeException('Internal Error', 500);
						}
					}
				}
				foreach ($product_ids as $val)
				{
					if (!in_array($val, $request_data['relation_ids']))
					{
						$arr = array (
							'collection_id' => $collection['id'], 
							'product_id' => $val
						);
						Collection_product_relationService::get_instance()->delete_relations($arr);
					}
				}
				
				$collection_service->clear($request_data['collection_id']);
			}
			$return_data['collection_id'] = $collection['id'];
			
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
	
	public function product_delete()
	{
        role::check('product_collection');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented.', 
			'content' => array ()
		);
		try {
			//* 初始化返回数据 */
			$return_data = array ();
			
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->get();
			
			if (empty($request_data['relation_id']) || empty($request_data['relation_id']))
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
			}
			//* 权限验证 ==根据业务逻辑定制== */
			$collection_relation_service = Collection_product_relationService::get_instance();
			$relation = $collection_relation_service->get($request_data['relation_id']);
			
			$collection_relation_service->remove($request_data['relation_id']);
			CollectionService::get_instance()->clear($relation['collection_id']);
			
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '删除成功';
			$return_struct['content'] = $return_data;
			$return_struct['action'] = array (
				'type' => 'location', 
				'url' => url::base() . 'product/' . $this->class_name . '/products?id=' . $relation['collection_id']
			);
			
			//* 请求类型 */
			if ($this->is_ajax_request())
			{
				// ajax 请求
				// json 输出
				$this->template->content = $return_struct;
			} else {
				// html 输出
				$this->template->return_struct = $return_struct;
				$content = new View('info');
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
	
	public function product_delete_all()
	{
        role::check('product_collection');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented.', 
			'content' => array ()
		);
		try
		{
			//* 初始化返回数据 */
			$return_data = array ();
			
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->post();
			
			if (empty($request_data['collection_id']) || empty($request_data['ids']))
			{
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
			}
			//* 权限验证 ==根据业务逻辑定制== */
			$collection_service = CollectionService::get_instance();
			$collection = $collection_service->get($request_data['collection_id']);

			//批量删除
			ORM::factory('collection_product_relation')->in('id', $request_data['ids'])->where('collection_id', $request_data['collection_id'])->delete_all();
			$collection_service->clear($request_data['collection_id']);
			
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '删除成功';
			$return_struct['content'] = $return_data;
			$return_struct['action'] = array (
				'type' => 'location', 
				'url' => url::base() . 'product/' . $this->class_name . '/' . 'products?id=' . $collection['id']
			);
			
			//* 请求类型 */
			if ($this->is_ajax_request())
			{
				// ajax 请求
				// json 输出
				$this->template->content = $return_struct;
			} else {
				// html 输出
				$this->template->return_struct = $return_struct;
				$content = new View('info');
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
    
	/**
	 * 设定菜单的排序
	 */
	public function set_order()
	{
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

		if (empty($id) || (empty($order) && $order != 0))
		{
			$return_struct['msg'] = Kohana::lang('o_global.bad_request');
			exit(json_encode($return_struct));
		}
		if (!is_numeric($order) || $order < 0)
		{
			$return_struct['msg'] = Kohana::lang('o_global.position_rule');
			exit(json_encode($return_struct));
		}
		$collection_service = CollectionService::get_instance();
		$collection_service->set($id, array (
			'order' => $order
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
	
	public function product_set_order()
	{
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

		if (empty($id) || (empty($order) && $order != 0))
		{
			$return_struct['msg'] = Kohana::lang('o_global.bad_request');
			exit(json_encode($return_struct));
		}
		if (!is_numeric($order) || $order < 0)
		{
			$return_struct['msg'] = Kohana::lang('o_global.position_rule');
			exit(json_encode($return_struct));
		}
		$collection_relation_service = Collection_product_relationService::get_instance();
		$relation = $collection_relation_service->get($id);
		$collection_relation_service->set($id, array (
			'order' => $order
		));
		CollectionService::get_instance()->clear($relation['collection_id']);
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
