<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">月结合约详细</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
	<div class="grid_c2">
		<div class="col_main">
			<!--** edit start**-->
			<div class="edit_area">
				<div class="out_box">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tbody>
							<tr>
								<th width=20%>上级代理</th>
								<td>竞波网</td>
							</tr>
                                <tr>
                                    <th width=20%>下级客户</th>
                                    <td><?php echo $user['lastname'].'('.$user['id'].')'; ?>
                                    	<input type="hidden" name="user_id" value="<?php echo $user['id'];?>" >
                                    </td>
                                </tr>
                                <tr>
                                    <th>合约类型</th>
                                    <td>
                                    	<input type="hidden" name="contractType" class="text" value="0">普通代理返利
                                    </td>
                                </tr>
                                <tr>
                                    <th>彩种分类</th>
                                    <td><?php echo ($contract['type'] == 7)? '北单':'普通'; ?></td>
                                </tr>
                                <tr>
                                    <th>合约创建时间</th>
                                    <td><?php echo $contract['createtime'];?></td>
                                </tr>
                                <tr>
                                    <th>合约生效时间</th>
                                    <td><?php echo $contract['starttime']; ?></td>
                                </tr>
                                <tr>
                                    <th>最后一次结算</th>
                                    <td><?php echo $contract['lastsettletime'];?></td>
                                </tr>
                                <!-- 
                                <tr>
                                    <th>返利税率</th>
                                    <td><?php echo $contract['taxrate'];?></td>
                                </tr>
                                 -->
                                <tr>
                                    <th>状态</th>
                                    <td><?php if ($contract['flag'] == 2) {echo '生效'; }
                                    	else {echo '关闭'; } ?>
                                    </td>
                                </tr>
                                <tr>
                                <th>备注：</th>
                                <td><?php echo $contract['note']; ?></td>
                                </tr>
						</tbody>
					</table>
				</div>
				<div class="out_box">
					<table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <thead>
                            <tr class="headings">
                            	<th width="100px;">合约细则</th>
                            	<th width="600px;" style="text-align:left; padding-left:20px;">返点范围:下限(达到)&nbsp;~&nbsp;上限(不超过)</th>
                            	<th width="600px;" style="text-align:left; padding-left:20px;">返点率</th>
                            </tr>
                        </thead>
						<tbody>
                            <?php foreach ($data as $item) {?>
                                <tr>
                                    <th>销售层级&nbsp;<?php echo $item['grade']; ?>:</th>
									<td>￥<?php echo $item['minimum']; ?>
                                    	&nbsp;~&nbsp;
										￥<?php echo $item['maximum']; ?>&nbsp;
									</td>
                                    <td><?php echo $item['rate']; ?>&nbsp;</td>
                                </tr>
                                <?php }?>
						</tbody>
					</table>
				</div>
				<div class="list_save">
					<input type="button" class="ui-button" value=" 关闭 " onclick="javascript:window.close();">
				</div>
            </div>
            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->

<div id='example' style="display:none;"></div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
    	$('#starttime').datepicker({dateFormat:"yy-mm-dd"});
    });

</script>