<?php defined('SYSPATH') OR die('No direct access allowed.');?>

<!--** content start**-->

<div id="content_frame">
<div class="grid_c2">
<div class="col_main">
<div class="public_crumb">
<p><a href="/">后台首页</a> 》编辑站点</p>
</div>
<!--**productlist edit start**-->

<div class="edit_area">
<form id="add_form" name="add_form" method="post" action="<?php echo url::base().url::current();?>">
<div class="division">
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tbody>
<tr>
<th width="20%">站点/IP:</th>
<td>
<input type="text" size="50" name="name" class="text required" value="<?php echo $data['name'];?>"/>
</td>
</tr>
</tbody>
</table>
</div>
<div class="btn_eidt">
<table width="445" border="0" cellpadding="0" cellspacing="0">
<tr>
<th width="152"></th>
<td width="293"><input name="Input" type="submit" class="ui-button" value=" 确认 "></td>
</tr>
</table>
</div>
</form>

</div>
<!--**productlist edit end**-->
</div>
</div>
</div>
<!--**content end**-->
<!--**footer start**-->
<div id="footer">
</div>
<!--**footer end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/init_tiny_mce.js"></script>
<script type="text/javascript">
var global_site_id = <?php 
			if(isset($data['site_id']))
			{
				echo $data['site_id'];
			} else {
				echo site::id();
			}
				?>;
    $(document).ready(function(){
		tinyMCE.execCommand('mceAddControl', true, 'content');
        $("#add_form").validate();
    });
function initialiseInstance(editor)
{
	//Get the textarea
	var container = $('#' + editor.editorId);
	//Get the form submit buttons for the textarea
	$('input[name=Input]').mouseover(function(e){
		container.val(editor.getContent());
	});
}
</script>

