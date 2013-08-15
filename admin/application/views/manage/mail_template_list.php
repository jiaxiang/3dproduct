<?php defined('SYSPATH') OR die('No direct access allowed.');
$list_columns = array(
	array('name'=>'ID','column'=>'id','class_num'=>'3', 'width'=>'40'),
	array('name'=>'名称','column'=>'name','class_num'=>'4', 'width'=>'100'),
	array('name'=>'分类','column'=>'mail_category_name','class_num'=>'4', 'width'=>'100'),
	array('name'=>'标题','column'=>'title','class_num'=>'6', 'width'=>'150'),
	array('name'=>'内容','column'=>'content_small','class_num'=>'6', 'width'=>'200'),
	array('name'=>'添加时间','column'=>'add_time','class_num'=>'4', 'width'=>'150'),
	array('name'=>'更新时间','column'=>'update_time','class_num'=>'4', 'width'=>'150'),
	array('name'=>'状态','column'=>'active_img','class_num'=>'3', 'width'=>'40')
);   
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'manage/mail_template/';?>'>邮件模板</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="<?php echo url::base();?>manage/mail_template/add"><span class="add_pro">添加新模板</span></a>
                </li>
            </ul>
        </div>
        <?php if (is_array($mail_templates) && count($mail_templates)) {?>
        <table  cellspacing="0" class="table_overflow">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="100px">操作</th>
                        <?php
                            foreach ($list_columns as $key=>$value):
                                echo '<th title="' . $value['name'] . '" width="' . $value['width'] . '">' . $value['name'] . '</th>';
                            endforeach;
                        ?>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($mail_templates as $value) : ?>
                    <tr>
                        <!-- <td><input class="sel" name="mail_template_id[]" value="<?php echo $value['id'];?>" type="checkbox" /></td> -->
                        <td><a href="<?php echo url::base();?>manage/mail_template/edit/<?php echo $value['id'];?>">编辑</a>
                                    <a href="<?php echo url::base();?>manage/mail_template/delete/<?php echo $value['id'];?>" onclick="return confirm('确认删除?');">删除</a>
                        </td>
                        <?php
                        foreach ($list_columns as $column_key=>$column_value):
                              if ($column_value['column'] == 'content_small')
                                      echo '<td><a href="javascript:void(0);" class="contentsmall" id="' . $value['id'] . '">' . $value[$column_value['column']] . '</a></td>';
                              else
                                      echo '<td>' . $value[$column_value['column']] . '</td>';
                        endforeach;
                        ?>
                    </tr>
                     <?php endforeach;?>
                </tbody>
         </form>
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
<div id="example"></div>
<script type="text/javascript">
    $(document).ready(function(){
        //define config object
        var dialogOpts = {
            title: "邮件模板内容",
            modal: true,
            autoOpen: false,
            height: 500,
            width: 600
        };
        $("#example").dialog(dialogOpts);

        $(".contentsmall").each(function(){
            var id = $(this).attr('id');
            $(this).click(function (){
                $("#example").html("loading...");
                $.ajax({
            		url: '<?php echo url::base();?>manage/mail_template/ajax_content' + '?id=' + id,
                    type: 'GET',
                    dataType: 'json',
                    error: function() {
                        window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                    },
                    success: function(retdat, status) {
        				ajax_block.close();
        				if (retdat['code'] == 200 && retdat['status'] == 1) {
        					$("#example").html(retdat['content']);
        				} else {
        					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
        				}
        			}
            	});
                $("#example").dialog("open");
                return false;
            }
        );
        });
    });
</script>
