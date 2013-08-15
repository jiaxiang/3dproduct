<?php defined('SYSPATH') OR die('No direct access allowed.');

class Email_sign_up_Controller extends Template_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->site_id = site::id();
	}
	
	/* 用户列表 */
	public function index()
	{
        /* 初始化默认查询条件 */
        $email_sign_up_query_struct = array(
            'where'=>array(
        		'user_id>' => 0,
        	),
            'like'=>array(),
            'orderby'   => array(),
            'limit'     => array(
                'per_page'  => 20,
                'offset'    => 0,
            ),
        );
        
		/* 权限检查 得到所有可管理站点ID列表 */
		$site_id_list = role::check('email_sign_up');
		
		/* 用户列表模板 */
		$this->template->content = new View("user/email_sign_up");
		
		/* 搜索功能 */
		$search_arr      = array('id','email','firstname','lastname','ip');
		$search_value    = $this->input->get('search_value');
		$search_type     = $this->input->get('search_type');
		$where_view      = array();
		if($search_arr)
		{
			foreach($search_arr as $value)
			{
				if($search_type == $value && strlen($search_value) > 0)
				{
					$email_sign_up_query_struct['where'][$value] = $search_value;
					if($value == 'ip')
					{
						$email_sign_up_query_struct['where'][$value] = tool::myip2long($search_value);
						
					}
				}
			}
			$where_view['search_type']	  = $search_type;
		}		

		//当前切入的站点查询条件
		$site_in = site::current_query_site_ids();
		$where_view['site_id']   = '';
		$email_sign_up_query_struct['where']['site_id'] = $site_in;
		
		/* 列表排序 */
		$orderby_arr= array
			(
				0   => array('id'=>'DESC'),
				1   => array('id'=>'ASC'),
				2   => array('site_id'=>'ASC'),
				3   => array('site_id'=>'DESC'),
				4   => array('email'=>'ASC'),
				5   => array('email'=>'DESC'),
				6   => array('firstname'=>'ASC'),
				7   => array('firstname'=>'DESC'),
				8   => array('lastname'=>'ASC'),
				9   => array('lastname'=>'DESC'),
				10  => array('date_add'=>'ASC'),
				11  => array('date_add'=>'DESC'),
				12  => array('ip'=>'ASC'),
				13  => array('ip'=>'DESC'),
				14  => array('active'=>'ASC'),
				15  => array('active'=>'DESC')
			);

		$orderby    = controller_tool::orderby($orderby_arr);
		$email_sign_up_query_struct['orderby'] = $orderby;
		
		/* 每页显示条数 */
		$per_page    = controller_tool::per_page();
		$email_sign_up_query_struct['limit']['per_page'] = $per_page;
		
		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => Mynewsletter::instance()->query_count($email_sign_up_query_struct),
			'items_per_page' => $per_page,
		));
		$email_sign_up_query_struct['limit']['offset'] = $this->pagination->sql_offset;
		
		$email_sign_ups = Mynewsletter::instance()->query_assoc($email_sign_up_query_struct);

		foreach ($email_sign_ups as $key => $value)
		{
			$site = Mysite::instance($value['site_id'])->get();
			$email_sign_ups[$key]['site'] = $site;
		}
		
		/* 调用列表 */
		$this->template->content->email_sign_up_list		= $email_sign_ups;
		
		/* 搜索信息保存 */
		$this->template->content->where			= $where_view;
	}
}