            �q�����������������������������������������������r
  �q����������           ֧��������ʾ���ṹ˵��             �����������r
  ��        �t�����������������������������������������������s        ��
����                                                                  ��
����     �ӿ����ƣ�֧������Աͨ�õ�¼�ӿڣ�user_authentication��      ��
������   ����汾��3.1                                                ��
  ��     �������ԣ�JAVA                                               ��
  ��     ��    Ȩ��֧�������й������缼�����޹�˾                     ��
����     �� �� �ߣ�֧�����̻���ҵ������֧����                         ��
  ��     ��ϵ��ʽ���̻�����绰0571-88158090                          ��
  ��                                                                  ��
  �t�������������������������������������������������������������������s

��������������
 �����ļ��ṹ
��������������

user_jsp_gb
  ��
  ��src�����������������������������������ļ���
  ��  ��
  ��  ��com.alipay.config
  ��  ��  ��
  ��  ��  ��AlipayConfig.java����������������Ϣ�����������ļ�
  ��  ��
  ��  ��com.alipay.util
  ��  ��  ��
  ��  ��  ��AlipayFunction.java�����������ú������ļ�
  ��  ��  ��
  ��  ��  ��AlipayNotify.java����������֧����֪ͨ�������ļ�
  ��  ��  ��
  ��  ��  ��AlipayService.java ��������֧�������������ļ�
  ��  ��  ��
  ��  ��  ��Md5Encrypt.java������������MD5ǩ�����ļ�
  ��  ��  ��
  ��  ��  ��UtilDate.java���������������Զ��嶩�����ļ�
  ��  ��
  ��  ��filters�������������������������������ļ��У�����ʱɾ����
  ��
  ��WebRoot����������������������������ҳ���ļ���
  ��  ��
  ��  ��images ������������������������ͼƬ��CSS��ʽ�ļ���
  ��  ��
  ��  ��index.jsp�������������������������ٸ������ģ���ļ�
  ��  ��
  ��  ��return_url.jsp ����������������ҳ����תͬ��֪ͨ�ļ�
  ��
  ��readme.txt ������������������ʹ��˵���ı�

��ע���
��Ҫ���õ��ļ��ǣ�alipay_config.jsp��index.jsp��return_url.jsp
���ð���com.alipay.config.*��com.alipay.util.*

public static void LogResult(String sWord)
��������Ҫ������־�ļ�����ʱ���ڵ����ϵľ���·����



������������������
 ���ļ������ṹ
������������������

alipay_function.java

public static String BuildMysign(Map sArray, String key)
���ܣ�����ǩ�����
���룺Map    sArray Ҫǩ��������
      String key ��ȫУ����
�����String ǩ������ַ���

public static Map ParaFilter(Map sArray)
���ܣ���ȥ�����еĿ�ֵ��ǩ������
���룺Map    sArray Ҫǩ��������
�����Map    ȥ����ֵ��ǩ�����������ǩ��������

public static String CreateLinkString(Map params)
���ܣ�����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
���룺Map    params ��Ҫƴ�ӵ�����
�����String ƴ������Ժ���ַ���

public static String query_timestamp(String partner)
���ܣ����ڷ����㣬���ýӿ�query_timestamp����ȡʱ����Ĵ�����
���룺String partner ���������ID
�����String ʱ����ַ���
���ܣ����ã�Ŀǰ����

public static void LogResult(String sWord)
���ܣ�д��־��������ԣ�����վ����Ҳ���Ըĳɴ������ݿ⣩
���룺String sWord Ҫд����־����ı�����
˵�����ú�������Ҫ������־���ڵ����ϵľ���·��

��������������������������������������������������������������

Md5Encrypt.java

public static String md5(String text)
���ܣ�MD5ǩ��
���룺String sMessage Ҫǩ�����ַ���
�����String ǩ�����

��������������������������������������������������������������

alipay_notify.java

public static String GetMysign(Map Params, String key)
���ܣ����ݷ�����������Ϣ������ǩ�����
���룺Map    Params ֪ͨ�������Ĳ�������
      String key ��ȫУ����
�����String ǩ�����

public static String Verify(String notify_id)
���ܣ���ȡԶ�̷�����ATN���,��֤����URL
���룺String notify_id ��֤֪ͨID
�����String ��֤���

public static String CheckUrl(String urlvalue)
���ܣ���ȡԶ�̷�����ATN���
���룺String urlvalue ָ��URL·����ַ
�����String ������ATN����ַ���

��������������������������������������������������������������

alipay_service.java

public static String BuildForm(String partner,
	String return_url,
	String email,
        String input_charset,
        String key,
        String sign_type)
���ܣ�������ύHTML
���룺String partner ���������ID
      String return_url ��¼����ת��ҳ�� ��http��ͷ��ʽ������·�����������?id=123�����Զ������
      String email ֧������Ա��¼�˺�
      String key ��ȫ������
      String input_charset �ַ������ʽ Ŀǰ֧�� gbk �� utf-8
      String sign_type ǩ����ʽ �����޸�
�����String ���ύHTML�ı�

��������������������������������������������������������������

UtilDate.java

public  static String getOrderNum()
���ܣ��Զ����������ţ���ʽyyyyMMddHHmmss
�����String ������

public  static String getDateFormatter()
���ܣ���ȡ���ڣ���ʽ��yyyy-MM-dd HH:mm:ss
�����String ����

public static String getDate()
���ܣ���ȡ���ڣ���ʽ��yyyyMMdd
�����String ����

public static String getThree()
���ܣ������������λ��
�����String �����λ��

��������������������
 �������⣬��������
��������������������

����ڼ���֧�����ӿ�ʱ�������ʻ�������⣬��ʹ����������ӣ��ύ���롣
https://b.alipay.com/support/helperApply.htm?action=supportHome
���ǻ���ר�ŵļ���֧����ԱΪ������




