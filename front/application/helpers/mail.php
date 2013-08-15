<?php defined('SYSPATH') or die('No direct script access.');

class mail_Core {
    private static $is_smtp = false;
    private static $crond_drive = 'mysql';
    const ADMIN_EMAIL = 'jiaxianglu@gmail.com';
    //const ADMIN_EMAIL = 'zhongzhengjie@gmail.com';

	/**
	 * 发送邮件
	 * @param string $to 接收人
	 * @param string $subject 主题
	 * @param string $message 邮件内容
	 * @param string $from_emial 发送邮件邮箱
	 * @param stiring $headers 邮件头
	 * @return boolean
	 */
	public static function send($to='', $subject='', $message='', $from_email='webmaster@jingbo365.com' , $headers=NULL)
	{
		if(empty($to) || empty($subject) || empty($message))
		{
			return false;
		}

		if(self::$is_smtp){
            $task_id = Crond::get_instance(self::$crond_drive)->add_mail_task($to,$subject,$message,$from_email);
            d($task_id);
            if($task_id > 0){
                return true;
            } else {
                return false;
            }
        } else {
            return Phpmail::instance()->smtp_send_mail($to, $subject, $message);
            //return Mail_z::instance()->smtp_send_mail($to, $subject, $message);
        }
	}

	/**
	 * 发送邮件
	 * @param string $to 接收人
	 * @param string $subject 主题
	 * @param string $message 邮件内容
	 * @param string $from_emial 发送邮件邮箱
	 * @param stiring $headers 邮件头
	 * @return boolean
	 */
	/*public static function send_($to='', $subject='', $message='', $from_email='webmaster@bizark.cn' , $headers=NULL)
	{
		if(empty($to) || empty($subject) || empty($message))
		{
			return false;
		}

        if(self::$is_smtp){
            $task_id = Crond::get_instance(self::$crond_drive)->add_mail_task($to,$subject,$message,$from_email);
            if($task_id > 0){
                return true;
            } else {
                return false;
            }
        } else {
            $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
    		if(is_null($headers))
    		{
    			//邮件头部信息
    			$headers='';
    			$headers.= 'From: '.$from_email. "\r\n";
    			$headers.= 'Reply-To: '.$from_email. "\r\n" ;
    			$headers.= 'MIME-Version:1.0'. "\r\n";
    			$headers.= 'Content-type: text/html; charset=utf8' . "\r\n";
    		}
    		return mail($to, $subject, $message, $headers);
        }
	}*/

	/**
	 * get mail by site_id and mail_category_flag
	 * @param <String> $mail_type mail type falg
	 * @param <Int> $site_id site id
	 * @return <Array>
	 */
	public static function mail_by_type($site_id,$mail_type)
	{
		$mail_category = Mymail_category::instance()->get_by_flag($mail_type);
		$category_id = $mail_category['id'];
		$mail = Mymail::instance()->get_by_type($site_id,$category_id);
		return $mail;
	}

	/**
	 * 根据模板得到邮件内容
	 * @param <Int> $site_id 站点ID
	 * @param <String> $email_type 邮件类型(邮件类型中的flag)
	 * @param <String> $to_email 接收人
	 * @param <String> $from_email 发送人
	 * @param <Array> $title_param 邮件标题替换内容(eg.array('{firstname}'=>'qin')把firstname替换成qin)
	 * @param <Array> $content_param 邮件替换内容(eg.array('{firstname}'=>'qin')把firstname替换成qin)
	 * @return <Boolean>
	 * eg. self::send_mail($site_id=0,$email_flag='',$to_email='',$from_email='',$title_param=array(),$content_param=array());
	 */
	public static function content($site_id=1,$mail_type='',$to_email='',$from_email='',$title_param=array(),$content_param=array()) {
		$site = Mysite::instance()->get();
		$mail = self::mail_by_type($site['id'], $mail_type);
		if(!$mail['id']){
			return false;
		}

		//d($content_param);
		$domain = $site['domain'];
		$domain_link = "<a href='".url::base()."'>".$domain."</a>";
		$server_email = $site['site_email'];
		$server_email_link = "<a href='mailto:".$server_email."'>".$server_email."</a>";

		//TODO
		//邮件内部自动替换内容
		$global_param = array(
			'{site}'=>$domain,
			'{site_link}'=>$domain_link,
			'{server_email}'=>$server_email,
			'{server_email_link}'=>$server_email_link
		);

		if(is_array($title_param)) {
			$title_param = array_merge($global_param,$title_param);
		}else {
			$title_param = $global_param;
		}

		if(is_array($content_param)) {
			$content_param = array_merge($global_param,$content_param);
		}else {
			$content_param = $global_param;
		}

		$content = $mail['content'];
		$title = $mail['title'];
		if(empty($from_email)) {
			$from_email = $server_email;
		}
		/**
		 * 替换标题变量
		 */
		if($title_param) {
			foreach($title_param as $key=>$value) {
				$title = str_ireplace($key, $value,$title);
			}
		}
		/**
		 * 替换内容中的变量
		 */
		if($content_param) {
			foreach($content_param as $key=>$value) {
				$content = str_ireplace($key, $value,$content);
			}
		}

		$mail['content_result'] = $content;
		$mail['title_result'] = $title;
		$mail['from_email'] = $from_email;

		return $mail;
	}

