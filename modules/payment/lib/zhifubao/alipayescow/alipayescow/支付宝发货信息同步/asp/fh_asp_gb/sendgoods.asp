<%
	'���ܣ�֧���������ӿڵ����ҳ�棬��������URL
	'�汾��3.1
	'���ڣ�2010-12-02
	'˵����
	'���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
	'�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���
	
'''''''''''''''''ע��'''''''''''''''''''''''''
'������ڽӿڼ��ɹ������������⣬
'�����Ե��̻��������ģ�https://b.alipay.com/support/helperApply.htm?action=consultationApply�����ύ���뼯��Э�������ǻ���רҵ�ļ�������ʦ������ϵ��Э�������
'��Ҳ���Ե�֧������̳��http://club.alipay.com/read-htm-tid-8681712.html��Ѱ����ؽ������

'ȷ�Ϸ���û�з������첽֪ͨҳ�棨notify_url����ҳ����תͬ��֪ͨҳ�棨return_url����
'���������󣬸ñʽ��׵�״̬�����˱����֧��������������֪ͨ���̻���վ�����̻���վ�ڵ������׻�˫���ܵĽӿ��еķ������첽֪ͨҳ�棨notify_url��
'�÷����ӿڽ���Ե������׽ӿڡ�˫���ܽӿ��еĵ�������֧�����漰����Ҫ�����������Ĳ���

'���ҿ�ݹ�˾������EXPRESS����ݣ��ķ���
''''''''''''''''''''''''''''''''''''''''''''''
%>
<!--#include file="alipay_config.asp"-->
<!--#include file="class/alipay_service.asp"-->
<%
'*********************************************�������*********************************************

'------------�������------------
'֧�������׺š����ǵ�½֧������վ�ڽ��׹����в�ѯ�õ���һ����8λ���ڿ�ͷ�Ĵ����֣��磺20100419XXXXXXXXXX�� 
trade_no		= request.Form("trade_no")

'������˾����
logistics_name	= request.Form("logistics_name")

'������������
invoice_no		= request.Form("invoice_no")

'��������ʱ���������ͣ�����ֵ��ѡ��POST��ƽ�ʣ���EXPRESS����ݣ���EMS��EMS��
transport_type	= request.Form("transport_type")

'------------ѡ�����------------
'���ұ��ص���IP��ַ
seller_ip		= ""

''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''

'����Ҫ����Ĳ������飬����Ķ�
para = Array("service=send_goods_confirm_by_platform","partner="&partner,"trade_no="&trade_no,"logistics_name="&logistics_name,"invoice_no="&invoice_no,"transport_type="&transport_type,"seller_ip="&seller_ip,"_input_charset="&input_charset)

'����������
alipay_service(para)

'��XMLԶ�̽���
'sHtmlText = build_form()
'response.Write sHtmlText

'��XMLԶ�̽���
'Զ��XML������֧�������̷���������XML��Ϣ������
'ע�⣺Զ�̽���XML������IIS�����������й�
url = create_url()

dim is_success,nodeTrade_status,nodeSend_time,nodeOut_trade_no,nodeError

Dim http,xml
Set http=Server.CreateObject("Microsoft.XMLHTTP")
http.Open "GET",url,False
http.send
Set xml=Server.CreateObject("Microsoft.XMLDOM")
xml.Async=true
xml.ValidateOnParse=False
xml.Load(http.ResponseXML)

set DataIs_success=xml.getElementsByTagName("is_success")  '��ȡ�ɹ���ʶis_success
is_success = DataIs_success.item(0).childnodes(0).text
if is_success = "T" then	
	set DataTradeBase=xml.getElementsByTagName("tradeBase")  '��ȡtradeBase�ڵ�������ӽڵ���Ϣ���̼���վΨһ������
	nodeSend_time = DataTradeBase.item(0).childnodes(9).text
	nodeOut_trade_no = DataTradeBase.item(0).childnodes(11).text
	nodeTrade_status = DataTradeBase.item(0).childnodes(24).text
else
	set DataError=xml.getElementsByTagName("error")  '��ȡ�������error
	nodeError = DataError.item(0).childnodes(0).text
end if

'*************************************************************************************************
'���ڴ˴���д�̻������ɹ����ҵ���߼�������룬�Ա���̻���վ��ĸñʶ�����֧�����Ķ�����Ϣͬ����

'*************************************************************************************************

%>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>֧��������</title>
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
<table align="center" width="350" cellpadding="5" cellspacing="0">
  <tr>
    <td align="center" class="font_title" colspan="2">XML����</td>
  </tr>
  <tr>
    <td class="font_content" align="right">�Ƿ񷢻��ɹ���</td>
    <td class="font_content" align="left"><%=is_success%></td>
  </tr>
 <%
 if is_success = "T" then
 %>
  <tr>
    <td class="font_content" align="right">�����ţ�</td>
    <td class="font_content" align="left"><%=nodeOut_trade_no%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">֧�������׺ţ�</td>
    <td class="font_content" align="left"><%=trade_no%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">����״̬��</td>
    <td class="font_content" align="left"><%=nodeTrade_status%></td>
  </tr>
  <tr>
    <td class="font_content" align="right">����ʱ�䣺</td>
    <td class="font_content" align="left"><%=nodeSend_time%></td>
  </tr>
  <%
  else
  %>
  <tr>
    <td class="font_content" align="right">������룺</td>
    <td class="font_content" align="left"><%=nodeError%></td>
  </tr>
  <%
  end if
  %>
</table>
</body>
</html>
