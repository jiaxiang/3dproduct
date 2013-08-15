<%@LANGUAGE="VBSCRIPT" CODEPAGE="65001"%>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<%
	'功能：付完款后跳转的页面（页面跳转同步通知页面）
	'版本：3.1
	'日期：2010-11-23
	'说明：
	'以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
	'该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
	
''''''''页面功能说明''''''''''''''''
'该页面可在本机电脑测试
'该页面称作“页面跳转同步通知页面”，是由支付宝服务器同步调用，可当作是支付完成后的提示信息页，如“您的某某某订单，多少金额已支付成功”。
'可放入HTML等美化页面的代码和订单交易完成后的数据库更新程序代码
'该页面可以使用ASP开发工具调试，也可以使用写文本函数log_result进行调试，该函数已被默认关闭，见alipay_notify.asp中的函数return_verify
'WAIT_SELLER_SEND_GOODS(表示买家已在支付宝交易管理中产生了交易记录且付款成功，但卖家没有发货);
''''''''''''''''''''''''''''''''''''
%>

<!--#include file="alipay_config.asp"-->
<!--#include file="class/alipay_notify.asp"-->

<%
'计算得出通知验证结果
verify_result = return_verify()

dim isResult

if verify_result then	'验证成功
	'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
	'请在这里加上商户的业务逻辑程序代码
	
	'——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    '获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
    order_no		= request.QueryString("out_trade_no")	'获取订单号
    total_fee		= request.QueryString("price")			'获取总金额
	
	if request.QueryString("trade_status") = "WAIT_SELLER_SEND_GOODS" then
		'判断该笔订单是否在商户网站中已经做过处理（可参考“集成教程”中“3.4返回数据处理”）
			'如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			'如果有做过处理，不执行商户的业务程序
	else
		response.Write "trade_status="&request.QueryString("trade_status")
	end if
	
	isResult = "验证成功"
	
	'——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	'''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''
else '验证失败
    '如要调试，请看alipay_notify.asp页面的return_verify函数，比对sign和mysign的值是否相等，或者检查responseTxt有没有返回true
    isResult = "验证失败"
end if
%>
<title>支付宝担保交易付款</title>
<style type="text/css">
            .font_content{
                font-family:"宋体";
                font-size:14px;
                color:#FF6600;
            }
            .font_title{
                font-family:"宋体";
                font-size:16px;
                color:#FF0000;
                font-weight:bold;
            }
            table{
                border: 1px solid #CCCCCC;
            }
        </style>
</head>
<body>
<table align="center" width="350" cellpadding="5" cellspacing="0">
  <tr>
    <td align="center" class="font_title" colspan="2">通知返回</td>
  </tr>
  <tr>
    <td class="font_content" align="right">验证状态：</td>
    <td class="font_content" align="left"><%=isResult%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">支付宝交易号：</td>
    <td class="font_content" align="left"><%=request.QueryString("trade_no")%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">订单号：</td>
    <td class="font_content" align="left"><%=request.QueryString("out_trade_no")%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">付款总金额：</td>
    <td class="font_content" align="left"><%=request.QueryString("price")%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">订单标题：</td>
    <td class="font_content" align="left"><%=request.QueryString("subject")%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">订单描述：</td>
    <td class="font_content" align="left"><%=request.QueryString("body")%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">买家账号：</td>
    <td class="font_content" align="left"><%=request.QueryString("buyer_email")%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">收货人姓名：</td>
    <td class="font_content" align="left"><%=request.QueryString("receive_name")%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">收货人地址：</td>
    <td class="font_content" align="left"><%=request.QueryString("receive_address")%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">收货人邮编：</td>
    <td class="font_content" align="left"><%=request.QueryString("receive_zip")%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">收货人电话：</td>
    <td class="font_content" align="left"><%=request.QueryString("receive_phone")%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">收货人手机：</td>
    <td class="font_content" align="left"><%=request.QueryString("receive_mobile")%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">交易状态：</td>
    <td class="font_content" align="left"><%=request.QueryString("trade_status")%></td>
  </tr>
</table>
</body>
</html>
