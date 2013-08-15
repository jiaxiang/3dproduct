<?php defined('SYSPATH') or die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">第三步 后台生成订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**order add start**-->
            <div class="tableform">
                <form id="add_form" name="add_form" method="post" action="<?php echo url::base()?>order/order_add/do_add">
                    <input type="hidden" name="user_id" value="<?php echo $user_id?>">
                    <input type="hidden" name="email" value="<?php echo $email?>">
                    <div class="out_box">
                        <h3 class="title1_h3">用户shipping地址信息</h3>
                        <table border="0" cellpadding="0" cellspacing="0" >
                            <thead >
                                <tr>
                                    <th width="30%">名：</th>
                                    <td>
                                        <input class="text required" type="text" id="shipping_firstname" name="shipping_firstname" value="<?php if(isset($address_info['firstname'])) echo $address_info['firstname'];?>">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                            </thead>
                            <thead >
                                <tr>
                                    <th>姓：</th>
                                    <td>
                                        <input class="text required" type="text" id="shipping_lastname" name="shipping_lastname" value="<?php if(isset($address_info['lastname'])) echo $address_info['lastname'];?>">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                            </thead>
                            <thead >
                                <tr>
                                    <th>地址：</th>
                                    <td>
                                        <input size="60" class="text required" type="text" id="shipping_address" name="shipping_address" value="<?php if(isset($address_info['address'])) echo $address_info['address'];?>">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                            </thead>
                            <thead >
                                <tr>
                                    <th>市：</th>
                                    <td>
                                        <input class="text required" type="text" id="shipping_city" name="shipping_city" value="<?php if(isset($address_info['city'])) echo $address_info['city'];?>">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                            </thead>
                            <thead >
                                <tr>
                                    <th>省/州：</th>
                                    <td>
                                        <input class="text" type="text" id="shipping_state" name="shipping_state" value="<?php if(isset($address_info['state'])) echo $address_info['state'];?>">
                                    </td>
                                </tr>
                            </thead>
                            <thead >
                                <tr>
                                    <th>国家：</th>
                                    <td>
                                        <select class="text" id="shipping_country" name="shipping_country">
                							<?php foreach ($country_list as $key => $country):?>
                                            	<option value="<?php echo $country['id']?>" <?php if(isset($address_info['country']) && $country['iso_code'] == $address_info['country']):?> selected<?php endif;?>><?php echo $country['name']?></option>
                                            <?php endforeach;?>
                                        </select>
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>邮编：</th>
                                    <td>
                                        <input class="text required" type="text" id="shipping_zip" name="shipping_zip" value="<?php if(isset($address_info['zip'])) echo $address_info['zip'];?>">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>电话：</th>
                                    <td>
                                        <input size="30" class="text required" type="text" id="shipping_phone" name="shipping_phone" value="<?php if(isset($address_info['phone'])) echo $address_info['phone'];?>">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>移动电话：</th>
                                    <td>
                                        <input size="30" class="text" type="text" id="shipping_mobile" name="shipping_mobile" value="<?php if(isset($address_info['phone_mobile'])) echo $address_info['phone_mobile'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>说明：</th>
                                    <td><label><input type="checkbox" id="billing_controller_box" checked>使用shipping地址作为billing地址</label></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="out_box" id="billing_tr" style="display:none">
                        <h3 class="title1_h3">用户billing地址信息</h3>
                        <table border="0" cellpadding="0" cellspacing="0" >
                            <thead >
                                <tr>
                                    <th width="30%">名:</th>
                                    <td>
                                        <input class="text" type="text" id="billing_firstname" name="billing_firstname">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                            </thead>
                            <thead >
                                <tr>
                                    <th>姓:</th>
                                    <td>
                                        <input class="text" type="text" id="billing_lastname" name="billing_lastname">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                            </thead>
                            <thead >
                                <tr>
                                    <th>地址:</th>
                                    <td>
                                        <input size="60" class="text" type="text" id="billing_address" name="billing_address">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                            </thead>
                            <thead >
                                <tr>
                                    <th>市:</th>
                                    <td>
                                        <input class="text" type="text" id="billing_city" name="billing_city">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                            </thead>
                            <thead >
                                <tr>
                                    <th>省/州:</th>
                                    <td>
                                        <input class="text" type="text" id="billing_state" name="billing_state">
                                    </td>
                                </tr>
                            </thead>
                            <thead >
                                <tr>
                                    <th>国家:</th>
                                    <td>
                                        <select class="text" id="billing_country" name="billing_country">
                							<?php foreach ($country_list as $key => $country):?>
                                            	<option value="<?php echo $country['id']?>" <?php if(isset($address_info['country']) && $country['iso_code'] == $address_info['country']):?> selected<?php endif;?>><?php echo $country['name']?></option>
                                            <?php endforeach;?>
                                        </select>
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>邮编:</th>
                                    <td>
                                        <input class="text" type="text" id="billing_zip" name="billing_zip">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>电话:</th>
                                    <td>
                                        <input size="30" class="text" type="text" id="billing_phone" name="billing_phone">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>移动电话:</th>
                                    <td><input size="30" class="text" type="text" id="billing_mobile" name="billing_mobile"></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                                <tr>
                                    <th>用户邮箱</th>
                                    <td><?php echo $email?></td>
                                </tr>
                                <tr>
                                    <th>购买货品信息</th>
                                    <td>
                                        <table id="good_info_box" cellspacing="0" cellpadding="0" border="0" width="100%" style="border:1px solid #efefef;">
                                            <tr>
                                                <th class="cell span-4" style="text-align:left"><b>货品SKU</b></th>
                                                <th class="cell span-5" style="text-align:left"><b>货品名称</b></th>
                                                <th class="cell span-3" style="text-align:left"><b>价格</b></th>
                                                <th class="cell span-3" style="text-align:left"><b>数量</b></th>
                                            </tr>
                                            <?php foreach ($good_info as $good):?>
                                            <tr>
                                                <td><?php echo $good['sku']?></td>
                                                <td><?php echo $good['title']?></td>
                                                <td><?php echo $good['price']?></td>
                                                <td><?php echo $good['cart_num'];?></td>
                                            </tr>
                                            <?php endforeach;?>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <th>货品总金额（USD）</th>
                                    <td>
                                        <input class="text required number" size="40" name="good_price" maxlength="20" value="<?php echo $good_price?>" min="0">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>运费金额（USD）</th>
                                    <td>
                                        <input class="text required number" size="40" name="shipping_price" maxlength="20" value="<?php echo $shipping_price?>" min="0">
                                        <span class="required">*</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>选择物流方式</th>
                                    <td>
                                        <select name="carrier" id="carrier" class="text">
                                            <?php foreach ($carrier_list as $carrier):?>
                                            	<option value="<?php echo $carrier?>"><?php echo $carrier?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </td>
                                </tr>                                
                                <tr>
                                    <th>选择支付币种</th>
                                    <td>
                                        <select name="code" id="code" class="text">
                                            <?php foreach ($currency_info as $currency):?>
                                            	<option value="<?php echo $currency['code']?>"><?php echo $currency['code']?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="list_save">
                        <input name="dosubmit" type="button" class="ui-button" value="上一步" onclick="javascript:location.href = '<?php echo url::base();?>order/order_add/add_next'" />
                        <input id="form_submit" name="submit" type="submit" value="完成" class="ui-button">
                    </div>
                </form>
            </div>
            <!--**order add end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script src="<?php echo url::base();?>js/jquery.validate.js" type="text/javascript"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $("#add_form").validate();
        $("#billing_controller_box").click(function(){
            if($(this).attr('checked')){
                $("#billing_tr").css('display', 'none');
                $(this).attr('checked', true);
                $('#billing_firstname,#billing_lastname,#billing_address,#billing_city,#billing_country,#billing_zip,#billing_phone').removeClass('text required');
            }else{
                $("#billing_tr").css('display', '');
                $(this).attr('checked', false);
                $('#billing_firstname,#billing_lastname,#billing_address,#billing_city,#billing_country,#billing_zip,#billing_phone').addClass('text required');
            }
        });
        $("#form_submit").click(function(){
            if($("#billing_controller_box").attr('checked'))
            {
                $('#billing_firstname').val($('#shipping_firstname').val());
                $('#billing_lastname').val($('#shipping_lastname').val());
                $('#billing_country').val($('#shipping_country').val());
                $('#billing_state').val($('#shipping_state').val());
                $('#billing_city').val($('#shipping_city').val());
                $('#billing_address').val($('#shipping_address').val());
                $('#billing_zip').val($('#shipping_zip').val());
                $('#billing_phone').val($('#shipping_phone').val());
                $('#billing_mobile').val($('#shipping_mobile').val());
            }
        });
    });

</script>