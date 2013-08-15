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
				<li class="on"><a href='<?php echo url::base() . 'distribution/agent/';?>'>定期结算执行日志</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
            <form action="<?php echo url::base() . url::current();?>" method="GET" name="search_form" class="new_search" id="search_form">
                <input type="hidden" value="0" id="adv_bar_nor" name="adv_bar">
                <div>搜索:
                    <select tabindex="1" name="settlecls" class="text">
                    	<option value="">-结算类型-</option>
                        <?php foreach ($settlecls as $key=>$value):?>
                        <option value="<?php echo $key;?>"><?php echo $value;?></option>
                        <?php endforeach;?>
                    </select>
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
				<th width="40px">执行动作</th>
				<th width="20px">执行备注</th>	
				<th width="20px">执行结果</th>	
				<th width="20px" class="txc">执行结果</th>
				<th width="50px">执行文字</th>	
				<th width="30px">IP地址</th>
				<th width="40px">时间</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) : ?>
				<tr>
					<td><?php echo $item['actname'];?></td>
					<td><?php echo $item['acttype'];?></td>
					<td><?php echo $item['po_ret'];?></td>
					<td class="txc">
						<?php 
                          	$img = $item['po_ret'] == 0 ?'/images/icon/accept.png':'/images/icon/cancel.png';
							echo '<img src="'.$img.'" rev="'.$item['id'].'"/>';	
						?>
					</td>
					<td><?php echo $item['po_msg'];?></td>
					<td><?php echo $item['ip'];?></td>
					<td><?php echo $item['date_add'];?></td>
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
                                    <th>结算类型：</th>
                                    <td colspan="3">
                                       <select tabindex="1" name="settlecls" class="text">
					                    	<option value="">-结算类型-</option>
					                        <?php foreach ($settlecls as $key=>$value):?>
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
