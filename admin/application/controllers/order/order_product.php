<?php defined('SYSPATH') or die('No direct access allowed.');
class Order_product_Controller extends Template_Controller
{
	public $template_ = 'layout/common_html';
	public function __construct()
	{
		parent::__construct();
		if ($this->is_ajax_request())
		{
			$this->template = new View('layout/default_json');
		}
		role::check('order_edit');
	}

	/*
	 * 手动添加订单中的货品
	 */
	public function add()
	{
		$request_data = $this->input->get();
		if (empty($request_data['order_id']) || !is_numeric($request_data['order_id']))
       	{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer());
        }
        
        $good_ids = array();
        
		// 初始化默认查询条件
	    $query_struct = array(
			'where'=>array(
	        	'type' => ProductService::PRODUCT_TYPE_GOODS,
	        	'on_sale' => 1,
	    		'store !=' => 0
	        ),
	        'like'=>array(),
	        'orderby'   => array(
	            'id'   =>'DESC',
	        ),
	        'limit'     => array(
	            'per_page'  =>10,
	            'offset'    =>0,
	        ),
	    );
		$order = Myorder::instance($request_data['order_id'])->get();	
        	
		if(empty($order) || !isset($order))
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer());
		}
		$data = Myorder_product::instance()->order_product_details(array('order_id'=>$order['id']));
		if(empty($data) || !isset($data))
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer());
		}
		foreach($data as $key=>$val)
		{
			$good_ids[$val['good_id']] = $val['good_id'];
		}			
		$query_struct['not_in']['id'] = $good_ids;
        if(isset($request_data['type']) && isset($request_data['keyword']) && !empty($request_data['keyword']))
	    {
			switch ($request_data['type']) 
	        {
				case 'sku':
					$query_struct['like'][$request_data['type']]  = trim($request_data['keyword']);
			        break;
		        case 'title':
		            $query_struct['like'][$request_data['type']] = trim($request_data['keyword']);
		            break;
	        }
		}		
		//* 调用后端服务获取数据 */
		$good_service = ProductService::get_instance();
		$count = $good_service->count($query_struct);				
		// 模板输出 分页
		$this->pagination = new Pagination(array (
			'total_items' => $count, 
			'items_per_page' => $query_struct['limit']['per_page']
		));
		$query_struct['limit']['offset']      = $this->pagination->sql_offset;
		$query_struct['limit']['page'] = $this->pagination->current_page;
		$good_list = $good_service->index($query_struct);

        $this->template = new View('layout/commonfix_html');
        $this->template->content = new View("order/order_product/add");
		$this->template->content->request_data = $request_data;
        $this->template->content->good_list = $good_list;
        $this->template->content->order = $order;
	}
	
	/*
	 * 手动修改订单中的信息
	 */
	public function add_goods()
	{
		$request_data = $this->input->get();
        $good_ids = array();
        $good_data = array();
		if (empty($request_data['order_id']) || !is_numeric($request_data['order_id']))
       	{
       		remind::set(Kohana::lang('o_global.bad_request'),request::referrer());
        }
		$order = Myorder::instance($request_data['order_id'])->get();
		if(empty($order) || !isset($order))
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer());
		}
		$request_data['good_ids'] = explode('-', $request_data['good_ids']);
		if (isset($request_data['good_ids']) && !empty($request_data['good_ids']))
		{
			$goods = Myorder_product::instance()->get_order_products_by_order_id($order['id']);
			foreach($goods as $val)
			{
				$good_ids[$val['good_id']] = $val['good_id'];
			}
			//批量添加货品
			foreach ($request_data['good_ids'] as $val)
			{
				if (!in_array($val, $good_ids))
				{						
					$good_data[] = ProductService::get_instance()->get($val);
				}
				else
				{
					remind::set(Kohana::lang('o_global.bad_request'),request::referrer());	
				}					
			}	
		}
        
		if(!is_array($good_data) || count($good_data) < 1)
		{
			remind::set(Kohana::lang('o_global.add_error'),request::referrer());	
		}
        $this->template = new View('layout/commonfix_html');
        $this->template->content = new View("order/order_product/add_goods");
		$this->template->content->good_data = $good_data;
        $this->template->content->order = $order;
	}
	
	/**
	 * 提交
	 */
	public function put()
	{
		$request_data = $this->input->post();
		$total_products = 0;
		$total = 0;
		if (empty($request_data['order_id']) || !is_numeric($request_data['order_id']))
       	{
       		remind::set(Kohana::lang('o_global.bad_request'),request::referrer());
        }
		$order = Myorder::instance($request_data['order_id'])->get();
		if(empty($order) || !isset($order))
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer());
		}
		$good_ids = $request_data['good_id'];
		$prices = $request_data['discount_price'];
		$amounts = $request_data['amount'];
		if ($good_ids && is_array($good_ids))
		{
			foreach($good_ids as $key=>$val)
			{
				$good_full_data = ProductService::get_instance()->get($val);
				if(empty($good_full_data) || !isset($good_full_data))
				{
					remind::set(Kohana::lang('o_global.bad_request'),request::referrer());	
				}
				if($good_full_data['store'] == '0')
				{
					remind::set(Kohana::lang('o_global.bad_request'),request::referrer());
				}
                
				//得到合理的价格数值
				if($good_full_data['store'] == -1 && $amounts[$key] > 999)
				{
					$amounts[$key] = 999;
				}
				if($good_full_data['store'] != -1 && $amounts[$key] > $good_full_data['store'])
				{
					$amounts[$key] = $good_full_data['store'];
				}
					
				//$product_data = ProductService::get_instance()->get($good_full_data['product_id']);
                
				$order_product_detail_data                          = array();
				$order_product_detail_data['order_id']              = $order['id'];
				$order_product_detail_data['product_type']          = ProductService::PRODUCT_TYPE_GOODS;
				$order_product_detail_data['dly_status']            = 'storage';
				//$order_product_detail_data['product_id']            = $product_data['id'];
				$order_product_detail_data['good_id']               = $val;
				$order_product_detail_data['quantity']              = $amounts[$key];
				$order_product_detail_data['sendnum']               = '0';
				$order_product_detail_data['price']                 = $good_full_data['price'];
				$order_product_detail_data['discount_price']        = $prices[$key];
				$order_product_detail_data['weight']                = $good_full_data['weight'];
				$order_product_detail_data['name']                  = $good_full_data['title'];
				$order_product_detail_data['SKU']                   = $good_full_data['sku'];
				$order_product_detail_data['brief']                 = $good_full_data['brief'];
				$order_product_detail_data['date_add']              = date('Y-m-d H:i:s',time());
				$order_product_detail_data['link']                  = product::permalink($good_full_data);
                order::do_order_product_detail_data_by_good(&$order_product_detail_data, $good_full_data, $good_full_data['default_image_id']);
				Myorder_product::instance()->add($order_product_detail_data);	
			}
            
			//重新查询数据库，计算价格
			$goods_order = Myorder_product::instance()->order_product_details(array('order_id'=>$order['id']));
			foreach($goods_order as $val)
			{
				$total_products += $val['quantity'] * $val['discount_price'];
			}
			$total = $total_products + $order['total_shipping'];
			$total_real = round($total * 100 / $order['conversion_rate']) / 100;
			$final_data = array('total'=>$total, 'total_products'=>$total_products,'total_real'=>$total_real);
			if(Myorder::instance($order['id'])->edit($final_data))
			{
				remind::set(Kohana::lang('o_global.add_success'),'','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),'','error');
			}	
		}
		else
		{
			remind::set(Kohana::lang('o_global.add_error'),'','error');
		} 
		$this->template = new View('layout/commonfix_html');
        $this->template->content = new View("order/order_product/put_goods");
        $this->template->content->order = $order;
	}
	
	/*
	 * 编辑
	 */
	public function ajax_edit()
	{
		$return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
		$id = intval($this->input->get('id'));
		if(!$id)
		{
			$return_struct['code'] = 400;
			$return_struct['msg'] = Kohana::lang('o_global.illegal_data');
			exit(json_encode($return_struct));
		}

		$data = Myorder_product::instance($id)->get();
		if(!$data)
		{
			$return_struct['code'] = 400;
			$return_struct['msg'] = Kohana::lang('o_global.access_denied');
			exit(json_encode($return_struct));
		}
		$good = Myorder::instance()->get_good_by_id($data['good_id']);	
		$data['store'] = !empty($good['store']) ? $good['store'] : '没有库存';
		$order = Myorder::instance($data['order_id'])->get();
		if(empty($order) || !isset($order))
		{
			$return_struct['code'] = 400;
			$return_struct['msg'] = Kohana::lang('o_global.access_denied');
			exit(json_encode($return_struct));
		}
		$data['currency'] = $order['currency'];
		$data['conversion_rate'] = $order['conversion_rate'];
		$return_template = $this->template = new View('layout/empty_html');
		$this->template->content = new View("order/order_product/ajax_edit");
		$this->template->content->data = $data;
		$return_str = $return_template->render();
		$return_struct['status'] = 1;
		$return_struct['code'] = 200;
		$return_struct['msg'] = 'Success';
		$return_struct['content'] = $return_str;
		exit(json_encode($return_struct));
	}
	
	/*
	 * 提交
	 */
	public function post()
	{
		$request_data = $this->input->post();
		$total_products = 0;
		$total = 0;
		if($_POST)
		{	
			if(!$request_data['id'])
			{
				remind::set(Kohana::lang('o_global.access_denied'),request::referrer());
			}
			if(!is_numeric($request_data['discount_price']) || $request_data['discount_price'] < 0 || !is_numeric($request_data['amount']) || $request_data['amount'] < 0)
			{
				remind::set(Kohana::lang('o_global.illegal_data'),request::referrer());
			}
			$data = Myorder_product::instance($request_data['id'])->get();
			if(!$data['id'])
			{
				remind::set(Kohana::lang('o_global.access_denied'),request::referrer());
			}
			$good = ProductService::get_instance()->get($data['good_id']);
			$order = Myorder::instance($data['order_id'])->get();			
			if($good['store'] == '0')
			{
				remind::set(Kohana::lang('o_global.bad_request'),request::referrer());
			}
			//得到合理的价格数值
			if($good['store'] == -1 && $request_data['amount'] > 999)
			{
				$request_data['amount'] = 999;
			}
			if($good['store'] != -1 && $request_data['amount'] > $good['store'])
			{
				$request_data['amount'] = $good['store'];
			}			
			$final_price = $request_data['discount_price'] * $order['conversion_rate'];						
			$set_data = array(
				'discount_price'     =>    $final_price,
				'quantity'           =>    $request_data['amount']	   		
			);
			if(Myorder_product::instance($data['id'])->edit($set_data))
			{
				//重新查询数据库，计算价格
				if(Myorder::instance($order['id'])->update_total())
				{
					remind::set(Kohana::lang('o_global.update_success'),'order/order/edit/id/'.$data['order_id'],'success');
				}
				else
				{
					remind::set(Kohana::lang('o_global.update_error'),'order/order/edit/id/'.$data['order_id'],'error');
				}	
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),'order/order/edit/id/'.$data['order_id']);	
			}					
		}
		else
		{
			remind::set(Kohana::lang('o_global.update_error'),request::referrer());	
		}	
	}
	
	/**
	 * 删除
	 */
	public function do_delete($id)
	{
		$total_products = 0;
		$total = 0;
		if(!$id)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
        }

        //验证
		$order_product_service = Order_productService::get_instance();
        $data = $order_product_service->get($id);
        if(!$data['id'])
        {
        	remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
        }

		if($order_product_service->remove($data['id']))
		{
			if(Myorder::instance($data['order_id'])->update_total())
			{
				remind::set(Kohana::lang('o_global.delete_success'),'order/order/edit/id/'.$data['order_id'],'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.delete_error'),'order/order/edit/id/'.$data['order_id'],'error');
			}
		}
		else
		{
			remind::set(Kohana::lang('o_global.delete_error'),'order/order/edit/id/'.$data['order_id'],'error');
		}
	}	
}