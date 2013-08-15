<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Googlecheckout Payment Driver.
 *
 * $Id: Googlecheckout.php 3769 2010-02-02  wdf $
 */
class Payment_Googlecheckout_Driver extends Payment_Driver {
    private $fileds = array (
            'item_name_1' => '',
            'item_description_1' => '',
            'item_quantity_1' => '',
            'item_price_1' => '',
            'item_currency_1' => '',
            'account' => '', //google帐号
            'submit_url' => '', //google提交地址
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
	    /*数据验证*/
        $validator = new Validation($this->fileds);
		$validator->pre_filter('trim');
		$validator->add_rules('account', 'required');
		$validator->add_rules('submit_url', 'required');
	    if (!($validator->validate()))
		{
			$validate_errors = $validator->errors();
			$errors = '';
			foreach ($validate_errors as $key => $val) {
				$errors.= $key.' failed rule '.$val.'<br>';
			}
			throw new PaymentException($errors, 400);
		}
		
		$this->fileds['submit_url'] = str_replace("{acount}",$validator->account,$validator->submit_url);
		$protocol = site::protocol();
		echo  "<div style='display:hidden'>".
			"<form name='jump_form' action='".$this->fileds['submit_url']."' method='post'>".
			"<input type='hidden' name='item_name_1' value='".$this->fileds['item_name_1']."'/>".
			"<input type='hidden' name='item_description_1' value='".$this->fileds['item_description_1']."'/>".
			"<input type='hidden' name='item_quantity_1' value='".$this->fileds['item_quantity_1']."'  />".
			"<input type='hidden' name='item_price_1' value='".$this->fileds['item_price_1']."'/>".
			"<input type='hidden' name='item_currency_1' value='".$this->fileds['item_currency_1']."'/>".
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