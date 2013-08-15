<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Paypal Payment Driver.
 *
 * $Id: Paypal.php 3769 2010-02-02  wdf $
 */
class Payment_Paypal_Driver extends Payment_Driver {
    private $fileds = array (
            'item_number' => '',
            'invoice' => '',
            'amount' => '',
            'currency_code' => '',
            'item_name' => '',
            'image_url' => '',
            'business' => '', //paypal帐号
            'cancel_return' => '',
            'return' => '',    //成功返回地址
            'notify_url' => '', //ipn地址
            'submit_url' => '', //提交地址
    		'bn_code' => '',//paypal活动码
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
		echo  "<div style='display:hidden'>".

			"<form name='paypal_jump_form' action='".$this->fileds['submit_url']."' method='post'>".

			"<input type='hidden' name='item_number' value='".$this->fileds['item_number']."'/>".
			"<input type='hidden' name='invoice' value='".$this->fileds['invoice']."'/>".
			"<input type='hidden' name='item_name' value='".$this->fileds['item_name']."'/>".
			"<input type='hidden' name='image_url' value='".$this->fileds['image_url']."'/>".
			"<input type='hidden' name='cmd' value='_xclick'/>".
			"<input type='hidden' name='business' value='".$this->fileds['business']."'/>".
			"<input type='hidden' name='cancel_return' value='".$this->fileds['cancel_return']."'/>".
			"<input type='hidden' name='return' value='".$this->fileds['return']."'/>".
			"<input type='hidden' name='notify_url' value='".$this->fileds['notify_url']."'/>".
			"<input type='hidden' name='amount' value='".$this->fileds['amount']."'/>".
			"<input type='hidden' name='currency_code' value='".$this->fileds['currency_code']."'/>".
			"<input type='hidden' name='bn' value='".$this->fileds['bn_code']."'/>".
			"</form></div>".

			"<script language=javascript>".
			"onload=function (){".
			"	document.paypal_jump_form.submit();".
			"}".
			"</script>".
			"<div>It's turning to secure payment page...<br/>".
			"<a href='javascript:void(0);' onclick='document.paypal_jump_form.submit();return false;'>Click here if your browser does not automatically redirect you.</a>.".
			"</div>".
		"";
		exit;
	}

} // End paymen Paypal Driver