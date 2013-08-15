<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 赛事操作(竞彩足球,竞彩篮球)
 */
class match_Core {
    private static $instance = NULL;
    private static $match_jczq_detail_url = 'http://info.sporttery.cn/football/info/fb_match_info.php?m=';
    private static $match_jclq_detail_url = 'http://info.sporttery.cn/basketball/info/bk_match_info.php?m=';
    
    // 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    
    
	/**
	 * 更新或添加信息
	 *
	 * @param  	array 	$data
	 * @return 	bool true or false
	 */
	public function update($data)
	{
	    $obj = ORM::factory('match_data');
	    if (!$obj->validate($data))
	        return FALSE;
	   
		$obj->where('play_type', $data['play_type'])
		    ->where('ticket_type', $data['ticket_type'])
		    ->where('match_num', $data['match_num'])
			->where('match_id', $data['match_id'])
			//->where('pool_id', $data['pool_id'])
			->find();
        
		if($obj->loaded)
		{
            $obj->comb = $data['comb'];
		    $obj->update_time = $data['update_time'];
		}
		else
		{
		    $obj->match_num = $data['match_num'];
		    $obj->match_id = $data['match_id'];
		    $obj->pool_id = $data['pool_id'];
            $obj->goalline = $data['goalline'];
            $obj->comb = $data['comb'];
            $obj->ticket_type = $data['ticket_type'];
            $obj->play_type = $data['play_type'];
            $obj->update_time = $data['update_time'];
		}
		return $obj->save();
	}
	
	
	/*
	 * 获取正在进行中的赛事完整数据
	 * 此处将预留接口获取更为合法的数据
	 * @param  	integer  $ticket_type  彩种
	 * @param  	integer  $play_type  玩法id
	 * @param  	date  $curtime  当前时间
	 * 
	 * @return array
	 * 
	 */
	public function get_results($ticket_type, $play_type, $curtime = NULL) 
	{
	    $matchs = array();
	    $timebeg = date("Y-m-d H:i:s", time() - 100000*60);
	    $matchtime_set = Kohana::config('site_config.match');

	    //今日结束时间设置
        $timeend = $this->count_end_time(tool::get_date());
        
        //print $timeend;
        
	    if ($ticket_type == 1)        //竞彩足球
	    {
    	    $obj = ORM::factory('match_data')
    	        ->select('match_details.*,match_datas.*')
    	        ->join('match_details', 'match_details.index_id', 'match_datas.match_id', 'LEFT')
                ->where('match_datas.ticket_type', $ticket_type)
    	        ->where('match_datas.play_type', $play_type)
    	        ->where('match_details.status', 0);
    	    
            if (!empty($curtime))
            {
                $obj->like('match_details.time', $curtime);
            }
            else
            {
                $obj->where('match_details.time > ', tool::get_date());
            }
            $obj->orderby('match_details.time', 'ASC');
            
	    }
	    elseif ($ticket_type == 6)    //竞彩篮球
	    {
    	    $obj = ORM::factory('match_data')
    	        ->select('match_bk_details.*,match_datas.*')
    	        ->join('match_bk_details', 'match_bk_details.index_id', 'match_datas.match_id', 'LEFT')
                ->where('match_datas.ticket_type', $ticket_type)
    	        ->where('match_datas.play_type', $play_type)
    	        ->where('match_details.status', 0);
    	        
            if (!empty($curtime))
            {
                $obj->like('match_bk_details.time', $curtime);
            }
            else
            {
                $obj->where('match_bk_details.time > ', tool::get_date());
            }
            $obj->orderby('match_bk_details.time', 'ASC');
	    }
	    
        $results = $obj->find_all();
        $groups_dates = array();
        $end_dates = array();
        
        foreach ($results as $result) 
        {   
            $tmp = array();
            $tmp = $result->as_array();
                        
            if (empty($tmp['index_id']))
                continue;
            
            $timeend_stamp = strtotime($tmp['time']);            //赛事开始时间
            $weekday = substr($tmp['match_info'], 0, 6);

            if (empty($groups_dates[$weekday]))
            {
                $groups_dates[$weekday] = date("Y-m-d", strtotime(tool::get_date_byweek($weekday, 1, $tmp['time'])));
                $end_dates[$weekday] = $this->count_end_time($groups_dates[$weekday]);
            }
            
            $tmp['match_date'] = $groups_dates[$weekday];        //销售的赛事日期
            $tmp['time_beg'] = date("Y-m-d H:i:s", $timeend_stamp + 60);    //比赛开始时间
            $tmp['time_end'] = $this->get_end_time($tmp['time'], $end_dates[$weekday], $matchtime_set['jczq_endtime']); //销售截止时间
            
            $tmp['match_end'] = 0;    //赛事结束或开始
            
            if (strtotime($tmp['time_end']) < time() || time() > strtotime($tmp['time_beg']))
            {
                $tmp['match_end'] = 1;
            }
            
            $tmp['match_url'] = $this->get_match_detail_url($tmp['match_id'], $ticket_type);
            $tmp['color'] = $this->get_match_color($tmp['league']);
            $matchs[] = $tmp;
        }

        return $matchs;
	}


