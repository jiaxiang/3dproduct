/* *
 *���ܣ������ʻ��й���Ϣ������·������������ҳ�棩
 *�汾��3.1
 *���ڣ�2010-11-24
 *˵����
 *���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
 *�ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���
	
 *��ʾ����λ�ȡ��ȫУ����ͺ��������ID
 *1.����֧������ҳ(www.alipay.com)��Ȼ��������ǩԼ֧�����˺ŵ�½.
 *2.����������еġ��̼ҷ��񡱣����ɲ鿴
	
 *��ȫУ����鿴ʱ������֧�������ҳ��ʻ�ɫ��������ô�죿
 *���������
 *1�������������ã������������������������
 *2���������������ԣ����µ�¼��ѯ��
 * */
package com.alipay.config;

import java.util.*;

public class AlipayConfig {
	// ��λ�ȡ��ȫУ����ͺ��������ID
	// 1.����֧�����̻���������(b.alipay.com)��Ȼ��������ǩԼ֧�����˺ŵ�½.
	// 2.���ʡ��������񡱡������ؼ��������ĵ�����https://b.alipay.com/support/helperApply.htm?action=selfIntegration��
	// 3.�ڡ��������ɰ������У���������������(Partner ID)��ѯ��������ȫУ����(Key)��ѯ��
	
	//�����������������������������������Ļ�����Ϣ������������������������������
	// ���������ID����2088��ͷ��16λ��������ɵ��ַ���
	public static String partner = "";
	
	// ���װ�ȫ�����룬�����ֺ���ĸ��ɵ�32λ�ַ���
	public static String key = "";
	
	// ǩԼ֧�����˺Ż������տ�֧�����ʻ�
	public static String seller_email = "";
	
	// notify_url ���׹����з�����֪ͨ��ҳ�� Ҫ�� http://��ʽ������·�����������?id=123�����Զ������
	public static String notify_url = "http://www.xxx.cn/db_jsp_utf8/notify_url.jsp";
	
	// ��������ת��ҳ�� Ҫ�� http://��ʽ������·�����������?id=123�����Զ������
	public static String return_url = "http://localhost:8080/db_jsp_utf8/return_url.jsp";
	
	// ��վ��Ʒ��չʾ��ַ���������?id=123�����Զ������
	public static String show_url = "http://www.alipay.com";
	
	//�տ���ƣ��磺��˾���ơ���վ���ơ��տ���������
	public static String mainname = "�տ����";
	//�����������������������������������Ļ�����Ϣ������������������������������


	// �ַ������ʽ Ŀǰ֧�� gbk �� utf-8
	public static String input_charset = "UTF-8";
	
	// ǩ����ʽ �����޸�
	public static String sign_type = "MD5";
	
	//����ģʽ,�����Լ��ķ������Ƿ�֧��ssl���ʣ���֧����ѡ��https������֧����ѡ��http
	public static String transport = "http";
}
