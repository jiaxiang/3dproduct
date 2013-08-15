<?php defined('SYSPATH') or die('No direct access allowed.');

class Product_auction_Controller extends Template_Controller {
	
	public function index(){
        role::check('product_auction');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		try{
			//* 初始化返回数据 */
			$return_data = array ();
			$request_data = $this->input->get();
            $experience = $this->input->get('experience', 0);
            
            $query_struct_current = array(
                'where' => array(
                     'status!=' => 2, 
                     'experience' => $experience,
                )
            );
			//列表排序
			$orderby_arr = array (
				1 => array (
					'name' => 'ASC'
				), 
				2 => array (
					'name' => 'DESC'
				), 
				3 => array (
					'price_start' => 'ASC'
				), 
				4 => array (
					'price_start' => 'DESC'
				),
				5 => array (
					'time_end' => 'ASC'
				), 
				6 => array (
					'time_end' => 'DESC'
				),
			);
            
            // 每页条目数
            controller_tool::request_per_page($query_struct_current, $request_data);
            
			if(isset($request_data['orderby']) && isset($orderby_arr[$request_data['orderby']])){
				$query_struct_current['orderby'] = $orderby_arr[$request_data['orderby']];
			}
            //调用服务执行查询            
            $auction_service = Product_auctionService::get_instance();
            $count = $auction_service->count($query_struct_current);
            // 模板输出 分页
            $this->pagination = new Pagination(array (
                'total_items' => $count, 
                'items_per_page' => $query_struct_current['limit']['per_page'] 
            ));
            $query_struct_current['limit']['page'] = $this->pagination->current_page;

            $products = array ();
			$product_ids = array ();
			$relation_products = array ();
			$relation_product_ids = array ();
            $categorys = CategoryService::get_instance()->get_categories();
			$relations = $auction_service->index($query_struct_current);
			foreach ($relations as $k => $relation){
                $relation_product_ids[$relation['product_id']] = $relation['product_id'];
                $relation['category'] = '';
                if($relation['category_ids']){
                    $relation['category_ids'] = explode(',', $relation['category_ids']);
                    foreach($relation['category_ids'] as $cid){                        
                        $relation['category'] .= isset($categorys[$cid])?$categorys[$cid]['title'].',':'';
                    }
                }
                $relations[$k] = $relation;
			}
            
			if(empty($relations)){
				//分页处理
				$this->pagination = new Pagination(array (
					'total_items' => 0, 
					'items_per_page' => 20
				));
			}
            
			$return_data['products'] = $relations;
			
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '';
			$return_struct['content'] = $return_data;
			
			//* 请求类型 */
			if ($this->is_ajax_request()){
				// ajax 请求
				// json 输出
				$this->template->content = $return_struct;
			} else {
				// html 输出
				//* 模板输出 */
				$content = new View('product/auction/index', array('experience'=>$experience));
                
				$this->template->content = $content;
				//* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				//* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
				//:: 当前应用专用数据
				$this->template->content->relation_product_ids = $relation_product_ids;
			
			} // end of request type determine

		}catch (MyRuntimeException $ex){
            $this->_ex($ex, $return_struct, $request_data);
		}
	}
    
	public function edit(){
		role::check('product_auction'); 
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		try{
            $id = $this->input->get('id');
            $post = $this->input->post();
            $auction_service = Product_auctionService::get_instance();
            //d($post);
    		if($post){
                $result = $auction_service->update($post);
				if(!$result){
					throw new MyRuntimeException('Internal Error', 500);
				}else{
                    remind::set('更新成功！', 'product/product_auction/', 'success');
                }
            }
    		if(empty($id)){
    			throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
    		}
            $data = $auction_service->get($id);
    		$this->template->content = new View('product/auction/edit', array('data'=>$data));
		} catch (MyRuntimeException $ex) {
            $this->_ex($ex);
		}
    }
    
	public function add_products(){
		role::check('product_auction');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
		try{
			//* 初始化返回数据 */
			$return_data = array();
            
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->get();
			$experience = $this->input->get('experience',0);
			$relation_query_struct = array (
				'where' => array (
					'status!=' => 2,
                    'experience' => $experience,
				), 
				'orderby' => array (
					'id' => 'DESC'
				)
			);
            
			//$relations = Product_auctionService::get_instance()->index($relation_query_struct);
			$product_ids = array();
			//foreach ($relations as $relation){
				//$product_ids[] = $relation['product_id'];
			//}
            
			$struct = product::get_struct($request_data);
			$query_struct_current = $struct['query'];
			$request_struct_current = $struct['request'];
			$query_struct_current['where']['type'] = ProductService::PRODUCT_TYPE_GOODS;

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
			if($this->is_ajax_request()){
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
			if($this->is_ajax_request()){
				// ajax 请求
				// json 输出
				$this->template->content = $return_struct;
			} else {
				//* html 输出 ==根据业务逻辑定制== */
				$this->template = new View('layout/commonfix_html');
				//* 模板输出 */
				$this->template->return_struct = $return_struct;
				
				$content = new View('product/auction/add_products', array('experience'=>$experience));
				//* 变量绑定 */
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
		role::check('product_auction');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => array ()
		);
        $this->template = new View('layout/commonfix_html');
		try{
			//$profiler = new Profiler;
			//* 初始化返回数据 */
			$return_data = array ();
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->post();
            $experiences = $this->input->post('experiences', array());
            
            if(empty($request_data['relation_ids'])){
                throw new MyRuntimeException('Input Error', 500);
            }            
			
			if(isset($request_data['relation_ids']) && !empty($request_data['relation_ids'])){  
                $query_struct = array(
            				'where' => array (
            					'id' => $request_data['relation_ids'],
            				)
            			);     
                $pdata = ProductService::get_instance()->query_assoc($query_struct);
                
                $product_data = array();
                foreach($pdata as $p){
                    $product_data[$p['id']] = $p;
                }
                     
    			$auction_service = Product_auctionService::get_instance();                       
				foreach($request_data['relation_ids'] as $pid => $val){
                    $relation_query_struct = array(
            				'where' => array(
            					'product_id' => $val,
            				)
            			);
                    
                    $pdata = $data = array();
                    $pdata = array_shift($auction_service->query_assoc($relation_query_struct));
                    
					$data['experience'] = isset($experiences[$pid])?1:0;                        
					if(empty($pdata)){
						$data['qty'] = 1;
                        $data['status'] = 0;
						$data['product_id'] = $val;
                        $data['price_start'] = '0.00';
                        $data['price_increase'] = '0.01';
                        $data['time_end'] = 3600*24;
                        $data['time_reset'] = 10;
                        if(isset($product_data[$val])){
                            $data['price'] = $product_data[$val]['price'];
						    $data['name'] = $product_data[$val]['title'];
						    if(!empty($product_data[$val]['default_image_id'])){
						        $data['default_image'] = '/att/product/'.$product_data[$val]['default_image_id'];
                            }elseif(!empty($product_data[$val]['goods_productpic_relation_struct'])){
                                $img = json_decode($product_data[$val]['goods_productpic_relation_struct'], 1);
                                if(isset($img['items'][0])){
                                    $img = ProductpicService::get_instance()->get($img['items'][0]);
                                    $data['default_image'] = '/att/product/'.$img['image_id'];
                                }
                            }else{
                                $data['default_image'] = '/att/product/';
                            }                          
						    $data['category_ids'] = ','.$product_data[$val]['category_id'].','.$product_data[$val]['category_ids'].',';
                        }
						$result = $auction_service->add($data);
						if(!$result){
							throw new MyRuntimeException('Internal Error', 500);
						}
					}elseif($pdata['id']>0){
                        $data['id'] = $pdata['id'];
                        $data['status'] = ($pdata['status']==2)?0:$pdata['status'];
                        if(isset($product_data[$val])){
                            $data['price'] = $product_data[$val]['price'];
						    $data['name'] = $product_data[$val]['title'];
						    if(!empty($product_data[$val]['default_image_id'])){
						        $data['default_image'] = '/att/product/'.$product_data[$val]['default_image_id'];
                            }elseif(!empty($product_data[$val]['goods_productpic_relation_struct'])){
                                $img = json_decode($product_data[$val]['goods_productpic_relation_struct'], 1);
                                if(isset($img['items'][0])){
                                    $img = ProductpicService::get_instance()->get($img['items'][0]);
                                    $data['default_image'] = '/att/product/'.$img['image_id'];
                                }
                            }else{
                                $data['default_image'] = '/att/product/';
                            }                          
						    $data['category_ids'] = ','.$product_data[$val]['category_id'].','.$product_data[$val]['category_ids'].',';
                        }
                        $auction_service->update($data);
                    }
				}
			}
			
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '添加成功！';
			$return_struct['content'] = $return_data;
			
			//* 请求类型 */
			if ($this->is_ajax_request()){
				// ajax 请求
				// json 输出
				$this->template->content = $return_struct;
			} else{
				// html 输出
				$this->template = new View('layout/empty_html');
                
				//* 变量绑定 */                
				$this->template->content = new View('product/auction/put_products');
				//* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				//* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
			}
		} catch (MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
		}
	}
	
	public function experience(){
        role::check('product_auction');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented.', 
			'content' => array ()
		);
		try{
			//* 初始化返回数据 */
			$return_data = array ();
			$ac = array('add'=>1, 'cancle'=>0);
			$a = $this->input->get('a','');
			$request_data = $this->input->post();
            if(!in_array($a, $ac) || empty($request_data['ids'])){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
			foreach($request_data['ids'] as $id){                
                $data['id'] = $id;
                $data['experience'] = $ac[$a];            
    			Product_auctionService::get_instance()->update($data);
            }
            
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '操作已成功';
			$return_struct['content'] = $return_data;
			$return_struct['action'] = array (
				'type' => 'location', 
				'url' => url::base() . 'product/product_auction'
			);
			
			//* 请求类型 */
			if ($this->is_ajax_request()){
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
    
	public function delete()
	{
        role::check('product_auction');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented.', 
			'content' => array ()
		);
		try {
			//* 初始化返回数据 */
			$return_data = array();
			
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->get();
            $id = $request_data['id'];
			if(empty($id)){
				throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
			}
            $data['id'] = $id;
            $data['status'] = 2;                
			Product_auctionService::get_instance()->update($data);
			
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '删除成功';
			$return_struct['content'] = $return_data;
			$return_struct['action'] = array (
				'type' => 'location', 
				'url' => url::base() . 'product/product_auction'
			);
			
			//* 请求类型 */
			if ($this->is_ajax_request()){
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
	
	public function delete_all(){
        role::check('product_auction');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented.', 
			'content' => array ()
		);
		try{
			//* 初始化返回数据 */
			$return_data = array ();
			
			//* 收集请求数据 ==根据业务逻辑定制== */
			$request_data = $this->input->post();
            
			//批量删除
			//ORM::factory('product_auction')->in('id', $request_data['ids'])->delete_all();
			foreach($request_data['ids'] as $id){                
                $data['id'] = $id;
                $data['status'] = 2;                
    			Product_auctionService::get_instance()->update($data);
            }
            
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '删除成功';
			$return_struct['content'] = $return_data;
			$return_struct['action'] = array (
				'type' => 'location', 
				'url' => url::base() . 'product/product_auction'
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
    
	public function onchange(){
		role::check('product_auction');
		$return_struct = array (
			'status' => 0, 
			'code' => 501, 
			'msg' => 'Not Implemented', 
			'content' => ''
		);
		try{
			$return_data = array();
            
			$request_data = $this->input->get();
            $id = (int)$request_data['id'];
			$v = $request_data['v']>0?0:1;
			$name = trim($request_data['name']);
            
			if(!isset($request_data['id']) || !in_array($name, array('status', 'recommend'))){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            $auction_service = Product_auctionService::get_instance();
            $p = $auction_service->get($id);
            
			if(!isset($p['id']) || $p['status']==2){
                throw new MyRuntimeException(Kohana::lang('o_global.bad_request'), 400);
            }
            
            $data = array();
			$data['id'] = $id;
			$data[$name] = $v;
			$result = $auction_service->update($data);
			if(!$result){
				throw new MyRuntimeException('Internal Error', 500);
			}
			
			//* 补充&修改返回结构体 */
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = '成功！';
			$return_struct['content'] = $v;
			
			//* 请求类型 */
			if ($this->is_ajax_request()){
				// ajax 请求
				// json 输出
				die(json_encode($return_struct));
			} else{
                throw new MyRuntimeException('成功', 200);
			}
		} catch (MyRuntimeException $ex) {
            $this->_ex($ex, $return_struct, $request_data);
		}
	}
}
