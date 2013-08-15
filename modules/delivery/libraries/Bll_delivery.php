<?php defined('SYSPATH') OR die('No direct access allowed.');

class Bll_delivery_Core {
	/**
	 * 根据物流类型ID以及相关条件查询满足条件的物流
	 * @param array $condition
	 * @return array $deliveries
	 * @author gehaifeng
	 * 
	 * $condition = array(
	 *   'site_id'              => '77',    //站点id
	 *   'delivery_category_id' => '9',     //物流类型ID
	 *   'country_id'           => '539',   //国家ID
	 *   'weight'               => '500',   //订单重量
	 *   'total_price'          => '300.99',//订单总价
	 * );
	 * 
	 * $deliveries = array(
	 *   'id'                   => '10',                 //物流ID
	 *   'name'                 => 'China Post',         //物流名称
	 *   'url'                  => 'www.ketai-inc.com',  //物流url地址
	 *   'delay'                => '5-20 days',          //物流延迟信息
	 *   'delivery_price'       => '46.97',              //物流价格
	 * );
	 */
	public static function get_deliveries_by_condition( $condition ){
		$category_id = $condition['delivery_category_id'];
		$weight      = $condition['weight'];
		$country_id  = $condition['country_id'];
		$total_price = $condition['total_price'];
		
		$dlv_instance = DeliveryService::get_instance();
		$dlvs = $dlv_instance->get_deliveries_by_category($category_id, $weight);
		
		if (empty($dlvs)) {
			return array();
		}
		
		$currency = BLL_Currency::get_current();
		$currency_sign = $currency['sign'];
		
		$deliveries = array();
		foreach ($dlvs as $dlv){
			$flag = 0;
			$id    = $dlv['id'];
			$name  = $dlv['name'];
			$url   = $dlv['url'];
			$delay = $dlv['delay'];
			
			if ( $dlv['type'] == 0 ) {
				$flag = 1;
			}else {
				$delivery_country = Delivery_countryService::get_instance()->get_delivery_country($id, $country_id);
				if (empty($delivery_country)) {
					if ( $dlv['is_default'] == 1 ) {
						$flag = 1;
					}else {
						continue;
					}
				}else {
					$flag = 1;
					$dlv['expression'] = $delivery_country['expression'];
				}
			}
			if ($flag = 0) {
				continue;
			}
			$delivery_price = delivery::cal_fee($dlv['expression'],$weight,$total_price);//计算物流费用
			
			$deliveries[] = array(
				'id'             => $id,
				'name'           => $name,
				'url'            => $url,
				'delay'          => $delay,
				'delivery_price' => BLL_Currency::get_price( round( $delivery_price, 2 ) ),
				'currency_sign'  => $currency_sign,
			);
		}
		
		return $deliveries;
	}
	
