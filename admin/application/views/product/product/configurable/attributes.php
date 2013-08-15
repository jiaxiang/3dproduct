<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!--**content start**-->
	<?php if (!empty($attributes)) {?>
	<!-- div class="division1" style="border:0px;margin-top:5px;text-align:center;">
		<input id="set_attributes" type="button" class="ui-button" value="  设置规格关联  " class=""/>
     </div -->
     <div class="clear"></div>
      <div class="division" id="tabs" style="margin-top:0;">
          <ul>
          	<?php foreach ($attributes as $attribute) { ?>
              <li><a href="#tabs-<?php echo $attribute['id']; ?>"><?php echo html::specialchars($attribute['name']); ?></a></li>
            <?php } ?>
          </ul>
          <?php foreach ($attributes as $attribute) { ?>
	      <div class="tableform" id="tabs-<?php echo $attribute['id']; ?>">
	      	<div>
	      	<?php
              if ($attribute['type']==0){
                if (isset($attribute['display']) AND $attribute['display'] == 'image') : ?>
	      		<?php foreach ($attribute['options'] as $option) { ?>
	      		<span id="o_<?php echo $attribute['id']; ?>_<?php echo $option['id']; ?>" alt="<?php echo $attribute['id']; ?>-<?php echo $option['id']; ?>" class="attr_add" style="margin-left:6px;margin-top:3px;padding:0 5px;cursor:pointer;"><img title="<?php echo htmlspecialchars($option['name']); ?>" src="<?php echo $option['image'][2]; ?>" border="0" width="20" height="20"/></span>
	      		<?php } ?>
	      	<?php else : ?>
	      		<?php foreach ($attribute['options'] as $option) { ?>
	      		<span id="o_<?php echo $attribute['id']; ?>_<?php echo $option['id']; ?>" alt="<?php echo $attribute['id']; ?>-<?php echo $option['id']; ?>" class="attr_add" style="border:1px solid #ededed;font-size:11px;font-weight:bold;color:#333;margin-left:6px;margin-top:3px;padding:1px 5px;cursor:pointer;"><?php echo html::specialchars($option['name']); ?></span>
	      		<?php } ?>
	      	<?php endif;
                }elseif($attribute['type']==1){
                    echo '<span id="o_'.$attribute['id'].'_0" alt="'.$attribute['id'].'-0" class="attr_add" style="display:none;">顾客输入项</span>';
                }
             ?>
	      	</div>
	      	<div class="division">
	      		<table id="options_<?php echo $attribute['id']; ?>" width="100%" style="border-top:1px solid #efefef;">
	      			<tr>
	      				<th style="text-align:left"><b>ID</b></th>
	      				<th style="text-align:left"><b>名称</b></th>
	      				<!-- th style="text-align:left;"><b>图片关联</b></th -->
	      				<th style="text-align:left"><b>操作</b></th>
	      			</tr>
	      		</table>
	      	</div>
	      </div>
	      <?php } ?>
	  </div>
	  <div class="division1" style="border:0px;margin-top:5px;text-align:center;">
		<input id="create_goods" type="button" class="ui-button" style="display:none;" value="自动生成货品"/>
	    <input id="save_attributes" type="button" class="ui-button" style="display:none;" value="  保存  "/>
	    <input id="close_attributes" type="button" class="ui-button" value="  取消  "/>
	</div>
	<?php } else { ?>
	    <script type="text/javascript">
	    	location.href='<?php echo url::base(); ?>product/product/attrrelation';
		</script>
	<?php } ?>
<div id="pdt_picrelation_ifm" class="ui-dialog-content ui-widget-content" style="display:none;">
    <iframe style="border:0px;width:100%;height:95%;" frameborder="0" src="" scrolling="auto"></iframe>
</div>
<!--** app content end**-->
<!--**content end**-->
<link type="text/css" href="<?php echo url::base(); ?>css/redmond/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo url::base(); ?>js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">
function arrRemove(array, value) {
    for(var i=0, n=0; i<array.length; i++) {
        if(array[i]!=value)
        {
            array[n++]=array[i];
        }
    }
    array.length -= 1;  
    return array;  
}

