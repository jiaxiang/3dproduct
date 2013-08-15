<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */ 
$return_data = $return_struct['content'];
$data = isset($return_data['data'])?$return_data['data']:array();
$brand_list = isset($return_data['brand_list'])?$return_data['brand_list']:array();
$attribute_list = isset($return_data['attribute_list'])?$return_data['attribute_list']:array();
$feature_list = isset($return_data['feature_list'])?$return_data['feature_list']:array();
?>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<div class="new_content">
    <div class="newgrid">
    	<div class="new_sub_menu_title fixfloat">
            <span class="title2">编辑类型</span>
            <span class="fright"></span>
        </div>
        
    	<div class="new_pro_tab">
        	<ul>
            	<span class="first"></span>
            	<li class="on">类型基本信息 *</li>
                <li>关联品牌：</li>
                <li>关联规格：</li>
                <li>关联特性：</li>
                <!-- li>关联参数：</li -->
            </ul>
        </div>
        
         <form id="edit_form" name="edit_form" method="POST" action="<?php echo url::base();?>product/classify/<?php echo $return_data['action'];?>">
        <div class="contentin new_pro_con fixfloat">
            <div class="out_box">
                <table cellspacing="0">
                    <tbody>
                        <tr>
                            <th>* 类型名称：</th>
                            <td><input name="id" id="id" type="hidden" value="<?php echo isset($data['id'])?$data['id']:'';?>">
                                <input id="name" name="name" type="input" class="text required"  value="<?php echo isset($data['name'])?$data['name']:'';?>" size="50">
                           </td>
                        </tr>
                        <tr>
                            <th> 类型后台名称：</th>
                            <td><input id="alias" name="alias" type="input" class="text"  value="<?php echo isset($data['alias'])?$data['alias']:'';?>" size="50"> （以"|"分隔，如：“T恤|T恤衫”）</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>    
        <div class="new_pro_con fixfloat">
            <div class="out_box">
            	<table cellspacing="0">
                	<col width="50" />
                    <col />
                    <col />
                    <col />
                	<tr class="headings">
                    	<th class="a_center"><input value="brand_id[]" name="checkall" type="checkbox"/></th>
                        <th>品牌名称</th>
                        <th>是否可筛选</th>
                    </tr>
                    <?php if (is_array($brand_list) && !empty($brand_list)) {?>
                    <?php foreach ($brand_list as $key => $val) {?>
                    <tr>
                    	<td class="a_center"><input value="<?php echo $val['id'];?>" name="brand_id[]" type="checkbox" <?php echo !empty($data['brands'][$val['id']]) ? "checked" : "";?>/></td>
                        <td><?php echo $val['name'];?></td>
                        <td align="center">
                        <?php if(!empty($data['brands'][$val['id']])):?>
                        <input value="1" name="brand_show[<?php echo $val['id'];?>]" type="radio" <?php echo $data['brands'][$val['id']]['is_show'] == 1 ? "checked" : "";?>/> 是
                        <input value="0" name="brand_show[<?php echo $val['id'];?>]" type="radio" <?php echo $data['brands'][$val['id']]['is_show'] == 0 ? "checked" : "";?>/> 否
                        <?php else:?>
                        <input value="1" name="brand_show[<?php echo $val['id'];?>]" type="radio" checked/> 是
                        <input value="0" name="brand_show[<?php echo $val['id'];?>]" type="radio"/> 否
                        <?php endif;?>
                        </td>
                    </tr>
                    <?php }
                    }?>
                </table>
            </div>
         </div>       
         <div class="new_pro_con fixfloat">
            <div class="out_box">
            	<table cellspacing="0">
                	<col width="50" />
                    <col />
                    <col />
                    <col />
                	<tr class="headings">
                    	<th class="a_center"><input value="attribute_id[]" name="checkall" type="checkbox"></th>
                        <th>规格名称</th>
                        <th>规格后台名称</th>
                        <th>显示方式</th>
                        <th>是否可筛选</th>
                    </tr>
                    <?php if (is_array($attribute_list) && !empty($attribute_list)) {?>
                    <?php foreach ($attribute_list as $key => $val) {?>
                    <tr>
                    	<td class="a_center"><input value="<?php echo $val['id'];?>" type="checkbox" name="attribute_id[]" <?php echo !empty($data['attributes'][$val['id']]) ? "checked" : "";?>/></td>
                        <td><?php echo $val['name'];?></td>
                        <td><?php echo $val['alias'];?> </td>
                        <td><?php echo ($val['display'] == 'text')?'文字':(($val['display'] == 'image')?'图片':'-');?></td>
                        <td align="center">
                        <?php if(!empty($data['attributes'][$val['id']])):?>
                        <input value="1" name="attribute_show[<?php echo $val['id'];?>]" type="radio" <?php echo $data['attributes'][$val['id']]['is_show'] == 1 ? "checked" : "";?>/> 是
                        <input value="0" name="attribute_show[<?php echo $val['id'];?>]" type="radio" <?php echo $data['attributes'][$val['id']]['is_show'] == 0 ? "checked" : "";?>/> 否
                        <?php else:?>
                        <input value="1" name="attribute_show[<?php echo $val['id'];?>]" type="radio" checked/> 是
                        <input value="0" name="attribute_show[<?php echo $val['id'];?>]" type="radio"/> 否
                        <?php endif;?>
                        </td>
                    </tr>
                    <?php }
                    }?>
                </table>
            </div>
         </div>       
         <div class="new_pro_con fixfloat">
            <div class="out_box">
            	<table cellspacing="0">
                	<col width="50" />
                    <col />
                    <col />
                    <col />
                	<tr class="headings">
                    	<th class="a_center"><input value="feature_id[]" name="checkall" type="checkbox"/></th>
                        <th>特性名称</th>
                        <th>特性后台名称</th>
                        <th>是否可筛选</th>
                    </tr>
                    <?php if (is_array($feature_list) && !empty($feature_list)) {?>
                    <?php foreach ($feature_list as $key => $val) {?>
                    <tr>
                    	<td class="a_center"><input value="<?php echo $val['id'];?>" type="checkbox" name="feature_id[]" <?php echo !empty($data['features'][$val['id']]) ? "checked" : "";?>></td>
                        <td><?php echo $val['name'];?></td>
                        <td><?php echo $val['alias'];?> </td>
                        <td align="center">
                        <?php if(!empty($data['features'][$val['id']])):?>
                        <input value="1" name="feature_show[<?php echo $val['id'];?>]" type="radio" <?php echo $data['features'][$val['id']]['is_show'] == 1 ? "checked" : "";?>/> 是
                        <input value="0" name="feature_show[<?php echo $val['id'];?>]" type="radio" <?php echo $data['features'][$val['id']]['is_show'] == 0 ? "checked" : "";?>/> 否
                        <?php else:?>
                        <input value="1" name="feature_show[<?php echo $val['id'];?>]" type="radio" checked/> 是
                        <input value="0" name="feature_show[<?php echo $val['id'];?>]" type="radio"/> 否
                        <?php endif;?>
                        </td>
                    </tr>
                    <?php }
                    }?>
                </table>
            </div>
         </div>
         <div class="new_pro_con fixfloat">
            <div class="out_box" id="box_argument_relation">
            	<input name="argument_group_create" type="button" class="ui-button" value="添加参数组" />
            </div>
         </div>  
         <div style="text-align:center;padding:5px;">
            <input name="dosubmit" type="submit" class="ui-button" value="确认修改" />
         </div>    
        </form>
    </div>
