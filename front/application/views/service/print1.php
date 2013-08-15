<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<?php
echo html::script(array
(
		'media/js/jquery.js',
		'media/js/vendor/jquery.ui.widget.js',
		'media/js/jquery.iframe-transport.js',
		'media/js/jquery.fileupload.js',
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
<script src="http://www.codeabb.com/stl/three.min.js"></script>
<script src="http://www.codeabb.com/stl/stats.js"></script>
<script src="http://www.codeabb.com/stl/detector.js"></script>
<script src="http://www.codeabb.com/stl/eventSTL.js"></script>
<script src="http://www.codeabb.com/stl/stl3.js"></script>
<style type="text/css" media="screen">body{position:relative}#dynamic-to-top{display:none;overflow:hidden;width:auto;z-index:90;position:fixed;bottom:20px;right:20px;top:auto;left:auto;font-family:sans-serif;font-size:1em;color:#fff;text-decoration:none;text-shadow:0 1px 0 #333;font-weight:bold;padding:17px 16px;border:1px solid #000;background:#111;-webkit-background-origin:border;-moz-background-origin:border;-icab-background-origin:border;-khtml-background-origin:border;-o-background-origin:border;background-origin:border;-webkit-background-clip:padding-box;-moz-background-clip:padding-box;-icab-background-clip:padding-box;-khtml-background-clip:padding-box;-o-background-clip:padding-box;background-clip:padding-box;-webkit-box-shadow:0 1px 3px rgba( 0, 0, 0, 0.4 ), inset 0 0 0 1px rgba( 0, 0, 0, 0.2 ), inset 0 1px 0 rgba( 255, 255, 255, .4 ), inset 0 10px 10px rgba( 255, 255, 255, .1 );-ms-box-shadow:0 1px 3px rgba( 0, 0, 0, 0.4 ), inset 0 0 0 1px rgba( 0, 0, 0, 0.2 ), inset 0 1px 0 rgba( 255, 255, 255, .4 ), inset 0 10px 10px rgba( 255, 255, 255, .1 );-moz-box-shadow:0 1px 3px rgba( 0, 0, 0, 0.4 ), inset 0 0 0 1px rgba( 0, 0, 0, 0.2 ), inset 0 1px 0 rgba( 255, 255, 255, .4 ), inset 0 10px 10px rgba( 255, 255, 255, .1 );-o-box-shadow:0 1px 3px rgba( 0, 0, 0, 0.4 ), inset 0 0 0 1px rgba( 0, 0, 0, 0.2 ), inset 0 1px 0 rgba( 255, 255, 255, .4 ), inset 0 10px 10px rgba( 255, 255, 255, .1 );-khtml-box-shadow:0 1px 3px rgba( 0, 0, 0, 0.4 ), inset 0 0 0 1px rgba( 0, 0, 0, 0.2 ), inset 0 1px 0 rgba( 255, 255, 255, .4 ), inset 0 10px 10px rgba( 255, 255, 255, .1 );-icab-box-shadow:0 1px 3px rgba( 0, 0, 0, 0.4 ), inset 0 0 0 1px rgba( 0, 0, 0, 0.2 ), inset 0 1px 0 rgba( 255, 255, 255, .4 ), inset 0 10px 10px rgba( 255, 255, 255, .1 );box-shadow:0 1px 3px rgba( 0, 0, 0, 0.4 ), inset 0 0 0 1px rgba( 0, 0, 0, 0.2 ), inset 0 1px 0 rgba( 255, 255, 255, .4 ), inset 0 10px 10px rgba( 255, 255, 255, .1 );-webkit-border-radius:30px;-moz-border-radius:30px;-icab-border-radius:30px;-khtml-border-radius:30px;border-radius:30px}#dynamic-to-top:hover{background:#4d5858;background:#111 -webkit-gradient( linear, 0% 0%, 0% 100%, from( rgba( 255, 255, 255, .2 ) ), to( rgba( 0, 0, 0, 0 ) ) );background:#111 -webkit-linear-gradient( top, rgba( 255, 255, 255, .2 ), rgba( 0, 0, 0, 0 ) );background:#111 -khtml-linear-gradient( top, rgba( 255, 255, 255, .2 ), rgba( 0, 0, 0, 0 ) );background:#111 -moz-linear-gradient( top, rgba( 255, 255, 255, .2 ), rgba( 0, 0, 0, 0 ) );background:#111 -o-linear-gradient( top, rgba( 255, 255, 255, .2 ), rgba( 0, 0, 0, 0 ) );background:#111 -ms-linear-gradient( top, rgba( 255, 255, 255, .2 ), rgba( 0, 0, 0, 0 ) );background:#111 -icab-linear-gradient( top, rgba( 255, 255, 255, .2 ), rgba( 0, 0, 0, 0 ) );background:#111 linear-gradient( top, rgba( 255, 255, 255, .2 ), rgba( 0, 0, 0, 0 ) );cursor:pointer}#dynamic-to-top:active{background:#111;background:#111 -webkit-gradient( linear, 0% 0%, 0% 100%, from( rgba( 0, 0, 0, .3 ) ), to( rgba( 0, 0, 0, 0 ) ) );background:#111 -webkit-linear-gradient( top, rgba( 0, 0, 0, .1 ), rgba( 0, 0, 0, 0 ) );background:#111 -moz-linear-gradient( top, rgba( 0, 0, 0, .1 ), rgba( 0, 0, 0, 0 ) );background:#111 -khtml-linear-gradient( top, rgba( 0, 0, 0, .1 ), rgba( 0, 0, 0, 0 ) );background:#111 -o-linear-gradient( top, rgba( 0, 0, 0, .1 ), rgba( 0, 0, 0, 0 ) );background:#111 -ms-linear-gradient( top, rgba( 0, 0, 0, .1 ), rgba( 0, 0, 0, 0 ) );background:#111 -icab-linear-gradient( top, rgba( 0, 0, 0, .1 ), rgba( 0, 0, 0, 0 ) );background:#111 linear-gradient( top, rgba( 0, 0, 0, .1 ), rgba( 0, 0, 0, 0 ) )}#dynamic-to-top,#dynamic-to-top:active,#dynamic-to-top:focus,#dynamic-to-top:hover{outline:none}#dynamic-to-top span{display:block;overflow:hidden;width:14px;height:12px;background:url( <?php echo url::base();?>media/images/dynamic-to-top/up.png )no-repeat center center}</style>
<style type="text/css">
#recaptcha_area, #recaptcha_table {
padding-left: 160px;
}
</style>
</head>
<body>
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
<div class="frm_form_field form-field  frm_required_field frm_left_container">
      <label class="frm_primary_label">姓名 <span class="frm_required">*</span></label>
  <input type="text" name="name" value="<?php if (isset($user['name'])) echo $user['name'];?>"  class="text required"/>
</div>
<div class="frm_form_field form-field  frm_required_field frm_left_container">
      <label class="frm_primary_label">Email <span class="frm_required">*</span></label>
  <input type="text" name="email" value="<?php if (isset($user['email'])) echo $user['email'];?>"  class="text required"/>
</div>
<div id="frm_field_69_container" class="frm_form_field form-field  frm_left_container">
     <label class="frm_primary_label">手机号码 <span class="frm_required">*</span>
        <span class="frm_required"></span>
    </label>
    <input type="text" name="mobile" value="<?php if (isset($user['mobile'])) echo $user['mobile'];?>"  size="20" class="tel auto_width"/>
</div>
<div id="frm_field_81_container" class="frm_form_field form-field  frm_left_container">
    <label class="frm_primary_label">上传预览图 <span class="frm_required">*</span>
        <span class="frm_required"></span>
    </label>
    <input type="file" class="file" id="fileupload" type="file" name="preview" data-url="<?php echo url::base();?>attach/upload_preview/preview/print3d" multiple/><br/>
<input type="hidden" name="item_meta[81]" value="" />
<div class="frm_description">
(Max Size:<?php echo kohana::config('upload.pic_max_size')/(1024*1024);?>MB)
<div id="progress" style="width: 200px;height: 18px">
    <div class="bar" style="width: 0%;height: 18px;background: red;"></div>
</div>
<span id="preview_pic"></span>
</div>
</div>
<div id="frm_field_191_container" class="frm_form_field form-field  frm_left_container">
    <label class="frm_primary_label">上传3D模型 <span class="frm_required">*</span>
        <span class="frm_required"></span>
    </label>
    <input type="file" class="file" id="modelupload" type="file" name="3dmodel" data-url="<?php echo url::base();?>attach/upload_model/3dmodel/model_stl" multiple/><br/>
<input type="hidden" name="item_meta[191]" value="" />
<div class="frm_description">
(Max Size:<?php echo kohana::config('upload.file_max_size')/(1024*1024);?>MB)
<div id="progressmodel" style="width: 200px;height: 18px">
    <div class="bar" style="width: 0%;height: 18px;background: red;"></div>
</div>
<span id="modelupload_tip"></span><div id="model_view" style="width:300px; height:200px"></div>
</div>
</div>
<div id="frm_field_71_container" class="frm_form_field form-field  frm_required_field frm_left_container">
    <label class="frm_primary_label">打印尺寸 <span class="frm_required">*</span>
    </label>
	<input type="text" name="sizel" class="text auto_width required" size="10"/>长
	<input type="text" name="sizew" class="text auto_width required" size="10"/>宽
	<input type="text" name="sizeh" class="text auto_width required" size="10"/>高
    <div class="frm_description">单位（mm），最小30*30*30</div>
</div>
<div id="frm_field_72_container" class="frm_form_field form-field  frm_required_field frm_left_container">
    <label class="frm_primary_label">打印数量 <span class="frm_required">*</span>
    </label>
    <input type="text" size="20" class="text auto_width required" name="quantity" value="1"/>
</div>
<div id="frm_field_76_container" class="frm_form_field form-field  frm_left_container">
    <label class="frm_primary_label">打印材料 <span class="frm_required">*</span>
        <span class="frm_required"></span>
    </label>
    <select name="material" id="material">
<?php
$material = order_Core::get_print_material();
$i = 0;
foreach ($material as $key => $val) {
?>
<option value="<?php echo $key;?>"<?php if ($i == 0) { echo ' selected'; }?>><?php echo $val;?></option>
<?php
$i++;
}
?>
</select>
    <!-- <div class="frm_radio"><input type="radio" name="item_meta[76]" id="field_76-0" value="ABS"   class="radio"/><label for="field_76-0">ABS</label></div>
    <div class="frm_radio"><input type="radio" name="item_meta[76]" id="field_76-1" value="PLA"   class="radio"/><label for="field_76-1">PLA</label></div> -->
</div>
<div id="frm_field_73_container" class="frm_form_field form-field  frm_left_container">
    <label class="frm_primary_label">材料颜色 <span class="frm_required">*</span>
    </label>
    <?php
$color = order_Core::get_print_color();
$i = 0;
foreach ($color as $key => $val) {
?>
<div class="frm_radio"><input type="radio" name="color" class="radio" value="<?php echo $val['filename'];?>"<?php if ($i == 0) { echo ' checked="checked"'; }?>/>
<img alt="<?php echo $val['filename'];?>" src="<?php echo $val['filepath'];?>" width="19px" height="20px"/>
</div>
<?php
$i++;
}
?>
<div class="frm_description">尼龙只有白色</div>
</div>
<div id="frm_field_112_container" class="frm_form_field form-field  frm_left_container">
    <label class="frm_primary_label">打印精度 <span class="frm_required">*</span>
    </label>
<!--     <div class="frm_radio"><input type="radio" name="item_meta[112]" id="field_112-1" value="Local - Ecopaqueteria (transport by bike - reserved to Madrid city)"   class="radio"/><label for="field_112-1">Local - Ecopaqueteria (transport by bike - reserved to Madrid city)</label></div>
    <div class="frm_radio"><input type="radio" name="item_meta[112]" id="field_112-2" value="Local, national and international"   class="radio"/><label for="field_112-2">Local, national and international</label></div>
    <div class="frm_radio"><input type="radio" name="item_meta[112]" id="field_112-0" value="" checked="checked"  class="radio"/><label for="field_112-0"></label></div>
 -->
 <select name="precision" id="precision">
<?php
$precision = order_Core::get_print_precision();
$i = 0;
foreach ($precision as $key => $val) {
?>
<option value="<?php echo $key;?>"<?php if ($i == 0) { echo ' selected'; }?>><?php echo $val;?></option>
<?php
$i++;
}
?></select>
</div>
<div id="frm_field_75_container" class="frm_form_field form-field  frm_left_container">
    <label class="frm_primary_label">我要留言<span class="frm_required"></span>
    </label>
    <textarea name="message" id="message" rows="5"  class="textarea required"></textarea>
</div>
<div id="frm_field_75_container" class="frm_form_field form-field  frm_left_container">
    <label class="frm_primary_label">验证码 <span class="frm_required">*</span></label>
<script>
var RecaptchaOptions = {
   theme : 'white'
};
</script>

<?php
include_once WEBROOT.'application/libraries/recaptchalib.php';
$publickey = "6LeEcuASAAAAAE5fahhuyG4RIw3ZgeuelPNxn1Fh";
echo recaptcha_get_html($publickey);
?>

</div>
<input type="hidden" name="item_key" value="" />
</div>
</fieldset>
</div>
<script type="text/javascript">
</script>
<p class="submit">
<input type="button" value="确认提交" id="submit_order"/><span id="tips"></span>&nbsp;&nbsp;<input type="button" value="估算价格" id="price_calc"/><span id="total_price"></span>
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
$('#material').change(function() {
	val = $(this).val();
	$("input:radio[name=color]").attr('disabled',false);
	if (val == 4) {
		$("input:radio[name=color]").each(function() {
			if ($(this).val() == 'white') {
				if ($(this).attr('checked') != 'checked') {
					//$(this).attr('checked',true);
					this.checked = true;
				}
			}
			else {
				$(this).attr('checked',false);
				$(this).attr('disabled',true);
			}
		});
	}
	else {
		/* $("input:radio[name=color]").each(function( index ) {
			if ($(this).attr('disabled') == 'disabled') {
				$(this).attr('disabled',false);
			}
			if ($(this).attr('checked') == 'checked') {
				$(this).attr('checked',false);
			}
			if (index == 0 && $(this).attr('checked') != 'checked') {
				this.checked = true;
			}
		}); */
	}
});
var preview_done = false;
var modelstl_done = false;
$(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        add: function (e, data) {
        	data.context = $("#preview_pic").text('正在上传，请稍后...');
            data.submit();
        },
        done: function (e, data) {
            if (data.result.code == 1) {
                img = '<img width="120px" height="100px" alt="'+data.result.data.name+'" src="<?php echo url::base();?>attach/pic/'+data.result.data.path+'"/>';
                $("#preview_pic").text('上传成功!').show();
            	$("#preview_pic").append(img);
            	$("#progress").fadeOut(1000);
            	preview_done = true;
            }
            else {
            	$("#preview_pic").text(data.result.msg);
            }
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        }
    });

    $('#modelupload').fileupload({
        dataType: 'json',
        add: function (e, data) {
        	data.context = $("#modelupload_tip").text('正在上传，请稍后...');
            data.submit();
        },
        done: function (e, data) {
            if (data.result.code == 1) {
                //model_view = '<script>init("<?php echo url::base();?>attach/stl/'+data.result.data.path+'","model_view");<\/script>';
                $("#modelupload_tip").text('上传成功!正在生成3D模型...').show().fadeOut(10000);
                init("<?php echo url::base();?>attach/stl/"+data.result.data.path,"model_view");
                //$("#modelupload_tip").append(model_view);

                //$("#modelupload_tip").text('生成完成!').show().fadeOut(5000);
                $("#progressmodel").fadeOut(1000);
                modelstl_done = true;
                //alert(data.result.data.path);
            }
            else {
            	$("#modelupload_tip").text(data.result.msg);
            }
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progressmodel .bar').css(
                'width',
                progress + '%'
            );
        }
    });
});
//model_type = ['.stl'];
//pic_type = ['.jpg','.jpeg','.png'];
$('#submit_order').click(function() {
	name = $("input[name=name]").val();
	email = $("input[name=email]").val();
	mobile = $("input[name=mobile]").val();
	material = $("#material").val();
	quantity = $("input[name=quantity]").val();
	color = $("input[name=color]:checked").val();
	precision = $("#precision").val();
	msg = $("#message").val();
	sizel = $("input[name=sizel]").val();
	sizew = $("input[name=sizew]").val();
	sizeh = $("input[name=sizeh]").val();
	//alert(color);return;
	rrf = $("#recaptcha_response_field").val();
	rcf = $("#recaptcha_challenge_field").val();
	if (name == '') {
		$("#tips").text("请填写真实姓名!").show().fadeOut(2000);
		return;
	}
	email_reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
	if (email == '' || !email_reg.test(email)) {
		$("#tips").text("请填写正确的电子邮箱地址!").show().fadeOut(2000);
		return;
	}
	if (!mobile.match(/^1[3|4|5|8][0-9]\d{4,8}$/)) {
		$("#tips").text("手机号码格式错误!").show().fadeOut(2000);
		return;
	}
	if (preview_done == false) {
		$("#tips").text("请上传预览图!").show().fadeOut(2000);
		return;
	}
	if (modelstl_done == false) {
		$("#tips").text("请上传3D模型文件!").show().fadeOut(2000);
		return;
	}
	if (sizel == false || sizel < 30) {
		$("#tips").text("最小为30mm!").show().fadeOut(2000);
		return;
	}
	if (sizew == false || sizew < 30) {
		$("#tips").text("最小为30mm!").show().fadeOut(2000);
		return;
	}
	if (sizeh == false || sizeh < 30) {
		$("#tips").text("最小为30mm!").show().fadeOut(2000);
		return;
	}
	if (quantity == false || quantity < 1) {
		$("#tips").text("请填写数量!").show().fadeOut(2000);
		return;
	}
	if (rrf == false || rcf == false) {
		$("#tips").text("请输入验证码!").show().fadeOut(2000);
		return;
	}
	$.ajax({
		url: '<?php echo url::base();?>service/print3d',
		type:'POST',
		data:{ "name": name,"email": email,"mobile": mobile,"material": material,"color": color,"precision": precision,"sizel": sizel,"sizew": sizew,"sizeh": sizeh,"quantity": quantity,"message": msg,"rrf":rrf,"rcf":rcf },
		dataType:'json',
		beforeSend: function () {
			$('#submit_order').attr('disabled',true);
			$('#submit_order').attr('value','loading');
			//$('#recap').submit();
		}
	})
			.done(function(j) {
				if(j.code == 1) {
					alert('Success!');
					window.location.href = '<?php echo url::base();?>service/print3d';
				}
				else{
					alert(j.msg);
					$('#submit_order').attr('disabled',false);
					$('#submit_order').attr('value','确认提交');
				}
			})
			.fail(function() {
				alert('Failed！');
				$('#submit_order').attr('disabled',false);
				$('#submit_order').attr('value','确认提交');
			})
			.always(function() {
				$('#submit_order').attr('disabled',false);
				$('#submit_order').attr('value','确认提交');
			});
});
$("#price_calc").click(function() {
	material = $("#material").val();
	quantity = $("input[name=quantity]").val();
	precision = $("#precision").val();
	sizel = $("input[name=sizel]").val();
	sizew = $("input[name=sizew]").val();
	sizeh = $("input[name=sizeh]").val();
	$.ajax({
		url: '<?php echo url::base();?>service/price_calc',
		type:'POST',
		data:{ "material": material,"precision": precision,"sizel": sizel,"sizew": sizew,"sizeh": sizeh,"quantity": quantity },
		dataType:'json',
		success:function(j) {
			if (j.code == 1) {
				$("#total_price").text('￥'+j.msg).show();
				return;
			}
			else {
				alert(j.msg);
				return;
			}
		}
	});
});
</script>
</body>
</html>