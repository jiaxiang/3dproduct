<?PHP
/*
 * @Description: ��Ǯ�����֧�����ؽӿڷ���
 * @Copyright (c) �Ϻ���Ǯ��Ϣ�������޹�˾
 * @version 2.0
 */


//����������˻���
///���¼��Ǯϵͳ��ȡ�û���ţ��û���ź��01��Ϊ����������˻��š�
$merchantAcctId="1000300079901";

//�����������Կ
///���ִ�Сд.�����Ǯ��ϵ��ȡ
$key="1234567897654321";

//�ַ���.�̶�ѡ��ֵ����Ϊ�ա�
///ֻ��ѡ��1��2��3.
///1����UTF-8; 2����GBK; 3����gb2312
///Ĭ��ֵΪ1
$inputCharset="3";

//����������֧������ĺ�̨��ַ.��[pageUrl]����ͬʱΪ�ա������Ǿ��Ե�ַ��
///��Ǯͨ�����������ӵķ�ʽ�����׽�����͵�[bgUrl]��Ӧ��ҳ���ַ�����̻�������ɺ������<result>���Ϊ1��ҳ���ת��<redirecturl>��Ӧ�ĵ�ַ��
///�����Ǯδ���յ�<redirecturl>��Ӧ�ĵ�ַ����Ǯ����֧�����GET��[pageUrl]��Ӧ��ҳ�档
$bgUrl="http://www.yoursite.com/receive.php";
	
//���ذ汾.�̶�ֵ
///��Ǯ����ݰ汾�������ö�Ӧ�Ľӿڴ������
///������汾�Ź̶�Ϊv2.0
$version="v2.0";

//��������.�̶�ѡ��ֵ��
///ֻ��ѡ��1��2��3
///1�������ģ�2����Ӣ��
///Ĭ��ֵΪ1
$language="1";

//ǩ������.�̶�ֵ
///1����MD5ǩ��
///��ǰ�汾�̶�Ϊ1
$signType="1";	
   
//֧��������
///��Ϊ���Ļ�Ӣ���ַ�
$payerName="payerName";

//֧������ϵ��ʽ����.�̶�ѡ��ֵ
///ֻ��ѡ��1
///1����Email
$payerContactType="1";	

//֧������ϵ��ʽ
///ֻ��ѡ��Email���ֻ���
$payerContact="";

//�̻�������
///����ĸ�����֡���[-][_]���
$orderId=date('YmdHis');		

//�������
///�Է�Ϊ��λ����������������
///�ȷ�2������0.02Ԫ
$orderAmount="2";
	
//�����ύʱ��
///14λ���֡���[4λ]��[2λ]��[2λ]ʱ[2λ]��[2λ]��[2λ]
///�磻20080101010101
$orderTime=date('YmdHis');

//��Ʒ����
///��Ϊ���Ļ�Ӣ���ַ�
$productName="productName";

//��Ʒ����
///��Ϊ�գ��ǿ�ʱ����Ϊ����
$productNum="1";

//��Ʒ����
///��Ϊ�ַ���������
$productId="";

//��Ʒ����
$productDesc="";
	
//��չ�ֶ�1
///��֧��������ԭ�����ظ��̻�
$ext1="";

//��չ�ֶ�2
///��֧��������ԭ�����ظ��̻�
$ext2="";
	
//֧����ʽ.�̶�ѡ��ֵ
///ֻ��ѡ��00��10��11��12��13��14
///00�����֧��������֧��ҳ����ʾ��Ǯ֧�ֵĸ���֧����ʽ���Ƽ�ʹ�ã�10�����п�֧��������֧��ҳ��ֻ��ʾ���п�֧����.11���绰����֧��������֧��ҳ��ֻ��ʾ�绰֧����.12����Ǯ�˻�֧��������֧��ҳ��ֻ��ʾ��Ǯ�˻�֧����.13������֧��������֧��ҳ��ֻ��ʾ����֧����ʽ��
$payType="00";

//ͬһ������ֹ�ظ��ύ��־
///�̶�ѡ��ֵ�� 1��0
///1����ͬһ������ֻ�����ύ1�Σ�0��ʾͬһ��������û��֧���ɹ���ǰ���¿��ظ��ύ��Ρ�Ĭ��Ϊ0����ʵ�ﹺ�ﳵ�������̻�����0�������Ʒ���̻�����1
$redoFlag="0";

