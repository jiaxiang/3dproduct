<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!--**content start**-->
   <div class="out_box">
        <form id="order_product_edit_form" name="order_product_edit_form" method="post" action="<?php echo url::base();?>order/order_product/post">
            <table id="order_product" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                    <tr>
                        <th width="100px">商品名称：</th>
                        <td  class="d_line"><?php echo $data['name']?></td>
                    </tr>
                    <tr>
                        <th>商品SKU：</th>
                        <td  class="d_line"><?php echo $data['SKU']?></td>
                    </tr>
                    <tr>
                        <th>商品折扣价格：</th>
                        <td><?php if($data['store']==0){echo round($data['discount_price']/$data['conversion_rate'],2);}else{?><input type="text" class="text required min max" name="discount_price" size="8" value="<?php echo round($data['discount_price']/$data['conversion_rate'],2)?>"></input><?php }?> <?php echo $data['currency'];?></td>
                    </tr>
                    <tr>
                        <th>商品价格：</th>
                        <td><?php echo round($data['price']/$data['conversion_rate'],2)?> <?php echo $data['currency'];?></td>
                    </tr>
                    <tr>
                        <th>购买数量：</th>
                        <td><?php if($data['store']==0){echo $data['quantity'];}else{?><input type="text" class="text" id="amount_<?php echo $data['id'];?>" name="amount" value="<?php echo $data['quantity']?>"></input><?php }?></td>
                    </tr>
                    <tr>
                        <th>购买库存：</th>
                        <td><span id="good_store_<?php echo $data['id'];?>" value="<?php echo ($data['store']==-1)?999:$data['store'];?>"><?php echo ($data['store']==-1)?999:$data['store'];?></span></td>
                    </tr>
                </tbody>
            </table>
            <div class="list_save">
            	<input name="id" type="hidden" value="<?php echo $data['id']?>"/>
            	<?php if($data['store']!=0):?>
                <input name="submit" type="submit" class="ui-button" value=" 确认修改"/>
                <?php endif;?>
            </div>
        </form>
    </div>
<!--**content end**-->
    <link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
	<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
    <script type="text/javascript">
	$(document).ready(function(){	
		$('#order_product_edit_form').validate({
			rules: {
				discount_price: {
					required: true,
					number: true,
			        min: 0,
			        max: 9999999.99
				},
				amount: { 
					required: true,
			    	digits: true,
			        min: 1,
			        quantityNumlimit:true							
		      	}
			},
			messages: {
				discount_price: {
					required: '必填',
					number: '必须为数字',
					min: '不可小于0',
					max: '不可大于 9999999.99'
				},
				amount: { 
					required: '必填',
					digits: '必须为正整数',
					min: '不可小于1',
					quantityNumlimit:'输入数量太大'								
		      	}
		    }
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
		} ,  "输入数量超过库存的数量" );	

		/* 按钮风格 */
        $(".ui-button,.ui-button-small").button();
	});
</script>