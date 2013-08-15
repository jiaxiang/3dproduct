<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
        <div>
            <!--	<div class="public_title title_h3"></div>	-->
            <form id="product_relations" action="/order/order_product/put" method="post">
            <input id="order_id" name="order_id" type="hidden" value="<?php echo $order['id']; ?>"/>
            <div class="out_box">
                <table id="product_relation_box" cellspacing="0" cellpadding="0" border="0" width="100%" style="border:1px solid #efefef;">
                	<tr>
                        <th class="cell span-6" style="text-align:left"><b>商品名称</b></th>
                        <th class="cell span-4" style="text-align:left"><b>商品SKU</b></th>
                        <th class="cell span-2" style="text-align:left"><b>折扣价格</b></th>
                        <th class="cell span-2" style="text-align:left"><b>商品价格</b></th>
                        <th class="cell span-3" style="text-align:left"><b>数量</b></th>
                        <th class="cell span-2" style="text-align:left"><b>库存</b></th>
                    </tr>
                    <?php foreach ($good_data as $good) { ?>
                    <tr>
                     	<input name="good_id[]" type="hidden" value="<?php echo $good['id'];?>">
                        <td class="cell span-6"><?php echo html::specialchars($good['title']); ?></td>
                        <td class="cell span-4"><?php echo html::specialchars($good['sku']); ?></td>
                        <td class="cell span-2 d_line"> <input type="text" id="discount_price_<?php echo $good['id'];?>" name="discount_price[]" class="text" size="6" value="<?php echo html::specialchars($good['price']);?>"></td>
                        <td class="cell span-2"><?php echo html::specialchars($good['price']);?></td>
                        <td class="cell span-3 d_line"><input type="text" id="amount_<?php echo $good['id'];?>" class="text" size="6" name="amount[]" value="1"></td>
                        <td class="cell span-2"><span id="good_store_<?php echo $good['id'];?>" value="<?php echo ($good['store']==-1)?999:$good['store'];?>"><?php echo ($good['store']==-1)?999:$good['store'];?></span></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <div class="list_save">
                <input id="add_product_relation" type="submit" class="ui-button" value="  确定添加   "/><span class="required">&nbsp;&nbsp;&nbsp;&nbsp;价格单位为：USD</span>
            </div>
            </form>
        </div>
<!--**content end**-->
<link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#product_relations').validate();
		
		var t = $('#product_relation_box').find('input[name="discount_price[]"]');
		t.each(function(){
		    $(this).rules('add', {
		    	required: true,
		    	number: true,
		        min: 0,
		        max: 9999999.99,
				messages: {
					required: '必填',
					number: '必须为数字',
					min: '不可小于0',
					max: '不可大于 9999999.99'
				}
			});
		});

		var t1 = $('#product_relation_box').find('input[name="amount[]"]');
		t1.each(function(){
		    $(this).rules('add', {
		    	required: true,
		    	digits: true,
		        min: 1,
		        quantityNumlimit:true,
				messages: {
					required: '必填',
					digits: '必须为正整数',
					min: '不可小于1',
					quantityNumlimit:'输入数量太大'
				}
			});
		});

		jQuery.validator.addMethod("quantityNumlimit", function (value, element){
		var id = $(element).attr('id').split('_')[1];
		var tt = $("#good_store_"+id).attr('value');
		var lable = true;	
		if((value - tt) > 0){
			lable = false;
			return false;
		}
		return this.optional(element) || lable;       
		} ,  "输入数量太大" );

		/* 按钮风格 */
        $(".ui-button,.ui-button-small").button();
	});
</script>