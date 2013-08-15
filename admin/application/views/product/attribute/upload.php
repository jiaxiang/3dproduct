<?php defined('SYSPATH') OR die('No direct access allowed.'); 
$return_data = $return_struct['content'];
?>
<script type="text/javascript">
<?php if(isset($return_data['option_id'])){?>
parent.$('input[name="option_image[<?php echo $return_data['option_id'];?>]"]').next('img').attr('src','<?php echo $return_data['picurl']?>');
parent.$('input[name="option_image[<?php echo $return_data['option_id'];?>]"]').val('<?php echo $return_data['meta']?>');
<?php }else{?>
var number = parent.$('#ifr').attr('number');
//var number = parent.$('#dialog').find('iframe').attr('number');
parent.$('input[num="'+number+'"]').prev('img').attr('src','<?php echo $return_data['picurl']?>');
parent.$('input[num="'+number+'"]').prev('img').prev('input').val('<?php echo $return_data['meta']?>');
<?php }?> 
parent.$('#dialog').dialog('close');
</script>