<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
	<table id="binding_box_table" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-left:1px solid #efefef;border-top:1px solid #efefef;">
		<tr>
			<th style="text-align:left;">商品名称</th>
			<th style="text-align:left;">货品名称</th>
			<th style="text-align:left;">商品价格</th>
			<th style="text-align:left;">市场价</th>
			<th style="text-align:left;">成本价</th>
			<th style="text-align: left;">重量(g)</th>
			<th style="text-align: left;">规格</th>
			<th style="text-align: left;">库存</th>
			<th style="text-align: left;">SKU</th>
			<th style="text-align: left;">是否上下架</th>
			<th style="text-align:left;">操作</th>
		</tr>
		<?php if (!empty($goods)) : ?>
		<?php $i = 1; ?>
		<?php foreach ($goods as $good) : ?>
		<tr id="goods_tr_<?php echo $good['id'] ?>">
			<td><?php echo isset($good['product_title'])? $good['product_title'] : '' ?></td>
			<td><?php echo $good['title'] ?></td>
			<td><?php echo $good['price'] ?></td>
			<td><?php echo $good['market_price'] ?></td>
			<td><?php echo $good['cost'] ?></td>
			<td><?php echo $good['weight'] ?></td>
			<td><?php echo isset($good['attribute']) ? $good['attribute'] : '没有规格设置' ?></td>
			<td><?php echo $good['store'] ?></td>
			<td><?php echo $good['sku'] ?></td>
			<td><?php echo $good['on_sale']==0? '否' : '是' ?></td>
			<td><a onclick="delete_binded_good('<?php echo $good['id'] ?>',this,1)" href="javascript:"><img width="12" height="12" border="0" name="delete_good" src="/images/icon/remove.gif"></a></td>
		</tr>
		<?php $i ++; ?>
		<?php endforeach; ?>
		<?php endif; ?>
	</table>