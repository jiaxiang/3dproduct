<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
$data = $return_data['data'];
?>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑规格</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <form id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>product/attribute/put">
                        <div class="division out_box">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width=200>* 规格名称： </th>
                                        <td><input name="id" id="id" type="hidden" value="<?php echo $data?$data['id']:''?>">
                                            <input id="name" name="name" type="input" class="text required" value="<?php echo $data?$data['name']:''?>" maxlength=100 size="50"></td>
                                    </tr>
                                    <tr>
                                        <th> 规格后台名： </th>
                                        <td><input id="alias" name="alias" type="input" class="text" value="<?php echo $data?$data['alias']:''?>" maxlength=255 size="50"> 用 | 分割</td>
                                    </tr>
                                    <tr>
                                        <th> 规格备注： </th>
                                        <td><input id="memo" name="memo" type="input" class="text"  value="<?php echo $data?$data['memo']:''?>" maxlength=255 size="50"></td>
                                    </tr>
                                    <!-- tr>
                                        <th> 规格用途： </th>
                                        <td><input name="apply" type="radio" value="0" <?php if(!isset($data['apply']) || $data['apply']==0)echo 'checked';?>> 后台设置 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input name="apply" type="radio" value="1" <?php if(isset($data['apply']) && $data['apply']==1)echo 'checked';?>> 前台用户设置 </td>
                                    </tr -->
                                    <tr>
                                        <th> 显示类型： </th>
                                        <td>
                                            <input name="type" type="radio" value="0" <?php if(!isset($data['type']) || $data['type']==0)echo 'checked';?>>选择项 &nbsp;&nbsp;&nbsp;&nbsp;
                                            <input name="type" type="radio" value="1" <?php if(isset($data['type']) && $data['type']==1)echo 'checked';?>>输入项
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <div id='attr_values'>                    
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
	                		<tr>
	                            <td><input name="addoptions" id="addoptions" type="button" class="ui-button" value="新加规格值" /></td>
	                        </tr>
	                	    </table>
	                	    <table cellspacing="0" cellpadding="0" border="0" width="100%" id="options">
                               <tr>
                                  <td colspan=5>选项显示方式：<input name="display" type="radio" value="text" <?php if(!isset($data['display']) || $data['display']=='text')echo 'checked';?>> 文字 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                      <input name="display" type="radio" value="image" <?php if(isset($data['display']) && $data['display']=='image')echo 'checked';?>> 图片</td>
                               </tr>
                               <tr>
                                  <th width="300" style="text-align:center;"><b>* 规格值名称</b></th>
                                  <th width="300" style="text-align:center;"><b>规格值后台名</b></th>
                                  <th width="150" style="text-align:center;"><b>* 排序</b></th>
                                  <th width="150" style="text-align:center;display:none" class="pic"><b>图片</b></th>
                                  <th style="text-align:center;"><b>操作</b></th>
                               </tr>  
                               <?php if(!empty($data['options']) && is_array($data['options'])){
                                    foreach($data['options'] as $id => $option){
                               ?>
                                    <tr class="option_str">
                                        <td align="center" width="300" class="d_line">
                                        <input name="option_name[<?php echo $option['id'];?>]" type="input" class="option_name_old text required nameRepeat"  value="<?php echo $option['name'];?>" size="50">
                                        </td>
                                        <td align="center" width="300" class="d_line"><input name="option_alias[<?php echo $option['id'];?>]" type="input" class="option_alias_old text aliasRepeat"  value="<?php echo $option['alias'];?>" size="50"></td>
                                        <td align="center" width="150" class="d_line"><input name="option_order[<?php echo $option['id'];?>]" type="input" class="option_order_old text digits orderRepeat"  value="<?php echo $option['order'];?>" size="20" maxlength=10></td>
                                        <td style="<?php echo ($data['display'] == 'image') ? '' : 'display:none'?>" class="pic" align="center" width="150">
                                        <input type="hidden" name="option_image[<?php echo $option['id'];?>]" value="<?php echo $option['image'];?>">
                                        <img src="<?php echo $option['picurl'];?>" width="30" height="30" style="vertical-align:middle"> 
                                        <input class="reselect_pic ui-button" name="reselect_pic" type="button" class="ui-button" value="<?php if(!empty($option['meta_struct']['image'][0])) echo '重新选择';else echo '选择图片';?>" /></td>
                                        <td align="center">
                                        <input name="option_id_old[<?php echo $option['id'];?>]" type="hidden" value="<?php echo $option['id'];?>">
                                        <input name="option_id" class="option_id" type="hidden" value="<?php echo $option['id'];?>">
                                        <a href="javascript:void(0)" class="delete">删除</a>
                                        </td>
                                    </tr>
                               <?php 
                                    }
                               }
                               else
                               {
                               ?>
                               <tr class="option_str">
                                  <td align="center" width="300" class="d_line">
                                  <input name="option_name_0" type="input" class="option_name text required nameRepeat"  value="" size="50"></td>
                                  <td align="center" width="300" class="d_line">
                                  <input name="option_alias_0" type="input" class="option_alias text aliasRepeat"  value="" size="50"></td>
                                  <td align="center" width="150" class="d_line">
                                  <input name="option_order_0" type="input" class="option_order text required digits orderRepeat"  value="0" size="20"></td>
                                  <td style="display:none" class="pic" align="center" width="150">
                                   <input type="hidden" name="option_image[]">
                                   <img src="/att/no.gif" width="30" height="30" style="vertical-align:middle"> 
                                   <input num="0" class="ui-button select_pic" name="select_pic" type="button" value="选择图片" /></td>
                                  <td align="center"><a href="javascript:void(0)" class="delete">删除</a></td>
                               </tr> 
                               <?php } ?>
                          </table>
                        </div>
                    </div>
                 <div class="list_save">
                 	<input id="dosubmit" name="dosubmit" type="submit" class="ui-button" value=" 保 存 " />
                 </div>
            </form>
        </div>
        <!--**productlist edit end**-->
    </div>
