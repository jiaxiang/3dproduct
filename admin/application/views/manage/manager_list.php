<?php defined('SYSPATH') OR die('No direct access allowed.');
$list_columns = array(
	array('name'=>'用户名','column'=>'username','class_num'=>'3', 'width'=>'100'),
	array('name'=>'名称','column'=>'name','class_num'=>'5', 'width'=>'150'),
	array('name'=>'邮箱','column'=>'email','class_num'=>'5', 'width'=>'150'),
	array('name'=>'用户权限模板','column'=>'role_name','class_num'=>'4', 'width'=>'100'),
	array('name'=>'上级','column'=>'parent_email','class_num'=>'5', 'width'=>'150'),
	array('name'=>'添加时间','column'=>'add_time','class_num'=>'4', 'width'=>'180')
);   
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'manage/manager/';?>'>系统帐号列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <?php //echo role::view_check('<li><a href="/manage/manager/add"><span class="add_pro">添加帐号</span></a></li>', 'manage_manager_add');?>
                <?php //echo role::view_check('<li><a href="/manage/manager/child"><span class="add_child">添加子帐号</span></a></li>', 'manage_manager_child');?>
                <?php echo role::view_check('<li><a href="/manage/manager/add_manager"><span class="add_peo">添加管理员</span></a></li>', 'manage_admin_account');?>
                <?php //echo role::view_check('<li><a href="javascript:void(0);" onclick="cg_manager_link();"><span class="edit_peo">调整帐号</span></a></li>', 'change_account_link');?>
            </ul>
        </div>
        <?php if (is_array($managers) && count($managers)) { ?>
        <table  cellspacing="0">
            <thead>
                <tr class="headings">
                    <th width="20px"><input type="checkbox" id="check_all"></th>
                    <th width="180">操作</th>
                        <?php        
                        foreach ($list_columns as $key=>$value):
                            echo '<th title="' . $value['name'] . '" width="' . $value['width'] . '">' . $value['name'] . '</th>';
                        endforeach;
                        ?>
                    <th width="30px">状态</th>
                </tr>
            </thead>
            <tbody>
                    <?php foreach ($managers as $value) : ?>
                <tr>
                    <td><input tags="null" class="sel" name="manager_id[]" value="<?php echo $value['id'];?>" type="checkbox"></td>
                    <td>
                        <?php if ($value['id'] <> $this->manager_id):?>
                        <a href="<?php echo url::base();?>manage/manager/edit/id/<?php echo $value['id'];?>">编辑</a>
                        <a href="<?php echo url::base();?>manage/manager/rule_view/id/<?php echo $value['id'];?>">权限列表</a>
                        <a href="<?php echo url::base();?>manage/manager/rule/<?php echo $value['id'];?>">权限设置</a>
                        <?php echo role::view_check('<a href="' . url::base() . 'manage/manager/delete/' . $value['id'] . '" onclick="return confirm(\'确认删除?\');">删除</a>', 'delete_merchant');?>
                        <?php endif;?>
                    </td>
                            <?php
                            foreach ($list_columns as $column_key=>$column_value):
                            	//zhu add
                                if($column_value['column']=='role_name')
                                {
                                	echo '<td>' . ($value[$column_value['column']]?$value[$column_value['column']]:'单独分配') . '</td>';
                                }
                                else
                                {
                                    echo '<td>' . $value[$column_value['column']] . '</td>';
                                }
                            endforeach;
                            ?>
                    <td><?php echo view_tool::get_active_img($value['active']);?></td>
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
<?php if($show_page):?>
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
<?php endif;?>
<div id="dialog_m"></div>
<script type="text/javascript">
    $(document).ready(function(){
        var dialogOpts = {
            title: "更新帐号关系",
            modal: true,
            autoOpen: false,
            height: 200,
            width: 600
        };
        $("#dialog_m").dialog(dialogOpts);
    });

    function cg_manager_link(){
        var id_str = "";
        $(".sel").each(function(){
            if($(this).attr('checked') == true){
                id_str += $(this).val() + ",";
            }
        });
        if(id_str == ""){
            alert("请选择用户.");
            return false;
        }
        $("#dialog_m").html("loading...");
        $.ajax({
    		url: '<?php echo url::base();?>manage/manager/ajax_change_parent',
            type: 'POST',
            data: {"id_str" : id_str},
            dataType: 'json',
            error: function() {
                window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
            },
            success: function(retdat, status) {
				ajax_block.close();
				if (retdat['code'] == 200 && retdat['status'] == 1) {
					$("#dialog_m").html(retdat['content']);
				} else {
					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
				}
			}
    	});
        $("#dialog_m").dialog("open");
    }
</script>