		<div class="new_count_tab">
            <ul>
                <li class="main">在线情况</li>
                <li <?php if (isset($overview)){ echo "class=\"current\""; } ?>><a href="/sitestat/overview">统计概况</a></li>
                
                <li class="main">时段分析</li>
                <li <?php if (isset($onedaystat)){ echo "class=\"current\""; } ?>><a href="/sitestat/onedaystat">今日统计</a></li>
                <li <?php if (isset($onedaystat_yesterday)){ echo "class=\"current\""; } ?>><a href="/sitestat/onedaystat/yesterday">昨日统计</a></li>
                <li <?php if (isset($fewdaystat)){ echo "class=\"current\""; } ?>><a href="/sitestat/fewdaystat">本月统计</a></li>
                <li <?php if (isset($fewdaystat_recent30days)){ echo "class=\"current\""; } ?>><a href="/sitestat/fewdaystat/recent30days">最近30天</a></li>
                
                <li class="main">来路分析</li>
                <li <?php if (isset($fromsite)){ echo "class=\"current\""; } ?>><a href="/sitestat/fromsite">来路域名</a></li>
                <li <?php if (isset($fromtype)){ echo "class=\"current\""; } ?>><a href="/sitestat/fromtype">来路分类</a></li>
                
                <li class="main">其它</li>
                <li <?php if (isset($viewpages)){ echo "class=\"current\""; } ?>><a href="/sitestat/viewpages">受访页面</a></li>
                <li <?php if (isset($areastat)){ echo "class=\"current\""; } ?>><a href="/sitestat/areastat">地区分布</a></li>
            </ul>
        </div>