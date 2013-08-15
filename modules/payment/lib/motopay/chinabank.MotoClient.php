<?php
/**
 * 网银在线 MOTO客户端接口
 * 版本: 2.5(php)
 * 开发者：ch.tommy@gmail.com
 */
require_once('nusoap.php');
require_once('chinabank.CreateXML.php');
require_once('chinabank.XmlParser.php');

class MOTOConstant {
    // 固定参数
    var $encoding   ="GBK";                        //编码格式
    var $soap_method= 'invoke02';                   //方法名称
    var $version    = '2.5(PHP)';                 //版本
    var $type       = 'md5';                      //加密类型
    // 可配参数
    
	
	var $soap_url   = 'http://motopaytest.chinabank.com.cn/webservice/motoPHP';// 服务路径
    var $merchantid = '1000';                  //商户号
    var $terminalid = '00000001';                   //终端号
	

    var $md5key     = 'test';                  //传输密钥
    
    /**
     * 以下是常量定义
     */
    // 错误代码
    var $MOTO_RET_SUCCESS                = "0";                        // 交易成功
    var $MOTO_RET_SUCCESS_ING            = "1";                        // 交易处理中
    var $MOTO_RET_FAILURE                = "E12041000";                // 交易失败
    var $MOTO_RET_ERROR                  = "E12041001";                // 内部错误
    var $MOTO_RET_NET                    = "E12041002";                // 通讯错误
    var $MOTO_RET_DB                     = "E12041003";                // 数据库错误
    var $MOTO_RET_AUTH                   = "E12041004";                // 安全验证失败
    var $MOTO_RET_OID                    = "E12041005";                // 交易号格式错误
    var $MOTO_RET_CARD                   = "E12041006";                // 信用卡格式错误
    var $MOTO_RET_CARDEXP                = "E12041007";                // 信用卡有效期格式错误
    var $MOTO_RET_AMOUNT                 = "E12041008";                // 交易金额格式错误
    var $MOTO_RET_NAME                   = "E12041009";                // 持卡人姓名格式错误
    var $MOTO_RET_IDCARD                 = "E12041010";                // 持卡人证件格式错误
    var $MOTO_RET_MOBILE                 = "E12041011";                // 持卡人电话格式错误
    var $MOTO_RET_NOTE                   = "E12041012";                // 备注格式错误
    var $MOTO_RET_MERCHANT               = "E12041013";                // 商户号错误
    var $MOTO_RET_TERMINAL               = "E12041014";                // 终端号错误
    var $MOTO_RET_MERCHANTEX             = "E12041015";                // 商户信息不完整
    var $MOTO_RET_PURVIEW_XF             = "E12041016";                // 终端没有消费权限
    var $MOTO_RET_PURVIEW_XFCX           = "E12041017";                // 终端没有撤销消费权限
    var $MOTO_RET_PURVIEW_SQ             = "E12041018";                // 终端没有预授权权限
    var $MOTO_RET_PURVIEW_SQCX           = "E12041019";                // 终端没有撤销预授权权限
    var $MOTO_RET_PURVIEW_QR             = "E12041020";                // 终端没有预授权确认权限
    var $MOTO_RET_PURVIEW_QRCX           = "E12041021";                // 终端没有撤销预授权确认权限
    var $MOTO_RET_PURVIEW_TK             = "E12041022";                // 终端没有退款权限
    var $MOTO_RET_SING_LIMIT             = "E12041023";                // 超出单笔交易限额
    var $MOTO_RET_MOTO_CARD              = "E12041024";                // MOTO不支持此信用卡
    var $MOTO_RET_MERCHANT_CARD          = "E12041025";                // 商户不支持此信用卡
    var $MOTO_RET_DAY_NUM                = "E12041026";                // 信用卡日交易次数限制
    var $MOTO_RET_DAY_DATE               = "E12041027";                // 信用卡日交易有效期错误次数限制
    var $MOTO_RET_DAY_AMOUNT             = "E12041028";                // 信用卡日交易金额上限错误次数限制
    var $MOTO_RET_DAY_LIMIT              = "E12041029";                // 超出日交易限额
    var $MOTO_RET_ORDERID                = "E12041030";                // 商户交易号重复
    var $MOTO_RET_OORDER                 = "E12041031";                // 原交易不存在
    var $MOTO_RET_OCARD                  = "E12041032";                // 信用卡原交易不符
    var $MOTO_RET_OAMOUNT                = "E12041033";                // 金额原交易不符
    var $MOTO_RET_OSTATE                 = "E12041034";                // 原交易不允许此操作
    var $MOTO_RET_OOORDER                = "E12041035";                // 原原交易不存在
    var $MOTO_RET_FLOAT                  = "E12041036";                // 超出预授权金额浮动范围
    var $MOTO_RET_REFUND                 = "E12041037";                // 退款金额不正确
    var $MOTO_RET_ORDER                  = "E12041038";                // 查询无此交易
    var $MOTO_RET_PURVIEW                = "E12041039";                // 无此交易查询权限
    var $MOTO_RET_XML                    = "E12041040";                // 消费成功 / 状态失败
    var $MOTO_RET_TERMINAL_TYPE          = "E12041041";                // 终端接入方式错误
    var $MOTO_RET_BANK                   = "E12041042";                // 此卡不允许做此交易
    var $MOTO_RET_MAIL                   = "E12041043";                // 持卡人邮件格式不正确
    var $MOTO_RET_VERSION                = "E12041044";                // 客户端版本号不正确
    var $MOTO_RET_CVV2                   = "E12041045";                // CVV2不允许为空 或者cvv2错
    var $MOTO_RET_WS_IP                  = "E12041046";                // IP校检失败  
    var $MOTO_RET_BLACKCARD              = "E12041047";                // 此卡为风险卡
    
