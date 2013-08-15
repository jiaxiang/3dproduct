<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">订单导出配置列表</li>
            </ul>
            <span class="fright">
                <?php if(site::id()>0):?>
                当前站点：<a href="http://<?php echo site::domain();?>" target="_blank" title="点击访问"><?php echo site::domain();?></a> [ <a href="<?php echo url::base();?>manage/site/out">返回</a> ]
                <?php endif;?>
            </span>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">删除</span></a></li>
                <li><a href="/order/order_export/view"><span class="add_pro">添加新配置</span></a></li>
            </ul>
        </div>
        <?php if (is_array($order_exports) && count($order_exports)) {?>
        <table  cellspacing="0">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="80">操作</th>
                        <th width="120">名称</th>
                        <th>添加时间</th>
                    </tr>
                </thead>
                <tbody>
                        <?php foreach ($order_exports as $key=>$rs) { ?>
                    <tr>
                        <td>
                            <input class="sel" name="order_export_config_ids[]" value="<?php echo $rs['id'];?>" type="checkbox" temp="<?php echo $key;?>" <?php if($rs['id'] == 1) {;?>disabled="true"<?php }?>>
                        </td>
                        <td><a href="<?php echo url::base();?>order/order_export/view/<?php echo $rs['id'];?>">编辑</a></td>
                        <td><?php echo $rs['name'];?></td>
                        <td><?php echo $rs['date_add'];?></td>
                    </tr>
                            <?php }?>
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
        //批量删除用户
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
                alert('请选择要删除的导出配置！');
                return false;
            }
            if(!confirm('确认删除选中的导出配置？')){
                return false;
            }
            $('#list_form').attr('action','/order/order_export/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>