<?php defined('SYSPATH') OR die('No direct access allowed.');
$return_data = $return_struct['content'];
?>
<form id="upload_form" name="upload_form" method="POST" action="<?php echo url::base();?>promotion/promotion_activity/upload" enctype="multipart/form-data">
<div class="division">
    <?php if(isset($return_data['option_id']) && !empty($return_data['option_id'])){?>
	<input name="option_id" type="hidden" value="<?php echo $return_data['option_id'];?>"/>
	<?php }?>

	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<th width="20%">图片：</th>
			<td width="80%"><input type="file" id="promotionActivityImg" name="promotionActivityImg[]" class="attachFieldProductpic ui-button ui-state-default ui-corner-all"/></td>
		</tr>
		<!-- <tr>
			<th>描述：</th>
			<td><input type="text" name="attribute_img_title[]" class="text"/></td>
		</tr> -->
		<tr>
			<td colspan="2" style="text-align:center;">
				<input id="upload" name="upload" type="button" class="ui-button" value="上传"/>
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
<script type="text/javascript" src="<?php echo url::base();?>js/jquery-ui-1.8.2.custom.min.js"></script>
<link type="text/css" href="<?php echo url::base();?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />

<script type="text/javascript">
$(document).ready(function(){
	$('#close').click(function(){
		parent.$('#dialog').dialog('close');
	});
	var picture_types = <?php echo json_encode($picture_types); ?>;
	$('#upload').click(function(){
	    var filename = $('#promotionActivityImg').val();
	    var posfix   = filename.slice(filename.lastIndexOf('.')+1).toLowerCase();
	    var in_types = false;
	    for (var i = 0; i < picture_types.length; i++) {
	        if (picture_types[i] == posfix) {
	            in_types = true;
	            break;
	        }
	    }
	    if (picture_types.length == 0 || in_types == true) {
	        $('#upload_form')[0].submit();
	    } else {
	        parent.showMessage('图片类型错误', '<font color="#990000">请选择 <?php echo implode(',', $picture_types); ?> 格式的图片文件！');
	    }
	});
	/* 按钮风格 */
    $(".ui-button,.ui-button-small").button();
});
</script>