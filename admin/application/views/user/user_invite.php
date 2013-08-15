<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'user/user/';?>'>邀请奖励</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li><a href="javascript:void(0);"><span class="rec_pro" id="batch_site_msg">发送短消息</span></a></li>
                <!-- li><a href="/user/user/export"><span class="batch_down" id="export_all">导出所有会员</span></a></li>
                <li><a href="/user/user/export"><span class="batch_down" id="export" >导出指定会员</span></a></li -->
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索：
                    <select name="search_type" class="text">
                        <option value="email" <?php if ($where['search_type'] == 'email')echo "SELECTED";?>>Email</option>
                        <option value="lastname" <?php if ($where['search_type'] == 'lastname')echo "SELECTED";?>>昵称</option>
                        <option value="ip" <?php if ($where['search_type'] == 'ip')echo "SELECTED";?>>IP</option>
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
                            <?php echo view_tool::sort('被邀请人Email',4, 200);?>
                        	<th width="200px">邀请人Email</th>
                            <?php echo view_tool::sort('被邀请人昵称',8, 120);?>
                            <th width="120px">邀请人昵称</th>
                            <?php echo view_tool::sort('被邀请人注册时间',10, 100);?>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($user_list as $rs) : ?>
                    <tr>
                        <td><input class="sel" name="userids[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
                        <td>
                        
                        <?php
                        if(empty($reward_list[$rs['id']])) {
						?>
                        <a href="<?php echo url::base();?>user/user_invite/edit/<?php echo $rs['id'];?>">现在奖励</a>
                        <?php
						} else {
						?>
                         已经奖励
                        <?php
						}
						?>    
                            
                        </td>
                        <td><?php echo $rs['email'];?>&nbsp;</td>
                        <td><?php echo $invite_list[$rs['invite_user_id']]['email'];?>&nbsp;</td>
                        <td><?php echo $invite_list[$rs['invite_user_id']]['lastname'];?>&nbsp;</td>  
                        <td><?php echo $rs['lastname'];?>&nbsp;</td>         
                            

                        <td><?php echo $rs['date_add'];?>&nbsp;</td>
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
    });
</script>