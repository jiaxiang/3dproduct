<?php
/*
 *������alipay_notify
 *���ܣ���������з�����֪ͨ��
 *��ϸ����ҳ����֪ͨ���غ��Ĵ����ļ�������Ҫ�޸�
 *�汾��3.1
 *�޸����ڣ�2010-11-23
 '˵����
 '���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 '�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���
*/

////////////////////ע��/////////////////////////
//����֪ͨ����ʱ���ɲ鿴���дlog��־��д��TXT������ݣ������֪ͨ�����Ƿ�����
/////////////////////////////////////////////////

require_once("alipay_function.php");

class alipay_notify {
    var $gateway;           //���ص�ַ
    var $_key;  			//��ȫУ����
    var $partner;           //�������ID
    var $sign_type;         //ǩ����ʽ ϵͳĬ��
    var $mysign;            //ǩ�����
    var $_input_charset;    //�ַ������ʽ
    var $transport;         //����ģʽ

    /**���캯��
	*�������ļ��г�ʼ������
	*$partner ���������ID
	*$key ��ȫУ����
	*$sign_type ǩ������
	*$_input_charset �ַ������ʽ
	*$transport ����ģʽ
     */
    function alipay_notify($partner,$key,$sign_type,$_input_charset = "GBK",$transport= "https") {

        $this->transport = $transport;
        if($this->transport == "https") {
            $this->gateway = "https://www.alipay.com/cooperate/gateway.do?";
        }else {
            $this->gateway = "http://notify.alipay.com/trade/notify_query.do?";
        }
        $this->partner          = $partner;
        $this->_key    			= $key;
        $this->mysign           = "";
        $this->sign_type	    = $sign_type;
        $this->_input_charset   = $_input_charset;
    }

    /********************************************************************************/

    /**��notify_url����֤
	*���ص���֤�����true/false
     */
    function notify_verify() {
        //��ȡԶ�̷�����ATN�������֤�Ƿ���֧��������������������
        if($this->transport == "https") {
            $veryfy_url = $this->gateway. "service=notify_verify" ."&partner=" .$this->partner. "&notify_id=".$_POST["notify_id"];
        } else {
            $veryfy_url = $this->gateway. "partner=".$this->partner."&notify_id=".$_POST["notify_id"];
        }
        $veryfy_result = $this->get_verify($veryfy_url);

        //����ǩ�����
		if(empty($_POST)) {							//�ж�POST���������Ƿ�Ϊ��
			return false;
		}
		else {
			$post          = para_filter($_POST);	//������POST���صĲ���ȥ��
			$sort_post     = arg_sort($post);	    //������POST������������������
			$this->mysign  = build_mysign($sort_post,$this->_key,$this->sign_type);   //����ǩ�����
	
			//д��־��¼
			log_result("veryfy_result=".$veryfy_result."\n notify_url_log:sign=".$_POST["sign"]."&mysign=".$this->mysign.",".create_linkstring($sort_post));
	
			//�ж�veryfy_result�Ƿ�Ϊture�����ɵ�ǩ�����mysign���õ�ǩ�����sign�Ƿ�һ��
			//$veryfy_result�Ľ������true����������������⡢���������ID��notify_idһ����ʧЧ�й�
			//mysign��sign���ȣ��밲ȫУ���롢����ʱ�Ĳ�����ʽ���磺���Զ�������ȣ��������ʽ�й�
			if (preg_match("/true$/i",$veryfy_result) && $this->mysign == $_POST["sign"]) {
				return true;
			} else {
				return false;
			}
		}
    }

    /********************************************************************************/

    /**��return_url����֤
	*return ��֤�����true/false
     */
    function return_verify() {
        //��ȡԶ�̷�����ATN�������֤�Ƿ���֧��������������������
        if($this->transport == "https") {
            $veryfy_url = $this->gateway. "service=notify_verify" ."&partner=" .$this->partner. "&notify_id=".$_GET["notify_id"];
        } else {
            $veryfy_url = $this->gateway. "partner=".$this->partner."&notify_id=".$_GET["notify_id"];
        }
        $veryfy_result = $this->get_verify($veryfy_url);

        //����ǩ�����
		if(empty($_GET)) {							//�ж�GET���������Ƿ�Ϊ��
			return false;
		}
		else {
			$get          = para_filter($_GET);	    //������GET��������������ȥ��
			$sort_get     = arg_sort($get);		    //������GET������������������
			$this->mysign  = build_mysign($sort_get,$this->_key,$this->sign_type);    //����ǩ�����
	
			//д��־��¼
			//log_result("veryfy_result=".$veryfy_result."\n return_url_log:sign=".$_GET["sign"]."&mysign=".$this->mysign."&".create_linkstring($sort_get));
	
			//�ж�veryfy_result�Ƿ�Ϊture�����ɵ�ǩ�����mysign���õ�ǩ�����sign�Ƿ�һ��
			//$veryfy_result�Ľ������true����������������⡢���������ID��notify_idһ����ʧЧ�й�
			//mysign��sign���ȣ��밲ȫУ���롢����ʱ�Ĳ�����ʽ���磺���Զ�������ȣ��������ʽ�й�
			if (preg_match("/true$/i",$veryfy_result) && $this->mysign == $_GET["sign"]) {            
				return true;
			}else {
				return false;
			}
		}
    }

    /********************************************************************************/

    /**��ȡԶ�̷�����ATN���
	*$url ָ��URL·����ַ
	*return ������ATN�����
     */
    function get_verify($url,$time_out = "60") {
        $urlarr     = parse_url($url);
        $errno      = "";
        $errstr     = "";
        $transports = "";
        if($urlarr["scheme"] == "https") {
            $transports = "ssl://";
            $urlarr["port"] = "443";
        } else {
            $transports = "tcp://";
            $urlarr["port"] = "80";
        }
        $fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
        if(!$fp) {
            die("ERROR: $errno - $errstr<br />\n");
        } else {
            fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
            fputs($fp, "Host: ".$urlarr["host"]."\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $urlarr["query"] . "\r\n\r\n");
            while(!feof($fp)) {
                $info[]=@fgets($fp, 1024);
            }
            fclose($fp);
            $info = implode(",",$info);
            return $info;
        }
    }

    /********************************************************************************/

}
?>
