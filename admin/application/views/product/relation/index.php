<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<?php
$product_list = array();
foreach ($return_struct['content']['assoc'] as $product)
{
	$product_list['_'.$product['id']] = $product;
}
?>
<!--**content start**-->
        <div class="col_main">
                <div class="public_right public">
                    <form id="search_form" name="search_form" method="GET" action="<?php echo url::base() . url::current();?>">
                        <input type="hidden" name="adv_bar" id="adv_bar_nor" value="0" />
                        <input name="product_id" type="hidden" value="<?php echo $product_id; ?>"/>
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
                <table id="pdt_relation_box" cellspacing="0" cellpadding="0" border="0" width="100%" style="border:1px solid #efefef;">
                	<tr>
                            <th class="span-1" style="text-align:left"><input type="checkbox" id="pdt_relation_check_all"></th>
                            <th class="cell span-4" style="text-align:left"><b>商品SKU</b></th>
                            <th class="cell span-4" style="text-align:left"><b>分类</b></th>
                            <th class="cell span-5" style="text-align:left"><b>商品前台名称</b></th>
                            <th class="cell span-5" style="text-align:left"><b>商品后台名称</b></th>
                            <th class="cell span-3" style="text-align:left"><b>品牌</b></th>
                    </tr>
                    <?php foreach ($product_list as $product) { ?>
                    <tr>
                     	<td class="span-1"><input name="pdt_relation_check" type="checkbox" value="<?php echo $product['id']; ?>"></td>
                        <td class="cell span-4"><?php echo html::specialchars($product['sku']); ?>&nbsp;</td>
                        <td class="cell span-4"><?php echo html::specialchars($category_list[$product['category_id']]); ?>&nbsp;</td>
                        <td class="cell span-5"><?php echo html::specialchars($product['title']); ?>&nbsp;</td>
                        <td class="cell span-5"><?php echo html::specialchars($product['name_manage']); ?>&nbsp;</td>
                        <td class="cell span-3"><?php echo $product['brand_id'] > 0 ? html::specialchars($brand_list[$product['brand_id']]) : '<font color=#ff0000>无</font>'; ?>&nbsp;</td>
                    </tr>
                    <?php } ?>
                    <tr>
                    	<td colspan="6" id="pager">
                    		<div class="Turnpage_rightper"> <?php echo view_tool::per_page(); ?>
				            	<div class="b_r_pager"> <?PHP echo $this->pagination->render('opococ'); ?> </div>
				        	</div>
                    	</td>
                    </tr>
                </table>
            </div>
            <div class="list_save">
                <input id="pdt_relation_submit" type="button" class="ui-button" value="  确定   "/></b>
                <input id="pdt_relation_empty" type="button" class="ui-button" value="清空本页"/>
            </div>
        </div>
<!--**content end**-->
<link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){

		var relations = <?php echo json_encode($product_list); ?>;
		var checks = $('#pdt_relation_box').find('input[name="pdt_relation_check"]');

		for (var i = 0; i < checks.length; i ++) {
			var id = $(checks[i]).val();
			if (parent.relation.is(id)) {
				$($(checks[i])).attr('checked', true);
			}
		}
		
		$('#pdt_relation_check_all').unbind().bind('click', function(){
			var checked = $(this).attr('checked');
			if (checks.length > 0) {
				for (var i = 0; i < checks.length; i ++) {
					var o = $(checks[i]);
					if (o.attr('checked') != checked) {
						var id = o.val();
						o.attr('checked', checked);
						if (checked) {
							parent.relation.add(relations['_' + id])
						} else {
							parent.relation.remove(id);
						}
					}
				}
			}
		});

		checks.unbind().bind('click', function(){
			var id = $(this).val();
			if ($(this).attr('checked')) {
				parent.relation.add(relations['_' + id])
			} else {
				parent.relation.remove(id);
			}
		});

		$('#pdt_relation_submit').unbind().bind('click', function(){
			parent.relation.update(true);
			parent.relation.dialog(false);
		});

		$('#pdt_relation_empty').unbind().bind('click', function(){
			for (var i = 0; i < checks.length; i ++) {
				var o = $(checks[i]);
				if (o.attr('checked')) {
					parent.relation.remove(o.val());
					o.attr('checked', false);
				}
			}
		});
	});
</script>