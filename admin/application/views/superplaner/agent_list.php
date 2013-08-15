<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base() . 'superplaner/agent/';?>'>超级发单人列表</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
			<ul class="pro_oper">
				<li>
					<a href="/superplaner/agent_select"><span class="add_pro">添加超级发单人</span></a>
				</li>
			</ul>
        </div>
		
		<?php if (is_array($data) && count($data)) {?>
		<table  cellspacing="0">
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
			<thead>
				<tr class="headings">
				<th width="200px">操作</th>
				<th width="70px">用户名(ID)</th>
				<th width="50px">真实姓名</th>
				<th width="50px">手机</th>
				<th width="30px">固话</th>
				<th width="50px">QQ</th>
				<th width="90px">创建日期</th>
				<!-- 
				<th width="90px">生效日期</th>
				<th width="70px">代理类型</th>
				<th width="40px">上级ID</th>
				<th width="40px">邀请码</th>
				<th width="40px">下级用户</th>
				-->
				<th width="30px">实时合约</th>
				<!-- 
				<th width="30px">月结合约</th>
				-->
				<th width="70px">备注</th>				
				<th width="20px" class="txc">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) { ?>
				<tr>
					<td><a href="agent/edit/<?php echo $item['id']; ?>">编辑</a>&nbsp;
						<?php if ($item['flag'] == 2) {?>
						<a href="agent/close/<?php echo $item['id']; ?>">关闭</a>&nbsp;
						<?php } else {?>
						<a href="agent/open/<?php echo $item['id']; ?>">生效</a>&nbsp;
						<?php } ?>
					</td>
					<td><?php echo $item['lastname']; echo '('.$item['user_id'].')'; ?></td>
					<td><?php echo $item['realname'];?></td>
					<td><?php echo $item['mobile'];?></td>
					<td><?php echo $item['tel'];?></td>
					<td><?php echo $item['qq'];?></td>
					<td><?php echo $item['createtime'];?></td>
					<!-- 
					<td><?php //echo $item['starttime'];?></td>
					
					<td><?php if ($item['agent_type'] == 0) {echo '普通代理';}
						 else if ($item['agent_type'] == 1) {echo '超级代理';}
						 else if ($item['agent_type'] == 2) {echo '二级代理';}?>
					</td>
					<td><?php echo $item['up_agent_id'];?></td>
					<td><?php echo $item['invite_code'];?></td>
					<td>
						<a href="agent_client/index/<?php echo $item['user_id'];?>">查看</a>
						<a href="client_select/index/<?php echo $item['user_id'];?>">添加</a>
					</td>
					 -->
					<td><a href="realtime_contract/index/<?php echo $item['user_id'];?>">查看</a></td>
					<!-- 
					<td><a href="month_contract/index/<?php echo $item['user_id'];?>">查看</a></td>
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
