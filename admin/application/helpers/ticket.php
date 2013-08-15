<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 彩票操作
 */
class ticket_Core {
    private static $instance = NULL;
    private $send_server_url = '';                    //发送打印彩票命令接口网址
    private $recive_server_url = '';                  //接收彩票返回命令接口网址  
    private $username = '';                           //接口帐号 
    private $password = '';                           //接口密码
    
    static public $zhushufenpei = array(
    	'1' 	=> array(1,0,0,0,0,0,0,0),
    	'21' 	=> array(0,1,0,0,0,0,0,0),
    	'31' 	=> array(0,0,1,0,0,0,0,0),
    	'41' 	=> array(0,0,0,1,0,0,0,0),
    	'51' 	=> array(0,0,0,0,1,0,0,0),
    	'61' 	=> array(0,0,0,0,0,1,0,0),
    	'71' 	=> array(0,0,0,0,0,0,1,0),
    	'81' 	=> array(0,0,0,0,0,0,0,1),
    	'23' 	=> array(2,1,0,0,0,0,0,0),
    	'36' 	=> array(3,3,0,0,0,0,0,0),
    	'37' 	=> array(3,3,1,0,0,0,0,0),
    	'410' 	=> array(4,6,0,0,0,0,0,0),
    	'414' 	=> array(4,6,4,0,0,0,0,0),
    	'415' 	=> array(4,6,4,1,0,0,0,0),
    	'515'	=> array(5,10,0,0,0,0,0,0),
    	'525' 	=> array(5,10,10,0,0,0,0,0),
    	'530' 	=> array(5,10,10,5,0,0,0,0),
    	'531' 	=> array(5,10,10,5,1,0,0,0),
    	'621' 	=> array(6,15,0,0,0,0,0,0),
    	'641' 	=> array(6,15,20,0,0,0,0,0),
    	'656' 	=> array(6,15,20,15,0,0,0,0),
    	'662' 	=> array(6,15,20,15,6,0,0,0),
    	'663' 	=> array(6,15,20,15,6,1,0),
    	'7127' 	=> array(7,21,35,35,21,7,1,0),
    	'8255' 	=> array(8,28,56,70,56,28,8,1),
    	'33' 	=> array(0,3,0,0,0,0,0,0),
    	'34' 	=> array(0,3,1,0,0,0,0,0),
    	'46' 	=> array(0,6,0,0,0,0,0,0),
    	'411' 	=> array(0,6,4,1,0,0,0,0),
    	'510' 	=> array(0,10,0,0,0,0,0,0),
    	'520' 	=> array(0,10,10,0,0,0,0,0),
    	'526' 	=> array(0,10,10,5,1,0,0,0),
    	'615' 	=> array(0,15,0,0,0,0,0,0),
    	'635' 	=> array(0,15,20,0,0,0,0,0),
    	'650' 	=> array(0,15,20,15,0,0,0,0),
    	'657' 	=> array(0,15,20,15,6,1,0,0),
    	'7120' 	=> array(0,21,35,35,21,7,1,0),
    	'8247' 	=> array(0,28,56,70,56,28,8,1),
    	'44' 	=> array(0,0,4,0,0,0,0,0),
    	'45' 	=> array(0,0,4,1,0,0,0,0),
    	'516' 	=> array(0,0,10,5,1,0,0,0),
    	'620' 	=> array(0,0,20,0,0,0,0,0),
    	'642' 	=> array(0,0,20,15,6,1,0,0),
    	'55' 	=> array(0,0,0,5,0,0,0,0),
    	'56' 	=> array(0,0,0,5,1,0,0,0),
    	'622' 	=> array(0,0,0,15,6,1,0,0),
    	'735' 	=> array(0,0,0,35,0,0,0,0),
    	'870' 	=> array(0,0,0,70,0,0,0,0),
    	'66' 	=> array(0,0,0,0,6,0,0,0),
    	'67' 	=> array(1,0,0,0,6,1,0,0),
    	'721' 	=> array(0,0,0,0,21,0,0,0),
    	'856' 	=> array(0,0,0,0,56,0,0,0),
    	'77' 	=> array(0,0,0,0,0,7,0,0),
    	'78' 	=> array(0,0,0,0,0,7,1,0),
    	'828' 	=> array(0,0,0,0,0,28,0,0),
    	'88' 	=> array(0,0,0,0,0,0,8,0),
    	'89' 	=> array(0,0,0,0,0,0,8,1)
    );
    
