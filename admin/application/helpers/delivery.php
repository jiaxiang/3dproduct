<?php defined('SYSPATH') or die('No direct script access.');

class Delivery_Core {

	 /**
	 * 生成默认的公式
	 * 
	 * @param  int $first_unit
	 * @param  int $continue_unit
	 * @param  int null $first_price
	 * @param  int null $continue_price
	 * @return string $expression
	 */
    public static function create_exp($first_unit, $continue_unit, $first_price=1, $continue_price=1) {
        $expression = '';
        
        $expression .= '{{w-0}-0.4}*{{{'.$first_unit.'-w}-0.4}+1}*'.$first_price;
        $expression .= '+ {{w-'.$first_unit.'}-0.6}*[(w-'.$first_unit.')/'.$continue_unit.']*'.$continue_price;
        return $expression;
    }   
}