
var submitHandlers = [];

/**
 * 商品表单验证
 */
$(document).ready(function(){
	var checkDefaultGoodInfo = function() {
		return $('#product_good_default_info_box').is(':visible');
	}
	
	//$('#edit_form').submit(function(){alert(1);return false;});
	
	$.validator.addMethod('pdt_store', function(value, element, params){
		if (value === '-1' || /^\d+$/.test(value) || value === '') {
			return true;
		} else {
			return false;
		}
	});
	
	$('#edit_form').validate({
		errorClass: 'error',
		//wrapper: 'p',
		//errorLabelContainer: $('#validate_error_message'),
		focusCleanup: false,
		submitHandler: function(form){
			/**
			 * 表单提交钩子
			 */
			if (submitHandlers.length > 0) {
				for (var i = 0; i < submitHandlers.length; i ++) {               
					if (!submitHandlers[i](form)) {
						return false;
					}
				}
			}
            
			ajax_block.open();
			form.submit();
		},
		rules: {
			title: {
				required: true,
				maxlength: 100
			},
			name_manage: {
				//required: true,
				maxlength: 255
			},
			product_point: {
				digits: true
			},
			weight: {
				digits: true,
				max : 999999
			},
			brief: {
				maxlength: 255
			},
			meta_title: {
				maxlength: 100
			},
			meta_keywords: {
				maxlength: 255
			},
			meta_description: {
				maxlength: 255
			},
			uri_name : {
				remote: url_base + 'product/product/uri_name_exists?id=' + $('#id').val()
			},
			sku: {
				required: true,
				remote: url_base + 'product/product/sku_exists?id=' + $('#id').val()
			},
			price: {
				required: checkDefaultGoodInfo,
				number: true,
				min: 0,
				max: 9999999.99
			},
			market_price: {
				required: checkDefaultGoodInfo,
				number: true,
				min: 0,
				max: 9999999.99
			},
			cost: {
				number: true,
				min: 0,
				max: 9999999.99
			},
			store: {
				pdt_store: true
			},
			category_id: {
				required: true,
				min: 1
			},
			classify_id: {
				required: true,
				min: 1
			}
		},
		messages: {
			title: {
				required: '商品名称不可为空',
				maxlength: '商品名称长度不可超过 100 个字符'
			},
			/*name_manage: {
				required: '商品管理名称不可为空',
				maxlength: '商品管理名称长度不可超过 255 个字符'
			},*/
			brief: {
				maxlength: '商品简介长度不可超过 255 个字符'
			},
			product_point: {
				digits: '商品点击数必须为正整数'
			},
			weight: {
				digits: '商品重量必须为正整数',
				max: '商品重量不可大于 999999'
			},
			meta_title: {
				maxlength: '商品标题（搜素引擎优化）长度不可超过 100 个字符'
			},
			meta_keywords: {
				maxlength: '商品关键字（搜索引擎优化）长度不可超过 255 个字符'
			},
			meta_description: {
				maxlength: '商品描述（搜索引擎优化）长度不可超过 255 个字符'
			},
			uri_name: {
				remote: '商品 URL 不可重复'
			},
			sku: {
				required: '商品 SKU 不可为空',
				remote: '商品 SKU 不可重复'
			},
			price: {
				required: '商品价格不可为空',
				min: '商品价格不可小于 0',
				max: '商品价格不可大于 9999999.99',
				number: '商品价格必须为数字'
			},
			market_price: {
				required: '商品市场价格不可为空',
				min: '商品市场价格不可小于 0',
				max: '商品市场价格不可大于 9999999.99',
				number: '商品市场价格必须为数字'
			},
			cost: {
				min: '商品成本价不可小于0',
				max: '商品成本价不可大于 9999999.99',
				number: '商品成本价必须为数字'
			},
			store: {
				pdt_store: '商品库存必须为大于 -1 的整数'
			},
			category_id: {
				required: '请选择商品分类',
				min: '请选择商品分类'
			},
			classify_id: {
				required: '请选择商品类型',
				min: '请选择商品类型'
			}
		}
	});
});