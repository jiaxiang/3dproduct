<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on">安装网站联盟（<?php echo $affiliate['name'] ?>）推广应用</li>
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
        </div>
		<div class="out_box">
			<font color="Red">注意：请确认<?php echo $site_name ?>已经在网站联盟<?php echo $affiliate['name'] ?>中注册，如果没有注册请先注册再进行操作！</font>
			<br>
			<br>
			如果已经注册，请填写下面的表单，在确认正确后提交
			<form method="POST" action="/affiliate/affiliate/post/">
			<input type="hidden" name="affiliate_id" value="<?php echo $affiliate['id'] ?>">
			<input type="hidden" name="affiliate_name" value="<?php echo $affiliate['name'] ?>">
			<table>
				<?php echo $affiliate['form_string'] ?>
				<tr>
				<th align="right" width="200">cookie保存时间（天）：</th>
				<td><input type="text" name="cookie_day" value="30"></td>
				</tr>
				
				<tr>
				<th align="right">结算币种：</th>
				<td>
					<input type="radio" value="default" name="currency_use" checked onclick="$('#currency').hide()">系统默认 &nbsp;&nbsp;
					<input type="radio" value="choose" name="currency_use" onclick="$('#currency').show()">选择币种
					<select name="currency" id="currency" style="display:none">
						<?php if(!empty($currencies)) : ?>
						<?php foreach ($currencies as $currency) : ?>
						<option value="<?php echo $currency['code'] ?>"><?php echo $currency['name'] ?></option>
						<?php endforeach; ?>
						<?php else : ?>
						<option value="USD">美元</option>
						<option value="GBP">英镑</option>
						<option value="EUR">欧元</option>
						<option value="JPY">日元</option>
						<option value="RMB">人民币</option>
						<?php endif; ?>
					</select>
				</td>
				</tr>
				
				<tr>
				<th align="right">&nbsp;</th>
				<td><input type="submit" value="安装" name="install_affiliate" class="ui-button ui-widget ui-state-default ui-corner-all"></td>
				</tr>
			</table>
			</form>
		</div>
	</div>
</div>