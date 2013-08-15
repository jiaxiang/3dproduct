<?php defined('SYSPATH') OR die('No direct access allowed.');
class Auto_order_Controller extends Controller{
	//已经启动的自动跟单
	public static $loty_auto = array(1);
	public static $method_auto = array("1"=>array("1","2","3","4"));

	public static $errorDesc = array();
	public  $job = null;
	public  $log = null;
	public  $plan = null;
	public  $plans_basic_obj = null;
	public  $plans_obj = null;
	public  $jczq_obj = null;
	public  $jclq_obj = null;
	public  $sfc_obj = null;
	public function __construct()
	{
		parent::__construct();
		$this->job = Auto_order_job_Model::get_instance();
		$this->log = Auto_order_log_Model::get_instance();
		//$this->plan = Auto_order_plan_Model::get_instance();

		$this->plans_basic_obj = Plans_basicService::get_instance();
        $this->jczq_obj = Plans_jczqService::get_instance();
		$this->jclq_obj = Plans_jclqService::get_instance();
		$this->sfc_obj = Plans_sfcService::get_instance();
		$this->bjdc_obj = Plans_bjdcService::get_instance();
		$this->errorDesc = Kohana::config('errorcode.error');

	}

	public function getAllJob ($lotyid,$playid,$fuid) {
		$auto_roder_job_obj = $this->job
			->where(array(	"lotyid"=>$lotyid,
							"playid"=>$playid,
							"fuid"=>$fuid,
							"stat"=>1
			))
			->find_all()->as_array();	
		return $auto_roder_job_obj;
	}
	/*
	* Function: 根据配置自动跟单处理
	* Param   : 
	* Return  : 
	*/
    public function index(){
		$loty = Kohana::config('ticket_type.type');
		foreach ($loty as $lotyid=>$val){
		    if(in_array($lotyid,self::$loty_auto)){
				foreach (self::$method_auto[$lotyid] as $playid){
					$this->dowithByLPid($lotyid,$playid);
				}
			}
		}
    }
	/*
	* Function: 竞彩足球自动跟单处理
	* Param   : 
	* Return  : 
	*/
	public function jczq() {
		//竞彩足球下的玩法
		$play_method = array("1","2","3","4");
		foreach ($play_method as $playid){
		    $this->dowithByLPid(1,$playid);
		}
	}
	/*
	* Function: 竞彩篮球自动跟单处理
	* Param   : 
	* Return  : 
	*/
	public function jclq() {
		//竞彩足球下的玩法
		$play_method = array("1","2","3","4");
		foreach ($play_method as $playid){
		    $this->dowithByLPid(6,$playid);
		}
	}
	/*
	* Function: 竞彩足球自动跟单处理
	* Param   : 
	* Return  : 
	*/
	public function bjdc() {
		//竞彩足球下的玩法
		$play_method = array("501","502","503","504","505");
		foreach ($play_method as $playid){
		    $this->dowithByLPid(7,$playid);
		}
	}
	/*
	* Function: 竞彩足球自动跟单处理
	* Param   : 
	* Return  : 
	*/
	public function sfc() {
		//竞彩足球下的玩法
		$play_method = array("1","2","3","4");
		foreach ($play_method as $playid){
		    $this->dowithByLPid(2,$playid);
		}
	}
	/*
	* Function: 自动跟单单玩法处理
	* Param   : 彩种：@lotyid  玩法：@playid
	* Return  : 
	*/
	public function dowithByLPid ($lotyid,$playid) {
		if(empty($lotyid)||empty($playid)){
			$servers = $this->input->server('argv');
			$playid = $servers[3];
			$lotyid = $servers[2];
		}
		
		$databasic = array();
        $data = array();
	    //得到玩法下的所有启用状态的自动跟单JOB
		$all_plan = $this->getAllPlan($lotyid,$playid);

		foreach ($all_plan as $aplanObj){
		    $aplan = $aplanObj->as_array();
			$auto_roder_jobs= $this->getAllJob($lotyid,$playid,$aplan['user_id']);
			foreach ($auto_roder_jobs as $aJobObj){
			    //处理该自动跟单
				$ajob=$aJobObj->as_array();
				$jobid = $ajob['id'];
				$uid = $ajob['uid'];
				$fuid = $ajob['fuid'];
				switch ($lotyid) {
				case '1':
					$pordernum = $aplan['basic_id'];
					$this->plans_obj=$this->jczq_obj;
				break;
				case '2':
					$pordernum = $aplan['basic_id'];
					$this->plans_obj=$this->sfc_obj;
				break;
				case '6':
					$pordernum = $aplan['basic_id'];
					$this->plans_obj=$this->jclq_obj;
				break;
				case '7':
					$pordernum = $aplan['basic_id'];
					$this->plans_obj=$this->bjdc_obj;
				break;
				default:
				    $pordernum = $aplan['order_num'];
				break;
				}

				$pid = $aplan['id'];


				$iscontinue=$this->log->where(array('pid'=>$pid,'uid'=>$uid))->find()->as_array();
				if ($iscontinue['id']) continue;				
				
				//竞彩足球   其它彩种还要扩展这里
				$result = $this->plans_obj->get_by_order_id($pordernum);
				
				switch ($lotyid) {
					case 2:
						$result['surplus']=$result['buyed'];
						$result['buyed'] = $result['copies']-$result['surplus'];
						break;
				}

				$errcode = '200';
				//验证余额
				$userobj = user::get_instance();
				$usermoney = $userobj->get_user_money($uid);


				if ($usermoney < $ajob['money'])
				{
					$errcode = '101';//用户余额不足	
				}
				
				//验证是否满员
				if ($result['surplus'] <= 0)
				{
					$errcode = '102' ;//'此方案已满员无法购买！';
				}
				//验证可认够的钱是否够
				if ($result['surplus']*$result['price_one'] < $ajob['money'])
				{
					$errcode = '103';//方案可购金额小于你的订制金额	
				}
				//方案是否限定范围
				if ($ajob['limitswitch']) {
				    if ($ajob['maximum']<$result['total_price']) {
				        $errcode = '104';//方案金额大于你的限定金额最大值
				    }
					if ($ajob['minimum']>$result['total_price']) {
				        $errcode = '105';//方案金额小于你的限定金额的最小值
				    }
				}

				//检查方案日期是否结束
				if (strtotime($result['time_end']) < time())
				{
					$errcode = '106';//'此方案已到期无法购买！';
				}

				//检查是否是合买对象
				if (!empty($result['friends'])&&$result['friends']!='all')
				{
					$errcode = '107';//'此方案只有固定的彩友可以合买！';
			
				}	
				
				$config = Kohana::config('database.default');
				extract($config['connection']);
				$mysqli = new mysqli($host, $user, $pass, $database, $port);
				if (mysqli_connect_errno()) echo '数据异常!';

				$mysqli->query("SET NAMES 'utf8'");
				if($errcode=='200'){
//					echo 'ssss';
					
					$query = "call auto_order('".$jobid."','".$pid."','".$lotyid."','".$playid."')";
//echo $query;
					if($mysqli->multi_query($query)){
						do {
							if ($rest = $mysqli->store_result()) {

								while ($row = $rest->fetch_row()) {
								
									 if($row[0]!='200'){
										$sql = "INSERT INTO `auto_order_logs` (`lotyid`,`playid`, `fuid`, `funame`, `uid`, `uname`, `rgmoney`, `state`,
					`isuccess`, `errcode`,	`ctime`, `ordernum`, `pid`)
					VALUES	('".$lotyid."', '".$playid."','".$fuid."','".$ajob['funame']."','".$uid."','".$ajob['uname']."',0, '1','0', '".$errcode."',NOW(), '".$pordernum."', ".$pid.");";
					$mysqli->query($sql);

									 }
									 else{
									     if ($row[1]==100) {
									        
											 //扯分彩票存入彩票表,打印彩票的格式/更新状态
											 $this->plans_obj->get_tickets($pid, $result['play_method'], $result['codes'], $result['typename'], $result['special_num'], $result['rate'], $result['basic_id']);

											//更新方案状态为未出票(是父类的方案)
											$this->plans_obj->update_status($pid, 1);

									     }
									 }
								 }
								$rest->close();
							 }
						 } while ($mysqli->next_result());
			
					}

				}
				else
				{
			
					$sql = "INSERT INTO `auto_order_logs` (`lotyid`,`playid`, `fuid`, `funame`, `uid`, `uname`, `rgmoney`, `state`,
					`isuccess`, `errcode`,	`ctime`, `ordernum`, `pid`)
					VALUES	('".$lotyid."', '".$playid."','".$fuid."','".$ajob['funame']."','".$uid."','".$ajob['uname']."',0, '1','0', '".$errcode."',NOW(), '".$pordernum."', ".$pid.");";
					//echo $sql;
					$mysqli->query($sql);
		
				}
			}

		}

	}

	
	public function getAllPlan($lotyid,$playid) {
				switch ($lotyid) {
				case '1':
					 	$allplan = ORM::factory('plans_jczq')
						->where(array(	"status"=>0,
										"plan_type"=>1,
										"play_method"=>$playid

						))->find_all();
				break;
				case '2':
					$allplan = ORM::factory('plans_sfc')
					->where(array(	"status"=>0,
					"parent_id"=>0,
					"play_method"=>$playid
				
					))->find_all();
					break;
				case '6':
					$allplan = ORM::factory('plans_jclq')
					->where(array(	"status"=>0,
					"plan_type"=>1,
					"play_method"=>$playid
				
					))->find_all();
					break;
				case '7':
					$allplan = ORM::factory('plans_bjdc')
					->where(array(	"status"=>0,
					"plan_type"=>1,
					"play_method"=>$playid
				
					))->find_all();
				break;

				default:
				     	$allplan = ORM::factory('plans_basic')
						->where(array(	"status"=>0,
										"plan_type"=>1,
										"ticket_type"=>$lotyid,
										"play_method"=>$playid

						))->find_all();
				break;
				}
	   
			return $allplan;
			
	}

}