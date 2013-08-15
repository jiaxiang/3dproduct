<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
$data = isset($return_data['data'])?$return_data['data']:array();
$filter_list = $return_data['filter_list'];
$category_list = $return_data['category_list'];
?>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑虚拟分类</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <form id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>product/aliasfilter/<?php echo $return_data['action'];?>">
                        <div class="out_box">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                    <th width="10%">* 上一级虚拟分类：</th>
                                    <td><input type="hidden" name="oldpid" value="<?php echo isset($data['pid'])?$data['pid']:'';?>" />
                                        <select name="pid" id="pid" class="required" style="width:300px;">
                                            <option value="0">----</option>
                                            <?php echo $filter_list;?>  
                                        </select>
                                    </td>
                                   </tr>
                                    <tr>
                                        <th>* 虚拟分类名称： </th>
                                        <td><input type="hidden" id="filter_id" name="id" value="<?php echo isset($data['id'])?$data['id']:'';?>"/>
                                        <input id="title" name="title" type="input" class="text required"  value="<?php echo isset($data['title'])?$data['title']:'';?>" size="50" maxlength="50"></td>
                                    </tr>
                                    <tr>
                                        <th>* 前台链接： </th>
                                        <td><input id="uri_name" name="uri_name" type="input" class="text required"  value="<?php echo isset($data['uri_name'])?$data['uri_name']:'';?>" size="50" maxlength="50"></td>
                                    </tr>
                                </tbody>
                            </table>
                         <table cellspacing="0" cellpadding="0" border="0" width="100%">
                          <tr>
                            <td>过滤条件设置：</td>
                          </tr>
                         </table>
                         <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                     <tr>
                                        <th width="10%">关键字：</th>
                                        <td>
                                          <input id="keywords" name="keywords" type="input" class="text"  value="<?php echo isset($data['filter_struct']['keywords'])?$data['filter_struct']['keywords']:''; ?>" size="30">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="10%">价格区间：</th>
                                        <td>
                                          $<input id="pricefrom" name="pricefrom" type="input" class="text"  value="<?php echo isset($data['filter_struct']['pricefrom'])?$data['filter_struct']['pricefrom']:'';?>" size="10">
                                          - $<input id="priceto" name="priceto" type="input" class="text"  value="<?php echo isset($data['filter_struct']['priceto'])?$data['filter_struct']['priceto']:'';?>" size="10">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>分类：</th>
                                        <td><select name="category_id" id="category_id" class="required">
                                            <option value="0">--全部--</option>
                                            <?php echo $category_list;?>  
                                        </select>
                                        <div id="category_change_tips" class="valierror"></div>
                                        </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                   <table cellspacing="0" cellpadding="0" border="0" width="100%" id="relations">
                                   <?php if(!empty($data['classify_name'])){?>  
                                   <tr>
                                   <th width="10%">类型：</th>
                                   <td><?php echo $data['classify_name'];?></td></tr>
                                   <?php }?>
                                   <?php if(!empty($data['brand_list'])){?>
                                   <tr><th width="10%">品牌：</th>
                                   <td>
                                   <input type="checkbox" value="brand[]" class="checkall"> 全部 
                                       <?php foreach ($data['brand_list'] as $val){?>
                                       <input type="checkbox" name="brand[]" value="<?php echo $val['id'];?>" <?php if(isset($data['filter_struct']['brands']) && in_array($val['id'],$data['filter_struct']['brands'])) echo "checked"?>> <?php echo $val['name'];?>  
                                       <?php }?>
                                   </td></tr>
                                   <?php }?>
                                   <?php if(!empty($data['attribute_list'])){?>
                                   <tr><th width="10%">规格：</th>
                                   <td><table cellspacing="0" cellpadding="0" border="0" width="100%">
                                   <?php foreach ($data['attribute_list'] as $val){?>
                                   <tr><td width="8%">
                                   <?php echo $val['name'];?>:
                                   </td><td>
                                   <input type="checkbox" value="attribute[<?php echo $val['id'];?>][]" class="checkall"/> 全部 
                                       <?php if(is_array($val['options']) && !empty($val['options'])){
                                             foreach ($val['options'] as $option){?>
                                       <input type="checkbox" name="attribute[<?php echo $val['id'];?>][]" value="<?php echo $option['id'];?>" <?php if(isset($data['filter_struct']['attributes'][$val['id']]) && in_array($option['id'],$data['filter_struct']['attributes'][$val['id']])) echo "checked"?>> <?php echo $option['name'];?>    
                                       <?php }
                                       }?>
                                       </td></tr>
                                     <?php }?>
                                   </table>
                                   </td></tr>
                                   <?php }?>
                                   <?php if(!empty($data['feature_list'])){?>
                                   <tr><th width="10%">特性：</th>
                                   <td><table cellspacing="0" cellpadding="0" border="0" width="100%">
                                   <?php foreach ($data['feature_list'] as $val){?>
                                   <tr><td width="8%">
                                   <?php echo $val['name'];?>:
                                   </td><td>
                                   <input type="checkbox" value="feature[<?php echo $val['id'];?>][]" class="checkall"/> 全部 
                                       <?php if(is_array($val['options']) && !empty($val['options'])){
                                             foreach ($val['options'] as $option){?>
                                       <input type="checkbox" name="feature[<?php echo $val['id'];?>][]" value="<?php echo $option['id'];?>" <?php if(isset($data['filter_struct']['features'][$val['id']]) && in_array($option['id'],$data['filter_struct']['features'][$val['id']])) echo "checked"?>> <?php echo $option['name'];?>  
                                       <?php }
                                       }?>
                                       </td></tr>
                                     <?php }?>
                                     </table>
                                   </td></tr>
                                   <?php }?>
                                   </table>
                        </div>
                 <div class="list_save">
                 	<input name="dosubmit" type="submit" class="ui-button" value=" 保 存 " />
                 </div>
            </form>
        </div>
        <!--**productlist edit end**-->
    </div>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
