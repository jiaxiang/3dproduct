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
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <div class="public">
                <div class="public_left title_h3">
                    <h3>订单列表</h3>
                </div>
                <div class="public_right">
                </div>
            </div>
            <div class="finder_search" id="advance_search_content">
                <div class="finder_search_table">
                    <form id="search_form" name="search_form" method="get" action="<?php echo url::base() . url::current();?>">
                        <table width="660" height="75" border="0" cellpadding="0" cellspacing="3">
                            <tr>
                                <td width="15%" height="21">关 键 字：</td>
                                <td colspan="4">
                                    <INPUT id="q" accessKey="s" tabIndex="1" size="48" name="advance_search_value" value="<?php echo $where['advance_search_value'];?>">
                                </td>
                            </tr>
                            <tr>
                                <td height="21">起始时间：</td>
                                <td width="124">
                                    <input name="date_begin" id="date_begin" accesskey="s" tabindex="1" size="12" value="<?php echo $where['date_begin'];?>" style="background-color:#f1f1f1" readonly="true"/>
                                </td>
                                <td width="100">结束时间：</td>
                                <td width="105">
                                    <input name="date_end" id="date_end" accesskey="s" tabindex="1" size="12"  value="<?php echo $where['date_end'];?>" style="background-color:#f1f1f1" readonly="true"/>
                                </td>
                                <td width="287">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="66">站点：</td>
                                <td width="105" colspan="3"><select tabindex="3" name="site_id">
                                        <option value="">全部</option>
                                        <?php foreach ($site_list as $key=>$value) {?>
                                        <option value="<?php echo $key;?>" <?php if ($where['site_id'] == $key)echo "SELECTED";?>><?php echo $value;?></option>
                                            <?php }?>
                                    </select></td>
                                <td width="287">&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="21">国家：</td>
                                <td><select tabindex="3" name="country">
                                        <option value="">全部</option>
                                        <option value="US" <?php if ($where['country'] == 'US')echo "SELECTED";?>>美国</option>
                                        <option value="GB" <?php if ($where['country'] == 'GB')echo "SELECTED";?>>英国</option>
                                        <option value="DE" <?php if ($where['country'] == 'DE')echo "SELECTED";?>>德国</option>
                                    </select></td>
                                <td height="21">订单总额：</td>
                                <td><select tabindex="3" name="amount">
                                        <option value="">不限</option>
                                        <option value="1" <?php if ($where['amount'] == '1')echo "SELECTED";?>>100以下</option>
                                        <option value="2" <?php if ($where['amount'] == '2')echo "SELECTED";?>>100-150</option>
                                        <option value="3" <?php if ($where['amount'] == '3')echo "SELECTED";?>>150-200</option>
                                        <option value="4" <?php if ($where['amount'] == '4')echo "SELECTED";?>>200-300</option>
                                        <option value="5" <?php if ($where['amount'] == '5')echo "SELECTED";?>>300以上</option>
                                    </select></td>
                                <td><label>
                                        <input type="submit" class="ui-button" name="Submit" value="搜索">
                                    </label></td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <!--	<div class="public_title title_h3"></div>	-->
            <!--**orderlist start**-->
            <div  class="head_content">
                <div class="actionBar mainHead" ></div>
                <div class="mainHead headBox" >
                    <div class="headContent">
                        <div class="finder-head">
                            <div class="span-1">
                                <input  type="checkbox" id="check_all">
                            </div>
                            <?php echo view_tool::orderby('操作', 2);?>
                            <?php echo view_tool::orderby('订单号', 5, 0);?>
                            <?php echo view_tool::orderby('站点', 5, 2);?>
                            <?php echo view_tool::orderby('Email', 5, 4);?>
                            <?php echo view_tool::orderby('币种', 1);?>
                            <?php echo view_tool::orderby('订单总额', 3, 6);?>
                            <?php echo view_tool::orderby('当前状态', 5, 8);?>
                            <?php echo view_tool::orderby('下单时间', 5, 10);?>
                            <?php echo view_tool::orderby('支付时间', 5, 12);?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main_content" style="visibility: visible; opacity: 1;">
                <div class="finder">
                    <div class="finder-list" >
                        <?php if (is_array($order_list) && count($order_list)) {
    foreach ($order_list as $key=>$rs) { ?>
                        <div class="row" id="top_div_<?php echo $key;?>">
                            <div class="row-line" style="cursor: pointer;">
                                <div class="span-1 span-select">
                                    <input tags="null" class="sel" name="goods_id" value="19" type="checkbox"  temp="<?php echo $key;?>">
                                </div>
                                <div class="cell span-2 fd"><a href="<?php echo url::base();?>order/order/edit/id/<?php echo $rs['id'];?>">编辑</a> </div>
                                <div class="span-5 fB"><a href="<?php echo url::base();?>order/order/edit/id/<?php echo $rs['id'];?>">#<?php echo $rs['order_num'];?> </a> </div>
                                <div class="cell span-5 orderCell"><?php echo $rs['site']['name'];?> </div>
                                <div class="cell span-5"> <?php echo $rs['email'];?> </div>
                                <div class="cell span-1"> <?php echo $rs['currency'];?> </div>
                                <div class="cell span-3"> <?php echo $rs['total_real'];?> </div>
                                <div class="cell span-5" style="background-color:<?php echo $rs['order_status']['bgcolor'];?>;"> <?php echo $rs['order_status']['admin_show'];?></div>
                                <div class="cell span-5"> <?php echo $rs['date_add'];?> </div>
                                <div class="cell span-5"> <?php echo $rs['date_pay'];?> </div>
                            </div>
                        </div>
        <?php }
}
?>
                    </div>
                </div>
            </div>
            <!--**orderlist end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div id="footer">
    <div class="bottom">
        <div class="Turnpage_leftper">
            <ul>
                <li class="b_icon_16">
                    <img src="<?php echo url::base();?>images/icon/plus.png">
                </li>
                <li>
                    <select tabindex="3" name="order_export_id">
                        <option value="1">默认格式</option>
<?php foreach ($order_export_list as $key=>$value) {?>
                        <option value="<?php echo $key;?>" ><?php echo $value;?></option>
    <?php }?>
                    </select>
                    <a href="javascript:;" id="do_export">导出订单</a>
                </li>
                <li>
                    <a href="javascript:;"  id="edit_export">导出设置</a>
                </li>
                <li class="b_icon_16">
                    <img src="<?php echo url::base();?>images/icon/plus.png">
                </li>
                <li>
                    <select tabindex="3" name="batch_order_status" id="batch_order_status">
                        <option value="6">发货处理中</option>
                        <option value="13">(测试单)废除</option>
                    </select>
                    <a href="javascript:;" id="do_batch">批量操作</a>
                </li>
            </ul>
        </div>
        <!--end of div class Turnpage_leftper-->
        <div class="Turnpage_rightper">
<?php echo view_tool::per_page(); ?>
            <div class="b_r_pager">
<?PHP echo $this->pagination->render('opococ'); ?>
            </div>
        </div>
        <!--end of div class Turnpage_rightper-->
    </div>
</div>
<!--END FOOTER-->
