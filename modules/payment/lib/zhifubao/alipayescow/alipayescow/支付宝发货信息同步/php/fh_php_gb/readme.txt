                  �q�����������������������������������������������r
  �q����������������           ֧��������ʾ���ṹ˵��             �����������������r
  ��              �t�����������������������������������������������s              ��
����                                                                              ��
����     �ӿ����ƣ�֧���������ӿڣ�send_goods_confirm_by_platform��               ��
������   ����汾��3.1                                                            ��
  ��     �������ԣ�PHP                                                            ��
  ��     ��    Ȩ��֧�������й������缼�����޹�˾                                 ��
����     �� �� �ߣ�֧�����̻���ҵ������֧����                                     ��
  ��     ��ϵ��ʽ���̻�����绰0571-88158090                                      ��
  ��                                                                              ��
  �t�������������������������������������������������������������������������������s

��������������
 �����ļ��ṹ
��������������

fh_php_gb
  ��
  ��class�����������������������������������������ļ���
  ��  ��
  ��  ��alipay_function.php�����������������������ú������ļ�
  ��  ��
  ��  ��alipay_notify.php����������������������֧����֪ͨ�������ļ������ã�
  ��  ��
  ��  ��alipay_service.php ��������������������֧�������������ļ�
  ��
  ��log.txt��������������������������������������־�ļ�
  ��
  ��alipay_config.php��������������������������������Ϣ�����ļ�
  ��
  ��sendgoods.php������������������������������֧�����ӿ�����ļ�
  ��
  ��index.php��������������������������������������ģ���ļ�
  ��
  ��readme.txt ��������������������������������ʹ��˵���ı�

��ע���
��Ҫ���õ��ļ��ǣ�alipay_config.php��sendgoods.php

index.php����֧�����ṩ�ĸ������ģ���ļ�����ѡ��ʹ�á�
����̻���վ����ҵ��������Ҫʹ�ã����sendgoods.php��Ϊ���̻���վ��վ���ν�ҳ�档
�����Ҫʹ��index.php����ôsendgoods.php�ļ�������ģ�ֻ�����ú�alipay_config.php�ļ�
�õ�index.phpҳ�����̻���վ�е�HTTP·���������̻���վ����Ҫ��λ�ã�����ֱ��ʹ��֧�����ӿڡ�


������������������
 ���ļ������ṹ
������������������

alipay_function.php

function build_mysign($sort_array,$key,$sign_type = "MD5")
���ܣ�����ǩ�����
���룺Array  $sort_array Ҫǩ��������
      String $key ��ȫУ����
      String $sign_type ǩ������ Ĭ��ֵ MD5
�����String ǩ������ַ���

function create_linkstring($array)
���ܣ�����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
���룺Array  $array ��Ҫƴ�ӵ�����
�����String ƴ������Ժ���ַ���

function para_filter($parameter)
���ܣ���ȥ�����еĿ�ֵ��ǩ������
���룺Array  $parameter ǩ��������
�����Array  ȥ����ֵ��ǩ�����������ǩ��������

function arg_sort($array)
���ܣ�����������
���룺Array  $array ����ǰ������
�����Array  ����������

function sign($prestr,$sign_type)
���ܣ�ǩ���ַ���
���룺String $prestr ��Ҫǩ�����ַ���
      String $sign_type ǩ������
�����String ǩ�����

function log_result($word)
���ܣ�д��־��������ԣ�����վ����Ҳ���Ըĳɴ������ݿ⣩
���룺String $word Ҫд����־����ı�����

function query_timestamp($partner) 
���ܣ����ڷ����㣬���ýӿ�query_timestamp����ȡʱ����Ĵ�����
���룺String $partner ���������ID
�����String ʱ����ַ���
˵�������ã�Ŀǰ����

function charset_encode($input,$_output_charset ,$_input_charset)
���ܣ�ʵ�ֶ����ַ����뷽ʽ
���룺String $input ��Ҫ������ַ���
      String $_output_charset ����ı����ʽ
      String $_input_charset ����ı����ʽ
�����String �������ַ���

function charset_decode($input,$_input_charset ,$_output_charset) 
���ܣ�ʵ�ֶ����ַ����뷽ʽ
���룺String $input ��Ҫ������ַ���
      String $_output_charset ����Ľ����ʽ
      String $_input_charset ����Ľ����ʽ
�����String �������ַ���

��������������������������������������������������������������

alipay_notify.php

function alipay_notify($partner,$key,$sign_type,$_input_charset = "GBK",$transport= "https") 
���ܣ����캯��
      �������ļ��г�ʼ������
���룺String $partner ���������ID
      String $key ��ȫУ����
      String $sign_type ǩ������
      String $_input_charset �ַ������ʽ Ĭ��ֵ GBK
      String $transport ����ģʽ Ĭ��ֵ https

function notify_verify()
���ܣ���notify_url����֤
�����Bool  ��֤�����true/false

function return_verify()
���ܣ���return_url����֤
�����Bool  ��֤�����true/false

function get_verify($url,$time_out = "60")
���ܣ���ȡԶ�̷�����ATN���
���룺String $url ָ��URL·����ַ
      String $time_out ��ʱ��ʱ�� Ĭ��ֵ60
�����String ������ATN����ַ���

��������������������������������������������������������������

alipay_service.php

function alipay_service($parameter,$key,$sign_type)
���ܣ����캯��
      �������ļ�������ļ��г�ʼ������
���룺Array  $parameter ��Ҫǩ���Ĳ�������
      Array  $key ��ȫУ����
      Array  $sign_type ǩ������

function build_form()
���ܣ�������ύHTML
�����String ���ύHTML�ı�

function create_linkstring_urlencode($array)
���ܣ�����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
���룺Array  $array ��Ҫƴ�ӵ�����
�����String ƴ������Ժ���ַ���

function create_url()
���ܣ���������URL
�����String ����url


��������������������
 �������⣬��������
��������������������

����ڼ���֧�����ӿ�ʱ�������ʻ�������⣬��ʹ����������ӣ��ύ���롣
https://b.alipay.com/support/helperApply.htm?action=supportHome
���ǻ���ר�ŵļ���֧����ԱΪ������




