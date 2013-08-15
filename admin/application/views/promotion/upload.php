<?php defined('SYSPATH') OR die('No direct access allowed.'); 
$return_data = $return_struct['content'];
?>
<script type="text/javascript">
<?php //if(isset($return_data['option_id'])){?>
		parent.$('#pai').attr('src',"<?php echo $return_data['picurl'];?>");
		parent.$('#pai').css('display',"block");
		parent.$('#banner').attr('value',"<?php echo $return_data['picurl'];?>");

<?php //}else{?>
<?php // }?>
parent.$('#dialog').dialog('close');
</script>