    var $MOTO_CLIENT_AUTH                = "E12042004";                // php客户端安全验证错误
    
    
    // 关键字
    var $MOTO_KEY_ENCODE                 = "encode";                    // 密文
    var $MOTO_KEY_SIGN                   = "sign";                    // 签名
    var $MOTO_KEY_CLIENT                 = "client";                    // 客户端
    var $MOTO_KEY_TYPE                   = "type";                    // 交易类型
    var $MOTO_KEY_OID                    = "oid";                    // 交易号
    var $MOTO_KEY_OOID                   = "ooid";                    // 原交易号
    var $MOTO_KEY_CARD                   = "card";                    // 信用卡号
    var $MOTO_KEY_CARDEXP                = "cardexp";                // 信用卡有效期
    var $MOTO_KEY_AMOUNT                 = "amount";                    // 金额
    var $MOTO_KEY_EXTEND                 = "extend";                    // 附加参数
    var $MOTO_KEY_TIME                   = "time";                    // 远程时间
    var $MOTO_KEY_MERCHANT               = "merchant";                // 商户号
    var $MOTO_KEY_TERMINAL               = "terminal";                // 终端号
    var $MOTO_KEY_AUTHID                 = "authid";                    // 银行授权号
    var $MOTO_KEY_BANKNAME               = "bankname";                // 银行名称
    var $MOTO_KEY_CARDNAME               = "cardname";                // 信用卡名称
    var $MOTO_KEY_ERROR                  = "error";                    // 错误信息
    var $MOTO_KEY_ERROR_CN               = "_error";                    // 错误信息解释
    
    // 附加参数关键字
    var $MOTO_KEY_EXTEND_NAME            = "name";                    // 持卡人姓名
    var $MOTO_KEY_EXTEND_IDCARD          = "idcard";                    // 持卡人证件号
    var $MOTO_KEY_EXTEND_MOBILE          = "mobile";                    // 持卡人手机号
    var $MOTO_KEY_EXTEND_MAIL            = "mail";                    // 持卡人E-Mail
    var $MOTO_KEY_EXTEND_NOTE            = "note";                    // 备注
    var $MOTO_KEY_EXTEND_CVV2            = "cvv2";                    // cvv2
    
