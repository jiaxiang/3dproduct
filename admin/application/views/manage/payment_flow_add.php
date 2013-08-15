<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加支付方式</li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**category add start**-->
            <div class="edit_area">
                <form id="add_form" name="add_form" method="post" action="<?php echo url::base();?>manage/payment/do_flow_add" enctype="multipart/form-data">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <?php foreach ($payment_type_list as $key=>$value) {?>
                                    <?php switch ($key) {
                                        case '1':
                                            ?>
                                <tr>
                                    <th width="2%"><input type="checkbox" name="payment_type_id[]" value="<?php echo $key ;?>" class="text" /></th><td><?php echo $value ;?></td>
                                </tr>
                                            <?php			break;
                                        case '3':
                                            ?>
                                <tr>
                                    <th width="2%"><input type="checkbox" name="payment_type_id[]" id="pp" value="<?php echo $key ;?>" class="text" checked/></th><td><?php echo $value ;?></td>
                                </tr>
                                            <?php			break;
                                        case '2':
                                        case '7':
                                            ?>
                                <tr>
                                    <th width="2%"><input type="checkbox" name="payment_type_id[]" value="<?php echo $key ;?>" class="text" disabled="true"/></th><td><?php echo $value ;?></td>
                                </tr>
                                            <?php			break;
                                            ?>
                                        <?php } ?>
                                    <?php switch ($key) {
                                        case '1':
                                        case '2':
                                            break;
                                        case '3':
                                            ?>
                                <tr id="pp_account">
                                    <th width="2%"></th>
                                    <td colspan="8" align="left">
						请填写您的Paypal账号: <input type="text" size="40" name="account" id="account" value="" class="text my_required required email" />
                                    </td>
                                </tr>
                                            <?php			break;
                                        case '5':
                                        case '6':
                                        case '7':
                                            break;
                                            ?>
                                        <?php } ?>
                                    <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <div class="btn_eidt">
                        <table width="445" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="293">
                                    <input type="submit" class="ui-button" value="下一步" id="form_submit"/>
                                    <input type="button" class="ui-button" value="取消" onclick="javascript:history.back();" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
            <!--**category add end**-->
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
        $('#pp').click(function(){
            $('#pp_account').toggle();
            if($('#pp_account').css('display') == 'none'){
                $(".my_required").removeClass('required');
                $(".my_required").removeClass('email');
            }else{
                $(".my_required").addClass('required');
                $(".my_required").addClass('email');
            }
        });

        $('#form_submit').click(function(){
            var payment_type_id = $("input[name='payment_type_id[]']:checked").val();
            if(payment_type_id == undefined || payment_type_id == ''){
                alert("请至少选择一种支付方式");
                return false;
            }
            return true;
        });
    });
</script>

<!--**content end**-->
