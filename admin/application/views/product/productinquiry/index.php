<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<?php $inquiries = $return_struct['content']['assoc']; ?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href="<?php echo url::base();?>product/productinquiry">咨询列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="javascript:void(0);" id="delete_all"><span class="del_pro">批量删除</span></a>
                </li>
                <li class="down" id="examine_comment"><span class="batch_pro left">批量处理</span><span class="down_arrow left"></span>
                	<ul class="level_2">
                    	<li><a name="examine_isshow_1" href="javascript:void(0)">前台显示</a></li>
                        <li><a name="examine_isshow_0" href="javascript:void(0)">前台不显示</a></li>
                        <li><a name="examine_status_1" href="javascript:void(0)">确认处理</a></li>
                    </ul>
                </li>
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索:
                     <select name="type" id="select_type">
                                    <option value="sku" <?php if (isset($request_data['type']) && $request_data['type'] == 'sku') {?>selected<?php }?>>商品SKU</option>
                                    <option value="email" <?php if (isset($request_data['type']) && $request_data['type'] == 'email') {?>selected<?php }?>>Email</option>
                                    <option value="subject" <?php if (isset($request_data['type']) && $request_data['type'] == 'subject') {?>selected<?php }?>>主题</option>
                      </select>
                    <input class="text" type="text" name="keyword" id="keyword" value="<?php isset($request_data['keyword']) && print($request_data['keyword']);?>" />
                    <input type="submit" value="搜索" class="ui-button-small ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
                    <a id="advance_option" href="javascript:void(0);">高级搜索</a>
                </div>
            </form>
        </div>
       <?php if (is_array($inquiries) && count($inquiries)) {?>
        <table class="table_overflow" cellspacing="0">
             <form id="list_form" name="list_form" method="POST" action="<?php echo url::base();?>product/productinquiry/delete_all/">
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="60">操作</th>
                        <?php echo view_tool::sort('主题',18, 0);?>
                        <?php echo view_tool::sort('显示状态',4, 0);?>
                        <?php echo view_tool::sort('回复状态',20, 0);?>
                        <?php echo view_tool::sort('商品SKU',6, 0);?>
                        <?php echo view_tool::sort('姓名',8, 0);?>
                        <?php echo view_tool::sort('Email',10, 0);?>
                        <?php echo view_tool::sort('咨询时间',12, 0);?>
                        <?php echo view_tool::sort('IP',14, 0);?>
                        <?php echo view_tool::sort('处理状态',16, 60);?>
                    </tr>
                </thead>
                <tbody>
				<?php foreach ($inquiries as $key=>$rs) {?>
                  <tr id="top_div_<?php echo $key;?>">
                  <td><input class="sel" name="inquiry_id[]" value="<?php echo $rs['id'];?>" type="checkbox" temp="<?php echo $key;?>"></td>
                  <td> 
                   <a href="<?php echo url::base();?>product/productinquiry/get?id=<?php echo $rs['id'];?>">查看</a>
                   <a class="act_dodelete" href="<?php echo url::base();?>product/productinquiry/delete?id=<?php echo $rs['id'];?>"> 删除</a>
                  </td>
                  <td><?php echo html::specialchars($rs['subject']);?>&nbsp;</td>
                  <td><?php
                       if ($rs['is_show'] == ProductinquiryService::SHOW_NOTIN_FRONT) {
                           echo "前台不显示";
                       }
                       if ($rs['is_show'] == ProductinquiryService::SHOW_IN_FRONT) {
                           echo "<span style=\"color:green;\">前台显示</span>";
                       }
                    ?>
                   </td>
                   <td><?php if(isset($rs['reply_content']) && !empty($rs['reply_content'])){ echo '已回复'; } else { echo '未回复';}?></td>
                   <td><?php echo "<a href='" . url::base() . "product/product/edit?id=" . $rs['product_id'] . "'>" . (!empty($products[$rs['product_id']]) ? $products[$rs['product_id']]['sku'] : '') . "</a>";?>&nbsp;</td>
                   <td><?php if (empty($rs['user_id'])) { ?>
                                		<?php echo html::specialchars($rs['user_name']);?>
                                	<?php } else { ?>
                                		<a href="/user/user/edit/<?php echo $rs['user_id']; ?>"><?php echo !empty($users[$rs['user_id']]) ? html::specialchars($users[$rs['user_id']]['firstname'].' '.$users[$rs['user_id']]['lastname']) : '';?></a>
                                	<?php } ?>&nbsp;</td>
                    <td><?php if (empty($rs['user_id'])) { ?>
                                		<?php echo html::specialchars($rs['email']);?>
                                	<?php } else { ?>
                                		<?php echo !empty($users[$rs['user_id']]) ? html::specialchars($users[$rs['user_id']]['email']): ''; ?>
                                	<?php } ?>&nbsp;</td>
                    <td><?php echo $rs['create_timestamp'];?>&nbsp;</td>
                    <td><?php echo $rs['ip']; ?>&nbsp;</td>
                    <td><?php echo view_tool::get_active_img($rs['status'],true) ?>&nbsp;</td>
                  </tr>
                  <?php
                  }?>
                </tbody>
                <input id="examine_isshow" name="is_show" type="hidden" value="" disabled="true">
                <input id="examine_status" name="status" type="hidden" value="" disabled="true">
                <input name="listurl" type="hidden" value="<?php echo html::specialchars(url::current(TRUE)); ?>">
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
<div id="advance_search" style="display:none;" title="搜索咨询">
     <form id="adv_search_form" name="adv_search_form" method="GET" action="<?php echo url::base() . url::current();?>">
        <div class="dialog_box">
            <div class="body dialogContent">
                <!-- tips of pdtattrset_set_tips  -->
                <div id="gEditor-sepc-panel">
                    <div class="division">
                        <table height="75" border="0" cellpadding="0" cellspacing="3">
                            <tr>
                                <td width="60" height="21">关 键 字：</td>
                                <td colspan="3">
                                    <input class="text" size="50" type="text" name="keyword" id="keyword" value="<?php isset($request_data['keyword']) && print($request_data['keyword']);?>" />
                                </td>
                            </tr>
                           <tr>
                                <td height="21">搜索范围：</td>
                                <td colspan="3">
                                    <input checked="checked" type="radio" name="type" value="sku" <?php if (isset($request_data['type']) && $request_data['type'] == 'sku') {?>checked="checked"<?php }?>>
                                    商品SKU&nbsp;&nbsp;
                                    <input  type="radio" name="type" value="email" <?php if (isset($request_data['type']) && $request_data['type'] == 'email') {?>checked="checked"<?php }?>>
                                    Email&nbsp;&nbsp;<input  type="radio" name="type" value="subject" <?php if (isset($request_data['type']) && $request_data['type'] == 'subject') {?>checked="checked"<?php }?>>主题
                                </td>
                            </tr>
                            <tr>
                                <td height="21" width="66">所属站点：</td>
                                <td width="105"> 
                                </td>
                                <td height="21" width="66">显示状态：</td>
                                <td width="105">
                                    <select name="is_show"  >
                                        <option selected value="-1">---</option>
                                        <option value="1" <?php if (isset($request_data['is_show']) && $request_data['is_show'] == 1) {?>selected<?php }?>>在前台显示</option>
                                        <option value="0" <?php if (isset($request_data['is_show']) && $request_data['is_show'] == 0) {?>selected<?php }?>>在前台不显示</option>
                                    </select>
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
<div id='ajax_edit_content' style="display:none;"></div>
<script type="text/javascript" src="<?php echo url::base();?>js/message.js"></script>
<script type="text/javascript">
    $(function() {
    	$(".pro_oper .down").hover(function(){
			$(this).addClass("on");
    		$(this).children("ul").show();
    	}, function(){
			$(this).removeClass("on");
			$(this).children("ul").hide();
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
            height:250
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $("a.act_dodelete").unbind().bind('click',function(e){
        	var o = $(this);
        	confirm('请确认要删除此项?',function(){
            	ajax_block.open();
                $.ajax({
					url: o.attr('href'),
					dataType: 'json',
					success: function(retdat, status){
						ajax_block.close();
						if (retdat['status'] == 1 && retdat['code'] == 200) {
							o.parent().parent().remove();
							showMessage('提示信息','删除成功');
						} else {
							showMessage('请求失败',retdat['msg']);
						}
                	},
                	error: function(){
                    	ajax_block.close();
                		showMessage('操作失败','请求失败，请稍候重试');
                	}
                });
            });
            return false;
        });

        /**
		 * 评论批量删除
		 */
        $('#delete_all').click(function(){
            var list_form = $('#list_form');
            var count = 0;
			$('input[name="inquiry_id[]"]').each(function(idx, item){
				if ($(item).attr('checked') == true) {
					count ++;
				}
			});
            if (count > 0) {
           	     confirm("确定删除所有被选中的项吗?",function(){
               	     ajax_block.open();
                     list_form.submit();
                 });
            }else{
            	showMessage('操作失败','请选择要删除的咨询信息!');
            }
            return false;
        });

        $('a[name^="examine_status_"]').click(function(){
            var status = $(this).attr('name').split('_')[2];
			var list_form = $('#list_form');
			var count = 0;
			$('input[name="inquiry_id[]"]').each(function(idx, item){
				if ($(item).attr('checked') == true) {
					count ++;
				}
			});
            if (count > 0) {
           	     confirm("确认处理所有被选中商品咨询吗?", function(){
               	     ajax_block.open();
                  	 $('#examine_status').removeAttr('disabled').val(status);
               	     list_form.attr('action', url_base + 'product/productinquiry/examine_all');
                     list_form.submit();
                 });
            }else{
            	showMessage('操作失败','请选择要处理的商品咨询!');
            }
            return false;
        });
        $('a[name^="examine_isshow_"]').click(function(){
            var isshow = $(this).attr('name').split('_')[2];
			var list_form = $('#list_form');
			var count = 0;
			$('input[name="inquiry_id[]"]').each(function(idx, item){
				if ($(item).attr('checked') == true) {
					count ++;
				}
			});
            if (count > 0) {
           	     confirm("确定更改所有被选中商品咨询的显示状态吗?", function(){
               	     ajax_block.open();
                  	 $('#examine_isshow').removeAttr('disabled').val(isshow);
                  	 $('#examine_status').removeAttr('disabled').attr('value', 1);
               	     list_form.attr('action', url_base + 'product/productinquiry/examine_all');
                     list_form.submit();
                 });
            }else{
            	showMessage('操作失败','请选择要处理的商品咨询!');
            }
            return false;
        });
    });
</script>
