<?php defined('SYSPATH') OR die('No direct access allowed.');
$list_columns = array(
	array('name'=>'操作时间','column'=>'add_time','class_num'=>'4','width'=>120),
	array('name'=>'类型','column'=>'type_name','class_num'=>'4','width'=>150),
	array('name'=>'状态','column'=>'status_name','class_num'=>'4','width'=>100),
	array('name'=>'操作帐号','column'=>'manager_name','class_num'=>'5','width'=>150),
	array('name'=>'IP','column'=>'ip','class_num'=>'4','width'=>100),
	array('name'=>'详细说明','column'=>'memo','class_num'=>'4','width'=>0),
);
?>        
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
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">后台操作日志</li>
            </ul>
        </div>
        <div class="newgrid_top">
            <form action="<?php echo url::base() . url::current();?>" method="GET" name="search_form" class="new_search" id="search_form">
                <input type="hidden" value="0" id="adv_bar_nor" name="adv_bar">
                <div>搜索:
                    <select tabindex="3" name="user_log_type" class="text">
                        <option value=""> -用户操作- </option>
                        <?php foreach ($user_log_type as $key=>$value):?>
                        <option value="<?php echo $key;?>"><?php echo $value;?></option>
                        <?php endforeach;?>
                    </select>
                    <td width="124">
                        <select tabindex="3" name="manager_id" class="text">
                            <option value=""> -账号- </option>
                            <?php foreach ($managers as $key=>$value):?>
                            <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                            <?php endforeach;?>
                        </select>
                    </td>
                    <input type="submit" value="搜索" class="ui-button-small ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
                    <a id="advance_option" href="javascript:void(0);">高级搜索</a>
                </div>
            </form>

        </div>
        <?php if (is_array($user_logs) && count($user_logs)) {?>
        <table  cellspacing="0">
            <form id="list_form" name="list_form" method="post" action="<?php echo url::base() . url::current();?>">
                <thead>
                    <tr class="headings">
                        <!-- <th width="20"><input type="checkbox" id="check_all"></th> -->
                        <?php foreach ($list_columns as $key=>$value):?>
                        <th title="<?php echo $value['name'];?>"><?php echo $value['name'];?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user_logs as $key=>$value): ?>
                    <tr>
                        <!-- <td><input tags="null" class="sel" name="order_ids[]" value="<?php echo $value['id'];?>" type="checkbox" temp="<?php echo $key;?>"></td> -->
                            <?php foreach ($list_columns as $column_key=>$column_value):?>
                        <td <?php echo ($column_value['width']>0)?'width="' . $column_value['width'] . '"':'';?>><?php echo $value[$column_value['column']];?></td>
                            <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </form>
        </table>
        <?php }else {?>
        <?php echo remind::no_rows();?>
        <?php }?>
    </div>
</div>
<!--**content end**-->

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
                                    <th>用户操作：</th>
                                    <td colspan="3">
                                        <select tabindex="3" name="user_log_type" class="text">
                                            <option value="">全部</option>
                                            <?php foreach ($user_log_type as $key=>$value):?>
                                            <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>操作账号：</th>
                                    <td colspan="3">
                                        <select tabindex="3" name="manager_id" class="text">
                                            <option value="">全部</option>
                                            <?php foreach ($managers as $key=>$value):?>
                                            <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
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
