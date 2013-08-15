<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑站点新闻</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base().url::current();?>">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                              	<tr>
                                    <th width="15%">分类<span class="required"> *</span>：</th>
                                    <td>
                                      <select name="classid" class="required">
                                            <option value="0"> 信息分类 </option>
                                            <?php foreach($news_categories as $key=>$value):?>
                                            <option value="<?php echo $value['id'];?>" <?php echo ($data['classid'] == $value['id'])?'selected':'';?>>
                                                    <?php for ($i = 1;$i < $value['level_depth'];$i++):?>
                                                &#166;&nbsp;
                                                    <?php endfor; ?>
                                                    <?php echo $value['category_name'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">标题<span class="required"> *</span>：</th>
                                    <td>
                                        <input type="text" size="10" name="title" class="text t400  _x_ipt required" value="<?php echo $data['title']; ?>" />
                                    </td>
                                </tr>
								<tr>
                                    <th width="15%">来源<span class="required"> *</span>：</th>
                                    <td>
                                        <input type="text" size="10" name="comefrom" class="text t400  _x_ipt required" value="<?php echo $data['comefrom']; ?>" />
                                    </td>
                                </tr>
								<tr>
                                    <th width="15%">排序<span class="required"> *</span>：</th>
                                    <td>
                                        <input type="text" size="10" name="order" class="text t400  _x_ipt" value="<?php echo $data['order']; ?>" />
                                    </td>
                                </tr>
								<tr>
                                    <th width="15%">最新推荐<span class="required"> *</span>：</th>
                                    <td><input name="zxtj" type="checkbox" id="zxtj" value="1" <?php if($data['zxtj']==1){ echo'checked="checked"';}?> />
                                    </td>
                                </tr>
								<tr>
                                    <th width="15%">首页推荐<span class="required"> *</span>：</th>
                                  <td>
                                      <input type="radio" name="indextj" value="1" <?php if($data['indextj']==1){ echo'checked="checked"';}?>/>
									  第一栏&nbsp;  &nbsp; 
									   <input type="radio" name="indextj" value="2" <?php if($data['indextj']==2){ echo'checked="checked"';}?>/>
									  第二栏&nbsp;  &nbsp; 
									  <input type="radio" name="indextj" value="3" <?php if($data['indextj']==3){ echo'checked="checked"';}?>/>
									  第三栏&nbsp;  &nbsp;
									  <input name="indextj" type="radio" value="0" <?php if($data['indextj']==4){ echo'checked="checked"';}?>/>
									  不推荐&nbsp;  &nbsp;									  
                                    </td>
                                </tr>
								<tr>
                                    <th width="15%">新闻页推荐<span class="required"> *</span>：</th>
                                    <td><input name="newstj" type="checkbox" id="newstj" value="1" <?php if($data['newstj']==1){ echo'checked="checked"';}?>/>
                                    </td>
                                </tr>
								<tr>
                                    <th width="15%">置顶<span class="required"> *</span>：</th>
                                    <td><input name="zd" type="checkbox" id="zd" value="1" <?php if($data['zd']==1){ echo'checked="checked"';}?>/>
                                    </td>
                                </tr>
								<tr>
                                    <th width="15%">新闻列表页右边推荐1<span class="required"> *</span>：</th>
                                    <td><input name="list1" type="checkbox" id="list1" value="1" <?php if($data['list1']==1){ echo'checked="checked"';}?>/>
                                    </td>
                                </tr><tr>
                                    <th width="15%">新闻列表页右边推荐2<span class="required"> *</span>：</th>
                                    <td><input name="list2" type="checkbox" id="list2" value="1" <?php if($data['list2']==1){ echo'checked="checked"';}?>/>
                                    </td>
                                </tr>
								<tr>
                                    <th width="15%">关键字<span class="required"> *</span>：</th>
                                    <td>
                                        <input type="text" size="10" name="key" class="text t400  _x_ipt required" value="<?php echo $data['key'];?>" />关键字请用“|”隔开
                                    </td>
                                </tr>
                                
                                <!--开奖信息 start-->
                                <tr>
                                    <th width="15%">彩种<span class="required"> </span>：</th>
                                    <td>
                                        <input type="text" size="50" name="type" class="text t400  _x_ipt " value="<?php echo $data['type']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">期号<span class="required"> </span>：</th>
                                    <td>
                                        <input type="text" size="50" name="issue" class="text t400  _x_ipt " value="<?php echo $data['issue']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">开奖号码<span class="required"> </span>：</th>
                                    <td>
                                        <input type="text" size="50" name="number" class="text t400  _x_ipt " value="<?php echo $data['number']; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">开奖简介<span class="required"> </span>：</th>
                                    <td>
                                        <input type="text" size="50" name="summary" class="text t400  _x_ipt " value="<?php echo $data['summary']; ?>" />
                                    </td>
                                </tr>
                                <!--开奖信息 end-->
                                
                                
                                <tr>
                                    <th>内容<span class="required"> *</span>：</th>
                                    <td class="d_line">
                                        <textarea id="content" name="content" cols="75" rows="20" class="text _x_ipt t400 required" type="textarea" maxth="255" ><?php echo $data['content']; ?></textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <th width="20%">
                                        上传Logo图片
                                    </th>
                                    <td colspan="2">
                                   		 <input type="text" id="newpic" name="newpic" class="text" value="<?php echo $data['newpic'];?>" size="50">
                                        <?php if(isset($data['newpic'])):?>
                                        <span id="del_div"<?php 
										if ($data['newpic']==="") {
											echo ' style="display:none"';
											}?>>
                                            <img id="newpics" src="<?php echo $data['newpic'];?>" height="50" alt=""/>
                                            <input type='hidden' id="newpic_h"  name="newpic_h" value="<?php echo $data['newpic'];?>" />
                                            [<a href="javascript:void(0);" id="del_newpic">删除logo</a>]
                                            <?php else:?>
                                            <img id="newpics" style="display:none;"/>
                                            <?php endif;?> 
                                        </span>                                       
                                        <a href="javascript:void(0);" id="btn_upload_logo">点击上传新Logo</a>
                                    </td>
                                </tr>  

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input name="submit" type="submit" class="ui-button" value=" 保存 " />
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

<div id='upload_content' style="display:none;"></div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
        //define config object
        var dialogOpts = {
            title: "上传新Logo",
            modal: true,
            autoOpen: false,
            height: 180,
            width: 450
        };
        $('#upload_content').dialog(dialogOpts);
        $('#btn_upload_logo').click(function (){
            $("#upload_content").html("loading...");
            $.ajax({
        		url: '<?php echo url::base();?>site/news/logo_upload_iframe',
                type: 'GET',
                dataType: 'json',
				
                error: function() {
					//alert("sadfsadf");					
                    //window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#upload_content").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#upload_content").dialog("open");
            return false;
        });
    });
</script>


<script type="text/javascript">
    $(document).ready(function(){
					
		var img=$('#newpic_h').val();
        $('#del_newpic').click(function (){
			
		   if(!confirm('真的要删除吗？删除后无法还原！')){      
			   return;      
		   } 			
			
            $.ajax({
        		url: '<?php echo url::base();?>site/news/del_newpic/<?php echo $data['id'];?>?img='+img,
                type: 'GET',
                dataType: 'json',
                error: function() {				
                    //window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
						document.getElementById('newpic').value='';
						document.getElementById('newpic_h').value='';	
						document.getElementById('del_div').style.display='none';																		
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            return false;
        });
    });
</script>


<script type="text/javascript">
var global_site_id = 1;
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
