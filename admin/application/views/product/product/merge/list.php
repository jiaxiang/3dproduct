<?php defined('SYSPATH') OR die('No direct access allowed.') ?>
<?php $products = $return_struct['content']['assoc']; ?>
<!--**content start**-->
        <div class="col_main">
                <div class="public_right public">
                    <form id="search_form" name="search_form" method="GET" action="<?php echo url::base() . url::current();?>">
                        <input type="hidden" name="adv_bar" id="adv_bar_nor" value="0" />
                        <input type="hidden" name="classify_id" id="classify_id"  value="<?php echo isset($classify_id)?$classify_id:''; ?>"/>
                        <p> 搜索:
                            <label>
                                <select name="type" id="select_type" class="text">
                                	<option value="sku" <?php if (isset($request_data['type']) && $request_data['type'] == 'sku') {?>selected<?php }?>>商品SKU</option>
                                    <option value="name_manage" <?php if (isset($request_data['type']) && $request_data['type'] == 'name_manage') {?>selected<?php }?>>管理名称</option>
                                    <option value="title" <?php if (isset($request_data['type']) && $request_data['type'] == 'title') {?>selected<?php }?>>商品名称</option>
                                    <option value="category_id" <?php if (isset($request_data['type']) && $request_data['type'] == 'category_id') {?>selected<?php }?>>商品分类</option>
                                    <option value="brand_id" <?php if (isset($request_data['type']) && $request_data['type'] == 'brand_id') {?>selected<?php }?>>商品品牌</option>
                                </select>
                            </label>
                            <label>
                                <input class="text" type="text" name="keyword" id="keyword2" value="<?php !empty($keyword) && print($keyword);?>" />
                            </label>
                            <label>
                                <input type="submit" class="ui-button-small" name="searchbtn" value="搜索" class="btn_text">
                            </label>
                    </form>
                </div>
            <!--	<div class="public_title title_h3"></div>	-->
            <div class="division">
                <table id="pdt_merge_box" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top:5px;border:1px solid #efefef;">
                	<tr>
                            <th class="span-1" style="text-align:left;height:26px;"><input type="checkbox" id="pdt_relation_check_all"></th>
                            <th class="cell span-4" style="text-align:left"><b>商品SKU</b></th>
                            <th class="cell span-4" style="text-align:left"><b>分类</b></th>
                            <th class="cell span-5" style="text-align:left"><b>管理名称</b></th>
                            <th class="cell span-5" style="text-align:left"><b>商品名称</b></th>
                            <th class="cell span-3" style="text-align:left"><b>品牌</b></th>
                    </tr>
                    <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $product) : ?>
                    <tr>
                     	<td class="span-1"><input name="pdt_merge_check" type="checkbox" value="<?php echo $product['id']; ?>"></td>
                        <td class="cell span-4"><?php echo html::specialchars($product['sku']); ?></td>
                        <td class="cell span-4"><?php echo html::specialchars(empty($product['category']) ? '' : $product['category']['title_manage']); ?></td>
                        <td class="cell span-5"><?php echo html::specialchars($product['name_manage']); ?></td>
                        <td class="cell span-5"><?php echo html::specialchars($product['title']); ?></td>
                        <td class="cell span-3"><?php echo empty($product['brand']) ? '<font color=#ff0000>无</font>' :  html::specialchars($product['brand']['name']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else : ?>
                    <tr>
                    	<td colspan="6"><font color="#990000">未找到可供合并的商品！</td></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                    	<td colspan="6" id="pager">
                    		<div class="Turnpage_rightper"> <!--<?php echo view_tool::per_page(); ?>-->
				            	<div class="b_r_pager"> <?PHP //echo $this->pagination->render('opococ'); ?> </div>
				        	</div>
                    	</td>
                    </tr>
                </table>
            </div>
            <div class="list_save">
                <input id="pdt_merge_submit" type="button" class="ui-button" value="  确定   "/></b>
                <input id="pdt_merge_empty" type="button" class="ui-button" value="清空本页"/>
            </div>
        </div>
<!--**content end**-->
<link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var products = <?php echo json_encode($products); ?>;
		var container = $('#pdt_merge_box');
		var checkeds = container.find('input[name="pdt_merge_check"]');
		
		/**
		 * 选取框的捆绑JS
		 */
		checkeds.bind('click', function(){
			var o = $(this);
			var v = o.val();
			if (o.attr('checked') == true) {
				for (var i = 0; i < products.length; i++) {
					if (v == products[i]['id']) {
						parent.ptype_merge.add(products[i]);
						break;
					}
				}
			} else {
				parent.ptype_merge.remove(v);
			}
		});
		
		/**
		 * 确认按钮捆绑JS
		 */
		$('#pdt_merge_submit').bind('click', function(){
			parent.ptype_merge.update(true);
			parent.ptype_merge.dialog(false);
		});
		
		/**
		 * 清空本页捆绑JS
		 */
		$('#pdt_merge_empty').bind('click', function(){
			for (var i = 0; i < checkeds.length; i ++) {
				var t = $(checkeds[i]);
				if (t.attr('checked') == true) {
					t.attr('checked', false).click().attr('checked', false);
				}
			}
		});
		
		/**
		 * 列表全选按钮
		 */
		$('#pdt_relation_check_all').bind('click', function(){
			var c = $(this).attr('checked');
			for (var i = 0; i < checkeds.length; i ++) {
				var t = $(checkeds[i]);
				if (t.attr('checked') != c) {
					t.attr('checked', c).click().attr('checked', c);
				}
			}
		});
		
		if (checkeds.length > 0) {
			for (var i = 0; i < checkeds.length; i ++) {
				var o = $(checkeds[i]);
				if (parent.ptype_merge.is(o.val())) {
					$(checkeds[i]).attr('checked', true);
				}
			}
		}
	});
</script>