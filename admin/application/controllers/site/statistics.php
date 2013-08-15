<?php
defined('SYSPATH') or die('No direct access allowed.');

class Statistics_Controller extends Template_Controller{

	public function __construct()
	{
		parent::__construct();
		role::check('site');
	}

	/**
	 * site statistics
	 */
	public function index()
	{
		$site_detail = Mysite::instance()->detail();
		$statking_id = $site_detail['statking_id'];
		
		/* 初始化默认查询条件 */
		$query_struct = array(
			'where' => array(
			), 
			'like' => array(), 
			'orderby' => array(), 
			'limit' => array(
				//'per_page' => 20, 
				//'offset' => 0
			)
		);
		
		$data = array();
		$data['count_ip'] = 0;
		$data['sum_order'] = Myorder::instance()->sum();
		$data['count_order_user'] = Myorder::instance()->count_order_user();
		$data['count_order'] = Myorder::instance()->query_count($query_struct);
		$data['count_user'] = Myuser::instance()->query_count($query_struct);
		$average_data = statistics::get_average_data($data);
		$this->template->content = new View("site/statistics");
		$this->template->content->average_data = $average_data;
	}

	/**
	 * 得到当前站点的监控状态
	 */
	public function monitor()
	{
		$monitor_id = statistics::get_task_id();
		$this->template = new View('template_blank');
		$this->template->content = new View("site/statistics_monitor");
		$this->template->content->data_str = statistics::get_list($monitor_id); //0.08s
		$this->template->content->monitor_report = statistics::report_date($monitor_id); //2s - 10s
	}

	/**
	 * 得到当前站点的统计信息
	 */
	public function statking()
	{
		// 初始化返回数据
		$return_data = array();
		//请求结构体
		$request_data = array();
		try{
			$site_detail = Mysite::instance()->detail();
			$statking_id = $site_detail['statking_id'];
			
			$statking_main = statking::get_main_detail($statking_id); //0.7s
			$statking_str = statistics::get_statkings($statking_id); //0.8s

			$this->template = new View('template_blank');
			$this->template->content = new View("site/statistics_statking");
			$this->template->content->statking_str = $statking_str;
			$this->template->content->statking_main = $statking_main;
			$html = $this->template->render();
			$data = array();
			$data['statking_str'] = $statking_str;
			$data['statking_main'] = $statking_main;
			
			$return_data['count_ip'] = $statking_main['site']['all_count_ip'];
			$return_data['html'] = $html;
			exit(json_encode($return_data));
		} catch(MyRuntimeException $ex){
			$return_struct['status'] = 0;
			$return_struct['code'] = $ex->getCode();
			$return_struct['msg'] = $ex->getMessage();
			//TODO 异常处理
			//throw $ex;
			if($this->is_ajax_request()){
				$this->template = new View('layout/empty_html');
				$this->template->content = $return_struct['msg'];
			} else{
				$this->template->return_struct = $return_struct;
				
				$content = new View('info');
				$this->template->content = $content;
				/* 请求结构数据绑定 */
				$this->template->content->request_data = $request_data;
				/* 返回结构体绑定 */
				$this->template->content->return_struct = $return_struct;
			}
		}
	}
}
