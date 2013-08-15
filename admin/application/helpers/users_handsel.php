<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 用户管理工具方法
 */
class users_handsel_Core {
    private static $instance = NULL;

    // 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }
    
	/*
	 * 获取用户信息
	 * 
	 *  @param  	Int 	 用户id
	 *  @return 	array() 用户信息
	 */
	
	public function get($user_id)
	{
		//$user = ORM::factory('users_handsel', $user_id);
		$user = ORM::factory('users_handsel');
        $result = $user->where('uid', $user_id)->find();
		if ($user->loaded)
		{
		    return $result->as_array();
		}
		return FALSE;
	}
	
	/*
	 * 用户身份证，邮箱，手机号码唯一性验证。
	 * 
	 * 
	 */
	 public function check_idc_email_mobile($filed,$value){
		 $users_handsel = ORM::factory('users_handsel');
		 $array = array(
			$filed	=>	$value,
		 );
		 $users_handsel->where($array)->find();

		 if($users_handsel->loaded)
		 {
			 return true;
		 }else
		 {		
			 return false;
		 }
	}

	public function handsel_add($data)
	{
		$user_handsel = ORM::factory('users_handsel');
		if($user_handsel->validate($data, FALSE))
		{
			$user_handsel->save();
			return $user_handsel->id;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	 * 通过用户名获取用户信息
	 * 
	 *  @param  	Int 	 用户id
	 *  @return 	array() 用户信息
	 */
    public function get_search($lastname)
    {
        $user = ORM::factory('user');
        $result = $user->where('lastname', $lastname)->find();
        
        if ($user->loaded)
        {
            return $result->as_array();
        }
        else
        {
            return FALSE;
        }
    }
	
	/*
	 * 通过用户名和邮箱判断用户合法性
	 * 
	 * 
	 */
	 public function check_user($lastname,$email){
		 $user = ORM::factory('user');
		 $array = array(
			'lastname'	=>	$lastname,
			'email'	 	=>	$email
		 );

		 $result = $user->where($array)->find();

		 if($user->loaded)
		 {
			 return $result->as_array();
		 }else
		 {
			 			
			 return false;
		 }
	}
	
	
	/*
	 * 获取用户余额
	 * 
	 *  @param  	int 	 用户id
	 *  @return 	int 	余额
	 */
	
	public function get_user_money($user_id)
	{
	    $user = $this->get($user_id);
	    if (!empty($user))
	    {
	        return  $user['user_money'];
	    }
	    else 
	    {
	        return FALSE;
	    }
	}	
	

	/**
	 * edit user information
	 *
	 * @param   Array   $data
	 * @return 	Boolean
	 */
	public static function edit($uid, $data)
	{
		$uid = intval($uid);
		$user = ORM::factory('user', $uid);
		if($user->validate($data, TRUE))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}	
	
	/**
	 * 更新用户信息
	 * Enter description here ...
	 * @param int $uid
	 * @param array $data
	 * @return true/false
	 */
	public function update_user_info($uid, $data) {
		$return = false;
		$user = ORM::factory('user', $uid);
		if ($user->loaded) {
			$user->real_name = $data['real_name'];
			$user->email = $data['email'];
			$user->identity_card = $data['identity_card'];
			$user->sex = $data['sex'];
			$user->address = $data['address'];
			$user->zip_code = $data['zip_code'];
		//	$user->tel = $data['tel'];
			$user->mobile = $data['mobile'];
			$user->birthday = $data['birthday'];
			$user->save();
			if ($user->saved == true) {
				$this->data = $user->as_array();
				$return = true;
			}
		}
		return $return;
	}
	
	
		/**
	 * 更新用户状态
	 * Enter description here ...
	 * @param int $uid
	 * @param array $data
	 * @return true/false
	 */
	public function update_user($uid, $data) {
		$return = false;
		$user = ORM::factory('user', $uid);
		if ($user->loaded) {
			$user->active = $data['active'];
			$user->save();
			if ($user->saved == true) {
				$this->data = $user->as_array();
				$return = true;
			}
		}
		return $return;
		
	}
	
		/**
	 * 更新用户信息
	 * Enter description here ...
	 * @param int $uid
	 * @param array $data
	 * @return true/false
	 */
	public function update_user_free_money($uid, $data) {
		$return = false;
		$user = ORM::factory('user', $uid);
		if ($user->loaded) {
		//	$user->user_money = $data['user_money'];
			$user->free_money = $data['free_money'];
		//	$user->title = $data['free_money'];
		//	$data=array('user_money' => '100','free_money' => '100');
			$user->save();
			if ($user->saved == true) {
				$this->data = $user->as_array();
				$return = true;
			}
		}
		return $return;
	}
    
    
    /*
     * 统计统一ip注册数量
     * 
     */
    public function get_count_ip($ip)
    {
        $obj = ORM::factory('user');
        $obj->where("ip", $ip)->where('check_status', 2);
        return $obj->count_all();
    }
	
    /*
	* 用户资料修改验证邮箱
	*/
    public function validator_email($email){
		$session = Session::instance();
		$user = $session->get('user');
		$userinfo = self::get($user['id']);
		if($email!=$userinfo['email'])
		{
		  $isReg = self::is_register($email); 
		  if($isReg)
		  {
			   return false;  
		  }
		}
		return true;
	}
	
	public function delete($id)
	{
		$users_handsel = ORM::factory('users_handsel',$id);
		if(!$users_handsel->loaded){
		  return false;
		}
		$users_handsel->delete();
		//$this->clear_uris();
		return TRUE;
	}
}