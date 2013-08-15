<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<script language="javascript">
setInterval("refresh()", 120000);
function refresh(){
  window.location.reload();
}
</script>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'user/user_charge_orders/';?>'>会员充值记录列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">

            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索：
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
                            <?php echo view_tool::sort('ID',4, 40);?>                         
                           <th width="80px">本站订单号</th>
						   <th width="80px">第三方交易号</th>                              
                            <th width="50px">充值金额</th>                                          
                           <th width="60px">用户</th>
                           <th width="100px">支付银行</th>                           
                            <?php echo view_tool::sort('状态',8, 60);?>
                            <?php echo view_tool::sort('IP/地址', 12, 50);?>
                            <?php echo view_tool::sort('充值时间/返回时间', 16, 80);?>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($user_list as $rs) : ?>
                    <tr>
                        <td><input class="sel" name="userids[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
                        <td><?php echo $rs['id'];?>&nbsp;</td>
                        <td><?php echo $rs['order_num'];?>&nbsp;</td>
                        <td><?php echo $rs['ret_order_num'];?>&nbsp;</td>
                        <td><?php echo $rs['money'];?>&nbsp;</td>                        
                        <td><a href="/user/user/account/<?php echo $rs['userinfo']['id'];?>" target="_blank"><?php echo $rs['userinfo']['lastname'];?></a>&nbsp;</td> 
                        <td><?php 
                        if (!empty($pay_banks[$rs['bankname']])) {
                            echo $pay_banks[$rs['bankname']];
                        }
                        else {
                        	echo $rs['bankname'];
                        }
                        ?>&nbsp;</td>
                        <td><?php
							if($rs['status']==0){
								echo "<font color=gray>发出支付</font>";
							}elseif($rs['status']==1){
								echo "<font color=green>支付成功</font>";
							}elseif($rs['status']==2){	
								echo "<font color=red>支付失败</font>";
							}
						 ?>&nbsp;</td>
                        <td><?php echo $rs['ip'];?>&nbsp;</td>                       
                        <td><?php echo $rs['add_time'];?>&nbsp;
                        <br />
                        <?php
                        if(empty($rs['ret_time']))
                        {
                            echo '...';
                        }
                        else
                        {
                            echo $rs['ret_time'];
                        };?>
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