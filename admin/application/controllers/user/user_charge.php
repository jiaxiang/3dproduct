<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_charge_Controller extends Template_Controller {

	public function index($status = 0){
		//权限检查
		role::check('user_charge');
        
        $where = '';
        $lists = $uid = $users =array();
        
		//每页显示条数
		$status   = $this->input->get('status');
		$per_page = $this->input->get('per_page', 20);
        
        $db = Database::instance();
        
		if(isset($status)){
			$where = " WHERE status='{$status}' ";
		}
        $count = array_shift($db->query('SELECT COUNT(*) AS tot FROM user_charge_order '.$where)->result_array());
        
		//调用分页
		$this->pagination = new Pagination(array(
			'total_items'    => $count->tot,
			'items_per_page' => $per_page,
		));
        
        //lists
        $rs = $db->query('SELECT * FROM user_charge_order '.$where.$this->pagination->sql_limit);
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
		$this->template->content = new View("user/user_charge_list", array(
                    'lists'  => $lists, 
                    'users'  => $users,
                    'status' => $status,
            )
        );
	}

	/**
	 * 改变状态
	 */
	function do_active($id){
		//权限验证
		role::check('user_charge');
		if(!$id){
			remind::set(Kohana::lang('o_global.bad_request'), 'user/user_charge');
		}
        
        $db = Database::instance();
        $data = array_shift($db->query('SELECT * FROM user_charge_order WHERE id='.$id)->result_array(false));
        
		if($data['id']<=0 || $data['status']>0){
			remind::set(Kohana::lang('o_global.bad_request'), 'user/user_charge');
		}
        
		$logodata = array();
		$logodata['manager_id'] = $this->manager_id;
		$logodata['ip'] = tool::get_str_ip();
		$logodata['user_log_type'] = 27;
		$logodata['method'] = __CLASS__.'::'.__METHOD__.'()';    
		$logodata['memo'] = "充值订单号:".$data['order_num'].", 购买拍点数:".$data['price'].", 充值金额:".$data['money'];
        $sql = "UPDATE user_charge_order SET status=1 WHERE id='".$id."' ";
		if($db->query($sql)){      
            //充值用户Money
            $sql_reward = "UPDATE users 
                            SET user_money = user_money+".$data['price']."
                            WHERE id='".$data['user_id']."'
                          ";
            $db->query($sql_reward);
            
            //操作log   
            ulog::add($logodata);
			remind::set(Kohana::lang('o_global.update_success'), 'user/user_charge','success');
		}else{
            //操作log   
            ulog::add($logodata, 1);
			remind::set(Kohana::lang('o_global.update_error'), 'user/user_charge','error');
		}
	}
    

}
