<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<?php $product_list = $return_struct['content']['assoc']; ?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><a href='?'>礼品卡列表</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="javascript:void(0);" id="delete_all"><span class="del_pro">批量删除</span></a>
                </li> 
                <li>
                    <a href="javascript:void(0);" id="add_card"><span class="add_pro">添加礼品卡</span></a>
                </li>
            </ul>
        </div>
        <?php if (is_array($product_list) && count($product_list)) {?>
        <table  cellspacing="0" class="table_overflow" id="product_list_box">
            <form id="list_form" name="list_form" method="POST" action="<?php echo url::base();?>product/gift_card/delete_all/">
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="100">操作</th>
                            <?php echo view_tool::sort('商品 SKU',2, 150);?>
                            <?php echo view_tool::sort('商品名称',10, 0);?>
                            <?php echo view_tool::sort('价格',16, 50);?>
                            <?php echo view_tool::sort('上架',14, 50);?>
                    </tr>
                </thead>
                <tbody>         
                        <?php foreach ($product_list as $rs) : ?>
                    <tr>
                        <td><input class="sel" name="ids[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
                        <td>
                            <a class="act_doedit" href="<?php echo url::base();?>product/gift_card/edit?id=<?php echo $rs['id'];?>">编辑</a>
                            <a href="<?php echo url::base();?>product/gift_card/delete?id=<?php echo $rs['id'];?>" class="act_dodelete"> 删除</a>
                        </td>
                        <td><?php echo $rs['sku'];?></td>
                        <td><?php echo html::specialchars($rs['title']);?></td>
                        <td><?php echo $rs['price'];?></td>
                        <td id="sale">
                        	<?php if ($rs['on_sale'] == 1) : ?>
                        		<img name="set_on_sale" src="<?php echo url::base(); ?>images/icon/accept.png" alt="" border="0" style="cursor: pointer;">
                        	<?php else : ?>
                        		<img name="set_on_sale" src="<?php echo url::base(); ?>images/icon/cancel.png" alt="" border="0" style="cursor: pointer;">
                        	<?php endif; ?>
                        	<input type="hidden" value="<?php echo $rs['id'].'-'.$rs['on_sale']; ?>">
                        </td>  
                    </tr>
                        <?php endforeach; ?>
                </tbody>
                <input name="listurl" type="hidden" value="<?php echo html::specialchars(url::current(TRUE)); ?>">
            </form>
        </table>
            <?php }else {?>
            <?php echo remind::no_rows();?>
            <?php }?>
    </div>
</div>
<div id="add_box" style="display:none;" title="编辑礼品卡">
    <form id="add_form" name="add_form" method="post" action="/product/gift_card/post">
        <input type='hidden' name='id' id="card_id" value=0>
        <div class="dialog_box">
            <div class="out_box">
                <table height="75" border="0" cellpadding="0">
                    <tr>
                        <td width="20%">标题：</td>
                        <td>
                            <input class="text" size="40" type="text" name="title" id="title" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">SKU：</td>
                        <td>
                            <input class="text" size="40" type="text" name="sku" id="sku" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td width="20%">价格：</td>
                        <td>
                            <input class="text" size="40" type="text" name="price" id="price" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td>状态：</td>
                        <td>
                            <input type="radio" name="on_sale" id='on_sale1' value="1" checked>
                            上架 &nbsp;&nbsp;
                            <input type="radio" name="on_sale" id='on_sale0' value="0">
                            下架 &nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="list_save">
            <input name="create_all_goods" type="submit" class="ui-button" value=" 保 存 "/>
        </div>
    </form>
</div>
<!--**content end**-->
<script type="text/javascript">
    $(function(){
        /* 高级搜索 */
        $("#add_card").click(function(){
            $("#add_box").dialog("open");
        });
        // Dialog
        $('#add_box').dialog({
            autoOpen: false,
            modal: true,
            width: 600,
            height:250
        });
    });
