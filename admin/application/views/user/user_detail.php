<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">会员详情</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="newgrid">
            <!--** edit start**-->
            <div class="out_box">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                           	 <tr>
                                    <th>用户名：</th>
                                    <td>
                                    <label><?php echo $data['username'];?></label>
                                    </td>
                              </tr>
                                <tr>
                                    <th>邮箱：</th>
                                    <td>
									<label><?php echo $data['email'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>真实姓名：</th>
                                    <td>
									<label><?php echo $data['name'];?></label>
                                    </td>
                                </tr>
                                 <tr>
                                    <th>手机号码：</th>
                                    <td>
                                     <label><?php echo $data['mobile'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">注册时间：</th>
                                    <td><?php echo $data['reg_time'];?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>最后登录时间：</th>
                                    <td><?php echo $data['lastlogin_time'];?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clear">&nbsp;</div>


            </div>
            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<div id='example' style="display:none;"></div>
<div id='address' style="display:none;"></div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
	    $('#birthday').datepicker({dateFormat:"yy-mm-dd"});

        $("#add_form").validate({
			rules:{
        		email: {
					maxlength: 255
				},
				firstname: {
					maxlength: 50
				},
				lastname: {
					maxlength: 50
				}
			},
    		messages:{
				email: {
					required: '邮箱不可为空',
					maxlength: '邮箱长度不可超过 255 个字符'
				},
				firstname: {
					required: '名不可为空',
					maxlength: '名长度不可超过 50 个字符'
				},
				lastname: {
					required: '姓不可为空',
					maxlength: '姓长度不可超过 50 个字符'
				}
			}
        });
        $("#edit_password_form").validate({
			errorClass:"error",
			rules:{
        	password:{
				required:true,
				rangelength:[5,200]
        		}
			}
            });
        //查看邮件模板
        var dialogOpts = {
            title: "邮件模板内容",
            modal: true,
            autoOpen: false,
            height: 500,
            width: 600
        };
        $("#example").dialog(dialogOpts);

        $(".mail_template").click(function(){
            var id = $(this).attr('id');
            $("#example").html("loading...");
            $.ajax({
        		url: '<?php echo url::base();?>site/mail/ajax_content' + '?id=' + id,
                type: 'GET',
                dataType: 'json',
                error: function() {
                    window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#example").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#example").dialog("open");
        });

        var addressOpts = {
            title: "编辑地址",
            modal: true,
            autoOpen: false,
            width: 800
        };
        $("#address").dialog(addressOpts);
        $(".address_edit").click(function(){
            var id = $(this).attr('id');
            $("#address").html("loading...");
            $.ajax({
        		url: '<?php echo url::base();?>user/address/ajax_edit' + '?id=' + id,
                type: 'GET',
                dataType: 'json',
                error: function() {
                    window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#address").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#address").dialog("open");
        });
        $(".address_add").click(function(){
            var user_id = "<?php echo $data['id'];?>";
            $("#address").html("loading...");
            $.ajax({
        		url: '<?php echo url::base();?>user/address/ajax_add' + '?user_id=' + user_id,
                type: 'GET',
                dataType: 'json',
                error: function() {
                    window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#address").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#address").dialog("open");
        });
    });
</script>