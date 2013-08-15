<?php defined('SYSPATH') OR die('No direct access allowed.');

class Order_Controller extends Template_Controller {
	
	protected static $pdb = null;

	public function __construct()
	{
		parent::__construct();
		role::check('lottnum_qihao');
	}
	
	/**
	 * 方案列表
	 */
	public function index($status=null){
		$per_page = controller_tool::per_page();
		
		$lotyid     = lottnum::getlottid($status);
		$isnowissue = Qihaoservice::get_instance()->getisnow($lotyid);
		$nowissue   = $isnowissue['qihao'];
		
		$where = array('lotyid'=>$lotyid,'qihao'=>$nowissue);
		
		if($_GET){
			$issue = $this->input->get('issue')?$this->input->get('issue'):-1;
			$stat  = $this->input->get('stat')?$this->input->get('stat'):-1;
			$bid  = $this->input->get('bid');
			$wtype = $this->input->get('wtype');
			if($issue!=-1) {
				$where['qihao'] = $issue;
			}else{
				unset($where['qihao']);
			}
			
			if($stat==0||$stat==2) {
				$where['isfull'] = $stat;
			}elseif($stat==1){
				$where['restat'] = $stat;
			}elseif($stat==3){
				$where['cpstat'] = 2;
			}
			if($bid){
				$where['basic_id'] = $bid;
			}
			if($wtype>0){
				$where['wtype'] = $wtype;
			}
		}
		
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
        
        $acobj = Plans_lotty_orderService::get_instance();
        $return_data['count'] = $acobj->count($query_struct_default);        //统计数量
        
        if(isset($stat) && $stat == 3){
        	$return_data['sum'] = $acobj->query_sum($query_struct_default,array('allmoney','afterbonus'));
        }
        
		/* 调用分页 */
		$this->pagination = new Pagination(array(
			'total_items'    => $return_data['count'],
			'items_per_page' => $query_struct_default['limit']['per_page'],    
		));

        $query_struct_default['limit']['page'] = $this->pagination->current_page;
        
        $return_data['list'] = $acobj->query_assoc($query_struct_default);
        
        
        //彩种配置
        $lottconfig = Kohana::config("ticket_type.type");
        $return_data['lottconfig'] = $lottconfig;
        //方案状态说明
        $lottnumconfig = Kohana::config("lottnum");
        $return_data['cpstatinfo'] = $lottnumconfig['cpstatinfo'];
        $return_data['restatinfo'] = $lottnumconfig['restatinfo'];
        $return_data['isfullinfo'] = $lottnumconfig['isfullinfo'];
        

		$return_data['status'] = is_null($status)?'dlt':$status;
		//近10期期号列表
		$return_data['issues'] = $this->getissue(is_null($status)?'dlt':$status);
		$return_data['theissue'] = isset($issue)?$issue:$nowissue;
		
		$return_data['site_config'] = Kohana::config('site_config.site');
		$host = $_SERVER['HTTP_HOST'];
		$dis_site_config = Kohana::config('distribution_site_config');
		if (array_key_exists($host, $dis_site_config) == true && isset($dis_site_config[$host])) {
			$return_data['site_config']['site_title'] = $dis_site_config[$host]['site_name'];
			$return_data['site_config']['keywords'] = $dis_site_config[$host]['keywords'];
			$return_data['site_config']['description'] = $dis_site_config[$host]['description'];
		}
		
		$this->template->content = new View("lottnum/order", $return_data);
	}
	
	
	public function getissue($type='dlt'){
		
		$lotyid = lottnum::getlottid($type);
		$where = array('lotyid'=>$lotyid);
		$query_struct_default = array (
            'orderby' => array (
                'id' => 'DESC' 
            ), 
            'limit' => array (
                'per_page' => 20,
                'page' => 1
            ),
            'where' => $where,
        );
        
        $qhobj  = Qihaoservice::get_instance();
        $issues =$qhobj->query_assoc($query_struct_default);
        return $issues;
	}
	
