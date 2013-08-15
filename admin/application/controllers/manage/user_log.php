<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_log_Controller extends Template_Controller {

	public function __construct()
	{
		parent::__construct();
		role::check('manager_log');
	}
    
	/**
	 * site type list
	 */
	public function index() {
		$query_struct = array();
        
		/**
		 * 搜索
		 */
		$search_arr = array('manager_id','user_log_type','date_begin','date_end');
		foreach($this->input->get() as $key=>$value)
		{
			if(in_array($key,$search_arr))
			{
				if($key == 'date_begin')
				{
					$query_struct['where']["add_time >"] = $value . ' 00:00:00';
				}
				elseif($key == 'date_end')
				{
					$query_struct['where']["add_time <"] = $value . ' 24:00:00';
				}
				elseif(!empty($value))
				{
					$query_struct['where'][$key] = $value;
				}
			}
		}
        
		if($this->manager_is_admin <> 1)
		{
			//得到子用户的ID列表
			$childrens = Mymanager::instance($this->manager_id)->subs();
			$children_ids = array();
			$children_ids[] = $this->manager_id;
			foreach($childrens as $key=>$value)
			{
				$children_ids[] = $value['id'];
			}

			$query_struct['in']['manager_id'] = $children_ids;
		}

		//调用分页
		$per_page    = controller_tool::per_page();
		$this->pagination = new Pagination(
			array(
				'total_items'    => Myuser_log::instance()->count($query_struct),
				'items_per_page' => $per_page,
			)
		);
		$user_logs = Myuser_log::instance()->user_logs($query_struct,array('id'=>'DESC'),$per_page,$this->pagination->sql_offset);
		$user_log_type = Kohana::config('user_log_type.type');
		$user_log_type_status = Kohana::config('user_log_type.status');
		$managers = Mymanager::instance()->subs($this->manager_id);
		$managers[] = Mymanager::instance($this->manager_id)->get();

		foreach($user_logs as $key=>$value)
		{
			$user_logs[$key]['type_name'] = $user_log_type[$value['user_log_type']];
			$user_logs[$key]['status_name'] = $user_log_type_status[$value['user_log_type']][$value['status']];
			foreach($value as $k=>$v)
			{
				if(!is_numeric($v) && empty($v))
				{
					$user_logs[$key][$k] = '无';
				}
			}
		}

		$this->template->content = new View("manage/user_log_list");
		$this->template->content->user_logs = $user_logs;
		$this->template->content->user_log_type = $user_log_type;
		$this->template->content->today = date("Y-m-d",time());
		$this->template->content->yesterday = date("Y-m-d",time()-24*3600);
		$this->template->content->managers = $managers;
	}
	
}