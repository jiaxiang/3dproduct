<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑支付</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--** edit start**-->
            <div class="edit_area">
                <div class="out_box" id="tabs">
                    <ul>
                        <?php  foreach ($payment_type_list as $key=>$value) { ?>
                        <li><a href="#tabs-<?php echo $key ;?>"><?php echo $value ;?></a></li>
                        <?php }?>
                    </ul>
                    <div id="tabs-1">
                        <form id="add_form1" name="add_form" method="post" action="<?php echo url::base() . 'manage/payment/do_edit/1/' . $data['id'];?>">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="25%">提交地址： </th>
                                        <td><input size="30" name="submit_url" class="text t400 _x_ipt required" readonly="readonly" value="<?php echo arr::get_val_by_kvk('id', '1', 'submit_url', $payment_types);?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>当前可用：</th>
                                        <td><label>
                                                <input type="radio" name="active" value="1" checked>
										可用
                                                <input type="radio" name="active" value="0" <?php (empty($data['active'])||$data['active']==0)&&print('checked');?>>
										不可用 </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div id="tabs-2">
                        <form id="add_form2" name="add_form" method="post" action="<?php echo url::base() . 'manage/payment/do_edit/2/' . $data['id'];?>">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="25%">提交地址： </th>
                                        <td><input size="30" name="submit_url" class="text t400 _x_ipt required" readonly="readonly" value="<?php echo arr::get_val_by_kvk('id', '2', 'submit_url', $payment_types);?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>当前可用：</th>
                                        <td><label>
                                                <input type="radio" name="active" value="1" checked>
										可用
                                                <input type="radio" name="active" value="0" <?php (empty($data['active'])||$data['active']==0)&&print('checked');?>>
										不可用 </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div id="tabs-3">
                        <form id="add_form3" name="add_form" method="post" action="<?php echo url::base() . 'manage/payment/do_edit/3/' . $data['id'];?>">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="25%">提交地址： </th>
                                        <td><input size="30" name="submit_url" class="text t400 _x_ipt required" readonly="readonly" value="<?php echo arr::get_val_by_kvk('id', '3', 'submit_url', $payment_types);?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Paypal账号： </th>
                                        <td><input size="30" name="account" class="text t400 required" value="<?php echo $data['account'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>当前可用：</th>
                                        <td><label>
                                                <input type="radio" name="active" value="1" checked>
										可用
                                                <input type="radio" name="active" value="0" <?php (empty($data['active'])||$data['active']==0)&&print('checked');?>>
										不可用 </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div id="tabs-4">
                        <form id="add_form4" name="add_form" method="post" action="<?php echo url::base() . 'manage/payment/do_edit/4/' . $data['id'];?>">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="25%">提交地址： </th>
                                        <td><input size="30" name="submit_url" class="text t400 _x_ipt required" readonly="readonly" value="<?php echo arr::get_val_by_kvk('id', '4', 'submit_url', $payment_types);?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Paypal账号： </th>
                                        <td><input size="30" name="account" class="text t400 required" value="<?php echo $data['account'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>当前可用：</th>
                                        <td><label>
                                                <input type="radio" name="active" value="1" checked>
										可用
                                                <input type="radio" name="active" value="0" <?php (empty($data['active'])||$data['active']==0)&&print('checked');?>>
										不可用 </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div id="tabs-5">
                        <form id="add_form5" name="add_form" method="post" action="<?php echo url::base() . 'manage/payment/do_edit/5/' . $data['id'];?>">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="25%">提交地址： </th>
                                        <td><input size="30" name="submit_url" class="text t400 _x_ipt required" readonly="readonly" value="<?php echo arr::get_val_by_kvk('id', '5', 'submit_url', $payment_types);?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Paypal登陆跳转地址： </th>
                                        <td><input size="30" name="jump_url" class="text t400 _x_ipt required" value="https://www.paypal.com/cgi-bin/webscr"  readonly="readonly">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Paypal账号： </th>
                                        <td><input size="30" name="account" class="text t400 required" value="<?php echo $data['account'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Paypal密码： </th>
                                        <td><input size="30" name="passwd" class="text t400 required" value="<?php echo $data['paypalec']['passwd'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Paypal证书： </th>
                                        <td><input size="30" name="signature" class="text t400 required" value="<?php echo $data['paypalec']['signature'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">版本号： </th>
                                        <td><input size="30" name="version" class="text t400 required" value="<?php echo $data['paypalec']['version'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>当前可用：</th>
                                        <td><label>
                                                <input type="radio" name="active" value="1" checked>
										可用
                                                <input type="radio" name="active" value="0" <?php (empty($data['active'])||$data['active']==0)&&print('checked');?>>
										不可用 </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div id="tabs-6">
                        <form id="add_form6" name="add_form" method="post" action="<?php echo url::base() . 'manage/payment/do_edit/6/' . $data['id'];?>">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="25%">提交地址： </th>
                                        <td><input size="30" name="submit_url" class="text t400 _x_ipt required" readonly="readonly" value="<?php echo arr::get_val_by_kvk('id', '6', 'submit_url', $payment_types);?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Paypal登陆跳转地址： </th>
                                        <td><input size="30" name="jump_url" class="text t400 _x_ipt required" value="https://www.sandbox.paypal.com/cgi-bin/webscr"  readonly="readonly">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Paypal账号： </th>
                                        <td><input size="30" name="account" class="text t400 required" value="<?php echo $data['account'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Paypal密码： </th>
                                        <td><input size="30" name="passwd" class="text t400 required" value="<?php echo $data['paypalec']['passwd'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Paypal证书： </th>
                                        <td><input size="30" name="signature" class="text t400 required" value="<?php echo $data['paypalec']['signature'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">版本号： </th>
                                        <td><input size="30" name="version" class="text t400 required" value="<?php echo $data['paypalec']['version'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>当前可用：</th>
                                        <td><label>
                                                <input type="radio" name="active" value="1" checked>
										可用
                                                <input type="radio" name="active" value="0" <?php (empty($data['active'])||$data['active']==0)&&print('checked');?>>
										不可用 </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div id="tabs-7">
                        <form id="add_form7" name="add_form" method="post" action="<?php echo url::base() . 'manage/payment/do_edit/7';?>">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="25%">提交地址： </th>
                                        <td><input size="30" name="submit_url" class="text t400 _x_ipt required" readonly="readonly" value="<?php echo arr::get_val_by_kvk('id', '7', 'submit_url', $payment_types);?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">Google checkout 商家ID号： </th>
                                        <td><input size="30" name="account" class="text t400 required" value="<?php echo $data['account'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>当前可用：</th>
                                        <td><label>
                                                <input type="radio" name="active" value="1" checked>
										可用
                                                <input type="radio" name="active" value="0" <?php (empty($data['active'])||$data['active']==0)&&print('checked');?>>
										不可用 </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div id="tabs-8">
                        <form id="add_form8" name="add_form" method="post" action="<?php echo url::base() . 'manage/payment/do_edit/8/' . $data['id'];?>">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="25%">提交地址： </th>
                                        <td><input size="30" name="submit_url" class="text t400 _x_ipt required" readonly="readonly" value="<?php echo arr::get_val_by_kvk('id', '8', 'submit_url', $payment_types);?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">商户号： </th>
                                        <td><input size="30" name="account" class="text required" value="<?php echo $data['account'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                     <tr>
                                        <th width="25%">终端号： </th>
                                        <td><input size="30" name="terminalid" class="text required" value="<?php echo $data['motopay']['terminalid'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">传输密钥： </th>
                                        <td><input size="30" name="key" class="text required" value="<?php echo $data['motopay']['key'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>当前可用：</th>
                                        <td><label>
                                                <input type="radio" name="active" value="1" checked>
										可用
                                                <input type="radio" name="active" value="0" <?php (empty($data['active'])||$data['active']==0)&&print('checked');?>>
										不可用 </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div id="tabs-9">
                        <form id="add_form9" name="add_form" method="post" action="<?php echo url::base() . 'manage/payment/do_edit/9/' . $data['id'];?>">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="25%">提交地址： </th>
                                        <td><input size="30" name="submit_url" class="text t400 _x_ipt required" readonly="readonly" value="<?php echo arr::get_val_by_kvk('id', '9', 'submit_url', $payment_types);?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">商户号： </th>
                                        <td><input size="30" name="account" class="text required" value="<?php echo $data['account'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                     <tr>
                                        <th width="25%">终端号： </th>
                                        <td><input size="30" name="terminalid" class="text required" value="<?php echo $data['motopay']['terminalid'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">传输密钥： </th>
                                        <td><input size="30" name="key" class="text required" value="<?php echo $data['motopay']['key'];?>"><span class="required"> *</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>当前可用：</th>
                                        <td><label>
                                                <input type="radio" name="active" value="1" checked>
										可用
                                                <input type="radio" name="active" value="0" <?php (empty($data['active'])||$data['active']==0)&&print('checked');?>>
										不可用 </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                        
                    <div id="tabs-10">
                        <form id="add_form10" name="add_form" method="post" action="<?php echo url::base() . 'manage/payment/do_edit/10/' . $data['id'];?>">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <th width="25%">提交地址： </th>
                                        <td><input size="100" name="submit_url" class="text required" readonly="readonly" value="<?php echo arr::get_val_by_kvk('id', '10', 'submit_url', $payment_types);?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">签约支付宝账号： </th>
                                        <td><input size="30" name="account" class="text required" value="<?php echo $data['account'];?>"><span class="required"> *</span> (卖家支付宝帐户)
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">合作身份者ID： </th>
                                        <td><input size="30" name="partner" class="text required" value="<?php echo isset($data['args']['partner'])?$data['args']['partner']:'';?>"><span class="required"> *</span> (以2088开头的16位纯数字)
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="25%">安全检验码： </th>
                                        <td><input size="30" name="key" class="text required" value="<?php echo $data['args']['key'];?>"><span class="required"> *</span> (以数字和字母组成的32位字符)
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>当前可用：</th>
                                        <td>
                                            <input type="radio" name="active" value="1" checked>可用
                                            <input type="radio" name="active" value="0" <?php (empty($data['active'])||$data['active']==0)&&print('checked');?>>不可用
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                        
                </div>
            </div>
            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div id="footer">
    <div class="bottom">
        <div class="Turnpage_leftper">
        </div>
        <!--end of div class Turnpage_leftper-->
        <div class="Turnpage_rightper">
        </div>
        <!--end of div class Turnpage_rightper-->
    </div>
</div>
<!--END FOOTER-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form1").validate();
        $("#add_form2").validate();
        $("#add_form3").validate();
        $("#add_form4").validate();
        $("#add_form5").validate();
        $("#add_form6").validate();
        $("#add_form7").validate();
        $("#add_form8").validate();
        $("#add_form9").validate();
        // Tabs
        $('#tabs').tabs({ selected:<?php echo ($data['payment_type_id'] - 1);?>});
<?php foreach ($payment_type_list as $key=>$value) {?>
    <?php if ($key != $data['payment_type_id']) {?>
            $('#tabs').tabs('disable',<?php echo ($key - 1);?>);
        <?php }
}
?>

    });
</script>
