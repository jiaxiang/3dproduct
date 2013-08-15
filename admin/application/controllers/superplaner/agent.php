<?php
class Agent_Controller extends Template_Controller 
{
	public function __construct()
	{
		parent::__construct();
        role::check('superplaner_system_manage');
	}
	
	public function index() 
	{
		$agentDao = Superplaner::instance();
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
        $total = $agentDao -> count_agents();
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $agentList = $agentDao->lists($query_struct);
		
		$this->template->content = new View("superplaner/agent_list");
		$this->template->content->data = $agentList;
	}
	
	public function add($userId)
	{
		//权限验证
		role::check('superplaner_system_manage');
		if(!$userId)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
		}
		$userDao = Myuser::instance();
		$aUser = $userDao->get_by_id($userId);
		if ($aUser == null) {
			remind::set(Kohana::lang('o_agent.agent_user_not_exist'),request::referrer(),'error');
		}
		
		$agentDao = Superplaner::instance();
		$aAgent = array();
		$aAgent['user_id']  = $aUser['id'];
		$aAgent['mail']     = $aUser['email']; 
		$aAgent['lastname'] = $aUser['lastname'];
		$aAgent['realname'] = $aUser['real_name'];
		$aAgent['mobile']   = $aUser['mobile'];
		$aAgent['tel']      = $aUser['tel'];
		$aAgent['createtime'] = date("Y-m-d H:i:s",time());
		$aAgent['starttime']  = date("Y-m-d H:i:s",time());
		$aAgent['flag']       = 0;
		$aAgent['type']       = 0;
		$aAgent['up_agent_id']= 0;
		$aAgent['note']       = null;
		$aAgent['invite_code']= null;
		do {
			$aAgent['invite_code'] = $this->create_invite_code();
			$duplicate = $agentDao->get_by_invite_code($aAgent['invite_code']);
		}while ($duplicate != null);
		
