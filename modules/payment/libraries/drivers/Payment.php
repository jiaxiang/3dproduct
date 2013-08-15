<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Payment driver interface
 *
 * $Id: payment.php 3769 2010-02-02  flyC $
 *
 */
/* 自定义支付异常 */
class PaymentException extends MyRuntimeException{}

abstract class Payment_Driver {
	/**
	 * 设置支付信息
	 */
	abstract protected function set($fileds);

	/**
	 * 提交支付处理
	 */
	abstract protected function handle();

}