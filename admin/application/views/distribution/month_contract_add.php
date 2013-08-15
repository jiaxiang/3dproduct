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
                <form id="add_form" name="add_form" method="post" onsubmit="return CheckForm()" 
                	action="<?php echo url::base() . url::current();?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
		                	<?php if (isset($relation) == true) {?>
		                	<input type="hidden" name="relationId" value="<?php echo $relation['id'];?>" />
		                	<?php } ?>
                            <tbody>
                                <tr>
									<th width=20%>上级代理</th>
									<td>
									<?php if (isset($agent) == false) {
										echo '竞波网';
										echo "<input type='hidden' name='agentId' value='0' />";
                                    } else {
										echo $agent['lastname'];
										echo "<input type='hidden' name='agentId' value='".$agent['id']."' />";
                                    }
                                    ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th width=20%>下级客户 用户名(ID)</th>
                                    <td><?php echo $client['lastname'].'('.$client['id'].')'; ?>
                                    	<input type="hidden" name="clientId" value="<?php echo $client['id'];?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>合约类型</th>
                                    <td>
                                    	<input type="hidden" name="contractType" class="text" value="0">普通代理返利(0)
                                    </td>
                                </tr>
                                <tr>
                                    <th>彩种分类</th>
                                    <td><select id="type" name="type">
                                    		<option value="0" selected>普通</option>
                                    		<option value="7">北单</option>
                                    	</select><span class="required"> *</span>
                                    </td>
                                </tr>
                                <?php 
                                if (isset($rate_ability_array)) {
                                	//var_dump($rate_ability_array);die();
                                ?>
                                <tr>
                                    <th>代理返点能力</th>
                                    <td>
                                    	<span id="rate_ability_0" class="required"><?php echo $rate_ability_array['0'];?></span>
                                    	<span id="rate_ability_7" class="required"><?php echo $rate_ability_array['7'];?></span>
                                    	<span class="brief-input-state notice_inline">返点能力代表该代理可以允诺给下级客户的最大返点值</span>
                                    	<input type="hidden" id="rateAbility" name="rateAbility" value="<?php echo $rate_ability_array['0'];?>" />
                                    </td>
                                </tr>
                                <?php } ?>
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
                    
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        	<thead>
                            	<tr class="headings">
                            		<th width="100px;">合约细则</th>
                            		<th width="600px;" style="text-align:left; padding-left:20px;">销售额范围：下限(达到) ~ 上限(少于)</th>
                            		<th width="300px;" style="text-align:left; padding-left:20px;">返点率(0.00~0.60)</th>
                            	</tr>
                        	</thead>
                            <tbody>
                            	<?php foreach ($contractDetailData as $item) :?>
                                <tr>
                                    <th>销售层级: &nbsp;<?php echo $item['grade']; ?>
                                    	<input type="hidden" name="grade-<?php echo $item['grade']; ?>" 
                                    		value="<?php echo $item['grade']; ?>"/>
                                    </th>
                                    <td>￥<input type="text" size="30" class="text" 
                                    		name="minimum-<?php echo $item['grade']; ?>" 
                                    		id="minimum-<?php echo $item['grade']; ?>" 
                                    		value="<?php echo $item['minimum']; ?>" maxlength="9" 
                                    		onchange="onInputChange(input)" />
										&nbsp;~&nbsp;
										￥<input type="text" size="30" class="text" 
                                    		name="maximum-<?php echo $item['grade']; ?>" 
                                    		id="maximum-<?php echo $item['grade']; ?>" 
                                    		value="<?php echo $item['maximum']; ?>" maxlength="9"
                                    		onchange="onInputChange(this)" 
                                    		onkeyup="onInputChange(this)" /></td>
                                    <td><input type="text" size="10" class="text" 
                                    		name="rate-<?php echo $item['grade']; ?>" 
                                    		id="rate-<?php echo $item['grade']; ?>" 
                                    		value="<?php echo $item['rate']; ?>" maxlength="5">&nbsp;
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <div class="list_save">
                        <input type="submit" name="submit" class="ui-button" value=" 确认添加 " >
                    </div>
					<div class="out_box">
						<span>注意：销售层级请从小到大依次输入，例：</span><br />
						<span style="padding-left:30px;">销售层级:1, ￥0  ~￥1000, 0.02</span><br />
                    	<span style="padding-left:30px;">销售层级:2, ￥1000~￥2000, 0.03</span><br />
                    	<span style="padding-left:30px;">销售层级:3, ￥2000~￥3000, 0.04</span><br />
                    	<span style="padding-left:30px;">销售层级:4, ￥3000~￥4000, 0.05</span><br />
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
		$('#rate_ability_7').hide();
		$('#type').change(function(){
			if ($('#type').attr('value') == 0) {
				$('#rate_ability_0').show();
				$('#rate_ability_7').hide();
				$('#rateAbility').attr('value', $('#rate_ability_0').html());
			} else if ($('#type').attr('value') == 7) {
				$('#rate_ability_0').hide();
				$('#rate_ability_7').show();
				$('#rateAbility').attr('value', $('#rate_ability_7').html());
			}
		});
	});

</script>
<script type="text/javascript">
function CheckForm()
{
	var rateAbilityHidden = document.getElementById('rateAbility');
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
		if (rateInput.value == null || rateInput.value == '' )
		{
			alert('返点率不能为空, 返点细则'+index);
			return false;
		}
		if (rateInput.value < 0 || rateInput.value > rateInput.value > rateAbilityHidden.value) 
		{
			alert('返点率超出上级代理返点能力, 返点细则'+index);
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