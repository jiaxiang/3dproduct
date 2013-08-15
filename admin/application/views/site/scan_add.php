<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加扫描站点</li>
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
<!--**productlist edit start**-->
<form id="add_form" name="add_form" method="post" action="<?php echo url::base().url::current();?>">
<div class="edit_area">
<div class="division">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody>
<tr>
<th width="20%">HTTP://</th>
<td>
    <input type="text" size="50" name="name" class="text required" value="" > *请输入IP或域名！例如:www.123.com or 127.0.0.1
</td>
</tr>
</tbody>
</table>
        </div>
        </div>
        <div class="list_save">
        	<input name="submit" type="submit" class="ui-button" value=" 扫描 ">
        </div>
      </form>
      <!--**productlist edit end**-->
    </div>
  </div>
</div>
<!--**footer end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/init_tiny_mce.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
		tinyMCE.execCommand('mceAddControl', true, 'content');
        $("#add_form").validate();
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