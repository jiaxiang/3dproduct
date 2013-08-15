<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base().'card/card/';?>'>充值卡查询</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
			<div style="float:left;">
			<ul class="pro_oper">
				<li>
					<a href='/card/card_serial'>
						<span class="add_pro">创建充值卡</span></a>
				</li>
			</ul>
			</div>
			<div style="float:right;">
				<form id="search_form" name="search_form" method="get" onsubmit="return CheckForm()" 
	                action="<?php echo url::base().url::current();?>">
				<ul class="pro_oper" >
					<li>
						卡号范围搜索：
						<input type="text" name="beginNum" id="beginNum" maxlength="15" size="17" value="<?php echo $query_condition['beginNum'];?>" />
						~ <input type="text" name="endNum" id="endNum" maxlength="15" size="17" value="<?php echo $query_condition['endNum'];?>" />
					</li>
					<li>
						搜索：
						<select name="selectKey">
						<?php foreach ($selectMap as $key => $value) { ?>
							<option value="<?php echo $key; ?>" <?php if ($query_condition['selectKey'] == $key) {echo 'selected';}?> >
								<?php echo $value;?>
							</option>
						<?php }?>
						</select>
						<input type="text" name="selectValue" id="selectValue" value="<?php echo $query_condition['selectValue'];?>" />
					</li>
					<li>
						<input type="button" value="清除" onclick="ClearInput()"/>&nbsp;
						<input type="submit" value="查询" />
					</li>
				</ul>
				</form>
			</div>
			<div style="clear:both;"></div>
        </div>
		
		<?php if (is_array($data) && count($data)) {?>
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base().url::current();?>">
			<input type="hidden" name="returnURL" value="<?php echo $_SERVER['REQUEST_URI'];?>"/>
		<table  cellspacing="0">
			<thead>
				<tr class="headings">
				<th width="50px">操作</th>
				<th width="70px">管理号(ID)</th>
				<th width="70px">卡系列编号</th>
				<th width="50px">初始点数</th>
				<th width="50px">创建日期</th>
				<th width="50px">更新时间</th>
				<th width="20px">发行ID</th>
				<th width="50px">发行时间</th>
				<th width="20px">开卡ID</th>
				<th width="50px">开卡时间</th>
				<th width="20px" class="txc">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) { ?>
				<tr>
					<td><a href="<?php echo url::base().'card/card/edit/'.$item['id']; ?>">编辑</a>&nbsp;
					</td>
					<td>
						<a href="<?php echo url::base().'card/card/detail/'.$item['id']; ?>" target="_blank">
							<?php echo $item['mgrnum']; echo '('.$item['id'].')'; ?>
						</a>
					</td>
					<td>
						<a href="<?php echo url::base().'card/card_serial/detail/'.$item['cardserialid'];?>" target="_blank">
							<?php echo $item['cardserialcode'];?>
						</a>
					</td>
					<td><?php echo $item['points'];?></td>
					<td><?php echo $item['apdtime'];?></td>
					<td><?php echo $item['updtime'];?></td>
					<td><?php echo $item['issueid'];?></td>
					<td><?php echo $item['issuetime'];?></td>
					<td><?php echo $item['openid'];?></td>
					<td><?php echo $item['opentime'];?></td>
					<td class="txc">
					<?php if ($item['flag'] == 0) {echo '关闭[0]';}
					 else if ($item['flag'] == 2) {echo '待发行[2]';}
					 else if ($item['flag'] == 4) {echo '已发行[4]';}
					 else if ($item['flag'] == 6) {echo '已开卡[6]';}
					 else if ($item['flag'] == 8) {echo '已使用[8]';}
					?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		</form>
        <?php }else {?>
        <?php echo remind::no_rows();?>
        <?php }?>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
        <?php echo view_tool::per_page(); ?>
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
            <?PHP echo $this->pagination->render('opococ'); ?>
        </div>
    </div>
</div>
<!--END FOOTER-->

<script type="text/javascript">
function CheckForm()
{
	// beginNum
	// endNum
	var bgnNumInput = document.getElementById("beginNum");
	var endNumInput = document.getElementById("endNum");
	
	if (bgnNumInput.value != null && bgnNumInput.value != '') 
	{
		if (endNumInput.value == null || endNumInput.value == '')
		{
			alert("请填入结束卡号");
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
	}
	if (endNumInput.value != null && endNumInput.value != '') 
	{
		if (bgnNumInput.value == null || bgnNumInput.value == '')
		{
			alert("请填入起始卡号");
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
	}

	return true;
}
</script>
<script type="text/javascript">
function ClearInput()
{
	var bgnNumInput = document.getElementById("beginNum");
	bgnNumInput.value = '';
	var endNumInput = document.getElementById("endNum");
	endNumInput.value = '';
	var selectValueInput = document.getElementById("selectValue");
	selectValueInput.value = '';
}
</script>