    // 查询结果
    var $MOTO_SEARCH_OID                 = "_oid";                    // 交易号
    var $MOTO_SEARCH_AID                 = "_aid";                    // 授权号
    var $MOTO_SEARCH_TYPE                = "_type";                    // 交易类型
	var $MOTO_SEARCH_TYPECODE            = "_typecode";                // 交易类型码
    var $MOTO_SEARCH_CARD                = "_card";                    // 信用卡号
    var $MOTO_SEARCH_AMOUNT              = "_amount";                // 交易金额
    var $MOTO_SEARCH_DATE                = "_date";                    // 交易日期
    var $MOTO_SEARCH_STATE               = "_state";                    // 交易状态
	var $MOTO_SEARCH_STATECODE           = "_statecode";                 // 交易状态码
    var $MOTO_SEARCH_CODE                = "_code";                    // 交易结果
    var $MOTO_SEARCH_BANKNAME            = "_bankname";                // 银行名称
    var $MOTO_SEARCH_CARDNAME            = "_cardname";                // 信用卡名称
	var $MOTO_SEARCH_SETTLE              = "_settle";                // 结算标识
    var $MOTO_SEARCH_RECEIVE             = "_receive";               // 是否受理卡
    var $MOTO_SEARCH_RECCARD             = "_reccard";               // 商户受理卡
    var $MOTO_SEARCH_RECCARDCB           = "_reccardcb";             // 网银受理卡
    
    // 远程调用服务名称
    var $MOTO_REMOTE_CONSUME             = "ConsumeService";            // 消费交易
    var $MOTO_REMOTE_CONSUME_REVOKE      = "ConsumeRevokeService";    // 撤销消费
    var $MOTO_REMOTE_AUTHORIZE           = "AuthorizeService";        // 预授权交易
    var $MOTO_REMOTE_AUTHORIZE_REVOKE    = "AuthorizeRevokeService";    // 撤销预授权交易
    var $MOTO_REMOTE_CONFIRM             = "ConfirmService";            // 确认交易
    var $MOTO_REMOTE_CONFIRM_REVOKE      = "ConfirmRevokeService";    // 撤销确认交易
    var $MOTO_REMOTE_REFUND              = "RefundService";            // 退款申请
    var $MOTO_REMOTE_SEARCH              = "SearchService";            // 交易查询
    var $MOTO_REMOTE_REVERSE             = "ReverseService";            // 冲正交易
    var $MOTO_REMOTE_SEARCHCARD          = "SearchCardService";        // 查询受理卡
}

class MotoClient extends MOTOConstant {
    function CBInterface($encoding = 'GBK') {
        $this->encoding=$encoding ;           //初始化编码格式
    }
    
    /**
     * @modify by flyC 2010-10-13
     * 函数功能：设置服务路径 商户号 终端号 和 传输密钥
     * 说    明：当商户使用多个MOTO商户号时可使用此函数设置每次交易的商户号和传输密钥以及服务路径
     */
    function setUrlMerchantTerminalAndKey($soap_url, $merchantid, $terminalid, $md5key) {
    	$this->soap_url = $soap_url;
        $this->merchantid = $merchantid;
        $this->terminalid = $terminalid;
        $this->md5key = $md5key;
    }
    
    /**
     * 函数功能：原创调用适配
     */
    function remoteInvoke($service, $parameter) {
        // 合成XML
        $xdoc = new CreateXML($this->encoding);
        $xdoc->createRoot($service, $this->version);
        foreach($parameter as $key => $val) {
            $xdoc->addNode($key, $val['type'], $val['value']);
        }
        // 加密 签名
        $xstr = $xdoc->getString();
        // echo "xstr: $xstr";
        $req_sign = strtoupper(md5($xstr.$this->md5key));
        $req_code = base64_encode ($xstr);
        // 远程调用
		//生成SOAP调用是的参数
		$param = array(
			'service'    => $service,            //服务名
			'merchant'   => $this->merchantid,   //服务名
			'encoding'   => $this->encoding,     //编码类型
			'type'       => $this->type,         //加密类型(采用什么方式进行加密的)
			'code'       => $req_code,           //经过BASE64编码的明文
			'sign'       => $req_sign            //加密后的数据
		);
		// print_r($param);
		//调用网银提供的soap方法
		$soap_client = new soapclientw($this->soap_url, true);
		$rs = $soap_client->call($this->soap_method, $param);
		//echo "-----------------------------<br/>\n";
		//print_a('rs', $rs);
		
        // 处理返回值
        $rtcode = $rs['string'][0];
        $rtmsg = base64_decode( $rs['string'][1] );
		
        //echo "code: $rtmsg<br\>\n";
        if ( strtoupper(md5($rtmsg.$this->md5key)) == $rs['string'][2] ) {
            echo "return sign OK! <br>\n";
            // 解码
			echo $rtmsg;
            return xml2array($rtmsg);
        } else {
            echo "Chinabank Soap SIGN ERR! <br>\n";
            return array(
                'sname'  => $service, 
                'result' => $this->MOTO_CLIENT_AUTH, 
                'error'  => $this->MOTO_CLIENT_AUTH, 
                '_error' => 'SING ERR');
        }
    }
    
