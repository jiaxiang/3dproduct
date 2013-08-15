<?php defined('SYSPATH') OR die('No direct access allowed.');

class Jobs_Controller extends Template_Controller {
	
	protected static $pdb = null;

	public function __construct()
	{
		parent::__construct();
		role::check('lottnum_jobs');
	}
	
	public function index($status=null){
		$per_page = controller_tool::per_page();
		
		$lotyid = lottnum::getlottid($status);
		$where = array('lottyid'=>$lotyid);
		
		//初始化默认查询结构体 
        $query_struct_default = array (
            'orderby' => array (
                'id' => 'DESC' 
            ), 
            'limit' => array (
                'per_page' => $per_page,
                'page' => 1
            ),
            'where' => $where,
        );
        
        $jobobj = Lotty_jobService::get_instance();
        $return_data['count'] = $jobobj->count($query_struct_default);        //统计数量
        
        /* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => $return_data['count'],
			'items_per_page' => $query_struct_default['limit']['per_page'],    
		));

        $query_struct_default['limit']['page'] = $this->pagination->current_page;
        
        $return_data['list'] = $jobobj->query_assoc($query_struct_default);
        
        //彩种配置
        $lottconfig = Kohana::config("ticket_type.type");
        $return_data['lottconfig'] = $lottconfig;
        //任务状态说明
        $lottnumconfig = Kohana::config("lottnum");
        $return_data['jobstatconfig'] = $lottnumconfig['jobstat'];
        $return_data['jobtype'] = $lottnumconfig['jobtype'];
        
        
        $return_data['status'] = is_null($status)?'dlt':$status;
        
        //近10期期号列表
		$return_data['issues'] = lottnum::getissue(is_null($status)?'dlt':$status);
        
        
        $this->template->content = new View("lottnum/jobs", $return_data);
	}
	
	/**
	 * 任务下达
	 * @param $type
	 */
	public function add($type='dlt'){
		if(empty($_POST)){
			remind::set("未选择下达任务!",'/lottnum/jobs/index/'.$type.'/','error');
		}
		$lottyid = lottnum::getlottid($type);
		$issue   = $this->input->post('issue');
		$jtype   = $this->input->post('jtype');
		
	    $query_struct_default = array (
               'where' => array('lotyid'=>$lottyid,'qihao'=>$issue)
             );
		$qhservice = Qihaoservice::get_instance();
	    $qihao     = $qhservice->query_data_list($query_struct_default);
		if($qihao){
			if(time()<=strtotime($qihao[0]['endtime'])){
				remind::set($issue."期未截止不能下达清算任务!",'/lottnum/jobs/index/'.$type.'/','error');
			}
	    }else{
			 remind::set($issue."期不存在不可做任务下达!",'/lottnum/jobs/index/'.$type.'/','error');
		}
		
		if($jtype==2){
			
			$query_struct_default = array (
               'where' => array('lottyid'=>$lottyid,'qihao'=>$issue,'tasktype'=>1)
             );
             $jobobj = Lotty_jobService::get_instance();
             $job    = $jobobj->query_data_list($query_struct_default);
             if(count($job)==0){
             	remind::set($issue."期清算任务未下达，不可下达算奖任务!",'/lottnum/jobs/index/'.$type.'/','error');
             }else{
                 if($qihao[0]['qsstat']!=1){
				   	remind::set($issue."期清算任务未完成不可做任务下达!",'/lottnum/jobs/index/'.$type.'/','error');
			     }
             }
             
		}elseif($jtype==3){
			$query_struct_default = array (
               'where' => array('lottyid'=>$lottyid,'qihao'=>$issue,'tasktype'=>2)
             );
             $jobobj = Lotty_jobService::get_instance();
             $job    = $jobobj->query_data_list($query_struct_default);
             if(count($job)==0){
             	remind::set($issue."期算奖任务未下达，不可下达派奖任务!",'/lottnum/jobs/index/'.$type.'/','error');
             }
		}
		$data = array();
		$data['lottyid'] = $lottyid;
		$data['qihao'] = $issue;
		$data['tasktype'] = $jtype;
		$data['ctime'] = date("Y-m-d H:i:s");
		$data['stat'] = 0;
		$data['manager'] = $this->manager['username'];
		$flag = Lotty_jobService::get_instance()->add($data);
		if($flag>0){
			remind::set("任务下达成功!",'/lottnum/jobs/index/'.$type.'/','success');
		}else{
			remind::set("任务下达失败!",'/lottnum/jobs/index/'.$type.'/','error');
		}
	}
}