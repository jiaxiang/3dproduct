<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><?php echo $title;?></li>
            </ul>
			<span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                </li>
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索:
                	 <select name="type" id="type">
                     <option value="">所有类型</option>
                    <?php
					foreach($site_types as $key=>$value):
					?>
                    <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                    <?php
					endforeach;
					?>
                    </select>
                    <input name="keyword" class="text" id="keyword">
                    <input name="search_button" id="search_button" type="button" class="ui-button-small" value=" 搜索" onclick="search_site();">
                    <img src="<?php echo url::base();?>images/icon/ajax-loader.gif" alt="Loading..." style="display:none;"/>
                </div>
            </form>

        </div>
        <form id="add_form" name="add_form" method="post" action="<?php echo url::base().url::current();?>">
        <table  cellspacing="0">
                <thead>
                    <tr class="headings">
                        <th width="40%" style="text-align:center">可选网站</th>
                        <th width="20%" style="text-align:center">操作</th>
                        <th width="40%" style="text-align:center">已经分配的站点</th>                       
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:8px"><select name="source_select[]" id="source_select" size="20" multiple style="width:100%" ondblclick='add_target_select();'>
	                    <?php
						foreach($sites as $key=>$value):
						?>
	                    <option value="<?php echo $value['id'];?>" ondblclick='add_target_select();'><?php echo $value['name'];?></option>
	                    <?php
						endforeach;
						?>
	               		 </select>
                        </td>
                        <td style="text-align:center">
                        <p>
	                    <INPUT name="button" id="addAll" type="button" onclick="" value=">>" class="button_arrow">
	                  	</p>
	                  	<p>
	                    <INPUT name="button" id="add" type="button" onclick="add_target_select();" value=">"class="button_arrow">
	                  	</p>
	                  	<p>
	                    <INPUT name="button" id="delete" type="button" onclick="delete_target_select();" value="<" class="button_arrow">
	                  	</p>
	                  	<p>
	                    <INPUT name="button" id="deleteAll" type="button" onclick="" value="<<" class="button_arrow">
	                 	</p>
                        </td>
                        <td style="padding:8px">
                        <SELECT multiple size="20" name="target_select[]" id="target_select" style="width:100%" class="require" ondblclick='delete_target_select();'>
	                    <?php
						foreach($target_sites as $key=>$value):
						?>
	                    <option value="<?php echo $value['id'];?>" ondblclick='delete_target_select();'><?php echo $value['name'];?></option>
	                    <?php
						endforeach;
						?>
	                	</SELECT>
                        </td>
                    </tr>
                </tbody>
        </table>
        <div style="margin:4px auto;text-align:center;padding-bottom:6px">
          <input type="button" class="ui-button" name="button" value="保存添加信息" onclick="check_target_select();">
    	</div>
        </form>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
        </div>
    </div>
</div>
<!--END FOOTER-->
<script type="text/javascript">
jQuery.fn.extend({
	moveOptions: function(to, params)
	{
		var params = params || {};
		$('option' + (params.move_all ? '' : ':selected:not(.cm-required)'), this).appendTo(to);

		if (params.check_required) {
			var f = [];
			$('option.cm-required:selected', this).each(function() {
				f.push($(this).text());
			});

			if (f.length) {
				alert(params.message + "\n" + f.join(', '));
			}
		}

		return true;
	},

	swapOptions: function(direction)
	{
		$('option:selected', this).each(function() {
			if (direction == 'up') {
				$(this).prev().insertAfter(this);
			} else {
				$(this).next().insertBefore(this);
			}
		});

		return true;
	},

	selectOptions: function(flag)
	{
		$('option', this).attr('selected', (flag == true) ? 'selected' : '');

		return true;
	}
});
//给添加所有按钮添加onclick事件  
$(function(){  
	$(":button[id^=addAll]").click(function(){  
		var toAdds;  
		toAdds = $("#source_select option");  
			
		toAdds.each(function(){  
			$("#target_select").append("<option value='"+$(this).val()+"' ondblclick='delete_target_select();'>"+$(this).text()+"</option>");  
			$(this).remove();    
		});  
	});
});

//给删除和删除所有按钮添加onclick事件  
$(function(){   
	$(":button[id^=deleteAll]").click(function(){  
		var todeletes;  
		todeletes = $("#target_select option");  

		todeletes.each(function(){  
			$("#source_select").append("<option value='"+$(this).val()+"' ondblclick='add_target_select();'>"+$(this).text()+"</option>");  
			$(this).remove();    
		});
	});
});
//添加到目标
function add_target_select(){
	$("#source_select").moveOptions('#target_select');
}
//目录中删除
function delete_target_select(){
	$("#target_select").moveOptions('#source_select');
}
//选中提交
function check_target_select(){
	$("#target_select").selectOptions(true);
	$("#add_form").submit();
}
//ajax搜索
function search_site(){
	var type = $("#type>option:selected").val();
	var keyword = $("#keyword").val();
	var cur = "";
	$("#target_select option").each(function(){
		cur += $(this).val() + ",";
	});
	$("#search_button").hide().next().show();
	
	$.post("<?php echo $access_url;?>",{ 
		type: type, keyword: keyword , cur: cur
		}, function(data){
			if(data.success){
				$('#source_select').html(data.content);
				$("#search_button").show().next().hide();
			}else{
				alert('请选择搜索条件!');
				$("#search_button").show().next().hide();
			}
		},'json'
	);
	return false;
}
</script>