<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Motopay Payment Driver.
 */
class Payment_Motopay_Driver extends Payment_Driver {
    private $fileds = array (
        'cardid' => '', 
        'year_month' => '', 
        'money' => '', 
        'name' => '',  // 持卡人姓名
        'idcard' => '',  // 持卡人证件号
        'cvv2' => '',  // cvv2
        'mobile' => '',  // 持卡人手机号
        'mail' => '',  // 持卡人E-Mail
        'note' => '',  // 备注
        'merchantid' => '', 
        'terminalid' => '', 
        'key' => '', 
        'submit_url' => '',
        'order_num' => '',
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
    );
    private $error = array (
        'E12041000' => 'Transaction Failure!', 
        'E12041001' => 'Internal Error!', 
        'E12041002' => 'Communication Error!', 
        'E12041003' => 'Database Error!', 
        'E12041004' => 'Security Access Failure!', 
        'E12041005' => 'Transaction Number Format Error!', 
        'E12041006' => 'Credit Card Format Error!', 
        'E12041007' => 'Credit Card Expiry Date Format Error!', 
        'E12041008' => 'Transaction Amt Format Error!', 
        'E12041009' => 'Cardholder Name Format Error!', 
        'E12041010' => 'Cardholder Credentials Format Error!', 
        'E12041011' => 'Cardholder Phone Number Format Error!', 
        'E12041012' => 'Remark Format Error!', 
        'E12041013' => 'Firm Name Error!', 
        'E12041014' => 'Terminal Number Error!', 
        'E12041015' => 'Incomplete Merchant Information!', 
        'E12041016' => 'Terminal has no rights of Consumption!', 
        'E12041017' => 'Terminal has no rights of Cusumption Cancellation!', 
        'E12041018' => 'Terminal has no rights of Pre-Authorization!', 
        'E12041019' => 'Terminal has no rights of Pre-Authorization Cancellation!', 
        'E12041020' => 'Terminal has no rights of Confirmation of Pre-Authorization!', 
        'E12041021' => 'Terminal has no rights of Confirmation of Pre-Authorization Cancellation!', 
        'E12041022' => 'Terminal has no rights of Refund!', 
        'E12041023' => 'Exceed Single Transaction Frequency Limit!', 
        'E12041024' => 'MOTO does not support this credit card!', 
        'E12041025' => 'Merchant does not support this credit card!', 
        'E12041026' => 'Credit card daily transaction times limit!', 
        'E12041027' => 'Credit card daily transaction validity error times limit!', 
        'E12041028' => 'Credit card daily transaction floor limit error times limit!', 
        'E12041029' => 'Exceed Daily Transaction Frequency Limit!', 
        'E12041030' => 'Merchant Transaction Number Repetition!', 
        'E12041031' => 'Original Transaction does not exsit!', 
        'E12041032' => 'Credit card is inconsistent with original transaction!', 
        'E12041033' => 'Transaction Amount is inconsistent with original transaction!', 
        'E12041034' => 'Original Transaction does not allow this operation!', 
        'E12041035' => 'Original Transaction does not exsit!', 
        'E12041036' => 'Exceed Pre-Authorization Transaction Amount Float Range!', 
        'E12041037' => 'Incorrect Refund Amount!', 
        'E12041038' => 'Query: Transaction not found!', 
        'E12041039' => 'No rights of transaction query!', 
        'E12041040' => 'Transaction Succeeds. Update Failure. Transaction Status is updating.', 
        'E12041041' => 'Terminal Access Style Error!', 
        'E12041042' => 'The credit card does not permit this transaction!', 
        'E12042000' => 'Bank does not permit this transaction!', 
        'E12042010' => 'Invalid Transaction!', 
        'E12041043' => 'Incorrect Cardholder Mail Format!', 
        'E12041044' => 'Incorrect Client Version Number!', 
        'E12041045' => 'CVV2 cannot allow null values!'
    );
    
    public function set($fileds)
    {
        foreach ($fileds as $key => $filed)
        {
            if (isset($this->fileds[$key]))
            {
                $this->fileds[$key] = $filed;
            }
        }
    }
    
    /*
     * 预授权交易处理
     */
    public function handle()
    {
        //echo kohana::debug($this->fileds);
        //数据验证
        $validator = new Validation($this->fileds);
        $validator->pre_filter('trim');
        //$validator->add_rules('cardid', 'required');
        //$validator->add_rules('year_month', 'required');
        $validator->add_rules('money', 'required');
        //$validator->add_rules('name', 'required');
        //$validator->add_rules('idcard', 'required');
        //$validator->add_rules('cvv2', 'required');
        $validator->add_rules('submit_url', 'required');
        if (!($validator->validate()))
        {
            //错误输出,js层已经过滤，一般不会输出
            $validate_errors = $validator->errors();
            $errors = '';
            foreach ($validate_errors as $key => $val)
            {
                $errors .= $key . ' failed rule ' . $val . '<br>';
            }
            throw new PaymentException($errors, 400);
        }
        $motoClient = new MotoClient();
        $motoClient->setUrlMerchantTerminalAndKey($this->fileds['submit_url'], $this->fileds['merchantid'], $this->fileds['terminalid'], $this->fileds['key']);

        /*
        $red_ext = 'CUST_EMAIL='.$this->fileds['billing_email'].'~'.
                   'CUST_FNAME='.$this->fileds['billing_firstname'].'~'.
                   'CUST_LNAME='.$this->fileds['billing_lastname'].'~'.
                   'CUST_ADDR1='.$this->fileds['billing_address'].'~'.
                   'CUST_CITY='.$this->fileds['billing_city'].'~'.
                   'CUST_CNTRY_CD='.$this->fileds['billing_country'].'~'.
                   'EBT_USER_DATA18='.$this->fileds['billing_telephone'].'~'.
                   'SHIP_FNAME='.$this->fileds['shipping_firstname'].'~'.
                   'SHIP_LNAME='.$this->fileds['shipping_lastname'].'~'.
                   'SHIP_ADDR1='.$this->fileds['shipping_address'].'~'.
                   'SHIP_CITY='.$this->fileds['shipping_city'].'~'.
                   'SHIP_CNTRY_CD='.$this->fileds['shipping_country'].'~'.
                   'EBT_USER_DATA19=840~'.
                   'EBT_USER_DATA13=web~'.
                   'CUST_IP_ADDR='.$this->fileds['billing_ip_address'].'~'.
                   'ITEM_SEQ[1]=1~'.
                   'ITEM_DESC[1]='.$this->fileds['order_num'].'~'.
                   'ITEM_CST_AMT[1]='.$this->fileds['money'].'~'.
                   'ITEM_QTY[1]=1000';
        */
        //$result = $motoClient->authorize("", $this->fileds['cardid'], $this->fileds['year_month'], $this->fileds['money'], array ('name' => $this->fileds['name'], 'idcard' => $this->fileds['idcard'], 'cvv2' => $this->fileds['cvv2'], 'red_ext'=>$red_ext));
        $result = $motoClient->authorize("", $this->fileds['cardid'], $this->fileds['year_month'], $this->fileds['money'], array ('name' => $this->fileds['name'], 'idcard' => $this->fileds['idcard'], 'cvv2' => $this->fileds['cvv2']));
        if(!empty($result['error']) && isset($this->error[$result['error']])){
            $result['_error_en'] = $this->error[$result['error']];
        }else{
            $result['_error_en'] = 'unkown error';
        }
        return $result;
    }
}
