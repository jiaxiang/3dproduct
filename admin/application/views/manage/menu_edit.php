<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">编辑菜单项</li>
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
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                <div class="edit_area">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="25%">菜单名称：</th>
                                    <td>
                                        <input size="30" name="name" class="text required" value="<?php echo $data['name'];?>"><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>菜单标记：</th>
                                    <td>
                                        <input size="50" name="target" class="text required" value="<?php echo $data['target'];?>"><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>菜单URL：</th>
                                    <td>
                                        <input size="60" name="url" class="text required" value="<?php echo $data['url'];?>"><span class="required"> *</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>对应的权限资源：</th>
                                    <td>
                                        <select name="action_id" class="text">
                                            <option value="0"> -默认- </option>
                                            <?php
                                            foreach ($actions as $key=>$value):
                                            ?>
                                            <option value="<?php echo $value['id'];?>" <?php echo $value['selected'];?>>
                                                    <?php for ($i = 1;$i < $value['level_depth'];$i++) {?>
                                                &#166;&nbsp;
                                                        <?php } ?>
                                                    <?php echo $value['name'];?></option>
                                            <?php
                                            endforeach;
                                            ?>
                                        </select>
                                        	默认权限则所有用户可见
                                    </td>
                                </tr>
                                <tr>
                                    <th>上级菜单：</th>
                                    <td>
                                        <select name="parent_id" id="parent_id" class="required">
                                            <option value="0">--顶级菜单--</option>
                                            <?php if (is_array($menus) && count($menus)) {?>
                                                <?php foreach ($menus as $key=>$rs) {?>
                                            <option value="<?php echo $rs['id'];?>" <?php echo ($rs['id'] == $data['parent_id'])?'selected':'';?>>
                                                        <?php for ($i = 1;$i < $rs['level_depth'];$i++) {?>
                                                &#166;&nbsp;
                                                            <?php } ?>
                                                        <?php echo $rs['name'];?></option>
                                                    <?php } ?>
                                                <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>排序：</th>
                                    <td>
                                        <input size="10" name="order" class="text" value="<?php echo $data['order'];?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>别名(用于菜单定位的链接,多个以"|"分隔)：</th>
                                    <td>
                                        <textarea name="alias" cols="60" rows="3" class="text" type="textarea"><?php echo $data['alias'];?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>说明：</th>
                                    <td class="d_line">
                                        <textarea name="meno" cols="75" rows="5" class="text" type="textarea" maxlength="255"><?php echo $data['memo'];?></textarea>
                                        <span class="brief-input-state notice_inline">简短的菜单功能介绍，请不要超过255字节。</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>状态：</th>
                                    <td>
                                        <input name="active" type="radio" value="1" <?php echo ($data['active'] == 1)?"checked":"";?>>
                                        可用
                                        <input type="radio" name="active" value="0" <?php echo ($data['active'] == 0)?"checked":"";?>>
                                        不可用
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
        $("#add_form").validate({
        	errorPlacement:function(error, element){
	            if(element.attr("name") == "meno"){
	                //alert(error);
	                error.appendTo( element.parent());
	            }else{
	                error.insertAfter(element)
	            }
        	}
        });
    });
</script>