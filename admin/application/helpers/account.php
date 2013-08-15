<?php defined('SYSPATH') or die('No direct script access.');

class Account_Core{
	/**
	 * 获取账户信息
	 *
	 */
	public static function get($account_id)
	{
		$post_url = "http://manage.backstage-gateway.com/account";
		$post_var = "account_id=".$account_id;
		$result = tool::curl_pay($post_url,$post_var);
		$res	= @unserialize( stripcslashes($result));
		if(!is_array($res))
		{
			log::write('data_error',$result,__FILE__,__LINE__);
		}
		return $res;
	}
/*
Array
(
    [account] => Array
        (
            [id] => 1
            [name] => my_test
            [description] => 我自己的测试账号
            [vip] => 0
            [date_add] => 2010-05-05 15:29:10
            [last_settlement] => 2010-05-06 09:11:09
            [admin_employee_id] => 0
            [active] => 1
        )

    [balance] => Array
        (
            [0] => Array
                (
                    [id] => 1
                    [account_id] => 1
                    [currency] => USD
                    [amount] => 706
                    [fee] => 664
                    [guarantee] => 30
                    [date_add] => 2010-05-05 17:32:38
                    [date_upd] => 2010-05-05 09:32:38
                    [admin_employee_id] => 0
                    [active] => 1
                )

            [1] => Array
                (
                    [id] => 2
                    [account_id] => 1
                    [currency] => GBP
                    [amount] => 93.05
                    [fee] => 6.95
                    [guarantee] => 0
                    [date_add] => 2010-05-06 08:58:06
                    [date_upd] => 2010-05-06 00:58:05
                    [admin_employee_id] => 0
                    [active] => 1
                )

        )

    [history] => Array
        (
            [0] => Array
                (
                    [id] => 1
                    [account_id] => 1
                    [payment_log_id] => 1
                    [account_history_type_id] => 0
                    [message] => 订单金额 100 USD
手续费 5 USD
总金额 +95 USD
                    [currency] =>
                    [amount] => 0
                    [date_add] => 2010-05-05 17:17:39
                    [type] =>
                    [payment_log] => Array
                        (
                            [id] => 0
                            [payment_order_id] => 0
                            [api_id] => 0
                            [order_amount] => 0
                            [income] =>
                            [currency] =>
                            [email] =>
                            [cc_num] =>
                            [cc_type] =>
                            [cc_cvv] =>
                            [cc_exp_date] =>
                            [cc_valid_date] =>
                            [cc_issue] =>
                            [billing_firstname] =>
                            [billing_lastname] =>
                            [billing_zip] =>
                            [billing_address] =>
                            [billing_city] =>
                            [billing_state] =>
                            [billing_country] =>
                            [billing_phone] =>
                            [shipping_firstname] =>
                            [shipping_lastname] =>
                            [shipping_address] =>
                            [shipping_zip] =>
                            [shipping_city] =>
                            [shipping_state] =>
                            [shipping_country] =>
                            [can_capture] =>
                            [is_jump] =>
                            [gc_order_num] =>
                            [payment_status_id] =>
                            [message] =>
                            [context] =>
                            [avs] =>
                            [cvc] =>
                            [return_site] =>
                            [trans_id] =>
                            [ip] =>
                            [date_add] =>
                            [date_upd] =>
                        )

                )

            [1] => Array
                (
                    [id] => 2
                    [account_id] => 1
                    [payment_log_id] => 1
                    [account_history_type_id] => 0
                    [message] => 订单金额 100 USD
手续费 5 USD
总金额 +95 USD
                    [currency] =>
                    [amount] => 0
                    [date_add] => 2010-05-05 17:31:58
                    [type] =>
                    [payment_log] => Array
                        (
                            [id] => 0
                            [payment_order_id] => 0
                            [api_id] => 0
                            [order_amount] => 0
                            [income] =>
                            [currency] =>
                            [email] =>
                            [cc_num] =>
                            [cc_type] =>
                            [cc_cvv] =>
                            [cc_exp_date] =>
                            [cc_valid_date] =>
                            [cc_issue] =>
                            [billing_firstname] =>
                            [billing_lastname] =>
                            [billing_zip] =>
                            [billing_address] =>
                            [billing_city] =>
                            [billing_state] =>
                            [billing_country] =>
                            [billing_phone] =>
                            [shipping_firstname] =>
                            [shipping_lastname] =>
                            [shipping_address] =>
                            [shipping_zip] =>
                            [shipping_city] =>
                            [shipping_state] =>
                            [shipping_country] =>
                            [can_capture] =>
                            [is_jump] =>
                            [gc_order_num] =>
                            [payment_status_id] =>
                            [message] =>
                            [context] =>
                            [avs] =>
                            [cvc] =>
                            [return_site] =>
                            [trans_id] =>
                            [ip] =>
                            [date_add] =>
                            [date_upd] =>
                        )

                )

            [2] => Array
                (
                    [id] => 3
                    [account_id] => 1
                    [payment_log_id] => 1
                    [account_history_type_id] => 0
                    [message] => 订单金额 100 USD
手续费 5 USD
总金额 +95 USD
                    [currency] =>
                    [amount] => 0
                    [date_add] => 2010-05-05 17:47:52
                    [type] =>
                    [payment_log] => Array
                        (
                            [id] => 0
                            [payment_order_id] => 0
                            [api_id] => 0
                            [order_amount] => 0
                            [income] =>
                            [currency] =>
                            [email] =>
                            [cc_num] =>
                            [cc_type] =>
                            [cc_cvv] =>
                            [cc_exp_date] =>
                            [cc_valid_date] =>
                            [cc_issue] =>
                            [billing_firstname] =>
                            [billing_lastname] =>
                            [billing_zip] =>
                            [billing_address] =>
                            [billing_city] =>
                            [billing_state] =>
                            [billing_country] =>
                            [billing_phone] =>
                            [shipping_firstname] =>
                            [shipping_lastname] =>
                            [shipping_address] =>
                            [shipping_zip] =>
                            [shipping_city] =>
                            [shipping_state] =>
                            [shipping_country] =>
                            [can_capture] =>
                            [is_jump] =>
                            [gc_order_num] =>
                            [payment_status_id] =>
                            [message] =>
                            [context] =>
                            [avs] =>
                            [cvc] =>
                            [return_site] =>
                            [trans_id] =>
                            [ip] =>
                            [date_add] =>
                            [date_upd] =>
                        )

                )

            [3] => Array
                (
                    [id] => 4
                    [account_id] => 1
                    [payment_log_id] => 1
                    [account_history_type_id] => 0
                    [message] => 订单金额 100 USD
手续费 5 USD
总金额 +95 USD
                    [currency] =>
                    [amount] => 0
                    [date_add] => 2010-05-05 17:49:58
                    [type] =>
                    [payment_log] => Array
                        (
                            [id] => 0
                            [payment_order_id] => 0
                            [api_id] => 0
                            [order_amount] => 0
                            [income] =>
                            [currency] =>
                            [email] =>
                            [cc_num] =>
                            [cc_type] =>
                            [cc_cvv] =>
                            [cc_exp_date] =>
                            [cc_valid_date] =>
                            [cc_issue] =>
                            [billing_firstname] =>
                            [billing_lastname] =>
                            [billing_zip] =>
                            [billing_address] =>
                            [billing_city] =>
                            [billing_state] =>
                            [billing_country] =>
                            [billing_phone] =>
                            [shipping_firstname] =>
                            [shipping_lastname] =>
                            [shipping_address] =>
                            [shipping_zip] =>
                            [shipping_city] =>
                            [shipping_state] =>
                            [shipping_country] =>
                            [can_capture] =>
                            [is_jump] =>
                            [gc_order_num] =>
                            [payment_status_id] =>
                            [message] =>
                            [context] =>
                            [avs] =>
                            [cvc] =>
                            [return_site] =>
                            [trans_id] =>
                            [ip] =>
                            [date_add] =>
                            [date_upd] =>
                        )

                )

            [4] => Array
                (
                    [id] => 5
                    [account_id] => 1
                    [payment_log_id] => 1
                    [account_history_type_id] => 1
                    [message] => 订单金额 100 USD
手续费 5 USD
总金额 +95 USD
                    [currency] =>
                    [amount] => 0
                    [date_add] => 2010-05-05 17:51:59
                    [type] => capture
                    [payment_log] => Array
                        (
                            [id] => 0
                            [payment_order_id] => 0
                            [api_id] => 0
                            [order_amount] => 0
                            [income] =>
                            [currency] =>
                            [email] =>
                            [cc_num] =>
                            [cc_type] =>
                            [cc_cvv] =>
                            [cc_exp_date] =>
                            [cc_valid_date] =>
                            [cc_issue] =>
                            [billing_firstname] =>
                            [billing_lastname] =>
                            [billing_zip] =>
                            [billing_address] =>
                            [billing_city] =>
                            [billing_state] =>
                            [billing_country] =>
                            [billing_phone] =>
                            [shipping_firstname] =>
                            [shipping_lastname] =>
                            [shipping_address] =>
                            [shipping_zip] =>
                            [shipping_city] =>
                            [shipping_state] =>
                            [shipping_country] =>
                            [can_capture] =>
                            [is_jump] =>
                            [gc_order_num] =>
                            [payment_status_id] =>
                            [message] =>
                            [context] =>
                            [avs] =>
                            [cvc] =>
                            [return_site] =>
                            [trans_id] =>
                            [ip] =>
                            [date_add] =>
                            [date_upd] =>
                        )

                )

            [5] => Array
                (
                    [id] => 6
                    [account_id] => 1
                    [payment_log_id] => 1
                    [account_history_type_id] => 1
                    [message] => 订单金额 100 USD
手续费 5 USD
总金额 +95 USD
                    [currency] => USD
                    [amount] => -100
                    [date_add] => 2010-05-05 17:53:42
                    [type] => capture
                    [payment_log] => Array
                        (
                            [id] => 0
                            [payment_order_id] => 0
                            [api_id] => 0
                            [order_amount] => 0
                            [income] =>
                            [currency] =>
                            [email] =>
                            [cc_num] =>
                            [cc_type] =>
                            [cc_cvv] =>
                            [cc_exp_date] =>
                            [cc_valid_date] =>
                            [cc_issue] =>
                            [billing_firstname] =>
                            [billing_lastname] =>
                            [billing_zip] =>
                            [billing_address] =>
                            [billing_city] =>
                            [billing_state] =>
                            [billing_country] =>
                            [billing_phone] =>
                            [shipping_firstname] =>
                            [shipping_lastname] =>
                            [shipping_address] =>
                            [shipping_zip] =>
                            [shipping_city] =>
                            [shipping_state] =>
                            [shipping_country] =>
                            [can_capture] =>
                            [is_jump] =>
                            [gc_order_num] =>
                            [payment_status_id] =>
                            [message] =>
                            [context] =>
                            [avs] =>
                            [cvc] =>
                            [return_site] =>
                            [trans_id] =>
                            [ip] =>
                            [date_add] =>
                            [date_upd] =>
                        )

                )

            [6] => Array
                (
                    [id] => 7
                    [account_id] => 1
                    [payment_log_id] => 1
                    [account_history_type_id] => 1
                    [message] => 订单金额 100 USD
手续费 5 USD
总金额 +95 USD
                    [currency] => USD
                    [amount] => 100
                    [date_add] => 2010-05-05 18:03:44
                    [type] => capture
                    [payment_log] => Array
                        (
                            [id] => 0
                            [payment_order_id] => 0
                            [api_id] => 0
                            [order_amount] => 0
                            [income] =>
                            [currency] =>
                            [email] =>
                            [cc_num] =>
                            [cc_type] =>
                            [cc_cvv] =>
                            [cc_exp_date] =>
                            [cc_valid_date] =>
                            [cc_issue] =>
                            [billing_firstname] =>
                            [billing_lastname] =>
                            [billing_zip] =>
                            [billing_address] =>
                            [billing_city] =>
                            [billing_state] =>
                            [billing_country] =>
                            [billing_phone] =>
                            [shipping_firstname] =>
                            [shipping_lastname] =>
                            [shipping_address] =>
                            [shipping_zip] =>
                            [shipping_city] =>
                            [shipping_state] =>
                            [shipping_country] =>
                            [can_capture] =>
                            [is_jump] =>
                            [gc_order_num] =>
                            [payment_status_id] =>
                            [message] =>
                            [context] =>
                            [avs] =>
                            [cvc] =>
                            [return_site] =>
                            [trans_id] =>
                            [ip] =>
                            [date_add] =>
                            [date_upd] =>
                        )

                )

            [7] => Array
                (
                    [id] => 8
                    [account_id] => 1
                    [payment_log_id] => 1
                    [account_history_type_id] => 1
                    [message] => 订单金额 100 USD
手续费 5 USD
总金额 +95 USD
                    [currency] => USD
                    [amount] => 100
                    [date_add] => 2010-05-05 18:04:48
                    [type] => capture
                    [payment_log] => Array
                        (
                            [id] => 0
                            [payment_order_id] => 0
                            [api_id] => 0
                            [order_amount] => 0
                            [income] =>
                            [currency] =>
                            [email] =>
                            [cc_num] =>
                            [cc_type] =>
                            [cc_cvv] =>
                            [cc_exp_date] =>
                            [cc_valid_date] =>
                            [cc_issue] =>
                            [billing_firstname] =>
                            [billing_lastname] =>
                            [billing_zip] =>
                            [billing_address] =>
                            [billing_city] =>
                            [billing_state] =>
                            [billing_country] =>
                            [billing_phone] =>
                            [shipping_firstname] =>
                            [shipping_lastname] =>
                            [shipping_address] =>
                            [shipping_zip] =>
                            [shipping_city] =>
                            [shipping_state] =>
                            [shipping_country] =>
                            [can_capture] =>
                            [is_jump] =>
                            [gc_order_num] =>
                            [payment_status_id] =>
                            [message] =>
                            [context] =>
                            [avs] =>
                            [cvc] =>
                            [return_site] =>
                            [trans_id] =>
                            [ip] =>
                            [date_add] =>
                            [date_upd] =>
                        )

                )

            [8] => Array
                (
                    [id] => 9
                    [account_id] => 1
                    [payment_log_id] => 1
                    [account_history_type_id] => 1
                    [message] => 订单金额 100 USD
手续费 5 USD
总金额 +95 USD
                    [currency] => USD
                    [amount] => 100
                    [date_add] => 2010-05-05 18:05:05
                    [type] => capture
                    [payment_log] => Array
                        (
                            [id] => 0
                            [payment_order_id] => 0
                            [api_id] => 0
                            [order_amount] => 0
                            [income] =>
                            [currency] =>
                            [email] =>
                            [cc_num] =>
                            [cc_type] =>
                            [cc_cvv] =>
                            [cc_exp_date] =>
                            [cc_valid_date] =>
                            [cc_issue] =>
                            [billing_firstname] =>
                            [billing_lastname] =>
                            [billing_zip] =>
                            [billing_address] =>
                            [billing_city] =>
                            [billing_state] =>
                            [billing_country] =>
                            [billing_phone] =>
                            [shipping_firstname] =>
                            [shipping_lastname] =>
                            [shipping_address] =>
                            [shipping_zip] =>
                            [shipping_city] =>
                            [shipping_state] =>
                            [shipping_country] =>
                            [can_capture] =>
                            [is_jump] =>
                            [gc_order_num] =>
                            [payment_status_id] =>
                            [message] =>
                            [context] =>
                            [avs] =>
                            [cvc] =>
                            [return_site] =>
                            [trans_id] =>
                            [ip] =>
                            [date_add] =>
                            [date_upd] =>
                        )

                )

            [9] => Array
                (
                    [id] => 10
                    [account_id] => 1
                    [payment_log_id] => 1
                    [account_history_type_id] => 1
                    [message] => 订单金额 100 USD
手续费 5 USD
总金额 +95 USD
                    [currency] => USD
                    [amount] => 100
                    [date_add] => 2010-05-05 18:05:07
                    [type] => capture
                    [payment_log] => Array
                        (
                            [id] => 0
                            [payment_order_id] => 0
                            [api_id] => 0
                            [order_amount] => 0
                            [income] =>
                            [currency] =>
                            [email] =>
                            [cc_num] =>
                            [cc_type] =>
                            [cc_cvv] =>
                            [cc_exp_date] =>
                            [cc_valid_date] =>
                            [cc_issue] =>
                            [billing_firstname] =>
                            [billing_lastname] =>
                            [billing_zip] =>
                            [billing_address] =>
                            [billing_city] =>
                            [billing_state] =>
                            [billing_country] =>
                            [billing_phone] =>
                            [shipping_firstname] =>
                            [shipping_lastname] =>
                            [shipping_address] =>
                            [shipping_zip] =>
                            [shipping_city] =>
                            [shipping_state] =>
                            [shipping_country] =>
                            [can_capture] =>
                            [is_jump] =>
                            [gc_order_num] =>
                            [payment_status_id] =>
                            [message] =>
                            [context] =>
                            [avs] =>
                            [cvc] =>
                            [return_site] =>
                            [trans_id] =>
                            [ip] =>
                            [date_add] =>
                            [date_upd] =>
                        )

                )

        )

)

*/
}
