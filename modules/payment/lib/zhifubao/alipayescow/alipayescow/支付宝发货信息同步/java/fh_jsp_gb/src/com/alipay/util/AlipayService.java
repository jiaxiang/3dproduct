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
 *类名：alipay_refund_service
 *功能：支付宝外部服务接口控制
 *详细：该页面是请求参数核心处理文件，不需要修改
 *版本：3.1
 *修改日期：2010-12-17
 *说明：
  以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
  该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
 */

public class AlipayService {

	/**
	 * 功能：构造表单提交HTML
	 * @param partner 合作身份者ID
     * @param trade_no 支付宝交易号。它是登陆支付宝网站在交易管理中查询得到，一般以8位日期开头的纯数字（如：20100419XXXXXXXXXX）
     * @param logistics_name 物流公司名称
     * @param invoice_no 物流发货单号
     * @param transport_type 物流发货时的运输类型，三个值可选：POST（平邮）、EXPRESS（快递）、EMS（EMS）
     * @param seller_ip 卖家本地电脑IP地址
	 * @param input_charset 字符编码格式 目前支持 GBK 或 utf-8
	 * @param key 安全校验码
	 * @param sign_type 签名方式 不需修改
	 * @return 表单提交HTML文本
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
		
		Map sParaNew = AlipayFunction.ParaFilter(sPara); //除去数组中的空值和签名参数
		String mysign = AlipayFunction.BuildMysign(sParaNew, key);//生成签名结果
		
		StringBuffer sbHtml = new StringBuffer();
		List keys = new ArrayList(sParaNew.keySet());
		String gateway = "https://www.alipay.com/cooperate/gateway.do?";
		
		//GET方式传递
		//sbHtml.append("<form id=\"alipaysubmit\" name=\"alipaysubmit\" action=\"" + gateway + "_input_charset=" + input_charset + "\" method=\"get\">");
		//POST方式传递（GET与POST二必选一）
		sbHtml.append("<form id=\"alipaysubmit\" name=\"alipaysubmit\" action=\"" + gateway + "_input_charset=" + input_charset + "\" method=\"post\">");
		
		for (int i = 0; i < keys.size(); i++) {
			String name = (String) keys.get(i);
			String value = (String) sParaNew.get(name);
			
			sbHtml.append("<input type=\"hidden\" name=\"" + name + "\" value=\"" + value + "\"/>");
		}
        sbHtml.append("<input type=\"hidden\" name=\"sign\" value=\"" + mysign + "\"/>");
        sbHtml.append("<input type=\"hidden\" name=\"sign_type\" value=\"" + sign_type + "\"/>");
        
        //submit按钮控件请不要含有name属性
        sbHtml.append("<input type=\"submit\" value=\"支付宝确认发货\"></form>");
        
        sbHtml.append("<script>document.forms['alipaysubmit'].submit();</script>");
		
		return sbHtml.toString();
	}
	
	/** 
	 * 功能：把数组所有元素按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param params 需要排序并参与字符拼接的参数组
	 * @param input_charset 编码格式
	 * @return 拼接后字符串
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
	 * 功能：远程xml解析
	 * @param partner 合作身份者ID
     * @param trade_no 支付宝交易号。它是登陆支付宝网站在交易管理中查询得到，一般以8位日期开头的纯数字（如：20100419XXXXXXXXXX）
     * @param logistics_name 物流公司名称
     * @param invoice_no 物流发货单号
     * @param transport_type 物流发货时的运输类型，三个值可选：POST（平邮）、EXPRESS（快递）、EMS（EMS）
     * @param seller_ip 卖家本地电脑IP地址
	 * @param input_charset 字符编码格式 目前支持 GBK 或 utf-8
	 * @param key 安全校验码
	 * @param sign_type 签名方式 不需修改
	 * @return 获得解析结果
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
		
		Map sParaNew = AlipayFunction.ParaFilter(sPara); //除去数组中的空值和签名参数
		String mysign = AlipayFunction.BuildMysign(sParaNew, key);//生成签名结果
		
		sParaNew.put("sign", mysign);
		sParaNew.put("sign_type", "MD5");
		
		String strUrl = "https://www.alipay.com/cooperate/gateway.do?";
		URL url = new URL(strUrl);
		HttpURLConnection conn = (HttpURLConnection)url.openConnection();
		conn.setRequestMethod("POST");
		conn.setDoInput(true);
		conn.setDoOutput(true);
		OutputStream os = conn.getOutputStream();
		os.write(CreateLinkString_urlencode(sParaNew,input_charset).getBytes("GBK"));
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
