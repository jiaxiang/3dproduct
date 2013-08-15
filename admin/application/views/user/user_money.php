<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li <?php if(!isset($status)){print('class="on"');} ?>><a href='#'>资金变动详细</a></li>
            </ul>
        </div>
        <?php if(is_array($data['list']) && count($data['list'])) { ?>
        <table  cellspacing="0">
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <th width="200">记录时间</td>
                        <th width="100">父ID</td>                        
                        <th width="100">类型</td>
                        <th width="100">收入</td>
                        <th width="100">支出</td>
                        <th width="100">上次余额</td>
                        <th width="100">本次余额</td>
                        <th>备注</td>
                    </tr>
                </thead>
                <tbody>
                     <?php foreach ($data['list'] as $row) : ?>
                    <tr>
                        <td><?php echo $row['add_time'];?>&nbsp;</td>
                        <td><a href="/user/user/account/<?php echo $row['user_id'];?>"><?php echo $row['account_log_id'];?></a>&nbsp;</td>
                        <td><a href="/user/user/user_money/<?php echo $row['user_id'];?>/<?php 
						if (empty($data['get']['acid']))
						{
							echo 0;
						}
						else
						{
							echo $row['account_log_id'];
						}?>/<?php echo $row['log_type'];?>"
						><?php echo $money_type[$row['log_type']];?></a>&nbsp;</td>
                        <td><?php echo $row['is_in'] == 0 ? $row['price'] : 0;?>元</td>
                        <td><?php echo $row['is_in'] == 1 ? $row['price'] : 0;?>元&nbsp;</td>
                        <td><?php echo $row['user_money'];?>&nbsp;</td>
                        <td><?php echo $row['is_in'] == 1 ? $row['user_money'] - $row['price'] : $row['user_money'] + $row['price'];?>&nbsp;</td>
                        <td><?php echo $row['memo'];?>&nbsp;</td>
                    </tr>
                     <?php endforeach;?>
                     
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="8">
                        本金收入:<?php echo $data['USER_MONEY']['in'];?>&nbsp;&nbsp;&nbsp;
                        本金支出:<?php echo $data['USER_MONEY']['out'];?>&nbsp;&nbsp;&nbsp;
                        <br />
                        奖金收入:<?php echo $data['BONUS_MONEY']['in'];?>&nbsp;&nbsp;&nbsp;
                        奖金支出:<?php echo $data['BONUS_MONEY']['out'];?>&nbsp;&nbsp;&nbsp;
                        <br />
                        彩金总收入:<?php echo $data['FREE_MONEY']['in'];?>&nbsp;&nbsp;&nbsp;
                        彩金总支出:<?php echo $data['FREE_MONEY']['out'];?>&nbsp;&nbsp;&nbsp;
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