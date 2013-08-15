<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">SEO推广信息编辑</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**productlist edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base().url::current(TRUE);?>">
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="15%">默认标题：</th>
                                    <td>
                                        <input type="text" size="80" name="title" id="title" class="text" value="<?php echo $data['title']; ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>默认描述：</th>
                                    <td>
                                        <textarea name="description" id="description" cols="100" rows="5" class="text" type="textarea" ><?php echo $data['description']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>默认关键词：</th>
                                    <td>
                                        <textarea name="keywords" id="keywords" cols="100" rows="5" class="text" type="textarea" ><?php echo $data['keywords']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>首页标题：</th>
                                    <td>
                                        <input type="text" size="80" name="index_title" id="index_title" class="text" value="<?php echo $data['index_title']; ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>首页描述：</th>
                                    <td>
                                        <textarea name="index_description" id="index_description" cols="100" rows="5" class="text" type="textarea" ><?php echo $data['index_description']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>首页关键词：</th>
                                    <td>
                                        <textarea name="index_keywords" id="index_keywords" cols="100" rows="5" class="text" type="textarea" ><?php echo $data['index_keywords']; ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>站点简述：</th>
                                    <td>
                                        <textarea name="seowords" id="seowords" cols="100" rows="5" class="text" type="textarea" ><?php echo $data['seowords']; ?></textarea>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input type="button" name="button" class="ui-button" value=" 确认保存 "  onclick="submit_form(1);"/>
                    <input type="hidden" name="submit_target" id="submit_target" value="0" />
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
var global_site_id = 1;
    $(document).ready(function(){
        $("#add_form").validate({
        	errorPlacement:function(error, element){
        		if(element.attr("name") == "seowords"){
            		error.appendTo( element.parent());
       	 		}else{
            		error.insertAfter(element)
        		}
			}
        });
        tinyMCE.execCommand('mceAddControl', true, 'seowords');
    });
    
   function initialiseInstance(editor)
{
	//Get the textarea
	var container = $('#' + editor.editorId);
	//Get the form submit buttons for the textarea
	$('input[name=button]').mouseover(function(e){
		container.val(editor.getContent());
	});
}
</script>
