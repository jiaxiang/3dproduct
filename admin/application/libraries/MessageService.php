<?php defined('SYSPATH') OR die('No direct access allowed.');

class MessageService_Core extends DefaultService_Core {
	const IS_MANAGER_ROLE   = 1;  // 管理员角色
	const IS_MANAGER_REPLY   = 1;  // 留言是否有回复
	
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
    
    
    public function remove($id){
        Message_replyService::get_instance()->delete_reply_by_message_id($id);
        return $this->delete(array('id'=>$id));
    }
    
    /**
	 * 得到留言的信息状态以及留言者的信息
	 *
	 * @param Array $query_struct
	 * @return Array
	 */    
    public function messages($query_struct=array()){
    	$list = array();
    	$results = $this->query_assoc($query_struct);
    	if(!empty($results)){
    	foreach($results as $result)
		{
			$managers = Mymanager::instance($result['manager_id'])->get();
			$managers['name'] = !empty($managers['name']) ? $managers['name'] : '无';
			$managers['phone'] = !empty($managers['phone']) ? $managers['phone'] : '无';
			$managers['reply_status'] = view_tool::get_active_img($result['is_reply']);
			$status = Kohana::config('message.status');
			$merge_arr = array(
					'site_manager_name'  => $managers['name'],
					'phone'              => $managers['phone'],
					'status'             => $status[$result['status']],
					'reply_status'       => $managers['reply_status'],
			);
			$list[] = array_merge($result,$merge_arr);
			}
    	}else{
    		$list = '';
    	}
		return $list;
    }
    
    
    /**
	 * 得到留言的信息状态以及留言者的信息,回复信息以及回复者的信息
	 *
	 * @param Array $query_struct
	 * @return Array
	 */
    public function get_message_reply_by_site_manage_role($query_struct=array()){
    	$list = array();
    	$results = self::messages($query_struct);   	
    	if(!empty($results)){
	    	foreach($results as $result)
			{
				$request_struct = array(
					'where'  => array(
						'message_id' => $result['id'],			
					),
				);
				if(!empty($result['is_reply'])){
					$replies = Message_replyService::get_instance()->index($request_struct);
					foreach($replies as $key =>$val){
						$replies[$key]['manager_name'] = Mymanager::instance($val['manager_id'])->get('name');
						$replies[$key]['manager_email'] = Mymanager::instance($val['manager_id'])->get('email');
					}
					$result['replies'] = $replies;					
				}				
				$list[] = $result;
			}			
        }
        return $list;
    }
}