<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">发行单据信息</li>
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
								<th width=20%>操作员ID</th>
								<td><?php echo $issueBill['user_id']; ?></td>
							</tr>
							<tr>
								<th width=20%>单据编号</th>
								<td><?php echo $issueBill['num']; ?></td>
							</tr>
							<tr>
								<th width=20%>发行批次ID</th>
								<td><?php echo $issueBill['issueid'];?></td>
							</tr>
							<tr>
								<th>销售渠道</th>
								<td><?php echo $channelList[$issueBill['channelid']]['name']; ?></td>
							</tr>
							<tr>
								<th>销售渠道编号</th>
								<td><?php echo $issueBill['channelcode'].'('.$issueBill['id'].')'; ?></td>
							</tr>
							<tr>
								<th>卡系列的起始号码（管理号）</th>
								<td><?php echo $issueBill['bgnnum']; ?></td>
							</tr>
							<tr>
								<th>卡系列的结束号码（管理号）</th>
								<td><?php echo $issueBill['endnum']; ?></td>
							</tr>
							<tr>
								<th>发生金额</th>
								<td><?php echo $issueBill['moneys'];?></td>
							</tr>
							<tr>
								<th>描述</th>
								<td><?php echo $issueBill['des'];?></td>
							</tr>
							<tr>
								<th>创建时间</th>
								<td><?php echo $issueBill['apdtime'];?></td>
							</tr>
							<tr>
								<th>更新时间</th>
								<td><?php echo $issueBill['updtime'];?></td>
							</tr>
							<tr>
								<th>状态</th>
								<td>
								<?php if($issueBill['flag'] == 0){echo '已撤销';}?>
								<?php if($issueBill['flag'] == 2){echo '正常';}?>
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