		if($agentDao->agent_exist($aAgent))
		{
			remind::set(Kohana::lang('o_agent.agent_has_exist'),request::referrer(),'error');
		}
		if($agentDao->add($aAgent))
		{
			remind::set(Kohana::lang('o_global.add_success'),'superplaner/agent', 'success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.add_fail'),request::referrer(),'error');
		}
	}
	
	public function edit($agentId)
	{
		
		//权限检查 得到所有可管理站点ID列表
		role::check('superplaner_system_manage');
		$agentDao = Superplaner::instance();
		$aAgent = $agentDao->get_by_id($agentId);
		if ($aAgent == null) 
		{
			remind::set(Kohana::lang('o_agent.agent_not_exists'),request::referrer(),'error');
		}
		if($_POST) {
            //标签过滤
            tool::filter_strip_tags($_POST);
			if(Superplaner::instance($agentId)->edit($_POST))
			{
				remind::set(Kohana::lang('o_global.update_success'),'superplaner/agent','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			}
		}
		
		$this->template->content = new View("superplaner/agent_edit");
		$this->template->content->agent = $aAgent;
	}
	
	public function delete($agentId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('superplaner_system_manage');
		$agentDao = Superplaner::instance();
		$aAgent = $agentDao->get_by_id($agentId);
		if ($aAgent == null) {
			remind::set(Kohana::lang('o_agent.agent_not_exists'),request::referrer(),'error');
		}
		
	}
	
	public function open($agentId)
	{
		//权限验证
		role::check('superplaner_system_manage');
		if(!$agentId)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
		}
		$agentDao = Superplaner::instance();
		$aAgent = $agentDao->get_by_id($agentId);
		if ($aAgent == null) {
			remind::set(Kohana::lang('o_agent.agent_not_exists'), request::referrer(), 'error');
		}
		
		$realCttDao = Superplaner_Realtime_contract::instance();
		$searchStruct = array();
		//检查实时合约 （普通）
		$searchStruct['where'] = array(
			'user_id' => $aAgent['user_id'],
			'type'    => 0,
			'flag'    => 2
		);
		$realCttList = $realCttDao->lists($searchStruct);
//		d($realCttList);
		if (count($realCttList) == 0) {
			remind::set(Kohana::lang('o_contract.normal_realtime_contract_missing'), request::referrer(), 'error');
		}
		if (count($realCttList) > 1) {
			remind::set(Kohana::lang('o_contract.too_many_normal_realtime_contract'), request::referrer(), 'error');
		}
		
		//检查实时合约 （北单）
		/* $searchStruct['where'] = array(
			'user_id' => $aAgent['user_id'],
			'type'    => 7,
			'flag'    => 2
		);
		$realCttList = $realCttDao->lists($searchStruct);
		if (count($realCttList) == 0) {
			remind::set(Kohana::lang('o_contract.BEIDAN_realtime_contract_missing'), request::referrer(), 'error');
		}
		if (count($realCttList) > 1) {
			remind::set(Kohana::lang('o_contract.too_many_BEIDAN_realtime_contract'), request::referrer(), 'error');
		} */
		
		//检查月结合约 （普通）
		/* $monthCttDao = MyMonth_contract::instance();
		$searchStruct['where'] = array(
			'user_id' => $aAgent['user_id'],
			'type'    => 0,
			'flag'    => 2
		);
		$monthCttList = $monthCttDao->lists($searchStruct);
		if (count($monthCttList) == 0) {
			remind::set(Kohana::lang('o_contract.normal_month_contract_missing'), request::referrer(), 'error');
		}
		if (count($monthCttList) > 1) {
			remind::set(Kohana::lang('o_contract.too_many_normal_month_contract'), request::referrer(), 'error');
		} */
		
		//检查月结合约 （北单）
		/* $searchStruct['where'] = array(
			'user_id' => $aAgent['user_id'],
			'type'    => 7,
			'flag'    => 2
		);
		$realCttList = $monthCttDao->lists($searchStruct);
		if (count($realCttList) == 0) {
			remind::set(Kohana::lang('o_contract.BEIDAN_month_contract_missing'), request::referrer(), 'error');
		}
		if (count($realCttList) > 1) {
			remind::set(Kohana::lang('o_contract.too_many_BEIDAN_month_contract'), request::referrer(), 'error');
		} */
		
		//通过有效性检查，开启代理用户
		$aAgent['flag'] = 2;
		if ($agentDao->edit($aAgent))
		{
			remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.update_fail'),request::referrer(),'error');
		}
	}
	
	public function close($agentId)
	{
		//权限验证
		role::check('superplaner_system_manage');
		if(!$agentId)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
		}
		$agentDao = Superplaner::instance();
		$aAgent = $agentDao->get_by_id($agentId);
		if ($aAgent == null) {
			remind::set(Kohana::lang('o_agent.agent_not_exists'), request::referrer(), 'error');
		}
		
		$aAgent['flag'] = 0;
		if ($agentDao->edit($aAgent))
		{
			remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.update_fail'),request::referrer(),'error');
		}
	}
	
	public function create_invite_code()
	{
		$invite_code = '';
		$randInt = 0;
		
		for($index=0; $index<5; $index++)
		{
			$randInt = rand(0, 25);
			switch ($randInt)
			{
				case  0: $invite_code.='a'; break;
				case  1: $invite_code.='b'; break;
				case  2: $invite_code.='c'; break;
				case  3: $invite_code.='d'; break;
				case  4: $invite_code.='e'; break;
				case  5: $invite_code.='f'; break;
				case  6: $invite_code.='g'; break;
				case  7: $invite_code.='h'; break;
				case  8: $invite_code.='i'; break;
				case  9: $invite_code.='j'; break;
				case 10: $invite_code.='k'; break;
				case 11: $invite_code.='l'; break;
				case 12: $invite_code.='m'; break;
				case 13: $invite_code.='n'; break;
				case 14: $invite_code.='o'; break;
				case 15: $invite_code.='p'; break;
				case 16: $invite_code.='q'; break;
				case 17: $invite_code.='r'; break;
				case 18: $invite_code.='s'; break;
				case 19: $invite_code.='t'; break;
				case 20: $invite_code.='u'; break;
				case 21: $invite_code.='v'; break;
				case 22: $invite_code.='w'; break;
				case 23: $invite_code.='x'; break;
				case 24: $invite_code.='y'; break;
				case 25: $invite_code.='z'; break;
			}
		}
		
		
		return $invite_code;
	}
}
?>