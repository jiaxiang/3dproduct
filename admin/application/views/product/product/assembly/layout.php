<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div style="margin-top: 0px; border-top: 0px none;" class="division" id="product_attributes_btn_box">
    <input type="button" value=" 选择货品 " class="ui-button ui-widget ui-state-default ui-corner-all ui-state-focus" name="ptype_simple_open" id="ptype_simple_open" role="button" aria-disabled="false">
</div>
<div id="attributes_box" class="division" style="<?php if (empty($product['goods'])) : ?>display: none;<?php endif; ?> margin-top: 0px; border-top: 0px none;">
<?php
if (isset($product['goods']))
{
	$goods = new View('product/product/assembly/goods');
	$goods->attributes = empty($product['attributes']) ? array() : $product['attributes'];
	$goods->goods      = empty($product['goods']) ? array() : $product['goods'];
	echo $goods;
}
?>
</div>
<div id="pdt_attroptrs_ifm" class="ui-dialog-content ui-widget-content" style="display:none;">
    <iframe style="border:0px;width:100%;height:95%;" frameborder="0" src="" scrolling="auto"></iframe>
</div>
<script type="text/javascript">
               
	var ptype_simple = function() {
		var classify_id = '<?php echo isset($product["classify_id"])?$product["classify_id"]:""; ?>';
		var selector = {
			'dialog': '#pdt_attroptrs_ifm',
			'box': '#attributes_box'
		}
		var aids = <?php echo json_encode(empty($product['attroptrs']) ? array() : array_keys($product['attroptrs'])); ?>;
		var attroptrs = <?php echo json_encode(empty($product['attroptrs']) ? array() : $product['attroptrs']); ?>;
		var goods = <?php echo json_encode(empty($product['goods']) ? array() : $product['goods']); ?>;
		var attributes = <?php echo json_encode(empty($product['attributes']) ? array() : $product['attributes']); ?>;
		var attributeoptions_box = null;
    	var i = goods.length + 1;
    	
        /**
         * 设置按钮等的显示与隐藏
         */
        var setBtns = function() {
        	if (typeof t != 'undefined' && t.getGoodCount() > 0) {
        		$('#ptype_simple_close').show();
        		$('#ptype_simple_aat').show();
        		$('#ptype_simple_agd').show();
                $('#attributes_box').show();
        		$('#ptype_simple_open').hide();
                $('#product_good_default_info_box').hide();
        	} else {
        		$('#ptype_simple_close').hide();
        		$('#ptype_simple_aat').hide();
        		$('#ptype_simple_agd').hide();
                $('#attributes_box').hide();
        		$('#ptype_simple_open').show();
        	    $('#product_good_default_info_box').show();
        	}
        }
        
    	/**
    	 * 获取规格项的HTML
    	 */
    	var getAttroptHTML = function(attribute_id, option_id) {
    		var txt = '';
			if (typeof attributes[attribute_id] != 'undefined') {
				var attribute = attributes[attribute_id];
				if (typeof attribute['display'] != 'undefined' && attribute['display'] == "image") {
					txt = '<img name="pdt_attr_set_option" width="23" height="23" title="' + attribute['options'][option_id]['name'] + '" src="' + attribute['options'][option_id]['image'][2] + '"/>'
				} else {
					txt = attribute['options'][option_id]['name'];
				}
			} else {
				alert('Internal error.');
			}
			return txt;
    	}
    	
		
		/**
		 * 添加新的货品
		 */
		var crtNewGood = function() {
        	var required, row = '';
			
			row += '<tr>';
			row += '	<td>';
			row += '		<input name="pdt_goods[' + i + '][is_default]" value="1" type="checkbox">';
			row += '		<b>&nbsp;&nbsp;#' + i + '</b>';
			row += '	</td>';
			row += '	<td>';
			row += '		<input name="pdt_goods[' + i + '][on_sale]" type="checkbox" checked value="1">';
			row += '	</td>';
			row += '	<td>';
			row += '		<input name="pdt_goods[' + i + '][front_visible]" type="checkbox" value="1">';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_goods[' + i + '][sku]" value="" class="text" size="12">';
			row += '	</td>';
            
			for (var aid in attributes) {
                required = (typeof attroptrs[aid] != 'undefined' && (!attroptrs[aid][0] || attroptrs[aid]=='0'))?'':'required';
				row += '<td name="pdt_attrcol_' + aid + '" class="d_line">'
				row += '	<input type="hidden" name="pdt_goods[' + i + '][attroptrs][' + aid + ']" value="" class="' + required + '"/>';
				row += '	<span name="pdt_attr_set_option" style="cursor:pointer;">';
				row += required?'<img name="pdt_attr_set_option" src="' + url_base + 'images/choose_red.gif" border="0"/>':'顾客填写项';
				row += '	</span>';
				row += '</td>';
			}
			
			row += '	<td>';
			row += '		<input type="hidden" name="pdt_goods[' + i + '][picrels]" value=""/>';
			row += '		<div style="float:left;cursor:pointer;border:0px;padding:1px;">';
			row += '			<img name="pdt_pic_set_rel" border="0" src="' + url_base + 'images/choose_black.gif">';
			row += '		</div>';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_goods[' + i + '][title]" value="" class="text" size="16" maxlength="100">';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_goods[' + i + '][cost]" value="0" class="text" size="4" maxlength="10">';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_goods[' + i + '][price]" value="0" class="text" size="4">';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_goods[' + i + '][market_price]" value="0" class="text" size="4">';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_goods[' + i + '][store]" value="0" class="text" size="4" maxlength="10">';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_goods[' + i + '][weight]" value="0" class="text" size="4" maxlength="10">';
			row += '	</td>';
			row += '	<td>';
			row += '		<img name="pdt_good_delete" src="' + url_base + 'images/icon/remove.gif" border="0" width="12" height="12" style="cursor:pointer;"></a>';
			row += '	</td>';
			row += '</tr>';
			
			row = $(row);
			
			renderValue(row);
			
			if ($(selector.box).find('tbody').length > 0) {
				$(selector.box).find('tbody').append(row);
			} else {
				$(selector.box).find('table').append(row);
			}
			
			t.setValidate(row);
			
			i ++;
        }
    	
        /**
         * 填充货品的价格、市场价、成本、重量到商品
         */
		var renderGoodInfToPDT = function(row) {
			var price = row.find('input[name$="[price]"]').val().replace(/^\s+/, '').replace(/\s+$/, '');
			if (price.length > 0) {
				$('#price').val(price);
			}
			
			var market_price = row.find('input[name$="[market_price]"]').val().replace(/^\s+/, '').replace(/\s+$/, '');
			if (market_price.length > 0) {
				$('#market_price').val(market_price);
			}
			
			var cost = row.find('input[name$="[cost]"]').val().replace(/^\s+/, '').replace(/\s+$/, '');
			if (cost.length > 0) {
				$('#cost').val(cost);
			}
			
			var weight = row.find('input[name$="[weight]"]').val().replace(/^\s+/, '').replace(/\s+$/, '');
			if (weight.length > 0) {
				$('#weight').val(weight);
			}
		}
        
		/**
		 * 填充商品的价格、市场价、成本、重量到货品
		 */
		var renderValue = function(el) {
			if (typeof el == 'undefined') {
				el = $(selector.box);
			}
            el.find('input[name$="[store]"]').val(100);
            
			var price = $('#price').val().replace(/^\s+/, '').replace(/\s+$/, '');
			if (price.length > 0) {
				el.find('input[name$="[price]"]').val(price);
			}
			
			var market_price = $('#market_price').val().replace(/^\s+/, '').replace(/\s+$/, '');
			if (market_price.length > 0) {
				el.find('input[name$="[market_price]"]').val(market_price);
			}
			
			var cost = $('#cost').val().replace(/^\s+/, '').replace(/\s+$/, '');
			if (cost.length > 0) {
				el.find('input[name$="[cost]"]').val(cost);
			}
            
			var weight = $('#weight').val().replace(/^\s+/, '').replace(/\s+$/, '');
			if (weight.length > 0) {
				el.find('input[name$="[weight]"]').val(weight);
			}
		}
		
		/**
		 * 对外发布的接口
		 */
		var t = {
    		show: function() {
                var classify_id = $('#classify_id').val();
    			var url = url_base + 'product/product/get_attroptrs?classify_id=' + classify_id;
				if (aids.length > 0) {
					url += '&aids=' + aids.join(',');
				}
                if(!classify_id || classify_id<=0) {
                    showMessage('操作失败', '<font color="#990000">请选择商品类型。</font>');
                    return;
                }
				$(selector.dialog).find('iframe').attr('src', url);
				$(selector.dialog).dialog('open');
    		},
    		hide: function() {
    			$(selector.dialog).dialog('close');
    		},
    		empty: function() {
    			aids = [];
    			attroptrs = {};
    			$(selector.box).empty();
    			setBtns();
    		},
    		isAttrRel: function(aid) {
    			return typeof attroptrs[aid] != 'undefined';
    		},
    		getAttrs: function() {
    			return attributes;
    		},
    		setAttrs: function(attrs) {
    			// 添加新的规格关联
    			var prev = 0;
    			for (var aid in attrs) {
    				if (typeof attributes[aid] == 'undefined') {
    					var cs = $(selector.box).find('*[name^="pdt_attrcol_' + prev + '"]');
    					for (var i = cs.length - 1; i >= 0; i --) {
    						var c = $(cs[i]);
    						var h = '';
    						if (cs[i].nodeName.toUpperCase() == 'TH') {
    							h += '<th style="text-align:left;" name="pdt_attrcol_' + aid + '">';
								h += '	<b>* ' + attrs[aid]['name'] + '</b>';
								h += '</th>';
    						} else {
    							var n = c.find('input').attr('name');
    							var r = n.substring(n.indexOf('[') + 1, n.indexOf(']'));
                                var required = (typeof attrs[aid] != 'undefined' && attrs[aid]['type']==1)?'':'required';
                
	    						h += '<td name="pdt_attrcol_' + aid + '" class="d_line">'
								h += '	<input type="hidden" name="pdt_goods[' + r + '][attroptrs][' + aid + ']" value="" class="' + required + '"/>';
								h += '	<span name="pdt_attr_set_option" style="cursor:pointer;">';
								h += required?'		<img name="pdt_attr_set_option" src="' + url_base + 'images/choose_red.gif" border="0"/>':'';
								h += '	</span>';
								h += '</td>';
    						}
    						h = $(h);
							c.after(h);
    					}
    				}
    				prev = aid;
    			}
    			// 删除不再关联的规格
    			for (var aid in attributes) {
    				if (typeof attrs[aid] == 'undefined') {
    					$(selector.box).find('*[name^="pdt_attrcol_' + aid + '"]').remove();
    				}
    			}
    			attributes = attrs;
    		},
    		getAttroptRels: function() {
    			return attroptrs;
    		},
    		setAttroptRels: function(aors) {
    			if (typeof aors == 'object' && $.count(aors) > 0) {
    				aids = [];
					attroptrs = {};
					for (var k in aors) {
						if (typeof aors[k] != 'undefined') {
							for (var i = 0; i < aors[k].length; i ++) {
								if (typeof aors[k][i] != 'undefined') {
    								if (typeof attroptrs[k] == 'undefined') {
    									aids.push(k);
    									attroptrs[k] = [];
    								}
    								attroptrs[k].push(aors[k][i]);
								}
							}
						}
					}
    			}
    		},
    		setGoods: function() {
    			if ($.count(attroptrs) > 0) {
    				var url = url_base + 'product/product/get_goods?product_id=' + $('#product_id').val();
                    
    				for (var aid in attroptrs) {
    					for (var k in attroptrs[aid]) {
    						url += '&attroptrs[' + aid + '][]=' + attroptrs[aid][k];
    					}
    				}
                    //w=window.open('','_blank');w.document.write(url);
    				ajax_block.open();
    				$.ajax({
    					url: url,
    					type: 'GET',
    					dataType: 'json',
    					success: function(retdat, status) {
    						ajax_block.close();//showMessage('操作失败', retdat);;return;
    						if (retdat['status'] == 1 && retdat['code'] == 200) {
    							goods = retdat['content']['goods'];
    							$(selector.box).empty();
    							$(selector.box).html(retdat['content']['tpl']);
    							$(selector.box).show();
    							setBtns();
    						} else {
    							showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    						}
    					},
    					error: function() {
    						ajax_block.close();
    						showMessage('操作失败', '<font color="#990000">请稍后重新尝试！</font>');
    					}
    				});
    			}
    		},
    		isAttroptRel: function(aid, oid) {
    			if (t.isAttrRel(aid)) {
    				oid = parseInt(oid);
    				for (var i = 0; i < attroptrs[aid].length; i ++) {
    					if (oid == parseInt(attroptrs[aid][i])) {
    						return true;
    					}
    				}
    			}
    			return false;
    		},
    		getAttrRel: function(aid) {
    			if (t.isAttrRel(aid)) {
    				return attroptrs[aid];
    			} else {
    				return [];
    			}
    		},
    		isAttroptRelGood: function(aid, oid) {
    			if (!t.isAttroptRel(aid, oid)) {
    				return false;
    			}
    			return true;
    		},
    		getGoodCount: function() {
    			var length = $(selector.box).find('table').find('tr').length;
    			if (length > 1) {
    				return length - 1;
    			} else {
    				return 0;
    			}
    		},
    		setValidate: function(o) {
    			if (typeof o == 'object') {
    				o.find('input[name$="[price]"]').rules('add', {
    					required: true,
						number: true,
						min: 0,
						max: 9999999.99,
						messages: {
							required: '不可为空',
							min: '不可小于 0',
							max: '不可大于 9999999.99',
							number: '必须为数字'
						}
    				});
    				o.find('input[name$="[market_price]"]').rules('add', {
    					required: true,
						number: true,
						min: 0,
						max: 9999999.99,
						messages: {
							required: '不可为空',
							min: '不可小于 0',
							max: '不可大于 9999999.99',
							number: '必须为数字'
						}
    				});
    				o.find('input[name$="[sku]"]').rules('add', {
    					maxlength: 32,
						messages: {
							maxlength: '长度不可超过 32 个字符'
						}
    				});
    				o.find('input[name$="[cost]"]').rules('add', {
    					number: true,
						min: 0,
						max: 9999999.99,
						messages: {
							min: '不可小于0',
							max: '不可大于 9999999.99',
							number: '必须为数字'
						}
    				});
    				o.find('input[name$="[store]"]').rules('add', {
    					pdt_store: true,
    					messages: {
    						pdt_store: '必须为大于等于 -1 的整数'
    					}
    				});
    				o.find('input[name$="[weight]"]').rules('add', {
    					digits: true,
						max: 999999,
						messages: {
							digits: '必须为正整数',
							max: '不可大于 999999'
						}
    				});
    				//o.find('input[name*="[attroptrs]"]').each(function(idx, item){
    				o.find('.required').each(function(idx, item){
    					$(item).rules('add', {
	    					required: true,
	    					messages: {
	    						required: '请选择规格值'
	    					}
	    				});
    				});
    			} else {
    				var trs = $(selector.box).find('table').find('tr');
    				if (trs.length > 0) {
    					for (var i = 1; i < trs.length; i ++) {
    						t.setValidate($(trs[i]));
    					}
    				}
    			}
    		}
    	};
    	
    	$(document).ready(function(){
    		
    		/**
    		 * 规格关联弹出框初始化
    		 */
    		$("#pdt_attroptrs_ifm").dialog({
	            title: "规格",
	            modal: true,
	            autoOpen: false,
	            bgiframe: true,
	            height: 480,
	            width: 700
	        });
	        
	        /**
	         * 开启规格按钮绑定事件
	         */
	        $('#ptype_simple_open').bind('click', function(){
	        	t.show();
	        });
	        
	        /**
	         * 添加规格按钮绑定事件
	         */
	        $('#ptype_simple_aat').bind('click', function(){
	        	t.show();
	        });
	        
	        /**
	         * 关闭规格按钮绑定事件
	         */
	        $('#ptype_simple_close').bind('click', function(){
	        	if (confirm("确定要关闭规格吗？一旦关闭，所有货品将会丢失！")) {
	        		t.empty();
	        	}
	        });
	        
	        /**
	         * 添加货品按钮绑定事件
	         */
	        $('#ptype_simple_agd').bind('click', crtNewGood);
	        
	        /**
	         * 事件冒泡方式处理货品列表的相关操作，如货品删除，设置规格值，设置默认货品等
	         */
	        $(selector.box).bind('click', function(e){
	        	var b = $(selector.box);
	        	var o = $(e.target);
	        	var n = o.attr('name');
	        	
	        	if (n != undefined) {
	        		n = n.toUpperCase();
	        		switch (n) {
	        			case 'PDT_GOOD_DELETE':
	        				if (b.find('tr').length == 2) {
	        					showMessage('操作失败', '<font color="#990000">至少需要保留一个货品！</font>');
	        					return false;
	        				}
	        				if (confirm('确定要删除该货品吗？一旦删除将无法恢复！')) {
	        					var r = o.parent().parent();
	        					var is_default = r.find('input[name$="[is_default]"]').attr('checked');
	        					if (is_default) {
	        						var ds = b.find('input[name$="[is_default]"]');
	        						for (var i = 0; i < ds.length; i ++) {
	        							if ($(ds[i]).attr('checked') == false) {
	        								$(ds[i]).attr('checked', true);
	        								renderGoodInfToPDT($(ds[i]).parent().parent());
	        								break; 
	        							}
	        						}
	        					}
	        					r.remove();
	        				}
	        				return true;
	        			/**
	        			 * 设置货品
	        			 */
	        			case 'PDT_ATTR_SET_OPTION':
	        				if (attributeoptions_box == null) {
								if (e.target.nodeName.toUpperCase() == 'IMG') {
									o = o.parent();
								}
                                
								var oid = o.prev().val();
								var nme = o.prev().attr('name');
								if (typeof nme == 'undefined') {
									var nme = o.prev().attr('for');
								}
								var aid = nme.substring(nme.lastIndexOf('[') + 1, nme.lastIndexOf(']'));
								var counts = {};
								var count  = 1;
								
								// 计算每个规格项最多可以出现多少次
								for (var i in attroptrs) {
									if (i != aid) {
										count *= attroptrs[i].length;
									}
								}
								
								for (var i = 0; i < attroptrs[aid].length; i++) {
									counts[attroptrs[aid][i]] = 0;
								}
					
								$('input[name$="[attroptrs][' + aid + ']"]').each(function(idx, item){
									var tval = $(item).val();
									if (tval != '') {
										counts[tval]++;
									}
								});
								
								var ids = new Array();
								for (var i in counts) {
                                    //alert('i='+i + ', oid='  + oid + ', aid='  + aid + ', counts['+i+']=' + counts[i] + ', count=' + count);
									//if (i != aid && counts[i] < count) {
									if (i != oid && counts[i] < count) {
										ids.push(i);
									}
								}
								if (aid != '' && oid != '') {
									ids.unshift(oid);
								}
                                
								var unavailable = true;
								if (ids.length > 0) {
									for (var i = 0 ; i < ids.length; i++) {
										if (ids[i] != 'undefined') {
											unavailable = false;
											break;
										}
									}
								}
								if (unavailable == true) {
									var attribute_name = attributes[aid]['name'];
									if (confirm('无可用' + attribute_name + '，是否添加？')) {
										$('#ptype_simple_aat').click();
									}
								} else {
									var offset = o.offset();
									var box = $('<div></div>');
									box.css('top',      offset.top);
									box.css('left',     offset.left);
									box.css('z-index',  888);
									box.css('position', 'absolute');
									box.css('border',   '1px solid #000');
									box.css('background-color', '#efefef');
									box.css('padding',  '10px');
									
									for (var i = 0; i < ids.length; i++) {
										if (ids[i] != 'undefined') {
											var span = $('<span></span>');
											span.css('cursor',  'pointer');
											span.css('margin',  '3px');
											span.css('padding', '5px');
											span.attr('name',   'b-' + ids[i]);
											span.html(getAttroptHTML(aid, ids[i]));
											span.appendTo(box);
										}
									}
									
									box.find('span').click(function(){
										var n = o.prev();
										if (typeof n.attr('name') == 'undefined') {
											n = n.prev();
										}
										o.html($(this).html());
										n.val($(this).attr('name').split('-')[1]);
										o.parent().find('label').remove();
										attributeoptions_box.remove();
										attributeoptions_box = null;
									});
                                    
									box.appendTo($(this).parent());
									attributeoptions_box = box;
								}
							}
	        				return true;
	        			case 'PDT_PIC_SET_REL':
	        				var o = o.parent();
	        				var n = o.prev().attr('name');
	        				var i = n.substring(n.indexOf('[') + 1, n.indexOf(']'));
	        				goodpicrs.show(i, function(gid, pics){
	        					o.prev().val(pics.join(','));
	        					if (pics.length > 0) {
	        						o.html('<img name="pdt_pic_set_rel" border="0" src="' + url_base + 'images/icon/picthumb.gif">');
	        					} else {
	        						o.html('<img name="pdt_pic_set_rel" border="0" src="' + url_base + 'images/icon/choose_black.gif">');
	        					}
	        				});
	        				return true;
	        			/**
	        			 * 全选设置货品上下架
	        			 */
	        			case 'PDT_GOOD_SALE_CHECKALL':
	        				var sale = o.attr('checked');
	        				var ss   = b.find('input[name$="[on_sale]"]');
	        				for (var i = 0; i < ss.length; i ++) {
	        					$(ss[i]).attr('checked', sale);
	        				}
	        				return true;
	        			/**
	        			 * 设置默认货品的处理JS
	        			 */
	        			default:
	        				if (n.indexOf('[') > 0) {
	        					if (n.substr(n.lastIndexOf('[')) == '[IS_DEFAULT]') {
	        						if (o.attr('checked') == false) {
	        							return false;
	        						} else {
	        							var is = b.find('input[name$="[is_default]"]');
	        							for (var i = 0; i < is.length; i ++) {
	        								$(is[i]).attr('checked', false);
	        							}
	        							o.attr('checked', true);
	        							renderGoodInfToPDT(o.parent().parent());
	        						}
	        					}
	        				}
	        				return true;
	        		}
	        	}
	        });
	        
	        /**
        	 * 初始化货品列表前端验证
        	 */
        	t.setValidate();
    	});
    	
    	/**
    	 * 表单提交时，首先验证货品的SKU等信息是否正确
    	 */
    	submitHandlers.push(function(){
    		var trs = $(selector.box).find('tr');
    		if (trs.length > 1) {
    			var skus = {};
    			var url = url_base + 'product/assembly/validate?product_id=' + $('#product_id').val();
    			var sign = false;
    			for (var i = 1; i < trs.length; i ++) {
    				var tr  = $(trs[i]);
    				var sku = tr.find('input[name$="[sku]"]');
    				var sn  = sku.attr('name');
    				var id  = tr.find('input[name$="[id]"]');
    				var k   = sn.substring(sn.indexOf('[') + 1, sn.indexOf(']'));
    				sku = sku.val();
    				
    				if (sku.replace(/^\s+/, '').replace(/\s+$/, '') == '') {
    					continue;
    				}
    				
    				if (id.length > 0) {
    					url += '&goods[' + k + '][id]=' + id.val();
    				}
    				
    				if (typeof skus[sku.toLowerCase()] != 'undefined') {
    					showMessage('操作失败', '<font color="#990000">货品 #' + k + ' SKU 与其他货品重复！</font>');
    					return false;
    				}
    				
    				skus[sku.toLowerCase()] = true;
    				
    				url += '&goods[' + k + '][sku]=' + sku;
    				
    				sign = true;
    			}
                
    			success = true;
    			
    			if (sign == true) {
	    			ajax_block.open();
	    			$.ajax({
	    				url: url,
	    				type: 'GET',
	    				async: false,
	    				dataType: 'json',
	    				success: function(retdat, status) {
	    					ajax_block.close();
	    					if (retdat['status'] != 1 || retdat['code'] != 200) {
	    						success = false;
	    						showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '！</font>');
	    					}
	    				},
	    				error: function() {
	    					success = false;
	    					ajax_block.close();
	    					showMessage('操作失败', '<font color="#990000">验证货品信息失败，请稍后重新尝试！</font>');
	    				}
	    			});
    			}
    			
    			return success;
    		}
    		
    		return true;
    	});
    	
    	return t;
    }();
    
    /**
     * 货品与商品图片关联管理对象
     */
    var goodpicrs = function() {
    	var picrels = {};
    	
    	var key = function(gid) {
    		return 'g_' + gid;
    	}
    	var current_gid = 0;
    	var set_handler = null;
    	
    	var t = {
    		show: function(gid, handler) {
    			current_gid = gid;
    			if (typeof handler == 'function') {
    				set_handler = handler;
    			}
                
    			picrelation.show('goodpicrs.set', t.get(gid));
    		},
    		set: function(pids) {
    			if (typeof pids == 'object' && current_gid > 0) {
    				picrels[key(current_gid)] = pids;
    				if (typeof set_handler == 'function') {
    					set_handler(current_gid, pids);
    				}
    			}
    			current_gid = 0;
    			set_handler = null;
    		},
    		get: function(gid) {
    			if (t.is(gid)) {
    				return picrels[key(gid)];
    			} else {
    				return [];
    			}
    		},
    		is: function(gid) {
    			var k = key(gid);
    			return typeof picrels[k] != 'undefined' && picrels[k].length > 0;
    		}
    	};
    	
    	<?php if (!empty($product['goods'])) : ?>
    	$(document).ready(function(){
    		<?php $x = 1; ?>
    		<?php foreach ($product['goods'] as $good) : ?>
	    		<?php if (!empty($good['picrels'])) : ?>
	    			picrels[key(<?php echo $x; ?>)] = <?php echo json_encode($good['picrels']); ?>;
	    		<?php endif; ?>
	    		<?php $x ++; ?>
    		<?php endforeach; ?>
    	});
    	<?php endif; ?>
    	
    	return t;
    }();
    
    /**
     * 商品规格与商品图片关联管理对象
     */
    var attroptpicrs = function() {
    	var picrels = {};
    	
    	var key = function(aid, oid) {
    		return aid + '_' + oid;
    	}
    	var current_aid = 0;
    	var current_oid = 0;
    	var set_handler = null;
    	var t = {
    		show: function(aid, oid, handler) {
    			current_aid = aid;
    			current_oid = oid;
    			if (typeof handler == 'function') {
    				set_handler = handler;
    			}
    			picrelation.show('attroptpicrs.set', t.get(aid, oid));
    		},
    		set: function(pids) {
    			if (typeof pids == 'object' && current_aid > 0 && current_oid > 0) {
    				picrels[key(current_aid, current_oid)] = pids;
    				if (typeof set_handler == 'function') {
    					set_handler(current_aid, current_oid, pids);
    				}
    			}
    			current_aid = 0;
    			current_oid = 0;
    			set_handler = null;
    		},
    		get: function(aid, oid) {
    			if (t.is(aid, oid)) {
    				return picrels[key(aid, oid)];
    			} else {
    				return [];
    			}
    		},
    		is: function(aid, oid) {
    			var k = key(aid, oid);
    			return typeof picrels[k] != 'undefined' && picrels[k].length > 0;
    		}
    	};
    	
    	<?php if (!empty($product['attroptpicrs'])) : ?>
	    	<?php foreach ($product['attroptpicrs'] as $aid => $ors) : ?>
	    		<?php foreach ($ors as $oid => $pics) : ?>
	    			picrels[key(<?php echo $aid; ?>, <?php echo $oid; ?>)] = <?php echo json_encode($pics); ?>;
	    		<?php endforeach; ?>
	    	<?php endforeach; ?>
    	<?php endif; ?>
    	
    	return t;
    }();
</script>