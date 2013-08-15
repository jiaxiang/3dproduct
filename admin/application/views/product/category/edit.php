<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
$data = $return_data['data'];
$category_list = $return_data['category_list'];
$classify_list = $return_data['classify_list'];
?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑分类</li>
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
                <form enctype="multipart/form-data" id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>product/category/<?php echo $return_data['action'];?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th>前台是否显示：</th>
                                    <td><input type="radio" id="is_show" name="is_show" value="1" <?php if(!isset($data['is_show'])||$data['is_show']==1) {?>checked<?php }?>> 显示
                                        <input type="radio" id="is_show" name="is_show" value="0" <?php if(isset($data['is_show'])&&$data['is_show']==0) {?>checked<?php }?>> 不显示
									</td>
                                </tr>
                                <tr>
                                    <th>上一级分类：</th>
                                    <td><input type="hidden" name="oldpid" value="<?php echo isset($data['pid'])?$data['pid']:'';?>" />
                                        <select name="pid" id="pid" class="required">
                                            <option value="0">----</option>
                                            <?php echo $category_list;?>  
                                        </select> &nbsp;&nbsp;<span id='category_change_tips'></span>
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
                                    <td><input type="hidden" id="category_id" name="id" value="<?php echo isset($data['id'])?$data['id']:'';?>" />
                                        <input type="text" style="" size="10" name="title" class="text t400  _x_ipt required" value="<?php echo isset($data['title'])?$data['title']:'';?>" maxlength="100"/></td>
                                </tr>
                                <tr>
                                    <th>别名： </th>
                                    <td><input type="text" style="" size="10" name="title_manage" class="text t400  _x_ipt" value="<?php echo isset($data['title_manage'])?$data['title_manage']:'';?>" maxlength="100"/></td>
                                </tr>
                                <tr>
                                    <th> 分类URL:</th>
                                    <td><input type="text" style="" size="20" id="uri_name" name="uri_name" class="text _x_ipt" value="<?php echo isset($data['uri_name'])?$data['uri_name']:'';?>" maxlength="100"/>
                                    <span class="brief-input-state notice_inline">前台显示URL</span></td>
                                </tr>
                                <tr>
                                    <th>分类图片：</th>
                                    <td>
                                    <input type="hidden" name="pic_attach_id" value="<?php echo isset($data['pic_attach_id'])?$data['pic_attach_id']:'';?>">
                                    <img id="category_img" src="<?php echo isset($data['pic_url'])?$data['pic_url']:'';?>" width="120" height="120" style="vertical-align:middle"> 
                                    <input class="ui-button select_pic" name="select_pic" type="button" value="<?php echo !empty($data['pic_attach_id']) ? '重新选择' : '选择图片';?>" />
                                   <?php if(!empty($data['pic_attach_id'])) :?>
                                    <input class="ui-button del_pic" name="del_pic" type="button" value="删除图片" />
                                   <?php endif;?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Meta Title(页面标题): </th>
                                    <td><input type="text" style="" size="10" name="meta_title" class="text t400  _x_ipt" value="<?php echo isset($data['meta_title'])?$data['meta_title']:'';?>"/></td>
                                </tr>
                                <tr>
                                    <th>Meta Keywords(页面关键词): </th>
                                    <td><input type="text" style="" size="10" name="meta_keywords" class="text t400  _x_ipt" value="<?php echo isset($data['meta_keywords'])?$data['meta_keywords']:'';?>"/></td>
                                </tr>
                                <tr>
                                    <th>Meta Descriptions(页面描述):</th>
                                    <td><textarea name="meta_description" cols="75" rows="5" class="text _x_ipt t400" type="textarea" maxlength="255" ><?php echo isset($data['meta_description'])?$data['meta_description']:'';?></textarea>
                                        <span class="brief-input-state notice_inline">简短的商品介绍，请不要超过255字节。</span></td>
                                </tr>
                                <tr>
                                    <th>前台说明：</th>
                                    <td><textarea id="description" name="description" cols="75" rows="5" class="text _x_ipt t400" type="textarea" maxlength="1024" ><?php echo isset($data['description'])?$data['description']:'';?></textarea>
                                        <span class="brief-input-state notice_inline">简短的商品介绍，请不要超过1024字节。</span></td>
                                </tr>
                                <tr>
                                    <th>后台说明：</th>
                                    <td><textarea name="memo" cols="75" rows="5" class="text _x_ipt t400" type="textarea" maxlength="255" ><?php echo isset($data['memo'])?$data['memo']:'';?></textarea>
                                        <span class="brief-input-state notice_inline">简短的商品介绍，请不要超过255字节。</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <center><input id="dosubmit" name="dosubmit" type="button" class="ui-button" value="保存" /></center>
                </form>
            </div>
            <!--**category add end**-->
        </div>
    </div>
</div>
<div id="dialog" style="display:none">
    <iframe frameborder="no" style="border:0px;width:100%;height:100%;" src="" scrolling="auto" id="ifr"></iframe>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/init_tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/message.js"></script>
<script type="text/javascript">
var parent_is_show = '<?php echo isset($parent_is_show)?$parent_is_show:'';?>';
var global_site_id = <?php 
			if(isset($data['site_id']))
			{
				echo $data['site_id'];
			} else {
				echo site::id();
			}
	?>;
    $(function() {
		if(!$('input[name="pic_attach_id"]').val())$('#category_img').hide();
            
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
		$("input.del_pic").bind('click',function(){
			$('input[name="pic_attach_id"]').val(0);
			$('#category_img').hide();
	    });
    	
    	var validator = $("#add_form").validate({
    		errorPlacement:function(error, element){
            	if(element.attr("name") == "description"){
                	error.appendTo( element.parent());
           	 	}else{
                	error.insertAfter(element)
            	}
    		},
        	rules: { 
			    uri_name : {
				    //remote: '/product/category/check_exist_uri_name?category_id='+$('#category_id').val()
			    }
		    },
	        messages:{
		    	uri_name : {
		    	    remote: 'URL已经存在'
		        }
		    }
        });

        $('#dosubmit').unbind().bind('click', function(e){
            var list_form = $('#add_form');
            var value = $("input[@name='is_show']:checked").val();
            var pid = $("#pid").val();
            if(value == 1 && pid != 0 && parent_is_show == false)
            {
            	showMessage('操作失败', '此分类的上级分类在前台不显示，则此分类不能显示');
            	return false;
            }else if(value == 0 && <?php echo isset($has_child)?$has_child:'""';?> > 0 && parent_is_show == true){
				confirm('此分类含有子分类，此操作会使子分类在前台也不显示，确认这样处理吗?',function(){
					list_form.submit();
		        });
			}else if(value == 0 && parent_is_show == false){
				confirm('此分类上级分类在前台不显示，此操作会使此分类以及含有的子分类都在前台不显示，确认这样处理吗?',function(){
					list_form.submit();
		        });
			} 
			else{
				list_form.submit();
			}
        });
    	
        $("#pid").unbind().bind('change keyup',function(e){
            /* get current event stat */
            cur_disstat = $(this).attr('disabled');
            if(!cur_disstat){
                /* disable controls */
                $(this).attr('disabled',true);
                $("#category_change_tips").text('loading...');

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
                            if(retdat['content']['is_show'] == 1){
                            	parent_is_show = true;
                            	$('input[name="is_show"]').val(1);      
                            }else{
                            	parent_is_show = false;
                            	$('input[name="is_show"]').val(0);                            
                            }
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

