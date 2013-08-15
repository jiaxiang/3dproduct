<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#order_returned_form").validate();
        /* 按钮风格 */
        $(".ui-button-small,.ui-button").button();
    });
</script>
<div class="out_box fixfloat">
    <form name="order_returned_form" id="order_returned_form" action="<?php echo url::base();?>order/order/order_return" method="POST">
        <div class="out_box fixfloat">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <th width="20%">订单号：</th>
                        <td><?php echo $order['order_num'];?></td>
                        <th width="20%">状态：</th>
                        <td><?php echo isset($pay_status[$order['pay_status']])?$pay_status[$order['pay_status']]['name']:'未付款';?>【<?php echo isset($ship_status[$order['ship_status']])?$ship_status[$order['ship_status']]['name']:'未发货';?>】</td>
                    </tr>
                    <tr>
                        <th width="20%">物流方式：</th>
                        <td width="30%"><?php echo $order['carrier'];?></td>
                        <th width="20%">物流费用：</th>
                        <td><?php echo $order['currency']?> <?php echo round($order['total_shipping']/$order['conversion_rate'],2);?></td>
                    </tr>
                    <tr>
                        <th>备注(管理员)：</th>
                        <td>
                            <textarea type="textarea" class="text" rows="2" cols="30" name="content_admin"></textarea>
                        </td>
                        <th>备注(会员)：</th>
                        <td>
                            <textarea type="textarea" class="text" rows="2" cols="30" name="content_user"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="out_box fixfloat">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <th>货号</th>
                    <th>商品名称</th>
                    <th>购买数量</th>
                    <th>已发货</th>
                    <th>此单退货数量</th>
                </tr>
                <?php if($order_products):?>
                    <?php foreach($order_products as $key=>$value):?>
                <tr>
                    <td><?php echo $value['SKU'];?></td>
                    <td><?php echo $value['name'];?><?php if($value['attribute_style']) {
                                    echo '('.$value['attribute_style'].')';}?></td>
                    <td><?php echo $value['quantity'];?></td>
                    <td><?php echo $value['sendnum'];?></td>
                    <td><input class="text required digits" size="3" type="text" name="return_num[<?php echo $value['id'];?>]" value="<?php echo $value['sendnum'];?>" max="<?php echo $value['sendnum'];?>"/></td>
                </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </table>
        </div>
        <div class="list_save nore">
            <input name="create_all_goods" type="submit" class="ui-button" value=" 退货 "/>
            <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='$("#order_returned_goods").dialog("close");'/>
            <input type="hidden" name="submit_target" id="submit_target" value="0" />
            <input type="hidden" name="order_id" id="order_id" value="<?php echo $order['id'];?>" />
        </div>
    </form>
</div>

