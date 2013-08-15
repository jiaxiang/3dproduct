<%
	'功能：支付宝发货接口的入口页面，生成请求URL
	'版本：3.1
	'日期：2010-12-02
	'说明：
	'以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
	'该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
	
'''''''''''''''''注意'''''''''''''''''''''''''
'如果您在接口集成过程中遇到问题，
'您可以到商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决，
'您也可以到支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）寻找相关解决方案

'确认发货没有服务器异步通知页面（notify_url）与页面跳转同步通知页面（return_url），
'发货操作后，该笔交易的状态发生了变更，支付宝会主动发送通知给商户网站，而商户网站在担保交易或双功能的接口中的服务器异步通知页面（notify_url）
'该发货接口仅针对担保交易接口、双功能接口中的担保交易支付里涉及到需要卖家做发货的操作

'各家快递公司都属于EXPRESS（快递）的范畴
''''''''''''''''''''''''''''''''''''''''''''''
%>
<!--#include file="alipay_config.asp"-->
<!--#include file="class/alipay_service.asp"-->
<%
'*********************************************请求参数*********************************************

'------------必填参数------------
'支付宝交易号。它是登陆支付宝网站在交易管理中查询得到，一般以8位日期开头的纯数字（如：20100419XXXXXXXXXX） 
trade_no		= request.Form("trade_no")

'物流公司名称
logistics_name	= request.Form("logistics_name")

'物流发货单号
invoice_no		= request.Form("invoice_no")

'物流发货时的运输类型，三个值可选：POST（平邮）、EXPRESS（快递）、EMS（EMS）
transport_type	= request.Form("transport_type")

'------------选填参数------------
'卖家本地电脑IP地址
seller_ip		= ""

''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''

'构造要请求的参数数组，无需改动
para = Array("service=send_goods_confirm_by_platform","partner="&partner,"trade_no="&trade_no,"logistics_name="&logistics_name,"invoice_no="&invoice_no,"transport_type="&transport_type,"seller_ip="&seller_ip,"_input_charset="&input_charset)

'构造请求函数
alipay_service(para)

'无XML远程解析
'sHtmlText = build_form()
'response.Write sHtmlText

'含XML远程解析
'远程XML解析是支付宝立刻反馈回来的XML信息做解析
'注意：远程解析XML出错，与IIS服务器配置有关
url = create_url()

dim is_success,nodeTrade_status,nodeSend_time,nodeOut_trade_no,nodeError

Dim http,xml
Set http=Server.CreateObject("Microsoft.XMLHTTP")
http.Open "GET",url,False
http.send
Set xml=Server.CreateObject("Microsoft.XMLDOM")
xml.Async=true
xml.ValidateOnParse=False
xml.Load(http.ResponseXML)

set DataIs_success=xml.getElementsByTagName("is_success")  '获取成功标识is_success
is_success = DataIs_success.item(0).childnodes(0).text
if is_success = "T" then	
	set DataTradeBase=xml.getElementsByTagName("tradeBase")  '获取tradeBase节点下面的子节点信息：商家网站唯一订单号
	nodeSend_time = DataTradeBase.item(0).childnodes(9).text
	nodeOut_trade_no = DataTradeBase.item(0).childnodes(11).text
	nodeTrade_status = DataTradeBase.item(0).childnodes(24).text
else
	set DataError=xml.getElementsByTagName("error")  '获取错误代码error
	nodeError = DataError.item(0).childnodes(0).text
end if

'*************************************************************************************************
'请在此处编写商户发货成功后的业务逻辑程序代码，以便把商户网站里的该笔订单与支付宝的订单信息同步。

'*************************************************************************************************

%>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>支付宝发货</title>
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
    <td align="center" class="font_title" colspan="2">XML返回</td>
  </tr>
  <tr>
    <td class="font_content" align="right">是否发货成功：</td>
    <td class="font_content" align="left"><%=is_success%></td>
  </tr>
 <%
 if is_success = "T" then
 %>
  <tr>
    <td class="font_content" align="right">订单号：</td>
    <td class="font_content" align="left"><%=nodeOut_trade_no%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">支付宝交易号：</td>
    <td class="font_content" align="left"><%=trade_no%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">交易状态：</td>
    <td class="font_content" align="left"><%=nodeTrade_status%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">发货时间：</td>
    <td class="font_content" align="left"><%=nodeSend_time%></td>
  </tr>
  <%
  else
  %>
  <tr>
    <td class="font_content" align="right">错误代码：</td>
    <td class="font_content" align="left"><%=nodeError%></td>
  </tr>
  <%
  end if
  %>
</table>
</body>
</html>
