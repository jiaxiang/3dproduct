<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base().'card/card_log/';?>'>日志列表</a></li>
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
				<th width="100px">发生时间</th>
				<th width="20px">操作员ID</th>
				<th width="40px">目标数据</th>
				<th width="20px">目标数据ID</th>
				<th width="40px">操作内容</th>
				<th width="300px">操作详细</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) { ?>
				<tr>
					<td><?php echo $item['apdtime'];?></td>
					<td><?php echo $item['userid']; ?></td>
					<td><?php echo $item['target'];?></td>
					<td><?php echo $item['targetid'];?></td>
					<td><?php echo $item['action'];?></td>
					<td><?php echo $item['detail'];?></td>
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
