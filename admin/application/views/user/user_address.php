<?php defined('SYSPATH') OR die('No direct access allowed.');?>

<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <div class="public_crumb">
                <p><a href="<?php echo url::base();?>">后台首页</a> 》<a href="<?php echo url::base();?>user/user/">会员列表</a> 》会员地址</p>
            </div>
            <!--** edit start**-->
            <div class="edit_area">
				<div class="division" id="tabs">
					<ul>
						<li><a href="#tabs-1">地址1</a></li>
						<li><a href="#tabs-2">地址2</a></li>
						<li><a href="#tabs-3">地址3</a></li>
					</ul>
					<div id="tabs-1">
						<h3 class="title1_h3">会员地址信息</h3>
						<table width="90%" cellspacing="0" cellpadding="0" border="0">
							<thead>
								<tr>
									<th width="22%" style="font-weight: bold;">地址</th>
									<td style="border-top: 1px solid rgb(232, 235, 241); font-weight: bold;"> </td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>First Name:</th>
									<td><?php echo $address['firstname']?></td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>Last Name:</th>
									<td><?php echo $address['lastname']?></td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>Address:</th>
									<td><?php echo $address['address']?></td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>City:</th>
									<td><?php echo $address['city']?></td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>State/Province:</th>
									<td><?php echo $address['state']?></td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>Country:</th>
									<td><?php echo $address['country']?></td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>Zip/Postal code:</th>
									<td><?php echo $address['zip']?></td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>Phone Number:</th>
									<td><?php echo $address['phone']?></td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>Moble Number:</th>
									<td><?php echo $address['phone_moble']?></td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>备注:</th>
									<td><?php echo $address['other']?></td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>添加时间:</th>
									<td><?php echo $address['date_add']?></td>
								</tr>
							</thead>
							<thead>
								<tr>
									<th>修改时间:</th>
									<td><?php echo $address['date_upd']?></td>
								</tr>
							</thead>
						</table>
					</div>
					<div class="clear"></div>
				</div>
            </div>
            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div id="footer">
    <div class="bottom">
        <div class="Turnpage_leftper">
        </div>
        <!--end of div class Turnpage_leftper-->
        <div class="Turnpage_rightper">
        </div>
        <!--end of div class Turnpage_rightper-->
    </div>
</div>
<!--END FOOTER-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
    });
</script>
