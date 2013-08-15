<?php defined('SYSPATH') OR die('No direct access allowed.');

class Delivery_country_Controller extends Template_Controller 
{
	public function __construct()
	{
		parent::__construct();
		role::check('site_carrier');
	}

    /**
     * 物流对应的国家列表
     */
    function index($id)
    {
        if(!$id)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
        }

        $delivery_service = DeliveryService::get_instance();
        $delivery_country_service = Delivery_countryService::get_instance();
        //验证此条物流
        $data = $delivery_service->get($id);
        if(!$data['id'])
        {
        	remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
        }
		
        //初始化请求结构体
		$query_struct = array (
			'where'   => array (
				'delivery_id'   => $id,
			), 
			'like'    => array (), 
			'orderby' => array (
				'position'      => 'ASC',
				'id'            => 'ASC'
			), 
			'limit' => array (
				'per_page'      =>2000,
				'offset'        =>0
			)
		);
		
		// 每页条目数
		controller_tool::request_per_page($query_struct,$request_data);
		
		$count = $delivery_country_service->query_count($query_struct);

		// 模板输出 分页
		$this->pagination       = new Pagination(array(
			'total_items'    => $count,
			'items_per_page' => $query_struct['limit']['per_page'],
		));
		
		$query_struct['limit']['offset']      = $this->pagination->sql_offset;
		$query_struct['limit']['page'] = $this->pagination->current_page;			

		//调用列表
		$delivery_countries = $delivery_country_service->get_delivery_countries_by_position($query_struct);
		$this->template->content = new View("site/delivery_country");
		$this->template->content->delivery_countries = $delivery_countries;
		$this->template->content->data = $data;
		$this->template->content->countries	= $delivery_country_service->get_countries();
    }
}