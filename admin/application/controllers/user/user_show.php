<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_show_Controller extends Template_Controller {

	public function index($status = 0){
		//权限检查
		role::check('user_show');
        
        $where = '';
        $lists = $uid = $users =array();
        
		//每页显示条数
		$per_page    = $this->input->get('per_page', 20);
        
        $db = Database::instance();
        
		if($status >0){
			$where = " WHERE status='{$status}' ";
		}
        $count = array_shift($db->query('SELECT COUNT(*) AS tot FROM user_show '.$where)->result_array());
        
		//调用分页
		$this->pagination = new Pagination(array(
			'total_items'    => $count->tot,
			'items_per_page' => $per_page,
		));
        
        //晒宝
        $rs = $db->query('SELECT * FROM user_show '.$where.$this->pagination->sql_limit);
        foreach($rs as $row){
            $uid[$row->user_id] = $row->user_id;
            $lists[] = (array)$row;
        }
        
        //用户
        if(!empty($uid)){
            $rs = $db->query('SELECT id, lastname FROM users WHERE id IN ('.implode(',', $uid).')');
            foreach($rs as $row){
                $users[$row->id] = $row->lastname;
            }
        }
        
		//调用列表
		$this->template->content = new View("user/user_show_list", array(
                    'lists' => $lists, 
                    'users' => $users,
            )
        );
	}

	/**
	 * 修改信息
	 */
	function edit(){
		role::check('user_show');
		$return_struct = array(
			'status' => 0,
			'code'	 => 501,
			'msg'	 => 'Not Implemented'
		);
		$id = intval($this->input->get('id'));
		if(!$id){
            remind::set(Kohana::lang('o_global.illegal_data'),'user/user_show');
		}

        $db = Database::instance();
        $data = array_shift($db->query('SELECT * FROM user_show WHERE id='.$id)->result_array(false));
        
		if(!$data['id']){
            remind::set(Kohana::lang('o_global.illegal_data'),'user/user_show');
		}

        if($_POST){
            $status = (int)$_POST['status'];
            $sql = "UPDATE user_show SET status='".$status."' ";
            if((int)$data['get_money']==0 && !empty($_POST['get_money']) && (int)$_POST['get_money']>0){
                $money = (int)$_POST['get_money'];
                $sql .= ", get_money = ".$money;
                
                //奖励用户
                $db->query("UPDATE users SET user_money = user_money + {$money} WHERE id='".$data['user_id']."' ");
                
                //奖励记录
                $sql_reward = "INSERT INTO user_reward 
                                SET user_id='".$data['user_id']."',
                                reward_money= '{$money}',
                                memo= '晒宝奖励',
                                reward_type='reward'
                              ";
                $db->query($sql_reward);
            }
            
            $db->query($sql." WHERE id='".$id."' ");
            remind::set(Kohana::lang('o_global.update_success'), 'user/user_show', 'success');
        }        
        
        //用户
        if($data['user_id']>0){
            $data['user'] = array_shift($db->query('SELECT lastname FROM users WHERE id='.$data['user_id'])->result_array(false));
        }
        
        //图片
        if($data['image_ids']){
            $data['images'] = $db->query('SELECT * FROM user_upload_image WHERE status!=1 AND id in ('.$data['image_ids'].')')->result_array(false);
        }
        
		$this->template->content = new View("user/user_show_edit", array(
                    'data' => $data,
                    'front_domain' => site::default_domain()
            )
        );
	}

	/**
	 * 改变留言状态
	 */
	function do_active($id){
		//权限验证
		role::check('user_show');
		if(!$id){
			remind::set(Kohana::lang('o_global.bad_request'), 'user/user_show');
		}
        
        $db = Database::instance();
        $data = array_shift($db->query('SELECT * FROM user_show WHERE id='.$id)->result_array(false));
        
		if($data['id']<=0){
			remind::set(Kohana::lang('o_global.bad_request'), 'user/user_show');
		}
        $status = ($data['status'] == 1)?0:1;
        $sql = "UPDATE user_show SET status='".$status."' WHERE id='".$id."' ";
		if($db->query($sql)){
			remind::set(Kohana::lang('o_global.update_success'),request::referrer(),'success');
		}else{
			remind::set(Kohana::lang('o_global.update_error'),request::referrer(),'error');
		}
	}
	
    /**
     * 批量删除留言
     */
    public function batch_delete()
    {
        role::check('user_show');
        //初始化返回数据
        $return_data = array();
        //请求结构体
        $request_data = array();
        /* 可管理的站点ID列表 */
        
        $loginfo = Role::get_manager();
        try {
            $user_messageids = $this->input->post('user_messageids');
            
            if(is_array($user_messageids) && count($user_messageids) > 0)
            {
            	
                /* 初始化默认查询条件 */
                $query_struct = array(
                    'where'=>array(
                        'id'   => $user_messageids,
                    ),
                    'like'=>array(),
                    'limit'     => array(
                        'per_page'  =>300,
                        'offset'    =>0
                    ),
                );
                $user_messages = Mycontact_us::instance()->query_assoc($query_struct);
                
                /* 删除失败的 */
                $failed_message_names = '';
                /* 执行操作 */
                foreach($user_messages as $key=>$message)
                {
                    if(!Mycontact_us::instance($message['id'])->delete())
                    {
                        $failed_message_names .= ' | ' . $message['name'];
                    }
                }
                if(empty($failed_message_names))
                {
                	$return_struct['action'] = array(
                		'type'=>'location',
                		'url'=>url::base().'user/user_show/'
            		);
                    throw new MyRuntimeException(Kohana::lang('o_user.delete_user_message_success'),200);
                }
                else
                {
                    /* 中转提示页面的停留时间 */
                    $return_struct['action']['time'] = 10;
                    $failed_message_names = trim($failed_message_names,' | ');
                    throw new MyRuntimeException(Kohana::lang('o_user.delete_user_message_error',$failed_message_names),403);
                }
            }
            else
            {
                throw new MyRuntimeException(Kohana::lang('o_global.data_load_error'),403);
            }
        } catch (MyRuntimeException $ex) {
            $return_struct['code']   = $ex->getCode();
            $return_struct['msg']    = $ex->getMessage();
            $return_struct['status'] = $return_struct['code']==200?1:0;
            //TODO 异常处理
            //throw $ex;
            if($this->is_ajax_request()) {
                $this->template = new View('layout/empty_html');
                $this->template->content = $return_struct['msg'];
            } else {
                $this->template->return_struct = $return_struct;

                $content = new View('info');
                $this->template->content = $content;
                /* 请求结构数据绑定 */
                $this->template->content->request_data = $request_data;
                /* 返回结构体绑定 */
                $this->template->content->return_struct = $return_struct;
            }
        }
    }
    
	/* 删除图片 */
	function delete_show_img(){
		role::check('user_show');
        $data = array('code' => -1);
		$id = intval($this->input->get('id'));
        
		if($id<=0){
            $data['msg'] = Kohana::lang('o_global.illegal_data');
		}else{
            Database::instance()->query("UPDATE user_upload_image SET status=1 WHERE id='".$id."' ");
            $data['code'] = 1;
            $data['msg'] = "success";
        }
        exit(json_encode($data));
	}

}
