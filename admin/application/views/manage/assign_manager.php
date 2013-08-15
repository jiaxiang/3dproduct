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
<!--**content start**-->
<div id="content_frame">
<div class="grid_c2">
  <div class="col_main">
    <div class="public_crumb">
      <p><a href="/">桌面</a> 》 <?php echo $title;?></p>
    </div>
    <!--**productlist edit start**-->
    <div class="edit_area">
      <div class="division">
        <form id="search_form" name="search_form" method="post" action="#" onsubmit="return search_site();">
          <table cellspacing="0" cellpadding="0" border="0" width="100%" class="actionBar mainHead" align="center">
            <tbody>
              <tr>
                <td align="left" style="font-weight:bold;">搜索:
                  <INPUT name="keyword" class="text" id="keyword">
                  <INPUT name="search_button" id="search_button" type="button" class="ui-button-small" value=" 搜索" onclick="search_site();">
                  <img src="<?php echo url::base();?>images/icon/ajax-loader.gif" alt="Loading..." style="display:none;"/>
                  </td>
              </tr>
            </tbody>
          </table>
          </form>
          <form id="add_form" name="add_form" method="post" action="<?php echo url::base().url::current();?>">
          <TABLE id="linkgoods-table" width="100%" align="center">
            <!-- 商品搜索 -->
            <TBODY>
              <!-- 商品列表 -->
              <TR>
                <TH style="text-align:left;font-weight:bold;">可选管理员</TH>
                <TH style="text-align:center;font-weight:bold;">操作</TH>
                <TH style="text-align:left; font-weight:bold;">已经可以管理本站点管理员</TH>
              </TR>
              <TR>
                <TD width="42%">
                <select name="source_select[]" id="source_select" size="20" multiple style="width:100%" ondblclick='add_target_select();'>
                    <?php
					foreach($managers as $key=>$value):
					?>
                    <option value="<?php echo $value['id'];?>" ondblclick='add_target_select();'><?php echo $value['name'];?></option>
                    <?php
					endforeach;
					?>
                </select>
                </TD>
                <TD style="text-align:center;"><p>
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
                  </p></TD>
                <TD width="42%">
                <SELECT multiple size="20" name="target_select[]" id="target_select" style="width:100%" class="require" ondblclick='delete_target_select();'>
                    <?php
					foreach($site_managers as $key=>$value):
					?>
                    <option value="<?php echo $value['id'];?>" ondblclick='delete_target_select();'><?php echo $value['name'];?></option>
                    <?php
					endforeach;
					?>
                </SELECT>
                </TD>
              </TR>
            </TBODY>
          </TABLE>
          <div class="footContent" style="">
            <div style="margin: 0pt auto; width: 200px; height: 40px;" class="mainFoot">
              <table style="margin: 0pt auto; width: auto;">
                <tbody>
                  <tr>
                    <td>
                      <input type="button" class="ui-button" name="button" value="保存添加信息" onclick="check_target_select();">
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </form>
      </div>
      <!--**productlist edit end**-->
    </div>
  </div>
</div>
<!--**content end**-->
