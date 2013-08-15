<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Administrator</title>
        <link rel="stylesheet" href="<?php echo url::base();?>css/reset.source.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo url::base();?>css/grids.source.css" type="text/css" media="screen" />
        <link type="text/css" href="<?php echo url::base();?>css/custom-theme/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo url::base();?>js/jquery-1.3.2.min.js"></script>
        <script type="text/javascript" src="<?php echo url::base();?>js/jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript">
            function advance_search_toggle(){
                if($("#advance_search_content").css('display')=='none'){
                    $("#advance_search_content").show();
                    $("#advance_search_image").attr('src','<?php echo url::base();?>images/arrow-down.gif');
                }else{
                    $("#advance_search_content").hide();
                    $("#advance_search_image").attr('src','<?php echo url::base();?>images/arrow-up.gif');
                }
            }
            function menu_toggle(Obj){
                var obj_id = $(Obj).attr('id');
                $("#submenu > ol").hide();
                $("#firstmenu > li").removeClass('current');
                $("#submenu_"+obj_id).show();
                $(Obj).parent().addClass('current');
            }
            function show_foot_per_page(){
                $("#footer_per_page").toggle();
            }

            function submit_form(sign,form_id){
                if(!form_id){
                    form_id = "add_form";
                }
                if(!sign){
                    sign = 0;
                }
                $("#submit_target").val(sign);
                $("#" + form_id).submit();
            }

            $(document).ready(function(){
                $('#check_all').click(function(){
                    if($("#check_all").attr("checked")){
                        $("input[class='sel']").attr("checked",true);
                        $("div[id^='top_div_']").attr("class","row highlight selected")
                    }else{
                        $("input[class='sel']").attr("checked",false);
                        $("div[id^='top_div']").attr("class","row")
                    }
                });
                $("input[@type=checkbox][class='sel']").click(function(){
                    key	= $(this).attr("temp");
                    if($(this).attr("checked")){
                        $("div[id='top_div_"+key+"']").attr("class","row highlight selected")
                    }else{
                        $("div[id='top_div_"+key+"']").attr("class","row")
                    }
                });

                $('#select_type').change(function(){
                    select_temp	= $('#select_type option:selected').attr("temp");
                    $("#select_type option").each(function(){//遍历option
                        temp	= $(this).attr("temp");
                        if(select_temp == temp){
                            $("#input_"+temp).show()
                        }else{
                            $("#input_"+temp).hide()
                        }
                    });
                });
            });
        </script>
    </head>
    <body>
        <div id="page">
            <!--**header start**-->
            <div id="header">
                <div id="header-main">
                    <div id="header_left"><img src="<?php echo url::base();?>images/logo.gif" width="376" height="63"></div>
                    <div id="header_right">
                        <p>
                            <?php if(site::id()>0):?>
                                <?php echo site::domain();?><a href="<?php echo url::base();?>manage/site/out">返回</a>
                            <?php endif;?>
                            <a href="javascript:void(0);" onClick="open_site_list();">查看所有站点</a>　
                            <a href="http://admin.2.opococ.com/help/intro-index.html" target="_blank">帮助</a>
                        </p>
                        <p>
                            <span><?php echo $manager_data['name'];?></span>
                            [ <a href="<?php echo url::base();?>manage/manager/change_password">更改密码</a> ]
                            [ <a href="<?php echo url::base();?>login/logout">退出</a> ]
                        </p>
                    </div>
                </div>
                <div id="main-nav">
                    <?php echo menu::current_menus();?>
                </div>
            </div>
            <!--**header end**-->
            <!--**error start**-->
            <?php echo remind::get();?>
            <!--**error end**-->
            <?php if(isset($content)):?>
            <?php echo $content ?>
            <?php else:?>
            读取模板错误，请刷新页面重试！
            <?php endif;?>
        </div>
        <div id="site_list_content"  class='division' style="display:none;margin-top:0pt;">
        </div>
    </body>
    <script type="text/javascript">
    
        $(document).ready(function(){
        	//$("#site_list_content").load("/manage/site/ajax_site_list");
            var dialogOpts = {
                title: "站点列表",
                modal: true,
                autoOpen: false,
                height: 300,
                width: 600
            };
            $("#site_list_content").dialog(dialogOpts);
        });
        function open_site_list()
        {
            $.ajax({
        		url: '<?php echo url::base();?>manage/site/site_list_dialog',
                type: 'post',
                dataType: 'json',
                error: function() {
                    window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#site_list_content").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#site_list_content").dialog("open");
        }
    </script>
</html>
