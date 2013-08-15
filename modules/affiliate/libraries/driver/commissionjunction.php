<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Commissionjunction 联盟驱动
 *
 */
class Commissionjunction {
	/**
	 * Commissionjunction 联盟配置的特殊信息
	 *
	 * @return string
	 */
	function get_form_string(){
		$string = '
			<tr>
			<th align="right">cid:</th>
			<td><input name="pdata[cid]"></td>
			</tr>
			<tr>
			<th align="right">Action ID:</th>
			<td><input name="pdata[actionid]"></td>
			</tr>
			<tr>
			<th align="right">消息发送方式:</th>
			<td><input type="radio" name="send_type" value="0" checked>GET</td>
			</tr>';
		return $string;
	}
	
	/**
	 * Commissionjunction 编辑需要特殊处理的信息
	 *
	 * @param class $prm_value
	 * @param int $send_type
	 * @return string
	 */
	function get_edit_string($prm_value,$send_type = 0){
		$string = '
			<tr>
			<th align="right">cid:</th>
			<td><input name="pdata[cid]" value="'.(isset($prm_value->cid) ? $prm_value->cid :'') .'"></td>
			</tr>
			<tr>
			<th align="right">Action ID:</th>
			<td><input name="pdata[actionid]" value="'.(isset($prm_value->actionid) ? $prm_value->actionid :'').'"></td>
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
	function get_code($order, $affiliate_name = 'commissionjunction'){
		//获取站点联盟信息，并判断是否
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
        
        //要返回的联盟的代码
		$string = 
		"<img src=\"https://www.emjcd.com/u".
		"?AMOUNT={$order['total_product']}".
		"&CID={$prm_value->cid}".
		"&OID={$order['order_num']}".
		"&TYPE=" . $prm_value->actionid .
		"&CURRENCY={$order['currency']}&".
		"METHOD=IMG\" height=\"1\" width=\"1\">";
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
	public function set_cookie($site_id, $affiliate_name = 'commissionjunction', $log=1){
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
		//设置联盟的cookie
		setcookie('affiliateSource', 'commissionjunction', time()+ intval( $affiliate['cookie_day'] ) *86400);
		return '<!-- cookie affiliateSource was been -->';
	}
}