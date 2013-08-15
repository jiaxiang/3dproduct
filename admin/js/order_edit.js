$(function(){
    $(".arrow_hide").each(function(i){
        $(this).click(function(){
            $(this).parent().parent().children("table").toggle();
        })
    })
    //查看邮件模板
    var dialogOpts = {
        title: "编辑商品信息",
        modal: true,
        autoOpen: false,
        height: 500,
        width: 960
    };
    $("#order_product").dialog(dialogOpts);
    //支付order_pay_btn
    $(".order_pay_btn").click(function(){
        var order_id = $(this).attr('id');
        var url = $("#url").val();
        $("#order_pay").html("loading...");
        $.ajax({
    		url: '/order/order/order_pay',
            type: 'POST',
            data: {"order_id" : order_id},
            dataType: 'json',
            error: function() {
                window.location.href = url;
            },
            success: function(retdat, status) {
				ajax_block.close();
				if (retdat['code'] == 200 && retdat['status'] == 1) {
					$("#order_pay").html(retdat['content']);
				} else {
					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
				}
			}
    	});
        $("#order_pay").dialog("open");
        return false;
    });
    //修改订单状态
    $(".order_status_btn").click(function(){
        var id = $(this).attr('id');
        var action_name = $(this).attr('name');
        var url = $("#url").val();
        $("#order_status").html("loading...");
        $.ajax({
    		url: '/order/order/order_status',
            type: 'POST',
            data: {"order_id" : id,"action" : action_name},
            dataType: 'json',
            error: function(retdat, status) {
                window.location.href = url;
            },
            success: function(retdat, status) {
				ajax_block.close();
				if (retdat['code'] == 200 && retdat['status'] == 1) {
					$("#order_status").html(retdat['content']);
				} else {
					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
				}
			}
    	});
        $("#order_status").dialog("open");
        return false;
    });
    //退款
    $(".order_refund_btn").click(function(){
        var id = $(this).attr('id');
        var url = $("#url").val();
        $("#order_refund").html("loading...");
        $.ajax({
    		url: '/order/order/order_refund',
            type: 'POST',
            data: {"order_id" : id},
            dataType: 'json',
            error: function() {
                window.location.href = url;
            },
            success: function(retdat, status) {
				ajax_block.close();
				if (retdat['code'] == 200 && retdat['status'] == 1) {
					$("#order_refund").html(retdat['content']);
				} else {
					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
				}
			}
    	});
        $("#order_refund").dialog("open");
        return false;
    });
    //发货
    $(".order_ship_btn").click(function(){
        var id = $(this).attr('id');
        var url = $("#url").val();
        $("#order_ship").html("loading...");
        $.ajax({
    		url: '/order/order/order_ship',
            type: 'POST',
            data: {"order_id" : id},
            dataType: 'json',
            error: function() {
                window.location.href = url;
            },
            success: function(retdat, status) {
				ajax_block.close();
				if (retdat['code'] == 200 && retdat['status'] == 1) {
					$("#order_ship").html(retdat['content']);
				} else {
					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
				}
			}
    	});
        $("#order_ship").dialog("open");
        return false;
    });
    //退货
    $(".order_returned_goods_btn").click(function(){
        var id = $(this).attr('id');
        var url = $("#url").val();
        $("#order_returned_goods").html("loading...");
        $.ajax({
    		url: '/order/order/order_return',
            type: 'POST',
            data: {"order_id" : id},
            dataType: 'json',
            error: function() {
                window.location.href = url;
            },
            success: function(retdat, status) {
				ajax_block.close();
				if (retdat['code'] == 200 && retdat['status'] == 1) {
					$("#order_returned_goods").html(retdat['content']);
				} else {
					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
				}
			}
    	});
        $("#order_returned_goods").dialog("open");
        return false;
    });
    
    //编辑订单商品
    $("a.act_doedit").click(function(){
        var id = $(this).attr('id');
        var url = $("#url").val();
        var ifm = $('#order_product_edit');        
        ifm.html("loading...");
        $.ajax({
    		url: '/order/order_product/ajax_edit' + '?id=' + id,
            type: 'GET',
            dataType: 'json',
            error: function() {
                window.location.href = url;
            },
            success: function(retdat, status) {
				ajax_block.close();
				if (retdat['code'] == 200 && retdat['status'] == 1) {
					ifm.html(retdat['content']);
				} else {
					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
				}
			}
    	});
		ifm.dialog("open");
		return false;
    });
    
    // 编辑订单中商品信息
    $('#order_product_edit').dialog({
        autoOpen: false,
        modal: true,
        width: 600
    });    

    // Dialog
    $('#order_status_edit').dialog({
        autoOpen: false,
        modal: true,
        width: 760
    });
    // Dialog
    $('#order_edit').dialog({
        autoOpen: false,
        modal: true,
        width: 1000
    });
    //支付
    $('#order_pay').dialog({
        autoOpen: false,
        modal: true,
        width: 800,
        height:500
    });
    //更新订单状态
    $('#order_status').dialog({
        autoOpen: false,
        modal: true,
        width: 800,
        height:450
    });
    //发货
    $('#order_ship').dialog({
        autoOpen: false,
        modal: true,
        width: 1000,
        height:450
    });
    //退货
    $('#order_returned_goods').dialog({
        autoOpen: false,
        modal: true,
        width: 1000,
        height:450
    });
    //退款
    $('#order_refund').dialog({
        autoOpen: false,
        modal: true,
        width: 800,
        height:450
    });
    // 回复订单留言
    $('#order_message_add').dialog({
        autoOpen: false,
        modal: true,
        width: 800,
        height:300
    });
    // Tabs
    $('#tabs').tabs({
        selected:0
    });

    $("#order_edit_form").validate({
    	errorPlacement: function(error, element) {  
        	error.appendTo(element.parent());  
    	}
    });
    $("#order_message_add_form").validate({
        errorPlacement: function(error, element) {  
            error.appendTo(element.parent());  
        }
    });
    $("#order_send_form").validate();
    $("#order_refund_form").validate();
    $("#order_status_edit_form").validate();

    //物流链接变化
    $("input[name=ems_url]").click(function(){
        var ems_no = $("input[name=ems_num]").val();
        if($("input[name=ems_url]:checked").val() == 1){
            $("textarea[name=content_user]").val("Hi,\nYour ordered goods has been sent. The tracking Number of your order is: ("+ems_no+"), you may track the parcel on http://www.usps.com/shipping/trackandconfirm.htm?form=global. The tracking number will be valid in 48 hours. Please check the status of the order online often. Usually the order will be delivered in one week. If you can not receive it in 9 days, please contact with the carrier and us to ensure the fully delivery.Thanks.\n\nBest regards ");
            $("input[name=ems_url_text]").val('http://www.usps.com/shipping/trackandconfirm.htm?form=global');
        }else if($("input[name=ems_url]:checked").val() == 2){
            $("textarea[name=content_user]").val("Hi,\nYour ordered goods has been sent. The tracking Number of your order is: ("+ems_no+"), you may track the parcel on http://www.parcelforce.com/portal/pw/track?catId=7500082 . The tracking number will be valid in 48 hours. Please check the status of the order online often. Usually the order will be delivered in one week. If you can not receive it in 9 days, please contact with the carrier and us to ensure the fully delivery.Thanks.\n\nBest regards ");
            $("input[name=ems_url_text]").val('http://www.parcelforce.com/portal/pw/track?catId=7500082');
        }else{
            $("textarea[name=content_user]").val("");
            $("input[name=ems_url_text]").val('');
        }
    });
    //是否发邮件
    $("input[name=send_mail]").click(function(){
        if($("input[name=send_mail]:checked").val() == 1){
            $("#mail_template_div").show();
        }else{
            $("#mail_template_div").hide();
        }
    });
    //查看邮件模板
    /*var dialogOpts = {
        title: "邮件模板内容",
        modal: true,
        autoOpen: false,
        height: 500,
        width: 600
    };
    $("#example").dialog(dialogOpts);

    $(".content_small").each(function(){
        var id = $(this).attr('id');
        $(this).click(function (){
            $("#example").html("loading...");
            $("#example").load("/site/mail/ajax_content",{
                "id":id
            });
            $("#example").dialog("open");
        }
        );
    });*/
});