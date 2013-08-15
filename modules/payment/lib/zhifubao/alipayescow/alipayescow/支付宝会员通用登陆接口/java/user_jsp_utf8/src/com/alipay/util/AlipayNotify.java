package com.alipay.util;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Map;

import com.alipay.config.AlipayConfig;

/**
 *������alipay_notify
 *���ܣ�֧����������֪ͨ��
 *��ϸ����ҳ��������������Ĵ����ļ�������Ҫ�޸�
 *�汾��3.1
 *�޸����ڣ�2010-10-26
 *˵����
  ���´���ֻ��Ϊ�˷����̻����Զ��ṩ���������룬�̻����Ը����Լ���վ����Ҫ�����ռ����ĵ���д,����һ��Ҫʹ�øô��롣
  �ô������ѧϰ���о�֧�����ӿ�ʹ�ã�ֻ���ṩһ���ο���
 */

public class AlipayNotify {
	/**
	 * *���ܣ����ݷ�����������Ϣ������ǩ�����
	 * @param Params ֪ͨ�������Ĳ�������
	 * @param key ��ȫУ����
	 * @return ���ɵ�ǩ�����
	 */
	public static String GetMysign(Map Params, String key){
		Map sParaNew = AlipayFunction.ParaFilter(Params);//���˿�ֵ��sign��sign_type����
		String mysign = AlipayFunction.BuildMysign(sParaNew, key);//���ǩ�����
		
		return mysign;
	}
	
	/**
	* *���ܣ���ȡԶ�̷�����ATN���,��֤����URL
	* @param notify_id ֪ͨУ��ID
	* @return ������ATN���
	* ��֤�������
	* invalid����������� ��������������ⷵ�ش�����partner��key�Ƿ�Ϊ�� 
	* true ������ȷ��Ϣ
	* false �������ǽ�����Ƿ�������ֹ�˿������Լ���֤ʱ���Ƿ񳬹�һ����
	*/
	public static String Verify(String notify_id){
		//��ȡԶ�̷�����ATN�������֤�Ƿ���֧��������������������
		String transport = AlipayConfig.transport;
		String partner = AlipayConfig.partnerID;
		String veryfy_url = "";
		if(transport.equalsIgnoreCase("https")){
			veryfy_url = "https://www.alipay.com/cooperate/gateway.do?service=notify_verify";
		} else{
			veryfy_url = "http://notify.alipay.com/trade/notify_query.do?";
		}
		veryfy_url = veryfy_url + "&partner=" + partner + "&notify_id=" + notify_id;
		
		String responseTxt = CheckUrl(veryfy_url);
		
		return responseTxt;
	}
	
	/**
	* *���ܣ���ȡԶ�̷�����ATN���
	* @param urlvalue ָ��URL·����ַ
	* @return ������ATN���
	* ��֤�������
	* invalid����������� ��������������ⷵ�ش�����partner��key�Ƿ�Ϊ�� 
	* true ������ȷ��Ϣ
	* false �������ǽ�����Ƿ�������ֹ�˿������Լ���֤ʱ���Ƿ񳬹�һ����
	*/
	public static String CheckUrl(String urlvalue){
		String inputLine = "";

		try {
			URL url = new URL(urlvalue);
			HttpURLConnection urlConnection = (HttpURLConnection) url
					.openConnection();
			BufferedReader in = new BufferedReader(new InputStreamReader(
					urlConnection.getInputStream()));
			inputLine = in.readLine().toString();
		} catch (Exception e) {
			e.printStackTrace();
		}

		return inputLine;
	}
}
