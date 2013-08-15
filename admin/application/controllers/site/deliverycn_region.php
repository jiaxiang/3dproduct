<?php defined('SYSPATH') OR die('No direct access allowed.');

class Deliverycn_region_Controller extends Template_Controller 
{
	public function __construct()
	{
		parent::__construct();
		role::check('site_carrier');
	}

    /**
     * 物流对应的地区列表
     */
    function index($id)
    {
        if(!$id)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
        }

        $deliverycn_service = DeliverycnService::get_instance();
        $deliverycn_region_service = Deliverycn_regionService::get_instance();
        //验证此条物流
        $data = $deliverycn_service->get($id);
        if(!$data['id'])
        {
        	remind::set(Kohana::lang('o_global.access_denied'), request::referrer(), 'error');
        }
		
        //初始化请求结构体
		$query_struct = array (
			'where'   => array (
				'deliverycn_id'   => $id,
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
		
		$count = $deliverycn_region_service->query_count($query_struct);

		// 模板输出 分页
		$this->pagination       = new Pagination(array(
			'total_items'    => $count,
			'items_per_page' => $query_struct['limit']['per_page'],
		));
		
		$query_struct['limit']['offset']      = $this->pagination->sql_offset;
		$query_struct['limit']['page'] = $this->pagination->current_page;			

		//调用列表
		$deliverycn_regions = $deliverycn_region_service->get_delivery_regions_by_position($query_struct);
		$this->template->content = new View("site/deliverycn/region");
		$this->template->content->deliverycn_regions = $deliverycn_regions;
		$this->template->content->data = $data;
		$this->template->content->regions	= $deliverycn_region_service->get_regions();
    }
}