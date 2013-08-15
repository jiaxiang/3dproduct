<?php defined('SYSPATH') OR die('No direct access allowed.');
class Payment_Core {
	/**
	 * 往支付网关发送数据
	 * 用于pp支付或者其他跳转支付的验证流程
	 * 参数是order数组等等
	 * 注意pay_id必须
	 */
	public static function send_payment_detail($order,$pay_id,$secure_code,$submit_url)
	{

		//$post_url = "https://www.backstage-gateway.com/pp";
		$post_url = $submit_url;
		$post_var = "order_num=".$order['order_num']
						."&order_amount=".$order['total_real']
						."&order_currency=".$order['currency']
						."&billing_firstname=".$order['billing_firstname']
						."&billing_lastname=".$order['billing_lastname']
						."&billing_address=".$order['billing_address']
						."&billing_zip=".$order['billing_zip']
						."&billing_city=".$order['billing_city']
						."&billing_state=".$order['billing_state']
						."&billing_country=".$order['billing_country']
						."&billing_telephone=".$order['billing_phone']
						."&billing_ip_address=".long2ip($order['ip'])
						."&billing_email=".$order['email']
						."&shipping_firstname=".$order['shipping_firstname']
						."&shipping_lastname=".$order['shipping_lastname']
						."&shipping_address=".$order['shipping_address']
						."&shipping_zip=".$order['shipping_zip']
						."&shipping_city=".$order['shipping_city']
						."&shipping_state=".$order['shipping_state']
						."&shipping_country=".$order['shipping_country']
						."&trans_id=".$order['trans_id']
						."&secure_code=".$secure_code
						."&site_id=".$pay_id;
		$result			= tool::curl_pay($post_url,$post_var);

		$res			= @unserialize( stripcslashes($result));
		if(is_array($res)){
			return true;
		}else{
			return false;
		}
	}

}
