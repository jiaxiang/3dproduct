<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">冲值卡信息</li>
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
								<th width=20%>管理号</th>
								<td><?php echo $card['mgrnum']; echo '('.$card['id'].')'; ?></td>
							</tr>
							<tr>
								<th width=20%>初始密码</th>
								<td><?php echo $card['cardpass'];?></td>
							</tr>
							<tr>
								<th width=20%>创建时间</th>
								<td><?php echo $card['apdtime'];?></td>
							</tr>
							<tr>
								<th width=20%>更新时间</th>
								<td><?php echo $card['updtime'];?></td>
							</tr>
							<tr>
								<th width=20%>卡系列ID</th>
								<td><?php echo $card['cardserialid'];?></td>
							</tr>
							<tr>
								<th>卡系列编号</th>
								<td><?php echo $card['cardserialcode']; ?></td>
							</tr>
							<tr>
								<th>初始点数</th>
								<td><?php echo $card['points'];?></td>
							</tr>
							<tr>
								<th>对应的RMB面额</th>
								<td><?php echo $card['moneyrmb'];?></td>
							</tr>
							<tr>
								<th>对应的JPY面额</th>
								<td><?php echo $card['moneyjpy'];?></td>
							</tr>
							<tr>
								<th>销售成本(RMB)</th>
								<td><?php echo $card['salecost'];?></td>
							</tr>
							<tr>
								<th>发行批次ID</th>
								<td><?php echo $card['issueid'];?></td>
							</tr>
							<tr>
								<th>发行批次ID</th>
								<td><?php echo $card['issuetime'];?></td>
							</tr>
							<tr>
								<th>开卡批次ID</th>
								<td><?php echo $card['openid'];?></td>
							</tr>
							<tr>
								<th width=20%>开卡时间</th>
								<td><?php echo $card['opentime'];?></td>
							</tr>
							<tr>
								<th>状态</th>
								<td><?php if($card['flag'] == 0) echo '关闭'; ?>
									<?php if($card['flag'] == 2) echo '待发行'; ?>
									<?php if($card['flag'] == 4) echo '已发行'; ?>
									<?php if($card['flag'] == 6) echo '已开卡'; ?>
									<?php if($card['flag'] == 8) echo '已使用'; ?>
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
