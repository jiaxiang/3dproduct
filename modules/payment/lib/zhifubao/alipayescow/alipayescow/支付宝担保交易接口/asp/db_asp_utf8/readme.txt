            �q�����������������������������������������������r
  �q����������           ֧��������ʾ���ṹ˵��             �����������r
  ��        �t�����������������������������������������������s        ��
����                                                                  ��
����     �ӿ����ƣ�֧�����������׽ӿڣ�create_partner_trade_by_buyer����
������   ����汾��3.1                                                ��
  ��     �������ԣ�ASP                                                ��
  ��     ��    Ȩ��֧�������й������缼�����޹�˾                     ��
����     �� �� �ߣ�֧�����̻���ҵ������֧����                         ��
  ��     ��ϵ��ʽ���̻�����绰0571-88158090                          ��
  ��                                                                  ��
  �t�������������������������������������������������������������������s

��������������
 �����ļ��ṹ
��������������

db_asp_utf8
  ��
  ��class���������������������������ļ���
  ��  ��
  ��  ��alipay_function.asp���������ú������ļ�
  ��  ��
  ��  ��alipay_md5.asp ����������MD5���ļ�
  ��  ��
  ��  ��alipay_notify.asp��������֧����֪ͨ�������ļ�
  ��  ��
  ��  ��alipay_service.asp ������֧�������������ļ�
  ��
  ��images ����������������������ͼƬ��CSS��ʽ�ļ���
  ��
  ��log����������������������������־�ļ���
  ��
  ��alipay_config.asp������������������Ϣ�����ļ�
  ��
  ��alipayto.asp ����������������֧�����ӿ�����ļ�
  ��
  ��index.asp�����������������������ٸ������ģ���ļ�
  ��
  ��notify_url.asp ���������������������첽֪ͨҳ���ļ�
  ��
  ��return_url.asp ��������������ҳ����תͬ��֪ͨ�ļ�
  ��
  ��readme.txt ������������������ʹ��˵���ı�

��ע���
��Ҫ���õ��ļ��ǣ�alipay_config.asp��alipayto.asp

index.asp����֧�����ṩ�ĸ������ģ���ļ�����ѡ��ʹ�á�
����̻���վ����ҵ��������Ҫʹ�ã����alipayto.asp��Ϊ���̻���վ��վ���ν�ҳ�档
�����Ҫʹ��index.asp����ôalipayto.asp�ļ�������ģ�ֻ�����ú�alipay_config.asp�ļ�
�õ�index.aspҳ�����̻���վ�е�HTTP·���������̻���վ����Ҫ��λ�ã�����ֱ��ʹ��֧�����ӿڡ�


������������������
 ���ļ������ṹ
������������������

alipay_function.asp

function build_mysign(sArray, key, sign_type,input_charset)
���ܣ�����ǩ�����
���룺Array  sArray Ҫǩ��������
      String key ��ȫУ����
      String sign_type ǩ������
�����String ǩ������ַ���

function create_linkstring(sArray)
���ܣ�����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
���룺Array  sArray ��Ҫƴ�ӵ�����
�����String ƴ������Ժ���ַ���

function para_filter(sArray)
���ܣ���ȥ�����еĿ�ֵ��ǩ������
���룺Array  sArray ǩ��������
�����Array  ȥ����ֵ��ǩ�����������ǩ��������

function arg_sort(sArray)
���ܣ�����������
���룺Array  sArray ����ǰ������
�����Array  ����������

function sign(prestr,sign_type,input_charset)
���ܣ�ǩ���ַ���
���룺String prestr ��Ҫǩ�����ַ���
      String sign_type ǩ������
      String input_charset �����ʽ
�����String ǩ�����

function query_timestamp(partner)
���ܣ����ڷ����㣬���ýӿ�query_timestamp����ȡʱ����Ĵ�����
���룺String partner ���������ID
�����String ʱ����ַ���
���ܣ����ã�Ŀǰ����

function log_result(sWord)
���ܣ�д��־��������ԣ�����վ����Ҳ���Ըĳɴ������ݿ⣩
���룺String sWord Ҫд����־����ı�����

function GetDateTime_Format()
���ܣ���ȡ��ǰʱ��
��ʽ����[4λ]-��[2λ]-��[2λ] Сʱ[2λ 24Сʱ��]:��[2λ]:��[2λ]���磺2007-10-01 13:13:13
�����String ��ǰ����
˵��������

function GetDateTime()
���ܣ���ȡ��ǰʱ��
��ʽ����[4λ]��[2λ]��[2λ]Сʱ[2λ 24Сʱ��]��[2λ]��[2λ]���磺20071001131313
�����String ��ǰ����

Function DelStr(Str)
���ܣ����������ַ�
���룺String Str Ҫ�����˵��ַ���
�����String �ѱ����˵������ַ���

��������������������������������������������������������������

alipay_md5.asp

Public Function MD5(sMessage,input_charset)
���ܣ�MD5ǩ��
���룺String sMessage Ҫǩ�����ַ���
      String input_charset �����ʽ��utf-8��gbk
�����String ǩ�����

��������������������������������������������������������������

alipay_notify.asp

function notify_verify()
���ܣ���notify_url����֤
�����Bool  ��֤�����true/false

function return_verify()
���ܣ���return_url����֤
�����Bool  ��֤�����true/false

function GetRequestGet()
���ܣ���ȡ֧����GET����֪ͨ��Ϣ�����ԡ�������=����ֵ������ʽ�������
�����Array  request��������Ϣ��ɵ�����

function GetRequestPost()
���ܣ���ȡ֧����POST����֪ͨ��Ϣ�����ԡ�������=����ֵ������ʽ�������
�����Array  request��������Ϣ��ɵ�����

function get_http()
���ܣ���ȡԶ�̷�����ATN���
�����String ������ATN����ַ���

��������������������������������������������������������������

alipay_service.asp

function alipay_service(inputPara)
���ܣ����캯��
      �������ļ�������ļ��г�ʼ������
���룺Array  inputPara ��Ҫǩ���Ĳ�������

function build_form()
���ܣ�������ύHTML
�����String ���ύHTML�ı�


��������������������
 �������⣬��������
��������������������

����ڼ���֧�����ӿ�ʱ�������ʻ�������⣬��ʹ����������ӣ��ύ���롣
https://b.alipay.com/support/helperApply.htm?action=supportHome
���ǻ���ר�ŵļ���֧����ԱΪ������




