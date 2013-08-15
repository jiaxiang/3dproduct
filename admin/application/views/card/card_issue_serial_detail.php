<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">卡发行信息</li>
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
								<th width=20%>操作员[ID]</th>
								<td><?php echo $cardIssue['checkuser'].'['.$cardIssue['checkuserid'].']'; ?></td>
							</tr>
							<tr>
								<th width=20%>发行时间</th>
								<td><?php echo $cardIssue['issuetime'];?></td>
							</tr>
							<tr>
								<th>销售渠道</th>
								<td><?php echo $cardIssue['channelname']; ?></td>
							</tr>
							<tr>
								<th>卡系列的起始号码（管理号）</th>
								<td><?php echo $cardIssue['bgnnum']; ?></td>
							</tr>
							<tr>
								<th>卡系列的结束号码（管理号）</th>
								<td><?php echo $cardIssue['endnum']; ?></td>
							</tr>
							<tr>
								<th>每张卡的邮寄成本</th>
								<td><?php echo $cardIssue['mailcost'];?></td>
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
