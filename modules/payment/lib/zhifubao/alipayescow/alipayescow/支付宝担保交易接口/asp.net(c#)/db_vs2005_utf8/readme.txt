            �q�����������������������������������������������r
  �q����������           ֧��������ʾ���ṹ˵��             �����������r
  ��        �t�����������������������������������������������s        ��
����                                                                  ��
����     �ӿ����ƣ�֧�����������׽ӿڣ�create_partner_trade_by_buyer����
������   ����汾��3.1                                                ��
  ��     �������ԣ�ASP.NET(c#)                                        ��
  ��     ��    Ȩ��֧�������й������缼�����޹�˾                     ��
����     �� �� �ߣ�֧�����̻���ҵ������֧����                         ��
  ��     ��ϵ��ʽ���̻�����绰0571-88158090                          ��
  ��                                                                  ��
  �t�������������������������������������������������������������������s

��������������
 �����ļ��ṹ
��������������

db_vs2005_utf8
  ��
  ��app_code �����������������������ļ���
  ��  ��
  ��  ��alipay_config.cs ��������������Ϣ�����������ļ�
  ��  ��
  ��  ��alipay_function.cs ���������ú������ļ�
  ��  ��
  ��  ��alipay_notify.cs ��������֧����֪ͨ�������ļ�
  ��  ��
  ��  ��alipay_service.cs��������֧�������������ļ�
  ��
  ��images ����������������������ͼƬ��CSS��ʽ�ļ���
  ��
  ��log����������������������������־�ļ���
  ��
  ��alipayto.aspx����������������֧�����ӿ�����ļ�
  ��alipayto.aspx.cs ������������֧�����ӿ�����ļ�
  ��
  ��default.aspx �������������������ٸ������ģ���ļ�
  ��default.aspx.cs�����������������ٸ������ģ���ļ�
  ��
  ��notify_url.aspx���������������������첽֪ͨҳ���ļ�
  ��notify_url.aspx.cs �����������������첽֪ͨҳ���ļ�
  ��
  ��return_url.aspx��������������ҳ����תͬ��֪ͨ�ļ�
  ��return_url.aspx.cs ����������ҳ����תͬ��֪ͨ�ļ�
  ��
  ��Web.Config �����������������������ļ�������ʱɾ����
  ��
  ��readme.txt ������������������ʹ��˵���ı�

��ע���
��Ҫ���õ��ļ��ǣ�alipay_config.cs��alipayto.aspx��alipayto.aspx.cs
ͳһ�����ռ�Ϊ��namespace AlipayClass

index.aspx����֧�����ṩ�ĸ������ģ���ļ�����ѡ��ʹ�á�
����̻���վ����ҵ��������Ҫʹ�ã����alipayto.aspx��Ϊ���̻���վ��վ���ν�ҳ�档
�����Ҫʹ��default.aspx����ôalipayto.aspx�ļ�������ģ�ֻ�����ú�alipay_config.cs�ļ�
�õ�default.aspxҳ�����̻���վ�е�HTTP·���������̻���վ����Ҫ��λ�ã�����ֱ��ʹ��֧�����ӿڡ�



������������������
 ���ļ������ṹ
������������������

alipay_function.cs

public static string Build_mysign(Dictionary<string, string> dicArray, string key, string sign_type, string _input_charset)
���ܣ�����ǩ�����
���룺Dictionary<string, string>  dicArray Ҫǩ��������
      string key ��ȫУ����
      string sign_type ǩ������
      string _input_charset �����ʽ
�����string ǩ������ַ���

public static string Create_linkstring(Dictionary<string, string> dicArray)
���ܣ�����������Ԫ�أ����ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
���룺Dictionary<string, string> dicArray ��Ҫƴ�ӵ�����
�����string ƴ������Ժ���ַ���

public static Dictionary<string, string> Para_filter(SortedDictionary<string, string> dicArrayPre)
���ܣ���ȥ�����еĿ�ֵ��ǩ������������ĸa��z��˳������
���룺SortedDictionary<string, string> dicArrayPre ����ǰ�Ĳ�����
�����Dictionary<string, string>  ȥ����ֵ��ǩ�����������ǩ��������

public static string Sign(string prestr, string sign_type, string _input_charset)
���ܣ�ǩ���ַ���
���룺string prestr ��Ҫǩ�����ַ���
      string sign_type ǩ������
      string _input_charset �����ʽ
�����string ǩ�����

public static string Query_timestamp(string partner)
���ܣ����ڷ����㣬���ýӿ�query_timestamp����ȡʱ����Ĵ�����
���룺string partner ���������ID
�����string ʱ����ַ���

public static void log_result(string sPath, string sWord)
���ܣ�д��־��������ԣ�����վ����Ҳ���Ըĳɴ������ݿ⣩
���룺string sPath ��־�ı��ؾ���·��
      string sWord Ҫд����־����ı�����

��������������������������������������������������������������

alipay_notify.cs

public AlipayNotify(SortedDictionary<string, string> inputPara, string notify_id, string partner, string key, string input_charset, string sign_type, string transport)
���ܣ����캯��
      �������ļ��г�ʼ������
���룺SortedDictionary<string, string> inputPara ֪ͨ�������Ĳ�������
      string notify_id ��֤֪ͨID
      string partner ���������ID
      string key ��ȫУ����
      string input_charset �����ʽ
      string sign_type ǩ������
      string transport ����ģʽ

private string Verify(string notify_id)
���ܣ���֤�Ƿ���֧��������������������
���룺string notify_id ��֤֪ͨID
�����string ��֤���

private string Get_Http(string strUrl, int timeout)
���ܣ���ȡԶ�̷�����ATN���
���룺string strUrl ָ��URL·����ַ
      int timeout ��ʱʱ������
�����string ������ATN����ַ���

��������������������������������������������������������������

alipay_service.cs

        public AlipayService(string partner,
            string seller_email,
            string return_url,
            string notify_url,
            string show_url,
            string out_trade_no,
            string subject,
            string body,
            string price,
            string logistics_fee,
            string logistics_type,
            string logistics_payment,
            string quantity,
            string receive_name,
            string receive_address,
            string receive_zip,
            string receive_phone,
            string receive_mobile,
            string logistics_fee_1,
            string logistics_type_1,
            string logistics_payment_1,
            string logistics_fee_2,
            string logistics_type_2,
            string logistics_payment_2,
            string buyer_email,
            string discount,
            string key,
            string input_charset,
            string sign_type)
���ܣ����캯��
      �������ļ�������ļ��г�ʼ������
���룺string partner ���������ID
      string seller_email ǩԼ֧�����˺Ż�����֧�����ʻ�
      string return_url ��������ת��ҳ�� Ҫ�� ��http��ͷ��ʽ������·�����������?id=123�����Զ������
      string notify_url ���׹����з�����֪ͨ��ҳ�� Ҫ�� ��http����ʽ������·�����������?id=123�����Զ������
      string show_url ��վ��Ʒ��չʾ��ַ���������?id=123�����Զ������
      string out_trade_no �������վ����ϵͳ�е�Ψһ������ƥ��
      string subject �������ƣ���ʾ��֧��������̨��ġ���Ʒ���ơ����ʾ��֧�����Ľ��׹���ġ���Ʒ���ơ����б��
      string body ����������������ϸ��������ע����ʾ��֧��������̨��ġ���Ʒ��������
      string price �����ܽ���ʾ��֧��������̨��ġ���Ʒ���ۡ���
      string logistics_fee �������ã����˷�
      string logistics_type �������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
      string logistics_payment ����֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�
      string quantity ��Ʒ����������Ĭ��Ϊ1�����ı�ֵ����һ�ν��׿�����һ���¶������ǹ���һ����Ʒ��
      string receive_name �ջ����������磺����
      string receive_address �ջ��˵�ַ���磺XXʡXXX��XXX��XXX·XXXС��XXX��XXX��ԪXXX��
      string receive_zip �ջ����ʱ࣬�磺123456
      string receive_phone �ջ��˵绰���룬�磺0571-81234567
      string receive_mobile �ջ����ֻ����룬�磺13312341234
      string logistics_fee_1 �ڶ����������ã����˷�
      string logistics_type_1 �ڶ����������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
      string logistics_payment_1 �ڶ�������֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�
      string logistics_fee_2 �������������ã����˷�
      string logistics_type_2 �������������ͣ�����ֵ��ѡ��EXPRESS����ݣ���POST��ƽ�ʣ���EMS��EMS��
      string logistics_payment_2 ����������֧����ʽ������ֵ��ѡ��SELLER_PAY�����ҳе��˷ѣ���BUYER_PAY����ҳе��˷ѣ�
      string buyer_email Ĭ�����֧�����˺�
      string discount �ۿۣ��Ǿ���Ľ������ǰٷֱȡ���Ҫʹ�ô��ۣ���ʹ�ø���������֤С���������λ��
      string key ��ȫ������
      string input_charset �ַ������ʽ Ŀǰ֧�� gbk �� utf-8
      string sign_type ǩ����ʽ �����޸�

public string Build_Form()
���ܣ�������ύHTML
�����string ���ύHTML�ı�

��������������������������������������������������������������

return_url.aspx.cs

public SortedDictionary<string, string> GetRequestGet()
���ܣ���ȡ֧����GET����֪ͨ��Ϣ�����ԡ�������=����ֵ������ʽ�������
�����SortedDictionary<string, string> request��������Ϣ��ɵ�����

��������������������������������������������������������������

notify_url.aspx.cs

public SortedDictionary<string, string> GetRequestPost()
���ܣ���ȡ֧����POST����֪ͨ��Ϣ�����ԡ�������=����ֵ������ʽ�������
�����SortedDictionary<string, string> request��������Ϣ��ɵ�����

��������������������
 �������⣬��������
��������������������

����ڼ���֧�����ӿ�ʱ�������ʻ�������⣬��ʹ����������ӣ��ύ���롣
https://b.alipay.com/support/helperApply.htm?action=supportHome
���ǻ���ר�ŵļ���֧����ԱΪ������




