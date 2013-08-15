<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">充值卡信息</li>
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
								<th width=20%>管理号</th>
								<td><?php echo $card['mgrnum']; echo '('.$card['id'].')'; ?></td>
							</tr>
							<tr>
								<th width=20%>初始密码</th>
								<td>
									<input type="text" name="cardPass" id="cardPass" 
										value="<?php echo $card['cardpass'];?>" /> &nbsp;*&nbsp;(密码是10位长的数字)
								</td>
							</tr>
							<tr>
								<th width=20%>创建时间</th>
								<td><?php echo $card['apdtime'];?></td>
							</tr>
							<tr>
								<th width=20%>更新时间</th>
								<td><?php echo $card['updtime'];?></td>
							</tr>
							<tr>
								<th width=20%>卡系列ID</th>
								<td><?php echo $card['cardserialid'];?></td>
							</tr>
							<tr>
								<th>卡系列编号</th>
								<td><?php echo $card['cardserialcode']; ?></td>
							</tr>
							<tr>
								<th>初始点数</th>
								<td>
									<input type="text" name="points" id="points" 
										value="<?php echo $card['points'];?>" /> &nbsp;*
								</td>
							</tr>
							<tr>
								<th>对应的RMB面额</th>
								<td>
									<input type="text" name="moneyRMB" id="moneyRMB" 
										value="<?php echo $card['moneyrmb'];?>" /> &nbsp;*
									(该充值卡充入用户本金帐户的金额)
								</td>
							</tr>
							<tr>
								<th>对应的JPY面额</th>
								<td>
									<input type="text" name="moneyJPY" id="moneyJPY" 
										value="<?php echo $card['moneyjpy'];?>" /> &nbsp;*
									(该充值卡面向日本市场的销售金额)
								</td>
							</tr>
							<tr>
								<th>销售成本(RMB)</th>
								<td>
									<input type="text" name="saleCost" id="saleCost" 
										value="<?php echo $card['salecost'];?>" /> &nbsp;*
								</td>
							</tr>
							<tr>
								<th>发行批次ID</th>
								<td><?php echo $card['issueid'];?></td>
							</tr>
							<tr>
								<th width=20%>发行时间</th>
								<td><?php echo $card['issuetime'];?></td>
							</tr>
							<tr>
								<th width=20%>开卡批次ID</th>
								<td><?php echo $card['openid'];?></td>
							</tr>
							<tr>
								<th width=20%>开卡时间</th>
								<td><?php echo $card['opentime'];?></td>
							</tr>
							<tr>
								<th>状态</th>
								<td>
									<select name="flag">
								<?php if ($card['flag'] == 0 && $card['preflag'] == 0) {?>
										<option value="0" selected>关闭</option>
								<?php } else if ($card['flag'] == 0 && $card['preflag'] == 2) {?>
										<option value="0" selected>关闭</option>
										<option value="2" >待发行</option>
								<?php } else if ($card['flag'] == 0 && $card['preflag'] == 4) {?>
										<option value="0" selected>关闭</option>
										<option value="4" >已发行</option>
								<?php } else if ($card['flag'] == 0 && $card['preflag'] == 6) {?>
										<option value="0" selected>关闭</option>
										<option value="6" >已开卡</option>
								<?php } else if ($card['flag'] == 0 && $card['preflag'] == 8) {?>
										<option value="0" selected>关闭</option>
										<option value="6" >已使用</option>
								<?php } else if ($card['flag'] == 2) {?>
										<option value="2" selected>待发行</option>
										<option value="0" >关闭</option>
								<?php } else if ($card['flag'] == 4) {?>
										<option value="4" selected>已发行</option>
										<option value="0" >关闭</option>
								<?php } else if ($card['flag'] == 6) {?>
										<option value="6" selected>已开卡</option>
										<option value="0" >关闭</option>
								<?php } else if ($card['flag'] == 8) {?>
										<option value="8" selected>已使用</option>
										<option value="0" >关闭</option>
								<?php } ?>
									</select> &nbsp;*
								</td>
							</tr>
                            </tbody>
                        </table>
                    </div>
                    
					<div class="list_save">
						<input type="submit" name="submit" class="ui-button" value=" 确认更改 " />
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
	$(document).ready(function(){});
</script>

<script type="text/javascript">
function CheckForm()
{
	var passInput = document.getElementById("cardPass");
	if (passInput.value == null || passInput.value == '') {
		alert("请填入初始密码");
		return false;
	}
	
	var pointsInput = document.getElementById("points");
	if (pointsInput.value == null || pointsInput.value == '') {
		alert("请填入初始点数");
		return false;
	}
	if (isNaN(pointsInput.value) == true) {
		alert("初始点数必须是数字");
		return false;
	}
	
	var perMoneyRMBInput = document.getElementById("moneyRMB");
	if (perMoneyRMBInput.value == null || perMoneyRMBInput.value == '') {
		alert("请填入对应的RMB面额");
		return false;
	}
	if (isNaN(pointsInput.value) == true) {
		alert("面额必须是数字");
		return false;
	}
	var perMoneyJPYInput = document.getElementById("moneyJPY");
	if (codeInput.value == null || codeInput.value == '') {
		alert("请填入对应的JPY面额");
		return false;
	}
	if (isNaN(pointsInput.value) == true) {
		alert("面额必须是数字");
		return false;
	}
	
	var perCostInput = document.getElementById("saleCost");
	if (perCostInput.value == null || perCostInput.value == '') {
		alert("请填入销售成本");
		return false;
	}
	if (isNaN(perCostInput.value) == true) {
		alert("销售成本必须是数字");
		return false;
	}
	
	return true;
}
</script>
