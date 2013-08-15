<html>
<head>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
</head>
<body></body>
</html>
<?php
/*
 *功能：支付宝发货接口的入口页面，生成请求URL
 *版本：3.1
 *修改日期：2010-12-15
 '说明：
 '以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 '该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

*/

////////////////////注意/////////////////////////
//如果您在接口集成过程中遇到问题，
//您可以到商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决，
//您也可以到支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）寻找相关解决方案

//确认发货没有服务器异步通知页面（notify_url）与页面跳转同步通知页面（return_url），
//发货操作后，该笔交易的状态发生了变更，支付宝会主动发送通知给商户网站，而商户网站在担保交易或双功能的接口中的服务器异步通知页面（notify_url）
//该发货接口仅针对担保交易接口、双功能接口中的担保交易支付里涉及到需要卖家做发货的操作

//各家快递公司都属于EXPRESS（快递）的范畴
/////////////////////////////////////////////////

require_once("alipay_config.php");
require_once("class/alipay_service.php");

///////////////////////请求参数///////////////////
//------------必填参数------------
//支付宝交易号。它是登陆支付宝网站在交易管理中查询得到，一般以8位日期开头的纯数字（如：20100419XXXXXXXXXX） 
$trade_no		= $_POST['trade_no'];

//物流公司名称
$logistics_name	= $_POST['logistics_name'];

//物流发货单号
$invoice_no		= $_POST['invoice_no'];

//物流发货时的运输类型，三个值可选：POST（平邮）、EXPRESS（快递）、EMS（EMS）
$transport_type	= $_POST['transport_type'];

//------------选填参数------------
//卖家本地电脑IP地址
$seller_ip		= "";

/////////////////////////////////////////////////

//构造要请求的参数数组
$parameter = array(
        "service"			=> "send_goods_confirm_by_platform",	//接口名称，不需要修改

        //获取配置文件(alipay_config.php)中的值
        "partner"			=> $partner,
        "_input_charset"	=> $_input_charset,
		
		//必填参数
		"trade_no"			=> $trade_no,
		"logistics_name"	=> $logistics_name,
		"invoice_no"		=> $invoice_no,
		"transport_type"	=> $transport_type,
		
		//选填参数
		"seller_ip"			=> $seller_ip
);

//构造请求函数
$alipay = new alipay_service($parameter,$key,$sign_type);

//无XML远程解析
//$sHtmlText = $alipay->build_form();
//echo $sHtmlText;

//含XML远程解析
//注意：
//1、配置环境须支持DOMDocument，一般PHP5的配置环境支持
//2、配置环境须支持须支持SSL
$url = $alipay->create_url();

$doc = new DOMDocument();
$doc->load($url);
//获取成功标识is_success
$itemIs_success= $doc->getElementsByTagName( "is_success" );
$nodeIs_success = $itemIs_success->item(0)->nodeValue;

//获取错误代码 error
$itemError_code= $doc->getElementsByTagName( "error" );
$nodeError_code = $itemError_code->item(0)->nodeValue;

//获取request节点下面的子节点信息：支付宝交易号、交易状态、操作时间等
$itemTrade_no= $doc->getElementsByTagName( "trade_no" );
$nodeTrade_no = $itemTrade_no->item(0)->nodeValue;

$itemTrade_status = $doc->getElementsByTagName( "trade_status" );
$nodeTrade_status = $itemTrade_status->item(0)->nodeValue;

$itemSend_time = $doc->getElementsByTagName( "last_modified_time" );
$nodeSend_time = $itemSend_time->item(0)->nodeValue;

//获取tradeBase节点下面的子节点信息：商家网站唯一订单号
$itemOut_trade_no= $doc->getElementsByTagName( "out_trade_no" );
$nodeOut_trade_no = $itemOut_trade_no->item(0)->nodeValue;

/********************************************商户业务逻辑处理程序代码*************************************/
//请在此处编写商户发货成功后的业务逻辑程序代码，以便把商户网站里的该笔订单与支付宝的订单信息同步。


/*********************************************************************************************************/

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
    <td class="font_content" align="left"><?php echo $nodeIs_success ?></td>
  </tr>
  <?php
  if($nodeIs_success == "T"){
      echo '<tr>
    <td class="font_content" align="right">订单号：</td>
    <td class="font_content" align="left">'.$nodeOut_trade_no.'</td>
  </tr>
  <tr>
    <td class="font_content" align="right">支付宝交易号：</td>
    <td class="font_content" align="left">'.$nodeTrade_no.'</td>
  </tr>
  <tr>
    <td class="font_content" align="right">交易状态：</td>
    <td class="font_content" align="left">'.$nodeTrade_status.'</td>
  </tr>
  <tr>
    <td class="font_content" align="right">发货时间：</td>
    <td class="font_content" align="left">'.$nodeSend_time.'</td>
  </tr>';
  }elseif($nodeIs_success == "F"){
      echo '<tr>
    <td class="font_content" align="right">错误代码：</td>
    <td class="font_content" align="left">'.$nodeError_code.'</td>
  </tr>';
  }
  ?>
  
</table>
</body>
</html>