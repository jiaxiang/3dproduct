<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<input type="hidden" value="<?php for ($i=0; $i<count($product['goods']); $i++){ if($i==0){echo $product['goods'][$i]['id'];}else {echo ','.$product['goods'][$i]['id'];} }?>" name="goods_ids" id="goods_ids">

<div style="margin-top: 0px; border-top: 0px none;" class="division" id="product_attributes_btn_box">
    <span class="right">
    <font color="Blue">参考数据</font> &nbsp;&nbsp;
    商品价格:<input type="text" id="goods_price_c" value="0" readonly size="8" >
    市场价:<input type="text" id="goods_market_price_c" value="0" readonly size="8" >
    成本价:<input type="text" id="goods_cost_c" value="0" readonly size="8" >
    库存:<input type="text" id="store_c" value="-1" readonly size="8" >
    商品重量:<input type="text" id="goods_weight_c" value="0" readonly size="8" >
    </span>
    <input onclick="show_goods_nb_container()" type="button" value="添加绑定货品" class="ui-button ui-widget ui-state-default ui-corner-all ui-state-focus" name="add_not_binded_goods" id="add_not_binded_goods" role="button" aria-disabled="false" style="display: inline-block;">
</div>
<script type="text/javascript">
function delete_binded_good(good_id,row,is_binded){
	if(is_binded==1){
		var r=confirm("该货品已经绑定在商品上，如果删除，本次编辑不可恢复！"+ "\n" + "是否删除该货品在商品上的绑定？");
		if (r==false){
			return
		}
	}
	var goods_ids = document.getElementById('goods_ids').value;
	var new_goods_ids = '';
	var arr = goods_ids.split(',');
	if( arr.length > 1 ){
		for(i=0; i<arr.length; i++){
			if( arr[i]!=good_id){
				new_goods_ids = new_goods_ids=='' ? arr[i] : new_goods_ids+','+arr[i]; 
			}
		}
	}else{
		alert('提醒：捆绑商品至少保留一件货品!');
		return;
	}
	document.getElementById('goods_ids').value = new_goods_ids;
	var i=row.parentNode.parentNode.rowIndex;
	
	document.getElementById('binding_box_table').deleteRow(i);
	comput_values();
}

function comput_values(){
	var rows = document.getElementById('binding_box_table').rows
	
	var price = 0;
	var market_price = 0;
	var cost = 0;
	var weight = 0;
	var store = 0;
	for (i=1; i<rows.length; i++){
		price = accAdd(price , Number( rows[i].cells[2].innerHTML ) );
		market_price = accAdd(market_price , Number( rows[i].cells[3].innerHTML ) );
		cost = accAdd(cost , Number( rows[i].cells[4].innerHTML ) );
		weight = accAdd(weight , Number( rows[i].cells[5].innerHTML ) );
		store = Number( rows[i].cells[7].innerHTML );
		if( document.getElementById('store_c').value == -1 || ( document.getElementById('store_c').value > store && store != -1)){
				document.getElementById('store_c').value = store;
		}
	}
	document.getElementById('goods_price_c').value = price;
	document.getElementById('goods_market_price_c').value = market_price;
	document.getElementById('goods_cost_c').value = cost;
	document.getElementById('goods_weight_c').value = weight;
}

function accAdd(arg1,arg2){
    var r1,r2,m;
    try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
    try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
    m=Math.pow(10,Math.max(r1,r2))
    return (arg1*m+arg2*m)/m
}

$(document).ready(function(){
    comput_values()
});

</script>
<div id="binding_box_div" style="<?php if (empty($product['goods'])) : ?>display: none;<?php endif; ?> margin-top: 0px; border-top: 0px none;" class="division" >
<?php
	$goods = new View('product/product/binding/goods_binded');
	$goods->attributes = empty($product['attributes']) ? array() : $product['attributes'];
	$goods->goods      = empty($product['goods']) ? array() : $product['goods'];
	echo $goods;
?>
</div>

<div style="margin-top: 0px; border-top: 0px none;" class="division" id="attributes_box">
<?php
	$goods = new View('product/product/binding/goods_not_binded');
	$goods->product = $product;
	echo $goods;
?>
</div>
