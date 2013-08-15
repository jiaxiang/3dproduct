<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">充值卡开卡</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--** edit start**-->
            <div class="edit_area">
                <form id="add_form" name="add_form" method="post" onsubmit="return CheckForm()" 
                	action="<?php echo url::base().url::current();?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
							<tr>
								<th width=20%>操作员ID</th>
								<td><input type="hidden" name="checkUserId" id="checkUserId" value="<?php echo $manager['id'];?>" />
									<input type="hidden" name="checkUser" id="checkUser" value="<?php echo $manager['name'];?>" />
									<?php echo $manager['name'].'['.$manager['id'].']'; ?>
								</td>
							</tr>
							<tr>
								<th>发行充值卡的起始号码</th>
								<td><input type="text" name="beginNum" id="beginNum" maxlength="15" /> &nbsp;*
									(管理号是15位长的数字)
								</td>
							</tr>
							<tr>
								<th>发行充值卡的结束号码</th>
								<td><input type="text" name="endNum" id="endNum" maxlength="15" /> &nbsp;*
									(管理号是15位长的数字)
								</td>
							</tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="list_save">
                        <input type="submit" name="submit" class="ui-button" value=" 确认添加 " >
                    </div>
                    
                </form>
            </div>
            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->

<div id='example' style="display:none;"></div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#beginNum').focus(function(){
			$('#beginNum').after('<span id="beginNumShadow" style="display:none; width:350px; font-weight:bold; font-size:20px; margin-left:10px; "> </span>');
			var beginValue = $('#beginNum').attr('value');
			if (beginValue.length >= 5 && beginValue.length <10){
				beginValue = beginValue.substr(0,5)+'-'+beginValue.substr(5);
			} else if (beginValue.length >= 10 ) {
				beginValue = beginValue.substr(0,5)+'-'+beginValue.substr(5,5)+'-'+beginValue.substr(10);
			}
			$('#beginNumShadow').html(beginValue);
			$('#beginNumShadow').show();
		});
		$('#beginNum').blur(function(){
			$('#beginNumShadow').hide();
			$('#beginNumShadow').remove();
		});
		$('#beginNum').keyup(function(){
			var beginValue = $('#beginNum').attr('value');
			if (beginValue.length >= 5 && beginValue.length <10){
				beginValue = beginValue.substr(0,5)+'-'+beginValue.substr(5);
			} else if (beginValue.length >= 10 ) {
				beginValue = beginValue.substr(0,5)+'-'+beginValue.substr(5,5)+'-'+beginValue.substr(10);
			}
			$('#beginNumShadow').html(beginValue);
		});
		
		$('#endNum').focus(function(){
			$('#endNum').after('<span id="endNumShadow" style="display:none; width:350px; font-weight:bold; font-size:20px; margin-left:10px; "></span>');
			var endValue = $('#endNum').attr('value');
			if (endValue.length >= 5 && endValue.length <10){
				endValue = endValue.substr(0,5)+'-'+endValue.substr(5);
			} else if (endValue.length >= 10 ) {
				endValue = endValue.substr(0,5)+'-'+endValue.substr(5,5)+'-'+endValue.substr(10);
			}
			$('#endNumShadow').html(endValue);
			$('#endNumShadow').show();
		});
		$('#endNum').blur(function(){
			$('#endNumShadow').hide();
			$('#endNumShadow').remove();
		});
		$('#endNum').keyup(function(){
			var endValue = $('#endNum').attr('value');
			if (endValue.length >= 5 && endValue.length <10){
				endValue = endValue.substr(0,5)+'-'+endValue.substr(5);
			} else if (endValue.length >= 10 ) {
				endValue = endValue.substr(0,5)+'-'+endValue.substr(5,5)+'-'+endValue.substr(10);
			}
			$('#endNumShadow').html(endValue);
		});
	});
</script>
<script type="text/javascript">
function CheckForm()
{
	var userHidden = document.getElementById("checkUserId");
	if (userHidden.value == null || userHidden.value == '') {
		alert("操作员信息缺失！");
		return false;
	}
	
	var bgnNumInput = document.getElementById("beginNum");
	if (bgnNumInput.value == null || bgnNumInput.value == '') {
		alert("请填入起始卡号");
		return false;
	}
	if (bgnNumInput.value.length != 15) {
		alert("起始卡号不满15位");
		return false;
	}
	if (isNaN(bgnNumInput.value) == true) {
		alert("起始卡号必须是数字");
		return false;
	}
	
	var endNumInput = document.getElementById("endNum");
	if (endNumInput.value == null || endNumInput.value == '') {
		alert("请填入结束卡号");
		return false;
	}
	if (endNumInput.value.length != 15) {
		alert("结束卡号不满15位");
		return false;
	}
	if (isNaN(endNumInput.value) == true) {
		alert("结束卡号必须是数字");
		return false;
	}
	
	return true;
}
</script>
