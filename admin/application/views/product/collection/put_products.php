<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<?php $collection_id = $return_struct['content']['collection_id']; ?>
<script type="text/javascript">
    parent.location.href='<?php echo url::base();?>product/collection/products?id=<?php echo $collection_id;?>';
	parent.$('#product_relation_ifm').dialog('close');
</script>