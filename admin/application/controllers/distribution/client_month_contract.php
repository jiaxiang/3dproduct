<?php
class Client_month_contract_Controller extends Template_Controller 
{
	public function __construct()
	{
		parent::__construct();
        role::check('distribution_system_manage');
	}
	
	public function index() 
	{
		$relationId = null;
		$relation   = null;
		$client = null;
		$agent  = null;
        // set the query condition
		if ($_GET) 
		{
			$relationId = $_GET['relationId'];
		}
		if ($relationId == null || $relationId == '') 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
		}
		
		$relationDao = Myrelation::instance();
		$relation = $relationDao->get_by_id($relationId);
		if ($relation == null) 
		{
			remind::set(Kohana::lang('o_relation.relation_not_exists'),request::referrer(),'error');
		}
		
		$client = Myuser::instance() -> get_by_id($relation['user_id']);
		$agent  = Myuser::instance() -> get_by_id($relation['agentid']);
		
		$per_page = controller_tool::per_page();
        $orderby_arr = array
		(
			0   => array('id'=>'DESC'),
			1   => array('id'=>'ASC'),
			2   => array('order'=>'ASC'),
			3   => array('order'=>'DESC')
		);
        $orderby = controller_tool::orderby($orderby_arr);
        $query_struct = array(
            'where'   => array(),
            'orderby' => $orderby,
            'limit'   => array(
                                'per_page'  =>$per_page,
                                'offset'    =>0
                                )
        );
		$query_struct['where']['relation_id'] = $relationId;
		$query_struct['where']['contract_type'] = 1;
        
		$contractDao = MyMonth_contract::instance();
		$total = $contractDao -> count_contracts_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $contractList = $contractDao->lists($query_struct);
        
		$this->template->content = new View("distribution/client_month_contract_list");
		$this->template->content->contractList = $contractList;
		$this->template->content->relation = $relation;
		$this->template->content->agent  = $agent;
		$this->template->content->client = $client;
	}
	
	public function add($relationId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
		
		if ($relationId == null || $relationId == '') 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
		}
		
		$relationDao = Myrelation::instance();
		$theRelation = $relationDao->get_by_id($relationId);
		if ($theRelation == null) 
		{
			remind::set(Kohana::lang('o_relation.relation_not_exists'),request::referrer(),'error');
		}
		
		$client = Myuser::instance() -> get_by_id($theRelation['user_id']);
		$agent  = Myuser::instance() -> get_by_id($theRelation['agentid']);
		
		//确定 agent还可以给下级用户分配多少返点率
		$rate_ability_array = $this->getAgentRateAbility($agent['id']);
		if ($rate_ability_array == null) 
		{
			remind::set('请为上级代理添加月结合约',request::referrer(),'error');
		}
//		print_r($rate_ability_array);
		
		//初始化返点细则数组
		$contractDetailData = array();
		for ($index=1; $index<=10; $index++) 
		{
			$contractDetailData[$index] = array(
				'grade'   => $index,
				'minimum' => 0,
				'maximum' => 0,
				'rate'    => 0
			);
		}
		