	/**
	 * 撤销订单
	 * @param $type 彩种
	 * @param $pid 方案编号
	 */
	public function rev($type='dlt',$pid=null){
		if(intval($pid)*1<1){
			remind::set("方案编号 为空不可操作!",'/lottnum/order/index/'.$type.'/?'.http_build_query($_GET),'error');
		}
		
		$this->loaddb();
		$gcancelflag = true;
		$prow = self::$pdb->query("select uid,nums,rgnum,baodi,baodimoney,restat,onemoney from plans_lotty_orders where id='".$pid."'")
		                  ->result_array(FALSE);
		if($prow[0]['restat']==1){
			remind::set("已做过撤单处理，不可重复撤单!",'/lottnum/order/index/'.$type.'/?'.http_build_query($_GET),'error');
		}
		$grows =  self::$pdb->query("select id,restat from sale_prousers where pid='".$pid."'")
		                    ->result_array(FALSE);  
		/*
		 * 循环调用存储过程出现Commands out of sync; you can't run this command now 
		 * 暂时用mysqli的原生方法来做
		 */
		$config = Kohana::config('database.default');
		extract($config['connection']);
		$mysqli = new mysqli($host, $user, $pass, $database, $port);
		if (mysqli_connect_errno())
		{
		    remind::set("数据库异常!",'/lottnum/order/index/'.$type.'/?'.http_build_query($_GET),'error');
		}
		
	    if(is_array($grows)){
	    	foreach ($grows as $row){
	    		if($row['restat']==0){
					if ($mysqli->multi_query("call rev_order(".$row['id'].", -- 根单编号 
			                                    1, -- 撤单类型 1 方案撤单 2 跟单人撤销
							                    2011, -- 交易流水号
			                                   @stat)"))
					{
					    do {
					        if ($result = $mysqli->store_result()) {
					            while ($row = $result->fetch_row()) {
					                 if($row[0]!='succ') $gcancelflag = false;
					             }
					            $result->close();
					         }
					     } while ($mysqli->next_result());
					}else{
						$gcancelflag = false;
					}
	    		}
	    	}
		}
		
	   //操作方案表及清保
	   if($prow[0]['baodi']==1){
	    	if($mysqli->multi_query("call clearbaodi(".$pid.", -- 方案编号
                         0, -- 清保类型 0 只清保 1 清保加认购
                         @stat)")){
	    				do {
					        if ($result = $mysqli->store_result()) {
					            while ($row = $result->fetch_row()) {
					                 if($row[0]==-1) $gcancelflag = false;
					             }
					            $result->close();
					         }
					     } while ($mysqli->next_result());
	    	
            }else{
            	$gcancelflag = false;
            }
	    }
	    
	    if($gcancelflag){
	    	$crow = self::$pdb->query("update plans_lotty_orders set restat=1 where id='".$pid."'");
	    	remind::set("撤单成功!",'/lottnum/order/index/'.$type.'/?'.http_build_query($_GET),'success');
	    }else{
	    	//$result = array('stat'=>108,'info'=>'撤单失败!');
	    	remind::set("撤单失败!",'/lottnum/order/index/'.$type.'/?'.http_build_query($_GET),'error');
	    }
	}
	/**
	 * 查看方案详情
	 * @param  $type
	 * @param  $pid
	 */
	public function view($type='dlt',$pid){
		//pass
		$data = array();
		if(intval($pid)*1<1){
			remind::set("方案编号 为空不可操作!",'/lottnum/order/index/'.$type.'/?'.http_build_query($_GET),'error');
		}
		$this->loaddb();
		$prow = self::$pdb->query("select * from plans_lotty_orders where id='".$pid."'")
		                  ->result_array(FALSE);
		$data =  $prow[0];              
		                  
		$this->template->content = new View("lottnum/order_view", $data);
	}
	
	public function clear($type='dlt',$pid){
	   if(intval($pid)*1<1){
			remind::set("方案编号 为空不可操作!",'/lottnum/order/index/'.$type.'/?'.http_build_query($_GET),'error');
		}
		$this->loaddb();
		$prow = self::$pdb->query("select uid,nums,rgnum,baodi,baodimoney,restat,onemoney from plans_lotty_orders where id='".$pid."'")
		                  ->result_array(FALSE);
		if($prow[0]['baodi']==1){
			 $crow = self::$pdb->query("call clearbaodi(".$pid.", -- 方案编号
                         1, -- 清保类型 0 只清保 1 清保加认购
                         @stat)")->result_array(false);
			 if($crow[0]['result']!=-1){
			 	remind::set("清保成功!",'/lottnum/order/index/'.$type.'/?'.http_build_query($_GET),'success');
			 }else{
			 	remind::set("操作失败 !",'/lottnum/order/index/'.$type.'/?'.http_build_query($_GET),'error');
			 }
		}else{
			remind::set("方案不存在，或已做过清保处理 !",'/lottnum/order/index/'.$type.'/?'.http_build_query($_GET),'error');
		}
	}
	
	public function cpstat($type="dlt",$stat=2){
		if(empty($_POST)){
			remind::set("未提方案编号!",'/lottnum/order/index/'.$type.'/?'.http_build_query($_GET),'error');
		}
		$bstat = $stat==0?1:$stat;
		$this->loaddb();
		$pids = $this->input->post('pids');
		foreach ($pids as $pid){
			$row  = self::$pdb->query("update plans_lotty_orders set cpstat='$stat' where id='".$pid."'");
			$rows = self::$pdb->query("select pbid from sale_prousers where pid='".$pid."' and restat=0")->result_array(false);
			foreach ($rows as $val){
				self::$pdb->query("update plans_basics set status='".$bstat."' where id='".$val['pbid']."'");
			}
		}
		remind::set("操作成功!",'/lottnum/order/index/'.$type.'/?'.http_build_query($_GET),'success');
		
	}
	
	public function loaddb(){
		if(!self::$pdb){
		 	self::$pdb = Database::instance();
		}
	}
	
}
