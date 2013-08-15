<?php defined('SYSPATH') OR die('No direct access allowed.');

class Order_export_Controller extends Template_Controller {
    private $package_name = '';
    private $class_name = '';
    // Set the name of the template to use
    public $template_ = 'layout/common_html';

    public function __construct()
    {
        $package_name = substr(dirname(__FILE__),strlen(APPPATH.'controllers/'));
        empty($package_name) && $package_name = 'default';
        $this->package_name = $package_name;
        $this->class_name = strtolower(substr(__CLASS__,0,strpos(__CLASS__,'_')));
        parent::__construct();
        if($this->is_ajax_request()==TRUE)
        {
            $this->template = new View('layout/default_json');
        }
    }
    
	/**
	 * 当前订单导出配置列表
	 */
	public function index()
	{
		// 初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try {
	        /* 权限验证 订单导出配置*/
			role::check('order_edit');
	        
	        /* 初始化默认查询条件 */
	        $order_export_config_query_struct = array
	        (
	            'where'=>array(),
	            'like'=>array(),
	            'orderby'   => array(),
	            'limit'     => array
	            (
	                'per_page'  => 20,
	                'offset'    => 0,
	            ),
	        );
	        
			/* 得到默认每页显示多少条 */
			$per_page = controller_tool::per_page();
	
			/* 调用分页 */
			$this->pagination = new Pagination(array(
				'total_items'    => Myorder_export::instance()->query_count($order_export_config_query_struct),
				'items_per_page' => $per_page,
			));
			$order_export_config_query_struct['limit']['offset'] = $this->pagination->sql_offset;
			$order_export_config_query_struct['limit']['per_page'] = $per_page;
	        $order_exports = Myorder_export::instance()->query_assoc($order_export_config_query_struct);
	        
			/* 调用列表 */
	        $this->template->content = new View("order/order_export_list");
			$this->template->content->order_exports		= $order_exports;
			
		} catch (MyRuntimeException $ex) {
			$return_struct['status'] = 0;
			$return_struct['code']   = $ex->getCode();
			$return_struct['msg']    = $ex->getMessage();
			//TODO 异常处理
			//throw $ex;
			if($this->is_ajax_request()) {
				$this->template = new View('layout/empty_html');
				$this->template->content = $return_struct['msg'];
			} else {
				$this->template->return_struct = $return_struct;

				$content = new View('info');
				$this->template->content = $content;
				/* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				/* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
			}
		}
	}
	
	/**
	 *  订单导出查看
	 */
	public function view($id = 0)
	{
		//初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try {
	        /* 权限验证 订单导出配置*/
			role::check('order_edit');
	        
			if($id < 1)
			{
				/* 添加导出配置 */
				$data = Myorder_export::instance(1)->get();
				if($data['id'] > 0)
				{
					$export_select_ids = unserialize($data['export_ids']);
					$export_name = null;
					$export_id = 0;
				} else {
					throw new MyRuntimeException(Kohana::lang('o_order.default_config_error'),403);
				}
			} else {
				/* 修改导出配置 */
				$data = Myorder_export::instance($id)->get();
				$export_select_ids = unserialize($data['export_ids']);
				$export_name = $data['name'];
				$export_id = $data['id'];
			}
			
			/* 导出配置详情 */
			$xls= export::instance();
			$export_list = $xls->config();
	
			$export_select_list = array();
			foreach($export_select_ids as $value)
			{
				foreach($export_list as $key=>$rs)
				{
					$export_list[$key]['id'] = $key;
					if($key == $value)
					{
						$rs['id'] = $key;
						$export_select_list[$key] = $rs;
					}
				}
			}
			$export_spare_list = tool::my_array_diff($export_list,$export_select_list);
	
			$this->template->content = new View("order/order_export");
			$this->template->content->export_select_list			= $export_select_list;
	        $this->template->content->export_spare_list				= $export_spare_list;
	        $this->template->content->export_name					= $export_name;
	        $this->template->content->export_id						= $export_id;
		} catch (MyRuntimeException $ex) {
			$return_struct['status'] = 0;
			$return_struct['code']   = $ex->getCode();
			$return_struct['msg']    = $ex->getMessage();
			//TODO 异常处理
			//throw $ex;
			if($this->is_ajax_request()) {
				$this->template = new View('layout/empty_html');
				$this->template->content = $return_struct['msg'];
			} else {
				$this->template->return_struct = $return_struct;

				$content = new View('info');
				$this->template->content = $content;
				/* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				/* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
			}
		}
	}
	
	/**
	 *  订单导出配置
	 */
	public function do_edit($id = 0)
	{
        /* 权限验证 订单导出配置*/
		role::check('order_edit');
		if($_POST)
		{
			$order_export = Myorder_export::instance();
			$data = array();
			$data['manager_id'] = $this->manager_id;
			$data['name']		= $_POST['export_name'];
			$data['export_ids']	= serialize($_POST['export_select']);
			if($id == '0')
			{
				if($order_export->exist($data))
				{
					remind::set(Kohana::lang('o_order.order_rule_exist'),request::referrer('order/order_export/view/' . $id),'error');
				}
				if($order_export->name_exist($data))
				{
					remind::set(Kohana::lang('o_order.order_name_exist'),request::referrer('order/order_export/view/' . $id),'error');
				}
				if($order_export->add($data))
				{
					remind::set(Kohana::lang('o_order.order_export_success'),'order/order_export','success');
				}
				else
				{
					$errors = $order_export->errors() ;
					remind::set(Kohana::lang('o_order.order_export_error'),request::referrer('order/order_export/view/' . $id),'error');
				}
			}
			else
			{
				if(Myorder_export::instance($id)->edit($data))
				{
					remind::set(Kohana::lang('o_order.order_export_success'),'order/order_export','success');
				}
				else
				{
					remind::set(Kohana::lang('o_order.order_export_error'),request::referrer('order/order_export/view/' . $id),'error');
				}
			}
		}
	}
	/**
	 *  订单导出
	 */
	public function do_export($id=0)
	{
		// 初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try {
	        //权限验证
	        role::check('order_export');
	        if($id<1)
	        {
	        	throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
	        }
	        
	        if(!$id)
			{
				throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
	        }
	        
			if($_POST)
			{
    	        /* 验证是否选择了订单 */
    			if(!isset($_POST['order_ids']))
    			{
    				throw new MyRuntimeException(Kohana::lang('o_order.select_order_export'),403);
    			}
			
				$order_ids = $this->input->post('order_ids');//array(1,2);
				
				/* 得到当前的导出配置 */
				$data = Myorder_export::instance($id)->get();
				$output_field_ids = unserialize($data['export_ids']);

				/* 导出格式错误 */
				if(!is_array($output_field_ids) || count($output_field_ids) < 1)
				{
					throw new MyRuntimeException(Kohana::lang('o_order.order_export_config_error'),403);
				}
	
				$xls = export::instance();
				
				//$xls->debug(true);//开测试模式
				$xls->set_output_field_ids($output_field_ids);
				
				/* 订单状态，支付状态，物流状态 */
				$order_status = Kohana::config('order.order_status');
				$pay_status = Kohana::config('order.pay_status');
				$ship_status = Kohana::config('order.ship_status');
			
				$result = array();
				foreach($order_ids as $order_id){
					$order		                    = Myorder::instance($order_id)->get();
					$shipping_country_name          = Mycountry::instance()->get_name_by_iso_code($order['shipping_country']);
					$billing_country_name           = Mycountry::instance()->get_name_by_iso_code($order['billing_country']);
					$order_products = Myorder_product::instance()->order_product_details(array('order_id'=>$order['id']));
					
					$order['shipping_country_name']	= $shipping_country_name;
					$order['billing_country_name']	= $billing_country_name;
					$order['pay_status_name']	    = $pay_status[$order['pay_status']]['name'];
					$order['ship_status_name']	    = $ship_status[$order['ship_status']]['name'];
					$order['order_status_name']	    = $order_status[$order['order_status']]['name'];
					$order['ip']	                = long2ip($order['ip']);
					
					if(is_array($order_products) && count($order_products) > 0)
					{
						foreach($order_products as $key=>$value)
						{
							$order_products[$key]['total'] = round($value['discount_price']*$value['quantity']*$order['conversion_rate'],2);
						}
					}
					
					$order['product']				= $order_products;
					$xls->set_order_line($order);
				}
				$xls->output();
				exit;
			}
		} catch (MyRuntimeException $ex) {
			$return_struct['status'] = 0;
			$return_struct['code']   = $ex->getCode();
			$return_struct['msg']    = $ex->getMessage();
			var_dump($return_struct);exit;
			//TODO 异常处理
			//throw $ex;
			if($this->is_ajax_request()) {
				$this->template = new View('layout/empty_html');
				$this->template->content = $return_struct['msg'];
			} else {
				$this->template->return_struct = $return_struct;

				$content = new View('info');
				$this->template->content = $content;
				/* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				/* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
			}
		}
	}
	
	/**
	 * 批量删除订单导出配置
	 */
	public function batch_delete()
	{
		// 初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try {
	        /* 权限验证 订单导出配置*/
			role::check('order_edit');
			
	        $order_export_config_ids = $this->input->post('order_export_config_ids');
	        if(is_array($order_export_config_ids) && count($order_export_config_ids) > 0)
	        {
		        /* 初始化默认查询条件 */
		        $order_export_query_struct = array(
		            'where'=>array(),
		            'like'=>array(),
		            'limit'     => array(
		                'per_page'  =>300,
		                'offset'    =>0
		            ),
		        );
		        $count = Myorder_export::instance()->query_count($order_export_query_struct);
		        if($count == count($order_export_config_ids))
		        {
		        	throw new MyRuntimeException(Kohana::lang('o_order.keep_one_order_export_config'),403);
		        }
		        
		        /* 删除 */
		        foreach ($order_export_config_ids as $key=>$value) 
		        {
		        	Myorder_export::instance($value)->delete();
		        }
		        remind::set(Kohana::lang('o_global.delete_success'),request::referrer(),'success');
	        } else {
	        	throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
	        }
		} catch (MyRuntimeException $ex) {
			$return_struct['status'] = 0;
			$return_struct['code']   = $ex->getCode();
			$return_struct['msg']    = $ex->getMessage();
			//TODO 异常处理
			//throw $ex;
			if($this->is_ajax_request()) {
				$this->template = new View('layout/empty_html');
				$this->template->content = $return_struct['msg'];
			} else {
				$this->template->return_struct = $return_struct;

				$content = new View('info');
				$this->template->content = $content;
				/* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				/* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
			}
		}
	}
}
