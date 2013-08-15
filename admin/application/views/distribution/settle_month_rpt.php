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
				<li class="on"><a href='<?php echo url::base() . 'distribution/settlemonthrpt';?>'>月结报表</a></li>
			</ul>
		</div>
		<div class="newgrid_top">
			<div style="float:left;display:block;">
				<ul class="pro_oper">
	                <li><a href="javascript:void(0);"><span class="batch_pro" id="batch_chk">批量派发审核</span></a></li>
	            </ul>
			</div>
			<div class="rightsearchdiv12">
            <form action="<?php echo url::base() . url::current();?>" method="GET" name="search_form" class="new_search" id="search_form">
                <input type="hidden" value="0" id="adv_bar_nor" name="adv_bar">
                <div>搜索:
                	代理用户名：<input class="text" type='text' name='lastname' value=''/>
                    <select tabindex="3" name="isbeidan" class="text">
                    	<option value="">-是否北单-</option>
                        <?php foreach ($isbeidan as $key=>$value):?>
                        <option value="<?php echo $key+1;?>"><?php echo $value;?></option>
                        <?php endforeach;?>
                    </select>
                    <td width="124">
                        <select tabindex="3" name="agent_type" class="text">
                       	<option value="">-代理类型-</option>
                        <?php foreach ($agent_type as $key=>$value):?>
                        <option value="<?php echo $key+1;?>"><?php echo $value;?></option>
                        <?php endforeach;?>
                        </select>
                    </td>
                    <input type="submit" value=" 搜   索 " class="ui-button-small ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
                    <a id="advance_option" href="javascript:void(0);">高级搜索</a>
                </div>
            </form>
            </div>
        </div>
		<?php if (is_array($data) && count($data)) {?>
		<table  cellspacing="0">
		<form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
			<thead>
				<tr class="headings">
				<th width="15px"><input type="checkbox" id="check_all"></th>
				<th width="40px">操作</th>
				<th width="10px">ID</th>
				<th width="40px">类型/代理</th>
				<th width="40px">派发状态</th>
				<th width="20px">合约</th>	
				<th width="20px">帐期</th>	
				<th width="10px">北单</th>	
				<th width="30px">返点额</th>
				<th width="20px">订单额</th>
				<th width="20px">返点率</th>
				<th width="20px">税率</th>
				<th width="70px">结算时间</th>
				<th width="70px">更新时间</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $item) : ?>
				<tr>
					<td><input class="sel" name="new_ids[]" value="<?php echo $item['id'];?>" type="checkbox" /></td>
					<td><a href="settlemonthrptdtl?masterid=<?php 
						echo $item['id'];?>">明细</a>&nbsp;<a 
						href="settlemonthrpt?user_id=<?php 
						echo $item['user_id'];?>">该代理</a>&nbsp;
					</td>
					<td><?php echo $item['id'];?></td>
					<td><?php echo $item['agent_type'].'/ '. $item['lastname'];?>&nbsp;</td>
					<td style='font-weight:bold'><?php if ($item['flag']==2 && $item['agent_type']!='二级代理') echo '<span style="color:green;font-weight:bold">待派发[2]</span>';
						else if ($item['flag']==2 && $item['agent_type']=='二级代理') echo '<span style="color:green;font-weight:bold">待超代月结[2]</span>';
						else if ($item['flag']==3) echo '<span style="color:red;font-weight:bold">派发中[3]...</span>';
						else if ($item['flag']==4) echo '已派发[4]';
						else if ($item['flag']==0) echo '已派发[0]';
						else if ($item['flag']==5) echo '<span style="color:red;font-weight:bold">超代月结中[5]...</span>';
						else if ($item['flag']==6) echo '已派超代[6]';
						else echo("<span style='color:red;font-weight:bold'>未知状态</span>[".$item['flag']."]");
					?></td>
					<td><?php echo $item['mcid'];?></td>
					<td><?php echo $item['spid'];?></td>
					<td><?php echo $item['type'];?></td>
					<td><?php echo $item['bonus'];?></td>
					<td><?php echo $item['fromamt'];?></td>
					<td><?php echo $item['rate'];?></td>
					<td><?php echo $item['taxrate'];?></td>
					<td><?php echo $item['settletime'];?></td>
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
                                    <th>是否北单：</th>
                                    <td colspan="3">
                                        <select tabindex="3" name="agent_type" class="text">
                                        	<option value="">-是否北单-</option>
                                            <?php foreach ($agent_type as $key=>$value):?>
                                            <option value="<?php echo $key+1;?>"><?php echo $value;?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>代理类型：</th>
                                    <td colspan="3">
                                        <select tabindex="3" name="isbeidan" class="text">
                                            <?php foreach ($isbeidan as $key=>$value):?>
                                            <option value="">-代理类型-</option>
                                            <option value="<?php echo $key+1;?>"><?php echo $value;?></option>
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
<script type="text/javascript">
	$(function() {
        //删除友情链接
        $("#batch_chk").click(function(){
            var i = false;
            $('.sel').each(function(){
                if(i == false){
                    if($(this).attr("checked")==true){
                        i = true;
                    }
                }
            });
            if(i == false){
                alert('请选择要审核的月结返利！');
                return false;
            }
            if(!confirm('确认审核选中的月结返利？')){
                return false;
            }
            $('#list_form').attr('action','/distribution/settlemonthrpt/batch_chk/');
            $('#list_form').submit();
            return false;
        });
    });
</script>