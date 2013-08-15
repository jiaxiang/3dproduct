<!-- dialog form start -->
<?php if(isset($goodDiaStruct)) extract($goodDiaStruct,EXTR_OVERWRITE);?>
<div title="添加货品" id="<?php echo $dialog_form;?>" style="text-align:center;">
    <div style="text-align:left;padding:0 0 0 20px;margin:0 0 2px 0;">
	    <label>
	        <select id="<?php echo $goodSearchType;?>" name="<?php echo $goodSearchType;?>" class="text">
	            <option value="sku">货品SKU</option>
	            <option value="title">货品名</option>
	        </select>
	    </label>
	    <label>
	        <input class="text" type="text" name="<?php echo $goodKeyword;?>" />
	        <input class="text" type="hidden" name="hide_tran" id="<?php echo $goodKeyword;?>" value=""/>
	    </label>
	    <label>
	        <input type="button" class="ui-button" id="<?php echo $goodSearchbtn;?>" name="<?php echo $goodSearchbtn;?>" value="搜索">
	    </label>
    </div>
    <div class="division" style="margin-top:0;width:700px" >
    <table id="<?php echo $goodTable;?>" style="width:700px;border:1px solid #ccc;" class="table_overflow">

    </table>
    </div>

</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#<?php echo $goodSearchbtn;?>").click(function(){
    	$('#<?php echo $goodKeyword;?>').attr('value', $("input[name=<?php echo $goodKeyword;?>]").val());
    	//$("input[name=<?php echo $goodKeyword;?>]").val("");
    });
});
</script>