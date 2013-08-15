<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">回复留言</li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--** content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <div class="main_content" style="visibility: visible; opacity: 1;">
                <div class="finder">
                    <div class="finder-list" >
                        <?php foreach ($message_replys as $key=>$rs) { ?>
                        <div class="row">
                            <div class="infoPanel" id="message">
                                <div class="infocontent_frame" style="visibility: visible; opacity: 1;">

                                    <!--** manager_info framework start **-->
                                    <div class="division">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="finderInform">
                                            <thead>
                                                <tr>
                                                    <th width="20%" style="font-size:14px" colspan="2">商家信息</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                <tr>
                                                    <th width="20%">商家名称：</th>
                                                    <td style="text-align:left"><?php echo $rs['site_manager_name']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th width="20%">电话：</th>
                                                    <td style="text-align:left"><?php echo $rs['phone']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th width="20%">商家邮箱：</th>
                                                    <td style="text-align:left"><?php if(!empty($rs['email']))?><a href="mailto:<?php echo $rs['email']?>"><?php echo $rs['email']?></a></td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>
                                    <!--** manager_info framework end **-->

                                    <!--** message_info framework start **-->
                                    <div class="division">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="finderInform">
                                            <thead>
                                                <tr>
                                                    <th width="20%" style="font-size: 14px" colspan="2">留言信息&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th width="20%">标题：</th>
                                                    <td style="text-align:left"><?php echo $rs['title']?></td>
                                                </tr>
                                                <tr>
                                                    <th width="20%">优先级：</th>
                                                    <td style="text-align:left"><?php echo $rs['status']?></td>
                                                </tr>
                                                <tr>
                                                    <th width="20%">内容：</th>
                                                    <td style="text-align:left"><?php echo $rs['content']?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!--** reply_info framework start **-->
                                    <?php if(!empty($rs['is_reply'])) {?>
                                    <div class="division">
                                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="finderInform">
                                            <thead>
                                                <tr>
                                                    <th width="20%" style="font-size: 14px">回复信息&nbsp;</th>
                                                </tr>
                                            </thead>
                                                    <?php foreach($rs['replies'] as $val) {?>
                                            <tbody>
                                                <tr>
                                                    <td style="color:#192e32;font-size: 14px; font-weight:700;text-align:left"><?php echo $val['manager_name']?>于<?php echo $val['update_timestamp']?>回复：</td>
                                                </tr>
                                                <tr>
                                                    <td style="color:#192e32;font-size: 12px;text-align:left"><?php echo $val['content']?></td>
                                                </tr>
                                            </tbody>
                                                    <?php  } ?>
                                        </table>
                                    </div>
                                    <?php  } ?>
                                    <!--** reply_info framework end **-->
                                    
                                    <!--** manager_reply framework start **-->
                                    <form id="reply_form" name="reply_form" method="post" action="<?php echo url::base().'manage/message/do_edit';?>">
                                        <div class="out_box">
                                            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="finderInform">
                                                <input type="hidden" name="message_id" value="<?php echo $rs['id']?>">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2" width="20%" style="font-size: 14px">管理员回复：&nbsp;</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th>内容<span class="required">*</span>：</th>
                                                        <td align="left" style="text-align:left" class="d_line"><textarea id="content" name="content" cols="75" rows="10" class="text required" style="width:100%" type="textarea"></textarea>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color:#192e32;font-size: 12px;text-align:left">是否发邮件：</td>
                                                        <td style="text-align:left">
                                                            <input type="radio" name="send_mail" value="1" checked> 发送邮件
                                                            <input type="radio" name="send_mail" value="0"> 不发送邮件
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="color:#192e32;font-size: 12px;text-align:left" colspan="2">如果您已发过留言，新发的留言会取代现有的留言</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="list_save">
                                             <input type="submit" name="submit" class="ui-button" value="保存返回列表" />
                                        </div>
                                    </form>
                                    <!--** manager_reply framework end**-->
                                </div>
                            </div>
                        </div>
                            <?php  } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/init_tiny_mce.js"></script>
<script type="text/javascript">
var global_site_id = <?php 
			if(isset($rs['site_id']))
			{
				echo $rs['site_id'];
			} else {
				echo site::id();
			}
				?>;
    $(document).ready(function(){
        $("#reply_form").validate({
        	errorPlacement:function(error, element){
	            if(element.attr("name") == "content"){
	                //alert(error);
	                error.appendTo( element.parent());
	            }else{
	                error.insertAfter(element)
	            }
    		}
	   });
	   tinyMCE.execCommand('mceAddControl', true, 'content');
	});
 function initialiseInstance(editor)
{
	//Get the textarea
	var container = $('#' + editor.editorId);
	//Get the form submit buttons for the textarea
	$('input[name=submit]').mouseover(function(e){
		container.val(editor.getContent());
	});
}   
</script>