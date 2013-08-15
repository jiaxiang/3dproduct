<?php defined('SYSPATH') OR die('No direct access allowed.');?>
<!-- header_content -->
  <div class="new_sub_menu">
    <div class="new_sub_menu_con">
      <div class="newgrid_tab fixfloat">
        <ul>
          <li class="on">配送方式</li>
        </ul>
       </div>
    </div>
  </div>
<!-- header_content(end) -->
<!--** content start**-->
<form id="add_form" name="add_form" method="post" action="<?php echo url::base();?>site/deliverycn/do_edit">
  <input id="deliverycn_id" name="deliverycn_id" value="<?php echo $data['id']; ?>" type="hidden"/>
  <div class="out_box"> 
    <!--** basic information start**--> 
    <div class="new_order_con fixfloat ">
    <p class="right"><a href="http://help.b2c.bizark.cn/archives/2077" target="_blank" title="点击查看使用公式帮助"><img src="/images/tips_help.gif" /></a></p>   
      <table cellspacing="0" class="table_overflow">
        <col width="150">
        <col />
          <tr>
            <td class="a_right a_title">配送方式名称<span class="required"> *</span>：</td>
            <td><input type="text" name="name" class="text input_300px required" value="<?php echo $data['name'];?>" maxlength="100"/></td>
          </tr>
          <tr>
            <td class="a_right a_title">链接地址<span class="required"> *</span>：</td>
            <td><input type="text" name="url" class="text input_300px required url" value="<?php echo $data['url'];?>" maxlength="200"/></td>
          </tr>
          <tr>
            <td class="a_right a_title">重量设置：</td>
            <td>首重重量<select id="first_unit" name="first_unit">
                <?php foreach($unit_list as $key=>$value) :?>
                <option value="<?php echo $value['unit'];?>" <?php if ($value['unit'] == $data['first_unit']) : ?> selected<?php endif; ?>><?php echo $value['name'];?></option>
                <?php endforeach;?>
              </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              续重单位<select id="continue_unit" name="continue_unit">
                <?php foreach($unit_list as $key=>$value) :?>
                <option value="<?php echo $value['unit'];?>" <?php if ($value['unit'] == $data['continue_unit']) : ?> selected<?php endif; ?>><?php echo $value['name'];?></option>
                <?php endforeach;?>
              </select>
              </td>
          </tr>
          <tr>
            <td class="a_right a_title_last">地区费用类型：</td>
            <td id="region_control"><input type="radio" name="type" value="0" <?php if ($data['type'] == 0) : ?> checked<?php endif; ?>/> 统一设置         &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="type" value="1" <?php if ($data['type'] == 1) : ?> checked<?php endif; ?>/> 指定配送地区和费用 </td>
          </tr>
       
      </table>
    </div>
    <!--** basic information end**-->
    <!--** delivery default information start**-->
    
    <div id="delivery_default" class="new_order_con fixfloat " style="display:<?php if($data['type'] == 1) :?>none<?php endif;?>" >
      <table cellspacing="0" class="table_overflow">
      	<col width="150">
        <col />
         <tr>
            <td class="a_right a_title_last">配送费用<span class="required"> *</span>：</td>
            <td>
            <div class="delivery_expbox">
	            <div class="expression_validate_1 deliveryexp <?php if($data['use_exp'] == 1) :?>no_display<?php endif;?>"> 
	            	首重费用 <input type="text" name="first_price" class="text input_40px <?php if($data['type'] == 0 && $data['use_exp'] == 0) :?>required<?php endif;?>" <?php if($data['type'] == 1) :?>disabled="true"<?php endif;?> value="<?php if($data['is_default'] == 1) {echo $data['first_price'];}?>"/> 
	            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	            	续重费用 <input type="text" name="continue_price" class="text input_40px <?php if($data['type'] == 0 && $data['use_exp'] == 0) :?>required<?php endif;?>" <?php if($data['type'] == 1) :?>disabled="true"<?php endif;?> value="<?php if($data['is_default'] == 1) {echo $data['continue_price'];}?>" />
	            	<span class="delivery_msg" value="2" onclick="javascript:changeExp(this);"><a>使用公式</a></span>
	            </div> 
	            <div class="expression_validate_2 deliveryexp <?php if($data['use_exp'] == 0) :?>no_display<?php endif;?>"> 
	            	配送公式<input type="text" name="expression" class="text input_300px <?php if($data['type'] == 0 && $data['use_exp'] == 1) :?>required checkExp1 checkExp2<?php endif;?>" <?php if($data['type'] == 1) :?>disabled="true"<?php endif;?> <?php if($data['use_exp'] == 1) :?>value="<?php echo $data['expression'] ;?>"<?php endif;?>/> 	            	
	            	<input type="button" name="validate_expression0" class="ui-button-small ui-widget ui-state-default" value="验证"/>
	            	<input type="hidden" name="use_exp" <?php if($data['type'] == 1) :?>disabled="true"<?php endif;?> value="<?php echo $data['use_exp'] ;?>"></input> <span class="delivery_msg" value="1" onclick="javascript:changeExp(this);"><a>取消公式</a></span>
	            </div>
            	<input type="hidden" name="is_default" <?php if($data['type'] == 1) :?>disabled="true"<?php endif;?> value="1"/>
            </div> 
            </td>
         </tr>
      </table>
    </div>
    <!--** delivery default information start**-->
    <!--** delivery open default price information start**-->
    <div id="open_default_price" class="new_order_con fixfloat" style="display:<?php if($data['type'] == 0) :?>none<?php endif;?>">
      <table cellspacing="0" class="table_overflow">
      <col width="150">
        <col />
         <tr>
            <td class="a_right a_title"></td>
            <td><input type="checkbox" name="is_default" value="1" <?php if($data['type'] == 1 && $data['is_default'] == 1) :?>checked="true"<?php endif;?> <?php if($data['type'] == 0) :?>disabled="true"<?php endif;?>/> 启用默认费用
             <span class="delivery_msg">注意：未启用默认费用时，不在指定配送地区的顾客不能使用本配送方式下订单</span>
            </td>
         </tr>
         <tr id="set_default_price" <?php if($data['type'] == 0 || $data['is_default'] == 0) :?>class="no_display"<?php endif;?>>
            <td class="a_right a_title_last">配送费用<span class="required"> *</span>：</td>
            <td>
            <div class="delivery_expbox">
	            <div class="expression_validate_1 deliveryexp <?php if($data['use_exp'] == 1) :?>no_display<?php endif;?>"> 
	            	首重费用 <input type="text" name="first_price" class="text input_40px <?php if($data['type'] == 1 && $data['is_default'] == 1 && $data['use_exp'] == 0) :?>required<?php endif;?>" <?php if($data['type'] == 0) :?>disabled="true"<?php endif;?> value="<?php if($data['is_default'] == 1) {echo $data['first_price'];}?>"/> 
	            	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	            	续重费用 <input type="text" name="continue_price" class="input_40px text <?php if($data['type'] == 1 && $data['is_default'] == 1 && $data['use_exp'] == 0) :?>required<?php endif;?>" <?php if($data['type'] == 0) :?>disabled="true"<?php endif;?> value="<?php if($data['is_default'] == 1) {echo $data['continue_price'];}?>"/>
	            <span class="delivery_msg" value="2" onclick="javascript:changeExp(this);"><a>使用公式</a></span>
	            </div> 
	            <div class="expression_validate_2 deliveryexp <?php if($data['use_exp'] == 0) :?>no_display<?php endif;?>"> 
	            	配送公式<input type="text" name="expression" class="text input_300px <?php if($data['type'] == 1 && $data['is_default'] == 1 && $data['use_exp'] == 1) :?>required checkExp1 checkExp2<?php endif;?>" <?php if($data['type'] == 0) :?>disabled="true"<?php endif;?> value="<?php if($data['use_exp'] == 1) { echo $data['expression'] ;}?>"/> 
	            	<input type="button" name="validate_expression1" class="ui-button-small ui-widget ui-state-default" value="验证"/>
	            	<input type="hidden" name="use_exp" class="text input_300px" <?php if($data['type'] == 0) :?>disabled="true"<?php endif;?> value="<?php echo $data['use_exp']?>"/>
	            	<span class="delivery_msg"  value="1" onclick="javascript:changeExp(this);"><a>取消公式</a></span>
	            </div>
            </div>
            </td>
         </tr>
      </table>
    </div>
    <!--** delivery open default price information end**-->
    <!--** delivery set custom information start**-->
    <div id="set_region_price" class="new_order_con fixfloat " style="display:<?php if($data['type'] == 0) :?>none<?php endif;?>">
      <table cellspacing="0" class="table_overflow">
      <col width="150">
        <col />
         <tr>
            <td class="a_right a_title_last v_top">支持的配送地区<span class="required"> *</span>：</td>
            <td>
            	<div>
                	<ol id="region_group_manage" class="decimal">
                    </ol>
                </div>
                
                <span id="add_region_section" class="sysiconBtn">为指定的地区设置运费</span>
            </td>
         </tr>
        
      </table>
    </div>
    <!--** delivery set custom information start**-->
    <!--** other information start**-->
    <div class="new_order_con fixfloat ">
      <table cellspacing="0" class="table_overflow">
      <col width="150">
        <col />
         <tr>
            <td class="a_right a_title">详细介绍：</td><!-- <td class="d_line"> -->
            <td><textarea id="content" name="delay" cols="75" rows="5" class="text" type="textarea" maxlength="255" ><?php echo $data['delay']?></textarea>
            <span class="brief-input-state notice_inline">关于物流方式的介绍，请不要超过255字节。</span></td>
         </tr>
         <tr>
            <td class="a_right a_title">排序<span class="required"> *</span>：</td>
            <td><input type="text" id="position" name="position" class="input_40px required" value="<?php echo $data['position']?>"/></td>
         </tr>
         <tr>
            <td class="a_right a_title_last">状态：</td>
            <td><input type="radio" name="active" value="1" <?php if ($data['active'] == 1) : ?> checked<?php endif; ?>/> 启动 <input type="radio" name="active" value="0" <?php if ($data['active'] == 0) : ?> checked<?php endif; ?>/> 关闭 </td>
         </tr>
      </table>
    </div>
    <!--** other information end**-->    
  </div> 

  <div class="list_save">
      <input type="button" name="button" class="ui-button" value="保存返回列表"  onclick="submit_form();"/>
      <input type="button" name="button" class="ui-button" value="保存当前"  onclick="submit_form(1);"/>
      <input type="hidden" name="submit_target" id="submit_target" value="0" />
  </div>
