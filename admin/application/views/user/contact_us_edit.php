<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<form id="add_form" name="add_form" method="post" action="<?php echo url::base() . 'user/contact_us/do_edit/' . $data['id'];?>">
    <div class="out_box">
        <table width="98%" border="0" cellpadding="0" cellspacing="0" >
            <thead>
                <tr>
                    <th>名字：</th>
                    <td colspan="3"><?php echo $data['name']?></td>
                </tr>
            </thead>
            <tr>
                <th>邮箱：</th>
                <td><?php echo $data['email']?></td>
                <th>是否收到邮件：</th>
                <td><?php echo view_tool::get_active_img($data['is_receive']);?></td>
            </tr>
            <tr>
                <th>时间：</th>
                <td><?php echo $data['date_add']?></td>
                <th>IP/地址：</th>
                <td><?php echo long2ip($data['ip'])?></td>
            </tr>
            <tr>
                <th >留言内容：</th>
                <td colspan="3">
                    <?php echo $data['message'];?>&nbsp;
                </td>
            </tr>
            <tr>
                <th>回复<span class="required"> *</span>：</th>
                <td colspan="3" class="d_line_nospan">
                    <textarea type="textarea" rows="3" cols="60" name="return_message" class="text required"><?php echo $data['return_message']?></textarea>
                </td>
            </tr>
        </table>
    </div>
    <div class="list_save">
        <input name="submit" type="submit" class="ui-button" value=" 确认回复 " />
    </div>
</form>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate({
            rules: {
                return_message: "required"
            },
            messages: {
                return_message: "请输入回复内容！"
            }
        });
        /* 按钮风格 */
        $(".ui-button,.ui-button-small").button();
    });
</script>
