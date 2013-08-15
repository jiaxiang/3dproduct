<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">修改帐号密码</li>
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
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="25%"> 原密码： </th>
                                    <td>
                                        <input size="40" name="password" class="text required" maxlength="250" value="" type="password"/><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th> 新密码： </th>
                                    <td>
                                        <input id="password1" size="40" name="password1" class="text required" maxlength="250" minlength="6" value="" type="password"/><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th> 重复新密码： </th>
                                    <td>
                                        <input id="password2" size="40" name="password2" class="text required" maxlength="250" minlength="6" equalTo="#password1" value="" type="password"/><span class="required"> *</span>
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
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#add_form").validate();
    });
</script>