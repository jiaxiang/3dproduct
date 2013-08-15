<?php defined('SYSPATH') or die('No direct script access.');

	class add_money_Core {
		private static $instance = NULL;
		private static $on_off = true;
		private static $sp_userid = array(1,30,53,91,882,1531,1691,1320,1061,1697);
		private static $no_userid = array(70,91,92,93,102,1776);
		public static function get_instance(){
	        if(self::$instance === null){
	            $classname = __CLASS__;
	            self::$instance = new $classname();
	        }
	        return self::$instance;
	    }
	    
	    public function on_off() {
	    	return self::$on_off;
	    }
	    
	    /**
	     * 判断是否需要奖励彩金
	     * Enter description here ...
	     * @param unknown_type $userid
	     */
	    public function is_no_user($userid) {
	    	$agentobj = Myagent::instance();
	    	$agent = $agentobj->get_by_user_id($userid);
	    	if ($agent == false) {
	    		$is_agent = false;
	    	}
	    	else {
	    		if ($agent['flag'] == 2) {
	    			$is_agent = true;
	    		}
	    		else {
	    			$is_agent = false;
	    		}
	    	}
	    	if ($is_agent == false) {
	    		$relationobj = Myrelation::instance();
	    		$relation = $relationobj->is_client_user($userid);
	    		if ($relation == false) {
	    			$is_agent = false;
	    		}
	    		else {
	    			$is_agent = true;
	    		}
	    	}
			if (!in_array($userid, self::$no_userid) && $is_agent == false) {
				return true;
			}
			else {
				return false;
			}
	    }
	    
	    /**
	     *  1.大于199小于等于999元，充值一次送5元
	     *  2.大于999小于等于9999元，充值一次送50元
	     *  3.大于9999，充值一次送500元
	     * @param unknown_type $money
	     */
	    public function pay_add_money($userid, $money) {
	    	$lan = Kohana::config('lan');
        	$moneyobj = user_money::get_instance();
	    	$add_money = 0;
	    	if ($money >= 200 && $money < 1000) {
	    		$add_money = 5;
	    	}
	    	elseif ($money >= 1000 && $money < 10000) {
	    		$add_money = 50;
	    	}
	    	elseif ($money >= 10000) {
	    		$add_money = 500;
	    	}
	    	else {
	    		$add_money = 0;
	    	}
	    	if ($money == 0.01) $add_money = 0.01;
	    	$add_money = 0;
	    	if ($this->on_off() == false) {
	    		if (in_array($userid, self::$sp_userid) && $add_money > 0) {
	    			$order_num = date('YmdHis').rand(0, 99999);
        			$moneys['FREE_MONEY'] = $add_money;
        			$moneyobj->add_money($userid, $add_money, $moneys, 7, $order_num, $lan['money'][22]);
	    		}
	    	}
	    	else {
	    		if ($this->is_no_user($userid) == true && $add_money > 0) {
	    			$order_num = date('YmdHis').rand(0, 99999);
        			$moneys['FREE_MONEY'] = $add_money;
        			$moneyobj->add_money($userid, $add_money, $moneys, 7, $order_num, $lan['money'][22]);
	    		}
	    	}
	    }
	    
	    /**
	     * 1.大于等于500小于3000元 中500送50
	     * 2.大于等于3000小于8000元 中3000送300
	     * 3.大于等于8000元 中8000送800
	     * 竞彩足球中奖加奖
	     * @param unknown_type $money
	     */
	    public function get_bonus_add_money($bonus) {
	    	$add_money = 0;
	    	if ($bonus >= 500 && $bonus < 3000) {
	    		$add_money = 50;
	    	}
	    	elseif ($bonus >= 3000 && $bonus < 8000) {
	    		$add_money = 300;
	    	}
	    	elseif ($bonus >= 8000) {
	    		$add_money = 800;
	    	}
	    	else {
	    		$add_money = 0;
	    	}
	    	$add_money = 0;
	    	return $add_money;
	    }
	    
	    /**
	     * 中奖金额≥1000元，均可以得到10%的加奖
	     * @param unknown_type $money
	     * 竞彩篮球中奖加奖
	     */
	    public function get_bonus_add_money_jclq($bonus) {
	    	$add_money = 0;
	    	if ($bonus >= 1000) {
	    		$add_money = $bonus * 0.1;
	    	}
	    	else {
	    		$add_money = 0;
	    	}
	    	$add_money = 0;
	    	return $add_money;
	    }
	    
	    /**
	     * 加奖金额=购彩本金*8%
	     * @param unknown_type $money
	     * 任9中奖加奖
	     */
	    public function get_bonus_add_money_zcr9_1($bonus) {
	    	$add_money = 0;
	    	if ($bonus > 0) {
	    		$add_money = $bonus * 0.08;
	    	}
	    	else {
	    		$add_money = 0;
	    	}
	    	$add_money = 0;
	    	return $add_money;
	    }
	    
	    /**
	     * 中八场返还金额=购买本金*10%
	     * @param unknown_type $money
	     * 任9中8场加奖
	     */
	    public function get_bonus_add_money_zcr9_2($bonus) {
	    	$add_money = 0;
	    	if ($bonus > 0) {
	    		$add_money = $bonus * 0.1;
	    	}
	    	else {
	    		$add_money = 0;
	    	}
	    	$add_money = 0;
	    	return $add_money;
	    }
	    
	    /**
	     * 发放奖金
	     * @param unknown_type $userid
	     * @param unknown_type $add_money
	     * @param unknown_type $plan_basic_id
	     */
	    public function bonus_add_money($userid, $add_money, $plan_basic_id) {
	    	$lan = Kohana::config('lan');
        	$moneyobj = user_money::get_instance();
	    	if ($this->on_off() == false) {
	    		if (in_array($userid, self::$sp_userid) && $add_money > 0) {
	    			$order_num = date('YmdHis').rand(0, 99999);
        			$moneys['FREE_MONEY'] = $add_money;
        			$moneyobj->add_money($userid, $add_money, $moneys, 7, $order_num, $lan['money'][23].'订单ID:'.$plan_basic_id);
	    		}
	    	}
	    	else {
	    		if (!in_array($userid, self::$no_userid) && $add_money > 0) {
	    			$order_num = date('YmdHis').rand(0, 99999);
        			$moneys['FREE_MONEY'] = $add_money;
        			$moneyobj->add_money($userid, $add_money, $moneys, 7, $order_num, $lan['money'][23].'订单ID:'.$plan_basic_id);
	    		}
	    	}
	    }
	}
?>