<?php defined('SYSPATH') OR die('No direct access allowed.'); 
$return_data = $return_struct['content'];
?>
<script type="text/javascript">
parent.$('#category_img').show();
parent.$('#category_img').attr('src','<?php echo $return_data['pic_url']?>');
parent.$('input[name="pic_attach_id"]').val('<?php echo $return_data['pic_attach_id']?>');
parent.$('#dialog').dialog('close');
</script>