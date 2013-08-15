<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加币种</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th>名称：</th>
                                    <td>
                                        <input type="hidden" name="name" id="currency_name_val">
                                        <select name="currency_name" class="required text" id="currency_name" onchange="change_currency();">
                                            <?php
                                            if (isset($currency_name) && is_array($currency_name)) {?>
                                                <?php foreach ($currency_name as $key=>$value) {?>
                                                    <?php if(!empty($value)) {?>
                                            <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                                        <?php }?>
                                                    <?php }//end of foreach ?>
                                                <?php }//end of if ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>代码：</th>
                                    <td>
                                        <span id="currency_code"></span>
                                        <input type="hidden" name="code" id="currency_code_val">
                                    </td>
                                </tr>
                                <tr>
                                    <th>符号：</th>
                                    <td>
                                        <span id="currency_sign"></span>
                                        <input type="hidden" name="sign" id="currency_sign_val">
                                    </td>
                                </tr>
                                <tr>
                                    <th>格式：</th>
                                    <td>
                                        <select name="format"  class="required text">
                                            <?php
                                            if (isset($currency_format) && is_array($currency_format)) {?>
                                                <?php foreach ($currency_format as $key=>$value) {?>
                                            <option value="<?php echo $key;?>" ><?php echo $value;?></option>
                                                    <?php }//end of foreach ?>
                                                <?php }//end of if ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>小数位：</th>
                                    <td>
                                        <input type="text" name="decimals" id="currency_decimals_val" value="2" class="text" size="2" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <th>汇率：</th>
                                    <td>
                                        <input size="20" name="conversion_rate" class="text required" id="currency_rate"><span class="required"> * </span> 1当前币种= ?美元
                                    </td>
                                </tr>
                                <tr>
                                    <th>是否默认显示：</th>
                                    <td>
                                        <input type="radio" name="default" value="1">
                                        默认
                                        <input type="radio" name="default" value="0" checked>
                                        非默认
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input type="button" name="button" class="ui-button" value="保存返回列表"  onclick="submit_form();"/>
                    <input type="button" name="button" class="ui-button" value="保存"  onclick="submit_form(1);"/>
                    <input type="hidden" name="submit_target" id="submit_target" value="0" />
                </div>
            </form>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    var currency = <?php echo $currency_data?$currency_data:'';?>;
    $(document).ready(function(){
        $("#add_form").validate();
        change_currency();
    });
    function change_currency(){
        var currency_code = $('#currency_name').val();
        //var currency_name = eval('currency.name.' + currency_code);
        $('#currency_code').html(currency_code);
        $('#currency_sign').html(eval('currency.sign.' + currency_code));
        $('#currency_rate').val(eval('currency.rate.' + currency_code));

        $('#currency_code_val').val(currency_code);
        $('#currency_sign_val').val(eval('currency.sign.' + currency_code));
        $('#currency_rate_val').val(eval('currency.rate.' + currency_code));
        $('#currency_name_val').val(eval('currency.name.' + currency_code));
    }
</script>