	/*
	 * 根据输入的赛事id返回当前信息
	 * @param  	integer  $id 	赛事id
	 * @return array
	 */
	public function get_match_detail($id , $ticket_type = 1)
	{
	    $return = array();
	    
	    if ($ticket_type == 1)
	    {
	        $obj = ORM::factory('match_detail');
            $results = $obj->where('index_id', $id)->find();	        
	    }
	    elseif ($ticket_type == 6) 
	    {
	        $obj = ORM::factory('match_bk_detail');
            $results = $obj->where('index_id', $id)->find();	        
	    }

        
        if ($obj->loaded)
        {
            return $results->as_array();
        }
        else 
        {
            return  $return;
        }
	}	
	
	
	/*
	 * 根据输入的赛事id返回所有信息
	 */
	public function get_match_datas($ids ,$ticket_type =1)
	{
	    $matchs = array();
	    $obj = ORM::factory('match_detail');
        $results = $obj->in('index_id', $ids)
                    ->find_all();
                          
	    foreach ($results as $result) 
        {   
            $tmp = array();
            $matchs[$result->index_id] = $result->as_array();
            $matchs[$result->index_id]['match_url'] = $this->get_match_detail_url($result->index_id, $ticket_type);
        }
        
        return $matchs;
	}
	
	/*
	 * 根据输入的赛事id返回赛事详细的url链接地址
	 * @param  	integer  $match_id  赛事id
	 * return string 赛事链接地址
	 */	
	public function get_match_detail_url($match_id, $ticket_type) 
	{
	    if ($ticket_type == 1)
	    {
	        return self::$match_jczq_detail_url.$match_id;
	    }
	    elseif ($ticket_type == 6)
	    {
	        return self::$match_jclq_detail_url.$match_id;
	    }
	}
	
