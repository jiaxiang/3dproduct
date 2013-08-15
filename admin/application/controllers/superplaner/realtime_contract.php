<?php
class Realtime_contract_Controller extends Template_Controller 
{
	public function __construct()
	{
		parent::__construct();
        role::check('superplaner_system_manage');
	}
	
	public function index($userId) 
	{
		$user = Myuser::instance()->get_by_id($userId);
		
		$contractDao = Superplaner_Realtime_contract::instance();
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
        		'user_id' => $userId
            ),
            'orderby'       => $orderby,
            'limit'         => array(
                                'per_page'  =>$per_page,
                                'offset'    =>0
                                )
        );
        $total = $contractDao -> count_contracts_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $contractList = Superplaner_Realtime_contract::instance()->lists($query_struct);
		
		$this->template->content = new View("superplaner/realtime_contract_list");
		$this->template->content->data = $contractList;
		$this->template->content->user = $user;
	}
	
	public function add($userId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('superplaner_system_manage');
		
		$user = Myuser::instance()->get_by_id($userId);
		
//		print_r($_POST);
		if($_POST) 
		{
            $data = $_POST;
            $data['type'] = $_POST['contract_type']; 
            $data['createtime'] = date("Y-m-d H:i:s",time());
            $data['lastsettletime'] = date("Y-m-d H:i:s",time());
            
            //标签过滤
            tool::filter_strip_tags($data);
            
			$realtime_contractDao = Superplaner_Realtime_contract::instance();
//			if($realtime_contractDao->contract_exist($data))
//			{
//				remind::set(Kohana::lang('o_contract.contract_has_exist'),request::referrer(),'error');
//			}
			if($realtime_contractDao->add($data))
			{
				remind::set(Kohana::lang('o_global.add_success'),'superplaner/realtime_contract/index/'.$userId,'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
			}
		}
		$this->template->content = new View("superplaner/realtime_contract_add");
		$this->template->content->user = $user;
	}
	
	public function edit($contractId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('superplaner_system_manage');
		
		$contractDao = Superplaner_Realtime_contract::instance();
		$contract = $contractDao->get_by_id($contractId);
		if ($contract == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'),request::referrer(),'error');
		}
		$user = Myuser::instance()->get_by_id($contract['user_id']);
		
		if($_POST) 
		{
            //标签过滤
            tool::filter_strip_tags($_POST);
			if(Superplaner_Realtime_contract::instance($contractId)->edit($_POST))
			{
				remind::set(Kohana::lang('o_global.update_success'),'superplaner/realtime_contract/index/'.$user['id'],'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			}
		}
		
		$this->template->content = new View("superplaner/realtime_contract_edit");
		$this->template->content->contract = $contract;
		$this->template->content->user     = $user;
	}
	
	public function delete($contractId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('superplaner_system_manage');
		$contractDao = Superplaner_Realtime_contract::instance();
		$contract = $contractDao->get_by_id($contractId);
		if ($contract == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'),request::referrer(),'error');
		}
		if ($contract['flag'] == 2) {
			remind::set(Kohana::lang('o_contract.cannot_delete_inuse_contract'),request::referrer(),'error');
		}
		
		if(Superplaner_Realtime_contract::instance($contractId)->delete())
		{
			remind::set(Kohana::lang('o_global.delete_success'),'superplaner/realtime_contract/index/'.$contract['user_id'],'success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.delete_error'),'superplaner/realtime_contract/index/'.$contract['user_id'],'error');
		}
		
	}
	
	public function open($contractId)
	{
		//权限验证
		role::check('superplaner_system_manage');
		if(!$contractId)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
		}
		$contractDao = Superplaner_Realtime_contract::instance();
		$aContract = $contractDao->get_by_id($contractId);
		if ($aContract == null) {
			remind::set(Kohana::lang('o_contract.contract_not_exists'), request::referrer(), 'error');
		}
		
		//检查实时合约 （类型与当前合约一致）
		$searchStruct = array();
		$searchStruct['where'] = array(
			'user_id' => $aContract['user_id'],
			'type'    => $aContract['type'],
			'flag'    => 2
		);
		$contractList = $contractDao->lists($searchStruct);
		if ( count($contractList) > 0 ) {
			remind::set(Kohana::lang('o_contract.contract_has_exists'), request::referrer(), 'error');
		}
		
		//检查是否有下级用户需要返点
		$relationSearchCondition = array();
		$relationSearchCondition['where'] = array(
			'agentid'     => $aContract['user_id'],
			'client_type' => 1,
			'flag'        => 2
		);
		$relationDao = Myrelation::instance();
		$relationList = $relationDao->lists($relationSearchCondition);
		
		// 如果是普通实时合约，则比对下级用户的client_rate
		if ($aContract['type'] == 0)
		{
			foreach ($relationList as $aRelation) 
			{
				if ($aContract['rate'] <= $aRelation['client_rate']) 
				{
					remind::set('下级用户返点率(普通)高于该合约的返点率', request::referrer(), 'error');
				}
			}
		}
		// 如果是北单实时合约，则比对下级用户的client_rate_beidan
		else if ($aContract['type'] == 7) 
		{
			foreach ($relationList as $aRelation) 
			{
				if ($aContract['rate'] <= $aRelation['client_rate']) 
				{
					remind::set('下级用户返点率(北单)高于该合约的返点率', request::referrer(), 'error');
				}
			}
		}
		
		$aContract['flag'] = 2;
		if ($contractDao->edit($aContract))
		{
			remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.update_fail'),request::referrer(),'error');
		}
	}
	
	public function close($contractId)
	{
		//权限验证
		role::check('superplaner_system_manage');
		if(!$contractId)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
		}
		$contractDao = Superplaner_Realtime_contract::instance();
		$aContract = $contractDao->get_by_id($contractId);
		if ($aContract == null) {
			remind::set(Kohana::lang('o_contract.contract_not_exists'), request::referrer(), 'error');
		}
		
		$aContract['flag'] = 0;
		if ($contractDao->edit($aContract))
		{
			remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.update_fail'),request::referrer(),'error');
		}
	}
	
	public function template_reference($userId)
	{
		$templateDao = Superplaner_Realtime_contract_template::instance();
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
            'where'=>array(),
            'orderby'       => $orderby,
            'limit'         => array(
                                'per_page'  =>$per_page,
                                'offset'    =>0
                                )
        );
        $total = $templateDao -> count_templates();
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $contractList = $templateDao->lists($query_struct);
		
		$this->template->content = new View("superplaner/realtime_template_reference");
		$this->template->content->data = $contractList;
		$this->template->content->userId = $userId;
	}
	
	public function template_use($userId, $templateId)
	{
		$agentDao = Superplaner::instance();
		$agent = $agentDao->get_by_user_id($userId);
		if ($agent == null) 
		{
			remind::set(Kohana::lang('o_agent.agent_not_exists'),request::referrer(),'error');;
		}
		
		$rtTemplateDao = Superplaner_Realtime_contract_template::instance();
		$template = $rtTemplateDao->get_by_id($templateId);
		if ($template == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'),request::referrer(),'error');;
		}
		
		$contract = array();
		$contract['user_id'] = $agent['user_id'];
		$contract['type']    = $template['type'];
		$contract['rate']    = $template['rate'];
		$contract['taxrate']    = $template['taxrate'];
		$contract['createtime'] = date("Y-m-d H:i:s",time());
		$contract['starttime']  = date("Y-m-d H:i:s",time());
		$contract['lastsettletime'] = date("Y-m-d H:i:s",time());
		$contract['flag'] = 0;	//新建的都是关闭状态
		$contract['note'] = null;
		
		$rtContractDao = Superplaner_Realtime_contract::instance();
		if($rtContractDao->add($contract))
		{
			remind::set(Kohana::lang('o_global.add_success'),'superplaner/realtime_contract/index/'.$userId,'success');
		}
		else
		{
			remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
		}
	}
	
}
?>