</form>
  <!--** content end**-->
  <div id="validate_expression_ifm">
    <div class="out_box">
    <h3 class="title1_h3">您可以在这里测试配送公式是否计算正确、有效 </h3>
        <table width="90%" border="0" cellpadding="0" cellspacing="0" >
                <tr>
                    <th width="15%">配送公式：</th>
                    <td>
                        <input size="20" id="expression_validate" name="expression_validate" class="text input_300px"  title="配送公式不能为空！" value=""><span class="required"> *</span>
                    </td>
                </tr>
                <tr>
                    <th>商品重量：</th>
                    <td>
                        <input size="20" id="product_weight" name="product_weight" class="text" value="0" title="商品重量不能为空！"> 克
                    </td>
                </tr>
                <tr>
                    <th>订单价格：</th>
                    <td>
                        $ <input size="10" id="order_price" name="order_price" class="text" value="0.00" title="订单价格不能为空！">
                    </td>
                </tr>
                <tr id="expression_result" style="display:none;">
                	<th>计算结果：</th><td id="final_result"></td>
                </tr>
                <tr>
                	<th>提示：</th><td>公式中使用w标识重量，p标识订单价格，若公式中含有“重量”或者“订单价格”的标识，则请在下方对应输入框中输入相应的数据；系统提供默认为0的数据。</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="list_save">
                             <input name="button" type="button" class="ui-button" onclick="countexp();" value=" 计算" />
                        </div>
                    </td>
                </tr>
        </table>
	</div>
  </div>
  <div id="region_sel_ifm" class="ui-dialog-content ui-widget-content" style="height:270px;min-height:100px;width:auto;">
	<iframe style="border:0px;width:100%;height:98%;" frameborder="0" src="" scrolling="auto"></iframe>
  </div>
  <div id="message" class="ui-dialog-content ui-widget-content no_display" style="height:160px;min-height:100px;width:auto;">
    <p id="message_content"></p>
  </div>
  <script type="text/javascript" src="<?php echo url::base();?>js/jquery.validate.js"></script>
