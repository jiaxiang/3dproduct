<?php defined('SYSPATH') or die('No direct script access.');

class mail_Core {
    private static $is_smtp = false;
    private static $crond_drive = 'mysql';

	/**
	 * 发送邮件
	 * @param string $to 接收人
	 * @param string $subject 主题
	 * @param string $message 邮件内容
	 * @param string $from_emial 发送邮件邮箱
	 * @param stiring $headers 邮件头
	 * @return boolean
	 */
	public static function send_old($to='', $subject='', $message='', $from_email='' , $headers=NULL)
	{
		if(empty($to) || empty($subject) || empty($message))
		{
			return false;
		}
        return @Mail_z::instance()->smtp_send_mail($to, $subject, $message);

		if(is_null($headers))
		{
			/**
			 * 邮件头部信息
			 */
			$headers='';
			$headers.= 'From: '.$from_email. "\r\n";
			$headers.= 'Reply-To: '.$from_email. "\r\n" ;
			$headers.= 'MIME-Version:1.0'. "\r\n";
			$headers.= 'Content-type: text/html; charset=utf8' . "\r\n";
		}

		return mail($to, $subject, $message, $headers);
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
	public static function send($to='', $subject='', $message='', $from_email='' , $headers=NULL)
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
            return Phpmail::instance()->smtp_send_mail($to, $subject, $message);
            //return Mail_z::instance()->smtp_send_mail($to, $subject, $message);
        }
	}

	/**
	 * @param <String> $mail_type mail type falg
	 * @return <Array>
	 */
	public static function mail_by_type($mail_type)
	{
		$mail_category = Mymail_category::instance()->get_by_flag($mail_type);
		//d($mail_category);
		$category_id = $mail_category['id'];
		$mail = Mymail::instance()->get_by_type($category_id);

		return $mail;
	}

	/**
	 * 根据模板得到邮件内容
	 * @param <String> $email_type 邮件类型(邮件类型中的flag)
	 * @param <String> $to_email 接收人
	 * @param <String> $from_email 发送人
	 * @param <Array> $title_param 邮件标题替换内容(eg.array('{firstname}'=>'qin')把firstname替换成qin)
	 * @param <Array> $content_param 邮件替换内容(eg.array('{firstname}'=>'qin')把firstname替换成qin)
	 * @return <Boolean>
	 * eg. mail::send_mail($email_flag='',$to_email='',$from_email='',$title_param=array(),$content_param=array());
	 */
	public static function content($mail_type='',$to_email='',$from_email='',$title_param=array(),$content_param=array()) {
		$mail = self::mail_by_type($mail_type);
		if(!$mail['id'])
		{
			return false;
		}

		$domain = Mysite::instance()->get('domain');
		$domain_link = "<a href='http://".$domain."'>".$domain."</a>";
		$server_email = $from_email;
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
	 * @param <String> $email_flag 邮件类型
	 * @param <String> $to_email 接收人
	 * @param <String> $from_email 发送人
	 * @param <Array> $title_param 邮件标题替换内容(eg.array('{firstname}'=>'qin')把firstname替换成qin)
	 * @param <Array> $content_param 邮件替换内容(eg.array('{firstname}'=>'qin')把firstname替换成qin)
	 * @return <Boolean>
	 * eg. Mymail::send_mail($email_flag='',$to_email='',$from_email='',$title_param=array(),$content_param=array());
	 */
	public static function send_mail($mail_type='',$to_email='',$from_email='',$title_param=array(),$content_param=array())
	{
		//无邮件类型不能发送邮件
		if(empty ($mail_type)) {
			return false;
		}
		//无收件人
		if(empty ($to_email)) {
			return false;
		}
		$mail = self::content($mail_type,$to_email,$from_email,$title_param,$content_param);
		if(!$mail['id'])
		{
			return false;
		}
		$from_email = $mail['from_email'];
		$title = $mail['title_result'];
		//解决标题中文乱码问题
		$title = "=?UTF-8?B?".base64_encode($title)."?=";
		$content = $mail['content_result'];
		/**
		 * 邮件头部信息
		 */
		$headers='';
		$headers.= 'From: '.$from_email. "\r\n";
		$headers.= 'Reply-To: '.$from_email. "\r\n" ;
		$headers.= 'MIME-Version:1.0'. "\r\n";
		$headers.= 'Content-type: text/html; charset=utf8' . "\r\n";
		/**
		 * mail函数发送邮件
		 */
		try {
			$data = array();
			$data['title'] = $title;
			$data['content'] = $content;
//			$data['to_email'] = $to_email;
			$mail_log_id = Mymail_log::instance()->add($data);
            $mail = self::send($to_email, $title, $content);
			if($mail) {
				$update_data = array();
				$update_data['status'] = 1;
				Mymail_log::instance($mail_log_id)->update($update_data);
			}
            return $mail;
		}catch(Exception  $e) {
			return false;
		}
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
				$mail_type = 'order';
				$to_email = $order['email'];
				$from_email = '';
				$param = array(
					'{firstname}'          => $order['shipping_firstname'],
					'{lastname}'           => $order['shipping_lastname'],
					'{order_num}'          => $order['order_num'],
					'{amount}'             => $order['total_real'],
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
				$domain = Mysite::instance()->get('domain');
				$param['order_view_link'] = 'http://' . $domain . '/order/order_detail/' . $order_num;
				$title_param = $param;
				$content_param = $param;
				self::send_mail($mail_type,$to_email,$from_email,$title_param,$content_param);
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
				$mail_type = 'order_payment';
				$to_email = $order['email'];
				$from_email = '';
				$param = array(
					'{firstname}'          => $order['shipping_firstname'],
					'{lastname}'           => $order['shipping_lastname'],
					'{order_num}'          => $order['order_num'],
					'{amount}'             => $order['total_real'],
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
				$domain = Mysite::instance()->get('domain');
				$param['{order_view_link}'] = 'http://' . $domain . '/order/order_detail/' . $order_num;
				$title_param = $param;
				$content_param = $param;
				self::send_mail($mail_type,$to_email,$from_email,$title_param,$content_param);
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

			/* EMS URL  */
			$ems_url = (!empty($shipping_struct['ems_url']))?$shipping_struct['ems_url']:'';
			$ems_num = (!empty($shipping_struct['ems_num']))?$shipping_struct['ems_num']:$order['ems_num'];

			if(is_array($order) && $order['id'] > 0)
			{
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
				self::send_mail($mail_type,$to_email,$from_email,$title_param,$content_param);
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
			$refund_amount = (!empty($refund_struct['refund_amount']))?$refund_struct['refund_amount']:$order['total_real'];
			if(is_array($order) && $order['id'] > 0)
			{
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
				self::send_mail($mail_type,$to_email,$from_email,$title_param,$content_param);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
}
