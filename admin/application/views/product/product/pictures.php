<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php $pictures     = $return_struct['content']['pictures']; ?>
<?php $pids         = $return_struct['content']['pids']; ?>
<?php $save_handler = $return_struct['content']['save_handler']; ?>
<style type="text/css">
.border_per{float:left;width:142px;padding:0 0 0 20px;display:inline;overflow:hidden;}
	.perv{border:1px solid #efefef; width:120px;height:120px; padding:0px;margin:0px; overflow:hidden; position:relative; display:table-cell; text-align:center; vertical-align:middle;}
	.perv span{position:static; +position:absolute; top:50%;padding:0;margin:0; }   
	.perv img {position:static; +position:relative; top:-50%;left:-50%;padding:0;margin:0; }
	
	.perv_input{width:120px;height:30px; line-height:30px; padding:0px;margin:0px; overflow:hidden; display:block; text-align:center; vertical-align:middle; }
	

.perv_mid{border:1px solid #efefef; width:300px;height:300px; padding:0px;margin:0px; overflow:hidden; position:relative; display:table-cell; text-align:center; vertical-align:middle;}
.perv_mid span{position:static; +position:absolute; top:50%;padding:0;margin:0; }   
.perv_mid img {position:static; +position:relative; top:-50%;left:-50%;padding:0;margin:0; }

</style>
<!--**content start**-->
<?php //$i = 1; ?>
<?php if (!empty($pictures)) : ?>
<div class="division" style="border:0px">
	<?php foreach ($pictures as $picture) : ?>
	<div class="border_per">
		<div class="perv" style="height:130px;height:130px;">
			<span>
				<img src="<?php echo str_replace('.', '_120x120.', $picture['picurl_o']); ?>" border="0"/>
			</span>
		</div>
		<div class="perv_input">
			<input name="picture_id" type="checkbox" value="<?php echo $picture['id']; ?>"<?php if (in_array($picture['id'], $pids)) { ?> checked="checked"<?php } ?>/>&nbsp;<b>#<?php echo $picture['id']; ?></b>
		</div>
	</div>
	<?php endforeach; ?>
	<div class="clear"></div>   
</div>
<div class="division" style="border:0px;margin-top:5px;text-align:center;">
    <input id="save_picture" type="button" class="ui-button" value="  确定  "/>
    <input id="alsel_picture" type="button" class="ui-button" value="  全选  ">
	<input id="nosel_picture" type="button" class="ui-button" value="  清空  "/>
    <input id="close_picture" type="button" class="ui-button" value="  取消  "/>
</div>
<?php else : ?>
	<div class="division">
      	<font color="red">该商品尚未添加图片，请首先添加商品图片...</font>
    </div>
<?php endif; ?>
<link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	// 图片关联保存句柄（父窗口中定义的JS函数）
	var save_handler = parent.<?php echo $save_handler; ?>;
	var hide_handler = function() {
		parent.picrelation.hide();
	}
	
	$('#save_picture').click(function(){
		var picture_ids = [];
		$('input[name="picture_id"]').each(function(idx, item){
			var item = $(item);
			if (item.attr('checked') == true) {
				picture_ids.push(item.val());
			}
		});
		
		save_handler(picture_ids);
		hide_handler();
	});
	
	$('#close_picture').click(function(){
		hide_handler();
	});

	$('#not_picture').click(function(){
		if (confirm('确定要取消吗？')) {
			save_handler([]);
			hide_handler();
		}
	});

	$('#alsel_picture').click(function(){
		$('input[name="picture_id"]').each(function(idx, item){
			$(item).attr('checked', true);
		});
	});

	$('#nosel_picture').click(function(){
		$("input[name='picture_id']").attr("checked", false);
	});

	/* 按钮风格 */
    $(".ui-button,.ui-button-small").button();
});
</script>