<?php
class Bill_Controller extends Template_Controller 
{
	private $salesChannelDao = null;
	private $issueBillDao    = null;
	private $issueBillDtlDao = null;
	private $openBillDao     = null;
	private $openBillDtlDao  = null;
	
	private $salesChanenlList = null;
	private $salesChannelMap = null;
	
	public function __construct()
	{
		parent::__construct();
		role::check('card_system_manage');
		$this->salesChannelDao = MySalesChannel_Core::instance();
		$this->issueBillDao    = MyIssueBill_Core::instance();
		$this->issueBillDtlDao = MyIssueBillDtl_Core::instance();
		$this->openBillDao     = MyOpenBill_Core::instance();
		$this->issueBillDtlDao = MyOpenBillDtl_Core::instance();
		
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
		);
		$query_struct['where']['flag'] = 2;
		
		$this->salesChanenlList = $this->salesChannelDao->lists($query_struct);
		
		$this->salesChannelMap = array();
		foreach ($this->salesChanenlList as $aChannel) 
		{
			$this->salesChannelMap[$aChannel['id']] = $aChannel;
		}
	}
	
	public function issue_bill() 
	{
		role::check('card_system_manage');
		
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
        
		$total = $this->issueBillDao->count_items_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $issueBillList = $this->issueBillDao->lists($query_struct);
        
		$this->template->content = new View("card/issue_bill_list");
		$this->template->content->data = $issueBillList;
		$this->template->content->channelList = $this->salesChannelMap;
	}
	
	public function open_bill() 
	{
		role::check('card_system_manage');
		
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
        
		$total = $this->openBillDao->count_items_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $openBillList = $this->openBillDao->lists($query_struct);
        
		$this->template->content = new View("card/open_bill_list");
		$this->template->content->data = $openBillList;
		$this->template->content->channelList = $this->salesChannelMap;
	}
	
	public function issue_bill_detail($issueBillId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if ($issueBillId == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		$issueBill = $this->issueBillDao->get_by_id($issueBillId);
		if ($issueBill == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		
		$this->template->content = new View("card/issue_bill_detail");
		$this->template->content->issueBill = $issueBill;
		$this->template->content->channelList = $this->salesChannelMap;
	}

	public function open_bill_detail($openBillId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if ($openBillId == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		$openBill = $this->issueBillDao->get_by_id($openBillId);
		if ($openBill == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		
		$this->template->content = new View("card/open_bill_detail");
		$this->template->content->openBill = $openBill;
		$this->template->content->channelList = $this->salesChannelMap;
	}
	
}
?>