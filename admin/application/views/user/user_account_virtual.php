<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li><a href="/user/user/account/<?php echo $userid;?>">资金变动明细</a></li>
                <li class="on"><a href="/user/user/virtual_money_account/<?php echo $userid;?>">竞波币变动明细</a></li>
            </ul>
        </div>
        <?php if(is_array($data['list']) && count($data['list'])) { ?>
        <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="200">交易时间</td>
                        <th width="100">收入</td>
                        <th width="100">支出</td>
                        <th width="100">上次余额</td>
                        <th width="100">本次余额</td>
                        <th width="100">交易类型</td>
                        <th width="150">订单号</td>
                        
                        <th>备注</td>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($data['list'] as $row) : ?>
                    <tr>
                        <td><?php echo $row['add_time'];?>&nbsp;</td>
                        <td><?php echo $row['is_in'] == 0 ? $row['price'] : 0;?>元&nbsp;</td>
                        <td><?php echo $row['is_in'] == 1 ? $row['price'] : 0;?>元&nbsp;</td>
                        <td><?php echo $row['user_money'];?>元</td>
                        <td>
						<?php 
						if ($row['is_in'] == 1) {
                        	echo round($row['user_money'] - $row['price'], 2);
                        }
                        else {
                        	echo round($row['user_money'] + $row['price'], 2);
                        } 
						//echo $row['is_in'] == 1 ? $row['user_money'] - $row['price'] : $row['user_money'] + $row['price'];
						?>元&nbsp;
						</td>
                        <td><?php echo $row['type_name'];?>&nbsp;</td>
                        <td><?php echo $row['order_num'];?>&nbsp;</td>
                        
                        <td><?php echo $row['memo'];?>&nbsp;</td>
                    </tr>
                     <?php endforeach;?>
                     
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="8">&nbsp;
                        总收入:<?php echo $data['sum']['price'] - $data['outsum']['price'];?>&nbsp;&nbsp;&nbsp;
                        总支出:<?php echo $data['outsum']['price'];?>元&nbsp;&nbsp;&nbsp;
                        现在竞波币:<?php echo $data['user']['virtual_money'];?>元
                        
                        &nbsp;&nbsp;&nbsp;(注意:此处为该会员总的统计数据)
                        </td>
                    </tr>                     
                     
                     
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