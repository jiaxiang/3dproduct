<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base().'card/money_exchange/';?>'>中日汇率管理</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
			<ul class="pro_oper">
				<li>
					<a href='/card/money_exchange/add'>
						<span class="add_pro">添加汇率规则</span></a>
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
				<th width="70px">方案编号</th>
				<th width="70px">方案名称</th>
				<th width="50px">汇率(RMB : JPY)</th>
				<th width="30px">更新时间</th>
				<th width="30px">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) { ?>
				<tr>
					<td>
					<a href="<?php echo url::base().'card/money_exchange/detail/'.$item['id']; ?>" target="_blank">查看</a>&nbsp;
					<a href="<?php echo url::base().'card/money_exchange/delete/'.$item['id']; ?>">删除</a>&nbsp;
					</td>
					<td><?php echo $item['code']; ?></td>
					<td><?php echo $item['name']; ?></td>
					<td><?php echo $item['numrmb'].' : '.$item['numjpy']; ?></td>
					<td><?php echo $item['updtime']; ?></td>
					<td><?php if ($item['flag'] == 0) echo '关闭'; ?>
						<?php if ($item['flag'] == 2) echo '生效'; ?>
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
