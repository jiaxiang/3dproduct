<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'user/tq_extract/';?>'>操作提取金额信息</a></li>
            </ul>
        </div>`																																														
        <div class="newgrid_top">
            <ul class="pro_oper">
                 <li><a href="/user/tq_extract/"><span class="add_word">返回列表</span></a></li>
            </ul>
            
        </div>
        <table  cellspacing="0">
              <thead>
			  		 <tr class="headings">
                        <th colspan="9">会员信息</th>
                    </tr>
                    <tr class="headings">
                        <th width="150" class="txc">会员姓名</th>
						<th width="60" class="txc">性别</th>
                        <th width="100" class="txc">会员名</th>
						<th width="70" class="txc">手机</th>
						 <th width="70" class="txc">电话</th>
						 <th width="100" class="txc">用户余额</th>
						 <th width="120" class="txc">用户邮箱</th>
						 <th width="260" class="txc">地址</th>
                        <th></th>
                    </tr>
          </thead>
                <tbody>
                    <tr>
                        <td class="txc"><?php echo $data['user']['title'];?></td>
						<td class="txc"><?php if($data['user']['sex']==1){echo '男';}else{echo '女';}?></td>
						<td class="txc"><?php echo $data['user']['lastname']?></td>
						<td class="txc"><?php echo $data['user']['mobile'];?></td>
						<td class="txc"><?php echo $data['user']['tel'];?></td>
						<td class="txc"><?php  echo $data['user']['user_money'];?></td>
						<td class="txc"><?php  echo $data['user']['email'];?></td>
						<td class="txc"><?php echo $data['user']['address'];?></td>
						<td></td>
                    </tr>
				</tbody>
	  </table>
			<table  cellspacing="0">
              <thead>
			  		 <tr class="headings">
                        <th colspan="9">账号信息</th>
                    </tr>
                    <tr class="headings">
						<th width="120" class="txc">开户行姓名</th>
						<th width="120" class="txc">省份证</th>
						<th width="150" class="txc">开户银行名称</th>
						<th width="160" class="txc">支行名称</th>
						<th width="100" class="txc">省</th>
						<th width="100" class="txc">城市</th>
						<th></th>
                    </tr>
              </thead>
                <tbody>
                    <tr>
						<td class="txc"><?php echo $data['user']['real_name'];?></td>
						<td class="txc"><?php echo $data['user']['identity_card'];?></td>
                        <td class="txc"><?php echo $data['bankinfo'][0][$data['user']['bank_name']];?></td>
						<td class="txc"><?php if(!empty($data['user']['bank_detail'])){echo $data['user']['bank_detail'];}?></td>
						<td class="txc"><?php echo $data['user']['province']?></td>
						<td class="txc"><?php echo $data['user']['city'];?></td>
						<td></td>
                    </tr>
				</tbody>
			</table>
			<table  cellspacing="0" style=" margin-top:10px;">
              <thead>
			  		 <tr class="headings">
                        <th colspan="9">订单信息</th>
                    </tr>
                    <tr class="headings">
                        <th width="150">订单号</th>
                        <th width="150">取款人姓名</th>
						 <th width="150" class="txc">当前状态</th>
						 <th width="150" class="txc">申请时间</th>
                        <th></th>
                    </tr>
              </thead>
                <tbody>
                    <tr>
                        <td><?php echo $data['extract']['order_num'];?></td>
                        <td><?php echo $data['extract']['tq_name'];?></td>
						<td class="txc"><?php  echo $data['tq_ext'][0][$data['extract']['type']];?></td>
						<td class="txc"><?php  echo $data['extract']['addtime'];?></td>
						<td></td>
                    </tr>
				</tbody>
			</table>	
		    <table width="100%"  cellspacing="0" style=" margin-top:10px;">
			 	<thead>
			  		 <tr class="headings">
                        <th colspan="2">提取操作信息</th>
                    </tr>
				</thead>
				<form id="add_form" name="add_form" method="post"  action="<?php echo url::base() . url::current();?>">
				<tbody>		
                    <tr>
                      <td width="150" align="right">剩余金额：</td>
                      <td><?php echo $data['user']['user_money'];?>
                        <input type="hidden" name="user_money" value="<?php echo $data['user']['user_money'];?>">
                        <input type="hidden" name="tq_name" value="<?php echo $data['extract']['tq_name'];?>">
						<input type="hidden" name="u_id" value="<?php echo $data['user']['id'];?>">
						<input type="hidden" name="deductible" value="<?php echo $data['extract']['deductible'];?>"></td>
                    </tr>
                    <tr>
                      <td align="right">提取金额：</td>
                      <td ><?php echo $data['extract']['money'];?>
					  <input type="hidden" name="money" value="<?php echo $data['extract']['money'];?>"></td>
                    </tr>
                    <tr>
                      <td align="right"><span class="txc">状态：</span></td>
                      <td ><select name="type" >
                        <?php foreach($data['tq_ext'][0] as $k=>$v){?>
                        <option value="<?php echo $k?>" <?php if($k==$data['extract']['type']){echo 'selected';}?>><?php echo $v?></option>
                        <?php }?>
                      </select></td>
                    </tr>
                    <tr>
                      <td align="right"><span class="txc">手续费</span>：</td>
                      <td><input name="poundage" type="text" class="text required" value="<?php echo $data['extract']['poundage'];?>" size="50"></td>
                    </tr>
                    <tr>
                      <td align="right"><span class="txc">备注：</span></td>
                      <td><label>
                        <input name="content" type="text" class="text required" value="<?php  echo $data['extract']['content'];?>" size="50">
                      </label></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><label>
                        <input type="submit" name="Submit" value="提交">
                      </label></td>
                    </tr>
                </tbody> </form>
        </table>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate({});
    });
</script>