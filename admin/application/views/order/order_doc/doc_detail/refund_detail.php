<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="new_content">
    <div class="out_box pro_ie6">
		<div id="refund_detail_content" title="退款单详情">
    		<div class="out_box">    
        		<table id="refund_table" width="95%">
        			<tr>
        				<td>退款单号：&nbsp;&nbsp;<?php echo $refund_list['refund_num']?></td>
        				<td>订单号：&nbsp;&nbsp;<?php echo $refund_list['order_num']?></td>
        			</tr>
        			<tr>
                        <td>添加时间：&nbsp;&nbsp;<?php echo $refund_list['date_add']?></td>
        				<td>用户邮箱：&nbsp;&nbsp;<?php echo $refund_list['email']?></td>
        			</tr>
        			<tr>
        				<td>操作员：&nbsp;&nbsp;<?php echo $refund_list['manager']?></td>
        				<td>退款原因：&nbsp;&nbsp;<?php echo $refund_list['refund_reason']?></td>
        			</tr>
        			<tr>
        				<td>退款方式：&nbsp;&nbsp;<?php echo $refund_list['refund_method']?></td>
        				<td>币种：&nbsp;&nbsp;<?php echo $refund_list['currency']?></td>
        			</tr>
        			<tr>
        				<td>退款金额：&nbsp;&nbsp;<?php echo $refund_list['refund_amount']?></td>
        				<td>退款状态：&nbsp;&nbsp;<?php echo $refund_list['refund_status']?></td>
        			</tr>
        			<tr>
        				<td colspan="2">是否发送邮件：&nbsp;&nbsp;<?php echo $refund_list['is_send_email']?></td>
        			</tr>
        			<tr>
        				<td colspan="2">管理员备注：&nbsp;&nbsp;<?php echo $refund_list['content_admin']?></td>
        			</tr>
        			<tr>
        				<td colspan="2">用户备注：&nbsp;&nbsp;<?php echo $refund_list['content_user']?></td>
        			</tr>
        		</table>
    		</div>
		</div>
	</div>
</div>