<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php if (!empty($goods)) : ?>
	<table cellspacing="0" cellpadding="0" border="0" width="100%" style="border-left:1px solid #efefef;border-top:1px solid #efefef;">
		<tr>
			<th style="text-align:left;"><b>默认</b></th>
			<th style="text-align:left;" name="pdt_attrcol_0"><input type="checkbox" id="pdt_good_sale_checkall" name="pdt_good_sale_checkall"/>&nbsp;<b>上架</b></th>
			<th style="text-align:left;"><b>前台可见</b></th>
			<th style="text-align:left;"><b>货号SKU</b></th>
			<?php foreach ($attributes as $attribute) : ?>
			<th style="text-align:left;" name="pdt_attrcol_<?php echo $attribute['id']; ?>">
				<b>* <?php echo html::specialchars($attribute['name']);if(isset($attribute['alias']) && $attribute['name']!=$attribute['alias']){echo "(".$attribute['alias'].")";} ?></b>
			</th>
			<?php endforeach; ?>
			<th style="text-align:left;"><b>图片</b></th>
			<th style="text-align:left;"><b>标题</b></th>
			<th style="text-align:left;"><b>成本($)</b></th>
			<th style="text-align:left;"><b>* 价格(＄)</b></th>
			<th style="text-align:left;"><b>* 市场价(＄)</b></th>
			<th style="text-align:left;"><b>库存数量</b></th>
			<th style="text-align:left;"><b>重量(g)</b></th>
			<th style="text-align:left;"><b>操作</b></th>
		</tr>
		<?php $i = 1; ?>
		<?php foreach ($goods as $good) : ?>
		<tr>
			<td>
				<input type="checkbox" name="pdt_goods[<?php echo $i; ?>][default_goods]" value="1" <?php if (isset($good['default_goods']) && $good['default_goods'] == 1) : ?> checked="checked"<?php endif; ?>>
				<?php if (isset($good['id'])) : ?>
				<input type="hidden" name="pdt_goods[<?php echo $i; ?>][id]" value="<?php echo $good['id']; ?>">
				<?php endif; ?>
				<b>&nbsp;&nbsp;#<?php echo $i; ?></b>
			</td>
			<td name="pdt_attrcol_0">
				<input type="checkbox" name="pdt_goods[<?php echo $i; ?>][on_sale]" value="1"<?php if (!isset($good['on_sale']) OR $good['on_sale'] == 1) : ?> checked="checked"<?php endif; ?>>
			</td>
			<td name="pdt_attrcol_0">
				<input type="checkbox" name="pdt_goods[<?php echo $i; ?>][front_visible]" value="1"<?php if (isset($good['front_visible']) && $good['front_visible'] == 1) : ?> checked="checked"<?php endif; ?>>
			</td>
			<td class="d_line">
				<input type="text" name="pdt_goods[<?php echo $i; ?>][sku]" value="<?php echo isset($good['sku']) ? $good['sku'] : ''; ?>" class="text" size="12" maxlength="32">
			</td>
			<?php foreach ($attributes as $aid => $attribute) : ?>
            <td name="pdt_attrcol_<?php echo $aid; ?>">      
			<?php if (isset($good['attroptrs'][$aid]) AND isset($attribute['options'][$good['attroptrs'][$aid]])) : ?>        
            <input type="hidden" name="pdt_goods[<?php echo $i; ?>][attroptrs][<?php echo $aid; ?>]" value="<?php echo $good['attroptrs'][$aid]; ?>">      
			<?php $option = $attribute['options'][$good['attroptrs'][$aid]]; ?>
                <span name="pdt_attr_set_option" style="cursor:pointer;">
					<?php if (isset($attribute['display']) AND $attribute['display'] == 'image' AND isset($option['image'][2])) : ?>
					<img title="<?php echo html::specialchars($option['name']); ?>" name="pdt_attr_set_option" src="<?php echo $option['image'][2]; ?>" border="0" width="23" height="23">
					<?php else : ?>
					<?php echo html::specialchars($option['name']); ?>
					<?php endif; ?>
				</span>
			<?php else: ?>     
                <input type="hidden" name="pdt_goods[<?php echo $i; ?>][attroptrs][<?php echo $aid; ?>]" value="0"> 
                顾客填写项
			<?php endif; ?>            
			</td>    
			<?php endforeach; ?>
			<td>
				<input type="hidden" name="pdt_goods[<?php echo $i; ?>][picrels]" value="<?php echo !empty($good['picrels']) ? implode(',', $good['picrels']) : ''; ?>">
				<div style="float:left;cursor:pointer;border:0px;padding:1px;">
					<img name="pdt_pic_set_rel" border="0" src="<?php echo url::base(); ?>images/<?php if (!empty($good['picrels'])) : ?>icon/picthumb<?php else : ?>choose_black<?php endif; ?>.gif">
				</div>
			</td>
			<td class="d_line">
				<input type="text" name="pdt_goods[<?php echo $i; ?>][title]" value="<?php echo isset($good['title']) ? $good['title'] : ''; ?>" class="text" size="16" maxlength="100">
			</td>
			<td class="d_line">
				<input type="text" name="pdt_goods[<?php echo $i; ?>][cost]" value="<?php echo isset($good['cost']) ? $good['cost'] : 0; ?>" class="text" size="4" maxlength="10">
			</td>
			<td class="d_line">
				<input type="text" name="pdt_goods[<?php echo $i; ?>][price]" value="<?php echo isset($good['price']) ? $good['price'] : 0; ?>" class="text" size="4">
			</td>
			<td class="d_line">
				<input type="text" name="pdt_goods[<?php echo $i; ?>][market_price]" value="<?php echo isset($good['market_price']) ? $good['market_price'] : 0; ?>" class="text" size="4">
			</td>
			<td class="d_line">
				<input type="text" name="pdt_goods[<?php echo $i; ?>][store]" value="<?php echo isset($good['store']) ? $good['store'] : 0; ?>" class="text" size="4" maxlength="10">
			</td>
			<td class="d_line">
				<input type="text" name="pdt_goods[<?php echo $i; ?>][weight]" value="<?php echo isset($good['weight']) ? $good['weight'] : 0; ?>" class="text" size="4" maxlength="10">
			</td>
			<td>
				<img name="pdt_good_delete" src="<?php echo url::base(); ?>images/icon/remove.gif" border="0" width="12" height="12" style="cursor:pointer;">
			</td>
		</tr>
		<?php $i ++; ?>
		<?php endforeach; ?>
	</table>
<?php endif; ?>