    // 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    
    public function __construct()
    {
        $this->send_server_url = '';
        $this->recive_server_url = '';
        $this->username = '';
        $this->password = '';
    }
    
    /**
     * 计算出彩票的注数 适用于竞彩部分
     * Enter description here ...
     * @param unknown_type $code
     * codes:46:[1,2]/47:[胜]/48:[胜]/49:[胜]
     * @param unknown_type $chuanfa
     * 2串1
     */
    public function zhushu($codes, $chuanfa) {
    	$arrcode = explode('/', $codes);
    	if ($chuanfa == '单关') {
    		$chuan_code = 1;
    	}
    	else {
    		$chuans = explode('串', $chuanfa);
    		$chuan_code = $chuans[0].$chuans[1];
    	}
    	$zhushu_info = self::$zhushufenpei[$chuan_code];
    	$return = 0;
    	for ($i = 0; $i < count($zhushu_info); $i++) {
    		if ($zhushu_info[$i] > 0) {
    			$j = $i + 1;
    			$r = tool::get_combination($arrcode, $j, '/');
    			
    			for ($k = 0; $k < count($r); $k++) {
    				$code_t1 = explode('/', $r[$k]);
    				$match_re = 1;
    				for ($l = 0; $l < count($code_t1); $l++) {
    					$t1 = explode(':', $code_t1[$l]);
						$match_no = $t1[0];
						$no_len = strlen($match_no)+2;
						$t2 = substr(substr($code_t1[$l], $no_len), 0, -1);
						$t3 = explode(',', $t2);
						$match_re *= count($t3);
						
    				}
    				$return += $match_re;
    			}
    		}
    	}
    	return $return;
    	
    }
    
