<?php
class Realtime_contract_Controller extends Template_Controller 
{
	private $userDao;
	private $contractDao;
	private $relationDao;
	
	public function __construct()
	{
		parent::__construct();
        role::check('distribution_system_manage');
        $this->userDao     = Myuser::instance();
        $this->contractDao = MyRealtime_contract::instance();
        $this->relationDao = Myrelation::instance();
	}
	
	public function index($userId) 
	{
		$user = $this->userDao->get_by_id($userId);
		
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
        		'user_id' => $userId,
        		'contract_type' => 0 
            ),
            'orderby'       => $orderby,
            'limit'         => array(
                                'per_page'  =>$per_page,
                                'offset'    =>0
                                )
        );
        $total = $this->contractDao->count_contracts_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $contractList = $this->contractDao->lists($query_struct);
		
		$this->template->content = new View("distribution/realtime_contract_list");
		$this->template->content->data = $contractList;
		$this->template->content->user = $user;
	}
	
	public function add($userId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
		
		$client = $this->userDao->get_by_id($userId);
		
		$agent = null;
		$rate_ability_array = null;
		$relation = $this->relationDao->get_by_userid($userId);
		// 如果这个用户是一个下级用户
		if ($relation != null) 
		{	
			// 获取上级代理
			$agent = $this->userDao->get_by_id($relation['agentid']);
			if ($agent == null) {
				remind::set('请检查上级代理是否存在',request::referrer(),'error');
			}
			
			// 确定 agent还可以给下级用户分配多少返点率
			$rate_ability_array = $this->getAgentRateAbility($agent['id']);
			if ($rate_ability_array == null) 
			{
				remind::set('请为上级代理添加实时合约',request::referrer(),'error');
			}
		}
		
//		print_r($_POST);
		if($_POST) 
		{
            $data = $_POST;
            if (isset($_POST['relationId']) ){
	            $data['relation_id'] = $_POST['relationId']; 
            }
            if (isset($_POST['agentId']) ){
	            $data['agent_id']    = $_POST['agentId']; 
            }
            $data['user_id']        = $_POST['userId']; 
            $data['type']           = $_POST['type']; 
            $data['contract_type']  = $_POST['contract_type']; 
            $data['taxrate']        = 0;
            $data['flag']           = 0;
            $data['createtime']     = date("Y-m-d H:i:s",time());
            $data['starttime']      = date("Y-m-d H:i:s",time());
            $data['lastsettletime'] = date("Y-m-d H:i:s",time());
            
            //标签过滤
            tool::filter_strip_tags($data);
			      
			if($this->contractDao->add($data))
			{
				remind::set(Kohana::lang('o_global.add_success'),'distribution/realtime_contract/index/'.$userId,'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
			}
		}
		$this->template->content = new View("distribution/realtime_contract_add");
		$this->template->content->client   = $client;
		if ($agent != null) 
		{
			$this->template->content->agent    = $agent;
			$this->template->content->relation = $relation;
			$this->template->content->rate_ability_array = $rate_ability_array;
		}
	}
	
	public function edit($contractId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
		
		$contract = $this->contractDao->get_by_id($contractId);
		if ($contract == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'),request::referrer(),'error');
		}
		$user = $this->userDao->get_by_id($contract['user_id']);
		
		if($_POST) 
		{
            //标签过滤
            tool::filter_strip_tags($_POST);
			if(MyRealtime_contract::instance($contractId)->edit($_POST))
			{
				remind::set(Kohana::lang('o_global.update_success'),'distribution/realtime_contract/index/'.$user['id'],'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			}
		}
		
		$this->template->content = new View("distribution/realtime_contract_edit");
		$this->template->content->contract = $contract;
		$this->template->content->user     = $user;
	}
	
	public function delete($contractId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
		$contract = $this->contractDao->get_by_id($contractId);
		if ($contract == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'),request::referrer(),'error');
		}
		if ($contract['flag'] == 2) {
			remind::set(Kohana::lang('o_contract.cannot_delete_inuse_contract'),request::referrer(),'error');
		}
		
		if($this->contractDao->delete($contractId))
		{
			remind::set(Kohana::lang('o_global.delete_success'),'distribution/realtime_contract/index/'.$contract['user_id'],'success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.delete_error'),'distribution/realtime_contract/index/'.$contract['user_id'],'error');
		}
	}
	
	public function open($contractId)
	{
		//权限验证
		role::check('distribution_system_manage');
		if(!$contractId)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
		}
		$aContract = $this->contractDao->get_by_id($contractId);
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
		$contractList = $this->contractDao->lists($searchStruct);
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
		$relationList = $this->relationDao->lists($relationSearchCondition);
		
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
		if ($this->contractDao->edit($aContract))
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
		role::check('distribution_system_manage');
		if(!$contractId)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
		}
		$aContract = $this->contractDao->get_by_id($contractId);
		if ($aContract == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'), request::referrer(), 'error');
		}
		
		$aContract['flag'] = 0;
		if ($this->contractDao->edit($aContract))
		{
			remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.update_fail'),request::referrer(),'error');
		}
	}
	
	/**
	 * get the agent's rate return ability.
	 * Return the MAX rate which the agent can give to his client. 
	 * @param int $agentId
	 * @return 
	 * array() => 
	 * {
	 * 		0 => 0.02	//普通返点率
	 * 		7 => 0.03	//北单返点率
	 * }
	 */
	public function getAgentRateAbility($agentId)
	{
		$query_struct = array(
			'where' => array()
		);
		$query_struct['where']['user_id'] = $agentId;
		$query_struct['where']['flag'] = 2;
		$contractDao = MyRealtime_contract::instance();
		$contractList = $contractDao->lists($query_struct);
		
		$return = array();
		foreach ($contractList as $aContract)
		{
			$return[$aContract['type']] = $aContract['rate'];
		}
		return $return;
	}
	
	
	public function template_reference($userId)
	{
		$templateDao = MyRealtime_contract_template::instance();
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
		
		$this->template->content = new View("distribution/realtime_template_reference");
		$this->template->content->data = $contractList;
		$this->template->content->userId = $userId;
	}
	
	public function template_use($userId, $templateId)
	{
		$agentDao = Myagent::instance();
		$agent = $agentDao->get_by_user_id($userId);
		if ($agent == null) 
		{
			remind::set(Kohana::lang('o_agent.agent_not_exists'),request::referrer(),'error');;
		}
		
		$rtTemplateDao = MyRealtime_contract_template::instance();
		$template = $rtTemplateDao->get_by_id($templateId);
		if ($template == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'),request::referrer(),'error');;
		}
		
		$contract = array();
		$contract['relation_id']    = 0;
		$contract['agent_id']       = 0;
		$contract['user_id']        = $agent['user_id'];
		$contract['contract_type']  = 0;
		$contract['type']           = $template['type'];
		$contract['rate']           = $template['rate'];
		$contract['taxrate']        = 0;
		$contract['createtime']     = date("Y-m-d H:i:s",time());
		$contract['starttime']      = date("Y-m-d H:i:s",time());
		$contract['lastsettletime'] = date("Y-m-d H:i:s",time());
		$contract['flag']           = 0;	//新建的都是关闭状态
		$contract['note']           = null;
		
		$rtContractDao = MyRealtime_contract::instance();
		if($rtContractDao->add($contract))
		{
			remind::set(Kohana::lang('o_global.add_success'),'distribution/realtime_contract/index/'.$userId,'success');
		}
		else
		{
			remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
		}
	}
}
?>