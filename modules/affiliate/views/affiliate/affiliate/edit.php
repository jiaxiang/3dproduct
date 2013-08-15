<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on">编辑网站联盟（<?php echo $affiliate['affiliate_name'] ?>）推广应用</li>
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
			<form method="POST" action="/affiliate/affiliate/post/">
			<input type="hidden" name="affiliate_id" value="<?php echo $affiliate['affiliate_id'] ?>">
			<input type="hidden" name="affiliate_name" value="<?php echo $affiliate['affiliate_name'] ?>">
			<table>
				<?php echo $affiliate['form_string'] ?>
				<tr>
				<th align="right" width="200">cookie保存时间（天）：</td>
				<td><input type="text" name="cookie_day" value="<?php echo $affiliate['cookie_day'] ?>"></td>
				</tr>
				
				<tr>
				<th align="right">结算币种：</td>
				<td>
					<input type="radio" value="default" name="currency_use" <?php echo $affiliate['currency']=='default' ? 'checked' : ''; ?> onclick="$('#currency').hide()">系统默认 &nbsp;&nbsp;
					<input type="radio" value="choose" name="currency_use" <?php echo $affiliate['currency']=='default' ? '' : 'checked'; ?> onclick="$('#currency').show()">选择币种
					<select id="currency" name="currency" <?php echo $affiliate['currency']=='default' ? 'style="display:none;"' : ''; ?>>
						<?php if(!empty($currencies)) : ?>
						<?php foreach ($currencies as $currency) : ?>
						<option value="<?php echo $currency['code'] ?>" <?php echo $affiliate['currency']==$currency['code'] ? 'selected' : ''; ?>><?php echo $currency['name'] ?></option>
						<?php endforeach; ?>
						<?php else : ?>
						<option value="USD" <?php echo $affiliate['currency']=='USD' ? 'selected' : ''; ?>>美元</option>
						<option value="GBP" <?php echo $affiliate['currency']=='GBP' ? 'selected' : ''; ?>>英镑</option>
						<option value="EUR" <?php echo $affiliate['currency']=='EUR' ? 'selected' : ''; ?>>欧元</option>
						<option value="JPY" <?php echo $affiliate['currency']=='JPY' ? 'selected' : ''; ?>>日元</option>
						<option value="RMB" <?php echo $affiliate['currency']=='RMB' ? 'selected' : ''; ?>>人民币</option>
						<?php endif; ?>
					</select>
				</td>
				</tr>
				
				<tr>
				<th align="right">&nbsp;</td>
				<td><input type="submit" value="编辑" name="install_affiliate" class="ui-button ui-widget ui-state-default ui-corner-all"></td>
				</tr>
			</table>
			</form>
		</div>
	</div>
</div>