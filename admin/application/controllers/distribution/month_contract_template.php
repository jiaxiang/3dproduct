<?php
class Month_contract_template_Controller extends Template_Controller 
{
	public function __construct()
	{
		parent::__construct();
        role::check('distribution_system_manage');
	}
	
	public function index() 
	{
		$templateDao = MyMonth_contract_template::instance();
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
		
		$this->template->content = new View("distribution/month_contract_template_list");
		$this->template->content->data = $contractList;
	}
	
	public function add()
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
		
		$contractDetailData = array();
		for ($index=1; $index<=10; $index++) 
		{
			$contractDetailData[$index] = array(
				'grade'   => $index,
				'minimum' => null,
				'maximum' => null,
				'rate'    => null
			);
		}
		
		if($_POST) 
		{
            $data = $_POST;
            $data['type']          = $_POST['type'];
            $data['taxrate']       = 0.000; 
            $data['createtime']    = date("Y-m-d H:i:s",time());
            
            for ($index=1; $index<=10; $index++) 
            {
            	$contractDetailData[$index]['grade']   = $_POST['grade-'.$index];
	            $contractDetailData[$index]['minimum'] = $_POST['minimum-'.$index];
	            $contractDetailData[$index]['maximum'] = $_POST['maximum-'.$index];
	            $contractDetailData[$index]['rate']    = $_POST['rate-'.$index];
            }
            
            $detailList = array();
            for ($index=1; $index<=10; $index++) 
            {
            	if ($contractDetailData[$index]['minimum'] == null && 
            		$contractDetailData[$index]['maximum'] == null && 
            		$contractDetailData[$index]['rate'] == null) 
            	{
            		continue;
            	}
            	if ($contractDetailData[$index]['minimum'] == null || 
            		$contractDetailData[$index]['maximum'] == null || 
            		$contractDetailData[$index]['rate'] == null) 
            	{
            		remind::set(Kohana::lang('o_contract.detail_not_completed'),request::referrer(),'error');
            	}
            	if (is_numeric($contractDetailData[$index]['minimum']) == false || 
            		is_numeric($contractDetailData[$index]['maximum']) == false || 
            		is_numeric($contractDetailData[$index]['rate']) == false)
            	{
            		remind::set('请在合约细则中输入数字','error',request::referrer());
            		return;
            	}
            	if ($contractDetailData[$index]['minimum'] >= $contractDetailData[$index]['maximum']) 
            	{
            		remind::set(Kohana::lang('o_contract.detail_invalid'),request::referrer(),'error');
            	}
            	if (isset($contractDetailData[$index-1]['maximum'])){
	            	if ($contractDetailData[$index]['minimum'] != $contractDetailData[$index-1]['maximum'])
	            	{
	            		remind::set('销售额范围不连续',request::referrer(),'error');
	            		return;
	            	}
            	}
            	if (doubleval($contractDetailData[$index]['rate']) < 0) {
            		remind::set(Kohana::lang('o_contract.detail_invalid'),request::referrer(),'error');;
            	}
            	$detailList[] = $contractDetailData[$index];
            }
            
            
            //标签过滤
            tool::filter_strip_tags($data);
            
			$templateDao = MyMonth_contract_template::instance();
			if($contractId = $templateDao->add($data))
			{
				$dtlTemplateDao = MyMonth_contract_detail_template::instance();
				foreach ($detailList as $aContractDetail) 
				{
					$aContractDetail['contract_id'] = $contractId;
					$aContractDetail['createtime'] = date("Y-m-d H:i:s",time());
					$dtlTemplateDao->add($aContractDetail);
				}
				remind::set(Kohana::lang('o_global.add_success'),'distribution/month_contract_template/','success');
			}
			else
			{
				remind::set(Kohana::lang('o_global.add_error'),request::referrer(),'error');
			}
		}
		
		
		$this->template->content = new View("distribution/month_contract_template_add");
		$this->template->content->contractDetailData = $contractDetailData;
	}
	
	public function delete($templateId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
		
		$templateDao = MyMonth_contract_template::instance();
		$template = $templateDao->get_by_id($templateId);
		if ($template == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'),request::referrer(),'error');
		}
		
		$dtlTemplateDao = MyMonth_contract_detail_template::instance();
		$detailSearchStruct = array();
		$detailSearchStruct['where'] = array(
			'contract_id' => $templateId
		);
		$dtlTemplateList = $dtlTemplateDao->lists($detailSearchStruct);
		foreach ($dtlTemplateList as $aDtlTemplate) 
		{
			MyMonth_contract_detail_template::instance($aDtlTemplate['id'])->delete();
		}
		
		if(MyMonth_contract_template::instance($templateId)->delete())
		{
			remind::set(Kohana::lang('o_global.delete_success'),'distribution/month_contract_template/','success');
		}
		else 
		{
			remind::set(Kohana::lang('o_global.delete_error'),'distribution/month_contract_template/','error');
		}
	}
	
	public function detail($templateId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
		
		$templateDao = MyMonth_contract_template::instance();
		$dtlTemplateDao = MyMonth_contract_detail_template::instance();
		
		$template = $templateDao->get_by_id($templateId);
		if ($template == null) 
		{
			remind::set(Kohana::lang('o_contract.contract_not_exists'),'distribution/month_contract_template/','error');
		}
		
		$detailSearchStruct = array();
		$detailSearchStruct['where'] = array(
			'contract_id' => $templateId
		);
		$dtlTemplateList = $dtlTemplateDao->lists($detailSearchStruct);
		
		$this->template->content = new View("distribution/month_contract_template_detail");
		$this->template->content->template = $template;
		$this->template->content->dataList = $dtlTemplateList;
		
	}
}