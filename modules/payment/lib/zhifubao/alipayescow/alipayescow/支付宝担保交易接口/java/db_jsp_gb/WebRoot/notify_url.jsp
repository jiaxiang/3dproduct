<%
/* *
 ���ܣ�֧��������֪ͨ���õ�ҳ�棨�������첽֪ͨҳ�棩
 �汾��3.1
 ���ڣ�2010-11-24
 ˵����
 ���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 �ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���

 //***********ҳ�湦��˵��***********
 ������ҳ���ļ�ʱ�������ĸ�ҳ���ļ������κ�HTML���뼰�ո�
 ��ҳ�治���ڱ������Բ��ԣ��뵽�������������ԡ���ȷ�����������Է��ʸ�ҳ�档
 ��ҳ����Թ�����ʹ��д�ı�����log_result���ú����ѱ�Ĭ�Ͽ���
 WAIT_BUYER_PAY(��ʾ�������֧�������׹����в����˽��׼�¼����û�и���);
 WAIT_SELLER_SEND_GOODS(��ʾ�������֧�������׹����в����˽��׼�¼�Ҹ���ɹ���������û�з���);
 WAIT_BUYER_CONFIRM_GOODS(��ʾ�����Ѿ����˻�������һ�û����ȷ���ջ��Ĳ���);
 TRADE_FINISHED(��ʾ����Ѿ�ȷ���ջ�����ʽ������);
 �÷������첽֪ͨҳ������Ҫ�����ǣ���ֹ����δ���¡����û���յ���ҳ���ӡ�� success ��Ϣ��֧��������24Сʱ�ڰ�һ����ʱ������ط�֪ͨ
 //********************************
 * */
%>
<%@ page language="java" contentType="text/html; charset=GBK" pageEncoding="GBK"%>
<%@ page import="java.util.*"%>
<%@ page import="com.alipay.util.*"%>
<%@ page import="com.alipay.config.*"%>
<%
	String key = AlipayConfig.key;
	//��ȡ֧����POST����������Ϣ
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
		//valueStr = new String(valueStr.getBytes("ISO-8859-1"), "GBK");
		params.put(name, valueStr);
	}
	
	//�ж�responsetTxt�Ƿ�Ϊture�����ɵ�ǩ�����mysign���õ�ǩ�����sign�Ƿ�һ��
	//responsetTxt�Ľ������true����������������⡢���������ID��notify_idһ����ʧЧ�й�
	//mysign��sign���ȣ��밲ȫУ���롢����ʱ�Ĳ�����ʽ���磺���Զ�������ȣ��������ʽ�й�
	String mysign = AlipayNotify.GetMysign(params,key);
	String responseTxt = AlipayNotify.Verify(request.getParameter("notify_id"));
	String sign = request.getParameter("sign");
	
	//д��־��¼����Ҫ���ԣ���ȡ����������ע�ͣ�
	String sWord = "responseTxt=" + responseTxt + "\n notify_url_log:sign=" + sign + "&mysign=" + mysign + "\n notify�����Ĳ�����" + AlipayFunction.CreateLinkString(params);
	AlipayFunction.LogResult(sWord);
	
	//��ȡ֧������֪ͨ���ز������ɲο������ĵ���ҳ����תͬ��֪ͨ�����б�(���½����ο�)//
	String trade_no = request.getParameter("trade_no");				//֧�������׺�
	String order_no = request.getParameter("out_trade_no");	        //��ȡ������
	String total_fee = request.getParameter("price");	        	//��ȡ�ܽ��
	String subject = new String(request.getParameter("subject").getBytes("ISO-8859-1"),"GBK");//��Ʒ���ơ���������
	String body = "";//��Ʒ������������ע������
	if(request.getParameter("body") != null){
		body = new String(request.getParameter("body").getBytes("ISO-8859-1"), "GBK");
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
	
	if(mysign.equals(sign) && responseTxt.equals("true")){//��֤�ɹ�
		//////////////////////////////////////////////////////////////////////////////////////////
		//������������̻���ҵ���߼��������

		//�������������ҵ���߼�����д�������´�������ο�������
		if(trade_status.equals("WAIT_BUYER_PAY")){
		//���жϱ�ʾ�������֧�������׹����в����˽��׼�¼����û�и���
		
			//�жϸñʶ����Ƿ����̻���վ���Ѿ����������ɲο������ɽ̡̳��С�3.4�������ݴ�����
				//���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
				//���������������ִ���̻���ҵ�����
			
			out.println("success");	//�벻Ҫ�޸Ļ�ɾ��
		} else if(trade_status.equals("WAIT_SELLER_SEND_GOODS")){
		//���жϱ�ʾ�������֧�������׹����в����˽��׼�¼�Ҹ���ɹ���������û�з���
		
			//�жϸñʶ����Ƿ����̻���վ���Ѿ����������ɲο������ɽ̡̳��С�3.4�������ݴ�����
				//���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
				//���������������ִ���̻���ҵ�����
			
			out.println("success");	//�벻Ҫ�޸Ļ�ɾ��
		} else if(trade_status.equals("WAIT_BUYER_CONFIRM_GOODS")){
		//���жϱ�ʾ�����Ѿ����˻�������һ�û����ȷ���ջ��Ĳ���
		
			//�жϸñʶ����Ƿ����̻���վ���Ѿ����������ɲο������ɽ̡̳��С�3.4�������ݴ�����
				//���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
				//���������������ִ���̻���ҵ�����
			
			out.println("success");	//�벻Ҫ�޸Ļ�ɾ��
		} else if(trade_status.equals("TRADE_FINISHED")){
		//���жϱ�ʾ����Ѿ�ȷ���ջ�����ʽ������
		
			//�жϸñʶ����Ƿ����̻���վ���Ѿ����������ɲο������ɽ̡̳��С�3.4�������ݴ�����
				//���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
				//���������������ִ���̻���ҵ�����
			
			out.println("success");	//�벻Ҫ�޸Ļ�ɾ��
		}
		else {
			out.println("success");	//�벻Ҫ�޸Ļ�ɾ��
		}
		//�������������ҵ���߼�����д�������ϴ�������ο�������

		//////////////////////////////////////////////////////////////////////////////////////////
	}else{//��֤ʧ��
		out.println("fail");
	}
%>
