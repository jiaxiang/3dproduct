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
?>
</head>
<p>3D打印</p>
<form id="formid" action="<?php echo url::base();?>service/print3d" method="post" enctype="multipart/form-data">
姓名<input type="text" name="name"/><br/>
手机号码<input type="text" name="mobile"/><br/>
上传3D模型<input id="modelupload" type="file" name="3dmodel" data-url="<?php echo url::base();?>attach/upload_model/3dmodel/model_stl" multiple/>
(Max Size:<?php echo kohana::config('upload.file_max_size')/(1024*1024);?>MB)<br/>
<span id="modelupload_tip"></span>
<div id="progressmodel" style="width: 200px;height: 18px">
    <div class="bar" style="width: 0%;height: 18px;background: red;"></div>
</div>

上传预览图<input id="fileupload" type="file" name="preview" data-url="<?php echo url::base();?>attach/upload_preview/preview/print3d" multiple/>
(Max Size:<?php echo kohana::config('upload.pic_max_size')/(1024*1024);?>MB)<br/>
<span id="preview_pic"></span>
<div id="progress" style="width: 200px;height: 18px">
    <div class="bar" style="width: 0%;height: 18px;background: red;"></div>
</div>
<br/>
打印材料<select name="material" id="material">
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
</select><br/>
材料颜色
<?php
$color = order_Core::get_print_color();
$i = 0;
foreach ($color as $key => $val) {
?>
<input type="radio" name="color" value="<?php echo $val['filename'];?>"<?php if ($i == 0) { echo ' checked="checked"'; }?>/>
<img alt="<?php echo $val['filename'];?>" src="<?php echo $val['filepath'];?>" width="19px" height="20px"/>
<?php
$i++;
}
?>
<br/>
打印精度<select name="precision" id="precision">
<?php
$precision = order_Core::get_print_precision();
$i = 0;
foreach ($precision as $key => $val) {
?>
<option value="<?php echo $key;?>"<?php if ($i == 0) { echo ' selected'; }?>><?php echo $val;?></option>
<?php
$i++;
}
?></select><br/>
打印尺寸<input type="text" name="sizel"/>长<input type="text" name="sizew"/>宽<input type="text" name="sizeh"/>高（mm）<br/>
数量<input type="text" name="quantity" value="1"/><br/>
留言<textarea rows="5" cols="30" name="message" id="message"></textarea>
<p><a href="#" id="submit_order">submit</a>  <a href="<?php echo url::base();?>">back</a></p>
</form>
<p></p>
<span id="tips"></span>
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
        	data.context = $("#preview_pic").text('Uploading...');
            data.submit();
        },
        done: function (e, data) {
            if (data.result.code == 1) {
                img = '<img alt="'+data.result.data.name+'" src="<?php echo url::base();?>attach/pic/'+data.result.data.path+'"/>';
                $("#preview_pic").text('Ok!');
            	$("#preview_pic").append(img);
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
        	data.context = $("#modelupload_tip").text('Uploading...');
            data.submit();
        },
        done: function (e, data) {
            if (data.result.code == 1) {
                $("#modelupload_tip").text('Ok!');
                modelstl_done = true;
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
	mobile = $("input[name=mobile]").val();
	material = $("#material").val();
	quantity = $("input[name=quantity]").val();
	color = $("input[name=color]:checked").val();
	precision = $("#precision").val();
	sizel = $("input[name=sizel]").val();
	sizew = $("input[name=sizew]").val();
	sizeh = $("input[name=sizeh]").val();
	msg = $("#message").val();
	//alert(color);return;
	if (name == '') {
		$("#tips").text("请填写真实姓名!").show().fadeOut(1000);
		return;
	}
	if (!mobile.match(/^1[3|4|5|8][0-9]\d{4,8}$/)) {
		$("#tips").text("手机号码格式错误!").show().fadeOut(1000);
		return;
	}
	if (modelstl_done == false) {
		$("#tips").text("请上传3D模型文件!").show().fadeOut(1000);
		return;
	}
	if (preview_done == false) {
		$("#tips").text("请上传预览图!").show().fadeOut(1000);
		return;
	}
	if (sizel == false || sizel < 30) {
		$("#tips").text("最小为30mm!").show().fadeOut(1000);
		return;
	}
	if (sizew == false || sizew < 30) {
		$("#tips").text("最小为30mm!").show().fadeOut(1000);
		return;
	}
	if (sizeh == false || sizeh < 30) {
		$("#tips").text("最小为30mm!").show().fadeOut(1000);
		return;
	}
	if (quantity == false || quantity < 1) {
		$("#tips").text("请填写数量!").show().fadeOut(1000);
		return;
	}
	$.ajax({
		url: '<?php echo url::base();?>service/print3d',
		type:'POST',
		data:{ "name": name,"mobile": mobile,"material": material,"color": color,"precision": precision,"sizel": sizel,"sizew": sizew,"sizeh": sizeh,"quantity": quantity,"message": msg },
		dataType:'json',
		success:function(j) {
			if (j.code == 1) {
				alert('Success!');
				window.location.href = '<?php echo url::base();?>service/print3d';
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
</html>