<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="public_right public">
    <form action="/product/pdttpl/search_products" method="GET" name="search_form" id="search_form">
	<div>搜索:
		<select style="vertical-align: middle;" class="text" id="select_type" name="type">
			<option value="sku">商品 SKU</option>
			<option value="name_manage" <?php echo isset($search_type) && $search_type == 'name_manage' ? 'selected' : '' ?>>管理名称</option>
			<option value="title" <?php echo isset($search_type) && $search_type == 'title' ? 'selected' : '' ?>>商品名称</option>
			<option value="category_id" <?php echo isset($search_type) && $search_type == 'category_id' ? 'selected' : '' ?>>商品分类</option>
			<option value="brand_id" <?php echo isset($search_type) && $search_type == 'brand_id' ? 'selected' : '' ?>>商品品牌</option>
		</select>
		<input type="text" value="" style="vertical-align: middle;" id="keyword2" name="keyword" class="text">
		<input type="submit" aria-disabled="false" role="button" class="ui-button-small ui-widget ui-state-default ui-corner-all ui-button" value="搜索">
	</div>
	</form>
</div>
<div class="division">
<table style="border: 1px solid rgb(239, 239, 239);clear:both;" width="100%">
<tr bgcolor="#DFE2EA">
	<th class="cell span-4" style="text-align:left"><b>商品SKU</b></th>
    <th class="cell span-4" style="text-align:left"><b>分类</b></th>
    <th class="cell span-5" style="text-align:left"><b>管理名称</b></th>
    <th class="cell span-5" style="text-align:left"><b>商品名称</b></th>
    <th class="cell span-3" style="text-align:left"><b>品牌</b></th>
	<th style="text-align: left;" width="63">操作</th>
</tr>
<?php if (!empty($products)) : ?>
<?php foreach ($products as $product) : ?>
<tr>
    <td class="cell span-4"><?php echo html::specialchars($product['sku']); ?></td>
    <td class="cell span-4"><?php echo html::specialchars(empty($product['category']) ? '' : $product['category']['title_manage']); ?></td>
    <td class="cell span-5"><?php echo html::specialchars($product['name_manage']); ?></td>
    <td class="cell span-5"><?php echo html::specialchars($product['title']); ?></td>
    <td class="cell span-3"><?php echo empty($product['brand']) ? '<font color=#ff0000>无</font>' :  html::specialchars($product['brand']['name']); ?></td>
    <td class="cell span-3"><input type="button" value="选择" onclick="javascript:parent.show_template(<?php echo $product['id'] ?>);parent.hide_goods_nb_container()"</td>
</tr>
<?php endforeach; ?>
<?php else : ?>
<tr>
	<td colspan="6"><font color="#990000">未找到可供做模板的商品！</font></td>
</tr>
<?php endif; ?>
<tr><td colspan="6"><?php echo $pagination ?><div><input type="button" value="确认" style="width:60px;height:30px;" onclick="javascript:parent.hide_goods_nb_container()"></div></td></tr>
</table>
</div>
<br>
