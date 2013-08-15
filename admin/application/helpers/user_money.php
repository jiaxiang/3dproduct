<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 用户资金操作类
 * 所有涉及到会员资金变换都从这里入口
 */
class user_money_Core 
{
    private static $instance = NULL;

    // 获取单态实例
    public static function get_instance()
    {
        if (self::$instance === null)
        {
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    
    /*
     * 更新会员资金
     * @param	int	   $is_in  		收入0或支出1
     * @param  	int    $user_id	 	用户id
     * @param  	int    $money		金额
     * @param  	int	   $logtype		日志类型
     * @param  	string $order_num	订单号
     * @param  	string $money_type	资金类别
     * @param  	string $memo		备注
     * @return	bool   true or false成功或失败
     */
    public function update_money($is_in, $user_id, $money, $logtype, $order_num = NULL, $money_type = NULL, $memo = NULL)
    {
        $is_in = intval($is_in);
        $user_id = intval($user_id);
        $logtype = intval($logtype);
        
        //参数检测
        if ($user_id <= 0 || $is_in < 0 || $logtype < 0 || $money < 0)
            return  FALSE;
        
        //资金类别输入错误
        $money_type_set = Kohana::config('money_type');
        if (!empty($money_type))
        {
            $money_type = strtoupper($money_type);
            if (!array_key_exists($money_type, $money_type_set))
                return FALSE;
        }
        
        //若进账时资金类别为空则默认为本金
        if ($is_in == 0 && empty($money_type))
        {
            $money_type = 'USER_MONEY';
        }
        
        //获取用户所有总资金
        $userobj = user::get_instance();
        $user_moneys = $userobj->get_user_moneys($user_id);
        $old_user_moneys = $user_moneys;
        
        //当出现异常
        if (empty($user_moneys))
            return FALSE;
        
        $logobj = ORM::factory('account_log');
        
        $data = array();
        $data['order_num'] = $order_num;
        $data['log_type'] = $logtype;
        $data['user_id'] = $user_id;
        $data['price'] = $money;
        $data['user_money'] = $user_moneys['all_money'];
        $data['is_in'] = $is_in;
        $data['memo'] = $memo;
        $data['method'] = serialize(array('url' => 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"], 'method' => $_SERVER['REQUEST_METHOD']));
        $data['ip'] = tool::get_str_ip();
        
        //d($old_user_moneys, FALSE);
        //d($logobj->validate($data, FALSE));
        
	    if (!$logobj->validate($data, FALSE))
	        return FALSE;
        
        if ($data['is_in'] == 0)
        {
            $data['user_money'] = $data['user_money'] + $money;
        }
        else
        {
            $data['user_money'] = $data['user_money'] - $money;
        }
                
        //当出现资金异常
        if ($data['user_money'] < 0)
            return FALSE;
            
        //出账指定资金类别时需要再次检测余额是否够用
        if ($data['is_in'] == 1)
        {
            if (!empty($money_type))
            {
                switch ($money_type)
                {
                    case 'USER_MONEY':
                       $user_moneys['user_money'] =  $user_moneys['user_money'] - $money;
                       if ($user_moneys['user_money'] < 0)
                           return  FALSE;
                       break;
                    case 'BONUS_MONEY':
                       $user_moneys['bonus_money'] =  $user_moneys['bonus_money'] - $money;
                       if ($user_moneys['bonus_money'] < 0)
                           return  FALSE;
                       break; 
                    case 'FREE_MONEY':
                       $user_moneys['free_money'] =  $user_moneys['free_money'] - $money;
                       if ($user_moneys['free_money'] < 0)
                           return  FALSE;
                       break;
                }            
            }
            //当未指定资金类型时 本金=>奖金=>彩金扣款
            else 
            {
                $user_moneys['user_money'] =  $user_moneys['user_money'] - $money;
                if ($user_moneys['user_money'] < 0)
                {
                    $delmoney = $user_moneys['user_money'];
                    $user_moneys['user_money'] = 0;
                    $user_moneys['bonus_money'] += $delmoney;
                    
                    if ($user_moneys['bonus_money'] < 0)
                    {
                         $delmoney = $user_moneys['bonus_money'];
                         $user_moneys['bonus_money'] = 0;
                         $user_moneys['free_money'] += $delmoney;
                         
                         //当有异常
                         if ($user_moneys['free_money'] < 0)
                         {
                             return FALSE;
                         }
                    }
                }
            }
        }
        else
        {
            switch ($money_type)
            {
                case 'USER_MONEY':
                    $user_moneys['user_money'] =  $user_moneys['user_money'] + $money;
                    break;
                case 'BONUS_MONEY':
                    $user_moneys['bonus_money'] =  $user_moneys['bonus_money'] + $money;
                    break;                
                case 'FREE_MONEY':
                    $user_moneys['free_money'] =  $user_moneys['free_money'] + $money;
                    break;                     
            }
        }
                
	    //存储到记录表
        $logobj->save();
        //存储失败
        if (!$logobj->saved)
            return FALSE;
        //更改会员资金
        $falgupdate = $userobj->update_moneys($user_id, $user_moneys);
        if (!$falgupdate)
            return  FALSE;
        //当资金记录有变化时则记录详细日志
        $this->add_detail_log($user_moneys, $old_user_moneys, $user_id, $logobj->id);
        return TRUE;
    }
    
    
    /*
     * 增加资金
     * 返还资金或充值相关
     * @param  	int    $user_id	 	用户id
     * @param  	int    $money		金额
     * @param  	array  $arrmoney	金额结构体(格式如:array('USER_MONEY'=>10, 'BONUS_MONEY'=>20, 'FREE_MONEY'=>30))
     * @param  	int	   $logtype		日志类型
     * @param  	string $order_num	订单号
     * @param  	string $memo		备注
     * @return	int    $money or error code 大于0 则是成功否则则是失败
     * 				   -1, 参数错误
     * 				   -2, 金额错误
     * 				   -3, 读取用户错误
     * 				   -4, 用户资金异常错误
     * 				   -5, 验证失败
     * 				   -6, 存储错误
     * 				   -7, 更新会员资金错误	
     */
    public function add_money($user_id, $money, $arrmoney, $logtype, $order_num = NULL, $memo = NULL)
    {
        $user_id = intval($user_id);
        $logtype = intval($logtype);
        
        //参数检测
        if ($user_id <= 0 || $logtype < 0 || $money < 0 || !is_array($arrmoney) || empty($arrmoney))
            return  -1;
        
        $testmoney = 0;
        foreach ($arrmoney as $row)
        {
            $testmoney += $row;
        }
            
        if ($money != $testmoney)
            return -2;

        //获取用户所有总资金
        $userobj = user::get_instance();
        $user_moneys = $userobj->get_user_moneys($user_id);
        $old_user_moneys = $user_moneys;
        
        //当出现异常
        if (empty($user_moneys))
            return -3;            

        $logobj = ORM::factory('account_log');
        
        $data = array();
        $data['order_num'] = $order_num;
        $data['log_type'] = $logtype;
        $data['user_id'] = $user_id;
        $data['price'] = $money;
        $data['user_money'] = $user_moneys['all_money'];
        $data['is_in'] = 0;
        $data['memo'] = $memo;
        $data['method'] = serialize(array('url' => 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"], 'method' => $_SERVER['REQUEST_METHOD']));
        $data['ip'] = tool::get_str_ip();            
        
        //$data['user_money'] = $data['user_money'] + $money;
                
        if ($data['user_money'] < 0)
            return -4;
        
	    if (!$logobj->validate($data, FALSE))
	        return -5;
	        
        $logobj->save();

        if (!$logobj->saved)
            return -6;
        
        if (!empty($arrmoney['USER_MONEY']))
        {
            $user_moneys['user_money'] =  $user_moneys['user_money'] + $arrmoney['USER_MONEY'];
        }
        if (!empty($arrmoney['BONUS_MONEY']))
        {
            $user_moneys['bonus_money'] =  $user_moneys['bonus_money'] + $arrmoney['BONUS_MONEY'];
        }
        if (!empty($arrmoney['FREE_MONEY']))
        {
            $user_moneys['free_money'] =  $user_moneys['free_money'] + $arrmoney['FREE_MONEY'];
        }

        //更新会员资金
        if (!$userobj->update_moneys($user_id, $user_moneys))
            return -7;
        
        //当资金记录有变化时则记录详细日志
        $this->add_detail_log($user_moneys, $old_user_moneys, $user_id, $logobj->id);
        return $money;
    }
    
    
    /*
     * 减少资金
     * 返还资金或充值相关
     * @param  	int    $user_id	 	用户id
     * @param  	int    $money		金额
     * @param  	array  $arrmoney	金额结构体(格式如:array('USER_MONEY'=>10, 'BONUS_MONEY'=>20, 'FREE_MONEY'=>30))
     * @param  	int	   $logtype		日志类型
     * @param  	string $order_num	订单号
     * @param  	string $memo		备注
     * @return	int    money or error code 大于0 则是成功否则则是失败
     * 				   -1, 参数错误
     * 				   -2, 金额错误
     * 				   -3, 读取用户错误
     * 				   -4, 用户资金异常错误
     * 				   -5, 验证失败
     * 				   -6, 存储错误
     * 				   -7, 更新会员资金错误	
     */
    public function minus_money($user_id, $money, $arrmoney, $logtype, $order_num = NULL, $memo = NULL)
    {
        $user_id = intval($user_id);
        $logtype = intval($logtype);
        
        //参数检测
        if ($user_id <= 0 || $logtype < 0 || $money < 0 || !is_array($arrmoney) || empty($arrmoney))
            return  -1;
        
        $testmoney = 0;
        foreach ($arrmoney as $row)
        {
            $testmoney += $row;
        }

        if ($money != $testmoney)
            return -2;

        //获取用户所有总资金
        $userobj = user::get_instance();
        $user_moneys = $userobj->get_user_moneys($user_id);
        $old_user_moneys = $user_moneys;
        
        //当出现异常
        if (empty($user_moneys))
            return -3;

        $logobj = ORM::factory('account_log');
        
        $data = array();
        $data['order_num'] = $order_num;
        $data['log_type'] = $logtype;
        $data['user_id'] = $user_id;
        $data['price'] = $money;
        $data['user_money'] = $user_moneys['all_money'];
        $data['is_in'] = 1;
        $data['memo'] = $memo;
        $data['method'] = serialize(array('url' => 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"], 'method' => $_SERVER['REQUEST_METHOD']));
        $data['ip'] = tool::get_str_ip();            
        
        //$data['user_money'] = $data['user_money'] - $money;
                
        if ($data['user_money'] < 0)
            return -4;
        
	    if (!$logobj->validate($data, FALSE))
	        return -5;
	        
        $logobj->save();

        if (!$logobj->saved)
            return -6;
        
        if (!empty($arrmoney['USER_MONEY']))
        {
            $user_moneys['user_money'] =  $user_moneys['user_money'] - $arrmoney['USER_MONEY'];
        }
        if (!empty($arrmoney['BONUS_MONEY']))
        {
            $user_moneys['bonus_money'] =  $user_moneys['bonus_money'] - $arrmoney['BONUS_MONEY'];
        }
        if (!empty($arrmoney['FREE_MONEY']))
        {
            $user_moneys['free_money'] =  $user_moneys['free_money'] - $arrmoney['FREE_MONEY'];
        }
        
        //更新会员资金
        if (!$userobj->update_moneys($user_id, $user_moneys))
            return -7;
        
        //当资金记录有变化时则记录详细日志
        $this->add_detail_log($user_moneys, $old_user_moneys, $user_id, $logobj->id);
        return $money;
    }    
    
    
    

    /*
     * 添加详细记录
     * @param  	array  $user_moneys		用户新资金结构体
     * @param  	array  $old_user_moneys	用户老资金结构体
     * @param  	int    $user_id	 		用户id
     * @param  	int    $pid	 			父id
	 * @return 	bool   true or false	 
     */    
    public function add_detail_log($user_moneys, $old_user_moneys, $user_id, $pid)
    {
        $is_in = 0;
        if ($user_moneys['user_money'] != $old_user_moneys['user_money'])
        {
            $change_money = $user_moneys['user_money'] - $old_user_moneys['user_money'];
            if ($change_money < 0)
            {
                $change_money *= -1;
                $is_in = 1;
            }
            Money_logService::get_instance()->add_log($pid, $user_id, 'USER_MONEY', $is_in, $change_money, $old_user_moneys['user_money']);
        }
        if ($user_moneys['bonus_money'] != $old_user_moneys['bonus_money'])
        {
            $change_money = $user_moneys['bonus_money'] - $old_user_moneys['bonus_money'];
            if ($change_money < 0)
            {
                $change_money *= -1;
                $is_in = 1;
            }
            Money_logService::get_instance()->add_log($pid, $user_id, 'BONUS_MONEY', $is_in, $change_money, $old_user_moneys['bonus_money']);
        }
        if ($user_moneys['free_money'] != $old_user_moneys['free_money'])
        {
            $change_money = $user_moneys['free_money'] - $old_user_moneys['free_money'];
            if ($change_money < 0)
            {
                $change_money *= -1;
                $is_in = 1;
            }
            Money_logService::get_instance()->add_log($pid, $user_id, 'FREE_MONEY', $is_in, $change_money, $old_user_moneys['free_money']);
        }
    }
    
    
    /*
     * 根据输入的订单id返回资金相关的消费详细记录
     */
    public function get_con_by_order_num($order_num)
    {
        //获取资金关联id
        $obj = ORM::factory('account_log');
        $obj->where('order_num', $order_num)->find();
        $pid = 0;
        if ($obj->loaded)
        {
            $pid = $obj->id;
        }
        
        if (empty($pid))
            return FALSE;
        
        $obj = ORM::factory('money_log');
        $result = $obj->where('account_log_id', $pid)->find_all();
        
        //资金详细结构
        $return = array();
        foreach ($result as $row)
        {
            $row->is_in == 1 && $return[$row->log_type] = $row->price;
        }
        return $return;
    }    
    
    
    /*
     * 获取用户所有充值的资金结构体
     * (充值logtype:1,6)
     */
    public function get_user_recharge_money($user_id)
    {
        $return = array();
        $return['USER_MONEY'] = 0;
        $return['BONUS_MONEY'] = 0;
        $return['FREE_MONEY'] = 0;
        
        $obj = ORM::factory('account_log');
        $obj->where('user_id', $user_id)
            ->where('is_in', 0)
            ->in('log_type', array(1, 6));
        $results = $obj->find_all();
        
        $objmoney = ORM::factory('money_log');        
        foreach ($results as $row)
        {
            $results_money = $objmoney->where('account_log_id', $row->id)->find_all();
            
            foreach ($results_money as $rowmoney)
            {
                if ($rowmoney->log_type == 'USER_MONEY')
                {
                    $return['USER_MONEY'] += $rowmoney->price;
                }
                if ($rowmoney->log_type == 'BONUS_MONEY')
                {
                    $return['BONUS_MONEY'] += $rowmoney->price;
                }                
                if ($rowmoney->log_type == 'FREE_MONEY')
                {
                    $return['FREE_MONEY'] += $rowmoney->price;
                }
            }
            
        }
        return $return;
    }
    
    

}