	function consume2($orderid, $card, $cardexp, $amount, $extend) {
		$service = 'ConsumeService';
		$today = getdate();
		$parameter = array();
		$parameter['oid'] = array('type' => 'string', 'value' => ($orderid==null ? '' : $orderid) );
		$parameter['card'] = array('type' => 'string', 'value' => $card); 
		$parameter['cardexp'] = array('type' => 'string', 'value' => $cardexp); 
		$parameter['amount'] = array('type' => 'int', 'value' => $amount); 
		$parameter['merchant'] = array('type' => 'string', 'value' => $this->merchantid); 
		$parameter['terminal'] = array('type' => 'string', 'value' => $this->terminalid); 
		$parameter['time'] = array('type' => 'date', 'value' => date('Y-m-d H:i:s', $today[0]) );
		$parameter['extend'] = array('type' => 'map', 'value' => ($extend==null ? array() : $extend) ); 
		
		return $this->remoteInvoke($service, $parameter);
	}
	

	function consume($orderid, $card, $cardexp, $amount, $extend) {
		$service = $this->MOTO_REMOTE_CONSUME;
		$today = getdate();
		
		$parameter = array();
		$parameter[$this->MOTO_KEY_OID] = array('type' => 'string', 'value' => ($orderid == null ? '' : $orderid) );
		$parameter[$this->MOTO_KEY_CARD] = array('type' => 'string', 'value' => $card);
		$parameter[$this->MOTO_KEY_CARDEXP] = array('type' => 'string', 'value' => $cardexp);
		$parameter[$this->MOTO_KEY_AMOUNT] = array('type' => 'int', 'value' => $amount);
		$parameter[$this->MOTO_KEY_MERCHANT] = array('type' => 'string', 'value' => $this->merchantid);
		$parameter[$this->MOTO_KEY_TERMINAL] = array('type' => 'string', 'value' => $this->terminalid); 
		$parameter[$this->MOTO_KEY_TIME] = array('type' => 'date', 'value' => date('Y-m-d H:i:s', $today[0]) );
		$parameter[$this->MOTO_KEY_EXTEND] = array('type' => 'map', 'value' => ($extend==null ? array() : $extend) ); 
		
		return $this->remoteInvoke($service, $parameter);
	}
	
	function consumeRevoke($orderid, $oldid, $card, $cardexp, $amount, $extend) {
		$service = $this->MOTO_REMOTE_CONSUME_REVOKE;
		$today = getdate();
		
		$parameter = array();
		$parameter[$this->MOTO_KEY_OID] = array('type' => 'string', 'value' => ($orderid == null ? '' : $orderid) );
		$parameter[$this->MOTO_KEY_OOID] = array('type' => 'string', 'value' => ($oldid == null ? '' : $oldid) );
		$parameter[$this->MOTO_KEY_CARD] = array('type' => 'string', 'value' => $card);
		$parameter[$this->MOTO_KEY_CARDEXP] = array('type' => 'string', 'value' => $cardexp);
		$parameter[$this->MOTO_KEY_AMOUNT] = array('type' => 'int', 'value' => $amount);
		$parameter[$this->MOTO_KEY_MERCHANT] = array('type' => 'string', 'value' => $this->merchantid);
		$parameter[$this->MOTO_KEY_TERMINAL] = array('type' => 'string', 'value' => $this->terminalid); 
		$parameter[$this->MOTO_KEY_TIME] = array('type' => 'date', 'value' => date('Y-m-d H:i:s', $today[0]) );
		$parameter[$this->MOTO_KEY_EXTEND] = array('type' => 'map', 'value' => ($extend==null ? array() : $extend) ); 
		
		return $this->remoteInvoke($service, $parameter);
	}
	
