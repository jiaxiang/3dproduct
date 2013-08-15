<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base() . 'distribution/agent/';?>'>月结账期</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
        </div>
		<?php if (is_array($data) && count($data)) {?>
		<table  cellspacing="0">
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
			<thead>
				<tr class="headings">
				<th width="10px">ID</th>
				<th width="20px">账期</th>	
				<th width="70px">状态</th>
				<th width="30px">执行时间</th>	
				<th width="30px">开始记账时间</th>	
				<th width="30px">结束记账时间</th>
				<th width="70px">备注</th>
				<th width="20px" class="txc">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) : ?>
				<tr>
					<td><?php echo $item['id'];?></td>
					<td><?php echo $item['spid'];?></td>
					<td><?php echo $item['flag'];?></td>
					<td><?php echo $item['settletime'];?></td>
					<td><?php echo $item['starttime'];?></td>
					<td><?php echo $item['endtime'];?></td>
					<td><?php echo $item['note'];?></td>
					<td class="txc">
						<?php 
                          	$img = $item['flag'] <= 12 ?'/images/icon/accept.png':'/images/icon/cancel.png';
							echo '<img src="'.$img.'" rev="'.$item['id'].'"/>';	
						?>
					</td>
				</tr>
				<?php endforeach;?>
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
