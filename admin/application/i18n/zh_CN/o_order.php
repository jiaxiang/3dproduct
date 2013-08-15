<?php defined('SYSPATH') OR die('No direct access allowed.');

$lang = array
(
	/*
	 * order/order_export
	 */
	'order_rule_exist'              => '你有一个相同的规则存在，请不要重复添加！',
	'order_name_exist'              => '你有一个相同的名称存在，请更换名称！',
	'order_export_success'          => '配置 导出规则 成功！',
	'order_export_error'            => '配置 导出规则 失败，请重试！',
	'select_order_export'           => '请选择您要导出的订单！',
	'keep_one_order_export_config'  => '至少必须保留一个导出配置！',
	'default_config_error'          => '默认导出配置加载失败，请与管理员联系！',
	'order_export_config_error'     => '导出配置错误，请更换再试！',
	/*
	 * order/order_product
	 */
	'update_pro_price_info_success' => '修改商品与价格 成功！',
	'update_pro_price_info_error'   => '修改商品与价格 失败，请重试！',

	/*
	 * order/order
	 */
	'form_error'                           => 'Form_Error！',
	'back_order_success'                   => '回复 订单留言 成功！',
	'back_order_error'                     => '回复 订单留言 出错，请重试！',
	'order_send_success'                   => '订单 发货 成功！',
	'order_send_error'                     => '订单 发货 出错，请重试！',
	'order_back_amount_success'            => '订单退款 成功！',
	'order_back_amount_error'              => '订单退款 金额 出错，请重试！',
	'update_order_status_success'          => '修改订单状态 成功！',
	'update_order_status_error'            => '修改订单状态 出错，请重试！',
	'select_order'                         => '请选择您要处理的订单！',
	'update_order_success'                 => '成功修改订单状态！',
	'ge'                                   => '个',
	'order_ship_no_ems_num'                => '物流号不能为空！',
	'order_ship_sendnum_error'             => '发货数量错误,请重试！',
	'order_ship_no_sendnum_error'          => '发货失败，至少要发货一件商品！',
	'order_info_error'                     => '订单信息错误，请刷新重试！',
	'order_product_over'                   => '发货数量超过了可发货数量，请重试！',
	'order_product_ship_success'           => '发货成功！',
	'partial_order_product_ship_success'   => '部分发货成功',
	'order_return_num_error'               => '退货数量错误，请重试！',
	'no_return'                            => '退货失败，至少要退货一件商品！',
	'return_over'                          => '退货失败，退货数量超过了发货数量',
	'partial_order_product_return_success' => '部分退货成功！',
	'order_product_return_success'         => '退货成功！',
	'refund_amount_not_exceed_paid'        => '退款失败，退款金额不能大于支付金额！',
	'refund_amount_error'                  => '退款失败，退款金额应该为大于0的数字！',
	'refund_error'                         => '退款失败，请重试！',
	'refund_success'                       => '退款成功！',
	'update_status_success'                => '更新状态成功！',
	'update_status_error'                  => '更新状态失败！',
	'no_payment_method'                    => '支付方式错误，请重试！',
	'order_log_error'                      => '更新失败，订单日志未能生成，请重试！',
	'order_pay_success'                    => '订单支付成功！',
	'order_pay_error'                      => '订单支付失败！',
	'order_product_can_not_return'         => '当前订单不能退货，请确认订单发货了才能退货！',
	'order_product_can_not_ship'           => '当前订单不能发货，请确认当前订单是否已经发货！',
	'order_can_not_pay'                    => '当前订单不能更新为支付状态，请确认当前订单未支付！',
	'order_can_not_refund'                 => '当前订单不能退款，请确认当前订单已支付！',
	'order_can_not_cancel'                 => '订单：%s 不能废除，请确认后重试！',
	'order_cacel_error'                    => '订单：%s 废除失败，请确定认后重试！',
	'batch_cancel_success'                 => '订单批量废除成功！',
	'order_can_not_shipping_processing'    => '订单：%s 不能配货，请确认后重试！',
	'batch_success'                        => '批量操作成功！',
	'doc_error'                            => '生成单据失败，请检查后重试！',
	'carrier_price_rule'                   => '物流价格应为正数！',
	/*
	 * order/order_add
	 */
	 'email_wrong'						   => '用户邮箱地址错误，请检查后重试！',
	 'user_not_exist'                      => '用户不存在，请确认后重试！',
	 'user_not_active'                     => '用户账号已被停用，请确认后重试！',
	 'no_carrier'                          => '所在站点不存在物流,无法添加订单！',
	 'no_country'                          => '所在站点不存在发货国家,无法添加订单！',
	 'data_trans_wrong'                    => '添加订单失败，请重试！',
	 'user_address_wrong'                  => '用户地址信息错误，请检查后重试！',
	 'add_order_success'                   => '添加 订单信息 成功,订单号为：',
	 'add_order_wrong'                     => '添加 订单信息 出错，请重试！',
	 'change_site'                         => '请重新添加订单！',

	/*
	 * order/order_doc
	 */

	 'select_payment_export'               => '请选择您要导出的收款单！',
	 'export_config_error'				   => '导出配置错误，请更换再试！',
	 'order_status_success'                => '订单支付成功',
	 'order_status_error'                  => '订单支付错误，请确认该订单已支付后再试！',
	 'order_payment_data_error'            => '收款单数据错误，请确认后再试！',
	 'product_ship_load_error'             => '发货单商品读取错误，请确认后重试！',
	 'product_return_load_error'           => '退货单商品读取错误，请确认后重试！',
	 'store_update_failed'				   => ' 库存更新失败！',

);