	/**
	 * 根据模板发送系统邮件
	 * @param <Int> $site_id 站点ID
	 * @param <String> $email_flag 邮件类型
	 * @param <String> $to_email 接收人
	 * @param <String> $from_email 发送人
	 * @param <Array> $title_param 邮件标题替换内容(eg.array('{firstname}'=>'qin')把firstname替换成qin)
	 * @param <Array> $content_param 邮件替换内容(eg.array('{firstname}'=>'qin')把firstname替换成qin)
	 * @return <Boolean>
	 * eg. Mymail::send_mail($site_id=0,$email_flag='',$to_email='',$from_email='',$title_param=array(),$content_param=array());
	 */
	public static function send_mail($site_id=1,$mail_type='',$to_email='',$from_email='',$title_param=array(),$content_param=array())
	{
		//无邮件类型不能发送邮件
		if(empty ($mail_type)) {
			return false;
		}
		//无收件人
		if(empty ($to_email)) {
			return false;
		}

		$mail = self::content($site_id,$mail_type,$to_email,$from_email,$title_param,$content_param);

		if(!$mail['id']){
			return false;
		}
		$from_email = $mail['from_email'];
		$title = $mail['title_result'];
		//解决标题中文乱码问题
		$title = "=?UTF-8?B?".base64_encode($title)."?=";
		$content = $mail['content_result'];

		/**
		 * mail函数发送邮件
		 */
		try {
			$data = array();
			$data['to_email'] = $to_email;
			$data['title'] = $title;
			$data['content'] = $content;
			$mail_log_id = Mymail_log::instance()->add($data);

			$mail = @self::send($to_email, $title, $content, $from_email);
			//d($mail);
			if($mail) {
				$update_data = array();
				$update_data['status'] = 1;
				Mymail_log::instance($mail_log_id)->update($update_data);
				return $mail;
			}else {
				return $mail;
			}
		}catch(Exception  $e) {
			return false;
		}
	}

    /**
     * 用户注册时发送邮件
     * @param array $register_struct
     */
	public static function register($register_struct = array())
	{
	    //return FALSE;
		$user_id = (!empty($register_struct['user_id']))?$register_struct['user_id']:0;
		$password = (!empty($register_struct['password']))?$register_struct['password']:0;

		if($user_id < 1)
		{
			return false;
		}

		/* 用户详情 */
		$user = user::get_instance()->get($user_id);

		$site_id = Mysite::instance()->id();

		/* 邮件类型 */
		$mail_type = 'reg';

		$to_email = $user['email'];
		$firstname = $user['firstname'];
		$lastname = $user['lastname'];

		$param_data = array();
		$from_email = '';
		$title_param = array();
		$content_param = array(
		    '{email}'=>$to_email,
		    '{lastname}'=>$lastname,
		    '{password}'=>$password
		);
		$content_param = array_merge($content_param,$param_data);
		$title_param = $content_param;

		self::send_mail(1,$mail_type,$to_email,$from_email,$title_param,$content_param);
	}

	/**
	 * 找回密码邮件
	 * @param int $user_id
	 * @param string $email
	 * @param string $token 找回密码加密串
	 */
	public static function find_password($user_id,$email,$username,$password)
	{
		$mail_type = 'forget';
		$to_email = $email;
		$from_email = '';
		$expire = date('Y-m-d H:i',time() + Kohana::config('password.time'));
		$getpwd_url = route::action('get_password')."?u=".$user_id."&t=".$token;
		$getpwd_link = '<a href="'.$getpwd_url.'" target="_blank">'.$getpwd_url.'</a>';
		$title_param = array();
		$content_param = array(
		    '{email}'=>$email,
		    '{expire}'=>$expire,
		   /* '{url}'=>$getpwd_url,
		    '{url_link}'=>$getpwd_link*/
			'{username}'=>$username,
			'{password}'=>$password
		);
		self::send_mail(1,$mail_type,$to_email,$from_email,$title_param,$content_param);
	}