var init,global_site_id = 1;
function showRelations(content){
	var brand = '';
	var attribute = '';
	var feature = '';
	$('#relations').empty();
	//填充类型
	if(content['classify']!=''){
	    $('#relations').append('<tr><th width="10%"><input id="classify_id" name="classify_id" type="hidden" value="'+content['classify']['id']+'" />类型：</th><td>'+content['classify']['name']+'</td></tr>');
	}
	//填充品牌
	$.each(content['brand_list'],function(i,val){
		brand += ' <input type="checkbox" name="brand[]" value="'+val['id']+'" > '+val['name']
    });
    if(brand != ''){
        $('#relations').append('<tr><th width="10%">品牌：</th><td><input type="checkbox" value="brand[]" class="checkall"> 全部 '+brand+'</td></tr>');
    }
    //填充规格
    $.each(content['attribute_list'],function(i,val){
    	attribute += '<tr><td width="8%">'+val['name']+':</td><td><input type="checkbox" value="attribute['+val['id']+'][]" class="checkall"/> 全部 ';
    	$.each(val['options'],function(j,options){
    	  attribute += '<input type="checkbox" name="attribute['+val['id']+'][]" value="'+options['id']+'" /> '+options['name']+' ';
    	});
    	attribute += '</td></tr>';
    });
    if(attribute != ''){
    	$('#relations').append('<tr><th width="10%">规格：</th><td valign="top"><table cellspacing="0" cellpadding="0" border="0" width="100%">'+attribute+'</table></td></tr>');
    }
    //填充特性
    $.each(content['feature_list'],function(i,val){
    	feature += '<tr><td width="8%">'+val['name']+':</td><td><input type="checkbox" value="feature['+val['id']+'][]" class="checkall"/> 全部 ';
    	$.each(val['options'],function(j,options){
    		feature += '<input type="checkbox" name="feature['+val['id']+'][]" value="'+options['id']+'" > '+options['name']+' ';
    	});
    	feature += '</td></tr>';
    });
    if(feature != ''){
    	$('#relations').append('<tr><th width="10%">特性：</th><td><table cellspacing="0" cellpadding="0" border="0" width="100%">'+feature+'</table></td></tr>');
    }
}
// custom
$(document).ready(function(){
	jQuery.validator.addMethod("positivenumber", function (value, element){       
	    return this .optional(element) || /^(0\.\d+|[1-9]\d*(\.\d+)?)$/.test(value);       
	} ,  "只能是大于0的数字" ); 
	
	$("#add_form").validate({
    	rules: {
		    uri_name : {
			    remote: '/product/aliasfilter/check_exist_uri_name?filter_id='+$('#filter_id').val()
		    },
		    pricefrom:{
		    	positivenumber:true
            },
            priceto:{
    	        positivenumber:true
            }
	    },
        messages:{
	    	uri_name : {
	    	    remote: 'URL已经存在'
	        }
	    }
    });
    
    $('#category_id').bind('change keyup', function(){
    	if(init!=1 && !confirm('切换分类将重置所有过滤条件?')){
            return false;
        }
        init=0;
		var category_id = $(this).val();
		$(this).attr('disabled', true);
		$('#category_change_tips').html('loading...');
		$.ajax({
			url: '/product/aliasfilter/get_category_data?category_id=' + category_id,
			dataType: 'json',
			success: function(retdat,status) {
				if (retdat['status'] == 1 && retdat['code'] == 200) {
					$('#category_change_tips').empty();
					showRelations(retdat['content']);
					$('input.checkall').click(function(){
				        var name = $(this).val();
				        if($(this).attr('checked')){
				            $('input[name="'+name+'"]').each(function(){
				                $(this).attr('checked',true);
				            });
				        }else{
				        	$('input[name="'+name+'"]').each(function(){
				                $(this).attr('checked',false);
				            });
				        }
				    });
					$('#category_id').attr('disabled', false);
				} else {
					$('#relations').empty();
					$('#category_change_tips').html('Request error with message:' + retdat['msg']);
					$('#category_id').attr('disabled', false);
				}
			},
			error: function() {
				$('#category_change_tips').html('Request http error, please try again later.');
				$('#category_id').attr('disabled', false);
			}
		});
	});
    <?php if(!isset($data['id'])){ ?>init=1; $('#category_id').change();<?php } ?>

    $('input.checkall').click(function(){
        var name = $(this).val();
        if($(this).attr('checked')){
            $('input[name="'+name+'"]').each(function(){
                $(this).attr('checked',true);
            });
        }else{
        	$('input[name="'+name+'"]').each(function(){
                $(this).attr('checked',false);
            });
        }
    });
});
</script>