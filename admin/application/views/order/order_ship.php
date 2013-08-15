<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<div class="out_box fixfloat">
    <form name="ship_form" id="ship_form" action="<?php echo url::base();?>order/order/order_ship" method="POST">
        <div class="out_box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <th width="15%">订单号：</th>
                        <td width="35%"><?php echo $order['order_num'];?></td>
                        <th width="15%">状态：</th>
                        <td><?php echo isset($pay_status[$order['pay_status']])?$pay_status[$order['pay_status']]['name']:'未付款';?>【<?php echo isset($ship_status[$order['ship_status']])?$ship_status[$order['ship_status']]['name']:'未发货';?>】</td>
                    </tr>
                    <tr>
                        <th>物流方式：</th>
                        <td>
                            <select name="carrier_id" id="carrier_id" onchange="show_ems_url();">
                                <?php foreach($carriers as $key=>$value):?>
                                <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                        <th>物流费用：</th>
                        <td><?php echo $order['currency']?> <?php echo round($order['total_shipping']/$order['conversion_rate'],2);?></td>
                    </tr>
                    <tr>
                        <th>物流单号：</th>
                        <td><input type="text" name="ems" size="25" class="text required"><span class="required"> *</span></td>
                        <th>系统邮件是否发送：</th>
                        <td><input type="radio" name="is_send_mail" value="0" checked="checked">不发送<input type="radio" name="is_send_mail" value="1">发送</td>
                    </tr>
                    <tr>
                        <th>Tracking link：</th>
                        <td colspan="3">
                            <input type="text" name="ems_url" id="ems_url" size="60" class="text required">
                            <span class="brief-input-state notice_inline">用于用户邮件中用户查询物流状态的链接。</span>
                        </td>
                    </tr>
                    <tr>
                        <th>备注(管理员)：</th>
                        <td>
                            <textarea type="textarea" class="text" rows="2" cols="40" name="content_admin"></textarea>
                        </td>
                        <th>备注(会员)：</th>
                        <td>
                            <textarea type="textarea" class="text" rows="2" cols="40" name="content_user"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="out_box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <th style="text-align:left">货号</th>
                    <th style="text-align:left">商品名称</th>
                    <th style="text-align:left">购买数量</th>
                    <th style="text-align:left">已发货</th>
                    <th style="text-align:left">此单发货</th>
                </tr>
                <?php if($order_products):?>
                    <?php foreach($order_products as $key=>$value):?>
                <tr>
                    <td><?php echo $value['SKU'];?></td>
                    <?php if(isset($value['attribute_style']) && !empty($value['attribute_style'])) :?>
                    <td><?php echo $value['name'].'('.$value['attribute_style'].')';?></td>
                    <?php else :?>
                    <td><?php echo $value['name'];?></td>
                    <?php endif;?>
                    <td><?php echo $value['quantity'];?></td>
                    <td><?php echo $value['sendnum'];?></td>
                    <td><input class="text required digits" size="3" type="text" max="<?php echo $value['quantity']-$value['sendnum'];?>" name="ship_num[<?php echo $value['id'];?>]" value="<?php echo $value['quantity']-$value['sendnum'];?>"/></td>
                </tr>
                    <?php endforeach;?>
                <?php endif;?>
            </table>
        </div>
        <div class="list_save nore">
            <input name="create_all_goods" type="submit" class="ui-button" value=" 发货 "/>
            <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='$("#order_ship").dialog("close");'/>
            <input type="hidden" name="submit_target" id="submit_target" value="0" />
            <input type="hidden" name="order_id" id="order_id" value="<?php echo $order['id'];?>" />
        </div>
    </form>
    <script type="text/javascript">
        var carriers = <?php echo $carriers_json;?>;
        $(document).ready(function(){
            $("#ship_form").validate();
            /* 按钮风格 */
            $(".ui-button-small,.ui-button").button();
            show_ems_url();
        });    
        function show_ems_url()
        {
            var ems_id = $('#carrier_id').val();
            for(var i in carriers){
                if(carriers[i]['id'] == ems_id){
                    var carrier = carriers[i];
                    break;
                }
            }
            $('#ems_url').val(carrier['url']);
        }
    </script>
</div>