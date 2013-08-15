<!--**content start**-->
<div class="new_content">
    <div class="new_index">
        <h2>主面板</h2>
        <div class="top">
            <table class="x3">
                <thead>
                    <tr class="headings">
                        <th colspan="2">公告</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($notices as $key=>$value):?>
                    <tr>
                        <td width="60%"><a href="javascript:void(0);" id="<?php echo $value['id'];?>" onclick="notice(this);"><?php echo $value['title'];?></a></td>
                        <td><?php echo $value['add_time'];?></td>
                    </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <table class="x3">
                <thead>
                    <tr class="headings">
                        <th colspan="2">待处理事务</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><a href="<?php echo url::base();?>order/order/index/noprocessed">待处理订单：</a></td>
                        <td><?php echo $count_pay;?> 个</a></td>
                    </tr>
                    <tr>
                        <td><a href="<?php echo url::base();?>order/order?is_message_order=1">有留言订单：</a></td>
                        <td><?php echo $count_order_message;?> 个</td>
                    </tr>
                    <tr>
                        <td><a href="<?php echo url::base();?>user/contact_us/index/active">商店新留言：</a></td>
                        <td><?php echo $count_contact_us;?> 个</td>
                    </tr>
                </tbody>
            </table>
            <table class="x3">
                <thead>
                    <tr class="headings">
                        <th colspan="2">会员信息</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>今日注册会员：</td>
                        <td><?php echo $count_today_user;?> 个</td>
                    </tr>
                    <tr>
                        <td>有订单会员：</td>
                        <td><?php echo $count_order_user;?> 个</td>
                    </tr>
                    <tr>
                        <td>总会员：</td>
                        <td><?php echo $count_user;?> 个</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h2 class="clear">站点统计</h2>
        <table class="next">
            <col width="100"/>
            <col width="100"/>
            <col width="100"/>
            <col width="100"/>
            <col width="100"/>
            <col width="100"/>
            <thead>
                <tr class="headings">
                    <th class="a_left">时间段的订单数量</th>
                    <th>今天</th>
                    <th>昨天</th>
                    <th>前天</th>
                    <th>上周</th>
                    <th>上月</th>
                </tr>
            </thead>
    
            <tfoot>
                <tr>
                    <td class="a_right">合计</td>
                <?php if(isset($stat_date['today'])){ ?>
                    <td class="a_right"><?php echo $stat_date['today'];?></td>
                    <td class="a_right"><?php echo $stat_date['lastday'];?></td>
                    <td class="a_right"><?php echo $stat_date['last2day'];?></td>
                    <td class="a_right"><?php echo $stat_date['lastweek'];?></td>
                    <td class="a_right"><?php echo $stat_date['lastmonth'];?></td>
                 <?php } ?>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<!--**content end**-->
<div id='notice_dialog_content' style="display:none;" title="公告"></div>
<script type="text/javascript">
    $(function() {
        // Dialog
        $('#notice_dialog_content').dialog({
            autoOpen: false,
            width: 600,
            modal: true
        });
    });
    //公告
    function notice(obj){
        var id = $(obj).attr('id');
        $("#notice_dialog_content").html("loading...");
        $.ajax({
    		url: '<?php echo url::base();?>manage/notice/ajax_content?id=' + id,
            type: 'GET',
            dataType: 'json',
            error: function() {
                window.location.href = '<?php echo url::base();?>login?request_url=<?php echo url::current()?>';
            },
            success: function(retdat, status) {
				ajax_block.close();
				if (retdat['code'] == 200 && retdat['status'] == 1) {
					$("#notice_dialog_content").html(retdat['content']);
				} else {
					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
				}
			}
    	});
        $("#notice_dialog_content").dialog("open");
        return false;
    }
</script>