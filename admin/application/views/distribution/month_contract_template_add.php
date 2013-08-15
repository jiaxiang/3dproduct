<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加新月结合约模板</li>
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
                	action="<?php echo url::base() . url::current();?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width=20%>模板名称</th>
                                    <td><input type="text" name="name" id="name" value=""><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th>彩种分类</th>
                                    <td><select name="type">
                                    		<option value="0" selected>普通</option>
                                    		<option value="7">北单</option>
                                    	</select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        	<thead>
                            	<tr class="headings">
                            		<th width="100px;">合约细则</th>
                            		<th width="600px;" style="text-align:left; padding-left:20px;">销售额范围：下限(达到) ~ 上限(少于)</th>
                            		<th width="600px;" style="text-align:left; padding-left:20px;">返点率(0.00~0.60)</th>
                            	</tr>
                        	</thead>
                            <tbody>
                            	<?php foreach ($contractDetailData as $item) {?>
                                <tr>
                                    <th>销售层级: &nbsp;<?php echo $item['grade']; ?>
                                    	<input type="hidden" name="grade-<?php echo $item['grade']; ?>" 
                                    		value="<?php echo $item['grade']; ?>"/>
                                    </th>
                                    <td>￥<input type="text" size="30" class="text" 
                                    		name="minimum-<?php echo $item['grade']; ?>" 
                                    		id="minimum-<?php echo $item['grade']; ?>" 
                                    		value="" maxlength="9"> 
                                    		&nbsp;~&nbsp;
                                    	￥<input type="text" size="30" class="text" 
                                    		name="maximum-<?php echo $item['grade']; ?>" 
                                    		id="maximum-<?php echo $item['grade']; ?>" 
                                    		value="" maxlength="9" 
                                    		onchange="onInputChange(this)" 
                                    		onkeyup="onInputChange(this)" />
                                    </td>
                                    <td>
                                    	<input type="text" size="10" class="text" 
                                    		name="rate-<?php echo $item['grade']; ?>" 
                                    		id="rate-<?php echo $item['grade']; ?>" 
                                    		value="" maxlength="5" >&nbsp;
                                    </td>
                                </tr>
                                <?php };?>
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
function CheckForm()
{
	for(var index=0; index<10; index++)
	{
		var miniInput = document.getElementById('minimum-'+index);
		var maxiInput = document.getElementById('maximum-'+index);
		var rateInput = document.getElementById('rate-'+index);
//		alert('minimum:'+miniInput.value+'\n maximum:'+maxiInput.value +'\n rate:'+rateInput.value);
		if (
				(miniInput.value == null || miniInput.value == '') && 
				(maxiInput.value == null || maxiInput.value == '') && 
				(rateInput.value == null || rateInput.value == '' )
		   )
		{
			continue;
		}
		if (miniInput.value != null && 
			(maxiInput.value == null || maxiInput.value == '') &&
			(rateInput.value == null || rateInput.value == '') 
			)
		{
			miniInput.value = null;
			continue;
		}
		if (rateInput.value == null || rateInput.value == '' )
		{
			alert('返点率不能为空, 返点细则'+index);
			return false;
		}
		if (rateInput.value < 0 || rateInput.value > 0.6) 
		{
			alert('返点率超出范围, 返点细则'+index);
			return false;
		}
	}
	return true;
}
</script>
<script type="text/javascript">
function onInputChange(input)
{
	
	var inputId = input.id;
	var start = inputId.indexOf('-') +1;
	var grade = inputId.substr(start);
	var nextGrade = parseInt(grade) +1;
	var nextMiniInput = document.getElementById('minimum-'+nextGrade);
//	alert(nextGrade);
	
	var inputValue = input.value;
	if(nextMiniInput != null){
		nextMiniInput.value = inputValue;
	}
}
</script>