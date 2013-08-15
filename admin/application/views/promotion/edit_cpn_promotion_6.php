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
      <!--**category add start**-->
      <div class="edit_area">
        <form id="edit_form" name="edit_form" method="POST" action="<?php echo url::base();?>promotion/cpn_promotion/do_edit" enctype="multipart/form-data">
          <div class="out_box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
              <tbody> 
                <tr>
                  <th width="20%">折扣类型： </th>
                  <td><?php echo $coupon_schemes['cpns_memo'];?></td>
                </tr>
                <tr>
                  <th width="20%">订单优惠条件<span style="color:#F00">*</span>： </th>
                  <td><input type="text" style="" size="10" name="quantity_from" value="<?php (isset($quantity_from) && print($quantity_from))|| print($promotion['quantity_from'])?>" class="text required" />≤购买商品数量＜<input type="text" style="" size="10" name="quantity_to" class="text t40 _x_ipt required" value="<?php (isset($quantity_to) && print($quantity_to))|| print($promotion['quantity_to'])?>" /></td>
                </tr>
                  <input name="time_begin" type="hidden" value="<?php echo date('Y-m-d',strtotime($coupon['cpn_time_begin']));?>" />
                  <input name="time_end" type="hidden" value="<?php echo date('Y-m-d',strtotime($coupon['cpn_time_end'])-24*3600);?>" />
                <!--
                <tr>
                  <th>开始时间：</th>
                  <td><input type="text"  name="time_begin" id="time_begin"  value="<?php echo date('Y-m-d',strtotime($promotion['time_begin']));?>" size="10" class="text required" /></td>
                </tr>
                <tr>
                  <th>结束时间：</th>
                  <td><input type="text"  name="time_end" id="time_end" value="<?php echo date('Y-m-d',strtotime($promotion['time_end']));?>" size="10" class="text required" /></td>
                </tr>
                -->
                <tr>
                  <th>打折类型：</th>
                  <td>
                    <select name="discount_type" class="required">
                    <option value="0" <?php ((isset($discount_type) && $discount_type==0) ||$promotion['discount_type'] == 0 )&& print('selected');?>>百分比</option>
                    <option value="1" <?php ((isset($discount_type) && $discount_type==1) ||$promotion['discount_type'] == 1 )&& print('selected');?>>减去</option>
                    <option value="2" <?php ((isset($discount_type) && $discount_type==2) ||$promotion['discount_type'] == 2 )&& print('selected');?>>减到</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <th>打折值<span style="color:#F00">*</span>：</th>
                  <td><input type="text" name="price" class="text required" value="<?php (isset($price) && print($price)) || print($promotion['price']);?>" />(百分比:如果打9折，请输入0.9 &nbsp; 减去:如果设为100，那么客户的订单金额就会减$100 &nbsp; 减到:如果设为100，客户将以$100购买)</td>
                </tr>
                <tr>
                  <th>规则描述<span style="color:#F00">*</span>：</th>
                  <td><input type="text" style="" size="60" name="cpn_description" class="text required"  value="<?php (isset($cpn_description) && print($cpn_description))||print($promotion['cpn_description']);?>"/>(该标题会在购物车中显示)</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="btn_eidt">
            <table width="445" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <th width="152"></th>
                <td width="293">
                  <input name="cpn_id" type="hidden" value="<?php echo $promotion['cpn_id']?>" />
                  <input name="id" type="hidden" value="<?php echo $promotion['id']?>" />
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
        $("#edit_form").validate();
    });
</script>
