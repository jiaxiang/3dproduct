<?php
class Settlemonthrpt_Controller extends Template_Controller 
{
	public function __construct()
	{
		parent::__construct();
        role::check('distribution_system_manage');
	}
	public function index()
	{
		$settlemonthrptDao = Mysettlemonthrpt::instance();
		$agent_type = Kohana::config('settle.agent_type');
		$isbeidan = Kohana::config('settle.isbeidan');
		
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
            'where'=>array(
            ),
            'orderby'       => $orderby,
            'limit'         => array(
                                'per_page'  =>$per_page,
                                'offset'    =>0
                                )
        );
        
		/**
		 * 搜索
		 */
		$search_arr = array('type','agent_type','date_begin','date_end','user_id','lastname','isbeidan');
		foreach($this->input->get() as $key=>$value)
		{
			if(in_array($key,$search_arr))
			{
				if($key == 'date_begin')
				{
					$query_struct['where']["settletime >"] = $value . ' 00:00:00';
				}
				elseif($key == 'date_end')
				{
					$query_struct['where']["settletime <"] = $value . ' 24:00:00';
				}
				elseif($key == 'isbeidan' && !empty($value))
				{
					$query_struct['where']["type"] = $value-1;
				}
				elseif($key == 'agent_type' && !empty($value))
				{
					$query_struct['where']["agent_type"] = $value-1;
				}
				elseif(!empty($value))
				{
					$query_struct['where'][$key] = $value;
				}
			}
		}
		
        $total = $settlemonthrptDao -> count_itmes();
        $this->pagination = new Pagination(array(
			'base_url'			=> url::current(),
			'uri_segment'		=> 'page',
			'total_items'		=> $total,
			'items_per_page'	=> $per_page,
			'style'				=> 'digg'
		));
		$query_struct['limit']['offset'] = $this->pagination->sql_offset;
//        $dataList = Mysettlemonthrpt::instance()->lists($query_struct);
        $dataList = Mysettlemonthrpt::instance()->mylists($query_struct);
		
		foreach($dataList as $key=>$value)
		{
			$dataList[$key]['agent_type'] = $agent_type[$value['agent_type']];
			$dataList[$key]['type'] = $isbeidan[$value['type']];
			foreach($value as $k=>$v)
			{
				if(!is_numeric($v) && empty($v))
				{
					$dataList[$key][$k] = '无';
				}
			}
		}
		$this->template->content = new View("distribution/settle_month_rpt");
		$this->template->content->data = $dataList;
		$this->template->content->agent_type = $agent_type;
		$this->template->content->isbeidan = $isbeidan;
		$this->template->content->today = date("Y-m-d",time());
		$this->template->content->yesterday = date("Y-m-d",time()-24*3600);
	}
	public function batch_chk()
    {
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array();

        try {
            $new_ids = $this->input->post('new_ids');
            
            if(is_array($new_ids) && count($new_ids) > 0)
            {
                /* 删除失败的 */
                $failed_link_names = '';
                /* 执行操作 */
                foreach($new_ids as $new_id)
                {
                    if(Mysettlemonthrpt::instance()->chk($new_id)>0)
                    {
                        $failed_link_names .= ' | ' . $new_id;
                    }
                }
                if(empty($failed_link_names))
                {
                    throw new MyRuntimeException(Kohana::lang('settle.settle_month_rpt_chk_success'),200);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_link_names = trim($failed_link_names,' | ');
                    throw new MyRuntimeException(Kohana::lang('settle.settle_month_rpt_chk_error',$failed_link_names),403);
                }
            }
            else
            {
                throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
            }
        } catch (MyRuntimeException $ex) {
			$return_struct['code']   = $ex->getCode();
			$return_struct['msg']    = $ex->getMessage();
			$return_struct['status'] = $return_struct['code']==200?1:0;
        	//TODO 异常处理
			//throw $ex;
			if($this->is_ajax_request()) {
				$this->template = new View('layout/empty_html');
				$this->template->content = $return_struct['msg'];
			} else {
				$this->template->return_struct = $return_struct;

				$content = new View('info');
				$this->template->content = $content;
				/* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				/* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
			}
//            $this->_ex($ex, $return_struct);
        }
    }
	
	public function delete($user_id)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
	}
	public function add($userId)
	{
		//权限验证
		role::check('distribution_system_manage');
	}
	public function edit($agentId)
	{
		//权限检查 得到所有可管理站点ID列表
		role::check('distribution_system_manage');
	}
}
?>