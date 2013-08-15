<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Crond_Driver
 * 
 * @package   
 * @author B2C_BASE
 * @copyright qinbin
 * @version 2010
 * @access public
 */
abstract class Auto_order_Driver {


	abstract protected function check($to,$subject,$message,$from);



}