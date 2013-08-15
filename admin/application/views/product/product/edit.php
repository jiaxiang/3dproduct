<?php defined('SYSPATH') OR die('No direct access allowed.'); 
$product = $return_struct['content']['product'];
$action = isset($product['id'])?'编辑':'添加';
switch($product['type'])
{
	case ProductService::PRODUCT_TYPE_ASSEMBLY:
		$typename = '组合商品';
		break;
	case ProductService::PRODUCT_TYPE_CONFIGURABLE:
		$typename = '可配置商品';
		break;
	case ProductService::PRODUCT_TYPE_GOODS:
	default:
		$typename = '商品';
		break;
}
?>
<style type="text/css">
    .pdtpic-clicked {
        border: 1px solid #ff0000;
    }
</style>
<script type="text/javascript" src="<?php echo url::base();?>js/jq/plugins/tinymce/tiny_mce.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/product.validator.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/product.js"></script>
<script type="text/javascript" src="<?php echo url::base();?>js/init_tiny_mce.js"></script>
<form id="edit_form" name="edit_form" method="POST" action="<?php echo url::base();?>product/product/post">
    <input type="hidden" id="id" name="id" value="<?php echo isset($product['id'])?$product['id']:''; ?>"/>
    <input type="hidden" id="product_id" name="product_id" value="<?php echo isset($product['id'])?$product['id']:''; ?>"/>
    <input type="hidden" id="configurable_id" name="configurable_id" value="<?php echo isset($product['configurable_id'])?$product['configurable_id']:''; ?>"/>
    <input type="hidden" id="product_type" name="type" value="<?php echo isset($product['type'])?$product['type']:0; ?>">
    <input type="hidden" name="listurl" value="<?php if (isset($listurl)) : ?><?php echo html::specialchars($listurl); ?><?php endif; ?>">
    <div class="new_content">
        <div class="out_box pro_ie6"> 
                <div class="newgrid_tab fixfloat s_pro_tab">
                    <ul>
                        <span class="first"></span>
                        <li class="on">基本信息 *</li>
                        <li>商品图片</li>
                        <li>扩展商品分类</li>
                        <li>扩展商品描述</li>
                        <li>搜索引擎优化</li>
                        <li>相关商品</li>
                        <!-- li>批发设置</li -->
                    </ul> 
                </div> 

            <script type="text/javascript">
            //var global_site_id = '0';
                $(document).ready(function(){
                    $(".s_pro_tab li").each(function(index){
                        $(this).click(function(){
                            $(".s_pro_tab li.on").removeClass("on");
                            $(this).addClass("on");
                            $(".new_pro_cons").removeClass("contentin");
                            $(".new_pro_cons:eq(" + index + ")").addClass("contentin");
                        })
                    });
                    $('#made_date').datepicker({dateFormat:"yy-mm-dd"});
                    $('#quality_date').datepicker({dateFormat:"yy-mm-dd"});
                });
            </script>

            <div class="contentin fixfloat new_pro_cons">
                    <!--**商品通用信息 edit start**-->
                    <div class="division">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                                <tr>
                                    <?php if($product['type']==ProductService::PRODUCT_TYPE_GOODS){ ?>
                                    <th>前台可见</th>
                                    <td>
                                        <input type='radio' name='front_visible' value='1' <?php echo (!isset($product['front_visible']) || $product['front_visible']==1)?'checked':''; ?>>是 &nbsp; &nbsp;
                                        <input type='radio' name='front_visible' value='0' <?php echo (isset($product['front_visible']) && $product['front_visible']==0)?'checked':''; ?>>否 
                                    </td>
                                    <?php }else{ ?>
                                        <th></th><td></td>
                                    <?php } ?>
                                </tr>
                                <tr>
                                    <th>* 商品分类：</th>
                                    <td nowrap="nowrap">
                                            <select style="width: 150px;" class="required text valid" id="default_category_id" name="category_id">
                                                <option value="0">----</option>
                        						<?php echo isset($categorys_tree)?$categorys_tree:''; ?>
                                            </select> 
                                    </td>
                                </tr>
                                <tr>
									<th>* 商品类型：</th>
									<td nowrap="nowrap">
                                        <?php if(!empty($product['id']) && $product['type']==ProductService::PRODUCT_TYPE_CONFIGURABLE): ?>
                                            <input type=hidden id="classify_id" name="classify_id" value="<?php echo isset($product['classify_id'])?$product['classify_id']:'';?>">
    										<?php if (!empty($classifies)) : ?>
    										<?php foreach ($classifies as $classify) : ?>
    										<?php if(isset($classify['id']) && isset($product['classify_id']) && $classify['id'] == $product['classify_id']){ echo html::specialchars($classify['name']);break; }?>
    										<?php endforeach; ?>
    										<?php endif; ?>
                                        <?php else: ?>
    										<select style="width: 150px;" class="required text valid" id="classify_id" name="classify_id">
    											<option value="0">----</option>
    											<?php if (!empty($classifies)) : ?>
    											<?php foreach ($classifies as $classify) : ?>
    											<option value="<?php echo isset($classify['id'])?$classify['id']:''; ?>"<?php if (isset($classify['id']) && isset($product['classify_id']) && $classify['id'] == $product['classify_id']) : ?> selected<?php endif; ?>><?php echo html::specialchars($classify['name']); ?></option>
    											<?php endforeach; ?>
    											<?php endif; ?>
    										</select>
                                        <?php endif; ?>   
									</td>
								</tr>
                                <tr>
                                    <th>商品品牌：</th>
                                    <td><select style="width: 150px;" class="text" name="brand_id" id="brand_id"></select></td>
                                </tr>
                                <tr>
                                  <th>生产日期：</th>
                                  <td><input type="text"  name="made_date" id="made_date"  value="<?php echo isset($product['made_date'])?$product['made_date']:''; ?>" class="text " readonly="true"/></td>
                                </tr>
                                <tr>
                                  <th>质保日期：</th>
                                  <td><input type="text"  name="quality_date" id="quality_date"  value="<?php echo isset($product['quality_date'])?$product['quality_date']:''; ?>"  class="text " readonly="true"/></td>
                                </tr>
                                <tr>
                                  <th>质保期预警百分比：</th>
                                  <td><input type="text"  name="quality_percent" id="quality_percent"  value="<?php echo isset($product['quality_percent'])?$product['quality_percent']:''; ?>" class="text"/>% 
                                       （系统按照[保质期的总天数 x 预警百分比]作为质保期将要到期的依据给予提示。必须填写整数）</td>
                                </tr>
                                <tr>
                                    <th> 计量单位：</th>
                                    <td><input type="input" style="width: 130px;" maxlength="5" size="16" value="<?php echo isset($product['unit'])?html::specialchars($product['unit']):''; ?>" class="text" name="unit" id="unit"></td>
                                </tr>
                                <tr>
                                    <th>* 商品SKU：</th>
                                    <td><input type="input" style="width: 130px;" maxlength="100" size="16" value="<?php echo isset($product['sku'])?html::specialchars($product['sku']):''; ?>" class="text valid" name="sku" id="sku"></td>
                                </tr>
                                <tr>
                                    <th width="15%" class="a_title">* 商品前台名称： </th>
                                    <td><input type="input" style="width: 400px;" maxlength="100" size="60" value="<?php echo isset($product['title'])?html::specialchars($product['title']):''; ?>" class="text" name="title" id="goods_title"></td>
                                </tr>
                                <tr>
                                    <th width="15%" class="a_title"> 商品后台名称： </th>
                                    <td><input type="input" style="width: 400px;" maxlength="100" size="60" value="<?php echo isset($product['name_manage'])?html::specialchars($product['name_manage']):''; ?>" class="text" name="name_manage" id="goods_name_manage"></td>
                                </tr>
                                <tr>
                                    <th>商品URL(唯一)：</th>
                                    <td>
                                        <input type="input" style="width: 400px;" maxlength="255" size="50" value="<?php if (isset($product['uri_name'])) : ?><?php echo html::specialchars($product['uri_name']); ?><?php endif; ?>" class="text valid" name="uri_name" id="uri_name">
                                    </td>
                                </tr>
                
                                <tr>
                                    <th>商品简介：</th>
                                    <td><textarea class="text valid" mce_editable="false" maxth="255" rows="3" cols="75" name="brief"><?php if (isset($product['brief'])) : ?><?php echo html::specialchars($product['brief']); ?><?php endif; ?></textarea></td>
                                </tr>
                                <tr>
									<th>* 商品描述：</th>
									<td>
										<input type="hidden" name="pdtdes_title[0]" value="详细描述">
										<?php if (!empty($product['descsections'][0]) AND !empty($product['descsections'][0]['id'])) : ?><input type="hidden" name="pdtdes_id[0]" value="<?php echo $product['descsections'][0]['id']; ?>"><?php endif; ?>
										<textarea id="pdtdes_content_0" name="pdtdes_content[0]" style="width:100%;" rows="10" class="tinymce"><?php if(!empty($product['descsections'][0])){ echo $product['descsections'][0]['content']; } ?></textarea>
										<input type="hidden" name="pdtdes_position[0]" value="0">
									</td>
								</tr>
                            </tbody>
                        </table>
                    </div>
                                            
                    <div id="product_good_default_info_box" class="division" style="margin-top:0px;border-top:0px none;<?php if (!empty($product['goods']) || $product['type']==ProductService::PRODUCT_TYPE_ASSEMBLY) : ?>display:none;<?php endif; ?>">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                                <tr>
                                    <th width="15%">* 商品价格($)：</th>
                                    <td width="85%"><input type="input" style="width: 130px;" size="10" value="<?php if (isset($product['price'])) : ?><?php echo $product['price']; ?><?php endif; ?>" class="text" name="price" id="price"></td>
                                </tr>
                                <tr>
                                    <th>* 市场价： </th>
                                    <td><input type="input" style="width: 130px;" size="10" value="<?php if (isset($product['market_price'])) : ?><?php echo $product['market_price']; ?><?php endif; ?>" class="text" name="market_price" id="market_price"></td>
                                </tr>
                                <tr>
                                	<th>成本价：</th>
                                	<td><input type="input" style="width: 130px;" size="10" value="<?php if (isset($product['cost'])) : ?><?php echo $product['cost']; ?><?php endif; ?>" class="text" name="cost" id="cost"></td>
                                </tr>
                                <tr>
                                	<th>库存：</th>
                                	<td><input type="input" style="width: 130px;" size="10" value="<?php if (isset($product['store'])) : ?><?php echo $product['store']; ?><?php endif; ?>" class="text" name="store" id="store"></td>
                                </tr>
                                <tr>
                                	<th>重量：</th>
                                	<td><input type="input" style="width: 130px;" size="10" value="<?php if (isset($product['weight'])) : ?><?php echo $product['weight']; ?><?php endif; ?>" class="text" name="weight" id="weight">[单位为克(g)]</td>
                                </tr>
                                <tr>
                                    <th>上架：</th>
                                    <td>
                                        <input type="radio" class="radio" value="1" name="on_sale"<?php if (!isset($product['on_sale']) OR $product['on_sale'] == 1) { ?> checked="checked"<?php } ?>>&nbsp;是
                                        <input type="radio" class="radio" value="0" name="on_sale"<?php if (isset($product['on_sale']) AND $product['on_sale'] == 0) { ?> checked="checked"<?php } ?>>&nbsp;否
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="display: none; margin-top: 0px; border-top: 0px none;" class="division" id="features_box"></div>
                    <div style="display: none; margin-top: 0px; border-top: 0px none;" class="division" id="arguments_box"></div>
                    <?php echo $ptype_layout; ?>
                    <!--**商品通用信息 edit end**-->
            </div>            
            <div class="new_pro_cons fixfloat division">
                <table border=0 class="new_pro_pic_con">
                  <tr>
                  <td width=300>
                        <span class="pro_btn"><a href="#" class="pro_pic_default" id="pdtpic_set_default">设为默认</a><a href="#" class="pro_pic_up" id="pdtpic_upload">上传图片</a></span>           
                        <span class="mid_pic"><a href="" target="_blank"><img id="pdtpic_larger" src=""></a></span>
                  </td>
                  <td>          
                    <ul id="pdtpic_list" class="all_pic"></ul>
                  </td>
                  </tr>          
                </table>               
            </div>                             
            <div class="new_pro_cons fixfloat">
				<div class="division">
					<table id="pdt_category_additional_box" cellspacing="0" cellpadding="0" border="0" width="100%" style="border:1px solid #efefef;">
						<tr>
							<th style="text-align:center;"><input type="checkbox" id="pdt_category_additional_check_all"></th>
							<th style="text-align:left;padding-left:5px;">&nbsp;<b>名称</b></th>
							<th style="text-align:left;padding-left:5px;">&nbsp;<b>别名</b></th>
						</tr>
						<?php if (!empty($categories)) : ?>
						<?php foreach ($categories as $category) : ?>
						<tr>
							<td style="text-align:center;">
								<input type="checkbox" name="pdt_category_additional_id[]"<?php if (isset($product['category_id']) AND $category['id'] == $product['category_id']) : ?> disabled="disabled"<?php endif; ?><?php if (!empty($product['category_ids']) AND in_array($category['id'], $product['category_ids'])) : ?> checked="checked"<?php endif; ?> value="<?php echo $category['id']; ?>">
							</td>
							<td style="padding-left:5px;">
								<?php if ($category['level_depth'] > 1) : ?>
								<?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $category['level_depth'] - 2); ?>|__
								<?php endif; ?>
								<?php echo html::specialchars($category['title']); ?>
							</td>
							<td style="padding-left:5px;">
								<?php echo html::specialchars($category['title_manage']); ?>&nbsp;
							</td>
						</tr>
						<?php endforeach; ?>
						<?php endif; ?>
					</table>
				</div>
				<div class="clear">&nbsp;</div>
			</div>
            
            <div class="new_pro_cons fixfloat">
				<div class="division" id="pdtdes_container">
					<input id="pdtdes_add" name="pdtdes_add" type="button" class="ui-button" value=" 添加商品描述 " style="margin-left:10px;"/>
				</div>
				<div class="clear">&nbsp;</div>
			</div>
                                            
			<div class="new_pro_cons fixfloat">
				<div class="division">
					<table cellspacing="0" cellpadding="0" border="0" width="100%" style="border:1px solid #efefef;">
						<tr>
							<th width="15%">标题：</th>
							<td width="85%">
								<input name="meta_title" type="input" class="text"  value="<?php if (isset($product['meta_title'])) : ?><?php echo html::specialchars($product['meta_title']); ?><?php endif; ?>" style="width:505px;">
							</td>
						</tr>
						<tr>
							<th>关键字：</th>
							<td>
								<textarea name="meta_keywords" cols="75" rows="3" class="tinymce text" type="textarea" maxth="255"  style="width:500px;"><?php if (isset($product['meta_keywords'])) : ?><?php echo $product['meta_keywords']; ?><?php endif; ?></textarea>
							</td>
						</tr>
						<tr>
							<th>描述：</th>
							<td>
								<textarea name="meta_description" cols="75" rows="6" class="tinymce text" type="textarea" maxth="1024"  style="width:500px;"><?php if (isset($product['meta_description'])) : ?><?php echo $product['meta_description']; ?><?php endif; ?></textarea>
							</td>
						</tr>
					</table>
				</div>
				
				<div class="clear">&nbsp;</div>
			</div>
            <div class="new_pro_cons fixfloat" style="padding-top:5px;">&nbsp;
				<input id="pdt_relation_add" type="button" class="ui-button" style="margin-left:10px;" value="    添加    "/>
				<input id="pdt_relation_remove" type="button" class="ui-button" value=" 批量删除 "/>
				<div class="division">
					<table id="pdt_relation_container" cellspacing="0" cellpadding="0" border="0" width="100%" style="border:1px solid #efefef;">
						<tr>
							<th class="span-1" style="text-align:left"><input type="checkbox" id="pdt_relation_check_all"></th>
							<th class="cell span-4" style="text-align:left"><b>商品SKU</b></th>
							<th class="cell span-5" style="text-align:left"><b>商品前台名称</b></th>
							<th class="cell span-4" style="text-align:left"><b>商品后台名称</b></th>
							<th class="cell span-3" style="text-align:left"><b>操作</b></th>
						</tr>
					</table>
				</div>
				<div class="clear">&nbsp;</div>
			</div>
		
            <div class="new_pro_cons fixfloat">
				<div class="division">
				    <table cellspacing="0" cellpadding="0" border="0" width="100%">
						<tbody id="wholesale_sections">
						    <tr>
								<th width="15%"><b>是否启用批发功能？ </b></th>
								<td width="85%" id="wholesale_control">
									<input type="radio" name="set_wholesale_status" value="0"<?php if (!isset($product['is_wholesale']) OR $product['is_wholesale'] == 0) : ?> checked="checked"<?php endif; ?>>&nbsp;禁用<br/>
									<input type="radio" name="set_wholesale_status" value="1"<?php if (isset($product['is_wholesale']) AND $product['is_wholesale'] == 1) : ?> checked="checked"<?php endif; ?>>&nbsp;启用并允许零售<br/>
									<input type="radio" name="set_wholesale_status" value="2"<?php if (isset($product['is_wholesale']) AND $product['is_wholesale'] == 2) : ?> checked="checked"<?php endif; ?>>&nbsp;启用并禁止零售
									<input type="hidden" id="is_wholesale" name="is_wholesale" value="<?php echo isset($product['is_wholesale']) ? $product['is_wholesale'] : 0; ?>">
								</td>
						    </tr>
						    <tr>
								<th><b>优惠类型：</b> </th>
								<td>
									<select id="wholesale_type" name="wholesale_type">
										<option value="0"<?php if (!isset($product['wholesales']['type']) OR $product['wholesales']['type'] == 0) : ?> selected<?php endif; ?>>百分比</option>
										<option value="1"<?php if (isset($product['wholesales']['type']) AND $product['wholesales']['type'] == 1) : ?> selected<?php endif; ?>>减去</option>
										<option value="2"<?php if (isset($product['wholesales']['type']) AND $product['wholesales']['type'] == 2) : ?> selected<?php endif; ?>>减到</option>
									</select>
								</td>
						    </tr>
						    <tr>
						    	<th><b>商品批发：</b></th>
						    	<td>
						    		<b id="add_wholesale_section"><input type="button" class="ui-button" value=" 添加 "></b>
						    		<b id="reset_wholesale_counter" style="display:none;"><input type="button" class="ui-button" value=" 重新计算单价 "></b>
						    	</td>
						    </tr>
						</tbody>
				    </table>
				</div>
				<div class="clear">&nbsp;</div>
			</div>
			<div class="list_save">
                <input name="save_redirect" id="save_redirect" type="hidden" value="0"/>
                <input type="submit" onclick='javascript:$("#save_redirect").val(1);' class="ui-button" value=" 保存当前信息 "/>
                <input type="submit" onclick='javascript:$("#save_redirect").val(2);' class="ui-button" value="保存后继续添加"/>
                <input type="submit" class="ui-button" value="保存后到列表页"/>
            </div>
        </div>
    </div>
