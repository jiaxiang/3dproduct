<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'manage/payment_type/';?>'>支付类型列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
            <!--  
                <li>
                    <a href="<?php //echo url::base();?>manage/payment_type/add"><span class="add_pro">添加支付类型</span></a>
                </li>
            -->
                <li><a href="javascript:void(0);"><span class="del_pro" id="batch_delete">批量删除</span></a></li>
            </ul>
        </div>
        <?php if (is_array($payment_type_list) && count($payment_type_list)) {?>
        <table  cellspacing="0" class="table_overflow">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="20px"><input type="checkbox" id="check_all"></th>
                        <th width="30">操作</th>
                        <?php echo view_tool::sort('ID号', 0, 40);?>
                        <?php echo view_tool::sort('名称', 2, 180);?>
                        <?php echo view_tool::sort('图片', 4, 250);?>
                        <?php echo view_tool::sort('驱动', 6, 80);?>
                        <?php echo view_tool::sort('提交地址', 8, 300);?>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($payment_type_list as $rs) : ?>
                    <tr>
                        <td><input class="sel" name="payment_type_id[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
                        <td><a href="<?php echo url::base();?>manage/payment_type/edit/id/<?php echo $rs['id'];?>">编辑</a> 
                         </td>
                        <td><?php echo $rs['id'];?>&nbsp;</td>
                        <td><?php echo $rs['name'];?>&nbsp;</td>
                        <td><?php echo $rs['image_url'];?>&nbsp;</td>
                        <td><?php echo $rs['driver'];?>&nbsp;</td>
                        <td><?php echo $rs['submit_url'];?>&nbsp;</td>
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

<script type="text/javascript">
		$(function() {
        //删除支付类型
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
                alert('请选择要删除的支付类型！');
                return false;
            }
            if(!confirm('确认删除选中的支付类型？')){
                return false;
            }
            $('#list_form').attr('action','/manage/payment_type/batch_delete/');
            $('#list_form').submit();
            return false;
        });
    });
</script>