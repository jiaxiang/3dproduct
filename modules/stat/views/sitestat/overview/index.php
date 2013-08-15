<div class="new_content">
    <div class="out_box">
    <div class="pro_ie6">
    	<div class="new_sub_menu_title fixfloat">
            <span class="title2">统计报表</span>
            <span class="fright">
                  当前站点:<?php echo $site_name ?>
            </span>
        </div>
        
        <?php echo $sitestat_left ?>
        
        <div class="contentin new_pro_con fixfloat">
			<div class="count_con">
                <h2>统计概况<span>(<?php echo date('Y-m-d') ?>)</span></h2>       
                <div class="tips">
                    <span class="fB">小贴士：</span> 	统计概况为您提供网站当前的基本情况。
                </div> 
                                
                <div class="out_box">
                    <table cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th colspan="5"><span class="left fB f14px">访问量概况</span></th>
                            </tr>
                            <tr class="headings">
                                <th width="18%"></th>
                                <th width="11%">PV</th>
                                <th width="11%">IP</th>
                                <th width="10%">新增IP</th>
                                <th width="13%">人均浏览次数</th>
                            </tr>	
                        </thead>
                        <tfoot>
                            <tr>
                                <td>每日平均：</td>
                                <td><?php echo $average['pv'] ?></td>
                                <td><?php echo $average['ip_count'] ?></td>
                                <td></td><td></td>
                            </tr>
                            <tr>
                                <td>历史最高：</td>
                                <td><?php echo $highest['pv'] ?></td>
                                <td><?php echo $highest['ip_count'] ?></td>
                                <td></td><td></td>
                            </tr>
                            <tr>
                                <td>历史累计：</td>
                                <td><?php echo $total['pv'] ?></td>
                                <td><?php echo $total['ip_count'] ?></td>
                                <td></td><td></td>
                            </tr>
    
                        </tfoot>
                        <tbody>
                            <tr>
                                <td>今 日：</td>
                                <td><?php echo $today_pv_ip['pv'] ?></td>
                                <td><?php echo $today_pv_ip['ip_count'] ?></td>
                                <td><?php echo $yesterday_pv_ip['ip_new'] ?></td>
                                <td><?php echo $today_pv_ip['ip_count'] == 0 ? 0 : $today_pv_ip['pv']/$today_pv_ip['ip_count'] ?></td>
                            </tr>
                            <tr>
                                <td>昨 日：</td>
                                <td><?php echo $yesterday_pv_ip['pv'] ?></td>
                                <td><?php echo $yesterday_pv_ip['ip_count'] ?></td>
                                <td><?php echo $yesterday_pv_ip['ip_new'] ?></td>
                                <td><?php echo $yesterday_pv_ip['ip_count'] == 0 ? 0 : $yesterday_pv_ip['pv']/$yesterday_pv_ip['ip_count'] ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                

                <div class="out_box">
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
                    <table cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th colspan="6"><span class="left fB f14px">最近24小时流量趋势</span><span class="right"><a id="link_src1" class="piccurrent" href="javascript:" onclick="exchangepic('img_src1','link_src1')">折线图</a> | <a id="link_src2" href="javascript:" onclick="exchangepic('img_src2','link_src2')">柱状图</a></span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>
                                    <?php echo $flash1 ?>
                        			<?php echo $flash2 ?>
                                </td></tr>
                        </tbody>
                    </table>
                </div>

                <div class="out_box fixfloat">
                    <table cellspacing="0" cellpadding="0" style="width: 48%;" class="left">
                        <thead>
                            <tr>
                                <th colspan="2"><span class="left fB f14px">今日来路域名</span><span class="right"><a href="/sitestat/fromsite">查看全部</a></span></th>
                            </tr>
                            <tr class="headings">
                                <th>来路域名URL</th>
                                <th>来访次数</th>
                            </tr>	
                        </thead>

                        <tbody>
                        	<?php 
							for ($i=0; $i<count($domains); $i++){
								echo "
								<tr>
								<td><a href=\"http://{$domains[$i]['site']}\" title=\"http://{$domains[$i]['site']}\" target=\"_blank\">http://{$domains[$i]['site']}</a></td>
                                <td class=\"all_right\">{$domains[$i]['pv']}</td>
                                <tr>
                                ";
							}
							?>
                        </tbody>
                    </table>



                    <table cellspacing="0" cellpadding="0" style="width: 48%;" class="right">
                        <thead>
                            <tr>
                                <th colspan="2"><span class="left fB f14px">今日受访页面</span><span class="right"><a href="/sitestat/viewpages">查看全部</a></span></th>
                            </tr>
                            <tr class="headings">
                                <th>受访页面URL</th>
                                <th>受访次数</th>
                            </tr>	
                        </thead>

                        <tbody>
                        	<?php 
							for ($i=0; $i<count($pages); $i++){
								echo "
								<tr>
								<td><a href=\"http://{$pages[$i]['url']}\" title=\"http://{$pages[$i]['url']}\" target=\"_blank\">http://{$pages[$i]['url']}</a></td>
                                <td class=\"all_right\">{$pages[$i]['pv']}</td>
                                <tr>
                                ";
							}
							?>
                        </tbody>
                    </table>

                </div>

                <div class="out_box">
                   <table cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th colspan="2"><span class="left fB f14px">今日地区分布</span><span class="right"><a href="/sitestat/areastat">查看全部</a></span></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td><?php echo $flash3 ?></td>
                            </tr>
                        </tbody>
                    </table> 
                </div> 
            </div>    
        </div>  
    </div>
</div>
</div>