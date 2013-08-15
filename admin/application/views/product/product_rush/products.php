<?php defined('SYSPATH') OR die('No direct access allowed.');
$collection = $return_struct['content']['collection']; 
$products = $return_struct['content']['products'];?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="on"><?php echo $collection['title'];?></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <li>
                    <a href="javascript:void(0);" id="delete_all"><span class="del_pro">批量删除</span></a>
                </li>
                <li>
                    <a href="javascript:void(0);" id="add_product" title="加入新商品"><span class="add_pro">加入新商品</span></a>
                </li>
                <li>
                    <a href="/product/collection/"><span class="add_pro">商品专题列表</span></a>
                </li>
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索:
                     <input type="hidden" name="id" value="<?php echo $collection['id'];?>"/>
                     <select name="type" id="select_type" class="text" style="vertical-align:middle">
                                	<option value="sku" <?php if (isset($request_data['type']) && $request_data['type'] == 'sku') {?>selected<?php }?>>商品 SKU</option>
                                    <option value="name_manage" <?php if (isset($request_data['type']) && $request_data['type'] == 'name_manage') {?>selected<?php }?>>管理名称</option>
                                    <option value="title" <?php if (isset($request_data['type']) && $request_data['type'] == 'title') {?>selected<?php }?>>商品名称</option>
                                    <option value="category_id" <?php if (isset($request_data['type']) && $request_data['type'] == 'category_id') {?>selected<?php }?>>商品分类</option>
                     </select>
                    <input class="text" type="text" name="keyword" id="keyword2" style="vertical-align:middle"  value="" />
                    <input type="submit" value="搜索" class="ui-button-small ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
                </div>
            </form>
        </div>
        <?php if(empty($products) || !isset($products)) {?>
        <input type="hidden" name="collection_id" id="collection_id" value="<?php echo $collection['id'];?>"/>
        <?php }?>
        <?php if (is_array($products) && !empty($products)){?>
        <table cellspacing="0" id="product_list_box">
            <form id="list_form" name="list_form" method="POST" action="<?php echo url::base();?>product/collection/product_delete_all/">
            <input type="hidden" name="collection_id" id="collection_id" value="<?php echo $collection['id'];?>"/>           
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="150">操作</th>
                        <?php echo view_tool::sort('排序', 0, 80);?>
                        <?php echo view_tool::sort('商品SKU',2, 100);?>
                        <?php echo view_tool::sort('中文名称',4, 0);?>
                        <?php echo view_tool::sort('商品名称',6, 0);?>
                        <?php echo view_tool::sort('分类名',8, 0);?>
                        <?php echo view_tool::sort('上架',10, 80);?>
                        <?php echo view_tool::sort('价格($)',12, 80);?>
                    </tr>
                </thead>
                <tbody>  
                  <?php foreach ($products as $product){ ?>
                  <tr>
                  <td> <input class="sel" name="ids[]" value="<?php echo $product['relation_id'];?>" type="checkbox" /></td>
                  <td> 
                   <a href="<?php echo url::base();?>product/product/edit?id=<?php echo $product['id'];?>">编辑</a>
                   <a href="<?php echo url::base();?>product/collection/product_delete?relation_id=<?php echo $product['relation_id'];?>" class="act_dodelete">删除关联</a>
                  </td>
                   <td class="over">
                    <div class="new_float_parent">
                        <input type="text" class="text" size="4" name="position" value="<?php echo $product['relation_order'];?>" />
                        <div class="new_float">
                            <input type="text" class="text" size="4" name="order" value="<?php echo $product['relation_order'];?>" />
                            <input type="button" class="ui-button-small" value="保存" name="submit_order_form" />
                            <input type="hidden" name="order_id" value="<?php echo $product['relation_id']; ?>"/>
                            <input type="button" class="ui-button-small" value="取消" name="cancel_order_form"/>
                        </div>
                    </div>
                  </td>
                  <td><a href="<?php echo url::base();?>product/product/edit?id=<?php echo $product['id'];?>"><?php echo $product['sku'];?></a></td>
                  <td><?php echo $product['name_manage'];?></td>
                  <td><?php echo $product['title'];?></td>
                  <td><?php if (!empty($product['category'])) { ?><?php echo $product['category'];?><?php } else { ?><font color="#ff0000">无</font><?php } ?></td>
                  <td><?php echo view_tool::get_active_img($product['on_sale']);?><input type="hidden" value="<?php echo $product['id'].'-'.$product['on_sale']; ?>"></td>
                  <td><?php echo $product['price'];?></td>
                  </tr>
                  <?php }
                  ?>
                </tbody>
            </form>
        </table>
        <?php }else {?>
        <?php echo remind::no_rows();?>
        <?php }?>
    </div>
