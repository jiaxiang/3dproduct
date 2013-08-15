<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * API内部全局操作
 */
class Mytool_Core {

	/*
	 * curl模拟post
	 *
	 * @param String $API_Endpoint
	 * @param String $nvpStr
	 * @return String
	 */
	public static function curl_post($API_Endpoint,$nvpStr) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpStr);
		$response = curl_exec($ch);
		if(curl_errno($ch)) {
			$curl_error_no 	=curl_errno($ch) ;
			$curl_error_msg	=curl_error($ch);
		}else {
			curl_close($ch);
		}
		return $response;
	}

	/*
	 * 64们机和32位机兼容的ip2long
	 */
	public static function myip2long($strIP) {
		$lngIP=ip2long($strIP);
		if ($lngIP < 0){
			$lngIP += 4294967296;
		}
		return $lngIP;
	}

 	/**
	 * 可以把 sql 中的 OR 条件变成AND并加上括号
	 * 解决or条件不能括号的问题
	 * 参数sql是完整sql语句
	 * 参数or_where是想要加括号的or参数
	 */
	public static function bracket_or_where($sql,$or_where = array())
	{
		if(!empty($or_where)){
			$last_key = array_pop(array_keys($or_where));
			$last_value = array_pop($or_where);
			$sql = preg_replace('/OR(\s+.*\s+.*(.*?\s+OR\s+.*?)*\'%*'.$last_value.'%*\')\s/i', 'AND (${1}) ', $sql);
		}
		return $sql;
	}
	
	/**
	 * hash password
	 *
	 * @param 	Str 	$password
	 * @return 	Str 	hashed password
	 */
	public static function hash($password)
	{
		return sha1($password);
	}	
	
}