/**
 * 商品图片关联对象
 */
var picrelation = function() {
	var picres = {};
	var t = {
		show: function(save_handler, pids) {
			switch (typeof pids) {
				case 'undefined':
					pids = '';
					break;
				case 'string':
					break;
				case 'object':
					pids = pids.join(',');
			}
			
			var url = url_base + 'product/product/picrelation?product_id=' + $('#product_id').val() + '&save_handler=' + save_handler + '&pids=' + pids;
			$('#pdt_picrelation_ifm').find('iframe').attr('src', url);
			$('#pdt_picrelation_ifm').dialog('open');
		},
		hide: function() {
			$('#pdt_picrelation_ifm').dialog('close');
		}
	}
	return t;
}();

/* document dom ready */
$(function() {
    /* back button */
    $("button[name='goback'],input[name='goback']").click(function(e){
        history.go(-1);
        if(e){ e.preventDefault(); }
        return false;
    });
    /* ui effect */
    $('.ui-state-default').hover(
            function(){
                $(this).addClass("ui-state-hover");
            },
            function(){
                $(this).removeClass("ui-state-hover");
            }
        ).mousedown(function(){
            $(this).addClass("ui-state-active");
        }).mouseup(function(){
            $(this).removeClass("ui-state-active");
    });
});

attributes = <?php echo json_encode($attributes); ?>;
attroptrs  = {};

/**
 * 检查 配置项设置是否不完整
 */
function checkAttributes(attributes)
{
	if (parent.$.count(attributes) != parent.$.count(attroptrs)) {
		return false;
	}
	for (var k in attroptrs) {
		if (typeof attroptrs[k] == 'undefined' || attroptrs[k].length == 0) {
			return false;
		}
	}
	return true;
}

