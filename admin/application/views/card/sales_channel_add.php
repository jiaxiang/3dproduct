<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加销售渠道</li>
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
								<th width=20%>销售渠道编号</th>
								<td><input type="hidden" name="code" id="code" value="<?php echo $channelCode; ?>" /> 
									<?php echo $channelCode; ?>
								</td>
							</tr>
							<tr>
								<th width=20%>销售渠道名称</th>
								<td><input type="text" name="name" id="name" /> &nbsp;*</td>
							</tr>
							<tr>
								<th>销售渠道描述</th>
								<td><input type="text" name="des" id="des" size="50" /></td>
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
	var codeInput = document.getElementById("code");
	if (codeInput.value == null || codeInput.value == '') {
		alert("请填入销售渠道编号");
		return false;
	}
	
	var nameInput = document.getElementById("name");
	if (nameInput.value == null || nameInput.value == '') {
		alert("请填入销售渠道名称");
		return false;
	}
	
	return true;
}
</script>
