<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
	<div class="newgrid">
	
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on">
					<a href='<?php echo url::base() . 'distribution/agent_client/index/'.$theUser['id'];?>'>
						<?php echo $theUser['lastname'];?>&nbsp;的下级用户</a>
				</li>
			</ul>
		</div>
		<div class="newgrid_top">
			<ul class="pro_oper">
				<li>
					<a href="/distribution/client_select/index/<?php echo $theUser['id']; ?>"><span class="add_pro">添加下级用户</span></a>
				</li>
			</ul>
			<form id="search_form" name="search_form" class="new_search" method="GET" 
					action="<?php echo url::base().'distribution/agent_client/index/'.$theUser['id'];?>">
			<div>搜索：
				<select name="search_key" class="text">
					<option value="users.lastname"  <?php if ($searchBox['search_key'] == 'users.lastname') echo "SELECTED";?>>用户名</option>
					<option value="users.real_name" <?php if ($searchBox['search_key'] == 'users.real_name')echo "SELECTED";?>>真实姓名</option>
					<option value="users.email"     <?php if ($searchBox['search_key'] == 'users.email')    echo "SELECTED";?>>Email</option>
					<option value="users.mobile"    <?php if ($searchBox['search_key'] == 'users.mobile')   echo "SELECTED";?>>手机号码</option>
					<option value="users.ip"        <?php if ($searchBox['search_key'] == 'users.ip')       echo "SELECTED";?>>IP</option>
				</select>
				<input type="text" name="search_value" class="text" value="<?php echo $searchBox['search_value'];?>">
				<input type="submit" name="Submit2" value="搜索" class="ui-button-small">
			</div>
			</form>
        </div>
        
        <?php if (is_array($dataList) && count($dataList)) {?>
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base().url::current();?>">
			<table  cellspacing="0" >
				<thead>
					<tr class="headings">
						<th width="20px"><input type="checkbox" id="check_all"></th>
						<th width="100px">操作</th>
						<th width="70px">是否是代理</th>
						<th width="80px">用户名</th>
						<th width="150px">Email</th>
						<th width="60px">下线身份</th>
						<th width="70px">普通返点率</th>
						<th width="70px">北单返点率</th>
<!--						<th width="50px">实时合约</th>-->
						<th width="50px">月结合约</th>
						<th width="130px">注册时间</th>
						<th width="100px">IP/地址</th>
						<th width="60px">状态</th>
						<th width="60px">激活</th>
					</tr>
                </thead>
				<tbody>
					<?php foreach ($dataList as $item) : ?>
					<tr>
						<td><input class="sel" name="userids[]" value="<?php echo $item['id'];?>" type="checkbox" /></td>
						<td>
							<a href="<?php echo url::base().'user/user/detail/'.$item['id'];?>">查看</a>&nbsp;
							<a href="<?php echo url::base().'distribution/agent_client/delete/'.$theUser['id'].'/'.$item['id'];?>"
								 onclick="javascript:return confirm('确定删除？')">删除</a>&nbsp;
							<?php if ($theAgent['agent_type'] == 0 || $theAgent['agent_type'] == 11) {?>
							<a href="<?php echo url::base().'distribution/agent_client/edit/'.$item['relationId'] ?>">修改</a>
							<?php } ?>
						</td>
                        <td>
                        	<?php 
							if(isset($item['agent_type']) == false){ 
								echo '非代理';
							} 
							else if ($item['agent_type'] == 0){ 
								echo '普通代理';
							}
							else if ($item['agent_type'] == 1){ 
								echo '特殊超级代理';
							}
							else if ($item['agent_type'] == 2){ 
								echo '特殊二级代理';
							}
							else if ($item['agent_type'] == 11){ 
								echo '一级代理';
							}
							else if ($item['agent_type'] == 12){ 
								echo '二级代理';
							}
                 			?>
                        </td>
                        <td><?php echo $item['lastname'];?>&nbsp;</td>
						<td><?php echo $item['email'];?>&nbsp;</td>
						<td><?php if($item['client_type'] == 0)  {echo '普通用户' ;}
							else if ($item['client_type'] == 1)  {echo '返利用户' ;} 
							else if ($item['client_type'] == 2)  {echo '特殊二级代理' ;} 
							else if ($item['client_type'] == 11) {echo '一级代理'; } 
							else if ($item['client_type'] == 12) {echo '二级代理' ;}		
							?>&nbsp;
						</td>
						<td><?php 
	                        	if ($item['client_type'] == 1 || $item['client_type'] == 12) {echo $item['client_rate'];}
							?>
                        </td>
                        <td><?php 
	                        	if ($item['client_type'] == 1 || $item['client_type'] == 12) {echo $item['client_rate_beidan'];}
                        	?>
                        </td>
                        <!-- 
                        <td style="text-align:center;">
                        	<?php //if ($item['client_type'] == 1) { ?>
	                        	<a href="<?php //echo url::base().'distribution/client_realtime_contract?relationId='.$item['relationId']; ?>" >查看</a>
                        	<?php //} else if ($item['client_type'] == 12) { ?>
                        		<a href="<?php //echo url::base().'distribution/agent_realtime_contract?relationId='.$item['relationId']; ?>" >查看</a>
                        	<?php //} ?>
                        </td>
                         -->
                        <td style="text-align:center;">
                        	<?php if ($item['client_type'] == 1 || $item['client_type'] == 12) { ?>
                        		<a href="<?php echo url::base().'distribution/client_month_contract?relationId='.$item['relationId']; ?>" >查看</a>
                        	<?php } ?>
                        </td>
                        <td><?php echo $item['date_add'];?>&nbsp;</td>
                        <td><?php echo $item['ip'];?>&nbsp;</td>
                        <td><?php echo view_tool::get_active_img($item['active']);?></td>
                        <td>
                          <div id="register_mail_active">
                          	<?php 
                          		$img = $item['register_mail_active']==1 ?'/images/icon/accept.png':'/images/icon/cancel.png';
								echo '<img src="'.$img.'" rev="'.$item['id'].'"/>';	
                          	?>
                          	<input type="hidden" name="mail_active" value="<?php echo $item['register_mail_active'];?>"/>
                          </div>
                        </td>
                    </tr>
					<?php endforeach;?>
                </tbody>
			</table>
		</form>
            <?php }else {?>
            <?php echo remind::no_rows();?>
            <?php }?>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
        <?php echo view_tool::per_page(); ?>
    </div>
    <div class="Turnpage_rightper">
        <div class="b_r_pager">
            <?PHP echo $this->pagination->render('opococ'); ?>
        </div>
    </div>
