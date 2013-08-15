<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base().'card/card_type/';?>'>卡类型管理</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
			<ul class="pro_oper">
				<li>
					<a href='/card/card_type/add'>
						<span class="add_pro">创建卡类型</span></a>
				</li>
			</ul>
        </div>
		
		<?php if (is_array($data) && count($data)) {?>
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base().url::current();?>">
		<table  cellspacing="0">
			<thead>
				<tr class="headings">
				<th width="100px">操作</th>
				<th width="70px">卡类型名称</th>
				<th width="90px">创建日期</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) { ?>
				<tr>
					<td><a href="<?php echo url::base().'card/card_type/edit/'.$item['id']; ?>">编辑</a>&nbsp;
						<a href="<?php echo url::base().'card/card_type/delete/'.$item['id']; ?>"
							onclick="javascript:return confirm('确定删除该卡类型？')">删除</a>&nbsp;
					</td>
					<td><?php echo $item['name'];?></td>
					<td><?php echo $item['apdtime'];?></td>
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
