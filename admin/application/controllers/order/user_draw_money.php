<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_draw_money_Controller extends Template_Controller {
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
	public function index($status = 'all') {
	    /* 权限检查 订单列表 */
	    role::check('user_draw_money_review');
        
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
        	case 'review':
        	    role::check('user_draw_money_review');
        		$query_struct_default['where']['status'] = 0;
        		break;
        	case 'hasreview':
        	    role::check('user_draw_money_review');
        		$query_struct_default['where']['status'] = 1;
        		break;
        	case 'reviewfail':
        	    role::check('user_draw_money_input');
        		$query_struct_default['where']['status'] = 2;
        		break;
        	case 'hascharge':
        	    role::check('user_draw_money_charge');
        		$query_struct_default['where']['status'] = 3;
        		break;
        	case 'chargefail':
        		$query_struct_default['where']['status'] = 4;
        		break;
        	case 'chargewin':
        		$query_struct_default['where']['status'] = 5;
        		break;
        	default:
        }

		/* 搜索功能 */
		$search_arr      = array('id', 'money', 'account', 'truename', 'bank_name', 'province', 'city', 'bank_found');
		$search_value    = $this->input->get('search_value');
		$search_type     = $this->input->get('search_type');
		$where_view      = array();
		if($search_arr)
		{
			foreach($search_arr as $value)
			{
				if($search_type == $value && strlen($search_value) > 0)
				{
					 $query_struct_default['like'][$value] = $search_value;
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
        $acobj = User_draw_moneyService::get_instance();
        $return_data['count'] = $acobj->count($query_struct_current);        //统计数量
        
		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => $return_data['count'],
			'items_per_page' => $query_struct_current['limit']['per_page'],    
		));

        $query_struct_current['limit']['page'] = $this->pagination->current_page;
        $return_data['list'] = $acobj->query_assoc($query_struct_current);
        
        $return_data['account_type'] = Kohana::config('ticket_type');
        
        $i = 0;
        $managers = array();
        $users = array();
        foreach ($return_data['list'] as $rowlist)
        {
            $return_data['list'][$i] = $rowlist;
            $return_data['list'][$i]['other'] = json_decode($rowlist['other']);
            $users[$rowlist['user_id']] = $rowlist['user_id'];
            if (!empty($rowlist['manager_id']))
            {
                $managers[$rowlist['manager_id']] = $rowlist['manager_id'];
            }
            $i++;
        }

        $userobj = user::get_instance();
        $return_data['users'] = array();
        $return_data['managers'] = array();
        
        foreach($users as $rowuser)
        {
            $return_data['users'][$rowuser] = $userobj->get($rowuser);
        }
        
        foreach ($managers as $rowman)
        {
            $managers[$rowman] = Mymanager::instance($rowman)->get();
            if (!empty($managers[$rowman]))
            {
                $return_data['managers'][$rowman] = $managers[$rowman]['username'];
            }
        }
        $return_data['managers'] = $managers;
        $return_data['status'] = $status;

		$this->template->content = new View("order/user_draw_money", $return_data);
		$this->template->content->where = $where_view;
	}
	
	
	/*
	 * 设置审核通过
	 */
	public function set_hasreview()
	{
	    /* 权限检查 订单列表 */
        role::check('user_draw_money_review');
        
        if (empty($_POST))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money');
        }
        
        $request_data = $this->input->post();
        
        if (empty($request_data['order_ids']))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money');
        }
        
        $id = $request_data['order_ids'][0];
        
        $user_draw_moeny = User_draw_moneyService::get_instance();
        $user_draw_moeny->set_hasreview($id, $this->manager_id);
        
        //添加日志
        $logs_data = array();
        $logs_data['manager_id'] = $this->manager_id;
        $logs_data['user_log_type'] = 30;
        $logs_data['ip'] = tool::get_long_ip();
        $logs_data['memo'] = "成功id为:{$id}为审核通过";
        ulog::instance()->add($logs_data);
        
        remind::set("成功将id为:{$id}设为审核通过",'/order/user_draw_money/index/review','success');
	}

	
	/*
	 * 设置审核失败
	 */
	public function set_reviewfail()
	{
	    /* 权限检查 订单列表 */
        role::check('user_draw_money_review');
        
        if (empty($_POST))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money');
        }
        
        $request_data = $this->input->post();
        
        if (empty($request_data['order_ids']))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money');
        }
        
        $id = $request_data['order_ids'][0];
        
        $user_draw_moeny = User_draw_moneyService::get_instance();
        $user_draw_moeny->set_reviewfail($id, $this->manager_id);
        
        //添加日志
        $logs_data = array();
        $logs_data['manager_id'] = $this->manager_id;
        $logs_data['user_log_type'] = 30;
        $logs_data['ip'] = tool::get_long_ip();
        $logs_data['memo'] = "成功将id为:{$id}设为审核失败";
        ulog::instance()->add($logs_data);
        
        remind::set("成功将id为:{$id}设为审核失败",'/order/user_draw_money/index/review','success');
	}	


	

	/*
	 * 设为打款失败
	 */
	public function set_chargefail($id, $page = 1)
	{
	    /* 权限检查 订单列表 */
        role::check('user_draw_money_input');
        
        if (empty($id))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money/index/hasreview');
        }
        $user_draw_moeny = User_draw_moneyService::get_instance();
        $result = $user_draw_moeny->get($id);
        
	    if (empty($result))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money/index/hasreview/');
        }
        
        //提交操作
        if (!empty($_POST))
        {
            tool::filter_strip_tags($_POST);
            $memo = $_POST['memo'];

            $user_draw_moeny = User_draw_moneyService::get_instance();
            $user_draw_moeny->set_chargefail($id, $memo, $this->manager_id);
            
            //添加日志
            $logs_data = array();
            $logs_data['manager_id'] = $this->manager_id;
            $logs_data['user_log_type'] = 30;
            $logs_data['ip'] = tool::get_long_ip();
            $logs_data['memo'] = "成功将id为:{$id}设为打款失败";
            ulog::instance()->add($logs_data);
            
            remind::set("成功将id为:{$result['id']}的款项设为打款失败",'order/user_draw_money/index/hasreview/?page='.$page,'success');
        }
        
	    if ($result['status'] != 1)
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money/index/hasreview');
        }
                
        $this->template->content = new View("order/user_draw_money_chargefail", $result);
        
	}	
	
	
	
	/*
	 * 设为打款失败
	 */
	public function BACKKK_set_chargefail()
	{
	    /* 权限检查 订单列表 */
        role::check('user_draw_money_input');
        
        if (empty($_POST))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money/index/review');
        }
        
        $request_data = $this->input->post();
        
        if (empty($request_data['order_ids']))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money/index/review');
        }
        
        $id = $request_data['order_ids'][0];
        
        $user_draw_moeny = User_draw_moneyService::get_instance();
        $user_draw_moeny->set_chargefail($id, $this->manager_id);
        
        //添加日志
        $logs_data = array();
        $logs_data['manager_id'] = $this->manager_id;
        $logs_data['user_log_type'] = 30;
        $logs_data['ip'] = tool::get_long_ip();
        $logs_data['memo'] = "成功id为:{$id}为打款失败";
        ulog::instance()->add($logs_data);
        
        remind::set("成功将id为:{$id}设为审核失败",'/order/user_draw_money/index/review','success');
	}	
		

	/*
	 * 设为已打款
	 */
	public function set_hascharge($id, $page = 1)
	{
	    /* 权限检查 订单列表 */
        role::check('user_draw_money_input');
        
        if (empty($id))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money/index/hasreview');
        }
        $user_draw_moeny = User_draw_moneyService::get_instance();
        $result = $user_draw_moeny->get($id);
        
	    if (empty($result))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money/index/hasreview/');
        }
        
        //提交操作
        if (!empty($_POST))
        {
            tool::filter_strip_tags($_POST);
            $memo = '流水号：'.$_POST['serialnumber']."\n更多信息：".$_POST['memo'];

            $user_draw_moeny = User_draw_moneyService::get_instance();
            $user_draw_moeny->set_hascharge($id, $memo, $this->manager_id);
            
            //添加日志
            $logs_data = array();
            $logs_data['manager_id'] = $this->manager_id;
            $logs_data['user_log_type'] = 30;
            $logs_data['ip'] = tool::get_long_ip();
            $logs_data['memo'] = "成功id为:{$id}为已打款";
            ulog::instance()->add($logs_data);
            
            remind::set("成功为id:{$result['id']}的款项设为已打款",'order/user_draw_money/index/hasreview/?page='.$page,'success');
        }
        
	    if ($result['status'] != 1)
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money/index/hasreview');
        }
                
        $this->template->content = new View("order/user_draw_money_hascharge", $result);
        
	}
	
	
	/*
	 * 设为提现成功
	 */
	public function set_chargewin()
	{
	    /* 权限检查 订单列表 */
        role::check('user_draw_money_charge');
        
        if (empty($_POST))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money/index/hascharge');
        }
        
        $request_data = $this->input->post();
        
        if (empty($request_data['order_ids']))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money/index/hascharge');
        }
        
        $id = $request_data['order_ids'][0];
        
        $user_draw_moeny = User_draw_moneyService::get_instance();
        $user_draw_moeny->set_chargewin($id, $this->manager_id);
        
        //添加日志
        $logs_data = array();
        $logs_data['manager_id'] = $this->manager_id;
        $logs_data['user_log_type'] = 30;
        $logs_data['ip'] = tool::get_long_ip();
        $logs_data['memo'] = "成功id为:{$id}为提现成功";
        ulog::instance()->add($logs_data);
        
        remind::set("成功将id为:{$id}设为提现成功",'/order/user_draw_money/index/hascharge','success');
	}
	

	/*
	 * 导出所选
	 */
	public function exportinfo()
	{
	    /* 权限检查 订单列表 */
        role::check('user_draw_money_review');
        
        if (empty($_POST))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money/index/hascharge');
        }
        
        $request_data = $this->input->post();
        
        if (empty($request_data['order_ids']))
        {
            remind::set(Kohana::lang('o_global.bad_request'),'order/user_draw_money/index/hascharge');
        }
        
        $user_draw_moeny = User_draw_moneyService::get_instance();
        $query_struct = array();
        $query_struct['where']['id'] = $request_data['order_ids'];
        $results = $user_draw_moeny->query_assoc($query_struct);
        
        $expexcel = array();
        
        $m = 0;
        $expexcel[$m] = array('日期', 
        				'总金额', 
        				'总笔数', 
        				'支付宝帐号', '', '', '', '', '', '');
        
        $all_money = 0;
        $all_count = 0;
        $infos = array(); 
		foreach($results as $key => $value)
        {
            $row = array();
            
            $row[] = ' '.$value['id'];
            $row[] = $value['truename'];
            $row[] = ' '.$value['account'];
            $row[] = $value['bank_name'];
            $row[] = $value['province'];
            $row[] = $value['city'];
            $row[] = $value['bank_found'];
            $row[] = $value['money'];
            $row[] = ' 2';
            $row[] = $value['memo'] ;//str_replace("\n", ";", $value['memo']);
            $all_money = $all_money + $value['money'];
            
            $infos[] = $row;
            $all_count++;
        }

        
        $alipay_config = Kohana::config('site_config.site.alipay_account');
        
        $m++;
        $expexcel[$m][] = date('Y-m-d');
        $expexcel[$m][] = ' '.$all_money;
        $expexcel[$m][] = ' '.$all_count;
        $expexcel[$m][] = $alipay_config;
        $expexcel[$m][] = '';
        $expexcel[$m][] = '';
        $expexcel[$m][] = '';
        $expexcel[$m][] = '';
        $expexcel[$m][] = '';
        $expexcel[$m][] = '';
        
        $m++;
        $expexcel[$m] = array(
                        '商户流水号', 
                        '收款银行户名', 
                        '收款银行帐号', 
                        '收款开户银行', 
        				'收款银行所在省份', 
        				'收款银行所在市', 
                        '收款支行名称', 
        				'金额', 
                        '对公对私标志',
                        '备注',
                    );
        
        $expexcel = array_merge($expexcel, $infos);
        
        myexcel::get_instance()->get_excel_from_rows($expexcel, '会员提现财务导出');

        //添加日志
        $logs_data = array();
        $logs_data['manager_id'] = $this->manager_id;
        $logs_data['user_log_type'] = 30;
        $logs_data['ip'] = tool::get_long_ip();
        $logs_data['memo'] = "成功导出选定款项款项,id:".implode(',', $request_data['order_ids']);
        ulog::instance()->add($logs_data);
        exit();
	}
	
	
	

}