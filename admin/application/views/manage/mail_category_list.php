<?php defined('SYSPATH') OR die('No direct access allowed.');
$list_columns = array(
	array('name'=>'ID','column'=>'id','class_num'=>'3', 'width'=>'40'),
	array('name'=>'名称','column'=>'name','class_num'=>'4', 'width'=>'200'),
	array('name'=>'标记','column'=>'flag','class_num'=>'4', 'width'=>'150'),
	array('name'=>'添加时间','column'=>'add_time','class_num'=>'4', 'width'=>'150'),
	array('name'=>'状态','column'=>'active_img','class_num'=>'2', 'width'=>'40')
);
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'manage/mail_category/';?>'>邮件分类列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <?php echo role::view_check('<li><a href="/manage/mail_category/add"><span class="add_pro">添加邮件分类</span></a></li>', 'mail_category_edit');?>
            </ul>
        </div>
        <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <!-- <th width="20px"><input type="checkbox" id="check_all"></th> -->
                        <th width="100px">操作</th>
                        <?php
                            foreach ($list_columns as $key=>$value):
                                echo '<th title="' . $value['name'] . '" width="' . $value['width'] . '">' . $value['name'] . '</th>';
                            endforeach;
                        ?>
                    </tr>
                </thead>
                <tbody>
                     <?php if (is_array($mail_categories) && count($mail_categories)) :?>
                     <?php foreach ($mail_categories as $value) : ?>
                    <tr>
                        <!-- <td><input class="sel" name="mail_category_id[]" value="<?php echo $value['id'];?>" type="checkbox" /></td> -->
                        <td><?php echo role::view_check('<a href="' . url::base() . 'manage/mail_category/edit/' . $value['id'] . '" >编辑</a>', 'mail_category_edit');?>
                            <?php echo role::view_check('<a href="' . url::base() . 'manage/mail_category/delete/' . $value['id'] . '" onclick="return confirm(\'确认删除?\');">删除</a>', 'mail_category_edit');?>
                        </td>
                        <?php
                        foreach ($list_columns as $column_key=>$column_value):
                             echo '<td>' . $value[$column_value['column']] . '</td>';
                        endforeach;
                        ?>
                    </tr>
                     <?php endforeach;?>
                     <?php endif; ?>
                </tbody>
        </form>
        </table>
    </div>
</div>
<!--**content end**-->