<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<script type="text/javascript">
    $(document).ready(function(){
        $("#order_status_form").validate();
        /* 按钮风格 */
        $("button,input:submit,input:button").button();
    });
</script>
<form name="order_status_form" id="order_status_form" action="<?php echo url::base();?>order/order/order_status" method="POST">
    <div class="dialog_box">
        <div class="headContent">
            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="actionBar mainHead">
                <tbody>
                    <tr>
                        <td><span class="notice_inline">1 确认要更新的状态 » 2 填写备注 » 3 确认</span></td>
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
                                <th width="15%">订单号：</th>
                                <td width="35%"><?php echo $order['order_num'];?></td>
                                <th width="15%">状态：</th>
                                <td><?php echo isset($pay_status[$order['pay_status']])?$pay_status[$order['pay_status']]['name']:'未付款';?>【<?php echo isset($ship_status[$order['ship_status']])?$ship_status[$order['ship_status']]['name']:'未发货';?>】</td>
                            </tr>
                            <tr>
                                <th>订单金额：</th>
                                <td colspan="3"><?php echo $order['total_real'] . ' ' . $order['currency'];?></td>
                            </tr>
                            <tr>
                                <th>订单状态更新为：</th>
                                <td colspan="3" style="font-weight:bold;color:red;">
                                    <?php echo $title;?>
                                    <input type="hidden" value="<?php echo $action;?>" name="action">
                                </td>
                            </tr>
                            <tr>
                                <th>备注(管理员)：</th>
                                <td colspan="3">
                                    <textarea type="textarea" class="text" rows="2" cols="70" name="content_admin"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>备注(会员)：</th>
                                <td colspan="3">
                                    <textarea type="textarea" class="text" rows="2" cols="70" name="content_user"></textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="list_save">
        <input name="create_all_goods" type="submit" value=" 确认 "/>
        <input name="cancel" id="cancel_btn" type="button" value=" 取消 " onclick='$("#order_status").dialog("close");'/>
        <input type="hidden" name="submit_target" id="submit_target" value="0" />
        <input type="hidden" name="order_id" id="order_id" value="<?php echo $order['id'];?>" />
    </div>
</form>
<div id="validate_error_message" style="display:none;"></div>