<?php
/*
 * @Description: ��Ǯ�����֧�����ؽӿڷ���
 * @Copyright (c) �Ϻ���Ǯ��Ϣ�������޹�˾
 * @version 2.0
 */

//��ȡ����������˻���
$merchantAcctId=trim($_REQUEST['merchantAcctId']);

//���������������Կ
///���ִ�Сд
$key="1234567897654321";

//��ȡ���ذ汾.�̶�ֵ
///��Ǯ����ݰ汾�������ö�Ӧ�Ľӿڴ������
///������汾�Ź̶�Ϊv2.0
$version=trim($_REQUEST['version']);

//��ȡ��������.�̶�ѡ��ֵ��
///ֻ��ѡ��1��2��3
///1�������ģ�2����Ӣ��
///Ĭ��ֵΪ1
$language=trim($_REQUEST['language']);

//ǩ������.�̶�ֵ
///1����MD5ǩ��
///��ǰ�汾�̶�Ϊ1
$signType=trim($_REQUEST['signType']);

//��ȡ֧����ʽ
///ֵΪ��10��11��12��13��14
///00�����֧��������֧��ҳ����ʾ��Ǯ֧�ֵĸ���֧����ʽ���Ƽ�ʹ�ã�10�����п�֧��������֧��ҳ��ֻ��ʾ���п�֧����.11���绰����֧��������֧��ҳ��ֻ��ʾ�绰֧����.12����Ǯ�˻�֧��������֧��ҳ��ֻ��ʾ��Ǯ�˻�֧����.13������֧��������֧��ҳ��ֻ��ʾ����֧����ʽ��.14��B2B֧��������֧��ҳ��ֻ��ʾB2B֧��������Ҫ���Ǯ���뿪ͨ����ʹ�ã�
$payType=trim($_REQUEST['payType']);

//��ȡ���д���
///�μ����д����б�
$bankId=trim($_REQUEST['bankId']);

//��ȡ�̻�������
$orderId=trim($_REQUEST['orderId']);

//��ȡ�����ύʱ��
///��ȡ�̻��ύ����ʱ��ʱ��.14λ���֡���[4λ]��[2λ]��[2λ]ʱ[2λ]��[2λ]��[2λ]
///�磺20080101010101
$orderTime=trim($_REQUEST['orderTime']);

//��ȡԭʼ�������
///�����ύ����Ǯʱ�Ľ���λΪ�֡�
///�ȷ�2 ������0.02Ԫ
$orderAmount=trim($_REQUEST['orderAmount']);

//��ȡ��Ǯ���׺�
///��ȡ�ý����ڿ�Ǯ�Ľ��׺�
$dealId=trim($_REQUEST['dealId']);

//��ȡ���н��׺�
///���ʹ�����п�֧��ʱ�������еĽ��׺š��粻��ͨ������֧������Ϊ��
$bankDealId=trim($_REQUEST['bankDealId']);

//��ȡ�ڿ�Ǯ����ʱ��
///14λ���֡���[4λ]��[2λ]��[2λ]ʱ[2λ]��[2λ]��[2λ]
///�磻20080101010101
$dealTime=trim($_REQUEST['dealTime']);

//��ȡʵ��֧�����
///��λΪ��
///�ȷ� 2 ������0.02Ԫ
$payAmount=trim($_REQUEST['payAmount']);

//��ȡ����������
///��λΪ��
///�ȷ� 2 ������0.02Ԫ
$fee=trim($_REQUEST['fee']);

//��ȡ��չ�ֶ�1
$ext1=trim($_REQUEST['ext1']);

//��ȡ��չ�ֶ�2
$ext2=trim($_REQUEST['ext2']);

//��ȡ������
///10���� �ɹ�; 11���� ʧ��
$payResult=trim($_REQUEST['payResult']);

//��ȡ�������
///��ϸ���ĵ���������б�
$errCode=trim($_REQUEST['errCode']);

//��ȡ����ǩ����
$signMsg=trim($_REQUEST['signMsg']);



//���ɼ��ܴ������뱣������˳��
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"merchantAcctId",$merchantAcctId);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"version",$version);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"language",$language);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"signType",$signType);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"payType",$payType);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"bankId",$bankId);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"orderId",$orderId);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"orderTime",$orderTime);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"orderAmount",$orderAmount);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"dealId",$dealId);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"bankDealId",$bankDealId);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"dealTime",$dealTime);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"payAmount",$payAmount);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"fee",$fee);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"ext1",$ext1);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"ext2",$ext2);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"payResult",$payResult);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"errCode",$errCode);
	$merchantSignMsgVal=appendParam($merchantSignMsgVal,"key",$key);
$merchantSignMsg= md5($merchantSignMsgVal);


//��ʼ���������ַ
$rtnOk=0;
$rtnUrl="";

//�̼ҽ������ݴ�������ת���̼���ʾ֧�������ҳ��
///���Ƚ���ǩ���ַ�����֤
if(strtoupper($signMsg)==strtoupper($merchantSignMsg)){

	switch($payResult){
		  
		  case "10":
			
			/* 
			' �̻���վ�߼������ȷ����¶���֧��״̬Ϊ�ɹ�
			' �ر�ע�⣺ֻ��strtoupper($signMsg)==strtoupper($merchantSignMsg)����payResult=10���ű�ʾ֧���ɹ���ͬʱ������������ύ����ǰ�Ķ��������жԱ�У�顣
			*/
			
			//�������Ǯ�����������ṩ��Ҫ�ض���ĵ�ַ��
			$rtnOk=1;
			$rtnUrl="http://www.yoursite.com/show.php?msg=success!";
			
			break;
		  
		  default:

			$rtnOk=1;
			$rtnUrl="http://www.yoursite.com/show.php?msg=false!";

			break;
	}

}Else{

	$rtnOk=1;
	$rtnUrl="http://www.yoursite.com/show.php?msg=error!";

} 





	//���ܺ�����������ֵ��Ϊ�ղ�������ַ���
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
	//���ܺ�����������ֵ��Ϊ�ղ�������ַ���������


//���±������Ǯ�����������ṩ��Ҫ�ض���ĵ�ַ
?>
<result><?php echo $rtnOk; ?></result><redirecturl><?php echo $rtnUrl; ?></redirecturl>