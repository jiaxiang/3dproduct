<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
$category_list = $return_data['category_list'];
$classify_list = $return_data['classify_list'];
?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加分类</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**category add start**-->
            <div class="edit_area">
                <form enctype="multipart/form-data" id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>product/category/put">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th>上一级分类：</th>
                                    <td><select name="pid" id="pid" class="required">
                                            <option value="0">----</option>
                                            <?php echo $category_list;?>  
                                        </select>
                                        <div id="category_change_tips" class="valierror"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>所属类型：</th>
                                    <td><select name="classify_id" id="classify_id" class="required">
                                            <option value="0">----</option>
                                            <?php echo $classify_list;?>  
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>* 分类名： </th>
                                    <td><input type="text" style="" size="10" id="title" name="title" class="text t400  _x_ipt required" value="" maxlength="100" />
                                        <span class="brief-input-state notice_inline">前台显示英文名称</span></td>
                                </tr>
                                <tr>
                                    <th>* 管理名称： </th>
                                    <td><input type="text" style="" size="10" name="title_manage" class="text t400  _x_ipt required" value="" maxlength="100"/>
                                    <span class="brief-input-state notice_inline">后台管理中文名称</span></td>
                                </tr>
                                <tr>
                                    <th>分类图片：</th>
                                    <td><input type="hidden" name="pic_attach_id"><img src="/attachment/view/0_120*120.jpg" width="120" height="120" style="vertical-align:middle" id="category_img"> <input class="ui-button select_pic" name="select_pic" type="button" value="选择图片" /></td>
                                </tr>
                                <tr>
                                    <th>Meta Title(页面标题): </th>
                                    <td><input type="text" style="" size="10" name="meta_title" class="text t400  _x_ipt" /></td>
                                </tr>
                                <tr>
                                    <th>Meta Keywords(页面关键词): </th>
                                    <td><input type="text" style="" size="10" name="meta_keywords" class="text t400  _x_ipt" /></td>
                                </tr>
                                <tr>
                                    <th>Meta Descriptions(页面描述):</th>
                                    <td><textarea name="meta_description" cols="75" rows="5" class="text _x_ipt t400" type="textarea" maxlength="255" ></textarea>
                                        <span class="brief-input-state notice_inline">简短的商品介绍，请不要超过255字节。</span></td>
                                </tr>
                                <tr>
                                    <th>前台说明：</th>
                                    <td><textarea id="description" name="description" cols="75" rows="5" class="text _x_ipt t400" type="textarea" maxlength="1024"></textarea>
                                        <span class="brief-input-state notice_inline">简短的商品介绍，请不要超过1024字节。</span></td>
                                </tr>
                                <tr>
                                    <th>后台说明：</th>
                                    <td><textarea name="memo" cols="75" rows="5" class="text _x_ipt t400" type="textarea" maxlength="255" ></textarea>
                                        <span class="brief-input-state notice_inline">简短的商品介绍，请不要超过255字节。</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="btn_eidt">
                        <table width="445" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <th width="182"></th>
                                <td><input name="dosubmit" type="submit" class="ui-button" value="添加" /></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
            <!--**category add end**-->
        </div>
    </div>
</div>
<div id="dialog" style="display:none">
	<iframe style="border:0px;width:100%;height:98%;" src="" scrolling="auto" id="ifr"></iframe>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/init_tiny_mce.js"></script>
<script type="text/javascript">
    $(function() {
        var validator = $("#add_form").validate({
        	errorPlacement:function(error, element){
	            if(element.attr("name") == "description"){
	                error.appendTo( element.parent());
	            }else{
	                error.insertAfter(element)
	            }
        	}
        });
        
        //上传图片dialog设置
        var dialogOpts = {
            title: "图片上传",
            modal: true,
            autoOpen: false,
            height: 260,
            width: 500
        };
        //上传图片
	    $("#dialog").dialog(dialogOpts);
		$("input.select_pic").bind('click',function(){
			$('#dialog').find('iframe').attr('src', '/product/category/uploadform');
	        $('#dialog').dialog("open");
	    });
	    
        $("#pid").unbind().bind('change keyup',function(e){
            /* get current event stat */
            cur_disstat = $(this).attr('disabled');
            if(!cur_disstat){
                /* disable controls */
                $("#category_change_tips").text('loading...');
                $(this).attr('disabled',true);

                /* prepare arguments */
                reqid = $(":selected",this).attr('value');
                if(reqid == 0){
                	$("#category_change_tips").empty();
               	    $("select#classify_id").val(0);
                  	$("#pid").attr('disabled',false);
               	    return false;
                }else{
                    urlbase = '/product/category/get_category_data?category_id='+reqid;
                }
                /* ajax load data */
                xhrobj = $.ajax({url:urlbase,
                    //cache:false,
                    dataType:'json',
                    error:function(){
                        /* reset layout */
                        $("#pid").attr('disabled',false);
                        $("#category_change_tips").html('request http error, please try again later');
                        window.setTimeout(function(){
                            /* clear tips */
                            $("#category_change_tips").empty();
                        },2000);
                    },
                    //timeout:1000,
                    success:function(retdat,status){
                        /* app logic ok */
                        if(retdat['status'] == 1 && retdat['code'] == 200){
                            /* reset layout */
                            $("#category_change_tips").empty();
                            /* render layout */
                            $("select#classify_id").val(retdat['content']['classify_id']);
                            $("#pid").attr('disabled',false);
                            /* rebind event */
                        }else{
                            /* render layout */
                            $("#category_change_tips").html('request error with message:'+retdat['msg']);
                            $("#pid").attr('disabled',false);
                        }
                    }
                });
            }else{
                /* deal with the exception */
                $("#category_change_tips").html('request failed, please try to <a href="javascript:document.location.reload();">reload</a> the page.');
            }

        });

        tinyMCE.execCommand('mceAddControl', true, 'description');
    });
  function initialiseInstance(editor)
{
	//Get the textarea
	var container = $('#' + editor.editorId);
	//Get the form submit buttons for the textarea
	$('input[name=dosubmit]').mouseover(function(e){
		container.val(editor.getContent());
	});
}
</script>

