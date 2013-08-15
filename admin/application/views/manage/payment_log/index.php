<script type="text/javascript">
    $(function() {
        /* 高级搜索 */
        $("#advance_option").click(function(){
            $("#advance_search").dialog("open");
        });
        // Dialog
        $('#advance_search').dialog({
            autoOpen: false,
            modal: true,
            width: 600,
            height:150
        });
    });
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
    });
</script>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_top">
            <form action="/manage/payment_log" method="GET" name="search_form" class="new_search" id="search_form">
                <div>搜索:
                    <select name="search_type" class="text">
                        <option value="order_num" <?php if ($where['search_type'] == 'order_num')echo "SELECTED";?>>订单号</option>
                    </select>
                    <input type="text" name="search_value" class="text" value="<?php echo $where['search_value'];?>">
                    <input type="submit" value="搜索" class="ui-button-small ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
                    <a id="advance_option" href="javascript:void(0);">高级搜索</a>
                </div>
            </form>

        </div>
        <?php if (is_array($list) && count($list)) {?>
        <table cellspacing="0" class="table_overflow">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="30">序号</th>
                        <?php echo view_tool::sort('订单号',2, 125);?>
                        <th width="50">用户</th>
                        <th width="60">支付接口</th>
                        <th width="50">错误号</th>
                        <th width="60">错误信息</th>
                        <th>备注</th>
                        <?php echo view_tool::sort('记录时间',12,125);?>
                    </tr>
                </thead>
                <tbody>
                		<?php $i = 1;?>
                        <?php foreach ($list as $key=>$rs) { ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><a href="<?php echo url::base();?>order/order/edit/id/<?php echo $rs['id'];?>">#<?php echo $rs['order_num'];?> </a></td>
                        <td><?php echo $users[$rs['user_id']]['lastname'];?></td>
                        <td><?php echo $payment_types[$rs['payment_type_id']]['name'];?></td>
                        <td><?php echo $rs['error_id'];?></td>
                        <td><?php echo $rs['error_message'];?></td>
                        <td><?php echo $rs['remark'];?></td>
                        <td><?php echo $rs['date_add'];?></td>
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
<div id="advance_search" style="display:none;" title="搜索日志">
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
                </tbody>
            </table>
        </div>
        <div class="list_save">
            <input name="" type="submit" class="ui-button" value=" 搜索 "/>
            <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='$("#advance_search").dialog("close");'/>
        </div>
    </form>
</div>