//		print_r($contractDetailData);
		if($_POST) 
		{
			$data = $_POST;
			$data['relation_id']   = $_POST['relationId']; 
			$data['agent_id']      = $_POST['agentId']; 
			$data['user_id']       = $_POST['clientId']; 
			$data['contract_type'] = $_POST['contractType']; 
			$data['type']          = $_POST['type'];
			$data['rate_ability']  = $_POST['rateAbility'];
			$data['taxrate']       = 0.000;
			$data['flag']          = 0;
			$data['createtime']    = date("Y-m-d H:i:s",time());
			$data['starttime']     = date("Y-m-d H:i:s",time());
			$data['lastsettletime'] = date("Y-m-d H:i:s",time());
            
			for ($index=1; $index<=10; $index++) 
			{
				$contractDetailData[$index]['grade']   = $_POST['grade-'.$index];
				$contractDetailData[$index]['minimum'] = $_POST['minimum-'.$index];
				$contractDetailData[$index]['maximum'] = $_POST['maximum-'.$index];
				$contractDetailData[$index]['rate']    = $_POST['rate-'.$index];
			}
			
			$detailList = array();
			for ($index=1; $index<=10; $index++) 
			{
				if ($contractDetailData[$index]['minimum'] == null && 
					$contractDetailData[$index]['maximum'] == null && 
					$contractDetailData[$index]['rate'] == null) 
				{
					continue;
				}
            	if ($contractDetailData[$index]['minimum'] == null || 
            		$contractDetailData[$index]['maximum'] == null || 
            		$contractDetailData[$index]['rate'] == null) 
            	{
            		remind::set(Kohana::lang('o_contract.detail_not_completed'),request::referrer(),'error');
            		return;
            	}
				if (is_numeric($contractDetailData[$index]['minimum']) == false || 
            		is_numeric($contractDetailData[$index]['maximum']) == false || 
            		is_numeric($contractDetailData[$index]['rate']) == false)
            	{
            		remind::set('请在合约细则中输入数字','error',request::referrer());
            		return;
            	}
            	if ($contractDetailData[$index]['minimum'] >= $contractDetailData[$index]['maximum']) 
            	{
            		remind::set(Kohana::lang('o_contract.detail_invalid'),request::referrer(),'error');
            		return;
            	}
            	if (doubleval($contractDetailData[$index]['rate']) < 0) 
            	{
            		remind::set('返点率不能小于0',request::referrer(),'error');;
            		return;
            	}
            	if (doubleval($contractDetailData[$index]['rate']) > $data['rate_ability']) 
            	{
            		remind::set('超出代理的返点能力' ,request::referrer(),'error');
            		return;
            	}
            	if (isset($contractDetailData[$index-1]['maximum']))
            	{
	            	if ($contractDetailData[$index]['minimum'] != $contractDetailData[$index-1]['maximum'])
	            	{
	            		remind::set('销售额范围不连续',request::referrer(),'error');
	            		return;
	            	}
            	}
            	$detailList[] = $contractDetailData[$index];
            }
//            print_r($contractDetailData);
            
            
            //标签过滤
            tool::filter_strip_tags($data);
            
			$month_contractDao = MyMonth_contract::instance();
			if($contractId = $month_contractDao->add($data))
			{
				$month_contract_detailDao = MyMonth_contract_detail::instance();
				foreach ($detailList as $aContractDetail) 
				{
					$aContractDetail['contract_id'] = $contractId;
					$aContractDetail['createtime'] = date("Y-m-d H:i:s",time());
					$month_contract_detailDao->add($aContractDetail);
				}
				remind::set(Kohana::lang('o_global.add_success'),'distribution/client_month_contract?relationId='.$relationId, 'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
			}
		}
		
		$this->template->content = new View("distribution/client_month_contract_add");
		$this->template->content->relation = $theRelation;
		$this->template->content->agent = $agent;
		$this->template->content->client = $client;
		$this->template->content->rate_ability_array = $rate_ability_array;
		$this->template->content->contractDetailData = $contractDetailData;
	}
	
	public function detail($contractId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
		
		$contractDao = MyMonth_contract::instance();
		$contract = $contractDao->get_by_id($contractId);
		if ($contract == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'),request::referrer(),'error');
		}
		$agent  = Myuser::instance()->get_by_id($contract['agent_id']);
		$client = Myuser::instance()->get_by_id($contract['user_id']);
		
		$cttDetailDao = MyMonth_contract_detail::instance();
		$detailSearchStruct = array();
		$detailSearchStruct['where'] = array(
			'contract_id' => $contractId
		);
		$contactDetailList = $cttDetailDao->lists($detailSearchStruct);
		for ($i=0; $i<count($contactDetailList); $i++) 
		{
			$contactDetailList[$i]['index'] = $i;
		}
		
		$this->template->content = new View("distribution/client_month_contract_detail");
		$this->template->content->contract = $contract;
		$this->template->content->agent    = $agent;
		$this->template->content->client   = $client;
		$this->template->content->data = $contactDetailList;
	}
	
	public function delete($contractId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
		
		$contractDao = MyMonth_contract::instance();
		$contract = $contractDao->get_by_id($contractId);
		if ($contract == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'),request::referrer(),'error');
		}
		if ($contract['flag'] == 2) {
			remind::set(Kohana::lang('o_contract.cannot_delete_inuse_contract'),request::referrer(),'error');
		}
		
		$contractDetailDao = MyMonth_contract_detail::instance();
		$detailSearchStruct = array();
		$detailSearchStruct['where'] = array(
			'contract_id' => $contractId
		);
		$contactDetailList = $contractDetailDao->lists($detailSearchStruct);
		foreach ($contactDetailList as $aContractDetail) 
		{
			MyMonth_contract_detail::instance($aContractDetail['id'])->delete();
		}
		
		if(MyMonth_contract::instance($contractId)->delete())
		{
			remind::set(Kohana::lang('o_global.delete_success'),'distribution/client_month_contract?relationId='.$contract['relation_id'],'success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.delete_error'),'distribution/client_month_contract?relationId='.$contract['relation_id'],'error');
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
		$contractDao = MyMonth_contract::instance();
		$aContract = $contractDao->get_by_id($contractId);
		if ($aContract == null) {
			remind::set(Kohana::lang('o_contract.contract_not_exists'), request::referrer(), 'error');
		}
		
		//检查月结合约 （类型与当前合约一致）
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
		
		//检查月结合约细则
		$cttDtlDao = MyMonth_contract_detail::instance();
		$dtlSearchStruct = array();
		$dtlSearchStruct['where'] = array(
			'contract_id' => $aContract['id']
		);
		$cttDtlList = $cttDtlDao->lists($dtlSearchStruct);
		if (count($cttDtlList) == 0)
		{
			remind::set(Kohana::lang('o_agent.contract_detail_missing'), request::referrer(), 'error');
		}
		
		$aContract['flag'] = 2;
		if ($contractDao->edit($aContract))
		{
			remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
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
		$contractDao = MyMonth_contract::instance();
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
			remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
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
		$contractDao = MyMonth_contract::instance();
		$contractList = $contractDao->lists($query_struct);
		
		$contractDetailDao = MyMonth_contract_detail::instance();
		
		$return = array();
		foreach ($contractList as $aContract)
		{
			$lowestCttDtl = $contractDetailDao->get_lowest_grade_by_id($aContract['id']);
			if ($lowestCttDtl == false || $lowestCttDtl == null){
				$return[$aContract['type']] = 0;
			}else if ($lowestCttDtl['minimum'] != 0){
				$return[$aContract['type']] = 0;
			}else {
				$return[$aContract['type']] = $lowestCttDtl['rate'];
			}
		}
		
		return $return;
	}
	
	public function template_reference($userId)
	{
		$templateDao = MyMonth_contract_template::instance();
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
		
		$this->template->content = new View("distribution/month_template_reference");
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
		
		$mtTemplateDao = MyMonth_contract_template::instance();
		$template = $mtTemplateDao->get_by_id($templateId);
		if ($template == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'),request::referrer(),'error');;
		}
		
		$mtDtlTemplateDao = MyMonth_contract_detail_template::instance();
		$searchStruct = array();
		$searchStruct['where'] = array(
			'contract_id' => $templateId
		);
		$dtlTemplateList = $mtDtlTemplateDao->lists($searchStruct);
		
//		tool::filter_strip_tags($data);
            
		$mtContractDao = MyMonth_contract::instance();
		$mtContractDtlDao = MyMonth_contract_detail::instance();
		
		$contract = array();
		$contract['user_id'] = $agent['user_id'];
		$contract['flag']    = 0;
		$contract['type']    = $template['type'];
		$contract['taxrate'] = $template['taxrate'];
		$contract['createtime'] = date("Y-m-d H:i:s",time());
		$contract['starttime']  = date("Y-m-d H:i:s",time());
		$contract['lastsettletime'] = date("Y-m-d H:i:s",time());
		$contract['note']    = null;
		
		if($contractId = $mtContractDao->add($contract))
		{
			foreach ($dtlTemplateList as $aDtlTemplate) 
			{
				$contractDtl = array();
				$contractDtl['contract_id'] = $contractId;
				$contractDtl['createtime'] = date("Y-m-d H:i:s",time());
				$contractDtl['minimum'] = $aDtlTemplate['minimum'];
				$contractDtl['maximum'] = $aDtlTemplate['maximum'];
				$contractDtl['rate']    = $aDtlTemplate['rate'];
				$mtContractDtlDao->add($contractDtl);
			}
			remind::set(Kohana::lang('o_global.add_success'),'distribution/client_month_contract/index/'.$userId,'success');
		}
		else
		{
			remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
		}
		
	}
}
?>