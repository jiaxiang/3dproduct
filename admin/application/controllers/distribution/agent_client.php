<?php
class Agent_client_Controller extends Template_Controller 
{
	private $userDao;
	private $agentDao;
	private $relationDao;
	
	public function __construct()
	{
		parent::__construct();
        role::check('distribution_system_manage');
        $this->userDao     = Myuser::instance();
        $this->agentDao    = Myagent::instance();
        $this->relationDao = Myrelation::instance();
	}
	
	public function index($userId) 
	{
		//权限验证
		role::check('distribution_system_manage');
		if(!$userId)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
		}
		$aUser  = $this->userDao->get_by_id($userId);
		$aAgent = $this->agentDao->get_by_user_id($userId);
		if ($aAgent == null) 
		{
			remind::set(Kohana::lang('o_agent.agent_not_exists'),request::referrer(),'error');
			return;
		}
		
		//排序
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
        $query_struct['where']['agentid'] = $aAgent['user_id'];
        
		//搜索
		$search_arr = array('users.lastname','users.real_name','users.email','users.mobile','users.ip');
		$searchBox =array(
			'search_key' => null,
			'search_value' => null
		);
		$searchBox['search_key'] = $this->input->get('search_key');
		$searchBox['search_value'] = $this->input->get('search_value');
		if(in_array($searchBox['search_key'], $search_arr))
		{
			if($searchBox['search_key'] == 'ip') {
				$query_struct['like'][$value] = tool::myip2long($value);
			}
			elseif(!empty($searchBox['search_value'])) {
//				$query_struct['where'][$key] = $value;
				$query_struct['like'][$searchBox['search_key']] = $searchBox['search_value'];
			}
		}
		
		$total = $this->relationDao-> count_agent_client($aAgent['user_id']);
		$this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
		$dataList = $this->relationDao->mylists($query_struct);
		
