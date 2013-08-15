<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Earnmoney 联盟驱动
 *
 */
class Earnmoney {
	/**
	 * Earnmoney 联盟配置的特殊信息
	 *
	 * @return string
	 */
	function get_form_string(){
		$string = '
			<tr>
			<th align="right">消息发送方式:</th>
			<td><input type="radio" name="send_type" value="1" checked>POST</td>
			</tr>';
		return $string;
	}
	
	/**
	 * Earnmoney 编辑需要特殊处理的信息
	 *
	 * @param class $prm_value
	 * @param int $send_type
	 * @return string
	 */
	function get_edit_string($prm_value,$send_type = 0){
		$string = '
			<tr>
			<th align="right">消息发送方式:</th>
			<td><input type="radio" name="send_type" value="0" checked>POST</td>
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
	function get_code($order, $affiliate_name = 'earnmoney'){
		//获取站点联盟信息，并判断是否
		$affiliate = Site_affiliateService::get_instance()->query_row( array('where'=>array('affiliate_name'=>$affiliate_name, 'site_id'=>$order['site_id'], 'mark'=>1,)) );
		if (empty($affiliate)) {
			return '<!-- affiliate '. $affiliate_name .' is not exist，or been uninstalled -->';
		}
		$prm_value = json_decode($affiliate['prm_value']);
		
		//检查参数格式是否正确，如果参数格式不正确不予以处理
		$params = cookie::get ( 'earnmoney_params' );
		$strDecode = self::uriDecode($params);
        $aryParams = explode('|',$strDecode);
        if('nid'==$aryParams[0]&&'uid'==$aryParams[2]&&'resource'==$aryParams[4]&&'url'==$aryParams[6])
        {
            $url = $aryParams[7];
            $uid = $aryParams[3];
            $nid = $aryParams[1];
            $resource = $aryParams[5];
        }else {
        	return '<!-- prams format is not exact -->';
        }
		
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
        
        $post_var ="uid=".$uid
       		 ."&nid=".$nid
       		 ."&order_num=".$order['order_num']
       		 ."&order_amount=".$order['total_product']
        	 ."&referAddress="."http://".$_SERVER['HTTP_HOST']."/";
        self::cul_em($post_var);
        return '<!-- earnmoney 数据发送成功 -->';
	}
	
	/**
	 * 设置联盟的cookie，标记最新的联盟
	 *
	 * @param int $site_id 站点ID
	 * @param string $affiliate_name 联盟名称
	 * @param int $log 是否记录联盟带来的流量
	 * @return unknown
	 */
	function set_cookie($site_id, $affiliate_name = 'earnmoney', $log=1){
		$affiliate = Site_affiliateService::get_instance()->query_row( array('where'=>array('affiliate_name'=>$affiliate_name, 'site_id'=>$site_id, 'mark'=>1)) );
		if (empty($affiliate)) {
			return '<!-- affiliate '. $affiliate_name .' is not exist,or been uninstalled -->';
		}
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
		if (isset($_GET['param'])) {
			$params = $_GET['param'];
			$strDecode = self::uriDecode($params);
	        $aryParams = explode('|',$strDecode);
	        if('nid'==$aryParams[0]&&'uid'==$aryParams[2]&&'resource'==$aryParams[4]&&'url'==$aryParams[6]){
	            setcookie('affiliateSource', 'earnmoney', time()+ intval( $affiliate['cookie_day'] ) *86400);
				setcookie('earnmoney_params', $params, time() + intval( $affiliate['cookie_day'] ) *86400);
				return '<!-- cookie affiliateSource was been set -->';
	        }else {
	        	return '<!-- params format wrong -->';
	        }
		}else {
			return '<!-- no params -->';
		}
	}
	
	/**
	 * 数据解码
	 *
	 * @param string $sEncode
	 * @param string $key
	 * @return string
	 */
	public static function uriDecode($sEncode,$key='k125')
    {
        if(strlen($sEncode)==0)
            return '';
        else
        {
            $s_tem = strrev($sEncode);
            $s_tem = base64_decode($s_tem);
            $s_tem = rawurldecode($s_tem);
            $vcode=substr($s_tem,6,7);
            $s_tem=substr($s_tem,14);
            $a_tem = explode('&', $s_tem);
            $verifyCode='';
            foreach($a_tem as $rs)
            {
                $verifyCode.=$key.$rs;
            }
            $verifyCode=substr(md5($verifyCode),3,7);

            if($verifyCode==$vcode)
              return $s_tem;
            else
                return '';
        }
    }
    
    /*
	 * curl模拟post到earnmoney；
	 */
    public static function cul_em($post_val)
    {
        $url = 'http://fb.statcount.org/stat.php';
        $post_data = $post_val;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        return;
    }
}