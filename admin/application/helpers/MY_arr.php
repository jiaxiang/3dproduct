<?php defined('SYSPATH') or die('No direct script access.');

class arr extends arr_Core {

	/**
	* 排序,根据$var将$Arr排序
	* $var为二维数组$Arr某个键值
	* 类似mysql的order by
	*/
	public static function sort_by_var($Arr,$var,$sort = 'DESC'){
		if(is_array($var)){
			foreach($var as $k=>$v){
				$sort_key = $k;
				$sort = $v;
			}
		}else{
			$sort_key = $var;
		}
		$varArr = array();
		if(is_array($Arr)){
			foreach ($Arr as $key => $row) {
				$varArr[$key]  = $row[$sort_key];
			}
			if($sort == 'DESC'){
				array_multisort($varArr, SORT_DESC, $Arr);
			}else{
				array_multisort($varArr, SORT_ASC, $Arr);
			}
		}
		return $Arr;
	}


    /*
     * 根据数组中的一个键值对和另一个键获取另一个值
     */
    public static function get_val_by_kvk($key,$val,$another_key,$arr = array()) {
		foreach($arr as $k=>$v)
		{
			if($v[$key] == $val)
			{
				return $v[$another_key];
			}
		}
		return NULL;
    }


 	/**
	 * 强制数组有固定的键值
	 * 例	$key_arr	= array('a','b');
	 *		$value_arr	= array('a'=>'123');
	 * 返回 $arr		= array('a'=>'123','b'=>'');


	 * 又例 $key_arr	= array('a','b');
	 *		$value_arr	= array('a'=>'123','b'=>'123','c'=>'123');
	 * 返回 $arr		= array('a'=>'123','b'=>'123');
	 */
	public static function init_arr($key_arr,$value_arr = array())
	{
		$return_arr = array();
		if(is_array($key_arr))
		{
			foreach($key_arr as $key)
			{
				$return_arr[$key] = isset($value_arr[$key]) ? $value_arr[$key] : '';
			}
		}
		return $return_arr;
	}
}
