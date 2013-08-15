<?php defined('SYSPATH') OR die('No direct access allowed.');

class Affiliate_orderService_Core extends DefaultService_Core{
	/* 兼容php5.2环境 Start */ 
    private static $instance = NULL; 
    // 获取单态实例 
    public static function get_instance() 
    {
        if(self::$instance === null){ 
            $classname = __CLASS__; 
            self::$instance = new $classname(); 
        }
        return self::$instance; 
    }
    /**
     * 保存联盟订单
     *
     * @param array $order
     * @param array $affiliate
     * @return string
     */
    public function save_affiliate_order($order, $affiliate){
    	//计算IP
		$f = intval( $order['ip']/(256*256*256) );
		$f_r = $order['ip']-256*256*256*$f;
		$s = intval( $f_r/(256*256) );
		$s_r = $f_r-256*256*$s;
		$t = intval( $s_r/256 );
		$t_r = $s_r-256*$t;
		$ip = "$f.$s.$t.$t_r";
		
		/* 联盟订单记录 start */
		$query_struct = array(
			'affiliate_id'	 => $affiliate['affiliate_id'],
			'affiliate_name' => $affiliate['affiliate_name'],
			'site_id'		 => $order['site_id'],
			'site_name'		 => $_SERVER['HTTP_HOST'],
			'order_num'      => $order['order_num'],
			'order_amount'	 => $order['total_product'],
			'currency'       => $order['currency'],
			'order_time'	 => $order['date_add'],
			'user_ip'        => $ip,
			'user_country'   => $order['billing_country']);
		
		//判断订单是否有记录防止重复发送
		$arr = Affiliate_orderService::get_instance()->query_row( array( 'where'=>array('order_num'=>$order['order_num']) ) );
		if ( !empty($arr) ) {
			return '<!-- this order has been recorded before -->';
		}
		$orm_instance = ORM::factory('affiliate_order');
		$data = $orm_instance->as_array();
        foreach ($query_struct as $key=>$val) {
            array_key_exists($key,$data) && $orm_instance->$key = $val;
        }
        $orm_instance->save();
        return 1;
    }
}