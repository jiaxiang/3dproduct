<div class="new_content">
    <div class="out_box">
    <div class="pro_ie6">
    	<div class="new_sub_menu_title fixfloat">
            <span class="title2">统计报表</span>
            <span class="fright">
                  当前站点:<?php echo $data['site_name'] ?>
            </span>
        </div>
        <script type="text/javascript">
			$(document).ready(function(){
				$(".new_pro_tab li").each(function(index){
					$(this).click(function(){
						$(".new_pro_tab li.on").removeClass("on");
						$(this).addClass("on");	
						$(".new_pro_con").removeClass("contentin");
						$(".new_pro_con:eq(" + index + ")").addClass("contentin");	
					})
				});
			});
		</script>
        
		<?php echo $sitestat_left ?>
        
        <div class="contentin new_pro_con fixfloat">
			<div class="count_con">
                <h2>时段分析（<?php echo $data['date']; ?> —— <?php echo $data['date']; ?>）</h2>
                <div class="tips">
                    <span class="fB">小贴士：</span> 	时段分析为您提供网站任意时间内的流量变化情况。
                </div> 
                <div class="date"><script type="text/javascript" src="/js/year_month_date.js"></script>
                	<span style="float:left">
                		<a href="/sitestat/onedaystat" <?php if(isset($istoday)) echo "class=\"current\""; ?>>今日</a> | 
                		<a href="/sitestat/onedaystat/yesterday" <?php if(isset($isyesterday)) echo "class=\"current\""; ?>>昨日</a> | 
                		<a href="/sitestat/fewdaystat">本月</a> | 
                		<a href="/sitestat/fewdaystat/recent30days">最近30天</a> 
                		[<a href="/sitestat/onedaystat/oneday/<?php echo date('Y-m-d', strtotime($data['date'])-86400) ?>/0" <?php if(isset($isnext) && $isnext==0) echo "class=\"current\""; ?>>前一天</a>] 
                		[<a href="/sitestat/onedaystat/oneday/<?php echo date('Y-m-d', strtotime($data['date'])+86400) ?>/1" <?php if(isset($isnext) && $isnext==1) echo "class=\"current\""; ?>>后一天</a>]
                		&nbsp;&nbsp;&nbsp;&nbsp;
                	</span>
					<form method="POST" action="/sitestat/fewdaystat/fewdays">
					从<input type="text" name="time_from" id="time_from" onclick="javascript:ShowCalendar('time_from',0,20,1)" value="<?php echo $data['date']; ?>" readonly>
					到<input type="text" name="time_to" id="time_to" onclick="javascript:ShowCalendar('time_to',0,20,1)" value="<?php echo $data['date']; ?>" readonly><input type="submit" value="提交">&nbsp;&nbsp;
					</form>
                </div>
                
                <div class="out_box">
                    <table cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th colspan="6"><span class="left fB f14px">日访问量分布</span></th>
                            </tr>
                            <tr class="headings">
                                <th width="18%">日期</th>
                                <th width="11%">PV</th>
                                <th width="11%">IP</th>
                                <th width="13%">人均浏览次数</th>
                                <th width="36%">比例</th>
                            </tr>	
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $data['date']; ?></td>
                                <td><?php echo $data['date_pv']?></td>
                                <td><?php echo $data['date_ip']?></td>
                                <td><?php echo $data['date_pv_ip'] ?></td>
                                <td><img width="200" height="10" border="0" src="/images/bar1.gif"> 100%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab">
                    <div class="tab_title_box fixfloat">
                    <script type="text/javascript">
                    function exchangepic(id1, id2){
                    	$('#img_src1').hide();
                    	$('#link_src1').removeClass('piccurrent');
                    	$('#img_src2').hide();
                    	$('#link_src2').removeClass('piccurrent');
                    	$('#'+id1).show();
                    	$('#'+id2).addClass('piccurrent');
                    }
                    </script>
                    <style>
                    .piccurrent { background:none repeat scroll 0 0 #5C7AA0;color:#FFFFFF;padding:2px 4px;text-decoration:none; }
                    </style>
                        <ul>
                            <span class="left fB f14px">小时段分布</span>
                            <span class="right"><a id="link_src1" class="piccurrent" href="javascript:" onclick="exchangepic('img_src1','link_src1')">折线图</a> | <a id="link_src2" href="javascript:" onclick="exchangepic('img_src2','link_src2')">柱状图</a></span>
                        </ul><br>
                        
                        <div>
                        	<?php echo $data['flash1'] ?>
                        	<?php echo $data['flash2'] ?>
                        	</div>
                    </div>
                    <div class="tab_content_box" style="border-top:1px solid #98AED0;">
                        <table>
						<tr>
						<th colspan="5"><span class="left fB f14px">小时段分布</span></th>
						</tr>
						<tr class="headings">
						<th>小时段</th><th>pv</th><th>IP</th><th>人均浏览次数</th><th>比例</th>
						</tr>
						<?php
						for ($i=0; $i<count($data['hours']); $i++){
							$i_1 = $i+1;
							echo "
							<tr>
							<td>{$i}:00—{$i_1}:00</td><td>{$data['hours'][$i]['pv']}</td><td>{$data['hours'][$i]['ip']}</td><td>{$data['hours'][$i]['pv_ip']}</td><td><img width=\"{$data['hours'][$i]['pv_length']}\" height=\"10\" border=\"0\" src=\"/images/bar1.gif\">{$data['hours'][$i]['pv_rate']}</td>
							</tr>
							";
						}
						?>
						</table>
                    </div>
                </div> 
            </div>    
        </div>  
    </div>
</div>
</div>