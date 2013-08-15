<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 用户管理工具方法
 */
class plan_Core {
    private static $instance = NULL;
    private $detail_url_jczq;
    private $detail_url_jclq;
    private $detail_url_spf;
    private $detail_url_bjdc;
    
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
    	$data['site_config'] = Kohana::config('site_config.site');
    	$host = $_SERVER['HTTP_HOST'];
    	$dis_site_config = Kohana::config('distribution_site_config');
    	if (array_key_exists($host, $dis_site_config) == true && isset($dis_site_config[$host])) {
    		$data['site_config']['site_title'] = $dis_site_config[$host]['site_name'];
    		$data['site_config']['keywords'] = $dis_site_config[$host]['keywords'];
    		$data['site_config']['description'] = $dis_site_config[$host]['description'];
    	}
        $this->detail_url_jczq = 'http://'.$data['site_config']['name'].'/jczq/viewdetail/';
        $this->detail_url_jclq = 'http://'.$data['site_config']['name'].'/jclq/viewdetail/';
        $this->detail_url_bjdc = 'http://'.$data['site_config']['name'].'/bjdc/viewdetail/';
        $this->detail_url_zcsf = 'http://'.$data['site_config']['name'].'/zcsf/viewdetail/';
        $this->detail_url_dlt = 'http://'.$data['site_config']['name'].'/dlt/view/';
        $this->detail_url_pls = 'http://'.$data['site_config']['name'].'/pls/view/';
        $this->detail_url_plw = 'http://'.$data['site_config']['name'].'/plw/view/';
        $this->detail_url_qxc = 'http://'.$data['site_config']['name'].'/qxc/view/';
    }
    
    
    /*
     * 根据方案基表信息获取方案详细信息
     * @param array $result 方案基表信息
     * @return	array
     */    
    public function get_result(&$result, $needuser = TRUE)
    {
        if (empty($result))
            return FALSE;
                    
        $result['detail'] = array();
        $needuser && $result['user'] = array();
        
        switch ($result['ticket_type']) 
        {
            case 1:
               $result['detail'] = $this->get_result_jczq($result['order_num']);
               if (!empty($result['detail']))
               {
                   $result['detail']['total_price'] = $result['detail']['total_price'];//总价格    
                   $result['plan_copies'] = $result['detail']['zhushu'];         //总份数
                   $result['plan_priceone'] = $result['detail']['price_one'];    //每份价格    
                   $result['plan_buyed'] = $result['detail']['buyed'];           //已购买数量
                   $result['plan_bonus'] = $result['detail']['bonus'];           //奖金
                   $result['plan_detail'] = $this->detail_url_jczq.$result['detail']['basic_id'];
                   
                   if ($result['detail']['parent_id'] > 0)
                   {
                       $result['parent'] = $this->get_result_jczq($result['detail']['parent_id'], FALSE);
                   }
               }
            break;
            case 2:
               $result['detail'] = $this->get_result_sfc($result['order_num']);
               if (!empty($result['detail']))
               {
                   $result['detail']['total_price'] = $result['detail']['price'];//总价格    
                   $result['plan_copies'] = $result['detail']['copies'];         //总份数
                   $result['plan_priceone'] = $result['detail']['price_one'];    //每份价格    
                   $result['plan_buyed'] = $result['plan_copies'] - $result['detail']['buyed'];//已购买数量                                      
                   $result['plan_bonus'] = $result['detail']['bonus'];           //奖金
                   $result['plan_detail'] = $this->detail_url_zcsf.$result['detail']['basic_id'];
                   
                   if ($result['detail']['parent_id'] > 0)
                   {
                       $result['parent'] = $this->get_result_sfc($result['detail']['parent_id'], FALSE);
                   }                     
                   
               }
            break;
            case 6:
               $result['detail'] = $this->get_result_jclq($result['order_num']);
               if (!empty($result['detail']))
               {
                   $result['detail']['total_price'] = $result['detail']['total_price'];//总价格    
                   $result['plan_copies'] = $result['detail']['zhushu'];         //总份数
                   $result['plan_priceone'] = $result['detail']['price_one'];    //每份价格    
                   $result['plan_buyed'] = $result['detail']['buyed'];           //已购买数量
                   $result['plan_bonus'] = $result['detail']['bonus'];           //奖金
                   $result['plan_detail'] = $this->detail_url_jclq.$result['detail']['basic_id'];
                   
                   if ($result['detail']['parent_id'] > 0)
                   {
                       $result['parent'] = $this->get_result_jclq($result['detail']['parent_id'], FALSE);
                   }
               }
            break;
            case 7:
               $result['detail'] = $this->get_result_bjdc($result['order_num']); 
               if (!empty($result['detail']))
               {
                   $result['detail']['total_price'] = $result['detail']['total_price'];//总价格    
                   $result['plan_copies'] = $result['detail']['zhushu'];         //总份数
                   $result['plan_priceone'] = $result['detail']['price_one'];    //每份价格    
                   $result['plan_buyed'] = $result['detail']['buyed'];           //已购买数量
                   $result['plan_bonus'] = $result['detail']['bonus'];           //奖金
                   $result['plan_detail'] = $this->detail_url_bjdc.$result['detail']['basic_id'];
                   
                   if ($result['detail']['parent_id'] > 0)
                   {
                       $result['parent'] = $this->get_result_bjdc($result['detail']['parent_id'], FALSE);
                   }                    
               }
            break;
            case 8:
            	$result['detail'] = $this->get_result_dlt($result['order_num']);
            	if (!empty($result['detail']))
            	{
            		//d($result,false);
            		$result['detail']['total_price'] = $result['detail']['total_price'];//总价格
            		$result['plan_copies'] = $result['detail']['nums'];         //总份数
            		$result['plan_priceone'] = $result['detail']['onemoney'];    //每份价格
            		$result['plan_buyed'] = $result['detail']['rgnum'];           //已购买数量
            		$result['plan_bonus'] = $result['detail']['afterbonus'];           //奖金
            		$result['plan_detail'] = $this->detail_url_dlt.$result['detail']['id'];
            	}
            	break;
			case 9:
            	$result['detail'] = $this->get_result_dlt($result['order_num']);
            	if (!empty($result['detail']))
            	{
            		//d($result,false);
            		$result['detail']['total_price'] = $result['detail']['total_price'];//总价格
            		$result['plan_copies'] = $result['detail']['nums'];         //总份数
            		$result['plan_priceone'] = $result['detail']['onemoney'];    //每份价格
            		$result['plan_buyed'] = $result['detail']['rgnum'];           //已购买数量
            		$result['plan_bonus'] = $result['detail']['afterbonus'];           //奖金
            		$result['plan_detail'] = $this->detail_url_plw.$result['detail']['id'];
            	}
            	break;
            case 10:
            	$result['detail'] = $this->get_result_dlt($result['order_num']);
            	if (!empty($result['detail']))
            	{
            		//d($result,false);
            		$result['detail']['total_price'] = $result['detail']['total_price'];//总价格
            		$result['plan_copies'] = $result['detail']['nums'];         //总份数
            		$result['plan_priceone'] = $result['detail']['onemoney'];    //每份价格
            		$result['plan_buyed'] = $result['detail']['rgnum'];           //已购买数量
            		$result['plan_bonus'] = $result['detail']['afterbonus'];           //奖金
            		$result['plan_detail'] = $this->detail_url_qxc.$result['detail']['id'];
            	}
            	break;
            case 11:
            	$result['detail'] = $this->get_result_dlt($result['order_num']);
            	if (!empty($result['detail']))
            	{
            		//d($result,false);
            		$result['detail']['total_price'] = $result['detail']['total_price'];//总价格
            		$result['plan_copies'] = $result['detail']['nums'];         //总份数
            		$result['plan_priceone'] = $result['detail']['onemoney'];    //每份价格
            		$result['plan_buyed'] = $result['detail']['rgnum'];           //已购买数量
            		$result['plan_bonus'] = $result['detail']['afterbonus'];           //奖金
            		$result['plan_detail'] = $this->detail_url_pls.$result['detail']['id'];
            	}
            	break;
            default:
            break;
        }
        $needuser && $result['user']= user::get_instance()->get($result['user_id']);
    }
    

    /*
     * 根据彩票表获取方案信息
     * @param numeric $plan_id 方案id
     * @param numeric $ticket_type 彩种
     * @param numeric $play_method 玩法
     * @return	array	 
     */
    public function get_plan_by_tid($plan_id, $ticket_type, $play_method = NULL)
    {
        
        $result = array();
        switch ($ticket_type) 
        {
            case 1:
               $result = $this->get_result_jczq($plan_id, FALSE);
            break;
            case 2:
               $result = $this->get_result_sfc($plan_id, FALSE);
            break;
            case 6:
               $result = $this->get_result_jclq($plan_id, FALSE);
            break;
            case 7:
               $result = $this->get_result_bjdc($plan_id, FALSE);
            break;
            case 8:
            	$result = $this->get_result_dlt($plan_id, FALSE);
            	break;
            case 9:
            	$result = $this->get_result_dlt($plan_id, FALSE);
            	break;
            case 10:
            	$result = $this->get_result_dlt($plan_id, FALSE);
            	break;
            case 11:
            	$result = $this->get_result_dlt($plan_id, FALSE);
            	break;
            default:
            break;
        }
        return $result;
    }
    
    
    /*
     * 获取竞彩足球方案信息
     */
    public function get_result_jczq($id, $is_orderid = TRUE)
    {
        if ($is_orderid)
        {
            return Plans_jczqService::get_instance()->get_by_order_id($id);
        }
        else 
        {
            return Plans_jczqService::get_instance()->get_by_plan_id($id);   
        }
        
    }
	/*
     * 获取竞彩篮球方案信息
     */
    public function get_result_jclq($id, $is_orderid = TRUE)
    {
        if ($is_orderid)
        {
            return Plans_jclqService::get_instance()->get_by_order_id($id);
        }
        else 
        {
            return Plans_jclqService::get_instance()->get_by_plan_id($id);   
        }
    }
    /*
     * 获取普通足彩方案信息
     */
    public function get_result_sfc($id, $is_orderid = TRUE)
    {
        if ($is_orderid)
        {
            return Plans_sfcService::get_instance()->get_by_order_id($id);
        }
        else
        {
            return Plans_sfcService::get_instance()->get_by_plan_id($id);
        }
        
    }
    
    /*
     * 获取北京单场方案信息
     */
    public function get_result_bjdc($id, $is_orderid = TRUE)
    {
        if ($is_orderid)
        {
            return Plans_bjdcService::get_instance()->get_by_order_id($id);
        }
        else 
        {
            return Plans_bjdcService::get_instance()->get_by_plan_id($id);
        }
    }
    
    /*
     * 获取大乐透方案信息
     */
    public function get_result_dlt($id, $is_orderid = TRUE) {
    	if ($is_orderid)
    	{
    		return Plans_lotty_orderService::get_instance()->get_by_order_id($id);
    	}
    	else
    	{
    		return Plans_lotty_orderService::get_instance()->get_by_plan_id($id);
    	}
    }
    /*
     * 返还或派送奖金到相应方案
     * @param  integer  $ticket_type  彩种
     * @param  integer  $ordernum  订单号码
     * @param  integer  $money  退还金额
     * @return integer  返还操作的用户数量
     */
    public function refund_plan($ticket_type, $ordernum, $money)
    {
        if (empty($ticket_type) || empty($ordernum) || empty($money))
            return FALSE;
        
        $users = array();
        switch ($ticket_type)
        {
            case 1:
               $users = Plans_jczqService::get_instance()->refund_by_ticket($ordernum, $money);
               break;
            case 2:
               $users = Plans_sfcService::get_instance()->refund_by_ticket($ordernum, $money);
               break;
            case 6:
               $users = Plans_jclqService::get_instance()->refund_by_ticket($ordernum, $money);
               break;
            case 7:
               $users = Plans_bjdcService::get_instance()->refund_by_ticket($ordernum, $money);
               break;
            default:
               break;
        }
        
        //d($users);
        $i = 0;
        //开始分配
        if (!empty($users))
        {
            $moneyobj = user_money::get_instance();
            $lan = Kohana::config('lan');
            
            foreach ($users as $key => $value)
            {
                $order_num = date('YmdHis').rand(0, 99999);
                $moneys['USER_MONEY'] = $value;
                $flagret = $moneyobj->add_money($key, $value, $moneys, 5, $order_num, $lan['money'][9].',方案ID:'.$ordernum);
                d($flagret, FALSE);
                $i++;
            }
        }
        return $i;
    }
    

    /*
     * 无效彩票返还金额
     */
    public function refund_by_ticket($ticket_type, $ordernum, $money)
    {
        return $this->refund_plan($ticket_type, $ordernum, $money);
    }
    
    
    /*
     * 更改订单状态
     */
    public function update_status_by_ordernum($ordernum, $status)
    {
        $result = Plans_basicService::get_instance()->get_by_ordernum($ordernum);
        
        if (empty($result))
        {
            return FALSE;
        }
        
        switch ($result['ticket_type'])
        {
            case 1:
               $result1 = $this->get_result_jczq($ordernum);
               if (!empty($result1))
               {
                   Plans_jczqService::get_instance()->update_status($result1['id'], $status);
               }
            break;
            case 2:
               $result2 = $this->get_result_sfc($ordernum);	   
               if (!empty($result2))
               {
                   Plans_sfcService::get_instance()->update_status($result2['id'], $status);
               }               
            break;
            case 6:
               $result1 = $this->get_result_jclq($ordernum);
               if (!empty($result1))
               {
                   Plans_jclqService::get_instance()->update_status($result1['id'], $status);
               }
            break;
            case 7:
               $result7 = $this->get_result_bjdc($ordernum);
               if (!empty($result7))
               {
                   Plans_bjdcService::get_instance()->update_status($result7['id'], $status);
               }     
            break;
            default:
            break;
        }
    }
    
    
    /*
     * 过期方案处理
     */
    public function expired_plan()
    {
        @set_time_limit(300);        //防止过期处理错误
        
        
        //检索状态为方案提交及未出票状态的合买方案
        $query_struct = array();        
        $query_struct['where']['plan_type'] = 1;
        $query_struct['where']['date_end <'] = tool::get_date();
        $query_struct['where']['status'] = 0;    //array(0,1)
        
        $plan_basicobj = Plans_basicService::get_instance();
        $results = $plan_basicobj->query_assoc($query_struct);
        
        foreach ($results as $result)
        {
            $flag = FALSE;
             switch ($result['ticket_type']) 
             {
                 case 1:
                     $flag = Plans_jczqService::get_instance()->expired_plan($result['order_num']);
                     break;
                 case 2:
                     $flag = Plans_sfcService::get_instance()->expired_plan($result['order_num']);
                     break;                     
                 case 6:
                     $flag = Plans_jclqService::get_instance()->expired_plan($result['order_num']);
                     break;                    
                 case 7:
                     $flag = Plans_bjdcService::get_instance()->expired_plan($result['order_num']);
                     break;
             }  
             
             d($result['order_num'], FALSE);
             
             if ($flag)
             {
                 $this->update_status_by_ordernum($result['order_num'], 1);
             }
             else 
             {
                 $this->get_result($result, FALSE);
                 
                 if (!empty($result['detail']))
                 {
                     $this->cancel_plan($result['order_num']);
                 }
             }
             
             
        }
    }
    
    
    /*
     * 方案取消
     */
    public function cancel_plan($ordernum)
    {
        $result = Plans_basicService::get_instance()->get_by_ordernum($ordernum);
        //return false;
        if (empty($result))
            return FALSE;
        
        if ($result['status'] >= 5)        //已兑奖的不予取消
        {
            return FALSE;        
        }
        
        if ($result['start_user_id'] != $result['user_id'])    //非父订单不予处理
        {
            return FALSE;
        }
        
        switch ($result['ticket_type']) 
        {
             case 1:
                 $flag = Plans_jczqService::get_instance()->cancel_plan($result['order_num']);
                 break;
             case 2:
                 $flag = Plans_sfcService::get_instance()->cancel_plan($result['order_num']);
                 break;                     
             case 6:
                 $flag = Plans_jclqService::get_instance()->cancel_plan($result['order_num']);
                 break;                    
             case 7:
                 $flag = Plans_bjdcService::get_instance()->cancel_plan($result['order_num']);
                 break;
         }
         
         return $flag;
         
    }
    
    
    /*
     * 方案派奖
     */
    public function bonus_plan($ordernum)
    {
        $result = Plans_basicService::get_instance()->get_by_ordernum($ordernum);
                
        if (empty($result))
            return FALSE;
        
        if ($result['status'] != 4)        //非已中奖状态不予操作
        {
            return FALSE;
        }
        
        switch ($result['ticket_type']) 
        {
             case 1:
                 //$flag = Plans_jczqService::get_instance()->bonus_plan($result['order_num']);
                 $flag = Plans_jczqService::get_instance()->bonus_plan_virtual($result['order_num']);
                 break;
             case 2:
                 $flag = Plans_sfcService::get_instance()->bonus_plan($result['order_num']);
                 break;                     
             case 6:
                 $flag = Plans_jclqService::get_instance()->bonus_plan($result['order_num']);
                 break;                   
             case 7:
                 $flag = Plans_bjdcService::get_instance()->bonus_plan($result['order_num']);
                 break;
         }
    }
    
    
    /*
     * 统计总共成功消费的金额
     */
    public function get_all_win_money($user_id)
    {
        if (empty($user_id))
            return  0;
                    
        //此处需要调用所有的
        $obj = ORM::factory('plans_basic');
        $obj->where('user_id', $user_id)->in('status', array(2,3,4,5));
        $results = $obj->find_all();
                
        $moneyobj = user_money::get_instance();
        
        $retmoney = array();
        $retmoney['USER_MONEY'] = 0;
        $retmoney['BONUS_MONEY'] = 0;
        $retmoney['FREE_MONEY'] = 0;
        
        foreach ($results as $row)
        {
            $moneys = $moneyobj->get_con_by_order_num($row->order_num);
            if (!empty($moneys))
            {
                if (!empty($moneys['USER_MONEY']))
                {
                    $retmoney['USER_MONEY'] += $moneys['USER_MONEY'];
                }
                if (!empty($moneys['BONUS_MONEY']))
                {
                    $retmoney['BONUS_MONEY'] += $moneys['BONUS_MONEY'];
                }                
                if (!empty($moneys['FREE_MONEY']))
                {
                    $retmoney['FREE_MONEY'] += $moneys['FREE_MONEY'];
                }
            }
        }
        return $retmoney;
    }
}