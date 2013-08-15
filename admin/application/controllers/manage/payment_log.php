<?php defined('SYSPATH') OR die('No direct access allowed.');

class Payment_log_Controller extends Template_Controller {
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

	/**
	 * 订单列表
	 */
	public function index(){
		/* 权限检查 订单列表 */
        role::check('manage_payment_log');
		$order_ids = array();
        // 初始化默认查询条件
        $query_struct = array(
            'where'=>array(),
            'like'=>array(),
            'orderby'   => array(
                'id'   =>'DESC',
            ),
            'limit'     => array(
                'per_page'  =>20,
                'offset'    =>0,
            ),
        );

		$this->template->content = new View("manage/payment_log/index");

		/* 搜索功能 */
		$search_arr = array('order_num','user_id');
		$where_view      = array('search_type'=>'','search_value'=>'');
		
		/* 搜索 */
		$search_type = $this->input->get('search_type');
		$search_value = trim($this->input->get('search_value'));
		
		if($search_value) {
			foreach($search_arr as $value)
			{
				if(($search_type == $value) && !empty($search_value))
				{
					$query_struct['like'][$search_type] = $search_value;				
				}
			}
			$where_view['search_type']	  = $search_type;
			$where_view['search_value']   = $search_value;
		}
		/* 记录时间搜索 */
		$date_begin = $this->input->get('date_begin');
		$date_end   = $this->input->get('date_end');
		if(!empty($date_begin) && !empty($date_end))
		{
			$query_struct['where']['date_add >='] = $date_begin . ' 00:00:00';
			$query_struct['where']['date_add <='] = $date_end . ' 24:00:00';
		}
		
		/* 列表排序 */
		$orderby_arr= array(
			0    => array('id'=>'DESC'),
			1    => array('id'=>'ASC'),
			2    => array('order_num'=>'ASC'),
			3    => array('order_num'=>'DESC'),
			4    => array('date_add'=>'ASC'),
			5    => array('date_add'=>'DESC'),
		);
		$orderby = controller_tool::orderby($orderby_arr);
		$query_struct['orderby'] = $orderby;
		/* 得到默认每页显示多少条 */
		$per_page = controller_tool::per_page();

		$service = Payment_logService::get_instance();
		
		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => $service->query_count($query_struct),
			'items_per_page' => $per_page,
		));

		$query_struct['limit']['offset'] = $limit = $this->pagination->sql_offset;
		$query_struct['limit']['per_page'] = $per_page;
		
		/* 获取数据 */
		$list = $service->query_assoc($query_struct);//helper::dump($list); exit();
		/*$orm_instance = ORM::factory('payment_log');
		$list = $orm_instance->with('user')->find_all($per_page, $limit);//helper::dump($list);
		foreach ($list as $val) {
			echo $val->user->firstname.' ';
			//helper::dump($val);
		}exit();*/
		$user_ids = $payment_type_ids = array();
		$users = $payment_types = array();
		foreach ($list as $val) {
			if (!in_array($val['user_id'], $user_ids)) {
				$user_ids[] = $val['user_id'];
				$users[$val['user_id']]['lastname'] = '';
			}
			if (!in_array($val['payment_type_id'], $payment_type_ids)) {
				$payment_type_ids[] = $val['payment_type_id'];
				$payment_types[$val['payment_type_id']]['name'] = '';
			}
		}//helper::dump($user_ids);
		
		/* 用户数据 */
		if ($user_ids) {
			$join_query_struct = array(
	            'where'=>array('id'=>$user_ids),
	        );
			$user_service = UserService::get_instance();
			$users = $user_service->query_assoc($join_query_struct);
			foreach ($users as $key=>$val) {
				$users[$val['id']] = $val;
				unset($users[$key]);
			}
		}
		/* 支付类型数据 */
		if ($payment_type_ids) {
			$join_query_struct = array(
	            'where'=>array('id'=>$payment_type_ids),
	        );
			$payment_type_service = Payment_typeService::get_instance();
			$payment_types = $payment_type_service->query_assoc($join_query_struct);
			foreach ($payment_types as $key=>$val) {
				$payment_types[$val['id']] = $val;
				unset($payment_types[$key]);
			}
		}
		
		/* 调用列表 */
		$this->template->content->list = $list;
		$this->template->content->users = $users;
		$this->template->content->payment_types = $payment_types;
		$this->template->content->where = $where_view;
	}
	/**
	 * ajax get payment_log 
	 */
	public function ajax_index()
	{
		/* 权限检查 订单列表 */
        //role::check('manage_payment_log');
		$return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
		if(1 || request::is_ajax()) 
		{
			$order_num = $this->input->get('order_num');
			if ($order_num) {
				$query_struct = array(
		            'where'=>array('order_num'=>$order_num),
		            'orderby'   => array(
		                'id'   =>'DESC',
		            ),
		        );
				$service = Payment_logService::get_instance();
				$list = $service->query_assoc($query_struct);
				
				$return_template = $this->template = new View('template_blank');
				$this->template->content = new View("manage/payment_log/ajax_index");
				$this->template->content->list = $list;
				$return_str = $return_template->render();
			} else {
				$return_str = '参数错误';
			}
			$return_struct['status'] = 1;
			$return_struct['code'] = 200;
			$return_struct['msg'] = 'Success';
			$return_struct['content'] = $return_str;
			exit(json_encode($return_struct));
		}
	}
}