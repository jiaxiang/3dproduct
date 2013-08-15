<?php
/**
 *������alipay_service
 *���ܣ�֧�����ⲿ����ӿڿ���
 *��ϸ����ҳ��������������Ĵ����ļ�������Ҫ�޸�
 *�汾��3.1
 *�޸����ڣ�2010-11-23
 '˵����
 '���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 '�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���

*/

require_once("alipay_function.php");

class alipay_service {

    var $gateway;			//���ص�ַ
    var $_key;				//��ȫУ����
    var $mysign;			//ǩ�����
    var $sign_type;			//ǩ������
    var $parameter;			//��Ҫǩ���Ĳ�������
    var $_input_charset;    //�ַ������ʽ

    /**���캯��
	*�������ļ�������ļ��г�ʼ������
	*$parameter ��Ҫǩ���Ĳ�������
	*$key ��ȫУ����
	*$sign_type ǩ������
    */
    function alipay_service($parameter,$key,$sign_type) {
        $this->gateway		= "https://www.alipay.com/cooperate/gateway.do?";
        $this->_key  		= $key;
        $this->sign_type	= $sign_type;
        $preParameter		= para_filter($parameter);
		
        //�趨_input_charset��ֵ,Ϊ��ֵ�������Ĭ��ΪGBK
        if($parameter['_input_charset'] == '')
            $this->parameter['_input_charset'] = 'GBK';

        $this->_input_charset   = $this->parameter['_input_charset'];

        //���ǩ�����
        $this->parameter = arg_sort($preParameter);    //�õ�����ĸa��z������ǩ����������
        $this->mysign = build_mysign($this->parameter,$this->_key,$this->sign_type);
    }

    /********************************************************************************/

    /**������ύHTML
	*return ���ύHTML�ı�
     */
    function build_form() {
		//GET��ʽ����
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->gateway."_input_charset=".$this->parameter['_input_charset']."' method='get'>";
		//POST��ʽ���ݣ�GET��POST����ѡһ��
		//$sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->gateway."_input_charset=".$this->parameter['_input_charset']."' method='post'>";

        while (list ($key, $val) = each ($this->parameter)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }

        $sHtml = $sHtml."<input type='hidden' name='sign' value='".$this->mysign."'/>";
        $sHtml = $sHtml."<input type='hidden' name='sign_type' value='".$this->sign_type."'/>";

		//submit��ť�ؼ��벻Ҫ����name����
        $sHtml = $sHtml."<input type='submit' value='֧����ȷ�ϸ���'></form>";
		
		$sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
        return $sHtml;
    }
    /********************************************************************************/

}
?>