	/*
	 * 根据输入的赛事id,彩种和玩法,返回赛事所有信息
	 * @param  	integer  $id 	赛事id
	 * @return array
	 */
	public function get_match($id, $ticket_type, $play_type, $ds=false)
	{
	    $return = array();
	    $obj = ORM::factory('match_data');
	    
	    if ($ticket_type == 1)
	    {
	        $obj->select('match_details.*,match_datas.*');
	        $obj->join('match_details', 'match_details.index_id', 'match_datas.match_id', 'LEFT');
            $obj->where('match_datas.match_id', $id);
            $obj->where('match_datas.ticket_type', $ticket_type);
            $obj->where('match_datas.play_type', $play_type);	    
	    }
	    else 
	    {
	        $obj->select('match_bk_details.*,match_datas.*');
	        $obj->join('match_bk_details', 'match_bk_details.index_id', 'match_datas.match_id', 'LEFT');
            $obj->where('match_datas.match_id', $id);
            $obj->where('match_datas.ticket_type', $ticket_type);
            $obj->where('match_datas.play_type', $play_type);
	    }

        $result = $obj->find();
	            
        if ($obj->loaded)
        {
            $matchtime_set = Kohana::config('site_config.match');
            $return = $result->as_array();
            $timeend_stamp = strtotime($return['time']);            //赛事开始时间
            $weekday = substr($return['match_info'], 0, 6);
            $return['groups_date'] = date("Y-m-d", strtotime(tool::get_date_byweek($weekday, 1, $return['time'])));
            $return['end_date'] = $this->count_end_time($return['groups_date']);
            $return['time_beg'] = date("Y-m-d H:i:s", $timeend_stamp + 60);
            $return['time_end'] = $this->get_end_time($return['time'], $return['end_date'], $matchtime_set['jczq_endtime']);
            if ($ds == true) {
            	$return['time_end'] = $this->get_end_time($return['time'], $return['end_date'], $matchtime_set['jczq_endtime_ds']);
            }
            
            $return['match_end'] = 0;    //赛事结束或开始
            if (strtotime($return['time_end']) < time()  || time() > strtotime($return['time_beg']))
            {
                $return['match_end'] = 1;
            }
            return $this->get_comb($return);
        }
        else
        {
            return  $return;
        }
	}	
	
	
	/*
	 * 转换comb数据为数组
	 */
	function get_comb($result)
	{
        $return  = array();
        if(empty($result))
            return $return;
        
        if ($result['ticket_type'] == 1)
        {
    	    if ($result['play_type'] == 1)
    	    {
                $result['comb'] = json_decode($result['comb']);
                $result['A'] = $result['comb']->a->v;
                $result['D'] = $result['comb']->d->v;
                $result['H'] = $result['comb']->h->v;
                unset($result['comb']);
    	    }
    	    elseif ($result['play_type'] == 2)
    	    {
    	        $result['comb'] = json_decode($result['comb']);
    	        $result['0'] = $result['comb'][0]->v;
    	        $result['1'] = $result['comb'][1]->v;
    	        $result['2'] = $result['comb'][2]->v;
    	        $result['3'] = $result['comb'][3]->v;
    	        $result['4'] = $result['comb'][4]->v;
    	        $result['5'] = $result['comb'][5]->v;
    	        $result['6'] = $result['comb'][6]->v;
    	        $result['7'] = $result['comb'][7]->v;
    	        unset($result['comb']);
    	    }
    	    elseif ($result['play_type'] == 3)
    	    {
    	        //$result['comb_detail'] = json_decode($result['comb']);
    	    }
    	    elseif ($result['play_type'] == 4)
    	    {
    	        //$result['comb_detail'] = json_decode($result['comb']);
    	    }
	    }
	    elseif ($result['ticket_type'] == 6) 
	    {
	                
	    }
	    
	    return $result;
	    
	}
	
	
	/*
	 * 更新赛事详细表
	 * 从match_bjdc_datas 更新结果数据到  match_details 表
	 */
	public function refresh_match()
	{
	    $obj_from = ORM::factory('match_bjdc_data');
	    $obj_to = ORM::factory('match_detail');
	    
	    $results = $obj_to->where('status', 0)->find_all();
	    
	    //检查数据是否更新
	    $update = array();
	    foreach ($results as $result)
	    {
	        $tmp = $result->as_array();
	        
	        $check = $obj_from->where('home', $tmp['host_name'])
	                         ->where('away', $tmp['guest_name'])
	                         ->where('code <> ', '')
	                         ->where('sp_r <> ', '')
	                         ->where('bf <> ', '')
	                         ->find();
	        if ($obj_from->loaded)
	        {
	            $update[$tmp['id']] = $check['bf'];
	        }
	    }
        
	    //更新数据
	    foreach ($update as $key => $value)
	    {
	        $obj_to->where('id', $key)->find();
	        
	        if ($obj_to->loaded)
	        {
	            $obj_to->result = $value;
	            $obj_to->status = 1;
	            $obj_to->save();
	        }
	    }
	}
	
	
	/*
	 * 获取赛事颜色
	 * @param  string  $match_name  赛事名称
	 * @return string 颜色
	 */
	public function  get_match_color($match_name)
	{
	    $colors = Kohana::config('match_color');
	    if (!empty($colors['matchs'][$match_name]))
	    {
	        return $colors['matchs'][$match_name];
	    }

	    return '#004d00';	    
	}
	
	
	/*
	 * 根据输入日期计算结束售票时间
	 * @param  date  $date  日期
	 * @return date 当前日期的结束售票时间
	 */
	public function count_end_time($date)
	{
	    //print $date.'<br>';
	    $weekdate = tool::get_weekday($date, 2);
	    $time_stamp = strtotime($date);
	    
	    if ($weekdate == 6 || $weekdate == 7)
        {
            $timeend = date("Y-m-d H:i:s", mktime (0, 40, 0, date("m", $time_stamp), date("d", $time_stamp)+1, date("Y", $time_stamp)));
        }
        else
        {
            $timeend = date("Y-m-d H:i:s", mktime (22, 40, 0, date("m", $time_stamp), date("d", $time_stamp), date("Y", $time_stamp)));
        }
	    return $timeend;
	}
	
