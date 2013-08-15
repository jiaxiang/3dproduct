<?php
class Settlerealtimerpt_Controller extends Template_Controller 
{
	public function __construct()
	{
		parent::__construct();
        role::check('Superplaner_system_manage');
	}
	public function index()
	{
		$settlerealtimerptDao = Superplaner_settlerealtimerpt::instance();
		$agent_type = Kohana::config('settle.agent_type');
		$isbeidan = Kohana::config('settle.isbeidan');
		
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
		$search_arr = array('type','agent_type','isbeidan', 'date_begin','date_end','user_id','lastname');
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
				elseif($key == 'isbeidan' && !empty($value))
				{
					$query_struct['where']["type"] = $value-1;
				}
				elseif($key == 'agent_type' && !empty($value))
				{
					$query_struct['where']["agent_type"] = $value-1;
				}
				elseif(!empty($value))
				{
					$query_struct['where'][$key] = $value;
				}
			}
		}
		
        $total = $settlerealtimerptDao -> count_itmes();
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
//        $dataList = Superplaner_settlerealtimerpt::instance()->lists($query_struct);
        $dataList = Superplaner_settlerealtimerpt::instance()->mylists($query_struct);
		
		foreach($dataList as $key=>$value)
		{
			$dataList[$key]['agent_type'] = $agent_type[$value['agent_type']];
			$dataList[$key]['type'] = $isbeidan[$value['type']];
			foreach($value as $k=>$v)
			{
				if(!is_numeric($v) && empty($v))
				{
					$dataList[$key][$k] = '无';
				}
			}
		}
		$this->template->content = new View("superplaner/settle_realtime_rpt");
		$this->template->content->data = $dataList;
		$this->template->content->agent_type = $agent_type;
		$this->template->content->isbeidan = $isbeidan;
		$this->template->content->today = date("Y-m-d",time());
		$this->template->content->yesterday = date("Y-m-d",time()-24*3600);
	}

	public function delete($user_id)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('Superplaner_system_manage');
	}
	public function add($userId)
	{
		//权限验证
		role::check('Superplaner_system_manage');
	}
	public function edit($agentId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('Superplaner_system_manage');
	}
}
?>