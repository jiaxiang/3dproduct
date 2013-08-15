<!-- dialog form start -->
<?php if(isset($categoryDiaStruct)) extract($categoryDiaStruct,EXTR_OVERWRITE);
$session = Session::instance();
$sessionErrorData = $session->get('sessionErrorData');
if(isset($sessionErrorData) && !empty($sessionErrorData)) extract($sessionErrorData,EXTR_OVERWRITE);

$jsrelated = '';
if(isset($$related_ids_name))
foreach($$related_ids_name as $id){
	$jsrelated .= $id.',';
}
$jsrelated = trim($jsrelated,',');
$jsrelated ='['.$jsrelated.']';
?>
<div title="添加分类" id="<?php echo $dialog_form;?>" style="text-align:left;">
    <div class="division" style="margin-top:0;width:700px">
    <table id="<?php echo $categoryTable;?>" style="width:700px;border:1px solid #ccc;" class="table_overflow">
        <?php echo $tree;?>
    </table>
    </div>
</div>
<!-- dialog form end -->
<script type="text/javascript">
$(document).ready(function(){
	$('#<?php echo $dialog_form;?> img').click(function(){
		var dialog = "<?php echo $dialog_form;?>";
	    fold($(this),dialog);
	});
var related_ids = new Array();
related_ids = <?php echo $jsrelated;?>;
var obj = $(':checkbox[name="<?php echo $related_ids_name.'[]';?>"]');
var length = obj.length;
var relatedLength = related_ids.length;
for(var i = 0;i<length; i++){
	for(var j = 0;j<relatedLength; j++){
		if(obj.eq(i).val() == related_ids[j]){
			obj.eq(i).attr('checked','true');
		}
	}
}
});
</script>