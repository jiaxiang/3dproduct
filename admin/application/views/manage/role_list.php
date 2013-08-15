<?php defined('SYSPATH') OR die('No direct access allowed.');
$list_columns = array(
	array('name'=>'上级','column'=>'parent_name','class_num'=>'3', 'width'=>'100'),
	array('name'=>'名称','column'=>'name','class_num'=>'6', 'width'=>'100'),
	array('name'=>'类型','column'=>'type_name','class_num'=>'3', 'width'=>'100'),
	array('name'=>'添加时间','column'=>'add_time','class_num'=>'4', 'width'=>'150'),
	array('name'=>'状态','column'=>'active_img','class_num'=>'2', 'width'=>'40')
);
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'manage/role/';?>'>权限模板列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <?php echo role::view_check('<li><a href="/manage/role/add"><span class="add_pro">添加权限模板</span></a></li>', 'role_edit');?>
            </ul>
        </div>
        <?php if (is_array($roles) && count($roles)) {?>
        <table  cellspacing="0">
            <thead>
                <tr class="headings">
                    <!-- <th width="20"><input type="checkbox" id="check_all"></th> -->
                    <th width="150">操作</th>
                        <?php
                        foreach ($list_columns as $key=>$value):
                            echo '<th title="' . $value['name'] . '" width="' . $value['width'] . '">' . $value['name'] . '</th>';
                        endforeach;
                        ?>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                    <?php foreach ($roles as $value) : ?>
                <tr>
                    <!-- <td><input class="sel" name="role_ids[]" value="<?php echo $value['id'];?>" type="checkbox" /></td> -->
                    <td><?php echo role::view_check('<a href="/manage/role/edit/' . $value['id'] . '">编辑</a>', 'role_edit');?>
                        <a href="/manage/role/view_rule/<?php echo $value['id'];?>" >权限</a>
                                <?php echo role::view_check('<a href="/manage/role/rule/' . $value['id'] . '">权限设置</a>', 'role_edit');?>
                                <?php //echo role::view_check('<a href="/manage/role/delete/' . $value['id'] . '" onclick="return confirm(\'确认删除?\');">删除</a>', 'role_edit');?>
                    </td>
                            <?php
                            foreach ($list_columns as $column_key=>$column_value):
                                echo '<td>' . $value[$column_value['column']] . '</td>';
                            endforeach;
                            ?>
                    <td></td>
                </tr>
                    <?php endforeach;?>
            </tbody>
        </table>
            <?php }else {?>
            <?php echo remind::no_rows();?>
            <?php }?>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
        <?php echo view_tool::per_page(); ?>
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
            <?PHP echo $this->pagination->render('opococ'); ?>
        </div>
    </div>
</div>
<!--END FOOTER-->