<script language="javascript">
setInterval("refresh()",10000);
function refresh(){
  window.location.reload();
}
</script>
<?php 
foreach ($urls as $row)
{
	echo '<iframe src="'.url::base().$row.'" width = "100%" frameborder="0" scrolling="no" style="display:none"></iframe>';	
}
?>