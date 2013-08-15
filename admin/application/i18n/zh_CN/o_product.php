<?php defined('SYSPATH') OR die('No direct access allowed.');

$lang = array
(
	/*
	 * product/attribute_group
	 */
	'delete_own_attribute_value'              => '规格组对应有规格值，请先删除其下的规格值！',
	'select_attribute'                        => '请选择要删除的规格组！',
	'have'                                    => '有',
	'num_attribute_cannot_delete'             => ' 个规格组无法删除,请选择单个规格组删除！',
	'success_delete'                          => '成功删除',
	'num_attribute'                           => '个规格组',

	/*
	 * product/attribute
	 */
	'file_count_limit'                        => '图片数量超过限制：',
	'file_size_total_limit'                   => '图片总体积超过限制：',
	'size'                                    => ' size:',
	'file_type_invalid'                       => '图片类型错误：',
	'file_not_uploaded'                       => '图片上传失败：',
	'file_size_prelimit'                      => '图片体积超过限制：',
	'index'                                   => ' index:',
	'have_relative_data_cannot_delete'        => '该图片已被关联，请取消关联之后重试！',

	/*
	 * product/attribute
	 */
    'brand_name_exists'                       => '品牌名称已存在！',
	'uri_name_has_exists'                     => '商品 URL 已存在！',
	'good_sku_has_exists'                     => '货品 #%s SKU 与其他货品重复！',
	'add_product_success'                     => '商品添加成功！',
	'edit_product_success'                    => '操作成功！',
	'spec_has_exists_in_configurable'         => '规格相同的简单商品已经存在于同一个可配置商品中！',
	'spec_no_exists'                          => '不存在相同规格的货品',
        

	/*
	 * product/feature_group
	 */
	'add_feature_error'                       => '添加 附加属性组 失败,名称重复！',
	'add_product_category_error'              => '添加 商品类型 失败,名称重复！',
	'check_attribute_and_delete'              => '附加属性组对应有附加属性值，请检查或强行删除！',
	'num_attribute_cannot_delete'             => '个规格组无法删除,请选择单个规格组删除！',
	'success_delete'                          => '成功删除',
	'num_attribute'                           => '个规格组',

	/*
	 * product/feature_type
	 */
	'feature_not_exist'                       => '站点未添加扩展属性,请先添加完扩展属性后,再添加商品的类型！',
	'add_product_category_error'              => '添加 商品类型 失败,名称重复！',

	/*
	 * product/classify
	 */
	'product_classify_exist'                  => ' 商品类型 名称重复，请重试！',

	/*
	 * product/product
	 */
	'first_exist_category'                    => '站点 %s 下尚未添加任何商品分类，请首先添加商品分类',
	'sku_has_exist'                           => '商品 SKU 不可重复！',
	'good_sku_has_exist'                      => '货品 SKU 不可重复！',
	'uri_name_has_exist'                      => '商品 URL 不可重复！',
	'keep_one_more_good'                      => '至少需要保留一个货品！',
	'file_upload_error'                       => '文件上传失败！',
	'file_size_not_flow'                      => '文件体积不可超过：',
	'pic_type_error'                          => '错误的图片类型！',
	'pic_has_relation'                        => '该图片已被关联，请取消关联之后重试！',
	'pic_upload_failed'                       => '图片上传失败，可能图片体积过大！',
	'over_max_delete_range'                   => '批量删除最多可以删除99条，请重试！',

	/*
	 * product/virtualcategory
	 */
	'vir_category_url_has_exist'              => '添加 分类 失败,分类前台URL重覆！',
	'file_size_out_range'                     => '文件大小超限，请上传1MB以下图片！',
	'select_vir_category'                     => '请选择要删除的分类，请重试！',
	'num_vir_category_cannot_delete'          => ' 个分类没法删除,请选择单个分类删除！',
	'num_vir_category'                        => '个分类',
	'select_vir_category_product'             => '请选择要删除的分类商品，请重试！',
	'num_category_product_cannot_delete'      => ' 个分类商品关联没法删除,请选择单个商品删除！',
	'num_category_product'                    => ' 个分类商品关联',
	'update_vir_category_success'             => '编辑 分类 成功 ， 共添加了',
	'num_category_product_update'             => ' 个商品',
	'select_category_about'                   => '请选择要删除的分类属性关联值！',
	'num_category_about_cannot_delete'        => ' 个分类属性关联值没法删除,请选择单个分类属性关联值删除！',
	'num_category_about'                      => ' 个分类属性关联值',

	/*
	 * product/virtualcategory
	 */
	'category_title_has_exists'               => '分类名已经存在，请重新输入！',
	'category_title_manage_has_exists'        => '管理名称已经存在，请重新输入！',

	/* comment */
	'delete_success'                          => '删除成功！',

	'pic_set_default_ok'                      => '默认图片设置成功！',
        
	'phprpc_pic_save_failed'                  => '图片存储失败，请稍后重新尝试！',

	'export_pdt_not_found'                    => '未找到符合条件的商品！',

	'export_cte_tmpdir_failed'                => '创建临时目录时产生错误，商品导出失败！',

	'export_wte_tmp_failed'                   => '写入商品临时文件时产生错误，商品导出失败！',

	'import_cte_tmpdir_failed'                => '创建解压缩目录时产生错误，商品导入失败！',

	'import_wte_tmp_failed'                   => '解压缩上传文件时产生错误，商品导入失败！',

	'import_csv_not_found'                    => '压缩包中未找到商品 CSV 文件，商品导入失败！',

	/*
	 * product/inquirysubject
	 */
	'subject_has_exists'                      => '商品咨询主题已经存在，请重新输入！',
        
	/*
	 * product/collection
	 */
	'collection_title_has_exists'             => '商品专题名称已经存在，请重新输入！',
        
	/**
	 * product/post
	 */
	'no_save'                                 => '商品信息保存失败，请再试一次。',
	'not_select_binding_goods'                => '没有选择需要绑定的商品。',
);