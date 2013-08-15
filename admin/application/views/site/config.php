<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
        //define config object
        var dialogOpts = {
            title: "上传新Logo",
            modal: true,
            autoOpen: false,
            height: 250,
            width: 450
        };
        $('#upload_content').dialog(dialogOpts);
        $('#btn_upload_logo').click(function (){
            $("#upload_content").html("loading...");
            $.ajax({
        		url: '<?php echo url::base();?>site/config/logo_upload',
                type: 'GET',
                dataType: 'json',
                error: function() {
                    //window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#upload_content").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#upload_content").dialog("open");
            return false;
        });
    });
</script>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">站点配置信息</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--**edit start**-->
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base().url::current();?>">
                <div class="edit_area">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="20%">
                                        站点名称：
                                    </th>
                                    <td colspan="2"><input size="50" title="站点名称不能为空" name="name" class="text required" value="<?php echo $data['name'];?>"><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        站点域名：
                                    </th>
                                    <td colspan="2"><input size="50" title="站点域名不能为空" name="domain" class="text required" value="<?php echo $data['domain'];?>"><span class="required"> *</span> 只填写主域名，如：zz.com</td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        站点标题：
                                    </th>
                                    <td colspan="2"><input size="50" title="站点标题不能为空" name="site_title" class="text required" value="<?php echo $data['site_title'];?>"><span class="required"> *</span></td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        站点Logo：
                                    </th>
                                    <td colspan="2">
                                        <?php if(isset($data['logo'])):?>
                                        <img id="logo_img" src="<?php echo $data['logo'];?>" alt=""/>
                                        <input type='hidden' name="logo" value="<?php echo $data['logo'];?>" />
                                        [<a href='/site/config/delete_logo'>删除logo</a>]
                                        <?php else:?>
                                        <img id="logo_img" style="display:none;"/><span class="notice_inline">无站点Logo站点默认只显示标题！</span>
                                        <?php endif;?>                                        
                                        <div class="clear"></div>
                                        <a href="javascript:void(0);" id="btn_upload_logo">点击上传新Logo</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        站点邮箱：
                                    </th>
                                    <td colspan="2">
                                        <input size="50" title="站点邮箱不能为空!" name="site_email" class="text required email" value="<?php echo $data['site_email'];?>"><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                       	 Copyright设置：
                                    </th>
                                    <td colspan="2">
                                        <input size="50" name="copyright" class="text" value="<?php echo $data['copyright'];?>">
                                        <span class="brief-input-state notice_inline">支持变量：{year}->当前年；{month}->当前月；{day}->当前日；{domain}->当前站点。</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        Twitter链接设置：
                                    </th>
                                    <td colspan="2">
                                        <input size="50" name="twitter" class="text" value="<?php echo $data['twitter'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        Facebook链接设置：
                                    </th>
                                    <td colspan="2">
                                        <input size="50" name="facebook" class="text" value="<?php echo $data['facebook'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        Youtube链接设置：
                                    </th>
                                    <td colspan="2">
                                        <input size="50" name="youtube" class="text" value="<?php echo $data['youtube'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        Trustwave链接设置：
                                    </th>
                                    <td colspan="2">
                                        <textarea cols="100" rows="3" name="trustwave"><?php echo $data['trustwave'];?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        Macfee代码：
                                    </th>
                                    <td colspan="2">
                                        <textarea cols="100" rows="3" name="macfee"><?php echo $data['macfee'];?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        livechat代码：
                                    </th>
                                    <td colspan="2">
                                        <textarea cols="100" rows="5" name="livechat"><?php echo $data['livechat'];?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        	全局头部代码：
                                    </th>
                                    <td colspan="2">
                                        <textarea cols="100" rows="3" name="head_code"><?php echo $data['head_code'];?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        	全局底部代码：
                                    </th>
                                    <td colspan="2">
                                        <textarea cols="100" rows="3" name="body_code"><?php echo $data['body_code'];?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                                                                                           首页特殊代码：
                                    </th>
                                    <td colspan="2">
                                        <textarea cols="100" rows="3" name="index_code"><?php echo $data['index_code'];?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        	产品页代码：
                                    </th>
                                    <td colspan="2">
                                        <textarea cols="100" rows="3" name="product_code"><?php echo $data['product_code'];?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                       	 支付页代码：
                                    </th>
                                    <td>
                                        <textarea cols="100" rows="3" name="payment_code"><?php echo $data['payment_code'];?></textarea>
                                        
                                    </td>
                                    <td><span class="brief-input-state notice_inline">
                                        	  		  {order_num} -> 订单号；{order_value} -> 订单金额；
                                        			  {category_name} -> 产品所在分类；{product_sku} -> 产品SKU；
                                        			  {product_name} -> 产品名称；{price} -> 产品价格；
                                        			  {quantity} -> 订单产品数量。
                                        </span></td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                       	 支付成功页代码：
                                    </th>
                                    <td>
                                        <textarea cols="100" rows="3" name="pay_code"><?php echo $data['pay_code'];?></textarea>
                                        
                                    </td>
                                    <td><span class="brief-input-state notice_inline">
                                        	  		  {order_num} -> 订单号；{order_value} -> 订单金额；
                                        			  {category_name} -> 产品所在分类；{product_sku} -> 产品SKU；
                                        			  {product_name} -> 产品名称；{price} -> 产品价格；
                                        			  {quantity} -> 订单产品数量。
                                        </span></td>
                                </tr>
                                <tr>
                                    <th width="20%">
                                        	注册验证：
                                    </th>
                                    <td colspan="2">
                                      <input type="radio" name="register_mail_active" value="0" <?php $data['register_mail_active']==0 && print('checked="true"');?>/> 无需验证
                                      <input type="radio" name="register_mail_active" value="1" <?php $data['register_mail_active']==1 && print('checked="true"');?>/> 邮箱验证
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input name="submit" type="submit" class="ui-button" value=" 保存 ">
                </div>
            </form>
            <!--**edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<div id='upload_content' style="display:none;"></div>
<script type="text/javascript">
    $.extend($.validator.messages,{
        required:"站点邮箱不能为空!",
        email:"请输入一个可用的邮箱!"
    });

</script>