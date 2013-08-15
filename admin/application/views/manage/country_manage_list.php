<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'manage/country_manage/';?>'>国家配置管理</a></li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>

        <div class="newgrid_top">
            <ul class="pro_oper">
                <li><a href="/manage/country_manage/add"><span class="add_pro">添加国家</span></a></li>
                <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>
            </ul>
        </div>
        <?php if (is_array($country_manages) && count($country_manages)) {?>
        <table  cellspacing="0">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="100">操作</th>
                        <th width="100">国家代码</th>
                        <th width="200">英文名称</th>
                        <th width="200">中文名称</th>
                        <th width="30">状态</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($country_manages as $rs) : ?>
                    <tr>
                        <td><input class="sel" name="country_ids[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
                        <td>
                        <a href="<?php echo url::base();?>manage/country_manage/edit/<?php echo $rs['id'];?>">编辑</a>
                        <a class="act_dodelete" href="<?php echo url::base();?>manage/country_manage/do_delete/<?php echo $rs['id'];?>">删除</a>
                        </td>
                        <td><?php echo $rs['iso_code'];?></td>
                        <td><?php echo $rs['name'];?></td>
                        <td><?php echo $rs['name_manage'];?></td>
                        <td><?php echo view_tool::get_active_img($rs['active']);?></td>
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
<script type="text/javascript" src="<?php echo url::base();?>js/message.js"></script>
<script type="text/javascript">
    $(function() {
        //删除
        $("#batch_delete").click(function(){
        	var list_form   = $('#list_form');
            var ids_checked = $('input[class="sel"]:checked').length;
            if (ids_checked > 0) { 
         	    confirm("确定删除所有被选中的项吗?",function(){
         		    list_form.attr('action','<?php echo url::base();?>manage/country_manage/batch_delete');
					ajax_block.open();
         		    list_form.submit();
         	    });
            }else{
            	showMessage('操作失败','请选择要删除的项!');
            }
            return false;
        });

        $("a.act_dodelete").unbind().bind('click keyup',function(e){
        	obj = $(this);
            confirm('确认要删除此项?',function(){
            	ajax_block.open();
                location.href = obj.attr('href');
            });
            return false;
        });
    });
</script>