</div>
<div id="dialog" style="display:none;overflow:hidden;">
	<iframe frameborder="no" style="border:0px;width:100%;height:100%;" src="" scrolling="auto" id="ifr"></iframe>
</div>
<div id="message" style="display:none;"><p id="message_content"></p></div>
<script type="text/javascript" src="<?php echo url::base();?>js/message.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    var type = "<?php echo isset($data['type'])?$data['type']:0;?>";

	//上传图片dialog设置
    var dialogOpts = {
        title: "图片上传",
        modal: true,
        autoOpen: false,
        height: 260,
        width: 500
    };

	$('input[name="type"]').click(function(){
        type = this.value;
		if(this.value == 1){
			$('#attr_values').hide();
      	    var option = $("#options").find("tr.option_str");
      	    if(option.attr('class')){
                $('a.delete_live').click();
                confirm('请确认是否删除选项值', function(){
                    $('a.delete').click();
                });
      	    }
		}else{
			$('#attr_values').show();
		}
	});
          
	/*
	jQuery.validator.addMethod("orderRepeat", function (value, element){
		    var compare = $('.option_order').not($(element));
		    var lable = true;
  	 		compare.each(function(){
  	             if(value === $(this).val()){
   	            	lable = false;
   	  	  	        return false
  	             }
  	             
  	        });
	    return this.optional(element) || lable;       
	} ,  "排序不能重复" ); 
    */
	jQuery.validator.addMethod("nameRepeat", function (value, element){
	    var compare = $('.option_name').not($(element));
	    var lable = true;
	 		compare.each(function(){
	             if(value === $(this).val()){
	            	lable = false;
	  	  	        return false
	             }
	             
	        });
        return this.optional(element) || lable;       
    } ,  "规格项名称不能重复" );

	jQuery.validator.addMethod("aliasRepeat", function (value, element){
	    var compare = $('.option_alias').not($(element));
	    var lable = true;
	 		compare.each(function(){
	             if(value === $(this).val()){
	            	lable = false;
	  	  	        return false
	             }
	             
	        });
        return this.optional(element) || lable;       
    } ,  "规格项管理名称不能重复" );
    
    $("#add_form").validate({
        submitHandler: function(form){        
 	       $(".option_name").attr("name","option_name[]");
  	       $(".option_alias").attr("name","option_alias[]");
  	       $(".option_order").attr("name","option_order[]");
           if(type==0){
      	       var option = $("#options").find("tr.option_str");
      	       if(!option.attr('class')){
        	       showMessage('操作失败','至少有一个规格项');
      	  	       return false;
      	       }
           }
 	       form.submit();
 	    }
    });

	var image;
	var show_image = "<?php echo ((isset($data['display'])?$data['display']:'text') == 'image') ? '' : 'display:none';?>";
	$('input[name="display"]').click(function(){
		if(this.value == 'text'){
			$('.pic').hide();
			show_image = 'display:none';
		}else if(this.value == 'image'){
			$('.pic').show();
			show_image = '';
		}
	});
    
	//增加规格项
	var number = 1, old_number;
	$('.option_order_old').each(function(){
		if($(this).val() >= number){
			number = old_number = parseInt($(this).val())+1;
		}
    });
	$('#addoptions').live('click', function(){
		image = '<td style="'+show_image+'" class="pic" align="center" width="150"><input type="hidden" name="option_image[]"><img src="/att/no.gif" width="30" height="30" style="vertical-align:middle"> <input num="'+number+'" class="ui-button select_pic" name="select_pic" type="button" value="选择图片" /></td>';
		$('#options').append('<tr class="option_str"><td align="center" width="300" class="d_line"><input name="option_name_'+number+'" type="input" class="option_name text required nameRepeat"  value="" size="50"></td><td align="center" width="300" class="d_line"><input name="option_alias_'+number+'" type="input" class="option_alias text aliasRepeat"  value="" size="50"></td><td align="center" width="150" class="d_line"><input name="option_order_'+number+'" type="input" class="option_order text required digits orderRepeat"  value="'+number+'" size="20"></td>'+image+'<td align="center"><a href="javascript:void(0)" class="delete_live">删除</a></td></tr>');
		//删除规格项
	    $('a.delete_live').click(function(){
            $(this).parent('td').parent('tr').remove();
            number--;
            if(number<=0)number=0;
            if(number<old_number)number=old_number;
        });
		number++;
		//上传图片
	    $("#dialog").dialog(dialogOpts);
		$("input.select_pic").bind('click',function(){
			$('#dialog').find('iframe').attr('src', '/product/attribute/uploadform');
			$('#dialog').find('iframe').attr('number', $(this).attr('num'));
	        $('#dialog').dialog("open");
	    });
		/* 按钮风格 */
        $(".ui-button,.ui-button-small").button();
	});
	//删除规格项
    $('a.delete').click(function(){
         var obj = $(this);
         var option_id = $(this).prev('.option_id').val();
             if(!option_id || type==1){
                obj.parent('td').parent('tr').remove();
                return false;
             }
   	     confirm('请确认要删除此项?',function(){
       	   	 ajax_block.open();
             $.ajax({
      			url: '/product/attribute/option_relation_data?option_id=' + option_id ,
      			dataType: 'json',
      			success: function(retdat, status) {
        			ajax_block.close();
      				if (retdat['status'] == 1 && retdat['code'] == 200) {
      					obj.parent('td').parent('tr').remove();
                        old_number--;
                        if(old_number<0)old_number=0;showMessage('操作失败',retdat['msg']);
      				} else {
      					showMessage('操作失败',retdat['msg']);
      				}
      			},
      			error: function() {
      	  			ajax_block.close();
      				showMessage('操作失败','请求错误,请稍候重试');
      			}
      		});
   	    });
	});
    if(type==1){
        $('#attr_values').hide();
        $('a.delete').click();
    }
    
	//图片上传对话框
    $("#dialog").dialog(dialogOpts);
	$("input.select_pic").bind('click',function(){
		$('#dialog').find('iframe').attr('src', '/product/attribute/uploadform');
		$('#dialog').find('iframe').attr('number', $(this).attr('num'));
        $('#dialog').dialog("open");
    });
    
    //图片重新上传对话框
	$('input.reselect_pic').bind('click',function(){
		option_id = $(this).parent('td').parent('tr').find('.option_id').val();
		$('#dialog').find('iframe').attr('src', '/product/attribute/uploadform?option_id='+option_id);
        $('#dialog').dialog("open");
    });
});
</script>