<%@ Page Language="C#" AutoEventWireup="true" CodeFile="default.aspx.cs" Inherits="_default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>֧���� - ����֧�� ��ȫ���٣�</title>
    <link href="images/layout.css" type="text/css" rel="stylesheet">

    <script language="JavaScript">
<!-- 
  //У�������  -->
function CheckForm()
{
	if (document.alipayment.aliorder.value.length == 0) {
		alert("��������Ʒ����.");
		document.alipayment.aliorder.focus();
		return false;
	}
	if (document.alipayment.alimoney.value.length == 0) {
		alert("�����븶����.");
		document.alipayment.alimoney.focus();
		return false;
	}
	var reg	= new RegExp(/^\d*\.?\d{0,2}$/);
	if (! reg.test(document.alipayment.alimoney.value))
	{
        alert("����ȷ���븶����");
		document.alipayment.alimoney.focus();
		return false;
	}
	if (Number(document.alipayment.alimoney.value) < 0.01) {
		alert("����������С��0.01.");
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
                        ֧�����������׿���ͨ��</td>
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
                                    �տ��
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
                                    ���⣺
                                </td>
                                <td class="form-star">
                                    *
                                </td>
                                <td class="form-right" align="left">
                                    <input size="30" name="aliorder" maxlength="200"><span>�磺7��5�ն����</span></td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                    �����
                                </td>
                                <td class="form-star">
                                    *</td>
                                <td class="form-right" align="left">
                                    <input maxlength="10" size="30" name="alimoney" onfocus="if(Number(this.value)==0){this.value='';}" value="00.00" />
                                    <span>�磺112.21</span></td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                    ��ע��</td>
                                <td class="form-star">
                                </td>
                                <td class="form-right" align="left">
                                    <textarea name="alibody" rows="2" cols="40" wrap="physical"></textarea><br>
                                    ������ϵ��������ƷҪ�������ȡ�100�����ڣ�</td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                </td>
                                <td class="form-star">
                                </td>
                                <td class="form-right">
                                    <input type="image" src="images/button_sure.gif" value="ȷ�϶���" name="nextstep"></td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top" width="205" style="font-size: 12px; font-family: '����'">
                        <span id="glowtext">С��ʿ��</span>
                        <fieldset>
                            <p class="STYLE1">
                                ��ͨ��Ϊ<a href="<%=show_url%>" target="_blank"><strong><%=mainname%></strong></a>�ͻ�ר�ã�����֧�����������֧��ǰ�뱾��վ���һ�¡�</p>
                            <p class="style2">
                                �������<a href="<%=show_url%>" target="_blank"><strong><%=mainname%></strong></a>ȷ�Ϻö����ͻ�����ٸ�������ڿ��ٸ���ͨ����ġ����⡱��������������������ͱ�ע��������Ӧ�Ķ�����Ϣ��</p>
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
                    <font class="note-help">�������������򡱰�ť������ʾ���Ѿ����ܡ�֧��������Э�顱��ͬ�������ҹ������Ʒ��
                        <br>
                        �������β�����������Ʒ��¼���ϣ��������ҵ�˵���ͽ��ܵĸ��ʽ�����ұ���е���Ʒ��Ϣ��ȷ��¼�����Σ� </font>
                </td>
            </tr>
        </table>
        <table cellspacing="0" cellpadding="0" width="760" align="center" border="0">
            <tr align="middle">
                <td class="txt12 lh15">
                    <a href="http://china.alibaba.com/" target="_blank">����Ͱ����¹�˾</a> | ֧������Ȩ���� 2004-2012</td>
            </tr>
            <tr align="middle">
                <td class="txt12 lh15">
                    <img alt="֧����ͨ��������Ȩ����ȫ��֤�� " src="images/logo_vbvv.gif" border="0"><br>
                    ֧����ͨ��������Ȩ����ȫ ��֤��
                </td>
            </tr>
        </table>
</body>
</html>
