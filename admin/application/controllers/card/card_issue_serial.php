<?php
class Card_issue_serial_Controller extends Template_Controller 
{
	private $cardIssueSerialDao = null;
	private $salesChannelDao    = null;
	private $cardDao            = null;
	private $issueBillDao       = null;
	private $issueBillDtlDao    = null;
	private $cardLogDao;
	
	private $salesChanenlList = null;
	private $salesChannelMap = null;
	
	public function __construct()
	{
		parent::__construct();
		role::check('card_system_manage');
		$this->cardIssueSerialDao = MyCardIssueSerial_Core::instance();
		$this->salesChannelDao    = MySalesChannel_Core::instance();
		$this->cardDao            = MyCard_Core::instance();
		$this->issueBillDao       = MyIssueBill_Core::instance();
		$this->issueBillDtlDao    = MyIssueBillDtl_Core::instance();
		$this->cardLogDao         = MyCardLog_Core::instance();
		
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
        
		$total = $this->cardIssueSerialDao->count_items_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $cardIssueSerialList = $this->cardIssueSerialDao->lists($query_struct);
        
//        d($this->salesChannelMap, true);
		$this->template->content = new View("card/card_issue_serial_list");
		$this->template->content->data = $cardIssueSerialList;
		$this->template->content->channelList = $this->salesChannelMap;
	}
	
	public function add()
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if($_POST) 
		{
			$data = $_POST;
			$data['checkuserid'] = $_POST['checkUserId']; 
			$data['checkuser']   = $_POST['checkUser']; 
			$data['channelid']   = $_POST['channelId'];
			$data['channelcode'] = $this->salesChannelMap[$data['channelid']]['code'];
			$data['issuetime']   = date("Y-m-d H:i:s",time());
			$data['bgnnum']      = $_POST['beginNum']; 
			$data['endnum']      = $_POST['endNum']; 
			$data['mailcost']    = $_POST['mailCost'];
			$data['billid']      = 0;
			
			// 1. check the card mgrnum
			$where = array();
			$where['mgrnum >='] = $data['bgnnum'];
			$where['mgrnum <='] = $data['endnum'];
			$cardCount = $this->cardDao->count_items_with_condition($where);
			if ($cardCount != ($data['endnum'] - $data['bgnnum'] +1)) 
			{
				remind::set('目标卡号序列中存在未生成的卡号，请先生成充值卡再发行',request::referrer(),'error');
				return;
			}
			
			$where['flag'] = Ac_card_Model::FLAG_CLOSE;
			$cardCount = $this->cardDao->count_items_with_condition($where);
			if ($cardCount > 0) 
			{
				remind::set('目标卡号序列中存在已关闭的卡号，关闭的卡号不能发行',request::referrer(),'error');
				return;
			}			
			$where['flag'] = Ac_card_Model::FLAG_ISSUED;
			$cardCount = $this->cardDao->count_items_with_condition($where);
			if ($cardCount > 0) 
			{
				remind::set('目标卡号序列中存在已发行的卡号，不能重复发行',request::referrer(),'error');
				return;
			}			
			$where['flag'] = Ac_card_Model::FLAG_OPEN;
			$cardCount = $this->cardDao->count_items_with_condition($where);
			if ($cardCount > 0) 
			{
				remind::set('目标卡号序列中存在已生效(开启)的卡号，不能重复发行',request::referrer(),'error');
				return;
			}
			$where['flag'] = Ac_card_Model::FLAG_USED;
			$cardCount = $this->cardDao->count_items_with_condition($where);
			if ($cardCount > 0) 
			{
				remind::set('目标卡号序列中存在已使用的卡号，不能重复发行',request::referrer(),'error');
				return;
			}
			
            //标签过滤
            tool::filter_strip_tags($data);
			if(($cardIssueId = $this->cardIssueSerialDao->add($data)) == false)
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
				return;
			}
			
