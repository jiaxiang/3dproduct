<?php defined('SYSPATH') OR die('No direct access allowed.');  
$comment = $return_struct['content']; 
?>
<!-- header_content -->
<div class="new_sub_menu">
    <div class="new_sub_menu_con">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on">查看评论</li>
            </ul>
        </div>
    </div>
</div>
<!-- header_content(end) -->
<!--**content start**-->
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <div class="division">
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <th>商品名称： </th>
                            <td>
                            	<a href="/product/product/edit?id=<?php echo $comment['product_id']; ?>" target='_blank'><?php echo html::specialchars($product['name_manage']); ?></a>&nbsp;(<?php echo html::specialchars($product['sku']); ?>)
                            </td>
                        </tr>
                        <tr>
                            <th>姓名： </th>
                            <td>
                            	<?php if (empty($comment['user_id'])) { ?>
                                	<?php echo html::specialchars($comment['firstname'].' '.$comment['lastname']);?>&nbsp;
                                <?php } else { ?>
                                	<a href="/user/user/edit/<?php echo $user['id'] ?>" target='_blank'><?php echo htmlspecialchars($user['firstname'].' '.$user['lastname']); ?></a>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Email： </th>
                            <td>
                            	<?php if (empty($comment['user_id'])) { ?>
                            		<?php echo html::specialchars($comment['mail']); ?>
                            	<?php } else { ?>
                            		<?php echo html::specialchars($user['email']); ?>
                            	<?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>星级： </th>
                            <td>
                            	<?php echo str_repeat('<img border="0" src="/images/star-1.png"/>', $comment['grade']); ?><?php echo str_repeat('<img border="0" src="/images/star-0.png"/>', 5 - $comment['grade']); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>评论内容： </th>
                            <td style="word-wrap:break-word;word-break:break-all;overflow:hidden;"><?php echo html::specialchars($comment['content']); ?></td>
                        </tr>
                        <tr>
                            <th>评论状态： </th>
                            <td id="comment_status">
                            <?php 
	                            if ($comment['status'] == ProductcommentService::COMMENT_NOT_EXAMINE) {
	                            	echo "<span style=\"color:#3366CC;\">尚未审核</span>";
								}
								if ($comment['status'] == ProductcommentService::COMMENT_EXAMINE_FALSE) {
									echo "<span style=\"color:red;\">审核未通过</span>";
								}
								if ($comment['status'] == ProductcommentService::COMMENT_EXAMINE_TRUE) {
									echo "<span style=\"color:green;\">审核通过</span>";
								}
                            ?>
                            </td>
                        </tr>
                        <tr>
                            <th>评论时间： </th>
                            <td><?php echo $comment['create_timestamp'];?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="list_save">
                <b id="backup"><input type="button" class="ui-button" value="    返回    " /></b>
				<b id="comment_true" <?php if ($comment['status'] == ProductcommentService::COMMENT_EXAMINE_TRUE) { ?> style="display:none;"<?php } ?>><input type="button" class="ui-button" value="    审核通过    " /></b>
				<b id="comment_false" <?php if ($comment['status'] == ProductcommentService::COMMENT_EXAMINE_FALSE) { ?> style="display:none;"<?php } ?>><input type="button" class="ui-button" value="    审核未通过    " /></b>
				<b id="comment_no" <?php if ($comment['status'] == ProductcommentService::COMMENT_NOT_EXAMINE) { ?> style="display:none;"<?php } ?>><input type="button" class="ui-button" value="    尚未审核   " /></b>
            </div>
        </div>
        <!--**productlist edit end**-->
    </div>
</div>
<script type="text/javascript">
// custom
$(document).ready(function(){
	var comment_id = <?php echo $comment['id']; ?>;
	$('#backup').click(function(){
		history.go(-1);
	});
	$('#comment_no').click(function(){
		ajax_block.open();
		$.ajax({
			url: '/product/comment/examine?comment_id=' + comment_id + '&status=0',
			dataType: 'json',
			success: function(retdat, status){
				ajax_block.close();
				if (retdat['status'] == 1 && retdat['code'] == 200) {
					$('#comment_status').html('<span style="color:#3366CC;">尚未审核</span>');
					$('#comment_true').show();
					$('#comment_false').show();
					$("#comment_no").hide();
				} else {
					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
				}
			},
			error: function(){
				ajax_block.close();
				showMessage('操作失败', '<font color="#990000">请稍后重新尝试！</font>');
			}
		});
	});
    
	$("#comment_true").click(function(){
		ajax_block.open();
		$.ajax({
			url: '/product/comment/examine?comment_id=' + comment_id + '&status=1',
			dataType: 'json',
			success: function(retdat, status){
				ajax_block.close();
				if (retdat['status'] == 1 && retdat['code'] == 200) {
					$('#comment_status').html('<span style="color:green;">审核通过</span>');
					$('#comment_true').hide();
					$('#comment_false').show();
					$("#comment_no").show();
				} else {
					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
				}
			},
			error: function(){
				ajax_block.close();
				showMessage('操作失败', '<font color="#990000">请稍后重新尝试！</font>');
			}
		});
	});
	$('#comment_false').click(function(){
		ajax_block.open();
		$.ajax({
			url: '/product/comment/examine?comment_id=' + comment_id + '&status=2',
			dataType: 'json',
			success: function(retdat, status){
				ajax_block.close();
				if (retdat['status'] == 1 && retdat['code'] == 200) {
					$('#comment_status').html('<span style="color:red;">审核未通过</span>');
					$('#comment_true').show();
					$("#comment_no").show();
					$("#comment_false").hide();
				} else {
					showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
				}
			},
			error: function(){
				ajax_block.close();
				showMessage('操作失败', '<font color="#990000">请稍后重新尝试！</font>');
			}
		});
	});
});
</script>