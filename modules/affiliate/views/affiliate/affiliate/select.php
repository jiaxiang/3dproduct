<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on">网站联盟订单查看</li>
			</ul>
			<span class="fright">
				当前站点：<a title="点击访问" target="_blank" href="http://<?php echo $site_name ?>"><?php echo $site_name ?></a> [ <a href="/manage/site/out">返回</a> ]
			</span>
		</div>
		<div class="newgrid_top">
            <ul class="pro_oper">
                <li class=""><a href="/affiliate/affiliate"><span class="add_pro">网站联盟列表</span></a></li>
                <li class=""><a href="/affiliate/affiliate/select"><span class="add_pro">网站联盟订单查看</span></a></li>
            </ul>
            <form action="/affiliate/affiliate/select" method="GET" class="new_search">
				搜索：
				<select name="affiliate_id">
					<option value="0">联盟名称</option>
					<?php foreach ($affiliates as $affiliate) : ?>
					<option value="<?php echo $affiliate['id'] ?>"<?php echo $affiliate['selected']==1 ? ' selected' : '' ?>><?php echo $affiliate['name'] ?></option>
					<?php endforeach; ?>
				</select>
				
				开始时间：<input type="text" name="time_f" id="time_f" value="<?php echo $time_f ?>" size="10" readonly>
				结束时间：<input type="text" name="time_t" id="time_t" value="<?php echo $time_t ?>" size="10" readonly>
				<input type="submit" value="搜索" class="ui-button ui-widget ui-state-default ui-corner-all">
				<script type="text/javascript">
				$(document).ready(function(){
					$(function() {
						$("#time_f").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true});
					});
					$(function() {
						$("#time_t").datepicker({dateFormat: 'yy-mm-dd',changeMonth: true,changeYear: true});
					});
				});
				</script>
			</form>
        </div>
		<div>
		<table>
			<tr class="headings even">
				<th align="left">ID</th>
				<th align="left">联盟名称</th>
				<th align="left">推广网站</th>
				<th align="left">订单号</th>
				<th align="left">消费金额</th>
				<th align="left">支付币种</th>
				<th align="left">消费时间</th>
				<th align="left">IP</th>
				<th align="left">所在国家</th>
			</tr>
			<?php foreach ($orders as $order) : ?>
			<tr>
				<td><?php echo $order['id'] ?></td>
				<td><?php echo $order['affiliate_name'] ?></td>
				<td><?php echo $order['site_name'] ?></td>
				<td><?php echo $order['order_num'] ?></td>
				<td><?php echo $order['order_amount'] ?></td>
				<td><?php echo $order['currency'] ?></td>
				<td><?php echo $order['order_time'] ?></td>
				<td><?php echo $order['user_ip'] ?></td>
				<td><?php echo $order['user_country'] ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
		<?php echo $pagination ?>
		</div>
	</div>
</div>