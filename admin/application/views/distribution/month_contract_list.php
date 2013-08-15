<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base().'distribution/month_contract/index/'.$user['id'];?>'><?php echo $user['lastname'];?>&nbsp;月结合约列表</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
			<ul class="pro_oper">
				<li>
					<a href="/distribution/month_contract/add/<?php echo $user['id']; ?>"><span class="add_pro">添加月结合约</span></a>
				</li>
				<li>
					<a href="/distribution/month_contract/template_reference/<?php echo $user['id']; ?>"><span class="add_pro">参考月结合约模板</span></a>
				</li>
			</ul>
        </div>
		
		<?php if (is_array($data) && count($data)) {?>
		<table  cellspacing="0">
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
			<thead>
				<tr class="headings">
				<th width="50px">操作</th>
				<th width="30px">合约拥有者</th>
				<th width="30px">合约类型(合约号)</th>
				<th width="70px">合约创建日期</th>				
				<th width="70px">合约生效日期</th>				
				<th width="70px">最后一次结算</th>
				<!-- 
				<th width="40px">返点税率</th>
				 -->
				<th width="30px">备注</th>	
				<th width="20px" class="txc">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) { ?>
				<tr>
					<td>
					<!-- 
						<a href="<?php //echo url::base().'distribution/month_contract/edit/'.$item['id']; ?>">编辑</a>&nbsp;
					 -->
						<a href="<?php echo url::base().'distribution/month_contract/detail/'.$item['id']; ?>" target="_blank">查看</a>&nbsp;
						<?php if($item['flag']==0) {?>
						<a href="<?php echo url::base().'distribution/month_contract/open/'.$item['id']; ?>">生效</a>&nbsp;
						<?php } else {?>
						<a href="<?php echo url::base().'distribution/month_contract/close/'.$item['id']; ?>">关闭</a>&nbsp;
						<?php } ?>
						<br/><a href="<?php echo url::base().'distribution/month_contract/delete/'.$item['id']; ?>" 
							 onclick="javascript:return confirm('确定删除？')">删除</a>&nbsp;
					</td>
					<td><?php echo $user['lastname'].'('.$item['user_id'].')' ;?></td>
					<td><?php echo ($item['type'] == 7) ? '北单' : '普通'; echo '('.$item['id'].')';?></td>
					<td><?php echo $item['createtime'];?></td>
					<td><?php echo $item['starttime'];?></td>
					<td><?php echo $item['lastsettletime'];?></td>
					<!-- 
					<td><?php //echo $item['taxrate'];?></td>
					 -->
					<td><?php echo $item['note'];?></td>
					<td class="txc"><?php echo ($item['flag'] == 2)? '生效':'关闭';?></td>
				</tr>
				<?php } ?>
			</tbody>
		</form>
		</table>
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
