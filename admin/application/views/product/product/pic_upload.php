<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript">
/**
 * ��Ӷ����ƷͼƬ
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