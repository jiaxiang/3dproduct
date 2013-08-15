<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='<?php echo url::base() . 'manage/site_payment/';?>'>站点支付列表</a></li>
            </ul> 
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="<?php echo url::base();?>manage/payment/add"><span class="add_pro">添加支付</span></a>
                </li>
            </ul>
        </div>
        <table  cellspacing="0" class="table_overflow">
            <thead>
                <tr class="headings">
                    <th>当前使用支付</th>
                    <th width="80">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if (is_array($p_list) && count($p_list)) :?>
                <?php foreach ($p_list as $rs) : ?>
                <tr>
                <form id="payment_del_form_<?php echo $rs['id'];?>" name="payment_del_form_<?php echo $rs['id'];?>" method="post" action="<?php echo url::base() . 'manage/site_payment/do_delete_payment/';?>" onsubmit="javascript:return confirm('确定要删除吗？');">
                <td>
                    <input type=hidden name="del_payment_id" value="<?php echo $rs['id'];?>">
                   <?php if ($rs['payment_type']) {?>                    
                         <?php echo $rs['payment_type']['name'];?><?php echo $rs['account']?'(' . $rs['account'] . ')':'';?> 
                   <?php }?>
                </td>
                <td><input type="submit" class="ui-button-small" value="删除" name="del_payment" /> &nbsp;</td>
                </form>
                </tr>
                <?php endforeach;?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!--**content end**-->
