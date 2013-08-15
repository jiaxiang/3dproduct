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
        <form id="add_form" name="add_form" method="POST" action="<?php echo url::base();?>promotion/promotion/<?php echo isset($faction)?$faction:'do_add';?>" enctype="multipart/form-data">
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
                  <th>开始时间：</th>
                  <td><input type="text"  name="time_begin" id="time_begin"  value="<?php isset($time_begin) || $time_begin = date('Y-m-d',strtotime($promotion_activity['pmta_time_begin']));echo $time_begin;?>" size="10" class="required text" style="background-color:#f1f1f1" readonly="true"/></td>
                </tr>
                <tr>
                  <th>结束时间：</th>
                  <td><input type="text"  name="time_end" id="time_end" value="<?php isset($time_end) || $time_end = date('Y-m-d',strtotime($promotion_activity['pmta_time_end'])-24*3600); echo $time_end;?>" size="10" class="required text" style="background-color:#f1f1f1" readonly="true"/></td>
                </tr>
                <tr>
                  <th>选择商品<span style="color:#F00">*</span>：</th>
                  <td><?php echo $products_area;?></td>
                </tr>                
                <tr>
                  <th>规则标题<span style="color:#F00">*</span>：</th>
                  <td><input type="text" style="" size="60" name="pmt_description" class="text required" value="<?php echo isset($pmt_description)?$pmt_description:$promotion_scheme['pmts_memo'];?>"/>(该标题会在购物车中显示)</td>
                </tr>
                <tr>
                  <th>规则描述：</th>
                  <td><textarea name="pmt_des_extra" cols="75" rows="5" class="text" type="textarea" maxth="255" ><?php echo (isset($pmt_des_extra)?$pmt_des_extra:(isset($promotion_scheme['description'])?$promotion_scheme['description']:$promotion_scheme['pmts_memo']));?></textarea>
                    <span class="brief-input-state notice_inline">(对规则的简单描述，该描述会在促销页面显示)</span></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="btn_eidt a_center">
            <input name="id" type="hidden" value="<?php echo isset($promotion['id'])?$promotion['id']:0;?>" />
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

var dialog = 'dialog-form-product';
var thing = 'products';
var toField = 'relatedProducts';
var related_ids = 'related_ids';
opendialog(dialog ,thing,toField ,related_ids)
//modify
$("input[name='addProduct']").unbind().bind('click',function(e){
    $("#productSearchbtn").click();
    $('#dialog-form-product').dialog('open');
    if(e){ e.preventDefault();}
});
url_base = '<?php echo url::base();?>';
    //modify
<?php echo $js_product_field;?>
    $("#productSearchbtn").click(function(){
        var url = url_base+"promotion/promotion/search_product";
        var type = $("#productSearchType").val();
        var keyword = $("#productKeyword").val();
        var check = "checkAll";
        var thing = "products";
        var table = "productTable";
        tranData(url ,type,keyword ,check,thing,product_field,table);
    }).click();

    $('a[name=products_page]').live('click',function(){//modify
        var url = url_base+"promotion/promotion/search_product?page="+$(this).attr('rev');
        var type = $("#productSearchType").val();
        var keyword = $("#productKeyword").val();
        var check = "checkAll";
        var thing = "products";
        var table = "productTable";
        tranData(url ,type,keyword ,check,thing,product_field,table);
        return false;
    });
});
//]]>
</script>