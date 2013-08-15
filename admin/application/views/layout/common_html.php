<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * 默认View
 * @package feedback
 * @author nickfan<nickfan81@gmail.com>
 * @link http://feedback.ketai-cluster.com
 * @version $Id: default_html.php 191 2010-04-14 01:31:56Z fzx $
 */
if(isset($resourceUpdateTimestamp) && !empty($resourceUpdateTimestamp)) {
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s' , $resourceUpdateTimestamp) . ' GMT');
    if((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $resourceUpdateTimestamp) || (isset($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) < $resourceUpdateTimestamp)) {
        header('HTTP/1.0 304 Not Modified');
        exit;
    }
}

if(isset($resourceEtag) && !empty($resourceEtag)) {
    header('Etag: ' . $resourceEtag);
    if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $resourceEtag == $_SERVER['HTTP_IF_NONE_MATCH']) {
        header('HTTP/1.0 304 Not Modified');
        exit;
    }
}

if(isset($resourceCacheTimeInterval)) {
    if($resourceCacheTimeInterval==-1) {
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
    }else {
        if($resourceCacheTimeInterval>0) {
            header('Cache-control: max-age='.$resourceCacheTimeInterval);
        }
        if(isset($resourceExpiresTimestamp) && !empty($resourceExpiresTimestamp)) {
            header('Expires: ' . gmdate('D, d M Y H:i:s', $resourceExpiresTimestamp) . ' GMT');
        }else {
            header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$resourceCacheTimeInterval) . ' GMT');
        }
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php isset($title) && print(' - '.$title);?></title>
        <link rel="stylesheet" href="<?php echo url::base();?>css/reset.source.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo url::base();?>css/grids.source.css" type="text/css" media="screen" />
        <link type="text/css" href="<?php echo url::base();?>css/jqueryui/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
        <link rel="stylesheet" href="<?php echo url::base();?>css/style_new.css" type="text/css" media="screen" />
        <?php isset($addon_css_link_context) && print($addon_css_link_context);?>
        <script type="text/javascript" src="<?php echo url::base();?>js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="<?php echo url::base();?>js/jquery-ui-1.8.2.custom.min.js"></script>
        <script type="text/javascript" src="<?php echo url::base();?>js/jquery.blockUI.src.js"></script>
        <script type="text/javascript" src="<?php echo url::base();?>js/jquery.bgiframe.js"></script>
        <?php isset($addon_js_link_context) && print($addon_js_link_context);?>
        <?php if(isset($addon_css_content_ontext)) {?>
        <style type="text/css">
            <!--
                <?php echo $addon_css_content_ontext;?>
            -->
        </style>
            <?php }//end of $addon_css_content_ontext?>
        <?php if(isset($addon_js_content_context)) {?>
        <script type="text/javascript">
            //<![CDATA[
    <?php echo $addon_js_content_context;?>
        //]]>
        </script>
            <?php }//end of $addon_js_content_context?>
        <script type="text/javascript">
            /* 高级搜索 */
            function advance_search_toggle(){
                if($("#advance_search_content").css('display')=='none'){
                    $("#advance_search_content").show();
                    $("#advance_search_image").attr('src','<?php echo url::base();?>images/arrow-down.gif');
                }else{
                    $("#advance_search_content").hide();
                    $("#advance_search_image").attr('src','<?php echo url::base();?>images/arrow-up.gif');
                }
            }
            /* 菜单 */
            function menu_toggle(Obj){
                var obj_id = $(Obj).attr('id');
                $("#submenu > ol").hide();
                $("#firstmenu > li").removeClass('current');
                $("#submenu_"+obj_id).show();
                $(Obj).parent().addClass('current');
            }
            /* 显示底部每页显示多少条 */
            function show_foot_per_page(){
                $("#footer_per_page").toggle();
            }
            /* 表单提交 */
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
                $('.new_content').click(function(){
                    $("#footer_per_page").hide();
                });
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
                    key = $(this).attr("temp");
                    if($(this).attr("checked")){
                        $("div[id='top_div_"+key+"']").attr("class","row highlight selected")
                    }else{
                        $("div[id='top_div_"+key+"']").attr("class","row")
                    }
                });

                $('#select_type').change(function(){
                    select_temp = $('#select_type option:selected').attr("temp");
                    $("#select_type option").each(function(){//遍历option
                        temp    = $(this).attr("temp");
                        if(select_temp == temp){
                            $("#input_"+temp).show()
                        }else{
                            $("#input_"+temp).hide()
                        }
                    });
                });
                /* 按钮风格 */
                $(".ui-button-small,.ui-button").button();
                
                $('.new_main_nav ul').bgiframe();
            });
        </script>
    </head>
    <body>
        <div id="page">
            <!--**header start**-->
            <div class="new_header">
                <div class="new_header_bg">
                    <h1><a href="#">管理平台</a></h1>
                    <div class="new_header_info" id="header_message">
                        <span class="cBlue"><a href="<?php echo url::base();?>order/order/index/noprocessed">未处理订单（<span class="cOrange" id="header_message_amount">0</span>）</a></span>
                    </div>
                    <div class="new_header_right">
                        <div class="new_header_top_menu">
                            <img src="/images/new_top_menu_l.gif" width="30" height="29">
                            <ul>
                                <li class="m1"></li>
                                <li class="m3"><a href="/">桌面</a></li>
                                <li class="m2"><a href="http://help.surlink.cn" target="_blank">帮助</a></li>
                                <li><a href="http://<?php echo Mysite::instance()->get('domain');?>" target="_blank">浏览网店</a></li>
                            </ul>
                        </div>
                        <p>您好, <?php echo $manager_data['name'];?>
                            [ <a class="cYellow" href="<?php echo url::base();?>manage/manager/change_password">更改密码</a> ]
                            [ <a href="<?php echo url::base();?>login/logout" class="cYellow">退出</a> ]</p>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            $(".new_main_nav > li").each(function(i){
                                $(this).mouseover(function(){
                                    if ($(this).attr('id') == "on") {
                                    } else {
                                        $(this).addClass("on");
                                        $(this).children("a").addClass("on");
                                    }
                                }).mouseout(function(){
                                    if ($(this).attr('id') == "on") {
                                    } else {
                                        $(this).removeClass("on");
                                        $(this).children("a").removeClass("on");
                                    }
                                });
                            });
                            $(".new_main_nav ul ").css({display: "none"}); // Opera Fix
                            $(".new_main_nav li").hover(function(){
                                $(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
                            },function(){
                                $(this).find('ul:first').css({visibility: "hidden"});
                            });

                            $("#new_common_use_title").click(function(){
                                if ($(this).parent().children("ul")[0].className == "dis_none") {
                                    $(this).parent().children("ul").attr("className","dis");
                                } else {
                                    $(this).parent().children("ul").attr("className","dis_none");
                                }

                            });

                            //$(".newgrid_tab ul li:first").addClass("on");
                            /* 常用操作 */
                            $(".pro_oper > li").each(function(i){
                                $(this).hover(function(){
                                    $(".new_float_parent").removeAttr("style");
									$(this).addClass("on");
                                },function(){
                                	$(".new_float_parent").removeAttr("style");
                                    $(this).removeClass("on");	
                                })
                            })
                            //grid color
                            $(".newgrid tr:odd").addClass("odd");
                            $(".newgrid tr:even").addClass("even");
                            $(".newgrid tr").hover(function(){
                                $(this).addClass("on_mouse")
                            },function(){
                                $(this).removeClass("on_mouse")
                            });
                        });
                    </script>
                    <div class="new_menu clear">
                        <?php echo menu::current_menus();?>
                        <span class="new_common_use">
                            <h2 id="new_common_use_title">常用操作</h2>
                            <ul class="dis_none">
                                <li><a href="/product/product/add">添加新商品</a></li>
                                <li><a href="/order/order">订单列表</a></li>
                                <li class="last"><a href="/user/user">会员列表</a></li>
                            </ul>
                        </span>
                    </div>
                </div>
            </div>
            <!--**header end**-->
            <!--**error start**-->
            <?php echo remind::get();?>
            <!--**error end**-->
			<?php echo (!empty($content))?$content:'';?>
        </div>
        <div id="site_list_content" style="display:none;">
        </div>
    </body>
    <script type="text/javascript">

    var url_base = '<?php echo url::base(); ?>';
    
    $(document).ready(function(){
            var dialogOpts = {
                title: "站点列表",
                modal: true,
                autoOpen: false,
                height: 300,
                width: 600,
                bgiframe: true
            };
            $("#site_list_content").dialog(dialogOpts);
            $('#header_message').hide();
            
        });
        function open_site_list()
        {
        	$.ajax({
        		url: url_base + 'manage/site/site_list_dialog',
                type: 'POST',
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

        var ajax_block = function() {
			var t = {
				open: function() {
					$.blockUI({
						css: {
							width: '34px',
							height: '34px',
							border: '1px solid #000',
							textAlign: 'center',
							backgroundColor: '#fff',
					        cursor: 'wait',
					        left: '49%',
					        top: '49%'
						},
						overlayCSS: { 
							backgroundColor: '#000',
							opacity: 0.1,
							cursor: 'default'
					    },
						message: $('<img border="0" src="' + url_base + 'images/loading.gif">')
					});
				},
				close: function() {
					$.unblockUI();
				}
			};
			return t;
        }();

        function ajax_download(url)
    	{
    		ajax_block.open();
    		$.ajax({
    			url: url,
    			type: 'GET',
    			dataType: 'json',
    			timeout: 120 * 1000,
    			success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					location.href = retdat['content'];
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			},
    			error: function() {
    				ajax_block.close();
    				showMessage('操作失败', '<font color="#990000">请稍后重新尝试！</font>');
    			}
    		})
    	}
    </script>
</html>
