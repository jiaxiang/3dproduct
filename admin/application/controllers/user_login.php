<?php defined('SYSPATH') OR die('No direct access allowed.');

class User_login_Controller extends Controller{

	function is_login(){        
		//判断用户是否是已经登录状态
		$data = role::get_manager();
		if ($data['id'] > 0){
			echo '1';
            die();
		}
	}
    
	function index(){
		//判断用户是否是已经登录状态
		$data = role::get_manager();
		if ($data['id'] > 0)
		{
            $data['success'] = 'true';
			$data['msg'] = 1;
		}else{
            $data['success'] = 'false';
            $data['msg'] = 1;
        }
        
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$secode = $this->input->post('secode');
			$remember = $this->input->post('remember');
        
            $data['success'] = 'false';
            
			//验证登录
			$manager = role::log_in($username, $password);
			if(isset($manager['username'])){
				//判断普通账号的状态、权限
				if(!role::is_root($manager['username'])){
    				if ($manager['active'] <> 1)
    				{
    					ulog::login($manager['id'],1);
    					$data['msg'] =  Kohana::lang('o_global.account_was_locked');
    				}
                    
    				$actions = role::manager_actions($manager['id'],TRUE);    					
    				if (count($actions) < 1)
    				{
    					ulog::login($manager['id'],2);
    					$data['msg'] =  Kohana::lang('o_global.account_permission_enough');
    				}
                }
                
    			//是否记录用户名
    			if($remember == 1){
    				cookie::set('opococ_username',$username);
    			}else{
    				cookie::delete('opococ_username');
    			}
    			//清除记录登录错误记录
    			//Session::instance()->delete('login_error_count');
                
    			//记入SESSION
    			role::set_manager_session($manager);
                
    			//记录日志
    			ulog::login($manager['id']);
                $data['success'] = 'true';
                $data['msg'] = 1;
    			
    			/*if(empty($request_url))
    			{
    				remind::set(Kohana::lang('o_global.login_success'), '/index', 'success');
    			}
                else
                {
    				$request_url = url::base() . urldecode($request_url);
    				remind::set(Kohana::lang('o_global.login_success'), $request_url, 'success');
    			}*/
			}
        die(json_encode($data));
	} 
}

