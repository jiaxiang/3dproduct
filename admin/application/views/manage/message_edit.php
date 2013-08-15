<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">修改留言</li>
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
            <!--**message edit start**-->
            <form id="edit_form" name="edit_form" method="post" action="<?php echo url::base();?>manage/message/put">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="20%">标题<span class="required"> *</span>：</th>
                                    <td>
                                        <input type="text" size="60" name="title" class="text required" value="<?php echo $messages['title']?>" maxlength="78">
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">优先级：</th>
                                    <td>
                                        <?php foreach($status as $key=>$value):?>
                                        <input type="radio" name="status" value="<?php echo $key;?>" <?php if($messages['status'] == $key) echo 'checked'?>> <?php echo $value;?>
                                        <?php endforeach;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">邮箱<span class="required"> *</span>：</th>
                                    <td>
                                        <input type="text" name="email" class="text required email" size="60" value="<?php echo $messages['email']?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>内容<span class="required"> *</span>：</th>
                                    <td class="d_line">
                                        <textarea id="content" name="content" style="width:95%;" rows="10" class="text required" style="width:100%;"><?php echo $messages['content']?></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input type="hidden" name="id" value="<?php echo $messages['id']?>"></input>
                    <input type="submit" name="submit" class="ui-button" value="保存" />
                </div>
            </form>
            <!--**message edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/init_tiny_mce.js"></script>
<script type="text/javascript">
var global_site_id = <?php 
			if(isset($messages['site_id']))
			{
				echo $messages['site_id'];
			} else {
				echo site::id();
			}
				?>;
    $(document).ready(function(){
        $("#edit_form").validate({
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