<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="new_crumb">
            <span class="new_order_edit_title fleft"><?php echo $order['order_num']?>
                - <?php echo (isset($pay_status[$order['pay_status']]))?$pay_status[$order['pay_status']]['name']:'未支付';?>
                【<?php echo (isset($ship_status[$order['ship_status']]))?$ship_status[$order['ship_status']]['name']:'未发货';?>】
            </span>
            <div class="fright">
                <div class="fright">
                    <?php if($order['order_status'] == 4) {?>
                    <ul class="new_order_state">
                        <li class="first">&nbsp;</li>
                        <li>已废除</li>
                        <li class="last">&nbsp;</li>
                    </ul>
                        <?php } elseif($order['order_status'] == 3) { ?>
                    <ul class="new_order_state">
                        <li class="first">&nbsp;</li>
                        <li>已完成</li>
                        <li class="last">&nbsp;</li>
                    </ul>
                        <?php }else {?>
                    <ul class="new_order_state">
                        <li class="first">&nbsp;</li>
                        <li>                            
                                <?php if(order::pay_status_exclusion($order['pay_status'],2)) {?>
                            <a href="javascript:void(0);" class="order_status_btn" name="pending" id="<?php echo $order['id']?>">支付处理中</a>
                                    <?php }else {?>
                            支付处理中
                                    <?php }?>
                        </li>
                        <li class="line">&nbsp;</li>
                        <li>
                                <?php if(order::pay_status_exclusion($order['pay_status'],3)) {?>
                            <a href="javascript:void(0);" class="order_pay_btn" name="order_pay" id="<?php echo $order['id']?>">支付</a>
                                    <?php }else {?>
                            支付
                                    <?php }?>
                        <li class="line">&nbsp;</li>
                        <li>
                                <?php if(order::pay_status_exclusion($order['pay_status'],4)) {?>
                            <a href="javascript:void(0);" class="order_status_btn" name="awaiting_refund" id="<?php echo $order['id']?>">退款处理中</a>
                                    <?php }else {?>
                            退款处理中
                                    <?php }?>
                        </li>
                        <li class="line">&nbsp;</li>
                        <li>
                                <?php if(order::pay_status_exclusion($order['pay_status'],6)) {?>
                            <a href="javascript:void(0);" class="order_refund_btn" name="refund" id="<?php echo $order['id']?>">退款</a>
                                    <?php }else {?>
                            退款
                                    <?php }?>

                        </li>
                        <li class="last">&nbsp;</li>
                    </ul>
                    <ul class="new_order_state">
                        <li class="first">&nbsp;</li>
                        <li>
                                <?php if(order::ship_status_exclusion($order['ship_status'],2)) {?>
                            <a href="javascript:void(0);" class="order_status_btn" name="awaiting_shipment" id="<?php echo $order['id']?>">配货中</a>
                                    <?php }else {?>
                            配货中
                                    <?php }?>
                        </li>
                        <li class="line">&nbsp;</li>
                        <li>
                                <?php if(order::ship_status_exclusion($order['ship_status'],3)) {?>
                            <a href="javascript:void(0);" class="order_ship_btn" name="order_ship" id="<?php echo $order['id']?>">发货</a>
                                    <?php }else {?>
                            发货
                                    <?php }?>
                        </li>
                        <li class="line">&nbsp;</li>
                        <li>
                                <?php if(order::ship_status_exclusion($order['ship_status'],5)) {?>
                            <a href="javascript:void(0);" class="order_returned_goods_btn" name="returned_goods" id="<?php echo $order['id']?>">退货</a>
                                    <?php }else {?>
                            退货
                                    <?php }?>
                        </li>
                        <li class="last">&nbsp;</li>
                    </ul>
                    <ul class="new_order_state">
                        <li class="first">&nbsp;</li>
                        <li>
                                <?php if(order::order_status_exclusion($order['order_status'],2)) {?>
                            <a href="javascript:void(0);" class="order_status_btn" name="confirm" id="<?php echo $order['id']?>">确认订单</a>
                                    <?php }else {?>
                            确认订单
                                    <?php }?>
                        </li>
                        <li class="line">&nbsp;</li>
                        <li>
                                <?php if(order::order_status_exclusion($order['order_status'],3)) {?>
                            <a href="javascript:void(0);" class="order_status_btn" name="complete" id="<?php echo $order['id']?>">订单完成</a>
                                    <?php }else {?>
                            订单完成
                                    <?php }?>
                        </li>
                        <li class="last">&nbsp;</li>
                    </ul>
                    <ul class="new_order_state">
                        <li class="first">&nbsp;</li>
                        <li>
                                <?php if($order['order_status'] >= 3) {?>
                            废除
                                    <?php }else {?>
                            <a href="javascript:void(0);" class="order_status_btn" name="cancel" id="<?php echo $order['id']?>">废除</a>
                                    <?php }?>
                        </li>
                        <li class="last">&nbsp;</li>
                    </ul>
                        <?php }?>
                </div>
            </div>
        </div>
        <div class="new_order_con fixfloat">
            <h2>商品信息<img width="7" class="arrow_hide" height="4" style="margin: 0pt 0pt 3px 10px;cursor:pointer;" src="/images/new_arrow_ico.gif"></h2>
            <?php if($order['order_status'] == '1' || $order['order_status'] == '2'):?>
            <span class="new_order_common_btn">
                <a href="javascript:void(0);" id="product_manage">添加</a>
            </span>
            <?php endif;?>
            <table id="goods_info" cellspacing="0">
                <thead>
                    <tr class="headings">
                        <th>商品名称</th>
                        <th>备注</th>
                        <th>SKU</th>
                        <th>类型</th>
                        <th>原价</th>
                        <th>折扣价</th>
                        <th>数量</th>
                        <th>已发货数量</th>
                        <th>总价</th>
                        <?php if($order['order_status'] == '1' || $order['order_status'] == '2'):?>
                        <th width="80px">操作</th>
                        <?php endif;?>
                    </tr>
                </thead>
                <tbody>  
                	<input type="hidden" id="order_id" name="order_id" value="<?php echo $order['id'];?>"></input>
                	<input type="hidden" id="url" name="url" value="<?php echo url::base();?>order/order/edit/id/<?php echo $order['id'];?>"></input>
                    <?php foreach($order['order_product'] as $key=>$order_product){ ?>
                    <?php $order_product = coding::decode_product($order_product); ?>
                    <tr>
                        <td>
                        <?php if(!empty($order_product['link'])) :?>
                        <a href="<?php echo $order_product['link'];?>" target="_blank">
                        <?php else :?>
                        <a href="<?php echo product::permalink($order_product);?>" target="_blank">
                        <?php endif;?>
                        <?php 
                            echo $order_product['name']; 
                            if($order_product['attribute_style']){
                                echo '('.$order_product['attribute_style'].')';
                            }  
                        ?>
                        </a>
                        </td>
                        <td><?php echo $order_product['remark'];?></td>
                        <td><?php echo $order_product['SKU']?></td>
                        <td><?php echo kohana::config('product.order_product_type.'.$order_product['product_type'].'.name')?></td>
                        <td><?php echo $order['currency']?> <?php echo round($order_product['price']/$order['conversion_rate'],2)?></td>
                        <td><?php echo $order['currency']?> <?php echo round($order_product['discount_price']/$order['conversion_rate'],2)?></td>
                        <td><?php echo $order_product['quantity'];?></td>
                        <td><?php echo $order_product['sendnum'];?></td>
                        <td><?php echo $order['currency']?> <?php echo round(($order_product['discount_price']*$order_product['quantity'])/$order['conversion_rate'],2)?></td>
                        <?php if($order['order_status'] == '1' || $order['order_status'] == '2'):?>
                        <td><a href="javascript:void(0);" class="act_doedit" id="<?php echo $order_product['id']?>"><?php if(!empty($order_product['uri_name']) && !empty($order_product['store'])){echo "编辑";}else{echo "查看";}?></a>&nbsp;&nbsp;<a class="act_dodelete" href="/order/order_product/do_delete/<?php echo $order_product['id']?>">删除</a></td>
                        <?php endif;?>
                    </tr>
                        <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="10" class="a_right">商品费用：<?php echo $order['currency']?> <?php echo round($order['total_products']/$order['conversion_rate'],2);?></td>
                    </tr>
                    <?php if($order['total_discount']):?>
                    <tr>
                        <td colspan="10" class="a_right">折扣：<?php echo $order['currency']?> <?php echo round($order['total_discount']/$order['conversion_rate'],2);?></td>
                    </tr>
                    <?php endif;?>
                    <tr>
                        <td colspan="10" class="a_right">
                        <?php if($order['order_status'] == '1' || $order['order_status'] == '2'):?>
                         <div class="new_float_parent">		
                        		物流费用：<?php echo $order['currency']?>                        
                               <input type="text" class="text" size="4" name="order_shipping" value="<?php echo round($order['total_shipping']/$order['conversion_rate'],2);?>">
                               <div class="new_float_right" >
                                   <input type="text" class="text" size="4" name="final_shipping" value="<?php echo round($order['total_shipping']/$order['conversion_rate'],2);?>" />
                                   <input type="button" class="ui-button-small" value="保存" name="submit_order_form" />
                                   <input type="button" class="ui-button-small" value="取消" name="cancel_order_form"/>
                               </div>
                        </div>
                        <?php else:?>
                        物流费用：<?php echo $order['currency']?> <?php echo round($order['total_shipping']/$order['conversion_rate'],2);?>
                        <?php endif;?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="10" class="a_right">总价：<?php echo $order['currency']?> <span id="total_price"><?php echo round($order['total']/$order['conversion_rate'],2);?></span></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php if(count($order['order_discount_log'])):?>
        <div class="new_order_con fixfloat">
            <h2>优惠方案<img width="7" class="arrow_hide" height="4" style="margin: 0pt 0pt 3px 10px;cursor:pointer;" src="/images/new_arrow_ico.gif"></h2>
            <table>
                <thead>
                    <tr class="headings">
                        <th width="10%" >类型</th>
                        <th width="30%" >优惠方案</th>
                        <th width="20%" >优惠金额</th>
                        <th width="40%" >参考说明</th>
                    </tr>
                </thead>

                    <?php foreach($order['order_discount_log'] as $key=>$order_discount_log):?>
                <tbody id="productNode">
                    <tr>
                        <td>
                                    <?php if($order_discount_log['discount_type_id']>0):?>
                            促销
                                    <?php else:?>
                            优惠券
                                    <?php endif;?>
                        </td>
                        <td><?php echo $order_discount_log['description'];?></td>
                        <td>
                                    <?php if ($order_discount_log['discount_type'] == 0):?>
                            百分比 <?php echo ($order_discount_log['discount_value']*100);?>%
                                    <?php elseif($order_discount_log['discount_type'] == 1):?>
                            减去 <?php echo $order['currency']?> <?php echo round($order_discount_log['discount_value']/$order['conversion_rate'],2);?>
                                    <?php elseif($order_discount_log['discount_type'] == 2):?>
                            减到 <?php echo $order['currency']?> <?php echo round($order_discount_log['discount_value']/$order['conversion_rate'],2);?>
                                    <?php endif;?>
                        </td>
                        <td><?php echo $order_discount_log['reference']?></td>
                    </tr>
                </tbody>
                    <?php endforeach;?>
            </table>
        </div>
        <?php endif;?>
        <?php if(is_array($order['order_message']) && count($order['order_message']) > 0):?>
        <div class="new_order_con fixfloat">
            <h2>会员留言<img width="7" class="arrow_hide" height="4" style="margin: 0pt 0pt 3px 10px;" src="/images/new_arrow_ico.gif"></h2>
			<?php if($order['order_status'] == '1' || $order['order_status'] == '2'):?>
            <span class="new_order_common_btn"><a href="javascript:void(0);" onclick="$('#order_message_add').dialog('open');">回复留言</a></span>
			<?php endif;?>
            <table>
                <thead>
                    <tr class="headings">
                        <th class="a_center">时间</th>
                        <th class="a_center">类型</th>
                        <th>内容</th>
                    </tr>
                </thead>
                <tbody id="productNode">
                        <?php foreach($order['order_message'] as $key=>$order_message) {?>
                    <tr>
                        <td><?php echo $order_message['date_add']?></td>
                        <td>
                                    <?php echo ($order_message['type'] == 0)?'用户留言':'管理员回复';?>
                        </td>
                        <td><?php echo $order_message['message']?></td>
                    </tr>
                            <?php }?>
                </tbody>
            </table>
        </div>
        <?php endif;?>
        <div class="new_order_con fixfloat">
            <h2>订单信息<img width="7" class="arrow_hide" height="4" style="margin: 0pt 0pt 3px 10px;cursor:pointer;" src="/images/new_arrow_ico.gif"></h2>
            <?php if($order['order_status'] == '1' || $order['order_status'] == '2'):?>
            <span class="new_order_common_btn">
                <a href="javascript:void(0);" onclick="$('#order_edit').dialog({autoOpen:true,width:'90%'});">编辑</a>
            </span>
            <?php endif;?>
            <table style="width:48%" class="fleft">
                <thead>
                    <tr class="headings">
                        <th colspan="2">订单详情</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="a_right a_title">订单号：</td>
                        <td class="a_left"><?php echo $order['order_num']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">下单时间：</td>
                        <td class="a_left"><?php echo $order['date_add']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">当前状态：</td>
                        <td class="a_left">
                            <?php echo (isset($order_status[$order['order_status']]))?$order_status[$order['order_status']]['name']:'未处理';?> -
                            <?php echo (isset($pay_status[$order['pay_status']]))?$pay_status[$order['pay_status']]['name']:'未支付';?>
                            【<?php echo (isset($ship_status[$order['ship_status']]))?$ship_status[$order['ship_status']]['name']:'未发货';?>】
                        </td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">E-Mail：</td>
                        <td class="a_left"><?php echo $order['email']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">IP/地址：</td>
                        <td class="a_left"><?php echo long2ip($order['ip'])?><span id="ip_country"></span></td>
                    </tr>
                    <?php if(!empty($order['payment_id'])):?>
                    <?php 
                    	$payment = Mypayment::instance($order['payment_id'])->get();
                    	$payment_type_id = !empty($payment['payment_type_id']) ? $payment['payment_type_id'] : 0;
                    	if(!empty($payment_type_id)){
                    		$payment_type = Mypayment_type::instance($payment_type_id)->get();
                    	}
                    	$payment_type_name = !empty($payment_type['name']) ? $payment_type['name'] : '';
                    ?>
                    <tr>
                        <td class="a_right a_title">支付方式：</td>
                        <td class="a_left"><?php echo $payment_type_name;?></td>
                    </tr>
                    <?php endif;?>
                    <tr>
                        <td class="a_right a_title">交易号：</td>
                        <td class="a_left"><?php echo $order['trans_id']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">物流方式：</td>
                        <td class="a_left"><?php echo $order['carrier']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">物流号：</td>
                        <td class="a_left"><?php echo $order['ems_num']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">订单备注：</td>
                        <td class="a_left">【<?php echo (isset($order_source[$order['order_source']]['name']))?$order_source[$order['order_source']]['name']:'';?>】</td>
                    </tr>
                </tbody>
            </table>
            <table style="width:48%" class="fright">
                <thead>
                    <tr class="headings">
                        <th width="120">收货人地址信息</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="a_right a_title">收货人:</td>
                        <td class="a_left"><?php echo $order['shipping_lastname']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">地址:</td>
                        <td class="a_left"><?php echo $order['shipping_address']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">城市:</td>
                        <td class="a_left"><?php echo $order['shipping_city']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">州/省:</td>
                        <td class="a_left"><?php echo $order['shipping_state']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">邮编:</td>
                        <td class="a_left"><?php echo $order['shipping_zip']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">电话:</td>
                        <td class="a_left"><?php echo $order['shipping_phone']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">移动电话:</td>
                        <td class="a_left"><?php echo $order['shipping_mobile']?></td>
                    </tr>
                    <tr>
                        <td class="a_right a_title">管理员备注:</td>
                        <td class="a_left" colspan="2"><?php echo $order['mark']?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="new_order_con fixfloat">
            <h2>订单历史状态<img width="7" class="arrow_hide" height="4" style="margin: 0pt 0pt 3px 10px;cursor:pointer;" src="/images/new_arrow_ico.gif"></h2>
            <table>
                <thead>
                    <tr class="headings">
                        <th class="a_center">时间</th>
                        <th class="a_center">状态</th>
                        <th class="a_center">发邮件</th>
                        <th class="a_center">操作人员</th>
                        <th>备注(管理员)</th>
                        <th>备注(会员)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($order['order_history'] as $key => $order_history): ?>
                    <tr>
                        <td class="a_center"><?php echo $order_history['date_add'];?></td>
                        <td class="a_center">
                                <?php if(isset($order_history['status_flag'])):?>
                                    <?php
                                    $status_flag = $order_history['status_flag'];
                                    switch ($status_flag) {
                                        case 'pay_status':
                                            echo (isset($pay_status[$order_history['pay_status']]))?$pay_status[$order_history['pay_status']]['name']:'未支付';
                                            break;
                                        case 'ship_status':
                                            echo (isset($ship_status[$order_history['ship_status']]))?$ship_status[$order_history['ship_status']]['name']:'未发货';
                                            break;
                                        case 'order_status':
                                            echo (!empty($order_history['order_status']) && (isset($order_status[$order_history['order_status']])))
                                            ?$order_status[$order_history['order_status']]['name']:'未处理';
                                            break;
                                        default:
                                            echo "未处理";
                                    }
                                    ?>
                                <?php endif;?>
                        </td>
                        <td class="a_center">
                            <?php echo view_tool::get_active_img($order_history['is_send_mail']);?>
                        </td>
                        <td class="a_center"><?php echo $order_history['manager_name'];?></td>
                        <td><?php echo nl2br($order_history['content_admin']);?></td>
                        <td><?php echo nl2br($order_history['content_user']);?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--**content end**-->
<!-- order_edit dialog start -->
<div class="dialog" id="order_edit" title="修改订单信息">
    <div class="out_box">
        <form id="order_edit_form" name="order_edit_form" method="post" action="<?php echo url::base().'order/order/do_edit_order/id/'.$order['id'];?>">
            <div class="col2_set">
                <div class="col_1 col_narrow">
                    <h3 class="title1_h3">订单详情</h3>
                    <table width="90%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <th> 订单号：</th>
                            <td><?php echo $order['order_num']?></td>
                        </tr>
                        <tr>
                            <th>下单时间：</th>
                            <td><?php echo $order['date_add']?></td>
                        </tr>
                        <tr>
                            <th>当前状态：</th>
                            <td>
                                <?php echo (isset($order_status[$order['order_status']]))?$order_status[$order['order_status']]['name']:'未处理';?>
                                - <?php echo (isset($pay_status[$order['pay_status']]))?$pay_status[$order['pay_status']]['name']:'未支付';?>
                                【<?php echo (isset($ship_status[$order['ship_status']]))?$ship_status[$order['ship_status']]['name']:'未发货';?>】
                            </td>
                        </tr>
                        <tr>
                            <th>Email：</th>
                            <td  class="d_line">
                                <input size="30" name="email" class="text email required" value="<?php echo $order['email']?>">
                                <span class="required">*</span>
                            </td>
                        </tr>
                        <tr>
                            <th>IP/地址：</th>
                            <td><?php echo long2ip($order['ip'])?></td>
                        </tr>
                        <tr>
                            <th>交易号：</th>
                            <td><input size="30" name="trans_id" class="text " value="<?php echo $order['trans_id']?>"></td>
                        </tr>
                        <tr>
                            <th>EMS号：</th>
                            <td><input size="30" name="ems_num" class="text " value="<?php echo $order['ems_num']?>"></td>
                        </tr>
                        <tr>
                            <th class="last">订单备注：</th>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </div>
                <div class="col_2 col_wide">
                    <h3 class="title1_h3">用户收货地址信息</h3>
                    <table width="90%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <th>收货人名称：</th>
                            <td class="d_line">
                                <input size="30" name="shipping_lastname" class="text required" value="<?php echo $order['shipping_lastname']?>">
                                <span class="required"> *</span>
                            </td>
                        </tr>
                        <tr>
                            <th >电话：</th>
                            <td class="d_line">
                                <input size="30" name="shipping_phone" class="text required" value="<?php echo $order['shipping_phone']?>">
                                <span class="required"> *</span>
                            </td>
                        </tr>
                        <tr>
                            <th >移动电话：</th>
                            <td class="d_line">
                                <input size="30" name="shipping_mobile" class="text" value="<?php echo $order['shipping_mobile']?>">
                            </td>
                        </tr>
                        <tr>
                            <th >市：</th>
                            <td class="d_line">
                                <input size="30" name="shipping_city" class="text" value="<?php echo $order['shipping_city']?>">
                                <span class="required"> *</span>
                            </td>
                        </tr>
                        <tr>
                            <th >州/省：</th>
                            <td class="d_line">
                                <input size="30" name="shipping_state" class="text" value="<?php echo $order['shipping_state']?>">
                            </td>
                        </tr>
                        <tr>
                            <th >邮编：</th>
                            <td class="d_line">
                                <input size="30" name="shipping_zip" class="text required" value="<?php echo $order['shipping_zip']?>">
                                <span class="required"> *</span>
                            </td>
                        </tr>
                        <tr>
                            <th >地址：</th>
                            <td class="d_line">
                                <input size="60" name="shipping_address" class="text required" value="<?php echo $order['shipping_address']?>">
                                <span class="required"> *</span>
                            </td>
                        </tr>
                        <tr>
                        	<th >管理员备注：</th>
                            <td class="d_line" colspan="2">
                                <textarea class="text required" name="mark" cols="60" rows="3" type="textarea" maxlength="1024"><?php echo $order['mark']?></textarea>
                                <span class="required"> *</span>
                            </td>
                        </tr>
                        <tr>
                        	<th >是否发送邮件：</th>
                            <td colspan="2">
                                <input type="radio" name="is_receive" value="0" checked> 不发送<input type="radio" name="is_receive" value="1"> 发送</td>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="clear">&nbsp;</div>
            <div class="list_save">
                <input name="create_all_goods" type="submit" class="ui-button" value=" 保存 "/>
                <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='$("#order_edit").dialog("close");'/>
            </div>
        </form>
    </div>
</div>
<!-- order_edit dialog end -->
<!-- order_message dialog start -->
<div class="dialog" id="order_message_add" title="回复订单留言">
    <div class="out_box">
        <form id="order_message_add_form" name="order_message_add_form" method="post" action="<?php echo url::base().'order/order/do_add_order_message/id/'.$order['id'];?>">
            <table width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                    <tr>
                        <th>回复信息<span class="required"> *</span>：</th>
                        <td  class="d_line">
                            <textarea class="text required" rows="5" cols="60" name="order_message" maxlength="1000"></textarea>
                            <span class="brief-input-state notice_inline">请不要超过1000字节。</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="list_save">
                <input name="create_all_goods" type="submit" class="ui-button" value=" 回复 "/>
                <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='$("#order_message_add").dialog("close");'/>
            </div>
        </form>
    </div>
</div>
<!-- order_message dialog end -->
<div id='example' style="display:none;"></div>
<div id='order_product' style="display:none;"></div>
<div id='order_ship' style="display:none;" title="订单发货"></div>
<div id='order_returned_goods' style="display:none;" title="退货"></div>
<div id='order_refund' style="display:none;" title="订单退款"></div>
<div id='order_status' style="display:none;" title="更新订单状态"></div>
<div id='order_pay' style="display:none;" title="支付"></div>
<div id="order_product_edit" style="display:none;" title="订单商品信息">
</div>
<div id="product_relation_ifm" style="display:none;">
	<iframe style="border:0px;width:100%;height:98%;" frameborder="0" src="" scrolling="auto"></iframe>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/order_edit.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/message.js"></script>
<script type="text/javascript">
current_good_ids = {};
$(document).ready(function(){
    //获取IP详情
    $.ajax({
        type: "POST",
        url: "/order/ip/get_ip_country",
        //dataType: 'json',
        data: "ip=<?php echo long2ip($order['ip']);?>",
        success: function(data){
            $("#ip_country").html(data);
        }
    })

    //添加商品
    $('#product_manage').click(function(){
        var ifm = $('#product_relation_ifm');
		ifm.find('iframe').attr('src', '/order/order_product/add?order_id=' + $('#order_id').val());
		ifm.dialog('open');
	});

	// 相关商品设置窗口
    $('#product_relation_ifm').dialog({
		title: '添加商品',
		modal: true,
		autoOpen: false,
		height: 480,
		width: '90%'
    });

    //删除订单商品
    $("a.act_dodelete").unbind().bind('click keyup',function(e){
    	obj = $(this);
    	var trs = $("#goods_info").find('a.act_dodelete');
    	if (trs.length == 1) {
			showMessage('删除失败', '<font color="#990000">至少需要保留一个商品！</font>');
			return false;
		}
    	
        confirm('确认要删除此项?',function(){
            location.href = obj.attr('href');
        });
        return false;
    });

    $("a.product_not_exist").click(function(){
    	showMessage('操作失败', '<font color="#990000">此商品已不存在！</font>');
    	//return false;
    });  
});
//ajax修改物流费用
var default_shipping = '<?php echo round($order['total_shipping']/$order['conversion_rate'],2);?>';
$('input[name=order_shipping]').focus(function(){
    $('.new_float').hide();
    default_order = $(this).val();
    $(this).next().show();
    $(this).next().children('input[name=final_shipping]').focus();
});
$('input[name=cancel_order_form]').click(function(){
    $(this).parent().hide();
});
$('input[name=submit_order_form]').click(function(){
    var url = '<?php echo url::base();?>order/order/set_shipping';
    var obj = $(this).parent();
    var order_id = $('#order_id').val();
    var shipping = $(this).prev().val();
    $(this).parent().hide();
    if(shipping == default_shipping){
        return false;
    }
    obj.prev().attr('disabled','disabled');
    $.ajax({
        type:'GET',
        dataType:'json',
        url:url,
        data:'order_id='+order_id+'&shipping='+shipping,
        error:function(){},
        success:
            function(retdat,status){
            obj.prev().removeAttr('disabled');
            if(retdat['status'] == 1 && retdat['code'] == 200)
            {
            	default_shipping = shipping;
                obj.prev().attr('value',(retdat['content']['total_shipping']));
                $('#total_price').html(retdat['content']['total']);
            }else{
            	showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
            }
        }
    });
});
</script>