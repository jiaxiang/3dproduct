<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Login</title>
        <style type="text/css">
            <!--
            html,body{height:100%;margin:0;padding:0;}
            body{text-align:center;font:12px/1.35 Arial, Helvetica, sans-serif; background: #eeeeff center center repeat-x;line-height:1.5;text-align:center;min-width:534px;min-height:478px; text-align:left; color:#135163}
            input{outline:none;}
            ol, ul { list-style: none; margin:0; padding:0; border:0; }
            #login{	position:absolute;top:50%;left:50%;	margin-top:-239px;margin-left:-267px;width:534px;height:478px;overflow:hidden;}
            .top{margin-top:35px; background:#000033; height:60px; padding:2px;width:546px;color:#ffffff;text-align:center;}
            .content{height:274px; background:#eeeeee;border:1px solid #aaaaaa;}
            .bottom{width:534px; height:97px; background:url(img/new_login_bot.png)}
            .con_top{width:330px; margin:0 auto;height:33px; clear:both;}
            .fixfloat:after     { content:".";display:block;clear:both;visibility:hidden;height:0;}
            .fixfloat           { zoom:1}
            .message{background:url(images/new_login_error_ico.png) no-repeat 0 17px; color:#CC0000; padding-left:20px; height:35px; line-height:18px; clear:both;}
            .message p{margin:0; padding:0; display:block; float:left; margin-top:15px;}
            ul{width:335px; margin:0 auto;}
            li{margin-top:10px; display:block; clear:both; }
            .login_check{margin:0; padding:10px 0 8px 0; *padding:4px 0; _padding:6px 0 2px 0;}
            .input_text_over{background:url(images/new_login_input_bg_l.png) no-repeat 0 0; height:16px; padding:8px 5px; border:0; width:252px; color:#135264}
            .input_text_on{background:url(images/new_login_input_bg_l.png) no-repeat 0 -32px; height:16px; padding:8px 5px; border:0; width:252px; color:#135264}
            * html input{margin-left:-3px;}
            li .input_right_over{background:url(images/new_login_input_bg_r.png) 100% 0 no-repeat;*background:url(images/new_login_input_bg_r.png) 100% 1px no-repeat; display:block; height:32px; *height:33px; overflow:hidden;}
            li .input_right_on{background:url(images/new_login_input_bg_r.png) 100% -32px no-repeat;*background:url(images/new_login_input_bg_r.png) 100% -31px no-repeat; display:block; height:32px; *height:33px; overflow:hidden;}
            .input_check{margin-top:0px; vertical-align:middle;}
            * html .input_check{margin-top:-3px; vertical-align:middle;}
            label {float:left; width:68px; line-height:32px; color:#135163; font-size:14px;}
            .check{height:30px;}
            .check .input_right_over,.check .input_right_on{width:160px; float:left;}
            .check .input_text_over,.check .input_text_on{width:80px;}
            .check_num{float:left; margin-left:5px; display:inline;}
            .new_login_bt{ background:url(images/btn_home_login.gif) no-repeat; width:130px; height:32px; border:0}
            .line{background:url(images/new_login_line.png); height:2px; line-height:2px; font-size:0; width:532px; margin:0 auto; clear:both;}
			input:-webkit-autofill {background:#FFF !important; }
        </style>
        <script type="text/javascript" src="<?php echo url::base();?>js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
        <script type="text/javascript">
            var flag = 0;
            $(document).ready(function() {
                $("#add_form").validate({
                    rules: {
                        username: {
                            required: true
                        },
                        password: {
                            required: true,
                            minlength:6
                        }
                    },
                    errorPlacement:function(error, element){
                        $('#error_home_tr').html('<p>用户名和密码请填写完整.</p>');
                        $("#error_home_tr").show();
                    }
                });
				if ($.browser.webkit) {
					$('input[name="password"]').attr('autocomplete', 'off');
					$('input[name="username"]').attr('autocomplete', 'off');
				}
            });
            function reload_secoder(Obj){
                flag++;
                $(Obj).attr("src","<?php echo url::base();?>login/secoder?"+flag);
            }
        </script>
    </head>
    <body>
        <form id="add_form" name="add_form" method="post" action="<?php echo url::base().url::current(TRUE);?>">
            <div id="login">
                <div class="top"><h1>后台管理</h1></div>

                <div class="content">
                    <div class="con_top">
                        <div class="message" id="error_home_tr" style="display:<?php echo $error_display;?>;">
                            <?php echo $error;?>
                        </div>
                    </div>
                    <ul>
                        <li>
                            <span class="input_right_over">
                                <label for="username">用户名：</label>
                                <input type="text" id="username" name="username" class="input_text_over" maxlength="255" value="<?php echo $username;?>"/>
                            </span>
                        </li>
                        <li>
                            <span class="input_right_over">
                                <label for="password">密　码：</label>
                                <input type="password" id="password" name="password" minlength="6" class="input_text_over" maxlength="255"/>
                            </span>
                        </li>
                        <?php if($login_error_count > 3):?>
                        <li class="check">
                            <span class="input_right_over">
                                <label for="checknum">验证码：</label>
                                <input type="text" id="checknum" name="secode" maxlength="6" class="input_text_over required" />
                            </span>
                            <span class="check_num"><img src="<?php echo url::base();?>login/secoder" style="cursor:pointer;" onClick="reload_secoder(this);"/></span>
                        </li>
                        <?php endif;?>
                        <li class="login_check">
                            <label>&nbsp;</label>
                            <input id="remember" value="1" type="checkbox" name="remember" checked="checked"/>记住用户名
                        </li>
                    </ul>
                    <div class="line"></div>
                    <ul>
                        <li>
                            <label>&nbsp;</label>
                            <input type="submit" class="new_login_bt" value=""/>
                        </li>
                    </ul>
                </div>

                <div class="bottom"></div>
            </div>
        </form>
        <script type="text/javascript">
            $(document).ready(function(){
                $("input[type='text'],input[type='password']").each(function(){
                    $(this).focus(function(){
                        $(this).addClass("input_text_on").removeClass("input_text_over").parent("span").attr("className", "input_right_on");
                    }).blur(function(){
                        $(this).addClass("input_text_over").removeClass("input_text_on").parent("span").attr("className", "input_right_over");
                    })
                });
            });
        </script>
    </body>
</html>