    /*
     * 获取打印机返回信息
     */
    public function recive_ticket()
    {
        /*
         * 此处为接受操作,及时更新彩票信息
         */
    }
    
    
    /*
     * 打印彩票命令
     */
    public function send_ticket()
    {
        /*
         * 
         * 此处为发送操作
         * 
         */
    }
    
    
    /*
     * 插入彩票表
     * @param  integer  $plan_id  方案id(各大类的方案存储id,非基表id)
     * @param  integer  $ticket_type  彩票id
     * @param  integer  $play_method  玩法id
     * @param  string   $codes	彩票代码 
     * @param  integer  $rate  倍数
     * @return boolean  TRUE OR FALSE
     */
    public function crate_ticket($plan_id, $ticket_type, $play_method, $codes, $rate, $ordernum, $money = 0 )
    {
        if(empty($plan_id) || empty($ticket_type) || empty($play_method) || empty($codes) || empty($rate) || empty($ordernum))
            return FALSE;
        $maxlng = 99;
        
        //大于99倍时则需要重新设置
        if ($rate > $maxlng)
        {
            $maxloop = intval($rate / $maxlng);
            $surplus = $rate % $maxlng;
            $each_money = $money / $rate;
            
            for ($i = 0; $i < $maxloop; $i++)
            {
                $data = array();    
                $data['plan_id'] = $plan_id;
                $data['ticket_type'] = $ticket_type;
                $data['play_method'] = $play_method;
                $data['codes'] = $codes;
                $data['rate'] = $maxlng;
                $data['money'] = $each_money * $maxlng;
                $data['order_num'] = $ordernum;
                $this->add($data);
            }
            if ($surplus > 0)
            {
                $data = array();
                $data['plan_id'] = $plan_id;
                $data['ticket_type'] = $ticket_type;
                $data['play_method'] = $play_method;
                $data['codes'] = $codes;
                $data['rate'] = $surplus;
                $data['money'] = $each_money * $surplus;
                $data['order_num'] = $ordernum;
                $this->add($data);
            }
        }
        else
        {
            $data = array();    
            $data['plan_id'] = $plan_id;
            $data['ticket_type'] = $ticket_type;
            $data['play_method'] = $play_method;
            $data['codes'] = $codes;
            $data['rate'] = $rate;
            $data['money'] = $money;
            $data['order_num'] = $ordernum;
            $this->add($data);
        }
    }
    
    
    /*
     * 添加入库
     */
    public function add($data)
    {
        $obj = ORM::factory('ticket_num');
	    if (!$obj->validate($data))
	        return FALSE;

	    $obj->plan_id = $data['plan_id'];
	    $obj->ticket_type = $data['ticket_type'];
	    $obj->play_method = $data['play_method'];
        $obj->codes = $data['codes'];
        $obj->rate = $data['rate'];
        $obj->money = $data['money'];
        $obj->order_num = $data['order_num'];
        $obj->status = 0;
        $obj->save();
        
        if ($obj->saved)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    
    
    
    /*
     * 兑奖操作作
     * 
     * 对彩票状态为已出票的彩票遍历检查是否中奖
     * 将分别对每种彩票操作,返回结果 奖金:array(), 参数(status:0,未开奖, 1 已开奖; bonus 奖金)
     */
    public function  check_bonus()
    {
        @set_time_limit(300);
        
        $obj = ORM::factory('match_data')->where('status', 2);
        $results = $obj->find_all();
        
        $jczq_obj = Plans_jczqService::get_instance();
        $sfc_obj = Plans_sfcService::get_instance();
        
        
        foreach ($results as $row)
        {
            $data = array();
            switch ($row['ticket_type'])
            {
                case 1:
                    $data = $jczq_obj->check_bonus($row);
                    break;
                case 2:
                    $data = $sfc_obj->check_bonus($row);
                    break;
                case 3:
                    //$data = 
                    break;
                case 4:
                    //$data = 
                    break;
            }
            
            if (!empty($data))
            {
                if ($data['status'] == 1)
                {
                    //更新彩票状态
                    $this->update_ticket($row['id'], $data['bonus']);
                }
            }
            
            
        }
        
        
    }
    
    
    /*
     * 更新彩票状态
     * @param  array  $ids  彩票id
     * @param  integer  $updateto  更新的状态值
     * @param  array  $constatus  状态满足的条件
     * @return integer 成功的数量
     */
    public function update_status($ids, $updateto, $constatus, $manager_id = 0)
    {
        $updateto = intval($updateto);
        if (empty($ids) || empty($constatus))
        {
            return FALSE;
        }
                
        $obj = ORM::factory('ticket_num');
        
        $i = 0;
        foreach ($ids as $rowid)
        {
            $obj->where("id", $rowid)->find();
            if ($obj->loaded)
            {
                if (in_array($obj->status, $constatus))
                {
                    $obj->status = $updateto;
                    
                    switch ($updateto)
                    {
                        case 1:
                           $obj->time_print = tool::get_date();
                        break;
                        case 2:
                           $obj->time_duijiang = tool::get_date();
                        break;
                        case 0:
                            $obj->time_print = NULL;
                        break;
                    }
                    $obj->time_lastaction = tool::get_date();
                    
                    if (!empty($manager_id))
                    {
                        $obj->manager_id = $manager_id;
                    }
                    $obj->save();
                    
                    if ($obj->saved)
                    {
                        $i++;
                    }
                }
            }
        }
        
        return $i;
    }
    
    
    
    /*
     * 更新彩票表状态及奖金
     */
    public function update_ticket($ticket_id, $bonus, $manager_id = 0)
    {
        $obj = ORM::factory('ticket_num', $ticket_id);
        if ($obj->loaded)
        {
            $obj->status = 2;                        //更新为已兑奖
            $obj->bonus = $bonus;
            $obj->manager_id = $manager_id;
            $obj->save();
            
            if ($obj->saved)
            {
                return TRUE;
            }
            else 
            {
                return FALSE;
            }
        }
        else 
        {
            return FALSE;
        }
        
    }
    
    /*
     * 更新彩票表状态及奖金
     */
    public function update_bonus($ticket_id, $bonus, $num, $password, $manager_id = 0)
    {
        $obj = ORM::factory('ticket_num', $ticket_id);
        if ($obj->loaded)
        {
            $obj->status = 2;                        //更新为已兑奖
            $obj->bonus = $bonus;
            $obj->manager_id = $manager_id;
            $obj->num = $num;
            $obj->password = $password;
            $obj->time_duijiang = tool::get_date();
            $obj->time_lastaction = tool::get_date();
            $obj->save();
            if ($obj->saved)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }
    
    /*
     * 根据id获取当前彩票表信息
     */
    public function get($ticket_id)
    {
        $obj = ORM::factory('ticket_num', $ticket_id);
        if ($obj->loaded)
        {
            return $obj->as_array();
        }
        else
        {
            return FALSE;
        }
    } 

    
    /*
     * 提交数据
     */
    function get_url($url){
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_HEADER, 0);
    	curl_setopt($curl, CURLOPT_TIMEOUT, 3);//超时时间
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	$data = curl_exec($curl);
    	if (strpos ($data, "\n") > 0){
    		$data = substr ($data, 0, strpos ($data, "\n"));       	
    	}
    	curl_close($curl);
    	return $data;
    }
    
    /*
     * 获取数据
     */
    function get_request_url($url){
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_HEADER, 0);
    	curl_setopt($curl, CURLOPT_TIMEOUT, 3);//超时时间
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    	$data = curl_exec($curl);
    	curl_close($curl);
    	if ($data === false){
    	    return false;
    	}
    	return $data;
    }
    
