<?php
class Money_exchange_Controller extends Template_Controller 
{
	private $moneyExchangeDao = null;
	
	public function __construct()
	{
		parent::__construct();
        role::check('card_system_manage');
        $this->moneyExchangeDao = MyMoneyExchange_Core::instance();
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
        
		$total = $this->moneyExchangeDao->count_items_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $moneyExchangeList = $this->moneyExchangeDao->lists($query_struct);
        
		$this->template->content = new View("card/money_exchange_list");
		$this->template->content->data = $moneyExchangeList;
	}
	
	public function add()
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		$moneyExchangeCode = 'MEX-'.date("ymd-His",time());
		
		if($_POST) 
		{
			$data = $_POST;
			$data['code']    = $_POST['code']; 
			$data['name']    = $_POST['name']; 
			$data['numrmb']  = $_POST['numrmb']; 
			$data['numjpy']  = $_POST['numjpy']; 
			$data['updtime'] = date("Y-m-d H:i:s",time());
			$data['flag']    = $_POST['flag'];
			
            //标签过滤
            tool::filter_strip_tags($data);
            
			if($cardSerialId = $this->moneyExchangeDao->add($data))
			{
				remind::set(Kohana::lang('o_global.add_success'),'card/money_exchange', 'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
			}
		}
		
		$this->template->content = new View("card/money_exchange_add");
		$this->template->content->moneyExchangeCode = $moneyExchangeCode;
	}
	
	public function delete($moneyExchangeId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		$moneyExchange = $this->moneyExchangeDao->get_by_id($moneyExchangeId);
		if ($moneyExchange == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
			return;
		}
		
		if ($moneyExchange['flag'] == 2) {
			remind::set("不能删除生效中的汇率方案",request::referrer(),'error');
			return;
		}
		
		if($this->moneyExchangeDao->delete($moneyExchange))
		{
			remind::set(Kohana::lang('o_global.delete_success'),'card/money_exchange','success');
			return;
		}
		else 
		{
			remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
			return;
		}
	}
	
}
?>