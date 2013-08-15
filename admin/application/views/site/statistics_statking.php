<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tbody>
        <tr>
            <td width="70%">
                <script type="text/javascript" src="<?php echo url::base();?>amline/swfobject.js"></script>
                <div id="stat_report_content"><strong>You need to upgrade your Flash Player</strong></div>
                <script type="text/javascript">
                    // <![CDATA[
                    var stat_so = new SWFObject("<?php echo url::base();?>amline/amcolumn.swf?cache=0", "amcolumn", "100%", "250", "8", "#FFFFFF");
                    stat_so.addParam("wmode", "transparent");
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
                            <td width="40%">站点名称:</td>
                            <td><?php echo $statking_main['site']['sitename'];?></td>
                        </TR>
                        <TR>
                            <td>站点地址:</td>
                            <td><?php echo $statking_main['site']['site'];?></td>
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