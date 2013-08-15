<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加下级用户</li>
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
                                    <th width=20%>上级代理&lt;用户名(ID)&gt;</th>
                                    <td><?php echo $agent['lastname'].'('.$agent['id'].')'; ?>
                                    	<input type="hidden" name="agentId" value="<?php echo $agent['id'];?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th width=20%>下级客户 &lt;用户名(ID)&gt;</th>
                                    <td><?php echo $client['lastname'].'('.$client['id'].')'; ?>
                                    	<input type="hidden" name="clientId" value="<?php echo $client['id'];?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>用户类型</th>
                                    <td><select name="clientType">
                                    	<?php foreach ($clientTypeArray as $key => $value) { ?>
                                    		<option value="<?php echo $value; ?>"><?php echo $key; ?></option>
                                    	<?php } ?>
                                    	</select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>状态</th>
                                    <td>正常<input type="hidden" name="flag" value="2"/></td>
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