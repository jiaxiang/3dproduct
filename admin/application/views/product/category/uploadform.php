<?php defined('SYSPATH') OR die('No direct access allowed.');
$return_data = $return_struct['content'];
?>
<form id="upload_form" name="upload_form" method="POST" action="<?php echo url::base();?>product/category/upload" enctype="multipart/form-data">
<div class="division">
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<th width="20%">图片：</th>
			<td width="80%"><input type="file" name="category_img[]" class="attachFieldProductpic ui-button ui-state-default ui-corner-all"/></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align:center;">
				<input id="upload" name="upload" type="submit" class="ui-button" value="上传"/>
				<input id="close" name="close" type="button" class="ui-button" value="取消"/>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				图片尺寸：小于 <?php echo $picture_max_size; ?>MB<br/>
				可用扩展名：<?php echo implode(',&nbsp;', $picture_types); ?>
			</td>
		</tr>
	</table>
</div>
</form>
<link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#close').click(function(){
		parent.$('#dialog').dialog('close');
	});
	/* 按钮风格 */
    $(".ui-button,.ui-button-small").button();
});
</script>