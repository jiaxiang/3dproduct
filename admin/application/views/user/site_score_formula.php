<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<style>
<!--
.division table tbody tr th{width:200px}
.division table tbody tr td{width:250px}
-->
</style>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">积分公式设置</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base().'user/user_level/edit_formula';?>" >
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <thead>
                            </thead>
                            <tbody>
                                <tr>
                                  <th>积分计算公式：</th>
                                  <td>
                                  	<input type="text" name="user_score_formula" id="user_score_formula" class="text" value="<?php echo $site_detail['user_score_formula'];?>" />
                                  	<input type="button" name="validate_expression0" class="ui-button-small ui-widget ui-state-default" value="验证"/>
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
<div id="validate_expression_ifm">
    <div class="out_box">
    <h3 class="title1_h3">您可以在这里测试积分计算公式是否正确 </h3>
        <table width="90%" border="0" cellpadding="0" cellspacing="0" >
                <tr>
                    <th width="15%">积分计算公式：</th>
                    <td>
                        <input size="20" id="score_formula" name="score_formula" class="text input_300px"  value=""><span class="required"> *</span>
                    </td>
                </tr>
                <tr>
                    <th>消费金额：</th>
                    <td>
                        <input size="20" id="consumption" name="consumption" class="text" value="0"/> 
                    </td>
                </tr>
                <tr id="expression_result" style="display:none;">
                	<th>计算结果：</th><td id="final_result"></td>
                </tr>
                <tr>
                	<th>提示：</th><td>公式中使用s标识消费金额。系统提供默认为0的数据。</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="list_save">
                             <input name="button" type="button" class="ui-button" onclick="countexp();" value=" 计算" />
                        </div>
                    </td>
                </tr>
        </table>
	</div>
  </div>
<script type="text/javascript">
$(document).ready(function(){
	$('#add_form').validate({
		errorClass:"error",
		rules:{
		score_formula:{
			maxlength:255
			}
		}
	});
	
    $("#validate_expression_ifm").dialog({
        title: "验证积分计算公式",
        modal: true,
        autoOpen: false,
        height: 350,
        width: 600
    });
    $('input[name=validate_expression0]').click(function(){
    	$("#validate_expression_ifm").dialog('open');
    	$('#score_formula').val($('#user_score_formula').val());
    });
});
/** 验证公式 **/
function countexp(qd){
  var bds = $('input[name="score_formula"]').val();
  if (!qd){
      if (bds == ''){
        $('input[name="expression_validate"]').focus();
        alert("请输入积分计算公式");
        return;
      }
   }
  var re = new RegExp("/^[^\]\[\}\{\)\(0-9WwPp\+\-\/\*]+$/");
  if (re.test(bds)){
    alert("公式中含有非法字符");
  $('input[name="expression_validate"]').focus();
    return ;
  }
  var price = $('#consumption').val();
  
  var str;
    str = bds.replace(/(\[)/g, "getceil(");
    str = str.replace(/(\])/g, ")");
    str = str.replace(/(\{)/g, "getval(");
    str = str.replace(/(\})/g, ")");
    str = str.replace(/(S)/g, price);
    str = str.replace(/(s)/g, price);
    try {
      eval(str);
    }
    catch(e){
      alert("公式格式不正确");
      return;
    }
  var result = '<b>'+Math.round(eval(str)*100+0.01)/100+'</b>';
  $("#expression_result").css('display', '');
    $("#final_result").html(result);
}
</script>