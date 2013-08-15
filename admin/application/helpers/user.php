<?php defined('SYSPATH') or die('No direct script access.');
/**
 * 用户管理工具方法
 */
class user_Core {
    private static $instance = NULL;

    // 获取单态实例
    public static function get_instance(){
        if(self::$instance === null){
            $classname = __CLASS__;
            self::$instance = new $classname();
        }
        return self::$instance;
    }

    public function encrypt_passwd($passwd) {
    	$secret = Kohana::config('site_config.site.secret_pwd');
    	return Mytool::hash(Mytool::hash($passwd).$secret);
    }

	/**
	 * 判断用户是否登录
	 */
	public static function logged_in()
	{
		$session = Session::instance();
		$user = $session->get('user');
		if($user)
		{
			return $user;
		}else{
        	return FALSE;
		}
    }

	/**
	 * 用户登录验证
	 *
	 * @param  	string 	$email
	 * @param 	String 	$password
	 * @return 	Int 	user id
	 */
	public function login($email, $password, $type = 0)
	{
	    $field = 'lastname';
	    if ($type == 1)
	    {
	        $field = 'email';
	    }

		$user = ORM::factory('user')
			->where($field, $email)
			->where('password',Mytool::hash($password))
			->where('active',1)
			->find();

		if($user->loaded)
		{
			return $user->id;
		}
		else
		{
			return 0;
		}
	}

	/*
	 * 获取用户信息
	 *
	 *  @param  	Int 	 用户id
	 *  @return 	array() 用户信息
	 */

	public function get($user_id)
	{
		$user = ORM::factory('user', $user_id);
		if ($user->loaded)
		{
		    return $user->as_array();
		}
		return FALSE;
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
	    $return = 0;
	    $money = $this->get_user_moneys($user_id);

	    if (!empty($money))
	    {
	        $return = $money['all_money'];
	    }
	    return $return;
	}


	/*
	 * 获取用户所有余额
	 *
	 *  @param  	int 	 用户id
	 *  @return 	array
	 */
	public function get_user_moneys($user_id)
	{
	    $return = array();
	    $user = $this->get($user_id);
	    if (!empty($user))
	    {
	        $return['user_money'] = $user['user_money'];
	        $return['bonus_money'] = $user['bonus_money'];
	        $return['free_money'] = $user['free_money'];
	        $return['all_money'] = $return['user_money'] + $return['bonus_money'] + $return['free_money'];
	        return $return;
	    }
	    else
	    {
	        return $return;
	    }
	}

	public function get_user_virtual_money($user_id) {
		$return = 0;
	    $user = $this->get($user_id);

	    if (!empty($user))
	    {
	        $return = $user['virtual_money'];
	    }
	    return $return;
	}

    /**
     * 用户注册处理
     * @param array $user
     * @param string $password
     */
	public static function register_process($user,$password)
	{
		//TODO add register log
		$return = 'register successfully';
		User_logService::instance($user['id'])->add('register',$return);
	    $data=array('user_money' => '100','free_money' => '100');
		self::edit($user['id'], $data);
		/* 注册邮件的参数结构 */
		$register_struct = array();
		$register_struct['user_id'] = $user['id'];
		$register_struct['password'] = $password;

		mail::register($register_struct);

		//判断用户账号是否激活
		if($user['register_mail_active'])
		{
			user::login_process($user);
		}
	}

	/**
	 * 用户登录
	 * @param array $user
	 */
	public static function login_process($user)
	{
		$session = Session::instance();
		$session->set('user',$user);

        $data = array();
        $data['id'] = $user['id'];
        $data['login_ip'] = Input::instance()->ip_address();
        $data['login_time'] = date('Y-m-d H:i:s',time());
        self::edit($data['id'], $data);

		//TODO add logining log
		$return = 'login successfully';
		UlogService::get_instance()->add($user['id'], 'login', $return);
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
		if($user->validate($data,TRUE))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}


	/**
	 * 退出操作
	 */
	public static function logout_process()
	{
		$session = Session::instance();
		$user = $session->get('user');
		$session->delete('user',NULL);
		//删除购物车中的相关信息
		$session->delete('coupon_code',NULL);
		$session->delete('cart',NULL);

		//TODO add logout log
		$return = 'logout successfully';
		UlogService::get_instance()->add($user['id'], 'logout', $return);
	}

	/**
	 * 用户找回密码
	 * @param int $user_id
	 * @param string $email
	 * @param string $token
	 */
	public static function find_password_process($user_id,$email,$username,$password){
		mail::find_password($user_id,$email,$username,$password);
	}

