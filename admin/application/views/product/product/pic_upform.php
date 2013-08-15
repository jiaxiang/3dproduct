<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php $product = $return_struct['content']['product'];?>
<form id="upload_form" name="upload_form" method="POST" action="<?php echo url::base();?>product/product/pic_upload" enctype="multipart/form-data">
<div class="division"><h3>每一次最多可以连续上传5张图片！</h3>
	<input name="product_id" type="hidden" value="<?php echo isset($product['product_id'])?$product['product_id']:''; ?>"/>
	<table cellspacing="0" cellpadding="0" border="0" width="100%">
		<tr>
			<th width="20%">图片1：</th>
			<td width="80%"><input id="myProductPic" type="file" name="myPorductpic[]" class="attachFieldProductpic ui-button ui-state-default ui-corner-all"/></td>
		</tr>
		<tr>
			<th>描述1：</th>
			<td><input type="text" name="myPorductpicTitle[]" class="text"/></td>
		</tr>
    
		<tr>
			<th width="20%">图片2：</th>
			<td width="80%"><input id="myProductPic" type="file" name="myPorductpic[]" class="attachFieldProductpic ui-button ui-state-default ui-corner-all"/></td>
		</tr>
		<tr>
			<th>描述2：</th>
			<td><input type="text" name="myPorductpicTitle[]" class="text"/></td>
		</tr>
            
		<tr>
			<th width="20%">图片3：</th>
			<td width="80%"><input id="myProductPic" type="file" name="myPorductpic[]" class="attachFieldProductpic ui-button ui-state-default ui-corner-all"/></td>
		</tr>
		<tr>
			<th>描述3：</th>
			<td><input type="text" name="myPorductpicTitle[]" class="text"/></td>
		</tr>
            
		<tr>
			<th width="20%">图片4：</th>
			<td width="80%"><input id="myProductPic" type="file" name="myPorductpic[]" class="attachFieldProductpic ui-button ui-state-default ui-corner-all"/></td>
		</tr>
		<tr>
			<th>描述4：</th>
			<td><input type="text" name="myPorductpicTitle[]" class="text"/></td>
		</tr>
            
		<tr>
			<th width="20%">图片5：</th>
			<td width="80%"><input id="myProductPic" type="file" name="myPorductpic[]" class="attachFieldProductpic ui-button ui-state-default ui-corner-all"/></td>
		</tr>
		<tr>
			<th>描述5：</th>
			<td><input type="text" name="myPorductpicTitle[]" class="text"/></td>
		</tr>
            
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
<link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	var picture_types = <?php echo json_encode($picture_types); ?>;
	$('#upload').click(function(){
		var filename = $('#myProductPic').val();
		//var posfix   = filename.slice(filename.lastIndexOf('.')+1).toLowerCase();
		var posfix   = filename.slice(filename.lastIndexOf('.')+1);
		var in_types = false;
		for (var i = 0; i < picture_types.length; i++) {
			if (picture_types[i] == posfix) {
				in_types = true;
				break;
			}
		}
		if (picture_types.length == 0 || in_types == true) {
			ajax_block.open();
			$('#upload_form')[0].submit();
		} else {
			parent.showMessage('图片类型错误', '<font color="#990000">请选择 <?php echo implode(',', $picture_types); ?> 格式的图片文件！');
		}
	});
	$('#close').click(function(){
		parent.$('#pdt_picupload_ifm').dialog('close');
	});
	/* 按钮风格 */
    $(".ui-button,.ui-button-small").button();
});
</script>