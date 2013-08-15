<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div id="pdt_binding_ifm" class="ui-dialog-content ui-widget-content" style="display:none;">
    <iframe style="border:0px;width:100%;height:95%;" frameborder="0" src="" scrolling="auto"></iframe>
</div>
<script>
$(document).ready(function(){
    // 图片上传窗口
    $("#pdt_binding_ifm").dialog({
        title: "添加绑定货品",
        modal: true,
        autoOpen: false,
        height: 500,
        width: 800
    });
});
function show_goods_nb_container(){
	$('#pdt_binding_ifm').find('iframe').attr('src', '/product/product/get_goods_not_binded?site_id=<?php echo $product['site_id'] ?>&product_id=<?php echo$product['id']?>');
	$('#pdt_binding_ifm').dialog('open');
}
function hide_goods_nb_container(){
	$('#pdt_binding_ifm').dialog('close');
}
</script>