	/**
	 * 邮箱验证发送邮件
	 * @param int $user_id
	 * @param string $email
	 * @param string $token
	 */
	public static function check_email($user_id,$email,$key){
		mail::check_email($user_id,$email,$key);
	}

    /**
     * author zhubin
     * 根据注册项信息生成相应的html代码
     * @param $user_profile array  注册项信息
     * @param $class  string  css属性
     * return string
     */
    public static function show_view($user_attribute, $class=array())
    {
    	//补充css类选择器
        $default_class=array('text'=>'','select'=>'','radio'=>'','checkbox'=>'',
                            'required'=>'','numeric'=>'','string'=>'','numeric_string'=>'');
		$class = array_merge($default_class,$class);
        $type = kohana::config('user_attribute_type.attribute.'.$user_attribute['attribute_type']);
        $user_attribute['attribute_name'] = str_replace(' ','_',$user_attribute['attribute_name']);
        $html = '';
        if(!is_array($type['form']) && !empty($type['form']))
        {
            switch($type['form'])
            {
                case 'text':
                	$cla = $class[$type['form']];
                	if($user_attribute['attribute_required'])
                	{
                         $cla = $class[$type['form']].' '.$class['required'];
                	}
                	$name = $user_attribute['attribute_name'].'_'.$user_attribute['id'];
                	$attribute_type_arr = explode('.', $user_attribute['attribute_type']);

                    if($attribute_type_arr[0] == 'time')
		            {//对时间的处理
		            	$html .= '<input type="'.$type['form'].'" name="'.$name.'" id="'.$name.'" class="'.$cla.'" value="'.$user_attribute['user_attribute_value'].'"/>';
	                    if($type['item'] == 'datepicker')
			            {
			            	$date_form = str_replace('_','-',$attribute_type_arr[1]);
			                $html .= "
			                <script type=\"text/javascript\">
			                $(document).ready(function(){
			                    $(\"#$name\").datepicker({
			                        changeMonth: true,
			                        changeYear: true,
			                        yearRange:\"1950:".date('Y',time())."\",
			                        dateFormat: \"$date_form\"
			                    });
			                });
			                </script>
			                ";
			            }
		            }else if($attribute_type_arr[0] == 'input'){
		              switch($attribute_type_arr[1])
		              {
		                  case 'numeric':
		                  	$cla .= ' '.$class['numeric'];

		                  	break;
		                  case 'string':
		                  	$cla .= ' '.$class['string'];
		                  	break;
		                  case 'numeric_string':
		                  	$cla .= ' '.$class['numeric_string'];
		                  	break;
		                  default:
		                  	break;
		              }
		              $html .= '<input type="'.$type['form'].'" name="'.$name.'" id="'.$name.'" class="'.$cla.' length255" value="'.$user_attribute['user_attribute_value'].'"/>';
		            }else{
		            $html .= '<input type="'.$type['form'].'" name="'.$name.'" id="'.$name.'" class="'.$cla.' length255" value="'.$user_attribute['user_attribute_value'].'"/>';
		            }
                    break;
                case 'select':
                	$cla = $class[$type['form']];
                    if($user_attribute['attribute_required'])
                    {
                         $cla = $class[$type['form']].' '.$class['required'];
                    }
                    $attribute_options = explode(',', trim($user_attribute['attribute_option'], ','));
                    $name = $user_attribute['attribute_name'].'_'.$user_attribute['id'];
                    $html .= '<select name="'.$name.'"  class="'.$cla.'">';
                    foreach($attribute_options as $attribute_option)
                    {
                    	$selected = '';
                    	if($attribute_option==$user_attribute['user_attribute_value'])
                    	{
                    	   $selected='selected="selected"';
                    	}
                        $html .= '<option value="'.$attribute_option.'" '.$selected.'>'.$attribute_option.'</option>';
                    }
                    $html .= "</select>";

                    break;
                case 'radio':
                    $cla = $class[$type['form']];
                    if($user_attribute['attribute_required'])
                    {
                         $cla = $class[$type['form']].' '.$class['required'];
                    }
                    $name = $user_attribute['attribute_name'].'_'.$user_attribute['id'];
                    $attribute_options = explode(',', trim($user_attribute['attribute_option'], ','));
                    foreach($attribute_options as $attribute_option)
                    {
                        $checked = '';
                        if($attribute_option==$user_attribute['user_attribute_value'])
                        {
                           $checked='checked="true"';
                        }
                        $html .= $attribute_option.'<input type="'.$type['form'].'" name="'.$name.'" value="'.$attribute_option.'" '.$checked.' class="'.$cla.'"/>';
                    }
                    break;
                case 'checkbox':
                    $cla = $class[$type['form']];
                    if($user_attribute['attribute_required'])
                    {
                         $cla = $class[$type['form']].' '.$class['required'];
                    }
                    $name = $user_attribute['attribute_name'].'_'.$user_attribute['id'];
                    $attribute_options = explode(',', trim($user_attribute['attribute_option'], ','));
                    foreach($attribute_options as $attribute_option)
                    {
                        $checked = '';
                        if(!empty($user_attribute['user_attribute_value']))
                        {
	                        foreach($user_attribute['user_attribute_value'] as $option_value)
	                        {
		                        if($attribute_option==$option_value)
		                        {
		                           $checked='checked="true"';
		                           break;
		                        }
	                        }
                        }
                        $html .=$attribute_option.'<input type="'.$type['form'].'" name="'.$name.'[]" value="'.$attribute_option.'" '.$checked.' class="'.$cla.'"/>';
                    }
                    break;
                default:
                    break;
            }
        }
        return $html;
    }

    /**
     * 取得用户的等级
     */
    public static function user_level()
    {
    	$session = Session::instance();
    	$user = $session->get('user');
    	if(empty($user))
    	{
    		return false;
    	}else{
    		$user_level = User_levelService::get_instance()->get_level($user['level_id']);
    		return !empty($user_level['name'])?$user_level['name']:'';
    	}
    }

    /**
     * 取得用户的积分
     */
    public static function user_score()
    {
    	$session = Session::instance();
    	$user = $session->get('user');
    	if(empty($user))
    	{
    		return false;
    	}else{
    		return floor($user['score']);
    	}
    }

    /*
     * 更新用户资金  将返回用户总金额
     * @param	int	   $user_id   用户id
     * @param	array  $user_moneys 资金包
     * @return  bool   true or false
     */
    public function update_moneys($user_id, $user_moneys)
    {
        //d($user_moneys, false);

        $user_id = intval($user_id);

        if (empty($user_id))
            return  FALSE;

        $userobj = ORM::factory('user', $user_id);
    	if ($userobj->loaded)
        {
            $userobj->user_money = $user_moneys['user_money'];
            $userobj->bonus_money = $user_moneys['bonus_money'];
            $userobj->free_money = $user_moneys['free_money'];
            $userobj->save();

            if ($userobj->saved)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
    }





    /*
     * 更新用户钱  将返回用户金额
     */
    public function update_money($user_id, $add_money)
    {
        try
        {
            $user_id = intval($user_id);

            if ( empty($user_id))
                return  FALSE;

                $userobj = ORM::factory('user', $user_id);
            	if ($userobj->loaded)
		        {
		            $userobj->user_money = $add_money;
		            $userobj->save();
		            if ($userobj->saved)
		            {
		                return TRUE;
		            }
		            else
		            {
		                return FALSE;
		            }
		        }
        }
        catch (MyRuntimeException $ex)
        {
            return FALSE;
            //throw new MyRuntimeException('', 404);
        }


    }


	/**
	 * user register
	 *
	 * @param 	Array 	register data
	 * @return 	Int  	user id
	 */
	public function register($data)
	{
		$user = ORM::factory('user');
		$data['password'] = Mytool::hash($data['password']);
		if($user->validate($data, FALSE))
		{
			$user->save();
			return $user->id;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * is the user reigstered?
	 *
	 * @param 	String 	email
	 * @return 	boolean|user id
	 */
	public function is_register($check, $type = 0)
	{
	    $field = 'email';
	    if ($type == 1)
	        $field = 'lastname';

		$user = ORM::factory('user')
			->where($field, $check)
			->find();

		//print $user->last_query();

		if($user->loaded)
		{
			return $user->id;
		}
		else
		{
			return 0;
		}
	}


	/*
	 * 检查是否登录带提示
	 */
	public function check_login()
	{
	    $check = $this->check_user_login();
	    if (empty($check))
	    {
	        remind::set('您还没有登录，请先登录会员中心！', 'success', url::base().'user/login?redirect='.url::current(TRUE));
	    }
	}


	/*
	 * 检查是否登录
	 */
	public  function check_user_login()
	{
	    $user = Session::instance()->get('user');
	    if (empty($user))
	    {
	        return FALSE;
	    }
	    else
	    {
	        return $user['id'];
	    }
	}


    /**
	 * 验证用户密码
	 *
	 * @param 	Str $password
	 * @return 	Boolean
	 */
	public function check_password($uid, $password)
	{
	    $user = ORM::factory('user')
			->where('id', $uid)
			->where('password',tool::hash($password))
			->find();
		if($user->loaded)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	//验证用户提现密码
	public function check_draw_password($uid,$password){

		$user = ORM::factory('user')
				->where('id',$uid)
				->where('draw_password',tool::hash($password))
				->find();

		if($user->loaded)
		{
			return true;
		}else{
			return false;
			}
	}

	/**
	 * update user password
	 *
	 * @param 	Str 	$password
	 * @return 	Int 	user id | 0
	 */
	public function update_password($uid, $password)
	{
		$user = ORM::factory('user', $uid);
		if($user->loaded)
		{
			$user->password = tool::hash($password);
			$user->save();
			$this->data = $user->as_array();
			return $user->id;
		}
		else
		{
			return 0;
		}
	}

	//更新用户提现密码
	public function update_draw_password($uid,$password){
		$user = ORM::factory('user',$uid);
		if($user->loaded){
			$user->draw_password = tool::hash($password);
			$user->save();
			$this->data = $user->as_array();
			return $user->id;
			}else{
				return 0;
				}
		}

	/**
	 * 更新用户密码保护信息
	 * Enter description here ...
	 * @param int $uid
	 * @param int $question
	 * @param string $answer
	 * @return true/false
	 */
	public function update_password_protection($uid, $question, $answer) {
		$return = false;
		$user = ORM::factory('user', $uid);
		if ($user->loaded) {
			$user->question = $question;
			$user->answer = $answer;
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
	public function update_user_info($uid, $data) {
		$return = false;
		$user = ORM::factory('user', $uid);
		if ($user->loaded) {
			$user->title = $data['title'];
			$user->email = $data['email'];
			$user->identity_card = $data['identity_card'];
			$user->sex = $data['sex'];
			$user->address = $data['address'];
			$user->zip_code = $data['zip_code'];
			$user->tel = $data['tel'];
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
	 * 更新用户信息
	 * Enter description here ...
	 * @param int $uid
	 * @param array $data
	 * @return true/false
	 */
	public function update_question_info($uid, $data) {
		$return = false;
		$user = ORM::factory('user', $uid);
		if ($user->loaded) {
			$user->question = $data['question'];
			$user->answer = $data['answer'];
			$user->save();
			if ($user->saved == true) {
				$this->data = $user->as_array();
				$return = true;
			}
		}
		return $return;
	}


	/*
	 * 用户支付并返回订单号
	 * @param $user_id int
	 * @param $price int
	 * @return string 订单号
	 */
	public function get_user_charge_order($user_id, $price)
	{
	    $obj = ORM::factory('user_charge_order');
	    $ordernum = '';
        do
        {
            $ordernum = date('YmdHis') .rand(0, 99999);
            if(!$this->charge_exist($ordernum))
                break;
        }
        while (1);

        $obj->status = 0;
        $obj->user_id = $user_id;
        $obj->money = $price;
        $obj->order_num = $ordernum;
        $obj->ip = tool::get_str_ip();
        $obj->save();

        return $ordernum;

	}

    /*
     * 检查订单号,存在则返回订单信息
     * @param $order_num string 订单号
     * @return true or false
     */
    public function charge_exist($order_num)
    {
        $obj = ORM::factory('user_charge_order');
        $result = $obj->where('order_num', $order_num)->find();

        if ($obj->loaded)
        {
            return $result->as_array();
        }
        else
        {
            return FALSE;
        }
    }


    /*
     * 更新订单状态,诸如充值成功或失败
     * @param order_num string 订单号
     * @return status 状态 0,1,2
     */
    public function charge_update($order_num, $status)
    {
        $obj = ORM::factory('user_charge_order');
        $result = $obj->where('order_num', $order_num)->find();

        if ($obj->loaded)
        {
            $obj->status = $status;
            $obj->save();
        }
        else
        {
            return FALSE;
        }
    }


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

	/**
	 * 更新用户虚拟资金
	 * @param unknown_type $user_id
	 * @param unknown_type $add_money
	 */
	public function update_virtual_money($user_id, $add_money)
	{
		try
		{
			$user_id = intval($user_id);

			if ( empty($user_id))
				return  FALSE;

			$userobj = ORM::factory('user', $user_id);
			if ($userobj->loaded)
			{
				$userobj->virtual_money = $add_money;
				$userobj->save();
				if ($userobj->saved)
				{
					return TRUE;
				}
				else
				{
					return FALSE;
				}
			}
		}
		catch (MyRuntimeException $ex)
		{
			return FALSE;
			//throw new MyRuntimeException('', 404);
		}
	}


}