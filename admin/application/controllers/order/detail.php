<?php defined('SYSPATH') OR die('No direct access allowed.');

class Detail_Controller extends Template_Controller {
    // Set the name of the template to use
    public $template_ = 'layout/common_html';

	public $order_basic_obj, $order_detail_obj;
    public function __construct() {
        parent::__construct();
        if ($this->is_ajax_request() == TRUE) {
            $this->template = new View('layout/default_json');
        }
        role::check('orders');
        $this->order_basic_obj = OrderBasic::instance();
        $this->order_detail_obj = OrderDetail::instance();
    }

	/**
	 * 订单列表
	 */
	public function index($status = 'all') {
        //初始化默认查询结构体
        $query_struct_default = array (
            'orderby' => array (
                'id' => 'DESC'
            ),
            'limit' => array (
                'per_page' => 10,
                'page' => 1
            )
        );


        switch ($status)
        {
        	case 'noticket':
        		$query_struct_default['where']['status'] = 0;
        		break;
        	case 'hasticket':
        		$query_struct_default['where']['status'] = 1;
        		break;
        	case 'hasbonus':
        		$query_struct_default['where']['status'] = 2;
        		break;
        	case 'hasprice':
        		$query_struct_default['where']['bonus > '] = 0;
        		$query_struct_default['where']['status'] = 2;
        		break;
        	case 'invalid':
        		$query_struct_default['where']['status'] = -1;
        		break;
        	case 'confirm_invalid':
        		$query_struct_default['where']['status'] = -2;
        		break;
        	case 'input_bonus':
        		$query_struct_default['where']['bonus'] = -9999;
        		$query_struct_default['where']['status'] = 2;
        		break;
        	case 'ticket_tj':
        		$query_struct_default['where']['status > '] = 0;
        		$query_struct_default['where']['port > '] = 0;
        		if (strlen($this->input->get('start_time')) == 0) {
        			$query_struct_default['where']['time_print >= '] = date('Y-m-d').' 02:00:00';
        		}
        		if (strlen($this->input->get('end_time')) == 0) {
        			$mklasttime = mktime(2, 0, 0, date("m"), date("d")+1, date("Y"));
        			$lasttime = date('Y-m-d H:i:s', $mklasttime);
        			$query_struct_default['where']['time_print <= '] = $lasttime.' 02:00:00';
        		}
        	default:
        }

		/* 搜索功能 */
		$search_arr      = array('id','order_id');
		$search_value    = $this->input->get('search_value');
		$search_type     = $this->input->get('search_type');
		$where_view      = array();
		if (strlen($this->input->get('start_time')) > 0) {
			$query_struct_default['where']['time_print >='] = $this->input->get('start_time').' 02:00:00';
			$where_view['start_time'] = $this->input->get('start_time');
		}
		if (strlen($this->input->get('end_time')) > 0) {
			$query_struct_default['where']['time_print <='] = $this->input->get('end_time').' 02:00:00';
			$where_view['end_time'] = $this->input->get('end_time');
		}
		if($search_arr)
		{
			foreach($search_arr as $value)
			{
				if($search_type == $value && strlen($search_value) > 0)
				{
					 //$query_struct_default['like'][$value] = $search_value;
					$query_struct_default['where'][$value.' = '] = $search_value;
				}
			}
			$where_view['search_type'] = $search_type;
			$where_view['search_value'] = $search_value;
		}

		$request_data = $this->input->get();

        //初始化当前查询结构体
        $query_struct_current = array();

        //设置合并默认查询条件到当前查询结构体
        $query_struct_current = array_merge($query_struct_current, $query_struct_default);

        //列表排序
        $orderby_arr = array (
            0 => array (
                'id' => 'DESC'
            ),
            1 => array (
                'id' => 'ASC'
            ),
        );
        $orderby = controller_tool::orderby($orderby_arr);
        // 排序处理
        if(isset($request_data['orderby']) && is_numeric($request_data['orderby'])){
            $query_struct_current['orderby'] = $orderby;
        }
        $query_struct_current['orderby'] = $orderby;

        //每页条目数
        controller_tool::request_per_page($query_struct_current, $request_data);

        //调用服务执行查询
        //$acobj = Ticket_numService::get_instance();
        $return_data['count'] = $this->order_detail_obj->count($query_struct_current); //统计数量

		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => $return_data['count'],
			'items_per_page' => $query_struct_current['limit']['per_page'],
		));

        $query_struct_current['limit']['page'] = $this->pagination->current_page;
        $return_data['list'] = $this->order_detail_obj->query_assoc($query_struct_current);

        /* $total_money = 0;
        if ($status == 'ticket_tj') {
	        $query = 'select sum(price) as total_money from order_details where status > 0';
	        foreach ($query_struct_current as $key => $val) {
	        	if ($key == 'like') {
	        		foreach ($val as $k => $v) {
	        			$query .= ' and '.$k.'="'.$v.'"';
	        		}
	        	}
	        	if ($key == 'where') {
	        		foreach ($val as $k => $v) {
	        			$query .= ' and '.$k.'"'.$v.'"';
	        		}
	        	}
	        }
	        $db = Database::instance();
        	$total_money_results = $db->query($query);
        	foreach ($total_money_results as $total_money_result) {
        		$total_money += $total_money_result->total_money;
        	}
        } */

        //$return_data['account_type'] = Kohana::config('ticket_type');

        /* $i = 0;
        $plan_ids = array();
        $managers = array();
        foreach ($return_data['list'] as $rowlist)
        {
            $plan_ids[$rowlist['ticket_type']][$rowlist['plan_id']] = $rowlist['plan_id'];
            $return_data['list'][$i] = $rowlist;

            if (!empty($rowlist['manager_id']))
            {
                $managers[$rowlist['manager_id']] = $rowlist['manager_id'];
            }
            $i++;
        } */
		//$return_data['total_money'] = $total_money;
        $users = array();
        $return_data['plans'] = array();
        $return_data['plans_basic'] = array();
        /* $planobj = plan::get_instance();


        foreach ($plan_ids as $key => $rowplan)
        {
            foreach ($rowplan as $senplan)
            {
                $return_data['plans'][$key][$senplan] = $planobj->get_plan_by_tid($senplan, $key);

                if (!empty($return_data['plans'][$key][$senplan]))
                {
                   $users[$return_data['plans'][$key][$senplan]['user_id']] = $return_data['plans'][$key][$senplan]['user_id'];
                }

                if (empty($return_data['plans_basic'][$return_data['plans'][$key][$senplan]['basic_id']]))
                {
                   $return_data['plans_basic'][$return_data['plans'][$key][$senplan]['basic_id']] = Plans_basicService::get_instance()->get_by_ordernum($return_data['plans'][$key][$senplan]['basic_id']);
                }


            }
        } */

        /* $userobj = user::get_instance();
        $return_data['users'] = array();
        $return_data['managers'] = array();
        $return_data['status'] = $status;

        foreach($users as $rowuser)
        {
            $return_data['users'][$rowuser] = $userobj->get($rowuser);
        }

        foreach ($managers as $rowman)
        {
            $managers[$rowman] = Mymanager::instance($rowman)->get();
            if (!empty($managers[$rowman]))
            {
                $managers[$rowman] = $managers[$rowman]['username'];
            }
        }
        $return_data['managers'] = $managers;

        $return_data['site_config'] = Kohana::config('site_config.site');
        $host = $_SERVER['HTTP_HOST'];
        $dis_site_config = Kohana::config('distribution_site_config');
        if (array_key_exists($host, $dis_site_config) == true && isset($dis_site_config[$host])) {
        	$return_data['site_config']['site_title'] = $dis_site_config[$host]['site_name'];
        	$return_data['site_config']['keywords'] = $dis_site_config[$host]['keywords'];
        	$return_data['site_config']['description'] = $dis_site_config[$host]['description'];
        } */

		$this->template->content = new View("order/detail_list", $return_data);
		$this->template->content->where = $where_view;
	}

	public function show_detail($id) {
		$data = $this->order_detail_obj->get_order_by_id($id);
		$userdata = Myuser::instance($data['uid'])->get();
		$this->template->content = new View("order/show_detail");
		$this->template->content->data = $data;
		$this->template->content->user = $userdata;
	}

	/*
	 * 设置彩票为已出票
	 */
	public function set_yes()
	{
	    /* 权限检查 订单列表 */
        role::check('order_list');

        if (empty($_POST))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum');
        }

        $request_data = $this->input->post();

        if (empty($request_data['order_ids']))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum');
        }

        $ticketobj = ticket::get_instance();

        $num = $ticketobj->update_status($request_data['order_ids'], 1, array('0'), $this->manager_id);
        $sp_num = 0;
        if ($num > 0) {
        	foreach ($request_data['order_ids'] as $ticket_id) {
        		$ticket_info = $ticketobj->get($ticket_id);
        		if ($ticket_info['ticket_type'] == 1 || $ticket_info['ticket_type'] == 6) {
        			$r = $ticketobj->update_jc_ticket_sp($ticket_id, $ticket_info['play_method'], $ticket_info['codes'], $ticket_info['ticket_type']);
        			if ($r == true) {
        				$sp_num ++;
        			}
        		}
        	}
        }
        //添加日志
        $logs_data = array();
        $logs_data['manager_id'] = $this->manager_id;
        $logs_data['user_log_type'] = 28;
        $logs_data['ip'] = tool::get_long_ip();
        $logs_data['memo'] = "成功更新{$num}张彩票为已出票";
        ulog::instance()->add($logs_data);

        remind::set("成功更新{$num}张彩票为已出票！其中{$sp_num}张彩票赔率已更新",'/order/ticketnum/index/noticket','success');
	}


	/*
	 * 设置彩票为未出票
	 */
	public function set_no()
	{
	    /* 权限检查 订单列表 */
        role::check('order_list');

        if (empty($_POST))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum');
        }

        $request_data = $this->input->post();

        if (empty($request_data['order_ids']))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum');
        }

        $ticketobj = ticket::get_instance();

        $num = $ticketobj->update_status($request_data['order_ids'], 0, array('1'), $this->manager_id);

        //添加日志
        $logs_data = array();
        $logs_data['manager_id'] = $this->manager_id;
        $logs_data['user_log_type'] = 28;
        $logs_data['ip'] = tool::get_long_ip();
        $logs_data['memo'] = "成功更新{$num}张彩票为未出票";
        ulog::instance()->add($logs_data);

        remind::set("成功更新{$num}张彩票为未出票",'/order/ticketnum/index/hasticket','success');
	}


	/*
	 * 设置彩票为已兑奖
	 */
	public function set_duijiang()
	{
	    /* 权限检查 订单列表 */
        role::check('order_list');

        if (empty($_POST))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum');
        }

        $request_data = $this->input->post();

        if (empty($request_data['order_ids']))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum');
        }

        $ticketobj = ticket::get_instance();

        $num = $ticketobj->update_status($request_data['order_ids'], 2, array('1'), $this->manager_id);

        //添加日志
        $logs_data = array();
        $logs_data['manager_id'] = $this->manager_id;
        $logs_data['user_log_type'] = 28;
        $logs_data['ip'] = tool::get_long_ip();
        $logs_data['memo'] = "成功更新{$num}张彩票为已兑奖";
        ulog::instance()->add($logs_data);

        remind::set("成功更新{$num}张彩票为已兑奖",'/order/ticketnum/index/hasticket','success');
	}


	/*
	 * 设置彩票为已作废
	 */
	public function set_invalid()
	{
	    /* 权限检查 订单列表 */
        role::check('order_list');

        if (empty($_POST))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum');
        }

        $request_data = $this->input->post();

        if (empty($request_data['order_ids']))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum');
        }

        $ticketobj = ticket::get_instance();

        $num = $ticketobj->update_status($request_data['order_ids'], -1, array('0'), $this->manager_id);

        //添加日志
        $logs_data = array();
        $logs_data['manager_id'] = $this->manager_id;
        $logs_data['user_log_type'] = 28;
        $logs_data['ip'] = tool::get_long_ip();
        $logs_data['memo'] = "成功更新{$num}张彩票为已作废";
        ulog::instance()->add($logs_data);

        remind::set("成功更新{$num}张彩票为已作废",'/order/ticketnum/index/noticket','success');
	}



	/*
	 * 设置彩票为已作废
	 */
	public function set_confirm_invalid()
	{
	    /* 权限检查 订单列表 */
        role::check('order_list');

        if (empty($_POST))
        {
            remind::set(Kohana::lang('o_global.bad_request'), 'order/ticketnum/index/invalid');
        }

        $request_data = $this->input->post();

        if (empty($request_data['order_ids']))
        //if (empty($request_data['order_ids'][0]))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum/index/invalid');
        }

        //$id = $request_data['order_ids'][0];

        $ticketobj = ticket::get_instance();
        //$result = array();
        for ($i = 0; $i < count($request_data['order_ids']); $i++) {
        	$id = $request_data['order_ids'][$i];
        	$result = $ticketobj->get($id);
	        if (empty($result))
	        {
	            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum/index/invalid');
	        }
	        else
	        {
	            if ($result['status'] != -1)
	            {
	                remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum/index/invalid');
	            }
	        }
        	plan::get_instance()->refund_by_ticket($result['ticket_type'], $result['order_num'], $result['money']);
        }
        /*$result = $ticketobj->get($id);

        if (empty($result))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum/index/invalid');
        }
        else
        {
            if ($result['status'] != -1)
            {
                remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum/index/invalid');
            }
        }*/

        //plan::get_instance()->refund_by_ticket($result['ticket_type'], $result['order_num'], $result['money']);
        //$num = $ticketobj->update_status(array($id), -2, array('-1'), $this->manager_id);
        $num = $ticketobj->update_status($request_data['order_ids'], -2, array('-1'), $this->manager_id);
        //添加日志
        $logs_data = array();
        $logs_data['manager_id'] = $this->manager_id;
        $logs_data['user_log_type'] = 28;
        $logs_data['ip'] = tool::get_long_ip();
        $logs_data['memo'] = "成功更新{$num}张彩票为确认已作废";
        ulog::instance()->add($logs_data);

        remind::set("成功更新{$num}张彩票为确认已作废",'/order/ticketnum/index/confirm_invalid','success');
	}


	/*
	 * 录入奖金
	 */
	public function set_bonus($id, $page = 1)
	{
	    /* 权限检查 订单列表 */
        role::check('order_list');

        if (empty($id))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum/');
        }

        $ticketobj = ticket::get_instance();
        $result = $ticketobj->get($id);

	    if (empty($result))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum/');
        }

        //提交操作
        if (!empty($_POST))
        {
            tool::filter_strip_tags($_POST);
            $bonus = $_POST['money'];
            $num = $_POST['num'];
            $password = $_POST['password'];

            $flag = $ticketobj->update_bonus($id, $bonus, $num, $password, $this->manager_id);

            $addchar = '成功';
            if (!$flag)
                $addchar = '失败';

            //添加日志
            $logs_data = array();
            $logs_data['manager_id'] = $this->manager_id;
            $logs_data['user_log_type'] = 28;
            $logs_data['ip'] = tool::get_long_ip();
            $logs_data['memo'] = "为id:{$result['id']}的彩票录入奖金:{$bonus}{$addchar}";
            ulog::instance()->add($logs_data);

            if ($flag)
            {
                remind::set("成功为id:{$result['id']}的彩票录入奖金:{$bonus}",'/order/ticketnum/index/hasticket/?page='.$page,'success');
            }
            else
            {
                remind::set("为id:{$result['id']}的彩票录入奖金:{$bonus}失败",'/order/ticketnum/?page='.$page,'error');
            }
        }

	    if ($result['status'] != 1 && !($result['status'] ==2 && $result['bonus'] == -9999))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/ticketnum/');
        }

        //获取更多信息
        $result['plan'] = plan::get_instance()->get_plan_by_tid($result['plan_id'], $result['ticket_type']);
        $result['user'] = user::get_instance()->get($result['plan']['user_id']);

        $result['manager'] = '';
        if (!empty($result['manager_id']))
        {
            $manager = Mymanager::instance($result['manager_id'])->get();
            $result['manager'] = $manager['username'];
        }

        $this->template->content = new View("order/order_set_bonus", $result);

	}

	public function tj_all() {
		/* 权限检查 订单列表 */
		role::check('tickets_tj_all');
		$return_data = array();
		$where = 'status > 0 and port is not null';
		$where1 = 'status = 2 and port is not null and bonus > 0';
		if (strlen($this->input->get('start_time')) == 0) {
			$firsttime = date('Y-m-d');
		}
		else {
			$firsttime = $this->input->get('start_time');
		}
		if (strlen($this->input->get('end_time')) == 0) {
			$mklasttime = mktime(2, 0, 0, date("m"), date("d")+1, date("Y"));
			$lasttime = date('Y-m-d H:i:s', $mklasttime);
		}
		else {
			$lasttime = $this->input->get('end_time');
		}

		$where .= ' and time_print >= "'.$firsttime.' 02:00:00"';
		$where .= ' and time_print <= "'.$lasttime.' 02:00:00"';
		$where1 .= ' and time_print >= "'.$firsttime.' 02:00:00"';
		$where1 .= ' and time_print <= "'.$lasttime.' 02:00:00"';
		$return_data['start_time'] = $firsttime;
		$return_data['end_time'] = $lasttime;

		$query = 'select count(id) as c ,sum(money) as total_money ,port from ticket_nums where '.$where.' group by port order by port';
		$query1 = 'select count(id) as c ,sum(bonus) as total_bonus ,port from ticket_nums where '.$where1.' group by port order by port';

		$db = Database::instance();
		$total_money_results = $db->query($query);
		$total_bonus_results = $db->query($query1);

		$results = array();
		$i = 0;
		foreach ($total_money_results as $total_money_result) {
			$count = $total_money_result->c;
			$money = $total_money_result->total_money;
			$port = $total_money_result->port;
			$results[$i]['count'] = $count;
			$results[$i]['money'] = $money;
			$results[$i]['port'] = $port;
			$i++;
		}

		foreach ($total_bonus_results as $total_bonus_result) {
			$count = $total_bonus_result->c;
			$bonus = $total_bonus_result->total_bonus;
			$port = $total_bonus_result->port;
			foreach ($results as $k => $r) {
				if ($r['port'] == $port) {
					$results[$k]['bcount'] = $count;
					$results[$k]['bonus'] = $bonus;
				}
			}
		}

		$return_data['list'] = $results;

		$return_data['site_config'] = Kohana::config('site_config.site');
		$host = $_SERVER['HTTP_HOST'];
		$dis_site_config = Kohana::config('distribution_site_config');
		if (array_key_exists($host, $dis_site_config) == true && isset($dis_site_config[$host])) {
			$return_data['site_config']['site_title'] = $dis_site_config[$host]['site_name'];
			$return_data['site_config']['keywords'] = $dis_site_config[$host]['keywords'];
			$return_data['site_config']['description'] = $dis_site_config[$host]['description'];
		}
		//d($return_data);
		$this->template->content = new View("order/order_ticketnum_tj", $return_data);
		$this->template->content->where = $where_view;
	}

}