</form>
<div id="pdt_picrelation_ifm" class="ui-dialog-content ui-widget-content" style="display:none;">
    <iframe style="border:0px;width:100%;height:95%;" frameborder="0" src="" scrolling="auto"></iframe>
</div>
<div id="pdt_picupload_ifm" class="ui-dialog-content ui-widget-content" style="display:none;height:500px;min-height:500px;width:auto;">
    <iframe style="border:0px;width:100%;height:100%;" frameborder="0" src="" scrolling="auto"></iframe>
</div>
<div id="message" class="ui-dialog-content ui-widget-content" style="height:160px;min-height:100px;width:auto;display:none;">
    <p id="message_content"></p>
</div>
<div id="pdt_relation_ifm" class="ui-dialog-content ui-widget-content" style="width:auto;display:none;">
    <iframe style="border:0px;width:100%;height:95%;" frameborder="0" src="" scrolling="auto"></iframe>
</div>
<script type="text/javascript">
var ptype_configurable = "<?php if(!empty($product['id']) && $product['type']==ProductService::PRODUCT_TYPE_CONFIGURABLE){echo 1;}; ?>";
            
	/**
	 * 商品类型处理对象
	 */
	var classify = function(){
		var selector = '#classify_id';
		var current = $(selector).val();
		var allowed = false;
		var classifies = {};
		var handlers   = {};
		
		var t = {
			handler: function(name, func) {
				handlers[name] = func;
			},
			allowed: function (allow) {
				allowed = allow ? true : false;
				return allowed;
			},
			change: function(classify_id) {
				if (typeof classify_id != 'undefined') {
					if (allowed == true || confirm('是否更新商品类型？如果更新，已设置的商品特性、参数、品牌、货品信息将会丢失！')) {
						allowed = false;
						if (classify_id > 0)
						{                            
    						ajax_block.open();
							$.ajax({
								url: url_base + 'product/product/get_classify?classify_id=' + classify_id+'&type=' + $('#product_type').val(),
								type: 'GET',
								dataType: 'json',
								success: function(retdat, status) {
									ajax_block.close();
									if (retdat['code'] == 200 && retdat['status'] == 1) {
										current = classify_id;
										t.load(retdat['content']);
                                        if(typeof ptype_simple != 'undefined'){ptype_simple.empty();}
									} else {
										showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
									}
									$(selector).val(current);
								},
								error: function() {
									ajax_block.close();
									$(selector).val(current);
	                            	showMessage('操作失败', '<font color="#990000">请稍候重新尝试！</font>');
								}
							});
						} else {
							t.load({
								features: '',
                                attributes:'',    
								//arguments: '',
								brands: '<option value="0">----</option>'
							});
							current = 0;
						}
					}
				}
				$(selector).val(current);
			},
			getCurrent: function(){
				return current;
			},
			load: function(content) {
				// 填充商品特性
				if (typeof content['features'] != 'undefined'){
					var box = $('#features_box');
					if (content['features'] == '') {
						box.hide().empty();
					} else {
						box.show().html(content['features']);
					}
					if (typeof handlers['features'] == 'function') {
						handlers['features'](box, content['features']);
					}
				}
                
				// 填充商品属性
				if (typeof content['attributes'] != 'undefined'){
					var box = $('#attributes_box');
					if (content['attributes'] == '') {
						box.hide().empty();
					} else {
						box.show().html(content['attributes']);
					}
					if (typeof handlers['attributes'] == 'function') {
						handlers['attributes'](box, content['attributes']);
					}
				}
                
				// 填充商品参数
				/*if (typeof content['arguments'] != 'undefined')
				{
					var box = $('#arguments_box');
					if (content['arguments'] == '') {
						box.hide().empty();
					} else {
						box.show().html(content['arguments']);
					}
					if (typeof handlers['arguments'] == 'function') {
						handlers['arguments'](box, content['arguments']);
					}
				}*/
                
				// 填充商品品牌
				if (typeof content['brands'] != 'undefined')
				{
					var box = $('#brand_id');
					box.html(content['brands']);
					box.val("<?php echo isset($product['brand_id'])?json_encode($product['brand_id']):''; ?>");
					if (typeof handlers['brands'] == 'function') {
						handlers['brands'](box, content['brands']);
					}
				}
			}
		};
		
		var categories = <?php echo isset($categories)?json_encode($categories):'[]'; ?>;
		
		$(document).ready(function(){			
            if(!ptype_configurable==1){
    			$('#default_category_id').bind('change', function(){
    				if (confirm('商品分类已改变，是否将商品类型自动更新为商品分类所关联的商品类型？如果更新，已设置的商品特性、参数、品牌、货品信息将会丢失！')) {
    	    			var tv = $(this).val();
    	    			for (var i = 0; i < categories.length; i ++) {
    	    				if (categories[i]['id'] == tv) {
    	    					if (current != categories[i]['classify_id']) {
    	    						classify.allowed(true);
    	    						t.change(categories[i]['classify_id']);
    	    					}
    	    					break;
    	    				}
    	    			}
    				}
    			});
            
    			$(selector).bind('change', function(){
    				t.change($(selector).val());
    			});
            }
		});
		
		return t;
	}();

    /**
     * 初始化默认商品详细描述
     */
    $(document).ready(function(){
    	$('#pdtdes_content_0').rules('add', {
			required: true,
	        maxlength: 65535,
	        messages: {
	            required: '商品描述不可为空',
	            maxlength: '商品描述内容长度不可超过 65535 字节'
	        }
		});
		tinyMCE.execCommand('mceAddControl', true, 'pdtdes_content_0');
    });    
	
	/**
	 * 初始化商品图片
	 */
	<?php if (!empty($product['pictures'])) : ?>
		$(document).ready(function(){
			pictures.load(<?php echo json_encode(array_values($product['pictures'])); ?>);
		});
	<?php else : ?>
		$(document).ready(function(){
			pictures.load([]);
		});
	<?php endif; ?>
	
	/**
	 * 初始化商品扩展详细描述
	 */
	$(document).ready(function() {
		
		var number = 1;
		
		var desc_items = {};
		
		$.validator.addMethod('desc_title', function(value, element, params){
			var o = $(element);
			var n = o.attr('name');
			var i = n.substring(n.indexOf('[') + 1, n.indexOf(']'));
			var id = 'pdtdes_' + i;
			
			if (value == 'Product Detail')
			{
				return false;
			}
			
			for (var k in desc_items) {
				var item = desc_items[k];
				if (id != k && typeof item != 'undefined') {
					if (item.find('input[name^="pdtdes_title"]').val() == value) {
						return false;
					}
				}
			}
			
			return true;
		});


		var sectionAddLayout = function(section) {
		    var retstr = '';
		    if (typeof section['position'] != 'undefined' && section['position'] < 1) {
		        section['position'] = 1;
		    }
		    if (typeof section['id'] != 'undefined') {
		        retstr += '<input type="hidden" name="pdtdes_id['+number+']" value="' + section['id'] + '">';
		    }
		    retstr +='<div id="pdtdes_'+number+'" class="division"><table cellspacing="0" cellpadding="0" border="0" width="100%" style="border:1px solid #efefef;margin-top:4px">';
		    retstr +='    <tbody><tr>';
		    retstr +='    <th width="15%"><b>详细描述：</b></th>';
		    retstr +='    <th width="85%" style="text-align:left;"><div style="float:left;width:50%;"><b>#' + number + '</b></div><div style="text-align:right;float:left;width:50%;">&nbsp;&nbsp;&nbsp;&nbsp;<img name="delete" style="cursor:pointer;" width="12" height="12" border="0" title="删除" src="' + url_base + 'images/icon/remove.gif">&nbsp;</div></th>';
		    retstr +='    </tr><tr>';
		    retstr +='    <th width="15%">标题<span class="required"> *</span>：</th><td width="85%"><input value="';
		    if (typeof section['title'] != 'undefined') {
		        retstr += section['title'];
		    }
		    retstr +='" class="text required" name="pdtdes_title['+number+']" maxlength="100" size="30"></td></tr>';
		    retstr +='    <tr><th width="15%">内容<span class="required"> *</span>：</th><td width="85%">';
		    retstr +='    <textarea id="pdtdes_content_' + number + '" name="pdtdes_content['+number+']" style="width:100%;" rows="10" class="tinymce">';
		    if (typeof section['content'] != 'undefined') {
		        retstr += section['content'];
		    }
		    retstr +='</textarea>';
		    retstr +='    </td></tr><tr><th width="15%">排序<span class="required"> *</span>：</th>';
		    retstr +='    <td width="85%">';
		    retstr +='    <input name="pdtdes_position[' + number + ']" type="text" size="4" class="text required digits" value="';
		    if (typeof section['position'] != 'undefined') {
		        retstr += section['position'];
		    } else {
		        retstr += '1';
		    }
		    retstr +='"/>';
		    retstr +='</td></tr></table></div>';
		
		    $('#pdtdes_container').append(retstr);
		    
		    tinyMCE.execCommand('mceAddControl', true, 'pdtdes_content_' + number);
		
		    $('input[name="pdtdes_title[' + number + ']"]').rules('add', {
		        required: true,
		        maxlength: 100,
		        messages: {
		            required: '标题不可为空',
		            maxlength: '标题长度不可超过 100 字节'
		        }
		    });
		    
		    $('input[name="pdtdes_position[' + number + ']"]').rules('add', {
		        required: true,
		        digits: true,
		        min: 1,
		        messages: {
		            required: '排序不可为空',
		            digits: '排序必须为整数',
		            min: '排序不可小于 1'
		        }
		    });
		    
		    $('textarea[name="pdtdes_content[' + number + ']"]').rules('add', {
		        required: true,
		        maxlength: 65535,
		        messages: {
		            required: '内容不可为空',
		            maxlength: '内容长度不可超过 65535 字节'
		        }
		    });
		
		    number ++;
		}
		
		//新增商品描述选项
	    $('#pdtdes_add').unbind().bind('click keyup', function(e) {
	        try {
	            sectionAddLayout({});
	        } catch(ex) {
	            alert(ex);
	        }
	        if (e) {
	            e.preventDefault();
	        }
	        return false;
	    });
        	    
	    $('div[id^="pdtdes_"]').live('click', function(e){
	        var t = $(this);
	        var o = $(e.target);
	        if (e.target.nodeName.toUpperCase() == 'IMG' && typeof o.attr('name') != 'undefined') {
	            var n = o.attr('name').toUpperCase();
	            if (n == 'DELETE') {
	                if (confirm('确定要删除吗？')) {
	                	var id = t.attr('id');
	                	delete desc_items[id];
	                	t.remove();
	                } else {
	                	return false;
	                }
	            }
	        }
	
	        return true;
	    });
        
	    <?php if (!empty($product['descsections'])) : unset($product['descsections'][0]);?>
		    <?php foreach ((array)$product['descsections'] as $descsection) : ?>
	        	sectionAddLayout(<?php echo json_encode($descsection); ?>);
	    	<?php endforeach; ?>
        <?php endif; ?>
	});
    
	$(document).ready(function(){
		/**
		 * 获取商品价格
		 */
		var getProductPrice = function() {
			var p  = $('#price').val();
			var r1 = /^\d+$/;
			var r2 = /^\d+\.\d+$/;
			if (!r1.test(p) && !r2.test(p)) {
				p = '';
			}
			return p;
		}
		
		var wholesale_sections = function(){
			var o = $('#wholesale_sections');
			var sections = {};
			var idx = 1;
			var count = 0;
			var type = 2;
			var is_required = function() {
				return $('#is_wholesale').val() != '0';
			};
			var t = {
				count: function() {
					return count;
				},
				set_type: function(tv) {
					type = parseInt(tv);
					if (type == 0 || type == 1) {
						$('span[name="wholesale_namev"]').html('批发折扣');
					} else {
						$('span[name="wholesale_namev"]').html('批发价格');
					}
					return type;
				},
				add: function(sec) {
					count++;
					var row = $('<tr></tr>');
					row.append('<th>*&nbsp;<b>#' + idx + '</b>：</th>');
					var box = $('<td></td>');
					box.append('<input type="hidden" name="wholesale_indexs[]" value="' + idx + '">');
					box.append('批发数量&nbsp;<input type="text" class="text" size="8" name="wholesale_num_begin_' + idx + '" value="' + (typeof sec != 'undefined' && typeof sec['num_begin'] != 'undefined' ? sec['num_begin'] : '') + '">');
					box.append('&nbsp;&nbsp;&nbsp;&nbsp;');
					box.append('<span name="wholesale_namev">'+(type==2?'批发价格':'批发折扣')+'</span>&nbsp;<input type="text" class="text" size="8" name="wholesale_value_' + idx + '" value="' + (typeof sec != 'undefined' && typeof sec['value'] != 'undefined' ? sec['value'] : '') + '">');
					box.append('&nbsp;&nbsp;&nbsp;&nbsp;');
					box.append('<img src="' + url_base + 'images/icon/remove.gif" style="cursor:pointer" border="0" width="12" height="12">');
					box.append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
					box.append('单价：<span name="wholesale_price"></span>');
					row.append(box);
					o.append(row);
					var number = idx;
					row.find('img').bind('click', function(){
						t.del(number);
					});
					row.find('input[name="wholesale_num_begin_' + idx + '"]').rules('add', {
						required: is_required,
						min: 1,
						digits: true,
						messages: {
							required: '商品批发 #' + idx + ' 数量不可为空',
							min: '商品批发 #' + idx + ' 数量不可小于 1',
							digits: '商品批发 #' + idx + ' 数量必须为整数'
						}
					});
					row.find('input[name="wholesale_value_' + idx + '"]').bind('keyup', function(){
						var v = $(this).val();
						var p = getProductPrice();
						var v = $(this).val();
				        var n = '';
				        var r = /[0-9\.]/;
				        var b = true;
				        for (var i = 0; i < v.length; i++) {
				            var c = v.slice(i, i + 1);
				            if (r.test(c)) {
				                b = false;
				                n += c;
				            }
				        }
				        if (n.slice(0, 1) == '.') {
							n = '0' + n;
				        }
				        
				        switch (type) {
				        	case 0:
					        	if (parseFloat(n) > 1) {
									if (n.indexOf('.') > -1) {
										n = '0' + n.slice(n.indexOf('.'));
									} else {
										n = '0.';
									}
					        	}
					        	break;
				        	case 1:
					        	if (parseFloat(n) > parseFloat(p)) {
									n = p;
					        	}
					        	break;
					        default:
				        }
				        if (n.indexOf('.') > -1 && n.slice(n.indexOf('.') + 1).length > 2) {
							n = Math.round(n * 100)/100;
				        }
				        $(this).val(n);
				        t.reset_counter(number);
					}).rules('add', {
						required: is_required,
						min: 0,
						number: true,
						messages: {
							required: '商品批发 #' + idx + ' 价格不可为空',
							min: '商品批发 #' + idx + ' 价格不可小于 0',
							number: '商品批发 #' + idx + ' 价格必须为数字'
						}
					});
					sections[idx] = row;
					idx++;
					return number;
				},
				del: function(idx) {
					if (typeof sections[idx] != 'undefined') {
						if (t.count() > 1) {
							count--;
							sections[idx].remove();
							delete sections[idx];
							return true;
						} else {
							showMessage('删除失败', '<font color="#990000">至少保留一项批发区间！</font>');
							return false;
						}
					} else {
						return false;
					}
				},
				load: function(secs) {
					for (var i = 0; i < secs.length; i++) {
						t.reset_counter(t.add(secs[i]));
					}
					return true;
				},
				unload: function() {
					for (var k in sections) {
						if (typeof sections[k] != 'undefined') {
							count--;
							sections[k].remove();
							delete sections[k];
						}
					}
					return true;
				},
				reset_value: function() {
					for (var k in sections) {
						if (typeof sections[k] != 'undefined') {
							sections[k].find('input[name="wholesale_value_' + k + '"]').val('');
						}
					}
					return true;
				},
				reset_counter: function(idx) {
					if (type == 0 || type == 1) {
						var p = parseFloat(getProductPrice());
					}
					var counter = function(type, row) {
						var v = row.find('input[name^="wholesale_value_"]').val();
						var b = row.find('span[name="wholesale_price"]');
						if (v == '') {
							b.html('');
						} else if ((type == 0 || type == 1) && p == '') {
							b.html('<font color="#990000">无法获取商品价格或商品价格填写错误</font>');
						} else {
							if (v.slice(v.length - 1) == '.') {
								v = v.slice(0, v.length - 1);
							}
							v = parseFloat(v);
							switch (type) {
								case 0:
									v = p * v;
									break;
								case 1:
									v = p - v;
									break;
								case 2:
									break;
								default:
							}
							if (v.toString().indexOf('.') > -1) {
								v = Math.round(v * 100)/100;
							}
							b.html('$' + v);
						}
					};
					if (typeof idx != 'undefined') {
						if (typeof sections[idx] != 'undefined') {
							counter(type, sections[idx]);
						} else {
							return false;
						}
					} else {
						for (var k in sections) {
							if (typeof sections[k] != 'undefined') {
								sections[k].find('input[name^="wholesale_value_"]').keyup();
							}
						}
					}
					return true;
				}
			};
			return t;
		}();
	
		$('#wholesale_control').bind('click', function(e){
			var o = $(e.target);
			if (typeof o.attr('type') != 'undefined' && o.attr('type').toUpperCase() == 'RADIO') {
				var v = o.val();
				if (v == 0 && wholesale_sections.count() > 0) {
					if (confirm('确定要禁用批发功能吗？如果禁用，所有商品批发数据将会丢失！')) {
						$('#reset_wholesale_counter').hide();
						wholesale_sections.unload();
					} else {
						return false;
					}
				} else if (v > 0 && wholesale_sections.count() == 0) {
					$('#reset_wholesale_counter').show();
					wholesale_sections.set_type($('#wholesale_type').val());
					wholesale_sections.add();
				}
				$('#is_wholesale').val(v);
			}
		});
		
		$('#add_wholesale_section').bind('click', function(){
			if ($('#is_wholesale').val() == '0') {
				showMessage('添加失败', '<font color="#990000">批发功能处于禁用状态，请首先开启批发功能！</font>');
			} else {
				wholesale_sections.set_type($('#wholesale_type').val());
				wholesale_sections.add();
			}
		});
		
		var last_wholesale_type = <?php echo isset($product['wholesales']['type']) ? '\''.$product['wholesales']['type'].'\'' : '0'; ?>;
		$('#wholesale_type').bind('change', function(){
			if (wholesale_sections.count() > 0) {
				if (confirm('确定要修改优惠类型吗？如果修改，商品批发价格将需要重新设置！')) {
					var v = $(this).val();
					wholesale_sections.set_type(v);
					wholesale_sections.reset_value();
					wholesale_sections.reset_counter();
					last_wholesale_type = v;
				} else {
					$(this).val(last_wholesale_type);
				}
			}
		});
	
		$('#reset_wholesale_counter').bind('click', function(){
			if (wholesale_sections.count() > 0) {
				wholesale_sections.reset_counter();
			}
		});
	
		<?php if (isset($product['wholesales']['items']) AND !empty($product['wholesales']['items']) AND isset($product['is_wholesale']) AND $product['is_wholesale'] > 0) : ?>
			wholesale_sections.set_type(<?php echo $product['wholesales']['type']; ?>);
			$('#reset_wholesale_counter').show();
			wholesale_sections.load(<?php echo json_encode($product['wholesales']['items']); ?>);
		<?php endif; ?>
	});

	/**
	 * 初始化关联商品
	 */
	<?php if (!empty($product['relations'])) : ?>
		$(document).ready(function(){
			relation.load(<?php echo json_encode($product['relations']); ?>);
		});
	<?php endif; ?>   
        
    /**
	 * 初始化商品类型
	 */
    $(document).ready(function(){
    	classify.load(<?php echo json_encode($classify_content); ?>);
    });
             
</script>