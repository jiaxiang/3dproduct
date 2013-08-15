<?php defined('SYSPATH') or die('No direct access allowed.');
$session = Session::instance();
$sessionErrorData = $session->get('sessionErrorData');
if(isset($sessionErrorData) && !empty($sessionErrorData)) extract($sessionErrorData,EXTR_OVERWRITE);
?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑优惠券</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
  <div class="grid_c2">
    <div class="col_main">
      <!--**category add start**-->
      <div class="edit_area">
        <form id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>promotion/coupon/do_edit" enctype="multipart/form-data">
          <div class="out_box">
          	<input type='hidden' name= 'id' value="<?php echo $coupon['id'];?>">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
              <tbody>
                <tr>
                  <th>优惠券名称<span style="color:#F00">*</span>：</th>
                  <td><input type="text" style="" size="40" name="cpn_name" class="text required"  value="<?php (isset($cpn_name) && print($cpn_name))|| print($coupon['cpn_name']);?>"/></td>
                </tr>
                <tr>
                  <th>号码<span style="color:#F00">*</span>：</th>
                  <td><input type="text" style="" size="20" name="cpn_prefix" class="text required" value="<?php echo $coupon['cpn_prefix'];?>"  disabled /></td>
                </tr>
                <tr>
                  <th>状态：</th>
                  <td><input type="radio" name="disabled" value="0" <?php if ((isset($disabled)&& $disabled==0) || $coupon['disabled'] == 0) echo 'checked="true"';?> /> 启用&nbsp;&nbsp;&nbsp;<input type="radio" name="disabled" value="1" <?php if ((isset($disabled)&& $disabled==1) || $coupon['disabled'] == 1) echo 'checked="true"';?> /> 禁用</td>
                </tr>
                <tr>
                  <th>类型：</th>
                  <td>
                    <input type="radio" name="cpn_type" value="A" <?php if ($coupon['cpn_type'] == 'A') echo 'checked'; else echo 'disabled';?> /> 每张使用一次: 顾客可一次获得多张，但在规定时间内每张只能使用一次，无法重复使用<br />
                    <input type="radio" name="cpn_type" value="B" <?php if ($coupon['cpn_type'] == 'B') echo 'checked'; else echo 'disabled';?> /> 一张重复使用: 顾客只需获得一张，即可在规定的时间内重复使用
                  </td>
                </tr>         
                <tr>
                  <th>开始时间：</th>
                  <td><input type="text"  name="cpn_time_begin" id="time_begin"  value="<?php (isset($cpn_time_begin)&& print($cpn_time_begin))|| print(date('Y-m-d',strtotime($coupon['cpn_time_begin'])));?>" size="10" class="text required" style="background-color:#f1f1f1" readonly="true"/></td>
                </tr>
                <tr>
                  <th>结束时间：</th>
                  <td><input type="text"  name="cpn_time_end" id="time_end" value="<?php (isset($cpn_time_end)&& print($cpn_time_end))|| print(date('Y-m-d',strtotime($coupon['cpn_time_end'])-24*3600));?>" size="10" class="text required" style="background-color:#f1f1f1" readonly="true"/></td>
                </tr>
                <!--<tr>
                  <th><input type="checkbox" name="with_pmt" value="1" <?php if ($coupon['with_pmt'] == 1) echo ' checked';?> /></th>
                  <td>可与促销活动同时生效</td>
                </tr>-->
                <tr>
                  <th>选择优惠券规则：</th>
                  <td>
                    <?php
                    $num = 1;
                    foreach ( $promotion_schemes as $scheme ): ?>
                  	<input type="radio" name="cpns_id" value="<?php echo $scheme['id']?>" <?php if ($coupon['cpns_id'] == $scheme['id']) echo 'checked="true"';?> class="required" <?php if($coupon['cpns_id']){?>disabled<?php } ?> /> <?php echo $num++.' . '.$scheme['cpns_memo'] ?>
                  	<br/>
                	<?php endforeach ?>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="btn_eidt" style='text-align:center;'>
            <input name="cpnp_id" type="hidden" value="<?php echo $coupon['cpnp_id']?>" />
    	    <input name="cancel" type="button" class="ui-button" value=" 取 消 " onclick="javascript:history.back();"/>
	        <input name="dosubmit" type="submit" class="ui-button" value="下一步" />
          </div>
        </form>
      </div>
      <!--**category add end**-->
    </div>
  </div>
</div>
<!--**content end**-->
<script type="text/javascript">
$(function(){
	$('#time_begin').datepicker({dateFormat:"yy-mm-dd"});
	$('#time_end').datepicker({dateFormat:"yy-mm-dd"});
});
</script>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
    });
</script>
