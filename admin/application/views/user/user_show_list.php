<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'user/user_show/';?>'>用户晒宝列表</a></li>
            </ul>
        </div>
        <!-- div class="newgrid_top">
            <ul class="pro_oper">
                 <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索:
                	 <select name="search_type">
                     </select>
                     <input type="submit" name="Submit2" value="搜索" class="ui-button-small">
                </div>
            </form>

        </div -->
        <?php if(is_array($lists) && count($lists)) { ?>
        <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="100">操作</th>
                        <th width="150">用户名字</th>
                        <th>主题</th>
                        <th>感言</th>
                        <th width="100">时间</th>
                        <th width="100">前台是否显示</th>  
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($lists as $row) : ?>
                    <tr>
                        <td><a href="<?php echo url::base();?>user/user_show/edit?id=<?php echo $row['id'];?>">详细</a></td>
                        <td><?php isset($users[$row['user_id']]) && print($users[$row['user_id']]);?>&nbsp;</td>
                        <td><?php echo tool::my_substr($row['title'], 30);?>&nbsp;</td>
                        <td><?php echo tool::my_substr($row['memo'], 200);?>&nbsp;</td>
                        <td><?php echo $row['add_time'];?>&nbsp;</td>
                        <td>
                            <a href="<?php echo url::base();?>user/user_show/do_active/<?php echo $row['id'];?>">
                            <?php echo view_tool::get_active_img($row['status'],true);?>
                            </a>
                        </td>
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

<script type="text/javascript">
    $(function(){
        //批量删除
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
                alert('请选择要删除的项目！');
                return false;
            }
            if(!confirm('确认删除选中的项目？')){
                return false;
            }
            $('#list_form').attr('action','/user/user_show/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>