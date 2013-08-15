<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑卡类型</li>
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
								<th width=20%>卡类型ID</th>
								<td><?php echo $cardType['id'];?></td>
							</tr>
							<tr>
								<th width=20%>卡系列名称</th>
								<td><input type="text" name="name" id="name" 
									value="<?php echo $cardType['name'];?>" />
								</td>
							</tr>
							<tr>
								<th width=20%>创建时间</th>
								<td><?php echo $cardType['apdtime'];?></td>
							</tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="list_save">
                        <input type="submit" name="submit" class="ui-button" value=" 确认更改 " >
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
	var nameInput = document.getElementById("name");
	if (nameInput.value == null || nameInput.value == '') {
		alert("请填入卡类型名称");
		return false;
	}
	
	return true;
}
</script>
