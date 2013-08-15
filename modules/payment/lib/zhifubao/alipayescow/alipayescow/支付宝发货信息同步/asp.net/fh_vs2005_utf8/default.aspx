<%@ Page Language="C#" AutoEventWireup="true" CodeFile="default.aspx.cs" Inherits="_Default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>支付宝发货模板页</title>
    <style type="text/css">
	.form-left{
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
</style>

    <script language="JavaScript">
function CheckForm()
{
	if (document.alipayment.trade_no.value.length == 0) {
		alert("请输入支付宝交易号.");
		document.alipayment.trade_no.focus();
		return false;
	}
	if (document.alipayment.logistics_name.value.length == 0) {
		alert("请输入物流公司名称.");
		document.alipayment.logistics_name.focus();
		return false;
	}
	if (document.alipayment.invoice_no.value.length == 0) {
		alert("请输入物流发货单号.");
		document.alipayment.invoice_no.focus();
		return false;
	}
	if (document.alipayment.transport_type.value.length == 0) {
		alert("请输入物流发货时的运输类型.");
		document.alipayment.transport_type.focus();
		return false;
	}
}  
    </script>

</head>
<body>
    <center>
        <form id="alipayment" name="alipayment" action="sendgoods.aspx" method="post" onsubmit="return CheckForm();">
            <table cellspacing="0" cellpadding="0" width="450" border="0">
                <tr>
                    <td class="font_title" valign="middle">
                        支付宝发货通道</td>
                </tr>
                <tr>
                    <td align="center">
                        <hr width="450" size="2" color="#999999">
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <table cellspacing="0" cellpadding="0" width="350" border="0">
                            <tr>
                                <td class="form-left">
                                    支付宝交易号：</td>
                                <td class="form-right">
                                    <input size="30" name="trade_no" maxlength="20"></td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                    发货类型：</td>
                                <td class="form-right">
                                    <select name="transport_type">
                                        <option value="EMS">EMS</option>
                                        <option value="POST">平邮</option>
                                        <option value="EXPRESS" selected="selected">快递</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                    物流公司名称：</td>
                                <td class="form-right">
                                    <input size="30" name="logistics_name" maxlength="30"></td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                    物流发货单号：</td>
                                <td class="form-right">
                                    <input size="30" name="invoice_no" maxlength="50"></td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                </td>
                                <td class="form-right">
                                    <input name="alipaysendgoods" id="alipaysendgoods" value="发 货" type="submit"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </center>
</body>
</html>
