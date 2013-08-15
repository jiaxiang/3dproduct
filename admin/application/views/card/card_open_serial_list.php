<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base().'card/card_open_serial/';?>'>充值卡开卡管理</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
			<ul class="pro_oper">
				<li>
					<a href='/card/card_open_serial/add'>
						<span class="add_pro">我要开一批卡</span></a>
				</li>
			</ul>
        </div>
		
		<?php if (is_array($data) && count($data)) {?>
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base().url::current();?>">
		<table cellspacing="0">
			<thead>
				<tr class="headings">
				<th width="70px">操作</th>
				<th width="70px">开卡时间</th>
				<th width="50px">发行ID</th>
				<th width="50px">初始号码</th>
				<th width="50px">结束号码</th>
				<th width="30px">操作员ID</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) { ?>
				<tr>
					<td>
					<a href="<?php echo url::base().'card/card_open_serial/detail/'.$item['id']; ?>" target="_blank">查看</a>&nbsp;
					<a href="<?php echo url::base().'card/card_open_serial/delete/'.$item['id']; ?>" 
						onclick="javascript:return confirm('确定删除？')">撤销</a>&nbsp;
					</td>
					<td><?php echo $item['opentime'];?></td>
					<td><?php echo $item['issueid'];?></td>
					<td><?php echo $item['bgnnum'];?></td>
					<td><?php echo $item['endnum'];?></td>
					<td><?php echo $item['checkuser'].'('.$item['checkuserid'].')';?></td>
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