	// 预授权
	function authorize($orderid, $card, $cardexp, $amount, $extend) {
		$service = $this->MOTO_REMOTE_AUTHORIZE;
		$today = getdate();
		
		$parameter = array();
		$parameter[$this->MOTO_KEY_OID] = array('type' => 'string', 'value' => ($orderid == null ? '' : $orderid) );
		$parameter[$this->MOTO_KEY_CARD] = array('type' => 'string', 'value' => $card);
		$parameter[$this->MOTO_KEY_CARDEXP] = array('type' => 'string', 'value' => $cardexp);
		$parameter[$this->MOTO_KEY_AMOUNT] = array('type' => 'int', 'value' => $amount);
		$parameter[$this->MOTO_KEY_MERCHANT] = array('type' => 'string', 'value' => $this->merchantid);
		$parameter[$this->MOTO_KEY_TERMINAL] = array('type' => 'string', 'value' => $this->terminalid); 
		$parameter[$this->MOTO_KEY_TIME] = array('type' => 'date', 'value' => date('Y-m-d H:i:s', $today[0]) );
		$parameter[$this->MOTO_KEY_EXTEND] = array('type' => 'map', 'value' => ($extend==null ? array() : $extend) ); 
		
		return $this->remoteInvoke($service, $parameter);
	}

	function authorizeRevoke($orderid, $oldid, $card, $cardexp, $amount, $extend) {
		$service = $this->MOTO_REMOTE_AUTHORIZE_REVOKE;
		$today = getdate();
		
		$parameter = array();
		$parameter[$this->MOTO_KEY_OID] = array('type' => 'string', 'value' => ($orderid == null ? '' : $orderid) );
		$parameter[$this->MOTO_KEY_OOID] = array('type' => 'string', 'value' => ($oldid == null ? '' : $oldid) );
		$parameter[$this->MOTO_KEY_CARD] = array('type' => 'string', 'value' => $card);
		$parameter[$this->MOTO_KEY_CARDEXP] = array('type' => 'string', 'value' => $cardexp);
		$parameter[$this->MOTO_KEY_AMOUNT] = array('type' => 'int', 'value' => $amount);
		$parameter[$this->MOTO_KEY_MERCHANT] = array('type' => 'string', 'value' => $this->merchantid);
		$parameter[$this->MOTO_KEY_TERMINAL] = array('type' => 'string', 'value' => $this->terminalid); 
		$parameter[$this->MOTO_KEY_TIME] = array('type' => 'date', 'value' => date('Y-m-d H:i:s', $today[0]) );
		$parameter[$this->MOTO_KEY_EXTEND] = array('type' => 'map', 'value' => ($extend==null ? array() : $extend) ); 
		
		return $this->remoteInvoke($service, $parameter);
	}

	function authorizeCfm($orderid, $oldid, $card, $cardexp, $amount, $extend) {
		$service = $this->MOTO_REMOTE_CONFIRM;
		$today = getdate();
		
		$parameter = array();
		$parameter[$this->MOTO_KEY_OID] = array('type' => 'string', 'value' => ($orderid == null ? '' : $orderid) );
		$parameter[$this->MOTO_KEY_OOID] = array('type' => 'string', 'value' => ($oldid == null ? '' : $oldid) );
		$parameter[$this->MOTO_KEY_CARD] = array('type' => 'string', 'value' => $card);
		$parameter[$this->MOTO_KEY_CARDEXP] = array('type' => 'string', 'value' => $cardexp);
		$parameter[$this->MOTO_KEY_AMOUNT] = array('type' => 'int', 'value' => $amount);
		$parameter[$this->MOTO_KEY_MERCHANT] = array('type' => 'string', 'value' => $this->merchantid);
		$parameter[$this->MOTO_KEY_TERMINAL] = array('type' => 'string', 'value' => $this->terminalid); 
		$parameter[$this->MOTO_KEY_TIME] = array('type' => 'date', 'value' => date('Y-m-d H:i:s', $today[0]) );
		$parameter[$this->MOTO_KEY_EXTEND] = array('type' => 'map', 'value' => ($extend==null ? array() : $extend) ); 
		
		return $this->remoteInvoke($service, $parameter);
	}

