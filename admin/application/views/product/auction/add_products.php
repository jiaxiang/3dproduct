<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<?php $product_list = $return_struct['content']['assoc']; ?>
<!--**content start**-->
        <div>
            <div>
                <form id="search_form" name="search_form" method="GET" action="<?php echo url::base() . url::current();?>">            
                    <input type="hidden" name="adv_bar" id="adv_bar_nor" value="0" />
                    <p> 搜索:
                        <label>
                            <select name="type" id="select_type" class="text">
                            	<option value="sku" <?php if (isset($request_data['type']) && $request_data['type'] == 'sku') {?>selected<?php }?>>商品SKU</option>
                                <option value="title" <?php if (isset($request_data['type']) && $request_data['type'] == 'title') {?>selected<?php }?>>商品名称</option>
                                <option value="category_id" <?php if (isset($request_data['type']) && $request_data['type'] == 'category_id') {?>selected<?php }?>>商品分类</option>
                            </select>
                        </label>
                        <label>
                            <input class="text" type="text" name="keyword" id="keyword2" value="" />
                        </label>
                        <label>
                            <input type="submit" class="ui-button-small" name="searchbtn" value="搜索">
                        </label>
                </form>
            </div>
                                    
            <form id="product_relations" action="/product/product_auction/put_products" onsubmit='return check();' method="post">
            <div class="division">                
                <table width='100%' style="border:1px solid #cccccc;">
                	<tr>
                        <th style="text-align:left">全选 <input type="checkbox" id="check_all"></th>
                        <th style="text-align:left">体验竞拍 <input type="checkbox" id="check_experience"></th>
                        <th style="text-align:left"><b>商品SKU</b></th>
                        <th style="text-align:left"><b>分类</b></th>
                        <th style="text-align:left"><b>商品名称</b></th>
                    </tr>
                    <?php foreach ($product_list as $product) { ?>
                    <tr>
                     	<td><input class='relation_ids' name="relation_ids[<?php echo $product['id']; ?>]" type="checkbox" value="<?php echo $product['id']; ?>" <?php if (in_array($product['id'], $product_ids)) : ?> checked="checked"<?php endif; ?>></td>
                     	<td><input class='experiences' name="experiences[<?php echo $product['id']; ?>]" type="checkbox" value="1"></td>
                        <td><?php echo html::specialchars($product['sku']); ?></td>
                        <td><?php echo !empty($product['category_id'])?html::specialchars($category_list[$product['category_id']]):'无'; ?></td>
                        <td><?php echo html::specialchars($product['title']); ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                    	<td colspan="6">
                    		<div class="Turnpage_rightper">
				            	<div class="b_r_pager"> <?php echo $this->pagination->render('opococ'); ?> </div>
				        	</div>
                    	</td>
                    </tr>
                </table>
            </div>
            <div class="list_save">
                <input type="submit" class="ui-button" value="  添 加   "/>
                <input id="cancel_product_relation" type="button" class="ui-button" value="  取 消   "/>
            </div>
            </form>
        </div>
<!--**content end**-->
<link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
	url_base = '<?php echo url::base(); ?>';
    function check(){
        var check = false;    
        $('.relation_ids').each(function(idx, item){
    	    if($(item).attr('checked')==true)check=true;
    	});
    	if(check==true){
            return true;
    	}else{
    		parent.showMessage('操作失败', '<font color="#990000">请选择所要竞拍的商品！</font>');
    	}	
        return false;		
    }
    
	$(document).ready(function(){        
		$('#check_all').click(function(){
			var rs = $(this).attr('checked');
			var ps = parent.relation_product_ids;
			$('.relation_ids').each(function(i, item){
				$(item).attr('checked', rs);
				if (rs == true) {
					var relation_id = $(item).val();
					if (typeof ps[relation_id] == 'undefined') {
						ps[relation_id] = true;
					}
				}
				if(rs == false)
                {
                	var relation_id = $(item).val();
                    delete ps[relation_id];
                }
			});
			parent.relation_product_ids = ps;
		});

		$('#check_experience').click(function(){
			var rs = $(this).attr('checked');
			//var ps = parent.check_experiences;
			$('.experiences').each(function(i, item){
				$(item).attr('checked', rs);
				/*if (rs == true) {
					var relation_id = $(item).val();
					if (typeof ps[relation_id] == 'undefined') {
						ps[relation_id] = true;
					}
				}
				if(rs == false)
                {
                	var relation_id = $(item).val();
                    delete ps[relation_id];
                }*/
			});
			//parent.check_experiences = ps;
		});
        
		$('.relation_ids').click(function(){
			var relation_id  = $(this).val();
			var ps = parent.relation_product_ids;
			if ($(this).attr('checked') == true) {
				if (typeof ps[relation_id] == 'undefined') {
					ps[relation_id] = true;
				}
			} else {
				if (typeof ps[relation_id] != 'undefined') {
					delete ps[relation_id];
				}
			}
			parent.relation_product_ids = ps;
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
		
		
		$('#cancel_product_relation').click(function(){
			parent.$('#product_relation_ifm').dialog('close');
		});

		/* 按钮风格 */
        $(".ui-button,.ui-button-small").button();

        var ps = parent.relation_product_ids;
        $('.relation_ids').each(function(idx, item){
            var o = $(item);
            var v = o.val();
			if (typeof ps[v] != 'undefined') {
				o.attr('checked', true);
			} else {
				o.attr('checked', false);
			}
        });
        
        var experience = '<?php echo $experience;?>';
        if(experience==1){
            $('#check_experience').attr('disabled', true);
			$('.experiences').each(function(i, item){
				$(item).attr('readonly', true);
				$(item).attr('checked', true);
            });
        }
	});
</script>