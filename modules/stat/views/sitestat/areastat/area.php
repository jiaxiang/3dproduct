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
                <h2>访客地区分布（<?php echo $data['date_from']; ?> —— <?php echo $data['date_to']; ?>）</h2>
                <div class="tips">
                    <span class="fB">小贴士：</span>访客地区分布为您提供不同国家和地区在一定时间范围内给网站带来的流量情况
                </div>
                <div class="date"><script type="text/javascript" src="/js/year_month_date.js"></script>
                	<span style="float:left">
                		<a href="/sitestat/areastat" <?php if(isset($istoday)) echo "class=\"current\""; ?>>今日</a> | 
                		<a href="/sitestat/areastat/yesterday" <?php if(isset($isyesterday)) echo "class=\"current\""; ?>>昨日</a> | 
                		<a href="/sitestat/areastat/thismonth" <?php if(isset($isthismonth)) echo "class=\"current\""; ?>>本月</a> | 
                		<a href="/sitestat/areastat/recent30days" <?php if(isset($isrecent30days)) echo "class=\"current\""; ?>>最近30天</a> 
                		[<a href="/sitestat/areastat/oneday/<?php echo date('Y-m-d',strtotime( $data['date_from'] )-86400) ?>">前一天</a>] 
                		[<a href="/sitestat/areastat/oneday/<?php echo date('Y-m-d',strtotime( $data['date_to'] )+86400) ?>">后一天</a>]
                		&nbsp;&nbsp;&nbsp;&nbsp;
                	</span>
					<form method="POST" action="/sitestat/areastat/fewdays">
					从<input type="text" name="time_from" id="time_from" onclick="javascript:ShowCalendar('time_from',0,20,1)" value="<?php echo $data['date_from']; ?>" readonly>
					到<input type="text" name="time_to" id="time_to" onclick="javascript:ShowCalendar('time_to',0,20,1)" value="<?php echo $data['date_to']; ?>" readonly><input type="submit" value="提交">&nbsp;&nbsp;
					</form>
                </div>
                
                <div class="tab">
                    <div class="tab_title_box fixfloat">
                        <ul>
                            <span class="left fB f14px">访客地区分布占比</span>
                        </ul><br>
                        <?php
                        if ($data['flash1'] == 'none') {
                        	echo '您查询的日期暂时没有地区分布的数据！';
                        }else {
                        	echo $data['flash1'];
                        }
                        ?>
                    </div>
                    <div class="tab_content_box" style="border:1px solid #98AED0;">
                        <table>
						<tr>
						<th colspan="5">
							<span class="left fB f14px">访客地区列表（从<?php echo $data['date_from']; ?>到<?php echo $data['date_to']; ?>）</span>
							<div class="right">
								<form method="POST" action="">
									<input type="hidden" name="time_from" value="<?php echo $data['date_from']; ?>">
									<input type="hidden" name="time_to" value="<?php echo $data['date_to']; ?>">
									<input type="hidden" name="page_now" value="<?php echo $data['page'] ?>">
									<input type="hidden" name="page_total" value="<?php echo $data['pages'] ?>">
									
									<input type="submit" name="first_page" value="首页" <?php echo ($data['page']==1 ? 'disabled' : '' ); ?>>
									<input type="submit" name="previous_page" value="上一页" <?php echo ($data['page']==1 ? 'disabled' : '' ); ?>>
									<input type="submit" name="next_page" value="下一页" <?php echo ($data['page']==$data['pages'] ? 'disabled' : '' ); ?>>
									<input type="submit" name="last_page" value="末页" <?php echo ($data['page']==$data['pages'] ? 'disabled' : '' ); ?>>
									第<?php echo $data['page'] ?>页 共<?php echo $data['pages'] ?>页 每页<?php echo $data['perpage'] ?>条
								</form>
							</div>
						</th>
						</tr>
						<tr class="headings">
						<th>地区名称</th><th>来访次数</th><th>独立IP</th><th>停留时间</th><th>跳出率</th>
						</tr>
						<tr>
						<td>总计</td><td><?php echo $data['total_pv'] ?></td><td><?php echo $data['total_ip'] ?></td><td><?php echo $data['total_viewtime'] ?></td><td><?php echo $data['total_jump_rate'] ?></td>
						</tr>
						<?php
						for ($i=0; $i<count($data['areas']); $i++){
							echo "
							<tr>
							<td>{$data['areas'][$i]['name']}</td>
							<td>{$data['areas'][$i]['pv']}</td>
							<td>{$data['areas'][$i]['ip']}</td>
							<td>{$data['areas'][$i]['viewtime']}</td>
							<td>{$data['areas'][$i]['jump_rate']}</td>
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