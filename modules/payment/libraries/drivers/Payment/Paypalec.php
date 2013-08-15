<?php
defined('SYSPATH') or die('No direct access allowed.');
/**
 * Paypal Payment Driver.
 *
 * $Id: Paypal.php 3769 2010-02-02  wdf $
 */
class Payment_Paypalec_Driver extends Payment_Driver {
    private $fileds = array (
        'RETURNURL' => '', 
        'CANCELURL' => '', 
        'NOTIFYURL' => '', 
        'HDRIMG' => '', 
        'CURRENCYCODE' => '', 
        'AMT' => '', 
        'INVNUM' => '',
        'JUMPURL' => '',
        'SUCCESSURL' => '',
        'SUBMITURL' => '',  
        'VERSION' => '', 
        'PWD' => '',
        'USER' => '',
        'SIGNATURE' => '',
    );

    public function set($fileds)
    {
        foreach ($fileds as $key => $filed) {
            if (isset($this->fileds[$key])) {
                $this->fileds[$key] = $filed;
            }
        }
    }

    public function handle()
    {
        //ec session是否存在
        $session = Session::instance();
        $paypal_ec_token = $session->get('paypal_ec_token');
        $paypal_ec_payerid = $session->get('paypal_ec_payerid');
        if (!$paypal_ec_token || !$paypal_ec_payerid) {
            return false;
        } else {
            $paypal_ec_return = $this->go_ec($paypal_ec_token, $paypal_ec_payerid);
            return $paypal_ec_return;
        }
    }
    
    /**
     * paypal ec 第一步
     */
    public function do_ec()
    {   
        //ec支付
        $nvpstr = "&METHOD=SetExpressCheckout" . 
        		  "&RETURNURL=" . $this->fileds['RETURNURL'] . 
        		  "&CANCELURL=" . $this->fileds['CANCELURL'] . 
        		  "&NOTIFYURL=" . $this->fileds['NOTIFYURL'] . 
        		  "&HDRIMG=" . $this->fileds['HDRIMG'] . 
        		  "&CURRENCYCODE=" . $this->fileds['CURRENCYCODE'] . 
        		  "&AMT=" . $this->fileds['AMT'];
        $res_array = $this->do_curl_pp($nvpstr);
        if (strtoupper($res_array['ACK']) == 'SUCCESS'){
            $paypal_ec_token = $res_array['TOKEN'];
		    $session = Session::instance();
		    $session->set('paypal_ec_token',$paypal_ec_token);
		    $order_review = true;
		    $user_action_key = "&useraction=" . ((int)$order_review == false ? 'commit' : 'continue');
		    url::redirect($this->fileds['JUMPURL']."?cmd=_express-checkout&token=".$paypal_ec_token.$user_action_key);
		} else {
		    throw new PaymentException($res_array['L_LONGMESSAGE0'],400);
		}
    }
 
    /**
     * paypal ec 第二步
     */
    public function get_ec($token)
    {   //ec支付
        $nvpstr = "&METHOD=GetExpressCheckoutDetails" . 
        		  "&TOKEN=" . $token;
        $res_array = $this->do_curl_pp($nvpstr);
        $session = Session::instance();
		$session->set('paypal_ec_payerid',$res_array['PAYERID']);
        return $res_array;
    }

    /**
     * paypal ec 第三步
     */
    public function go_ec($token, $payerid)
    { 
        //ec支付
        $nvpstr = "&METHOD=DoExpressCheckoutPayment" . 
        		  "&PAYMENTACTION=sale" . 
        		  "&PAYERID=" . $payerid . 
        		  "&AMT=" . $this->fileds['AMT'] . 
        		  "&CURRENCYCODE=" . $this->fileds['CURRENCYCODE'] . 
        		  "&INVNUM=" . $this->fileds['INVNUM'] . 
        		  "&NOTIFYURL=" . $this->fileds['NOTIFYURL'] . 
        		  "&TOKEN=" . $token;
        $res_array = $this->do_curl_pp($nvpstr);
        return $res_array;
    }
    /*
    public static function do_construction_str($nvpstr)
    {
        $api = $this->api;
        $post_to_str = "payment=" . urlencode($nvpstr) . "
        				&VERSION=" . $api['api_version'] . "
        				&PWD=" . $api['api_pass'] . "
        				&SIGNATURE=" . $api['api_signature'] . "
        				&USER=" . $api['api_name'] . "
        				&ENDPOINT=" . $api['api_end_point'];
        return $post_to_str;
    }
    */
    public function do_curl_pp($nvpstr)
    {    
        //提交到PP返回数组
        $user = $this->fileds['USER'];
        $password = $this->fileds['PWD'];
        $signature = $this->fileds['SIGNATURE'];
        $endpoint = $this->fileds['SUBMITURL'];
        $version = $this->fileds['VERSION'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $nvpreq = "VERSION=" . urlencode($version) . 
        		  "&PWD=" . urlencode($password) . 
        		  "&USER=" . urlencode($user) . 
        		  "&SIGNATURE=" . urlencode($signature) . $nvpstr;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        $response = curl_exec($ch);
        $nvpResArray = array ();
        parse_str($response,$nvpResArray);
        return $nvpResArray;
    }
} // End paymen Paypal Driver