<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li ><a href='<?php echo url::base().'card/bill/issue_bill/';?>'>发行单据列表</a></li>
				<li class="on"><a href='<?php echo url::base().'card/bill/open_bill/';?>'>开卡单据列表</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
			<ul class="pro_oper">
				<li>
				</li>
			</ul>
        </div>
		
		<?php if (is_array($data) && count($data)) {?>
		<form id="list_form" name="list_form" method="post" 
			action="<?php echo url::base().url::current();?>">
		<table cellspacing="0">
			<thead>
				<tr class="headings">
				<th width="70px">操作</th>
				<th width="70px">单据编号</th>
				<th width="20px">发行批次ID</th>
				<th width="50px">销售渠道</th>
				<th width="50px">起始卡号</th>
				<th width="50px">结束卡码</th>
				<th width="30px">添加时间</th>
				<th width="30px">更新时间</th>
				<th width="30px">操作员ID</th>
				<th width="30px">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) { ?>
				<tr>
					<td>
					<a href="<?php echo url::base().'card/bill/open_bill_detail/'.$item['id']; ?>" target="_blank">查看</a>&nbsp;
					</td>
					<td><?php echo $item['num'];?></td>
					<td><?php echo $item['issueid']; ?></td>
					<td><?php echo $channelList[$item['channelid']]['name'];?></td>
					<td><?php echo $item['bgnnum'];?></td>
					<td><?php echo $item['endnum'];?></td>
					<td><?php echo $item['apdtime'];?></td>
					<td><?php echo $item['updtime'];?></td>
					<td><?php echo $item['user_id'];?></td>
					<td><?php if($item['flag'] == 0) {echo '已撤销';}?>
						<?php if($item['flag'] == 2) {echo '正常';}?>
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
