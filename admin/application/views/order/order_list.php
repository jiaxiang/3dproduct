<script type="text/javascript">
    $(function() {
        //导出订单事件
        $("#do_export").click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要导出的订单！');
                return false;
            }
            $('#order_export_content').dialog("open");
        });
        //批量配货
        $('#shipping_processing').click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要配货的订单！');
                return false;
            }
            $('#shipping_processing_dialog').dialog("open");
        });
        //批量废除订单
        $('#order_cancel').click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要废除的订单！');
                return false;
            }
            $("#order_cancel_content").dialog("open");
        });
        //编辑导出事件
        $("#edit_export").click(function(){
            var order_export_id = $("select[name=order_export_id]").val();
            $('#edit_export').attr('href','<?php echo url::base();?>order/order_export/index/'+order_export_id);
            $('#edit_export').click();
            return false;
        });
        //批量处理时间
        $("#do_batch").click(function(){
            var order_status = $("#batch_order_status").val();
            $('#list_form').attr('action','<?php echo url::base();?>order/order/do_batch_edit_order_status/'+order_status);
            $('#list_form').submit();
            return false;
        });
        /* 高级搜索 */
        $("#advance_option").click(function(){
            $("#advance_search").dialog("open");
        });
        // Dialog
        $('#advance_search').dialog({
            autoOpen: false,
            modal: true,
            width: 600,
            height:300
        });
        //更新订单状态
        $('#order_cancel_content').dialog({
            autoOpen: false,
            modal: true,
            width: 800,
            height:350
        });
        //订单导出
        $('#order_export_content').dialog({
            autoOpen: false,
            modal: true,
            width: 600,
            height:300
        });
        //批量配货
        $('#shipping_processing_dialog').dialog({
            autoOpen: false,
            modal: true,
            width: 600,
            height:300
        });
    });
    function order_cancel(){
        $('#content_user_batch').val($('#content_user').val());
        $('#content_admin_batch').val($('#content_admin').val());
        $('#list_form').attr('action','<?php echo url::base();?>order/order/batch_order_status/cancel');
        $('#list_form').submit();
    }
    //导出订单
    function order_export(){
        var order_export_id = $('input[name=export_type]:checked').val();
        $('#list_form').attr('action','<?php echo url::base();?>order/order_export/do_export/'+order_export_id);
        $('#list_form').submit();
        $("#order_export_content").dialog("close");
        return false;
    }
    //批量配货
    function shipping_processing(){
        $('#content_user_batch').val($('#content_user').val());
        $('#content_admin_batch').val($('#content_admin').val());
        $('#list_form').attr('action','<?php echo url::base();?>order/order/batch_order_status/shipping_processing');
        $('#list_form').submit();
    }
</script>
<script type="text/javascript">
    $(function() {
        $("#date_begin").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:"yy-mm-dd"
        });
        $("#date_end").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:"yy-mm-dd"
        });
        /* 批量操作 */
        $(".pro_oper .down").hover(function(){
            $(this).addClass("on");
            $(this).children("ul").show();
        }, function(){
            $(this).removeClass("on");
            $(this).children("ul").hide();
        });
    });
