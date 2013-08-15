<?php
class Card_Controller extends Template_Controller 
{
	private $cardDao;
	private $cardLogDao;
	private $selectMap;
	
	public function __construct()
	{
		parent::__construct();
        role::check('card_system_manage');
        
        $this->cardDao = MyCard_Core::instance();
        $this->cardLogDao   = MyCardLog_Core::instance();
        
		$this->selectMap = array();
        $this->selectMap['mgrNum']     = '卡号';
        $this->selectMap['serialCode'] = '卡系列编号';
        $this->selectMap['issueId']    = '发行批次号';
        $this->selectMap['openId']     = '开卡批次号';
        $this->selectMap['flag']       = '状态';
	}
	
	public function index() 
	{
		role::check('card_system_manage');
		
		$query_condition = array();
		$query_condition['beginNum']   = null;
		$query_condition['endNum']     = null;
		$query_condition['selectKey']  = null;
		$query_condition['selectValue']  = null;
		
		$per_page = controller_tool::per_page();
        $orderby_arr = array
		(
			0   => array('id'=>'ASC'),
			1   => array('id'=>'DESC'),
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
        
		if ($_GET) 
		{
			if (!empty($_GET['beginNum']) && !empty($_GET['endNum'])) {
				$query_condition['beginNum'] = $_GET['beginNum'];
				$query_condition['endNum']   = $_GET['endNum'];
				$query_struct['where']['mgrnum >='] = $_GET['beginNum'];
				$query_struct['where']['mgrnum <='] = $_GET['endNum'];
				
			} 
			if (isset($_GET['selectValue']) && !empty($_GET['selectValue']) ) {
				
				$query_condition['selectKey']   = $_GET['selectKey'];
				$query_condition['selectValue'] = $_GET['selectValue'];
				
				if ($query_condition['selectKey'] == 'mgrNum') {
					$query_struct['where']['mgrnum'] = $_GET['selectValue'];
				} else if ($query_condition['selectKey'] == 'serialCode') {
					$query_struct['where']['cardserialcode'] = $_GET['selectValue'];
				} else if ($query_condition['selectKey'] == 'issueId') {
					$query_struct['where']['issueid'] = $_GET['selectValue'];
				} else if ($query_condition['selectKey'] == 'openId') {
					$query_struct['where']['openid'] = $_GET['selectValue'];
				} else if ($query_condition['selectKey'] == 'flag') {
					$query_struct['where']['flag'] = $_GET['selectValue'];
				}
			}
		}
        
		$total = $this->cardDao->count_items_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $cardList = $this->cardDao->lists($query_struct);
        
		$this->template->content = new View("card/card_list");
		$this->template->content->data = $cardList;
		$this->template->content->query_condition = $query_condition;
		$this->template->content->selectMap = $this->selectMap;
	}
	
	public function edit($cardId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if ($cardId == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		$theCard = $this->cardDao->get_by_id($cardId);
		if ($theCard == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		
		if($_POST) 
		{
			$data = $_POST;
			$data['id']       = $cardId;
			$data['cardpass'] = $_POST['cardPass'];
			$data['points']   = $_POST['points'];
			$data['flag']     = $_POST['flag'];
			$data['updtime']  = date("Y-m-d H:i:s",time());
			$data['moneyrmb'] = $_POST['moneyRMB'];
			$data['moneyjpy'] = $_POST['moneyJPY'];
			$data['salecost'] = $_POST['saleCost'];
			
			$data['preflag'] = ($data['flag'] == 0) ? $theCard['flag'] : 0 ;
			
            //标签过滤
            tool::filter_strip_tags($data);
            
			if($this->cardDao->edit($data))
			{
				$aLog = array();
				$aLog['userid']   = $this->manager['id'];
				$aLog['apdtime']  = date('Y-m-d H:i:s', time());
				$aLog['target']   = Ac_cardlog_Model::TARGET_CARD;
				$aLog['targetid'] = $cardId;
				$aLog['action']   = Ac_cardlog_Model::ACTION_CHANGE;
				$updateString = '';
				foreach ($_POST as $key => $value)
				{
					$updateString = $updateString.$key.'='.$value.',';
				}
				$aLog['detail']   = 'update a card. '.$updateString;
				$this->cardLogDao->add($aLog);
				
				remind::set(Kohana::lang('o_global.update_success'),'card/card','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			}
		}
		
		$this->template->content = new View("card/card_edit");
		$this->template->content->card = $theCard;
	}
	
	public function detail($cardId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if ($cardId == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		$card = $this->cardDao->get_by_id($cardId);
		if ($card == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
			return;
		}
		
		$this->template->content = new View("card/card_detail");
		$this->template->content->card = $card;
	}
	
}
?>