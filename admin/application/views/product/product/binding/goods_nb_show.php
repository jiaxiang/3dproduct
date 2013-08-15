<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="public_right public">
    <form action="/product/product/get_goods_not_binded" method="GET" name="search_form" id="search_form">
        <input type="hidden" value="<?php echo $product['site_id'] ?>" id="site_id" name="site_id">
        <input type="hidden" value="<?php echo $product['id'] ?>" id="product_id" name="product_id">
        <p> 搜索:
            <label>
                <select class="text" id="select_type" name="select_type">
                    <option value="product_category" <?php if(isset($request_data['select_type']) && $request_data['select_type'] == 'product_category'){echo 'selected';} ?>>商品分类</option>
                    <option value="product_title" <?php if(isset($request_data['select_type']) && $request_data['select_type'] == 'product_title'){echo 'selected';} ?>>商品名称</option>
                    <option value="product_sku" <?php if(isset($request_data['select_type']) && $request_data['select_type'] == 'product_sku'){echo 'selected';} ?>>商品SKU</option>
                    <option value="goods_title" <?php if(isset($request_data['select_type']) && $request_data['select_type'] == 'goods_title'){echo 'selected';} ?>>货品名称</option>
                    <option value="goods_sku" <?php if(isset($request_data['select_type']) && $request_data['select_type'] == 'goods_sku'){echo 'selected';} ?>>货品SKU</option>
                </select>
            </label>
            <label>
            	<input type="text" name="select_key" id="select_key" value="">
            </label>
            <label>
                <input type="submit" class="ui-button-small" value="搜索" name="searchbtn">
            </label>
		</p>
	</form>
</div>
<div class="division">
<table style="border: 1px solid rgb(239, 239, 239);clear:both;" width="100%">
<tr bgcolor="#DFE2EA">
	<th style="text-align: left;">&nbsp;</th>
	<th style="text-align: left;">商品名称</th>
	<th style="text-align: left;">商品SKU</th>
	<th style="text-align: left;">货品名称</th>
	<th style="text-align: left;">货品SKU</th>
</tr>
<?php if (!empty($product['goods_nb'])) : ?>
<?php foreach ($product['goods_nb'] as $good) : ?>
<tr style="border: 1px solid #DFE2EA;">
	<td><input type="checkbox" name="check_<?php echo $good['id'] ?>" id="check_<?php echo $good['id'] ?>" style="vertical-align:middle;" onclick="manage_bingding_good(<?php echo $good['id'] ?>)">
		<input type="hidden" value="<?php echo $good['title'] ?>" id="title_<?php echo $good['id'] ?>">
		<input type="hidden" value="<?php echo isset($good['product_title'])? $good['product_title'] : '' ?>" id="product_title_<?php echo $good['id'] ?>">
		<input type="hidden" value="<?php echo $good['price'] ?>" id="price_<?php echo $good['id'] ?>">
		<input type="hidden" value="<?php echo $good['market_price'] ?>" id="market_price_<?php echo $good['id'] ?>">
		<input type="hidden" value="<?php echo $good['cost'] ?>" id="cost_<?php echo $good['id'] ?>">
		<input type="hidden" value="<?php echo $good['weight'] ?>" id="weight_<?php echo $good['id'] ?>">
		<input type="hidden" value="<?php echo $good['sku'] ?>" id="sku_<?php echo $good['id'] ?>">
		<input type="hidden" value="<?php echo $good['store'] ?>" id="store_<?php echo $good['id'] ?>">
		<input type="hidden" value="<?php echo isset($good['attribute']) ? $good['attribute'] : '没有规格设置' ?>" id="attribute_<?php echo $good['id'] ?>">
		<input type="hidden" value="<?php echo ($good['on_sale'] == 0) ? '否' : '是' ?>" id="on_sale_<?php echo $good['id'] ?>">
	</td>
	<td><?php echo isset($good['product_title'])? $good['product_title'] : '' ?></td>
	<td><?php echo isset($good['product_sku'])? $good['product_sku'] : '' ?></td>
	<td><?php echo $good['title'] ?></td>
	<td><?php echo $good['sku'] ?></td>
</tr>
<?php endforeach; ?>
<?php else: ?>
<tr>
<tr><td colspan="5" style="text-align:center;"><font color="Red">没有找到可以绑定的货品！</font></td></tr>
</tr>
<?php endif; ?>
</table>
<?php echo $pagination ?>
<br>
<div><input type="button" value="确认" style="width:60px;height:30px;" onclick="javascript:parent.hide_goods_nb_container()" >
	 <input type="button" value="清空" style="width:60px;height:30px;" onclick="clear_all()" >
</div>
</div>

<script type="text/javascript">
var goods_ids_now = new Array(<?php if (!empty($product['goods_nb'])) {$i=0; foreach ($product['goods_nb'] as $good){ echo $i==0 ? $good['id'] : ','.$good['id']; $i++;}} ?>);
window.onload = function(){
	var goods_ids = parent.document.getElementById('goods_ids').value;
	var ids_array = goods_ids.split(',');
	if( ids_array.length >= 1 && goods_ids_now.length >= 1 ){
		for(j=0; j<goods_ids_now.length; j++ ){
			good_id = goods_ids_now[j];
			for(i=0; i<ids_array.length; i++){
				if( ids_array[i] == good_id ){
					document.getElementById('check_' + good_id).checked = true;
					break;
				}
			}
		}
	}
}

function clear_all(){
	for(j=0; j<goods_ids_now.length; j++ ){
		good_id = goods_ids_now[j];
		document.getElementById('check_' + good_id).checked = false;
		cancle_binding_good(good_id);
	}
}

