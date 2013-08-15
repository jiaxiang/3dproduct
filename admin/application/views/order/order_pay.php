<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#order_pay_form_diglog").validate();
        /* 按钮风格 */
        $(".ui-button-small,.ui-button").button();
    });
</script>
<form name="order_pay_form_diglog" id="order_pay_form_diglog" action="<?php echo url::base();?>order/order/order_pay" method="POST">
    <div class="dialog_box">
        <div class="headContent">
            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="actionBar mainHead">
                <tbody>
                    <tr>
                        <td><span class="notice_inline">1 确认订单信息 » 2 确认金额 » 3 确认支付</span></td>
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
                                <th width="20%">订单金额：</th>
                                <td width="30%"><?php echo $order['total_real'] . ' ' . $order['currency'];?></td>
                                <th width="20%">下单时间：</th>
                                <td width="30%"><?php echo $order['date_add'];?></td>
                            </tr>
                            <tr>
                                <th>支付方式：</th>
                                <td width="30%">
                                    <select name="payment_method_id">
                                        <?php foreach($payment_method as $key=>$value):?>
                                        <option value="<?php echo $key;?>"><?php echo $value['name'];?></option>
                                        <?php endforeach;?>
                                    </select>
                                </td>
                                <th>收款账号：</th>
                                <td>
                                    <input type="text" name="receive_account" class="text" size="25">
                                </td>
                            </tr>
                            <tr>
                                <th>金额(<?php echo $order['currency'];?>)：</th>
                                <td class="d_line">
                                    <input type="text" name="amount" class="text required number" size="15" min="0" value="<?php echo $order['total_real'];?>">
                                </td>
                                <th>交易号：</th>
                                <td>
                                    <input type="text" name="trans_no" class="text" size="25">
                                </td>
                            </tr>
                            <tr>
                                <th>是否发送邮件：</th>
                                <td colspan="3"><input type="radio" name="is_send_mail" value="0" checked="checked">不发送<input type="radio" name="is_send_mail" value="1">发送</td>
                            </tr>
                            <tr>
                                <th>备注(管理员)：</th>
                                <td colspan="3">
                                    <textarea type="textarea" class="text" rows="2" cols="60" name="content_admin" maxlength="1000"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>备注(会员)：</th>
                                <td colspan="3">
                                    <textarea type="textarea" class="text" rows="2" cols="60" name="content_user" maxlength="1000"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="list_save">
        <input name="create_all_goods" type="submit" class="ui-button" value="支付"/>
        <input name="cancel" id="cancel_btn" type="button" class="ui-button" value="取消" onclick='$("#order_pay").dialog("close");'/>
        <input type="hidden" name="submit_target" id="submit_target" value="0" />
        <input type="hidden" name="order_id" id="order_id" value="<?php echo $order['id'];?>" />
    </div>
</form>