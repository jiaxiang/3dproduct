<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">卡系列信息</li>
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
                <form id="add_form" name="add_form" method="post" onsubmit="return CheckForm()" 
                	action="<?php echo url::base().url::current();?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
							<tr>
								<th width=20%>卡系列编号</th>
								<td><?php echo $cardSerial['code']; echo '('.$cardSerial['id'].')'; ?></td>
							</tr>
							<tr>
								<th width=20%>卡系列名称</th>
								<td><?php echo $cardSerial['name'];?></td>
							</tr>
							<tr>
								<th width=20%>创建时间</th>
								<td><?php echo $cardSerial['apdtime'];?></td>
							</tr>
							<tr>
								<th width=20%>更新时间</th>
								<td><?php echo $cardSerial['updtime'];?></td>
							</tr>
							<tr>
								<th>卡系列的起始号码（管理号）</th>
								<td><?php echo $cardSerial['bgnnum']; ?></td>
							</tr>
							<tr>
								<th>卡系列的结束号码（管理号）</th>
								<td><?php echo $cardSerial['endnum']; ?></td>
							</tr>
							<tr>
								<th>充值卡类型</th>
								<td><?php echo $cardTypeMap[$cardSerial['cardtype']];?></td>
							</tr>
							<tr>
								<th>每张卡的初始点数</th>
								<td><?php echo $cardSerial['points'];?></td>
							</tr>
							<tr>
								<th>每张卡对应的RMB面额</th>
								<td><?php echo $cardSerial['permoneyrmb'];?></td>
							</tr>
							<tr>
								<th>每张卡对应的JPY面额</th>
								<td><?php echo $cardSerial['permoneyjpy'];?></td>
							</tr>
							<tr>
								<th>每张卡的成本(RMB)</th>
								<td><?php echo $cardSerial['percost'];?></td>
							</tr>
							<?php if ($cardSerial['flag'] == 0) {?>
							<tr>
								<th>关闭前状态</th>
								<td>
								<?php if ($cardSerial['preflag'] == 0) { echo '关闭';}
								 else if ($cardSerial['preflag'] == 2) { echo '未生成卡'; }
								 else if ($cardSerial['preflag'] == 4) { echo '已生成卡'; }
								 else if ($cardSerial['preflag'] == 6) { echo '锁定'; }
								?>
								</td>
							</tr>
							<?php } ?>
							<tr>
								<th>状态</th>
								<td>
								<?php if ($cardSerial['flag'] == 0) { echo '关闭';}
								 else if ($cardSerial['flag'] == 2) { echo '未生成卡'; }
								 else if ($cardSerial['flag'] == 4) { echo '已生成卡'; }
								 else if ($cardSerial['flag'] == 6) { echo '锁定'; }
								?>
								</td>
							</tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="list_save">
                        <input type="button" class="ui-button" value=" 关闭 " 
                        	onclick="javascript:window.close();" />
                    </div>
                    
                </form>
            </div>
            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->

<div id='example' style="display:none;"></div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
	$(document).ready(function(){});
</script>
