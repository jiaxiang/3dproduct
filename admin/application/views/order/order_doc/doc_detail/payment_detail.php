<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="new_content">
    <div class="out_box pro_ie6">
		<div id="payment_detail_content" title="收款单详情">
    		<div class="out_box">    
        		<table id="payment_table" width="95%">
        			<tr>
        				<td>收款单号：&nbsp;&nbsp;<?php echo $payment_list['payment_num']?></td>
        				<td>订单号：&nbsp;&nbsp;<?php echo $payment_list['order_num']?></td>
        			</tr>
        			<tr>
        				<td>添加时间：&nbsp;&nbsp;<?php echo $payment_list['date_add']?></td>
        				<td>用户邮箱：&nbsp;&nbsp;<?php echo $payment_list['email']?></td>
        			</tr>
        			<tr>
        				<td>操作员：&nbsp;&nbsp;<?php echo $payment_list['manager']?></td>
        				<td>支付方式：&nbsp;&nbsp;<?php echo $payment_list['payment_method']?></td>
        			</tr>
        			<tr>
        				<td>币种：&nbsp;&nbsp;<?php echo $payment_list['currency']?></td>
        				<td>支付金额：&nbsp;&nbsp;<?php echo $payment_list['amount']?></td>
        			</tr>
        			<tr>
        				<td>支付状态：&nbsp;&nbsp;<?php echo $payment_list['payment_status']?></td>
        				<td>收款账号：&nbsp;&nbsp;<?php echo $payment_list['receive_account']?></td>
        			</tr>
        			<tr>
        				<td>交易号：&nbsp;&nbsp;<?php echo $payment_list['trans_no']?></td>
        				<td>是否发送邮件：&nbsp;&nbsp;<?php echo $payment_list['is_send_email']?></td>
        			</tr>
        			<tr>
        				<td colspan="2">管理员备注：&nbsp;&nbsp;<?php echo $payment_list['content_admin']?></td>
        			</tr>
        			<tr>
        				<td colspan="2">用户备注：&nbsp;&nbsp;<?php echo $payment_list['content_user']?></td>
        			</tr>
        		</table>
    		</div>
		</div>
	</div>
</div>