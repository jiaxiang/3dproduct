<?php defined('SYSPATH') OR die('No direct access allowed.');

class Index_Controller extends Template_Controller {
    
    public function __construct(){
        //权限验证
        role::check('default');
		parent::__construct();
    }
    
	function index(){
        $this->template = new View("index");
    }
    
    function server_info(){
        $this->template = new View("server_info");
    }
    
	function desktop()
	{
        //权限验证
        role::check('default');
        
		//各个时间段的订单数量
		$stat_date = Myorder::instance()->stat_by_date();

		//已支付订单数量
        // 初始化默认查询条件
        $order_query_struct = array(
            'where'=>array('active'=>1),
            'like'=>array(),
            'orderby'   => array(
                'order'   =>'ASC',
            ),
            'limit'     => array(
                'per_page'  =>20,
                'offset'    =>0,
            ),
        );
        
        /* 已支付未做处理的订单 */
        $order_query_struct['where']['pay_status'] = 3;
        $order_query_struct['where']['order_status'] = 1;
        $order_query_struct['where']['ship_status'] = 1;
        $count_pay = Myorder::instance()->query_count($order_query_struct);

		//有订单用户数量
		$count_order_user = Myorder::instance()->count_order_user();

        /* 初始化默认查询条件 */
        $user_query_struct = array(
            'where'=>array(),
            'like'=>array(),
            'orderby'   => array(),
            'limit'     => array(
                //'per_page'  => 20,
                //'offset'    => 0,
            ),
        );		
		//用户总数
		$count_user = Myuser::instance()->query_count();

		//今日用户
		$today		=date( 'Y-m-d H:i:s', mktime(0,0,0,date('m') ,date('d'),date('Y')));
		$user_query_struct['where']['date_add >='] = $today;
		$count_today_user = Myuser::instance()->query_count($user_query_struct);

		//有留言的订单数量
		$count_order_message = Myorder_message::instance()->count_order();
        
		//新的网站留言
		$count_contact_us = Mycontact_us::instance()->count(array('active'=>'1'));

		/* 平台公告列表 */
        $notice_query_struct = array(
            'where'=>array(),
            'like'=>array(),
            'orderby'   => array(
                'id'    => 'DESC',
            ),
            'limit'     => array(
                'per_page'  =>3,
                'offset'    =>0,
            ),
        );
		$notices = Mynotice::instance()->query_assoc($notice_query_struct);
                
        $this->template->content = new View("index_desktop");
		$this->template->content->count_pay = $count_pay;
		$this->template->content->count_order_message = $count_order_message;
		$this->template->content->count_contact_us = $count_contact_us;

		$this->template->content->count_order_user = $count_order_user;
		$this->template->content->count_user = $count_user;
		$this->template->content->count_today_user = $count_today_user;
		$this->template->content->notices = $notices;
		$this->template->content->stat_date = $stat_date;
	}

    function calculator()
	{
        $this->template = new View("calculator");
    }

    function set_profiler()
	{
        Session::instance()->set('profiler',1);
        exit('set_profiler');
    }

    function delete_profiler()
	{
        Session::instance()->delete('profiler');
    }
}


