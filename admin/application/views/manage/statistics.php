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
<div id="content_frame">
  <div class="grid_c2">
    <div class="col_main">
      <div class="clear"></div>
      <div class="main_content">
        <div class="tableform">
          <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tbody>
              <tr>
                <td width="33%"><h4>订单转化率</h4>
                  <div class="division">
                    <table cellspacing="0" cellpadding="0" border="0" class="finderInform" style="border-left:1px solid #dddddd;">
                      <thead>
                        <tr>
                          <th>总订单量</th>
                          <th>总访问IP量</th>
                          <th>转化率</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><?php echo $average_data['count_order'];?></td>
                          <td><?php echo $average_data['count_ip'];?></td>
                          <td><?php echo $average_data['conversion_rate'];?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div></td>
                <td width="33%"><h4>注册会员购买率</h4>
                  <div class="division">
                    <table cellspacing="0" cellpadding="0" border="0" class="finderInform" style="border-left:1px solid #dddddd;">
                      <thead>
                        <tr>
                          <th>有过订单的会员数</th>
                          <th>总会员数</th>
                          <th>注册会员购买率</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><?php echo $average_data['count_order_user'];?></td>
                          <td><?php echo $average_data['count_user'];?></td>
                          <td><?php echo $average_data['order_user_rate'];?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div></td>
                <td width="34%"><h4>客户平均订单金额</h4>
                  <div class="division">
                    <table cellspacing="0" cellpadding="0" border="0" class="finderInform" style="border-left:1px solid #dddddd;">
                      <thead>
                        <tr>
                          <th>总订单金额</th>
                          <th>总订单数</th>
                          <th>平均订单金额</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><?php echo $average_data['sum_order'];?></td>
                          <td><?php echo $average_data['count_order'];?></td>
                          <td><?php echo $average_data['average_order'];?></td>
                        </tr>
                      </tbody>
                    </table>
                  </div></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="tableform">
          <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tbody>
              <tr>
                <td width="75%">
				<script type="text/javascript" src="<?php echo url::base();?>amline/swfobject.js"></script>
                  <div id="flashcontent"><strong>You need to upgrade your Flash Player</strong></div>
                  <script type="text/javascript">
                        // <![CDATA[
                        var so = new SWFObject("<?php echo url::base();?>amline/amline.swf", "amline", "100%", "250", "8", "#FFFFFF");
                        so.addVariable("path", "/amline/");
                        so.addVariable("settings_file", encodeURIComponent("<?php echo url::base();?>amline/chart_settings/task_report_day_resptime.xml"));
                        so.addVariable("chart_data", encodeURIComponent("<?php echo $data_str;?>"));
                        so.write("flashcontent");
                        // ]]>
                    </script>
                    </td>
                <td valign="top">
                    <TABLE border="0" cellSpacing="0" cellPadding="0" width="100%" align="center">
                      <TBODY>
                        <TR>
                        	<th colspan="2">概况</th>
                        </TR>
                        <TR>
                          <TD>日期:</TD>
                          <TD align="right" ><?php echo $monitor_report['date'];?></TD>
                        </TR>
                        <TR>
                          <TD>第一次检查时间:</TD>
                          <TD align="right"><?php echo $monitor_report['start_check_time'];?></TD>
                        </TR>
                        <TR>
                          <TD>最后检查时间:</TD>
                          <TD align="right"><?php echo $monitor_report['last_check_time'];?></TD>
                        </TR>
                        <TR>
                          <TD>可用率:</TD>
                          <TD align="right"><?php echo $monitor_report['uptime_percent'];?></TD>
                        </TR>
                        <TR>
                          <TD>故障时间:</TD>
                          <TD align="right"><?php echo $monitor_report['fault_time_minute'];?></TD>
                        </TR>
                        <TR>
                          <TD>故障率:</TD>
                          <TD align="right"><?php echo $monitor_report['fault_time_percent'];?></TD>
                        </TR>
                        <TR>
                          <TD>总检查次数:</TD>
                          <TD align="right"><?php echo $monitor_report['total_check_sum'];?></TD>
                        </TR>
                        <TR>
                          <TD>可用次数:</TD>
                          <TD align="right"><?php echo $monitor_report['up_check_sum'];?></TD>
                        </TR>
                      </TBODY>
                    </TABLE>
                  </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="tableform">
          <table cellspacing="0" cellpadding="0" border="0" width="100%">
            <tbody>
              <tr>
                <td width="75%">
                <script type="text/javascript" src="<?php echo url::base();?>amline/swfobject.js"></script>
                <div id="stat_report_content"><strong>You need to upgrade your Flash Player</strong></div>
                <script type="text/javascript">
                    // <![CDATA[
                        var stat_so = new SWFObject("<?php echo url::base();?>amline/amcolumn.swf?cache=0", "amcolumn", "100%", "250", "8", "#FFFFFF");
                        stat_so.addVariable("path", "/amline/");
                        stat_so.addVariable("settings_file", encodeURIComponent("<?php echo url::base();?>amline/chart_settings/sataking_report.xml"));                // you can set two or more different settings files here (separated by commas)
                        stat_so.addVariable("chart_data", encodeURIComponent("<?php echo $statking_str;?>"));
                        stat_so.write("stat_report_content");
                    // ]]>
                </script>
                </td>
                <td>
                    <TABLE border="0" cellSpacing="0" cellPadding="0" width="100%" align="center">
                      <TBODY>
                        <TR>
                        	<th colspan="2">综合信息</th>
                        </TR>
                        <TR>
                        	<td width="25%">站点名称:</td>
                            <td width="25%"><?php echo $statking_main['site']['sitename'];?></td>
                        </TR>
                        <TR>
                            <td width="25%">站点地址:</td>
                            <td width="25%"><?php echo $statking_main['site']['site'];?></td>
                        </TR>
                        <TR>
                        	<td>站点介绍:</td>
                            <td><?php echo $statking_main['site']['sitedes'];?></td>
                        </TR>
                        <TR>
                        	<td>总量统计:</td>
                            <td>访问者(IP):<?php echo $statking_main['site']['all_count_ip'];?> 浏览量(PV): <?php echo $statking_main['site']['all_count'];?></td>
                        </TR>
                        <TR>
                            <td>最高流量:</td>
                            <td>访问者(IP):<?php echo $statking_main['max']['count_ip'];?> 浏览量(PV): <?php echo $statking_main['max']['count_pv'];?></td>
                        </TR>
                        <TR>
                        	<td>本月流量:</td>
                            <td>访问者(IP):<?php echo $statking_main['month']['count_ip'];?> 浏览量(PV): <?php echo $statking_main['month']['count_pv'];?></td>
                        </TR>
                        <TR>
                            <td>本年流量</td>
                            <td>访问者(IP):<?php echo $statking_main['year']['count_ip'];?> 浏览量(PV): <?php echo $statking_main['year']['count_pv'];?></td>
                        </TR>
                        <TR>
                        	<td>统计天数:</td>
                            <td><?php echo $statking_main['count_days'];?></td>
                        </TR>
                        <TR>
                            <td>平均流量:</td>
                            <td>访问者(IP):<?php echo $statking_main['average']['count_ip'];?> 浏览量(PV): <?php echo $statking_main['average']['count_pv'];?></td>
                        </TR>
                        <TR>
                        	<td>最大流量:</td>
                            <td>访问者(IP):<?php echo $statking_main['max']['count_ip'];?> 浏览量(PV): <?php echo $statking_main['max']['count_pv'];?></td>
                        </TR>
                        <TR>
                            <td>最小流量:</td>
                            <td>访问者(IP):<?php echo $statking_main['min']['count_ip'];?> 浏览量(PV): <?php echo $statking_main['min']['count_pv'];?></td>
                        </TR>
                      </TBODY>
                    </TABLE>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!--**content_frame end**-->
