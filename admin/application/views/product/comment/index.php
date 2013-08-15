<?php defined('SYSPATH') OR die('No direct access allowed.'); 
$comments = $return_struct['content']['assoc']; 
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href="<?php echo url::base();?>product/comment">评论列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="javascript:void(0);" id="delete_all"><span class="del_pro">批量删除</span></a>
                </li>
                <li class="down" id="examine_comment"><span class="batch_pro left">批量审核</span><span class="down_arrow left"></span>
                	<ul class="level_2">
                    	<li><a name="examine_status_1" href="javascript:void(0)">审核通过</a></li>
                    	<li><a name="examine_status_2" href="javascript:void(0)">审核未通过</a></li>
                        <li><a name="examine_status_0" href="javascript:void(0)">尚未审核</a></li>
                    </ul>
                </li>
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索:
                     <select name="type" id="select_type">
                        <option value="sku" <?php if (isset($request_data['type']) && $request_data['type'] == 'sku') {?>selected<?php }?>>商品SKU</option>
                        <!-- <option value="title" <?php //if (isset($request_data['type']) && $request_data['type'] == 'title') {?>selected<?php //}?>>称呼</option> -->
                        <!-- <option value="name" <?php //if (isset($request_data['type']) && $request_data['type'] == 'name') {?>selected<?php //}?>>姓名</option> -->
                        <option value="mail" <?php if (isset($request_data['type']) && $request_data['type'] == 'mail') {?>selected<?php }?>>Email</option>
                        <!-- <option value="grade" <?php //if (isset($request_data['type']) && $request_data['type'] == 'grade') {?>selected<?php //}?>>星级</option> -->
                      </select>
                    <input class="text" type="text" name="keyword" id="keyword2" value="<?php isset($request_data['keyword']) && !empty($request_data['keyword']) && print($request_data['keyword']);?>" />
                    <input type="submit" value="搜索" class="ui-button-small ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
                    <a id="advance_option" href="javascript:void(0);">高级搜索</a>
                </div>
            </form>
        </div>
       <?php if (is_array($comments) && count($comments)) {?>
        <table  cellspacing="0">
             <form id="list_form" name="list_form" method="POST" action="<?php echo url::base();?>product/comment/delete_all/">
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="150">操作</th>
                        <?php echo view_tool::sort('审核',4, 0);?>
                        <?php echo view_tool::sort('商品SKU',6, 0);?>
                        <?php echo view_tool::sort('姓名',8, 0);?>
                        <?php echo view_tool::sort('Email',10, 0);?>
                        <?php echo view_tool::sort('星级',12, 0);?>
                        <?php echo view_tool::sort('评论时间',14, 0);?>
                        <?php echo view_tool::sort('IP',16, 0);?>
                    </tr>
                </thead>
                <tbody>
				<?php foreach ($comments as $key=>$rs) {?>
                  <tr id="top_div_<?php echo $key;?>">
                  <td><input class="sel" name="comment_id[]" value="<?php echo $rs['id'];?>" type="checkbox" temp="<?php echo $key;?>"></td>
                  <td> 
                   <a href="<?php echo url::base();?>product/comment/get?id=<?php echo $rs['id'];?>">查看</a>
                   <a class="act_dodelete" href="<?php echo url::base();?>product/comment/delete?id=<?php echo $rs['id'];?>"> 删除</a>
                  </td>
                  <td>
                      <?php
                           if ($rs['status'] == ProductcommentService::COMMENT_NOT_EXAMINE) {
                               echo "未审核";
                           }
                           if ($rs['status'] == ProductcommentService::COMMENT_EXAMINE_FALSE) {
                               echo "<span style=\"color:red;\">未通过</span>";
                           }
                           if ($rs['status'] == ProductcommentService::COMMENT_EXAMINE_TRUE) {
                               echo "<span style=\"color:green;\">通过</span>";
                           }
                        ?>
                   </td>
                   <td><?php echo "<a href='" . url::base() . "product/product/edit?id=" . $rs['product_id'] . "'>" . (!empty($products[$rs['product_id']]) ? $products[$rs['product_id']]['sku'] : '') . "</a>";?>&nbsp;</td>
                   <td><?php if (empty($rs['user_id'])) { ?>
                                		<?php echo html::specialchars($rs['firstname'].' '.$rs['lastname']);?>
                                	<?php } else { ?>
                                		<a href="/user/user/edit/<?php echo $rs['user_id']; ?>"><?php echo !empty($users[$rs['user_id']]) ? html::specialchars($users[$rs['user_id']]['firstname'].' '.$users[$rs['user_id']]['lastname']) : ''; ?></a>
                                	<?php } ?>&nbsp;</td>
                    <td><?php if (empty($rs['user_id'])) { ?>
                                		<?php echo html::specialchars($rs['mail']);?>
                                	<?php } else { ?>
                                		<?php echo !empty($users[$rs['user_id']]) ? html::specialchars($users[$rs['user_id']]['email']): ''; ?>
                                	<?php } ?>&nbsp;</td>
                    <td><?php echo str_repeat('<img border="0" src="/images/star-1.png"/>', $rs['grade']); ?><?php echo str_repeat('<img border="0" src="/images/star-0.png"/>', 5 - $rs['grade']); ?></td>
                    <td><?php echo $rs['create_timestamp'];?>&nbsp;</td>
                    <td><?php echo $rs['ip']; ?>&nbsp;</td>
                  </tr>
                  <?php
                  }?>
                </tbody>
                <input id="examine_status" name="status" type="hidden" value="">
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
<div id="advance_search" style="display:none;" title="搜索评论">
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
                                    <input class="text" size="50" type="text" name="keyword" id="keyword" value="<?php isset($request_data['keyword']) && !empty($request_data['keyword']) && print($request_data['keyword']);?>" />
                                </td>
                            </tr>
                           <tr>
                                <td height="21">搜索范围：</td>
                                <td colspan="3">
                                    <input checked="checked" type="radio" name="type" value="sku" <?php if (isset($request_data['type']) && $request_data['type'] == 'sku') {?>checked="checked"<?php }?>>
                                    商品SKU&nbsp;&nbsp;
                                    <!-- <input  type="radio" name="type" value="title" <?php //if (isset($request_data['type']) && $request_data['type'] == 'title') {?>checked="checked"<?php //}?>>
                                    称呼&nbsp;&nbsp; -->
                                    <!-- <input  type="radio" name="type" value="name" <?php //if (isset($request_data['type']) && $request_data['type'] == 'name') {?>checked="checked"<?php //}?>>
                                    姓名&nbsp;&nbsp; -->
                                    <input  type="radio" name="type" value="mail" <?php if (isset($request_data['type']) && $request_data['type'] == 'mail') {?>checked="checked"<?php }?>>
                                    Email&nbsp;&nbsp;
                                    <!-- <input  type="radio" name="type" value="grade" <?php //if (isset($request_data['type']) && $request_data['type'] == 'grade') {?>checked="checked"<?php //}?>>
                                    星级&nbsp;&nbsp; -->
                                </td>
                            </tr>
                            <tr>
                                <td height="21" width="66">状态：</td>
                                <td width="105">
                                    <select name="status"  >
                                        <option selected value="-1">---</option>
                                        <option value="1" <?php if (isset($request_data['status']) && $request_data['status'] == 1) {?>selected<?php }?>>审核通过</option>
                                        <option value="0" <?php if (isset($request_data['status']) && $request_data['status'] == 0) {?>selected<?php }?>>未审核</option>
                                        <option value="2" <?php if (isset($request_data['status']) && $request_data['status'] == 2) {?>selected<?php }?>>审核未通过</option>
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
			$('input[name="comment_id[]"]').each(function(idx, item){
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
            	showMessage('操作失败','请选择要删除的商品评论!');
            }
            return false;
        });

        $('a[name^="examine_status_"]').click(function(){
            var status = $(this).attr('name').split('_')[2];
			var list_form = $('#list_form');
			var count = 0;
			$('input[name="comment_id[]"]').each(function(idx, item){
				if ($(item).attr('checked') == true) {
					count ++;
				}
			});
            if (count > 0) {
           	     confirm("确定更改所有被选中商品评论的审核状态吗?", function(){
               	     ajax_block.open();
                  	 $('#examine_status').val(status);
               	     list_form.attr('action', url_base + 'product/comment/examine_all');
                     list_form.submit();
                 });
            }else{
            	showMessage('操作失败','请选择要审核的商品评论!');
            }
            return false;
        });
    });
</script>
