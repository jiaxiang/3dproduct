<?php
class Card_open_serial_Controller extends Template_Controller 
{
	private $cardDao;
	private $openBillDao;
	private $openBillDtlDao;
	private $cardOpenSerialDao;
	private $cardIssueSerialDao;
	private $cardLogDao;
	
	private $selectMap;
	
	public function __construct()
	{
		parent::__construct();
        role::check('card_system_manage');
        
        $this->cardDao            = MyCard_Core::instance();
        $this->openBillDao        = MyOpenBill_Core::instance();
        $this->openBillDtlDao     = MyOpenBillDtl_Core::instance();
		$this->cardOpenSerialDao  = MyCardOpenSerial_Core::instance();
        $this->cardIssueSerialDao = MyCardIssueSerial_Core::instance();
        $this->cardLogDao         = MyCardLog_Core::instance();
        
        $this->selectMap = array();
        $this->selectMap['mgrNum']     = '卡号';
        $this->selectMap['serialCode'] = '卡系列编号';
        $this->selectMap['issueId']    = '卡发行号';
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
        
		$total = $this->cardOpenSerialDao->count_items_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $cardOpenList = $this->cardOpenSerialDao->lists($query_struct);
        
		$this->template->content = new View("card/card_open_serial_list");
		$this->template->content->data = $cardOpenList;
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
			$data['opentime']    = date("Y-m-d H:i:s",time());
			$data['bgnnum']      = $_POST['beginNum']; 
			$data['endnum']      = $_POST['endNum']; 
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
				remind::set('目标卡号序列中存在已关闭的卡号，不能执行开卡',request::referrer(),'error');
				return;
			}			
			$where['flag'] = Ac_card_Model::FLAG_UNISSUE;
			$cardCount = $this->cardDao->count_items_with_condition($where);
			if ($cardCount > 0) 
			{
				remind::set('目标卡号序列中存在未发行的卡号，未发行的卡不能开启',request::referrer(),'error');
				return;
			}			
			$where['flag'] = Ac_card_Model::FLAG_OPEN;
			$cardCount = $this->cardDao->count_items_with_condition($where);
			if ($cardCount > 0) 
			{
				remind::set('目标卡号序列中存在已生效的卡号，不能重复执行开卡',request::referrer(),'error');
				return;
			}
			$where['flag'] = Ac_card_Model::FLAG_USED;
			$cardCount = $this->cardDao->count_items_with_condition($where);
			if ($cardCount > 0) 
			{
				remind::set('目标卡号序列中存在已使用的卡号，不能重复执行开卡',request::referrer(),'error');
				return;
			}
			
			// 2. check sub card issue
			$aCard = $this->cardDao->get_by_mgrnum($data['bgnnum']);
			
			$where = array();
			$where['mgrnum >='] = $data['bgnnum'];
			$where['mgrnum <='] = $data['endnum'];
			$where['issueid'] = $aCard['issueid'];
			$cardCount = $this->cardDao->count_items_with_condition($where);
			if ($cardCount != ($data['endnum'] - $data['bgnnum'] +1)) 
			{
				remind::set('每个开卡批次的卡必须是同一个发行批次的卡',request::referrer(),'error');
				return;
			}
			// check issue serial
			$theIssue = $this->cardIssueSerialDao->get_by_id($aCard['issueid']);
			if ($theIssue == null) {
				remind::set('开卡批次记录丢失',request::referrer(),'error');
				return;
			}
			$data['issueid'] = $theIssue['id'];
			
            //标签过滤
            tool::filter_strip_tags($data);
            
