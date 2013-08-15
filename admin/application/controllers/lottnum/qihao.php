<?php defined('SYSPATH') OR die('No direct access allowed.');

class Qihao_Controller extends Template_Controller {

	public function __construct()
	{
		parent::__construct();
		role::check('lottnum_qihao');
	}
	/**
	 * 期号改列表
	 */
	public function index($status = NULL){
		$per_page = controller_tool::per_page();
		
		$lotyid = lottnum::getlottid($status);
		$where = array('lotyid'=>$lotyid);
		//初始化默认查询结构体 
        $query_struct_default = array (
            'orderby' => array (
                'id' => 'DESC' 
            ), 
            'limit' => array (
                'per_page' => $per_page,
                'page' => isset($_GET['page'])?$_GET['page']:1
            ),
            'where' => $where,
        );
        $acobj = Qihaoservice::get_instance();
        $return_data['count'] = $acobj->count($query_struct_default);        //统计数量
        
		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => $return_data['count'],
			'items_per_page' => $query_struct_default['limit']['per_page'],    
		));

        $query_struct_current['limit']['page'] = $this->pagination->current_page;
        
        $return_data['list'] = $acobj->query_assoc($query_struct_default);
        
        
        //彩种配置
        $lottconfig = Kohana::config("ticket_type.type");
        $return_data['lottconfig'] = $lottconfig;

		$return_data['status'] = is_null($status)?'dlt':$status;
		
		
		$this->template->content = new View("lottnum/qihao", $return_data);
		//$this->template->content->where = $where_view;
	}
	/**
	 * 添加期号
	 * @param $type
	 */
	public function add($type='dlt',$id=null){
		$data = array();
		$data['type'] = $type;
		if(intval($id)){
			$data['id'] = $id;
		}
		
		if(!is_null($id)){
			$data['data'] = Qihaoservice::get_instance()->get_qihao_by_id($id);
		}
		
		$lotyid = lottnum::getlottid($type);
		//彩种配置
        $lottconfig = Kohana::config("ticket_type.type");
        $data['lottconfig'] = $lottconfig;
        $data['lotyid']     = $lotyid;
        //彩种奖项配置
        $bonusconfig = Kohana::config("lottnum.bonusinfo");
        $thislott    = $bonusconfig[$type];
        $data['bonusconfig'] = $thislott;
        
        
		$this->template->content = new View("lottnum/qihao_add", $data);
	}
	/**
	 * 
	 * @param unknown_type $qid
	 */
	public function addok($type='dlt',$qid=''){
		if(empty($_POST)) {
			remind::set("提交数据出!",'/lottnum/qihao/add/'.$type.'/'.$qid,'error');
		}
		
		if(intval($qid)){ //edit
			//basic
			$qhdata = array();
			$qhdata['qihao'] = $this->input->post('qihao');
			$qhdata['endtime'] = $this->input->post('endtime');
			$qhdata['fendtime'] = $this->input->post('fendtime');
			$qhdata['dendtime'] = $this->input->post('dendtime');
			$qhdata['ktime'] = $this->input->post('ktime');
			$qhdata['isnow'] = $this->input->post('isnow');
			$qhdata['buystat'] = $this->input->post('buystat');
			$qhdata['cpstat'] = $this->input->post('cpstat');
			$qhdata['lotyid'] = $this->input->post('lotyid');
			$qhdata['ctime'] = date("Y-m-d H:i:s");
			$qhdata['id'] = $qid;
			$flag = Qihaoservice::get_instance()->updateqihao($qhdata);
			if($flag==1){
				//ext
				$ext = array();
				$bonusconfig = Kohana::config("lottnum.bonusinfo");
	            $thislott    = $bonusconfig[$type];
	            
	            //$ext['limitcode'] = strlen($this->input->post('limitcode'))>3?$this->input->post('limitcode'):'';
	            $ext['limitcode'] = str_replace(array("\n", "\r\n") , ",", $this->input->post('limitcode'));
	            $ext['awardnum'] = $this->input->post('awardnum');
	            $sales = $this->input->post('sales');
	            $acc   = $this->input->post('acc');
	            if(!empty($sales)|!empty($acc)){
	            	$ext['salesacc'] = $sales."|".$acc;
	            }
	            $ext['bonusinfo'] = '';
	            foreach ($thislott as $k=>$v){
	            	$ext['bonusinfo'].= $k.",".$this->input->post('zn'.$k).",".$this->input->post('zd'.$k).";";
	            }
	            $ext['bonusinfo'] = substr($ext['bonusinfo'],0,-1);
	            $ext['lotyid'] = $this->input->post('lotyid');
	            $ext['qihao'] = $this->input->post('qihao');
	            $ext['qid']  = $qid;
	            $ext['ctime'] = date("Y-m-d H:i:s");
	            Qihaoservice::get_instance()->updateext($ext);
	            remind::set($qhdata['qihao']."期号修改成功!",'/lottnum/qihao/index/'.$type.'/','success');
			}elseif($flag==-1){
				remind::set($qhdata['qihao']."期不存在!",'/lottnum/qihao/index/'.$type.'/','error');
			}else{
				remind::set("提交数据出!",'/lottnum/qihao/index/'.$type.'/','error');
			}
			
		}else{ //add
			//basic
			$qhdata = array();
			$qhdata['qihao'] = $this->input->post('qihao');
			$qhdata['endtime'] = $this->input->post('endtime');
			$qhdata['fendtime'] = $this->input->post('fendtime');
			$qhdata['dendtime'] = $this->input->post('dendtime');
			$qhdata['ktime'] = $this->input->post('ktime');
			$qhdata['isnow'] = $this->input->post('isnow');
			$qhdata['buystat'] = $this->input->post('buystat');
			$qhdata['lotyid'] = $this->input->post('lotyid');
			$qhdata['cpstat'] = $this->input->post('cpstat');
			$qhdata['ctime'] = date("Y-m-d H:i:s");
			//$ext['limitcode'] = str_replace(array("\n", "\r\n") , ",", $this->input->post('limitcode'));
			
			
			$flag = Qihaoservice::get_instance()->addqihao($qhdata);
			if($flag>0){
				//ext
				$ext = array();
				$bonusconfig = Kohana::config("lottnum.bonusinfo");
	            $thislott    = $bonusconfig[$type];
	            //$ext['limitcode'] = strlen($this->input->post('limitcode'))>3?$this->input->post('limitcode'):'';
	            $ext['awardnum'] = $this->input->post('awardnum');
	            $sales = $this->input->post('sales');
	            $acc   = $this->input->post('acc');
	            if(!empty($sales)|!empty($acc)){
	            	$ext['salesacc'] = $sales."|".$acc;
	            }
	            $ext['bonusinfo'] = '';
	            foreach ($thislott as $k=>$v){
	            	$ext['bonusinfo'].= $k.",".$this->input->post('zn'.$k).",".$this->input->post('zd'.$k).";";
	            }
	            $ext['bonusinfo'] = substr($ext['bonusinfo'],0,-1);
	            $ext['lotyid'] = $this->input->post('lotyid');
	            $ext['qihao'] = $this->input->post('qihao');
	            $ext['qid']  = $flag;
	            $ext['ctime'] = date("Y-m-d H:i:s");
	            Qihaoservice::get_instance()->addext($ext);
				remind::set($qhdata['qihao']."期号添加成功!",'/lottnum/qihao/index/'.$type.'/','success');
			}elseif($flag==-1){
				remind::set($qhdata['qihao']."期已存在!",'/lottnum/qihao/index/'.$type.'/','error');
			}else{
				remind::set("提交数据出!",'/lottnum/qihao/index/'.$type.'/','error');
			}
		}
	}
	
	public function formatbonus($data,$type='dlt'){
		//pass
	}
	
	public function del($type='dlt',$id){
		$flag = Qihaoservice::get_instance()->del($id);
		if($flag){
			remind::set("删除成功!",'/lottnum/qihao/index/'.$type.'/','success');
		}else{
			remind::set("删除失败!",'/lottnum/qihao/index/'.$type.'/','error');
		}
	}
	
   public function batch_delete($type='dlt'){
   	    $qids = $this->input->post('qids');
   		foreach ($qids as $val){
   			Qihaoservice::get_instance()->del($val);
   		}
		remind::set("删除成功!",'/lottnum/qihao/index/'.$type.'/','success');
	}
	/**
	 * 抓取开奖信息
	 */
   public function getkjinfo($type='dlt',$expect=''){
   	$lottnumconfig = Kohana::config('lottnum');
   	$urixmls       = $lottnumconfig['kjxml'];
   	$uri           = $urixmls[$type];
   	$result = array();
   	if($uri){
   		$xmltext = file_get_contents($uri);
   		$parseobj =  xmlparse::get_instance();
   		$xmlarr   = $parseobj->xml2array($xmltext);
   		foreach ($xmlarr['row'] as $val){
			$tmparr = $val['@attributes'];
			if($tmparr['expect']==$expect){
				$opencode = $tmparr['opencode'];
				$result['sale']     = $tmparr['Sale'];
				$result['acc']      = $tmparr['BonusBalance'];
				$winnames =  explode(',',$tmparr['WinName']);
				$wincount =  explode(',',$tmparr['WinCount']);
				$winmoney =  explode(',',$tmparr['WinMoney']);
				
				$names = array();
				$counts = array();
				$bonus  = array();
				foreach ($winnames as $key=>$val){
					if($key==9) break;
					$nkey = 9 + $key;
					array_push($names,$val);
					array_push($counts,$wincount[$key]);
					array_push($bonus,$winmoney[$key]);
					if(isset($winnames[$nkey])){
						array_push($names,$winnames[$nkey]);
						array_push($counts,$wincount[$nkey]);
						array_push($bonus,$winmoney[$nkey]);
					}	
				}
				$result['names']    = $names;
				$result['counts']   = $counts;
				$result['bouns']    = $bonus;
				$result['opencode'] = str_replace(array(' + ',' ','-'),array('|',',',','),$opencode);
				
			}
   		}
   	}
   	exit(json_encode($result));
   }
   
   
 
}