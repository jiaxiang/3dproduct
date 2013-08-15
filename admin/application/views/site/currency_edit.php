<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">币种编辑</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <!--** edit start**-->
            <div class="edit_area">
                <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . url::current();?>">
                    <div class="out_box">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th>代码：</th>
                                    <td><?php echo $data['code'];?></td>
                                </tr>
                                <tr>
                                    <th>名称：</th>
                                    <td><?php echo $data['name'];?></td>
                                </tr>
                                <tr>
                                    <th>符号：</th>
                                    <td><?php echo $data['sign'];?></td>
                                </tr>
                                <tr>
                                    <th>汇率：</th>
                                    <td><input size="20" name="conversion_rate" class="text required" value="<?php echo $data['conversion_rate'];?>"><span class="required"> * </span> 1当前币种= ?美元
                                    </td>
                                </tr>
                                <tr>
                                    <th>是否默认显示：</th>
                                    <td>
                                        <input type="radio" name="default" value="1" <?php if ($data['default'] == 1)echo "checked";?>>
                                        默认
                                        <input type="radio" name="default" value="0" <?php if ($data['default'] == 0)echo "checked";?>>
                                        非默认
                                    </td>
                                </tr>
                                <tr>
                                    <th>是否可用：</th>
                                    <td>
                                        <input type="radio" name="active" value="1" <?php if ($data['active'] == 1)echo "checked";?>>
                                        可用
                                        <input type="radio" name="active" value="0" <?php if ($data['active'] == 0)echo "checked";?>>
                                        不可用
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="list_save">
                        <input type="submit" class="ui-button" name="button" value="编辑"  />
                    </div>
                </form>
            </div>
            <!--** edit end**-->
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