</div>
<div id="product_relation_ifm" style="display:none;">
	<iframe style="border:0px;width:100%;height:98%;" frameborder="0" src="" scrolling="auto"></iframe>
</div>
<!--**content end**-->
<!--FOOTER-->
<?php if (!empty($products)) : ?>
<div class="new_bottom fixfloat">
    <div class="b_r_view new_bot_l">
      <?php echo view_tool::per_page(); ?>
    </div>

    <div class="Turnpage_rightper">
        <div class="b_r_pager">
        <?php echo $this->pagination->render('opococ'); ?>
        </div>
    </div>
</div>
<?php endif; ?>
<!--END FOOTER-->
<script type="text/javascript" src="<?php echo url::base();?>js/message.js"></script>
<script type="text/javascript">
var default_order = '0';
relation_product_ids = <?php if (!empty($relation_product_ids)) : ?><?php echo json_encode($relation_product_ids); ?><?php else : ?>{}<?php endif; ?>;
$('input[name=position]').focus(function(){
	$('.new_float').hide();
	default_order = $(this).val();
	$(this).next().show();
	$(this).next().children('input[name=order]').focus();
});
$('input[name=cancel_order_form]').click(function(){
	$(this).parent().hide();
});
$('input[name=submit_order_form]').click(function(){
	var url = '<?php echo url::base();?>product/collection/product_set_order';
	var obj = $(this).parent();
	var id = $(this).next().val();
	var order = $(this).prev().val();
	$(this).parent().hide();
	if(order == default_order){
		return false;
	}
	obj.prev().attr('disabled','disabled');
	ajax_block.open();
	$.ajax({
		type:'GET',
		dataType:'json',
		url:url,
		data:'id='+id+'&order='+order,
		error:function(){ajax_block.close();},
		success: function(retdat,status) {
			ajax_block.close();
			obj.prev().removeAttr('disabled');
			if(retdat['status'] == 1 && retdat['code'] == 200)
			{
				obj.prev().attr('value',(retdat['content']['order']));
			}else{
				alert(retdat['msg']);
			}
		}
	});
					   
});
    $(function() {
    	// 相关商品设置窗口
        $('#product_relation_ifm').dialog({
    		title: '加入关联商品',
    		modal: true,
    		autoOpen: false,
    		height: 480,
    		width: 900
        });
        $('#add_product').click(function(){
    		var ifm = $('#product_relation_ifm');
    		ifm.find('iframe').attr('src', '/product/collection/add_products?collection_id=' + $('#collection_id').val());
    		ifm.dialog('open');
    	});
    	
    	/**
		 * 商品批量删除
		 */
        $('#delete_all').click(function(){
            var list_form   = $('#list_form');
            var ids_checked = $('input[class="sel"]:checked').length;
            if (ids_checked > 0) { 
            	confirm("确定删除所有被选中的商品吗?",function(){
            		list_form.submit();
            	});
            }else{
            	showMessage('操作失败','请选择要删除的项!');
            }
            return false;
        });
        /**
		 * 单条商品 删除
		 */
        $('a.act_dodelete').unbind().bind('click keyup',function(){
            obj = $(this);
            confirm('确定要删除该商品吗?',function(){
            	location.href = obj.attr('href');
            });
            return false;
        });
		//上下架处理
        $('#product_list_box').find('img').css('cursor', 'pointer');
        $('#product_list_box').bind('click', function(e){
            var o = $(e.target);
            if (e.target.nodeName.toUpperCase() == 'IMG') {
                var h = o.parent().find('input');
                var v = h.val().split('-');
                ajax_block.open();
                $.ajax({
                    url: url_base + 'product/product/onsale?status=' + v[1] + '&product_id=' + v[0],
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
    });
</script>