	/*
	 * 根据输入的日期获得截止时间
	 * @param  date  $date  赛事截止日期
	 * @param  date  $datecheck  本期截止日期
	 * @param  integer  $sysendreduce  售票截止购买时间秒数
	 */
	public function get_end_time($date, $datecheck, $sysendreduce)
	{
	    $return = '';
	    if (strtotime($date) > strtotime($datecheck))
	    {
	        $return = $datecheck;
	    }
	    else
	    {
	        $return = date("Y-m-d H:i:s", strtotime($date) - $sysendreduce);
	    }
	    
	    return $return;
	}
	
	/**
	 * 根据001，日期取得比赛信息
	 * Enter description here ...
	 * @param unknown_type $match_info
	 * @param unknown_type $time
	 */
	public function get_match_detail_by_infotime($match_info, $time) {
		$obj = ORM::factory('match_detail');
		$obj->like('match_info', $match_info);
		$obj->like('time', $time);
		$results = $obj->find();
		$return = array();
		$return = $results->as_array();
        return $return;
	}
	
	/**
	 * 获取已经结束的赛事信息
	 * Enter description here ...
	 */
	public function get_over_match($ticket_type = 1) {
		$mkstarttime = mktime(date("H"), date("i")-90, date("s"), date("m"), date("d"), date("Y"));
        $start_time = date('Y-m-d H:i:s', $mkstarttime);
        
        $mklasttime = mktime(date("H"), date("i"), date("s"), date("m"), date("d")-7, date("Y"));
        $last_time = date('Y-m-d H:i:s', $mklasttime);
		$query = 'select * from match_details where ticket_type="'.$ticket_type.'" and time >= "'.$last_time.'" and time <= "'.$start_time.'" and result is null order by id limit 50';
		$db = Database::instance();
		$results = $db->query($query);
		/**
		 * ["id"]=> string(5) "23162" 
		 * ["index_id"]=> string(5) "25859" 
		 * ["status"]=> string(1) "0" 
		 * ["result"]=> NULL 
		 * ["host_name"]=> string(14) "格拉纳达CF" 
		 * ["host_url"]=> string(24) "http://www.sporttery.com" 
		 * ["host_rank"]=> string(2) "19" 
		 * ["guest_name"]=> string(9) "马洛卡" 
		 * ["guest_url"]=> string(44) "http://www.sporttery.cn/LaLiga/Mallorca.html" 
		 * ["guest_rank"]=> string(2) "15" 
		 * ["match_info"]=> string(9) "周日053" 
		 * ["league"]=> string(21) "西班牙甲级联赛" 
		 * ["time"]=> string(19) "2011-11-21 04:59:00"
		 */ 
		$return = array();
        foreach ($results as $result) {
        	$tmp = array(
        		'id' => $result->id,
	        	'index_id' => $result->index_id,
	        	'status' => $result->status,
	        	'result' => $result->result,
	        	'host_name' => $result->host_name,
	        	'host_url' => $result->host_url,
	        	'host_rank' => $result->host_rank,
	        	'guest_name' => $result->guest_name,
	        	'guest_url' => $result->guest_url,
        		'guest_rank' => $result->guest_rank,
	        	'match_info' => $result->match_info,
	        	'league' => $result->league,
	        	'time' => $result->time,
        	);
        	$return[$result->id] = $tmp;
        	//$total_money += $total_money_result->total_money;
        }
        return $return;
	}
	
