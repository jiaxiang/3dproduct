<?php defined('SYSPATH') or die('No direct script access.');

class Mytool_Core
{
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

	/**
	 * javaScript decode for xxs clean
	 *
	 * @param 	Str 	$code
	 * @return 	Str 	decode javaScript code
	 */
	public static function js_decode($code)
	{
		return base64_decode($code);	
	}

	/**
	 * javaScript encode for xxs clean
	 *
	 * @param 	Str 	$code
	 * @return 	Str 	encode javaScript code
	 */
	public static function js_encode($code)
	{
		return base64_encode($code);	
	}
}

