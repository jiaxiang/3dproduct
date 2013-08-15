<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'user/contact_us/';?>'>留言列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                 <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索:
                	 <select name="search_type">
                             <option value="email" <?php if ($where['search_type'] == 'email') echo 'selected'?>>邮箱</option>
                             <option value="name" <?php if ($where['search_type'] == 'name') echo 'selected'?>>名字</option>
                             <option value="ip" <?php if ($where['search_type'] == 'ip') echo 'selected'?>>IP</option>
                     </select>
                    <input type="text" class="text" name="search_value" value="<?php echo $where['search_value']?>">
                     <input type="submit" name="Submit2" value="搜索" class="ui-button-small">
                </div>
            </form>

        </div>
        <?php if(is_array($contact_us_list) && count($contact_us_list)){ ?>
        <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="100">操作</th>
                        <?php echo view_tool::sort('Email', 4, 200);?>
                        <?php echo view_tool::sort('名字', 6, 100);?>
                        <?php echo view_tool::sort('信息', 8, 200);?>
                        <?php echo view_tool::sort('回复', 8, 200);?>
                        <?php echo view_tool::sort('注册时间', 10, 150);?>
                        <th width="150">IP/地址</th>
                        <th>状态</th>  
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($contact_us_list as $rs) : ?>
                    <tr>
                        <td><input class="sel" name="user_messageids[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
                        <td><a href="javascript:void(0);" class="contact_us_edit" id="<?php echo $rs['id'];?>">回复</a>
                             <?php if($rs['active'] == 1):?>
                             <a href="<?php echo url::base();?>user/contact_us/do_active/<?php echo $rs['id'];?>">设为已处理</a>
                             <?php endif;?>
                        </td>
                        <td><?php echo $rs['email'];?>&nbsp;</td>
                        <td><?php echo $rs['name'];?>&nbsp;</td>
                        <td><?php echo tool::my_substr($rs['message'], 30);?>&nbsp;</td>
                        <td><?php echo tool::my_substr($rs['return_message'], 30);?>&nbsp;</td>
                        <td><?php echo $rs['date_add'];?>&nbsp;</td>
                        <td><?php echo long2ip($rs['ip']);?>&nbsp;</td>
                        <td><a href="<?php echo url::base();?>user/contact_us/do_active/<?php echo $rs['id'];?>">
                            <?php echo view_tool::get_active_img($rs['active'],false);?>
                            </a></td>
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
<div id='ajax_edit_content' style="display:none;"></div>
<script type="text/javascript">
    $(document).ready(function(){
        var dialogOpts = {
            title: "处理留言",
            modal: true,
            autoOpen: false,
            height: 350,
            width: '80%'
        };

        $("#ajax_edit_content").dialog(dialogOpts);
        $(".contact_us_edit").click(function(){
            var id = $(this).attr('id');
            $("#ajax_edit_content").html("loading...");
            $.ajax({
        		url: '<?php echo url::base();?>user/contact_us/ajax_edit' + '?id=' + id,
                type: 'GET',
                dataType: 'json',
                error: function() {
                    window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
                },
                success: function(retdat, status) {
    				ajax_block.close();
    				if (retdat['code'] == 200 && retdat['status'] == 1) {
    					$("#ajax_edit_content").html(retdat['content']);
    				} else {
    					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
    				}
    			}
        	});
            $("#ajax_edit_content").dialog("open");
        });
    });
</script>
<script type="text/javascript">
		$(function() {
        //批量删除留言
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
                alert('请选择要删除的留言！');
                return false;
            }
            if(!confirm('确认删除选中的留言？')){
                return false;
            }
            $('#list_form').attr('action','/user/contact_us/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>