package com.alipay.util;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.UnsupportedEncodingException;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.util.*;

import com.alipay.util.AlipayFunction;

/**
 *������alipay_refund_service
 *���ܣ�֧�����ⲿ����ӿڿ���
 *��ϸ����ҳ��������������Ĵ����ļ�������Ҫ�޸�
 *�汾��3.1
 *�޸����ڣ�2010-12-17
 *˵����
  ���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
  �ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���
 */

public class AlipayService {

	/**
	 * ���ܣ�������ύHTML
	 * @param partner ���������ID
     * @param trade_no ֧�������׺š����ǵ�½֧������վ�ڽ��׹����в�ѯ�õ���һ����8λ���ڿ�ͷ�Ĵ����֣��磺20100419XXXXXXXXXX��
     * @param logistics_name ������˾����
     * @param invoice_no ������������
     * @param transport_type ��������ʱ���������ͣ�����ֵ��ѡ��POST��ƽ�ʣ���EXPRESS����ݣ���EMS��EMS��
     * @param seller_ip ���ұ��ص���IP��ַ
	 * @param input_charset �ַ������ʽ Ŀǰ֧�� GBK �� utf-8
	 * @param key ��ȫУ����
	 * @param sign_type ǩ����ʽ �����޸�
	 * @return ���ύHTML�ı�
	 */
	public static String BuildForm(String partner,
			String trade_no,
			String logistics_name,
			String invoice_no,
			String transport_type,
			String seller_ip,
            String input_charset,
            String key,
            String sign_type){
		Map sPara = new HashMap();
		sPara.put("service","send_goods_confirm_by_platform");
		sPara.put("partner", partner);
		sPara.put("trade_no", trade_no);
		sPara.put("logistics_name", logistics_name);
		sPara.put("invoice_no", invoice_no);
		sPara.put("transport_type", transport_type);
		sPara.put("seller_ip", seller_ip);
		sPara.put("_input_charset", input_charset);
		
		Map sParaNew = AlipayFunction.ParaFilter(sPara); //��ȥ�����еĿ�ֵ��ǩ������
		String mysign = AlipayFunction.BuildMysign(sParaNew, key);//����ǩ�����
		
		StringBuffer sbHtml = new StringBuffer();
		List keys = new ArrayList(sParaNew.keySet());
		String gateway = "https://www.alipay.com/cooperate/gateway.do?";
		
		//GET��ʽ����
		//sbHtml.append("<form id=\"alipaysubmit\" name=\"alipaysubmit\" action=\"" + gateway + "_input_charset=" + input_charset + "\" method=\"get\">");
		//POST��ʽ���ݣ�GET��POST����ѡһ��
		sbHtml.append("<form id=\"alipaysubmit\" name=\"alipaysubmit\" action=\"" + gateway + "_input_charset=" + input_charset + "\" method=\"post\">");
		
		for (int i = 0; i < keys.size(); i++) {
			String name = (String) keys.get(i);
			String value = (String) sParaNew.get(name);
			
			sbHtml.append("<input type=\"hidden\" name=\"" + name + "\" value=\"" + value + "\"/>");
		}
        sbHtml.append("<input type=\"hidden\" name=\"sign\" value=\"" + mysign + "\"/>");
        sbHtml.append("<input type=\"hidden\" name=\"sign_type\" value=\"" + sign_type + "\"/>");
        
        //submit��ť�ؼ��벻Ҫ����name����
        sbHtml.append("<input type=\"submit\" value=\"֧����ȷ�Ϸ���\"></form>");
        
        sbHtml.append("<script>document.forms['alipaysubmit'].submit();</script>");
		
		return sbHtml.toString();
	}
	
	/** 
	 * ���ܣ�����������Ԫ�ذ��ա�����=����ֵ����ģʽ�á�&���ַ�ƴ�ӳ��ַ���
	 * @param params ��Ҫ���򲢲����ַ�ƴ�ӵĲ�����
	 * @param input_charset �����ʽ
	 * @return ƴ�Ӻ��ַ���
	 */
	public static String CreateLinkString_urlencode(Map params, String input_charset){
		List keys = new ArrayList(params.keySet());
		Collections.sort(keys);

		String prestr = "";

		for (int i = 0; i < keys.size(); i++) {
			String key = (String) keys.get(i);
			String value = (String) params.get(key);

			try {
				prestr = prestr + key + "=" + URLEncoder.encode(value,input_charset) + "&";
			} catch (UnsupportedEncodingException e) {

				e.printStackTrace();
			}
		}

		return prestr;
	}
	
	/**
	 * ���ܣ�Զ��xml����
	 * @param partner ���������ID
     * @param trade_no ֧�������׺š����ǵ�½֧������վ�ڽ��׹����в�ѯ�õ���һ����8λ���ڿ�ͷ�Ĵ����֣��磺20100419XXXXXXXXXX��
     * @param logistics_name ������˾����
     * @param invoice_no ������������
     * @param transport_type ��������ʱ���������ͣ�����ֵ��ѡ��POST��ƽ�ʣ���EXPRESS����ݣ���EMS��EMS��
     * @param seller_ip ���ұ��ص���IP��ַ
	 * @param input_charset �ַ������ʽ Ŀǰ֧�� GBK �� utf-8
	 * @param key ��ȫУ����
	 * @param sign_type ǩ����ʽ �����޸�
	 * @return ��ý������
	 */
	public static String PostXml(String partner,
			String trade_no,
			String logistics_name,
			String invoice_no,
			String transport_type,
			String seller_ip,
            String input_charset,
            String key,
            String sign_type) throws Exception{
		Map sPara = new HashMap();
		sPara.put("service","send_goods_confirm_by_platform");
		sPara.put("partner", partner);
		sPara.put("trade_no", trade_no);
		sPara.put("logistics_name", logistics_name);
		sPara.put("invoice_no", invoice_no);
		sPara.put("transport_type", transport_type);
		sPara.put("seller_ip", seller_ip);
		sPara.put("_input_charset", input_charset);
		
		Map sParaNew = AlipayFunction.ParaFilter(sPara); //��ȥ�����еĿ�ֵ��ǩ������
		String mysign = AlipayFunction.BuildMysign(sParaNew, key);//����ǩ�����
		
		sParaNew.put("sign", mysign);
		sParaNew.put("sign_type", "MD5");
		
		String strUrl = "https://www.alipay.com/cooperate/gateway.do?_input_charset=utf-8";
		URL url = new URL(strUrl);
		HttpURLConnection conn = (HttpURLConnection)url.openConnection();
		conn.setRequestMethod("POST");
		conn.setDoInput(true);
		conn.setDoOutput(true);
		OutputStream os = conn.getOutputStream();
		os.write(CreateLinkString_urlencode(sParaNew,input_charset).getBytes("utf-8"));
		os.close();

		BufferedReader br = new BufferedReader(new InputStreamReader(conn.getInputStream()));
		String line;
		String xmlResult ="";
		while( (line =br.readLine()) != null ){
			xmlResult += "\n"+line;
		}
		br.close();

		return xmlResult;
	}
}
