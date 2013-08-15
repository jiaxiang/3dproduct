<div class="division">
  <form action="/product/pdttpl/set" method="GET" target="_parent">
  	<input type="hidden" value="<?php echo $product['id'] ?>" name="product_id" id="product_id">
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<thead>
		<tr>
			<th style="text-align:left;" width="10px"><input type="checkbox" name="checkall" onclick="check_all(this)" style="vertical-align:middle;"></th>
			<th style="text-align:left;" width="80px">商品信息名称</th>
			<th style="text-align:left;">商品信息内容</th>
			<th style="text-align:left;">当前模板内容<?php if(!empty($template)): ?>&nbsp;&nbsp;<a target="_parent" href="/product/pdttpl/delete?template_id=<?php echo $template['id'] ?>" onclick="return user_choose()">删除当前模板</a><?php endif; ?></th>
		</tr>
	</thead>
	<tbody>
		<tr><td><input type="checkbox" name="product[title]"></td>
			<td>商品名称： </td>
			<td><?php echo html::specialchars($product['title']); ?></td>
			<td><?php echo (isset($template['title']) && $template['title']!='') ? $template['title'] : '<font color="Red">尚未设置</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[name_manage]"></td>
			<td>管理名称： </td>
			<td><?php if (isset($product['name_manage'])) : ?><?php echo html::specialchars($product['name_manage']); ?><?php endif; ?></td>
			<td><?php echo (isset($template['name_manage']) && $template['name_manage']!='' )? $template['name_manage'] : '<font color="Red">尚未设置</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[uri_name]"></td>
			<td>商品URL：</td>
			<td><?php if (isset($product['uri_name'])) : ?><?php echo html::specialchars($product['uri_name']); ?><?php endif; ?></td>
			<td><?php echo (isset($template['uri_name']) && $template['uri_name']!='' )? $template['uri_name'] : '<font color="Red">尚未设置</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[category_id]"></td>
			<td>商品分类：</td>
			<td><?php if (isset($product['category_name'])) : ?><?php echo html::specialchars($product['category_name']); ?><?php endif; ?></td>
			<td><?php echo (isset($template['category_name']) && $template['category_name']!='' )? $template['category_name'] : '<font color="Red">尚未设置</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[classify_id]" id="classify_id" onclick="check_features(this)"></td>
			<td>商品类型：</td>
			<td><?php if (isset($product['classify_name'])) : ?><?php echo html::specialchars($product['classify_name']); ?><?php endif; ?></td>
			<td><?php echo (isset($template['classify_name']) && $template['classify_name']!='') ? $template['classify_name'] : '<font color="Red">尚未设置</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[brand_id]"></td>
			<td>商品品牌：</td>
			<td><?php if (isset($product['brand_name'])) : ?><?php echo html::specialchars($product['brand_name']); ?><?php endif; ?></td>
			<td><?php echo (isset($template['brand_name']) && $template['brand_name']!='') ? $template['brand_name'] : '<font color="Red">尚未设置</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[brief]"></td>
			<td>商品简介：</td>
			<td><?php if (isset($product['brief'])) : ?><?php echo html::specialchars($product['brief']); ?><?php endif; ?></td>
			<td><?php echo (isset($template['brief']) && $template['brief']!='')? $template['brief'] : '<font color="Red">尚未设置</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[descsections]"></td>
			<td>商品描述：</td>
			<td><?php if (!empty($product['descsections'][0])) : ?><?php echo $product['descsections'][0]['content']; ?><?php endif; ?></td>
			<td><?php echo (isset($template['descsections']) && $template['descsections']!='') ? $template['descsections'] : '<font color="Red">尚未设置</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[product_featureoption_relation_struct]" onclick="check_class(this)" id="featureoption"></td>
			<td>商品特性：</td>
			<td><?php if (!empty($product['fetuoptrs_v'])) : ?><?php echo $product['fetuoptrs_v'] ?><?php endif; ?></td>
			<td><?php echo (isset($template['fetuoptrs_v']) && $template['fetuoptrs_v']!='' ) ? $template['fetuoptrs_v'] : '<font color="Red">尚未设置</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[goods_price]"></td>
			<td>商品价格($)：</td>
			<td><?php echo $product['goods_price']; ?></td>
			<td><?php echo (isset($template['goods_price']) && $template['goods_price']!=0 )? $template['goods_price'] : '<font color="Red">尚未设置或为默认值</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[goods_market_price]"></td>
			<td>市场价格($)：</td>
			<td><?php echo $product['goods_market_price']; ?></td>
			<td><?php echo (isset($template['goods_market_price']) && $template['goods_market_price']!=0 ) ? $template['goods_market_price'] : '<font color="Red">尚未设置或为默认值</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[goods_cost]"></td>
			<td>成本价格($)：</td>
			<td><?php echo $product['goods_cost']; ?></td>
			<td><?php echo (isset($template['goods_cost']) && $template['goods_cost']!=0 )? $template['goods_cost'] : '<font color="Red">尚未设置或为默认值</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[store]"></td>
			<td>初始库存：</td>
			<td><?php echo $product['store']; ?></td>
			<td><?php echo (isset($template['store']) && $template['store']!=-1 ) ? $template['store'] : '<font color="Red">尚未设置或为默认值</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[goods_weight]"></td>
			<td>商品重量(g)：</td>
			<td><?php echo $product['goods_weight']; ?></td>
			<td><?php echo (isset($template['goods_weight']) && $template['goods_weight']!=0 ) ? $template['goods_weight'] : '<font color="Red">尚未设置或为默认值</font>'?></td>
		</tr>
		<tr><td><input type="checkbox" name="product[on_sale]"></td>
			<td>是否上架：</td>
			<td><?php echo $product['on_sale']==0 ? '否' : '是'; ?></td>
			<td><?php echo isset($template['on_sale'])? ($template['on_sale'] ==0 ? '否' : '是') : '<font color="Red">尚未设置</font>'?></td>
		</tr>
		</tbody>
	</table>
	<input type="submit" value="设置模板" style="height:40px;width:80px;">&nbsp;&nbsp;&nbsp;<input type="reset" value="清除选择" style="height:40px;width:80px;">
  </form>
</div>
<script type="text/javascript">
function user_choose(){
	if(confirm("确定要删除模板吗？")){
		return true;
	}else{
		return false;
	}
}
function check_class(obj){
	if(obj.checked == true){
		document.getElementById('classify_id').checked = true;
	}
}

function check_features(obj){
	if(obj.checked == false){
		document.getElementById('featureoption').checked = false;
	}
}
function check_all(obj){
	var checkboxs = document.getElementsByTagName('input');
	var length = checkboxs.length;
	if(obj.checked){
		for (i=0;i<length;i++){
			if(checkboxs[i].type=="checkbox"){
				checkboxs[i].checked = true;
			}
		}
	}else{
		for (i=0;i<length;i++){
			if(checkboxs[i].type=="checkbox"){
				checkboxs[i].checked = false;
			}
		}
	}
}
</script>
<br><br>