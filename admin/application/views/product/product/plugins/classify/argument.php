<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<?php if (!empty($argument_relation)) : ?>
	<?php foreach ($argument_relation as $relation) : ?>
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<th colspan="2" style="text-align:left;">
				<b>&nbsp;&nbsp;<?php echo html::specialchars($relation['name']); ?></b>
			</th>
		</tr>
		<?php foreach ($relation['items'] as $argument) : ?>
		<tr>
			<th width="15%"><?php echo html::specialchars($argument['name']); ?>：</th>
			<td width="85%">
				<input type="text" class="text" name="pdt_argumrs[<?php echo html::specialchars($relation['name']); ?>][<?php echo html::specialchars($argument['name']); ?>]" value="<?php if (isset($arguments[$relation['name']][$argument['name']])) : ?><?php echo html::specialchars($arguments[$relation['name']][$argument['name']]); ?><?php endif; ?>" size="32">
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endforeach; ?>
<?php endif; ?>