		/**
	 * 邮箱验证
	 * @param int $user_id
	 * @param string $email
	 * @param string $token 找回密码加密串
	 */
	public static function check_email($user_id,$email,$key)
	{
		$mail_type = 'reply_inquiry';
		$to_email = $email;
		$from_email = '';
		$expire = date('Y-m-d H:i',time() + Kohana::config('password.time'));
		$check_url = route::action('check_email')."user/check_email/?key=".$key;
		$check_link = '<a href="'.$check_url.'" target="_blank">'.$check_url.'</a>';
		$title_param = array();
		$content_param = array(
		    '{email}'=>$email,
		    '{expire}'=>$expire,
		    '{url}'=>$check_url,
		    '{url_link}'=>$check_link,
		);
		self::send_mail(1,$mail_type,$to_email,$from_email,$title_param,$content_param);
	}


	/**
	 * 邮箱验证
	 * @param int $user_id
	 * @param string $email
	 * @param string $token 找回密码加密串
	 */
	public static function check_register($uid,$email,$lastname,$key)
	{
		$mail_type = 'register_mail_active';
		$to_email = $email;
		$from_email = '';
		$expire = date('Y-m-d H:i',time() + Kohana::config('password.time'));
		$check_url = route::action('check_register')."user/reg_success/?key=".$key."&id=".$uid."&u=".$lastname."&e=".$email;
		$check_link = '<a href="'.$check_url.'" target="_blank">'.$check_url.'</a>';
		$title_param = array();
		$content_param = array(
			'{username}'=>$lastname,
		    '{email}'=>$email,
		    '{expire}'=>$expire,
		    '{url}'=>$check_url,
		    '{url_link}'=>$check_link,
		);
		self::send_mail(1,$mail_type,$to_email,$from_email,$title_param,$content_param);
	}

	public static function order_create($email, $lastname, $order_num) {
		$mail_type = 'create_order';
		$to_email = $email;
		$from_email = '';
		$title_param = array();
		$content_param = array(
				'{username}'=>$lastname,
				'{email}'=>$email,
				'{order}'=>$order_num,
		);
		self::send_mail(1,$mail_type,$to_email,$from_email,$title_param,$content_param);
	}

	public static function order_create2admin($userinfo, $order_id) {
		$mail_type = 'create_order2admin';
		$to_email = self::ADMIN_EMAIL;
		$from_email = '';
		$title_param = array();
		$order_link = 'http://180.153.223.69:3689/order/detail?search_type=order_id&search_value='.$order_id;
		$content_param = array(
				'{username}'=>$userinfo['username'],
				'{name}'=>$userinfo['name'],
				'{email}'=>$userinfo['email'],
				'{mobile}'=>$userinfo['mobile'],
				'{order}'=>$order_link,
				'{time}'=>date('Y-m-d H:i:s'),
		);
		self::send_mail(1,$mail_type,$to_email,$from_email,$title_param,$content_param);
	}



