<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加中日汇率方案</li>
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
								<th width=20%>汇率方案编号</th>
								<td><input type="hidden" name="code" id="code" value="<?php echo $moneyExchangeCode;?>" />
									<?php echo $moneyExchangeCode;?>
								</td>
							</tr>
							<tr>
								<th width=20%>汇率方案名称</th>
								<td><input type="text" name="name" id="name" value="" /> &nbsp;*</td>
							</tr>
							<tr>
								<th>100 RMB兑换JPY</th>
								<td><input type="text" name="num" id="num" /> &nbsp;*</td>
							</tr>
							<tr>
								<th>100 JPY兑换RMB</th>
								<td><input type="text" name="num2" id="num2" /> &nbsp;*</td>
							</tr>
							<tr>
								<th>状态</th>
								<td>关闭<input type="hidden" name="flag" id="flag" value="0" /></td>
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
	});

</script>
<script type="text/javascript">
function CheckForm()
{
	var codeHidden = document.getElementById("code");
	if (codeHidden.value == null || codeHidden.value == '') {
		alert("操作员信息缺失！");
		return false;
	}
	
	var nameInput = document.getElementById("name");
	if (nameInput.value == null || nameInput.value == '') {
		alert("请输入方案名称");
		return false;
	}
	
	var numInput = document.getElementById("num");
	if (numInput.value == null || numInput.value == '') {
		alert("请填入RMB兑换JPY的值");
		return false;
	}
	if (isNaN(numInput.value) == true) {
		alert("汇率必须是数字");
		return false;
	}
	
	var num2Input = document.getElementById("num2");
	if (num2Input.value == null || num2Input.value == '') {
		alert("请填入JPY兑换RMB的值");
		return false;
	}
	if (isNaN(num2Input.value) == true) {
		alert("汇率必须是数字");
		return false;
	}
	
	return true;
}
</script>
