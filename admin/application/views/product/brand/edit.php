<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
$data = $return_data['data'];//echo "<pre>";print_r($data);die();
?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑品牌</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <form id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>product/brand/<?php echo $return_data['action'];?>">
                        <div class="out_box">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th>品牌排序： </th>
                                        <td><input name="id" id="id" type="hidden" value="<?php echo isset($data['id'])?$data['id']:''?>">
                                            <input id="order" name="order" type="input" class="text"  value="<?php echo $data?$data['order']:0?>" maxlength=5 size="5"></td>
                                    </tr>
                                    <tr>
                                        <th>* 品牌名称： </th>
                                        <td><input id="name" name="name" type="input" class="text required"  value="<?php echo isset($data['name'])?$data['name']:''?>" maxlength=50 size="50"></td>
                                    </tr>
                                    <tr>
                                        <th>品牌别名： </th>
                                        <td><input id="alias" name="alias" type="input" class="text"  value="<?php echo isset($data['alias'])?$data['alias']:''?>" maxlength=50 size="50"></td>
                                    </tr>
                                    <tr>
                                        <th>品牌站点URL： </th>
                                        <td><input id="url" name="url" type="input" class="text"  value="<?php echo isset($data['url'])?$data['url']:''?>" maxlength=255 size="100"></td>
                                    </tr>
                                    <tr>
                                        <th>品牌logo： </th>
                                        <td><input id="logo" name="logo" type="input" class="text"  value="<?php echo isset($data['logo'])?$data['logo']:''?>" maxlength=255 size="100"></td>
                                    </tr>
                                    <tr>
                                        <th>页面标题(TITLE)： </th>
                                        <td><input id="page_title" name="page_title" type="input" class="text"  value="<?php echo isset($data['page_title'])?$data['page_title']:''?>" maxlength=255 size="100"></td>
                                    </tr>
                                    <tr>
                                        <th>页面关键词(META_KEYWORDS)： </th>
                                        <td><input id="meta_keywords" name="meta_keywords" type="input" class="text"  value="<?php echo isset($data['meta_keywords'])?$data['meta_keywords']:''?>" maxlength=255 size="100"></td>
                                    </tr>
                                    <tr>
                                        <th>页面描述(META_DESCRIPTION)： </th>
                                        <td><input id="meta_description" name="meta_description" type="input" class="text"  value="<?php echo isset($data['meta_description'])?$data['meta_description']:''?>" maxlength=255 size="100"></td>
                                    </tr>
                                    <tr>
                                        <th>详细说明： </th>
                                        <td><textarea name="detial" id="detial" cols="75" rows="20" class="text _x_ipt t400" type="textarea" maxth="255" ><?php echo isset($data['detial'])?$data['detial']:''?></textarea></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                 <div class="list_save">
                 	<input name="dosubmit" type="submit" class="ui-button" value=" 保 存 " />
                 	<input name="back" type="button" class="ui-button" value=" 返 回 " onclick="history.back();" />
                 </div>
            </div>
            </form>
        </div>
        <!--**productlist edit end**-->
    </div>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/init_tiny_mce.js"></script>
<script type="text/javascript">
function initialiseInstance(editor)
{
	//Get the textarea
	var container = $('#' + editor.editorId);
	//Get the form submit buttons for the textarea
	$('input[name=submit]').mouseover(function(e){
		container.val(editor.getContent());
	});
}    
    $(document).ready(function(){
        $("#add_form").validate({
        	errorPlacement:function(error, element){
	            if(element.attr("name") == "detial"){
	                //alert(error);
	                error.appendTo( element.parent());
	            }else{
	                error.insertAfter(element)
	            }
		    }/*,            
        	rules: {
			    name : {
				    remote: '/product/brand/check_name?brand_id='+$('#id').val()
			    }
		    },
	        messages:{
		    	name : {
		    	    remote: '品牌名称已经存在'
		        }
		    }*/            
        });
		tinyMCE.execCommand('mceAddControl', true, 'detial');
    });  
</script>