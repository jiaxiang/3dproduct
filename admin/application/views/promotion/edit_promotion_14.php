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
                <li class="on">设置促销规则（<?php echo $pmts_memo?>）</li>
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
        <form id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>promotion/promotion/do_edit" enctype="multipart/form-data">
          <div class="out_box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
              <tbody>               
                <tr>
                  <th>规则名称：</th>
                  <td><?php echo $promotion_scheme['pmts_memo'];?></td>
                </tr>
                <?php if($promotion_scheme['description']):?>
                <tr>
                  <th>规则说明：</th>
                  <td><?php echo $promotion_scheme['description'];?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th width="20%">订单优惠条件<span style="color:#F00">*</span>： </th>
                  <td><input type="text" style="" size="10" name="money_from" value="<?php (isset($money_from) && print($money_from))|| print($promotion['money_from'])?>" class="text t40 _x_ipt required" />≤订单金额＜<input type="text" style="" size="10" name="money_to" class="text t40 _x_ipt required" value="<?php (isset($money_to) && print($money_to))|| print($promotion['money_to'])?>" />(金额的最大值为999999999999.999)</td>
                </tr>
                <tr>
                  <th>开始时间：</th>
                  <td><input type="text"  name="time_begin" id="time_begin"  value="<?php (isset($time_begin)&& print($time_begin))|| print(date('Y-m-d',strtotime($promotion['time_begin'])));?>" size="10" class="text required" style="background-color:#f1f1f1" readonly="true"/></td>
                </tr>
                <tr>
                  <th>结束时间：</th>
                  <td><input type="text"  name="time_end" id="time_end" value="<?php (isset($time_end)&& print($time_end))|| print(date('Y-m-d',strtotime($promotion['time_end'])-24*3600));?>" size="10" class="text required" style="background-color:#f1f1f1" readonly="true"/></td>
                </tr>                
                <tr>
                  <th>规则标题<span style="color:#F00">*</span>：</th>
                  <td><input type="text" style="" size="60" name="pmt_description" class="text required"  value="<?php (isset($pmt_description) && print($pmt_description))||print($promotion['pmt_description']);?>"/>(该标题会在购物车中显示)</td>
                </tr>
                <tr>
                  <th>规则描述：</th>
                  <td><textarea name="pmt_des_extra" cols="75" rows="5" class="text" type="textarea" maxth="255" ><?php (isset($pmt_des_extra) && print($pmt_des_extra)) || print($promotion['pmt_des_extra']);?></textarea>
                    <span class="brief-input-state notice_inline">(对规则的简单描述，该描述会在促销页面显示)</span></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="btn_eidt">
            <table width="445" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <th width="152"></th>
                <td width="293">
                  <input name="pmts_id" type="hidden" value="<?php echo $promotion['pmts_id']?>" />
                  <input name="id" type="hidden" value="<?php echo $promotion['id']?>" />
                  <input name="dosubmit" type="button" class="ui-button" value="取消" onclick="javascript:history.back()" />
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
