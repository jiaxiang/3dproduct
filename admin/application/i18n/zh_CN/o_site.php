<?php defined('SYSPATH') OR die('No direct access allowed.');

$lang = array
(
    'doc_parent_category_cannot_self'                  => '上级分类不能选择自身！',
	'update_site_config_error'                         => '不能写入站点配置文件，请检查系统system/config目录是否有读写权限！',

	/*
	 * site/carrier_country
	 */
	'carrier_country_has_exist'                         => '这个物流国家关联已经存在，请不要重复添加！',

	/*
	 * site/carrier_country
	 */
	'check_carrier_range'                               => '请检查您所添加的区间范围！',
	'carrier_range_begin_overlap'                       => '这个物流区间开始重叠！',
	'carrier_range_end_overlap'                         => '这个物流区间结束重叠',
	
	/*
	 * site/carrier_country
	 */
	'add_success_goto_add_carrier'                      => '添加基本成功，继续添加物流对应的物流区间！',
	'add_success_goto_add_country'                      => '添加基本成功，继续添加物流对应的国家！',
	'update_success_goto_add_carrier'                   => '编辑基本成功，继续添加物流对应的物流区间！',
	'update_success_goto_add_country'                   => '编辑基本成功，继续添加物流对应的国家！',
	'update_success_carrier'                            => '这个物流区间结束重叠！',
	'carrier_has_exist'                                 => '这个物流已经存在，请不要重复添加！',
	'check_carrier_correct'                             => '请填写正确物流区间！',
	'carrier_data_error'                                => '区间数据错误，请重试！',
	'carrier_data_error_check'                          => '区间数据错误，开始值要小于结束值，区间值也不能有重复和重叠的区间，请重试！',

	/*
	 * site/company
	 */
	'content_cannot_null'                               => '内容不能为空！',

	/*
	 * site/config
	 */
	'title_cannot_null'                                 => '站点标题不能为空！',
	'robots_cannot_null'                                => 'robots信息不能为空,请重试！',
	'config_not_exist'                                  => '无可配置信息！',
	'pic_type_incorrect'                                => '图片类型不正确，只能上传.jpg格式的图！',
	'pic_size_out_range'                                => '图片大小超过限制,请编辑后重传！',
	'file_type_error'                                   => '文件类型错误，请重试！',
	'logo_success_upload'                               => 'Logo 上传成功！',
	'logo_error_upload'                                 => 'Logo 上传失败！',
	'site_config_payment_success_cannot_submit'           => '支付成功代码为空不能提交，请检查后重试！',

	/*
	 * site/country
	 */
	'country_has_exist'                                 => '这个国家已经存在，请不要重复添加！',
    'delete_country_success'                            => '删除国家成功',
    'delete_country_error'                              => '国家：%s 删除失败，请确定认后重试！',
	/*
	 * site/curreny
	 */
	'current_not_exist'                                 => '站点必须有一种可用币种！',
	'current_has_exist'                                 => '这个币种已经存在，请不要重复添加！',
	'default_current_cannot_delete'                     => '不能删除默认币种，请重试！',
    'delete_currency_success'                           => '删除币种成功',
    'delete_currency_error'                             => '币种：%s 删除失败，请确认后重试！',
	/*
	 * site/doc_category
	 */
	'doc_category_cannot_null'                          => '分类名称不能为空！',
	'doc_category_not_exist'                            => '不存在的页面分类！',
	'doc_category_has_exist'                            => '分类名称重复！',
    'delete_doc_success'                                => '删除文案成功',
    'delete_doc_error'                                  => '文案：%s 删除失败，请确认后重试！',

	/*
	 * site/mail
	 */
	'check_mail_template'                               => '操作错误,非本站邮件模板！',
	'mail_template_not_exist'                           => '邮件模板不存在！',
	'check_mail_category'                               => '邮件分类错误，请删除后重新设定邮件模板！',
    'delete_mail_success'                               => '删除邮件模板成功',
    'delete_mail_error'                                 => '邮件模板：%s 删除失败，请确认后重试！',

	/*
	 * site/scan
	 */
	'scan_has_exist'                                    => '站点报告已经存在,如果想更新报告，请先删除旧的报告！',
	'scanning'                                          => '站点正在扫描中...请稍等片刻在查看报告！',
	'scan_site_not_exist'                               => '站点不存在！',
	'scan_site_has_exist'                               => '站点已经存在,若想重新生成报告，请先删除站点后在添加！',

	/*
	 * site/seo_manage
	 */
	'seo_domain_cannot_null'                            => '域名输入错误 请重试！',
	'product_request_check'                             => '产品数据过多，请联系管理员处理！',

	/*
	 * site/sitemap
	 */
	'product_id_format_check'                           => '不包含的产品ID列表格式错误！',
	'sitemap_error_handle'                              => '重建失败，请稍候重试！',
	'sku_update_error'                                  => '更新失败！<br/>SKU:',
	'sku_update_success'                                => '更新成功！',
	'name_update_error'                                 => '更新失败！<br/>名称:',
	'name_update_success'                               => '更新成功！',
    /*
     * site/faq
     */
    'delete_faq_success'                                => '删除faq模板成功',
    'delete_faq_error'                                  => 'faq：%s 删除失败，请确认后重试！',

    /*
     * site/menu
     */
    'delete_menu_success'                               => '删除导航成功',
    'delete_menu_error'                                 => '导航：%s 删除失败，请确认后重试！',
	'url_exist'                                 		=> 'url已经存在，请不要重复添加！',
    /*
     * site/link
     */
    'delete_link_success'                               => '删除友情链接成功',
    'delete_link_error'                                 => '友情链接：%s 删除失败，请确认后重试！',
    /*
     * site/carrier
     */
    'delete_carrier_error'                              => '物流：%s 删除失败，请确认后重试！',
    'delete_carrier_success'                            => '删除物流成功！',
    /*
     * site/news
     */
    'delete_new_success'                                => '删除新闻成功',
    'delete_new_error'                                  => '新闻：%s 删除失败，请确认后重试！',
);	
?>