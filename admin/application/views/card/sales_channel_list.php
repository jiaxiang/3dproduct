<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base().'card/sales_channel/';?>'>销售渠道管理</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
			<ul class="pro_oper">
				<li>
					<a href='/card/sales_channel/add'>
						<span class="add_pro">添加销售渠道</span></a>
				</li>
			</ul>
        </div>
		
		<?php if (is_array($data) && count($data)) {?>
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base().url::current();?>">
		<table  cellspacing="0">
			<thead>
				<tr class="headings">
				<th width="70px">操作</th>
				<th width="70px">编号(ID)</th>
				<th width="50px">名称</th>
				<th width="90px">创建日期</th>
				<th width="90px">更新日期</th>
				<th width="20px" class="txc">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) { ?>
				<tr>
					<td>
						<a href="<?php echo url::base().'card/sales_channel/edit/'.$item['id']; ?>">编辑</a>&nbsp;
						<a href="<?php echo url::base().'card/sales_channel/delete/'.$item['id']; ?>"
							onclick="javascript:return confirm('确定删除？')">删除</a>&nbsp;
					</td>
					<td>
						<a href="<?php echo url::base().'card/sales_channel/detail/'.$item['id']; ?>" target="_blank">
							<?php echo $item['code']; echo '('.$item['id'].')'; ?>
						</a>
					</td>
					<td><?php echo $item['name'];?></td>
					<td><?php echo $item['apdtime'];?></td>
					<td><?php echo $item['updtime'];?></td>
					<td class="txc"><?php echo ($item['flag'] == 2)? '正常':'关闭';?></td>
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
