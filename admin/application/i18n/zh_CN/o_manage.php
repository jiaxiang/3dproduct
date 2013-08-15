<?php defined('SYSPATH') OR die('No direct access allowed.');

$lang = array
(
	/*
	 * manage/action
	 */
	'resources_name_exist'            => '资源名称重复,更新资源名称再试！',
	'resources_not_exist'             => '资源不存在！',
	'parent_action_is_self'           => '上级资源不能选择自身！',
	'parent_action_is_child'          => '上级资源不能选择子项！',

	/*
	 * manage/domain
	 */
	'domain_has_exist'                => '域名已经存在，请重试！',
	'domain_has_been_registered'      => '域名已经被注册，请重试！',
	'domain_registered_error'         => '域名注册失败，请重试！',
	'domain_not_exist'                => '域名不存在！',
	'communication_failed'            => '通信失败，请刷新重试！',
    'delete_domain_success'           => '删除域名成功',
    'delete_domain_error'             => '域名：%s 删除失败，请确定认后重试！',

	/*
	 * manage/mail_category
	 */
	'category_mark_exist'             => '邮件分类标记已经存在 ，请重试！',
	'category_not_exist'              => '邮件分类不存在！',
    'delete_mail_category_success'    => '删除邮件类型成功',
    'delete_mail_category_error'      => '邮件类型：%s 删除失败，请确定认后重试！',
	/*
	 * manage/mail_template
	 */
	'template_not_exist'              => '邮件模板不存在！',
    'delete_mail_template_success'    => '删除邮件模板成功',
    'delete_mail_template_error'      => '邮件模板：%s 删除失败，请确定认后重试！',
	/*
	 * manage/manager
	 */
	'child_same_parent'               => '子帐号与父账号相同，禁止调整！',
	'only_root_do'                    => '只允许root帐号操作，请与root联系！',
	'only_admin_do'                   => '只允许管理员操作，请与管理员联系！',
	'name_has_exist'                  => '用户名已经被使用,请更换其他的名称！',
	'email_has_exist'                 => '邮箱已经被使用,请更换邮箱再试！',
	'two_pwd_not_valid'               => '两次密码输入不一致！',
	'child_not_son_account'           => '子帐号不能再次添加子帐号！',
	'user_not_exist'                  => '用户不存在！',
	'self_account'                    => '本帐号只能有',
	'num_site'                        => ' 个站点！',
	'select_site'                     => '请选择站点！',
	'pwd_is_incorrect'                => '原密码错误！',
	'self_account_not_do'             => '自身帐号不能操作！',
	'password_length_error'           => '密码长度最少为6个字符！',

    'delete_manager_success'          => '删除账号成功',
    'delete_manager_error'            => '账号：%s 删除失败，请确定认后重试！',
	'self_can_not_set_self_site'      => '账号自己不能调整自己可管理站点，请与管理员联系！',
	'username_can_not_repeat'         => '用户名已经存在，请更换再试！',
	'manager_edit_load_error'         => '用户信息出错，请联系管理员处理！',

	/*
	 * manage/menu
	 */
	'menu_has_exist'                  => '菜单已经存在，请重试！',
	'mark_has_exist'                  => '标记已经存在，请重试！',
	'menu_can_not_add_level'          => '添加失败，最多只能添加三级菜单！',

	/*
	 * manage/message
	 */
	'message_success'                  => '留言回复成功！',
	'message_error'                    => '留言回复失败，请重试！',
	'message_edit_success'             => '留言修改成功！',
	'message_edit_error'               => '留言修改失败，请重试！',
    'delete_message_success'           => '删除留言成功',
    'delete_message_error'             => '留言：%s 删除失败，请确定认后重试！',
	/*
	 * manage/notice
	 */
	'notice_not_exist'                => '公告不存在！',
    'delete_notice_success'           => '删除公告成功',
    'delete_notice_error'             => '公告：%s 删除失败，请确定认后重试！',
	/*
	 * manage/payment
	 */
	'form_error'                      => 'Form_Error！',
	'you_have_this_payment'           => '你已经有这个支付了，请不要重复添加！',
    'delete_payment_type_success'     => '删除支付类型成功',
    'delete_payment_type_error'       => '支付类型：%s 删除失败，请确定认后重试！',
    'delete_payment_success'          => '删除支付成功',
    'delete_payment_error'            => '支付：%s 删除失败，请确定认后重试！',

	/*
	 * manage/role
	 */
	'group_has_exist'                => '用户组已经存在！',
	'parent_group_type_not_match'    => '上级用户组与选择的类型冲突！',
	'parent_group_is_self'           => '上级分组不能选择自身！',
	'group_not_exist'                => '用户组不存在！',
	'select_user_role'               => '请选择用户权限！',

	/*
	 * manage/site_group
	 */
	'site_group_has_exist'           => '分组已经存在，请重试！',
	'site_group_not_exist'           => '分组不存在，请重试！',

	/*
	 * manage/site_payment
	 */
	'add_site_payment_success'       => '添加 新站点支付 成功！',
	'add_site_payment_error'         => '添加 新站点支付 出错，请重试！',
	'delete_site_payment_success'    => '删除 站点支付 成功！',
	'delete_site_payment_error'      => '删除 站点支付 出错，请重试！',
	'update_payment_position_success'=> '更改 支付 排序成功！',
	'update_payment_position_error'  => '更改 支付 排序出错，请重试！',

	/*
	 * manage/site_type
	 */
	'site_type_not_exist'            => '站点类型不存在，请重试！',

	/*
	 * manage/site
	 */
	'site_has_exist'                 => '站点已经存在！',
	'site_not_exist'                 => '站点不存在，请重试！',
	'close_monitor_success'          => '关闭监控成功！',
	'close_monitor_error'            => '关闭监控失败！',
	'into_site'                      => '进入管理站点！',
	'cannot_manage_this_site'        => '无法管理本站点！',
	'out_site_success'               => '切出站点成功！',
	'close_monitor_error'            => '关闭监控失败！',
    'delete_site_success'            => '删除站点成功',
    'delete_site_error'              => '站点：%s 删除失败，请确定认后重试！',

	/*
	 * manage/theme
	 */
	'theme_not_exist'                => '模板不存在，请重试！',
	'pic_size_out_range'             => '图片大小超过限制,请编辑后重传！',
	'theme_id_has_exist'             => '主题ID已经存在,请重新添加！',
	'theme_id_not_exist'             => '主题ID不存在，请重试！',
	'theme_key_not_exist'            => '非法操作,主题标识不存在！',
	'to_upgrade'                     => '暂时不开发自选择模板功能,如需要设置,请与管理员联系！',
	'pic_type_incorrect'             => '图片类型不正确，只能上传gif, png, jpg等格式的图！',
	'key_conflict'                   => '标识冲突,请更换标识再试！',
	'out_site_success'               => '切出站点成功！',
	'close_monitor_error'            => '关闭监控失败！',
	'delete_theme_success'           => '模板删除成功！',
	'manage_theme_delete_failed_by_site' => '主题关联了站点后不能删除，请取消关联后再试！',
	'theme_config_delete_theme_used' => '模板已经被站点使用，不能删除！',

    /*
     * manage/user_log
     */
    'delete_user_log_success'        => '删除操作日志成功',
    'delete_user_log_error'          => '操作日志：%s 删除失败，请确定认后重试！',

	/*
	 * manage/country_manage
	 */
	'country_name_has_exists'        => '国家的英文名称已经存在，请重新输入！',
	'country_name_manage_has_exists' => '国家的中文名称已经存在，请重新输入！',
	'country_iso_code_has_exists'    => '国家的简码已经存在，请重新输入！',
	'country_not_exist'              => '国家不存在！'

);
