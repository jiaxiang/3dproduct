<?php
/*
 *���ܣ�֧���������ӿڵ����ҳ�棬��������URL
 *�汾��3.1
 *�޸����ڣ�2010-12-15
 '˵����
 '���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 '�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���

*/

////////////////////ע��/////////////////////////
//������ڽӿڼ��ɹ������������⣬
//�����Ե��̻��������ģ�https://b.alipay.com/support/helperApply.htm?action=consultationApply�����ύ���뼯��Э�������ǻ���רҵ�ļ�������ʦ������ϵ��Э�������
//��Ҳ���Ե�֧������̳��http://club.alipay.com/read-htm-tid-8681712.html��Ѱ����ؽ������

//ȷ�Ϸ���û�з������첽֪ͨҳ�棨notify_url����ҳ����תͬ��֪ͨҳ�棨return_url����
//���������󣬸ñʽ��׵�״̬�����˱����֧��������������֪ͨ���̻���վ�����̻���վ�ڵ������׻�˫���ܵĽӿ��еķ������첽֪ͨҳ�棨notify_url��
//�÷����ӿڽ���Ե������׽ӿڡ�˫���ܽӿ��еĵ�������֧�����漰����Ҫ�����������Ĳ���

//���ҿ�ݹ�˾������EXPRESS����ݣ��ķ���
/////////////////////////////////////////////////

require_once("alipay_config.php");
require_once("class/alipay_service.php");

///////////////////////�������///////////////////
//------------�������------------
//֧�������׺š����ǵ�½֧������վ�ڽ��׹����в�ѯ�õ���һ����8λ���ڿ�ͷ�Ĵ����֣��磺20100419XXXXXXXXXX�� 
$trade_no		= $_POST['trade_no'];

//������˾����
$logistics_name	= $_POST['logistics_name'];

//������������
$invoice_no		= $_POST['invoice_no'];

//��������ʱ���������ͣ�����ֵ��ѡ��POST��ƽ�ʣ���EXPRESS����ݣ���EMS��EMS��
$transport_type	= $_POST['transport_type'];

//------------ѡ�����------------
//���ұ��ص���IP��ַ
$seller_ip		= "";

/////////////////////////////////////////////////

//����Ҫ����Ĳ�������
$parameter = array(
        "service"			=> "send_goods_confirm_by_platform",	//�ӿ����ƣ�����Ҫ�޸�

        //��ȡ�����ļ�(alipay_config.php)�е�ֵ
        "partner"			=> $partner,
        "_input_charset"	=> $_input_charset,
		
		//�������
		"trade_no"			=> $trade_no,
		"logistics_name"	=> $logistics_name,
		"invoice_no"		=> $invoice_no,
		"transport_type"	=> $transport_type,
		
		//ѡ�����
		"seller_ip"			=> $seller_ip
);

//����������
$alipay = new alipay_service($parameter,$key,$sign_type);

//��XMLԶ�̽���
//$sHtmlText = $alipay->build_form();
//echo $sHtmlText;

//��XMLԶ�̽���
//ע�⣺
//1�����û�����֧��DOMDocument��һ��PHP5�����û���֧��
//2�����û�����֧����֧��SSL
$url = $alipay->create_url();

$doc = new DOMDocument();
$doc->load($url);
//��ȡ�ɹ���ʶis_success
$itemIs_success= $doc->getElementsByTagName( "is_success" );
$nodeIs_success = $itemIs_success->item(0)->nodeValue;

//��ȡ������� error
$itemError_code= $doc->getElementsByTagName( "error" );
$nodeError_code = $itemError_code->item(0)->nodeValue;

//��ȡrequest�ڵ�������ӽڵ���Ϣ��֧�������׺š�����״̬������ʱ���
$itemTrade_no= $doc->getElementsByTagName( "trade_no" );
$nodeTrade_no = $itemTrade_no->item(0)->nodeValue;

$itemTrade_status = $doc->getElementsByTagName( "trade_status" );
$nodeTrade_status = $itemTrade_status->item(0)->nodeValue;

$itemSend_time = $doc->getElementsByTagName( "last_modified_time" );
$nodeSend_time = $itemSend_time->item(0)->nodeValue;

//��ȡtradeBase�ڵ�������ӽڵ���Ϣ���̼���վΨһ������
$itemOut_trade_no= $doc->getElementsByTagName( "out_trade_no" );
$nodeOut_trade_no = $itemOut_trade_no->item(0)->nodeValue;

/********************************************�̻�ҵ���߼�����������*************************************/
//���ڴ˴���д�̻������ɹ����ҵ���߼�������룬�Ա���̻���վ��ĸñʶ�����֧�����Ķ�����Ϣͬ����


/*********************************************************************************************************/

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
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
    <td class="font_content" align="left"><?php echo $nodeIs_success ?></td>
  </tr>
  <?php
  if($nodeIs_success == "T"){
      echo '<tr>
    <td class="font_content" align="right">�����ţ�</td>
    <td class="font_content" align="left">'.$nodeOut_trade_no.'</td>
  </tr>
  <tr>
    <td class="font_content" align="right">֧�������׺ţ�</td>
    <td class="font_content" align="left">'.$nodeTrade_no.'</td>
  </tr>
  <tr>
    <td class="font_content" align="right">����״̬��</td>
    <td class="font_content" align="left">'.$nodeTrade_status.'</td>
  </tr>
  <tr>
    <td class="font_content" align="right">����ʱ�䣺</td>
    <td class="font_content" align="left">'.$nodeSend_time.'</td>
  </tr>';
  }elseif($nodeIs_success == "F"){
      echo '<tr>
    <td class="font_content" align="right">������룺</td>
    <td class="font_content" align="left">'.$nodeError_code.'</td>
  </tr>';
  }
  ?>
  
</table>
</body>
</html>