
<%
	/*
	 ���ܣ���Ա��ע���¼�ӿڵ����ҳ�棬��������URL
	 *�汾��3.1.1
	 *���ڣ�2010-11-30
	 *˵����
	 *���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
	 *�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���
	
	 *************************ע��*****************
	 ������ڽӿڼ��ɹ������������⣬
	 �������ύ���뼯��Э�������ǻ���רҵ�ļ�������ʦ������ϵ��Э�������
	 ��Ҳ���Ե�֧������̳��http://club.alipay.com/read-htm-tid-8681712.html��Ѱ����ؽ������
	 
	 �������ʹ����չ���������չ���ܲ�������ֵ��
	 Ҫ���ݵĲ���Ҫô������Ϊ�գ�Ҫô�Ͳ�Ҫ���������������ؿؼ���URL�����
	 **********************************************
	 */
%>
<%@ page language="java" contentType="text/html; charset=GBK"
	pageEncoding="GBK"%>
<%@ page import="com.alipay.config.*"%>
<%@ page import="com.alipay.util.*"%>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=GBK">
		<title>֧������Աͨ�õ�½</title>
	</head>
	<%
		//request.setCharacterEncoding("UTF-8");
		//AlipyConfig.java��������Ϣ���������޸ģ�
		String input_charset = AlipayConfig.input_charset;
		String sign_type = AlipayConfig.sign_type;
		String partner = AlipayConfig.partnerID;
		String key = AlipayConfig.key;

		String return_url = AlipayConfig.return_url;
		
		///////////////////////////////////////////////////////////////////////////////////
		
		//ѡ�����
        String email = "";      //��Ա��ע���½ʱ����Ա��֧�����˺�

        /////////////////////////////////////////////////////////////////////////////////////////////////////

		//���캯������������URL
		String sHtmlText = AlipayService.BuildForm(partner,return_url,email,input_charset,key,sign_type);
	%>

	<body>
		<style type="text/css">
<!--
.style1 {
	color: #FF0000
}
-->
</style>
<br><br>
		<table width="30%" border="0" align="center">
			<tr>
				<th scope="col" style="FONT-SIZE: 14px; COLOR: #FF6600; FONT-FAMILY: Verdana">
					֧������Աͨ�õ�½
				</th>
			</tr>
			<tr>
				<td ><%= sHtmlText%></td>
			</tr>
			<tr>
				<td height="2" bgcolor="#ff7300"></td>
			</tr>
		</table>
	</body>
</html>
