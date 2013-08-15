<?php
class Card_serial_Controller extends Template_Controller 
{
	private $cardDao       = null;
	private $cardTypeDao   = null;
	private $cardSerialDao = null;
	private $cardLogDao    = null;
	
	private $cardTypeMap = null;
	
	public function __construct()
	{
		parent::__construct();
        role::check('card_system_manage');
        $this->cardDao       = MyCard_Core::instance();
        $this->cardTypeDao   = MyCardType_Core::instance();
        $this->cardSerialDao = MyCardSerial_Core::instance();
        $this->cardLogDao    = MyCardLog_Core::instance();
        
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
        $tempList = $this->cardTypeDao->lists($query_struct);
        $this->cardTypeMap = array();
        $this->cardTypeMap[0] = '无';
        foreach ($tempList as $aCardType)
        {
        	$this->cardTypeMap[$aCardType['id']] = $aCardType['name'];
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
        
		$total = $this->cardSerialDao->count_items_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $cardSerialList = $this->cardSerialDao->lists($query_struct);
        
		$this->template->content = new View("card/card_serial_list");
		$this->template->content->data = $cardSerialList;
		$this->template->content->cardTypeMap = $this->cardTypeMap;
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
			$data['preflag']     = Ac_cardserial_Model::FLAG_CLOSE;			
			$data['flag']        = Ac_cardserial_Model::FLAG_UNCREATED;
			$data['apdtime']     = date("Y-m-d H:i:s",time());
			$data['updtime']     = date("Y-m-d H:i:s",time());
			$data['bgnnum']      = $_POST['beginNum']; 
			$data['endnum']      = $_POST['endNum']; 
			$data['cardtype']    = $_POST['cardType'];
			$data['points']      = $_POST['points'];
			$data['permoneyrmb'] = $_POST['perMoneyRMB'];
			$data['permoneyjpy'] = $_POST['perMoneyJPY'];
			$data['percost']     = $_POST['perCost'];
			
            //标签过滤
            tool::filter_strip_tags($data);
            
			if($cardSerialId = $this->cardSerialDao->add($data))
			{
				$aLog = array();
				$aLog['userid']   = $this->manager['id'];
				$aLog['apdtime']  = date('Y-m-d H:i:s', time());
				$aLog['target']   = Ac_cardlog_Model::TARGET_CARD_SERIAL;
				$aLog['targetid'] = $cardSerialId;
				$aLog['action']   = Ac_cardlog_Model::ACTION_CREATE;
				$aLog['detail']   = 'insert new cardSerial: '.$cardSerialId.'('.$data['bgnnum'].'-'.$data['endnum'].')';
				$this->cardLogDao->add($aLog);
				remind::set(Kohana::lang('o_global.add_success'),'card/card_serial', 'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
			}
		}
		
		$cardSerialCode = 'CSE-'.date("ymd-His",time());
		
		$this->template->content = new View("card/card_serial_add");
		$this->template->content->cardSerialCode = $cardSerialCode;
		$this->template->content->cardTypeMap = $this->cardTypeMap;
	}
	
	public function edit($cardSerialId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if ($cardSerialId == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		$cardSerial = $this->cardSerialDao->get_by_id($cardSerialId);
		if ($cardSerial == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		
		if($_POST) 
		{
			$data = $_POST;
			$data['id']          = $cardSerial['id'];
			$data['updtime']     = date("Y-m-d H:i:s",time());
			$data['flag']        = $_POST['flag'];
			$data['bgnnum']      = $_POST['beginNum']; 
			$data['endnum']      = $_POST['endNum']; 
			$data['cardtype']    = $_POST['cardType'];
			$data['points']      = $_POST['points'];
			$data['permoneyrmb'] = $_POST['perMoneyRMB'];
			$data['permoneyjpy'] = $_POST['perMoneyJPY'];
			$data['percost']     = $_POST['perCost'];
			
			//检查是否更新成关闭状态
			$data['preflag'] = ($data['flag'] == 0) ? $cardSerial['flag'] : Ac_cardserial_Model::FLAG_CLOSE;
			
            //标签过滤
            tool::filter_strip_tags($data);
            
			if($this->cardSerialDao->edit($data))
			{
				$aLog = array();
				$aLog['userid']   = $this->manager['id'];
				$aLog['apdtime']  = date('Y-m-d H:i:s', time());
				$aLog['target']   = Ac_cardlog_Model::TARGET_CARD_SERIAL;
				$aLog['targetid'] = $cardSerialId;
				$aLog['action']   = Ac_cardlog_Model::ACTION_CHANGE;
				$updateString = '';
				foreach ($_POST as $key => $value)
				{
					$updateString = $updateString.$key.'='.$value.',';
				}
				$aLog['detail']   = 'update cardSerial. '.$updateString;
				$this->cardLogDao->add($aLog);
				remind::set(Kohana::lang('o_global.update_success'),'card/card_serial','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			}
		}
		
		$this->template->content = new View("card/card_serial_edit");
		$this->template->content->cardSerial = $cardSerial;
		$this->template->content->cardTypeMap = $this->cardTypeMap;
	}
	
	public function delete($cardSerialId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		$cardSerial = $this->cardSerialDao->get_by_id($cardSerialId);
		if ($cardSerial == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
			return;
		}
		if ($cardSerial['flag'] == Ac_cardserial_Model::FLAG_LOCKED) 
		{
			remind::set('该卡系列已经锁定，不能生成子卡',request::referrer(),'error');
			return;
		}
		
		$where = array();
		$where['cardserialid'] = $cardSerial['id'];
		$count = $this->cardDao->count_items_with_condition($where);
		if ($count > 0) {
			remind::set("该卡系列的还有子卡存在，不能删除",request::referrer(),'error');
			return;
		}
		
		if($this->cardSerialDao->delete($cardSerialId))
		{
			$aLog = array();
			$aLog['userid']   = $this->manager['id'];
			$aLog['apdtime']  = date('Y-m-d H:i:s', time());
			$aLog['target']   = Ac_cardlog_Model::TARGET_CARD_SERIAL;
			$aLog['targetid'] = $cardSerialId;
			$aLog['action']   = Ac_cardlog_Model::ACTION_REMOVE;
			$aLog['detail']   = 'Del the cardSerial: '.$cardSerialId.'('.$cardSerial['bgnnum'].'-'.$cardSerial['endnum'].')';
			$this->cardLogDao->add($aLog);
			remind::set(Kohana::lang('o_global.delete_success'),'card/card_serial','success');
			return;
		}
		else 
		{
			remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
			return;
		}
	}
	
	public function detail($cardSerialId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if ($cardSerialId == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		$cardSerial = $this->cardSerialDao->get_by_id($cardSerialId);
		if ($cardSerial == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		
		$this->template->content = new View("card/card_serial_detail");
		$this->template->content->cardSerial = $cardSerial;
		$this->template->content->cardTypeMap = $this->cardTypeMap;
	}
	
	public function createCards($cardSerialId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		$cardSerial = $this->cardSerialDao->get_by_id($cardSerialId);
		if ($cardSerial == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
			return;
		}
		if ($cardSerial['flag'] == Ac_cardserial_Model::FLAG_CLOSE) 
		{
			remind::set('该卡系列处于关闭状态，不能生成子卡',request::referrer(),'error');
			return;
		}
		if ($cardSerial['flag'] == Ac_cardserial_Model::FLAG_CREATED) 
		{
			remind::set('该卡系列已经生成过子卡了，不能重复生成',request::referrer(),'error');
			return;
		}
		if ($cardSerial['flag'] == Ac_cardserial_Model::FLAG_LOCKED) 
		{
			remind::set('该卡系列已经锁定，不能生成子卡',request::referrer(),'error');
			return;
		}
		if ($cardSerial['bgnnum'] > $cardSerial['endnum']) 
		{
			remind::set('卡系列的起始号码大于结束号码',request::referrer(),'error');
			return;
		}
		
		// 检查生成的卡号是否重复
		$where = array();
		$where['mgrnum >='] = $cardSerial['bgnnum'];
		$where['mgrnum <='] = $cardSerial['endnum'];
		$count = $this->cardDao->count_items_with_condition($where);
		if ($count > 0) {
			remind::set("目标管理号序列中有已经存在的子卡的卡号，不能重复生成",request::referrer(),'error');
			return;
		}
		
		$cardCount = $cardSerial['endnum'] - $cardSerial['bgnnum'] + 1;
		
		$pwdEngine = MyPasswordEngine::instance();
		$newCardList = array();
		for($index = 0; $index < $cardCount; $index++ )
		{
			$aCard = array();
			$beginNum = $cardSerial['bgnnum'] + $index;
			$beginNum = sprintf('%.0f', $beginNum);
			$aCard['mgrnum']         = $beginNum;
			$aCard['cardpass']       = $pwdEngine->getRandomKey(10);
			$aCard['cardserialid']   = $cardSerial['id'];
			$aCard['cardserialcode'] = $cardSerial['code'];
			$aCard['points']         = $cardSerial['points'];
			$aCard['preflag']        = Ac_card_Model::FLAG_CLOSE;
			$aCard['flag']           = Ac_card_Model::FLAG_CLOSE;
			$aCard['apdtime']        = date("Y-m-d H:i:s",time());
			$aCard['updtime']        = date("Y-m-d H:i:s",time());
			$aCard['moneyrmb']       = $cardSerial['permoneyrmb'];
			$aCard['moneyjpy']       = $cardSerial['permoneyjpy'];
			$aCard['salecost']       = $cardSerial['percost'];
			$aCard['issueid']        = 0;
			$aCard['issuetime']      = null;
			$aCard['openid']         = 0;
			$aCard['opentime']       = null;
			$newCardList[] = $aCard;
		}
		if (($this->cardDao->batch_insert($newCardList)) == false) {
			remind::set("子卡添加失败",request::referrer(),'error');
			return;
		}
		
		$data = array();
		$data['id']      = $cardSerial['id'];
		$data['updtime'] = date("Y-m-d H:i:s",time());
		$data['flag']    = Ac_cardserial_Model::FLAG_CREATED;	//已生成子卡
		//标签过滤
		tool::filter_strip_tags($data);
		if(($this->cardSerialDao->edit($data)) == false)
		{
			remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
		}
		$aLog = array();
		$aLog['userid']   = $this->manager['id'];
		$aLog['apdtime']  = date('Y-m-d H:i:s', time());
		$aLog['target']   = Ac_cardlog_Model::TARGET_CARD;
		$aLog['targetid'] = 0;
		$aLog['action']   = Ac_cardlog_Model::ACTION_CREATE;
		$aLog['detail']   = 'Create card:'.$cardSerial['bgnnum'].'-'.$cardSerial['endnum'];
		$this->cardLogDao->add($aLog);
		remind::set(Kohana::lang('o_global.add_success'),'card/card_serial', 'success');
	}
	
	public function removeCards($cardSerialId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		$cardSerial = $this->cardSerialDao->get_by_id($cardSerialId);
		if ($cardSerial == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
			return;
		}
		if ($cardSerial['flag'] == Ac_cardserial_Model::FLAG_CLOSE) 
		{
			remind::set('该卡系列处于关闭状态，不能删除子卡',request::referrer(),'error');
			return;
		}
		if ($cardSerial['flag'] == Ac_cardserial_Model::FLAG_LOCKED) 
		{
			remind::set('该卡系列已经锁定，不能删除子卡',request::referrer(),'error');
			return;
		}
		
		$where = array();
		$where ['cardserialid'] = $cardSerial['id'];
		$where ['mgrnum >='] = $cardSerial['bgnnum'];
		$where ['mgrnum <='] = $cardSerial['endnum'];
		if (($this->cardDao->batch_delete($where)) == false)
		{
			remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
			return;
		}
		
		$data = array();
		$data['id']      = $cardSerial['id'];
		$data['updtime'] = date("Y-m-d H:i:s",time());
		$data['flag']    = Ac_cardserial_Model::FLAG_UNCREATED;	//未生成子卡
		//标签过滤
		tool::filter_strip_tags($data);
		if (($this->cardSerialDao->edit($data)) == false)
		{
			remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			return;
		}
		
		$aLog = array();
		$aLog['userid']   = $this->manager['id'];
		$aLog['apdtime']  = date('Y-m-d H:i:s', time());
		$aLog['target']   = Ac_cardlog_Model::TARGET_CARD;
		$aLog['targetid'] = 0;
		$aLog['action']   = Ac_cardlog_Model::ACTION_REMOVE;
		$aLog['detail']   = 'Remove card:'.$cardSerial['bgnnum'].'-'.$cardSerial['endnum'];
		$this->cardLogDao->add($aLog);
		remind::set(Kohana::lang('o_global.delete_success'),'card/card_serial', 'success');
	}
	
	public function lock($cardSerialId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		$cardSerial = $this->cardSerialDao->get_by_id($cardSerialId);
		if ($cardSerial == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
			return;
		}
		
		// update card flag 
		$newValues = array();
		$newValues['flag'] = Ac_card_Model::FLAG_UNISSUE;
		$newValues['updtime'] = date("Y-m-d H:i:s",time());
		
		$where = array();
		$where['mgrnum >='] = $cardSerial['bgnnum'];
		$where['mgrnum <='] = $cardSerial['endnum'];
		$where['cardserialid'] = $cardSerial['id'];
		
		if($this->cardDao->batch_update($newValues, $where) == false)
		{
			remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			return;
		}
		
		$data = array();
		$data['id']      = $cardSerial['id'];
		$data['updtime'] = date("Y-m-d H:i:s",time());
		$data['flag']    = Ac_cardserial_Model::FLAG_LOCKED;
		//标签过滤
		tool::filter_strip_tags($data);
		if(($this->cardSerialDao->edit($data)) == false)
		{
			remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			return;
		}
		
		$aLog = array();
		$aLog['userid']   = $this->manager['id'];
		$aLog['apdtime']  = date('Y-m-d H:i:s', time());
		$aLog['target']   = Ac_cardlog_Model::TARGET_CARD_SERIAL;
		$aLog['targetid'] = $cardSerialId;
		$aLog['action']   = Ac_cardlog_Model::ACTION_CHANGE;
		$aLog['detail']   = 'Lock cardSerial:'.$cardSerialId.'('.$cardSerial['bgnnum'].'-'.$cardSerial['endnum'].')';
		remind::set(Kohana::lang('o_global.update_success'),'card/card_serial', 'success');
	}	
}
?>