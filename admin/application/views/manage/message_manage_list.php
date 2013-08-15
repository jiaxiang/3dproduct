<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'manage/message/';?>'>管理员留言列表管理</a></li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>
            </ul>
        </div>
        <?php if (is_array($messages) && count($messages)) {?>
        <table  cellspacing="0" class="table_overflow">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20px"><input type="checkbox" id="check_all"></th>
                        <th width="100px">操作</th>
						<?php echo view_tool::sort('商家名称', 2, 80);?>
                        <?php echo view_tool::sort('优先级', 4, 60);?>
                        <?php echo view_tool::sort('留言标题', 6, 220);?>
                        <?php echo view_tool::sort('商家邮箱', 8,150);?>
                        <th width="100px">联系电话</th>
                        <?php echo view_tool::sort('留言时间', 10 ,150);?>
                        <?php echo view_tool::sort('IP/地址', 12, 120);?>
                        <?php echo view_tool::sort('回复状态', 14, 70);?>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($messages as $rs) : ?>
                    <tr>
                        <td><input class="sel" name="message_id[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
                        <td><a href="<?php echo url::base();?>manage/message/edit?id=<?php echo $rs['id'];?>">回复</a> 
                            <a href="<?php echo url::base();?>manage/message/delete?id=<?php echo $rs['id'];?>" onclick="return confirm('确认删除留言?');">删除</a>
                        </td>
						<td><?php echo $rs['site_manager_name'];?></td>
						<td><?php echo $rs['status'];?></td>
						<td><?php echo $rs['title'];?></td>
						<td><?php if(isset($rs['email']) && !empty($rs['email'])) echo $rs['email']; else echo '无';?></td>
						<td><?php echo $rs['phone'];?></td>
						<td><?php echo $rs['create_timestamp'];?></td>
						<td><?php echo long2ip($rs['ip']);?></td>
						<td><?php echo $rs['reply_status'];?></td>
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
		$(function() {
        //删除留言
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
            $('#list_form').attr('action','/manage/message/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>