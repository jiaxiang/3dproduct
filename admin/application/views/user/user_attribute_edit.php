<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑会员注册项</li>
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
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base().'user/user_attribute/post';?>" >
            <input type="hidden" name="id" value="<?php echo $user_attribute['id']?>"/>
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                  <th>注册项名称：</th>
                                  <td><input type="text" name="attribute_name" id="attribute_name" class="required text" value="<?php echo $user_attribute['attribute_name'];?>"/><span style="color:#F00;">*</span></td>
                                </tr>
                                <tr>
                                  <th>是否必填：</th>
                                  <td><input type="checkbox" name="attribute_required" id="attribute_required" value="1" <?php if($user_attribute['attribute_required']==1) echo 'checked="true"';?> class="sel"/></td>
                                </tr>
                                <tr>
                                  <th>类型：</th>
                                  <td>
                                    <select name="attribute_type" id="attribute_type" class="text">
                                      <?php foreach ($attribute_types as $key=>$attribute_type):?>
                                      <optgroup label="<?php isset($attribute_type_group[$key]) && print($attribute_type_group[$key]);?>">
                                       <?php foreach($attribute_type as $key1=>$value):?>
                                       <option value="<?php echo $key.'.'.$key1;?>" <?php if($user_attribute['attribute_type']==$key.'.'.$key1) echo 'selected';?>> <?php echo $value['name'];?> </option>
                                       <?php endforeach;?>
                                       </optgroup>
                                      <?php endforeach;?>
                                    </select>
                                  </td>
                                </tr>
                                <?php $type=substr($user_attribute['attribute_type'],0,strpos($user_attribute['attribute_type'],'.'));?>
                                <tr <?php if($type!='select') echo 'style="display:none;"';?>>
                                  <th>选项内容：</th>
                                  <td><input type="text " name="attribute_option[]" value="<?php echo $user_attribute['attribute_option'][0];?>" class="text <?php if($type=='select') echo 'required';?>"/> <span name="del_option" style="color:#F00;">X</span></td>
                                </tr>
                                <?php 
									$n = count($user_attribute['attribute_option']);
									for($i=1;$i<$n;$i++):
								?>
                                <tr <?php if($type!='select') echo 'style="display:none;"';?>>
                                  <th> </th>
                                  <td><input type="text" name="attribute_option[]" value="<?php echo $user_attribute['attribute_option'][$i];?>" class="<?php if($type=='select') echo 'required';?>"/> <span name="del_option" style="color:#F00;">X</span></td>
                                </tr>
                                <?php endfor;?>
                                <tr <?php if($type!='select') echo 'style="display:none;"';?>>
                                  <th> </th>
                                  <td >
                                    <span id="attribute_option_add" style="display:block; float:left; width:50px; height:20px; padding-left:20px; background:url(/images/new_bg_ico.gif) no-repeat ;background-position:0 -198px;"> </span>
                                    <span id="attribute_option_del" style="display:block; float:left; width:50px; height:20px; padding-left:20px;background:url(/images/new_bg_ico.gif) no-repeat ;background-position:0 -222px;"> </span>
                                  </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input name="submit" type="submit" class="ui-button" value=" 保存 "><input name="cancel" type="button" class="ui-button" value="取消">
                </div>
            </form>
            <!--**edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<div id='upload_content' style="display:none;"></div>
<script type="text/javascript">
$(document).ready(function(){
    var error_required = false;
    var error_length_name = false;
    var error_length_option = false;
    $(":button[name='cancel']").unbind().bind('click',function(){
        location.href="/user/user_attribute";
    });

    //选项控制
    $("#attribute_option_add").click(function(){
        $(this).parent().parent().before('<tr><th> </th><td><input type="text" name="attribute_option[]" class="required text" /> <span name="del_option" style="color:#F00;">X</span></td></tr>');
    });
    $("#attribute_option_del").click(function(){
        var attribute_options = $("input[name='attribute_option[]']");
        if(attribute_options.length == 1)
        {
            alert("至少要有一个选项");
        }else{
            $(this).parent().parent().prev().remove();
        }
    });
    $("span[name='del_option']").unbind().live('click',function(){
        var attribute_options = $("input[name='attribute_option[]']");
        if(attribute_options.length == 1)
        {
            alert("至少要有一个选项");
        }else{
            if($(this).parent().prev().html() == "选项内容："){
                $(this).parent().parent().next().children("th").html("选项内容：");
            }
            $(this).parent().parent().remove();
        }
    });

    //选项显示控制
    $("#attribute_type").unbind().bind('click keyup',function(){
        var val = $(this).val();
        var option_group = val.substring(0,val.indexOf('.'));
        if(option_group == 'select'){
            $(".division table tr:gt(2)").show();
            $("input[name='attribute_option[]']").addClass('required');
        }else{
        	$(".division table tr:gt(2)").hide();
        	error_length_option = false;
        	$("input[name='attribute_option[]']").removeClass('required');
        }
    });

    //验证
    $("#add_form").bind('submit',function(){
    	error_required=false;
        $(".required").each(function(){
            var val=$(this).val();
            if(val==''){
                if(error_required==false){
                	error_required = true;
                }
                if(!$(this).next().attr('generated')){
                    $(this).after('<label class="error" generated="true">本项必填！</label>');
                }else{
					$(this).next().html('本项必填！');	
				}
            }
        });
        if(error_required || error_length_option || error_length_name) return false;
    });
    $(".required").unbind().live('keyup',function(){
        if($(this).val()!=''){
            if($(this).next().attr('generated')){
                $(this).next().remove();
            }
        }
    });
	$("#attribute_name,input[name='attribute_option[]']").live('keyup',function(){
		if($(this).attr('name')=='attribute_name'){
			var val = $(this).val();
			if(val.length>256){
				error_length_name = true;
				if(!$(this).next().attr('generated')){
                    $(this).after('<label class="error" generated="true">不能超过256个字符！</label>');
                }else{
					$(this).next().html('不能超过256个字符！');	
				}
			}else{
				error_length_name = false;	
			}
		}
		if($(this).attr('name')=='attribute_option[]'){
			var val='';
			$("input[name='attribute_option[]']").each(function(){
				val += $(this).val()+',';													
			});
			if(val.length>256){
				error_length_option = true;
				if(!$(this).next().attr('generated')){
                    $(this).after('<label class="error" generated="true">选项内容总长不能超过256个字符！</label>');
                }else{
					$(this).next().html('选项内容总长不能超过256个字符！');	
				}
			}else{
				error_length_option = false;	
			}
		}
	});
    

});



</script>