<?php defined('SYSPATH') or die('No direct access allowed.');?>
<div class="new_content">
    <div class="out_box pro_ie6">
		<div id="ship_detail_content" title="发货单详情">
    		<div class="out_box">    
        		<table id="ship_table" width="95%">
        			<tr>
        				<td>发货单号：&nbsp;&nbsp;<?php echo $ship_list['ship_num']?></td>
        				<td>订单号：&nbsp;&nbsp;<?php echo $ship_list['order_num']?></td>
        			</tr>
        			<tr>
        				<td>添加时间：&nbsp;&nbsp;<?php echo $ship_list['date_add']?></td>
        				<td>用户邮箱：&nbsp;&nbsp;<?php echo $ship_list['email']?></td>
        			</tr>
        			<tr>
        				<td>操作员：&nbsp;&nbsp;<?php echo $ship_list['manager']?></td>
        				<td>配送方式：&nbsp;&nbsp;<?php echo $ship_list['carrier']?></td>
        			</tr>
        			<tr>
        				<td>物流单号：&nbsp;&nbsp;<?php echo $ship_list['ems_num']?></td>
        				<td>运费金额：&nbsp;&nbsp;<?php echo $ship_list['total_shipping']?></td>
        			</tr>
        			<tr>
        				<td>币种：&nbsp;&nbsp;<?php echo $ship_list['currency']?></td>
        				<td>发货状态：&nbsp;&nbsp;<?php echo $ship_list['ship_status']?></td>
        			</tr>
        			<tr>
        				<td colspan="2">是否发送邮件：&nbsp;&nbsp;<?php echo $ship_list['is_send_email']?></td>
        			</tr>
        			<tr>
        				<td colspan="2">管理员备注：&nbsp;&nbsp;<?php echo $ship_list['content_admin']?></td>
        			</tr>
        			<tr>
        				<td colspan="2">用户备注：&nbsp;&nbsp;<?php echo $ship_list['content_user']?></td>
        			</tr>
        		</table>
    		</div>
    		<div class="out_box">
    			<ul>
    				<li>发货明细：</li>
    			</ul>
    			<table id="ship_product" width="95%">
    				<thead>
    					<tr>
    						<th class="a_left">SKU</th>
    						<th class="a_left">货品名称</th>
    						<th class="a_left">规格</th>
    						<th class="a_left">购买数量</th>
    						<th class="a_left">发货数量</th>
    					</tr>
    				</thead>
    				<tbody>
    					<?php foreach($ship_list['send_data'] as $data):?>
    						<tr>
    							<td><?php echo $data['SKU']?></td>
    							<td><?php echo $data['name']?></td>
    							<td><?php echo $data['attribute_style']?></td>
    							<td><?php echo $data['quantity']?></td>
    							<td><?php echo $data['shipnum']?></td>
    						</tr>
    					<?php endforeach;?>
    				</tbody>
    			</table>
    		</div>
		</div>
	</div>
</div>