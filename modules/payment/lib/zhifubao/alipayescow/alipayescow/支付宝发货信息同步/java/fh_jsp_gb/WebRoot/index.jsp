<%
	/*
	 ���ܣ�֧�����������ģ��ҳ
	 *�汾��3.1
	 *���ڣ�2010-12-17
	 *˵����
	 *���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
	 *�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���

	 */
%>
<%@ page language="java" contentType="text/html; charset=GBK"
	pageEncoding="GBK"%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=GBK">
		<title>֧���������ӿ�</title>
		<style type="text/css">
			.form-left{
				font-family:"����";
				font-size:14px;
				color:#FF6600;
			}
			.font_title{
				font-family:"����";
				font-size:16px;
				color:#FF0000;
				font-weight:bold;
			}
		</style>
		<SCRIPT language=JavaScript>
		function CheckForm()
		{
			if (document.alipayment.trade_no.value.length == 0) {
				alert("������֧�������׺�.");
				document.alipayment.trade_no.focus();
				return false;
			}
			if (document.alipayment.logistics_name.value.length == 0) {
				alert("������������˾����.");
				document.alipayment.logistics_name.focus();
				return false;
			}
			if (document.alipayment.invoice_no.value.length == 0) {
				alert("������������������.");
				document.alipayment.invoice_no.focus();
				return false;
			}
			if (document.alipayment.transport_type.value.length == 0) {
				alert("��������������ʱ����������.");
				document.alipayment.transport_type.focus();
				return false;
			}
		}  
		</SCRIPT>
	</head>
<body>
    <center>
        <form id="alipayment" name="alipayment" action="sendgoods.jsp" method="post" onsubmit="return CheckForm();">
            <table cellspacing="0" cellpadding="0" width="450" border="0">
                <tr>
                    <td class="font_title" valign="middle">
                        ֧��������ͨ��</td>
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
                                    ֧�������׺ţ�</td>
                                <td class="form-right">
                                    <input size="30" name="trade_no" maxlength="20"></td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                    �������ͣ�</td>
                                <td class="form-right">
                                    <select name="transport_type">
                                        <option value="EMS">EMS</option>
                                        <option value="POST">ƽ��</option>
                                        <option value="EXPRESS" selected="selected">���</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                    ������˾���ƣ�</td>
                                <td class="form-right">
                                    <input size="30" name="logistics_name" maxlength="30"></td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                    �����������ţ�</td>
                                <td class="form-right">
                                    <input size="30" name="invoice_no" maxlength="50"></td>
                            </tr>
                            <tr>
                                <td class="form-left">
                                </td>
                                <td class="form-right">
                                    <input name="alipaysendgoods" id="alipaysendgoods" value="�� ��" type="submit"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </form>
    </center>
	</body>
</html>
