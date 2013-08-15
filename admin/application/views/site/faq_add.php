<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加FAQ</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--** content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**productlist edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>

                                <tr>
                                    <th width="15%">标题<span class="required"> *</span>：</th>
                                    <td>
                                        <input type="type" size="60" name="title" class="text required" value="" />
                                    </td>
                                </tr>

                                <tr>
                                    <th>内容<span class="required"> *</span>：</th>
                                    <td class="d_line">
                                        <textarea id="content" name="content" cols="75" rows="20" class="text _x_ipt t400 required" type="textarea" maxth="255" ></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input type="submit" name="submit" class="ui-button" value="保存返回列表 "/>
                </div>
            </form>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/init_tiny_mce.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate({
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
