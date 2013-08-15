<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
echo html::script(array
(
		'media/js/jquery.js',
), FALSE);
echo html::stylesheet(array
(
		'media/css/custom',
		'media/css/fancybox',
		'media/css/flexslider',
		'media/css/formidablepro',
		'media/css/light-blue',
		'media/css/mediaelementplayer',
		'media/css/shortcode',
		'media/css/shr-custom-sprite',
		'media/css/slidesjs',
		'media/css/style',
		'media/css/widget',
		'media/css/tb',
), FALSE);
?>
</head>
<div id="main" class="right-side clearfix">
<?php echo View::factory('head')->set('user', $user)->render();?>
<article id="content">
<div class="post post-single post-page-single" id="post-367">
<div class="post-format"><div class="frm_forms with_frm_style" id="frm_form_7_container">
<form enctype="multipart/form-data" method="post" class="frm-show-form " id="formid" action="<?php echo url::base();?>service/print3d" >
<div class="frm_form_fields">
<fieldset>
<div>
<input type="hidden" name="frm_action" value="create" />
<input type="hidden" name="form_id" value="7" />
<input type="hidden" name="form_key" value="qbj03s" />
<div id="frm_field_67_container" class="frm_form_field form-field  frm_required_field frm_left_container">
<label class="frm_primary_label">用户名/电子邮箱 </label>
<input type="text" name="ue" value=""  size="20" class="text required"/>
</div>
<div id="frm_field_69_container" class="frm_form_field form-field  frm_left_container">
<label class="frm_primary_label">密码 </label>
<input type="password" name="passwd" value=""  size="20" class="text required"/>
</div>
<input type="hidden" name="item_key" value="" />
</div>
</fieldset>
</div>
<script type="text/javascript">
</script>
<p class="submit">
<input type="button" value="登陆" id="login"/>&nbsp;&nbsp;<input type="button" value="注册" id="reg"/><span id="tips"></span>
</p>
</form>
</div>
</div>
</div>
<!--end post page-->
</article>
<!--End Content-->
</div>
<script type="text/javascript">
$(function() {
	$('#reg').click(function() {
		window.location.href = '<?php echo url::base();?>user/register';
	});
	$('#login').click(function() {
		ue= $('input[name=ue]').val();
		if (ue == '') {
			$("#tips").text("请填写用户名!").show().fadeOut(1000);
			//$('#tips').html('请填写用户名！');
			return ;
		}
		passwd = $('input[name=passwd]').val();
		if (passwd == '') {
			$("#tips").text("请填写密码!").show().fadeOut(1000);
			//$('#tips').html('请填写密码！');
			return ;
		}
		$('#tips').html('Loading');
		$.ajax({
			url: '<?php echo url::base();?>user/login',
			type:'POST',
			data:{ "ue": ue, "passwd": passwd },
			dataType:'json',
			success:function(j) {
				if (j.code == 0) {
					alert('登录成功！');
					//$('#tips').html('');
					window.location.href = '<?php echo url::base();?>service/print3d';
				}
				else {
					$("#tips").text(j.msg).show().fadeOut(1000);
					//$('#tips').html(j.msg);
				}
			}
		});
	});
});
</script>
</html>