	function authorizeCfmRevoke($orderid, $oldid, $card, $cardexp, $amount, $extend) {
		$service = $this->MOTO_REMOTE_CONFIRM_REVOKE;
		$today = getdate();
		
		$parameter = array();
		$parameter[$this->MOTO_KEY_OID] = array('type' => 'string', 'value' => ($orderid == null ? '' : $orderid) );
		$parameter[$this->MOTO_KEY_OOID] = array('type' => 'string', 'value' => ($oldid == null ? '' : $oldid) );
		$parameter[$this->MOTO_KEY_CARD] = array('type' => 'string', 'value' => $card);
		$parameter[$this->MOTO_KEY_CARDEXP] = array('type' => 'string', 'value' => $cardexp);
		$parameter[$this->MOTO_KEY_AMOUNT] = array('type' => 'int', 'value' => $amount);
		$parameter[$this->MOTO_KEY_MERCHANT] = array('type' => 'string', 'value' => $this->merchantid);
		$parameter[$this->MOTO_KEY_TERMINAL] = array('type' => 'string', 'value' => $this->terminalid); 
		$parameter[$this->MOTO_KEY_TIME] = array('type' => 'date', 'value' => date('Y-m-d H:i:s', $today[0]) );
		$parameter[$this->MOTO_KEY_EXTEND] = array('type' => 'map', 'value' => ($extend==null ? array() : $extend) ); 
		
		return $this->remoteInvoke($service, $parameter);
	}

	function refundment($orderid, $oldid, $card, $cardexp, $amount, $extend) {
		$service = $this->MOTO_REMOTE_REFUND;
		$today = getdate();
		
		$parameter = array();
		$parameter[$this->MOTO_KEY_OID] = array('type' => 'string', 'value' => ($orderid == null ? '' : $orderid) );
		$parameter[$this->MOTO_KEY_OOID] = array('type' => 'string', 'value' => ($oldid == null ? '' : $oldid) );
		$parameter[$this->MOTO_KEY_CARD] = array('type' => 'string', 'value' => $card);
		$parameter[$this->MOTO_KEY_CARDEXP] = array('type' => 'string', 'value' => $cardexp);
		$parameter[$this->MOTO_KEY_AMOUNT] = array('type' => 'int', 'value' => $amount);
		$parameter[$this->MOTO_KEY_MERCHANT] = array('type' => 'string', 'value' => $this->merchantid);
		$parameter[$this->MOTO_KEY_TERMINAL] = array('type' => 'string', 'value' => $this->terminalid); 
		$parameter[$this->MOTO_KEY_TIME] = array('type' => 'date', 'value' => date('Y-m-d H:i:s', $today[0]) );
		$parameter[$this->MOTO_KEY_EXTEND] = array('type' => 'map', 'value' => ($extend==null ? array() : $extend) ); 
		
		return $this->remoteInvoke($service, $parameter);
	}

	function search($orderid) {
		$service = $this->MOTO_REMOTE_SEARCH;
		$today = getdate();
		
		$parameter = array();
		$parameter[$this->MOTO_KEY_OID] = array('type' => 'string', 'value' => $orderid);
		$parameter[$this->MOTO_KEY_MERCHANT] = array('type' => 'string', 'value' => $this->merchantid);
		$parameter[$this->MOTO_KEY_TERMINAL] = array('type' => 'string', 'value' => $this->terminalid);
		$parameter[$this->MOTO_KEY_EXTEND] = array('type' => 'map', 'value' => ($extend==null ? array() : $extend) ); 
		
		return $this->remoteInvoke($service, $parameter);
	}

	function reverse($orderid) {
		$service = $this->MOTO_REMOTE_REVERSE;
		
		$parameter = array();
		$parameter[$this->MOTO_KEY_OID] = array('type' => 'string', 'value' => $orderid);
		$parameter[$this->MOTO_KEY_MERCHANT] = array('type' => 'string', 'value' => $this->merchantid);
		$parameter[$this->MOTO_KEY_TERMINAL] = array('type' => 'string', 'value' => $this->terminalid); 
		$parameter[$this->MOTO_KEY_EXTEND] = array('type' => 'map', 'value' => ($extend==null ? array() : $extend) ); 
		
		return $this->remoteInvoke($service, $parameter);
	}
}

class CBResult {
    var $rtcode;
    var $map;
    function CBResult($rtcode) {
        $this->rtcode = $rtcode;
        $this->map = array();
    }
    function getRtcode() {
        return $this->rtcode;
    }
    function setRtcode($rtcode) {
        $this->rtcode = $rtcode;
    }
    function getValue($key) {
        return $this->map[$key];
    }
    function setValue($key, $value) {
        $this->map[$key] = $value;
    }
    function setMap($map) {
        $this->map = $map;
    }
    function getMap() {
        return $this->map;
    }
}
?>