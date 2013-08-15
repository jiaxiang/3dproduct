<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">商业智能</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content_frame start**-->
<div class="new_content">
    <div class="new_index">
        <div class="table_site fixfloat grid-c2-s6">
            <div class="col-main">
                <div class="main-wrap" id="statistic_statking">
                </div>
                <br/>
                <div class="main-wrap" id="statistic_monitor">
                    <img src="/images/loading.gif" alt="loading">loading...
                </div>
            </div>
            <div class="col-sub">
                <table cellspacing="0">
                    <caption>订单转化率</caption>
                    <col width="120">
                    <col >
                    <tr>
                        <th>总订单量</th>
                        <td id="count_order"><?php echo $average_data['count_order'];?></td>
                    </tr>
                    <tr>
                        <th>总访问IP量</th>
                        <td id="count_ip">Loading...</td>
                    </tr>
                    <tr>
                        <th class="last">转化率</th>
                        <td id="count_rate">Loading...</td>
                    </tr>
                </table>
                <table cellspacing="0">
                    <caption>注册会员购买率</caption>
                    <col width="120">
                    <col >
                    <tr>
                        <th>有过订单的会员数</th>
                        <td><?php echo $average_data['count_order_user'];?></td>
                    </tr>
                    <tr>
                        <th>总会员数</th>
                        <td><?php echo $average_data['count_user'];?></td>
                    </tr>
                    <tr>
                        <th class="last">注册会员购买率</th>
                        <td><?php echo $average_data['order_user_rate'];?></td>
                    </tr>
                </table>
                <table cellspacing="0">
                    <caption>客户平均订单金额</caption>
                    <col width="120">
                    <col >
                    <tr>
                        <th>总订单金额</th>
                        <td><?php echo $average_data['sum_order'];?></td>
                    </tr>
                    <tr>
                        <th>总订单数</th>
                        <td><?php echo $average_data['count_order'];?></td>
                    </tr>
                    <tr>
                        <th class="last">平均订单金额</th>
                        <td><?php echo $average_data['average_order'];?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        //$("#statistic_statking").load("/site/statistics/monitor");
        //$("#statistic_monitor").load("/site/statistics/statking");
        $.ajax({
            type: "POST",
            url: "/site/statistics/statking",
            dataType:'json',
            success: function(redata){
                var count_ip = parseFloat(redata.count_ip);
                var count_order = parseFloat($('#count_order').html());
                $("#statistic_monitor").html(redata.html);
                $('#count_ip').html(redata.count_ip);
                var count_rate_temp = count_ip>0?count_order/count_ip:0;
                //var count_rate_num = Math.round(count_rate_temp*10000)/10000;
                var count_rate = count_rate_temp*100;
				var count_rate = count_rate.toFixed(2);
                $('#count_rate').html(count_rate.toString() + '%');
            }
        });
    });
</script>
<!--**content_frame end**-->
