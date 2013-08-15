<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
    <tbody>
        <tr>
            <td width="70%">
                <script type="text/javascript" src="<?php echo url::base();?>amline/swfobject.js"></script>
                <div id="flashcontent"><strong>You need to upgrade your Flash Player</strong></div>
                <script type="text/javascript">
                    // <![CDATA[
                    var so = new SWFObject("<?php echo url::base();?>amline/amline.swf", "amline", "100%", "250", "8", "#FFFFFF");
                    so.addParam("wmode", "transparent");
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
                            <TD width="40%">日期:</TD>
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
