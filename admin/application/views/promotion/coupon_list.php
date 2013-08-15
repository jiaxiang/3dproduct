<?php defined('SYSPATH') or die('No direct access allowed.');?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href="<?php echo url::base();?>promotion/coupon">优惠券列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                 <li>
                    <a href="<?php echo url::base();?>promotion/coupon/add" title="添加"><span class="add_pro">添加优惠券</span></a>
                 </li>
                 <li>
                    <a href="javascript:void(0);" id="delete_all"><span class="del_pro">批量删除</span></a>
                 </li>
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>自动生成的优惠券:<input type="checkbox" name="is_front" value="1" style="vertical-align:top; margin-top:5px;" <?php if (isset($request_data['is_front']) && $request_data['is_front'] == 1) {?>checked="true"<?php }?>/>
                                                        搜索:
                     <select name="type" id="select_type" class="text">
                            <!-- <option value="id" <?php if (isset($request_data['type']) && $request_data['type'] == 'id') {?>selected<?php }?>>ID</option> -->
                            <option value="cpn_name" <?php if (isset($request_data['type']) && $request_data['type'] == 'cpn_name') {?>selected<?php }?>>优惠券名称</option>
                            <option value="cpn_prefix" <?php if (isset($request_data['type']) && $request_data['type'] == 'cpn_prefix') {?>selected<?php }?>>优惠券号码</option>
                            
                        </select>
                    <input class="text" type="text" name="keyword" id="keyword2" value="<?php isset($request_data['keyword']) && !empty($request_data['keyword']) &&print($request_data['keyword']); ?>" />
                    <input type="submit" value="搜索" class="ui-button-small ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
                    <a id="advance_option" href="javascript:void(0);">高级搜索</a>
                </div>
            </form>
        </div>
        <?php if ( is_array($coupon_list) && count($coupon_list) ) {?>
        <table  cellspacing="0">
              <form id="list_form" name="list_form" method="POST" action="<?php echo url::base().url::current();?>">   
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="150">操作</th>
                        <?php echo view_tool::sort('优惠券名称',2, 0);?>
                        <?php echo view_tool::sort('优惠券号码',6, 0);?>
                        <?php echo view_tool::sort('优惠券类型',8, 0);?>
                        <th>折扣类型</th>
                        <?php echo view_tool::sort('是否启用',10, 0);?>
                        <?php echo view_tool::sort('总数量',12, 0);?>
                        <?php echo view_tool::sort('开始时间',14, 0);?>
                        <?php echo view_tool::sort('结束时间',16, 0);?>
                    </tr>
                </thead>
                <tbody>
                  <?php
				foreach ( $coupon_list as $key => $rs ) : ?>
                  <tr id="top_div_<?php echo $key;?>" <?php if ( isset($rs['coupon_codes']) && count($rs['coupon_codes']) ) { ?>onclick="javascript:$(this).next().toggle();"<?php }?>>
                  <td><input class="sel" name="id[]" value="<?php echo $rs['id'];?>" type="checkbox" temp="<?php echo $key;?>"></td>
                  <td><a href="<?php echo url::base();?>promotion/coupon/edit?id=<?php echo $rs['id'];?>">编辑</a>&nbsp;
                    			<a href="<?php echo url::base();?>promotion/coupon/do_delete?id=<?php echo $rs['id'];?>" class="act_dodelete">删除</a>&nbsp; 
                                <?php if ( $rs['cpn_type'] == 'A' ) :?>
                                    <a href="javascript:;" onclick="javascript:disp_prompt(<?php echo $rs['id'];?>);">下载</a>
                                <?php else:?>
                                    <a href="<?php echo url::base();?>promotion/coupon/download?id=<?php echo $rs['id'];?>&amount=1">下载</a>
                                <?php endif;?></td>
                  <td><?php echo $rs['cpn_name'];?>&nbsp;</td>
                   <td><?php echo $rs['cpn_prefix'];?>&nbsp;</td>
                    <td><?php if ($rs['cpn_type']=='A') echo '多张且每张使用一次'; else echo '一张重复使用';?>&nbsp;</td>
                    <td><?php echo $rs['coupon_scheme']['cpns_memo'];?>&nbsp;</td>
                    <td><?php if ($rs['disabled']==0) echo '是'; else echo '否'?>&nbsp;</td>
                    <td><?php echo $rs['cpn_gen_quantity'];?>&nbsp;</td>  
                	<td><?php echo date('Y-m-d',strtotime($rs['cpn_time_begin']));?>&nbsp;</td>
                	<td><?php echo date('Y-m-d',strtotime($rs['cpn_time_end'])-24*3600);?>&nbsp;</td>
                  </tr>
                  <?php if ( isset($rs['coupon_codes']) && count($rs['coupon_codes']) ) : ?>
                    <tr style="display:none;" id="group_<?php echo $rs['id'];?>">
	            	<td colspan="10">
		                <div class="new_in_table">
		            	<span><a id="download_url" href="<?php echo url::base();?>promotion/coupon/download?id=<?php echo $rs['id'];?>&type=0">下载</a> <input type="radio" name="download_type" value="0" checked onclick="add_download_type(this.value);" />未使用  <input type="radio" name="download_type" value="1" onclick="add_download_type(this.value);" />已使用  <input type="radio" name="download_type" value="2" onclick="add_download_type(this.value);" />全部</span><br />
          					<table cellspacing="0" cellpadding="0" border="0" width="100%" class="finderInform">
          					<thead>
            					<tr>
              					<th width="10%">优惠券号码</th>
              					<td>是否使用</td>
            					</tr>
				            </thead>
          					<tbody class="spec-body">
					        <?php foreach ( $rs['coupon_codes'] as $_code ) : ?>
            				<tr>
              				<th><?php echo $_code['code'];?></th>
              				<td><?php if ($_code['is_used'] == 0) echo '未使用'; else echo '已使用';?></td>
            				</tr>
          					<?php endforeach ?>
          					</tbody>
          					</table>
		                </div>
	                </td>
	          		</tr>
	          		<?php  endif;
            endforeach; ?>
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
<div id="advance_search" style="display:none;" title="搜索优惠卷">
     <form id="adv_search_form" name="adv_search_form" method="GET" action="<?php echo url::base() . url::current();?>">
        <div class="dialog_box">
            <div class="body dialogContent">
                <!-- tips of pdtattrset_set_tips  -->
                <div id="gEditor-sepc-panel">
                    <div class="division">
                        <table height="75" style="border:1px solid #CCC;" cellpadding="0" cellspacing="3" width="100%">
                        <tr>
                            <td width="30%" height="21">自动生成的优惠券:</td>
                            <td width="70%">
                              <input type="checkbox" name="is_front" value="1" <?php if (isset($request_data['is_front']) && $request_data['is_front'] == 1) {?>checked="true"<?php }?>/>
                            </td>
                        </tr>
                            <tr>
                                <td width="30%" height="21">关 键 字：</td>
                                <td width="70%">
                                    <input class="text" type="text" name="keyword" id="keyword" value="<?php isset($request_data['keyword']) && !empty($request_data['keyword']) &&print($request_data['keyword']); ?>" />
                                </td>
                            </tr>
                           <tr>
                            <td width="30%" height="21">搜索范围：</td>
                            <td width="70%">
                            <!-- 
                            <input  type="radio" name="type" value="id" <?php if (isset($request_data['type']) && $request_data['type'] == 'id') {?>checked="checked"<?php }?>>
                                ID&nbsp;&nbsp;
                                 -->
                                <input  type="radio" name="type" value="cpn_name" <?php if ((isset($request_data['type']) && $request_data['type'] == 'cpn_name') || !isset($request_data['type'])) {?>checked="true"<?php }?>>
                                优惠券名称&nbsp;&nbsp;
                                <input  type="radio" name="type" value="cpn_prefix" <?php if (isset($request_data['type']) && $request_data['type'] == 'cpn_prefix') {?>checked="true"<?php }?>>
                                优惠券号码&nbsp;&nbsp; 
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
<div id='download_confirm' style="display:none;"></div>
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
            height:300
        });
    });
</script>
<script type="text/javascript">
   function disp_prompt(id)
   {
        var amount=prompt("请输入需要下载优惠券的数量","50");
        if(amount)
        {
	        var test = /^\d+$/;
	        if(!test.test(amount) || amount>=10000 || amount<=0)
	        {
				alert("请输入小于10000大于0的正整数！");
				return false;
	        }
	        window.open("/promotion/coupon/download?id="+id+'&amount='+amount);
        }
  
    }
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
            	     list_form.attr('action','<?php echo url::base();?>promotion/coupon/do_delete_all/');
                     list_form.submit();
                 });
            }else{
            	showMessage('操作失败','请选择要删除的项!');
            }
            return false;
        });
    });
</script>
