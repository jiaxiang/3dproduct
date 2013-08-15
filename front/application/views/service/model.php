<html>
<?php
echo html::script(array
(
		'media/js/jquery.js',
), FALSE);
?>
3D建模
<form action="<?php echo url::base();?>service/model3d" method="post" enctype="multipart/form-data">
<input type="file" name="img_val"/>
<input type="submit" value="submit"/>
</form>
<?php
if (isset($is_upload) && $is_upload == 1) {
?>
<span id="buy">确认购买</span>
<br/><span id="add">加入购物车</span>
<span id="tips"></span>
<?php
}
?>
<script type="text/javascript">
$('#add').click(function() {
	$.ajax({
		url: '<?php echo url::base();?>service/add_to_cart',
		type:'POST',
		data:{ 'type':<?php echo Order_detail_Model::TYPE_2;?> },
		dataType:'json',
		success:function(j) {
			if (j.code == 0) {
				alert('加入成功！');
			}
			else {
				$('#tips').html(j.msg);
			}
		}
	});
});
$('#buy').click(function() {
	$.ajax({
		url: '<?php echo url::base();?>service/to_buy',
		type:'POST',
		data:{ 'type':<?php echo Order_detail_Model::TYPE_2;?> },
		dataType:'json',
		success:function(j) {
			if (j.code == 0) {
				alert('购买成功！');
			}
			else {
				$('#tips').html(j.msg);
			}
		}
	});
});
</script>
</html>