</div>
<div id="message" class="ui-dialog-content ui-widget-content" style="height:160px;min-height:100px;width:auto;">
    <p id="message_content"></p>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    
    $(".new_pro_tab li").each(function(index){
    	$(this).click(function(){
    		$(".new_pro_tab li.on").removeClass("on");
    		$(this).addClass("on");
    		$(".new_pro_con").removeClass("contentin");
    		$(".new_pro_con:eq(" + index + ")").addClass("contentin");
    	})
    });
                
    	$.validator.addMethod('checkArgumentGroupName', function(value, element, params){
    		var idxs = $('input[name="argument_group_index[]"]');
    		var elnm = $(element).attr('name');
			for (var x = 0; x < idxs.length; x ++) {
				var tn = $('input[name="argument_group_name_' + $(idxs[x]).val() + '"]');
				if (tn.length > 0 && tn.attr('name') != elnm) {
					if (value == tn.val()) {
						return false;
					}
				}
			}
    		return true;
    	});

    	$.validator.addMethod('checkArgumentName', function(value, element, params) {
			var elnm = $(element).attr('name');
			var agix = elnm.split('_')[2].slice(0, -3);
			for (var x = 0; x < arguments_index[agix]; x ++) {
				var tn = $('input[name="argument_name_' + agix + '[' + x + ']"]');
				if (tn.length > 0 && tn.get(0) != element && tn.val() == value) {
					return false;
				}
			}
			return true;
    	});
    	
    	var argument_group_index = 1;
    	var arguments_index      = {};

    	function showMessage(title, content) {
            var message = $('#message');
            $('#message_content').html(content);
            message.dialog('option', 'title', title);
            message.dialog('open');
        }
    	
    	function renderArgument(idx, argument)
    	{
			var s = $('#argument_group_' + idx);

			var n1 = 'argument_name_' + idx + '[' + arguments_index[idx] + ']';
			var n2 = 'argument_alias_' + idx + '[' + arguments_index[idx] + ']';
			
			var t = $('<tr></tr>');
			t.append('<td class="a_center">#' + (arguments_index[idx] + 1) + '</td>');
			t.append('<td class="a_left"><span class="w1">名称：</span><input type="text" class="text" name="' + n1 + '" value=""></td>');
			t.append('<td class="a_left"><span class="w2">后台名称：</span><input type="text" class="text" name="' + n2 + '" value=""></td>');
			t.append('<td class="a_right""><a href="javascript:void(0);" name="argument_remove">删除</a></td>');

			if (typeof argument != 'undefined') {
				t.find('input[name="' + n1 + '"]').val(argument.name);
				t.find('input[name="' + n2 + '"]').val(argument.alias);
			}

			s.append(t);

			t.find('input[name="' + n1 + '"]').rules('add', {
				required: true,
				checkArgumentName: true,
				messages: {
					required: '不可为空',
					checkArgumentName: '不可重复'
				}
			});
			/*t.find('input[name="' + n2 + '"]').rules('add', {
				required: true,
				messages: {
					required: '不可为空'
				}
			});*/
			
			arguments_index[idx] ++;
    	}
    	
    	function renderArgumentGroup(argument_group)
		{
			var s = $('<table cellspacing="0" class="classify_table" id="argument_group_' + argument_group_index + '" style="margin-top:10px;"></table>');

			var t = $('<tr></tr>');
			t.append('<th width="10%" class="a_center cDBlue fB f14px"> * 组：#' + argument_group_index + '<input type="hidden" name="argument_group_index[]" value="' + argument_group_index + '"></th>');
			t.append('<th width="30%" class="a_left"><span class="fB cDBlue w1">名称：</span><input class="text" name="argument_group_name_' + argument_group_index + '" value=""></th>');
			t.append('<th width="30%" class="a_left"><span class="fB cDBlue w2">后台名称：</span><input class="text" name="argument_group_alias_' + argument_group_index +'" value=""></th>');
			t.append('<th width="30%" class="a_right atcion"><span name="argument_create" style="cursor:pointer;">添加参数</span>&nbsp;|&nbsp;<span name="argument_group_remove" style="cursor:pointer;">删除参数组</span></th>');

			s.append(t);
			
			$('#box_argument_relation').append(s);

			arguments_index[argument_group_index] = 0;

			t.find('input[name="argument_group_name_' + argument_group_index +'"]').rules('add', {
				required: true,
				checkArgumentGroupName: true,
				messages: {
					required: '不可为空',
					checkArgumentGroupName: '不可重复'
				}
			});
			/*t.find('input[name="argument_group_alias_' + argument_group_index + '"]').rules('add', {
				required: true,
				messages: {
					required: '不可为空'
				}
			});*/
			
			if (typeof argument_group != 'undefined') {
				t.find('input[name="argument_group_name_' + argument_group_index +'"]').val(argument_group.name);
				t.find('input[name="argument_group_alias_' + argument_group_index + '"]').val(argument_group.alias);
				for (var i = 0; i < argument_group.items.length; i ++) {
					var item = argument_group.items[i];
					renderArgument(argument_group_index, item);
				}
			} else {
				renderArgument(argument_group_index);
			}

			argument_group_index ++;
		}

		$("#edit_form").validate();
		
		$('#box_argument_relation').bind('click', function(e){
            var t = $(this);
			var o = $(e.target);

			if (typeof o.attr('name') != 'undefined') {
				switch (o.attr('name').toUpperCase()) {
					case 'ARGUMENT_GROUP_CREATE':
						renderArgumentGroup();
						break;
					case 'ARGUMENT_CREATE':
						renderArgument(o.parent().parent().find('input[name="argument_group_index[]"]').val());
						break;
					case 'ARGUMENT_REMOVE':
						var r = o.parent().parent();
						if (r.parent().children().length > 2) {
							r.remove();
						} else {
							showMessage('操作失败', '<font color="#990000">每个参数组至少需要保留一个参数！</font>');
						}
						break;
					case 'ARGUMENT_GROUP_REMOVE':
						var i = o.parent().parent().find('input[name="argument_group_index[]"]').val();
						$('#argument_group_' + i).remove();
						break;
				}
			}
        });

        <?php if (!empty($data['argument_relation_struct'])) : ?>
		var argument_relation_struct = <?php echo $data['argument_relation_struct']; ?>;
		for (var i = 0; i < argument_relation_struct.length; i ++) {
			renderArgumentGroup(argument_relation_struct[i]);
		}
        <?php endif; ?>

        $('#message').dialog({
            title: '',
            modal: true,
            autoOpen: false,
            height: 160,
            width: 300,
            buttons: {
                '确定': function(){
                    $('#message').dialog('close');
                }
            }
        });
        
        $('input[name="checkall"]').click(function(){
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