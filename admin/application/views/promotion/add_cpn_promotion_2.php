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
                <li class="on">设置优惠券规则</li>
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
        <form id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>promotion/cpn_promotion/do_add" enctype="multipart/form-data">
          <div class="out_box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
              <tbody>
                <tr>
                  <th width="20%">折扣类型： </th>
                  <td><?php echo $coupon_schemes['cpns_memo'];?></td>
                </tr>
                <tr>
                  <th width="20%">订单优惠条件<span style="color:#F00">*</span>： </th>
                  <td>
                  <input type="text" style="" size="10" name="money_from" class="text t40 _x_ipt required" value="<?php isset($money_from) && print($money_from);?>"/>≤订单金额＜<input type="text" style="" size="10" name="money_to" class="text t40 _x_ipt required" value="<?php (isset($money_to) && print($money_to)) || print("9999999");?>" />
                  (金额的最大值为999999999999.999)
                  </td>
                </tr>
                  <input name="time_begin" type="hidden" value="<?php echo date('Y-m-d',strtotime($coupon['cpn_time_begin']));?>" />
                  <input name="time_end" type="hidden" value="<?php echo date('Y-m-d',strtotime($coupon['cpn_time_end'])-24*3600);?>" />
                <!--
                <tr>
                  <th>开始时间：</th>
                  <td><input type="text"  name="time_begin" id="time_begin"  value="<?php echo date('Y-m-d',strtotime($coupon['cpn_time_begin']));?>" size="10" class="text required" /></td>
                </tr>
                <tr>
                  <th>结束时间：</th>
                  <td><input type="text"  name="time_end" id="time_end" value="<?php echo date('Y-m-d',strtotime($coupon['cpn_time_end']));?>" size="10" class="text required" /></td>
                </tr>
                -->
                <tr>
                  <th>规则描述<span style="color:#F00">*</span>：</th>
                  <td><input type="text" style="" size="60" name="cpn_description" class="text required"  value="<?php isset($cpn_description) && print($cpn_description);?>"/>(该标题会在购物车中显示)</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="btn_eidt">
            <table width="445" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <th width="152"></th>
                <td width="293">
                  <input name="cpns_id" type="hidden" value="<?php echo $cpns_id ?>" /> 
                  <input name="cpn_id" type="hidden" value="<?php echo $coupon_id ?>" />
                  <input name="prev" type="button" class="ui-button" value="上一步" onclick="javascript:history.back();"/>       
                  <input name="dosubmit" type="submit" class="ui-button" value="保存" />
                </td>
              </tr>
            </table>
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
