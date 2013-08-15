<?php defined('SYSPATH') OR die('No direct access allowed.');
$list_columns = array(
	array('name'=>'ID','column'=>'id','class_num'=>'40'),
	array('name'=>'标题','column'=>'title','class_num'=>'120'),
	array('name'=>'发布者','column'=>'manager_id','class_num'=>'80'),
	array('name'=>'发布时间','column'=>'add_time','class_num'=>'120')		
);
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'manage/notice/';?>'>公告</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="<?php echo url::base();?>manage/notice/add"><span class="add_pro">添加公告</span></a>
                </li>
                <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索：
                    <select name="search_type" class="text">
                        <option value="title">标题</option>
                        <option value="content">内容</option>
                    </select>
                    <input type="text" name="search_value" class="text" value="<?php isset($where['search_value']) && !empty($where['search_value']) && print($where['search_value']);?>" size="30">
                    <input type="submit" name="Submit2" value="搜索" class="ui-button-small">
                </div>
            </form>
        </div>
        <?php if (is_array($notice) && count($notice)) {?>
        <table  cellspacing="0">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20px"><input type="checkbox" id="check_all"></th>
                        <th width="100px">操作</th> 
                            <?php
                            foreach ($list_columns as $key=>$value):
                                echo '<th title="' . $value['name'] . '" width="' . $value['class_num'] . '">' . $value['name'] . '</div>';
                            endforeach;
                            ?>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($notice as $value) : ?>
                    <tr>
                        <td><input class="sel" name="notice_ids[]" value="<?php echo $value['id'];?>" type="checkbox" /></td>
                        <td><a href="<?php echo url::base();?>manage/notice/edit/<?php echo $value['id'];?>">编辑</a>
                            <a href="<?php echo url::base();?>manage/notice/delete/<?php echo $value['id'];?>" onclick="return confirm('确认删除?');">删除</a>
                        </td>
                                <?php
                                foreach ($list_columns as $column_key=>$column_value):
                                    if ($column_value['column'] == 'title') {
                                        echo '<td width="250px"><a href="javascript:void(0);" id="' . $value['id'] . '" class="contentsmall">' . tool::my_substr($value[$column_value['column']], 40) . '</a></td>';
                                    }
                                    else {
                                        echo '<td>' . $value[$column_value['column']] . '</td>';
                                }
                                endforeach;
                                ?>
                        <td></td>
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

<div id='example'></div>
<script type="text/javascript">
    $(document).ready(function(){
        //define config object
        var dialogOpts = {
            title: "公告内容",
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
            		url: '<?php echo url::base();?>manage/notice/ajax_content' + '?id=' + id,
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

    $(function() {
        //删除公告
        $("#batch_delete").click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要删除的公告！');
                return false;
            }
            if(!confirm('确认删除选中的公告？')){
                return false;
            }
            $('#list_form').attr('action','/manage/notice/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>