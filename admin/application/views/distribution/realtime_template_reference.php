<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base().'distribution/realtime_contract/template_reference/'.$userId;?>'>实时合约模板列表</a></li>
			</ul>
		</div>																																												
		<!-- 
		<div class="newgrid_top">
			<ul class="pro_oper">
				<li>
					<a href="/distribution/realtime_contract_template/add/"><span class="add_pro">添加实时合约模板</span></a>
				</li>
			</ul>
        </div>
		 -->
		
		<table  cellspacing="0">
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
			<thead>
				<tr class="headings">
				<th width="50px">操作</th>
				<th width="50px">模板名称</th>
				<th width="50px">合约类型</th>
				<th width="70px">返点率</th>
				<th width="70px">返点税率</th>
				<th width="70px">合约创建日期</th>				
				</tr>
			</thead>
			<?php if (is_array($data) && count($data)) {?>
			<tbody>
				<?php foreach ($data as $item) { ?>
				<tr>
					<td>
						<a href="<?php echo url::base().'distribution/realtime_contract/template_use/'.$userId.'/'.$item['id']; ?>" >使用该模板</a>&nbsp;
					</td>
					<td><?php echo $item['name'];?></td>
					<td><?php echo ($item['type'] == 7) ? '北单' : '普通'; ?></td>
					<td><?php echo $item['rate'];?></td>
					<td><?php echo $item['taxrate'];?></td>
					<td><?php echo $item['createtime'];?></td>
				</tr>
				<?php }?>
			</tbody>
	        <?php } else {?>
	        <?php 	echo remind::no_rows();?>
	        <?php }?>
		</form>
		</table>
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
