<%@ Page Language="C#" AutoEventWireup="true" CodeFile="default.aspx.cs" Inherits="_default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>支付宝 - 网上支付 安全快速！</title>
    <link href="images/layout.css" type="text/css" rel="stylesheet">

    <script language="JavaScript">
<!-- 
  //校验输入框  -->
function CheckForm()
{
	if (document.alipayment.aliorder.value.length == 0) {
		alert("请输入商品名称.");
		document.alipayment.aliorder.focus();
		return false;
	}
	if (document.alipayment.alimoney.value.length == 0) {
		alert("请输入付款金额.");
		document.alipayment.alimoney.focus();
		return false;
	}
	var reg	= new RegExp(/^\d*\.?\d{0,2}$/);
	if (! reg.test(document.alipayment.alimoney.value))
	{
        alert("请正确输入付款金额");
		document.alipayment.alimoney.focus();
		return false;
	}
	if (Number(document.alipayment.alimoney.value) < 0.01) {
		alert("付款金额金额最小是0.01.");
		document.alipayment.alimoney.focus();
		return false;
	}
}  
    </script>

</head>
<body text="#000000" bgcolor="#ffffff" leftmargin="0" topmargin="4">
    <center>
        <table cellspacing="0" cellpadding="0" width="760" border="0">
            <tbody>
                <tr>
                    <td class="title">
                        支付宝担保交易快速通道</td>
                </tr>
            </tbody>
        </table>
        <br>
        <form name="alipayment" onsubmit="return CheckForm();" action="alipayto.aspx" method="post"
            target="_blank">
            <table>
                <tr>
                    <td valign="top">
                        <table cellspacing="0" cellpadding="0" width="740" border="0">
                            <tr>
                                <td class="form-left">
                                    收款方：
                                </td>
                                <td class="form-star">
                                    *
                                </td>
                                <td class="form-right" align="left">
                                    <%=mainname%>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center">
                                    <hr width="600" size="2" color="#999999">
                                </td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                    标题：
                                </td>
                                <td class="form-star">
                                    *
                                </td>
                                <td class="form-right" align="left">
                                    <input size="30" name="aliorder" maxlength="200"><span>如：7月5日定货款。</span></td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                    付款金额：
                                </td>
                                <td class="form-star">
                                    *</td>
                                <td class="form-right" align="left">
                                    <input maxlength="10" size="30" name="alimoney" onfocus="if(Number(this.value)==0){this.value='';}" value="00.00" />
                                    <span>如：112.21</span></td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                    备注：</td>
                                <td class="form-star">
                                </td>
                                <td class="form-right" align="left">
                                    <textarea name="alibody" rows="2" cols="40" wrap="physical"></textarea><br>
                                    （如联系方法，商品要求、数量等。100汉字内）</td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                </td>
                                <td class="form-star">
                                </td>
                                <td class="form-right">
                                    <input type="image" src="images/button_sure.gif" value="确认订单" name="nextstep"></td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top" width="205" style="font-size: 12px; font-family: '宋体'">
                        <span id="glowtext">小贴士：</span>
                        <fieldset>
                            <p class="STYLE1">
                                本通道为<a href="<%=show_url%>" target="_blank"><strong><%=mainname%></strong></a>客户专用，采用支付宝付款。请在支付前与本网站达成一致。</p>
                            <p class="style2">
                                请务必与<a href="<%=show_url%>" target="_blank"><strong><%=mainname%></strong></a>确认好订单和货款后，再付款。可以在快速付款通道里的“标题”、“订单金额”、“付款方”和备注中填入相应的订单信息。</p>
                            <p class="style2 style3">
                                &nbsp;</p>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </form>
        <table cellspacing="1" width="760" border="0">
            <tr>
                <td>
                    <font class="note-help">如果您点击“购买”按钮，即表示您已经接受“支付宝服务协议”，同意向卖家购买此物品。
                        <br>
                        您有责任查阅完整的物品登录资料，包括卖家的说明和接受的付款方式。卖家必须承担物品信息正确登录的责任！ </font>
                </td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" width="760" align="center" border="0">
            <tr align="middle">
                <td class="txt12 lh15">
                    <a href="http://china.alibaba.com/" target="_blank">阿里巴巴旗下公司</a> | 支付宝版权所有 2004-2012</td>
            </tr>
            <tr align="middle">
                <td class="txt12 lh15">
                    <img alt="支付宝通过“国际权威安全认证” " src="images/logo_vbvv.gif" border="0"><br>
                    支付宝通过“国际权威安全 认证”
                </td>
            </tr>
        </table>
</body>
</html>
