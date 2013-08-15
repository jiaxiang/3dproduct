<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">添加子帐号</li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
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
                                    <th width="25%"> 用户名： </th>
                                    <td>
                                        <input size="30" name="username" class="text required" maxlength="100"  value=""><span class="required"> *</span>
                                        [用户登录时候的用户名]
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                	邮箱：
                                    </th>
                                    <td>
                                        <input size="40" name="email" class="text required email" maxlength="320" value=""><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                	密码：
                                    </th>
                                    <td>
                                        <input size="30" id="password1" name="password1" type="password" class="text required"  maxlength="50" minlength="6" value=""><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                	重复密码：
                                    </th>
                                    <td>
                                        <input size="30" id="password2" name="password2" type="password" class="text required" maxlength="50" minlength="6" value=""><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                	姓名：
                                    </th>
                                    <td>
                                        <input size="50" name="name" class="text required" maxlength="250" value=""><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>选择帐户权限模板: </th>
                                    <td>
                                        <select name="role_id" class="text">
                                            <option value=""> 单独分配权限 </option>
                                            <?php
                                            foreach($roles as $key=>$value):
                                                echo '<option value="' . $value['id'] . '"> ' . $value['name'] . ' </option>';
                                            endforeach;
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>状态: </th>
                                    <td>
                                        <input name="active" type="radio" value="1" checked>
                                        可用
                                        <input type="radio" name="active" value="0">
                                        不可用
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="list_save">
                    <input type="button" name="button" class="ui-button" value="保存返回列表"  onclick="submit_form();"/>
                    <input type="button" name="button" class="ui-button" value="保存当前"  onclick="submit_form(1);"/>
                    <input type="hidden" name="permission" class="ui-button" value="单独分配权限" onclick='submit_form(2);'/>
                    <input type="hidden" name="submit_target" id="submit_target" value="0" />
                </div>
            </form>
            <!--**productlist edit end**-->
        </div>
    </div>
</div>
<!--**content end**-->
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script src="<?php echo url::base();?>js/jquery.provincesCity.js" type="text/javascript"></script>
<script src="<?php echo url::base();?>js/provincesdata.js" type="text/javascript"></script>
<script type="text/javascript">
//zhu add
var obj_role = $("select[name='role_id']");
var obj_permission = $("input[name='permission']");
obj_role.change(function(){
	if(!obj_role.val()){
		obj_permission.attr("disabled",false);
	}else{
		obj_permission.attr("disabled",true);
	}
}); 
    $(document).ready(function(){
        $("#add_form").validate({
            rules: {
                password2: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password1"
                }
            }
        });
        $("#ChinaArea").ProvinceCity();
    });
    function change_country(Obj){
        var country = $(Obj).val();
        if(country == 'CN'){
            $("#state").hide();
            $("#township").show();
            $("#city").show();
            $("#province").show();
        }else{
            $("#state").show();
            $("#township").hide();
            $("#city").hide();
            $("#province").hide();
        }
    }
</script>