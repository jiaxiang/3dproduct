<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
            <li class="on"><a href="/order/order">订单列表</a></li>
            <!-- <li ><a href="/order/order/index/hasbuy">未满员</a></li>
            <li ><a href="/order/order/index/noprint">未出票</a></li>
            <li ><a href="/order/order/index/hasprint">已出票</a></li>
            <li ><a href="/order/order/index/givehonus">已派奖</a></li>
            <li ><a href="/order/order/index/cancel">已撤单</a></li> -->
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
             <li><a href="javascript:void(0);"><span class="rec_pro" id="goodsok">订单发货</span></a></li>
             <li><a href="javascript:void(0);"><span class="rec_pro" id="complete">订单完成</span></a></li>
             <li><a href="javascript:void(0);"><span class="del_pro" id="cancel">订单取消</span></a></li>
          </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div style="padding-right:10px;">搜索：
                    <select name="search_type" class="text">
                        <option value="id" <?php if ($where['search_type'] == 'id')echo "SELECTED";?>>ID</option>
                        <option value="order_num" <?php if ($where['search_type'] == 'order_num')echo "SELECTED";?>>订单号码</option>
                        <option value="lastname" <?php if ($where['search_type'] == 'lastname')echo "SELECTED";?>>用户名</option>
                    </select>
                    <input type="text" name="search_value" class="text" value="<?php echo $where['search_value'];?>">
                    时间：<input type="text" id="start_time" name="start_time" value="<?php if (isset($where['start_time'])) echo $where['start_time'];?>" class="text" size="10" />
        			<script type="text/javascript">$(function() { $("#start_time").datepicker({ currentText: 'Now',dateFormat: "yy-mm-dd" }); });</script>
        			到<input type="text" id="end_time" name="end_time" value="<?php if (isset($where['end_time'])) echo $where['end_time'];?>" class="text" size="10" />
        			<script type="text/javascript">$(function() { $("#end_time").datepicker({ currentText: 'Now',dateFormat: "yy-mm-dd" }); });</script>
                    <input type="submit" name="Submit2" value="搜索" class="ui-button-small">
                </div>
            </form>
        </div>
        <?php if (is_array($list) && count($list)) {?>
        <table cellspacing="0" class="table_overflow">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="10"><input type="checkbox" id="check_all"></th>
                        <th width="30">订单ID</th>
                        <th width="110">订单号</th>
                        <th width="30">用户ID</th>
                        <th width="40">下单人</th>
                        <th width="80">手机号码</th>
                        <th width="40">总金额</th>
                        <th width="40">状态</th>
                        <th width="120">购买时间</th>
                        <th width="120">更新时间</th>
                        <th width="50">操作</th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($list as $key=>$rs) { ?>
                    	<tr>
                        <td><input class="sel" name="order_ids[]" value="<?php echo $rs['id'];?>" type="checkbox"></td>
                        <td><?php echo $rs['id'];?></td>
                        <td><?php echo $rs['order_num'];?></td>
                        <td><?php echo $rs['uid'];?></td>
                        <td><?php echo $rs['name'];?></td>
                        <td><?php echo $rs['mobile'];?></td>
                        <td><?php echo $rs['price'];?></td>
                        <td><?php echo Order_basic_Model::show_status($rs['status']);?></td>
                        <td><?php echo $rs['add_time'];?></td>
                        <td><?php echo $rs['update_time'];?></td>
                        <td><a href="<?php echo url::base();?>order/detail?search_type=order_id&search_value=<?php echo $rs['id'];?>">查看子订单</a></td>
                    </tr>
                    <?php }?>
                </tbody>
                <input name="backurl" type="hidden" value="" />
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

<!-- 批量废除订单 -->
<div id="order_cancel_content" title="设为已打票" style="display:none;">
    <div class="dialog_box">
        <div class="out_box">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <td colspan="2">
                            <h3 class="title1_h3">确定要打印选中的彩票?</h3>
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
<script type="text/javascript">
//取消订单
$("#cancel").click(function(){
	var i = false;
	var j = 0;
	$('.sel').each(function(){
		if(i == false){
			if($(this).attr("checked")==true){
				i = true;
			}
		}
	});
	if(i == false){
		alert('请选择订单！');
		return false;
	}
	$('.sel').each(function(){
		if($(this).attr("checked")==true) {
			j++;
		}
	});
	if(!confirm('确认要进行此操作吗？')) {
		return false;
	}
	$('#list_form').attr('action','/order/order/update_order_status/<?php echo Order_basic_Model::STATUS_5?>');
	$('#list_form').submit();
	return false;
});
$("#complete").click(function(){
	var i = false;
	var j = 0;
	$('.sel').each(function(){
		if(i == false){
			if($(this).attr("checked")==true){
				i = true;
			}
		}
	});
	if(i == false){
		alert('请选择订单！');
		return false;
	}
	$('.sel').each(function(){
		if($(this).attr("checked")==true) {
			j++;
		}
	});
	if(!confirm('确认要进行此操作吗？')) {
		return false;
	}
	$('#list_form').attr('action','/order/order/update_order_status/<?php echo Order_basic_Model::STATUS_4?>');
	$('#list_form').submit();
	return false;
});
$("#goodsok").click(function(){
	var i = false;
	var j = 0;
	$('.sel').each(function(){
		if(i == false){
			if($(this).attr("checked")==true){
				i = true;
			}
		}
	});
	if(i == false){
		alert('请选择订单！');
		return false;
	}
	$('.sel').each(function(){
		if($(this).attr("checked")==true) {
			j++;
		}
	});
	if(!confirm('确认要进行此操作吗？')) {
		return false;
	}
	$('#list_form').attr('action','/order/order/update_order_status/<?php echo Order_basic_Model::STATUS_3?>');
	$('#list_form').submit();
	return false;
});
</script>


