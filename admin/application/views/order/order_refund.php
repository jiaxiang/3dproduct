<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#order_refund_form_diglog").validate({
            messages: {
                refund_money: {
                    max: '退款金额不能大于支付金额！'
                }
            }
        });
        /* 按钮风格 */
        $(".ui-button-small,.ui-button").button();
    });
</script>
<form name="order_refund_form_diglog" id="order_refund_form_diglog" action="<?php echo url::base();?>order/order/order_refund" method="POST">
    <div class="dialog_box">
        <div class="headContent">
            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="actionBar mainHead">
                <tbody>
                    <tr>
                        <td><span class="notice_inline">1 选择退款原因和退款方式 » 2 确认退款金额 » 3 确认退款</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="body dialogContent">
            <!-- tips of pdtattrset_set_tips  -->
            <div id="gEditor-sepc-panel">
                <div class="out_box">
                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                        <tbody>
                            <tr>
                                <th width="20%">订单号：</th>
                                <td><?php echo $order['order_num'];?></td>
                                <th width="20%">状态：</th>
                                <td><?php echo isset($pay_status[$order['pay_status']])?$pay_status[$order['pay_status']]['name']:'未付款';?>【<?php echo isset($ship_status[$order['ship_status']])?$ship_status[$order['ship_status']]['name']:'未发货';?>】</td>
                            </tr>
                            <tr>
                                <th width="20%">下单时间：</th>
                                <td width="30%"><?php echo $order['date_add'];?></td>
                                <th width="20%">支付时间：</th>
                                <td width="30%"><?php echo $order['date_pay'];?></td>
                            </tr>
                            <tr>
                                <th width="20%">订单金额：</th>
                                <td width="30%"><?php echo $order['total_real'] . ' ' . $order['currency'];?></td>
                                <th width="20%">支付金额：</th>
                                <td width="30%"><?php echo $order['total_paid'] . ' ' . $order['currency'];?></td>
                            </tr>
                            <tr>
                                <th>退款原因：</th>
                                <td>
                                    <select name="refund_reason">
                                        <?php foreach($refund_reason as $key=>$value):?>
                                        <option value="<?php echo $key;?>"><?php echo $value['name'];?></option>
                                        <?php endforeach;?>
                                    </select>
                                </td>
                                <th>退款方式：</th>
                                <td>
                                    <select name="refund_method">
                                        <?php foreach($refund_method as $key=>$value):?>
                                        <option value="<?php echo $key;?>"><?php echo $value['name'];?></option>
                                        <?php endforeach;?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>退款金额(<?php echo $order['currency'];?>)：</th>
                                <td colspan="3">
                                    <input type="text" name="refund_money" class="text required number" size="10" value="<?php echo $order['total_paid'];?>" max="<?php echo $order['total_paid'];?>" min="0">
                                </td>
                            </tr>
                            <tr>
                                <th>是否发送邮件：</th>
                                <td colspan="3"><input type="radio" name="is_send_mail" value="0" checked="checked">不发送<input type="radio" name="is_send_mail" value="1">发送</td>
                            </tr>
                            <tr>
                                <th>备注(管理员)：</th>
                                <td>
                                    <textarea type="textarea" class="text" rows="2" cols="20" name="content_admin"></textarea>
                                </td>
                                <th>备注(会员)：</th>
                                <td>
                                    <textarea type="textarea" class="text" rows="2" cols="20" name="content_user"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="list_save">
        <input name="create_all_goods" type="submit" class="ui-button" value=" 退款 "/>
        <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='$("#order_refund").dialog("close");'/>
        <input type="hidden" name="submit_target" id="submit_target" value="0" />
        <input type="hidden" name="order_id" id="order_id" value="<?php echo $order['id'];?>" />
    </div>
</form>