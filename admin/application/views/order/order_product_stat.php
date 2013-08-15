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
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">商品销量统计(只统计已经付款的订单)</li>
            </ul>
        </div>
        <div class="newgrid">
            <form id="search_form" name="search_form" method="GET" action="<?php echo url::base() . url::current();?>">
                <table width="660" height="75" border="0" cellpadding="0" cellspacing="3" style="width:660px;">
                            <tr>
                                <td width="100" height="21">关 键 字：</td>
                                <td colspan="4">
                                    <label>
                                        <select name="advance_search_type" class="text">
                                            <option value="SKU" <?php if ($where['advance_search_value'] == 'SKU')echo "SELECTED";?>>SKU货号</option>
                                            <option value="name" <?php if ($where['advance_search_value'] == 'name')echo "SELECTED";?>>商品名</option>
                                        </select>
                                    </label>
                                    <label>
                                        <input type="text" name="advance_search_value" class="text" value="<?php echo $where['advance_search_value'];?>">
                                    </label>
                                 </td>
                            </tr>
                            <tr>
                                <td height="21">起始时间：</td>
                                <td width="124">
                                    <input name="date_begin" id="date_begin" class="text" accesskey="s" tabindex="1" size="15" value="<?php echo $where['date_begin'];?>" style="background-color:#f1f1f1" readonly="true"/>
                                </td>
                                <td width="100">结束时间：</td>
                                <td width="105">
                                    <input name="date_end" id="date_end" class="text" accesskey="s" tabindex="1" size="15"  value="<?php echo $where['date_end'];?>" style="background-color:#f1f1f1" readonly="true"/>
                                </td>
                                <td width="287">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="66">站    点：</td>
                                <td width="105" colspan="3">
                                    <select tabindex="3" name="site_id" class="text">
                                        <option value="">全部</option>
                                        <?php foreach ($site_list as $key=>$value) :?>
                                        <option value="<?php echo $key;?>" <?php if ($where['site_id'] == $key)echo "SELECTED";?>><?php echo $value;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </td>
                                <td width="287">&nbsp;</td>
                            </tr>
                            <tr>
                                <td height="21">金额大于：</td>
                                <td>
                                    $<input name="price_above" id="price_above" class="text" accesskey="s" tabindex="1" size="12" value="<?php echo $where['price_above'];?>"/>
                                </td>
                                <td height="21">金额小于：</td>
                                <td>
                                    $<input name="price_under" id="price_under" class="text" accesskey="s" tabindex="1" size="12" value="<?php echo $where['price_under'];?>"/>
                                </td>
                                <td><label>
                                        <input type="submit" class="ui-button" name="Submit" value="搜索" class="btn_text"/>
                                    </label></td>
                            </tr>
                        </table>
            </form>
        </div>
        <table  cellspacing="0">
                <thead>
                    <tr class="headings">
                        <?php echo view_tool::sort('站点分类名',0, 0);?>
                        <?php echo view_tool::sort('站点名',2, 0);?>
                        <?php echo view_tool::sort('货号SKU',4, 0);?>
                        <?php echo view_tool::sort('商品名称',6, 0);?>
                        <?php echo view_tool::sort('数量',8, 0);?>
                        <?php echo view_tool::sort('卖价',10, 0);?>
                        <?php echo view_tool::sort('总折后价',12, 0);?>
                        <?php echo view_tool::sort('平均折后价',14, 0);?>
                    </tr>
                </thead>
                <tbody>
                  <?php if (is_array($order_product_stat) && count($order_product_stat)) {?>
                 <?php foreach ($order_product_stat as $key=>$rs) { ?>
                  <tr id="top_div_<?php echo $key;?>">
                  <td><?php echo $rs['site_type_name'];?>&nbsp;</td>
                  <td> 
                   <?php echo $rs['site_name'];?>&nbsp;
                  </td>
                  <td><?php echo $rs['SKU'];?></td>
                  <td><?php echo $rs['name'];?>&nbsp;</td>
                   <td><?php echo $rs['quantity'];?>&nbsp;</td>
                   <td><?php echo $rs['price'];?>&nbsp;</td>
                    <td><?php echo $rs['discount_price_total'];?>&nbsp;</td>
                    <td><?php echo $rs['discount_price'];?>&nbsp;</td>
                  </tr>
                  <?php }
                  }?>
                </tbody>
        </table>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
        </div>
    </div>
</div>
<!--END FOOTER-->
<div id="advance_search" style="display:none;" title="搜索评论">
     <form id="adv_search_form" name="adv_search_form" method="GET" action="<?php echo url::base() . url::current();?>">
        <div class="dialog_box">
            <div class="body dialogContent">
                <!-- tips of pdtattrset_set_tips  -->
                <div id="gEditor-sepc-panel">
                    <div class="division">
                        <table height="75" border="0" cellpadding="0" cellspacing="3">
                            <tr>
                                <td width="60" height="21">关 键 字：</td>
                                <td colspan="3">
                                    <input class="text" size="50" type="text" name="keyword" id="keyword" value="<?php isset($request_data['keyword']) && !empty($request_data['keyword']) && print($request_data['keyword']); ?>" />
                                </td>
                            </tr>
                           <tr>
                                <td height="21">搜索范围：</td>
                                <td colspan="3">
                                    <input checked="checked" type="radio" name="type" value="sku" <?php if (isset($request_data['type']) && $request_data['type'] == 'sku') {?>checked="checked"<?php }?>>
                                    商品SKU&nbsp;&nbsp;
                                    <!-- <input  type="radio" name="type" value="title" <?php //if (isset($request_data['type']) && $request_data['type'] == 'title') {?>checked="checked"<?php //}?>>
                                    称呼&nbsp;&nbsp; -->
                                    <!-- <input  type="radio" name="type" value="name" <?php //if (isset($request_data['type']) && $request_data['type'] == 'name') {?>checked="checked"<?php //}?>>
                                    姓名&nbsp;&nbsp; -->
                                    <input  type="radio" name="type" value="mail" <?php if (isset($request_data['type']) && $request_data['type'] == 'mail') {?>checked="checked"<?php }?>>
                                    Email&nbsp;&nbsp;
                                    <!-- <input  type="radio" name="type" value="grade" <?php //if (isset($request_data['type']) && $request_data['type'] == 'grade') {?>checked="checked"<?php //}?>>
                                    星级&nbsp;&nbsp; -->
                                </td>
                            </tr>
                            <tr>
                                <td height="21" width="66">所属站点：</td>
                                <td width="105">
                                    <select name="site_id">
                                        <option value="0" <?php if (isset($request_data['site_id']) && $request_data['site_id'] == '0') {?>selected<?php }?>>全部站点</option>
                                        <?php if (is_array($site_list) && count($site_list)) {?>
                                            <?php foreach ($site_list as $key=>$rs) {?>
                                        <option value="<?php echo $key;?>" <?php if (isset($request_data['site_id']) && $request_data['site_id'] == $key) {?>selected<?php }?> ><?php echo $rs;?></option>
                                                <?php }?>
                                            <?php } ?>
                                    </select>
                                </td>
                                <td height="21" width="66">状态：</td>
                                <td width="105">
                                    <select name="status"  >
                                        <option selected value="">---</option>
                                        <option value="1" <?php if (isset($request_data['status']) && $request_data['status'] == 1) {?>selected<?php }?>>审核通过</option>
                                        <option value="0" <?php if (isset($request_data['status']) && $request_data['status'] == 0) {?>selected<?php }?>>未审核</option>
                                        <option value="2" <?php if (isset($request_data['status']) && $request_data['status'] == 2) {?>selected<?php }?>>审核未通过</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="list_save">
            <input name="create_all_goods" type="submit" class="ui-button" value=" 搜索 "/>
            <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='$("#advance_search").dialog("close");'/>
        </div>
    </form>
</div>