<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base().'distribution/agent_select/';?>'>添加代理</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
			<form id="search_form" name="search_form" class="new_search" method="GET" 
					action="<?php echo url::base() . 'distribution/agent_select/';?>">
			<div>搜索：
				<select name="search_type" class="text">
					<option value="lastname"  <?php if ($where['search_type'] == 'lastname') echo "SELECTED";?>>用户名</option>
					<option value="real_name" <?php if ($where['search_type'] == 'real_name')echo "SELECTED";?>>真实姓名</option>
					<option value="email"     <?php if ($where['search_type'] == 'email')    echo "SELECTED";?>>Email</option>
					<option value="mobile"    <?php if ($where['search_type'] == 'mobile')   echo "SELECTED";?>>手机号码</option>
					<option value="ip"        <?php if ($where['search_type'] == 'ip')       echo "SELECTED";?>>IP</option>
				</select>
				<input type="text" name="search_value" class="text" value="<?php echo $where['search_value'];?>">
				<input type="submit" name="Submit2" value="搜索" class="ui-button-small">
			</div>
			</form>

        </div>
        <?php if (is_array($user_list) && count($user_list)) {?>
		<table  cellspacing="0" class="table_overflow">
			<form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
				<thead>
					<tr class="headings">
						<th width="20px"><input type="checkbox" id="check_all"></th>
						<th width="100px">操作</th>
						<?php echo view_tool::sort('Email',4, 200);?>
						<?php echo view_tool::sort('用户名',8, 100);?>
						<?php echo view_tool::sort('真实姓名',8, 60);?>
						<?php echo view_tool::sort('固定电话',8, 100);?>
						<?php echo view_tool::sort('手机号码',8, 100);?>
						<?php echo view_tool::sort('注册时间',10, 130);?>
						<?php echo view_tool::sort('IP/地址', 12, 100);?>
						<?php /**echo view_tool::sort('会员等级', 14, 100); **/?>
						<?php echo view_tool::sort('状态', 14, 40);?>
						<?php echo view_tool::sort('激活', 16, 40);?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($user_list as $rs) : ?>
					<tr>
						<td><input class="sel" name="userids[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
						<td>
							<a href="<?php echo url::base().'user/user/detail/'.$rs['id'];?>">查看</a>&nbsp;
							<a href="<?php echo url::base().'distribution/agent/add/'.$rs['id'];?>">升级为代理</a>
						</td>
						<td><?php echo $rs['email'];?>&nbsp;
                        
                        </td>
                        <td><?php echo $rs['lastname'];?>&nbsp;</td>
                        <td><?php echo $rs['real_name'];?>&nbsp;</td>
                        <td><?php echo $rs['tel'];?>&nbsp;</td>
                        <td><?php echo $rs['mobile'];?>&nbsp;</td>
                        <td><?php echo $rs['date_add'];?>&nbsp;</td>
                        <td><?php echo $rs['ip'];?>&nbsp;</td>
                        <td><?php echo view_tool::get_active_img($rs['active']);?></td>
                        <td>
                          <div id="register_mail_active">
                          	<?php 
                          		$img = $rs['register_mail_active']==1 ?'/images/icon/accept.png':'/images/icon/cancel.png';
								echo '<img src="'.$img.'" rev="'.$rs['id'].'"/>';	
                          	?>
                          	<input type="hidden" name="mail_active" value="<?php echo $rs['register_mail_active'];?>"/>
                          </div>
                        </td>
                    </tr>
                        <?php endforeach;?>
                </tbody>
            </form>
        </table>
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
    $(function() {
        var dialogOpts = {
            title: "群发站内消息",
            modal: true,
            autoOpen: false,
            height: 220,
            width: 400
        };
        $("#site_msg").dialog(dialogOpts);
        $("#batch_site_msg").click(function (){
                var i = false, uid = '';
                $('.sel').each(function(){
                    if($(this).attr("checked")==true){
                        i = true;
                        uid += $(this).attr("value") + ',';
                    }
                });
                if(i == false){
                    alert('请选择用户！');
                    return false;
                }
                $("#uid").val(uid);
                $("#site_msg").dialog("open");
                $("#msg_form").validate();
                return false;
            }
        );
        
        //会员的导出
        $("#export").click(function(){
            var arr = $("input[name='userids[]']");
            var str = 'export_point_user=1&';
            for(var i=0;i<arr.length;i++)
            {
                if(arr.eq(i).attr('checked'))
                {
                    str += 'userids[]='+arr.eq(i).val()+'&';
                }
            }
            str = '/user/user/export?'+str;
            location.href=str;
            return false;
        });
        //批量删除用户
        $("#batch_delete").click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要停用的账号！');
                return false;
            }
            if(!confirm('停用的用户不能再登录而且不能用同样的邮箱注册，确认停用吗？')){
                return false;
            }
            $('#list_form').attr('action','/user/user/batch/delete');
            $('#list_form').submit();
            return false;
        });
        //批量恢复用户
        $("#batch_recover").click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要恢复的账号！');
                return false;
            }
            if(!confirm('确认恢复吗？')){
                return false;
            }
            $('#list_form').attr('action','/user/user/batch/recover');
            $('#list_form').submit();
            return false;
        });
        
        $('#register_mail_active img').click(function(){
			var obj = $(this);
            if(obj.next().val() == 0)
            {
				if(confirm("点击确定将激活该账号,是否要激活该账号?"))
				{

					var user_id = obj.attr('rev');
					$.ajax({
	            		url: url_base + 'user/user/active_user',
	            		type: 'POST',
	            		data: 'user_id=' + user_id ,
	            		dataType: 'json',
	            		success: function(retdat, status){	
	            			if (retdat['code'] == 200 && retdat['status'] == 1) {
	            				obj.attr('src','/images/icon/accept.png');
	            				obj.next().val(1);
	            			} else {
	            				alert(retdat['msg']);
	            			}
	            		},
	            		error: function(){
	            			alert('Request error, please try again later!');
	            		}
	            	});
				}
            }
        	
        });
		
        
    });
</script>