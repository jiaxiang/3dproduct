<?php 
defined('SYSPATH') OR die('No direct access allowed.');

class Login_Controller extends Controller {
	/**
	 * log in
	 */
	function index()
	{
	    $this->logout(FALSE);
	    
		/* 原请求页面 */
		$request_url = $this->input->get('request_url');
		//用户名和密码输入错误三次后就需要输入验证码
		$login_error_count = Session::instance()->get('login_error_count');
		if(!$login_error_count)
		{
			$login_error_count = 1;
			Session::instance()->set('login_error_count',$login_error_count);
		}

		//判断用户是否是已经登录状态
		$data = role::get_manager();
		//D($data);
		
		if ($data['id'] > 0)
		{
			remind::set(Kohana::lang('o_global.current_status_login'), '/', 'success');
		}
        
		//验证码KEY
		secoder::$seKey = 'opococ.secoder';
		//错误信息
		$message = remind::get_message();
		if ( empty($message))
		{
			$error_display = "none";
			$error = "";
		}
		else
		{
			$error_display = "";
			$error = $message;
		}
        
		//登录
		if ($_POST)
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$secode = $this->input->post('secode');
			$remember = $this->input->post('remember');
            
			//验证验证码
			if ($login_error_count>3 && !secoder::check($secode))
			{
				remind::set(Kohana::lang('o_global.code_input_error'), 'login');
			}            
			
			//验证登录
			$manager = role::log_in($username, $password);
			if(isset($manager['username']))
			{
				//判断普通账号的状态、权限
				if(!role::is_root($manager['username']))
                {
    				if ($manager['active'] <> 1)
    				{
    					ulog::login($manager['id'],1);
    					remind::set(Kohana::lang('o_global.account_was_locked'), 'login');
    				}
                    
    				$actions = role::manager_actions($manager['id'],TRUE);  
    				if (count($actions) < 1)
    				{
    					ulog::login($manager['id'],2);
    					remind::set(Kohana::lang('o_global.account_permission_enough'), 'login');
    				}
                }
                
    			/* 是否记录用户名 */
    			if($remember == 1)
    			{
    				cookie::set('opococ_username',$username);
    			}else{
    				cookie::delete('opococ_username');
    			}
    			//清除记录登录错误记录
    			Session::instance()->delete('login_error_count');
    			//记入SESSION
    			role::set_manager_session($manager);
    			//记录日志
    			ulog::login($manager['id']);
    			if(empty($request_url))
    			{
    				remind::set(Kohana::lang('o_global.login_success'), '/index', 'success');
    			}
                else
                {
    				$request_url = url::base() . urldecode($request_url);
    				remind::set(Kohana::lang('o_global.login_success'), $request_url, 'success');
    			}
			}
			else
			{
				ulog::login();
				$login_error_count++;
				Session::instance()->set('login_error_count',$login_error_count);
				remind::set(Kohana::lang('o_global.user_and_password_error'), 'login');
			}
		}
        
		/* 浏览器记录的用户名 */
		$username = cookie::get('opococ_username');
		$this->template = new View('login');
		$this->template->login_error_count = $login_error_count;
		$this->template->error             = $error;
		$this->template->error_display     = $error_display;
		$this->template->username          = $username;		
		$this->template->render(TRUE);
	}
	
	/**
	 * log out
	 */
	public function logout($showmsg = TRUE)
	{
		$session = Session::instance();
		
		$session->delete('Opococ_manager');
		$session->delete('Opococ_manager_active_time');
		$session->delete('Opococ_manager_login_ip');
		
		/* zhu 删除用户权限列表 */
		$session->delete(acltool::$acl_tag);		
        
		if ($showmsg)
		{
		    remind::set(Kohana::lang('o_global.logout_success'), 'login');
		}
	}
	
	/**
	 * show secode
	 */
	function secoder()
	{
		secoder::$seKey = 'opococ.secoder';
		secoder::entry();
	}
}

