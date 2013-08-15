<?php defined('SYSPATH') OR die('No direct access allowed.');

class Message_replyService_Core extends DefaultService_Core {
	
	/* 兼容php5.2环境 Start */
    private static $instance = NULL;
    // 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    
    public function delete_reply_by_message_id($message_id = 0){
    	return ORM::factory('message_reply')
			->where('message_id',$message_id)
			->delete_all();
    }
    
    public function get_reply_by_manager_id($manager_id = 0, $message_id = 0){
    	$message_replys = array();
    	$message_reply = ORM::factory('message_reply')
			->where('manager_id',$manager_id)
			->where('message_id',$message_id)
			->find();
		$message_reply = $message_reply->as_array();
		return $message_reply;
    }
    
}    