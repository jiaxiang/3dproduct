<?php

class Sales_channel_Controller extends Template_Controller 
{
	private $salesChannelDao    = null;
	private $cardIssueSerialDao = null;
	private $cardLogDao         = null;
	
	public function __construct()
	{
		parent::__construct();
        role::check('card_system_manage');
        $this->salesChannelDao    = MySalesChannel_Core::instance();
        $this->cardIssueSerialDao = MyCardIssueSerial_Core::instance();
        $this->cardLogDao         = MyCardLog_Core::instance();
	}
	
	public function index() 
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
        
		$total = $this->salesChannelDao->count_items_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $cardSerialList = $this->salesChannelDao->lists($query_struct);
        
		$this->template->content = new View("card/sales_channel_list");
		$this->template->content->data = $cardSerialList;
	}
	
	public function add()
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if($_POST) 
		{
			$data = $_POST;
			$data['code']        = $_POST['code']; 
			$data['name']        = $_POST['name']; 
			$data['flag']        = 2;
			$data['apdtime']     = date("Y-m-d H:i:s",time());
			$data['updtime']     = date("Y-m-d H:i:s",time());
			
            //标签过滤
            tool::filter_strip_tags($data);
            
			if($salesChannelId = $this->salesChannelDao->add($data))
			{
				$aLog = array();
				$aLog['userid']   = $this->manager['id'];
				$aLog['apdtime']  = date('Y-m-d H:i:s', time());
				$aLog['target']   = Ac_cardlog_Model::TARGET_SALE_CHANNEL;
				$aLog['targetid'] = $salesChannelId;
				$aLog['action']   = Ac_cardlog_Model::ACTION_CREATE;
				$aLog['detail']   = 'insert new saleChannel: '.$salesChannelId;
				$this->cardLogDao->add($aLog);
				
				remind::set(Kohana::lang('o_global.add_success'),'card/sales_channel', 'success');
				return;
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
				return;
			}
		}
		
		$channelCode = 'CHL-'.date("ymd-His",time());
		$this->template->content = new View("card/sales_channel_add");
		$this->template->content->channelCode = $channelCode;
	}
	
	public function edit($salesChannelId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if ($salesChannelId == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		$salesChannel = $this->salesChannelDao->get_by_id($salesChannelId);
		if ($salesChannel == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		
		if($_POST) 
		{
			$data = $_POST;
			$data['id']      = $salesChannel['id'];
			$data['updtime'] = date("Y-m-d H:i:s",time());
			$data['des']     = $_POST['des'];
			$data['flag']    = $_POST['flag'];
			
            //标签过滤
            tool::filter_strip_tags($data);
            
			if($this->salesChannelDao->edit($data))
			{
				$aLog = array();
				$aLog['userid']   = $this->manager['id'];
				$aLog['apdtime']  = date('Y-m-d H:i:s', time());
				$aLog['target']   = Ac_cardlog_Model::TARGET_SALE_CHANNEL;
				$aLog['targetid'] = $salesChannelId;
				$aLog['action']   = Ac_cardlog_Model::ACTION_CHANGE;
				$updateString = '';
				foreach ($_POST as $key => $value)
				{
					$updateString = $updateString.$key.'='.$value.',';
				}
				$aLog['detail']   = 'update saleChannel. '.$updateString;
				$this->cardLogDao->add($aLog);
				
				remind::set(Kohana::lang('o_global.update_success'),'card/sales_channel','success');
				return;
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
				return;
			}
		}
		
		$this->template->content = new View("card/sales_channel_edit");
		$this->template->content->salesChannel = $salesChannel;
	}
	
	public function detail($salesChannelId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if ($salesChannelId == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		$salesChannel = $this->salesChannelDao->get_by_id($salesChannelId);
		if ($salesChannel == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		
		$this->template->content = new View("card/sales_channel_detail");
		$this->template->content->salesChannel = $salesChannel;
	}

	public function delete($salesChannelId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		$salesChannel = $this->salesChannelDao->get_by_id($salesChannelId);
		if ($salesChannel == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
			return;
		}
		
		$where = array();
		$where['channelid'] = $salesChannelId;
		$count = $this->cardIssueSerialDao->count_items_with_condition($where);
		if ($count > 0) 
		{
			remind::set('仍有卡发行依赖该渠道,不能删除',request::referrer(),'error');
			return;
		}
		
		if($this->salesChannelDao->delete($salesChannelId))
		{
			$aLog = array();
			$aLog['userid']   = $this->manager['id'];
			$aLog['apdtime']  = date('Y-m-d H:i:s', time());
			$aLog['target']   = Ac_cardlog_Model::TARGET_SALE_CHANNEL;
			$aLog['targetid'] = $salesChannelId;
			$aLog['action']   = Ac_cardlog_Model::ACTION_REMOVE;
			$aLog['detail']   = 'Del the saleChannel: '.$salesChannelId;
			$this->cardLogDao->add($aLog);
			
			remind::set(Kohana::lang('o_global.delete_success'),'card/sales_channel','success');
			return;
		}
		remind::set(Kohana::lang('o_global.delete_error'),'card/sales_channel','error');
	}
}
