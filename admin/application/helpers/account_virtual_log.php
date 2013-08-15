<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 用户资金变动日志表
 */
class account_virtual_log_Core {
    private static $instance = NULL;

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
	 * @param  array $data 数据包
	 * @return true or false
	 */
	public function add($data)
	{
	    $obj = ORM::factory('account_virtual_log');
	    
	    if (!$obj->validate($data))
	        return FALSE;
        
	    !empty($data['order_num']) && $obj->order_num = $data['order_num'];
	    $obj->user_id = $data['user_id'];
	    $obj->log_type = $data['log_type'];
	    empty($data['is_in']) && $data['is_in'] = 0;
        $obj->is_in = $data['is_in'];
        $obj->price = $data['price'];
        $obj->user_money = $data['user_money'];
        $obj->memo = $data['memo'];
        $obj->method = serialize(array('url' => 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"], 'method' => $_SERVER['REQUEST_METHOD']));
        $obj->ip = tool::get_str_ip();
        
		$obj->save();
		
		if ($obj->saved)
		{
		    //更新用户表金额
		    $userobj = user::get_instance();
		    if($data['is_in']==0)
		    {
		        $usermoney = $obj->user_money + $obj->price;
		    }
		    else 
		    {
		        $usermoney = $obj->user_money - $obj->price;
		    }
		    
		    $userobj->update_virtual_money($data['user_id'], $usermoney);
		    
		    return TRUE;
		}
		else 
		{
		    return FALSE;
		}
		
	}      
}