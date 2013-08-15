<?php defined('SYSPATH') OR die('No direct access allowed.');

class Newsletter_Controller extends Template_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->site_id = site::id();
	}
	
	/* Newsletter列表 */
	public function index()
	{
        /* 初始化默认查询条件 */
        $newsletter_query_struct = array(
            'where'=>array(
        		'user_id' => 0,
        	),
            'like'=>array(),
            'orderby'   => array(),
            'limit'     => array(
                'per_page'  => 20,
                'offset'    => 0,
            ),
        );
        
		/* 权限检查 得到所有可管理站点ID列表 */
		$site_id_list = role::check('newsletter');
		
		/* Newsletter列表模板 */
		$this->template->content = new View("user/newsletter");
		
		/* 搜索功能 */
		$search_arr      = array('email','ip');
		$search_value    = $this->input->get('search_value');
		$search_type     = $this->input->get('search_type');
		$where_view      = array();
		if($search_arr)
		{
			foreach($search_arr as $value)
			{
				if($search_type == $value && strlen($search_value) > 0)
				{
					$newsletter_query_struct['where'][$value] = $search_value;
					if($value == 'ip')
					{
						$newsletter_query_struct['where'][$value] = tool::myip2long($search_value);
						
					}
				}
			}
			$where_view['search_type']	  = $search_type;
			$where_view['search_value']   = $search_value;
		}		

		//当前切入的站点查询条件
		$site_in = site::current_query_site_ids();
		$where_view['site_id']   = '';
		$newsletter_query_struct['where']['site_id'] = $site_in;
		
		/* 列表排序 */
		$orderby_arr= array
			(
				0   => array('id'=>'DESC'),
				1   => array('id'=>'ASC'),
				2   => array('site_id'=>'ASC'),
				3   => array('site_id'=>'DESC'),
				4   => array('email'=>'ASC'),
				5   => array('email'=>'DESC'),
				6   => array('date_add'=>'ASC'),
				7   => array('date_add'=>'DESC'),
				8   => array('ip'=>'ASC'),
				9   => array('ip'=>'DESC'),
				10  => array('active'=>'ASC'),
				11  => array('active'=>'DESC')
			);

		$orderby    = controller_tool::orderby($orderby_arr);
		$newsletter_query_struct['orderby'] = $orderby;
		
		/* 每页显示条数 */
		$per_page    = controller_tool::per_page();
		$newsletter_query_struct['limit']['per_page'] = $per_page;
		
		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => Mynewsletter::instance()->query_count($newsletter_query_struct),
			'items_per_page' => $per_page,
		));
		$newsletter_query_struct['limit']['offset'] = $this->pagination->sql_offset;
		
		$newsletters = Mynewsletter::instance()->query_assoc($newsletter_query_struct);

		foreach ($newsletters as $key => $value)
		{
			$site = Mysite::instance($value['site_id'])->get();
			$newsletters[$key]['site'] = $site;
		}
		
		/* 调用列表 */
		$this->template->content->newsletter_list		= $newsletters;
		
		/* 搜索信息保存 */
		$this->template->content->where			= $where_view;
	}
}