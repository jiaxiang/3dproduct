<%
/* *
 ���ܣ�֧������Ա��¼��ɺ���ת���ص�ҳ�棨����ҳ��
 �汾��3.1
 ���ڣ�2010-10-26
 ˵����
 ���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 �ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���

 //***********ҳ�湦��˵��***********
 ��ҳ����ڱ������Բ���
 ��ҳ�����������ҳ��������֧����������ͬ������
 �ɷ���HTML������ҳ��Ĵ���Ͷ���������ɺ�����ݿ���³������
 ���飺
 ���̻���վ��Ա���ݿ�������һ���ֶΣ�user_id��֧�����û�ΨһID����
 �����ص���Ϣ��ֹ�в���user_id����ô������֧������Ա��Ϣ�����ݱ�
 ��Ա��Ϣ�����ݱ��е�ΨһID�����̻���վ��Ա���ݱ��е�
 //********************************
 * */
%>
<%@ page language="java" contentType="text/html; charset=GBK" pageEncoding="GBK"%>
<%@ page import="java.util.*"%>
<%@ page import="com.alipay.util.*"%>
<%@ page import="com.alipay.config.*"%>
<html>
  <head>
		<meta http-equiv="Content-Type" content="text/html; charset=GBK">
		<title>֧������Ա��ע���¼������Ϣ</title>
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

	if(mysign.equals(sign) && responseTxt.equals("true")){
	///////////////////////////������������̻���ҵ���߼��������/////////////////////////////////
    //���������ҵ���߼�����д�������´�������ο���
    //��ȡ֧������֪ͨ���ز���
	String user_id = request.getParameter("user_id");		//��ȡ֧�����û�ΨһID��
		//������������̻���ҵ���߼��������
		//������ʾ������
		//�жϻ�ȡ����user_id��ֵ�Ƿ����̻���Ա���ݿ��д��ڣ������Ƿ���������֧������Ա��ע���½��
		//	�������ڣ�������Զ�Ϊ��Ա����ע��һ����Ա������Ϣ�����̻���վ��Ա���ݱ��У�
		//	�ҰѸû�Ա�����̻���վ�ϵĵ�¼״̬�����ĳɡ��ѵ�¼��״̬������¼���̼���վ��Ա���ݱ��м�¼��½��Ϣ�����½ʱ�䡢������IP�ȡ�
		//	�����ڣ��жϸû�Ա���̻���վ�ϵĵ�¼״̬�Ƿ��ǡ��ѵ�¼��״̬
		//		�����ǣ���Ѹû�Ա�����̻���վ�ϵĵ�¼״̬�����ĳɡ��ѵ�¼��״̬������¼���̼���վ��Ա���ݱ��м�¼��½��Ϣ�����½ʱ�䡢������IP�ȡ�
		//		���ǣ������κ����ݿ�ҵ���߼������ж��ôη�����ϢΪ�ظ�ˢ�·������ӵ��¡�
	//���������ҵ���߼�����д�������ϴ�������ο���
    ///////////////////////////////////////////////////////////////////////////////////////
%>
<table align="center" width="350" cellpadding="5" cellspacing="0">
	<tr>
	    <td align="center" class="font_title">�װ����̳ǻ�Ա��<%=user_id%>��<br />���Ѿ���¼�ɹ�</td>
	</tr>
</table>
<%
	}else{
%>
<table align="center" width="350" cellpadding="5" cellspacing="0">
  <tr>
    <td align="center" class="font_title">ϵͳ������֤ʧ��</td>
  </tr>
</table>
<%
	}
%>
  </body>
</html>
