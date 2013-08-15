<?php defined('SYSPATH') or die('No direct script access.');

class currency_Core {
	private static $data;
    public static function config() {
        $tems = Mycurrency::instance()->currencies();
        $currency_config = array();
        foreach($tems as $key=>$rs){
            $currency_config[$rs['code']] = $rs['rate'];
        }
        return $currency_config;
    }

    public static function quote_xe($to, $from = 'USD') {
        if (!isset(self::$data[$to]))
        {
			@set_time_limit(600);
			$url = 'http://www.xe.net/ucc/convert.cgi';
			$data = 'Amount=1&From=' . $from . '&To=' . $to;

			$page = tool::curl_pay($url,$data);

			$page = explode("\n", $page);

			if (is_object($page) || $page !='') {
				$match = array();
				preg_match('/[0-9.]+\s*' . $from . '\s*=\s*([0-9.]+)\s*' . $to . '/', implode('', $page), $match);
				if (sizeof($match) > 0) {
					self::$data[$to] = $match[1];
				} else {
					self::$data[$to] = false;
				}
			}
        }
        return self::$data[$to];

    }

    public static function corn_quote($from = 'quote_xe' , &$message = '') {

        $currencies = Mycurrency::instance()->currencies();
        foreach($currencies as $key=>$rs){
            if(strlen($rs['code']) == 3){
                $currency_data = array();

				$rate = currency::quote_xe($rs['code']);
				$currency_data['conversion_rate'] = round((1/$rate),5);

                $currency = Mycurrency::instance($rs['id'])->edit($currency_data);
                $message = '';
                if($currency)
                {
                    $message .= $rs['code'].' 汇率更新成功<br>';
                }
            }
        }
        return true;
    }
}

?>