		$this->template->content = new View("distribution/agent_client_list");
		$this->template->content->theUser  = $aUser;
		$this->template->content->theAgent  = $aAgent;
		$this->template->content->searchBox = $searchBox;
		$this->template->content->dataList = $dataList;
	}
	
	public function add($agentId, $clientId)
	{
		//权限验证
		role::check('distribution_system_manage');
		if(!$agentId)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		if(!$clientId)
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		
		// check agentId & clientId
		$agentUser = $this->userDao->get_by_id($agentId);
		if ($agentUser == null) {
			remind::set(Kohana::lang('o_agent.agent_user_not_exist'),request::referrer(),'error');
			return;
		}
		$clientUser = $this->userDao->get_by_id($clientId);
		if ($clientUser == null) {
			remind::set(Kohana::lang('o_agent.client_not_exists'),request::referrer(),'error');
			return;
		}
		
		//check user has been client
		if($this->relationDao->user_has_been_client($clientId))
		{
			remind::set('该用户已经是某个代理的下级用户了',request::referrer(),'error');
			return;
		}
		
		// check if client is a agent
		$theAgent = $this->agentDao->get_by_user_id($clientId);
		
		$clientTypeArray = array();
		if ($theAgent != null && 
			($theAgent['agent_type'] == 1 || $theAgent['agent_type'] == 11)) 
		{
			remind::set('该用户是一级代理，不能成为其他人的下级',request::referrer(),'error');
			return;
		}
		if ($theAgent != null) {
			$clientTypeArray['特殊二级代理'] = 2;
			$clientTypeArray['二级代理'] = 12;
		}else {
			$clientTypeArray['普通下线'] = 0;
			$clientTypeArray['返利下线'] = 1;
		}
		
		if($_POST) 
		{
			$data = $_POST;
			$data['agentid'] = $_POST['agentId']; 
			$data['user_id'] = $_POST['clientId'];
			$data['flag'] = 2;
			$data['client_type'] = $_POST['clientType'];
			$data['client_rate'] = 0.00;
			$data['client_rate_beidan'] = 0.00;
			$data['date_add'] = date("Y-m-d H:i:s",time());
			$data['date_end'] = null;
			$data['adminid'] = 0;
			
			if($this->relationDao->add($data))
			{
				remind::set(Kohana::lang('o_global.add_success'),'distribution/agent_client/index/'.$agentUser['id'], 'success');
			}
			else 
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
			}
		}
		$this->template->content = new View("distribution/agent_client_add");
		$this->template->content->agent  = $agentUser;
		$this->template->content->client = $clientUser;
		$this->template->content->clientTypeArray = $clientTypeArray;
	}
	
	public function delete($agentId, $clientId)
	{
		role::check('distribution_system_manage');
		
		$relationDao = Myrelation::instance();
		$relation = $relationDao->get_by_agentid_userid($agentId, $clientId);
		if ($relation == null) 
		{
			remind::set(Kohana::lang('o_relation.relation_not_exists'),request::referrer(),'error');
		}
		
		if(Myrelation::instance($relation['id'])->delete())
		{
			remind::set(Kohana::lang('o_global.delete_success'),'distribution/agent_client/index/'.$relation['agentid'],'success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.delete_error'),'distribution/agent_client/index/'.$relation['agentid'],'error');
		}
	}
	
	public function edit($relationId)
	{
		role::check('distribution_system_manage');
		
		$relationDao = Myrelation::instance();
//		$agentDao = Myagent::instance();
		$userDao = Myuser::instance();
		
		$relation = $relationDao->get_by_id($relationId);
		if ($relation == null) 
		{
			remind::set(Kohana::lang('o_agent.agent_not_exists'),request::referrer(),'error');
		}
		$agent = $userDao->get_by_id($relation['agentid']);
		$client = $userDao->get_by_id($relation['user_id']);
		
		if($_POST) 
		{
			$client_rate = $_POST['client_rate'];
			$client_rate_beidan = $_POST['client_rate_beidan'];
			if ($client_rate < 0 || $client_rate > 0.1) 
			{
				remind::set('普通返点率超出范围。',request::referrer(),'error');
			}
			if ($client_rate_beidan < 0 || $client_rate_beidan > 0.1) 
			{
				remind::set('北单返点率超出范围。',request::referrer(),'error');
			}
			
			$rtCttDao = MyRealtime_contract::instance();
			$query_struct = array(
				'where'=>array(
					'user_id'=>$agent['id'],
					'flag'   => 2
            	)
			);
			$cttList = $rtCttDao->lists($query_struct);
			if (count($cttList) != 2)
			{
				remind::set('代理的实时合约不完备。',request::referrer(),'error');
				return;
			}
			
			foreach ($cttList as $contract) 
			{
				if ($contract['type'] == 0 && 
					$contract['flag'] == 2 && 
					$contract['rate'] < $client_rate)
				{
					remind::set('普通返点率超出代理返点率。',request::referrer(),'error');
					return;
				}
				else if ($contract['type'] == 7 &&
					$contract['flag'] == 2 && 
					$contract['rate'] < $client_rate_beidan)
				{
					remind::set('北单返点率超出代理返点率。',request::referrer(),'error');
					return;
				}
			}
			
            //标签过滤
            tool::filter_strip_tags($_POST);
            
			if(Myrelation::instance($relationId)->edit($_POST))
			{
				remind::set(Kohana::lang('o_global.update_success'),'distribution/agent_client/index/'.$relation['agentid'],'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			}
		}
		
		$this->template->content = new View("distribution/agent_client_edit");
		$this->template->content->agent = $agent;
		$this->template->content->client = $client;
		$this->template->content->relation = $relation;
	}
	
	//this is function for ajax
	public function set_client_rate()
	{
		//初始化返回数组
		$return_struct = array(
			'status'        => 0,
			'code'          => 501,
			'msg'           => 'Not Implemented',
			'content'       => array(),
		);
		$request_data = $this->input->get();
		$relationId = isset($request_data['relationId']) ?  $request_data['relationId'] : '';
		$client_rate = isset($request_data['client_rate']) ?  $request_data['client_rate'] : '';
		if(empty($relationId) || empty($client_rate))
		{
			$return_struct['msg'] = Kohana::lang('o_global.bad_request');
			exit(json_encode($return_struct));
		}
		if(!is_numeric($client_rate) || $client_rate<0 || $client_rate > 0.1)
		{
			$return_struct['msg'] = Kohana::lang('o_global.rate_rule');
			exit(json_encode($return_struct));
		}
		if(Myrelation::instance()->set_client_rate($relationId,$client_rate))
		{
			$return_struct = array(
				'status'        => 1,
				'code'          => 200,
				'msg'           => Kohana::lang('o_global.update_success'),
				'content'       => array('client_rate'=>$client_rate),
			);
		} else {
			$return_struct['msg'] = Kohana::lang('o_global.update_error');
		}
		exit(json_encode($return_struct));
	}
	
}
?>