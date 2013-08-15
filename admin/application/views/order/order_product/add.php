<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
        <div>
                <div class="public_right public">
                    <form id="search_form" name="search_form" method="GET" action="<?php echo url::base() . url::current();?>">
                    	<input id="order_id" name="order_id" type="hidden" value="<?php echo $order['id']; ?>"/>
                        <input type="hidden" name="adv_bar" id="adv_bar_nor" value="0" />
                        <p> 搜索:
                            <label>
                                <select name="type" id="select_type" class="text">
                                	<option value="sku" <?php if (isset($request_data['type']) && $request_data['type'] == 'sku') {?>selected<?php }?>>商品SKU</option>
                                    <option value="title" <?php if (isset($request_data['type']) && $request_data['type'] == 'title') {?>selected<?php }?>>商品名称</option>
                                </select>
                            </label>
                            <label>
                                <input class="text" type="text" name="keyword" id="keyword2" value="<?php isset($request_data['keyword']) && print($request_data['keyword'])?>" />
                            </label>
                            <label>
                                <input type="submit" class="ui-button-small" name="searchbtn" value="搜索">
                            </label>
                    </form>
                </div>
            <!--	<div class="public_title title_h3"></div>	-->
            <div class="division" style='width:96%'>
                <table id="product_relation_box" cellspacing="0" cellpadding="0" border="0" width="100%" style="border:1px solid #efefef;">
                	<tr>
                            <th style="text-align:left"><input type="checkbox" id="check_all"></th>
                            <th style="text-align:left"><b>商品名称</b></th>
                            <th style="text-align:left"><b>商品SKU</b></th>
                            <th style="text-align:left"><b>价格</b></th>
                            <th style="text-align:left"><b>库存</b></th>
                    </tr>
                    <?php foreach ($good_list as $good) { ?>
                    <tr>
                     	<td><input name="good_ids[]" type="checkbox" value="<?php echo $good['id'];?>"></td>
                        <td><?php echo html::specialchars($good['title']); ?></td>
                        <td><?php echo html::specialchars($good['sku']); ?></td>
                        <td><?php echo html::specialchars($good['price']);?> USD</td>
                        <td><?php echo ($good['store']==-1)?999:$good['store'];?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                    	<td colspan="6">
                    		<div class="Turnpage_rightper"> <!--<?php echo view_tool::per_page(); ?>-->
				            	<div class="b_r_pager"> <?PHP echo $this->pagination->render('opococ'); ?> </div>
				        	</div>
                    	</td>
                    </tr>
                </table>
            </div>
            <div class="list_save">
                <input id="add_product_relation" type="button" class="ui-button" value="  添加   "/>
                <input id="cancel_product_relation" type="button" class="ui-button" value="  取消   "/>
            </div>
        </div>
<!--**content end**-->
<link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/message.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#check_all').click(function(){
			var rs = $(this).attr('checked');
			var ps = parent.current_good_ids;
			$('input[name="good_ids[]"]').each(function(i, item){
				$(item).attr('checked', rs);
				if (rs == true) {
					var good_id = $(item).val();
					if (typeof ps[good_id] == 'undefined') {
						ps[good_id] = true;
					}
				}
				if(rs == false)
                {
                	var good_id = $(item).val();
                    delete ps[good_id];
                }
			});
			parent.current_good_ids = ps;
		});

		$('input[name="good_ids[]"]').click(function(){
			var good_id  = $(this).val();
			var ps = parent.current_good_ids;
			if ($(this).attr('checked') == true) {
				if (typeof ps[good_id] == 'undefined') {
					ps[good_id] = true;
				}
			} else {
				if (typeof ps[good_id] != 'undefined') {
					delete ps[good_id];
				}
			}
			parent.current_good_ids = ps;
		});

        //扩展显示元素的长度
	    jQuery.extend({
	        count: function(v) {
	            if (typeof v == 'object') {
	                var c = 0;
	                for (var k in v) {
	                    c ++;
	                }
	                return c;
	            } else if (typeof v == 'array') {
	                return v.length;
	            }else if (typeof v == 'string') {
	                return v.length;
	            } else {
	                return -1;
	            }
	        }
	    });
		
		$('#add_product_relation').live('click', function(){
			var ps = parent.current_good_ids;
			var good_ids = '';
			var amounts = '';
			var prices = '';
			if(typeof ps != 'defined' && $.count(ps) > 0){
	    		for (var good_id in ps) {
	        		if (good_ids != '') {	
	        			good_ids += '-';
	        		}
	        		good_ids += good_id;
	        	}
	    		var order_id = $('#order_id').val();
	    		url = url_base + 'order/order_product/add_goods?order_id=' + order_id + '&good_ids=' + good_ids;
	    		location.href = url;
        	}else{
        		parent.showMessage('操作失败', '<font color="#990000">请选择需要添加的商品！</font>');
        	}	
		});
		
		$('#cancel_product_relation').click(function(){
			var ps = parent.current_good_ids;
			$('input[name="good_ids[]"]').each(function(idx, item){
				var o = $(item);
				if (o.attr('checked') == true) {
					o.attr('checked', false);
					var v = o.val();
					if (typeof ps[v] != 'undefined') {
						delete ps[v];
					}
				}
			});
			parent.current_good_ids = ps;
			parent.$('#product_relation_ifm').dialog('close');
		});

		/* 按钮风格 */
        $(".ui-button,.ui-button-small").button();
        var ps = parent.current_good_ids;
        $('input[name="good_ids[]"]').each(function(idx, item){
            var o = $(item);
            var v = o.val();
			if (typeof ps[v] != 'undefined') {
				o.attr('checked', true);
			} else {
				o.attr('checked', false);
			}
        })
	});
</script>