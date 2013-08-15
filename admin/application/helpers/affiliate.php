<?php
class affiliate_Core {

	public static function send($order_id)
	{
		$order = Myorder::instance($order_id)->get();
		$order['order_product_detail'] = Myorder_product::instance()->order_product_details(array('order_id'=>$order['id']));

		$order['skulist'] = '';
		$order['qlist'] = '';
		$order['amtlist'] = '';
		$order['source'] = '1';

		$skulist = array();
		$qlist = array();
		$amtlist = array();
		foreach($order['order_product_detail'] as $key=>$order_product_detail)
		{
			$skulist[] = $order_product_detail['SKU'];
			$qlist[] = $order_product_detail['quantity'];
			$amtlist[] = $order_product_detail['discount_price'];
		}
		$order['skulist'] = implode("|", $skulist);
		$order['qlist'] = implode("|", $qlist);
		$order['amtlist'] = implode("|", $amtlist);


		$post_url = "http://af.statcount.org/order/";

		$post_var = "order=".$order['order_num']
						."&amount=".$order['total_real']
						."&skulist=".$order['skulist']
						."&qlist=".$order['qlist']
						."&amtlist=".$order['amtlist']
						."&cur=".$order['currency']
						."&source=".$order['source'];
		$result			= tool::curl_pay($post_url,$post_var);
		if(stristr($result == 'SUCCESS')){
			return true;
		}else{
			return false;
		}
    }
}
/*

GET和POST方法提交的数据都可以接受

同时支持http和ssl(https)协议

参数说明：
<img src="http://af.statcount.org/order/?order=ORDER_CODE&amount=AMOUNT&skulist=PRODUCT_SKU_LIST&qlist=QUANTITY_LIST&amtlist=PRODUCT_PRICE_LIST&cur=SHORT_CUR
RENCY_CODE&source=AFFILIATE_SOURCE" width="1" height="1">

http://af.statcount.org/order/?
order=ORDER_CODE&
amount=AMOUNT&
skulist=PRODUCT_SKU_LIST&
qlist=QUANTITY_LIST&
amtlist=PRODUCT_PRICE_LIST&
cur=SHORT_CURRENCY_CODE&
source=AFFILIATE_SOURCE

说明：
订单号   订单总价（扣除税收和邮费）    商品列表    数量列表     商品对应价格列表    短货币编码 来源联盟id

商品列表，数量列表，商品对应价格列表以竖线（|）分割，开始结束不包括该分割线
例如：
skulist=GS1250|GS1251&
qlist=2|1&
amtlist=82.51|56.54&


AFFILIATE_SOURCE:
值对应联盟
1:linkshare
2:shareasale
3:webgains
4:cj
5:affiliatefuture
6:da
例如：source=1


SHORT_CURRENCY_CODE：
ISO-4217标准
Code Currency
AUD Australian Dollar
CAD Canadian Dollar
CHF Swiss Franc
CZK Czech Koruna
DKK Danish Krone
EUR Euro
GBP Pound Sterling
HKD Hong Kong Dollar
HUF Hungarian Forint
JPY Japanese Yen
NOK Norwegian Krone
NZD New Zealand Dollar
PLN Polish Zloty
SEK Swedish Krona
SGD Singapore Dollar
USD U.S. Dollar
例如：cur=USD
*/
?>