	/*
	 * 生成订单邮件
	 * @param string $order_num
	 */
	public static function order_created($order_num = '')
	{
		if(!empty($order_num))
		{
			$order = Myorder::instance()->get_by_order_num($order_num);
			if(is_array($order) && $order['id'] > 0)
			{
				$site_id = Mysite::instance()->id();
				$user = Myuser::instance($order['user_id'])->get();
				$mail_type = 'order';
				$to_email = $order['email'];
				$from_email = '';
				$param = array(
					'{firstname}'          => $user['firstname'],
					'{lastname}'           => $user['lastname'],
					'{order_num}'          => $order['order_num'],
					'{amount}'             => $order['total'],
					'{currency}'           => $order['currency'],
					'{shipping_firstname}' => $order['shipping_firstname'],
					'{shipping_lastname}'  => $order['shipping_lastname'],
					'{shipping_address}'   => $order['shipping_address'],
					'{shipping_city}'      => $order['shipping_city'],
					'{shipping_state}'     => $order['shipping_state'],
					'{shipping_country}'   => $order['shipping_country'],
					'{shipping_zip}'       => $order['shipping_zip'],
					'{shipping_phone}'     => $order['shipping_phone'],
					'{shipping_mobile}'    => $order['shipping_mobile']
				);
				/* 订单查询链接 */
				$param['{order_view_link}'] = url::base() . 'order/order_detail/' . $order_num;
				$title_param = $param;
				$content_param = $param;
				self::send_mail(1,$mail_type,$to_email,$from_email,$title_param,$content_param);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * 订单支付成功邮件
	 * @param stiring $order_num
	 */
	public static function payment_success($order_num = '')
	{
		if(!empty($order_num))
		{
			$order = Myorder::instance()->get_by_order_num($order_num);
			if(is_array($order) && $order['id'] > 0)
			{
				$site_id = Mysite::instance()->id();
				$mail_type = 'order_payment';
				$to_email = $order['email'];
				$from_email = '';
				$param = array(
					'{firstname}'          => $order['shipping_firstname'],
					'{lastname}'           => $order['shipping_lastname'],
					'{order_num}'          => $order['order_num'],
					'{amount}'             => $order['total'],
					'{currency}'           => $order['currency'],
					'{shipping_firstname}' => $order['shipping_firstname'],
					'{shipping_lastname}'  => $order['shipping_lastname'],
					'{shipping_address}'   => $order['shipping_address'],
					'{shipping_city}'      => $order['shipping_city'],
					'{shipping_state}'     => $order['shipping_state'],
					'{shipping_country}'   => $order['shipping_country'],
					'{shipping_zip}'       => $order['shipping_zip'],
					'{shipping_phone}'     => $order['shipping_phone'],
					'{shipping_mobile}'    => $order['shipping_mobile']
				);
				/* 订单查询链接 */
				$param['{order_view_link}'] = url::base() . 'order/order_detail/' . $order_num;
				$title_param = $param;
				$content_param = $param;
				self::send_mail(1,$mail_type,$to_email,$from_email,$title_param,$content_param);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * 发货邮件
	 * @param array $ship_struct
	 */
	public static function shipping($shipping_struct = array())
	{
		$order_num = (!empty($shipping_struct['order_num']))?$shipping_struct['order_num']:'';
		if(!empty($order_num))
		{
			$order = Myorder::instance()->get_by_order_num($order_num);

			/* EMS URL */
			$ems_url = (!empty($shipping_struct['ems_url']))?$shipping_struct['ems_url']:'';
			$ems_num = (!empty($shipping_struct['ems_num']))?$shipping_struct['ems_num']:$order['ems_num'];

			if(is_array($order) && $order['id'] > 0)
			{
				$site_id = Mysite::instance()->id();
				$mail_type = 'shipping';
				$to_email = $order['email'];
				$from_email = '';
				$param = array(
					'{firstname}'          => $order['shipping_firstname'],
					'{lastname}'           => $order['shipping_lastname'],
					'{order_num}'          => $order['order_num'],
					'{ems_num}'            => $ems_num,
					'{ems_url}'            => $ems_url
				);

				$title_param = $param;
				$content_param = $param;
				self::send_mail(1,$mail_type,$to_email,$from_email,$title_param,$content_param);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * 退款邮件
	 * @param string $refund_struct 订单号
	 */
	public static function refund($refund_struct = array())
	{
		$order_num = (!empty($refund_struct['order_num']))?$refund_struct['order_num']:'';
		if(!empty($order_num))
		{
			$order = Myorder::instance()->get_by_order_num($order_num);

			/* 退款金额  */
			$refund_amount = (!empty($refund_struct['refund_amount']))?$refund_struct['refund_amount']:$order['total'];
			if(is_array($order) && $order['id'] > 0)
			{
				$site_id = Mysite::instance()->id();
				$mail_type = 'refund';
				$to_email = $order['email'];
				$from_email = '';
				$param = array(
					'{firstname}'          => $order['shipping_firstname'],
					'{lastname}'           => $order['shipping_lastname'],
					'{order_num}'          => $order['order_num'],
					'{amount}'             => $refund_amount,
					'{currency}'           => $order['currency']
				);

				$title_param = $param;
				$content_param = $param;
				self::send_mail(1,$mail_type,$to_email,$from_email,$title_param,$content_param);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	/**
	 * 用于提交newsletter时发送邮件
	 * @param $email
	 */
	public static function newsletter($email)
	{
		$site_id = Mysite::instance()->id();
		$mail_type = 'newsletter';

	    $config = Mycoupon::instance()->is_generate_discount_num('newsletter');
        $discount_info = array();

        if(!empty($config))
        {
          $discount_info = Mycoupon::instance()->get_discount_number($config);
        }

        $paramData = array();
        if(!empty($discount_info)){
            $paramData = array(
                '{discount_num}'    => $discount_info['discount_num'],
                '{expiration_date}' => $discount_info['expiration_date'],
                '{discount_value}'  => $discount_info['discount_value'],
            );
        }
		if(empty($paramData)){
		  $mail_type = 'newsletterNoDiscountNumber';
		}

		$to_email = $email;
		$from_email = '';
		$title_param = array();
		$content_param = $paramData;
		self::send_mail(1,$mail_type,$to_email,$from_email,$title_param,$content_param);
	}

	/**
     * 用于提交emailafriend时发送邮件
     * @param $from   自己的邮箱
     * @param $to     朋友的邮箱
     * @param $title_param
     * @param $content_param
     */
   public static function emailafriend($from,$to,$title_param,$content_param)
    {
        $site_id = Mysite::instance()->id();
        $mail_type = 'tell_a_friend';

        $config = Mycoupon::instance()->is_generate_discount_num('email_a_friend');
        $discount_info = array();
        if(!empty($config))
        {
          $discount_info = Mycoupon::instance()->get_discount_number($config);
        }

        $paramData = array();
        if(!empty($discount_info)){
            $paramData = array(
                '{discount_num}'    => $discount_info['discount_num'],
                '{expiration_date}' => $discount_info['expiration_date'],
                '{discount_value}'  => $discount_info['discount_value'],
            );
        }

        if(empty($paramData)){
          $mail_type = 'tell_a_friendNoDiscountNumber';
        }
        $to_email = $to;
        $from_email = $from;
        $content_param = array_merge($content_param,$paramData);
        self::send_mail(1,$mail_type,$to_email,$from_email,$title_param,$content_param);
    }
    /**
     * 用于提交emailafriend时给自己发送邮件
     * @param $email   自己的邮件地址
     * @param $title_param   标题变量
     * @param $content_param 内容变量
     */
    public static function email_to_me($email, $title_param, $content_param){
        $site_id = Mysite::instance()->id();
        //判断是否生成打折号并取得打折号的折扣信息
        $config = Mycoupon::instance()->is_generate_discount_num('email_to_me');
    	if(!empty($config))
        {
        	$discount_info = Mycoupon::instance()->get_discount_number($config);
        }
        //设置打折信息
    	$param_data = array();
        if(!empty($discount_info))
        {
            $param_data = array(
                '{discount_num}'    => $discount_info['discount_num'],
                '{expiration_date}' => $discount_info['expiration_date'],
                '{discount_value}'  => $discount_info['discount_value'],
            );
        }
        //判断取得的模板类型
        $mail_type = 'email_to_me';
    	if(empty($param_data)){
          $mail_type = 'email_to_meNoDiscountNumber';
        }
        $content_param = array_merge($content_param,$param_data);
        //发送邮件
        $from_mail = '';
        $to_mail = $email;
        self::send_mail(1, $mail_type, $to_mail, $from_mail, $title_param, $content_param);
    }


    /**
     * 用于提交emailafriend时给自己发送邮件
     * @param $email   自己的邮件地址
     * @param $title_param   标题变量
     * @param $content_param 内容变量
     */
    public static function register_mail_active($user)
    {
    	//生成验证码
    	$vc = md5(md5($user['id'] + 'ABC123')+ 'ABC123');
    	$active_url = url::base().'active_user?user_id='.$user['id'].'&vc='.$vc;
        $user['firstname'] = isset($user['firstname'])?$user['firstname']:'';
    	$content_param = array(
		    '{email}'=>$user['email'],
		    '{firstname}'=>$user['firstname'],
    	    '{lastname}'=>$user['lastname'],
    		'{active_url}'=>$active_url,
    		'{active_link}'=>"<a href=\"".$active_url."\" targer=\"_blank\">".$active_url."</a>",
		);
		$title_param = array(
			'{firstname}'=>$user['firstname'],
			'{lastname}'=>$user['lastname'],
		);
		$from_mail = '';
		$to_mail = $user['email'];
		$mail_type = 'register_mail_active';
		self::send_mail(1, $mail_type, $to_mail, $from_mail, $title_param, $content_param);
    }

}
