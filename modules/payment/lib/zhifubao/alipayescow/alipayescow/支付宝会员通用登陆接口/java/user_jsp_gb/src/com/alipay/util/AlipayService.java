package com.alipay.util;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.*;

import com.alipay.util.AlipayFunction;

/**
 *������alipay_service
 *���ܣ�֧�����ⲿ����ӿڿ���
 *��ϸ����ҳ��������������Ĵ����ļ�������Ҫ�޸�
 *�汾��3.1.1
 *�޸����ڣ�2010-11-30
 *˵����
  ���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
  �ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���
 */

public class AlipayService {
	/**
	 * ���ܣ�������ύHTML
	 * @param partner ���������ID
	 * @param return_url ��������ת��ҳ�� Ҫ�� ��http��ͷ��ʽ������·�����������?id=123�����Զ������
	 * @param email ��Ա��ע���½ʱ����Ա��֧�����˺�
	 * @param input_charset �ַ������ʽ Ŀǰ֧�� GBK �� utf-8
	 * @param sign_type ǩ����ʽ �����޸�
	 * @param key ��ȫУ����
	 * @return ���ύHTML�ı�
	 */
	public static String BuildForm(String partner,
			String return_url,
			String email,
            String input_charset,
            String key,
            String sign_type){
		Map sPara = new HashMap();
		sPara.put("service","user_authentication");
		sPara.put("partner", partner);
		sPara.put("return_url", return_url);
		sPara.put("email", email);
		sPara.put("_input_charset", input_charset);
		
		Map sParaNew = AlipayFunction.ParaFilter(sPara); //��ȥ�����еĿ�ֵ��ǩ������
		String mysign = AlipayFunction.BuildMysign(sParaNew, key);//����ǩ�����
		
		StringBuffer sbHtml = new StringBuffer();
		List keys = new ArrayList(sParaNew.keySet());
		String gateway = "https://www.alipay.com/cooperate/gateway.do?";
		
		//GET��ʽ����
		sbHtml.append("<form id=\"alipaysubmit\" name=\"alipaysubmit\" action=\"" + gateway + "_input_charset=" + input_charset + "\" method=\"get\">");
		//POST��ʽ���ݣ�GET��POST����ѡһ��
		//sbHtml.append("<form id=\"alipaysubmit\" name=\"alipaysubmit\" action=\"" + gateway + "_input_charset=" + input_charset + "\" method=\"post\">");

		for (int i = 0; i < keys.size(); i++) {
			String name = (String) keys.get(i);
			String value = (String) sParaNew.get(name);
			
			sbHtml.append("<input type=\"hidden\" name=\"" + name + "\" value=\"" + value + "\"/>");
		}
        sbHtml.append("<input type=\"hidden\" name=\"sign\" value=\"" + mysign + "\"/>");
        sbHtml.append("<input type=\"hidden\" name=\"sign_type\" value=\"" + sign_type + "\"/>");
        
        //submit��ť�ؼ��벻Ҫ����name����
        sbHtml.append("<input type=\"submit\" value=\"֧������Ա��¼\"></form>");
        
        //sbHtml.append("<script>document.forms['alipaysubmit'].submit();</script>");
		
		return sbHtml.toString();
	}
}
