                  �q�����������������������������������������������r
  �q����������������           ֧��������ʾ���ṹ˵��             �����������������r
  ��              �t�����������������������������������������������s              ��
����                                                                              ��
����     �ӿ����ƣ�֧���������ӿڣ�send_goods_confirm_by_platform��               ��
������   ����汾��3.1                                                            ��
  ��     �������ԣ�JAVA                                                           ��
  ��     ��    Ȩ��֧�������й������缼�����޹�˾                                 ��
����     �� �� �ߣ�֧�����̻���ҵ������֧����                                     ��
  ��     ��ϵ��ʽ���̻�����绰0571-88158090                                      ��
  ��                                                                              ��
  �t�������������������������������������������������������������������������������s

��������������
 �����ļ��ṹ
��������������

fh_jsp_utf8
  ��
  ��src�����������������������������������������ļ���
  ��  ��
  ��  ��com.alipay.config
  ��  ��  ��
  ��  ��  ��AlipayConfig.java����������������������Ϣ�����������ļ�
  ��  ��
  ��  ��com.alipay.util
  ��  ��  ��
  ��  ��  ��AlipayFunction.java�����������������ú������ļ�
  ��  ��  ��
  ��  ��  ��AlipayNotify.java����������������֧����֪ͨ�������ļ������ã�
  ��  ��  ��
  ��  ��  ��AlipayService.java ��������������֧�������������ļ�
  ��  ��  ��
  ��  ��  ��Md5Encrypt.java������������������MD5ǩ�����ļ�
  ��  ��  ��
  ��  ��  ��UtilDate.java���������������������Զ��嶩�����ļ������ã�
  ��  ��
  ��  ��filters�������������������������������������ļ��У�����ʱɾ����
  ��
  ��WebRoot����������������������������������ҳ���ļ���
  ��  ��
  ��  ��sendgoods.jsp������������������������֧�����ӿ�����ļ�
  ��  ��
  ��  ��index.jsp��������������������������������ģ���ļ�
  ��
  ��readme.txt ������������������������������ʹ��˵���ı�

��ע���
��Ҫ���õ��ļ��ǣ�alipay_config.jsp��sendgoods.jsp
���ð���com.alipay.config.*��com.alipay.util.*

index.jsp����֧�����ṩ�ĸ������ģ���ļ�����ѡ��ʹ�á�
����̻���վ����ҵ��������Ҫʹ�ã����sendgoods.jsp��Ϊ���̻���վ��վ���ν�ҳ�档
�����Ҫʹ��index.jsp����ôsendgoods.jsp�ļ�������ģ�ֻ�����ú�alipay_config.java�ļ�
�õ�index.jspҳ�����̻���վ�е�HTTP·���������̻���վ����Ҫ��λ�ã�����ֱ��ʹ��֧�����ӿڡ�

public static void LogResult(String sWord)
��������Ҫ������־�ļ�����ʱ���ڵ����ϵľ���·����



������������������
 ���ļ������ṹ
������������������

AlipayFunction.java

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

AlipayNotify.java

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

AlipayService.java

public static String BuildForm(String partner,
	String trade_no,
	String logistics_name,
	String invoice_no,
	String transport_type,
	String seller_ip,
        String input_charset,
        String key,
        String sign_type)
���ܣ�������ύHTML
���룺String partner ���������ID
      String trade_no ֧�������׺š����ǵ�½֧������վ�ڽ��׹����в�ѯ�õ���һ����8λ���ڿ�ͷ�Ĵ����֣��磺20100419XXXXXXXXXX��
      String logistics_name ������˾����
      String invoice_no ������������
      String transport_type ��������ʱ���������ͣ�����ֵ��ѡ��POST��ƽ�ʣ���EXPRESS����ݣ���EMS��EMS��
      String seller_ip ���ұ��ص���IP��ַ
      String key ��ȫ������
      String input_charset �ַ������ʽ Ŀǰ֧�� gbk �� utf-8
      String sign_type ǩ����ʽ �����޸�
�����String ���ύHTML�ı�

public static String CreateLinkString_urlencode(Map params, String input_charset)
���ܣ�����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ�������URL���룩
���룺Map    params ��Ҫƴ�ӵ�����
�����String ƴ������Ժ���ַ���

public static String PostXml(String partner,
	String trade_no,
	String logistics_name,
	String invoice_no,
	String transport_type,
	String seller_ip,
        String input_charset,
        String key,
        String sign_type)
���ܣ�Զ��xml����
���룺String partner ���������ID
      String trade_no ֧�������׺š����ǵ�½֧������վ�ڽ��׹����в�ѯ�õ���һ����8λ���ڿ�ͷ�Ĵ����֣��磺20100419XXXXXXXXXX��
      String logistics_name ������˾����
      String invoice_no ������������
      String transport_type ��������ʱ���������ͣ�����ֵ��ѡ��POST��ƽ�ʣ���EXPRESS����ݣ���EMS��EMS��
      String seller_ip ���ұ��ص���IP��ַ
      String key ��ȫ������
      String input_charset �ַ������ʽ Ŀǰ֧�� gbk �� utf-8
      String sign_type ǩ����ʽ �����޸�
�����String ��ý������

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

public static String getTime()
���ܣ���ȡ��ǰʱ�䣬��ʽ��HHmmss
�����String ��ǰʱ��

public static String getThree()
���ܣ������������λ��
�����String �����λ��

��������������������
 �������⣬��������
��������������������

����ڼ���֧�����ӿ�ʱ�������ʻ�������⣬��ʹ����������ӣ��ύ���롣
https://b.alipay.com/support/helperApply.htm?action=supportHome
���ǻ���ר�ŵļ���֧����ԱΪ������




