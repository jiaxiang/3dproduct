
<%
	/*
	���ܣ�������Ʒ�й���Ϣ�����ҳ��
	 *��ϸ����ҳ���ǽӿ����ҳ�棬����֧��ʱ��URL
	 *�汾��3.1
	 *���ڣ�2010-11-24
	 *˵����
	 *���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
	 *�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���
	
	 *************************ע��*****************
	������ڽӿڼ��ɹ������������⣬
	�����Ե��̻��������ģ�https://b.alipay.com/support/helperApply.htm?action=consultationApply�����ύ���뼯��Э�������ǻ���רҵ�ļ�������ʦ������ϵ��Э�������
	��Ҳ���Ե�֧������̳��http://club.alipay.com/read-htm-tid-8681712.html��Ѱ����ؽ������
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
		<title>֧�����������׸���</title>
		<style type="text/css">
.font_content{
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
table{
	border: 1px solid #CCCCCC;
}
		</style>
	</head>
	<%
		//request.setCharacterEncoding("UTF-8");
			//AlipyConfig.java��������Ϣ���������޸ģ�
			String input_charset = AlipayConfig.input_charset;
			String sign_type = AlipayConfig.sign_type;
			String seller_email = AlipayConfig.seller_email;
			String partner = AlipayConfig.partner;
			String key = AlipayConfig.key;

			String show_url = AlipayConfig.show_url;
			String notify_url = AlipayConfig.notify_url;
			String return_url = AlipayConfig.return_url;

			///////////////////////////////////////////////////////////////////////////////////

			//���²�������Ҫͨ���µ�ʱ�Ķ������ݴ���������
			//�������
			UtilDate date = new UtilDate();//��ȡ֧�������������ɶ�����
	        String out_trade_no = date.getOrderNum();//�������վ����ϵͳ�е�Ψһ������ƥ��
	        //�������ƣ���ʾ��֧��������̨��ġ���Ʒ���ơ����ʾ��֧�����Ľ��׹���ġ���Ʒ���ơ����б��
	        String subject = new String(request.getParameter("aliorder").getBytes("ISO-8859-1"),"GBK");
	        //����������������ϸ��������ע����ʾ��֧��������̨��ġ���Ʒ��������
	        String body = new String(request.getParameter("alibody").getBytes("ISO-8859-1"),"GBK");
	        //�����ܽ���ʾ��֧��������̨��ġ�Ӧ���ܶ��
	        String price = new String(request.getParameter("alimoney").getBytes("ISO-8859-1"),"GBK");

	        String logistics_fee = "0.00";				//�������ã����˷ѡ�
	        String logistics_type = "EXPRESS";			//�������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
	        String logistics_payment = "SELLER_PAY";	//����֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�

	        String quantity = "1";						//��Ʒ����������Ĭ��Ϊ1�����ı�ֵ����һ�ν��׿�����һ���¶������ǹ���һ����Ʒ��

	        //��չ������������ջ���Ϣ���Ƽ���Ϊ���
	        //�ù���������������Ѿ����̻���վ���µ����������һ���ջ���Ϣ��������Ҫ�����֧�����ĸ����������ٴ���д�ջ���Ϣ��
	        //��Ҫʹ�øù��ܣ������ٱ�֤receive_name��receive_address��ֵ
	        String receive_name	= "�ջ�������";			//�ջ����������磺����
	        String receive_address = "�ջ��˵�ַ";		//�ջ��˵�ַ���磺XXʡXXX��XXX��XXX·XXXС��XXX��XXX��ԪXXX��
	        String receive_zip = "123456";				//�ջ����ʱ࣬�磺123456
	        String receive_phone = "0571-81234567";		//�ջ��˵绰���룬�磺0571-81234567
	        String receive_mobile = "13312341234";		//�ջ����ֻ����룬�磺13312341234

	        //��չ���������ڶ���������ʽ
	        //������ʽ������Ϊһ�������֡���Ҫʹ�ã�������������Ҫ�������ݣ�����ʹ�ã�������������ҪΪ��
	        //���˵�һ��������ʽ�������еڶ���������ʽ���Ҳ������һ��������ʽ�е�����������ͬ��
	        //��logistics_type="EXPRESS"����ôlogistics_type_1�ͱ�����ʣ�µ�����ֵ��POST��EMS����ѡ��
	        String logistics_fee_1 = "";				//�������ã����˷ѡ�
	        String logistics_type_1	= "";				//�������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
	        String logistics_payment_1 = "";			//����֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�

	        //��չ��������������������ʽ
	        //������ʽ������Ϊһ�������֡���Ҫʹ�ã�������������Ҫ�������ݣ�����ʹ�ã�������������ҪΪ��
	        //���˵�һ��������ʽ�͵ڶ���������ʽ�������е�����������ʽ���Ҳ������һ��������ʽ�͵ڶ���������ʽ�е�����������ͬ��
	        //��logistics_type="EXPRESS"��logistics_type_1="EMS"����ôlogistics_type_2��ֻ��ѡ��"POST"
	        String logistics_fee_2 = "";				//�������ã����˷ѡ�
	        String logistics_type_2	= "";				//�������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
	        String logistics_payment_2 = "";			//����֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�

	        //��չ���ܲ�����������
	        String buyer_email = "";					//Ĭ�����֧�����˺�
	        String discount = "";						//�ۿۣ��Ǿ���Ľ������ǰٷֱȡ���Ҫʹ�ô��ۣ���ʹ�ø���������֤С���������λ��

	        /////////////////////////////////////////////////////////////////////////////////////////////////////
			
			////���캯������������URL
	        String sHtmlText = AlipayService.BuildForm(partner,seller_email,return_url,notify_url,show_url,out_trade_no,
				subject,body,price,logistics_fee,logistics_type,logistics_payment,quantity,receive_name,receive_address,
				receive_zip,receive_phone,receive_mobile,logistics_fee_1,logistics_type_1,logistics_payment_1,
				logistics_fee_2,logistics_type_2,logistics_payment_2,buyer_email,discount,input_charset,key,sign_type);
	%>

	<body>
        <table align="center" width="350" cellpadding="5" cellspacing="0">
            <tr>
                <td align="center" class="font_title" colspan="2">����ȷ��</td>
            </tr>
            <tr>
                <td class="font_content" align="right">�����ţ�</td>
                <td class="font_content" align="left"><%=out_trade_no%></td>
            </tr>
            <tr>
                <td class="font_content" align="right">�����ܽ�</td>
                <td class="font_content" align="left"><%=price%></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><%=sHtmlText%></td>
            </tr>
        </table>
	</body>
</html>
