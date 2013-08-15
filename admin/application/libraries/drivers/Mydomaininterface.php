<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Mydomaininterface_Driver {
	/**
	 * check domain
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @return Boolean
	 */
	abstract public function check($sld,$tld);

	/**
	 * check domain
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @return Boolean
	 */
	abstract public function purchase($sld,$tld);
	
	/**
	 * get domain dns info
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @return Boolean
	 */
	abstract public function get_dns($sld,$tld);

	/**
	 * get domain host info
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @return Boolean
	 */
	abstract public function get_hosts($sld,$tld);

	/**
	 * set domain hosts
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @param $data eg. .com
	 * @return Boolean
	 */
	abstract public function set_hosts($sld,$tld,$data);

	/**
	 * set domain name server
	 *
	 * @param $sld eg. 126
	 * @param $tld eg. .com
	 * @param $data eg. .com
	 * @return Boolean
	 */
	abstract public function set_ns($sld,$tld,$data);

	/**
	 * set interface account
	 *
	 * @param $username account username
	 * @param $password account password
	 */
	abstract public function account($username = NULL,$password = NULL);
}