function manage_bingding_good( good_id ){
	if(document.getElementById('check_' + good_id).checked == true){
		insert_to_binding(good_id);
	}else{
		cancle_binding_good(good_id);
	}
}

function cancle_binding_good(good_id){
	var table = parent.document.getElementById('binding_box_table');
	var rows = table.rows;
	var sku_this = document.getElementById( 'sku_' + good_id ).value;
	var row_cancle = 0;
	
	if(rows.length<=2){
		alert('提醒：捆绑商品至少保留一件货品!');
		document.getElementById('check_' + good_id).checked = true;
		return;
	}
	
	for(i=1; i<rows.length; i++){
		if (rows[i].cells[8].innerHTML == sku_this){
			row_cancle = i;
			break;
		}
	}
	if(row_cancle>0){
		parent.document.getElementById('binding_box_table').deleteRow(row_cancle);
		parent.comput_values();
		
		var goods_ids = parent.document.getElementById('goods_ids').value;
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
		parent.document.getElementById('goods_ids').value = new_goods_ids;
	}
}

function insert_to_binding(good_id){
	var goods_ids = parent.document.getElementById('goods_ids').value;
	var ids_array = goods_ids.split(',');
	if( ids_array.length >= 1 ){
		for(i=0; i<ids_array.length; i++){
			if( ids_array[i] == good_id ){
				alert('该货品已经被绑定在该捆绑商品中！');
				return;
			}
		}
	}
	
	var good_title = document.getElementById( 'title_' + good_id );
	var product_title = document.getElementById( 'product_title_' + good_id );
	var price = document.getElementById( 'price_' + good_id );
	var market_price = document.getElementById( 'market_price_' + good_id );
	var cost = document.getElementById( 'cost_' + good_id );
	var weight = document.getElementById( 'weight_' + good_id );
	var store = document.getElementById( 'store_' + good_id );
	var sku = document.getElementById( 'sku_' + good_id );
	var attribute = document.getElementById( 'attribute_' + good_id );
	var on_sale = document.getElementById( 'on_sale_' + good_id );
	
	parent.document.getElementById('binding_box_div').style.display='block';
	
	var col_0 = product_title.value; 
	var col_1 = good_title.value; 
	var col_2 = price.value;
	var col_3 = market_price.value;
	var col_4 = cost.value;
	var col_5 = weight.value;
	var col_6 = attribute.value;
	var col_7 = store.value;
	var col_8 = sku.value;
	var col_9 = on_sale.value;
	var col_10 = "<a href=\"javascript:\" onclick=\"delete_binded_good('"+good_id+"',this)\"><img width=\"12\" height=\"12\" border=\"0\" src=\"/images/icon/remove.gif\" name=\"delete_good\"></a>"
	insert_tr('binding_box_table',col_0,col_1,col_2,col_3,col_4,col_5,col_6,col_7,col_8,col_9,col_10);
	
	parent.document.getElementById('goods_ids').value = parent.document.getElementById('goods_ids').value=='' ? good_id : (parent.document.getElementById('goods_ids').value + ',' + good_id);
	if( parent.document.getElementById('store_c').value == -1 || ( parent.document.getElementById('store_c').value > store.value && store.value != -1)){
		parent.document.getElementById('store_c').value = store.value;
	}
	
	
	parent.document.getElementById('goods_price_c').value = accAdd( Number(parent.document.getElementById('goods_price_c').value) , Number(price.value) );
	parent.document.getElementById('goods_market_price_c').value = accAdd( Number(parent.document.getElementById('goods_market_price_c').value) , Number(market_price.value) );
	parent.document.getElementById('goods_cost_c').value = accAdd( Number(parent.document.getElementById('goods_cost_c').value) , Number(cost.value) );
	parent.document.getElementById('goods_weight_c').value = accAdd( Number(parent.document.getElementById('goods_weight_c').value) , Number(weight.value) );
	
}

function accAdd(arg1,arg2){
    var r1,r2,m;
    try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
    try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
    m=Math.pow(10,Math.max(r1,r2))
    return (arg1*m+arg2*m)/m
}

function insert_tr(table_id,col_0,col_1,col_2,col_3,col_4,col_5,col_6,col_7,col_8,col_9,col_10){
	var tab =parent.document.getElementById( table_id );
	var newRow = tab.insertRow(-1);//添加了一行
	var cell0 = newRow.insertCell(0);//在新行中添加单元格
	var cell1 = newRow.insertCell(1);
	var cell2 = newRow.insertCell(2);
	var cell3 = newRow.insertCell(3);//在新行中添加单元格
	var cell4 = newRow.insertCell(4);
	var cell5 = newRow.insertCell(5);
	var cell6 = newRow.insertCell(6);
	var cell7 = newRow.insertCell(7);
	var cell8 = newRow.insertCell(8);
	var cell9 = newRow.insertCell(9);
	var cell10 = newRow.insertCell(10);
	cell0.innerHTML = col_0;//在新单元格中写入文本
	cell1.innerHTML = col_1;//在新单元格中写入文本
	cell2.innerHTML = col_2;//在新单元格中写入文本
	cell3.innerHTML = col_3;//在新单元格中写入文本
	cell4.innerHTML = col_4;//在新单元格中写入文本
	cell5.innerHTML = col_5;//在新单元格中写入文本
	cell6.innerHTML = col_6;//在新单元格中写入文本
	cell7.innerHTML = col_7;//在新单元格中写入文本
	cell8.innerHTML = col_8;//在新单元格中写入文本
	cell9.innerHTML = col_9;//在新单元格中写入文本
	cell10.innerHTML = col_10;//在新单元格中写入文本
}
</script>
