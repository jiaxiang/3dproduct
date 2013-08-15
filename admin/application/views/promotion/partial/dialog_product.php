<!-- dialog form start -->
<?php if(isset($productDiaStruct)) extract($productDiaStruct,EXTR_OVERWRITE);?>
<div title="添加商品" id="<?php echo $dialog_form;?>">
    <div style="text-align:left;padding:0 0 0 20px;margin:0 0 2px 0;">
	    <label>
	        <select id="<?php echo $productSearchType;?>" name="<?php echo $productSearchType;?>" class="text">
	            <option value="sku">商品SKU</option>
	            <option value="name_manage">中文名称</option>
	            <option value="title">商品名称</option>
	            <option value="category_id">商品分类</option>
	        </select>
	    </label>
	    <label>
	        <input class="text" type="text" name="<?php echo $productKeyword;?>"  />
	        <input class="text" type="hidden" name="hide_tran" id="<?php echo $productKeyword;?>" value=""/>
	    </label>
	    <label>
	        <input type="button" class="ui-button" id="<?php echo $productSearchbtn;?>" name="<?php echo $productSearchbtn;?>" value="搜索">
	    </label>
    </div>
    <div class="division" style="margin-top:0;width:98%">
    <table id="<?php echo $productTable;?>" width='100%'>

    </table>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#<?php echo $productSearchbtn;?>").click(function(){
        $('#<?php echo $productKeyword;?>').attr('value', $("input[name=<?php echo $productKeyword;?>]").val());
        //$("input[name=<?php echo $productKeyword;?>]").val("");
    });
});
</script>