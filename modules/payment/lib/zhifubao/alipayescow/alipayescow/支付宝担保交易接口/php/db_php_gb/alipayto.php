<?php
/*
 *���ܣ�������Ʒ�й���Ϣ��ȷ�϶���֧�������߹������ҳ��
 *��ϸ����ҳ���ǽӿ����ҳ�棬����֧��ʱ��URL
 *�汾��3.1
 *�޸����ڣ�2010-11-23
 '˵����
 '���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 '�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���

*/

////////////////////ע��/////////////////////////
//������ڽӿڼ��ɹ������������⣬
//�����Ե��̻��������ģ�https://b.alipay.com/support/helperApply.htm?action=consultationApply�����ύ���뼯��Э�������ǻ���רҵ�ļ�������ʦ������ϵ��Э�������
//��Ҳ���Ե�֧������̳��http://club.alipay.com/read-htm-tid-8681712.html��Ѱ����ؽ������
//�������ʹ����չ���������չ���ܲ�������ֵ��
//�ܽ����㷽ʽ�ǣ��ܽ��=price*quantity+logistics_fee+discount��
//�����price����Ϊ�ܽ��������˷ѡ��ۿۡ����ﳵ�й�����Ʒ�ܶ�ȼ��������ն�����Ӧ���ܶ
//������������ֻʹ��һ�飬����������̻���վ���µ�ʱѡ����������ͣ���ݡ�ƽ�ʡ�EMS���������Զ�ʶ��logistics_type�����������е�һ��ֵ
//���ҿ�ݹ�˾������EXPRESS����ݣ��ķ���
/////////////////////////////////////////////////

require_once("alipay_config.php");
require_once("class/alipay_service.php");

/*���²�������Ҫͨ���µ�ʱ�Ķ������ݴ���������*/
//�������
$out_trade_no	= date(Ymdhms);				//�������վ����ϵͳ�е�Ψһ������ƥ��
$subject		= $_POST['aliorder'];		//�������ƣ���ʾ��֧��������̨��ġ���Ʒ���ơ����ʾ��֧�����Ľ��׹���ġ���Ʒ���ơ����б��
$body			= $_POST['alibody'];		//����������������ϸ��������ע����ʾ��֧��������̨��ġ���Ʒ��������
$price			= $_POST['alimoney'];		//�����ܽ���ʾ��֧��������̨��ġ�Ӧ���ܶ��

$logistics_fee		= "0.00";				//�������ã����˷ѡ�
$logistics_type		= "EXPRESS";			//�������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
$logistics_payment	= "SELLER_PAY";			//����֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�

$quantity		= "1";						//��Ʒ����������Ĭ��Ϊ1�����ı�ֵ����һ�ν��׿�����һ���¶������ǹ���һ����Ʒ��

//��չ������������ջ���Ϣ���Ƽ���Ϊ���
//�ù���������������Ѿ����̻���վ���µ����������һ���ջ���Ϣ��������Ҫ�����֧�����ĸ����������ٴ���д�ջ���Ϣ��
//��Ҫʹ�øù��ܣ������ٱ�֤receive_name��receive_address��ֵ
//�ջ���Ϣ��ʽ���ϸ�����������ַ���ʱࡢ�绰���ֻ��ĸ�ʽ��д
$receive_name		= "�ջ�������";			//�ջ����������磺����
$receive_address	= "�ջ��˵�ַ";			//�ջ��˵�ַ���磺XXʡXXX��XXX��XXX·XXXС��XXX��XXX��ԪXXX��
$receive_zip		= "123456";				//�ջ����ʱ࣬�磺123456
$receive_phone		= "0571-81234567";		//�ջ��˵绰���룬�磺0571-81234567
$receive_mobile		= "13312341234";		//�ջ����ֻ����룬�磺13312341234

