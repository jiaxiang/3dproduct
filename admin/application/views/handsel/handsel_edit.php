<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑彩金活动</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**productlist edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base().url::current();?>">
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>

                                <tr>
                                    <th width="15%">彩金赠送活动名称:</th>
                                    <td>
                                        <input type="text" size="10" name="title" id="title" class="text t400  _x_ipt required" value="<?php isset($data) && print($data['title']); ?>"/><span class="required"> *</span>
                                        <span class="brief-input-state notice_inline">请不要超过255字节。</span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>活动开始时间:</th>
                                    <td>
                                        <input type="text" size="10" name="start_time" id="start_time" class="text t400  _x_ipt  required" value="<?php isset($data) && print($data['start_time']); ?>"/><span class="required"> * </span>
                                        <span class="brief-input-state notice_inline">请不要超过255字节。</span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>活动结束时间:</th>
                                    <td>
                                        <input type="text" size="10" name="end_time" id="end_time" class="text t400  _x_ipt required" value="<?php isset($data) && print($data['end_time']); ?>"/><span class="required"> *</span>
                                        <span class="brief-input-state notice_inline">请不要超过255字节。</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>赠送彩金额度:</th>
                                    <td>
                                        <input type="text" size="10" name="total" id="total" class="text t400  _x_ipt required" value="<?php isset($data) && print($data['total']); ?>"/><span class="required"> *</span>
                                        <span class="brief-input-state notice_inline">请不要超过255字节。</span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>彩金活动是否开启:</th>
                                    <td>
                                        <input type="radio" <?php if(isset($data) && $data['status']) echo 'checked'; ?> value="1" name="status">开启 &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" <?php if(!isset($data) || !$data['status']) echo 'checked'; ?> value="0" name="status">不开启 
                                    </td>
                                </tr>
                                <tr>
                                    <th>人工审核是否开启:</th>
                                    <td>
                                        <input type="radio" <?php if(isset($data) && $data['check']) echo 'checked'; ?> value="1" name="check">开启 &nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="radio" <?php if(!isset($data) || !$data['check']) echo 'checked'; ?> value="0" name="check">不开启 
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input name="submit" type="submit" class="ui-button" value=" 保存 " />
                </div>
            </form>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
	    $('#start_time').datepicker({dateFormat:"yy-mm-dd"});
		$('#end_time').datepicker({dateFormat:"yy-mm-dd"});

        $("#add_form").validate({
			rules:{
        		title: {
					maxlength: 255
				},
				start_time: {
					maxlength: 50
				},
				end_time: {
					maxlength: 50
				}

			},
    		messages:{
				title: {
					required: '活动名称不可为空',
					maxlength: '邮箱长度不可超过 255 个字符'
				},
				start_time: {
					required: '开始时间不可为空',
					maxlength: '名长度不可超过 50 个字符'
				},
				end_time: {
					required: '结束时间不可为空',
					maxlength: '姓长度不可超过 50 个字符'
				}

			}
        });
        $("#edit_password_form").validate({
			errorClass:"error",
			rules:{
        	password:{
				required:true,
				rangelength:[5,200]
        		}
			}
            });
        //查看邮件模板
        var dialogOpts = {
            title: "邮件模板内容",
            modal: true,
            autoOpen: false,
            height: 500,
            width: 600
        };
        $("#example").dialog(dialogOpts);

        $(".mail_template").click(function(){
            var id = $(this).attr('id');
            $("#example").html("loading...");
            $.ajax({
        		url: '<?php echo url::base();?>site/mail/ajax_content' + '?id=' + id,
                type: 'GET',
                dataType: 'json',
                error: function() {
                    window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#example").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#example").dialog("open");
        });

        var addressOpts = {
            title: "编辑地址",
            modal: true,
            autoOpen: false,
            width: 800
        };
        $("#address").dialog(addressOpts);
        $(".address_edit").click(function(){
            var id = $(this).attr('id');
            $("#address").html("loading...");
            $.ajax({
        		url: '<?php echo url::base();?>user/address/ajax_edit' + '?id=' + id,
                type: 'GET',
                dataType: 'json',
                error: function() {
                    window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#address").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#address").dialog("open");
        });
        $(".address_add").click(function(){
            var user_id = "<?php echo $data['id'];?>";
            $("#address").html("loading...");
            $.ajax({
        		url: '<?php echo url::base();?>user/address/ajax_add' + '?user_id=' + user_id,
                type: 'GET',
                dataType: 'json',
                error: function() {
                    window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#address").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#address").dialog("open");
        });
    });
</script>
