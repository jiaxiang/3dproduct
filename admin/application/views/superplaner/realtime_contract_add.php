<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加新合约</li>
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
                <form id="add_form" name="add_form" method="post" onsubmit="return FormCheck()"
                	action="<?php echo url::base().url::current();?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width=20%>代理用户名(ID)</th>
                                    <td><?php echo $user['lastname'].'('.$user['id'].')'; ?>
                                    	<input type="hidden" name="user_id" value="<?php echo $user['id'];?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <th>合约类型</th>
                                    <td><select name="contract_type">
                                    		<option value="0" selected>普通</option>
                                    		<option value="7">北单</option>
                                    	</select><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>返点率</th>
                                    <td>
                                    	<input type="text" size="30" name="rate" class="text" value="0.000" id="rate">
                                    	<span class="required"> *</span>
                                    	<span>&nbsp;数值范围在(0.00~0.60)之间&nbsp;</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>生效时间</th>
                                    <td><?php echo date("Y-m-d H:i:s",time());?>
                                    	<input type="hidden" name="starttime" id="starttime" class="text" 
                                    		value="<?php echo date("Y-m-d H:i:s",time());?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <th>返利税率</th>
                                    <td><input type="hidden" name="taxrate" class="text" value="0">0.000</td>
                                </tr>
                                <tr>
                                    <th>合约状态</th>
                                    <td>关闭<input type="hidden" name="flag" value="0"/>
                                    	<span style="padding-left:30px;">请在合约创建后，再将其设置成有效状态。</span>
                                    </td>
                                </tr>
                                <tr>
                                	<th>备注：</th>
                                	<td>
                                		<textarea maxlength="255" type="textarea" class="text valid" rows="5" cols="56" name="note"></textarea>
                                		<span class="brief-input-state notice_inline">用户备注，请不要超过250字节。</span>
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
    	$('#starttime').datepicker({dateFormat:"yy-mm-dd"});
    });

</script>
<script type="text/javascript">
function FormCheck()
{
	var rateInput = document.getElementById('rate');
	if(rateInput.value == null || rateInput.value == ''){
		alert('返点率不能为空');
		return false;
	}
	if(rateInput.value < 0 || rateInput.value > 0.6){
		alert('返点率超出范围');
		return false;
	}
	
	return true;
}
</script>