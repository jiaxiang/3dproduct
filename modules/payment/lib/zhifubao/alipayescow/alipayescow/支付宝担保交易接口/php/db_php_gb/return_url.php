<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<?php
/*
	*���ܣ���������ת��ҳ�棨ҳ����תͬ��֪ͨҳ�棩
	*�汾��3.1
	*���ڣ�2010-11-23
	'˵����
	'���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
	'�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���
	
*/
///////////ҳ�湦��˵��///////////////
//��ҳ����ڱ������Բ���
//��ҳ�������ҳ����תͬ��֪ͨҳ�桱������֧����������ͬ�����ã��ɵ�����֧����ɺ����ʾ��Ϣҳ���硰����ĳĳĳ���������ٽ����֧���ɹ�����
//�ɷ���HTML������ҳ��Ĵ���Ͷ���������ɺ�����ݿ���³������
//��ҳ�����ʹ��PHP�������ߵ��ԣ�Ҳ����ʹ��д�ı�����log_result���е��ԣ��ú����ѱ�Ĭ�Ϲرգ���alipay_notify.php�еĺ���return_verify
//WAIT_SELLER_SEND_GOODS(��ʾ�������֧�������׹����в����˽��׼�¼�Ҹ���ɹ���������û�з���);
///////////////////////////////////

require_once("class/alipay_notify.php");
require_once("alipay_config.php");

//����֪ͨ������Ϣ
$alipay = new alipay_notify($partner,$key,$sign_type,$_input_charset,$transport);
//����ó�֪ͨ��֤���
$verify_result = $alipay->return_verify();

if($verify_result) {//��֤�ɹ�
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//������������̻���ҵ���߼��������

    //��ȡ֧������֪ͨ���ز������ɲο������ĵ���ҳ����תͬ��֪ͨ�����б�
    $dingdan           = $_GET['out_trade_no'];		//��ȡ������
    $total_fee         = $_GET['price'];			//��ȡ�ܼ۸�

    if($_GET['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
		//�жϸñʶ����Ƿ����̻���վ���Ѿ����������ɲο������ɽ̡̳��С�3.4�������ݴ�����
			//���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
			//���������������ִ���̻���ҵ�����
    }
    else {
      echo "trade_status=".$_GET['trade_status'];
    }
	
	$verify_resultShow = "��֤�ɹ�";
	
	//�������������ҵ���߼�����д�������ϴ�������ο�������
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}
else {
    //��֤ʧ��
    //��Ҫ���ԣ��뿴alipay_notify.phpҳ���return_verify�������ȶ�sign��mysign��ֵ�Ƿ���ȣ����߼��$veryfy_result��û�з���true
	
	$verify_resultShow = "��֤ʧ��";
}
?>
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
<body>
<table align="center" width="350" cellpadding="5" cellspacing="0">
  <tr>
    <td align="center" class="font_title" colspan="2">֪ͨ����</td>
  </tr>
  <tr>
    <td class="font_content" align="right">��֤�Ƿ�ɹ���</td>
    <td class="font_content" align="left"><?php echo $verify_resultShow; ?></td>
  </tr>
  <tr>
    <td class="font_content" align="right">֧�������׺ţ�</td>
    <td class="font_content" align="left"><?php echo $_GET['trade_no']; ?></td>
  </tr>
  <tr>
    <td class="font_content" align="right">�����ţ�</td>
    <td class="font_content" align="left"><?php echo $_GET['out_trade_no']; ?></td>
  </tr>
  <tr>
    <td class="font_content" align="right">�����ܽ�</td>
    <td class="font_content" align="left"><?php echo $_GET['price']; ?></td>
  </tr>
  <tr>
    <td class="font_content" align="right">��Ʒ���⣺</td>
    <td class="font_content" align="left"><?php echo $_GET['subject']; ?></td>
  </tr>
  <tr>
    <td class="font_content" align="right">��Ʒ������</td>
    <td class="font_content" align="left"><?php echo $_GET['body']; ?></td>
  </tr>
  <tr>
    <td class="font_content" align="right">����˺ţ�</td>
    <td class="font_content" align="left"><?php echo $_GET['buyer_email']; ?></td>
  </tr>
  <tr>
    <td class="font_content" align="right">�ջ���������</td>
    <td class="font_content" align="left"><?php echo $_GET['receive_name']; ?></td>
  </tr>
  <tr>
    <td class="font_content" align="right">�ջ��˵�ַ��</td>
    <td class="font_content" align="left"><?php echo $_GET['receive_address']; ?></td>
  </tr>
  <tr>
    <td class="font_content" align="right">�ջ����ʱࣺ</td>
    <td class="font_content" align="left"><?php echo $_GET['receive_zip']; ?></td>
  </tr>
  <tr>
    <td class="font_content" align="right">�ջ��˵绰��</td>
    <td class="font_content" align="left"><?php echo $_GET['receive_phone']; ?></td>
  </tr>
  <tr>
    <td class="font_content" align="right">�ջ����ֻ���</td>
    <td class="font_content" align="left"><?php echo $_GET['receive_mobile']; ?></td>
  </tr>
  <tr>
    <td class="font_content" align="right">����״̬��</td>
    <td class="font_content" align="left"><?php echo $_GET['trade_status']; ?></td>
  </tr>
</table>
</body>
</html>
