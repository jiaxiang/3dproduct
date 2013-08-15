<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<form id="address_edit_form" name="address_edit_form" method="post" action="<?php echo url::base() . 'user/address/do_edit/' . $address['id'];?>">
    <div class="out_box">
        <table width="98%" border="0" cellpadding="0" cellspacing="0" >
            <thead >
                <tr>
                    <th><span style="color:#F00">* </span>姓名：</th>
                    <td class="d_line"><input size="20" name="lastname" class="text required" value="<?php echo $address['lastname']?>"></td>
                </tr>
            </thead>
            <tr>
                <th><span style="color:#F00">* </span>电话：</th>
                <td class="d_line"><input size="20" name="phone" class="text" value="<?php echo $address['phone']?>"></td>
                <th>移动电话：</th>
                <td class="d_line"><input size="20" name="phone_mobile" class="text" value="<?php echo $address['phone_mobile']?>"></td>
            </tr>
            <tr>
                <th><span style="color:#F00">* </span>国家：</th>
                <td class="d_line">
                <select name="country" class="text required">
                <?php foreach($countries as $key=>$value):?>
                     <option value="<?php echo $key;?>" <?php echo ($key == $address['country'])?'selected':'';?>><?php echo $value;?></option>
                <?php endforeach;?>
                </select>
                </td>
                <th>省区：</th>
                <td class="d_line"><input size="20" name="state" class="text" value="<?php echo $address['state']?>"></td>
            </tr>
            <tr>
                <th><span style="color:#F00">* </span>城市：</th>
                <td class="d_line"><input size="20" name="city" class="text required" value="<?php echo $address['city']?>"></td>
                <th><span style="color:#F00">* </span>邮编/邮政编码：</th>
                <td class="d_line"><input size="20" name="zip" class="text required" value="<?php echo $address['zip']?>"></td>
            </tr>
            <tr>
                <th><span style="color:#F00">* </span>地址：</th>
                <td colspan="3" class="d_line"><input size="50" name="address" class="text required" value="<?php echo $address['address']?>"></td>
            </tr>
            <tr>
                <th >备注:</th>
                <td colspan="3" class="d_line"><textarea type="textarea" class="" rows="3" cols="50" name="other"><?php echo $address['other']?></textarea></td>
            </tr>
        </table>
    </div>
    <div class="list_save">
        <input name="submit" type="submit" class="ui-button" value=" 保存 " />
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $("#address_edit_form").validate({
            rules: {
                firstname: "required",
                lastname: "required",
                country: "required",
                city: "required",
                zip: "required",
                address: "required",
                phone:"required"
            },
            messages: {
                firstname: " 请输入您的姓！",
                lastname: " 请输入您的名！",
                country: "  请选择国家！",
                city: "  请输入城市名称！",
                zip: " 请输入邮编！",
                address: "请输入地址！",
                phone:"请输入电话号码！"
            }
        });

        /* 按钮风格 */
        $(".ui-button,.ui-button-small").button();
    });
</script>