<%
/* *
 ���ܣ���������ת��ҳ�棨ҳ����תͬ��֪ͨҳ�棩
 �汾��3.1
 ���ڣ�2010-11-24
 ˵����
 ���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 �ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���

 //***********ҳ�湦��˵��***********
 ��ҳ����ڱ������Բ���
 ��ҳ�������ҳ����תͬ��֪ͨҳ�桱������֧����������ͬ�����ã��ɵ�����֧����ɺ����ʾ��Ϣҳ���硰����ĳĳĳ���������ٽ����֧���ɹ�����
 �ɷ���HTML������ҳ��Ĵ���Ͷ���������ɺ�����ݿ���³������
WAIT_SELLER_SEND_GOODS(��ʾ�������֧�������׹����в����˽��׼�¼�Ҹ���ɹ���������û�з���);
 //********************************
 * */
%>
<%@ page language="java" contentType="text/html; charset=GBK" pageEncoding="GBK"%>
<%@ page import="java.util.*"%>
<%@ page import="java.util.Map"%>
<%@ page import="com.alipay.util.*"%>
<%@ page import="com.alipay.config.*"%>
<html>
  <head>
		<meta http-equiv="Content-Type" content="text/html; charset=GBK">
		<title>֧���ɹ��ͻ��˷���</title>
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
  <body>
<%
	String key = AlipayConfig.key;
	//��ȡ֧����GET����������Ϣ
	Map params = new HashMap();
	Map requestParams = request.getParameterMap();
	for (Iterator iter = requestParams.keySet().iterator(); iter.hasNext();) {
		String name = (String) iter.next();
		String[] values = (String[]) requestParams.get(name);
		String valueStr = "";
		for (int i = 0; i < values.length; i++) {
			valueStr = (i == values.length - 1) ? valueStr + values[i]
					: valueStr + values[i] + ",";
		}
		//����������δ����ڳ�������ʱʹ�á����mysign��sign�����Ҳ����ʹ����δ���ת��
		valueStr = new String(valueStr.getBytes("ISO-8859-1"), "GBK");
		params.put(name, valueStr);
	}
	
	//�ж�responsetTxt�Ƿ�Ϊture�����ɵ�ǩ�����mysign���õ�ǩ�����sign�Ƿ�һ��
	//responsetTxt�Ľ������true����������������⡢���������ID��notify_idһ����ʧЧ�й�
	//mysign��sign���ȣ��밲ȫУ���롢����ʱ�Ĳ�����ʽ���磺���Զ�������ȣ��������ʽ�й�
	String mysign = AlipayNotify.GetMysign(params,key);
	String responseTxt = AlipayNotify.Verify(request.getParameter("notify_id"));
	String sign = request.getParameter("sign");
	
	//д��־��¼����Ҫ���ԣ���ȡ����������ע�ͣ�
	//String sWord = "responseTxt=" + responseTxt + "\n return_url_log:sign=" + sign + "&mysign=" + mysign + "\n return�����Ĳ�����" + AlipayFunction.CreateLinkString(params);
	//AlipayFunction.LogResult(sWord);

	//��ȡ֧������֪ͨ���ز������ɲο������ĵ���ҳ����תͬ��֪ͨ�����б�(���½����ο�)//
	String trade_no = request.getParameter("trade_no");				//֧�������׺�
	String order_no = request.getParameter("out_trade_no");	        //��ȡ������
	String total_fee = request.getParameter("price");	            //��ȡ�ܽ��
	String subject = new String(request.getParameter("subject").getBytes("ISO-8859-1"),"GBK");				//��Ʒ���ơ���������
	String body = "";
	if(request.getParameter("body") != null){
		body = new String(request.getParameter("body").getBytes("ISO-8859-1"), "GBK");//��Ʒ������������ע������
	}
	String buyer_email = request.getParameter("buyer_email");		//���֧�����˺�
	String receive_name = "";//�ջ�������
	if(request.getParameter("receive_name") != null){
		receive_name = new String(request.getParameter("receive_name").getBytes("ISO-8859-1"), "GBK");
	}
	String receive_address = "";//�ջ��˵�ַ
	if(request.getParameter("receive_address") != null){
		receive_address = new String(request.getParameter("receive_address").getBytes("ISO-8859-1"), "GBK");
	}
	String receive_zip = "";//�ջ����ʱ�
	if(request.getParameter("receive_zip") != null){
		receive_zip = new String(request.getParameter("receive_zip").getBytes("ISO-8859-1"), "GBK");
	}
	String receive_phone = "";//�ջ��˵绰
	if(request.getParameter("receive_phone") != null){
		receive_phone = new String(request.getParameter("receive_phone").getBytes("ISO-8859-1"), "GBK");
	}
	String receive_mobile = "";//�ջ����ֻ�
	if(request.getParameter("receive_mobile") != null){
		receive_mobile = new String(request.getParameter("receive_mobile").getBytes("ISO-8859-1"), "GBK");
	}
	String trade_status = request.getParameter("trade_status");		//����״̬
	//��ȡ֧������֪ͨ���ز������ɲο������ĵ���ҳ����תͬ��֪ͨ�����б�(���Ͻ����ο�)//

	String verifyStatus = "";
	
	if(mysign.equals(sign) && responseTxt.equals("true")){
		//////////////////////////////////////////////////////////////////////////////////////////
		//������������̻���ҵ���߼��������

		//�������������ҵ���߼�����д�������´�������ο�������	
		if(trade_status.equals("WAIT_SELLER_SEND_GOODS")){
			//�жϸñʶ����Ƿ����̻���վ���Ѿ����������ɲο������ɽ̡̳��С�3.4�������ݴ�����
				//���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
				//���������������ִ���̻���ҵ�����
		}
		
		verifyStatus = "��֤�ɹ�";
		//�������������ҵ���߼�����д�������ϴ�������ο�������
		
		//////////////////////////////////////////////////////////////////////////////////////////
	}else{
		verifyStatus = "��֤ʧ��";
	}
%>
<table align="center" width="350" cellpadding="5" cellspacing="0">
            <tr>
                <td align="center" class="font_title" colspan="2">
                    ֪ͨ����</td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    ֧�������׺ţ�</td>
                <td class="font_content" align="left"><%=trade_no %></td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    �����ţ�</td>
                <td class="font_content" align="left"><%=order_no %></td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    �����ܽ�</td>
                <td class="font_content" align="left"><%=total_fee %></td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    ��Ʒ���⣺</td>
                <td class="font_content" align="left"><%=subject %></td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    ��Ʒ������</td>
                <td class="font_content" align="left"><%=body %></td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    ����˺ţ�</td>
                <td class="font_content" align="left"><%=buyer_email %></td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    �ջ���������</td>
                <td class="font_content" align="left"><%=receive_name %></td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    �ջ��˵�ַ��</td>
                <td class="font_content" align="left"><%=receive_address %></td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    �ջ����ʱࣺ</td>
                <td class="font_content" align="left"><%=receive_zip %></td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    �ջ��˵绰��</td>
                <td class="font_content" align="left"><%=receive_phone %></td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    �ջ����ֻ���</td>
                <td class="font_content" align="left"><%=receive_mobile %></td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    ����״̬��</td>
                <td class="font_content" align="left"><%=trade_status %></td>
            </tr>
            <tr>
                <td class="font_content" align="right">
                    ��֤״̬��</td>
                <td class="font_content" align="left"><%=verifyStatus %></td>
            </tr>
        </table>
  </body>
</html>
