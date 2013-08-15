<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
$filter_list = $return_data['filter_list'];
$category_list = $return_data['category_list'];
$classify_list = $return_data['classify_list'];
?>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加虚拟分类</li>
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
            <form id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>product/aliasfilter/put">
                        <div class="out_box">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                     <tr>
                                        <th width="10%">所属站点：</th>
                                        <td>
                                           <?php echo $site_name;?>
                                        </td>
                                    </tr>
                                    <tr>
                                    <th>上一级虚拟分类：</th>
                                    <td><select name="pid" id="pid" class="required">
                                            <option value="0">----</option>
                                            <?php echo $filter_list;?>  
                                        </select>
                                        <div id="category_change_tips" class="valierror"></div>
                                    </td>
                                </tr>
                                    <tr>
                                        <th>* 虚拟分类名称： </th>
                                        <td><input id="title" name="title" type="input" class="text required"  value="" size="50" maxlength="100"></td>
                                    </tr>
                                    <tr>
                                        <th>* 前台链接： </th>
                                        <td><input id="uri_name" name="uri_name" type="input" class="text required"  value="" size="50" maxlength="100"></td>
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
                                          <input id="keywords" name="keywords" type="input" class="text"  value="" size="30">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="10%">价格区间：</th>
                                        <td>
                                          $<input id="pricefrom" name="pricefrom" type="input" class="text"  value="" size="10">
                                          - $<input id="priceto" name="priceto" type="input" class="text"  value="" size="10">
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
                                    </table>
                        </div>
                 <div class="list_save">
                 	<input name="dosubmit" type="submit" class="ui-button" value="添加" />
                 </div>
            </form>
        </div>
        <!--**productlist edit end**-->
    </div>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
function showRelations(content){
	var brand = '';
	var attribute = '';
	var feature = '';
	$('#relations').empty();
	//填充类型
	if(content['classify']!=''){
	    $('#relations').append('<tr><th width="10%">类型：</th><td>'+content['classify']['name']+'</td></tr>');
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
	$("#title").bind('keyup blur',function(){
        $('#uri_name').val($("#title").val());
    }); 

	jQuery.validator.addMethod("positivenumber", function (value, element){       
	    return this .optional(element) || /^(0\.\d+|[1-9]\d*(\.\d+)?)$/.test(value);       
	} ,  "只能是大于0的数字" ); 
	  
    $("#add_form").validate({
    	rules: {
		    uri_name:{
			    remote: '/product/aliasfilter/check_exist_uri_name'
		    },
		    pricefrom:{
		    	positivenumber:true
            },
            priceto:{
    	        positivenumber:true
            }
	    },
        messages:{
	    	uri_name:{
	    	    remote: 'URL已经存在'
	        }
	    }
    });
    
    $('#category_id').attr('disabled', true);
    $('#category_change_tips').html('loading...');
    $.ajax({
		url: '/product/aliasfilter/get_category_data?category_id=0',
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
	
	$('#category_id').bind('change keyup', function(){
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

});
</script>