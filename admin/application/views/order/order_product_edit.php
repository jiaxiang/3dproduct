<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<form id="add_form" name="add_form" method="post" action="<?php echo url::base() . 'order/order_product/do_edit/' . $data['id'];?>">
    <div class="division">
        <h3 class="title1_h3">编辑商品信息</h3>

        <table cellspacing="0" cellpadding="0" class="finderInform" style="border-left:1px solid #e8ebf1;">
            <thead >
                <tr>
                    <th width="20%">商品名称</th>
                    <th width="20%">商品属性</th>
                    <th width="10%">货号SKU</th>
                    <th width="10%">&nbsp;</th>
                    <th width="10%"></th>
                    <th width="10%">单价</th>
                    <th width="8%">数量</th>
                    <th colspan="2">小计</th>
                </tr>
            </thead>
            <tbody id="productNode">
                <?php foreach ($data['product'] as $key=>$rs) {?>
                <tr>
                    <td><input size="10" name="order_product[<?php echo $rs['id']?>][name]" class="text required" value="<?php echo $rs['name']?>">&nbsp;</td>
                    <td><input size="10" name="order_product[<?php echo $rs['id']?>][attribute_style]" class="text required" value="<?php echo $rs['attribute_style']?>">&nbsp;</td>
                    <td><input size="10" name="order_product[<?php echo $rs['id']?>][SKU]" class="text required" value="<?php echo $rs['SKU']?>">&nbsp;</td>
                    <td><?php echo kohana::config('product.order_product_type.' . $rs['product_type'] . '.value_type.' . $rs['product_detail_type'])?>&nbsp;</td>
                    <td><!--<input size="10" name="order_product[<?php echo $rs['id']?>][price]" class="text required" value="<?php echo $rs['price']?>">-->&nbsp;</td>
                    <td><input size="10" name="order_product[<?php echo $rs['id']?>][discount_price]" class="text required" value="<?php echo $rs['discount_price']?>">&nbsp;</td>
                    <td><input size="3" name="order_product[<?php echo $rs['id']?>][quantity]" class="text required" value="<?php echo $rs['quantity']?>">&nbsp;</td>
                    <td colspan="2"><?php echo $rs['discount_price'] * $rs['quantity']?>&nbsp;</td>
                </tr>
                    <?php }?>
                <tr>
                    <th colspan="7"  style="font-weight:bold;">商品总费用</th>
                    <th colspan="2" ><input size="8" name="total_products" class="text required" value="<?php echo $data['total_products']?>">&nbsp;</th>
                </tr>
                <tr>
                    <th colspan="7" style="font-weight:bold;">物流</th>
                    <th width="5%" ><input size="8" name="carrier" class="text required" value="<?php echo $data['carrier']?>">&nbsp;</th>
                    <th width="5%" ><input size="8" name="total_shipping" class="text required" value="<?php echo $data['total_shipping']?>">&nbsp;</th>
                </tr>
                <tr>
                    <th colspan="7" style="font-weight:bold;">总计</th>
                    <th colspan="2" ><input size="8" name="total" class="text required" value="<?php echo $data['total']?>">&nbsp;</th>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="list_save">
        <b class="blue">
            <input name="submit" type="submit" class="ui-button" value=" 保存 " />
        </b>
    </div>
</form>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
    });
</script>