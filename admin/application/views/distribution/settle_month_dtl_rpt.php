<script type="text/javascript">
    $(function() {
        $("#date_begin").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:"yy-mm-dd"
        });
        $("#date_end").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:"yy-mm-dd"
        });
        /* 高级搜索 */
        $("#advance_option").click(function(){
            $("#advance_search").dialog("open");
        });
        // Dialog
        $('#advance_search').dialog({
            autoOpen: false,
            modal: true,
            width: 600,
            height:300
        });
    });
</script>
<div class="new_content">
	<div class="newgrid">
		<div class="newgrid_tab fixfloat">
			<ul>
				<li class="on"><a href='<?php echo url::base() . 'distribution/settlemonthrptdtl';?>'>月结报表明细</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
            <form action="<?php echo url::base() . url::current();?>" method="GET" name="search_form" class="new_search" id="search_form">
                <input type="hidden" value="0" id="adv_bar_nor" name="adv_bar">
                <div>搜索:
                	代理用户名：<input class="text" type='text' name='agentlastname' value=''/>
                    <td width="124">
                        <select tabindex="3" name="ticket_type" class="text">
                        <option value="">-彩种-</option>
                        <?php foreach ($ticket_type as $key=>$value):?>
                        <option value="<?php echo $key;?>"><?php echo $value;?></option>
                        <?php endforeach;?>
                        </select>
                    </td>
                    <input type="submit" value=" 搜   索 " class="ui-button-small ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
                    <a id="advance_option" href="javascript:void(0);">高级搜索</a>
                </div>
            </form>
        </div>
		<?php if (is_array($data) && count($data)) {?>
		<table  cellspacing="0">
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
			<thead>
				<tr class="headings">
				<th width="15px"><input type="checkbox" id="check_all"></th>
				<th width="40px">操作</th>
				<th width="30px">代理</th>
				<th width="30px">合约号</th>
				<th width="30px">客户</th>	
				<th width="30px">彩种</th>
				<th width="20px">返点率</th>
				<th width="50px">订单号</th>	
				<th width="30px">订单金额</th>
				<th width="70px">结算时间</th>
				<th width="20px" class="txc">状态</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) : ?>
				<tr>
					<td><input class="sel" name="new_ids[]" value="<?php echo $item['id'];?>" type="checkbox" /></td>
					<td><a href="settlemonthrpt">返回</a>&nbsp;
					</td>
					<td><?php echo $item['agentlastname'];?>&nbsp;</td>
					<td><?php echo $item['mcid'];?></td>
					<td><?php echo $item['clientlastname'];?>&nbsp;</td>
					<td><?php echo $item['ticket_type'];?></td>
					<td><?php echo $item['rate'];?></td>
					<td><a href='<?php echo $item['urlbase'].$item['order_num'];?>'><?php echo $item['order_num'];?></a></td>
					<td><?php echo $item['fromamt'];?></td>
					<td><?php echo $item['settletime'];?></td>
					<td class="txc">
						<?php 
                          	$img = $item['flag'] == 2 ?'/images/icon/accept.png':'/images/icon/cancel.png';
							echo '<img src="'.$img.'" rev="'.$item['id'].'"/>';	
						?>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</form>
		</table>
        <?php }else {?>
        <?php echo remind::no_rows();?>
        <?php }?>
    </div>
</div>
<!--**content end**-->
<!--FOOTER-->
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
        <?php echo view_tool::per_page(); ?>
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
            <?PHP echo $this->pagination->render('opococ'); ?>
        </div>
    </div>
</div>
<!--END FOOTER-->
<div id="advance_search" style="display:none;" title="高级搜索">
    <form name="advance_search_form" id="advance_search_form" method="get" action="<?php echo url::base() . url::current();?>">
        <div class="dialog_box">
            <div class="body dialogContent">
                <!-- tips of pdtattrset_set_tips  -->
                <div id="gEditor-sepc-panel">
                    <div class="division">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%">
                            <tbody>
                                <tr>
                                    <th width="15%">开始时间：</th>
                                    <td width="35%">
                                        <input name="date_begin" id="date_begin" size="20" value="<?php echo $yesterday;?>" class="text"/>
                                    </td>
                                    <th width="15%">结束时间：</th>
                                    <td>
                                        <input name="date_end" id="date_end" size="20"  value="<?php echo $today;?>" class="text"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th>彩种：</th>
                                    <td colspan="3">
                                        <select tabindex="3" name="ticket_type" class="text">
                                            <option value="">-彩种-</option>
                                            <?php foreach ($ticket_type as $key=>$value):?>
                                            <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="list_save">
            <input name="create_all_goods" type="submit" class="ui-button" value=" 搜索 "/>
            <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='$("#advance_search").dialog("close");'/>
        </div>
    </form>
</div>
