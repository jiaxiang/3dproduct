<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div style="margin-top: 0px; border-top: 0px none;" class="division" id="ptype_merge_btn_box">
    <input type="button" value=" 添加商品 " class="ui-button ui-widget ui-state-default ui-corner-all ui-state-focus" name="ptype_merge_add" id="ptype_merge_add" role="button" aria-disabled="false">
</div>
<div style="margin-top: 0px; border-top: 0px none; display: none;" class="division" id="ptype_merge_fetus_box"></div>
<div style="margin-top: 0px; border-top: 0px none;" class="division" id="merges_box">
	<table cellspacing="0" cellpadding="0" border="0" width="100%" style="border-left:1px solid #efefef;border-top:1px solid #efefef;">
		<tr>
			<th style="text-align:left;"><b>默认</b></th>
			<th style="text-align:left;"><b>* SKU</b></th>
			<th style="text-align:left;"><b>* 标题</b></th>
			<th style="text-align:left;"><b>* 价格(＄)</b></th>
			<th style="text-align:left;"><b>* 市场价(＄)</b></th>
			<th style="text-align:left;"><input type="checkbox" id="pdt_good_sale_checkall" name="pdt_good_sale_checkall"/>&nbsp;<b>上架</b></th>
			<th style="text-align:left;"><b>图片</b></th>
			<th style="text-align:left;"><b>成本($)</b></th>
			<th style="text-align:left;"><b>库存数量</b></th>
			<th style="text-align:left;"><b>重量(g)</b></th>
			<th style="text-align:left;"><b>操作</b></th>
		</tr>
	</table>
</div>
<div id="pdt_merges_ifm" class="ui-dialog-content ui-widget-content" style="display:none;">
    <iframe style="border:0px;width:100%;height:95%;" frameborder="0" src="" scrolling="auto"></iframe>