	/**
	 * 手动更新赛事赛果
	 * Enter description here ...
	 * @param unknown_type $id
	 * @param unknown_type $result
	 */
	public function update_match_result($id, $result) {
		$obj = ORM::factory('match_detail');
		$obj->where('id', $id)
			->find();
		if($obj->loaded)
		{
            $obj->result = $result;
		}
		else
		{
		    return false;
		}
		return $obj->save();
	}
	
	public function get_unstart_match($ticket_type = 1) {
		$nowtime = date('Y-m-d H:i:s');
		$query = 'select match_details.*,match_datas.pool_id from match_details 
		left join match_datas on match_details.index_id=match_datas.match_id 
		where match_details.ticket_type="'.$ticket_type.'" and match_details.time > "'.$nowtime.'" 
		group by match_details.id order by match_details.time ';
		$db = Database::instance();
		$results = $db->query($query);
		/**
		 * ["id"]=> string(5) "23162"
		 * ["index_id"]=> string(5) "25859"
		 * ["status"]=> string(1) "0"
		 * ["result"]=> NULL
		 * ["host_name"]=> string(14) "格拉纳达CF"
		 * ["host_url"]=> string(24) "http://www.sporttery.com"
		 * ["host_rank"]=> string(2) "19"
		 * ["guest_name"]=> string(9) "马洛卡"
		 * ["guest_url"]=> string(44) "http://www.sporttery.cn/LaLiga/Mallorca.html"
		 * ["guest_rank"]=> string(2) "15"
		 * ["match_info"]=> string(9) "周日053"
		 * ["league"]=> string(21) "西班牙甲级联赛"
		 * ["time"]=> string(19) "2011-11-21 04:59:00"
		 */
		$return = array();
		foreach ($results as $result) {
			$tmp = array(
					'id' => $result->id,
					'index_id' => $result->index_id,
					'status' => $result->status,
					'result' => $result->result,
					'host_name' => $result->host_name,
					'host_url' => $result->host_url,
					'host_rank' => $result->host_rank,
					'guest_name' => $result->guest_name,
					'guest_url' => $result->guest_url,
					'guest_rank' => $result->guest_rank,
					'match_info' => $result->match_info,
					'league' => $result->league,
					'time' => $result->time,
					'pool_id' => $result->pool_id,
			);
			$return[$result->id] = $tmp;
		}
		return $return;
	}
	
	/**
	 * 手动取消
	 * Enter description here ...
	 * @param unknown_type $id
	 * @param unknown_type $result
	 */
	public function update_match_pool_id($id, $result) {
		$query = 'UPDATE match_datas SET `pool_id` = "'.$result.'" WHERE match_id = "'.$id.'"';
		$db = Database::instance();
		$results = $db->query($query);
		return true;
	}
	
}