			// 2. add IssueBill;
			$theIssueBill = array();
			$theIssueBill['num']        = 'ISB-'.date("ymd-His",time());
			$theIssueBill['user_id']    = $this->manager['id'];
			$theIssueBill['flag']       = Ac_issuebill_Model::FLAG_NEW;
			$theIssueBill['issueid']    = $cardIssueId;
			$theIssueBill['channelid']  = $data['channelid'];
			$theIssueBill['channelcode'] = $data['channelcode'];
			$theIssueBill['bgnnum']     = $data['bgnnum'];
			$theIssueBill['endnum']     = $data['endnum'];
			$theIssueBill['moneys']     = 0;
			$theIssueBill['des']        = '';
			$theIssueBill['apdtime']    = date("Y-m-d H:i:s",time());
			$theIssueBill['updtime']    = date("Y-m-d H:i:s",time());
			tool::filter_strip_tags($theIssueBill);
			if (($issueBillId = $this->issueBillDao->add($theIssueBill)) == false) {
				remind::set('添加发行单据时失败，请检查一致性。',request::referrer(),'error');
				return;
			}
			
			$updData = array();
			$updData['id'] = $cardIssueId;
			$updData['billid'] = $issueBillId;
			$this->cardIssueSerialDao->edit($updData);
			
			// 3. update card flag 
			$newValues = array();
			$newValues['flag']      = Ac_card_Model::FLAG_ISSUED;
			$newValues['issueid']   = $cardIssueId;
			$newValues['issuetime'] = $data['issuetime'];
			$newValues['updtime']   = date("Y-m-d H:i:s",time());
			
			$where = array();
			$where['mgrnum >='] = $data['bgnnum'];
			$where['mgrnum <='] = $data['endnum'];
			$where['flag'] = Ac_card_Model::FLAG_UNISSUE;
			
			if (($this->cardDao->batch_update($newValues, $where)) == false) {
				remind::set('更新充值卡状态失败，请检查一致性。',request::referrer(),'error');
				return;
			}
			
			// 4. batch add issueBillDtl
			$cardCount = $data['endnum'] - $data['bgnnum'] + 1;
			
			$issueBillDtlList = array();
			for($index = 0; $index < $cardCount; $index++ )
			{
				$aIssueBillDtl = array();
				$aIssueBillDtl['mstid']  = $issueBillId;
				$aIssueBillDtl['num']    = $theIssueBill['num'];
				$mgrNum = $data['bgnnum'] + $index;
				$mgrNum = sprintf('%.0f', $mgrNum);
				$aIssueBillDtl['mgrnum'] = $mgrNum;
				$issueBillDtlList[] = $aIssueBillDtl;
			}
			$this->issueBillDtlDao->batch_insert($issueBillDtlList);
			
			// 5. add the log
			$aLog = array();
			$aLog['userid']   = $this->manager['id'];
			$aLog['apdtime']  = date('Y-m-d H:i:s', time());
			$aLog['target']   = Ac_cardlog_Model::TARGET_CARD_ISSUE;
			$aLog['targetid'] = $cardIssueId;
			$aLog['action']   = Ac_cardlog_Model::ACTION_CREATE;
			$aLog['detail']   = 'insert new cardIssueSerial: '.$cardIssueId.'('.$data['bgnnum'].'-'.$data['endnum'].')';
			$this->cardLogDao->add($aLog);
			remind::set(Kohana::lang('o_global.add_success'),'card/card_issue_serial', 'success');
			return;
		}
		
		$this->template->content = new View("card/card_issue_serial_add");
		$this->template->content->channelList = $this->salesChannelMap;
		$this->template->content->manager = $this->manager;
	}
	
