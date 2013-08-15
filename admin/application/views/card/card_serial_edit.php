<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑卡系列</li>
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
								<th width=20%>卡系列编号</th>
								<td><?php echo $cardSerial['code'];?></td>
							</tr>
							<tr>
								<th width=20%>卡系列名称</th>
								<td><?php echo $cardSerial['name'];?></td>
							</tr>
							<tr>
								<th width=20%>创建时间</th>
								<td><?php echo $cardSerial['apdtime'];?></td>
							</tr>
							<tr>
								<th width=20%>更新时间</th>
								<td><?php echo $cardSerial['updtime'];?></td>
							</tr>
							<tr>
								<th>卡系列的起始号码（管理号）</th>
								<td><input type="text" name="beginNum" id="beginNum" maxlength="15" 
										value="<?php echo $cardSerial['bgnnum']; ?>" /> &nbsp;*
									(管理号是15位长的数字)
								</td>
							</tr>
							<tr>
								<th>卡系列的结束号码（管理号）</th>
								<td><input type="text" name="endNum" id="endNum" maxlength="15" 
										value="<?php echo $cardSerial['endnum']; ?>" /> &nbsp;*
									(管理号是15位长的数字)
								</td>
							</tr>
							<tr>
								<th>充值卡类型</th>
								<td><select name="cardType">
									<?php foreach ($cardTypeMap as $aKey => $aType) 
									{
										if ($cardSerial['cardtype'] == $aKey) {
											echo "<option value=\"".$aKey."\" selected >".$aType."</option>";
										} else {
											echo "<option value=\"".$aKey."\">".$aType."</option>";
										}
									}
									?>
									</select>
								</td>
							</tr>
							<tr>
								<th>每张卡的初始点数</th>
								<td><input type="text" name="points" id="points" 
										value="<?php echo $cardSerial['points'];?>" />&nbsp;*
								</td>
							</tr>
							<tr>
								<th>每张卡对应的RMB面额</th>
								<td><input type="text" name="perMoneyRMB" id="perMoneyRMB" 
										value="<?php echo $cardSerial['permoneyrmb'];?>" />&nbsp;*
									(该充值卡充入用户本金帐户的金额)
								</td>
							</tr>
							<tr>
								<th>每张卡对应的JPY面额</th>
								<td><input type="text" name="perMoneyJPY" id="perMoneyJPY" 
										value="<?php echo $cardSerial['permoneyjpy'];?>" />&nbsp;*
									(该充值卡面向日本市场的销售金额)
								</td>
							</tr>
							<tr>
								<th>每张卡的成本(RMB)</th>
								<td><input type="text" name="perCost" id="perCost" 
										value="<?php echo $cardSerial['percost'];?>"/>&nbsp;*
								</td>
							</tr>
							<tr>
								<th>状态</th>
								<td><select name="flag">
									<?php if($cardSerial['flag'] == 0 && $cardSerial['preflag'] == 2) { ?>
										<option value="0" selected >关闭</option>
										<option value="2" >未生成子卡</option>
									<?php } else if ($cardSerial['flag'] == 0 && $cardSerial['preflag'] == 4)  { ?>
										<option value="0" selected >关闭</option>
										<option value="4" >已生成子卡</option>
									<?php } else if ($cardSerial['flag'] == 2)  { ?>
										<option value="2" selected >未生成子卡</option>
										<option value="0" >关闭</option>
									<?php } else if ($cardSerial['flag'] == 4)  { ?>
										<option value="4" selected >已生成子卡</option>
										<option value="0" >关闭</option>
									<?php } else if ($cardSerial['flag'] == 6)  { ?>
										<option value="6" selected >锁定</option>
										<option value="0" >关闭</option>
									<?php } ?>
									</select>&nbsp;*
								</td>
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

	var passInput = document.getElementById("cardPass");
	if (passInput.value == null || passInput.value == '') {
		alert("请填入每张卡的初始密码");
		return false;
	}
	
	var pointsInput = document.getElementById("points");
	if (pointsInput.value == null || pointsInput.value == '') {
		alert("请填入每张卡的初始点数");
		return false;
	}
	if (isNaN(pointsInput.value) == true) {
		alert("初始点数必须是数字");
		return false;
	}
	
	var perMoneyRMBInput = document.getElementById("perMoneyRMB");
	if (perMoneyRMBInput.value == null || perMoneyRMBInput.value == '') {
		alert("请填入对应的RMB面额");
		return false;
	}
	if (isNaN(pointsInput.value) == true) {
		alert("面额必须是数字");
		return false;
	}
	var perMoneyJPYInput = document.getElementById("perMoneyJPY");
	if (codeInput.value == null || codeInput.value == '') {
		alert("请填入对应的JPY面额");
		return false;
	}
	if (isNaN(pointsInput.value) == true) {
		alert("面额必须是数字");
		return false;
	}
	
	var perCostInput = document.getElementById("perCost");
	if (perCostInput.value == null || perCostInput.value == '') {
		alert("请填入每张卡的成本");
		return false;
	}
	if (isNaN(perCostInput.value) == true) {
		alert("成本必须是数字");
		return false;
	}
	
	return true;
}
</script>
