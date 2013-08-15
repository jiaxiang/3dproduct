<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加新会员</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--** edit start**-->
            <div class="edit_area">
                <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width=20%>邮箱：</th>
                                    <td><input size="60" name="email" class="text" value=""><span class="required"> *</span></td>
                                </tr>
                                <!-- tr>
                                    <th>姓：</th>
                                    <td><input size="60" name="firstname" class="text" value=""><span class="required"> *</span>
                                    </td>
                                </tr -->
                                <tr>
                                    <th>姓名：</th>
                                    <td><input size="60" name="lastname" class="text" value=""><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th>密码：</th>
                                    <td><input type="password" size="60" name="password" class="text" value=""><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th>是否发邮件：</th>
                                    <td>
                                        <input type="radio" name="send_mail" value="1" checked>发送邮件
                                        <input type="radio" name="send_mail" value="0">不发送邮件
                                        <!--[<a href="javascript:void(0);" class="mail_template" id="3">注册邮件模板</a>]-->
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="list_save">
                        <input type="button" name="button" class="ui-button" value="保存返回列表" onclick="submit_form();"/>
                        <input type="button" name="button" class="ui-button" value="保存当前" onclick="submit_form(1);"/>
                        <input type="hidden" name="submit_target" id="submit_target" value="0" />
                    </div>
                </form>
            </div>
            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->

<div id='example' style="display:none;"></div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate({
			errorClass:"error",
			rules:{
        		email:{
					required:true,
					email:true
    				},
    			firstname:{
    				required:true,
					maxlength:200
        			},
    			lastname:{
    				required:true,
    				maxlength:200
        			},
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
            $("#example").html("loading...");
            $("#example").load("<?php echo url::base();?>site/mail/ajax_template/reg");
            $("#example").dialog("open");
        });
    });
</script>