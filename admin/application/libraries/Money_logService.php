<?php defined('SYSPATH') OR die('No direct access allowed.');

class Money_logService_Core extends DefaultService_Core 
{
	/* 兼容php5.2环境 Start */
    private static $instance = NULL;
	   
    // 获取单态实例
    public static function get_instance()
    {
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }

    
    /*
     * 添加详细日志
     * @param  long	 $account_log_id  	资金记录id
     * @param  long	 $user_id			用户id
     * @param  long	 $log_type			日志类型
     * @param  long	 $is_in				收入0或支出1
     * @param  long	 $price				资金
     * @param  long	 $user_money		用户当前金额
     * @param  long	 $memo				详细说明
     * @return true or false
     */
    public function add_log($account_log_id, $user_id, $log_type, $is_in, $price, $user_money, $memo = NULL)
    {
        $data = array();
        $data['account_log_id'] = $account_log_id;
        $data['user_id'] = $user_id;
        $data['log_type'] = $log_type;
        $data['is_in'] = $is_in;
        $data['price'] = $price;
        $data['user_money'] = $user_money;
        $data['memo'] = $memo;
        
        $obj = ORM::factory('money_log');
        
        if (!$obj->validate($data, FALSE))
            return FALSE;
        
        $obj->save();

        if ($obj->saved) 
        {
            return TRUE;
        }
        else
        {
            return  FALSE;
        }
    }
}