<script type="text/javascript">

	url_base = '<?php echo url::base(); ?>';
	selected_region_ids = new Array();

	region_area_id = {};
	
	
    $(document).ready(function(){
    	var label = false;
    	$("#add_form").validate({
            errorPlacement:function(error, element){
                if(element.attr("name") == "delay"){
                    error.appendTo( element.parent());
                }else{
                    error.insertAfter(element)
                }
            }
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

		//地区费用类型选择
		$('#region_control').live('click', function(e){
			var o = $(e.target);
			if (typeof o.attr('type') != 'undefined' && o.attr('type').toUpperCase() == 'RADIO') {
				var v = o.val();
				if (v == 1) {
					$('#open_default_price').show();
					$('#set_region_price').show();
					$('#delivery_default').hide();
					<?php if ($data['use_exp'] == 1) :?>						
						$('#open_default_price').find('.expression_validate_1').addClass('no_display');
				    	$('#open_default_price').find('.expression_validate_2').removeClass('no_display');
				    <?php else : ?>
				    
					<?php endif; ?>

					if(typeof selected_region_ids != 'undefined' && $.count(selected_region_ids) == 0){
						region_sections.add();
					}
					
					$('#open_default_price').find(':input').removeAttr('disabled');
					$('#set_region_price').find(':input').removeAttr('disabled');
					$('#delivery_default').find(':input').attr('disabled', 'true').removeClass('required');
					label = true;
				}else{	
					if(label){
						if (!confirm('切换将导致已增加的地区物流信息丢失？')) {
			                return false;
						}
						label = false;
					}															
					$('#open_default_price').hide();
					$('#set_region_price').hide();					
					$('#delivery_default').show();
					$('#delivery_default').find(':input').removeAttr('disabled');
					<?php if ($data['use_exp'] == 1) :?>
						$('#delivery_default').find('.expression_validate_1').addClass('no_display');
				    	$('#delivery_default').find('.expression_validate_2').removeClass('no_display');
				    	$('#delivery_default').find('input[name="expression"]').addClass('required checkExp1 checkExp2');
				    <?php else :?>
				    	$('#delivery_default').find('input[name="first_price"]').addClass('required');
				    	$('#delivery_default').find('input[name="continue_price"]').addClass('required');					
					<?php endif; ?>
					$('#open_default_price').find(':input').removeClass('required').attr('disabled', 'true');
					$('#set_region_price').find(':input').removeClass('required').attr('disabled', 'true');

					region_sections.unload();																			
				}
			}
		});

		//设置启用默认费用
		$('input[name="is_default"]').live('click keyup',function(){
            if($(this).attr('checked')){
                $('#set_default_price').removeClass('no_display');
                <?php if($data['use_exp'] == 1) :?>
                	$('#set_default_price').find('input[name="expression"]').addClass('required checkExp1 checkExp2');
	                $('#set_default_price').find('input[name="first_price"]').removeClass('required');
	                $('#set_default_price').find('input[name="continue_price"]').removeClass('required');
	            <?php else :?>
		            $('#set_default_price').find('input[name="expression"]').removeClass('required checkExp1 checkExp2');
	                $('#set_default_price').find('input[name="first_price"]').addClass('required');
	                $('#set_default_price').find('input[name="continue_price"]').addClass('required');
                <?php endif;?>
            }else{
            	$('#set_default_price').addClass('no_display');
            	$('#set_default_price').find(':input').removeClass('required');           	
            }
        });

		//正则表达式设置价格必须输入正确的数字
		$('input[name^="first_price"]').live('keyup', function(){
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

	        if (n.indexOf('.') > -1 && n.slice(n.indexOf('.') + 1).length > 2) {
				n = Math.round(n * 100)/100;
	        }
	        $(this).val(n);
		}).rules('add', {
			min: 0,
			number: true,
			messages: {
				required: '费用价格不可为空',
				min: '费用价格不可小于 0',
				number: '费用价格必须为数字'
			}
		});
		
		$('input[name^="continue_price"]').live('keyup', function(){
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

	        if (n.indexOf('.') > -1 && n.slice(n.indexOf('.') + 1).length > 2) {
				n = Math.round(n * 100)/100;
	        }
	        
	        $(this).val(n);
		}).rules('add', {
			min: 0,
			number: true,
			messages: {
				required: '费用价格不可为空',
				min: '费用价格不可小于 0',
				number: '费用价格必须为数字'
			}
		});

		//验证公式的格式
		jQuery.validator.addMethod("checkExp1", function (value, element){
		    var lable = true;
		    var re = new RegExp("/^[^\]\[\}\{\)\(0-9WwPp\+\-\/\*]+$/");
		    if (re.test(value)){
		    	lable = false;
		  	}			
		    return this.optional(element) || lable;       
		} ,  "公式中含有非法字符" );

		jQuery.validator.addMethod("checkExp2", function (value, element){
		    var lable = true;
		    var price = 100;
	        var weight = 100;
	        var str ;
	            str = value.replace(/(\[)/g, "getceil(");
	            str = str.replace(/(\])/g, ")");
	            str = str.replace(/(\{)/g, "getval(");
	            str = str.replace(/(\})/g, ")");
	            str = str.replace(/(W)/g, weight);
	            str = str.replace(/(w)/g, weight);
	            str = str.replace(/(P)/g, price);
	            str = str.replace(/(p)/g, price);
	          try {
	            eval(str);
	            label = true;
	            return true;
	          }catch(e){
	        	label = false;
	            return false;
	          }			
		    return this.optional(element) || lable;       
		} ,  "公式格式不正确" );

		//验证公式的正确性
		$('input[name="product_weight"], input[name="order_price"]').bind('keyup', function(){
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

	        if (n.indexOf('.') > -1 && n.slice(n.indexOf('.') + 1).length > 2) {
				n = Math.round(n * 100)/100;
	        }
	        $(this).val(n);
		});

		//排序规范
		$('#position').live('keyup', function(){
			var v = $(this).val();
			var n = '';
	        var r = /[0-9]/;
	        var b = true;
	        for (var i = 0; i < v.length; i++) {
	            var c = v.slice(i, i + 1);
	            if (r.test(c)) {
	                b = false;
	                n += c;
	            }
	        }

	        $(this).val(n);
		 });   
        
		//区域的操作
	    var region_sections = function(){
			var o = $('#region_group_manage');
			var sections = {};
			var idx = 1;
			var count = 0;
			var k = 0;

			var t = {
				count: function() {
					return count;
				},
				add: function(sec) {
					count++;
					selected_region_ids[idx] = [];
					if(typeof sec != 'undefined' && typeof sec['region_ids'] != 'undefined'){
						var ps = selected_region_ids[idx];
						id = sec['region_ids'].split('-');
						for(var data in id){
							if (typeof ps[id[data]] == 'undefined') {
								ps[id[data]] = true;
							} 
						}
						selected_region_ids[idx] = ps;
					}				
					var box = $('<li id="delivery_area_' + idx + '" class="delivery_area"></li>');
					var row_city = $('<div id="delivery_city_' + idx + '"></div>');
					var row_box = $('<div id="delivery_expbox_' + idx + '"></div>');
					var row_price = $('<div class="expression_validate_1 deliveryexp"></div>');
					var row_exp = $('<div class="expression_validate_2 deliveryexp no_display"></div>');

					row_city.append('<span class="small_btn right"><span id="del_region_' + idx + '" class="del"></span></span>');	
					row_city.append('配送地区 <input type="text" id="region_names_' + idx + '" name="region_names_' + idx + '" class="input_300px text required" value="' + (typeof sec != 'undefined' && typeof sec['region_names'] != 'undefined' ? sec['region_names'] : '') + '" readonly="true" style="background-color:#f1f1f1"/>');
					row_city.append('<span id="sel_region_' + idx + '" class="sel_region"><img src="'+url_base+'images/icon_view.gif"></img></span>');	
					if(typeof sec != 'undefined' && typeof sec['ids'] != 'undefined'){
						row_city.append('<input type="hidden" name="ids_' + idx + '" id="ids_' + idx + '" value="' + sec['ids'] + '" style="width:10px;">');
					}
					if(typeof sec != 'undefined' && typeof sec['disable_names'] != 'undefined' && $.count(sec['disable_names']) > 0){
						row_city.append('&nbsp;&nbsp;&nbsp;<span style="color:#ff0000">' + sec['disable_names'] + '已被管理员禁止使用，将不会调用此物流信息。</span>');
					}		
					row_city.append('<input type="hidden" name="region_ids_' + idx + '" id="region_ids_' + idx + '"  value="' + (typeof sec != 'undefined' && typeof sec['region_ids'] != 'undefined' ? sec['region_ids'] : '') + '" style="width:10px;"><input type="hidden" name="des_indexs[]" value="' + idx + '" style="width:10px;">');
					row_price.append('首重费用 <input type="text" name="first_price_' + idx + '" class="text input_40px required" value="' + (typeof sec != 'undefined' && sec['use_exp'] == '0' && typeof sec['first_price'] != 'undefined' ? sec['first_price'] : '') + '"/>');
					row_price.append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
					row_price.append(' 续重费用 <input type="text" name="continue_price_' + idx + '"  class="text input_40px required" value="' + (typeof sec != 'undefined' && sec['use_exp'] == '0' && typeof sec['continue_price'] != 'undefined' ? sec['continue_price'] : '') + '"/>');
					row_price.append('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
					row_price.append('<span class="delivery_msg" value="2" onclick="javascript:changeExp(this);"><a>使用公式</a></span>');
					row_exp.append('配送公式 <input type="text" name="expression_' + idx + '" class="input_300px text" value="' + (typeof sec != 'undefined' && sec['use_exp'] == '1' ? sec['expression'] : '') + '"/>');
					row_exp.append(' <input type="button" name="validate_expression_' + idx + '" class="ui-button-small ui-widget ui-state-default ui-corner-all" value="验证"/>');
					row_exp.append('<input type="hidden" name="regions_use_exp_' + idx + '" value="' + (typeof sec != 'undefined' && typeof sec['use_exp'] != 'undefined' ? sec['use_exp'] : '0') + '"></input> <span class="delivery_msg"  value="1" onclick="javascript:changeExp(this);"><a>取消公式</a></span>');
					row_box.append(row_price).append(row_exp);
					box.append(row_city).append(row_box);
					o.append(box);
					var number = idx;
					if(typeof sec != 'undefined' && sec['use_exp'] == '1'){
						box.find('.expression_validate_1').addClass('no_display');
				    	box.find('.expression_validate_2').removeClass('no_display');
				    	box.find('input[name="first_price_' + idx + '"]').removeClass('required');
				    	box.find('input[name="continue_price_' + idx + '"]').removeClass('required');
					}
					box.find('span[class="del"]').bind('click', function(){
						if (!confirm('确定要删除吗？')) {
	                        return false;
	                    }
						k = $(this).attr('id').split('_')[2];
						t.del(number);					
					});
					sections[idx] = box;
					idx++;
					return number;
				},
				del: function(idx) {
					if (typeof sections[idx] != 'undefined') {
						if (t.count() > 1) {
							count--;																			
		                    if ($('input[name="ids_' + k + '"]').length > 0) {
		                        var deliverycn_id = $('#deliverycn_id').val();
		                        var ids  = $('input[name="ids_' + k + '"]').val();
		                        var url = url_base + 'site/delivery/del_region?ids=' + ids + '&deliverycn_id=' + deliverycn_id;
		                        $.ajax({
		                            url: url,
		                            type: 'GET',
		                            dataType: 'json',
		                            success: function(retdat, status) {
		                                if (retdat['status'] == 1 && retdat['code'] == 200) {
		                                	sections[idx].remove();
		        							delete sections[idx];
		        							delete selected_region_ids[idx];
		                                } else {
		                                    showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
		                                }
		                            }
		                        });
		                    } else {
		                    	sections[idx].remove();
								delete sections[idx];
								delete selected_region_ids[idx];
		                    }	
						} else {
							showMessage('删除失败', '<font color="#990000">至少保留一项！</font>');
							return false;
						}
					} else {
						return false;
					}
				},
				load: function(secs) {
					for (var i = 0; i < secs.length; i++) {
						t.add(secs[i]);						
					}
					return true;
				},
				unload: function() {
					for (var k in sections) {
						if (typeof  sections[k] != 'undefined' && sections[k]['ids'] == 'undefined') {
							count--;
							sections[k].remove();
							delete sections[k];
						}
					}
					return true;
				}
			};
			return t;
		}();	    

		//增加国家配送
		$('#add_region_section').unbind().bind('click',function(e){
            try{
            	region_sections.add();
            }catch(ex){
                alert(ex);
            }
            if(e){
                e.preventDefault();
            }
            return false;
        });

        // 验证页面窗口
        $("#validate_expression_ifm").dialog({
            title: "验算配送公式",
            modal: true,
            autoOpen: false,
            height: 350,
            width: 600
        });
		
		//打开验证页面
        $('input[name^="validate_expression"]').live('click', function(){
        	var ifm = $('#validate_expression_ifm');
        	var val = $(this).prev().attr('value');
			ifm.find('#expression_validate').attr('value', val);
			ifm.find('#order_price').attr('value', '0.00');
			ifm.find('#product_weight').attr('value', '0');
			ifm.find('#expression_result').hide();
            ifm.dialog('open');
        });

        //选择地区窗口
        $("#region_sel_ifm").dialog({
            title: "选择地区",
            modal: true,
            autoOpen: false,
            height: 600,
            width: 600
        });

        //提示窗口
        $('#message').dialog({
            title: '',
            modal: true,
            autoOpen: false,
            height: 160,
            width: 300,
            buttons: {
                '确定': function(){
                    $('#message').dialog('close');
                }
            }
        });
		
		//打开地区选择页面
        $('input[name^="region_names_"], .sel_region').live('click keyup', function(){
        	region_area_id = $(this).attr('id').split('_')[2];
        	var region_ids = '';
        	var ifm = $('#region_sel_ifm');
        	$('input[name^="region_ids_"]').each(function(idx, item){
                var o = $(item);
                var region_id = o.val();
                var id = o.attr('id').split('_')[2];
                if(region_area_id != id && region_id != ''){
                	if (region_ids != '') {
                    	region_ids += '-';
                    }
                    region_ids += region_id;
                }  
            });
            if($.count(region_ids) > 0){
            	var url = url_base + 'site/deliverycn/sel_region?region_ids='+region_ids;
        	}else{
        		var url = url_base + 'site/deliverycn/sel_region';
        	}
            ifm.find('iframe').attr('src', url);
        	ifm.dialog('open');
        });
        
        //加载存在的地区信息
    	<?php if (isset($region_area) && !empty($region_area)) : ?>
			region_sections.load(<?php echo json_encode($region_area); ?>);
			label = true;
		<?php endif; ?>
	});

    //使用公式按钮切换
    function changeExp(e){
		var id = $(e).attr('value');
		var p = $(e).parent().parent();
		if(p.show()){
	        if(id == 1){
	            p.find('.expression_validate_1').removeClass('no_display');
	            p.find('.expression_validate_2').addClass('no_display');
	            p.find('input[name="use_exp"]').attr('value', '0');
	            p.find('input[name^="regions_use_exp_"]').attr('value', '0');
	            p.find('input[name^="first_price"]').addClass('required');
	            p.find('input[name^="continue_price"]').addClass('required');
	            p.find('input[name^="expression"]').removeClass('required checkExp1 checkExp2');
	        }else if(id == 2){
	        	p.find('.expression_validate_1').addClass('no_display');
	            p.find('.expression_validate_2').removeClass('no_display');
	            p.find('input[name="use_exp"]').attr('value', '1');
	            p.find('input[name^="regions_use_exp_"]').attr('value', '1');
	            p.find('input[name^="first_price"]').removeClass('required');
	            p.find('input[name^="continue_price"]').removeClass('required');
	            p.find('input[name^="expression"]').addClass('required checkExp1 checkExp2');
	        }
		}
    }

    //配送地区获取
    function renderSelRegions(id){
    	var region_ids = '';
    	if(typeof selected_region_ids[id] != 'defined' && $.count(selected_region_ids[id]) > 0){
    		for (var region_id in selected_region_ids[id]) {
        		if (region_ids != '') {
        			region_ids += '-';
        		}
        		region_ids += region_id;
        	}
        	
    		$("#region_ids_"+id).attr('value', region_ids);//alert(region_ids);return;

    		$.ajax({
        		url: url_base + 'site/deliverycn/get_region?region_ids=' + region_ids,
        		type: 'GET',
        		dataType: 'json',
        		success: function(retdat, status) {
        			if (retdat['code'] == 200 && retdat['status'] == 1) {
            			$("#region_names_"+id).attr('value', retdat['content']); 
            			$("#region_names_"+id).removeClass('error');	
            			var parent = $("#region_names_"+id).parent();
            			parent.find('label[class="error"]').hide();				
        			} else {
        				showMessage('操作失败', '<font color="#990000">' + retdat['msg'] + '</font>');
        			}
        		},
        		error: function() {
        			showMessage('请求错误', '<font color="#990000">请稍后重新尝试！</font>');
        		}
        	});
    	}else{
    		$("#region_ids_"+id).attr('value', '');
    		$("#region_names_"+id).attr('value', '');
    	}   	
    }

    /** 验证公式 **/
    function countexp(qd){
  	  var bds = $('input[name="expression_validate"]').val();
  	  if (!qd){
  	      if (bds == ''){
  	        $('input[name="expression_validate"]').focus();
  	        alert("请输入配送公式");
  	        return;
  	      }
  	   }
  	  var re = new RegExp("/^[^\]\[\}\{\)\(0-9WwPp\+\-\/\*]+$/");
  	  if (re.test(bds)){
  	    alert("公式中含有非法字符");
    	$('input[name="expression_validate"]').focus();
  	    return ;
  	  }
  	  var price = $('#order_price').val();
  	  var weight = $('#product_weight').val();
  	  var str ;
  	    str = bds.replace(/(\[)/g, "getceil(");
  	    str = str.replace(/(\])/g, ")");
  	    str = str.replace(/(\{)/g, "getval(");
  	    str = str.replace(/(\})/g, ")");
  	    str = str.replace(/(W)/g, weight);
  	    str = str.replace(/(w)/g, weight);
  	    str = str.replace(/(P)/g, price);
  	    str = str.replace(/(p)/g, price);
  	    try {
  	      eval(str);
  	    }
  	    catch(e){
  	      alert("公式格式不正确");
  	      return;
  	    }
  	  var result = '<b>$'+Math.floor(eval(str)*100+0.01)/100+'</b>';
  	  $("#expression_result").css('display', '');
      $("#final_result").html(result);
  	}

  	//匹配{}里面的值
    function getval(expval){
  	  if (eval(expval) > 0.000001){
  	    return 1;
  	  }else if (eval(expval) >-0.000001&&eval(expval)< 0.000001){
  	    return 1/2;
  	  }else{
  	    return 0;
  	  }
  	}

    //匹配[]里面的值
  	function getceil(expval){
  	  if (eval(expval) > 0){
  	    return Math.ceil(eval(expval)-0.000001);
  	  }else{
  	    return 0;
  	  }
  	}

  	//提示
    function showMessage(title, content) {
        var message = $('#message');
        $('#message_content').html(content);
        message.dialog('option', 'title', title);
        message.dialog('open');
    }
</script>