	/**
	 * 计算物流价格
	 * 
	 * @param array $condition
	 * @return float $price
	 * 
	 * $condition = array(
	 *   'delivery_id'          => '77',        //物流ID
	 *   'country_id'           => '539',       //物流递送国家
	 *   'weight'               => '500',       //订单重量
	 *   'total_price'          => '300.99',    //订单总价
	 * );
	 * 如果返回值是-1表示当前物流不支持到收货地址的配送
	 */
	public static function get_delivery_price_by_condition( $condition ){
		$delivery_id = $condition['delivery_id'];
		$country_id  = $condition['country_id'];
		$weight      = $condition['weight'];
		$total_price = $condition['total_price'];
		
		$dlv_instance = DeliveryService::get_instance();
		$delivery = $dlv_instance->get_delivery_by_id($delivery_id);
		
		if(empty($delivery)){
			return 0;
		}
		
		$flag = 0;
		if ( $delivery['type'] == 0 ) {
			$flag = 1;
		}else {
			$delivery_country = Delivery_countryService::get_instance()->get_delivery_country($delivery_id, $country_id);
			if (empty($delivery_country)) {
				if ( $delivery['is_default'] == 1 ) {
					$flag = 1;
				}else {
					return 0;
				}
			}else {
				$flag = 1;
				$delivery['expression'] = $delivery_country['expression'];
			}
		}
		if ($flag = 0) {
			return 0;
		}
		$price = delivery::cal_fee($delivery['expression'],$weight,$total_price);//计算物流费用
		
		return $price;
	}
	/**
	 * 根据国家的ISO获取各个物流类型的物流方式信息
	 *
	 * @param string $country_iso
	 * @param array  $delivery_cats
	 * @return $delivery_cats
	 */
	public static function get_cats_deliveries_by_country_iso($country_iso, $delivery_cats=array() ){
		if (empty($delivery_cats)) {
			$delivery_cats = self::_get_cart_delivary_data();
		}
		if (empty($delivery_cats)) {
			return array();
		}
		$country = Mycountry::instance()->get_by_iso($country_iso);
		if (empty($country)) {
			return array();
		}
		$country_id = $country['id'];
		
		foreach ($delivery_cats as $cat_id => $delivery_cat){
			$delivery_cats[$cat_id]['cat_id'] = $cat_id;
			$delivery_cats[$cat_id]['cat_name'] = Delivery_categoryService::get_instance()->get_name_by_id($cat_id);
			$condition = array(
				'delivery_category_id' =>$cat_id,
				'country_id'           =>$country_id,
				'weight'               =>$delivery_cat['weight'],
				'total_price'          =>$delivery_cat['total_price'],
			);
			$cat_deliveries = self::get_deliveries_by_condition($condition);
			$delivery_cats[$cat_id]['deliveries'] = $cat_deliveries;
		}
		$delivery_cats = array_values($delivery_cats);
		return $delivery_cats;
	}
	/**
	 * 获取订单指定物流的运费
	 *
	 * @param  mixed  $delivery_ids
	 * @param  string $country_iso
	 * @param  array  $delivery_cats
	 * @return array  $price_arr
	 */
	public static function get_delivery_price_selected($delivery_ids, $country_iso, $delivery_cats=array() ){
		if (empty($delivery_cats)) {
			$delivery_cats = self::_get_cart_delivary_data();
		}
		if (empty($delivery_cats)) {
			return array();
		}
		$country = Mycountry::instance()->get_by_iso($country_iso);
		if (empty($country)) {
			return array();
		}
		$country_id = $country['id'];
		
		$price_arr = array();
		if (!is_array($delivery_ids)) {
			$delivery_id = $delivery_ids;
			$delivery = DeliveryService::get_instance()->get($delivery_id);
			$cat_id = $delivery['delivery_category_id'];
			if ( key_exists($cat_id,$delivery_cats) ) {
				$condition = array(
					'delivery_id' => $delivery_id,
					'country_id'  => $country_id,
					'weight'      => $delivery_cats[$cat_id]['weight'],
					'total_price' => $delivery_cats[$cat_id]['total_price'],
				);
				$price = self::get_delivery_price_by_condition($condition);
				$price_arr[$delivery_id] = $price;
			}else {
				$price_arr[$delivery_id] = -1;
			}
			
		}else {
			foreach ($delivery_ids as $delivery_id){
				$delivery = DeliveryService::get_instance()->get($delivery_id);
				$cat_id = $delivery['delivery_category_id'];
				if ( key_exists($cat_id,$delivery_cats) ) {
					$condition = array(
						'delivery_id' => $delivery_id,
						'country_id'  => $country_id,
						'weight'      => $delivery_cats[$cat_id]['weight'],
						'total_price' => $delivery_cats[$cat_id]['total_price'],
					);
					$price = self::get_delivery_price_by_condition($condition);
					$price_arr[$delivery_id] = $price;
				}else {
					$price_arr[$delivery_id] = -1;
				}
			}
		}
		return $price_arr;
	}
	
	private static function _get_cart_delivary_data(){
		$cart_detail = CartService::instance()->get();
		
		if (empty($cart_detail['cart_product'])){
			return array();
		}
		$delivery_cats = array();
		foreach ($cart_detail['cart_product']['good'] as $good){
			if ( key_exists($good['delivery_category_id'], $delivery_cats) ){
				$delivery_cats[$good['delivery_category_id']]['weight'] += $good['weight'] * $good['quantity'];
				$delivery_cats[$good['delivery_category_id']]['total_price'] += $good['price'] * $good['quantity'];
			}else {
				$delivery_cats[$good['delivery_category_id']] = array(
					'weight'      => $good['weight'] * $good['quantity'],
					'total_price' => $good['price'] * $good['quantity'],
				);
			}
		}
		//如果是赠品需要物流，相应添加赠品的物流价格
		if ( isset($cart_detail['cart_product']['gift']) ) {
			$gifts = $cart_detail['cart_product']['gift'];
			foreach ($gifts as $gift){
				if ( key_exists($gift['delivery_category_id'], $delivery_cats) ){
					$delivery_cats[$gift['delivery_category_id']]['weight'] += $gift['weight'] * $gift['quantity'];
					$delivery_cats[$gift['delivery_category_id']]['total_price'] += $gift['price'] * $gift['quantity'];
				}else {
					$delivery_cats[$gift['delivery_category_id']] = array(
						'weight'      => $gift['weight'] * $gift['quantity'],
						'total_price' => $gift['price'] * $gift['quantity'],
					);
				}
			}
		}
		return $delivery_cats;
	}
	
	public static function get_category_name_by_id($cat_id){
		return Delivery_categoryService::get_instance()->get_name_by_id($cat_id);
	}
	
	public static function get_delivery($id){
		return DeliveryService::get_instance()->get($id);
	}
}