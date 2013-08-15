<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Number helper class.
 *
 * $Id: num.php 3769 2008-12-15 00:48:56Z zombor $
 *
 * @package    Core
 * @author     Kohana Team
 * @copyright  (c) 2007-2008 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class helper_Core {

	/**
	 * Round a number to the nearest nth
	 *
	 * @param   integer  number to round
	 * @param   integer  number to round to
	 * @return  integer
	 */
	public static function dump($val) {
		echo '<pre>';
		var_dump($val);
		echo '</pre>';
	}

} // End num