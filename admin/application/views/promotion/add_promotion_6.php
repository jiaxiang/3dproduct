<?php defined('SYSPATH') or die('No direct access allowed.');
$session = Session::instance();
$sessionErrorData = $session->get('sessionErrorData');
if(isset($sessionErrorData) && !empty($sessionErrorData)) extract($sessionErrorData,EXTR_OVERWRITE);
?>
<script type="text/javascript" src="/js/discount.js"></script>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">设置促销规则</li>
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
        <form id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>promotion/promotion/do_add" enctype="multipart/form-data">
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
                  <td>
                  <input type="text" style="" size="10" name="money_from" class="text t40 _x_ipt required" value="<?php isset($money_from) && print($money_from);?>"/>≤订单金额＜<input type="text" style="" size="10" name="money_to" class="text t40 _x_ipt required" value="<?php (isset($money_to) && print($money_to)) || print("9999999");?>"/>
                  (金额的最大值为999999999999.999)
                  </td>
                </tr>
                <tr>
                  <th>开始时间：</th>
                  <td><input type="text"  name="time_begin" id="time_begin"  value="<?php (isset($time_begin)&& print($time_begin))|| print(date('Y-m-d',strtotime($promotion_activity['pmta_time_begin'])));?>" size="10" class="required text" style="background-color:#f1f1f1" readonly="true"/></td>
                </tr>
                <tr>
                  <th>结束时间：</th>
                  <td><input type="text"  name="time_end" id="time_end" value="<?php (isset($time_end)&& print($time_end))|| print(date('Y-m-d',strtotime($promotion_activity['pmta_time_end'])-24*3600));?>" size="10" class="required text" style="background-color:#f1f1f1" readonly="true"/></td>
                </tr>
                <tr>
                  <th>选择赠品：</th>
                  <td><?php echo $goods_area;?></td>
                </tr>
                <tr>
                  <th>赠品打折类型：</th>
                  <td>
                    <select name="discount_type" class="required">
                    <option value="0" <?php isset($discount_type) && $discount_type==0 && print('selected');?>>百分比</option>
                    <option value="1" <?php isset($discount_type) && $discount_type==1 && print('selected');?>>减去</option>
                    <option value="2" <?php isset($discount_type) && $discount_type==2 && print('selected');?>>减到</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <th>打折值<span style="color:#F00">*</span>：</th>
                  <td><input type="text" name="price" class="text required" value="<?php isset($price) && print($price);?>"/>(百分比:如果打9折，请输入0.9 &nbsp; 减去:如果设为100，那么客户的订单金额就会减$100 &nbsp; 减到:如果设为100，客户将以$100购买)</td>
                </tr>
                <tr>
                  <th>规则标题<span style="color:#F00">*</span>：</th>
                  <td><input type="text" style="" size="60" name="pmt_description" class="text required" value="<?php isset($pmt_description) && print($pmt_description);?>"/>(该标题会在购物车中显示)</td>
                </tr>
                <tr>
                  <th>规则描述：</th>
                  <td><textarea name="pmt_des_extra" cols="75" rows="5" class="text" type="textarea" maxth="255" ><?php isset($pmt_des_extra) && print($pmt_des_extra);?></textarea>
                    <span class="brief-input-state notice_inline">(对规则的简单描述，该描述会在促销页面显示)</span></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="btn_eidt a_center">
            <input name="pmts_id" type="hidden" value="<?php echo $pmts_id ?>" />
            <input name="pmta_id" type="hidden" value="<?php echo $pmta_id ?>" />       
            <input name="dosubmit" type="button" class="ui-button" value="上一步" onclick="javascript:window.location='<?php echo url::base();?>promotion/promotion/add?id=<?php echo $pmta_id ?>&pmts_id=<?php echo $pmts_id ?>';" />
            <input name="dosubmit" type="submit" class="ui-button" value="保存" />
          </div>
        </form>
      </div>
      <!--**category add end**-->
    </div>
  </div>
</div>
<!--**content end**-->
<!-- dialog form start -->
<?php echo $dialog;?>
<!-- dialog form end -->
<script type="text/javascript">
$(function(){
	$('#time_begin').datepicker({dateFormat:"yy-mm-dd"});
	$('#time_end').datepicker({dateFormat:"yy-mm-dd"});
});
</script>

<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>

<script type="text/javascript">
//<![CDATA[
$(document).ready(function(){
    $("#add_form").validate();

var dialog = 'dialog-form-good';
var thing = 'goods';
var toField = 'relatedGoods';
var related_ids = 'gift_related_ids';
url_base = '<?php echo url::base();?>';
opendialog(dialog ,thing,toField ,related_ids)
//modify
$("input[name='addGoods']").unbind().bind('click',function(e){
    $('#dialog-form-good').dialog('open');
    if(e){ e.preventDefault();}
});
<?php echo $js_good_field;?>
    //modify
    $("#goodSearchbtn").click(function(){
        var url = url_base+"promotion/promotion/search_good";
        var type = $("#goodSearchType").val();
        var keyword = $("#goodKeyword").val();
        var check = "checkAll_good";
        var thing = "goods";
        var table = "goodTable";
        tranData(url ,type,keyword ,check,thing,good_field,table);
    }).click();

    $('a[name=goods_page]').live('click',function(){//modify
        var url = url_base+"promotion/promotion/search_good?page="+$(this).attr('rev');;
        var type = $("#goodSearchType").val();
        var keyword = $("#goodKeyword").val();
        var check = "checkAll_good";
        var thing = "goods";
        var table = "goodTable";
        tranData(url ,type,keyword ,check,thing,good_field,table);
        return false;
    });
});
//]]>
</script>