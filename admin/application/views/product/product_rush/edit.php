<?php defined('SYSPATH') OR die('No direct access allowed.');
/* 返回的主体数据 */
$return_data = $return_struct['content'];
$data = $return_data['data'];
?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑抢购商品数据</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <form id="edit_form" name="edit_form" method="POST" action="<?php echo url::base();?>product/product_rush/edit">
            <div class="out_box" id="tabs">
                <div class="tableform" id="tabs-1">
                        <div class="division">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width=200>* 商品名称： </th>
                                        <td>
                                        <input name="id" id="id" type="hidden" value="<?php echo $data['id'];?>">
                                        <input id="title" name="title" type="input" class="text required"  value="<?php echo isset($data)?$data['title']:'';?>" size="50">
                                        </td>
                                    </tr>
                                     <tr>
                                        <th>抢购库存： </th>
                                        <td><input id="store" name="store" type="input" class="text"  value="<?php echo isset($data)?$data['store']:'';?>" size="50"></td>
                                    </tr>
                                     <tr>
                                        <th>抢购价格： </th>
                                        <td><input id="price_rush" name="price_rush" type="input" class="text"  value="<?php echo isset($data)?$data['price_rush']:'';?>" size="50"></td>
                                    </tr>
                                     <tr>
                                        <th>商品价格： </th>
                                        <td><input id="price" name="price" type="input" class="text"  value="<?php echo isset($data)?$data['price']:'';?>" size="50"></td>
                                    </tr>
                                     <tr>
                                        <th>最大购买数： </th>
                                        <td><input id="max_buy" name="max_buy" type="input" class="text"  value="<?php echo isset($data)?$data['max_buy']:'';?>" size="50"></td>
                                    </tr>
                                     <tr>
                                        <th>开始时间： </th>
                                        <td>
                                        <input readonly id="start_date" name="start_date" type="input" class="text"  value="<?php echo isset($data)?date('Y-m-d',strtotime($data['start_time'])):'';?>" size="20">
                                        <input id="start_time" name="start_time" type="input" class="text"  value="<?php echo isset($data)?date('H:i:s',strtotime($data['start_time'])):'';?>" size="20">
                                        </td>
                                    </tr>
                                     <tr>
                                        <th>结束时间： </th>
                                        <td>
                                        <input readonly id="end_date" name="end_date" type="input" class="text"  value="<?php echo isset($data)?date('Y-m-d',strtotime($data['end_time'])):'';?>" size="20">
                                        <input id="end_time" name="end_time" type="input" class="text"  value="<?php echo isset($data)?date('H:i:s',strtotime($data['end_time'])):'';?>" size="20">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                </div>
                 <div style="text-align:center;">
                 	<input type="submit" class="ui-button" value=" 编辑 " />
                 </div>
            </div>
            </form>
        </div>
        <!--**productlist edit end**-->
    </div>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#start_date').datepicker({dateFormat:"yy-mm-dd"});
    $('#end_date').datepicker({dateFormat:"yy-mm-dd"});
    $("#edit_form").validate();
});
</script>