</script>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <?php foreach($quick_search as $key=>$value):?>
                <li <?php echo $value['class'];?>><a href='<?php echo url::base() . 'order/order/index/' .$key;?>'><?php echo $value['name'];?></a></li>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <?php echo role::view_check('<li><a href="javascript:void(0);" id="do_export"><span class="batch_pro">导出订单</span></a></li>', 'order_export');?>

                <li class="down" id="export_product"><span class="batch_pro left">批量操作</span><span class="down_arrow left"></span>
                    <ul class="level_2">
                        <li><a id="shipping_processing" href="javascript:void(0)">批量配货</a></li>
                        <?php /* 可显示废除按钮的状态 */ ?>
                        <?php if(in_array($status,$show_cancel_btn_quick_search)):?>
                            <?php echo role::view_check('<li><a href="javascript:void(0);" id="order_cancel">批量废除</a></li>', 'order_edit');?>
                        <?php endif;?>
                    </ul>
                </li>
            </ul>
            <form action="/order/order" method="GET" name="search_form" class="new_search" id="search_form">
                <input type="hidden" value="0" id="adv_bar_nor" name="adv_bar">
                <div>搜索:
                    <select name="search_type" class="text">
                        <option value="order_num" <?php if ($where['search_type'] == 'order_num')echo "SELECTED";?>>订单号</option>
                        <option value="email" <?php if ($where['search_type'] == 'email')echo "SELECTED";?>>Email</option>
                        <option value="trans_id" <?php if ($where['search_type'] == 'trans_id')echo "SELECTED";?>>交易号</option>
                        <option value="shipping_lastname" <?php if ($where['search_type'] == 'shipping_lastname')echo "SELECTED";?>>收货人</option>
                        <option value="shipping_phone" <?php if ($where['search_type'] == 'shipping_phone')echo "SELECTED";?>>电话</option>
                        <option value="shipping_mobile" <?php if ($where['search_type'] == 'shipping_mobile')echo "SELECTED";?>>移动电话</option>
                    </select>
                    
                    <input type="text" name="search_value" class="text" value="<?php echo $where['search_value'];?>">
                    <input type="submit" value="搜索" class="ui-button-small ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
                    <a id="advance_option" href="javascript:void(0);">高级搜索</a>
                </div>
            </form>

        </div>
        <?php if (is_array($order_list) && count($order_list)) {?>
        <table cellspacing="0" class="table_overflow">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="30"><input type="checkbox" id="check_all"></th>
                        <th width="30">序号</th>
                        <?php echo view_tool::sort('订单号', 2, 0);?>
                        <th>邮箱</th>
                        <th width="30">币种</th>
                        <th width="50">金额</th>
                        <th width="60">状态</th>
                        <th width="60">支付</th>
                        <th width="60">发货</th>
                        <?php echo view_tool::sort('下单时间', 12, 120);?>
                        <th width="120">支付时间</th>
                        <th width="80">支付日志</th>
                    </tr>
                </thead>
                <tbody>
                		<?php $i = 1;?>
                        <?php foreach ($order_list as $key=>$rs) { ?>
                    <tr>
                        <td>
                            <input class="sel" name="order_ids[]" value="<?php echo $rs['id'];?>" type="checkbox" temp="<?php echo $key;?>">
                        </td>
                        <td><?php echo $i;?></td>
                        <td><a href="<?php echo url::base();?>order/order/edit/id/<?php echo $rs['id'];?>"><?php echo $rs['order_num'];?> </a></td>
                        <td><a href="<?php echo url::base();?>order/order?search_type=email&search_value=<?php echo $rs['email'];?>"><?php echo $rs['email'];?></a></td>
                        <td><?php echo $rs['currency'];?></td>
                        <td><?php echo $rs['total_real'];?></td>
                        <td><?php echo (isset($order_status[$rs['order_status']]))?$order_status[$rs['order_status']]['name']:'未处理';?></td>
                        <td><?php echo (isset($pay_status[$rs['pay_status']]))?$pay_status[$rs['pay_status']]['name']:'未支付';?></td>
                        <td><?php echo (isset($ship_status[$rs['ship_status']]))?$ship_status[$rs['ship_status']]['name']:'未发货';?></td>
                        <td><?php echo $rs['date_add'];?></td>
                        <td><?php echo $rs['date_pay'];?></td>
                        <td><a href="javascript:void(0);" onclick="showpaymentlog(<?php echo $rs['id'];?>, <?php echo $rs['order_num'];?>)">窗口</a> <a href="<?php echo url::base();?>manage/payment_log?search_type=order_num&search_value=<?php echo $rs['order_num'];?>">链接</a><div id='paymentlog_dialog_content_<?php echo $rs['id'];?>' style="display:none;" title="支付日志"></div></td>
                    </tr>
                    <?php $i++;?>
                            <?php }?>
                </tbody>
                <input type="hidden" value="" id="content_user_batch" name="content_user_batch">
                <input type="hidden" value="" id="content_admin_batch" name="content_admin_batch">
            </form>
        </table>
            <?php }else {?>
            <?php echo remind::no_rows();?>
            <?php }?>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
        <?php echo view_tool::per_page(); ?>
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
            <?PHP echo $this->pagination->render('opococ'); ?>
        </div>
    </div>
