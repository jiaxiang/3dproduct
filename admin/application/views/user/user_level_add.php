<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<style>
<!--
.division table tbody tr th{width:120px;text-align:right;}
.division table tbody tr td{width:600px}
-->
</style>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加会员等级</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base().'user/user_level/put';?>" >
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                  <th>管理名称<span style="color:#F00;">*</span>：</th>
                                  <td><input type="text" name="name_manage" id="name_manage" class="text"/></td>
                                </tr>
                                <tr>
                                  <th>等级名称<span style="color:#F00;">*</span>：</th>
                                  <td><input type="text" name="name" id="name" class="text"/></td>
                                </tr>
                                <tr>
                                  <th>等级类型<span style="color:#F00;">*</span>：</th>
                                  <td>
									<input name="is_special"  type="radio" value="0" checked="true" /> 普通等级&nbsp;&nbsp;&nbsp;&nbsp;
                                  	<input name="is_special"  type="radio" value="1" /> 特殊等级 
                                  	<span style="color:#666666;margin-left:10px;padding-left:5px;">
                                  		普通等级会员的等级会随着会员积分的变化而变化，特殊等级会员的等级不会随着积分的变化而变化
                                  	</span>
								  </td>
                                </tr>
                                <tr>
                                  <th>所需分数<span style="color:#F00;">*</span>：</th>
                                  <td>
                                    <input type="text" name="score" id="score" class="text"/>
                                    <span style="color:#666666;margin-left:10px;padding-left:5px;">
                                    	会员累计积分达到此标准后会自动调整为当前会员等级
                                    </span>
                                  </td>
                                </tr>
                                <tr>
                                  <th>是否为默认等级<span style="color:#F00;">*</span>：</th>
                                  <td>
                                  	 <input name="is_default"  type="radio" value="1" /> 是&nbsp;&nbsp;&nbsp;&nbsp;
                                  	 <input name="is_default"  type="radio" value="0" checked="true" /> 否
                                  	 <span style="color:#666666;margin-left:10px;padding-left:5px;">
                                  	 	如果选择'是'，顾客注册商店会员成功时，初始等级为当前等级
                                  	 </span>
                                  </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input name="submit" type="submit" class="ui-button" value="保存 "><input name="cancel" type="button" class="ui-button" onclick="javascript:history.back();" value="取消"/>
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
	$('#add_form').validate({
		errorClass:"error",
		rules:{
		name_manage:{
				required:true,
				maxlength:255
			},
			name:{
				required:true,
				maxlength:255
			},
			score:{
				required:true,
				digits:true,
				max:9999999999
				
			}
		}
	});
	$("input[name=is_special]").click(function(){
		if($(this).attr('checked') && $(this).val()=='1')
		{
			$(this).parent().parent().next().hide();
		}else{
			$(this).parent().parent().next().show();
		}
	});

	$('input[name=submit]').click(function(){
		if($("input[name=is_special][value=1]").attr('checked'))
		{
			$("input[name=is_special]").parent().parent().next().remove();
		}
	});
});
</script>