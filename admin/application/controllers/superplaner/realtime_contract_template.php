<?php
class Realtime_contract_template_Controller extends Template_Controller 
{
	public function __construct()
	{
		parent::__construct();
        role::check('superplaner_system_manage');
	}
	
	public function index() 
	{
		$templateDao = Superplaner_Realtime_contract_template::instance();
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
            'where'=>array(),
            'orderby'       => $orderby,
            'limit'         => array(
                                'per_page'  =>$per_page,
                                'offset'    =>0
                                )
        );
        $total = $templateDao -> count_templates();
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
        $contractList = $templateDao->lists($query_struct);
		
		$this->template->content = new View("superplaner/realtime_contract_template_list");
		$this->template->content->data = $contractList;
	}
	
	public function add()
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
		
		if($_POST) 
		{
            $data = $_POST;
            $data['type'] = $_POST['contract_type']; 
            $data['createtime'] = date("Y-m-d H:i:s",time());
            
            //标签过滤
            tool::filter_strip_tags($data);
            
			$templateDao = Superplaner_Realtime_contract_template::instance();
			if($templateDao->add($data))
			{
				remind::set(Kohana::lang('o_global.add_success'),'superplaner/realtime_contract_template/','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
			}
		}
		$this->template->content = new View("superplaner/realtime_contract_template_add");
	}
	
	public function delete($templateId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
		
		$templateDao = Superplaner_Realtime_contract_template::instance();
		$template = $templateDao->get_by_id($templateId);
		if ($template == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'),request::referrer(),'error');
		}
		
		if(Superplaner_Realtime_contract_template::instance($templateId)->delete())
		{
			remind::set(Kohana::lang('o_global.delete_success'),request::referrer(),'success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.delete_error'),request::referrer(),'error');
		}
	}
}