// custom
$(document).ready(function(){
	$('#tabs').tabs({selected:0});
	
	$('.attr_add').click(function(){
		var ids  = $(this).attr('alt').split('-');
		var name = $(this).html();

		var attribute_id = ids[0];
		var option_id    = ids[1];
        
		if (typeof attroptrs[attribute_id] == 'undefined') {
			attroptrs[attribute_id] = [];
		}
		
		if ($.inArray(option_id, attroptrs[attribute_id]) > -1) {
			parent.showMessage('操作失败', '<font color="#990000">已添加过该规格项，请勿重复添加！</font>');
		} else {
			attroptrs[attribute_id].push(option_id);
            
			var tr = $('<tr></tr>');
			tr.append('<td>' + attribute_id + '-' + option_id + '</td>');
			tr.append('<td>' + name + '</td>');
			/* 删除图片选择
            var td = '';
			td += '<td><div style="cursor:pointer;border:0px;" name="a_' + attribute_id + '_' + option_id + '">';
			if (parent.attroptpicrs.is(attribute_id, option_id)) {
				td += '<img src="<?php echo url::base(); ?>images/icon/picthumb.gif" border="0" width="23" height="23"/>';
			} else {
				td += '<img src="<?php echo url::base(); ?>images/choose_black.gif" border="0"/>';
			}
			td += '</div></td>';
			tr.append(td);*/
            if(option_id==0){
                tr.append('<td>--</td>');
            }else{
			    tr.append('<td><a href="#"><img src="<?php echo url::base();?>images/icon/remove.gif" border="0" width="12" height="12"/></a></a></td>');
			}
			tr.find('a').unbind().bind('click', function(){
				var ids = $(this).parent().prev().prev().html().split('-');
				//if (!parent.ptype_simple.isAttroptRelGood(ids[0],ids[1])) {
					var key = $.inArray(ids[1], attroptrs[ids[0]]);
					if (key > -1) {
						//delete attroptrs[ids[0]][key];
                        attroptrs[ids[0]] = arrRemove(attroptrs[ids[0]], ids[1]);
						$(this).parent().parent().remove();
					} else {
						alert('Internal error.');
					}
				//} else {
				//	parent.showMessage('删除失败', '<font color="#990000">该规格项已与货品关联，请删除所关联的货品，然后重新尝试！</font>');
				//}
				return false;
			});
			
			tr.find('div').unbind().bind('click', function(){
				var o = $(this);
				parent.attroptpicrs.show(attribute_id, option_id, function(aid, oid, pids){
					if (typeof pids == 'object' && pids.length > 0) {
						o.html('<img src="<?php echo url::base(); ?>images/icon/picthumb.gif" border="0" width="23" height="23"/>');
					} else {
						o.html('<img src="<?php echo url::base(); ?>images/choose_black.gif" border="0"/>')
					}
				});
			});
			
			$(this).parent().next().find('table').append(tr);
		}
	});
	
	var set_attroptpicrs = function() {
		var url = '';
		for (var aid in attroptrs) {
			if (typeof attroptrs[aid] != 'undefined') {
				for (var i = 0; i < attroptrs[aid].length; i ++) {
					var oid = attroptrs[aid][i];
					var key = 'a_' + aid + '_' + oid;
					if (parent.attroptpicrs.is(aid, oid)) {
						var pics = parent.attroptpicrs.get(aid, oid);
						if (pics.length > 0) {
							pics = pics.join(',');
							url += '&picrels[' + aid + '][' + oid + ']=' + pics;
						}
					}
				}
			}
		}
		if (url.length > 0) {
			url = url_base + 'product/product/set_attroptpicrs?product_id=' + parent.$('#product_id').val() + url;
			$.ajax({
				url: url,
				type: 'GET',
				dataType: 'json',
				success: function(retdat, status) {
					if (retdat['code'] != 200 || retdat['status'] != 1) {
						parent.showMessage('保存失败', '<font color="#990000">保存商品规格与图片关联失败！</font>');
					}
				},
				error: function() {
					parent.showMessage('保存失败', '<font color="#990000">保存商品规格与图片关联失败！</font>');
				}
			});
		}
	}
	
	$('#create_goods').click(function(){
		//if (checkAttributes(attroptrs)) {
        if (checkAttributes(attributes)) {
			//set_attroptpicrs();
			parent.ptype_simple.setAttrs(attributes);
			parent.ptype_simple.setAttroptRels(attroptrs);
			parent.ptype_simple.setGoods();
			parent.ptype_simple.hide();
		} else {
			parent.showMessage('操作失败', '<font color="#990000">规格项尚未设置完成！</font>');
		}
	});

	$('#save_attributes').click(function() {
		if (checkAttributes(attributes)) {
			//set_attroptpicrs();
			parent.ptype_simple.setAttrs(attributes);
			parent.ptype_simple.setAttroptRels(attroptrs);
			parent.ptype_simple.hide();
		} else {
			parent.showMessage('保存失败', '<font color="#990000">规格项尚未设置完成！</font>');
		}
	});

	$('#close_attributes').click(function(){
		parent.ptype_simple.hide();
	});

	$('#set_attributes').click(function(){
		var items = $('div[id^="tabs-"]');
		var attribute_ids = '';
		items.each(function(i, item){
			var id = $(item).attr('id').split('-')[1];
			if (attribute_ids == '') {
				attribute_ids += id;
			} else {
				attribute_ids += ',' + id;
			}
		});
		location.href = url_base + 'product/product/attrrelation?&aids=' + attribute_ids;
	});

	/**
	 * 初始化
	 */
	for (var aid in attributes) {
		var options = attributes[aid]['options'];
        if(attributes[aid]['type']==1
             //&& parent.ptype_simple.isAttroptRel(aid, 0)
            ){
            $('#o_' + aid + '_0').click();
            continue;
        }
		for (var oid in options) {
			if (parent.ptype_simple.isAttroptRel(aid, oid)) {
				$('#o_' + aid + '_' + oid).click();
			}
		}
	}
	
	if (parent.ptype_simple.getGoodCount() > 0) {
		$('#save_attributes').show();
	} else {
		$('#create_goods').show();
	}
	
	$('#product_id').val(parent.$('#product_id').val());
	
	/* 按钮风格 */
    $(".ui-button,.ui-button-small").button();
});
</script>