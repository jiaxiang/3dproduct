<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript">
/**
 * 添加多个商品图片
 */
<?php 
if (isset($picture)){ 
    foreach($picture as $pic){
?>
    
parent.pictures.add(<?php echo json_encode($pic); ?>, true);

<?php 
    }
} 
?>
parent.$('#pdt_picupload_ifm').dialog('close');
</script>