<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php if (!empty($goods)) : ?>
<?php foreach ($goods as $good) : ?>
<tr class = "element_str">
<input name="good_id" type="hidden" value="<?php echo html::specialchars($good['id'])?>">
<input id="good_store_<?php echo html::specialchars($good['id'])?>" name="good_store" class="option_store" type="hidden" value="<?php echo ($good['store']==-1)?999:html::specialchars($good['store']);?>">
<td width="20%"><?php echo html::specialchars($good['sku']); ?></td>
<td width="30%"><?php echo html::specialchars($good['title']); ?></td>
<td width="10%"><?php echo html::specialchars($good['price']); ?></td>
<td width="15%"><input id="amount_<?php echo html::specialchars($good['id'])?>" name="amount[<?php echo $good['id']?>]" type="text" class="text" size="10" value="1"></td>
<td width="15%"><?php echo ($good['store']==-1)?999:html::specialchars($good['store']);?></td>
<td width="10%"><img style="cursor:pointer;" src="<?php echo url::base(); ?>images/icon/remove.gif" width="12" height="12" border="0"/></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>