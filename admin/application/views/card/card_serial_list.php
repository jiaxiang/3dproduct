<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base().'card/card_serial/';?>'>卡系列管理</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
			<ul class="pro_oper">
				<li>
					<a href='/card/card_serial/add'>
						<span class="add_pro">添加卡系列</span></a>
				</li>
			</ul>
        </div>
		
		<?php if (is_array($data) && count($data)) {?>
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base().url::current();?>">
		<table  cellspacing="0">
			<thead>
				<tr class="headings">
				<th width="50px">操作</th>
				<th width="50px">编号(ID)</th>
				<th width="50px">名称</th>
				<th width="50px">初始号码</th>
				<th width="50px">结束号码</th>
				<th width="50px">卡类型</th>
				<th width="50px">子卡管理</th>
				<th width="40px">创建日期</th>
				<th width="40px">更新日期</th>
				<th width="50px" class="txc">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) { ?>
				<tr>
					<td>
					<?php if ($item['flag'] != 6 && $item['flag'] != 4) {?>
					<a href="<?php echo url::base().'card/card_serial/edit/'.$item['id']; ?>">编辑</a>&nbsp;
					<a href="<?php echo url::base().'card/card_serial/delete/'.$item['id']; ?>"
						onclick="javascript:return confirm('确定删除？')">删除</a>&nbsp;
					<?php } ?>
					</td>
					<td>
						<a href="<?php echo url::base().'card/card_serial/detail/'.$item['id']; ?>" target="_blank">
							<?php echo $item['code']; echo '('.$item['id'].')'; ?>
						</a>
					</td>
					<td><?php echo $item['name'];?></td>
					<td><?php echo $item['bgnnum'];?></td>
					<td><?php echo $item['endnum'];?></td>
					<td><?php echo $cardTypeMap[$item['cardtype']];?></td>
					<td>
						<?php if ($item['flag'] == 2) {?>
						<a href="<?php echo url::base().'card/card_serial/createCards/'.$item['id']; ?>">生成子卡</a>&nbsp;
						<?php } else if ($item['flag'] == 4) { ?>
						<a href="<?php echo url::base().'card/card_serial/removeCards/'.$item['id']; ?>"
							onclick="javascript:return confirm('确定删除子卡？')">删除子卡</a>&nbsp;<br/>
						<a href="<?php echo url::base().'card/card_serial/lock/'.$item['id']; ?>" 
							onclick="javascript:return confirm('确定锁定该卡系列？一旦锁定将不能撤销，或删除子卡。')">锁定</a>&nbsp;
						<?php } ?>
					</td>
					<td><?php echo $item['apdtime'];?></td>
					<td><?php echo $item['updtime'];?></td>
					<td class="txc">
					<?php if ($item['flag'] == 0) { echo '关闭';}
					 else if ($item['flag'] == 2) { echo '未生成子卡'; }
					 else if ($item['flag'] == 4) { echo '已生成子卡'; }
					 else if ($item['flag'] == 6) { echo '锁定(待发行)'; }
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
