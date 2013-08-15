<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="division">
	<table width="100%" style="border-top:1px solid #efefef;">
	<?php if (!empty($attributes)) : ?>
	<?php foreach ($attributes as $attribute) : ?>
		<tr>
			<th width="10%" style="text-align:center"><input type="checkbox" name="attribute_ids" value="<?php echo $attribute['id']; ?>"<?php if (!empty($aids) AND in_array($attribute['id'], $aids)) { ?> checked="checked"<?php } ?>/></th>
			<td width="20%"><?php echo html::specialchars($attribute['name']); ?></td>
			<td width="70%">
				<?php foreach ($attribute['options'] as $option) { ?>
					<?php if (!empty($attribute['display']) && $attribute['display'] == 'image') { ?>
					<img alt="" src="<?php echo $option['image'][2]; ?>" border="0" height="20" width="20"/>
					<?php } else { ?>
					<?php echo html::specialchars($option['name']); ?>&nbsp;
					<?php } ?>
				<?php } ?>
			</td>
		</tr>
	<?php endforeach; ?>
	<?php else : ?>
		<tr>
			<td><font color="#990000">未找到任何商品规格，请添加之后重新尝试！</font></td>
		</tr>
	<?php endif; ?>
	</table>
	<br/>
	<input id="save_attributes" type="button" class="ui-button" value="   确定   " />
</div>
<link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#save_attributes').click(function(){
		var attribute_ids = '';
		$('input[name="attribute_ids"]').each(function(idx, item){
			var item = $(item);
			if (item.attr('checked') == true) {
				if (attribute_ids == '') {
					attribute_ids = item.val();
				} else {
					attribute_ids += ',' + item.val();
				}
			}
		});
		if (attribute_ids == '') {
			<?php if (!empty($attributes)) : ?>
			if (parent.ptype_simple.getGoodCount() > 0 && !confirm('所有货品将会丢失，确定不再关联任何规格吗？')) {
				return false;
			}
			parent.ptype_simple.empty();
			<?php endif; ?>
			parent.ptype_simple.hide();
		} else {
			var url = url_base + 'product/product/get_attroptrs?&aids=' + attribute_ids;
			location.href = url;
		}
	});
	/* 按钮风格 */
    $(".ui-button,.ui-button-small").button();
});
</script>