<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_charge_orders_Controller extends Template_Controller {
    // Set the name of the template to use
    public $template_ = 'layout/common_html';

    public function __construct()
    {
        parent::__construct();
        if($this->is_ajax_request()==TRUE)
        {
            $this->template = new View('layout/default_json');
        }
    }
	

	//用户充值记录
    public function index(){

        role::check('user_charge_orders');
		
        /* 初始化默认查询条件 */
        $user_query_struct = array(
            'where'=>array(),
            'like'=>array(),
            'orderby'   => array(
                'id'  => "DESC",			
			),
            'limit'     => array(
                'per_page'  => 20,
                'offset'    => 0,
            ),
        );        

		/* 用户列表模板 */
		$this->template->content = new View("user/user_charge_orders");
		
		/* 搜索功能 */
		$search_arr      = array('order_num');
		$search_value    = $this->input->get('search_value');
		$where_view      = array();

		$user_query_struct['like']['order_num'] = $search_value;
		//$user_query_struct['like']['ret_order_num'] = $search_value;
		$where_view['search_value'] = $search_value;			

		/* 每页显示条数 */
		$per_page    = controller_tool::per_page();
		$user_query_struct['limit']['per_page'] = $per_page;
		
		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => User_chargeService::get_instance()->query_count($user_query_struct),
			'items_per_page' => $per_page,
		));

	//d($this->pagination->sql_offset);
		$user_query_struct['limit']['offset'] = $this->pagination->sql_offset;

		$users = User_chargeService::get_instance()->lists($user_query_struct);

		$userobj = user::get_instance();
  
        foreach($users as $key=>$rowuser)
        {
            $users[$key]['userinfo'] = $userobj->get($rowuser['user_id']);
        }

		/* 调用列表 */
		$this->template->content->user_list	= $users;
		$this->template->content->where	= $where_view;
		$this->template->content->pay_banks	= Kohana::config('pay_banks');
	}	
	
 }