            �q�����������������������������������������������r
  �q����������           ֧��������ʾ���ṹ˵��             �����������r
  ��        �t�����������������������������������������������s        ��
����                                                                  ��
����     �ӿ����ƣ�֧�����������׽ӿڣ�create_partner_trade_by_buyer����
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

db_jsp_gb
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
  ��  ��alipayto.jsp ������������������֧�����ӿ�����ļ�
  ��  ��
  ��  ��index.jsp�������������������������ٸ������ģ���ļ�
  ��  ��
  ��  ��notify_url.jsp �����������������������첽֪ͨҳ���ļ�
  ��  ��
  ��  ��return_url.jsp ����������������ҳ����תͬ��֪ͨ�ļ�
  ��
  ��readme.txt ������������������ʹ��˵���ı�

��ע���
��Ҫ���õ��ļ��ǣ�alipay_config.jsp��alipayto.jsp
���ð���com.alipay.config.*��com.alipay.util.*

index.jsp����֧�����ṩ�ĸ������ģ���ļ�����ѡ��ʹ�á�
����̻���վ����ҵ��������Ҫʹ�ã����alipayto.jsp��Ϊ���̻���վ��վ���ν�ҳ�档
�����Ҫʹ��index.jsp����ôalipayto.jsp�ļ�������ģ�ֻ�����ú�alipay_config.java�ļ�
�õ�index.jspҳ�����̻���վ�е�HTTP·���������̻���վ����Ҫ��λ�ã�����ֱ��ʹ��֧�����ӿڡ�

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
	String seller_email,
	String return_url,
	String notify_url,
	String show_url,
	String out_trade_no,
	String subject,
	String body,
	String price,
	String logistics_fee,
	String logistics_type,
	String logistics_payment,
	String quantity,
	String receive_name,
	String receive_address,
	String receive_zip,
        String receive_phone,
        String receive_mobile,
        String logistics_fee_1,
        String logistics_type_1,
        String logistics_payment_1,
        String logistics_fee_2,
        String logistics_type_2,
        String logistics_payment_2,
        String buyer_email,
        String discount,
        String input_charset,
        String key,
        String sign_type)
���ܣ�������ύHTML
���룺String partner ���������ID
      String seller_email ǩԼ֧�����˺Ż�����֧�����ʻ�
      String return_url ��������ת��ҳ�� Ҫ�� ��http��ͷ��ʽ������·�����������?id=123�����Զ������
      String notify_url ���׹����з�����֪ͨ��ҳ�� Ҫ�� ��http����ʽ������·�����������?id=123�����Զ������
      String show_url ��վ��Ʒ��չʾ��ַ���������?id=123�����Զ������
      String out_trade_no �������վ����ϵͳ�е�Ψһ������ƥ��
      String subject �������ƣ���ʾ��֧��������̨��ġ���Ʒ���ơ����ʾ��֧�����Ľ��׹���ġ���Ʒ���ơ����б��
      String body ����������������ϸ��������ע����ʾ��֧��������̨��ġ���Ʒ��������
      String price �����ܽ���ʾ��֧��������̨��ġ���Ʒ���ۡ���
      String logistics_fee �������ã����˷�
      String logistics_type �������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
      String logistics_payment ����֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�
      String quantity ��Ʒ����������Ĭ��Ϊ1�����ı�ֵ����һ�ν��׿�����һ���¶������ǹ���һ����Ʒ��
      String receive_name �ջ����������磺����
      String receive_address �ջ��˵�ַ���磺XXʡXXX��XXX��XXX·XXXС��XXX��XXX��ԪXXX��
      String receive_zip �ջ����ʱ࣬�磺123456
      String receive_phone �ջ��˵绰���룬�磺0571-81234567
      String receive_mobile �ջ����ֻ����룬�磺13312341234
      String logistics_fee_1 �ڶ����������ã����˷�
      String logistics_type_1 �ڶ����������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
      String logistics_payment_1 �ڶ�������֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�
      String logistics_fee_2 �������������ã����˷�
      String logistics_type_2 �������������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
      String logistics_payment_2 ����������֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�
      String buyer_email Ĭ�����֧�����˺�
      String discount �ۿۣ��Ǿ���Ľ������ǰٷֱȡ���Ҫʹ�ô��ۣ���ʹ�ø���������֤С���������λ��
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




