<?php defined('SYSPATH') or die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href="<?php echo url::base();?>promotion/promotion_activity">促销活动列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="<?php echo url::base();?>promotion/promotion_activity/add" title="添加"><span class="add_pro">添加活动</span></a>
                 </li>
                 <li>
                    <a href="javascript:void(0);" id="delete_all"><span class="del_pro">批量删除</span></a>
                </li>
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索:
                     <select name="type" id="select_type" class="text">
                            <!-- <option value="id" <?php if (isset($request_data['type']) && $request_data['type'] == 'id') {?>selected<?php }?>>ID</option>  -->
                            <option value="pmta_name" <?php if (isset($request_data['type']) && $request_data['type'] == 'pmta_name') {?>selected<?php }?>>活动名称</option>
                            <option value="meta_title" <?php if (isset($request_data['type']) && $request_data['type'] == 'meta_title') {?>selected<?php }?>>头部标题</option>
                            <option value="frontend_description" <?php if (isset($request_data['type']) && $request_data['type'] == 'frontend_description') {?>selected<?php }?>>前台描述</option>
                    </select>
                    <input class="text" type="text" name="keyword" id="keyword2" value="<?php isset($request_data['keyword']) && !empty($request_data['keyword']) &&print($request_data['keyword']); ?>" />
                    <input type="submit" value="搜索" class="ui-button-small ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
                    <a id="advance_option" href="javascript:void(0);">高级搜索</a>
                </div>
            </form>
        </div>
        <?php if ( is_array($promotion_activity_list) && count($promotion_activity_list) ) {?>
        <form id="list_form" name="list_form" method="POST" action="<?php echo url::base().url::current();?>">   
        <table  cellspacing="0">
                <thead>
                    <tr class="headings">
                        <th width="20px"><input type="checkbox" id="check_all"></th>
                        <th width="150px">操作</th>
                        <?php echo view_tool::sort('活动名称',4, 150);?>
                        <?php echo view_tool::sort('开始时间',6, 150);?>
                        <?php echo view_tool::sort('结束时间',8, 150);?>
                        <?php echo view_tool::sort('头部标题',10, 150);?>
                        <?php echo view_tool::sort('前台描述',12, 150);?>
                    </tr>
                </thead>
                <tbody>
                  <?php
   				 foreach ( $promotion_activity_list as $key => $rs ) { ?>
                  <tr id="top_div_<?php echo $key;?>">
                  <td><input class="sel" name="id[]" value="<?php echo $rs['id'];?>" type="checkbox" temp="<?php echo $key;?>"></td>
                  <td> 
                   <a href="<?php echo url::base();?>promotion/promotion_activity/edit?id=<?php echo $rs['id'];?>">编辑</a>&nbsp;
                   <a href="<?php echo url::base();?>promotion/promotion_activity/do_delete?id=<?php echo $rs['id'];?>" class="act_dodelete">删除</a>&nbsp; 
                   <a href="<?php echo url::base();?>promotion/promotion/add?id=<?php echo $rs['id'];?>">添加促销规则</a>
                  </td>
                  <td <?php if ( isset($rs['promotions']) && count($rs['promotions']) ) { ?>onclick="javascript:$('#group_<?php echo $rs['id'];?>').toggle();"<?php }?>><?php echo $rs['pmta_name'];?>&nbsp;</td>
                   <td <?php if ( isset($rs['promotions']) && count($rs['promotions']) ) { ?>onclick="javascript:$('#group_<?php echo $rs['id'];?>').toggle();"<?php }?>><?php echo date('Y-m-d',strtotime($rs['pmta_time_begin']));?>&nbsp;</td>
                   <td <?php if ( isset($rs['promotions']) && count($rs['promotions']) ) { ?>onclick="javascript:$('#group_<?php echo $rs['id'];?>').toggle();"<?php }?>><?php echo date('Y-m-d',strtotime($rs['pmta_time_end'])-24*3600);?>&nbsp;</td>
                    <td <?php if ( isset($rs['promotions']) && count($rs['promotions']) ) { ?>onclick="javascript:$('#group_<?php echo $rs['id'];?>').toggle();"<?php }?>><?php echo $rs['meta_title'];?>&nbsp;</td>
                    <td <?php if ( isset($rs['promotions']) && count($rs['promotions']) ) { ?>onclick="javascript:$('#group_<?php echo $rs['id'];?>').toggle();"<?php }?>><?php echo $rs['frontend_description'];?>&nbsp;</td>
                  </tr>
                  <?php if ( isset($rs['promotions']) && count($rs['promotions']) ) { ?>
                    <tr style="display:none;" id="group_<?php echo $rs['id'];?>">
	            	<td colspan="8">
		                <div class="new_in_table">
		            	<table cellspacing="0" cellpadding="0" border="0" width="100%" class="finderInform" >
                            <thead>
                                <tr>
                                <th width="25%" style="text-align:left;">操作</th>
                                <th width="25%" style="text-align:left;" >规则描述</th>
                                <th width="25%" style="text-align:left;">起始时间</th>
                                <th width="25%" style="text-align:left;">结束时间</th>
                                </tr>
                            </thead>
                            <tbody class="spec-body">
                            <?php foreach ( $rs['promotions'] as $keyp => $rsp ) : ?>
                            <tr >
                            <th style="text-align:left;"><a href="<?php echo url::base();?>promotion/promotion/edit?id=<?php echo $rsp['id'];?>">编辑</a>&nbsp;<a class="act_dodelete" href="<?php echo url::base();?>promotion/promotion/do_delete?id=<?php echo $rsp['id'];?>"> 删除</a>&nbsp;</th>
                            <td style="text-align:left;"><?php echo $rsp['description'];?></td>
                            <td style="text-align:left;"><?php echo date('Y-m-d',strtotime($rsp['time_begin']));?></td>
                            <td style="text-align:left;"><?php echo date('Y-m-d',strtotime($rsp['time_end'])-24*3600);?></td>
                            </tr>
                            <?php endforeach ?>
                            </tbody>
                            </table>
		                </div>
	                </td>
	          		</tr>
	          		 <?php }?>
	          		 <?php }?>
	          		 <tr><td colspan="8" style="border:0"></td></tr>
                </tbody>
        </table>
        </form>
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
<div id="advance_search" style="display:none;" title="搜索促销活动">
     <form id="adv_search_form" name="adv_search_form" method="GET" action="<?php echo url::base() . url::current();?>">
        <div class="dialog_box">
            <div class="body dialogContent">
                <!-- tips of pdtattrset_set_tips  -->
                <div id="gEditor-sepc-panel">
                    <div class="division">
                        <table height="75" style="border:1px solid #CCC;" cellpadding="0" cellspacing="3" width="100%">
                            <tr>
                                <td width="66" height="21">关 键 字：</td>
                                <td colspan="3">
                                    <input class="text" type="text" name="keyword" id="keyword" value="<?php isset($request_data['keyword']) && !empty($request_data['keyword']) &&print($request_data['keyword']); ?>" />
                                </td>
                            </tr>
                            <tr>
                            <td height="21" width="66">搜索范围：</td>
                             <td colspan="3">
                             <!--
                             <input  type="radio" name="type" value="id" <?php if (isset($request_data['type']) && $request_data['type'] == 'id') {?>checked="checked"<?php }?>>
                                ID&nbsp;&nbsp; 
                             -->
                                <input  type="radio" name="type" value="pmta_name" <?php if ((isset($request_data['type']) && $request_data['type'] == 'pmta_name')|| !isset($request_data['type'])) {?>checked="true"<?php }?>>
                                活动名称&nbsp;&nbsp;
                                <input  type="radio" name="type" value="meta_title" <?php if (isset($request_data['type']) && $request_data['type'] == 'meta_title') {?>checked="true"<?php }?>>
                                头部标题&nbsp;&nbsp; 
                                <input  type="radio" name="type" value="frontend_description" <?php if (isset($request_data['type']) && $request_data['type'] == 'frontend_description') {?>checked="true"<?php }?>>
                                前台描述&nbsp;&nbsp; 
                            </td>
                           </tr>
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
<script type="text/javascript" src="<?php echo url::base();?>js/message.js"></script>
<script type="text/javascript">
    $(function() {
        /* 高级搜索 */
        $("#advance_option").click(function(){
            $("#advance_search").dialog("open");
        });
        // Dialog
        $('#advance_search').dialog({
            autoOpen: false,
            modal: true,
            width: 600,
            height:250
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("a.act_dodelete").unbind().bind('click keyup',function(e){
            obj = $(this);
            confirm('请确认要删除此项?',function(){
                location.href = obj.attr('href');
            });
            return false;
        });

        /**
		 * 批量删除
		 */
        $('#delete_all').click(function(){
            var list_form   = $('#list_form');
            var ids_checked = $('input[class="sel"]:checked').length;
            if (ids_checked > 0) {
           	     confirm("确定删除所有被选中的项吗?",function(){
            	     list_form.attr('action','<?php echo url::base();?>promotion/promotion_activity/do_delete_all/');
                     list_form.submit();
                 });
            }else{
            	showMessage('操作失败','请选择要删除的项!');
            }
            return false;
        });
    });
</script>