</div>
<script type="text/javascript">
	
	var attroptrs = <?php echo json_encode(empty($product['attroptrs']) ? array() : $product['attroptrs']); ?>;
	
	var ptype_merge = function() {
		var selector = {
			dialog: '#pdt_merges_ifm',
			btn: {
				box: '#ptype_merge_btn_box',
				add: '#ptype_merge_add'
			},
			container: '#merges_box'
		};
		
		var items = {};
		
		var products = {};
		var changes = [];
		var deletes = [];
		var picrels = {};
		
		var idx = 1;
		
		/**
		 * 获取商品是否作为捆绑商品的默认货品
		 */
		var get_is_default = function(pdt) {
			var is_default = 0;
        	if (typeof pdt['is_default'] != 'undefined') {
        		is_default = pdt['is_default'];
        	} else if ($.count(items) == 0) {
        		is_default = 1;
        	}
        	return is_default;
		}
		
		var getRow = function(pdt) {
        	var row = '';
        	
        	var is_default = get_is_default(pdt);
        	
			row += '<tr>';
			row += '	<td>';
			row += '		<input name="pdt_merges[' + idx + '][is_default]" value="1" type="checkbox"' + (is_default == 0 ? '' : ' checked="checked"') + '>';
			row += '		<b>&nbsp;&nbsp;#' + idx + '</b>';
			row += '        <input type="hidden" name="pdt_merges[' + idx + '][id]" value="' + pdt['id'] + '">';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_merges[' + idx + '][sku]" value="' + pdt['sku'] + '" class="text" size="12">';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_merges[' + idx + '][title]" value="' + pdt['title'] + '" class="text" size="16" maxlength="100">';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_merges[' + idx + '][goods_price]" value="' + pdt['goods_price'] + '" class="text" size="4">';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_merges[' + idx + '][goods_market_price]" value="' + pdt['goods_market_price'] + '" class="text" size="4">';
			row += '	</td>';
			row += '	<td name="pdt_attrcol_0">';
			row += '		<input name="pdt_merges[' + idx + '][on_sale]" type="checkbox" value="1"' + (pdt['on_sale'] == 1 ? ' checked="checked"' : '') + '>';
			row += '	</td>';
			row += '	<td>';
			row += '		<input type="hidden" name="pdt_merges[' + idx + '][picrels]" value="' + pdt['picrels'].join(',') + '"/>';
			row += '		<div style="cursor:pointer;border:0px;">';
			if (typeof pdt['picrels'] == 'undefined' || pdt['picrels'].length == 0) {
				row += '<img name="pdt_pic_set_rel" border="0" src="' + url_base + 'images/choose_black.gif">';
			} else {
				row += '<img name="pdt_pic_set_rel" border="0" src="' + url_base + 'images/icon/picthumb.gif">';
			}
			row += '		</div>';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_merges[' + idx + '][goods_cost]" value="' + pdt['goods_cost'] + '" class="text" size="4" maxlength="10">';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_merges[' + idx + '][store]" value="' + pdt['store'] + '" class="text" size="4" maxlength="10">';
			row += '	</td>';
			row += '	<td class="d_line">';
			row += '		<input name="pdt_merges[' + idx + '][goods_weight]" value="' + pdt['goods_weight'] + '" class="text" size="4" maxlength="10">';
			row += '	</td>';
			row += '	<td>';
			row += '		<img name="pdt_good_delete" src="' + url_base + 'images/icon/remove.gif" border="0" width="12" height="12" style="cursor:pointer;"></a>';
			row += '	</td>';
			row += '</tr>';
			
			row = $(row);
			
			// 如果商品作为默认货品，则将其价格、市场价、成本价、重量、上下架信息填充入商品对应信息
			if (is_default == 1) {
				renderPDTInf(pdt);
			}
			
			row = $(row);
			
			//setValidate(row);
			
			idx ++;
			
			return row;
        }
		
        /**
         * 设置合并商品的前端JS验证
         */
        var setValidate = function(o) {
        	o.find('input[name$="[goods_price]"]').rules('add', {
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
			o.find('input[name$="[goods_market_price]"]').rules('add', {
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
			o.find('input[name$="[title]"]').rules('add', {
				required: true,
				maxlength: 100,
				messages: {
					required: '不可为空',
					maxlength: '长度不可超过 100 个字符'
				}
			});
			o.find('input[name$="[sku]"]').rules('add', {
				required: true,
				maxlength: 32,
				messages: {
					required: '不可为空',
					maxlength: '长度不可超过 32 个字符'
				}
			});
			o.find('input[name$="[goods_cost]"]').rules('add', {
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
			o.find('input[name$="[goods_weight]"]').rules('add', {
				digits: true,
				max: 999999,
				messages: {
					digits: '必须为正整数',
					max: '不可大于 999999'
				}
			});
        }
        
        /**
         * 将商品信息填充入商品表单区域
         */
        var renderPDTInf = function(pdt) {
        	$('#goods_market_price').val(pdt['goods_market_price'] + '');
        	$('#goods_cost').val(pdt['goods_cost'] + '');
        	$('#goods_weight').val(pdt['goods_weight'] + '');
        	$('#goods_price').val(pdt['goods_price'] + '');
        }
        
		var t = {
			dialog: function(yn) {
				var classify_id = $('#classify_id').val();
				if (classify_id == 0) {
					showMessage('操作失败', '<font color="#990000">请首先选择商品类型！</font>');
					return false;
				}
				var ifm = $(selector.dialog);
				if (yn) {
					ifm.find('iframe').attr('src', url_base + 'product/merge/index?site_id=<?php echo $product['site_id']; ?>&classify_id=' + classify_id);
					ifm.dialog('open');
				} else {
					ifm.dialog('close');
				}
			},
			add: function(pdt) {
				if (typeof pdt == 'object' && typeof pdt['type'] != 'undefined' && pdt['type'] == 0) {
					pdt['id'] += '';
					if (typeof items[pdt['id']] == 'undefined') {
						if (pdt['classify_id'] != $('#classify_id').val()) {
							return false;
						}
						for (var i = 0; i < changes.length; i ++) {
							if (changes[i]['id'] == pdt['id']) {
								return false;
							}
						}
						products[pdt['id']] = pdt;
						changes.push(pdt);
						return true;
					} else {
						for (var i = 0; i < deletes.length; i ++) {
							if (deletes[i] == pdt['id']) {
								delete deletes[i];
								var nd = [];
								for (var x = 0; x < deletes.length; x ++) {
									if (typeof deletes[x] != 'undefined') {
										nd.push(deletes[x]);
									}
								}
								deletes = nd;
								return true;
							}
						}
					}
				}
				return false;
			},
			remove: function(pdt_id) {
				pdt_id += '';
				if (typeof items[pdt_id] != 'undefined') {
					deleted = false;
					for (var i = 0; i < deletes.length; i ++) {
						if (deletes[i] == pdt_id) {
							deleted = true;
							break;
						}
					}
					if (deleted == false) {
						deletes.push(pdt_id);
					}
					return true;
				} else if (changes.length > 0) {
					for (var i = 0; i < changes.length; i ++) {
						var pdt = changes[i];
						if (pdt['id'] == pdt_id) {
							delete changes[i];
							var nc = [];
							for (var x = 0; x < changes.length; x ++) {
								if (typeof changes[x] != 'undefined') {
									nc.push(changes[x]);
								}
							}
							changes = nc;
							return true;
						}
					}
				}
				return false;
			},
			is: function(pdt_id) {
				pdt_id += '';
				if (typeof items[pdt_id] != 'undefined') {
					return true;
				}
				for (var i = 0; i < changes.length; i ++) {
					if (changes[i]['id'] == pdt_id) {
						return true;
					}
				}
				return false;
			},
			update: function(yn) {
				if (yn) {
					if (changes.length > 0) {
						var box = $(selector.container).find('table');
						if (box.find('tbody').length > 0) {
							box = box.find('tbody');
						}
						for (var i = 0; i < changes.length; i ++) {
							var c = changes[i];
							var row = getRow(c);
							if (typeof c['pictures'] != 'undefined' && $.count(c['pictures']) > 0)
							{
								picrels[c['id']] = [];
								for (var k in c['pictures']) {
									var picture = c['pictures'][k];
									var idx = pictures.add(picture);
									picrels[c['id']].push(idx);
									if (pictures.get_current() == 0) {
										pictures.show(idx);
									}
								}
							}
							items[c['id']] = row;
							box.append(row);
							setValidate(row);
						}
					}
					if (deletes.length > 0) {
						for (var i = 0; i < deletes.length; i ++) {
							var pdt_id = deletes[i];
							if (typeof items[pdt_id] != 'undefined') {
								items[pdt_id].remove();
								delete items[pdt_id];
							}
							if (typeof picrels[pdt_id] != 'undefined') {
								for (var x in picrels[pdt_id]) {
									var idx = picrels[pdt_id][x];
									pictures.remove(idx, false);
								}
							}
						}
						var ni = {};
						for (var k in items) {
							if (typeof items[k] != 'undefined') {
								ni[k] = items[k];
							}
						}
						items = ni;
					}
				}
				
				//resetPDTPrice();
				
				changes = [];
				deletes = [];
			},
			changed: function() {
				return changes.length > 0;
			}
		}
		
		//TODO 加入特性钩子
		
		$(document).ready(function(){
			
			// 隐藏商品价格、市场价、成本、库存、重量显示区域
			$('#product_good_default_info_box').hide();
			
			// 添加特性的处理钩子
			classify.handler('features', function(box, features){
				features = features.replace(/^\s+/, '').replace(/\s+$/, '');
				if (features.length > 0) {
					features = $(features);
					var is = features.find('input[name^="pdt_fetuoptrs"]');
					var fs = {};
					if (is.length > 0) {
						for (var i = 0; i < is.length; i ++) {
							var o = $(is[i]);
							var n = o.attr('name');
							
							var fid = n.substring(n.indexOf('[') + 1, n.indexOf(']'));
							var fnm = o.parent().prev().html();
							
							fs[fid] = fnm;
						}
					}
					
					var cs = '';
					for (var fid in fs) {
						var fnm = fs[fid];
						cs += '<input type="checkbox" name="ptype_merge_fetus[]" value="' + fid + '"' + (typeof attroptrs[fid] != 'undefined' ? ' checked="checked"' : '') + '/>&nbsp;' + fnm + '&nbsp;&nbsp;';
					}
					
					if (cs.length > 0) {
						cs = '<b><font color="#990000">请选择所要合并的特性：</font></b>' + cs;
						$('#ptype_merge_fetus_box').empty().html(cs).show();
					} else {
						$('#ptype_merge_fetus_box').empty().hide();
					}
				}
				box.empty().hide();
			});
			
			/**
			 * 初始化选择合并商品的弹出框
			 */
			$(selector.dialog).dialog({
				title:       '选择合并商品',
	            modal:       true,
	            autoOpen:    false,
	            height:      400,
	            width:       800,
	            beforeclose: function() {
					if (t.changed()) {
						if (!confirm('所有修改将会丢失，确定关闭吗？')) {
							return false;
						}
						t.update(false);
					}
				}
			});
			
			$(selector.btn.add).bind('click', function(){
				t.dialog(true);
			});
			
			$(selector.container).bind('click', function(e){
				var o = $(e.target);
				var n = o.attr('name');
				if (typeof n != 'undefined') {
					switch (n.toUpperCase()) {
						/**
						 * 处理货品的删除
						 */
						case 'PDT_GOOD_DELETE':
							if ($.count(items) > 1) {
								if (confirm('确定要删除吗？')) {
									var id = o.parent().parent().find('input[name$="[id]"]');
									if (id.length == 1) {
										id = id.val();
										if (t.is(id)) {
											var c = o.parent().parent().find('input[name$="[is_default]"]');
											if (c.attr('checked') == true) {
												var cs = $(selector.container).find('input[name$="[is_default]"]');
												for (var i = 0; i < cs.length; i ++) {
													var c = $(cs[i]);
													if (c.attr('checked') == false) {
														c.attr('checked', true);
														break;
													}
												}
												var d = o.parent().parent().find('input[name$="[id]"]');
												if (d.length > 0 && typeof products[d.val()] != 'undefined') {
													renderPDTInf(products[d.val()]);
												}
											}
											t.remove(id);
											t.update(true);
										}
									}
								}
							} else {
								showMessage('操作失败', '<font color="#990000">至少保留一个参与合并的商品！</font>');
							}
							break;
						/**
						 * 处理货品上下架的批量设置
						 */
						case 'PDT_GOOD_SALE_CHECKALL':
							var ss = $(selector.container).find('input[name$="[on_sale]"]');
							if (ss.length > 0) {
								var checked = o.attr('checked');
								for (var i = 0; i < ss.length; i ++) {
									$(ss[i]).attr('checked', checked);
								}
							}
							break;
						/**
						 * 其他处理
						 */
						default:
							/**
							 * 处理默认货品的切换
							 */
							if (n.lastIndexOf('[') > -1 && n.lastIndexOf(']') > -1) {
								var k = n.substring(n.lastIndexOf('[') + 1, n.lastIndexOf(']')).toUpperCase();
								if (k == 'IS_DEFAULT') {
									var cs = $(selector.container).find('input[name$="[is_default]"]');
									for (var i = 0; i < cs.length; i ++) {
										var c = $(cs[i]);
										if (c.attr('checked') == true) {
											c.attr('checked', false);
										}
									}
									o.attr('checked', true);
									var d = o.parent().parent().find('input[name$="[id]"]');
									if (d.length > 0 && typeof products[d.val()] != 'undefined') {
										renderPDTInf(products[d.val()]);
									}
								}
							}
					}
				}
			});
		});
		
		/**
		 * 表单提交时，验证合并商品是否合法
		 */
		submitHandlers.push(function(){
			var url = url_base + 'product/merge/validate?classify_id=' + $('#classify_id').val();
			var mfids = $('#ptype_merge_fetus_box').find('input[name="ptype_merge_fetus[]"]');
			if (mfids.length > 0) {
				if ($.count(items) == 0) {
					showMessage('操作失败', '<font color="#990000">请选择所要合并的商品！</font>');
					return false;
				}
				
				var is_checked = false;
				for (var i = 0; i < mfids.length; i ++) {
					var mfid = $(mfids[i]);
					if (mfid.attr('checked') == true) {
						is_checked = true;
						url += '&mfids[]=' + mfid.val();
					}
				}
				if (is_checked == false) {
					showMessage('操作失败', '<font color="#990000">请选择所要合并的特性！</font>');
					return false;
				}
				
				var skus = {};
				skus[$('#sku').val().toLowerCase()] = true;
				
				for (var pid in items) {
					var sku = items[pid].find('input[name$="[sku]"]');
					var sin = sku.attr('name');
					var i = sin.substring(sin.indexOf('[') + 1, sin.indexOf(']'));
					
					sku = sku.val();
					
					if (typeof skus[sku.toLowerCase()] != 'undefined') {
						showMessage('操作失败', '<font color="#990000">参与合并的商品 #' + i + ' 与其他商品的SKU重复！</font>');
						return false;
					}
					
					skus[sku.toLowerCase()] = true;
					
					url += '&merges[' + i + '][id]='  + pid;
					url += '&merges[' + i + '][sku]=' + sku;
				}
				
				var success = true;
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
						ajax_block.close();
						success = false;
						showMessage('操作失败', '<font color="#990000">验证所要合并的商品合法性失败，请稍后重新尝试！</font>');
					}
				});
				
				return success;
			} else {
				showMessage('操作失败', '<font color="#990000">未找到任何可供关联的特性！</font>');
				return false;
			}
		});
		
		return t;
	}();
	
	<?php if (!empty($product['merges'])) : ?>
		$(document).ready(function(){
			<?php foreach ($product['merges'] as $merge) : ?>
				ptype_merge.add(<?php echo json_encode($merge); ?>);
				ptype_merge.update(true);
			<?php endforeach; ?>
		});
	<?php endif; ?>
</script>