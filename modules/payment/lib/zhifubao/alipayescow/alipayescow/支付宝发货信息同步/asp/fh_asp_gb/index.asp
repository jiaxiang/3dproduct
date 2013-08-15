<%
	'功能：支付宝发货入口模板页
	'版本：3.1
	'日期：2010-12-02
	'说明：
	'以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
	'该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML XMLNS:CC>
<HEAD>
<TITLE>支付宝 - 网上支付 安全快速！</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">
<META content=网上购物/网上支付/安全支付/安全购物/购物，安全/支付,安全/支付宝/安全,支付/安全，购物/支付, 
name=description 在线 付款,收款 网上,贸易 网上贸易.>
<META content=网上购物/网上支付/安全支付/安全购物/购物，安全/支付,安全/支付宝/安全,支付/安全，购物/支付, name=keywords 
在线 付款,收款 网上,贸易 网上贸易.>
<SCRIPT language=JavaScript>
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
</SCRIPT>
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
</HEAD>
<BODY text=#000000 bgColor=#ffffff leftMargin=0 topMargin=4>
<CENTER>
  <BR />
  <FORM name=alipayment onSubmit="return CheckForm();" action=sendgoods.asp 
method=post target="_blank">
    <TABLE cellSpacing=0 cellPadding=0 width=450 border=0>
      <TR>
        <TD class=font_title valign="middle">支付宝发货通道</TD>
      </TR>
      <TR>
        <TD align="center"><HR width=450 SIZE=2 color="#999999"></TD>
      </TR>
      <tr>
        <td align="center"><TABLE cellSpacing=0 cellPadding=0 width=350 border=0>
            <TR>
              <TD class=form-left>支付宝交易号：</TD>
              <TD class=form-right><INPUT size=30 name=trade_no maxlength="20"></TD>
            </TR>
            <TR>
              <TD class=form-left>发货类型：</TD>
              <TD class=form-right><select name="transport_type">
                  <option value="EMS">EMS</option>
                  <option value="POST">平邮</option>
                  <option value="EXPRESS" selected="selected">快递</option>
                </select>
              </TD>
            </TR>
            <TR>
              <TD class=form-left>物流公司名称：</TD>
              <TD class=form-right><INPUT size=30 name=logistics_name maxlength="30"></TD>
            </TR>
            <TR>
              <TD class=form-left>物流发货单号：</TD>
              <TD class=form-right><INPUT size=30 name=invoice_no maxlength="50"></TD>
            </TR>
            <TR>
              <TD class=form-left></TD>
              <TD class=form-right><input name="alipaysendgoods" id="alipaysendgoods" value="发 货" type="submit"></TD>
            </TR>
          </TABLE></td>
      </tr>
    </TABLE>
  </FORM>
</CENTER>
</BODY>
</HTML>