	/**
     * 根据方案id获得彩票信息
     * Enter description here ...
     * @param unknown_type $pid
     */
    function getTicketByPlanID($pid, $order_num) {
        $obj = ORM::factory('ticket_num');
    	$obj->select('*');
    	$obj->where('order_num', $order_num);
        $obj->where('plan_id', $pid);
        $obj->orderby('id', 'ASC');
        $results = $obj->find_all();
        $t_info = array();
        foreach ($results as $result) {
        	$t = $result->as_array();
        	$t_info[] = $t;
        }
        return $t_info;
    }
    
    /*
     * 根据订单号获得所有彩票
     */
    public function get_results_by_ordernum($order_num)
    {
        $obj = ORM::factory('ticket_num');
    	$obj->where('order_num', $order_num);
        $obj->orderby('id', 'ASC');
        $results = $obj->find_all();
        $return = array();
        foreach ($results as $result) {
        	$return[] = $result->as_array();
        }
        unset($results);
        return $return;
    }
    
    /**
     * 更新moreinfo
     * @param unknown_type $id
     * @param unknown_type $play_method
     * @param unknown_type $codes
     * @param unknown_type $ticket_type
     */
    public function update_jc_ticket_sp($id, $play_method, $codes, $ticket_type=1) {
    	require_once WEBROOT.'cron_script/SQL.php';
    	$sql_obj = new SQL();
    	$return = array();
    	$code_a = explode(';', $codes);
    	$code = $code_a[0];
    	$match_detail = explode('/', $code);
    	for ($i = 0; $i < count($match_detail); $i++) {
    		$match_info = explode('|', $match_detail[$i]);
    		$match_id = $match_info[0];
    		preg_match_all("/\[(.*)\]/", $match_info[1], $match_result, PREG_SET_ORDER);
    		$match_results = $match_result[0][1];
    		$match_results_a = explode(',', $match_results);
    		$match_result_sp = array();
    		$select_match_query = 'select comb,goalline from match_datas where ticket_type="'.$ticket_type.'" and play_type="'.$play_method.'" and match_id="'.$match_id.'" limit 1';
    		$sql_obj->query($select_match_query);
    		$match_data = $sql_obj->fetch_array();
    		$sp = $match_data['comb'];
    		$goalline = $match_data['goalline'];
    		//var_dump($sp);
    		$play_config = array();
    		if ($ticket_type == 1) {
    			switch ($play_method) {
    				case 1:
    					$play_config = array(
    					'3' => 'H',
    					'1' => 'D',
    					'0' => 'A',
    					);
    					break;
    				case 2:
    					$play_config = array(
    					'0' => '0',
    					'1' => '1',
    					'2' => '2',
    					'3' => '3',
    					'4' => '4',
    					'5' => '5',
    					'6' => '6',
    					'7' => '7',
    					);
    					break;
    				case 3:
    					$play_config = array(
    					'负其它' => '-1:-A',
    					'胜其它' => '-1:-H',
    					'平其它' => '-1:-D',
    					'0:0' => '00:00',
    					'0:1' => '00:01',
    					'0:2' => '00:02',
    					'0:3' => '00:03',
    					'0:4' => '00:04',
    					'0:5' => '00:05',
    					'1:0' => '01:00',
    					'1:1' => '01:01',
    					'1:2' => '01:02',
    					'1:3' => '01:03',
    					'1:4' => '01:04',
    					'1:5' => '01:05',
    					'2:0' => '02:00',
    					'2:1' => '02:01',
    					'2:2' => '02:02',
    					'2:3' => '02:03',
    					'2:4' => '02:04',
    					'2:5' => '02:05',
    					'3:0' => '03:00',
    					'3:1' => '03:01',
    					'3:2' => '03:02',
    					'3:3' => '03:03',
    					'4:0' => '04:00',
    					'4:1' => '04:01',
    					'4:2' => '04:02',
    					'5:0' => '05:00',
    					'5:1' => '05:01',
    					'5:2' => '05:02',
    					);
    					break;
    				case 4:
    					$play_config = array(
    					'0-0' => 'cc',
    					'0-1' => 'cb',
    					'0-3' => 'ca',
    					'1-0' => 'bc',
    					'1-1' => 'bb',
    					'1-3' => 'ba',
    					'3-0' => 'ac',
    					'3-1' => 'ab',
    					'3-3' => 'aa',
    					);
    					break;
    				default: break;
    			}
    		}
    		if ($ticket_type == 6) {
    			switch ($play_method) {
    				case 1:
    					$play_config = array(
    					'2' => 'H',
    					'1' => 'D',
    					);
    					break;
    				case 2:
    					$play_config = array(
    					'2' => 'H',
    					'1' => 'D',
    					);
    					break;
    				case 3:
    					$play_config = array(
    					'01' => 'u4e3bu80dc1-5',//主胜1-5
    					'02' => 'u4e3bu80dc6-10',//主胜6-10
    					'03' => 'u4e3bu80dc11-15',//主胜11-15
    					'04' => 'u4e3bu80dc16-20',//主胜16-20
    					'05' => 'u4e3bu80dc21-25',//主胜21-25
    					'06' => 'u4e3bu80dc26+',//主胜26+
    					'11' => 'u5ba2u80dc1-5',//客胜1-5
    					'12' => 'u5ba2u80dc6-10',//客胜6-10
    					'13' => 'u5ba2u80dc11-15',//客胜11-15
    					'14' => 'u5ba2u80dc16-20',//客胜16-20
    					'15' => 'u5ba2u80dc21-25',//客胜21-25
    					'16' => 'u5ba2u80dc26+',//客胜26+
    					);
    					break;
    				case 4:
    					$play_config = array(
    					'1' => 'H',
    					'2' => 'D',
    					);
    					break;
    				default: break;
    			}
    		}
    		//$sp = '{"cc":{"c":"cc","v":"4.30","s":"1","d":"2011-09-06","t":"05:59:00"},"cb":{"c":"cb","v":"15.00","s":"1","d":"2011-09-06","t":"05:59:00"},"ca":{"c":"ca","v":"28.00","s":"1","d":"2011-09-06","t":"05:59:00"},"bc":{"c":"bc","v":"6.50","s":"1","d":"2011-09-06","t":"05:59:00"},"bb":{"c":"bb","v":"4.50","s":"1","d":"2011-09-06","t":"05:59:00"},"ba":{"c":"ba","v":"5.40","s":"1","d":"2011-09-06","t":"05:59:00"},"ac":{"c":"ac","v":"34.00","s":"1","d":"2011-09-06","t":"05:59:00"},"ab":{"c":"ab","v":"15.00","s":"1","d":"2011-09-06","t":"05:59:00"},"aa":{"c":"aa","v":"3.85","s":"1","d":"2011-09-06","t":"05:59:00"}}';
    		$sp = json_decode($sp);
    		$result_sp = array();
    		foreach ($sp as $key => $val) {
    			if (isset($val->c)) {
    					
    				$result_sp[$val->c] = $val->v;
    			}
    			else {
    				$result_sp[] = $val->v;
    			}
    		}
    		//var_dump($result_sp);
    		for ($j = 0; $j < count($match_results_a); $j++) {
    			$key = $play_config[$match_results_a[$j]];
    			if (array_key_exists($key, $result_sp)) {
    				$match_result_sp[] = $result_sp[$key];
    			}
    		}
    		$match_result_sp = implode(',', $match_result_sp);
    		//$return[] = $match_id.':'.$match_result_sp;
    		if ($ticket_type == 6 && ($play_method == 2 || $play_method == 4)) {
    			if ($play_method == 2 && $goalline > 0) {
    				$goalline = '+'.$goalline;
    			}
    			$return[] = $match_id.'('.$goalline.'):'.$match_result_sp;
    		}
    		else {
    			$return[] = $match_id.':'.$match_result_sp;
    		}
    		//var_dump($return);
    		//echo $i;
    	}
    	$return = implode('|', $return);
    	//var_dump($return);
    	$sql_obj->query('update ticket_nums set moreinfo="'.$return.'" where id="'.$id.'"');
    	if (!$sql_obj->error()) {
    		return true;
    	}
    	else {
    		return false;
    	}
    }
    
    
}