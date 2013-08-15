<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Webgains 联盟驱动
 *
 */
class Webgains {
	/**
	 * Webgains 联盟配置的特殊信息
	 *
	 * @return string
	 */
	function get_form_string(){
		$string = '
			<tr>
			<th align="right">wgprogramid:</th>
			<td><input name="pdata[wgprogramid]"></td>
			</tr>
			<tr>
			<th align="right">wgeventid:</th>
			<td><input name="pdata[wgeventid]"></td>
			</tr>
			<tr>
			<th align="right">pin:</th>
			<td><input name="pdata[pin]"></td>
			</tr>
			<tr>
			<th align="right">消息发送方式:</th>
			<td><input type="radio" name="send_type" value="0" checked>GET</td>
			</tr>';
		return $string;
	}
	
	/**
	 * Webgains 编辑需要特殊处理的信息
	 *
	 * @param class $prm_value
	 * @param int $send_type
	 * @return string
	 */
	function get_edit_string($prm_value,$send_type = 0){
		$string = '
			<tr>
			<th align="right">wgprogramid:</th>
			<td><input name="pdata[wgprogramid]" value="'.(isset($prm_value->wgprogramid) ? $prm_value->wgprogramid :'') .'"></td>
			</tr>
			<tr>
			<th align="right">wgeventid:</th>
			<td><input name="pdata[wgeventid]" value="'.(isset($prm_value->wgeventid) ? $prm_value->wgeventid :'') .'"></td>
			</tr>
			<tr>
			<th align="right">pin:</th>
			<td><input name="pdata[pin]" value="'.(isset($prm_value->pin) ? $prm_value->pin :'') .'"></td>
			</tr>
			<tr>
			<th align="right">消息发送方式:</th>
			<td><input type="radio" name="send_type" value="0" checked>GET</td>
			</tr>';
		return $string;
	}
	
	/**
	 * 订单支付成功页获取的单独的代码，向联盟发送订单成功支付信息
	 *
	 * @param array $order
	 * @param string $affiliate_name
	 * @return string
	 */
	function get_code($order, $affiliate_name = 'webgains'){
		//检查联盟是否安装，并获取联盟的信息
		$affiliate = Site_affiliateService::get_instance()->query_row( array('where'=>array('affiliate_name'=>$affiliate_name, 'site_id'=>$order['site_id'], 'mark'=>1,)) );
		if (empty($affiliate)) {
			return '<!-- affiliate '. $affiliate_name .' is not exist，or been uninstalled -->';
		}
		$prm_value = json_decode($affiliate['prm_value']);
		
        //货币转换相关
        $currency = $affiliate['currency'] == 'default' ? Kohana::config('affiliates.affiliatefuture.currency') : $affiliate['currency'];
        if($currency != 'default'){
        	$order['total_product'] = isset($order['total_products']) ? BLL_Currency::get_price($order['total_products'], $order['currency'], $currency) : BLL_Currency::get_price($order['total_product'], $order['currency'], $currency);
        	$order['total_product'] = str_replace(',','',$order['total_product']);
        	//$order['total_product'] = BLL_Currency::get_price($order['total_product'], $order['currency'], $currency);
        	$order['currency'] = $currency;
        }
        
		$save_return = Affiliate_orderService::save_affiliate_order($order, $affiliate);
        if ($save_return != 1) {
        	return $save_return;
        }
        
		$string = 
		"<!-- <webgains tracking code> -->
		<script language=\"javascript\" type=\"text/javascript\">
		var wgOrderReference = '{$order['order_num']}';
		var wgOrderValue = '{$order['total_product']}';
		var wgEventID = '{$prm_value->wgeventid}';
		var wgComment = '';
		var wgLang = 'en_EN';
		var wgsLang = 'javascript-client';
		var wgVersion = '1.2';
		var wgProgramID = '{$prm_value->wgprogramid}';
		var wgSubDomain = 'track';
		var wgCheckSum = '';
		var wgItems = '';
		var wgVoucherCode = '';
		var wgCustomerID = '';
		
		if(location.protocol.toLowerCase() == \"https:\") wgProtocol=\"https\";
		else wgProtocol = \"http\";
		
		wgUri = wgProtocol + \"://\" + wgSubDomain + \".webgains.com/transaction.html\" + \"?wgver=\" + wgVersion + \"&wgprotocol=\" + wgProtocol + \"&wgsubdomain=\" + wgSubDomain + \"&wgslang=\" + wgsLang + \"&wglang=\" + wgLang + \"&wgprogramid=\" + wgProgramID + \"&wgeventid=\" + wgEventID + \"&wgvalue=\" + wgOrderValue + \"&wgchecksum=\" + wgCheckSum + \"&wgorderreference=\"  + wgOrderReference + \"&wgcomment=\" + escape(wgComment) + \"&wglocation=\" + escape(document.referrer) + \"&wgitems=\" + escape(wgItems) + \"&wgcustomerid=\" + escape(wgCustomerID) + \"&wgvouchercode=\" + escape(wgVoucherCode);
		document.write('<sc'+'ript language=\"JavaScript\"  type=\"text/javascript\" src=\"'+wgUri+'\"></sc'+'ript>');
		
		</script>
		<noscript>
		<img src=\"http://track.webgains.com/transaction.html".
		"?wgver=1.2".
		"&wgprogramid={$prm_value->wgprogramid}".
		"&wgrs=1".
		"&wgvalue={$order['total_product']}".
		"&wgeventid={$prm_value->wgeventid}".
		"&wgorderreference={$order['order_num']}".
		"&wgitems=&wgvouchercode=&wgcustomerid=\" alt=\"\" />
		</noscript>
		<!-- </webgains tracking code> -->";
		return $string;
	}
	
	/**
	 * 设置联盟的cookie，标记最新的联盟
	 *
	 * @param int $site_id 站点ID
	 * @param string $affiliate_name 联盟名称
	 * @param int $log 是否记录联盟带来的流量
	 * @return unknown
	 */
	public function set_cookie( $site_id, $affiliate_name = 'webgains', $log=1){
		//检查联盟是否安装，并获取联盟的信息
		$affiliate = Site_affiliateService::get_instance()->query_row( array('where'=>array('affiliate_name'=>$affiliate_name, 'site_id'=>$site_id, 'mark'=>1)) );
		if (empty($affiliate)) {
			return '<!-- the affiliate '. $affiliate_name .' is not exist, or it was been uninstalled. -->';
		}
		//如果需要记录联盟的流量，则将相关内容插入数据库
		if ($log == 1) {
			$query_struct = array(
				'affiliate_id'	 => $affiliate['affiliate_id'],
				'affiliate_name' => $affiliate_name,
				'site_id'		 => $site_id,
				'site_name'		 => $_SERVER['HTTP_HOST'],
				'visit_url'		 => $_SERVER['REQUEST_URI'],
				'visit_time'	 => date('Y-m-d H:i:s'),);
				
			$orm_instance = ORM::factory('affiliate_visit');
			$data = $orm_instance->as_array();
            foreach ($query_struct as $key=>$val) {
                array_key_exists($key,$data) && $orm_instance->$key = $val;
            }
            $orm_instance->save();
		}
		setcookie('affiliateSource', 'webgains', time() + intval( $affiliate['cookie_day'] ) * 86400);
		return '<!-- cookie affiliateSource was been set -->';
	}
}