//��Ǯ�ĺ��������˻���
///��δ�Ϳ�Ǯǩ���������Э�飬����Ҫ��д������
$pid=""; ///��������ڿ�Ǯ���û����


   
//���ɼ���ǩ����
///����ذ�������˳��͹�����ɼ��ܴ���
	$signMsgVal=appendParam($signMsgVal,"inputCharset",$inputCharset);
	$signMsgVal=appendParam($signMsgVal,"bgUrl",$bgUrl);
	$signMsgVal=appendParam($signMsgVal,"version",$version);
	$signMsgVal=appendParam($signMsgVal,"language",$language);
	$signMsgVal=appendParam($signMsgVal,"signType",$signType);
	$signMsgVal=appendParam($signMsgVal,"merchantAcctId",$merchantAcctId);
	$signMsgVal=appendParam($signMsgVal,"payerName",$payerName);
	$signMsgVal=appendParam($signMsgVal,"payerContactType",$payerContactType);
	$signMsgVal=appendParam($signMsgVal,"payerContact",$payerContact);
	$signMsgVal=appendParam($signMsgVal,"orderId",$orderId);
	$signMsgVal=appendParam($signMsgVal,"orderAmount",$orderAmount);
	$signMsgVal=appendParam($signMsgVal,"orderTime",$orderTime);
	$signMsgVal=appendParam($signMsgVal,"productName",$productName);
	$signMsgVal=appendParam($signMsgVal,"productNum",$productNum);
	$signMsgVal=appendParam($signMsgVal,"productId",$productId);
	$signMsgVal=appendParam($signMsgVal,"productDesc",$productDesc);
	$signMsgVal=appendParam($signMsgVal,"ext1",$ext1);
	$signMsgVal=appendParam($signMsgVal,"ext2",$ext2);
	$signMsgVal=appendParam($signMsgVal,"payType",$payType);	
	$signMsgVal=appendParam($signMsgVal,"redoFlag",$redoFlag);
	$signMsgVal=appendParam($signMsgVal,"pid",$pid);
	$signMsgVal=appendParam($signMsgVal,"key",$key);
$signMsg= strtoupper(md5($signMsgVal));
	
	//���ܺ�����������ֵ��Ϊ�յĲ�������ַ���
	Function appendParam($returnStr,$paramId,$paramValue){

		if($returnStr!=""){
			
				if($paramValue!=""){
					
					$returnStr.="&".$paramId."=".$paramValue;
				}
			
		}else{
		
			If($paramValue!=""){
				$returnStr=$paramId."=".$paramValue;
			}
		}
		
		return $returnStr;
	}
	//���ܺ�����������ֵ��Ϊ�յĲ�������ַ���������
	
?>

<!doctype html public "-//w3c//dtd html 4.0 transitional//en" >
<html>
	<head>
		<title>ʹ�ÿ�Ǯ֧��</title>
		<meta http-equiv="content-type" content="text/html; charset=gb2312" >
	</head>
	
<BODY>
	
	<div align="center">
		<table width="259" border="0" cellpadding="1" cellspacing="1" bgcolor="#CCCCCC" >
			<tr bgcolor="#FFFFFF">
				<td width="80">֧����ʽ:</td>
				<td >��Ǯ[99bill]</td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td >�������:</td>
				<td ><?php echo $orderId; ?></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>�������:</td>
				<td><?php echo $orderAmount; ?></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>֧����:</td>
				<td><?php echo $payerName; ?></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td>��Ʒ����:</td>
				<td><?php echo $productName; ?></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
			</tr>
	  </table>
	</div>

	<div align="center" style="font-size=12px;font-weight: bold;color=red;">
		<form name="kqPay" method="post" action="https://www.99bill.com/gateway/recvMerchantInfoAction.htm">
			<input type="hidden" name="inputCharset" value="<?php echo $inputCharset; ?>"/>
			<input type="hidden" name="bgUrl" value="<?php echo $bgUrl; ?>"/>
			<input type="hidden" name="version" value="<?php echo $version; ?>"/>
			<input type="hidden" name="language" value="<?php echo $language; ?>"/>
			<input type="hidden" name="signType" value="<?php echo $signType; ?>"/>
			<input type="hidden" name="signMsg" value="<?php echo $signMsg; ?>"/>
			<input type="hidden" name="merchantAcctId" value="<?php echo $merchantAcctId; ?>"/>
			<input type="hidden" name="payerName" value="<?php echo $payerName; ?>"/>
			<input type="hidden" name="payerContactType" value="<?php echo $payerContactType; ?>"/>
			<input type="hidden" name="payerContact" value="<?php echo $payerContact; ?>"/>
			<input type="hidden" name="orderId" value="<?php echo $orderId; ?>"/>
			<input type="hidden" name="orderAmount" value="<?php echo $orderAmount; ?>"/>
			<input type="hidden" name="orderTime" value="<?php echo $orderTime; ?>"/>
			<input type="hidden" name="productName" value="<?php echo $productName; ?>"/>
			<input type="hidden" name="productNum" value="<?php echo $productNum; ?>"/>
			<input type="hidden" name="productId" value="<?php echo $productId; ?>"/>
			<input type="hidden" name="productDesc" value="<?php echo $productDesc; ?>"/>
			<input type="hidden" name="ext1" value="<?php echo $ext1; ?>"/>
			<input type="hidden" name="ext2" value="<?php echo $ext2; ?>"/>
			<input type="hidden" name="payType" value="<?php echo $payType; ?>"/>
			<input type="hidden" name="redoFlag" value="<?php echo $redoFlag; ?>"/>
			<input type="hidden" name="pid" value="<?php echo $pid; ?>"/>
			<input type="submit" name="submit" value="�ύ����Ǯ">
			
		</form>		
	</div>
	
</BODY>
</HTML>