<?php
class Settlerealtimerptdtl_Controller extends Template_Controller 
{
	public function __construct()
	{
		parent::__construct();
        role::check('distribution_system_manage');
	}
	public function index()
	{
		$settlerealtimerptdtlDao = Mysettlerealtimerptdtl::instance();
		$ticket_type = Kohana::config('ticket_type.type');
		
		$per_page = controller_tool::per_page();
        $orderby_arr= array
        (
                0   => array('id'=>'DESC'),
                1   => array('id'=>'ASC'),
                2   => array('order'=>'ASC'),
                3   => array('order'=>'DESC')
        );
        $orderby    = controller_tool::orderby($orderby_arr);
        $query_struct = array(
            'where'=>array(
            ),
            'orderby'       => $orderby,
            'limit'         => array(
                                'per_page'  =>$per_page,
                                'offset'    =>0
                                )
        );
        
		/**
		 * 搜索
		 */
		$agentlastname = '';
		$clientlastname = '';
		$ticket_type_searchkey = '';
		$search_arr = array('ticket_type','date_begin','date_end','agentid','user_id','agentlastname','clientlastname','masterid');
		
		foreach($this->input->get() as $key=>$value)
		{
			if(in_array($key,$search_arr))
			{
				if($key == 'date_begin')
				{
					$query_struct['where']["settletime >"] = $value . ' 00:00:00';
				}
				elseif($key == 'date_end')
				{
					$query_struct['where']["settletime <"] = $value . ' 24:00:00';
				}
				elseif($key == 'agentlastname' && !empty($value) )
				{
					$query_struct['where']["agent.lastname ="] = $value;
					$agentlastname = $value;
				}
				elseif($key == 'clientlastname' && !empty($value) )
				{
					$query_struct['where']["uuu.lastname ="] = $value;
					$clientlastname = $value;
				}
				elseif($key == 'ticket_type' && !empty($value) )
				{
					$query_struct['where'][$key] = $value;
					$ticket_type_searchkey = $value;
				}
				elseif(!empty($value))
				{
					$query_struct['where'][$key] = $value;
				}
			}
		}
		
        $total = $settlerealtimerptdtlDao -> count_items2($query_struct);
        $sum1list = $settlerealtimerptdtlDao -> sum_items($query_struct);
		$agentfromamtsum = $sum1list[0]['asum'];
		$clientretsum = $sum1list[0]['bsum'];
		if ($agentfromamtsum == 0){
	        $sum1list = $settlerealtimerptdtlDao -> sum_items($query_struct,0);
			$agentfromamtsum = $sum1list[0]['asum'];
			$clientretsum = $sum1list[0]['bsum'];
		}
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
//        $dataList = Mysettlerealtimerptdtl::instance()->lists($query_struct);
        $dataList = Mysettlerealtimerptdtl::instance()->mylists($query_struct);
		
		foreach($dataList as $key=>$value)
		{
			$dataList[$key]['urlbase'] = $this->getUrlBaseByTicketType($dataList[$key]['ticket_type']);
			if ($value['ticket_type'] != 0 && $value['ticket_type'] != 99){
				$dataList[$key]['ticket_type'] = $ticket_type[$value['ticket_type']];
			}else {
				$dataList[$key]['ticket_type'] = '';
			}
			
			foreach($value as $k=>$v)
			{
				if(!is_numeric($v) && empty($v))
				{
					$dataList[$key][$k] = '无';
				}
			}
		}
		$this->template->content = new View("distribution/settle_realtime_dtl_rpt");
		$this->template->content->data = $dataList;
		$this->template->content->pre_get = $this->input->get();
		$this->template->content->ticket_type = $ticket_type;
		$this->template->content->agentfromamtsum = $agentfromamtsum;
		$this->template->content->clientretsum = $clientretsum;
		$this->template->content->today = date("Y-m-d",time());
		$this->template->content->yesterday = date("Y-m-d",time()-24*3600);
	}
	public function getUrlBaseByTicketType($tickettype){
		$data['site_config'] = Kohana::config('site_config.site');
		$host = $_SERVER['HTTP_HOST'];
		$dis_site_config = Kohana::config('distribution_site_config');
		if (array_key_exists($host, $dis_site_config) == true && isset($dis_site_config[$host])) {
			$data['site_config']['site_title'] = $dis_site_config[$host]['site_name'];
			$data['site_config']['keywords'] = $dis_site_config[$host]['keywords'];
			$data['site_config']['description'] = $dis_site_config[$host]['description'];
		}
		switch ($tickettype) 
        {
            case 1:return "http://".$data['site_config']['name']."/jczq/viewdetail/";
            case 2:return "http://".$data['site_config']['name']."/zcsf/viewdetail/";
            case 6:return "http://".$data['site_config']['name']."/jclq/viewdetail/";
            case 7:return "http://".$data['site_config']['name']."/bjdc/viewdetail/";
        }
        return "http://".$data['site_config']['name']."/jczq/viewdetail/";
    }
    
	public function delete($user_id)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
	}
	public function add($userId)
	{
		//权限验证
		role::check('distribution_system_manage');
	}
	public function edit($agentId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
	}
}
?>