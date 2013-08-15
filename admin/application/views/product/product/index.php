<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<?php $product_list = $return_struct['content']['assoc']; ?>
<!--**content start**-->
<div class="new_content">
    <div class="newgrid">
        <div class="newgrid_tab fixfloat">
            <ul>
                <li class="<?php echo $request_data['status']==ProductService::PRODUCT_STATUS_PUBLISH?'on':'off';?>"><a href='<?php echo url::base() . 'product/product/';?>'>商品列表</a></li>
                <li class="<?php echo $request_data['status']==ProductService::PRODUCT_STATUS_DELETE?'on':'off';?>"><a href='<?php echo url::base() . 'product/product?status='.ProductService::PRODUCT_STATUS_DELETE;?>'>商品回收筒</a></li>
            </ul>
        </div>
        <div class="newgrid_top">
            <ul class="pro_oper">
                <?php if($request_data['status']==ProductService::PRODUCT_STATUS_DELETE){ ?>
                <!-- li>
                    <a href="javascript:void(0);" id="delete_all_on"><span class="del_pro">批量永久删除</span></a>
                </li -->
                <li>
                    <a href="javascript:void(0);" id="recycle_all"><span class="batch_pro right">批量还原</span></a>
                </li>
                <?php }else{ ?>
                <li>
                    <a href="javascript:void(0);" id="delete_all"><span class="del_pro">批量删除</span></a>
                </li>
                <li class="down" id="export_product"><span class="batch_pro left">导出商品</span><span class="down_arrow left"></span>
                    <ul class="level_2">
                        <li><a id="export_checked" href="javascript:void(0)">导出选中</a></li>
                        <li><a id="export_current" href="javascript:void(0)">导出本页</a></li>
                        <li><a id="export_all" href="javascript:void(0)">导出全部</a></li>
                    </ul>
                </li>
                <?php } ?>
                <li>
                    <a href="/product/product/add/" title="添加简单商品"><span class="add_pro">添加简单商品</span></a>
                </li>
                <li>
                    <a href="/product/product/add/1" title="添加可配置商品"><span class="add_pro">添加可配置商品</span></a>
                </li>
                <!-- li>
                    <a href="/product/product/add/2" title="添加组合商品"><span class="add_pro">添加组合商品</span></a>
                </li>
                <li>
                    <a href="/product/pdttpl" title="设置商品模板"><span class="add_pro">设置商品模板</span></a>
                </li -->
            </ul>
            <form id="search_form" name="search_form" class="new_search" method="GET" action="<?php echo url::base() . url::current();?>">
                <div>搜索:
                    <select name="type" id="select_type" class="text" style="vertical-align:middle">
                        <option value="sku" <?php if (isset($request_data['type']) && $request_data['type'] == 'sku') {?>selected<?php }?>>商品 SKU</option>
                        <option value="title" <?php if (isset($request_data['type']) && $request_data['type'] == 'title') {?>selected<?php }?>>商品名称</option>
                        <option value="category_id" <?php if (isset($request_data['type']) && $request_data['type'] == 'category_id') {?>selected<?php }?>>商品分类</option>
                        <option value="brand_id" <?php if (isset($request_data['type']) && $request_data['type'] == 'brand_id') {?>selected<?php }?>>商品品牌</option>
                    </select>
                    <input class="text" type="text" name="keyword" id="keyword2" style="vertical-align:middle"  value="" />
                    <input type="submit" value="搜索" class="ui-button-small ui-widget ui-state-default ui-corner-all" role="button" aria-disabled="false">
                    <a id="advance_option" href="javascript:void(0);">高级搜索</a>
                </div>
            </form>

        </div>
        <?php if (is_array($product_list) && count($product_list)) {?>
        <table  cellspacing="0" class="table_overflow" id="product_list_box">
            <form id="list_form" name="list_form" method="POST" action="<?php echo url::base();?>product/product/delete_all/">
                <thead>
                    <tr class="headings">
                        <th width="20"><input type="checkbox" id="check_all"></th>
                        <th width="100">操作</th>
                            <?php echo view_tool::sort('类型', 22, 40);?>
                            <?php echo view_tool::sort('商品 SKU',2, 150);?>
                            <?php echo view_tool::sort('商品分类',6, 60);?>
                            <?php echo view_tool::sort('商品名称',10, 0);?>
                            <th width="90">扩展分类</th>
                        	<th width="90">商品品牌</th>
                            <?php echo view_tool::sort('价格',16, 50);?>
                            <?php echo view_tool::sort('上架',14, 50);?>
                            <?php echo view_tool::sort('前台可见',18, 60);?>
                        	<th width="130">质保到期</th>
                    </tr>
                </thead>
                <tbody>         
                        <?php foreach ($product_list as $rs) : ?>
                        <?php $rs = coding::decode_product($rs); ?>
                    <tr>
                        <td><input class="sel" name="ids[]" value="<?php echo $rs['id'];?>" type="checkbox" /></td>
                        <td>
                            <?php if($request_data['status']==ProductService::PRODUCT_STATUS_DELETE){ ?>
                            <a href="<?php echo url::base();?>product/product/recycle?id=<?php echo $rs['id'];?>" class="act_dorecycle"> 还原</a>
                            <a href="<?php echo url::base();?>product/product/delete?status=2&id=<?php echo $rs['id'];?>" class="act_dodelete"> 永久删除</a>
                            <?php }else{ ?>            
                            <a href="<?php echo product::permalink($rs); ?>" target="_blank">查看</a>
                            <a class="act_doedit" href="<?php echo url::base();?>product/product/edit?id=<?php echo $rs['id'];?>">编辑</a>
                            <a href="<?php echo url::base();?>product/product/delete?id=<?php echo $rs['id'];?>" class="act_dodelete"> 删除</a>
                            <?php } ?>    
                        </td>
                        <td><?php 
                            switch($rs['type'])
                            {
                                case ProductService::PRODUCT_TYPE_ASSEMBLY:
                                    echo '<img src="/images/ptype_merge.gif" valign=center alt="组合商品" title="组合商品">';
                                    break;
                                case ProductService::PRODUCT_TYPE_CONFIGURABLE:    
                                    echo '<img src="/images/ptype_merge.gif" valign=center alt="可配置商品" title="可配置商品">';
                                    break;
                                default:    
                                    echo '<img src="/images/ptype_simple.gif" valign=center alt="简单商品" title="简单商品">'; 
                                break;
                            }
                        ?></td>
                        <td><a href="<?php echo url::base();?>product/product/edit?id=<?php echo $rs['id'];?>"><?php echo $rs['sku'];?></a></td>
                        <td><?php if (isset($rs['category'])) {?><?php echo empty($rs['category']['title']) ? '&nbsp;' : html::specialchars($rs['category']['title']);?><?php } else { ?><font color="#ff0000">无</font><?php } ?></td>
                        <td><?php echo html::specialchars($rs['title']);?></td>
                        <?php if (!empty($rs['category_ids'])) : ?>
                        <?php 
	                        $names = '';
	                        foreach ((array)$rs['category_ids'] as $cid) :
		                        if (isset($categorys[$cid])) :
		                        	if (!empty($names)) :
		                        		$names .= '; ';
		                        	endif;
		                        	$names .= $categorys[$cid]['title'];
		                        endif;
	                        endforeach;
                        ?>
                        <td title="<?php echo htmlspecialchars($names); ?>">
	                        <img src="<?php echo url::base(); ?>images/icon/accept.png" alt="" border="0">
                        </td>
                        <?php else : ?>
                        <td><img src="<?php echo url::base(); ?>images/icon/cancel.png" alt="" border="0"></td>
                        <?php endif; ?>
                        <td><?php if (!empty($rs['brand'])) { ?><?php echo html::specialchars($rs['brand']['name']); ?><?php } else { ?><font color="#ff0000">无</font><?php } ?></td>
                        <td><?php echo $rs['price'];?></td>
                        <td id="sale">
                        	<?php if ($request_data['status']==ProductService::PRODUCT_STATUS_PUBLISH && $rs['on_sale'] == 1) : ?>
                        		<img name="set_on_sale" src="<?php echo url::base(); ?>images/icon/accept.png" alt="" border="0" style="cursor: pointer;">
                        	<?php else : ?>
                        		<img name="set_on_sale" src="<?php echo url::base(); ?>images/icon/cancel.png" alt="" border="0" style="cursor: pointer;">
                        	<?php endif; ?>
                        	<input type="hidden" value="<?php echo $rs['id'].'-'.$rs['on_sale']; ?>">
                        </td>
                        <td id="front_visible">
                        	<?php if ($request_data['status']==ProductService::PRODUCT_STATUS_PUBLISH && $rs['front_visible'] == 1) : ?>
                        		<img name="set_front_visible" src="<?php echo url::base(); ?>images/icon/accept.png" alt="" border="0" style="cursor: pointer;">
                        	<?php else : ?>
                        		<img name="set_front_visible" src="<?php echo url::base(); ?>images/icon/cancel.png" alt="" border="0" style="cursor: pointer;">
                        	<?php endif; ?>
                        	<input type="hidden" value="<?php echo $rs['id'].'-'.$rs['front_visible']; ?>">
                        </td>
                        <td>
                            <?php if(isset($rs['quality_date']) && isset($rs['made_date'])){ ?>
                            <?php 
                                $one_day = (60*60*24);
                                $da = ceil((strtotime($rs['quality_date'])-strtotime($rs['made_date']))/$one_day);
                                $dt = ceil((time()-strtotime($rs['made_date']))/$one_day);
                                if($dt>$da){ 
                            ?>
                                <font color='#ff0000'>已经过期！</font> <?php echo $dt.'/'.$da;?>                                
                            <?php }elseif($dt<ceil($da*$rs['quality_percent']*0.01)){ ?>
                                <font color='#cccccc'>正常 <?php echo $dt.'/'.$da;?></font>
                            <?php }else{ ?>
                                <font color='#0000ee'>将要过期</font> <?php echo $dt.'/'.$da;?>
                            <?php } ?>
                            <?php } ?>
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
<div id="advance_search" style="display:none;" title="搜索商品">
    <form id="adv_search_form" name="adv_search_form" method="GET" action="<?php echo url::base() . url::current();?>">
        <div class="dialog_box">
            <div class="out_box">
                <table height="75" border="0" cellpadding="0">
                    <tr>
                        <td width="20%">关 键 字：</td>
                        <td>
                            <input class="text" size="40" type="text" name="keyword" id="keyword" value="" />
                        </td>
                    </tr>
                    <tr>
                        <td>搜索范围：</td>
                        <td>
                            <input type="radio" name="type" value="sku" <?php if (!isset($request_data['type']) || $request_data['type'] == 'sku') {?>checked="checked"<?php }?>>
                            商品SKU&nbsp;
                            <input type="radio" name="type" value="name_manage" <?php if (isset($request_data['type']) && $request_data['type'] == 'name_manage') {?>checked="checked"<?php }?>>
                            管理名称&nbsp;
                            <input type="radio" name="type" value="title" <?php if (isset($request_data['type']) && $request_data['type'] == 'title') {?>checked="checked"<?php }?>>
                            商品名称&nbsp;
                            <input type="radio" name="type" value="category_id" <?php if (isset($request_data['type']) && $request_data['type'] == 'category_id') {?>checked="checked"<?php }?>>
                            商品分类&nbsp;
                            <input type="radio" name="type" value="brand_id" <?php if (isset($request_data['type']) && $request_data['type'] == 'brand_id') {?>checked="checked"<?php }?>>
                            商品品牌&nbsp;
                        </td>
                    </tr>
                    <tr>
                        <td>商品状态：</td>
                        <td>
                            <select name="on_sale" class="text">
                                <option value="-1"<?php if (!isset($request_data['on_sale'])) : ?> selected<?php endif; ?>>全部</option>
                                <option value="1"<?php if (isset($request_data['on_sale']) AND $request_data['on_sale'] == 1) : ?> selected<?php endif; ?>>上架</option>
                                <option value="0"<?php if (isset($request_data['on_sale']) AND $request_data['on_sale'] == 0) : ?> selected<?php endif; ?>>下架</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="list_save">
            <input name="create_all_goods" type="submit" class="ui-button" value=" 搜索 "/>
            <input name="cancel" id="cancel_btn" type="button" class="ui-button" value=" 取消 " onclick='$("#advance_search").dialog("close");'/>
        </div>
    </form>
</div>
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
        
        /**
         * 商品批量还原
         */
        $('#recycle_all').click(function(){
            var list_form   = $('#list_form');
            var ids_checked = $('input[class="sel"]:checked').length;
            if (ids_checked > 0) {
            	if (ids_checked > 99) 
                {
                	showMessage('操作失败', '<font color="#990000">批量还原最多可以同时选择99个商品！</font>');
                	return false;
                }
                //confirm("确定还原所有被选中的项吗?",function(){
               	    ajax_block.open();
                    list_form.attr('action','<?php echo url::base();?>product/product/recycle_all/');
                    list_form.submit();
                //});
            }
            else {  
                showMessage('操作失败', '<font color="#990000">请选择所要还原的商品！</font>');
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
         * 高级查询表单的显示与隐藏
         */
        $('#advance_option').click(function(){
            if ($('#advance_search_content').toggle().is(':hidden')) {
                $('#advance_search_image').attr('src', '<?php echo url::base();?>images/arrow-up.gif');
            } else {
                $('#advance_search_image').attr('src', '<?php echo url::base();?>images/arrow-down.gif');
            }
            return false;
        });

        /**
         * 单条商品 AJAX 还原
         */
        $('a.act_dorecycle').unbind().bind('click keyup',function(e){
            var o = $(this);
            //confirm('请确认要还原此项?',function(){
                ajax_block.open();
                $.ajax({
                    url: o.attr('href'),
                    dataType: 'json',
                    success: function(retdat, status) {
                        ajax_block.close();
                        if (retdat['status'] == 1 && retdat['code'] == 200) {
                            document.location.reload();
                        } else {
                            showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
                        }
                    },
                    error: function() {
                        ajax_block.close();
                        showMessage('请求失败', '<font color="#990000">请稍后重新尝试！</font>');
                    }
                });
            //});
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
        
<?php if($request_data['status']==ProductService::PRODUCT_STATUS_PUBLISH){ ?>
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
                        url = url_base + 'product/product/set_on_sale?status=' + v[1] + '&product_id=' + v[0];
                        break;
                     case 'SET_FRONT_VISIBLE':
                        var h = o.parent().find('input');
                        var v = h.val().split('-');
                        if (v[1] == '0') {
                        	v[1] = '1';
                        } else {
                        	v[1] = '0';
                        }
                        url = url_base + 'product/product/set_front_visible?status=' + v[1] + '&product_id=' + v[0];
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
<?php } ?>
    
        var export_url = url_base + 'product/export/index?type=product&';
		<?php foreach ($query_struct['where'] as $k => $v) : ?>
		        export_url += '<?php echo $k; ?>=<?php echo is_array($v) ? implode('-', $v) : urlencode($v); ?>&';
		<?php endforeach; ?>;
		<?php foreach ($query_struct['orderby'] as $k => $v) : ?>
		        export_url += 'orderby[]=<?php echo $k; ?>-<?php echo strtoupper($v) == 'ASC' ? 0: 1; ?>&';
		<?php endforeach; ?>

        $('#export_current').click(function(){
        	var cs = $('input[name="ids[]"]');
            if (cs.length > 0) {
            	var product_id = [];
            	for (var i = 0; i < cs.length; i ++) {
            		product_id.push($(cs[i]).val());
            	}
            	ajax_download(url_base + 'product/export/index?type=product&product_id=' + product_id.join('-'));
                //ajax_download(export_url + 'per_page=<?php echo $query_struct['limit']['per_page']; ?>&page=<?php echo $query_struct['limit']['page']; ?>');
            } else {
                showMessage('操作失败', '<font color="#990000">未找到符合条件的商品！</font>');
            }
        });

        $('#export_checked').click(function(){
            var product_id = [];
            $('input[name="ids[]"]').each(function(idx, item){
                var item = $(item);
                if (item.attr('checked') == true) {
                    product_id.push(item.val());
                }
            });
            if (product_id.length > 0) {
                ajax_download(url_base + 'product/export/index?type=product&product_id=' + product_id.join('-'));
            } else {
                showMessage('操作失败', '<font color="#990000">请选择所要导出的商品！</font>');
            }
        });

        $('#export_all').click(function(){
            if ($('input[name="ids[]"]').length > 0) {
                ajax_download(export_url);
            } else {
                showMessage('操作失败', '<font color="#990000">未找到符合条件的商品！</font>');
            }
        });

        var url_current = <?php echo json_encode(urlencode(url::current(TRUE))); ?>;
        $('.act_doedit').click(function(){
            var url = $(this).attr('href');
            url += '&listurl=' + url_current;
            location.href = url;
            return false;
        });
    });
</script>