</div>
<!--END FOOTER-->
<div id="site_msg" style='display:none;'>    
    <form id="msg_form" name="msg_form" class="new_search" method="post" action="<?php echo url::base();?>user/user/site_msg">
        <input type="hidden" name="uid" id="uid">
        <p>消息内容：</p>
        <textarea name='msg' cols='50' rows='6' class="text required"></textarea> <label><font color='red'>*</font></label>
        <br><br><center><input type="submit" value=" 发 送 " class="ui-button-small"></center>
    </form>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>

<script type="text/javascript">
        var default_rate = '0';
        $('input[name=rate]').focus(function(){
            $('.new_float').hide();
            default_rate = $(this).val();
            $(this).next().show();
            $(this).next().children('input[name=client_rate]').focus();
        });
        $('input[name=cancel_order_form]').click(function(){
            $(this).parent().hide();
        });
        $('input[name=submit_order_form]').click(function(){
            var url = '<?php echo url::base();?>distribution/agent_client/set_client_rate';
            var obj = $(this).parent();
            var relationId = $(this).next().val();
            var rate = $(this).prev().val();
            $(this).parent().hide();
            if (rate == default_rate){
                return false;
            }
            obj.prev().attr('disabled','disabled');
            $.ajax({
                type:'GET',
                dataType:'json',
                url:url,
                data:'relationId=' + relationId + '&client_rate=' + rate,
                error:function(){},
                success:
                    function(retdat,status){
                    obj.prev().removeAttr('disabled');
                    if(retdat['status'] == 1 && retdat['code'] == 200)
                    {
                        obj.prev().attr('value',(retdat['content']['client_rate']));
                    }else{
                        alert(retdat['msg']);
                    }
                }
            });
        });
</script>

<script type="text/javascript">
        var default_rate = '0';
        $('input[name=rate]').focus(function(){
            $('.new_float').hide();
            default_rate = $(this).val();
            $(this).next().show();
            $(this).next().children('input[name=client_rate]').focus();
        });
        $('input[name=cancel_order_form]').click(function(){
            $(this).parent().hide();
        });
        $('input[name=submit_order_form]').click(function(){
            var url = '<?php echo url::base();?>distribution/agent_client/set_client_rate';
            var obj = $(this).parent();
            var relationId = $(this).next().val();
            var rate = $(this).prev().val();
            $(this).parent().hide();
            if (rate == default_rate){
                return false;
            }
            obj.prev().attr('disabled','disabled');
            $.ajax({
                type:'GET',
                dataType:'json',
                url:url,
                data:'relationId=' + relationId + '&client_rate=' + rate,
                error:function(){},
                success:
                    function(retdat,status){
                    obj.prev().removeAttr('disabled');
                    if(retdat['status'] == 1 && retdat['code'] == 200)
                    {
                        obj.prev().attr('value',(retdat['content']['client_rate']));
                    }else{
                        alert(retdat['msg']);
                    }
                }
            });
        });
</script>