<?php
class Card_type_Controller extends Template_Controller 
{
	private $cardTypeDao = null;
	
	public function __construct()
	{
		parent::__construct();
        role::check('card_system_manage');
        $this->cardTypeDao = MyCardType_Core::instance();
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
        
		$total = $this->cardTypeDao->count_items_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $cardSerialList = $this->cardTypeDao->lists($query_struct);
        
		$this->template->content = new View("card/card_type_list");
		$this->template->content->data = $cardSerialList;
	}
	
	public function add()
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if($_POST) 
		{
			$data = $_POST;
			$data['name']        = $_POST['name']; 
			$data['apdtime']     = date("Y-m-d H:i:s",time());
			
            //标签过滤
            tool::filter_strip_tags($data);
            
			if($cardTypeId = $this->cardTypeDao->add($data))
			{
				remind::set(Kohana::lang('o_global.add_success'),'card/card_type', 'success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
			}
		}
		
		$this->template->content = new View("card/card_type_add");
	}
	
	public function edit($cardTypeId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		if ($cardTypeId == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		$cardType = $this->cardTypeDao->get_by_id($cardTypeId);
		if ($cardType == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
			return;
		}
		
		if($_POST) 
		{
			$data = $_POST;
			$data['id']    = $cardType['id'];
			$data['name']  = $_POST['name'];
			
            //标签过滤
            tool::filter_strip_tags($data);
            
			if($this->cardTypeDao->edit($data))
			{
				remind::set(Kohana::lang('o_global.update_success'),'card/card_type','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
			}
		}
		
		$this->template->content = new View("card/card_type_edit");
		$this->template->content->cardType = $cardType;
	}
	
	public function delete($cardTypeId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('card_system_manage');
		
		$cardType = $this->cardTypeDao->get_by_id($cardTypeId);
		if ($cardType == null) 
		{
			remind::set(Kohana::lang('o_global.bad_request'),request::referrer(),'error');
			return;
		}
		
		if($this->cardTypeDao->delete($cardTypeId))
		{
			remind::set(Kohana::lang('o_global.delete_success'),'card/card_type','success');
			return;
		}
		else 
		{
			remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
			return;
		}
	}
	
//	public function detail($cardTypeId)
//	{
//		//权限检查 得到所有可管理站点ID列表
//		role::check('card_system_manage');
//		
//		if ($cardTypeId == null) 
//		{
//			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
//			return;
//		}
//		$cardType = $this->cardSerialDao->get_by_id($cardTypeId);
//		if ($cardType == null) 
//		{
//			remind::set(Kohana::lang('o_global.bad_request'), request::referrer(), 'error');
//			return;
//		}
//		
//		$this->template->content = new View("card/card_type_detail");
//		$this->template->content->cardSerial = $cardType;
//	}

	
}
?>