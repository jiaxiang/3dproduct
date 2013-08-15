<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base() . 'distribution/agent/';?>'>代理列表</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
			<ul class="pro_oper">
				<li>
					<a href="/distribution/agent_select"><span class="add_pro">添加代理</span></a>
				</li>
			</ul>
        </div>
		
		<?php if (is_array($data) && count($data)) {?>
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
		<table  cellspacing="0">
			<thead>
				<tr class="headings">
				<th width="200px">操作</th>
				<th width="70px">用户名(ID)</th>
				<th width="50px">真实姓名</th>
				<th width="50px">手机</th>
				<th width="30px">固话</th>
				<th width="50px">QQ</th>
				<th width="90px">创建日期</th>
				<th width="70px">代理类型</th>
				<th width="40px">上级ID</th>
				<th width="40px">邀请码</th>
				<th width="40px">下级用户</th>
				<th width="30px">实时合约</th>
				<th width="30px">月结合约</th>
				<th width="70px">备注</th>				
				<th width="20px" class="txc">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) { ?>
				<tr>
					<td><a href="<?php echo url::base().'distribution/agent/edit/'.$item['id']; ?>">编辑</a>&nbsp;
						<?php if ($item['flag'] == 2) {?>
						<a href="<?php echo url::base().'distribution/agent/close/'.$item['id']; ?>">关闭代理</a>&nbsp;
						<?php } else {?>
						<a href="<?php echo url::base().'distribution/agent/open/'.$item['id']; ?>">代理生效</a>&nbsp;
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
					 -->
					<td><?php if ($item['agent_type'] == 0) {echo '普通代理';}
						 else if ($item['agent_type'] == 1) {echo '特殊超级代理';}
						 else if ($item['agent_type'] == 2) {echo '特殊二级代理';}
						 else if ($item['agent_type'] == 11) {echo '一级代理';}
						 else if ($item['agent_type'] == 12) {echo '二级代理';}
						?>
					</td>
					<td><?php echo $item['up_agent_id'];?></td>
					<td><?php echo $item['invite_code'];?></td>
					<td>
						<a href="<?php echo url::base().'distribution/agent_client/index/'.$item['user_id'];?>">查看</a>
					</td>
					<td>
						<a href="<?php echo url::base().'distribution/realtime_contract/index/'.$item['user_id'];?>">查看</a>
					</td>
					<td>
						<a href="<?php echo url::base().'distribution/month_contract/index/'.$item['user_id'];?>">查看</a>
					</td>
					<td><?php echo $item['note'];?></td>
					<td class="txc"><?php echo ($item['flag'] == 2)? '生效':'关闭';?></td>
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
