<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Jump Payment Driver.
 *
 * $Id: Jump.php 3769 2010-02-02  wdf $
 */
class Payment_Jump_Driver extends Payment_Driver {
    private $fileds = array (
    		'order_num' => '',
            'order_amount' => '',
            'flag' => '',
            'currency' => '',
            'billing_firstname' => '', 
            'billing_lastname' => '', 
            'billing_address' => '', 
            'billing_zip' => '', 
            'billing_city' => '', 
            'billing_state' => '', 
            'billing_country' => '', 
            'billing_phone' => '',
            'email' => '',
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
    }
    
	public function handle()
	{
	    //数据验证
        $validator = new Validation($this->fileds);
		$validator->pre_filter('trim');
		$validator->add_rules('submit_url', 'required');
		$validator->add_rules('order_num', 'required');
		$validator->add_rules('order_amount', 'required');
		$validator->add_rules('currency', 'required');
		$validator->add_rules('flag', 'required');
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
		$mac = md5($this->fileds['secure_code'].$this->fileds['order_num'].$this->fileds['order_amount'].$this->fileds['currency'].$this->fileds['site_id']);
	    //echo kohana::debug($this->fileds);
		echo  "<div style='display:hidden'>".
				"<form name='jump_form' action='".$this->fileds['submit_url']."' method='post'>".
				"<input name='order_num' type='hidden' value='".$this->fileds['order_num']."' />".
				"<input name='order_amount' type='hidden' value='".$this->fileds['order_amount']."' />".
				"<input name='flag' type='hidden' value='".$this->fileds['flag']."' />".
				"<input name='site_id' type='hidden' value='".$this->fileds['site_id']."' />".
				"<input name='currency' type='hidden' value='".$this->fileds['currency']."' />".
				"<input name='mac' type='hidden' value='".$mac."' />".

				"<input name='shipping_firstname' type='hidden' value='".$this->fileds['shipping_firstname']."' />".
				"<input name='shipping_lastname' type='hidden' value='".$this->fileds['shipping_lastname']."' />".
				"<input name='shipping_address' type='hidden' value='".$this->fileds['shipping_address']."' />".
				"<input name='shipping_zip' type='hidden' value='".$this->fileds['shipping_zip']."' />".
				"<input name='shipping_state' type='hidden' value='".$this->fileds['shipping_state']."' />".
				"<input name='shipping_city' type='hidden' value='".$this->fileds['shipping_city']."' />".
				"<input name='shipping_country' type='hidden' value='".$this->fileds['shipping_country']."' />".

				"<input name='billing_firstname' type='hidden' value='".$this->fileds['billing_firstname']."' />".
				"<input name='billing_lastname' type='hidden' value='".$this->fileds['billing_lastname']."' />".
				"<input name='billing_address' type='hidden' value='".$this->fileds['billing_address']."' />".
				"<input name='email' type='hidden' value='".$this->fileds['email']."' />".
				"<input name='billing_zip' type='hidden' value='".$this->fileds['billing_zip']."' />".
				"<input name='billing_state' type='hidden' value='".$this->fileds['billing_state']."' />".
				"<input name='billing_city' type='hidden' value='".$this->fileds['billing_city']."' />".
				"<input name='billing_country' type='hidden' value='".$this->fileds['billing_country']."' />".
				"<input name='billing_phone' type='hidden' value='".$this->fileds['billing_phone']."' />".
				"</form></div>".
				"<script language=javascript>".
				"onload=function (){".
				"	document.jump_form.submit();".
				"}".
				"</script>".
				"<div>It's turning to secure payment page...<br/>".
				"<a href='javascript:void(0);' onclick='document.jump_form.submit();return false;'>Click here if your browser does not automatically redirect you.</a>.".
				"</div>".
			"";
		exit;
	}
}