			if(($cardOpenId = $this->cardOpenSerialDao->add($data)) == false)
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
				return;
			}
			
			// 3. add openBill;
			$aOpenBill = array();
			$aOpenBill['num']         = 'OPB-'.date('ymd-His',time());
			$aOpenBill['user_id']     = $data['checkuserid'];
			$aOpenBill['flag']        = Ac_openbill_Model::FLAG_NEW;
			$aOpenBill['issueid']     = $theIssue['id'];
			$aOpenBill['channelid']   = $theIssue['channelid'];
			$aOpenBill['channelcode'] = $theIssue['channelcode'];
			$aOpenBill['bgnnum']      = $data['bgnnum'];
			$aOpenBill['endnum']      = $data['endnum'];
			$aOpenBill['moneys']      = 0;
			$aOpenBill['des']         = null;
			$aOpenBill['apdtime']     = date('Y-m-d H:i:s', time());
			$aOpenBill['updtime']     = date('Y-m-d H:i:s', time());
			if (($openBillId = $this->openBillDao->add($aOpenBill)) == false)
			{
				remind::set('添加开卡单据时失败，请检查一致性。',request::referrer(),'error');
				return;
			}
				
			$updData = array();
			$updData['id'] = $cardOpenId;
			$updData['billid'] = $openBillId;
			$this->cardOpenSerialDao->edit($updData);
				
			//4. update card flag and 
			$newValues = array();
			$newValues['flag']     = Ac_card_Model::FLAG_OPEN;
			$newValues['openid']   = $cardOpenId;
			$newValues['opentime'] = $data['opentime'];
			$newValues['updtime']  = date("Y-m-d H:i:s",time());
			
			$where = array();
			$where['mgrnum >='] = $data['bgnnum'];
			$where['mgrnum <='] = $data['endnum'];
			$where['flag'] = Ac_card_Model::FLAG_ISSUED;
			
			if (($this->cardDao->batch_update($newValues, $where)) == false) {
				remind::set('更新充值卡状态失败，请检查一致性。',request::referrer(),'error');
				return;
			}
			
			// 5. batch add openBillDtl
			$cardCount = $data['endnum'] - $data['bgnnum'] + 1;
			
			$openBillDtlList = array();
			for($index = 0; $index < $cardCount; $index++ )
			{
				$aOpenBillDtl = array();
				$aOpenBillDtl['mstid']  = $openBillId;
				$aOpenBillDtl['num']    = $aOpenBill['num'];
				$mgrNum = $data['bgnnum'] + $index;
				$mgrNum = sprintf('%.0f', $mgrNum);
				$aOpenBillDtl['mgrnum'] = $mgrNum;
				$openBillDtlList[] = $aOpenBillDtl;
			}
			$this->openBillDtlDao->batch_insert($openBillDtlList);
			
			// 6. add log
			$aLog = array();
			$aLog['userid']   = $this->manager['id'];
			$aLog['apdtime']  = date('Y-m-d H:i:s', time());
			$aLog['target']   = Ac_cardlog_Model::TARGET_CARD_OPEN;
			$aLog['targetid'] = $cardOpenId;
			$aLog['action']   = Ac_cardlog_Model::ACTION_CREATE;
			$aLog['detail']   = 'insert new cardOpenSerial: '.$cardOpenId.'('.$data['bgnnum'].'-'.$data['endnum'].')';
			$this->cardLogDao->add($aLog);
			remind::set(Kohana::lang('o_global.add_success'),'card/card_open_serial', 'success');
			return;
		}
		
		$this->template->content = new View("card/card_open_serial_add");
		$this->template->content->manager = $this->manager;
	}
	
	public function detail($cardOpenSerialId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if ($cardOpenSerialId == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		$cardOpenSerial = $this->cardOpenSerialDao->get_by_id($cardOpenSerialId);
		if ($cardOpenSerial == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		
		$this->template->content = new View("card/card_open_serial_detail");
		$this->template->content->cardOpen = $cardOpenSerial;
	}
	
	public function delete($cardOpenSerialId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		$cardOpenSerial = $this->cardOpenSerialDao->get_by_id($cardOpenSerialId);
		if ($cardOpenSerial == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
			return;
		}
		
		// check the sub cards' flag
		$where = array();
		$where['mgrnum >='] = $cardOpenSerial['bgnnum'];
		$where['mgrnum <='] = $cardOpenSerial['endnum'];
		$where['flag'] = Ac_card_Model::FLAG_OPEN;
		$cardCount = $this->cardDao->count_items_with_condition($where);
		if ($cardCount != ($cardOpenSerial['endnum'] - $cardOpenSerial['bgnnum'] + 1)) {
			remind::set('该开卡批次的卡中，存在已被使用的卡, 不能删除该批次',request::referrer(),'error');
			return;
		}
		
		// update the sub cards' flag
		$newValues = array();
		$newValues['flag']     = Ac_card_Model::FLAG_ISSUED;
		$newValues['openid']   = 0;
//		$newValues['opentime'] = null;
		$newValues['updtime']  = date("Y-m-d H:i:s",time());
		if (($this->cardDao->batch_update($newValues, $where)) == false){
			remind::set('更新卡状态失败',request::referrer(),'error');
			return;
		}
		
		// update the openBill flag
		$updBillData = array();
		$updBillData['id']      = $cardOpenSerial['billid'];
		$updBillData['flag']    = Ac_openbill_Model::FLAG_DELETED;
		$updBillData['updtime'] = date("Y-m-d H:i:s",time());
		$this->openBillDao->edit($updBillData);
		
		// delete the card opem serial
		if(($this->cardOpenSerialDao->delete($cardOpenSerialId)) == false)
		{
			remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
			return;
		}
		$aLog = array();
		$aLog['userid']   = $this->manager['id'];
		$aLog['apdtime']  = date('Y-m-d H:i:s', time());
		$aLog['target']   = Ac_cardlog_Model::TARGET_CARD_OPEN;
		$aLog['targetid'] = $cardOpenSerialId;
		$aLog['action']   = Ac_cardlog_Model::ACTION_REMOVE;
		$aLog['detail']   = 'Del the cardOpenSerial: '.$cardOpenSerialId.'('.$cardOpenSerial['bgnnum'].'-'.$cardOpenSerial['endnum'].')';
		$this->cardLogDao->add($aLog);
		remind::set(Kohana::lang('o_global.delete_success'),'card/card_open_serial','success');
	}
	
}
?>