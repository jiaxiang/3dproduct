<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">订单详情</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="newgrid">
            <!--** edit start**-->
            <div class="out_box">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                           	 <tr>
                                    <th>ID：</th>
                                    <td>
                                    <label><?php echo $data['id'];?></label>
                                    </td>
                              </tr>
                                <tr>
                                    <th>父订单ID：</th>
                                    <td>
									<label><?php echo $data['order_id'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>用户信息：</th>
                                    <td>
									ID:<label><?php echo $user['id'];?></label><br/>
									Email:<label><?php echo $user['email'];?></label><br/>
									Username:<label><?php echo $user['username'];?></label><br/>
									Name:<label><?php echo $user['name'];?></label><br/>
									Mobile:<label><?php echo $user['mobile'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>类型：</th>
                                    <td>
									<label><?php echo Order_detail_Model::show_type($data['type']);?></label>
                                    </td>
                                </tr>
                                 <tr>
                                    <th>价格：</th>
                                    <td>
                                     <label><?php echo $data['price'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>模型：</th>
                                    <td>
                                     <label><a href="http://<?php echo kohana::config('site_config.site.name').'/attach/download/'.$data['model'].'/'.$data['model_name'];?>" target="_blank"><?php echo $data['model_name'];?></a></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>预览图：</th>
                                    <td>
                                     <label><img src="http://<?php echo kohana::config('site_config.site.name').'/attach/pic/'.$data['preview'];?>"/></label>
                                     <label><?php echo $data['preview_name'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>打印尺寸：</th>
                                    <td>
                                     <label><?php echo $data['size'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>打印材料：</th>
                                    <td>
                                     <label><?php echo order::get_print_material($data['material']);?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>打印颜色：</th>
                                    <td>
                                     <label>
                                    <?php
                                    $color = order::get_print_color($data['color']);
                                    if ($color != '') {
                                    	echo '<img src="'.$color.'" width="30px" height="40px"/>';
                                    }
                                     ?>
                                     <?php echo $data['color'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>打印精度：</th>
                                    <td>
                                     <label><?php echo order::get_print_precision($data['precision']);?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>打印数量：</th>
                                    <td>
                                     <label><?php echo $data['quantity'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>草图：</th>
                                    <td>
                                     <label><?php echo $data['draft'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>留言：</th>
                                    <td>
                                     <label><?php echo $data['message'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>预付金额：</th>
                                    <td>
                                     <label><?php echo $data['front_money'];?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>订单状态：</th>
                                    <td>
                                     <label><?php echo Order_basic_Model::show_status($data['status']);?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">下单时间：</th>
                                    <td><?php echo $data['add_time'];?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>最后更新时间：</th>
                                    <td><?php echo $data['update_time'];?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="clear">&nbsp;</div>


            </div>
            <!--** edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<div id='example' style="display:none;"></div>
<div id='address' style="display:none;"></div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
	    $('#birthday').datepicker({dateFormat:"yy-mm-dd"});

        $("#add_form").validate({
			rules:{
        		email: {
					maxlength: 255
				},
				firstname: {
					maxlength: 50
				},
				lastname: {
					maxlength: 50
				}
			},
    		messages:{
				email: {
					required: '邮箱不可为空',
					maxlength: '邮箱长度不可超过 255 个字符'
				},
				firstname: {
					required: '名不可为空',
					maxlength: '名长度不可超过 50 个字符'
				},
				lastname: {
					required: '姓不可为空',
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