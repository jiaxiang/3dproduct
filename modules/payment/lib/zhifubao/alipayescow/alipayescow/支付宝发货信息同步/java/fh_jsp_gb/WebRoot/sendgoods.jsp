
<%
	/*
	���ܣ�֧���������ӿڵ����ҳ�棬��������URL
	 *�汾��3.1
	 *���ڣ�2010-12-17
	 *˵����
	 *���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
	 *�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���
	
	 *************************ע��*****************
	������ڽӿڼ��ɹ������������⣬
	�����Ե��̻��������ģ�https://b.alipay.com/support/helperApply.htm?action=consultationApply�����ύ���뼯��Э�������ǻ���רҵ�ļ�������ʦ������ϵ��Э�������
	��Ҳ���Ե�֧������̳��http://club.alipay.com/read-htm-tid-8681712.html��Ѱ����ؽ������
	Ҫ���ݵĲ���Ҫô������Ϊ�գ�Ҫô�Ͳ�Ҫ���������������ؿؼ���URL�����
	
	ȷ�Ϸ���û�з������첽֪ͨҳ�棨notify_url����ҳ����תͬ��֪ͨҳ�棨return_url����
	���������󣬸ñʽ��׵�״̬�����˱����֧��������������֪ͨ���̻���վ�����̻���վ�ڵ������׻�˫���ܵĽӿ��еķ������첽֪ͨҳ�棨notify_url��
	�÷����ӿڽ���Ե������׽ӿڡ�˫���ܽӿ��еĵ�������֧�����漰����Ҫ�����������Ĳ���

	���ҿ�ݹ�˾������EXPRESS����ݣ��ķ���
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
		<title>֧���������ӿ�</title>
	</head>
	<body>
	<%
		//request.setCharacterEncoding("UTF-8");
		//AlipyConfig.java��������Ϣ���������޸ģ�
		String input_charset = AlipayConfig.input_charset;
		String sign_type = AlipayConfig.sign_type;
		String partner = AlipayConfig.partner;
		String key = AlipayConfig.key;
		/////////////////////////////////////////�������////////////////////////////////////////////////////
		//-----------�������-----------
		//֧�������׺š����ǵ�½֧������վ�ڽ��׹����в�ѯ�õ���һ����8λ���ڿ�ͷ�Ĵ����֣��磺20100419XXXXXXXXXX�� 
		String trade_no = request.getParameter("trade_no");
		
		//������˾����
		String logistics_name = new String(request.getParameter("logistics_name").getBytes("ISO-8859-1"),"GBK");
		
		//������������
		String invoice_no = new String(request.getParameter("invoice_no").getBytes("ISO-8859-1"),"GBK");
		
		//��������ʱ���������ͣ�����ֵ��ѡ��POST��ƽ�ʣ���EXPRESS����ݣ���EMS��EMS��
		String transport_type = request.getParameter("transport_type");

		//-----------ѡ�����-----------
		//���ұ��ص���IP��ַ
		String seller_ip = "";

		/////////////////////////////////////////////////////////////////////////////////////////////////////
		
		//���캯��������XMLԶ�̽���
        //String sHtmlText = AlipayService.BuildForm(partner,trade_no,logistics_name,invoice_no,transport_type,
        //seller_ip,input_charset,key,sign_type);
        //out.println(sHtmlText);

		//���캯��������XMLԶ�̽���
		String xmlResult = AlipayService.PostXml(partner,trade_no,logistics_name,invoice_no,transport_type,
        seller_ip,input_charset,key,sign_type);
        out.println(xmlResult);
		
		////////////////////////////////////////////////////////////////////////////////////////////////////
		//���ڴ˴���д�̻������ɹ����ҵ���߼�������룬�Ա���̻���վ��ĸñʶ�����֧�����Ķ�����Ϣͬ����

		///////////////////////////////////////////////////////////////////////////////////////////////////
	%>
	</body>
</html>