</script>
<script type="text/javascript" src="<?php echo url::base();?>js/message.js"></script>
<script type="text/javascript">
$(function(){
        $(".pro_oper .down").hover(function(){
            $(this).addClass("on");
            $(this).children("ul").show();
        }, function(){
            $(this).removeClass("on");
            $(this).children("ul").hide();
        }); 
        
        /**
         * 商品批量删除
         */
        $('#delete_all').click(function(){
            var list_form   = $('#list_form');
            var ids_checked = $('input[class="sel"]:checked').length;
            if (ids_checked > 0) {
            	if (ids_checked > 99) 
                {
                	showMessage('操作失败', '<font color="#990000">批量删除最多可以删除99个商品！</font>');
                	return false;
                }
                confirm("确定删除所有被选中的项吗?",function(){
               	    ajax_block.open();
                    list_form.submit();
                });
            }
            else {  
                showMessage('操作失败', '<font color="#990000">请选择所要删除的商品！</font>');
            }
            
            return false;
        });
  
        /**
         * 单条商品 AJAX 删除
         */
        $('a.act_dodelete').unbind().bind('click keyup',function(e){
            var o = $(this);
            var url = o.attr('href');//document.write(url);
            var msg = url.indexOf('?status=2')>0?'数据删除后无法恢复！':'';
            confirm(msg+'请确认要删除此项?',function(){
                ajax_block.open();
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function(retdat, status) {
                        ajax_block.close();
                        if (retdat['status'] == 1 && retdat['code'] == 200) {
                            document.location.reload();
                            //o.parent().parent().remove();
                        } else {
                            showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
                        }
                    },
                    error: function() {
                        ajax_block.close();
                        showMessage('请求失败', '<font color="#990000">请稍后重新尝试！</font>');
                    }
                });
            });
            return false;
        });
        
        $('#product_list_box').bind('click', function(e){
            var url, o = $(e.target);
            var n = o.attr('name');
            if (typeof n != 'undefined' && n != '') {
                n = n.toUpperCase();
                switch (n){
                    case 'SET_ON_SALE':
                        var h = o.parent().find('input');
                        var v = h.val().split('-');
                        if (v[1] == '0') {
                        	v[1] = '1';
                        } else {
                        	v[1] = '0';
                        }
                        url = url_base + 'product/gift_card/set_on_sale?status=' + v[1] + '&product_id=' + v[0];
                        break;
                     case 'SET_FRONT_VISIBLE':
                        var h = o.parent().find('input');
                        var v = h.val().split('-');
                        if (v[1] == '0') {
                        	v[1] = '1';
                        } else {
                        	v[1] = '0';
                        }
                        url = url_base + 'product/gift_card/set_front_visible?status=' + v[1] + '&product_id=' + v[0];
                        break;
                     default : 
                        //document.write(n);
                        break;
                }
                if(!url)return;
                ajax_block.open();
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(retdat, status) {
                        ajax_block.close();
                        if (retdat['code'] == 200 && retdat['status'] == 1) {
                            if (retdat['content'] == 1) {
                                o.attr('src', url_base + 'images/icon/accept.png');
                            } else {
                                o.attr('src', url_base + 'images/icon/cancel.png');
                            }
                            h.val(v[0] + '-' + retdat['content']);
                        } else {
                            showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
                        }
                    },
                    error: function() {
                        ajax_block.close();
                        showMessage('请求失败', '<font color="#990000">请稍后重新尝试！</font>');
                    }
                });
            }
            return true;
        });

        var url_current = <?php echo json_encode(urlencode(url::current(TRUE))); ?>;
        $('.act_doedit').click(function(){
            var url = $(this).attr('href');
                ajax_block.open();
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function(retdat, status) {
                        ajax_block.close();
                        if (retdat['status'] == 1 && retdat['code'] == 200) {
                            $("#add_box").dialog("open");
                            $('#card_id').val(retdat['content'].id); 
                            $('#title').val(retdat['content'].title); 
                            $('#sku').val(retdat['content'].sku); 
                            $('#price').val(retdat['content'].price); 
                            if(retdat['content'].on_sale==1){
                                $('#on_sale1').attr('checked',true);
                            }else{
                                $('#on_sale0').attr('checked',true);
                            }
                        } else {
                            showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
                        }
                    },
                    error: function() {
                        ajax_block.close();
                        showMessage('请求失败', '<font color="#990000">请稍后重新尝试！</font>');
                    }
                });
            return false;
        });
});
</script>