</div>
<!--END FOOTER-->
<div id="advance_search" style="display:none;" title="搜索订单">
    <form name="batch_order_status_form" id="batch_order_status_form" method="get" action="<?php echo url::base() . url::current();?>">
        <div class="out_box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <th width="15%">开始时间：</th>
                        <td width="35%">
                            <input name="date_begin" id="date_begin" accesskey="s" tabindex="1" size="20" value="" class="text" style="background-color:#f1f1f1" readonly="true"/>
                        </td>
                        <th width="15%">结束时间：</th>
                        <td>
                            <input name="date_end" id="date_end" accesskey="s" tabindex="1" size="20" value="" class="text" style="background-color:#f1f1f1" readonly="true"/>
                        </td>
                    </tr>
                    <tr>
                        <th>订单总额：</th>
                        <td colspan="3">
                            <input name="amount_begin" id="amount_begin" size="10" value="" class="text"/>
                            -
                            <input name="amount_end" id="amount_end" size="10" value="" class="text"/>
                        </td>
                    </tr>
                    <tr>
                        <th width="15%">支付状态：</th>
                        <td width="35%">
                            <select name="pay_status">
                                <option value=""> - 全部 - </option>
                                <?php foreach($pay_status as $key=>$value):?>
                                <option value="<?php echo $key;?>"><?php echo $value['name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                        <th width="15%">发货状态：</th>
                        <td>
                            <select name="ship_status">
                                <option value=""> - 全部 - </option>
                                <?php foreach($ship_status as $key=>$value):?>
                                <option value="<?php echo $key;?>"><?php echo $value['name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>订单状态：</th>
                        <td colspan="3">
                            <select name="order_status">
                                <option value=""> - 全部 - </option>
                                <?php foreach($order_status as $key=>$value):?>
                                <option value="<?php echo $key;?>"><?php echo $value['name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="list_save">
            <input name="create_all_goods" type="submit" class="ui-button" value=" 搜索 "/>
            <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='$("#advance_search").dialog("close");'/>
        </div>
    </form>
</div>
<!-- 批量废除订单 -->
<div id="order_cancel_content" title="批量废除订单" style="display:none;">
    <div class="dialog_box">
        <div class="out_box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <td colspan="2">
                            <h3 class="title1_h3">废除的订单不能再做任何处理操作，请谨慎操作！</h3>
                        </td>
                    </tr>
                    <tr>
                        <th>备注(管理员)：</th>
                        <td>
                            <textarea type="textarea" class="text" rows="3" cols="60" name="content_admin" id="content_admin"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>备注(会员)：</th>
                        <td>
                            <textarea type="textarea" class="text" rows="3" cols="60" name="content_user" id="content_user"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="list_save">
        <input name="order_cancel_btn" onclick="order_cancel();" type="button" value=" 确认 " class="ui-button"/>
        <input name="cancel" id="cancel_btn" type="button" value=" 取消 " class="ui-button" onclick='$("#order_cancel_content").dialog("close");'/>
    </div>
</div>
<!-- 订单导出 -->
<div id="order_export_content" title="订单导出" style="display:none;">
    <div class="out_box">
        <table cellspacing="0" cellpadding="0" border="0" width="95%" class="table_overflow">
            <tbody>
            <thead>
                <tr class="headings">
                    <th>选择导出格式</th>
                </tr>
            </thead>
            <?php
            $i = 0;
            foreach ($order_export_list as $key=>$value) {
                $i++;
                ?>
            <tr>
                <td>
                    <label><input type="radio" name="export_type" <?php echo ($i==1)?'checked':'';?> value="<?php echo $key;?>"><?php echo $value;?></label>
                </td>
            </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
    <div class="list_save">
        <input name="order_cancel_btn" onclick="order_export();" type="button" value=" 确认 " class="ui-button"/>
        <input name="cancel" id="cancel_btn" type="button" value=" 取消 " class="ui-button" onclick='$("#order_export_content").dialog("close");'/>
    </div>
</div>
<!-- 批量配货 -->
<div id="shipping_processing_dialog" title="批量配货" style="display:none;">
    <div class="dialog_box">
        <div class="out_box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <td colspan="2">
                            <h3 class="title1_h3">确认选择的订单是要处理为配货中！</h3>
                        </td>
                    </tr>
                    <tr>
                        <th>备注(管理员)：</th>
                        <td>
                            <textarea type="textarea" class="text" rows="3" cols="60" name="content_admin" id="content_admin"></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th>备注(会员)：</th>
                        <td>
                            <textarea type="textarea" class="text" rows="3" cols="60" name="content_user" id="content_user"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="list_save">
        <input name="order_cancel_btn" onclick="shipping_processing();" type="button" value=" 确认 " class="ui-button"/>
        <input name="cancel" id="cancel_btn" type="button" value=" 取消 " class="ui-button" onclick='$("#shipping_processing_dialog").dialog("close");'/>
    </div>
</div>
<!-- 支付日志 -->
<script type="text/javascript">
    $(function() {
        // Dialog
        $("div[id^='paymentlog_dialog_content_']").each(function(){
        	$(this).dialog({
	            autoOpen: false,
	            width: 800,
	            height:180,
	            modal: true
        	});
        });
    });
    //公告
    function showpaymentlog(id, order_num){
    	if ($("#paymentlog_dialog_content_"+id).html()=='' || $("#paymentlog_dialog_content_"+id).html()=='loading...') { //若已ajax请求过，只需显示窗口
	        $("#paymentlog_dialog_content_"+id).html("loading...");
	        $.ajax({
	    		url: '<?php echo url::base();?>manage/payment_log/ajax_index?order_num='+order_num,
	            type: 'GET',
	            dataType: 'json',
	            error: function() {
	                alert('error');
	            },
	            success: function(retdat, status) {
					ajax_block.close();
					if (retdat['code'] == 200 && retdat['status'] == 1) {
						$("#paymentlog_dialog_content_"+id).html(retdat['content']);
					} else {
						showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
					}
				}
	    	});
    	}
        $("#paymentlog_dialog_content_"+id).dialog("open");
        return true;
    }
</script>