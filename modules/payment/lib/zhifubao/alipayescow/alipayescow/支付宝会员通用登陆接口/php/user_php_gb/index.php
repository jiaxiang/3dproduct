<?php
/*
 *���ܣ���Աͨ�õ�¼�ӿڵ����ҳ�棬��������URL
 *�汾��3.1
 *�޸����ڣ�2010-11-26
 '˵����
 '���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 '�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���

*/

////////////////////ע��/////////////////////////
//������ڽӿڼ��ɹ������������⣬
//�����Ե��̻��������ģ�https://b.alipay.com/support/helperApply.htm?action=consultationApply�����ύ���뼯��Э�������ǻ���רҵ�ļ�������ʦ������ϵ��Э�������
//��Ҳ���Ե�֧������̳��http://club.alipay.com/read-htm-tid-8681712.html��Ѱ����ؽ������
/////////////////////////////////////////////////

require_once("alipay_config.php");
require_once("class/alipay_user_service.php");

//ѡ�����
$email = "";		//��Աͨ�õ�¼ʱ����Ա��֧�����˺�

/////////////////////////////////////////////////

//����Ҫ����Ĳ�������
$parameter = array(
        "service"			=> "user_authentication",	//�ӿ����ƣ�����Ҫ�޸�

        //��ȡ�����ļ�(alipay_config.php)�е�ֵ
        "partner"			=> $partner,
        "return_url"		=> $return_url,
        "_input_charset"	=> $_input_charset,
		
		//ѡ�����
		"email"				=> $email
);

//����������
$alipay = new alipay_user_service($parameter,$key,$sign_type);
$sHtmlText = $alipay->build_form();

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>֧������Աͨ�õ�¼</title>
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
    <td align="center" class="font_title">֧������Աͨ�õ�¼</td>
  </tr>
  <tr>
    <td align="center"><?php echo $sHtmlText; ?></td>
  </tr>
</table>
</body>
</html>