//	public function edit($cardIssueSerialId)
//	{
//		//权限检查 得到所有可管理站点ID列表
//		role::check('card_system_manage');
//		
//		if ($cardIssueSerialId == null) 
//		{
//			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
//			return;
//		}
//		$cardIssueSerial = $this->cardIssueSerialDao->get_by_id($cardIssueSerialId);
//		if ($cardIssueSerial == null) 
//		{
//			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
//			return;
//		}
//		
//		if($_POST) 
//		{
//			$data = $_POST;
//			$data['id']        = $cardIssueSerial['id'];
//			$data['channelid'] = $_POST['channelId'];
//			$data['bgnnum']    = $_POST['beginNum']; 
//			$data['endnum']    = $_POST['endNum']; 
//			$data['mailcost']  = $_POST['mailCost'];
//			
//            //标签过滤
//            tool::filter_strip_tags($data);
//            
//			if($this->cardIssueSerialDao->edit($data))
//			{
//				remind::set(Kohana::lang('o_global.update_success'),'card/card_issue_serial','success');
//			}
//			else
//			{
//				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
//			}
//		}
//		
//		$this->template->content = new View("card/card_issue_serial_edit");
//		$this->template->content->channelList = $this->salesChannelMap;
//		$this->template->content->cardIssue = $cardIssueSerial;
//	}
	
	public function detail($cardIssueSerialId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if ($cardIssueSerialId == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		$cardIssueSerial = $this->cardIssueSerialDao->get_by_id($cardIssueSerialId);
		if ($cardIssueSerial == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		
		$cardIssueSerial['channelname'] = $this->salesChannelMap[$cardIssueSerial['channelid']]['name'];
		
		$this->template->content = new View("card/card_issue_serial_detail");
		$this->template->content->cardIssue = $cardIssueSerial;
	}
	
	public function delete($cardIssueSerialId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		$cardIssueSerial = $this->cardIssueSerialDao->get_by_id($cardIssueSerialId);
		if ($cardIssueSerial == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
			return;
		}
		
		// check the sub cards' flag
		$where = array();
		$where['mgrnum >='] = $cardIssueSerial['bgnnum'];
		$where['mgrnum <='] = $cardIssueSerial['endnum'];
		$where['flag'] = Ac_card_Model::FLAG_ISSUED;
		$cardCount = $this->cardDao->count_items_with_condition($where);
		if ($cardCount != ($cardIssueSerial['endnum'] - $cardIssueSerial['bgnnum'] + 1)) {
			remind::set('该发行批次的卡中，存在已被使用的卡, 不能删除该批次',request::referrer(),'error');
			return;
		}
		
		// update the sub cards' flag
		$newValues = array();
		$newValues['flag']      = Ac_card_Model::FLAG_UNISSUE;
		$newValues['issueid']   = 0;
//		$newValues['issuetime'] = null;
		$newValues['updtime']   = date("Y-m-d H:i:s",time());
		if (($this->cardDao->batch_update($newValues, $where)) == false){
			remind::set('更新卡状态失败',request::referrer(),'error');
			return;
		}
		
		// update the issueBill flag
		$updBillData = array();
		$updBillData['id'] = $cardIssueSerial['billid'];
		$updBillData['flag'] = Ac_issuebill_Model::FLAG_DELETED;
		$updBillData['updtime'] = date("Y-m-d H:i:s",time());
		$this->issueBillDao->edit($updBillData);
		
		// delete the card issue serial
		if(($this->cardIssueSerialDao->delete($cardIssueSerialId)) == false)
		{
			remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
			return;
		}
		$aLog = array();
		$aLog['userid']   = $this->manager['id'];
		$aLog['apdtime']  = date('Y-m-d H:i:s', time());
		$aLog['target']   = Ac_cardlog_Model::TARGET_CARD_ISSUE;
		$aLog['targetid'] = $cardIssueSerialId;
		$aLog['action']   = Ac_cardlog_Model::ACTION_REMOVE;
		$aLog['detail']   = 'Del the cardIssueSerial: '.$cardIssueSerialId.'('.$cardIssueSerial['bgnnum'].'-'.$cardIssueSerial['endnum'].')';
		$this->cardLogDao->add($aLog);
		remind::set(Kohana::lang('o_global.delete_success'),'card/card_issue_serial','success');
	}
	
}
?>