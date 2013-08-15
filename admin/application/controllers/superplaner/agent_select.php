<?php
class Agent_select_Controller extends Template_Controller 
{
	public function __construct()
	{
		parent::__construct();
        role::check('superplaner_system_manage');
	}
	
	public function index() 
	{
		$user_query_struct = array(
            'where'=>array(),
            'like'=>array(),
            'orderby'   => array(),
            'limit'     => array(
                'per_page'  => 20,
                'offset'    => 0,
            ),
        );
		
		/* 搜索功能 */
		$search_arr      = array('id','email','firstname','lastname','ip','mobile','real_name');
		$search_value    = $this->input->get('search_value');
		$search_type     = $this->input->get('search_type');
		$where_view      = array();
		if($search_arr){
			foreach($search_arr as $value){
				if($search_type == $value && strlen($search_value) > 0){
					$user_query_struct['like'][$value] = $search_value;
					//$user_query_struct['where'][$value] = $search_value;
					if($value == 'ip'){
						$user_query_struct['like'][$value] = tool::myip2long($search_value);
						//$user_query_struct['where'][$value] = tool::myip2long($search_value);						
					}
				}
			}
			$where_view['search_type']  = $search_type;
			$where_view['search_value'] = $search_value;
		}
		
		/* 列表排序 */
		$orderby_arr= array(
				0   => array('id'=>'DESC'),
				1   => array('id'=>'ASC'),
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
				15  => array('active'=>'DESC'),
				16  => array('register_mail_active'=>'ASC'),
				17  => array('register_mail_active'=>'DESC')
			);
        
		$orderby = controller_tool::orderby($orderby_arr);
		$user_query_struct['orderby'] = $orderby;
		
		/* 每页显示条数 */
		$per_page    = controller_tool::per_page();
		$user_query_struct['limit']['per_page'] = $per_page;
		
		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => Myuser::instance()->query_count($user_query_struct),
			'items_per_page' => $per_page,
		));
		$user_query_struct['limit']['offset'] = $this->pagination->sql_offset;
		
		$user_list = Myuser::instance()->query_assoc($user_query_struct);
		//找出所有的站点的用户等级信息
		$user_levelservice = User_levelService::get_instance();
		$query_struct = array(
			'where'=>array(
				'active'=>1,
			),
		);
        
		$user_levels = $user_levelservice->index($query_struct);
		$tmp = array();		
		foreach($user_levels as $user_level)
		{
			if($user_level['is_default'])
			{
				$tmp['default'] = $user_level;
			}
			$tmp[$user_level['id']] = $user_level;
		}
		$user_levels = $tmp;
		foreach ($user_list as $key => $value) {
            $users[$key]['level'] = '';
			if(!empty($user_levels[$value['level_id']]))
			{
				$users[$key]['level'] = $user_levels[$value['level_id']]['name_manage'];
			}else{
				//$users[$key]['level'] = $user_levels['default']['name_manage'];
			}
		}
		
		$this->template->content = new View("superplaner/agent_select_list");
		$this->template->content->where	= $where_view;
		$this->template->content->user_list = $user_list;
	}
	
	
}
?>