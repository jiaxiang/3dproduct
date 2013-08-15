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
<label class="frm_primary_label">用户名 </label>
<input type="text" name="username" value=""  size="20" class="text required"/>
</div>
<div id="frm_field_67_container" class="frm_form_field form-field  frm_required_field frm_left_container">
<label class="frm_primary_label">电子邮箱 </label>
<input type="text" name="email" value=""  size="20" class="text required"/>
</div>
<div id="frm_field_69_container" class="frm_form_field form-field  frm_left_container">
<label class="frm_primary_label">密码 </label>
<input type="password" name="passwd" value=""  size="20" class="text required"/>
</div>
<div id="frm_field_69_container" class="frm_form_field form-field  frm_left_container">
<label class="frm_primary_label">确认密码 </label>
<input type="password" name="confirm_passwd" value=""  size="20" class="text required"/>
</div>
<input type="hidden" name="item_key" value="" />
</div>
</fieldset>
</div>
<script type="text/javascript">
</script>
<p class="submit">
<input type="button" value="创建" id="creat"/>&nbsp;&nbsp;<input type="button" value="登陆" id="login"/><span id="tips"></span>
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
$('#login').click(function() {
	window.location.href = '<?php echo url::base();?>user/login';
});
$('#creat').click(function() {
	username = $('input[name=username]').val();
	if (username == '') {
		//$('#tips').html('请填写用户名！');
		$("#tips").text("请填写用户名！").show().fadeOut(1000);
		return false;
	}
	email = $('input[name=email]').val();
	if (email == '') {
		//$('#tips').html('请填写邮箱！');
		$("#tips").text("请填写邮箱!").show().fadeOut(1000);
		return false;
	}
	passwd = $('input[name=passwd]').val();
	if (passwd == '') {
		//$('#tips').html('请填写密码！');
		$("#tips").text("请填写密码!").show().fadeOut(1000);
		return false;
	}
	confirm_passwd = $('input[name=confirm_passwd]').val();
	if (confirm_passwd == '') {
		//$('#tips').html('请填写确认密码！');
		$("#tips").text("请填写确认密码!").show().fadeOut(1000);
		return false;
	}
	if (passwd != confirm_passwd) {
		//$('#tips').html('密码填写不一致！');
		$("#tips").text("密码填写不一致!").show().fadeOut(1000);
		return false;
	}
	$('#tips').html('Loading');
	$.ajax({
		url: '<?php echo url::base();?>user/register',
		type:'POST',
		data:{ "username": username, "email": email, "passwd": passwd },
		dataType:'json',
		success:function(j) {
			if (j.code == 0) {
				alert('注册成功！');
				//$('#tips').html('请查收邮件！');
				$("#tips").text("请去邮箱查收邮件完成认证!").show();
			}
			else {
				//$('#tips').html(j.msg);
				$("#tips").text(j.msg).show().fadeOut(1000);
			}
		}
	});
});
</script>
</html>