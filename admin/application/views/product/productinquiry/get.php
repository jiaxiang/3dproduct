<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php $inquiry = $return_struct['content']; ?>
<div id="content_frame">
    <div class="grid_c2">
        <div class="col_main">
            <form id="add_form" name="add_form" method="post" action="<?php echo url::base() . 'product/productinquiry/do_edit/'?>">
            <div class="out_box">
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <th>商品名称： </th>
                            <td>
                            	<a href="/product/product/edit?id=<?php echo $inquiry['product_id']; ?>"><?php echo html::specialchars($product['name_manage']); ?></a>&nbsp;(<?php echo html::specialchars($product['sku']); ?>)
                            </td>
                        </tr>
                        <tr>
                            <th>姓名： </th>
                            <td>
                            	<?php if (empty($inquiry['user_id'])) { ?>
                                	<?php echo html::specialchars($inquiry['user_name']);?>&nbsp;
                                <?php } else { ?>
                                	<a href="/user/user/edit/<?php echo $user['id'] ?>"><?php echo htmlspecialchars($user['firstname'].' '.$user['lastname']); ?></a>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Email： </th>
                            <td>
                            	<?php if (empty($inquiry['user_id'])) { ?>
                            		<?php echo html::specialchars($inquiry['email']); ?>
                            	<?php } else { ?>
                            		<?php echo html::specialchars($user['email']); ?>
                            	<?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th>评论内容： </th>
                            <td style="word-wrap:break-word;word-break:break-all;overflow:hidden;"><?php echo html::specialchars($inquiry['content']);?></td>
                        </tr>
                        <tr>
                            <th>评论时间： </th>
                            <td><?php echo $inquiry['create_timestamp'];?></td>
                        </tr>
                        <tr>
                            <th>回复内容： </th>
                            <td><textarea id="reply_content" name="reply_content" cols="120" rows="6" class="text" type="textarea" value="" maxlength="1024"><?php !empty($inquiry['reply_content']) && print($inquiry['reply_content']);?></textarea></td>
                        </tr>
                        <tr>
                            <th>显示状态： </th>
                            <td>
                            <input type="radio" name="is_show" value="0" <?php echo ($inquiry['is_show'] == 0)?"checked":"";?>> 前台不显示
                            <input name="is_show" type="radio" value="1" <?php echo ($inquiry['is_show'] == 1)?"checked":"";?>> 前台显示</td>       
                        </tr>
                        <tr>
                            <th>是否发邮件： </th>
                            <td>
                            <?php if ($inquiry['is_receive'] == 1) :?>
                            <font style="color:#ff0000">已发送</font>
                            <?php else :?>
                            <input type="radio" name="is_receive" value="0" checked="true"> 不发送邮件
                            <input name="is_receive" type="radio" value="1"> 发送邮件</td>
                            <?php endif;?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="list_save">
            	<input type="hidden" name="id" value="<?php echo $inquiry['id'];?>"></input>
                <input type="button" class="ui-button" value="返回列表 " onclick='window.location.href="<?php echo url::base() . 'product/productinquiry'?>"'/>
				<input type="submit" class="ui-button" value="确认处理" />
            </div>
            </form>
        </div>
        <!--**productlist edit end**-->
    </div>
</div>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
    	$("#add_form").validate();
        add_class();
        <?php if(!empty($inquiry['reply_content']) && isset($inquiry['reply_content'])) :?>
        	$('#reply_content').addClass('required');
        <?php endif;?>        
    });
    function add_class(){
        $('input[name="is_receive"]').bind('click', function(e){
        	var o = $(e.target);
    		if (typeof o.attr('type') != 'undefined' && o.attr('type').toUpperCase() == 'RADIO') {
    			var v = o.val();
    			if(v == 0){
    				$('#reply_content').removeClass('required error');
    				$('label.error').remove();
        		}else if(v > 0){
        			$('#reply_content').addClass('required');
        		}
    		}				
         }); 
    }
</script>