//��չ���������ڶ���������ʽ
//������ʽ������Ϊһ�������֡���Ҫʹ�ã�������������Ҫ�������ݣ�����ʹ�ã�������������ҪΪ��
//���˵�һ��������ʽ�������еڶ���������ʽ���Ҳ������һ��������ʽ�е�����������ͬ��
//��logistics_type="EXPRESS"����ôlogistics_type_1�ͱ�����ʣ�µ�����ֵ��POST��EMS����ѡ��
$logistics_fee_1	= "";					//�������ã����˷ѡ�
$logistics_type_1	= "";					//�������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
$logistics_payment_1= "";					//����֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�

//��չ��������������������ʽ
//������ʽ������Ϊһ�������֡���Ҫʹ�ã�������������Ҫ�������ݣ�����ʹ�ã�������������ҪΪ��
//���˵�һ��������ʽ�͵ڶ���������ʽ�������е�����������ʽ���Ҳ������һ��������ʽ�͵ڶ���������ʽ�е�����������ͬ��
//��logistics_type="EXPRESS"��logistics_type_1="EMS"����ôlogistics_type_2��ֻ��ѡ��"POST"
$logistics_fee_2	= "";					//�������ã����˷ѡ�
$logistics_type_2	= "";					//�������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
$logistics_payment_2= "";					//����֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�

//��չ���ܲ�����������
$buyer_email		= '';			//Ĭ�����֧�����˺�
$discount	 		= '';			//�ۿۣ��Ǿ���Ľ������ǰٷֱȡ���Ҫʹ�ô��ۣ���ʹ�ø���������֤С���������λ��

/////////////////////////////////////////////////

//����Ҫ����Ĳ�������
$parameter = array(
        "service"			=> "create_partner_trade_by_buyer",	//�ӿ����ƣ�����Ҫ�޸�
        "payment_type"		=> "1",               				//�������ͣ�����Ҫ�޸�

        //��ȡ�����ļ�(alipay_config.php)�е�ֵ
        "partner"			=> $partner,
        "seller_email"		=> $seller_email,
        "return_url"		=> $return_url,
        "notify_url"		=> $notify_url,
        "_input_charset"	=> $_input_charset,
        "show_url"			=> $show_url,

        //�Ӷ��������ж�̬��ȡ���ı������
        "out_trade_no"		=> $out_trade_no,
        "subject"			=> $subject,
        "body"				=> $body,
        "price"				=> $price,
		"quantity"			=> $quantity,
		
		"logistics_fee"		=> $logistics_fee,
		"logistics_type"	=> $logistics_type,
		"logistics_payment"	=> $logistics_payment,
		
		//��չ���ܲ�����������ջ���Ϣ
		"receive_name"		=> $receive_name,
		"receive_address"	=> $receive_address,
		"receive_zip"		=> $receive_zip,
		"receive_phone"		=> $receive_phone,
		"receive_mobile"	=> $receive_mobile,
		
		//��չ���ܲ��������ڶ���������ʽ
		"logistics_fee_1"	=> $logistics_fee_1,
		"logistics_type_1"	=> $logistics_type_1,
		"logistics_payment_1"=> $logistics_payment_1,
		
		//��չ���ܲ�������������������ʽ
		"logistics_fee_2"	=> $logistics_fee_2,
		"logistics_type_2"	=> $logistics_type_2,
		"logistics_payment_2"=> $logistics_payment_2,

		//��չ���ܲ�����������
		"discount"			=> $discount,
		"buyer_email"		=> $buyer_email
);

//����������
$alipay = new alipay_service($parameter,$key,$sign_type);
$sHtmlText = $alipay->build_form();

?>
<html>
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <title>֧������������֧��</title>
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
                <td align="center" class="font_title" colspan="2">����ȷ��</td>
            </tr>
            <tr>
                <td class="font_content" align="right">�����ţ�</td>
                <td class="font_content" align="left"><?php echo $out_trade_no; ?></td>
            </tr>
            <tr>
                <td class="font_content" align="right">�����ܽ�</td>
                <td class="font_content" align="left"><?php echo $price; ?></td>
            </tr>
            <tr>
                <td align="center" colspan="2"><?php echo $sHtmlText; ?></td>
            </tr>
        </table>
    </body>
</html>
