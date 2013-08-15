<!--**content start**-->
<div class="newgrid">
        <?php if (is_array($list) && count($list)) {?>
        <table cellspacing="0" class="table_overflow">
                <thead>
                    <tr class="headings">
                        <th width="30">序号</th>
                        <th width="125">订单号</th>
                        <th width="50">用户</th>
                        <th width="60">支付接口</th>
                        <th width="50">错误号</th>
                        <th width="60">错误信息</th>
                        <th>备注</th>
                        <th width="125">记录时间</th>
                    </tr>
                </thead>
                <tbody>
                		<?php $i = 1;?>
                        <?php foreach ($list as $key=>$rs) { ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><a>#<?php echo $rs['order_num'];?> </a></td>
                        <td><?php echo $rs['user_id'];?></td>
                        <td><?php echo $rs['payment_type_id'];?></td>
                        <td><?php echo $rs['error_id'];?></td>
                        <td><?php echo $rs['error_message'];?></td>
                        <td><?php echo $rs['remark'];?></td>
                        <td><?php echo $rs['date_add'];?></td>
                    </tr>
                    <?php $i++;?>
                            <?php }?>
                </tbody>
        </table>
            <?php }else {?>
            <?php echo remind::no_rows();?>
            <?php }?>
</div>
<!--**content end**-->