<?php
class Card_log_Controller extends Template_Controller 
{
	private $cardLogDao = null;
	
	public function __construct()
	{
		parent::__construct();
		role::check('card_system_manage');
		$this->cardLogDao = MyCardLog_Core::instance();
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
        
		$total = $this->cardLogDao->count_items_with_condition($query_struct['where']);
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $cardLogList = $this->cardLogDao->lists($query_struct);
        
		$this->template->content = new View("card/card_log_list");
		$this->template->content->data = $cardLogList;
	}
	
}
?>