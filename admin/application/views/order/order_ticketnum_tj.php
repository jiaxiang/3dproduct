<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
            <li class="on"><a href="/order/ticketnum/tj_all">彩票数据汇总</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div style="padding-right:100px;">
                      时间：<input type="text" id="start_time" name="start_time" value="<?php if (isset($start_time)) echo $start_time;?>" class="text" size="10" />
        			<script type="text/javascript">$(function() { $("#start_time").datepicker({ currentText: 'Now',dateFormat: "yy-mm-dd" }); });</script>
        			到<input type="text" id="end_time" name="end_time" value="<?php if (isset($end_time)) echo $end_time;?>" class="text" size="10" />
        			<script type="text/javascript">$(function() { $("#end_time").datepicker({ currentText: 'Now',dateFormat: "yy-mm-dd" }); });</script>
                    <input type="submit" name="Submit2" value="搜索" class="ui-button-small">
                </div>
            </form>
        </div>
        <?php
        if (is_array($list) && count($list)) {
        ?>
        <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
        <table cellspacing="0" class="table_overflow">
                <thead>
                    <tr class="headings">
                        <th width="40">端口号码</th>
                        <th width="40">出票数量</th>
                        <th width="40">出票金额</th>
                        <th width="40">中奖数量</th>
                        <th width="40">中奖金额</th>
                    </tr>
                </thead>
                <tbody>
				<?php 
				$t_c = 0;
				$t_m = 0;
				$t_bc = 0;
				$t_b = 0;
				foreach ($list as $key=>$rs) { 
					$t_c += $rs['count'];
					$t_m += $rs['money'];
					if (!isset($rs['bcount']) && $rs['bcount'] == null) {
						$rs['bcount'] = 0;
					}
					if (!isset($rs['bonus']) && $rs['bonus'] == null) {
						$rs['bonus'] = 0;
					}
					$t_bc += $rs['bcount'];
					$t_b += $rs['bonus'];
				?>
                <tr>
					<td>
					<?php echo $rs['port'];?>
					</td>
					<td>
					<?php echo $rs['count'];?>
					</td>
					<td>
					<?php echo $rs['money'];?>&nbsp;&nbsp;<a href="<?php echo url::base();?>order/ticketnum/index/ticket_tj?search_type=port&search_value=<?php echo $rs['port'];?>&start_time=<?php echo $start_time;?>&end_time=<?php echo $end_time;?>&Submit2=搜索">点击查看详细</a>
					</td>
					<td>
					<?php echo $rs['bcount'];?>
					</td>
					<td>
					<?php echo $rs['bonus'];?>
					</td>
                </tr>
				<?php
 				}
 				?>
				<tr>
					<td>
					总计
					</td>
					<td>
					<?php echo $t_c;?>
					</td>
					<td>
					<?php echo $t_m;?>
					</td>
					<td>
					<?php echo $t_bc;?>
					</td>
					<td>
					<?php echo $t_b;?>
					</td>
				</tr>
                </tbody>
        </table>
        </form>
            <?php }else {?>
            <?php echo remind::no_rows();?>
            <?php }?>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
       
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
       
        </div>
    </div>
</div>
<!--END FOOTER-->