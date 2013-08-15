<?php defined('SYSPATH') OR die('No direct access allowed.');
$products = $return_struct['content']['products'];
?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li <?php (empty($experience) || $experience==0) && print('class="on"');?>><a href='/product/product_auction'>竞拍商品</a></li>
                <li <?php (isset($experience) && $experience==1) && print('class="on"');?>><a href='/product/product_auction?experience=1'>体验竞拍商品</a></li>
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
                <?php if(empty($experience) || $experience==0){ ?>
                    <a id="experience_set" href='/product/product_auction/experience?a=add'><span class="add_pro">加入体验竞拍</span></a>
                <?php }else{?>
                    <a id="experience_set" href='/product/product_auction/experience?a=cancle'><span class="del_pro">取消体验竞拍</span></a> 
                <?php }?>
                 </li>
            </ul>
            <!-- form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索:
                     <select name="type" id="select_type" class="text" style="vertical-align:middle">
                        <option value="sku" <?php if (isset($request_data['type']) && $request_data['type'] == 'sku') {?>selected<?php }?>>商品 SKU</option>
                        <option value="title" <?php if (isset($request_data['type']) && $request_data['type'] == 'title') {?>selected<?php }?>>商品名称</option>
                        <option value="category_id" <?php if (isset($request_data['type']) && $request_data['type'] == 'category_id') {?>selected<?php }?>>商品分类</option>
                     </select>
                    <input class="text" type="text" name="keyword" id="keyword2" style="vertical-align:middle"  value="" />
                    <input type="submit" value="搜索" class="ui-button-small ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
                </div>
            </form -->
        </div>
        <?php if (is_array($products) && !empty($products)){?>
        <table cellspacing="0" id="product_list_box">
            <form id="list_form" name="list_form" method="POST" action="<?php echo url::base();?>product/product_auction/delete_all/">
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="60">操作</th>
                        <?php echo view_tool::sort('商品名称', 1, 0);?>
                        <th width="60">数量</th>
                        <th width="150">分类名</th>
                        <?php echo view_tool::sort('起拍价', 3, 80);?>
                        <th width="60">递增价</th>
                        <?php echo view_tool::sort('倒计时', 5, 80);?>
                        <th width="80">重置时间(秒)</th>
                        <th width="60">上架竞拍</th>
                        <th width="60">推荐竞拍</th>
                    </tr>
                </thead>
                <tbody>  
                  <?php foreach ($products as $product){ ?>
                      <tr>
                      <td> <input class="sel" name="ids[]" value="<?php echo $product['id'];?>" type="checkbox" /></td>
                      <td> 
                       <a href="<?php echo url::base();?>product/product_auction/edit?id=<?php echo $product['id'];?>">编辑</a>
                       <a href="<?php echo url::base();?>product/product_auction/delete?id=<?php echo $product['id'];?>" class="act_dodelete">删除</a>
                      </td>
                      <td><a href="<?php echo url::base();?>product/product/edit?id=<?php echo $product['product_id'];?>"><?php echo $product['name'];?></a></td>
                      <td><?php echo $product['qty'];?></td>
                      <td><?php echo trim($product['category'],',');?></td>
                      <td><?php echo $product['price_start'];?></td>
                      <td><?php echo $product['price_increase'];?></td>
                      <td><?php echo html::time_end($product['time_end']);?></td>
                      <td><?php echo $product['time_reset'];?></td>
                      <td><?php echo view_tool::get_active_img($product['status']);?><input type="hidden" name="status" value="<?php echo $product['id'].'-'.$product['status']; ?>"></td>
                      <td><?php echo view_tool::get_active_img($product['recommend']);?><input type="hidden" name="recommend" value="<?php echo $product['id'].'-'.$product['recommend']; ?>"></td>
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
$(function(){
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
    		ifm.find('iframe').attr('src', '/product/product_auction/add_products?experience=<?php echo $experience;?>');
    		ifm.dialog('open');
    	});
    	
    	/**
		 * 商品批量体验设置
		 */
        $('#experience_set').click(function(){
            var list_form   = $('#list_form');
            var ids_checked = $('input[class="sel"]:checked').length;
            if (ids_checked > 0) { 
                list_form.attr('action', $('#experience_set').attr('href'));
                list_form.submit();
            }else{
            	showMessage('操作失败','请选择要改变的项!');
            }
            return false;
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
                var name = h.attr('name');
                ajax_block.open();
                $.ajax({
                    url: url_base + 'product/product_auction/onchange?name=' + name + '&id=' + v[0] + '&v=' + v[1],
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
