<?php
defined ( 'SYSPATH' ) or die ( 'No direct access allowed.' );
/**
 * Cc Payment Driver.
 *
 * $Id: Cc.php 3769 2010-02-02  wdf $
 */
class Payment_Cc_Driver extends Payment_Driver {
    private $fileds = array (
    		'order_num' => '',
            'order_amount' => '',
            'order_currency' => '',
    		'card_num' => '', 
            'card_type' => '', 
            'card_cvv' => '', 
            'card_exp_month' => '', 
            'card_exp_year' => '', 
            'card_valid_month' => '', 
            'card_valid_year' => '', 
            'card_issue' => '', 
            'billing_firstname' => '', 
            'billing_lastname' => '', 
            'billing_address' => '', 
            'billing_zip' => '', 
            'billing_city' => '', 
            'billing_state' => '', 
            'billing_country' => '', 
            'billing_telephone' => '', 
            'billing_ip_address' => '', 
            'billing_email' => '',
            'shipping_firstname' => '',
            'shipping_lastname' => '',
            'shipping_address' => '',
            'shipping_zip' => '',
            'shipping_city' => '',
            'shipping_state' => '',
            'shipping_country' => '',
            'secure_code' => '',
            'site_id' => '',
            'submit_url' => '',
    );
    
    public function set($fileds)
    {
        foreach($fileds as $key => $filed){
            if(isset($this->fileds[$key])){
                $this->fileds[$key] = $filed;
            }
        }
        if(!empty($this->fileds['card_type'])){
            $this->fileds['card_valid_month'] = ($this->fileds['card_type'] == 'Maestro' || $this->fileds['card_type'] == 'Solo') ? $this->fileds['card_valid_month'] : '';
            $this->fileds['card_valid_year'] = ($this->fileds['card_type'] == 'Maestro' || $this->fileds['card_type'] == 'Solo') ? $this->fileds['card_valid_year'] : '';
            $this->fileds['card_issue'] = ($this->fileds['card_type'] == 'Maestro' || $this->fileds['card_type'] == 'Solo') ? $this->fileds['card_issue'] : '';
        }
    }

    /*
     * 提交支付处理
     */
    public function handle()
    {
        //echo kohana::debug($this->fileds);
        //数据验证
        $validator = new Validation($this->fileds);
		$validator->pre_filter('trim');
		$validator->add_rules('submit_url', 'required');
		$validator->add_rules('order_num', 'required');
		$validator->add_rules('order_amount', 'required');
		$validator->add_rules('order_currency', 'required');
		$validator->add_rules('card_num', 'required');
		$validator->add_rules('card_type','required');
		$validator->add_rules('card_cvv','required');
		$validator->add_rules('card_exp_month', 'required');
		$validator->add_rules('card_exp_year','required');
		$validator->add_rules('card_valid_month', 'standard_text');
		$validator->add_rules('card_valid_year','standard_text');
		$validator->add_rules('card_issue', 'standard_text');
		if (!($validator->validate()))
		{
			//错误输出,js层已经过滤，一般不会输出
			$validate_errors = $validator->errors();
			$errors = '';
			foreach ($validate_errors as $key => $val) {
				$errors.= $key.' failed rule '.$val.'<br>';
			}
			throw new PaymentException($errors, 400);
		}
        $post_url = $this->fileds['submit_url'];
        $post_var = "order_num=" . $this->fileds['order_num'] . 
        			"&order_amount=" . $this->fileds['order_amount'] . 
        			"&order_currency=" . $this->fileds['order_currency'] . 
        			"&card_num=" . $this->fileds['card_num'] . 
        			"&card_type=" . $this->fileds['card_type'] . 
        			"&card_cvv=" . $this->fileds['card_cvv'] . 
        			"&card_exp_month=" . $this->fileds['card_exp_month'] . 
        			"&card_exp_year=" . $this->fileds['card_exp_year'] . 
        			"&card_issue=" . $this->fileds['card_issue'] . 
        			"&card_valid_month=" . $this->fileds['card_valid_month'] . 
        			"&card_valid_year=" . $this->fileds['card_valid_year'] . 
        			"&billing_firstname=" . $this->fileds['billing_firstname'] . 
        			"&billing_lastname=" . $this->fileds['billing_lastname'] . 
        			"&billing_address=" . $this->fileds['billing_address'] . 
        			"&billing_zip=" . $this->fileds['billing_zip'] . 
        			"&billing_city=" . $this->fileds['billing_city'] . 
        			"&billing_state=" . $this->fileds['billing_state'] . 
        			"&billing_country=" . $this->fileds['billing_country'] . 
        			"&billing_telephone=" . $this->fileds['billing_telephone'] . 
        			"&billing_ip_address=" . $this->fileds['billing_ip_address'] . 
        			"&billing_email=" . $this->fileds['billing_email'] . 
        			"&shipping_firstname=" . $this->fileds['shipping_firstname'] . 
        			"&shipping_lastname=" . $this->fileds['shipping_lastname'] . 
        			"&shipping_address=" . $this->fileds['shipping_address'] . 
        			"&shipping_zip=" . $this->fileds['shipping_zip'] . 
        			"&shipping_city=" . $this->fileds['shipping_city'] . 
        			"&shipping_state=" . $this->fileds['shipping_state'] . 
        			"&shipping_country=" . $this->fileds['shipping_country'] . 
        			"&secure_code=" . $this->fileds['secure_code'] . 
        			"&site_id=" . $this->fileds['site_id'];
        $result = tool::curl_pay ( $post_url, $post_var );
        $error_msg = '';
        $is_serialization = tool::check_serialization ( $result, $error_msg );
        $res = null;
        if ($is_serialization)
        {
            $result = stripcslashes ($result);
            $res = unserialize ($result);
        }
        $return_key = array (
                'status_id', 
                'trans_id', 
                'message', 
                'api', 
                'avs', 
                'status', 
                'flag' 
        );
        $return_arr = arr::init_arr($return_key, $res);
        return $return_arr;
    }
}
