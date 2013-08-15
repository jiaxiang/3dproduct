
<%
	/*
	功能：支付宝发货接口的入口页面，生成请求URL
	 *版本：3.1
	 *日期：2010-12-17
	 *说明：
	 *以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
	 *该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
	
	 *************************注意*****************
	如果您在接口集成过程中遇到问题，
	您可以到商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决，
	您也可以到支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）寻找相关解决方案
	要传递的参数要么不允许为空，要么就不要出现在数组与隐藏控件或URL链接里。
	
	确认发货没有服务器异步通知页面（notify_url）与页面跳转同步通知页面（return_url），
	发货操作后，该笔交易的状态发生了变更，支付宝会主动发送通知给商户网站，而商户网站在担保交易或双功能的接口中的服务器异步通知页面（notify_url）
	该发货接口仅针对担保交易接口、双功能接口中的担保交易支付里涉及到需要卖家做发货的操作

	各家快递公司都属于EXPRESS（快递）的范畴
	 **********************************************
	 */
%>
<%@ page language="java" contentType="text/html; charset=utf-8"
	pageEncoding="utf-8"%>
<%@ page import="com.alipay.config.*"%>
<%@ page import="com.alipay.util.*"%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>支付宝发货接口</title>
	</head>
	<body>
	<%
		//request.setCharacterEncoding("UTF-8");
		//AlipyConfig.java中配置信息（不可以修改）
		String input_charset = AlipayConfig.input_charset;
		String sign_type = AlipayConfig.sign_type;
		String partner = AlipayConfig.partner;
		String key = AlipayConfig.key;
		/////////////////////////////////////////请求参数////////////////////////////////////////////////////
		//-----------必填参数-----------
		//支付宝交易号。它是登陆支付宝网站在交易管理中查询得到，一般以8位日期开头的纯数字（如：20100419XXXXXXXXXX） 
		String trade_no = request.getParameter("trade_no");
		
		//物流公司名称
		String logistics_name = new String(request.getParameter("logistics_name").getBytes("ISO-8859-1"),"utf-8");
		
		//物流发货单号
		String invoice_no = new String(request.getParameter("invoice_no").getBytes("ISO-8859-1"),"utf-8");
		
		//物流发货时的运输类型，三个值可选：POST（平邮）、EXPRESS（快递）、EMS（EMS）
		String transport_type = request.getParameter("transport_type");

		//-----------选填参数-----------
		//卖家本地电脑IP地址
		String seller_ip = "";

		/////////////////////////////////////////////////////////////////////////////////////////////////////
			
		//构造函数——无XML远程解析
        //String sHtmlText = AlipayService.BuildForm(partner,trade_no,logistics_name,invoice_no,transport_type,
        //seller_ip,input_charset,key,sign_type);
        //out.println(sHtmlText);

		//构造函数——含XML远程解析
		String xmlResult = AlipayService.PostXml(partner,trade_no,logistics_name,invoice_no,transport_type,
        seller_ip,input_charset,key,sign_type);
        out.println(xmlResult);
		
		////////////////////////////////////////////////////////////////////////////////////////////////////
		//请在此处编写商户发货成功后的业务逻辑程序代码，以便把商户网站里的该笔订单与支付宝的订单信息同步。

		///////////////////////////////////////////////////////////////////////////////////////////////////
	%>
	</body>
</html>
